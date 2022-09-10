<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeePromotionRepository;
use App\Repositories\Contracts\HRM\EmployeeJobHistoryRepository;
use App\Repositories\Contracts\HRM\EmployeeHRJobRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeePromotionJobRequest;

class EmployeePromotionJobController extends Controller {

    private $employeejobhistory;
    private $employeepromotion;
    private $employeehrjob;

    public function __construct(
        EmployeePromotionRepository $employeepromotion,
        EmployeeJobHistoryRepository $employeejobhistory,
        EmployeeHRJobRepository $employeehrjob) {
        $this->employeepromotion = $employeepromotion;
        $this->employeejobhistory = $employeejobhistory;
        $this->employeehrjob = $employeehrjob;

        $this->middleware('auth');
        $this->middleware('permission:view.employeepromotionjobs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeepromotionjobs', ['only' => ['store']]);
        $this->middleware('permission:edit.employeepromotionjobs',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeepromotionjobs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $rows=$this->employeehrjob
        ->where([['employee_h_r_jobs.employee_h_r_id','=',request('employee_h_r_id',0)]])
        ->orderBy('employee_h_r_jobs.id','asc')
        ->get([
            'employee_h_r_jobs.*',
        ]);

        echo json_encode($rows);
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
    public function store(EmployeePromotionJobRequest $request) {
        $employeehrjob = $this->employeehrjob->create([
        'employee_h_r_id'=>$request->employee_h_r_id,
        'job_description'=>$request->job_description,
        'sort_id'=>$request->sort_id,
        ]);

        if($employeehrjob){
        return response()->json(array('success' => true,'id' =>  $employeehrjob->id,'employee_h_r_id'=>$request->employee_h_r_id,'message' => 'Save Successfully'),200);
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
    public function update(EmployeePromotionJobRequest $request, $id) {
        $employeehrjob = $this->employeehrjob->update($id,[
        'job_description'=>$request->job_description,
        'sort_id'=>$request->sort_id,
        ]);

        if($employeehrjob){
        return response()->json(array('success' =>true ,'id'=>$id,'employee_h_r_id'=>$request->employee_h_r_id,'message'=>'Update Successfully'),200);
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
