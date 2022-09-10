<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Sales\SalesOrderCloseRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Sales\ProjectionRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Http\Requests\SalesOrderCloseRequest;
use Illuminate\Support\Carbon;
use App\Library\Sms;

class SalesOrderCloseApprovalController extends Controller
{
 private $salesorderclose;
 private $salesorder;
 private $projection;
 private $job;
 private $salesordercountry;
 private $buyernature;
 private $style;

 public function __construct(
  SalesOrderCloseRepository $salesorderclose,
  SalesOrderRepository $salesorder,
  JobRepository $job,
  ProjectionRepository $projection,
  CompanyRepository $company,
  BuyerNatureRepository $buyernature,
  StyleRepository $style,
  SalesOrderCountryRepository $salesordercountry

 ) {
  $this->salesorderclose = $salesorderclose;
  $this->salesorder = $salesorder;
  $this->projection = $projection;
  $this->company = $company;
  $this->job = $job;
  $this->buyernature = $buyernature;
  $this->style = $style;
  $this->salesordercountry = $salesordercountry;
  $this->middleware('auth');
  // $this->middleware('permission:approve.salesordercloseapproval',   ['only' => ['approved', 'index','reportData']]);

 }
 public function index()
 {
  $job = array_prepend(array_pluck($this->job->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  return Template::loadView('Approval.SalesOrderCloseApproval', ['job' => $job, 'company' => $company]);
 }

 public function reportData()
 {
  $status = array_prepend(array_only(config('bprs.status'), [1, 0]), '-Select-', '');
  $rows = $this->salesorderclose
   ->selectRaw(
    '
    sales_order_closes.id,
    sales_order_closes.remarks,
    sales_order_closes.approved_at,
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
    $join->on('sales_orders.id', '=', 'sales_order_closes.sale_order_id');
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
    $join->on('users.id', '=', 'sales_order_closes.approved_by');
   })
   ->when(request('date_from'), function ($q) {
    return $q->where('sales_orders.ship_date', '>=', request('date_from'));
   })
   ->when(request('date_to'), function ($q) {
    return $q->where('sales_orders.ship_date', '<=', request('date_to'));
   })
   ->whereNull('sales_order_closes.approved_at')
   ->orderBy('sales_order_closes.id', 'desc')
   ->groupBy([
    'sales_order_closes.id',

    'sales_order_closes.remarks',
    'sales_order_closes.approved_at',
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'sales_orders.job_id',
    'jobs.job_no',
    'styles.style_ref',
    'buyers.name',
    'sales_orders.projection_id',
    'sales_orders.place_date',
    'sales_orders.ship_date',
    'sales_orders.receive_date',
    'sales_orders.file_no',
    'sales_orders.tna_to',
    'sales_orders.tna_from',
    'sales_orders.order_status',
    'users.name',
    'sales_orders.produced_company_id',
   ])
   ->get()
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

 public function approved(Request $request)
 {
  $id = request('id', 0);
  $master = $this->salesorderclose->find($id);
  $user = \Auth::user();
  $approved_at = date('Y-m-d h:i:s');
  $master->approved_by = $user->id;
  $master->approved_at = $approved_at;
  $master->closed_date = $approved_at;
  $master->timestamps = false;
  $salesorderclose = $master->save();
  if ($salesorderclose) {
   return response()->json(array('success' => true, 'message' => "Approve Successfully", 200));
  }
 }
}
