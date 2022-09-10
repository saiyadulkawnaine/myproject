<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ItemAccountSupplierRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CountryRepository;


use App\Library\Template;
use App\Http\Requests\ItemAccountSupplierRequest;



class ItemAccountSupplierController extends Controller {

  private $itemaccountsupplier;
  private $itemaccount;
  private $supplier;
  private $country;

    public function __construct(ItemAccountSupplierRepository $itemaccountsupplier,ItemAccountRepository $itemaccount,SupplierRepository $supplier,CountryRepository $country) {
      $this->itemaccountsupplier = $itemaccountsupplier;
      $this->itemaccount = $itemaccount;
      $this->supplier = $supplier;
      $this->country = $country;

      $this->middleware('auth');

    //   $this->middleware('permission:view.itemaccountsuppliers',   ['only' => ['create', 'index','show']]);
    //   $this->middleware('permission:create.itemaccountsuppliers', ['only' => ['store']]);
    //   $this->middleware('permission:edit.itemaccountsuppliers',   ['only' => ['update']]);
    //   $this->middleware('permission:delete.itemaccountsuppliers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $country=array_prepend(array_pluck($this->country->get(),'code','id'),'-Select-','');
      $itemaccountsuppliers=array();
        $rows=$this->itemaccountsupplier
        ->where([['item_account_id','=',request('item_account_id',0)]])
        ->orderBy('item_account_suppliers.id','desc')
        ->get();
  		foreach($rows as $row){
        $itemaccountsupplier['id']=$row->id;
        $itemaccountsupplier['supplier_id']=$supplier[$row->supplier_id];
        $itemaccountsupplier['custom_name']=$row->custom_name;
        $itemaccountsupplier['country_id']=$country[$row->country_id];
        $itemaccountsupplier['supplier_point_id']=$country[$row->supplier_point_id];
        $itemaccountsupplier['prod_dosage']=$row->prod_dosage;
  		   array_push($itemaccountsuppliers,$itemaccountsupplier);
  		}
        echo json_encode($itemaccountsuppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.ItemAccountSupplier", ['supplier'=>$supplier,'country'=>$country]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemAccountSupplierRequest $request) {
        $itemaccountsupplier = $this->itemaccountsupplier->create([
            'item_account_id'=>$request->item_account_id,
            'supplier_id'=>$request->supplier_id,
            'custom_name'=>$request->custom_name,
            'country_id'=>$request->country_id,
            'supplier_point_id'=>$request->supplier_point_id,
            'prod_dosage'=>$request->prod_dosage,
            'hs_code'=>$request->hs_code,
            'remarks'=>$request->remarks
        ]);
        if ($itemaccountsupplier) {
            return response()->json(array('success' => true, 'id' => $itemaccountsupplier->id, 'message' => 'Save Successfully'), 200);
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
        $itemaccountsupplier = $this->itemaccountsupplier
        ->leftJoin('item_accounts',function($join){
            $join->on('item_account_suppliers.item_account_id','=','item_accounts.id');
        })
        ->where([['item_account_suppliers.id','=',$id]])
        ->get([
            'item_accounts.item_description',
            'item_accounts.specification',
            'item_account_suppliers.*',
        ])
        ->first();
        $row ['fromData'] = $itemaccountsupplier;
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
    public function update(ItemAccountSupplierRequest $request, $id) {
        $itemaccountsupplier = $this->itemaccountsupplier->update($id, [
            'item_account_id'=>$request->item_account_id,
            'supplier_id'=>$request->supplier_id,
            'custom_name'=>$request->custom_name,
            'country_id'=>$request->country_id,
            'supplier_point_id'=>$request->supplier_point_id,
            'prod_dosage'=>$request->prod_dosage,
            'hs_code'=>$request->hs_code,
            'remarks'=>$request->remarks
        ]);
        if ($itemaccountsupplier) {
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
        if ($this->itemaccountsupplier->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getItemSpecDesc(){
        $rows = $this->itemaccount
        ->selectRaw(
            'item_accounts.id as item_account_id,
            item_accounts.item_description,
            item_accounts.specification
        ')
        ->leftJoin('item_account_suppliers',function($join){
            $join->on('item_account_suppliers.item_account_id','=','item_accounts.id');
        })
        ->groupBy([
            'item_accounts.id',
            'item_accounts.item_description',
            'item_accounts.specification'
        ])
        ->get()
        ->map(function($rows){
            return $rows;
        })
        ->first();
        echo json_encode($rows);
    }

}