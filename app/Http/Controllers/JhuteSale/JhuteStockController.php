<?php

namespace App\Http\Controllers\JhuteSale;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JhuteSale\JhuteStockRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Library\Template;
use App\Library\Sms;
use App\Http\Requests\JhuteSale\JhuteStockRequest;

class JhuteStockController extends Controller
{
 private $company;
 private $location;
 private $jhutestock;
 private $buyer;
 private $uom;
 private $ctrlHead;
 private $designation;

 public function __construct(
  CompanyRepository $company,
  LocationRepository $location,
  JhuteStockRepository $jhutestock,
  BuyerRepository $buyer,
  UomRepository $uom,
  AccChartCtrlHeadRepository $ctrlHead,
  DesignationRepository $designation
 ) {
  $this->company = $company;
  $this->location = $location;
  $this->jhutestock = $jhutestock;
  $this->buyer = $buyer;
  $this->uom = $uom;
  $this->ctrlHead = $ctrlHead;
  $this->designation = $designation;

  $this->middleware('auth');
  $this->middleware('permission:view.jhutestocks',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.jhutestocks', ['only' => ['store']]);
  $this->middleware('permission:edit.jhutestocks',   ['only' => ['update']]);
  $this->middleware('permission:delete.jhutestocks', ['only' => ['destroy']]);
 }
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');

  $rows = $this->jhutestock
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jhute_stocks.company_id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'jhute_stocks.location_id');
   })
   ->where([['jhute_stocks.stock_for', '=', 1]])
   ->orderBy('jhute_stocks.id', 'desc')
   ->get(
    [
     'jhute_stocks.*',
     'companies.name as company_name',
     'locations.name as location_name',
    ]
   )
   ->map(function ($rows) {
    $rows->stock_date = date('Y-m-d', strtotime($rows->stock_date));
    return $rows;
   });

  echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '', '');
  $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
  $ctrlHead = array_prepend(array_pluck($this->ctrlHead
   ->where([['acc_chart_sub_group_id', '=', 64]])
   ->where([['ctrlhead_type_id', '=', 1]])
   //->whereNotIn('ctrlhead_type_id',[1])
   ->get(), 'name', 'id'), '', '');
  $status = array_prepend(config('bprs.status'), '-Select-', '');

  return Template::loadView('JhuteSale.JhuteStock', ['company' => $company, 'location' => $location, 'buyer' => $buyer, 'uom' => $uom, 'ctrlHead' => $ctrlHead, 'status' => $status]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(JhuteStockRequest $request)
 {
  $max = $this->jhutestock
   ->where([['company_id', $request->company_id]])
   ->max('stock_no');
  $stock_no = $max + 1;
  $jhutestock = $this->jhutestock->create([
   'stock_no' => $stock_no,
   'company_id' => $request->company_id,
   'location_id' => $request->location_id,
   'stock_date' => $request->stock_date,
   'remarks' => $request->remarks,
   'stock_for' => 1,
  ]);
  if ($jhutestock) {
   return response()->json(array('success' => true, 'id' => $jhutestock->id, 'stock_no' => $stock_no, 'message' => 'Save Successfully'), 200);
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
  $jhutestock = $this->jhutestock->find($id);
  $row['fromData'] = $jhutestock;
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
 public function update(JhuteStockRequest $request, $id)
 {
  $jhutestock = $this->jhutestock->update($id, $request->except(['id']));

  if ($jhutestock) {
   return response()->json(array('success' => true, 'id' => $id, 'message' => "Update Successfully"), 200);
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
  if ($this->jhutestock->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Deleted Successfully'), 200);
  }
 }
}
