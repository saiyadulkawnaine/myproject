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
class BudgetApprovalStatusController extends Controller
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
        //$this->middleware('permission:approvefirst.budgetfabric',   ['only' => ['firstapproved']]);
        //$this->middleware('permission:approvesecond.budgetfabric', ['only' => ['secondapproved']]);
        //$this->middleware('permission:approvethird.budgetfabric',   ['only' => ['thirdapproved']]);
        //$this->middleware('permission:approvefinal.budgetfabric', ['only' => ['finalapproved']]);
        //$this->middleware('permission:approvefirst.budgetfabric|approvesecond.budgetfabric|approvethird.budgetfabric|approvefinal.budgetfabric', ['only' => ['approvalReturn']]);



    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.BudgetApprovalStatus',['company'=>$company,'buyer'=>$buyer]);
    }
	public function reportData() {
        $approval_type_id=request('approval_type_id');
        $rows=$this->budgetapproval
        ->join('budgets',function($join){
            $join->on('budget_approvals.budget_id','=','budgets.id');
        })
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

        ->leftJoin('users as fabric_first_approval',function($join){
            $join->on('fabric_first_approval.id','=','budget_approvals.fabric_first_approved_by');
        })
        ->leftJoin('users as fabric_second_approval',function($join){
            $join->on('fabric_second_approval.id','=','budget_approvals.fabric_second_approved_by');
        })
        ->leftJoin('users as fabric_third_approval',function($join){
            $join->on('fabric_third_approval.id','=','budget_approvals.fabric_third_approved_by');
        })
        ->leftJoin('users as fabric_final_approval',function($join){
            $join->on('fabric_final_approval.id','=','budget_approvals.fabric_final_approved_by');
        })

        ->leftJoin('users as yarn_first_approval',function($join){
            $join->on('yarn_first_approval.id','=','budget_approvals.yarn_first_approved_by');
        })
        ->leftJoin('users as yarn_second_approval',function($join){
            $join->on('yarn_second_approval.id','=','budget_approvals.yarn_second_approved_by');
        })
        ->leftJoin('users as yarn_third_approval',function($join){
            $join->on('yarn_third_approval.id','=','budget_approvals.yarn_third_approved_by');
        })
        ->leftJoin('users as yarn_final_approval',function($join){
            $join->on('yarn_final_approval.id','=','budget_approvals.yarn_final_approved_by');
        })

        ->leftJoin('users as yarndye_first_approval',function($join){
            $join->on('yarndye_first_approval.id','=','budget_approvals.yarndye_first_approved_by');
        })
        ->leftJoin('users as yarndye_second_approval',function($join){
            $join->on('yarndye_second_approval.id','=','budget_approvals.yarndye_second_approved_by');
        })
        ->leftJoin('users as yarndye_third_approval',function($join){
            $join->on('yarndye_third_approval.id','=','budget_approvals.yarndye_third_approved_by');
        })
        ->leftJoin('users as yarndye_final_approval',function($join){
            $join->on('yarndye_final_approval.id','=','budget_approvals.yarndye_final_approved_by');
        })


        ->leftJoin('users as fabricprod_first_approval',function($join){
            $join->on('fabricprod_first_approval.id','=','budget_approvals.fabricprod_first_approved_by');
        })
        ->leftJoin('users as fabricprod_second_approval',function($join){
            $join->on('fabricprod_second_approval.id','=','budget_approvals.fabricprod_second_approved_by');
        })
        ->leftJoin('users as fabricprod_third_approval',function($join){
            $join->on('fabricprod_third_approval.id','=','budget_approvals.fabricprod_third_approved_by');
        })
        ->leftJoin('users as fabricprod_final_approval',function($join){
            $join->on('fabricprod_final_approval.id','=','budget_approvals.fabricprod_final_approved_by');
        })

        ->leftJoin('users as embel_first_approval',function($join){
            $join->on('embel_first_approval.id','=','budget_approvals.embel_first_approved_by');
        })
        ->leftJoin('users as embel_second_approval',function($join){
            $join->on('embel_second_approval.id','=','budget_approvals.embel_second_approved_by');
        })
        ->leftJoin('users as embel_third_approval',function($join){
            $join->on('embel_third_approval.id','=','budget_approvals.embel_third_approved_by');
        })
        ->leftJoin('users as embel_final_approval',function($join){
            $join->on('embel_final_approval.id','=','budget_approvals.embel_final_approved_by');
        }) 
        ->leftJoin('users as trim_first_approval',function($join){
            $join->on('trim_first_approval.id','=','budget_approvals.trim_first_approved_by');
        })
        ->leftJoin('users as trim_second_approval',function($join){
            $join->on('trim_second_approval.id','=','budget_approvals.trim_second_approved_by');
        })
        ->leftJoin('users as trim_third_approval',function($join){
            $join->on('trim_third_approval.id','=','budget_approvals.trim_third_approved_by');
        })
        ->leftJoin('users as trim_final_approval',function($join){
            $join->on('trim_final_approval.id','=','budget_approvals.trim_final_approved_by');
        })
        ->leftJoin('users as other_first_approval',function($join){
            $join->on('other_first_approval.id','=','budget_approvals.other_first_approved_by');
        })
        ->leftJoin('users as other_second_approval',function($join){
            $join->on('other_second_approval.id','=','budget_approvals.other_second_approved_by');
        })
        ->leftJoin('users as other_third_approval',function($join){
            $join->on('other_third_approval.id','=','budget_approvals.other_third_approved_by');
        })
        ->leftJoin('users as other_final_approval',function($join){
            $join->on('other_final_approval.id','=','budget_approvals.other_final_approved_by');
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
        ->orderBy('budgets.id','desc')
        ->get([
        'budgets.*',
        'jobs.id as job_id',
        'jobs.job_no',
        'styles.style_ref',
        'styles.id as style_id',
        'buyers.code as buyer_name',
        'buyingagents.name as buying_agent',
        'teams.name as team_name',
        'currencies.code as currency_code',
        'companies.code as company_name',
        'uoms.code as uom_code',
        'fabric_first_approval.name as fabric_first_approved_by',
        'budget_approvals.fabric_first_approved_at',
        'fabric_second_approval.name as fabric_second_approved_by',
        'budget_approvals.fabric_second_approved_at',
        'fabric_third_approval.name as fabric_third_approved_by',
        'budget_approvals.fabric_third_approved_at',
        'fabric_final_approval.name as fabric_final_approved_by',
        'budget_approvals.fabric_final_approved_at',

        'yarn_first_approval.name as yarn_first_approved_by',
        'budget_approvals.yarn_first_approved_at',
        'yarn_second_approval.name as yarn_second_approved_by',
        'budget_approvals.yarn_second_approved_at',
        'yarn_third_approval.name as yarn_third_approved_by',
        'budget_approvals.yarn_third_approved_at',
        'yarn_final_approval.name as yarn_final_approved_by',
        'budget_approvals.yarn_final_approved_at',

        'yarndye_first_approval.name as yarndye_first_approved_by',
        'budget_approvals.yarndye_first_approved_at',
        'yarndye_second_approval.name as yarndye_second_approved_by',
        'budget_approvals.yarndye_second_approved_at',
        'yarndye_third_approval.name as yarndye_third_approved_by',
        'budget_approvals.yarndye_third_approved_at',
        'yarndye_final_approval.name as yarndye_final_approved_by',
        'budget_approvals.yarndye_final_approved_at',

        'fabricprod_first_approval.name as fabricprod_first_approved_by',
        'budget_approvals.fabricprod_first_approved_at',
        'fabricprod_second_approval.name as fabricprod_second_approved_by',
        'budget_approvals.fabricprod_second_approved_at',
        'fabricprod_third_approval.name as fabricprod_third_approved_by',
        'budget_approvals.fabricprod_third_approved_at',
        'fabricprod_final_approval.name as fabricprod_final_approved_by',
        'budget_approvals.fabricprod_final_approved_at',

        'embel_first_approval.name as embel_first_approved_by',
        'budget_approvals.embel_first_approved_at',
        'embel_second_approval.name as embel_second_approved_by',
        'budget_approvals.embel_second_approved_at',
        'embel_third_approval.name as embel_third_approved_by',
        'budget_approvals.embel_third_approved_at',
        'embel_final_approval.name as embel_final_approved_by',
        'budget_approvals.embel_final_approved_at',

        'trim_first_approval.name as trim_first_approved_by',
        'budget_approvals.trim_first_approved_at',
        'trim_second_approval.name as trim_second_approved_by',
        'budget_approvals.trim_second_approved_at',
        'trim_third_approval.name as trim_third_approved_by',
        'budget_approvals.trim_third_approved_at',
        'trim_final_approval.name as trim_final_approved_by',
        'budget_approvals.trim_final_approved_at',

        'other_first_approval.name as other_first_approved_by',
        'budget_approvals.other_first_approved_at',
        'other_second_approval.name as other_second_approved_by',
        'budget_approvals.other_second_approved_at',
        'other_third_approval.name as other_third_approved_by',
        'budget_approvals.other_third_approved_at',
        'other_final_approval.name as other_final_approved_by',
        'budget_approvals.other_final_approved_at',
        ])
        ->map(function($rows){
            $rows->budget_date=date('Y-m-d',strtotime($rows->budget_date));
            $rows->fabric_first_approved_by=$rows->fabric_first_approved_by."  ". $rows->fabric_first_approved_at;
            $rows->fabric_second_approved_by=$rows->fabric_second_approved_by."  ". $rows->fabric_second_approved_at;
            $rows->fabric_third_approved_by=$rows->fabric_third_approved_by."  ". $rows->fabric_third_approved_at;
            $rows->fabric_final_approved_by=$rows->fabric_final_approved_by."  ". $rows->fabric_final_approved_at;

            $rows->yarn_first_approved_by=$rows->yarn_first_approved_by."  ". $rows->yarn_first_approved_at;
            $rows->yarn_second_approved_by=$rows->yarn_second_approved_by."  ". $rows->yarn_second_approved_at;
            $rows->yarn_third_approved_by=$rows->yarn_third_approved_by."  ". $rows->yarn_third_approved_at;
            $rows->yarn_final_approved_by=$rows->yarn_final_approved_by."  ". $rows->yarn_final_approved_at;

            $rows->yarndye_first_approved_by=$rows->yarndye_first_approved_by."  ". $rows->yarndye_first_approved_at;
            $rows->yarndye_second_approved_by=$rows->yarndye_second_approved_by."  ". $rows->yarndye_second_approved_at;
            $rows->yarndye_third_approved_by=$rows->yarndye_third_approved_by."  ". $rows->yarndye_third_approved_at;
            $rows->yarndye_final_approved_by=$rows->yarndye_final_approved_by."  ". $rows->yarndye_final_approved_at;

            $rows->fabricprod_first_approved_by=$rows->fabricprod_first_approved_by."  ". $rows->fabricprod_first_approved_at;
            $rows->fabricprod_second_approved_by=$rows->fabricprod_second_approved_by."  ". $rows->fabricprod_second_approved_at;
            $rows->fabricprod_third_approved_by=$rows->fabricprod_third_approved_by."  ". $rows->fabricprod_third_approved_at;
            $rows->fabricprod_final_approved_by=$rows->fabricprod_final_approved_by."  ". $rows->fabricprod_final_approved_at;

            $rows->embel_first_approved_by=$rows->embel_first_approved_by."  ". $rows->embel_first_approved_at;
            $rows->embel_second_approved_by=$rows->embel_second_approved_by."  ". $rows->embel_second_approved_at;
            $rows->embel_third_approved_by=$rows->embel_third_approved_by."  ". $rows->embel_third_approved_at;
            $rows->embel_final_approved_by=$rows->embel_final_approved_by."  ". $rows->embel_final_approved_at;

            $rows->trim_first_approved_by=$rows->trim_first_approved_by."  ". $rows->trim_first_approved_at;
            $rows->trim_second_approved_by=$rows->trim_second_approved_by."  ". $rows->trim_second_approved_at;
            $rows->trim_third_approved_by=$rows->trim_third_approved_by."  ". $rows->trim_third_approved_at;
            $rows->trim_final_approved_by=$rows->trim_final_approved_by."  ". $rows->trim_final_approved_at;

            $rows->other_first_approved_by=$rows->other_first_approved_by."  ". $rows->other_first_approved_at;
            $rows->other_second_approved_by=$rows->other_second_approved_by."  ". $rows->other_second_approved_at;
            $rows->other_third_approved_by=$rows->other_third_approved_by."  ". $rows->other_third_approved_at;
            $rows->other_final_approved_by=$rows->other_final_approved_by."  ". $rows->other_final_approved_at;



            return $rows;
        });
        echo json_encode($rows);
    }

    public function reportRtnData() {
        $rows=$this->budgetapproval
        ->join('budgets',function($join){
            $join->on('budget_approvals.budget_id','=','budgets.id');
        })
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

        ->leftJoin('users as fabric_returned',function($join){
            $join->on('fabric_returned.id','=','budget_approvals.fabric_returned_by');
        })
        ->leftJoin('users as yarn_returned',function($join){
            $join->on('yarn_returned.id','=','budget_approvals.yarn_returned_by');
        })
        ->leftJoin('users as yarndye_returned',function($join){
            $join->on('yarndye_returned.id','=','budget_approvals.yarndye_returned_by');
        })
        ->leftJoin('users as fabricprod_returned',function($join){
            $join->on('fabricprod_returned.id','=','budget_approvals.fabricprod_returned_by');
        })

        ->leftJoin('users as embel_returned',function($join){
            $join->on('embel_returned.id','=','budget_approvals.embel_returned_by');
        })
        ->leftJoin('users as trim_returned',function($join){
            $join->on('trim_returned.id','=','budget_approvals.trim_returned_by');
        })
        ->leftJoin('users as other_returned',function($join){
            $join->on('other_returned.id','=','budget_approvals.other_returned_by');
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
        ->whereNotNull('fabric_returned_at')
        ->orWhereNotNull('yarn_returned_at')
        ->orWhereNotNull('yarndye_returned_at')
        ->orWhereNotNull('fabricprod_returned_at')
        ->orWhereNotNull('embel_returned_at')
        ->orWhereNotNull('trim_returned_at')
        ->orWhereNotNull('other_returned_at')
        ->orderBy('budgets.id','desc')
        ->get([
        'budgets.*',
        'jobs.id as job_id',
        'jobs.job_no',
        'styles.style_ref',
        'styles.id as style_id',
        'buyers.code as buyer_name',
        'buyingagents.name as buying_agent',
        'teams.name as team_name',
        'currencies.code as currency_code',
        'companies.code as company_name',
        'uoms.code as uom_code',

        'fabric_returned.name as fabric_returned_by',
        'budget_approvals.fabric_returned_at',
        'budget_approvals.fabric_returned_coments',

        'yarn_returned.name as yarn_returned_by',
        'budget_approvals.yarn_returned_at',
        'budget_approvals.yarn_returned_coments',

        'yarndye_returned.name as yarndye_returned_by',
        'budget_approvals.yarndye_returned_at',
        'budget_approvals.yarndye_returned_coments',

        'fabricprod_returned.name as fabricprod_returned_by',
        'budget_approvals.fabricprod_returned_at',
        'budget_approvals.fabricprod_returned_coments',

        'embel_returned.name as embel_returned_by',
        'budget_approvals.embel_returned_at',
        'budget_approvals.embel_returned_coments',

        'trim_returned.name as trim_returned_by',
        'budget_approvals.trim_returned_at',
        'budget_approvals.trim_returned_coments',

        'other_returned.name as other_returned_by',
        'budget_approvals.other_returned_at',
        'budget_approvals.other_returned_coments',
        ])
        ->map(function($rows){
            $rows->budget_date=date('Y-m-d',strtotime($rows->budget_date));
            $rows->fabric_returned_by=$rows->fabric_returned_by."  ". $rows->fabric_returned_at;
            $rows->yarn_returned_by=$rows->yarn_returned_by."  ". $rows->yarn_returned_at;
            $rows->yarndye_returned_by=$rows->yarndye_returned_by."  ". $rows->yarndye_returned_at;
            $rows->fabricprod_returned_by=$rows->fabricprod_returned_by."  ". $rows->fabricprod_returned_at;
            $rows->embel_returned_by=$rows->embel_returned_by."  ". $rows->embel_returned_at;
            $rows->trim_returned_by=$rows->trim_returned_by."  ". $rows->trim_returned_at;
            $rows->other_returned_by=$rows->other_returned_by."  ". $rows->other_returned_at;
            return $rows;
        });
        echo json_encode($rows);
    }
    
}
