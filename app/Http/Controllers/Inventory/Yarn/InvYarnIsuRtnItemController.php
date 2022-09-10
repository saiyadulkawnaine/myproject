<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvItemRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRtnRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRtnItemRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransactionRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnIsuRtnItemRequest;

class InvYarnIsuRtnItemController extends Controller {
    private $invrcv;
    private $invyarnrcv;
    private $invyarnrcvitem;
    private $invyarnisurtn;
    private $invyarnisurtnitem;
    private $store;
    private $rqyarn;
    private $itemaccount;
    private $invyarntransaction;
    private $invyarnitem;
    private $poyarndyeing;
    private $salesorder;

    public function __construct(
        InvRcvRepository $invrcv,
        InvYarnRcvRepository $invyarnrcv, 
        InvYarnRcvItemRepository $invyarnrcvitem,

        InvYarnIsuRtnRepository $invyarnisurtn,
        InvYarnIsuRtnItemRepository $invyarnisurtnitem, 
        StoreRepository $store,
        RqYarnRepository $rqyarn,
        ItemAccountRepository $itemaccount,
        InvYarnTransactionRepository $invyarntransaction,
        InvYarnItemRepository $invyarnitem,
        PoYarnDyeingRepository $poyarndyeing,
        SalesOrderRepository $salesorder
    ) 
    {
        $this->invrcv = $invrcv;
        $this->invyarnrcv = $invyarnrcv;
        $this->invyarnrcvitem = $invyarnrcvitem;
        $this->invyarnisurtn = $invyarnisurtn;
        $this->invyarnisurtnitem = $invyarnisurtnitem;
        $this->store = $store;
        $this->rqyarn = $rqyarn;
        $this->itemaccount = $itemaccount;
        $this->invyarntransaction = $invyarntransaction;
        $this->invyarnitem = $invyarnitem;
        $this->poyarndyeing = $poyarndyeing;
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
        $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })

        ->where([['itemcategories.identity','=',1]])
        ->orderBy('item_account_ratios.ratio','desc')
        ->get([
        'item_accounts.id',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);

        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
        }
       
        $inv_yarn_rcv_id=request('inv_yarn_rcv_id',0);
        $invyarnrcv=$this->invyarnrcv->find($inv_yarn_rcv_id);
        $invcv=$this->invrcv->find($invyarnrcv->inv_rcv_id);

        $invyarnrcvitem=$this->invrcv
        ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_yarn_rcv_items',function($join){
        $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id')
        ->whereNull('inv_yarn_rcv_items.deleted_at');
        })

        ->join('inv_yarn_items',function($join){
        $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
        ->whereNull('inv_yarn_rcv_items.deleted_at');
        })
        ->join('item_accounts',function($join){
        $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
        })

        ->leftJoin('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->join('uoms',function($join){
        $join->on('uoms.id','=','item_accounts.uom_id');
        })

        ->join('colors',function($join){
        $join->on('colors.id','=','inv_yarn_items.color_id');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','inv_yarn_rcv_items.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
        })
        ->where([['inv_yarn_rcvs.id','=',$inv_yarn_rcv_id]])
        ->orderBy('inv_yarn_rcv_items.id','desc')
        ->get([
        'itemcategories.name as itemcategory_name',
        'itemclasses.name as itemclass_name',
        'item_accounts.id as item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'uoms.code as uom_code',

        'inv_yarn_rcv_items.id',
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'colors.name as color_name',
        'inv_yarn_rcv_items.qty',
        'inv_yarn_rcv_items.rate',
        'inv_yarn_rcv_items.amount',
        'inv_yarn_rcv_items.store_qty',
        'uoms.code as uom',
        'inv_yarn_rcv_items.store_rate',
        'inv_yarn_rcv_items.store_amount',
        'styles.style_ref',
        'buyers.name as buyer_name',
        'sales_orders.sale_order_no'
        ])
        ->map(function($invyarnrcvitem) use($yarnDropdown) {
        $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
        $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
        $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
        return $invyarnrcvitem;
        });
        echo json_encode($invyarnrcvitem);
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
    
    public function store(InvYarnIsuRtnItemRequest $request) {
        $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);
        \DB::beginTransaction();
        $store_qty=$request->qty;
        $store_rate=$request->rate;
        $store_amount=$request->amount;
        try
        {
            $invyarnrcvitem = $this->invyarnrcvitem->create(
            [
                'inv_yarn_rcv_id'=> $request->inv_yarn_rcv_id,         
                'inv_yarn_item_id'=> $request->inv_yarn_item_id,          
                'sales_order_id'=> $request->sales_order_id,          
                'store_id'=> $request->store_id,
                'cone_per_bag'=> $request->cone_per_bag,     
                'wgt_per_cone'=> $request->wgt_per_cone,     
                'wgt_per_bag'=> $request->wgt_per_bag,     
                'no_of_bag'=> $request->no_of_bag,
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

            $invyarntransaction=$this->invyarntransaction->create(
            [
                'trans_type_id'=>1,
                'trans_date'=>$invyarnrcv->receive_date,
                'inv_yarn_rcv_item_id'=>$invyarnrcvitem->id,
                'inv_yarn_item_id'=>$request->inv_yarn_item_id,
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
        if($invyarnrcvitem){
        return response()->json(array('success' => true,'id' =>  $invyarnrcvitem->id,'inv_yarn_rcv_id' => $request->inv_yarn_rcv_id,'message' => 'Save Successfully'),200);
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
        $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })

        ->where([['itemcategories.identity','=',1]])
        ->orderBy('item_account_ratios.ratio','desc')
        ->get([
        'item_accounts.id',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);

        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
        }
        /*
       $rows = $this->invyarnisurtnitem
       ->join('inv_yarn_isu_rtns',function($join){
            $join->on('inv_yarn_isu_rtn_items.inv_yarn_isu_rtn_id','=','inv_yarn_isu_rtns.id');
       })
       ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_isu_rtn_items.inv_yarn_item_id');
        })
       ->join('suppliers',function($join){
            $join->on('suppliers.id','=','inv_yarn_items.supplier_id'); 
        })
        ->join('item_accounts',function($join){
            $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->join('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id','=','inv_yarn_isu_rtn_items.sales_order_id');
        })
        ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->join('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
       ->where([['inv_yarn_isu_rtn_items.id','=',$id]])
       ->orderBy('inv_yarn_isu_rtn_items.id','desc')
       ->get([
        'inv_yarn_isu_rtn_items.*',
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'colors.name as color_name',
        'itemcategories.name as itemcategory_name',
        'itemclasses.name as itemclass_name',
        'item_accounts.id as item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'uoms.code as uom_code',
        'styles.style_ref',
        'buyers.name as buyer_name',
        'sales_orders.sale_order_no',
        'suppliers.name as supplier_name',
       ])
       ->map(function($rows) use($yarnDropdown) {
            $rows->yarn_count=$rows->count."/".$rows->symbol;
            $rows->yarn_des=$rows->yarn_count.",".$yarnDropdown[$rows->item_account_id].",".$rows->yarn_type.",".$rows->brand.",".$rows->color_name.",".$rows->lot;
            return $rows;
        })->first();*/
        //$inv_yarn_rcv_id=request('inv_yarn_rcv_id',0);
        //$invyarnrcv=$this->invyarnrcv->find($inv_yarn_rcv_id);
        //$invcv=$this->invrcv->find($invyarnrcv->inv_rcv_id);

        $rows=$this->invyarnrcvitem
        ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcvs.id','=','inv_yarn_rcv_items.inv_yarn_rcv_id');
        })
        ->join('inv_rcvs',function($join){
        $join->on('inv_rcvs.id','=','inv_yarn_rcvs.inv_rcv_id');
        })
        ->join('inv_yarn_items',function($join){
        $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id');
        })
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_yarn_items.supplier_id');
        })
        ->join('item_accounts',function($join){
        $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
        })

        ->leftJoin('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->join('uoms',function($join){
        $join->on('uoms.id','=','item_accounts.uom_id');
        })

        ->join('colors',function($join){
        $join->on('colors.id','=','inv_yarn_items.color_id');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','inv_yarn_rcv_items.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
        })
        ->where([['inv_yarn_rcv_items.id','=',$id]])
        ->orderBy('inv_yarn_rcv_items.id','desc')
        ->get([
        'inv_yarn_rcv_items.*',
        

        'itemcategories.name as itemcategory_name',
        'itemclasses.name as itemclass_name',
        'item_accounts.id as item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'uoms.code as uom_code',

        
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'suppliers.name as supplier_name',
        'colors.name as color_name',
        
        'uoms.code as uom',
        
        'styles.style_ref',
        'buyers.name as buyer_name',
        'sales_orders.sale_order_no'
        ])
        ->map(function($rows) use($yarnDropdown) {
            $rows->yarn_count=$rows->count."/".$rows->symbol;
            $rows->yarn_des=$rows->yarn_count.",".$yarnDropdown[$rows->item_account_id].",".$rows->yarn_type.",".$rows->brand.",".$rows->color_name.",".$rows->lot;
            return $rows;
        })->first();
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
    public function update(InvYarnIsuRtnItemRequest $request, $id) {
        $issueNo=$this->invyarntransaction
        ->join('inv_yarn_isu_items',function($join){
        $join->on('inv_yarn_isu_items.id','=','inv_yarn_transactions.inv_yarn_isu_item_id');
        })
        ->join('inv_isus',function($join){
        $join->on('inv_isus.id','=','inv_yarn_isu_items.inv_isu_id');
        })
        ->where([['inv_yarn_transactions.inv_yarn_rcv_item_id','=',$id]])
        ->where([['inv_yarn_transactions.trans_type_id','=',2]])
        ->get(['inv_isus.issue_no'])
        ->first();
        
        if($issueNo){
        return response()->json(array('success' => false,'message' => 'Update No Possible, Issue no '.$issueNo->issue_no.' Found '),200);

        }


        $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);
        $store_qty=$request->qty;
        $store_rate=$request->rate;
        $store_amount=$request->amount;
        \DB::beginTransaction();
        try
        {
        $invyarnrcvitem = $this->invyarnrcvitem->update($id,
        [
            'inv_yarn_item_id'=> $request->inv_yarn_item_id,         
            'store_id'=> $request->store_id, 
            'cone_per_bag'=> $request->cone_per_bag,     
            'wgt_per_cone'=> $request->wgt_per_cone,     
            'wgt_per_bag'=> $request->wgt_per_bag,     
            'no_of_bag'=> $request->no_of_bag,
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

        $invyarntransaction=$this->invyarntransaction
        ->where([['inv_yarn_rcv_item_id','=',$id]])
        ->where([['trans_type_id','=',1]])
        ->update([
            'trans_date'=>$invyarnrcv->receive_date,
            'inv_yarn_item_id'=>$request->inv_yarn_item_id, 
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
        if($invyarnrcvitem){
            return response()->json(array('success' => true,'id' => $id,'inv_yarn_rcv_id' => $request->inv_yarn_rcv_id,'message' => 'Update Successfully'),200);
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
        if($this->invyarnisurtn->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }

    public function getRtnYarnItem()
    {
        $yarnDescription=$this->invyarnitem
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_yarn_items.item_account_id'); 
        })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','inv_yarn_items.supplier_id'); 
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id'); 
        })
        ->leftJoin('item_account_ratios',function($join){
            $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('compositions',function($join){
            $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })

        ->where([['itemcategories.identity','=',1]])
        ->get([
        'inv_yarn_items.id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'itemclasses.name as itemclass_name',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);
        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
            $itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
        }

        $invrcv=$this->invrcv->find(request('inv_rcv_id'));

        $rows=$this->invyarnitem
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_yarn_items.item_account_id'); 
        })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','inv_yarn_items.supplier_id'); 
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id'); 
        })
        ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->where([['inv_yarn_items.supplier_id','=',$invrcv->supplier_id]])
        ->when(request('lot'), function ($q) {
            return $q->where('inv_yarn_items.lot', 'like', '%'.request('lot', 0).'%');
        })
        ->when(request('brand'), function ($q) {
            return $q->where('inv_yarn_items.brand', 'like', '%'.request('brand', 0).'%');
        })
        ->get([
            'inv_yarn_items.id',
            'inv_yarn_items.lot',
            'inv_yarn_items.brand',
            'inv_yarn_items.supplier_id',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'suppliers.name as supplier_name',
            'itemcategories.name as itemcategory_name',
            'colors.name as yarn_color_name'
        ])
        ->map(function ($rows) use($yarnDropdown)  {
            $rows->yarn_des = isset($yarnDropdown[$rows->id])?$yarnDropdown[$rows->id].','.$rows->brand.','.$rows->yarn_color_name:'';
            $rows->inv_yarn_item_id = $rows->id;
            return $rows;
        });
        echo json_encode($rows);
    }

    public function getRate()
    {
        $inv_yarn_item_id=request('inv_yarn_item_id',0);
        $rate=$this->invyarnitem
        ->join('inv_yarn_rcv_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
        })
        ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcvs.id','=','inv_yarn_rcv_items.inv_yarn_rcv_id');
        })
        ->where([['inv_yarn_items.id','=',$inv_yarn_item_id]])
        ->orderBy('inv_yarn_rcvs.id','desc')
        ->orderBy('inv_yarn_rcv_items.id','desc')
        ->get(['inv_yarn_rcv_items.store_rate'])
        ->first();
        echo json_encode($rate);
    }

    public function getRtnSaleOrder(){
        $salesorder=$this->salesorder
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
         produced_company.name as produced_company_name
         ')
        // ->join('sales_order_countries',function($join){
        //      $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
        //  })
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
        // ->join('sales_order_gmt_color_sizes', function($join)  {
        //  $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
        //  })
        //  ->join('style_gmts',function($join){
        //  $join->on('style_gmts.id','=','sales_order_gmt_color_sizes.style_gmt_id');
        //  })
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
        ])
        ->get()
        ->map(function ($salesorder){
          return $salesorder;
         });
        echo json_encode($salesorder);
    }
}