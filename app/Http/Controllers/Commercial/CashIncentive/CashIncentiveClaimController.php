<?php
namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveClaimRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubInvoiceRepository;

use App\Library\Template;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveClaimRequest;

class CashIncentiveClaimController extends Controller {

    private $cashincentiveclaim;
    private $cashincentiveref;
    private $explcsc;
    private $expdocsubmission;


    public function __construct(CashIncentiveClaimRepository $cashincentiveclaim,CashIncentiveRefRepository $cashincentiveref,
    ExpLcScRepository $explcsc,ExpDocSubmissionRepository $expdocsubmission, ExpDocSubInvoiceRepository $expdocsubinvoice
    ) {
        $this->cashincentiveclaim = $cashincentiveclaim;
        $this->cashincentiveref = $cashincentiveref;
        $this->explcsc = $explcsc;
        $this->expdocsubmission = $expdocsubmission;
        $this->expdocsubinvoice = $expdocsubinvoice;

        $this->middleware('auth');

        $this->middleware('permission:view.cashincentiveclaims',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.cashincentiveclaims', ['only' => ['store']]);
        $this->middleware('permission:edit.cashincentiveclaims',   ['only' => ['update']]);
        $this->middleware('permission:delete.cashincentiveclaims', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         
        //$cashincentiveclaims=array();
        $rows=$this->cashincentiveclaim
        ->join('cash_incentive_refs', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_claims.cash_incentive_ref_id');
        })
        ->where([['cash_incentive_ref_id','=',request('cash_incentive_ref_id',0)]])
        ->orderBy('cash_incentive_claims.id','desc')
        ->get([
           'cash_incentive_claims.*',
           'cash_incentive_refs.avg_rate'
        ])
        ->map(function($rows){
            $total_net_wgt_qty=$rows->net_wgt_exp_qty*$rows->avg_rate;
            $knit_charge=$rows->net_wgt_exp_qty*$rows->knitting_charge_per_kg;
            $dye_charge=$rows->net_wgt_exp_qty*$rows->dyeing_charge_per_kg;

            $rows->exp_date=date('d-M-Y',strtotime($rows->exp_date));
            $rows->bl_date=date('d-M-Y',strtotime($rows->bl_date));
            $rows->invoice_qty=number_format($rows->invoice_qty,2);
            $rows->invoice_amount=number_format($rows->invoice_amount,2);
            $rows->net_wgt_exp_qty=number_format($rows->net_wgt_exp_qty,2);
            $rows->realized_amount=number_format($rows->realized_amount,2);
            $rows->cost_of_export=number_format($rows->cost_of_export,2);
            $rows->freight=number_format($rows->freight,2);
            $rows->net_realized_amount=number_format($rows->net_realized_amount,2);
            $rows->claim_amount=number_format($rows->claim_amount,2);
            $rows->local_cur_amount=number_format($rows->local_cur_amount,2);
            $rows->exch_rate=number_format($rows->exch_rate,2);

            $rows->total_net_wgt_qty=number_format($total_net_wgt_qty,2);
            $rows->knit_charge=number_format($knit_charge,2);
            $rows->dye_charge=number_format($dye_charge,2);
            return $rows;
        });
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashIncentiveClaimRequest $request) {
    	$realized_year=date('Y',strtotime($request->realized_date));
        $request->request->add(['realized_year' => $realized_year]);
        $cashincentiveclaim=$this->cashincentiveclaim->create($request->except(['id','avg_rate','process_loss_per']));
        if($cashincentiveclaim){
            return response()->json(array('success' => true,'id' =>  $cashincentiveclaim->id,'message' => 'Save Successfully'),200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $claim = $this->cashincentiveclaim->find($id);
        $cashincentiverefid=$this->cashincentiveref->find($claim->cash_incentive_ref_id);
       $cashincentiveclaim = $this->cashincentiveclaim
        ->join('cash_incentive_refs', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_claims.cash_incentive_ref_id');
        })
        ->join('exp_doc_sub_invoices', function($join)  {
            $join->on('exp_doc_sub_invoices.id', '=', 'cash_incentive_claims.exp_doc_sub_invoice_id');
        })
        ->join('exp_doc_submissions', function($join)  {
            $join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
        })
        ->join('exp_invoices',function($join){
            $join->on('exp_invoices.exp_lc_sc_id', '=', 'exp_doc_submissions.exp_lc_sc_id');
            $join->on('exp_doc_sub_invoices.exp_invoice_id','=','exp_invoices.id');
        })
        ->join('exp_lc_scs', function($join)  {
            $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
        })
        ->leftJoin(\DB::raw("(
            select
            keycontrols.company_id,
            sum(keycontrol_parameters.value) as process_loss_per
            from
            keycontrols
            join keycontrol_parameters on keycontrols.id=keycontrol_parameters.keycontrol_id 
            where keycontrol_parameters.parameter_id=15
            and '".$cashincentiverefid->claim_sub_date."' 
            between keycontrol_parameters.from_date and keycontrol_parameters.to_date
            group by keycontrols.company_id
        ) keycontrol"), "keycontrol.company_id", "=", "exp_lc_scs.beneficiary_id")
       ->where([['cash_incentive_claims.id','=',$id]])
       ->get([
           'cash_incentive_claims.*',
           'exp_doc_sub_invoices.id as exp_doc_sub_invoice_id',
           'cash_incentive_refs.avg_rate',
           'keycontrol.process_loss_per'
           //'exp_invoices.invoice_no',
           //'exp_doc_submissions.bank_ref_bill_no as bank_bill_no'
       ])
       ->first();
       //dd($cashincentiverefid);die;
       //$cashincentiveclaim->realized_year=date('Y',strtotime($cashincentiveclaim->realized_date));
       $row ['fromData'] = $cashincentiveclaim;
       $dropdown['att'] = '';
       $row ['dropDown'] = $dropdown;
       echo json_encode($row); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CashIncentiveClaimRequest $request, $id) {
    	$realized_year=date('Y',strtotime($request->realized_date));
        $request->request->add(['realized_year' =>$realized_year]);
        $cashincentiveclaim=$this->cashincentiveclaim->update($id,$request->except(['id','avg_rate','process_loss_per']));
        if($cashincentiveclaim){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        /*if($this->cashincentiveclaim->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }*/
        if($this->cashincentiveclaim->where([['id','=',$id]])->forceDelete()){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getDocInvoice(){
        //echo "mmmmm"; die;
        $cashincentiverefid=$this->cashincentiveref->find(request('cashincentiverefid',0));
        $explcscid=$this->explcsc->find($cashincentiverefid->exp_lc_sc_id);
        //dd($cashincentiverefid['exp_lc_sc_id']);
        $impdocaccept=$this->expdocsubmission
        ->selectRaw('
            exp_invoices.id as exp_invoice_id,
            exp_invoices.invoice_no,
            exp_invoices.invoice_date,
            exp_invoices.invoice_value,
            exp_invoices.discount_amount,
            exp_invoices.bonus_amount,
            exp_invoices.claim_amount,
            exp_invoices.commission,
            exp_invoices.net_inv_value,
            exp_invoices.invoice_qty,
            exp_invoices.bl_cargo_no,
            exp_invoices.exp_form_no,
            exp_invoices.exp_form_date,
            exp_invoices.bl_cargo_date,
            exp_invoices.net_wgt_exp_qty,
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.file_no,
            exp_doc_submissions.bank_ref_bill_no,
            exp_doc_submissions.exp_lc_sc_id,
            exp_doc_sub_invoices.id as exp_doc_sub_invoice_id,
            keycontrol.process_loss_per
        ')
        ->join('exp_doc_sub_invoices',function($join){
          $join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
          $join->whereNull('exp_doc_sub_invoices.deleted_at');
        })
        ->join('exp_invoices', function($join)  {
            $join->on('exp_doc_sub_invoices.exp_invoice_id', '=', 'exp_invoices.id');
        })
        ->join('exp_lc_scs', function($join)  {
            $join->on('exp_lc_scs.id', '=', 'exp_doc_submissions.exp_lc_sc_id');
        })
        ->leftJoin(\DB::raw("(
            select
            keycontrols.company_id,
            sum(keycontrol_parameters.value) as process_loss_per
            from
            keycontrols
            join keycontrol_parameters on keycontrols.id=keycontrol_parameters.keycontrol_id 
            where keycontrol_parameters.parameter_id=15
            and '".$cashincentiverefid->claim_sub_date."' 
            between keycontrol_parameters.from_date and keycontrol_parameters.to_date
            group by keycontrols.company_id
        ) keycontrol"), "keycontrol.company_id", "=", "exp_lc_scs.beneficiary_id")
        ->where([['exp_doc_submissions.exp_lc_sc_id','=',$cashincentiverefid->exp_lc_sc_id]])
        //->where([['exp_lc_scs.file_no','=',$explcscid->file_no]])
        ->when(request('bank_ref_bill_no'), function ($q) {
            return $q->where('exp_doc_submissions.bank_ref_bill_no', 'LIKE', "%".request('bank_ref_bill_no', 0)."%");
        })
        ->when(request('invoice_no'), function ($q) {
            return $q->where('exp_invoices.invoice_no', 'LIKE', "%".request('invoice_no', 0)."%");
        })
        ->get()
        ->map(function ($impdocaccept) use($cashincentiverefid){
            $impdocaccept->rate=$impdocaccept->invoice_value/$impdocaccept->invoice_qty;
            $impdocaccept->u_invoice_value=$impdocaccept->net_inv_value;
            $impdocaccept->ship_date=date('d-M-y',strtotime($impdocaccept->ship_date));
            $impdocaccept->invoice_value=number_format($impdocaccept->invoice_value,2);
            $impdocaccept->discount_amount=number_format($impdocaccept->discount_amount,2);
            $impdocaccept->bonus_amount=number_format($impdocaccept->bonus_amount,2);
            $impdocaccept->claim_amount=number_format($impdocaccept->claim_amount,2);
            $impdocaccept->commission=number_format($impdocaccept->commission,2);
            $impdocaccept->net_inv_value=number_format($impdocaccept->net_inv_value,2);
            $impdocaccept->avg_rate=$cashincentiverefid->avg_rate;
            return $impdocaccept;
        });
        
        // $saved = $impdocaccept->filter(function ($value) {
        //     if($value->exp_doc_sub_invoice_id){
        //         return $value;
        //     }
        // })->values();
        echo json_encode($impdocaccept);
    }
}
