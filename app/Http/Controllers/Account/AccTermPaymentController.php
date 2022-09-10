<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccTermPaymentRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccTermPaymentRequest;

class AccTermPaymentController extends Controller {

    private $acctermpayment;
    private $commercialhead;

    public function __construct(
        AccTermPaymentRepository $acctermpayment,
        CommercialHeadRepository $commercialhead
    ) {
        $this->commercialhead = $commercialhead;
        $this->acctermpayment = $acctermpayment;

        $this->middleware('auth');
        // $this->middleware('permission:view.acctermpayments',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.acctermpayments', ['only' => ['store']]);
        // $this->middleware('permission:edit.acctermpayments',   ['only' => ['update']]);
        // $this->middleware('permission:delete.acctermpayments', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');

       $acctermpayments = array();
       $rows=$this->acctermpayment
       ->where([['acc_term_installment_id','=',request('acc_term_installment_id', 0)]])
       ->get();
       foreach($rows as $row){
           $acctermpayment['id']=$row->id;
           $acctermpayment['amount']=number_format($row->amount,2);
           $acctermpayment['interest_amount']=number_format($row->interest_amount,2);
           $acctermpayment['other_charge_amount']=number_format($row->other_charge_amount,2);
           $acctermpayment['delay_charge_amount']=number_format($row->delay_charge_amount,2);
           $acctermpayment['payment_source_id']=$commercialhead[$row->payment_source_id];
           $acctermpayment['payment_date']=date('Y-m-d',strtotime($row->payment_date));

           array_push($acctermpayments,$acctermpayment);
       }
       echo json_encode($acctermpayments);
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
    public function store(AccTermPaymentRequest $request) {
        $acctermpayment=$this->acctermpayment->create($request->except(['id']));
        if($acctermpayment){
            return response()->json(array('success' => true,'id' =>  $acctermpayment->id,'message' => 'Save Successfully'),200);
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
        $acctermpayment=$this->acctermpayment->find($id);
        $row ['fromData'] = $acctermpayment;
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
    public function update(AccTermPaymentRequest $request, $id) {
        $acctermpayment=$this->acctermpayment->update($id,$request->except(['id']));
        if($acctermpayment){
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
        if($this->acctermpayment->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
