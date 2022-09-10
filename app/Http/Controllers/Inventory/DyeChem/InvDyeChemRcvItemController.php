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
use App\Repositories\Contracts\Purchase\PoDyeChemRepository;
use App\Repositories\Contracts\Purchase\PoDyeChemItemRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqItemRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemRcvItemRequest;

class InvDyeChemRcvItemController extends Controller {

    private $invrcv;
    private $invdyechemrcv;
    private $invdyechemrcvitem;
    private $invdyechemtransaction;
    private $podyechem;
    private $podyechemitem;
    private $invpurreq;
    private $invpurreqitem;
    private $itemaccount;

    public function __construct(
        InvRcvRepository $invrcv,
        InvDyeChemRcvRepository $invdyechemrcv, 
        InvDyeChemRcvItemRepository $invdyechemrcvitem,
        InvDyeChemTransactionRepository $invdyechemtransaction, 
        PoDyeChemRepository $podyechem,
        PoDyeChemItemRepository $podyechemitem,
        InvPurReqRepository $invpurreq,
        InvPurReqItemRepository $invpurreqitem,
        ItemAccountRepository $itemaccount
    ) {
        $this->invrcv = $invrcv;
        $this->invdyechemrcv = $invdyechemrcv;
        $this->invdyechemrcvitem = $invdyechemrcvitem;
        $this->invdyechemtransaction = $invdyechemtransaction;
        $this->podyechem = $podyechem;
        $this->podyechemitem = $podyechemitem;
        $this->invpurreq = $invpurreq;
        $this->invpurreqitem = $invpurreqitem;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemrcvitem',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemrcvitem', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemrcvitem',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemrcvitem', ['only' => ['destroy']]);
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
        if($invcv->receive_against_id==0)
        {
          $rows = $this->invrcv
          ->join('inv_dye_chem_rcvs',function($join){
          $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
          })
          ->join('inv_dye_chem_rcv_items',function($join){
          $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id');
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
          ])
          ->map(function($rows){
            $rows->currency_code='BDT';
            $rows->exch_rate=1;
            return $rows;
          });
        }


        if($invcv->receive_against_id==7)
        {
          $rows = $this->invrcv
          ->join('inv_dye_chem_rcvs',function($join){
          $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
          })
          ->join('inv_dye_chem_rcv_items',function($join){
          $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id');
          })
          ->join('po_dye_chem_items', function($join){
            $join->on('po_dye_chem_items.id', '=', 'inv_dye_chem_rcv_items.po_dye_chem_item_id');
            })
          ->join('po_dye_chems', function($join){
            $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
            })

          ->join('inv_pur_req_items', function($join){
            $join->on('inv_pur_req_items.id', '=', 'po_dye_chem_items.inv_pur_req_item_id');
            })
          ->join('inv_pur_reqs', function($join){
          $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
          })
          ->join('currencies', function($join){
          $join->on('currencies.id', '=', 'inv_pur_reqs.currency_id');
          })
          ->join('item_accounts', function($join){
          $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
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
          'currencies.code as currency_code',
          'inv_pur_reqs.requisition_no as rq_no',
          'po_dye_chems.po_no',
          'po_dye_chems.exch_rate',
          'po_dye_chems.pi_no'
          ])
          ->map(function($rows){
            return $rows;
          });
        }

        if($invcv->receive_against_id==109)
        {
          $rows = $this->invrcv
          ->join('inv_dye_chem_rcvs',function($join){
          $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
          })
          ->join('inv_dye_chem_rcv_items',function($join){
          $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id');
          })
          ->join('inv_pur_req_items', function($join){
          $join->on('inv_pur_req_items.id', '=', 'inv_dye_chem_rcv_items.inv_pur_req_item_id');
          })
          ->join('inv_pur_reqs', function($join){
          $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
          })
          ->join('currencies', function($join){
          $join->on('currencies.id', '=', 'inv_pur_reqs.currency_id');
          })
          ->join('item_accounts', function($join){
          $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
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
          'currencies.code as currency_code',
          'inv_pur_reqs.requisition_no as rq_no'
          ])
          ->map(function($rows){
            $rows->exch_rate=1;
            return $rows;
          });
        }
       
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
    public function store(InvDyeChemRcvItemRequest $request) {

      $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);

