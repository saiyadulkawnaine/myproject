<?php

namespace App\Http\Controllers\Inventory\GreyFabric;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabRcvRepository;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabRcvItemRepository;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabTransactionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\GreyFabric\InvGreyFabTransInItemRequest;

class InvGreyFabTransInItemController extends Controller {

    private $invrcv;
    private $invgreyfabrcv;
    private $invgreyfabrcvitem;
    private $invgreyfabtransaction;
    private $itemaccount;
    private $invisu;
    private $gmtspart;
    private $store;

    public function __construct(
        InvRcvRepository $invrcv,
        InvGreyFabRcvRepository $invgreyfabrcv, 
        InvGreyFabRcvItemRepository $invgreyfabrcvitem,
        InvGreyFabTransactionRepository $invgreyfabtransaction, 
        ItemAccountRepository $itemaccount,
        InvIsuRepository $invisu,
        GmtspartRepository $gmtspart,
        StoreRepository $store
    ) {
        $this->invrcv = $invrcv;
        $this->invgreyfabrcv = $invgreyfabrcv;
        $this->invgreyfabrcvitem = $invgreyfabrcvitem;
        $this->invgreyfabtransaction = $invgreyfabtransaction;
        $this->itemaccount = $itemaccount;
        $this->invisu = $invisu;
        $this->gmtspart = $gmtspart;
        $this->store = $store;
        $this->middleware('auth');
        //$this->middleware('permission:view.invgreyfabrcv',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invgreyfabrcv', ['only' => ['store']]);
        //$this->middleware('permission:edit.invgreyfabrcv',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invgreyfabrcv', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $inv_grey_fab_rcv_id=request('inv_grey_fab_rcv_id',0);
        $invgreyfabrcv=$this->invgreyfabrcv->find($inv_grey_fab_rcv_id);
        $invcv=$this->invrcv->find($invgreyfabrcv->inv_rcv_id);

        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $fabricDescription = collect(\DB::select("
            select
            autoyarns.id,
            constructions.name as construction,
            compositions.name,
            autoyarnratios.ratio
            FROM autoyarns
            join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
            join compositions on compositions.id = autoyarnratios.composition_id
            join constructions on constructions.id = autoyarns.construction_id
            "
        ));

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
            $fabricDescriptionArr[$row->id]=$row->construction;
            $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
        }
        
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $prodknitqc=$this->invrcv
        ->selectRaw('
            inv_grey_fab_rcv_items.id,
            inv_grey_fab_rcv_items.qty,
            prod_knit_dlvs.store_id,

            inv_grey_fab_items.autoyarn_id,
            inv_grey_fab_items.gmtspart_id,
            inv_grey_fab_items.fabric_look_id,
            inv_grey_fab_items.fabric_shape_id,
            inv_grey_fab_items.gsm_weight,
            inv_grey_fab_items.dia as dia_width,
            inv_grey_fab_items.measurment as measurement,
            inv_grey_fab_items.roll_length,
            inv_grey_fab_items.stitch_length,
            inv_grey_fab_items.shrink_per,
            inv_grey_fab_items.colorrange_id,
            colorranges.name as colorrange_name,
            inv_grey_fab_items.color_id,
            colors.name as fabric_color,
            inv_grey_fab_items.supplier_id,


            prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id, 
            prod_knit_qcs.id as prod_knit_qc_id,   
            --prod_knit_qcs.gsm_weight,   
            --prod_knit_qcs.dia_width,   
            --prod_knit_qcs.measurement,   
            --prod_knit_qcs.roll_length,   
            --prod_knit_qcs.shrink_per,   
            prod_knit_qcs.reject_qty,   
            prod_knit_qcs.qc_pass_qty,   
            prod_knit_qcs.reject_qty_pcs,   
            prod_knit_qcs.qc_pass_qty_pcs,   
            prod_knit_qcs.qc_result,

            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,
            prod_knit_item_rolls.roll_weight,
            prod_knit_item_rolls.width,
            prod_knit_item_rolls.qty_pcs,
            --prod_knit_item_rolls.fabric_color,
            prod_knit_item_rolls.gmt_sample,
            prod_knit_items.prod_knit_id,
            --prod_knit_items.stitch_length,

            prod_knits.shift_id,
            prod_knits.prod_no,
            --prod_knits.supplier_id,
            prod_knits.location_id,
            prod_knits.floor_id,

            suppliers.name as supplier_name,
            locations.name as location_name,
            floors.name as floor_name,
            asset_quantity_costs.custom_no as machine_no,
            asset_technical_features.dia_width as machine_dia,
            asset_technical_features.gauge as machine_gg,
            gmtssamples.name as gmt_sample,
            

            
            CASE 
            WHEN  inhouseprods.sale_order_no IS NULL THEN outhouseprods.sale_order_no 
            ELSE inhouseprods.sale_order_no
            END as sale_order_no,
            CASE 
            WHEN  inhouseprods.style_ref IS NULL THEN outhouseprods.style_ref 
            ELSE inhouseprods.style_ref
            END as style_ref,

            CASE 
            WHEN  inhouseprods.buyer_name IS NULL THEN outhouseprods.buyer_name 
            ELSE inhouseprods.buyer_name
            END as buyer_name,

            CASE 
            WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
            ELSE inhouseprods.customer_name
            END as customer_name

            
        ')
        ->join('inv_grey_fab_rcvs',function($join){
            $join->on('inv_grey_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
        })
        ->join('inv_grey_fab_rcv_items',function($join){
            $join->on('inv_grey_fab_rcv_items.inv_grey_fab_rcv_id', '=', 'inv_grey_fab_rcvs.id');
        })
        ->join('inv_grey_fab_items',function($join){
            $join->on('inv_grey_fab_items.id', '=', 'inv_grey_fab_rcv_items.inv_grey_fab_item_id');
        })
        
        ->join('prod_knit_dlv_rolls',function($join){
            //$join->on('prod_knit_dlvs.id', '=', 'prod_knit_dlv_rolls.prod_knit_dlv_id');
            $join->on('inv_grey_fab_rcv_items.prod_knit_dlv_roll_id', '=', 'prod_knit_dlv_rolls.id');
        })
        ->join('prod_knit_dlvs',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'prod_knit_dlv_rolls.prod_knit_dlv_id');
        })
        ->join('prod_knit_qcs',function($join){
            $join->on('prod_knit_qcs.id', '=', 'prod_knit_dlv_rolls.prod_knit_qc_id');
        })
        ->join('prod_knit_rcv_by_qcs',function($join){
            $join->on('prod_knit_rcv_by_qcs.id', '=', 'prod_knit_qcs.prod_knit_rcv_by_qc_id');
        })
        ->join('prod_knit_item_rolls',function($join){
            $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
        })
        ->join('prod_knit_items',function($join){
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
        })
        ->join ('prod_knits',function($join){
            $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
        })
        ->join ('suppliers',function($join){
            $join->on('suppliers.id', '=', 'inv_grey_fab_items.supplier_id');
        })
        ->leftJoin ('colorranges',function($join){
            $join->on('colorranges.id', '=', 'inv_grey_fab_items.colorrange_id');
        })
         ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
        })
        ->leftJoin ('locations',function($join){
            $join->on('locations.id', '=', 'prod_knits.location_id');
        })
        ->leftJoin ('floors',function($join){
            $join->on('floors.id', '=', 'prod_knits.floor_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
        })
        ->leftJoin('gmtssamples',function($join){
            $join->on('gmtssamples.id','=','prod_knit_item_rolls.gmt_sample');
        })

        ->leftJoin(\DB::raw("(
            select 
            pl_knit_items.id,
            colorranges.name as colorrange_name,
            colorranges.id as colorrange_id,
            customer.name as customer_name,
            CASE 
            WHEN  style_fabrications.autoyarn_id IS NULL THEN so_knit_items.autoyarn_id 
            ELSE style_fabrications.autoyarn_id
            END as autoyarn_id,

            CASE 
            WHEN  style_fabrications.gmtspart_id IS NULL THEN so_knit_items.gmtspart_id 
            ELSE style_fabrications.gmtspart_id
            END as gmtspart_id,

            CASE 
            WHEN  style_fabrications.fabric_look_id IS NULL THEN so_knit_items.fabric_look_id 
            ELSE style_fabrications.fabric_look_id
            END as fabric_look_id,

            CASE 
            WHEN  style_fabrications.fabric_shape_id IS NULL THEN so_knit_items.fabric_shape_id 
            ELSE style_fabrications.fabric_shape_id
            END as fabric_shape_id,
            CASE 
            WHEN sales_orders.sale_order_no IS NULL THEN so_knit_items.gmt_sale_order_no 
            ELSE sales_orders.sale_order_no
            END as sale_order_no,
            CASE 
            WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
            ELSE styles.style_ref
            END as style_ref,
            CASE 
            WHEN buyers.name IS NULL THEN outbuyers.name 
            ELSE buyers.name
            END as buyer_name
            from pl_knit_items
            join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
            left join colorranges on colorranges.id=pl_knit_items.colorrange_id
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
        ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
        ->leftJoin(\DB::raw("(
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        colorranges.id as colorrange_id,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        buyers.name as buyer_name,
        companies.name as customer_name  
        from 
        po_knit_service_item_qties
        join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
        join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
        left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
        join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
        join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
        
        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join companies on companies.id=po_knit_services.company_id
        order by po_knit_service_item_qties.id
        ) outhouseprods"),"outhouseprods.id","=","prod_knit_items.po_knit_service_item_qty_id")
        ->where([['inv_grey_fab_rcvs.id','=',$inv_grey_fab_rcv_id]])
        ->orderBy('inv_grey_fab_rcv_items.id','desc')
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            return $prodknitqc;
        });

        echo json_encode($prodknitqc);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    	$inv_grey_fab_isu_item_id=request('inv_grey_fab_isu_item_id',0);
    	$inv_grey_fab_isu_item_ids=explode(',',$inv_grey_fab_isu_item_id);


    	$invrcv=$this->invrcv->find(request('inv_rcv_id',0));
        $invgreyfabrcv=$this->invgreyfabrcv->where([['inv_rcv_id','=',request('inv_rcv_id',0)]])->get()->first();
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');

        $fabricDescription = collect(\DB::select("
            select
            autoyarns.id,
            constructions.name as construction,
            compositions.name,
            autoyarnratios.ratio
            FROM autoyarns
            join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
            join compositions on compositions.id = autoyarnratios.composition_id
            join constructions on constructions.id = autoyarns.construction_id
            "
        ));

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
            $fabricDescriptionArr[$row->id]=$row->construction;
            $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
        }
        
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $prodknitqc=$this->invisu
        ->selectRaw('
            inv_grey_fab_isu_items.id,
            inv_grey_fab_isu_items.qty as rcv_qty,

            inv_grey_fab_rcv_items.inv_grey_fab_item_id,
            inv_grey_fab_rcv_items.store_id,
            prod_knit_dlvs.store_id,
            prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id, 
            prod_knit_qcs.id as prod_knit_qc_id,   
            prod_knit_qcs.gsm_weight,   
            prod_knit_qcs.dia_width,   
            prod_knit_qcs.measurement,   
            prod_knit_qcs.roll_length,   
            prod_knit_qcs.shrink_per,   
            prod_knit_qcs.reject_qty,   
            prod_knit_qcs.qc_pass_qty,   
            prod_knit_qcs.reject_qty_pcs,   
            prod_knit_qcs.qc_pass_qty_pcs,   
            prod_knit_qcs.qc_result,

            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,
            prod_knit_item_rolls.roll_weight,
            prod_knit_item_rolls.width,
            prod_knit_item_rolls.qty_pcs,
            prod_knit_item_rolls.fabric_color,
            colors.name as fabric_color_name,
            prod_knit_item_rolls.gmt_sample,
            prod_knit_items.prod_knit_id,
            prod_knit_items.stitch_length,

            prod_knits.shift_id,
            prod_knits.prod_no,
            prod_knits.supplier_id,
            prod_knits.location_id,
            prod_knits.floor_id,

            suppliers.name as supplier_name,
            locations.name as location_name,
            floors.name as floor_name,
            asset_quantity_costs.custom_no as machine_no,
            asset_technical_features.dia_width as machine_dia,
            asset_technical_features.gauge as machine_gg,
            gmtssamples.name as gmt_sample,
            CASE 
            WHEN  inhouseprods.autoyarn_id IS NULL THEN outhouseprods.autoyarn_id 
            ELSE inhouseprods.autoyarn_id
            END as autoyarn_id,
            CASE 
            WHEN  inhouseprods.gmtspart_id IS NULL THEN outhouseprods.gmtspart_id 
            ELSE inhouseprods.gmtspart_id
            END as gmtspart_id,
            CASE 
            WHEN  inhouseprods.fabric_look_id IS NULL THEN outhouseprods.fabric_look_id 
            ELSE inhouseprods.fabric_look_id
            END as fabric_look_id,

            CASE 
            WHEN  inhouseprods.fabric_shape_id IS NULL THEN outhouseprods.fabric_shape_id 
            ELSE inhouseprods.fabric_shape_id
            END as fabric_shape_id,

            CASE 
            WHEN  inhouseprods.colorrange_name IS NULL THEN outhouseprods.colorrange_name 
            ELSE inhouseprods.colorrange_name
            END as colorrange_name,

            CASE 
            WHEN  inhouseprods.colorrange_id IS NULL THEN outhouseprods.colorrange_id 
            ELSE inhouseprods.colorrange_id
            END as colorrange_id,

            
            CASE 
            WHEN  inhouseprods.sale_order_no IS NULL THEN outhouseprods.sale_order_no 
            ELSE inhouseprods.sale_order_no
            END as sale_order_no,
            CASE 
            WHEN  inhouseprods.style_ref IS NULL THEN outhouseprods.style_ref 
            ELSE inhouseprods.style_ref
            END as style_ref,

            CASE 
            WHEN  inhouseprods.buyer_name IS NULL THEN outhouseprods.buyer_name 
            ELSE inhouseprods.buyer_name
            END as buyer_name,

            CASE 
            WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
            ELSE inhouseprods.customer_name
            END as customer_name

            
        ')
        ->join('inv_grey_fab_isu_items',function($join){
            $join->on('inv_isus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
        })
        ->join('inv_grey_fab_rcv_items',function($join){
            $join->on('inv_grey_fab_rcv_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id');
        })
        ->join('inv_grey_fab_rcvs',function($join){
            $join->on('inv_grey_fab_rcvs.id', '=', 'inv_grey_fab_rcv_items.inv_grey_fab_rcv_id');
        })
        ->join('inv_rcvs',function($join){
            $join->on('inv_rcvs.id', '=', 'inv_grey_fab_rcvs.inv_rcv_id');
        })
        ->join('prod_knit_dlvs',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'inv_grey_fab_rcvs.prod_knit_dlv_id');
        })
        ->join('prod_knit_dlv_rolls',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'prod_knit_dlv_rolls.prod_knit_dlv_id');
            $join->on('inv_grey_fab_rcv_items.prod_knit_dlv_roll_id', '=', 'prod_knit_dlv_rolls.id');
        })
        ->join('prod_knit_qcs',function($join){
            $join->on('prod_knit_qcs.id', '=', 'prod_knit_dlv_rolls.prod_knit_qc_id');
        })
        ->join('prod_knit_rcv_by_qcs',function($join){
            $join->on('prod_knit_rcv_by_qcs.id', '=', 'prod_knit_qcs.prod_knit_rcv_by_qc_id');
        })
        ->join('prod_knit_item_rolls',function($join){
            $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
        })
        ->join('prod_knit_items',function($join){
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
        })
        ->join ('prod_knits',function($join){
            $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
        })
        ->join ('suppliers',function($join){
            $join->on('suppliers.id', '=', 'prod_knits.supplier_id');
        })
        ->leftJoin ('locations',function($join){
            $join->on('locations.id', '=', 'prod_knits.location_id');
        })
        ->leftJoin ('floors',function($join){
            $join->on('floors.id', '=', 'prod_knits.floor_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
        })
        ->leftJoin('gmtssamples',function($join){
            $join->on('gmtssamples.id','=','prod_knit_item_rolls.gmt_sample');
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
        })

        ->leftJoin(\DB::raw("(
            select 
            pl_knit_items.id,
            colorranges.name as colorrange_name,
            colorranges.id as colorrange_id,
            customer.name as customer_name,
            companies.id as company_id,
            CASE 
            WHEN  style_fabrications.autoyarn_id IS NULL THEN so_knit_items.autoyarn_id 
            ELSE style_fabrications.autoyarn_id
            END as autoyarn_id,

            CASE 
            WHEN  style_fabrications.gmtspart_id IS NULL THEN so_knit_items.gmtspart_id 
            ELSE style_fabrications.gmtspart_id
            END as gmtspart_id,

            CASE 
            WHEN  style_fabrications.fabric_look_id IS NULL THEN so_knit_items.fabric_look_id 
            ELSE style_fabrications.fabric_look_id
            END as fabric_look_id,

            CASE 
            WHEN  style_fabrications.fabric_shape_id IS NULL THEN so_knit_items.fabric_shape_id 
            ELSE style_fabrications.fabric_shape_id
            END as fabric_shape_id,
            CASE 
            WHEN sales_orders.sale_order_no IS NULL THEN so_knit_items.gmt_sale_order_no 
            ELSE sales_orders.sale_order_no
            END as sale_order_no,
            CASE 
            WHEN sales_orders.id IS NULL THEN 0
            ELSE sales_orders.id
            END as sale_order_id,
            CASE 
            WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
            ELSE styles.style_ref
            END as style_ref,
            CASE 
            WHEN styles.id IS NULL THEN 0 
            ELSE styles.id
            END as style_id,
            CASE 
            WHEN buyers.name IS NULL THEN outbuyers.name 
            ELSE buyers.name
            END as buyer_name,

            CASE 
            WHEN buyers.id IS NULL THEN outbuyers.id 
            ELSE buyers.id
            END as buyer_id

            from pl_knit_items
            join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
            left join colorranges on colorranges.id=pl_knit_items.colorrange_id
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
            left join companies  on companies.id=customer.company_id
        ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
        ->leftJoin(\DB::raw("(
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        colorranges.id as colorrange_id,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        sales_orders.id as sale_order_id,
        styles.style_ref,
        styles.id as style_id,
        buyers.name as buyer_name,
        buyers.id as buyer_id,
        companies.name as customer_name,
        companies.id as company_id   
        from 
        po_knit_service_item_qties
        join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
        join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
        left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
        join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
        join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
        
        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join companies on companies.id=po_knit_services.company_id
        order by po_knit_service_item_qties.id
        ) outhouseprods"),"outhouseprods.id","=","prod_knit_items.po_knit_service_item_qty_id")
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','outhouseprods.company_id');
            $join->Oron('companies.id','=','inhouseprods.company_id');
        })
        ->leftJoin('styles',function($join){
            $join->on('styles.id','=','outhouseprods.style_id');
            $join->Oron('styles.id','=','inhouseprods.style_id');
        })
        ->leftJoin('sales_orders',function($join){
            $join->on('sales_orders.id','=','outhouseprods.sale_order_id');
            $join->Oron('sales_orders.id','=','inhouseprods.sale_order_id');
        })
        
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','outhouseprods.buyer_id');
            $join->Oron('buyers.id','=','inhouseprods.buyer_id');
        })
        ->where([['inv_isus.isu_basis_id','=',9]])
        //->where([['inv_isus.company_id','=',$invrcv->from_company_id]])
        ->where([['inv_isus.id','=',$invgreyfabrcv->inv_isu_id]])
        ->whereIn('inv_grey_fab_isu_items.id',$inv_grey_fab_isu_item_ids)
        ->when(request('challan_no'), function ($q) {
          return $q->where('inv_isus.issue_no', 'like', '%'.request('challan_no', 0).'%');
        })
        /*->when(request('buyer_id',0), function ($q) {
        return $q->where('buyers.id', '=', request('buyer_id',0));
        })
        ->when(request('style_ref',0), function ($q) {
        return $q->where('styles.style_ref', 'like', '%'.request('style_ref',0).'%');
        })
        ->when(request('sale_order_no',0), function ($q) {
        return $q->where('sales_orders.sale_order_no', 'like', '%'.request('sale_order_no',0).'%');
        })*/
        ->orderBy('inv_grey_fab_isu_items.id','desc')
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            return $prodknitqc;
        });
        return Template::loadView('Inventory.GreyFabric.InvGreyFabTransInItemMatrix',['prodknitqc'=>$prodknitqc,'store'=>$store]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvGreyFabTransInItemRequest $request) {
        if($request->qty==0){
         return response()->json(array('success' =>false ,'id'=>0,'inv_isu_id'=>$request->inv_grey_fab_rcv_id,'message'=>'Zero qty  not allowed'),200);
        }

      $invgreyfabrcv=$this->invrcv->find($request->inv_rcv_id);
      \DB::beginTransaction();
      foreach($request->inv_grey_fab_isu_item_id as $index=>$inv_grey_fab_isu_item_id)
      {
      	if($request->qty[$index])
        {
			try
			{
				$invgreyfabrcvitem = $this->invgreyfabrcvitem->create(
				[
				'inv_grey_fab_rcv_id'=> $request->inv_grey_fab_rcv_id,         
				'prod_knit_dlv_roll_id'=> $request->prod_knit_dlv_roll_id[$index],
				'inv_grey_fab_item_id'=> $request->inv_grey_fab_item_id[$index],          
				'inv_grey_fab_isu_item_id'=> $inv_grey_fab_isu_item_id,          
				'store_id'=> $request->store_id[$index],
				'qty' => $request->qty[$index],
				'rate' => 0,
				'amount'=> 0,
				'store_qty' => $request->qty[$index],
				'store_rate' => 0,
				'store_amount'=> 0,
				'room'=> $request->room[$index],     
				'rack'=> $request->rack[$index],     
				'shelf'=> $request->shelf[$index],
				'remarks' => $request->remarks[$index]     
				]);

				$invgreyfabtransaction=$this->invgreyfabtransaction->create(
				[
				'trans_type_id'=>1,
				'trans_date'=>$invgreyfabrcv->receive_date,
				'inv_grey_fab_rcv_item_id'=>$invgreyfabrcvitem->id,
				'inv_grey_fab_isu_item_id'=> $inv_grey_fab_isu_item_id,
				'inv_grey_fab_item_id'=>$request->inv_grey_fab_item_id[$index],
				'company_id'=>$invgreyfabrcv->company_id,
				'supplier_id'=>$invgreyfabrcv->supplier_id,
				'store_id'=>$request->store_id[$index],
				'store_qty' => $request->qty[$index],
				'store_rate' => 0,
				'store_amount'=> 0
				]);
			}
			catch(EXCEPTION $e)
			{
				\DB::rollback();
				throw $e;
			}
		}
      }
      \DB::commit();
      if($invgreyfabrcvitem){
        return response()->json(array('success' =>true ,'id'=>$invgreyfabrcvitem->id, 'inv_grey_fab_rcv_id'=>$request->inv_grey_fab_rcv_id,'message'=>'Saved Successfully'),200);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $invgreyfabrcvitem=$this->invgreyfabrcvitem->find($id);
        $row ['fromData'] = $invgreyfabrcvitem;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvGreyFabTransInItemRequest $request, $id) {
    if($request->qty==0){
     return response()->json(array('success' =>false ,'id'=>0,'inv_isu_id'=>$request->inv_grey_fab_rcv_id,'message'=>'Zero qty  not allowed'),200);
    }
      $invgreyfabrcv=$this->invrcv->find($request->inv_rcv_id);
      \DB::beginTransaction();
      try
      {
        $invgreyfabrcvitem = $this->invgreyfabrcvitem->update($id,
        [
        //'inv_grey_fab_rcv_id'=> $request->inv_grey_fab_rcv_id,         
        //'prod_knit_dlv_roll_id'=> $request->prod_knit_dlv_roll_id,
        'inv_grey_fab_item_id'=> $request->inv_grey_fab_item_id,          
        //'inv_grey_fab_isu_item_id'=> $request->inv_grey_fab_isu_item_id,          
        'store_id'=> $request->store_id,
        'qty' => $request->qty,
        'rate' => 0,
        'amount'=> 0,
        'store_qty' => $request->qty,
        'store_rate' => 0,
        'store_amount'=> 0,
        'room'=> $request->room,     
        'rack'=> $request->rack,     
        'shelf'=> $request->shelf,
        'remarks' => $request->remarks     
        ]);

        $invgreyfabtransaction=$this->invgreyfabtransaction->create(
        [
        'trans_type_id'=>1,
        'trans_date'=>$invgreyfabrcv->receive_date,
        //'inv_grey_fab_rcv_item_id'=>$invgreyfabrcvitem->id,
        //'inv_grey_fab_isu_item_id'=> $request->inv_grey_fab_isu_item_id,
        'inv_grey_fab_item_id'=>$request->inv_grey_fab_item_id,
        'company_id'=>$invgreyfabrcv->company_id,
        'supplier_id'=>$invgreyfabrcv->supplier_id,
        'store_id'=>$request->store_id,
        'store_qty' => $request->qty,
        'store_rate' => 0,
        'store_amount'=> 0
        ]);
      }
      catch(EXCEPTION $e)
      {
        \DB::rollback();
        throw $e;
      }
      \DB::commit();
      if($invgreyfabrcvitem){
        return response()->json(array('success' =>true ,'id'=>$id, 'inv_grey_fab_rcv_id'=>$request->inv_grey_fab_rcv_id,'message'=>'Saved Successfully'),200);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
      return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
        if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


    public function getItem()
    {
        $invrcv=$this->invrcv->find(request('inv_rcv_id',0));
        $invgreyfabrcv=$this->invgreyfabrcv->where([['inv_rcv_id','=',request('inv_rcv_id',0)]])->get()->first();
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $fabricDescription = collect(\DB::select("
            select
            autoyarns.id,
            constructions.name as construction,
            compositions.name,
            autoyarnratios.ratio
            FROM autoyarns
            join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
            join compositions on compositions.id = autoyarnratios.composition_id
            join constructions on constructions.id = autoyarns.construction_id
            "
        ));

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
            $fabricDescriptionArr[$row->id]=$row->construction;
            $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
        }
        
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $prodknitqc=$this->invisu
        ->selectRaw('
            inv_grey_fab_isu_items.id,
            inv_grey_fab_isu_items.qty as rcv_qty,

            inv_grey_fab_rcv_items.inv_grey_fab_item_id,
            inv_grey_fab_rcv_items.store_id,
            prod_knit_dlvs.store_id,
            prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id, 
            prod_knit_qcs.id as prod_knit_qc_id,   
            prod_knit_qcs.gsm_weight,   
            prod_knit_qcs.dia_width,   
            prod_knit_qcs.measurement,   
            prod_knit_qcs.roll_length,   
            prod_knit_qcs.shrink_per,   
            prod_knit_qcs.reject_qty,   
            prod_knit_qcs.qc_pass_qty,   
            prod_knit_qcs.reject_qty_pcs,   
            prod_knit_qcs.qc_pass_qty_pcs,   
            prod_knit_qcs.qc_result,

            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,
            prod_knit_item_rolls.roll_weight,
            prod_knit_item_rolls.width,
            prod_knit_item_rolls.qty_pcs,
            prod_knit_item_rolls.fabric_color,
            colors.name as fabric_color_name,
            prod_knit_item_rolls.gmt_sample,
            prod_knit_items.prod_knit_id,
            prod_knit_items.stitch_length,

            prod_knits.shift_id,
            prod_knits.prod_no,
            prod_knits.supplier_id,
            prod_knits.location_id,
            prod_knits.floor_id,

            suppliers.name as supplier_name,
            locations.name as location_name,
            floors.name as floor_name,
            asset_quantity_costs.custom_no as machine_no,
            asset_technical_features.dia_width as machine_dia,
            asset_technical_features.gauge as machine_gg,
            gmtssamples.name as gmt_sample,
            CASE 
            WHEN  inhouseprods.autoyarn_id IS NULL THEN outhouseprods.autoyarn_id 
            ELSE inhouseprods.autoyarn_id
            END as autoyarn_id,
            CASE 
            WHEN  inhouseprods.gmtspart_id IS NULL THEN outhouseprods.gmtspart_id 
            ELSE inhouseprods.gmtspart_id
            END as gmtspart_id,
            CASE 
            WHEN  inhouseprods.fabric_look_id IS NULL THEN outhouseprods.fabric_look_id 
            ELSE inhouseprods.fabric_look_id
            END as fabric_look_id,

            CASE 
            WHEN  inhouseprods.fabric_shape_id IS NULL THEN outhouseprods.fabric_shape_id 
            ELSE inhouseprods.fabric_shape_id
            END as fabric_shape_id,

            CASE 
            WHEN  inhouseprods.colorrange_name IS NULL THEN outhouseprods.colorrange_name 
            ELSE inhouseprods.colorrange_name
            END as colorrange_name,

            CASE 
            WHEN  inhouseprods.colorrange_id IS NULL THEN outhouseprods.colorrange_id 
            ELSE inhouseprods.colorrange_id
            END as colorrange_id,

            
            CASE 
            WHEN  inhouseprods.sale_order_no IS NULL THEN outhouseprods.sale_order_no 
            ELSE inhouseprods.sale_order_no
            END as sale_order_no,
            CASE 
            WHEN  inhouseprods.style_ref IS NULL THEN outhouseprods.style_ref 
            ELSE inhouseprods.style_ref
            END as style_ref,

            CASE 
            WHEN  inhouseprods.buyer_name IS NULL THEN outhouseprods.buyer_name 
            ELSE inhouseprods.buyer_name
            END as buyer_name,

            CASE 
            WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
            ELSE inhouseprods.customer_name
            END as customer_name,
            invgreyfabrcvitems.id as inv_grey_fab_rcv_item_id

            
        ')
        ->join('inv_grey_fab_isu_items',function($join){
            $join->on('inv_isus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
        })
        ->join('inv_grey_fab_rcv_items',function($join){
            $join->on('inv_grey_fab_rcv_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id');
        })
        ->join('inv_grey_fab_rcvs',function($join){
            $join->on('inv_grey_fab_rcvs.id', '=', 'inv_grey_fab_rcv_items.inv_grey_fab_rcv_id');
        })
        ->join('inv_rcvs',function($join){
            $join->on('inv_rcvs.id', '=', 'inv_grey_fab_rcvs.inv_rcv_id');
        })
        ->join('prod_knit_dlvs',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'inv_grey_fab_rcvs.prod_knit_dlv_id');
        })
        ->join('prod_knit_dlv_rolls',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'prod_knit_dlv_rolls.prod_knit_dlv_id');
            $join->on('inv_grey_fab_rcv_items.prod_knit_dlv_roll_id', '=', 'prod_knit_dlv_rolls.id');
        })
        ->join('prod_knit_qcs',function($join){
            $join->on('prod_knit_qcs.id', '=', 'prod_knit_dlv_rolls.prod_knit_qc_id');
        })
        ->join('prod_knit_rcv_by_qcs',function($join){
            $join->on('prod_knit_rcv_by_qcs.id', '=', 'prod_knit_qcs.prod_knit_rcv_by_qc_id');
        })
        ->join('prod_knit_item_rolls',function($join){
            $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
        })
        ->join('prod_knit_items',function($join){
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
        })
        ->join ('prod_knits',function($join){
            $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
        })
        ->join ('suppliers',function($join){
            $join->on('suppliers.id', '=', 'prod_knits.supplier_id');
        })
        ->leftJoin ('locations',function($join){
            $join->on('locations.id', '=', 'prod_knits.location_id');
        })
        ->leftJoin ('floors',function($join){
            $join->on('floors.id', '=', 'prod_knits.floor_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
        })
        ->leftJoin('gmtssamples',function($join){
            $join->on('gmtssamples.id','=','prod_knit_item_rolls.gmt_sample');
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
        })

        ->leftJoin(\DB::raw("(
            select 
            pl_knit_items.id,
            colorranges.name as colorrange_name,
            colorranges.id as colorrange_id,
            customer.name as customer_name,
            companies.id as company_id,
            CASE 
            WHEN  style_fabrications.autoyarn_id IS NULL THEN so_knit_items.autoyarn_id 
            ELSE style_fabrications.autoyarn_id
            END as autoyarn_id,

            CASE 
            WHEN  style_fabrications.gmtspart_id IS NULL THEN so_knit_items.gmtspart_id 
            ELSE style_fabrications.gmtspart_id
            END as gmtspart_id,

            CASE 
            WHEN  style_fabrications.fabric_look_id IS NULL THEN so_knit_items.fabric_look_id 
            ELSE style_fabrications.fabric_look_id
            END as fabric_look_id,

            CASE 
            WHEN  style_fabrications.fabric_shape_id IS NULL THEN so_knit_items.fabric_shape_id 
            ELSE style_fabrications.fabric_shape_id
            END as fabric_shape_id,
            CASE 
            WHEN sales_orders.sale_order_no IS NULL THEN so_knit_items.gmt_sale_order_no 
            ELSE sales_orders.sale_order_no
            END as sale_order_no,
            CASE 
            WHEN sales_orders.id IS NULL THEN 0
            ELSE sales_orders.id
            END as sale_order_id,
            CASE 
            WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
            ELSE styles.style_ref
            END as style_ref,
            CASE 
            WHEN styles.id IS NULL THEN 0 
            ELSE styles.id
            END as style_id,
            CASE 
            WHEN buyers.name IS NULL THEN outbuyers.name 
            ELSE buyers.name
            END as buyer_name,

            CASE 
            WHEN buyers.id IS NULL THEN outbuyers.id 
            ELSE buyers.id
            END as buyer_id

            from pl_knit_items
            join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
            left join colorranges on colorranges.id=pl_knit_items.colorrange_id
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
            left join companies  on companies.id=customer.company_id
        ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
        ->leftJoin(\DB::raw("(
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        colorranges.id as colorrange_id,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        sales_orders.id as sale_order_id,
        styles.style_ref,
        styles.id as style_id,
        buyers.name as buyer_name,
        buyers.id as buyer_id,
        companies.name as customer_name,
        companies.id as company_id   
        from 
        po_knit_service_item_qties
        join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
        join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
        left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
        join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
        join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
        
        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join companies on companies.id=po_knit_services.company_id
        order by po_knit_service_item_qties.id
        ) outhouseprods"),"outhouseprods.id","=","prod_knit_items.po_knit_service_item_qty_id")
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','outhouseprods.company_id');
            $join->Oron('companies.id','=','inhouseprods.company_id');
        })
        ->leftJoin('styles',function($join){
            $join->on('styles.id','=','outhouseprods.style_id');
            $join->Oron('styles.id','=','inhouseprods.style_id');
        })
        ->leftJoin('sales_orders',function($join){
            $join->on('sales_orders.id','=','outhouseprods.sale_order_id');
            $join->Oron('sales_orders.id','=','inhouseprods.sale_order_id');
        })
        
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','outhouseprods.buyer_id');
            $join->Oron('buyers.id','=','inhouseprods.buyer_id');
        })

        ->leftJoin('inv_grey_fab_rcv_items as invgreyfabrcvitems',function($join){
            $join->on('invgreyfabrcvitems.inv_grey_fab_isu_item_id','=','inv_grey_fab_isu_items.id');
        })

        ->where([['inv_isus.isu_basis_id','=',9]])
        ->where([['inv_isus.id','=',$invgreyfabrcv->inv_isu_id]])
        ->when(request('challan_no'), function ($q) {
          return $q->where('inv_isus.issue_no', 'like', '%'.request('challan_no', 0).'%');
        })
        ->orderBy('inv_grey_fab_isu_items.id','desc')
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            return $prodknitqc;
        })
        ->filter(function($prodknitqc){
        	if(!$prodknitqc->inv_grey_fab_rcv_item_id){
        		return $prodknitqc;
        	}

        })
        ->values();

        echo json_encode($prodknitqc);
    }
}