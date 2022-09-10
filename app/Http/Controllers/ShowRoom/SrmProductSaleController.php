<?php

namespace App\Http\Controllers\ShowRoom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ShowRoom\SrmProductSaleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\ShowRoom\SrmProductReceiveDtlRepository;
use App\Repositories\Contracts\ShowRoom\SrmProductScanRepository;

use App\Library\Template;
use App\Http\Requests\ShowRoom\SrmProductSaleRequest;

class SrmProductSaleController extends Controller {
    private $sale;
    private $salesordergmtcolorsize;
    private $company;
    private $productreceivedtl;
    private $srmproductscan;
    

    public function __construct(
        SrmProductSaleRepository $sale,
        SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
        CompanyRepository $company,
        SrmProductReceiveDtlRepository $productreceivedtl,
        SrmProductScanRepository $srmproductscan
    ) {
      $this->sale  = $sale;
      $this->salesordergmtcolorsize = $salesordergmtcolorsize;
      $this->company = $company;
      $this->productreceivedtl = $productreceivedtl;
      $this->srmproductscan = $srmproductscan;

      $this->middleware('auth');
      //$this->middleware('permission:view.srmproductsales',   ['only' => ['create', 'index','show']]);
     // $this->middleware('permission:create.srmproductsales', ['only' => ['store']]);
      //$this->middleware('permission:edit.srmproductsales',   ['only' => ['update']]);
      //$this->middleware('permission:delete.srmproductsales', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $rows=$this->sale
        ->orderBy('id','desc')
        ->limit(5)
        ->get()
        /* ->first() */;
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $yesno=array_prepend(config('bprs.yesno'),'','');
        $paymentType=array_prepend(config('bprs.paymentType'),'','');
		return Template::loadView('ShowRoom.SrmProductSale', ['yesno'=>$yesno,'paymentType'=>$paymentType]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SrmProductSaleRequest $request) {
    $max = $this->sale->max('invoice_no');
    $invoice_no=$max+1;
        $sale=$this->sale->create([
            'invoice_no'=>$invoice_no,
            //'tax_amount'=>$request->tax_amount,
            'discount_amount'=>$request->discount_amount,
            'paid_amount'=>$request->paid_amount,
            'return_amount'=>$request->return_amount,
            'debit_card_no'=>$request->debit_card_no,
            'credit_card_no'=>$request->credit_card_no,
            //'payment_type_id'=>$request->payment_type_id,
            'scan_date'=>$request->scan_date,
            'customer_name'=>$request->customer_name,
            'net_paid_amount'=>$request->net_paid_amount,
            'credit_sale_id'=>$request->credit_sale_id,
            'remarks'=>$request->remarks
        ]);
        foreach($request->srm_product_receive_dtl_id as $index=>$srm_product_receive_dtl_id){
            if($srm_product_receive_dtl_id)
            {
                $srmproductscan = $this->srmproductscan->create([
                'srm_product_sale_id'=>$sale->id,
                'srm_product_receive_dtl_id'=>$srm_product_receive_dtl_id,
                'qty'=>$request->qty[$index],
                'sales_rate'=>$request->sales_rate[$index],
                'amount'=>$request->amount[$index],
                'vat_per'=>$request->vat_per[$index],
                'source_tax_per'=>$request->source_tax_per[$index],
                'gross_amount'=>$request->gross_amount[$index],
                ]);
            }
        }
        if($sale){
            return response()->json(array('success' => true,'id' => $sale->id,'message' => 'Save Successfully'),200);
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
        $sale=$this->sale->find($id);
        $row ['fromData'] = $sale;
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
    public function update(SrmProductSaleRequest $request, $id) {

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

    public function getProduct()
    {
        $bar_code_no=request('srm_product_receive_dtl_id',0);
        $bar_code_no= (int) $bar_code_no;

        
        $product=$this->productreceivedtl
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.id', '=', 'srm_product_receive_dtls.sales_order_gmt_color_size_id');
        })
        ->join('style_gmt_color_sizes', function($join)  {
            $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->join('style_gmts', function($join) {
            $join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
            $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('style_colors', function($join) {
            $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
        })
        ->join('colors', function($join) {
            $join->on('style_colors.color_id', '=', 'colors.id');
        })
        ->join('style_sizes', function($join) {
            $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes', function($join) {
            $join->on('style_sizes.size_id', '=', 'sizes.id');
        })
        ->leftJoin('srm_product_scans', function($join) {
            $join->on('srm_product_scans.srm_product_receive_dtl_id', '=', 'srm_product_receive_dtls.id');
        })
        ->leftJoin('srm_product_sales', function($join) {
            $join->on('srm_product_scans.srm_product_sale_id', '=', 'srm_product_sales.id');
        })
        //->where([['sales_order_gmt_color_sizes.id','=',$bar_code_no]])
        ->where([['srm_product_receive_dtls.id','=',$bar_code_no]])
        ->get([
            'srm_product_receive_dtls.id as srm_product_receive_dtl_id',
            'srm_product_receive_dtls.style_gmt_name as item_description',
            'srm_product_receive_dtls.color_name',
            'srm_product_receive_dtls.size_name',
            'srm_product_receive_dtls.sales_rate',
            'srm_product_receive_dtls.vat_per',
            'srm_product_receive_dtls.source_tax_per',
        ])
        ->map(function($product){
            $product->product_desc=$product->item_description.",".$product->color_name.",".$product->size_name;
            $product->qty=1;
            $amount=$product->qty*$product->sales_rate;
            $vat_par=($amount*$product->vat_per)/100;
            $source_tax_per=($amount*$product->source_tax_per)/100;
            $gross_amount=$amount+$vat_par+$source_tax_per;
            $product->gross_amount=number_format($gross_amount,2,'.','');
            $product->display_amount=number_format($amount,2);
            $product->amount=number_format($amount,2,'.','');
            $product->display_qty=number_format($product->qty,0);
            $product->display_sales_rate=number_format($product->sales_rate,4);
            $product->sales_rate=number_format($product->sales_rate,4,'.','');
            $product->vat=number_format($vat_par,4,'.','');
            $product->source_tax=number_format($source_tax_per,4,'.','');
            return $product;
        })->first();
        echo json_encode($product);
    }

    public function getInvoice()
    {
      $invoice=$this->sale
      ->where([['id','=',request('id',0)]])
      ->get()
      ->map(function($invoice){
        $invoice->invoice_date=date('d-M-Y',strtotime($invoice->created_at));
        $invoice->invoice_time=date('H:m:s',strtotime($invoice->created_at));
        return $invoice;
      })->first();
      //echo json_encode($invoice);
     $product=$this->sale
      ->join('srm_product_scans', function($join) {
            $join->on('srm_product_scans.srm_product_sale_id', '=', 'srm_product_sales.id');
      })
      ->join('srm_product_receive_dtls', function($join) {
            $join->on('srm_product_receive_dtls.id', '=', 'srm_product_scans.srm_product_receive_dtl_id');
      })
      ->where([['srm_product_sales.id','=',request('id',0)]])
      ->get(['srm_product_receive_dtls.style_gmt_name',
        'srm_product_receive_dtls.size_name',
        'srm_product_receive_dtls.color_name',
        'srm_product_scans.qty',
        'srm_product_scans.sales_rate',
        'srm_product_scans.amount',
        'srm_product_scans.vat_per',
        'srm_product_scans.source_tax_per',
        'srm_product_scans.gross_amount',
      ])
      ->map(function($product){
        $product->decs=$product->style_gmt_name.",".$product->color_name.",".$product->size_name;
        $product->display_qty=number_format($product->qty,0);
        $product->display_rate=number_format($product->sales_rate,2);
        $product->display_amount=number_format($product->amount,2);
        return $product;
      });
       $invoice->qty=number_format($product->sum('qty'),0);
       $invoice->amount=number_format($product->sum('amount'),2);
       $invoice->vat=number_format($product->sum('vat_per'),2);
       $invoice->stax=number_format($product->sum('source_tax_per'),2);
       $invoice->gross_amount=number_format($product->sum('gross_amount'),2);
       $invoice->discount_amount=number_format( $invoice->discount_amount,2);
       $invoice->net_paid_amount=number_format( $invoice->net_paid_amount,2);
      return Template::loadView('ShowRoom.SrmProductSaleInvoice',['invoice'=>$invoice,'product'=>$product]);
    }

    public function getDtailInvoicePdf()
    {
	    $id=request('id',0);
	      $invoice=$this->sale
	      ->where([['id','=',$id]])
	      ->get()
	      ->map(function($invoice){
	        $invoice->invoice_date=date('d-M-Y',strtotime($invoice->created_at));
	        $invoice->invoice_time=date('H:m:s',strtotime($invoice->created_at));
	        return $invoice;
	      })->first();
	      //echo json_encode($invoice);
	     $product=$this->sale
	      ->join('srm_product_scans', function($join) {
	            $join->on('srm_product_scans.srm_product_sale_id', '=', 'srm_product_sales.id');
	      })
	      ->join('srm_product_receive_dtls', function($join) {
	            $join->on('srm_product_receive_dtls.id', '=', 'srm_product_scans.srm_product_receive_dtl_id');
	      })
	      ->where([['srm_product_sales.id','=',$id ]])
	      ->get(['srm_product_receive_dtls.style_gmt_name',
	        'srm_product_receive_dtls.size_name',
	        'srm_product_receive_dtls.color_name',
	        'srm_product_scans.qty',
	        'srm_product_scans.sales_rate',
	        'srm_product_scans.amount',
	        'srm_product_scans.vat_per',
	        'srm_product_scans.source_tax_per',
	        'srm_product_scans.gross_amount',
	      ])
	      ->map(function($product){
	        $product->decs=$product->style_gmt_name.",".$product->color_name.",".$product->size_name;
	        $product->display_qty=number_format($product->qty,0);
	        $product->display_rate=number_format($product->sales_rate,2);
	        $product->display_amount=number_format($product->amount,2);
	        return $product;
	      });
	       $invoice->qty=number_format($product->sum('qty'),0);
	       $invoice->amount=number_format($product->sum('amount'),2);
	       $invoice->vat=number_format($product->sum('vat_per'),2);
	       $invoice->stax=number_format($product->sum('source_tax_per'),2);
	       $invoice->gross_amount=number_format($product->sum('gross_amount'),2);
	       $invoice->discount_amount=number_format( $invoice->discount_amount,2);
	       $invoice->net_paid_amount=number_format( $invoice->net_paid_amount,2);

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
	       $pdf->SetY(10);
	       //$employeelist = $this->reportData();

	       $txt = "Lithe Group Invoice Pay Slip";
	       //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
	       $pdf->SetY(5);
	       $pdf->Text(80, 5, $txt);
	       $pdf->SetY(10);
	       $pdf->SetFont('helvetica', 'N', 10);
	       //$pdf->Text(60, 10, $data['company']->address);
	       $pdf->SetFont('helvetica', '', 8);
	       $id=request('id',0);



	       $view= \View::make('Defult.ShowRoom.SrmProductSaleInvoicePdf',['invoice'=>$invoice,'product'=>$product]);
	       $html_content=$view->render();
	       $pdf->SetY(15);
	       $pdf->WriteHtml($html_content, true, false,true,false,'');
	       $filename = storage_path() . '/SrmProductSaleInvoicePdf.pdf';
	       //echo $html_content;
	       //$pdf->output($filename);
	       $pdf->output($filename,'I');
	       exit();
	       //$pdf->output($filename,'F');
	       //return response()->download($filename);

	      //return Template::loadView('ShowRoom.SrmProductSaleInvoicePdf',['invoice'=>$invoice,'product'=>$product]);
    }

}
