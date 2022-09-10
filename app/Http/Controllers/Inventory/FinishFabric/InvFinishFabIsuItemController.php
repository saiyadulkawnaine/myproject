<?php

namespace App\Http\Controllers\Inventory\FinishFabric;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabIsuRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabIsuItemRepository;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabTransactionRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabItemRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\FinishFabric\InvFinishFabIsuItemRequest;

class InvFinishFabIsuItemController extends Controller {

    private $invisu;
    private $invfinishfabisu;
    private $invfinishfabisuitem;
    private $invrcv;
    private $store;
    private $itemaccount;
    private $invfinishfabtransaction;
    private $invfinishfabitem;
    private $gmtspart;
    private $autoyarn;

    public function __construct(
        InvIsuRepository $invisu,
        InvFinishFabIsuRepository $invfinishfabisu, 
        InvFinishFabIsuItemRepository $invfinishfabisuitem, 
        InvRcvRepository $invrcv,
        StoreRepository $store,
        ItemAccountRepository $itemaccount,
        InvFinishFabTransactionRepository $invfinishfabtransaction,
        InvFinishFabItemRepository $invfinishfabitem,
        GmtspartRepository $gmtspart,
        StyleRepository $style,
        SalesOrderRepository $salesorder,
        AutoyarnRepository $autoyarn
    ) {
        $this->invisu = $invisu;
        $this->invfinishfabisu = $invfinishfabisu;
        $this->invfinishfabisuitem = $invfinishfabisuitem;
        $this->invrcv = $invrcv;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->invfinishfabtransaction = $invfinishfabtransaction;
        $this->invfinishfabitem = $invfinishfabitem;
        $this->gmtspart = $gmtspart;
        $this->style = $style;
        $this->salesorder = $salesorder;
        $this->autoyarn = $autoyarn;
        $this->middleware('auth');
        $this->middleware('permission:view.invfinishfabisuitems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invfinishfabisuitems', ['only' => ['store']]);
        $this->middleware('permission:edit.invfinishfabisuitems',   ['only' => ['update']]);
        $this->middleware('permission:delete.invfinishfabisuitems', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $invisu=$this->invisu->find(request('inv_isu_id',0));
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $rollqcresult=array_prepend(config('bprs.rollqcresult'),'-Select-','');
        

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

            // $invrcv=$this->invrcv
            // ->join('inv_finish_fab_rcvs', function($join)  {
            //     $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
            // })
            // ->join('prod_finish_dlvs', function($join)  {
            //     $join->on('inv_finish_fab_rcvs.prod_finish_dlv_id', '=', 'prod_finish_dlvs.id');
            // })
            // ->where([['inv_rcvs.company_id','=',$invisu->company_id]])
            // ->get(['prod_finish_dlvs.menu_id'])
            // ->first();
            
            
            $invrcv=$this->invisu
            ->join('inv_finish_fab_isu_items', function($join)  {
                $join->on('inv_finish_fab_isu_items.inv_isu_id', '=', 'inv_isus.id');
            })
            ->join('inv_finish_fab_rcv_items', function($join)  {
                $join->on('inv_finish_fab_rcv_items.id', '=', 'inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id');
            })
            ->join('inv_finish_fab_rcvs', function($join)  {
                $join->on('inv_finish_fab_rcv_items.inv_finish_fab_rcv_id', '=', 'inv_finish_fab_rcvs.id');
            })
            ->join('inv_rcvs', function($join)  {
                $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
            })
            ->where([['inv_isus.id','=',$invisu->id]])
            ->get(['inv_rcvs.receive_against_id'])
            ->first();
           

            if ($invrcv->receive_against_id==285) {
                $prodknitqc = collect(
                    \DB::select("
                    select 
                    prod_knit_item_rolls.id as prod_knit_item_roll_id,
                    prod_knit_item_rolls.custom_no,
        
                    prod_knit_item_rolls.roll_weight,
                    prod_knit_item_rolls.width,
                    prod_knit_item_rolls.qty_pcs,
                    prod_knit_items.id as prod_knit_item_id,
                    prod_knit_items.prod_knit_id,
                    prod_knit_items.stitch_length,
                    prod_knits.prod_no,
                    prod_knit_qcs.measurement,   
                    prod_knit_qcs.roll_length,   
                    prod_knit_qcs.shrink_per, 
                    inv_finish_fab_items.autoyarn_id,
                    inv_finish_fab_items.gmtspart_id,
                    inv_finish_fab_items.fabric_look_id,
                    inv_finish_fab_items.fabric_shape_id,
                    prod_batch_finish_qc_rolls.gsm_weight,   
                    prod_batch_finish_qc_rolls.dia_width,
                    prod_batch_finish_qc_rolls.grade_id,
        
                    dyeingbatch.style_ref,
                    dyeingbatch.buyer_name,
                    dyeingbatch.sale_order_no,
                    dyeingbatch.batch_color_name,
        
                    dyeingbatch.customer_name,
                    dyeingbatch.dyeing_batch_no,
        
                    inv_finish_fab_rcv_items.id as inv_finish_fab_rcv_item_id, 
                    --inv_finish_fab_rcv_items.store_qty as rcv_qty,
                    inv_finish_fab_rcv_items.inv_finish_fab_item_id,
                    inv_finish_fab_rcv_items.store_id,
                    inv_finish_fab_isu_items.id,
                    inv_finish_fab_isu_items.qty as rcv_qty
        
                    from 
                    inv_isus
                    inner join inv_finish_fab_isu_items on  inv_finish_fab_isu_items.inv_isu_id=inv_isus.id
        
                    inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.id=inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id
        
                    
                    inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.id=inv_finish_fab_rcv_items.inv_finish_fab_rcv_id
                    inner join inv_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
        
                    
                    inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
                    inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
                    inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
                    and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
                    inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
                    inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
                    left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
                    left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
        
                    left join (
                    select 
                    prod_batches.id,
                    prod_batches.batch_no as dyeing_batch_no,
                    prod_batch_rolls.id as prod_batch_roll_id,
                    batch_colors.name as batch_color_name,
                    customers.name as customer_name,
                    po_dyeing_service_item_qties.sales_order_id,
                    budget_fabric_prods.budget_fabric_id,
                    so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
                    styles.style_ref,
                    buyers.name as buyer_name,
                    sales_orders.sale_order_no
        
                    from 
                    prod_batches
                    inner join prod_batch_rolls on prod_batch_rolls.prod_batch_id = prod_batches.id
                    inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
                    inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
                    inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
                    inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
                    inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
                    inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
                    inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
                    inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
                    left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
                    left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
                    inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
                    inner join buyers  customers on customers.id = so_dyeings.buyer_id 
                    inner join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
                    inner join jobs on jobs.id = sales_orders.job_id
                    inner join styles on styles.id = jobs.style_id
                    inner join buyers on buyers.id = styles.buyer_id
                    inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id 
                    inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
                    inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
                    inner join constructions on constructions.id = autoyarns.construction_id
                    where prod_batches.deleted_at is null 
                    ) dyeingbatch on dyeingbatch.prod_batch_roll_id=prod_batch_finish_qc_rolls.prod_batch_roll_id
        
        
                    /*inner join inv_grey_fab_isu_items on (inv_grey_fab_isu_items.id = dyeingbatch.inv_grey_fab_isu_item_id or inv_grey_fab_isu_items.id = aopbatch.inv_grey_fab_isu_item_id)*/

                    inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = dyeingbatch.inv_grey_fab_isu_item_id 
        
                    inner join inv_isus grey_fab_isus on grey_fab_isus.id = inv_grey_fab_isu_items.inv_isu_id
                    inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
                    inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
        
                    inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
                    inner join inv_rcvs grey_fab_rcvs  on grey_fab_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
                    inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
                    inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
                    and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
        
                    inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
                    inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
                    inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
                    inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
        
                    inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
                    left join colors on  colors.id=prod_knit_item_rolls.fabric_color
                    
                    where inv_isus.id = ? and inv_isus.deleted_at is null
                    order by inv_finish_fab_isu_items.id desc
                    ",[$invisu->id])
                )
                ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult){
                    $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                    $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                    $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                    $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                    $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';
                    return $prodknitqc;
                });
                echo json_encode($prodknitqc);
            }
            if ($invrcv->receive_against_id==287) {
                $prodknitqc = collect(
                    \DB::select("
                    select 
                    prod_knit_item_rolls.id as prod_knit_item_roll_id,
                    prod_knit_item_rolls.custom_no,
        
                    prod_knit_item_rolls.roll_weight,
                    prod_knit_item_rolls.width,
                    prod_knit_item_rolls.qty_pcs,
                    prod_knit_items.id as prod_knit_item_id,
                    prod_knit_items.prod_knit_id,
                    prod_knit_items.stitch_length,
                    prod_knits.prod_no,
                    prod_knit_qcs.measurement,   
                    prod_knit_qcs.roll_length,   
                    prod_knit_qcs.shrink_per, 
                    inv_finish_fab_items.autoyarn_id,
                    inv_finish_fab_items.gmtspart_id,
                    inv_finish_fab_items.fabric_look_id,
                    inv_finish_fab_items.fabric_shape_id,
                    prod_batch_finish_qc_rolls.gsm_weight,   
                    prod_batch_finish_qc_rolls.dia_width,
                    prod_batch_finish_qc_rolls.grade_id,
        
                    aopbatch.style_ref,
                    aopbatch.buyer_name,
                    aopbatch.sale_order_no,
                    aopbatch.batch_color_name,
        
                    aopbatch.customer_name,
        
                    aopbatch.dyeing_batch_no,
                    aopbatch.aop_batch_no, 
        
                    inv_finish_fab_rcv_items.id as inv_finish_fab_rcv_item_id, 
                    --inv_finish_fab_rcv_items.store_qty as rcv_qty,
                    inv_finish_fab_rcv_items.inv_finish_fab_item_id,
                    inv_finish_fab_rcv_items.store_id,
                    inv_finish_fab_isu_items.id,
                    inv_finish_fab_isu_items.qty as rcv_qty
        
                    from 
                    inv_isus
                    inner join inv_finish_fab_isu_items on  inv_finish_fab_isu_items.inv_isu_id=inv_isus.id
        
                    inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.id=inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id
        
                    
                    inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.id=inv_finish_fab_rcv_items.inv_finish_fab_rcv_id
                    inner join inv_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
        
                    
                    inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
                    inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
                    inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
                    and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
                    inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
                    inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
                    left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
                    left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
        
                    left join (
                    select 
                    prod_aop_batches.id,
                    prod_aop_batches.batch_no as aop_batch_no,
                    prod_aop_batch_rolls.id as prod_aop_batch_roll_id,
                    batch_colors.name as batch_color_name,
                    customers.name as customer_name,
                    po_aop_service_item_qties.sales_order_id,
                    budget_fabric_prods.budget_fabric_id,
                    so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
                    prod_batches.batch_no as dyeing_batch_no,
                    styles.style_ref,
                    buyers.name as buyer_name,
                    sales_orders.sale_order_no
        
                    from 
                    prod_aop_batches
                    inner join prod_aop_batch_rolls on prod_aop_batch_rolls.prod_aop_batch_id = prod_aop_batches.id
                    inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
                    inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
                    inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
                    inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
                    inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id
                    inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
        
                    inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
                    inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
                    inner join colors batch_colors on batch_colors.id = prod_batches.batch_color_id
                    inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
        
                    inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
                    inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
                    inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
                    inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
                    inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
                    inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
                    inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
                    inner join buyers  customers on customers.id = so_aops.buyer_id 
                    inner join sales_orders on sales_orders.id=po_aop_service_item_qties.sales_order_id
                    inner join jobs on jobs.id = sales_orders.job_id
                    inner join styles on styles.id = jobs.style_id
                    inner join buyers on buyers.id = styles.buyer_id
                    inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id 
                    inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
                    inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
                    inner join constructions on constructions.id = autoyarns.construction_id
                    where prod_aop_batches.deleted_at is null 
        
                    ) aopbatch on aopbatch.prod_aop_batch_roll_id=prod_batch_finish_qc_rolls.prod_aop_batch_roll_id
                    
                    inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = aopbatch.inv_grey_fab_isu_item_id 
                    inner join inv_isus grey_fab_isus on grey_fab_isus.id = inv_grey_fab_isu_items.inv_isu_id
                    inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
                    inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
        
                    inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
                    inner join inv_rcvs grey_fab_rcvs  on grey_fab_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
                    inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
                    inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
                    and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
        
                    inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
                    inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
                    inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
                    inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
        
                    inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
                    left join colors on  colors.id=prod_knit_item_rolls.fabric_color
                    
                    where inv_isus.id = ? and inv_isus.deleted_at is null
                    order by inv_finish_fab_isu_items.id desc
                    ",[$invisu->id])
                    )
                ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult){
                    $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                    $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                    $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                    $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                    $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';
                    return $prodknitqc;
                });
                echo json_encode($prodknitqc);
            }

            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(InvFinishFabIsuItemRequest $request) {
        if($request->qty==0){
         return response()->json(array('success' =>false ,'id'=>0,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Zero qty  not allowed'),200);
        }
        if($request->qty>$request->bal_qty){
         return response()->json(array('success' =>false ,'id'=>0,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Issue qty greater than balance qty not allowed'),200);
        }
        $invisu=$this->invisu->find($request->inv_isu_id);
        $invfinishfabitem=$this->invfinishfabitem->find($request->inv_finish_fab_item_id);
        $trans_type_id=2;
        \DB::beginTransaction();
        try
        {
            $invfinishfabisuitem=$this->invfinishfabisuitem->create([
            'inv_isu_id'=>$request->inv_isu_id,
            'store_id'=>$request->store_id,
            'qty'=>$request->qty,
            'rate'=>0,
            'amount'=>0,
            'returnable_qty'=>0,
            'returned_qty'=>0,
            'remarks'=>$request->remarks,
            'inv_finish_fab_item_id'=>$request->inv_finish_fab_item_id,
            'inv_finish_fab_rcv_item_id'=>$request->inv_finish_fab_rcv_item_id,
            ]);

            $invfinishfabtransaction=$this->invfinishfabtransaction->create([
            'trans_type_id'=>$trans_type_id,
            'trans_date'=>$invisu->issue_date,
            'inv_finish_fab_rcv_item_id'=>$request->inv_finish_fab_rcv_item_id,
            'inv_finish_fab_isu_item_id'=>$invfinishfabisuitem->id,
            'inv_finish_fab_item_id'=>$request->inv_finish_fab_item_id,
            'company_id'=>$invisu->company_id,
            'supplier_id'=>$invfinishfabitem->supplier_id,
            'store_id'=>$request->store_id,
            'store_qty' => $request->qty*-1,
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
    $totalQty=$this->invfinishfabisuitem->where([['inv_isu_id','=',$request->inv_isu_id]])->sum('qty');
    return response()->json(array('success' =>true ,'id'=>$invfinishfabisuitem->id,'inv_isu_id'=>$request->inv_isu_id,'total'=>$totalQty,'message'=>'Saved Successfully'),200);
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

        $rows=$this->invfinishfabisuitem
        ->join ('inv_finish_fab_rcv_items',function($join){
            $join->on('inv_finish_fab_rcv_items.id', '=', 'inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id');
        })
        ->leftJoin(\DB::raw("(
        select 
        inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id,
        sum(inv_finish_fab_isu_items.qty) as isu_qty
        from 
        inv_finish_fab_isu_items
        join inv_isus on inv_isus.id=inv_finish_fab_isu_items.inv_isu_id
        where
        inv_isus.deleted_at is null and 
        inv_finish_fab_isu_items.deleted_at is null  
        group by inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id
        ) isus"),"isus.inv_finish_fab_rcv_item_id","=","inv_finish_fab_rcv_items.id")
        ->where([['inv_finish_fab_isu_items.id','=',$id]])
        ->get(['inv_finish_fab_isu_items.*','isus.isu_qty','inv_finish_fab_rcv_items.qty as rcv_qty'])
        ->first();
        $rows->isu_qty=$rows->isu_qty-$rows->qty;
        $rows->bal_qty=$rows->rcv_qty-($rows->isu_qty);
        
       $row ['fromData'] = $rows;
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
    public function update(InvFinishFabIsuItemRequest $request, $id) {
        if($request->qty==0){
         return response()->json(array('success' =>false ,'id'=>$id,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Zero qty  not allowed'),200);
        }

        if($request->qty>$request->bal_qty){
         return response()->json(array('success' =>false ,'id'=>$id,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Issue qty greater than balance qty not allowed'),200);
        }

        

        $invisu=$this->invisu->find($request->inv_isu_id);
        $invfinishfabitem=$this->invfinishfabitem->find($request->inv_finish_fab_item_id);
        $trans_type_id=2;
        \DB::beginTransaction();
        try
        {
            $invfinishfabisuitem=$this->invfinishfabisuitem->update($id,[
            'inv_isu_id'=>$request->inv_isu_id,
            'store_id'=>$request->store_id,
            'qty'=>$request->qty,
            'rate'=>0,
            'amount'=>0,
            'returnable_qty'=>0,
            'returned_qty'=>0,
            'remarks'=>$request->remarks,
            
            ]);

            $invfinishfabtransaction=$this->invfinishfabtransaction
            ->where([['inv_finish_fab_isu_item_id','=',$id]])
            ->where([['trans_type_id','=',2]])
            ->update([
            'trans_date'=>$invisu->issue_date,
            'store_id'=>$request->store_id,
            'store_qty' => $request->qty*-1,
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
    $totalQty=$this->invfinishfabisuitem->where([['inv_isu_id','=',$request->inv_isu_id]])->sum('qty');

    return response()->json(array('success' =>true ,'id'=>$id,'inv_isu_id'=>$request->inv_isu_id,'total'=>$totalQty,'message'=>'Update Successfully'),200);
        /*if($invyarnisuitem){
            return response()->json(array('success'=> true, 'id' =>$id,'inv_isu_id'=>$request->inv_isu_id, 'message'=>'Updated Successfully'),200);
        }*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
        return response()->json(array('success'=>false,'message'=>'Deleted Not Possible'),200);
        if($this->invisu->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }

    public function getFinishFabItem()
    {
        $invisu=$this->invisu->find(request('inv_isu_id',0));
        $receive_against_id=request('receive_against_id',0);

        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $rollqcresult=array_prepend(config('bprs.rollqcresult'),'-Select-','');


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
        
        $buyer_id=request('buyer_id',0);
        $style_ref=request('style_ref',0);
        $sale_order_no=request('sale_order_no',0);
        $buyers='';
        $styles='';
        $sale_orders='';
        if($buyer_id){
            $buyers=' and styles.buyer_id ='.$buyer_id.''; 
        }
        if($style_ref){
            $styles=" and styles.style_ref like '%".$style_ref."%' "; 
        }
        if($sale_order_no){
            $sale_orders=" and sales_orders.sale_order_no like '%".$sale_order_no."%' "; 
        }

        // $invrcv=$this->invrcv
        // ->join('inv_finish_fab_rcvs', function($join)  {
        //     $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
        // })
        // ->join('prod_finish_dlvs', function($join)  {
        //     $join->on('inv_finish_fab_rcvs.prod_finish_dlv_id', '=', 'prod_finish_dlvs.id');
        // })
        // ->where([['inv_rcvs.company_id','=',$invisu->company_id]])
        // ->get(['inv_rcvs.receive_against_id'])
        // ->first();

        //dd($invrcv);die;
        //dd($invfinishfabrcv);die;

        if ($receive_against_id==287) {
            $prodknitqc = collect(
                \DB::select("
                select 
                prod_knit_item_rolls.id as prod_knit_item_roll_id,
                prod_knit_item_rolls.custom_no,
    
                prod_knit_item_rolls.roll_weight,
                prod_knit_item_rolls.width,
                prod_knit_item_rolls.qty_pcs,
                prod_knit_items.id as prod_knit_item_id,
                prod_knit_items.prod_knit_id,
                prod_knit_items.stitch_length,
                prod_knits.prod_no,
                prod_knit_qcs.measurement,   
                prod_knit_qcs.roll_length,   
                prod_knit_qcs.shrink_per, 
    
    
                inv_finish_fab_items.autoyarn_id,
                inv_finish_fab_items.gmtspart_id,
                inv_finish_fab_items.fabric_look_id,
                inv_finish_fab_items.fabric_shape_id,
                prod_batch_finish_qc_rolls.gsm_weight,   
                prod_batch_finish_qc_rolls.dia_width,
                prod_batch_finish_qc_rolls.grade_id,
    
                
    
                aopbatch.style_ref,
                aopbatch.buyer_name,
                aopbatch.sale_order_no,
                aopbatch.batch_color_name,
                aopbatch.customer_name,
                aopbatch.dyeing_batch_no,
                aopbatch.aop_batch_no, 
    
                inv_finish_fab_rcv_items.id, 
                inv_finish_fab_rcv_items.store_qty as rcv_qty,
                inv_finish_fab_rcv_items.inv_finish_fab_item_id,
                inv_finish_fab_rcv_items.store_id,
                isus.isu_qty
    
                from 
                inv_rcvs
                inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
                inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
                inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
                inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
                inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
                and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
                inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
                inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
                left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
                left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
    
                left join (
                    select 
                    prod_aop_batches.id,
                    prod_aop_batches.batch_no as aop_batch_no,
                    prod_aop_batch_rolls.id as prod_aop_batch_roll_id,
                    batch_colors.name as batch_color_name,
                    customers.name as customer_name,
                    po_aop_service_item_qties.sales_order_id,
                    budget_fabric_prods.budget_fabric_id,
                    so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
                    prod_batches.batch_no as dyeing_batch_no,
                    styles.style_ref,
                    buyers.name as buyer_name,
                    sales_orders.sale_order_no
    
                    from 
                    prod_aop_batches
                    inner join prod_aop_batch_rolls on prod_aop_batch_rolls.prod_aop_batch_id = prod_aop_batches.id
                    inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
                    inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
                    inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
                    inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
                    inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id
                    inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
    
                    inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
                    inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
                    inner join colors batch_colors on batch_colors.id = prod_batches.batch_color_id
                    inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
    
                    inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
                    inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
                    inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
                    inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
                    inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
                    inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
                    inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
                    inner join buyers  customers on customers.id = so_aops.buyer_id 
                    inner join sales_orders on sales_orders.id=po_aop_service_item_qties.sales_order_id
                    inner join jobs on jobs.id = sales_orders.job_id
                    inner join styles on styles.id = jobs.style_id
                    inner join buyers on buyers.id = styles.buyer_id
                    inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id 
                    inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
                    inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
                    inner join constructions on constructions.id = autoyarns.construction_id
                    where prod_aop_batches.deleted_at is null $buyers $styles $sale_orders
                ) aopbatch on aopbatch.prod_aop_batch_roll_id=prod_batch_finish_qc_rolls.prod_aop_batch_roll_id
    
                inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = aopbatch.inv_grey_fab_isu_item_id 
                inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
                inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
                inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
    
                inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
                inner join inv_rcvs grey_fab_rcvs  on grey_fab_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
                inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
                inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
                and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
    
                inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
                inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
                inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
                inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
    
                inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
                left join colors on  colors.id=prod_knit_item_rolls.fabric_color
                left join (
                select 
                inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id,
                sum(inv_finish_fab_isu_items.qty) as isu_qty
                from 
                inv_finish_fab_isu_items
                join inv_isus on inv_isus.id=inv_finish_fab_isu_items.inv_isu_id
                where
                inv_isus.deleted_at is null and 
                inv_finish_fab_isu_items.deleted_at is null  
                group by inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id
                ) isus on isus.inv_finish_fab_rcv_item_id=inv_finish_fab_rcv_items.id
                where inv_rcvs.company_id = ? and inv_rcvs.deleted_at is null 
                order by inv_finish_fab_rcv_items.id
                ",[$invisu->company_id])
                )
                ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                //$prodknitqc->dyetype=$prodknitqc->dyeing_type_id?$dyetype[$prodknitqc->dyeing_type_id]:'';
                $prodknitqc->yarndtl='';//$prodknitqc->prod_knit_item_id?implode(',',$yarnDtls[$prodknitqc->prod_knit_item_id]):'';
                $prodknitqc->bal_qty=$prodknitqc->rcv_qty-$prodknitqc->isu_qty;
                $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';
                return $prodknitqc;
                })
                ->filter(function($prodknitqc){
                if($prodknitqc->bal_qty>0){
                    return $prodknitqc;
                }
            })
            ->values();
    
            echo json_encode($prodknitqc);
        }
        if ($receive_against_id==285) {
            $prodknitqc = collect(
                \DB::select("
                select 
                prod_knit_item_rolls.id as prod_knit_item_roll_id,
                prod_knit_item_rolls.custom_no,
    
                prod_knit_item_rolls.roll_weight,
                prod_knit_item_rolls.width,
                prod_knit_item_rolls.qty_pcs,
                prod_knit_items.id as prod_knit_item_id,
                prod_knit_items.prod_knit_id,
                prod_knit_items.stitch_length,
                prod_knits.prod_no,
                prod_knit_qcs.measurement,   
                prod_knit_qcs.roll_length,   
                prod_knit_qcs.shrink_per, 
    
    
                inv_finish_fab_items.autoyarn_id,
                inv_finish_fab_items.gmtspart_id,
                inv_finish_fab_items.fabric_look_id,
                inv_finish_fab_items.fabric_shape_id,
                prod_batch_finish_qc_rolls.gsm_weight,   
                prod_batch_finish_qc_rolls.dia_width,
                prod_batch_finish_qc_rolls.grade_id,
    
                
    
                dyeingbatch.style_ref,
                dyeingbatch.buyer_name,
                dyeingbatch.sale_order_no,
                dyeingbatch.batch_color_name,
                dyeingbatch.customer_name,
                dyeingbatch.dyeing_batch_no,
    
                inv_finish_fab_rcv_items.id, 
                inv_finish_fab_rcv_items.store_qty as rcv_qty,
                inv_finish_fab_rcv_items.inv_finish_fab_item_id,
                inv_finish_fab_rcv_items.store_id,
                isus.isu_qty
    
                from 
                inv_rcvs
                inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
                inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
                inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
                inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
                inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
                and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
                inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
                inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
                left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
                left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
    
                left join (
                    select 
                    prod_batches.id,
                    prod_batches.batch_no as dyeing_batch_no,
                    prod_batch_rolls.id as prod_batch_roll_id,
                    batch_colors.name as batch_color_name,
                    customers.name as customer_name,
                    po_dyeing_service_item_qties.sales_order_id,
                    budget_fabric_prods.budget_fabric_id,
                    so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
                    styles.style_ref,
                    buyers.name as buyer_name,
                    sales_orders.sale_order_no
    
                    from 
                    prod_batches
                    inner join prod_batch_rolls on prod_batch_rolls.prod_batch_id = prod_batches.id
                    inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
                    inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
                    inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
                    inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
                    inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
                    inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
                    inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
                    inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
                    left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
                    left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
                    inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
                    inner join buyers  customers on customers.id = so_dyeings.buyer_id 
    
                    inner join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
                    inner join jobs on jobs.id = sales_orders.job_id
                    inner join styles on styles.id = jobs.style_id
                    inner join buyers on buyers.id = styles.buyer_id
                    inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id 
                    inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
                    inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
                    inner join constructions on constructions.id = autoyarns.construction_id
                    where prod_batches.deleted_at is null $buyers $styles $sale_orders
                ) dyeingbatch on dyeingbatch.prod_batch_roll_id=prod_batch_finish_qc_rolls.prod_batch_roll_id
    
                /*inner join inv_grey_fab_isu_items on (inv_grey_fab_isu_items.id = dyeingbatch.inv_grey_fab_isu_item_id or inv_grey_fab_isu_items.id = aopbatch.inv_grey_fab_isu_item_id)*/
    
                inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = dyeingbatch.inv_grey_fab_isu_item_id 
    
                inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
                inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
                inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
    
                inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
                inner join inv_rcvs grey_fab_rcvs  on grey_fab_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
                inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
                inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
                and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
    
                inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
                inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
                inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
                inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
    
                inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
                left join colors on  colors.id=prod_knit_item_rolls.fabric_color
                left join (
                select 
                inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id,
                sum(inv_finish_fab_isu_items.qty) as isu_qty
                from 
                inv_finish_fab_isu_items
                join inv_isus on inv_isus.id=inv_finish_fab_isu_items.inv_isu_id
                where
                inv_isus.deleted_at is null and 
                inv_finish_fab_isu_items.deleted_at is null  
                group by inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id
                ) isus on isus.inv_finish_fab_rcv_item_id=inv_finish_fab_rcv_items.id
                where inv_rcvs.company_id = ? and inv_rcvs.deleted_at is null 
                order by inv_finish_fab_rcv_items.id
                ",[$invisu->company_id])
                )
                ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                //$prodknitqc->dyetype=$prodknitqc->dyeing_type_id?$dyetype[$prodknitqc->dyeing_type_id]:'';
                $prodknitqc->yarndtl='';//$prodknitqc->prod_knit_item_id?implode(',',$yarnDtls[$prodknitqc->prod_knit_item_id]):'';
                $prodknitqc->bal_qty=$prodknitqc->rcv_qty-$prodknitqc->isu_qty;
                $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';
                return $prodknitqc;
                })
                ->filter(function($prodknitqc){
                if($prodknitqc->bal_qty>0){
                    return $prodknitqc;
                }
            })
            ->values();
    
            echo json_encode($prodknitqc);
        }
        
    }

	public function getOrder(){
		$order=$this->salesorder
		->selectRaw('
		sales_orders.id as sales_order_id,
		sales_orders.sale_order_no,
		sales_orders.ship_date,
		sales_orders.produced_company_id,
		styles.style_ref,
		styles.id as style_id,
		jobs.job_no,
		buyers.code as buyer_name,
		companies.name as company_id,
		produced_company.name as produced_company_name,
		sales_orders.qty as order_qty
		')
		->join('jobs', function($join)  {
		$join->on('jobs.id', '=', 'sales_orders.job_id');
		})
		->join('companies', function($join)  {
		$join->on('companies.id', '=', 'jobs.company_id');
		})
		->leftJoin('companies as produced_company', function($join)  {
		$join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
		})
		->join('styles', function($join)  {
		$join->on('styles.id', '=', 'jobs.style_id');
		})
		->join('buyers', function($join)  {
		$join->on('buyers.id', '=', 'styles.buyer_id');
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'LIKE', "%".request('job_no', 0)."%");
		})
		->when(request('sale_order_no'), function ($q) {
		return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
		})
		->groupBy([
		'sales_orders.id',
		'sales_orders.sale_order_no',
		'sales_orders.ship_date',
		'sales_orders.produced_company_id',
		'styles.style_ref',
		'styles.id',
		'jobs.job_no',
		'buyers.code',
		'companies.name',
		'produced_company.name',
		'sales_orders.qty'
		])
		->get()
		->map(function ($order){
		return $order;
		});
		echo json_encode($order); 
	}

    public function getStyle(){
		return response()->json($this->style->getAll()->map(function($rows){
			$rows->receivedate=date("d-M-Y",strtotime($rows->receive_date));
			$rows->buyer=$rows->buyer_name;
			$rows->deptcategory=$rows->dept_category_name;
			$rows->season=$rows->season_name;
			$rows->uom=$rows->uom_name;
			$rows->team=$rows->team_name;
			$rows->teammember=$rows->team_member_name;
			$rows->productdepartment=$rows->department_name;
			return $rows;
		}));
	}
}