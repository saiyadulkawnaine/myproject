<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SupplierNatureRepository;
use App\Repositories\Contracts\Util\ContactNatureRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\SupplierNatureRequest;

class SupplierNatureController extends Controller {

    private $suppliernature;
	private $contactnature;
    private $supplier;

    public function __construct(SupplierNatureRepository $suppliernature, SupplierRepository $supplier,ContactNatureRepository $contactnature) {
        $this->suppliernature = $suppliernature;
		$this->contactnature = $contactnature;
        $this->supplier = $supplier;
        $this->middleware('auth');
        $this->middleware('permission:view.suppliernatures',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.suppliernatures', ['only' => ['store']]);
        $this->middleware('permission:edit.suppliernatures',   ['only' => ['update']]);
        $this->middleware('permission:delete.suppliernatures', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $suppliernatures=array();
        $rows=$this->suppliernature->get();
        foreach ($rows as $row) {
          $suppliernature['id']=$row->id;
          $suppliernature['name']=$row->name;
          $suppliernature['code']=$row->code;
          $suppliernature['supplier']=$supplier[$row->supplier_id];
          array_push($suppliernatures,$suppliernature);
        }
        echo json_encode($suppliernatures);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$contactnature=$this->contactnature
		->leftJoin('supplier_natures', function($join)  {
			$join->on('supplier_natures.contact_nature_id', '=', 'contact_natures.id');
			$join->where('supplier_natures.supplier_id', '=', request('supplier_id',0));
			$join->whereNull('supplier_natures.deleted_at');
		})
        ->where([['contact_natures.nature_type','=',2]])
        ->orderBy('contact_natures.name','asc')
		->get([
		'contact_natures.id',
		'contact_natures.name',
		'supplier_natures.id as supplier_nature_id'
		]);
		$saved = $contactnature->filter(function ($value) {
			if($value->supplier_nature_id){
				return $value;
			}
		})->values();
		
		$new = $contactnature->filter(function ($value) {
			if(!$value->supplier_nature_id){
				return $value;
			}
		})->values();
		$row ['unsaved'] = $new;
		$row ['saved'] = $saved;
		echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierNatureRequest $request) {

		foreach($request->contact_nature_id as $index=>$val){
				$suppliernature = $this->suppliernature->updateOrCreate(
				['supplier_id' => $request->supplier_id, 'contact_nature_id' => $request->contact_nature_id[$index]]);
		}
        if ($suppliernature) {
            return response()->json(array('success' => true, 'id' => $suppliernature->id, 'message' => 'Save Successfully'), 200);
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
        $suppliernature = $this->suppliernature->find($id);
        $row ['fromData'] = $suppliernature;
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
    public function update(SupplierNatureRequest $request, $id) {
        $suppliernature = $this->suppliernature->update($id, $request->except(['id']));
        if ($suppliernature) {
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
        if ($this->suppliernature->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
