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
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use Illuminate\Support\Carbon;


class ProdDyeingDailyReportController extends Controller {
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
    private $invdyechemisurq;

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
        SupplierRepository $supplier,
        InvDyeChemIsuRqRepository $invdyechemisurq
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
        $this->supplier = $supplier;
        $this->invdyechemisurq = $invdyechemisurq;

        $this->middleware('auth');
      //$this->middleware('permission:view.prodgmtcartonqtyreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        return Template::loadView('Report.FabricProduction.ProdDyeingDailyReport',['shiftname'=>$shiftname]);
    }

    public function reportData(){

        $date_from =request('date_from',0);
        $date_to=request('date_to',0);
        $unload_shift=request('unload_shift',0);
        //$time_from=request('time_from',0);
        //$time_to=request('time_to',0);
        
      //  $unloaded_at_from=date('m/d/Y H:i:s',strtotime($date_from." ".$time_from));
       // $unloaded_at_to=date('m/d/Y H:i:s',strtotime($date_to." ".$time_to));

        // $unloaded_at_from=date('Y-m-d H:i:s',strtotime($date_from." ".$time_from));
        // $unloaded_at_to=date('Y-m-d H:i:s',strtotime($date_to." ".$time_to));

        // $timeFrom=null;
        // $timeTo=null;
        $shift=null;
        if ($unload_shift) {
            $shift="and prod_batches.unload_shift = $unload_shift ";
        }
        // $timeFrom="and prod_batches.unloaded_at >= TO_DATE('".$unloaded_at_from."', 'MM/DD/YYYY HH24:MI:SS')";
        //$timeTo="and prod_batches.unloaded_at <= TO_DATE('".$unloaded_at_to."', 'MM/DD/YYYY HH24:MI:SS')";
        
        // if ($time_from) {
        //     $timeFrom="and prod_batches.unloaded_at >= '".$unloaded_at_from."'";
        // }
        // if ($time_to) { 
        //  $timeTo="and prod_batches.unloaded_at <= '".$unloaded_at_to."'";
        // }

        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
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
            SELECT 
            m.prod_batch_id,
            m.batch_no,
            m.batch_date,
            m.load_date,
            m.unload_date,
            m.loaded_at,
            m.unloaded_at,
            m.tgt_hour,
            m.unload_shift,
            m.prod_capacity,
            m.machine_no,
            m.customer_name,
            m.batch_color_name,
            m.bcompany_code,
            m.pcompany_code,
            m.autoyarn_id,
            m.gmtspart_id,
            m.fabric_look_id,
            m.fabric_shape_id,
            m.gsm_weight,
            m.dia_width,
            m.gmt_buyer_name,
            m.gmt_sale_order_no,
            m.gmt_style_ref,
            sum(m.qty) as batch_qty
            FROM
                (SELECT
                prod_batches.id as prod_batch_id,
                prod_batches.batch_no,
                prod_batches.batch_date,
                prod_batches.loaded_at,
                prod_batches.unloaded_at,
                prod_batches.load_date,
                prod_batches.unload_date,
                prod_batches.tgt_hour,
                prod_batches.unload_shift,
                asset_acquisitions.prod_capacity,
                asset_quantity_costs.custom_no as machine_no,
                buyers.name as customer_name,
                batch_colors.name as batch_color_name,
                self_order.bcompany_code,
                self_order.pcompany_code,
                CASE 
                WHEN  self_order.autoyarn_id IS NULL THEN so_dyeing_items.autoyarn_id
                ELSE self_order.autoyarn_id
                END as autoyarn_id,
                CASE 
                WHEN  self_order.fabric_look_id IS NULL THEN so_dyeing_items.fabric_look_id
                ELSE self_order.fabric_look_id
                END as fabric_look_id,
                CASE 
                WHEN  self_order.fabric_shape_id IS NULL THEN so_dyeing_items.fabric_shape_id
                ELSE self_order.fabric_shape_id
                END as fabric_shape_id,
                CASE 
                WHEN  self_order.gmtspart_id IS NULL THEN so_dyeing_items.gmtspart_id
                ELSE self_order.gmtspart_id
                END as gmtspart_id,
                CASE 
                WHEN  self_order.gsm_weight IS NULL THEN so_dyeing_items.gsm_weight
                ELSE self_order.gsm_weight
                END as gsm_weight,
                CASE 
                WHEN  self_order.dia_width IS NULL THEN so_dyeing_items.dia
                ELSE self_order.dia_width
                END as dia_width,
                CASE 
                WHEN  self_order.measurement IS NULL THEN so_dyeing_items.measurment
                ELSE self_order.measurement
                END as measurment,
                CASE 
                WHEN  gmt_buyers.name IS NULL THEN self_order.buyer_name
                ELSE gmt_buyers.name
                END as gmt_buyer_name,
                CASE 
                WHEN  so_dyeing_items.gmt_style_ref IS NULL THEN self_order.style_ref
                ELSE so_dyeing_items.gmt_style_ref
                END as gmt_style_ref,
                CASE 
                WHEN  so_dyeing_items.gmt_sale_order_no IS NULL THEN self_order.sale_order_no
                ELSE so_dyeing_items.gmt_sale_order_no
                END as gmt_sale_order_no,
                prod_batch_rolls.qty

                from
                prod_batches
                join colors batch_colors on batch_colors.id=prod_batches.batch_color_id
                join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
                join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
                join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id=so_dyeing_fabric_rcv_items.id
                join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
                join so_dyeings on so_dyeing_refs.so_dyeing_id=so_dyeings.id
                join buyers on buyers.id=so_dyeings.buyer_id
                --/////Self Order
                left join (
                    select
                    prod_batch_rolls.id as prod_batch_roll_id,
                    inv_grey_fab_items.autoyarn_id,
                    inv_grey_fab_items.gmtspart_id,
                    inv_grey_fab_items.fabric_look_id,
                    inv_grey_fab_items.fabric_shape_id,
                    inv_grey_fab_items.gsm_weight,
                    inv_grey_fab_items.dia as dia_width,
                    inv_grey_fab_items.measurment as measurement,
                    sales_orders.sale_order_no,
                    styles.style_ref,
                    bcompany.code as bcompany_code,
                    pcompanies.code as pcompany_code,
                    buyers.name as buyer_name
                    from
                    prod_batch_rolls
                    join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
                    join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id=so_dyeing_fabric_rcv_items.id
                    join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
                    join so_dyeings on so_dyeing_refs.so_dyeing_id=so_dyeings.id
                    left join so_dyeing_pos on so_dyeing_pos.so_dyeing_id=so_dyeings.id
                    join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
                    join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
                    join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id
                    join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
                    join jobs on jobs.id=sales_orders.job_id
                    join styles on styles.id=jobs.style_id
                    join buyers on buyers.id=styles.buyer_id
                    join companies bcompany on bcompany.id=jobs.company_id
                    left join companies pcompanies on pcompanies.id=sales_orders.produced_company_id
                    join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id=so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
                    join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
                    join inv_grey_fab_items on inv_grey_fab_items.id=inv_grey_fab_isu_items.inv_grey_fab_item_id
                    where 
                    prod_batch_rolls.deleted_at is null and
                    so_dyeing_fabric_rcv_rols.deleted_at is null and
                    po_dyeing_service_items.deleted_at is null and
                    inv_grey_fab_items.deleted_at is null
                )self_order on self_order.prod_batch_roll_id=prod_batch_rolls.id
                --//Subcontract
                left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
                left join buyers gmt_buyers on gmt_buyers.ID=so_dyeing_items.GMT_BUYER
                left join asset_quantity_costs on asset_quantity_costs.id=prod_batches.machine_id
                join asset_acquisitions on asset_acquisitions.id=asset_quantity_costs.asset_acquisition_id

                where
                prod_batches.unload_posting_date >= '".$date_from."' and 
                prod_batches.unload_posting_date <= '".$date_to."' and
                prod_batches.is_redyeing=0 and 
                prod_batches.deleted_at is null and 
                prod_batch_rolls.deleted_at is null and 
                prod_batches.unloaded_at is not null
                $shift
            )m

            group by
                m.prod_batch_id,
                m.batch_no,
                m.batch_date,
                m.load_date,
                m.unload_date,
                m.loaded_at,
                m.unloaded_at,
                m.tgt_hour,
                m.unload_shift,
                m.prod_capacity,
                m.machine_no,
                m.customer_name,
                m.batch_color_name,
                m.bcompany_code,
                m.pcompany_code,
                m.autoyarn_id,
                m.gmtspart_id,
                m.fabric_look_id,
                m.fabric_shape_id,
                m.gsm_weight,
                m.dia_width,
                m.gmt_buyer_name,
                m.gmt_sale_order_no,
                m.gmt_style_ref
            ")
        )
        ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$shiftname){/* ,$buyer,$company */

            $loadTime=Carbon::parse($rows->loaded_at);
            $unloadTime=Carbon::parse($rows->unloaded_at);
            $rows->time_taken=$unloadTime->diffInHours($loadTime);
            $rows->deviation=$rows->tgt_hour-$rows->time_taken;
            $rows->unload_shift=$shiftname[$rows->unload_shift];
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:'';
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';
            $rows->batch_qty=number_format($rows->batch_qty,2);
            $rows->batch_date=date('d-M-Y',strtotime($rows->batch_date));
            $rows->load_time=date('h:i A',strtotime($rows->loaded_at));
            $rows->unload_time=date('h:i A',strtotime($rows->unloaded_at));
            $rows->unload_date=date('d-M-Y',strtotime($rows->unload_date));
            $rows->load_date=date('d-M-Y',strtotime($rows->load_date));
            return $rows;
        });

        echo json_encode($rows);
    }

    public function getDyeingIsuRq(){
        $prod_batch_id=request('prod_batch_id',0);
        $rows = $this->invdyechemisurq
        ->join('prod_batches',function($join){
            $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','prod_batches.company_id');
        })
        ->leftJoin('locations',function($join){
            $join->on('locations.id','=','prod_batches.location_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->join('colors batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
            })
        ->join('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
        ->where([['inv_dye_chem_isu_rqs.menu_id','=',208]])
        ->where([['inv_dye_chem_isu_rqs.prod_batch_id','=',$prod_batch_id]])
        ->orderBy('inv_dye_chem_isu_rqs.id','desc')
        ->get([
            'inv_dye_chem_isu_rqs.*',
            'colors.name as fabric_color',
            'batch_colors.name as batch_color',
            'colorranges.name as colorrange_name',
            'prod_batches.colorrange_id',
            'prod_batches.batch_no',
            'prod_batches.lap_dip_no',
            'prod_batches.batch_wgt',
            'companies.code as company_id',
            'locations.name as location_id',
        ]);

        echo json_encode($rows);
    }

}