<?php
namespace App\Http\Controllers\Report\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\ShowRoom\SrmProductSaleRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\ShowRoom\SrmProductReceiveDtlRepository;
use App\Repositories\Contracts\ShowRoom\SrmProductScanRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\ShowRoom\SrmProductReceiveRepository;


class GarmentStockReportController extends Controller
{
    private $sale;
    private $salesordergmtcolorsize;
    private $productreceivedtl;
    private $srmproductscan;

    private $salesorder;
    private $buyer;
	  private $company;
    private $supplier;
    private $style;
    private $user;
    private $srmproductreceive;


	public function __construct(
        SrmProductSaleRepository $sale,
        SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
        SrmProductReceiveRepository $srmproductreceive,
        SrmProductReceiveDtlRepository $productreceivedtl,
        SrmProductScanRepository $srmproductscan,
        SalesOrderRepository $salesorder,
        CompanyRepository $company, 
        BuyerRepository $buyer,
        SupplierRepository $supplier,
        UserRepository $user,
        StyleRepository $style
  )
    {
        $this->sale = $sale;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->srmproductreceive = $srmproductreceive;
        $this->productreceivedtl = $productreceivedtl;
        $this->srmproductscan = $srmproductscan;
        $this->salesorder = $salesorder;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
        $this->user = $user;
        $this->style = $style;

      $this->middleware('auth');
		//$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');
      return Template::loadView('Report.POS.GarmentStockReport',['company'=>$company,'buyer'=>$buyer,'supplier'=>$supplier]);
    }
	public function reportData() {
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $start_date=date('Y-m-d', strtotime($date_from));
		    $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));

        /*$gmtstock=$this->srmproductreceive
        ->selectRaw('
          srm_product_receives.receive_date,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.style_ref,
          srm_product_receive_dtls.color_name,
          srm_product_receive_dtls.size_name,
          opening.qty as opening_stock
        ')
        ->leftJoin('srm_product_receive_dtls',function($join){
          $join->on('srm_product_receives.id','=','srm_product_receive_dtls.srm_product_receive_id');
        })
        ->join('srm_product_scans', function($join) {
          $join->on('srm_product_receive_dtls.id', '=', 'srm_product_scans.srm_product_receive_dtl_id');
        })
        ->join('srm_product_sales', function($join) {
          $join->on('srm_product_scans.srm_product_sale_id', '=', 'srm_product_sales.id');
        })
          ->leftJoin(\DB::raw("(SELECT
           srm_product_receives.id as srm_product_receive_id,
           sum(srm_product_receive_dtls.qty) as qty 
          FROM srm_product_receives 
          join
          srm_product_receive_dtls
            on srm_product_receive_dtls.srm_product_receive_id = srm_product_receives.id
          where 
            srm_product_receives.receive_date<='".$yesterday."' 
          group by
          srm_product_receives.id) opening"),
           "opening.srm_product_receive_id", "=", "srm_product_receives.id")
        ->when($date_from, function ($q) use($date_from){
          return $q->where('srm_product_receives.receive_date', '>=',$date_from);
        })
        ->when($date_to, function ($q) use($date_to) {
            return $q->where('srm_product_receives.receive_date', '<=',$date_to);
        })
        ->when(request('company_id'), function ($q) {
            return $q->where('srm_product_receive_dtls.company_id', '=', request('company_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
            return $q->where('exp_lc_scs.buyer_id', '=', request('buyer_id', 0));
        })
        ->when(request('color_id'), function ($q) {
            return $q->where('srm_product_receive_dtls.color_id', '=', request('color_id', 0));
        })
        ->when(request('style_gmt_name'), function ($q) {
            return $q->where('srm_product_receive_dtls.style_gmt_name', 'LIKE', "%".request('style_gmt_name', 0)."%");
        })
        ->when(request('size_name'), function ($q) {
            return $q->where('srm_product_receive_dtls.size_name', 'LIKE', "%".request('size_name', 0)."%");
        })
        ->groupBy([
          'srm_product_receives.receive_date',
          'srm_product_receive_dtls.style_gmt_name',
          'srm_product_receive_dtls.style_ref',
          'srm_product_receive_dtls.color_name',
          'srm_product_receive_dtls.size_name',
          'opening.qty'
        ])
        ->get();*/
        $gmtstock =collect( \DB::select("
          select 
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_ref,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name,
          sum (srm_product_receive_dtls.qty) as qty,
          sum (srm_product_receive_dtls.amount) as amount,
          receive.qty as receive_qty,
          opening_receive.qty as open_receive_qty,
          sales.qty as sale_qty,
          sales.amount as sale_amount,
          opening_sales.qty as open_sale_qty
          from srm_product_receives
          join srm_product_receive_dtls on srm_product_receives.id=srm_product_receive_dtls.srm_product_receive_id
          left join( 
          select
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name,
          sum(srm_product_receive_dtls.qty) as qty
          from srm_product_receives
          join srm_product_receive_dtls on srm_product_receives.id=srm_product_receive_dtls.srm_product_receive_id
          where srm_product_receives.receive_date>='".$date_from."' 
          and srm_product_receives.receive_date<='".$date_to."'
          group by 
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name
          ) receive on receive.style_id=srm_product_receive_dtls.style_id 
          and receive.style_gmt_name=srm_product_receive_dtls.style_gmt_name
          and receive.size_name=srm_product_receive_dtls.size_name
          and receive.color_name=srm_product_receive_dtls.color_name

          left join( 
          select
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name,
          sum(srm_product_receive_dtls.qty) as qty
          from srm_product_receives
          join srm_product_receive_dtls on srm_product_receives.id=srm_product_receive_dtls.srm_product_receive_id
          where srm_product_receives.receive_date < '".$date_from."' 
          group by 
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name
          ) opening_receive on opening_receive.style_id=srm_product_receive_dtls.style_id 
          and opening_receive.style_gmt_name=srm_product_receive_dtls.style_gmt_name
          and opening_receive.size_name=srm_product_receive_dtls.size_name
          and opening_receive.color_name=srm_product_receive_dtls.color_name


          left join( 
          select
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name,
          sum(srm_product_scans.qty) as qty,
          sum(srm_product_scans.amount) as amount
          from srm_product_receives
          join srm_product_receive_dtls on srm_product_receives.id=srm_product_receive_dtls.srm_product_receive_id
          join srm_product_scans on srm_product_scans.srm_product_receive_dtl_id=srm_product_receive_dtls.id
          join srm_product_sales on srm_product_sales.id=srm_product_scans.srm_product_sale_id
          where srm_product_sales.scan_date>='".$date_from."'  
          and srm_product_sales.scan_date<='".$date_to."' 
          group by 
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name
          ) sales on sales.style_id=srm_product_receive_dtls.style_id 
          and sales.style_gmt_name=srm_product_receive_dtls.style_gmt_name
          and sales.size_name=srm_product_receive_dtls.size_name
          and sales.color_name=srm_product_receive_dtls.color_name

          left join( 
          select
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name,
          sum(srm_product_scans.qty) as qty
          from srm_product_receives
          join srm_product_receive_dtls on srm_product_receives.id=srm_product_receive_dtls.srm_product_receive_id
          join srm_product_scans on srm_product_scans.srm_product_receive_dtl_id=srm_product_receive_dtls.id
          join srm_product_sales on srm_product_sales.id=srm_product_scans.srm_product_sale_id
          where srm_product_sales.scan_date<='".$date_from."' 

          group by 
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name
          ) opening_sales on opening_sales.style_id=srm_product_receive_dtls.style_id 
          and opening_sales.style_gmt_name=srm_product_receive_dtls.style_gmt_name
          and opening_sales.size_name=srm_product_receive_dtls.size_name
          and opening_sales.color_name=srm_product_receive_dtls.color_name

          group by 
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_ref,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name,
          receive.qty,
          opening_receive.qty,
          sales.qty,
          sales.amount,
          opening_sales.qty"))
          ->map(function($gmtstock){
            // $gmtstock->rate=$gmtstock->amount/$gmtstock->qty;
            // $opening_stock=$gmtstock->open_receive_qty-$gmtstock->open_sale_qty;
            // $gmtstock->opening_stock=$opening_stock;
            // $gmtstock->total_qty=$opening_stock+$gmtstock->receive_qty;
            // $gmtstock->closing_stock=$gmtstock->total_qty-$gmtstock->sale_qty;
            // $gmtstock->stock_value=$gmtstock->closing_stock*$gmtstock->rate;
            
              $rate=$gmtstock->amount/$gmtstock->qty;
              $opening_stock=$gmtstock->open_receive_qty-$gmtstock->open_sale_qty;
              $total_qty=$opening_stock+$gmtstock->receive_qty;
              $closing_stock=$total_qty-$gmtstock->sale_qty;
              $stock_value=$closing_stock*$rate;
              $gmtstock->opening_stock=number_format($opening_stock,0);
              $gmtstock->total_qty=number_format($total_qty,0);
              $gmtstock->closing_stock=number_format($closing_stock,0);
              $gmtstock->stock_value=number_format($stock_value,2);
              $gmtstock->rate=number_format($rate,4);
              $gmtstock->receive_qty=number_format($gmtstock->receive_qty,0);
              $gmtstock->sale_qty=number_format($gmtstock->sale_qty,0);
              $gmtstock->sale_amount=number_format($gmtstock->sale_amount,2);

            return $gmtstock;

          });

        echo json_encode($gmtstock);
    }

     public function getReceiveQty(){
      
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $style_id=request('style_id',0);
      $style_gmt_name=request('style_gmt_name',0);
      $color_name=request('color_name',0);
      $size_name=request('size_name',0);

      $receiveqty=collect(\DB::select("
		select srm_product_receive_dtls.id as srm_product_receive_dtl_id,
		srm_product_receives.receive_date,
		exp_invoices.invoice_no,
		srm_product_receive_dtls.style_id,
		srm_product_receive_dtls.style_ref,
		srm_product_receive_dtls.style_gmt_name,
		srm_product_receive_dtls.size_name,
		srm_product_receive_dtls.color_name,
		sum(srm_product_receive_dtls.qty) as qty,
		avg(srm_product_receive_dtls.rate) as receive_rate,
		sum(srm_product_receive_dtls.amount) as receive_amount
		from srm_product_receives
		join exp_invoices on exp_invoices.id = srm_product_receives.exp_invoice_id 
		join srm_product_receive_dtls on srm_product_receives.id=srm_product_receive_dtls.srm_product_receive_id
		where srm_product_receives.receive_date>='".$date_from."' 
		and srm_product_receives.receive_date<='".$date_to."'
		and srm_product_receive_dtls.style_id = '".$style_id."'
		and srm_product_receive_dtls.style_gmt_name = '".$style_gmt_name."'
		and srm_product_receive_dtls.color_name = '".$color_name."'
		and srm_product_receive_dtls.size_name = '".$size_name."'

		GROUP BY 
		srm_product_receive_dtls.id,
		srm_product_receives.receive_date,
		exp_invoices.invoice_no,
		srm_product_receive_dtls.style_id,
		srm_product_receive_dtls.style_ref,
		srm_product_receive_dtls.style_gmt_name,
		srm_product_receive_dtls.size_name,
		srm_product_receive_dtls.color_name"))
      ->map(function($receiveqty){
        $receiveqty->receive_qty=number_format($receiveqty->qty,0);
        $receiveqty->receive_rate=number_format($receiveqty->receive_rate,4);
        $receiveqty->receive_amount=number_format($receiveqty->receive_amount,2);
        $receiveqty->receive_date=date('d-M-Y',strtotime($receiveqty->receive_date));
        return $receiveqty;
      });
      echo json_encode($receiveqty);
    }

    public function getSalesQty(){
      
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $style_id=request('style_id',0);
      $style_gmt_name=request('style_gmt_name',0);
      $color_name=request('color_name',0);
      $size_name=request('size_name',0);

      $salesqty=collect(\DB::select("
        select
        srm_product_receive_dtls.style_id,
        srm_product_receive_dtls.style_ref,
        srm_product_receive_dtls.style_gmt_name,
        srm_product_receive_dtls.size_name,
        srm_product_receive_dtls.color_name,
        srm_product_sales.scan_date,
        sum(srm_product_scans.qty) as qty,
        sum(srm_product_scans.amount) as amount,
        avg(srm_product_scans.sales_rate) as sales_rate

        FROM srm_product_sales
          left join srm_product_scans on 
          	srm_product_sales.id=srm_product_scans.srm_product_sale_id
          left join srm_product_receive_dtls on
            srm_product_scans.srm_product_receive_dtl_id=srm_product_receive_dtls.id

        
          where
          srm_product_sales.scan_date>='".$date_from."' 
          and srm_product_sales.scan_date<='".$date_to."'
          and srm_product_receive_dtls.style_id = '".$style_id."'
          and srm_product_receive_dtls.style_gmt_name = '".$style_gmt_name."'
          and srm_product_receive_dtls.color_name = '".$color_name."'
          and srm_product_receive_dtls.size_name = '".$size_name."'

        GROUP BY 
          srm_product_sales.scan_date,
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_ref,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name
      
      "))
      ->map(function($salesqty){
          $salesqty->sale_qty=number_format($salesqty->qty,0);
          $salesqty->sale_rate=number_format($salesqty->sales_rate,4);
          $salesqty->sale_amount=number_format($salesqty->amount,2);
          $salesqty->scan_date=date('d-M-Y',strtotime($salesqty->scan_date));
        return $salesqty;
      });
      echo json_encode($salesqty);
    }
}
