<?php

namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRealizeRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;

use App\Library\Template;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveRealizeRequest;

class CashIncentiveRealizeController extends Controller
{

 private $cashincentiverealize;
 private $cashincentiveref;
 private $explcsc;
 private $country;
 private $supplier;
 private $itemaccount;
 private $commercialhead;

 public function __construct(CashIncentiveRealizeRepository $cashincentiverealize, CashIncentiveRefRepository $cashincentiveref, ExpLcScRepository $explcsc, CountryRepository $country, ItemAccountRepository $itemaccount, SupplierRepository $supplier, CompanyRepository $company, CommercialHeadRepository $commercialhead)
 {
  $this->cashincentiverealize = $cashincentiverealize;
  $this->cashincentiveref = $cashincentiveref;
  $this->explcsc = $explcsc;
  $this->country = $country;
  $this->itemaccount = $itemaccount;
  $this->supplier = $supplier;
  $this->company = $company;
  $this->commercialhead = $commercialhead;

  $this->middleware('auth');

  $this->middleware('permission:view.cashincentiverealizes',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.cashincentiverealizes', ['only' => ['store']]);
  $this->middleware('permission:edit.cashincentiverealizes',   ['only' => ['update']]);
  $this->middleware('permission:delete.cashincentiverealizes', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $region = config('bprs.region');

  $cashincentiverealizes = array();
  $rows = $this->cashincentiverealize
   ->leftJoin('cash_incentive_refs', function ($join) {
    $join->on('cash_incentive_realizes.cash_incentive_ref_id', '=', 'cash_incentive_refs.id');
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
   ->leftJoin(\DB::raw("(
            SELECT 
              cash_incentive_refs.id as cash_incentive_ref_id,
              sum(cash_incentive_claims.claim_amount) as claim_amount
            FROM
            cash_incentive_claims
            left join cash_incentive_refs on cash_incentive_claims.cash_incentive_ref_id = cash_incentive_refs.id 
            
           GROUP BY
              cash_incentive_refs.id
          ) cashClaim"), "cashClaim.cash_incentive_ref_id", "=", "cash_incentive_refs.id")
   ->leftJoin(
    \DB::raw("(
            SELECT 
                cash_incentive_refs.id as cash_incentive_ref_id,
                sum(cash_incentive_loans.advance_amount_tk) as advance_amount_tk,
                cash_incentive_loans.loan_ref_no
            FROM
            cash_incentive_loans
            left join cash_incentive_refs on cash_incentive_loans.cash_incentive_ref_id = cash_incentive_refs.id 
            GROUP BY
            cash_incentive_refs.id,
            cash_incentive_loans.loan_ref_no
        ) cashLoan"),
    "cashLoan.cash_incentive_ref_id",
    "=",
    "cash_incentive_refs.id"
   )
   ->orderBy('cash_incentive_realizes.id', 'desc')
   ->get([
    'cash_incentive_realizes.id',
    'cash_incentive_realizes.cash_incentive_ref_id',
    'cash_incentive_realizes.sanctioned_amount',
    'cash_incentive_refs.bank_file_no',
    'cash_incentive_refs.claim_sub_date',
    'cash_incentive_refs.incentive_no',
    'cash_incentive_refs.exp_lc_sc_id',
    'exp_lc_scs.lc_sc_no',
    'companies.name as company_name',
    'buyers.code as buyer_id',
    'cashClaim.claim_amount',
    'cashLoan.loan_ref_no',
    'cashLoan.advance_amount_tk',
   ]);

  foreach ($rows as $row) {
   $cashincentiverealize['id'] = $row->id;
   $cashincentiverealize['sanctioned_amount'] = number_format($row->sanctioned_amount, 2);
   $cashincentiverealize['claim_amount'] = number_format($row->claim_amount, 2);
   $cashincentiverealize['advance_amount_tk'] = number_format($row->advance_amount_tk, 2);
   $cashincentiverealize['loan_ref_no'] = $row->loan_ref_no;
   $cashincentiverealize['incentive_no'] = $row->incentive_no;
   $cashincentiverealize['lc_sc_no'] = $row->lc_sc_no;
   $cashincentiverealize['remarks'] = $row->remarks;
   $cashincentiverealize['bank_file_no'] = $row->bank_file_no;
   $cashincentiverealize['claim_sub_date'] = date('Y-m-d', strtotime($row->claim_sub_date));
   //$cashincentiverealize['region_id']=$region[$row->region_id];
   $cashincentiverealize['company_name'] = $row->company_name;
   $cashincentiverealize['buyer_id'] = $row->buyer_id;

   array_push($cashincentiverealizes, $cashincentiverealize);
  }
  echo json_encode($cashincentiverealizes);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
  $filequery = array_prepend(config('bprs.filequery'), '-Select-', '');
  $commercialhead = array_prepend(array_pluck($this->commercialhead->get(), 'name', 'id'), '-Select-', '');
  return Template::LoadView('Commercial.CashIncentive.CashIncentiveRealize', ['yesno' => $yesno, 'filequery' => $filequery, 'commercialhead' => $commercialhead]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(CashIncentiveRealizeRequest $request)
 {
  $cashincentiverealize = $this->cashincentiverealize->create([
   'cash_incentive_ref_id' => $request->cash_incentive_ref_id,
   'sanctioned_amount' => $request->sanctioned_amount,
   'remarks' => $request->remarks,
  ]);
  if ($cashincentiverealize) {
   return response()->json(array('success' => true, 'id' =>  $cashincentiverealize->id, 'message' => 'Save Successfully'), 200);
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
  $cashincentiverealize = $this->cashincentiverealize
   ->selectRaw('
            cash_incentive_realizes.id,
            cash_incentive_realizes.cash_incentive_ref_id,
            cash_incentive_realizes.sanctioned_amount,
            cash_incentive_realizes.remarks,
            cash_incentive_refs.exp_lc_sc_id,
            cash_incentive_refs.incentive_no,
            cash_incentive_refs.claim_sub_date,
            cash_incentive_refs.bank_file_no,
            cash_incentive_refs.region_id,
            exp_lc_scs.lc_sc_no,
            companies.name as company_name,
            buyers.code as buyer_id,
            cashLoan.loan_ref_no,
            cashLoan.advance_amount_tk,
            cashClaim.claim_amount,
            banks.name,
            bank_branches.branch_name,
            currencies.name as currency_id
        ')
   ->leftJoin('cash_incentive_refs', function ($join) {
    $join->on('cash_incentive_realizes.cash_incentive_ref_id', '=', 'cash_incentive_refs.id');
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
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
   })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->leftJoin('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   ->leftJoin(\DB::raw("(
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
            
           GROUP BY
              cash_incentive_refs.id
          ) cashClaim"), "cashClaim.cash_incentive_ref_id", "=", "cash_incentive_refs.id")
   ->leftJoin(\DB::raw("(
              SELECT 
                cash_incentive_refs.id as cash_incentive_ref_id,
                sum(cash_incentive_loans.advance_amount_tk) as advance_amount_tk,cash_incentive_loans.loan_ref_no
              FROM
              cash_incentive_loans
              left join cash_incentive_refs on cash_incentive_loans.cash_incentive_ref_id = cash_incentive_refs.id 
             GROUP BY
                cash_incentive_refs.id,
                cash_incentive_loans.loan_ref_no
            ) cashLoan"), "cashLoan.cash_incentive_ref_id", "=", "cash_incentive_refs.id")
   ->when(request('lc_sc_no'), function ($q) {
    return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%" . request('lc_sc_no', 0) . "%");
   })
   ->when(request('incentive_no'), function ($q) {
    return $q->where('cash_incentive_refs.incentive_no', '=', 'LIKE', "%" . request('incentive_no', 0) . "%");
   })
   ->when(request('claim_sub_date'), function ($q) {
    return $q->where('cash_incentive_refs.claim_sub_date', '=', request('claim_sub_date', 0));
   })
   ->where([['cash_incentive_realizes.id', '=', $id]])
   ->groupBy([
    'cash_incentive_realizes.id',
    'cash_incentive_realizes.cash_incentive_ref_id',
    'cash_incentive_realizes.sanctioned_amount',
    'cash_incentive_realizes.remarks',
    'cash_incentive_refs.exp_lc_sc_id',
    'cash_incentive_refs.incentive_no',
    'cash_incentive_refs.claim_sub_date',
    'cash_incentive_refs.bank_file_no',
    'cash_incentive_refs.region_id',
    'exp_lc_scs.lc_sc_no',
    'companies.name',
    'buyers.code',
    'cashLoan.loan_ref_no',
    'cashLoan.advance_amount_tk',
    'cashClaim.claim_amount',
    'banks.name',
    'bank_branches.branch_name',
    'currencies.name'
   ])
   ->get()
   ->map(function ($cashincentiverealize) {
    $cashincentiverealize->claim_sub_date = date('Y-m-d', strtotime($cashincentiverealize->claim_sub_date));
    $cashincentiverealize->exporter_branch_name = $cashincentiverealize->bank_name . ' (' . $cashincentiverealize->branch_name . ' )';
    return $cashincentiverealize;
   })
   ->first();
  $row['fromData'] = $cashincentiverealize;
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
 public function update(CashIncentiveRealizeRequest $request, $id)
 {
  $cashincentiverealize = $this->cashincentiverealize->update($id, [
   'cash_incentive_ref_id' => $request->cash_incentive_ref_id,
   'sanctioned_amount' => $request->sanctioned_amount,
   'remarks' => $request->remarks,
  ]);
  if ($cashincentiverealize) {
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
  if ($this->cashincentiverealize->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getCashReference()
 {
  $region = config('bprs.region');

  $cashincentiveref = $this->cashincentiveref
   ->selectRaw('
            cash_incentive_refs.id,
            cash_incentive_refs.exp_lc_sc_id,
            cash_incentive_refs.incentive_no,
            cash_incentive_refs.claim_sub_date,
            cash_incentive_refs.bank_file_no,
            cash_incentive_refs.region_id,
            exp_lc_scs.lc_sc_no,
            cumulatives.lc_sc_no as replaced_lc_sc_no,
            companies.name as company_name,
            buyers.code as buyer_id,
            cashLoan.loan_ref_no,
            cashLoan.advance_amount_tk,
            cashClaim.claim_amount,
            banks.name,
            bank_branches.branch_name,
            currencies.name as currency_id
        ')
   ->leftJoin('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'cash_incentive_refs.exp_lc_sc_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'cash_incentive_refs.company_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
   })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->leftJoin('currencies', function ($join) {
    $join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
   })
   ->leftJoin(\DB::raw("(
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
            
           GROUP BY
              cash_incentive_refs.id
          ) cashClaim"), "cashClaim.cash_incentive_ref_id", "=", "cash_incentive_refs.id")
   ->leftJoin(\DB::raw("(
              SELECT 
                cash_incentive_refs.id as cash_incentive_ref_id,
                sum(cash_incentive_loans.advance_amount_tk) as advance_amount_tk,cash_incentive_loans.loan_ref_no
              FROM
              cash_incentive_loans
              left join cash_incentive_refs on cash_incentive_loans.cash_incentive_ref_id = cash_incentive_refs.id 
             GROUP BY
                cash_incentive_refs.id,
                cash_incentive_loans.loan_ref_no
            ) cashLoan"), "cashLoan.cash_incentive_ref_id", "=", "cash_incentive_refs.id")
   ->leftJoin(\DB::raw("(SELECT 
            exp_lc_scs.id as replaced_lc_sc_id,
            exp_lc_scs.lc_sc_no, 
            exp_rep_lc_scs.exp_lc_sc_id
            FROM exp_rep_lc_scs 
            join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   
            group by 
                exp_lc_scs.id,
                exp_lc_scs.lc_sc_no,    
                exp_rep_lc_scs.exp_lc_sc_id) cumulatives"), "cumulatives.exp_lc_sc_id", "=", "exp_lc_scs.id")
   ->when(request('lc_sc_no'), function ($q) {
    return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%" . request('lc_sc_no', 0) . "%");
   })
   ->when(request('incentive_no'), function ($q) {
    return $q->where('cash_incentive_refs.incentive_no', '=', 'LIKE', "%" . request('incentive_no', 0) . "%");
   })
   ->when(request('claim_sub_date'), function ($q) {
    return $q->where('cash_incentive_refs.claim_sub_date', '=', request('claim_sub_date', 0));
   })
   ->orderBy('cash_incentive_refs.id', 'desc')
   ->groupBy([
    'cash_incentive_refs.id',
    'cash_incentive_refs.exp_lc_sc_id',
    'cash_incentive_refs.incentive_no',
    'cash_incentive_refs.claim_sub_date',
    'cash_incentive_refs.bank_file_no',
    'cash_incentive_refs.region_id',
    'exp_lc_scs.lc_sc_no',
    'cumulatives.lc_sc_no',
    'companies.name',
    'buyers.code',
    'cashLoan.loan_ref_no',
    'cashLoan.advance_amount_tk',
    'cashClaim.claim_amount',
    'banks.name',
    'bank_branches.branch_name',
    'currencies.name'
   ])
   ->get()
   ->map(function ($cashincentiveref) use ($region) {
    $cashincentiveref->claim_sub_date = ($cashincentiveref->claim_sub_date !== null) ? date("d-M-Y", strtotime($cashincentiveref->claim_sub_date)) : null;
    $cashincentiveref->region_id = $region[$cashincentiveref->region_id];
    $cashincentiveref->exporter_branch_name = $cashincentiveref->bank_name . ' (' . $cashincentiveref->branch_name . ' )';
    return $cashincentiveref;
   });


  echo json_encode($cashincentiveref);
 }
}
