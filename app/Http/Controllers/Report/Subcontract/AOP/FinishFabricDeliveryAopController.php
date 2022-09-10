<?php
namespace App\Http\Controllers\Report\Subcontract\AOP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use Illuminate\Support\Carbon;

class FinishFabricDeliveryAopController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $user;
	private $buyernature;
	private $itemaccount;
	private $autoyarn;
	private $teammember;
    private $team;
    private $gmtspart;
	private $sodyeingdlv;
	private $prodfinishdlv;

	public function __construct(
		StyleRepository $style,
		CompanyRepository $company,
		BuyerRepository $buyer,
		BuyerNatureRepository $buyernature,
		UserRepository $user,
		ItemAccountRepository $itemaccount,
		AutoyarnRepository $autoyarn,
		TeamRepository $team,
		TeammemberRepository $teammember,
		GmtspartRepository $gmtspart,
		SoDyeingDlvRepository $sodyeingdlv,
		ProdFinishDlvRepository $prodfinishdlv
	)
    {
		$this->style=$style;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->user = $user;
		$this->buyernature = $buyernature;
		$this->itemaccount = $itemaccount;
		$this->autoyarn = $autoyarn;
		$this->team = $team;
		$this->teammember = $teammember;
		$this->gmtspart = $gmtspart;
		$this->sodyeingdlv = $sodyeingdlv;
		$this->prodfinishdlv = $prodfinishdlv;

		$this->middleware('auth');
		//$this->middleware('permission:view.orderprogressreports',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$from=request('date_from', 0);
        $to=request('date_to', 0);
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.Subcontract.AOP.FinishFabricDeliveryAop',['company'=>$company,'buyer'=>$buyer,'status'=>$status,'team'=>$team,'from'=>$from,'to'=>$to]);
    }

    public function getSelfData()
    {
		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$company_id=request('company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$company=null;
		$buyer=null;
		if($company_id){
			$company=" and prod_finish_dlvs.company_id = $company_id ";
		}
		if($buyer_id){
			$buyer=" and prod_finish_dlvs.buyer_id=$buyer_id ";
		}
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);

		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
        	$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
        	$join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        	$join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
			'autoyarns.*',
			'constructions.name',
			'compositions.name as composition_name',
			'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
        	$fabricDescriptionArr[$row->id]=$row->name;
        	$fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        	$desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

		$rows=collect(
			\DB::select("
			select
			m.barcode_no,
			m.bill_no,
			m.bill_date,
			m.company_id,
			m.buyer_id,
			m.customer_code,
			m.company_code,
			m.gmtspart_id,
			m.autoyarn_id,
			m.fabric_look_id,
			m.gsm_weight,
			m.dia_width,
			m.measurement,
			m.roll_length,
			m.stitch_length,
			m.shrink_per,
			m.batch_color_name as batch_color,
			m.batch_no,
			m.sale_order_no,
			m.aop_sales_order_no,
			m.style_ref,
			m.currency_code,
			m.exch_rate,
			sum(m.qc_pass_qty) as qty,
			sum(m.qty_pcs) as qty_pcs,
			avg(m.rate) as rate,
			sum(m.batch_qty) as batch_qty,
			count(id) as no_of_roll 
			from (
			select 
				prod_finish_dlvs.id as barcode_no,
				prod_finish_dlvs.DLV_NO as bill_no,
				prod_finish_dlvs.DLV_DATE as bill_date,
				prod_finish_dlvs.company_id,
				prod_finish_dlvs.buyer_id,
				prod_finish_dlv_rolls.id, 
				prod_batch_finish_qcs.id as prod_batch_finish_qc_id,   
				prod_aop_batch_finish_qc_rolls.gsm_weight,   
				prod_aop_batch_finish_qc_rolls.dia_width,
				prod_aop_batch_finish_qc_rolls.reject_qty,   
				prod_aop_batch_finish_qc_rolls.qty as qc_pass_qty,   
				prod_aop_batch_finish_qc_rolls.grade_id,
				prod_aop_batches.batch_no,
				prod_aop_batch_rolls.qty as batch_qty,
				prod_knit_qcs.measurement,   
				prod_knit_qcs.roll_length,   
				prod_knit_qcs.shrink_per,
				prod_knit_item_rolls.id as prod_knit_item_roll_id,
				prod_knit_item_rolls.custom_no,
				prod_knit_item_rolls.roll_weight,
				prod_knit_item_rolls.width,
				prod_knit_item_rolls.qty_pcs,
				prod_knit_item_rolls.gmt_sample,
				prod_knit_items.prod_knit_id,
				prod_knit_items.stitch_length,
				prod_knits.prod_no,
				buyers.name as buyer_name,
				customers.code as customer_code,
				companies.code as company_code,
				currencies.code as currency_code,
				styles.style_ref,
				sales_orders.sale_order_no,
				style_fabrications.autoyarn_id,
				style_fabrications.gmtspart_id,
				style_fabrications.fabric_look_id,
				style_fabrications.fabric_shape_id,

				po_aop_service_item_qties.rate,
				so_aops.sales_order_no as aop_sales_order_no,
				fabriccolors.name as batch_color_name,
				po_aop_services.exch_rate
			from 
			prod_finish_dlvs
			join companies on companies.id=prod_finish_dlvs.company_id
			join buyers customers on customers.id = prod_finish_dlvs.buyer_id
			inner join prod_finish_dlv_rolls prod_aop_finish_dlv_rolls on prod_finish_dlvs.id = prod_aop_finish_dlv_rolls.prod_finish_dlv_id 
			inner join prod_batch_finish_qc_rolls prod_aop_batch_finish_qc_rolls on prod_aop_batch_finish_qc_rolls.id = prod_aop_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
			inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_aop_batch_finish_qc_rolls.prod_batch_finish_qc_id 
			inner join prod_aop_batches on prod_aop_batches.id = prod_batch_finish_qcs.prod_aop_batch_id
			inner join prod_aop_batch_rolls on prod_aop_batch_rolls.id = prod_aop_batch_finish_qc_rolls.prod_aop_batch_roll_id
			inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
			inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
			inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
			inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
			inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id
			inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
			inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
			inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
			inner join colors fabriccolors on fabriccolors.id = prod_batches.batch_color_id
			inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
			inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
			left join currencies on currencies.id = so_aops.currency_id
			inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
			inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
			inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
			inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
			inner join po_aop_services on po_aop_services.id=po_aop_service_items.po_aop_service_id
			inner join budget_fabric_prod_cons on budget_fabric_prod_cons.id = po_aop_service_item_qties.budget_fabric_prod_con_id
			inner join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id
			inner join jobs on jobs.id = sales_orders.job_id
			inner join styles on styles.id = jobs.style_id
			inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
			inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
			inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
			inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
			inner join constructions on constructions.id = autoyarns.construction_id
			inner join buyers on buyers.id = styles.buyer_id
			inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
			inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
			inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
			inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
			inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
			inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
			inner join inv_rcvs on inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
			inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
			inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
			and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
			inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
			inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
			inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
			inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
			inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 

			where 
			prod_finish_dlvs.dlv_date >= to_date('".$date_from."', 'YYYY/MM/DD')
			and prod_finish_dlvs.dlv_date <= to_date('".$date_to."', 'YYYY/MM/DD')
			and prod_finish_dlvs.deleted_at is null $buyer $company
			) m  
			group by 
				m.barcode_no,
				m.bill_no,
				m.bill_date,
				m.company_id,
				m.buyer_id,
				m.customer_code,
				m.company_code,
				m.gmtspart_id,
				m.autoyarn_id,
				m.fabric_look_id,
				m.gsm_weight,
				m.dia_width,
				m.measurement,
				m.roll_length,
				m.stitch_length,
				m.shrink_per,
				m.batch_color_name,
				m.batch_no,
				m.sale_order_no,
				m.aop_sales_order_no,
				m.style_ref,
				m.currency_code,
				m.exch_rate"))
			->map(function($rows) use($desDropdown,$gmtspart){
				$rows->uom_code='Kg';
				$rows->bill_date=date('d-M-Y',strtotime($rows->bill_date));
				$rows->gmt_style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
				$rows->gmt_sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
				if ($rows->batch_qty) {
					$rows->amount=$rows->rate*$rows->batch_qty;
				}
				$rows->fabric_desc=isset($desDropdown[$rows->autoyarn_id])?$desDropdown[$rows->autoyarn_id]:'';
				$rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
				//$rows->exch_rate=0;
				
				if ($rows->currency_code=='USD') {
					$rows->exch_rate=84;
					$rows->amount_bdt=$rows->amount*$rows->exch_rate;
				}
				else{
					$rows->amount_bdt=$rows->amount;
				}
				$rows->amount_bdt=number_format($rows->amount_bdt,2,'.',',');
				$rows->amount=number_format($rows->amount,2,'.',',');
				$rows->qty=number_format($rows->qty,2,'.',',');
				$rows->rate=number_format($rows->rate,4,'.',',');
				$rows->grey_wgt=number_format($rows->batch_qty,2,'.',',');
				$rows->no_of_roll=number_format($rows->no_of_roll,0,'.',',');
				return $rows;
			});
		   echo json_encode($rows);
	}


    public function getSubcontractData()
    {

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$company_id=request('company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$company=null;
		$buyer=null;
		if($company_id){
			$company=" and so_aop_dlvs.company_id = $company_id ";
		}
		if($buyer_id){
			$buyer=" and so_aop_dlvs.buyer_id=$buyer_id ";
		}
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);

		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
        	$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
        	$join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        	$join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
			'autoyarns.*',
			'constructions.name',
			'compositions.name as composition_name',
			'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
        	$fabricDescriptionArr[$row->id]=$row->name;
        	$fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        	$desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

		$rows=collect(
			\DB::select("
			select 
			so_aop_dlvs.id as barcode_no,
			so_aop_dlvs.issue_no as bill_no,
			so_aop_dlvs.issue_date as bill_date,
			so_aop_dlvs.buyer_id,
			so_aops.sales_order_no as dyeing_sales_order_no,
			so_aop_items.gmtspart_id,
			so_aop_items.autoyarn_id,
			so_aop_items.gsm_weight,
			so_aop_dlv_items.no_of_roll,
			so_aop_dlv_items.design_no,
			so_aop_dlv_items.fin_dia as dia_width,
			so_aop_dlv_items.fin_gsm as gsm_wgt,
			so_aop_dlv_items.grey_used as grey_wgt,
			so_aop_items.gmt_style_ref,
			so_aop_items.gmt_sale_order_no,
			companies.code as company_code,
			buyers.name as customer_name,
			uoms.code as uom_code,
			colors.name as batch_color,
			currencies.code as currency_code,
			so_aops.exch_rate,
			sum(so_aop_dlv_items.qty) as qty,
			avg(so_aop_dlv_items.rate) as rate,
			sum(so_aop_dlv_items.amount) as amount
		from so_aop_dlvs
		join companies on companies.id=so_aop_dlvs.company_id
		join buyers on buyers.id = so_aop_dlvs.buyer_id
		left join currencies on currencies.id = so_aop_dlvs.currency_id
		join so_aop_dlv_items on so_aop_dlv_items.so_aop_dlv_id=so_aop_dlvs.id
		join so_aop_refs on so_aop_refs.id=so_aop_dlv_items.so_aop_ref_id
		join so_aops on so_aops.id=so_aop_refs.so_aop_id
		join so_aop_items on so_aop_items.so_aop_ref_id=so_aop_refs.id
		left join uoms on uoms.id=so_aop_items.uom_id
		left join colors on colors.id = so_aop_items.fabric_color_id
		where so_aop_dlvs.issue_date >= to_date('".$date_from."', 'YYYY/MM/DD')
		and so_aop_dlvs.issue_date <= to_date('".$date_to."', 'YYYY/MM/DD')
		and so_aop_dlv_items.deleted_at is null
		and  so_aop_items.deleted_at is null $buyer $company
		group by
			so_aop_dlvs.id,
			so_aop_dlvs.issue_no,
			so_aop_dlvs.issue_date,
			so_aop_dlvs.buyer_id,
			so_aops.sales_order_no,
			so_aop_items.gmtspart_id,
			so_aop_items.autoyarn_id,
			so_aop_items.gsm_weight,
			so_aop_dlv_items.no_of_roll, 
			so_aop_dlv_items.design_no,
			so_aop_dlv_items.fin_dia,
			so_aop_dlv_items.fin_gsm,
			so_aop_dlv_items.grey_used,
			so_aop_items.gmt_style_ref,
			so_aop_items.gmt_sale_order_no,

			companies.code,
			buyers.name,
			uoms.code,
			colors.name,
			currencies.code,
			so_aops.exch_rate
			"))
			->map(function($rows) use($desDropdown,$gmtspart){
				$rows->fabric_desc=isset($desDropdown[$rows->autoyarn_id])?$desDropdown[$rows->autoyarn_id]:'';
				$rows->gmtspart=$gmtspart[$rows->gmtspart_id];
				if($rows->currency_code=='USD'){
					$rows->amount_bdt=$rows->amount*84;
				}
				else{
					$rows->amount_bdt=$rows->amount;
				}
				
				
				$rows->qty=number_format($rows->qty,2,'.',',');
				$rows->rate=number_format($rows->rate,4,'.',',');
				$rows->amount=number_format($rows->amount,2,'.',',');
				$rows->amount_bdt=number_format($rows->amount_bdt,2,'.',',');
				$rows->grey_wgt=number_format($rows->grey_wgt,2,'.',',');
				$rows->no_of_roll=number_format($rows->no_of_roll,0,'.',',');
				return $rows;
			});
		   echo json_encode($rows);
	}

}
