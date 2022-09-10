<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputQtyRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtDlvInputOrderRequest;


class ProdGmtDlvInputOrderController extends Controller {

    private $prodgmtdlvinputorder;
    private $prodgmtdlvinput;
    private $location;
    private $gmtdlvinputqty;

    public function __construct(ProdGmtDlvInputOrderRepository $prodgmtdlvinputorder,ProdGmtDlvInputRepository $prodgmtdlvinput, LocationRepository $location,SalesOrderCountryRepository $salesordercountry, WstudyLineSetupRepository $wstudylinesetup,ProdGmtDlvInputQtyRepository $gmtdlvinputqty, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->prodgmtdlvinputorder = $prodgmtdlvinputorder;
        $this->prodgmtdlvinput = $prodgmtdlvinput;
        $this->gmtdlvinputqty = $gmtdlvinputqty;
        $this->salesordercountry = $salesordercountry;
        $this->location = $location;
        $this->wstudylinesetup = $wstudylinesetup;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtdlvinputorders',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtdlvinputorders', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtdlvinputorders',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtdlvinputorders', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');    
         
        $prodgmtdlvinputorders=array();
        $rows=$this->prodgmtdlvinputorder
        ->selectRaw('
        prod_gmt_dlv_input_orders.id,
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
        ->leftJoin('prod_gmt_dlv_inputs', function($join)  {
            $join->on('prod_gmt_dlv_inputs.id', '=', 'prod_gmt_dlv_input_orders.prod_gmt_dlv_input_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_input_orders.sales_order_country_id');
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
        ->where([['prod_gmt_dlv_input_id','=', request('prod_gmt_dlv_input_id',0)]])
        ->orderBy('prod_gmt_dlv_input_orders.id','desc')
        ->get([
            'prod_gmt_dlv_input_orders.*',
            'sales_orders.sale_order_no'
        ]);
        foreach($rows as $row){
            $prodgmtdlvinputorder['id']=$row->id;
            $prodgmtdlvinputorder['sales_order_country_id']=$row->sales_order_country_id;
            $prodgmtdlvinputorder['sale_order_no']=$row->sale_order_no;
            $prodgmtdlvinputorder['country_id']=$row->country_id;
            $prodgmtdlvinputorder['buyer_name']=$row->buyer_name;
            $prodgmtdlvinputorder['style_ref']=$row->style_ref;
            $prodgmtdlvinputorder['job_no']=$row->job_no;
            $prodgmtdlvinputorder['ship_date']=$row->ship_date;
            $prodgmtdlvinputorder['fabric_look_id']=$fabriclooks[$row->fabric_look_id];
            array_push($prodgmtdlvinputorders,$prodgmtdlvinputorder);
        }
        echo json_encode($prodgmtdlvinputorders);
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
    public function store(ProdGmtDlvInputOrderRequest $request) {
		$prodgmtdlvinputorder=$this->prodgmtdlvinputorder->create([
            'prod_gmt_dlv_input_id'=>$request->prod_gmt_dlv_input_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'fabric_look_id'=>$request->fabric_look_id
        ]);
        if($prodgmtdlvinputorder){
            return response()->json(array('success' => true,'id' =>  $prodgmtdlvinputorder->id,'message' => 'Save Successfully'),200);
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

        $prodgmtdlvinputorder = $this->prodgmtdlvinputorder
        ->leftJoin('prod_gmt_dlv_inputs', function($join)  {
            $join->on('prod_gmt_dlv_inputs.id', '=', 'prod_gmt_dlv_input_orders.prod_gmt_dlv_input_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_input_orders.sales_order_country_id');
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
        ->where([['prod_gmt_dlv_input_orders.id','=',$id]])
        ->get([
            'prod_gmt_dlv_input_orders.*',
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
        
        /*$gmtdlvinputqty = $this->prodgmtdlvinputorder
        ->join('prod_gmt_dlv_inputs', function($join)  {
            $join->on('prod_gmt_dlv_inputs.id', '=', 'prod_gmt_dlv_input_orders.prod_gmt_dlv_input_id');
        })
        ->join('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_input_orders.sales_order_country_id');
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
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->join('styles', function($join)  {
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
        ->leftJoin('prod_gmt_dlv_input_qties',function($join){
           $join->on('prod_gmt_dlv_input_qties.prod_gmt_dlv_input_order_id','=','prod_gmt_dlv_input_orders.id');
           $join->on('prod_gmt_dlv_input_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        
        ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_dlv_input_qties.qty) as qty FROM prod_gmt_dlv_input_qties join prod_gmt_dlv_input_orders on prod_gmt_dlv_input_orders.id =prod_gmt_dlv_input_qties.prod_gmt_dlv_input_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_dlv_input_qties.sales_order_gmt_color_size_id where prod_gmt_dlv_input_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")

        ->join('prod_gmt_cutting_qties',function($join){
            $join->on('prod_gmt_cutting_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
         })
        ->join('prod_gmt_cutting_orders',function($join){
            $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id','=','prod_gmt_cutting_orders.id');
         })
        ->join(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_cutting_qties.qty) as cut_qty FROM prod_gmt_cutting_qties join prod_gmt_cutting_orders on prod_gmt_cutting_orders.id =prod_gmt_cutting_qties.prod_gmt_cutting_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id where prod_gmt_cutting_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) prodgmtcutqty"), "prodgmtcutqty.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")


  	    ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')    
        ->where([['prod_gmt_dlv_input_orders.id','=',$id]])
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
          'prod_gmt_dlv_input_qties.id as prod_gmt_dlv_input_qty_id',
          'prod_gmt_dlv_input_qties.qty',
          'prodgmtcutqty.cut_qty'
        ])*/
        $gmtdlvinputqty = $this->prodgmtdlvinputorder
        ->join('prod_gmt_dlv_inputs', function($join)  {
            $join->on('prod_gmt_dlv_inputs.id', '=', 'prod_gmt_dlv_input_orders.prod_gmt_dlv_input_id');
        })

        ->join('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_input_orders.sales_order_country_id');
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
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('prod_gmt_cutting_orders',function($join){
            $join->on('prod_gmt_cutting_orders.sales_order_country_id','=','prod_gmt_dlv_input_orders.sales_order_country_id');
         })
        ->join('prod_gmt_cutting_qties',function($join){
            $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id','=','prod_gmt_cutting_orders.id');
         })
        
        ->join('sales_order_gmt_color_sizes', function($join)  {
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
        ->leftJoin('prod_gmt_dlv_input_qties',function($join){
           $join->on('prod_gmt_dlv_input_qties.prod_gmt_dlv_input_order_id','=','prod_gmt_dlv_input_orders.id');
           $join->on('prod_gmt_dlv_input_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        ->join(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_cutting_qties.qty) as cut_qty FROM prod_gmt_cutting_qties join prod_gmt_cutting_orders on prod_gmt_cutting_orders.id =prod_gmt_cutting_qties.prod_gmt_cutting_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id where prod_gmt_cutting_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) prodgmtcutqty"), "prodgmtcutqty.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")


        
        
        ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_dlv_input_qties.qty) as qty FROM prod_gmt_dlv_input_qties join prod_gmt_dlv_input_orders on prod_gmt_dlv_input_orders.id =prod_gmt_dlv_input_qties.prod_gmt_dlv_input_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_dlv_input_qties.sales_order_gmt_color_size_id where prod_gmt_dlv_input_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')    
        ->where([['prod_gmt_dlv_input_orders.id','=',$id]])
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
            prod_gmt_dlv_input_qties.id as prod_gmt_dlv_input_qty_id,
            prod_gmt_dlv_input_qties.qty,
            prodgmtcutqty.cut_qty,
            cumulatives.qty as cumulative_qty
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
          'prod_gmt_dlv_input_qties.id',
          'prod_gmt_dlv_input_qties.qty',
          'prodgmtcutqty.cut_qty',
          'cumulatives.qty'
        ])
        ->get()
        ->map(function ($gmtdlvinputqty){
            $gmtdlvinputqty->balance_qty=$gmtdlvinputqty->cut_qty-$gmtdlvinputqty->cumulative_qty;
            
            $gmtdlvinputqty->cumulative_qty_saved=$gmtdlvinputqty->cumulative_qty-$gmtdlvinputqty->qty;
            $gmtdlvinputqty->balance_qty_saved=$gmtdlvinputqty->cut_qty-$gmtdlvinputqty->cumulative_qty_saved;
            return $gmtdlvinputqty;
        });
        $saved = $gmtdlvinputqty->filter(function ($value) {
            if($value->prod_gmt_dlv_input_qty_id){
                return $value;
            }
        });
        $new = $gmtdlvinputqty->filter(function ($value) {
            if(!$value->prod_gmt_dlv_input_qty_id){
                return $value;
            }
        });

        $row ['fromData'] = $prodgmtdlvinputorder;
        $dropdown['dlvinputgmtcosi'] = "'".Template::loadView('Production.Garments.ProdGmtDlvInputQtyMatrix',['colorsizes'=>$new,'saved'=>$saved])."'";
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
    public function update(ProdGmtDlvInputOrderRequest $request, $id) {
        $prodgmtdlvinputorder=$this->prodgmtdlvinputorder->update($id,
        [
            'prod_gmt_dlv_input_id'=>$request->prod_gmt_dlv_input_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'fabric_look_id'=>$request->fabric_look_id,
        ]);
        if($prodgmtdlvinputorder){
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
        if($this->prodgmtdlvinputorder->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getDlvInputOrder(){
        $prodgmtdlvinput=$this->prodgmtdlvinput->find(request('prodgmtdlvinputid',0));
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
        ->where([['sales_orders.produced_company_id','=',$prodgmtdlvinput->produced_company_id]])   
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
        ->map(function ($salesordercountry){
           return $salesordercountry;
         });
        echo json_encode($salesordercountry);
        
    }

}