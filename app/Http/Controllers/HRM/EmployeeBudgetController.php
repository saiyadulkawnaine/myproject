<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeBudgetRepository;
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
use App\Http\Requests\HRM\EmployeeBudgetRequest;
use GuzzleHttp\Client;

class EmployeeBudgetController extends Controller {

    private $employeebudget;
    private $designation;
    private $department;
    private $user;
    private $division;
    private $section;
    private $subsection;
    private $location;

    public function __construct(
        EmployeeBudgetRepository $employeebudget,
        DesignationRepository $designation,
        DepartmentRepository $department,
        DivisionRepository $division,
        SectionRepository $section,
        SubsectionRepository $subsection,
        CompanyRepository $company,
        UserRepository $user,
        LocationRepository $location
    ) {
        $this->employeebudget = $employeebudget;
        $this->designation = $designation;
        $this->department = $department;
        $this->division = $division;
        $this->section = $section;
        $this->subsection = $subsection;
        $this->company = $company;
        $this->location = $location;
        $this->user = $user;

        $this->middleware('auth');
        $this->middleware('permission:view.employeebudgets',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeebudgets', ['only' => ['store']]);
        $this->middleware('permission:edit.employeebudgets',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeebudgets', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $employeebudgets=array();
        $rows=$this->employeebudget
        ->join('companies',function($join){
            $join->on('companies.id','=','employee_budgets.company_id');
        })
        ->join('departments',function($join){
            $join->on('departments.id','=','employee_budgets.department_id');
        })
        ->leftJoin('locations',function($join){
            $join->on('locations.id','=','employee_budgets.location_id');
        })
        ->leftJoin('divisions',function($join){
            $join->on('divisions.id','=','employee_budgets.division_id');
        })
        ->leftJoin('sections',function($join){
            $join->on('sections.id','=','employee_budgets.section_id');
        })
        ->leftJoin('subsections',function($join){
            $join->on('subsections.id','=','employee_budgets.subsection_id');
        })
        ->orderBy('employee_budgets.id','desc')
        ->get([
            'employee_budgets.*',
            'companies.name as company_name',
            'locations.name as location_name',
            'divisions.name as division_name',
            'departments.name as department_name',
            'sections.name as section_name',
            'subsections.name as subsection_name',
        ]);
       
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
        $transportreq = array_prepend(config('bprs.transportrequired'),'-Select-','');
        $roomreq = array_prepend(config('bprs.roomrequired'),'-Select-','');
        $computer = array_prepend(config('bprs.computer'),'-Select-','');
        $designationlevel = array_prepend(config('bprs.designationlevel'),'-Select-','');
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $division=array_prepend(array_pluck($this->division->get(),'name','id'),'-Select-','');
        $section=array_prepend(array_pluck($this->section->get(),'name','id'),'','');
        $subsection=array_prepend(array_pluck($this->subsection->get(),'name','id'),'','');
		return Template::loadView('HRM.EmployeeBudget', ['designation'=>$designation,'department'=>$department,'yesno'=>$yesno,'transportreq'=>$transportreq,'company'=>$company,'status'=>$status,'location'=>$location,'division'=>$division,'section'=>$section,
        'subsection'=>$subsection,'roomreq'=>$roomreq, 'computer'=>$computer,'designationlevel'=>$designationlevel]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeBudgetRequest $request) {
        $prevBudget=$this->employeebudget
        ->where([['company_id','=',$request->company_id]])
        ->where([['department_id','=',$request->department_id]])
        ->where([['division_id','=',$request->division_id]])
        ->where([['location_id','=',$request->location_id]])
        ->where([['section_id','=',$request->section_id]])
        ->where([['subsection_id','=',$request->subsection_id]])
        ->get()->first();

        if ($prevBudget) {
            return response()->json(array('success' => false,'message' => 'Employee Budget found'),200);
        }
    	$employeebudget=$this->employeebudget->create($request->except(['id']));
        if($employeebudget){
            return response()->json(array('success' => true,'id' =>  $employeebudget->id,'message' => 'Save Successfully'),200);
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
		$employeebudget = $this->employeebudget->find($id);
		$row ['fromData'] = $employeebudget;
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
    public function update(EmployeeBudgetRequest $request, $id) {
        $prevBudgetRequisition=$this->employeebudget
        ->join('employee_budget_positions',function($join){
            $join->on('employee_budget_positions.employee_budget_id','=','employee_budgets.id');
        })
        ->join('employee_recruit_reqs',function($join){
            $join->on('employee_recruit_reqs.employee_budget_position_id','=','employee_budget_positions.id');
        })
        ->find($id);
        if ($prevBudgetRequisition) {
            return response()->json(array('success' => false,'id' => $id,'message' => 'Update not possible.Requisition found'),200);
        }
       $employeebudget=$this->employeebudget->update($id,$request->except(['id','company_id']));

        if($employeebudget){
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
        if($this->employeebudget->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}