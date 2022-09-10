<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Sms;
use GuzzleHttp\Client;
class EmployeeHRApprovalController extends Controller
{
    private $employeehr;
    private $user;
    private $buyer;
    private $company;

    public function __construct(
		EmployeeHRRepository $employeehr,
		UserRepository $user,
		CompanyRepository $company

    ) {
        $this->employeehr = $employeehr;
        $this->user = $user;
        $this->company = $company;
        $this->middleware('auth');
        $this->middleware('permission:approve.employeehrs',   ['only' => ['approved', 'index','reportData']]);

    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.EmployeeHRApproval',['company'=>$company]);
    }
	public function reportData() {
        
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $yesno = config('bprs.yesno');
        $gender = array_prepend(config('bprs.gender'),'-Select-',0);
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-','');
        $designationlevel=array_prepend(config('bprs.designationlevel'),'--','');
        $employeecategory=array_prepend(config('bprs.employeecategory'),'--','');

        $employeehrs=array();
        $rows=$this->employeehr
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
        ->orderBy('employee_h_rs.id','desc')
        ->when(request('company_id'), function ($q) {
          return $q->where('employee_h_rs.company_id','=',request('company_id', 0));
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('employee_h_rs.date_of_join','>=',request('date_from', 0));
        })   
        ->when(request('date_to'), function ($q) {
            return $q->where('employee_h_rs.date_of_join','=',request('date_to', 0));
        })
        ->whereNull('employee_h_rs.approved_at')
        ->whereNull('employee_h_rs.approved_by')
        ->get([
        'employee_h_rs.*',
        'designations.name as designation_name',
        'designations.designation_level_id',
        'designations.employee_category_id',
        'companies.name as company_name',
        'locations.name as location_name',
        'divisions.name as division_name',
        'departments.name as department_name',
        'sections.name as section_name',
        'subsections.name as subsection_name',
        'employee_h_rs.inactive_date',
        ])
        ->map(function($rows) use($user,$gender,$yesno,$status,$designationlevel,$employeecategory){
          $rows->gender=isset($gender[$rows->gender_id])?$gender[$rows->gender_id]:'';
          $rows->user=isset($user[$rows->user_id])?$user[$rows->user_id]:'';
          $rows->status=isset($status[$rows->status_id])?$status[$rows->status_id]:'';
          $rows->created_date=date("d-M-Y",strtotime($rows->created_at));
          $rows->date_of_join=($rows->date_of_join !== null)?date("d-M-Y",strtotime($rows->date_of_join)):null; 
          $rows->date_of_birth=($rows->date_of_birth !== null)?date("d-M-Y",strtotime($rows->date_of_birth)):null; 
          $rows->salary=number_format($rows->salary,0);
          $rows->compliance_salary=number_format($rows->compliance_salary,0);
          $rows->api_status=$rows->api_status?$yesno[$rows->api_status]:'No';
          $rows->designation_level=$designationlevel[$rows->designation_level_id];
          $rows->employee_category=$employeecategory[$rows->employee_category_id];
          return $rows;

        });
        echo json_encode($rows);
    }

    public function approved (Request $request)
    {
    	$id=request('id',0);
    	$master=$this->employeehr->find($id);
		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');
        $master->approved_by=$user->id;
        $master->approved_at=$approved_at;
        $master->timestamps=false;
        $employeehr=$master->save();

		/*$employeehr = $this->employeehr->update($id,[
			'approved_by' => $user->id,  
			'approved_at' =>  $approved_at
		]);*/

        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $gender = array_prepend(config('bprs.gender'),'-Select-','');

        $emp=$this->employeehr
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
        ->where([['employee_h_rs.id','=',$id]])
        ->get([
        'employee_h_rs.id as emp_id',
        'employee_h_rs.name as emp_name',
        'employee_h_rs.company_id',
        'companies.name as company_name',
        'employee_h_rs.designation_id',
        'designations.name as designation_name',
        'employee_h_rs.department_id',
        'departments.name as department_name',
        'employee_h_rs.date_of_birth',
        'employee_h_rs.gender_id',
        'employee_h_rs.date_of_join',
        'employee_h_rs.probation_days',
        'employee_h_rs.national_id',
        'employee_h_rs.salary as gross_salary',
        'employee_h_rs.compliance_salary as comp_gross_salary',
        'employee_h_rs.contact as phone_no',
        'employee_h_rs.religion',
        'employee_h_rs.status_id',
        'employee_h_rs.location_id',
        'locations.name as location_name',
        'employee_h_rs.division_id',
        'divisions.name as division_name',
        'employee_h_rs.section_id',
        'sections.name as section_name',
        'employee_h_rs.subsection_id as sub_section_id',
        'subsections.name as subsection_name as sub_section_name',
        'employee_h_rs.inactive_date',
        ])
        ->map(function($emp) use($status,$gender){
        $emp->date_of_birth=date('Y-m-d',strtotime($emp->date_of_birth));
        $emp->date_of_join=date('Y-m-d',strtotime($emp->date_of_join));
        $emp->inactive_date=$emp->inactive_date?date('Y-m-d',strtotime($emp->inactive_date)):'';
        $emp->gender_name=$gender[$emp->gender_id];
        $emp->status_name=$status[$emp->status_id];
        return $emp;
        })
        ->first();
        $data = json_encode($emp);
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
            "Content-Type"  => "application/json"
          ]; 
          //echo $token; die;
          $res=$client->post('http://192.168.32.10:8082/Api/Erp/Employee', ['body' => $data, 'headers' => $headers]);
          //echo $res->getBody();
          $ApiStatus=json_decode($res->getBody());
          $this->employeehr->update($id,[
          'api_status'=>$ApiStatus->Status,
          ]);
        }
        catch(\GuzzleHttp\Exception\RequestException $e)
        {
          if($employeehr){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Approved Successfully, But Remote Server Not Updated'),200);
          }
          throw $e;
        }

		if($employeehr){
		return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
		}
    }

    
}
