<?php

namespace App\Http\Controllers\Inventory\Trim;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\Trim\InvTrimRcvRepository;
use App\Repositories\Contracts\Inventory\Trim\InvTrimItemRepository;
use App\Repositories\Contracts\Inventory\Trim\InvTrimRcvItemRepository;
use App\Repositories\Contracts\Inventory\Trim\InvTrimTransactionRepository;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Purchase\PoTrimItemRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqItemRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Trim\InvTrimRcvItemRequest;

class InvTrimRcvItemController extends Controller {

    private $invrcv;
    private $invtrimrcv;
    private $invtrimitem;
    private $invtrimrcvitem;
    private $invtrimtransaction;
    private $potrim;
    private $potrimitem;
    private $invpurreq;
    private $invpurreqitem;
    private $itemaccount;
    private $store;

    public function __construct(
        InvRcvRepository $invrcv,
        InvTrimRcvRepository $invtrimrcv, 
        InvTrimItemRepository $invtrimitem, 
        InvTrimRcvItemRepository $invtrimrcvitem,
        InvTrimTransactionRepository $invtrimtransaction, 
        PoTrimRepository $potrim,
        PoTrimItemRepository $potrimitem,
        InvPurReqRepository $invpurreq,
        InvPurReqItemRepository $invpurreqitem,
        ItemAccountRepository $itemaccount,
        StoreRepository $store
    ) {
        $this->invrcv = $invrcv;
        $this->invtrimrcv = $invtrimrcv;
        $this->invtrimitem = $invtrimitem;
        $this->invtrimrcvitem = $invtrimrcvitem;
        $this->invtrimtransaction = $invtrimtransaction;
        $this->potrim = $potrim;
        $this->potrimitem = $potrimitem;
        $this->invpurreq = $invpurreq;
        $this->invpurreqitem = $invpurreqitem;
        $this->itemaccount = $itemaccount;
        $this->store = $store;
        $this->middleware('auth');
        $this->middleware('permission:view.invtrimrcvitem',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invtrimrcvitem', ['only' => ['store']]);
        $this->middleware('permission:edit.invtrimrcvitem',   ['only' => ['update']]);
        $this->middleware('permission:delete.invtrimrcvitem', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $inv_trim_rcv_id=request('inv_trim_rcv_id',0);
        $invtrimrcv=$this->invtrimrcv->find($inv_trim_rcv_id);
        $invcv=$this->invrcv->find($invtrimrcv->inv_rcv_id);
        $rows = $this->invrcv
        ->join('inv_trim_rcvs',function($join){
        $join->on('inv_trim_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_trim_rcv_items',function($join){
        $join->on('inv_trim_rcv_items.inv_trim_rcv_id','=','inv_trim_rcvs.id');
        })
        ->join('po_trim_item_reports', function($join){
        $join->on('po_trim_item_reports.id', '=', 'inv_trim_rcv_items.po_trim_item_report_id');
        })
        ->join('po_trim_items', function($join){
        $join->on('po_trim_items.id', '=', 'po_trim_item_reports.po_trim_item_id');
        })
        ->join('po_trims', function($join){
        $join->on('po_trim_items.po_trim_id', '=', 'po_trims.id');
        })
       
        ->join('currencies', function($join){
        $join->on('currencies.id', '=', 'po_trims.currency_id');
        })
        ->join('budget_trims', function($join){
        $join->on('budget_trims.id', '=', 'po_trim_items.budget_trim_id');
        })
        ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
        })
        ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->join('sales_orders', function($join){
        $join->on('sales_orders.id', '=', 'po_trim_item_reports.sales_order_id');
        })
        ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'itemclasses.costing_uom_id');
        })
        ->leftJoin('style_colors', function($join){
        $join->on('style_colors.id', '=', 'po_trim_item_reports.style_color_id');
        })
        ->leftJoin('style_sizes', function($join){
        $join->on('style_sizes.id', '=', 'po_trim_item_reports.style_size_id');
        })
        ->leftJoin('colors', function($join){
        $join->on('colors.id', '=', 'style_colors.color_id');
        })
        ->leftJoin('sizes', function($join){
        $join->on('sizes.id', '=', 'style_sizes.size_id');
        })
        ->leftJoin('colors as itemcolors', function($join){
        $join->on('itemcolors.id', '=', 'po_trim_item_reports.trim_color');
        })
        ->where([['inv_trim_rcvs.id','=',$inv_trim_rcv_id]])
        ->orderBy('inv_trim_rcvs.id','desc')
        ->get([
        'inv_trim_rcv_items.*',
        'inv_trim_rcvs.id as inv_trim_rcv_id',
        'po_trims.po_no',
        'po_trims.pi_no',
        'po_trims.exch_rate',
        'po_trim_items.id as po_trim_item_id',
        'po_trim_item_reports.id as po_trim_item_report_id',
        'po_trim_item_reports.description',
        'po_trim_item_reports.measurment',
        'po_trim_item_reports.trim_color as trim_color_id',
        'itemcolors.name as item_color_name',
        'colors.name as style_color_name',
        'sizes.name as style_size_name',
        'po_trim_item_reports.qty as po_qty',
        'po_trim_item_reports.rate as po_rate',
        'po_trim_item_reports.amount as po_amount',
        'itemcategories.name as category_name',
        'itemclasses.id as itemclass_id',
        'itemclasses.name as class_name',
        'uoms.code as uom_name',
        'currencies.code as currency_code',
        'sales_orders.sale_order_no'
        ])
        ->map(function($rows){
		if($rows->currency_code=='USD'){
		$rows->exch_rate=84;
		}
		else{
		$rows->exch_rate=1;
		}
        return $rows;
        });
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
        $po_trim_item_report_id=request('po_trim_item_report_id');
        $potrimitemreportid=explode(',',$po_trim_item_report_id);