      $store_qty=$request->qty*1;
      $store_rate=$request->rate*$request->exch_rate;
      $store_amount=$request->amount*$request->exch_rate;
      \DB::beginTransaction();
      try
      {
        $invdyechemrcvitem = $this->invdyechemrcvitem->create(
        [
        'inv_dye_chem_rcv_id'=> $request->inv_dye_chem_rcv_id,         
        'po_dye_chem_item_id'=> $request->po_dye_chem_item_id,
        'inv_pur_req_item_id'=> $request->inv_pur_req_item_id,
        'item_account_id'=> $request->item_account_id,        
        'store_id'=> $request->store_id,
        'batch'=> $request->batch,        
        'expiry_date'=> $request->expiry_date,        
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

        if($invcv->receive_against_id==0)
        {
          //$this->invrcv
           $rows =$this->invdyechemrcvitem
          ->join('inv_dye_chem_rcvs',function($join){
          $join->on('inv_dye_chem_rcvs.id','=','inv_dye_chem_rcv_items.inv_dye_chem_rcv_id');
          })
          ->join('inv_rcvs',function($join){
          $join->on('inv_rcvs.id','=','inv_dye_chem_rcvs.inv_rcv_id');
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
          'itemcategories.name as category_name',
          'itemclasses.name as class_name',
          'item_accounts.sub_class_name',
          'item_accounts.item_description as item_desc',
          'item_accounts.specification',
          'uoms.code as uom_code',
          'uoms.code as store_uom',
          ])
          ->map(function($rows){
            $rows->currency_code='BDT';
            $rows->exch_rate=1;
            return $rows;
          })->first();
        }


        if($invcv->receive_against_id==7)
        {
            $rows =$this->invdyechemrcvitem
            ->join('inv_dye_chem_rcvs',function($join){
            $join->on('inv_dye_chem_rcvs.id','=','inv_dye_chem_rcv_items.inv_dye_chem_rcv_id');
            })
            ->join('inv_rcvs',function($join){
            $join->on('inv_rcvs.id','=','inv_dye_chem_rcvs.inv_rcv_id');
            })
            ->join('po_dye_chem_items', function($join){
            $join->on('po_dye_chem_items.id', '=', 'inv_dye_chem_rcv_items.po_dye_chem_item_id');
            })
            ->join('po_dye_chems', function($join){
            $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
            })

            ->join('inv_pur_req_items', function($join){
            $join->on('inv_pur_req_items.id', '=', 'po_dye_chem_items.inv_pur_req_item_id');
            })
            ->join('inv_pur_reqs', function($join){
            $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
            })
            ->join('currencies', function($join){
            $join->on('currencies.id', '=', 'inv_pur_reqs.currency_id');
            })
            ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
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
            'itemcategories.name as category_name',
            'itemclasses.name as class_name',
            'item_accounts.sub_class_name',
            'item_accounts.item_description as item_desc',
            'item_accounts.specification',
            'uoms.code as uom_code',
            'uoms.code as store_uom',
            'currencies.code as currency_code',
            'inv_pur_reqs.requisition_no as rq_no',
            'po_dye_chems.po_no',
            'po_dye_chems.exch_rate',
            'po_dye_chems.pi_no'
            ])
            ->map(function($rows){
            ///$rows->exch_rate=1;
            return $rows;
            })->first();
        }

        if($invcv->receive_against_id==109)
        {
          $rows =$this->invdyechemrcvitem
          ->join('inv_dye_chem_rcvs',function($join){
          $join->on('inv_dye_chem_rcvs.id','=','inv_dye_chem_rcv_items.inv_dye_chem_rcv_id');
          })
          ->join('inv_rcvs',function($join){
          $join->on('inv_rcvs.id','=','inv_dye_chem_rcvs.inv_rcv_id');
          })
          ->join('inv_pur_req_items', function($join){
          $join->on('inv_pur_req_items.id', '=', 'inv_dye_chem_rcv_items.inv_pur_req_item_id');
          })
          ->join('inv_pur_reqs', function($join){
          $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
          })
          ->join('currencies', function($join){
          $join->on('currencies.id', '=', 'inv_pur_reqs.currency_id');
          })
          ->join('item_accounts', function($join){
          $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
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
          'itemcategories.name as category_name',
          'itemclasses.name as class_name',
          'item_accounts.sub_class_name',
          'item_accounts.item_description as item_desc',
          'item_accounts.specification',
          'uoms.code as uom_code',
          'uoms.code as store_uom',
          'currencies.code as currency_code',
          'inv_pur_reqs.requisition_no as rq_no'
          ])
          ->map(function($rows){
            $rows->exch_rate=1;
            return $rows;
          })->first();
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
    public function update(InvDyeChemRcvItemRequest $request, $id) {
      $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);
      $store_qty=$request->qty*1;
      $store_rate=$request->rate*$request->exch_rate;
      $store_amount=$request->amount*$request->exch_rate;

      \DB::beginTransaction();
      try
      {
      $invdyechemrcvitem = $this->invdyechemrcvitem->update($id,
      [
        //'inv_dye_chem_rcv_id'=> $request->inv_dye_chem_rcv_id,         
        'po_dye_chem_item_id'=> $request->po_dye_chem_item_id,
        'inv_pur_req_item_id'=> $request->inv_pur_req_item_id,
        'item_account_id'=> $request->item_account_id,        
        'store_id'=> $request->store_id,
        'batch'=> $request->batch,        
        'expiry_date'=> $request->expiry_date,        
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
        if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


    public function getItem()
    {
        $invrcv=$this->invrcv->find(request('inv_rcv_id',0));

        if($invrcv->receive_against_id==0)
        {// Opening Balance, Un-Known
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
          ->where([['item_accounts.status_id','=',1]])
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
            $rows->currency_code='BDT';
            $rows->exch_rate=1;
            return $rows;
          });
          echo json_encode($rows);
        }

        if($invrcv->receive_against_id==7)
        {// Regular, Loan
            $rows=$this->podyechem
            ->join('po_dye_chem_items', function($join){
            $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
            })
            ->join('inv_pur_req_items', function($join){
            $join->on('inv_pur_req_items.id', '=', 'po_dye_chem_items.inv_pur_req_item_id');
            })
            ->join('inv_pur_reqs', function($join){
            $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
            })
            ->join('currencies', function($join){
            $join->on('currencies.id', '=', 'po_dye_chems.currency_id');
            })
            ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
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
			     ->when(request('po_dye_chem_id'), function ($q) {
              return $q->where('po_dye_chems.id', '=',request('po_dye_chem_id', 0));
            })
            ->when(request('po_no'), function ($q) {
              return $q->where('po_dye_chems.po_no', '=',request('po_no', 0));
            })
            ->when(request('pi_no'), function ($q) {
              return $q->where('po_dye_chems.pi_no', '=',request('pi_no', 0));
            })
			      ->when(request('inv_pur_req_id'), function ($q) {
              return $q->where('inv_pur_reqs.id', '=',request('inv_pur_req_id', 0));
            })
            ->when(request('requisition_no'), function ($q) {
            return $q->where('inv_pur_reqs.requisition_no', '=',request('requisition_no', 0));
            })
            ->when(request('item_description'), function ($q) {
            return $q->where('item_accounts.item_description', 'LIKE',"%".request('item_description', 0)."%");
            })
            ->where([['po_dye_chems.company_id','=',$invrcv->company_id]])
            ->whereIn('itemcategories.identity',[7,8])
            ->whereNotNull('po_dye_chems.approved_at')
            ->selectRaw(
            '
            po_dye_chems.po_no,
            po_dye_chems.pi_no,
            po_dye_chems.exch_rate,
            po_dye_chem_items.id,
            po_dye_chem_items.id as po_dye_chem_item_id,
            po_dye_chem_items.qty as qty,
            po_dye_chem_items.rate as rate,
            po_dye_chem_items.amount as amount,
            inv_pur_reqs.requisition_no as rq_no,
            itemcategories.name as category_name,
            itemclasses.name as class_name,
            item_accounts.id as item_account_id,
            item_accounts.sub_class_name,
            item_accounts.item_description,
            item_accounts.specification,
            uoms.code as uom_name,
            currencies.code as currency_code
            ')
            ->get()
            ->map(function ($rows) {
            //$rows->exch_rate=1;
            return $rows;
            });
            echo json_encode($rows);
        }

        if($invrcv->receive_against_id==109)
        {// Regular, Loan
            $rows=$this->invpurreq
            ->join('inv_pur_req_items', function($join){
            $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
            })
            ->join('currencies', function($join){
            $join->on('currencies.id', '=', 'inv_pur_reqs.currency_id');
            })
            ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
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
            ->when(request('inv_pur_req_id'), function ($q) {
              return $q->where('inv_pur_reqs.id', '=',request('inv_pur_req_id', 0));
            })
            ->when(request('requisition_no'), function ($q) {
            return $q->where('inv_pur_reqs.requisition_no', '=',request('requisition_no', 0));
            })
            ->when(request('item_description'), function ($q) {
            return $q->where('item_accounts.item_description', 'LIKE',"%".request('item_description', 0)."%");
            })

            ->where([['inv_pur_reqs.company_id','=',$invrcv->company_id]])
            ->whereIn('itemcategories.identity',[7,8])
            ->whereIn('inv_pur_reqs.pay_mode',[5,6,1])
            ->whereNotNull('inv_pur_reqs.final_approved_at')
            ->selectRaw(
            '
            inv_pur_req_items.id,
            inv_pur_req_items.id as inv_pur_req_item_id,
            inv_pur_req_items.qty as qty,
            inv_pur_req_items.rate as rate,
            inv_pur_req_items.amount as amount,
            inv_pur_reqs.requisition_no as rq_no,
            itemcategories.name as category_name,
            itemclasses.name as class_name,
            item_accounts.id as item_account_id,
            item_accounts.sub_class_name,
            item_accounts.item_description,
            item_accounts.specification,
            uoms.code as uom_name,
            currencies.code as currency_code
            ')
            ->get()
            ->map(function ($rows) {
            $rows->exch_rate=1;
            return $rows;
            });
            echo json_encode($rows);
        }
    }
}