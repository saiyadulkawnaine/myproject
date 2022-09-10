<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonEntryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtExFactoryRequest;

class ProdGmtExFactoryController extends Controller
{

 private $prodgmtexfactory;
 private $company;
 private $prodgmtcarton;
 private $location;
 private $buyer;
 private $supplier;
 private $expinvoice;



 public function __construct(ProdGmtExFactoryRepository $prodgmtexfactory, ProdGmtCartonEntryRepository $prodgmtcarton, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier, BuyerRepository $buyer, CountryRepository $country, ExpInvoiceRepository $expinvoice)
 {
  $this->prodgmtexfactory = $prodgmtexfactory;
  $this->prodgmtcarton = $prodgmtcarton;
  $this->company = $company;
  $this->location = $location;
  $this->supplier = $supplier;
  $this->country = $country;
  $this->buyer = $buyer;
  $this->expinvoice = $expinvoice;


  $this->middleware('auth');
  $this->middleware('permission:view.prodgmtexfactories',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.prodgmtexfactories', ['only' => ['store']]);
  $this->middleware('permission:edit.prodgmtexfactories',   ['only' => ['update']]);
  $this->middleware('permission:delete.prodgmtexfactories', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $forwardingAgents = array_prepend(array_pluck($this->supplier->forwardingAgents(), 'name', 'id'), '-Select-', '');
  $transportAgents = array_prepend(array_pluck($this->supplier->transportAgents(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'code', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');

  $prodgmtexfactorys = array();
  $rows = $this->prodgmtexfactory
   ->leftJoin('exp_invoices', function ($join) {
    $join->on('exp_invoices.id', '=', 'prod_gmt_ex_factories.exp_invoice_id');
   })
   ->orderBy('prod_gmt_ex_factories.id', 'desc')
   ->get([
    'prod_gmt_ex_factories.*',
    'exp_invoices.invoice_no'
   ]);
  foreach ($rows as $row) {
   $prodgmtexfactory['id'] = $row->id;
   $prodgmtexfactory['buyer_id'] = isset($buyer[$row->buyer_id]) ? $buyer[$row->buyer_id] : '';
   $prodgmtexfactory['company_id'] = isset($company[$row->company_id]) ? $company[$row->company_id] : '';
   $prodgmtexfactory['location_id'] = isset($location[$row->location_id]) ? $location[$row->location_id] : '';
   $prodgmtexfactory['transport_agent_id'] = $transportAgents[$row->transport_agent_id];
   $prodgmtexfactory['forwarding_agent_id'] = $forwardingAgents[$row->forwarding_agent_id];
   $prodgmtexfactory['exfactory_date'] = date('Y-m-d', strtotime($row->exfactory_date));
   $prodgmtexfactory['invoice_no'] = $row->invoice_no;
   $prodgmtexfactory['port_of_loading'] = $row->port_of_loading;
   $prodgmtexfactory['driver_name'] = $row->driver_name;
   $prodgmtexfactory['driver_contact_no'] = $row->driver_contact_no;
   $prodgmtexfactory['driver_license_no'] = $row->driver_license_no;
   $prodgmtexfactory['lock_no'] = $row->lock_no;
   $prodgmtexfactory['truck_no'] = $row->truck_no;
   $prodgmtexfactory['depo_name'] = $row->depo_name;

   array_push($prodgmtexfactorys, $prodgmtexfactory);
  }
  echo json_encode($prodgmtexfactorys);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {

  $company = array_prepend(array_pluck($this->company->where([['nature_id', '=', 1]])->get(), 'name', 'id'), '-Select-', '');
  $country = array_prepend(array_pluck($this->country->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->buyers(), 'name', 'id'), '', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $forwardingAgents = array_prepend(array_pluck($this->supplier->forwardingAgents(), 'name', 'id'), '-Select-', '');
  $transportAgents = array_prepend(array_pluck($this->supplier->transportAgents(), 'name', 'id'), '-Select-', '');

  return Template::loadView('Production.Garments.ProdGmtExFactory', ['forwardingAgents' => $forwardingAgents, 'transportAgents' => $transportAgents, 'buyer' => $buyer, 'company' => $company, 'location' => $location, 'country' => $country]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProdGmtExFactoryRequest $request)
 {
  $prodgmtexfactory = $this->prodgmtexfactory->create($request->except(['id', 'supplier_id']));
  if ($prodgmtexfactory) {
   return response()->json(array('success' => true, 'id' =>  $prodgmtexfactory->id, 'message' => 'Save Successfully'), 200);
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
  $prodgmtexfactory = $this->prodgmtexfactory->leftJoin('exp_invoices', function ($join) {
   $join->on('exp_invoices.id', '=', 'prod_gmt_ex_factories.exp_invoice_id');
  })
   ->where([['prod_gmt_ex_factories.id', '=', $id]])
   ->get([
    'prod_gmt_ex_factories.*',
    'exp_invoices.invoice_no'
   ])
   ->map(function ($prodgmtexfactory) {
    $prodgmtexfactory->exfactory_date = date('Y-m-d', strtotime($prodgmtexfactory->exfactory_date));
    return $prodgmtexfactory;
   })
   ->first();
  $row['fromData'] = $prodgmtexfactory;
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
 public function update(ProdGmtExFactoryRequest $request, $id)
 {
  $prodgmtexfactory = $this->prodgmtexfactory->update($id, $request->except(['id', 'supplier_id', 'buyer_id']));
  if ($prodgmtexfactory) {
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
  if ($this->prodgmtexfactory->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getExpInvoice()
 {
  $country = array_prepend(array_pluck($this->country->get(), 'name', 'id'), '-Select-', '');

  $expinvoices = array();
  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->leftJoin('exp_adv_invoices', function ($join) {
    $join->on('exp_adv_invoices.id', '=', 'exp_invoices.exp_adv_invoice_id');
   })
   ->orderBy('exp_invoices.id', 'desc')
   ->get([
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.beneficiary_id',
    'exp_lc_scs.buyer_id',
    'buyers.name as buyer_id',
    'companies.name as beneficiary_id',
    'exp_lc_scs.lien_date',
    'exp_lc_scs.hs_code',
    'exp_lc_scs.re_imbursing_bank',
    'exp_invoices.*',
    'exp_adv_invoices.invoice_no as advance_invoice_no'
   ]);
  foreach ($rows as $row) {
   $expinvoice['id'] = $row->id;
   $expinvoice['lc_sc_no'] = $row->lc_sc_no;
   $expinvoice['invoice_no'] = $row->invoice_no;
   $expinvoice['invoice_date'] = date('Y-m-d', strtotime($row->invoice_date));
   $expinvoice['invoice_value'] = number_format($row->invoice_value, 2);
   $expinvoice['net_inv_value'] = number_format($row->net_inv_value, 2);
   $expinvoice['exp_form_no'] = $row->exp_form_no;
   $expinvoice['exp_form_date'] = ($row->exp_form_date !== null) ? date('Y-m-d', strtotime($row->exp_form_date)) : null;
   $expinvoice['actual_ship_date'] = ($row->actual_ship_date !== null) ? date('Y-m-d', strtotime($row->actual_ship_date)) : null;
   $expinvoice['country_id'] = $country[$row->country_id];
   $expinvoice['shipping_bill_no'] = $row->shipping_bill_no;
   $expinvoice['net_wgt_exp_qty'] = number_format($row->net_wgt_exp_qty, 2);
   $expinvoice['gross_wgt_exp_qty'] = number_format($row->gross_wgt_exp_qty, 2);
   $expinvoice['remarks'] = $row->remarks;
   $expinvoice['advance_invoice_no'] = $row->advance_invoice_no;
   array_push($expinvoices, $expinvoice);
  }
  echo json_encode($expinvoices);
 }

 public function ExfactoryPdf()
 {
  $id = request('id', 0);
  $transportAgents = array_prepend(array_pluck($this->supplier->transportAgents(), 'name', 'id'), '-Select-', '');

  $prodgmtexfactorys = array();
  $rows = $this->prodgmtexfactory
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'prod_gmt_ex_factories.buyer_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'prod_gmt_ex_factories.company_id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('locations.id', '=', 'prod_gmt_ex_factories.location_id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'prod_gmt_ex_factories.forwarding_agent_id');
   })
   ->leftJoin('exp_invoices', function ($join) {
    $join->on('exp_invoices.id', '=', 'prod_gmt_ex_factories.exp_invoice_id');
   })
   ->where([['prod_gmt_ex_factories.id', '=', $id]])
   ->get([
    'prod_gmt_ex_factories.*',
    'exp_invoices.invoice_no',
    'companies.id as company_id',
    'suppliers.name as forwarding_agent_id',
    'suppliers.address as forwarding_agent_address'
   ]);
  foreach ($rows as $row) {
   $prodgmtexfactory['id'] = $row->id;
   $prodgmtexfactory['buyer_id'] = $row->buyer_id;
   $prodgmtexfactory['company_id'] = $row->company_id;
   $prodgmtexfactory['location_id'] = $row->location_id;
   $prodgmtexfactory['transport_agent_id'] = $transportAgents[$row->transport_agent_id];
   $prodgmtexfactory['forwarding_agent_id'] = $row->forwarding_agent_id;
   $prodgmtexfactory['forwarding_agent_address'] = $row->forwarding_agent_address;
   $prodgmtexfactory['exfactory_date'] = date('d-M-Y', strtotime($row->exfactory_date));
   $prodgmtexfactory['invoice_no'] = $row->invoice_no;
   $prodgmtexfactory['port_of_loading'] = $row->port_of_loading;
   $prodgmtexfactory['driver_name'] = $row->driver_name;
   $prodgmtexfactory['driver_contact_no'] = $row->driver_contact_no;
   $prodgmtexfactory['driver'] = $row->driver_name . ",( " . $row->driver_contact_no . " )";
   $prodgmtexfactory['driver_license_no'] = $row->driver_license_no;
   $prodgmtexfactory['lock_no'] = $row->lock_no;
   $prodgmtexfactory['truck_no'] = $row->truck_no;
   $prodgmtexfactory['recipient'] = $row->recipient;
   $prodgmtexfactory['depo_name'] = $row->depo_name;
   $prodgmtexfactory['remarks'] = $row->remarks;
   $prodgmtexfactory['produced_company_id'] = $row->produced_company_id;
  }


  $gmtexfactoryqty = $this->prodgmtexfactory
   ->join('prod_gmt_ex_factory_qties', function ($join) {
    $join->on('prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id', '=', 'prod_gmt_ex_factories.id');
   })
   ->join('prod_gmt_carton_details', function ($join) {
    $join->on('prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id', '=', 'prod_gmt_carton_details.id');
   })
   ->join('prod_gmt_carton_entries', function ($join) {
    $join->on('prod_gmt_carton_details.prod_gmt_carton_entry_id', '=', 'prod_gmt_carton_entries.id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.id', '=', 'prod_gmt_carton_details.sales_order_country_id');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->join('companies as produced_company', function ($join) {
    $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })

   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.style_id', '=', 'styles.id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->join(\DB::raw("(SELECT 
        sales_orders.id as sale_order_id,
        sum(style_pkg_ratios.qty) as qty 
        FROM 
        prod_gmt_carton_entries
        join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
        join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
        join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
        join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
        join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null
          
        where prod_gmt_ex_factories.id=$id 
        group by sales_orders.id

        ) stylepkgratios"), "stylepkgratios.sale_order_id", "=", "sales_orders.id")

   ->join(\DB::raw("(select  sales_orders.id as sale_order_id,count(prod_gmt_ex_factory_qties.id) as no_of_carton   from prod_gmt_ex_factories 
        inner join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id = prod_gmt_ex_factories.id 
        and prod_gmt_ex_factory_qties.deleted_at is null
    
        inner join prod_gmt_carton_details on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
        
        inner join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id 
        left join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        where prod_gmt_ex_factories.id=$id
        group by sales_orders.id

        ) nocarton"), "nocarton.sale_order_id", "=", "sales_orders.id")

   ->where([['prod_gmt_ex_factories.id', '=', $id]])
   ->selectRaw('
        prod_gmt_ex_factories.id,
        buyers.id as buyer_id,
        buyers.name as buyer_name,
        styles.id as style_id,
        styles.style_ref as style_ref,
        jobs.company_id,
        item_accounts.id as item_account_id,
        item_accounts.item_description,
        sales_orders.id as sales_order_id,
        sales_orders.sale_order_no,
        sales_orders.produced_company_id,
        companies.name as company_name,
        produced_company.name as produced_company_name,
        stylepkgratios.qty,
        nocarton.no_of_carton 
        ')
   ->groupBy([
    'prod_gmt_ex_factories.id',
    'buyers.id',
    'buyers.name',
    'styles.id',
    'styles.style_ref',
    'jobs.company_id',
    'item_accounts.id',
    'item_accounts.item_description',
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'sales_orders.produced_company_id',
    'companies.name',
    'produced_company.name',
    'stylepkgratios.qty',
    'nocarton.no_of_carton'
   ])
   ->get()
   ->map(function ($gmtexfactoryqty) {
    return $gmtexfactoryqty;
   });
  $produced_company_id = 0;
  $saved = array();
  $styleArr = array();
  $i = 0;
  foreach ($gmtexfactoryqty as $rows) {
   $saved[$rows->buyer_id][$rows->style_id]['buyer_name'] = $rows->buyer_name;
   $saved[$rows->buyer_id][$rows->style_id]['style_ref'] = $rows->style_ref;
   $saved[$rows->buyer_id][$rows->style_id]['company_name'] = $rows->company_name;
   $saved[$rows->buyer_id][$rows->style_id]['sale_order_no'][$rows->sales_order_id] = $rows->sale_order_no;

   $saved[$rows->buyer_id][$rows->style_id]['item_description'][$rows->item_description] = $rows->item_description;
   $saved[$rows->buyer_id][$rows->style_id]['produced_company_id'][$rows->produced_company_id] = $rows->produced_company_name;
   $saved[$rows->buyer_id][$rows->style_id]['qty'][$rows->sales_order_id] = $rows->qty;
   $saved[$rows->buyer_id][$rows->style_id]['no_of_carton'][$rows->sales_order_id] = $rows->no_of_carton;
   $produced_company_id = $rows->produced_company_id;
   $i++;
  }
  if (!$i) {
   echo "<h2 style='margin:20 auto;padding:20px;font:30'>NO DATA FOUND</h2>";
   die;
  }
  $company = $this->company
   ->where([['id', '=', $produced_company_id]])
   ->get()->first();


  $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $header = ['logo' => $company->logo, 'address' => $company->address, 'title' => 'GARMENTS DELIVERY CHALLAN & GATE PASS'];
  $pdf->setCustomHeader($header);
  $pdf->SetPrintHeader(true);
  //$pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(true);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
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
  $challan = str_pad($prodgmtexfactory['id'], 10, 0, STR_PAD_LEFT);
  $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
  $pdf->SetFont('helvetica', '', 8);
  $view = \View::make('Defult.Production.Garments.ExfactoryPdf', ['prodgmtexfactory' => $prodgmtexfactory, 'saved' => $saved]);
  $html_content = $view->render();
  $pdf->SetY(42);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/ExfactoryPdf.pdf';
  $pdf->output($filename);
  exit();
 }
}
