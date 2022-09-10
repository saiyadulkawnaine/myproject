<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ItemAccountSupplierRateRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\ItemAccountSupplierRateRequest;
use App\Repositories\Contracts\Util\ItemAccountSupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;



class ItemAccountSupplierRateController extends Controller {

  private $itemsupplierrate;
  private $itemaccount;
  private $supplier;
  private $itemaccountsupplier;
  private $currency;


    public function __construct(ItemAccountSupplierRateRepository $itemsupplierrate,ItemAccountRepository $itemaccount,SupplierRepository $supplier,ItemAccountSupplierRepository $itemaccountsupplier,CurrencyRepository $currency
    ) {
      $this->itemsupplierrate = $itemsupplierrate;
      $this->itemaccount = $itemaccount;
      $this->supplier = $supplier;
      $this->itemaccountsupplier = $itemaccountsupplier;
      $this->currency = $currency;

      $this->middleware('auth');

    //   $this->middleware('permission:view.itemsupplierrates',   ['only' => ['create', 'index','show']]);
    //   $this->middleware('permission:create.itemsupplierrates', ['only' => ['store']]);
    //   $this->middleware('permission:edit.itemsupplierrates',   ['only' => ['update']]);
    //   $this->middleware('permission:delete.itemsupplierrates', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $itemsupplierrates=array();
        $rows=$this->itemsupplierrate
        ->leftJoin('item_account_suppliers',function($join){
            $join->on('item_account_suppliers.id','=','item_account_supplier_rates.item_account_supplier_id');
        })
        ->leftJoin('item_accounts',function($join){
            $join->on('item_account_suppliers.item_account_id','=','item_accounts.id');
        })
        /* ->leftJoin('suppliers',function($join){
            $join->on('item_account_supplier_rates.supplier_id','=','suppliers.id');
        }) */
        ->where([['item_account_supplier_rates.item_account_supplier_id','=',request('item_account_supplier_id',0)]])
        ->orderBy('item_account_supplier_rates.id','desc')
        ->get([
            'item_accounts.item_description',
            'item_accounts.specification',
            'item_account_suppliers.custom_name',
            'item_account_suppliers.supplier_id',
            'item_account_supplier_rates.*'
        ]);
  		foreach($rows as $row){
        $itemsupplierrate['id']=$row->id;
        $itemsupplierrate['supplier_id']=$supplier[$row->supplier_id];
        $itemsupplierrate['date_from']=date('d-M-Y',strtotime($row->date_from));
        $itemsupplierrate['date_to']=date('d-M-Y',strtotime($row->date_to));
        $itemsupplierrate['custom_name']=$row->custom_name;
        $itemsupplierrate['dom_rate']=$row->dom_rate;
  		   array_push($itemsupplierrates,$itemsupplierrate);
  		}
        echo json_encode($itemsupplierrates);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        
        return Template::loadView("Util.ItemAccountSupplierRate",['currency'=>$currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemAccountSupplierRateRequest $request) {
          $prerate=$this->itemsupplierrate
          ->when(request('date_from'), function ($q) {
          return $q->where('item_account_supplier_rates.date_from', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
          return $q->where('item_account_supplier_rates.date_to', '<=',request('date_to', 0));
          })
          ->where([['item_account_supplier_rates.item_account_supplier_id','=',$request->item_account_supplier_id]])
         // ->where([['item_account_supplier_rates.supplier_id','=',$request->supplier_id]])
          ->get()->first();
          if($prerate){
              return response()->json(array('success' => false,  'message' => 'Duplicate Data found'), 200);
          }

          $this->itemsupplierrate
          ->where([['item_account_supplier_rates.item_account_supplier_id','=',$request->item_account_supplier_id]])
          //->where([['item_account_supplier_rates.supplier_id','=',$request->supplier_id]])
          ->delete();

        $itemsupplierrate = $this->itemsupplierrate->create([
            'item_account_supplier_id'=>$request->item_account_supplier_id,
            'date_from'=>$request->date_from,
            'date_to'=>$request->date_to,
            'dom_rate'=>$request->dom_rate,
            'foreign_rate'=>$request->foreign_rate,
            'dom_currency_id'=>$request->dom_currency_id,
            'foreign_currency_id'=>$request->foreign_currency_id,
            'exch_rate'=>$request->exch_rate,
            'remarks'=>$request->remarks
        ]);
        
        if ($itemsupplierrate) {
            return response()->json(array('success' => true, 'id' => $itemsupplierrate->id, 'message' => 'Save Successfully'), 200);
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
        $itemsupplierrate = $this->itemsupplierrate
        ->leftJoin('item_account_suppliers',function($join){
            $join->on('item_account_suppliers.id','=','item_account_supplier_rates.item_account_supplier_id');
        })
        ->leftJoin('item_accounts',function($join){
            $join->on('item_account_suppliers.item_account_id','=','item_accounts.id');
        })
/*         ->leftJoin('suppliers',function($join){
            $join->on('item_account_supplier_rates.supplier_id','=','suppliers.id');
        }) */
        ->where([['item_account_supplier_rates.id','=',$id]])
        ->get([
            'item_accounts.item_description',
            'item_accounts.specification',
            'item_account_suppliers.custom_name',
            'item_account_suppliers.supplier_id',
            'item_account_supplier_rates.*',
        ])
        ->first();
        $row ['fromData'] = $itemsupplierrate;
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
    public function update(ItemAccountSupplierRateRequest $request, $id) {
      $prerate=$this->itemsupplierrate
          ->when(request('date_from'), function ($q) {
          return $q->where('item_account_supplier_rates.date_from', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
          return $q->where('item_account_supplier_rates.date_to', '<=',request('date_to', 0));
          })
          ->where([['item_account_supplier_rates.item_account_supplier_id','=',$request->item_account_supplier_id]])
          //->where([['item_account_supplier_rates.supplier_id','=',$request->supplier_id]])
          ->where([['item_account_supplier_rates.id','!=',$id]])
          ->get()->first();
          if($prerate){
              return response()->json(array('success' => false,  'message' => 'Duplicate Data found'), 200);
          }
        $itemsupplierrate = $this->itemsupplierrate->update($id, [
            'item_account_supplier_id'=>$request->item_account_supplier_id,
            'date_from'=>$request->date_from,
            'date_to'=>$request->date_to,
            'dom_rate'=>$request->dom_rate,
            'foreign_rate'=>$request->foreign_rate,
            'dom_currency_id'=>$request->dom_currency_id,
            'foreign_currency_id'=>$request->foreign_currency_id,
            'exch_rate'=>$request->exch_rate,
            'remarks'=>$request->remarks
        ]);
        if ($itemsupplierrate) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->itemsupplierrate->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    // public function getItemSupplier(){
    //     $rows = $this->itemaccount
    //     ->leftJoin('item_account_suppliers',function($join){
    //         $join->on('item_account_suppliers.item_account_id','=','item_accounts.id');
    //     })
    //     ->leftJoin('suppliers',function($join){
    //         $join->on('item_account_suppliers.supplier_id','=','suppliers.id');
    //     })
    //     ->where([['item_account_suppliers.item_account_id','=',request('item_account_id',0)]])
    //     ->get([
    //         'item_accounts.id as item_account_id',
    //         'suppliers.id as supplier_id', 
    //         'suppliers.name'
    //     ]);
    //     return $rows;
    // }

    // public function getCustomName(){
    //     $itemsupplier=$this->itemaccountsupplier
    //     ->leftJoin('suppliers',function($join){
    //         $join->on('item_account_suppliers.supplier_id','=','suppliers.id');
    //     })
    //     /* ->leftJoin('item_account_supplier_rates',function($join){
    //         $join->on('item_account_suppliers.supplier_id','=','item_account_supplier_rates.supplier_id');
    //     }) */
    //     ->where([['item_account_suppliers.supplier_id','=',request('supplier_id',0)]])
    //     ->get([
    //         //'item_account_suppliers.supplier_id',
    //         'item_account_suppliers.custom_name'
    //     ]);
    //     return $itemsupplier;
    // }

}