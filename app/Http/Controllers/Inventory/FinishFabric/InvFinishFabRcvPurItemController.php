<?php

namespace App\Http\Controllers\Inventory\FinishFabric;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvFabricRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabItemRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvItemRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabTransactionRepository;

use App\Repositories\Contracts\Util\GmtspartRepository;

use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Purchase\PoYarnItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;

use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemAccountRatioRepository;

use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\FinishFabric\InvFinishFabRcvPurItemRequest;

class InvFinishFabRcvPurItemController extends Controller {

    
    private $invrcv;

    private $invfinishfabrcv;
    private $invfinishfabrcvfabric;
    private $invfinishfabitem;
    private $invfinishfabrcvitem;
    private $invfinishfabtransaction;
    private $gmtspart;


    private $poyarnitem;
    private $poyarn;
    private $poyarndyeing;
    private $itemaccount;
    private $itemaccountratio;
    private $store;
    private $color;
    private $autoyarn;

    public function __construct(
        InvRcvRepository $invrcv,
        InvFinishFabRcvRepository $invfinishfabrcv,
        InvFinishFabRcvFabricRepository $invfinishfabrcvfabric, 
        InvFinishFabItemRepository $invfinishfabitem,
        InvFinishFabRcvItemRepository $invfinishfabrcvitem,
        InvFinishFabTransactionRepository $invfinishfabtransaction,
        GmtspartRepository $gmtspart, 

        PoYarnRepository $poyarn,
        PoYarnItemRepository $poyarnitem,
        PoYarnDyeingRepository $poyarndyeing,
        ItemAccountRepository $itemaccount,
        ItemAccountRatioRepository $itemaccountratio,
        StoreRepository $store,
        ColorRepository $color,
        AutoyarnRepository $autoyarn
    ) {
        $this->invrcv = $invrcv;

        $this->invfinishfabrcv = $invfinishfabrcv;
        $this->invfinishfabrcvfabric = $invfinishfabrcvfabric;
        $this->invfinishfabitem = $invfinishfabitem;
        $this->invfinishfabrcvitem = $invfinishfabrcvitem;
        $this->invfinishfabtransaction = $invfinishfabtransaction;
        $this->gmtspart = $gmtspart;

        $this->poyarnitem = $poyarnitem;
        $this->poyarn = $poyarn;
        $this->poyarndyeing = $poyarndyeing;
        $this->itemaccount = $itemaccount;
        $this->itemaccountratio = $itemaccountratio;
        $this->store = $store;
        $this->color = $color;
        $this->autoyarn = $autoyarn;

        $this->middleware('auth');
        $this->middleware('permission:view.invfinishfabrcvitems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invfinishfabrcvitems', ['only' => ['store']]);
        $this->middleware('permission:edit.invfinishfabrcvitems',   ['only' => ['update']]);
        $this->middleware('permission:delete.invfinishfabrcvitems', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
            
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');



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

        $rolls=$this->invfinishfabrcvfabric
        ->join('inv_finish_fab_rcv_items',function($join){
        $join->on('inv_finish_fab_rcv_items.inv_finish_fab_rcv_fabric_id', '=', 'inv_finish_fab_rcv_fabrics.id');
        })
        ->join('inv_finish_fab_rcvs',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.inv_finish_fab_rcv_id', '=', 'inv_finish_fab_rcvs.id');
        })
        ->join('inv_rcvs',function($join){
        $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
        })
        ->join('po_fabric_items',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.po_fabric_item_id', '=', 'po_fabric_items.id');
        })
        ->join('po_fabrics',function($join){
        $join->on('po_fabric_items.po_fabric_id', '=', 'po_fabrics.id');
        })
        ->join('budget_fabrics',function($join){
        $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_gmts',function($join){
        $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('sales_orders',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.sales_order_id','=','sales_orders.id');
        })
        ->join('colors',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.fabric_color_id','=','colors.id');
        })
        ->join('gmtsparts',function($join){
        $join->on('style_fabrications.gmtspart_id','=','gmtsparts.id');
        })
        ->join('jobs',function($join){
        $join->on('sales_orders.job_id','=','jobs.id');
        })
        ->join('styles',function($join){
        $join->on('jobs.style_id','=','styles.id');
        })
        ->join('buyers',function($join){
        $join->on('styles.buyer_id','=','buyers.id');
        })
        ->where([['inv_finish_fab_rcv_fabrics.id','=',request('inv_finish_fab_rcv_fabric_id',0)]])
        ->get([
        'inv_finish_fab_rcv_items.*',
        'inv_finish_fab_rcv_fabrics.gsm_weight',
        'inv_finish_fab_rcv_fabrics.dia',
        'inv_finish_fab_rcv_fabrics.stitch_length',
        'inv_finish_fab_rcv_fabrics.shrink_per',
        'style_fabrications.autoyarn_id',
        'style_fabrications.gmtspart_id',
        'style_fabrications.fabric_look_id',
        'style_fabrications.fabric_shape_id',
        'po_fabric_items.rate',
        'po_fabrics.exch_rate',
        'gmtsparts.name as body_part',

        ])
        ->map(function($rolls) use($desDropdown,$fabriclooks,$fabricshape){
        $rolls->fabrication=$desDropdown[$rolls->autoyarn_id];
        $rolls->fabric_shape=$fabricshape[$rolls->fabric_shape_id];
        $rolls->fabric_look=$fabriclooks[$rolls->fabric_look_id];
        return $rolls;
        });
        return response()->json($rolls);
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
    public function store(InvFinishFabRcvPurItemRequest $request) {
        

        $invrcv=$this->invrcv->find($request->inv_rcv_id);
        $fabrics=$this->invfinishfabrcvfabric
        ->join('inv_finish_fab_rcvs',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.inv_finish_fab_rcv_id', '=', 'inv_finish_fab_rcvs.id');
        })
        ->join('inv_rcvs',function($join){
        $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
        })
        ->join('po_fabric_items',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.po_fabric_item_id', '=', 'po_fabric_items.id');
        })
        ->join('po_fabrics',function($join){
        $join->on('po_fabric_items.po_fabric_id', '=', 'po_fabrics.id');
        })
        ->join('budget_fabrics',function($join){
          $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_gmts',function($join){
          $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
          $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('sales_orders',function($join){
          $join->on('inv_finish_fab_rcv_fabrics.sales_order_id','=','sales_orders.id');
        })
        ->join('colors',function($join){
          $join->on('inv_finish_fab_rcv_fabrics.fabric_color_id','=','colors.id');
        })
        ->join('gmtsparts',function($join){
          $join->on('style_fabrications.gmtspart_id','=','gmtsparts.id');
        })
        ->join('jobs',function($join){
          $join->on('sales_orders.job_id','=','jobs.id');
        })
        ->join('styles',function($join){
          $join->on('jobs.style_id','=','styles.id');
        })
        ->join('buyers',function($join){
          $join->on('styles.buyer_id','=','buyers.id');
        })
        ->where([['inv_finish_fab_rcv_fabrics.id','=',$request->inv_finish_fab_rcv_fabric_id]])
        ->get([
            'inv_finish_fab_rcv_fabrics.*',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'po_fabric_items.rate',
            'po_fabrics.exch_rate',
            
        ])
        ->first();

        $store_qty=$request->roll_weight*1;
        $store_rate=$fabrics->rate*$fabrics->exch_rate;
        $amount=$request->roll_weight*$fabrics->rate;
        $store_amount=$amount*$fabrics->exch_rate;

        \DB::beginTransaction();
        try
        {
            $invfinishfabitem=$this->invfinishfabitem->firstOrCreate(
            [
            'autoyarn_id'=>$fabrics->autoyarn_id,
            'gmtspart_id'=>$fabrics->gmtspart_id,
            'fabric_look_id'=>$fabrics->fabric_look_id,
            'fabric_shape_id'=>$fabrics->fabric_shape_id,
            'gsm_weight'=>$fabrics->gsm_weight,
            'dia'=>$fabrics->dia,
            'measurment'=>'.',
            'roll_length'=>$request->roll_length,
            'stitch_length'=>$fabrics->stitch_length,
            'shrink_per'=>$fabrics->shrink_per,
            'colorrange_id'=>$fabrics->colorrange_id,
            'color_id'=>$fabrics->fabric_color_id,
            'supplier_id'=>$invrcv->supplier_id,
            ],
            [
            'deleted_ip' => ''
            ]);

            $invfinishfabrcvitem = $this->invfinishfabrcvitem->create(
            [
            'inv_finish_fab_rcv_id'=> $request->inv_finish_fab_rcv_id,         
            'inv_finish_fab_rcv_fabric_id'=> $request->inv_finish_fab_rcv_fabric_id,
            'inv_finish_fab_item_id'=> $invfinishfabitem->id,          
            'store_id'=> $request->store_id,
            'qty' => $request->roll_weight,
            'rate' => $fabrics->rate,
            'amount'=> $amount,
            'store_qty' => $store_qty,
            'store_rate' => $store_rate,
            'store_amount'=> $store_amount,
            'room'=> $request->room,     
            'rack'=> $request->rack,     
            'shelf'=> $request->shelf,
            'roll_no'=> $request->roll_no,
            'remarks' => $request->remarks     
            ]);

            $invfinishfabtransaction=$this->invfinishfabtransaction->create(
            [
            'trans_type_id'=>1,
            'trans_date'=>$invrcv->receive_date,
            'inv_finish_fab_rcv_item_id'=>$invfinishfabrcvitem->id,
            'inv_finish_fab_item_id'=>$invfinishfabitem->id,
            'company_id'=>$invrcv->company_id,
            'supplier_id'=>$invrcv->supplier_id,
            'store_id'=>$request->store_id,
            'store_qty' => $store_qty,
            'store_rate' => $store_rate,
            'store_amount'=> $store_amount,
            ]);

        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
            
        
        \DB::commit();

        if($invfinishfabrcvitem){
        return response()->json(array('success' => true,'id' =>  $invfinishfabrcvitem->id,'inv_finish_fab_rcv_fabric_id' => $request->inv_finish_fab_rcv_fabric_id,'message' => 'Save Successfully'),200);
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
        $invfinishfabrcvitem=$this->invfinishfabrcvitem
        ->join('inv_finish_fab_items',function($join){
          $join->on('inv_finish_fab_rcv_items.inv_finish_fab_item_id','=','inv_finish_fab_items.id');
        })
        ->where([['inv_finish_fab_rcv_items.id','=',$id]])
        ->get([
            'inv_finish_fab_rcv_items.*',
            'inv_finish_fab_rcv_items.qty as roll_weight',
            'inv_finish_fab_items.roll_length',
        ])
        ->first();
        $row ['fromData'] = $invfinishfabrcvitem;
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
    public function update(InvFinishFabRcvPurItemRequest $request, $id) {
        $invrcv=$this->invrcv->find($request->inv_rcv_id);
        $fabrics=$this->invfinishfabrcvfabric
        ->join('inv_finish_fab_rcvs',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.inv_finish_fab_rcv_id', '=', 'inv_finish_fab_rcvs.id');
        })
        ->join('inv_rcvs',function($join){
        $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
        })
        ->join('po_fabric_items',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.po_fabric_item_id', '=', 'po_fabric_items.id');
        })
        ->join('po_fabrics',function($join){
        $join->on('po_fabric_items.po_fabric_id', '=', 'po_fabrics.id');
        })
        ->join('budget_fabrics',function($join){
          $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_gmts',function($join){
          $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
          $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('sales_orders',function($join){
          $join->on('inv_finish_fab_rcv_fabrics.sales_order_id','=','sales_orders.id');
        })
        ->join('colors',function($join){
          $join->on('inv_finish_fab_rcv_fabrics.fabric_color_id','=','colors.id');
        })
        ->join('gmtsparts',function($join){
          $join->on('style_fabrications.gmtspart_id','=','gmtsparts.id');
        })
        ->join('jobs',function($join){
          $join->on('sales_orders.job_id','=','jobs.id');
        })
        ->join('styles',function($join){
          $join->on('jobs.style_id','=','styles.id');
        })
        ->join('buyers',function($join){
          $join->on('styles.buyer_id','=','buyers.id');
        })
        ->where([['inv_finish_fab_rcv_fabrics.id','=',$request->inv_finish_fab_rcv_fabric_id]])
        ->get([
            'inv_finish_fab_rcv_fabrics.*',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'po_fabric_items.rate',
            'po_fabrics.exch_rate',
            
        ])
        ->first();
        $store_qty=$request->roll_weight*1;
        $store_rate=$fabrics->rate*$fabrics->exch_rate;
        $amount=$request->roll_weight*$fabrics->rate;
        $store_amount=$amount*$fabrics->exch_rate;
        \DB::beginTransaction();
        try
        {
            $invfinishfabrcvitem = $this->invfinishfabrcvitem->update($id,
            [
            'store_id'=> $request->store_id,
            'qty' => $request->roll_weight,
            'rate' => $fabrics->rate,
            'amount'=> $amount,
            'store_qty' => $store_qty,
            'store_rate' => $store_rate,
            'store_amount'=> $store_amount,
            'room'=> $request->room,     
            'rack'=> $request->rack,     
            'shelf'=> $request->shelf,
            'roll_no'=> $request->roll_no,
            'remarks' => $request->remarks     
            ]);

            $invfinishfabtransaction=$this->invfinishfabtransaction
            ->where([['inv_finish_fab_rcv_item_id','=',$id]])
            ->where([['trans_type_id','=',1]])
            ->update(
            [
            //'trans_type_id'=>1,
            //'trans_date'=>$invyarnrcv->receive_date,
            //'inv_finish_fab_rcv_item_id'=>$invfinishfabrcvitem->id,
            //'inv_finish_fab_item_id'=>$invfinishfabitem->id,
            'company_id'=>$invrcv->company_id,
            'supplier_id'=>$invrcv->supplier_id,
            'store_id'=>$request->store_id,
            'store_qty' => $store_qty,
            'store_rate' => $store_rate,
            'store_amount'=> $store_amount,
            ]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();
                
        if($invfinishfabrcvitem){
            return response()->json(array('success' => true,'id' => $id,'inv_finish_fab_rcv_fabric_id' => $request->inv_finish_fab_rcv_fabric_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        return response()->json(array('success'=>false,'message'=>'Deleted Not Successfully'),200);

        if($this->invyarnrcvitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    
}