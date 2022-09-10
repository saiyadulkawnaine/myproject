<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Sms;

class PoTrimShortApprovalController extends Controller
{
    private $potrim;
    private $user;
    private $supplier;
    private $company;
    private $implc;
    private $implcpo;

    public function __construct(
        PoTrimRepository $potrim,
        UserRepository $user,
        SupplierRepository $supplier,
        CompanyRepository $company,
        ImpLcRepository $implc,
        ImpLcPoRepository $implcpo

    ) {
        $this->potrim = $potrim;
        $this->user = $user;
        $this->supplier = $supplier;
        $this->company = $company;
        $this->implc = $implc;
        $this->implcpo = $implcpo;

        $this->middleware('auth');

        // $this->middleware('permission:approve.potrimshorts',   ['only' => ['approved', 'index', 'reportData', 'reportDataApp']]);
        // $this->middleware('permission:unapprove.potrimshorts',   ['only' => ['unapproved']]);
    }

    public function index()
    {
        $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
        $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
        return Template::loadView('Approval.PoTrimShortApproval', ['company' => $company, 'supplier' => $supplier]);
    }

    public function reportData()
    {
        $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
        $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
        $rows = $this->potrim
            ->join('companies', function ($join) {
                $join->on('companies.id', '=', 'po_trims.company_id');
            })
            ->join('suppliers', function ($join) {
                $join->on('suppliers.id', '=', 'po_trims.supplier_id');
            })
            ->join('currencies', function ($join) {
                $join->on('currencies.id', '=', 'po_trims.currency_id');
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('po_trims.company_id', '=', request('company_id', 0));
            })
            ->when(request('supplier_id'), function ($q) {
                return $q->where('po_trims.supplier_id', '=', request('supplier_id', 0));
            })
            ->when(request('date_from'), function ($q) {
                return $q->where('po_trims.po_date', '>=', request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
                return $q->where('po_trims.po_date', '<=', request('date_to', 0));
            })
            ->whereNull('po_trims.approved_at')
            ->where([['po_trims.po_type_id', '=', 2]])
            ->orderBy('po_trims.id', 'desc')
            ->get([
                'po_trims.*',
                'companies.code as company_code',
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
        $master = $this->potrim->find($id);
        $user = \Auth::user();
        $approved_at = date('Y-m-d H:i:s');

        $master->approved_by = $user->id;
        $master->approved_at = $approved_at;
        $master->unapproved_by = NULL;
        $master->unapproved_at = NULL;
        $master->timestamps = false;
        $potrim = $master->save();


        if ($potrim) {
            return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
        }
    }

    public function reportDataApp()
    {
        $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
        $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');

        $rows = $this->potrim
            ->selectRaw('
            po_trims.id,
            po_trims.po_no,
            po_trims.po_date,
            po_trims.pi_no,
            po_trims.pi_date,
            po_trims.source_id,
            po_trims.pay_mode,
            po_trims.amount,
            companies.code as company_code,
            suppliers.name as supplier_code,
            currencies.code as currency_code,
            implc.lc_date,
            implc.lc_no_i,
            implc.lc_no_ii,
            implc.lc_no_iii,
            implc.lc_no_iv, 
            trim_rcv.rcv_qty,
            sum(po_trim_items.qty) as po_qty
        ')
            ->leftJoin('po_trim_items', function ($join) {
                $join->on('po_trim_items.po_trim_id', '=', 'po_trims.id');
            })
            ->join('companies', function ($join) {
                $join->on('companies.id', '=', 'po_trims.company_id');
            })
            ->join('suppliers', function ($join) {
                $join->on('suppliers.id', '=', 'po_trims.supplier_id');
            })
            ->join('currencies', function ($join) {
                $join->on('currencies.id', '=', 'po_trims.currency_id');
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
            join po_trims on po_trims.id=imp_lc_pos.purchase_order_id
            where imp_lcs.menu_id=2 
        ) implc"), "po_trims.id", "=", "implc.purchase_order_id")
            ->leftJoin(\DB::raw("(
            select 
            po_trim_items.po_trim_id,
            sum(inv_trim_rcv_items.qty) as rcv_qty--,
            --sum(inv_trim_rcv_items.amount) as rcv_amount,
            --sum(inv_trim_transactions.store_qty) as store_qty,
            --sum(inv_trim_transactions.store_amount) as rcv_amount_tk
            from po_trim_items
            join po_trim_item_reports on po_trim_item_reports.po_trim_item_id=po_trim_items.id
            join inv_trim_rcv_items on inv_trim_rcv_items.po_trim_item_report_id=po_trim_item_reports.id
            join inv_trim_transactions on inv_trim_transactions.inv_trim_rcv_item_id=inv_trim_rcv_items.id
            where inv_trim_transactions.trans_type_id=1
            group by 
            po_trim_items.po_trim_id
        ) trim_rcv"), "trim_rcv.po_trim_id", "=", "po_trims.id")
            ->when(request('company_id'), function ($q) {
                return $q->where('po_trims.company_id', '=', request('company_id', 0));
            })
            ->when(request('supplier_id'), function ($q) {
                return $q->where('po_trims.supplier_id', '=', request('supplier_id', 0));
            })
            ->when(request('date_from'), function ($q) {
                return $q->where('po_trims.po_date', '>=', request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
                return $q->where('po_trims.po_date', '<=', request('date_to', 0));
            })
            ->whereNotNull('po_trims.approved_at')
            ->where([['po_trims.po_type_id', '=', 2]])
            ->orderBy('po_trims.id', 'desc')
            ->groupBy([
                'po_trims.id',
                'po_trims.po_no',
                'po_trims.po_date',
                'po_trims.pi_no',
                'po_trims.pi_date',
                'po_trims.source_id',
                'po_trims.pay_mode',
                'po_trims.amount',
                'companies.code',
                'suppliers.name',
                'currencies.code',
                'implc.lc_date',
                'implc.lc_no_i',
                'implc.lc_no_ii',
                'implc.lc_no_iii',
                'implc.lc_no_iv',
                'trim_rcv.rcv_qty'

            ])
            ->get()
            ->map(function ($rows) use ($source, $paymode) {
                $rows->source = $source[$rows->source_id];
                $rows->paymode = $paymode[$rows->pay_mode];
                $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
                $rows->amount = number_format($rows->amount);
                $rows->po_qty = number_format($rows->po_qty);
                $rows->rcv_qty = number_format($rows->rcv_qty);
                $rows->delv_start_date = $rows->delv_start_date ? date('d-M-Y', strtotime($rows->delv_start_date)) : '--';
                $rows->delv_end_date = $rows->delv_end_date ? date('d-M-Y', strtotime($rows->delv_end_date)) : '--';
                $rows->lc_date = $rows->lc_date ? date('d-M-Y', strtotime($rows->lc_date)) : '';
                $rows->lc_no = $rows->lc_no_i ? $rows->lc_no_i . " " . $rows->lc_no_ii . " " . $rows->lc_no_iii . " " . $rows->lc_no_iv . " Dt:" . $rows->lc_date . "" : '--';
                return $rows;
            });
        echo json_encode($rows);
    }

    public function unapproved(Request $request)
    {
        $id = request('id', 0);
        $master = $this->potrim->find($id);
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
            ->where([['imp_lcs.menu_id', '=', 2]])
            ->where([['imp_lc_pos.purchase_order_id', '=', $id]])
            ->get(['imp_lc_pos.purchase_order_id'])
            ->first();
        if ($implcpo) {
            return response()->json(array('success' => false,  'message' => 'LC Found. Untag PO from LC first'), 200);
        }

        $potrim = $master->save();


        if ($potrim) {
            return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
        }
    }

    public function getTrimsSummery()
    {
        $id = request('id', 0);

        $rows = $this->potrim
            ->join('companies', function ($join) {
                $join->on('companies.id', '=', 'po_trims.company_id');
            })
            ->leftJoin('buyers', function ($join) {
                $join->on('buyers.id', '=', 'po_trims.buyer_id');
            })
            ->join('suppliers', function ($join) {
                $join->on('suppliers.id', '=', 'po_trims.supplier_id');
            })
            ->join('currencies', function ($join) {
                $join->on('currencies.id', '=', 'po_trims.currency_id');
            })
            ->where([['po_trims.id', '=', $id]])
            ->get([
                'po_trims.*',
                'companies.name as company_name',
                'suppliers.name as supplier_name',
                'currencies.code as currency_code',
                'buyers.name as buyer_name'
            ])
            ->first();

        $details = collect(\DB::Select("
            select
            m.style_ref,
            m.style_id,
            m.company_id,
            m.bnf_company,
            m.itemclass_id,
            m.item_class,
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
            from
            (
                select 
                styles.style_ref,
                styles.id as style_id,
                companies.id as company_id,
                companies.code as bnf_company,
                budget_trims.itemclass_id,
                budget_trims.id as budget_trim_id,
                itemclasses.name as item_class,
                uoms.code as uom_code,
                budget_trims.cons as bom_qty,
                budget_trims.rate as bom_rate,
                budget_trims.amount as bom_amount,
                currentPo.qty as po_qty,
                currentPo.rate as po_rate,
                currentPo.amount as po_amount,
                cumulative.qty as total_po_qty,
                cumulative.rate as total_po_rate,
                cumulative.amount as total_po_amount
                from PO_TRIMS 
                inner join PO_TRIM_ITEMS on PO_TRIMS.ID = PO_TRIM_ITEMS.PO_TRIM_ID 
                inner join BUDGET_TRIMS on PO_TRIM_ITEMS.BUDGET_TRIM_ID = BUDGET_TRIMS.ID 
                and PO_TRIM_ITEMS.DELETED_AT is null 
                inner join ITEMCLASSES on ITEMCLASSES.ID = BUDGET_TRIMS.ITEMCLASS_ID 
                inner join ITEMCATEGORIES on ITEMCATEGORIES.ID = ITEMCLASSES.ITEMCATEGORY_ID 
                inner join BUDGETS on BUDGETS.ID = BUDGET_TRIMS.BUDGET_ID 
                inner join JOBS on JOBS.ID = BUDGETS.JOB_ID 
                inner join COMPANIES on COMPANIES.ID = JOBS.COMPANY_ID 
                inner join STYLES on STYLES.ID = JOBS.STYLE_ID 
                inner join BUYERS on BUYERS.ID = STYLES.BUYER_ID 
                inner join UOMS on UOMS.ID = BUDGET_TRIMS.UOM_ID 
                left join (
                SELECT 
                po_trim_items.budget_trim_id,
                sum(po_trim_items.qty) as qty,
                avg(po_trim_items.rate) as rate,
                sum(po_trim_items.amount) as amount
                FROM po_trim_items 
                join budget_trims on budget_trims.id=po_trim_items.budget_trim_id
                join po_trims on po_trims.id=po_trim_items.po_trim_id
                where po_trims.id = '" . $id . "'
                group by 
                po_trim_items.budget_trim_id
                ) currentPo on CURRENTPO.budget_trim_id = budget_trims.ID 
                left join (
                SELECT 
                po_trim_items.budget_trim_id,
                sum(po_trim_items.qty) as qty,
                avg(po_trim_items.rate) as rate,
                sum(po_trim_items.amount) as amount
                FROM po_trim_items 
                join budget_trims on budget_trims.id=po_trim_items.budget_trim_id
                join po_trims on po_trims.id=po_trim_items.po_trim_id
                group by 
                po_trim_items.budget_trim_id
                ) cumulative on CUMULATIVE.budget_trim_id = budget_trims.id 
                where (PO_TRIMS.ID = '" . $id . "') 
                and PO_TRIMS.DELETED_AT is null
            )m
            
            group by
            m.style_ref,
            m.style_id,
            m.company_id,
            m.bnf_company,
            m.itemclass_id,
            m.item_class,
            m.uom_code
            order by m.itemclass_id  
        "))
            ->map(function ($details) {
                $details->qty_bal = $details->bom_qty - $details->total_po_qty;
                $details->rate_bal = $details->bom_rate - $details->po_rate;
                $details->amount_bal = $details->bom_amount - $details->total_po_amount;
                return $details;
            });

        //  dd($details);die;

        $shorttype = array_prepend(config('bprs.shorttype'), '-Select-', '');

        $responsible = $this->potrim
            ->join('po_trim_items', function ($join) {
                $join->on('po_trims.id', '=', 'po_trim_items.po_trim_id');
            })
            ->join('po_trim_item_resps', function ($join) {
                $join->on('po_trim_items.id', '=', 'po_trim_item_resps.po_trim_item_id');
            })
            ->join('sections', function ($join) {
                $join->on('sections.id', '=', 'po_trim_item_resps.section_id');
            })
            ->leftJoin('employee_h_rs', function ($join) {
                $join->on('employee_h_rs.id', '=', 'po_trim_item_resps.employee_h_r_id');
            })
            ->leftJoin('companies', function ($join) {
                $join->on('companies.id', '=', 'employee_h_rs.company_id');
            })
            ->where([['po_trims.id', '=', $id]])
            ->orderBy('po_trim_item_resps.id', 'desc')
            ->get([
                'po_trim_item_resps.*',
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

        return Template::loadView('Approval.PoTrimApprovalSummeryMatrix', ['data' => $data]);
    }

    public function poDetails()
    {
        $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
        $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');

        $rows = $this->potrim
            ->selectRaw('
            styles.style_ref,
            companies.code as company_code,
            budget_trims.itemclass_id,
            itemclasses.name as item_class,
            po_trims.id as po_trim_id,
            po_trims.po_no,
            po_trims.po_date,
            po_trims.pi_no,
            po_trims.pi_date,
            po_trims.source_id,
            po_trims.pay_mode,
            po_trims.delv_start_date,
            po_trims.delv_end_date,
            po_trims.exch_rate,
           
            po_trim_items.id as po_trim_item_id,
            suppliers.name as supplier_name,
            currencies.code as currency_code,
            sum(po_trim_items.qty) as po_qty,
            avg(po_trim_items.rate) as po_rate,
            sum(po_trim_items.amount) as po_amount
        ')
            ->join('companies', function ($join) {
                $join->on('companies.id', '=', 'po_trims.company_id');
            })
            ->join('suppliers', function ($join) {
                $join->on('suppliers.id', '=', 'po_trims.supplier_id');
            })
            ->join('currencies', function ($join) {
                $join->on('currencies.id', '=', 'po_trims.currency_id');
            })
            ->join('po_trim_items', function ($join) {
                $join->on('po_trims.id', '=', 'po_trim_items.po_trim_id');
            })
            ->join('budget_trims', function ($join) {
                $join->on('po_trim_items.budget_trim_id', '=', 'budget_trims.id')
                    ->whereNull('po_trim_items.deleted_at');
            })
            ->join('itemclasses', function ($join) {
                $join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
            })
            ->leftJoin('itemcategories', function ($join) {
                $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
            })
            ->join('budgets', function ($join) {
                $join->on('budgets.id', '=', 'budget_trims.budget_id');
            })
            ->join('jobs', function ($join) {
                $join->on('jobs.id', '=', 'budgets.job_id');
            })
            ->join('styles', function ($join) {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->join('buyers', function ($join) {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->leftJoin('uoms', function ($join) {
                $join->on('uoms.id', '=', 'budget_trims.uom_id');
            })
            ->where([['jobs.company_id', '=', request('company_id', 0)]])
            ->where([['budget_trims.itemclass_id', '=', request('itemclass_id', 0)]])
            ->where([['styles.id', '=', request('style_id', 0)]])
            ->groupBy([
                'styles.style_ref',
                'companies.code',
                'budget_trims.itemclass_id',
                'itemclasses.name',
                'po_trims.id',
                'po_trims.po_no',
                'po_trims.po_date',
                'po_trims.pi_no',
                'po_trims.pi_date',
                'po_trims.source_id',
                'po_trims.pay_mode',
                'po_trims.delv_start_date',
                'po_trims.delv_end_date',
                'po_trims.exch_rate',
                'po_trim_items.id',
                'suppliers.name',
                'currencies.code',

            ])
            ->get()
            ->map(function ($rows) use ($source, $paymode) {
                $rows->source = $source[$rows->source_id];
                $rows->paymode = $paymode[$rows->pay_mode];
                $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
                $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
                $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
                return $rows;
            });

        echo json_encode($rows);
    }

    public function getRcvNo()
    {
        $po_trim_id = request('id', 0);
        $trimrcv = $this->potrim
            ->join('po_trim_items', function ($join) {
                $join->on('po_trims.id', '=', 'po_trim_items.po_trim_id');
            })
            ->join('po_trim_item_reports', function ($join) {
                $join->on('po_trim_items.id', '=', 'po_trim_item_reports.po_trim_item_id');
            })
            ->join('inv_trim_rcv_items', function ($join) {
                $join->on('po_trim_item_reports.id', '=', 'inv_trim_rcv_items.po_trim_item_report_id');
            })
            ->join('inv_trim_rcvs', function ($join) {
                $join->on('inv_trim_rcv_items.inv_trim_rcv_id', '=', 'inv_trim_rcvs.id');
            })
            ->join('inv_rcvs', function ($join) {
                $join->on('inv_trim_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
            })
            ->where([['po_trims.id', '=', $po_trim_id]])
            ->get([
                'po_trim_items.id as po_item_id',
                'inv_rcvs.receive_no',
                'inv_rcvs.receive_date',
                'inv_rcvs.challan_no',
                'inv_rcvs.remarks',
                'inv_trim_rcv_items.id as inv_rcv_item_id',
                'inv_trim_rcv_items.qty',
                'inv_trim_rcv_items.rate',
                'inv_trim_rcv_items.amount',
                'inv_trim_rcv_items.store_qty',
                'inv_trim_rcv_items.store_amount',
            ])
            ->map(function ($trimrcv) {
                $trimrcv->receive_date = date('d-M-Y', strtotime($trimrcv->receive_date));
                $trimrcv->qty = number_format($trimrcv->qty, 2);
                $trimrcv->rate = number_format($trimrcv->rate, 4);
                $trimrcv->amount = number_format($trimrcv->amount, 2);
                $trimrcv->store_qty = number_format($trimrcv->store_qty, 2);
                $trimrcv->store_amount = number_format($trimrcv->store_amount, 2);
                return $trimrcv;
            });
        echo json_encode($trimrcv);
    }
}
