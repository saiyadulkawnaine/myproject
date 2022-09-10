<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubBankRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubAcceptRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpDocSubBankRequest;
use App\Repositories\Contracts\Util\CommercialHeadRepository;

class LocalExpDocSubBankController extends Controller {

    private $localexpdocsubbank;
    private $company;
    private $buyer;
    private $currency;
    private $localexpdocaccept;

    public function __construct(LocalExpDocSubBankRepository $localexpdocsubbank,LocalExpDocSubAcceptRepository $localexpdocaccept,CompanyRepository $company, BuyerRepository $buyer,CurrencyRepository $currency, CommercialHeadRepository $commercialhead) {

        $this->localexpdocaccept = $localexpdocaccept;
        $this->localexpdocsubbank = $localexpdocsubbank;
        $this->commercialhead = $commercialhead;
        $this->currency = $currency;
        $this->company = $company;
        $this->buyer = $buyer;

    $this->middleware('auth');

    // $this->middleware('permission:view.localexpdocsubbanks',   ['only' => ['create', 'index','show']]);
    // $this->middleware('permission:create.localexpdocsubbanks', ['only' => ['store']]);
    // $this->middleware('permission:edit.localexpdocsubbanks',   ['only' => ['update']]);
    // $this->middleware('permission:delete.localexpdocsubbanks', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {   
        $submissiontype=array_prepend(config('bprs.submissiontype'), '','');
        $localexpdocsubbanks=array();
        $rows=$this->localexpdocsubbank
        ->join('local_exp_doc_sub_accepts',function($join){
            $join->on('local_exp_doc_sub_accepts.id','=','local_exp_doc_sub_banks.local_exp_doc_sub_accept_id');
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->orderBy('local_exp_doc_sub_banks.id','desc')
        ->get([            
            'local_exp_doc_sub_banks.*',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.beneficiary_id',
            'local_exp_lcs.buyer_id', 
            'local_exp_lcs.buyers_bank', 
            'local_exp_lcs.currency_id', 
            'buyers.name as buyer_id',
            'companies.code as beneficiary_id',
            'currencies.name as currency_id',
            ]);
        //->get();
        foreach($rows as $row){
            $localexpdocsubbank['id']=$row->id;
            $localexpdocsubbank['local_exp_lc_id']=$row->local_exp_lc_id;
            $localexpdocsubbank['beneficiary_id']=$row->beneficiary_id;
            $localexpdocsubbank['local_lc_no']=$row->local_lc_no;
            $localexpdocsubbank['submission_date']=($row->submission_date !== null)?date("Y-m-d",strtotime($row->submission_date)):null;
            $localexpdocsubbank['submission_type_id']=$submissiontype[$row->submission_type_id];
            $localexpdocsubbank['bank_ref_bill_no']=$row->bank_ref_bill_no;
            $localexpdocsubbank['negotiation_date']=($row->negotiation_date !== null)?date('Y-m-d',strtotime($row->negotiation_date)):null;
            $localexpdocsubbank['courier_recpt_no']=$row->courier_recpt_no;
            array_push($localexpdocsubbanks,$localexpdocsubbank);
        }
        echo json_encode($localexpdocsubbanks);
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

        return Template::LoadView('Commercial.LocalExport.LocalExpDocSubBank',['currency'=>$currency,'commercialhead'=>$commercialhead,'submissiontype'=>$submissiontype,'company'=>$company]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocalExpDocSubBankRequest $request) {

        $localexpdocsubbank=$this->localexpdocsubbank->create($request->except(['id','beneficiary_id','buyer_id','currency_id','buyers_bank','local_lc_no','local_lc_no_accept_id']));
        if($localexpdocsubbank){
            return response()->json(array('success' => true,'id' =>  $localexpdocsubbank->id,'message' => 'Save Successfully'),200);
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
       $localexpdocsubbank = $this->localexpdocsubbank
       ->join('local_exp_doc_sub_accepts',function($join){
            $join->on('local_exp_doc_sub_accepts.id','=','local_exp_doc_sub_banks.local_exp_doc_sub_accept_id');
        })
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','local_exp_lcs.beneficiary_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
            $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
        })
        ->where([['local_exp_doc_sub_banks.id','=',$id]])
        ->get([
            'local_exp_doc_sub_banks.*',
            'local_exp_lcs.id as local_exp_lc_id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.beneficiary_id',
            'local_exp_lcs.buyer_id', 
            'local_exp_lcs.buyers_bank', 
            'local_exp_lcs.currency_id', 
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id',
            'local_exp_invoices.local_invoice_value'
        ])
        ->map(function($localexpdocsubbank){
            $localexpdocsubbank->local_lc_no_accept_id=$localexpdocsubbank->local_exp_doc_sub_accept_id.' ;'.$localexpdocsubbank->local_lc_no;
            return $localexpdocsubbank;
        })
        ->first();

        $localexpdocsubbank['submission_date']=($localexpdocsubbank->submission_date !== null)?date("Y-m-d",strtotime($localexpdocsubbank->submission_date)):null;
        $localexpdocsubbank['negotiation_date']=($localexpdocsubbank->negotiation_date !== null )?date("Y-m-d",strtotime($localexpdocsubbank->negotiation_date)):null;

        $row ['fromData'] = $localexpdocsubbank;
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
    public function update(LocalExpDocSubBankRequest $request, $id) {
        $localexpdocsubbank=$this->localexpdocsubbank->update($id,$request->except(['id','beneficiary_id','buyer_id','currency_id','buyers_bank','local_lc_no','local_lc_no_accept_id']));
        if($localexpdocsubbank){
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
        if($this->localexpdocsubbank->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getDocSubAccept(){
        
        $rows=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','local_exp_lcs.beneficiary_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->leftJoin('local_exp_doc_sub_banks',function($join){
            $join->on('local_exp_doc_sub_banks.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
        })
        ->whereNotNull('local_exp_doc_sub_accepts.accept_receive_date')
        ->when(request('local_lc_no'), function ($q) {
            return $q->where('local_exp_lcs.local_lc_no', 'LIKE', "%".request('local_lc_no', 0)."%");
        }) 
         ->when(request('beneficiary_id'), function ($q) {
            return $q->where('local_exp_lcs.beneficiary_id', '=', request('beneficiary_id', 0));
        })
        ->orderBy('local_exp_doc_sub_accepts.id','asc')
        ->get([
            'local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id',
            'local_exp_lcs.*',
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id',
            'local_exp_invoices.local_invoice_value',
            'local_exp_doc_sub_banks.id as local_exp_doc_sub_banks_id'
        ])
        ->map(function($rows){
            $rows->local_lc_no_accept_id=$rows->local_exp_doc_sub_accept_id.' ;'.$rows->local_lc_no;
            return $rows;
        });

        $notsaved = $rows->filter(function ($value) {
            if(!$value->local_exp_doc_sub_banks_id){
                return $value;
            }
        })->values();
        echo json_encode($notsaved);
    }
    
    public function getLatter()
    {
        $id=request('id',0);

        $rows=$this->localexpdocsubbank
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_banks.local_exp_lc_id');
        })
        ->leftJoin('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'local_exp_lcs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->where([['local_exp_doc_sub_banks.id',$id]])
        ->get([            
            'local_exp_doc_sub_banks.*',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.file_no',
            'local_exp_lcs.beneficiary_id',
            'local_exp_lcs.buyer_id', 
            'local_exp_lcs.buyers_bank', 
            'local_exp_lcs.currency_id', 
            'local_exp_lcs.exch_rate', 
            'buyers.name as buyer_id',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id',
        ])
        ->first();

        $replacablesc=$this->localexpdocaccept
        ->where([['local_exp_lcs.file_no','=',$rows->file_no]])
        ->where([['local_exp_lcs.sc_or_lc','=',1]])
        ->whereNull('local_exp_lcs.deleted_at')
        ->whereIn('local_exp_lcs.lc_sc_nature_id',[2])
        ->get([
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.lc_sc_value',
            'local_exp_lcs.local_commission_per',
            'local_exp_lcs.foreign_commission_per',
        ])
        ->map(function($replacablesc){
            $replacablesc->local_commission=$replacablesc->lc_sc_value*($replacablesc->local_commission_per/100);
            $replacablesc->foreign_commission=$replacablesc->lc_sc_value*($replacablesc->foreign_commission_per/100);
            return $replacablesc;
        });

        $direct_lc_sc=$this->localexpdocaccept
        ->where([['local_exp_lcs.file_no','=',$rows->file_no]])
        ->whereNull('local_exp_lcs.deleted_at')
        ->whereIn('local_exp_lcs.lc_sc_nature_id',[1])
        ->get([
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.lc_sc_value',
            'local_exp_lcs.local_commission_per',
            'local_exp_lcs.foreign_commission_per',
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

        $btbpc=$this->localexpdocaccept
        ->selectRaw('
            local_exp_lcs.file_no,
            ExpPC.pc_taken_mount,
            ExpPC.pc_taken_rate,
            BTB.btb_open_amount
        ')
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('bank_branches', function($join) {
            $join->on('bank_branches.id', '=', 'local_exp_lcs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join) {
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->leftJoin(\DB::raw("(SELECT local_exp_lcs.file_no,sum(imp_backed_local_exp_lcs.amount) as btb_open_amount FROM imp_backed_local_exp_lcs right join local_exp_lcs on local_exp_lcs.id = imp_backed_local_exp_lcs.local_exp_lc_id right join  imp_lcs on imp_lcs.id = imp_backed_local_exp_lcs.imp_lc_id    group by local_exp_lcs.file_no) BTB"), "BTB.file_no", "=", "local_exp_lcs.file_no")
        ->leftJoin(\DB::raw("(SELECT 
            local_exp_lcs.file_no,
            sum(exp_pre_credit_lc_scs.equivalent_fc) as pc_taken_mount, 
            min(exp_pre_credits.rate) as pc_taken_rate
            FROM 
            exp_pre_credit_lc_scs 
            join local_exp_lcs on local_exp_lcs.id = exp_pre_credit_lc_scs.local_exp_lc_id 
            join exp_pre_credits on exp_pre_credits.id = exp_pre_credit_lc_scs.exp_pre_credit_id
            where exp_pre_credit_lc_scs.deleted_at is null  
            group by local_exp_lcs.file_no
        ) ExpPC"), "ExpPC.file_no", "=", "local_exp_lcs.file_no")
        ->where([['local_exp_lcs.file_no','=',$rows->file_no]])
        ->groupBy([
            'local_exp_lcs.file_no',
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

        $invoice_detail=$this->localexpdocsubbank
        ->selectRaw('
        local_exp_invoices.id as exp_invoice_id,
        local_exp_invoices.invoice_no,
        local_exp_invoices.invoice_date,
        local_exp_invoices.invoice_value,
        local_exp_lcs.local_lc_no,
        local_exp_lcs.sc_or_lc,
        local_exp_doc_sub_invoices.id as exp_doc_sub_invoice_id
        ')
        ->join('local_exp_invoices', function($join)  {
            $join->on('local_exp_invoices.local_exp_lc_id', '=', 'local_exp_doc_sub_banks.local_exp_lc_id');
        })
        ->join('local_exp_lcs', function($join)  {
            $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
        })
        ->join('local_exp_doc_sub_invoices',function($join){
          $join->on('local_exp_doc_sub_invoices.exp_invoice_id','=','local_exp_invoices.id');
          $join->on('local_exp_doc_sub_invoices.exp_doc_submission_id','=','local_exp_doc_sub_banks.id');
          $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->where([['local_exp_doc_sub_banks.id','=',$id]])
        ->get()
        ->map(function($invoice_detail){
            $invoice_detail->invoice_date=date('d-M-Y',strtotime($invoice_detail->invoice_date));
            return $invoice_detail;

        });

        $rows->total_invoice_value=$invoice_detail->sum('invoice_value');
      
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

      $view= \View::make('Defult.Commercial.LocalExport.ExpNegotiationSheetPdf',['sub'=>$sub,'master'=>$rows,'replacablesc'=>$replacablesc,'direct_lc_sc'=>$direct_lc_sc,'btbpc'=>$btbpc, 'invoice_detail'=>$invoice_detail]);

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
}
