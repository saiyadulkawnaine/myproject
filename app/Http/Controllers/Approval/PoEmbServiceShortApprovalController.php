<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Sms;
use Illuminate\Support\Carbon;
use App\Library\Numbertowords;

class PoEmbServiceShortApprovalController extends Controller
{
  private $poembservice;
  private $company;
  private $supplier;
  private $currency;
  private $termscondition;
  private $purchasetermscondition;
  private $itemaccount;
  private $implc;
  private $implcpo;

  public function __construct(
    PoEmbServiceRepository $poembservice,
    CompanyRepository $company,
    SupplierRepository $supplier,
    CurrencyRepository $currency,
    TermsConditionRepository $termscondition,
    PurchaseTermsConditionRepository $purchasetermscondition,
    ItemAccountRepository $itemaccount,
    ImpLcRepository $implc,
    ImpLcPoRepository $implcpo

  ) {
    $this->poembservice = $poembservice;
    $this->company = $company;
    $this->supplier = $supplier;
    $this->currency = $currency;
    $this->termscondition = $termscondition;
    $this->purchasetermscondition = $purchasetermscondition;
    $this->itemaccount = $itemaccount;
    $this->implc = $implc;
    $this->implcpo = $implcpo;

    $this->middleware('auth');

    // $this->middleware('permission:approve.poembserviceshorts',   ['only' => ['approved', 'index', 'reportData', 'reportDataApp', 'unapproved']]);
  }

  public function index()
  {
    $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
    // $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
    $supplier = array_prepend(array_pluck($this->supplier->where([['status_id', '=', 1]])->get(), 'name', 'id'), '-Select-', '');
    $menu = array_prepend(config('bprs.menu'), '-Select-', '');
    return Template::loadView('Approval.PoEmbServiceShortApproval', ['company' => $company, 'supplier' => $supplier, 'menu' => $menu]);
  }

