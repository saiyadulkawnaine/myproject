<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\HRM\EmployeeToDoListRepository;
use App\Repositories\Contracts\HRM\EmployeeToDoListTaskRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeToDoListTaskRequest;

class EmployeeToDoListTaskController extends Controller {

    private $employee;
    private $employeetodolist;
    private $employeetodolisttask;
    private $user;

    public function __construct(
      EmployeeRepository $employee,
      EmployeeToDoListRepository $employeetodolist,
      EmployeeToDoListTaskRepository $employeetodolisttask,
      UserRepository $user
    ) {
        $this->employee = $employee;
        $this->employeetodolist = $employeetodolist;
        $this->employeetodolisttask = $employeetodolisttask;
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
        $todopriority=array_prepend(config('bprs.todopriority'), '-Select-','');
        $rows=$this->employeetodolist
        ->leftJoin('employee_to_do_list_tasks',function($join){
            $join->on('employee_to_do_list_tasks.employee_to_do_list_id','=','employee_to_do_lists.id');
        })
        ->where([['employee_to_do_lists.id','=',request('employee_to_do_list_id',0)]])
        ->orderBy('employee_to_do_list_tasks.id','desc')
        ->get([
        'employee_to_do_list_tasks.*',
        ])
        ->map(function($rows) use($todopriority){
            $rows->priority_id=$todopriority[$rows->priority_id];
            $rows->start_date=date('d-M-Y',strtotime($rows->start_date));
            $rows->end_date=date('d-M-Y',strtotime($rows->end_date));
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeToDoListTaskRequest $request) {
		$employeetodolisttask=$this->employeetodolisttask->create($request->except(['id']));
		if($employeetodolisttask){
			return response()->json(array('success' => true,'id' =>  $employeetodolisttask->id,'message' => 'Save Successfully'),200);
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
     $employeetodolisttask = $this->employeetodolisttask->find($id);
     
	   $row ['fromData'] = $employeetodolisttask;
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
    public function update(EmployeeToDoListTaskRequest $request, $id) {
       $employeetodolisttask=$this->employeetodolisttask->update($id,$request->except(['id']));
		if($employeetodolisttask){
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
        if($this->employeetodolisttask->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}
