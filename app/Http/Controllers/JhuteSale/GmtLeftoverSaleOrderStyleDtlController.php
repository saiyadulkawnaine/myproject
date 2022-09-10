<?php

namespace App\Http\Controllers\JhuteSale;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderItemRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderQtyRepository;
use App\Library\Template;
use App\Http\Requests\JhuteSale\GmtLeftoverSaleOrderStyleDtlRequest;

class GmtLeftoverSaleOrderStyleDtlController extends Controller
{
 private $jhutesaledlvorderitem;
 private $jhutesaledlvorder;
 private $uom;
 private $ctrlHead;
 private $style;
 private $salesordergmtcolorsize;
 private $jhutesaledlvorderqty;

 public function __construct(
  JhuteSaleDlvOrderItemRepository $jhutesaledlvorderitem,
  JhuteSaleDlvOrderRepository $jhutesaledlvorder,
  UomRepository $uom,
  AccChartCtrlHeadRepository $ctrlHead,
  StyleRepository $style,
  SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
  JhuteSaleDlvOrderQtyRepository $jhutesaledlvorderqty
 ) {

  $this->jhutesaledlvorderitem = $jhutesaledlvorderitem;
  $this->jhutesaledlvorder = $jhutesaledlvorder;
  $this->uom = $uom;
  $this->ctrlHead = $ctrlHead;
  $this->style = $style;
  $this->salesordergmtcolorsize = $salesordergmtcolorsize;
  $this->jhutesaledlvorderqty = $jhutesaledlvorderqty;
  $this->middleware('auth');

  // $this->middleware('permission:view.gmtleftoversaledlvorderitems',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.gmtleftoversaledlvorderitems', ['only' => ['store']]);
  // $this->middleware('permission:edit.gmtleftoversaledlvorderitems',   ['only' => ['update']]);
  // $this->middleware('permission:delete.gmtleftoversaledlvorderitems', ['only' => ['destroy']]);
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
  $jhutesaledlvorderitems = array();
  $rows = $this->jhutesaledlvorderitem

   ->where([['jhute_sale_dlv_order_id', '=', request('jhute_sale_dlv_order_id', 0)]])
   ->orderBy('jhute_sale_dlv_order_items.id', 'desc')
   ->get();
  foreach ($rows as $row) {
   $jhutesaledlvorderitem['id'] = $row->id;
   $jhutesaledlvorderitem['acc_chart_ctrl_head_id'] = isset($ctrlHead[$row->acc_chart_ctrl_head_id]) ? $ctrlHead[$row->acc_chart_ctrl_head_id] : '';
   $jhutesaledlvorderitem['uom_id'] = isset($uom[$row->uom_id]) ? $uom[$row->uom_id] : '';
   $jhutesaledlvorderitem['qty'] = number_format($row->qty, 2);
   $jhutesaledlvorderitem['rate'] = number_format($row->rate, 4);
   $jhutesaledlvorderitem['amount'] = number_format($row->amount, 2);
   $jhutesaledlvorderitem['remarks'] = $row->remarks;
   array_push($jhutesaledlvorderitems, $jhutesaledlvorderitem);
  }
  echo json_encode($jhutesaledlvorderitems);
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
 public function store(GmtLeftoverSaleOrderStyleDtlRequest $request)
 {
  $jhutesaledlvorderitem = $this->jhutesaledlvorderitem->create([
   // 'dlv_no'=>$dlv_no,
   'jhute_sale_dlv_order_id' => $request->jhute_sale_dlv_order_id,
   'style_id' => $request->style_id,
   'acc_chart_ctrl_head_id' => $request->acc_chart_ctrl_head_id,
   'uom_id' => $request->uom_id,
   'qty' => $request->qty,
   'rate' => $request->rate,
   'amount' => $request->amount,
   'remarks' => $request->remarks,
  ]);
  if ($jhutesaledlvorderitem) {
   return response()->json(array('success' => true, 'id' => $jhutesaledlvorderitem->id, 'message' => 'Save Successfully'), 200);
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
  $jhutesaledlvorderitem = $this->jhutesaledlvorderitem
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jhute_sale_dlv_order_items.style_id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'styles.uom_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.style_id', '=', 'styles.id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->where([['jhute_sale_dlv_order_items.id', '=', $id]])
   ->get([
    'jhute_sale_dlv_order_items.*',
    'styles.style_ref',
    'styles.uom_id',
    'companies.name as company_name'
   ])
   ->first();

  $jhutesaledlvorderqty = $this->jhutesaledlvorderitem
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jhute_sale_dlv_order_items.style_id');
   })
   ->join('jobs', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
   })
   ->join('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
   })
   ->join('style_gmt_color_sizes', function ($join) {
    $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'style_colors.color_id');
   })
   ->join('style_sizes', function ($join) {
    $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
   })
   ->join('sizes', function ($join) {
    $join->on('sizes.id', '=', 'style_sizes.size_id');
   })
   ->leftJoin('jhute_sale_dlv_order_qties', function ($join) {
    $join->on('jhute_sale_dlv_order_qties.jhute_sale_dlv_order_item_id', '=', 'jhute_sale_dlv_order_items.id')->whereNull('jhute_sale_dlv_order_qties.deleted_at');
    $join->on('jhute_sale_dlv_order_qties.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
   })
   ->where([['sales_orders.order_status', '=', 4]])
   ->where([['sales_order_gmt_color_sizes.qty', '>', 0]])
   ->orderBy('style_gmt_color_sizes.style_gmt_id')
   ->orderBy('style_colors.sort_id')
   ->orderBy('style_sizes.sort_id')
   ->where([['jhute_sale_dlv_order_items.id', '=', $id]])
   ->get([
    'sizes.name as size_name',
    'sizes.code as size_code',
    'colors.name as color_name',
    'colors.code as color_code',
    'style_sizes.sort_id as size_sort_id',
    'style_colors.sort_id as color_sort_id',
    'sales_order_gmt_color_sizes.qty',
    'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id',
    'sales_order_gmt_color_sizes.qty as order_qty',
    'item_accounts.item_description',
    'sales_orders.sale_order_no',
    'jhute_sale_dlv_order_items.id as jhute_sale_dlv_order_item_id',
    'jhute_sale_dlv_order_qties.id as jhute_sale_dlv_order_qty_id',
    'jhute_sale_dlv_order_qties.qty',
    'jhute_sale_dlv_order_qties.rate',
    'jhute_sale_dlv_order_qties.amount',
   ])
   ->map(function ($jhutesaledlvorderqty) {   
    return $jhutesaledlvorderqty;
   });

  $saved = $jhutesaledlvorderqty->filter(function ($value) {
   if ($value->jhute_sale_dlv_order_qty_id) {
    return $value;
   }
  });
  $new = $jhutesaledlvorderqty->filter(function ($value) {
   if (!$value->jhute_sale_dlv_order_qty_id) {
    return $value;
   }
  });


  $row['fromData'] = $jhutesaledlvorderitem;
  $dropdown['gmtleftoversalecosi'] = "'" . Template::loadView('JhuteSale.GmtLeftoverSaleOrderQtyMatrix', ['colorsizes' => $new, 'saved' => $saved]) . "'";
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
 public function update(GmtLeftoverSaleOrderStyleDtlRequest $request, $id)
 {
  $jhutesaledlvorderitem = $this->jhutesaledlvorderitem->update($id, [
   'jhute_sale_dlv_order_id' => $request->jhute_sale_dlv_order_id,
   'style_id' => $request->style_id,
   'acc_chart_ctrl_head_id' => $request->acc_chart_ctrl_head_id,
   'uom_id' => $request->uom_id,
   'qty' => $request->qty,
   'rate' => $request->rate,
   'amount' => $request->amount,
   'remarks' => $request->remarks,
  ]);
  if ($jhutesaledlvorderitem) {
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
  if ($this->jhutesaledlvorderitem->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Deleted Successfully'), 200);
  }
 }

 public function getGmtLeftoverStyle()
 {
  $rows = $this->style
   ->leftJoin('buyers', function ($join) {
    $join->on('styles.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('buyers as buyingagents', function ($join) {
    $join->on('styles.buying_agent_id', '=', 'buyingagents.id');
   })
   ->leftJoin('uoms', function ($join) {
    $join->on('styles.uom_id', '=', 'uoms.id');
   })
   ->leftJoin('seasons', function ($join) {
    $join->on('styles.season_id', '=', 'seasons.id');
   })
   ->leftJoin('teams', function ($join) {
    $join->on('styles.team_id', '=', 'teams.id');
   })
   ->leftJoin('teammembers', function ($join) {
    $join->on('styles.teammember_id', '=', 'teammembers.id');
   })
   ->leftJoin('users', function ($join) {
    $join->on('users.id', '=', 'teammembers.user_id');
   })
   ->leftJoin('productdepartments', function ($join) {
    $join->on('productdepartments.id', '=', 'styles.productdepartment_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.style_id', '=', 'styles.id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->when(request('buyer_id'), function ($q) {
    return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
   })
   ->when(request('style_ref'), function ($q) {
    return $q->where('styles.style_ref', 'like', '%' . request('style_ref', 0) . '%');
   })
   ->when(request('style_description'), function ($q) {
    return $q->where('styles.style_description', 'like', '%' . request('style_description', 0) . '%');
   })
   ->orderBy('styles.id', 'desc')
   ->get([
    'styles.*',
    'buyers.code as buyer_name',
    'uoms.name as uom_name',
    'seasons.name as season_name',
    'teams.name as team_name',
    'users.name as team_member_name',
    'productdepartments.department_name',
    'buyingagents.name as buying_agent_id',
    'companies.name as company_name'
   ])
   ->map(function ($rows) {
    $rows->receivedate = date("d-M-Y", strtotime($rows->receive_date));
    $rows->buyer = $rows->buyer_name;
    $rows->deptcategory = $rows->dept_category_name;
    $rows->season = $rows->season_name;
    $rows->uom = $rows->uom_name;
    $rows->team = $rows->team_name;
    $rows->teammember = $rows->team_member_name;
    $rows->productdepartment = $rows->department_name;
    $rows->companies = $rows->company_name;
    return $rows;
   });

  echo json_encode($rows);
 }
}
