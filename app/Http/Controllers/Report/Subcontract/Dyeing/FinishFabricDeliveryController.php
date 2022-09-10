<?php
namespace App\Http\Controllers\Report\Subcontract\Dyeing;

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

class FinishFabricDeliveryController extends Controller
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
		$company=array_prepend(array_pluck($this->company->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.Subcontract.Dyeing.FinishFabricDelivery',['company'=>$company,'buyer'=>$buyer,'status'=>$status,'team'=>$team,'from'=>$from,'to'=>$to]);
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
				m.dyeing_sales_order_no,
				m.style_ref,
				m.gmt_sale_order_no,
				m.currency_code,
				m.exch_rate,
				sum(m.qc_pass_qty) as qty,
				sum(m.qty_pcs) as qty_pcs,
				avg(m.rate) as rate,
				avg(m.c_rate) as c_rate,
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
				prod_batch_finish_qc_rolls.gsm_weight,   
				prod_batch_finish_qc_rolls.dia_width,
				prod_knit_qcs.measurement,   
				prod_knit_qcs.roll_length,   
				prod_knit_qcs.shrink_per,   
				prod_batch_finish_qc_rolls.qty as qc_pass_qty,   
				batch_colors.name as batch_color_name,
				prod_batches.batch_no,
				prod_batch_rolls.qty as batch_qty,
				prod_knit_item_rolls.id as prod_knit_item_roll_id,
				prod_knit_item_rolls.roll_weight,
				prod_knit_item_rolls.width,
				prod_knit_item_rolls.qty_pcs,
				prod_knit_items.prod_knit_id,
				prod_knit_items.stitch_length,
				prod_knits.prod_no,
				buyers.name as buyer_name,
				customers.code as customer_code,
				companies.code as company_code,
				currencies.code as currency_code,
				styles.style_ref,
				sales_orders.sale_order_no,
				so_dyeing_items.gmt_sale_order_no,
				style_fabrications.autoyarn_id,
				style_fabrications.gmtspart_id,
				style_fabrications.fabric_look_id,
				po_dyeing_service_item_qties.rate,
				so_dyeing_items.rate as c_rate,
				so_dyeings.sales_order_no as dyeing_sales_order_no,
				po_dyeing_services.exch_rate

			from 
			prod_finish_dlvs
				join companies on companies.id=prod_finish_dlvs.company_id
				join buyers customers on customers.id = prod_finish_dlvs.buyer_id
				inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
				inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
				inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
				inner join prod_batches on prod_batches.id = prod_batch_finish_qcs.prod_batch_id
				inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
				inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
				inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
				inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
				inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
				left join currencies on currencies.id = so_dyeings.currency_id
				inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
				inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
				inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
				inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
				inner join po_dyeing_services on po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id
				inner join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
				inner join jobs on jobs.id = sales_orders.job_id
				inner join styles on styles.id = jobs.style_id
				inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
				inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
				inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
				inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
				inner join constructions on constructions.id = autoyarns.construction_id
				inner join buyers on buyers.id = styles.buyer_id
				left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id = so_dyeing_refs.id
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
				left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
			where prod_finish_dlvs.dlv_date >= TO_DATE('".$date_from."', 'YYYY/MM/DD')
				and prod_finish_dlvs.dlv_date <= TO_DATE('".$date_to."', 'YYYY/MM/DD')
				$buyer $company
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
					m.dyeing_sales_order_no,
					m.style_ref,
					m.gmt_sale_order_no,
					m.currency_code,
					m.exch_rate
			"/* ,[$date_from,$date_to,$buyer_id] */))
			->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks){
				$rows->fabric_look=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
				$rows->uom_code='Kg';
				$rows->bill_date=date('d-M-Y',strtotime($rows->bill_date));
				$rows->gmt_style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
				$rows->gmt_sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
				$rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
				if ($rows->batch_qty) {
					$rows->amount=$rows->rate*$rows->batch_qty;
				}
				$rows->fabric_desc=isset($desDropdown[$rows->autoyarn_id])?$desDropdown[$rows->autoyarn_id]:'';
				$rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
				//$rows->exch_rate=0;
				if ($rows->exch_rate) {
					$rows->amount_bdt=$rows->amount*$rows->exch_rate;
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
			$company=" and so_dyeing_dlvs.company_id = $company_id ";
		}
		if($buyer_id){
			$buyer=" and so_dyeing_dlvs.buyer_id=$buyer_id ";
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
				so_dyeing_dlvs.id as barcode_no,
				so_dyeing_dlvs.issue_no as bill_no,
				so_dyeing_dlvs.issue_date as bill_date,
				so_dyeing_dlvs.buyer_id,
				so_dyeings.sales_order_no as dyeing_sales_order_no,
				so_dyeing_items.gmtspart_id,
				so_dyeing_items.autoyarn_id,
				so_dyeing_items.fabric_look_id,
				so_dyeing_items.gsm_weight,
				so_dyeing_items.measurment,
				so_dyeing_dlv_items.no_of_roll,
				so_dyeing_dlv_items.batch_no,
				so_dyeing_dlv_items.fin_dia as dia_width,
				so_dyeing_dlv_items.fin_gsm as gsm_wgt,
				so_dyeing_dlv_items.grey_used as grey_wgt,
				so_dyeing_items.gmt_style_ref,
				so_dyeing_items.gmt_sale_order_no,
				--so_dyeing_items.dyeing_type_id,
				--so_dyeing_dlv_items.process_name,
				--gmt_buyers.name as gmt_buyer_name,
				companies.code as company_code,
				buyers.name as customer_name,
				uoms.code as uom_code,
				colors.name as batch_color,
				currencies.code as currency_code,
				so_dyeings.exch_rate,
				sum(so_dyeing_dlv_items.qty) as qty,
				avg(so_dyeing_dlv_items.rate) as rate,
				sum(so_dyeing_dlv_items.amount) as amount
			from so_dyeing_dlvs
			join companies on companies.id=so_dyeing_dlvs.company_id
			join buyers on buyers.id = so_dyeing_dlvs.buyer_id
			left join currencies on currencies.id = so_dyeing_dlvs.currency_id
			join so_dyeing_dlv_items on so_dyeing_dlv_items.so_dyeing_dlv_id=so_dyeing_dlvs.id
			join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_dlv_items.so_dyeing_ref_id
			join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
			join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
			--left join buyers gmt_buyers on gmt_buyers.id=so_dyeing_items.gmt_buyer
			left join uoms on uoms.id=so_dyeing_items.uom_id
			left join colors on colors.id = so_dyeing_items.fabric_color_id
			where so_dyeing_dlvs.issue_date >= TO_DATE('".$date_from."', 'YYYY/MM/DD')
			and so_dyeing_dlvs.issue_date <= to_date('".$date_to."', 'YYYY/MM/DD')
			and so_dyeing_dlv_items.deleted_at is null
			and  so_dyeing_items.deleted_at is null $company $buyer
			group by
				so_dyeing_dlvs.id,
				so_dyeing_dlvs.issue_no,
				so_dyeing_dlvs.issue_date,
				so_dyeing_dlvs.buyer_id,
				so_dyeings.sales_order_no,
				so_dyeing_items.gmtspart_id,
				so_dyeing_items.autoyarn_id,
				so_dyeing_items.fabric_look_id,
				so_dyeing_items.gsm_weight,
				so_dyeing_items.measurment,
				so_dyeing_dlv_items.no_of_roll,
				so_dyeing_dlv_items.batch_no,
				so_dyeing_dlv_items.fin_dia,
				so_dyeing_dlv_items.fin_gsm,
				so_dyeing_dlv_items.grey_used,
				so_dyeing_items.gmt_style_ref,
				so_dyeing_items.gmt_sale_order_no,
				--so_dyeing_items.dyeing_type_id,
				--so_dyeing_dlv_items.process_name,
				--gmt_buyers.name,
				companies.code,
				buyers.name,
				uoms.code,
				colors.name,
				currencies.code,
				so_dyeings.exch_rate
			"))
			->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks){
				$rows->fabric_desc=isset($desDropdown[$rows->autoyarn_id])?$desDropdown[$rows->autoyarn_id]:'';
				$rows->fabric_look=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
				$rows->gmtspart=$gmtspart[$rows->gmtspart_id];
				$rows->amount_bdt=$rows->amount*$rows->exch_rate;
				
				$rows->qty=number_format($rows->qty,2,'.',',');
				$rows->rate=number_format($rows->rate,4,'.',',');
				$rows->amount=number_format($rows->amount,4,'.',',');
				$rows->amount_bdt=number_format($rows->amount_bdt,2,'.',',');
				$rows->grey_wgt=number_format($rows->grey_wgt,2,'.',',');
				$rows->no_of_roll=number_format($rows->no_of_roll,0,'.',',');
				return $rows;
			});
		   echo json_encode($rows);
	}

}
