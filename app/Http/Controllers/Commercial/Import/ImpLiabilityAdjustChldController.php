<?php
namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpLiabilityAdjustChldRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLiabilityAdjustRepository;
use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;

use App\Library\Template;

use App\Http\Requests\Commercial\Import\ImpLiabilityAdjustChldRequest;

class ImpLiabilityAdjustChldController extends Controller {

    private $impliabladjustchld;
    private $impliabilityadjust;
    private $commercialhead;
    private $impdocaccept;
    private $bankaccount;

    public function __construct(
        ImpLiabilityAdjustChldRepository $impliabladjustchld,
        ImpLiabilityAdjustRepository $impliabilityadjust, 
        CommercialHeadRepository $commercialhead,
        ImpDocAcceptRepository $impdocaccept,
        BankAccountRepository $bankaccount
    ) {
        $this->impliabladjustchld = $impliabladjustchld;
        $this->impliabilityadjust = $impliabilityadjust;
        $this->commercialhead = $commercialhead;
        $this->impdocaccept = $impdocaccept;
        $this->bankaccount = $bankaccount;


        $this->middleware('auth');
        $this->middleware('permission:view.impliabilityadjustchlds',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.impliabilityadjustchlds', ['only' => ['store']]);
        $this->middleware('permission:edit.impliabilityadjustchlds',   ['only' => ['update']]);
        $this->middleware('permission:delete.impliabilityadjustchlds', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
         
        $impliabladjustchlds=array();
        $rows=$this->impliabladjustchld
        ->leftJoin('bank_accounts', function($join)  {
            $join->on('bank_accounts.id', '=', 'imp_liability_adjust_chlds.bank_account_id');
        })
        ->leftJoin('commercial_heads',function($join){
            $join->on('bank_accounts.account_type_id','=','commercial_heads.id');
        })
        ->where([['imp_liability_adjust_id','=',request('imp_liability_adjust_id',0)]])
        ->orderBy('imp_liability_adjust_chlds.id','desc')
        ->get([
            'imp_liability_adjust_chlds.*',
            'commercial_heads.name as commercial_head_name'
        ]);
        foreach($rows as $row){
            $impliabladjustchld['id']=$row->id;
            $impliabladjustchld['commercial_head_name']=$row->commercial_head_name;
            $impliabladjustchld['payment_head']=$commercialhead[$row->payment_head];
            $impliabladjustchld['adj_source']=$commercialhead[$row->adj_source];
            $impliabladjustchld['exch_rate']=$row->exch_rate;
            $impliabladjustchld['amount']=$row->amount;
            $impliabladjustchld['dom_currency']=$row->dom_currency;
            $impliabladjustchld['issuing_bank_id']=$row->issuing_bank_id;
            $impliabladjustchld['loan_ref']=$row->loan_ref;
            $impliabladjustchld['maturity_date']=$row->maturity_date;
            $impliabladjustchld['remarks']=$row->remarks;
            array_push($impliabladjustchlds,$impliabladjustchld);
        }
        echo json_encode($impliabladjustchlds);
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
    public function store(ImpLiabilityAdjustChldRequest $request) {

        $impliabladjustchld=$this->impliabladjustchld->create($request->except(['id','commercial_head_name']));
        
        if($impliabladjustchld){
            return response()->json(array('success' => true,'id' =>  $impliabladjustchld->id ,'message' => 'Save Successfully'),200);
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
       $impliabladjustchld = $this->impliabladjustchld
       ->leftJoin('bank_accounts', function($join)  {
            $join->on('bank_accounts.id', '=', 'imp_liability_adjust_chlds.bank_account_id');
        })
        ->leftJoin('commercial_heads',function($join){
            $join->on('bank_accounts.account_type_id','=','commercial_heads.id');
        })
       ->where([['imp_liability_adjust_chlds.id','=',$id]])
       ->get([
        'imp_liability_adjust_chlds.*',
        'commercial_heads.name as commercial_head_name'
       ])
       ->first();
       $row ['fromData'] = $impliabladjustchld;
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
    public function update(ImpLiabilityAdjustChldRequest $request, $id) {
            
        $impliabladjustchld=$this->impliabladjustchld->update($id,[
            'payment_head'=>$request->payment_head,
            'adj_source'=>$request->adj_source,
            'exch_rate'=>$request->exch_rate,
            'amount'=>$request->amount,
            'dom_currency'=>$request->dom_currency,
            'issuing_bank_id'=>$request->issuing_bank_id,
            'remarks'=>$request->remarks,
            'loan_ref'=>$request->loan_ref,
            'tenor'=>$request->tenor,
            'maturity_date'=>$request->maturity_date,
            'bank_account_id'=>$request->bank_account_id,
        ]);
            
        
        if($impliabladjustchld){
            return response()->json(array('success' => true,'id' =>  $id ,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->impliabladjustchld->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getBankAccount(){
       $imp_doc_accept_id=request('imp_doc_accept_id',0);
       $impdocaccept=$this->impdocaccept
       ->join('imp_lcs',function($join){
         $join->on('imp_lcs.id','=','imp_doc_accepts.imp_lc_id');
        })
       ->where([['imp_doc_accepts.id','=',$imp_doc_accept_id]])
       ->get([
        'imp_lcs.issuing_bank_branch_id',
        'imp_lcs.company_id'
        ])
       ->first();

       $rows=$this->bankaccount
        ->join('bank_branches', function($join)  {
            $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id');
        })
        ->join('banks',function($join){
            $join->on('bank_branches.bank_id','=','banks.id');
        })
        ->join('commercial_heads',function($join){
            $join->on('bank_accounts.account_type_id','=','commercial_heads.id');
        })
        ->where([['bank_branches.id','=',$impdocaccept->issuing_bank_branch_id]])
        ->where([['bank_accounts.company_id','=',$impdocaccept->company_id]])
        ->where([['commercial_heads.commercialhead_type_id','=', 27]])
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
