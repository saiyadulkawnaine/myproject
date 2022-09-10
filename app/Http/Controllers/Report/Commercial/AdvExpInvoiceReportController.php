<?php

namespace App\Http\Controllers\Report\Commercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceRepository;
use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;

class AdvExpInvoiceReportController extends Controller
{
 private $explcsc;
 private $expinvoice;
 private $expadvinvoice;
 private $company;
 private $buyer;

 public function __construct(
  ExpLcScRepository $explcsc,
  ExpInvoiceRepository $expinvoice,
  ExpAdvInvoiceRepository $expadvinvoice,
  CompanyRepository $company,
  BuyerRepository $buyer

 ) {
  $this->explcsc    = $explcsc;
  $this->expinvoice    = $expinvoice;
  $this->expadvinvoice    = $expadvinvoice;
  $this->company = $company;
  $this->buyer = $buyer;
  $this->middleware('auth');
  //$this->middleware('permission:view.cashincentivefollowupreports',   ['only' => ['create', 'index','show']]);
 }
 public function index()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '', '');
  return Template::loadView('Report.Commercial.AdvExpInvoiceReport', ['company' => $company, 'buyer' => $buyer]);
 }


 public function getData()
 {
  $data = $this->expadvinvoice
   ->selectRaw('
    		styles.style_ref,
    		buyers.name as buyer_name,
    		banks.name as lien_bank,
    		bcompanies.code as company_id,
    		companies.code as pcompany,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
    		exp_lc_scs.lc_sc_no,
    		exp_lc_scs.lc_sc_date,
    		exp_adv_invoices.invoice_no,
            exp_adv_invoices.id as exp_adv_invoice_id,
    		exp_adv_invoices.invoice_date,
            exp_adv_invoice_orders.exp_pi_order_id,
			exp_adv_invoice_orders.qty as adv_invoice_qty,
			exp_adv_invoice_orders.rate as adv_invoice_rate,
			exp_adv_invoice_orders.amount as adv_invoice_amount,
			invoice.invoice_qty,
			invoice.invoice_amount,
            exp_adv_invoices.invoice_value

    	')
   ->join('exp_adv_invoice_orders', function ($join) {
    $join->on('exp_adv_invoice_orders.exp_adv_invoice_id', '=', 'exp_adv_invoices.id');
    $join->whereNull('exp_adv_invoice_orders.deleted_at');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_pi_orders.id', '=', 'exp_adv_invoice_orders.exp_pi_order_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('exp_pi_orders.sales_order_id', '=', 'sales_orders.id');
   })
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_adv_invoices.exp_lc_sc_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'sales_orders.produced_company_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->leftJoin('companies as bcompanies', function ($join) {
    $join->on('bcompanies.id', '=', 'jobs.company_id');
   })
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
   })
   ->leftJoin(\DB::raw("( 
            select 
            exp_adv_invoices.id as exp_adv_invoice_id,
            exp_invoice_orders.exp_pi_order_id,
            sum(exp_invoice_orders.qty) as invoice_qty,
            avg(exp_invoice_orders.rate) as invoice_rate,
            sum(exp_invoice_orders.amount) as invoice_amount
            from exp_invoices
            join exp_adv_invoices on exp_invoices.exp_adv_invoice_id = exp_adv_invoices.id
            join exp_adv_invoice_orders on exp_adv_invoice_orders.exp_adv_invoice_id = exp_adv_invoices.id
            join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id
            join exp_pi_orders on exp_pi_orders.id= exp_invoice_orders.exp_pi_order_id 
            join sales_orders on exp_pi_orders.sales_order_id = sales_orders.id 
            join exp_lc_scs on exp_lc_scs.id = exp_invoices.exp_lc_sc_id 
            left join jobs on jobs.id = sales_orders.job_id 
            left join styles on styles.id = jobs.style_id 
            left join buyers on buyers.id = styles.buyer_id 
            where exp_invoice_orders.deleted_at is null
            and exp_adv_invoice_orders.deleted_at is null
            and exp_adv_invoice_orders.exp_pi_order_id=exp_invoice_orders.exp_pi_order_id
            group by
            exp_adv_invoices.id, 
            exp_invoice_orders.exp_pi_order_id
        ) invoice"), [
    ["invoice.exp_pi_order_id", "=", "exp_adv_invoice_orders.exp_pi_order_id"],
    ["invoice.exp_adv_invoice_id", "=", "exp_adv_invoices.id"]
   ])
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->when(request('invoice_date_from', 0), function ($q) {
    return $q->where('exp_adv_invoices.invoice_date', '>=', request('invoice_date_from', 0));
   })
   ->when(request('invoice_date_to', 0), function ($q) {
    return $q->where('exp_adv_invoices.invoice_date', '<=', request('invoice_date_to', 0));
   })
   ->when(request('lc_sc_date_from', 0), function ($q) {
    return $q->where('exp_lc_scs.lc_sc_date', '>=', request('lc_sc_date_from', 0));
   })
   ->when(request('lc_sc_date_to', 0), function ($q) {
    return $q->where('exp_lc_scs.lc_sc_date', '<=', request('lc_sc_date_to', 0));
   })
   ->when(request('lc_sc_no', 0), function ($q) {
    return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%" . request('lc_sc_no', 0) . "%");
   })
   ->when(request('buyer_id', 0), function ($q) {
    return $q->where('exp_lc_scs.buyer_id', '=', request('buyer_id', 0));
   })
   ->when(request('company_id', 0), function ($q) {
    return $q->where('jobs.company_id', '=', request('company_id', 0));
   })
   ->orderBy('exp_adv_invoices.invoice_date')
   ->get()
   ->map(function ($data) {
    $data->ship_date = date('d-M-Y', strtotime($data->ship_date));
    $data->lc_sc_date = date('d-M-Y', strtotime($data->lc_sc_date));
    $data->invoice_date = date('d-M-Y', strtotime($data->invoice_date));
    $yet_to_adj_qty = $data->adv_invoice_qty - $data->invoice_qty;
    $data->adv_invoice_qty = number_format($data->adv_invoice_qty, 0);
    $data->invoice_qty = number_format($data->invoice_qty, 0);
    $data->yet_to_adj_qty = number_format($yet_to_adj_qty, 0);
    $yet_to_adj_amount = $data->adv_invoice_amount - $data->invoice_amount;
    $data->adv_invoice_amount = number_format($data->adv_invoice_amount);
    $data->invoice_amount = number_format($data->invoice_amount);
    $data->yet_to_adj_amount = number_format($yet_to_adj_amount);
    return $data;
   });

  echo json_encode($data);
 }

 public function getExportLcSc()
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
    $rows->contractNature = $contractNature[$rows->lc_sc_nature_id];
    return $rows;
   });
  echo json_encode($rows);
 }

 public function getInvoice()
 {
  $exp_pi_order_id = request('exp_pi_order_id', 0);
  $exp_adv_invoice_id = request('exp_adv_invoice_id', 0);

  $expinvoices = array();
  $rows = $this->expinvoice
   ->selectRaw('
            exp_invoices.id as exp_invoice_id,
            exp_invoices.invoice_no,
            exp_invoices.invoice_date,
            sum(exp_invoice_orders.qty) as invoice_qty,
            sum(exp_invoice_orders.amount) as invoice_amount
        ')
   ->join('exp_adv_invoices', function ($join) {
    $join->on('exp_adv_invoices.id', '=', 'exp_invoices.exp_adv_invoice_id');
   })
   ->join('exp_invoice_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_invoice_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->where([['exp_invoice_orders.exp_pi_order_id', '=', $exp_pi_order_id]])
   ->where([['exp_invoices.exp_adv_invoice_id', '=', $exp_adv_invoice_id]])
   ->orderBy('exp_invoices.id', 'desc')
   ->groupBy([
    'exp_invoices.id',
    'exp_invoices.invoice_no',
    'exp_invoices.invoice_date'
   ])
   ->get();
  foreach ($rows as $row) {
   $expinvoice['id'] = $row->id;
   $expinvoice['invoice_no'] = $row->invoice_no;
   $expinvoice['invoice_date'] = date('Y-m-d', strtotime($row->invoice_date));
   $expinvoice['invoice_amount'] = number_format($row->invoice_amount, 2);
   if ($row->invoice_qty) {
    $expinvoice['invoice_rate'] = number_format($row->invoice_amount / $row->invoice_qty, 2);
   }
   $expinvoice['invoice_qty'] = number_format($row->invoice_qty, 2);

   array_push($expinvoices, $expinvoice);
  }
  echo json_encode($expinvoices);
 }
}
