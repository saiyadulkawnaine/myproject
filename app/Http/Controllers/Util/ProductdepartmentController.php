<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ProductdepartmentRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\ProductdepartmentRequest;

class ProductdepartmentController extends Controller {

    private $productdepartment;
    private $buyer;

    public function __construct(ProductdepartmentRepository $productdepartment,BuyerRepository $buyer) {
        $this->productdepartment = $productdepartment;
        $this->buyer = $buyer;

        $this->middleware('auth');
        $this->middleware('permission:view.productdepartments',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.productdepartments', ['only' => ['store']]);
        $this->middleware('permission:edit.productdepartments',   ['only' => ['update']]);
        $this->middleware('permission:delete.productdepartments', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $deptcategory=array_prepend(config('bprs.deptcategory'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $productdepartments=array();
      $rows=$this->productdepartment->get();
      foreach ($rows as $row) {
        $productdepartment['id']=$row->id;
        $productdepartment['from_age']=$row->from_age;
        $productdepartment['to_age']=$row->to_age;
        $productdepartment['departmentname']=$row->department_name;
        $productdepartment['deptcategory']=$deptcategory[$row->dept_category_id];
        $productdepartment['buyer']=$buyer[$row->buyer_id];
        array_push($productdepartments,$productdepartment);
      }
        echo json_encode($productdepartments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $deptcategory=array_prepend(config('bprs.deptcategory'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.Productdepartment",['deptcategory'=>$deptcategory,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductdepartmentRequest $request) {
        $productdepartment = $this->productdepartment->create($request->except(['id']));
        if ($productdepartment) {
            return response()->json(array('success' => true, 'id' => $productdepartment->id, 'message' => 'Save Successfully'), 200);
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
        $productdepartment = $this->productdepartment->find($id);
        $row ['fromData'] = $productdepartment;
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
    public function update(ProductdepartmentRequest $request, $id) {
        $productdepartment = $this->productdepartment->update($id, $request->except(['id']));
        if ($productdepartment) {
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
        if ($this->productdepartment->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
