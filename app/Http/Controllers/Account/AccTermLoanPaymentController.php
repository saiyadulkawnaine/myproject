<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccTermLoanPaymentRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccTermLoanPaymentRequest;

class AccTermLoanPaymentController extends Controller {

    private $acctermloanpayment;
    private $commercialhead;

    public function __construct(
        AccTermLoanPaymentRepository $acctermloanpayment,
        CommercialHeadRepository $commercialhead
    ) {
        $this->commercialhead = $commercialhead;
        $this->acctermloanpayment = $acctermloanpayment;

        $this->middleware('auth');
        // $this->middleware('permission:view.acctermloanpayments',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.acctermloanpayments', ['only' => ['store']]);
        // $this->middleware('permission:edit.acctermloanpayments',   ['only' => ['update']]);
        // $this->middleware('permission:delete.acctermloanpayments', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');

       $acctermloanpayments = array();
       $rows=$this->acctermloanpayment
       ->where([['acc_term_loan_installment_id','=',request('acc_term_loan_installment_id', 0)]])
       ->orderBy('acc_term_loan_payments.id','desc')
       ->get();
       foreach($rows as $row){
           $acctermloanpayment['id']=$row->id;
           $acctermloanpayment['amount']=number_format($row->amount,2);
           $acctermloanpayment['interest_amount']=number_format($row->interest_amount,2);
           $acctermloanpayment['other_charge_amount']=number_format($row->other_charge_amount,2);
           $acctermloanpayment['delay_charge_amount']=number_format($row->delay_charge_amount,2);
           $acctermloanpayment['payment_source_id']=$commercialhead[$row->payment_source_id];
           $acctermloanpayment['payment_date']=date('Y-m-d',strtotime($row->payment_date));

           array_push($acctermloanpayments,$acctermloanpayment);
       }
       echo json_encode($acctermloanpayments);
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
    public function store(AccTermLoanPaymentRequest $request) {
        $acctermloanpayment=$this->acctermloanpayment->create($request->except(['id']));
        if($acctermloanpayment){
            return response()->json(array('success' => true,'id' =>  $acctermloanpayment->id,'message' => 'Save Successfully'),200);
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
        $acctermloanpayment=$this->acctermloanpayment->find($id);
        $row ['fromData'] = $acctermloanpayment;
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
    public function update(AccTermLoanPaymentRequest $request, $id) {
        $acctermloanpayment=$this->acctermloanpayment->update($id,$request->except(['id']));
        if($acctermloanpayment){
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
        if($this->acctermloanpayment->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
