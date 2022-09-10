<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpDocMaturityRepository;
use App\Repositories\Contracts\Util\SupplierRepository;

use App\Library\Template;
use App\Http\Requests\Commercial\Import\ImpDocMaturityRequest;

class ImpDocMaturityController extends Controller {

    private $impdocmaturity;
    private $supplier;


    public function __construct(
        ImpDocMaturityRepository $impdocmaturity, SupplierRepository $supplier
        ) {
        $this->impdocmaturity = $impdocmaturity;
        $this->supplier=$supplier;

        $this->middleware('auth');
        // $this->middleware('permission:view.impdocmaturitys',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.impdocmaturitys', ['only' => ['store']]);
        // $this->middleware('permission:edit.impdocmaturitys',   ['only' => ['update']]);
        // $this->middleware('permission:delete.impdocmaturitys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

       $impdocmaturitys = array();
       $rows = $this->impdocmaturity->get();
       foreach($rows as $row){
         $impdocmaturity['id']=$row->id;
         $impdocmaturity['doc_maturity_date']=date('Y-m-d',strtotime($row->doc_maturity_date));
         
         array_push($impdocmaturitys,$impdocmaturity);
       }
       echo json_encode($impdocmaturitys);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return Template::LoadView('Commercial.Import.ImpDocMaturity');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImpDocMaturityRequest $request) {

        $impdocmaturity = $this->impdocmaturity->create([
            'doc_maturity_date' => $request->doc_maturity_date
            ]);

        if($impdocmaturity){
            return response()->json(array('success'=>true,'id'=>$impdocmaturity->id,'message'=>'Save Successfully'),200);
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
        $impdocmaturity=$this->impdocmaturity->find($id);
        $row['fromData']=$impdocmaturity;
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
    public function update(ImpDocMaturityRequest $request, $id) {
        $impdocaccept=$this->impdocmaturity->update($id,$request->except([
            'doc_maturity_date' => $request->doc_maturity_date
            ]));
        if($impdocmaturity){
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
        $impdocmaturity = $this->impdocmaturity->findOrFail($id);
		if($impdocmaturity->forceDelete()){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}	
		else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }

    public function getMatureLetter()
    {
        $id=request('id',0);

        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $rows=$this->impdocmaturity
        ->join('imp_doc_maturity_dtls', function($join)  {
            $join->on('imp_doc_maturities.id', '=', 'imp_doc_maturity_dtls.imp_doc_maturity_id');
        })
        ->join('imp_doc_accepts', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_doc_maturity_dtls.imp_doc_accept_id');
        })
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
        ->where([['imp_doc_maturities.id','=',$id]])
        ->get([
            'imp_doc_maturities.doc_maturity_date',
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
        
        $invoice_detail=$this->impdocmaturity
        ->join('imp_doc_maturity_dtls', function($join)  {
            $join->on('imp_doc_maturities.id', '=', 'imp_doc_maturity_dtls.imp_doc_maturity_id');
        })
        ->join('imp_doc_accepts', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_doc_maturity_dtls.imp_doc_accept_id');
        })
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
        })
        ->where([['imp_doc_maturities.id','=',$id]])
        ->orderBy('imp_doc_accepts.id','desc')
        ->get([
        'imp_doc_accepts.id as imp_doc_accept_id',
         'imp_doc_accepts.invoice_no',
         'imp_doc_accepts.invoice_date',
         'imp_doc_accepts.doc_value',
         'imp_lcs.lc_no_i',
         'imp_lcs.lc_no_ii',
         'imp_lcs.lc_no_iii',
         'imp_lcs.lc_no_iv',
         'imp_lcs.supplier_id',
         'imp_lcs.lc_date', 
         'imp_doc_maturity_dtls.*'
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