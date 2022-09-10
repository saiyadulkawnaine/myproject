<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanySupplierRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\CompanySupplierRequest;

class CompanySupplierController extends Controller {

    private $companysupplier;
	private $company;
    private $supplier;

    public function __construct(CompanySupplierRepository $companysupplier, SupplierRepository $supplier,CompanyRepository $company) {
        $this->companysupplier = $companysupplier;
		$this->company = $company;
        $this->supplier = $supplier;
        $this->middleware('auth');
        $this->middleware('permission:view.companysuppliers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.companysuppliers', ['only' => ['store']]);
        $this->middleware('permission:edit.companysuppliers',   ['only' => ['update']]);
        $this->middleware('permission:delete.companysuppliers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $companysuppliers=array();
        $rows=$this->companysupplier->get();
        foreach ($rows as $row) {
          $companysupplier['id']=$row->id;
          $companysupplier['name']=$row->name;
          $companysupplier['code']=$row->code;
          $companysupplier['supplier']=$supplier[$row->supplier_id];
          array_push($companysuppliers,$companysupplier);
        }
        echo json_encode($companysuppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$company=$this->company
		->leftJoin('company_suppliers', function($join)  {
			$join->on('company_suppliers.company_id', '=', 'companies.id');
			$join->where('company_suppliers.supplier_id', '=', request('supplier_id',0));
			$join->whereNull('company_suppliers.deleted_at');
		})
		->get([
		'companies.id',
		'companies.name',
		'company_suppliers.id as company_supplier_id'
		]);
		$saved = $company->filter(function ($value) {
			if($value->company_supplier_id){
				return $value;
			}
		})->values();
		
		$new = $company->filter(function ($value) {
			if(!$value->company_supplier_id){
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
    public function store(CompanySupplierRequest $request) {
		foreach($request->company_id as $index=>$val){
				$companysupplier = $this->companysupplier->updateOrCreate(
				['supplier_id' => $request->supplier_id, 'company_id' => $request->company_id[$index]]);
		}
        if ($companysupplier) {
            return response()->json(array('success' => true, 'id' => $companysupplier->id, 'message' => 'Save Successfully'), 200);
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
        $companysupplier = $this->companysupplier->find($id);
        $row ['fromData'] = $companysupplier;
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
    public function update(CompanySupplierRequest $request, $id) {
        $companysupplier = $this->companysupplier->update($id, $request->except(['id']));
        if ($companysupplier) {
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
        if ($this->companysupplier->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
