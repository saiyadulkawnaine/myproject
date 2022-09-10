<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;



use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtDlvPrintQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtDlvPrintQtyController extends Controller
{

 private $company;
 private $dlvprintqty;
 private $location;
 private $buyer;

 public function __construct(ProdGmtDlvPrintQtyRepository $dlvprintqty, ProdGmtDlvPrintOrderRepository $prodgmtdlvprintorder, ProdGmtDlvPrintRepository $prodgmtdlvprint, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier, CountryRepository $country, BuyerRepository $buyer, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize)
 {
  $this->dlvprintqty = $dlvprintqty;
  $this->prodgmtdlvprintorder = $prodgmtdlvprintorder;
  $this->prodgmtdlvprint = $prodgmtdlvprint;
  $this->company = $company;
  $this->buyer = $buyer;
  $this->location = $location;
  $this->supplier = $supplier;
  $this->country = $country;
  $this->salesordergmtcolorsize = $salesordergmtcolorsize;
  $this->middleware('auth');
  /*$this->middleware('permission:view.prodgmtdlvprintqtys',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtdlvprintqtys', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtdlvprintqtys',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtdlvprintqtys', ['only' => ['destroy']]);*/
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
  //
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProdGmtDlvPrintQtyRequest $request)
 {
  foreach ($request->sales_order_gmt_color_size_id as $index => $sales_order_gmt_color_size_id) {
   if ($sales_order_gmt_color_size_id && $request->qty[$index]) {
    $dlvprintqty = $this->dlvprintqty->updateOrCreate(
     [
      'sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id, 'prod_gmt_dlv_print_order_id' => $request->prod_gmt_dlv_print_order_id
     ],
     [
      'qty' => $request->qty[$index]
     ]
    );
   }
  }


  if ($dlvprintqty) {
   return response()->json(array('success' => true, 'id' =>  $dlvprintqty->id, 'message' => 'Save Successfully'), 200);
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
  $dlvprintqty = $this->dlvprintqty->find($id);
  $row['fromData'] = $dlvprintqty;
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
 public function update(ProdGmtDlvPrintQtyRequest $request, $id)
 {
  $dlvprintqty = $this->dlvprintqty->update($id, $request->except(['id']));
  if ($dlvprintqty) {
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
  if ($this->dlvprintqty->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
