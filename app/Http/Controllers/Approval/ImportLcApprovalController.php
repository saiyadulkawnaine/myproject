<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Sms;
use Illuminate\Support\Carbon;
use App\Library\Numbertowords;

class ImportLcApprovalController extends Controller
{
 private $implc;
 private $user;
 private $buyer;
 private $company;
 private $currency;
 private $country;
 private $supplier;
 private $bank;
 private $itemcategory;
 private $explcsc;
 private $bankbranch;
 private $bankaccount;

 public function __construct(
  ImpLcRepository $implc,
  CurrencyRepository $currency,
  CountryRepository $country,
  SupplierRepository $supplier,
  BankRepository $bank,
  CompanyRepository $company,
  ItemcategoryRepository $itemcategory,
  ExpLcScRepository $explcsc,
  BankBranchRepository $bankbranch,
  BankAccountRepository $bankaccount,
  UserRepository $user,
  BuyerRepository $buyer

 ) {
  $this->implc = $implc;
  $this->user = $user;
  $this->company = $company;
  $this->buyer = $buyer;
  $this->currency = $currency;
  $this->country = $country;
  $this->supplier = $supplier;
  $this->bank = $bank;
  $this->itemcategory = $itemcategory;
  $this->explcsc = $explcsc;
  $this->bankbranch = $bankbranch;
  $this->bankaccount = $bankaccount;

  $this->middleware('auth');

  // $this->middleware('permission:approve.importLc',   ['only' => ['approved', 'index','reportData','reportDataApp','unapproved']]);
 }

