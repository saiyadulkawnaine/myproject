<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Commercial\Export\ExpDocSubmissionRequest;
use App\Repositories\Contracts\Util\CommercialHeadRepository;

class ExpDocSubmissionController extends Controller {

    private $expdocsubmission;
    private $company;
    private $buyer;
    private $currency;
    private $explcsc;

    public function __construct(ExpDocSubmissionRepository $expdocsubmission,ExpLcScRepository $explcsc,CompanyRepository $company, BuyerRepository $buyer,CurrencyRepository $currency, CommercialHeadRepository $commercialhead) {

        $this->explcsc = $explcsc;
        $this->expdocsubmission = $expdocsubmission;
        $this->commercialhead = $commercialhead;
        $this->currency = $currency;
        $this->company = $company;
        $this->buyer = $buyer;

    $this->middleware('auth');

    $this->middleware('permission:view.expdocsubmissions',   ['only' => ['create', 'index','show']]);
    $this->middleware('permission:create.expdocsubmissions', ['only' => ['store']]);
    $this->middleware('permission:edit.expdocsubmissions',   ['only' => ['update']]);
    $this->middleware('permission:delete.expdocsubmissions', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {   
        $submissiontype=array_prepend(config('bprs.submissiontype'), '','');      
        $expdocsubmissions=array();
        $rows=$this->expdocsubmission
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
        ->where([['exp_doc_submissions.doc_submitted_to_id',1]])
        ->orderBy('exp_doc_submissions.id','desc')
        ->get([            
            'exp_doc_submissions.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.buyers_bank', 
            'exp_lc_scs.currency_id', 
            'buyers.name as buyer_id',
            'companies.code as beneficiary_id',
            'currencies.name as currency_id',
            ]);
        //->get();
        foreach($rows as $row){
            $expdocsubmission['id']=$row->id;
            $expdocsubmission['exp_lc_sc_id']=$row->exp_lc_sc_id;
            $expdocsubmission['beneficiary_id']=$row->beneficiary_id;
            $expdocsubmission['lc_sc_no']=$row->lc_sc_no;
            $expdocsubmission['submission_date']=($row->submission_date !== null)?date("Y-m-d",strtotime($row->submission_date)):null;
            $expdocsubmission['submission_type_id']=$submissiontype[$row->submission_type_id];
            $expdocsubmission['bank_ref_bill_no']=$row->bank_ref_bill_no;
            $expdocsubmission['negotiation_date']=($row->negotiation_date !== null)?date('Y-m-d',strtotime($row->negotiation_date)):null;
            $expdocsubmission['stuffing_date']=($row->stuffing_date !== null)?date('Y-m-d',strtotime($row->stuffing_date)):null;
            $expdocsubmission['days_to_realize']=$row->days_to_realize;
            $expdocsubmission['possible_realization_date']=($row->possible_realization_date !== null)?date('Y-m-d',strtotime($row->possible_realization_date)):null;
            $expdocsubmission['courier_recpt_no']=$row->courier_recpt_no;
            array_push($expdocsubmissions,$expdocsubmission);
        }
        echo json_encode($expdocsubmissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $submissiontype=array_prepend(config('bprs.submissiontype'), '-Select-','');
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');

        return Template::LoadView('Commercial.Export.ExpDocSubmission',['currency'=>$currency,'commercialhead'=>$commercialhead,'submissiontype'=>$submissiontype,'company'=>$company]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpDocSubmissionRequest $request) {
        $request->request->add(['doc_submitted_to_id' =>1]);
        $expdocsubmission=$this->expdocsubmission->create($request->except(['id','beneficiary_id','buyer_id','currency_id','buyers_bank','lc_sc_no']));
        if($expdocsubmission){
            return response()->json(array('success' => true,'id' =>  $expdocsubmission->id,'message' => 'Save Successfully'),200);
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
       $expdocsubmission = $this->expdocsubmission
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
        ->where([['exp_doc_submissions.id','=',$id]])
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

        $expdocsubmission['submission_date']=($expdocsubmission->submission_date !== null)?date("Y-m-d",strtotime($expdocsubmission->submission_date)):null;
        $expdocsubmission['negotiation_date']=($expdocsubmission->negotiation_date !== null )?date("Y-m-d",strtotime($expdocsubmission->negotiation_date)):null;
        $expdocsubmission['possible_realization_date']=($expdocsubmission->possible_realization_date !== null)?date("Y-m-d",strtotime($expdocsubmission->possible_realization_date)):null;

        $row ['fromData'] = $expdocsubmission;
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
    public function update(ExpDocSubmissionRequest $request, $id) {
        $expdocsubmission=$this->expdocsubmission->update($id,$request->except(['id','beneficiary_id','buyer_id','currency_id','buyers_bank','lc_sc_no']));
        if($expdocsubmission){
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
        if($this->expdocsubmission->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getDocSubLc(){
        
        $rows=$this->explcsc
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
        })
       ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
       ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%".request('lc_sc_no', 0)."%");
        }) 
         ->when(request('beneficiary_id'), function ($q) {
            return $q->where('exp_lc_scs.beneficiary_id', '=', request('beneficiary_id', 0));
        })
        //->groupBy(['exp_lc_scs.id'])
        ->orderBy('exp_lc_scs.id','asc')
        ->get([
            'exp_lc_scs.*',
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id'
        ]);
            //return $rows;
            echo json_encode($rows);
    }

    public function getLatter()
    {
        $id=request('id',0);

        $rows=$this->expdocsubmission
        ->join('exp_lc_scs',function($join){
        $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
        })
        ->leftJoin('bank_branches', function($join){
        $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
        $join->on('banks.id', '=', 'bank_branches.bank_id');
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
        ->where([['exp_doc_submissions.id',$id]])
        ->get([            
        'exp_doc_submissions.*',
        'exp_lc_scs.lc_sc_no',
        'exp_lc_scs.file_no',
        'exp_lc_scs.beneficiary_id',
        'exp_lc_scs.buyer_id', 
        'exp_lc_scs.buyers_bank', 
        'exp_lc_scs.currency_id', 
        'exp_lc_scs.exch_rate', 
        'buyers.name as buyer_id',
        'banks.name as bank_name',
        'bank_branches.branch_name',
        'bank_branches.address as bank_address',
        'bank_branches.contact',
        'companies.name as beneficiary_id',
        'currencies.name as currency_id',
        ])
        ->first();

        $replacablesc=$this->explcsc
        ->where([['exp_lc_scs.file_no','=',$rows->file_no]])
        ->where([['exp_lc_scs.sc_or_lc','=',1]])
        ->whereNull('exp_lc_scs.deleted_at')
        ->whereIn('exp_lc_scs.lc_sc_nature_id',[2])
        ->get([
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.lc_sc_value',
            'exp_lc_scs.local_commission_per',
            'exp_lc_scs.foreign_commission_per',
        ])
        ->map(function($replacablesc){
            $replacablesc->local_commission=$replacablesc->lc_sc_value*($replacablesc->local_commission_per/100);
            $replacablesc->foreign_commission=$replacablesc->lc_sc_value*($replacablesc->foreign_commission_per/100);
            return $replacablesc;
        });

        $direct_lc_sc=$this->explcsc
        ->where([['exp_lc_scs.file_no','=',$rows->file_no]])
        ->whereNull('exp_lc_scs.deleted_at')
        ->whereIn('exp_lc_scs.lc_sc_nature_id',[1])
        ->get([
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.lc_sc_value',
            'exp_lc_scs.local_commission_per',
            'exp_lc_scs.foreign_commission_per',
        ])
        ->map(function($direct_lc_sc){
            $direct_lc_sc->local_commission=$direct_lc_sc->lc_sc_value*($direct_lc_sc->local_commission_per/100);
            $direct_lc_sc->foreign_commission=$direct_lc_sc->lc_sc_value*($direct_lc_sc->foreign_commission_per/100);
            return $direct_lc_sc;
        });

        $rows->local_commission=$direct_lc_sc->sum('local_commission')+$replacablesc->sum('local_commission');
        $rows->foreign_commission=$direct_lc_sc->sum('foreign_commission')+$replacablesc->sum('foreign_commission');
        $rows->freight=0;
        $rows->freight_l_commission_f_commission=$rows->local_commission+$rows->foreign_commission+$rows->freight;

        $rows->net_file_value=($direct_lc_sc->sum('lc_sc_value')+$replacablesc->sum('lc_sc_value'))-$rows->freight_l_commission_f_commission;
        $rows->btb_openable=$rows->net_file_value*(70/100);

        $btbpc=$this->explcsc
        ->selectRaw('
            exp_lc_scs.file_no,
            ExpPC.pc_taken_mount,
            ExpPC.pc_taken_rate,
            BTB.btb_open_amount
        ')
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->leftJoin('bank_branches', function($join) {
            $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join) {
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(imp_backed_exp_lc_scs.amount) as btb_open_amount FROM imp_backed_exp_lc_scs right join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id right join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id    group by exp_lc_scs.file_no) BTB"), "BTB.file_no", "=", "exp_lc_scs.file_no")
        ->leftJoin(\DB::raw("(SELECT 
            exp_lc_scs.file_no,
            sum(exp_pre_credit_lc_scs.equivalent_fc) as pc_taken_mount, 
            min(exp_pre_credits.rate) as pc_taken_rate
            FROM 
            exp_pre_credit_lc_scs 
            join exp_lc_scs on exp_lc_scs.id = exp_pre_credit_lc_scs.exp_lc_sc_id 
            join exp_pre_credits on exp_pre_credits.id = exp_pre_credit_lc_scs.exp_pre_credit_id
            where exp_pre_credit_lc_scs.deleted_at is null 
            group by exp_lc_scs.file_no
        ) ExpPC"), "ExpPC.file_no", "=", "exp_lc_scs.file_no")
        ->where([['exp_lc_scs.file_no','=',$rows->file_no]])
        ->groupBy([
            'exp_lc_scs.file_no',
            'ExpPC.pc_taken_mount',
            'ExpPC.pc_taken_rate',
            'BTB.btb_open_amount'
        ])
        ->get()
        ->first();

        $btbpc->btb_open_amount_per=($btbpc->btb_open_amount/$rows->net_file_value)*100;
        $btbpc->yet_to_btb_open_amount=$rows->btb_openable-$btbpc->btb_open_amount;
        $btbpc->yet_to_btb_open_amount_per=($btbpc->yet_to_btb_open_amount/$rows->net_file_value)*100;
        $btbpc->pc_taken_mount_per=($btbpc->pc_taken_mount/$rows->net_file_value)*100;

        $invoice_detail=$this->expdocsubmission
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
        exp_invoices.freight_by_supplier,
        exp_invoices.freight_by_buyer,  
        exp_lc_scs.lc_sc_no,
        exp_lc_scs.local_commission_per,
        exp_lc_scs.foreign_commission_per,
        exp_lc_scs.sc_or_lc,
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
        ->where([['exp_doc_submissions.id','=',$id]])
        ->get()
        ->map(function($invoice_detail){
            if($invoice_detail->sc_or_lc==1)
            {
              $invoice_detail->sc_or_lc_name='SC'; 
            }
            else{
              $invoice_detail->sc_or_lc_name='LC'; 
            }

            $invoice_detail->invoice_date=date('d-M-Y',strtotime($invoice_detail->invoice_date));
            $invoice_detail->deduction=$invoice_detail->discount_amount+$invoice_detail->bonus_amount+$invoice_detail->claim_amount;
            $invoice_detail->freight=$invoice_detail->freight_by_buyer+$invoice_detail->freight_by_supplier;
            $invoice_detail->local_commission=$invoice_detail->invoice_value*($invoice_detail->local_commission_per/100);
            $invoice_detail->foreign_commission=$invoice_detail->invoice_value*($invoice_detail->foreign_commission_per/100);
            $invoice_detail->net_inv_value=$invoice_detail->invoice_value-($invoice_detail->deduction+$invoice_detail->freight+$invoice_detail->local_commission+ $invoice_detail->foreign_commission);
            return $invoice_detail;

        });

        $rows->total_invoice_value=$invoice_detail->sum('invoice_value');
        $rows->total_invoice_deduction=$invoice_detail->sum('deduction');
        $rows->total_invoice_freight=$invoice_detail->sum('freight');
        $rows->total_invoice_local_commission=$invoice_detail->sum('local_commission');
        $rows->total_invoice_foreign_commission=$invoice_detail->sum('foreign_commission');
        $rows->total_invoice_net_inv_value=$invoice_detail->sum('net_inv_value');

        if($btbpc->btb_open_amount_per>=70){
            $rows->btb_open_amount_per=$btbpc->btb_open_amount_per;
        }
        else{
            $rows->btb_open_amount_per=70;
        }

        $rows->fc_held_bb_lc=$rows->total_invoice_net_inv_value*($rows->btb_open_amount_per/100);
        $rows->erq_account=$rows->total_invoice_net_inv_value*(1/100);

        $rows->packing_credit_invoice=$rows->total_invoice_net_inv_value*(16/100);
        //$rows->salary=$rows->total_invoice_net_inv_value*(10/100);
        $rows->mda_normal=$rows->total_invoice_net_inv_value*(1/100);
        $rows->interest_on_purchase=$rows->total_invoice_net_inv_value*(1/100);
        $rows->source_tax=$rows->total_invoice_net_inv_value*(1/100);
        $rows->frg_bank_charge=$rows->total_invoice_net_inv_value*(3/100);

        //$rows->central_fund=$rows->total_invoice_net_inv_value*(.03/100);
        //$rows->negotiation_commission=500/$rows->exch_rate;
        //$rows->negotiation_commission_per=($rows->negotiation_commission/$rows->total_invoice_net_inv_value)*100;

        $rows->current_account=$rows->total_invoice_net_inv_value-($rows->fc_held_bb_lc+$rows->erq_account+$rows->packing_credit_invoice+$rows->mda_normal+$rows->interest_on_purchase+$rows->source_tax+$rows->frg_bank_charge);

        $rows->current_account_per=($rows->current_account/$rows->total_invoice_net_inv_value)*100;

        //$rows->total=$rows->current_account+($rows->fc_held_bb_lc+$rows->erq_account+$rows->packing_credit_invoice+$rows->salary+$rows->mda_normal+$rows->interest_on_purchase+$rows->source_tax+$rows->central_fund+$rows->negotiation_commission);
      
      $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      
      $pdf->SetFont('helvetica', 'N', 8);
      $sub="Sub : Negotiation Sheet of export document.";

      $view= \View::make('Defult.Commercial.Export.ExpNegotiationSheetPdf',['sub'=>$sub,'master'=>$rows,'replacablesc'=>$replacablesc,'direct_lc_sc'=>$direct_lc_sc,'btbpc'=>$btbpc, 'invoice_detail'=>$invoice_detail]);

      $html_content=$view->render();
      $pdf->SetY(55);
      $pdf->WriteHtml($html_content, true, false,true,false,'');

        $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetX(150);
        $qrc=$rows->bank_name.', VALUE USD '.number_format($rows->total_invoice_net_inv_value,2).", ".$rows->buyer_id;
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 230, 20, 20, $barcodestyle, 'N');
        $pdf->Text(170, 250, 'FAMKAM ERP');
        $pdf->Text(172, 254, 'LC ID :'.$rows->id);

        $pdf->SetFont('helvetica', 'N', 10);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/ExpNegotiationSheetPdf.pdf';
        $pdf->output($filename);
    }

	public function getForwardLetter()
    {
        $id=request('id',0);

        $rows=$this->expdocsubmission
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
        })
        ->leftJoin('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
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
        ->where([['exp_doc_submissions.id',$id]])
        ->get([            
            'exp_doc_submissions.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.lc_sc_date',
            'exp_lc_scs.file_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.buyers_bank', 
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exch_rate', 
            'exp_lc_scs.sc_or_lc', 
            'buyers.name as buyer_id',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'companies.name as beneficiary_id',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
        ])
        ->map(function($rows){
            if($rows->submission_type_id==1){
                $rows->submission_type='Purchase/Collection';
            }
            if($rows->submission_type_id==2){
                $rows->submission_type='Collection';
            }
            if($rows->submission_type_id==3) {
                $rows->submission_type='Endorsement';
            }

            if($rows->sc_or_lc==1)
            {
              $rows->sc_or_lc_name='Sales Cont'; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_or_lc_name='Export LC'; 
            }
            $rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
            return $rows;
        })
        ->first();
        

        $invoice_detail=$this->expdocsubmission
        ->selectRaw('
            exp_invoices.id as exp_invoice_id,
            exp_invoices.invoice_no,
            exp_invoices.invoice_date,
            exp_invoices.invoice_value,
            exp_invoices.commission,
            exp_invoices.discount_amount,
            exp_invoices.bonus_amount,
            exp_invoices.claim_amount,
            exp_invoices.net_inv_value, 
            exp_invoices.gross_wgt_exp_qty,  
            exp_invoices.net_wgt_exp_qty,
            exp_invoices.total_ctn_qty,
            exp_invoices.freight_by_supplier,
            exp_invoices.freight_by_buyer, 
            exp_invoices.up_charge_amount, 
            exp_lc_scs.local_commission_per,
            exp_lc_scs.foreign_commission_per,
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.sc_or_lc,
            exp_lc_scs.lc_sc_date,
            exp_doc_sub_invoices.id as exp_doc_sub_invoice_id,
            cumulatives.cumulative_qty
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
        ->leftJoin(\DB::raw("(
            SELECT 
            exp_invoices.id as exp_invoice_id,
            sum(exp_invoice_orders.qty) as cumulative_qty,
            sum(exp_invoice_orders.amount) as cumulative_amount 
            FROM exp_doc_sub_invoices 
            join exp_invoices on  exp_invoices.id=exp_doc_sub_invoices.exp_invoice_id
            --join exp_pi_orders on exp_pi_orders.id =exp_invoice_orders.exp_pi_order_id 
            join exp_invoice_orders on  exp_invoices.id=exp_invoice_orders.exp_invoice_id 
            
            where exp_invoice_orders.deleted_at is null  
            --and exp_invoices.id IN (5362,5359)
            group by exp_invoices.id
             ) cumulatives"), "cumulatives.exp_invoice_id", "=", "exp_invoices.id")
        ->where([['exp_doc_submissions.id','=',$id]])
        ->get()
        ->map(function($invoice_detail){
            $invoice_detail->lc_sc_date=date('d-M-Y',strtotime($invoice_detail->lc_sc_date));
            $invoice_detail->invoice_date=date('d-M-Y',strtotime($invoice_detail->invoice_date));
            $invoice_detail->deduction=$invoice_detail->discount_amount+$invoice_detail->bonus_amount+$invoice_detail->claim_amount;
            $invoice_detail->freight=$invoice_detail->freight_by_buyer+$invoice_detail->freight_by_supplier;
            $invoice_detail->local_commission=$invoice_detail->invoice_value*($invoice_detail->local_commission_per/100);
            $invoice_detail->foreign_commission=$invoice_detail->invoice_value*($invoice_detail->foreign_commission_per/100);
            $invoice_detail->net_inv_value=$invoice_detail->invoice_value-($invoice_detail->deduction+$invoice_detail->freight+$invoice_detail->local_commission+ $invoice_detail->foreign_commission)+$invoice_detail->up_charge_amount;
            return $invoice_detail;
        });
        $rows->total_invoice_net_inv_value=$invoice_detail->sum('net_inv_value');
        $rows->invoice_qty=$invoice_detail->sum('cumulative_qty');
        $rows->total_ctn=$invoice_detail->sum('total_ctn_qty');

        $arrInvoice=array();
        foreach($invoice_detail as $bar){

            $arrInvoice[$bar->id][$bar->exp_invoice_id]=$bar->invoice_no.", Dated: ".date('d-M-Y',strtotime($bar->invoice_date));
        }

        $rows->invoice_no=implode(", ",$arrInvoice[$rows->exp_invoice_id]);
        $rows->gross_wgt_exp_qty=$invoice_detail->sum('gross_wgt_exp_qty');
        $rows->net_wgt_exp_qty=$invoice_detail->sum('net_wgt_exp_qty');



        $orders=$this->expdocsubmission
        ->join('exp_doc_sub_invoices',function($join){
            $join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
            $join->whereNull('exp_doc_sub_invoices.deleted_at');
        })
        ->join('exp_invoices', function($join)  {
            $join->on('exp_doc_sub_invoices.exp_invoice_id','=','exp_invoices.id');
        })
        ->join('exp_lc_scs', function($join)  {
            $join->on('exp_invoices.exp_lc_sc_id', '=', 'exp_doc_submissions.exp_lc_sc_id');
        })
        ->join('exp_lc_sc_pis', function($join) {
            $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
            //$join->orOn('exp_lc_sc_pis.exp_lc_sc_id','=','exp_rep_lc_scs.replaced_lc_sc_id');
        })
        ->join('exp_pis', function($join)  {
            $join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
        }) 
        ->join('exp_invoice_orders',function($join){ 
            $join->on('exp_invoice_orders.exp_invoice_id','=','exp_invoices.id');
            $join->whereNull('exp_invoice_orders.deleted_at');
        })
        ->join('exp_pi_orders', function($join)  {
            $join->on('exp_invoice_orders.exp_pi_order_id','=','exp_pi_orders.id');
            $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
        })
        ->join('sales_orders', function($join)  {
            $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
        })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
        })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','sales_order_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
            $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->where([['exp_doc_submissions.id','=',$id]])
        ->get([
            'exp_doc_submissions.id',
            'exp_doc_sub_invoices.id as exp_doc_sub_invoice_id',
            'exp_invoices.id as exp_invoice_id',
            'exp_invoice_orders.id as exp_invoice_order_id',
            'exp_pi_orders.id as exp_pi_order_id',
            'sales_orders.id as sales_order_id',
            //'uoms.code as uom_name',
            'item_accounts.id as item_account_id',
            'item_accounts.item_description',
        ]);

        $row=array();
        $dsrows=array();
        foreach($orders as $order){
            $row['id'][]=$order->exp_invoice_id;
            $dsrows[$order->item_account_id]=$order->item_description;
        }
        $doc=array();
        foreach($dsrows as $key=>$item){
            $doc[]=$item;
        }
        $rows->gmt_item=implode(',',$doc);
        //dd(implode(',',$doc));
        //die();
        
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'N', 9);
        
        $sub="Sub : Request for ".$rows['submission_type']." of our documents no: ".$rows['invoice_no']." for ".$rows['currency_code']." ".$rows['currency_symbol'] .$rows['total_invoice_net_inv_value']. " against ".$rows['sc_or_lc_name']." no: ".$rows['lc_sc_no']."; Date:".$rows['lc_sc_date'];

        $body="We have submitted export documents against delivery of ". $rows['invoice_qty'] ." PCS =".$rows['total_ctn']." CTN,  " .$rows['gmt_item']." against ".$rows['sc_or_lc_name']." no: ".$rows['lc_sc_no']." ; Date : ".($rows['lc_sc_date'])." as follows ";

        //$ttp1="Documents no: ".$rows['invoice_no']." for ".$rows['currency_code']." ".$rows['currency_symbol']." ".$rows['total_invoice_net_inv_value'] ;

        $ttp2="Therefore we request you to take neccessary steps to ".$rows['submission_type']." bills as soon as possible.";

        $view= \View::make('Defult.Commercial.Export.ExpDocSubForwardLetterPdf',['rows'=>$rows,'sub'=>$sub,'body'=>$body,/* 'ttp1'=>$ttp1, */'ttp2'=>$ttp2,'invoice_detail'=>$invoice_detail]);
        $html_content=$view->render();
        $pdf->SetY(40);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetX(150);
        //$qrc=$rows->bank_name.', VALUE USD '.number_format($rows->total_invoice_net_inv_value,2).", ".$rows->buyer_id;

        $qrc =  'Document value:'.$rows['currency_symbol'].' '.number_format($rows['total_invoice_net_inv_value'],2).
                ',Garment qty:'.$rows['invoice_qty'].',Carton Qty :'.$rows['total_ctn'].',Submitted for:'.$rows['submission_type'].',Bank:'.$rows['bank_name'].',Buyer:'.$rows['buyer_id'].',Export LC/SC :'.$rows['lc_sc_no'].',Garment Item:'.$rows['gmt_item'].',Gross weight:'.$rows['gross_wgt_exp_qty'].'Net weight :'.$rows['net_wgt_exp_qty'];
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 220, 40, 40, $barcodestyle, 'N');
        $pdf->Text(170, 244, 'File No:'.$rows['file_no']);
        $pdf->Text(170, 247, 'Sub ID :'.$id);

        $pdf->SetFont('helvetica', 'N', 10);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/ExpDocSubForwardLetterPdf.pdf';
        $pdf->output($filename);
    }

    
    public function getBoe(){
        $payterm = array_prepend(config('bprs.payterm'), '','');

        $id=request('id',0);

        $rows=$this->expdocsubmission
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
        })
        ->leftJoin('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
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
        ->where([['exp_doc_submissions.id',$id]])
        ->get([            
            'exp_doc_submissions.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.lc_sc_date',
            'exp_lc_scs.file_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.buyers_bank', 
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exch_rate', 
            'exp_lc_scs.sc_or_lc',
            'exp_lc_scs.pay_term_id',
            'exp_lc_scs.tenor',
            'buyers.name as buyer_id',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'companies.name as company_name',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_name',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
        ])
        ->map(function($rows) use ($payterm) {

            if ($rows->pay_term_id==1) {
                $rows->pay_term="Usance ".$rows->tenor." days sight";
            }else {
                $rows->pay_term=$payterm[$rows->pay_term_id];
            }
            if($rows->submission_type_id==1){
                $rows->submission_type_id='Purchase/Collection';
            }else{
                $rows->submission_type_id='Collection';
            }
            if($rows->sc_or_lc==1)
            {
              $rows->sc_or_lc_name='Sales Contract'; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_or_lc_name='Export LC'; 
            }
            $rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
            return $rows;
        })
        ->first();
        $invoice_detail=$this->expdocsubmission
        ->selectRaw('
            exp_invoices.id as exp_invoice_id,
            exp_invoices.invoice_no,
            exp_invoices.invoice_date,
            exp_invoices.invoice_value,
            exp_invoices.commission,
            exp_invoices.discount_amount,
            exp_invoices.bonus_amount,
            exp_invoices.claim_amount,
            exp_invoices.net_inv_value, 
            exp_invoices.gross_wgt_exp_qty,  
            exp_invoices.net_wgt_exp_qty,
            exp_invoices.total_ctn_qty,
            exp_invoices.freight_by_supplier,
            exp_invoices.freight_by_buyer,
            exp_invoices.up_charge_amount,
            exp_lc_scs.local_commission_per,
            exp_lc_scs.foreign_commission_per,
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.sc_or_lc,
            exp_lc_scs.lc_sc_date,
            exp_lc_scs.pay_term_id,
            exp_doc_sub_invoices.id as exp_doc_sub_invoice_id,
            cumulatives.cumulative_qty
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
        ->leftJoin(\DB::raw("(
            SELECT 
            exp_invoices.id as exp_invoice_id,
            sum(exp_invoice_orders.qty) as cumulative_qty,
            sum(exp_invoice_orders.amount) as cumulative_amount 
            FROM exp_doc_sub_invoices 
            join exp_invoices on  exp_invoices.id=exp_doc_sub_invoices.exp_invoice_id
            --join exp_pi_orders on exp_pi_orders.id =exp_invoice_orders.exp_pi_order_id 
            join exp_invoice_orders on  exp_invoices.id=exp_invoice_orders.exp_invoice_id 
            
            where exp_invoice_orders.deleted_at is null  
            --and exp_invoices.id IN (5362,5359)
            group by exp_invoices.id
             ) cumulatives"), "cumulatives.exp_invoice_id", "=", "exp_invoices.id")
        ->where([['exp_doc_submissions.id','=',$id]])
        ->get()
        ->map(function($invoice_detail){
            $invoice_detail->lc_sc_date=date('d-M-Y',strtotime($invoice_detail->lc_sc_date));
            $invoice_detail->invoice_date=date('d-M-Y',strtotime($invoice_detail->invoice_date));
            $invoice_detail->deduction=$invoice_detail->discount_amount+$invoice_detail->bonus_amount+$invoice_detail->claim_amount;
            $invoice_detail->freight=$invoice_detail->freight_by_buyer+$invoice_detail->freight_by_supplier;
            $invoice_detail->local_commission=$invoice_detail->invoice_value*($invoice_detail->local_commission_per/100);
            $invoice_detail->foreign_commission=$invoice_detail->invoice_value*($invoice_detail->foreign_commission_per/100);
            $invoice_detail->net_inv_value=$invoice_detail->invoice_value-($invoice_detail->deduction+$invoice_detail->freight+$invoice_detail->local_commission+$invoice_detail->foreign_commission)+$invoice_detail->up_charge_amount;
            return $invoice_detail;
        });
        $rows->total_invoice_net_inv_value=$invoice_detail->sum('net_inv_value');
        $rows->invoice_qty=$invoice_detail->sum('cumulative_qty');
        $rows->total_ctn=$invoice_detail->sum('total_ctn_qty');

        $arrInvoice=array();
        foreach($invoice_detail as $bar){
            $arrInvoice[$bar->id][$bar->exp_invoice_id]=$bar->invoice_no.", Dated: ".date('d-M-Y',strtotime($bar->invoice_date));
        }

        $rows->invoice_no=implode(", ",$arrInvoice[$rows->exp_invoice_id]);
        $amount=$rows->total_invoice_net_inv_value;
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
        $rows->inword=$inword;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->AddPage();
        $pdf->SetY(15);
        $image_file ='images/logo/'.$rows['logo'];
        $pdf->Image($image_file, 90, 10, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(18);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 21, $rows['company_address']);
        $pdf->Cell(0, 40, $rows['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        $pdf->SetY(25);
        
        $pdf->SetFont('helvetica', 'N', 10);
        //$pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.Export.BillOfExchangePdf',['rows'=>$rows,'image_file'=>$image_file]);
        $html_content=$view->render();
        $pdf->SetY(45);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/BillOfExchangePdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }
}
