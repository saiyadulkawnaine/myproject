<?php

namespace App\Http\Controllers\Report\HRM;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\HRM\RegisterVisitorRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
class RegisterVisitorReportController extends Controller
{
	private $registervisitor;
    private $employee;
    private $user;
    private $company;
    private $location;

	public function __construct(EmployeeRepository $employee,
	RegisterVisitorRepository $registervisitor,CompanyRepository $company, 
	UserRepository $user,LocationRepository $location)
    {
		$this->employee = $employee;
        $this->registervisitor = $registervisitor;
        $this->user = $user;
        $this->company = $company;
		$this->location = $location;
		
		$this->middleware('auth');

		//$this->middleware('permission:view.employeelists',   ['only' => ['create', 'index','show']]);
	}
	
    public function index() {
		$user=array_prepend(array_pluck($this->user->get(),'name','id'),'','');
      	$location=array_prepend(array_pluck($this->location->get(),'name','id'),'','');
      	return Template::loadView('Report.HRM.RegisterVisitorReport',['user'=>$user,'location'=>$location]);
	}
	 
	public function reportData() {
		$location=array_prepend(array_pluck($this->location->get(),'name','id'),'','');
		$registervisitor=$this->registervisitor
		->leftJoin('users',function($join){
		  $join->on('users.id','=','register_visitors.user_id');
		})
		->leftJoin('employee_h_rs',function($join){
		  $join->on('users.id','=','employee_h_rs.user_id');
		})
		->leftJoin('departments',function($join){
		  $join->on('departments.id','=','employee_h_rs.department_id');
		})
		->leftJoin('users as approve_user',function($join){
		  $join->on('approve_user.id','=','register_visitors.approve_user_id');
		})
		->leftJoin('employee_h_rs as approve_employee',function($join){
		  $join->on('approve_user.id','=','approve_employee.user_id');
		})
		->when(request('user_id'), function ($q) {
		  return $q->where('register_visitors.user_id', '=', request('user_id', 0));
		})
		->when(request('name'), function ($q) {
		  return $q->where('register_visitors.name', 'LIKE', "%".request('name', 0)."%");
		})
		->when(request('date_from'), function ($q) {
			return $q->where('register_visitors.arrival_date', '>=',request('date_from', 0));
		})
	   ->when(request('date_to'), function ($q) {
			return $q->where('register_visitors.arrival_date', '<=',request('date_to', 0));
		})
		->orderBy('register_visitors.id','desc')
		->get([
		  'register_visitors.*',
		  'users.name as user_name',
		  'approve_user.name as approve_user_name',
		  'users.id as user_id',
		  'departments.name as department_name',
		//'locations.name as location_name',
		])
		->map(function($registervisitor) use($location){
			$registervisitor->location_id=$location[$registervisitor->location_id];
			$registervisitor->arrival_date=date('d-M-Y',strtotime($registervisitor->arrival_date));
			if($registervisitor->approved_at){
				$registervisitor->approved_by="Approved";
			}else {
				$registervisitor->approved_by='';
			}
			return $registervisitor;
			});
			return $registervisitor;
	}
	 
   	public function html(){
      $visitorlist = $this->reportData();
      echo json_encode($visitorlist);
   }

}