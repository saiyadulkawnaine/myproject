<?php

namespace App\Http\Controllers\Report\Commercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveDocPrepRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveYarnBtbLcRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveClaimRepository;

class CashIncentiveReportController extends Controller
{
 private $expsalescontract;
 private $currency;
 private $company;
 private $cashincentiveref;
 private $cashincentivedocprep;
 private $yarnbtplc;
 private $cashincentiveclaim;
 private $itemaccount;
 private $supplier;
 public function __construct(
  ExpLcScRepository $expsalescontract,
  CurrencyRepository $currency,
  CompanyRepository $company,
  BuyerRepository $buyer,
  CashIncentiveRefRepository $cashincentiveref,
  CashIncentiveDocPrepRepository $cashincentivedocprep,
  CashIncentiveYarnBtbLcRepository $yarnbtplc,
  CashIncentiveClaimRepository $cashincentiveclaim,
  ItemAccountRepository $itemaccount,
  SupplierRepository $supplier
 ) {
  $this->expsalescontract    = $expsalescontract;
  $this->currency = $currency;
  $this->buyer = $buyer;
  $this->company = $company;
  $this->cashincentiveref = $cashincentiveref;
  $this->cashincentivedocprep = $cashincentivedocprep;
  $this->yarnbtplc = $yarnbtplc;
  $this->cashincentiveclaim = $cashincentiveclaim;
  $this->itemaccount = $itemaccount;
  $this->supplier = $supplier;

  $this->middleware('auth');
  //$this->middleware('permission:view.cashincentivefollowupreports',   ['only' => ['create', 'index','show']]);
 }
 public function index()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '', '');
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
  $region = array_prepend(config('bprs.region'), '-Select-', '');
  $years = array_prepend(config('bprs.years'), '-Select-', '');
  $selected_year = date('Y');


  return Template::loadView('Report.Commercial.CashIncentiveReport', ['company' => $company, 'buyer' => $buyer, 'yesno' => $yesno, 'region' => $region, 'years' => $years, 'selected_year' => $selected_year]);
 }


 public function getData()
 {
  $region = config('bprs.region');
  $yesno = config('bprs.yesno');
  $year = request('year', 0);
  $realiz_year = '';
  if ($year) {
   $realiz_year = " and cash_incentive_claims.realized_year = $year ";
  } else {
   $realiz_year = '';
  }
  $rows = $this->cashincentiveref
   ->selectRaw('
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.lc_sc_value,
            exp_lc_scs.buyer_id,
            exp_lc_scs.beneficiary_id,  
            exp_lc_scs.exporter_bank_branch_id,
            cash_incentive_refs.id as cash_incentive_ref_id,
            cash_incentive_refs.incentive_no,
            cash_incentive_refs.bank_file_no,
            cash_incentive_refs.region_id,
            cash_incentive_refs.claim_sub_date,
            cash_incentive_refs.company_id,
            cash_incentive_refs.remarks,
            cash_incentive_doc_preps.gsp_certify_btma_arranged,
            cash_incentive_doc_preps.vat_eleven_arranged,
            cash_incentive_doc_preps.ud_copy_arranged,
            cash_incentive_doc_preps.prc_bd_format_arranged,
            cash_incentive_doc_preps.alt_cash_assist_bgmea_arranged,
            cash_incentive_doc_preps.cash_certify_btma_arranged,
            cash_incentive_files.audit_submit_date,
            cash_incentive_files.bb_submit_date,
            cash_incentive_files.audit_report_date as audit_complete,
            cash_incentive_files.amount as file_amount,
 
            cashClaim.invoice_qty,
            cashClaim.invoice_amount,
            cashClaim.net_wgt_exp_qty,
            cashClaim.realized_amount,
            cashClaim.freight,
            cashClaim.net_realized_amount,
            cashClaim.cost_of_export,
            cashClaim.claim,
            cashClaim.claim_amount,
            cashClaim.local_cur_amount,

            cashLoan.advance_amount_tk,
            cash_incentive_adv_claims.amount as advance_applied_amount,
            buyers.name as buyer_name,
            companies.code as company_code,
            bank_branches.branch_name,
            banks.name as bank_name')
   /* 
            currencies.name as currency_id,
            exp_lc_scs.currency_id, 
            cash_incentive_claims.bank_bill_no,
            cash_incentive_claims.invoice_no,
            */
   ->leftJoin('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'cash_incentive_refs.exp_lc_sc_id');
   })
   ->leftJoin('cash_incentive_doc_preps', function ($join) {
    $join->on('cash_incentive_doc_preps.cash_incentive_ref_id', '=', 'cash_incentive_refs.id');
   })
   ->leftJoin('cash_incentive_files', function ($join) {
    $join->on('cash_incentive_files.cash_incentive_ref_id', '=', 'cash_incentive_refs.id');
   })
   ->leftJoin('cash_incentive_adv_claims', function ($join) {
    $join->on('cash_incentive_adv_claims.cash_incentive_ref_id', '=', 'cash_incentive_refs.id');
   })
   ->join(\DB::raw("(
          SELECT 
            cash_incentive_refs.id as cash_incentive_ref_id,
            sum(cash_incentive_claims.invoice_qty) as invoice_qty,
            sum(cash_incentive_claims.invoice_amount) as invoice_amount,
            sum(cash_incentive_claims.net_wgt_exp_qty) as net_wgt_exp_qty,
            sum(cash_incentive_claims.realized_amount) as realized_amount,
            sum(cash_incentive_claims.freight) as freight,
            sum(cash_incentive_claims.net_realized_amount) as net_realized_amount,
            sum(cash_incentive_claims.cost_of_export) as cost_of_export,
            avg(cash_incentive_claims.claim) as claim,
            sum(cash_incentive_claims.claim_amount) as claim_amount,
            sum(cash_incentive_claims.local_cur_amount) as local_cur_amount
          FROM
          cash_incentive_claims
          left join cash_incentive_refs on cash_incentive_claims.cash_incentive_ref_id = cash_incentive_refs.id 
          where cash_incentive_claims.deleted_at is null $realiz_year
         GROUP BY
            cash_incentive_refs.id
        ) cashClaim"), "cashClaim.cash_incentive_ref_id", "=", "cash_incentive_refs.id")
   /* 
            ->leftJoin('cash_incentive_loans',function($join){
                $join->on('cash_incentive_loans.cash_incentive_ref_id','=','cash_incentive_refs.id');
            }) 
        */
   ->leftJoin(\DB::raw("(
            SELECT 
              cash_incentive_refs.id as cash_incentive_ref_id,
              sum(cash_incentive_loans.advance_amount_tk) as advance_amount_tk
            FROM
            cash_incentive_loans
            left join cash_incentive_refs on cash_incentive_loans.cash_incentive_ref_id = cash_incentive_refs.id 
           GROUP BY
              cash_incentive_refs.id
          ) cashLoan"), "cashLoan.cash_incentive_ref_id", "=", "cash_incentive_refs.id")
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'cash_incentive_refs.company_id');
   })
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
   })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   // ->when(request('year'), function ($q) use($year) {
   //     return $q->whereYear('cashClaim.realized_date', '=', /* date('Y') */$year);
   //     //$q->whereYear('created_at', '=', date('Y'));
   // })
   ->when(request('lc_sc_no'), function ($q) {
    return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%" . request('lc_sc_no', 0) . "%");
   })
   /* ->when(request('date_from'), function ($q) {
			return $q->where('exp_lc_scs.lc_sc_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
			return $q->where('exp_lc_scs.lc_sc_date', '<=',request('date_to', 0));
		}) */
   ->when(request('bank_bill_no'), function ($q) {
    return $q->where('cash_incentive_claims.bank_bill_no', 'LIKE', "%" . request('file_no', 0) . "%");
   })
   ->when(request('company_id'), function ($q) {
    return $q->where('cash_incentive_refs.company_id', '=', request('company_id', 0));
   })
   ->when(request('buyer_id'), function ($q) {
    return $q->where('exp_lc_scs.buyer_id', '=', request('buyer_id', 0));
   })
   ->groupBy([
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_value',
    'exp_lc_scs.buyer_id',
    'exp_lc_scs.beneficiary_id',
    'exp_lc_scs.exporter_bank_branch_id',
    'cash_incentive_refs.id',
    'cash_incentive_refs.incentive_no',
    'cash_incentive_refs.bank_file_no',
    'cash_incentive_refs.region_id',
    'cash_incentive_refs.claim_sub_date',
    'cash_incentive_refs.company_id',
    'cash_incentive_refs.remarks',
    'cash_incentive_doc_preps.gsp_certify_btma_arranged',
    'cash_incentive_doc_preps.vat_eleven_arranged',
    'cash_incentive_doc_preps.ud_copy_arranged',
    'cash_incentive_doc_preps.prc_bd_format_arranged',
    'cash_incentive_doc_preps.alt_cash_assist_bgmea_arranged',
    'cash_incentive_doc_preps.cash_certify_btma_arranged',
    'cash_incentive_files.audit_submit_date',
    'cash_incentive_files.bb_submit_date',
    'cash_incentive_files.audit_report_date',
    'cash_incentive_files.amount',

    'cashClaim.invoice_qty',
    'cashClaim.invoice_amount',
    'cashClaim.net_wgt_exp_qty',
    'cashClaim.realized_amount',
    'cashClaim.freight',
    'cashClaim.net_realized_amount',
    'cashClaim.cost_of_export',
    'cashClaim.claim',
    'cashClaim.claim_amount',
    'cashClaim.local_cur_amount',
    'cash_incentive_adv_claims.amount',
    //'cash_incentive_claims.bank_bill_no',
    //'cash_incentive_claims.invoice_no',
    'cashLoan.advance_amount_tk',

    'buyers.name',
    'companies.code',
    'bank_branches.branch_name',
    'banks.name'
   ])
   //->orderBy('cash_incentive_refs.id')
   ->get()
   ->map(function ($rows) use ($region, $yesno) {
    if ($rows->claim_sub_date !== null) {
     $rows->claim_sub_date = date("d-M-Y", strtotime($rows->claim_sub_date));
    }
    $rows->bb_submit_date = $rows->bb_submit_date ? date("d-M-Y", strtotime($rows->bb_submit_date)) : '--';
    $rows->audit_submit_date = $rows->audit_submit_date ? date("d-M-Y", strtotime($rows->audit_submit_date)) : '--';
    $rows->audit_complete = $rows->audit_complete ? date("d-M-Y", strtotime($rows->audit_complete)) : '--';
    $rows->region_id = isset($region[$rows->region_id]) ? $region[$rows->region_id] : '';
    $rows->gsp_certify_btma_arranged = isset($yesno[$rows->gsp_certify_btma_arranged]) ? $yesno[$rows->gsp_certify_btma_arranged] : '';
    $rows->vat_eleven_arranged = isset($yesno[$rows->vat_eleven_arranged]) ? $yesno[$rows->vat_eleven_arranged] : '';
    $rows->ud_copy_arranged = isset($yesno[$rows->ud_copy_arranged]) ? $yesno[$rows->ud_copy_arranged] : '';
    $rows->prc_bd_format_arranged = isset($yesno[$rows->prc_bd_format_arranged]) ? $yesno[$rows->prc_bd_format_arranged] : '';
    $rows->alt_cash_assist_bgmea_arranged = isset($yesno[$rows->alt_cash_assist_bgmea_arranged]) ? $yesno[$rows->alt_cash_assist_bgmea_arranged] : '';
    $rows->cash_certify_btma_arranged = isset($yesno[$rows->cash_certify_btma_arranged]) ? $yesno[$rows->cash_certify_btma_arranged] : '';
    $rows->exporter_branch_name = $rows->bank_name . '<br/>(' . $rows->branch_name . ')';
    $balance_tk = $rows->local_cur_amount - $rows->advance_amount_tk;
    $rows->balance_tk = number_format($balance_tk, 2);
    $rows->local_cur_amount = number_format($rows->local_cur_amount, 2);
    $rows->advance_amount_tk = number_format($rows->advance_amount_tk, 2);
    $rows->invoice_qty = number_format($rows->invoice_qty, 2);
    $rows->invoice_amount = number_format($rows->invoice_amount, 2);
    $rows->net_wgt_exp_qty = number_format($rows->net_wgt_exp_qty, 2);
    $rows->realized_amount = number_format($rows->realized_amount, 2);
    $rows->cost_of_export = number_format($rows->cost_of_export, 2);
    $rows->freight = number_format($rows->freight, 2);
    $rows->net_realized_amount = number_format($rows->net_realized_amount, 2);
    $rows->claim = number_format($rows->claim, 2);
    $rows->claim_amount = number_format($rows->claim_amount, 2);
    $rows->advance_applied_amount = number_format($rows->advance_applied_amount, 2);
    $rows->file_amount = number_format($rows->file_amount, 2);
    return $rows;
   });
  echo json_encode($rows);
 }

 public function getClaim()
 {
  $year = request('year', 0);
  // $realiz_year='';
  // if($year){
  //     $realiz_year=" and cash_incentive_claims.realized_year = $year ";  
  // }
  // else{
  //     $realiz_year='';
  // }
  //$cash_incentive_ref_id=request('cash_incentive_ref_id',0);
  if (request('cash_incentive_ref_id', 0)) {
   $rows = $this->cashincentiveclaim
    ->leftJoin('cash_incentive_refs', function ($join) {
     $join->on('cash_incentive_claims.cash_incentive_ref_id', '=', 'cash_incentive_refs.id');
    })
    ->where([['cash_incentive_claims.cash_incentive_ref_id', '=', request('cash_incentive_ref_id', 0)]])
    ->when(request('cash_incentive_ref_id'), function ($q) {
     return $q->where('cash_incentive_refs.id', '=', request('cash_incentive_ref_id', 0));
    })
    ->get([
     'cash_incentive_refs.id as cash_incentive_ref_id',
     'cash_incentive_claims.*'
    ])
    ->map(function ($rows) {
     $rows->realized_date = date('d-M-Y', strtotime($rows->realized_date));
     $short_realized_amount = $rows->invoice_amount - $rows->realized_amount;
     if ($rows->invoice_amount) {
      $short_realize_percent = ($short_realized_amount / $rows->invoice_amount) * 100;
     }
     $rows->short_realized_amount = number_format($short_realized_amount, 2);
     $rows->short_realize_percent = number_format($short_realize_percent, 2) . " %";
     $rows->local_cur_amount = number_format($rows->local_cur_amount, 2);
     $rows->invoice_qty = number_format($rows->invoice_qty, 2);
     $rows->invoice_amount = number_format($rows->invoice_amount, 2);
     $rows->net_wgt_exp_qty = number_format($rows->net_wgt_exp_qty, 2);
     $rows->realized_amount = number_format($rows->realized_amount, 2);
     $rows->cost_of_export = number_format($rows->cost_of_export, 2);
     $rows->freight = number_format($rows->freight, 2);
     $rows->net_realized_amount = number_format($rows->net_realized_amount, 2);
     $rows->claim_amount = number_format($rows->claim_amount, 2);
     return $rows;
    });
   return $rows;
  } else {
   $rows = $this->cashincentiveclaim
    ->leftJoin('cash_incentive_refs', function ($join) {
     $join->on('cash_incentive_claims.cash_incentive_ref_id', '=', 'cash_incentive_refs.id');
    })
    ->leftJoin('exp_lc_scs', function ($join) {
     $join->on('exp_lc_scs.id', '=', 'cash_incentive_refs.exp_lc_sc_id');
    })
    ->leftJoin('companies', function ($join) {
     $join->on('companies.id', '=', 'cash_incentive_refs.company_id');
    })
    ->leftJoin('buyers', function ($join) {
     $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
    })
    ->when(request('year'), function ($q) use ($year) {
     return $q->whereYear('cash_incentive_claims.realized_date', '=', request('year', 0));
     //$q->whereYear('created_at', '=', date('Y'));
    })
    ->when(request('lc_sc_no'), function ($q) {
     return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%" . request('lc_sc_no', 0) . "%");
    })
    ->when(request('bank_bill_no'), function ($q) {
     return $q->where('cash_incentive_claims.bank_bill_no', 'LIKE', "%" . request('bank_bill_no', 0) . "%");
    })
    ->when(request('company_id'), function ($q) {
     return $q->where('cash_incentive_refs.company_id', '=', request('company_id', 0));
    })
    ->when(request('buyer_id'), function ($q) {
     return $q->where('exp_lc_scs.buyer_id', '=', request('buyer_id', 0));
    })
    ->get([
     'cash_incentive_refs.id as cash_incentive_ref_id',
     'exp_lc_scs.lc_sc_no',
     'cash_incentive_claims.*'
    ])
    ->map(function ($rows) {
     $rows->realized_date = date('d-M-Y', strtotime($rows->realized_date));
     $short_realized_amount = $rows->invoice_amount - $rows->realized_amount;
     if ($rows->invoice_amount) {
      $short_realize_percent = ($short_realized_amount / $rows->invoice_amount) * 100;
     }
     $rows->short_realized_amount = number_format($short_realized_amount, 2);
     $rows->short_realize_percent = number_format($short_realize_percent, 2) . " %";
     $rows->local_cur_amount = number_format($rows->local_cur_amount, 2);
     $rows->invoice_qty = number_format($rows->invoice_qty, 2);
     $rows->invoice_amount = number_format($rows->invoice_amount, 2);
     $rows->net_wgt_exp_qty = number_format($rows->net_wgt_exp_qty, 2);
     $rows->realized_amount = number_format($rows->realized_amount, 2);
     $rows->cost_of_export = number_format($rows->cost_of_export, 2);
     $rows->freight = number_format($rows->freight, 2);
     $rows->net_realized_amount = number_format($rows->net_realized_amount, 2);
     $rows->claim_amount = number_format($rows->claim_amount, 2);
     return $rows;
    });
   return $rows;
  }
 }

 public function getDocPrep()
 {
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
  $docprep = $this->cashincentivedocprep
   ->leftJoin('cash_incentive_refs', function ($join) {
    $join->on('cash_incentive_doc_preps.cash_incentive_ref_id', '=', 'cash_incentive_refs.id');
   })
   ->where([['cash_incentive_ref_id', '=', request('cash_incentive_ref_id', 0)]])
   ->get([
    'cash_incentive_doc_preps.*'
   ])
   ->map(function ($docprep) use ($yesno) {
    $docprep->exp_lc_sc_arranged = $yesno[$docprep->exp_lc_sc_arranged];
    $docprep->exp_invoice_arranged = $yesno[$docprep->exp_invoice_arranged];
    $docprep->exp_packinglist_arranged = $yesno[$docprep->exp_packinglist_arranged];
    $docprep->bill_of_loading_arranged = $yesno[$docprep->bill_of_loading_arranged];
    $docprep->exp_bill_of_entry_arranged = $yesno[$docprep->exp_bill_of_entry_arranged];
    $docprep->exp_form_arranged = $yesno[$docprep->exp_form_arranged];
    $docprep->gsp_co_arranged = $yesno[$docprep->gsp_co_arranged];
    $docprep->prc_bd_format_arranged = $yesno[$docprep->prc_bd_format_arranged];
    $docprep->ud_copy_arranged = $yesno[$docprep->ud_copy_arranged];
    $docprep->btb_lc_arranged = $yesno[$docprep->btb_lc_arranged];
    $docprep->import_pi_arranged = $yesno[$docprep->import_pi_arranged];
    $docprep->gsp_certify_btma_arranged = $yesno[$docprep->gsp_certify_btma_arranged];
    $docprep->vat_eleven_arranged = $yesno[$docprep->vat_eleven_arranged];
    $docprep->rcv_yarn_challan_arranged = $yesno[$docprep->rcv_yarn_challan_arranged];
    $docprep->imp_invoice_arranged = $yesno[$docprep->imp_invoice_arranged];
    $docprep->imp_packing_list_arranged = $yesno[$docprep->imp_packing_list_arranged];
    $docprep->bnf_certify_spin_mil_arranged = $yesno[$docprep->bnf_certify_spin_mil_arranged];
    $docprep->certificate_of_origin_arranged = $yesno[$docprep->certificate_of_origin_arranged];
    $docprep->alt_cash_assist_bgmea_arranged = $yesno[$docprep->alt_cash_assist_bgmea_arranged];
    $docprep->cash_certify_btma_arranged = $yesno[$docprep->cash_certify_btma_arranged];
    return $docprep;
   });

  return Template::loadView('Report.Commercial.CashIncentiveReportDocPrepMatrix', ['docprep' => $docprep]);
 }
}
