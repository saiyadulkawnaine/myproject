<?php

namespace App\Http\Controllers\ShowRoom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
//use App\Repositories\Contracts\ShowRoom\SrmProductReceiveOrderRepository;
use App\Repositories\Contracts\ShowRoom\SrmProductReceiveRepository;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\UomRepository;

use App\Library\Template;
use App\Http\Requests\ShowRoom\SrmProductReceiveRequest;

class SrmProductReceiveController extends Controller {

    private $srmproductreceive;
    //private $inspectionorder;
    private $salesordercountry;
    private $gmtcolorsize;
    private $expinvoice;
    private $country;
    private $uom;
    private $currency;
    private $buyer;

 

    public function __construct(
        SrmProductReceiveRepository $srmproductreceive,
        //SrmProductReceiveOrderRepository $inspectionorder, 
        SalesOrderCountryRepository $salesordercountry,
        SalesOrderGmtColorSizeRepository $gmtcolorsize,
        ExpInvoiceRepository $expinvoice,
        BuyerRepository $buyer,
        UomRepository $uom,
        CurrencyRepository $currency,
        CountryRepository $country
    ) {
        $this->srmproductreceive = $srmproductreceive;
        //$this->inspectionorder = $inspectionorder;
        $this->salesordercountry = $salesordercountry;
        $this->gmtcolorsize = $gmtcolorsize;
        $this->expinvoice = $expinvoice;
        $this->country = $country;
        $this->uom = $uom;
        $this->buyer = $buyer;
        $this->currency = $currency;

        $this->middleware('auth');
        /*$this->middleware('permission:view.srmproductreceives',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.srmproductreceives', ['only' => ['store']]);
        $this->middleware('permission:edit.srmproductreceives',   ['only' => ['update']]);
        $this->middleware('permission:delete.srmproductreceives', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user = \Auth::user();
        $user_id=$user->id;
        //$srmproductreceives=array();
        $rows=$this->srmproductreceive
        ->join('exp_invoices',function($join){
            $join->on('exp_invoices.id','=','srm_product_receives.exp_invoice_id');
        })
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_invoices.exp_lc_sc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
		->join('buyer_users', function($join) use($user_id) {
			$join->on('buyer_users.buyer_id', '=', 'buyers.id');
			$join->where('buyer_users.user_id', '=', $user_id);
		})
        ->get([
            'srm_product_receives.*',
            'exp_invoices.invoice_no',
        ])
        ->map(function($rows){
            $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
            return $rows;
        });
        /* foreach($rows as $row){
            $srmproductreceive['id']=$row->id;
            $srmproductreceive['sale_order_no']=$row->sale_order_no;
            $srmproductreceive['country_id']=$row->country_id;
            $srmproductreceive['receive_date']=date('d-m-Y',strtotime($row->receive_date));
            array_push($srmproductreceives,$srmproductreceive);
        } */
        //echo json_encode($srmproductreceives);
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-',0);
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-',0);
        return Template::loadView('ShowRoom.SrmProductReceive',['uom'=>$uom,'buyer'=>$buyer,'currency'=>$currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SrmProductReceiveRequest $request) {
		$srmproductreceive = $this->srmproductreceive->create([
            'exp_invoice_id'=>$request->exp_invoice_id,
            'receive_date'=>$request->receive_date
        ]);
        if($srmproductreceive){
            return response()->json(array('success' => true,'id' =>  $srmproductreceive->id ,'message' => 'Save Successfully'),200);
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
        $srmproductreceive = $this->srmproductreceive
        ->join('exp_invoices',function($join){
            $join->on('exp_invoices.id','=','srm_product_receives.exp_invoice_id');
        })
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_invoices.exp_lc_sc_id');
        })
        ->where([['srm_product_receives.id','=',$id]])
        ->get([
            'srm_product_receives.*',
            'exp_invoices.invoice_no',
        ])
        ->first();

        $receivedtail = $this->srmproductreceive
        ->join('exp_invoices',function($join){
            $join->on('exp_invoices.id','=','srm_product_receives.exp_invoice_id');
        })
        ->join('exp_invoice_orders', function($join)  {
            $join->on('exp_invoices.id', '=', 'exp_invoice_orders.exp_invoice_id');
        })
        ->join('exp_pi_orders', function($join)  {
            $join->on('exp_pi_orders.id', '=', 'exp_invoice_orders.exp_pi_order_id');
        })
        ->join('sales_orders', function($join)  {
            $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
            //$join->on('sales_order_countries.country_id', '=', 'exp_invoices.country_id');
        })
        ->leftJoin('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
        })
        ->leftJoin('style_gmt_color_sizes', function($join)  {
            $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->leftJoin('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_gmt_color_sizes.style_gmt_id');
        })
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','style_gmts.item_account_id');
        })
        ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->leftJoin('style_colors',function($join){
            $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','style_colors.color_id');
        })
        ->leftJoin('style_sizes',function($join){
            $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->leftJoin('sizes',function($join){
            $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->leftJoin('countries',function($join){
            $join->on('countries.id','=','exp_invoices.country_id');
        })
        ->join('exp_lc_scs', function($join)  {
            $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
        })
        ->leftJoin('currencies', function($join)  {
           $join->on('currencies.country_id', '=', 'countries.id');
            //$join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
         })
        ->join('exp_invoice_order_dtls',function($join){
            $join->on('exp_invoice_order_dtls.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
            $join->on('exp_invoice_order_dtls.exp_invoice_order_id','=','exp_invoice_orders.id');
            $join->whereNull('exp_invoice_order_dtls.deleted_at');
        })
        ->leftJoin('srm_product_receive_dtls',function($join){
            $join->on('srm_product_receives.id','=','srm_product_receive_dtls.srm_product_receive_id');
            $join->on('sales_order_gmt_color_sizes.id','=','srm_product_receive_dtls.sales_order_gmt_color_size_id');
        })
        ->orderBy('styles.id')
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id') 
        ->where([['srm_product_receives.id','=',$id]])
        ->get([
            'srm_product_receives.id as srm_product_receive_id',
            'exp_invoices.country_id',
            'styles.id as style_id',
            'styles.style_ref as style_ref',
            'jobs.id as job_id',
            'jobs.job_no',
            'sales_orders.id as sales_order_id',
            'sales_orders.sale_order_no',
            'colors.id as color_id',
            'colors.name as color_name',
            'colors.code as color_code',
            'sizes.id as size_id',
            'sizes.name as size_name',
            'uoms.code as uom_code',
            'uoms.id as uom_id',
            'currencies.code as currency_code',
            //'exp_lc_scs.currency_id as scurrency_id',
            'currencies.id as currency_id',

            'style_gmts.id as style_gmt_id',
            'style_colors.sort_id as color_sort_id',
            'sales_order_gmt_color_sizes.qty as colorsize_qty',
            'sales_order_gmt_color_sizes.rate as colorsize_rate',
            'sales_order_gmt_color_sizes.amount as colorsize_amount',
            'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id',
            'item_accounts.item_description',
            'item_accounts.uom_id',
            'srm_product_receive_dtls.id as srm_product_receive_dtl_id',
            //'srm_product_receive_dtls.srm_product_receive_id',
            // 'srm_product_receive_dtls.style_id',
            // 'srm_product_receive_dtls.style_ref',
            // 'srm_product_receive_dtls.style_gmt_id',
            // 'srm_product_receive_dtls.job_id',
            // 'srm_product_receive_dtls.sales_order_id',
            // 'srm_product_receive_dtls.sale_order_no',
            // 'srm_product_receive_dtls.size_id',
            // 'srm_product_receive_dtls.size_name',
            // 'srm_product_receive_dtls.color_id',
            // 'srm_product_receive_dtls.color_name',
            'srm_product_receive_dtls.style_gmt_name',
            //'srm_product_receive_dtls.currency_id',
            'srm_product_receive_dtls.qty',
            'srm_product_receive_dtls.rate',
            'srm_product_receive_dtls.amount',
            'srm_product_receive_dtls.sales_rate',
            'srm_product_receive_dtls.vat_per',
            'srm_product_receive_dtls.source_tax_per'
        ])
        ->map(function ($receivedtail) {
            //$receivedtail->currency_id=isset($currency[$receivedtail->currency_id])?$currency[$receivedtail->currency_id]:'';
            return $receivedtail;
        });

        $saved = $receivedtail->filter(function ($value) {
            if($value->srm_product_receive_dtl_id){
                return $value;
            }
        });
        $new = $receivedtail->filter(function ($value) {
            if(!$value->srm_product_receive_dtl_id){
                return $value;
            }
        });


       $row ['fromData'] = $srmproductreceive;
       $dropdown['receivedtalicosi'] = "'".Template::loadView('ShowRoom.SrmProductReceiveDtlMatrix',['colorsizes'=>$new,'saved'=>$saved])."'";
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
    public function update(SrmProductReceiveRequest $request, $id) {
        $srmproductreceive=$this->srmproductreceive->update($id,[
            'exp_invoice_id'=>$request->exp_invoice_id,
            'receive_date'=>$request->receive_date
        ]);
        if($srmproductreceive){
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
        if($this->srmproductreceive->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
   
    public function getExpInvoiceNo(){
        $user = \Auth::user();
        $user_id=$user->id;
        
        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
         
        $expinvoices=array();
        $rows=$this->expinvoice
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_invoices.exp_lc_sc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
		->join('buyer_users', function($join) use($user_id) {
			$join->on('buyer_users.buyer_id', '=', 'buyers.id');
			$join->where('buyer_users.user_id', '=', $user_id);
		})
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->when(request('invoice_no'), function ($q) {
            return $q->where('exp_invoices.invoice_no', 'LIKE',"%".request('invoice_no', 0)."%");
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('exp_invoices.invoice_date', '>=', 'date_from');
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('exp_invoices.invoice_date', '<=', 'date_to');
        })
        ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_invoices.lc_sc_no', 'LIKE',"%".request('lc_sc_no', 0)."%");
        })
        ->orderBy('exp_invoices.id','desc')
        ->get([
            'exp_lc_scs.id',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id',
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'exp_lc_scs.lien_date',
            'exp_lc_scs.hs_code',
            'exp_lc_scs.re_imbursing_bank',
            'exp_invoices.*',
            //'exp_invoices.id as exp_invoice_id'
            ]);
        foreach($rows as $row){
            $expinvoice['id']=$row->id;
            $expinvoice['exp_lc_sc_id']=$row->exp_lc_sc_id;
            $expinvoice['lc_sc_no']=$row->lc_sc_no;
            $expinvoice['invoice_no']=$row->invoice_no;
            $expinvoice['invoice_date']=date('Y-m-d',strtotime($row->invoice_date));
            $expinvoice['invoice_value']=$row->invoice_value;
            $expinvoice['exp_form_no']=$row->exp_form_no;
            $expinvoice['exp_form_date']=($row->exp_form_date !== null)?date('Y-m-d',strtotime($row->exp_form_date)):null;
            $expinvoice['actual_ship_date']=($row->actual_ship_date !==null)?date('Y-m-d',strtotime($row->actual_ship_date)):null;
            $expinvoice['country_id']=$country[$row->country_id];
            $expinvoice['remarks']=$row->remarks;

            array_push($expinvoices,$expinvoice);
        }
        echo json_encode($expinvoices);
        
    }
    /*     //     ->join('exp_invoice_orders', function($join)  {
        //         $join->on('exp_invoices.id', '=', 'exp_invoice_orders.exp_invoice_id');
        //     })
        //     ->join('exp_pi_orders', function($join)  {
        //         $join->on('exp_pi_orders.id', '=', 'exp_invoice_orders.exp_pi_order_id');
        //     })
        //     ->join('sales_orders', function($join)  {
        //         $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
        //     })
        //     ->join('exp_invoice_order_dtls',function($join){
        //         //$join->on('exp_invoice_order_dtls.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        //         $join->on('exp_invoice_order_dtls.exp_invoice_order_id','=','exp_invoice_orders.id');
        //         $join->whereNull('exp_invoice_order_dtls.deleted_at');
        //     })

        //     /* ->join('sales_order_countries', function($join)  {
        //         $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
        //     }) */
        //     ->join('jobs', function($join)  {
        //         $join->on('jobs.id', '=', 'sales_orders.job_id');
        //     })
        //     ->join('styles', function($join)  {
        //         $join->on('styles.id', '=', 'jobs.style_id');
        //     })
        //    ->join('sales_order_gmt_color_sizes', function($join)  {
        //         $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
        //     })
        //     ->leftJoin('style_gmt_color_sizes', function($join)  {
        //         $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
        //     })
        //     ->leftJoin('style_gmts',function($join){
        //         $join->on('style_gmts.id','=','style_gmt_color_sizes.style_gmt_id');
        //     })
        //     ->leftJoin('item_accounts',function($join){
        //       $join->on('item_accounts.id','=','style_gmts.item_account_id');
        //     })
        //     ->leftJoin('uoms',function($join){
        //       $join->on('uoms.id','=','item_accounts.uom_id');
        //     })
        //     ->leftJoin('style_colors',function($join){
        //         $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        //     })
        //     ->leftJoin('colors',function($join){
        //         $join->on('colors.id','=','style_colors.color_id');
        //     })
        //     ->leftJoin('style_sizes',function($join){
        //         $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        //     })
        //     ->leftJoin('sizes',function($join){
        //         $join->on('sizes.id','=','style_sizes.size_id');
        //     })
            
        //     ->leftJoin('srm_product_receive_dtls',function($join){
        //         $join->on('srm_product_receives.id','=','srm_product_receive_dtls.srm_product_receive_id');
        //         $join->on('sales_order_gmt_color_sizes.id','=','srm_product_receive_dtls.sales_order_gmt_color_size_id');
        //     }) */

}