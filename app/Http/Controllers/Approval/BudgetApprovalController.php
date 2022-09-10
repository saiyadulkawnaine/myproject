<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Sms;
class BudgetApprovalController extends Controller
{
    private $budget;
    private $user;
    private $buyer;
    private $company;

    public function __construct(
		BudgetRepository $budget,
		UserRepository $user,
		BuyerRepository $buyer,
		CompanyRepository $company

    ) {
        $this->budget = $budget;
        $this->user = $user;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->middleware('auth');
        //$this->middleware('permission:approve.budgets',   ['only' => ['approved', 'index','reportData','reportDataApp','unapproved']]);
        //$this->middleware('permission:view.budgetapproval',   ['only' => [ 'index','reportData','reportDataApp']]);
        $this->middleware('permission:approve.budgets',   ['only' => ['approved','unapproved']]);


    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.BudgetApproval',['company'=>$company,'buyer'=>$buyer]);
    }
	public function reportData() {
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
        ->whereNull('budgets.approved_at')
        ->where([['budgets.ready_to_approve_id','=',1]])
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

    public function approved (Request $request)
    {
    	$id=request('id',0);
    	$master=$this->budget->find($id);
		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');

        $master->approved_by=$user->id;
        $master->approved_at=$approved_at;
        $master->unapproved_by=NULL;
        $master->unapproved_at=NULL;
        $master->timestamps=false;
        $budget=$master->save();
		/*$budget = $this->budget->update($id,[
			'approved_by' => $user->id,  
			'approved_at' =>  $approved_at,
            'unapproved_by' => NULL,  
            'unapproved_at' => NULL,
		]);*/

		if($budget){
		return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
		}
    }

    public function reportDataApp() {
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
        ->whereNotNull('budgets.approved_at')
        ->orderBy('budgets.id','desc')
        ->get([
        'budgets.*',
        'jobs.id as job_id',
        'jobs.job_no',
        'styles.style_ref',
        'buyers.code as buyer_name',
        'teams.name as team_name',
        'currencies.code as currency_code',
        'companies.code as company_name',
        'uoms.code as uom_code'
        ])
        ->map(function($rows){
            $rows->budget_date=date('Y-m-d',strtotime($rows->budget_date));
            return $rows;
        });
        echo json_encode($rows);
    }

    public function unapproved (Request $request)
    {
        $id=request('id',0);
        $master=$this->budget->find($id);
        $user = \Auth::user();
        $unapproved_at=date('Y-m-d h:i:s');
        $unapproved_count=$master->unapproved_count+1;
        $master->approved_by=NUll;
        $master->approved_at=NUll;
        $master->unapproved_by=$user->id;
        $master->unapproved_at=$unapproved_at;
        $master->unapproved_count=$unapproved_count;
        $master->timestamps=false;
        $budget=$master->save();
        /*$budget = $this->budget->update($id,[
            'approved_by' => NULL,  
            'approved_at' =>  NULL,
            'unapproved_by' => $user->id,  
            'unapproved_at' =>  $unapproved_at,
            'unapproved_count' =>  $unapproved_count
        ]);*/

        if($budget){
        return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
        }
    }
}
