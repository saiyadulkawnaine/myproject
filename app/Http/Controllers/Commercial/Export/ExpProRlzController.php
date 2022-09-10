<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzRepository;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzDeductRepository;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzAmountRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Account\AccTermLoanInstallmentRepository;
use App\Repositories\Contracts\Account\AccTermLoanPaymentRepository;
use App\Repositories\Contracts\Commercial\Export\ExpPreCreditRepository;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubTransectionRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpProRlzRequest;

class ExpProRlzController extends Controller {

    private $explcsc;
    private $expprorlz;
    private $expprorlzdeduct;
    private $expprorlzamount;
    private $currency;
    private $buyer;
    private $company;
    private $expdocsubmission;
    private $commercialhead;
    private $acctermloaninstallment;
    private $acctermloanpayment;
    private $expprecredit;
    private $expdocsubtransection;
    

    public function __construct(
        ExpProRlzRepository $expprorlz,
        ExpProRlzDeductRepository $expprorlzdeduct,
        ExpProRlzAmountRepository $expprorlzamount,
        ExpLcScRepository $explcsc,
        CurrencyRepository $currency,
        BuyerRepository $buyer,
        CompanyRepository $company, 
        ExpDocSubmissionRepository $expdocsubmission,
        CommercialHeadRepository $commercialhead,
        AccTermLoanInstallmentRepository $acctermloaninstallment,
        AccTermLoanPaymentRepository $acctermloanpayment,
        ExpPreCreditRepository $expprecredit,
        ExpDocSubTransectionRepository $expdocsubtransection
    ) {
        $this->explcsc = $explcsc;
        $this->expprorlz = $expprorlz;
        $this->expprorlzdeduct = $expprorlzdeduct;
        $this->expprorlzamount = $expprorlzamount;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->expdocsubmission = $expdocsubmission;
        $this->commercialhead = $commercialhead;
        $this->acctermloaninstallment = $acctermloaninstallment;
        $this->acctermloanpayment = $acctermloanpayment;
        $this->expprecredit = $expprecredit;
        $this->expdocsubtransection = $expdocsubtransection;

        $this->middleware('auth');

        $this->middleware('permission:view.expprorlzs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.expprorlzs', ['only' => ['store']]);
        $this->middleware('permission:edit.expprorlzs',   ['only' => ['update']]);
        $this->middleware('permission:delete.expprorlzs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        /*$company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'code','id'),'-Select-','');
         
        $expprorlzs=array();
        $rows=$this->expprorlz
            ->join('exp_lc_scs',function($join){
                $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
            })
            ->join('buyers',function($join){
                $join->on('buyers.id','=','exp_lc_scs.buyer_id');
            })
            ->join('companies', function($join)  {
                $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
            })
            ->join('currencies',function($join){
                $join->on('currencies.id','=','exp_lc_scs.currency_id');
            })
            ->join('exp_doc_submissions',function($join){
                $join->on('exp_doc_submissions.id','=','exp_pro_rlzs.exp_doc_submission_id');
            })
        ->get();

        foreach($rows as $row){
            $expprorlz['id']=$row->id;
            $expprorlz['realized_fdbc_no']=$row->realized_fdbc_no;
            $expprorlz['exp_lc_sc_id']=$row->exp_lc_sc_id;
            $expprorlz['lc_sc_no']=$row->lc_sc_no;
            $expprorlz['beneficiary']=$company[$row->beneficiary_id];//combo
            $expprorlz['buyer']=$buyer[$row->buyer_id];
            $expprorlz['realized_date']=date('Y-m-d',strtotime($row->realized_date));
            $expprorlz['fdbc_no']=$row->fdbc_no;
            $expprorlz['remarks']=$row->remarks;
            
            array_push($expprorlzs,$expprorlz);
        }
        echo json_encode($expprorlzs);*/

        $submissiontype=array_prepend(config('bprs.submissiontype'), '','');
        $rows=$this->expdocsubmission
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->join('exp_pro_rlzs',function($join){
            $join->on('exp_pro_rlzs.exp_doc_submission_id','=','exp_doc_submissions.id');
        })
        ->leftJoin(\DB::raw("(SELECT exp_doc_submissions.id,sum(exp_invoice_orders.amount) as bank_ref_amount FROM exp_doc_submissions  right join exp_doc_sub_invoices on exp_doc_sub_invoices.exp_doc_submission_id = exp_doc_submissions.id right join exp_invoices on exp_invoices.id = exp_doc_sub_invoices.exp_invoice_id right join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id where exp_doc_sub_invoices.deleted_at is null and exp_invoice_orders.deleted_at is null  group by exp_doc_submissions.id) Docsub"), "Docsub.id", "=", "exp_doc_submissions.id")
        ->leftJoin(\DB::raw("(SELECT 
            exp_doc_submissions.id,
            sum(exp_invoices.net_inv_value) as bank_ref_amount 
            FROM exp_doc_submissions   
            join exp_doc_sub_invoices on exp_doc_sub_invoices.exp_doc_submission_id = exp_doc_submissions.id 
            join exp_invoices on exp_invoices.id = exp_doc_sub_invoices.exp_invoice_id  
            where 
            exp_doc_sub_invoices.deleted_at is null 
            group by exp_doc_submissions.id
            ) netinvval"), "netinvval.id", "=", "exp_doc_submissions.id")

        ->leftJoin(\DB::raw("(SELECT exp_doc_submissions.id,sum(exp_doc_sub_transections.doc_value) as negotiated_amount FROM exp_doc_submissions  right join exp_doc_sub_transections on exp_doc_sub_transections.exp_doc_submission_id = exp_doc_submissions.id  where exp_doc_sub_transections.deleted_at is null  group by exp_doc_submissions.id) DocsubTra"), "DocsubTra.id", "=", "exp_doc_submissions.id")
        ->when(request('bank_ref_bill_no'), function ($q) {
            return $q->where('exp_doc_submissions.bank_ref_bill_no', 'LIKE', "%".request('bank_ref_bill_no', 0)."%");
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('exp_doc_submissions.bank_ref_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('exp_doc_submissions.bank_ref_date', '<=',request('date_to', 0));
        })
        ->get([            
            'exp_doc_submissions.id as exp_doc_submission_id',
            'exp_doc_submissions.bank_ref_bill_no',
            'exp_doc_submissions.bank_ref_date',
            'exp_doc_submissions.courier_recpt_no',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id',
            'exp_lc_scs.buyers_bank',
            'exp_lc_scs.currency_id',
            'exp_pro_rlzs.id',
            'exp_pro_rlzs.realization_date',
            'exp_pro_rlzs.remarks',
            'currencies.name as currency_id',
            'netinvval.bank_ref_amount',
            'DocsubTra.negotiated_amount'
        ])
        ->map(function ($rows){
            $rows->bank_ref_date=date('d-M-y',strtotime($rows->bank_ref_date));
            $rows->realization_date=date('d-M-y',strtotime($rows->realization_date));
            return $rows;
        });
        echo json_encode ($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');

        return Template::LoadView('Commercial.Export.ExpProRlz',['company'=>$company,'currency'=>$currency,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpProRlzRequest $request) {
        //return response()->json(array('success' => false,'message' => 'Save is locked temporarily'),200);
        \DB::beginTransaction();
        $expprorlz=$this->expprorlz->create(['exp_doc_submission_id'=>$request->exp_doc_submission_id,'realization_date'=>$request->realization_date,'remarks'=>$request->remarks]);
        try
        {
            if($request->exists('commercial_head_id'))
            {
                foreach($request->commercial_head_id as $index=>$commercial_head_id)
                {
                $this->expprorlzdeduct->create(['exp_pro_rlz_id'=>$expprorlz->id,'commercial_head_id'=>$request->commercial_head_id[$index],'doc_value'=>$request->doc_value[$index],'exch_rate'=>$request->exch_rate[$index],'dom_value'=>$request->dom_value[$index]]);
                }
            }
            
            foreach($request->a_commercial_head_id as $index=>$a_commercial_head_id)
            {
                $commercialhead=$this->commercialhead->find($a_commercial_head_id);
                $commercialhead_type_id=$commercialhead->commercialhead_type_id;
                if(($commercialhead_type_id==4 || $commercialhead_type_id==5 || $commercialhead_type_id==6) &&  ($request->a_ac_loan_id[$index]=='' || $request->a_ac_loan_id[$index]==0)){
                    \DB::rollback();
                    return response()->json(array('success' => false,'message' => 'Please select a loan'),200);

                }
                
                $acc_term_loan_payment_id=null;

                if(($commercialhead_type_id==4)){

                    $loan=$this->expdocsubtransection->find($request->a_ac_loan_id[$index]);
                    $acctermloaninstallment=$this->acctermloaninstallment->where([['acc_term_loan_id','=',$loan->acc_term_loan_id]])->get()->first();
                    //$this->acctermloanpayment->where([['id','=',$request->a_acc_term_loan_payment_id[$index]]])->delete();

                    $acctermloanpayment=$this->acctermloanpayment->create([
                    'acc_term_loan_installment_id'=>$acctermloaninstallment->id,
                    'payment_date'=>$request->realization_date,
                    'interest_amount'=>0,
                    'amount'=>$request->a_dom_value[$index],
                    'other_charge_amount'=>0,
                    'delay_charge_amount'=>0,
                    ]);
                    $acc_term_loan_payment_id=$acctermloanpayment->id;
                }

                if(($commercialhead_type_id==5 || $commercialhead_type_id==6)){

                    $loan=$this->expprecredit->find($request->a_ac_loan_id[$index]);
                    $acctermloaninstallment=$this->acctermloaninstallment->where([['acc_term_loan_id','=',$loan->acc_term_loan_id]])->get()->first();
                    //$this->acctermloanpayment->where([['id','=',$request->a_acc_term_loan_payment_id[$index]]])->delete();

                    $acctermloanpayment=$this->acctermloanpayment->create([
                    'acc_term_loan_installment_id'=>$acctermloaninstallment->id,
                    'payment_date'=>$request->realization_date,
                    'interest_amount'=>0,
                    'amount'=>$request->a_dom_value[$index],
                    'other_charge_amount'=>0,
                    'delay_charge_amount'=>0,
                    ]);
                    $acc_term_loan_payment_id=$acctermloanpayment->id;
                }
                $this->expprorlzamount->create([
                    'exp_pro_rlz_id'=>$expprorlz->id,
                    'commercial_head_id'=>$request->a_commercial_head_id[$index],
                    'doc_value'=>$request->a_doc_value[$index],
                    'exch_rate'=>$request->a_exch_rate[$index],
                    'dom_value'=>$request->a_dom_value[$index],
                    'ac_loan_id'=>$request->a_ac_loan_id[$index],
                    'ac_loan_no'=>$request->a_ac_loan_no[$index],
                    'acc_term_loan_payment_id'=>$acc_term_loan_payment_id,
                ]);
            }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        
        if($expprorlz){
            return response()->json(array('success' => true,'id' =>  $expprorlz->id,'message' => 'Save Successfully'),200);
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
        $expprorlz = $this->expprorlz->find($id);

        $expprorlzdeduct=$this->expprorlzdeduct
        ->join('commercial_heads',function($join){
            $join->on('commercial_heads.id','=','exp_pro_rlz_deducts.commercial_head_id');
        })
        ->where([['exp_pro_rlz_id','=',$id]])
        ->get(['exp_pro_rlz_deducts.*','commercial_heads.name']);

        $expprorlzamount=$this->expprorlzamount
        ->join('commercial_heads',function($join){
            $join->on('commercial_heads.id','=','exp_pro_rlz_amounts.commercial_head_id');
        })
        ->where([['exp_pro_rlz_id','=',$id]])
        ->get(['exp_pro_rlz_amounts.*','commercial_heads.name']);

        $commercialhead=$this->commercialhead->get(['id','name']);

        $row ['fromData'] = $expprorlz;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['expprorlzdeduct'] = $expprorlzdeduct;
        $row ['expprorlzamount'] = $expprorlzamount;
        $row ['head'] = $commercialhead;
        echo json_encode($row); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpProRlzRequest $request, $id) {
        \DB::beginTransaction();
        $this->expprorlzdeduct->where([['exp_pro_rlz_id','=',$id]])->delete();
        $this->expprorlzamount->where([['exp_pro_rlz_id','=',$id]])->delete();
        $expprorlz=$this->expprorlz->update($id,['realization_date'=>$request->realization_date,'remarks'=>$request->remarks]);
        try
        {
            if($request->exists('commercial_head_id'))
            {
                foreach($request->commercial_head_id as $index=>$commercial_head_id)
                {
                    $this->expprorlzdeduct->create([
                        'exp_pro_rlz_id'=>$id,
                        'commercial_head_id'=>$request->commercial_head_id[$index],
                        'doc_value'=>$request->doc_value[$index],
                        'exch_rate'=>$request->exch_rate[$index],
                        'dom_value'=>$request->dom_value[$index],
                    ]);
                }
            }
            foreach($request->a_commercial_head_id as $index=>$a_commercial_head_id)
            {
                $commercialhead=$this->commercialhead->find($a_commercial_head_id);
                $commercialhead_type_id=$commercialhead->commercialhead_type_id;
                if(($commercialhead_type_id==4 || $commercialhead_type_id==5 || $commercialhead_type_id==6) &&  ($request->a_ac_loan_id[$index]=='' || $request->a_ac_loan_id[$index]==0)){
                    \DB::rollback();
                    return response()->json(array('success' => false,'id' =>  $id,'message' => 'Please select a loan'),200);
                }
               
                $acc_term_loan_payment_id=null;

                if(($commercialhead_type_id==4)){

                    $loan=$this->expdocsubtransection->find($request->a_ac_loan_id[$index]);
                    $acctermloaninstallment=$this->acctermloaninstallment->where([['acc_term_loan_id','=',$loan->acc_term_loan_id]])->get()->first();
                    $this->acctermloanpayment->where([['id','=',$request->a_acc_term_loan_payment_id[$index]]])->delete();

                    $acctermloanpayment=$this->acctermloanpayment->create([
                    'acc_term_loan_installment_id'=>$acctermloaninstallment->id,
                    'payment_date'=>$request->realization_date,
                    'interest_amount'=>0,
                    'amount'=>$request->a_dom_value[$index],
                    'other_charge_amount'=>0,
                    'delay_charge_amount'=>0,
                    ]);
                    $acc_term_loan_payment_id=$acctermloanpayment->id;
                }

                if(($commercialhead_type_id==5 || $commercialhead_type_id==6)){

                    $loan=$this->expprecredit->find($request->a_ac_loan_id[$index]);
                    $acctermloaninstallment=$this->acctermloaninstallment->where([['acc_term_loan_id','=',$loan->acc_term_loan_id]])->get()->first();
                    $this->acctermloanpayment->where([['id','=',$request->a_acc_term_loan_payment_id[$index]]])->delete();

                    $acctermloanpayment=$this->acctermloanpayment->create([
                    'acc_term_loan_installment_id'=>$acctermloaninstallment->id,
                    'payment_date'=>$request->realization_date,
                    'interest_amount'=>0,
                    'amount'=>$request->a_dom_value[$index],
                    'other_charge_amount'=>0,
                    'delay_charge_amount'=>0,
                    ]);
                    $acc_term_loan_payment_id=$acctermloanpayment->id;
                }
              
                

                $this->expprorlzamount->create([
                    'exp_pro_rlz_id'=>$id,
                    'commercial_head_id'=>$request->a_commercial_head_id[$index],
                    'doc_value'=>$request->a_doc_value[$index],
                    'exch_rate'=>$request->a_exch_rate[$index],
                    'dom_value'=>$request->a_dom_value[$index],
                    'ac_loan_id'=>$request->a_ac_loan_id[$index],
                    'ac_loan_no'=>$request->a_ac_loan_no[$index],
                    'acc_term_loan_payment_id'=>$acc_term_loan_payment_id,
                ]);
            }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        
        if($expprorlz){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->expprorlz->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
    public function gethead()
    {
        $commercialhead=$this->commercialhead->get(['id','name']);
        $commercial_head_id=$this->commercialhead->where([['commercialhead_type_id','=',4]])->get(['id','name'])->first();
        $rows=$this->expdocsubmission
        ->leftJoin(\DB::raw("(SELECT exp_doc_submissions.id,sum(exp_doc_sub_transections.doc_value) as doc_value,sum(exp_doc_sub_transections.dom_value) as dom_value,avg(exp_doc_sub_transections.exch_rate) as exch_rate  FROM exp_doc_submissions  right join exp_doc_sub_transections on exp_doc_sub_transections.exp_doc_submission_id = exp_doc_submissions.id  where exp_doc_sub_transections.deleted_at is null  group by exp_doc_submissions.id) DocsubTra"), "DocsubTra.id", "=", "exp_doc_submissions.id")
        ->where([['exp_doc_submissions.id','=',request('exp_doc_submission_id',0)]])
        ->get(['DocsubTra.doc_value','DocsubTra.dom_value','DocsubTra.exch_rate'])
        ->map(function ($rows) use($commercial_head_id){
            $rows->commercial_head_id=$commercial_head_id->id;
            $rows->name=$commercial_head_id->name;
            return $rows;
        });
        $head=['head'=>$commercialhead,'expprorlzdeduct'=>[],'expprorlzamount'=>$rows];
        echo json_encode($head);
    }

    public function importdocsubmission(){
        $submissiontype=array_prepend(config('bprs.submissiontype'), '','');
        $rows=$this->expdocsubmission
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->leftJoin('exp_pro_rlzs',function($join){
            $join->on('exp_pro_rlzs.exp_doc_submission_id','=','exp_doc_submissions.id');
        })
        ->leftJoin(\DB::raw("(SELECT exp_doc_submissions.id,sum(exp_invoice_orders.amount) as bank_ref_amount FROM exp_doc_submissions  right join exp_doc_sub_invoices on exp_doc_sub_invoices.exp_doc_submission_id = exp_doc_submissions.id right join exp_invoices on exp_invoices.id = exp_doc_sub_invoices.exp_invoice_id right join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id where exp_doc_sub_invoices.deleted_at is null and exp_invoice_orders.deleted_at is null  group by exp_doc_submissions.id) Docsub"), "Docsub.id", "=", "exp_doc_submissions.id")
        ->leftJoin(\DB::raw("(SELECT 
            exp_doc_submissions.id,
            sum(exp_invoices.net_inv_value) as bank_ref_amount 
            FROM exp_doc_submissions   
            join exp_doc_sub_invoices on exp_doc_sub_invoices.exp_doc_submission_id = exp_doc_submissions.id 
            join exp_invoices on exp_invoices.id = exp_doc_sub_invoices.exp_invoice_id  
            where 
            exp_doc_sub_invoices.deleted_at is null  
            group by exp_doc_submissions.id
            ) netinvval"), "netinvval.id", "=", "exp_doc_submissions.id")
        ->leftJoin(\DB::raw("(SELECT exp_doc_submissions.id,sum(exp_doc_sub_transections.doc_value) as negotiated_amount FROM exp_doc_submissions  right join exp_doc_sub_transections on exp_doc_sub_transections.exp_doc_submission_id = exp_doc_submissions.id  where exp_doc_sub_transections.deleted_at is null  group by exp_doc_submissions.id) DocsubTra"), "DocsubTra.id", "=", "exp_doc_submissions.id")
        ->when(request('bank_ref_bill_no'), function ($q) {
            return $q->where('exp_doc_submissions.bank_ref_bill_no', 'LIKE', "%".request('bank_ref_bill_no', 0)."%");
        })
        ->when(request('date_from'), function ($q) {
			return $q->where('exp_doc_submissions.bank_ref_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
			return $q->where('exp_doc_submissions.bank_ref_date', '<=',request('date_to', 0));
		})
        ->get([            
            'exp_doc_submissions.id as exp_doc_submission_id',
            'exp_doc_submissions.bank_ref_bill_no',
            'exp_doc_submissions.bank_ref_date',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.buyers_bank', 
            'exp_lc_scs.currency_id', 
            'exp_pro_rlzs.id',
            'exp_pro_rlzs.realization_date',
            'exp_pro_rlzs.remarks',
            'currencies.name as currency_id',
            'netinvval.bank_ref_amount',
            'DocsubTra.negotiated_amount'
        ])
        ->map(function ($rows){
            $rows->bank_ref_date=date('d-M-y',strtotime($rows->bank_ref_date));
            return $rows;
        });
       echo json_encode ($rows);
    }

    public function getLoanRef(){

        $exp_doc_submission_id=request('exp_doc_submission_id',0);
        $loan_date_from=request('loan_date_from',0);
        $loan_date_to=request('loan_date_to',0);
        $commercial_head_id=request('commercial_head_id',0);
        $commercialhead=$this->commercialhead->find($commercial_head_id);
        $commercialhead_type_id=$commercialhead->commercialhead_type_id;
        $expdocsubmission=$this->expdocsubmission->find($exp_doc_submission_id);
        $lcsc = collect(\DB::select("
            select 
            exp_lc_scs.exporter_bank_branch_id
            from 
            exp_doc_submissions
            join exp_lc_scs on exp_lc_scs.id=exp_doc_submissions.exp_lc_sc_id
            where exp_doc_submissions.id=".$exp_doc_submission_id."
        "))
        ->first();

        //echo json_encode($commercialhead);
        if($commercialhead_type_id == 4){
            $pcs = collect(\DB::select("
            select 
            exp_doc_sub_transections.id,
            exp_doc_sub_transections.ac_loan_no as loan_no,
            exp_doc_submissions.negotiation_date as loan_date,
            'Doc Purchase' as loan_type,
            exp_doc_sub_transections.dom_value as amount
            from 
            exp_doc_submissions
            join exp_doc_sub_transections on exp_doc_sub_transections.exp_doc_submission_id=exp_doc_submissions.id
            join exp_lc_scs on exp_lc_scs.id=exp_doc_submissions.exp_lc_sc_id
            where exp_lc_scs.exporter_bank_branch_id=".$lcsc->exporter_bank_branch_id."
            "));
            echo json_encode($pcs);
        }
        if($commercialhead_type_id==5 || $commercialhead_type_id==6 ){
            //echo "gg"; die;
            $pcs = collect(\DB::select("
            select 
            exp_pre_credits.id,
            exp_pre_credits.loan_no,
            exp_pre_credits.cr_date as loan_date,
            'PC' as loan_type,
            sum(exp_pre_credit_lc_scs.credit_taken) as amount
            from 
            exp_pre_credits
            join exp_pre_credit_lc_scs on exp_pre_credit_lc_scs.exp_pre_credit_id=exp_pre_credits.id
            join exp_lc_scs on exp_lc_scs.id=exp_pre_credit_lc_scs.exp_lc_sc_id
            where exp_lc_scs.exporter_bank_branch_id=".$lcsc->exporter_bank_branch_id."
            group by 
            exp_pre_credits.id,
            exp_pre_credits.loan_no,
            exp_pre_credits.cr_date
            "));
            echo json_encode($pcs);
        }
    }
}
