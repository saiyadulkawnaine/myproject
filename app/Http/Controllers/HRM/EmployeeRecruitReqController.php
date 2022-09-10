<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeRecruitReqRepository;
use App\Repositories\Contracts\HRM\EmployeeBudgetPositionRepository;
use App\Repositories\Contracts\HRM\EmployeeBudgetRepository;
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
use App\Http\Requests\HRM\EmployeeRecruitReqRequest;
use GuzzleHttp\Client;

class EmployeeRecruitReqController extends Controller {

    private $employeerecruitreq;
    private $employeebudget;
    private $employeehr;
    private $designation;
    private $department;
    private $user;
    private $division;
    private $section;
    private $subsection;
    private $location;

    public function __construct(
        EmployeeRecruitReqRepository $employeerecruitreq,
        EmployeeBudgetRepository $employeebudget,
        EmployeeBudgetPositionRepository $employeebudgetposition,
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
        $this->employeerecruitreq = $employeerecruitreq;
        $this->employeebudgetposition = $employeebudgetposition;
        $this->employeebudget = $employeebudget;
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
        $this->middleware('permission:view.employeerecruitreqs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeerecruitreqs', ['only' => ['store']]);
        $this->middleware('permission:edit.employeerecruitreqs',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeerecruitreqs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');

        $rows=$this->employeerecruitreq
        ->join('employee_budget_positions',function($join){
            $join->on('employee_recruit_reqs.employee_budget_position_id','=','employee_budget_positions.id');
        })
        ->join('employee_budgets',function($join){
            $join->on('employee_budget_positions.employee_budget_id','=','employee_budgets.id');
        })
        ->join('divisions',function($join){
            $join->on('divisions.id','=','employee_budgets.division_id');
        })
        ->leftJoin('sections',function($join){
            $join->on('sections.id','=','employee_budgets.section_id');
        })
        ->leftJoin('subsections',function($join){
            $join->on('subsections.id','=','employee_budgets.subsection_id');
        })
        ->join('employee_h_rs',function($join){
            $join->on('employee_recruit_reqs.employee_h_r_id','=','employee_h_rs.id');
        })
        ->orderBy('employee_recruit_reqs.id','desc')
        ->get([
            'employee_recruit_reqs.*',
            'employee_h_rs.id as employee_h_r_id',
            'employee_budgets.company_id',
            'employee_budgets.location_id',
            'employee_budgets.department_id',
            'employee_budget_positions.designation_id',
            'employee_h_rs.code',
            'employee_h_rs.contact',
            'employee_h_rs.name',
            'divisions.name as division_name',
            'sections.name as section_name',
            'subsections.name as subsection_name',
        ])
        ->map(function($rows) use($designation,$department,$company,$location){
            $rows->post_date=date('d-M-Y',strtotime($rows->post_date));
            $rows->company_name=isset($company[$rows->company_id])?$company[$rows->company_id]:'';
            $rows->designation_name=isset($designation[$rows->designation_id])?$designation[$rows->designation_id]:'';
            $rows->department_name=isset($department[$rows->department_id])?$department[$rows->department_id]:'';
            $rows->location_name=isset($location[$rows->location_id])?$location[$rows->location_id]:'';
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
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'','');
        $purpose = array_prepend(config('bprs.purpose'),'-Select-','');
        $transportmode = array_prepend(config('bprs.transportmode'),'-Select-','');
        
		return Template::loadView('HRM.EmployeeRecruitReq', ['user'=>$user,/* 'userData'=>$userData, */'designation'=>$designation,'department'=>$department,'company'=>$company,'location'=>$location,'purpose'=>$purpose,'transportmode'=>$transportmode]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRecruitReqRequest $request) {
        $budgetposition=$this->employeebudgetposition
        ->join('employee_budgets',function($join){
            $join->on('employee_budgets.id','=','employee_budget_positions.employee_budget_id');
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
        ->where([['employee_budget_positions.id','=',$request->employee_budget_position_id]])
        ->get([
           'employee_budget_positions.id',
           'employee_budget_positions.no_of_post',
           'activeEmployee.no_of_active_employee'
        ])
        ->map(function($budgetposition){
            $budgetposition->vacancy_available=$budgetposition->no_of_post-$budgetposition->no_of_active_employee;
            return $budgetposition;
        })
        ->first();
        if ($request->no_of_required_position>$budgetposition->vacancy_available) {
            return response()->json(array('success' => false,'message' => 'Requisition Exceeded Available Vacancy'),200);
        }
        
		$employeerecruitreq=$this->employeerecruitreq->create([
            'employee_h_r_id'=>$request->employee_h_r_id,
            'employee_budget_position_id'=>$request->employee_budget_position_id,
            'requisition_date'=>$request->requisition_date,
            'date_of_join'=>$request->date_of_join,
            'no_of_required_position'=>$request->no_of_required_position,
            'age_limit'=>$request->age_limit,
            'justification'=>$request->justification,
            'person_specification'=>$request->person_specification,
            
        ]);
		if($employeerecruitreq){
			return response()->json(array('success' => true,'id' =>  $employeerecruitreq->id,'message' => 'Save Successfully'),200);
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
        $designationlevel = array_prepend(config('bprs.designationlevel'),'','');
        $employeerecruitreq = $this->employeerecruitreq
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
        ->map(function($employeerecruitreq) use($designationlevel){
            $employeerecruitreq->vacancy_available=$employeerecruitreq->budgeted_position-$employeerecruitreq->no_of_active_employee;
            $employeerecruitreq->designation_level_id=$designationlevel[$employeerecruitreq->designation_level_id];
            return $employeerecruitreq;
        })
        ->first();
	    $row ['fromData'] = $employeerecruitreq;
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

    public function update(EmployeeRecruitReqRequest $request, $id) {
        $approved=$this->employeerecruitreq->find($id);
        if($approved->approved_at){
          return response()->json(array('success' => false,  'message' => 'Requisition Approved, Update not Possible'), 200);
        }
        else 
        {
            $budgetposition=$this->employeebudgetposition
            ->join('employee_budgets',function($join){
                $join->on('employee_budgets.id','=','employee_budget_positions.employee_budget_id');
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
            ->where([['employee_budget_positions.id','=',$request->employee_budget_position_id]])
            ->get([
               'employee_budget_positions.id',
               'employee_budget_positions.no_of_post',
               'activeEmployee.no_of_active_employee'
            ])
            ->map(function($budgetposition){
                $budgetposition->vacancy_available=$budgetposition->no_of_post-$budgetposition->no_of_active_employee;
                return $budgetposition;
            })
            ->first();
            if ($request->no_of_required_position > $budgetposition->vacancy_available) {
                return response()->json(array('success' => false,'message' => 'Requisition Exceeded Available Vacancy'),200);
            }
            $employeerecruitreq=$this->employeerecruitreq->update($id,[
                'employee_h_r_id'=>$request->employee_h_r_id,
                'employee_budget_position_id'=>$request->employee_budget_position_id,
                'requisition_date'=>$request->requisition_date,
                'date_of_join'=>$request->date_of_join,
                'no_of_required_position'=>$request->no_of_required_position,
                'age_limit'=>$request->age_limit,
                'justification'=>$request->justification,
                'person_specification'=>$request->person_specification,
            ]);
            if($employeerecruitreq){
                return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
            }
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id) {
        if($this->employeerecruitreq->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getEmployeeBudget(){
        $yesno = array_prepend(config('bprs.yesno'),'','');
        $transportreq = array_prepend(config('bprs.transportrequired'),'','');
        $roomreq = array_prepend(config('bprs.roomrequired'),'','');
        $computer = array_prepend(config('bprs.computer'),'','');
        $designationlevel = array_prepend(config('bprs.designationlevel'),'','');
        $rows=$this->employeebudget
        ->join('employee_budget_positions',function($join){
            $join->on('employee_budgets.id','=','employee_budget_positions.employee_budget_id');
        })
        ->join('designations',function($join){
            $join->on('designations.id','=','employee_budget_positions.designation_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','employee_budgets.company_id');
        })
        ->join('departments',function($join){
            $join->on('departments.id','=','employee_budgets.department_id');
        })
        ->join('locations',function($join){
            $join->on('locations.id','=','employee_budgets.location_id');
        })
        ->join('divisions',function($join){
            $join->on('divisions.id','=','employee_budgets.division_id');
        })
        ->leftJoin('sections',function($join){
            $join->on('sections.id','=','employee_budgets.section_id');
        })
        ->leftJoin('subsections',function($join){
            $join->on('subsections.id','=','employee_budgets.subsection_id');
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
        ->when(request('company_id'), function ($q) {
            return $q->where('employee_budgets.company_id','=',request('company_id', 0));
        })
        ->when(request('designation_id'), function ($q) {
            return $q->where('employee_budgets.designation_id','=',request('designation_id', 0));
        })   
        ->when(request('department_id'), function ($q) {
            return $q->where('employee_budgets.department_id','=',request('department_id', 0));
        }) 
        ->orderBy('employee_budgets.id','desc')
        ->orderBy('employee_budget_positions.id','desc')
        ->get([
            'employee_budget_positions.*',
            'designations.name as designation_name',
            'designations.designation_level_id',
            'companies.name as company_name',
            'locations.name as location_name',
            'divisions.name as division_name',
            'departments.name as department_name',
            'sections.name as section_name',
            'subsections.name as subsection_name',
            'activeEmployee.no_of_active_employee'
        ])
        ->map(function($rows) use($yesno,$transportreq,$roomreq,$computer,$designationlevel){
            $rows->room_required_id=$roomreq[$rows->room_required_id];
            $rows->desk_required_id=$yesno[$rows->desk_required_id];
            $rows->intercom_required_id=$yesno[$rows->intercom_required_id];
            $rows->computer_required_id=$computer[$rows->computer_required_id];
            $rows->ups_required_id=$yesno[$rows->ups_required_id];
            $rows->printer_required_id=$yesno[$rows->printer_required_id];
            $rows->cell_phone_required_id=$yesno[$rows->cell_phone_required_id];
            $rows->sim_required_id=$yesno[$rows->sim_required_id];
            $rows->network_required_id=$yesno[$rows->network_required_id];
            $rows->transport_required_id=$transportreq[$rows->transport_required_id];
            $rows->designation_level_id=$designationlevel[$rows->designation_level_id];
            $rows->vacancy_available=$rows->no_of_post-$rows->no_of_active_employee;
            return $rows;
        });

        echo json_encode($rows);
    }

    public function getEmployee(){
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');

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
          'employee_h_rs.*',
        ])
        ->map(function($employeehr) use($company,$designation,$department){
          $employeehr->employee_name=$employeehr->name;
          $employeehr->company_id=$company[$employeehr->company_id];
          $employeehr->designation_name=isset($designation[$employeehr->designation_id])?$designation[$employeehr->designation_id]:'';
          $employeehr->department_name=isset($department[$employeehr->department_id])?$department[$employeehr->department_id]:'';
          $employeehr->location_name=isset($location[$employeehr->location_id])?$location[$employeehr->location_id]:'';
          $employeehr->address='';
          return $employeehr;
        });

        echo json_encode($employeehr);
    }

    public function requisitionFormPdf(){
        $id=request('id', 0);
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'','');
        $yesno = array_prepend(config('bprs.yesno'),'','');
        $transportreq = array_prepend(config('bprs.transportrequired'),'','');
        $roomreq = array_prepend(config('bprs.roomrequired'),'','');
        $computer = array_prepend(config('bprs.computer'),'','');
        $designationlevel = array_prepend(config('bprs.designationlevel'),'','');

        $rows=$this->employeerecruitreq
        ->join('employee_budget_positions',function($join){
            $join->on('employee_recruit_reqs.employee_budget_position_id','=','employee_budget_positions.id');
        })
        ->join('employee_budgets',function($join){
            $join->on('employee_budget_positions.employee_budget_id','=','employee_budgets.id');
        })
        ->join('divisions',function($join){
            $join->on('divisions.id','=','employee_budgets.division_id');
        })
        ->leftJoin('sections',function($join){
            $join->on('sections.id','=','employee_budgets.section_id');
        })
        ->leftJoin('subsections',function($join){
            $join->on('subsections.id','=','employee_budgets.subsection_id');
        })
        ->leftJoin('designations',function($join){
            $join->on('designations.id','=','employee_budget_positions.designation_id');
        })
        ->join('employee_h_rs',function($join){
            $join->on('employee_recruit_reqs.employee_h_r_id','=','employee_h_rs.id');
        })
        ->leftJoin('designations as reporting_designation',function($join){
            $join->on('reporting_designation.id','=','employee_h_rs.designation_id');
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
        ->leftJoin('users',function($join){
            $join->on('users.id','=','employee_recruit_reqs.created_by');
        })
        ->join('employee_h_rs as employee_user',function($join){
            $join->on('users.id','=','employee_user.user_id');
        })
        ->where([['employee_recruit_reqs.id','=',$id]])
        ->get([
           // 'employee_recruit_reqs.*',
            'employee_recruit_reqs.id as employee_recruit_req_id',
            'employee_recruit_reqs.requisition_date',
            'employee_recruit_reqs.date_of_join',
            'employee_recruit_reqs.no_of_required_position',
            'employee_recruit_reqs.age_limit',
            'employee_recruit_reqs.justification',
            'employee_recruit_reqs.person_specification',
            'employee_recruit_reqs.approved_at',
            'employee_h_rs.id as employee_h_r_id',
            'employee_budgets.company_id',
            'employee_budgets.department_id',
            'employee_budgets.division_id',
            'employee_budgets.location_id',
            'employee_budgets.section_id',
            'employee_budgets.subsection_id',
            'employee_budget_positions.*',
            'employee_h_rs.code',
            'employee_h_rs.contact',
            'employee_h_rs.name as employee_name',
            'divisions.name as division_name',
            'sections.name as section_name',
            'subsections.name as subsection_name',
            'activeEmployee.no_of_active_employee',
            'employee_user.name as user_name',
            'employee_user.contact as user_contact',
            'employee_user.designation_id as user_designation_id',
            'designations.designation_level_id',
            'reporting_designation.name as reporting_emp_designation',
        ])
        ->map(function($rows) use($designation,$department,$company,$location,$yesno,$transportreq,$roomreq,$computer,$designationlevel){
            $rows->room_required_id=$roomreq[$rows->room_required_id];
            $rows->desk_required_id=$yesno[$rows->desk_required_id];
            $rows->intercom_required_id=$yesno[$rows->intercom_required_id];
            $rows->computer_required_id=$computer[$rows->computer_required_id];
            $rows->ups_required_id=$yesno[$rows->ups_required_id];
            $rows->printer_required_id=$yesno[$rows->printer_required_id];
            $rows->cell_phone_required_id=$yesno[$rows->cell_phone_required_id];
            $rows->sim_required_id=$yesno[$rows->sim_required_id];
            $rows->network_required_id=$yesno[$rows->network_required_id];
            $rows->transport_required_id=$transportreq[$rows->transport_required_id];
            $rows->designation_level_id=$designationlevel[$rows->designation_level_id];
            $rows->date_of_join=$rows->date_of_join?date('d-M-Y',strtotime($rows->date_of_join)):'';
            $rows->requisition_date=$rows->requisition_date?date('d-M-Y',strtotime($rows->requisition_date)):'';
            $rows->company_name=isset($company[$rows->company_id])?$company[$rows->company_id]:'';
            $rows->designation_name=isset($designation[$rows->designation_id])?$designation[$rows->designation_id]:'';
            $rows->user_designation_name=isset($designation[$rows->user_designation_id])?$designation[$rows->user_designation_id]:'';
            $rows->department_name=isset($department[$rows->department_id])?$department[$rows->department_id]:'';
            $rows->location_name=isset($location[$rows->location_id])?$location[$rows->location_id]:'';
            $rows->vacancy_available=$rows->no_of_post-$rows->no_of_active_employee;
            return $rows;

        })
        ->first();

        $employeerecruitreqreplace=$this->employeerecruitreq
        ->join('employee_recruit_req_replaces',function($join){
            $join->on('employee_recruit_req_replaces.employee_recruit_req_id','=','employee_recruit_reqs.id');
        })
        ->join('employee_h_rs',function($join){
            $join->on('employee_recruit_req_replaces.employee_h_r_id','=','employee_h_rs.id');
        })
        ->where([['employee_recruit_reqs.id','=',$id]])
        ->orderBy('employee_recruit_req_replaces.id','desc')
        ->get([
            'employee_recruit_req_replaces.*',
            'employee_h_rs.id as employee_h_r_id',
            'employee_h_rs.name as employee_name',
        ]);

        $arrReplace=array();
        foreach($employeerecruitreqreplace as $data){
            $arrReplace[$data->employee_recruit_req_id][]="ERP Id:".$data->employee_h_r_id." ".$data->employee_name;
        }
//dd($arrReplace);die;
        $rows->replaced_employee=implode(',',$arrReplace[$rows->employee_recruit_req_id]);

        $recruitreqjob=$this->employeerecruitreq
        ->join('employee_recruit_req_jobs',function($join){
            $join->on('employee_recruit_req_jobs.employee_recruit_req_id','=','employee_recruit_reqs.id');
        })
        ->where([['employee_recruit_reqs.id','=',$id]])
        ->orderBy('employee_recruit_req_jobs.sort_id','asc')
        ->get([
            'employee_recruit_req_jobs.*'
        ]);

       $company=$this->company
       ->where([['id','=',$rows->company_id]])
       ->get()->first();

    //    dd($company);die;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(10, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->AddPage();
        $pdf->SetY(15);
        $image_file ='images/logo/'.$company->logo;
        $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(20);
        $pdf->SetFont('helvetica', 'N', 8);
        $pdf->Text(70, 12, $company->address);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetDrawColor(191);
        $pdf->SetFillColor(127);
        $pdf->SetTextColor(127);
        $pdf->Text(10, 5, "Date: ".$rows->requisition_date);
        $pdf->SetFont('helvetica', 'N', 8);
        $pdf->SetDrawColor(0, 0, 0, 50);
        $pdf->SetFillColor(0, 0, 0, 100);
        $pdf->SetTextColor(0, 0, 0, 100);
       // $pdf->SetY(10);
        $pdf->SetY(16);
        //$pdf->AddPage();
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
        $pdf->SetX(150);
        $challan=str_pad($id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
       
        $pdf->SetFont('helvetica', 'N', 10);
        $view= \View::make('Defult.HRM.EmployeeRequisitionFormPdf',['rows'=>$rows,'recruitreqjob'=>$recruitreqjob]);
        $html_content=$view->render();
        $pdf->SetY(25);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/EmployeeRequisitionFormPdf.pdf';
        $pdf->output($filename);
    }
}