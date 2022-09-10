<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtDlvToEmbOrderRequest;


class ProdGmtDlvToEmbOrderController extends Controller {

    private $prodgmtdlvtoemborder;
    private $prodgmtdlvtoemb;
    private $location;
    private $gmtdlvtoembqty;

    public function __construct(ProdGmtDlvToEmbOrderRepository $prodgmtdlvtoemborder,ProdGmtDlvToEmbRepository $prodgmtdlvtoemb, LocationRepository $location,SalesOrderCountryRepository $salesordercountry, ProdGmtDlvToEmbQtyRepository $gmtdlvtoembqty, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->prodgmtdlvtoemborder = $prodgmtdlvtoemborder;
        $this->prodgmtdlvtoemb = $prodgmtdlvtoemb;
        $this->gmtdlvtoembqty = $gmtdlvtoembqty;
        $this->salesordercountry = $salesordercountry;
        $this->location = $location;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtdlvtoemborders',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtdlvtoemborders', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtdlvtoemborders',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtdlvtoemborders', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');    
         
        $prodgmtdlvtoemborders=array();
        $rows=$this->prodgmtdlvtoemborder
        ->selectRaw('
        prod_gmt_dlv_to_emb_orders.id,
        sales_order_countries.id as sales_order_country_id,
        sales_orders.sale_order_no,
        sales_orders.ship_date,
        sales_orders.produced_company_id,
        styles.style_ref,
        styles.id as style_id,
        jobs.job_no,
        jobs.company_id,
        buyers.code as buyer_name,
        countries.name as country_id,
        companies.name as company_id
        ')
        ->leftJoin('prod_gmt_dlv_to_embs', function($join)  {
            $join->on('prod_gmt_dlv_to_embs.id', '=', 'prod_gmt_dlv_to_emb_orders.prod_gmt_dlv_to_emb_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_to_emb_orders.sales_order_country_id');
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
        ->where([['prod_gmt_dlv_to_emb_id','=', request('prod_gmt_dlv_to_emb_id',0)]])
        ->orderBy('prod_gmt_dlv_to_emb_orders.id','desc')
        ->get([
            'prod_gmt_dlv_to_emb_orders.*',
            'sales_orders.sale_order_no'
        ]);
        foreach($rows as $row){
            $prodgmtdlvtoemborder['id']=$row->id;
            $prodgmtdlvtoemborder['sales_order_country_id']=$row->sales_order_country_id;
            $prodgmtdlvtoemborder['sale_order_no']=$row->sale_order_no;
            $prodgmtdlvtoemborder['country_id']=$row->country_id;
            $prodgmtdlvtoemborder['buyer_name']=$row->buyer_name;
            $prodgmtdlvtoemborder['style_ref']=$row->style_ref;
            $prodgmtdlvtoemborder['job_no']=$row->job_no;
            $prodgmtdlvtoemborder['ship_date']=$row->ship_date;
            $prodgmtdlvtoemborder['fabric_look_id']=$fabriclooks[$row->fabric_look_id];
            array_push($prodgmtdlvtoemborders,$prodgmtdlvtoemborder);
        }
        echo json_encode($prodgmtdlvtoemborders);
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
    public function store(ProdGmtDlvToEmbOrderRequest $request) {
		$prodgmtdlvtoemborder=$this->prodgmtdlvtoemborder->create([
            'prod_gmt_dlv_to_emb_id'=>$request->prod_gmt_dlv_to_emb_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'fabric_look_id'=>$request->fabric_look_id
        ]);
        if($prodgmtdlvtoemborder){
            return response()->json(array('success' => true,'id' =>  $prodgmtdlvtoemborder->id,'message' => 'Save Successfully'),200);
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

        $prodgmtdlvtoemborder = $this->prodgmtdlvtoemborder
        ->leftJoin('prod_gmt_dlv_to_embs', function($join)  {
            $join->on('prod_gmt_dlv_to_embs.id', '=', 'prod_gmt_dlv_to_emb_orders.prod_gmt_dlv_to_emb_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_to_emb_orders.sales_order_country_id');
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
        ->leftJoin('companies as produced_company', function($join)  {
            $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
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
        ->where([['prod_gmt_dlv_to_emb_orders.id','=',$id]])
        ->get([
            'prod_gmt_dlv_to_emb_orders.*',
            'sales_orders.sale_order_no',
            'sales_orders.produced_company_id',
            'sales_orders.ship_date',
            'sales_orders.job_id',
            'jobs.job_no',
            'jobs.company_id',
            'styles.buyer_id',
            'styles.style_ref',
            'companies.name as company_id',
            'countries.name as country_id',
            'buyers.name as buyer_name',
            'produced_company.name as produced_company_name'

        ])
        ->first();

        
        $gmtdlvtoembqty = $this->prodgmtdlvtoemborder
        ->join('prod_gmt_dlv_to_embs', function($join)  {
            $join->on('prod_gmt_dlv_to_embs.id', '=', 'prod_gmt_dlv_to_emb_orders.prod_gmt_dlv_to_emb_id');
        })
        ->join('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_to_emb_orders.sales_order_country_id');
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
        ->join('budget_embs',function($join){
            $join->on('budgets.id','=','budget_embs.budget_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('prod_gmt_cutting_orders',function($join){
            $join->on('prod_gmt_cutting_orders.sales_order_country_id','=','prod_gmt_dlv_to_emb_orders.sales_order_country_id');
         })
         ->leftJoin('prod_gmt_cutting_qties',function($join){
            $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id','=','prod_gmt_cutting_orders.id');
         })
        ->leftJoin('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.id', '=', 'prod_gmt_cutting_qties.sales_order_gmt_color_size_id');
        })
        ->leftJoin('style_gmt_color_sizes', function($join)  {
            $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->leftJoin('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts',function($join){
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
        ->join('style_embelishments',function($join){
            $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            $join->on('style_embelishments.style_gmt_id','=','sales_order_gmt_color_sizes.style_gmt_id');
            })
        ->join('budget_emb_cons',function($join){
            $join->on('budget_embs.id','=','budget_emb_cons.budget_emb_id')
            ->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id')
            ->whereNull('budget_emb_cons.deleted_at');
        })
        ->leftJoin('prod_gmt_dlv_to_emb_qties',function($join){
           $join->on('prod_gmt_dlv_to_emb_qties.prod_gmt_dlv_to_emb_order_id','=','prod_gmt_dlv_to_emb_orders.id');
           $join->on('prod_gmt_dlv_to_emb_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_cutting_qties.qty) as cut_qty FROM prod_gmt_cutting_qties join prod_gmt_cutting_orders on prod_gmt_cutting_orders.id =prod_gmt_cutting_qties.prod_gmt_cutting_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id where prod_gmt_cutting_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) prodgmtcutqty"), "prodgmtcutqty.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")

        ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_dlv_to_emb_qties.qty) as qty FROM prod_gmt_dlv_to_emb_qties join prod_gmt_dlv_to_emb_orders on prod_gmt_dlv_to_emb_orders.id =prod_gmt_dlv_to_emb_qties.prod_gmt_dlv_to_emb_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_dlv_to_emb_qties.sales_order_gmt_color_size_id where prod_gmt_dlv_to_emb_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")

  	    ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')    
        ->where([['prod_gmt_dlv_to_emb_orders.id','=',$id]])
        ->selectRaw('
            sizes.name as size_name,
            sizes.code as size_code,
            colors.name as color_name,
            colors.code as color_code,
            style_sizes.sort_id as size_sort_id,
            style_colors.sort_id as color_sort_id,
            sales_order_gmt_color_sizes.plan_cut_qty,
            sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
            item_accounts.item_description,
            prod_gmt_dlv_to_emb_qties.id as prod_gmt_dlv_to_emb_qty_id,
            prod_gmt_dlv_to_emb_qties.qty,
            prodgmtcutqty.cut_qty,
            cumulatives.qty as cumulative_qty,
            budget_emb_cons.req_cons as req_qty
            ')
            ->groupBy([
                'sizes.name',
                'sizes.code',
                'colors.name',
                'colors.code',
                'style_sizes.sort_id',
                'style_colors.sort_id',
                'sales_order_gmt_color_sizes.plan_cut_qty',
                'sales_order_gmt_color_sizes.id',
                'item_accounts.item_description',
                'prod_gmt_dlv_to_emb_qties.id',
                'prod_gmt_dlv_to_emb_qties.qty',
                'prodgmtcutqty.cut_qty',
                'cumulatives.qty',
                'budget_emb_cons.req_cons'
              ])
        ->get()
        ->map(function ($gmtdlvtoembqty){
            $gmtdlvtoembqty->balance_qty=$gmtdlvtoembqty->plan_cut_qty-$gmtdlvtoembqty->cumulative_qty;
            $gmtdlvtoembqty->cumulative_qty_saved=$gmtdlvtoembqty->cumulative_qty-$gmtdlvtoembqty->qty;
            $gmtdlvtoembqty->balance_qty_saved=$gmtdlvtoembqty->plan_cut_qty-$gmtdlvtoembqty->cumulative_qty_saved;
            return $gmtdlvtoembqty;
        });
        $saved = $gmtdlvtoembqty->filter(function ($value) {
            if($value->prod_gmt_dlv_to_emb_qty_id){
                return $value;
            }
        });
        $new = $gmtdlvtoembqty->filter(function ($value) {
            if(!$value->prod_gmt_dlv_to_emb_qty_id){
                return $value;
            }
        });

        $row ['fromData'] = $prodgmtdlvtoemborder;
        $dropdown['dlvtoembgmtcosi'] = "'".Template::loadView('Production.Garments.ProdGmtDlvToEmbQtyMatrix',['colorsizes'=>$new,'saved'=>$saved])."'";
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
    public function update(ProdGmtDlvToEmbOrderRequest $request, $id) {
        $prodgmtdlvtoemborder=$this->prodgmtdlvtoemborder->update($id,
        [
            'prod_gmt_dlv_to_emb_id'=>$request->prod_gmt_dlv_to_emb_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'fabric_look_id'=>$request->fabric_look_id,
        ]);
        if($prodgmtdlvtoemborder){
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
        if($this->prodgmtdlvtoemborder->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getDlvToEmbOrder(){
        $prodgmtdlvtoemb=$this->prodgmtdlvtoemb->find(request('prodgmtdlvtoembid',0));
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
        ->join('budgets',function($join){
            $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('budget_embs',function($join){
            $join->on('budgets.id','=','budget_embs.budget_id');
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
        ->join('style_embelishments',function($join){
            $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            $join->on('style_embelishments.style_gmt_id','=','sales_order_gmt_color_sizes.style_gmt_id');
        })
        ->join('budget_emb_cons',function($join){
            $join->on('budget_embs.id','=','budget_emb_cons.budget_emb_id')
            ->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id')
            ->whereNull('budget_emb_cons.deleted_at');
        })
         ->where([['sales_orders.produced_company_id','=',$prodgmtdlvtoemb->produced_company_id]])
         ->where([['style_embelishments.embelishment_id','=',21]])
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