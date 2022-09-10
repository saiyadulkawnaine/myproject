<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqPaidRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;

use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvPurReqPaidRequest;

class InvPurReqPaidController extends Controller {

    private $invpurreq;
    private $invpurreqpaid;

    public function __construct(InvPurReqRepository $invpurreq,InvPurReqPaidRepository $invpurreqpaid) {
        $this->invpurreq = $invpurreq;
        $this->invpurreqpaid = $invpurreqpaid;

        $this->middleware('auth');

        $this->middleware('permission:view.invpurreqpaids',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invpurreqpaids', ['only' => ['store']]);
        $this->middleware('permission:edit.invpurreqpaids',   ['only' => ['update']]);
        $this->middleware('permission:delete.invpurreqpaids', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $invpurreqpaids=array();
        $rows=$this->invpurreqpaid
        ->leftJoin('users',function($join){
            $join->on('users.id','=','inv_pur_req_paids.user_id');
         })
        ->where([['inv_pur_req_id','=',request('inv_pur_req_id',0)]])
        ->get([
        'inv_pur_req_paids.*',
        'users.name as user_name'
        ]);

        foreach($rows as $row){
            $invpurreqpaid['id']=$row->id;
            $invpurreqpaid['paid_date']=date('d-M-Y',strtotime($row->paid_date));
            $invpurreqpaid['amount']=$row->amount;
            $invpurreqpaid['user_id']=$row->user_id;
            $invpurreqpaid['user_name']=$row->user_name;  
            array_push($invpurreqpaids,$invpurreqpaid);
        }
        echo json_encode($invpurreqpaids);
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
    public function store(InvPurReqPaidRequest $request) {

        $invpurreqpaid = $this->invpurreqpaid->create([
            'inv_pur_req_id'=>$request->inv_pur_req_id,
            'paid_date'=>$request->paid_date,
            'amount'=>$request->amount,
            'user_id'=>$request->user_id
            ]);
		if($invpurreqpaid){
			return response()->json(array('success' => true,'id' =>  $invpurreqpaid->id,'message' => 'Save Successfully'),200);
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
        
        $invpurreqpaid = $this->invpurreqpaid->find($id);
        $invpurreqpaid->paid_date=date('Y-m-d',strtotime($invpurreqpaid->paid_date)); 
        $row ['fromData'] = $invpurreqpaid;
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
    public function update(InvPurReqPaidRequest $request, $id) {
        $invpurreqpaid=$this->invpurreqpaid->update($id,$request->except(['id']));
        
		if($invpurreqpaid){
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
        if($this->invpurreqpaid->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        } 
    }
}
