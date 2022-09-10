<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeHRStatusRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Sms;
use GuzzleHttp\Client;
class EmployeeHRStatusApprovalController extends Controller
{
    private $employeehr;
    private $employeehrstatus;
    private $user;
    private $buyer;
    private $company;

    public function __construct(
        EmployeeHRRepository $employeehr,
        EmployeeHRStatusRepository $employeehrstatus,
		UserRepository $user,
		CompanyRepository $company

    ) {
        $this->employeehr = $employeehr;
        $this->employeehrstatus = $employeehrstatus;
        $this->user = $user;
        $this->company = $company;
        $this->middleware('auth');
        //$this->middleware('permission:approve.employeehrstatus',   ['only' => ['approved', 'index','reportData']]);
        $this->middleware('permission:view.employeehrapproval',   ['only' => [ 'index','reportData']]);
        $this->middleware('permission:approve.employeehrs',   ['only' => ['approved',]]);

    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.EmployeeHRStatusApproval',['company'=>$company]);
    }
	public function reportData() {
        
        $yesno = config('bprs.yesno');
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $hrinactivefor = array_prepend(config('bprs.hrinactivefor'),'-Select-',0);

        $rows=$this->employeehrstatus
        ->join('employee_h_rs',function($join){
        $join->on('employee_h_rs.id','=','employee_h_r_statuses.employee_h_r_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->join('designations',function($join){
        $join->on('designations.id','=','employee_h_rs.designation_id');
        })
        ->join('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->leftJoin('locations',function($join){
        $join->on('locations.id','=','employee_h_rs.location_id');
        })
        ->leftJoin('divisions',function($join){
        $join->on('divisions.id','=','employee_h_rs.division_id');
        })
        ->leftJoin('sections',function($join){
        $join->on('sections.id','=','employee_h_rs.section_id');
        })
        ->leftJoin('subsections',function($join){
        $join->on('subsections.id','=','employee_h_rs.subsection_id');
        })
        ->leftJoin('employee_h_rs as reportto',function($join){
        $join->on('reportto.id','=','employee_h_rs.report_to_id');
        })
        ->orderBy('employee_h_r_statuses.id','desc')
        ->when(request('company_id'), function ($q) {
          return $q->where('employee_h_rs.company_id','=',request('company_id', 0));
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('employee_h_r_statuses.status_date','>=',request('date_from', 0));
        })   
        ->when(request('date_to'), function ($q) {
            return $q->where('employee_h_r_statuses.status_date','=',request('date_to', 0));
        })
        ->whereNull('employee_h_r_statuses.approved_at')
        ->whereNull('employee_h_r_statuses.approved_by')
        ->get([
            'employee_h_r_statuses.id',
            'employee_h_r_statuses.status_id',
            'employee_h_r_statuses.status_date',
            'employee_h_r_statuses.logistics_status_id',
            'employee_h_r_statuses.remarks',
            'employee_h_r_statuses.created_at',
            'employee_h_rs.id as employee_h_r_id',
            'employee_h_rs.name as employee_name',
            'companies.name as company_name',
            'designations.name as designation_name',
            'departments.name as department_name',
            'employee_h_rs.date_of_birth',
            'employee_h_rs.gender_id',
            'employee_h_rs.date_of_join',
            'employee_h_rs.probation_days',
            'employee_h_rs.national_id',
            'employee_h_rs.salary as gross_salary',
            'employee_h_rs.contact as phone_no',
            'employee_h_rs.religion',
            'employee_h_rs.grade',
            'locations.name as location_name',
            'divisions.name as division_name',
            'sections.name as section_name',
            'subsections.name as subsection_name',
            'reportto.name as report_to_name',
            
        ])
        ->map(function($rows) use($status,$yesno,$hrinactivefor){
            $rows->status=$status[$rows->status_id];
            $rows->logistics_status=$rows->logistics_status_id?$hrinactivefor[$rows->logistics_status_id]:'';
            $rows->created_date=date("d-M-Y",strtotime($rows->created_at));
            $rows->status_date=date("d-M-Y",strtotime($rows->status_date));
            $rows->api_status=$rows->api_status?$yesno[$rows->api_status]:'No';
            return $rows;
        });
        echo json_encode($rows);
    }

