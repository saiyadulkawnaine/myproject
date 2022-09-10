<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeHRJobRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeHRJobRequest;

class EmployeeHRJobController extends Controller {

    private $employeehrjob;

    public function __construct(EmployeeHRJobRepository $employeehrjob) {
        $this->employeehrjob = $employeehrjob;

        $this->middleware('auth');
        // $this->middleware('permission:view.employeehrjobs',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.employeehrjobs', ['only' => ['store']]);
        // $this->middleware('permission:edit.employeehrjobs',   ['only' => ['update']]);
        // $this->middleware('permission:delete.employeehrjobs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $employeehrjobs=array();
        $rows=$this->employeehrjob
        ->where([['employee_h_r_id','=',request('employee_h_r_id',0)]])
        ->orderBy('employee_h_r_jobs.sort_id','asc')
        ->get();
        foreach($rows as $row){
           $employeehrjob['id']=$row->id; 
           $employeehrjob['job_description']=$row->job_description; 
           $employeehrjob['sort_id']=$row->sort_id; 

           array_push($employeehrjobs,$employeehrjob);
        }
        echo json_encode($employeehrjobs);
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
    public function store(EmployeeHRJobRequest $request) {
		$employeehrjob=$this->employeehrjob->create($request->except(['id']));
		if($employeehrjob){
			return response()->json(array('success' => true,'id' =>  $employeehrjob->id,'message' => 'Save Successfully'),200);
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
       $employeehrjob = $this->employeehrjob->find($id);
	   $row ['fromData'] = $employeehrjob;
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
    public function update(EmployeeHRJobRequest $request, $id) {
       $employeehrjob=$this->employeehrjob->update($id,$request->except(['id']));
		if($employeehrjob){
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
        if($this->employeehrjob->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
