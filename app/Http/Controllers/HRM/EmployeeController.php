<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeRequest;

class EmployeeController extends Controller {

    private $employee;
    private $designation;
    private $department;

    public function __construct(EmployeeRepository $employee,DesignationRepository $designation,DepartmentRepository $department) {
        $this->employee = $employee;
        $this->designation = $designation;
        $this->department = $department;

        $this->middleware('auth');
        $this->middleware('permission:view.employees',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employees', ['only' => ['store']]);
        $this->middleware('permission:edit.employees',   ['only' => ['update']]);
        $this->middleware('permission:delete.employees', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $employees=array();
        $rows=$this->employee->get();
        foreach($rows as $row){
           $employee['id']=$row->id; 
           $employee['name']=$row->name; 
           $employee['code']=$row->code; 
           $employee['designation_id']=isset($designation[$row->designation_id])?$designation[$row->designation_id]:''; 
           $employee['department_id']=isset($department[$row->department_id])?$department[$row->department_id]:''; 
           $employee['grade']=$row->grade; 
           $employee['date_of_join']=($row->date_of_join !== null)?date("Y-m-d",strtotime($row->date_of_join)):null;
           $employee['date_of_birth']=($row->date_of_birth !== null)?date("Y-m-d",strtotime($row->date_of_birth)):null; 
           $employee['national_id']=$row->national_id; 
           $employee['address']=$row->address; 
           $employee['salary']=$row->salary; 
           $employee['last_education']=$row->last_education; 
           $employee['experience']=$row->experience; 
           $employee['tin']=$row->tin;
		      $employee['email']=$row->email;
		      $employee['contact']=$row->contact;
        array_push($employees,$employee);
        }
        echo json_encode($employees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
      $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'','');
      $department=array_prepend(array_pluck($this->department->get(),'name','id'),'','');
		return Template::loadView('HRM.Employee', ['designation'=>$designation,'department'=>$department,'status'=>$status]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request) {
		$employee=$this->employee->create($request->except(['id']));
		if($employee){
			return response()->json(array('success' => true,'id' =>  $employee->id,'message' => 'Save Successfully'),200);
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
       $employee = $this->employee->find($id);
	   $row ['fromData'] = $employee;
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
    public function update(EmployeeRequest $request, $id) {
       $employee=$this->employee->update($id,$request->except(['id']));
		if($employee){
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
        if($this->employee->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getEmployee() {

        //echo "monzu";die;
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $employees=array();
        $rows=$this->employee
        ->when(request('name'), function ($q) {
        return $q->where('name', 'like','%'.request('name', 0).'%');
        })
        ->when(request('code'), function ($q) {
        return $q->where('code', '=', request('code', 0));
        })
        ->when(request('contact'), function ($q) {
        return $q->where('contact', '=', request('contact', 0));
        })
         ->when(request('email'), function ($q) {
        return $q->where('email', '=', request('email', 0));
        })
         ->orderBy('name','asc')
        ->get();
        foreach($rows as $row){
           $employee['id']=$row->id; 
           $employee['name']=$row->name; 
           $employee['code']=$row->code; 
           $employee['designation_id']=$designation[$row->designation_id]; 
           $employee['department_id']=$department[$row->department_id]; 
           $employee['grade']=$row->grade; 
           $employee['date_of_join']=date('Y-m-d',strtotime($row->date_of_join)); 
           $employee['date_of_birth']=date('Y-m-d',strtotime($row->date_of_birth)); 
           $employee['national_id']=$row->national_id; 
           $employee['address']=$row->address; 
           $employee['salary']=$row->salary; 
           $employee['last_education']=$row->last_education; 
           $employee['experience']=$row->experience; 
           $employee['tin']=$row->tin;
           $employee['email']=$row->email;
           $employee['contact']=$row->contact;
        array_push($employees,$employee);
        }
        echo json_encode($employees);
    }

}
