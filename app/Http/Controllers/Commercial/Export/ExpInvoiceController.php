<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceRepository;
use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Commercial\Export\ExpInvoiceRequest;

class ExpInvoiceController extends Controller
{

 private $expinvoice;
 private $bankaccount;
 private $company;
 private $country;
 private $explcsc;
 private $buyer;
 private $budgetfabric;
 private $budget;
 private $salesorder;
 private $expadvinvoice;

 public function __construct(ExpInvoiceRepository $expinvoice, BankAccountRepository $bankaccount, CompanyRepository $company, ExpLcScRepository $explcsc, CountryRepository $country, BuyerRepository $buyer, BudgetFabricRepository $budgetfabric, BudgetRepository $budget, SalesOrderRepository $salesorder, ExpAdvInvoiceRepository $expadvinvoice)
 {

  $this->explcsc = $explcsc;
  $this->expinvoice = $expinvoice;
  $this->bankaccount = $bankaccount;
  $this->company = $company;
  $this->country = $country;
  $this->buyer = $buyer;
  $this->budgetfabric = $budgetfabric;
  $this->budget = $budget;
  $this->salesorder = $salesorder;
  $this->expadvinvoice = $expadvinvoice;

  $this->middleware('auth');
  $this->middleware('permission:view.expinvoices',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.expinvoices', ['only' => ['store']]);
  $this->middleware('permission:edit.expinvoices',   ['only' => ['update']]);
  $this->middleware('permission:delete.expinvoices', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {

  $country = array_prepend(array_pluck($this->country->get(), 'name', 'id'), '-Select-', '');
  $invoicestatus = array_prepend(config('bprs.invoicestatus'), '', '');
  $expinvoices = array();
  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->leftJoin('exp_adv_invoices', function ($join) {
    $join->on('exp_adv_invoices.id', '=', 'exp_invoices.exp_adv_invoice_id');
   })
   ->leftJoin('users', function ($join) {
    $join->on('users.id', '=', 'exp_invoices.updated_by');
   })
   ->orderBy('exp_invoices.id', 'desc')
   ->get([
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.beneficiary_id',
    'exp_lc_scs.buyer_id',
    'buyers.code as buyer_code',
    'companies.name as beneficiary_id',
    'exp_lc_scs.lien_date',
    'exp_lc_scs.hs_code',
    'exp_lc_scs.re_imbursing_bank',
    'exp_invoices.*',
    'exp_adv_invoices.invoice_no as advance_invoice_no',
    'users.name as updated_by_name'
   ]);
  foreach ($rows as $row) {
   $expinvoice['id'] = $row->id;
   $expinvoice['lc_sc_no'] = $row->lc_sc_no;
   $expinvoice['invoice_no'] = $row->invoice_no;
   $expinvoice['buyer_code'] = $row->buyer_code;
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
   $expinvoice['invoice_status_id'] = $invoicestatus[$row->invoice_status_id];
   $expinvoice['advance_invoice_no'] = $row->advance_invoice_no;
   $expinvoice['updated_by_name'] = $row->updated_by_name;
   $expinvoice['updated_at'] = date('Y-m-d H:i:s A', strtotime($row->updated_at));
   array_push($expinvoices, $expinvoice);
  }
  echo json_encode($expinvoices);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $country = array_prepend(array_pluck($this->country->get(), 'name', 'id'), '-Select-', '');
  $incoterm = array_prepend(config('bprs.incoterm'), '-Select-', '');
  $deliveryMode = array_prepend(config('bprs.deliveryMode'), '-Select-', '');
  $invoicestatus = array_prepend(config('bprs.invoicestatus'), '-Select-', '');
  $consignee = array_prepend(array_pluck($this->buyer->consignee(), 'name', 'id'), '', '');
  $notifyingParties = array_prepend(array_pluck($this->buyer->notifyingParties(), 'name', 'id'), '', '');
  return Template::LoadView('Commercial.Export.ExpInvoice', ['company' => $company, 'country' => $country, 'incoterm' => $incoterm, 'deliveryMode' => $deliveryMode, 'invoicestatus' => $invoicestatus, 'consignee' => $consignee, 'notifyingParties' => $notifyingParties]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ExpInvoiceRequest $request)
 {
  $request->request->add(['discount_amount' => 0]);
  $request->request->add(['bonus_amount' => 0]);
  $request->request->add(['commission' => 0]);
  $request->request->add(['claim_amount' => 0]);
  $request->request->add(['up_charge_amount' => 0]);
  $expinvoice = $this->expinvoice->create($request->except(['id', 'lc_sc_no', 'buyer_id', 'company_id', 'beneficiary_id', 'lien_date', 'hs_code', 'adv_invoice_no']));

  if ($expinvoice) {
   return response()->json(array('success' => true, 'id' =>  $expinvoice->id, 'message' => 'Save Successfully'), 200);
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
  $expinvoice = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('exp_adv_invoices', function ($join) {
    $join->on('exp_adv_invoices.id', '=', 'exp_invoices.exp_adv_invoice_id');
   })
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    'exp_lc_scs.id as exp_lc_sc_id',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.beneficiary_id',
    'exp_lc_scs.buyer_id',
    'exp_lc_scs.lien_date',
    'exp_lc_scs.hs_code',
    'buyers.name as buyer_id',
    'companies.name as beneficiary_id',
    'exp_adv_invoices.invoice_no as adv_invoice_no',
   ])
   ->first();
  $expinvoice->invoice_amount = $expinvoice->invoice_value;
  $row['fromData'] = $expinvoice;
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
 public function update(ExpInvoiceRequest $request, $id)
 {
  $expinvoice = $this->expinvoice->update($id, $request->except(['id', 'lc_sc_no', 'buyer_id', 'company_id', 'beneficiary_id', 'lien_date', 'hs_code', 'adv_invoice_no']));
  if ($expinvoice) {
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
  if ($this->expinvoice->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getLcSc()
 {

  $contractNature = array_prepend(config('bprs.contractNature'), '-Select-', '');
  $rows = $this->explcsc
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   ->when(request('lc_sc_no'), function ($q) {
    return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%" . request('lc_sc_no', 0) . "%");
   })
   ->when(request('beneficiary_id'), function ($q) {
    return $q->where('exp_lc_scs.beneficiary_id', '=', request('beneficiary_id', 0));
   })
   ->when(request('lc_sc_date'), function ($q) {
    return $q->where('exp_lc_scs.lc_sc_date', '=', request('lc_sc_date', 0));
   })
   //->groupBy(['exp_lc_scs.id'])
   ->orderBy('exp_lc_scs.id', 'desc')
   ->get([
    'exp_lc_scs.*',
    'buyers.name as buyer_id',
    'companies.name as beneficiary_id',
    'currencies.name as currency_id'
   ])
   ->map(function ($rows) use ($contractNature) {
    $rows->lc_sc_nature = $contractNature[$rows->lc_sc_nature_id];
    $rows->lc_sc_date = date('d-M-Y', strtotime($rows->lc_sc_date));
    $rows->last_delivery_date = date('d-M-Y', strtotime($rows->last_delivery_date));
    return $rows;
   });
  echo json_encode($rows);
 }

 public function getAdvanceInvoice()
 {
  $rows = $this->expadvinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_adv_invoices.exp_lc_sc_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'exp_adv_invoices.country_id');
   })
   ->when(request('invoice_no'), function ($q) {
    return $q->where('exp_adv_invoices.invoice_no', 'LIKE', "%" . request('invoice_no', 0) . "%");
   })
   ->when(request('invoice_date'), function ($q) {
    return $q->where('exp_adv_invoices.invoice_date', '=', request('invoice_date', 0));
   })
   ->orderBy('exp_adv_invoices.id', 'desc')
   ->get([
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.beneficiary_id',
    'exp_lc_scs.buyer_id',
    'buyers.name as buyer_id',
    'companies.name as beneficiary_id',
    'exp_lc_scs.lien_date',
    'exp_lc_scs.hs_code',
    'exp_lc_scs.re_imbursing_bank',
    'exp_adv_invoices.*',
    'countries.name as country_name'
   ])
   ->map(function ($rows) {
    $rows->invoice_date = date('Y-m-d', strtotime($rows->invoice_date));
    $rows->exp_form_date = ($rows->exp_form_date !== null) ? date('Y-m-d', strtotime($rows->exp_form_date)) : null;
    $rows->actual_ship_date = ($rows->actual_ship_date !== null) ? date('Y-m-d', strtotime($rows->actual_ship_date)) : null;
    $rows->net_wgt_exp_qty = number_format($rows->net_wgt_exp_qty, 2);
    $rows->gross_wgt_exp_qty = number_format($rows->gross_wgt_exp_qty, 2);
    return $rows;
   });

  echo json_encode($rows);
 }

 public function getPortOfEntry(Request $request)
 {
  return $this->expinvoice->where([['port_of_entry', 'LIKE', '%' . $request->q . '%']])->orderBy('port_of_entry', 'asc')->get(['port_of_entry as name']);
 }

 public function getPortOfLoading(Request $request)
 {
  return $this->expinvoice->where([['port_of_loading', 'LIKE', '%' . $request->q . '%']])->orderBy('port_of_loading', 'asc')->get(['port_of_loading as name']);
 }

 public function getPortOfDischarge(Request $request)
 {
  return $this->expinvoice->where([['port_of_discharge', 'LIKE', '%' . $request->q . '%']])->orderBy('port_of_discharge', 'asc')->get(['port_of_discharge as name']);
 }

 public function OpenExpCi()
 {
  $id = request('id', 0);
  $invoicedtl = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
   ]);
  echo json_encode($invoicedtl);
 }

 public function getOrderWiseExpCi()
 {
  $payterm = array_prepend(config('bprs.payterm'), '', '');
  $incoterm = array_prepend(config('bprs.incoterm'), '', '');
  $deliveryMode = array_prepend(config('bprs.deliveryMode'), '', '');
  $invoicestatus = array_prepend(config('bprs.invoicestatus'), '-Select-', '');
  $id = request('id', 0);

  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
   })
   // ->leftJoin('bank_accounts', function($join){
   //     $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id')
   //     ->where([['bank_accounts.account_type_id','=',17]]);
   // })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyers.id', '=', 'buyer_branches.buyer_id');
   })
   // ->leftJoin('buyers as notifying_party',function($join){
   //     $join->on('notifying_party.id','=','exp_lc_scs.notifying_party_id');
   // })
   ->leftJoin('buyers as notifying_party', function ($join) {
    $join->on('notifying_party.id', '=', 'exp_invoices.notifying_party_id');
   })
   ->leftJoin('buyers as second_notifying_party', function ($join) {
    $join->on('second_notifying_party.id', '=', 'exp_invoices.second_notifying_party_id');
   })
   ->leftJoin('buyer_branches as notify_branch', function ($join) {
    $join->on('notify_branch.buyer_id', '=', 'notifying_party.id');
   })
   ->leftJoin('buyer_branches as second_notify_branch', function ($join) {
    $join->on('second_notify_branch.buyer_id', '=', 'second_notifying_party.id');
   })
   // ->leftJoin('buyers as consignee',function($join){
   //     $join->on('consignee.id','=','exp_lc_scs.consignee_id');
   // })
   ->leftJoin('buyers as consignee', function ($join) {
    $join->on('consignee.id', '=', 'exp_invoices.consignee_id');
   })
   ->leftJoin('buyer_branches as consignee_branch', function ($join) {
    $join->on('consignee_branch.buyer_id', '=', 'consignee.id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   ->leftJoin(\DB::raw("(
                SELECT 
                exp_lc_scs.id as exp_lc_sc_id,
                bank_accounts.account_no,
                bank_accounts.company_id
                FROM exp_lc_scs 
                join bank_branches on bank_branches.id =exp_lc_scs.exporter_bank_branch_id 
                join bank_accounts on  bank_accounts.bank_branch_id=bank_branches.id 
                where bank_accounts.account_type_id = 17
                and bank_accounts.company_id=exp_lc_scs.beneficiary_id
                group by 
                exp_lc_scs.id,
                bank_accounts.account_no,
                bank_accounts.company_id) bankaccount"), "bankaccount.exp_lc_sc_id", "=", "exp_lc_scs.id")
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    'exp_lc_scs.sc_or_lc',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.beneficiary_id',
    'exp_lc_scs.buyer_id',
    'exp_lc_scs.tenor',
    'exp_lc_scs.pay_term_id',
    'exp_lc_scs.buyers_bank',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'notifying_party.name as notifying_party_name',
    'notify_branch.address as notify_branch_address',
    'second_notifying_party.name as second_notifying_party_name',
    'second_notify_branch.address as second_notify_branch_address',
    'consignee.name as consignee_name',
    'consignee_branch.address as consignee_branch_address',
    'companies.name as beneficiary_name',
    'exp_lc_scs.lien_date',
    'exp_lc_scs.hs_code',
    'exp_lc_scs.re_imbursing_bank',
    'exp_lc_scs.transfer_bank',
    'exp_lc_scs.advise_bank',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'companies.rex_no',
    'companies.rex_date',
    'companies.post_code',
    'companies.erc_no',
    'companies.vat_number',
    'currencies.code as currency_code',
    'currencies.symbol as currency_symbol',
    'banks.name as bank_name',
    'banks.swift_code',
    'bank_branches.branch_name',
    'bank_branches.address as bank_address',
    'bank_branches.contact',
    'bankaccount.account_no',
   ])
   ->map(function ($rows) use ($payterm, $incoterm, $deliveryMode, $invoicestatus) {
    $rows->pay_term_id = $payterm[$rows->pay_term_id];
    $rows->incoterm_id = $incoterm[$rows->incoterm_id];
    $rows->ship_mode_id = $deliveryMode[$rows->ship_mode_id];

    $rows->bl_cargo_date = ($rows->bl_cargo_date !== null) ? date('d.m.Y', strtotime($rows->bl_cargo_date)) : null;
    $rows->exp_form_date = ($rows->exp_form_date !== null) ? date('d.m.Y', strtotime($rows->exp_form_date)) : null;
    $rows->etd_port = ($rows->etd_port !== null) ? date('d.m.Y', strtotime($rows->etd_port)) : null;
    $rows->eta_port = ($rows->eta_port !== null) ? date('d.m.Y', strtotime($rows->eta_port)) : null;

    $rows->discount_detail = $rows->discount_remarks . " " . $rows->discount_per . "%";
    $rows->bonus_detail = $rows->bonus_remarks . " " . $rows->annual_bonus_per . "%";
    $rows->claim_detail = $rows->claim_remarks . " " . $rows->claim_per . "%";

    if ($rows->sc_or_lc == 1) {
     $rows->sc_or_lc_name = 'Sales Contract';
    } else if ($rows->sc_or_lc == 2) {
     $rows->sc_or_lc_name = 'Export LC';
    }

    if ($rows->invoice_status_id == 1) {
     $rows->invoice_status = $invoicestatus[$rows->invoice_status_id];
    }

    return $rows;
   })
   ->first();


  $expinvoiceorder = $this->expinvoice
   ->selectRaw('
            sales_orders.id,
            styles.style_ref,
            sales_orders.sale_order_no,
            exp_pi_orders.sales_order_id,
            exp_invoices.id as exp_invoice_id,
            exp_pi_orders.id as exp_pi_order_id,
            exp_invoice_orders.id as exp_invoice_order_id,
            uoms.code as uom_code,
            exp_invoice_orders.commodity,
            exp_invoice_orders.qty,
            exp_invoice_orders.rate,
            exp_invoice_orders.amount as invoice_amount,
            stylegmt.ratio_qty
        ')
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_invoice_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_pi_order_id', '=', 'exp_pi_orders.id');
   })
   ->join('exp_pis', function ($join) {
    $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
    //$join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('uoms', function ($join) {
    $join->on('uoms.id', '=', 'styles.uom_id');
   })
   ->leftJoin(\DB::raw("(
            select 
            styles.id as style_id,
            sum(style_gmts.gmt_qty) as ratio_qty
            from styles
            join style_gmts on style_gmts.style_id = styles.id 
            where style_gmts.deleted_at is null  
            group by styles.id) stylegmt"), "stylegmt.style_id", "=", "styles.id")
   ->where([['exp_invoices.id', '=', $id]])
   ->orderBy('exp_invoice_orders.id', 'asc')
   ->groupBy([
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'styles.style_ref',
    'exp_pi_orders.sales_order_id',
    'exp_invoices.id',
    'exp_pi_orders.id',
    'exp_invoice_orders.id',
    'uoms.code',
    'exp_invoice_orders.commodity',
    'exp_invoice_orders.qty',
    'exp_invoice_orders.rate',
    'exp_invoice_orders.amount',
    'stylegmt.ratio_qty',
   ])
   ->get()
   ->map(function ($expinvoiceorder) {
    $expinvoiceorder->ship_date = date('d-M-y', strtotime($expinvoiceorder->ship_date));
    if ($expinvoiceorder->ratio_qty) {
     $expinvoiceorder->invoice_qty = $expinvoiceorder->qty / $expinvoiceorder->ratio_qty;
    }
    if ($expinvoiceorder->invoice_qty) {
     $expinvoiceorder->invoice_rate = $expinvoiceorder->invoice_amount / $expinvoiceorder->invoice_qty;
    }

    return $expinvoiceorder;
   });



  $inword = Numbertowords::ntow(number_format($rows->net_inv_value, 2, '.', ''), $rows->currency_code, 'cents only');
  $rows->inword = $inword;
  $rows->uom_code = $expinvoiceorder->max('uom_code');


  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->AddPage();
  $pdf->SetY(10);
  $image_file = 'images/logo/' . $rows->logo;
  $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  $pdf->SetY(12);
  $pdf->SetFont('helvetica', 'N', 8);
  //$pdf->Text(70, 12, $rows->company_address);
  $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M');
  $pdf->SetFont('helvetica', 'B', 14);
  $pdf->SetDrawColor(191);
  $pdf->SetFillColor(127);
  $pdf->SetTextColor(127);
  $pdf->Text(10, 10, $rows->invoice_status);
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetDrawColor(0, 0, 0, 50);
  $pdf->SetFillColor(0, 0, 0, 100);
  $pdf->SetTextColor(0, 0, 0, 100);
  $pdf->SetY(16);
  //$pdf->AddPage();
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
  $pdf->SetY(5);
  $pdf->SetX(150);
  $challan = str_pad($id, 10, 0, STR_PAD_LEFT);
  $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
  //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
  $pdf->SetFont('helvetica', 'N', 8);
  //$pdf->SetTitle('General Item Purchase Order');
  $view = \View::make('Defult.Commercial.Export.OrderWiseCIPdf', ['rows' => $rows, 'expinvoiceorder' => $expinvoiceorder]);
  $html_content = $view->render();
  $pdf->SetY(35);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/OrderWiseCIPdf.pdf';
  $pdf->output($filename);
  exit();
 }

 public function getColorSizeExpCi()
 {
  $payterm = array_prepend(config('bprs.payterm'), '', '');
  $incoterm = array_prepend(config('bprs.incoterm'), '', '');
  $deliveryMode = array_prepend(config('bprs.deliveryMode'), '', '');
  $invoicestatus = array_prepend(config('bprs.invoicestatus'), '-Select-', '');
  $id = request('id', 0);

  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
   })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyers.id', '=', 'buyer_branches.buyer_id');
   })
   ->leftJoin('buyers as notifying_party', function ($join) {
    $join->on('notifying_party.id', '=', 'exp_invoices.notifying_party_id');
   })
   ->leftJoin('buyers as second_notifying_party', function ($join) {
    $join->on('second_notifying_party.id', '=', 'exp_invoices.second_notifying_party_id');
   })
   ->leftJoin('buyer_branches as notify_branch', function ($join) {
    $join->on('notify_branch.buyer_id', '=', 'notifying_party.id');
   })
   ->leftJoin('buyer_branches as second_notify_branch', function ($join) {
    $join->on('second_notify_branch.buyer_id', '=', 'second_notifying_party.id');
   })
   ->leftJoin('buyers as consignee', function ($join) {
    $join->on('consignee.id', '=', 'exp_invoices.consignee_id');
   })
   ->leftJoin('buyer_branches as consignee_branch', function ($join) {
    $join->on('consignee_branch.buyer_id', '=', 'consignee.id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'currencies.country_id');
   })
   ->leftJoin(\DB::raw("(
                SELECT 
                exp_lc_scs.id as exp_lc_sc_id,
                bank_accounts.account_no,
                bank_accounts.company_id
                FROM exp_lc_scs 
                join bank_branches on bank_branches.id =exp_lc_scs.exporter_bank_branch_id 
                join bank_accounts on  bank_accounts.bank_branch_id=bank_branches.id 
                where bank_accounts.account_type_id = 17
                and bank_accounts.company_id=exp_lc_scs.beneficiary_id
                group by 
                exp_lc_scs.id,
                bank_accounts.account_no,
                bank_accounts.company_id) bankaccount"), "bankaccount.exp_lc_sc_id", "=", "exp_lc_scs.id")
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    //'exp_lc_scs.id',
    'exp_lc_scs.sc_or_lc',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.beneficiary_id',
    'exp_lc_scs.buyer_id',
    'exp_lc_scs.tenor',
    'exp_lc_scs.pay_term_id',
    'exp_lc_scs.buyers_bank',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'notifying_party.name as notifying_party_name',
    'notify_branch.address as notify_branch_address',
    'second_notifying_party.name as second_notifying_party_name',
    'second_notify_branch.address as second_notify_branch_address',
    'consignee.name as consignee_name',
    'consignee_branch.address as consignee_branch_address',
    'companies.name as beneficiary_name',
    'exp_lc_scs.lien_date',
    'exp_lc_scs.hs_code',
    'exp_lc_scs.re_imbursing_bank',
    'exp_lc_scs.transfer_bank',
    'exp_lc_scs.advise_bank',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'companies.rex_no',
    'companies.rex_date',
    'companies.post_code',
    'companies.erc_no',
    'companies.vat_number',
    'currencies.code as currency_code',
    'currencies.symbol as currency_symbol',
    'banks.name as bank_name',
    'banks.swift_code',
    'bank_branches.branch_name',
    'bank_branches.address as bank_address',
    'bank_branches.contact',
    'bankaccount.account_no',
    'countries.region_id',
   ])
   ->map(function ($rows) use ($payterm, $incoterm, $deliveryMode, $invoicestatus) {
    $rows->pay_term_id = $payterm[$rows->pay_term_id];
    $rows->incoterm_id = $incoterm[$rows->incoterm_id];
    $rows->ship_mode_id = $deliveryMode[$rows->ship_mode_id];

    $rows->bl_cargo_date = ($rows->bl_cargo_date !== null) ? date('d.m.Y', strtotime($rows->bl_cargo_date)) : null;
    $rows->exp_form_date = ($rows->exp_form_date !== null) ? date('d.m.Y', strtotime($rows->exp_form_date)) : null;
    $rows->etd_port = ($rows->etd_port !== null) ? date('d.m.Y', strtotime($rows->etd_port)) : null;
    $rows->eta_port = ($rows->eta_port !== null) ? date('d.m.Y', strtotime($rows->eta_port)) : null;

    $rows->discount_detail = $rows->discount_remarks . " " . $rows->discount_per . "%";
    $rows->bonus_detail = $rows->bonus_remarks . " " . $rows->annual_bonus_per . "%";
    $rows->claim_detail = $rows->claim_remarks . " " . $rows->claim_per . "%";

    if ($rows->sc_or_lc == 1) {
     $rows->sc_or_lc_name = 'Sales Contract';
    } else if ($rows->sc_or_lc == 2) {
     $rows->sc_or_lc_name = 'Export LC';
    }

    if ($rows->invoice_status_id == 1) {
     $rows->invoice_status = $invoicestatus[$rows->invoice_status_id];
    }

    return $rows;
   })
   ->first();



  $order = $this->expinvoice
   ->selectRaw('
            sales_orders.id,
            styles.style_ref,
            sales_orders.sale_order_no,
            exp_pi_orders.sales_order_id,
            sizes.name as size_name,
            sizes.code as size_code,
            colors.name as color_name,
            colors.code as color_code,
            style_sizes.sort_id as size_sort_id,
            style_colors.sort_id as color_sort_id,
            sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
            item_accounts.item_description,
            countries.name as country_name,
            exp_invoice_order_dtls.id as exp_invoice_order_dtl_id,
            exp_invoice_order_dtls.qty as invoice_qty,
            exp_invoice_order_dtls.rate as invoice_rate,
            exp_invoice_order_dtls.amount as invoice_amount,
            budgets.id as budget_id,
            style_gmts.custom_catg,
            uoms.code as uom_code
        ')
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoices.id', '=', 'exp_invoice_orders.exp_invoice_id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_invoice_order_dtls', function ($join) {
    $join->on('exp_invoice_order_dtls.exp_invoice_order_id', '=', 'exp_invoice_orders.id');
    $join->whereNull('exp_invoice_order_dtls.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_pi_orders.id', '=', 'exp_invoice_orders.exp_pi_order_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
    //$join->on('sales_order_countries.country_id', '=', 'exp_invoices.country_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('uoms', function ($join) {
    $join->on('uoms.id', '=', 'styles.uom_id');
   })
   ->leftJoin('budgets', function ($join) {
    $join->on('styles.id', '=', 'budgets.style_id');
   })
   ->join('sales_order_gmt_color_sizes', function ($join) {
    $join->on('exp_invoice_order_dtls.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
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
   ->leftJoin('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'style_colors.color_id');
   })
   ->leftJoin('style_sizes', function ($join) {
    $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
   })
   ->leftJoin('sizes', function ($join) {
    $join->on('sizes.id', '=', 'style_sizes.size_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'sales_order_countries.country_id');
   })

   ->where([['exp_invoices.id', '=', $id]])
   ->orderBy('exp_invoice_order_dtls.id', 'asc')
   ->groupBy([
    'sales_orders.id',
    'styles.style_ref',
    'sales_orders.sale_order_no',
    'exp_pi_orders.sales_order_id',
    'sizes.name',
    'sizes.code',
    'colors.name',
    'colors.code',
    'style_sizes.sort_id',
    'style_colors.sort_id',
    'sales_order_gmt_color_sizes.id',
    'item_accounts.item_description',
    'countries.name',

    'exp_invoice_order_dtls.id',
    'exp_invoice_order_dtls.qty',
    'exp_invoice_order_dtls.rate',
    'exp_invoice_order_dtls.amount',
    'budgets.id',
    'style_gmts.custom_catg',
    'uoms.code',
   ])
   ->get()
   ->map(function ($order) {
    $order->fabrication = $order->item_description . " ;" . $order->custom_catg;
    $order->ship_date = date('d-M-y', strtotime($order->ship_date));
    return $order;
   });

  $inword = Numbertowords::ntow(number_format($rows->net_inv_value, 2, '.', ''), $rows->currency_name, 'cents only');
  $rows->inword = $inword;
  $rows->uom_code = $order->max('uom_code');

  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $pdf->SetY(15);
  $image_file = 'images/logo/' . $rows->logo;
  $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  $pdf->SetY(17);
  $pdf->SetFont('helvetica', 'N', 8);
  //$pdf->Text(70, 12, $rows->company_address);
  $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M');
  $pdf->SetFont('helvetica', 'B', 14);
  $pdf->SetDrawColor(191);
  $pdf->SetFillColor(127);
  $pdf->SetTextColor(127);
  $pdf->Text(10, 10, $rows->invoice_status);
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetDrawColor(0, 0, 0, 50);
  $pdf->SetFillColor(0, 0, 0, 100);
  $pdf->SetTextColor(0, 0, 0, 100);
  $pdf->SetY(16);
  //$pdf->AddPage();
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
  $pdf->SetY(5);
  $pdf->SetX(150);
  $challan = str_pad($id, 10, 0, STR_PAD_LEFT);
  $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
  $pdf->SetFont('helvetica', 'N', 7);
  $view = \View::make('Defult.Commercial.Export.ColorSizeWiseCIPdf', ['rows' => $rows, 'order' => $order]);
  $html_content = $view->render();
  $pdf->SetY(35);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/ColorSizeWiseCIPdf.pdf';
  $pdf->output($filename);
  exit();
 }

 public function getColorWiseExpCi()
 {
  $payterm = array_prepend(config('bprs.payterm'), '', '');
  $incoterm = array_prepend(config('bprs.incoterm'), '', '');
  $deliveryMode = array_prepend(config('bprs.deliveryMode'), '', '');
  $invoicestatus = array_prepend(config('bprs.invoicestatus'), '-Select-', '');
  $id = request('id', 0);
  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
   })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyers.id', '=', 'buyer_branches.buyer_id');
   })
   ->leftJoin('buyers as notifying_party', function ($join) {
    $join->on('notifying_party.id', '=', 'exp_invoices.notifying_party_id');
   })
   ->leftJoin('buyers as second_notifying_party', function ($join) {
    $join->on('second_notifying_party.id', '=', 'exp_invoices.second_notifying_party_id');
   })
   ->leftJoin('buyer_branches as notify_branch', function ($join) {
    $join->on('notify_branch.buyer_id', '=', 'notifying_party.id');
   })
   ->leftJoin('buyer_branches as second_notify_branch', function ($join) {
    $join->on('second_notify_branch.buyer_id', '=', 'second_notifying_party.id');
   })
   ->leftJoin('buyers as consignee', function ($join) {
    $join->on('consignee.id', '=', 'exp_invoices.consignee_id');
   })
   ->leftJoin('buyer_branches as consignee_branch', function ($join) {
    $join->on('consignee_branch.buyer_id', '=', 'consignee.id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'currencies.country_id');
   })
   ->leftJoin(\DB::raw("(
                SELECT 
                exp_lc_scs.id as exp_lc_sc_id,
                bank_accounts.account_no,
                bank_accounts.company_id
                FROM exp_lc_scs 
                join bank_branches on bank_branches.id =exp_lc_scs.exporter_bank_branch_id 
                join bank_accounts on  bank_accounts.bank_branch_id=bank_branches.id 
                where bank_accounts.account_type_id = 17
                and bank_accounts.company_id=exp_lc_scs.beneficiary_id
                group by 
                exp_lc_scs.id,
                bank_accounts.account_no,
                bank_accounts.company_id) bankaccount"), "bankaccount.exp_lc_sc_id", "=", "exp_lc_scs.id")
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    //'exp_lc_scs.id',
    'exp_lc_scs.sc_or_lc',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.beneficiary_id',
    'exp_lc_scs.buyer_id',
    'exp_lc_scs.tenor',
    'exp_lc_scs.pay_term_id',
    'exp_lc_scs.buyers_bank',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'notifying_party.name as notifying_party_name',
    'notify_branch.address as notify_branch_address',
    'second_notifying_party.name as second_notifying_party_name',
    'second_notify_branch.address as second_notify_branch_address',
    'consignee.name as consignee_name',
    'consignee_branch.address as consignee_branch_address',
    'companies.name as beneficiary_name',
    'exp_lc_scs.lien_date',
    'exp_lc_scs.hs_code',
    'exp_lc_scs.re_imbursing_bank',
    'exp_lc_scs.transfer_bank',
    'exp_lc_scs.advise_bank',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'companies.rex_no',
    'companies.rex_date',
    'companies.post_code',
    'companies.erc_no',
    'companies.vat_number',
    'currencies.code as currency_name',
    'banks.name as bank_name',
    'banks.swift_code',
    'bank_branches.branch_name',
    'bank_branches.address as bank_address',
    'bank_branches.contact',
    'bankaccount.account_no',
    'countries.region_id',
   ])
   ->map(function ($rows) use ($payterm, $incoterm, $deliveryMode, $invoicestatus) {
    $rows->pay_term_id = $payterm[$rows->pay_term_id];
    $rows->incoterm_id = $incoterm[$rows->incoterm_id];
    $rows->ship_mode_id = $deliveryMode[$rows->ship_mode_id];

    $rows->bl_cargo_date = ($rows->bl_cargo_date !== null) ? date('d.m.Y', strtotime($rows->bl_cargo_date)) : null;
    $rows->exp_form_date = ($rows->exp_form_date !== null) ? date('d.m.Y', strtotime($rows->exp_form_date)) : null;
    $rows->etd_port = ($rows->etd_port !== null) ? date('d.m.Y', strtotime($rows->etd_port)) : null;
    $rows->eta_port = ($rows->eta_port !== null) ? date('d.m.Y', strtotime($rows->eta_port)) : null;

    if ($rows->region_id == 1) {
     $rows->region = "European Union";
    } elseif ($rows->region_id == 5) {
     $rows->region = "United States of America";
    } elseif ($rows->region_id == 10) {
     $rows->region = "Australian";
    } elseif ($rows->region_id == 15) {
     $rows->region = "Asian";
    } elseif ($rows->region_id == 20) {
     $rows->region = "African";
    } elseif ($rows->region_id == 25) {
     $rows->region = "North American";
    } elseif ($rows->region_id == 30) {
     $rows->region = "South American";
    }

    $rows->discount_detail = $rows->discount_remarks . " " . $rows->discount_per . "%";
    $rows->bonus_detail = $rows->bonus_remarks . " " . $rows->annual_bonus_per . "%";
    $rows->claim_detail = $rows->claim_remarks . " " . $rows->claim_per . "%";

    if ($rows->sc_or_lc == 1) {
     $rows->sc_or_lc_name = 'Sales Contract';
    } else if ($rows->sc_or_lc == 2) {
     $rows->sc_or_lc_name = 'Export LC';
    }

    if ($rows->invoice_status_id == 1) {
     $rows->invoice_status = $invoicestatus[$rows->invoice_status_id];
    }

    return $rows;
   })
   ->first();

  $order = $this->expinvoice
   ->selectRaw('
            sales_orders.id,
            styles.style_ref,
            sales_orders.sale_order_no,
            colors.name as color_name,
            colors.code as color_code,
            style_colors.sort_id as color_sort_id,
            item_accounts.item_description,
            countries.name as country_name,
            sum(exp_invoice_order_dtls.qty) as invoice_qty,
            avg(exp_invoice_order_dtls.rate) as invoice_rate,
            sum(exp_invoice_order_dtls.amount) as invoice_amount,
            budgets.id as budget_id,
            style_gmts.custom_catg
        ')
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoices.id', '=', 'exp_invoice_orders.exp_invoice_id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_invoice_order_dtls', function ($join) {
    $join->on('exp_invoice_order_dtls.exp_invoice_order_id', '=', 'exp_invoice_orders.id');
    $join->whereNull('exp_invoice_order_dtls.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_pi_orders.id', '=', 'exp_invoice_orders.exp_pi_order_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
    //$join->on('sales_order_countries.country_id', '=', 'exp_invoices.country_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('budgets', function ($join) {
    $join->on('styles.id', '=', 'budgets.style_id');
   })
   ->join('sales_order_gmt_color_sizes', function ($join) {
    $join->on('exp_invoice_order_dtls.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
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

   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'sales_order_countries.country_id');
   })
   ->where([['exp_invoices.id', '=', $id]])
   ->orderBy('sales_orders.sale_order_no')
   ->orderBy('style_colors.sort_id')
   ->groupBy([
    'sales_orders.id',
    'styles.style_ref',
    'sales_orders.sale_order_no',
    'colors.name',
    'colors.code',
    'style_colors.sort_id',
    'item_accounts.item_description',
    'countries.name',
    // 'exp_invoice_order_dtls.id',
    // 'exp_invoice_order_dtls.qty',
    // 'exp_invoice_order_dtls.rate',
    // 'exp_invoice_order_dtls.amount',
    'budgets.id',
    'style_gmts.custom_catg'
   ])
   ->get()
   ->map(function ($order) /* use($desDropdown) */ {
    //$order->budget_fabrication=isset($desDropdown[$order->budget_id])?$desDropdown[$order->budget_id]:'';
    $order->fabrication = $order->item_description . " ;" . $order->custom_catg;
    $order->ship_date = date('d-M-y', strtotime($order->ship_date));
    return $order;
   });

  $amount = $order->sum('invoice_amount');
  $inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $rows->currency_name, 'cents');
  $rows->inword = $inword;
  $data = $order/* ->groupBy('sales_order_id') */;

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
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->AddPage();
  $pdf->SetY(15);
  $image_file = 'images/logo/' . $rows->logo;
  $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  $pdf->SetY(17);
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M');
  //$pdf->Text(70, 12, $rows->company_address);
  $pdf->SetFont('helvetica', 'B', 14);
  $pdf->SetDrawColor(191);
  $pdf->SetFillColor(127);
  $pdf->SetTextColor(127);
  $pdf->Text(10, 10, $rows->invoice_status);
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetDrawColor(0, 0, 0, 50);
  $pdf->SetFillColor(0, 0, 0, 100);
  $pdf->SetTextColor(0, 0, 0, 100);
  $pdf->SetY(16);
  //$pdf->AddPage();
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
  $pdf->SetY(5);
  $pdf->SetX(150);
  $challan = str_pad($id, 10, 0, STR_PAD_LEFT);
  $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
  $pdf->SetFont('helvetica', 'N', 7);
  $view = \View::make('Defult.Commercial.Export.ColorWiseCIPdf', ['rows' => $rows, 'data' => $data]);
  $html_content = $view->render();
  $pdf->SetY(35);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/ColorWiseCIPdf.pdf';
  $pdf->output($filename);
 }

 public function getSizeWiseExpCi()
 {
  $payterm = array_prepend(config('bprs.payterm'), '', '');
  $incoterm = array_prepend(config('bprs.incoterm'), '', '');
  $deliveryMode = array_prepend(config('bprs.deliveryMode'), '', '');
  $invoicestatus = array_prepend(config('bprs.invoicestatus'), '-Select-', '');
  $id = request('id', 0);
  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
   })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyers.id', '=', 'buyer_branches.buyer_id');
   })
   ->leftJoin('buyers as notifying_party', function ($join) {
    $join->on('notifying_party.id', '=', 'exp_invoices.notifying_party_id');
   })
   ->leftJoin('buyers as second_notifying_party', function ($join) {
    $join->on('second_notifying_party.id', '=', 'exp_invoices.second_notifying_party_id');
   })
   ->leftJoin('buyer_branches as notify_branch', function ($join) {
    $join->on('notify_branch.buyer_id', '=', 'notifying_party.id');
   })
   ->leftJoin('buyer_branches as second_notify_branch', function ($join) {
    $join->on('second_notify_branch.buyer_id', '=', 'second_notifying_party.id');
   })
   ->leftJoin('buyers as consignee', function ($join) {
    $join->on('consignee.id', '=', 'exp_invoices.consignee_id');
   })
   ->leftJoin('buyer_branches as consignee_branch', function ($join) {
    $join->on('consignee_branch.buyer_id', '=', 'consignee.id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'currencies.country_id');
   })
   ->leftJoin(\DB::raw("(
                SELECT 
                exp_lc_scs.id as exp_lc_sc_id,
                bank_accounts.account_no,
                bank_accounts.company_id
                FROM exp_lc_scs 
                join bank_branches on bank_branches.id =exp_lc_scs.exporter_bank_branch_id 
                join bank_accounts on  bank_accounts.bank_branch_id=bank_branches.id 
                where bank_accounts.account_type_id = 17
                and bank_accounts.company_id=exp_lc_scs.beneficiary_id
                group by 
                exp_lc_scs.id,
                bank_accounts.account_no,
                bank_accounts.company_id) bankaccount"), "bankaccount.exp_lc_sc_id", "=", "exp_lc_scs.id")
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    //'exp_lc_scs.id',
    'exp_lc_scs.sc_or_lc',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.beneficiary_id',
    'exp_lc_scs.buyer_id',
    'exp_lc_scs.tenor',
    'exp_lc_scs.pay_term_id',
    'exp_lc_scs.buyers_bank',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'notifying_party.name as notifying_party_name',
    'notify_branch.address as notify_branch_address',
    'second_notifying_party.name as second_notifying_party_name',
    'second_notify_branch.address as second_notify_branch_address',
    'consignee.name as consignee_name',
    'consignee_branch.address as consignee_branch_address',
    'companies.name as beneficiary_name',
    'exp_lc_scs.lien_date',
    'exp_lc_scs.hs_code',
    'exp_lc_scs.re_imbursing_bank',
    'exp_lc_scs.transfer_bank',
    'exp_lc_scs.advise_bank',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'companies.rex_no',
    'companies.rex_date',
    'companies.post_code',
    'companies.erc_no',
    'companies.vat_number',
    'currencies.code as currency_name',
    'banks.name as bank_name',
    'banks.swift_code',
    'bank_branches.branch_name',
    'bank_branches.address as bank_address',
    'bank_branches.contact',
    'bankaccount.account_no',
    'countries.region_id',
   ])
   ->map(function ($rows) use ($payterm, $incoterm, $deliveryMode, $invoicestatus) {
    $rows->pay_term_id = $payterm[$rows->pay_term_id];
    $rows->incoterm_id = $incoterm[$rows->incoterm_id];
    $rows->ship_mode_id = $deliveryMode[$rows->ship_mode_id];

    $rows->bl_cargo_date = ($rows->bl_cargo_date !== null) ? date('d.m.Y', strtotime($rows->bl_cargo_date)) : null;
    $rows->exp_form_date = ($rows->exp_form_date !== null) ? date('d.m.Y', strtotime($rows->exp_form_date)) : null;
    $rows->etd_port = ($rows->etd_port !== null) ? date('d.m.Y', strtotime($rows->etd_port)) : null;
    $rows->eta_port = ($rows->eta_port !== null) ? date('d.m.Y', strtotime($rows->eta_port)) : null;

    if ($rows->region_id == 1) {
     $rows->region = "European Union";
    } elseif ($rows->region_id == 5) {
     $rows->region = "United States of America";
    } elseif ($rows->region_id == 10) {
     $rows->region = "Australian";
    } elseif ($rows->region_id == 15) {
     $rows->region = "Asian";
    } elseif ($rows->region_id == 20) {
     $rows->region = "African";
    } elseif ($rows->region_id == 25) {
     $rows->region = "North American";
    } elseif ($rows->region_id == 30) {
     $rows->region = "South American";
    }

    $rows->discount_detail = $rows->discount_remarks . " " . $rows->discount_per . "%";
    $rows->bonus_detail = $rows->bonus_remarks . " " . $rows->annual_bonus_per . "%";
    $rows->claim_detail = $rows->claim_remarks . " " . $rows->claim_per . "%";

    if ($rows->sc_or_lc == 1) {
     $rows->sc_or_lc_name = 'Sales Contract';
    } else if ($rows->sc_or_lc == 2) {
     $rows->sc_or_lc_name = 'Export LC';
    }

    if ($rows->invoice_status_id == 1) {
     $rows->invoice_status = $invoicestatus[$rows->invoice_status_id];
    }

    return $rows;
   })
   ->first();


  $order = $this->expinvoice
   ->selectRaw('
            styles.style_ref,
            sales_orders.sale_order_no,
            sizes.name as size_name,
            sizes.code as size_code,
            style_sizes.sort_id as size_sort_id,
            item_accounts.item_description,
            countries.name as country_name,
            sum(exp_invoice_order_dtls.qty) as invoice_qty,
            avg(exp_invoice_order_dtls.rate) as invoice_rate,
            sum(exp_invoice_order_dtls.amount) as invoice_amount,
            budgets.id as budget_id,
            style_gmts.custom_catg
        ')
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoices.id', '=', 'exp_invoice_orders.exp_invoice_id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_invoice_order_dtls', function ($join) {
    $join->on('exp_invoice_order_dtls.exp_invoice_order_id', '=', 'exp_invoice_orders.id');
    $join->whereNull('exp_invoice_order_dtls.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_pi_orders.id', '=', 'exp_invoice_orders.exp_pi_order_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
    //$join->on('sales_order_countries.country_id', '=', 'exp_invoices.country_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('budgets', function ($join) {
    $join->on('styles.id', '=', 'budgets.style_id');
   })
   ->join('sales_order_gmt_color_sizes', function ($join) {
    $join->on('exp_invoice_order_dtls.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
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
   ->leftJoin('style_sizes', function ($join) {
    $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
   })
   ->leftJoin('sizes', function ($join) {
    $join->on('sizes.id', '=', 'style_sizes.size_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'sales_order_countries.country_id');
   })
   ->where([['exp_invoices.id', '=', $id]])
   ->orderBy('sales_orders.sale_order_no')
   ->orderBy('style_sizes.sort_id')
   ->groupBy([
    'styles.style_ref',
    'sales_orders.sale_order_no',
    'sizes.name',
    'sizes.code',
    'style_sizes.sort_id',
    'item_accounts.item_description',
    'countries.name',
    'budgets.id',
    'style_gmts.custom_catg'
   ])
   ->get()
   ->map(function ($order)/*  use($desDropdown) */ {
    //$order->budget_fabrication=isset($desDropdown[$order->budget_id])?$desDropdown[$order->budget_id]:'';
    $order->fabrication = $order->item_description . " ;" . $order->custom_catg;
    $order->ship_date = date('d-M-y', strtotime($order->ship_date));
    return $order;
   });

  $amount = $order->sum('invoice_amount');
  $inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $rows->currency_name, 'cents');
  $rows->inword = $inword;
  $data = $order/* ->groupBy('sales_order_id') */;

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
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->AddPage();
  $pdf->SetY(15);
  $image_file = 'images/logo/' . $rows->logo;
  $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  $pdf->SetY(17);
  $pdf->SetFont('helvetica', 'N', 8);
  //$pdf->Text(70, 12, $rows->company_address);
  $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M');
  $pdf->SetFont('helvetica', 'B', 14);
  $pdf->SetDrawColor(191);
  $pdf->SetFillColor(127);
  $pdf->SetTextColor(127);
  $pdf->Text(10, 10, $rows->invoice_status);
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetDrawColor(0, 0, 0, 50);
  $pdf->SetFillColor(0, 0, 0, 100);
  $pdf->SetTextColor(0, 0, 0, 100);
  $pdf->SetY(16);
  //$pdf->AddPage();
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
  $pdf->SetY(5);
  $pdf->SetX(150);
  $challan = str_pad($id, 10, 0, STR_PAD_LEFT);
  $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
  $pdf->SetFont('helvetica', 'N', 7);
  $view = \View::make('Defult.Commercial.Export.SizeWiseCIPdf', ['rows' => $rows, 'data' => $data]);
  $html_content = $view->render();
  $pdf->SetY(35);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/SizeWiseCIPdf.pdf';
  $pdf->output($filename);
  exit();
 }

 public function bnfDeclaration()
 {
  $id = request('id', 0);
  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyers.id', '=', 'buyer_branches.buyer_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   /* ->leftJoin('countries',function($join){
            $join->on('countries.id','=','currencies.country_id');
        }) */
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    'exp_lc_scs.id',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.buyer_id',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'currencies.code as currency_code',
    'currencies.symbol as currency_symbol',
    //'countries.region_id',
   ])
   ->map(function ($rows) {
    return $rows;
   })
   ->first();

  $order = $this->expinvoice
   ->selectRaw('
            sales_orders.id,
            styles.style_ref,
            styles.id as style_id,
            sales_orders.sale_order_no,
            exp_pi_orders.sales_order_id,
            exp_invoices.id as exp_invoice_id,
            exp_pi_orders.id as exp_pi_order_id,
            exp_invoice_orders.id as exp_invoice_order_id,
            style_fabrications.id as style_fabrication_id,
            style_fabrications.gmtspart_id,
            item_accounts.item_description,
            style_gmts.article,
            item_accounts.id as item_account_id,
            
            exp_invoice_orders.qty as invoice_qty,
            exp_invoice_orders.rate as invoice_rate,
            exp_invoice_orders.amount as invoice_amount
        ')
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_invoice_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_pi_order_id', '=', 'exp_pi_orders.id');
   })
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('exp_rep_lc_scs', function ($join) {
    $join->on('exp_rep_lc_scs.exp_lc_sc_id', '=', 'exp_lc_scs.id');
   })
   ->join('exp_lc_sc_pis', function ($join) {
    $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
    $join->orOn('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
   })
   ->join('exp_pis', function ($join) {
    $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
    //$join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
    //$join->on('sales_order_countries.country_id', '=', 'exp_invoices.country_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('budgets', function ($join) {
    $join->on('styles.id', '=', 'budgets.style_id');
   })
   ->leftJoin('budget_fabrics', function ($join) {
    $join->on('budget_fabrics.budget_id', '=', 'budgets.id');
   })
   ->join('style_fabrications', function ($join) {
    $join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
    $join->on('style_fabrications.style_id', '=', 'budgets.style_id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
   })
   ->join('autoyarns', function ($join) {
    $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'style_fabrications.uom_id');
   })
   ->where([['style_fabrications.is_narrow', '=', 0]])
   ->where([['exp_invoices.id', '=', $id]])
   ->groupBy([
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'styles.id',
    'styles.style_ref',
    'exp_pi_orders.sales_order_id',
    'exp_invoices.id',
    'exp_pi_orders.id',
    'exp_invoice_orders.id',
    'style_gmts.article',
    'style_fabrications.gmtspart_id',
    'style_fabrications.id',
    'item_accounts.item_description',
    'item_accounts.id',
    'exp_invoice_orders.qty',
    'exp_invoice_orders.rate',
    'exp_invoice_orders.amount',
   ])
   ->get();

  $salesorderarr = array();
  $starr = array();
  $artarr = array();
  $itemdescarr = array();
  foreach ($order as $data) {
   $salesorderarr[$data->sales_order_id] = $data->sale_order_no;
   $starr[$data->style_id] = $data->style_ref;
   $artarr[$data->style_id] = $data->article;
   //$itemdescarr[$data->exp_invoice_id][]=$data->item_description;
   $itemdescarr[$data->item_account_id] = $data->item_description;
  }
  $sale_order_no = implode(',', $salesorderarr);
  $style_ref = implode(',', $starr);
  $article_no = implode(',', $artarr);
  $item_description = implode(',', $itemdescarr);

  //    dd(implode(',',$itemdescarr));
  //   die();
  //////////////////////////////
  /* $new_arr=array();
                foreach($itemdescarr as $key => $val) {
                    $new_arr[$key] = $val;
                } */
  //////////////////////

  //dd(implode(',',$new_arr));
  //die();

  // $uniq_arr = array_unique($new_arr);


  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $pdf->SetY(15);

  $pdf->SetFont('helvetica', 'N', 10);
  //$pdf->SetFont('helvetica', '', 8);
  $view = \View::make('Defult.Commercial.Export.BnfDeclarationPdf', ['rows' => $rows, 'sale_order_no' => $sale_order_no, 'style_ref' => $style_ref, 'article_no' => $article_no, 'item_description' => $item_description]);
  $html_content = $view->render();
  $pdf->SetY(40);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/BnfDeclarationPdf.pdf';
  $pdf->output($filename);
  exit();
 }

 public function confirmLetter()
 {
  $id = request('id', 0);
  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyers.id', '=', 'buyer_branches.buyer_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   /* ->leftJoin('countries',function($join){
            $join->on('countries.id','=','currencies.country_id');
        }) */
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    'exp_lc_scs.id',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.buyer_id',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'currencies.code as currency_code',
    'currencies.symbol as currency_symbol',
    //'countries.region_id',
   ])
   ->map(function ($rows) {
    return $rows;
   })
   ->first();

  $order = $this->expinvoice
   ->selectRaw('
            sales_orders.id,
            styles.style_ref,
            styles.id as style_id,
            sales_orders.sale_order_no,
            exp_pi_orders.sales_order_id,
            exp_invoices.id as exp_invoice_id,
            exp_pi_orders.id as exp_pi_order_id,
            exp_invoice_orders.id as exp_invoice_order_id,
            style_gmts.article,
            style_fabrications.id as style_fabrication_id,
            style_fabrications.gmtspart_id,
            item_accounts.item_description,
            item_accounts.id as item_account_id,
            exp_invoice_orders.qty as invoice_qty,
            exp_invoice_orders.rate as invoice_rate,
            exp_invoice_orders.amount as invoice_amount
        ')
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_invoice_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_pi_order_id', '=', 'exp_pi_orders.id');
   })
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('exp_rep_lc_scs', function ($join) {
    $join->on('exp_rep_lc_scs.exp_lc_sc_id', '=', 'exp_lc_scs.id');
   })
   ->join('exp_lc_sc_pis', function ($join) {
    $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
    $join->orOn('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
   })
   ->join('exp_pis', function ($join) {
    $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
    //$join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
    //$join->on('sales_order_countries.country_id', '=', 'exp_invoices.country_id');
   })
   // ->join('sales_order_gmt_color_sizes', function($join)  {
   //     $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
   // })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('budgets', function ($join) {
    $join->on('styles.id', '=', 'budgets.style_id');
   })
   ->leftJoin('budget_fabrics', function ($join) {
    $join->on('budget_fabrics.budget_id', '=', 'budgets.id');
   })
   ->join('style_fabrications', function ($join) {
    $join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
    $join->on('style_fabrications.style_id', '=', 'budgets.style_id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
   })
   ->join('autoyarns', function ($join) {
    $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'style_fabrications.uom_id');
   })
   ->where([['style_fabrications.is_narrow', '=', 0]])
   ->where([['exp_invoices.id', '=', $id]])
   ->groupBy([
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'styles.id',
    'styles.style_ref',
    'exp_pi_orders.sales_order_id',
    'exp_invoices.id',
    'exp_pi_orders.id',
    'exp_invoice_orders.id',
    'style_gmts.article',
    'style_fabrications.gmtspart_id',
    'style_fabrications.id',
    'item_accounts.item_description',
    'item_accounts.id',
    'exp_invoice_orders.qty',
    'exp_invoice_orders.rate',
    'exp_invoice_orders.amount',
   ])
   ->get();

  $salesorderarr = array();
  $starr = array();
  $artarr = array();
  $itemdescarr = array();
  foreach ($order as $data) {
   $salesorderarr[$data->sales_order_id] = $data->sale_order_no;
   $starr[$data->style_id] = $data->style_ref;
   $artarr[$data->style_id] = $data->article;
   //$itemdescarr[$data->exp_invoice_id][]=$data->item_description;
   $itemdescarr[$data->item_account_id] = $data->item_description;
  }
  $sale_order_no = implode(',', $salesorderarr);
  $style_ref = implode(',', $starr);
  $article_no = implode(',', $artarr);
  $item_description = implode(',', $itemdescarr);

  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $pdf->SetY(15);

  $pdf->SetFont('helvetica', 'N', 10);
  //$pdf->SetFont('helvetica', '', 8);
  $view = \View::make('Defult.Commercial.Export.ConfirmLetterPdf', ['rows' => $rows, 'sale_order_no' => $sale_order_no, 'style_ref' => $style_ref, 'article_no' => $article_no, 'item_description' => $item_description]);
  $html_content = $view->render();
  $pdf->SetY(40);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/ConfirmLetterPdf.pdf';
  $pdf->output($filename);
  exit();
 }

 public function shipperConfirm()
 {
  $id = request('id', 0);
  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyers.id', '=', 'buyer_branches.buyer_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   /* ->leftJoin('countries',function($join){
            $join->on('countries.id','=','currencies.country_id');
        }) */
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    'exp_lc_scs.id',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.buyer_id',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'currencies.code as currency_code',
    'currencies.symbol as currency_symbol',
    //'countries.region_id',
   ])
   ->map(function ($rows) {

    return $rows;
   })
   ->first();

  $order = $this->expinvoice
   ->selectRaw('
            sales_orders.id,
            styles.style_ref,
            styles.id as style_id,
            sales_orders.sale_order_no,
            exp_pi_orders.sales_order_id,
            exp_invoices.id as exp_invoice_id,
            exp_pi_orders.id as exp_pi_order_id,
            exp_invoice_orders.id as exp_invoice_order_id,
            style_gmts.article,
            style_fabrications.id as style_fabrication_id,
            style_fabrications.gmtspart_id,
            item_accounts.item_description,
            item_accounts.id as item_account_id,
            exp_invoice_orders.qty as invoice_qty,
            exp_invoice_orders.rate as invoice_rate,
            exp_invoice_orders.amount as invoice_amount
        ')
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_invoice_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_pi_order_id', '=', 'exp_pi_orders.id');
   })
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('exp_rep_lc_scs', function ($join) {
    $join->on('exp_rep_lc_scs.exp_lc_sc_id', '=', 'exp_lc_scs.id');
   })
   ->join('exp_lc_sc_pis', function ($join) {
    $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
    $join->orOn('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
   })
   ->join('exp_pis', function ($join) {
    $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
    //$join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
    //$join->on('sales_order_countries.country_id', '=', 'exp_invoices.country_id');
   })
   // ->join('sales_order_gmt_color_sizes', function($join)  {
   //     $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
   // })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('budgets', function ($join) {
    $join->on('styles.id', '=', 'budgets.style_id');
   })
   ->leftJoin('budget_fabrics', function ($join) {
    $join->on('budget_fabrics.budget_id', '=', 'budgets.id');
   })
   ->join('style_fabrications', function ($join) {
    $join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
    $join->on('style_fabrications.style_id', '=', 'budgets.style_id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
   })
   ->join('autoyarns', function ($join) {
    $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'style_fabrications.uom_id');
   })
   ->where([['style_fabrications.is_narrow', '=', 0]])
   ->where([['exp_invoices.id', '=', $id]])
   ->groupBy([
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'styles.id',
    'styles.style_ref',
    'exp_pi_orders.sales_order_id',
    'exp_invoices.id',
    'exp_pi_orders.id',
    'exp_invoice_orders.id',
    'style_gmts.article',
    'style_fabrications.gmtspart_id',
    'style_fabrications.id',
    'item_accounts.item_description',
    'item_accounts.id',
    'exp_invoice_orders.qty',
    'exp_invoice_orders.rate',
    'exp_invoice_orders.amount',
   ])
   ->get();

  $salesorderarr = array();
  $starr = array();
  $artarr = array();
  $itemdescarr = array();
  foreach ($order as $data) {
   $salesorderarr[$data->sales_order_id] = $data->sale_order_no;
   $starr[$data->style_id] = $data->style_ref;
   $artarr[$data->style_id] = $data->article;
   //$itemdescarr[$data->exp_invoice_id][]=$data->item_description;
   $itemdescarr[$data->item_account_id] = $data->item_description;
  }
  $sale_order_no = implode(',', $salesorderarr);
  $style_ref = implode(',', $starr);
  $article_no = implode(',', $artarr);
  $item_description = implode(',', $itemdescarr);

  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $pdf->SetY(15);

  $pdf->SetFont('helvetica', 'N', 10);
  //$pdf->SetFont('helvetica', '', 8);
  $view = \View::make('Defult.Commercial.Export.ShipperConfirmationPdf', ['rows' => $rows, 'sale_order_no' => $sale_order_no, 'style_ref' => $style_ref, 'article_no' => $article_no, 'item_description' => $item_description]);
  $html_content = $view->render();
  $pdf->SetY(40);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/ShipperConfirmationPdf.pdf';
  $pdf->output($filename);
  exit();
 }

 public function shipperCertificateDeclare()
 {
  $id = request('id', 0);
  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyers.id', '=', 'buyer_branches.buyer_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   /* ->leftJoin('countries',function($join){
            $join->on('countries.id','=','currencies.country_id');
        }) */
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    'exp_lc_scs.id',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.buyer_id',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'currencies.code as currency_code',
    'currencies.symbol as currency_symbol',
    //'countries.region_id',
   ])
   ->map(function ($rows) {
    return $rows;
   })
   ->first();

  $order = $this->expinvoice
   ->selectRaw('
            sales_orders.id,
            styles.style_ref,
            styles.id as style_id,
            sales_orders.sale_order_no,
            exp_pi_orders.sales_order_id,
            exp_invoices.id as exp_invoice_id,
            exp_pi_orders.id as exp_pi_order_id,
            exp_invoice_orders.id as exp_invoice_order_id,
            style_gmts.article,
            style_fabrications.id as style_fabrication_id,
            style_fabrications.gmtspart_id,
            item_accounts.item_description,
            item_accounts.id as item_account_id,
            exp_invoice_orders.qty as invoice_qty,
            exp_invoice_orders.rate as invoice_rate,
            exp_invoice_orders.amount as invoice_amount
        ')
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_invoice_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_pi_order_id', '=', 'exp_pi_orders.id');
   })
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('exp_rep_lc_scs', function ($join) {
    $join->on('exp_rep_lc_scs.exp_lc_sc_id', '=', 'exp_lc_scs.id');
   })
   ->join('exp_lc_sc_pis', function ($join) {
    $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
    $join->orOn('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
   })
   ->join('exp_pis', function ($join) {
    $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
    //$join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
    //$join->on('sales_order_countries.country_id', '=', 'exp_invoices.country_id');
   })
   // ->join('sales_order_gmt_color_sizes', function($join)  {
   //     $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
   // })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('budgets', function ($join) {
    $join->on('styles.id', '=', 'budgets.style_id');
   })
   ->leftJoin('budget_fabrics', function ($join) {
    $join->on('budget_fabrics.budget_id', '=', 'budgets.id');
   })
   ->join('style_fabrications', function ($join) {
    $join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
    $join->on('style_fabrications.style_id', '=', 'budgets.style_id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
   })
   ->join('autoyarns', function ($join) {
    $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'style_fabrications.uom_id');
   })
   ->where([['style_fabrications.is_narrow', '=', 0]])
   ->where([['exp_invoices.id', '=', $id]])
   ->groupBy([
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'styles.id',
    'styles.style_ref',
    'exp_pi_orders.sales_order_id',
    'exp_invoices.id',
    'exp_pi_orders.id',
    'exp_invoice_orders.id',
    'style_gmts.article',
    'style_fabrications.gmtspart_id',
    'style_fabrications.id',
    'item_accounts.item_description',
    'item_accounts.id',
    'exp_invoice_orders.qty',
    'exp_invoice_orders.rate',
    'exp_invoice_orders.amount',
   ])
   ->get();

  $salesorderarr = array();
  $starr = array();
  $artarr = array();
  $itemdescarr = array();
  foreach ($order as $data) {
   $salesorderarr[$data->sales_order_id] = $data->sale_order_no;
   $starr[$data->style_id] = $data->style_ref;
   $artarr[$data->style_id] = $data->article;
   //$itemdescarr[$data->exp_invoice_id][]=$data->item_description;
   $itemdescarr[$data->item_account_id] = $data->item_description;
  }
  $sale_order_no = implode(',', $salesorderarr);
  $style_ref = implode(',', $starr);
  $article_no = implode(',', $artarr);
  $item_description = implode(',', $itemdescarr);

  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $pdf->SetY(15);

  $pdf->SetFont('helvetica', 'N', 10);
  //$pdf->SetFont('helvetica', '', 8);
  $view = \View::make('Defult.Commercial.Export.ShipperCertificateDeclarePdf', ['rows' => $rows, 'sale_order_no' => $sale_order_no, 'style_ref' => $style_ref, 'article_no' => $article_no, 'item_description' => $item_description]);
  $html_content = $view->render();
  $pdf->SetY(40);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/ShipperCertificateDeclarePdf.pdf';
  $pdf->output($filename);
  exit();
 }


 public function bnfConfirmAzo()
 {
  $id = request('id', 0);
  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyers.id', '=', 'buyer_branches.buyer_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   /* ->leftJoin('countries',function($join){
            $join->on('countries.id','=','currencies.country_id');
        }) */
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    'exp_lc_scs.id',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.buyer_id',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'currencies.code as currency_code',
    'currencies.symbol as currency_symbol',
    //'countries.region_id',
   ])
   ->map(function ($rows) {
    return $rows;
   })
   ->first();

  $order = $this->expinvoice
   ->selectRaw('
            sales_orders.id,
            styles.style_ref,
            styles.id as style_id,
            sales_orders.sale_order_no,
            exp_pi_orders.sales_order_id,
            exp_invoices.id as exp_invoice_id,
            exp_pi_orders.id as exp_pi_order_id,
            exp_invoice_orders.id as exp_invoice_order_id,
            style_gmts.article,
            style_fabrications.id as style_fabrication_id,
            style_fabrications.gmtspart_id,
            item_accounts.item_description,
            item_accounts.id as item_account_id,
            exp_invoice_orders.qty as invoice_qty,
            exp_invoice_orders.rate as invoice_rate,
            exp_invoice_orders.amount as invoice_amount
        ')
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_invoice_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_pi_order_id', '=', 'exp_pi_orders.id');
   })
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('exp_rep_lc_scs', function ($join) {
    $join->on('exp_rep_lc_scs.exp_lc_sc_id', '=', 'exp_lc_scs.id');
   })
   ->join('exp_lc_sc_pis', function ($join) {
    $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
    $join->orOn('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
   })
   ->join('exp_pis', function ($join) {
    $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
    //$join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
    //$join->on('sales_order_countries.country_id', '=', 'exp_invoices.country_id');
   })
   // ->join('sales_order_gmt_color_sizes', function($join)  {
   //     $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
   // })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('budgets', function ($join) {
    $join->on('styles.id', '=', 'budgets.style_id');
   })
   ->leftJoin('budget_fabrics', function ($join) {
    $join->on('budget_fabrics.budget_id', '=', 'budgets.id');
   })
   ->join('style_fabrications', function ($join) {
    $join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
    $join->on('style_fabrications.style_id', '=', 'budgets.style_id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
   })
   ->join('autoyarns', function ($join) {
    $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'style_fabrications.uom_id');
   })
   ->where([['style_fabrications.is_narrow', '=', 0]])
   ->where([['exp_invoices.id', '=', $id]])
   ->groupBy([
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'styles.id',
    'styles.style_ref',
    'exp_pi_orders.sales_order_id',
    'exp_invoices.id',
    'exp_pi_orders.id',
    'exp_invoice_orders.id',
    'style_gmts.article',
    'style_fabrications.gmtspart_id',
    'style_fabrications.id',
    'item_accounts.item_description',
    'item_accounts.id',
    'exp_invoice_orders.qty',
    'exp_invoice_orders.rate',
    'exp_invoice_orders.amount',
   ])
   ->get();

  $salesorderarr = array();
  $starr = array();
  $artarr = array();
  $itemdescarr = array();
  foreach ($order as $data) {
   $salesorderarr[$data->sales_order_id] = $data->sale_order_no;
   $starr[$data->style_id] = $data->style_ref;
   $artarr[$data->style_id] = $data->article;
   //$itemdescarr[$data->exp_invoice_id][]=$data->item_description;
   $itemdescarr[$data->item_account_id] = $data->item_description;
  }
  $sale_order_no = implode(',', $salesorderarr);
  $style_ref = implode(',', $starr);
  $article_no = implode(',', $artarr);
  $item_description = implode(',', $itemdescarr);

  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $pdf->SetY(15);

  $pdf->SetFont('helvetica', 'N', 10);
  //$pdf->SetFont('helvetica', '', 8);
  $view = \View::make('Defult.Commercial.Export.BnfConfirmAZOPdf', ['rows' => $rows, 'sale_order_no' => $sale_order_no, 'style_ref' => $style_ref, 'article_no' => $article_no, 'item_description' => $item_description]);
  $html_content = $view->render();
  $pdf->SetY(40);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/BnfConfirmAZOPdf.pdf';
  $pdf->output($filename);
  exit();
 }

 public function certifyBanAzo()
 {
  $id = request('id', 0);
  $rows = $this->expinvoice
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyers.id', '=', 'buyer_branches.buyer_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   /* ->leftJoin('countries',function($join){
            $join->on('countries.id','=','currencies.country_id');
        }) */
   ->where([['exp_invoices.id', '=', $id]])
   ->get([
    'exp_invoices.*',
    'exp_lc_scs.id',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.buyer_id',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'currencies.code as currency_code',
    'currencies.symbol as currency_symbol',
    //'countries.region_id',
   ])
   ->map(function ($rows) {
    return $rows;
   })
   ->first();

  $order = $this->expinvoice
   ->selectRaw('
            sales_orders.id,
            styles.style_ref,
            styles.id as style_id,
            styles.style_description,
            sales_orders.sale_order_no,
            exp_pi_orders.sales_order_id,
            exp_invoices.id as exp_invoice_id,
            exp_pi_orders.id as exp_pi_order_id,
            exp_invoice_orders.id as exp_invoice_order_id,
            style_gmts.article,
            style_fabrications.id as style_fabrication_id,
            style_fabrications.gmtspart_id,
            item_accounts.item_description,
            item_accounts.id as item_account_id,
            exp_invoice_orders.qty as invoice_qty,
            exp_invoice_orders.rate as invoice_rate,
            exp_invoice_orders.amount as invoice_amount
        ')
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_invoice_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_pi_order_id', '=', 'exp_pi_orders.id');
   })
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('exp_rep_lc_scs', function ($join) {
    $join->on('exp_rep_lc_scs.exp_lc_sc_id', '=', 'exp_lc_scs.id');
   })
   ->join('exp_lc_sc_pis', function ($join) {
    $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
    $join->orOn('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
   })
   ->join('exp_pis', function ($join) {
    $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
    //$join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
    //$join->on('sales_order_countries.country_id', '=', 'exp_invoices.country_id');
   })
   // ->join('sales_order_gmt_color_sizes', function($join)  {
   //     $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
   // })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('budgets', function ($join) {
    $join->on('styles.id', '=', 'budgets.style_id');
   })
   ->leftJoin('budget_fabrics', function ($join) {
    $join->on('budget_fabrics.budget_id', '=', 'budgets.id');
   })
   ->join('style_fabrications', function ($join) {
    $join->on('budget_fabrics.style_fabrication_id', '=', 'style_fabrications.id');
    $join->on('style_fabrications.style_id', '=', 'budgets.style_id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
   })
   ->join('autoyarns', function ($join) {
    $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'style_fabrications.uom_id');
   })
   ->where([['style_fabrications.is_narrow', '=', 0]])
   ->where([['exp_invoices.id', '=', $id]])
   ->groupBy([
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'styles.id',
    'styles.style_ref',
    'styles.style_description',
    'exp_pi_orders.sales_order_id',
    'exp_invoices.id',
    'exp_pi_orders.id',
    'exp_invoice_orders.id',
    'style_gmts.article',
    'style_fabrications.gmtspart_id',
    'style_fabrications.id',
    'item_accounts.item_description',
    'item_accounts.id',
    'exp_invoice_orders.qty',
    'exp_invoice_orders.rate',
    'exp_invoice_orders.amount',
   ])
   ->get();

  $salesorderarr = array();
  $starr = array();
  $stDescarr = array();
  $artarr = array();
  $itemdescarr = array();
  foreach ($order as $data) {
   $salesorderarr[$data->sales_order_id] = $data->sale_order_no;
   $starr[$data->style_id] = $data->style_ref;
   $stDescarr[$data->style_id] = $data->style_description;
   $artarr[$data->style_id] = $data->article;
   //$itemdescarr[$data->exp_invoice_id][]=$data->item_description;
   $itemdescarr[$data->item_account_id] = $data->item_description;
  }
  $sale_order_no = implode(',', $salesorderarr);
  $style_ref = implode(',', $starr);
  $style_description = implode(',', $stDescarr);
  $article_no = implode(',', $artarr);
  $item_description = implode(',', $itemdescarr);

  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $pdf->SetY(15);

  $pdf->SetFont('helvetica', 'N', 9);
  //$pdf->SetFont('helvetica', '', 8);
  $view = \View::make('Defult.Commercial.Export.CertifyBanAZOPdf', ['rows' => $rows, 'sale_order_no' => $sale_order_no, 'style_ref' => $style_ref, 'article_no' => $article_no, 'item_description' => $item_description, 'style_description' => $style_description]);
  $html_content = $view->render();
  $pdf->SetY(40);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/CertifyBanAZOPdf.pdf';
  $pdf->output($filename);
  exit();
 }
}
