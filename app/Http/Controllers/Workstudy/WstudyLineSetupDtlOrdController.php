<?php

namespace App\Http\Controllers\Workstudy;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupDtlRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupDtlOrdRepository;
use App\Library\Template;
use App\Http\Requests\Workstudy\WstudyLineSetupDtlOrdRequest;
use Illuminate\Support\Carbon;

class WstudyLineSetupDtlOrdController extends Controller
{

 private $lineresourcesetup;
 private $setupdetail;
 private $setupdetailord;
 private $company;
 private $location;
 private $style;

 public function __construct(
  WstudyLineSetupDtlOrdRepository $setupdetailord,
  WstudyLineSetupDtlRepository $setupdetail,
  WstudyLineSetupRepository $lineresourcesetup,
  CompanyRepository $company,
  LocationRepository $location,
  StyleRepository $style,
  SalesOrderRepository $salesorder,
  JobRepository $job
 ) {
  $this->setupdetail = $setupdetail;
  $this->setupdetailord = $setupdetailord;
  $this->lineresourcesetup = $lineresourcesetup;
  $this->company = $company;
  $this->location = $location;
  $this->style = $style;
  $this->salesorder = $salesorder;
  $this->job = $job;

  $this->middleware('auth');
  $this->middleware('permission:view.wstudylinesetupdtls',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.wstudylinesetupdtls', ['only' => ['store']]);
  $this->middleware('permission:edit.wstudylinesetupdtls',   ['only' => ['update']]);
  $this->middleware('permission:delete.wstudylinesetupdtls', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {

  $setupdetails = array();
  $rows = $this->setupdetail
   ->join('wstudy_line_setup_dtl_ords', function ($join) {
    $join->on('wstudy_line_setup_dtl_ords.wstudy_line_setup_dtl_id', '=', 'wstudy_line_setup_dtls.id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'wstudy_line_setup_dtl_ords.sales_order_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'wstudy_line_setup_dtl_ords.style_gmt_id');
   })
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })

   ->where([['wstudy_line_setup_dtls.id', '=', request('wstudy_line_setup_dtl_id', 0)]])
   ->orderBy('wstudy_line_setup_dtl_ords.id', 'desc')
   ->get([
    'wstudy_line_setup_dtl_ords.*',
    'styles.id as style_id',
    'styles.style_ref',
    'sales_orders.sale_order_no',
    'sales_orders.ship_date',
    'buyers.name as buyer_name',
    'companies.code as company_name',
    'item_accounts.item_description'
   ]);
  foreach ($rows as $row) {
   $setupdetail['id'] = $row->id;
   $setupdetail['style_id'] = $row->style_id;
   $setupdetail['style_ref'] = $row->style_ref;
   $setupdetail['item_description'] = $row->item_description;
   $setupdetail['sale_order_no'] = $row->sale_order_no;
   $setupdetail['buyer_name'] = $row->buyer_name;
   $setupdetail['company_name'] = $row->company_name;
   $setupdetail['sewing_start_at'] = $row->sewing_start_at;
   $setupdetail['sewing_end_at'] = $row->sewing_end_at;
   $setupdetail['ship_date'] = date('d-M-Y', strtotime($row->ship_date));
   $setupdetail['qty'] = $row->qty;
   $setupdetail['remarks'] = $row->remarks;
   array_push($setupdetails, $setupdetail);
  }
  echo json_encode($setupdetails);
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
 public function store(WstudyLineSetupDtlOrdRequest $request)
 {

  $sewing_start_at = Carbon::parse($request->sewing_start_at);
  $sewing_end_at = Carbon::parse($request->sewing_end_at);
  $prod_hour = $sewing_start_at->diffInHours($sewing_end_at);
  $setupdetailord = $this->setupdetailord->create([
   'wstudy_line_setup_dtl_id' => $request->wstudy_line_setup_dtl_id,
   'style_gmt_id' => $request->style_gmt_id,
   'sales_order_id' => $request->sales_order_id,
   'sewing_start_at' => $request->sewing_start_at,
   'sewing_end_at' => $request->sewing_end_at,
   'qty' => $request->qty,
   'prod_hour' => $prod_hour,
   'remarks' => $request->remarks
  ]);
  if ($setupdetailord) {
   return response()->json(array('success' => true, 'id' => $setupdetailord->id, 'message' => 'Save Successfully'), 200);
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
  $setupdetail = $this->setupdetailord
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'wstudy_line_setup_dtl_ords.sales_order_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('style_gmts', function ($join) {
    $join->on('style_gmts.style_id', '=', 'styles.id');
    $join->on('style_gmts.id', '=', 'wstudy_line_setup_dtl_ords.style_gmt_id');
   })
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->where([['wstudy_line_setup_dtl_ords.id', '=', $id]])
   ->get([
    'styles.id as style_id',
    'sales_orders.sale_order_no',
    'item_accounts.item_description',
    'wstudy_line_setup_dtl_ords.*',
   ])
   ->first();
  $row['fromData'] = $setupdetail;
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
 public function update(WstudyLineSetupDtlOrdRequest $request, $id)
 {
  $sewing_start_at = Carbon::parse($request->sewing_start_at);
  $sewing_end_at = Carbon::parse($request->sewing_end_at);
  $prod_hour = $sewing_start_at->diffInHours($sewing_end_at);
  $setupdetailord = $this->setupdetailord->update($id, [
   'style_gmt_id' => $request->style_gmt_id,
   'sales_order_id' => $request->sales_order_id,
   'sewing_start_at' => $request->sewing_start_at,
   'sewing_end_at' => $request->sewing_end_at,
   'qty' => $request->qty,
   'prod_hour' => $prod_hour,
   'remarks' => $request->remarks
  ]);

  if ($setupdetailord) {
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


  if ($this->setupdetailord->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function lineSetupStyleRef()
 {
  $style = $this->style
   ->selectRaw('
            styles.id as style_id,
            styles.style_ref,
            style_gmts.id as style_gmt_id,
            style_gmts.item_account_id,
            item_accounts.item_description,
            buyers.name as buyer_name,
            jobs.job_no,
            sales_orders.id as sales_order_id,
            sales_orders.sale_order_no,    
            sales_orders.ship_date,
            sum(sales_order_gmt_color_sizes.qty) as qty
          ')
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.style_id', '=', 'styles.id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.job_id', '=', 'jobs.id');
   })
   ->join('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_gmt_color_sizes.sale_order_id');
   })
   ->join('style_gmt_color_sizes', function ($join) {
    $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id')
     ->whereNull('style_gmt_color_sizes.deleted_at');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmt_color_sizes.style_gmt_id', '=', 'style_gmts.id');
    $join->on('styles.id', '=', 'style_gmts.style_id');
   })
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->when(request('buyer_id'), function ($q) {
    return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
   })
   ->when(request('style_ref'), function ($q) {
    return $q->where('styles.style_ref', 'LIKE', "%" . request('style_ref', 0) . "%");
   })
   ->when(request('sales_orders'), function ($q) {
    return $q->where('sales_orders.sale_order_no', 'LIKE', "%" . request('sale_order_no', 0) . "%");
   })
   ->when(request('job_no'), function ($q) {
    return $q->where('jobs.job_no', 'LIKE', "%" . request('job_no', 0) . "%");
   })
   ->orderBy('styles.id', 'desc')
   ->orderBy('sales_orders.id', 'desc')
   ->groupBy([
    'styles.id',
    'styles.style_ref',
    'style_gmts.id',
    'style_gmts.item_account_id',
    'item_accounts.item_description',
    'buyers.name',
    'jobs.job_no',
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'sales_orders.ship_date'
   ])
   ->get();

  echo json_encode($style);
 }
}
