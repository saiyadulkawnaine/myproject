<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ItemAccountSupplierFeatRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\ItemAccountSupplierFeatRequest;
use App\Repositories\Contracts\Util\ItemAccountSupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;




class ItemAccountSupplierFeatController extends Controller {

  private $supplierfeature;
  private $itemaccount;
  private $supplier;
  private $itemaccountsupplier;
  private $currency;
  


    public function __construct(ItemAccountSupplierFeatRepository $supplierfeature,ItemAccountRepository $itemaccount,SupplierRepository $supplier,ItemAccountSupplierRepository $itemaccountsupplier,CurrencyRepository $currency
    ) {
      $this->supplierfeature = $supplierfeature;
      $this->itemaccount = $itemaccount;
      $this->supplier = $supplier;
      $this->itemaccountsupplier = $itemaccountsupplier;
      $this->currency = $currency;
      

      $this->middleware('auth');

    //   $this->middleware('permission:view.supplierfeatures',   ['only' => ['create', 'index','show']]);
    //   $this->middleware('permission:create.supplierfeatures', ['only' => ['store']]);
    //   $this->middleware('permission:edit.supplierfeatures',   ['only' => ['update']]);
    //   $this->middleware('permission:delete.supplierfeatures', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
      $feature=array_prepend(config('bprs.supplyfeature'),'-Select-','');
      $supplierfeatures=array();
        $rows=$this->supplierfeature
        ->leftJoin('item_account_suppliers',function($join){
            $join->on('item_account_suppliers.id','=','item_account_supplier_feats.item_account_supplier_id');
        })
        ->leftJoin('item_accounts',function($join){
            $join->on('item_account_suppliers.item_account_id','=','item_accounts.id');
        })
        ->where([['item_account_supplier_feats.item_account_supplier_id','=',request('item_account_supplier_id',0)]])
        ->orderBy('item_account_supplier_feats.id','desc')
        ->get([
            'item_accounts.item_description',
            'item_accounts.specification',
            'item_account_suppliers.custom_name',
            'item_account_suppliers.supplier_id',
            'item_account_supplier_feats.*'
        ]);
  		foreach($rows as $row){
        $supplierfeature['id']=$row->id;
        $supplierfeature['supplier_id']=$supplier[$row->supplier_id];
        $supplierfeature['feature_point_id']=$feature[$row->feature_point_id];
        $supplierfeature['available_id']=$yesno[$row->available_id];
        $supplierfeature['mandatory_id']=$yesno[$row->mandatory_id];
        $supplierfeature['values']=$row->values;
  		   array_push($supplierfeatures,$supplierfeature);
  		}
        echo json_encode($supplierfeatures);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $feature=array_prepend(config('bprs.supplyfeature'),'-Select-','');
        

        return Template::loadView("Util.ItemAccountSupplierFeat",['feature'=>$feature,'yesno'=>$yesno]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemAccountSupplierFeatRequest $request) {
        $supplierfeature = $this->supplierfeature->create([
            'item_account_supplier_id'=>$request->item_account_supplier_id,
            'feature_point_id'=>$request->feature_point_id,
            'available_id'=>$request->available_id,
            'mandatory_id'=>$request->mandatory_id,
            'values'=>$request->values,
            'remarks'=>$request->remarks
        ]);
        if ($supplierfeature) {
            return response()->json(array('success' => true, 'id' => $supplierfeature->id, 'message' => 'Save Successfully'), 200);
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
        $supplierfeature = $this->supplierfeature
        ->leftJoin('item_account_suppliers',function($join){
            $join->on('item_account_suppliers.id','=','item_account_supplier_feats.item_account_supplier_id');
        })
        ->leftJoin('item_accounts',function($join){
            $join->on('item_account_suppliers.item_account_id','=','item_accounts.id');
        })
/*         ->leftJoin('suppliers',function($join){
            $join->on('item_account_supplier_feats.supplier_id','=','suppliers.id');
        }) */
        ->where([['item_account_supplier_feats.id','=',$id]])
        ->get([
            'item_accounts.item_description',
            'item_accounts.specification',
            'item_account_suppliers.custom_name',
            'item_account_suppliers.supplier_id',
            'item_account_supplier_feats.*',
        ])
        ->first();
        $row ['fromData'] = $supplierfeature;
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
    public function update(ItemAccountSupplierFeatRequest $request, $id) {
        $supplierfeature = $this->supplierfeature->update($id, [
            'item_account_supplier_id'=>$request->item_account_supplier_id,
            'feature_point_id'=>$request->feature_point_id,
            'available_id'=>$request->available_id,
            'mandatory_id'=>$request->mandatory_id,
            'values'=>$request->values,
            'remarks'=>$request->remarks
        ]);
        if ($supplierfeature) {
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
        if ($this->supplierfeature->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}