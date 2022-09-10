<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
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
use App\Http\Requests\HRM\EmployeeHRRequest;
use GuzzleHttp\Client;

class EmployeeHRController extends Controller {

    private $employeehr;
    private $designation;
    private $department;
    private $user;
    private $division;
    private $section;
    private $subsection;
    private $location;

    public function __construct(
        EmployeeHRRepository $employeehr,
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
        $this->designation = $designation;
        $this->department = $department;
        $this->division = $division;
        $this->section = $section;
        $this->subsection = $subsection;
        $this->company = $company;
        $this->location = $location;
        $this->user = $user;

        $this->middleware('auth');
        $this->middleware('permission:view.employeehrs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeehrs', ['only' => ['store']]);
        $this->middleware('permission:edit.employeehrs',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeehrs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

      
      //==============================
      /*$path= public_path('images')."\FFLK.csv";
      //echo $path; die;
      $row = 1;
      \DB::beginTransaction();
      if (($handle = fopen($path, "r")) !== FALSE) {
      while (($data = fgetcsv($handle)) !== FALSE) {
      
      if($row<=148){

      if($row==1){
      }
      else{

      $department = $this->department->firstOrCreate(['name' => $data[1]]);
      $designation = $this->designation->firstOrCreate(['name' => $data[3]]);
		$gender=0;
		if($data[8]=='F')
		{
		$gender=2;
		}
		else if($data[8]=='M')
		{
		$gender=1;
		}
      try
      {
      $employeehr=$this->employeehr->create([
      'company_id'=>4,
      'name'=>$data[0],
      'department_id'=>$department->id,
      'designation_id'=>$designation->id,
      'salary'=>$data[4],
      'grade'=>$data[5],
      'date_of_birth'=>date('Y-m-d',strtotime($data[6])),
      'date_of_join'=>date('Y-m-d',strtotime($data[7])),
      'gender_id'=>$gender,
      'national_id'=>$data[9],
      'address'=>$data[10],
      'religion'=>$data[11],
      'contact'=>$data[12],
      'api_status'=>1,
      ]);
      }
      catch(EXCEPTION $e)
      {
      \DB::rollback();
      throw $e;
      }
      }
      }
      $row++;
      }
      fclose($handle);
      }
      \DB::commit();
      echo $row;

      die; */

      //===============================

      

    	/*$client = new Client();
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

		$id=8317;
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $gender = array_prepend(config('bprs.gender'),'-Select-','');

		$emp=$this->employeehr
		->join('companies',function($join){
          $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->join('designations',function($join){
          $join->on('designations.id','=','employee_h_rs.designation_id');
        })
        ->join('departments',function($join){
          $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->where([['employee_h_rs.id','=',$id]])
        ->get([
        	'employee_h_rs.id as emp_id',
        	'employee_h_rs.name as emp_name',
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
        	'employee_h_rs.status_id',
        ])
        ->map(function($emp) use($status,$gender){
        	$emp->date_of_birth=date('Y-m-d',strtotime($emp->date_of_birth));
        	$emp->date_of_join=date('Y-m-d',strtotime($emp->date_of_join));
        	$emp->gender_name=$gender[$emp->gender_id];
        	$emp->status_name=$status[$emp->status_id];
            return $emp;
        })
        ->first();

		$data = json_encode($emp); 
		$res=$client->post('http://192.168.32.10:8082/Api/Erp/Employee', ['body' => $data, 'headers' => $headers]);
		echo $res->getBody(); 
		die;*/

        /*$employees = \DB::connection('lf_staff')->select("select empname,designation,department,grade,doj,dob,nationalidno,vill,ps,po,dis,gross,realgross,basic,phone,email,gender,religion,empcode from lf.employeedetails_view where employeestatus='Active'");
        \DB::beginTransaction();

        foreach($employees as $employee){
            $designation = $this->designation->firstOrCreate(['name' => $employee->designation]);
            $department = $this->department->firstOrCreate(['name' => $employee->department]);
            $gender=0;
            if($employee->gender=='F')
            {
               $gender=2;
            }
            else if($employee->gender=='M')
            {
                $gender=1;
            }
            
            $address= "Vill : " .$employee->vill .", PS : ". $employee->ps .", PO : ". $employee->po.", Dis : ". $employee->dis; 
            
            try
            {               
            $employeehr=$this->employeehr->create(['company_id'=>6,'name'=>$employee->empname,'designation_id'=>$designation->id,'department_id'=>$department->id,'grade'=>$employee->grade,'date_of_join'=>$employee->doj,'date_of_birth'=>$employee->dob,'national_id'=>$employee->nationalidno,'address'=>$address,'salary'=>$employee->gross,'gender_id'=>$gender,'religion'=>$employee->religion,'empcode_jibika'=>$employee->empcode]);

            $employees = \DB::connection('lf_staff')->update('update lf.employee set servicebookno ='.$employeehr->id.'  where empcode = ?', [$employee->empcode]);
            }
            catch(EXCEPTION $e)
            {
                \DB::rollback();
                throw $e;
            }

        }
        \DB::commit();
        echo 'ok';*/

        //echo json_encode($employees);die;

        /*$id=8317;
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $gender = array_prepend(config('bprs.gender'),'-Select-','');

		$emp=$this->employeehr
		->join('companies',function($join){
          $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->join('designations',function($join){
          $join->on('designations.id','=','employee_h_rs.designation_id');
        })
        ->join('departments',function($join){
          $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->where([['employee_h_rs.id','=',$id]])
        ->get([
        	'employee_h_rs.id as emp_id',
        	'employee_h_rs.name as emp_name',
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
        	'employee_h_rs.address',
        	'employee_h_rs.religion',
        	'employee_h_rs.status_id',
        ])
        ->map(function($emp) use($status,$gender){
        	$emp->date_of_birth=date('Y-m-d',strtotime($emp->date_of_birth));
        	$emp->date_of_join=date('Y-m-d',strtotime($emp->date_of_join));
        	$emp->gender_name=$gender[$emp->gender_id];
        	$emp->status_name=$status[$emp->status_id];
            return $emp;
        })
        ->first();
        echo json_encode($emp);
        die;*/



        //$designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        //$department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        //$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        //$location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $yesno = config('bprs.yesno');
        $gender = array_prepend(config('bprs.gender'),'-Select-',0);
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 

        $employeehrs=array();
        $rows=$this->employeehr
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
        ->orderBy('employee_h_rs.id','desc')
        ->when(request('company_id'), function ($q) {
          return $q->where('employee_h_rs.company_id','=',request('company_id', 0));
        })
        ->when(request('designation_id'), function ($q) {
            return $q->where('employee_h_rs.designation_id','=',request('designation_id', 0));
        })   
        ->when(request('department_id'), function ($q) {
            return $q->where('employee_h_rs.department_id','=',request('department_id', 0));
        })
        ->when(request('location_id'), function ($q) {
            return $q->where('employee_h_rs.location_id','=',request('location_id', 0));
        })
        ->get([
        'employee_h_rs.*',
        'designations.name as designation_name',

        'companies.name as company_name',
        'locations.name as location_name',
        'divisions.name as division_name',
        'departments.name as department_name',
        'sections.name as section_name',
        'subsections.name as subsection_name',
        'employee_h_rs.inactive_date',
        ])
        ->map(function($rows) use($user,$gender,$yesno,$status){
          $rows->gender=isset($gender[$rows->gender_id])?$gender[$rows->gender_id]:'';
          $rows->user=isset($user[$rows->user_id])?$user[$rows->user_id]:'';
          $rows->status=isset($status[$rows->status_id])?$status[$rows->status_id]:'';
          $rows->date_of_join=($rows->date_of_join !== null)?date("d-M-Y",strtotime($rows->date_of_join)):null; 
          $rows->date_of_birth=($rows->date_of_birth !== null)?date("d-M-Y",strtotime($rows->date_of_birth)):null; 
          $rows->salary=number_format($rows->salary,0);
          $rows->compliance_salary=number_format($rows->compliance_salary,0);
          $rows->api_status=$rows->api_status?$yesno[$rows->api_status]:'No';
          return $rows;

        });
        /*foreach($rows as $row){
          $employeehr['id']=$row->id; 
          $employeehr['name']=$row->name; 
          $employeehr['code']=$row->code;
          $employeehr['gender_id']=isset($gender[$row->gender_id])?$gender[$row->gender_id]:'';
          $employeehr['user_id']=isset($user[$row->user_id])?$user[$row->user_id]:'';
          $employeehr['company_id']=$company[$row->company_id];
          $employeehr['designation_id']=isset($designation[$row->designation_id])?$designation[$row->designation_id]:''; 
          $employeehr['department_id']=isset($department[$row->department_id])?$department[$row->department_id]:''; 
          $employeehr['grade']=$row->grade; 
          $employeehr['date_of_join']=($row->date_of_join !== null)?date("d-M-Y",strtotime($row->date_of_join)):null; 
          $employeehr['date_of_birth']=($row->date_of_birth !== null)?date("d-M-Y",strtotime($row->date_of_birth)):null; 
          $employeehr['national_id']=$row->national_id; 
          $employeehr['address']=$row->address;
          $employeehr['salary']=number_format($row->salary,0);
          $employeehr['compliance_salary']=number_format($row->compliance_salary,0);
          //$employeehr['yesno']=$yesno[$row->is_advanced_applicable];
          $employeehr['last_education']=$row->last_education;
          $employeehr['experience']=$row->experience;
          $employeehr['tin']=$row->tin;
          $employeehr['email']=$row->email;
          $employeehr['contact']=$row->contact;
          $employeehr['religion']=$row->religion;
          $employeehr['api_status']=$row->api_status?$yesno[$row->api_status]:'No';
          array_push($employeehrs,$employeehr);
        }*/
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $yesno = array_prepend(config('bprs.yesno'),'-Select-','');
        $gender = array_prepend(config('bprs.gender'),'-Select-','');
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $division=array_prepend(array_pluck($this->division->get(),'name','id'),'-Select-','');
        $section=array_prepend(array_pluck($this->section->get(),'name','id'),'','');
        $subsection=array_prepend(array_pluck($this->subsection->get(),'name','id'),'','');
        $employeetype=array_prepend(config('bprs.employeetype'), '-Select-','');
		return Template::loadView('HRM.EmployeeHR', ['designation'=>$designation,'department'=>$department,'yesno'=>$yesno,'gender'=>$gender,'company'=>$company,'status'=>$status,'location'=>$location,'division'=>$division,'section'=>$section,'subsection'=>$subsection,'employeetype'=>$employeetype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeHRRequest $request) {
        $request->request->add(['api_status' => 0]);
        $request->request->add(['status_id' => 1]);
    		$employeehr=$this->employeehr->create($request->except(['id','role_id','user_name','employee_name','signatory_name','report_to_name']));

    		if($employeehr){
    			return response()->json(array('success' => true,'id' =>  $employeehr->id,'message' => 'Save Successfully'),200);
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
		$empname=array_prepend(array_pluck($this->employeehr->get(),'name','id'),'','');
		$employeehr = $this->employeehr
		->leftJoin('users',function($join){
		$join->on('users.id','=','employee_h_rs.user_id');
		})
		->where([['employee_h_rs.id','=',$id]])
		->get([
		'employee_h_rs.*',
		'users.name as user_name',
		])
		->map(function($employeehr) use($empname){
			$employeehr->report_to_name=$empname[$employeehr->report_to_id];
			$employeehr->signatory_name=$empname[$employeehr->signatory_id];
			return $employeehr;
		})
		->first();
		$employeehr['date_of_join']=($employeehr->date_of_join !== null)?date("Y-m-d",strtotime($employeehr->date_of_join)):null; 
		$employeehr['date_of_birth']=($employeehr->date_of_birth !== null)?date("Y-m-d",strtotime($employeehr->date_of_birth)):null;
		$row ['fromData'] = $employeehr;
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
    public function update(EmployeeHRRequest $request, $id) {
        //$request->request->add(['api_status' => 0]);
        $emp=$this->employeehr->find($id);
        if($emp->approved_at){
        $employeehr=$this->employeehr->update($id,$request->except(['id','company_id','role_id','user_name','employee_name','report_to_name','signatory_name','salary','compliance_salary','designation_id','location_id','division_id','department_id','section_id','subsection_id','grade','report_to_id']));
        }
        else{
        $employeehr=$this->employeehr->update($id,$request->except(['id','user_name','report_to_name','signatory_name',]));
        //$employeehr=$this->employeehr->update($id,$request->except(['id']));
        }
        if($employeehr){
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
        if($this->employeehr->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    /*private function getApiToken($empid){

    }

    private function setApiData(){

    }*/
    public function getUser(){
        $rows=$this->user
        ->leftJoin('role_user',function($join){
          $join->on('users.id','=','role_user.user_id');
        })
        ->leftJoin('roles',function($join){
          $join->on('roles.id','=','role_user.role_id');
        })
        ->when(request('name'), function ($q) {
	        return $q->where('users.name', 'like', '%'.request('name', 0).'%');
        })
        ->when(request('email'), function ($q) {
          return $q->where('users.email', 'like', '%'.request('email', 0).'%');
        })
        ->get([
          'users.*',
          'users.name as user_name',
          'role_user.role_id',
          'roles.name as role_id'
        ]);
        echo json_encode($rows);
    }

    public function getReportEmployee(){
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');

        $employeehr=$this->employeehr
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
          'employee_h_rs.*'
        ])
        ->map(function($employeehr) use($company,$designation,$department){
          $employeehr->employee_name=$employeehr->name;
          $employeehr->company_id=$company[$employeehr->company_id];
          $employeehr->designation_id=isset($designation[$employeehr->designation_id])?$designation[$employeehr->designation_id]:'';
          $employeehr->department_id=isset($department[$employeehr->department_id])?$department[$employeehr->department_id]:'';
          return $employeehr;
        });

        echo json_encode($employeehr);
    }

    public function getAppointLetter()
    {

      $id=request('id',0);
      $employeehr=array_prepend(array_pluck($this->employeehr->get(),'name','id'),'','');
      $employeeaddress=array_prepend(array_pluck($this->employeehr->get(),'designation_id','id'),'','');
      $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'','');
      $rows=$this->employeehr
      ->join('companies',function($join){
        $join->on('companies.id','=','employee_h_rs.company_id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','employee_h_rs.created_by');
      })
      ->leftJoin('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
      })
      ->leftJoin('designations',function($join){
        $join->on('designations.id','=','employee_h_rs.designation_id');
      })
      ->leftJoin('locations',function($join){
        $join->on('locations.id','=','employee_h_rs.location_id');
      })
      // ->leftJoin('employee_h_r_jobs',function($join){
      //   $join->on('employee_h_rs.id','=','employee_h_r_jobs.employee_h_r_id');
      // })
      // ->leftJoin('employee_h_r_leaves',function($join){
      //   $join->on('employee_h_rs.id','=','employee_h_r_leaves.employee_h_r_id');
      // })
      ->where([['employee_h_rs.id','=',$id]])
      ->get([
        'employee_h_rs.*',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'designations.name as designation_name',
        'designations.employee_category_id',
        'departments.name as department_name',
        'locations.name as location_name',
        'locations.address as location_address',
        'users.name as user_name',
      ])
      ->first();

      $leave=$this->employeehr
      ->leftJoin('employee_h_r_leaves',function($join){
        $join->on('employee_h_rs.id','=','employee_h_r_leaves.employee_h_r_id');
      })
      ->where([['employee_h_rs.id','=',$id]])
      ->whereNull('employee_h_r_leaves.deleted_at')
      ->orderBy('employee_h_r_leaves.sort_id')
      ->get([
        'employee_h_rs.id as employee_h_r_id',
        'employee_h_r_leaves.*'
      ]);

      $job=$this->employeehr
      ->leftJoin('employee_h_r_jobs',function($join){
        $join->on('employee_h_rs.id','=','employee_h_r_jobs.employee_h_r_id');
      })
      ->where([['employee_h_rs.id','=',$id]])
      ->whereNull('employee_h_r_jobs.deleted_at')
      ->orderBy('employee_h_r_jobs.sort_id')
      ->get([
        'employee_h_rs.id as employee_h_r_id',
        'employee_h_r_jobs.*',
      ]);


      
      $rows->report_name=isset($employeehr[$rows->report_to_id])?$employeehr[$rows->report_to_id]:'';
      $rows->report_designation_id=$employeeaddress[$rows->report_to_id];
      $rows->report_designation=$designation[$rows->report_designation_id];
    
      $rows->signatory_name=isset($employeehr[$rows->signatory_id])?$employeehr[$rows->signatory_id]:'';
      $rows->signatory_designation_id=$employeeaddress[$rows->signatory_id];
      $rows->signatory_designation=$designation[$rows->signatory_designation_id];

      $rows->date_of_join=date("j F,Y",strtotime($rows->date_of_join));
      $rows->date_of_birth=date("d-M-Y",strtotime($rows->date_of_birth));
      $rows->print_date=$rows->appointment_date?date('l, F d, Y',strtotime($rows->appointment_date)):'';
      // $newTime = strtotime($rows->date_of_join, $rows->probation_days);
      // $rows->p_days = date('M', $newTime);

      $amount=$rows->salary;
      //$details=$data->groupBy('requisition_no');

      $inword=Numbertowords::ntow(number_format($amount,2,'.',''),'TK','Paisa');
      $rows->inword=$inword;
      // $employeehr['master']=$rows;
        
      $company=$this->company
      ->where([['id','=',$rows->company_id]])
      ->get()->first();
      $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(true);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'N', 8);
      $pdf->AddPage();
      
      $barcodestyle = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => false,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0,0,0),
        'bgcolor' => false, //array(255,255,255),
        'text' => true,
        'font' => 'helvetica',
        'fontsize' => 8,
        'stretchtext' => 4
      );
      $pdf->SetY(5);
      $pdf->SetX(185);
      $qrc =  'Name :'.$rows->name." ,\n".
            'Designation :'.$rows->designation_name." ,\n".
            'Department :'.$rows->department_name." ,\n".
            'Salary :'.$rows->salary." ,\n".
            'Company :'.$rows->company_name." ,\n".
            'Location :'.$rows->location_name;
    //  $pdf->write2DBarcode($qrc, 'QRCODE,Q', 180, 3, 45, 20, $barcodestyle, 'N');
      $pdf->write2DBarcode($qrc, 'QRCODE,Q', 180, 3, 45, 45, $barcodestyle, 'N');

      $pdf->Text(180, 17, 'FAMKAM ERP');
      $pdf->Text(180, 20, 'Employee ID:'.$rows->id);

      $pdf->SetY(10);
      $image_file ='images/logo/'.$company->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(10);
      $pdf->SetFont('helvetica', 'N', 9);
      //$pdf->Text(73, 12, $company->address);
       $pdf->Cell(0, 40, $company->address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      $pdf->SetY(16);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', 'N', 10);
      //$pdf->SetTitle('Appointment Letter');

      if ($rows->employee_category_id==1) {
        $view= \View::make('Defult.HRM.AppintmentLetterGMPdf',['rows'=>$rows,'job'=>$job,'leave'=>$leave]);
        $html_content=$view->render();
        $pdf->SetY(40);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/AppintmentLetterGMPdf.pdf';
        $pdf->output($filename);
      }else {
        $view= \View::make('Defult.HRM.AppintmentLetterPdf',['rows'=>$rows,'job'=>$job,'leave'=>$leave]);
        $html_content=$view->render();
        $pdf->SetY(40);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/AppintmentLetterPdf.pdf';
        $pdf->output($filename);
      }

    }

    public function getNDAPdf(){
      $id=request('id',0);
      $rows=$this->employeehr
      ->join('companies',function($join){
        $join->on('companies.id','=','employee_h_rs.company_id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','employee_h_rs.created_by');
      })
      ->leftJoin('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
      })
      ->leftJoin('designations',function($join){
        $join->on('designations.id','=','employee_h_rs.designation_id');
      })
      ->leftJoin('locations',function($join){
        $join->on('locations.id','=','employee_h_rs.location_id');
      })
      ->where([['employee_h_rs.id','=',$id]])
      ->get([
        'employee_h_rs.*',
        //'employee_h_r_jobs.job_description',
        //'employee_h_r_leaves.leave_description',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'designations.name as designation_name',
        'departments.name as department_name',
        'locations.name as location_name',
        'locations.address as location_address',
        'users.name as user_name',
      ])
      ->first();

      $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, '100', PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->setCellHeightRatio(1.80);
      //$pdf->SetFont('helvetica', '', 10);
      $pdf->AddPage();
      $pdf->SetY(100);
      $pdf->SetFont('helvetica', 'N', 10);
 
      $view= \View::make('Defult.HRM.NDApdf',['rows'=>$rows]);
      $html_content=$view->render();
      $pdf->WriteHtml($html_content, true, false,true,false,'');

      $pdf->SetFont('helvetica', '', 8);
      $filename = storage_path() . '/NonDisclosureAgreementpdf.pdf';
      $pdf->output($filename,'I');
      exit();
    }
}
