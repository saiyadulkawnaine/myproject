<?php

namespace App\Http\Controllers\Report\HRM;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeMovementRepository;
use App\Repositories\Contracts\HRM\EmployeeMovementDtlRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
class EmployeeMovementReportController extends Controller
{
	private $employeemovement;
	private $employeemovementdtl;
	private $employeehr;
	private $designation;
	private $department;
	private $company;
	private $user;

	public function __construct(
		EmployeeMovementRepository $employeemovement,
		EmployeeMovementDtlRepository $employeemovementdtl,
		EmployeeHRRepository $employeehr,
		DesignationRepository $designation,
		DepartmentRepository $department,
		CompanyRepository $company,
		UserRepository $user
		)
    {
		$this->employeemovement = $employeemovement;
		$this->employeemovementdtl = $employeemovementdtl;
		$this->employeehr = $employeehr;
      	$this->designation = $designation;
      	$this->department = $department;
		$this->company = $company;
        $this->user = $user;

		$this->middleware('auth');

		//$this->middleware('permission:view.employeetodolistreport',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		
		$designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'','');
		$department=array_prepend(array_pluck($this->department->get(),'name','id'),'','');
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'','');
      	return Template::loadView('Report.HRM.EmployeeMovementReport',['designation'=>$designation,'department'=>$department,'company'=>$company]);
	}
	 
	public function reportData() {
		$from=request('date_from', 0);
		$to=request('date_to', 0);
    	$start_date=date('Y-m', strtotime($to))."-01";
		$last_month_date_from=date("Y-m-d", strtotime("first day of previous month",strtotime($start_date)));
		$last_month_date_to=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
		$purpose = array_prepend(config('bprs.purpose'),'-Select-','');
		$company_id=request('company_id',0);
		$department_id=request('department_id',0);
		$designation_id=request('designation_id',0);
		$company=null;
		$department=null;
		$designation=null;
		if ($company_id) {
			$company=" and employee_h_rs.company_id = $company_id ";
		}
		if ($department_id) {
			$department=" and employee_h_rs.department_id = $department_id ";
		}
		if ($designation_id) {
			$designation=" and employee_h_rs.designation_id = $designation_id ";
		}
		$rows=$this->employeemovementdtl
		->join('employee_movements',function($join){
			$join->on('employee_movement_dtls.employee_movement_id','=','employee_movements.id');
		})
		->join('employee_h_rs',function($join){
			$join->on('employee_movements.employee_h_r_id','=','employee_h_rs.id');
		})
		->join('companies',function($join){
			$join->on('companies.id','=','employee_h_rs.company_id');
		})
		->join('departments',function($join){
			$join->on('departments.id','=','employee_h_rs.department_id');
		})
		->join('designations',function($join){
			$join->on('designations.id','=','employee_h_rs.designation_id');
		})
		// ->leftJoin(\DB::raw("(select 
		// 	employee_h_rs.id as employee_h_r_id,
		// 	employee_h_rs.name as employee_name,
		// 	count(employee_movements.id) as total_out_this_month
		// 	from employee_h_rs
		// 	join employee_movements on employee_movements.employee_h_r_id=employee_h_rs.id
		// 	where employee_movements.post_date >='".$from."'
		// 	and employee_movements.post_date <='".$to."'
		// 	$department $designation
		// 	group by 
		// 	employee_h_rs.id,
		// 	employee_h_rs.name) outThisMonth"),
		// 'outThisMonth.employee_h_r_id','=','employee_h_rs.id')
		->leftJoin(\DB::raw("(
			select 
			employee_h_rs.id as employee_h_r_id,
			count(total_days.disvalue) as total_out_this_month
			from employee_h_rs
			join(
			select
			distinct(employee_movements.id) as disvalue,
			employee_h_rs.id as employee_h_r_id
			from employee_movement_dtls 
			join employee_movements on employee_movement_dtls.employee_movement_id = employee_movements.id
			join employee_h_rs on employee_movements.employee_h_r_id=employee_h_rs.id
			where employee_movement_dtls.out_date >= '".$from."'
			and employee_movement_dtls.out_date <= '".$to."'
			$company $department $designation
			group by
			employee_movements.id,
			employee_h_rs.id
			)total_days on total_days.employee_h_r_id=employee_h_rs.id
			where 1=1
			$company $department $designation
			group by 
			employee_h_rs.id) outThisMonth"),
		'outThisMonth.employee_h_r_id','=','employee_h_rs.id')
		// ->leftJoin(\DB::raw("(select 
		// 	employee_h_rs.id as employee_h_r_id,
		// 	employee_h_rs.name as employee_name,
		// 	count(employee_movements.id) as total_out_last_month
		// 	from employee_h_rs
		// 	join employee_movements on employee_movements.employee_h_r_id=employee_h_rs.id
		// 	where employee_movements.post_date >='".$last_month_date_from."'
		// 	and employee_movements.post_date <='".$last_month_date_to."'
		// 	$department $designation
		// 	group by 
		// 	employee_h_rs.id,
		// 	employee_h_rs.name) outLastMonth"),
		// 'outLastMonth.employee_h_r_id','=','employee_h_rs.id')
		->leftJoin(\DB::raw("(
			select 
			employee_h_rs.id as employee_h_r_id,
			count(total_days.disvalue) as total_out_last_month
			from employee_h_rs
			join(
			select
			distinct(employee_movements.id) as disvalue,
			employee_h_rs.id as employee_h_r_id
			from employee_movement_dtls 
			join employee_movements on employee_movement_dtls.employee_movement_id = employee_movements.id
			join employee_h_rs on employee_movements.employee_h_r_id=employee_h_rs.id
			where employee_movement_dtls.out_date >= '".$last_month_date_from."'
			and employee_movement_dtls.out_date <= '".$last_month_date_to."'
			$company $department $designation
			group by
			employee_movements.id,
			employee_h_rs.id
			)total_days on total_days.employee_h_r_id=employee_h_rs.id
			where 1=1
			$department $designation
			group by 
			employee_h_rs.id) outLastMonth"),
		'outLastMonth.employee_h_r_id','=','employee_h_rs.id')

		->when(request('date_from'), function ($q) {
			return $q->where('employee_movement_dtls.out_date', '>=',request('date_from', 0));
		})
	   	->when(request('date_to'), function ($q)  {
			return $q->where('employee_movement_dtls.out_date', '<=',request('date_to', 0));
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
		->orderBy('employee_movements.id','desc')
		->get([
			'employee_movements.id',
			'employee_movements.post_date',
			'employee_movements.employee_h_r_id',
			'employee_movement_dtls.*',
			'employee_h_rs.company_id',
			'employee_h_rs.location_id',
			'employee_h_rs.department_id',
			'employee_h_rs.designation_id',
			'employee_h_rs.code',
			'employee_h_rs.contact',
			'employee_h_rs.name',
			'companies.name as company_name',
			'companies.logo as logo',
			'companies.address as company_address',
			'designations.name as designation_name',
			'departments.name as department_name',
			'outThisMonth.total_out_this_month',
			'outLastMonth.total_out_last_month',
		])
		->map(function($rows) use($purpose){
			$rows->out_date=($rows->out_date_time!==null)?date('d-m-Y',strtotime($rows->out_date_time)):null;
			$rows->out_time=($rows->out_date_time!==null)?date('h:i:s A',strtotime($rows->out_date_time)):null;
			$rows->return_date=($rows->return_date_time!==null)?date('d-m-Y',strtotime($rows->return_date_time)):null;
			$rows->return_time=($rows->return_date_time!==null)?date('h:i:s A',strtotime($rows->return_date_time)):null;
			$rows->purpose=isset($purpose[$rows->purpose_id])?$purpose[$rows->purpose_id]:'';
			$rows->total_out_this_month=number_format($rows->total_out_this_month,0);
			return $rows;
		});

		echo json_encode($rows);
	}

	public function departmentWise(){
		$from=request('date_from', 0);
		$to=request('date_to', 0);
		$company_id=request('company_id',0);
		$department_id=request('department_id',0);
		$designation_id=request('designation_id',0);
		$department=null;
		$company=null;
		$designation=null;
		if ($company_id) {
			$company =" and employee_h_rs.company_id = $company_id ";
		}
		if ($department_id) {
			$department=" and employee_h_rs.department_id = $department_id ";
		}
		if ($designation_id) {
			$designation=" and employee_h_rs.designation_id = $designation_id ";
		}
		$data=collect(
			\DB::select("
			select
			depart.department_id,
			depart.name as department_name,
			count(*) as no_of_employee
			from (
				select
				distinct(employee_h_rs.id) as disvalue,
				departments.id as department_id,
				departments.name
				from employee_movement_dtls 
				join employee_movements on employee_movement_dtls.employee_movement_id = employee_movements.id
				join employee_h_rs on employee_movements.employee_h_r_id=employee_h_rs.id
				join departments on departments.id=employee_h_rs.department_id
				where employee_movement_dtls.out_date >= '".$from."'
				AND employee_movement_dtls.out_date <= '".$to."'
				$company $department $designation
				group by
				employee_h_rs.id,
				departments.id,
				departments.name
			)depart
			group by 
			 depart.department_id,
			 depart.name
			"))
			->map(function($data){
				return $data;
			});

		echo json_encode($data);
	}
	 
	public function dEmployeeDtl(){
		$department_id=request('department_id',0);
		$from=request('date_from', 0);
		$to=request('date_to', 0);
		$data=collect(
			\DB::select("
			select
			departments.id as department_id,
			departments.name as department_name,
			employee_h_rs.name as employee_name,
			employee_h_rs.contact,
			employee_movement_dtls.out_date
			from
			employee_movement_dtls
			join employee_movements on employee_movement_dtls.employee_movement_id = employee_movements.id
			join employee_h_rs on employee_movements.employee_h_r_id=employee_h_rs.id
			join departments on departments.id=employee_h_rs.department_id
			where  employee_movement_dtls.out_date >= TO_DATE('".$from."', 'YYYY/MM/DD')
			and employee_movement_dtls.out_date <= TO_DATE('".$to."', 'YYYY/MM/DD')
			and departments.id=?
			group by 
			departments.id,
			departments.name,
			employee_h_rs.name,
			employee_h_rs.contact,
			employee_movement_dtls.out_date
			order by departments.id
			",[$department_id]))
			->map(function($data){
				return $data;
			});

		echo json_encode($data);
	}
}
