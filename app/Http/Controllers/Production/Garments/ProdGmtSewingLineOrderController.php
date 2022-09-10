<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtSewingLineOrderRequest;

class ProdGmtSewingLineOrderController extends Controller {

    private $prodgmtsewinglineorder;
    private $prodgmtsewingline;
    private $location;
    private $company;
    private $gmtsewinglineqty;

    public function __construct(ProdGmtSewingLineOrderRepository $prodgmtsewinglineorder,ProdGmtSewingLineRepository $prodgmtsewingline, LocationRepository $location,SalesOrderCountryRepository $salesordercountry, ProdGmtSewingLineQtyRepository $gmtsewinglineqty, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize, WstudyLineSetupRepository $wstudylinesetup,CompanyRepository $company) {
        $this->prodgmtsewinglineorder = $prodgmtsewinglineorder;
        $this->prodgmtsewingline = $prodgmtsewingline;
        $this->gmtsewinglineqty = $gmtsewinglineqty;
        $this->salesordercountry = $salesordercountry;
        $this->location = $location;
        $this->company = $company;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->wstudylinesetup = $wstudylinesetup;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtsewinglineorders',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtsewinglineorders', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtsewinglineorders',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtsewinglineorders', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');    
        $subsections=$this->wstudylinesetup
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
        })
        ->join('wstudy_line_setup_lines', function($join)  {
            $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('subsections', function($join)  {
            $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
        })
        ->join('floors', function($join)  {
            $join->on('floors.id', '=', 'subsections.floor_id');
        })
        ->when(request('location_id'), function ($q) {
            return $q->where('wstudy_line_setups.location_id', '=',request('location_id', 0));
        })
        ->get([
            'wstudy_line_setups.id',
            'subsections.name',
            'subsections.code',
            'floors.name as floor_name'
        ]);
        $lineNames=Array();
        $lineCode=Array();
        $lineFloor=Array();
        foreach ($subsections as $subsection) {
           $lineNames[$subsection->id][]=$subsection->name;
           $lineCode[$subsection->id][]=$subsection->code;
           $lineFloor[$subsection->id][]=$subsection->floor_name;
        }
        $prodgmtsewinglineorders=array();
        $rows=$this->prodgmtsewinglineorder
        ->selectRaw('
        prod_gmt_sewing_line_orders.id,
        prod_gmt_sewing_line_orders.wstudy_line_setup_id,
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
        /*  
            wstudy_line_setups.id as wstudy_line_setup_id,
            wstudy_line_setups.location_id,
            locations.name as location_id,
        */
        ->leftJoin('prod_gmt_sewing_lines', function($join)  {
            $join->on('prod_gmt_sewing_lines.id', '=', 'prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_sewing_line_orders.sales_order_country_id');
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
        ->leftJoin('wstudy_line_setups', function($join)  {
            $join->on('prod_gmt_sewing_line_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        /* ->join('locations', function($join)  {
            $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
        }) */
        ->where([['prod_gmt_sewing_line_id','=', request('prod_gmt_sewing_line_id',0)]])
        ->orderBy('prod_gmt_sewing_line_orders.id','desc')
        ->get([
            'prod_gmt_sewing_line_orders.*',
            'sales_orders.sale_order_no',
           // 'wstudy_line_setups.location_id',
           // 'locations.name as location_id'
        ]);
        foreach($rows as $row){
            $prodgmtsewinglineorder['id']=$row->id;
            $prodgmtsewinglineorder['sales_order_country_id']=$row->sales_order_country_id;
            $prodgmtsewinglineorder['sale_order_no']=$row->sale_order_no;
            $prodgmtsewinglineorder['country_id']=$row->country_id;
            $prodgmtsewinglineorder['fabric_look_id']=$fabriclooks[$row->fabric_look_id];
            $prodgmtsewinglineorder['receive_hour']=$row->receive_hour;
            $prodgmtsewinglineorder['ship_date']=$row->ship_date;
            $prodgmtsewinglineorder['line_name']=isset($lineNames[$row->wstudy_line_setup_id])?implode(',',$lineNames[$row->wstudy_line_setup_id]):'';
            array_push($prodgmtsewinglineorders,$prodgmtsewinglineorder);
        }
        echo json_encode($prodgmtsewinglineorders);
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
    public function store(ProdGmtSewingLineOrderRequest $request) {
		$prodgmtsewinglineorder=$this->prodgmtsewinglineorder->create([
            'prod_gmt_sewing_line_id'=>$request->prod_gmt_sewing_line_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'fabric_look_id'=>$request->fabric_look_id,
            'wstudy_line_setup_id'=>$request->wstudy_line_setup_id,
            'prod_hour'=>$request->prod_hour
        ]);
        if($prodgmtsewinglineorder){
            return response()->json(array('success' => true,'id' =>  $prodgmtsewinglineorder->id,'message' => 'Save Successfully'),200);
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
        $subsections=$this->prodgmtsewinglineorder
        ->leftJoin('wstudy_line_setups', function($join)  {
            $join->on('prod_gmt_sewing_line_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('wstudy_line_setup_lines', function($join)  {
            $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('subsections', function($join)  {
            $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
        })
        ->when(request('line_merged_id'), function ($q) {
            return $q->where('wstudy_line_setups.line_merged_id', 'LIKE', "%".request('line_merged_id', 0)."%");
        })
        ->where([['prod_gmt_sewing_line_orders.id','=',$id]])
        ->get([
            'wstudy_line_setups.id',
            'subsections.code'
        ]);
        $lineNames=Array();
        foreach($subsections as $subsection){
            $lineNames[$subsection->id][]=$subsection->code;
        }
        $prodgmtsewinglineorder = $this->prodgmtsewinglineorder
        ->leftJoin('prod_gmt_sewing_lines', function($join)  {
            $join->on('prod_gmt_sewing_lines.id', '=', 'prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_sewing_line_orders.sales_order_country_id');
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
        ->leftJoin('wstudy_line_setups', function($join)  {
            $join->on('prod_gmt_sewing_line_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        /* ->join('locations', function($join)  {
            $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
        }) */
        ->where([['prod_gmt_sewing_line_orders.id','=',$id]])
        ->get([
            'prod_gmt_sewing_line_orders.*',
            'sales_orders.sale_order_no',
            'wstudy_line_setups.id as wstudy_line_setup_id',
            //'sales_orders.produced_company_id',
            'sales_orders.ship_date',
            'sales_orders.job_id',
            'jobs.job_no',
            'jobs.company_id',
            'styles.buyer_id',
            'styles.style_ref',
            'companies.id as company_id',
            'companies.name as company_name',
            'countries.name as country_id',
            'buyers.name as buyer_name',
            //'produced_company.name as produced_company_name'

        ])
        ->map(function($prodgmtsewinglineorder) use($lineNames){
            //if($prodgmtsewinglineorder->line_name !== null){
                $prodgmtsewinglineorder->line_name=isset($lineNames[$prodgmtsewinglineorder->wstudy_line_setup_id])?implode(',',$lineNames[$prodgmtsewinglineorder->wstudy_line_setup_id]):'';
            //}
           // $prodgmtsewinglineorder->line_name=implode(',',$lineNames[$prodgmtsewinglineorder->wstudy_line_setup_id]);
            return $prodgmtsewinglineorder;
        })
        ->first();
      
        $gmtsewinglineqty = $this->prodgmtsewinglineorder
        ->leftJoin('prod_gmt_sewing_lines', function($join)  {
            $join->on('prod_gmt_sewing_lines.id', '=', 'prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_sewing_line_orders.sales_order_country_id');
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
        /*->join('prod_gmt_cutting_orders',function($join){
            $join->on('prod_gmt_cutting_orders.sales_order_country_id','=','prod_gmt_sewing_line_orders.sales_order_country_id');
        })
        ->join('prod_gmt_cutting_qties',function($join){
            $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id','=','prod_gmt_cutting_orders.id');
         })*/
        /* ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.id', '=', 'prod_gmt_cutting_qties.sales_order_gmt_color_size_id');
        })*/
        ->join('sales_order_gmt_color_sizes', function($join)  {
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
        ->leftJoin('prod_gmt_sewing_line_qties',function($join){
           $join->on('prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id','=','prod_gmt_sewing_line_orders.id');
           $join->on('prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })      
        ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_sewing_line_qties.qty) as qty FROM prod_gmt_sewing_line_qties join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.id =prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id where prod_gmt_sewing_line_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")

  	    ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')    
        ->where([['prod_gmt_sewing_line_orders.id','=',$id]])
        //->toSql();
        //dd($gmtsewinglineqty);
        //die;
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
          'prod_gmt_sewing_line_qties.id as prod_gmt_sewing_line_qty_id',
          'prod_gmt_sewing_line_qties.qty',
          'cumulatives.qty as cumulative_qty',
        ])
        ->map(function ($gmtsewinglineqty){
            $gmtsewinglineqty->balance_qty=$gmtsewinglineqty->plan_cut_qty-$gmtsewinglineqty->cumulative_qty;
            $gmtsewinglineqty->cumulative_qty_saved=$gmtsewinglineqty->cumulative_qty-$gmtsewinglineqty->qty;
            $gmtsewinglineqty->balance_qty_saved=$gmtsewinglineqty->plan_cut_qty-$gmtsewinglineqty->cumulative_qty_saved;
            return $gmtsewinglineqty;
        });
        $saved = $gmtsewinglineqty->filter(function ($value) {
            if($value->prod_gmt_sewing_line_qty_id){
                return $value;
            }
        });
        $new = $gmtsewinglineqty->filter(function ($value) {
            if(!$value->prod_gmt_sewing_line_qty_id){
                return $value;
            }
        });

        $row ['fromData'] = $prodgmtsewinglineorder;
        $dropdown['sewinglinegmtcosi'] = "'".Template::loadView('Production.Garments.ProdGmtSewingLineQtyMatrix',['colorsizes'=>$new,'saved'=>$saved])."'";
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
    public function update(ProdGmtSewingLineOrderRequest $request, $id) {
        $prodgmtsewinglineorder=$this->prodgmtsewinglineorder->update($id,
        [
            'prod_gmt_sewing_line_id'=>$request->prod_gmt_sewing_line_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'fabric_look_id'=>$request->fabric_look_id,
            'wstudy_line_setup_id'=>$request->wstudy_line_setup_id,
            'prod_hour'=>$request->prod_hour
        ]);
        if($prodgmtsewinglineorder){
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
        if($this->prodgmtsewinglineorder->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getSewingLineOrder(){
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
            companies.id as company_id,
            companies.name as company_name,
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
            'companies.id',
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
    public function getLine(){
        $prodgmtsewingline=$this->prodgmtsewingline->find(request('prod_gmt_sewing_line_id',0));
        $company=$this->company
        ->join('suppliers', function($join)  {
            $join->on('suppliers.company_id', '=', 'companies.id');
        })
        ->where([['suppliers.id','=',$prodgmtsewingline->supplier_id]])
        ->get()
        ->first();

        $input_date=$prodgmtsewingline->input_date;
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');

        $subsections=$this->wstudylinesetup
         ->join('companies', function($join)  {
             $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
         })
         ->join('suppliers', function($join)  {
             $join->on('suppliers.company_id', '=', 'companies.id');
         })
         ->join('wstudy_line_setup_lines', function($join)  {
             $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
         })
         ->join('subsections', function($join)  {
             $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
         })
         ->join('floors', function($join)  {
             $join->on('floors.id', '=', 'subsections.floor_id');
         })
         ->when(request('location_id'), function ($q) {
             return $q->where('wstudy_line_setups.location_id', '=',request('location_id', 0));
         })
         ->where([['wstudy_line_setups.company_id','=',$company->company_id]])
         //->where([['wstudy_line_setups.company_id','=',request('produced_company_id',0)]])
         ->get([
             'wstudy_line_setups.id',
             'subsections.name',
             'subsections.code',
             'floors.name as floor_name'
         ]);
         $lineNames=Array();
         $lineCode=Array();
         $lineFloor=Array();
         foreach($subsections as $subsection)
         {
            $lineNames[$subsection->id][]=$subsection->name;
            $lineCode[$subsection->id][]=$subsection->code;
            $lineFloor[$subsection->id][]=$subsection->floor_name;
         }
 
 
         $wstudylinesetup=$this->wstudylinesetup
         // ->join('wstudy_line_setup_lines', function($join)  {
         //     $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
         // })
         ->join('wstudy_line_setup_dtls', function($join)  {
             $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
         })
         ->join('companies', function($join)  {
             $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
         })
         ->join('suppliers', function($join)  {
            $join->on('suppliers.company_id', '=', 'wstudy_line_setups.company_id');
         })
         ->join('locations', function($join)  {
             $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
         })
         ->when(request('line_merged_id'), function ($q) {
             return $q->where('wstudy_line_setups.line_merged_id', 'LIKE', "%".request('line_merged_id', 0)."%");
         })
         ->when($input_date, function ($q) use($input_date){
         return $q->where('wstudy_line_setup_dtls.from_date', '>=',$input_date);
         })
         ->when($input_date, function ($q) use($input_date){
         return $q->where('wstudy_line_setup_dtls.to_date', '<=',$input_date);
         })
         ->where([['wstudy_line_setups.company_id','=',$company->company_id]])
        // ->where([['wstudy_line_setups.company_id','=',request('produced_company_id',0)]])
         ->get([
             'wstudy_line_setups.*',
             'companies.name as company_name',
             'locations.name as location_name'
         ])
         ->map(function($wstudylinesetup) use($yesno,$lineNames,$lineCode,$lineFloor){
             $wstudylinesetup->line_merged_id=$yesno[$wstudylinesetup->line_merged_id];
             $wstudylinesetup->line_name=implode(',',$lineNames[$wstudylinesetup->id]);
             $wstudylinesetup->line_code=implode(',',$lineCode[$wstudylinesetup->id]);
             $wstudylinesetup->line_floor=implode(',',$lineFloor[$wstudylinesetup->id]);
             return $wstudylinesetup;
         });
 
         echo json_encode($wstudylinesetup);
     }
}