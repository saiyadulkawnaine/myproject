<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeHRLeaveRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeHRLeaveRequest;

class EmployeeHRLeaveController extends Controller {

    private $employeehrleave;

    public function __construct(EmployeeHRLeaveRepository $employeehrleave) {
        $this->employeehrleave = $employeehrleave;

        $this->middleware('auth');
        // $this->middleware('permission:view.employeehrleaves',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.employeehrleaves', ['only' => ['store']]);
        // $this->middleware('permission:edit.employeehrleaves',   ['only' => ['update']]);
        // $this->middleware('permission:delete.employeehrleaves', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $employeehrleaves=array();
        $rows=$this->employeehrleave
        ->where([['employee_h_r_id','=',request('employee_h_r_id',0)]])
        ->orderBy('employee_h_r_leaves.sort_id','asc')
        ->get();
        foreach($rows as $row){
           $employeehrleave['id']=$row->id; 
           $employeehrleave['leave_description']=$row->leave_description; 
           $employeehrleave['sort_id']=$row->sort_id; 

           array_push($employeehrleaves,$employeehrleave);
        }
        echo json_encode($employeehrleaves);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeHRLeaveRequest $request) {
		$employeehrleave=$this->employeehrleave->create($request->except(['id']));
		if($employeehrleave){
			return response()->json(array('success' => true,'id' =>  $employeehrleave->id,'message' => 'Save Successfully'),200);
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
       $employeehrleave = $this->employeehrleave->find($id);
	   $row ['fromData'] = $employeehrleave;
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
    public function update(EmployeeHRLeaveRequest $request, $id) {
       $employeehrleave=$this->employeehrleave->update($id,$request->except(['id']));
		if($employeehrleave){
			return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
		} 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->employeehrleave->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}