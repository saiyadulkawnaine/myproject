<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SupplierWashChargeRepository;
use App\Repositories\Contracts\Util\WashChargeRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\SupplierWashChargeRequest;

class SupplierWashChargeController extends Controller {

    private $supplierwashcharge;
    private $washcharge;
    private $supplier;

    public function __construct(SupplierWashChargeRepository $supplierwashcharge,WashChargeRepository $washcharge,SupplierRepository $supplier) {
        $this->supplierwashcharge = $supplierwashcharge;
        $this->washcharge = $washcharge;
        $this->supplier = $supplier;
        $this->middleware('auth');
        $this->middleware('permission:view.supplierwashcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.supplierwashcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.supplierwashcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.supplierwashcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $supplierwashcharges=array();
      $rows=$this->supplierwashcharge->get();
      foreach ($rows as $row) {
        $supplierwashcharge['id']=$row->id;
        $supplierwashcharge['supplier']=$supplier[$row->supplier_id];
        $supplierwashcharge['rate']=$row->rate;
        array_push($supplierwashcharges,$supplierwashcharge);
      }
        echo json_encode($supplierwashcharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $washcharge=array_prepend(array_pluck($this->washcharge-get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.SupplierWashCharge",['washcharge'=>$washcharge,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierWashChargeRequest $request) {
        $supplierwashcharge = $this->supplierwashcharge->create($request->except(['id']));
        if ($supplierwashcharge) {
            return response()->json(array('success' => true, 'id' => $supplierwashcharge->id, 'message' => 'Save Successfully'), 200);
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
        $supplierwashcharge = $this->supplierwashcharge->find($id);
        $row ['fromData'] = $supplierwashcharge;
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
    public function update(SupplierWashChargeRequest $request, $id) {
        $supplierwashcharge = $this->supplierwashcharge->update($id, $request->except(['id']));
        if ($supplierwashcharge) {
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
        if ($this->supplierwashcharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
