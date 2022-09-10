<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\HRM\EmployeeToDoListRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeToDoListRequest;

class EmployeeToDoListController extends Controller {

    private $employee;
    private $user;

    public function __construct(
      EmployeeRepository $employee,
      EmployeeToDoListRepository $employeetodolist,
      UserRepository $user
    ) {
        $this->employee = $employee;
        $this->employeetodolist = $employeetodolist;
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
        $user = \Auth::user();
        $rows=$this->employeetodolist
        ->leftJoin('users',function($join){
          $join->on('users.id','=','employee_to_do_lists.user_id');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->leftJoin('departments',function($join){
          $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->where([['users.id','=',$user->id]])
        ->orderBy('employee_to_do_lists.id','desc')
        ->get([
          'employee_to_do_lists.*',
          'users.name as user_name',
          'users.id as user_id',
          'departments.name as department_name',
        //'locations.name as location_name',
        ])
        ->map(function($rows){
          $rows->exec_date=date('d-M-Y',strtotime($rows->exec_date));
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
      $user = \Auth::user();
      $userData=$this->user
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
      })
      /*->leftJoin('locations',function($join){
        $join->on('locations.id','=','employee_h_rs.location_id');
      })*/
      ->where([['users.id','=',$user->id]])
      ->get([
      'users.name as user_name',
      'users.id as user_id',
      'departments.name as department_name',
      //'locations.name as location_name',
      ])
      ->first();
      $todopriority=array_prepend(config('bprs.todopriority'), '-Select-','');

		return Template::loadView('HRM.EmployeeToDoList', ['userData'=>$userData,'todopriority'=>$todopriority]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeToDoListRequest $request) {
		$employeetodolist=$this->employeetodolist->create($request->except(['id']));
		if($employeetodolist){
			return response()->json(array('success' => true,'id' =>  $employeetodolist->id,'message' => 'Save Successfully'),200);
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
     //$employeetodolist = $this->employeetodolist->find($id);
      $employeetodolist=$this->employeetodolist
      ->leftJoin('users',function($join){
        $join->on('users.id','=','employee_to_do_lists.user_id');
        })
        ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->leftJoin('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->where([['employee_to_do_lists.id','=',$id]])
        ->get([
        'employee_to_do_lists.*',
        'users.name as user_name',
        'users.id as user_id',
        'departments.name as department_name',
        //'locations.name as location_name',
        ])
        ->first();
	   $row ['fromData'] = $employeetodolist;
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
    public function update(EmployeeToDoListRequest $request, $id) {
       $employeetodolist=$this->employeetodolist->update($id,$request->except(['id']));
		if($employeetodolist){
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
        if($this->employeetodolist->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}