 public function index()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->where([['status_id', '=', 1]])->get(), 'name', 'id'), '-Select-', '');
  $menu = array_prepend(config('bprs.menu'), '-Select-', '');
  return Template::loadView('Approval.ImportLcApproval', ['company' => $company, 'buyer' => $buyer, 'supplier' => $supplier, 'menu' => $menu]);
 }

 public function reportData()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'code', 'id'), '-Select-', '');
  $country = array_prepend(array_pluck($this->country->get(), 'code', 'id'), '-Select-', '');
  $currency = array_prepend(array_pluck($this->currency->get(), 'code', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  $bankbranch = array_prepend(array_pluck(
   $this->bankbranch
    ->leftJoin('banks', function ($join) {
     $join->on('banks.id', '=', 'bank_branches.bank_id');
    })
    ->get([
     'bank_branches.id',
     'bank_branches.branch_name',
     'banks.name as bank_name',
    ])
    ->map(function ($bankbranch) {
     $bankbranch->name = $bankbranch->bank_name . ' (' . $bankbranch->branch_name . ' )';
     return $bankbranch;
    }),
   'name',
   'id'
  ), '-Select-', '');
  $lctype = array_prepend(config('bprs.lctype'), '-Select-', '');
  $payterm = array_prepend(config('bprs.payterm'), '-Select-', '');
  $menu = array_prepend(config('bprs.menu'), '-Select-', '');

  $implcs = array();
  $rows = collect(\DB::select(
   "
    select 
    imp_lcs.id,
    imp_lcs.menu_id,
    imp_lcs.lc_date,
    imp_lcs.company_id,
    imp_lcs.supplier_id,
    imp_lcs.lc_type_id,
    imp_lcs.issuing_bank_branch_id,
    imp_lcs.last_delivery_date,
    imp_lcs.expiry_date,
    imp_lcs.lc_application_date,
    imp_lcs.lc_no_i,
    imp_lcs.lc_no_ii,
    imp_lcs.lc_no_iii,
    imp_lcs.lc_no_iv,
    imp_lcs.pay_term_id,
    case when 
    imp_lcs.menu_id=1
    then sum(po_fabrics.amount)
    when 
    imp_lcs.menu_id=2
    then sum(po_trims.amount)
    when 
    imp_lcs.menu_id=3
    then sum(po_yarns.amount)
    when 
    imp_lcs.menu_id=4
    then sum(po_knit_services.amount)
    when 
    imp_lcs.menu_id=5
    then sum(po_aop_services.amount)
    when 
    imp_lcs.menu_id=6
    then sum(po_dyeing_services.amount)
    when 
    imp_lcs.menu_id=7
    then sum(po_dye_chems.amount)
    when 
    imp_lcs.menu_id=8
    then sum(po_generals.amount)
    when 
    imp_lcs.menu_id=9
    then sum(po_yarn_dyeings.amount)
    when 
    imp_lcs.menu_id=10
    then sum(po_emb_services.amount)
    when 
    imp_lcs.menu_id=11
    then sum(po_general_services.amount)
    else 0
    end as lc_amount
    from imp_lcs  
    left join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
    left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
    left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
    left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
    left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
    left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
    left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
    left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
    left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
    left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
    left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
    left join po_general_services on po_general_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=11
    where imp_lcs.ready_to_approve_id=1
    and imp_lcs.approved_at is null
    group by 
    imp_lcs.id,
    imp_lcs.menu_id,
    imp_lcs.lc_date,
    imp_lcs.company_id,
    imp_lcs.supplier_id,
    imp_lcs.lc_type_id,
    imp_lcs.issuing_bank_branch_id,
    imp_lcs.last_delivery_date,
    imp_lcs.expiry_date,
    imp_lcs.lc_application_date,
    imp_lcs.lc_no_i,
    imp_lcs.lc_no_ii,
    imp_lcs.lc_no_iii,
    imp_lcs.lc_no_iv,
    imp_lcs.pay_term_id
    order by imp_lcs.id desc
    "
  ));
  foreach ($rows as $row) {
   $implc['id'] = $row->id;
   $implc['company'] = isset($company[$row->company_id]) ? $company[$row->company_id] : '--';
   $implc['supplier'] = isset($supplier[$row->supplier_id]) ? $supplier[$row->supplier_id] : '--';
   $implc['lc_type_id'] = isset($lctype[$row->lc_type_id]) ? $lctype[$row->lc_type_id] : '--';
   $implc['bankbranch'] = isset($bankbranch[$row->issuing_bank_branch_id]) ? $bankbranch[$row->issuing_bank_branch_id] : '--';
   $implc['last_delivery_date'] = ($row->last_delivery_date !== null) ? date("Y-m-d", strtotime($row->last_delivery_date)) : '--';
   $implc['expiry_date'] = ($row->expiry_date !== null) ? date("Y-m-d", strtotime($row->expiry_date)) : '--';
   $implc['lc_date'] = ($row->lc_date !== null) ? date("Y-m-d", strtotime($row->lc_date)) : '--';
   $implc['lc_application_date'] = ($row->lc_application_date !== null) ? date('Y-m-d', strtotime($row->lc_application_date)) : '--';
   $implc['lc_no'] = $row->lc_no_i . " " . $row->lc_no_ii . " " . $row->lc_no_iii . " " . $row->lc_no_iv;
   $implc['pay_term_id'] = isset($payterm[$row->pay_term_id]) ? $payterm[$row->pay_term_id] : '--';
   $implc['lc_amount'] = number_format($row->lc_amount, 2);
   $implc['menu_id'] = isset($menu[$row->menu_id]) ? $menu[$row->menu_id] : '--';
   array_push($implcs, $implc);
  }
  echo json_encode($implcs);
 }

 public function approved(Request $request)
 {
  $id = request('id', 0);
  $master = $this->implc->find($id);
  $user = \Auth::user();
  $approved_at = date('Y-m-d h:i:s');
  $master->approved_by = $user->id;
  $master->approved_at = $approved_at;
  $master->unapproved_by = NULL;
  $master->unapproved_at = NULL;
  $master->timestamps = false;
  $implc = $master->save();
  if ($implc) {
   return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
  }
 }

 public function reportDataApp()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'code', 'id'), '-Select-', '');
  $country = array_prepend(array_pluck($this->country->get(), 'code', 'id'), '-Select-', '');
  $currency = array_prepend(array_pluck($this->currency->get(), 'code', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  $bankbranch = array_prepend(array_pluck(
   $this->bankbranch
    ->leftJoin('banks', function ($join) {
     $join->on('banks.id', '=', 'bank_branches.bank_id');
    })
    ->get([
     'bank_branches.id',
     'bank_branches.branch_name',
     'banks.name as bank_name',
    ])
    ->map(function ($bankbranch) {
     $bankbranch->name = $bankbranch->bank_name . ' (' . $bankbranch->branch_name . ' )';
     return $bankbranch;
    }),
   'name',
   'id'
  ), '-Select-', '');
  $lctype = array_prepend(config('bprs.lctype'), '-Select-', '');
  $payterm = array_prepend(config('bprs.payterm'), '-Select-', '');
  $menu = array_prepend(config('bprs.menu'), '-Select-', '');

  $implcs = array();
  $rows = collect(\DB::select(
   "
      select 
      imp_lcs.id,
      imp_lcs.menu_id,
      imp_lcs.lc_date,
      imp_lcs.company_id,
      imp_lcs.supplier_id,
      imp_lcs.lc_type_id,
      imp_lcs.issuing_bank_branch_id,
      imp_lcs.last_delivery_date,
      imp_lcs.expiry_date,
      imp_lcs.lc_application_date,
      imp_lcs.lc_no_i,
      imp_lcs.lc_no_ii,
      imp_lcs.lc_no_iii,
      imp_lcs.lc_no_iv,
      imp_lcs.pay_term_id,
      case when 
      imp_lcs.menu_id=1
      then sum(po_fabrics.amount)
      when 
      imp_lcs.menu_id=2
      then sum(po_trims.amount)
      when 
      imp_lcs.menu_id=3
      then sum(po_yarns.amount)
      when 
      imp_lcs.menu_id=4
      then sum(po_knit_services.amount)
      when 
      imp_lcs.menu_id=5
      then sum(po_aop_services.amount)
      when 
      imp_lcs.menu_id=6
      then sum(po_dyeing_services.amount)
      when 
      imp_lcs.menu_id=7
      then sum(po_dye_chems.amount)
      when 
      imp_lcs.menu_id=8
      then sum(po_generals.amount)
      when 
      imp_lcs.menu_id=9
      then sum(po_yarn_dyeings.amount)
      when 
      imp_lcs.menu_id=10
      then sum(po_emb_services.amount)
      when 
      imp_lcs.menu_id=11
      then sum(po_general_services.amount)
      else 0
      end as lc_amount
      from imp_lcs  
      left join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
      left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
      left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
      left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
      left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
      left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
      left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
      left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
      left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
      left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
      left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
      left join po_general_services on po_general_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=11
      where imp_lcs.approved_at is not null
      group by 
      imp_lcs.id,
      imp_lcs.menu_id,
      imp_lcs.lc_date,
      imp_lcs.company_id,
      imp_lcs.supplier_id,
      imp_lcs.lc_type_id,
      imp_lcs.issuing_bank_branch_id,
      imp_lcs.last_delivery_date,
      imp_lcs.expiry_date,
      imp_lcs.lc_application_date,
      imp_lcs.lc_no_i,
      imp_lcs.lc_no_ii,
      imp_lcs.lc_no_iii,
      imp_lcs.lc_no_iv,
      imp_lcs.pay_term_id
      order by imp_lcs.id desc
      "
  ));
  foreach ($rows as $row) {
   $implc['id'] = $row->id;
   $implc['company'] = isset($company[$row->company_id]) ? $company[$row->company_id] : '--';
   $implc['supplier'] = isset($supplier[$row->supplier_id]) ? $supplier[$row->supplier_id] : '--';
   $implc['lc_type_id'] = isset($lctype[$row->lc_type_id]) ? $lctype[$row->lc_type_id] : '--';
   $implc['bankbranch'] = isset($bankbranch[$row->issuing_bank_branch_id]) ? $bankbranch[$row->issuing_bank_branch_id] : '--';
   $implc['last_delivery_date'] = ($row->last_delivery_date !== null) ? date("Y-m-d", strtotime($row->last_delivery_date)) : '--';
   $implc['expiry_date'] = ($row->expiry_date !== null) ? date("Y-m-d", strtotime($row->expiry_date)) : '--';
   $implc['lc_date'] = ($row->lc_date !== null) ? date("Y-m-d", strtotime($row->lc_date)) : '--';
   $implc['lc_application_date'] = ($row->lc_application_date !== null) ? date('Y-m-d', strtotime($row->lc_application_date)) : '--';
   $implc['lc_no'] = $row->lc_no_i . " " . $row->lc_no_ii . " " . $row->lc_no_iii . " " . $row->lc_no_iv;
   $implc['pay_term_id'] = isset($payterm[$row->pay_term_id]) ? $payterm[$row->pay_term_id] : '--';
   $implc['lc_amount'] = number_format($row->lc_amount, 2);
   $implc['menu_id'] = isset($menu[$row->menu_id]) ? $menu[$row->menu_id] : '--';
   array_push($implcs, $implc);
  }
  echo json_encode($implcs);
 }

 public function unapproved(Request $request)
 {
  $id = request('id', 0);
  $master = $this->implc->find($id);
  $user = \Auth::user();
  $unapproved_at = date('Y-m-d h:i:s');
  $unapproved_count = $master->unapproved_count + 1;
  $master->approved_by = NUll;
  $master->approved_at = NUll;
  $master->unapproved_by = $user->id;
  $master->unapproved_at = $unapproved_at;
  $master->unapproved_count = $unapproved_count;
  $master->timestamps = false;
  $implc = $master->save();


  if ($implc) {
   return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
  }
 }

 public function getApprovalPdf()
 {
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '', '');
  $payterm = array_prepend(config('bprs.payterm'), '', '');
  $maturityform = array_prepend(config('bprs.maturityform'), '', '');
  $incoterm = array_prepend(config('bprs.incoterm'), '', '');
  $deliveryMode = array_prepend(config('bprs.deliveryMode'), '', '');
  $inoutcharges = array_prepend(config('bprs.inoutcharges'), '', '');
  $poType = [
   1 => "Fabric",
   2 => "Accessories",
   3 => "Yarn",
   4 => "Kniting Charge",
   5 => "AOP Charge",
   6 => "Dyeing Charge",
   7 => "Dyes & Chemical",
   8 => "General Item",
   9 => "Yarn Dyeing",
   10 => "Embellishment Charge",
   11 => "General Service"
  ];

  $bankaccount = array_prepend(array_pluck(
   $this->bankaccount
    ->leftJoin('commercial_heads', function ($join) {
     $join->on('commercial_heads.id', '=', 'bank_accounts.account_type_id');
    })
    ->get([
     'bank_accounts.id',
     'commercial_heads.name',
     'bank_accounts.account_no',
    ])
    ->map(function ($bankaccount) {
     $bankaccount->name = $bankaccount->name . ' (' . $bankaccount->account_no . ' )';
     return $bankaccount;
    }),
   'name',
   'id'
  ), '', '');

  $id = request('id', 0);
  $implc = $this->implc
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'imp_lcs.issuing_bank_branch_id');
   })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'imp_lcs.company_id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
   })
   ->leftJoin('suppliers as lc_to_suppliers', function ($join) {
    $join->on('lc_to_suppliers.id', '=', 'imp_lcs.lc_to_id');
   })
   ->leftJoin('suppliers as insurance', function ($join) {
    $join->on('insurance.id', '=', 'imp_lcs.insurance_company_id');
   })
   ->leftJoin('currencies', function ($join) {
    $join->on('currencies.id', '=', 'imp_lcs.currency_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'imp_lcs.origin_id');
   })
   ->leftJoin('bank_accounts', function ($join) {
    $join->on('bank_accounts.id', '=', 'imp_lcs.debit_ac_id');
   })
   ->leftJoin('commercial_heads', function ($join) {
    $join->on('commercial_heads.id', '=', 'bank_accounts.account_type_id');
   })
   ->where([['imp_lcs.id', '=', $id]])
   ->get([
    'imp_lcs.*',
    'banks.id as bank_id',
    'banks.name as bank_name',
    'bank_branches.branch_name',
    'bank_branches.address as bank_address',
    'bank_branches.contact',
    'suppliers.name as supplier_name',
    'suppliers.contact_person as supplier_contact',
    'suppliers.address as supplier_address',
    'suppliers.factory_address',
    'lc_to_suppliers.name as lcto_supplier_name',
    'lc_to_suppliers.contact_person as lcto_supplier_contact',
    'lc_to_suppliers.address as lcto_supplier_address',
    'lc_to_suppliers.factory_address as lcto_factory_address',
    'insurance.name as insurance_company_name',
    'insurance.address as insurance_company_address',
    'suppliers.email as supplier_email',
    'companies.name as company_name',
    'companies.address as company_address',
    'companies.tin_number',
    'companies.irc_no',
    'companies.ban_bank_reg_no',
    'companies.ban_bank_reg_date',
    'currencies.code as currency_code',
    'currencies.name as currency_name',
    'commercial_heads.name as account_type',
    'bank_accounts.account_no',
    'countries.name as origin_name',
   ])
   ->map(function ($implc) use ($payterm, $deliveryMode, $maturityform, $poType, $incoterm, $inoutcharges, $bankaccount) {
    $implc->lc_no = $implc->lc_no_i . " " . $implc->lc_no_ii . " " . $implc->lc_no_iii /* ." ".$implc->lc_no_iv */;
    $implc->lc_date = $implc->lc_date ? date('d-M-Y', strtotime($implc->lc_date)) : '';
    $implc->ban_bank_reg_date = $implc->ban_bank_reg_date ? date('d-M-Y', strtotime($implc->ban_bank_reg_date)) : '';
    $implc->last_ship_date = $implc->last_delivery_date ? date('d-M-Y', strtotime($implc->last_delivery_date)) : '';
    $implc->expiry_date = $implc->expiry_date ? date('d-M-Y', strtotime($implc->expiry_date)) : '';
    $implc->cover_note_date = $implc->cover_note_date ? date('d-M-Y', strtotime($implc->cover_note_date)) : '';

    $implc->pay_term = $payterm[$implc->pay_term_id];
    if ($implc->pay_term_id == 1) {
     $implc->credit_availed = "Deferred Payment";
    } elseif ($implc->pay_term_id == 2) {
     $implc->credit_availed = "Sight Payment";
    } else {
     $implc->credit_availed = $payterm[$implc->pay_term_id];
    }

    if ($implc->partial_shipment_id == 1) {
     $implc->partial_shipment = "Allowed";
    } else {
     $implc->partial_shipment = "Not Allowed";
    }

    if ($implc->transhipment_id == 1) {
     $implc->transhipment = "Allowed";
    } else {
     $implc->transhipment = "Not Allowed";
    }

    if ($implc->add_conf_ref_id == 1) {
     $implc->add_conf_ref = "Not requested";
    } else {
     $implc->add_conf_ref = "Requested";
    }

    $implc->add_conf_charge = $inoutcharges[$implc->add_conf_charge_id];
    $implc->maturity_form = $maturityform[$implc->maturity_form_id];
    $implc->delivery_mode = $deliveryMode[$implc->delivery_mode_id];
    $implc->incoterm_id = $incoterm[$implc->incoterm_id];
    $implc->inside_charge_id = $inoutcharges[$implc->inside_charge_id];
    $implc->outside_charge_id = $inoutcharges[$implc->outside_charge_id];
    $implc->debit_ac_id = $bankaccount[$implc->debit_ac_id];
    $implc->po_type = $poType[$implc->menu_id];
    if ($implc->pay_term_id == 2) {
     $implc->tenor_days = "sight";
    } else {
     $implc->tenor_days = $implc->tenor . "  Days";
    }
    return $implc;
    return $implc;
   })
   ->first();

  // $pi = collect(\DB::select("
  //     select 
  //     imp_lcs.id as imp_lc_id,
  //     imp_lcs.lc_to_id,
  //     imp_lc_pos.id as imp_lc_po_id,
  //     imp_lc_pos.purchase_order_id,
  //     case when 
  //     imp_lcs.menu_id=1
  //     then po_fabrics.supplier_id
  //     when 
  //     imp_lcs.menu_id=2
  //     then po_trims.supplier_id
  //     when 
  //     imp_lcs.menu_id=3
  //     then po_yarns.supplier_id
  //     when 
  //     imp_lcs.menu_id=4
  //     then po_knit_services.supplier_id
  //     when 
  //     imp_lcs.menu_id=5
  //     then po_aop_services.supplier_id
  //     when 
  //     imp_lcs.menu_id=6
  //     then po_dyeing_services.supplier_id
  //     when 
  //     imp_lcs.menu_id=7
  //     then po_dye_chems.supplier_id
  //     when 
  //     imp_lcs.menu_id=8
  //     then po_generals.supplier_id
  //     when 
  //     imp_lcs.menu_id=9
  //     then po_yarn_dyeings.supplier_id
  //     when 
  //     imp_lcs.menu_id=10
  //     then po_emb_services.supplier_id
  //     when 
  //     imp_lcs.menu_id=11
  //     then po_general_services.supplier_id
  //     else null
  //     end as supplier_id,

  //     case when 
  //     imp_lcs.menu_id=1
  //     then po_fabrics.pi_no
  //     when 
  //     imp_lcs.menu_id=2
  //     then po_trims.pi_no
  //     when 
  //     imp_lcs.menu_id=3
  //     then po_yarns.pi_no
  //     when 
  //     imp_lcs.menu_id=4
  //     then po_knit_services.pi_no
  //     when 
  //     imp_lcs.menu_id=5
  //     then po_aop_services.pi_no
  //     when 
  //     imp_lcs.menu_id=6
  //     then po_dyeing_services.pi_no
  //     when 
  //     imp_lcs.menu_id=7
  //     then po_dye_chems.pi_no
  //     when 
  //     imp_lcs.menu_id=8
  //     then po_generals.pi_no
  //     when 
  //     imp_lcs.menu_id=9
  //     then po_yarn_dyeings.pi_no
  //     when 
  //     imp_lcs.menu_id=10
  //     then po_emb_services.pi_no
  //     when 
  //     imp_lcs.menu_id=11
  //     then po_general_services.pi_no
  //     else null
  //     end as pi_no,

  //     case when 
  //     imp_lcs.menu_id=1
  //     then po_fabrics.pi_date
  //     when 
  //     imp_lcs.menu_id=2
  //     then po_trims.pi_date
  //     when 
  //     imp_lcs.menu_id=3
  //     then po_yarns.pi_date
  //     when 
  //     imp_lcs.menu_id=4
  //     then po_knit_services.pi_date
  //     when 
  //     imp_lcs.menu_id=5
  //     then po_aop_services.pi_date
  //     when 
  //     imp_lcs.menu_id=6
  //     then po_dyeing_services.pi_date
  //     when 
  //     imp_lcs.menu_id=7
  //     then po_dye_chems.pi_date
  //     when 
  //     imp_lcs.menu_id=8
  //     then po_generals.pi_date
  //     when 
  //     imp_lcs.menu_id=9
  //     then po_yarn_dyeings.pi_date
  //     when 
  //     imp_lcs.menu_id=10
  //     then po_emb_services.pi_date
  //     when 
  //     imp_lcs.menu_id=11
  //     then po_general_services.pi_date
  //     else null
  //     end as pi_date,
  //     case when 
  //     imp_lcs.menu_id=1
  //     then po_fabrics.id
  //     when 
  //     imp_lcs.menu_id=2
  //     then po_trims.id
  //     when 
  //     imp_lcs.menu_id=3
  //     then po_yarns.id
  //     when 
  //     imp_lcs.menu_id=4
  //     then po_knit_services.id
  //     when 
  //     imp_lcs.menu_id=5
  //     then po_aop_services.id
  //     when 
  //     imp_lcs.menu_id=6
  //     then po_dyeing_services.id
  //     when 
  //     imp_lcs.menu_id=7
  //     then po_dye_chems.id
  //     when 
  //     imp_lcs.menu_id=8
  //     then po_generals.id
  //     when 
  //     imp_lcs.menu_id=9
  //     then po_yarn_dyeings.id
  //     when 
  //     imp_lcs.menu_id=10
  //     then po_emb_services.id
  //     when 
  //     imp_lcs.menu_id=11
  //     then po_general_services.id
  //     else 0
  //     end as id,
  //     case when 
  //     imp_lcs.menu_id=1
  //     then po_fabrics.po_no
  //     when 
  //     imp_lcs.menu_id=2
  //     then po_trims.po_no
  //     when 
  //     imp_lcs.menu_id=3
  //     then po_yarns.po_no
  //     when 
  //     imp_lcs.menu_id=4
  //     then po_knit_services.po_no
  //     when 
  //     imp_lcs.menu_id=5
  //     then po_aop_services.po_no
  //     when 
  //     imp_lcs.menu_id=6
  //     then po_dyeing_services.po_no
  //     when 
  //     imp_lcs.menu_id=7
  //     then po_dye_chems.po_no
  //     when 
  //     imp_lcs.menu_id=8
  //     then po_generals.po_no
  //     when 
  //     imp_lcs.menu_id=9
  //     then po_yarn_dyeings.po_no
  //     when 
  //     imp_lcs.menu_id=10
  //     then po_emb_services.po_no
  //     when 
  //     imp_lcs.menu_id=11
  //     then po_general_services.po_no
  //     else 0
  //     end as po_no,
  //     case when 
  //     imp_lcs.menu_id=1
  //     then po_fabrics.amount
  //     when 
  //     imp_lcs.menu_id=2
  //     then po_trims.amount
  //     when 
  //     imp_lcs.menu_id=3
  //     then po_yarns.amount
  //     when 
  //     imp_lcs.menu_id=4
  //     then po_knit_services.amount
  //     when 
  //     imp_lcs.menu_id=5
  //     then po_aop_services.amount
  //     when 
  //     imp_lcs.menu_id=6
  //     then po_dyeing_services.amount
  //     when 
  //     imp_lcs.menu_id=7
  //     then po_dye_chems.amount
  //     when 
  //     imp_lcs.menu_id=8
  //     then po_generals.amount
  //     when 
  //     imp_lcs.menu_id=9
  //     then po_yarn_dyeings.amount
  //     when 
  //     imp_lcs.menu_id=10
  //     then po_emb_services.amount
  //     when 
  //     imp_lcs.menu_id=11
  //     then po_general_services.amount
  //     else 0
  //     end as amount
  //     from 
  //     imp_lcs
  //     join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
  //     left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
  //     left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
  //     left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
  //     left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
  //     left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
  //     left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
  //     left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
  //     left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
  //     left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
  //     left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
  //     left join po_general_services on po_general_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=11
  //     where imp_lcs.id=".$id."
  //     "
  // ))
  // ->map(function($pi) use($supplier){
  // $pi->pi_date=date('d-m-Y',strtotime($pi->pi_date));
  //     $pi->pi_date=($pi->pi_date !== null)?date('d-M-Y',strtotime($pi->pi_date)):null;
  //     if($pi->lc_to_id){
  //         $pi->supplier_name=$supplier[$pi->lc_to_id];
  //     }else{
  //         $pi->supplier_name=$supplier[$pi->supplier_id];
  //     }
  //     return $pi;
  // });

  // $amount_pi=0;

  // $supplier_name='';
  // $piNoArr=[];

  // foreach($pi as $row){
  //     $amount_pi+=$row->amount;
  //     $supplier_name=$supplier[$row->supplier_id];
  //     $piNoArr[$row->pi_date]=$row->pi_no.' Date:'.$row->pi_date;
  // }

  // $explcscs =$this->explcsc
  // ->selectRaw('
  //     exp_lc_scs.id ,
  //     exp_lc_scs.lc_sc_no ,
  //     exp_lc_scs.sc_or_lc ,
  //     exp_lc_scs.lc_sc_date ,
  //     exp_lc_scs.lc_sc_value ,
  //     exp_lc_scs.file_no ,
  //     exp_lc_scs.last_delivery_date ,
  //     buyers.code as buyer,
  //     companies.code as company,
  //     imp_backed_exp_lc_scs.exp_lc_sc_id
  // ')
  // ->join('imp_backed_exp_lc_scs',function($join){
  //     $join->on('imp_backed_exp_lc_scs.exp_lc_sc_id','=','exp_lc_scs.id');
  // })
  // ->join('imp_lcs',function($join){
  //     $join->on('imp_lcs.id','=','imp_backed_exp_lc_scs.imp_lc_id');
  // })
  // ->join('companies', function($join)  {
  //     $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
  // })
  // ->join('buyers', function($join)  {
  //     $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
  // })
  // ->when(request('lc_sc_no'), function ($q) {
  //     return $q->where('exp_lc_scs.lc_sc_no', '=', request('lc_sc_no', 0));
  // })
  // ->where([['imp_lcs.id','=',$id]])
  // ->get();

  // $lc_sc_amount=0;
  // $LC=""; 
  // $SC="";

  // foreach($explcscs as $explcsc){
  //     if($explcsc->sc_or_lc==2){
  //   $LC.=$explcsc->lc_sc_no."    "."Date:".date('d-M-Y',strtotime($explcsc->lc_sc_date)).",";
  //     }
  //     if($explcsc->sc_or_lc==1){
  //         $SC.=$explcsc->lc_sc_no."    "."Date:".date('d-M-Y',strtotime($explcsc->lc_sc_date)).",";
  //     }
  //     $lc_sc_amount+=$explcsc->lc_sc_value;
  // }
  // $lc_string='';
  // if($LC){
  //     $lc_string.='Export LC No: '.$LC;
  // }
  // $sc_string='';
  // if($SC){
  //     $sc_string.='Sales Contract No: '.$SC;
  // }

  // $amount=$amount_pi;
  // $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$implc->currency_name,'cents only');
  // $implc->inword=$inword;

  // $implc->exp_lc_sc=$lc_string." ".$sc_string;
  // $implc->lc_sc_amount=$lc_sc_amount;
  //dd(implode(';',$piNoArr));die;
  // $implc->pi_no=implode('; ',$piNoArr);



  if ($implc->bank_id == 1) {
   $pdf = new \TCPDF('P', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
   $pdf->SetPrintHeader(false);
   $pdf->SetPrintFooter(false);
   $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
   $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
   $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
   $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
   $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
   $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
   $pdf->SetAutoPageBreak(TRUE, 10);
   $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
   $pdf->SetFont('helvetica', 'B', 12);
   $pdf->AddPage();
   $pdf->SetY(5);
   $image_file = 'images/logo/islami_bank_logo.png';
   $pdf->Image($image_file, 10, 5, 20, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
   $pdf->SetY(15);
   $pdf->SetFont('helvetica', 'N', 9);
   // $view= \View::make('Defult.Approval.ImportLcApprovalPdf',['implc'=>$implc,'amount_pi'=>$amount_pi]);
   $view = \View::make('Defult.Approval.ImportLcApprovalPdf', ['implc' => $implc]);
   $html_content = $view->render();
   $pdf->WriteHtml($html_content, true, false, true, false, '');
   $filename = storage_path() . '/ImportLcApprovalPdf.pdf';
   $pdf->output($filename, 'I');
   exit();
  }

  if ($implc->bank_id == 62) {
   $pdf = new \TCPDF('P', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
   $pdf->SetPrintHeader(false);
   $pdf->SetPrintFooter(false);
   $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
   $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
   $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
   $pdf->SetMargins(10, 10, 10);
   $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
   $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
   $pdf->SetAutoPageBreak(TRUE, 10);
   $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
   $pdf->SetFont('helvetica', 'B', 12);
   $pdf->AddPage();
   $pdf->SetY(5);
   // $image_file ='images/logo/islami_bank_logo.png';
   // $pdf->Image($image_file, 10, 5, 20, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
   $pdf->SetY(20);
   $pdf->SetFont('helvetica', 'N', 9);
   $view = \View::make('Defult.Approval.ImportLcApprovalPdf', ['implc' => $implc, 'amount_pi' => $amount_pi]);
   $html_content = $view->render();
   $pdf = WriteHtml($html_content, true, false, true, false, '');
   $filename = storage_path() . '/ImportLcApprovalPdf.pdf';
   $pdf->output($filename, 'I');
   exit();
  }
 }
}
