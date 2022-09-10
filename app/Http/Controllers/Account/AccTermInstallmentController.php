<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccTermInstallmentRepository;
use App\Library\Template;
use Illuminate\Support\Carbon;
use App\Http\Requests\Account\AccTermInstallmentRequest;

class AccTermInstallmentController extends Controller {

    private $accterminstallment;

    public function __construct(AccTermInstallmentRepository $accterminstallment) {
        $this->accterminstallment = $accterminstallment;

        $this->middleware('auth');
        // $this->middleware('permission:view.accterminstallments',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.accterminstallments', ['only' => ['store']]);
        // $this->middleware('permission:edit.accterminstallments',   ['only' => ['update']]);
        // $this->middleware('permission:delete.accterminstallments', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $accterminstallments=array();
      $rows = $this->accterminstallment
        ->join('acc_term_loans', function($join)  {
            $join->on('acc_term_installments.acc_term_loan_id', '=', 'acc_term_loans.id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            acc_term_installments.id as acc_term_installment_id,
            sum(acc_term_payments.amount) as principal_amount,
            sum(acc_term_payments.interest_amount) as interest_amount
            FROM acc_term_installments 
            join acc_term_payments on acc_term_installments.id =acc_term_payments.acc_term_installment_id 
            where acc_term_payments.deleted_at is null  
            group by acc_term_installments.id
        ) payments"), "payments.acc_term_installment_id", "=", "acc_term_installments.id")
        ->where([['acc_term_installments.acc_term_loan_id','=',request('acc_term_loan_id', 0)]])
        ->orderBy('acc_term_installments.sort_id','asc')
        ->get([
            'acc_term_installments.*',
            'acc_term_loans.loan_date',
            'payments.principal_amount',
            'payments.interest_amount',
        ])
        ->map(function($rows){
            $rows->paid_amount=$rows->principal_amount+$rows->interest_amount;
            $rows->balance_amount=$rows->amount-$rows->paid_amount;
            return $rows;
        });
        foreach ($rows as $row) {
            $accterminstallment['id']=$row->id;
            $accterminstallment['sort_id']=$row->sort_id;
            $accterminstallment['loan_date']=date('Y-m-d',strtotime($row->loan_date));
            $accterminstallment['balance_amount']=number_format($row->balance_amount,2);
            $accterminstallment['paid_amount']=number_format($row->paid_amount,2);
            
            $accterminstallment['amount']=number_format($row->amount,2);
            $accterminstallment['due_date']=date('Y-m-d',strtotime($row->due_date));
            
            array_push($accterminstallments,$accterminstallment);
        }

        echo json_encode($accterminstallments);
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
    public function store(AccTermInstallmentRequest $request) {
        //

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
        $accterminstallment=$this->accterminstallment
        ->join('acc_term_loans', function($join)  {
            $join->on('acc_term_installments.acc_term_loan_id', '=', 'acc_term_loans.id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            acc_term_installments.id as acc_term_installment_id,
            sum(acc_term_payments.amount) as principal_amount,
            sum(acc_term_payments.interest_amount) as interest_amount
            FROM acc_term_installments 
            join acc_term_payments on acc_term_installments.id =acc_term_payments.acc_term_installment_id 
            where acc_term_payments.deleted_at is null  
            group by acc_term_installments.id
        ) payments"), "payments.acc_term_installment_id", "=", "acc_term_installments.id")
        ->where([['acc_term_installments.id','=',$id]])
        ->get([
            'acc_term_installments.*',
            'acc_term_loans.loan_date',
            'payments.principal_amount',
            'payments.interest_amount',
        ])
        ->map(function($rows){
            $rows->paid_amount=$rows->principal_amount+$rows->interest_amount;
            $rows->balance_amount=$rows->amount-$rows->paid_amount;
            return $rows;
        })
        ->first();

        $row ['fromData'] = $accterminstallment;
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
    public function update(AccTermInstallmentRequest $request, $id) {
        $installment=$this->accterminstallment->find($id);
        $payment=$this->accterminstallment
        ->leftJoin(\DB::raw("(
            SELECT 
            acc_term_installments.id as acc_term_installment_id,
            sum(acc_term_payments.amount)+sum(acc_term_payments.interest_amount) as paid_amount
            FROM acc_term_installments 
            join acc_term_payments on acc_term_installments.id =acc_term_payments.acc_term_installment_id 
            where acc_term_payments.deleted_at is null  
            group by acc_term_installments.id
        ) payments"), "payments.acc_term_installment_id", "=", "acc_term_installments.id")
        ->where([['acc_term_installments.id','=',$id]])
        ->get([
            'acc_term_installments.id',
            'payments.paid_amount',
        ])
        ->first();

        if ($installment->amount <= $payment->paid_amount) {
            return response()->json(array('success' => false,'id'=>$id,'message' => 'Installment Paid.Update Not Possible'),200);
        }

        $accterminstallment=$this->accterminstallment->update($id,[
            'amount'=>$request->amount,
            'due_date'=>$request->due_date
        ]);

        
        $due_date=Carbon::parse($request->due_date);
        $updateInstallments=$this->accterminstallment
        ->where([['acc_term_loan_id','=',$installment->acc_term_loan_id]])
        ->where([['id','>',$id]])
        ->get();
        $last_due_date=$due_date;
        foreach($updateInstallments as $row){
            $last_due_date=$last_due_date->addMonth();
            $this->accterminstallment->update($row->id,[
                'amount'=>$request->amount,
                'due_date'=>$last_due_date,
            ]);
        }


        if($accterminstallment){
            return response()->json(array('success' => true,'id'=>$id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->accterminstallment->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
