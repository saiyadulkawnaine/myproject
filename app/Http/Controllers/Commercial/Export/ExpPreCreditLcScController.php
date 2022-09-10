<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpPreCreditLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpPreCreditRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Account\AccTermLoanRepository;
use App\Repositories\Contracts\Account\AccTermLoanInstallmentRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpPreCreditLcScRequest;

class ExpPreCreditLcScController extends Controller {

    private $explcsc;
    private $expprecreditlcsc;
    private $expprecredit;
    private $bankaccount;
    private $acctermloan;
    private $acctermloaninstallment;

    public function __construct(
        ExpLcScRepository $explcsc,
        ExpPreCreditLcScRepository $expprecreditlcsc,
        ExpPreCreditRepository $expprecredit,
        BankAccountRepository $bankaccount,
        AccTermLoanRepository $acctermloan,
        AccTermLoanInstallmentRepository $acctermloaninstallment
    ) {
        $this->explcsc = $explcsc;
        $this->expprecredit = $expprecredit;
        $this->expprecreditlcsc = $expprecreditlcsc;
        $this->bankaccount = $bankaccount;
        $this->acctermloan = $acctermloan;
        $this->acctermloaninstallment = $acctermloaninstallment;

        $this->middleware('auth');
        $this->middleware('permission:view.expprecreditlcscs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.expprecreditlcscs', ['only' => ['store']]);
        $this->middleware('permission:edit.expprecreditlcscs',   ['only' => ['update']]);
        $this->middleware('permission:delete.expprecreditlcscs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         
        $expprecreditlcscs=array();
        $rows=$this->expprecreditlcsc
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_pre_credit_lc_scs.exp_lc_sc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->leftJoin('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->where([['exp_pre_credit_id','=',request('exp_pre_credit_id',0)]])
        ->get([        
            'exp_lc_scs.id',
            'exp_lc_scs.lc_sc_no',
            'buyers.name as buyer_name',
            'banks.name as bank_name',
            'exp_pre_credit_lc_scs.*'
            ]);
        foreach($rows as $row){
            $expprecreditlcsc['id']=$row->id;
            $expprecreditlcsc['exp_lc_sc_id']=$row->exp_lc_sc_id;
            $expprecreditlcsc['lc_sc_no']=$row->lc_sc_no;
            $expprecreditlcsc['credit_taken']=$row->credit_taken;
            $expprecreditlcsc['exch_rate']=$row->exch_rate;
            $expprecreditlcsc['buyer_name']=$row->buyer_name;
            $expprecreditlcsc['bank_name']=$row->bank_name;
            $expprecreditlcsc['equivalent_fc']=$row->equivalent_fc;
            array_push($expprecreditlcscs,$expprecreditlcsc);
        }
        echo json_encode($expprecreditlcscs);
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
    public function store(ExpPreCreditLcScRequest $request) {
        $expprecredit=$this->expprecredit->find($request->exp_pre_credit_id);
        \DB::beginTransaction();
        try
        {
        $expprecreditlcsc=$this->expprecreditlcsc->create([
            'exp_pre_credit_id'=>$request->exp_pre_credit_id,
            'exp_lc_sc_id'=>$request->exp_lc_sc_id,
            'credit_taken'=>$request->credit_taken,
            'exch_rate'=>$request->exch_rate,
            'equivalent_fc'=>$request->equivalent_fc
        ]);
        $total_credit_taken=$this->expprecreditlcsc->where([['exp_pre_credit_id','=',$request->exp_pre_credit_id]])->sum('credit_taken');
        $this->acctermloan->update($expprecredit->acc_term_loan_id,[
            'amount'=>$total_credit_taken,
            'installment_amount'=>$total_credit_taken,
        ]);
        \DB::update("update acc_term_loan_installments set amount=".$total_credit_taken." where acc_term_loan_installments.acc_term_loan_id=".$expprecredit->acc_term_loan_id."");
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        if($expprecreditlcsc){
            return response()->json(array('success' => true,'id' =>  $expprecreditlcsc->id,'message' => 'Save Successfully'),200);
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
       $expprecreditlcsc = $this->expprecreditlcsc
       ->selectRaw('exp_lc_scs.id,
       exp_lc_scs.lc_sc_no,
       exp_pre_credit_lc_scs.id,
       exp_pre_credit_lc_scs.exp_pre_credit_id,
       exp_pre_credit_lc_scs.exp_lc_sc_id,
       exp_pre_credit_lc_scs.exch_rate,
       exp_pre_credit_lc_scs.credit_taken,
       exp_pre_credit_lc_scs.equivalent_fc
       ')
       ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_pre_credit_lc_scs.exp_lc_sc_id');
        })
        ->join('exp_pre_credits',function($join){
            $join->on('exp_pre_credits.id','=','exp_pre_credit_lc_scs.exp_pre_credit_id');
        })
        ->where([['exp_pre_credit_lc_scs.id','=',$id]])
        ->get([
            'exp_pre_credit_lc_scs.*',
            'exp_lc_scs.id',
            'exp_lc_scs.lc_sc_no as exp_lc_sc_id'
        ])
       ->first();
       $row ['fromData'] = $expprecreditlcsc;
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
    public function update(ExpPreCreditLcScRequest $request, $id) {
        $expprecredit=$this->expprecredit->find($request->exp_pre_credit_id);
        \DB::beginTransaction();
        try
        {
        $expprecreditlcsc=$this->expprecreditlcsc->update($id,[
            'exp_pre_credit_id'=>$request->exp_pre_credit_id,
            'exp_lc_sc_id'=>$request->exp_lc_sc_id,
            'credit_taken'=>$request->credit_taken,
            'exch_rate'=>$request->exch_rate,
            'equivalent_fc'=>$request->equivalent_fc
        ]);

        $total_credit_taken=$this->expprecreditlcsc->where([['exp_pre_credit_id','=',$request->exp_pre_credit_id]])->sum('credit_taken');
        $this->acctermloan->update($expprecredit->acc_term_loan_id,[
            'amount'=>$total_credit_taken,
            'installment_amount'=>$total_credit_taken,
        ]);
        \DB::update("update acc_term_loan_installments set amount=".$total_credit_taken." where acc_term_loan_installments.acc_term_loan_id=".$expprecredit->acc_term_loan_id."");
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        if($expprecreditlcsc){
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
        if($this->expprecreditlcsc->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getExpLcSc(){
        $bankaccount = $this->bankaccount->find(request('bank_account_id', 0));
        $contractNature = array_prepend(config('bprs.contractNature'), '-Select-','');         
        $rows=$this->explcsc
        ->join('companies',function($join){
            $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->join('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
        })
        // ->join('bank_accounts', function($join)  {
        //     $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id');
        // })
        ->join('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->where([['exp_lc_scs.exporter_bank_branch_id','=',$bankaccount->bank_branch_id]])
        ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%".request('lc_sc_no', 0)."%");
        }) 
         ->when(request('beneficiary_id'), function ($q) {
            return $q->where('exp_lc_scs.beneficiary_id', '=', request('beneficiary_id', 0));
        })
        ->when(request('lc_sc_date'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_date', '=',request('lc_sc_date', 0));
        })
        ->orderBy('exp_lc_scs.id','desc')
        ->get([
            'exp_lc_scs.*',
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id',
            'banks.name as bank_name',
        ])
        ->map(function ($rows) use($contractNature){
            $rows->lc_sc_nature=$contractNature[$rows->lc_sc_nature_id];
            $rows->last_delivery_date=date('Y-m-d',strtotime($rows->last_delivery_date));
            $rows->lc_sc_date=date('Y-m-d',strtotime($rows->lc_sc_date));
            return $rows;
        });
        echo json_encode($rows);
    }
}
