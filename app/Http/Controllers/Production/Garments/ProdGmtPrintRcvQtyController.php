<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtPrintRcvQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtPrintRcvQtyController extends Controller
{

 private $gmtprintrcvqty;
 private $prodgmtdlvinputorder;
 private $salesordergmtcolorsize;

 public function __construct(ProdGmtPrintRcvQtyRepository $gmtprintrcvqty, ProdGmtPrintRcvOrderRepository $prodgmtdlvinputorder, ProdGmtPrintRcvRepository $prodgmtdlvinput, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize)
 {
  $this->gmtprintrcvqty = $gmtprintrcvqty;
  $this->prodgmtdlvinputorder = $prodgmtdlvinputorder;
  $this->prodgmtdlvinput = $prodgmtdlvinput;
  $this->salesordergmtcolorsize = $salesordergmtcolorsize;
  $this->middleware('auth');
  /*$this->middleware('permission:view.prodgmtgmtprintrcvqtys',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtgmtprintrcvqtys', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtgmtprintrcvqtys',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtgmtprintrcvqtys', ['only' => ['destroy']]);*/
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  //
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
 public function store(ProdGmtPrintRcvQtyRequest $request)
 {
  foreach ($request->sales_order_gmt_color_size_id as $index => $sales_order_gmt_color_size_id) {
   if ($sales_order_gmt_color_size_id && $request->qty[$index]) {
    $gmtprintrcvqty = $this->gmtprintrcvqty->updateOrCreate(
     [
      'sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,
      'prod_gmt_print_rcv_order_id' => $request->prod_gmt_print_rcv_order_id
     ],
     [
      'qty' => $request->qty[$index],
      'reject_qty' => $request->reject_qty[$index],
     ]
    );
   }
  }


  if ($gmtprintrcvqty) {
   return response()->json(array('success' => true, 'id' =>  $gmtprintrcvqty->id, 'message' => 'Save Successfully'), 200);
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
  $gmtprintrcvqty = $this->gmtprintrcvqty->find($id);
  $row['fromData'] = $gmtprintrcvqty;
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
 public function update(ProdGmtPrintRcvQtyRequest $request, $id)
 {
  $gmtprintrcvqty = $this->gmtprintrcvqty->update($id, $request->except(['id']));
  if ($gmtprintrcvqty) {
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
  if ($this->gmtprintrcvqty->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
