<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\BankAccountRequest;

class BankAccountController extends Controller {

    private $bankaccount;
    private $bank;
    private $currency;
    private $bankbranch;
    private $commercialhead;
    private $company;
    

    public function __construct(
        BankAccountRepository $bankaccount,
        BankRepository $bank,
        CurrencyRepository $currency,
        BankBranchRepository $bankbranch,
        CommercialHeadRepository $commercialhead,
        CompanyRepository $company
    ) {
        $this->bankaccount = $bankaccount;
        $this->bank = $bank;
        $this->currency = $currency;
        $this->company = $company;
        $this->bankbranch = $bankbranch;
        $this->commercialhead = $commercialhead;
		
        $this->middleware('auth');
        $this->middleware('permission:view.bankaccounts',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.bankaccounts', ['only' => ['store']]);
        $this->middleware('permission:edit.bankaccounts',   ['only' => ['update']]);
        $this->middleware('permission:delete.bankaccounts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {        
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $bankaccounts=array();
        $rows=$this->bankaccount->where([['bank_branch_id','=',request('bank_branch_id',0)]])->get();
        foreach ($rows as $row) {
          $bankaccount['id']=$row->id;
          $bankaccount['account_no']=$row->account_no;
          $bankaccount['account_type_id']=$commercialhead[$row->account_type_id];
          $bankaccount['currency_id']=$currency[$row->currency_id];
          $bankaccount['company_id']=$company[$row->company_id];
          array_push($bankaccounts,$bankaccount);
        }
        echo json_encode($bankaccounts);
        
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
    public function store(BankAccountRequest $request) {
		$bankaccount = $this->bankaccount->create($request->except(['id']));
        if ($bankaccount) {
            return response()->json(array('success' => true, 'id' => $bankaccount->id, 'message' => 'Save Successfully'), 200);
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
       $bankaccount = $this->bankaccount->find($id);
        $row ['fromData'] = $bankaccount;
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
    public function update(BankAccountRequest $request, $id) {
      $bankaccount = $this->bankaccount->update($id, $request->except(['id']));
        if ($bankaccount) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
         if ($this->bankaccount->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getDebitAccount(Request $request){
        $bankaccount=
            $this->bankaccount
            ->leftJoin('commercial_heads',function($join){
                $join->on('commercial_heads.id','=','bank_accounts.account_type_id');
            })
            ->whereIn('commercial_heads.commercialhead_type_id',[12,13,14,18])
            ->where([['bank_accounts.company_id','=',request('company_id',0)]])
            ->where([['bank_accounts.bank_branch_id','=',request('issuing_bank_branch_id',0)]])
            ->get([
                'bank_accounts.id',
                'commercial_heads.name as c_name',
                'bank_accounts.account_no',
            ])
            ->map(function($bankaccount){
                $bankaccount->name=$bankaccount->c_name.' (' .$bankaccount->account_no. ' )';
                return $bankaccount;
            });
        echo json_encode($bankaccount);
    }

}
