<?php
namespace App\Http\Controllers\Report\FabricProduction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceItemQtyRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\SupplierRepository;


class ProdKnitDailyReportController extends Controller {
    private $prodknit;
    private $prodknititem;
    private $assetquantitycost;
    private $plknititem;
    private $plknit;
    private $poknitserviceitemqty;
    private $poknitservice;
    private $autoyarn;
    private $gmtspart;
    private $buyer;
    private $company;
    private $location;
    public function __construct(
        ProdKnitRepository $prodknit, 
        ProdKnitItemRepository $prodknititem, 
        AssetQuantityCostRepository $assetquantitycost,
        PlKnitItemRepository $plknititem,
        PlKnitRepository $plknit,
        PoKnitServiceItemQtyRepository $poknitserviceitemqty,
        PoKnitServiceRepository $poknitservice,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        BuyerRepository $buyer,
        CompanyRepository $company,
        LocationRepository $location,
        SupplierRepository $supplier
        ) 
    {
        $this->prodknit = $prodknit;
        $this->prodknititem = $prodknititem;
        $this->assetquantitycost = $assetquantitycost;
        $this->plknititem = $plknititem;
        $this->plknit = $plknit;
        $this->poknitserviceitemqty = $poknitserviceitemqty;
        $this->poknitservice = $poknitservice;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->middleware('auth');
      //$this->middleware('permission:view.prodgmtcartonqtyreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
        return Template::loadView('Report.FabricProduction.ProdKnitDailyReport',['supplier'=>$supplier,'productionsource'=>$productionsource,'location'=>$location]);
    }

    public function reportData(){
        $date_from =request('date_from',0);
        $date_to=request('date_to',0);
        $supplier_id=request('supplier_id',0);
        $location_id=request('location_id',0);
        $basis_id=request('basis_id',0);
        $supplierId='';
        if($supplier_id){
        $supplierId= ' and prod_knits.supplier_id= '.$supplier_id;
        }
        else{
        $supplierId= '';
        }

        $locationId='';
        if($location_id){
        $locationId= ' and prod_knits.location_id= '.$location_id;
        }
        else{
        $locationId= '';
        }

        $basisId='';
        if($basis_id){
        $basisId= ' and prod_knits.basis_id= '.$basis_id;
        }
        else{
        $basisId= '';
        }

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
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
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }


