<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\DyingChargeSupplierRepository;
use App\Repositories\Contracts\Util\DyingChargeRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\DyingChargeSupplierRequest;

class DyingChargeSupplierController extends Controller {

    private $dyingchargesupplier;
    private $dyingcharge;
    private $supplier;

    public function __construct(DyingChargeSupplierRepository $dyingchargesupplier,DyingChargeRepository $dyingcharge,SupplierRepository $supplier) {
        $this->dyingchargesupplier = $dyingchargesupplier;
        $this->dyingcharge = $dyingcharge;
        $this->supplier = $supplier;

        $this->middleware('auth');
        $this->middleware('permission:view.dyingchargesuppliers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.dyingchargesuppliers', ['only' => ['store']]);
        $this->middleware('permission:edit.dyingchargesuppliers',   ['only' => ['update']]);
        $this->middleware('permission:delete.dyingchargesuppliers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $dyingchargesuppliers=array();
      $rows=$this->dyingchargesupplier->get();
      foreach ($rows as $row) {
        $dyingchargesupplier['id']=$row->id;
        $dyingchargesupplier['rate']=$row->rate;
        $dyingchargesupplier['supplier']=$supplier[$row->supplier_id];
        array_push($dyingchargesuppliers,$dyingchargesupplier);
      }
        echo json_encode($dyingchargesuppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $dyingcharge=array_prepend(array_pluck($this->dyingcharge-get(),'name','id'),'-Select-',0);
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-',0);
        return Template::loadView("Util.DyingChargeSupplier",['dyingcharge'=>$dyingcharge,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DyingChargeSupplierRequest $request) {
        $dyingchargesupplier = $this->dyingchargesupplier->create($request->except(['id']));
        if ($dyingchargesupplier) {
            return response()->json(array('success' => true, 'id' => $dyingchargesupplier->id, 'message' => 'Save Successfully'), 200);
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
        $dyingchargesupplier = $this->dyingchargesupplier->find($id);
        $row ['fromData'] = $dyingchargesupplier;
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
    public function update(DyingChargeSupplierRequest $request, $id) {
        $dyingchargesupplier = $this->dyingchargesupplier->update($id, $request->except(['id']));
        if ($dyingchargesupplier) {
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
        if ($this->dyingchargesupplier->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
