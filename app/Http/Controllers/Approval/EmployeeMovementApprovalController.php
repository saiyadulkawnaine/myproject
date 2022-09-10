<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeMovementRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Sms;

class EmployeeMovementApprovalController extends Controller
{
	private $employeemovement;
    private $employeehr;
    private $designation;
    private $department;
    private $location;
    private $company;
    private $user;

    public function __construct(
		EmployeeHRRepository $employeehr, 
        EmployeeMovementRepository $employeemovement,
        DesignationRepository $designation, 
        DepartmentRepository $department, 
        CompanyRepository $company, 
        UserRepository $user, 
        LocationRepository $location

    ) {
        $this->employeehr = $employeehr;
        $this->employeemovement = $employeemovement;
        $this->user = $user;
        $this->designation = $designation;
        $this->department = $department;
        $this->company = $company;
		$this->location = $location;
		
		$this->middleware('auth');
		
        $this->middleware('permission:approve.employeemovements',   ['only' => ['approved', 'index','reportData']]);

    }
    public function index() {
		$designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        
        return Template::loadView('Approval.EmployeeMovementApproval',['designation'=>$designation,'department'=>$department,'company'=>$company]);
    }
	public function reportData() {
		$location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
		return response()->json(
			$this->employeemovement
			->join('employee_h_rs',function($join){
				$join->on('employee_movements.employee_h_r_id','=','employee_h_rs.id');
			})
			->leftJoin('departments', function($join)  {
				$join->on('employee_h_rs.department_id', '=', 'departments.id');
			})
			->leftJoin('designations', function($join)  {
				$join->on('employee_h_rs.designation_id', '=', 'designations.id');
			})
			->leftJoin('companies', function($join)  {
				$join->on('employee_h_rs.company_id', '=', 'companies.id');
			})
			->when(request('company_id'), function ($q) {
				return $q->where('employee_h_rs.company_id', '=',request('company_id', 0));
			})
			->when(request('department_id'), function ($q) {
				return $q->where('employee_h_rs.department_id', '=',request('department_id', 0));
			})
			->when(request('designation_id'), function ($q) {
				return $q->where('employee_h_rs.designation_id', '=',request('designation_id', 0));
			})
			->when(request('date_from'), function ($q) {
				return $q->where('employee_movements.post_date', '>=',request('date_from', 0));
			})
			->when(request('date_to'), function ($q) {
				return $q->where('employee_movements.post_date', '<=',request('date_to', 0));
			})
			->whereNull('employee_movements.approved_at')
			->orderBy('employee_movements.id','desc')
			->get([
				'employee_movements.*',
				'employee_h_rs.company_id',
				'employee_h_rs.location_id',
				'employee_h_rs.department_id',
				'employee_h_rs.designation_id',
				'employee_h_rs.code',
				'employee_h_rs.contact',
				'employee_h_rs.name',
				'companies.name as company_name',
				'departments.name as department',
				'designations.name as designation'
			])
			->map(function($rows) use($location){
				$rows->post_date=date('d-M-Y',strtotime($rows->post_date));
				$rows->location_id=isset($location[$rows->location_id])?$location[$rows->location_id]:'';
				return $rows;
			})
        );
    }

    public function approved (Request $request)
    {
    	$id=request('id',0);
    	$master=$this->employeemovement->find($id);

		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');
		$employeemovement = $this->employeemovement->update($id,[
			'approved_by' => $user->id,  
			'approved_at' =>  $approved_at
		]);

		if($employeemovement){
			return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
		}
    }
}
