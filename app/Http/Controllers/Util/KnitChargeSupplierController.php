<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\KnitChargeSupplierRepository;
use App\Repositories\Contracts\Util\KnitChargeRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\KnitChargeSupplierRequest;

class KnitChargeSupplierController extends Controller {

    private $knitchargesupplier;
    private $knitcharge;
    private $supplier;

    public function __construct(KnitChargeSupplierRepository $knitchargesupplier,KnitChargeRepository $knitcharge,SupplierRepository $supplier) {
        $this->knitchargesupplier = $knitchargesupplier;
        $this->knitcharge = $knitcharge;
        $this->supplier = $supplier;

        $this->middleware('auth');
        $this->middleware('permission:view.knitchargesuppliers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.knitchargesuppliers', ['only' => ['store']]);
        $this->middleware('permission:edit.knitchargesuppliers',   ['only' => ['update']]);
        $this->middleware('permission:delete.knitchargesuppliers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $knitchargesuppliers=array();
      $rows=$this->knitchargesupplier->get();
      foreach ($rows as $row) {
        $knitchargesupplier['id']=$row->id;
        $knitchargesupplier['rate']=$row->rate;
        $knitchargesupplier['supplier']=$supplier[$row->supplier_id];
        array_push($knitchargesuppliers,$knitchargesupplier);
      }
        echo json_encode($knitchargesuppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $knitcharge=array_prepend(array_pluck($this->knitcharge-get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.KnitChargeSupplier",['knitcharge'=>$knitcharge,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KnitChargeSupplierRequest $request) {
        $knitchargesupplier = $this->knitchargesupplier->create($request->except(['id']));
        if ($knitchargesupplier) {
            return response()->json(array('success' => true, 'id' => $knitchargesupplier->id, 'message' => 'Save Successfully'), 200);
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
        $knitchargesupplier = $this->knitchargesupplier->find($id);
        $row ['fromData'] = $knitchargesupplier;
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
    public function update(KnitChargeSupplierRequest $request, $id) {
        $knitchargesupplier = $this->knitchargesupplier->update($id, $request->except(['id']));
        if ($knitchargesupplier) {
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
        if ($this->knitchargesupplier->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
