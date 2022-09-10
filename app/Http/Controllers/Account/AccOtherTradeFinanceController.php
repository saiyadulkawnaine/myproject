<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccTermLoanRepository;
use App\Repositories\Contracts\Account\AccTermLoanInstallmentRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Library\Template;
use Illuminate\Support\Carbon;
use App\Http\Requests\Account\AccOtherTradeFinanceRequest;

class AccOtherTradeFinanceController extends Controller {

    private $acctermloan;
    private $acctermloaninstallment;
    private $company;
    private $commercialhead;
    private $bankbranch;
    private $bankaccount;

    public function __construct(
        AccTermLoanRepository $acctermloan,
        AccTermLoanInstallmentRepository $acctermloaninstallment,
        BankBranchRepository $bankbranch,
        CompanyRepository $company,
        CommercialHeadRepository $commercialhead,
        BankAccountRepository $bankaccount) {

        $this->acctermloan = $acctermloan;
        $this->acctermloaninstallment = $acctermloaninstallment;
        $this->company = $company;
        $this->commercialhead = $commercialhead;
        $this->bankbranch = $bankbranch;
        $this->bankaccount = $bankaccount;

        $this->middleware('auth');
        // $this->middleware('permission:view.acctermloans',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.acctermloans', ['only' => ['store']]);
        // $this->middleware('permission:edit.acctermloans',   ['only' => ['update']]);
        // $this->middleware('permission:delete.acctermloans', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
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



       $acctermloans = array();
       $rows=$this->acctermloan
       ->leftJoin('bank_accounts', function($join)  {
            $join->on('bank_accounts.id', '=', 'acc_term_loans.bank_account_id');
        })
        ->leftJoin('bank_branches', function($join)  {
            $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id');
        })
        ->leftJoin('banks',function($join){
            $join->on('bank_branches.bank_id','=','banks.id');
        })
        ->where([['acc_term_loans.term_loan_for','=',2]])
        ->orderBy('acc_term_loans.id','desc')
        ->get([
            'acc_term_loans.*',
            'bank_accounts.account_no',
            'bank_accounts.company_id',
            'bank_accounts.account_type_id',
            'bank_branches.id as bank_branch_id',
        ]);
       foreach($rows as $row){
           $acctermloan['id']=$row->id;
           $acctermloan['loan_ref_no']=$row->loan_ref_no;
           $acctermloan['remarks']=$row->remarks;
           $acctermloan['grace_period']=$row->grace_period;
           $acctermloan['amount']=number_format($row->amount,2);
           $acctermloan['installment_amount']=number_format($row->installment_amount,2);
           $acctermloan['no_of_installment']=$row->no_of_installment;
           $acctermloan['company_id']=$company[$row->company_id];
           $acctermloan['account_type_id']=$commercialhead[$row->account_type_id];
           $acctermloan['bank_branch_id']=$bankbranch[$row->bank_branch_id];
           $acctermloan['loan_date']=date('Y-m-d',strtotime($row->loan_date));

           array_push($acctermloans,$acctermloan);
       }
       echo json_encode($acctermloans);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
       $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
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

        return Template::loadView('Account.AccOtherTradeFinance', ['company'=>$company,'bankbranch'=>$bankbranch,'commercialhead'=>$commercialhead]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccOtherTradeFinanceRequest $request) {

        \DB::beginTransaction();
        //$request->request->add(['term_loan_for' =>2]);
       // $request->request->add(['installment_amount' =>$request->amount]);
        $acctermloan=$this->acctermloan->create([
            'bank_account_id'=>$request->bank_account_id,
            'loan_ref_no'=>$request->loan_ref_no,
            'loan_date'=>$request->loan_date,
            'amount'=>$request->amount,
            'grace_period'=>$request->grace_period,
            'rate'=>$request->rate,
            'installment_amount'=>$request->amount,
            'remarks'=>$request->remarks,
            'no_of_installment'=>1,
            'term_loan_for'=>2,
        ]);


        try
        {
            $this->acctermloaninstallment->updateOrCreate([
                'acc_term_loan_id'=>$acctermloan->id,
                'amount'=>$acctermloan->amount,
                'sort_id'=>1,
                'due_date'=>$request->maturity_date
            ]);

        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        if($acctermloan){
            return response()->json(array('success' => true,'id' =>  $acctermloan->id,'message' => 'Save Successfully'),200);
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
        $acctermloan=$this->acctermloan
        ->leftJoin('bank_accounts', function($join)  {
            $join->on('bank_accounts.id', '=', 'acc_term_loans.bank_account_id');
        })
        ->leftJoin('bank_branches', function($join)  {
            $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id');
        })
        ->leftJoin('banks',function($join){
            $join->on('bank_branches.bank_id','=','banks.id');
        })
        ->where([['acc_term_loans.id','=',$id]])
        ->get([
            'acc_term_loans.*',
            'bank_accounts.account_no',
            'bank_accounts.company_id',
            'bank_accounts.account_type_id',
            'bank_branches.id as bank_branch_id',
        ])
        ->first();
        $row ['fromData'] = $acctermloan;
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
    public function update(AccOtherTradeFinanceRequest $request, $id) {
        $acctermloan=$this->acctermloan->update($id,$request->except(['id','no_of_installment','installment_amount','amount','loan_date','grace_period','maturity_date','account_no','company_id','bank_branch_id','account_type_id']));
        if($acctermloan){
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
        if($this->acctermloan->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getBankAccount()
    {
      $rows=$this->bankaccount
        ->join('bank_branches', function($join)  {
            $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id');
        })
        ->join('banks',function($join){
            $join->on('bank_branches.bank_id','=','banks.id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('bank_accounts.currency_id','=','currencies.id');
        }) 
        ->leftJoin('companies',function($join){
            $join->on('bank_accounts.company_id','=','companies.id');
        }) 
        ->leftJoin('commercial_heads',function($join){
            $join->on('bank_accounts.account_type_id','=','commercial_heads.id');
        })
        ->when(request('name'), function ($q) {
            return $q->where('bank_branches.branch_name', 'LIKE', "%".request('name', 0)."%");
        })
        ->when(request('account_no'), function ($q) {
            return $q->where('bank_accounts.account_no', 'LIKE', "%".request('account_no', 0)."%");
        })
        ->orderBy('bank_accounts.company_id','desc')
        ->get([
            'banks.name as bank_name',
            'bank_accounts.*',
            'bank_branches.id as bank_branch_id',
            'bank_branches.branch_name',
            'currencies.name as currency_name',
            'companies.name as company_name',
            'commercial_heads.name as account_type'
        ]);

        echo json_encode($rows);
  
    }

}
