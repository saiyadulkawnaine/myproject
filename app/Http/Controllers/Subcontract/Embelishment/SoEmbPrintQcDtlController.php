<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcDtlRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintEntOrderRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Util\CompanyRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintQcDtlRequest;


class SoEmbPrintQcDtlController extends Controller
{

 private $soembprintqcdtl;
 private $soembprintqc;
 private $soembprintentorder;
 private $assetquantitycost;
 private $company;

 public function __construct(
  SoEmbPrintQcDtlRepository $soembprintqcdtl,
  SoEmbPrintQcRepository $soembprintqc,
  SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
  AssetQuantityCostRepository $assetquantitycost,
  SoEmbPrintEntOrderRepository $soembprintentorder,
  CompanyRepository $company
 ) {
  $this->soembprintqcdtl = $soembprintqcdtl;
  $this->soembprintqc = $soembprintqc;
  $this->soembprintentorder = $soembprintentorder;
  $this->salesordergmtcolorsize = $salesordergmtcolorsize;
  $this->assetquantitycost = $assetquantitycost;
  $this->company = $company;

  $this->middleware('auth');
  // $this->middleware('permission:view.soembprintqcdtls',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.soembprintqcdtls', ['only' => ['store']]);
  // $this->middleware('permission:edit.soembprintqcdtls',   ['only' => ['update']]);
  // $this->middleware('permission:delete.soembprintqcdtls', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');
  $rows = $this->soembprintqcdtl
   ->join('so_emb_print_qcs', function ($join) {
    $join->on('so_emb_print_qcs.id', '=', 'so_emb_print_qc_dtls.so_emb_print_qc_id');
   })
   ->join('so_emb_refs', function ($join) {
    $join->on('so_emb_refs.id', '=', 'so_emb_print_qc_dtls.so_emb_ref_id');
   })
   ->join('so_emb_cutpanel_rcv_qties', function ($join) {
    $join->on('so_emb_refs.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_ref_id');
   })
   ->leftJoin('so_emb_items', function ($join) {
    $join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
   })
   ->leftJoin('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'so_emb_items.gmtspart_id');
   })
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'so_emb_items.item_account_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'so_emb_items.color_id');
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
   ->leftJoin('gmtsparts as style_embelishment_gmtspart', function ($join) {
    $join->on('style_embelishment_gmtspart.id', '=', 'style_embelishments.gmtspart_id');
   })
   ->leftJoin('embelishments', function ($join) {
    $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
   })
   ->leftJoin('embelishment_types', function ($join) {
    $join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
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
   ->leftJoin('sizes', function ($join) {
    $join->on('sizes.id', '=', 'style_sizes.size_id');
   })
   ->leftJoin('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
   })
   ->leftJoin('colors as colors_style', function ($join) {
    $join->on('colors_style.id', '=', 'style_colors.color_id');
   })

   ->where([['so_emb_print_qc_dtls.so_emb_print_qc_id', '=', request('so_emb_print_qc_id', 0)]])
   ->orderBy('so_emb_print_qc_dtls.id', 'DESC')
   ->get([
    'so_emb_print_qc_dtls.*',
    'so_emb_items.gmt_sale_order_no as sales_order_no',
    'item_accounts.item_description as item_desc',
    'colors.name as gmt_color',
    'gmtsparts.name as gmtspart',
    'so_emb_cutpanel_rcv_qties.design_no',

    'style_gmt_items.item_description as c_item_desc',
    'style_embelishment_gmtspart.name as c_gmtspart',
    'sales_orders.sale_order_no as c_sale_order_no',
    'colors_style.name as c_gmt_color'
   ])
   ->map(function ($rows) use ($productionsource) {
    $rows->prod_source_id = $productionsource[$rows->prod_source_id];
    $rows->item_desc = $rows->item_desc ? $rows->item_desc : $rows->c_item_desc;
    $rows->gmtspart = $rows->gmtspart ? $rows->gmtspart : $rows->c_gmtspart;
    $rows->sales_order_no = $rows->sales_order_no ? $rows->sales_order_no : $rows->c_sale_order_no;
    $rows->gmt_color = $rows->gmt_color ? $rows->gmt_color : $rows->c_gmt_color;
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
 public function store(SoEmbPrintQcDtlRequest $request)
 {
  $soembprintqcdtl = $this->soembprintqcdtl->create([
   'so_emb_print_qc_id' => $request->so_emb_print_qc_id,
   'prod_source_id' => $request->prod_source_id,
   'so_emb_ref_id' => $request->so_emb_ref_id,
   'prod_hour' => $request->prod_hour,
   'qc_pass' => $request->qc_pass,
   'reject' => $request->reject,
   'alter' => $request->alter,
   'replace' => $request->replace,
   'remarks' => $request->remarks
  ]);
  if ($soembprintqcdtl) {
   return response()->json(array('success' => true, 'id' =>  $soembprintqcdtl->id, 'message' => 'Save Successfully'), 200);
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
  $soembprintqcdtl = $this->soembprintqcdtl
   ->join('so_emb_print_qcs', function ($join) {
    $join->on('so_emb_print_qcs.id', '=', 'so_emb_print_qc_dtls.so_emb_print_qc_id');
   })
   ->join('so_emb_refs', function ($join) {
    $join->on('so_emb_refs.id', '=', 'so_emb_print_qc_dtls.so_emb_ref_id');
   })
   ->join('so_emb_cutpanel_rcv_qties', function ($join) {
    $join->on('so_emb_refs.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_ref_id');
   })
   ->leftJoin('so_emb_items', function ($join) {
    $join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
   })
   ->leftJoin('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'so_emb_items.gmtspart_id');
   })
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'so_emb_items.item_account_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'so_emb_items.color_id');
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
   ->leftJoin('gmtsparts as style_embelishment_gmtspart', function ($join) {
    $join->on('style_embelishment_gmtspart.id', '=', 'style_embelishments.gmtspart_id');
   })
   ->leftJoin('embelishments', function ($join) {
    $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
   })
   ->leftJoin('embelishment_types', function ($join) {
    $join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
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
   ->leftJoin('sizes', function ($join) {
    $join->on('sizes.id', '=', 'style_sizes.size_id');
   })
   ->leftJoin('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
   })
   ->leftJoin('colors as colors_style', function ($join) {
    $join->on('colors_style.id', '=', 'style_colors.color_id');
   })

   ->where([['so_emb_print_qc_dtls.id', '=', $id]])
   ->get([
    'so_emb_print_qc_dtls.*',
    'so_emb_items.gmt_sale_order_no as sales_order_no',
    'item_accounts.item_description as item_desc',
    'colors.name as gmt_color',
    'gmtsparts.name as gmtspart',
    'so_emb_cutpanel_rcv_qties.design_no',

    'style_gmt_items.item_description as c_item_desc',
    'style_embelishment_gmtspart.name as c_gmtspart',
    'sales_orders.sale_order_no as c_sale_order_no',
    'colors_style.name as c_gmt_color'
   ])
   ->map(function ($soembprintqcdtl) {
    $soembprintqcdtl->item_desc = $soembprintqcdtl->item_desc ? $soembprintqcdtl->item_desc : $soembprintqcdtl->c_item_desc;
    $soembprintqcdtl->gmtspart = $soembprintqcdtl->gmtspart ? $soembprintqcdtl->gmtspart : $soembprintqcdtl->c_gmtspart;
    $soembprintqcdtl->sales_order_no = $soembprintqcdtl->sales_order_no ? $soembprintqcdtl->sales_order_no : $soembprintqcdtl->c_sale_order_no;
    $soembprintqcdtl->gmt_color = $soembprintqcdtl->gmt_color ? $soembprintqcdtl->gmt_color : $soembprintqcdtl->c_gmt_color;
    return $soembprintqcdtl;
   })
   ->first();
  $row['fromData'] = $soembprintqcdtl;
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
 public function update(SoEmbPrintQcDtlRequest $request, $id)
 {
  $soembprintqcdtl = $this->soembprintqcdtl->update(
   $id,
   [
    'prod_source_id' => $request->prod_source_id,
    'so_emb_ref_id' => $request->so_emb_ref_id,
    'prod_hour' => $request->prod_hour,
    'qc_pass' => $request->qc_pass,
    'reject' => $request->reject,
    'alter' => $request->alter,
    'replace' => $request->replace,
    'remarks' => $request->remarks
   ]
  );

  if ($soembprintqcdtl) {
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
  if ($this->soembprintqcdtl->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getSoEmbPrint()
 {
  $prod_source_id = request('prod_source_id');
  $buyer_id = request('buyer_id');
  if ($prod_source_id == 1) {
   $rows = $this->soembprintentorder
    ->join('so_emb_print_entries', function ($join) {
     $join->on('so_emb_print_entries.id', '=', 'so_emb_print_ent_orders.so_emb_print_entry_id');
    })
    ->join('so_emb_cutpanel_rcv_qties', function ($join) {
     $join->on('so_emb_cutpanel_rcv_qties.id', '=', 'so_emb_print_ent_orders.so_emb_cutpanel_rcv_qty_id');
    })
    ->join('so_emb_refs', function ($join) {
     $join->on('so_emb_refs.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_ref_id');
    })
    ->join('so_emb_po_items', function ($join) {
     $join->on('so_emb_po_items.so_emb_ref_id', '=', 'so_emb_refs.id');
    })
    ->join('po_emb_service_item_qties', function ($join) {
     $join->on('po_emb_service_item_qties.id', '=', 'so_emb_po_items.po_emb_service_item_qty_id');
    })
    ->join('po_emb_service_items', function ($join) {
     $join->on('po_emb_service_items.id', '=', 'po_emb_service_item_qties.po_emb_service_item_id')->whereNull('po_emb_service_items.deleted_at');
    })
    ->join('po_emb_services', function ($join) {
     $join->on('po_emb_services.id', '=', 'po_emb_service_items.po_emb_service_id');
    })
    ->join('budget_embs', function ($join) {
     $join->on('budget_embs.id', '=', 'po_emb_service_items.budget_emb_id');
    })
    ->join('style_embelishments', function ($join) {
     $join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
    })
    ->join('style_gmts', function ($join) {
     $join->on('style_gmts.id', '=', 'style_embelishments.style_gmt_id');
    })
    ->join('item_accounts', function ($join) {
     $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
    })
    ->join('gmtsparts', function ($join) {
     $join->on('gmtsparts.id', '=', 'style_embelishments.gmtspart_id');
    })
    ->join('embelishments', function ($join) {
     $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
    })
    ->join('embelishment_types', function ($join) {
     $join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
    })
    ->join('budget_emb_cons', function ($join) {
     $join->on('budget_emb_cons.id', '=', 'po_emb_service_item_qties.budget_emb_con_id')->whereNull('budget_emb_cons.deleted_at');
    })
    ->join('sales_order_gmt_color_sizes', function ($join) {
     $join->on('sales_order_gmt_color_sizes.id', '=', 'budget_emb_cons.sales_order_gmt_color_size_id');
    })
    ->join('sales_order_countries', function ($join) {
     $join->on('sales_order_countries.id', '=', 'sales_order_gmt_color_sizes.sale_order_country_id');
    })
    ->join('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
    })
    ->join('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->join('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->join('style_gmt_color_sizes', function ($join) {
     $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
    })
    ->join('style_sizes', function ($join) {
     $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
    })
    ->join('sizes', function ($join) {
     $join->on('sizes.id', '=', 'style_sizes.size_id');
    })
    ->join('style_colors', function ($join) {
     $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
    })
    ->join('colors', function ($join) {
     $join->on('colors.id', '=', 'style_colors.color_id');
    })
    ->where([['so_emb_print_entries.buyer_id', '=', $buyer_id]])
    ->orderBy('so_emb_print_ent_orders.id', 'DESC')
    ->get([
     'so_emb_print_ent_orders.*',
     'sales_orders.sale_order_no as sales_order_no',
     'item_accounts.item_description as item_desc',
     'colors.name as gmt_color',
     'gmtsparts.name as gmtspart',
     'so_emb_cutpanel_rcv_qties.design_no',
     'so_emb_refs.id as so_emb_ref_id'
    ]);
   echo json_encode($rows);
  }
  if ($prod_source_id == 5) {
   $rows = $this->soembprintentorder
    ->join('so_emb_print_entries', function ($join) {
     $join->on('so_emb_print_entries.id', '=', 'so_emb_print_ent_orders.so_emb_print_entry_id');
    })
    ->join('so_emb_cutpanel_rcv_qties', function ($join) {
     $join->on('so_emb_cutpanel_rcv_qties.id', '=', 'so_emb_print_ent_orders.so_emb_cutpanel_rcv_qty_id');
    })
    ->join('so_emb_refs', function ($join) {
     $join->on('so_emb_refs.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_ref_id');
    })
    ->join('so_emb_items', function ($join) {
     $join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
    })
    ->join('gmtsparts', function ($join) {
     $join->on('gmtsparts.id', '=', 'so_emb_items.gmtspart_id');
    })
    ->join('item_accounts', function ($join) {
     $join->on('item_accounts.id', '=', 'so_emb_items.item_account_id');
    })
    ->join('colors', function ($join) {
     $join->on('colors.id', '=', 'so_emb_items.color_id');
    })
    ->where([['so_emb_print_entries.buyer_id', '=', $buyer_id]])
    ->orderBy('so_emb_print_ent_orders.id', 'DESC')
    ->get([
     'so_emb_print_ent_orders.*',
     'so_emb_items.gmt_sale_order_no as sales_order_no',
     'item_accounts.item_description as item_desc',
     'colors.name as gmt_color',
     'gmtsparts.name as gmtspart',
     'so_emb_cutpanel_rcv_qties.design_no',
     'so_emb_refs.id as so_emb_ref_id'
    ]);
   echo json_encode($rows);
  }
 }
}
