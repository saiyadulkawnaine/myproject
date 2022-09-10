<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvOrderRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvQtyRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRefRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbCutpanelRcvOrderRequest;

class SoEmbCutpanelRcvOrderController extends Controller
{


 private $soembcutpanelrcv;
 private $soembcutpanelrcvorder;
 private $soembcutpanelrcvqty;
 private $soemb;
 private $poembref;
 private $soembitem;
 private $autoyarn;
 private $gmtspart;
 private $uom;
 private $color;
 private $size;
 private $embelishment;
 private $embelishmenttype;


 public function __construct(
  SoEmbCutPanelRcvRepository $soembcutpanelrcv,
  SoEmbCutPanelRcvOrderRepository $soembcutpanelrcvorder,
  SoEmbCutPanelRcvQtyRepository $soembcutpanelrcvqty,
  SoEmbRepository $soemb,
  SoEmbRefRepository $poembref,
  SoEmbItemRepository $soembitem,
  AutoyarnRepository $autoyarn,
  GmtspartRepository $gmtspart,
  UomRepository $uom,
  ColorRepository $color,
  SizeRepository $size,
  EmbelishmentRepository $embelishment,
  EmbelishmentTypeRepository $embelishmenttype
 ) {
  $this->soembcutpanelrcv = $soembcutpanelrcv;
  $this->soembcutpanelrcvorder = $soembcutpanelrcvorder;
  $this->soembcutpanelrcvqty = $soembcutpanelrcvqty;
  $this->soemb = $soemb;
  $this->poembref = $poembref;
  $this->soembitem = $soembitem;
  $this->autoyarn = $autoyarn;
  $this->gmtspart = $gmtspart;
  $this->uom = $uom;
  $this->color = $color;
  $this->size = $size;
  $this->embelishment = $embelishment;
  $this->embelishmenttype = $embelishmenttype;
  $this->middleware('auth');

  // $this->middleware('permission:view.soembcutpanelrcvorders',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.soembcutpanelrcvorders', ['only' => ['store']]);
  // $this->middleware('permission:edit.soembcutpanelrcvorders',   ['only' => ['update']]);
  // $this->middleware('permission:delete.soembcutpanelrcvorders', ['only' => ['destroy']]);

 }


 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $rows = $this->soembcutpanelrcvorder
   ->join('so_emb_cutpanel_rcvs', function ($join) {
    $join->on('so_emb_cutpanel_rcvs.id', '=', 'so_emb_cutpanel_rcv_orders.so_emb_cutpanel_rcv_id');
   })
   ->join('so_embs', function ($join) {
    $join->on('so_embs.id', '=', 'so_emb_cutpanel_rcv_orders.so_emb_id');
   })
   ->where([['so_emb_cutpanel_rcv_orders.so_emb_cutpanel_rcv_id', '=', request('so_emb_cutpanel_rcv_id')]])
   ->orderBy('so_emb_cutpanel_rcv_orders.id', 'desc')
   ->get([
    'so_emb_cutpanel_rcv_orders.*',
    'so_embs.sales_order_no'
   ]);
  echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  //
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SoEmbCutpanelRcvOrderRequest $request)
 {
  $soembcutpanelrcvorder = $this->soembcutpanelrcvorder->create($request->except('id', 'sales_order_no'));
  if ($soembcutpanelrcvorder) {
   return response()->json(array('success' => true, 'id' =>  $soembcutpanelrcvorder->id, 'so_emb_cutpanel_rcv_id' =>  $request->so_emb_cutpanel_rcv_id, 'message' => 'Save Successfully'), 200);
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
  $rows = $this->soembcutpanelrcvorder
   ->join('so_emb_cutpanel_rcvs', function ($join) {
    $join->on('so_emb_cutpanel_rcvs.id', '=', 'so_emb_cutpanel_rcv_orders.so_emb_cutpanel_rcv_id');
   })
   ->join('so_embs', function ($join) {
    $join->on('so_embs.id', '=', 'so_emb_cutpanel_rcv_orders.so_emb_id');
   })
   ->where([['so_emb_cutpanel_rcv_orders.id', '=', $id]])
   ->get([
    'so_emb_cutpanel_rcv_orders.*',
    'so_embs.sales_order_no'
   ])
   ->first();

  $row['fromData'] = $rows;
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
 public function update(SoEmbCutpanelRcvOrderRequest $request, $id)
 {
  $soembcutpanelrcvqty = $this->soembcutpanelrcvqty
   ->where([['so_emb_cutpanel_rcv_order_id', '=', $id]])
   ->first();
  if ($soembcutpanelrcvqty) {
   return response()->json(array('success' => false, 'message' => 'Cutpanel Quantity Found'), 200);
  }

  $soembcutpanelrcvorder = $this->soembcutpanelrcvorder->update($id, [
   'so_emb_id' => $request->so_emb_id,
   'remarks' => $request->remarks
  ]);


  if ($soembcutpanelrcvorder) {
   return response()->json(array('success' => true, 'id' => $id, 'so_emb_cutpanel_rcv_id' => $request->so_emb_cutpanel_rcv_id, 'message' => 'Update Successfully'), 200);
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
  return response()->json(array('success' => false, 'message' => 'Delete Not Successfully'), 200);
  if ($this->soembcutpanelrcvorder->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getSoEmb()
 {
  $buyer_id = request('buyer_id', 0);
  $production_area_id = request('production_area_id', 0);
  $soemb = $this->soemb
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'so_embs.company_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('so_embs.buyer_id', '=', 'buyers.id');
   })
   ->when(request('company_id'), function ($q) {
    return $q->where('so_embs.company_id', '=', request('company_id'));
   })
   ->when(request('so_no'), function ($q) {
    return $q->where('so_embs.sales_order_no', '=', request('so_no'));
   })
   ->where([['so_embs.buyer_id', '=', $buyer_id]])
   ->where([['so_embs.production_area_id', '=', $production_area_id]])
   ->orderBy('so_embs.id', 'DESC')
   ->get([
    'so_embs.*',
    'buyers.name as buyer_name',
    'companies.name as company_name'
   ]);
  echo json_encode($soemb);
 }
}
