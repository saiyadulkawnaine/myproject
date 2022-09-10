<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccTermLoanAdjustmentRepository;
use App\Repositories\Contracts\Account\AccTermLoanRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Commercial\Export\ExpPreCreditRepository;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubTransectionRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLiabilityAdjustChldRepository;
use App\Library\Template;
use Illuminate\Support\Carbon;
use App\Http\Requests\Account\AccTermLoanAdjustmentRequest;

class AccTermLoanAdjustmentController extends Controller {

    private $acctermloanadjustment;
    private $commercialhead;
    private $acctermloan;
    private $company;
    private $bankbranch;
    private $expprecredit;
    private $expdocsubmission;
    private $expdocsubtransection;
    private $impliabladjustchld;


    public function __construct(
        AccTermLoanAdjustmentRepository $acctermloanadjustment,
        CommercialHeadRepository $commercialhead,
        AccTermLoanRepository $acctermloan,
        CompanyRepository $company,
        BankBranchRepository $bankbranch,
        ExpPreCreditRepository $expprecredit,
        ExpDocSubmissionRepository $expdocsubmission,
        ExpDocSubTransectionRepository $expdocsubtransection,
        ImpLiabilityAdjustChldRepository $impliabladjustchld
    ) {

        $this->acctermloanadjustment = $acctermloanadjustment;
        $this->commercialhead = $commercialhead;
        $this->acctermloan = $acctermloan;
        $this->company = $company;
        $this->bankbranch = $bankbranch;
        $this->expprecredit = $expprecredit;
        $this->expdocsubmission = $expdocsubmission;
        $this->expdocsubtransection = $expdocsubtransection;
        $this->impliabladjustchld = $impliabladjustchld;

        $this->middleware('auth');
        // $this->middleware('permission:view.acctermloanadjustments',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.acctermloanadjustments', ['only' => ['store']]);
        // $this->middleware('permission:edit.acctermloanadjustments',   ['only' => ['update']]);
        // $this->middleware('permission:delete.acctermloanadjustments', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
       

        $rows=collect(
            \DB::select("
            select
                acc_term_loan_adjustments.id,
                acc_term_loan_adjustments.payment_date,
                acc_term_loan_adjustments.commercial_head_id,
                acc_term_loan_adjustments.payment_source_id,
                acc_term_loan_adjustments.amount,
                acc_term_loan_adjustments.interest_rate,
                acc_term_loan_adjustments.other_charge_amount,
                acc_term_loan_adjustments.delay_charge_amount,
                acc_term_loan_adjustments.remarks,
                case when
                acc_term_loan_adjustments.commercial_head_id in (10,15)
                then exp_doc_sub_transections.ac_loan_no
                when acc_term_loan_adjustments.commercial_head_id = 14
                then acc_term_loans.loan_ref_no
                when acc_term_loan_adjustments.commercial_head_id in (11,12)
                then exp_pre_credits.loan_no
                else null
                end as loan_ref_no
            from
            acc_term_loan_adjustments
            left join exp_doc_sub_transections on exp_doc_sub_transections.id=acc_term_loan_adjustments.other_loan_ref_id
            left join acc_term_loans on acc_term_loans.id=acc_term_loan_adjustments.other_loan_ref_id
            left join exp_pre_credits on exp_pre_credits.id=acc_term_loan_adjustments.other_loan_ref_id
        "))
        ->map(function($rows) use($commercialhead){
            $rows->payment_date=date('Y-m-d',strtotime($rows->payment_date));
            $rows->commercial_head=$commercialhead[$rows->commercial_head_id];
            $rows->payment_source=$commercialhead[$rows->payment_source_id];
            return $rows;
        });
        
        echo json_encode($rows);

    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
       $commercialhead=array_prepend(array_pluck($this->commercialhead->whereIn('id',[10,11,12,14,15,141])->get(),'name','id'),'-Select-','');
       $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
           $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');

		return Template::loadView('Account.AccTermLoanAdjustment', ['commercialhead'=>$commercialhead,'company'=>$company,'bankbranch'=>$bankbranch]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccTermLoanAdjustmentRequest $request) {
        $acctermloanadjustment=$this->acctermloanadjustment->create([
            'payment_date'=>$request->payment_date,
            'commercial_head_id'=>$request->commercial_head_id,
            'other_loan_ref_id'=>$request->other_loan_ref_id,
            'amount'=>$request->amount,
            'interest_rate'=>$request->interest_rate,
            'other_charge_amount'=>$request->other_charge_amount,
            'delay_charge_amount'=>$request->delay_charge_amount,
            'payment_source_id'=>$request->payment_source_id,
            'remarks'=>$request->remarks
        ]);

        if($acctermloanadjustment){
            return response()->json(array('success' => true,'id' =>  $acctermloanadjustment->id,'message' => 'Save Successfully'),200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $adjusiment=$this->acctermloanadjustment->find($id); 
        $commercial_head_id=$adjusiment->commercial_head_id;
        //Negotiation Liability or Bai_As_Sarf(FDB)
        if ($commercial_head_id==10 || $commercial_head_id==15) {
            $acctermloanadjustment=$this->acctermloanadjustment
            ->join('exp_doc_sub_transections',function($join){
                $join->on('exp_doc_sub_transections.id','=','acc_term_loan_adjustments.other_loan_ref_id');
            })
            ->where([['acc_term_loan_adjustments.id','=',$id]])
            ->get([
                'acc_term_loan_adjustments.*',
                'exp_doc_sub_transections.ac_loan_no as loan_ref_no'
            ])
            ->first();
            $row ['fromData'] = $acctermloanadjustment;
            $dropdown['att'] = '';
            $row ['dropDown'] = $dropdown;
            echo json_encode($row);
        }
        //Mudaraba TR
        if ($commercial_head_id==14) {
            $acctermloanadjustment=$this->acctermloanadjustment
            ->join('acc_term_loans',function($join){
                $join->on('acc_term_loans.id','=','acc_term_loan_adjustments.other_loan_ref_id');
            })
            ->where([['acc_term_loan_adjustments.id','=',$id]])
            ->get([
                'acc_term_loan_adjustments.*',
                'acc_term_loans.loan_ref_no',
                'acc_term_loans.loan_date',
                'acc_term_loans.grace_period',
            ])
            ->map(function($acctermloanadjustment){
                $acctermloanadjustment->maturity_date=Carbon::parse($acctermloanadjustment->loan_date)->addDays($acctermloanadjustment->grace_period)->addMonth(-1);
                $acctermloanadjustment->maturity_date=date('d-M-Y',strtotime($acctermloanadjustment->maturity_date));
                return $acctermloanadjustment;
            })
            ->first();
            $row ['fromData'] = $acctermloanadjustment;
            $dropdown['att'] = '';
            $row ['dropDown'] = $dropdown;
            echo json_encode($row);
        }
        //Bai Salam or Packing Credit
        if ($commercial_head_id==11 || $commercial_head_id==12) {
            $acctermloanadjustment=$this->acctermloanadjustment
            ->join('exp_pre_credits',function($join){
                $join->on('exp_pre_credits.id','=','acc_term_loan_adjustments.other_loan_ref_id');
            })
            ->where([['acc_term_loan_adjustments.id','=',$id]])
            ->get([
                'acc_term_loan_adjustments.*',
                'exp_pre_credits.loan_no as loan_ref_no',
                'exp_pre_credits.maturity_date'
            ])
            ->first();
            $row ['fromData'] = $acctermloanadjustment;
            $dropdown['att'] = '';
            $row ['dropDown'] = $dropdown;
            echo json_encode($row);
        }
        //EDF
        if ($commercial_head_id==141) {
            $acctermloanadjustment=$this->acctermloanadjustment
            ->join('imp_liability_adjust_chlds',function($join){
                $join->on('imp_liability_adjust_chlds.id','=','acc_term_loan_adjustments.other_loan_ref_id');
            })
            ->where([['acc_term_loan_adjustments.id','=',$id]])
            ->get([
                'acc_term_loan_adjustments.*',
                'imp_liability_adjust_chlds.loan_ref as loan_ref_no'
            ])
            ->first();
            $row ['fromData'] = $acctermloanadjustment;
            $dropdown['att'] = '';
            $row ['dropDown'] = $dropdown;
            echo json_encode($row);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccTermLoanAdjustmentRequest $request, $id) {
         $acctermloanadjustment=$this->acctermloanadjustment->update($id,[
         'payment_date'=>$request->payment_date,
         'amount'=>$request->amount,
         'other_loan_ref_id'=>$request->other_loan_ref_id,
         'interest_rate'=>$request->interest_rate,
         'other_charge_amount'=>$request->other_charge_amount,
         'delay_charge_amount'=>$request->delay_charge_amount,
         'payment_source_id'=>$request->payment_source_id,
         'remarks'=>$request->remarks,
        ]);
        if($acctermloanadjustment){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->acctermloanadjustment->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getTermLoan()
    {
        $commercial_head_id=request('commercial_head_id', 0);
        //Negotiation Liability or Bai_As_Sarf(FDB)
        if ($commercial_head_id==10 || $commercial_head_id==15) {
            $rows=$this->expdocsubtransection
            ->join('exp_doc_submissions',function($join){
                $join->on('exp_doc_submissions.id','=','exp_doc_sub_transections.exp_doc_submission_id');
            })
            ->join('exp_lc_scs',function($join){
                $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
            })
            ->join('bank_branches',function($join){
                $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
            })
            ->join('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->whereIn('exp_doc_sub_transections.commercialhead_id',[10,15])
            ->when(request('company_id'), function ($q) {
                return $q->where('exp_lc_scs.company_id', '=', request('company_id', 0));
            })
            ->when(request('bank_branch_id'), function ($q) {
                return $q->where('exp_lc_scs.exporter_bank_branch_id', '=', request('bank_branch_id', 0));
            })
            ->orderBy('exp_doc_sub_transections.id','desc')
            ->get([
                'exp_doc_sub_transections.*',
                'exp_doc_submissions.negotiation_date',
                'banks.name as bank_name',
                'bank_branches.branch_name',
                ])
            ->map(function($rows){
                $rows->loan_date=date('d-M-Y',strtotime($rows->negotiation_date));
                $rows->loan_ref_no=$rows->ac_loan_no;
                $rows->amount=$rows->dom_value;
                return $rows;
            });
 
            echo json_encode($rows);
        }
        //Murabaha TR
        if ($commercial_head_id==14) {
            $rows=$this->acctermloan
            ->join('bank_branches',function($join){
                $join->on('bank_branches.id','=','acc_term_loans.bank_branch_id');
            })
            ->join('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->where([['acc_term_loans.commercial_head_id','=',14]])
            ->when(request('company_id'), function ($q) {
                return $q->where('acc_term_loans.company_id', '=', request('company_id', 0));
            })
            ->when(request('bank_branch_id'), function ($q) {
                return $q->where('acc_term_loans.bank_branch_id', '=', request('bank_branch_id', 0));
            })
            ->orderBy('acc_term_loans.id','desc')
            ->get([
                'acc_term_loans.*',
                'banks.name as bank_name',
                'bank_branches.branch_name',
                ])
            ->map(function($rows){
                $rows->loan_date=date('Y-m-d',strtotime($rows->loan_date));
                $rows->maturity_date=Carbon::parse($rows->loan_date)->addDays($rows->grace_period);
                return $rows;
            });
 
            echo json_encode($rows);
        }
        //Bai Salam or Packing Credit
        if ($commercial_head_id==11 || $commercial_head_id==12) {
            $rows=$this->expprecredit
            ->selectRaw('
                exp_pre_credits.id,
                exp_pre_credits.loan_no as loan_ref_no,
                exp_pre_credits.cr_date as loan_date,
                max(banks.name) as bank_name,
                max(bank_branches.branch_name) as branch_name,
                sum(exp_pre_credit_lc_scs.credit_taken) as amount
            ')
            ->join('exp_pre_credit_lc_scs',function($join){
                $join->on('exp_pre_credits.id','=','exp_pre_credit_lc_scs.exp_pre_credit_id');
            })
            ->join('exp_lc_scs',function($join){
                $join->on('exp_lc_scs.id','=','exp_pre_credit_lc_scs.exp_lc_sc_id');
            })
            ->join('bank_branches',function($join){
                $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
            })
            ->join('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->where([['exp_pre_credits.loan_type_id','=',1]])
            ->when(request('company_id'), function ($q) {
                return $q->where('exp_pre_credits.company_id', '=', request('company_id', 0));
            })
            ->when(request('bank_branch_id'), function ($q) {
                return $q->where('exp_lc_scs.exporter_bank_branch_id', '=', request('bank_branch_id', 0));
            })
            ->orderBy('exp_pre_credits.id','desc')
            ->groupBy([
                'exp_pre_credits.id',
                'exp_pre_credits.loan_no',
                'exp_pre_credits.cr_date',
                'exp_pre_credits.maturity_date',
                'banks.name',
                'bank_branches.branch_name',
            ])
            ->get()
            ->map(function($rows){
                $rows->loan_date=$rows->loan_date?date('d-M-Y',strtotime($rows->loan_date)):'--';
                $rows->maturity_date=$rows->maturity_date?date('d-M-Y',strtotime($rows->maturity_date)):'--';
                return $rows;
            });
 
            echo json_encode($rows);
        }
        //EDF
        if ($commercial_head_id==141) {
            $rows=$this->impliabladjustchld
            ->join('imp_liability_adjusts',function($join){
                $join->on('imp_liability_adjusts.id','=','imp_liability_adjust_chlds.imp_liability_adjust_id');
            })
            ->join('imp_doc_accepts',function($join){
                $join->on('imp_doc_accepts.id','=','imp_liability_adjusts.imp_doc_accept_id');
            })
            ->join('imp_lcs', function($join)  {
                $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
            })
            ->join('bank_branches',function($join){
                $join->on('bank_branches.id','=','imp_lcs.issuing_bank_branch_id');
            })
            ->join('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->where([['imp_liability_adjust_chlds.adj_source','=',141]])
            ->when(request('company_id'), function ($q) {
                return $q->where('imp_lcs.company_id', '=', request('company_id', 0));
            })
            ->when(request('bank_branch_id'), function ($q) {
                return $q->where('imp_lcs.issuing_bank_branch_id', '=', request('bank_branch_id', 0));
            })
            ->orderBy('imp_liability_adjust_chlds.id','desc')
            ->get([
                'imp_liability_adjust_chlds.*',
                'imp_liability_adjusts.payment_date',
                'banks.name as bank_name',
                'bank_branches.branch_name',
                ])
            ->map(function($rows){
                $rows->loan_date=date('Y-m-d',strtotime($rows->payment_date));
                $rows->loan_ref_no=$rows->loan_ref;
                $rows->amount=$rows->dom_currency;
                return $rows;
            });
 
            echo json_encode($rows);
        }
    }

}