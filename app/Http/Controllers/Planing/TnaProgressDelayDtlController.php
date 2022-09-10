<?php

namespace App\Http\Controllers\Planing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Planing\TnaProgressDelayRepository;
use App\Repositories\Contracts\Planing\TnaProgressDelayDtlRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Library\Template;
use App\Http\Requests\Planing\TnaProgressDelayDtlRequest;

class TnaProgressDelayDtlController extends Controller {

    private $tnaprogressdelay;
    private $tnaprogressdelaydtl;
    private $designation;
    private $department;
    private $company;
    private $location;

    public function __construct(
        TnaProgressDelayRepository $tnaprogressdelay,
        TnaProgressDelayDtlRepository $tnaprogressdelaydtl,
        DesignationRepository $designation,
        DepartmentRepository $department,
        CompanyRepository $company,
        LocationRepository $location,
        EmployeeHRRepository $employeehr
    ) {
        $this->tnaprogressdelay = $tnaprogressdelay;
        $this->tnaprogressdelaydtl = $tnaprogressdelaydtl;
        $this->designation = $designation;
        $this->department = $department;
        $this->company = $company;
        $this->location = $location;
        $this->employeehr = $employeehr;

        $this->middleware('auth');
        /* $this->middleware('permission:view.tnaprogressdelaydtls',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.tnaprogressdelaydtls', ['only' => ['store']]);
        $this->middleware('permission:edit.tnaprogressdelaydtls',   ['only' => ['update']]);
        $this->middleware('permission:delete.tnaprogressdelaydtls', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tnaprogressdelaydtls=array();
        $rows=$this->tnaprogressdelaydtl
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','tna_progress_delay_dtls.employee_h_r_id');
        })
        ->where([['tna_progress_delay_id','=',request('tna_progress_delay_id',0)]])
        ->orderBy('tna_progress_delay_dtls.id','desc')
        ->get([
            'tna_progress_delay_dtls.*',
            'employee_h_rs.name'
        ]);
        foreach($rows as $row){
            $tnaprogressdelaydtl['id']=$row->id;
            $tnaprogressdelaydtl['cause_of_delay']=$row->cause_of_delay;
            $tnaprogressdelaydtl['impact']=$row->impact;
            $tnaprogressdelaydtl['action_taken']=$row->action_taken;
            $tnaprogressdelaydtl['name']=$row->name;

            array_push($tnaprogressdelaydtls, $tnaprogressdelaydtl);
        }
        echo json_encode($tnaprogressdelaydtls);

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
    public function store(TnaProgressDelayDtlRequest $request) {
        $tnaprogressdelaydtl=$this->tnaprogressdelaydtl->create([
            'tna_progress_delay_id'=>$request->tna_progress_delay_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
            'cause_of_delay'=>$request->cause_of_delay,
            'impact'=>$request->impact,
            'action_taken'=>$request->action_taken,
            
            
            ]);
        if($tnaprogressdelaydtl){
            return response()->json(array('success'=>true,'id'=>$tnaprogressdelaydtl->id,'message'=>'Save Successfully'),200);
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
       $tnaprogressdelaydtl=$this->tnaprogressdelaydtl
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','tna_progress_delay_dtls.employee_h_r_id');
        })
        ->where([['tna_progress_delay_dtls.id','=',$id]])
        ->get([
           'tna_progress_delay_dtls.*',
           'employee_h_rs.name',
       ])
       ->first();
       $row['fromData']=$tnaprogressdelaydtl;
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
    

    public function update(TnaProgressDelayDtlRequest $request, $id) {
        $tnaprogressdelaydtl=$this->tnaprogressdelaydtl->update($id,
        [
            'tna_progress_delay_id'=>$request->tna_progress_delay_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
            'cause_of_delay'=>$request->cause_of_delay,
            'impact'=>$request->impact,
            'action_taken'=>$request->action_taken,
        ]);
        if($tnaprogressdelaydtl){
            return response()->json(array('success'=>true,'id'=>$id,'message'=>'Update Successfully'),200);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->tnaprogressdelaydtl->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}       
    }

    public function getEmployeeHr(){
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $yesno = config('bprs.yesno');
        $employeehrs=array();
        $rows=$this->employeehr
        ->orderBy('employee_h_rs.id','desc')
        ->when(request('designation_id'), function ($q) {
            return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
        })
        ->when(request('department_id'), function ($q) {
            return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
        })
        ->when(request('company_id'), function ($q) {
            return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
        })
        ->get(['employee_h_rs.*']);
        foreach($rows as $row){
           $employeehr['id']=$row->id; 
           $employeehr['name']=$row->name; 
           $employeehr['code']=$row->code;
           $employeehr['company_id']=$company[$row->company_id];
           $employeehr['designation_id']=isset($designation[$row->designation_id])?$designation[$row->designation_id]:''; 
           $employeehr['department_id']=isset($department[$row->department_id])?$department[$row->department_id]:''; 
           $employeehr['date_of_join']=($row->date_of_join !== null)?date("Y-m-d",strtotime($row->date_of_join)):null; 
           $employeehr['national_id']=$row->national_id; 
           $employeehr['last_education']=$row->last_education;
           $employeehr['experience']=$row->experience;
		   $employeehr['email']=$row->email;
		   $employeehr['contact']=$row->contact;
           array_push($employeehrs,$employeehr);
        }
        echo json_encode($employeehrs);
    }

}
