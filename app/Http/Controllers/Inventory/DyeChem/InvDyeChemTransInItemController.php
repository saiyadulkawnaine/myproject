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
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemTransInItemRequest;

class InvDyeChemTransInItemController extends Controller {

    private $invrcv;
    private $invdyechemrcv;
    private $invdyechemrcvitem;
    private $invdyechemtransaction;
    private $itemaccount;
    private $invisu;

    public function __construct(
        InvRcvRepository $invrcv,
        InvDyeChemRcvRepository $invdyechemrcv, 
        InvDyeChemRcvItemRepository $invdyechemrcvitem,
        InvDyeChemTransactionRepository $invdyechemtransaction, 
        ItemAccountRepository $itemaccount,
        InvIsuRepository $invisu
    ) {
        $this->invrcv = $invrcv;
        $this->invdyechemrcv = $invdyechemrcv;
        $this->invdyechemrcvitem = $invdyechemrcvitem;
        $this->invdyechemtransaction = $invdyechemtransaction;
        $this->itemaccount = $itemaccount;
        $this->invisu = $invisu;
        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemtransinitem',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemtransinitem', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemtransinitem',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemtransinitem', ['only' => ['destroy']]);
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
        ->join('inv_dye_chem_isu_items',function($join){
        $join->on('inv_dye_chem_isu_items.id','=','inv_dye_chem_rcv_items.inv_dye_chem_isu_item_id');
        })
        ->join('inv_isus',function($join){
        $join->on('inv_isus.id','=','inv_dye_chem_isu_items.inv_isu_id');
        })
        ->join('item_accounts',function($join){
        $join->on('inv_dye_chem_isu_items.item_account_id','=','item_accounts.id');
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
        'inv_isus.issue_no as transfer_no',
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
    public function store(InvDyeChemTransInItemRequest $request) {

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
        'inv_dye_chem_isu_item_id'=> $request->inv_dye_chem_isu_item_id,
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
          ->join('inv_dye_chem_isu_items',function($join){
          $join->on('inv_dye_chem_isu_items.id','=','inv_dye_chem_rcv_items.inv_dye_chem_isu_item_id');
          })
          ->join('inv_isus',function($join){
          $join->on('inv_isus.id','=','inv_dye_chem_isu_items.inv_isu_id');
          })
          ->join('item_accounts',function($join){
          $join->on('inv_dye_chem_isu_items.item_account_id','=','item_accounts.id');
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
          'inv_isus.issue_no as transfer_no',
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
    public function update(InvDyeChemTransInItemRequest $request, $id) {
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
        'inv_dye_chem_isu_item_id'=> $request->inv_dye_chem_isu_item_id,
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
          $rows=$this->invisu
          ->join('inv_dye_chem_isu_items', function($join){
            $join->on('inv_dye_chem_isu_items.inv_isu_id', '=', 'inv_isus.id');
          })
          ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'inv_dye_chem_isu_items.item_account_id');
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
          ->where([['inv_isus.isu_basis_id','=',9]])
          ->where([['inv_isus.company_id','=',$invrcv->from_company_id]])
          ->when(request('challan_no'), function ($q) {
          return $q->where('inv_isus.issue_no', 'like', '%'.request('challan_no', 0).'%');
          })
          ->selectRaw(
          '
          inv_isus.issue_no as transfer_no,
          inv_dye_chem_isu_items.id as inv_dye_chem_isu_item_id,
          inv_dye_chem_isu_items.qty,
          inv_dye_chem_isu_items.rate,
          inv_dye_chem_isu_items.amount,
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
}