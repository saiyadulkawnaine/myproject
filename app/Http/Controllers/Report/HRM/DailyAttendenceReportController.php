<?php

namespace App\Http\Controllers\Report\HRM;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeBudgetRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use GuzzleHttp\Client;
class DailyAttendenceReportController extends Controller
{
	private $employeehr;
	private $employeebudget;
	private $company;
	private $designation;
	private $department;

	public function __construct(
		EmployeeHRRepository $employeehr,
		EmployeeBudgetRepository $employeebudget,
		DesignationRepository $designation,
		DepartmentRepository $department,
		CompanyRepository $company
	)
    {
		$this->employeehr = $employeehr;
		$this->employeebudget = $employeebudget;
      	$this->designation = $designation;
      	$this->department = $department;
      	$this->company = $company;

		$this->middleware('auth');
		$this->middleware('permission:view.dailyattendencereports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
		$department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$yesno = config('bprs.yesno');
		$gender = array_prepend(config('bprs.gender'),'-Select-','');
		$status=array_prepend(array_only(config('bprs.status'), [1,0]),'-All-','');
		$work_date=date('Y-m-d');
		return Template::loadView('Report.HRM.DailyAttendenceReport',[
			'designation'=>$designation,
			'department'=>$department,
			'yesno'=>$yesno,
			'gender'=>$gender,
			'company'=>$company,
			'status'=>$status,
			'work_date'=>$work_date
		]);
	}
	 
	public function html() {
		
		$yesno = config('bprs.yesno');
		$gender = array_prepend(config('bprs.gender'),'-Select-','');
		$company=request('company_id',NULL);
		$from_work_date=request('from_work_date', 0);
		$to_work_date=request('to_work_date', 0);

		$client = new Client();
		$response = $client->request('POST', 'http://192.168.32.10:8082/Token',
		[
		'form_params' => [
		'grant_type' => 'password',
		'username' => 'erpadmin',
		'password' => 'admin@erp',
		]
		]);

		$body=json_decode($response->getBody());
		$token=$body->access_token;
		$data=[
			'from_date'=>$from_work_date,
			'to_date'=>$to_work_date,
			'company_id'=>$company
		];
		$headers = [
		'Authorization' => 'Bearer ' . $token,        
		'Accept'        => 'application/json',
		"Content-Type"  => "application/x-www-form-urlencoded"
		]; 
		//echo $token; die;
		$res=$client->post('http://192.168.32.10:8082/Api/Erp/AttendanceStatus', ['form_params' => $data, 'headers' => $headers]);
		//echo $res->getBody(); die;
		$attarr=[];
		$attendences=json_decode($res->getBody());
		foreach($attendences as $row){
			$attarr[$row->emp_id]=$row->attendance_status;
		}

		//print_r($ApiStatus);
		//die;



		$employeehr=$this->employeehr
		->selectRaw('employee_h_rs.id,
		employee_h_rs.name,
		employee_h_rs.code,
		employee_h_rs.date_of_join,
		employee_h_rs.date_of_birth,
		employee_h_rs.gender_id,
		employee_h_rs.national_id,
		employee_h_rs.tin,
		employee_h_rs.salary,
		employee_h_rs.religion,
		employee_h_rs.contact,
		employee_h_rs.email,
		employee_h_rs.address,
		employee_h_rs.status_id,
		companies.id as company_id,
		companies.name as company_name,
		departments.id as department_id,
		designations.name as designation_name,departments.name as department_name')		/* */
		->join('companies',function($join){
		$join->on('employee_h_rs.company_id','=','companies.id');
		})
		->leftJoin('departments',function($join){
		$join->on('employee_h_rs.department_id','=','departments.id');
		})
		->leftJoin('designations',function($join){
		$join->on('employee_h_rs.designation_id','=','designations.id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->when(request('department_id'), function ($q) {
		return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
		})
		->when(request('designation_id'), function ($q) {
		return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
		})
		->when(request('code'), function ($q) {
		return $q->where('employee_h_rs.code', '=', request('code', 0));
		})
		->when(request('date_from'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '<=',request('date_to', 0));
		})
		->where([['employee_h_rs.status_id','=',1]])
		->orderBy('employee_h_rs.id','desc')
		->get()
		->map(function($employeehr) use($gender,$yesno,$attarr){
		$employeehr->company_name = $employeehr->company_name;
		$employeehr->designation_id =$employeehr->designation_name;
		$employeehr->department_id =$employeehr->department_name;
		$employeehr->attendance_status =	isset($attarr[$employeehr->id])?$attarr[$employeehr->id]:'';
		return $employeehr;
		});

		$company_level=[];
		$companies=$this->company->orderBy('id')->get();
		foreach($companies as $company){
			$company_level[$company->id]=['enlisted'=>0,'present'=>0,'leave'=>0,'absent'=>0,'movment'=>0];
		}


		foreach($employeehr as $row){
			$company_level[$row->company_id]['enlisted']+=1;
			if($row->attendance_status=='P'){
			$company_level[$row->company_id]['present']+=1;
			}
			if($row->attendance_status=='CL' || $row->attendance_status=='SL' || $row->attendance_status=='ML' || $row->attendance_status=='EL' ){
			$company_level[$row->company_id]['leave']+=1;
			}
			if($row->attendance_status=='AB'){
			$company_level[$row->company_id]['absent']+=1;
			}
		}

		$companies->map(function($companies) use($company_level){
			$companies->enlisted=number_format($company_level[$companies->id]['enlisted'],0);
			$companies->present=number_format($company_level[$companies->id]['present'],0);
			$companies->leave=number_format($company_level[$companies->id]['leave'],0);
			$companies->absent=number_format($company_level[$companies->id]['absent'],0);
			$companies->btn=1;
			return $companies;
		});
		echo json_encode($companies);
	 }

	public function getdataDept(){

		$yesno = config('bprs.yesno');
		$gender = array_prepend(config('bprs.gender'),'-Select-','');
		$company=request('company_id',NULL);
		$from_work_date=request('from_work_date', 0);
		$to_work_date=request('to_work_date', 0);

		$client = new Client();
		$response = $client->request('POST', 'http://192.168.32.10:8082/Token',
		[
		'form_params' => [
		'grant_type' => 'password',
		'username' => 'erpadmin',
		'password' => 'admin@erp',
		]
		]);

		$body=json_decode($response->getBody());
		$token=$body->access_token;
		$data=[
		'from_date'=>$from_work_date,
		'to_date'=>$to_work_date,
		'company_id'=>$company
		];
		$headers = [
		'Authorization' => 'Bearer ' . $token,        
		'Accept'        => 'application/json',
		"Content-Type"  => "application/x-www-form-urlencoded"
		]; 
		//echo $token; die;
		$res=$client->post('http://192.168.32.10:8082/Api/Erp/AttendanceStatus', ['form_params' => $data, 'headers' => $headers]);
		$attarr=[];
		$attendences=json_decode($res->getBody());
		foreach($attendences as $row){
		$attarr[$row->emp_id]=$row->attendance_status;
		}

		//print_r($ApiStatus);
		//die;



		$employeehr=$this->employeehr
		->selectRaw('employee_h_rs.id,
		employee_h_rs.name,
		employee_h_rs.code,
		employee_h_rs.date_of_join,
		employee_h_rs.date_of_birth,
		employee_h_rs.gender_id,
		employee_h_rs.national_id,
		employee_h_rs.tin,
		employee_h_rs.salary,
		employee_h_rs.religion,
		employee_h_rs.contact,
		employee_h_rs.email,
		employee_h_rs.address,
		employee_h_rs.status_id,
		companies.id as company_id,
		companies.name as company_name,
		departments.id as department_id,
		designations.name as designation_name,departments.name as department_name')		/* */
		->join('companies',function($join){
		$join->on('employee_h_rs.company_id','=','companies.id');
		})
		->leftJoin('departments',function($join){
		$join->on('employee_h_rs.department_id','=','departments.id');
		})
		->leftJoin('designations',function($join){
		$join->on('employee_h_rs.designation_id','=','designations.id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->when(request('department_id'), function ($q) {
		return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
		})
		->when(request('designation_id'), function ($q) {
		return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
		})
		->when(request('code'), function ($q) {
		return $q->where('employee_h_rs.code', '=', request('code', 0));
		})
		->when(request('date_from'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '<=',request('date_to', 0));
		})
		->where([['employee_h_rs.status_id','=',1]])
		->orderBy('employee_h_rs.id','desc')
		->get()
		->map(function($employeehr) use($gender,$yesno,$attarr){
		$employeehr->company_name = $employeehr->company_name;
		//$employeehr->designation_id =$employeehr->designation_name;
		$employeehr->designation_name =$employeehr->designation_name;
		//$employeehr->department_id =$employeehr->department_name;
		$employeehr->department_name =$employeehr->department_name;
		$employeehr->attendance_status =	isset($attarr[$employeehr->id])?$attarr[$employeehr->id]:'';
		return $employeehr;
		});

		$department_level=[];
		$departments=$this->employeehr
		->leftJoin('departments',function($join){
		$join->on('employee_h_rs.department_id','=','departments.id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->groupBy(['departments.id','departments.name'])
		->orderBy('departments.id')
		->get([
		'departments.id',
		'departments.name',
		]);
		foreach($departments as $department){
		$department_level[$department->id]=['enlisted'=>0,'budgeted'=>0,'present'=>0,'leave'=>0,'absent'=>0,'movment'=>0];
		}

		$department_budget=[];
		$budgetEmp=$this->employeebudget
		->selectRaw('
			departments.id,
			departments.name,
			sum(employee_budget_positions.no_of_post) as budgtet_employee
		')
		->join('departments',function($join){
			$join->on('employee_budgets.department_id','=','departments.id');
		})
		->join('employee_budget_positions',function($join){
			$join->on('employee_budget_positions.employee_budget_id','=','employee_budgets.id');
		})
		->where([['employee_budgets.company_id','=',request('company_id')]])
		->groupBy([
			'departments.id',
			'departments.name',
		])
		->get();

		foreach($budgetEmp as $data){
			$department_budget[$data->id]=$data->budgtet_employee;
		}


		foreach($employeehr as $row){
		$department_level[$row->department_id]['enlisted']+=1;
		$department_level[$row->department_id]['budgeted']=isset($department_budget[$row->department_id])?$department_budget[$row->department_id]:0;
		if($row->attendance_status=='P'){
		$department_level[$row->department_id]['present']+=1;
		}
		if($row->attendance_status=='CL' || $row->attendance_status=='SL' || $row->attendance_status=='ML' || $row->attendance_status=='EL' ){
		$department_level[$row->department_id]['leave']+=1;
		}
		if($row->attendance_status=='AB'){
		$department_level[$row->department_id]['absent']+=1;
		}
		}

		$departments->map(function($departments) use($department_level){
		$departments->enlisted=number_format($department_level[$departments->id]['enlisted'],0);
		$departments->present=number_format($department_level[$departments->id]['present'],0);
		$departments->leave=number_format($department_level[$departments->id]['leave'],0);
		$departments->absent=number_format($department_level[$departments->id]['absent'],0);
		$departments->budgeted=number_format($department_level[$departments->id]['budgeted'],0);
		return $departments;
		});
		echo json_encode($departments);

	}

	public function getdataSect(){

		$yesno = config('bprs.yesno');
		$gender = array_prepend(config('bprs.gender'),'-Select-','');
		$company=request('company_id',NULL);
		$from_work_date=request('from_work_date', 0);
		$to_work_date=request('to_work_date', 0);

		$client = new Client();
		$response = $client->request('POST', 'http://192.168.32.10:8082/Token',
		[
		'form_params' => [
		'grant_type' => 'password',
		'username' => 'erpadmin',
		'password' => 'admin@erp',
		]
		]);

		$body=json_decode($response->getBody());
		$token=$body->access_token;
		$data=[
		'from_date'=>$from_work_date,
		'to_date'=>$to_work_date,
		'company_id'=>$company
		];
		$headers = [
		'Authorization' => 'Bearer ' . $token,        
		'Accept'        => 'application/json',
		"Content-Type"  => "application/x-www-form-urlencoded"
		]; 
		//echo $token; die;
		$res=$client->post('http://192.168.32.10:8082/Api/Erp/AttendanceStatus', ['form_params' => $data, 'headers' => $headers]);
		$attarr=[];
		$attendences=json_decode($res->getBody());
		foreach($attendences as $row){
		$attarr[$row->emp_id]=$row->attendance_status;
		}

		//print_r($ApiStatus);
		//die;



		$employeehr=$this->employeehr
		->selectRaw('employee_h_rs.id,
		employee_h_rs.name,
		employee_h_rs.code,
		employee_h_rs.date_of_join,
		employee_h_rs.date_of_birth,
		employee_h_rs.gender_id,
		employee_h_rs.national_id,
		employee_h_rs.tin,
		employee_h_rs.salary,
		employee_h_rs.religion,
		employee_h_rs.contact,
		employee_h_rs.email,
		employee_h_rs.address,
		employee_h_rs.status_id,
		companies.id as company_id,
		companies.name as company_name,
		departments.id as department_id,
		sections.id as section_id,
		designations.name as designation_name,
		departments.name as department_name'
		)		/* */
		->join('companies',function($join){
		$join->on('employee_h_rs.company_id','=','companies.id');
		})
		->leftJoin('departments',function($join){
		$join->on('employee_h_rs.department_id','=','departments.id');
		})
		->leftJoin('designations',function($join){
		$join->on('employee_h_rs.designation_id','=','designations.id');
		})
		->leftJoin('sections',function($join){
		$join->on('employee_h_rs.section_id','=','sections.id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->when(request('department_id'), function ($q) {
		return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
		})
		->when(request('designation_id'), function ($q) {
		return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
		})
		->when(request('code'), function ($q) {
		return $q->where('employee_h_rs.code', '=', request('code', 0));
		})
		->when(request('date_from'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '<=',request('date_to', 0));
		})
		->where([['employee_h_rs.status_id','=',1]])
		->orderBy('employee_h_rs.id','desc')
		->get()
		->map(function($employeehr) use($gender,$yesno,$attarr){
		$employeehr->company_name = $employeehr->company_name;
		//$employeehr->designation_id =$employeehr->designation_name;
		$employeehr->designation_name =$employeehr->designation_name;
		//$employeehr->department_id =$employeehr->department_name;
		$employeehr->department_name =$employeehr->department_name;
		$employeehr->attendance_status =	isset($attarr[$employeehr->id])?$attarr[$employeehr->id]:'';
		return $employeehr;
		});

		$section_level=[];
		$sections=$this->employeehr
		->leftJoin('sections',function($join){
		$join->on('employee_h_rs.section_id','=','sections.id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->groupBy(['sections.id','sections.name'])
		->orderBy('sections.id')
		->get([
		'sections.id',
		'sections.name',
		]);
		foreach($sections as $section){
		$section_level[$section->id]=['enlisted'=>0,'budgeted'=>0,'present'=>0,'leave'=>0,'absent'=>0,'movment'=>0];
		}

		$section_budget=[];
		$budgetEmp=$this->employeebudget
		->selectRaw('
			sections.id,
			sections.name,
			sum(employee_budget_positions.no_of_post) as budgtet_employee
		')
		->join('sections',function($join){
			$join->on('employee_budgets.section_id','=','sections.id');
		})
		->join('employee_budget_positions',function($join){
			$join->on('employee_budget_positions.employee_budget_id','=','employee_budgets.id');
		})
		->where([['employee_budgets.company_id','=',request('company_id')]])
		->groupBy([
			'sections.id',
			'sections.name',
		])
		->get();

		foreach($budgetEmp as $data){
			$section_budget[$data->id]=$data->budgtet_employee;
		}


		foreach($employeehr as $row){
		$section_level[$row->section_id]['enlisted']+=1;
		$section_level[$row->section_id]['budgeted']=isset($section_budget[$row->section_id])?$section_budget[$row->section_id]:0;
		if($row->attendance_status=='P'){
		$section_level[$row->section_id]['present']+=1;
		}
		if($row->attendance_status=='CL' || $row->attendance_status=='SL' || $row->attendance_status=='ML' || $row->attendance_status=='EL' ){
		$section_level[$row->section_id]['leave']+=1;
		}
		if($row->attendance_status=='AB'){
		$section_level[$row->section_id]['absent']+=1;
		}
		}

		$sections->map(function($sections) use($section_level){
		$sections->enlisted=number_format($section_level[$sections->id]['enlisted'],0);
		$sections->present=number_format($section_level[$sections->id]['present'],0);
		$sections->leave=number_format($section_level[$sections->id]['leave'],0);
		$sections->absent=number_format($section_level[$sections->id]['absent'],0);
		$sections->budgeted=number_format($section_level[$sections->id]['budgeted'],0);
		return $sections;
		});
		echo json_encode($sections);

	}

	public function getdataSubSect(){

		$yesno = config('bprs.yesno');
		$gender = array_prepend(config('bprs.gender'),'-Select-','');
		$company=request('company_id',NULL);
		$from_work_date=request('from_work_date', 0);
		$to_work_date=request('to_work_date', 0);

		$client = new Client();
		$response = $client->request('POST', 'http://192.168.32.10:8082/Token',
		[
		'form_params' => [
		'grant_type' => 'password',
		'username' => 'erpadmin',
		'password' => 'admin@erp',
		]
		]);

		$body=json_decode($response->getBody());
		$token=$body->access_token;
		$data=[
		'from_date'=>$from_work_date,
		'to_date'=>$to_work_date,
		'company_id'=>$company
		];
		$headers = [
		'Authorization' => 'Bearer ' . $token,        
		'Accept'        => 'application/json',
		"Content-Type"  => "application/x-www-form-urlencoded"
		]; 
		//echo $token; die;
		$res=$client->post('http://192.168.32.10:8082/Api/Erp/AttendanceStatus', ['form_params' => $data, 'headers' => $headers]);
		$attarr=[];
		$attendences=json_decode($res->getBody());
		foreach($attendences as $row){
		$attarr[$row->emp_id]=$row->attendance_status;
		}

		//print_r($ApiStatus);
		//die;



		$employeehr=$this->employeehr
		->selectRaw('employee_h_rs.id,
		employee_h_rs.name,
		employee_h_rs.code,
		employee_h_rs.date_of_join,
		employee_h_rs.date_of_birth,
		employee_h_rs.gender_id,
		employee_h_rs.national_id,
		employee_h_rs.tin,
		employee_h_rs.salary,
		employee_h_rs.religion,
		employee_h_rs.contact,
		employee_h_rs.email,
		employee_h_rs.address,
		employee_h_rs.status_id,
		companies.id as company_id,
		companies.name as company_name,
		departments.id as department_id,
		sections.id as section_id,
		subsections.id as subsection_id,
		designations.name as designation_name,
		departments.name as department_name'
		)		/* */
		->join('companies',function($join){
		$join->on('employee_h_rs.company_id','=','companies.id');
		})
		->leftJoin('departments',function($join){
		$join->on('employee_h_rs.department_id','=','departments.id');
		})
		->leftJoin('designations',function($join){
		$join->on('employee_h_rs.designation_id','=','designations.id');
		})
		->leftJoin('sections',function($join){
		$join->on('employee_h_rs.section_id','=','sections.id');
		})
		->leftJoin('subsections',function($join){
		$join->on('employee_h_rs.subsection_id','=','subsections.id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->when(request('department_id'), function ($q) {
		return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
		})
		->when(request('designation_id'), function ($q) {
		return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
		})
		->when(request('code'), function ($q) {
		return $q->where('employee_h_rs.code', '=', request('code', 0));
		})
		->when(request('date_from'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '<=',request('date_to', 0));
		})
		->where([['employee_h_rs.status_id','=',1]])
		->orderBy('employee_h_rs.id','desc')
		->get()
		->map(function($employeehr) use($gender,$yesno,$attarr){
		$employeehr->company_name = $employeehr->company_name;
		//$employeehr->designation_id =$employeehr->designation_name;
		$employeehr->designation_name =$employeehr->designation_name;
		//$employeehr->department_id =$employeehr->department_name;
		$employeehr->department_name =$employeehr->department_name;
		$employeehr->attendance_status =	isset($attarr[$employeehr->id])?$attarr[$employeehr->id]:'';
		return $employeehr;
		});

		$subsection_level=[];
		$subsections=$this->employeehr
		->leftJoin('subsections',function($join){
		$join->on('employee_h_rs.subsection_id','=','subsections.id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->groupBy(['subsections.id','subsections.name'])
		->orderBy('subsections.id')
		->get([
		'subsections.id',
		'subsections.name',
		]);
		foreach($subsections as $subsection){
		$subsection_level[$subsection->id]=['enlisted'=>0,'budgeted'=>0,'present'=>0,'leave'=>0,'absent'=>0,'movment'=>0];
		}

		$subsection_budget=[];
		$budgetEmp=$this->employeebudget
		->selectRaw('
			subsections.id,
			subsections.name,
			sum(employee_budget_positions.no_of_post) as budgtet_employee
		')
		->join('subsections',function($join){
			$join->on('employee_budgets.subsection_id','=','subsections.id');
		})
		->join('employee_budget_positions',function($join){
			$join->on('employee_budget_positions.employee_budget_id','=','employee_budgets.id');
		})
		->where([['employee_budgets.company_id','=',request('company_id')]])
		->groupBy([
			'subsections.id',
			'subsections.name',
		])
		->get();

		foreach($budgetEmp as $data){
			$subsection_budget[$data->id]=$data->budgtet_employee;
		}

		foreach($employeehr as $row){
		$subsection_level[$row->subsection_id]['enlisted']+=1;
		$subsection_level[$row->subsection_id]['budgeted']=isset($subsection_budget[$row->subsection_id])?$subsection_budget[$row->subsection_id]:0;
		if($row->attendance_status=='P'){
		$subsection_level[$row->subsection_id]['present']+=1;
		}
		if($row->attendance_status=='CL' || $row->attendance_status=='SL' || $row->attendance_status=='ML' || $row->attendance_status=='EL' ){
		$subsection_level[$row->subsection_id]['leave']+=1;
		}
		if($row->attendance_status=='AB'){
		$subsection_level[$row->subsection_id]['absent']+=1;
		}
		}

		$subsections->map(function($subsections) use($subsection_level){
		$subsections->enlisted=number_format($subsection_level[$subsections->id]['enlisted'],0);
		$subsections->present=number_format($subsection_level[$subsections->id]['present'],0);
		$subsections->leave=number_format($subsection_level[$subsections->id]['leave'],0);
		$subsections->absent=number_format($subsection_level[$subsections->id]['absent'],0);
		$subsections->budgeted=number_format($subsection_level[$subsections->id]['budgeted'],0);
		return $subsections;
		});
		echo json_encode($subsections);

	}

	public function getdataDegn(){

		$yesno = config('bprs.yesno');
		$gender = array_prepend(config('bprs.gender'),'-Select-','');
		$company=request('company_id',NULL);
		$from_work_date=request('from_work_date', 0);
		$to_work_date=request('to_work_date', 0);

		$client = new Client();
		$response = $client->request('POST', 'http://192.168.32.10:8082/Token',
		[
		'form_params' => [
		'grant_type' => 'password',
		'username' => 'erpadmin',
		'password' => 'admin@erp',
		]
		]);

		$body=json_decode($response->getBody());
		$token=$body->access_token;
		$data=[
		'from_date'=>$from_work_date,
		'to_date'=>$to_work_date,
		'company_id'=>$company
		];
		$headers = [
		'Authorization' => 'Bearer ' . $token,        
		'Accept'        => 'application/json',
		"Content-Type"  => "application/x-www-form-urlencoded"
		]; 
		//echo $token; die;
		$res=$client->post('http://192.168.32.10:8082/Api/Erp/AttendanceStatus', ['form_params' => $data, 'headers' => $headers]);
		$attarr=[];
		$attendences=json_decode($res->getBody());
		foreach($attendences as $row){
		$attarr[$row->emp_id]=$row->attendance_status;
		}

		//print_r($ApiStatus);
		//die;



		$employeehr=$this->employeehr
		->selectRaw('employee_h_rs.id,
		employee_h_rs.name,
		employee_h_rs.code,
		employee_h_rs.date_of_join,
		employee_h_rs.date_of_birth,
		employee_h_rs.gender_id,
		employee_h_rs.national_id,
		employee_h_rs.tin,
		employee_h_rs.salary,
		employee_h_rs.religion,
		employee_h_rs.contact,
		employee_h_rs.email,
		employee_h_rs.address,
		employee_h_rs.status_id,
		companies.id as company_id,
		companies.name as company_name,
		departments.id as department_id,
		sections.id as section_id,
		subsections.id as subsection_id,
		designations.id as designation_id,
		designations.name as designation_name,
		departments.name as department_name'
		)		/* */
		->join('companies',function($join){
		$join->on('employee_h_rs.company_id','=','companies.id');
		})
		->leftJoin('departments',function($join){
		$join->on('employee_h_rs.department_id','=','departments.id');
		})
		->leftJoin('designations',function($join){
		$join->on('employee_h_rs.designation_id','=','designations.id');
		})
		->leftJoin('sections',function($join){
		$join->on('employee_h_rs.section_id','=','sections.id');
		})
		->leftJoin('subsections',function($join){
		$join->on('employee_h_rs.subsection_id','=','subsections.id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->when(request('department_id'), function ($q) {
		return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
		})
		->when(request('designation_id'), function ($q) {
		return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
		})
		->when(request('code'), function ($q) {
		return $q->where('employee_h_rs.code', '=', request('code', 0));
		})
		->when(request('date_from'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '<=',request('date_to', 0));
		})
		->where([['employee_h_rs.status_id','=',1]])
		->orderBy('employee_h_rs.id','desc')
		->get()
		->map(function($employeehr) use($gender,$yesno,$attarr){
		$employeehr->company_name = $employeehr->company_name;
		//$employeehr->designation_id =$employeehr->designation_name;
		$employeehr->designation_name =$employeehr->designation_name;
		//$employeehr->department_id =$employeehr->department_name;
		$employeehr->department_name =$employeehr->department_name;
		$employeehr->attendance_status =	isset($attarr[$employeehr->id])?$attarr[$employeehr->id]:'';
		return $employeehr;
		});

		$designation_level=[];
		$designations=$this->employeehr
		->leftJoin('designations',function($join){
		$join->on('employee_h_rs.designation_id','=','designations.id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->groupBy(['designations.id','designations.name'])
		->orderBy('designations.id')
		->get([
		'designations.id',
		'designations.name',
		]);
		foreach($designations as $designation){
		$designation_level[$designation->id]=['enlisted'=>0,'budgeted'=>0,'present'=>0,'leave'=>0,'absent'=>0,'movment'=>0];
		}

		$designation_budget=[];
		$budgetEmp=$this->employeebudget
		->selectRaw('
			designations.id,
			designations.name,
			sum(employee_budget_positions.no_of_post) as budgtet_employee
		')
		->join('employee_budget_positions',function($join){
			$join->on('employee_budget_positions.employee_budget_id','=','employee_budgets.id');
		})
		->join('designations',function($join){
			$join->on('employee_budget_positions.designation_id','=','designations.id');
		})
		->where([['employee_budgets.company_id','=',request('company_id')]])
		->groupBy([
			'designations.id',
			'designations.name',
		])
		->get();

		foreach($budgetEmp as $data){
			$designation_budget[$data->id]=$data->budgtet_employee;
		}


		foreach($employeehr as $row){
		$designation_level[$row->designation_id]['enlisted']+=1;
		$designation_level[$row->designation_id]['budgeted']=isset($designation_budget[$row->designation_id])?$designation_budget[$row->designation_id]:0;
		if($row->attendance_status=='P'){
		$designation_level[$row->designation_id]['present']+=1;
		}
		if($row->attendance_status=='CL' || $row->attendance_status=='SL' || $row->attendance_status=='ML' || $row->attendance_status=='EL' ){
		$designation_level[$row->designation_id]['leave']+=1;
		}
		if($row->attendance_status=='AB'){
		$designation_level[$row->designation_id]['absent']+=1;
		}
		}

		$designations->map(function($designations) use($designation_level){
		$designations->enlisted=number_format($designation_level[$designations->id]['enlisted'],0);
		$designations->present=number_format($designation_level[$designations->id]['present'],0);
		$designations->leave=number_format($designation_level[$designations->id]['leave'],0);
		$designations->absent=number_format($designation_level[$designations->id]['absent'],0);
		$designations->budgeted=number_format($designation_level[$designations->id]['budgeted'],0);
		return $designations;
		});
		echo json_encode($designations);

	}

	public function getdataEmpl(){
		$yesno = config('bprs.yesno');
		$gender = array_prepend(config('bprs.gender'),'-Select-','');
		$company=request('company_id',NULL);
		$from_work_date=request('from_work_date', 0);
		$to_work_date=request('to_work_date', 0);

		$client = new Client();
		$response = $client->request('POST', 'http://192.168.32.10:8082/Token',
		[
		'form_params' => [
		'grant_type' => 'password',
		'username' => 'erpadmin',
		'password' => 'admin@erp',
		]
		]);

		$body=json_decode($response->getBody());
		$token=$body->access_token;
		$data=[
		'from_date'=>$from_work_date,
		'to_date'=>$to_work_date,
		'company_id'=>$company
		];
		$headers = [
		'Authorization' => 'Bearer ' . $token,        
		'Accept'        => 'application/json',
		"Content-Type"  => "application/x-www-form-urlencoded"
		]; 
		//echo $token; die;
		$res=$client->post('http://192.168.32.10:8082/Api/Erp/AttendanceStatus', ['form_params' => $data, 'headers' => $headers]);
		$attarr=[];
		$lcountarr=[];
		$acountarr=[];
		$intimearr=[];
		$attendences=json_decode($res->getBody());
		foreach($attendences as $row){
		$attarr[$row->emp_id]=$row->attendance_status;
		$lcountarr[$row->emp_id]=$row->leave_counter;
		$acountarr[$row->emp_id]=$row->absent_counter;
		$intimearr[$row->emp_id]=$row->in_time;
		}

		//print_r($ApiStatus);
		//die;



		$employeehr=$this->employeehr
		->selectRaw('employee_h_rs.id,
		employee_h_rs.name,
		employee_h_rs.code,
		employee_h_rs.date_of_join,
		employee_h_rs.date_of_birth,
		employee_h_rs.gender_id,
		employee_h_rs.national_id,
		employee_h_rs.tin,
		employee_h_rs.salary,
		employee_h_rs.religion,
		employee_h_rs.contact,
		employee_h_rs.email,
		employee_h_rs.address,
		employee_h_rs.status_id,
		companies.id as company_id,
		companies.name as company_name,
		departments.id as department_id,
		sections.id as section_id,
		subsections.id as subsection_id,
		designations.id as designation_id,
		designations.name as designation_name,
		locations.name as location_name,
		divisions.name as division_name,
		departments.name as department_name'
		)		/* */
		->join('companies',function($join){
		$join->on('employee_h_rs.company_id','=','companies.id');
		})
		->leftJoin('locations',function($join){
		$join->on('employee_h_rs.location_id','=','locations.id');
		})
		->leftJoin('divisions',function($join){
		$join->on('employee_h_rs.division_id','=','divisions.id');
		})
		->leftJoin('departments',function($join){
		$join->on('employee_h_rs.department_id','=','departments.id');
		})
		->leftJoin('sections',function($join){
		$join->on('employee_h_rs.section_id','=','sections.id');
		})
		->leftJoin('subsections',function($join){
		$join->on('employee_h_rs.subsection_id','=','subsections.id');
		})
		->leftJoin('designations',function($join){
		$join->on('employee_h_rs.designation_id','=','designations.id');
		})
		
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->when(request('department_id'), function ($q) {
		return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
		})
		->when(request('designation_id'), function ($q) {
		return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
		})
		->when(request('code'), function ($q) {
		return $q->where('employee_h_rs.code', '=', request('code', 0));
		})
		->when(request('date_from'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
		return $q->where('employee_h_rs.date_of_join', '<=',request('date_to', 0));
		})
		->where([['employee_h_rs.status_id','=',1]])
		->orderBy('employee_h_rs.id','desc')
		->get()
		->map(function($employeehr) use($gender,$yesno,$attarr,$lcountarr,$acountarr,$intimearr){
		$employeehr->company_name = $employeehr->company_name;
		//$employeehr->designation_id =$employeehr->designation_name;
		$employeehr->designation_name =$employeehr->designation_name;
		//$employeehr->department_id =$employeehr->department_name;
		$employeehr->department_name =$employeehr->department_name;
		$employeehr->date_of_join =date('d-M-Y',strtotime($employeehr->date_of_join));
		$employeehr->attendance_status =	isset($attarr[$employeehr->id])?$attarr[$employeehr->id]:'';
		$employeehr->present='--';
		$employeehr->leave='--';
		$employeehr->absent='--';
		if(isset($attarr[$employeehr->id])){
			if($attarr[$employeehr->id]=='P'){
		    $employeehr->present='Yes';
		    }
			if($attarr[$employeehr->id]=='CL' || $attarr[$employeehr->id]=='SL'|| $attarr[$employeehr->id]=='ML' || $attarr[$employeehr->id]=='EL'){
			$employeehr->leave='Yes';
			}
			if($attarr[$employeehr->id]=='AB'){
		    $employeehr->absent='Yes';
		    }
		}
		$employeehr->in_time=isset($intimearr[$employeehr->id])?$intimearr[$employeehr->id]:'--';
		$employeehr->leave_counter=isset($lcountarr[$employeehr->id])?$lcountarr[$employeehr->id]:'--';
		$employeehr->absent_counter=isset($acountarr[$employeehr->id])?$acountarr[$employeehr->id]:'--';
		

		
		

		return $employeehr;
		});

		/*$designation_level=[];
		$designations=$this->employeehr
		->leftJoin('designations',function($join){
		$join->on('employee_h_rs.designation_id','=','designations.id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		})
		->groupBy(['designations.id','designations.name'])
		->orderBy('designations.id')
		->get([
		'designations.id',
		'designations.name',
		]);
		foreach($designations as $designation){
		$designation_level[$designation->id]=['enlisted'=>0,'present'=>0,'leave'=>0,'absent'=>0,'movment'=>0];
		}


		foreach($employeehr as $row){
		$designation_level[$row->designation_id]['enlisted']+=1;
		if($row->attendance_status=='P'){
		$designation_level[$row->designation_id]['present']+=1;
		}
		if($row->attendance_status=='CL' || $row->attendance_status=='SL' || $row->attendance_status=='ML' || $row->attendance_status=='EL' ){
		$designation_level[$row->designation_id]['leave']+=1;
		}
		if($row->attendance_status=='AB'){
		$designation_level[$row->designation_id]['absent']+=1;
		}
		}

		$designations->map(function($designations) use($designation_level){
		$designations->enlisted=number_format($designation_level[$designations->id]['enlisted'],0);
		$designations->present=number_format($designation_level[$designations->id]['present'],0);
		$designations->leave=number_format($designation_level[$designations->id]['leave'],0);
		$designations->absent=number_format($designation_level[$designations->id]['absent'],0);
		return $designations;
		});*/
		echo json_encode($employeehr);
	}
}
