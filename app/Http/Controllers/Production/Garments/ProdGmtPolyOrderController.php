<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPolyRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPolyOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPolyQtyRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\SupplierRepository;

use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtPolyOrderRequest;


class ProdGmtPolyOrderController extends Controller {

    private $prodgmtpolyorder;
    private $prodgmtpoly;
    private $location;
    private $gmtpolyqty;

    public function __construct(ProdGmtPolyOrderRepository $prodgmtpolyorder,ProdGmtPolyRepository $prodgmtpoly, LocationRepository $location,SalesOrderCountryRepository $salesordercountry, WstudyLineSetupRepository $wstudylinesetup,ProdGmtPolyQtyRepository $gmtpolyqty, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize, SupplierRepository $supplier) {
        $this->prodgmtpolyorder = $prodgmtpolyorder;
        $this->prodgmtpoly = $prodgmtpoly;
        $this->gmtpolyqty = $gmtpolyqty;
        $this->salesordercountry = $salesordercountry;
        $this->location = $location;
        $this->wstudylinesetup = $wstudylinesetup;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->supplier = $supplier;

        $this->middleware('auth');
        // $this->middleware('permission:view.prodgmtpolyorders',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.prodgmtpolyorders', ['only' => ['store']]);
        // $this->middleware('permission:edit.prodgmtpolyorders',   ['only' => ['update']]);
        // $this->middleware('permission:delete.prodgmtpolyorders', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

	$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
    $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
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
        foreach($subsections as $subsection)
        {
           $lineNames[$subsection->id][]=$subsection->name;
           $lineCode[$subsection->id][]=$subsection->code;
           $lineFloor[$subsection->id][]=$subsection->floor_name;
        }
         
        $prodgmtpolyorders=array();
        $rows=$this->prodgmtpolyorder
        ->leftJoin('prod_gmt_polies', function($join)  {
            $join->on('prod_gmt_polies.id', '=', 'prod_gmt_poly_orders.prod_gmt_poly_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_poly_orders.sales_order_country_id');
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
            $join->on('prod_gmt_poly_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->leftJoin('locations', function($join)  {
            $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
        })
        ->where([['prod_gmt_poly_id','=', request('prod_gmt_poly_id',0)]])
        ->orderBy('prod_gmt_poly_orders.id','desc')
        ->get([
            'prod_gmt_poly_orders.*',
            'sales_orders.sale_order_no',
            'wstudy_line_setups.location_id',
            'locations.name as location_id'
        ]);
        foreach($rows as $row){
            $prodgmtpolyorder['id']=$row->id;
            $prodgmtpolyorder['sales_order_country_id']=$row->sales_order_country_id;
            $prodgmtpolyorder['sale_order_no']=$row->sale_order_no;
            $prodgmtpolyorder['prod_source_id']=$productionsource[$row->prod_source_id];
            $prodgmtpolyorder['supplier_id']=$supplier[$row->supplier_id];
            $prodgmtpolyorder['location_id']=$row->location_id;
            $prodgmtpolyorder['wstudy_line_setup_id']=isset($row->wstudy_line_setup_id)?implode(',',$lineCode[$row->wstudy_line_setup_id]):'';
            array_push($prodgmtpolyorders,$prodgmtpolyorder);
        }
        echo json_encode($prodgmtpolyorders);
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
    public function store(ProdGmtPolyOrderRequest $request) {
		$prodgmtpolyorder=$this->prodgmtpolyorder->create([
            'prod_gmt_poly_id'=>$request->prod_gmt_poly_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'prod_source_id'=>$request->prod_source_id,
            'supplier_id'=>$request->supplier_id,
            'wstudy_line_setup_id'=>$request->wstudy_line_setup_id,
            'prod_hour'=>$request->prod_hour
        ]);
        if($prodgmtpolyorder){
            return response()->json(array('success' => true,'id' =>  $prodgmtpolyorder->id,'message' => 'Save Successfully'),200);
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

        $subsections=$this->prodgmtpolyorder
        ->leftJoin('wstudy_line_setups', function($join)  {
            $join->on('prod_gmt_poly_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
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
        ->where([['prod_gmt_poly_orders.id','=',$id]])
        ->get([
            'wstudy_line_setups.id',
            'subsections.code'
        ]);
        $lineNames=Array();
        foreach($subsections as $subsection)
        {
           $lineNames[$subsection->id][]=$subsection->code;
        }


        $prodgmtpolyorder = $this->prodgmtpolyorder
        ->join('prod_gmt_polies', function($join)  {
            $join->on('prod_gmt_polies.id', '=', 'prod_gmt_poly_orders.prod_gmt_poly_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_poly_orders.sales_order_country_id');
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
            $join->on('prod_gmt_poly_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->leftJoin('locations', function($join)  {
            $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
        })
        ->where([['prod_gmt_poly_orders.id','=',$id]])
        ->get([
            'prod_gmt_poly_orders.*',
            'sales_orders.sale_order_no',
            'wstudy_line_setups.id as wstudy_line_setup_id',
            'sales_orders.produced_company_id',
            'sales_orders.ship_date',
            'sales_orders.job_id',
            'jobs.job_no',
            'jobs.company_id',
            'styles.buyer_id',
            'companies.name as company_id',
            'countries.name as country_id',
            'locations.name as location_id',
            'buyers.name as buyer_name',
            'produced_company.name as produced_company_name'

        ])->map(function($prodgmtpolyorder) use($lineNames){
            $prodgmtpolyorder->line_name=isset($prodgmtpolyorder->wstudy_line_setup_id)?implode(',',$lineNames[$prodgmtpolyorder->wstudy_line_setup_id]):'';
            return $prodgmtpolyorder;
        })->first();

        $gmtpolyqty = $this->prodgmtpolyorder
        ->leftJoin('prod_gmt_polies', function($join)  {
            $join->on('prod_gmt_polies.id', '=', 'prod_gmt_poly_orders.prod_gmt_poly_id');
        })
        ->leftJoin('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_poly_orders.sales_order_country_id');
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
        ->leftJoin('prod_gmt_poly_qties',function($join){
           $join->on('prod_gmt_poly_qties.prod_gmt_poly_order_id','=','prod_gmt_poly_orders.id');
           $join->on('prod_gmt_poly_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_poly_qties.qty) as qty FROM prod_gmt_poly_qties join prod_gmt_poly_orders on prod_gmt_poly_orders.id =prod_gmt_poly_qties.prod_gmt_poly_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_poly_qties.sales_order_gmt_color_size_id where prod_gmt_poly_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")
  	    ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')    
        ->where([['prod_gmt_poly_orders.id','=',$id]])
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
          'prod_gmt_poly_qties.id as prod_gmt_poly_qty_id',
          'prod_gmt_poly_qties.qty',
          'prod_gmt_poly_qties.alter_qty',
          'prod_gmt_poly_qties.spot_qty',
          'prod_gmt_poly_qties.reject_qty',
          'prod_gmt_poly_qties.replace_qty',
          'cumulatives.qty as cumulative_qty'
        ])
        ->map(function ($gmtpolyqty){
            $gmtpolyqty->balance_qty=$gmtpolyqty->plan_cut_qty-$gmtpolyqty->cumulative_qty;
            $gmtpolyqty->cumulative_qty_saved=$gmtpolyqty->cumulative_qty-$gmtpolyqty->qty;
            $gmtpolyqty->balance_qty_saved=$gmtpolyqty->plan_cut_qty-$gmtpolyqty->cumulative_qty_saved;
            return $gmtpolyqty;
        });
        $saved = $gmtpolyqty->filter(function ($value) {
            if($value->prod_gmt_poly_qty_id){
                return $value;
            }
        });
        $new = $gmtpolyqty->filter(function ($value) {
            if(!$value->prod_gmt_poly_qty_id){
                return $value;
            }
        });

        $row ['fromData'] = $prodgmtpolyorder;
        $dropdown['polygmtcosi'] = "'".Template::loadView('Production.Garments.ProdGmtPolyQtyMatrix',['colorsizes'=>$new,'saved'=>$saved])."'";
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
    public function update(ProdGmtPolyOrderRequest $request, $id) {
        $prodgmtpolyorder=$this->prodgmtpolyorder->update($id,
        [
            'prod_gmt_poly_id'=>$request->prod_gmt_poly_id,
            'sales_order_country_id'=>$request->sales_order_country_id,
            'prod_source_id'=>$request->prod_source_id,
            'supplier_id'=>$request->supplier_id,
            'wstudy_line_setup_id'=>$request->wstudy_line_setup_id,
            'prod_hour'=>$request->prod_hour
        ]);
        if($prodgmtpolyorder){
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
        if($this->prodgmtpolyorder->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getPolyOrder(){
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

    public function getLine(){
       $prodgmtpoly=$this->prodgmtpoly->find(request('prod_gmt_poly_id',0));
       $poly_qc_date=$prodgmtpoly->poly_qc_date;
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
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
        ->where([['wstudy_line_setups.company_id','=',request('produced_company_id',0)]])
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
        ->join('locations', function($join)  {
            $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
        })
        ->when(request('line_merged_id'), function ($q) {
            return $q->where('wstudy_line_setups.line_merged_id', 'LIKE', "%".request('line_merged_id', 0)."%");
        })
        ->whereRAW(" ? between wstudy_line_setup_dtls.from_date and wstudy_line_setup_dtls.to_date ",[$poly_qc_date])

        /*->when($poly_qc_date, function ($q) use($poly_qc_date){
        return $q->where('wstudy_line_setup_dtls.from_date', '>=',$poly_qc_date);
        })
        ->when($poly_qc_date, function ($q) use($poly_qc_date){
        return $q->where('wstudy_line_setup_dtls.to_date', '<=',$poly_qc_date);
        })*/
        ->where([['wstudy_line_setups.company_id','=',request('produced_company_id',0)]])
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