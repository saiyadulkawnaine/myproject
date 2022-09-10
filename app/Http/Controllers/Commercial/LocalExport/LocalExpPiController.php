<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;

use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiOrderRepository;
use App\Http\Requests\Commercial\LocalExport\LocalExpPiRequest;

class LocalExpPiController extends Controller
{

 private $localexppi;
 private $buyer;
 private $salesorder;
 private $company;
 private $itemaccount;
 private $termscondition;
 private $localexppiorder;
 private $currency;
 private $embelishment;
 private $embelishmenttype;
 private $size;


 public function __construct(
  LocalExpPiRepository $localexppi,
  BuyerRepository $buyer,
  ItemAccountRepository $itemaccount,
  SalesOrderRepository $salesorder,
  CompanyRepository $company,
  TermsConditionRepository $termscondition,
  PurchaseTermsConditionRepository $purchasetermscondition,
  LocalExpPiOrderRepository $localexppiorder,
  SoKnitRepository $soknit,
  SoDyeingRepository $sodyeing,
  GmtspartRepository $gmtspart,
  AutoyarnRepository $autoyarn,
  UomRepository $uom,
  ColorrangeRepository $colorrange,
  CurrencyRepository $currency,
  ColorRepository $color,
  EmbelishmentTypeRepository $embelishmenttype,
  EmbelishmentRepository $embelishment,
  SizeRepository $size


 ) {
  $this->localexppi = $localexppi;
  $this->localexppiorder = $localexppiorder;
  $this->buyer = $buyer;
  $this->salesorder = $salesorder;
  $this->company = $company;
  $this->itemaccount = $itemaccount;
  $this->termscondition = $termscondition;
  $this->purchasetermscondition = $purchasetermscondition;
  $this->soknit = $soknit;
  $this->sodyeing = $sodyeing;
  $this->autoyarn = $autoyarn;
  $this->gmtspart = $gmtspart;
  $this->uom = $uom;
  $this->colorrange = $colorrange;
  $this->color = $color;
  $this->currency = $currency;
  $this->embelishmenttype = $embelishmenttype;
  $this->embelishment = $embelishment;
  $this->size = $size;

  $this->middleware('auth');
  // $this->middleware('permission:view.localexppis',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.localexppis', ['only' => ['store']]);
  // $this->middleware('permission:edit.localexppis',   ['only' => ['update']]);
  // $this->middleware('permission:delete.localexppis', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  /* $exppi=$this->exppi->where([['company_id','=',2]])->orderBy('id')->get();
       $i=1;
      foreach($exppi as $row){
        $exppiorder=$this->exppi->update($row->id,[
            'sys_pi_no'=>$i
        ]);
       $i++;
      }*/
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
  $payterm = array_prepend(config('bprs.payterm'), '-Select-', '');
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $localexppis = array();
  $rows = $this->localexppi
   ->when(request('advise_bank'), function ($q) {
    return $q->where('local_exp_pis.advise_bank', 'like', '%' . request('advise_bank', 0) . '%');
   })
   ->orderBy('id', 'desc')
   ->take(500)
   ->get();
  foreach ($rows as $row) {
   $localexppi['id'] = $row->id;
   $localexppi['company_id'] = isset($company[$row->company_id]) ? $company[$row->company_id] : '';
   $localexppi['pi_no'] = $row->pi_no;
   $localexppi['sys_pi_no'] = $row->sys_pi_no;
   $localexppi['buyer_id'] = isset($buyer[$row->buyer_id]) ? $buyer[$row->buyer_id] : '';
   $localexppi['pi_validity_days'] = $row->pi_validity_days;
   $localexppi['pi_date'] = date('d-M-Y', strtotime($row->pi_date));
   $localexppi['pay_term_id'] = isset($payterm[$row->pay_term_id]) ? $payterm[$row->pay_term_id] : '';
   $localexppi['production_area_id'] = isset($productionarea[$row->production_area_id]) ? $productionarea[$row->production_area_id] : '';
   $localexppi['tenor'] = $row->tenor;
   $localexppi['delivery_date'] = date('d-M-Y', strtotime($row->delivery_date));
   $localexppi['delivery_place'] = $row->delivery_place;
   $localexppi['hs_code'] = $row->hs_code;
   $localexppi['remarks'] = $row->remarks;
   array_push($localexppis, $localexppi);
  }
  echo json_encode($localexppis);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $currency = array_prepend(array_pluck($this->currency->get(), 'code', 'id'), '-Select-', '');
  $salesorder = array_prepend(array_pluck($this->salesorder->get(), 'job_no', 'id'), '-Select-', '');
  $localexppi = array_prepend(array_pluck($this->localexppi->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
  $payterm = array_prepend(config('bprs.payterm'), '-Select-', '');
  $incoterm = array_prepend(config('bprs.incoterm'), '-Select-', '');
  $productionarea = array_prepend(array_only(config('bprs.productionarea'), [5, 10, 20, 25, 45, 50]), '-Select-', '');
  $aoptype = array_prepend(array_pluck($this->embelishmenttype->getAopTypes(), 'name', 'id'), '-Select-', '');
  return Template::loadView('Commercial.LocalExport.LocalExpPi', ['buyer' => $buyer, 'payterm' => $payterm, 'incoterm' => $incoterm, 'localexppi' => $localexppi, 'salesorder' => $salesorder, 'company' => $company, 'productionarea' => $productionarea, 'currency' => $currency, 'aoptype' => $aoptype]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(LocalExpPiRequest $request)
 {
  $max = $this->localexppi->where([['company_id', $request->company_id]])->max('sys_pi_no');
  $sys_pi_no = $max + 1;
  $request->request->add(['sys_pi_no' => $sys_pi_no]);
  $localexppi = $this->localexppi->create($request->except(['id']));
  $termscondition = $this->termscondition->where([['menu_id', '=', 110]])->orderBy('sort_id')->get();
  foreach ($termscondition as $row) {
   $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id' => $localexppi->id, 'term' => $row->term, 'sort_id' => $row->sort_id, 'menu_id' => 110]);
  }
  if ($localexppi) {
   return response()->json(array('success' => true, 'id' => $localexppi->id, 'message' => 'Save Successfully'), 200);
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
  $localexppi = $this->localexppi->find($id);
  $row['fromData'] = $localexppi;
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
 public function update(LocalExpPiRequest $request, $id)
 {
  $localexppi = $this->localexppi->update($id, $request->except(['id', 'sys_pi_no', 'company_id', 'buyer_id'/* ,'currency_id' */]));

  if ($localexppi) {
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
  if ($this->localexppi->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  } else {
   return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
  }
 }

 public function getAdviseBank(Request $request)
 {
  return $this->localexppi
   ->where([['advise_bank', 'LIKE', '%' . $request->q . '%']])
   ->distinct()
   ->orderBy('advise_bank', 'asc')
   ->get(['advise_bank as name']);
 }

 public function localPiPdf()
 {
  $id = request('id', 0);
  $payterm = array_prepend(config('bprs.payterm'), '-Select-', '');
  $currency = array_prepend(array_pluck($this->currency->get(), 'code', 'id'), '-Select-', '');
  $productionarea = array_prepend(array_only(config('bprs.productionarea'), [5, 10, 20, 25, 45, 50]), '-Select-', '');
  $rows = $this->localexppi
   ->leftJoin('buyers as customers', function ($join) {
    $join->on('local_exp_pis.buyer_id', '=', 'customers.id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyer_branches.buyer_id', '=', 'customers.id');
   })
   ->leftJoin('companies as salesorder_company', function ($join) {
    $join->on('local_exp_pis.company_id', '=', 'salesorder_company.id');
   })
   ->where([['local_exp_pis.id', '=', $id]])
   ->orderBy('local_exp_pis.id', 'desc')
   ->get([
    'local_exp_pis.*',
    'customers.name as customer_name',
    'salesorder_company.name as salecompany_name',
    'salesorder_company.logo as logo',
    'salesorder_company.address as company_address',
    'salesorder_company.vat_number',
    'buyer_branches.contact_person',
    'buyer_branches.address as customer_address',
   ]);
  foreach ($rows as $row) {
   $localexppi['id'] = $row->id;
   $localexppi['pi_no'] = $row->pi_no;
   $localexppi['sys_pi_no'] = $row->sys_pi_no;

   if ($row->production_area_id == 20) {
    $localexppi['production_area_id'] = "Dyeing/Finishing";
   } else {
    $localexppi['production_area_id'] = $productionarea[$row->production_area_id];
   }
   $localexppi['customer_name'] = $row->customer_name;
   $localexppi['pi_validity_days'] = $row->pi_validity_days;
   $localexppi['pi_date'] = date('d-M-Y', strtotime($row->pi_date));
   $localexppi['pay_term_id'] = isset($payterm[$row->pay_term_id]) ? $payterm[$row->pay_term_id] : '';
   $localexppi['tenor'] = $row->tenor;
   $localexppi['logo'] = $row->logo;
   $localexppi['company_address'] = $row->company_address;
   $localexppi['delivery_date'] = date('d-M-Y', strtotime($row->delivery_date));
   $localexppi['delivery_place'] = $row->delivery_place;
   $localexppi['advise_bank'] = $row->advise_bank;
   $localexppi['account_no'] = $row->account_no;
   $localexppi['swift_code'] = $row->swift_code;
   $localexppi['lc_negotiable'] = $row->lc_negotiable;
   $localexppi['overdue'] = $row->overdue;
   $localexppi['maturity_date'] = $row->maturity_date;
   $localexppi['partial_delivery'] = $row->partial_delivery;
   $localexppi['tolerance'] = $row->tolerance;
   $localexppi['contact_person'] = $row->contact_person;
   $localexppi['customer_address'] = $row->customer_address;
   $localexppi['vat_number'] = $row->vat_number;
   $localexppi['hs_code'] = $row->hs_code;
   $localexppi['currency_id'] = isset($currency[$row->currency_id]) ? $currency[$row->currency_id] : '';

   $localexppi['remarks'] = $row->remarks;
  }
  $localexppi['master'] = $rows;

  // $orders=$this->localexppiorder->get();


  $localexppis = $this->localexppi->find($id);
  $production_area_id = $localexppis->production_area_id;
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
    $desDropdown[$key] = $val . "," . implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');


   $soknit = $this->soknit
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
    ->leftJoin('po_knit_services', function ($join) {
     $join->on('po_knit_services.id', '=', 'po_knit_service_items.po_knit_service_id');
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
    ->leftJoin('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->where([['local_exp_pis.id', '=', $localexppis->id]])
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
                so_knit_items.gsm_weight as c_gsm_weight,
                so_knit_items.currency_id,
                so_color.name as c_fabric_color_name,
                po_color.name as fabric_color_name,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                local_exp_pi_orders.id,
                local_exp_pi_orders.local_exp_pi_id,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_pi_orders.qty,
                local_exp_pi_orders.amount,
                local_exp_pi_orders.discount_per 
            ')
    ->orderBy('local_exp_pi_orders.id', 'desc')
    ->get()
    ->map(function ($soknit) use ($desDropdown, $fabriclooks, $fabricshape) {
     $soknit->sales_order_item_id = $soknit->po_knit_service_item_qty_id ? $soknit->po_knit_service_item_qty_id : $soknit->so_knit_item_id;
     $soknit->fabrication = $soknit->autoyarn_id ? $desDropdown[$soknit->autoyarn_id] : $desDropdown[$soknit->c_autoyarn_id];
     $soknit->fabriclooks = $soknit->fabric_look_id ? $fabriclooks[$soknit->fabric_look_id] : $fabriclooks[$soknit->c_fabric_look_id];
     $soknit->fabricshape = $soknit->fabric_shape_id ? $fabricshape[$soknit->fabric_shape_id] : $fabricshape[$soknit->c_fabric_shape_id];
     $soknit->fabric_shape_id = $soknit->fabric_shape_id ? $soknit->fabric_shape_id : $soknit->c_fabric_shape_id;
     $soknit->gsm_weight = $soknit->gsm_weight ? $soknit->gsm_weight : $soknit->c_gsm_weight;
     $soknit->order_qty = $soknit->order_qty ? $soknit->order_qty : $soknit->c_qty;
     $soknit->order_rate = $soknit->order_rate ? $soknit->order_rate : $soknit->c_rate;

     $soknit->Custom_style_ref = $soknit->style_ref ? $soknit->style_ref : $soknit->gmt_style_ref;
     $soknit->Custom_sale_order_no = $soknit->sale_order_no ? $soknit->sale_order_no : $soknit->gmt_sale_order_no;

     $soknit->fabric_color = $soknit->fabric_color_name ? $soknit->fabric_color_name : $soknit->c_fabric_color_name;
     $soknit->uom_code = $soknit->uom_name ? $soknit->uom_name : $soknit->so_uom_name;
     $soknit->item_description = $soknit->fabrication . ',' . $soknit->fabriclooks . ',' . $soknit->fabricshape . ',' . $soknit->gsm_weight . ',' . $soknit->fabric_color;
     $soknit->balance_qty = $soknit->order_qty - $soknit->cumulative_qty;
     $soknit->tagable_amount = $soknit->balance_qty * $soknit->order_rate;
     return $soknit;
    });

   $orders = $soknit;
   //echo json_encode($orders);

  }
  if ($production_area_id == 20) {
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
    $desDropdown[$key] = $val . "," . implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');

   $sodyeing = $this->localexppiorder
    ->leftJoin('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->leftJoin('so_dyeing_refs', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_dyeing_refs.id');
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
    ->leftJoin('po_dyeing_services', function ($join) {
     $join->on('po_dyeing_services.id', '=', 'po_dyeing_service_items.po_dyeing_service_id');
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
    ->leftJoin(\DB::raw("(SELECT
            so_dyeing_refs.id as so_dyeing_ref_id,
                sum(so_dyeing_items.qty) as cumulative_qty
                FROM so_dyeing_items  
                join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_items.so_dyeing_ref_id 
            group by so_dyeing_refs.id) cumulatives"), "cumulatives.so_dyeing_ref_id", "=", "so_dyeing_refs.id")
    ->where([['local_exp_pis.id', '=', $localexppis->id]])
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
                style_fabrications.dyeing_type_id,
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
     'local_exp_pi_orders.local_exp_pi_id',
     'local_exp_pi_orders.sales_order_ref_id',
     'local_exp_pi_orders.qty',
     'local_exp_pi_orders.amount',
     'local_exp_pi_orders.discount_per',
     'local_exp_pi_orders.id'
    ])
    ->get()
    ->map(function ($sodyeing) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape, $uom, $colorrange, $color, $dyetype) {
     $sodyeing->customer_sales_order = $sodyeing->sales_order_no;
     $sodyeing->fabrication = $sodyeing->autoyarn_id ? $desDropdown[$sodyeing->autoyarn_id] : $desDropdown[$sodyeing->c_autoyarn_id];
     $sodyeing->gmtspart = $sodyeing->gmtspart_id ? $gmtspart[$sodyeing->gmtspart_id] : $gmtspart[$sodyeing->c_gmtspart_id];
     $sodyeing->fabriclooks = $sodyeing->fabric_look_id ? $fabriclooks[$sodyeing->fabric_look_id] : $fabriclooks[$sodyeing->c_fabric_look_id];
     $sodyeing->fabricshape = $sodyeing->fabric_shape_id ? $fabricshape[$sodyeing->fabric_shape_id] : $fabricshape[$sodyeing->c_fabric_shape_id];
     $sodyeing->uom_id = $sodyeing->uom_id ? $uom[$sodyeing->uom_id] : '';
     $sodyeing->gsm_weight = $sodyeing->gsm_weight ? $sodyeing->gsm_weight : $sodyeing->c_gsm_weight;

     $sodyeing->fabric_color_name = $sodyeing->fabric_color_id ? $color[$sodyeing->fabric_color_id] : $color[$sodyeing->c_fabric_color_id];
     $sodyeing->colorrange_id = $sodyeing->colorrange_id ? $colorrange[$sodyeing->colorrange_id] : $colorrange[$sodyeing->c_colorrange_id];

     $sodyeing->order_qty = $sodyeing->order_qty ? $sodyeing->order_qty : $sodyeing->c_qty;
     $sodyeing->pcs_qty = $sodyeing->pcs_qty;
     $sodyeing->order_rate = $sodyeing->order_rate ? $sodyeing->order_rate : $sodyeing->c_rate;

     $sodyeing->Custom_style_ref = $sodyeing->style_ref ? $sodyeing->style_ref : $sodyeing->gmt_style_ref;
     $sodyeing->Custom_buyer_name = $sodyeing->buyer_name ? $sodyeing->buyer_name : $sodyeing->gmt_buyer_name;
     $sodyeing->Custom_sale_order_no = $sodyeing->sale_order_no ? $sodyeing->sale_order_no : $sodyeing->gmt_sale_order_no;

     $sodyeing->dye_aop_type = $sodyeing->dyeing_type_id ? $dyetype[$sodyeing->dyeing_type_id] : $dyetype[$sodyeing->c_dyeing_type_id];

     $sodyeing->uom_code = $sodyeing->uom_name ? $sodyeing->uom_name : $sodyeing->so_uom_name;
     $sodyeing->item_description = $sodyeing->fabrication . ',' . $sodyeing->fabriclooks . ',' . $sodyeing->fabricshape . ',' . $sodyeing->gsm_weight . ',' . $sodyeing->fabric_color_name;

     return $sodyeing;
    });
   $orders = $sodyeing;
   //echo json_encode($rows);
  }
  //Aop
  if ($production_area_id == 25) {
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
    $desDropdown[$key] = $val . "," . implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');

   $soaop = $this->localexppiorder
    ->leftJoin('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->leftJoin('so_aop_refs', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_aop_refs.id');
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
    ->leftJoin('po_aop_services', function ($join) {
     $join->on('po_aop_services.id', '=', 'po_aop_service_items.po_aop_service_id');
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
    ->where([['local_exp_pis.id', '=', $localexppis->id]])

    ->selectRaw(
     '
                po_aop_service_item_qties.id as po_aop_service_item_qty_id,
                so_aop_items.id as so_aop_item_id,
                so_aops.id as so_aop_id_sc,
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

                po_aop_service_item_qties.rate  as order_rate,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                so_aop_items.fabric_look_id as c_fabric_look_id,
                so_aop_items.fabric_shape_id as c_fabric_shape_id,
                so_aop_items.gmtspart_id as c_gmtspart_id,
                so_aop_items.gsm_weight as c_gsm_weight,
                so_aop_items.fabric_color_id as c_fabric_color_id,
                so_aop_items.colorrange_id as c_colorrange_id,
                po_aop_service_item_qties.embelishment_type_id,
                so_aop_items.embelishment_type_id as c_embelishment_type_id,
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
    ->map(function ($soaop) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape, $uom, $colorrange, $color, $aoptype) {
     $soaop->customer_sales_order = $soaop->sales_order_no;
     $soaop->fabrication = $soaop->autoyarn_id ? $desDropdown[$soaop->autoyarn_id] : $desDropdown[$soaop->c_autoyarn_id];
     $soaop->gmtspart = $soaop->gmtspart_id ? $gmtspart[$soaop->gmtspart_id] : $gmtspart[$soaop->c_gmtspart_id];
     $soaop->fabriclooks = $soaop->fabric_look_id ? $fabriclooks[$soaop->fabric_look_id] : $fabriclooks[$soaop->c_fabric_look_id];
     $soaop->fabricshape = $soaop->fabric_shape_id ? $fabricshape[$soaop->fabric_shape_id] : $fabricshape[$soaop->c_fabric_shape_id];
     $soaop->uom_id = $soaop->uom_id ? $uom[$soaop->uom_id] : '';
     $soaop->gsm_weight = $soaop->gsm_weight ? $soaop->gsm_weight : $soaop->c_gsm_weight;

     $soaop->fabric_color_name = $soaop->fabric_color_id ? $color[$soaop->fabric_color_id] : $color[$soaop->c_fabric_color_id];
     $soaop->colorrange_id = $soaop->colorrange_id ? $colorrange[$soaop->colorrange_id] : $colorrange[$soaop->c_colorrange_id];

     $soaop->order_qty = $soaop->order_qty ? $soaop->order_qty : $soaop->c_qty;
     $soaop->order_rate = $soaop->order_rate ? $soaop->order_rate : $soaop->c_rate;

     $soaop->Custom_style_ref = $soaop->style_ref ? $soaop->style_ref : $soaop->gmt_style_ref;
     $soaop->Custom_buyer_name = $soaop->buyer_name ? $soaop->buyer_name : $soaop->gmt_buyer_name;
     $soaop->Custom_sale_order_no = $soaop->sale_order_no ? $soaop->sale_order_no : $soaop->gmt_sale_order_no;

     $soaop->dye_aop_type = $soaop->embelishment_type_id ? $aoptype[$soaop->embelishment_type_id] : $aoptype[$soaop->c_embelishment_type_id];

     $soaop->uom_code = $soaop->uom_name ? $soaop->uom_name : $soaop->so_uom_name;
     $soaop->item_description = $soaop->fabrication . ',' . $soaop->fabriclooks . ',' . $soaop->fabricshape . ',' . $soaop->gsm_weight . ',' . $soaop->fabric_color_name;

     return $soaop;
    });
   $orders = $soaop;
  }
  //Embelishment Type
  if ($production_area_id == 45 || $production_area_id == 50 || $production_area_id == 51) {
   $embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
   $embelishmenttype = array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(), 'name', 'id'), '', '');
   $embelishment = array_prepend(array_pluck($this->embelishment->get(), 'name', 'id'), '', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '', '');
   $size = array_prepend(array_pluck($this->size->get(), 'name', 'id'), '', '');

   $soemb = $this->localexppiorder
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
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
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
    ->leftJoin('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
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
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_emb_items.gmt_buyer');
    })
    ->where([['local_exp_pis.id', '=', $localexppis->id]])
    ->orderBy('local_exp_pi_orders.id', 'desc')
    ->get()
    ->map(function ($soemb) use ($gmtspart, $embelishmentsize, $embelishmenttype, $embelishment, $color, $size) {
     $soemb->customer_sales_order = $soemb->sales_order_no;
     $soemb->emb_size = $embelishmentsize[$soemb->embelishment_size_id];
     $soemb->gmtspart = $soemb->gmtspart_id ? $gmtspart[$soemb->gmtspart_id] : $gmtspart[$soemb->c_gmtspart_id];

     $soemb->emb_size = $soemb->embelishment_size_id ? $embelishmentsize[$soemb->embelishment_size_id] : $embelishmentsize[$soemb->c_embelishment_size_id];
     $soemb->emb_name = $soemb->embelishment_id ? $embelishment[$soemb->embelishment_id] : $embelishment[$soemb->c_embelishment_id];
     $soemb->gmt_color = $soemb->gmt_color ? $soemb->gmt_color : $color[$soemb->c_color_id];
     $soemb->gmt_size = $soemb->gmt_size ? $soemb->gmt_size : $size[$soemb->c_size_id];
     $soemb->item_description = $soemb->item_description . ',' . $soemb->emb_name . ',' . $soemb->emb_size . ',' . $soemb->gmtspart . ',' . $soemb->gmt_color . ',' . $soemb->gmt_size;
     $soemb->amount = $soemb->amount ? $soemb->amount : $soemb->c_amount;
     $soemb->net_amount = $soemb->amount + $soemb->discount_per;
     $soemb->order_rate = $soemb->order_rate ? $soemb->order_rate : $soemb->c_rate;
     $soemb->Custom_style_ref = $soemb->style_ref ? $soemb->style_ref : $soemb->gmt_style_ref;
     $soemb->Custom_buyer_name = $soemb->buyer_name ? $soemb->buyer_name : $soemb->gmt_buyer_name;
     $soemb->Custom_sale_order_no = $soemb->sale_order_no ? $soemb->sale_order_no : $soemb->gmt_sale_order_no;

     $soemb->dye_aop_type = $soemb->embelishment_type_id ? $embelishmenttype[$soemb->embelishment_type_id] : $embelishmenttype[$soemb->c_embelishment_type_id];
     $soemb->sale_order_no = $soemb->sale_order_no ? $soemb->sale_order_no : $soemb->gmt_sale_order_no;
     $soemb->uom_code = $soemb->so_uom_name ? $soemb->so_uom_name : 'Pcs';

     $soemb->sales_order_item_id = $soemb->po_emb_service_item_qty_id ? $soemb->po_emb_service_item_qty_id : $soemb->so_emb_item_id;
     $soemb->order_rate = $soemb->order_rate ? $soemb->order_rate : $soemb->c_rate;
     return $soemb;
    });
   $orders = $soemb;
  }
  $amount = $orders->sum('amount');
  $currency = $localexppi['currency_id'];
  $inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $currency, 'cents');
  $localexppi['inword'] = $inword;

  $purchasetermscondition = $this->purchasetermscondition->where([['purchase_order_id', '=', $id]])->where([['menu_id', '=', 110]])->orderBy('sort_id')->get();
  $localexppi['purchasetermscondition'] = $purchasetermscondition;

  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $barcodestyle = array(
   'position' => '',
   'align' => 'C',
   'stretch' => false,
   'fitwidth' => true,
   'cellfitalign' => '',
   'border' => false,
   'hpadding' => 'auto',
   'vpadding' => 'auto',
   'fgcolor' => array(0, 0, 0),
   'bgcolor' => false, //array(255,255,255),
   'text' => true,
   'font' => 'helvetica',
   'fontsize' => 8,
   'stretchtext' => 4
  );
  $pdf->SetY(10);
  $pdf->SetX(150);
  $challan = str_pad($localexppi['id'], 10, 0, STR_PAD_LEFT);
  $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
  $pdf->SetY(10);
  $image_file = 'images/logo/' . $localexppi['logo'];
  $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  $pdf->SetY(13);
  $pdf->SetFont('helvetica', 'N', 9);
  $pdf->Cell(0, 40, $localexppi['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M');
  //$pdf->Text(68, 16, $localexppi['company_address']);
  //$pdf->SetY(16);
  $pdf->SetFont('helvetica', '', 8);
  $view = \View::make('Defult.Commercial.LocalExport.LocalExpPiPdf', ['localexppi' => $localexppi, 'orders' => $orders]);
  $html_content = $view->render();
  $pdf->SetY(36);

  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/LocalExpPiPdf.pdf';
  $pdf->output($filename, 'I');
  exit();
 }

 public function localShortPiPdf()
 {
  $id = request('id', 0);
  $payterm = array_prepend(config('bprs.payterm'), '-Select-', '');
  $currency = array_prepend(array_pluck($this->currency->get(), 'code', 'id'), '-Select-', '');
  $productionarea = array_prepend(array_only(config('bprs.productionarea'), [5, 10, 20, 25, 45, 50]), '-Select-', '');
  $rows = $this->localexppi
   ->leftJoin('buyers as customers', function ($join) {
    $join->on('local_exp_pis.buyer_id', '=', 'customers.id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyer_branches.buyer_id', '=', 'customers.id');
   })
   ->leftJoin('companies as salesorder_company', function ($join) {
    $join->on('local_exp_pis.company_id', '=', 'salesorder_company.id');
   })
   ->where([['local_exp_pis.id', '=', $id]])
   ->orderBy('local_exp_pis.id', 'desc')
   ->get([
    'local_exp_pis.*',
    'customers.name as customer_name',
    'salesorder_company.name as salecompany_name',
    'salesorder_company.logo as logo',
    'salesorder_company.address as company_address',
    'salesorder_company.vat_number',
    'buyer_branches.contact_person',
    'buyer_branches.address as customer_address',
   ]);
  foreach ($rows as $row) {
   $localexppi['id'] = $row->id;
   $localexppi['pi_no'] = $row->pi_no;
   $localexppi['sys_pi_no'] = $row->sys_pi_no;

   if ($row->production_area_id == 20) {
    $localexppi['production_area_id'] = "Dyeing/Finishing";
   } else {
    $localexppi['production_area_id'] = $productionarea[$row->production_area_id];
   }
   $localexppi['customer_name'] = $row->customer_name;
   $localexppi['pi_validity_days'] = $row->pi_validity_days;
   $localexppi['pi_date'] = date('d-M-Y', strtotime($row->pi_date));
   $localexppi['pay_term_id'] = isset($payterm[$row->pay_term_id]) ? $payterm[$row->pay_term_id] : '';
   $localexppi['tenor'] = $row->tenor;
   $localexppi['logo'] = $row->logo;
   $localexppi['company_address'] = $row->company_address;
   $localexppi['delivery_date'] = date('d-M-Y', strtotime($row->delivery_date));
   $localexppi['delivery_place'] = $row->delivery_place;
   $localexppi['advise_bank'] = $row->advise_bank;
   $localexppi['account_no'] = $row->account_no;
   $localexppi['swift_code'] = $row->swift_code;
   $localexppi['lc_negotiable'] = $row->lc_negotiable;
   $localexppi['overdue'] = $row->overdue;
   $localexppi['maturity_date'] = $row->maturity_date;
   $localexppi['partial_delivery'] = $row->partial_delivery;
   $localexppi['tolerance'] = $row->tolerance;
   $localexppi['contact_person'] = $row->contact_person;
   $localexppi['customer_address'] = $row->customer_address;
   $localexppi['vat_number'] = $row->vat_number;
   $localexppi['hs_code'] = $row->hs_code;
   $localexppi['currency_id'] = isset($currency[$row->currency_id]) ? $currency[$row->currency_id] : '';

   $localexppi['remarks'] = $row->remarks;
  }
  $localexppi['master'] = $rows;

  // $orders=$this->localexppiorder->get();


  $localexppis = $this->localexppi->find($id);
  $production_area_id = $localexppis->production_area_id;
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
    $desDropdown[$key] = $val . "," . implode(",", $fabricCompositionArr[$key]);
   }


   $soknit = $this->soknit
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
    ->leftJoin('po_knit_services', function ($join) {
     $join->on('po_knit_services.id', '=', 'po_knit_service_items.po_knit_service_id');
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
    ->leftJoin('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->where([['local_exp_pis.id', '=', $localexppis->id]])
    ->selectRaw('
                so_knits.sales_order_no as knitting_sales_order,
                style_fabrications.autoyarn_id,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_knit_items.gmt_style_ref,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sum(po_knit_service_item_qties.qty) as order_qty,
                avg(po_knit_service_item_qties.rate) as order_rate,
                sum(po_knit_service_item_qties.amount) as order_amount,
                sum(so_knit_items.qty) as c_qty,
                avg(so_knit_items.rate) as c_rate,
                cumulatives.cumulative_qty,
                sum(local_exp_pi_orders.qty) as qty,
                sum(local_exp_pi_orders.amount) as amount
            ')
    //->orderBy('style_fabrications.autoyarn_id','desc')
    ->groupBy([
     'so_knits.sales_order_no',
     'style_fabrications.autoyarn_id',
     'styles.style_ref',
     'sales_orders.sale_order_no',
     'so_knit_items.gmt_style_ref',
     'so_knit_items.gmt_sale_order_no',
     'so_knit_items.autoyarn_id',
     'uoms.code',
     'so_uoms.code',
     'cumulatives.cumulative_qty',
    ])
    ->get()
    ->map(function ($soknit) use ($desDropdown) {
     $soknit->fabrication = $soknit->autoyarn_id ? $desDropdown[$soknit->autoyarn_id] : $desDropdown[$soknit->c_autoyarn_id];
     $soknit->order_qty = $soknit->order_qty ? $soknit->order_qty : $soknit->c_qty;
     $soknit->order_rate = $soknit->order_rate ? $soknit->order_rate : $soknit->c_rate;

     $soknit->Custom_style_ref = $soknit->style_ref ? $soknit->style_ref : $soknit->gmt_style_ref;
     $soknit->Custom_sale_order_no = $soknit->sale_order_no ? $soknit->sale_order_no : $soknit->gmt_sale_order_no;
     $soknit->uom_code = $soknit->uom_name ? $soknit->uom_name : $soknit->so_uom_name;
     $soknit->item_description = $soknit->fabrication;
     $soknit->balance_qty = $soknit->order_qty - $soknit->cumulative_qty;
     $soknit->tagable_amount = $soknit->balance_qty * $soknit->order_rate;
     return $soknit;
    });

   $orders = $soknit;
   //echo json_encode($orders);

  }
  if ($production_area_id == 20) {
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
    $desDropdown[$key] = $val . "," . implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');

   $sodyeing = $this->localexppiorder
    ->leftJoin('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->leftJoin('so_dyeing_refs', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_dyeing_refs.id');
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
    ->leftJoin('po_dyeing_services', function ($join) {
     $join->on('po_dyeing_services.id', '=', 'po_dyeing_service_items.po_dyeing_service_id');
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
    ->leftJoin(\DB::raw("(SELECT
            so_dyeing_refs.id as so_dyeing_ref_id,
                sum(so_dyeing_items.qty) as cumulative_qty
                FROM so_dyeing_items  
                join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_items.so_dyeing_ref_id 
            group by so_dyeing_refs.id) cumulatives"), "cumulatives.so_dyeing_ref_id", "=", "so_dyeing_refs.id")
    ->where([['local_exp_pis.id', '=', $localexppis->id]])
    ->selectRaw(
     '
                style_fabrications.autoyarn_id,
                style_fabrications.dyeing_type_id,
                sum(po_dyeing_service_item_qties.qty) as order_qty,
                avg(po_dyeing_service_item_qties.rate) as order_rate,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
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
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sum(local_exp_pi_orders.qty) as qty,
                sum(local_exp_pi_orders.amount) as amount
              '
    )
    //->orderBy('local_exp_pi_orders.id','desc')
    ->groupBy([
     'style_fabrications.autoyarn_id',
     'style_fabrications.dyeing_type_id',
     'so_dyeing_items.autoyarn_id',
     'so_dyeing_items.dyeing_type_id',
     'styles.style_ref',
     'sales_orders.sale_order_no',
     'so_dyeing_items.gmt_style_ref',
     'so_dyeing_items.gmt_sale_order_no',
     'buyers.name',
     'gmt_buyer.name',
     'uoms.code',
     'so_uoms.code',
    ])
    ->get()
    ->map(function ($sodyeing) use ($desDropdown, $dyetype) {
     $sodyeing->customer_sales_order = $sodyeing->sales_order_no;
     $sodyeing->fabrication = $sodyeing->autoyarn_id ? $desDropdown[$sodyeing->autoyarn_id] : $desDropdown[$sodyeing->c_autoyarn_id];
     $sodyeing->order_qty = $sodyeing->order_qty ? $sodyeing->order_qty : $sodyeing->c_qty;
     $sodyeing->order_rate = $sodyeing->order_rate ? $sodyeing->order_rate : $sodyeing->c_rate;
     $sodyeing->Custom_style_ref = $sodyeing->style_ref ? $sodyeing->style_ref : $sodyeing->gmt_style_ref;
     $sodyeing->Custom_buyer_name = $sodyeing->buyer_name ? $sodyeing->buyer_name : $sodyeing->gmt_buyer_name;
     $sodyeing->Custom_sale_order_no = $sodyeing->sale_order_no ? $sodyeing->sale_order_no : $sodyeing->gmt_sale_order_no;
     $sodyeing->dye_aop_type = $sodyeing->dyeing_type_id ? $dyetype[$sodyeing->dyeing_type_id] : $dyetype[$sodyeing->c_dyeing_type_id];
     $sodyeing->uom_code = $sodyeing->uom_name ? $sodyeing->uom_name : $sodyeing->so_uom_name;
     $sodyeing->item_description = $sodyeing->fabrication;

     return $sodyeing;
    });
   $orders = $sodyeing;
   //echo json_encode($rows);
  }
  //Aop
  if ($production_area_id == 25) {
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
    $desDropdown[$key] = $val . "," . implode(",", $fabricCompositionArr[$key]);
   }
   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');

   $soaop = $this->localexppiorder
    ->leftJoin('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
    ->leftJoin('so_aop_refs', function ($join) {
     $join->on('local_exp_pi_orders.sales_order_ref_id', '=', 'so_aop_refs.id');
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
    ->leftJoin('po_aop_services', function ($join) {
     $join->on('po_aop_services.id', '=', 'po_aop_service_items.po_aop_service_id');
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
    ->where([['local_exp_pis.id', '=', $localexppis->id]])

    ->selectRaw(
     '
                style_fabrications.autoyarn_id,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                styles.style_ref,
                sales_orders.sale_order_no,
                so_aop_items.gmt_style_ref,
                so_aop_items.gmt_sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                po_aop_service_item_qties.embelishment_type_id,
                so_aop_items.embelishment_type_id as c_embelishment_type_id,
                avg(po_aop_service_item_qties.rate)  as order_rate,
                avg(so_aop_items.rate) as c_rate,
                sum(so_aop_items.amount) as c_amount,
                sum(local_exp_pi_orders.qty) as qty,
                sum(local_exp_pi_orders.amount) as amount
              '
    )
    //->orderBy('local_exp_pi_orders.id','desc')
    ->groupBy([
     'style_fabrications.autoyarn_id',
     'so_aop_items.autoyarn_id',
     'styles.style_ref',
     'sales_orders.sale_order_no',
     'so_aop_items.gmt_style_ref',
     'so_aop_items.gmt_sale_order_no',
     'buyers.name',
     'gmt_buyer.name',
     'uoms.code',
     'so_uoms.code',
     'po_aop_service_item_qties.embelishment_type_id',
     'so_aop_items.embelishment_type_id',
    ])
    ->get()
    ->map(function ($soaop) use ($desDropdown, $aoptype) {
     $soaop->customer_sales_order = $soaop->sales_order_no;
     $soaop->fabrication = $soaop->autoyarn_id ? $desDropdown[$soaop->autoyarn_id] : $desDropdown[$soaop->c_autoyarn_id];
     $soaop->order_qty = $soaop->order_qty ? $soaop->order_qty : $soaop->c_qty;
     $soaop->order_rate = $soaop->order_rate ? $soaop->order_rate : $soaop->c_rate;
     $soaop->Custom_style_ref = $soaop->style_ref ? $soaop->style_ref : $soaop->gmt_style_ref;
     $soaop->Custom_buyer_name = $soaop->buyer_name ? $soaop->buyer_name : $soaop->gmt_buyer_name;
     $soaop->Custom_sale_order_no = $soaop->sale_order_no ? $soaop->sale_order_no : $soaop->gmt_sale_order_no;
     $soaop->dye_aop_type = $soaop->embelishment_type_id ? $aoptype[$soaop->embelishment_type_id] : $aoptype[$soaop->c_embelishment_type_id];
     $soaop->uom_code = $soaop->uom_name ? $soaop->uom_name : $soaop->so_uom_name;
     $soaop->item_description = $soaop->fabrication;
     return $soaop;
    });
   $orders = $soaop;
  }
  //Embelishment Type
  if ($production_area_id == 45 || $production_area_id == 50 || $production_area_id == 51) {
   $embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
   $embelishmenttype = array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(), 'name', 'id'), '', '');
   $embelishment = array_prepend(array_pluck($this->embelishment->get(), 'name', 'id'), '', '');
   $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '', '');
   $size = array_prepend(array_pluck($this->size->get(), 'name', 'id'), '', '');

   $soemb = $this->localexppiorder
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
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
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
    ->leftJoin('local_exp_pis', function ($join) {
     $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
     $join->whereNull('local_exp_pi_orders.deleted_at');
    })
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
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'styles.buyer_id');
    })
    ->leftJoin('buyers as gmt_buyer', function ($join) {
     $join->on('gmt_buyer.id', '=', 'so_emb_items.gmt_buyer');
    })
    ->where([['local_exp_pis.id', '=', $localexppis->id]])
    ->orderBy('local_exp_pi_orders.id', 'desc')
    ->get()
    ->map(function ($soemb) use ($gmtspart, $embelishmentsize, $embelishmenttype, $embelishment, $color, $size) {
     $soemb->customer_sales_order = $soemb->sales_order_no;
     $soemb->emb_size = $embelishmentsize[$soemb->embelishment_size_id];
     $soemb->gmtspart = $soemb->gmtspart_id ? $gmtspart[$soemb->gmtspart_id] : $gmtspart[$soemb->c_gmtspart_id];

     $soemb->emb_size = $soemb->embelishment_size_id ? $embelishmentsize[$soemb->embelishment_size_id] : $embelishmentsize[$soemb->c_embelishment_size_id];
     $soemb->emb_name = $soemb->embelishment_id ? $embelishment[$soemb->embelishment_id] : $embelishment[$soemb->c_embelishment_id];
     $soemb->gmt_color = $soemb->gmt_color ? $soemb->gmt_color : $color[$soemb->c_color_id];
     $soemb->gmt_size = $soemb->gmt_size ? $soemb->gmt_size : $size[$soemb->c_size_id];
     $soemb->item_description = $soemb->item_description . ',' . $soemb->emb_name . ',' . $soemb->emb_size . ',' . $soemb->gmtspart . ',' . $soemb->gmt_color . ',' . $soemb->gmt_size;
     $soemb->amount = $soemb->amount ? $soemb->amount : $soemb->c_amount;
     $soemb->net_amount = $soemb->amount + $soemb->discount_per;
     $soemb->order_rate = $soemb->order_rate ? $soemb->order_rate : $soemb->c_rate;
     $soemb->Custom_style_ref = $soemb->style_ref ? $soemb->style_ref : $soemb->gmt_style_ref;
     $soemb->Custom_buyer_name = $soemb->buyer_name ? $soemb->buyer_name : $soemb->gmt_buyer_name;
     $soemb->Custom_sale_order_no = $soemb->sale_order_no ? $soemb->sale_order_no : $soemb->gmt_sale_order_no;

     $soemb->dye_aop_type = $soemb->embelishment_type_id ? $embelishmenttype[$soemb->embelishment_type_id] : $embelishmenttype[$soemb->c_embelishment_type_id];
     $soemb->sale_order_no = $soemb->sale_order_no ? $soemb->sale_order_no : $soemb->gmt_sale_order_no;
     $soemb->uom_code = $soemb->so_uom_name ? $soemb->so_uom_name : 'Pcs';

     $soemb->sales_order_item_id = $soemb->po_emb_service_item_qty_id ? $soemb->po_emb_service_item_qty_id : $soemb->so_emb_item_id;
     $soemb->order_rate = $soemb->order_rate ? $soemb->order_rate : $soemb->c_rate;
     return $soemb;
    });
   $orders = $soemb;
  }
  $amount = $orders->sum('amount');
  $currency = $localexppi['currency_id'];
  $inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $currency, 'cents');
  $localexppi['inword'] = $inword;

  $purchasetermscondition = $this->purchasetermscondition->where([['purchase_order_id', '=', $id]])->where([['menu_id', '=', 110]])->orderBy('sort_id')->get();
  $localexppi['purchasetermscondition'] = $purchasetermscondition;

  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(true);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $barcodestyle = array(
   'position' => '',
   'align' => 'C',
   'stretch' => false,
   'fitwidth' => true,
   'cellfitalign' => '',
   'border' => false,
   'hpadding' => 'auto',
   'vpadding' => 'auto',
   'fgcolor' => array(0, 0, 0),
   'bgcolor' => false, //array(255,255,255),
   'text' => true,
   'font' => 'helvetica',
   'fontsize' => 8,
   'stretchtext' => 4
  );
  $pdf->SetY(10);
  $pdf->SetX(150);
  $challan = str_pad($localexppi['id'], 10, 0, STR_PAD_LEFT);
  $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
  $pdf->SetY(10);
  $image_file = 'images/logo/' . $localexppi['logo'];
  $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  $pdf->SetY(13);
  $pdf->SetFont('helvetica', 'N', 9);
  $pdf->Cell(0, 40, $localexppi['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M');
  //$pdf->Text(68, 16, $localexppi['company_address']);
  //$pdf->SetY(16);
  $pdf->SetFont('helvetica', '', 7);
  $view = \View::make('Defult.Commercial.LocalExport.LocalExpPiPdf', ['localexppi' => $localexppi, 'orders' => $orders]);
  $html_content = $view->render();
  $pdf->SetY(35);

  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/LocalExpPiPdf.pdf';
  $pdf->output($filename, 'I');
  exit();
 }

 public function searchExp()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
  $payterm = array_prepend(config('bprs.payterm'), '-Select-', '');
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $localexppis = array();
  $rows = $this->localexppi
   ->when(request('company_search_id'), function ($q) {
    return $q->where('local_exp_pis.company_id', "=", request('company_search_id'));
   })
   ->when(request('buyer_search_id'), function ($q) {
    return $q->where('local_exp_pis.buyer_id', '=', request('buyer_search_id'));
   })
   ->when(request('from_date'), function ($q) {
    return $q->where('local_exp_pis.pi_date', '>=', request('from_date'));
   })
   ->when(request('to_date'), function ($q) {
    return $q->where('local_exp_pis.pi_date', '<=', request('to_date'));
   })
   ->orderBy('id', 'desc')->get();
  foreach ($rows as $row) {
   $localexppi['id'] = $row->id;
   $localexppi['company_id'] = isset($company[$row->company_id]) ? $company[$row->company_id] : '';
   $localexppi['pi_no'] = $row->pi_no;
   $localexppi['sys_pi_no'] = $row->sys_pi_no;
   $localexppi['buyer_id'] = isset($buyer[$row->buyer_id]) ? $buyer[$row->buyer_id] : '';
   $localexppi['pi_validity_days'] = $row->pi_validity_days;
   $localexppi['pi_date'] = date('d-M-Y', strtotime($row->pi_date));
   $localexppi['pay_term_id'] = isset($payterm[$row->pay_term_id]) ? $payterm[$row->pay_term_id] : '';
   $localexppi['production_area_id'] = isset($productionarea[$row->production_area_id]) ? $productionarea[$row->production_area_id] : '';
   $localexppi['tenor'] = $row->tenor;
   $localexppi['delivery_date'] = date('d-M-Y', strtotime($row->delivery_date));
   $localexppi['delivery_place'] = $row->delivery_place;
   $localexppi['hs_code'] = $row->hs_code;
   $localexppi['remarks'] = $row->remarks;
   array_push($localexppis, $localexppi);
  }
  echo json_encode($localexppis);
 }
}
