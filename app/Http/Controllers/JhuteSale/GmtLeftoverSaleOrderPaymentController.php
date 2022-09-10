<?php

namespace App\Http\Controllers\JhuteSale;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderPaymentRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\JhuteSale\GmtLeftoverSaleOrderPaymentRequest;

class GmtLeftoverSaleOrderPaymentController extends Controller
{
    private $jhutesaledlvorderpayment;
    private $jhutesaledlvorder;
    private $user;
    public function __construct(
        JhuteSaleDlvOrderPaymentRepository $jhutesaledlvorderpayment, 
        JhuteSaleDlvOrderRepository $jhutesaledlvorder, 
        UserRepository $user){

     $this->jhutesaledlvorderpayment=$jhutesaledlvorderpayment;
     $this->jhutesaledlvorder=$jhutesaledlvorder;
     $this->user=$user;

      $this->middleware('auth');
        // $this->middleware('permission:view.jhutesaledlvorderpayments',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.jhutesaledlvorderpayments', ['only' => ['store']]);
        // $this->middleware('permission:edit.jhutesaledlvorderpayments',   ['only' => ['update']]);
        // $this->middleware('permission:delete.jhutesaledlvorderpayments', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
     $jhutesaledlvorderpayments=array();
     $rows=$this->jhutesaledlvorderpayment
      ->where([['jhute_sale_dlv_order_id','=',request('jhute_sale_dlv_order_id',0)]])
     ->orderBy('jhute_sale_dlv_order_payments.id','desc')
     ->get();
     foreach($rows as $row){
      $jhutesaledlvorderpayment['id']=$row->id;
      $jhutesaledlvorderpayment['payment_date']=$row->payment_date;
      $jhutesaledlvorderpayment['amount']=number_format($row->amount,2);
      $jhutesaledlvorderpayment['receive_by_id']=$user[$row->receive_by_id];
      array_push($jhutesaledlvorderpayments,$jhutesaledlvorderpayment);
     }
     echo json_encode($jhutesaledlvorderpayments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GmtLeftoverSaleOrderPaymentRequest $request)
    {
    $jhutesaledlvorderpayment=$this->jhutesaledlvorderpayment->create([
        'jhute_sale_dlv_order_id'=>$request->jhute_sale_dlv_order_id,
        'payment_date'=>$request->payment_date,
        'amount'=>$request->amount,
        'receive_by_id'=>$request->receive_by_id,
    ]);
    if ($jhutesaledlvorderpayment) {
        return response()->json(array('success'=>true, 'id'=>$jhutesaledlvorderpayment->id, 'message'=>'Save Successfully'),200);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
    {
        $jhutesaledlvorderpayment = $this->jhutesaledlvorderpayment->find($id);
        $row['fromData'] = $jhutesaledlvorderpayment;
        $dropdown['att'] = '';
        $row['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GmtLeftoverSaleOrderPaymentRequest $request,$id)
    {
        $jhutesaledlvorderpayment=$this->jhutesaledlvorderpayment->update($id,$request->except(['id']));
        if ($jhutesaledlvorderpayment) {
           return response()->json(array('success'=> true, 'id' =>$id, 'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          if($this->jhutesaledlvorderpayment->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }
}