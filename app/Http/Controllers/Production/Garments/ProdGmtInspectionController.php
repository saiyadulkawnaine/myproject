<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtInspectionOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtInspectionRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtInspectionRequest;

class ProdGmtInspectionController extends Controller {

    private $prodgmtinspection;
    private $inspectionorder;
    private $salesordercountry;
    private $gmtcolorsize;

    public function __construct(
        ProdGmtInspectionRepository $prodgmtinspection,
        ProdGmtInspectionOrderRepository $inspectionorder, 
        SalesOrderCountryRepository $salesordercountry,
        SalesOrderGmtColorSizeRepository $gmtcolorsize
    ) {
        $this->prodgmtinspection = $prodgmtinspection;
        $this->inspectionorder = $inspectionorder;
        $this->salesordercountry = $salesordercountry;
        $this->gmtcolorsize = $gmtcolorsize;
        $this->middleware('auth');
        /*$this->middleware('permission:view.prodgmtinspections',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodgmtinspections', ['only' => ['store']]);
        $this->middleware('permission:edit.prodgmtinspections',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodgmtinspections', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $prodgmtinspections=array();
        $rows=$this->prodgmtinspection
        ->leftJoin('sales_order_countries',function($join){ 
            $join->on('sales_order_countries.id','=','prod_gmt_inspections.sales_order_country_id');
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
       
        ->leftJoin('buyers', function($join)  {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->orderBy('prod_gmt_inspections.id','desc')
        ->get([
            'prod_gmt_inspections.*',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'styles.style_ref',
            'jobs.job_no',
            'buyers.code as buyer_name',
            'companies.name as company_id',
            'produced_company.name as produced_company_name',
            'countries.name as country_id'
        ]);
        /* foreach($rows as $row){
            $prodgmtinspection['id']=$row->id;
            $prodgmtinspection['sale_order_no']=$row->sale_order_no;
            $prodgmtinspection['country_id']=$row->country_id;
            $prodgmtinspection['inspection_date']=date('d-m-Y',strtotime($row->inspection_date));
            array_push($prodgmtinspections,$prodgmtinspection);
        } */
        //echo json_encode($prodgmtinspections);
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return Template::loadView('Production.Garments.ProdGmtInspection');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtInspectionRequest $request) {
		$prodgmtinspection = $this->prodgmtinspection->create([
            'sales_order_country_id'=>$request->sales_order_country_id,
            'inspection_date'=>$request->inspection_date
        ]);
        if($prodgmtinspection){
            return response()->json(array('success' => true,'id' =>  $prodgmtinspection->id ,'message' => 'Save Successfully'),200);
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
        $prodgmtinspection = $this->prodgmtinspection
        ->leftJoin('sales_order_countries',function($join){
            $join->on('sales_order_countries.id','=','prod_gmt_inspections.sales_order_country_id');
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
        ->leftJoin('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->where([['prod_gmt_inspections.id','=',$id]])
        ->get([
            'prod_gmt_inspections.*',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'styles.style_ref',
            'jobs.job_no',
            'buyers.code as buyer_name',
            'companies.name as company_id',
            'produced_company.name as produced_company_name',
            'countries.name as country_id'
        ])
        ->first();

        $inspectionorder = $this->prodgmtinspection
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_inspections.sales_order_country_id');
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
        ->leftJoin('prod_gmt_inspection_orders',function($join){
           $join->on('prod_gmt_inspection_orders.prod_gmt_inspection_id','=','prod_gmt_inspections.id');
           $join->on('prod_gmt_inspection_orders.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_inspection_orders.qty) as qty FROM prod_gmt_inspections 
        join prod_gmt_inspection_orders
         on prod_gmt_inspections.id =prod_gmt_inspection_orders.prod_gmt_inspection_id 
        join sales_order_gmt_color_sizes
         on sales_order_gmt_color_sizes.id=prod_gmt_inspection_orders.sales_order_gmt_color_size_id where prod_gmt_inspection_orders.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")
  	    ->orderBy('style_colors.sort_id')
        ->where([['prod_gmt_inspections.id','=',$id]])
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
        ->get([
          'colors.name as color_name',
          'colors.code as color_code',
          'sizes.name as size_name',
          'style_colors.sort_id',
          'style_sizes.sort_id',
          'sales_order_gmt_color_sizes.plan_cut_qty',
          'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id',
          'item_accounts.item_description',
          'prod_gmt_inspection_orders.id as prod_gmt_inspection_order_id',
          'prod_gmt_inspection_orders.qty',
          'prod_gmt_inspection_orders.re_check_qty',
          'prod_gmt_inspection_orders.failed_qty',
          'prod_gmt_inspection_orders.re_check_remarks',
          'prod_gmt_inspection_orders.failed_remarks',
          'prod_gmt_inspection_orders.expected_exfactory_date',
          'prod_gmt_inspection_orders.exfactory_qty',
          'cumulatives.qty as cumulative_qty'
        ])
        
        ->map(function ($inspectionorder){
            $inspectionorder->balance_qty=$inspectionorder->plan_cut_qty-$inspectionorder->cumulative_qty;
            $inspectionorder->cumulative_qty_saved=$inspectionorder->cumulative_qty-$inspectionorder->qty;
            $inspectionorder->balance_qty_saved=$inspectionorder->plan_cut_qty-$inspectionorder->cumulative_qty_saved;
            return $inspectionorder;
        });
        $saved = $inspectionorder->filter(function ($value) {
            if($value->prod_gmt_inspection_order_id){
                return $value;
            }
        });
        $new = $inspectionorder->filter(function ($value) {
            if(!$value->prod_gmt_inspection_order_id){
                return $value;
            }
        });


        $row ['fromData'] = $prodgmtinspection;
        $dropdown['inspectionordergmtcosi'] = "'".Template::loadView('Production.Garments.ProdGmtInspectionOrderMatrix',['colorsizes'=>$new,'saved'=>$saved])."'";
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
    public function update(ProdGmtInspectionRequest $request, $id) {
        $prodgmtinspection=$this->prodgmtinspection->update($id,[
            'sales_order_country_id'=>$request->sales_order_country_id,
            'inspection_date'=>$request->inspection_date
        ]);
        if($prodgmtinspection){
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
        if($this->prodgmtinspection->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
   
    public function getSalesOrderCountry(){
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