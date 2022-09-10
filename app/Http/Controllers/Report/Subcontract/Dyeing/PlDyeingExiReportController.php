<?php

namespace App\Http\Controllers\Report\Subcontract\Dyeing;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;

use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingItemRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
class PlDyeingExiReportController extends Controller
{
    private $company;
    private $buyer;
    private $location;
    private $assetacquisition;
    private $pldyeingitem;
    private $gmtspart;
    private $autoyarn;

	public function __construct(
        CompanyRepository $company,
        BuyerRepository $buyer,
        LocationRepository $location,
        AssetAcquisitionRepository $assetacquisition,
        PlDyeingItemRepository $pldyeingitem,
        GmtspartRepository $gmtspart,
        AutoyarnRepository $autoyarn
    )
    {
		
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->assetacquisition = $assetacquisition;
        $this->pldyeingitem = $pldyeingitem;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->middleware('auth');
        //$this->middleware('permission:view.subinbmarketingreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.Subcontract.Dyeing.PlDyeingExiReport',['company'=>$company,'buyer'=>$buyer,'location'=>$location]);
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
        
        $pldyeingitem=$this->pldyeingitem
        ->join('pl_dyeings', function($join)  {
            $join->on('pl_dyeings.id', '=', 'pl_dyeing_items.pl_dyeing_id');
        })
        ->join('pl_dyeing_item_qties', function($join)  {
            $join->on('pl_dyeing_item_qties.pl_dyeing_item_id', '=', 'pl_dyeing_items.id');
        })
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','pl_dyeings.machine_id');
        })
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->join('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'pl_dyeing_items.colorrange_id');
        })
        ->join('so_dyeing_refs', function($join)  {
            $join->on('so_dyeing_refs.id', '=', 'pl_dyeing_items.so_dyeing_ref_id');
        })
        ->join('so_dyeings',function($join){
            $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_po_items', function($join)  {
            $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
        })
        ->leftJoin('po_dyeing_service_item_qties',function($join){
              $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
        })
        ->leftJoin('po_dyeing_service_items',function($join){
                 $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
                 ->whereNull('po_dyeing_service_items.deleted_at');
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
        ->leftJoin('so_dyeing_items', function($join)  {
            $join->on('so_dyeing_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
        })
        ->leftJoin('colors as so_color',function($join){
            $join->on('so_color.id','=','so_dyeing_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
            $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
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
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','jobs.company_id');
        })
        ->leftJoin('companies as pcompanies',function($join){
            $join->on('pcompanies.id','=','sales_orders.produced_company_id');
        })
        ->leftJoin('companies as dyeingcompanies',function($join){
            $join->on('dyeingcompanies.id','=','so_dyeings.company_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('buyers as gmt_buyer',function($join){
            $join->on('gmt_buyer.id','=','so_dyeing_items.gmt_buyer');
        })
        ->leftJoin('buyers as customers',function($join){
            $join->on('customers.id','=','so_dyeings.buyer_id');
        })
        ->where([['pl_dyeings.company_id','=',request('company_id',0)]])
        ->when(request('date_from'), function ($q) use($date_from){
        return $q->where('pl_dyeing_item_qties.pl_date', '>=',$date_from);
        })
        ->when(request('date_to'), function ($q) use($date_to) {
        return $q->where('pl_dyeing_item_qties.pl_date', '<=',$date_to);
        })
        /*->when(request('date_from'), function ($q) use($date_from){
        return $q->where('pl_dyeing_items.pl_start_date', '>=',$date_from);
        })
        ->when(request('date_to'), function ($q) use($date_to) {
        return $q->where('pl_dyeing_items.pl_end_date', '<=',$date_to);
        })*/
        ->orderBy('pl_dyeing_items.id')
        ->selectRaw('
            pl_dyeing_items.id,
            pl_dyeing_items.pl_start_date,
            pl_dyeing_items.pl_end_date,
            pl_dyeing_items.gsm_weight as pl_gsm_weight,
            pl_dyeing_items.dia as pl_dia,
            pl_dyeing_items.qty,
            pl_dyeings.machine_id,
            min(pl_dyeing_item_qties.pl_date) as min_date,
            max(pl_dyeing_item_qties.pl_date) as max_date,
            sum(pl_dyeing_item_qties.qty) as day_qty,
            so_color.name as c_fabric_color_name,
            po_color.name as fabric_color_name,
            
            
            companies.code as company_name,
            pcompanies.code as prod_company_name,
            dyeingcompanies.code as dyeing_company,
            customers.name as customer_name,
            styles.style_ref,
            so_dyeing_items.gmt_style_ref,
            sales_orders.sale_order_no,
            so_dyeing_items.gmt_sale_order_no,
            buyers.name as buyer_name,
            gmt_buyer.name as gmt_buyer_name,
            so_dyeings.sales_order_no as dyeing_sale_order,
            style_fabrications.gmtspart_id,
            so_dyeing_items.gmtspart_id as c_gmtspart_id,
            style_fabrications.autoyarn_id,
            so_dyeing_items.autoyarn_id as c_autoyarn_id,
            style_fabrications.fabric_shape_id,
            so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
            style_fabrications.fabric_look_id,
            so_dyeing_items.fabric_shape_id as c_fabric_look_id,
            po_dyeing_service_item_qties.dia,
            so_dyeing_items.dia as c_dia,
            budget_fabrics.gsm_weight,
            so_dyeing_items.gsm_weight as c_gsm_weight,
            asset_acquisitions.brand,
            asset_quantity_costs.custom_no as machine_no,
            asset_acquisitions.prod_capacity as capacity'
              )
        ->groupBy([
            'pl_dyeing_items.id',
            'pl_dyeing_items.pl_start_date',
            'pl_dyeing_items.pl_end_date',
            'pl_dyeing_items.gsm_weight',
            'pl_dyeing_items.dia',
            'pl_dyeing_items.qty',
            'pl_dyeings.machine_id',
            //'pl_dyeing_item_qties.pl_date',
            //'pl_dyeing_item_qties.qty as date_qty',
            'so_color.name',
            'po_color.name',
            
            
            'companies.code',
            'pcompanies.code',
            'dyeingcompanies.code',
            'customers.name',
            'styles.style_ref',
            'so_dyeing_items.gmt_style_ref',
            'sales_orders.sale_order_no',
            'so_dyeing_items.gmt_sale_order_no',
            'buyers.name',
            'gmt_buyer.name',
            'so_dyeings.sales_order_no',
            'style_fabrications.gmtspart_id',
            'so_dyeing_items.gmtspart_id',
            'style_fabrications.autoyarn_id',
            'so_dyeing_items.autoyarn_id',
            'style_fabrications.fabric_shape_id',
            'so_dyeing_items.fabric_shape_id',
            'style_fabrications.fabric_look_id',
            'so_dyeing_items.fabric_shape_id',
            'po_dyeing_service_item_qties.dia',
            'so_dyeing_items.dia',
            'budget_fabrics.gsm_weight',
            'so_dyeing_items.gsm_weight',
            'asset_acquisitions.brand',
            'asset_quantity_costs.custom_no',
            'asset_acquisitions.prod_capacity'
        ])
        ->get()
        ->map(function($pldyeingitem) use($gmtspart,$desDropdown,$fabricshape,$fabriclooks){
            $pldyeingitem->fabric_color=$pldyeingitem->fabric_color_name?$pldyeingitem->fabric_color_name:$pldyeingitem->c_fabric_color_name;
            $pldyeingitem->sales_order_no=$pldyeingitem->sale_order_no?$pldyeingitem->sale_order_no:$pldyeingitem->gmt_sale_order_no;
            $pldyeingitem->style_ref=$pldyeingitem->style_ref?$pldyeingitem->style_ref:$pldyeingitem->gmt_style_ref;
            $pldyeingitem->buyer_name=$pldyeingitem->buyer_name?$pldyeingitem->buyer_name:$pldyeingitem->gmt_buyer_name;
            $pldyeingitem->gmtspart=$pldyeingitem->gmtspart_id?$gmtspart[$pldyeingitem->gmtspart_id]:$gmtspart[$pldyeingitem->c_gmtspart_id];
            $pldyeingitem->fabrication=$pldyeingitem->autoyarn_id?$desDropdown[$pldyeingitem->autoyarn_id]:$desDropdown[$pldyeingitem->c_autoyarn_id];
            $pldyeingitem->fabricshape=$pldyeingitem->fabric_shape_id?$fabricshape[$pldyeingitem->fabric_shape_id]:$fabricshape[$pldyeingitem->c_fabric_shape_id];
            $pldyeingitem->fabriclooks=$pldyeingitem->fabric_look_id?$fabriclooks[$pldyeingitem->fabric_look_id]:$fabriclooks[$pldyeingitem->c_fabric_look_id];
            $pldyeingitem->req_gsm_weight=$pldyeingitem->gsm_weight?$pldyeingitem->gsm_weight:$pldyeingitem->c_gsm_weight;
            $pldyeingitem->req_dia=$pldyeingitem->dia?$pldyeingitem->dia:$pldyeingitem->c_dia;
            $pldyeingitem->pl_start_date=date('d-M-Y',strtotime($pldyeingitem->pl_start_date));
            $pldyeingitem->pl_end_date=date('d-M-Y',strtotime($pldyeingitem->pl_end_date));
            $pldyeingitem->qty=number_format($pldyeingitem->qty,2);



            return $pldyeingitem;
        });
        return $pldyeingitem;
    }
    public function html(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $data=$this->getPlan($date_from,$date_to);
        echo json_encode($data);
    }
}

