<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpBankChargeRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Import\ImpBankChargeRequest;

class ImpBankChargeController extends Controller {

    private $impbankcharge;

    public function __construct(ImpBankChargeRepository $impbankcharge) {
        $this->impbankcharge = $impbankcharge;

        $this->middleware('auth');
        $this->middleware('permission:view.impbankcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.impbankcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.impbankcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.impbankcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $impbankcharges = array();
       $rows = $this->impbankcharge->get();
       foreach($rows as $row){
         $impbankcharge['id']=$row->id;
         $impbankcharge['amount']=$row->amount;
         array_push($impbankcharges,$impbankcharge);
       }
       echo json_encode($impbankcharges);
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
    public function store(ImpBankChargeRequest $request) {
      $impbankcharge=$this->impbankcharge->create($request>except(['id']));
      if($impbankcharge){
         return response()->json(array('success'=>true,'id'=>$impbankcharge->id,'message'=>'Save Successfully'),200);
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
        $impbankcharge=$this->impbankcharge->find($id);
        $row['fromData']=$impbankcharge;
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
    public function update(ImpBankChargeRequest $request, $id) {
        $impbankcharge=$this->impbankcharge->update($id,$request>except(['id']));
        if($impbankcharge){
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
        if($this->impbankcharge->delete($id)){
           return response()->json(array('success'=>true,'message'=>'Delete Successfully'),200);
        }
    }

}
