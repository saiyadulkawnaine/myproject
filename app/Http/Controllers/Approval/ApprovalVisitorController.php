<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\HRM\RegisterVisitorRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Sms;
class ApprovalVisitorController extends Controller
{
    private $registervisitor;
    private $employee;
    private $user;

    public function __construct(
      EmployeeRepository $employee,
      RegisterVisitorRepository $registervisitor,
      UserRepository $user
    ) {
        $this->employee = $employee;
        $this->registervisitor = $registervisitor;
        $this->user = $user;
        $this->middleware('auth');

		//$this->middleware('permission:approvefirst.invpurreqs',   ['only' => ['firstapproved']]);
    }
    public function index() {
        return Template::loadView('Approval.ApprovalVisitor');
    }
	public function reportData() {
        $new_day=date('Y-m-d');
		$data = DB::table("register_visitors")
		->select(
            "register_visitors.*",
            "users.name as user_name",
            "users.id as user_id",
            "departments.name as department_name"
		)
		->leftJoin('users',function($join){
            $join->on('users.id','=','register_visitors.user_id');
        })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->leftJoin('departments',function($join){
            $join->on('departments.id','=','employee_h_rs.department_id');
        })
       ->where([['register_visitors.arrival_date','=',$new_day]])
       ->whereNull('register_visitors.approved_by')
		->when(request('organization_dtl'), function ($q) {
            return $q->where('register_visitors.organization_dtl', 'LIKE', "%".request('organization_dtl', 0)."%");
		})
		->when(request('date_from'), function ($q) {
			return $q->where('register_visitors.arrival_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
			return $q->where('register_visitors.arrival_date', '<=',request('date_to', 0));
		})
		->orderBy('register_visitors.id','desc')
		->get()
		->map(function($data) {
          $data->arrival_date=date('d-M-Y',strtotime($data->arrival_date));
          return $data;
		});
		$datas=array();
		foreach($data as $row){
			array_push($datas,$row);
		}
		echo json_encode($datas);
    }

    public function approved (Request $request)
    {
    	foreach($request->id as $index=>$id){
			if($id){
				$user = \Auth::user();
				$approved_at=date('Y-m-d h:i:s');
				$registervisitor = $this->registervisitor->update($id,
				['approved_by' => $user->id,  'approved_at' =>  $approved_at]);
			}
        }
 
        if($registervisitor){
           //$user=array_prepend(array_pluck($this->user->get(),'name','id'),'','');
            $visitor = $this->registervisitor
            ->leftJoin('users',function($join){
                $join->on('users.id','=','register_visitors.user_id');
              })
              ->leftJoin('employee_h_rs',function($join){
                $join->on('users.id','=','employee_h_rs.user_id');
              })
              ->where([['register_visitors.id','=',$id]])
             // ->orderBy('register_visitors.id','desc')
              ->get([
                'register_visitors.*',
                'users.name as user_name',
                'users.id as user_id'
              ])
            ->first();
            $title ='Visit Approved';
            $text = 
            $title."\n".
            'Name: '.$visitor->name."\n".
            'Phone: '.$visitor->contact_no."\n".
            'Organization: '.$visitor->organization_dtl."\n".
            'Arrival Date: '.$visitor->arrival_date."\n".
            'Arrived at: '.$visitor->arrival_time."\n".
            'To Whom: '.$visitor->user_name."\n".
            'Purpose: '.$visitor->purpose;
  
            $userContact=$this->registervisitor
            ->leftJoin('users',function($join){
              $join->on('users.id','=','register_visitors.user_id');
            })
            ->leftJoin('employee_h_rs',function($join){
              $join->on('users.id','=','employee_h_rs.user_id');
            })
            ->where([['register_visitors.id','=',$id]])
            //->where([['users.id','=',$user->id]])
            ->get([
              'users.id as user_id',
              'users.name as user_name',
              'employee_h_rs.contact'
            ])->first();

            $reception=$this->registervisitor
            ->leftJoin('users',function($join){
              $join->on('users.id','=','register_visitors.created_by');
            })
            ->leftJoin('employee_h_rs',function($join){
              $join->on('users.id','=','employee_h_rs.user_id');
            })
            ->where([['register_visitors.id','=',$id]])
            ->get([
              'users.id as user_id',
              'users.name as user_name',
              'employee_h_rs.contact as createdby_contact'
            ])->first();

  
          $sms=Sms::send_sms($text, 
          '88'.$visitor->contact_no.','.
          '88'.$userContact->contact.','.
          '88'.$reception->createdby_contact.',8801714173989,8801715424277,8801321128280');//Alex,Simple,Bivuti Hira
          return response()->json(array('success' => true,'sms' => $sms,'userContact'=>$userContact,  'message' => 'Approved Successfully'), 200);
          }

    }

    public function visitApproved(){
        $new_day=date('Y-m-d');
        $visits= DB::table("register_visitors")
		->select(
            "register_visitors.*",
            "users.name as user_name",
            "users.id as user_id"
		)
        ->leftJoin('users',function($join){
            $join->on('users.id','=','register_visitors.user_id');
        })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('users.id','=','employee_h_rs.user_id');
        })
        //->where([['register_visitors.arrival_date','=',$new_day]])
        ->whereNotNull('register_visitors.approved_by')
        ->get();
        echo json_encode($visits);
    }

}
