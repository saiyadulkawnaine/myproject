<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccChartDivisionRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccChartDivisionRequest;

class AccChartDivisionController extends Controller {

    private $ctrlHead;
    private $accchartdivision;
    private $division;


    public function __construct(AccChartCtrlHeadRepository $ctrlHead,AccChartDivisionRepository $accchartdivision,DivisionRepository $division) {
        $this->ctrlHead = $ctrlHead;
        $this->accchartdivision = $accchartdivision;
        $this->division = $division;
        $this->middleware('auth');
        $this->middleware('permission:view.accchartdivisions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.accchartdivisions', ['only' => ['store']]);
        $this->middleware('permission:edit.accchartdivisions',   ['only' => ['update']]);
        $this->middleware('permission:delete.accchartdivisions', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $ctrlHead=array_prepend(array_pluck($this->ctrlHead->get(),'name','id'),'-Select-','');
        $accchartdivisions=array();
        $rows=$this->accchartdivision->get();
        foreach ($rows as $row) {
          $accchartdivision['id']=$row->id;
          $accchartdivision['name']=$row->name;
          $accchartdivision['code']=$row->code;
          $accchartdivision['ctrlHead']=$ctrlHead[$row->acc_chart_ctrl_head_id];
          array_push($accchartdivisions,$accchartdivision);
        }
        echo json_encode($accchartdivisions);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $division=$this->division
		->leftJoin('acc_chart_divisions', function($join)  {
			$join->on('acc_chart_divisions.division_id', '=', 'divisions.id');
			$join->where('acc_chart_divisions.acc_chart_ctrl_head_id', '=', request('acc_chart_ctrl_head_id',0));
			$join->whereNull('acc_chart_divisions.deleted_at');
		})
		->get([
		'divisions.id',
		'divisions.name',
		'acc_chart_divisions.id as acc_chart_division_id'
		]);
		$saved = $division->filter(function ($value) {
			if($value->acc_chart_division_id){
				return $value;
			}
		})->values();
		
		$new = $division->filter(function ($value) {
			if(!$value->acc_chart_division_id){
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
    public function store(AccChartDivisionRequest $request) {
		foreach($request->division_id as $index=>$val){
				$accchartdivision = $this->accchartdivision->updateOrCreate(
				['acc_chart_ctrl_head_id' => $request->acc_chart_ctrl_head_id, 'division_id' => $request->division_id[$index]]);
		}
        if ($accchartdivision) {
            return response()->json(array('success' => true, 'id' => $accchartdivision->id, 'message' => 'Save Successfully'), 200);
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
       $accchartdivision = $this->accchartdivision->find($id);
	   $row ['fromData'] = $accchartdivision;
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
    public function update(AccChartDivisionRequest $request, $id) {
        $accchartdivision=$this->accchartdivision->update($id,$request->except(['id']));
		if($accchartdivision){
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
        if($this->accchartdivision->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
