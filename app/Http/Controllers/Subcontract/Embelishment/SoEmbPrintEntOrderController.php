<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintEntryRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintEntOrderRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvQtyRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\LocationRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintEntOrderRequest;


class SoEmbPrintEntOrderController extends Controller
{

 private $soembprintentorder;
 private $soembprintentry;
 private $soembcutpanelrcvqty;
 private $assetquantitycost;
 private $company;
 private $supplier;
 private $location;

 public function __construct(
  SoEmbPrintEntOrderRepository $soembprintentorder,
  SoEmbPrintEntryRepository $soembprintentry,
  WstudyLineSetupRepository $wstudylinesetup,
  SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
  SoEmbCutpanelRcvQtyRepository $soembcutpanelrcvqty,
  AssetQuantityCostRepository $assetquantitycost,
  CompanyRepository $company,
  SupplierRepository $supplier,
  LocationRepository $location
 ) {
  $this->soembprintentorder = $soembprintentorder;
  $this->soembprintentry = $soembprintentry;
  // $this->gmtsewingqty = $gmtsewingqty;
  $this->wstudylinesetup = $wstudylinesetup;
  $this->salesordergmtcolorsize = $salesordergmtcolorsize;
  $this->soembcutpanelrcvqty = $soembcutpanelrcvqty;
  $this->assetquantitycost = $assetquantitycost;
  $this->company = $company;
  $this->supplier = $supplier;
  $this->location = $location;

  $this->middleware('auth');
  // $this->middleware('permission:view.soembprintentorders',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.soembprintentorders', ['only' => ['store']]);
  // $this->middleware('permission:edit.soembprintentorders',   ['only' => ['update']]);
  // $this->middleware('permission:delete.soembprintentorders', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');
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
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'so_emb_print_ent_orders.asset_quantity_cost_id');
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
   ->where([['so_emb_print_ent_orders.so_emb_print_entry_id', '=', request('so_emb_print_entry_id', 0)]])
   ->orderBy('so_emb_print_ent_orders.id', 'DESC')
   ->get([
    'so_emb_print_ent_orders.*',
    'so_emb_items.gmt_sale_order_no as sales_order_no',
    'item_accounts.item_description as item_desc',
    'colors.name as gmt_color',
    'gmtsparts.name as gmtspart',
    'so_emb_cutpanel_rcv_qties.design_no',
    'asset_quantity_costs.asset_no',

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
 public function store(SoEmbPrintEntOrderRequest $request)
 {
  $soembprintentorder = $this->soembprintentorder->create([
   'so_emb_print_entry_id' => $request->so_emb_print_entry_id,
   'prod_source_id' => $request->prod_source_id,
   'so_emb_cutpanel_rcv_qty_id' => $request->so_emb_cutpanel_rcv_qty_id,
   'asset_quantity_cost_id' => $request->asset_quantity_cost_id,
   'prod_hour' => $request->prod_hour,
   'qty' => $request->qty,
   'remarks' => $request->remarks
  ]);
  if ($soembprintentorder) {
   return response()->json(array('success' => true, 'id' =>  $soembprintentorder->id, 'message' => 'Save Successfully'), 200);
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
  $soembprintentorder = $this->soembprintentorder
   ->join('so_emb_print_entries', function ($join) {
    $join->on('so_emb_print_entries.id', '=', 'so_emb_print_ent_orders.so_emb_print_entry_id');
   })
   ->join('so_emb_cutpanel_rcv_qties', function ($join) {
    $join->on('so_emb_cutpanel_rcv_qties.id', '=', 'so_emb_print_ent_orders.so_emb_cutpanel_rcv_qty_id');
   })
   ->join('so_emb_refs', function ($join) {
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
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'so_emb_print_ent_orders.asset_quantity_cost_id');
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
   ->where([['so_emb_print_ent_orders.id', '=', $id]])
   ->get([
    'so_emb_print_ent_orders.*',
    'so_emb_items.gmt_sale_order_no as sales_order_no',
    'item_accounts.item_description as item_desc',
    'colors.name as gmt_color',
    'gmtsparts.name as gmtspart',
    'so_emb_cutpanel_rcv_qties.design_no',
    'asset_quantity_costs.asset_no',

    'style_gmt_items.item_description as c_item_desc',
    'style_embelishment_gmtspart.name as c_gmtspart',
    'sales_orders.sale_order_no as c_sale_order_no',
    'colors_style.name as c_gmt_color'
   ])
   ->map(function ($soembprintentorder) {
    $soembprintentorder->item_desc = $soembprintentorder->item_desc ? $soembprintentorder->item_desc : $soembprintentorder->c_item_desc;
    $soembprintentorder->gmtspart = $soembprintentorder->gmtspart ? $soembprintentorder->gmtspart : $soembprintentorder->c_gmtspart;
    $soembprintentorder->sales_order_no = $soembprintentorder->sales_order_no ? $soembprintentorder->sales_order_no : $soembprintentorder->c_sale_order_no;
    $soembprintentorder->gmt_color = $soembprintentorder->gmt_color ? $soembprintentorder->gmt_color : $soembprintentorder->c_gmt_color;
    return $soembprintentorder;
   })
   ->first();
  $row['fromData'] = $soembprintentorder;
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
 public function update(SoEmbPrintEntOrderRequest $request, $id)
 {
  $soembprintentorder = $this->soembprintentorder->update(
   $id,
   [
    'prod_source_id' => $request->prod_source_id,
    'so_emb_cutpanel_rcv_qty_id' => $request->so_emb_cutpanel_rcv_qty_id,
    'asset_quantity_cost_id' => $request->asset_quantity_cost_id,
    'prod_hour' => $request->prod_hour,
    'qty' => $request->qty,
    'remarks' => $request->remarks
   ]
  );

  if ($soembprintentorder) {
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
  if ($this->soembprintentorder->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getCutpanelOrder()
 {
  $prod_source_id = request('prod_source_id');
  if ($prod_source_id == 1) {
   $in_rows = $this->soembcutpanelrcvqty
    ->join('so_emb_cutpanel_rcv_orders', function ($join) {
     $join->on('so_emb_cutpanel_rcv_orders.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_cutpanel_rcv_order_id');
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
    ->orderBy('so_emb_cutpanel_rcv_qties.id', 'DESC')
    ->get([
     'so_emb_cutpanel_rcv_qties.*',
     'sales_orders.sale_order_no as sales_order_no',
     'item_accounts.item_description as item_desc',
     'colors.name as gmt_color',
     'gmtsparts.name as gmtspart'
    ]);
   echo json_encode($in_rows);
  }
  if ($prod_source_id == 5) {
   $rows = $this->soembcutpanelrcvqty
    ->join('so_emb_cutpanel_rcv_orders', function ($join) {
     $join->on('so_emb_cutpanel_rcv_orders.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_cutpanel_rcv_order_id');
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
    ->orderBy('so_emb_cutpanel_rcv_qties.id', 'DESC')
    ->get([
     'so_emb_cutpanel_rcv_qties.*',
     'so_emb_items.gmt_sale_order_no as sales_order_no',
     'item_accounts.item_description as item_desc',
     'colors.name as gmt_color',
     'gmtsparts.name as gmtspart'
    ]);
   echo json_encode($rows);
  }
 }

 public function getMachine()
 {
  $prod_date = request('prod_date');
  $so_emb_cutpanel_rcv_qty_id = request('so_emb_cutpanel_rcv_qty_id');
  $qty = $this->soembcutpanelrcvqty->find($so_emb_cutpanel_rcv_qty_id);

  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $depMethod = array_prepend(config('bprs.depMethod'), '-Select-', '');
  $assetType = config('bprs.assetType');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $assetquantitycost = $this->assetquantitycost
   ->selectRaw('
   asset_quantity_costs.id,
   asset_quantity_costs.serial_no,
   asset_quantity_costs.custom_no,
   asset_quantity_costs.asset_no,
   asset_quantity_costs.total_cost as origin_cost,
   asset_quantity_costs.accumulated_dep,
   asset_acquisitions.name as asset_name,
   asset_acquisitions.company_id,
   asset_acquisitions.location_id,
   asset_acquisitions.supplier_id,
   asset_acquisitions.iregular_supplier,
   asset_acquisitions.type_id,
   asset_acquisitions.production_area_id,
   asset_acquisitions.asset_group,
   asset_acquisitions.brand,
   asset_acquisitions.origin,
   asset_acquisitions.purchase_date,
   asset_acquisitions.prod_capacity,
   asset_acquisitions.salvage_value,
   asset_acquisitions.depreciation_method_id,
   asset_acquisitions.depreciation_rate
   ')
   ->leftJoin('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->leftJoin('so_emb_print_mcs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'so_emb_print_mcs.asset_quantity_cost_id');
   })
   ->leftJoin('so_emb_print_mc_dtls', function ($join) {
    $join->on('so_emb_print_mcs.id', '=', 'so_emb_print_mc_dtls.so_emb_print_mc_id');
   })
   ->leftJoin('so_emb_print_mc_dtl_ords', function ($join) {
    $join->on('so_emb_print_mc_dtls.id', '=', 'so_emb_print_mc_dtl_ords.so_emb_print_mc_dtl_id');
   })
   ->leftJoin('so_emb_refs', function ($join) {
    $join->on('so_emb_refs.id', '=', 'so_emb_print_mc_dtl_ords.so_emb_ref_id');
   })
   ->where([['so_emb_refs.id', '=', $qty->so_emb_ref_id]])
   ->when(request('asset_no'), function ($q) {
    return $q->where('asset_quantity_costs.asset_no', '=', request('asset_no', 0));
   })
   ->when(request('custom_no'), function ($q) {
    return $q->where('asset_quantity_costs.custom_no', '=', request('custom_no', 0));
   })
   ->when(request('asset_name'), function ($q) {
    return $q->where('asset_acquisitions.name', 'like', '%' . request('asset_name', 0) . '%');
   })
   ->when(request('prod_date'), function ($q) use ($prod_date) {
    return $q->where('so_emb_print_mc_dtls.from_date', '>=', $prod_date);
   })
   ->when(request('prod_date'), function ($q) use ($prod_date) {
    return $q->where('so_emb_print_mc_dtls.to_date', '<=', $prod_date);
   })
   ->groupBy([
    'asset_quantity_costs.id',
    'asset_quantity_costs.serial_no',
    'asset_quantity_costs.custom_no',
    'asset_quantity_costs.asset_no',
    'asset_quantity_costs.total_cost',
    'asset_quantity_costs.accumulated_dep',
    'asset_acquisitions.name',
    'asset_acquisitions.company_id',
    'asset_acquisitions.location_id',
    'asset_acquisitions.supplier_id',
    'asset_acquisitions.iregular_supplier',
    'asset_acquisitions.type_id',
    'asset_acquisitions.production_area_id',
    'asset_acquisitions.asset_group',
    'asset_acquisitions.brand',
    'asset_acquisitions.origin',
    'asset_acquisitions.purchase_date',
    'asset_acquisitions.prod_capacity',
    'asset_acquisitions.salvage_value',
    'asset_acquisitions.depreciation_method_id',
    'asset_acquisitions.depreciation_rate'
   ])
   ->get()
   ->map(function ($assetquantitycost) use ($assetType, $productionarea, $supplier, $company, $location, $depMethod) {
    $assetquantitycost->type_id = isset($assetType[$assetquantitycost->type_id]) ? $assetType[$assetquantitycost->type_id] : '';
    $assetquantitycost->production_area_id = isset($productionarea[$assetquantitycost->production_area_id]) ? $productionarea[$assetquantitycost->production_area_id] : '';
    $assetquantitycost->supplier_id = isset($supplier[$assetquantitycost->supplier_id]) ? $supplier[$assetquantitycost->supplier_id] : '';
    $assetquantitycost->company_id = isset($company[$assetquantitycost->company_id]) ? $company[$assetquantitycost->company_id] : '';
    $assetquantitycost->location_id = isset($location[$assetquantitycost->location_id]) ? $location[$assetquantitycost->location_id] : '';
    $assetquantitycost->depreciation_method_id = isset($depMethod[$assetquantitycost->depreciation_method_id]) ? $depMethod[$assetquantitycost->depreciation_method_id] : '';
    $assetquantitycost->written_down_value = $assetquantitycost->origin_cost - $assetquantitycost->accumulated_dep;
    return $assetquantitycost;
   });

  echo json_encode($assetquantitycost);
 }
}
