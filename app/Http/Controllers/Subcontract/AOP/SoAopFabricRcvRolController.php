<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvItemRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRolRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRefRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopFabricRcvRolRequest;

class SoAopFabricRcvRolController extends Controller {
   
    private $soaopfabricrcv;
    private $soaopfabricrcvitem;
    private $soaopfabricrcvrol;
    private $soaop;
    private $poaopref;
    private $soaopitem;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $invisu;
    private $itemaccount;
    private $prodfinishdlv;

    public function __construct(
		SoAopFabricRcvRepository $soaopfabricrcv,
		SoAopFabricRcvItemRepository $soaopfabricrcvitem,
		SoAopFabricRcvRolRepository $soaopfabricrcvrol,
		SoAopRepository $soaop, 
		SoAopRefRepository $poaopref, 
		SoAopItemRepository $soaopitem, 
		AutoyarnRepository $autoyarn,
		GmtspartRepository $gmtspart,
		UomRepository $uom,
		ColorrangeRepository $colorrange,
		ColorRepository $color,
		InvIsuRepository $invisu,
        ItemAccountRepository $itemaccount,
        ProdFinishDlvRepository $prodfinishdlv
    ) {
        $this->soaopfabricrcv = $soaopfabricrcv;
        $this->soaopfabricrcvitem = $soaopfabricrcvitem;
        $this->soaopfabricrcvrol = $soaopfabricrcvrol;
        $this->soaop = $soaop;
        $this->poaopref = $poaopref;
        $this->soaopitem = $soaopitem;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->invisu = $invisu;
        $this->itemaccount = $itemaccount;
        $this->prodfinishdlv = $prodfinishdlv;
        $this->middleware('auth');
      
        //$this->middleware('permission:view.soaopfabricrcvitemrols',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.soaopfabricrcvitemrols', ['only' => ['store']]);
        //$this->middleware('permission:edit.soaopfabricrcvitemrols',   ['only' => ['update']]);
        //$this->middleware('permission:delete.soaopfabricrcvitemrols', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        /*'insert into so_aop_fabric_rcv_rols (so_aop_fabric_rcv_item_id, qty, rate, amount,created_by,created_at,updated_by,updated_at,deleted_at,row_status)
select id, qty, rate, amount,created_by,created_at,updated_by,updated_at,deleted_at,row_status
from so_aop_fabric_rcv_items';*/
		
        $rows=$this->soaopfabricrcvrol
        ->join('so_aop_fabric_rcv_items',function($join){
        $join->on('so_aop_fabric_rcv_items.id', '=', 'so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id');
        })
        ->where([['so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id','=',request('so_aop_fabric_rcv_item_id',0)]])
        ->orderBy('so_aop_fabric_rcv_rols.id','desc')
        ->get([
            'so_aop_fabric_rcv_rols.*',
            'so_aop_fabric_rcv_items.rate',
        ])
        ->map(function($rows){
            $rows->amount=number_format($rows->qty*$rows->rate,2);
            $rows->qty=number_format($rows->qty,2);
            return $rows;
        });
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
    public function store(SoAopFabricRcvRolRequest $request) {
            $soaopfabricrcvitem=$this->soaopfabricrcvitem->find($request->so_aop_fabric_rcv_item_id);

			$soaopfabricrcvrol=$this->soaopfabricrcvrol->create([
			'so_aop_fabric_rcv_item_id'=>$request->so_aop_fabric_rcv_item_id,
			'custom_no'=>$request->custom_no,
            'qty'=>$request->qty,
			'room'=>$request->room,
			'rack'=>$request->rack,
			'shelf'=>$request->shelf,
            'remarks'=>$request->remarks,
			]);
            
            $total=$this->soaopfabricrcvrol
            ->where([['so_aop_fabric_rcv_item_id','=',$request->so_aop_fabric_rcv_item_id]])
            ->sum('qty');

            $this->soaopfabricrcvitem->update($soaopfabricrcvitem->id,[
                'qty'=>$total,
                'amount'=>$total*$soaopfabricrcvitem->rate,
            ]);

		if($soaopfabricrcvrol){
			return response()->json(array('success' => true,'id' =>  $soaopfabricrcvrol->id,'so_aop_fabric_rcv_item_id' =>  $request->so_aop_fabric_rcv_item_id,'message' => 'Save Successfully'),200);
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
        $rows=$this->soaopfabricrcvrol->find($id);
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
    public function update(SoAopFabricRcvRolRequest $request, $id) {
		if($request->qty <= 0){
			return response()->json(array('success' => false,'id' => $id,'so_aop_fabric_rcv_item_id' => $request->so_aop_fabric_rcv_item_id,'message' => '0 Qty Not Allowed'),200);
		}

        $soaopfabricrcvitem=$this->soaopfabricrcvitem->find($request->so_aop_fabric_rcv_item_id);

        $soaopfabricrcvrol=$this->soaopfabricrcvrol->update($id,$request->except(['id','so_aop_fabric_rcv_item_id']));

        $total=$this->soaopfabricrcvrol
        ->where([['so_aop_fabric_rcv_item_id','=',$request->so_aop_fabric_rcv_item_id]])
        ->sum('qty');

        $this->soaopfabricrcvitem->update($soaopfabricrcvitem->id,[
        'qty'=>$total,
        'amount'=>$total*$soaopfabricrcvitem->rate,
        ]);

		if($soaopfabricrcvrol){
			return response()->json(array('success' => true,'id' => $id,'so_aop_fabric_rcv_item_id' => $request->so_aop_fabric_rcv_item_id,'message' => 'Update Successfully'),200);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
		if($this->soaopfabricrcvrol->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }

    
}