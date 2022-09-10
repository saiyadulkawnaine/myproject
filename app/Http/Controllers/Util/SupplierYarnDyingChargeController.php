<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SupplierYarnDyingChargeRepository;
use App\Repositories\Contracts\Util\YarnDyingChargeRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\SupplierYarnDyingChargeRequest;

class SupplierYarnDyingChargeController extends Controller {

    private $supplieryarndyingcharge;
    private $yarndyingcharge;
    private $supplier;

    public function __construct(SupplierYarnDyingChargeRepository $supplieryarndyingcharge,YarnDyingChargeRepository $yarndyingcharge,SupplierRepository $supplier) {
        $this->supplieryarndyingcharge = $supplieryarndyingcharge;
        $this->yarndyingcharge = $yarndyingcharge;
        $this->supplier = $supplier;
        $this->middleware('auth');
        $this->middleware('permission:view.supplieryarndyingcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.supplieryarndyingcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.supplieryarndyingcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.supplieryarndyingcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $supplieryarndyingcharges=array();
      $rows=$this->supplieryarndyingcharge->get();
      foreach ($rows as $row) {
        $supplieryarndyingcharge['id']=$row->id;
        $supplieryarndyingcharge['rate']=$row->rate;
        $supplieryarndyingcharge['supplier']=$supplier[$row->supplier_id];
        array_push($supplieryarndyingcharges,$supplieryarndyingcharge);
      }
        echo json_encode($supplieryarndyingcharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $yarndyingcharge=array_prepend(array_pluck($this->yarndyingcharge-get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.SupplierYarnDyingCharge",['yarndyingcharge'=>$yarndyingcharge,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierYarnDyingChargeRequest $request) {
        $supplieryarndyingcharge = $this->supplieryarndyingcharge->create($request->except(['id']));
        if ($supplieryarndyingcharge) {
            return response()->json(array('success' => true, 'id' => $supplieryarndyingcharge->id, 'message' => 'Save Successfully'), 200);
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
        $supplieryarndyingcharge = $this->supplieryarndyingcharge->find($id);
        $row ['fromData'] = $supplieryarndyingcharge;
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
    public function update(SupplierYarnDyingChargeRequest $request, $id) {
        $supplieryarndyingcharge = $this->supplieryarndyingcharge->update($id, $request->except(['id']));
        if ($supplieryarndyingcharge) {
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
        if ($this->supplieryarndyingcharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
