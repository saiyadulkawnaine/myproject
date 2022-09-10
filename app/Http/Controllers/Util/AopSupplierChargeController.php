<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\AopSupplierChargeRepository;
use App\Repositories\Contracts\Util\AopChargeRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\AopSupplierChargeRequest;

class AopSupplierChargeController extends Controller {

    private $aopsuppliercharge;
    private $aopcharge;
    private $supplier;

    public function __construct(AopSupplierChargeRepository $aopsuppliercharge,AopChargeRepository $aopcharge,SupplierRepository $supplier) {
        $this->aopsuppliercharge = $aopsuppliercharge;
        $this->aopcharge = $aopcharge;
        $this->supplier = $supplier;

        $this->middleware('auth');
        $this->middleware('permission:view.aopsuppliercharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.aopsuppliercharges', ['only' => ['store']]);
        $this->middleware('permission:edit.aopsuppliercharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.aopsuppliercharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $aopsuppliercharges=array();
      $rows=$this->aopsuppliercharge->get();
      foreach ($rows as $row) {
        $aopsuppliercharge['id']=$row->id;
        $aopsuppliercharge['rate']=$row->rate;
        $aopsuppliercharge['supplier']=$supplier[$row->supplier_id];
        array_push($aopsuppliercharges,$aopsuppliercharge);
      }
        echo json_encode($aopsuppliercharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $aopcharge=array_prepend(array_pluck($this->aopcharge-get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.AopChargeSupplier",['aopcharge'=>$aopcharge,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AopSupplierChargeRequest $request) {
        $aopsuppliercharge = $this->aopsuppliercharge->create($request->except(['id']));
        if ($aopsuppliercharge) {
            return response()->json(array('success' => true, 'id' => $aopsuppliercharge->id, 'message' => 'Save Successfully'), 200);
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
        $aopsuppliercharge = $this->aopsuppliercharge->find($id);
        $row ['fromData'] = $aopsuppliercharge;
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
    public function update(AopSupplierChargeRequest $request, $id) {
        $aopsuppliercharge = $this->aopsuppliercharge->update($id, $request->except(['id']));
        if ($aopsuppliercharge) {
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
        if ($this->aopsuppliercharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
