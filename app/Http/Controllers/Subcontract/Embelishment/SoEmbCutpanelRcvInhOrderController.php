<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvOrderRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvQtyRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbCutpanelRcvInhOrderRequest;

class SoEmbCutpanelRcvInhOrderController extends Controller
{

 private $soembcutpanelrcvinhorder;
 private $soembcutpanelrcvinh;
 private $gmtprintreceiveqty;
 private $salesordercountry;
 private $salesordergmtcolorsize;
 private $soemb;

 public function __construct(
  SoEmbCutpanelRcvOrderRepository $soembcutpanelrcvinhorder,
  SoEmbCutpanelRcvRepository $prodgmtprintreceive,
  SoEmbCutpanelRcvQtyRepository $gmtprintreceiveqty,
  SalesOrderCountryRepository $salesordercountry,
  SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
  SoEmbRepository $soemb
 ) {
  $this->soembcutpanelrcvinhorder = $soembcutpanelrcvinhorder;
  $this->prodgmtprintreceive = $prodgmtprintreceive;
  $this->gmtprintreceiveqty = $gmtprintreceiveqty;
  $this->salesordercountry = $salesordercountry;
  $this->salesordergmtcolorsize = $salesordergmtcolorsize;
  $this->soemb = $soemb;

  $this->middleware('auth');
  /*$this->middleware('permission:view.prodgmtsoembcutpanelrcvinhorders',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodgmtsoembcutpanelrcvinhorders', ['only' => ['store']]);
        $this->middleware('permission:edit.prodgmtsoembcutpanelrcvinhorders',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodgmtsoembcutpanelrcvinhorders', ['only' => ['destroy']]);*/
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */

 public function index()
 {
  $soembcutpanelrcvinhorders = array();
  $rows = $this->soembcutpanelrcvinhorder
   ->join('so_embs', function ($join) {
    $join->on('so_embs.id', '=', 'so_emb_cutpanel_rcv_orders.so_emb_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'so_embs.buyer_id');
   })
   ->where([['so_emb_cutpanel_rcv_orders.so_emb_cutpanel_rcv_id', '=', request('so_emb_cutpanel_rcv_id', 0)]])
   ->orderBy('so_emb_cutpanel_rcv_orders.id', 'desc')
   ->get([
    'so_emb_cutpanel_rcv_orders.*',
    'so_embs.sales_order_no',
    'buyers.name as customer_name'
   ]);
  foreach ($rows as $row) {
   $soembcutpanelrcvinhorder['id'] = $row->id;
   $soembcutpanelrcvinhorder['sale_order_no'] = $row->sales_order_no;
   $soembcutpanelrcvinhorder['customer_name'] = $row->customer_name;
   $soembcutpanelrcvinhorder['remarks'] = $row->remarks;
   array_push($soembcutpanelrcvinhorders, $soembcutpanelrcvinhorder);
  }
  echo json_encode($soembcutpanelrcvinhorders);
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
 public function store(SoEmbCutpanelRcvInhOrderRequest $request)
 {
  $soembcutpanelrcvinhorder = $this->soembcutpanelrcvinhorder->create([
   'so_emb_cutpanel_rcv_id' => $request->so_emb_cutpanel_rcv_id,
   'so_emb_id' => $request->so_emb_id,
   'remarks' => $request->remarks,
  ]);

  if ($soembcutpanelrcvinhorder) {
   return response()->json(array('success' => true, 'id' =>  $soembcutpanelrcvinhorder->id, 'message' => 'Save Successfully'), 200);
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

  $soembcutpanelrcvinhorder = $this->soembcutpanelrcvinhorder
   ->join('so_embs', function ($join) {
    $join->on('so_embs.id', '=', 'so_emb_cutpanel_rcv_orders.so_emb_id');
   })
   ->where([['so_emb_cutpanel_rcv_orders.id', '=', $id]])
   ->get([
    'so_emb_cutpanel_rcv_orders.*',
    'so_embs.sales_order_no',
   ])
   ->first();


  $row['fromData'] = $soembcutpanelrcvinhorder;
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

 public function update(SoEmbCutpanelRcvInhOrderRequest $request, $id)
 {
  $soembcutpanelrcvinhorder = $this->soembcutpanelrcvinhorder->update(
   $id,
   [
    'so_emb_cutpanel_rcv_id' => $request->so_emb_cutpanel_rcv_id,
    'so_emb_id' => $request->so_emb_id,
    'remarks' => $request->remarks,
   ]
  );
  if ($soembcutpanelrcvinhorder) {
   return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
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
  if ($this->soembcutpanelrcvinhorder->delete($id)) {
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
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'so_embs.currency_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('so_embs.buyer_id', '=', 'buyers.id');
   })
   ->join('so_emb_pos', function ($join) {
    $join->on('so_emb_pos.so_emb_id', '=', 'so_embs.id');
   })
   ->when(request('company_id'), function ($q) {
    return $q->where('so_embs.company_id', '=', request('company_id'));
   })
   ->when(request('so_no'), function ($q) {
    return $q->where('so_embs.sales_order_no', '=', request('so_no'));
   })
   ->where([['so_embs.buyer_id', '=', $buyer_id]])
   //->where([['so_embs.company_id', '=', $buyer_id]])
   ->where([['so_embs.production_area_id', '=', $production_area_id]])
   ->whereNotNull('so_emb_pos.po_emb_service_id')
   ->get([
    'so_embs.*',
    'buyers.name as buyer_name',
    'companies.name as company_name',
    'currencies.code as currency'
   ]);
  echo json_encode($soemb);
 }
}
