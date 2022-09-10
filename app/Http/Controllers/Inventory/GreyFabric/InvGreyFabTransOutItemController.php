<?php

namespace App\Http\Controllers\Inventory\GreyFabric;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabIsuRepository;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabIsuItemRepository;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabTransactionRepository;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabItemRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\GreyFabric\InvGreyFabTransOutItemRequest;

class InvGreyFabTransOutItemController extends Controller {

    private $invisu;
    private $invgreyfabisu;
    private $invgreyfabisuitem;
    private $invrcv;
    private $store;
    private $itemaccount;
    private $invgreyfabtransaction;
    private $invgreyfabitem;
    private $gmtspart;

    public function __construct(
        InvIsuRepository $invisu,
        InvGreyFabIsuRepository $invgreyfabisu, 
        InvGreyFabIsuItemRepository $invgreyfabisuitem, 
        InvRcvRepository $invrcv,
        StoreRepository $store,
        ItemAccountRepository $itemaccount,
        InvGreyFabTransactionRepository $invgreyfabtransaction,
        InvGreyFabItemRepository $invgreyfabitem,
        GmtspartRepository $gmtspart,
        StyleRepository $style,
        SalesOrderRepository $salesorder
    ) {
        $this->invisu = $invisu;
        $this->invgreyfabisu = $invgreyfabisu;
        $this->invgreyfabisuitem = $invgreyfabisuitem;
        $this->invrcv = $invrcv;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->invgreyfabtransaction = $invgreyfabtransaction;
        $this->invgreyfabitem = $invgreyfabitem;
        $this->gmtspart = $gmtspart;
        $this->style = $style;
        $this->salesorder = $salesorder;
        $this->middleware('auth');
        //$this->middleware('permission:view.invyarnisuitem',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invyarnisuitem', ['only' => ['store']]);
        //$this->middleware('permission:edit.invyarnisuitem',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invyarnisuitem', ['only' => ['destroy']]);
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


            inv_grey_fab_rcv_items.inv_grey_fab_item_id,
            inv_grey_fab_rcv_items.store_id,
            prod_knit_dlvs.store_id,
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
        ->join('inv_grey_fab_isu_items',function($join){
            $join->on('inv_isus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
        })
        ->join('inv_grey_fab_items',function($join){
            $join->on('inv_grey_fab_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_item_id');
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
            $join->on('suppliers.id', '=', 'inv_grey_fab_items.supplier_id');
        })
        ->leftJoin ('colorranges',function($join){
            $join->on('colorranges.id', '=', 'inv_grey_fab_items.colorrange_id');
        })
         ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_grey_fab_items.color_id');
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
        ->where([['inv_isus.id','=',request('inv_isu_id')]])
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

        echo json_encode($prodknitqc);
        
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
    
    public function store(InvGreyFabTransOutItemRequest $request) {
        if($request->qty==0){
         return response()->json(array('success' =>false ,'id'=>0,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Zero qty  not allowed'),200);
        }
        if($request->qty>$request->bal_qty){
         return response()->json(array('success' =>false ,'id'=>0,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Issue qty greater than balance qty not allowed'),200);
        }
        $invisu=$this->invisu->find($request->inv_isu_id);
        $invgreyfabitem=$this->invgreyfabitem->find($request->inv_grey_fab_item_id);
        $trans_type_id=2;
        \DB::beginTransaction();
        try
        {
            $invgreyfabisuitem=$this->invgreyfabisuitem->create([
            'inv_isu_id'=>$request->inv_isu_id,
            'store_id'=>$request->store_id,
            'qty'=>$request->qty,
            'rate'=>0,
            'amount'=>0,
            'returnable_qty'=>0,
            'returned_qty'=>0,
            'remarks'=>$request->remarks,
            'inv_grey_fab_item_id'=>$request->inv_grey_fab_item_id,
            'inv_grey_fab_rcv_item_id'=>$request->inv_grey_fab_rcv_item_id,
            ]);

            $invgreyfabtransaction=$this->invgreyfabtransaction->create([
            'trans_type_id'=>$trans_type_id,
            'trans_date'=>$invisu->issue_date,
            'inv_grey_fab_rcv_item_id'=>$request->inv_grey_fab_rcv_item_id,
            'inv_grey_fab_isu_item_id'=>$invgreyfabisuitem->id,
            'inv_grey_fab_item_id'=>$request->inv_grey_fab_item_id,
            'company_id'=>$invisu->company_id,
            'supplier_id'=>$invgreyfabitem->supplier_id,
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
    return response()->json(array('success' =>true ,'id'=>$invgreyfabisuitem->id,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Saved Successfully'),200);
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

        $rows=$this->invgreyfabisuitem
        ->join ('inv_grey_fab_rcv_items',function($join){
            $join->on('inv_grey_fab_rcv_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id');
        })
        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id,
        sum(inv_grey_fab_isu_items.qty) as isu_qty
        from 
        inv_grey_fab_isu_items
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where
        inv_isus.deleted_at is null and 
        inv_grey_fab_isu_items.deleted_at is null  
        group by inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
        ) isus"),"isus.inv_grey_fab_rcv_item_id","=","inv_grey_fab_rcv_items.id")
        ->where([['inv_grey_fab_isu_items.id','=',$id]])
        ->get(['inv_grey_fab_isu_items.*','isus.isu_qty','inv_grey_fab_rcv_items.qty as rcv_qty'])
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
    public function update(InvGreyFabTransOutItemRequest $request, $id) {
        if($request->qty==0){
         return response()->json(array('success' =>false ,'id'=>$id,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Zero qty  not allowed'),200);
        }
        if($request->qty>$request->bal_qty){
         return response()->json(array('success' =>false ,'id'=>$id,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Issue qty greater than balance qty not allowed'),200);
        }
        $invisu=$this->invisu->find($request->inv_isu_id);
        $invgreyfabitem=$this->invgreyfabitem->find($request->inv_grey_fab_item_id);
        $trans_type_id=2;
        \DB::beginTransaction();
        try
        {
            $invgreyfabisuitem=$this->invgreyfabisuitem->update($id,[
            'inv_isu_id'=>$request->inv_isu_id,
            'store_id'=>$request->store_id,
            'qty'=>$request->qty,
            'rate'=>0,
            'amount'=>0,
            'returnable_qty'=>0,
            'returned_qty'=>0,
            'remarks'=>$request->remarks,
            
            ]);

            $invgreyfabtransaction=$this->invgreyfabtransaction
            ->where([['inv_grey_fab_isu_item_id','=',$id]])
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
    return response()->json(array('success' =>true ,'id'=>$id,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Update Successfully'),200);
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

    public function getGreyFabItem()
    {
        $invisu=$this->invisu->find(request('inv_isu_id',0));
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
            inv_grey_fab_rcv_items.store_qty as rcv_qty,
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
            isus.isu_qty

            
        ')
        ->join('inv_grey_fab_rcvs',function($join){
            $join->on('inv_grey_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
        })
        ->join('inv_grey_fab_rcv_items',function($join){
            $join->on('inv_grey_fab_rcv_items.inv_grey_fab_rcv_id', '=', 'inv_grey_fab_rcvs.id');
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

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id,
        sum(inv_grey_fab_isu_items.qty) as isu_qty
        from 
        inv_grey_fab_isu_items
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where
        inv_isus.deleted_at is null and 
        inv_grey_fab_isu_items.deleted_at is null  
        group by inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
        ) isus"),"isus.inv_grey_fab_rcv_item_id","=","inv_grey_fab_rcv_items.id")

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
        ->where([['inv_rcvs.company_id','=',$invisu->company_id]])
        ->when(request('buyer_id',0), function ($q) {
        return $q->where('buyers.id', '=', request('buyer_id',0));
        })
        ->when(request('style_ref',0), function ($q) {
        return $q->where('styles.style_ref', 'like', '%'.request('style_ref',0).'%');
        })
        ->when(request('sale_order_no',0), function ($q) {
        return $q->where('sales_orders.sale_order_no', 'like', '%'.request('sale_order_no',0).'%');
        })
        ->orderBy('inv_grey_fab_rcv_items.id','desc')
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->bal_qty=$prodknitqc->rcv_qty-$prodknitqc->isu_qty;
            return $prodknitqc;
        })
        ->filter(function($prodknitqc){
            if($prodknitqc->bal_qty>=0){
            return $prodknitqc;
            }
        })
        ->values();
        echo json_encode($prodknitqc);
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