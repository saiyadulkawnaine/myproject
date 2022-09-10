<?php
namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveLoanRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveLoanRequest;



class CashIncentiveLoanController extends Controller {

    private $cashincentiveloan;
    private $cashincentiveref;


    public function __construct(CashIncentiveLoanRepository $cashincentiveloan, CashIncentiveRefRepository $cashincentiveref) {
        $this->cashincentiveloan = $cashincentiveloan;
        $this->cashincentiveref = $cashincentiveref;


        $this->middleware('auth');

        $this->middleware('permission:view.cashincentiveloans',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.cashincentiveloans', ['only' => ['store']]);
        $this->middleware('permission:edit.cashincentiveloans',   ['only' => ['update']]);
        $this->middleware('permission:delete.cashincentiveloans', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         
        $cashincentiveloans=array();
        $rows=$this->cashincentiveloan
        ->selectRaw(
            'cash_incentive_loans.id,
            cash_incentive_loans.cash_incentive_ref_id,
            cash_incentive_loans.advance_date,
            cash_incentive_loans.loan_ref_no,
            cash_incentive_loans.advance_per,
            cash_incentive_loans.advance_amount_tk,
            cash_incentive_loans.advance_amount_usd,
            cash_incentive_loans.rate,
            cash_incentive_loans.remarks,
            banks.name as bank_name,
            bank_branches.branch_name,
            sum(cash_incentive_claims.claim_amount) as claim_amount,
            sum(cash_incentive_claims.local_cur_amount) as local_cur_amount
            ')
            ->leftJoin('cash_incentive_refs',function($join){
                $join->on('cash_incentive_loans.cash_incentive_ref_id','=','cash_incentive_refs.id');
            })
            ->leftJoin('exp_lc_scs',function($join){
                $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
            })
            ->leftJoin('bank_branches',function($join){
                $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
            })
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->leftJoin('cash_incentive_claims',function($join){
                $join->on('cash_incentive_claims.cash_incentive_ref_id','=','cash_incentive_refs.id');
            })
            ->where([['cash_incentive_loans.cash_incentive_ref_id','=',request('cash_incentive_ref_id',0)]])
            ->orderBy('cash_incentive_loans.id','desc')
            ->groupBy([
                'cash_incentive_loans.id',
                'cash_incentive_loans.cash_incentive_ref_id',
                'cash_incentive_loans.advance_date',
                'cash_incentive_loans.loan_ref_no',
                'cash_incentive_loans.advance_per',
                'cash_incentive_loans.advance_amount_tk',
                'cash_incentive_loans.advance_amount_usd',
                'cash_incentive_loans.rate',
                'cash_incentive_loans.remarks',
                'banks.name',
                'bank_branches.branch_name',
            ])
            ->get();
        
        foreach($rows as $row){
            $cashincentiveloan['id']=$row->id;
            $cashincentiveloan['advance_date']=date('Y-m-d',strtotime($row->advance_date));
            $cashincentiveloan['loan_ref_no']=$row->loan_ref_no;
            $cashincentiveloan['advance_per']=$row->advance_per;
            $cashincentiveloan['advance_amount_tk']=number_format($row->advance_amount_tk,2);
            $cashincentiveloan['advance_amount_usd']=number_format($row->advance_amount_usd,2);
            $cashincentiveloan['claim_amount']=number_format($row->claim_amount,2);
            $cashincentiveloan['local_cur_amount']=number_format($row->local_cur_amount,2);
            $cashincentiveloan['remarks']=$row->remarks;
            array_push($cashincentiveloans,$cashincentiveloan);
        }
        echo json_encode($cashincentiveloans);
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
    public function store(CashIncentiveLoanRequest $request) {
        $cashincentiveloan=$this->cashincentiveloan->create([
            'cash_incentive_ref_id'=>$request->cash_incentive_ref_id,
            'advance_date'=>$request->advance_date,
            'loan_ref_no'=>$request->loan_ref_no,
            'advance_per'=>$request->advance_per,
            'advance_amount_tk'=>$request->advance_amount_tk,
            'advance_amount_usd'=>$request->advance_amount_usd,
            'rate'=>$request->rate,
            'remarks'=>$request->remarks,
        ]);
        if($cashincentiveloan){
            return response()->json(array('success' => true,'id' =>  $cashincentiveloan->id,'message' => 'Save Successfully'),200);
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
       $cashincentiveloan = $this->cashincentiveloan
       ->selectRaw(     
        '
        cash_incentive_loans.id,
        cash_incentive_loans.cash_incentive_ref_id,
        cash_incentive_loans.advance_date,
        cash_incentive_loans.loan_ref_no,
        cash_incentive_loans.advance_per,
        cash_incentive_loans.advance_amount_tk,
        cash_incentive_loans.advance_amount_usd,
        cash_incentive_loans.rate,
        cash_incentive_loans.remarks,
        banks.name as bank_name,
        bank_branches.branch_name,
        sum(cash_incentive_claims.claim_amount) as claim_amount,
        sum(cash_incentive_claims.local_cur_amount) as local_cur_amount
        ')
        ->leftJoin('cash_incentive_refs',function($join){
            $join->on('cash_incentive_loans.cash_incentive_ref_id','=','cash_incentive_refs.id');
        })
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->leftJoin('bank_branches',function($join){
            $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->leftJoin('cash_incentive_claims',function($join){
            $join->on('cash_incentive_claims.cash_incentive_ref_id','=','cash_incentive_refs.id');
        })
        ->where([['cash_incentive_loans.id','=',$id]])
        ->groupBy([
            'cash_incentive_loans.id',
            'cash_incentive_loans.cash_incentive_ref_id',
            'cash_incentive_loans.advance_date',
            'cash_incentive_loans.loan_ref_no',
            'cash_incentive_loans.advance_per',
            'cash_incentive_loans.advance_amount_tk',
            'cash_incentive_loans.advance_amount_usd',
            'cash_incentive_loans.rate',
            'cash_incentive_loans.remarks',
            'banks.name',
            'bank_branches.branch_name',
        ])
        ->get(/* [
            'cash_incentive_loans.*',
            'banks.name as bank_name',
            'bank_branches.branch_name',
           // 'sum(cash_incentive_claims.claim_amount) as claim_amount',
           // 'sum(cash_incentive_claims.local_cur_amount) as local_cur_amount'
        ] */)
        ->map(function($cashincentiveloan){
            $cashincentiveloan->exporter_branch_name=$cashincentiveloan->bank_name.' (' .$cashincentiveloan->branch_name. ' )';
            //$rows->claim_amount=number_format($rows->claim_amount,2);
            //$rows->local_cur_amount=number_format($rows->local_cur_amount,2);
            return $cashincentiveloan;
        })
        ->first();
       $row ['fromData'] = $cashincentiveloan;
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
    public function update(CashIncentiveLoanRequest $request, $id) {
        $cashincentiveloan=$this->cashincentiveloan->update($id,[
            'cash_incentive_ref_id'=>$request->cash_incentive_ref_id,
            'advance_date'=>$request->advance_date,
            'loan_ref_no'=>$request->loan_ref_no,
            'advance_per'=>$request->advance_per,
            'advance_amount_tk'=>$request->advance_amount_tk,
            'advance_amount_usd'=>$request->advance_amount_usd,
            'rate'=>$request->rate,
            'remarks'=>$request->remarks,
        ]);
        if($cashincentiveloan){
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
        if($this->cashincentiveloan->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getCashRef(){
        $id=request('cash_incentive_ref_id',0);
        $rows = $this->cashincentiveref
        ->selectRaw(
            'cash_incentive_refs.id as cash_incentive_ref_id,
            sum(cash_incentive_claims.claim_amount) as claim_amount,
            sum(cash_incentive_claims.local_cur_amount) as local_cur_amount
        ')
        ->join('cash_incentive_claims',function($join){
            $join->on('cash_incentive_claims.cash_incentive_ref_id','=','cash_incentive_refs.id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        ->groupBy([
            'cash_incentive_refs.id',
        ])
        ->get()
        ->map(function($rows){
            return $rows;
        })
        ->first();
        echo json_encode($rows);
    }
}
