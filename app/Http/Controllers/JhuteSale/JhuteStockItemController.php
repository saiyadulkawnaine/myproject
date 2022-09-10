<?php

namespace App\Http\Controllers\JhuteSale;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JhuteSale\JhuteStockItemRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\JhuteSale\JhuteStockRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Library\Template;
use App\Http\Requests\JhuteSale\JhuteStockItemRequest;

class JhuteStockItemController extends Controller
{
 private $jhutestockitem;
 private $jhutestock;
 private $uom;
 private $ctrlHead;


 public function __construct(
  JhuteStockItemRepository $jhutestockitem,
  JhuteStockRepository $jhutestock,
  UomRepository $uom,
  AccChartCtrlHeadRepository $ctrlHead
 ) {

  $this->jhutestockitem = $jhutestockitem;
  $this->jhutestock = $jhutestock;
  $this->uom = $uom;
  $this->ctrlHead = $ctrlHead;
  $this->middleware('auth');

  $this->middleware('permission:view.jhutestockitems',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.jhutestockitems', ['only' => ['store']]);
  $this->middleware('permission:edit.jhutestockitems',   ['only' => ['update']]);
  $this->middleware('permission:delete.jhutestockitems', ['only' => ['destroy']]);
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
  $jhutestockitems = array();
  $rows = $this->jhutestockitem
   ->where([['jhute_stock_id', '=', request('jhute_stock_id', 0)]])
   ->orderBy('jhute_stock_items.id', 'desc')
   ->get();
  foreach ($rows as $row) {
   $jhutestockitem['id'] = $row->id;
   $jhutestockitem['acc_chart_ctrl_head_id'] = isset($ctrlHead[$row->acc_chart_ctrl_head_id]) ? $ctrlHead[$row->acc_chart_ctrl_head_id] : '';
   $jhutestockitem['uom_id'] = isset($uom[$row->uom_id]) ? $uom[$row->uom_id] : '';
   $jhutestockitem['qty'] = number_format($row->qty, 2);
   $jhutestockitem['rate'] = number_format($row->rate, 4);
   $jhutestockitem['amount'] = number_format($row->amount, 2);
   $jhutestockitem['remarks'] = $row->remarks;
   array_push($jhutestockitems, $jhutestockitem);
  }
  echo json_encode($jhutestockitems);
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
 public function store(JhuteStockItemRequest $request)
 {
  $approved = $this->jhutestock->find($request->jhute_stock_id);
  if ($approved->approved_at) {
   return response()->json(array('success' => false, 'message' => 'Approved. Update Not Allowed'), 200);
  }
  $jhutestockitem = $this->jhutestockitem->create($request->except(['id']));
  if ($jhutestockitem) {
   return response()->json(array('success' => true, 'id' => $jhutestockitem->id, 'message' => 'Save Successfully'), 200);
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
  $jhutestockitem = $this->jhutestockitem->find($id);
  $row['fromData'] = $jhutestockitem;
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
 public function update(JhuteStockItemRequest $request, $id)
 {
  $approved = $this->jhutestock->find($request->jhute_stock_id);
  if ($approved->approved_at) {
   return response()->json(array('success' => false, 'message' => 'Approved. Update Not Allowed'), 200);
  }
  $jhutestockitem = $this->jhutestockitem->update($id, $request->except(['id']));
  if ($jhutestockitem) {
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
  if ($this->jhutestockitem->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Deleted Successfully'), 200);
  }
 }
}
