<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlOrdRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintMcDtlOrdRequest;
use Illuminate\Support\Carbon;

class SoEmbPrintMcDtlOrdController extends Controller
{

 private $soembprintmc;
 private $soembprintmcdtl;
 private $soembprintmcdtlord;
 private $company;
 private $location;
 private $style;
 private $soemb;

 public function __construct(
  SoEmbPrintMcDtlOrdRepository $soembprintmcdtlord,
  SoEmbPrintMcDtlRepository $soembprintmcdtl,
  SoEmbPrintMcRepository $soembprintmc,
  CompanyRepository $company,
  LocationRepository $location,
  StyleRepository $style,
  SalesOrderRepository $salesorder,
  JobRepository $job,
  SoEmbRepository $soemb
 ) {
  $this->soembprintmcdtl = $soembprintmcdtl;
  $this->soembprintmcdtlord = $soembprintmcdtlord;
  $this->soembprintmc = $soembprintmc;
  $this->company = $company;
  $this->location = $location;
  $this->style = $style;
  $this->salesorder = $salesorder;
  $this->job = $job;
  $this->soemb = $soemb;

  $this->middleware('auth');
  // $this->middleware('permission:view.soembprintmcdtlords',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.soembprintmcdtlords', ['only' => ['store']]);
  // $this->middleware('permission:edit.soembprintmcdtlords',   ['only' => ['update']]);
  // $this->middleware('permission:delete.soembprintmcdtlords', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $rows = $this->soembprintmcdtlord
   ->join('so_emb_print_mc_dtls', function ($join) {
    $join->on('so_emb_print_mc_dtls.id', '=', 'so_emb_print_mc_dtl_ords.so_emb_print_mc_dtl_id');
   })
   ->join('so_emb_refs', function ($join) {
    $join->on('so_emb_refs.id', '=', 'so_emb_print_mc_dtl_ords.so_emb_ref_id');
   })
   ->join('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'so_emb_print_mc_dtl_ords.gmtspart_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'so_emb_print_mc_dtl_ords.item_account_id');
   })
   ->leftJoin('so_emb_items', function ($join) {
    $join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
   })
   ->leftJoin('so_emb_po_items', function ($join) {
    $join->on('so_emb_po_items.so_emb_ref_id', '=', 'so_emb_refs.id');
   })
   ->leftJoin('po_emb_service_item_qties', function ($join) {
    $join->on('po_emb_service_item_qties.id', '=', 'so_emb_po_items.po_emb_service_item_qty_id');
   })
   ->leftJoin('budget_emb_cons', function ($join) {
    $join->on('budget_emb_cons.id', '=', 'po_emb_service_item_qties.budget_emb_con_id')->whereNull('budget_emb_cons.deleted_at');
   })
   ->leftJoin('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_order_gmt_color_sizes.id', '=', 'budget_emb_cons.sales_order_gmt_color_size_id');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_gmt_color_sizes.sale_order_id');
   })

   ->where([['so_emb_print_mc_dtl_ords.so_emb_print_mc_dtl_id', '=', request('so_emb_print_mc_dtl_id', 0)]])
   ->orderBy('so_emb_print_mc_dtl_ords.id', 'DESC')
   ->selectRaw('
   so_emb_print_mc_dtl_ords.*,
   gmtsparts.name as gmtspart,
   item_accounts.item_description as item_desc,
   so_emb_items.gmt_sale_order_no as sale_order_no,
   sales_orders.sale_order_no as c_sale_order_no
   ')
   ->get()
   ->map(function ($rows) {
    $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->c_sale_order_no;
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
  //
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SoEmbPrintMcDtlOrdRequest $request)
 {
  $printing_start_at = Carbon::parse($request->printing_start_at);
  $printing_end_at = Carbon::parse($request->printing_end_at);
  $prod_hour = $printing_start_at->diffInHours($printing_end_at);
  $soembprintmcdtlord = $this->soembprintmcdtlord->create([
   'so_emb_print_mc_dtl_id' => $request->so_emb_print_mc_dtl_id,
   'gmtspart_id' => $request->gmtspart_id,
   'item_account_id' => $request->item_account_id,
   'so_emb_ref_id' => $request->so_emb_ref_id,
   'printing_start_at' => $request->printing_start_at,
   'printing_end_at' => $request->printing_end_at,
   'qty' => $request->qty,
   'prod_hour' => $prod_hour,
   'remarks' => $request->remarks
  ]);
  if ($soembprintmcdtlord) {
   return response()->json(array('success' => true, 'id' => $soembprintmcdtlord->id, 'message' => 'Save Successfully'), 200);
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
  $soembprintmcdtlord = $this->soembprintmcdtlord
   ->join('so_emb_print_mc_dtls', function ($join) {
    $join->on('so_emb_print_mc_dtls.id', '=', 'so_emb_print_mc_dtl_ords.so_emb_print_mc_dtl_id');
   })
   ->join('so_emb_refs', function ($join) {
    $join->on('so_emb_refs.id', '=', 'so_emb_print_mc_dtl_ords.so_emb_ref_id');
   })
   ->join('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'so_emb_print_mc_dtl_ords.gmtspart_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'so_emb_print_mc_dtl_ords.item_account_id');
   })
   ->leftJoin('so_emb_items', function ($join) {
    $join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
   })
   ->leftJoin('so_emb_po_items', function ($join) {
    $join->on('so_emb_po_items.so_emb_ref_id', '=', 'so_emb_refs.id');
   })
   ->leftJoin('po_emb_service_item_qties', function ($join) {
    $join->on('po_emb_service_item_qties.id', '=', 'so_emb_po_items.po_emb_service_item_qty_id');
   })
   ->leftJoin('budget_emb_cons', function ($join) {
    $join->on('budget_emb_cons.id', '=', 'po_emb_service_item_qties.budget_emb_con_id')->whereNull('budget_emb_cons.deleted_at');
   })
   ->leftJoin('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_order_gmt_color_sizes.id', '=', 'budget_emb_cons.sales_order_gmt_color_size_id');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_gmt_color_sizes.sale_order_id');
   })

   ->where([['so_emb_print_mc_dtl_ords.id', '=', $id]])
   ->selectRaw('
   so_emb_print_mc_dtl_ords.*,
   gmtsparts.name as gmtspart,
   item_accounts.item_description as item_desc,
   so_emb_items.gmt_sale_order_no as sale_order_no,
   sales_orders.sale_order_no as c_sale_order_no
   ')
   ->get()
   ->map(function ($soembprintmcdtlord) {
    $soembprintmcdtlord->sale_order_no = $soembprintmcdtlord->sale_order_no ? $soembprintmcdtlord->sale_order_no : $soembprintmcdtlord->c_sale_order_no;
    return $soembprintmcdtlord;
   })
   ->first();
  $row['fromData'] = $soembprintmcdtlord;
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
 public function update(SoEmbPrintMcDtlOrdRequest $request, $id)
 {
  $printing_start_at = Carbon::parse($request->printing_start_at);
  $printing_end_at = Carbon::parse($request->printing_end_at);
  $prod_hour = $printing_start_at->diffInHours($printing_end_at);
  $soembprintmcdtlord = $this->soembprintmcdtlord->update($id, [
   'gmtspart_id' => $request->gmtspart_id,
   'item_account_id' => $request->item_account_id,
   'so_emb_ref_id' => $request->so_emb_ref_id,
   'printing_start_at' => $request->printing_start_at,
   'printing_end_at' => $request->printing_end_at,
   'qty' => $request->qty,
   'prod_hour' => $prod_hour,
   'remarks' => $request->remarks
  ]);

  if ($soembprintmcdtlord) {
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
  if ($this->soembprintmcdtlord->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getSalesOrder()
 {
  $embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');
  $rows = $this->soemb
   ->join('so_emb_refs', function ($join) {
    $join->on('so_emb_refs.so_emb_id', '=', 'so_embs.id');
   })
   ->leftJoin('so_emb_items', function ($join) {
    $join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
   })
   ->leftJoin('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'so_emb_items.gmtspart_id');
   })
   ->leftJoin('embelishments', function ($join) {
    $join->on('embelishments.id', '=', 'so_emb_items.embelishment_id');
   })
   ->leftJoin('embelishment_types', function ($join) {
    $join->on('embelishment_types.id', '=', 'so_emb_items.embelishment_type_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'so_emb_items.gmt_buyer');
   })
   ->leftJoin('uoms', function ($join) {
    $join->on('uoms.id', '=', 'so_emb_items.uom_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'so_emb_items.color_id');
   })
   ->leftJoin('sizes', function ($join) {
    $join->on('sizes.id', '=', 'so_emb_items.size_id');
   })
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'so_emb_items.item_account_id');
   })

   ->leftJoin('so_emb_pos', function ($join) {
    $join->on('so_emb_pos.so_emb_id', '=', 'so_embs.id');
   })
   ->leftJoin('so_emb_po_items', function ($join) {
    $join->on('so_emb_po_items.so_emb_ref_id', '=', 'so_emb_refs.id');
   })
   ->leftJoin('po_emb_service_item_qties', function ($join) {
    $join->on('po_emb_service_item_qties.id', '=', 'so_emb_po_items.po_emb_service_item_qty_id');
   })
   ->leftJoin('po_emb_service_items', function ($join) {
    $join->on('po_emb_service_items.id', '=', 'po_emb_service_item_qties.po_emb_service_item_id')->whereNull('po_emb_service_items.deleted_at');
   })
   ->leftJoin('po_emb_services', function ($join) {
    $join->on('po_emb_services.id', '=', 'po_emb_service_items.po_emb_service_id');
   })
   ->leftJoin('budget_embs', function ($join) {
    $join->on('budget_embs.id', '=', 'po_emb_service_items.budget_emb_id');
   })
   ->leftJoin('style_embelishments', function ($join) {
    $join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
   })
   ->leftJoin('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_embelishments.style_gmt_id');
   })
   ->leftJoin('item_accounts as style_gmt_items', function ($join) {
    $join->on('style_gmt_items.id', '=', 'style_gmts.item_account_id');
   })
   ->leftJoin('gmtsparts as style_embelishment_gmtsparts', function ($join) {
    $join->on('style_embelishment_gmtsparts.id', '=', 'style_embelishments.gmtspart_id');
   })
   ->leftJoin('embelishments as embelishments_style', function ($join) {
    $join->on('embelishments_style.id', '=', 'style_embelishments.embelishment_id');
   })
   ->leftJoin('embelishment_types as style_embelishment_type', function ($join) {
    $join->on('style_embelishment_type.id', '=', 'style_embelishments.embelishment_type_id');
   })
   ->leftJoin('budget_emb_cons', function ($join) {
    $join->on('budget_emb_cons.id', '=', 'po_emb_service_item_qties.budget_emb_con_id')->whereNull('budget_emb_cons.deleted_at');
   })
   ->leftJoin('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_order_gmt_color_sizes.id', '=', 'budget_emb_cons.sales_order_gmt_color_size_id');
   })
   ->leftJoin('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.id', '=', 'sales_order_gmt_color_sizes.sale_order_country_id');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('style_gmt_color_sizes', function ($join) {
    $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
   })
   ->leftJoin('style_sizes', function ($join) {
    $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
   })
   ->leftJoin('sizes as sizes_style', function ($join) {
    $join->on('sizes_style.id', '=', 'style_sizes.size_id');
   })
   ->leftJoin('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
   })
   ->leftJoin('colors as colors_style', function ($join) {
    $join->on('colors_style.id', '=', 'style_colors.color_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'sales_order_countries.country_id');
   })
   ->leftJoin('buyers as style_buyers', function ($join) {
    $join->on('style_buyers.id', '=', 'styles.buyer_id');
   })
   ->when(request('sale_order_no'), function ($q) {
    return $q->where('so_emb_items.gmt_sale_order_no', '=', request('sale_order_no'));
   })
   ->when(request('buyer_id'), function ($q) {
    return $q->where('so_embs.buyer_id', '=', request('buyer_id'));
   })
   ->where([['so_embs.production_area_id', '=', 45]])
   ->selectRaw(
    '  
        so_emb_refs.id,
        so_emb_refs.so_emb_id,
        embelishments.name as emb_name,
        embelishment_types.name as emb_type,
        so_emb_items.gmtspart_id,
        gmtsparts.name as gmtspart,
        so_emb_items.embelishment_size_id,
        so_emb_items.qty,
        so_emb_items.rate,
        so_emb_items.amount,
        so_emb_items.gmt_style_ref as style_ref,
        so_emb_items.gmt_sale_order_no as sale_order_no,
        so_emb_items.delivery_date,
        buyers.name as buyer_name,
        uoms.code as uom_name,
        colors.name as gmt_color,
        sizes.name as gmt_size,
        so_emb_items.item_account_id,
        item_accounts.item_description as item_desc,

        so_emb_refs.so_emb_id as c_so_emb_id,
        embelishments_style.name as c_emb_name,
        style_embelishment_type.name as c_emb_type,
        style_embelishment_gmtsparts.name as c_gmtspart,
        style_embelishments.embelishment_size_id,
        po_emb_services.delv_start_date as c_delivery_date,
        po_emb_service_item_qties.qty as c_qty,
        po_emb_service_item_qties.rate as c_rate,
        po_emb_service_item_qties.amount as c_amount,
        styles.style_ref as c_style_ref,
        sales_orders.sale_order_no as c_sale_order_no,
        style_buyers.name as c_buyer_name,
        style_gmt_items.item_description as c_item_desc,
        colors_style.name as c_gmt_color,
        sizes_style.name as c_gmt_size,
        style_embelishments.gmtspart_id as c_gmtspart_id,
        style_gmts.item_account_id as c_item_account_id
        '
   )
   ->orderBy('so_emb_items.id', 'DESC')
   ->get()
   ->map(function ($rows) use ($embelishmentsize) {
    $rows->emb_size = $embelishmentsize[$rows->embelishment_size_id] ? $embelishmentsize[$rows->embelishment_size_id] : $embelishmentsize[$rows->embelishment_size_id];
    $rows->so_emb_id = $rows->so_emb_id ? $rows->so_emb_id : $rows->c_so_emb_id;
    $rows->emb_name = $rows->emb_name ? $rows->emb_name : $rows->c_emb_name;
    $rows->emb_type = $rows->emb_type ? $rows->emb_type : $rows->c_emb_type;
    $rows->gmtspart = $rows->gmtspart ? $rows->gmtspart : $rows->c_gmtspart;
    $rows->qty = $rows->qty ? $rows->qty : $rows->c_qty;
    $rows->rate = $rows->rate ? $rows->rate : $rows->c_rate;
    $rows->amount = $rows->amount ? $rows->amount : $rows->c_amount;
    $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->c_style_ref;
    $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->c_sale_order_no;
    $rows->delivery_date = $rows->delivery_date ? $rows->delivery_date : $rows->c_delivery_date;
    $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->c_buyer_name;
    $rows->gmt_color = $rows->gmt_color ? $rows->gmt_color : $rows->c_gmt_color;
    $rows->gmt_size = $rows->gmt_size ? $rows->gmt_size : $rows->c_gmt_size;
    $rows->item_desc = $rows->item_desc ? $rows->item_desc : $rows->c_item_desc;
    $rows->gmtspart_id = $rows->gmtspart_id ? $rows->gmtspart_id : $rows->c_gmtspart_id;
    $rows->item_account_id = $rows->item_account_id ? $rows->item_account_id : $rows->c_item_account_id;

    $rows->qty = number_format($rows->qty, 2, '.', ',');
    $rows->rate = number_format($rows->rate, 4, '.', ',') . ' / ' . $rows->uom_name;
    $rows->amount = number_format($rows->amount, 2, '.', ',');
    return $rows;
   });
  echo json_encode($rows);
 }
}
