<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\GeneralStore\InvCasReqItemRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvCasReqRepository;

use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvCasReqItemRequest;
use App\Repositories\Contracts\Util\UomRepository;

class InvCasReqItemController extends Controller {

    private $casreqitem;
    private $invpurReq;
    private $uom;

    public function __construct(InvCasReqItemRepository $casreqitem,InvCasReqRepository $invpurReq,UomRepository $uom) {
        $this->casreqitem = $casreqitem;
        $this->invpurReq = $invpurReq;
        $this->uom = $uom;

        $this->middleware('auth');
        //$this->middleware('permission:view.invcasreqitems',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invcasreqitems', ['only' => ['store']]);
        //$this->middleware('permission:edit.invcasreqitems',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invcasreqitems', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
        $invpurReq=array_prepend(array_pluck($this->invpurReq->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
        $casreqitems=array();
        $rows=$this->casreqitem->where([['inv_pur_req_id','=',request('inv_pur_req_id',0)]])->get();
        foreach($rows as $row){
            $casreqitem['id']=$row->id;
            $casreqitem['inv_pur_req_id']=$invpurReq[$row->inv_pur_req_id];
            $casreqitem['item_description']=$row->item_description;
            $casreqitem['qty']=number_format($row->qty,2);
            $casreqitem['uom_code']=$uom[$row->uom_id];
            $casreqitem['rate']=$row->rate;
            $casreqitem['amount']=number_format($row->amount,2); 
            $casreqitem['remarks']=$row->remarks;
            array_push($casreqitems,$casreqitem);
        }
        echo json_encode($casreqitems);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvCasReqItemRequest $request) {
		$casreqitem=$this->casreqitem->create($request->except(['id']));
		if($casreqitem){
			return response()->json(array('success' => true,'id' =>  $casreqitem->id,'message' => 'Save Successfully'),200);
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
        $casreqitem = $this->casreqitem->find($id);
        $row ['fromData'] = $casreqitem;
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
    public function update(InvCasReqItemRequest $request, $id) {
        $casreqitem=$this->casreqitem->update($id,$request->except(['id']));
		if($casreqitem){
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
        if($this->casreqitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        } 
    }

}
