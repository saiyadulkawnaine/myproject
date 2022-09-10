<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccChartLocationRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccChartLocationRequest;

class AccChartLocationController extends Controller {

    private $accchartctrlhead;
    private $accchartlocation;
    private $location;
    //private $ctrlHead;

    public function __construct(AccChartCtrlHeadRepository $ctrlHead,AccChartLocationRepository $accchartlocation,LocationRepository $location) {
        $this->ctrlHead = $ctrlHead;
        $this->accchartlocation = $accchartlocation;
        $this->location = $location;


        $this->middleware('auth');
        $this->middleware('permission:view.accchartlocations',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.accchartlocations', ['only' => ['store']]);
        $this->middleware('permission:edit.accchartlocations',   ['only' => ['update']]);
        $this->middleware('permission:delete.accchartlocations', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $ctrlHead=array_prepend(array_pluck($this->ctrlHead->get(),'name','id'),'-Select-','');
        $accchartlocations=array();
        $rows=$this->accchartlocation->get();
        foreach ($rows as $row) {
          $accchartlocation['id']=$row->id;
          $accchartlocation['name']=$row->name;
          $accchartlocation['code']=$row->code;
          $accchartlocation['ctrlHead']=$ctrlHead[$row->acc_chart_ctrl_head_id];
          array_push($accchartlocations,$accchartlocation);
        }
        echo json_encode($accchartlocations);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $location=$this->location
		->leftJoin('acc_chart_locations', function($join)  {
			$join->on('acc_chart_locations.location_id', '=', 'locations.id');
			$join->where('acc_chart_locations.acc_chart_ctrl_head_id', '=', request('acc_chart_ctrl_head_id',0));
			$join->whereNull('acc_chart_locations.deleted_at');
		})
		->get([
		'locations.id',
		'locations.name',
		'acc_chart_locations.id as acc_chart_location_id'
		]);
		$saved = $location->filter(function ($value) {
			if($value->acc_chart_location_id){
				return $value;
			}
		})->values();
		
		$new = $location->filter(function ($value) {
			if(!$value->acc_chart_location_id){
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
    public function store(AccChartLocationRequest $request) {
		foreach($request->location_id as $index=>$val){
				$accchartlocation = $this->accchartlocation->updateOrCreate(
				['acc_chart_ctrl_head_id' => $request->acc_chart_ctrl_head_id, 'location_id' => $request->location_id[$index]]);
		}
        if ($accchartlocation) {
            return response()->json(array('success' => true, 'id' => $accchartlocation->id, 'message' => 'Save Successfully'), 200);
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
        $accchartlocation = $this->accchartlocation->find($id);
	   $row ['fromData'] = $accchartlocation;
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
    public function update(AccChartLocationRequest $request, $id) {
        $accchartlocation=$this->accchartlocation->update($id,$request->except(['id']));
		if($accchartlocation){
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
        if($this->accchartlocation->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
