<?php

namespace App\Http\Controllers\Report\Subcontract\Kniting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;

use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
class PlKnitExiReportController extends Controller
{
    private $company;
    private $buyer;
    private $location;
    private $assetacquisition;
    private $plknititem;
    private $gmtspart;
    private $autoyarn;

	public function __construct(
        CompanyRepository $company,
        BuyerRepository $buyer,
        LocationRepository $location,
        AssetAcquisitionRepository $assetacquisition,
        PlKnitItemRepository $plknititem,
        GmtspartRepository $gmtspart,
        AutoyarnRepository $autoyarn
    )
    {
		
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->assetacquisition = $assetacquisition;
        $this->plknititem = $plknititem;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->middleware('auth');
        //$this->middleware('permission:view.subinbmarketingreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.Subcontract.Kniting.PlKnitExiReport',['company'=>$company,'buyer'=>$buyer,'location'=>$location]);
    }
	
    
   

    
    public function getPlan($date_from,$date_to)
    {

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $autoyarn=$this->autoyarn->join('autoyarnratios', function($join)  {
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
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }
        
        $plknititem=$this->plknititem
        ->join('pl_knits', function($join)  {
            $join->on('pl_knits.id', '=', 'pl_knit_items.pl_knit_id');
        })
        ->join('pl_knit_item_qties', function($join)  {
            $join->on('pl_knit_item_qties.pl_knit_item_id', '=', 'pl_knit_items.id');
        })
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','pl_knit_items.machine_id');
        })
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_acquisitions.id','=','asset_technical_features.asset_acquisition_id');
        })
        ->join('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'pl_knit_items.colorrange_id');
        })
        ->join('so_knit_refs', function($join)  {
            $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
        })
        ->join('so_knits',function($join){
            $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
        })
        ->leftJoin('so_knit_po_items', function($join)  {
            $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
        })
        ->leftJoin('po_knit_service_item_qties',function($join){
              $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
        })
        ->leftJoin('po_knit_service_items',function($join){
                 $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                 ->whereNull('po_knit_service_items.deleted_at');
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
        ->leftJoin('so_knit_items', function($join)  {
            $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
        })
        ->leftJoin('colors as so_color',function($join){
            $join->on('so_color.id','=','so_knit_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
            $join->on('po_color.id','=','po_knit_service_item_qties.fabric_color_id');
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
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','jobs.company_id');
        })
        ->leftJoin('companies as pcompanies',function($join){
            $join->on('pcompanies.id','=','sales_orders.produced_company_id');
        })
        ->leftJoin('companies as knitcompanies',function($join){
            $join->on('knitcompanies.id','=','so_knits.company_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('buyers as gmt_buyer',function($join){
            $join->on('gmt_buyer.id','=','so_knit_items.gmt_buyer');
        })
        ->leftJoin('buyers as customers',function($join){
            $join->on('customers.id','=','so_knits.buyer_id');
        })

        ->leftJoin(\DB::raw('(
            select
            
            prod_knit_items.pl_knit_item_id,
            sum(prod_knit_item_rolls.roll_weight) as qty
            from
            prod_knits
            join prod_knit_items on prod_knit_items.prod_knit_id=prod_knits.id
            join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id=prod_knit_items.id
            group by 
           
            prod_knit_items.pl_knit_item_id ) prod'),'prod.pl_knit_item_id', '=', 'pl_knit_items.id')
        
        ->where([['pl_knits.company_id','=',request('company_id',0)]])
        ->when(request('date_from'), function ($q) use($date_from){
        return $q->where('pl_knit_item_qties.pl_date', '>=',$date_from);
        })
        ->when(request('date_to'), function ($q) use($date_to) {
        return $q->where('pl_knit_item_qties.pl_date', '<=',$date_to);
        })
        /*->when(request('date_from'), function ($q) use($date_from){
        return $q->where('pl_knit_items.pl_start_date', '>=',$date_from);
        })
        ->when(request('date_to'), function ($q) use($date_to) {
        return $q->where('pl_knit_items.pl_end_date', '<=',$date_to);
        })*/
        ->orderBy('pl_knit_items.id')
        ->selectRaw('
            pl_knit_items.id,
            pl_knit_items.pl_start_date,
            pl_knit_items.pl_end_date,
            pl_knit_items.gsm_weight as pl_gsm_weight,
            pl_knit_items.dia as pl_dia,
            pl_knit_items.qty,
            pl_knit_items.machine_id,
            min(pl_knit_item_qties.pl_date) as min_date,
            max(pl_knit_item_qties.pl_date) as max_date,
            sum(pl_knit_item_qties.qty) as day_qty,
            so_color.name as c_fabric_color_name,
            po_color.name as fabric_color_name,
            
            
            companies.code as company_name,
            pcompanies.code as prod_company_name,
            knitcompanies.code as knit_company,
            customers.name as customer_name,
            styles.style_ref,
            so_knit_items.gmt_style_ref,
            sales_orders.sale_order_no,
            so_knit_items.gmt_sale_order_no,
            buyers.name as buyer_name,
            gmt_buyer.name as gmt_buyer_name,
            so_knits.sales_order_no as knit_sale_order,
            style_fabrications.gmtspart_id,
            so_knit_items.gmtspart_id as c_gmtspart_id,
            style_fabrications.autoyarn_id,
            so_knit_items.autoyarn_id as c_autoyarn_id,
            style_fabrications.fabric_shape_id,
            so_knit_items.fabric_shape_id as c_fabric_shape_id,
            style_fabrications.fabric_look_id,
            so_knit_items.fabric_shape_id as c_fabric_look_id,
            po_knit_service_item_qties.dia,
            so_knit_items.dia as c_dia,
            budget_fabrics.gsm_weight,
            so_knit_items.gsm_weight as c_gsm_weight,
            asset_acquisitions.brand,
            asset_quantity_costs.custom_no as machine_no,
            asset_acquisitions.prod_capacity as capacity,
            asset_technical_features.dia_width,
            asset_technical_features.gauge,
            asset_technical_features.extra_cylinder,
            asset_technical_features.no_of_feeder,
            colorranges.name as colorrange_name,
            prod.qty as prod_qty'
              )
        ->groupBy([
            'pl_knit_items.id',
            'pl_knit_items.pl_start_date',
            'pl_knit_items.pl_end_date',
            'pl_knit_items.gsm_weight',
            'pl_knit_items.dia',
            'pl_knit_items.qty',
            'pl_knit_items.machine_id',
            //'pl_knit_item_qties.pl_date',
            //'pl_knit_item_qties.qty as date_qty',
            'so_color.name',
            'po_color.name',
            'companies.code',
            'pcompanies.code',
            'knitcompanies.code',
            'customers.name',
            'styles.style_ref',
            'so_knit_items.gmt_style_ref',
            'sales_orders.sale_order_no',
            'so_knit_items.gmt_sale_order_no',
            'buyers.name',
            'gmt_buyer.name',
            'so_knits.sales_order_no',
            'style_fabrications.gmtspart_id',
            'so_knit_items.gmtspart_id',
            'style_fabrications.autoyarn_id',
            'so_knit_items.autoyarn_id',
            'style_fabrications.fabric_shape_id',
            'so_knit_items.fabric_shape_id',
            'style_fabrications.fabric_look_id',
            'so_knit_items.fabric_shape_id',
            'po_knit_service_item_qties.dia',
            'so_knit_items.dia',
            'budget_fabrics.gsm_weight',
            'so_knit_items.gsm_weight',
            'asset_acquisitions.brand',
            'asset_quantity_costs.custom_no',
            'asset_acquisitions.prod_capacity',
            'asset_technical_features.dia_width',
            'asset_technical_features.gauge',
            'asset_technical_features.extra_cylinder',
            'asset_technical_features.no_of_feeder',
            'colorranges.name',
            'prod.qty',
        ])
        ->get()
        ->map(function($plknititem) use($gmtspart,$desDropdown,$fabricshape,$fabriclooks){
            $plknititem->fabric_color=$plknititem->fabric_color_name?$plknititem->fabric_color_name:$plknititem->c_fabric_color_name;
            $plknititem->sales_order_no=$plknititem->sale_order_no?$plknititem->sale_order_no:$plknititem->gmt_sale_order_no;
            $plknititem->style_ref=$plknititem->style_ref?$plknititem->style_ref:$plknititem->gmt_style_ref;
            $plknititem->buyer_name=$plknititem->buyer_name?$plknititem->buyer_name:$plknititem->gmt_buyer_name;
            $plknititem->gmtspart=$plknititem->gmtspart_id?$gmtspart[$plknititem->gmtspart_id]:$gmtspart[$plknititem->c_gmtspart_id];
            $plknititem->fabrication=$plknititem->autoyarn_id?$desDropdown[$plknititem->autoyarn_id]:$desDropdown[$plknititem->c_autoyarn_id];
            $plknititem->fabricshape=$plknititem->fabric_shape_id?$fabricshape[$plknititem->fabric_shape_id]:$fabricshape[$plknititem->c_fabric_shape_id];
            $plknititem->fabriclooks=$plknititem->fabric_look_id?$fabriclooks[$plknititem->fabric_look_id]:$fabriclooks[$plknititem->c_fabric_look_id];
            $plknititem->req_gsm_weight=$plknititem->gsm_weight?$plknititem->gsm_weight:$plknititem->c_gsm_weight;
            $plknititem->req_dia=$plknititem->dia?$plknititem->dia:$plknititem->c_dia;
            $plknititem->pl_start_date=date('d-M-Y',strtotime($plknititem->pl_start_date));
            $plknititem->pl_end_date=date('d-M-Y',strtotime($plknititem->pl_end_date));
            $plknititem->qty=number_format($plknititem->qty,2);
            $plknititem->prod_qty=number_format($plknititem->prod_qty,2);
            return $plknititem;
        });
        return $plknititem;
    }
    public function html(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $data=$this->getPlan($date_from,$date_to);
        echo json_encode($data);
    }
}

