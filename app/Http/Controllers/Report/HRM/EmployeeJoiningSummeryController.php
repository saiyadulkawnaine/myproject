<?php

namespace App\Http\Controllers\Report\HRM;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;

class EmployeeJoiningSummeryController extends Controller
{
	private $employeehr;
	private $company;
	private $designation;
	private $department;

	public function __construct(EmployeeHRRepository $employeehr,DesignationRepository $designation,DepartmentRepository $department,CompanyRepository $company)
    {
		$this->employeehr = $employeehr;
      	$this->designation = $designation;
      	$this->department = $department;
      	$this->company = $company;

		$this->middleware('auth');

		// $this->middleware('permission:view.employeejoiningsummeryreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
	  $status=array_prepend(array_only(config('bprs.status'), [1,0]),'-All-','');
	  $designationlevel=array_prepend(config('bprs.designationlevel'),'-Select-','');
	  $employeecategory=array_prepend(config('bprs.employeecategory'),'-Select-','');
      return Template::loadView('Report.HRM.EmployeeJoiningSummery',['company'=>$company,'status'=>$status,'designationlevel'=>$designationlevel,'employeecategory'=>$employeecategory]);
	}
	 
  public function getDepartmentData(){
        
		$company_id=request('company_id',0);
		$status_id=request('status_id',NULL);
		$date_from=request('date_from', 0);
		$date_to=request('date_to', 0);
		$designation_level_id=request('designation_level_id',0);
		$employee_category_id=request('employee_category_id',0);

		$designationlevel=null;
		$employeecategory=null;
		$datefrom=null;
		$dateto=null;
		$status=null;
		$company=null;

		if($date_from){
			$datefrom=" and employee_h_rs.date_of_join>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and employee_h_rs.date_of_join<='".$date_to."' ";
		}

		if($company_id){
			$company=" and employee_h_rs.company_id = $company_id ";
		}
		if($status_id){
			$status=" and employee_h_rs.status_id = $status_id ";
		}
		if($designation_level_id){
			$designationlevel=" and designations.designation_level_id = $designation_level_id ";
		}
		if($employee_category_id){
			$employeecategory=" and designations.employee_category_id = $employee_category_id ";
		}


		$rows=collect(
		\DB::select("
			select
			m.department_id,
			m.department_name,
			m.join_month,
			m.join_month_no,
			m.join_year,
			count(m.id) as no_of_employee
			from(
			select
			employee_h_rs.id,
			employee_h_rs.department_id,
			departments.name as department_name,
			employee_h_rs.date_of_join,
			to_char(employee_h_rs.date_of_join, 'Mon') as join_month,
			to_char(employee_h_rs.date_of_join, 'MM') as join_month_no,
			to_char(employee_h_rs.date_of_join, 'yy') as join_year
			from
			employee_h_rs
			join departments on departments.id=employee_h_rs.department_id
			left join designations on designations.id=employee_h_rs.designation_id
			where 1=1 $datefrom $dateto $company $status $employeecategory $designationlevel
			)m
			group by
			m.department_id,
			m.department_name,
			m.join_month,
			m.join_month_no,
			m.join_year
			order by 
			m.join_year,
			m.join_month_no,
			no_of_employee desc
		"))
		->map(function($rows) {
			$rows->month=$rows->join_month."-".$rows->join_year;
			return $rows;
		});

		$monthArr=[];
		$departmentArr=[];
		$monthwiseArr=[];
		foreach($rows as $data){
			$monthArr[$data->month]=$data->month;
			$departmentArr[$data->department_id]=$data->department_name;
			$monthwiseArr[$data->department_id][$data->month]=$data->no_of_employee;
		}
		
		return Template::loadView('Report.HRM.EmployeeJoiningSummeryMatrix',['monthArr'=>$monthArr,'departmentArr'=>$departmentArr,'monthwiseArr'=>$monthwiseArr,'date_from'=>$date_from,'date_to'=>$date_to]);
  }

	public function getSectionData(){
        
		$company_id=request('company_id',0);
		$status_id=request('status_id',NULL);
		$date_from=request('date_from', 0);
		$date_to=request('date_to', 0);
		$designation_level_id=request('designation_level_id',0);
		$employee_category_id=request('employee_category_id',0);

		$designationlevel=null;
		$employeecategory=null;
		$datefrom=null;
		$dateto=null;
		$status=null;
		$company=null;

		if($date_from){
			$datefrom=" and employee_h_rs.date_of_join>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and employee_h_rs.date_of_join<='".$date_to."' ";
		}

		if($company_id){
			$company=" and employee_h_rs.company_id = $company_id ";
		}
		if($status_id){
			$status=" and employee_h_rs.status_id = $status_id ";
		}
		if($designation_level_id){
			$designationlevel=" and designations.designation_level_id = $designation_level_id ";
		}
		if($employee_category_id){
			$employeecategory=" and designations.employee_category_id = $employee_category_id ";
		}


		$rows=collect(
		\DB::select("
			select
			m.section_id,
			m.section_name,
			m.join_month,
			m.join_month_no,
			m.join_year,
			count(m.id) as no_of_employee
			from(
			select
			employee_h_rs.id,
			employee_h_rs.section_id,
			sections.name as section_name,
			employee_h_rs.date_of_join,
			to_char(employee_h_rs.date_of_join, 'Mon') as join_month,
			to_char(employee_h_rs.date_of_join, 'MM') as join_month_no,
			to_char(employee_h_rs.date_of_join, 'yy') as join_year
			from
			employee_h_rs
			join sections on sections.id=employee_h_rs.section_id
			left join designations on designations.id=employee_h_rs.designation_id
			where 1=1 $datefrom $dateto $company $status $employeecategory $designationlevel
			)m
			group by
			m.section_id,
			m.section_name,
			m.join_month,
			m.join_month_no,
			m.join_year
			order by 
			m.join_year,
			m.join_month_no,
			no_of_employee desc
		"))
		->map(function($rows) {
			$rows->month=$rows->join_month."-".$rows->join_year;
			return $rows;
		});

		$monthArr=[];
		$sectionArr=[];
		$monthwiseArr=[];
		foreach($rows as $data){
			$monthArr[$data->month]=$data->month;
			$sectionArr[$data->section_id]=$data->section_name;
			$monthwiseArr[$data->section_id][$data->month]=$data->no_of_employee;
		}
		
		return Template::loadView('Report.HRM.EmployeeJoiningSummerySectionMatrix',['monthArr'=>$monthArr,'sectionArr'=>$sectionArr,'monthwiseArr'=>$monthwiseArr,'date_from'=>$date_from,'date_to'=>$date_to]);
    }
	 
	public function getSubsectionData(){
        
		$company_id=request('company_id',0);
		$status_id=request('status_id',NULL);
		$date_from=request('date_from', 0);
		$date_to=request('date_to', 0);
		$designation_level_id=request('designation_level_id',0);
		$employee_category_id=request('employee_category_id',0);

		$designationlevel=null;
		$employeecategory=null;
		$datefrom=null;
		$dateto=null;
		$status=null;
		$company=null;

		if($date_from){
			$datefrom=" and employee_h_rs.date_of_join>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and employee_h_rs.date_of_join<='".$date_to."' ";
		}
		if($company_id){
			$company=" and employee_h_rs.company_id = $company_id ";
		}
		if($status_id){
			$status=" and employee_h_rs.status_id = $status_id ";
		}
		if($designation_level_id){
			$designationlevel=" and designations.designation_level_id = $designation_level_id ";
		}
		if($employee_category_id){
			$employeecategory=" and designations.employee_category_id = $employee_category_id ";
		}


		$rows=collect(
		\DB::select("
			select
			m.subsection_id,
			m.subsection_name,
			m.join_month,
			m.join_month_no,
			m.join_year,
			count(m.id) as no_of_employee
			from(
			select
			employee_h_rs.id,
			employee_h_rs.subsection_id,
			subsections.name as subsection_name,
			employee_h_rs.date_of_join,
			to_char(employee_h_rs.date_of_join, 'Mon') as join_month,
			to_char(employee_h_rs.date_of_join, 'MM') as join_month_no,
			to_char(employee_h_rs.date_of_join, 'yy') as join_year
			from
			employee_h_rs
			join subsections on subsections.id=employee_h_rs.subsection_id
			left join designations on designations.id=employee_h_rs.designation_id
			where 1=1 $datefrom $dateto $company $status $employeecategory $designationlevel
			)m
			group by
			m.subsection_id,
			m.subsection_name,
			m.join_month,
			m.join_month_no,
			m.join_year
			order by 
			m.join_year,
			m.join_month_no,
			no_of_employee desc
		"))
		->map(function($rows) {
			$rows->month=$rows->join_month."-".$rows->join_year;
			return $rows;
		});

		$monthArr=[];
		$subsectionArr=[];
		$monthwiseArr=[];
		foreach($rows as $data){
			$monthArr[$data->month]=$data->month;
			$subsectionArr[$data->subsection_id]=$data->subsection_name;
			$monthwiseArr[$data->subsection_id][$data->month]=$data->no_of_employee;
		}
		
		return Template::loadView('Report.HRM.EmployeeJoiningSummerySubsectionMatrix',['monthArr'=>$monthArr,'subsectionArr'=>$subsectionArr,'monthwiseArr'=>$monthwiseArr,'date_from'=>$date_from,'date_to'=>$date_to]);
    }
	 
	public function getDesignationData(){
        
		$company_id=request('company_id',0);
		$designation_level_id=request('designation_level_id',0);
		$employee_category_id=request('employee_category_id',0);
		$status_id=request('status_id',NULL);
		$date_from=request('date_from', 0);
		$date_to=request('date_to', 0);

		$datefrom=null;
		$dateto=null;
		$status=null;
		$company=null;
		$designationlevel=null;
		$employeecategory=null;

		if($date_from){
			$datefrom=" and employee_h_rs.date_of_join>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and employee_h_rs.date_of_join<='".$date_to."' ";
		}
		if($company_id){
			$company=" and employee_h_rs.company_id = $company_id ";
		}
		if($status_id){
			$status=" and employee_h_rs.status_id = $status_id ";
		}
		if($designation_level_id){
			$designationlevel=" and designations.designation_level_id = $designation_level_id ";
		}
		if($employee_category_id){
			$employeecategory=" and designations.employee_category_id = $employee_category_id ";
		}


		$rows=collect(
		\DB::select("
			select
			m.designation_id,
			m.designation_name,
			m.join_month,
			m.join_month_no,
			m.join_year,
			count(m.id) as no_of_employee
			from(
			select
			employee_h_rs.id,
			employee_h_rs.designation_id,
			designations.name as designation_name,
			employee_h_rs.date_of_join,
			to_char(employee_h_rs.date_of_join, 'Mon') as join_month,
			to_char(employee_h_rs.date_of_join, 'MM') as join_month_no,
			to_char(employee_h_rs.date_of_join, 'yy') as join_year
			from
			employee_h_rs
			join designations on designations.id=employee_h_rs.designation_id
			where 1=1 $datefrom $dateto $company $status $designationlevel $employeecategory
			)m
			group by
			m.designation_id,
			m.designation_name,
			m.join_month,
			m.join_month_no,
			m.join_year
			order by 
			m.join_year,
			m.join_month_no,
			no_of_employee desc
		"))
		->map(function($rows) {
			$rows->month=$rows->join_month."-".$rows->join_year;
			return $rows;
		});

		$monthArr=[];
		$designationArr=[];
		$monthwiseArr=[];
		foreach($rows as $data){
			$monthArr[$data->month]=$data->month;
			$designationArr[$data->designation_id]=$data->designation_name;
			$monthwiseArr[$data->designation_id][$data->month]=$data->no_of_employee;
		}
		
		return Template::loadView('Report.HRM.EmployeeJoiningSummeryDesignationMatrix',['monthArr'=>$monthArr,'designationArr'=>$designationArr,'monthwiseArr'=>$monthwiseArr,'date_from'=>$date_from,'date_to'=>$date_to]);
    }

	public function getSectionEmployee(){
		$section_id=request('section_id', 0);
		$company_id=request('company_id',0);
		$designation_level_id=request('designation_level_id',0);
		$employee_category_id=request('employee_category_id',0);
		$date_from=request('date_from', 0);
		$date_to=request('date_to', 0);
		$status_id=request('status_id',NULL);

		$datefrom=null;
		$dateto=null;
		$company=null;
		$status=null;
		$designationlevel=null;
		$employeecategory=null;

		if($date_from){
			$datefrom=" and employee_h_rs.date_of_join>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and employee_h_rs.date_of_join<='".$date_to."' ";
		}
		if($status_id){
			$status=" and employee_h_rs.status_id = $status_id ";
		}
		if($company_id){
			$company=" and employee_h_rs.company_id = $company_id ";
		}
		if($designation_level_id){
			$designationlevel=" and designations.designation_level_id = $designation_level_id ";
		}
		if($employee_category_id){
			$employeecategory=" and designations.employee_category_id = $employee_category_id ";
		}

		$emp_status=array_prepend(config('bprs.status'),'--','');

		$rows=collect(
			\DB::select(
			"select
				employee_h_rs.id as employee_h_r_id,
				employee_h_rs.name as employee_name,
				employee_h_rs.section_id,
				sections.name as section,
				departments.name as department,
				subsections.name as subsection,
				designations.name as designation,
				companies.name as company,
				locations.name as location,
				divisions.name as division,
				employee_h_rs.date_of_join,
				employee_h_rs.status_date,
				employee_h_rs.status_id
				from
				employee_h_rs
				join sections on sections.id=employee_h_rs.section_id
				join companies on companies.id=employee_h_rs.company_id
				join locations on locations.id=employee_h_rs.location_id
				left join divisions on divisions.id=employee_h_rs.division_id
				left join subsections on subsections.id=employee_h_rs.subsection_id
				left join departments on departments.id=employee_h_rs.department_id
				left join designations on designations.id=employee_h_rs.designation_id
				where 1=1 $datefrom $dateto $company $status $designationlevel $employeecategory
				and employee_h_rs.section_id='".$section_id."'
				
			"))
			->map(function($rows) use($emp_status){
				$rows->status=$emp_status[$rows->status_id];
				$rows->date_of_join=date('d-M-Y',strtotime($rows->date_of_join));
				$rows->status_date=$rows->status_date?date('d-M-Y',strtotime($rows->status_date)):'--';
				return $rows;
			});
		echo json_encode($rows);
	}

	public function getSubSectionEmployee(){
		$subsection_id=request('subsection_id', 0);
		$company_id=request('company_id',0);
		$designation_level_id=request('designation_level_id',0);
		$employee_category_id=request('employee_category_id',0);
		$date_from=request('date_from', 0);
		$date_to=request('date_to', 0);
		$status_id=request('status_id',NULL);

		$datefrom=null;
		$dateto=null;
		$company=null;
		$status=null;
		$designationlevel=null;
		$employeecategory=null;

		if($date_from){
			$datefrom=" and employee_h_rs.date_of_join>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and employee_h_rs.date_of_join<='".$date_to."' ";
		}
		if($status_id){
			$status=" and employee_h_rs.status_id = $status_id ";
		}
		if($company_id){
			$company=" and employee_h_rs.company_id = $company_id ";
		}
		if($designation_level_id){
			$designationlevel=" and designations.designation_level_id = $designation_level_id ";
		}
		if($employee_category_id){
			$employeecategory=" and designations.employee_category_id = $employee_category_id ";
		}

		$emp_status=array_prepend(config('bprs.status'),'--','');

		$rows=collect(
			\DB::select(
			"select
				employee_h_rs.id as employee_h_r_id,
				employee_h_rs.name as employee_name,
				employee_h_rs.subsection_id,
				sections.name as section,
				departments.name as department,
				subsections.name as subsection,
				designations.name as designation,
				companies.name as company,
				locations.name as location,
				divisions.name as division,
				employee_h_rs.date_of_join,
				employee_h_rs.status_date,
				employee_h_rs.status_id
				from
				employee_h_rs
				join subsections on subsections.id=employee_h_rs.subsection_id
				join companies on companies.id=employee_h_rs.company_id
				join locations on locations.id=employee_h_rs.location_id
				left join divisions on divisions.id=employee_h_rs.division_id
				left join sections on sections.id=employee_h_rs.section_id
				left join departments on departments.id=employee_h_rs.department_id
				left join designations on designations.id=employee_h_rs.designation_id
				where 1=1 $datefrom $dateto $company $status $designationlevel $employeecategory
				and employee_h_rs.subsection_id='".$subsection_id."'
			"))
			->map(function($rows) use($emp_status){
				$rows->status=$emp_status[$rows->status_id];
				$rows->date_of_join=date('d-M-Y',strtotime($rows->date_of_join));
				$rows->status_date=$rows->status_date?date('d-M-Y',strtotime($rows->status_date)):'--';
				return $rows;
			});
		echo json_encode($rows);
	}

	public function getDepartmentEmployee(){
		$department_id=request('department_id', 0);
		$company_id=request('company_id',0);
		$designation_level_id=request('designation_level_id',0);
		$employee_category_id=request('employee_category_id',0);
		$date_from=request('date_from', 0);
		$date_to=request('date_to', 0);
		$status_id=request('status_id',NULL);

		$datefrom=null;
		$dateto=null;
		$company=null;
		$status=null;
		$designationlevel=null;
		$employeecategory=null;

		if($date_from){
			$datefrom=" and employee_h_rs.date_of_join>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and employee_h_rs.date_of_join<='".$date_to."' ";
		}
		if($status_id){
			$status=" and employee_h_rs.status_id = $status_id ";
		}
		if($company_id){
			$company=" and employee_h_rs.company_id = $company_id ";
		}
		if($designation_level_id){
			$designationlevel=" and designations.designation_level_id = $designation_level_id ";
		}
		if($employee_category_id){
			$employeecategory=" and designations.employee_category_id = $employee_category_id ";
		}

		$emp_status=array_prepend(config('bprs.status'),'--','');

		$rows=collect(
			\DB::select(
			"select
				employee_h_rs.id as employee_h_r_id,
				employee_h_rs.name as employee_name,
				employee_h_rs.department_id,
				sections.name as section,
				departments.name as department,
				subsections.name as subsection,
				designations.name as designation,
				companies.name as company,
				locations.name as location,
				divisions.name as division,
				employee_h_rs.date_of_join,
				employee_h_rs.status_date,
				employee_h_rs.status_id

				from
				employee_h_rs
				join departments on departments.id=employee_h_rs.department_id
				join companies on companies.id=employee_h_rs.company_id
				join locations on locations.id=employee_h_rs.location_id
				left join divisions on divisions.id=employee_h_rs.division_id
				left join sections on sections.id=employee_h_rs.section_id
				left join subsections on subsections.id=employee_h_rs.subsection_id
				left join designations on designations.id=employee_h_rs.designation_id
				where 1=1 $datefrom $dateto $company $status $designationlevel $employeecategory
				and employee_h_rs.department_id='".$department_id."'
			"))
			->map(function($rows) use($emp_status){
				$rows->status=$emp_status[$rows->status_id];
				$rows->date_of_join=date('d-M-Y',strtotime($rows->date_of_join));
				$rows->status_date=date('d-M-Y',strtotime($rows->status_date));
				$rows->status_date=$rows->status_date?date('d-M-Y',strtotime($rows->status_date)):'--';
				return $rows;
			});
		echo json_encode($rows);
	}

	public function getDesignationEmployee(){
		$designation_id=request('designation_id', 0);
		$company_id=request('company_id',0);
		$designation_level_id=request('designation_level_id',0);
		$employee_category_id=request('employee_category_id',0);
		$date_from=request('date_from', 0);
		$date_to=request('date_to', 0);
		$status_id=request('status_id',NULL);

		$datefrom=null;
		$dateto=null;
		$company=null;
		$status=null;
		$designationlevel=null;
		$employeecategory=null;

		if($date_from){
			$datefrom=" and employee_h_rs.date_of_join>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and employee_h_rs.date_of_join<='".$date_to."' ";
		}
		if($status_id){
			$status=" and employee_h_rs.status_id = $status_id ";
		}
		if($company_id){
			$company=" and employee_h_rs.company_id = $company_id ";
		}
		if($designation_level_id){
			$designationlevel=" and designations.designation_level_id = $designation_level_id ";
		}
		if($employee_category_id){
			$employeecategory=" and designations.employee_category_id = $employee_category_id ";
		}

		$emp_status=array_prepend(config('bprs.status'),'--','');

		$rows=collect(
			\DB::select(
			"select
				employee_h_rs.id as employee_h_r_id,
				employee_h_rs.name as employee_name,
				employee_h_rs.designation_id,
				sections.name as section,
				departments.name as department,
				subsections.name as subsection,
				designations.name as designation,
				companies.name as company,
				locations.name as location,
				divisions.name as division,
				employee_h_rs.date_of_join,
				employee_h_rs.status_date,
				employee_h_rs.status_id
				from
				employee_h_rs
				join designations on designations.id=employee_h_rs.designation_id
				join companies on companies.id=employee_h_rs.company_id
				join locations on locations.id=employee_h_rs.location_id
				left join divisions on divisions.id=employee_h_rs.division_id
				left join sections on sections.id=employee_h_rs.section_id
				left join subsections on subsections.id=employee_h_rs.subsection_id
				left join departments on departments.id=employee_h_rs.department_id
				where 1=1 $datefrom $dateto $company $status $designationlevel $employeecategory
				and employee_h_rs.designation_id='".$designation_id."'
			"))
			->map(function($rows) use($emp_status){
				$rows->status=$emp_status[$rows->status_id];
				$rows->date_of_join=date('d-M-Y',strtotime($rows->date_of_join));
				$rows->status_date=$rows->status_date?date('d-M-Y',strtotime($rows->status_date)):'--';
				return $rows;
			});
		echo json_encode($rows);
	}
}
