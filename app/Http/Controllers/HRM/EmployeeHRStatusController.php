<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeHRStatusRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeHRStatusRequest;
use GuzzleHttp\Client;

class EmployeeHRStatusController extends Controller {

    private $employeehr;
    private $employeehrstatus;
    private $designation;
    private $department;
    private $division;
    private $section;
    private $subsection;
    private $company;
    private $location;

    public function __construct(
        EmployeeHRRepository $employeehr,
        EmployeeHRStatusRepository $employeehrstatus,
        DesignationRepository $designation,
        DepartmentRepository $department,
        DivisionRepository $division,
        SectionRepository $section,
        SubsectionRepository $subsection,
        CompanyRepository $company,
        LocationRepository $location
    ) {
        $this->employeehr = $employeehr;
        $this->employeehrstatus = $employeehrstatus;
        $this->designation = $designation;
        $this->division = $division;
        $this->section = $section;
        $this->subsection = $subsection;
        $this->company = $company;
        $this->location = $location;
        $this->department = $department;
        $this->middleware('auth');
        $this->middleware('permission:view.employeehrstatuses',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeehrstatuses', ['only' => ['store']]);
        $this->middleware('permission:edit.employeehrstatuses',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeehrstatuses', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $yesno = config('bprs.yesno');
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $hrinactivefor=array_prepend(array_only(config('bprs.hrinactivefor'), [1, 0]),'-Select-',''); 

        $rows=$this->employeehrstatus
        ->join('employee_h_rs',function($join){
        $join->on('employee_h_rs.id','=','employee_h_r_statuses.employee_h_r_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->join('designations',function($join){
        $join->on('designations.id','=','employee_h_rs.designation_id');
        })
        ->join('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->leftJoin('locations',function($join){
        $join->on('locations.id','=','employee_h_rs.location_id');
        })
        ->leftJoin('divisions',function($join){
        $join->on('divisions.id','=','employee_h_rs.division_id');
        })
        ->leftJoin('sections',function($join){
        $join->on('sections.id','=','employee_h_rs.section_id');
        })
        ->leftJoin('subsections',function($join){
        $join->on('subsections.id','=','employee_h_rs.subsection_id');
        })
        ->leftJoin('employee_h_rs as reportto',function($join){
        $join->on('reportto.id','=','employee_h_rs.report_to_id');
        })
        ->leftJoin('users as approvedby',function($join){
        $join->on('approvedby.id','=','employee_h_r_statuses.approved_by');
        })
        ->orderBy('employee_h_r_statuses.id','desc')
        ->get([
            'employee_h_r_statuses.id',
            'employee_h_r_statuses.status_id',
            'employee_h_r_statuses.status_date',
            'employee_h_r_statuses.api_status',
            'approvedby.name as approved_by',
            'employee_h_r_statuses.approved_at',
            'employee_h_rs.id as employee_h_r_id',
            'employee_h_rs.name as employee_name',
            'companies.name as company_name',
            'designations.name as designation_name',
            'departments.name as department_name',
            'employee_h_rs.date_of_birth',
            'employee_h_rs.gender_id',
            'employee_h_rs.date_of_join',
            'employee_h_rs.probation_days',
            'employee_h_rs.national_id',
            'employee_h_rs.salary as gross_salary',
            'employee_h_rs.contact as phone_no',
            'employee_h_rs.religion',
            'employee_h_rs.grade',
            'locations.name as location_name',
            'divisions.name as division_name',
            'sections.name as section_name',
            'subsections.name as subsection_name',
            'reportto.name as report_to_name',
            
        ])
        ->take(500)
        ->map(function($rows) use($status,$yesno){
            $rows->status=$status[$rows->status_id];
            $rows->status_date=date("d-M-Y",strtotime($rows->status_date));
            $rows->approved_at=date("d-M-Y H:i:s",strtotime($rows->approved_at));
            $rows->api_status=$rows->api_status?$yesno[$rows->api_status]:'No';
            return $rows;
        });
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $division=array_prepend(array_pluck($this->division->get(),'name','id'),'-Select-','');
        $section=array_prepend(array_pluck($this->section->get(),'name','id'),'-Select-','');
        $subsection=array_prepend(array_pluck($this->subsection->get(),'code','id'),'-Select-','');
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $hrinactivefor=array_prepend(config('bprs.hrinactivefor'),'-Select-',''); 
        return Template::loadView('HRM.EmployeeHRStatus', [
        'status'=>$status,
        'designation'=>$designation,
        'department'=>$department,
        'company'=>$company,
        'location'=>$location,
        'division'=>$division,
        'section'=>$section,
        'subsection'=>$subsection,
        'hrinactivefor'=>$hrinactivefor
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeHRStatusRequest $request) {
        $employeehr=$this->employeehr->find($request->employee_h_r_id);
        $request->request->add(['old_status_id' => $employeehr->status_id]);
        $request->request->add(['api_status' => 0]);

        if($request->status_id==$employeehr->status_id){
        return response()->json(array('success' => false,'id' =>  $employeehr->id,'message' => 'Select Correct Status'),200);
        }
        if($request->status_id==''){
        return response()->json(array('success' => false,'id' =>  $employeehr->id,'message' => 'Select Correct Status'),200);
        }

        $unapproved=$this->employeehrstatus
        ->where([['employee_h_r_id','=',$request->employee_h_r_id]])
        ->whereNull('approved_at')
        ->get();
        if($unapproved->first()){
          return response()->json(array('success' => false,'id' =>  $employeehr->id,'message' => 'Please Approved Current Status'),200);
        }

        $employeehrstatus=$this->employeehrstatus->create([
            'employee_h_r_id'=>$request->employee_h_r_id,
            'status_id'=>$request->status_id,
            'status_date'=>$request->status_date,
            'logistics_status_id'=>$request->logistics_status_id,
            'old_status_id'=>$request->old_status_id,
            'api_status'=>$request->api_status,
            'remarks'=>$request->remarks,
        ]);

        if($employeehrstatus){
            return response()->json(array('success' => true,'id' =>  $employeehrstatus->id,'message' => 'Save Successfully'),200);
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
       $employeehrstatus = $this->employeehrstatus->find($id);
	   $row ['fromData'] = $employeehrstatus;
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
    public function update(EmployeeHRStatusRequest $request, $id) {
       //return response()->json(array('success' => false,'message' => 'Update Not Possible'),200);
        $approved=$this->employeehrstatus->find($id);
        if($approved->approved_by){
            return response()->json(array('success' => false,'message' => 'This Is Approved So Update Not Possible'),200);
        }

        $employeehrstatus=$this->employeehrstatus->update($id,[
            'status_date'=>$request->status_date,
            'logistics_status_id'=>$request->logistics_status_id,
            'remarks'=>$request->remarks,
        ]);
		if($employeehrstatus){
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
        $approved=$this->employeehrstatus->find($id);
        if($approved->approved_by){
            return response()->json(array('success' => false,'message' => 'This Is Approved So Delete Not Possible'),200);
        }

        $employeehrstatus = $this->employeehrstatus->findOrFail($id);
        if($employeehrstatus->forceDelete()){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
    public function sendToApi(){
        //return response()->json(array('success' => false,'message' => 'Send Not Possible'),200);
        $employeehrstatus=$this->employeehrstatus->find(request('id',0));
        if(!$employeehrstatus->approved_by){
            return response()->json(array('success' => false,'message' => 'This Is Not Approved So Send Not Possible'),200);
        }

        $id=$employeehrstatus->id;
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $hrinactivefor=array_prepend(config('bprs.hrinactivefor'),'-Select-',''); 

        $emp=$this->employeehrstatus
        ->join('employee_h_rs',function($join){
        $join->on('employee_h_rs.id','=','employee_h_r_statuses.employee_h_r_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->where([['employee_h_r_statuses.id','=',$id]])
        ->get([
        'employee_h_rs.id as emp_id',
        'employee_h_rs.company_id',
        'employee_h_r_statuses.status_id',
        'employee_h_r_statuses.logistics_status_id',
        'employee_h_r_statuses.status_date as inactive_date',
        ])
        ->map(function($emp) use($status,$hrinactivefor){
            if($emp->status_id==1){
                $emp->emp_id=$emp->emp_id;
                $emp->company_id=$emp->company_id;
                $emp->employee_status='Active';
                $emp->inactive_date='';
                $emp->logistics_status_id='';
                $emp->logistics_status='';

            }else{
                $emp->emp_id=$emp->emp_id;
                $emp->company_id=$emp->company_id;
                $emp->employee_status='Inactive';
                $emp->inactive_date=$emp->inactive_date?date('Y-m-d',strtotime($emp->inactive_date)):'';
                $emp->logistics_status_id=$emp->logistics_status_id;
                $emp->logistics_status=$hrinactivefor[$emp->logistics_status_id];
            }

            return $emp;
        })
        ->first();
        $data = [
        	'emp_id'=>$emp->emp_id,
        	'company_id'=>$emp->company_id,
        	'employee_status'=>$emp->employee_status,
        	'inactive_date'=>$emp->inactive_date,
        	'logistics_status_id'=>$emp->logistics_status_id,
        	'logistics_status'=>$emp->logistics_status,
        ];
        //echo $data; die;
        //echo json_encode($data); die;

        
        try
        {
          $client = new Client();
          $response = $client->request('POST', 'http://192.168.32.10:8082/Token',
          [
            'form_params' => [
            'grant_type' => 'password',
            'username' => 'erpadmin',
            'password' => 'admin@erp',
          ]
          ]);
          //$code = $response->getStatusCode();
          $body=json_decode($response->getBody());
          $token=$body->access_token;
          $headers = [
            'Authorization' => 'Bearer ' . $token,        
            'Accept'        => 'application/json',
            "Content-Type"  => "application/x-www-form-urlencoded"
          ]; 
          //echo $token; die;
          $res=$client->post('http://192.168.32.10:8082/Api/Erp/EmployeeStatus', ['form_params' => $data, 'headers' => $headers]);
          //echo $res->getBody();
          $ApiStatus=json_decode($res->getBody());
          $this->employeehrstatus->update($id,[
          'api_status'=>$ApiStatus->Status,
          ]);
          $this->employeehr->update($emp->emp_id,[
          'api_status'=>$ApiStatus->Status,
          ]);
        }
        catch(\GuzzleHttp\Exception\RequestException $e)
        {
          //if($employeehrstatus){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Approved Successfully, But Remote Server Not Updated','data'=>$data),200);
          //}
          throw $e;
        }
    }

    public function getEmployeeHr(){
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-','');
        $employeehr=$this->employeehr
        ->join('companies',function($join){
        $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->join('designations',function($join){
        $join->on('designations.id','=','employee_h_rs.designation_id');
        })
        ->join('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->leftJoin('locations',function($join){
        $join->on('locations.id','=','employee_h_rs.location_id');
        })
        ->leftJoin('divisions',function($join){
        $join->on('divisions.id','=','employee_h_rs.division_id');
        })
        ->leftJoin('sections',function($join){
        $join->on('sections.id','=','employee_h_rs.section_id');
        })
        ->leftJoin('subsections',function($join){
        $join->on('subsections.id','=','employee_h_rs.subsection_id');
        })
        ->leftJoin('employee_h_rs as reportto',function($join){
        $join->on('reportto.id','=','employee_h_rs.report_to_id');
        })
        ->when(request('company_id'), function ($q) {
          return $q->where('employee_h_rs.company_id','=',request('company_id', 0));
        })
        ->when(request('designation_id'), function ($q) {
          return $q->where('employee_h_rs.designation_id','=',request('designation_id', 0));
        })   
        ->when(request('department_id'), function ($q) {
          return $q->where('employee_h_rs.department_id','=',request('department_id', 0));
        }) 
        //->where([['employee_h_rs.status_id','=',1]])
        ->get([
        'employee_h_rs.id',
        'employee_h_rs.name as employee_name',
        'companies.name as company_name',
        'designations.name as designation_name',
        'departments.name as department_name',
        'employee_h_rs.date_of_birth',
        'employee_h_rs.gender_id',
        'employee_h_rs.date_of_join',
        'employee_h_rs.probation_days',
        'employee_h_rs.national_id',
        'employee_h_rs.salary as gross_salary',
        'employee_h_rs.contact as phone_no',
        'employee_h_rs.religion',
        'employee_h_rs.grade',
        'locations.name as location_name',
        'divisions.name as division_name',
        'sections.name as section_name',
        'subsections.name as subsection_name',
        'reportto.name as report_to_name',
        'employee_h_rs.status_id',
        'employee_h_rs.inactive_date',
        ])
        ->map(function($employeehr) use($status){
            $employeehr->status_name=$employeehr->status_id?$status[$employeehr->status_id]:'';
            return $employeehr;
        });
        echo json_encode($employeehr);
    }

    public function getAllEmployeeStatus(){
      $yesno = config('bprs.yesno');
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $hrinactivefor=array_prepend(array_only(config('bprs.hrinactivefor'), [1, 0]),'-Select-',''); 

        $rows=$this->employeehrstatus
        ->join('employee_h_rs',function($join){
        $join->on('employee_h_rs.id','=','employee_h_r_statuses.employee_h_r_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->join('designations',function($join){
        $join->on('designations.id','=','employee_h_rs.designation_id');
        })
        ->join('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->leftJoin('locations',function($join){
        $join->on('locations.id','=','employee_h_rs.location_id');
        })
        ->leftJoin('divisions',function($join){
        $join->on('divisions.id','=','employee_h_rs.division_id');
        })
        ->leftJoin('sections',function($join){
        $join->on('sections.id','=','employee_h_rs.section_id');
        })
        ->leftJoin('subsections',function($join){
        $join->on('subsections.id','=','employee_h_rs.subsection_id');
        })
        ->leftJoin('employee_h_rs as reportto',function($join){
        $join->on('reportto.id','=','employee_h_rs.report_to_id');
        })
        ->leftJoin('users as approvedby',function($join){
        $join->on('approvedby.id','=','employee_h_r_statuses.approved_by');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('employee_h_r_statuses.status_date','>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('employee_h_r_statuses.status_date','<=',request('date_to', 0));
        }) 
        ->orderBy('employee_h_r_statuses.id','desc')
        ->get([
            'employee_h_r_statuses.id',
            'employee_h_r_statuses.status_id',
            'employee_h_r_statuses.status_date',
            'employee_h_r_statuses.api_status',
            'approvedby.name as approved_by',
            'employee_h_r_statuses.approved_at',
            'employee_h_rs.id as employee_h_r_id',
            'employee_h_rs.name as employee_name',
            'companies.name as company_name',
            'designations.name as designation_name',
            'departments.name as department_name',
            'employee_h_rs.date_of_birth',
            'employee_h_rs.gender_id',
            'employee_h_rs.date_of_join',
            'employee_h_rs.probation_days',
            'employee_h_rs.national_id',
            'employee_h_rs.salary as gross_salary',
            'employee_h_rs.contact as phone_no',
            'employee_h_rs.religion',
            'employee_h_rs.grade',
            'locations.name as location_name',
            'divisions.name as division_name',
            'sections.name as section_name',
            'subsections.name as subsection_name',
            'reportto.name as report_to_name',
            
        ])
        ->map(function($rows) use($status,$yesno){
            $rows->status=$status[$rows->status_id];
            $rows->status_date=date("d-M-Y",strtotime($rows->status_date));
            $rows->approved_at=date("d-M-Y H:i:s",strtotime($rows->approved_at));
            $rows->api_status=$rows->api_status?$yesno[$rows->api_status]:'No';
            return $rows;
        });
        echo json_encode($rows);
    }

}