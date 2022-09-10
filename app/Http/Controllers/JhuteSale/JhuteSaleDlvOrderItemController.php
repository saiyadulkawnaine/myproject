<?php

namespace App\Http\Controllers\JhuteSale;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderItemRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Library\Template;
use App\Http\Requests\JhuteSale\JhuteSaleDlvOrderItemRequest;

class JhuteSaleDlvOrderItemController extends Controller
{
 private $jhutesaledlvorderitem;
 private $jhutesaledlvorder;
 private $uom;
 private $ctrlHead;


 public function __construct(
  JhuteSaleDlvOrderItemRepository $jhutesaledlvorderitem,
  JhuteSaleDlvOrderRepository $jhutesaledlvorder,
  UomRepository $uom,
  AccChartCtrlHeadRepository $ctrlHead
 ) {

  $this->jhutesaledlvorderitem = $jhutesaledlvorderitem;
  $this->jhutesaledlvorder = $jhutesaledlvorder;
  $this->uom = $uom;
  $this->ctrlHead = $ctrlHead;
  $this->middleware('auth');

  // $this->middleware('permission:view.jhutesaledlvorderitems',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.jhutesaledlvorderitems', ['only' => ['store']]);
  // $this->middleware('permission:edit.jhutesaledlvorderitems',   ['only' => ['update']]);
  // $this->middleware('permission:delete.jhutesaledlvorderitems', ['only' => ['destroy']]);
 }
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
  $ctrlHead = array_prepend(array_pluck($this->ctrlHead->where([['acc_chart_sub_group_id', '=', 64]])->get(), 'name', 'id'), '', '');
  $jhutesaledlvorderitems = array();
  $rows = $this->jhutesaledlvorderitem
   ->where([['jhute_sale_dlv_order_id', '=', request('jhute_sale_dlv_order_id', 0)]])
   ->orderBy('jhute_sale_dlv_order_items.id', 'desc')
   ->get();
  foreach ($rows as $row) {
   $jhutesaledlvorderitem['id'] = $row->id;
   $jhutesaledlvorderitem['acc_chart_ctrl_head_id'] = isset($ctrlHead[$row->acc_chart_ctrl_head_id]) ? $ctrlHead[$row->acc_chart_ctrl_head_id] : '';
   $jhutesaledlvorderitem['uom_id'] = isset($uom[$row->uom_id]) ? $uom[$row->uom_id] : '';
   $jhutesaledlvorderitem['qty'] = number_format($row->qty, 2);
   $jhutesaledlvorderitem['rate'] = number_format($row->rate, 4);
   $jhutesaledlvorderitem['amount'] = number_format($row->amount, 2);
   $jhutesaledlvorderitem['remarks'] = $row->remarks;
   array_push($jhutesaledlvorderitems, $jhutesaledlvorderitem);
  }
  echo json_encode($jhutesaledlvorderitems);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(JhuteSaleDlvOrderItemRequest $request)
 {
  $approved = $this->jhutesaledlvorder->find($request->jhute_sale_dlv_order_id);
  if ($approved->approved_at) {
   return response()->json(array('success' => false, 'message' => 'Approved. Update Not Allowed'), 200);
  }
  $jhutesaledlvorderitem = $this->jhutesaledlvorderitem->create($request->except(['id']));
  if ($jhutesaledlvorderitem) {
   return response()->json(array('success' => true, 'id' => $jhutesaledlvorderitem->id, 'message' => 'Save Successfully'), 200);
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
  $jhutesaledlvorderitem = $this->jhutesaledlvorderitem->find($id);
  $row['fromData'] = $jhutesaledlvorderitem;
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
 public function update(JhuteSaleDlvOrderItemRequest $request, $id)
 {
  $approved = $this->jhutesaledlvorder->find($request->jhute_sale_dlv_order_id);
  if ($approved->approved_at) {
   return response()->json(array('success' => false, 'message' => 'Approved. Update Not Allowed'), 200);
  }
  $jhutesaledlvorderitem = $this->jhutesaledlvorderitem->update($id, $request->except(['id']));
  if ($jhutesaledlvorderitem) {
   return response()->json(array('success' => true, 'id' => $id, 'message' => 'Updated Successfully'), 200);
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
  if ($this->jhutesaledlvorderitem->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Deleted Successfully'), 200);
  }
 }
}
