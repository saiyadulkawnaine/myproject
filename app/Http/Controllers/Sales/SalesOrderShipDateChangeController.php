<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderShipDateChangeRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Sales\ProjectionRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\SalesOrderShipDateChangeRequest;
use Illuminate\Support\Carbon;

class SalesOrderShipDateChangeController extends Controller
{

 private $salesordershipdatechange;
 private $salesorder;
 private $projection;
 private $job;
 private $salesordercountry;

 public function __construct(
  SalesOrderShipDateChangeRepository $salesordershipdatechange,
  SalesOrderRepository $salesorder,
  JobRepository $job,
  ProjectionRepository $projection,
  CompanyRepository $company
 ) {
  $this->salesordershipdatechange = $salesordershipdatechange;
  $this->salesorder = $salesorder;
  $this->projection = $projection;
  $this->company = $company;
  $this->job = $job;


  $this->middleware('auth');
  $this->middleware('permission:view.salesordershipdatechanges',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.salesordershipdatechanges', ['only' => ['store']]);
  $this->middleware('permission:edit.salesordershipdatechanges',   ['only' => ['update']]);
  $this->middleware('permission:delete.salesordershipdatechanges', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $status = array_prepend(array_only(config('bprs.status'), [1, 0]), '-Select-', '');
  $rows = $this->salesordershipdatechange
   ->selectRaw(
    '
            sales_order_ship_date_changes.id,
            sales_order_ship_date_changes.ship_date,
            sales_order_ship_date_changes.old_ship_date,
            sales_order_ship_date_changes.remarks,
            sales_order_ship_date_changes.approved_at,
            sales_orders.id as sale_order_id,
			sales_orders.sale_order_no,
			sales_orders.job_id,
			jobs.job_no,
            styles.style_ref,
            buyers.name as buyer_name,
			sales_orders.projection_id,
			sales_orders.place_date,
			sales_orders.receive_date,
			sales_orders.file_no,
			sales_orders.tna_to,
			sales_orders.tna_from,
			sales_orders.produced_company_id,
			sales_orders.order_status,
            users.name as approved_by,
			sum(sales_order_gmt_color_sizes.qty) as qty,
            avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount'
   )
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_ship_date_changes.sale_order_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->join('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_gmt_color_sizes.sale_order_id');
   })
   ->leftJoin('users', function ($join) {
    $join->on('users.id', '=', 'sales_order_ship_date_changes.approved_by');
   })
   ->groupBy([
    'sales_order_ship_date_changes.id',
    'sales_order_ship_date_changes.ship_date',
    'sales_order_ship_date_changes.old_ship_date',
    'sales_order_ship_date_changes.remarks',
    'sales_order_ship_date_changes.approved_at',
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'sales_orders.job_id',
    'jobs.job_no',
    'styles.style_ref',
    'buyers.name',
    'sales_orders.projection_id',
    'sales_orders.place_date',
    'sales_orders.receive_date',
    'sales_orders.file_no',
    'sales_orders.tna_to',
    'sales_orders.tna_from',
    'sales_orders.order_status',
    'users.name',
    'sales_orders.produced_company_id',
   ])
   ->orderBy('sales_order_ship_date_changes.id', 'desc')
   ->get()

   ->take(500)
   ->map(function ($rows) use ($status) {
    $receive_date = Carbon::parse($rows->receive_date);
    $ship_date = Carbon::parse($rows->ship_date);
    $diff = $receive_date->diffInDays($ship_date);
    if ($diff > 1) {
     $diff .= " Days";
    } else {
     $diff .= " Day";
    }
    $rows->lead_time = $diff;
    $rows->order_status = isset($status[$rows->order_status]) ? $status[$rows->order_status] : '';
    $rows->old_ship_date = date("d-M-Y", strtotime($rows->old_ship_date));
    $rows->ship_date = date("d-M-Y", strtotime($rows->ship_date));
    $rows->approved_at = $rows->approved_at ? date("d-M-Y H:i:s", strtotime($rows->approved_at)) : '--';
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

  $job = array_prepend(array_pluck($this->job->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  return Template::loadView('Sales.SalesOrderShipDateChange', ['job' => $job, 'company' => $company]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SalesOrderShipDateChangeRequest $request)
 {
  $salesorder = $this->salesorder->find($request->sale_order_id);
  $request->request->add(['old_ship_date' => date('Y-m-d', strtotime($salesorder->ship_date))]);

  $unapproved = $this->salesordershipdatechange
   ->where([['sale_order_id', '=', $request->sale_order_id]])
   ->whereNull('approved_at')
   ->get();

  if ($unapproved->first()) {
   return response()->json(array('success' => false, 'id' =>  $salesorder->id, 'message' => 'Please Approved Current Ship Date'), 200);
  }

  $salesordershipdatechange = $this->salesordershipdatechange->create([
   'sale_order_id' => $request->sale_order_id,
   'old_ship_date' => $request->old_ship_date,
   'ship_date' => $request->ship_date,
   'remarks' => $request->remarks,
  ]);

  if ($salesordershipdatechange) {
   return response()->json(array('success' => true, 'id' =>  $salesordershipdatechange->id, 'message' => 'Save Successfully'), 200);
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
  $salesordershipdatechange = $this->salesordershipdatechange->find($id);
  $row['fromData'] = $salesordershipdatechange;
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

 public function update(SalesOrderShipDateChangeRequest $request, $id)
 {
  //return response()->json(array('success' => false,'message' => 'Update Not Possible'),200);
  $shipstatus = $this->salesordershipdatechange->find($id);

  if ($shipstatus) {
   return response()->json(array('success' => false, 'id' => $id, 'message' => 'Update Not Possible'), 200);
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
  $approved = $this->salesordershipdatechange->find($id);
  if ($approved->approved_by) {
   return response()->json(array('success' => false, 'message' => 'This Is Approved So Delete Not Possible'), 200);
  }
  $salesordershipdatechange = $this->salesordershipdatechange->findOrFail($id);
  if ($salesordershipdatechange->forceDelete()) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }


 public function getSalesOrder()
 {
  $status = array_prepend(config('bprs.status'), '-Select-', '');
  $rows = $this->salesorder
   ->selectRaw(
    'sales_orders.id,
    sales_orders.sale_order_no,
    sales_orders.job_id,
    jobs.job_no,
    sales_orders.projection_id,
    sales_orders.place_date,
    sales_orders.receive_date,
    sales_orders.ship_date,
    sales_orders.file_no,
    sales_orders.remarks,
    sales_orders.tna_to,
    sales_orders.tna_from,
    sales_orders.order_status,
    sales_orders.produced_company_id,
    styles.style_ref,
    sum(sales_order_gmt_color_sizes.qty) as qty,
    sum(sales_order_gmt_color_sizes.amount) as amount'
   )
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_gmt_color_sizes.sale_order_id');
   })
   ->when(request('style_ref'), function ($q) {
    return $q->where('styles.style_ref', 'LIKE', "%" . request('style_ref', 0) . "%");
   })
   ->when(request('job_no'), function ($q) {
    return $q->where('jobs.job_no', 'LIKE', "%" . request('job_no', 0) . "%");
   })
   ->when(request('sale_order_no'), function ($q) {
    return $q->where('sales_orders.sale_order_no', 'LIKE', "%" . request('sale_order_no', 0) . "%");
   })
   ->groupBy([
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'sales_orders.job_id',
    'jobs.job_no',
    'sales_orders.projection_id',
    'sales_orders.place_date',
    'sales_orders.receive_date',
    'sales_orders.ship_date',
    'sales_orders.file_no',
    'sales_orders.remarks',
    'sales_orders.tna_to',
    'sales_orders.tna_from',
    'sales_orders.order_status',
    'sales_orders.produced_company_id',
    'styles.style_ref',
   ])
   ->orderBy('sales_orders.id', 'desc')
   ->get()
   ->map(function ($rows) use ($status) {
    $rows->order_status = isset($status[$rows->order_status]) ? $status[$rows->order_status] : '';
    $rows->ship_date = date("d-M-Y", strtotime($rows->ship_date));
    return $rows;
   });
  echo json_encode($rows);
 }

 public function getAllChangedShipDate()
 {
  $status = array_prepend(array_only(config('bprs.status'), [1, 0]), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $rows = $this->salesordershipdatechange
   ->selectRaw(
    '
   sales_order_ship_date_changes.id,
   sales_order_ship_date_changes.ship_date,
   sales_order_ship_date_changes.remarks,
   sales_order_ship_date_changes.old_ship_date,
   sales_orders.id as sale_order_id,
			sales_orders.sale_order_no,
			sales_orders.job_id,
			jobs.job_no,
			sales_orders.projection_id,
			sales_orders.place_date,
			sales_orders.receive_date,
			sales_orders.file_no,
			sales_orders.tna_to,
			sales_orders.tna_from,
   sales_orders.order_status,
			sales_orders.produced_company_id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			sum(sales_order_gmt_color_sizes.amount) as amount'
   )
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_ship_date_changes.sale_order_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_gmt_color_sizes.sale_order_id');
   })
   ->when(request('date_from'), function ($q) {
    return $q->where('sales_order_ship_date_changes.ship_date', '>=', request('date_from', 0));
   })
   ->when(request('date_to'), function ($q) {
    return $q->where('sales_order_ship_date_changes.ship_date', '<=', request('date_to', 0));
   })
   ->groupBy([
    'sales_order_ship_date_changes.id',
    'sales_order_ship_date_changes.ship_date',
    'sales_order_ship_date_changes.remarks',
    'sales_order_ship_date_changes.old_ship_date',
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'sales_orders.job_id',
    'jobs.job_no',
    'sales_orders.projection_id',
    'sales_orders.place_date',
    'sales_orders.receive_date',
    'sales_orders.file_no',
    // 'sales_orders.remarks',
    'sales_orders.tna_to',
    'sales_orders.tna_from',
    'sales_orders.order_status',
    'sales_orders.produced_company_id',
   ])
   ->orderBy('sales_order_ship_date_changes.id', 'desc')
   ->get()
   ->map(function ($rows) use ($status, $company) {
    $rows->order_status = isset($status[$rows->order_status]) ? $status[$rows->order_status] : '';
    $rows->produced_company = $company[$rows->produced_company_id];
    $rows->ship_date = date("d-M-Y", strtotime($rows->ship_date));
    $rows->approved_at = $rows->approved_at ? date("d-M-Y H:i:s", strtotime($rows->approved_at)) : '--';
    return $rows;
   });
  echo json_encode($rows);
 }
}
