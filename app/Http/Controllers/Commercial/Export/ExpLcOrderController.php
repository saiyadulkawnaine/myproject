<?php
namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpScOrderRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Commercial\Export\ExpPiRepository;
use App\Repositories\Contracts\Commercial\Export\ExpPiOrderRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScPiRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpScOrderRequest;

class ExpLcOrderController extends Controller {

    private $explcsc;
    private $expscorder;
    private $itemaccount;
    private $salesorder;
    private $job;
    private $exppi;
    private $exppiorder;
    private $lcscpi;

    public function __construct(ExpScOrderRepository $expscorder, ExpLcScRepository $explcsc,ItemAccountRepository $itemaccount, SalesOrderRepository $salesorder, StyleRepository $style, JobRepository $job,ExpPiRepository $exppi, ExpPiOrderRepository $exppiorder,ExpLcScPiRepository $lcscpi) {
        $this->explcsc = $explcsc;
        $this->expscorder = $expscorder;
        $this->itemaccount = $itemaccount;
        $this->salesorder = $salesorder;
        $this->job = $job;
        $this->style = $style;
        $this->exppi = $exppi;
        $this->exppiorder = $exppiorder;
        $this->lcscpi = $lcscpi;
        $this->middleware('auth');
        $this->middleware('permission:view.explcorders',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.explcorders', ['only' => ['store']]);
        $this->middleware('permission:edit.explcorders',   ['only' => ['update']]);
        $this->middleware('permission:delete.explcorders', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
     
    $orders=$this->salesorder
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
        ->join('uoms', function($join)  {
            $join->on('styles.uom_id', '=', 'uoms.id');
        })
        ->join('exp_pi_orders', function($join)  {
            $join->on('exp_pi_orders.sales_order_id', '=', 'sales_orders.id');
            $join->whereNull('exp_pi_orders.deleted_at');
        })
        ->join('exp_pis', function($join)  {
        $join->on('exp_pis.id', '=', 'exp_pi_orders.exp_pi_id');
        })
        ->join('exp_lc_sc_pis', function($join)  {
        $join->on('exp_lc_sc_pis.exp_pi_id', '=', 'exp_pis.id');
        })
        ->where([['exp_lc_sc_pis.exp_lc_sc_id','=', request('exp_lc_sc_id',0)]])
       ->get([
        'exp_pi_orders.id',
        'sales_orders.id as sales_order_id',
        'sales_orders.sale_order_no',
        'styles.style_ref',
        'jobs.job_no',
        'uoms.code as uom_name',
        'item_accounts.id as item_account_id',
        'item_accounts.item_description',
        'exp_pi_orders.qty',
        'exp_pi_orders.rate',
        'exp_pi_orders.amount',
       ]);
       $row=array();
       $rows=array();
       foreach($orders as $order){
        $row[$order->id]['id']=$order->id;
        $row[$order->id]['sale_order_no']=$order->sale_order_no;
        $row[$order->id]['sales_order_id']=$order->sales_order_id;
        $row[$order->id]['style_ref']=$order->style_ref;
        $row[$order->id]['job_no']=$order->job_no;
        $row[$order->id]['uom_name']=$order->uom_name;
        $row[$order->id]['item_description'][$order->item_account_id]=$order->item_description;
        $row[$order->id]['qty']=$order->qty;  
        $row[$order->id]['amount']=$order->amount;  
        
       }

        $datas=array();
        foreach($row as $orderid=>$value){
            $value['item_description']=implode(',',$value['item_description']);
            if($value['qty']){
                $value['rate']=number_format($value['amount']/$value['qty'],4);
            }
            array_push($datas, $value);
        }
        echo json_encode($datas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $sales_order_id=request('sales_order_id',0);
        $idArr=explode(',',$sales_order_id);

        $items=$this->salesorder
       ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
       ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('style_gmts',function($join){
        $join->on('style_gmts.style_id','=','styles.id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
       
        ->whereIn('sales_orders.id', $idArr)
        ->get([
        'sales_orders.id',
        'item_accounts.item_description'
        ])
        ->groupBy('id')
        ->map(function ($item, $key) {
        return $item->implode('item_description',', ');
        })
        ->toArray();

       $orders=$this->salesorder
       ->selectRaw('
        sales_orders.id,
        sales_orders.sale_order_no,
        styles.style_ref,
        jobs.job_no,
        uoms.code as uom_name,
        cumulatives.cumulative_qty,
        sum(sales_order_gmt_color_sizes.qty) as qty,
        avg(sales_order_gmt_color_sizes.rate) as rate,
        sum(sales_order_gmt_color_sizes.amount) as amount
        ')
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
        ->join('uoms', function($join)  {
        $join->on('styles.uom_id', '=', 'uoms.id');
        })
        ->leftJoin(\DB::raw("(SELECT sales_orders.id as sales_order_id,sum(exp_pi_orders.qty) as cumulative_qty FROM exp_pi_orders right join sales_orders on sales_orders.id = exp_pi_orders.sales_order_id   group by sales_orders.id) cumulatives"), "cumulatives.sales_order_id", "=", "sales_orders.id")
       ->whereIn('sales_orders.id', $idArr)
        ->groupBy([
        'sales_orders.id',
        'sales_orders.sale_order_no',
        'styles.style_ref',
        'jobs.job_no',
        'uoms.code',
        'cumulatives.cumulative_qty'
       ])
       ->get()
       ->map(function ($orders) use($items){
        $orders->item_description=$items[$orders->id];
        $orders->balance_qty=$orders->qty-$orders->cumulative_qty;
        if($orders->qty){
            $orders->rate=$orders->amount/$orders->qty;
        }
        $orders->tagable_amount=$orders->balance_qty*$orders->rate;
        return $orders;
        });
        return Template::loadView('Commercial.Export.ExpPiOrderMatrix',['orders'=>$orders]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpScOrderRequest $request) {

        $max = $this->exppi->where([['company_id', $request->company_id]])->max('sys_pi_no');
        $sys_pi_no=$max+1;
        $request->request->add(['sys_pi_no' => $sys_pi_no]);
        $request->request->add(['pi_no' => $sys_pi_no]);


        \DB::beginTransaction();

        $exppi=$this->exppi->create(['company_id'=>$request->company_id,'itemclass_id'=>$request->itemclass_id,'buyer_id'=>$request->buyer_id,'pi_date'=>$request->pi_date,'pay_term_id'=>$request->pay_term_id,'incoterm_id'=>$request->incoterm_id,'delivery_date'=>$request->delivery_date,'pi_no'=>$request->pi_no,'sys_pi_no'=>$request->sys_pi_no]);

        //$request->request->add(['exp_pi_id' => $exppi->id]);

        foreach($request->sales_order_id as $index=>$sales_order_id){

            try
            {
           
                $exppiorder = $this->exppiorder->create(
                ['exp_pi_id' => $exppi->id,'sales_order_id' => $sales_order_id,'qty' => $request->qty[$index],'rate' => $request->rate[$index],'amount' => $request->amount[$index]]);
            }
            catch(EXCEPTION $e)
            {
                \DB::rollback();
                throw $e;
                return response()->json(array('success' => false,'message' => 'Save not Successfull'),200);
            }
           
        }

        $lcscpi = $this->lcscpi->create(
                ['exp_pi_id' => $exppi->id,'exp_lc_sc_id' => $request->exp_lc_sc_id]);
        \DB::commit();

        if($lcscpi){
            return response()->json(array('success'=>true,'id'=>$exppiorder->id,'message'=>'Save Successfully'),200);
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
       $items=$this->salesorder
       ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
       ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('style_gmts',function($join){
        $join->on('style_gmts.style_id','=','styles.id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        
        ->join('exp_pi_orders', function($join)  {
        $join->on('exp_pi_orders.sales_order_id', '=', 'sales_orders.id');
        })
        ->join('exp_pis', function($join)  {
        $join->on('exp_pis.id', '=', 'exp_pi_orders.exp_pi_id');
        })
        ->where([['exp_pi_orders.id','=', $id]])
        ->get([
        'sales_orders.id',
        'item_accounts.item_description'
        ])
        ->groupBy('id')
        ->map(function ($item, $key) {
        return $item->implode('item_description',', ');
        })
        ->toArray();
       $orders=$this->salesorder
       ->selectRaw('
        sales_orders.id as sales_order_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        jobs.job_no,
        uoms.code as uom_name,
        cumulatives.cumulative_qty,
        sum(sales_order_gmt_color_sizes.qty) as order_qty,
        avg(sales_order_gmt_color_sizes.rate) as order_rate,
        sum(sales_order_gmt_color_sizes.amount) as order_amount,
        exp_pi_orders.id,
        exp_pi_orders.qty,
        exp_pi_orders.rate,
        exp_pi_orders.amount
        ')
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
        ->join('uoms', function($join)  {
        $join->on('styles.uom_id', '=', 'uoms.id');
        })
        ->join('exp_pi_orders', function($join)  {
        $join->on('exp_pi_orders.sales_order_id', '=', 'sales_orders.id');
        })
        ->join('exp_pis', function($join)  {
        $join->on('exp_pis.id', '=', 'exp_pi_orders.exp_pi_id');
        })
        ->leftJoin(\DB::raw("(SELECT sales_orders.id as sales_order_id,sum(exp_pi_orders.qty) as cumulative_qty FROM exp_pi_orders right join sales_orders on sales_orders.id = exp_pi_orders.sales_order_id   group by sales_orders.id) cumulatives"), "cumulatives.sales_order_id", "=", "sales_orders.id")

        ->where([['exp_pi_orders.id','=', $id]])
        ->groupBy([
        'sales_orders.id',
        'sales_orders.sale_order_no',
        'styles.style_ref',
        'jobs.job_no',
        'uoms.code',
        'cumulatives.cumulative_qty',
        'exp_pi_orders.id',
        'exp_pi_orders.qty',
        'exp_pi_orders.rate',
        'exp_pi_orders.amount'
       ])
       ->get()
       ->map(function ($orders) use($items){
         $orders->item_description=$items[$orders->sales_order_id];
         $orders->balance_qty=$orders->order_qty-($orders->cumulative_qty-$orders->qty);
         return $orders;
        })->first();
       $row['fromData']=$orders;
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
    public function update(ExpScOrderRequest $request, $id) {
         $exppiorder=$this->exppiorder->update($id,[
            'qty'=>$request->qty,
            'rate'=>$request->rate,
            'amount'=>$request->amount
        ]);
        if($exppiorder){
            return response()->json(array('success'=>true,'id'=>$id,'message'=>'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->exppiorder->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
        else
        {
          return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
        } 
    }

    public function importorder ()
    {
       $explcsc=$this->explcsc->find(request('explcid',0));
       $exppi=$this->exppi->find(request('exppiid',0));
        $items=$this->salesorder
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.style_id','=','styles.id');
        })
        ->join('item_accounts', function($join) {
            $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->where([['jobs.company_id','=',$explcsc->beneficiary_id]])
        ->where([['styles.buyer_id','=',$explcsc->buyer_id]])
        ->get([
        'sales_orders.id',
        'item_accounts.item_description'
        ])
        ->groupBy('id')
        ->map(function ($item, $key) {
        return $item->implode('item_description',', ');
        })
        ->toArray();
       $orders=$this->salesorder
       ->selectRaw('
        sales_orders.id,
        sales_orders.sale_order_no,
        styles.style_ref,
        jobs.job_no,
        uoms.code as uom_name,
        cumulatives.cumulative_qty,
        sum(sales_order_gmt_color_sizes.qty) as qty,
        avg(sales_order_gmt_color_sizes.rate) as rate,
        sum(sales_order_gmt_color_sizes.amount) as amount
        ')
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
        ->join('uoms', function($join)  {
        $join->on('styles.uom_id', '=', 'uoms.id');
        })
        ->leftJoin(\DB::raw("(SELECT sales_orders.id as sales_order_id,sum(exp_pi_orders.qty) as cumulative_qty FROM exp_pi_orders right join sales_orders on sales_orders.id = exp_pi_orders.sales_order_id   group by sales_orders.id) cumulatives"), "cumulatives.sales_order_id", "=", "sales_orders.id")
       
        ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
        })
        ->when(request('job_no'), function ($q) {
            return $q->where('jobs.job_no', 'LIKE', "%".request('job_no', 0)."%");
        })
        ->when(request('sale_order_no'), function ($q) {
            return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
        })
        ->where([['jobs.company_id','=',$explcsc->beneficiary_id]])
        ->where([['styles.buyer_id','=',$explcsc->buyer_id]])
        ->groupBy([
        'sales_orders.id',
        'sales_orders.sale_order_no',
        'styles.style_ref',
        'jobs.job_no',
        'uoms.code',
        'cumulatives.cumulative_qty'
       ])
       ->get()
       ->map(function ($orders) use($items){
         $orders->item_description=isset($items[$orders->id])?$items[$orders->id]:'';
         $orders->balance_qty=$orders->qty-$orders->cumulative_qty;
         if($orders->qty){
            $orders->rate=$orders->amount/$orders->qty;
        }
         return $orders;
        });
       $notsaved = $orders->filter(function ($value) {
            if($value->balance_qty>0){
                return $value;
            }
        })->values();
       echo json_encode($notsaved);
    }
}