    public function approved (Request $request)
    {
    	$id=request('id',0);
    	$master=$this->employeehrstatus->find($id);
		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');
        $master->approved_by=$user->id;
        $master->approved_at=$approved_at;
        $master->timestamps=false;
        \DB::beginTransaction();
        try
        {
            $employeehrstatus=$master->save();
            $employeehr=$this->employeehr->update($master->employee_h_r_id,['status_id'=>$master->status_id,'status_date'=>$master->status_date]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

		

        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $hrinactivefor=array_prepend(config('bprs.hrinactivefor'),'-Select-',''); 

        $emp=$this->employeehrstatus
        ->join('employee_h_rs',function($join){
        $join->on('employee_h_rs.id','=','employee_h_r_statuses.employee_h_r_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->where([['employee_h_r_statuses.id','=',$id]])
        ->get([
        'employee_h_rs.id as emp_id',
        'employee_h_rs.company_id',
        'employee_h_r_statuses.status_id',
        'employee_h_r_statuses.logistics_status_id',
        'employee_h_r_statuses.status_date as inactive_date',
        ])
        ->map(function($emp) use($status,$hrinactivefor){
            if($emp->status_id==1){
                $emp->emp_id=$emp->emp_id;
                $emp->company_id=$emp->company_id;
                $emp->employee_status='Active';
                $emp->inactive_date='';
                $emp->logistics_status_id='';
                $emp->logistics_status='';

            }else{
                $emp->emp_id=$emp->emp_id;
                $emp->company_id=$emp->company_id;
                $emp->employee_status='Inactive';
                $emp->inactive_date=$emp->inactive_date?date('Y-m-d',strtotime($emp->inactive_date)):'';
                $emp->logistics_status_id=$emp->logistics_status_id;
                $emp->logistics_status=$hrinactivefor[$emp->logistics_status_id];
            }

            return $emp;
        })
        ->first();
        $data = [
            'emp_id'=>$emp->emp_id,
            'company_id'=>$emp->company_id,
            'employee_status'=>$emp->employee_status,
            'inactive_date'=>$emp->inactive_date,
            'logistics_status_id'=>$emp->logistics_status_id,
            'logistics_status'=>$emp->logistics_status,
        ];

        
        try
        {
          $client = new Client();
          $response = $client->request('POST', 'http://192.168.32.10:8082/Token',
          [
            'form_params' => [
            'grant_type' => 'password',
            'username' => 'erpadmin',
            'password' => 'admin@erp',
          ]
          ]);
          //$code = $response->getStatusCode();
          $body=json_decode($response->getBody());
          $token=$body->access_token;
          $headers = [
            'Authorization' => 'Bearer ' . $token,        
            'Accept'        => 'application/json',
            "Content-Type"  => "application/x-www-form-urlencoded"
          ]; 
          //echo $token; die;
          $res=$client->post('http://192.168.32.10:8082/Api/Erp/EmployeeStatus', ['form_params' => $data, 'headers' => $headers]);
          //echo $res->getBody();
          $ApiStatus=json_decode($res->getBody());
          $this->employeehrstatus->update($id,[
          'api_status'=>$ApiStatus->Status,
          ]);
          $this->employeehr->update($emp->emp_id,[
          'api_status'=>$ApiStatus->Status,
          ]);
        }
        catch(\GuzzleHttp\Exception\RequestException $e)
        {
          if($employeehrstatus){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Approved Successfully, But Remote Server Not Updated'),200);
          }
          throw $e;
        }

		if($employeehr){
		return response()->json(array('success' => true,  'message' => 'Approved Successfully','dd'=>$data), 200);
		}
    }

    
}
