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
class BudgetOtherApprovalController extends Controller
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
        $this->middleware('permission:approvefirst.budgetother',   ['only' => ['firstapproved']]);
        $this->middleware('permission:approvesecond.budgetother', ['only' => ['secondapproved']]);
        $this->middleware('permission:approvethird.budgetother',   ['only' => ['thirdapproved']]);
        $this->middleware('permission:approvefinal.budgetother', ['only' => ['finalapproved']]);
        $this->middleware('permission:approvefirst.budgetother|approvesecond.budgetother|approvethird.budgetother|approvefinal.budgetother', ['only' => ['approvalReturn']]);



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
                return $q->whereNull('budget_approvals.other_first_approved_at');
            }
            if($approval_type_id==2){
            return $q->whereNotNull('budget_approvals.other_first_approved_at')->whereNull('budget_approvals.other_second_approved_at');
            }
            if($approval_type_id==3){
            return $q->whereNotNull('budget_approvals.other_second_approved_at')->whereNull('budget_approvals.other_third_approved_at');
            }
            if($approval_type_id==10){
            return $q->whereNotNull('budget_approvals.other_third_approved_at')->whereNull('budget_approvals.other_final_approved_at');
            }
        })
        //->whereNull('budgets.approved_at')
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
                $other_first_approved_at=date('Y-m-d h:i:s');
                $master->other_first_approved_by=$user->id;
                $master->other_first_approved_at=$other_first_approved_at;
                $master->timestamps=false;
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
                $other_second_approved_at=date('Y-m-d h:i:s');
                $master->other_second_approved_by=$user->id;
                $master->other_second_approved_at=$other_second_approved_at;
                $master->timestamps=false;
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
                $other_third_approved_at=date('Y-m-d h:i:s');
                $master->other_third_approved_by=$user->id;
                $master->other_third_approved_at=$other_third_approved_at;
                $master->timestamps=false;
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
                $other_final_approved_at=date('Y-m-d h:i:s');
                $master->other_final_approved_by=$user->id;
                $master->other_final_approved_at=$other_final_approved_at;
                $master->timestamps=false;
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
            'other_ready_to_approve_id' => NULL,
        ]);

        $this->approvalcommenthistory->create([
                'model_id'=>$id,
                'model_type'=>'budget_others',
                'comments'=>$returned_coments,
                'comments_by'=>$user->id,
                'comments_at'=>$returned_at
        ]);
        return response()->json(array('success' => true,'type' => $aproval_type, 'message' => 'Returned Successfully'), 200);
    }
}
