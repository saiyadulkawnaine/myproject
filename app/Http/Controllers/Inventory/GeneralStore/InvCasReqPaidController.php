<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\GeneralStore\InvCasReqPaidRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;

use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvCasReqPaidRequest;

class InvCasReqPaidController extends Controller {

    private $invcasreq;
    private $invcasreqpaid;

    public function __construct(InvPurReqRepository $invcasreq,InvCasReqPaidRepository $invcasreqpaid) {
        $this->invcasreq = $invcasreq;
        $this->invcasreqpaid = $invcasreqpaid;

        $this->middleware('auth');
        $this->middleware('permission:view.invcasreqpaids',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invcasreqpaids', ['only' => ['store']]);
        $this->middleware('permission:edit.invcasreqpaids',   ['only' => ['update']]);
        $this->middleware('permission:delete.invcasreqpaids', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $invcasreqpaids=array();
        $rows=$this->invcasreqpaid
        ->leftJoin('users',function($join){
            $join->on('users.id','=','inv_cas_req_paids.user_id');
         })
        ->where([['inv_pur_req_id','=',request('inv_pur_req_id',0)]])
        ->get([
        'inv_cas_req_paids.*',
        'users.name as user_name'
        ]);

        foreach($rows as $row){
            $invcasreqpaid['id']=$row->id;
            $invcasreqpaid['paid_date']=date('d-M-Y',strtotime($row->paid_date));
            $invcasreqpaid['amount']=$row->amount;
            $invcasreqpaid['user_id']=$row->user_id;
            $invcasreqpaid['user_name']=$row->user_name;  
            array_push($invcasreqpaids,$invcasreqpaid);
        }
        echo json_encode($invcasreqpaids);
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
    public function store(InvCasReqPaidRequest $request) {

        $invcasreqpaid = $this->invcasreqpaid->create([
            'inv_pur_req_id'=>$request->inv_pur_req_id,
            'paid_date'=>$request->paid_date,
            'amount'=>$request->amount,
            'user_id'=>$request->user_id
            ]);
		if($invcasreqpaid){
			return response()->json(array('success' => true,'id' =>  $invcasreqpaid->id,'message' => 'Save Successfully'),200);
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
        
        $invcasreqpaid = $this->invcasreqpaid->find($id);
        $invcasreqpaid->paid_date=date('Y-m-d',strtotime($invcasreqpaid->paid_date)); 
        $row ['fromData'] = $invcasreqpaid;
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
    public function update(InvCasReqPaidRequest $request, $id) {
        $invcasreqpaid=$this->invcasreqpaid->update($id,$request->except(['id']));
        
		if($invcasreqpaid){
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
        if($this->invcasreqpaid->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        } 
    }
}
