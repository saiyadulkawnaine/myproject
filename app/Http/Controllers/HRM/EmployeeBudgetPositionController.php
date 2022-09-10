<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeBudgetRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\HRM\EmployeeBudgetPositionRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeBudgetPositionRequest;

class EmployeeBudgetPositionController extends Controller {

    private $employeebudgetposition;

    public function __construct(EmployeeBudgetRepository $employeebudget,
    DesignationRepository $designation,EmployeeBudgetPositionRepository $employeebudgetposition) {
        $this->employeebudgetposition = $employeebudgetposition;
        $this->employeebudget = $employeebudget;
        $this->designation = $designation;

        $this->middleware('auth');
        $this->middleware('permission:view.employeebudgetpositions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeebudgetpositions', ['only' => ['store']]);
        $this->middleware('permission:edit.employeebudgetpositions',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeebudgetpositions', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $yesno = array_prepend(config('bprs.yesno'),'','');
        $transportreq = array_prepend(config('bprs.transportrequired'),'','');
        $roomreq = array_prepend(config('bprs.roomrequired'),'','');
        $computer = array_prepend(config('bprs.computer'),'','');
        $designationlevel = array_prepend(config('bprs.designationlevel'),'','');
        $rows=$this->employeebudgetposition
        ->join('designations',function($join){
            $join->on('designations.id','=','employee_budget_positions.designation_id');
        })
        ->where([['employee_budget_id','=',request('employee_budget_id',0)]])
        ->orderBy('employee_budget_positions.id','asc')
        ->get([
            'employee_budget_positions.*',
            'designations.name as designation_name',
            'designations.designation_level_id',
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeBudgetPositionRequest $request) {

        $empbud=$this->employeebudgetposition
        ->where([['employee_budget_id','=',$request->employee_budget_id]])
        ->where([['designation_id','=',$request->designation_id]])
        ->get()->first();

        if ($empbud) {
            return response()->json(array('success' => false,'message' => 'Budgeted Designation found'),200);
        }
		$employeebudgetposition=$this->employeebudgetposition->create($request->except(['id','designation_name','designation_level_id']));
		if($employeebudgetposition){
			return response()->json(array('success' => true,'id' =>  $employeebudgetposition->id,'message' => 'Save Successfully'),200);
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
       $employeebudgetposition = $this->employeebudgetposition
       ->join('designations',function($join){
            $join->on('designations.id','=','employee_budget_positions.designation_id');
        })
       ->where([['employee_budget_positions.id','=',$id]])
       ->get([
           'employee_budget_positions.*',
           'designations.name as designation_name',
           'designations.designation_level_id',
           'designations.grade',
       ])
       ->first();
	   $row ['fromData'] = $employeebudgetposition;
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
    public function update(EmployeeBudgetPositionRequest $request, $id) {
       $employeebudgetposition=$this->employeebudgetposition->update($id,$request->except(['id','designation_name','designation_id','designation_level_id']));
		if($employeebudgetposition){
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
        if($this->employeebudgetposition->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getDesignation(){
        $designationlevel=array_prepend(config('bprs.designationlevel'),'-Select-','');
        $employeecategory=array_prepend(config('bprs.employeecategory'),'--','');

        $rows=$this->designation
        ->when(request('id'), function ($q) {
          return $q->where('designations.id', '=', request('id', 0));
        })
        ->orderBy('designations.id','desc')
        ->get(['designations.*'])
        ->map(function($rows) use($designationlevel,$employeecategory){
            $rows->designation_level=$designationlevel[$rows->designation_level_id];
            $rows->employee_category=$employeecategory[$rows->employee_category_id];
            return $rows;
        });
        echo json_encode($rows);
    }
}