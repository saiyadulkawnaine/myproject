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
use App\Library\Template;
use App\Http\Requests\Workstudy\WstudyLineSetupDtlRequest;

class WstudyLineSetupDtlController extends Controller
{

  private $lineresourcesetup;
  private $setupdetail;
  private $company;
  private $location;
  private $style;

  public function __construct(WstudyLineSetupDtlRepository $setupdetail, WstudyLineSetupRepository $lineresourcesetup, CompanyRepository $company, LocationRepository $location, StyleRepository $style, SalesOrderRepository $salesorder, JobRepository $job)
  {
    $this->setupdetail = $setupdetail;
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
      ->leftJoin('wstudy_line_setups', function ($join) {
        $join->on('wstudy_line_setups.id', '=', 'wstudy_line_setup_dtls.wstudy_line_setup_id');
      })
      ->leftJoin('styles', function ($join) {
        $join->on('styles.id', '=', 'wstudy_line_setup_dtls.style_id');
      })
      ->when(request('line_chief'), function ($q) {
        return $q->where('wstudy_line_setup_dtls.line_chief', 'LIKE', "%" . request('line_chief', 0) . "%");
      })
      ->where([['wstudy_line_setup_id', '=', request('wstudy_line_setup_id', 0)]])
      ->orderBy('wstudy_line_setup_dtls.from_date', 'desc')
      ->get([
        'wstudy_line_setup_dtls.*',
        'styles.id as style_id',
        'styles.style_ref'
      ]);
    foreach ($rows as $row) {
      $setupdetail['id'] = $row->id;
      $setupdetail['style_id'] = $row->style_id;
      $setupdetail['style_ref'] = $row->style_ref;
      $setupdetail['from_date'] = date('Y-m-d', strtotime($row->from_date));
      $setupdetail['to_date'] = date('Y-m-d', strtotime($row->to_date));
      $setupdetail['operator'] = $row->operator;
      $setupdetail['helper'] = $row->helper;
      $setupdetail['working_hour'] = $row->working_hour;
      $setupdetail['overtime_hour'] = $row->overtime_hour;
      $setupdetail['line_chief'] = $row->line_chief;
      $setupdetail['total_mnt'] = $row->total_mnt;
      $setupdetail['sewing_start_at'] = $row->sewing_start_at;
      $setupdetail['sewing_end_at'] = $row->sewing_end_at;
      $setupdetail['lunch_start_at'] = $row->lunch_start_at;
      $setupdetail['lunch_end_at'] = $row->lunch_end_at;
      $setupdetail['tiffin_start_at'] = $row->tiffin_start_at;
      $setupdetail['tiffin_end_at'] = $row->tiffin_end_at;
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
  public function store(WstudyLineSetupDtlRequest $request)
  {
    $detail = $this->setupdetail
      ->where([['wstudy_line_setup_id', '=', $request->wstudy_line_setup_id]])
      ->when(request('from_date'), function ($q) {
        return $q->where('wstudy_line_setup_dtls.from_date', '>=', request('from_date', 0));
      })
      ->when(request('from_date'), function ($q) {
        return $q->where('wstudy_line_setup_dtls.to_date', '<=', request('from_date', 0));
      })
      ->get()
      ->first();

    if ($detail) {
      return response()->json(array('success' => false, 'id' => '', 'message' => 'This Date range found for this master id'), 200);
    }

    $setupdetail = $this->setupdetail->create([
      'from_date' => $request->from_date,
      'to_date' => $request->from_date,
      'wstudy_line_setup_id' => $request->wstudy_line_setup_id,
      'style_id' => $request->style_id,
      'operator' => $request->operator,
      'helper' => $request->helper,
      'line_chief' => $request->line_chief,
      'working_hour' => $request->working_hour,
      'overtime_hour' => $request->overtime_hour,
      'total_mnt' => $request->total_mnt,
      'target_per_hour' => $request->target_per_hour,
      'sewing_start_at' => $request->sewing_start_at,
      'sewing_end_at' => $request->sewing_end_at,
      'lunch_start_at' => $request->lunch_start_at,
      'lunch_end_at' => $request->lunch_end_at,
      'tiffin_start_at' => $request->tiffin_start_at,
      'tiffin_end_at' => $request->tiffin_end_at,
      'remarks' => $request->remarks
    ]);
    if ($setupdetail) {
      return response()->json(array('success' => true, 'id' => $setupdetail->id, 'message' => 'Save Successfully'), 200);
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
    $setupdetail = $this->setupdetail
      ->join('wstudy_line_setups', function ($join) {
        $join->on('wstudy_line_setups.id', '=', 'wstudy_line_setup_dtls.wstudy_line_setup_id');
      })
      ->leftJoin('styles', function ($join) {
        $join->on('styles.id', '=', 'wstudy_line_setup_dtls.style_id');
      })
      ->where([['wstudy_line_setup_dtls.id', '=', $id]])
      ->get([
        'styles.id as style_id',
        'styles.style_ref',
        'wstudy_line_setup_dtls.*',
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
  public function update(WstudyLineSetupDtlRequest $request, $id)
  {
    $detail = $this->setupdetail
      ->when(request('from_date'), function ($q) {
        return $q->where('wstudy_line_setup_dtls.from_date', '>=', request('from_date', 0));
      })
      ->when(request('from_date'), function ($q) {
        return $q->where('wstudy_line_setup_dtls.to_date', '<=', request('from_date', 0));
      })
      ->where([['wstudy_line_setup_id', '=', $request->wstudy_line_setup_id]])
      ->where([['id', '!=', $id]])
      ->get()
      ->first();
    if ($detail) {
      return response()->json(array('success' => false, 'id' => '', 'message' => 'This Date range found for this master id'), 200);
    }

    $setupdetail = $this->setupdetail->update($id, [
      'from_date' => $request->from_date,
      'to_date' => $request->from_date,
      'wstudy_line_setup_id' => $request->wstudy_line_setup_id,
      'style_id' => $request->style_id,
      'operator' => $request->operator,
      'helper' => $request->helper,
      'line_chief' => $request->line_chief,
      'working_hour' => $request->working_hour,
      'overtime_hour' => $request->overtime_hour,
      'total_mnt' => $request->total_mnt,
      'target_per_hour' => $request->target_per_hour,
      'sewing_start_at' => $request->sewing_start_at,
      'sewing_end_at' => $request->sewing_end_at,
      'lunch_start_at' => $request->lunch_start_at,
      'lunch_end_at' => $request->lunch_end_at,
      'tiffin_start_at' => $request->tiffin_start_at,
      'tiffin_end_at' => $request->tiffin_end_at,
      'remarks' => $request->remarks
    ]);
    if ($setupdetail) {
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
    if ($this->setupdetail->delete($id)) {
      return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
    }
  }

  public function lineSetupStyleRef()
  {
    $style = $this->style
      ->selectRaw('
            styles.id,
            styles.style_ref,
            jobs.job_no,
            sales_orders.sale_order_no,    
            sales_orders.ship_date,
            style_gmts.item_account_id,
            item_accounts.item_description,
            
            colors.name as color_name,
            colors.code as color_code,
            style_sizes.id as style_size_id,
            sizes.name,
            sizes.code
        ')

      ->leftJoin('buyers', function ($join) {
        $join->on('buyers.id', '=', 'styles.buyer_id');
      })
      ->join('jobs', function ($join) {
        $join->on('jobs.style_id', '=', 'styles.id');
      })
      ->join('sales_orders', function ($join) {
        $join->on('sales_orders.job_id', '=', 'jobs.id');
      })
      ->join('sales_order_gmt_color_sizes', function ($join) {
        $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
      })
      ->join('style_gmts', function ($join) {
        $join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
      })
      ->join('item_accounts', function ($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('style_gmt_color_sizes', function ($join) {
        $join->on('style_gmt_color_sizes.style_id', '=', 'styles.id');

        $join->on('style_gmt_color_sizes.style_gmt_id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
        $join->on('style_gmt_color_sizes.style_color_id', '=', 'sales_order_gmt_color_sizes.style_color_id');
        $join->on('style_gmt_color_sizes.style_size_id', '=', 'sales_order_gmt_color_sizes.style_size_id');
      })
      ->join('style_colors', function ($join) {
        $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
      })
      ->join('colors', function ($join) {
        $join->on('style_colors.color_id', '=', 'colors.id');
      })
      ->join('style_sizes', function ($join) {
        $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
      })
      ->join('sizes', function ($join) {
        $join->on('style_sizes.size_id', '=', 'sizes.id');
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
      ->get([
        'buyers.name as buyer_id',
        'styles.id as style_id',
        'styles.style_ref',
        'style.buyer_id',
        'jobs.job_no',
        'style_gmts.item_account_id',
        'item_accounts.item_description as item_account_id',

        'style_gmt_color_sizes.id as style_gmt_color_size_id',
        'style_gmt_color_sizes.style_gmt_id',
        'style_colors.id as style_color_id',
        'colors.name as color_name',
        'colors.code as color_code',
        'style_sizes.id as style_size_id',
        'sizes.name',
        'sizes.code'
      ]);

    echo json_encode($style);
  }

  public function getChiefName(Request $request)
  {
    return $this->setupdetail
      ->where([['line_chief', 'LIKE', '%' . $request->q . '%']])
      ->orderBy('line_chief', 'asc')
      ->get(['wstudy_line_setup_dtls.line_chief as name']);
  }
}
