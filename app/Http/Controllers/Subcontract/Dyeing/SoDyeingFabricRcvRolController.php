<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvItemRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRolRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRefRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingFabricRcvRolRequest;

class SoDyeingFabricRcvRolController extends Controller {
   
    private $sodyeingfabricrcv;
    private $sodyeingfabricrcvitem;
    private $sodyeingfabricrcvrol;
    private $sodyeing;
    private $podyeingref;
    private $sodyeingitem;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $invisu;

    public function __construct(
		SoDyeingFabricRcvRepository $sodyeingfabricrcv,
		SoDyeingFabricRcvItemRepository $sodyeingfabricrcvitem,
		SoDyeingFabricRcvRolRepository $sodyeingfabricrcvrol,
		SoDyeingRepository $sodyeing, 
		SoDyeingRefRepository $podyeingref, 
		SoDyeingItemRepository $sodyeingitem, 
		AutoyarnRepository $autoyarn,
		GmtspartRepository $gmtspart,
		UomRepository $uom,
		ColorrangeRepository $colorrange,
		ColorRepository $color,
		InvIsuRepository $invisu
    ) {
        $this->sodyeingfabricrcv = $sodyeingfabricrcv;
        $this->sodyeingfabricrcvitem = $sodyeingfabricrcvitem;
        $this->sodyeingfabricrcvrol = $sodyeingfabricrcvrol;
        $this->sodyeing = $sodyeing;
        $this->podyeingref = $podyeingref;
        $this->sodyeingitem = $sodyeingitem;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->invisu = $invisu;

        $this->middleware('auth');
      
        //$this->middleware('permission:view.sodyeingfabricrcvitemrols',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingfabricrcvitemrols', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingfabricrcvitemrols',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingfabricrcvitemrols', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        /*'insert into so_dyeing_fabric_rcv_rols (so_dyeing_fabric_rcv_item_id, qty, rate, amount,created_by,created_at,updated_by,updated_at,deleted_at,row_status)
select id, qty, rate, amount,created_by,created_at,updated_by,updated_at,deleted_at,row_status
from so_dyeing_fabric_rcv_items';*/
		
        $rows=$this->sodyeingfabricrcvrol
        ->join('so_dyeing_fabric_rcv_items',function($join){
        $join->on('so_dyeing_fabric_rcv_items.id', '=', 'so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id');
        })
        ->where([['so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id','=',request('so_dyeing_fabric_rcv_item_id',0)]])
        ->orderBy('so_dyeing_fabric_rcv_rols.id','desc')
        ->get([
            'so_dyeing_fabric_rcv_rols.*',
            'so_dyeing_fabric_rcv_items.rate',
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
    public function store(SoDyeingFabricRcvRolRequest $request) {
        $sodyeingfabricrcvitem=$this->sodyeingfabricrcvitem->find($request->so_dyeing_fabric_rcv_item_id);

        $sodyeingfabricrcvrol=$this->sodyeingfabricrcvrol->create([
            'so_dyeing_fabric_rcv_item_id'=>$request->so_dyeing_fabric_rcv_item_id,
            'custom_no'=>$request->custom_no,
            'qty'=>$request->qty,
        
        ]);
        
        $total=$this->sodyeingfabricrcvrol
        ->where([['so_dyeing_fabric_rcv_item_id','=',$request->so_dyeing_fabric_rcv_item_id]])
        ->sum('qty');

        $this->sodyeingfabricrcvitem->update($sodyeingfabricrcvitem->id,[
            'qty'=>$total,
            'amount'=>$total*$sodyeingfabricrcvitem->rate,
        ]);

		if($sodyeingfabricrcvrol){
			return response()->json(array('success' => true,'id' =>  $sodyeingfabricrcvrol->id,'so_dyeing_fabric_rcv_item_id' =>  $request->so_dyeing_fabric_rcv_item_id,'message' => 'Save Successfully'),200);
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
        $rows=$this->sodyeingfabricrcvrol->find($id);
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
    public function update(SoDyeingFabricRcvRolRequest $request, $id) {
		if($request->qty <= 0){
			return response()->json(array('success' => false,'id' => $id,'so_dyeing_fabric_rcv_item_id' => $request->so_dyeing_fabric_rcv_item_id,'message' => '0 Qty Not Allowed'),200);
		}

        $sodyeingfabricrcvitem=$this->sodyeingfabricrcvitem->find($request->so_dyeing_fabric_rcv_item_id);

        $sodyeingfabricrcvrol=$this->sodyeingfabricrcvrol->update($id,
            //$request->except(['id','so_dyeing_fabric_rcv_item_id']
            [
                'so_dyeing_fabric_rcv_item_id'=>$request->so_dyeing_fabric_rcv_item_id,
                'custom_no'=>$request->custom_no,
                'qty'=>$request->qty,
            ]
        );

        $total=$this->sodyeingfabricrcvrol
        ->where([['so_dyeing_fabric_rcv_item_id','=',$request->so_dyeing_fabric_rcv_item_id]])
        ->sum('qty');

        $this->sodyeingfabricrcvitem->update($sodyeingfabricrcvitem->id,[
        'qty'=>$total,
        'amount'=>$total*$sodyeingfabricrcvitem->rate,
        ]);

		if($sodyeingfabricrcvrol){
			return response()->json(array('success' => true,'id' => $id,'so_dyeing_fabric_rcv_item_id' => $request->so_dyeing_fabric_rcv_item_id,'message' => 'Update Successfully'),200);
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
		if($this->sodyeingfabricrcvrol->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }

    
}