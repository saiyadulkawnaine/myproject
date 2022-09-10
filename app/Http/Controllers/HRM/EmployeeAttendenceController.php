<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeAttendenceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeAttendenceRequest;
use GuzzleHttp\Client;

class EmployeeAttendenceController extends Controller {

    private $attendence;
    private $company;

    public function __construct(EmployeeAttendenceRepository $attendence,CompanyRepository $company) {
        $this->attendence = $attendence;
        $this->company = $company;

        $this->middleware('auth');
        /* $this->middleware('permission:view.employeeattendences',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeeattendences', ['only' => ['store']]);
        $this->middleware('permission:edit.employeeattendences',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeeattendences', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        

        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $attendences=array();
        $rows=$this->attendence
        ->orderBy('employee_attendences.id','desc')
        ->get();
        foreach($rows as $row){
        $attendence['id']=$row->id; 
        $attendence['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:''; 
        $attendence['attendence_date']=date("Y-m-d",strtotime($row->attendence_date));
        $attendence['operator']=$row->operator; 
        $attendence['helper']=$row->helper; 
        $attendence['prod_staff']=$row->prod_staff; 
        $attendence['supporting_staff']=$row->supporting_staff; 
        array_push($attendences,$attendence);
        }
        echo json_encode($attendences);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		return Template::loadView('HRM.EmployeeAttendence', ['company'=>$company]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeAttendenceRequest $request) {
		$attendence=$this->attendence->create($request->except(['id']));
		if($attendence){
			return response()->json(array('success' => true,'id' =>  $attendence->id,'message' => 'Save Successfully'),200);
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
       $attendence = $this->attendence->find($id);
       $attendence['attendence_date']=date("Y-m-d",strtotime($attendence->attendence_date));
	   $row ['fromData'] = $attendence;
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
    public function update(EmployeeAttendenceRequest $request, $id) {
       $attendence=$this->attendence->update($id,$request->except(['id']));
		if($attendence){
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
        if($this->attendence->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