        $rows=collect(
            \DB::select("
            select
            prod_knits.id,
            prod_knits.prod_no,
            prod_knits.prod_date,
            prod_knits.basis_id,
            prod_knits.location_id,
            locations.name as location_name,
            prod_knits.supplier_id,
            suppliers.name as supplier_name,
            prod_knits.shift_id,
            prod_knit_items.id as prod_knit_item_id,
            prod_knit_items.pl_knit_item_id,
            prod_knit_items.gsm_weight  as knited_gsm_weight,
            prod_knit_items.dia as knited_dia,
            prod_knit_items.measurment as knited_measurment,
            asset_quantity_costs.custom_no as machine_no,
            asset_technical_features.dia_width as machine_dia,
            asset_technical_features.gauge as machine_gauge,
            companies.code bcompany_name,
            pcompanies.code pcompany_name,
            inhprods.so_knit_item_id,
            inhprods.knit_sales_order_no,
            CASE 
            WHEN  inhprods.po_knit_service_item_qty_id IS NULL THEN prod_knit_items.po_knit_service_item_qty_id
            ELSE inhprods.po_knit_service_item_qty_id
            END as po_knit_service_item_qty_id,
            CASE 
            WHEN  inhprods.autoyarn_id IS NULL THEN style_fabrications.autoyarn_id
            ELSE inhprods.autoyarn_id
            END as autoyarn_id,
            CASE 
            WHEN  inhprods.fabric_look_id IS NULL THEN style_fabrications.fabric_look_id
            ELSE inhprods.fabric_look_id
            END as fabric_look_id,
            CASE 
            WHEN  inhprods.fabric_shape_id IS NULL THEN style_fabrications.fabric_shape_id
            ELSE inhprods.fabric_shape_id
            END as fabric_shape_id,
            CASE 
            WHEN  inhprods.gmtspart_id IS NULL THEN style_fabrications.gmtspart_id
            ELSE inhprods.gmtspart_id
            END as gmtspart_id,
            CASE 
            WHEN  inhprods.gsm_weight IS NULL THEN budget_fabrics.gsm_weight
            ELSE inhprods.gsm_weight
            END as gsm_weight,
            CASE 
            WHEN  inhprods.dia IS NULL THEN po_knit_service_item_qties.dia
            ELSE inhprods.dia
            END as dia,
            CASE 
            WHEN  inhprods.measurment IS NULL THEN po_knit_service_item_qties.measurment
            ELSE inhprods.measurment
            END as measurment,
            CASE 
            WHEN  inhprods.fabric_color_name IS NULL THEN colors.name
            ELSE inhprods.fabric_color_name
            END as fabric_color_name,
            CASE 
            WHEN  inhprods.customer_name IS NULL THEN customers.name
            ELSE inhprods.customer_name
            END as customer_name,
            CASE 
            WHEN  inhprods.gmt_buyer_name IS NULL THEN buyers.name
            ELSE inhprods.gmt_buyer_name
            END as gmt_buyer_name,
            CASE 
            WHEN  inhprods.gmt_style_ref IS NULL THEN styles.style_ref
            ELSE inhprods.gmt_style_ref
            END as gmt_style_ref,
            CASE 
            WHEN  inhprods.gmt_sale_order_no IS NULL THEN sales_orders.sale_order_no
            ELSE inhprods.gmt_sale_order_no
            END as gmt_sale_order_no,
            CASE
            WHEN inhprods.inh_rate IS NULL THEN po_knit_service_item_qties.rate
            ELSE inhprods.inh_rate
            END as rate,
            CASE
            WHEN inhprods.inh_exch_rate IS NULL THEN po_knit_services.exch_rate
            ELSE inhprods.inh_exch_rate
            END as exch_rate,
            prodknititemrolls.prod_knit_qty,
            prodknititemrollqcs.prod_knit_qc_qty,
            prodknititemrolldlvs.prod_knit_dlv_qty,
            prodknititemyarns.yarn_used_qty
            from
            prod_knits
            join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
            left join (
            select
            pl_knit_items.id as pl_knit_item_id,
            so_knit_po_items.po_knit_service_item_qty_id,
            so_knit_items.id as so_knit_item_id,
            so_knit_items.autoyarn_id,
            so_knit_items.fabric_look_id,
            so_knit_items.fabric_shape_id,
            so_knit_items.gmtspart_id,
            so_knit_items.gsm_weight,
            so_knit_items.dia,
            so_knit_items.measurment,
            so_knit_items.rate as inh_rate,
            so_knits.sales_order_no as knit_sales_order_no,
            so_knits.currency_id as inh_currency_id,
            so_knits.exch_rate as inh_exch_rate,
            colors.name as fabric_color_name,
            customers.name as customer_name,
            gmts_buyers.name as gmt_buyer_name,
            so_knit_items.gmt_style_ref,
            so_knit_items.gmt_sale_order_no

            from 
            pl_knit_items
            join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
            join so_knit_refs on pl_knit_items.so_knit_ref_id=so_knit_refs.id
            left join so_knit_po_items on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
            left join  so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
            left join  so_knits on so_knits.id=so_knit_refs.so_knit_id
            left join  buyers  customers on customers.id=so_knits.buyer_id
            left join  buyers  gmts_buyers on gmts_buyers.id=so_knit_items.gmt_buyer
            left join  colors   on colors.id=so_knit_items.fabric_color_id
            where 1=1
            ) inhprods on inhprods.pl_knit_item_id=prod_knit_items.pl_knit_item_id

            left join po_knit_service_item_qties on 
            (po_knit_service_item_qties.id=prod_knit_items.po_knit_service_item_qty_id or  po_knit_service_item_qties.id=inhprods.po_knit_service_item_qty_id)
            left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
            left join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
            left join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
            left join budget_fabrics on  budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            left join style_fabrications on  style_fabrications.id=budget_fabrics.style_fabrication_id
            left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            left join jobs on jobs.id=sales_orders.job_id
            left join styles on styles.id=jobs.style_id
            left join buyers on buyers.id=styles.buyer_id
            left join companies on companies.id=jobs.company_id
            left join companies pcompanies on pcompanies.id=sales_orders.produced_company_id
            left join companies customers on customers.id=po_knit_services.company_id
            left join suppliers  on suppliers.id=prod_knits.supplier_id
            left join locations  on locations.id=prod_knits.location_id
            left join colors on colors.id=po_knit_service_item_qties.fabric_color_id
            left join asset_quantity_costs on asset_quantity_costs.id=prod_knit_items.asset_quantity_cost_id
            left join asset_technical_features on asset_technical_features.asset_acquisition_id=asset_quantity_costs.asset_acquisition_id
            left join(
            select 
            prod_knit_item_rolls.prod_knit_item_id,
            sum(prod_knit_item_rolls.roll_weight) as prod_knit_qty
            from
            prod_knit_item_rolls
            group by 
            prod_knit_item_rolls.prod_knit_item_id
            ) prodknititemrolls on prodknititemrolls.prod_knit_item_id=prod_knit_items.id
            left join(
            select 
            prod_knit_item_rolls.prod_knit_item_id,
            sum(prod_knit_item_rolls.roll_weight) as prod_knit_qc_qty
            from
            prod_knit_item_rolls
            join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.prod_knit_item_roll_id=prod_knit_item_rolls.id
            join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id
            group by 
            prod_knit_item_rolls.prod_knit_item_id
            ) prodknititemrollqcs on prodknititemrollqcs.prod_knit_item_id=prod_knit_items.id
            left join(
            select 
            prod_knit_item_rolls.prod_knit_item_id,
            sum(prod_knit_item_rolls.roll_weight) as prod_knit_dlv_qty
            from
            prod_knit_item_rolls
            join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.prod_knit_item_roll_id=prod_knit_item_rolls.id
            join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id
            join prod_knit_dlv_rolls on prod_knit_dlv_rolls.prod_knit_qc_id=prod_knit_qcs.id
            group by 
            prod_knit_item_rolls.prod_knit_item_id
            ) prodknititemrolldlvs on prodknititemrolldlvs.prod_knit_item_id=prod_knit_items.id

            left join(
            select 
            prod_knit_item_yarns.prod_knit_item_id,
            sum(prod_knit_item_yarns.qty) as yarn_used_qty
            from
            prod_knit_item_yarns
            group by 
            prod_knit_item_yarns.prod_knit_item_id
            ) prodknititemyarns on prodknititemyarns.prod_knit_item_id=prod_knit_items.id

            where prod_knits.prod_date>='".$date_from."'
            and prod_knits.prod_date<='".$date_to."'  $supplierId $basisId $locationId
            order by prod_knits.id
            ")
        )
        ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$buyer,$company){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:'';
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
            $rows->prod_knit_qc_wip=$rows->prod_knit_qc_qty-$rows->prod_knit_qty;
            $rows->prod_knit_dlv_wip=$rows->prod_knit_dlv_qty-$rows->prod_knit_qc_qty;
            $rows->knit_charge=$rows->rate*$rows->prod_knit_qc_qty*$rows->exch_rate;
            $rows->knit_charge=number_format($rows->knit_charge,2);
            $rows->rate=number_format($rows->rate,2);
            $rows->prod_knit_qty=number_format($rows->prod_knit_qty,2);
            $rows->yarn_used_qty=number_format($rows->yarn_used_qty,2);
            $rows->prod_knit_qc_qty=number_format($rows->prod_knit_qc_qty,2);
            $rows->prod_knit_dlv_qty=number_format($rows->prod_knit_dlv_qty,2);
            $rows->prod_knit_qc_wip=number_format($rows->prod_knit_qc_wip,2);
            $rows->prod_knit_dlv_wip=number_format($rows->prod_knit_dlv_wip,2);
            $rows->prod_date=date('d-M-Y',strtotime($rows->prod_date));
            return $rows;
        });

        echo json_encode($rows);
    }

}