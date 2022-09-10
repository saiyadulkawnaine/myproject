<?php

namespace App\Http\Controllers\Inventory\DyeChem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemRcvRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemRcvItemRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemTransactionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuRtnItemRequest;

class InvDyeChemIsuRtnItemController extends Controller {

    private $invrcv;
    private $invdyechemrcv;
    private $invdyechemrcvitem;
    private $invdyechemtransaction;
    private $itemaccount;
    private $invisu;
    private $job;

    public function __construct(
        InvRcvRepository $invrcv,
        InvDyeChemRcvRepository $invdyechemrcv, 
        InvDyeChemRcvItemRepository $invdyechemrcvitem,
        InvDyeChemTransactionRepository $invdyechemtransaction, 
        ItemAccountRepository $itemaccount,
        InvIsuRepository $invisu,
        JobRepository $job
    ) {
        $this->invrcv = $invrcv;
        $this->invdyechemrcv = $invdyechemrcv;
        $this->invdyechemrcvitem = $invdyechemrcvitem;
        $this->invdyechemtransaction = $invdyechemtransaction;
        $this->itemaccount = $itemaccount;
        $this->invisu = $invisu;
        $this->job = $job;
        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemisurtnitem',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisurtnitem', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisurtnitem',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisurtnitem', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $inv_dye_chem_rcv_id=request('inv_dye_chem_rcv_id',0);
        $invdyechemrcv=$this->invdyechemrcv->find($inv_dye_chem_rcv_id);
        $invcv=$this->invrcv->find($invdyechemrcv->inv_rcv_id);
        $rows = $this->invrcv
        ->join('inv_dye_chem_rcvs',function($join){
        $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_dye_chem_rcv_items',function($join){
        $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','inv_dye_chem_rcv_items.sales_order_id');
        })
        ->join('item_accounts',function($join){
        $join->on('inv_dye_chem_rcv_items.item_account_id','=','item_accounts.id');
        })
        ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->where([['inv_dye_chem_rcvs.id','=',$inv_dye_chem_rcv_id]])
        ->orderBy('inv_dye_chem_rcvs.id','desc')
        ->get([
        'inv_dye_chem_rcv_items.*',
        'inv_dye_chem_rcvs.id as inv_dye_chem_rcv_id',
        'itemcategories.name as category_name',
        'itemclasses.name as class_name',
        'item_accounts.sub_class_name',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code as uom_name',
        'uoms.code as store_uom',
        'sales_orders.sale_order_no',
        ])
        ->map(function($rows){
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
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvDyeChemIsuRtnItemRequest $request) {

      $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);

      $store_qty=$request->qty*1;
      $store_rate=$request->rate;
      $store_amount=$request->amount;
      \DB::beginTransaction();
      try
      {
        $invdyechemrcvitem = $this->invdyechemrcvitem->create(
        [
        'inv_dye_chem_rcv_id'=> $request->inv_dye_chem_rcv_id,         
        'sales_order_id'=> $request->sales_order_id,
        'item_account_id'=> $request->item_account_id,        
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

        $invdyechemtransaction=$this->invdyechemtransaction->create([
        'trans_type_id'=>1,
        'trans_date'=>$invyarnrcv->receive_date,
        'inv_dye_chem_rcv_item_id'=>$invdyechemrcvitem->id,
        'item_account_id'=>$request->item_account_id,
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

      
      if($invdyechemrcvitem){
        return response()->json(array('success' =>true ,'id'=>$invdyechemrcvitem->id, 'inv_dye_chem_rcv_id'=>$request->inv_dye_chem_rcv_id,'message'=>'Saved Successfully'),200);
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
        $rcv_item=$this->invdyechemrcvitem->find($id);
        $invdyechemrcv=$this->invdyechemrcv->find($rcv_item->inv_dye_chem_rcv_id);
        $invcv=$this->invrcv->find($invdyechemrcv->inv_rcv_id);

        
           $rows =$this->invdyechemrcvitem
          ->join('inv_dye_chem_rcvs',function($join){
          $join->on('inv_dye_chem_rcvs.id','=','inv_dye_chem_rcv_items.inv_dye_chem_rcv_id');
          })
          ->join('inv_rcvs',function($join){
          $join->on('inv_rcvs.id','=','inv_dye_chem_rcvs.inv_rcv_id');
          })
          ->leftJoin('sales_orders',function($join){
          $join->on('sales_orders.id','=','inv_dye_chem_rcv_items.sales_order_id');
          })
          ->join('item_accounts',function($join){
          $join->on('inv_dye_chem_rcv_items.item_account_id','=','item_accounts.id');
          })
          ->join('itemclasses', function($join){
          $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
          })
          ->join('itemcategories', function($join){
          $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
          })
          ->leftJoin('uoms', function($join){
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
          })
          ->where([['inv_dye_chem_rcv_items.id','=',$id]])
          ->orderBy('inv_rcvs.id','desc')
          ->get([
          'inv_dye_chem_rcv_items.*',
          'inv_dye_chem_rcvs.id as inv_dye_chem_rcv_id',
          'itemcategories.name as item_category',
          'itemclasses.name as item_class',
          'item_accounts.sub_class_name',
          'item_accounts.item_description as item_desc',
          'item_accounts.specification',
          'uoms.code as uom_code',
          'uoms.code as store_uom',
          'sales_orders.sale_order_no',
          ])
          ->map(function($rows){
            //$rows->currency_code='BDT';
            //$rows->exch_rate=1;
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
    public function update(InvDyeChemIsuRtnItemRequest $request, $id) {
      $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);
      $store_qty=$request->qty;
      $store_rate=$request->rate;
      $store_amount=$request->amount;

      \DB::beginTransaction();
      try
      {
      $invdyechemrcvitem = $this->invdyechemrcvitem->update($id,
      [
        //'inv_dye_chem_rcv_id'=> $request->inv_dye_chem_rcv_id,         
        'sales_order_id'=> $request->sales_order_id,
        'item_account_id'=> $request->item_account_id,        
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

      $invdyechemtransaction=$this->invdyechemtransaction
      ->where([['inv_dye_chem_rcv_item_id','=',$id]])
      ->where([['trans_type_id','=',1]])
      ->update([
        //'trans_type_id'=>1,
        'trans_date'=>$invyarnrcv->receive_date,
        'inv_dye_chem_rcv_item_id'=>$id,
        'item_account_id'=>$request->item_account_id,
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

      
      if($invdyechemrcvitem){
        return response()->json(array('success' =>true ,'id'=>$id, 'inv_dye_chem_rcv_id'=>$request->inv_dye_chem_rcv_id,'message'=>'Saved Successfully'),200);
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
          $rows=$this->itemaccount
          ->join('itemclasses', function($join){
            $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
          })
          ->join('itemcategories', function($join){
            $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
          })
          ->leftJoin('uoms', function($join){
            $join->on('uoms.id', '=', 'item_accounts.uom_id');
          })
          ->whereIn('itemcategories.identity',[7,8])
          ->selectRaw(
          '
          itemcategories.name as category_name,
          itemclasses.name as class_name,
          item_accounts.id,
          item_accounts.id as item_account_id,
          item_accounts.sub_class_name,
          item_accounts.item_description,
          item_accounts.specification,
          uoms.code as uom_name
          ')
          ->get()
          ->map(function($rows){
            //$rows->currency_code='BDT';
            //$rows->exch_rate=1;
            return $rows;
          });
          echo json_encode($rows);
    }

    public function getRate()
    {
        $item_account_id=request('item_account_id',0);
        $rate=$this->itemaccount
        ->join('inv_dye_chem_rcv_items',function($join){
            $join->on('item_accounts.id','=','inv_dye_chem_rcv_items.item_account_id')
            ->whereNull('inv_dye_chem_rcv_items.deleted_at');
        })
        ->join('inv_dye_chem_rcvs',function($join){
            $join->on('inv_dye_chem_rcvs.id','=','inv_dye_chem_rcv_items.inv_dye_chem_rcv_id');
        })
        ->where([['item_accounts.id','=',$item_account_id]])
        ->orderBy('inv_dye_chem_rcvs.id','desc')
        ->orderBy('inv_dye_chem_rcv_items.id','desc')
        ->get(['inv_dye_chem_rcv_items.store_rate'])
        ->first();
        echo json_encode($rate);
    }

    public function getOrder()
    {

          $invrcv=$this->invrcv->find(request('inv_dye_chem_rcv_id',0));
          $rows=$this->job
          ->join('sales_orders', function($join){
            $join->on('sales_orders.job_id', '=', 'jobs.id');
          })
          ->join('styles', function($join){
            $join->on('styles.id', '=', 'jobs.style_id');
          })
          ->leftJoin('buyers', function($join){
            $join->on('buyers.id', '=', 'styles.buyer_id');
          })
          ->leftJoin('companies', function($join){
            $join->on('companies.id', '=', 'jobs.company_id');
          })
          ->leftJoin('companies as produced_company', function($join)  {
          $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
          })
          ->leftJoin('teammembers', function($join)  {
          $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
          })
          ->leftJoin('users', function($join)  {
          $join->on('users.id', '=', 'teammembers.user_id');
          })
          ->where([['jobs.company_id','=',$invrcv->company_id]])
          ->selectRaw(
          '
          styles.style_ref,
          buyers.name as buyer_name,
          sales_orders.id,
          sales_orders.id as sale_order_id,
          sales_orders.sale_order_no,
          sales_orders.qty,
          sales_orders.rate,
          sales_orders.amount,
          sales_orders.ship_date,
          companies.code as company_name,
          produced_company.code as pcompany_name,
          users.name as team_member_name
          ')
          ->get()
          ->map(function($rows){
            $rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
            return $rows;
          });
          echo json_encode($rows);
        
    }
}