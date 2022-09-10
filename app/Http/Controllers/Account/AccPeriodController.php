<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccPeriodRepository;
use App\Repositories\Contracts\Account\AccYearRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccPeriodRequest;

class AccPeriodController extends Controller {

    private $accperiod;
	private $accyear;

    public function __construct(AccPeriodRepository $accperiod,AccYearRepository $accyear) {
        $this->accperiod = $accperiod;
		$this->accyear = $accyear;

        $this->middleware('auth');
        $this->middleware('permission:view.accperiods',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.accperiods', ['only' => ['store']]);
        $this->middleware('permission:edit.accperiods',   ['only' => ['update']]);
        $this->middleware('permission:delete.accperiods', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $yesno = config('bprs.yesno');
		$accperiods = array();
		$rows = $this->accperiod->get();
		foreach($rows as $row){
			$accperiod['id']=$row->id;
			$accperiod['acc_year_id']=$row->acc_year_id;
			$accperiod['period']=$row->period;
			$accperiod['is_open']=$yesno[$row->is_open];
			$accperiod['start_date']=date('Y-m-d',strtotime($row->start_date));
			$accperiod['end_date']=date('Y-m-d',strtotime($row->end_date));
			$accperiod['name']=$row->name;
			array_push($accperiods,$accperiod);
			}
			echo json_encode($accperiods);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccPeriodRequest $request) {
		$accperiod=$this->accperiod->create($request->except(['id']));
		if($accperiod){
			return response()->json(array('success' => true,'id' =>  $accperiod->id,'message' => 'Save Successfully'),200);
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
       $accperiod = $this->accperiod->find($id);
	   $row ['fromData'] = $accperiod;
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
    public function update(AccPeriodRequest $request, $id) {
        $accperiod=$this->accperiod->update($id,$request->except(['id']));
		if($accperiod){
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
       if($this->accperiod->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		} 
        else{
             return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
        } 
    }

}
