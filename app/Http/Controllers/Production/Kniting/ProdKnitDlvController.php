<?php

namespace App\Http\Controllers\Production\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Kniting\ProdKnitDlvRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Http\Requests\Production\Kniting\ProdKnitDlvRequest;

class ProdKnitDlvController extends Controller
{

 private $prodknitdlv;
 private $company;
 private $buyer;
 private $supplier;
 private $store;
 private $gmtspart;
 private $itemaccount;
 private $currency;

 public function __construct(
  ProdKnitDlvRepository $prodknitdlv,
  CompanyRepository $company,
  BuyerRepository $buyer,
  SupplierRepository $supplier,
  StoreRepository $store,
  GmtspartRepository $gmtspart,
  ItemAccountRepository $itemaccount,
  CurrencyRepository $currency
 ) {
  $this->prodknitdlv = $prodknitdlv;
  $this->company = $company;
  $this->buyer = $buyer;
  $this->supplier = $supplier;
  $this->store = $store;
  $this->gmtspart = $gmtspart;
  $this->itemaccount = $itemaccount;
  $this->currency = $currency;

  $this->middleware('auth');
  $this->middleware('permission:view.prodknitdlvs',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.prodknitdlvs', ['only' => ['store']]);
  $this->middleware('permission:edit.prodknitdlvs',   ['only' => ['update']]);
  $this->middleware('permission:delete.prodknitdlvs', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $rows = $this->prodknitdlv
   ->leftJoin('companies', function ($join) {
    $join->on('prod_knit_dlvs.company_id', '=', 'companies.id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('prod_knit_dlvs.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('stores', function ($join) {
    $join->on('prod_knit_dlvs.store_id', '=', 'stores.id');
   })
   ->orderBy('prod_knit_dlvs.id', 'desc')
   ->take(500)
   ->get([
    'prod_knit_dlvs.*',
    'companies.name as company_name',
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
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  $store = array_prepend(array_pluck($this->store->get(), 'name', 'id'), '-Select-', '');


  return Template::loadView('Production.Kniting.ProdKnitDlv', [
   'company' => $company,
   'buyer' => $buyer,
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
 public function store(ProdKnitDlvRequest $request)
 {
  $max = $this->prodknitdlv->max('dlv_no');
  $dlv_no = $max + 1;
  $prodknitdlv = $this->prodknitdlv->create(['dlv_no' => $dlv_no, 'dlv_date' => $request->dlv_date, 'company_id' => $request->company_id, 'buyer_id' => $request->buyer_id, 'store_id' => $request->store_id, 'remarks' => $request->remarks]);
  if ($prodknitdlv) {
   return response()->json(array('success' => true, 'id' =>  $prodknitdlv->id, 'message' => 'Save Successfully'), 200);
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
  $prodknitdlv = $this->prodknitdlv->find($id);
  $prodknitdlv->dlv_date = date('Y-m-d', strtotime($prodknitdlv->dlv_date));
  $row['fromData'] = $prodknitdlv;
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
 public function update(ProdKnitDlvRequest $request, $id)
 {
  $prodknitdlv = $this->prodknitdlv->update($id, $request->except(['id', 'dlv_no', 'company_id', 'buyer_id']));
  if ($prodknitdlv) {
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
  if ($this->prodknitdlv->delete($id)) {
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
  $rows = $this->prodknitdlv
   ->leftJoin('companies', function ($join) {
    $join->on('prod_knit_dlvs.company_id', '=', 'companies.id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('prod_knit_dlvs.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('stores', function ($join) {
    $join->on('prod_knit_dlvs.store_id', '=', 'stores.id');
   })
   ->join('users', function ($join) {
    $join->on('users.id', '=', 'prod_knit_dlvs.created_by');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('users.id', '=', 'employee_h_rs.user_id');
   })
   ->where([['prod_knit_dlvs.id', '=', $id]])
   ->orderBy('prod_knit_dlvs.id', 'desc')
   ->get([
    'prod_knit_dlvs.*',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'buyers.name as buyer_name',
    'stores.name as store_name',
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
        sum(m.qc_pass_qty) as qty,
        sum(m.qty_pcs) as qty_pcs,
        count(id) as number_of_roll 
        from (
        select 
        prod_knit_dlv_rolls.id, 
        prod_knit_qcs.id as prod_knit_qc_id,   
        prod_knit_qcs.gsm_weight,   
        prod_knit_qcs.dia_width,   
        prod_knit_qcs.measurement,   
        prod_knit_qcs.roll_length,   
        prod_knit_qcs.shrink_per,   
        prod_knit_qcs.reject_qty,   
        prod_knit_qcs.qc_pass_qty,   
        prod_knit_qcs.reject_qty_pcs,   
        prod_knit_qcs.qc_pass_qty_pcs,   
        prod_knit_qcs.qc_result,

        prod_knit_item_rolls.id as prod_knit_item_roll_id,
        prod_knit_item_rolls.custom_no,
        prod_knit_item_rolls.roll_weight,
        prod_knit_item_rolls.width,
        prod_knit_item_rolls.qty_pcs,
        prod_knit_item_rolls.fabric_color,
        prod_knit_item_rolls.gmt_sample,
        prod_knit_items.prod_knit_id,
        prod_knit_items.stitch_length,

        prod_knits.shift_id,
        prod_knits.prod_no,
        prod_knits.supplier_id,
        prod_knits.location_id,
        prod_knits.floor_id,

        suppliers.name as supplier_name,
        locations.name as location_name,
        floors.name as floor_name,
        asset_quantity_costs.custom_no as machine_no,
        asset_technical_features.dia_width as machine_dia,
        asset_technical_features.gauge as machine_gg,
        gmtssamples.name as gmt_sample,
        case 
        when  inhouseprods.autoyarn_id is null then outhouseprods.autoyarn_id 
        else inhouseprods.autoyarn_id
        end as autoyarn_id,
        case 
        when  inhouseprods.gmtspart_id is null then outhouseprods.gmtspart_id 
        else inhouseprods.gmtspart_id
        end as gmtspart_id,
        case 
        when  inhouseprods.fabric_look_id is null then outhouseprods.fabric_look_id 
        else inhouseprods.fabric_look_id
        end as fabric_look_id,

        case 
        when  inhouseprods.fabric_shape_id is null then outhouseprods.fabric_shape_id 
        else inhouseprods.fabric_shape_id
        end as fabric_shape_id,
        case 
        when  inhouseprods.colorrange_name is null then outhouseprods.colorrange_name 
        else inhouseprods.colorrange_name
        end as colorrange_name,


        case 
        when  inhouseprods.sale_order_no is null then outhouseprods.sale_order_no 
        else inhouseprods.sale_order_no
        end as sale_order_no,
        case 
        when  inhouseprods.style_ref is null then outhouseprods.style_ref 
        else inhouseprods.style_ref
        end as style_ref,

        case 
        when  inhouseprods.buyer_name is null then outhouseprods.buyer_name 
        else inhouseprods.buyer_name
        end as buyer_name,

        case 
        when  inhouseprods.customer_name is null then outhouseprods.customer_name 
        else inhouseprods.customer_name
        end as customer_name


        from prod_knit_dlvs 
        inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
        inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
        inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
        inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
        inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
        inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
        inner join suppliers on suppliers.id = prod_knits.supplier_id 
        left join locations on locations.id = prod_knits.location_id 
        left join floors on floors.id = prod_knits.floor_id 
        left join asset_quantity_costs on asset_quantity_costs.id = prod_knit_items.asset_quantity_cost_id 
        left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
        left join gmtssamples on gmtssamples.id = prod_knit_item_rolls.gmt_sample 
        left join (
        select 
        pl_knit_items.id,
        colorranges.name as colorrange_name,
        customer.name as customer_name,
        case 
        when  style_fabrications.autoyarn_id is null then so_knit_items.autoyarn_id 
        else style_fabrications.autoyarn_id
        end as autoyarn_id,

        case 
        when  style_fabrications.gmtspart_id is null then so_knit_items.gmtspart_id 
        else style_fabrications.gmtspart_id
        end as gmtspart_id,

        case 
        when  style_fabrications.fabric_look_id is null then so_knit_items.fabric_look_id 
        else style_fabrications.fabric_look_id
        end as fabric_look_id,

        case 
        when  style_fabrications.fabric_shape_id is null then so_knit_items.fabric_shape_id 
        else style_fabrications.fabric_shape_id
        end as fabric_shape_id,
        case 
        when sales_orders.sale_order_no is null then so_knit_items.gmt_sale_order_no 
        else sales_orders.sale_order_no
        end as sale_order_no,
        case 
        when styles.style_ref is null then so_knit_items.gmt_style_ref 
        else styles.style_ref
        end as style_ref,
        case 
        when buyers.name is null then outbuyers.name 
        else buyers.name
        end as buyer_name
        from pl_knit_items
        join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
        left join colorranges on colorranges.id=pl_knit_items.colorrange_id
        join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
        left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
        left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
        left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
        and po_knit_service_items.deleted_at is null
        left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
        left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
        left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
        left join so_knits on so_knits.id=so_knit_refs.so_knit_id
        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
        left join buyers customer on customer.id=so_knits.buyer_id
        ) inhouseprods on inhouseprods.id = prod_knit_items.pl_knit_item_id 
        left join (
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        buyers.name as buyer_name,
        companies.name as customer_name  
        from 
        po_knit_service_item_qties
        join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
        join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
        left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
        join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
        join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id

        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join companies on companies.id=po_knit_services.company_id
        order by po_knit_service_item_qties.id
        ) outhouseprods on outhouseprods.id = prod_knit_items.po_knit_service_item_qty_id where (prod_knit_dlvs.id = ?) and prod_knit_dlvs.deleted_at is null
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
        m.shrink_per
            ", [$id])
  )
   ->map(function ($prodknitqc) use ($desDropdown, $fabriclooks, $fabricshape, $gmtspart) {
    $prodknitqc->body_part = $prodknitqc->gmtspart_id ? $gmtspart[$prodknitqc->gmtspart_id] : '';
    $prodknitqc->fabrication = $prodknitqc->autoyarn_id ? $desDropdown[$prodknitqc->autoyarn_id] : '';
    $prodknitqc->fabric_look = $prodknitqc->fabric_look_id ? $fabriclooks[$prodknitqc->fabric_look_id] : '';
    $prodknitqc->fabric_shape = $prodknitqc->fabric_shape_id ? $fabricshape[$prodknitqc->fabric_shape_id] : '';
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
  $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $header['logo'] = $rows->logo;
  $header['address'] = $rows->company_address;
  $header['title'] = 'Grey Fabric Roll Delivery to Store Challan / Gate Pass';
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
  $pdf->SetTitle('Grey Fabric Roll Delivery to Store Challan / Gate Pass');
  $view = \View::make('Defult.Production.Kniting.ProdKnitDlvPdf', ['data' => $data]);
  $html_content = $view->render();
  $pdf->SetY(42);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/ProdKnitDlvPdf.pdf';
  $pdf->output($filename);
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
  $rows = $this->prodknitdlv
   ->leftJoin('companies', function ($join) {
    $join->on('prod_knit_dlvs.company_id', '=', 'companies.id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('prod_knit_dlvs.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('stores', function ($join) {
    $join->on('prod_knit_dlvs.store_id', '=', 'stores.id');
   })
   ->join('users', function ($join) {
    $join->on('users.id', '=', 'prod_knit_dlvs.created_by');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('users.id', '=', 'employee_h_rs.user_id');
   })
   ->where([['prod_knit_dlvs.id', '=', $id]])
   ->orderBy('prod_knit_dlvs.id', 'desc')
   ->get([
    'prod_knit_dlvs.*',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'buyers.name as buyer_name',
    'stores.name as store_name',
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
			prod_knit_dlv_rolls.id, 
			prod_knit_qcs.id as prod_knit_qc_id,   
			prod_knit_qcs.gsm_weight,   
			prod_knit_qcs.dia_width,   
			prod_knit_qcs.measurement,   
			prod_knit_qcs.roll_length,   
			prod_knit_qcs.shrink_per,   
			prod_knit_qcs.reject_qty,   
			prod_knit_qcs.qc_pass_qty,   
			prod_knit_qcs.reject_qty_pcs,   
			prod_knit_qcs.qc_pass_qty_pcs,   
			prod_knit_qcs.qc_result,

			prod_knit_item_rolls.id as prod_knit_item_roll_id,
			prod_knit_item_rolls.custom_no,
			prod_knit_item_rolls.roll_weight,
			prod_knit_item_rolls.width,
			prod_knit_item_rolls.qty_pcs,
			prod_knit_item_rolls.fabric_color,
			prod_knit_item_rolls.gmt_sample,
			prod_knit_items.prod_knit_id,
			prod_knit_items.stitch_length,
            prod_knit_items.machine_info_outside,

			prod_knits.shift_id,
			prod_knits.prod_no,
			prod_knits.supplier_id,
			prod_knits.location_id,
			prod_knits.floor_id,

			suppliers.name as supplier_name,
			locations.name as location_name,
			floors.name as floor_name,
			asset_quantity_costs.custom_no as machine_no,
			asset_technical_features.dia_width as machine_dia,
			asset_technical_features.gauge as machine_gg,
			gmtssamples.name as gmt_sample,
			case 
			when  inhouseprods.autoyarn_id is null then outhouseprods.autoyarn_id 
			else inhouseprods.autoyarn_id
			end as autoyarn_id,
			case 
			when  inhouseprods.gmtspart_id is null then outhouseprods.gmtspart_id 
			else inhouseprods.gmtspart_id
			end as gmtspart_id,
			case 
			when  inhouseprods.fabric_look_id is null then outhouseprods.fabric_look_id 
			else inhouseprods.fabric_look_id
			end as fabric_look_id,

			case 
			when  inhouseprods.fabric_shape_id is null then outhouseprods.fabric_shape_id 
			else inhouseprods.fabric_shape_id
			end as fabric_shape_id,
			case 
			when  inhouseprods.colorrange_name is null then outhouseprods.colorrange_name 
			else inhouseprods.colorrange_name
			end as colorrange_name,


			case 
			when  inhouseprods.sale_order_no is null then outhouseprods.sale_order_no 
			else inhouseprods.sale_order_no
			end as sale_order_no,
			case 
			when  inhouseprods.style_ref is null then outhouseprods.style_ref 
			else inhouseprods.style_ref
			end as style_ref,

			case 
			when  inhouseprods.buyer_name is null then outhouseprods.buyer_name 
			else inhouseprods.buyer_name
			end as buyer_name,

			case 
			when  inhouseprods.customer_name is null then outhouseprods.customer_name 
			else inhouseprods.customer_name
			end as customer_name,
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


			from prod_knit_dlvs 
			inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
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
			inner join colors on  colors.id=inv_yarn_items.color_id
			inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 

			inner join suppliers on suppliers.id = prod_knits.supplier_id 
			left join locations on locations.id = prod_knits.location_id 
			left join floors on floors.id = prod_knits.floor_id 
			left join asset_quantity_costs on asset_quantity_costs.id = prod_knit_items.asset_quantity_cost_id 
			left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
			left join gmtssamples on gmtssamples.id = prod_knit_item_rolls.gmt_sample 
			left join (
			select 
			pl_knit_items.id,
			colorranges.name as colorrange_name,
			customer.name as customer_name,
			case 
			when  style_fabrications.autoyarn_id is null then so_knit_items.autoyarn_id 
			else style_fabrications.autoyarn_id
			end as autoyarn_id,

			case 
			when  style_fabrications.gmtspart_id is null then so_knit_items.gmtspart_id 
			else style_fabrications.gmtspart_id
			end as gmtspart_id,

			case 
			when  style_fabrications.fabric_look_id is null then so_knit_items.fabric_look_id 
			else style_fabrications.fabric_look_id
			end as fabric_look_id,

			case 
			when  style_fabrications.fabric_shape_id is null then so_knit_items.fabric_shape_id 
			else style_fabrications.fabric_shape_id
			end as fabric_shape_id,
			case 
			when sales_orders.sale_order_no is null then so_knit_items.gmt_sale_order_no 
			else sales_orders.sale_order_no
			end as sale_order_no,
			case 
			when styles.style_ref is null then so_knit_items.gmt_style_ref 
			else styles.style_ref
			end as style_ref,
			case 
			when buyers.name is null then outbuyers.name 
			else buyers.name
			end as buyer_name
			from pl_knit_items
			join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
			left join colorranges on colorranges.id=pl_knit_items.colorrange_id
			join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
			left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
			left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
			left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
			and po_knit_service_items.deleted_at is null
			left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
			left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
			left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
			left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
			left join so_knits on so_knits.id=so_knit_refs.so_knit_id
			left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
			left join jobs on jobs.id=sales_orders.job_id
			left join styles on styles.id=jobs.style_id
			left join buyers on buyers.id=styles.buyer_id
			left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
			left join buyers customer on customer.id=so_knits.buyer_id
			) inhouseprods on inhouseprods.id = prod_knit_items.pl_knit_item_id 
			left join (
			select 
			po_knit_service_item_qties.id,
			colorranges.name as colorrange_name,
			style_fabrications.autoyarn_id,
			style_fabrications.gmtspart_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			sales_orders.sale_order_no,
			styles.style_ref,
			buyers.name as buyer_name,
			companies.name as customer_name  
			from 
			po_knit_service_item_qties
			join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
			join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
			left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
			join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
			join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
			join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id

			left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
			left join jobs on jobs.id=sales_orders.job_id
			left join styles on styles.id=jobs.style_id
			left join buyers on buyers.id=styles.buyer_id
			left join companies on companies.id=po_knit_services.company_id
			order by po_knit_service_item_qties.id
			) outhouseprods on outhouseprods.id = prod_knit_items.po_knit_service_item_qty_id 
			where (prod_knit_dlvs.id = ?) and prod_knit_dlvs.deleted_at is null
            ", [$id])
  )
   ->map(function ($yarn) use ($yarnDropdown) {
    $yarn->yarn_count = $yarn->count . "/" . $yarn->symbol;
    $yarn->composition = $yarn->item_account_id ? $yarnDropdown[$yarn->item_account_id] : '';
    return $yarn;
   });

  $yarnDtls = [];
  foreach ($yarn as $yar) {
   $index = $yar->gmtspart_id . "-" . $yar->autoyarn_id . "-" . $yar->fabric_look_id . "-" . $yar->fabric_shape_id . "-" . $yar->gsm_weight . "-" . $yar->dia_width . "-" . $yar->fabric_color . "-" . $yar->sale_order_no . "-" . $yar->stitch_length . "-" . $yar->style_ref . "-" . $yar->buyer_name . "-" . $yar->machine_no . "-" . $yar->machine_gg . "-" . $yar->machine_dia . "-" . $yar->machine_info_outside;
   $yarn = $yar->lot . " " . $yar->itemclass_name . " " . $yar->yarn_count . " " . $yar->composition . " " . $yar->yarn_type . " " . $yar->brand . " " . $yar->color_name;

   $yarnDtls[$index][$yarn] = $yarn;
  }



  $rolldtls = collect(
   \DB::select("
        select m.gmtspart_id,
        m.autoyarn_id,
        m.fabric_look_id,
        m.fabric_shape_id,
        m.gsm_weight,
        m.dia_width,
        m.fabric_color,
        m.fab_color_name,
        m.sale_order_no,
        m.stitch_length,
        m.style_ref,
        m.buyer_name,
        m.machine_no,
        m.machine_gg,
        m.machine_dia,
        m.machine_info_outside,
        sum(m.qc_pass_qty) as qty,
        sum(m.qty_pcs) as qty_pcs,
        count(id) as number_of_roll 
        from (
        select 
        prod_knit_dlv_rolls.id, 
        prod_knit_qcs.id as prod_knit_qc_id,   
        prod_knit_qcs.gsm_weight,   
        prod_knit_qcs.dia_width,   
        prod_knit_qcs.measurement,   
        prod_knit_qcs.roll_length,   
        prod_knit_qcs.shrink_per,   
        prod_knit_qcs.reject_qty,   
        prod_knit_qcs.qc_pass_qty,   
        prod_knit_qcs.reject_qty_pcs,   
        prod_knit_qcs.qc_pass_qty_pcs,   
        prod_knit_qcs.qc_result,

        prod_knit_item_rolls.id as prod_knit_item_roll_id,
        prod_knit_item_rolls.custom_no,
        prod_knit_item_rolls.roll_weight,
        prod_knit_item_rolls.width,
        prod_knit_item_rolls.qty_pcs,
        prod_knit_item_rolls.fabric_color,
        colors.name as fab_color_name,
        prod_knit_item_rolls.gmt_sample,
        prod_knit_items.prod_knit_id,
        prod_knit_items.stitch_length,
        prod_knit_items.machine_info_outside,
        prod_knits.shift_id,
        prod_knits.prod_no,
        prod_knits.supplier_id,
        prod_knits.location_id,
        prod_knits.floor_id,

        suppliers.name as supplier_name,
        locations.name as location_name,
        floors.name as floor_name,
        asset_quantity_costs.custom_no as machine_no,
        asset_technical_features.dia_width as machine_dia,
        asset_technical_features.gauge as machine_gg,
        gmtssamples.name as gmt_sample,
        case 
        when  inhouseprods.autoyarn_id is null then outhouseprods.autoyarn_id 
        else inhouseprods.autoyarn_id
        end as autoyarn_id,
        case 
        when  inhouseprods.gmtspart_id is null then outhouseprods.gmtspart_id 
        else inhouseprods.gmtspart_id
        end as gmtspart_id,
        case 
        when  inhouseprods.fabric_look_id is null then outhouseprods.fabric_look_id 
        else inhouseprods.fabric_look_id
        end as fabric_look_id,

        case 
        when  inhouseprods.fabric_shape_id is null then outhouseprods.fabric_shape_id 
        else inhouseprods.fabric_shape_id
        end as fabric_shape_id,
        case 
        when  inhouseprods.colorrange_name is null then outhouseprods.colorrange_name 
        else inhouseprods.colorrange_name
        end as colorrange_name,


        case 
        when  inhouseprods.sale_order_no is null then outhouseprods.sale_order_no 
        else inhouseprods.sale_order_no
        end as sale_order_no,
        case 
        when  inhouseprods.style_ref is null then outhouseprods.style_ref 
        else inhouseprods.style_ref
        end as style_ref,

        case 
        when  inhouseprods.buyer_name is null then outhouseprods.buyer_name 
        else inhouseprods.buyer_name
        end as buyer_name,

        case 
        when  inhouseprods.customer_name is null then outhouseprods.customer_name 
        else inhouseprods.customer_name
        end as customer_name


        from prod_knit_dlvs 
        inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
        inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
        inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
        inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
        inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
        inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
        inner join suppliers on suppliers.id = prod_knits.supplier_id 
        left join locations on locations.id = prod_knits.location_id 
        left join floors on floors.id = prod_knits.floor_id 
        left join asset_quantity_costs on asset_quantity_costs.id = prod_knit_items.asset_quantity_cost_id 
        left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
        left join gmtssamples on gmtssamples.id = prod_knit_item_rolls.gmt_sample
        left join colors on  colors.id=prod_knit_item_rolls.fabric_color
        left join (
        select 
        pl_knit_items.id,
        colorranges.name as colorrange_name,
        customer.name as customer_name,
        case 
        when  style_fabrications.autoyarn_id is null then so_knit_items.autoyarn_id 
        else style_fabrications.autoyarn_id
        end as autoyarn_id,

        case 
        when  style_fabrications.gmtspart_id is null then so_knit_items.gmtspart_id 
        else style_fabrications.gmtspart_id
        end as gmtspart_id,

        case 
        when  style_fabrications.fabric_look_id is null then so_knit_items.fabric_look_id 
        else style_fabrications.fabric_look_id
        end as fabric_look_id,

        case 
        when  style_fabrications.fabric_shape_id is null then so_knit_items.fabric_shape_id 
        else style_fabrications.fabric_shape_id
        end as fabric_shape_id,
        case 
        when sales_orders.sale_order_no is null then so_knit_items.gmt_sale_order_no 
        else sales_orders.sale_order_no
        end as sale_order_no,
        case 
        when styles.style_ref is null then so_knit_items.gmt_style_ref 
        else styles.style_ref
        end as style_ref,
        case 
        when buyers.name is null then outbuyers.name 
        else buyers.name
        end as buyer_name
        from pl_knit_items
        join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
        left join colorranges on colorranges.id=pl_knit_items.colorrange_id
        join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
        left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
        left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
        left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
        and po_knit_service_items.deleted_at is null
        left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
        left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
        left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
        left join so_knits on so_knits.id=so_knit_refs.so_knit_id
        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
        left join buyers customer on customer.id=so_knits.buyer_id
        ) inhouseprods on inhouseprods.id = prod_knit_items.pl_knit_item_id 
        left join (
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        buyers.name as buyer_name,
        companies.name as customer_name  
        from 
        po_knit_service_item_qties
        join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
        join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
        left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
        join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
        join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id

        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join companies on companies.id=po_knit_services.company_id
        order by po_knit_service_item_qties.id
        ) outhouseprods on outhouseprods.id = prod_knit_items.po_knit_service_item_qty_id where (prod_knit_dlvs.id = ?) and prod_knit_dlvs.deleted_at is null
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
        m.sale_order_no,
        m.stitch_length,
        m.style_ref,
        m.buyer_name,
        m.machine_no,
        m.machine_gg,
        m.machine_dia,
        m.machine_info_outside
            ", [$id])
  )
   ->map(function ($prodknitqc) use ($desDropdown, $fabriclooks, $fabricshape, $gmtspart, $yarnDtls) {
    $index = $prodknitqc->gmtspart_id . "-" . $prodknitqc->autoyarn_id . "-" . $prodknitqc->fabric_look_id . "-" . $prodknitqc->fabric_shape_id . "-" . $prodknitqc->gsm_weight . "-" . $prodknitqc->dia_width . "-" . $prodknitqc->fabric_color . "-" . $prodknitqc->sale_order_no . "-" . $prodknitqc->stitch_length . "-" . $prodknitqc->style_ref . "-" . $prodknitqc->buyer_name . "-" . $prodknitqc->machine_no . "-" . $prodknitqc->machine_gg . "-" . $prodknitqc->machine_dia . "-" . $prodknitqc->machine_info_outside;

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
  $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $header['logo'] = $rows->logo;
  $header['address'] = $rows->company_address;
  $header['title'] = 'Grey Fabric Roll Delivery to Store Challan / Gate Pass';
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
  $pdf->SetTitle('Grey Fabric Roll Delivery to Store Challan / Gate Pass');

  if (!$data['details']->max('machine_no')) {
   $view = \View::make('Defult.Production.Kniting.ProdKnitDlvChallanOutsidePdf', ['data' => $data]);
   $html_content = $view->render();
   $pdf->SetY(42);
   $pdf->WriteHtml($html_content, true, false, true, false, '');
   $filename = storage_path() . '/ProdKnitDlvChallanOutsidePdf.pdf';
   $pdf->output($filename);
  } else {
   $view = \View::make('Defult.Production.Kniting.ProdKnitDlvChallanPdf', ['data' => $data]);
   $html_content = $view->render();
   $pdf->SetY(42);
   $pdf->WriteHtml($html_content, true, false, true, false, '');
   $filename = storage_path() . '/ProdKnitDlvChallanPdf.pdf';
   $pdf->output($filename);
  }
 }

 public function getBill()
 {

  $id = request('id', 0);

  $company = $this->prodknitdlv
   ->join('prod_knit_dlv_rolls', function ($join) {
    $join->on('prod_knit_dlv_rolls.prod_knit_dlv_id', '=', 'prod_knit_dlvs.id');
   })
   ->join('prod_knit_qcs', function ($join) {
    $join->on('prod_knit_qcs.id', '=', 'prod_knit_dlv_rolls.prod_knit_qc_id');
   })
   ->join('prod_knit_rcv_by_qcs', function ($join) {
    $join->on('prod_knit_rcv_by_qcs.id', '=', 'prod_knit_qcs.prod_knit_rcv_by_qc_id');
   })
   ->join('prod_knit_item_rolls', function ($join) {
    $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
   })
   ->join('prod_knit_items', function ($join) {
    $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
   })
   ->join('prod_knits', function ($join) {
    $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'prod_knits.supplier_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'suppliers.company_id');
   })
   ->where([['prod_knit_dlvs.id', '=', $id]])
   ->where([['suppliers.company_id', '>', 0]])
   ->get([
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
   ])
   ->first();

  if (!$company) {
   return "<h1>Bill is not applicable for this Knitting Company</h1>";
  } else {

   $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
   $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
   $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
   $currency = array_prepend(array_pluck($this->currency->get(), 'code', 'id'), '-Select-', '');
   $symbol = array_prepend(array_pluck($this->currency->get(), 'hundreds_name', 'id'), '-Select-', '');

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

   $rows = $this->prodknitdlv
    ->leftJoin('companies', function ($join) {
     $join->on('prod_knit_dlvs.company_id', '=', 'companies.id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('prod_knit_dlvs.buyer_id', '=', 'buyers.id');
    })
    ->leftJoin('stores', function ($join) {
     $join->on('prod_knit_dlvs.store_id', '=', 'stores.id');
    })
    ->join('users', function ($join) {
     $join->on('users.id', '=', 'prod_knit_dlvs.created_by');
    })
    ->leftJoin('employee_h_rs', function ($join) {
     $join->on('users.id', '=', 'employee_h_rs.user_id');
    })
    ->where([['prod_knit_dlvs.id', '=', $id]])
    ->orderBy('prod_knit_dlvs.id', 'desc')
    ->get([
     'prod_knit_dlvs.*',
     'companies.name as company_name',
     'companies.logo as logo',
     'companies.address as company_address',
     'buyers.name as buyer_name',
     'stores.name as store_name',
     'users.name as user_name',
     'employee_h_rs.contact'
    ])
    ->first();
   $rows->dlv_date = date('d-M-Y', strtotime($rows->dlv_date));

   $rolldtls = collect(
    \DB::select("
            select 
            m.gmtspart_id,
            m.autoyarn_id,
            m.fabric_look_id,
            m.fabric_shape_id,
            m.gsm_weight,
            m.dia_width,
            m.measurement,
            m.stitch_length,
            m.currency_id,
            avg(m.rate) as rate,
            sum(m.qc_pass_qty) as qty,
            sum(m.qty_pcs) as qty_pcs,
            count(id) as number_of_roll

            from (
                select 
                prod_knit_dlv_rolls.id, 
                prod_knit_qcs.id as prod_knit_qc_id,   
                prod_knit_qcs.gsm_weight,   
                prod_knit_qcs.dia_width,   
                prod_knit_qcs.measurement,
                prod_knit_qcs.reject_qty,   
                prod_knit_qcs.qc_pass_qty,   
                prod_knit_qcs.reject_qty_pcs,   
                prod_knit_qcs.qc_pass_qty_pcs,   
                prod_knit_qcs.qc_result,

                prod_knit_item_rolls.id as prod_knit_item_roll_id,
                prod_knit_item_rolls.custom_no,
                prod_knit_item_rolls.roll_weight,
                prod_knit_item_rolls.width,
                prod_knit_item_rolls.qty_pcs,
                prod_knit_item_rolls.fabric_color,
                prod_knit_item_rolls.gmt_sample,
                prod_knit_items.prod_knit_id,
                prod_knit_items.stitch_length,

                prod_knits.shift_id,
                prod_knits.prod_no,
                prod_knits.supplier_id,
                prod_knits.location_id,
                prod_knits.floor_id,

                suppliers.name as supplier_name,
                locations.name as location_name,
                floors.name as floor_name,
                asset_quantity_costs.custom_no as machine_no,
                asset_technical_features.dia_width as machine_dia,
                asset_technical_features.gauge as machine_gg,
                gmtssamples.name as gmt_sample,
                inhouseprods.autoyarn_id,
                inhouseprods.gmtspart_id,
                inhouseprods.fabric_look_id,
                inhouseprods.fabric_shape_id,
                inhouseprods.colorrange_name,
                inhouseprods.sale_order_no,
                inhouseprods.style_ref,
                inhouseprods.buyer_name,
                inhouseprods.customer_name,
                inhouseprods.currency_id,
                inhouseprods.rate

                from prod_knit_dlvs 
                inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id
                inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id
                inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id
                inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id
                inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id
                inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id
                inner join suppliers on suppliers.id = prod_knits.supplier_id
                left join locations on locations.id = prod_knits.location_id
                left join floors on floors.id = prod_knits.floor_id 
                left join asset_quantity_costs on asset_quantity_costs.id = prod_knit_items.asset_quantity_cost_id 
                left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
                left join gmtssamples on gmtssamples.id = prod_knit_item_rolls.gmt_sample 
                left join (
                    select 
                    pl_knit_items.id,
                    colorranges.name as colorrange_name,
                    customer.name as customer_name,

                    case 
                    when  style_fabrications.autoyarn_id is null then so_knit_items.autoyarn_id 
                    else style_fabrications.autoyarn_id
                    end as autoyarn_id,

                    case 
                    when  style_fabrications.gmtspart_id is null then so_knit_items.gmtspart_id 
                    else style_fabrications.gmtspart_id
                    end as gmtspart_id,

                    case 
                    when  style_fabrications.fabric_look_id is null then so_knit_items.fabric_look_id 
                    else style_fabrications.fabric_look_id
                    end as fabric_look_id,

                    case 
                    when  style_fabrications.fabric_shape_id is null then so_knit_items.fabric_shape_id 
                    else style_fabrications.fabric_shape_id
                    end as fabric_shape_id,

                    case 
                    when sales_orders.sale_order_no is null then so_knit_items.gmt_sale_order_no 
                    else sales_orders.sale_order_no
                    end as sale_order_no,

                    case 
                    when styles.style_ref is null then so_knit_items.gmt_style_ref 
                    else styles.style_ref
                    end as style_ref,

                    case 
                    when buyers.name is null then outbuyers.name 
                    else buyers.name
                    end as buyer_name,

                    CASE 
                    WHEN  so_knit_items.rate IS NULL THEN po_knit_service_item_qties.rate
                    ELSE so_knit_items.rate
                    END as rate,

                    case 
                    when currencies.id is null then po_currency.id
                    else currencies.id
                    end as currency_id

                    from pl_knit_items
                    join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
                    left join colorranges on colorranges.id=pl_knit_items.colorrange_id
                    join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
                    left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
                    left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
                    left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
                    and po_knit_service_items.deleted_at is null
                    left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
                    left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                    left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                    left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
                    left join so_knits on so_knits.id=so_knit_refs.so_knit_id
                    left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                    left join jobs on jobs.id=sales_orders.job_id
                    left join styles on styles.id=jobs.style_id
                    left join buyers on buyers.id=styles.buyer_id
                    left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
                    left join buyers customer on customer.id=so_knits.buyer_id
                    left join currencies on currencies.id=so_knits.currency_id
                    left join po_knit_services on po_knit_service_items.po_knit_service_id=po_knit_services.id
                    left join currencies po_currency on currencies.id=po_knit_services.currency_id
                ) inhouseprods on inhouseprods.id = prod_knit_items.pl_knit_item_id 
                where (prod_knit_dlvs.id = ?) and prod_knit_dlvs.deleted_at is null
                group by
                prod_knit_dlv_rolls.id, 
                prod_knit_qcs.id,
                prod_knit_qcs.gsm_weight,
                prod_knit_qcs.dia_width,
                prod_knit_qcs.measurement,
                prod_knit_qcs.reject_qty,
                prod_knit_qcs.qc_pass_qty,   
                prod_knit_qcs.reject_qty_pcs,   
                prod_knit_qcs.qc_pass_qty_pcs,   
                prod_knit_qcs.qc_result,

                prod_knit_item_rolls.id,
                prod_knit_item_rolls.custom_no,
                prod_knit_item_rolls.roll_weight,
                prod_knit_item_rolls.width,
                prod_knit_item_rolls.qty_pcs,
                prod_knit_item_rolls.fabric_color,
                prod_knit_item_rolls.gmt_sample,
                prod_knit_items.prod_knit_id,
                prod_knit_items.stitch_length,

                prod_knits.shift_id,
                prod_knits.prod_no,
                prod_knits.supplier_id,
                prod_knits.location_id,
                prod_knits.floor_id,

                suppliers.name,
                locations.name,
                floors.name,
                asset_quantity_costs.custom_no,
                asset_technical_features.dia_width,
                asset_technical_features.gauge,
                gmtssamples.name,
                inhouseprods.autoyarn_id,
                inhouseprods.gmtspart_id,
                inhouseprods.fabric_look_id,
                inhouseprods.fabric_shape_id,
                inhouseprods.colorrange_name,
                inhouseprods.sale_order_no,
                inhouseprods.style_ref,
                inhouseprods.buyer_name,
                inhouseprods.customer_name,
                inhouseprods.currency_id,
                inhouseprods.rate
            ) m  
            group by 
            m.gmtspart_id,
            m.autoyarn_id,
            m.fabric_look_id,
            m.fabric_shape_id,
            m.gsm_weight,
            m.dia_width,
            m.measurement,
            m.stitch_length,
            m.currency_id
            ", [$id])
   )
    ->map(function ($prodknitqc) use ($desDropdown, $fabriclooks, $fabricshape, $gmtspart) {
     $prodknitqc->body_part = $prodknitqc->gmtspart_id ? $gmtspart[$prodknitqc->gmtspart_id] : '';
     $prodknitqc->fabrication = $prodknitqc->autoyarn_id ? $desDropdown[$prodknitqc->autoyarn_id] : '';
     $prodknitqc->fabric_look = $prodknitqc->fabric_look_id ? $fabriclooks[$prodknitqc->fabric_look_id] : '';
     $prodknitqc->fabric_shape = $prodknitqc->fabric_shape_id ? $fabricshape[$prodknitqc->fabric_shape_id] : '';
     if ($prodknitqc->qty_pcs) {
      $prodknitqc->amount = $prodknitqc->qty_pcs * $prodknitqc->rate;
     } else {
      $prodknitqc->amount = $prodknitqc->qty * $prodknitqc->rate;
     }

     return $prodknitqc;
    });

   $data['master']    = $rows;
   $data['details']   = $rolldtls->groupBy(['currency_id']);
   //$amount=$data['details']->sum('amount');
   //$inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
   //$rows->inword=$inword;


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
   $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
   $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
   $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
   $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
   $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
   $header['logo'] = $company->logo;
   $header['address'] = $company->company_address;
   $header['title'] = 'Knitting Bill';
   $header['barcodestyle'] = $barcodestyle;
   $header['barcodeno'] = $challan;
   $pdf->setCustomHeader($header);
   $pdf->SetFont('helvetica', 'B', 12);
   $pdf->AddPage();
   $pdf->SetFont('helvetica', '', 8);
   $pdf->SetTitle('Knitting Bill');
   $view = \View::make('Defult.Production.Kniting.ProdKnitDlvBill', ['data' => $data, 'currency' => $currency, 'symbol' => $symbol]);
   $html_content = $view->render();
   $pdf->SetY(42);
   $pdf->WriteHtml($html_content, true, false, true, false, '');
   $filename = storage_path() . '/ProdKnitDlvBill.pdf';
   $pdf->output($filename);
  }
 }

 public function searchProdKint()
 {
  $rows = $this->prodknitdlv
   ->leftJoin('companies', function ($join) {
    $join->on('prod_knit_dlvs.company_id', '=', 'companies.id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('prod_knit_dlvs.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('stores', function ($join) {
    $join->on('prod_knit_dlvs.store_id', '=', 'stores.id');
   })
   ->when(request('company_search_id'), function ($q) {
    return $q->where('prod_knit_dlvs.company_id', "=", request('company_search_id'));
   })
   ->when(request('store_search_id'), function ($q) {
    return $q->where('prod_knit_dlvs.store_id', '=', request('store_search_id'));
   })
   ->when(request('from_date'), function ($q) {
    return $q->where('prod_knit_dlvs.dlv_date', '>=', request('from_date'));
   })
   ->when(request('to_date'), function ($q) {
    return $q->where('prod_knit_dlvs.dlv_date', '<=', request('to_date'));
   })
   ->orderBy('prod_knit_dlvs.id', 'desc')
   ->get([
    'prod_knit_dlvs.*',
    'companies.name as company_name',
    'buyers.name as buyer_name',
    'stores.name as store_name',
   ])->map(function ($rows) {
    $rows->dlv_date = date('d-M-Y', strtotime($rows->dlv_date));
    return $rows;
   });
  return response()->json($rows);
 }
}