  public function reportData()
  {
    $paymode = config('bprs.paymode');
    $rows = $this->poembservice
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'po_emb_services.company_id');
      })
      ->join('suppliers', function ($join) {
        $join->on('suppliers.id', '=', 'po_emb_services.supplier_id');
      })
      ->join('currencies', function ($join) {
        $join->on('currencies.id', '=', 'po_emb_services.currency_id');
      })
      ->when(request('company_id'), function ($q) {
        return $q->where('po_emb_services.company_id', '=', request('company_id', 0));
      })
      ->when(request('supplier_id'), function ($q) {
        return $q->where('po_emb_services.supplier_id', '=', request('supplier_id', 0));
      })
      ->when(request('date_from'), function ($q) {
        return $q->where('po_emb_services.po_date', '>=', request('date_from', 0));
      })
      ->when(request('date_to'), function ($q) {
        return $q->where('po_emb_services.po_date', '<=', request('date_to', 0));
      })
      ->whereNull('po_emb_services.approved_by')
      ->where([['po_emb_services.po_type_id', '=', 2]])
      ->orderBy('po_emb_services.id', 'desc')
      ->get([
        'po_emb_services.*',
        'companies.code as company_code',
        'suppliers.code as supplier_code',
        'currencies.code as currency_code',
      ])
      ->map(function ($rows) use ($paymode) {
        $rows->paymode = $paymode[$rows->pay_mode];
        $rows->amount = number_format($rows->amount, 2);
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
    $master = $this->poembservice->find($id);
    $user = \Auth::user();
    $approved_at = date('Y-m-d h:i:s');
    $master->approved_by = $user->id;
    $master->approved_at = $approved_at;
    $master->unapproved_by = NULL;
    $master->unapproved_at = NULL;
    $master->timestamps = false;
    $poembservice = $master->save();
    if ($poembservice) {
      return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
    }
  }

  public function reportDataApp()
  {
    $paymode = config('bprs.paymode');
    $rows = $this->poembservice
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'po_emb_services.company_id');
      })
      ->join('suppliers', function ($join) {
        $join->on('suppliers.id', '=', 'po_emb_services.supplier_id');
      })
      ->join('currencies', function ($join) {
        $join->on('currencies.id', '=', 'po_emb_services.currency_id');
      })
      ->when(request('company_id'), function ($q) {
        return $q->where('po_emb_services.company_id', '=', request('company_id', 0));
      })
      ->when(request('supplier_id'), function ($q) {
        return $q->where('po_emb_services.supplier_id', '=', request('supplier_id', 0));
      })
      ->when(request('date_from'), function ($q) {
        return $q->where('po_emb_services.po_date', '>=', request('date_from', 0));
      })
      ->when(request('date_to'), function ($q) {
        return $q->where('po_emb_services.po_date', '<=', request('date_to', 0));
      })
      ->whereNotNull('po_emb_services.approved_by')
      ->where([['po_emb_services.po_type_id', '=', 2]])
      ->orderBy('po_emb_services.id', 'desc')
      ->get([
        'po_emb_services.*',
        'companies.code as company_code',
        'suppliers.code as supplier_code',
        'currencies.code as currency_code',
      ])
      ->map(function ($rows) use ($paymode) {
        $rows->paymode = $paymode[$rows->pay_mode];
        $rows->amount = number_format($rows->amount, 2);
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
    $master = $this->poembservice->find($id);
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
      ->where([['imp_lcs.menu_id', '=', 10]])
      ->where([['imp_lc_pos.purchase_order_id', '=', $id]])
      ->get(['imp_lc_pos.purchase_order_id'])
      ->first();
    if ($implcpo) {
      return response()->json(array('success' => false,  'message' => 'LC Found. Untag PO from LC first'), 200);
    }


    $poembservice = $master->save();

    if ($poembservice) {
      return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
    }
  }

  public function getEmbServiceSummery()
  {
    $id = request('id', 0);

    $rows = $this->poembservice
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'po_emb_services.company_id');
      })
      ->join('suppliers', function ($join) {
        $join->on('suppliers.id', '=', 'po_emb_services.supplier_id');
      })
      ->join('currencies', function ($join) {
        $join->on('currencies.id', '=', 'po_emb_services.currency_id');
      })
      ->where([['po_emb_services.id', '=', $id]])
      ->get([
        'po_emb_services.*',
        'companies.name as company_name',
        'suppliers.name as supplier_name',
        'currencies.code as currency_code'
      ])
      ->first();

    $embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');

    $details = collect(
      \DB::Select("
      select 
      styles.id as style_id,
      styles.style_ref,
      companies.id as company_id,
      companies.code as bnf_company,
      budget_embs.style_embelishment_id,
      style_embelishments.embelishment_size_id,
      embelishments.name as embelishment_name,
      embelishment_types.name as embelishment_type,
      gmtsparts.name as gmtspart_name,
      item_accounts.item_description,
      po_emb_service_items.budget_emb_id,
      budget_embs.cons as bom_qty,
      budget_embs.rate as bom_rate,
      budget_embs.amount as bom_amount,
      po_emb_service_items.qty as po_qty,
      po_emb_service_items.rate as po_rate,
      po_emb_service_items.amount as po_amount,
      cumulative.qty as total_po_qty,
      cumulative.rate as total_po_rate,
      cumulative.amount as total_po_amount
      from po_emb_services 
      join po_emb_service_items on po_emb_service_items.po_emb_service_id = po_emb_services.id 
      join budget_embs on budget_embs.id = po_emb_service_items.budget_emb_id 
      left join (
        select 
        po_emb_service_items.budget_emb_id,
        sum(po_emb_service_items.qty) as qty,
        avg(po_emb_service_items.rate) as rate,
        sum(po_emb_service_items.amount) as amount
        from po_emb_service_items 
        join budget_embs on budget_embs.id=po_emb_service_items.budget_emb_id
        group by 
        po_emb_service_items.budget_emb_id
      ) cumulative on cumulative.budget_emb_id = budget_embs.id 
      join style_embelishments on style_embelishments.id = budget_embs.style_embelishment_id 
      join style_gmts on style_gmts.id = style_embelishments.style_gmt_id
      join item_accounts on item_accounts.id=style_gmts.item_account_id
      join budgets on budgets.id=budget_embs.budget_id
      join gmtsparts on gmtsparts.id = style_embelishments.gmtspart_id 
      join embelishments on embelishments.id = style_embelishments.embelishment_id 
      join embelishment_types on embelishment_types.id = style_embelishments.embelishment_type_id
      join jobs on jobs.id = budgets.job_id
      join companies on companies.id = jobs.company_id 
      join styles on styles.id = jobs.style_id 
      where (po_emb_services.id = ?) 
      and po_emb_services.deleted_at is null
    ", [$id])
    )
      ->map(function ($details) use ($embelishmentsize) {
        $details->embelishment_size = $embelishmentsize[$details->embelishment_size_id];
        $details->qty_bal = $details->bom_qty - $details->total_po_qty;
        $details->rate_bal = $details->bom_rate - $details->po_rate;
        $details->amount_bal = $details->bom_amount - $details->total_po_amount;
        return $details;
      });

    //  dd($details);die;

    $shorttype = array_prepend(config('bprs.shorttype'), '-Select-', '');

    $responsible = $this->poembservice
      ->join('po_emb_service_items', function ($join) {
        $join->on('po_emb_services.id', '=', 'po_emb_service_items.po_emb_service_id');
      })
      ->join('po_emb_service_item_resps', function ($join) {
        $join->on('po_emb_service_items.id', '=', 'po_emb_service_item_resps.po_emb_service_item_id');
      })
      ->join('sections', function ($join) {
        $join->on('sections.id', '=', 'po_emb_service_item_resps.section_id');
      })
      ->leftJoin('employee_h_rs', function ($join) {
        $join->on('employee_h_rs.id', '=', 'po_emb_service_item_resps.employee_h_r_id');
      })
      ->leftJoin('companies', function ($join) {
        $join->on('companies.id', '=', 'employee_h_rs.company_id');
      })
      ->where([['po_emb_services.id', '=', $id]])
      ->orderBy('po_emb_service_item_resps.id', 'desc')
      ->get([
        'po_emb_service_item_resps.*',
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

    return Template::loadView('Approval.PoEmbServiceApprovalSummeryMatrix', ['data' => $data]);
  }

  public function poDetails()
  {
    $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
    $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');

    $rows = $this->poembservice
      ->selectRaw('
        styles.style_ref,
        companies.code as company_code,
        budget_embs.style_embelishment_id,
        po_emb_services.id as po_emb_service_id,
        po_emb_services.po_no,
        po_emb_services.po_date,
        po_emb_services.pi_no,
        po_emb_services.pi_date,
        po_emb_services.source_id,
        po_emb_services.pay_mode,
        po_emb_services.delv_start_date,
        po_emb_services.delv_end_date,
        po_emb_services.exch_rate,
       
        po_emb_service_items.id as po_emb_service_item_id,
        suppliers.name as supplier_name,
        currencies.code as currency_code,
        sum(po_emb_service_items.qty) as po_qty,
        avg(po_emb_service_items.rate) as po_rate,
        sum(po_emb_service_items.amount) as po_amount
    ')
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'po_emb_services.company_id');
      })
      ->join('suppliers', function ($join) {
        $join->on('suppliers.id', '=', 'po_emb_services.supplier_id');
      })
      ->join('currencies', function ($join) {
        $join->on('currencies.id', '=', 'po_emb_services.currency_id');
      })
      ->join('po_emb_service_items', function ($join) {
        $join->on('po_emb_services.id', '=', 'po_emb_service_items.po_emb_service_id');
      })
      ->join('budget_embs', function ($join) {
        $join->on('budget_embs.id', '=', 'po_emb_service_items.budget_emb_id');
      })
      ->join('style_embelishments', function ($join) {
        $join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
      })
      ->join('style_gmts', function ($join) {
        $join->on('style_gmts.id', '=', 'style_embelishments.style_gmt_id');
      })
      ->join('item_accounts', function ($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('gmtsparts', function ($join) {
        $join->on('gmtsparts.id', '=', 'style_embelishments.gmtspart_id');
      })
      ->join('embelishments', function ($join) {
        $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
      })
      ->join('embelishment_types', function ($join) {
        $join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
      })
      ->join('budgets', function ($join) {
        $join->on('budgets.id', '=', 'budget_embs.budget_id');
      })
      ->join('jobs', function ($join) {
        $join->on('jobs.id', '=', 'budgets.job_id');
      })
      ->join('styles', function ($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
      })
      ->where([['jobs.company_id', '=', request('company_id', 0)]])
      ->where([['po_emb_service_items.budget_emb_id', '=', request('budget_emb_id', 0)]])
      ->where([['budget_embs.style_embelishment_id', '=', request('style_embelishment_id', 0)]])
      ->where([['styles.id', '=', request('style_id', 0)]])
      ->groupBy([
        'styles.style_ref',
        'companies.code',
        'budget_embs.style_embelishment_id',
        'po_emb_services.id',
        'po_emb_services.po_no',
        'po_emb_services.po_date',
        'po_emb_services.pi_no',
        'po_emb_services.pi_date',
        'po_emb_services.source_id',
        'po_emb_services.pay_mode',
        'po_emb_services.delv_start_date',
        'po_emb_services.delv_end_date',
        'po_emb_services.exch_rate',
        'po_emb_service_items.id',
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
