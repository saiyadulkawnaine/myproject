<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\ProfitcenterRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use Illuminate\Support\Carbon;

class CentralBudgetReportController extends Controller
{

  private $subsection;
  private $wstudylinesetup;
  private $prodgmtsewing;
  private $buyer;
  private $company;
  private $profitcenter;
  private $supplier;
  private $location;

  public function __construct(
    SubsectionRepository $subsection,
    WstudyLineSetupRepository $wstudylinesetup,
    ProdGmtSewingRepository $prodgmtsewing,
    CompanyRepository $company, 
    ProfitcenterRepository $profitcenter, 
    LocationRepository $location, 
    SupplierRepository $supplier, 
    BuyerRepository $buyer
  )
  {
    $this->subsection                = $subsection;
    $this->wstudylinesetup           = $wstudylinesetup;
    $this->prodgmtsewing             = $prodgmtsewing;
    $this->company = $company;
    $this->profitcenter = $profitcenter;
    $this->buyer = $buyer;
    $this->location = $location;
    $this->supplier = $supplier;
    $this->middleware('auth');
  }

  public function index() {
    return Template::loadView('Report.CentralBudget');
  }

  public function reportData() {
    $date_from=request('date_from',0);
    $date_to =request('date_to',0);

    $date = Carbon::parse($date_from);
    $now = Carbon::parse($date_to);
    $diff = $date->diffInMonths($now);
    $monthArr=[];
    for($i=0;$i<=$diff;$i++){
      $month=date('M-y',strtotime($date));
      $monthArr[$month]=$month;
      $date->addMonth();
    }

    $companies=$this->company->orderBy('name')->get(['id','code','name']);
    $otherType=array_prepend(config('bprs.otherType'),'Select',0);
    
    $com=[];
    $comMonth=[];
    $comMonthInc=[];
    $comMonthPro=[];
    $comMonthExp=[];

    $monthInc=[];
    $monthExp=[];
    $monthPro=[];

    $monthNonCashExp=[];
    $typeMonthNonCashExp=[];

    $monthTloan=[];
    $typeMonthTloan=[];
   
    $budgets = \DB::select("
      select
      acc_beps.company_id,
      companies.code as company_code,
      profitcenters.code as profit_code,
      acc_beps.profitcenter_id,
      acc_beps.start_date,
      acc_beps.end_date,
      acc_chart_sub_groups.acc_chart_group_id,
      acc_chart_ctrl_heads.id,
      acc_chart_ctrl_heads.code,
      acc_chart_ctrl_heads.name,
      acc_chart_ctrl_heads.other_type_id,
      acc_bep_entries.amount
      from
      acc_beps
      join acc_bep_entries on acc_bep_entries.acc_bep_id=acc_beps.id
      join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
      join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
      join companies on companies.id=acc_beps.company_id
      join profitcenters on profitcenters.id=acc_beps.profitcenter_id
      where acc_beps.start_date >= ?
      and acc_beps.end_date <= ?
      order by
      acc_beps.company_id
    ",[$date_from,$date_to]);
    $budgetdats=collect($budgets);
    foreach($budgetdats as $data ){
      $index=$data->company_id."-".$data->profitcenter_id;
      $index_name=$data->company_code. " (".$data->profit_code.")";
      foreach($monthArr as $key=>$value){
        $com[$index]=$index_name;
        $comMonth[$index][$key]=0;

        $comMonthInc[$index][$key]=0;
        $comMonthExp[$index][$key]=0;
        $comMonthPro[$index][$key]=0;

        $monthInc[$key]=0;
        $monthExp[$key]=0;
        $monthPro[$key]=0;
        //===========Non Cash==============
        if($data->other_type_id==90 || $data->other_type_id==95){
          $monthNonCashExp['other_type_id'][$data->other_type_id]=$otherType[$data->other_type_id];
          $monthNonCashExp['bud'][$key]=0;
          $typeMonthNonCashExp['bud'][$data->other_type_id][$key]=0;
        }
      }
    }

    $budtloans = \DB::select("
      select
      bank_accounts.company_id,
      companies.code as company_code,
      commercial_heads.id as commercial_head_id,
      commercial_heads.name as commercial_head_name,
      acc_term_loan_installments.due_date,
      acc_term_loan_installments.amount
      from
      acc_term_loans
      join acc_term_loan_installments on acc_term_loans.id=acc_term_loan_installments.acc_term_loan_id
      join bank_accounts on bank_accounts.id=acc_term_loans.bank_account_id
      join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
      join companies on companies.id=bank_accounts.company_id
      where acc_term_loan_installments.due_date >= ?
      and acc_term_loan_installments.due_date <= ?
      and acc_term_loans.term_loan_for=1
      order by
      bank_accounts.company_id
    ",[$date_from,$date_to]);

    $budtloandats=collect($budtloans);
    foreach($budtloandats as $data ){
      foreach($monthArr as $key=>$value){
        $monthTloan['loan_type_id'][$data->commercial_head_id]=$data->commercial_head_name;
        $monthTloan['bud'][$key]=0;
        $typeMonthTloan['bud'][$data->commercial_head_id][$key]=0;
      }
    }
    
    foreach($budgetdats as $data ){
      $index=$data->company_id."-".$data->profitcenter_id;
      $month=date('M-y',strtotime($data->start_date));
      if($data->acc_chart_group_id ==16 || $data->acc_chart_group_id ==25){
        $comMonthInc[$index][$month]+=$data->amount;
        $monthInc[$month]+=$data->amount;
      }
      else{
         $comMonthExp[$index][$month]+=$data->amount;
         $monthExp[$month]+=$data->amount;
         //===========Non Cash==============
        if($data->other_type_id==90 || $data->other_type_id==95){
          //$monthNonCashExp['other_type_id'][$data->other_type_id]=$otherType[$data->other_type_id];
          $monthNonCashExp['bud'][$month]+=$data->amount;
          $typeMonthNonCashExp['bud'][$data->other_type_id][$month]+=$data->amount;
        }
      }
      $comMonthPro[$index][$month]=($comMonthInc[$index][$month]-$comMonthExp[$index][$month]);
      $monthPro[$month]=($monthInc[$month]-$monthExp[$month]);
      
    }

    foreach($budtloandats as $data ){
        $month=date('M-y',strtotime($data->due_date));
        $monthTloan['bud'][$month]+=$data->amount;
        $typeMonthTloan['bud'][$data->commercial_head_id][$month]+=$data->amount;
    }

    return Template::loadView('Report.CentralBudgetMatrix',[
    'date_from'=>$date_from,
    'date_to'=>$date_to,
    'companies'=>$companies,
    'monthArr'=>$monthArr,
    'com'=>$com,
    'comMonth'=>$comMonth,

    'comMonthInc'=>$comMonthInc,
    'comMonthExp'=>$comMonthExp,
    'comMonthPro'=>$comMonthPro,
    
    'monthInc'=>$monthInc,
    'monthExp'=>$monthExp,
    'monthPro'=>$monthPro,

    'monthNonCashExp'=>$monthNonCashExp,
    'typeMonthNonCashExp'=>$typeMonthNonCashExp,

    'monthTloan'=>$monthTloan,
    'typeMonthTloan'=>$typeMonthTloan,

    ]);
  }

  public function reportDetail() {
    $date_from=request('date_from',0);
    $date_to =request('date_to',0);
    $company_id =request('company_id',0);
    $profitcenter_id =request('profitcenter_id',0);

    $date = Carbon::parse($date_from);
    $now = Carbon::parse($date_to);
    $diff = $date->diffInMonths($now);
    $monthArr=[];
    for($i=0;$i<=$diff;$i++){
    $month=date('M-y',strtotime($date));
    $monthArr[$month]=$month;
    $date->addMonth();
    }

    $companies=$this->company->find($company_id);
    $profitcenters=$this->profitcenter->find($profitcenter_id);
    $otherType=array_prepend(config('bprs.otherType'),'Select',0);
    
    

    $codeInc=[];
    $codeExpFix=[];
    $codeExpVar=[];

    $codeMonthInc=[];
    $codeMonthExpFix=[];
    $codeMonthExpVar=[];

    //$codeMonthPro=[];

    $monthInc=[];
    $monthExpFix=[];
    $monthExpVar=[];
    $monthExp=[];
    $monthPro=[];

    $monthNonCashExp=[];
    $typeMonthNonCashExp=[];

    $monthTloan=[];
    $typeMonthTloan=[];
   
    $budgets = \DB::select("
      select
      acc_beps.company_id,
      acc_beps.profitcenter_id,
      profitcenters.code as profitcenter_code,
      acc_beps.start_date,
      acc_beps.end_date,
      acc_chart_sub_groups.acc_chart_group_id,
      acc_chart_ctrl_heads.id,
      acc_chart_ctrl_heads.code,
      acc_chart_ctrl_heads.name,
      acc_chart_ctrl_heads.other_type_id,
      acc_chart_ctrl_heads.expense_type_id,
      acc_bep_entries.amount
      from
      acc_beps
      join acc_bep_entries on acc_bep_entries.acc_bep_id=acc_beps.id
      join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
      join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
      join companies on companies.id=acc_beps.company_id
      join profitcenters on profitcenters.id=acc_beps.profitcenter_id
      where acc_beps.start_date >= ?
      and acc_beps.end_date <= ?
      and acc_beps.company_id = ?
      and acc_beps.profitcenter_id = ?
      order by
      acc_chart_sub_groups.acc_chart_group_id
    ",[$date_from,$date_to,$company_id,$profitcenter_id]);
    $budgetdats=collect($budgets);
    foreach($budgetdats as $data ){
      foreach($monthArr as $key=>$value){

        if($data->acc_chart_group_id ==16 || $data->acc_chart_group_id ==25){
          $codeInc[$data->id]=$data->name;
          $codeMonthInc[$data->id][$key]=0;
        }
        else{
          if($data->expense_type_id==2){
              $codeExpFix[$data->id]=$data->name;
              $codeMonthExpFix[$data->id][$key]=0;
          }
          else{
             $codeExpVar[$data->id]=$data->name;
             $codeMonthExpVar[$data->id][$key]=0;
          }
          if($data->other_type_id==90 || $data->other_type_id==95){
          $monthNonCashExp['other_type_id'][$data->other_type_id]=$otherType[$data->other_type_id];
          $monthNonCashExp['bud'][$key]=0;
          $typeMonthNonCashExp['bud'][$data->other_type_id][$key]=0;
          }
        }
        
        $codeMonthPro[$data->id][$key]=0;

        $monthInc[$key]=0;
        $monthExpFix[$key]=0;
        $monthExpVar[$key]=0;
        $monthExp[$key]=0;
        $monthPro[$key]=0;
      }
    }

    $budtloans = \DB::select("
      select
      bank_accounts.company_id,
      companies.code as company_code,
      commercial_heads.id as commercial_head_id,
      commercial_heads.name as commercial_head_name,
      acc_term_loan_installments.due_date,
      acc_term_loan_installments.amount
      from
      acc_term_loans
      join acc_term_loan_installments on acc_term_loans.id=acc_term_loan_installments.acc_term_loan_id
      join bank_accounts on bank_accounts.id=acc_term_loans.bank_account_id
      join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
      join companies on companies.id=bank_accounts.company_id
      where acc_term_loan_installments.due_date >= ?
      and acc_term_loan_installments.due_date <= ?
      and bank_accounts.company_id = ?
      and acc_term_loans.term_loan_for=1
      order by
      bank_accounts.company_id
    ",[$date_from,$date_to,$company_id]);

    $budtloandats=collect($budtloans);
    foreach($budtloandats as $data ){
      foreach($monthArr as $key=>$value){
        $monthTloan['loan_type_id'][$data->commercial_head_id]=$data->commercial_head_name;
        $monthTloan['bud'][$key]=0;
        $typeMonthTloan['bud'][$data->commercial_head_id][$key]=0;
      }
    }

    foreach($budgetdats as $data ){
      $month=date('M-y',strtotime($data->start_date));
      if($data->acc_chart_group_id ==16 || $data->acc_chart_group_id ==25){
        $codeMonthInc[$data->id][$month]+=$data->amount;
        $monthInc[$month]+=$data->amount;
      }
      else{
        if($data->expense_type_id==2){
         $codeMonthExpFix[$data->id][$month]+=$data->amount;
         $monthExpFix[$month]+=$data->amount;
         $monthExp[$month]+=$data->amount;
        }
        else{
          $codeMonthExpVar[$data->id][$month]+=$data->amount;
          $monthExpVar[$month]+=$data->amount;
          $monthExp[$month]+=$data->amount;
        }
        //===========Non Cash==============
        if($data->other_type_id==90 || $data->other_type_id==95){
          $monthNonCashExp['bud'][$month]+=$data->amount;
          $typeMonthNonCashExp['bud'][$data->other_type_id][$month]+=$data->amount;
        }
      }
      $monthPro[$month]=($monthInc[$month]-$monthExp[$month]);
      
    }

    foreach($budtloandats as $data ){
        $month=date('M-y',strtotime($data->due_date));
        $monthTloan['bud'][$month]+=$data->amount;
        $typeMonthTloan['bud'][$data->commercial_head_id][$month]+=$data->amount;
    }

    return Template::loadView('Report.CentralBudgetMatrixDetail',[
    'date_from'=>$date_from,
    'date_to'=>$date_to,
    'companies'=>$companies,
    'profitcenters'=>$profitcenters,
    'monthArr'=>$monthArr,

    'codeInc'=>$codeInc,
    'codeExpFix'=>$codeExpFix,
    'codeExpVar'=>$codeExpVar,
    'codeMonthInc'=>$codeMonthInc,
    'codeMonthExpFix'=>$codeMonthExpFix,
    'codeMonthExpVar'=>$codeMonthExpVar,
    //'comMonthPro'=>$comMonthPro,
    
    'monthInc'=>$monthInc,
    'monthExpFix'=>$monthExpFix,
    'monthExpVar'=>$monthExpVar,
    'monthExp'=>$monthExp,
    'monthPro'=>$monthPro,

    'monthNonCashExp'=>$monthNonCashExp,
    'typeMonthNonCashExp'=>$typeMonthNonCashExp,

    'monthTloan'=>$monthTloan,
    'typeMonthTloan'=>$typeMonthTloan,

    ]);
  }

  public function reportBudVsAcl() {
    $date_from=request('date_from',0);
    $date_to =request('date_to',0);

    $date = Carbon::parse($date_from);
    $now = Carbon::parse($date_to);
    $diff = $date->diffInMonths($now);
    $monthArr=[];
    for($i=0;$i<=$diff;$i++){
    $month=date('M-y',strtotime($date));
    $monthArr[$month]=$month;
    $date->addMonth();
    }

    $companies=$this->company->orderBy('name')->get(['id','code','name']);
    $otherType=array_prepend(config('bprs.otherType'),'Select',0);



    $com=[];
    $comMonth=[];
    $comMonthInc=[];
    $comMonthPro=[];
    $comMonthExp=[];

    $monthInc=[];
    $monthExp=[];
    $monthPro=[];

    $monthNonCashExp=[];
    $typeMonthNonCashExp=[];

    $monthTloan=[];
    $typeMonthTloan=[];

    $budgets = \DB::select("
    select
    acc_beps.company_id,
    companies.code as company_code,
    profitcenters.code as profit_code,
    acc_beps.profitcenter_id,
    acc_beps.start_date,
    acc_beps.end_date,
    acc_chart_sub_groups.acc_chart_group_id,
    acc_chart_ctrl_heads.id,
    acc_chart_ctrl_heads.code,
    acc_chart_ctrl_heads.name,
    acc_chart_ctrl_heads.other_type_id,
    acc_bep_entries.amount
    from
    acc_beps
    join acc_bep_entries on acc_bep_entries.acc_bep_id=acc_beps.id
    join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
    join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
    join companies on companies.id=acc_beps.company_id
    join profitcenters on profitcenters.id=acc_beps.profitcenter_id
    where acc_beps.start_date >= ?
    and acc_beps.end_date <= ?
    order by
    acc_beps.company_id
    ",[$date_from,$date_to]);
    $budgetdats=collect($budgets);
    foreach($budgetdats as $data ){
      $index=$data->company_id."-".$data->profitcenter_id;
      $index_name=$data->company_code. " (".$data->profit_code.")";
      foreach($monthArr as $key=>$value){
        $com[$index]=$index_name;
        $comMonth[$index][$key]=0;

        $comMonthInc['bud'][$index][$key]=0;
        $comMonthExp['bud'][$index][$key]=0;
        $comMonthPro['bud'][$index][$key]=0;

        $comMonthInc['acl'][$index][$key]=0;
        $comMonthExp['acl'][$index][$key]=0;
        $comMonthPro['acl'][$index][$key]=0;

        $comMonthInc['var'][$index][$key]=0;
        $comMonthExp['var'][$index][$key]=0;
        $comMonthPro['var'][$index][$key]=0;

        $monthInc['bud'][$key]=0;
        $monthExp['bud'][$key]=0;
        $monthPro['bud'][$key]=0;

        $monthInc['acl'][$key]=0;
        $monthExp['acl'][$key]=0;
        $monthPro['acl'][$key]=0;

        $monthInc['var'][$key]=0;
        $monthExp['var'][$key]=0;
        $monthPro['var'][$key]=0;
        //===========Non Cash==============
        if($data->other_type_id==90 || $data->other_type_id==95){
          $monthNonCashExp['other_type_id'][$data->other_type_id]=$otherType[$data->other_type_id];
          $monthNonCashExp['bud'][$key]=0;
          $monthNonCashExp['acl'][$key]=0;
          $monthNonCashExp['var'][$key]=0;
          $typeMonthNonCashExp['bud'][$data->other_type_id][$key]=0;
          $typeMonthNonCashExp['acl'][$data->other_type_id][$key]=0;
          $typeMonthNonCashExp['var'][$data->other_type_id][$key]=0;
        }
      }
    }

    $budtloans = \DB::select("
      select
      bank_accounts.company_id,
      companies.code as company_code,
      commercial_heads.id as commercial_head_id,
      commercial_heads.name as commercial_head_name,
      acc_term_loan_installments.due_date,
      acc_term_loan_installments.amount
      from
      acc_term_loans
      join acc_term_loan_installments on acc_term_loans.id=acc_term_loan_installments.acc_term_loan_id
      join bank_accounts on bank_accounts.id=acc_term_loans.bank_account_id
      join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
      join companies on companies.id=bank_accounts.company_id
      where acc_term_loan_installments.due_date >= ?
      and acc_term_loan_installments.due_date <= ?
      and acc_term_loans.term_loan_for=1
      order by
      bank_accounts.company_id
    ",[$date_from,$date_to]);

    $budtloandats=collect($budtloans);
    foreach($budtloandats as $data ){
      foreach($monthArr as $key=>$value){
        $monthTloan['loan_type_id'][$data->commercial_head_id]=$data->commercial_head_name;
        $monthTloan['bud'][$key]=0;
        $monthTloan['acl'][$key]=0;
        $monthTloan['var'][$key]=0;
        $typeMonthTloan['bud'][$data->commercial_head_id][$key]=0;
        $typeMonthTloan['acl'][$data->commercial_head_id][$key]=0;
        $typeMonthTloan['var'][$data->commercial_head_id][$key]=0;
      }
    }
    

    $acls = \DB::select("
    select
    acc_trans_prnts.company_id,
    companies.code as company_code,
    profitcenters.code as profit_code,
    acc_trans_chlds.profitcenter_id,
    acc_trans_prnts.trans_date,
    acc_chart_sub_groups.acc_chart_group_id,
    acc_chart_ctrl_heads.id,
    acc_chart_ctrl_heads.code,
    acc_chart_ctrl_heads.other_type_id,
    acc_chart_ctrl_heads.name,
    (CASE WHEN acc_chart_sub_groups.acc_chart_group_id = 1 or acc_chart_sub_groups.acc_chart_group_id = 4 or acc_chart_sub_groups.acc_chart_group_id = 7 or acc_chart_sub_groups.acc_chart_group_id = 16 or acc_chart_sub_groups.acc_chart_group_id = 25
    THEN abs(sum (acc_trans_chlds.amount))
    ELSE sum (acc_trans_chlds.amount)
    END ) AS amount
    from 
    acc_trans_prnts
    join acc_trans_chlds on acc_trans_chlds.acc_trans_prnt_id=acc_trans_prnts.id
    join acc_periods on acc_periods.id=acc_trans_prnts.acc_period_id
    join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
    join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
    join companies on companies.id=acc_trans_prnts.company_id
    join profitcenters on profitcenters.id=acc_trans_chlds.profitcenter_id
    where acc_trans_prnts.trans_date>=?
    and acc_trans_prnts.trans_date<=?
    and acc_chart_ctrl_heads.statement_type_id=2
    and acc_trans_prnts.deleted_at is null
    and acc_trans_chlds.deleted_at is null
    group by
    acc_trans_prnts.company_id,
    companies.code,
    profitcenters.code,
    acc_trans_chlds.profitcenter_id,
    acc_trans_prnts.trans_date,
    acc_chart_sub_groups.acc_chart_group_id,
    acc_chart_ctrl_heads.id,
    acc_chart_ctrl_heads.code,
    acc_chart_ctrl_heads.other_type_id,
    acc_chart_ctrl_heads.name
    order by acc_trans_prnts.company_id
    ",[$date_from,$date_to]);
    $acldats=collect($acls);
    foreach($acldats as $data ){
      $index=$data->company_id."-".$data->profitcenter_id;
      $index_name=$data->company_code. " (".$data->profit_code.")";
      foreach($monthArr as $key=>$value){
        $com[$index]=$index_name;
        $comMonth[$index][$key]=0;

        $comMonthInc['bud'][$index][$key]=0;
        $comMonthExp['bud'][$index][$key]=0;
        $comMonthPro['bud'][$index][$key]=0;

        $comMonthInc['acl'][$index][$key]=0;
        $comMonthExp['acl'][$index][$key]=0;
        $comMonthPro['acl'][$index][$key]=0;

        $comMonthInc['var'][$index][$key]=0;
        $comMonthExp['var'][$index][$key]=0;
        $comMonthPro['var'][$index][$key]=0;

        $monthInc['bud'][$key]=0;
        $monthExp['bud'][$key]=0;
        $monthPro['bud'][$key]=0;

        $monthInc['acl'][$key]=0;
        $monthExp['acl'][$key]=0;
        $monthPro['acl'][$key]=0;

        $monthInc['var'][$key]=0;
        $monthExp['var'][$key]=0;
        $monthPro['var'][$key]=0;
        //===========Non Cash==============
        if($data->other_type_id==90 || $data->other_type_id==95){
          $monthNonCashExp['other_type_id'][$data->other_type_id]=$otherType[$data->other_type_id];
          $monthNonCashExp['bud'][$key]=0;
          $monthNonCashExp['acl'][$key]=0;
          $monthNonCashExp['var'][$key]=0;
          $typeMonthNonCashExp['bud'][$data->other_type_id][$key]=0;
          $typeMonthNonCashExp['acl'][$data->other_type_id][$key]=0;
          $typeMonthNonCashExp['var'][$data->other_type_id][$key]=0;
        }
      }
    }

    $acltloans = \DB::select("
      select
      bank_accounts.company_id,
      companies.code as company_code,
      commercial_heads.id as commercial_head_id,
      commercial_heads.name,
      acc_term_loan_payments.payment_date,
      acc_term_loan_payments.amount,
      acc_term_loan_payments.interest_amount
      from
      acc_term_loans
      join acc_term_loan_installments on acc_term_loans.id=acc_term_loan_installments.acc_term_loan_id
      join acc_term_loan_payments on acc_term_loan_payments.acc_term_loan_installment_id=acc_term_loan_installments.id
      join bank_accounts on bank_accounts.id=acc_term_loans.bank_account_id
      join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
      join companies on companies.id=bank_accounts.company_id
      where acc_term_loan_payments.payment_date >= ?
      and acc_term_loan_payments.payment_date <= ?
      and acc_term_loans.term_loan_for=1
      order by
      bank_accounts.company_id
    ",[$date_from,$date_to]);

    $acltloandats=collect($acltloans);
    foreach($acltloandats as $data ){
      foreach($monthArr as $key=>$value){
        $monthTloan['loan_type_id'][$data->commercial_head_id]=$data->commercial_head_name;
        $monthTloan['bud'][$key]=0;
        $monthTloan['acl'][$key]=0;
        $monthTloan['var'][$key]=0;
        $typeMonthTloan['bud'][$data->commercial_head_id][$key]=0;
        $typeMonthTloan['acl'][$data->commercial_head_id][$key]=0;
        $typeMonthTloan['var'][$data->commercial_head_id][$key]=0;
      }
    }


    foreach($budgetdats as $data ){
      $index=$data->company_id."-".$data->profitcenter_id;
      $month=date('M-y',strtotime($data->start_date));
      if($data->acc_chart_group_id ==16 || $data->acc_chart_group_id ==25){
      $comMonthInc['bud'][$index][$month]+=$data->amount;
      $comMonthInc['var'][$index][$month]+=$data->amount;
      $monthInc['bud'][$month]+=$data->amount;
      $monthInc['var'][$month]+=$data->amount;
      }
      else{
        $comMonthExp['bud'][$index][$month]+=$data->amount;
        $comMonthExp['var'][$index][$month]+=$data->amount;
        $monthExp['bud'][$month]+=$data->amount;
        $monthExp['var'][$month]+=$data->amount;


        if($data->other_type_id==90 || $data->other_type_id==95){
          //$monthNonCashExp['other_type_id'][$data->other_type_id]=$data->other_type_id;
          $monthNonCashExp['bud'][$month]+=$data->amount;
          $monthNonCashExp['var'][$month]+=$data->amount;
          $typeMonthNonCashExp['bud'][$data->other_type_id][$month]+=$data->amount;
          $typeMonthNonCashExp['var'][$data->other_type_id][$month]+=$data->amount;
        }
      }
      $comMonthPro['bud'][$index][$month]=($comMonthInc['bud'][$index][$month]-$comMonthExp['bud'][$index][$month]);
      $comMonthPro['var'][$index][$month]=($comMonthInc['bud'][$index][$month]-$comMonthExp['bud'][$index][$month]);
      $monthPro['bud'][$month]=($monthInc['bud'][$month]-$monthExp['bud'][$month]);
      $monthPro['var'][$month]=($monthInc['bud'][$month]-$monthExp['bud'][$month]);
    }

    foreach($acldats as $data ){
      $index=$data->company_id."-".$data->profitcenter_id;
      $month=date('M-y',strtotime($data->trans_date));
      if($data->acc_chart_group_id ==16 || $data->acc_chart_group_id ==25){
        $comMonthInc['acl'][$index][$month]+=$data->amount;
        $comMonthInc['var'][$index][$month]=($comMonthInc['acl'][$index][$month] - $comMonthInc['bud'][$index][$month]);
        $monthInc['acl'][$month]+=$data->amount;
        $monthInc['var'][$month]=($monthInc['acl'][$month] - $monthInc['bud'][$month]);
      }
      else{
        $comMonthExp['acl'][$index][$month]+=$data->amount;
        $comMonthExp['var'][$index][$month]=($comMonthExp['bud'][$index][$month] - $comMonthExp['acl'][$index][$month]);

        $monthExp['acl'][$month]+=$data->amount;
        $monthExp['var'][$month]=($monthExp['bud'][$month] - $monthExp['acl'][$month]);

        if($data->other_type_id==90 || $data->other_type_id==95){
          //$monthNonCashExp['other_type_id'][$data->other_type_id]=$data->other_type_id;
          $monthNonCashExp['acl'][$month]+=$data->amount;
          $monthNonCashExp['var'][$month]=($monthNonCashExp['bud'][$month]-$monthNonCashExp['acl'][$month]);

          $typeMonthNonCashExp['acl'][$data->other_type_id][$month]+=$data->amount;
          $typeMonthNonCashExp['var'][$data->other_type_id][$month]=($typeMonthNonCashExp['bud'][$data->other_type_id][$month]-$typeMonthNonCashExp['acl'][$data->other_type_id][$month]);
        }
      }
      $comMonthPro['acl'][$index][$month]=($comMonthInc['acl'][$index][$month] - $comMonthExp['acl'][$index][$month]);
      $comMonthPro['var'][$index][$month]=($comMonthPro['bud'][$index][$month] - $comMonthPro['acl'][$index][$month]);

      $monthPro['acl'][$month]=($monthInc['acl'][$month]-$monthExp['acl'][$month]);
      $monthPro['var'][$month]=($monthPro['bud'][$month]-$monthPro['acl'][$month]);
    }

    foreach($budtloandats as $data ){
        $month=date('M-y',strtotime($data->due_date));
        $monthTloan['bud'][$month]+=$data->amount;
        $monthTloan['var'][$month]+=$data->amount;
        $typeMonthTloan['bud'][$data->commercial_head_id][$month]+=$data->amount;
        $typeMonthTloan['var'][$data->commercial_head_id][$month]+=$data->amount;
    }

    foreach($acltloandats as $data ){
        $month=date('M-y',strtotime($data->payment_date));
        $data->amount=$data->amount+$data->interest_amount;
        $monthTloan['bud'][$month]+=$data->amount;
        $monthTloan['var'][$month]+=$data->amount;
        $typeMonthTloan['bud'][$data->commercial_head_id][$month]+=$data->amount;
        $typeMonthTloan['var'][$data->commercial_head_id][$month]+=$data->amount;
    }

    return Template::loadView('Report.CentralBudgetVsAclMatrix',[
      'date_from'=>$date_from,
      'date_to'=>$date_to,
      'companies'=>$companies,
      'monthArr'=>$monthArr,
      'com'=>$com,
      'comMonth'=>$comMonth,

      'comMonthInc'=>$comMonthInc,
      'comMonthExp'=>$comMonthExp,
      'comMonthPro'=>$comMonthPro,

      'monthInc'=>$monthInc,
      'monthExp'=>$monthExp,
      'monthPro'=>$monthPro,


      'monthNonCashExp'=>$monthNonCashExp,
      'typeMonthNonCashExp'=>$typeMonthNonCashExp,

      'monthTloan'=>$monthTloan,
      'typeMonthTloan'=>$typeMonthTloan,

    ]);
  }

  public function reportDetailBudVsAcl() {
    $date_from=request('date_from',0);
    $date_to =request('date_to',0);
    $company_id =request('company_id',0);
    $profitcenter_id =request('profitcenter_id',0);

    $date = Carbon::parse($date_from);
    $now = Carbon::parse($date_to);
    $diff = $date->diffInMonths($now);
    $monthArr=[];
    for($i=0;$i<=$diff;$i++){
    $month=date('M-y',strtotime($date));
    $monthArr[$month]=$month;
    $date->addMonth();
    }

    $companies=$this->company->find($company_id);
    $profitcenters=$this->profitcenter->find($profitcenter_id);
    $otherType=array_prepend(config('bprs.otherType'),'Select',0);
    
    

    $codeInc=[];
    $codeExpFix=[];
    $codeExpVar=[];

    $codeMonthInc=[];
    $codeMonthExpFix=[];
    $codeMonthExpVar=[];

    //$codeMonthPro=[];

    $monthInc=[];
    $monthExpFix=[];
    $monthExpVar=[];
    $monthExp=[];
    $monthPro=[];
    $codeMonthIncComment=[];
    $codeMonthExpFixComment=[];
    $codeMonthExpVarComment=[];

    $monthNonCashExp=[];
    $typeMonthNonCashExp=[];

    $monthTloan=[];
    $typeMonthTloan=[];
   
    $budgets = \DB::select("
      select
      acc_beps.company_id,
      acc_beps.profitcenter_id,
      profitcenters.code as profitcenter_code,
      acc_beps.start_date,
      acc_beps.end_date,
      acc_chart_sub_groups.acc_chart_group_id,
      acc_chart_ctrl_heads.id,
      acc_chart_ctrl_heads.code,
      acc_chart_ctrl_heads.name,
      acc_chart_ctrl_heads.other_type_id,
      acc_chart_ctrl_heads.expense_type_id,
      acc_bep_entries.amount,
      acc_bep_entries.remarks
      from
      acc_beps
      join acc_bep_entries on acc_bep_entries.acc_bep_id=acc_beps.id
      join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
      join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
      join companies on companies.id=acc_beps.company_id
      join profitcenters on profitcenters.id=acc_beps.profitcenter_id
      where acc_beps.start_date >= ?
      and acc_beps.end_date <= ?
      and acc_beps.company_id = ?
      and acc_beps.profitcenter_id = ?
      order by
      acc_chart_ctrl_heads.code
    ",[$date_from,$date_to,$company_id,$profitcenter_id]);
    $budgetdats=collect($budgets);
    foreach($budgetdats as $data ){
      foreach($monthArr as $key=>$value){
         
        if($data->acc_chart_group_id ==16 || $data->acc_chart_group_id ==25){
          $codeInc['bud'][$data->id]=$data->name;
          $codeMonthInc['bud'][$data->id][$key]=0;

          $codeInc['acl'][$data->id]=$data->name;
          $codeMonthInc['acl'][$data->id][$key]=0;

          $codeInc['var'][$data->id]=$data->name;
          $codeMonthInc['var'][$data->id][$key]=0;
          $codeMonthIncComment['bud'][$data->id][$key]='';
        }
        else{
          if($data->expense_type_id==2){
              $codeExpFix['bud'][$data->id]=$data->name;
              $codeMonthExpFix['bud'][$data->id][$key]=0;

              $codeExpFix['acl'][$data->id]=$data->name;
              $codeMonthExpFix['acl'][$data->id][$key]=0;

              $codeExpFix['var'][$data->id]=$data->name;
              $codeMonthExpFix['var'][$data->id][$key]=0;
              $codeMonthExpFixComment['bud'][$data->id][$key]='';
          }
          else{
             $codeExpVar['bud'][$data->id]=$data->name;
             $codeMonthExpVar['bud'][$data->id][$key]=0;

             $codeExpVar['acl'][$data->id]=$data->name;
             $codeMonthExpVar['acl'][$data->id][$key]=0;

             $codeExpVar['var'][$data->id]=$data->name;
             $codeMonthExpVar['var'][$data->id][$key]=0;
             $codeMonthExpVarComment['bud'][$data->id][$key]='';
          }
          //===========Non Cash==============
          if($data->other_type_id==90 || $data->other_type_id==95){
            $monthNonCashExp['other_type_id'][$data->other_type_id]=$otherType[$data->other_type_id];
            $monthNonCashExp['bud'][$key]=0;
            $monthNonCashExp['acl'][$key]=0;
            $monthNonCashExp['var'][$key]=0;
            $typeMonthNonCashExp['bud'][$data->other_type_id][$key]=0;
            $typeMonthNonCashExp['acl'][$data->other_type_id][$key]=0;
            $typeMonthNonCashExp['var'][$data->other_type_id][$key]=0;
          }
        }
        
        $codeMonthPro['bud'][$data->id][$key]=0;

        $monthInc['bud'][$key]=0;
        $monthExpFix['bud'][$key]=0;
        $monthExpVar['bud'][$key]=0;
        $monthExp['bud'][$key]=0;
        $monthPro['bud'][$key]=0;

        $codeMonthPro['acl'][$data->id][$key]=0;

        $monthInc['acl'][$key]=0;
        $monthExpFix['acl'][$key]=0;
        $monthExpVar['acl'][$key]=0;
        $monthExp['acl'][$key]=0;
        $monthPro['acl'][$key]=0;

        $codeMonthPro['var'][$data->id][$key]=0;

        $monthInc['var'][$key]=0;
        $monthExpFix['var'][$key]=0;
        $monthExpVar['var'][$key]=0;
        $monthExp['var'][$key]=0;
        $monthPro['var'][$key]=0;
      }
    }

    $budtloans = \DB::select("
      select
      bank_accounts.company_id,
      companies.code as company_code,
      commercial_heads.id as commercial_head_id,
      commercial_heads.name as commercial_head_name,
      acc_term_loan_installments.due_date,
      acc_term_loan_installments.amount
      from
      acc_term_loans
      join acc_term_loan_installments on acc_term_loans.id=acc_term_loan_installments.acc_term_loan_id
      join bank_accounts on bank_accounts.id=acc_term_loans.bank_account_id
      join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
      join companies on companies.id=bank_accounts.company_id
      where acc_term_loan_installments.due_date >= ?
      and acc_term_loan_installments.due_date <= ?
      and bank_accounts.company_id=?
      and acc_term_loans.term_loan_for=1
      order by
      bank_accounts.company_id
    ",[$date_from,$date_to,$company_id]);

    $budtloandats=collect($budtloans);
    foreach($budtloandats as $data ){
      foreach($monthArr as $key=>$value){
        $monthTloan['loan_type_id'][$data->commercial_head_id]=$data->commercial_head_name;
        $monthTloan['bud'][$key]=0;
        $monthTloan['acl'][$key]=0;
        $monthTloan['var'][$key]=0;
        $typeMonthTloan['bud'][$data->commercial_head_id][$key]=0;
        $typeMonthTloan['acl'][$data->commercial_head_id][$key]=0;
        $typeMonthTloan['var'][$data->commercial_head_id][$key]=0;
      }
    }
    $acls = \DB::select("
      select
      acc_trans_prnts.company_id,
      companies.code as company_code,
      profitcenters.code as profit_code,
      acc_trans_chlds.profitcenter_id,
      acc_trans_prnts.trans_date,
      acc_chart_sub_groups.acc_chart_group_id,
      acc_chart_ctrl_heads.id,
      acc_chart_ctrl_heads.code,
      acc_chart_ctrl_heads.name,
      acc_chart_ctrl_heads.other_type_id,
      acc_chart_ctrl_heads.expense_type_id,
      (CASE WHEN acc_chart_sub_groups.acc_chart_group_id = 1 or acc_chart_sub_groups.acc_chart_group_id = 4 or acc_chart_sub_groups.acc_chart_group_id = 7 or acc_chart_sub_groups.acc_chart_group_id = 16 or acc_chart_sub_groups.acc_chart_group_id = 25
      THEN abs(sum (acc_trans_chlds.amount))
      ELSE sum (acc_trans_chlds.amount)
      END ) AS amount
      from 
      acc_trans_prnts
      join acc_trans_chlds on acc_trans_chlds.acc_trans_prnt_id=acc_trans_prnts.id
      join acc_periods on acc_periods.id=acc_trans_prnts.acc_period_id
      join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
      join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
      join companies on companies.id=acc_trans_prnts.company_id
      join profitcenters on profitcenters.id=acc_trans_chlds.profitcenter_id
      where acc_trans_prnts.trans_date>=?
      and acc_trans_prnts.trans_date<=?
      and acc_chart_ctrl_heads.statement_type_id=2
      and acc_trans_prnts.company_id = ?
      and acc_trans_chlds.profitcenter_id = ?
      and acc_trans_prnts.deleted_at is null
      and acc_trans_chlds.deleted_at is null
      group by
      acc_trans_prnts.company_id,
      companies.code,
      profitcenters.code,
      acc_trans_chlds.profitcenter_id,
      acc_trans_prnts.trans_date,
      acc_chart_sub_groups.acc_chart_group_id,
      acc_chart_ctrl_heads.id,
      acc_chart_ctrl_heads.code,
      acc_chart_ctrl_heads.name,
      acc_chart_ctrl_heads.other_type_id,
      acc_chart_ctrl_heads.expense_type_id
      order by acc_chart_ctrl_heads.code
    ",[$date_from,$date_to,$company_id,$profitcenter_id]);
    $acldats=collect($acls);
    foreach($acldats as $data ){
      foreach($monthArr as $key=>$value){

        if($data->acc_chart_group_id ==16 || $data->acc_chart_group_id ==25){
          $codeInc['bud'][$data->id]=$data->name;
          $codeMonthInc['bud'][$data->id][$key]=0;

          $codeInc['acl'][$data->id]=$data->name;
          $codeMonthInc['acl'][$data->id][$key]=0;

          $codeInc['var'][$data->id]=$data->name;
          $codeMonthInc['var'][$data->id][$key]=0;
          $codeMonthIncComment['bud'][$data->id][$key]='';
        }
        else{
          if($data->expense_type_id==2){
              $codeExpFix['bud'][$data->id]=$data->name;
              $codeMonthExpFix['bud'][$data->id][$key]=0;

              $codeExpFix['acl'][$data->id]=$data->name;
              $codeMonthExpFix['acl'][$data->id][$key]=0;

              $codeExpFix['var'][$data->id]=$data->name;
              $codeMonthExpFix['var'][$data->id][$key]=0;
              $codeMonthExpFixComment['bud'][$data->id][$key]='';
          }
          else{
             $codeExpVar['bud'][$data->id]=$data->name;
             $codeMonthExpVar['bud'][$data->id][$key]=0;

             $codeExpVar['acl'][$data->id]=$data->name;
             $codeMonthExpVar['acl'][$data->id][$key]=0;

             $codeExpVar['var'][$data->id]=$data->name;
             $codeMonthExpVar['var'][$data->id][$key]=0;
             $codeMonthExpVarComment['bud'][$data->id][$key]='';

          }
          //===========Non Cash==============
          if($data->other_type_id==90 || $data->other_type_id==95){
            $monthNonCashExp['other_type_id'][$data->other_type_id]=$otherType[$data->other_type_id];
            $monthNonCashExp['bud'][$key]=0;
            $monthNonCashExp['acl'][$key]=0;
            $monthNonCashExp['var'][$key]=0;
            $typeMonthNonCashExp['bud'][$data->other_type_id][$key]=0;
            $typeMonthNonCashExp['acl'][$data->other_type_id][$key]=0;
            $typeMonthNonCashExp['var'][$data->other_type_id][$key]=0;
          }
        }
        
        $codeMonthPro['bud'][$data->id][$key]=0;

        $monthInc['bud'][$key]=0;
        $monthExpFix['bud'][$key]=0;
        $monthExpVar['bud'][$key]=0;
        $monthExp['bud'][$key]=0;
        $monthPro['bud'][$key]=0;

        $codeMonthPro['acl'][$data->id][$key]=0;
        
        $monthInc['acl'][$key]=0;
        $monthExpFix['acl'][$key]=0;
        $monthExpVar['acl'][$key]=0;
        $monthExp['acl'][$key]=0;
        $monthPro['acl'][$key]=0;

        $codeMonthPro['var'][$data->id][$key]=0;

        $monthInc['var'][$key]=0;
        $monthExpFix['var'][$key]=0;
        $monthExpVar['var'][$key]=0;
        $monthExp['var'][$key]=0;
        $monthPro['var'][$key]=0;
      }
    }

    $acltloans = \DB::select("
      select
      bank_accounts.company_id,
      companies.code as company_code,
      commercial_heads.id as commercial_head_id,
      commercial_heads.name,
      acc_term_loan_payments.payment_date,
      acc_term_loan_payments.amount,
      acc_term_loan_payments.interest_amount
      from
      acc_term_loans
      join acc_term_loan_installments on acc_term_loans.id=acc_term_loan_installments.acc_term_loan_id
      join acc_term_loan_payments on acc_term_loan_payments.acc_term_loan_installment_id=acc_term_loan_installments.id
      join bank_accounts on bank_accounts.id=acc_term_loans.bank_account_id
      join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
      join companies on companies.id=bank_accounts.company_id
      where acc_term_loan_payments.payment_date >= ?
      and acc_term_loan_payments.payment_date <= ?
      and bank_accounts.company_id=?
      and acc_term_loans.term_loan_for=1
      order by
      bank_accounts.company_id
    ",[$date_from,$date_to,$company_id]);

    $acltloandats=collect($acltloans);
    foreach($acltloandats as $data ){
      foreach($monthArr as $key=>$value){
        $monthTloan['loan_type_id'][$data->commercial_head_id]=$data->commercial_head_name;
        $monthTloan['bud'][$key]=0;
        $monthTloan['acl'][$key]=0;
        $monthTloan['var'][$key]=0;
        $typeMonthTloan['bud'][$data->commercial_head_id][$key]=0;
        $typeMonthTloan['acl'][$data->commercial_head_id][$key]=0;
        $typeMonthTloan['var'][$data->commercial_head_id][$key]=0;
      }
    }


    foreach($budgetdats as $data ){
      $month=date('M-y',strtotime($data->start_date));
      if($data->acc_chart_group_id ==16 || $data->acc_chart_group_id ==25){
        $codeMonthInc['bud'][$data->id][$month]+=$data->amount;
        $monthInc['bud'][$month]+=$data->amount;

        $codeMonthInc['var'][$data->id][$month]+=$data->amount;
        $monthInc['var'][$month]+=$data->amount;

        $codeMonthIncComment['bud'][$data->id][$month]=$data->remarks;
      }
      else{
        if($data->expense_type_id==2){
         $codeMonthExpFix['bud'][$data->id][$month]+=$data->amount;
         $monthExpFix['bud'][$month]+=$data->amount;
         $monthExp['bud'][$month]+=$data->amount;

         $codeMonthExpFix['var'][$data->id][$month]+=$data->amount;
         $monthExpFix['var'][$month]+=$data->amount;
         $monthExp['var'][$month]+=$data->amount;
         $codeMonthExpFixComment['bud'][$data->id][$key]=$data->remarks;
        }
        else{
          $codeMonthExpVar['bud'][$data->id][$month]+=$data->amount;
          $monthExpVar['bud'][$month]+=$data->amount;
          $monthExp['bud'][$month]+=$data->amount;

          $codeMonthExpVar['var'][$data->id][$month]+=$data->amount;
          $monthExpVar['var'][$month]+=$data->amount;
          $monthExp['var'][$month]+=$data->amount;
          $codeMonthExpVarComment['bud'][$data->id][$key]=$data->remarks;
        }
        if($data->other_type_id==90 || $data->other_type_id==95){
          //$monthNonCashExp['other_type_id'][$data->other_type_id]=$data->other_type_id;
          $monthNonCashExp['bud'][$month]+=$data->amount;
          $monthNonCashExp['var'][$month]+=$data->amount;
          $typeMonthNonCashExp['bud'][$data->other_type_id][$month]+=$data->amount;
          $typeMonthNonCashExp['var'][$data->other_type_id][$month]+=$data->amount;
        }
      }
      $monthPro['bud'][$month]=($monthInc['bud'][$month]-$monthExp['bud'][$month]);
      $monthPro['var'][$month]=($monthInc['var'][$month]-$monthExp['var'][$month]);
      
    }

    foreach($acldats as $data ){
      $month=date('M-y',strtotime($data->trans_date));
      if($data->acc_chart_group_id ==16 || $data->acc_chart_group_id ==25){
        $codeMonthInc['acl'][$data->id][$month]+=$data->amount;
        $codeMonthInc['var'][$data->id][$month]=$codeMonthInc['acl'][$data->id][$month] - $codeMonthInc['bud'][$data->id][$month];
        $monthInc['acl'][$month]+=$data->amount;
        $monthInc['var'][$month]=$monthInc['acl'][$month]-$monthInc['bud'][$month];
      }
      else{
        if($data->expense_type_id==2){
         $codeMonthExpFix['acl'][$data->id][$month]+=$data->amount;
         $codeMonthExpFix['var'][$data->id][$month]=$codeMonthExpFix['bud'][$data->id][$month] - $codeMonthExpFix['acl'][$data->id][$month];
         $monthExpFix['acl'][$month]+=$data->amount;
         $monthExpFix['var'][$month]=$monthExpFix['bud'][$month]-$monthExpFix['acl'][$month];
         $monthExp['acl'][$month]+=$data->amount;
         $monthExp['var'][$month]=$monthExp['bud'][$month]-$monthExp['acl'][$month];
        }
        else{
          $codeMonthExpVar['acl'][$data->id][$month]+=$data->amount;
          $codeMonthExpVar['var'][$data->id][$month]=$codeMonthExpVar['bud'][$data->id][$month]-$codeMonthExpVar['acl'][$data->id][$month];
          $monthExpVar['acl'][$month]+=$data->amount;
          $monthExpVar['var'][$month]=$monthExpVar['bud'][$month]-$monthExpVar['acl'][$month];
          $monthExp['acl'][$month]+=$data->amount;
          $monthExp['var'][$month]=$monthExp['bud'][$month]-$monthExp['acl'][$month];
        }
        if($data->other_type_id==90 || $data->other_type_id==95){
          //$monthNonCashExp['other_type_id'][$data->other_type_id]=$data->other_type_id;
          $monthNonCashExp['acl'][$month]+=$data->amount;
          $monthNonCashExp['var'][$month]=($monthNonCashExp['bud'][$month]-$monthNonCashExp['acl'][$month]);

          $typeMonthNonCashExp['acl'][$data->other_type_id][$month]+=$data->amount;
          $typeMonthNonCashExp['var'][$data->other_type_id][$month]=($typeMonthNonCashExp['bud'][$data->other_type_id][$month]-$typeMonthNonCashExp['acl'][$data->other_type_id][$month]);
        }
      }
      $monthPro['acl'][$month]=($monthInc['acl'][$month]-$monthExp['acl'][$month]);
      $monthPro['var'][$month]=($monthPro['bud'][$month]-$monthPro['acl'][$month]);
      
    }

    foreach($budtloandats as $data ){
        $month=date('M-y',strtotime($data->due_date));
        $monthTloan['bud'][$month]+=$data->amount;
        $monthTloan['var'][$month]+=$data->amount;
        $typeMonthTloan['bud'][$data->commercial_head_id][$month]+=$data->amount;
        $typeMonthTloan['var'][$data->commercial_head_id][$month]+=$data->amount;
    }

    foreach($acltloandats as $data ){
        $month=date('M-y',strtotime($data->payment_date));
        $data->amount=$data->amount+$data->interest_amount;
        $monthTloan['bud'][$month]+=$data->amount;
        $monthTloan['var'][$month]+=$data->amount;
        $typeMonthTloan['bud'][$data->commercial_head_id][$month]+=$data->amount;
        $typeMonthTloan['var'][$data->commercial_head_id][$month]+=$data->amount;
    }


    return Template::loadView('Report.CentralBudgetMatrixDetailBudVsAcl',[
    'date_from'=>$date_from,
    'date_to'=>$date_to,
    'companies'=>$companies,
    'profitcenters'=>$profitcenters,
    'monthArr'=>$monthArr,

    'codeInc'=>$codeInc,
    'codeExpFix'=>$codeExpFix,
    'codeExpVar'=>$codeExpVar,
    'codeMonthInc'=>$codeMonthInc,
    'codeMonthExpFix'=>$codeMonthExpFix,
    'codeMonthExpVar'=>$codeMonthExpVar,
    //'comMonthPro'=>$comMonthPro,
    
    'monthInc'=>$monthInc,
    'monthExpFix'=>$monthExpFix,
    'monthExpVar'=>$monthExpVar,
    'monthExp'=>$monthExp,
    'monthPro'=>$monthPro,
    'codeMonthIncComment'=>$codeMonthIncComment,
    'codeMonthExpFixComment'=>$codeMonthExpFixComment,
    'codeMonthExpVarComment'=>$codeMonthExpVarComment,

    'monthNonCashExp'=>$monthNonCashExp,
    'typeMonthNonCashExp'=>$typeMonthNonCashExp,

    'monthTloan'=>$monthTloan,
    'typeMonthTloan'=>$typeMonthTloan,

    ]);
  }




}
