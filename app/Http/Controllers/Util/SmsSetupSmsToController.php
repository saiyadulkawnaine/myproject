<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\SmsSetupSmsToRepository;
use App\Library\Template;
use App\Http\Requests\SmsSetupSmsToRequest;

class SmsSetupSmsToController extends Controller
{
    private $smssetupsmsto;
    private $employeehr;
    private $designation;
    private $department;
    private $company;

  public function __construct(
        SmsSetupSmsToRepository $smssetupsmsto,
        EmployeeHRRepository $employeehr,
        DesignationRepository $designation,
        DepartmentRepository $department,
        CompanyRepository $company

    )

    {
        $this->smssetupsmsto = $smssetupsmsto;
        $this->employeehr = $employeehr;
        $this->designation = $designation;
        $this->department = $department;
        $this->company = $company;


        $this->middleware('auth');
        // $this->middleware('permission:view.smssetupsmstos',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.smssetupsmstos', ['only' => ['store']]);
        // $this->middleware('permission:edit.smssetupsmstos',   ['only' => ['update']]);
        // $this->middleware('permission:delete.smssetupsmstos', ['only' => ['destroy']]);
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
       $smssetupsmstos = array();
       $rows=$this->smssetupsmsto
        ->Join('employee_h_rs',function($join){
          $join->on('employee_h_rs.id','=','sms_setup_sms_tos.employee_h_r_id');
        })
        ->where([['sms_setup_id','=',request('sms_setup_id',0)]])
        ->get([
            'sms_setup_sms_tos.*',
            'employee_h_rs.name as employee_name'
        ]);

        foreach($rows as $row){
           $smssetupsmsto['id']=$row->id;
           $smssetupsmsto['employee_name']=$row->employee_name;
            array_push($smssetupsmstos,$smssetupsmsto);
        }
        echo json_encode($smssetupsmstos);
     }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
        public function store(SmsSetupSmsToRequest $request) {
        $smssetupsmsto=$this->smssetupsmsto->create([
            'sms_setup_id'=>$request->sms_setup_id,
            'employee_h_r_id'=>$request->employee_h_r_id,


        ]);

        if($smssetupsmsto){
            return response()->json(array('success' => true,'id' =>  $smssetupsmsto->id,'message' => 'Save Successfully'),200);
        }
    }


    public function show($id)
    {
        //
    }

   
        public function edit($id) {
        $smssetupsmsto=$this->smssetupsmsto
        ->Join('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','sms_setup_sms_tos.employee_h_r_id');
        })
        ->where([['sms_setup_sms_tos.id','=',$id]])
        ->get([
            'sms_setup_sms_tos.*',
            'employee_h_rs.name',
        ])
        ->first();

        $row ['fromData'] = $smssetupsmsto;
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
       public function update(SmsSetupSmsToRequest $request, $id) {
        $smssetupsmsto=$this->smssetupsmsto->update($id,[
            'sms_setup_id'=>$request->sms_setup_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
        ]);
        if($smssetupsmsto){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->smssetupsmsto->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }


    public function getEmployee(){
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $employeehrs=array();
        $rows=$this->employeehr
        ->where([['employee_h_rs.status_id','=',1]])
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
        ->get([
         'employee_h_rs.*'
        ]);
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