<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Purchase\PoAopServiceRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Sms;

class PoAopServiceApprovalController extends Controller
{
 private $poaopservice;
 private $user;
 private $supplier;
 private $company;
 private $implc;
 private $implcpo;

 public function __construct(
  PoAopServiceRepository $poaopservice,
  UserRepository $user,
  SupplierRepository $supplier,
  CompanyRepository $company,
  ImpLcRepository $implc,
  ImpLcPoRepository $implcpo

 ) {
  $this->poaopservice = $poaopservice;
  $this->user = $user;
  $this->supplier = $supplier;
  $this->company = $company;
  $this->implc = $implc;
  $this->implcpo = $implcpo;

  $this->middleware('auth');
  $this->middleware('permission:approve.poaopservices',   ['only' => ['approved', 'index', 'reportData', 'reportDataApp', 'unapproved']]);
 }
 public function index()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  return Template::loadView('Approval.PoAopServiceApproval', ['company' => $company, 'supplier' => $supplier]);
 }
 public function reportData()
 {
  $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
  $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
  $rows = $this->poaopservice
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'po_aop_services.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'po_aop_services.supplier_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'po_aop_services.currency_id');
   })
   ->when(request('company_id'), function ($q) {
    return $q->where('po_aop_services.company_id', '=', request('company_id', 0));
   })
   ->when(request('supplier_id'), function ($q) {
    return $q->where('po_aop_services.supplier_id', '=', request('supplier_id', 0));
   })
   ->when(request('date_from'), function ($q) {
    return $q->where('po_aop_services.po_date', '>=', request('date_from', 0));
   })
   ->when(request('date_to'), function ($q) {
    return $q->where('po_aop_services.po_date', '<=', request('date_to', 0));
   })
   ->whereNull('po_aop_services.approved_at')
   ->where([['po_aop_services.po_type_id', '=', 1]])
   ->orderBy('po_aop_services.id', 'desc')
   ->get([
    'po_aop_services.*',
    'companies.name as company_code',
    'suppliers.name as supplier_code',
    'currencies.code as currency_code',
   ])
   ->map(function ($rows) use ($source, $paymode) {
    $rows->source = $source[$rows->source_id];
    $rows->paymode = $paymode[$rows->pay_mode];
    $rows->amount = number_format($rows->amount);
    $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
    $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
    $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
    return $rows;
   });
  echo json_encode($rows);
 }

 public function approved(Request $request)
 {
  $id = request('id', 0);
  $master = $this->poaopservice->find($id);
  $user = \Auth::user();
  $approved_at = date('Y-m-d h:i:s');

  $master->approved_by = $user->id;
  $master->approved_at = $approved_at;
  $master->unapproved_by = NULL;
  $master->unapproved_at = NULL;
  $master->timestamps = false;
  $poaopservice = $master->save();


  if ($poaopservice) {
   return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
  }
 }

 public function reportDataApp()
 {
  $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
  $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
  $rows = $this->poaopservice
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'po_aop_services.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'po_aop_services.supplier_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'po_aop_services.currency_id');
   })
   ->when(request('company_id'), function ($q) {
    return $q->where('po_aop_services.company_id', '=', request('company_id', 0));
   })
   ->when(request('supplier_id'), function ($q) {
    return $q->where('po_aop_services.supplier_id', '=', request('supplier_id', 0));
   })
   ->when(request('date_from'), function ($q) {
    return $q->where('po_aop_services.po_date', '>=', request('date_from', 0));
   })
   ->when(request('date_to'), function ($q) {
    return $q->where('po_aop_services.po_date', '<=', request('date_to', 0));
   })
   ->whereNotNull('po_aop_services.approved_at')
   ->where([['po_aop_services.po_type_id', '=', 1]])
   ->orderBy('po_aop_services.id', 'desc')
   ->get([
    'po_aop_services.*',
    'companies.name as company_code',
    'suppliers.name as supplier_code',
    'currencies.code as currency_code',
   ])
   ->map(function ($rows) use ($source, $paymode) {
    $rows->source = $source[$rows->source_id];
    $rows->paymode = $paymode[$rows->pay_mode];
    $rows->amount = number_format($rows->amount);
    $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
    $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
    $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
    return $rows;
   });
  echo json_encode($rows);
 }

 public function unapproved(Request $request)
 {
  $id = request('id', 0);
  $master = $this->poaopservice->find($id);
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
   ->where([['imp_lcs.menu_id', '=', 5]])
   ->where([['imp_lc_pos.purchase_order_id', '=', $id]])
   ->get(['imp_lc_pos.purchase_order_id'])
   ->first();
  if ($implcpo) {
   return response()->json(array('success' => false,  'message' => 'LC Found. Untag PO from LC first'), 200);
  }


  $poaopservice = $master->save();


  if ($poaopservice) {
   return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
  }
 }

 public function getAopServiceSummery()
 {
  $id = request('id', 0);

  $rows = $this->poaopservice
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'po_aop_services.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'po_aop_services.supplier_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'po_aop_services.currency_id');
   })
   ->where([['po_aop_services.id', '=', $id]])
   ->get([
    'po_aop_services.*',
    'companies.name as company_name',
    'suppliers.name as supplier_name',
    'currencies.code as currency_code'
   ])
   ->first();

  $dyetype = array_prepend(config('bprs.dyetype'), '-Select-', '');
  $materialsourcing = array_prepend(config('bprs.materialsourcing'), '-Select-', '');
  $fabricnature = array_prepend(config('bprs.fabricnature'), '-Select-', '');
  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');

  $fabricDescription = $this->poaopservice
   ->leftJoin('po_aop_service_items', function ($join) {
    $join->on('po_aop_service_items.po_aop_service_id', '=', 'po_aop_services.id');
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
   ->leftJoin('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_fabrications.style_gmt_id');
   })
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->leftJoin('budgets', function ($join) {
    $join->on('budgets.id', '=', 'budget_fabrics.budget_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'budgets.job_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('gmtsparts', function ($join) {
    $join->on('gmtsparts.id', '=', 'style_fabrications.gmtspart_id');
   })
   ->leftJoin('autoyarns', function ($join) {
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
   ->where([['po_aop_services.id', '=', $id]])
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
   $desDropdown[$key] = $val . ", " . implode(",", $fabricCompositionArr[$key]);
  }

  $details = collect(
   \DB::Select("
          select 
          styles.id as style_id,
          styles.style_ref,
          companies.id as company_id,
          companies.code as bnf_company,
          budget_fabrics.style_fabrication_id,
          budget_fabrics.gsm_weight,
          style_fabrications.fabric_look_id,
          style_fabrications.dyeing_type_id,
          gmtsparts.name as gmtspart_name,
          uoms.code as uom_code,
          po_aop_service_items.budget_fabric_prod_id,
          budget_fabric_prods.cons as bom_qty,
          budget_fabric_prods.rate as bom_rate,
          budget_fabric_prods.amount as bom_amount,
          po_aop_service_items.qty as po_qty,
          po_aop_service_items.rate as po_rate,
          po_aop_service_items.amount as po_amount,
          cumulative.qty as total_po_qty,
          cumulative.rate as total_po_rate,
          cumulative.amount as total_po_amount
          from po_aop_services 
          join po_aop_service_items on po_aop_service_items.po_aop_service_id = po_aop_services.id 
          join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id 
          left join (
              select 
              po_aop_service_items.budget_fabric_prod_id,
              sum(po_aop_service_items.qty) as qty,
              avg(po_aop_service_items.rate) as rate,
              sum(po_aop_service_items.amount) as amount
              from po_aop_service_items 
              join budget_fabric_prods on budget_fabric_prods.id=po_aop_service_items.budget_fabric_prod_id
              group by 
              po_aop_service_items.budget_fabric_prod_id
          ) cumulative on cumulative.budget_fabric_prod_id = budget_fabric_prods.id 
          join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id 
          join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
          join budgets on budgets.id=budget_fabrics.budget_id
          join gmtsparts on gmtsparts.id = style_fabrications.gmtspart_id 
          join autoyarns on autoyarns.id = style_fabrications.autoyarn_id 
          left join uoms on uoms.id=style_fabrications.uom_id
          join jobs on jobs.id = budgets.job_id
          join companies on companies.id = jobs.company_id 
          join styles on styles.id = jobs.style_id 
          where (po_aop_services.id =  ? ) --27466
          and po_aop_services.deleted_at is null
        ", [$id])
  )
   ->map(function ($details) use ($desDropdown, $fabriclooks, $dyetype) {
    $details->dyeing_type = $dyetype[$details->dyeing_type_id];
    $details->fabric_description =  $desDropdown[$details->style_fabrication_id] . ", " . $details->gsm_weight;
    $details->fabriclooks = $fabriclooks[$details->fabric_look_id];
    $details->qty_bal = $details->bom_qty - $details->total_po_qty;
    $details->rate_bal = $details->bom_rate - $details->po_rate;
    $details->amount_bal = $details->bom_amount - $details->total_po_amount;
    return $details;
   });

  //  dd($details);die;

  $shorttype = array_prepend(config('bprs.shorttype'), '-Select-', '');

  $responsible = $this->poaopservice
   ->join('po_aop_service_items', function ($join) {
    $join->on('po_aop_services.id', '=', 'po_aop_service_items.po_aop_service_id');
   })
   ->join('po_aop_service_item_resps', function ($join) {
    $join->on('po_aop_service_items.id', '=', 'po_aop_service_item_resps.po_aop_service_item_id');
   })
   ->join('sections', function ($join) {
    $join->on('sections.id', '=', 'po_aop_service_item_resps.section_id');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('employee_h_rs.id', '=', 'po_aop_service_item_resps.employee_h_r_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'employee_h_rs.company_id');
   })
   ->where([['po_aop_services.id', '=', $id]])
   ->orderBy('po_aop_service_item_resps.id', 'desc')
   ->get([
    'po_aop_service_item_resps.*',
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

  return Template::loadView('Approval.PoAopServiceApprovalSummeryMatrix', ['data' => $data]);
 }

 public function poDetails()
 {
  $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
  $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');

  $rows = $this->poaopservice
   ->selectRaw('
            styles.style_ref,
            companies.code as company_code,
            budget_fabrics.style_fabrication_id,
            po_aop_services.id as po_aop_service_id,
            po_aop_services.po_no,
            po_aop_services.po_date,
            po_aop_services.pi_no,
            po_aop_services.pi_date,
            po_aop_services.source_id,
            po_aop_services.pay_mode,
            po_aop_services.delv_start_date,
            po_aop_services.delv_end_date,
            po_aop_services.exch_rate,
           
            po_aop_service_items.id as po_aop_service_item_id,
            suppliers.name as supplier_name,
            currencies.code as currency_code,
            sum(po_aop_service_items.qty) as po_qty,
            avg(po_aop_service_items.rate) as po_rate,
            sum(po_aop_service_items.amount) as po_amount
        ')
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'po_aop_services.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'po_aop_services.supplier_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'po_aop_services.currency_id');
   })
   ->join('po_aop_service_items', function ($join) {
    $join->on('po_aop_services.id', '=', 'po_aop_service_items.po_aop_service_id');
   })
   ->join('budget_fabric_prods', function ($join) {
    $join->on('po_aop_service_items.budget_fabric_prod_id', '=', 'budget_fabric_prods.id');
   })
   ->join('budget_fabrics', function ($join) {
    $join->on('budget_fabric_prods.budget_fabric_id', '=', 'budget_fabrics.id');
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
   ->where([['jobs.company_id', '=', request('company_id', 0)]])
   ->where([['po_aop_service_items.budget_fabric_prod_id', '=', request('budget_fabric_prod_id', 0)]])
   ->where([['budget_fabrics.style_fabrication_id', '=', request('style_fabrication_id', 0)]])
   ->where([['styles.id', '=', request('style_id', 0)]])
   ->groupBy([
    'styles.style_ref',
    'companies.code',
    'budget_fabrics.style_fabrication_id',
    'po_aop_services.id',
    'po_aop_services.po_no',
    'po_aop_services.po_date',
    'po_aop_services.pi_no',
    'po_aop_services.pi_date',
    'po_aop_services.source_id',
    'po_aop_services.pay_mode',
    'po_aop_services.delv_start_date',
    'po_aop_services.delv_end_date',
    'po_aop_services.exch_rate',
    'po_aop_service_items.id',
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
}
