<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\SupplierSettingRepository;
use App\Library\Template;
use App\Http\Requests\Util\SupplierSettingRequest;

class SupplierSettingController extends Controller {

    private $supplier;
    private $suppliersetting;

    public function __construct(SupplierSettingRepository $suppliersetting,SupplierRepository $supplier) {
        $this->suppliersetting = $suppliersetting;
        $this->supplier = $supplier;

        $this->middleware('auth');
        // $this->middleware('permission:view.suppliersettings',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.suppliersettings', ['only' => ['store']]);
        // $this->middleware('permission:edit.suppliersettings',   ['only' => ['update']]);
        // $this->middleware('permission:delete.suppliersettings', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $suppliersettings=array();
        $rows=$this->suppliersetting->get();
        foreach ($rows as $row) {
          $suppliersetting['id']=$row->id;
          $suppliersetting['supplier_name']=$supplier[$row->supplier_id];
          $suppliersetting['payment_blocked_id']=$yesno[$row->payment_blocked_id];
          $suppliersetting['remarks']=$row->remarks;
          array_push($suppliersettings,$suppliersetting);
        }
        echo json_encode($suppliersettings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        return Template::loadView("Util.SupplierSetting",['supplier'=>$supplier,'yesno'=>$yesno]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierSettingRequest $request) {
        $suppliersetting = $this->suppliersetting->create($request->except(['id']));
        if ($suppliersetting) {
            return response()->json(array('success' => true, 'id' => $suppliersetting->id, 'message' => 'Save Successfully'), 200);
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
        $suppliersetting = $this->suppliersetting->find($id);
        $row ['fromData'] = $suppliersetting;
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
    public function update(SupplierSettingRequest $request, $id) {
        $suppliersetting = $this->suppliersetting->update($id, $request->except(['id']));
        if ($suppliersetting) {
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
        if ($this->suppliersetting->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
