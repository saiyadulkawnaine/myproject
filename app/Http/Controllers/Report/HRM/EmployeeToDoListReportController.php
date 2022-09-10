<?php

namespace App\Http\Controllers\Report\HRM;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeToDoListRepository;
use App\Repositories\Contracts\HRM\EmployeeToDoListTaskRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
class EmployeeToDoListReportController extends Controller
{
	private $employeehr;
	private $company;
	private $designation;
	private $department;
	private $user;

	public function __construct(EmployeeHRRepository $employeehr,DesignationRepository $designation,DepartmentRepository $department,CompanyRepository $company,
	EmployeeToDoListRepository $employeetodolist,
	EmployeeToDoListTaskRepository $employeetodolisttask,
	UserRepository $user)
    {
		$this->employeehr = $employeehr;
      	$this->designation = $designation;
      	$this->department = $department;
		$this->company = $company;
		$this->employeetodolist = $employeetodolist;
        $this->employeetodolisttask = $employeetodolisttask;
        $this->user = $user;

		$this->middleware('auth');

		//$this->middleware('permission:view.employeetodolistreport',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
		$user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$yesno = config('bprs.yesno');
      	$todopriority=array_prepend(config('bprs.todopriority'), '-Select-','');
      return Template::loadView('Report.HRM.EmployeeToDoListReport',['designation'=>$designation,'user'=>$user,'yesno'=>$yesno,'todopriority'=>$todopriority,'company'=>$company]);
	 }
	 
	public function reportData() {
	  $todopriority=array_prepend(config('bprs.todopriority'), '-Select-','');
		
	  $taskbar=$this->employeetodolist
	  ->leftJoin('employee_to_do_list_tasks',function($join){
		  $join->on('employee_to_do_list_tasks.employee_to_do_list_id','=','employee_to_do_lists.id');
	  })
	  ->leftJoin('employee_to_do_list_task_bars',function($join){
		  $join->on('employee_to_do_list_task_bars.employee_to_do_list_task_id','=','employee_to_do_list_tasks.id');
	  })
	  ->get([
		  //'employee_to_do_lists.user_id',
		  'employee_to_do_list_tasks.id as employee_to_do_list_task_id',
		  'employee_to_do_list_task_bars.barrier_desc',
	  ]);
	$arrBariar=array();
	foreach($taskbar as $bar){
		//$arrBariar[$bar->user_id][$bar->employee_to_do_list_task_id]['barrier_desc']=implode(',',$bar->barrier_desc);
		$arrBariar[$bar->employee_to_do_list_task_id][]=$bar->barrier_desc;
	}
	

      $rows=$this->employeetodolist
		->leftJoin('users',function($join){
			$join->on('users.id','=','employee_to_do_lists.user_id');
		})
		->leftJoin('employee_to_do_list_tasks',function($join){
            $join->on('employee_to_do_list_tasks.employee_to_do_list_id','=','employee_to_do_lists.id');
		})
		->when(request('user_id'), function ($q) {
			return $q->where('employee_to_do_lists.user_id', '=', request('user_id', 0));
		})
		->when(request('date_from'), function ($q) {
			return $q->where('employee_to_do_lists.exec_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
			return $q->where('employee_to_do_lists.exec_date', '<=',request('date_to', 0));
		})
		->when(request('priority_id'), function ($q) {
			return $q->where('employee_to_do_list_tasks.priority_id', '=', request('priority_id', 0));
		})
		->selectRaw('
			employee_to_do_lists.id as employee_to_do_list_id,
			employee_to_do_lists.user_id,
			employee_to_do_lists.exec_date,
			employee_to_do_lists.remarks,
			employee_to_do_list_tasks.id as employee_to_do_list_task_id,
			employee_to_do_list_tasks.task_desc,
			employee_to_do_list_tasks.priority_id,
			employee_to_do_list_tasks.task_time,
			employee_to_do_list_tasks.start_date,
			employee_to_do_list_tasks.end_date,
			employee_to_do_list_tasks.result_desc,
			employee_to_do_list_tasks.impact_desc,
			
			users.name as user_name,
			users.id as user_id
			')
		->groupBy([
			'employee_to_do_lists.id',
			'employee_to_do_lists.user_id',
			'employee_to_do_lists.exec_date',
			'employee_to_do_lists.remarks',
			'employee_to_do_list_tasks.id',
			'employee_to_do_list_tasks.task_desc',
			'employee_to_do_list_tasks.priority_id',
			'employee_to_do_list_tasks.task_time',
			'employee_to_do_list_tasks.start_date',
			'employee_to_do_list_tasks.end_date',
			'employee_to_do_list_tasks.result_desc',
			'employee_to_do_list_tasks.impact_desc',
			'users.name',
			'users.id',
		])
		->get()
			//dd($rows);
		->map(function($rows) use($todopriority,$arrBariar){
			$rows->priority_id=$todopriority[$rows->priority_id];
			$rows->exec_date=($rows->exec_date !== null)?date('d-M-Y',strtotime($rows->exec_date)):'';
			$rows->start_date=($rows->start_date !== null)?date('d-M-Y',strtotime($rows->start_date)):'';
			$rows->end_date=($rows->end_date !== null)?date('d-M-Y',strtotime($rows->end_date)):'';
			$rows->barrier_desc=implode(",\n",$arrBariar[$rows->employee_to_do_list_task_id]);

			$actionStart=strtotime($rows->start_date);
			$actionEnd=strtotime($rows->end_date);

			if($actionStart=='' && $actionEnd == ''){
				$rows->status="background-color:red";
			}
			if($actionStart && !$actionEnd){
				$rows->status="background-color:yellow";
			}
			if($actionStart && $actionEnd ){
				$rows->status="background-color:green";
			}

			return $rows;

		});
		
		$empArr=array();
		$arrBariar=array();
		foreach($rows as $todolist){
			$empArr[$todolist->user_id]['user_name']=$todolist->user_name;
			$empArr[$todolist->user_id]['exec_date']=$todolist->exec_date;
			$empArr[$todolist->user_id]['priority_id']=$todolist->priority_id;
			$empArr[$todolist->user_id]['task_desc']=$todolist->task_desc;
			$empArr[$todolist->user_id]['task_time']=$todolist->task_time;
			$empArr[$todolist->user_id]['start_date']=$todolist->start_date;
			$empArr[$todolist->user_id]['end_date']=$todolist->end_date;
			$empArr[$todolist->user_id]['result_desc']=$todolist->result_desc;
			$empArr[$todolist->user_id]['impact_desc']=$todolist->impact_desc;
			$empArr[$todolist->user_id]['barrier_desc']=$todolist->barrier_desc;
			$empArr[$todolist->user_id]['status']=$todolist->status;
		}

		$category=$rows->groupBy('user_id');
		// $category=$rows;
		$start_date=date('d-M-Y',strtotime(request('date_from',0)));
		$end_date=date('d-M-Y',strtotime(request('date_to',0)));
		return Template::loadView('Report.HRM.ToDoListMatrix',['rows'=>$rows,'category'=>$category,'empArr'=>$empArr,'arrBariar'=>$arrBariar,'start_date'=>$start_date,'end_date'=>$end_date]);
	 }
	 

}
