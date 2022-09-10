<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeHRJobRepository;
use App\Repositories\Contracts\HRM\EmployeePromotionRepository;
use App\Repositories\Contracts\HRM\EmployeeJobHistoryRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeePromotionRequest;
use GuzzleHttp\Client;

class EmployeePromotionController extends Controller {

    private $employeehr;
    private $employeehrjob;
    private $employeepromotions;
    private $employeejobhistory;
    private $designation;
    private $department;
    private $division;
    private $section;
    private $subsection;
    private $location;
    private $user;

    public function __construct(
    	EmployeeHRRepository $employeehr,
    	EmployeeHRJobRepository $employeehrjob,
    	EmployeePromotionRepository $employeepromotions,
    	EmployeeJobHistoryRepository $employeejobhistory,
    	DesignationRepository $designation,
    	DepartmentRepository $department,
    	DivisionRepository $division,
      SectionRepository $section,
      SubsectionRepository $subsection,
    	CompanyRepository $company,
    	UserRepository $user,
    	LocationRepository $location
    ) {
        $this->employeehr = $employeehr;
        $this->employeehrjob = $employeehrjob;
        $this->employeepromotions = $employeepromotions;
        $this->employeejobhistory = $employeejobhistory;
        $this->designation = $designation;
        $this->department = $department;
        $this->division = $division;
        $this->section = $section;
        $this->subsection = $subsection;
        $this->company = $company;
        $this->location = $location;
        $this->user = $user;

        $this->middleware('auth');
        $this->middleware('permission:view.employeepromotions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeepromotions', ['only' => ['store']]);
        $this->middleware('permission:edit.employeepromotions',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeepromotions', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $rows=$this->employeepromotions
        ->join('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','employee_promotions.employee_h_r_id');
        })
        ->leftJoin('companies',function($join){
        $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->leftJoin('designations',function($join){
        $join->on('designations.id','=','employee_h_rs.designation_id');
        })
        ->leftJoin('locations',function($join){
        $join->on('locations.id','=','employee_h_rs.location_id');
        })
        ->leftJoin('divisions',function($join){
        $join->on('divisions.id','=','employee_h_rs.division_id');
        })
        ->leftJoin('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
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
        ->leftJoin('employee_h_rs as oldreportto',function($join){
        $join->on('oldreportto.id','=','employee_promotions.old_report_to_id');
        })
        ->leftJoin('designations as olddesignations',function($join){
        $join->on('olddesignations.id','=','employee_promotions.old_designation_id');
        })
      
        ->orderBy('employee_promotions.id','desc')
        ->get([
            'employee_promotions.*',
            'employee_h_rs.name as employee_name',
            'employee_h_rs.code',
            'designations.name as designation_name',

            'employee_h_rs.company_id',
            'employee_h_rs.location_id',
            'employee_h_rs.division_id',
            'employee_h_rs.department_id',
            'employee_h_rs.section_id',
            'employee_h_rs.subsection_id',
            'employee_h_rs.report_to_id',
            
            'companies.name as company_name',
            'departments.name as department_name',
            'divisions.name as division_name',
            'sections.name as section_name',
            'subsections.name as subsection_name',
            'locations.name as location_name',
            'reportto.name as report_to_name',
            'oldreportto.name as old_report_to_name',
            'olddesignations.name as old_designation_name',
            ]);
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
      return Template::loadView('HRM.EmployeePromotion', [
        'designation'=>$designation,
        'department'=>$department,
        'company'=>$company,
        'location'=>$location,
        'division'=>$division,
        'section'=>$section,
        'subsection'=>$subsection
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeePromotionRequest $request) {
      $employeehr=$this->employeehr->find($request->employee_h_r_id);
      $request->request->add(['old_designation_id' => $employeehr->designation_id]);
      $request->request->add(['old_grade' => $employeehr->grade]);
      $request->request->add(['old_report_to_id' => $employeehr->report_to_id]);
      $request->request->add(['api_status' => 0]);
      \DB::beginTransaction();
      try
      {
        $employeepromotions=$this->employeepromotions->create([
        'employee_h_r_id'=>$request->employee_h_r_id,
        'promotion_date'=>$request->promotion_date,

        'designation_id'=>$request->designation_id,
        'grade'=>$request->grade,
        'report_to_id'=>$request->report_to_id,

        'old_designation_id'=>$request->old_designation_id,
        'old_grade'=>$request->old_grade,
        'old_report_to_id'=>$request->old_report_to_id,

        'remarks'=>$request->remarks,
        'api_status'=>$request->api_status,
        ]);
        $this->employeehr->update($employeehr->id,[
        'designation_id'=>$request->designation_id,
        'grade'=>$request->grade,
        'report_to_id'=>$request->report_to_id,
        ]);

        $employeehrjob=$this->employeehrjob->where([['employee_h_r_id','=',$request->employee_h_r_id]])->orderBy('sort_id')->get();
        foreach($employeehrjob as $row){
        $this->employeejobhistory->create([
        'employee_h_r_job_id'=>$row->id,
        'employee_promotion_id'=>$employeepromotions->id,
        'job_description'=>$row->job_description,
        'sort_id'=>$row->sort_id
        ]);
        }
      }
      catch(EXCEPTION $e)
      {
        \DB::rollback();
        throw $e;
      }
      \DB::commit();

      $emp=$this->employeepromotions
      ->join('employee_h_rs',function($join){
      $join->on('employee_h_rs.id','=','employee_promotions.employee_h_r_id');
      })
      ->where([['employee_promotions.id','=',$employeepromotions->id]])
      ->get([
      'employee_promotions.employee_h_r_id as emp_id',
      'employee_promotions.promotion_date',
      'employee_h_rs.company_id',
      'employee_promotions.designation_id',
      ])
      ->map(function($emp){
      $emp->effect_date=date('Y-m-d',strtotime($emp->promotion_date));
      return $emp;
      })
      ->first();
      $data = json_encode($emp);

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
        "Content-Type"  => "application/json"
        ]; 
        //echo $token; die;
        $res=$client->post('http://192.168.32.10:8082/Api/Erp/Promotion', ['body' => $data, 'headers' => $headers]);
        //echo $res->getBody();
        $ApiStatus=json_decode($res->getBody());
        $this->employeepromotions->update($employeepromotions->id,[
        'api_status'=>$ApiStatus->Status,
        ]);
      }
      catch(\GuzzleHttp\Exception\RequestException $e)
      {
        if($employeehr){
        return response()->json(array('success' => true,'id' =>  $employeepromotions->id,'message' => 'Save Successfully'),200);
        }
        throw $e;
      }


      return response()->json(array('success' => true,'id' =>  $employeepromotions->id,'message' => 'Save Successfully'),200);
          
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
      $employeepromotions = $this->employeepromotions
      ->join('employee_h_rs',function($join){
      $join->on('employee_h_rs.id','=','employee_promotions.employee_h_r_id');
      })
      ->leftJoin('companies',function($join){
      $join->on('companies.id','=','employee_h_rs.company_id');
      })
      ->leftJoin('locations',function($join){
      $join->on('locations.id','=','employee_h_rs.location_id');
      })
      ->leftJoin('designations',function($join){
      $join->on('designations.id','=','employee_h_rs.designation_id');
      })
      ->leftJoin('departments',function($join){
      $join->on('departments.id','=','employee_h_rs.department_id');
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
      ->leftJoin('designations as olddesignations',function($join){
      $join->on('olddesignations.id','=','employee_promotions.old_designation_id');
      })
      ->leftJoin('employee_h_rs as oldreportto',function($join){
      $join->on('oldreportto.id','=','employee_promotions.old_report_to_id');
      })
      ->leftJoin('employee_h_rs as reportto',function($join){
      $join->on('reportto.id','=','employee_promotions.report_to_id');
      })
      ->where([['employee_promotions.id','=',$id]])
      ->get([
      'employee_promotions.*',
      'employee_h_rs.name as employee_name',
      'employee_h_rs.code',
      'companies.name as company_name',
      'locations.name as location_name',
      'divisions.name as division_name',
      'departments.name as department_name',
      'sections.name as section_name',
      'subsections.name as subsection_name',
      'olddesignations.name as designation_name',
      'oldreportto.name as old_report_to_name',
      'reportto.name as report_to_name',
      ])
      ->first();

      $row ['fromData'] = $employeepromotions;
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
    public function update(EmployeePromotionRequest $request, $id) {
      $request->request->add(['api_status' => 0]);
      \DB::beginTransaction();
      try
      {
        $employeepromotions=$this->employeepromotions->update($id,[
        'promotion_date'=>$request->promotion_date,
        'designation_id'=>$request->designation_id,
        'grade'=>$request->grade,
        'report_to_id'=>$request->report_to_id,
        'remarks'=>$request->remarks,
        'api_status'=>$request->api_status,
        ]);
        $this->employeehr->update($request->employee_h_r_id,[
        'code'=>$request->code,
        'designation_id'=>$request->designation_id,
        'grade'=>$request->grade,
        'report_to_id'=>$request->report_to_id,
        ]);
      }
      catch(EXCEPTION $e)
      {
        \DB::rollback();
        throw $e;
      }
      \DB::commit();
      $emp=$this->employeepromotions
      ->join('employee_h_rs',function($join){
      $join->on('employee_h_rs.id','=','employee_promotions.employee_h_r_id');
      })
      ->where([['employee_promotions.id','=',$id]])
      ->get([
      'employee_promotions.employee_h_r_id as emp_id',
      'employee_promotions.promotion_date',
      'employee_h_rs.company_id',
      'employee_promotions.designation_id',
      ])
      ->map(function($emp){
      $emp->effect_date=date('Y-m-d',strtotime($emp->promotion_date));
      return $emp;
      })
      ->first();
      $data = json_encode($emp);
      
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
        "Content-Type"  => "application/json"
        ]; 
        //echo $token; die;
        $res=$client->post('http://192.168.32.10:8082/Api/Erp/Promotion', ['body' => $data, 'headers' => $headers]);
        //echo $res->getBody();
        $ApiStatus=json_decode($res->getBody());
        $this->employeepromotions->update($id,[
        'api_status'=>$ApiStatus->Status,
        ]);
      }
      catch(\GuzzleHttp\Exception\RequestException $e)
      {
        if($employeehr){
        return response()->json(array('success' => true,'id' =>  $id,'message' => 'Save Successfully'),200);
        }
        throw $e;
      }

      
      return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->employeepromotions->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }


    public function getEmployeeHr(){
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
        ->where([['employee_h_rs.status_id','=',1]])
        ->get([
        'employee_h_rs.id',
        'employee_h_rs.name as employee_name',
        'employee_h_rs.company_id',
        'companies.name as company_name',
        'employee_h_rs.designation_id',
        'designations.name as designation_name',
        'employee_h_rs.department_id',
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
        'employee_h_rs.status_id',
        'employee_h_rs.location_id',
        'locations.name as location_name',
        'employee_h_rs.division_id',
        'divisions.name as division_name',
        'employee_h_rs.section_id',
        'sections.name as section_name',
        'employee_h_rs.subsection_id',
        'subsections.name as subsection_name',
        'employee_h_rs.inactive_date',
        'employee_h_rs.report_to_id',
        'reportto.name as report_to_name',
        ])
        ->map(function($employeehr){
          return $employeehr;
        });

        echo json_encode($employeehr);
    }

    public function getReportEmployee(){
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $section=array_prepend(array_pluck($this->section->get(),'name','id'),'-Select-','');
        
        $rows=$this->employeehr
        ->when(request('company_id'), function ($q) {
          return $q->where('employee_h_rs.company_id','=',request('company_id', 0));
        })
        ->when(request('designation_id'), function ($q) {
          return $q->where('employee_h_rs.designation_id','=',request('designation_id', 0));
        })   
        ->when(request('department_id'), function ($q) {
          return $q->where('employee_h_rs.department_id','=',request('department_id', 0));
        }) 
        ->get([
          'employee_h_rs.id',
          'employee_h_rs.name',
          'employee_h_rs.code',
          'employee_h_rs.designation_id',
          'employee_h_rs.department_id',
          'employee_h_rs.section_id',
          'employee_h_rs.company_id',
          'employee_h_rs.contact',
          'employee_h_rs.email',
        ])
        ->map(function($rows) use($company,$designation,$department,$section){
          $rows->employee_name=$rows->name;
          $rows->company_id=$company[$rows->company_id];
          $rows->designation_id=isset($designation[$rows->designation_id])?$designation[$rows->designation_id]:'';
          $rows->department_id=isset($department[$rows->department_id])?$department[$rows->department_id]:'';
          $rows->section_id=isset($section[$rows->section_id])?$section[$rows->section_id]:'';
          return $rows;
        });
        echo json_encode($rows);
    }
}
