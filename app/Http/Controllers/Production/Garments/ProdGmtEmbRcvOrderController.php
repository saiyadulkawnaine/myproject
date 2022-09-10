<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtEmbRcvOrderRequest;


class ProdGmtEmbRcvOrderController extends Controller {

    private $prodgmtembrcvorder;
    private $prodgmtembrcv;
    private $location;
    private $gmtembrcvqty;

    public function __construct(ProdGmtEmbRcvOrderRepository $prodgmtembrcvorder,ProdGmtEmbRcvRepository $prodgmtembrcv, LocationRepository $location,SalesOrderCountryRepository $salesordercountry,ProdGmtEmbRcvQtyRepository $gmtembrcvqty, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->prodgmtembrcvorder = $prodgmtembrcvorder;
        $this->prodgmtembrcv = $prodgmtembrcv;
        $this->gmtembrcvqty = $gmtembrcvqty;
        $this->salesordercountry = $salesordercountry;
        $this->location = $location;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtembrcvorders',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtembrcvorders', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtembrcvorders',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtembrcvorders', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');    
         
        $prodgmtembrcvorders=array();
        $rows=$this->prodgmtembrcvorder
        ->selectRaw('
        prod_gmt_emb_rcv_orders.id,
        sales_order_countries.id as sales_order_country_id,
        sales_orders.sale_order_no,
        sales_orders.ship_date,
        styles.style_ref,
        styles.id as style_id,
        jobs.job_no,
        jobs.company_id,
        buyers.code as buyer_name,
        countries.name as country_id,
        companies.name as company_id
        ')
        ->leftJoin('prod_gmt_emb_rcvs', function($join)  {
            $join->on('prod_gmt_emb_rcvs.id', '=', 'prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_emb_rcv_orders.sales_order_country_id');
        })
        ->leftJoin('countries', function($join) {
            $join->on('countries.id', '=', 'sales_order_countries.country_id');
        })
        ->leftJoin('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
        })
       ->leftJoin('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
       ->leftJoin('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
       ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
       
        ->leftJoin('buyers', function($join)  {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->where([['prod_gmt_emb_rcv_id','=', request('prod_gmt_emb_rcv_id',0)]])
        ->orderBy('prod_gmt_emb_rcv_orders.id','desc')
        ->get([
            'prod_gmt_emb_rcv_orders.*',
            'sales_orders.sale_order_no'
        ]);
        foreach($rows as $row){
            $prodgmtembrcvorder['id']=$row->id;
            $prodgmtembrcvorder['sales_order_country_id']=$row->sales_order_country_id;
            $prodgmtembrcvorder['sale_order_no']=$row->sale_order_no;
            $prodgmtembrcvorder['country_id']=$row->country_id;
            $prodgmtembrcvorder['buyer_name']=$row->buyer_name;
            $prodgmtembrcvorder['style_ref']=$row->style_ref;
            $prodgmtembrcvorder['job_no']=$row->job_no;
            $prodgmtembrcvorder['ship_date']=$row->ship_date;
            $prodgmtembrcvorder['fabric_look_id']=$fabriclooks[$row->fabric_look_id];
            array_push($prodgmtembrcvorders,$prodgmtembrcvorder);
        }
        echo json_encode($prodgmtembrcvorders);
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
    public function store(ProdGmtEmbRcvOrderRequest $request) {
		$prodgmtembrcvorder = $this->prodgmtembrcvorder->create([
            'prod_gmt_emb_rcv_id'=>$request->prod_gmt_emb_rcv_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'fabric_look_id'=>$request->fabric_look_id,
            'supplier_id'=>$request->supplier_id,
            'location_id'=>$request->location_id,
            'asset_quantity_cost_id'=>$request->asset_quantity_cost_id,
            'receive_hour'=>$request->receive_hour,        
            'prod_source_id'=>$request->prod_source_id,        
        ]);
        if($prodgmtembrcvorder){
            return response()->json(array('success' => true,'id' =>  $prodgmtembrcvorder->id,'message' => 'Save Successfully'),200);
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

        $prodgmtembrcvorder = $this->prodgmtembrcvorder
        ->leftJoin('prod_gmt_emb_rcvs', function($join)  {
            $join->on('prod_gmt_emb_rcvs.id', '=', 'prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_emb_rcv_orders.sales_order_country_id');
        })
        ->leftJoin('countries', function($join) {
            $join->on('countries.id', '=', 'sales_order_countries.country_id');
        })
        ->leftJoin('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
        })
        ->leftJoin('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
        })
        ->leftJoin('style_gmts',function($join){
            $join->on('style_gmts.id','=','sales_order_gmt_color_sizes.style_gmt_id');
        })
        ->leftJoin('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->where([['prod_gmt_emb_rcv_orders.id','=',$id]])
        ->get([
            'prod_gmt_emb_rcv_orders.*',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'sales_orders.job_id',
            'jobs.job_no',
            'jobs.company_id',
            'styles.buyer_id',
            'styles.style_ref',
            'companies.name as company_id',
            'countries.name as country_id',
            'buyers.name as buyer_name',
        ])
        ->first();

        
        $gmtembrcvqty = $this->prodgmtembrcvorder
        ->join('prod_gmt_emb_rcvs', function($join)  {
            $join->on('prod_gmt_emb_rcvs.id', '=', 'prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id');
        })
        ->join('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_emb_rcv_orders.sales_order_country_id');
        })
        ->join('countries', function($join) {
            $join->on('countries.id', '=', 'sales_order_countries.country_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
        })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('budgets',function($join){
            $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('budget_Embs',function($join){
            $join->on('budgets.id','=','budget_Embs.budget_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
        })
        ->join('style_gmt_color_sizes', function($join)  {
            $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','style_gmts.item_account_id');
        })
        ->join('style_colors',function($join){
            $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','style_colors.color_id');
        })
        ->join('style_sizes',function($join){
            $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
            $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('style_embelishments',function($join){
            $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            $join->on('style_embelishments.style_gmt_id','=','sales_order_gmt_color_sizes.style_gmt_id');
        })
        ->join('budget_emb_cons',function($join){
            $join->on('budget_embs.id','=','budget_emb_cons.budget_emb_id')
            ->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id')
            ->whereNull('budget_emb_cons.deleted_at');
        })
        ->leftJoin('prod_gmt_emb_rcv_qties',function($join){
           $join->on('prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id','=','prod_gmt_emb_rcv_orders.id');
           $join->on('prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        
        ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_emb_rcv_qties.qty) as qty FROM prod_gmt_emb_rcv_qties join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.id =prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id where prod_gmt_emb_rcv_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")

  	    ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')    
        ->where([['prod_gmt_emb_rcv_orders.id','=',$id]])
        ->where([['style_embelishments.embelishment_id','=',21]])
        ->get([
          'sizes.name as size_name',
          'sizes.code as size_code',
          'colors.name as color_name',
          'colors.code as color_code',
          'style_sizes.sort_id as size_sort_id',
          'style_colors.sort_id as color_sort_id',
          'sales_order_gmt_color_sizes.plan_cut_qty',
          'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id',
          'item_accounts.item_description',
          'prod_gmt_emb_rcv_qties.id as prod_gmt_emb_rcv_qty_id',
          'prod_gmt_emb_rcv_qties.qty',
          'prod_gmt_emb_rcv_qties.reject_qty',
          'budget_emb_cons.req_cons as req_qty',
          'cumulatives.qty as cumulative_qty'
        ])
        ->map(function ($gmtembrcvqty){
            $gmtembrcvqty->balance_qty=$gmtembrcvqty->req_qty-$gmtembrcvqty->cumulative_qty;
            $gmtembrcvqty->cumulative_qty_saved=$gmtembrcvqty->cumulative_qty-$gmtembrcvqty->qty;
            $gmtembrcvqty->balance_qty_saved=$gmtembrcvqty->req_qty-$gmtembrcvqty->cumulative_qty_saved;
            return $gmtembrcvqty;
        });
        $saved = $gmtembrcvqty->filter(function ($value) {
            if($value->prod_gmt_emb_rcv_qty_id){
                return $value;
            }
        });
        $new = $gmtembrcvqty->filter(function ($value) {
            if(!$value->prod_gmt_emb_rcv_qty_id){
                return $value;
            }
        });

        $row ['fromData'] = $prodgmtembrcvorder;
        $dropdown['embrcvgmtcosi'] = "'".Template::loadView('Production.Garments.ProdGmtEmbRcvQtyMatrix',['colorsizes'=>$new,'saved'=>$saved])."'";
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
    public function update(ProdGmtEmbRcvOrderRequest $request, $id) {
        $prodgmtembrcvorder=$this->prodgmtembrcvorder->update($id,
        [
            'prod_gmt_emb_rcv_id'=>$request->prod_gmt_emb_rcv_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'fabric_look_id'=>$request->fabric_look_id,
            'supplier_id'=>$request->supplier_id,
            'location_id'=>$request->location_id,
            'asset_quantity_cost_id'=>$request->asset_quantity_cost_id,
            'receive_hour'=>$request->receive_hour,        
            'prod_source_id'=>$request->prod_source_id, 
        ]);
        if($prodgmtembrcvorder){
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
        if($this->prodgmtembrcvorder->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getEmbRcvOrder(){
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $salesordercountry=$this->salesordercountry
        ->selectRaw('
            sales_order_countries.id as sales_order_country_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.produced_company_id,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            buyers.code as buyer_name,
            companies.name as company_id,
            produced_company.name as produced_company_name,
            countries.name as country_id,
            sum(sales_order_gmt_color_sizes.qty) as order_qty,
            avg(sales_order_gmt_color_sizes.rate) as order_rate,
            sum(sales_order_gmt_color_sizes.amount) as order_amount
         ')
        ->join('countries', function($join) {
            $join->on('countries.id', '=', 'sales_order_countries.country_id');
         })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
         })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
         })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
         })
        ->leftJoin('companies as produced_company', function($join)  {
            $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
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
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
         })   
        ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
         })
        ->when(request('job_no'), function ($q) {
            return $q->where('jobs.job_no', 'LIKE', "%".request('job_no', 0)."%");
         })
        ->when(request('sale_order_no'), function ($q) {
            return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
         })
        ->groupBy([
            'sales_order_countries.id',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'sales_orders.produced_company_id',
            'styles.style_ref',
            'styles.id',
            'jobs.job_no',
            'buyers.code',
            'companies.name',
            'produced_company.name',
            'countries.name',

        ])
        ->get()
        ->map(function ($salesordercountry) use($fabriclooks){
           return $salesordercountry;
         });
        echo json_encode($salesordercountry);
        
    }

}