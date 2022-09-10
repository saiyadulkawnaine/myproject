<?php

namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveAdvRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveClaimRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use PDF;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveAdvRequest;

class CashIncentiveAdvController extends Controller
{

 private $cashincentiveref;
 private $cashincentiveadv;
 private $cashincentiveclaim;
 private $explcsc;
 private $country;
 private $supplier;
 private $itemaccount;
 private $bankbranch;

 public function __construct(
  CashIncentiveAdvRepository $cashincentiveadv,
  CashIncentiveRefRepository $cashincentiveref,
  BankBranchRepository $bankbranch,
  ExpLcScRepository $explcsc,
  CountryRepository $country,
  ItemAccountRepository $itemaccount,
  SupplierRepository $supplier,
  CompanyRepository $company,
  CashIncentiveClaimRepository $cashincentiveclaim
 ) {
  $this->cashincentiveadv = $cashincentiveadv;
  $this->cashincentiveref = $cashincentiveref;
  $this->cashincentiveclaim = $cashincentiveclaim;
  $this->explcsc = $explcsc;
  $this->country = $country;
  $this->itemaccount = $itemaccount;
  $this->supplier = $supplier;
  $this->company = $company;
  $this->bankbranch = $bankbranch;

  $this->middleware('auth');

  // $this->middleware('permission:view.cashincentiveadvs',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.cashincentiveadvs', ['only' => ['store']]);
  // $this->middleware('permission:edit.cashincentiveadvs',   ['only' => ['update']]);
  // $this->middleware('permission:delete.cashincentiveadvs', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
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
   'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'code', 'id'), '-Select-', '');

  $cashincentiveadvs = array();
  $rows = $this->cashincentiveadv
   ->orderBy('cash_incentive_advs.id', 'desc')
   ->get();

  foreach ($rows as $row) {
   $cashincentiveadv['id'] = $row->id;
   $cashincentiveadv['company_id'] = $company[$row->company_id];
   $cashincentiveadv['advance_per'] = $row->advance_per;
   $cashincentiveadv['applied_date'] = date('Y-m-d', strtotime($row->applied_date));
   $cashincentiveadv['exporter_bank_branch_id'] = $bankbranch[$row->exporter_bank_branch_id];
   $cashincentiveadv['remarks'] = $row->remarks;
   array_push($cashincentiveadvs, $cashincentiveadv);
  }
  echo json_encode($cashincentiveadvs);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {

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
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  //$country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
  $region = array_prepend(config('bprs.region'), '-Select-', '');

  return Template::LoadView('Commercial.CashIncentive.CashIncentiveAdv', ['region' => $region, 'supplier' => $supplier, 'company' => $company, 'yesno' => $yesno, 'bankbranch' => $bankbranch]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(CashIncentiveAdvRequest $request)
 {
  $cashincentiveadv = $this->cashincentiveadv->create($request->except(['id']));
  if ($cashincentiveadv) {
   return response()->json(array('success' => true, 'id' =>  $cashincentiveadv->id, 'message' => 'Save Successfully'), 200);
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
  $cashincentiveadv = $this->cashincentiveadv->find($id);
  $row['fromData'] = $cashincentiveadv;
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
 public function update(CashIncentiveAdvRequest $request, $id)
 {
  $cashincentiveadv = $this->cashincentiveadv->update($id, $request->except(['id']));
  if ($cashincentiveadv) {
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
  if ($this->cashincentiveref->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function advanceLetter()
 {
  $id = request('id', 0);

  $rows = $this->cashincentiveadv
   ->leftJoin('bank_branches', function ($join) {
    $join->on('bank_branches.id', '=', 'cash_incentive_advs.exporter_bank_branch_id');
   })
   ->leftJoin('banks', function ($join) {
    $join->on('banks.id', '=', 'bank_branches.bank_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'cash_incentive_advs.company_id');
   })
   ->where([['cash_incentive_advs.id', '=', $id]])
   ->get([
    'cash_incentive_advs.*',
    'companies.name as company_name',
    'bank_branches.branch_name',
    'bank_branches.address as bank_address',
    'bank_branches.contact',
    'banks.name as bank_name',
    'banks.is_islamic_bank_id',
   ])
   ->first();
  $rows->applied_date = date('d.m.Y', strtotime($rows->applied_date));
  if ($rows->is_islamic_bank_id == 1) {
   $rows->is_islamic_bank = "QSCA";
  } else {
   $rows->is_islamic_bank = "cash incentive";
  }


  $incentiveadvclaim = $this->cashincentiveadv
   ->join('cash_incentive_adv_claims', function ($join) {
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
   ->where([['cash_incentive_advs.id', '=', $id]])
   ->orderBy('cash_incentive_adv_claims.id', 'asc')
   ->get([
    'cash_incentive_adv_claims.*',
    'cash_incentive_refs.bank_file_no',
    'claims.claim_amount',
    'claims.local_cur_amount',
    'banks.name as bank_name',
    'bank_branches.branch_name',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
   ]);
  $rows->total_amount = $incentiveadvclaim->sum('amount');

  $amount = round($rows->total_amount);
  $inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $rows->currency_name, $rows->hundreds_name);
  $rows->inword = $inword;

  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins('25', '40', '20');
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, '37.8');
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->AddPage();
  $pdf->SetY(40);
  $pdf->SetFont('helvetica', '', 10);

  $view = \View::make('Defult.Commercial.CashIncentive.AdvanceLetterPdf', ['rows' => $rows, 'incentiveadvclaim' => $incentiveadvclaim]);
  $html_content = $view->render();
  //$pdf->SetY(55);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
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
  $pdf->SetX(150);
  $qrc = 'Reference ID :' . $id . ", LC/SC No: " . $rows['lc_sc_no'] . ", Company: " . $rows['company_name'] . ", Bank name: " . $rows['bank_name'];
  $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 200, 20, 20, $barcodestyle, 'N');
  $pdf->Text(170, 220, $id);
  // $pdf->Text(172, 254, 'LC ID :'.$implc->id);

  // $pdf->SetFont('helvetica', 'N', 10);
  $pdf->SetFont('helvetica', '', 8);
  $filename = storage_path() . '/AdvanceLetterPdf.pdf';
  //$pdf->output($filename);
  $pdf->output($filename, 'I');
  exit();
 }
}
