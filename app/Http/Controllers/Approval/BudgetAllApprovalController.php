<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Approval\BudgetApprovalRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Approval\ApprovalCommentHistoryRepository;
use App\Library\Sms;
class BudgetAllApprovalController extends Controller
{
    private $budget;
    private $budgetapproval;
    private $user;
    private $buyer;
    private $company;
    private $approvalcommenthistory;

    public function __construct(
		BudgetRepository $budget,
        BudgetApprovalRepository $budgetapproval,
		UserRepository $user,
		BuyerRepository $buyer,
		CompanyRepository $company,
        ApprovalCommentHistoryRepository $approvalcommenthistory

    ) {
        $this->budget = $budget;
        $this->budgetapproval = $budgetapproval;
        $this->user = $user;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->approvalcommenthistory = $approvalcommenthistory;
        $this->middleware('auth');
        //$this->middleware('permission:approve.budgets',   ['only' => ['approved', 'index','reportData','reportDataApp','unapproved']]);
        //$this->middleware('permission:view.budgetapproval',   ['only' => [ 'index','reportData','reportDataApp']]);
        //$this->middleware('permission:approve.budgets',   ['only' => ['approved','unapproved']]);
        $this->middleware('permission:approvefirst.budgetall',   ['only' => ['firstapproved']]);
        $this->middleware('permission:approvesecond.budgetall', ['only' => ['secondapproved']]);
        $this->middleware('permission:approvethird.budgetall',   ['only' => ['thirdapproved']]);
        $this->middleware('permission:approvefinal.budgetall', ['only' => ['finalapproved']]);
        $this->middleware('permission:approvefirst.budgetall|approvesecond.budgetall|approvethird.budgetall|approvefinal.budgetall', ['only' => ['approvalReturn']]);



    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.BudgetApproval',['company'=>$company,'buyer'=>$buyer]);
    }
	public function reportData() {
        $approval_type_id=request('approval_type_id');
        $rows=$this->budget
        ->join('jobs',function($join){
            $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','jobs.company_id');
        })
        ->join('teams',function($join){
            $join->on('teams.id','=','styles.team_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','jobs.currency_id');
        })
        ->join('uoms',function($join){
            $join->on('uoms.id','=','styles.uom_id');
        })
        ->leftJoin('buyers as buyingagents', function($join)  {
            $join->on('styles.buying_agent_id', '=', 'buyingagents.id');
        })
        ->leftJoin('budget_approvals',function($join){
            $join->on('budget_approvals.budget_id','=','budgets.id');
        })
        
        ->when(request('company_id'), function ($q) {
        return $q->where('jobs.company_id', '=',request('company_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
        return $q->where('styles.buyer_id', '=',request('buyer_id', 0));
        })
        ->when(request('date_from'), function ($q) {
        return $q->where('budgets.budget_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
        return $q->where('budgets.budget_date', '<=',request('date_to', 0));
        })
        ->when($approval_type_id, function ($q) use ($approval_type_id){
            if($approval_type_id==1){
                return $q->whereNull('budget_approvals.fabric_first_approved_at')
                ->whereNull('budget_approvals.yarn_first_approved_at')
                ->whereNull('budget_approvals.yarndye_first_approved_at')
                ->whereNull('budget_approvals.fabricprod_first_approved_at')
                ->whereNull('budget_approvals.embel_first_approved_at')
                ->whereNull('budget_approvals.trim_first_approved_at')
                ->whereNull('budget_approvals.other_first_approved_at');
                //->whereNull('budget_approvals.all_first_approved_at');
            }
            if($approval_type_id==2){
            return $q->whereNotNull('budget_approvals.fabric_first_approved_at')
            ->whereNotNull('budget_approvals.yarn_first_approved_at')
            ->whereNotNull('budget_approvals.yarndye_first_approved_at')
            ->whereNotNull('budget_approvals.fabricprod_first_approved_at')
            ->whereNotNull('budget_approvals.embel_first_approved_at')
            ->whereNotNull('budget_approvals.trim_first_approved_at')
            ->whereNotNull('budget_approvals.other_first_approved_at')
            //->whereNotNull('budget_approvals.all_first_approved_at')

            ->whereNull('budget_approvals.fabric_second_approved_at')
            ->whereNull('budget_approvals.yarn_second_approved_at')
            ->whereNull('budget_approvals.yarndye_second_approved_at')
            ->whereNull('budget_approvals.fabricprod_second_approved_at')
            ->whereNull('budget_approvals.embel_second_approved_at')
            ->whereNull('budget_approvals.trim_second_approved_at')
            ->whereNull('budget_approvals.other_second_approved_at');
            //->whereNull('budget_approvals.all_second_approved_at');
            }
            if($approval_type_id==3){
            return $q->whereNotNull('budget_approvals.fabric_second_approved_at')
            ->whereNotNull('budget_approvals.yarn_second_approved_at')
            ->whereNotNull('budget_approvals.yarndye_second_approved_at')
            ->whereNotNull('budget_approvals.fabricprod_second_approved_at')
            ->whereNotNull('budget_approvals.embel_second_approved_at')
            ->whereNotNull('budget_approvals.trim_second_approved_at')
            ->whereNotNull('budget_approvals.other_second_approved_at')
            //->whereNotNull('budget_approvals.all_second_approved_at')

            ->whereNull('budget_approvals.fabric_third_approved_at')
            ->whereNull('budget_approvals.yarn_third_approved_at')
            ->whereNull('budget_approvals.yarndye_third_approved_at')
            ->whereNull('budget_approvals.fabricprod_third_approved_at')
            ->whereNull('budget_approvals.embel_third_approved_at')
            ->whereNull('budget_approvals.trim_third_approved_at')
            ->whereNull('budget_approvals.other_third_approved_at');
            //->whereNull('budget_approvals.all_third_approved_at');
            }
            if($approval_type_id==10){
            return $q->whereNotNull('budget_approvals.fabric_third_approved_at')
            ->whereNotNull('budget_approvals.yarn_third_approved_at')
            ->whereNotNull('budget_approvals.yarndye_third_approved_at')
            ->whereNotNull('budget_approvals.fabricprod_third_approved_at')
            ->whereNotNull('budget_approvals.embel_third_approved_at')
            ->whereNotNull('budget_approvals.trim_third_approved_at')
            ->whereNotNull('budget_approvals.other_third_approved_at')
            //->whereNotNull('budget_approvals.all_third_approved_at')

            ->whereNull('budget_approvals.fabric_final_approved_at')
            ->whereNull('budget_approvals.yarn_final_approved_at')
            ->whereNull('budget_approvals.yarndye_final_approved_at')
            ->whereNull('budget_approvals.fabricprod_final_approved_at')
            ->whereNull('budget_approvals.embel_final_approved_at')
            ->whereNull('budget_approvals.trim_final_approved_at')
            ->whereNull('budget_approvals.other_final_approved_at');
            //->whereNull('budget_approvals.all_final_approved_at');
            }
        })
        

        ->where([['budget_approvals.fabric_ready_to_approve_id','=',1]])
        ->where([['budget_approvals.yarn_ready_to_approve_id','=',1]])
        ->where([['budget_approvals.yarndye_ready_to_approve_id','=',1]])
        ->where([['budget_approvals.fabricprod_ready_to_approve_id','=',1]])
        ->where([['budget_approvals.embel_ready_to_approve_id','=',1]])
        ->where([['budget_approvals.trim_ready_to_approve_id','=',1]])
        ->where([['budget_approvals.other_ready_to_approve_id','=',1]])
        ->orderBy('budgets.id','desc')
        ->get([
        'budgets.*',
        'jobs.id as job_id',
        'jobs.job_no',
        'styles.style_ref',
        'styles.id as style_id',
        'buyers.code as buyer_name',
        'teams.name as team_name',
        'currencies.code as currency_code',
        'companies.code as company_name',
        'uoms.code as uom_code',
        'buyingagents.name as buying_agent'
        ])
        ->map(function($rows){
            $rows->budget_date=date('Y-m-d',strtotime($rows->budget_date));
            return $rows;
        });
        echo json_encode($rows);
    }
    public function firstapproved (Request $request)
    {

        foreach($request->id as $index=>$id){
            if($id){
                $master=$this->budgetapproval->where([['budget_id','=',$id]])->get()->first();
                $user = \Auth::user();
                $first_approved_at=date('Y-m-d h:i:s');
                $master->timestamps=false;

                $master->fabric_first_approved_by=$user->id;
                $master->fabric_first_approved_at=$first_approved_at;

                $master->yarn_first_approved_by=$user->id;
                $master->yarn_first_approved_at=$first_approved_at;

                $master->yarndye_first_approved_by=$user->id;
                $master->yarndye_first_approved_at=$first_approved_at;

                $master->fabricprod_first_approved_by=$user->id;
                $master->fabricprod_first_approved_at=$first_approved_at;

                $master->embel_first_approved_by=$user->id;
                $master->embel_first_approved_at=$first_approved_at;

                $master->trim_first_approved_by=$user->id;
                $master->trim_first_approved_at=$first_approved_at;

                $master->other_first_approved_by=$user->id;
                $master->other_first_approved_at=$first_approved_at;
                
                $budget=$master->save();
            }
        }
        return response()->json(array('success' => true,'type' => 'firstapproved', 'message' => 'Approved Successfully'), 200);

    }


    public function secondapproved (Request $request)
    {
        foreach($request->id as $index=>$id){
            if($id){
                $master=$this->budgetapproval->where([['budget_id','=',$id]])->get()->first();
                $user = \Auth::user();
                $second_approved_at=date('Y-m-d h:i:s');
                $master->timestamps=false;

                

                $master->fabric_second_approved_by=$user->id;
                $master->fabric_second_approved_at=$second_approved_at;

                $master->yarn_second_approved_by=$user->id;
                $master->yarn_second_approved_at=$second_approved_at;

                $master->yarndye_second_approved_by=$user->id;
                $master->yarndye_second_approved_at=$second_approved_at;

                $master->fabricprod_second_approved_by=$user->id;
                $master->fabricprod_second_approved_at=$second_approved_at;

                $master->embel_second_approved_by=$user->id;
                $master->embel_second_approved_at=$second_approved_at;

                $master->trim_second_approved_by=$user->id;
                $master->trim_second_approved_at=$second_approved_at;

                $master->other_second_approved_by=$user->id;
                $master->other_second_approved_at=$second_approved_at;
                
                $budget=$master->save();
            }
        }
        return response()->json(array('success' => true, 'type' => 'secondapproved','message' => 'Approved Successfully'), 200);
    }

    public function thirdapproved (Request $request)
    {
        foreach($request->id as $index=>$id){
            if($id){
                $master=$this->budgetapproval->where([['budget_id','=',$id]])->get()->first();
                $user = \Auth::user();
                $third_approved_at=date('Y-m-d h:i:s');
                $master->timestamps=false;
                

                $master->fabric_third_approved_by=$user->id;
                $master->fabric_third_approved_at=$third_approved_at;

                $master->yarn_third_approved_by=$user->id;
                $master->yarn_third_approved_at=$third_approved_at;

                $master->yarndye_third_approved_by=$user->id;
                $master->yarndye_third_approved_at=$third_approved_at;

                $master->fabricprod_third_approved_by=$user->id;
                $master->fabricprod_third_approved_at=$third_approved_at;

                $master->embel_third_approved_by=$user->id;
                $master->embel_third_approved_at=$third_approved_at;

                $master->trim_third_approved_by=$user->id;
                $master->trim_third_approved_at=$third_approved_at;

                $master->other_third_approved_by=$user->id;
                $master->other_third_approved_at=$third_approved_at;
                
                $budget=$master->save();
            }
        }
        return response()->json(array('success' => true,'type' => 'thirdapproved', 'message' => 'Approved Successfully'), 200);
    }
    public function finalapproved (Request $request)
    {
        foreach($request->id as $index=>$id){
            if($id){
                $master=$this->budgetapproval->where([['budget_id','=',$id]])->get()->first();
                $user = \Auth::user();
                $final_approved_at=date('Y-m-d h:i:s');
                $master->timestamps=false;
               

                $master->fabric_final_approved_by=$user->id;
                $master->fabric_final_approved_at=$final_approved_at;

                $master->yarn_final_approved_by=$user->id;
                $master->yarn_final_approved_at=$final_approved_at;

                $master->yarndye_final_approved_by=$user->id;
                $master->yarndye_final_approved_at=$final_approved_at;

                $master->fabricprod_final_approved_by=$user->id;
                $master->fabricprod_final_approved_at=$final_approved_at;

                $master->embel_final_approved_by=$user->id;
                $master->embel_final_approved_at=$final_approved_at;

                $master->trim_final_approved_by=$user->id;
                $master->trim_final_approved_at=$final_approved_at;

                $master->other_final_approved_by=$user->id;
                $master->other_final_approved_at=$final_approved_at;
                
                $budget=$master->save();
            }
        }
        return response()->json(array('success' => true,'type' => 'finalapproved', 'message' => 'Approved Successfully'), 200);
    }

    public function approvalReturn(Request $request){
        $id=$request->id;
        $master=$this->budgetapproval->where([['budget_id','=',$id]])->get()->first();
        $returned_coments=$request->returned_coments;
        $aproval_type=$request->aproval_type;
        $user = \Auth::user(); 
        $returned_at=date('Y-m-d h:i:s');
        $mktcost = $this->budgetapproval->update($master->id,[
        'fabric_returned_by' => $user->id,  
        'fabric_returned_at' =>  $returned_at,
        'fabric_returned_coments' =>  $returned_coments,

        'fabric_first_approved_by' => NULL,  
        'fabric_first_approved_at' =>  NULL,
        'fabric_second_approved_by' => NULL,  
        'fabric_second_approved_at' =>  NULL,
        'fabric_third_approved_by' => NULL,  
        'fabric_third_approved_at' =>  NULL,
        'fabric_final_approved_by' => NULL,  
        'fabric_final_approved_at' =>  NULL,


        'yarn_returned_by' => $user->id,  
        'yarn_returned_at' =>  $returned_at,
        'yarn_returned_coments' =>  $returned_coments,

        'yarn_first_approved_by' => NULL,  
        'yarn_first_approved_at' =>  NULL,
        'yarn_second_approved_by' => NULL,  
        'yarn_second_approved_at' =>  NULL,
        'yarn_third_approved_by' => NULL,  
        'yarn_third_approved_at' =>  NULL,
        'yarn_final_approved_by' => NULL,  
        'yarn_final_approved_at' =>  NULL,


        'yarndye_returned_by' => $user->id,  
        'yarndye_returned_at' =>  $returned_at,
        'yarndye_returned_coments' =>  $returned_coments,

        'yarndye_first_approved_by' => NULL,  
        'yarndye_first_approved_at' =>  NULL,
        'yarndye_second_approved_by' => NULL,  
        'yarndye_second_approved_at' =>  NULL,
        'yarndye_third_approved_by' => NULL,  
        'yarndye_third_approved_at' =>  NULL,
        'yarndye_final_approved_by' => NULL,  
        'yarndye_final_approved_at' =>  NULL,


        'fabricprod_returned_by' => $user->id,  
        'fabricprod_returned_at' =>  $returned_at,
        'fabricprod_returned_coments' =>  $returned_coments,

        'fabricprod_first_approved_by' => NULL,  
        'fabricprod_first_approved_at' =>  NULL,
        'fabricprod_second_approved_by' => NULL,  
        'fabricprod_second_approved_at' =>  NULL,
        'fabricprod_third_approved_by' => NULL,  
        'fabricprod_third_approved_at' =>  NULL,
        'fabricprod_final_approved_by' => NULL,  
        'fabricprod_final_approved_at' =>  NULL,

        'embel_returned_by' => $user->id,  
        'embel_returned_at' =>  $returned_at,
        'embel_returned_coments' =>  $returned_coments,

        'embel_first_approved_by' => NULL,  
        'embel_first_approved_at' =>  NULL,
        'embel_second_approved_by' => NULL,  
        'embel_second_approved_at' =>  NULL,
        'embel_third_approved_by' => NULL,  
        'embel_third_approved_at' =>  NULL,
        'embel_final_approved_by' => NULL,  
        'embel_final_approved_at' =>  NULL,

        'trim_returned_by' => $user->id,  
        'trim_returned_at' =>  $returned_at,
        'trim_returned_coments' =>  $returned_coments,

        'trim_first_approved_by' => NULL,  
        'trim_first_approved_at' =>  NULL,
        'trim_second_approved_by' => NULL,  
        'trim_second_approved_at' =>  NULL,
        'trim_third_approved_by' => NULL,  
        'trim_third_approved_at' =>  NULL,
        'trim_final_approved_by' => NULL,  
        'trim_final_approved_at' =>  NULL,

        'other_returned_by' => $user->id,  
        'other_returned_at' =>  $returned_at,
        'other_returned_coments' =>  $returned_coments,

        'other_first_approved_by' => NULL,  
        'other_first_approved_at' =>  NULL,
        'other_second_approved_by' => NULL,  
        'other_second_approved_at' =>  NULL,
        'other_third_approved_by' => NULL,  
        'other_third_approved_at' =>  NULL,
        'other_final_approved_by' => NULL,  
        'other_final_approved_at' =>  NULL,
        
        'fabric_ready_to_approve_id' => NULL,  
        'yarn_ready_to_approve_id' =>  NULL,
        'yarndye_ready_to_approve_id' => NULL,
        'fabricprod_ready_to_approve_id' => NULL,
        'embel_ready_to_approve_id' => NULL,
        'trim_ready_to_approve_id' => NULL,
        'other_ready_to_approve_id' => NULL,
        ]);

        $this->approvalcommenthistory->create([
                'model_id'=>$id,
                'model_type'=>'budget_trims',
                'comments'=>$returned_coments,
                'comments_by'=>$user->id,
                'comments_at'=>$returned_at
        ]);
        return response()->json(array('success' => true,'type' => $aproval_type, 'message' => 'Returned Successfully'), 200);
    }
}
