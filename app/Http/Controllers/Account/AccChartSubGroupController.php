<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccChartSubGroupRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccChartSubGroupRequest;

class AccChartSubGroupController extends Controller {

    private $accchartsubgroup;
    private $ctrlHead;

    public function __construct(AccChartSubGroupRepository $accchartsubgroup, AccChartCtrlHeadRepository $ctrlHead) {
        $this->accchartsubgroup = $accchartsubgroup;
        $this->ctrlHead = $ctrlHead;

        $this->middleware('auth');
        $this->middleware('permission:view.accchartsubgroups',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.accchartsubgroups', ['only' => ['store']]);
        $this->middleware('permission:edit.accchartsubgroups',   ['only' => ['update']]);
        $this->middleware('permission:delete.accchartsubgroups', ['only' => ['destroy']]);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$accchartsubgroups=array();
		$accchartgroup=config('bprs.accchartgroup');
			$rows=$this->accchartsubgroup->orderBy('id','desc')->get();
			foreach ($rows as $row) {
				$accchartsubgroup['id']=$row->id;
				$accchartsubgroup['name']=$row->name;
				$accchartsubgroup['acc_chart_group_id']=$accchartgroup[$row->acc_chart_group_id];
				$accchartsubgroup['sort_id']=$row->sort_id;
				

				array_push($accchartsubgroups,$accchartsubgroup);
			}
        echo json_encode($accchartsubgroups);
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $accchartgroup=config('bprs.accchartgroup');
        
		return Template::loadView('Account.AccChartSubGroup', ['accchartgroup'=>$accchartgroup]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccChartSubGroupRequest $request) {
		$accchartsubgroup=$this->accchartsubgroup->create($request->except(['id']));
		if($accchartsubgroup){
			return response()->json(array('success' => true,'id' =>  $accchartsubgroup->id,'message' => 'Save Successfully'),200);
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
		
		$accchartsubgroup = $this->accchartsubgroup->find($id);
	   $row ['fromData'] = $accchartsubgroup;
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
    public function update(AccChartSubGroupRequest $request, $id) {
		
		$accchartsubgroup=$this->accchartsubgroup->update($id,$request->except(['id']));
		if($accchartsubgroup){
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
		if($this->accchartsubgroup->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
        else{
             return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
        }
        
    }

}
