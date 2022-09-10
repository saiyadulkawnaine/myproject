<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubInvoiceRepository;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzRepository;

use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpDocSubInvoiceRequest;

class ExpDocSubInvoiceController extends Controller {

    private $explcsc;
    private $expdocsubmission;
    private $expdocsubinvoice;
    private $expprorlz;
    

    public function __construct(ExpDocSubmissionRepository $expdocsubmission, ExpDocSubInvoiceRepository $expdocsubinvoice,ExpLcScRepository $explcsc,
        ExpProRlzRepository $expprorlz) {

        $this->explcsc = $explcsc;
        $this->expdocsubmission = $expdocsubmission;
        $this->expdocsubinvoice = $expdocsubinvoice;
        $this->expprorlz = $expprorlz;
        

        $this->middleware('auth');
        $this->middleware('permission:view.expdocsubinvoices',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.expdocsubinvoices', ['only' => ['store']]);
        $this->middleware('permission:edit.expdocsubinvoices',   ['only' => ['update']]);
        $this->middleware('permission:delete.expdocsubinvoices', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {         
        $expdocsubinvoices=array();
        $rows=$this->expdocsubinvoice
        ->join('exp_invoices',function($join){
            $join->on('exp_lc_scs.id','=','exp_invoices.exp_lc_sc_id');
        })
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
        ->where([['exp_doc_submission_id',request('exp_doc_submission_id',0)]])
        ->get([            
            'exp_doc_submissions.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.buyers_bank', 
            'exp_lc_scs.currency_id', 
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id',
            ]);
        //->get();
        foreach($rows as $row){
            $expdocsubinvoice['id']=$row->id;
            $expdocsubinvoice['exp_doc_submission_id']=$row->exp_doc_submission_id;
            $expdocsubinvoice['lc_sc_no']=$row->lc_sc_no;
            $expdocsubinvoice['submission_date']=($row->submission_date !== null)?date("Y-m-d",strtotime($row->submission_date)):null;
            $expdocsubinvoice['submission_type_id']=$row->submission_type_id;
            $expdocsubinvoice['bank_ref_bill_no']=$row->bank_ref_bill_no;
            $expdocsubinvoice['negotiation_date']=($row->negotiation_date !== null)?date('Y-m-d',strtotime($row->negotiation_date)):null;
            $expdocsubinvoice['days_to_realize']=$row->days_to_realize;
            $expdocsubinvoice['possible_realization_date']=($row->possible_realization_date !== null)?date('Y-m-d',strtotime($row->possible_realization_date)):null;
            $expdocsubinvoice['courier_recpt_no']=$row->courier_recpt_no;
            array_push($expdocsubinvoices,$expdocsubinvoice);
        }
        echo json_encode($expdocsubinvoices);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
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
        exp_invoices.bl_cargo_no,
        exp_lc_scs.lc_sc_no,
        exp_doc_sub_invoices.id as exp_doc_sub_invoice_id
        ')
        ->join('exp_invoices', function($join)  {
        $join->on('exp_invoices.exp_lc_sc_id', '=', 'exp_doc_submissions.exp_lc_sc_id');
        })
        ->join('exp_lc_scs', function($join)  {
        $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
        })
        ->join('exp_doc_sub_invoices',function($join){
          $join->on('exp_doc_sub_invoices.exp_invoice_id','=','exp_invoices.id');
          $join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
          $join->whereNull('exp_doc_sub_invoices.deleted_at');
        })
        //->where([['exp_invoices.invoice_status_id','=',2]])
        ->where([['exp_doc_submissions.id','=',request('exp_doc_submission_id',0)]])
        ->get()
        ->map(function ($impdocaccept){
        $impdocaccept->ship_date=date('d-M-y',strtotime($impdocaccept->ship_date));
        $impdocaccept->invoice_value=number_format($impdocaccept->invoice_value,2);
        $impdocaccept->discount_amount=number_format($impdocaccept->discount_amount,2);
        $impdocaccept->bonus_amount=number_format($impdocaccept->bonus_amount,2);
        $impdocaccept->claim_amount=number_format($impdocaccept->claim_amount,2);
        $impdocaccept->commission=number_format($impdocaccept->commission,2);
        $impdocaccept->net_inv_value=number_format($impdocaccept->net_inv_value,2);
        return $impdocaccept;
        });

        $saved = $impdocaccept->filter(function ($value) {
            if($value->exp_doc_sub_invoice_id){
                return $value;
            }
        })->values();

        $impdocacceptnew=$this->expdocsubmission
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
        exp_invoices.bl_cargo_no,
        exp_lc_scs.lc_sc_no,
        exp_doc_sub_invoices.id as exp_doc_sub_invoice_id
        ')
        ->join('exp_invoices', function($join)  {
        $join->on('exp_invoices.exp_lc_sc_id', '=', 'exp_doc_submissions.exp_lc_sc_id');
        })
        ->join('exp_lc_scs', function($join)  {
        $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
        })
        ->leftJoin('exp_doc_sub_invoices',function($join){
          $join->on('exp_doc_sub_invoices.exp_invoice_id','=','exp_invoices.id');
          //$join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
          $join->whereNull('exp_doc_sub_invoices.deleted_at');
        })
        ->where([['exp_invoices.invoice_status_id','=',2]])
        ->where([['exp_doc_submissions.id','=',request('exp_doc_submission_id',0)]])
        ->get()
        ->map(function ($impdocacceptnew){
        $impdocacceptnew->ship_date=date('d-M-y',strtotime($impdocacceptnew->ship_date));
        $impdocacceptnew->invoice_value=number_format($impdocacceptnew->invoice_value,2);
        $impdocacceptnew->discount_amount=number_format($impdocacceptnew->discount_amount,2);
        $impdocacceptnew->bonus_amount=number_format($impdocacceptnew->bonus_amount,2);
        $impdocacceptnew->claim_amount=number_format($impdocacceptnew->claim_amount,2);
        $impdocacceptnew->commission=number_format($impdocacceptnew->commission,2);
        $impdocacceptnew->net_inv_value=number_format($impdocacceptnew->net_inv_value,2);
        return $impdocacceptnew;
        });
        $new = $impdocacceptnew->filter(function ($value) {
            if(!$value->exp_doc_sub_invoice_id)
            {
                return $value;
            }
        })
        ->values();
        echo json_encode(array('new'=>$new,'saved'=>$saved));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpDocSubInvoiceRequest $request) {
        

        foreach($request->exp_invoice_id as $index=>$exp_invoice_id){
            if($exp_invoice_id)
            {
                $expdocsubinvoice = $this->expdocsubinvoice->create(
                ['exp_invoice_id' => $exp_invoice_id,'exp_doc_submission_id' => $request->exp_doc_submission_id]);
            }
        }
        if($expdocsubinvoice){
            return response()->json(array('success' => true,'id' =>  $expdocsubinvoice->id,'message' => 'Save Successfully'),200);
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
       $expdocsubinvoice = $this->expdocsubinvoice
        ->join('exp_lc_scs',function($join){
                $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
            })
        ->join('buyers',function($join){
                $join->on('buyers.id','=','exp_lc_scs.buyer_id');
            })
        ->join('currencies',function($join){
                $join->on('currencies.id','=','exp_lc_scs.currency_id');
            })
            ->where([['exp_doc_sub_invoice.id','=',$id]])
            ->get([
                'exp_doc_submissions.*',
                'exp_lc_scs.id as exp_lc_sc_id',
                'exp_lc_scs.lc_sc_no',
                'exp_lc_scs.beneficiary_id',
                'exp_lc_scs.buyer_id', 
                'exp_lc_scs.buyers_bank', 
                'exp_lc_scs.currency_id', 
                'buyers.name as buyer_id',
                'companies.name as beneficiary_id',
                'currencies.name as currency_id'
            ])
        ->first();
       
        $row ['fromData'] = $expdocsubinvoice;
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
    public function update(ExpDocSubInvoiceRequest $request, $id) {
          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $docinvoice=$this->expdocsubinvoice->find($id);
        $expprorlz = $this->expprorlz
        ->where([['exp_doc_submission_id','=',$docinvoice->exp_doc_submission_id]])
        ->get()
        ->first();
       // dd($expprorlz);
        if ($expprorlz) {
            return response()->json(array('success' => false,'message' => 'Document Realized. Delete Unsuccessfully'),200);
        }
        
        if($this->expdocsubinvoice->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getInvoiceDetails(){
        

        $rows=$this->explcsc
        ->join('companies',function($join){
            $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
        })
       ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
       ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        /* ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_no', '=', request('lc_sc_no', 0));
        }) */
        //->groupBy(['exp_lc_scs.id'])
        ->orderBy('exp_lc_scs.id','asc')
        ->get([
            'exp_lc_scs.*',
           /* 'exp_lc_scs.lc_sc_no as exp_lc_sc_id',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.buyers_bank', 
            'exp_lc_scs.currency_id', */
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id'
        ]);
            echo json_encode($rows);
    }
}
