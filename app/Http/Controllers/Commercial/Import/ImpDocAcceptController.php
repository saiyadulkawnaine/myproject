<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
//use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Import\ImpDocAcceptRequest;

class ImpDocAcceptController extends Controller {

    private $impdocaccept;
    private $supplier;
    private $bankbranch;
    private $implc;
    private $commercialhead;
    private $company;
    private $bankaccount;

    public function __construct(
        ImpDocAcceptRepository $impdocaccept,
        ImpLcRepository $implc,
        SupplierRepository $supplier,
        BankBranchRepository $bankbranch,
        BankAccountRepository $bankaccount,
        CommercialHeadRepository $commercialhead,
        CompanyRepository $company
    ) {
        $this->impdocaccept = $impdocaccept;
        $this->supplier = $supplier;
        $this->bankbranch = $bankbranch;
        $this->implc = $implc;
        $this->commercialhead = $commercialhead;
        $this->company = $company;
        $this->bankaccount = $bankaccount;
        

        $this->middleware('auth');
        $this->middleware('permission:view.impdocaccepts',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.impdocaccepts', ['only' => ['store']]);
        $this->middleware('permission:edit.impdocaccepts',   ['only' => ['update']]);
        $this->middleware('permission:delete.impdocaccepts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
        $impdocaccepts = array();
        $rows = $this->impdocaccept
            ->selectRaw('
                imp_doc_accepts.id,
                imp_doc_accepts.imp_lc_id,
                imp_doc_accepts.commercial_head_id,
                imp_doc_accepts.invoice_no,
                imp_doc_accepts.invoice_date,
                imp_doc_accepts.shipment_date,
                imp_doc_accepts.company_accep_date,
                imp_doc_accepts.bank_accep_date,
                imp_doc_accepts.bank_ref,
                imp_doc_accepts.loan_ref,
                imp_doc_accepts.doc_value,
                imp_doc_accepts.rate,
                imp_lcs.lc_no_i,imp_lcs.lc_no_ii,
                imp_lcs.lc_no_iii,imp_lcs.lc_no_iv,
                imp_lcs.lc_type_id,imp_lcs.pay_term_id,
                commercial_heads.name as commercial_head_name
            ')
            ->join('imp_lcs', function($join)  {
                $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
            })
            ->leftJoin('bank_accounts',function($join){
                $join->on('bank_accounts.id','=','imp_doc_accepts.bank_account_id');
            })
            ->leftJoin('commercial_heads',function($join){
                $join->on('bank_accounts.account_type_id','=','commercial_heads.id');
            })
            ->orderBy('imp_doc_accepts.id','desc')
            ->get();

            foreach($rows as $row){
                $impdocaccept['id']=$row->id;
                //$impdocaccept['imp_lc_id']=$row->imp_lc_id;
                $impdocaccept['lc_no']=$row->lc_no_i." ".$row->lc_no_ii." ".$row->lc_no_iii." ".$row->lc_no_iv;
                $impdocaccept['commercial_head_id']=$commercialhead[$row->commercial_head_id];
                $impdocaccept['lc_type_id']=$lctype[$row->lc_type_id];
                $impdocaccept['pay_term_id']=$payterm[$row->pay_term_id];
                $impdocaccept['invoice_no']=$row->invoice_no;
                $impdocaccept['invoice_date']=date('Y-m-d',strtotime($row->invoice_date));
                $impdocaccept['shipment_date']=date('Y-m-d',strtotime($row->shipment_date));
                $impdocaccept['company_accep_date']=date('Y-m-d',strtotime($row->company_accep_date));
                $impdocaccept['bank_ref']=$row->bank_ref;
                $impdocaccept['loan_ref']=$row->loan_ref;
                $impdocaccept['commercial_head_name']=$row->commercial_head_name;
                $impdocaccept['doc_value']=number_format($row->doc_value,2);
                $impdocaccept['rate']=$row->rate;
                array_push($impdocaccepts,$impdocaccept);
            }
        echo json_encode($impdocaccepts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        $deliveryMode = array_prepend(config('bprs.deliveryMode'), '-Select-','');
        $implc=array_prepend(array_pluck($this->implc->get(),'lc_no_i','id'),'-Select-','');
        $docStatus = array_prepend(config('bprs.docStatus'), '-Select-','');
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
        $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        

        return Template::LoadView('Commercial.Import.ImpDocAccept',['supplier'=>$supplier,'bankbranch'=>$bankbranch,'payterm'=>$payterm,'incoterm'=>$incoterm,'deliveryMode'=>$deliveryMode,'docStatus'=>$docStatus,'implc'=>$implc,'commercialhead'=>$commercialhead,'lctype'=>$lctype,'company'=>$company]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImpDocAcceptRequest $request) {
      $impdocaccept=$this->impdocaccept->create($request->except(['id','lc_no','company_id','supplier_id','issuing_bank_branch_id','lc_type_id','pay_term_id','tenor','commercial_head_name','issuing_bank_branch','company_name']));
      /* [
          'imp_lc_id'=>$request->imp_lc_id,'commercial_head_id'=>$request->commercial_head_id,'invoice_no'=>$request->invoice_no,'invoice_date'=>$request->invoice_date,'shipment_date'=>$request->shipment_date,'company_accep_date'=>$request->company_accep_date,'bank_accep_date'=>$request->bank_accep_date,'bank_ref'=>$request->bank_ref,'loan_ref'=>$request->loan_ref,'doc_value'=>$request->doc_value,'rate'=>$request->rate,'bl_cargo_no'=>$request->bl_cargo_no,'bl_cargo_date'=>$request->bl_cargo_date,'shipment_mode'=>$request->shipment_mode,'doc_status'=>$request->doc_status,'copy_doc_rcv_date'=>$request->copy_doc_rcv_date,'original_doc_rcv_date'=>$request->original_doc_rcv_date,'doc_to_cf_date'=>$request->doc_to_cf_date,'feeder_vessel'=>$request->feeder_vessel,'mother_vessel'=>$request->mother_vessel,'eta_date'=>$request->eta_date,'ic_received_date'=>$request->ic_received_date,'shipping_bill_no'=>$request->shipping_bill_no,'incoterm_id'=>$request->incoterm_id,'incoterm_place'=>$request->incoterm_place,'port_of_loading'=>$request->port_of_loading,'port_of_discharge'=>$request->port_of_discharge,'internal_file_no'=>$request->internal_file_no,
          'bill_of_entry_no'=>$request->bill_of_entry_no,'psi_ref_no'=>$request->psi_ref_no,'maturity_date'=>$request->maturity_date,'container_no'=>$request->container_no,'qty'=>$request->qty,'remarks'=>$request->remarks
      ] */
      if($impdocaccept){
         return response()->json(array('success'=>true,'id'=>$impdocaccept->id,'message'=>'Save Successfully'),200);
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
        $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');

        $impdocaccept=$this->impdocaccept
        ->selectRaw('
        imp_doc_accepts.id,
        imp_doc_accepts.imp_lc_id,
        imp_doc_accepts.commercial_head_id,
        imp_doc_accepts.invoice_no,
        imp_doc_accepts.invoice_date,
        imp_doc_accepts.shipment_date,
        imp_doc_accepts.company_accep_date,
        imp_doc_accepts.bank_accep_date,
        imp_doc_accepts.discharge_date,
        imp_doc_accepts.port_clearing_date,
        imp_doc_accepts.bank_ref,
        imp_doc_accepts.loan_ref,
        imp_doc_accepts.doc_value,
        imp_doc_accepts.rate,
        imp_doc_accepts.bl_cargo_no,
        imp_doc_accepts.bl_cargo_date,imp_doc_accepts.shipment_mode,
        imp_doc_accepts.doc_status,imp_doc_accepts.copy_doc_rcv_date,
        imp_doc_accepts.original_doc_rcv_date,imp_doc_accepts.doc_to_cf_date,
        imp_doc_accepts.feeder_vessel,imp_doc_accepts.mother_vessel,
        imp_doc_accepts.eta_date,imp_doc_accepts.ic_received_date,
        imp_doc_accepts.shipping_bill_no,imp_doc_accepts.incoterm_id,
        imp_doc_accepts.incoterm_place,imp_doc_accepts.port_of_loading,
        imp_doc_accepts.port_of_discharge,imp_doc_accepts.internal_file_no,
        imp_doc_accepts.bill_of_entry_no,imp_doc_accepts.psi_ref_no,
        imp_doc_accepts.maturity_date,imp_doc_accepts.container_no,
        imp_doc_accepts.qty,imp_doc_accepts.remarks,
        
        imp_lcs.lc_no_i,imp_lcs.lc_no_ii,
        imp_lcs.lc_no_iii,imp_lcs.lc_no_iv,
        imp_lcs.company_id,imp_lcs.supplier_id,imp_lcs.issuing_bank_branch_id,
        imp_lcs.pay_term_id,imp_lcs.lc_type_id,imp_lcs.tenor,
        companies.name as company_name,
        suppliers.name as supplier_name,
        commercial_heads.name as commercial_head_name,
        bank_branches.branch_name,
        banks.name as bank_name
        ')
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
        })
        ->leftJoin('bank_branches',function($join){
            $join->on('bank_branches.id','=','imp_lcs.issuing_bank_branch_id');
        })
        ->leftJoin('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'imp_lcs.company_id');
        })
        ->join('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
        ->leftJoin('bank_accounts',function($join){
            $join->on('bank_accounts.id','=','imp_doc_accepts.bank_account_id');
        })
        ->leftJoin('commercial_heads',function($join){
            $join->on('bank_accounts.account_type_id','=','commercial_heads.id');
        })
        ->where([['imp_doc_accepts.id','=',$id]])
        ->get()
        ->first();
       //->find($id); 
        $impdocaccept->lc_no=$impdocaccept->lc_no_i." ".$impdocaccept->lc_no_ii." ".$impdocaccept->lc_no_iii." ".$impdocaccept->lc_no_iv;
        $impdocaccept->lc_type_id=$lctype[ $impdocaccept->lc_type_id];
        $impdocaccept->pay_term_id=$payterm[ $impdocaccept->pay_term_id];
        $impdocaccept->issuing_bank_branch=$impdocaccept->bank_name.' (' .$impdocaccept->branch_name.' )';

        $row['fromData']=$impdocaccept;
        $dropdown['att']='';
        $row['dropDown']=$dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImpDocAcceptRequest $request, $id) {
        $impdocaccept=$this->impdocaccept->update($id,$request->except(['id','lc_no','company_id','company_name','supplier_id','issuing_bank_branch_id','issuing_bank_branch','lc_type_id','pay_term_id','tenor','commercial_head_name']));
        if($impdocaccept){
           return response()->json(array('success'=>true,'id'=>$id,'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->impdocaccept->delete($id)){
           return response()->json(array('success'=>true,'message'=>'Delete Successfully'),200);
        }
    }

    Public function getImportLc(){

        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
        $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
         
        $implcs=array();
        $rows=$this->implc
        ->when(request('company_id'), function ($q) {
            return $q->where('imp_lcs.company_id', '=', request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
            return $q->where('imp_lcs.supplier_id', '=', request('supplier_id', 0));
        })
        ->when(request('issuing_bank_branch_id'), function ($q) {
            return $q->where('imp_lcs.issuing_bank_branch_id', '=', request('issuing_bank_branch_id', 0));
        })
        /*->when(request('lc_type_id'), function ($q) {
            return $q->where('imp_lcs.lc_type_id', '=', request('lc_type_id', 0));
           })*/
        /* ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'imp_lcs.company_id');
        })
        ->join('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
       ->join('bank_branches', function($join)  {
            $join->on('bank_branches.id', '=', 'imp_lcs.issuing_bank_branch_id');
        }) */
        ->get();
        foreach($rows as $row){
            $implc['id']=$row->id;
            $implc['company_id'] = $row->company_id;
            $implc['company_name'] = $company[$row->company_id];
            $implc['supplier_id']= $supplier[$row->supplier_id];
            $implc['issuing_bank_branch_id']=$row->issuing_bank_branch_id;
            $implc['issuing_bank_branch']=$bankbranch[$row->issuing_bank_branch_id];
            $implc['lc_type_id']=  $lctype[$row->lc_type_id];
            $implc['last_delilvery_date']=date('Y-m-d',strtotime($row->last_delilvery_date));
            $implc['expiry_date']=date('Y-m-d',strtotime($row->expiry_date));
            $implc['lc_no']=$row->lc_no_i." ".$row->lc_no_ii." ".$row->lc_no_iii." ".$row->lc_no_iv;
            $implc['pay_term_id']=$payterm[$row->pay_term_id];
            $implc['exch_rate']=$row->exch_rate;
            $implc['tenor']=$row->tenor;
            
            array_push($implcs,$implc);
        }
        echo json_encode($implcs);

    }

    public function getBankAccount(){
        $issuing_bank_branch_id=request('issuing_bank_branch_id',0);
        $company_id=request('company_id',0);
   
        $rows=$this->bankaccount
        ->join('bank_branches', function($join)  {
            $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id');
        })
        ->join('banks',function($join){
            $join->on('bank_branches.bank_id','=','banks.id');
        })
        ->join('commercial_heads',function($join){
           $join->on('bank_accounts.account_type_id','=','commercial_heads.id');
        })
        ->when(request('branch_name'), function ($q) {
            return $q->where('bank_branches.name', 'LIKE', "%".request('branch_name', 0)."%");
        })
        ->when(request('account_no'), function ($q) {
            return $q->where('bank_accounts.account_no', 'LIKE', "%".request('account_no', 0)."%");
        })
        ->orderBy('bank_accounts.id','desc')
        ->where([['bank_branches.id','=',$issuing_bank_branch_id]])
        ->where([['bank_accounts.company_id','=',$company_id]])
        ->where([['commercial_heads.commercialhead_type_id','=',3]])
        ->get([
            'bank_accounts.*',
            'banks.name',
            'bank_branches.branch_name',
            'commercial_heads.name as commercial_head_name'
        ]);
        echo json_encode($rows);
    }


    public function getMatureLetter()
    {
        $id=request('id',0);
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $rows=$this->impdocaccept
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
        })
        ->leftJoin('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'imp_lcs.issuing_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'imp_lcs.company_id');
        })
        ->where([['imp_doc_accepts.id','=',$id]])
        ->orderBy('imp_doc_accepts.id','desc')
        ->get([
            'imp_doc_accepts.*',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'companies.code as company_code',
            'companies.name as company_name',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
        ])
        ->first();
        
        $invoice_detail=$this->impdocaccept
        ->join('imp_doc_accept_maturities', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_doc_accept_maturities.imp_doc_accept_id');
        })
        ->join('imp_doc_accepts as m_invoice', function($join)  {
            $join->on('m_invoice.id', '=', 'imp_doc_accept_maturities.doc_invoice_id');
        })
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'm_invoice.imp_lc_id');
        })
        ->where([['imp_doc_accepts.id','=',$id]])
        ->orderBy('imp_doc_accepts.id','desc')
        ->get([
        'imp_doc_accepts.id as imp_doc_accept_id',
         'm_invoice.invoice_no',
         'm_invoice.invoice_date',
         'm_invoice.doc_value',
         'imp_lcs.lc_no_i',
         'imp_lcs.lc_no_ii',
         'imp_lcs.lc_no_iii',
         'imp_lcs.lc_no_iv',
         'imp_lcs.supplier_id',
         'imp_lcs.lc_date',
         
         'imp_doc_accept_maturities.*'
        ])
        ->map(function($invoice_detail) use($supplier) {
            $invoice_detail->lc_no=$invoice_detail->lc_no_i." ".$invoice_detail->lc_no_ii." ".$invoice_detail->lc_no_iii." ".$invoice_detail->lc_no_iv;
            
            $invoice_detail->invoice_date=date('d-M-Y',strtotime($invoice_detail->invoice_date));
            $invoice_detail->beneficiary=$supplier[$invoice_detail->supplier_id];
            return $invoice_detail;
        });
        

        $arrInvoice=array();
        foreach($invoice_detail as $bar){
            $arrInvoice[$bar->imp_doc_accept_id][$bar->id]=
            "LC No:".$bar->lc_no.";Date: ".date('d-M-Y',strtotime($bar->lc_date))."Supplier:".$supplier[$bar->supplier_id]."\n ;"."Invoice No:".$bar->invoice_no.";Date:".date('d-M-Y',strtotime($bar->invoice_date))."\n;";
        }
        $rows->invoice_no=implode(", ",$arrInvoice[$rows->id]);
        
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
        
        $sub="Sub : Request to give maturity date as mentioned in below matrix.";

        $body="We do hereby inform you that we have received materials from suppliers as per L/C terms and conditions and now requesting you to confirm payment maturity date to the supplier's bank accordingly. ";

        $ttp2="Your prompt co-operation will be highly appreciated.";

        $view= \View::make('Defult.Commercial.Import.ImpDocAcceptMaturityPdf',['rows'=>$rows,'sub'=>$sub,'body'=>$body,'invoice_detail'=>$invoice_detail,'ttp2'=>$ttp2]);
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

        $qrc =  $rows['invoice_no']."\n ,".
                'Issuing Bank:'.$rows['bank_name'].', Applicant:'.$rows['company_name'];
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 220, 40, 40, $barcodestyle, 'N');
        $pdf->Text(170, 244, 'ID :'.$id);
        $pdf->Text(170, 247, $rows['company_code']);

        $pdf->SetFont('helvetica', 'N', 10);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/ImpDocAcceptMaturityPdf.pdf';
        $pdf->output($filename);
    }

}
