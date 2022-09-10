<?php

namespace App\Http\Controllers\ShowRoom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ShowRoom\SrmProductReceiveDtlRepository;
use App\Repositories\Contracts\ShowRoom\SrmProductReceiveRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Library\Template;

use App\Http\Requests\ShowRoom\SrmProductReceiveDtlRequest;

class SrmProductReceiveDtlController extends Controller {

    //private $company;
    private $productreceivedtl;

    public function __construct(SrmProductReceiveRepository $srmproductreceive,SrmProductReceiveDtlRepository $productreceivedtl, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->productreceivedtl = $productreceivedtl;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->srmproductreceive=$srmproductreceive;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtproductreceivedtls',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtproductreceivedtls', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtproductreceivedtls',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtproductreceivedtls', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		//
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
    public function store(SrmProductReceiveDtlRequest $request) {
        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            //$receiveid=$request->srm_product_receive_id[$index];
            if($sales_order_gmt_color_size_id && $request->qty[$index] )
            {
                $productreceivedtl = $this->productreceivedtl->updateOrCreate(
                [
                    'sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,
                    'srm_product_receive_id' => $request->srm_product_receive_id
                ],
                [
                    'style_id' => $request->style_id[$index],
                    'job_id' => $request->job_id[$index],
                    'sales_order_id' => $request->sales_order_id[$index],
                    'size_id' => $request->size_id[$index],
                    'color_id' => $request->color_id[$index],
                    'style_gmt_id' => $request->style_gmt_id[$index],
                    'uom_id' => $request->uom_id[$index],
                    'style_ref' => $request->style_ref[$index],
                    'style_gmt_name' => $request->style_gmt_name[$index],
                    'sale_order_no' => $request->sale_order_no[$index],
                    'size_name' => $request->size_name[$index],
                    'color_name' => $request->color_name[$index],
                    'currency_id' => $request->currency_id[$index],
                    //'currency_code' => $request->currency_code[$index],
                    'country_id' => $request->country_id[$index],
                    //'country_name' => $request->country_name[$index],
                    'qty' => $request->qty[$index],
                    'rate' => $request->rate[$index],
                    'amount' => $request->amount[$index],
                    'sales_rate' => $request->sales_rate[$index],
                    'vat_per' => $request->vat_per[$index],
                    'source_tax_per' => $request->source_tax_per[$index],
                ]);
            }
        }

        if($productreceivedtl){
            return response()->json(array('success' => true,'id' =>  $productreceivedtl->id,/* 'srm_product_receive_id'=>$receiveid, */'message' => 'Save Successfully'),200);
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
        $productreceivedtl = $this->productreceivedtl->find($id);
        $row ['fromData'] = $productreceivedtl;
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
    public function update(SrmProductReceiveDtlRequest $request, $id) {
        $productreceivedtl=$this->productreceivedtl->update($id,$request->except(['id']));
        if($productreceivedtl){
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
        if($this->productreceivedtl->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getBercodePdf(){
        $id=request('id',0);
       
        $receivedtail = $this->productreceivedtl
        ->leftJoin('srm_product_receives',function($join){
            $join->on('srm_product_receives.id','=','srm_product_receive_dtls.srm_product_receive_id');
        })
        ->leftJoin('currencies', function($join)  {
            $join->on('currencies.id', '=', 'srm_product_receive_dtls.currency_id');
        })
        ->where([['srm_product_receive_dtls.id','=',$id]])
        ->get([
            'srm_product_receive_dtls.id as srm_product_receive_dtl_id',
            'srm_product_receive_dtls.qty',
            'srm_product_receive_dtls.style_ref',
            'srm_product_receive_dtls.sale_order_no',
            'srm_product_receive_dtls.style_gmt_name',
            'srm_product_receive_dtls.color_name',
            'srm_product_receive_dtls.size_name',
            'srm_product_receive_dtls.sales_rate',
            'srm_product_receive_dtls.currency_id',
            'currencies.code as currency_code',
        ])
       ->first();
       $qty=$receivedtail['qty'];
        
        $pdf = new \Pdf('P', 'mm', 'A7 PORTRAIT', true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->setTopMargin(13.0);
        $pdf->SetRightMargin(0);
        $pdf->setHeaderMargin(13);
        $pdf->SetFooterMargin(13.0);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 13.0);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 6);
        $pdf->AddPage('P', 'G9');
        $barcodestyle = array(
            'position' => '',
            'align' => 'L',
            'stretch' => false,
            'fitwidth' => false,
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
        
        $pdf->SetY(4);
        $pdf->SetX(4);


        $challan=str_pad($receivedtail['srm_product_receive_dtl_id'],10,0,STR_PAD_LEFT ) ;

        //$x = $pdf->GetX();
        //$y = $pdf->GetY();
        $pdf->setCellMargins(0,0,2.0,0);
        //$pdf->Cell(0, 0,'Product Code', 0, 1);
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', /* $x-2.5 */'', /* $y-6.5 */'', 63.5, 18, 0.2, $barcodestyle, 'N');
        //$pdf->SetXY($x,$y);
        //$pdf->Cell(62, 25,'', 0, 0, 'L', FALSE, '', 0, FALSE, 'C', '');
        //$pdf->SetXY($x,$y);
        //$pdf->Cell(63.5, 33, 'Price', 0, 0, 'L', FALSE, '', 0, FALSE, 'C', 'B');
        //$pdf->Cell(0, 0, 'CODE 39 EXTENDED', 0, 1);
        //$pdf->Text(170, 250, 'FAMKAM ERP');
        $pdf->Text(5, 24, 'Style Ref :'.$receivedtail['style_ref']);
        $pdf->Text(5, 27, 'Order No :'.$receivedtail['sale_order_no']);
        $pdf->Text(5, 30, 'Item :'.$receivedtail['style_gmt_name']);
        $pdf->Text(5, 33, 'Color :'.$receivedtail['color_name']);
        $pdf->Text(5, 36, 'Size :'.$receivedtail['size_name']);
        $pdf->Text(5, 39, 'Price :'.$receivedtail['sales_rate']." ".$receivedtail['currency_code']);

        
        $view= \View::make('Defult.ShowRoom.ReceiveBarcodePdf',['receivedtail'=>$receivedtail]);
        $html_content=$view->render();
        $pdf->SetY(23);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/BarcodePdf.pdf';
        $pdf->output($filename);
        exit();
    }

}