<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeRecruitReqJobRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeRecruitReqJobRequest;

class EmployeeRecruitReqJobController extends Controller {

    private $employeerecruitreqjob;

    public function __construct(EmployeeRecruitReqJobRepository $employeerecruitreqjob) {
        $this->employeerecruitreqjob = $employeerecruitreqjob;

        $this->middleware('auth');
        $this->middleware('permission:view.employeerecruitreqjobs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeerecruitreqjobs', ['only' => ['store']]);
        $this->middleware('permission:edit.employeerecruitreqjobs',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeerecruitreqjobs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $employeerecruitreqjobs=array();
        $rows=$this->employeerecruitreqjob
        ->where([['employee_recruit_req_id','=',request('employee_recruit_req_id',0)]])
        ->orderBy('employee_recruit_req_jobs.sort_id','asc')
        ->get();
        foreach($rows as $row){
           $employeerecruitreqjob['id']=$row->id; 
           $employeerecruitreqjob['job_description']=$row->job_description; 
           $employeerecruitreqjob['sort_id']=$row->sort_id; 

           array_push($employeerecruitreqjobs,$employeerecruitreqjob);
        }
        echo json_encode($employeerecruitreqjobs);
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
    public function store(EmployeeRecruitReqJobRequest $request) {
		$employeerecruitreqjob=$this->employeerecruitreqjob->create($request->except(['id']));
		if($employeerecruitreqjob){
			return response()->json(array('success' => true,'id' =>  $employeerecruitreqjob->id,'message' => 'Save Successfully'),200);
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
       $employeerecruitreqjob = $this->employeerecruitreqjob->find($id);
	   $row ['fromData'] = $employeerecruitreqjob;
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
    public function update(EmployeeRecruitReqJobRequest $request, $id) {
       $employeerecruitreqjob=$this->employeerecruitreqjob->update($id,$request->except(['id']));
		if($employeerecruitreqjob){
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
        if($this->employeerecruitreqjob->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}