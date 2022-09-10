<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Sms;

class PoFabricShortApprovalController extends Controller
{
 private $pofabric;
 private $user;
 private $supplier;
 private $company;
 private $implc;
 private $implcpo;
 private $budgetfabric;

 public function __construct(
  PoFabricRepository $pofabric,
  UserRepository $user,
  SupplierRepository $supplier,
  CompanyRepository $company,
  ImpLcRepository $implc,
  ImpLcPoRepository $implcpo,
  BudgetFabricRepository $budgetfabric

 ) {
  $this->pofabric = $pofabric;
  $this->user = $user;
  $this->supplier = $supplier;
  $this->company = $company;
  $this->implc = $implc;
  $this->implcpo = $implcpo;
  $this->budgetfabric = $budgetfabric;

  $this->middleware('auth');
  // $this->middleware('permission:approve.pofabricshorts',   ['only' => ['approved', 'index', 'reportData', 'reportDataApp', 'unapproved']]);
 }
 public function index()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  return Template::loadView('Approval.PoFabricShortApproval', ['company' => $company, 'supplier' => $supplier]);
 }
 public function reportData()
 {
  $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
  $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
  $poType = [1 => "Regular", 2 => "Short"];
  $rows = $this->pofabric
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'po_fabrics.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'po_fabrics.supplier_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'po_fabrics.currency_id');
   })
   ->leftJoin(\DB::raw("(
            select
            po_fabrics.id as po_fabric_id,
            sum(po_fabric_item_qties.qty) as po_qty
            from
            po_fabrics
            join po_fabric_items on po_fabric_items.po_fabric_id=po_fabrics.id
            join po_fabric_item_qties on po_fabric_item_qties.po_fabric_item_id=po_fabric_items.id
            join budget_fabric_cons on budget_fabric_cons.id =po_fabric_item_qties.budget_fabric_con_id
            where po_fabric_item_qties.deleted_at is null
            group by po_fabrics.id
        ) pofabricQties"), "pofabricQties.po_fabric_id", "=", "po_fabrics.id")
   ->leftJoin(\DB::raw("(
            select
            po_fabrics.id as po_fabric_id,
            sum(budget_fabric_cons.grey_fab) as grey_fab,
            sum(budget_fabric_cons.amount) as bom_amount
            from
            po_fabrics
            join po_fabric_items on po_fabric_items.po_fabric_id=po_fabrics.id
            join po_fabric_item_qties on po_fabric_item_qties.po_fabric_item_id=po_fabric_items.id
            join budget_fabric_cons on budget_fabric_cons.id =po_fabric_item_qties.budget_fabric_con_id
            where po_fabric_item_qties.deleted_at is null
            group by po_fabrics.id
        ) budget"), "budget.po_fabric_id", "=", "po_fabrics.id")
   ->when(request('company_id'), function ($q) {
    return $q->where('po_fabrics.company_id', '=', request('company_id', 0));
   })
   ->when(request('supplier_id'), function ($q) {
    return $q->where('po_fabrics.supplier_id', '=', request('supplier_id', 0));
   })
   ->when(request('date_from'), function ($q) {
    return $q->where('po_fabrics.po_date', '>=', request('date_from', 0));
   })
   ->when(request('date_to'), function ($q) {
    return $q->where('po_fabrics.po_date', '<=', request('date_to', 0));
   })
   ->whereNull('po_fabrics.approved_at')
   ->where([['po_fabrics.po_type_id', '=', 2]])
   ->orderBy('po_fabrics.id', 'desc')
   ->get([
    'po_fabrics.*',
    'companies.name as company_code',
    'suppliers.name as supplier_code',
    'currencies.code as currency_code',
    'pofabricQties.po_qty',
    'budget.grey_fab',
    'budget.bom_amount'
   ])
   ->map(function ($rows) use ($source, $paymode, $poType) {
    $rows->po_type = $poType[$rows->po_type_id];
    $rows->source = $source[$rows->source_id];
    $rows->paymode = $paymode[$rows->pay_mode];
    $budget_bal_qty = $rows->grey_fab - $rows->po_qty;
    $budget_bal_amount = $rows->bom_amount - $rows->amount;
    $rows->budget_bal_qty = number_format($budget_bal_qty);
    $rows->budget_bal_amount = number_format($budget_bal_amount);
    $rows->amount = number_format($rows->amount);
    $rows->po_qty = number_format($rows->po_qty);
    $rows->grey_fab = number_format($rows->grey_fab);
    $rows->bom_amount = number_format($rows->bom_amount);
    $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
    $rows->po_date = $rows->po_date ? date('d-M-Y', strtotime($rows->po_date)) : '--';
    $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
    return $rows;
   });
  echo json_encode($rows);
 }

 public function approved(Request $request)
 {
  $id = request('id', 0);
  $master = $this->pofabric->find($id);
  $user = \Auth::user();
  $approved_at = date('Y-m-d h:i:s');

  $master->approved_by = $user->id;
  $master->approved_at = $approved_at;
  $master->unapproved_by = NULL;
  $master->unapproved_at = NULL;
  $master->timestamps = false;
  $pofabric = $master->save();


  if ($pofabric) {
   return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
  }
 }

 public function reportDataApp()
 {
  $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
  $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
  $rows = $this->pofabric
   ->selectRaw('
            po_fabrics.id,
            po_fabrics.po_no,
            po_fabrics.po_date,
            po_fabrics.pi_no,
            po_fabrics.pi_date,
            po_fabrics.source_id,
            po_fabrics.pay_mode,
            po_fabrics.amount,
            companies.code as company_code,
            suppliers.name as supplier_code,
            currencies.code as currency_code,
            implc.lc_date,
            implc.lc_no_i,
            implc.lc_no_ii,
            implc.lc_no_iii,
            implc.lc_no_iv,
            pofabricQties.po_qty,
            budget.grey_fab,
            budget.bom_amount,   
            fabric_rcv.rcv_qty
        ')
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'po_fabrics.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'po_fabrics.supplier_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'po_fabrics.currency_id');
   })
   ->leftJoin('imp_lc_pos', function ($join) {
    $join->on('imp_lc_pos.purchase_order_id', '=', 'po_fabrics.id');
   })
   ->leftJoin('imp_lcs', function ($join) {
    $join->on('imp_lc_pos.imp_lc_id', '=', 'imp_lcs.id')
     ->where([['imp_lcs.menu_id', '=', 1]]);
   })
   ->leftJoin(\DB::raw("(
            select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_date,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            from  imp_lcs 
            join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id 
            join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id
            where imp_lcs.menu_id=1 
        ) implc"), "po_fabrics.id", "=", "implc.purchase_order_id")
   ->leftJoin(\DB::raw("(
            select
            po_fabrics.id as po_fabric_id,
            sum(po_fabric_item_qties.qty) as po_qty
            from
            po_fabrics
            join po_fabric_items on po_fabric_items.po_fabric_id=po_fabrics.id
            join po_fabric_item_qties on po_fabric_item_qties.po_fabric_item_id=po_fabric_items.id
            join budget_fabric_cons on budget_fabric_cons.id =po_fabric_item_qties.budget_fabric_con_id
            where po_fabric_item_qties.deleted_at is null
            group by po_fabrics.id
        ) pofabricQties"), "pofabricQties.po_fabric_id", "=", "po_fabrics.id")
   ->leftJoin(\DB::raw("(
            select
            po_fabrics.id as po_fabric_id,
            sum(budget_fabric_cons.grey_fab) as grey_fab,
            sum(budget_fabric_cons.amount) as bom_amount
            from
            po_fabrics
            join po_fabric_items on po_fabric_items.po_fabric_id=po_fabrics.id
            join po_fabric_item_qties on po_fabric_item_qties.po_fabric_item_id=po_fabric_items.id
            join budget_fabric_cons on budget_fabric_cons.id =po_fabric_item_qties.budget_fabric_con_id
            where po_fabric_item_qties.deleted_at is null
            group by po_fabrics.id
        ) budget"), "budget.po_fabric_id", "=", "po_fabrics.id")
   ->leftJoin(\DB::raw("(
            select 
            po_fabric_items.po_fabric_id,
            sum(inv_finish_fab_rcv_items.qty) as rcv_qty--,
            --sum(inv_finish_fab_rcv_items.amount) as rcv_amount,
            --sum(inv_finish_fab_transactions.store_qty) as store_qty,
            --sum(inv_finish_fab_transactions.store_amount) as rcv_amount_tk
            from inv_finish_fab_rcv_fabrics
            join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_fabric_id=inv_finish_fab_rcv_fabrics.id
            join inv_finish_fab_transactions on inv_finish_fab_transactions.inv_finish_fab_rcv_item_id=inv_finish_fab_rcv_items.id
            join po_fabric_items on po_fabric_items.id=inv_finish_fab_rcv_fabrics.po_fabric_item_id
            where inv_finish_fab_transactions.trans_type_id=1
            group by 
            po_fabric_items.po_fabric_id
          ) fabric_rcv"), "fabric_rcv.po_fabric_id", "=", "po_fabrics.id")
   ->when(request('company_id'), function ($q) {
    return $q->where('po_fabrics.company_id', '=', request('company_id', 0));
   })
   ->when(request('supplier_id'), function ($q) {
    return $q->where('po_fabrics.supplier_id', '=', request('supplier_id', 0));
   })
   ->when(request('date_from'), function ($q) {
    return $q->where('po_fabrics.po_date', '>=', request('date_from', 0));
   })
   ->when(request('date_to'), function ($q) {
    return $q->where('po_fabrics.po_date', '<=', request('date_to', 0));
   })
   ->whereNotNull('po_fabrics.approved_at')
   ->where([['po_fabrics.po_type_id', '=', 2]])
   ->orderBy('po_fabrics.id', 'desc')
   ->groupBy([
    'po_fabrics.id',
    'po_fabrics.po_no',
    'po_fabrics.po_date',
    'po_fabrics.pi_no',
    'po_fabrics.pi_date',
    'po_fabrics.source_id',
    'po_fabrics.pay_mode',
    'po_fabrics.amount',
    'companies.code',
    'suppliers.name',
    'currencies.code',
    'implc.lc_date',
    'implc.lc_no_i',
    'implc.lc_no_ii',
    'implc.lc_no_iii',
    'implc.lc_no_iv',
    'pofabricQties.po_qty',
    'budget.grey_fab',
    'budget.bom_amount',
    'fabric_rcv.rcv_qty'
   ])
   ->get()
   ->map(function ($rows) use ($source, $paymode) {
    $rows->source = $source[$rows->source_id];
    $rows->paymode = $paymode[$rows->pay_mode];
    $budget_bal_qty = $rows->grey_fab - $rows->po_qty;
    $budget_bal_amount = $rows->bom_amount - $rows->amount;
    $rows->budget_bal_qty = number_format($budget_bal_qty);
    $rows->budget_bal_amount = number_format($budget_bal_amount);
    $rows->amount = number_format($rows->amount);
    $rows->po_qty = number_format($rows->po_qty);
    $rows->grey_fab = number_format($rows->grey_fab);
    $rows->bom_amount = number_format($rows->bom_amount);
    $rows->rcv_qty = number_format($rows->rcv_qty);
    $rows->delv_start_date = $rows->delv_start_date ? date('d-M-Y', strtotime($rows->delv_start_date)) : '--';
    $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
    $rows->delv_end_date = $rows->delv_end_date ? date('d-M-Y', strtotime($rows->delv_end_date)) : '--';
    $rows->lc_date = $rows->lc_date ? date('d-M-Y', strtotime($rows->lc_date)) : '--';
    $rows->lc_no = $rows->lc_no_i . " " . $rows->lc_no_ii . " " . $rows->lc_no_iii . " " . $rows->lc_no_iv . " LC Dt:" . $rows->lc_date . "";
    return $rows;
   });
  echo json_encode($rows);
 }

 public function unapproved(Request $request)
 {
  $id = request('id', 0);
  $master = $this->pofabric->find($id);
  $user = \Auth::user();
  $unapproved_at = date('Y-m-d h:i:s');
  $unapproved_count = $master->unapproved_count + 1;
  $master->approved_by = NUll;
  $master->approved_at = NUll;
  $master->unapproved_by = $user->id;
  $master->unapproved_at = $unapproved_at;
  $master->unapproved_count = $unapproved_count;
  $master->timestamps = false;

  $implcpo = $this->implcpo
   ->join('imp_lcs', function ($join) {
    $join->on('imp_lcs.id', '=', 'imp_lc_pos.imp_lc_id');
   })
   ->where([['imp_lcs.menu_id', '=', 1]])
   ->where([['imp_lc_pos.purchase_order_id', '=', $id]])
   ->get(['imp_lc_pos.purchase_order_id'])
   ->first();
  if ($implcpo) {
   return response()->json(array('success' => false,  'message' => 'LC Found. Untag PO from LC first'), 200);
  }

  $pofabric = $master->save();


  if ($pofabric) {
   return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
  }
 }

 public function getFabricSummery()
 {
  $id = request('id', 0);

  $rows = $this->pofabric
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'po_fabrics.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'po_fabrics.supplier_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'po_fabrics.currency_id');
   })
   ->where([['po_fabrics.id', '=', $id]])
   ->get([
    'po_fabrics.*',
    'companies.name as company_name',
    'suppliers.name as supplier_name',
    'currencies.code as currency_code'
   ])
   ->first();

  $materialsourcing = array_prepend(config('bprs.materialsourcing'), '-Select-', '');
  $fabricnature = array_prepend(config('bprs.fabricnature'), '-Select-', '');
  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
  $fabricDescription = $this->budgetfabric
   ->join('style_fabrications', function ($join) {
    $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('budgets', function ($join) {
    $join->on('budgets.id', '=', 'budget_fabrics.budget_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'budgets.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
   })
   ->join('autoyarns', function ($join) {
    $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
   })

   ->join('autoyarnratios', function ($join) {
    $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
   })
   ->join('compositions', function ($join) {
    $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
   })
   ->join('constructions', function ($join) {
    $join->on('constructions.id', '=', 'autoyarns.construction_id');
   })
   ->join('po_fabric_items', function ($join) {
    $join->on('po_fabric_items.budget_fabric_id', '=', 'budget_fabrics.id')
     ->whereNull('po_fabric_items.deleted_at');
   })
   ->join('po_fabrics', function ($join) {
    $join->on('po_fabrics.id', '=', 'po_fabric_items.po_fabric_id');
   })
   ->where([['po_fabrics.id', '=', $id]])
   ->get([
    'style_fabrications.id',
    'constructions.name as construction',
    'autoyarnratios.composition_id',
    'compositions.name',
    'autoyarnratios.ratio',
   ]);
  $fabricDescriptionArr = array();
  $fabricCompositionArr = array();
  foreach ($fabricDescription as $row) {
   $fabricDescriptionArr[$row->id] = $row->construction;
   $fabricCompositionArr[$row->id][] = $row->name . " " . $row->ratio . "%";
  }

  $desDropdown = array();
  foreach ($fabricDescriptionArr as $key => $val) {
   $desDropdown[$key] = $val . " " . implode(", ", $fabricCompositionArr[$key]);
  }

  $details = collect(
   \DB::Select("
            select
            m.style_ref,
            m.style_id,
            m.company_id,
            m.bnf_company,
            m.fabric_look_id,
            m.style_fabrication_id,
            m.gsm_weight,
            m.dia,
            m.fabric_color_name,
            m.gmtspart_name,
            m.uom_code,
            sum(m.bom_qty) as bom_qty,
            avg(m.bom_rate) as bom_rate,
            sum(m.bom_amount) as bom_amount,
            sum(m.po_qty) as po_qty,
            avg(m.po_rate) as po_rate,
            sum(m.po_amount) as po_amount,
            sum(m.total_po_qty) as total_po_qty,
            avg(m.total_po_rate) as total_po_rate,
            sum(m.total_po_amount) as total_po_amount
            from(
            select 
            styles.style_ref,
            styles.id as style_id,
            companies.id as company_id,
            companies.code as bnf_company,
            style_fabrications.fabric_look_id,
            budget_fabrics.style_fabrication_id,
            budget_fabrics.gsm_weight,
            budget_fabric_cons.id as budget_fabric_con_id,
            budget_fabric_cons.dia,
            fabric_colors.name as fabric_color_name,
            gmtsparts.name as gmtspart_name,
            uoms.code as uom_code,
            budget_fabric_cons.grey_fab as bom_qty,
            budget_fabric_cons.rate as bom_rate,
            budget_fabric_cons.amount as bom_amount,
            sum(po_fabric_item_qties.qty) as po_qty,
            avg(po_fabric_item_qties.rate) as po_rate,
            sum(po_fabric_item_qties.amount) as po_amount,
            sum(cumulative.qty) as total_po_qty,
            avg(cumulative.rate) as total_po_rate,
            sum(cumulative.amount) as total_po_amount
            from po_fabrics 
            join po_fabric_items on po_fabrics.id = po_fabric_items.po_fabric_id
            join po_fabric_item_qties on po_fabric_item_qties.po_fabric_item_id=po_fabric_items.id
            join budget_fabric_cons on budget_fabric_cons.id=po_fabric_item_qties.budget_fabric_con_id
            join budget_fabrics on budget_fabric_cons.budget_fabric_id = budget_fabrics.id 
            and po_fabric_items.budget_fabric_id = budget_fabrics.id 
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            join gmtsparts on gmtsparts.id = style_fabrications.gmtspart_id
            join budgets on budgets.id = budget_fabrics.budget_id 
            join jobs on jobs.id = budgets.job_id 
            join companies on companies.id = jobs.company_id 
            join styles on styles.id = jobs.style_id 
            join buyers on buyers.id = styles.buyer_id 
            join uoms on uoms.id = style_fabrications.uom_id 
            join colors fabric_colors on fabric_colors.id = budget_fabric_cons.fabric_color
            left join (
            select 
            po_fabric_item_qties.budget_fabric_con_id,
            sum(po_fabric_item_qties.qty) as qty,
            avg(po_fabric_item_qties.rate) as rate,
            sum(po_fabric_item_qties.amount) as amount
            from po_fabric_item_qties 
            join budget_fabric_cons on budget_fabric_cons.id=po_fabric_item_qties.budget_fabric_con_id
            group by 
            po_fabric_item_qties.budget_fabric_con_id
            ) cumulative on cumulative.budget_fabric_con_id = budget_fabric_cons.id 
            where (po_fabrics.id = ?) 
            and po_fabrics.deleted_at is null
            group by
            styles.style_ref,
            styles.id,
            companies.id,
            companies.code,
            style_fabrications.fabric_look_id,
            budget_fabrics.style_fabrication_id,
            budget_fabrics.gsm_weight,
            budget_fabric_cons.id,
            budget_fabric_cons.dia,
            fabric_colors.name,
            gmtsparts.name,
            uoms.code,
            budget_fabric_cons.grey_fab,
            budget_fabric_cons.rate,
            budget_fabric_cons.amount 
            )m
            group by
            m.style_ref,
            m.style_id,
            m.company_id,
            m.bnf_company,
            m.fabric_look_id,
            m.style_fabrication_id,
            m.gsm_weight,
            m.dia,
            m.fabric_color_name,
            m.gmtspart_name,
            m.uom_code
        ", [$id])
  )
   ->map(function ($details) use ($desDropdown, $fabriclooks) {
    $details->fabric_description =  $desDropdown[$details->style_fabrication_id] . ", " . $details->gsm_weight . ", " . $details->dia;
    $details->fabriclooks = $fabriclooks[$details->fabric_look_id];
    $details->qty_bal = $details->bom_qty - $details->total_po_qty;
    $details->rate_bal = $details->bom_rate - $details->po_rate;
    $details->amount_bal = $details->bom_amount - $details->total_po_amount;
    return $details;
   });

  //  dd($details);die;

  $shorttype = array_prepend(config('bprs.shorttype'), '-Select-', '');

  $responsible = $this->pofabric
   ->join('po_fabric_items', function ($join) {
    $join->on('po_fabrics.id', '=', 'po_fabric_items.po_fabric_id');
   })
   ->join('po_fabric_item_resps', function ($join) {
    $join->on('po_fabric_items.id', '=', 'po_fabric_item_resps.po_fabric_item_id');
   })
   ->join('sections', function ($join) {
    $join->on('sections.id', '=', 'po_fabric_item_resps.section_id');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('employee_h_rs.id', '=', 'po_fabric_item_resps.employee_h_r_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'employee_h_rs.company_id');
   })
   ->where([['po_fabrics.id', '=', $id]])
   ->orderBy('po_fabric_item_resps.id', 'desc')
   ->get([
    'po_fabric_item_resps.*',
    'employee_h_rs.name as employee_name',
    'sections.name as section_name',
    'companies.name as company_name'
   ])
   ->map(function ($responsible) use ($shorttype) {
    $responsible->short_type = $shorttype[$responsible->short_type_id];
    return $responsible;
   });

  $data['master'] = $rows;
  $data['details'] = $details;
  $data['responsible'] = $responsible;

  return Template::loadView('Approval.PoFabricApprovalSummeryMatrix', ['data' => $data]);
 }

 public function poDetails()
 {
  $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
  $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');

  $rows = $this->pofabric
   ->selectRaw('
            styles.style_ref,
            companies.code as company_code,
            budget_fabrics.style_fabrication_id,
            po_fabrics.id as po_fabric_id,
            po_fabrics.po_no,
            po_fabrics.po_date,
            po_fabrics.pi_no,
            po_fabrics.pi_date,
            po_fabrics.source_id,
            po_fabrics.pay_mode,
            po_fabrics.delv_start_date,
            po_fabrics.delv_end_date,
            po_fabrics.exch_rate,
           
            po_fabric_items.id as po_fabric_item_id,
            suppliers.name as supplier_name,
            currencies.code as currency_code,
            sum(po_fabric_item_qties.qty) as po_qty,
            avg(po_fabric_item_qties.rate) as po_rate,
            sum(po_fabric_item_qties.amount) as po_amount
        ')
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'po_fabrics.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'po_fabrics.supplier_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'po_fabrics.currency_id');
   })
   ->join('po_fabric_items', function ($join) {
    $join->on('po_fabrics.id', '=', 'po_fabric_items.po_fabric_id');
   })
   ->join('budget_fabrics', function ($join) {
    $join->on('po_fabric_items.budget_fabric_id', '=', 'budget_fabrics.id')
     ->whereNull('po_fabric_items.deleted_at');
   })
   ->join('style_fabrications', function ($join) {
    $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->join('budgets', function ($join) {
    $join->on('budgets.id', '=', 'budget_fabrics.budget_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'budgets.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
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
   ->join('po_fabric_item_qties', function ($join) {
    $join->on('po_fabric_item_qties.po_fabric_item_id', '=', 'po_fabric_items.id')
     ->whereNull('po_fabric_item_qties.deleted_at');
   })
   // ->join('budget_fabric_cons',function($join){
   //     $join->on('po_fabric_item_qties.budget_fabric_con_id','=','budget_fabric_cons.id')
   //     ->whereNull('budget_fabric_cons.deleted_at');
   // })
   ->where([['jobs.company_id', '=', request('company_id', 0)]])
   ->where([['budget_fabrics.style_fabrication_id', '=', request('style_fabrication_id', 0)]])
   ->where([['styles.id', '=', request('style_id', 0)]])
   ->groupBy([
    'styles.style_ref',
    'companies.code',
    'budget_fabrics.style_fabrication_id',
    'po_fabrics.id',
    'po_fabrics.po_no',
    'po_fabrics.po_date',
    'po_fabrics.pi_no',
    'po_fabrics.pi_date',
    'po_fabrics.source_id',
    'po_fabrics.pay_mode',
    'po_fabrics.delv_start_date',
    'po_fabrics.delv_end_date',
    'po_fabrics.exch_rate',
    'po_fabric_items.id',
    'suppliers.name',
    'currencies.code',

   ])
   ->get()
   ->map(function ($rows) use ($source, $paymode) {
    $rows->source = $source[$rows->source_id];
    $rows->paymode = $paymode[$rows->pay_mode];
    $rows->delv_start_date = $rows->delv_start_date ? date('d-M-Y', strtotime($rows->delv_start_date)) : '';
    $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
    $rows->delv_end_date = $rows->delv_end_date ? date('d-M-Y', strtotime($rows->delv_end_date)) : '';
    return $rows;
   });

  echo json_encode($rows);
 }

 public function getRcvNo()
 {
  $po_fabric_id = request('id', 0);
  $fabricrcv = $this->pofabric
   ->join('po_fabric_items', function ($join) {
    $join->on('po_fabric_items.po_fabric_id', '=', 'po_fabrics.id');
   })
   ->join('inv_finish_fab_rcv_fabrics', function ($join) {
    $join->on('inv_finish_fab_rcv_fabrics.po_fabric_item_id', '=', 'po_fabric_items.id');
   })
   ->join('inv_finish_fab_rcv_items', function ($join) {
    $join->on('inv_finish_fab_rcv_fabrics.id', '=', 'inv_finish_fab_rcv_items.inv_finish_fab_rcv_fabric_id');
   })
   ->join('inv_finish_fab_rcvs', function ($join) {
    $join->on('inv_finish_fab_rcv_fabrics.inv_finish_fab_rcv_id', '=', 'inv_finish_fab_rcvs.id');
   })
   ->join('inv_rcvs', function ($join) {
    $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
   })
   ->where([['po_fabric_items.po_fabric_id', '=', $po_fabric_id]])
   ->get([
    'po_fabric_items.id as po_item_id',
    'inv_rcvs.receive_no',
    'inv_rcvs.receive_date',
    'inv_rcvs.challan_no',
    'inv_rcvs.remarks',
    'inv_finish_fab_rcv_items.id as inv_rcv_item_id',
    'inv_finish_fab_rcv_items.qty',
    'inv_finish_fab_rcv_items.rate',
    'inv_finish_fab_rcv_items.amount',
    'inv_finish_fab_rcv_items.store_qty',
    'inv_finish_fab_rcv_items.store_amount',
   ])
   ->map(function ($fabricrcv) {
    $fabricrcv->qty = number_format($fabricrcv->qty, 2);
    $fabricrcv->rate = number_format($fabricrcv->rate, 4);
    $fabricrcv->amount = number_format($fabricrcv->amount, 2);
    $fabricrcv->store_qty = number_format($fabricrcv->store_qty, 2);
    $fabricrcv->store_amount = number_format($fabricrcv->store_amount, 2);
    return $fabricrcv;
   });
  echo json_encode($fabricrcv);
 }
}
