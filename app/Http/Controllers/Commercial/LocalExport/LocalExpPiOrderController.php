<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiOrderRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;


use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpPiOrderRequest;

class LocalExpPiOrderController extends Controller
{

 private $localexppi;
 private $localexppiorder;
 private $itemaccount;
 private $salesorder;
 private $soknit;
 private $sodyeing;
 private $soaop;
 private $soemb;
 private $embelishment;
 private $size;


 public function __construct(
  LocalExpPiOrderRepository $localexppiorder,
  LocalExpPiRepository $localexppi,
  ItemAccountRepository $itemaccount,
  SalesOrderRepository $salesorder,
  StyleRepository $style,
  SoKnitRepository $soknit,
  SoDyeingRepository $sodyeing,
  GmtspartRepository $gmtspart,
  AutoyarnRepository $autoyarn,
  UomRepository $uom,
  ColorrangeRepository $colorrange,
  SoAopRepository $soaop,
  EmbelishmentTypeRepository $embelishmenttype,
  SoEmbRepository $soemb,
  EmbelishmentRepository $embelishment,
  ColorRepository $color,
  SizeRepository $size

 ) {
  $this->localexppi = $localexppi;
  $this->localexppiorder = $localexppiorder;
  $this->itemaccount = $itemaccount;
  $this->salesorder = $salesorder;
  $this->style = $style;
  $this->soknit = $soknit;
  $this->sodyeing = $sodyeing;
  $this->autoyarn = $autoyarn;
  $this->gmtspart = $gmtspart;
  $this->uom = $uom;
  $this->colorrange = $colorrange;
  $this->color = $color;
  $this->size = $size;
  $this->soaop = $soaop;
  $this->embelishmenttype = $embelishmenttype;
  $this->soemb = $soemb;
  $this->embelishment = $embelishment;

  $this->middleware('auth');
  // $this->middleware('permission:view.localexppiorders',['only'=>['create','index''show']]);
  // $this->middleware('permission:create.localexppiorders',['only' => ['store']]);
  // $this->middleware('permission:edit.localexppiorders',['only' => ['update']]);
  // $this->middleware('permission:delete.localexppiorders',['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {

  $localexppi = $this->localexppi->find(request('local_exp_pi_id', 0));
  $production_area_id = $localexppi->production_area_id;
  if ($production_area_id == 10) {
   $autoyarn = $this->autoyarn
    ->join('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->join('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->join('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');


   $rows = $this->soknit
    ->join('so_knit_refs', function ($join) {
     $join->on('so_knit_refs.so_knit_id', '=', 'so_knits.id');
    })
    ->leftJoin('so_knit_pos', function ($join) {
     $join->on('so_knit_pos.so_knit_id', '=', 'so_knits.id');
    })
    ->leftJoin('so_knit_po_items', function ($join) {
     $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
    })
    ->leftJoin('po_knit_service_items', function ($join) {
     $join->on('po_knit_service_items.id', '=', 'po_knit_service_item_qties.po_knit_service_item_id')
      ->whereNull('po_knit_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_knit_service_item_qties.sales_order_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_knit_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_knit_items', function ($join) {
     $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_knit_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_knit_items.uom_id');
    })
    ->leftJoin('colors as so_color', function ($join) {
     $join->on('so_color.id', '=', 'so_knit_items.fabric_color_id');
    })
    ->leftJoin('colors as po_color', function ($join) {
     $join->on('po_color.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->leftJoin(\DB::raw("(SELECT
                so_knit_refs.id as sales_order_ref_id,
                sum(local_exp_pi_orders.qty) as cumulative_qty
                FROM local_exp_pi_orders  
                join so_knit_refs on so_knit_refs.id = local_exp_pi_orders.sales_order_ref_id 
                
            group by so_knit_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_knit_refs.id")
    ->leftJoin('local_exp_pi_orders', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_knit_refs.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->where([['local_exp_pi_orders.local_exp_pi_id', '=', $localexppi->id]])
    ->selectRaw('
                so_knits.sales_order_no as knitting_sales_order,
                so_knit_refs.id as so_knit_ref_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.uom_id,
                budget_fabrics.gsm_weight,
                po_knit_service_item_qties.qty as order_qty,
                po_knit_service_item_qties.pcs_qty,
                po_knit_service_item_qties.rate as order_rate,
                po_knit_service_item_qties.amount as order_amount,
                so_knit_items.qty as c_qty,
                so_knit_items.rate as c_rate,
                so_knit_items.uom_id as c_uom_id,
                cumulatives.cumulative_qty,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_knit_items.gmt_style_ref,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                so_knit_items.fabric_look_id as c_fabric_look_id,
                so_knit_items.fabric_shape_id as c_fabric_shape_id,
                so_knit_items.gmtspart_id as c_gmtspart_id,
                so_knit_items.gsm_weight as c_gsm_weight,
                so_color.name as c_fabric_color_name,
                po_color.name as fabric_color_name,
                uoms.code as uom_code,
                so_uoms.code as c_uom_code,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per 
            ')
    ->orderBy('local_exp_pi_orders.id', 'desc')
    ->get()
    ->map(function ($rows) use ($desDropdown, $fabriclooks, $fabricshape) {
     $rows->sales_order_item_id = $rows->po_knit_service_item_qty_id ? $rows->po_knit_service_item_qty_id : $rows->so_knit_item_id;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->fabric_shape_id = $rows->fabric_shape_id ? $rows->fabric_shape_id : $rows->c_fabric_shape_id;
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;
     $rows->order_qty = $rows->order_qty ? $rows->order_qty : $rows->c_qty;
     $rows->order_rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->fabric_color = $rows->fabric_color_name ? $rows->fabric_color_name : $rows->c_fabric_color_name;
     $rows->uom_code = $rows->uom_code ? $rows->uom_code : $rows->c_uom_code;
     $rows->item_description = $rows->fabrication . ',' . $rows->fabriclooks . ',' . $rows->fabricshape . ',' . $rows->gsm_weight . ',' . $rows->fabric_color;
     $rows->balance_qty = $rows->order_qty - $rows->cumulative_qty;
     $rows->tagable_amount = $rows->balance_qty * $rows->order_rate;
     return $rows;
    });

   $orders = $rows;
   echo json_encode($orders);
  } elseif ($production_area_id == 20) {
   $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '-Select-', '');

   $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
   $autoyarn = $this->autoyarn
    ->join('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->join('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->join('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');

   $rows = $this->sodyeing
    ->join('so_dyeing_refs', function ($join) {
     $join->on('so_dyeing_refs.so_dyeing_id', '=', 'so_dyeings.id');
    })
    ->leftJoin('so_dyeing_pos', function ($join) {
     $join->on('so_dyeing_pos.so_dyeing_id', '=', 'so_dyeings.id');
    })
    ->leftJoin('so_dyeing_po_items', function ($join) {
     $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
    })
    ->leftJoin('po_dyeing_service_item_qties', function ($join) {
     $join->on('po_dyeing_service_item_qties.id', '=', 'so_dyeing_po_items.po_dyeing_service_item_qty_id');
    })
    ->leftJoin('po_dyeing_service_items', function ($join) {
     $join->on('po_dyeing_service_items.id', '=', 'po_dyeing_service_item_qties.po_dyeing_service_item_id')
      ->whereNull('po_dyeing_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_dyeing_service_item_qties.sales_order_id');
    })
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'po_dyeing_service_item_qties.fabric_color_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_dyeing_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_dyeing_items', function ($join) {
     $join->on('so_dyeing_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_dyeing_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_dyeing_items.uom_id');
    })
    // ->leftJoin('colors as po_color',function($join){
    //     $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
    // })

    ->leftJoin(\DB::raw("(SELECT
            so_dyeing_refs.id as so_dyeing_ref_id,
                sum(so_dyeing_items.qty) as cumulative_qty
                FROM so_dyeing_items  
                join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_items.so_dyeing_ref_id 
                
            group by so_dyeing_refs.id) cumulatives"), "cumulatives.so_dyeing_ref_id", "=", "so_dyeing_refs.id")

    ->leftJoin('local_exp_pi_orders', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_dyeing_refs.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->where([['local_exp_pi_orders.local_exp_pi_id', '=', $localexppi->id]])
    ->selectRaw(
     '
                po_dyeing_service_item_qties.id as po_dyeing_service_item_qty_id,
                so_dyeing_items.id as so_dyeing_item_id,
                so_dyeings.id as so_dyeing_id_sc,
                so_dyeing_refs.id as so_dyeing_ref_id,
                so_dyeing_refs.so_dyeing_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_dyeing_service_item_qties.fabric_color_id,
                po_dyeing_service_item_qties.colorrange_id,
                sum(po_dyeing_service_item_qties.qty) as order_qty,
                po_dyeing_service_item_qties.pcs_qty,
                avg(po_dyeing_service_item_qties.rate) as order_rate,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.gmtspart_id as c_gmtspart_id,
                so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.colorrange_id as c_colorrange_id,
                sum(so_dyeing_items.qty) as c_qty,
                avg(so_dyeing_items.rate) as c_rate,
                sum(so_dyeing_items.amount) as c_amount,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_style_ref,
                so_dyeing_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_code,
                so_uoms.code as so_uom_name,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per
              '
    )
    ->orderBy('local_exp_pi_orders.id', 'desc')
    ->groupBy([
     'so_dyeings.id',
     'so_dyeing_refs.id',
     'so_dyeing_refs.so_dyeing_id',
     'po_dyeing_service_item_qties.id',
     'so_dyeing_items.id',
     'constructions.name',
     'style_fabrications.autoyarn_id',
     'style_fabrications.fabric_look_id',
     'style_fabrications.fabric_shape_id',
     'style_fabrications.gmtspart_id',
     'budget_fabrics.gsm_weight',
     'po_dyeing_service_item_qties.fabric_color_id',
     'po_dyeing_service_item_qties.colorrange_id',
     'po_dyeing_service_item_qties.pcs_qty',
     'so_dyeing_items.autoyarn_id',
     'so_dyeing_items.fabric_look_id',
     'so_dyeing_items.fabric_shape_id',
     'so_dyeing_items.gmtspart_id',
     'so_dyeing_items.gsm_weight',
     'so_dyeing_items.fabric_color_id',
     'so_dyeing_items.colorrange_id',
     'styles.style_ref',
     'sales_orders.sale_order_no',
     'so_dyeing_items.gmt_style_ref',
     'so_dyeing_items.gmt_sale_order_no',
     'buyers.name',
     'gmt_buyer.name',
     'uoms.code',
     'so_uoms.code',
     'local_exp_pi_orders.local_exp_pi_id',
     'local_exp_pi_orders.sales_order_ref_id',
     'local_exp_pi_orders.qty',
     'local_exp_pi_orders.amount',
     'local_exp_pi_orders.discount_per',
     'local_exp_pi_orders.id'
    ])
    ->get()
    ->map(function ($rows) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape, $uom, $colorrange, $color) {
     $rows->customer_sales_order = $rows->sales_order_no;
     $rows->sales_order_id = $rows->so_dyeing_id_sc;
     $rows->sales_order_ref_id = $rows->so_dyeing_ref_id;
     $rows->sales_order_item_id = $rows->po_dyeing_service_item_qty_id ? $rows->po_dyeing_service_item_qty_id : $rows->so_dyeing_item_id;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->uom_id = $rows->uom_id ? $uom[$rows->uom_id] : '';
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;

     $rows->fabric_color_name = $rows->fabric_color_id ? $color[$rows->fabric_color_id] : $color[$rows->c_fabric_color_id];
     $rows->colorrange_id = $rows->colorrange_id ? $colorrange[$rows->colorrange_id] : $colorrange[$rows->c_colorrange_id];

     $rows->order_qty = $rows->order_qty ? $rows->order_qty : $rows->c_qty;
     $rows->pcs_qty = $rows->pcs_qty;
     $rows->order_rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->uom_name ? $rows->uom_name : $rows->so_uom_name;
     $rows->item_description = $rows->fabrication . ',' . $rows->fabriclooks . ',' . $rows->fabricshape . ',' . $rows->gsm_weight . ',' . $rows->fabric_color_name;
     //$rows->balance_qty=$rows->order_qty-$rows->cumulative_qty;
     //$rows->tagable_amount=$rows->balance_qty*$rows->order_rate;
     return $rows;
    });

   echo json_encode($rows);
  } elseif ($production_area_id == 25) {
   $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '-Select-', '');
   $aoptype = array_prepend(array_pluck($this->embelishmenttype->getAopTypes(), 'name', 'id'), '-Select-', '');
   $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
   $autoyarn = $this->autoyarn
    ->leftJoin('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');

   $rows = $this->soaop
    ->join('so_aop_refs', function ($join) {
     $join->on('so_aop_refs.so_aop_id', '=', 'so_aops.id');
    })
    ->leftJoin('so_aop_pos', function ($join) {
     $join->on('so_aop_pos.so_aop_id', '=', 'so_aops.id');
    })
    ->leftJoin('so_aop_po_items', function ($join) {
     $join->on('so_aop_po_items.so_aop_ref_id', '=', 'so_aop_refs.id');
    })
    ->leftJoin('po_aop_service_item_qties', function ($join) {
     $join->on('po_aop_service_item_qties.id', '=', 'so_aop_po_items.po_aop_service_item_qty_id');
    })
    ->leftJoin('po_aop_service_items', function ($join) {
     $join->on('po_aop_service_items.id', '=', 'po_aop_service_item_qties.po_aop_service_item_id')
      ->whereNull('po_aop_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_aop_service_item_qties.sales_order_id');
    })
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'po_aop_service_item_qties.fabric_color_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_aop_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_aop_items', function ($join) {
     $join->on('so_aop_items.so_aop_ref_id', '=', 'so_aop_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_aop_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_aop_items.uom_id');
    })
    ->leftJoin(\DB::raw("(SELECT
            so_aop_refs.id as so_aop_ref_id,
                sum(so_aop_items.qty) as cumulative_qty
                FROM so_aop_items  
                join so_aop_refs on so_aop_refs.id = so_aop_items.so_aop_ref_id 
                
            group by so_aop_refs.id) cumulatives"), "cumulatives.so_aop_ref_id", "=", "so_aop_refs.id")

    ->leftJoin('local_exp_pi_orders', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_aop_refs.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->where([['local_exp_pi_orders.local_exp_pi_id', '=', $localexppi->id]])
    ->selectRaw(
     '
                so_aop_refs.id as so_aop_ref_id,
                so_aop_refs.so_aop_id,
                constructions.name as constructions_name,
                po_aop_service_item_qties.fabric_color_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_aop_service_item_qties.budget_fabric_prod_con_id,
                po_aop_service_item_qties.colorrange_id,
                
                po_aop_service_item_qties.rate as order_rate,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                so_aop_items.fabric_look_id as c_fabric_look_id,
                so_aop_items.fabric_shape_id as c_fabric_shape_id,
                so_aop_items.gmtspart_id as c_gmtspart_id,
                so_aop_items.gsm_weight as c_gsm_weight,
                so_aop_items.fabric_color_id as c_fabric_color_id,
                so_aop_items.colorrange_id as c_colorrange_id,
                po_aop_service_item_qties.embelishment_type_id,
                po_aop_service_item_qties.coverage,
                po_aop_service_item_qties.impression,
                so_aop_items.embelishment_type_id as c_embelishment_type_id,
                so_aop_items.coverage as c_coverage,
                so_aop_items.impression as c_impression,
                so_aop_items.rate as c_rate,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_aop_items.gmt_style_ref,
                so_aop_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per
              '
    )
    ->orderBy('local_exp_pi_orders.id', 'desc')
    ->get()
    ->map(function ($rows) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape, $uom, $colorrange, $color, $aoptype) {
     $rows->customer_sales_order = $rows->sales_order_no;
     $rows->sales_order_ref_id = $rows->so_aop_ref_id;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;
     $rows->fabric_color_name = $rows->fabric_color_id ? $color[$rows->fabric_color_id] : $color[$rows->c_fabric_color_id];
     $rows->colorrange_id = $rows->colorrange_id ? $colorrange[$rows->colorrange_id] : $colorrange[$rows->c_colorrange_id];
     $rows->embelishment_type_id = $rows->embelishment_type_id ? $aoptype[$rows->embelishment_type_id] : $aoptype[$rows->c_embelishment_type_id];
     $rows->coverage = $rows->coverage ? $rows->coverage : $rows->c_coverage;
     $rows->impression = $rows->impression ? $rows->impression : $rows->c_impression;
     $rows->qty = number_format($rows->qty, 2, '.', ',');
     $rows->amount = number_format($rows->amount, 2, '.', ',');
     $rows->order_rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->uom_name ? $rows->uom_name : $rows->so_uom_name;
     $rows->item_description = $rows->fabrication . ',' . $rows->fabriclooks . ',' . $rows->fabricshape . ',' . $rows->gsm_weight . ',' . $rows->fabric_color_name . ',' . $rows->embelishment_type_id . ',' . $rows->coverage . '%' . ',' . $rows->impression;
     return $rows;
    });

   echo json_encode($rows);
  } elseif ($production_area_id == 45 || $production_area_id == 50 || $production_area_id == 51) {

   $embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
   $embelishmenttype = array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(), 'name', 'id'), '', '');
   $embelishment = array_prepend(array_pluck($this->embelishment->get(), 'name', 'id'), '', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '', '');
   $size = array_prepend(array_pluck($this->size->get(), 'name', 'id'), '', '');

   $rows = $this->localexppiorder
    ->selectRaw(
     '
                so_emb_refs.id as so_emb_ref_id,
                so_emb_refs.so_emb_id,
                so_embs.sales_order_no,
                gmtsparts.id as gmtspart_id,
                so_emb_items.gmtspart_id as c_gmtspart_id,
                style_embelishments.embelishment_size_id,
                style_embelishments.embelishment_type_id,
                style_embelishments.embelishment_id,
                so_emb_items.embelishment_id as c_embelishment_id,
                so_emb_items.embelishment_type_id as c_embelishment_type_id,
                so_emb_items.embelishment_size_id as c_embelishment_size_id,
                so_emb_items.color_id as c_color_id,
                so_emb_items.size_id as c_size_id,
                po_emb_service_item_qties.rate as order_rate,
                so_emb_items.rate as c_rate,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_emb_items.gmt_style_ref,
                so_emb_items.gmt_sale_order_no,  
                so_uoms.code as so_uom_name,
                item_accounts.item_description,
                colors.name as gmt_color,
                sizes.name as gmt_size,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per
            '
    )
    ->join('so_emb_refs', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_emb_refs.id');
     //$join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->leftJoin('so_embs', function ($join) {
     $join->on('so_emb_refs.so_emb_id', '=', 'so_embs.id');
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
     $join->on('po_emb_service_items.id', '=', 'po_emb_service_item_qties.po_emb_service_item_id')
      ->whereNull('po_emb_service_items.deleted_at');
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
    ->leftJoin('item_accounts', function ($join) {
     $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
    })
    ->leftJoin('gmtsparts', function ($join) {
     $join->on('gmtsparts.id', '=', 'style_embelishments.gmtspart_id');
    })
    ->leftJoin('embelishments', function ($join) {
     $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
    })
    ->leftJoin('embelishment_types', function ($join) {
     $join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
    })
    ->leftJoin('budget_emb_cons', function ($join) {
     $join->on('budget_emb_cons.id', '=', 'po_emb_service_item_qties.budget_emb_con_id')
      ->whereNull('budget_emb_cons.deleted_at');
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
    ->leftJoin('style_gmt_color_sizes', function ($join) {
     $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
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
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'style_colors.color_id');
    })
    ->leftJoin('so_emb_items', function ($join) {
     $join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_emb_items.uom_id');
    })
    ->where([['local_exp_pi_orders.local_exp_pi_id', '=', $localexppi->id]])
    ->orderBy('local_exp_pi_orders.id', 'desc')
    ->get()
    ->map(function ($rows) use ($gmtspart, $embelishmentsize, $embelishmenttype, $embelishment, $color, $size) {
     $rows->customer_sales_order = $rows->sales_order_no;
     $rows->emb_size = $embelishmentsize[$rows->embelishment_size_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->embelishment_type = $rows->embelishment_type_id ? $embelishmenttype[$rows->embelishment_type_id] : $embelishmenttype[$rows->c_embelishment_type_id];
     $rows->emb_size = $rows->embelishment_size_id ? $embelishmentsize[$rows->embelishment_size_id] : $embelishmentsize[$rows->c_embelishment_size_id];
     $rows->emb_name = $rows->embelishment_id ? $embelishment[$rows->embelishment_id] : $embelishment[$rows->c_embelishment_id];
     $rows->gmt_color = $rows->gmt_color ? $rows->gmt_color : $color[$rows->c_color_id];
     $rows->gmt_size = $rows->gmt_size ? $rows->gmt_size : $size[$rows->c_size_id];
     $rows->item_description = $rows->item_description . ',' . $rows->emb_name . ',' . $rows->emb_size . ',' . $rows->gmtspart . ',' . $rows->gmt_color . ',' . $rows->gmt_size;
     $rows->amount = $rows->amount ? $rows->amount : $rows->c_amount;
     $rows->net_amount = $rows->amount + $rows->discount_per;
     $rows->order_rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->so_uom_name ? $rows->so_uom_name : 'Pcs';

     $rows->sales_order_item_id = $rows->po_emb_service_item_qty_id ? $rows->po_emb_service_item_qty_id : $rows->so_emb_item_id;
     $rows->qty = number_format($rows->qty, 2, '.', ',');
     $rows->amount = number_format($rows->amount, 2, '.', ',');
     $rows->order_rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     return $rows;
    });
   echo json_encode($rows);
  }
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  //$sales_order_id=request('sales_order_id',0);
  //$idArr=explode(',',$sales_order_id);

  $localexppi = $this->localexppi->find(request('local_exp_pi_id', 0));
  $production_area_id = $localexppi->production_area_id;
  if ($production_area_id == 10) {
   $autoyarn = $this->autoyarn
    ->join('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->join('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->join('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');


   $rows = $this->soknit
    ->join('so_knit_refs', function ($join) {
     $join->on('so_knit_refs.so_knit_id', '=', 'so_knits.id');
    })
    ->leftJoin('so_knit_pos', function ($join) {
     $join->on('so_knit_pos.so_knit_id', '=', 'so_knits.id');
    })
    ->leftJoin('so_knit_po_items', function ($join) {
     $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
    })
    ->leftJoin('po_knit_service_items', function ($join) {
     $join->on('po_knit_service_items.id', '=', 'po_knit_service_item_qties.po_knit_service_item_id')
      ->whereNull('po_knit_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_knit_service_item_qties.sales_order_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_knit_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_knit_items', function ($join) {
     $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_knit_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_knit_items.uom_id');
    })
    ->leftJoin('colors as so_color', function ($join) {
     $join->on('so_color.id', '=', 'so_knit_items.fabric_color_id');
    })
    ->leftJoin('colors as po_color', function ($join) {
     $join->on('po_color.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->leftJoin(\DB::raw("(SELECT
            so_knit_refs.id as sales_order_ref_id,
            sum(local_exp_pi_orders.qty) as cumulative_qty
             FROM local_exp_pi_orders  
             join so_knit_refs on so_knit_refs.id = local_exp_pi_orders.sales_order_ref_id
             join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
              where  local_exp_pis.PRODUCTION_AREA_ID=10   
           group by so_knit_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_knit_refs.id")

    ->leftJoin('local_exp_pi_orders', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_knit_refs.id');
     //$join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->whereIn('so_knit_refs.id', explode(',', request('sales_order_ref_id', 0)))
    /* 
            */
    ->selectRaw('
                so_knits.id,
                so_knits.sales_order_no as knitting_sales_order,
                so_knit_refs.id as so_knit_ref_id,
                so_knit_refs.so_knit_id,
                style_fabrications.autoyarn_id,
                constructions.name as constructions_name,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                style_fabrications.uom_id,
                budget_fabrics.gsm_weight,
                po_knit_service_item_qties.id as po_knit_service_item_qty_id,
                po_knit_service_item_qties.qty as order_qty,
                po_knit_service_item_qties.pcs_qty,
                po_knit_service_item_qties.rate as order_rate,
                po_knit_service_item_qties.amount as order_amount,
                po_knit_service_item_qties.dia,
                po_knit_service_item_qties.measurment,
                so_knit_items.id as so_knit_item_id,
                so_knit_items.qty as c_qty,
                so_knit_items.rate as c_rate,
                so_knit_items.amount as c_amount,
                so_knit_items.uom_id as c_uom_id,
                cumulatives.cumulative_qty,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_knit_items.gmt_style_ref,
                so_knit_items.gmt_sale_order_no,
                buyers.code as buyer_name,
                gmt_buyer.code as gmt_buyer_name,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                so_knit_items.fabric_look_id as c_fabric_look_id,
                so_knit_items.fabric_shape_id as c_fabric_shape_id,
                so_knit_items.gmtspart_id as c_gmtspart_id,
                so_knit_items.gsm_weight as c_gsm_weight,
                so_knit_items.dia as c_dia,
                so_knit_items.measurment as c_measurment,

                so_color.name as c_fabric_color_name,
                po_color.name as fabric_color_name,
                uoms.code as uom_code,
                so_uoms.code as c_uom_code 
            ')
    ->orderBy('so_knit_refs.id', 'desc')
    ->get()
    ->map(function ($rows) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape) {
     $rows->sales_order_id = $rows->so_knit_id;
     $rows->sales_order_ref_id = $rows->so_knit_ref_id;
     $rows->sales_order_item_id = $rows->po_knit_service_item_qty_id ? $rows->po_knit_service_item_qty_id : $rows->so_knit_item_id;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->fabric_shape_id = $rows->fabric_shape_id ? $rows->fabric_shape_id : $rows->c_fabric_shape_id;
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;
     $rows->dia = $rows->dia ? $rows->dia : $rows->c_dia;
     $rows->measurment = $rows->measurment ? $rows->measurment : $rows->c_measurment;
     $rows->order_qty = $rows->order_qty ? $rows->order_qty : $rows->c_qty;
     $rows->order_rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->order_amount = $rows->order_amount ? $rows->order_amount : $rows->c_amount;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->fabric_color = $rows->fabric_color_name ? $rows->fabric_color_name : $rows->c_fabric_color_name;
     $rows->uom_code = $rows->uom_code ? $rows->uom_code : $rows->c_uom_code;
     $rows->item_description = $rows->fabrication . ',' . $rows->fabriclooks . ',' . $rows->fabricshape . ',' . $rows->gsm_weight . ',' . $rows->fabric_color;
     $rows->balance_qty = $rows->order_qty - $rows->cumulative_qty;
     $rows->tagable_amount = $rows->balance_qty * $rows->order_rate;
     return $rows;
    });

   $orders = $rows;
   return Template::loadView('Commercial.LocalExport.LocalExpPiOrderMatrix', ['orders' => $orders]);
  } elseif ($production_area_id == 20) {
   $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '-Select-', '');
   $dyetype = array_prepend(config('bprs.dyetype'), '-Select-', '');
   $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
   $autoyarn = $this->autoyarn
    ->join('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->join('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->join('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');

   $rows = $this->sodyeing
    ->join('so_dyeing_refs', function ($join) {
     $join->on('so_dyeing_refs.so_dyeing_id', '=', 'so_dyeings.id');
    })
    ->leftJoin('so_dyeing_pos', function ($join) {
     $join->on('so_dyeing_pos.so_dyeing_id', '=', 'so_dyeings.id');
    })
    ->leftJoin('so_dyeing_po_items', function ($join) {
     $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
    })
    ->leftJoin('po_dyeing_service_item_qties', function ($join) {
     $join->on('po_dyeing_service_item_qties.id', '=', 'so_dyeing_po_items.po_dyeing_service_item_qty_id');
    })
    ->leftJoin('po_dyeing_service_items', function ($join) {
     $join->on('po_dyeing_service_items.id', '=', 'po_dyeing_service_item_qties.po_dyeing_service_item_id')
      ->whereNull('po_dyeing_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_dyeing_service_item_qties.sales_order_id');
    })
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'po_dyeing_service_item_qties.fabric_color_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_dyeing_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_dyeing_items', function ($join) {
     $join->on('so_dyeing_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_dyeing_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_dyeing_items.uom_id');
    })
    // ->leftJoin('colors as po_color',function($join){
    //     $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
    // })

    ->leftJoin(\DB::raw("(SELECT so_dyeing_refs.id as sales_order_ref_id,sum(local_exp_pi_orders.qty) as cumulative_qty FROM local_exp_pi_orders right join so_dyeing_refs on so_dyeing_refs.id = local_exp_pi_orders.sales_order_ref_id 
            join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
              where  local_exp_pis.PRODUCTION_AREA_ID=20
            group by so_dyeing_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_dyeing_refs.id")

    ->leftJoin('local_exp_pi_orders', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_dyeing_refs.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->whereIn('so_dyeing_refs.id', explode(',', request('sales_order_ref_id', 0)))
    ->selectRaw(
     '
                so_dyeings.id as so_dyeing_id_sc,
                so_dyeing_refs.id as so_dyeing_ref_id,
                so_dyeing_refs.so_dyeing_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                style_fabrications.dyeing_type_id,
                budget_fabrics.gsm_weight,
                po_dyeing_service_item_qties.fabric_color_id,
                po_dyeing_service_item_qties.colorrange_id,
                sum(po_dyeing_service_item_qties.qty) as order_qty,
                po_dyeing_service_item_qties.pcs_qty,
                avg(po_dyeing_service_item_qties.rate) as order_rate,
                sum(po_dyeing_service_item_qties.amount) as order_amount,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.gmtspart_id as c_gmtspart_id,
                so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.colorrange_id as c_colorrange_id,
                so_dyeing_items.dyeing_type_id as c_dyeing_type_id,
                sum(so_dyeing_items.qty) as c_qty,
                avg(so_dyeing_items.rate) as c_rate,
                sum(so_dyeing_items.amount) as c_amount,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_style_ref,
                so_dyeing_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_code,
                so_uoms.code as so_uom_name,
                cumulatives.cumulative_qty
              '
    )
    ->orderBy('so_dyeing_refs.id', 'desc')
    ->groupBy([
     'so_dyeings.id',
     'so_dyeing_refs.id',
     'so_dyeing_refs.so_dyeing_id',
     'po_dyeing_service_item_qties.id',
     'so_dyeing_items.id',
     'constructions.name',
     'style_fabrications.autoyarn_id',
     'style_fabrications.fabric_look_id',
     'style_fabrications.fabric_shape_id',
     'style_fabrications.gmtspart_id',
     'style_fabrications.dyeing_type_id',
     'budget_fabrics.gsm_weight',
     'po_dyeing_service_item_qties.fabric_color_id',
     'po_dyeing_service_item_qties.colorrange_id',
     'po_dyeing_service_item_qties.pcs_qty',
     'so_dyeing_items.autoyarn_id',
     'so_dyeing_items.fabric_look_id',
     'so_dyeing_items.fabric_shape_id',
     'so_dyeing_items.gmtspart_id',
     'so_dyeing_items.gsm_weight',
     'so_dyeing_items.fabric_color_id',
     'so_dyeing_items.colorrange_id',
     'so_dyeing_items.dyeing_type_id',
     'styles.style_ref',
     'sales_orders.sale_order_no',
     'so_dyeing_items.gmt_style_ref',
     'so_dyeing_items.gmt_sale_order_no',
     'buyers.name',
     'gmt_buyer.name',
     'uoms.code',
     'so_uoms.code',
     'cumulatives.cumulative_qty'
    ])
    ->get()
    ->map(function ($rows) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape, $uom, $colorrange, $color, $dyetype) {
     $rows->sales_order_id = $rows->so_dyeing_id_sc;
     $rows->sales_order_ref_id = $rows->so_dyeing_ref_id;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->uom_id = $rows->uom_id ? $uom[$rows->uom_id] : '';
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;

     $rows->fabric_color_name = $rows->fabric_color_id ? $color[$rows->fabric_color_id] : $color[$rows->c_fabric_color_id];
     $rows->colorrange_id = $rows->colorrange_id ? $colorrange[$rows->colorrange_id] : $colorrange[$rows->c_colorrange_id];
     $rows->dyeing_type_id = $rows->dyeing_type_id ? $dyetype[$rows->dyeing_type_id] : $dyetype[$rows->c_dyeing_type_id];

     $rows->order_qty = $rows->order_qty ? $rows->order_qty : $rows->c_qty;
     $rows->pcs_qty = $rows->pcs_qty;
     $rows->order_rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->order_amount = $rows->order_amount ? $rows->order_amount : $rows->c_amount;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->uom_name ? $rows->uom_name : $rows->so_uom_name;
     $rows->item_description = $rows->fabrication . ',' . $rows->fabriclooks . ',' . $rows->fabricshape . ',' . $rows->gsm_weight . ',' . $rows->fabric_color_name . ',' . $rows->dyeing_type_id;
     $rows->balance_qty = $rows->order_qty - $rows->cumulative_qty;
     $rows->tagable_amount = $rows->balance_qty * $rows->order_rate;
     return $rows;
    });
   $orders = $rows;
   return Template::loadView('Commercial.LocalExport.LocalExpPiOrderMatrix', ['orders' => $orders]);
  } elseif ($production_area_id == 25) {
   $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '-Select-', '');
   $aoptype = array_prepend(array_pluck($this->embelishmenttype->getAopTypes(), 'name', 'id'), '-Select-', '');
   $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
   $autoyarn = $this->autoyarn
    ->leftJoin('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');

   $rows = $this->soaop
    ->join('so_aop_refs', function ($join) {
     $join->on('so_aop_refs.so_aop_id', '=', 'so_aops.id');
    })
    ->leftJoin('so_aop_pos', function ($join) {
     $join->on('so_aop_pos.so_aop_id', '=', 'so_aops.id');
    })
    ->leftJoin('so_aop_po_items', function ($join) {
     $join->on('so_aop_po_items.so_aop_ref_id', '=', 'so_aop_refs.id');
    })
    ->leftJoin('po_aop_service_item_qties', function ($join) {
     $join->on('po_aop_service_item_qties.id', '=', 'so_aop_po_items.po_aop_service_item_qty_id');
    })
    ->leftJoin('po_aop_service_items', function ($join) {
     $join->on('po_aop_service_items.id', '=', 'po_aop_service_item_qties.po_aop_service_item_id')
      ->whereNull('po_aop_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_aop_service_item_qties.sales_order_id');
    })
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'po_aop_service_item_qties.fabric_color_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_aop_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_aop_items', function ($join) {
     $join->on('so_aop_items.so_aop_ref_id', '=', 'so_aop_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_aop_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_aop_items.uom_id');
    })
    ->leftJoin(\DB::raw("(SELECT so_aop_refs.id as sales_order_ref_id,sum(local_exp_pi_orders.qty) as cumulative_qty FROM local_exp_pi_orders right join so_aop_refs on so_aop_refs.id = local_exp_pi_orders.sales_order_ref_id  
            join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
              where  local_exp_pis.PRODUCTION_AREA_ID=25
             group by so_aop_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_aop_refs.id")
    ->leftJoin('local_exp_pi_orders', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_aop_refs.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->whereIn('so_aop_refs.id', explode(',', request('sales_order_ref_id', 0)))
    ->selectRaw('
                so_aop_refs.id as so_aop_ref_id,
                so_aop_refs.so_aop_id,
                constructions.name as constructions_name,
                po_aop_service_item_qties.fabric_color_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_aop_service_item_qties.budget_fabric_prod_con_id,
                po_aop_service_item_qties.colorrange_id,
                po_aop_service_item_qties.embelishment_type_id,
                po_aop_service_item_qties.coverage,
                po_aop_service_item_qties.impression,

                sum(po_aop_service_item_qties.qty) as order_qty,
                avg(po_aop_service_item_qties.rate) as order_rate,
                sum(po_aop_service_item_qties.amount) as order_amount,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                so_aop_items.fabric_look_id as c_fabric_look_id,
                so_aop_items.fabric_shape_id as c_fabric_shape_id,
                so_aop_items.gmtspart_id as c_gmtspart_id,
                so_aop_items.gsm_weight as c_gsm_weight,
                so_aop_items.fabric_color_id as c_fabric_color_id,
                so_aop_items.colorrange_id as c_colorrange_id,
                so_aop_items.embelishment_type_id as c_embelishment_type_id,
                so_aop_items.coverage as c_coverage,
                so_aop_items.impression as c_impression,
                sum(so_aop_items.qty) as c_qty,
                avg(so_aop_items.rate) as c_rate,
                sum(so_aop_items.amount) as c_amount,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_aop_items.gmt_style_ref,
                so_aop_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_code,
                so_uoms.code as so_uom_name,
                cumulatives.cumulative_qty
            ')
    ->orderBy('so_aop_refs.id', 'desc')
    ->groupBy([
     'so_aop_refs.id',
     'so_aop_refs.so_aop_id',
     'po_aop_service_item_qties.id',
     'so_aop_items.id',
     'constructions.name',
     'po_aop_service_item_qties.fabric_color_id',
     'style_fabrications.autoyarn_id',
     'style_fabrications.fabric_look_id',
     'style_fabrications.fabric_shape_id',
     'style_fabrications.gmtspart_id',

     'budget_fabrics.gsm_weight',
     'po_aop_service_item_qties.embelishment_type_id',
     'po_aop_service_item_qties.coverage',
     'po_aop_service_item_qties.impression',
     'po_aop_service_item_qties.budget_fabric_prod_con_id',
     'po_aop_service_item_qties.colorrange_id',
     'so_aop_items.autoyarn_id',
     'so_aop_items.fabric_look_id',
     'so_aop_items.fabric_shape_id',
     'so_aop_items.gmtspart_id',
     'so_aop_items.gsm_weight',
     'so_aop_items.fabric_color_id',
     'so_aop_items.colorrange_id',
     'so_aop_items.embelishment_type_id',
     'so_aop_items.coverage',
     'so_aop_items.impression',
     'styles.style_ref',
     'sales_orders.sale_order_no',
     'so_aop_items.gmt_style_ref',
     'so_aop_items.gmt_sale_order_no',
     'buyers.name',
     'gmt_buyer.name',
     'uoms.code',
     'so_uoms.code',
     'cumulatives.cumulative_qty'
    ])
    ->get()
    ->map(function ($rows) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape, $uom, $colorrange, $color, $aoptype) {
     $rows->sales_order_ref_id = $rows->so_aop_ref_id;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->uom_id = $rows->uom_id ? $uom[$rows->uom_id] : '';
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;

     $rows->fabric_color_name = $rows->fabric_color_id ? $color[$rows->fabric_color_id] : $color[$rows->c_fabric_color_id];
     $rows->colorrange_id = $rows->colorrange_id ? $colorrange[$rows->colorrange_id] : $colorrange[$rows->c_colorrange_id];
     $rows->embelishment_type_id = $rows->embelishment_type_id ? $aoptype[$rows->embelishment_type_id] : $aoptype[$rows->c_embelishment_type_id];
     $rows->coverage = $rows->coverage ? $rows->coverage : $rows->c_coverage;
     $rows->impression = $rows->impression ? $rows->impression : $rows->c_impression;

     $rows->order_qty = $rows->order_qty ? $rows->order_qty : $rows->c_qty;
     $rows->order_rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->order_amount = $rows->order_amount ? $rows->order_amount : $rows->c_amount;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->uom_name ? $rows->uom_name : $rows->so_uom_name;
     $rows->item_description = $rows->fabrication . ',' . $rows->fabriclooks . ',' . $rows->fabricshape . ',' . $rows->gsm_weight . ',' . $rows->fabric_color_name . ',' . $rows->embelishment_type_id . ',' . $rows->coverage . ',' . $rows->impression;
     $rows->balance_qty = $rows->order_qty - $rows->cumulative_qty;
     $rows->tagable_amount = $rows->balance_qty * $rows->order_rate;
     return $rows;
    });
   $orders = $rows;
   return Template::loadView('Commercial.LocalExport.LocalExpPiOrderMatrix', ['orders' => $orders]);
  } elseif ($production_area_id == 45 || $production_area_id == 50 || $production_area_id == 51) {
   $embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
   $embelishmenttype = array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(), 'name', 'id'), '', '');
   $embelishment = array_prepend(array_pluck($this->embelishment->get(), 'name', 'id'), '', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '', '');
   $size = array_prepend(array_pluck($this->size->get(), 'name', 'id'), '', '');
   $rows = $this->soemb
    ->selectRaw(
     '
                so_embs.sales_order_no,
                so_embs.buyer_id,
                so_embs.company_id,
                so_embs.receive_date,
                so_emb_refs.id as so_emb_ref_id,
                so_emb_refs.so_emb_id,
                sum(po_emb_service_item_qties.qty) as order_qty,
                sum(po_emb_service_item_qties.rate) as order_rate,
                sum(po_emb_service_item_qties.amount) as order_amount,
                gmtsparts.id as gmtspart_id,
                style_embelishments.embelishment_size_id,
                style_embelishments.embelishment_type_id,
                style_embelishments.embelishment_id,
                so_emb_items.embelishment_id as c_embelishment_id,
                so_emb_items.embelishment_type_id as c_embelishment_type_id,
                so_emb_items.embelishment_size_id as c_embelishment_size_id,
                so_emb_items.gmtspart_id as c_gmtspart_id,
                so_emb_items.color_id as c_color_id,
                so_emb_items.size_id as c_size_id,
                so_emb_items.qty as c_qty,
                so_emb_items.rate as c_rate,
                so_emb_items.amount as c_amount,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_emb_items.gmt_style_ref,
                so_emb_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                item_accounts.item_description,
                colors.name as gmt_color,
                sizes.name as gmt_size,
                so_uoms.code as so_uom_name,
                customers.code as customer_code,
                sales_company.code as sales_company_code,
                cumulatives.cumulative_qty
            '
    )
    ->leftJoin('buyers as customers', function ($join) {
     $join->on('so_embs.buyer_id', '=', 'customers.id');
    })
    ->leftJoin('companies as sales_company', function ($join) {
     $join->on('so_embs.company_id', '=', 'sales_company.id');
    })
    ->join('so_emb_refs', function ($join) {
     $join->on('so_emb_refs.so_emb_id', '=', 'so_embs.id');
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
     $join->on('po_emb_service_items.id', '=', 'po_emb_service_item_qties.po_emb_service_item_id')
      ->whereNull('po_emb_service_items.deleted_at');
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
    ->leftJoin('item_accounts', function ($join) {
     $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
    })
    ->leftJoin('gmtsparts', function ($join) {
     $join->on('gmtsparts.id', '=', 'style_embelishments.gmtspart_id');
    })
    ->leftJoin('embelishments', function ($join) {
     $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
    })
    ->leftJoin('embelishment_types', function ($join) {
     $join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
    })
    ->leftJoin('budget_emb_cons', function ($join) {
     $join->on('budget_emb_cons.id', '=', 'po_emb_service_item_qties.budget_emb_con_id')
      ->whereNull('budget_emb_cons.deleted_at');
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
     $join->on('jobs.style_id', '=', 'styles.id');
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
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'style_colors.color_id');
    })
    // ->join('countries',function($join){
    //     $join->on('countries.id','=','sales_order_countries.country_id');
    // })
    ->leftJoin('so_emb_items', function ($join) {
     $join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_emb_items.gmt_buyer');
    })
    // ->leftJoin('uoms',function($join){
    //     $join->on('uoms.id','=','style_fabrications.uom_id');
    // })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_emb_items.uom_id');
    })
    ->leftJoin(\DB::raw("(SELECT so_emb_refs.id as sales_order_ref_id,sum(local_exp_pi_orders.qty) as cumulative_qty FROM local_exp_pi_orders right join so_emb_refs on so_emb_refs.id = local_exp_pi_orders.sales_order_ref_id  
            join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
              where  local_exp_pis.production_area_id in (45, 50, 51)
             group by so_emb_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_emb_refs.id")
    ->leftJoin('local_exp_pi_orders', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_emb_refs.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->whereIn('so_emb_refs.id', explode(',', request('sales_order_ref_id', 0)))
    ->orderBy('so_emb_refs.id', 'desc')
    ->groupBy([
     'so_embs.sales_order_no',
     'so_embs.buyer_id',
     'so_embs.company_id',
     'so_embs.receive_date',
     'so_emb_refs.id',
     'so_emb_refs.so_emb_id',
     'gmtsparts.id',
     'so_emb_items.gmtspart_id',
     'style_embelishments.embelishment_size_id',
     'style_embelishments.embelishment_type_id',
     'style_embelishments.embelishment_id',
     'so_emb_items.embelishment_id',
     'so_emb_items.embelishment_type_id',
     'so_emb_items.embelishment_size_id',
     'so_emb_items.color_id',
     'so_emb_items.size_id',

     'so_emb_items.qty',
     'so_emb_items.rate',
     'so_emb_items.amount',
     'styles.style_ref',
     'sales_orders.sale_order_no',
     'so_emb_items.gmt_style_ref',
     'so_emb_items.gmt_sale_order_no',
     'buyers.name',
     'gmt_buyer.name',
     'item_accounts.item_description',
     'colors.name',
     'sizes.name',
     'so_uoms.code',
     'customers.code',
     'sales_company.code',
     'cumulatives.cumulative_qty'
    ])
    ->get()
    ->map(function ($rows) use ($gmtspart, $embelishmentsize, $embelishmenttype, $embelishment, $color, $size) {
     $rows->customer_sales_order = $rows->sales_order_no;
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->embelishment_type = $rows->embelishment_type_id ? $embelishmenttype[$rows->embelishment_type_id] : $embelishmenttype[$rows->c_embelishment_type_id];
     $rows->emb_size = $rows->embelishment_size_id ? $embelishmentsize[$rows->embelishment_size_id] : $embelishmentsize[$rows->c_embelishment_size_id];
     $rows->emb_name = $rows->embelishment_id ? $embelishment[$rows->embelishment_id] : $embelishment[$rows->c_embelishment_id];
     $rows->amount = $rows->amount ? $rows->amount : $rows->c_amount;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->so_uom_name ? $rows->so_uom_name : 'Pcs';
     $rows->sales_order_ref_id = $rows->so_emb_ref_id;
     $rows->order_qty = $rows->order_qty ? $rows->order_qty : $rows->c_qty;
     $rows->order_rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->order_amount = $rows->order_amount ? $rows->order_amount : $rows->c_amount;
     $rows->gmt_color = $rows->gmt_color ? $rows->gmt_color : $color[$rows->c_color_id];
     $rows->gmt_size = $rows->gmt_size ? $rows->gmt_size : $size[$rows->c_size_id];
     $rows->item_description = $rows->item_description . ',' . $rows->emb_name . ',' . $rows->emb_size . ',' . $rows->gmtspart . ',' . $rows->gmt_color . ',' . $rows->gmt_size;
     $rows->balance_qty = $rows->order_qty - $rows->cumulative_qty;
     $rows->tagable_amount = $rows->balance_qty * $rows->order_rate;
     return $rows;
    });
   $orders = $rows;
   return Template::loadView('Commercial.LocalExport.LocalExpPiOrderMatrix', ['orders' => $orders]);
  }
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(LocalExpPiOrderRequest $request)
 {
  $qty = 0;
  $amount = 0;
  foreach ($request->sales_order_ref_id as $index => $sales_order_ref_id) {

   if ($request->qty[$index]) {
    $localexppiorder = $this->localexppiorder->create(
     [
      'local_exp_pi_id' => $request->local_exp_pi_id, 'sales_order_ref_id' => $sales_order_ref_id,
      //'sales_order_ref_id' => $request->sales_order_ref_id[$index],
      'qty' => $request->qty[$index],
      'amount' => $request->amount[$index],
      'discount_per' => $request->discount_per[$index],
     ]
    );
    $qty += $request->qty[$index];
    $amount += $request->amount[$index];
   }
  }
  $tqty = $qty;
  $tamount = $amount;
  $this->localexppi->where([['id', '=', $localexppiorder->local_exp_pi_id]])->update(['qty' => $tqty, 'amount' => $tamount]);
  if ($localexppiorder) {
   return response()->json(array('success' => true, 'id' => $localexppiorder->id, 'message' => 'Save Successfully'), 200);
  }
  /* if($localexppiorder){
            return response()->json(array('success'=>true,'id'=>$localexppiorder->id,'local_exp_pi_id' => $request->local_exp_pi_id,'message'=>'Save Successfully'),200);
        } */
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
  $localpi = $this->localexppiorder->find($id);
  $localexppi = $this->localexppi->find($localpi->local_exp_pi_id);
  $production_area_id = $localexppi->production_area_id;
  if ($production_area_id == 20) {
   $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '-Select-', '');
   $autoyarn = $this->autoyarn
    ->join('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->join('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->join('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');

   $rows = $this->localexppiorder
    ->join('so_dyeing_refs', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_dyeing_refs.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->join('so_dyeings', function ($join) {
     $join->on('so_dyeing_refs.so_dyeing_id', '=', 'so_dyeings.id');
    })
    ->leftJoin('so_dyeing_pos', function ($join) {
     $join->on('so_dyeing_pos.so_dyeing_id', '=', 'so_dyeings.id');
    })
    ->leftJoin('so_dyeing_po_items', function ($join) {
     $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
    })
    ->leftJoin('po_dyeing_service_item_qties', function ($join) {
     $join->on('po_dyeing_service_item_qties.id', '=', 'so_dyeing_po_items.po_dyeing_service_item_qty_id');
    })
    ->leftJoin('po_dyeing_service_items', function ($join) {
     $join->on('po_dyeing_service_items.id', '=', 'po_dyeing_service_item_qties.po_dyeing_service_item_id')
      ->whereNull('po_dyeing_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_dyeing_service_item_qties.sales_order_id');
    })
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'po_dyeing_service_item_qties.fabric_color_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_dyeing_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_dyeing_items', function ($join) {
     $join->on('so_dyeing_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_dyeing_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_dyeing_items.uom_id');
    })
    // ->leftJoin('colors as po_color',function($join){
    //     $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
    // })
    ->leftJoin(\DB::raw("(SELECT so_dyeing_refs.id as sales_order_ref_id,sum(local_exp_pi_orders.qty) as cumulative_qty FROM local_exp_pi_orders right join so_dyeing_refs on so_dyeing_refs.id = local_exp_pi_orders.sales_order_ref_id join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
            where  local_exp_pis.PRODUCTION_AREA_ID=20  group by so_dyeing_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_dyeing_refs.id")
    ->join('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
    })
    ->where([['local_exp_pi_orders.id', '=', $id]])
    ->where('local_exp_pi_orders.local_exp_pi_id', '=', $localexppi->id)
    ->selectRaw(
     '
                so_dyeing_refs.id as so_dyeing_ref_id,
                so_dyeing_refs.so_dyeing_id,
                so_dyeings.sales_order_no as dyeing_sales_order,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                budget_fabrics.gsm_weight,
                po_dyeing_service_item_qties.fabric_color_id,
                po_dyeing_service_item_qties.colorrange_id,
                po_dyeing_service_item_qties.qty as order_qty,
                po_dyeing_service_item_qties.pcs_qty,
                po_dyeing_service_item_qties.rate as order_rate,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.colorrange_id as c_colorrange_id,
                so_dyeing_items.qty as c_qty,
                so_dyeing_items.rate as c_rate,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_style_ref,
                so_dyeing_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_code,
                so_uoms.code as so_uom_name,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per,
                cumulatives.cumulative_qty
              '
    )
    ->get()
    ->map(function ($rows) use ($desDropdown, $fabriclooks, $fabricshape, $colorrange, $color) {
     $rows->sales_order_no = $rows->dyeing_sales_order;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;
     $rows->fabric_color_name = $rows->fabric_color_id ? $color[$rows->fabric_color_id] : $color[$rows->c_fabric_color_id];
     $rows->colorrange_id = $rows->colorrange_id ? $colorrange[$rows->colorrange_id] : $colorrange[$rows->c_colorrange_id];


     $rows->order_qty = $rows->order_qty ? $rows->order_qty : $rows->c_qty;
     $rows->rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->uom_name ? $rows->uom_name : $rows->so_uom_name;
     $rows->item_description = $rows->fabrication . ',' . $rows->fabriclooks . ',' . $rows->fabricshape . ',' . $rows->gsm_weight . ',' . $rows->fabric_color_name . ',' . $rows->colorrange_id;
     $rows->balance_qty = $rows->order_qty - $rows->cumulative_qty;
     $rows->tagable_amount = $rows->balance_qty * $rows->order_rate;
     $rows->net_amount = $rows->amount + $rows->discount_per;
     return $rows;
    })->first();

   $row['fromData'] = $rows;
   $dropdown['att'] = '';
   $row['dropDown'] = $dropdown;
   echo json_encode($row);
  } elseif ($production_area_id == 10) {
   $autoyarn = $this->autoyarn
    ->join('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->join('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->join('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');


   $rows = $this->localexppiorder
    ->join('so_knit_refs', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_knit_refs.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->join('so_knits', function ($join) {
     $join->on('so_knit_refs.so_knit_id', '=', 'so_knits.id');
    })
    ->leftJoin('so_knit_pos', function ($join) {
     $join->on('so_knit_pos.so_knit_id', '=', 'so_knits.id');
    })
    ->leftJoin('so_knit_po_items', function ($join) {
     $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
    })
    ->leftJoin('po_knit_service_items', function ($join) {
     $join->on('po_knit_service_items.id', '=', 'po_knit_service_item_qties.po_knit_service_item_id')
      ->whereNull('po_knit_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_knit_service_item_qties.sales_order_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_knit_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_knit_items', function ($join) {
     $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_knit_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_knit_items.uom_id');
    })
    ->leftJoin('colors as so_color', function ($join) {
     $join->on('so_color.id', '=', 'so_knit_items.fabric_color_id');
    })
    ->leftJoin('colors as po_color', function ($join) {
     $join->on('po_color.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->leftJoin(\DB::raw("(SELECT
                so_knit_refs.id as sales_order_ref_id,
                sum(local_exp_pi_orders.qty) as cumulative_qty
                FROM local_exp_pi_orders  
                join so_knit_refs on so_knit_refs.id = local_exp_pi_orders.sales_order_ref_id 
                join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
                where  local_exp_pis.PRODUCTION_AREA_ID=10 
            group by so_knit_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_knit_refs.id")
    ->join('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
    })
    ->where([['local_exp_pi_orders.id', '=', $id]])
    ->where('local_exp_pi_orders.local_exp_pi_id', '=', $localexppi->id)
    ->selectRaw('
                so_knits.sales_order_no as knitting_sales_order,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.uom_id,
                budget_fabrics.gsm_weight,
                po_knit_service_item_qties.qty as order_qty,
                po_knit_service_item_qties.pcs_qty,
                po_knit_service_item_qties.rate as order_rate,
                po_knit_service_item_qties.amount as order_amount,
                so_knit_items.qty as c_qty,
                so_knit_items.rate as c_rate,
                so_knit_items.uom_id as c_uom_id,
                cumulatives.cumulative_qty,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_knit_items.gmt_style_ref,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                so_knit_items.fabric_look_id as c_fabric_look_id,
                so_knit_items.fabric_shape_id as c_fabric_shape_id,
                so_knit_items.gmtspart_id as c_gmtspart_id,
                so_knit_items.gsm_weight as c_gsm_weight,
                so_color.name as c_fabric_color_name,
                po_color.name as fabric_color_name,
                uoms.code as uom_code,
                so_uoms.code as c_uom_code,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per 
            ')
    ->orderBy('local_exp_pi_orders.id', 'desc')
    ->get()
    ->map(function ($rows) use ($desDropdown, $fabriclooks, $fabricshape) {
     $rows->sales_order_no = $rows->knitting_sales_order;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->fabric_shape_id = $rows->fabric_shape_id ? $rows->fabric_shape_id : $rows->c_fabric_shape_id;
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;
     $rows->order_qty = $rows->order_qty ? $rows->order_qty : $rows->c_qty;
     $rows->rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->gmt_style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->gmt_sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->fabric_color = $rows->fabric_color_name ? $rows->fabric_color_name : $rows->c_fabric_color_name;
     $rows->uom_code = $rows->uom_code ? $rows->uom_code : $rows->c_uom_code;
     $rows->item_description = $rows->fabrication . ',' . $rows->fabriclooks . ',' . $rows->fabricshape . ',' . $rows->gsm_weight . ',' . $rows->fabric_color;
     $rows->balance_qty = $rows->order_qty - $rows->cumulative_qty;
     $rows->tagable_amount = $rows->balance_qty * $rows->order_rate;
     $rows->net_amount = $rows->amount + $rows->discount_per;
     return $rows;
    })->first();

   $orders = $rows;

   $row['fromData'] = $orders;
   $dropdown['att'] = '';
   $row['dropDown'] = $dropdown;
   echo json_encode($row);
  } elseif ($production_area_id == 25) {
   $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '-Select-', '');
   $aoptype = array_prepend(array_pluck($this->embelishmenttype->getAopTypes(), 'name', 'id'), '-Select-', '');
   $autoyarn = $this->autoyarn
    ->leftJoin('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');

   $rows = $this->localexppiorder
    ->join('so_aop_refs', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_aop_refs.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->join('so_aops', function ($join) {
     $join->on('so_aop_refs.so_aop_id', '=', 'so_aops.id');
    })
    ->leftJoin('so_aop_pos', function ($join) {
     $join->on('so_aop_pos.so_aop_id', '=', 'so_aops.id');
    })
    ->leftJoin('so_aop_po_items', function ($join) {
     $join->on('so_aop_po_items.so_aop_ref_id', '=', 'so_aop_refs.id');
    })
    ->leftJoin('po_aop_service_item_qties', function ($join) {
     $join->on('po_aop_service_item_qties.id', '=', 'so_aop_po_items.po_aop_service_item_qty_id');
    })
    ->leftJoin('po_aop_service_items', function ($join) {
     $join->on('po_aop_service_items.id', '=', 'po_aop_service_item_qties.po_aop_service_item_id')
      ->whereNull('po_aop_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_aop_service_item_qties.sales_order_id');
    })
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'po_aop_service_item_qties.fabric_color_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_aop_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_aop_items', function ($join) {
     $join->on('so_aop_items.so_aop_ref_id', '=', 'so_aop_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_aop_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_aop_items.uom_id');
    })
    // ->leftJoin('colors as po_color',function($join){
    //     $join->on('po_color.id','=','po_aop_service_item_qties.fabric_color_id');
    // })
    ->leftJoin(\DB::raw("(SELECT so_aop_refs.id as sales_order_ref_id,sum(local_exp_pi_orders.qty) as cumulative_qty FROM local_exp_pi_orders right join so_aop_refs on so_aop_refs.id = local_exp_pi_orders.sales_order_ref_id 
            join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
              where  local_exp_pis.PRODUCTION_AREA_ID=25  group by so_aop_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_aop_refs.id")
    ->join('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
    })
    ->where([['local_exp_pi_orders.id', '=', $id]])
    ->where([['local_exp_pi_orders.local_exp_pi_id', '=', $localexppi->id]])
    ->selectRaw(
     '
                so_aop_refs.id as so_aop_ref_id,
                so_aop_refs.so_aop_id,
                so_aops.sales_order_no as aop_sales_order,
                po_aop_service_item_qties.fabric_color_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                budget_fabrics.gsm_weight,
                po_aop_service_item_qties.budget_fabric_prod_con_id,
                po_aop_service_item_qties.colorrange_id,
                po_aop_service_item_qties.qty as order_qty,
                po_aop_service_item_qties.rate as order_rate,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                so_aop_items.fabric_look_id as c_fabric_look_id,
                so_aop_items.fabric_shape_id as c_fabric_shape_id,
                so_aop_items.gsm_weight as c_gsm_weight,
                so_aop_items.fabric_color_id as c_fabric_color_id,
                so_aop_items.colorrange_id as c_colorrange_id,
                so_aop_items.qty as c_qty,
                so_aop_items.rate as c_rate,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_aop_items.gmt_style_ref,
                so_aop_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per,
                cumulatives.cumulative_qty
              '
    )
    ->get()
    ->map(function ($rows) use ($desDropdown, $fabriclooks, $fabricshape, $colorrange, $color) {
     $rows->sales_order_no = $rows->aop_sales_order;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;
     $rows->fabric_color_name = $rows->fabric_color_id ? $color[$rows->fabric_color_id] : $color[$rows->c_fabric_color_id];
     $rows->colorrange_id = $rows->colorrange_id ? $colorrange[$rows->colorrange_id] : $colorrange[$rows->c_colorrange_id];
     $rows->order_qty = $rows->order_qty ? $rows->order_qty : $rows->c_qty;
     $rows->rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->uom_name ? $rows->uom_name : $rows->so_uom_name;
     $rows->item_description = $rows->fabrication . ',' . $rows->fabriclooks . ',' . $rows->fabricshape . ',' . $rows->gsm_weight . ',' . $rows->fabric_color_name . ',' . $rows->colorrange_id;
     $rows->balance_qty = $rows->order_qty - $rows->cumulative_qty;
     $rows->tagable_amount = $rows->balance_qty * $rows->order_rate;
     $rows->net_amount = $rows->amount + $rows->discount_per;
     return $rows;
    })->first();

   $row['fromData'] = $rows;
   $dropdown['att'] = '';
   $row['dropDown'] = $dropdown;
   echo json_encode($row);
  } elseif ($production_area_id == 45 || $production_area_id == 50 || $production_area_id == 51) {
   $embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
   $embelishmenttype = array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(), 'name', 'id'), '', '');
   $embelishment = array_prepend(array_pluck($this->embelishment->get(), 'name', 'id'), '', '');

   $rows = $this->localexppiorder
    ->selectRaw(
     '
                so_emb_refs.id as so_emb_ref_id,
                so_emb_refs.so_emb_id,
                so_embs.sales_order_no,
                gmtsparts.id as gmtspart_id,
                so_emb_items.gmtspart_id as c_gmtspart_id,
                style_embelishments.embelishment_size_id,
                style_embelishments.embelishment_type_id,
                style_embelishments.embelishment_id,
                so_emb_items.embelishment_id as c_embelishment_id,
                so_emb_items.embelishment_type_id as c_embelishment_type_id,
                so_emb_items.embelishment_size_id as c_embelishment_size_id,
                po_emb_service_item_qties.rate as order_rate,
                po_emb_service_item_qties.qty as order_qty,
                so_emb_items.rate as c_rate,
                so_emb_items.qty as c_qty,
                so_emb_items.gmt_style_ref,
                so_emb_items.gmt_sale_order_no,  
                so_uoms.code as so_uom_name,
                styles.style_ref,
                sales_orders.sale_order_no,

                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per,
                cumulatives.cumulative_qty
            '
    )
    ->leftJoin('so_emb_refs', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_emb_refs.id');
     //$join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->leftJoin('so_embs', function ($join) {
     $join->on('so_emb_refs.so_emb_id', '=', 'so_embs.id');
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
     $join->on('po_emb_service_items.id', '=', 'po_emb_service_item_qties.po_emb_service_item_id')
      ->whereNull('po_emb_service_items.deleted_at');
    })
    ->leftJoin('budget_embs', function ($join) {
     $join->on('budget_embs.id', '=', 'po_emb_service_items.budget_emb_id');
    })
    ->leftJoin('style_embelishments', function ($join) {
     $join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
    })
    ->leftJoin('gmtsparts', function ($join) {
     $join->on('gmtsparts.id', '=', 'style_embelishments.gmtspart_id');
    })
    ->leftJoin('embelishments', function ($join) {
     $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
    })
    ->leftJoin('embelishment_types', function ($join) {
     $join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
    })
    ->leftJoin('budget_emb_cons', function ($join) {
     $join->on('budget_emb_cons.id', '=', 'po_emb_service_item_qties.budget_emb_con_id')
      ->whereNull('budget_emb_cons.deleted_at');
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
    ->leftJoin('style_gmt_color_sizes', function ($join) {
     $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
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
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'style_colors.color_id');
    })
    ->leftJoin('so_emb_items', function ($join) {
     $join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_emb_items.uom_id');
    })
    ->leftJoin(\DB::raw("(SELECT so_emb_refs.id as sales_order_ref_id,sum(local_exp_pi_orders.qty) as cumulative_qty FROM local_exp_pi_orders right join so_emb_refs on so_emb_refs.id = local_exp_pi_orders.sales_order_ref_id 
            join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
              where  local_exp_pis.production_area_id in (45,50,51)  group by so_emb_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_emb_refs.id")
    ->join('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
    })
    ->where([['local_exp_pi_orders.id', '=', $id]])
    ->where([['local_exp_pi_orders.local_exp_pi_id', '=', $localexppi->id]])
    ->get()
    ->map(function ($rows) use ($gmtspart, $embelishmentsize, $embelishmenttype, $embelishment) {
     $rows->customer_sales_order = $rows->sales_order_no;
     $rows->emb_size = $embelishmentsize[$rows->embelishment_size_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->embelishment_type = $rows->embelishment_type_id ? $embelishmenttype[$rows->embelishment_type_id] : $embelishmenttype[$rows->c_embelishment_type_id];
     $rows->emb_size = $rows->embelishment_size_id ? $embelishmentsize[$rows->embelishment_size_id] : $embelishmentsize[$rows->c_embelishment_size_id];
     $rows->emb_name = $rows->embelishment_id ? $embelishment[$rows->embelishment_id] : $embelishment[$rows->c_embelishment_id];
     $rows->item_description = $rows->emb_name . ',' . $rows->emb_size . ',' . $rows->embelishment_type . ',' . $rows->gmtspart;
     $rows->amount = $rows->amount ? $rows->amount : $rows->c_amount;
     $rows->rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->so_uom_name ? $rows->so_uom_name : 'Pcs';

     $rows->sales_order_item_id = $rows->po_emb_service_item_qty_id ? $rows->po_emb_service_item_qty_id : $rows->so_emb_item_id;
     $rows->order_qty = $rows->order_qty ? $rows->order_qty : $rows->c_qty;
     $rows->order_rate = $rows->order_rate ? $rows->order_rate : $rows->c_rate;
     $rows->balance_qty = $rows->order_qty - $rows->cumulative_qty;
     $rows->tagable_amount = $rows->balance_qty * $rows->order_rate;
     $rows->net_amount = $rows->amount + $rows->discount_per;
     return $rows;
    })
    ->first();

   $row['fromData'] = $rows;
   $dropdown['att'] = '';
   $row['dropDown'] = $dropdown;
   echo json_encode($row);
  }
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function update(LocalExpPiOrderRequest $request, $id)
 {
  $localexppiorder = $this->localexppiorder->update($id, [
   'qty' => $request->qty,
   'discount_per' => $request->discount_per,
   'amount' => $request->amount
  ]);

  $totalQty = $this->localexppiorder
   ->join('local_exp_pis', function ($join) {
    $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
   })
   ->where([['local_exp_pis.id', '=', $request->local_exp_pi_id]])
   ->get([
    'local_exp_pi_orders.qty',
    'local_exp_pi_orders.amount',
   ]);
  $qty = $totalQty->sum('qty');
  $amount = $totalQty->sum('amount');

  $this->localexppi->where([['id', '=', $request->local_exp_pi_id]])->update(['qty' => $qty, 'amount' => $amount]);

  if ($localexppiorder) {
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
  if ($this->localexppiorder->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  } else {
   return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
  }
 }


 public function getInboundSaleOrder()
 {
  $localexppi = $this->localexppi->find(request('localexppiid', 0));
  $production_area_id = $localexppi->production_area_id;
  //Yarn Dyeing Sales Order
  if ($production_area_id == 5) {
   return;
  }
  //Knitting Sales Order
  elseif ($production_area_id == 10) {
   $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');

   $autoyarn = $this->autoyarn
    ->join('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->join('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->join('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
   $rows = $this->soknit
    ->selectRaw('
                so_knits.sales_order_no,
                so_knits.buyer_id,
                so_knits.company_id,
                so_knits.receive_date,
                so_knit_refs.id as sales_order_ref_id,
                so_knit_refs.so_knit_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_knit_service_item_qties.dia,
                po_knit_service_item_qties.measurment,
                po_knit_service_item_qties.qty,
                po_knit_service_item_qties.pcs_qty,
                po_knit_service_item_qties.rate,
                po_knit_service_item_qties.amount,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                so_knit_items.fabric_look_id as c_fabric_look_id,
                so_knit_items.fabric_shape_id as c_fabric_shape_id,
                so_knit_items.gmtspart_id as c_gmtspart_id,
                so_knit_items.gsm_weight as c_gsm_weight,
                so_knit_items.dia as c_dia,
                so_knit_items.measurment as c_measurment,
                so_knit_items.qty as c_qty,
                so_knit_items.rate as c_rate,
                so_knit_items.amount as c_amount,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_knit_items.gmt_style_ref,
                so_knit_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                so_color.name as c_fabric_color_name,
                po_color.name as fabric_color_name,
                customers.code as customer_code,
                salesorder_company.code as sales_company_code,
                cumulatives.cumulative_qty
            ')
    ->join('buyers as customers', function ($join) {
     $join->on('so_knits.buyer_id', '=', 'customers.id');
    })
    ->join('companies as salesorder_company', function ($join) {
     $join->on('so_knits.company_id', '=', 'salesorder_company.id');
    })
    ->leftJoin('sub_inb_marketings', function ($join) {
     $join->on('so_knits.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
    })
    ->join('so_knit_refs', function ($join) {
     $join->on('so_knit_refs.so_knit_id', '=', 'so_knits.id');
    })
    ->leftJoin('so_knit_pos', function ($join) {
     $join->on('so_knit_pos.so_knit_id', '=', 'so_knits.id');
    })
    ->leftJoin('so_knit_po_items', function ($join) {
     $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
    })
    ->leftJoin('po_knit_service_items', function ($join) {
     $join->on('po_knit_service_items.id', '=', 'po_knit_service_item_qties.po_knit_service_item_id')
      ->whereNull('po_knit_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_knit_service_item_qties.sales_order_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_knit_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_knit_items', function ($join) {
     $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_knit_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_knit_items.uom_id');
    })
    ->leftJoin('colors as so_color', function ($join) {
     $join->on('so_color.id', '=', 'so_knit_items.fabric_color_id');
    })
    ->leftJoin('colors as po_color', function ($join) {
     $join->on('po_color.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->leftJoin(\DB::raw("(SELECT
                so_knit_refs.id as sales_order_ref_id,
                sum(local_exp_pi_orders.qty) as cumulative_qty
                FROM local_exp_pi_orders  
                join so_knit_refs on so_knit_refs.id = local_exp_pi_orders.sales_order_ref_id 
                join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
              where  local_exp_pis.production_area_id=10  
            group by so_knit_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_knit_refs.id")
    ->when(request('sales_order_no'), function ($q) {
     return $q->where('so_knits.sales_order_no', 'LIKE', "%" . request('sales_order_no', 0) . "%");
    })
    ->when(request('company_id'), function ($q) {
     return $q->where('so_knits.company_id', '=', request('company_id', 0));
    })
    ->when(request('buyer_id'), function ($q) {
     return $q->where('so_knits.buyer_id', '=', request('buyer_id', 0));
    })
    ->where([['so_knits.company_id', '=', $localexppi->company_id]])
    ->where([['so_knits.buyer_id', '=', $localexppi->buyer_id]])
    ->where([['so_knits.currency_id', '=', $localexppi->currency_id]])
    ->orderBy('so_knit_refs.id', 'desc')
    ->get()
    ->map(function ($rows) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape, $uom) {
     //$rows->wo_so_no=$rows->so_knit_ref_id;
     $rows->customer_sales_order = $rows->sales_order_no;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->uom_id = $rows->uom_id ? $uom[$rows->uom_id] : '';
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;
     $rows->dia = $rows->dia ? $rows->dia : $rows->c_dia;
     $rows->measurment = $rows->measurment ? $rows->measurment : $rows->c_measurment;
     $rows->qty = $rows->qty ? $rows->qty : $rows->c_qty;
     $rows->pcs_qty = $rows->pcs_qty;
     $rows->rate = $rows->rate ? $rows->rate : $rows->c_rate;
     $rows->amount = $rows->amount ? $rows->amount : $rows->c_amount;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->uom_name ? $rows->uom_name : $rows->so_uom_name;
     $rows->fabric_color = $rows->fabric_color_name ? $rows->fabric_color_name : $rows->c_fabric_color_name;
     $rows->balance_qty = $rows->qty - $rows->cumulative_qty;
     //$rows->qty=number_format($rows->qty,2,'.',',');
     //$rows->pcs_qty=number_format($rows->pcs_qty,0,'.',',');
     //$rows->amount=number_format($rows->amount,2,'.',',');
     return $rows;
    });

   $notsaved = $rows->filter(function ($value) {
    if ($value->balance_qty > 0) {
     return $value;
    }
   })->values();
   echo json_encode($notsaved);
  }
  //Dyeing Sales Order
  elseif ($production_area_id == 20) {
   $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '-Select-', '');
   $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
   $autoyarn = $this->autoyarn
    ->join('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->join('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->join('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');


   $rows = $this->sodyeing
    ->selectRaw(
     '
                so_dyeings.sales_order_no,
                so_dyeings.buyer_id,
                so_dyeings.company_id,
                so_dyeings.receive_date,
                so_dyeing_refs.id as sales_order_ref_id,
                so_dyeing_refs.so_dyeing_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_dyeing_service_item_qties.fabric_color_id,
                po_dyeing_service_item_qties.colorrange_id,
                po_dyeing_service_item_qties.qty,
                po_dyeing_service_item_qties.pcs_qty,
                po_dyeing_service_item_qties.rate,
                po_dyeing_service_item_qties.amount,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.gmtspart_id as c_gmtspart_id,
                so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.colorrange_id as c_colorrange_id,
                so_dyeing_items.qty as c_qty,
                so_dyeing_items.rate as c_rate,
                so_dyeing_items.amount as c_amount,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_style_ref,
                so_dyeing_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                customers.code as customer_code,
                sales_company.code as sales_company_code,
                cumulatives.cumulative_qty
                '
    )
    ->leftJoin('buyers as customers', function ($join) {
     $join->on('so_dyeings.buyer_id', '=', 'customers.id');
    })
    ->leftJoin('companies as sales_company', function ($join) {
     $join->on('so_dyeings.company_id', '=', 'sales_company.id');
    })
    // ->leftJoin('currencies', function($join)  {
    //     $join->on('currencies.id', '=', 'so_dyeings.currency_id');
    // })
    // ->leftJoin('sub_inb_marketings', function($join)  {
    //     $join->on('so_dyeings.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
    // })
    ->join('so_dyeing_refs', function ($join) {
     $join->on('so_dyeing_refs.so_dyeing_id', '=', 'so_dyeings.id');
    })
    ->leftJoin('so_dyeing_pos', function ($join) {
     $join->on('so_dyeing_pos.so_dyeing_id', '=', 'so_dyeings.id');
    })
    ->leftJoin('so_dyeing_po_items', function ($join) {
     $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
    })
    ->leftJoin('po_dyeing_service_item_qties', function ($join) {
     $join->on('po_dyeing_service_item_qties.id', '=', 'so_dyeing_po_items.po_dyeing_service_item_qty_id');
    })
    ->leftJoin('po_dyeing_service_items', function ($join) {
     $join->on('po_dyeing_service_items.id', '=', 'po_dyeing_service_item_qties.po_dyeing_service_item_id')
      ->whereNull('po_dyeing_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_dyeing_service_item_qties.sales_order_id');
    })
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'po_dyeing_service_item_qties.fabric_color_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_dyeing_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_dyeing_items', function ($join) {
     $join->on('so_dyeing_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_dyeing_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_dyeing_items.uom_id');
    })
    ->leftJoin(\DB::raw("(SELECT so_dyeing_refs.id as sales_order_ref_id,sum(local_exp_pi_orders.qty) as cumulative_qty FROM local_exp_pi_orders right join so_dyeing_refs on so_dyeing_refs.id = local_exp_pi_orders.sales_order_ref_id 
            join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
              where  local_exp_pis.production_area_id=20  
            group by so_dyeing_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_dyeing_refs.id")
    ->when(request('sales_order_no'), function ($q) {
     return $q->where('so_dyeings.sales_order_no', 'LIKE', "%" . request('sales_order_no', 0) . "%");
    })
    ->when(request('company_id'), function ($q) {
     return $q->where('so_dyeings.company_id', '=', request('company_id', 0));
    })
    ->when(request('buyer_id'), function ($q) {
     return $q->where('so_dyeings.buyer_id', '=', request('buyer_id', 0));
    })
    ->where([['so_dyeings.company_id', '=', $localexppi->company_id]])
    ->where([['so_dyeings.buyer_id', '=', $localexppi->buyer_id]])
    ->where([['so_dyeings.currency_id', '=', $localexppi->currency_id]])
    ->orderBy('so_dyeing_refs.id', 'desc')
    ->get()
    ->map(function ($rows) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape, $uom, $colorrange, $color) {
     $rows->customer_sales_order = $rows->sales_order_no;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->uom_id = $rows->uom_id ? $uom[$rows->uom_id] : '';
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;

     $rows->fabric_color_id = $rows->fabric_color_id ? $color[$rows->fabric_color_id] : $color[$rows->c_fabric_color_id];
     $rows->colorrange_id = $rows->colorrange_id ? $colorrange[$rows->colorrange_id] : $colorrange[$rows->c_colorrange_id];

     $rows->qty = $rows->qty ? $rows->qty : $rows->c_qty;
     $rows->pcs_qty = $rows->pcs_qty;
     $rows->rate = $rows->rate ? $rows->rate : $rows->c_rate;
     $rows->amount = $rows->amount ? $rows->amount : $rows->c_amount;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->uom_name ? $rows->uom_name : $rows->so_uom_name;
     $rows->balance_qty = $rows->qty - $rows->cumulative_qty;
     $rows->qty = number_format($rows->qty, 2, '.', ',');
     $rows->pcs_qty = number_format($rows->pcs_qty, 0, '.', ',');
     $rows->amount = number_format($rows->amount, 2, '.', ',');
     return $rows;
    });
   $notsaved = $rows->filter(function ($value) {
    if ($value->balance_qty > 0) {
     return $value;
    }
   })->values();
   echo json_encode($notsaved);
  }
  //AOP Sales Order
  elseif ($production_area_id == 25) {
   $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '-Select-', '');
   $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
   $aoptype = array_prepend(array_pluck($this->embelishmenttype->getAopTypes(), 'name', 'id'), '-Select-', '');
   $autoyarn = $this->autoyarn
    ->leftJoin('autoyarnratios', function ($join) {
     $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('compositions', function ($join) {
     $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
    })
    ->when(request('construction_name'), function ($q) {
     return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
    })
    ->when(request('composition_name'), function ($q) {
     return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
    })
    ->orderBy('autoyarns.id', 'desc')
    ->get([
     'autoyarns.*',
     'constructions.name',
     'compositions.name as composition_name',
     'autoyarnratios.ratio'
    ]);

   $fabricDescriptionArr = array();
   $fabricCompositionArr = array();
   foreach ($autoyarn as $row) {
    $fabricDescriptionArr[$row->id] = $row->name;
    $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
   }
   $desDropdown = array();
   foreach ($fabricDescriptionArr as $key => $val) {
    $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');


   $rows = $this->soaop
    ->selectRaw(
     '
                so_aops.sales_order_no,
                so_aops.buyer_id,
                so_aops.company_id,
                so_aops.receive_date,
                so_aop_refs.id as sales_order_ref_id,
                so_aop_refs.so_aop_id,
                constructions.name as construction_name,
                po_aop_service_item_qties.fabric_color_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_aop_service_item_qties.budget_fabric_prod_con_id,
                po_aop_service_item_qties.colorrange_id,
                po_aop_service_item_qties.embelishment_type_id,
                po_aop_service_item_qties.coverage,
                po_aop_service_item_qties.impression,
                po_aop_service_item_qties.qty,
                po_aop_service_item_qties.rate,
                po_aop_service_item_qties.amount,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                so_aop_items.fabric_look_id as c_fabric_look_id,
                so_aop_items.fabric_shape_id as c_fabric_shape_id,
                so_aop_items.gmtspart_id as c_gmtspart_id,
                so_aop_items.gsm_weight as c_gsm_weight,
                so_aop_items.fabric_color_id as c_fabric_color_id,
                so_aop_items.colorrange_id as c_colorrange_id,
                so_aop_items.embelishment_type_id as c_embelishment_type_id,
                so_aop_items.coverage as c_coverage,
                so_aop_items.impression as c_impression,
                so_aop_items.qty as c_qty,
                so_aop_items.rate as c_rate,
                so_aop_items.amount as c_amount,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_aop_items.gmt_style_ref,
                so_aop_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                customers.code as customer_code,
                sales_company.code as sales_company_code,
                cumulatives.cumulative_qty
                '
    )
    ->leftJoin('buyers as customers', function ($join) {
     $join->on('so_aops.buyer_id', '=', 'customers.id');
    })
    ->leftJoin('companies as sales_company', function ($join) {
     $join->on('so_aops.company_id', '=', 'sales_company.id');
    })
    // ->leftJoin('currencies', function($join)  {
    //     $join->on('currencies.id', '=', 'so_dyeings.currency_id');
    // })
    // ->leftJoin('sub_inb_marketings', function($join)  {
    //     $join->on('so_dyeings.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
    // })
    ->join('so_aop_refs', function ($join) {
     $join->on('so_aop_refs.so_aop_id', '=', 'so_aops.id');
    })
    ->leftJoin('so_aop_pos', function ($join) {
     $join->on('so_aop_pos.so_aop_id', '=', 'so_aops.id');
    })
    ->leftJoin('so_aop_po_items', function ($join) {
     $join->on('so_aop_po_items.so_aop_ref_id', '=', 'so_aop_refs.id');
    })
    ->leftJoin('po_aop_service_item_qties', function ($join) {
     $join->on('po_aop_service_item_qties.id', '=', 'so_aop_po_items.po_aop_service_item_qty_id');
    })
    ->leftJoin('po_aop_service_items', function ($join) {
     $join->on('po_aop_service_items.id', '=', 'po_aop_service_item_qties.po_aop_service_item_id')
      ->whereNull('po_aop_service_items.deleted_at');
    })
    ->leftJoin('sales_orders', function ($join) {
     $join->on('sales_orders.id', '=', 'po_aop_service_item_qties.sales_order_id');
    })
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'po_aop_service_item_qties.fabric_color_id');
    })
    ->leftJoin('jobs', function ($join) {
     $join->on('jobs.id', '=', 'sales_orders.job_id');
    })
    ->leftJoin('styles', function ($join) {
     $join->on('styles.id', '=', 'jobs.style_id');
    })
    ->leftJoin('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_aop_service_items.budget_fabric_prod_id');
    })
    ->leftJoin('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->leftJoin('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->leftJoin('autoyarns', function ($join) {
     $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
    })
    ->leftJoin('constructions', function ($join) {
     $join->on('autoyarns.construction_id', '=', 'constructions.id');
    })
    ->leftJoin('so_aop_items', function ($join) {
     $join->on('so_aop_items.so_aop_ref_id', '=', 'so_aop_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_aop_items.gmt_buyer');
    })
    ->leftJoin('uoms', function ($join) {
     $join->on('uoms.id', '=', 'style_fabrications.uom_id');
    })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_aop_items.uom_id');
    })
    ->leftJoin(\DB::raw("(SELECT
            so_aop_refs.id as sales_order_ref_id,
            sum(local_exp_pi_orders.qty) as cumulative_qty 
           FROM
             local_exp_pi_orders
              right join so_aop_refs on so_aop_refs.id = local_exp_pi_orders.sales_order_ref_id  
              join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
              where  local_exp_pis.production_area_id=25
           group by so_aop_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_aop_refs.id")
    ->when(request('sales_order_no'), function ($q) {
     return $q->where('so_aops.sales_order_no', 'LIKE', "%" . request('sales_order_no', 0) . "%");
    })
    ->when(request('company_id'), function ($q) {
     return $q->where('so_aops.company_id', '=', request('company_id', 0));
    })
    ->when(request('buyer_id'), function ($q) {
     return $q->where('so_aops.buyer_id', '=', request('buyer_id', 0));
    })
    ->where([['so_aops.company_id', '=', $localexppi->company_id]])
    ->where([['so_aops.buyer_id', '=', $localexppi->buyer_id]])
    ->where([['so_aops.currency_id', '=', $localexppi->currency_id]])
    ->orderBy('so_aop_refs.id', 'desc')
    ->get()
    ->map(function ($rows) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape, $uom, $colorrange, $color, $aoptype) {
     $rows->customer_sales_order = $rows->sales_order_no;
     $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
     $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
     $rows->uom_id = $rows->uom_id ? $uom[$rows->uom_id] : '';
     $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;

     $rows->fabric_color_id = $rows->fabric_color_id ? $color[$rows->fabric_color_id] : $color[$rows->c_fabric_color_id];
     $rows->colorrange_id = $rows->colorrange_id ? $colorrange[$rows->colorrange_id] : $colorrange[$rows->c_colorrange_id];
     $rows->embelishment_type_id = $rows->embelishment_type_id ? $aoptype[$rows->embelishment_type_id] : $aoptype[$rows->c_embelishment_type_id];
     $rows->coverage = $rows->coverage ? $rows->coverage : $rows->c_coverage;
     $rows->impression = $rows->impression ? $rows->impression : $rows->c_impression;
     $rows->qty = $rows->qty ? $rows->qty : $rows->c_qty;
     $rows->rate = $rows->rate ? $rows->rate : $rows->c_rate;
     $rows->amount = $rows->amount ? $rows->amount : $rows->c_amount;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->uom_name ? $rows->uom_name : $rows->so_uom_name;
     $rows->balance_qty = $rows->qty - $rows->cumulative_qty;
     $rows->qty = number_format($rows->qty, 2, '.', ',');
     $rows->amount = number_format($rows->amount, 2, '.', ',');
     return $rows;
    });
   $notsaved = $rows->filter(function ($value) {
    if ($value->balance_qty > 0) {
     return $value;
    }
   })->values();
   echo json_encode($notsaved);
  }
  //Embelishment Work Order
  elseif ($production_area_id == 45 || $production_area_id == 50 || $production_area_id == 51) {
   $embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
   $embelishmenttype = array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(), 'name', 'id'), '', '');
   $rows = $this->soemb
    ->selectRaw(
     '
                so_embs.sales_order_no,
                so_embs.buyer_id,
                so_embs.company_id,
                so_embs.receive_date,
                so_emb_refs.id as sales_order_ref_id,
                so_emb_refs.so_emb_id,
                po_emb_service_item_qties.qty,
                po_emb_service_item_qties.rate,
                po_emb_service_item_qties.amount,
                gmtsparts.id as gmtspart_id,
                so_emb_items.gmtspart_id as c_gmtspart_id,
                so_emb_items.qty as c_qty,
                so_emb_items.rate as c_rate,
                so_emb_items.amount as c_amount,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_emb_items.gmt_style_ref,
                so_emb_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                
                so_uoms.code as so_uom_name,
                customers.code as customer_code,
                sales_company.code as sales_company_code,
                cumulatives.cumulative_qty
            '
    )
    ->leftJoin('buyers as customers', function ($join) {
     $join->on('so_embs.buyer_id', '=', 'customers.id');
    })
    ->leftJoin('companies as sales_company', function ($join) {
     $join->on('so_embs.company_id', '=', 'sales_company.id');
    })
    ->join('so_emb_refs', function ($join) {
     $join->on('so_emb_refs.so_emb_id', '=', 'so_embs.id');
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
     $join->on('po_emb_service_items.id', '=', 'po_emb_service_item_qties.po_emb_service_item_id')
      ->whereNull('po_emb_service_items.deleted_at');
    })
    ->leftJoin('budget_embs', function ($join) {
     $join->on('budget_embs.id', '=', 'po_emb_service_items.budget_emb_id');
    })
    ->leftJoin('style_embelishments', function ($join) {
     $join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
    })
    ->leftJoin('gmtsparts', function ($join) {
     $join->on('gmtsparts.id', '=', 'style_embelishments.gmtspart_id');
    })
    ->leftJoin('embelishments', function ($join) {
     $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
    })
    ->leftJoin('embelishment_types', function ($join) {
     $join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
    })
    ->leftJoin('budget_emb_cons', function ($join) {
     $join->on('budget_emb_cons.id', '=', 'po_emb_service_item_qties.budget_emb_con_id')
      ->whereNull('budget_emb_cons.deleted_at');
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
     $join->on('jobs.style_id', '=', 'styles.id');
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
    ->leftJoin('colors', function ($join) {
     $join->on('colors.id', '=', 'style_colors.color_id');
    })
    // ->join('countries',function($join){
    //     $join->on('countries.id','=','sales_order_countries.country_id');
    // })
    ->leftJoin('so_emb_items', function ($join) {
     $join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_emb_items.gmt_buyer');
    })
    // ->leftJoin('uoms',function($join){
    //     $join->on('uoms.id','=','style_fabrications.uom_id');
    // })
    ->leftJoin('uoms as so_uoms', function ($join) {
     $join->on('so_uoms.id', '=', 'so_emb_items.uom_id');
    })
    ->leftJoin(\DB::raw("(SELECT
                so_emb_refs.id as sales_order_ref_id,
                sum(local_exp_pi_orders.qty) as cumulative_qty 
            FROM
                local_exp_pi_orders
                right join so_emb_refs on so_emb_refs.id = local_exp_pi_orders.sales_order_ref_id  
                join local_exp_pis on local_exp_pis.id = local_exp_pi_orders.local_exp_pi_id 
                where  local_exp_pis.production_area_id in (45, 50, 51)
            group by so_emb_refs.id) cumulatives"), "cumulatives.sales_order_ref_id", "=", "so_emb_refs.id")
    ->when(request('sales_order_no'), function ($q) {
     return $q->where('so_embs.sales_order_no', 'LIKE', "%" . request('sales_order_no', 0) . "%");
    })
    ->when(request('company_id'), function ($q) {
     return $q->where('so_embs.company_id', '=', request('company_id', 0));
    })
    ->when(request('buyer_id'), function ($q) {
     return $q->where('so_embs.buyer_id', '=', request('buyer_id', 0));
    })
    ->where([['so_embs.company_id', '=', $localexppi->company_id]])
    ->where([['so_embs.buyer_id', '=', $localexppi->buyer_id]])
    ->where([['so_embs.currency_id', '=', $localexppi->currency_id]])
    ->orderBy('so_emb_refs.id', 'desc')
    ->get()
    ->map(function ($rows) use ($gmtspart, $embelishmentsize, $embelishmenttype) {
     $rows->customer_sales_order = $rows->sales_order_no;
     $rows->emb_size = $embelishmentsize[$rows->embelishment_size_id];
     $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
     $rows->embelishment_type_id = $rows->embelishment_type_id ? $embelishmenttype[$rows->embelishment_type_id] : $embelishmenttype[$rows->c_embelishment_type_id];
     $rows->qty = $rows->qty ? $rows->qty : $rows->c_qty;
     $rows->rate = $rows->rate ? $rows->rate : $rows->c_rate;
     $rows->amount = $rows->amount ? $rows->amount : $rows->c_amount;
     $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
     $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
     $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
     $rows->uom_code = $rows->so_uom_name ? $rows->so_uom_name : 'Pcs';
     $rows->balance_qty = $rows->qty - $rows->cumulative_qty;
     $rows->qty = number_format($rows->qty, 2, '.', ',');
     $rows->amount = number_format($rows->amount, 2, '.', ',');
     return $rows;
    });
   $notsaved = $rows->filter(function ($value) {
    if ($value->balance_qty > 0) {
     return $value;
    }
   })->values();
   echo json_encode($notsaved);
  }
 }
}
