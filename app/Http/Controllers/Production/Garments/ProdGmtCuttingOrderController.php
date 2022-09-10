<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\SupplierRepository;

use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtCuttingOrderRequest;


class ProdGmtCuttingOrderController extends Controller {

    private $prodgmtcuttingorder;
    private $prodgmtcutting;
    private $gmtcuttingqty;
    private $location;
    private $supplier;

    public function __construct(ProdGmtCuttingOrderRepository $prodgmtcuttingorder,ProdGmtCuttingRepository $prodgmtcutting,SalesOrderCountryRepository $salesordercountry,ProdGmtCuttingQtyRepository $gmtcuttingqty, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize, LocationRepository $location,SupplierRepository $supplier) {
        $this->prodgmtcuttingorder = $prodgmtcuttingorder;
        $this->prodgmtcutting = $prodgmtcutting;
        $this->gmtcuttingqty = $gmtcuttingqty;
        $this->salesordercountry = $salesordercountry;
        $this->supplier = $supplier;
        $this->location = $location;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtcuttingorders',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtcuttingorders', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtcuttingorders',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtcuttingorders', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

	    $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
        $prodgmtcuttingorders=array();
        $rows=$this->prodgmtcuttingorder
        ->selectRaw('
            prod_gmt_cutting_orders.id,
            prod_gmt_cutting_orders.prod_source_id,
            prod_gmt_cutting_orders.location_id,
            prod_gmt_cutting_orders.table_no,
            sales_order_countries.id as sales_order_country_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            jobs.company_id,
            buyers.code as buyer_name,
            countries.name as country_id,
            locations.name as location_id,
            companies.name as company_id
        ')
        ->leftJoin('locations', function($join)  {
            $join->on('locations.id', '=', 'prod_gmt_cutting_orders.location_id');
        })
        ->leftJoin('prod_gmt_cuttings', function($join)  {
            $join->on('prod_gmt_cuttings.id', '=', 'prod_gmt_cutting_orders.prod_gmt_cutting_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_cutting_orders.sales_order_country_id');
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
        ->where([['prod_gmt_cutting_id','=', request('prod_gmt_cutting_id',0)]])
        ->orderBy('prod_gmt_cutting_orders.id','desc')
        ->get([
            'prod_gmt_cutting_orders.*',
            'sales_orders.sale_order_no',
            'locations.name as location_id'

        ]);
        foreach($rows as $row){
            $prodgmtcuttingorder['id']=$row->id;
            $prodgmtcuttingorder['sales_order_country_id']=$row->sales_order_country_id;
            $prodgmtcuttingorder['sale_order_no']=$row->sale_order_no;
            $prodgmtcuttingorder['prod_source_id']=isset($productionsource[$row->prod_source_id])?$productionsource[$row->prod_source_id]:'';
            $prodgmtcuttingorder['location_id']=$row->location_id;
            $prodgmtcuttingorder['table_no']=$row->table_no;
            array_push($prodgmtcuttingorders,$prodgmtcuttingorder);
        }
        echo json_encode($prodgmtcuttingorders);
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
    public function store(ProdGmtCuttingOrderRequest $request) {
		$prodgmtcuttingorder=$this->prodgmtcuttingorder->create([
            'prod_gmt_cutting_id'=>$request->prod_gmt_cutting_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'prod_source_id'=>$request->prod_source_id,
            'table_no'=>$request->table_no,
            'marker_length'=>$request->marker_length,
            'marker_width'=>$request->marker_width,
            'cutting_hour'=>$request->cutting_hour,
            'fabric_look_id'=>$request->fabric_look_id,
            'location_id'=>$request->location_id,
            'supplier_id'=>$request->supplier_id,
            'used_fabric'=>$request->used_fabric,
            'wastage_fabric'=>$request->wastage_fabric,
            'lay_cut_no'=>$request->lay_cut_no,
            'uom_id'=>$request->uom_id
        ]);
        if($prodgmtcuttingorder){
            return response()->json(array('success' => true,'id' =>  $prodgmtcuttingorder->id,'message' => 'Save Successfully'),200);
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

        $prodgmtcuttingorder = $this->prodgmtcuttingorder
        ->leftJoin('prod_gmt_cuttings', function($join)  {
            $join->on('prod_gmt_cuttings.id', '=', 'prod_gmt_cutting_orders.prod_gmt_cutting_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_cutting_orders.sales_order_country_id');
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
        
        ->where([['prod_gmt_cutting_orders.id','=',$id]])
        ->get([
            'prod_gmt_cutting_orders.*',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'sales_orders.job_id',
            'jobs.job_no',
            'jobs.company_id',
            'styles.buyer_id',
            'companies.name as company_id',
            'countries.name as country_id',
            'buyers.name as buyer_name',
        ])
        ->first();

        $gmtcuttingqty = $this->prodgmtcuttingorder
        ->leftJoin('prod_gmt_cuttings', function($join)  {
            $join->on('prod_gmt_cuttings.id', '=', 'prod_gmt_cutting_orders.prod_gmt_cutting_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_cutting_orders.sales_order_country_id');
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
        ->leftJoin('prod_gmt_cutting_qties',function($join){
           $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id','=','prod_gmt_cutting_orders.id');
           $join->on('prod_gmt_cutting_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })

        ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_cutting_qties.qty) as qty FROM prod_gmt_cutting_qties join prod_gmt_cutting_orders on prod_gmt_cutting_orders.id =prod_gmt_cutting_qties.prod_gmt_cutting_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id where prod_gmt_cutting_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")

        ->orderBy('style_gmt_color_sizes.style_gmt_id')
  	    ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')    
        ->where([['prod_gmt_cutting_orders.id','=',$id]])
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
          'prod_gmt_cutting_qties.id as prod_gmt_cutting_qty_id',
          'prod_gmt_cutting_qties.qty',
          'prod_gmt_cutting_qties.alter_qty',
          'prod_gmt_cutting_qties.spot_qty',
          'prod_gmt_cutting_qties.reject_qty',
          'prod_gmt_cutting_qties.replace_qty',
          'cumulatives.qty as cumulative_qty'
        ])
        ->map(function ($gmtcuttingqty){
            $gmtcuttingqty->balance_qty=$gmtcuttingqty->plan_cut_qty-$gmtcuttingqty->cumulative_qty;
            $gmtcuttingqty->cumulative_qty_saved=$gmtcuttingqty->cumulative_qty-$gmtcuttingqty->qty;
            $gmtcuttingqty->balance_qty_saved=$gmtcuttingqty->plan_cut_qty-$gmtcuttingqty->cumulative_qty_saved;
            return $gmtcuttingqty;
        });
        $saved = $gmtcuttingqty->filter(function ($value) {
            if($value->prod_gmt_cutting_qty_id){
                return $value;
            }
        });
        $new = $gmtcuttingqty->filter(function ($value) {
            if(!$value->prod_gmt_cutting_qty_id){
                return $value;
            }
        });
        $row ['fromData'] = $prodgmtcuttingorder;
        $dropdown['cuttinggmtcosi'] = "'".Template::loadView('Production.Garments.ProdGmtCuttingQtyMatrix',['colorsizes'=>$new,'saved'=>$saved])."'";
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
    public function update(ProdGmtCuttingOrderRequest $request, $id) {
        $prodgmtcuttingQty= $this->gmtcuttingqty
        ->where([['prod_gmt_cutting_order_id','=',$id]])
        ->first();
        if($prodgmtcuttingQty){
            $prodgmtcuttingorder=$this->prodgmtcuttingorder->update($id,
            [
                'prod_source_id'=>$request->prod_source_id,
                'table_no'=>$request->table_no,
                'marker_length'=>$request->marker_length,
                'marker_width'=>$request->marker_width,
                'cutting_hour'=>$request->cutting_hour,
                'fabric_look_id'=>$request->fabric_look_id,
                'location_id'=>$request->location_id,
                'supplier_id'=>$request->supplier_id,
                'used_fabric'=>$request->used_fabric,
                'wastage_fabric'=>$request->wastage_fabric,
                'lay_cut_no'=>$request->lay_cut_no,
                'uom_id'=>$request->uom_id
            ]);
        }
        else {
            $prodgmtcuttingorder=$this->prodgmtcuttingorder->update($id,
            [
                'prod_gmt_cutting_id'=>$request->prod_gmt_cutting_id,
                'sales_order_country_id'=>$request->sales_order_country_id,
                'prod_source_id'=>$request->prod_source_id,
                'table_no'=>$request->table_no,
                'marker_length'=>$request->marker_length,
                'marker_width'=>$request->marker_width,
                'cutting_hour'=>$request->cutting_hour,
                'fabric_look_id'=>$request->fabric_look_id,
                'location_id'=>$request->location_id,
                'supplier_id'=>$request->supplier_id,
                'used_fabric'=>$request->used_fabric,
                'wastage_fabric'=>$request->wastage_fabric,
                'lay_cut_no'=>$request->lay_cut_no,
                'uom_id'=>$request->uom_id
            ]);
        }
        if($prodgmtcuttingorder){
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
        if($this->prodgmtcuttingorder->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getcuttingOrder(){
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
         'countries.name'
        ])
        ->get()
        ->map(function ($salesordercountry){
          return $salesordercountry;
         });
        echo json_encode($salesordercountry);
        
    }

}