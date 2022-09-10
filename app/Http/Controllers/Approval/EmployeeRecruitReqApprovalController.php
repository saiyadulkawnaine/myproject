<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeRecruitReqRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Sms;
use GuzzleHttp\Client;

class EmployeeRecruitReqApprovalController extends Controller
{
    private $employeerecruitreq;
    private $user;
    private $buyer;
    private $company;

    public function __construct(
      EmployeeRecruitReqRepository $employeerecruitreq,
		  UserRepository $user,
		  CompanyRepository $company

    ) {
        $this->employeerecruitreq = $employeerecruitreq;
        $this->user = $user;
        $this->company = $company;
        $this->middleware('auth');
        //$this->middleware('permission:approve.employeerecruitreqs',   ['only' => ['approved', 'index','reportData']]);

    }

    public function index() {
		  $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      return Template::loadView('Approval.EmployeeRecruitReqApproval',['company'=>$company]);
    }

	  public function reportData() {
      $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
      $designationlevel = array_prepend(config('bprs.designationlevel'),'-Select-','');

      $rows=$this->employeerecruitreq
      ->join('employee_budget_positions',function($join){
          $join->on('employee_recruit_reqs.employee_budget_position_id','=','employee_budget_positions.id');
      })
      ->join('employee_budgets',function($join){
          $join->on('employee_budget_positions.employee_budget_id','=','employee_budgets.id');
      })
      ->join('employee_h_rs',function($join){
          $join->on('employee_recruit_reqs.employee_h_r_id','=','employee_h_rs.id');
      })
      ->leftJoin('users',function($join){
          $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('companies',function($join){
          $join->on('companies.id','=','employee_budgets.company_id');
      })
      ->leftJoin('departments',function($join){
          $join->on('departments.id','=','employee_budgets.department_id');
      })
      ->leftJoin('designations',function($join){
          $join->on('designations.id','=','employee_budget_positions.designation_id');
      })
      ->leftJoin('locations',function($join){
          $join->on('locations.id','=','employee_budgets.location_id');
      })
      ->leftJoin('sections',function($join){
          $join->on('sections.id','=','employee_budgets.section_id');
      })
      ->leftJoin('subsections',function($join){
          $join->on('subsections.id','=','employee_budgets.subsection_id');
      })
      ->leftJoin('divisions',function($join){
          $join->on('divisions.id','=','employee_budgets.division_id');
      })
      ->leftJoin(\DB::raw("(select
          employee_h_rs.company_id,
          employee_h_rs.department_id,
          employee_h_rs.division_id,
          employee_h_rs.location_id,
          employee_h_rs.designation_id,
          count(employee_h_rs.status_id) as no_of_active_employee
      from
      employee_h_rs
      where employee_h_rs.status_id = 1
      group by
      employee_h_rs.company_id,
      employee_h_rs.department_id,
      employee_h_rs.division_id,
      employee_h_rs.location_id,
      employee_h_rs.designation_id) activeEmployee"),
      [
          ['activeEmployee.company_id','=','employee_budgets.company_id'],
          ['activeEmployee.department_id','=','employee_budgets.department_id'],
          ['activeEmployee.division_id','=','employee_budgets.division_id'],
          ['activeEmployee.location_id','=','employee_budgets.location_id'],
          ['activeEmployee.designation_id','=','employee_budget_positions.designation_id'],
      ])
      ->orderBy('employee_recruit_reqs.id','desc')
      ->when(request('company_id'), function ($q) {
        return $q->where('employee_budgets.company_id','=',request('company_id', 0));
      })
      ->when(request('date_from'), function ($q) {
          return $q->where('employee_h_rs.requisition_date','>=',request('date_from', 0));
      })   
      ->when(request('date_to'), function ($q) {
          return $q->where('employee_h_rs.requisition_date','=',request('date_to', 0));
      })
      ->whereNull('employee_recruit_reqs.approved_at')
      ->whereNull('employee_recruit_reqs.approved_by')
      ->get([
          'employee_recruit_reqs.*',
          'employee_h_rs.id as employee_h_r_id',
          'employee_budgets.company_id',
          'employee_budgets.location_id',
          'employee_budgets.department_id',
          'employee_budget_positions.designation_id',
          'employee_budget_positions.no_of_post as budgeted_position',
          'employee_budget_positions.grade',
          'employee_h_rs.code',
          'employee_h_rs.contact',
          'employee_h_rs.name as employee_name',
          'companies.name as company_name',
          'departments.name as department_name',
          'designations.name as designation_name',
          'designations.designation_level_id',
          'locations.name as location_name',
          'divisions.name as division_name',
          'sections.name as section_name',
          'subsections.name as subsection_name',
          'activeEmployee.no_of_active_employee'
      ])
      ->map(function($rows) use($designationlevel){
        $rows->requisition_date=date('d-M-Y',strtotime($rows->requisition_date));
        $rows->date_of_join=date('d-M-Y',strtotime($rows->date_of_join));
        $rows->vacancy_available=$rows->budgeted_position-$rows->no_of_active_employee;
        $rows->designation_level_id=$designationlevel[$rows->designation_level_id];
        return $rows;
      });
      
      echo json_encode($rows);
  }

  public function approved (Request $request)
  {
    	$id=request('id',0);
    	$master=$this->employeerecruitreq->find($id);
		  $user = \Auth::user();
		  $approved_at=date('Y-m-d h:i:s');
      $master->approved_by=$user->id;
      $master->approved_at=$approved_at;
      $master->timestamps=false;
      $employeerecruitreq=$master->save();

      $designationlevel = array_prepend(config('bprs.designationlevel'),'-Select-','');

      $emp=$this->employeerecruitreq
      ->join('employee_budget_positions',function($join){
        $join->on('employee_recruit_reqs.employee_budget_position_id','=','employee_budget_positions.id');
      })
      ->join('employee_budgets',function($join){
        $join->on('employee_budget_positions.employee_budget_id','=','employee_budgets.id');
      })
      ->join('employee_h_rs',function($join){
        $join->on('employee_recruit_reqs.employee_h_r_id','=','employee_h_rs.id');
      })
      ->leftJoin('users',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('companies',function($join){
        $join->on('companies.id','=','employee_budgets.company_id');
      })
      ->leftJoin('departments',function($join){
        $join->on('departments.id','=','employee_budgets.department_id');
      })
      ->leftJoin('designations',function($join){
        $join->on('designations.id','=','employee_budget_positions.designation_id');
      })
      ->leftJoin('locations',function($join){
        $join->on('locations.id','=','employee_budgets.location_id');
      })
      ->leftJoin('sections',function($join){
        $join->on('sections.id','=','employee_budgets.section_id');
      })
      ->leftJoin('subsections',function($join){
        $join->on('subsections.id','=','employee_budgets.subsection_id');
      })
      ->leftJoin('divisions',function($join){
        $join->on('divisions.id','=','employee_budgets.division_id');
      })
      ->leftJoin(\DB::raw("(select
        employee_h_rs.company_id,
        employee_h_rs.department_id,
        employee_h_rs.division_id,
        employee_h_rs.location_id,
        employee_h_rs.designation_id,
        count(employee_h_rs.status_id) as no_of_active_employee
      from
      employee_h_rs
      where employee_h_rs.status_id = 1
      group by
      employee_h_rs.company_id,
      employee_h_rs.department_id,
      employee_h_rs.division_id,
      employee_h_rs.location_id,
      employee_h_rs.designation_id) activeEmployee"),
      [
        ['activeEmployee.company_id','=','employee_budgets.company_id'],
        ['activeEmployee.department_id','=','employee_budgets.department_id'],
        ['activeEmployee.division_id','=','employee_budgets.division_id'],
        ['activeEmployee.location_id','=','employee_budgets.location_id'],
        ['activeEmployee.designation_id','=','employee_budget_positions.designation_id'],
      ])
      ->where([['employee_recruit_reqs.id','=',$id]])
      ->get([
        'employee_recruit_reqs.*',
        'employee_h_rs.id as employee_h_r_id',
        'employee_budgets.company_id',
        'employee_budgets.location_id',
        'employee_budgets.department_id',
        'employee_budget_positions.designation_id',
        'employee_budget_positions.no_of_post as budgeted_position',
        'employee_budget_positions.grade',
        'employee_h_rs.code',
        'employee_h_rs.contact',
        'employee_h_rs.name as employee_name',
        'companies.name as company_name',
        'departments.name as department_name',
        'designations.name as designation_name',
        'designations.designation_level_id',
        'locations.name as location_name',
        'divisions.name as division_name',
        'sections.name as section_name',
        'subsections.name as subsection_name',
        'activeEmployee.no_of_active_employee'
      ])
      ->map(function($emp) use($designationlevel){
        $emp->requisition_date=date('d-M-Y',strtotime($emp->requisition_date));
        $emp->date_of_join=date('d-M-Y',strtotime($emp->date_of_join));
        $emp->vacancy_available=$emp->budgeted_position-$emp->no_of_active_employee;
        $emp->designation_level_id=$designationlevel[$emp->designation_level_id];
        return $emp;
      })
      ->first();
       

		if($employeerecruitreq){
		  return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
		}
  }

  public function getEmpReplaced(){
    $id=request('id',0);
    $rows=$this->employeerecruitreq
    ->join('employee_recruit_req_replaces',function($join){
      $join->on('employee_recruit_req_replaces.employee_recruit_req_id','=','employee_recruit_reqs.id');
    })
    ->join('employee_h_rs',function($join){
      $join->on('employee_h_rs.id','=','employee_recruit_req_replaces.employee_h_r_id');
    })
    ->leftJoin('designations',function($join){
      $join->on('designations.id','=','employee_h_rs.designation_id');
    })
    ->where([['employee_recruit_reqs.id','=',$id]])
    ->get([
      'employee_recruit_req_replaces.*',
      'employee_h_rs.name as employee_name',
      'designations.name as designation_name',
    ]);

    //dd($rows);

    echo json_encode($rows);

  }

  public function getEmpRecruitReqJobDesc(){
    $id=request('id',0);
    $rows=$this->employeerecruitreq
    ->join('employee_recruit_req_jobs',function($join){
      $join->on('employee_recruit_req_jobs.employee_recruit_req_id','=','employee_recruit_reqs.id');
    })
    ->where([['employee_recruit_reqs.id','=',$id]])
    ->get([
      'employee_recruit_req_jobs.*',
    ]);

    echo json_encode($rows);
  }
    
}
