<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\BankChargeRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Import\BankChargeRequest;

class BankChargeController extends Controller {

    private $bankcharge;

    public function __construct(BankChargeRepository $bankcharge) {
        $this->bankcharge = $bankcharge;

        $this->middleware('auth');
        //$this->middleware('permission:view.bank_charges',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.bank_charges', ['only' => ['store']]);
        //$this->middleware('permission:edit.bank_charges',   ['only' => ['update']]);
        //$this->middleware('permission:delete.bank_charges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $bankcharges = array();
       $rows = $this->bankcharge->get();
       foreach($rows as $row){
         $bankcharge['id']=$row->id;
         $bankcharge['amount']=$row->amount;
         array_push($bankcharges,$bankcharge);
       }
       echo json_encode($bankcharges);
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
    public function store(BankChargeRequest $request) {
      $bankcharge=$this->bankcharge->create($request>except(['id']));
      if($bankcharge){
         return response()->json(array('success'=>true,'id'=>$bankcharge->id,'message'=>'Save Successfully'),200);
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
        $bankcharge=$this->bankcharge->find($id);
        $row['fromData']=$bankcharge;
        $dropdown['att']='';
        $row['dropDown']=$dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BankChargeRequest $request, $id) {
        $bankcharge=$this->bankcharge->update($id,$request>except(['id']));
        if($bankcharge){
           return response()->json(array('success'=>true,'id'=>$id,'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->bankcharge->delete($id)){
           return response()->json(array('success'=>true,'message'=>'Delete Successfully'),200);
        }
    }

}
