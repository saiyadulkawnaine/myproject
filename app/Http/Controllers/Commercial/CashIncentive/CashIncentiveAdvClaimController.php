<?php

namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveAdvClaimRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveAdvRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveAdvClaimRequest;

class CashIncentiveAdvClaimController extends Controller
{

 private $cashincentiveadvclaim;
 private $cashincentiveadv;
 private $cashincentiveref;

 public function __construct(CashIncentiveAdvClaimRepository $cashincentiveadvclaim, CashIncentiveRefRepository $cashincentiveref, CashIncentiveAdvRepository $cashincentiveadv)
 {
  $this->cashincentiveadvclaim = $cashincentiveadvclaim;
  $this->cashincentiveref = $cashincentiveref;
  $this->cashincentiveadv = $cashincentiveadv;
  $this->middleware('auth');

  // $this->middleware('permission:view.cashincentiveadvclaims',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.cashincentiveadvclaims', ['only' => ['store']]);
  // $this->middleware('permission:edit.cashincentiveadvclaims',   ['only' => ['update']]);
  // $this->middleware('permission:delete.cashincentiveadvclaims', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */

 public function index()
 {

  $cashincentiveadvclaims = array();
  $rows = $this->cashincentiveadvclaim
   ->join('cash_incentive_advs', function ($join) {
    $join->on('cash_incentive_adv_claims.cash_incentive_adv_id', '=', 'cash_incentive_advs.id');
   })
   ->join('cash_incentive_refs', function ($join) {
    $join->on('cash_incentive_refs.id', '=', 'cash_incentive_adv_claims.cash_incentive_ref_id');
   })
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'cash_incentive_refs.exp_lc_sc_id');
   })
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
   })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->leftJoin(\DB::raw("(
            SELECT 
            cash_incentive_refs.id as cash_incentive_ref_id,
            sum(cash_incentive_claims.claim_amount) as claim_amount,
            sum(cash_incentive_claims.local_cur_amount) as local_cur_amount
            FROM cash_incentive_refs 
            join cash_incentive_claims on cash_incentive_claims.cash_incentive_ref_id = cash_incentive_refs.id 
            group by 
            cash_incentive_refs.id
        ) claims"), "claims.cash_incentive_ref_id", "=", "cash_incentive_refs.id")
   ->where([['cash_incentive_adv_claims.cash_incentive_adv_id', '=', request('cash_incentive_adv_id', 0)]])
   ->orderBy('cash_incentive_adv_claims.id', 'desc')
   ->get([
    'cash_incentive_adv_claims.*',
    'cash_incentive_refs.bank_file_no',
    'cash_incentive_advs.advance_per',
    'claims.claim_amount',
    'claims.local_cur_amount',
    'banks.name as bank_name',
    'bank_branches.branch_name',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
   ]);

  foreach ($rows as $row) {
   $cashincentiveadvclaim['id'] = $row->id;
   $cashincentiveadvclaim['cash_incentive_ref_id'] = $row->cash_incentive_ref_id;
   $cashincentiveadvclaim['lc_sc_no'] = $row->lc_sc_no;
   $cashincentiveadvclaim['lc_sc_date'] = date('Y-m-d', strtotime($row->lc_sc_date));
   $cashincentiveadvclaim['bank_file_no'] = $row->bank_file_no;
   $cashincentiveadvclaim['advance_per'] = number_format($row->advance_per, 2);
   $cashincentiveadvclaim['rate'] = number_format($row->rate, 2);
   $cashincentiveadvclaim['amount'] = number_format($row->amount, 2);
   $cashincentiveadvclaim['claim_amount'] = number_format($row->claim_amount, 2);
   $cashincentiveadvclaim['local_cur_amount'] = number_format($row->local_cur_amount, 2);
   $cashincentiveadvclaim['remarks'] = $row->remarks;
   array_push($cashincentiveadvclaims, $cashincentiveadvclaim);
  }
  echo json_encode($cashincentiveadvclaims);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  //   
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(CashIncentiveAdvClaimRequest $request)
 {
  $cashincentiveadvclaim = $this->cashincentiveadvclaim->create([
   'cash_incentive_adv_id' => $request->cash_incentive_adv_id,
   'cash_incentive_ref_id' => $request->cash_incentive_ref_id,
   'rate' => $request->rate,
   'amount' => $request->amount,
   'remarks' => $request->remarks,
  ]);
  if ($cashincentiveadvclaim) {
   return response()->json(array('success' => true, 'id' =>  $cashincentiveadvclaim->id, 'message' => 'Save Successfully'), 200);
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
  $cashincentiveadvclaim = $this->cashincentiveadvclaim
   ->join('cash_incentive_advs', function ($join) {
    $join->on('cash_incentive_adv_claims.cash_incentive_adv_id', '=', 'cash_incentive_advs.id');
   })
   ->join('cash_incentive_refs', function ($join) {
    $join->on('cash_incentive_refs.id', '=', 'cash_incentive_adv_claims.cash_incentive_ref_id');
   })
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'cash_incentive_refs.exp_lc_sc_id');
   })
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
   })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->leftJoin(\DB::raw("(
            SELECT 
            cash_incentive_refs.id as cash_incentive_ref_id,
            sum(cash_incentive_claims.claim_amount) as claim_amount,
            sum(cash_incentive_claims.local_cur_amount) as local_cur_amount
            FROM cash_incentive_refs 
            join cash_incentive_claims on cash_incentive_claims.cash_incentive_ref_id = cash_incentive_refs.id 
            group by 
            cash_incentive_refs.id
        ) claims"), "claims.cash_incentive_ref_id", "=", "cash_incentive_refs.id")
   ->where([['cash_incentive_adv_claims.id', '=', $id]])
   ->orderBy('cash_incentive_adv_claims.id', 'desc')
   ->get([
    'cash_incentive_adv_claims.*',
    'cash_incentive_refs.bank_file_no',
    'cash_incentive_advs.advance_per',
    'claims.claim_amount',
    'claims.local_cur_amount',
    'banks.name as bank_name',
    'bank_branches.branch_name',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
   ])
   ->map(function ($cashincentiveadvclaim) {
    $cashincentiveadvclaim->exporter_branch_name = $cashincentiveadvclaim->bank_name . ' (' . $cashincentiveadvclaim->branch_name . ' )';
    //$rows->claim_amount=number_format($rows->claim_amount,2);
    //$rows->local_cur_amount=number_format($rows->local_cur_amount,2);
    return $cashincentiveadvclaim;
   })
   ->first();
  $row['fromData'] = $cashincentiveadvclaim;
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
 public function update(CashIncentiveAdvClaimRequest $request, $id)
 {
  $cashincentiveadvclaim = $this->cashincentiveadvclaim->update($id, [
   'cash_incentive_adv_id' => $request->cash_incentive_adv_id,
   'cash_incentive_ref_id' => $request->cash_incentive_ref_id,
   'rate' => $request->rate,
   'amount' => $request->amount,
   'remarks' => $request->remarks,
  ]);
  if ($cashincentiveadvclaim) {
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
  if ($this->cashincentiveadvclaim->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getCashRef()
 {
  $region = config('bprs.region');
  $advanceId = request('cash_incentive_adv_id', 0);
  $cashincentiveadv = $this->cashincentiveadv->find($advanceId);
  $cashincentiverefs = array();
  $rows = $this->cashincentiveref
   ->leftJoin('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'cash_incentive_refs.exp_lc_sc_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'cash_incentive_refs.company_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin(\DB::raw("(SELECT 
            exp_lc_scs.id as replaced_lc_sc_id,
            exp_lc_scs.lc_sc_no, 
            exp_rep_lc_scs.exp_lc_sc_id
            FROM exp_rep_lc_scs 
            join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   
            group by 
            exp_lc_scs.id,
            exp_lc_scs.lc_sc_no,    
            exp_rep_lc_scs.exp_lc_sc_id
        ) cumulatives"), "cumulatives.exp_lc_sc_id", "=", "exp_lc_scs.id")
   ->leftJoin(\DB::raw("(
            SELECT 
            cash_incentive_refs.id as cash_incentive_ref_id,
            sum(cash_incentive_claims.claim_amount) as claim_amount,
            sum(cash_incentive_claims.local_cur_amount) as local_cur_amount
            FROM cash_incentive_refs 
            join cash_incentive_claims on cash_incentive_claims.cash_incentive_ref_id = cash_incentive_refs.id 
            group by 
            cash_incentive_refs.id
        ) claims"), "claims.cash_incentive_ref_id", "=", "cash_incentive_refs.id")
   ->when(request('lc_sc_no'), function ($q) {
    return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%" . request('lc_sc_no', 0) . "%");
   })
   ->when(request('beneficiary_id'), function ($q) {
    return $q->where('exp_lc_scs.beneficiary_id', '=', request('beneficiary_id', 0));
   })
   ->when(request('claim_sub_date'), function ($q) {
    return $q->where('cash_incentive_refs.claim_sub_date', '=', request('claim_sub_date', 0));
   })
   ->when(request('bank_file_no'), function ($q) {
    return $q->where('cash_incentive_refs.bank_file_no', '=', request('bank_file_no', 0));
   })
   ->where([['cash_incentive_refs.company_id', '=', $cashincentiveadv->company_id]])
   ->where([['exp_lc_scs.exporter_bank_branch_id', '=', $cashincentiveadv->exporter_bank_branch_id]])
   ->orderBy('cash_incentive_refs.id', 'desc')
   ->get([
    'cash_incentive_refs.*',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.lc_sc_value',
    'exp_lc_scs.file_no',
    'cumulatives.lc_sc_no as replaced_lc_sc_no',
    'companies.name as company_name',
    'buyers.name as buyer_name',
    'claims.claim_amount',
    'claims.local_cur_amount',
   ]);

  foreach ($rows as $row) {
   $cashincentiveref['id'] = $row->id;
   $cashincentiveref['incentive_no'] = $row->incentive_no;
   $cashincentiveref['lc_sc_no'] = $row->lc_sc_no;
   $cashincentiveref['remarks'] = $row->remarks;
   $cashincentiveref['bank_file_no'] = $row->bank_file_no;
   $cashincentiveref['claim_sub_date'] = date('Y-m-d', strtotime($row->claim_sub_date));
   $cashincentiveref['lc_sc_date'] = date('Y-m-d', strtotime($row->lc_sc_date));
   $cashincentiveref['region_id'] = $region[$row->region_id];
   $cashincentiveref['company_name'] = $row->company_name;
   $cashincentiveref['buyer_name'] = $row->buyer_name;
   $cashincentiveref['replaced_lc_sc_no'] = $row->replaced_lc_sc_no;
   $cashincentiveref['claim_amount'] = $row->claim_amount;
   $cashincentiveref['local_cur_amount'] = $row->local_cur_amount;
   $cashincentiveref['lc_sc_value'] = $row->lc_sc_value;
   $cashincentiveref['file_no'] = $row->file_no;
   $cashincentiveref['advance_amount'] = $row->local_cur_amount * ($cashincentiveadv->advance_per / 100);

   array_push($cashincentiverefs, $cashincentiveref);
  }
  echo json_encode($cashincentiverefs);
 }
}
