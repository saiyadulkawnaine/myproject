<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\HRM\EmployeeToDoListRepository;
use App\Repositories\Contracts\HRM\EmployeeToDoListTaskRepository;
use App\Repositories\Contracts\HRM\EmployeeToDoListTaskBarRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeToDoListTaskBarRequest;

class EmployeeToDoListTaskBarController extends Controller {

    private $employee;
    private $employeetodolist;
    private $employeetodolisttask;
    private $employeetodolisttaskbar;
    private $user;

    public function __construct(
      EmployeeRepository $employee,
      EmployeeToDoListRepository $employeetodolist,
      EmployeeToDoListTaskRepository $employeetodolisttask,
      EmployeeToDoListTaskBarRepository $employeetodolisttaskbar,
      UserRepository $user
    ) {
        $this->employee = $employee;
        $this->employeetodolist = $employeetodolist;
        $this->employeetodolisttask = $employeetodolisttask;
        $this->employeetodolisttaskbar = $employeetodolisttaskbar;
        $this->user = $user;
        $this->middleware('auth');
        //$this->middleware('permission:view.employees',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.employees', ['only' => ['store']]);
        //$this->middleware('permission:edit.employees',   ['only' => ['update']]);
        //$this->middleware('permission:delete.employees', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $rows=$this->employeetodolisttask
        ->leftJoin('employee_to_do_list_task_bars',function($join){
            $join->on('employee_to_do_list_task_bars.employee_to_do_list_task_id','=','employee_to_do_list_tasks.id');
        })
        ->where([['employee_to_do_list_tasks.id','=',request('employee_to_do_list_task_id',0)]])
        ->get([
        'employee_to_do_list_task_bars.*',
        ]);
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeToDoListTaskBarRequest $request) {
		$employeetodolisttaskbar=$this->employeetodolisttaskbar->create($request->except(['id']));
		if($employeetodolisttaskbar){
			return response()->json(array('success' => true,'id' =>  $employeetodolisttaskbar->id,'message' => 'Save Successfully'),200);
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
     $employeetodolisttaskbar = $this->employeetodolisttaskbar->find($id);
     
	   $row ['fromData'] = $employeetodolisttaskbar;
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
    public function update(EmployeeToDoListTaskBarRequest $request, $id) {
       $employeetodolisttaskbar=$this->employeetodolisttaskbar->update($id,$request->except(['id']));
		if($employeetodolisttaskbar){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
		} 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->employeetodolisttaskbar->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}
