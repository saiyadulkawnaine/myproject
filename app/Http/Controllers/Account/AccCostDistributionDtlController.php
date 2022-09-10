<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccCostDistributionDtlRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccCostDistributionDtlRequest;

class AccCostDistributionDtlController extends Controller
{

 private $acccostdistributiondtl;
 private $accchartctrlhead;
 private $profitcenter;
 private $salesorder;

 public function __construct(
  AccCostDistributionDtlRepository $acccostdistributiondtl,
  SalesOrderRepository $salesorder

 ) {
  $this->acccostdistributiondtl = $acccostdistributiondtl;
  $this->salesorder = $salesorder;

  $this->middleware('auth');
  // $this->middleware('permission:view.acccostdistributiondtls',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.acccostdistributiondtls', ['only' => ['store']]);
  // $this->middleware('permission:edit.acccostdistributiondtls',   ['only' => ['update']]);
  // $this->middleware('permission:delete.acccostdistributiondtls', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $othercosthead = array_prepend(config('bprs.othercosthead'), '-Select-', '');
  $acccostdistributiondtls = array();
  $rows = $this->acccostdistributiondtl
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'acc_cost_distribution_dtls.sale_order_id');
   })
   ->orderBy('acc_cost_distribution_dtls.id', 'desc')
   ->get([
    'acc_cost_distribution_dtls.*',
    'sales_orders.sale_order_no'
   ]);
  foreach ($rows as $row) {
   $acccostdistributiondtl['id'] = $row->id;
   $acccostdistributiondtl['sale_order_no'] = $row->sale_order_no;
   $acccostdistributiondtl['cost_type_id'] = $othercosthead[$row->cost_type_id];
   $acccostdistributiondtl['amount'] = $row->amount;
   $acccostdistributiondtl['remarks'] = $row->remarks;

   array_push($acccostdistributiondtls, $acccostdistributiondtl);
  }
  echo json_encode($acccostdistributiondtls);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $othercosthead = array_prepend(config('bprs.othercosthead'), '-Select-', '');
  return Template::loadView('Account.AccCostDistributionDtl', ['othercosthead' => $othercosthead]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(AccCostDistributionDtlRequest $request)
 {
  $acccostdistributiondtl = $this->acccostdistributiondtl->create($request->except(['id', 'sale_order_no']));
  if ($acccostdistributiondtl) {
   return response()->json(array('success' => true, 'id' =>  $acccostdistributiondtl->id, 'message' => 'Save Successfully'), 200);
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
  $acccostdistributiondtl = $this->acccostdistributiondtl
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'acc_cost_distribution_dtls.sale_order_id');
   })
   ->where([['acc_cost_distribution_dtls.id', '=', $id]])
   ->get([
    'acc_cost_distribution_dtls.*',
    'sales_orders.sale_order_no'
   ])
   ->first();

  $row['fromData'] = $acccostdistributiondtl;
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
 public function update(AccCostDistributionDtlRequest $request, $id)
 {
  $acccostdistributiondtl = $this->acccostdistributiondtl->update($id, $request->except(['id', 'sale_order_no']));
  if ($acccostdistributiondtl) {
   return response()->json(array('success' => true, 'id' =>  $id, 'message' => 'Update Successfully'), 200);
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
  if ($this->acccostdistributiondtl->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getSalesOrder()
 {
  $salesorder = $this->salesorder
   ->selectRaw('
            sales_orders.id as sale_order_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.produced_company_id,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            buyers.code as buyer_name,
            companies.name as company_id,
            produced_company.name as produced_company_name,
            countries.name as country_id,
            sum(sales_order_gmt_color_sizes.qty) as order_qty,
            avg(sales_order_gmt_color_sizes.rate) as order_rate,
            sum(sales_order_gmt_color_sizes.amount) as order_amount
         ')
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
   })
   ->join('countries', function ($join) {
    $join->on('countries.id', '=', 'sales_order_countries.country_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->leftJoin('companies as produced_company', function ($join) {
    $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
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
    'sales_orders.ship_date',
    'sales_orders.produced_company_id',
    'styles.style_ref',
    'styles.id',
    'jobs.job_no',
    'buyers.code',
    'companies.name',
    'produced_company.name',
    'countries.name'
   ])
   ->get()
   ->map(function ($salesorder) {
    return $salesorder;
   });
  echo json_encode($salesorder);
 }
}
