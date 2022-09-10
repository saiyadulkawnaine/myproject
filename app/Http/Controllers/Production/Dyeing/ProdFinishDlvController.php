<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Http\Requests\Production\Dyeing\ProdFinishDlvRequest;

class ProdFinishDlvController extends Controller
{

 private $prodfinishdlv;
 private $company;
 private $buyer;
 private $supplier;
 private $store;
 private $gmtspart;
 private $itemaccount;
 private $location;

 public function __construct(
  ProdFinishDlvRepository $prodfinishdlv,
  CompanyRepository $company,
  BuyerRepository $buyer,
  LocationRepository $location,
  SupplierRepository $supplier,
  StoreRepository $store,
  GmtspartRepository $gmtspart,
  ItemAccountRepository $itemaccount
 ) {
  $this->prodfinishdlv = $prodfinishdlv;
  $this->company = $company;
  $this->buyer = $buyer;
  $this->location = $location;
  $this->supplier = $supplier;
  $this->store = $store;
  $this->gmtspart = $gmtspart;
  $this->itemaccount = $itemaccount;
  $this->middleware('auth');

  /*$this->middleware('permission:view.prodfinishdlvs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodfinishdlvs', ['only' => ['store']]);
        $this->middleware('permission:edit.prodfinishdlvs',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodfinishdlvs', ['only' => ['destroy']]); */
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $rows = $this->prodfinishdlv
   ->leftJoin('companies', function ($join) {
    $join->on('prod_finish_dlvs.company_id', '=', 'companies.id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('prod_finish_dlvs.location_id', '=', 'locations.id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('prod_finish_dlvs.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('stores', function ($join) {
    $join->on('prod_finish_dlvs.store_id', '=', 'stores.id');
   })
   ->where([['prod_finish_dlvs.dlv_to_finish_store', '=', 1]])
   ->where([['prod_finish_dlvs.menu_id', '=', 285]])
   ->orderBy('prod_finish_dlvs.id', 'desc')
   ->get([
    'prod_finish_dlvs.*',
    'companies.name as company_name',
    'locations.name as location_name',
    'buyers.name as buyer_name',
    'stores.name as store_name',
   ])->map(function ($rows) {
    $rows->dlv_date = date('d-M-Y', strtotime($rows->dlv_date));
    return $rows;
   });
  return response()->json($rows);
  //echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->whereNotNull('buyers.company_id')->get(), 'name', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  $store = array_prepend(array_pluck($this->store->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');

  return Template::loadView('Production.Dyeing.ProdFinishDlv', [
   'company' => $company,
   'buyer' => $buyer,
   'location' => $location,
   'supplier' => $supplier,
   'store' => $store
  ]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProdFinishDlvRequest $request)
 {
  $max = $this->prodfinishdlv->max('dlv_no');
  $dlv_no = $max + 1;
  $prodfinishdlv = $this->prodfinishdlv->create([
   'dlv_no' => $dlv_no,
   'dlv_date' => $request->dlv_date,
   'company_id' => $request->company_id,
   'buyer_id' => $request->buyer_id,
   'store_id' => $request->store_id,
   'remarks' => $request->remarks,
   'location_id' => $request->location_id,
   'driver_name' => $request->driver_name,
   'driver_contact_no' => $request->driver_contact_no,
   'driver_license_no' => $request->driver_license_no,
   'lock_no' => $request->lock_no,
   'truck_no' => $request->truck_no,
   'dlv_to_finish_store' => 1,
   'menu_id' => 285,
  ]);
  if ($prodfinishdlv) {
   return response()->json(array('success' => true, 'id' =>  $prodfinishdlv->id, 'message' => 'Save Successfully'), 200);
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
  $prodfinishdlv = $this->prodfinishdlv->find($id);
  $prodfinishdlv->dlv_date = date('Y-m-d', strtotime($prodfinishdlv->dlv_date));
  $row['fromData'] = $prodfinishdlv;
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
 public function update(ProdFinishDlvRequest $request, $id)
 {
  $prodfinishdlv = $this->prodfinishdlv->update($id, $request->except(['id', 'dlv_no', 'company_id', 'buyer_id']));
  if ($prodfinishdlv) {
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
  if ($this->prodfinishdlv->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getPdf()
 {

  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
  $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');


  $fabricDescription = collect(\DB::select(
   "
            select
            autoyarns.id,
            constructions.name as construction,
            compositions.name,
            autoyarnratios.ratio
            FROM autoyarns
            join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
            join compositions on compositions.id = autoyarnratios.composition_id
            join constructions on constructions.id = autoyarns.construction_id
            "
  ));

  $fabricDescriptionArr = array();
  $fabricCompositionArr = array();
  foreach ($fabricDescription as $row) {
   $fabricDescriptionArr[$row->id] = $row->construction;
   $fabricCompositionArr[$row->id][] = $row->name . " " . $row->ratio . "%";
  }

  $desDropdown = array();
  foreach ($fabricDescriptionArr as $key => $val) {
   $desDropdown[$key] = $val . " " . implode(",", $fabricCompositionArr[$key]);
  }
  $id = request('id', 0);
  $rows = $this->prodfinishdlv
   ->leftJoin('companies', function ($join) {
    $join->on('prod_finish_dlvs.company_id', '=', 'companies.id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('prod_finish_dlvs.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('stores', function ($join) {
    $join->on('prod_finish_dlvs.store_id', '=', 'stores.id');
   })
   ->join('users', function ($join) {
    $join->on('users.id', '=', 'prod_finish_dlvs.created_by');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('users.id', '=', 'employee_h_rs.user_id');
   })
   ->leftJoin('prod_finish_dlv_rolls', function ($join) {
    $join->on('prod_finish_dlv_rolls.prod_finish_dlv_id', '=', 'prod_finish_dlvs.id');
   })
   ->leftJoin('prod_batch_finish_qc_rolls', function ($join) {
    $join->on('prod_batch_finish_qc_rolls.id', '=', 'prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id');
   })
   ->leftJoin('prod_batch_finish_qcs', function ($join) {
    $join->on('prod_batch_finish_qc_rolls.prod_batch_finish_qc_id', '=', 'prod_batch_finish_qcs.id');
   })
   ->leftJoin('prod_batches', function ($join) {
    $join->on('prod_batch_finish_qcs.prod_batch_id', '=', 'prod_batches.id');
   })
   ->leftJoin('prod_batch_rolls', function ($join) {
    $join->on('prod_batch_rolls.id', '=', 'prod_batch_finish_qc_rolls.prod_batch_roll_id');
   })
   ->leftJoin('so_dyeing_fabric_rcv_rols', function ($join) {
    $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
   })
   ->leftJoin('so_dyeing_fabric_rcv_items', function ($join) {
    $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
   })
   ->leftJoin('so_dyeing_refs', function ($join) {
    $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
   })
   ->leftJoin('so_dyeings', function ($join) {
    $join->on('so_dyeing_refs.so_dyeing_id', '=', 'so_dyeings.id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyer_branches.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('currencies', function ($join) {
    $join->on('currencies.id', '=', 'so_dyeings.currency_id');
   })
   ->where([['prod_finish_dlvs.id', '=', $id]])
   ->orderBy('prod_finish_dlvs.id', 'desc')
   ->get([
    'prod_finish_dlvs.*',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'currencies.code as currency_code',
    'currencies.name as currency_name',
    'currencies.hundreds_name',
    'stores.name as store_name',
    'stores.address as store_address',
    'users.name as user_name',
    'employee_h_rs.contact'
   ])
   ->first();
  $rows->dlv_date = date('d-M-Y', strtotime($rows->dlv_date));

  $rolldtls = collect(
   \DB::select("
        select m.gmtspart_id,
        m.autoyarn_id,
        m.fabric_look_id,
        m.fabric_shape_id,
        m.gsm_weight,
        m.dia_width,
        m.measurement,
        m.roll_length,
        m.stitch_length,
        m.shrink_per,
        m.batch_color_name,
        m.batch_no,
        m.sale_order_no,
        m.dye_sale_order_no,
        m.style_ref,
        m.gmt_sale_order_no,
        sum(m.qc_pass_qty) as qty,
        sum(m.qty_pcs) as qty_pcs,
        avg(m.rate) as rate,
        avg(m.c_rate) as c_rate,
        sum(m.batch_qty) as batch_qty,
        count(id) as number_of_roll 
        from (
        select 
        prod_finish_dlv_rolls.id, 
        prod_batch_finish_qcs.id as prod_batch_finish_qc_id,   
        prod_batch_finish_qc_rolls.gsm_weight,   
        prod_batch_finish_qc_rolls.dia_width,

        prod_knit_qcs.measurement,   
        prod_knit_qcs.roll_length,   
        prod_knit_qcs.shrink_per,   
        prod_batch_finish_qc_rolls.reject_qty,   
        prod_batch_finish_qc_rolls.qty as qc_pass_qty,   
        prod_batch_finish_qc_rolls.grade_id,

        batch_colors.id as batch_color_id,
        batch_colors.name as batch_color_name,
        prod_batches.batch_no,
        prod_batch_rolls.qty as batch_qty,
        prod_knit_item_rolls.id as prod_knit_item_roll_id,
        prod_knit_item_rolls.custom_no,
        prod_knit_item_rolls.roll_weight,
        prod_knit_item_rolls.width,
        prod_knit_item_rolls.qty_pcs,
        dyeingcolors.id as fabric_color,
        dyeingcolors.name as fab_color_name,
        prod_knit_item_rolls.gmt_sample,
        prod_knit_items.prod_knit_id,
        prod_knit_items.stitch_length,
        prod_knits.prod_no,
        asset_quantity_costs.custom_no as machine_no,
        asset_technical_features.dia_width as machine_dia,
        asset_technical_features.gauge as machine_gg,

        buyers.name as buyer_name,
        styles.style_ref,
        sales_orders.sale_order_no,
        so_dyeing_items.gmt_sale_order_no,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,

        po_dyeing_service_item_qties.rate,
        so_dyeing_items.rate as c_rate,
        so_dyeings.sales_order_no as dye_sale_order_no

        from 
        prod_finish_dlvs
        inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
        inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
        inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
        inner join prod_batches on prod_batches.id = prod_batch_finish_qcs.prod_batch_id
        inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
        inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
        inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
        inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
        inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
        inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
        inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
        inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
        inner join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
        inner join jobs on jobs.id = sales_orders.job_id
        inner join styles on styles.id = jobs.style_id
        inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
        inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
        inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
        inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
        inner join constructions on constructions.id = autoyarns.construction_id
        inner join buyers on buyers.id = styles.buyer_id
        left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id

        left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id = so_dyeing_refs.id

        left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
        left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 


        inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
        inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
        inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
        inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
        inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        inner join inv_rcvs on inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
        inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
        inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
        and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
        inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
        inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
        inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
        inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
        inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
        left join colors on  colors.id=prod_knit_item_rolls.fabric_color
        left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
        where (prod_finish_dlvs.id = ?) and prod_finish_dlvs.deleted_at is null
        ) m  
        group by 
        m.gmtspart_id,
        m.autoyarn_id,
        m.fabric_look_id,
        m.fabric_shape_id,
        m.gsm_weight,
        m.dia_width,
        m.measurement,
        m.roll_length,
        m.stitch_length,
        m.shrink_per,
        m.batch_color_name,
        m.batch_no,
        m.sale_order_no,
        m.dye_sale_order_no,
        m.style_ref,
        m.gmt_sale_order_no
            ", [$id])
  )
   ->map(function ($prodknitqc) use ($desDropdown, $fabriclooks, $fabricshape, $gmtspart) {
    $prodknitqc->body_part = $prodknitqc->gmtspart_id ? $gmtspart[$prodknitqc->gmtspart_id] : '';
    $prodknitqc->fabrication = $prodknitqc->autoyarn_id ? $desDropdown[$prodknitqc->autoyarn_id] : '';
    $prodknitqc->fabric_look = $prodknitqc->fabric_look_id ? $fabriclooks[$prodknitqc->fabric_look_id] : '';
    $prodknitqc->fabric_shape = $prodknitqc->fabric_shape_id ? $fabricshape[$prodknitqc->fabric_shape_id] : '';
    $prodknitqc->rate = $prodknitqc->rate ? $prodknitqc->rate : $prodknitqc->c_rate;
    if ($prodknitqc->batch_qty) {
     $prodknitqc->amount = $prodknitqc->rate * $prodknitqc->batch_qty;
    }
    return $prodknitqc;
   });

  $amount = $rolldtls->sum('amount');
  $inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $rows->currency_name, $rows->hundreds_name);
  $rows->inword = $inword;

  $data['master']    = $rows;
  $data['details']   = $rolldtls;

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
  $challan = str_pad($data['master']->id, 10, 0, STR_PAD_LEFT);


  $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(true);
  $pdf->SetPrintFooter(true);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, '42', PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $header['logo'] = $rows->logo;
  $header['address'] = $rows->company_address;
  $header['title'] = 'Bill';
  $header['barcodestyle'] = $barcodestyle;
  $header['barcodeno'] = $challan;
  $pdf->setCustomHeader($header);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $pdf->SetFont('helvetica', '', 8);
  $pdf->SetTitle('Bill');
  $view = \View::make('Defult.Production.Dyeing.ProdFinishDlvPdf', ['data' => $data]);
  $html_content = $view->render();
  $pdf->SetY(42);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/ProdFinishDlvPdf.pdf';
  $pdf->output($filename);
  exit();
 }

 public function getChallan()
 {

  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
  $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');


  $fabricDescription = collect(\DB::select(
   "
            select
            autoyarns.id,
            constructions.name as construction,
            compositions.name,
            autoyarnratios.ratio
            FROM autoyarns
            join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
            join compositions on compositions.id = autoyarnratios.composition_id
            join constructions on constructions.id = autoyarns.construction_id
            "
  ));

  $fabricDescriptionArr = array();
  $fabricCompositionArr = array();
  foreach ($fabricDescription as $row) {
   $fabricDescriptionArr[$row->id] = $row->construction;
   $fabricCompositionArr[$row->id][] = $row->name . " " . $row->ratio . "%";
  }

  $desDropdown = array();
  foreach ($fabricDescriptionArr as $key => $val) {
   $desDropdown[$key] = $val . " " . implode(",", $fabricCompositionArr[$key]);
  }
  $id = request('id', 0);
  $rows = $this->prodfinishdlv
   ->leftJoin('companies', function ($join) {
    $join->on('prod_finish_dlvs.company_id', '=', 'companies.id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('prod_finish_dlvs.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('stores', function ($join) {
    $join->on('prod_finish_dlvs.store_id', '=', 'stores.id');
   })
   ->join('users', function ($join) {
    $join->on('users.id', '=', 'prod_finish_dlvs.created_by');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('users.id', '=', 'employee_h_rs.user_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyer_branches.buyer_id', '=', 'buyers.id');
   })
   ->where([['prod_finish_dlvs.id', '=', $id]])
   ->orderBy('prod_finish_dlvs.id', 'desc')
   ->get([
    'prod_finish_dlvs.*',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'buyer_branches.address as buyer_address',
    'buyers.name as buyer_name',
    'stores.name as store_name',
    'stores.address as store_address',
    'users.name as user_name',
    'employee_h_rs.contact'
   ])
   ->first();
  $rows->dlv_date = date('d-M-Y', strtotime($rows->dlv_date));

  $yarnDescription = $this->itemaccount
   ->leftJoin('item_account_ratios', function ($join) {
    $join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
   })
   ->leftJoin('compositions', function ($join) {
    $join->on('compositions.id', '=', 'item_account_ratios.composition_id');
   })
   ->leftJoin('itemclasses', function ($join) {
    $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
   })
   ->leftJoin('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })

   ->where([['itemcategories.identity', '=', 1]])
   ->orderBy('item_account_ratios.ratio', 'desc')
   ->get([
    'item_accounts.id',
    'compositions.name as composition_name',
    'item_account_ratios.ratio',
   ]);

  $itemaccountArr = array();
  $yarnCompositionArr = array();
  foreach ($yarnDescription as $row) {
   $itemaccountArr[$row->id]['count'] = $row->count . "/" . $row->symbol;
   $yarnCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }

  $yarnDropdown = array();
  foreach ($itemaccountArr as $key => $value) {
   $yarnDropdown[$key] = implode(",", $yarnCompositionArr[$key]);
  }





  $yarn = collect(
   \DB::select("
            select 
            prod_finish_dlv_rolls.id, 
            prod_batch_finish_qcs.id as prod_batch_finish_qc_id,   
            prod_batch_finish_qc_rolls.gsm_weight,   
            prod_batch_finish_qc_rolls.dia_width,

            prod_knit_qcs.measurement,   
            prod_knit_qcs.roll_length,   
            prod_knit_qcs.shrink_per,   
            prod_batch_finish_qc_rolls.reject_qty,   
            prod_batch_finish_qc_rolls.qty as qc_pass_qty,   
            prod_batch_finish_qc_rolls.grade_id,

            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,
            prod_knit_item_rolls.roll_weight,
            prod_knit_item_rolls.width,
            prod_knit_item_rolls.qty_pcs,
            dyeingcolors.id as fabric_color,
            dyeingcolors.name as fab_color_name,
            batch_colors.id as batch_color_id,
            batch_colors.name as batch_color_name,
            prod_knit_item_rolls.gmt_sample,
            prod_knit_items.prod_knit_id,
            prod_knit_items.stitch_length,
            prod_knits.prod_no,
            asset_quantity_costs.custom_no as machine_no,
            asset_technical_features.dia_width as machine_dia,
            asset_technical_features.gauge as machine_gg,

            buyers.name as buyer_name,
            styles.style_ref,
            sales_orders.sale_order_no,
            style_fabrications.autoyarn_id,
            style_fabrications.gmtspart_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            prod_knit_item_yarns.id as prod_knit_item_yarn_id,
            inv_yarn_items.lot,
            inv_yarn_items.brand,
            colors.name as color_name,
            itemcategories.name as itemcategory_name,
            itemclasses.name as itemclass_name,
            item_accounts.id as item_account_id,
            yarncounts.count,
            yarncounts.symbol,
            yarntypes.name as yarn_type,
            uoms.code as uom_code




            from 
            prod_finish_dlvs
            inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
            inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
            inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
            inner join prod_batches on prod_batches.id = prod_batch_finish_qcs.prod_batch_id
            inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
            inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
            inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
            inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
            inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
            inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
            inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
            inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
            inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
            inner join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
            inner join jobs on jobs.id = sales_orders.job_id
            inner join styles on styles.id = jobs.style_id
            inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
            inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
            inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
            inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
            inner join constructions on constructions.id = autoyarns.construction_id
            inner join buyers on buyers.id = styles.buyer_id
            left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
            left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
            left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
            left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 


            inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
            inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
            inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
            inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
            inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
            inner join inv_rcvs on inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
            inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
            inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
            and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
            inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
            inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
            inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
            inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
            inner join prod_knit_item_yarns on prod_knit_items.id = prod_knit_item_yarns.prod_knit_item_id 
            inner join inv_yarn_isu_items on  inv_yarn_isu_items.id=prod_knit_item_yarns.inv_yarn_isu_item_id
            inner join inv_yarn_items on  inv_yarn_items.id=inv_yarn_isu_items.inv_yarn_item_id
            inner join item_accounts on  item_accounts.id=inv_yarn_items.item_account_id
            inner join yarncounts on  yarncounts.id=item_accounts.yarncount_id
            inner join yarntypes on  yarntypes.id=item_accounts.yarntype_id
            inner join itemclasses on  itemclasses.id=item_accounts.itemclass_id
            inner join itemcategories on  itemcategories.id=item_accounts.itemcategory_id
            inner join uoms on  uoms.id=item_accounts.uom_id
            inner join colors  on  colors.id=inv_yarn_items.color_id
            inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
            where (prod_finish_dlvs.id = ?) and prod_finish_dlvs.deleted_at is null
            ", [$id])
  )
   ->map(function ($yarn) use ($yarnDropdown) {
    $yarn->yarn_count = $yarn->count . "/" . $yarn->symbol;
    $yarn->composition = $yarn->item_account_id ? $yarnDropdown[$yarn->item_account_id] : '';
    return $yarn;
   });

  $yarnDtls = [];
  foreach ($yarn as $yar) {

   $index = $yar->gmtspart_id . "-" . $yar->autoyarn_id . "-" . $yar->fabric_look_id . "-" . $yar->fabric_shape_id . "-" . $yar->gsm_weight . "-" . $yar->dia_width . "-" . $yar->fabric_color . "-" . $yar->batch_color_id . "-" . $yar->sale_order_no . "-" . $yar->stitch_length . "-" . $yar->style_ref . "-" . $yar->buyer_name . "-" . $yar->machine_no . "-" . $yar->machine_gg;
   $yarn = $yar->lot . ", " ./*$yar->itemclass_name." ".*/ $yar->yarn_count . " " . $yar->composition . " " . $yar->yarn_type . " " . $yar->brand . " " . $yar->color_name;

   $yarnDtls[$index][$yarn] = $yarn;
  }



  $rolldtls = collect(
   \DB::select("
        select 
        m.gmtspart_id,
        m.autoyarn_id,
        m.fabric_look_id,
        m.fabric_shape_id,
        m.gsm_weight,
        m.dia_width,
        m.fabric_color,
        m.fab_color_name,
        m.batch_color_id,
        m.batch_color_name,
        m.stitch_length,
        m.sale_order_no,
        m.style_ref,
        m.buyer_name,
        m.machine_no,
        m.machine_gg,
        m.batch_no,
        m.gmt_sale_order_no,
        m.dye_sale_order_no,
        m.shrink_per,
        sum(m.qc_pass_qty) as qty,
        sum(m.qty_pcs) as qty_pcs,
        count(id) as number_of_roll 
        from (
        select 
        prod_finish_dlv_rolls.id, 
        prod_batch_finish_qcs.id as prod_batch_finish_qc_id,   
        prod_batch_finish_qc_rolls.gsm_weight,   
        prod_batch_finish_qc_rolls.dia_width,

        prod_knit_qcs.measurement,   
        prod_knit_qcs.roll_length,   
        prod_knit_qcs.shrink_per,   
        prod_batch_finish_qc_rolls.reject_qty,   
        prod_batch_finish_qc_rolls.qty as qc_pass_qty,   
        prod_batch_finish_qc_rolls.grade_id,

        prod_knit_item_rolls.id as prod_knit_item_roll_id,
        prod_knit_item_rolls.custom_no,
        prod_knit_item_rolls.roll_weight,
        prod_knit_item_rolls.width,
        prod_knit_item_rolls.qty_pcs,
        dyeingcolors.id as fabric_color,
        dyeingcolors.name as fab_color_name,
        batch_colors.id as batch_color_id,
        batch_colors.name as batch_color_name,
        prod_knit_item_rolls.gmt_sample,
        prod_knit_items.prod_knit_id,
        prod_knit_items.stitch_length,
        prod_knits.prod_no,
        asset_quantity_costs.custom_no as machine_no,
        asset_technical_features.dia_width as machine_dia,
        asset_technical_features.gauge as machine_gg,

        buyers.name as buyer_name,
        styles.style_ref,
        sales_orders.sale_order_no,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        prod_batches.batch_no,
        so_dyeing_items.gmt_sale_order_no,
        so_dyeings.sales_order_no as dye_sale_order_no



        from 
        prod_finish_dlvs
        inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
        inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
        inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
        inner join prod_batches on prod_batches.id = prod_batch_finish_qcs.prod_batch_id
        inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
        inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
        inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
        inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
        inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
        inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
        inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
        inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
        inner join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
        inner join jobs on jobs.id = sales_orders.job_id
        inner join styles on styles.id = jobs.style_id
        inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
        inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
        inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
        inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
        inner join constructions on constructions.id = autoyarns.construction_id
        inner join buyers on buyers.id = styles.buyer_id
        left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
        left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
        left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id = so_dyeing_refs.id
        left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
        left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 


        inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
        inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
        inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
        inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
        inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        inner join inv_rcvs on inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
        inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
        inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
        and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
        inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
        inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
        inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
        inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
        inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
        left join colors on  colors.id=prod_knit_item_rolls.fabric_color
        where (prod_finish_dlvs.id = ?) and prod_finish_dlvs.deleted_at is null
        ) m  
        group by 
        m.gmtspart_id,
        m.autoyarn_id,
        m.fabric_look_id,
        m.fabric_shape_id,
        m.gsm_weight,
        m.dia_width,
        m.fabric_color,
        m.fab_color_name,
        m.batch_color_id,
        m.batch_color_name,
        m.sale_order_no,
        m.stitch_length,
        m.style_ref,
        m.buyer_name,
        m.machine_no,
        m.machine_gg,
        m.batch_no,
        m.gmt_sale_order_no,
        m.dye_sale_order_no,
        m.shrink_per
            ", [$id])
  )
   ->map(function ($prodknitqc) use ($desDropdown, $fabriclooks, $fabricshape, $gmtspart, $yarnDtls) {
    $index = $prodknitqc->gmtspart_id . "-" . $prodknitqc->autoyarn_id . "-" . $prodknitqc->fabric_look_id . "-" . $prodknitqc->fabric_shape_id . "-" . $prodknitqc->gsm_weight . "-" . $prodknitqc->dia_width . "-" . $prodknitqc->fabric_color . "-" . $prodknitqc->batch_color_id . "-" . $prodknitqc->sale_order_no . "-" . $prodknitqc->stitch_length . "-" . $prodknitqc->style_ref . "-" . $prodknitqc->buyer_name . "-" . $prodknitqc->machine_no . "-" . $prodknitqc->machine_gg;
    $prodknitqc->body_part = $prodknitqc->gmtspart_id ? $gmtspart[$prodknitqc->gmtspart_id] : '';
    $prodknitqc->fabrication = $prodknitqc->autoyarn_id ? $desDropdown[$prodknitqc->autoyarn_id] : '';
    $prodknitqc->fabric_look = $prodknitqc->fabric_look_id ? $fabriclooks[$prodknitqc->fabric_look_id] : '';
    $prodknitqc->fabric_shape = $prodknitqc->fabric_shape_id ? $fabricshape[$prodknitqc->fabric_shape_id] : '';
    $prodknitqc->yarn = isset($yarnDtls[$index]) ? implode(' + ', $yarnDtls[$index]) : '';
    return $prodknitqc;
   });

  $data['master']    = $rows;
  $data['details']   = $rolldtls;

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
  $challan = str_pad($data['master']->id, 10, 0, STR_PAD_LEFT);


  $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(true);
  $pdf->SetPrintFooter(true);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, '42', PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $header['logo'] = $rows->logo;
  $header['address'] = $rows->company_address;
  $header['title'] = 'Finish Fabric Roll Delivery to Store Challan / Gate Pass';
  $header['barcodestyle'] = $barcodestyle;
  $header['barcodeno'] = $challan;
  $pdf->setCustomHeader($header);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  //$pdf->SetY(10);
  //$image_file ='images/logo/'.$rows->logo;
  //$pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  //$pdf->SetY(13);
  //$pdf->SetFont('helvetica', 'N', 8);
  //$pdf->Text(115, 12, $rows->company_address);

  /*$pdf->SetY(3);
        $pdf->SetX(190);
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');*/
  $pdf->SetFont('helvetica', '', 8);
  $pdf->SetTitle('Finish Fabric Roll Delivery to Store Challan / Gate Pass');
  $view = \View::make('Defult.Production.Dyeing.ProdFinishDlvChallanPdf', ['data' => $data]);
  $html_content = $view->render();
  $pdf->SetY(42);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/ProdFinishDlvChallanPdf.pdf';
  $pdf->output($filename);
  exit();
 }

 public function getPdfShort()
 {

  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
  $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');


  $fabricDescription = collect(\DB::select(
   "
            select
            autoyarns.id,
            constructions.name as construction,
            compositions.name,
            autoyarnratios.ratio
            FROM autoyarns
            join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
            join compositions on compositions.id = autoyarnratios.composition_id
            join constructions on constructions.id = autoyarns.construction_id
            "
  ));

  $fabricDescriptionArr = array();
  $fabricCompositionArr = array();
  foreach ($fabricDescription as $row) {
   $fabricDescriptionArr[$row->id] = $row->construction;
   $fabricCompositionArr[$row->id][] = $row->name . " " . $row->ratio . "%";
  }

  $desDropdown = array();
  foreach ($fabricDescriptionArr as $key => $val) {
   $desDropdown[$key] = $val . " " . implode(",", $fabricCompositionArr[$key]);
  }
  $id = request('id', 0);
  $rows = $this->prodfinishdlv
   ->leftJoin('companies', function ($join) {
    $join->on('prod_finish_dlvs.company_id', '=', 'companies.id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('prod_finish_dlvs.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('stores', function ($join) {
    $join->on('prod_finish_dlvs.store_id', '=', 'stores.id');
   })
   ->join('users', function ($join) {
    $join->on('users.id', '=', 'prod_finish_dlvs.created_by');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('users.id', '=', 'employee_h_rs.user_id');
   })
   ->leftJoin('prod_finish_dlv_rolls', function ($join) {
    $join->on('prod_finish_dlv_rolls.prod_finish_dlv_id', '=', 'prod_finish_dlvs.id');
   })
   ->leftJoin('prod_batch_finish_qc_rolls', function ($join) {
    $join->on('prod_batch_finish_qc_rolls.id', '=', 'prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id');
   })
   ->leftJoin('prod_batch_finish_qcs', function ($join) {
    $join->on('prod_batch_finish_qc_rolls.prod_batch_finish_qc_id', '=', 'prod_batch_finish_qcs.id');
   })
   ->leftJoin('prod_batches', function ($join) {
    $join->on('prod_batch_finish_qcs.prod_batch_id', '=', 'prod_batches.id');
   })
   ->leftJoin('prod_batch_rolls', function ($join) {
    $join->on('prod_batch_rolls.id', '=', 'prod_batch_finish_qc_rolls.prod_batch_roll_id');
   })
   ->leftJoin('so_dyeing_fabric_rcv_rols', function ($join) {
    $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
   })
   ->leftJoin('so_dyeing_fabric_rcv_items', function ($join) {
    $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
   })
   ->leftJoin('so_dyeing_refs', function ($join) {
    $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
   })
   ->leftJoin('so_dyeings', function ($join) {
    $join->on('so_dyeing_refs.so_dyeing_id', '=', 'so_dyeings.id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyer_branches.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('currencies', function ($join) {
    $join->on('currencies.id', '=', 'so_dyeings.currency_id');
   })
   ->where([['prod_finish_dlvs.id', '=', $id]])
   ->orderBy('prod_finish_dlvs.id', 'desc')
   ->get([
    'prod_finish_dlvs.*',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'currencies.code as currency_code',
    'currencies.name as currency_name',
    'currencies.hundreds_name',
    'stores.name as store_name',
    'stores.address as store_address',
    'users.name as user_name',
    'employee_h_rs.contact'
   ])
   ->first();
  $rows->dlv_date = date('d-M-Y', strtotime($rows->dlv_date));

  $rolldtls = collect(
   \DB::select("
        select m.gmtspart_id,
        m.autoyarn_id,
        m.fabric_look_id,
        m.fabric_shape_id,
        m.batch_color_name,
        m.batch_no,
        m.sale_order_no,
        m.dye_sale_order_no,
        m.style_ref,
        m.gmt_sale_order_no,
        m.gsm_weight,
        m.dia,
        sum(m.qc_pass_qty) as qty,
        sum(m.qty_pcs) as qty_pcs,
        avg(m.rate) as rate,
        avg(m.c_rate) as c_rate,
        sum(m.batch_qty) as batch_qty,
        count(id) as number_of_roll 
        from (
        select 
        prod_finish_dlv_rolls.id, 
        prod_batch_finish_qcs.id as prod_batch_finish_qc_id,   
  
        prod_batch_finish_qc_rolls.reject_qty,   
        prod_batch_finish_qc_rolls.qty as qc_pass_qty,   
        prod_batch_finish_qc_rolls.grade_id,

        batch_colors.id as batch_color_id,
        batch_colors.name as batch_color_name,
        prod_batches.batch_no,
        prod_batch_rolls.qty as batch_qty,
        prod_knit_item_rolls.id as prod_knit_item_roll_id,
        prod_knit_item_rolls.custom_no,
        prod_knit_item_rolls.roll_weight,
        prod_knit_item_rolls.width,
        prod_knit_item_rolls.qty_pcs,
        dyeingcolors.id as fabric_color,
        dyeingcolors.name as fab_color_name,
        prod_knit_item_rolls.gmt_sample,
        prod_knit_items.prod_knit_id,
        prod_knit_items.stitch_length,
        prod_knits.prod_no,
        asset_quantity_costs.custom_no as machine_no,
        asset_technical_features.dia_width as machine_dia,
        asset_technical_features.gauge as machine_gg,

        buyers.name as buyer_name,
        styles.style_ref,
        sales_orders.sale_order_no,
        so_dyeing_items.gmt_sale_order_no,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        budget_fabrics.gsm_weight,
        po_dyeing_service_item_qties.dia,
        po_dyeing_service_item_qties.rate,
        so_dyeing_items.rate as c_rate,
        so_dyeings.sales_order_no as dye_sale_order_no

        from 
        prod_finish_dlvs
        inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
        inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
        inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
        inner join prod_batches on prod_batches.id = prod_batch_finish_qcs.prod_batch_id
        inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
        inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
        inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
        inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
        inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
        inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
        inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
        inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
        inner join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
        inner join jobs on jobs.id = sales_orders.job_id
        inner join styles on styles.id = jobs.style_id
        inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
        inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
        inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
        inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
        inner join constructions on constructions.id = autoyarns.construction_id
        inner join buyers on buyers.id = styles.buyer_id
        left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id

        left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id = so_dyeing_refs.id

        left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
        left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 


        inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
        inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
        inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
        inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
        inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        inner join inv_rcvs on inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
        inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
        inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
        and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
        inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
        inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
        inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
        inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
        inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
        left join colors on  colors.id=prod_knit_item_rolls.fabric_color
        left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
        where (prod_finish_dlvs.id = ?) and prod_finish_dlvs.deleted_at is null
        ) m  
        group by 
        m.gmtspart_id,
        m.autoyarn_id,
        m.fabric_look_id,
        m.fabric_shape_id,
        m.batch_color_name,
        m.batch_no,
        m.sale_order_no,
        m.dye_sale_order_no,
        m.style_ref,
        m.gmt_sale_order_no,
        m.gsm_weight,
        m.dia
            ", [$id])
  )
   ->map(function ($prodknitqc) use ($desDropdown, $fabriclooks, $fabricshape, $gmtspart) {
    $prodknitqc->body_part = $prodknitqc->gmtspart_id ? $gmtspart[$prodknitqc->gmtspart_id] : '';
    $prodknitqc->fabrication = $prodknitqc->autoyarn_id ? $desDropdown[$prodknitqc->autoyarn_id] : '';
    $prodknitqc->fabric_look = $prodknitqc->fabric_look_id ? $fabriclooks[$prodknitqc->fabric_look_id] : '';
    $prodknitqc->fabric_shape = $prodknitqc->fabric_shape_id ? $fabricshape[$prodknitqc->fabric_shape_id] : '';
    $prodknitqc->rate = $prodknitqc->rate ? $prodknitqc->rate : $prodknitqc->c_rate;
    if ($prodknitqc->batch_qty) {
     $prodknitqc->amount = $prodknitqc->rate * $prodknitqc->batch_qty;
    }
    return $prodknitqc;
   });

  $amount = $rolldtls->sum('amount');
  $inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $rows->currency_name, $rows->hundreds_name);
  $rows->inword = $inword;

  $data['master']    = $rows;
  $data['details']   = $rolldtls;

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
  $challan = str_pad($data['master']->id, 10, 0, STR_PAD_LEFT);


  $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(true);
  $pdf->SetPrintFooter(true);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, '42', PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $header['logo'] = $rows->logo;
  $header['address'] = $rows->company_address;
  $header['title'] = 'Bill';
  $header['barcodestyle'] = $barcodestyle;
  $header['barcodeno'] = $challan;
  $pdf->setCustomHeader($header);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $pdf->SetFont('helvetica', '', 8);
  $pdf->SetTitle('Bill');
  $view = \View::make('Defult.Production.Dyeing.ProdFinishDlvPdfShort', ['data' => $data]);
  $html_content = $view->render();
  $pdf->SetY(42);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/ProdFinishDlvPdf.pdf';
  $pdf->output($filename);
 }
}
