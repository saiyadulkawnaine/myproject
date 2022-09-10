<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeRecruitReqReplaceRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Library\Sms;
use App\Library\Numbertowords;
use App\Http\Requests\HRM\EmployeeRecruitReqReplaceRequest;

class EmployeeRecruitReqReplaceController extends Controller {

    private $employeerecruitreqreplace;
    private $employeehr;
    private $designation;
    private $department;
    private $location;
    private $user;

    public function __construct(
        EmployeeHRRepository $employeehr, 
        EmployeeRecruitReqReplaceRepository $employeerecruitreqreplace,
        DesignationRepository $designation, 
        DepartmentRepository $department,
        CompanyRepository $company, 
        UserRepository $user, 
        LocationRepository $location
    ) {
        $this->employeehr = $employeehr;
        $this->employeerecruitreqreplace = $employeerecruitreqreplace;
        $this->user = $user;
        $this->designation = $designation;
        $this->department = $department;
        $this->company = $company;
        $this->location = $location;

        $this->middleware('auth');
        $this->middleware('permission:view.employeerecruitreqreplaces',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeerecruitreqreplaces', ['only' => ['store']]);
        $this->middleware('permission:edit.employeerecruitreqreplaces',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeerecruitreqreplaces', ['only' => ['destroy']]);
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
       $user = \Auth::user();
        $rows=$this->employeerecruitreqreplace
        ->join('employee_h_rs',function($join){
            $join->on('employee_recruit_req_replaces.employee_h_r_id','=','employee_h_rs.id');
        })
        ->leftJoin('users',function($join){
            $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->where([['employee_recruit_req_replaces.created_by','=',$user->id]])
        ->orderBy('employee_recruit_req_replaces.id','desc')
        ->get([
            'employee_recruit_req_replaces.*',
            'employee_h_rs.company_id',
            'employee_h_rs.location_id',
            'employee_h_rs.department_id',
            'employee_h_rs.designation_id',
            'employee_h_rs.code',
            'employee_h_rs.contact',
            'employee_h_rs.name as employee_name',
        ])
        ->map(function($rows) use($designation,$department,$company,$location){
            $rows->post_date=date('d-M-Y',strtotime($rows->post_date));
            $rows->company_name=isset($company[$rows->company_id])?$company[$rows->company_id]:'';
            $rows->designation_name=isset($designation[$rows->designation_id])?$designation[$rows->designation_id]:'';
            $rows->department_name=isset($department[$rows->department_id])?$department[$rows->department_id]:'';
            $rows->location_id=isset($location[$rows->location_id])?$location[$rows->location_id]:'';
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
    public function store(EmployeeRecruitReqReplaceRequest $request) {
		$employeerecruitreqreplace=$this->employeerecruitreqreplace->create([
            'employee_h_r_id'=>$request->employee_h_r_id, 
            'employee_recruit_req_id'=>$request->employee_recruit_req_id, 
        ]);
		if($employeerecruitreqreplace){
			return response()->json(array('success' => true,'id' =>  $employeerecruitreqreplace->id,'message' => 'Save Successfully'),200);
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

        $employeerecruitreqreplace = $this->employeerecruitreqreplace
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_recruit_req_replaces.employee_h_r_id','=','employee_h_rs.id');
        })
        ->leftJoin('designations',function($join){
            $join->on('designations.id','=','employee_h_rs.designation_id');
        })
        ->where([['employee_recruit_req_replaces.id','=',$id]])
        ->get([
            'employee_recruit_req_replaces.*',
            'employee_h_rs.code',
            'employee_h_rs.contact',
            'employee_h_rs.name as employee_name',
            'designations.name as designation_name',
        ])
        ->map(function($employeerecruitreqreplace) {
            return $employeerecruitreqreplace;
        })
        ->first();
	    $row ['fromData'] = $employeerecruitreqreplace;
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

    public function update(EmployeeRecruitReqReplaceRequest $request, $id) {
        $employeerecruitreqreplace=$this->employeerecruitreqreplace->update($id,[
            'employee_h_r_id'=>$request->employee_h_r_id,
            'employee_recruit_req_id'=>$request->employee_recruit_req_id, 
            
        ]);
		if($employeerecruitreqreplace){
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
        if($this->employeerecruitreqreplace->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getReplaceEmployee(){
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
          $employeehr->company_id=$company[$employeehr->company_id];
          $employeehr->designation_id=isset($designation[$employeehr->designation_id])?$designation[$employeehr->designation_id]:'';
          $employeehr->department_id=isset($department[$employeehr->department_id])?$department[$employeehr->department_id]:'';
          $employeehr->location_id=isset($location[$employeehr->location_id])?$location[$employeehr->location_id]:'';
          $employeehr->address='';
          return $employeehr;
        });

        echo json_encode($employeehr);
    }

   
}