        $invrcv=$this->invrcv->find(request('inv_rcv_id',0));
        $rows=$this->potrim
        ->join('po_trim_items', function($join){
        $join->on('po_trim_items.po_trim_id', '=', 'po_trims.id');
        })
        ->join('po_trim_item_reports', function($join){
        $join->on('po_trim_item_reports.po_trim_item_id', '=', 'po_trim_items.id');
        })
        
        ->join('currencies', function($join){
        $join->on('currencies.id', '=', 'po_trims.currency_id');
        })
        ->join('budget_trims', function($join){
        $join->on('budget_trims.id', '=', 'po_trim_items.budget_trim_id');
        })
        ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
        })
        ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->join('sales_orders', function($join){
        $join->on('sales_orders.id', '=', 'po_trim_item_reports.sales_order_id');
        })
        ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'itemclasses.costing_uom_id');
        })
        ->leftJoin('style_colors', function($join){
        $join->on('style_colors.id', '=', 'po_trim_item_reports.style_color_id');
        })
        ->leftJoin('style_sizes', function($join){
        $join->on('style_sizes.id', '=', 'po_trim_item_reports.style_size_id');
        })
        ->leftJoin('colors', function($join){
        $join->on('colors.id', '=', 'style_colors.color_id');
        })
        ->leftJoin('sizes', function($join){
        $join->on('sizes.id', '=', 'style_sizes.size_id');
        })
        ->leftJoin('colors as itemcolors', function($join){
        $join->on('itemcolors.id', '=', 'po_trim_item_reports.trim_color');
        })
        ->leftJoin(\DB::raw("(
            select 
            inv_trim_rcv_items.po_trim_item_report_id,
            sum(qty) as qty
            from
            inv_trim_rcv_items
            where inv_trim_rcv_items.deleted_at is null
            group by 
            inv_trim_rcv_items.po_trim_item_report_id) cumulatives"), "cumulatives.po_trim_item_report_id", "=", "po_trim_item_reports.id")
        ->when(request('po_trim_id'), function ($q) {
        return $q->where('po_trims.id', '=',request('po_trim_id', 0));
        })
        ->when(request('po_no'), function ($q) {
        return $q->where('po_trims.po_no', '=',request('po_no', 0));
        })
        ->when(request('pi_no'), function ($q) {
        return $q->where('po_trims.pi_no', '=',request('pi_no', 0));
        })
        

        ->where([['po_trims.company_id','=',$invrcv->company_id]])
        ->where([['po_trims.supplier_id','=',$invrcv->supplier_id]])
        ->whereIn('po_trim_item_reports.id',$potrimitemreportid)
        ->whereIn('itemcategories.identity',[6])
        ->selectRaw(
        '
        po_trims.po_no,
        po_trims.pi_no,
        po_trims.exch_rate,
        po_trim_items.id,
        po_trim_item_reports.id as po_trim_item_report_id,
        po_trim_item_reports.description,
        po_trim_item_reports.measurment,
        po_trim_item_reports.trim_color as trim_color_id,
        itemcolors.name as item_color_name,
        colors.name as style_color_name,
        sizes.name as style_size_name,
        po_trim_item_reports.qty as po_qty,
        po_trim_item_reports.rate as po_rate,
        po_trim_item_reports.amount as po_amount,
        itemcategories.name as category_name,
        itemclasses.id as itemclass_id,
        itemclasses.name as class_name,
        uoms.code as uom_name,
        currencies.code as currency_code,
        sales_orders.sale_order_no,
        cumulatives.qty as cu_qty
        ')
        ->get()
        ->map(function ($rows) {
            if($rows->currency_code=='USD'){
            $rows->exch_rate=84;  
            }
            else{
            $rows->exch_rate=1;  
            }
            $rows->bal_qty=$rows->po_qty-$rows->cu_qty;
            $rows->bal_amount=$rows->bal_qty*$rows->po_rate;
            if($rows->bal_qty<0){
               $rows->bal_qty=0; 
            }
            if($rows->bal_amount<0){
               $rows->bal_amount=0; 
            }
            return $rows;
        });
         return Template::loadView('Inventory.Trim.InvTrimRcvItemMatrix',['rows'=>$rows,'store'=>$store]);
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvTrimRcvItemRequest $request) {

      $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);

      
      \DB::beginTransaction();
      try
      {
        foreach($request->po_trim_item_report_id as $index=>$po_trim_item_report_id){

            $store_qty=$request->qty[$index]*1;
            $store_rate=$request->rate[$index]*$request->exch_rate[$index];
            $store_amount=$request->amount[$index]*$request->exch_rate[$index];

            $invtrimitem=$this->invtrimitem->firstOrCreate(
            [
            'itemclass_id'=>$request->itemclass_id[$index],
            'color_id'=>$request->trim_color_id[$index],
            'measurment'=>$request->measurment[$index],
            'description'=>$request->description[$index],
            ],
            [
            'deleted_ip' => ''
            ]);

            $invtrimrcvitem = $this->invtrimrcvitem->create(
            [
            'inv_trim_rcv_id'=> $request->inv_trim_rcv_id,         
            'inv_trim_item_id'=> $invtrimitem->id,         
            'po_trim_item_report_id'=> $po_trim_item_report_id,
            'store_id'=> $request->store_id[$index],
            'qty' => $request->qty[$index],
            'rate' => $request->rate[$index],
            'amount'=> $request->amount[$index],
            'store_qty' => $store_qty,
            'store_rate' => $store_rate,
            'store_amount'=> $store_amount,
            'room'=> $request->room[$index],     
            'rack'=> $request->rack[$index],     
            'shelf'=> $request->shelf[$index],
            'remarks' => $request->remarks[$index]     
            ]);

            $invtrimtransaction=$this->invtrimtransaction->create([
            'trans_type_id'=>1,
            'trans_date'=>$invyarnrcv->receive_date,
            'inv_trim_rcv_item_id'=>$invtrimrcvitem->id,
            'inv_trim_item_id'=>$invtrimitem->id,
            'company_id'=>$invyarnrcv->company_id,
            'supplier_id'=>$invyarnrcv->supplier_id,
            'store_id'=>$request->store_id[$index],
            'store_qty' => $store_qty,
            'store_rate' => $store_rate,
            'store_amount'=> $store_amount
            ]);
        }
        
      }
      catch(EXCEPTION $e)
      {
          \DB::rollback();
          throw $e;
      }
      \DB::commit();

      
      if($invtrimrcvitem){
        return response()->json(array('success' =>true ,'id'=>$invtrimrcvitem->id, 'inv_trim_rcv_id'=>$request->inv_trim_rcv_id,'message'=>'Saved Successfully'),200);
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
        //$rcv_item=$this->invtrimrcvitem->find($id);
        //$invtrimrcv=$this->invtrimrcv->find($rcv_item->inv_trim_rcv_id);
        //$invcv=$this->invrcv->find($invtrimrcv->inv_rcv_id);

        $rows = $this->invtrimrcvitem
        ->join('inv_trim_rcvs',function($join){
        $join->on('inv_trim_rcvs.id','=','inv_trim_rcv_items.inv_trim_rcv_id');
        })
        ->join('inv_rcvs',function($join){
        $join->on('inv_rcvs.id','=','inv_trim_rcvs.inv_rcv_id');
        })
        ->join('po_trim_item_reports', function($join){
        $join->on('po_trim_item_reports.id', '=', 'inv_trim_rcv_items.po_trim_item_report_id');
        })
        ->join('po_trim_items', function($join){
        $join->on('po_trim_items.id', '=', 'po_trim_item_reports.po_trim_item_id');
        })
        ->join('po_trims', function($join){
        $join->on('po_trim_items.po_trim_id', '=', 'po_trims.id');
        })
       
        ->join('currencies', function($join){
        $join->on('currencies.id', '=', 'po_trims.currency_id');
        })
        ->join('budget_trims', function($join){
        $join->on('budget_trims.id', '=', 'po_trim_items.budget_trim_id');
        })
        ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
        })
        ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->join('sales_orders', function($join){
        $join->on('sales_orders.id', '=', 'po_trim_item_reports.sales_order_id');
        })
        ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'itemclasses.costing_uom_id');
        })
        ->leftJoin('style_colors', function($join){
        $join->on('style_colors.id', '=', 'po_trim_item_reports.style_color_id');
        })
        ->leftJoin('style_sizes', function($join){
        $join->on('style_sizes.id', '=', 'po_trim_item_reports.style_size_id');
        })
        ->leftJoin('colors', function($join){
        $join->on('colors.id', '=', 'style_colors.color_id');
        })
        ->leftJoin('sizes', function($join){
        $join->on('sizes.id', '=', 'style_sizes.size_id');
        })
        ->leftJoin('colors as itemcolors', function($join){
        $join->on('itemcolors.id', '=', 'po_trim_item_reports.trim_color');
        })
        ->where([['inv_trim_rcv_items.id','=',$id]])
        ->get([
        'inv_trim_rcv_items.*',
        'inv_trim_rcvs.id as inv_trim_rcv_id',
        'po_trims.po_no',
        'po_trims.pi_no',
        'po_trims.exch_rate',
        'po_trim_items.id as po_trim_item_id',
        'po_trim_item_reports.id as po_trim_item_report_id',
        'po_trim_item_reports.description',
        'po_trim_item_reports.measurment',
        'po_trim_item_reports.trim_color as trim_color_id',
        'itemcolors.name as item_color_name',
        'colors.name as style_color_name',
        'sizes.name as style_size_name',
        'po_trim_item_reports.qty as po_qty',
        'po_trim_item_reports.rate as po_rate',
        'po_trim_item_reports.amount as po_amount',
        'itemcategories.name as category_name',
        'itemclasses.id as itemclass_id',
        'itemclasses.name as class_name',
        'uoms.code as uom_name',
        'currencies.code as currency_code',
        'sales_orders.sale_order_no'
        ])
        ->map(function($rows){
          return $rows;
        })
        ->first();
        if($rows->currency_code=='USD'){
        	$rows->exch_rate=84;
        }
        else{
        	$rows->exch_rate=1;
        }
       
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
    public function update(InvTrimRcvItemRequest $request, $id) {
      $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);
      $store_qty=$request->qty*1;
      $store_rate=$request->rate*$request->exch_rate;
      $store_amount=$request->amount*$request->exch_rate;

      \DB::beginTransaction();
      try
      {
      $invtrimrcvitem = $this->invtrimrcvitem->update($id,
      [
        'store_id'=> $request->store_id,
        'qty' => $request->qty,
        'rate' => $request->rate,
        'amount'=> $request->amount,
        'store_qty' => $store_qty,
        'store_rate' => $store_rate,
        'store_amount'=> $store_amount,
        'room'=> $request->room,     
        'rack'=> $request->rack,     
        'shelf'=> $request->shelf,
        'remarks' => $request->remarks     
      ]);

      $invtrimtransaction=$this->invtrimtransaction
      ->where([['inv_trim_rcv_item_id','=',$id]])
      ->where([['trans_type_id','=',1]])
      ->update([
        //'trans_type_id'=>1,
        'trans_date'=>$invyarnrcv->receive_date,
        'inv_trim_rcv_item_id'=>$id,
        'company_id'=>$invyarnrcv->company_id,
        'supplier_id'=>$invyarnrcv->supplier_id,
        'store_id'=>$request->store_id,
        'store_qty' => $store_qty,
        'store_rate' => $store_rate,
        'store_amount'=> $store_amount
        ]);
      }
      catch(EXCEPTION $e)
      {
          \DB::rollback();
          throw $e;
      }
      \DB::commit();

      
      if($invtrimrcvitem){
        return response()->json(array('success' =>true ,'id'=>$id, 'inv_trim_rcv_id'=>$request->inv_trim_rcv_id,'message'=>'Saved Successfully'),200);
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
        if($this->invtrimrcvitem->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


    public function getItem()
    {
        $invrcv=$this->invrcv->find(request('inv_rcv_id',0));
        $rows=$this->potrim
        ->join('po_trim_items', function($join){
        $join->on('po_trim_items.po_trim_id', '=', 'po_trims.id');
        })
        ->join('po_trim_item_reports', function($join){
        $join->on('po_trim_item_reports.po_trim_item_id', '=', 'po_trim_items.id');
        })
        
        ->join('currencies', function($join){
        $join->on('currencies.id', '=', 'po_trims.currency_id');
        })
        ->join('budget_trims', function($join){
        $join->on('budget_trims.id', '=', 'po_trim_items.budget_trim_id');
        })
        ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
        })
        ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->join('sales_orders', function($join){
        $join->on('sales_orders.id', '=', 'po_trim_item_reports.sales_order_id');
        })
        ->join('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->join('styles', function($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'itemclasses.costing_uom_id');
        })
        ->leftJoin('style_colors', function($join){
        $join->on('style_colors.id', '=', 'po_trim_item_reports.style_color_id');
        })
        ->leftJoin('style_sizes', function($join){
        $join->on('style_sizes.id', '=', 'po_trim_item_reports.style_size_id');
        })
        ->leftJoin('colors', function($join){
        $join->on('colors.id', '=', 'style_colors.color_id');
        })
        ->leftJoin('sizes', function($join){
        $join->on('sizes.id', '=', 'style_sizes.size_id');
        })
        ->leftJoin('colors as itemcolors', function($join){
        $join->on('itemcolors.id', '=', 'po_trim_item_reports.trim_color');
        })
        ->leftJoin(\DB::raw("(
            select 
            inv_trim_rcv_items.po_trim_item_report_id,
            sum(qty) as qty
            from
            inv_trim_rcv_items
            where inv_trim_rcv_items.deleted_at is null
            group by 
            inv_trim_rcv_items.po_trim_item_report_id) cumulatives"), "cumulatives.po_trim_item_report_id", "=", "po_trim_item_reports.id")
        ->when(request('po_trim_id'), function ($q) {
        return $q->where('po_trims.id', '=',request('po_trim_id', 0));
        })
        ->when(request('po_no'), function ($q) {
        return $q->where('po_trims.po_no', '=',request('po_no', 0));
        })
        ->when(request('pi_no'), function ($q) {
        return $q->where('po_trims.pi_no', '=',request('pi_no', 0));
        })
        

        ->where([['po_trims.company_id','=',$invrcv->company_id]])
        ->where([['po_trims.supplier_id','=',$invrcv->supplier_id]])
        ->whereNotNull('po_trims.approved_at')
        ->whereNull('po_trims.unapproved_at')
        ->whereIn('itemcategories.identity',[6])
        ->selectRaw(
        '
        po_trims.po_no,
        po_trims.pi_no,
        po_trims.exch_rate,
        po_trim_items.id,
        po_trim_item_reports.id as po_trim_item_report_id,
        po_trim_item_reports.description,
        po_trim_item_reports.measurment,
        po_trim_item_reports.trim_color as trim_color_id,
        itemcolors.name as item_color_name,
        colors.name as style_color_name,
        sizes.name as style_size_name,
        po_trim_item_reports.qty as qty,
        po_trim_item_reports.rate as rate,
        po_trim_item_reports.amount as amount,
        itemcategories.name as category_name,
        itemclasses.id as itemclass_id,
        itemclasses.name as class_name,
        uoms.code as uom_name,
        currencies.code as currency_code,
        sales_orders.sale_order_no,
        styles.style_ref,
        cumulatives.qty as cu_qty
        ')
        ->get()
        ->map(function ($rows) {
            if($rows->currency_code=='USD'){
            $rows->exch_rate=84;
            }
            else{
            $rows->exch_rate=1;
            }
            $rows->bal_qty=$rows->qty-$rows->cu_qty;
            $rows->bal_amount=$rows->bal_qty-$rows->rate;
            if($rows->bal_qty<0){
               $rows->bal_qty=0; 
            }
            if($rows->bal_amount<0){
               $rows->bal_amount=0; 
            }
            return $rows;
        });
        echo json_encode($rows);
    }
}