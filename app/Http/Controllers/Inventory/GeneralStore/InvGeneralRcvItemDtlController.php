<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvItemRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvItemDtlRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvGeneralRcvItemDtlRequest;

class InvGeneralRcvItemDtlController extends Controller {

    private $invrcv;
    private $invgeneralrcv;
    private $invgeneralrcvitem;
    private $invgeneralrcvitemdtl;
    

    public function __construct(
        InvRcvRepository $invrcv,
        InvGeneralRcvRepository $invgeneralrcv, 
        InvGeneralRcvItemRepository $invgeneralrcvitem,
        InvGeneralRcvItemDtlRepository $invgeneralrcvitemdtl
        
    ) {
        $this->invrcv = $invrcv;
        $this->invgeneralrcv = $invgeneralrcv;
        $this->invgeneralrcvitem = $invgeneralrcvitem;
        $this->invgeneralrcvitemdtl = $invgeneralrcvitemdtl;
       
        $this->middleware('auth');
        //$this->middleware('permission:view.invgeneralrcv',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invgeneralrcv', ['only' => ['store']]);
        //$this->middleware('permission:edit.invgeneralrcv',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invgeneralrcv', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $inv_general_rcv_item_id=request('inv_general_rcv_item_id',0);
        $rows=$this->invgeneralrcvitemdtl
        ->where([['inv_general_rcv_item_id','=',$inv_general_rcv_item_id]])
        ->get();
        echo json_encode($rows);
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
    public function store(InvGeneralRcvItemDtlRequest $request) {
        $invgeneralrcvitemdtl = $this->invgeneralrcvitemdtl->create(
        [
        'inv_general_rcv_item_id'=> $request->inv_general_rcv_item_id,         
        'serial_no'=> $request->serial_no,
        'qty'=>1,
        'warantee_date'=> $request->warantee_date,        
        ]);

        if($invgeneralrcvitemdtl){
        return response()->json(array('success' =>true ,'id'=>$invgeneralrcvitemdtl->id, 'inv_general_rcv_item_id'=>$request->inv_general_rcv_item_id,'message'=>'Saved Successfully'),200);
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
        $rows=$this->invgeneralrcvitemdtl->find($id);
        $row ['fromData'] = $rows;
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
    public function update(InvGeneralRcvItemDtlRequest $request, $id) {
      $invgeneralrcvitemdtl = $this->invgeneralrcvitemdtl->update($id,
        [
        'serial_no'=> $request->serial_no,
        'qty'=>1,
        'warantee_date'=> $request->warantee_date,        
        ]);

        if($invgeneralrcvitemdtl){
        return response()->json(array('success' =>true ,'id'=>$id, 'inv_general_rcv_item_id'=>$request->inv_general_rcv_item_id,'message'=>'Update Successfully'),200);
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
        return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
        if($this->invgeneralrcvitemdtl->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }
}