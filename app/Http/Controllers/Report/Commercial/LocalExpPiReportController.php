<?php
namespace App\Http\Controllers\Report\Commercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiOrderRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
//use App\Library\Numbertowords;

class LocalExpPiReportController extends Controller
{
    private $localexppi;
    private $buyer;
    private $salesorder;
    private $company;
    private $itemaccount;
    private $termscondition;
    private $localexppiorder;
    private $currency;
    private $soaop;
    
    public function __construct(LocalExpPiRepository $localexppi,BuyerRepository $buyer,ItemAccountRepository $itemaccount,SalesOrderRepository $salesorder, CompanyRepository $company, TermsConditionRepository $termscondition,
    PurchaseTermsConditionRepository $purchasetermscondition, LocalExpPiOrderRepository $localexppiorder,
    SoKnitRepository $soknit,
    SoDyeingRepository $sodyeing,
    GmtspartRepository $gmtspart,
    AutoyarnRepository $autoyarn,
    UomRepository $uom,
    ColorrangeRepository $colorrange,
    CurrencyRepository $currency,
    ColorRepository $color,
    EmbelishmentTypeRepository $embelishmenttype,
    SoAopRepository $soaop
  ) {
        $this->localexppi = $localexppi;
        $this->localexppiorder = $localexppiorder;
        $this->buyer = $buyer;
        $this->salesorder = $salesorder;
        $this->company = $company;
        $this->itemaccount = $itemaccount;
        $this->termscondition = $termscondition;
        $this->purchasetermscondition = $purchasetermscondition;
        $this->soknit = $soknit;
        $this->sodyeing = $sodyeing;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->currency= $currency;
        $this->embelishmenttype = $embelishmenttype;
        $this->soaop = $soaop;

		$this->middleware('auth');
		//$this->middleware('permission:view.localexppireports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $productionarea=array_prepend(array_only(config('bprs.productionarea'),[5,10,20,25,45,50]),'-Select-','');
        $years=array_prepend(config('bprs.years'),'-Select-','');
        $selected_year=date('Y');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
    	
        return Template::loadView('Report.Commercial.LocalExpPiReport',['company'=>$company,'buyer'=>$buyer,'productionarea'=>$productionarea,'years'=>$years,'selected_year'=>$selected_year,'payterm'=>$payterm,'incoterm'=>$incoterm,
        'aoptype'=>$aoptype]);
    }

    
    public function getData()
     {   
       $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
       $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
       $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
       $production_area_id=request('production_area_id', 0);
       $date_from =request('date_from',0);
        $date_to=request('date_to',0);

       $localexppis=array();
       $rows=$this->localexppi
       ->leftJoin(\DB::raw("(SELECT
       local_exp_pis.id as local_exp_pi_id,
            sum(local_exp_pi_orders.qty) as total_qty,
            sum(local_exp_pi_orders.amount) as total_value,
            sum(local_exp_pi_orders.discount_per) as total_discount
            FROM local_exp_pi_orders  
            join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id   
        group by local_exp_pis.id) localitem"), "localitem.local_exp_pi_id", "=", "local_exp_pis.id")

        ->leftJoin(\DB::raw("(select local_exp_pis.id as local_exp_pi_id,
        local_exp_lcs.local_lc_no as lc_no ,
        local_exp_lcs.lc_date
        FROM local_exp_lcs
        left join local_exp_lc_tag_pis on
         local_exp_lcs.id = local_exp_lc_tag_pis.local_exp_lc_id 
        left join local_exp_pis on
         local_exp_lc_tag_pis.local_exp_pi_id = local_exp_pis.id 
        group by
         local_exp_pis.id,
        local_exp_lcs.local_lc_no,
         local_exp_lcs.lc_date) localLc"), "localLc.local_exp_pi_id", "=", "local_exp_pis.id")

       ->when(request('production_area_id'), function ($q,$production_area_id) {
            return $q->where('local_exp_pis.production_area_id', '=', $production_area_id);
        //return $q->where('local_exp_pis.production_area_id', 'LIKE', "%".request('production_area_id', 0)."%");
        })
        ->when(request('company_id'), function ($q) {
            return $q->where('local_exp_pis.company_id', '=', request('company_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
            return $q->where('local_exp_pis.buyer_id', '=', request('buyer_id', 0));
        })
        ->when($date_from, function ($q) use($date_from){
            return $q->where('local_exp_pis.pi_date', '>=',$date_from);
        })
        ->when($date_to, function ($q) use($date_to) {
            return $q->where('local_exp_pis.pi_date', '<=',$date_to);
        })
       ->orderBy('local_exp_pis.id','desc')
       ->get([
           'local_exp_pis.*',
           'local_exp_pis.id as local_exp_pi_id',
           'localitem.total_value',
           'localitem.total_qty',
           'localitem.total_discount',
           'localLc.lc_no',
           'localLc.lc_date',
       ]);
       foreach($rows as $row){
           $localexppi['local_exp_pi_id']=$row->local_exp_pi_id;
           $localexppi['remarks']=$row->remarks;
           $localexppi['company_code']=isset($company[$row->company_id])?$company[$row->company_id]:'';
           $localexppi['buyer_name']=isset($buyer[$row->buyer_id])?$buyer[$row->buyer_id]:'';
           $localexppi['pi_no']=$row->pi_no;
           $localexppi['sys_pi_no']=$row->sys_pi_no;
           $localexppi['lc_no']=$row->lc_no;
           $localexppi['lc_date']=$row->lc_date;
           $localexppi['pi_validity_days']=$row->pi_validity_days;
           $localexppi['pi_date']=date('d-M-Y',strtotime($row->pi_date));
           $localexppi['pay_term_id']=isset($payterm[$row->pay_term_id])?$payterm[$row->pay_term_id]:'';
           $localexppi['tenor']=$row->tenor;
           $localexppi['delivery_date']=date('d-M-Y',strtotime($row->delivery_date));
           $localexppi['delivery_place']=$row->delivery_place;
           $localexppi['hs_code']=$row->hs_code;
           $localexppi['remarks']=$row->remarks;
           $localexppi['net_value']=number_format($row->total_value+$row->total_discount,2,'.',',');
           $localexppi['lc_value']=number_format(0,2,'.',',');
           if($row->lc_no){
            $localexppi['lc_value']=number_format($row->total_value+$row->total_discount,2,'.',',');

           }
           $localexppi['total_value']=number_format($row->total_value,2,'.',',');
           $localexppi['total_discount']=number_format($row->total_discount,2,'.',',');
           $localexppi['total_qty']=number_format($row->total_qty,2,'.',',');
           array_push($localexppis, $localexppi);
       }
       echo json_encode($localexppis);
    }

  

    public function getExpItemDetail(){
        $localexppi=$this->localexppi->find(request('local_exp_pi_id',0));
        $production_area_id=$localexppi->production_area_id;
        if($production_area_id==10){
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
            ->when(request('construction_name'), function ($q) {
                return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
            })
            ->when(request('composition_name'), function ($q) {
                return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
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
            $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
    
    
            $rows=$this->soknit
            ->join('so_knit_refs',function($join){
                $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_pos',function($join){
                $join->on('so_knit_pos.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_po_items',function($join){
                $join->on('so_knit_po_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('po_knit_service_item_qties',function($join){
                $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                ->whereNull('po_knit_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_knit_items',function($join){
                $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('buyers',function($join){
                $join->on('buyers.id','=','styles.buyer_id');
            })
            ->leftJoin('buyers as gmt_buyer',function($join){
                $join->on('gmt_buyer.id','=','so_knit_items.gmt_buyer');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_knit_items.uom_id');
            })
            ->leftJoin('colors as so_color',function($join){
                $join->on('so_color.id','=','so_knit_items.fabric_color_id');
            })
            ->leftJoin('colors as po_color',function($join){
                $join->on('po_color.id','=','po_knit_service_item_qties.fabric_color_id');
            })
            ->leftJoin(\DB::raw("(SELECT
                so_knit_refs.id as sales_order_ref_id,
                sum(local_exp_pi_orders.qty) as cumulative_qty
                FROM local_exp_pi_orders  
                join so_knit_refs on so_knit_refs.id = local_exp_pi_orders.sales_order_ref_id   
            group by so_knit_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_knit_refs.id")
            ->leftJoin('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_knit_refs.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->where('local_exp_pi_orders.local_exp_pi_id','=',$localexppi->id)
            ->selectRaw('
                so_knits.sales_order_no as knitting_sales_order,
                so_knit_refs.id as so_knit_ref_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.uom_id,
                budget_fabrics.gsm_weight,
                po_knit_service_item_qties.qty as order_qty,
                po_knit_service_item_qties.pcs_qty,
                po_knit_service_item_qties.rate as order_rate,
                po_knit_service_item_qties.amount as order_amount,
                so_knit_items.qty as c_qty,
                so_knit_items.rate as c_rate,
                so_knit_items.uom_id as c_uom_id,
                cumulatives.cumulative_qty,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_knit_items.gmt_style_ref,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                so_knit_items.fabric_look_id as c_fabric_look_id,
                so_knit_items.fabric_shape_id as c_fabric_shape_id,
                so_knit_items.gmtspart_id as c_gmtspart_id,
                so_knit_items.gsm_weight as c_gsm_weight,
                so_color.name as c_fabric_color_name,
                po_color.name as fabric_color_name,
                uoms.code as uom_code,
                so_uoms.code as c_uom_code,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per 
            ')
            ->orderBy('local_exp_pi_orders.id','desc')
            ->get()
            ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape){
              $rows->sales_order_item_id=$rows->po_knit_service_item_qty_id?$rows->po_knit_service_item_qty_id:$rows->so_knit_item_id;
              $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
              $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
              $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
              $rows->fabric_shape_id=$rows->fabric_shape_id?$rows->fabric_shape_id:$rows->c_fabric_shape_id;
              $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
              $rows->order_qty=$rows->order_qty?$rows->order_qty:$rows->c_qty;
              $rows->order_rate=$rows->order_rate?$rows->order_rate:$rows->c_rate;
              $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
              $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
              $rows->fabric_color=$rows->fabric_color_name?$rows->fabric_color_name:$rows->c_fabric_color_name;
              $rows->uom_code=$rows->uom_code?$rows->uom_code:$rows->c_uom_code;
              $rows->item_description=$rows->fabrication.','.$rows->fabriclooks.','.$rows->fabricshape.','.$rows->gsm_weight.','.$rows->fabric_color;
              $net_amount=$rows->amount-$rows->discount_per;
              $rows->net_amount=number_format($net_amount,2,'.',',');
              $rows->amount=number_format($rows->amount,2,'.',',');
              $rows->qty=number_format($rows->qty,2,'.',',');
              $rows->order_rate=number_format($rows->order_rate,4,'.',',');
              return $rows;
            });

            $orders=$rows;
            echo json_encode($orders);

        }
        elseif ($production_area_id==20) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');

            $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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
            ->when(request('construction_name'), function ($q) {
            return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
            })
            ->when(request('composition_name'), function ($q) {
            return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
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
            $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

            $rows=$this->sodyeing
            ->join('so_dyeing_refs',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_pos',function($join){
                $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_po_items',function($join){
                $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('po_dyeing_service_item_qties',function($join){
                $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
            })
            ->leftJoin('po_dyeing_service_items',function($join){
                $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_dyeing_items',function($join){
                $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('buyers',function($join){
                $join->on('buyers.id','=','styles.buyer_id');
            })
            ->leftJoin('buyers as gmt_buyer',function($join){
                $join->on('gmt_buyer.id','=','so_dyeing_items.gmt_buyer');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
            })
            ->leftJoin(\DB::raw("(SELECT
            so_dyeing_refs.id as so_dyeing_ref_id,
                sum(so_dyeing_items.qty) as cumulative_qty
                FROM so_dyeing_items  
                join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_items.so_dyeing_ref_id   
            group by so_dyeing_refs.id) cumulatives"), "cumulatives.so_dyeing_ref_id", "=", "so_dyeing_refs.id")

            ->leftJoin('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_dyeing_refs.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->where('local_exp_pi_orders.local_exp_pi_id','=',$localexppi->id)
            ->selectRaw('
                po_dyeing_service_item_qties.id as po_dyeing_service_item_qty_id,
                so_dyeing_items.id as so_dyeing_item_id,
                so_dyeings.id as so_dyeing_id_sc,
                so_dyeing_refs.id as so_dyeing_ref_id,
                so_dyeing_refs.so_dyeing_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_dyeing_service_item_qties.fabric_color_id,
                po_dyeing_service_item_qties.colorrange_id,
                sum(po_dyeing_service_item_qties.qty) as order_qty,
                po_dyeing_service_item_qties.pcs_qty,
                avg(po_dyeing_service_item_qties.rate) as order_rate,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.gmtspart_id as c_gmtspart_id,
                so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.colorrange_id as c_colorrange_id,
                sum(so_dyeing_items.qty) as c_qty,
                avg(so_dyeing_items.rate) as c_rate,
                sum(so_dyeing_items.amount) as c_amount,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_style_ref,
                so_dyeing_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_code,
                so_uoms.code as so_uom_name,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per
              '
            )
            ->orderBy('so_dyeing_refs.id','desc')
            ->groupBy([
              'so_dyeings.id',
              'so_dyeing_refs.id',
              'so_dyeing_refs.so_dyeing_id',
              'po_dyeing_service_item_qties.id',
              'so_dyeing_items.id',
              'constructions.name',
              'style_fabrications.autoyarn_id',
              'style_fabrications.fabric_look_id',
              'style_fabrications.fabric_shape_id',
              'style_fabrications.gmtspart_id',
              'budget_fabrics.gsm_weight',
              'po_dyeing_service_item_qties.fabric_color_id',
              'po_dyeing_service_item_qties.colorrange_id',
              'po_dyeing_service_item_qties.pcs_qty',
              'so_dyeing_items.autoyarn_id',
              'so_dyeing_items.fabric_look_id',
              'so_dyeing_items.fabric_shape_id',
              'so_dyeing_items.gmtspart_id',
              'so_dyeing_items.gsm_weight',
              'so_dyeing_items.fabric_color_id',
              'so_dyeing_items.colorrange_id',
              'styles.style_ref',
              'sales_orders.sale_order_no',
              'so_dyeing_items.gmt_style_ref',
              'so_dyeing_items.gmt_sale_order_no',
              'buyers.name',
              'gmt_buyer.name',
              'uoms.code',
              'so_uoms.code',
              'local_exp_pi_orders.local_exp_pi_id',
              'local_exp_pi_orders.sales_order_ref_id',
              'local_exp_pi_orders.qty',
              'local_exp_pi_orders.amount',
              'local_exp_pi_orders.discount_per',
              'local_exp_pi_orders.id'
            ])
            ->get()
            ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color )/* */{
                $rows->customer_sales_order=$rows->sales_order_no;
                $rows->sales_order_id=$rows->so_dyeing_id_sc;
                $rows->sales_order_ref_id=$rows->so_dyeing_ref_id;
                $rows->sales_order_item_id=$rows->po_dyeing_service_item_qty_id?$rows->po_dyeing_service_item_qty_id:$rows->so_dyeing_item_id;
                $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
                $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
                $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
                $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
                $rows->uom_id=$rows->uom_id?$uom[$rows->uom_id]:'';
                $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;

                $rows->fabric_color_name=$rows->fabric_color_id?$color[$rows->fabric_color_id]:$color[$rows->c_fabric_color_id];
                $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];

                $rows->order_qty=$rows->order_qty?$rows->order_qty:$rows->c_qty;
                $rows->pcs_qty=$rows->pcs_qty;
                $rows->order_rate=$rows->order_rate?$rows->order_rate:$rows->c_rate;
                $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
                $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
                $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
                $rows->uom_code=$rows->uom_name?$rows->uom_name:$rows->so_uom_name;
                $rows->item_description=$rows->fabrication.','.$rows->fabriclooks.','.$rows->fabricshape.','.$rows->gsm_weight.','.$rows->fabric_color_name;
                $net_amount=$rows->amount+$rows->discount_per;
                $rows->net_amount=number_format($net_amount,2,'.',',');
                $rows->amount=number_format($rows->amount,2,'.',',');
                $rows->qty=number_format($rows->qty,2,'.',',');
                $rows->order_rate=number_format($rows->order_rate,4,'.',',');
                return $rows;

            });

            echo json_encode($rows);
        }
        elseif ($production_area_id==25) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
            $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
            $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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
            ->when(request('construction_name'), function ($q) {
            return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
            })
            ->when(request('composition_name'), function ($q) {
            return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

            $rows=$this->soaop
            ->join('so_aop_refs',function($join){
                $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
                $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_po_items',function($join){
                $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('po_aop_service_item_qties',function($join){
                $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->leftJoin('budget_fabric_prod_cons',function($join){
                $join->on('po_aop_service_item_qties.budget_fabric_prod_con_id','=','budget_fabric_prod_cons.id');
            })
            ->leftJoin('po_aop_service_items',function($join){
                $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','budget_fabric_prod_cons.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','budget_fabric_prod_cons.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
                $join->on('budget_fabric_prod_cons.budget_fabric_prod_id','=','budget_fabric_prods.id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_aop_items',function($join){
                $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('buyers',function($join){
                $join->on('buyers.id','=','styles.buyer_id');
            })
            ->leftJoin('buyers as gmt_buyer',function($join){
                $join->on('gmt_buyer.id','=','so_aop_items.gmt_buyer');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_aop_items.uom_id');
            })
            ->leftJoin(\DB::raw("(SELECT
            so_aop_refs.id as so_aop_ref_id,
                sum(so_aop_items.qty) as cumulative_qty
                FROM so_aop_items  
                join so_aop_refs on so_aop_refs.id = so_aop_items.so_aop_ref_id   
            group by so_aop_refs.id) cumulatives"), "cumulatives.so_aop_ref_id", "=", "so_aop_refs.id")

            ->leftJoin('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_aop_refs.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->where('local_exp_pi_orders.local_exp_pi_id','=',$localexppi->id)
            ->selectRaw('
                so_aop_refs.id as so_aop_ref_id,
                so_aop_refs.so_aop_id,
                constructions.name as constructions_name,
                budget_fabric_prod_cons.fabric_color_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_aop_service_item_qties.budget_fabric_prod_con_id,
                po_aop_service_item_qties.colorrange_id,
                
                po_aop_service_item_qties.rate as order_rate,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                so_aop_items.fabric_look_id as c_fabric_look_id,
                so_aop_items.fabric_shape_id as c_fabric_shape_id,
                so_aop_items.gmtspart_id as c_gmtspart_id,
                so_aop_items.gsm_weight as c_gsm_weight,
                so_aop_items.fabric_color_id as c_fabric_color_id,
                so_aop_items.colorrange_id as c_colorrange_id,
                po_aop_service_item_qties.embelishment_type_id,
                po_aop_service_item_qties.coverage,
                po_aop_service_item_qties.impression,
                so_aop_items.embelishment_type_id as c_embelishment_type_id,
                so_aop_items.coverage as c_coverage,
                so_aop_items.impression as c_impression,
                so_aop_items.rate as c_rate,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_aop_items.gmt_style_ref,
                so_aop_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per
              '
            )
            ->orderBy('so_aop_refs.id','desc')
            ->get()
            ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$aoptype){
                $rows->customer_sales_order=$rows->sales_order_no;
                $rows->sales_order_ref_id=$rows->so_aop_ref_id;
                $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
                $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
                $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
                $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
                $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
                $rows->fabric_color_name=$rows->fabric_color_id?$color[$rows->fabric_color_id]:$color[$rows->c_fabric_color_id];
                $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];
                $rows->embelishment_type_id=$rows->embelishment_type_id?$aoptype[$rows->embelishment_type_id]:$aoptype[$rows->c_embelishment_type_id];
                $rows->coverage=$rows->coverage?$rows->coverage:$rows->c_coverage;
                $rows->impression=$rows->impression?$rows->impression:$rows->c_impression;
                $rows->order_rate=$rows->order_rate?$rows->order_rate:$rows->c_rate;
                $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
                $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
                $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
                $rows->uom_code=$rows->uom_name?$rows->uom_name:$rows->so_uom_name;
                $rows->item_description=$rows->fabrication.','.$rows->fabriclooks.','.$rows->fabricshape.','.$rows->gsm_weight.','.$rows->fabric_color_name.','.$rows->embelishment_type_id.','.$rows->coverage.'%'.','.$rows->impression;
                $net_amount=$rows->amount+$rows->discount_per;
                $rows->net_amount=number_format($net_amount,2,'.',',');
                $rows->amount=number_format($rows->amount,2,'.',',');
                $rows->qty=number_format($rows->qty,2,'.',',');
                $rows->order_rate=number_format($rows->order_rate,4,'.',',');
                return $rows;
            });

            echo json_encode($rows);
        }
    }
     
}