<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Sales\ProjectionRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\SalesOrderRequest;
use Illuminate\Support\Carbon;

class SalesOrderController extends Controller
{

 private $salesorder;
 private $projection;
 private $job;

 public function __construct(SalesOrderRepository $salesorder, JobRepository $job, ProjectionRepository $projection, CompanyRepository $company)
 {
  $this->salesorder = $salesorder;
  $this->projection = $projection;
  $this->company = $company;
  $this->job = $job;
  $this->middleware('auth');
  $this->middleware('permission:view.salesorders',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.salesorders', ['only' => ['store']]);
  $this->middleware('permission:edit.salesorders',   ['only' => ['update']]);
  $this->middleware('permission:delete.salesorders', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  //$job=array_prepend(array_pluck($this->job->get(),'job_no','id'),'-Select-','');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $salesorders = array();
  $rows = $this->salesorder->getAll();
  foreach ($rows as $row) {
   $receive_date = Carbon::parse($row->receive_date);
   $ship_date = Carbon::parse($row->ship_date);
   $diff = $receive_date->diffInDays($ship_date);
   if ($diff > 1) {
    $diff .= " Days";
   } else {
    $diff .= " Day";
   }

   $salesorder['id'] =    $row->id;
   $salesorder['saleorderno'] =    $row->sale_order_no;
   $salesorder['placedate'] = date('d-M-Y', strtotime($row->place_date));
   $salesorder['receivedate'] = date('d-M-Y', strtotime($row->receive_date));
   $salesorder['shipdate'] = date('d-M-Y', strtotime($row->ship_date));
   $salesorder['fileno'] = $row->file_no;
   $salesorder['job'] =    $row->job_no;
   $salesorder['qty'] =    $row->qty;
   $salesorder['produced_company_id'] = $company[$row->produced_company_id];
   $salesorder['lead_time'] =    $diff;
   if ($row->qty && $row->amount) {
    $salesorder['rate'] =    $row->amount / $row->qty;
   } else {
    $salesorder['rate'] = '';
   }
   $salesorder['amount'] =    $row->amount;
   array_push($salesorders, $salesorder);
  }
  echo json_encode($salesorders);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $projection = array_prepend(array_pluck($this->projection->get(), 'proj_no', 'id'), '-Select-', '');
  $job = array_prepend(array_pluck($this->job->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  return Template::loadView('Sales.SalesOrder', ['job' => $job, 'projection' => $projection, 'company' => $company]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SalesOrderRequest $request)
 {
  $request->request->add(['org_ship_date' => $request->ship_date]);
  $salesorder = $this->salesorder->create($request->except(['id', 'job_no']));
  if ($salesorder) {
   return response()->json(array('success' => true, 'id' => $salesorder->id, 'message' => 'Save Successfully'), 200);
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
  // $salesorder = $this->salesorder->find($id);
  $salesorder = $this->salesorder->join('jobs', function ($join) {
   $join->on('sales_orders.job_id', '=', 'jobs.id');
  })
   ->where('sales_orders.id', '=', $id)
   ->get([
    'sales_orders.*',
    'jobs.job_no',
   ]);
  $row['fromData'] = $salesorder[0];
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
 public function update(SalesOrderRequest $request, $id)
 {
  if (\Auth::user()->level() >= 4) {
   $salesorder = $this->salesorder->update($id, $request->except(['id', 'job_no']));
  } else {
   $salesorder = $this->salesorder->update($id, $request->except(['id', 'job_no', 'ship_date', 'produced_company_id']));
  }
  if ($salesorder) {
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
  if ($this->salesorder->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
