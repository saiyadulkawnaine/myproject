<?php
namespace App\Http\Controllers\Report\Subcontract\Kniting;

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
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitDlvRepository;
use Illuminate\Support\Carbon;

class FinishFabricDeliveryKnitingController extends Controller
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
	private $soknitdlv;
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
		SoKnitDlvRepository $soknitdlv,
		ProdKnitDlvRepository $prodfinishdlv
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
		$this->soknitdlv = $soknitdlv;
		$this->prodfinishdlv = $prodfinishdlv;

		$this->middleware('auth');
		//$this->middleware('permission:view.orderprogressreports',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$from=request('date_from', 0);
        $to=request('date_to', 0);
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.Subcontract.Kniting.FinishFabricDeliveryKniting',['company'=>$company,'buyer'=>$buyer,'status'=>$status,'team'=>$team,'from'=>$from,'to'=>$to]);
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
			m.customer_code,
			m.company_code,
			m.gmtspart_id,
			m.autoyarn_id,
			m.fabric_look_id,
			m.fabric_shape_id,
			m.gsm_weight,
			m.dia_width,
			m.measurement,
			m.stitch_length,
			m.shrink_per,
			m.kniting_sales_order_no,
			m.style_ref,
			m.sale_order_no as gmt_sale_order_no,
			m.currency_code,
			m.exch_rate,
			avg(m.rate) as rate,
			sum(m.qc_pass_qty) as qty,
			count(id) as no_of_roll
			from (
				select 
				prod_knit_dlvs.id as barcode_no,
				prod_knit_dlvs.dlv_no as bill_no,
				prod_knit_dlvs.dlv_date as bill_date,
				prod_knit_dlv_rolls.id,
				prod_knit_qcs.gsm_weight,
				prod_knit_qcs.dia_width,
				prod_knit_qcs.measurement,
				prod_knit_qcs.qc_pass_qty,
				prod_knit_qcs.shrink_per,
				prod_knit_item_rolls.width,
				prod_knit_item_rolls.fabric_color,
				prod_knit_items.stitch_length,
				companies.code as company_code,
				customers.code as customer_code,
				inhouseprods.autoyarn_id,
				inhouseprods.gmtspart_id,
				inhouseprods.fabric_look_id,
				inhouseprods.fabric_shape_id,
				inhouseprods.sale_order_no,
				inhouseprods.style_ref,
				inhouseprods.kniting_sales_order_no,
				inhouseprods.currency_code,
				inhouseprods.exch_rate,
				inhouseprods.rate
				from prod_knit_dlvs 
				inner join companies on companies.id=prod_knit_dlvs.company_id
				inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id
				inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id
				inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id
				inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id
				inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id
				inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id
				inner join suppliers on suppliers.id = prod_knits.supplier_id
				inner join companies customers on customers.id=suppliers.company_id
				left join (
					select 
					pl_knit_items.id,

					case 
					when  style_fabrications.autoyarn_id is null then so_knit_items.autoyarn_id 
					else style_fabrications.autoyarn_id
					end as autoyarn_id,

					case 
					when  style_fabrications.gmtspart_id is null then so_knit_items.gmtspart_id 
					else style_fabrications.gmtspart_id
					end as gmtspart_id,

					case 
					when  style_fabrications.fabric_look_id is null then so_knit_items.fabric_look_id 
					else style_fabrications.fabric_look_id
					end as fabric_look_id,

					case 
					when  style_fabrications.fabric_shape_id is null then so_knit_items.fabric_shape_id 
					else style_fabrications.fabric_shape_id
					end as fabric_shape_id,

					case 
					when sales_orders.sale_order_no is null then so_knit_items.gmt_sale_order_no 
					else sales_orders.sale_order_no
					end as sale_order_no,

					case 
					when styles.style_ref is null then so_knit_items.gmt_style_ref 
					else styles.style_ref
					end as style_ref,

					CASE 
					WHEN  so_knit_items.rate IS NULL THEN po_knit_service_item_qties.rate
					ELSE so_knit_items.rate
					END as rate,

					currencies.code as currency_code,
					so_knits.sales_order_no as kniting_sales_order_no,
					so_knits.exch_rate

					from pl_knit_items
					join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
					join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
					left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
					left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
					left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
					and po_knit_service_items.deleted_at is null
					left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
					left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
					left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
					left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
					left join so_knits on so_knits.id=so_knit_refs.so_knit_id
					left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
					left join jobs on jobs.id=sales_orders.job_id
					left join styles on styles.id=jobs.style_id
					left join buyers on buyers.id=styles.buyer_id
					left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
					left join buyers customer on customer.id=so_knits.buyer_id
					left join currencies on currencies.id=so_knits.currency_id
				) inhouseprods on inhouseprods.id = prod_knit_items.pl_knit_item_id 
				where prod_knit_dlvs.dlv_date >= TO_DATE('".$date_from."', 'YYYY/MM/DD')
				and prod_knit_dlvs.dlv_date <= TO_DATE('".$date_to."', 'YYYY/MM/DD')
				and prod_knit_dlv_rolls.deleted_at is null
			) m  
			group by 
			m.barcode_no,
			m.bill_no,
			m.bill_date,
			m.customer_code,
			m.company_code,
			m.gmtspart_id,
			m.autoyarn_id,
			m.fabric_look_id,
			m.fabric_shape_id,
			m.gsm_weight,
			m.dia_width,
			m.measurement,
			m.stitch_length,
			m.shrink_per,
			m.kniting_sales_order_no,
			m.style_ref,
			m.sale_order_no,
			m.currency_code,
			m.exch_rate
			"))
			->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks){
				$rows->fabric_look=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
				$rows->uom_code='Kg';
				$rows->bill_date=date('d-M-Y',strtotime($rows->bill_date));
				$rows->gmt_style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
				$rows->amount=$rows->rate*$rows->qty;
				$rows->fabric_desc=isset($desDropdown[$rows->autoyarn_id])?$desDropdown[$rows->autoyarn_id]:'';
				$rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
				$rows->amount_bdt=$rows->amount*$rows->exch_rate;

				$rows->amount_bdt=number_format($rows->amount_bdt,2,'.',',');
				$rows->amount=number_format($rows->amount,2,'.',',');
				$rows->qty=number_format($rows->qty,2,'.',',');
				$rows->rate=number_format($rows->rate,4,'.',',');
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
			so_knit_dlvs.id as barcode_no,
			so_knit_dlvs.issue_no as bill_no,
			so_knit_dlvs.issue_date as bill_date,
			so_knit_dlvs.buyer_id,
			so_knits.sales_order_no as kniting_sales_order_no,
			so_knit_items.gmtspart_id,
			so_knit_items.autoyarn_id,
			so_knit_items.fabric_look_id,
			so_knit_items.fabric_shape_id,
			so_knit_items.gsm_weight,
			so_knit_items.dia,
			so_knit_items.measurment,
			so_knit_dlv_items.no_of_roll,
			so_knit_items.gmt_style_ref,
			so_knit_items.gmt_sale_order_no,
			companies.code as company_code,
			buyers.name as customer_name,
			uoms.code as uom_code,
			colors.name as batch_color,
			currencies.code as currency_code,
			so_knits.exch_rate,
			sum(so_knit_dlv_items.qty) as qty,
			avg(so_knit_dlv_items.rate) as rate,
			sum(so_knit_dlv_items.amount) as amount
		from so_knit_dlvs
		join companies on companies.id=so_knit_dlvs.company_id
		join buyers on buyers.id = so_knit_dlvs.buyer_id
		left join currencies on currencies.id = so_knit_dlvs.currency_id
		join so_knit_dlv_items on so_knit_dlv_items.so_knit_dlv_id=so_knit_dlvs.id
		join so_knit_refs on so_knit_refs.id=so_knit_dlv_items.so_knit_ref_id
		join so_knits on so_knits.id=so_knit_refs.so_knit_id
		join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
		--left join buyers gmt_buyers on gmt_buyers.id=so_knit_items.gmt_buyer
		left join uoms on uoms.id=so_knit_items.uom_id
		left join colors on colors.id = so_knit_items.fabric_color_id
		where so_knit_dlvs.issue_date >= to_date('".$date_from."', 'YYYY/MM/DD')
		and so_knit_dlvs.issue_date <= to_date('".$date_to."', 'YYYY/MM/DD')
		and so_knit_dlv_items.deleted_at is null
		and  so_knit_items.deleted_at is null 
		group by
			so_knit_dlvs.id,
			so_knit_dlvs.issue_no,
			so_knit_dlvs.issue_date,
			so_knit_dlvs.buyer_id,
			so_knit_dlv_items.no_of_roll,
			so_knits.sales_order_no,
			so_knit_items.gmtspart_id,
			so_knit_items.autoyarn_id,
			so_knit_items.fabric_look_id,
			so_knit_items.fabric_shape_id,
			so_knit_items.gsm_weight,
			so_knit_items.dia,
			so_knit_items.measurment,
			so_knit_items.gmt_style_ref,
			so_knit_items.gmt_sale_order_no,
			companies.code,
			buyers.name,
			uoms.code,
			colors.name,
			currencies.code,
			so_knits.exch_rate
			"))
			->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape){
				$rows->fabric_desc=isset($desDropdown[$rows->autoyarn_id])?$desDropdown[$rows->autoyarn_id]:'';
				$rows->fabric_look=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
				$rows->gmtspart=$gmtspart[$rows->gmtspart_id];
				if($rows->currency_code=="USD"){

					$rows->amount_bdt=$rows->amount*84;
				}
				else{
					$rows->amount_bdt=$rows->amount;
				}
				
				$rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
				$rows->qty=number_format($rows->qty,2,'.',',');
				$rows->rate=number_format($rows->rate,4,'.',',');
				$rows->amount=number_format($rows->amount,2,'.',',');
				$rows->amount_bdt=number_format($rows->amount_bdt,2,'.',',');
				$rows->no_of_roll=number_format($rows->no_of_roll,0,'.',',');
				return $rows;
			});
		   echo json_encode($rows);
	}

}
