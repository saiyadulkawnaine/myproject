<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubTransectionRepository;
use App\Http\Requests\Commercial\Export\ExpDocSubTransectionRequest;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Account\AccTermLoanRepository;
use App\Repositories\Contracts\Account\AccTermLoanInstallmentRepository;
use App\Library\Template;

class ExpDocSubTransectionController extends Controller {

    private $expdocsubmission;
    private $expdocsubtransection;
    private $company;
    private $buyer;
    private $currency;
    private $explcsc;
    private $commercialhead;
    private $bankaccount;
    private $acctermloan;
    private $acctermloaninstallment;

    public function __construct(
        ExpDocSubmissionRepository $expdocsubmission, 
        ExpDocSubTransectionRepository $expdocsubtransection, 
        ExpLcScRepository $explcsc,
        CompanyRepository $company, 
        BuyerRepository $buyer, 
        CurrencyRepository $currency, 
        CommercialHeadRepository $commercialhead,
        BankAccountRepository $bankaccount, 
        AccTermLoanRepository $acctermloan,
        AccTermLoanInstallmentRepository $acctermloaninstallment
    ) {

        $this->explcsc = $explcsc;
        $this->expdocsubtransection = $expdocsubtransection;
        $this->expdocsubmission = $expdocsubmission;
        $this->commercialhead = $commercialhead;
        $this->currency = $currency;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->bankaccount = $bankaccount;
        $this->acctermloan = $acctermloan;
        $this->acctermloaninstallment = $acctermloaninstallment;

        $this->middleware('auth');
        $this->middleware('permission:view.expdocsubtransections',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.expdocsubtransections', ['only' => ['store']]);
        $this->middleware('permission:edit.expdocsubtransections',   ['only' => ['update']]);
        $this->middleware('permission:delete.expdocsubtransections', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() { 
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        
        $expdocsubtransections=array();
        $rows=$this->expdocsubtransection
        ->leftjoin('bank_accounts',function($join){
            $join->on('exp_doc_sub_transections.bank_account_id','=','bank_accounts.id');
        })
        ->leftjoin('commercial_heads',function($join){
            $join->on('commercial_heads.id','=','bank_accounts.account_type_id');
        })
        ->where([['exp_doc_submission_id','=',request('exp_doc_submission_id',0)]])
        ->get([
            'exp_doc_sub_transections.*',
            'commercial_heads.name as commercial_head_name'
        ]);
        foreach($rows as $row){
            $expdocsubtransection['id']=$row->id;
            $expdocsubtransection['exp_doc_submission_id']=$row->exp_doc_submission_id;
            $expdocsubtransection['commercialhead_id']=$commercialhead[$row->commercialhead_id];
            $expdocsubtransection['ac_loan_no']=$row->ac_loan_no;
            $expdocsubtransection['dom_value']=number_format($row->dom_value,2,'.',',');
            $expdocsubtransection['exch_rate']= $row->exch_rate;
            $expdocsubtransection['doc_value']=number_format($row->doc_value,2,'.',',');
            $expdocsubtransection['commercial_head_name']=$row->commercial_head_name;
            array_push($expdocsubtransections,$expdocsubtransection);
        }
        echo json_encode($expdocsubtransections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpDocSubTransectionRequest $request) {
        //return response()->json(array('success' => false,'message' => 'Save is locked temporarily'),200);
        $inputs=$request->except(['id','aa','commercial_head_name']);
        $expdocsubmission=$this->expdocsubmission->find($request->exp_doc_submission_id);
        $explcsc=$this->explcsc->find($expdocsubmission->exp_lc_sc_id);
        $maturity_date=date('Y-m-d', strtotime($expdocsubmission->negotiation_date. ' + '.$explcsc->tenor.' days'));
        \DB::beginTransaction();
        try
        {
        $acctermloan=$this->acctermloan->create([
        'loan_ref_no'=>$inputs['ac_loan_no'],
        'loan_date'=>$expdocsubmission->negotiation_date,
        'amount'=>$inputs['dom_value'], 
        'grace_period'=>$explcsc->tenor,
        'rate'=>0,
        'installment_amount'=>$inputs['dom_value'],
        'no_of_installment'=>1,
        'term_loan_for'=>2,
        'bank_account_id'=>$inputs['bank_account_id'],
        'remarks'=>NULL,
        ]);
        $this->acctermloaninstallment->create([
        'acc_term_loan_id'=>$acctermloan->id,
        'amount'=>$inputs['dom_value'],
        'sort_id'=>1,
        'due_date'=>$maturity_date,
        ]);
        $inputs['acc_term_loan_id']=$acctermloan->id;

        $expdocsubtransection=$this->expdocsubtransection->create($inputs);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        if($expdocsubtransection){
            return response()->json(array('success' => true,'id' =>  $expdocsubtransection->id,'message' => 'Save Successfully'),200);
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
       $expdocsubtransection = $this->expdocsubtransection
        ->leftjoin('bank_accounts',function($join){
            $join->on('exp_doc_sub_transections.bank_account_id','=','bank_accounts.id');
        })
        ->leftjoin('commercial_heads',function($join){
            $join->on('commercial_heads.id','=','bank_accounts.account_type_id');
        })
        ->where([['exp_doc_sub_transections.id','=',$id]])
        ->get([
            'exp_doc_sub_transections.*',
            'commercial_heads.name as commercial_head_name'
        ])
        ->first();
        $row ['fromData'] = $expdocsubtransection;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        echo json_encode($row); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpDocSubTransectionRequest $request, $id) {
        $inputs=$request->except(['id','aa','commercial_head_name']);
        $expdocsubmission=$this->expdocsubmission->find($request->exp_doc_submission_id);
        $explcsc=$this->explcsc->find($expdocsubmission->exp_lc_sc_id);
        $maturity_date=date('Y-m-d', strtotime($expdocsubmission->negotiation_date. ' + '.$explcsc->tenor.' days'));
        $transection=$this->expdocsubtransection->find($id);
        \DB::beginTransaction();
        try
        {
        $acctermloan=$this->acctermloan->update($transection->acc_term_loan_id,[
        'loan_ref_no'=>$inputs['ac_loan_no'],
        'loan_date'=>$expdocsubmission->negotiation_date,
        'amount'=>$inputs['dom_value'], 
        'grace_period'=>$explcsc->tenor,
        'rate'=>0,
        'installment_amount'=>$inputs['dom_value'],
        'no_of_installment'=>1,
        'term_loan_for'=>2,
        'bank_account_id'=>$inputs['bank_account_id'],
        'remarks'=>NULL,
        ]);
        $this->acctermloaninstallment
        ->where([['acc_term_loan_id','=',$transection->acc_term_loan_id]])
        ->update([
        'acc_term_loan_id'=>$transection->acc_term_loan_id,
        'amount'=>$inputs['dom_value'],
        'sort_id'=>1,
        'due_date'=>$maturity_date,
        ]);
        //$inputs['acc_term_loan_id']=$transection->acc_term_loan_id;

        $expdocsubtransection=$this->expdocsubtransection->update($id,$inputs);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        if($expdocsubtransection){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->expdocsubtransection->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getBankAccount()
    {
        $expdocsubmission=$this->expdocsubmission
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
        })
        ->where([['exp_doc_submissions.id','=',request('exp_doc_submission_id',0)]])
        ->get([
            'exp_lc_scs.exporter_bank_branch_id',
            'exp_lc_scs.beneficiary_id'
        ])
        ->first();

        $rows=$this->bankaccount
        ->join('bank_branches', function($join)  {
            $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id');
        })
        ->join('banks',function($join){
            $join->on('bank_branches.bank_id','=','banks.id');
        })
        ->leftjoin('commercial_heads',function($join){
            $join->on('commercial_heads.id','=','bank_accounts.account_type_id');
        })
        ->where([['bank_branches.id','=',$expdocsubmission->exporter_bank_branch_id]])
        ->where([['bank_accounts.company_id','=',$expdocsubmission->beneficiary_id]])
        ->where([['commercial_heads.commercialhead_type_id','=',4]])
        ->when(request('branch_name'), function ($q) {
            return $q->where('bank_branches.name', 'LIKE', "%".request('branch_name', 0)."%");
        })
        ->when(request('account_no'), function ($q) {
            return $q->where('bank_accounts.account_no', 'LIKE', "%".request('account_no', 0)."%");
        })
        ->orderBy('bank_accounts.id','desc')
        ->get([
            'bank_accounts.*',
            'banks.name',
            'bank_branches.branch_name',
            'commercial_heads.name as commercial_head_name'
        ]);
        echo json_encode($rows);
    }


}
