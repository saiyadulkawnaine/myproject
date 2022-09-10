<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccChartDepartmentRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccChartDepartmentRequest;

class AccChartDepartmentController extends Controller {

    private $ctrlHead;
    private $accchartdepartment;
    private $department;
    //private $ctrlHead;

    public function __construct(AccChartCtrlHeadRepository $ctrlHead,AccChartDepartmentRepository $accchartdepartment,DepartmentRepository $department) {
        $this->ctrlHead = $ctrlHead;
        $this->accchartdepartment = $accchartdepartment;
        $this->department = $department;


        $this->middleware('auth');
        $this->middleware('permission:view.accchartdepartments',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.accchartdepartments', ['only' => ['store']]);
        $this->middleware('permission:edit.accchartdepartments',   ['only' => ['update']]);
        $this->middleware('permission:delete.accchartdepartments', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $ctrlHead=array_prepend(array_pluck($this->ctrlHead->get(),'name','id'),'-Select-','');
        $accchartdepartments=array();
        $rows=$this->accchartdepartment->get();
        foreach ($rows as $row) {
          $accchartdepartment['id']=$row->id;
          $accchartdepartment['name']=$row->name;
          $accchartdepartment['code']=$row->code;
          $accchartdepartment['ctrlHead']=$ctrlHead[$row->acc_chart_ctrl_head_id];
          array_push($accchartdepartments,$accchartdepartment);
        }
        echo json_encode($accchartdepartments);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $department=$this->department
		->leftJoin('acc_chart_departments', function($join)  {
			$join->on('acc_chart_departments.department_id', '=', 'departments.id');
			$join->where('acc_chart_departments.acc_chart_ctrl_head_id', '=', request('acc_chart_ctrl_head_id',0));
			$join->whereNull('acc_chart_departments.deleted_at');
		})
		->get([
		'departments.id',
		'departments.name',
		'acc_chart_departments.id as acc_chart_department_id'
		]);
		$saved = $department->filter(function ($value) {
			if($value->acc_chart_department_id){
				return $value;
			}
		})->values();
		
		$new = $department->filter(function ($value) {
			if(!$value->acc_chart_department_id){
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
    public function store(AccChartDepartmentRequest $request) {
		foreach($request->department_id as $index=>$val){
				$accchartdepartment = $this->accchartdepartment->updateOrCreate(
				['acc_chart_ctrl_head_id' => $request->acc_chart_ctrl_head_id, 'department_id' => $request->department_id[$index]]);
		}
        if ($accchartdepartment) {
            return response()->json(array('success' => true, 'id' => $accchartdepartment->id, 'message' => 'Save Successfully'), 200);
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
       $accchartdepartment = $this->accchartdepartment->find($id);
	   $row ['fromData'] = $accchartdepartment;
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
    public function update(AccChartDepartmentRequest $request, $id) {
        $accchartdepartment=$this->accchartdepartment->update($id,$request->except(['id']));
		if($accchartdepartment){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->accchartdepartment->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
