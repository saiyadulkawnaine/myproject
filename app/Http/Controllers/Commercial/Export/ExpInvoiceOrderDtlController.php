<?php

namespace App\Http\Controllers\Commercial\Export;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceOrderDtlRepository;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceOrderRepository;

use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpInvoiceOrderDtlRequest;

class ExpInvoiceOrderDtlController extends Controller {

    private $expinvoice;
    private $expinvoiceorderdtl;
    private $location;
    private $expinvoiceorder;


    public function __construct(ExpInvoiceOrderDtlRepository $expinvoiceorderdtl, ExpInvoiceRepository $expinvoice,LocationRepository $location,ExpInvoiceOrderRepository $expinvoiceorder
    ){
        
        $this->expinvoice = $expinvoice;
        $this->expinvoiceorderdtl = $expinvoiceorderdtl;
        $this->location = $location;
        $this->expinvoiceorder = $expinvoiceorder;

        $this->middleware('auth');

        // $this->middleware('permission:view.expinvoiceorderdtls',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.expinvoiceorderdtls', ['only' => ['store']]);
        // $this->middleware('permission:edit.expinvoiceorderdtls',   ['only' => ['update']]);
        // $this->middleware('permission:delete.expinvoiceorderdtls', ['only' => ['destroy']]);
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
        $expinvoiceorder=$this->expinvoiceorder
        ->selectRaw('
            exp_invoice_orders.id as exp_invoice_order_id,
            sizes.name as size_name,
            sizes.code as size_code,
            colors.name as color_name,
            colors.code as color_code,
            style_sizes.sort_id as size_sort_id,
            style_colors.sort_id as color_sort_id,
            sales_order_gmt_color_sizes.qty as color_qty,
            sales_order_gmt_color_sizes.rate as color_rate,
            sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
            item_accounts.item_description,
            countries.name as country_name,
            exp_invoice_orders.qty as invoice_qty,
            exp_invoice_orders.rate as invoice_rate,
            exp_invoice_orders.amount as invoice_amount,
            exp_invoice_order_dtls.id as exp_invoice_order_dtl_id,
            exp_invoice_order_dtls.qty,
            exp_invoice_order_dtls.rate,
            exp_invoice_order_dtls.amount
        ')
        ->leftJoin('exp_invoices', function($join)  {
            $join->on('exp_invoices.id', '=', 'exp_invoice_orders.exp_invoice_id');
        })
        ->leftJoin('exp_pi_orders', function($join)  {
            $join->on('exp_pi_orders.id', '=', 'exp_invoice_orders.exp_pi_order_id');
        })
        ->leftJoin('sales_orders', function($join)  {
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
            $join->on('countries.id','=','sales_order_countries.country_id');
        })
        
        ->leftJoin('exp_invoice_order_dtls',function($join){
            $join->on('exp_invoice_order_dtls.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
            $join->on('exp_invoice_order_dtls.exp_invoice_order_id','=','exp_invoice_orders.id');
            $join->whereNull('exp_invoice_order_dtls.deleted_at');
        })
        ->where([['exp_invoice_orders.id','=',request('exp_invoice_order_id',0)]])
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
        ->get();
        $saved = $expinvoiceorder->filter(function ($value) {
            if($value->exp_invoice_order_dtl_id){
                return $value;
            }
        });
        $new = $expinvoiceorder->filter(function ($value) {
            if(!$value->exp_invoice_order_dtl_id){
                return $value;
            }
        });
        return Template::LoadView('Commercial.Export.ExpInvoiceOrderDtlMatrix',['expcolorsizes'=>$new,'saved'=>$saved]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpInvoiceOrderDtlRequest $request) {
       
        $impDocAcceptId=0;
        
        $total_qty=0;
        $total_amount=0;
        $expInvoiceOrderId=0;
        $avg_rate=0;
        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            $expInvoiceOrderId=$request->exp_invoice_order_id[$index];
            if($sales_order_gmt_color_size_id && $request->qty[$index])
            {
                $total_qty+=$request->qty[$index];
                $total_amount+=$request->amount[$index];
            }
        }
        $avg_rate=$total_amount/$total_qty;
        $expinvoiceorder=$this->expinvoiceorder->find($expInvoiceOrderId);
        if($total_qty !=$expinvoiceorder->qty){
            return response()->json(array('success' => false,'message' => 'Invoice Qty not match'),200);

        }

        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            $expInvoiceOrderId=$request->exp_invoice_order_id[$index];
            if($sales_order_gmt_color_size_id && $request->qty[$index])
            {
                $expinvoiceorderdtl = $this->expinvoiceorderdtl->updateOrCreate(
                [
                    'sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,
                    'exp_invoice_order_id' => $request->exp_invoice_order_id[$index]],
                [
                    'qty' => $request->qty[$index],
                    'rate' => $request->rate[$index],
                    'amount' => $request->amount[$index],
                ]);
            }
        }

        $this->expinvoiceorder->update($expInvoiceOrderId,['amount'=>$total_amount,'rate'=>$avg_rate]);

        if($expinvoiceorderdtl){
            return response()->json(array('success' => true,'id' =>  $expinvoiceorderdtl->id,'exp_invoice_order_id' =>  $expInvoiceOrderId,'message' => 'Save Successfully'),200);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpInvoiceOrderDtlRequest $request, $id) {
         //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $expinvoiceorderdtl=$this->expinvoiceorderdtl->find($id);
        if($this->expinvoiceorderdtl->delete($id)){
            return response()->json(array('success' => true, 'exp_invoice_id' =>  $expinvoiceorderdtl->exp_invoice_id,'message' => 'Delete Successfully'),200);
        }
        else{
             return response()->json(array('success' => false, 'exp_invoice_id' =>   $expinvoiceorderdtl->exp_invoice_id,  'message' => 'Delete Not Successfull Because Subsequent Entry Found'),  200);
        }
    }
}
