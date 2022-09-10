<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonEntryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryQtyRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use Illuminate\Support\Carbon;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtExFactoryQtyRequest;

class ProdGmtExFactoryQtyController extends Controller {

    private $prodgmtexfactory;
    private $exfactoryqty;
    private $company;
    private $prodgmtcarton;
    private $location;
    private $buyer;
    private $supplier;
    private $style;



    public function __construct(ProdGmtExFactoryQtyRepository $exfactoryqty, ProdGmtExFactoryRepository $prodgmtexfactory ,ProdGmtCartonEntryRepository $prodgmtcarton, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier, BuyerRepository $buyer, StyleRepository $style) {
        $this->exfactoryqty = $exfactoryqty;
        $this->prodgmtexfactory = $prodgmtexfactory;
        $this->prodgmtcarton = $prodgmtcarton;
        $this->company = $company;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->buyer = $buyer;
        $this->style=$style;


        $this->middleware('auth');
            $this->middleware('permission:view.prodgmtexfatoryqties',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtexfatoryqties', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtexfatoryqties',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtexfatoryqties', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       // $prodgmtexfactory=$this->prodgmtexfactory->find(request('prod_gmt_ex_factory_id',0));
        $rows=$this->prodgmtcarton
        ->selectRaw('
            prod_gmt_ex_factory_qties.id,
            sales_order_countries.id as sales_order_country_id,
            sales_order_countries.country_id,
            sales_orders.sale_order_no,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            buyers.code as buyer_name,
            countries.name as country_id,
            stylepkgratios.qty,
            stylepkgratios.assortment_name,
            stylepkgratios.packing_type,
            prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id,
            sum(sales_order_gmt_color_sizes.qty) as order_qty,
            avg(sales_order_gmt_color_sizes.rate) as order_rate,
            sum(sales_order_gmt_color_sizes.amount) as order_amount
        ')
        ->leftJoin('prod_gmt_carton_details', function($join) {
            $join->on('prod_gmt_carton_details.prod_gmt_carton_entry_id', '=', 'prod_gmt_carton_entries.id');
        })
        ->leftJoin('sales_order_countries', function($join) {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_carton_details.sales_order_country_id');
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
        ->join('prod_gmt_ex_factory_qties', function($join)  {
        $join->on('prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id', '=', 'prod_gmt_carton_details.id');
        })
        ->join('prod_gmt_ex_factories', function($join)  {
        $join->on('prod_gmt_ex_factories.id', '=', 'prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id');
        $join->whereNull('prod_gmt_ex_factory_qties.deleted_at');
        })

        
        ->leftJoin(\DB::raw("(SELECT style_pkgs.id as style_pkg_id,style_pkgs.assortment_name,style_pkgs.packing_type,sum(style_pkg_ratios.qty) as qty FROM style_pkgs join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id   group by style_pkgs.id,style_pkgs.assortment_name,style_pkgs.packing_type) stylepkgratios"), "stylepkgratios.style_pkg_id", "=", "prod_gmt_carton_details.style_pkg_id")
        ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
        })
        ->when(request('job_no'), function ($q) {
            return $q->where('jobs.job_no', 'LIKE', "%".request('job_no', 0)."%");
        })
        ->when(request('sale_order_no'), function ($q) {
            return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
        })
        ->when(request('country_id'), function ($q) {
            return $q->where('sales_order_countries.country_id', '=', request('country_id', 0));
        })
        /*->when(request('date_from'), function ($q) {
            return $q->where('prod_gmt_carton_entries.carton_date', '>=',request('date_from', 0));
        })
       ->when(request('date_to'), function ($q) {
            return $q->where('prod_gmt_carton_entries.carton_date', '<=',request('date_to', 0));
        })
       ->when(request('company_id'), function ($q) {
            return $q->where('prod_gmt_ex_factories.company_id', '=', request('company_id', 0));
        })
       ->when(request('buyer_id'), function ($q) {
            return $q->where('prod_gmt_ex_factories.buyer_id', '=', request('buyer_id', 0));
        })
       ->when(request('location_id'), function ($q) {
            return $q->where('prod_gmt_ex_factories.location_id', '=', request('location_id', 0));
        })*/
        ->where([['prod_gmt_ex_factories.id','=',request('prod_gmt_ex_factory_id',0)]])
        ->orderBy('prod_gmt_ex_factory_qties.id','desc')
        ->groupBy([ 'prod_gmt_ex_factory_qties.id',
            'sales_order_countries.id',
            'sales_order_countries.country_id',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            'styles.id',
            'jobs.job_no',
            'buyers.code',
            'countries.name',
            'stylepkgratios.qty',
            'stylepkgratios.assortment_name',
            'stylepkgratios.packing_type',
            'prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id'
        ])
        ->get()
        ->map(function($rows){
            $rows->amount=number_format($rows->qty*$rows->order_rate,2,'.',',');
            $rows->qty=number_format($rows->qty,2,'.',',');
            return $rows;

        });
        $saved = $rows->filter(function ($value) {
            if($value->id){
                return $value;
            }
        })->values();
        echo json_encode($saved);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $prodgmtexfactory=$this->prodgmtexfactory->find(request('prod_gmt_ex_factory_id',0));
        $rows=$this->prodgmtcarton
        ->selectRaw('
            prod_gmt_carton_details.id,
            sales_order_countries.id as sales_order_country_id,
            sales_order_countries.country_id,
            sales_orders.sale_order_no,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            buyers.code as buyer_name,
            countries.name as country_id,
            stylepkgratios.qty,
            stylepkgratios.assortment_name,
            stylepkgratios.packing_type,
            prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id,
            sum(sales_order_gmt_color_sizes.qty) as order_qty,
            avg(sales_order_gmt_color_sizes.rate) as order_rate,
            sum(sales_order_gmt_color_sizes.amount) as order_amount
        ')
        ->leftJoin('prod_gmt_carton_details', function($join) {
            $join->on('prod_gmt_carton_details.prod_gmt_carton_entry_id', '=', 'prod_gmt_carton_entries.id');
        })
        ->leftJoin('sales_order_countries', function($join) {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_carton_details.sales_order_country_id');
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
        ->leftJoin('prod_gmt_ex_factory_qties', function($join)  {
            $join->on('prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id', '=', 'prod_gmt_carton_details.id');
            $join->whereNull('prod_gmt_ex_factory_qties.deleted_at');
        })

        ->leftJoin(\DB::raw("(SELECT style_pkgs.id as style_pkg_id,style_pkgs.assortment_name,style_pkgs.packing_type,sum(style_pkg_ratios.qty) as qty FROM style_pkgs join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id   group by style_pkgs.id,style_pkgs.assortment_name,style_pkgs.packing_type) stylepkgratios"), "stylepkgratios.style_pkg_id", "=", "prod_gmt_carton_details.style_pkg_id")

        ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
        })
        ->when(request('job_no'), function ($q) {
            return $q->where('jobs.job_no', 'LIKE', "%".request('job_no', 0)."%");
        })
        ->when(request('sale_order_no'), function ($q) {
            return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
        })
        ->when(request('country_id'), function ($q) {
            return $q->where('sales_order_countries.country_id', '=', request('country_id', 0));
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('prod_gmt_carton_entries.carton_date', '>=',request('date_from', 0));
            })
       ->when(request('date_to'), function ($q) {
            return $q->where('prod_gmt_carton_entries.carton_date', '<=',request('date_to', 0));
            })
        ->where([['prod_gmt_carton_entries.company_id','=', $prodgmtexfactory->company_id]])
        ->where([['prod_gmt_carton_entries.buyer_id','=', $prodgmtexfactory->buyer_id]])
        ->where([['prod_gmt_carton_entries.location_id','=', $prodgmtexfactory->location_id]])
        ->orderBy('prod_gmt_carton_details.id')
        ->groupBy([ 'prod_gmt_carton_details.id',
            'sales_order_countries.id',
            'sales_order_countries.country_id',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            'styles.id',
            'jobs.job_no',
            'buyers.code',
            'countries.name',
            'stylepkgratios.qty',
            'stylepkgratios.assortment_name',
            'stylepkgratios.packing_type',
            'prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id'
        ])
        ->get() 
        ->map(function($rows){
            $rows->amount=number_format($rows->qty*$rows->order_rate,2,'.',',');
            $rows->qty=number_format($rows->qty,2,'.',',');
            return $rows;

        });
        $notsaved = $rows->filter(function ($value) {
            if(!$value->prod_gmt_carton_detail_id){
                return $value;
            }
        })->values();
        echo json_encode($notsaved);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtExFactoryQtyRequest $request) {

        /*foreach($request->prod_gmt_carton_detail_id as $index=>$prod_gmt_carton_detail_id){
            if($prod_gmt_carton_detail_id)
            {
                $exfactoryqty = $this->exfactoryqty->create(
                ['prod_gmt_carton_detail_id' => $prod_gmt_carton_detail_id,'prod_gmt_ex_factory_id' => $request->prod_gmt_ex_factory_id]);
            }
        }

        if($exfactoryqty){
            return response()->json(array('success' => true,'id' =>  $exfactoryqty->id,'message' => 'Save Successfully'),200);
        }*/

        $allintests = [];
        $timestamp = Carbon::now();
        $user = \Auth::user();
        foreach($request->prod_gmt_carton_detail_id as $index=>$prod_gmt_carton_detail_id)
        {
            if($prod_gmt_carton_detail_id)
            {
            	array_push($allintests, ['prod_gmt_carton_detail_id' => $prod_gmt_carton_detail_id,'prod_gmt_ex_factory_id' => $request->prod_gmt_ex_factory_id,'created_by'=>$user->id,'created_at'=>$timestamp]);
            }
        }

        $exfactoryqty = $this->exfactoryqty->insert($allintests);
        if($exfactoryqty){
            return response()->json(array('success' => true,'id' =>'','message' => 'Save Successfully'),200);
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
        $exfactoryqty = $this->exfactoryqty->find($id);
        $row ['fromData'] = $exfactoryqty;
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
    public function update(ProdGmtExFactoryRequest $request, $id) {
        $exfactoryqty=$this->exfactoryqty->update($id,['prod_gmt_ex_factory_id'=>$request->prod_gmt_ex_factory_id,'prod_gmt_carton_detail_id'=>$request->prod_gmt_carton_detail_id]);
        if($exfactoryqty){
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
        if($this->exfactoryqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully','action'=>'delete'),200);
        }
    }
    public function exStyle(){
        $deptcategory=array_prepend(config('bprs.deptcategory'),'-Select-','');
        $rows=$this->style
        ->leftJoin('buyers', function($join)  {
        $join->on('styles.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('uoms', function($join)  {
        $join->on('styles.uom_id', '=', 'uoms.id');
        })
        ->leftJoin('seasons', function($join)  {
        $join->on('styles.season_id', '=', 'seasons.id');
        })
        ->leftJoin('teams', function($join)  {
        $join->on('styles.team_id', '=', 'teams.id');
        })
        ->leftJoin('teammembers', function($join)  {
        $join->on('styles.teammember_id', '=', 'teammembers.id');
        })
        ->leftJoin('users', function($join)  {
        $join->on('users.id', '=', 'teammembers.user_id');
        })
        ->leftJoin('productdepartments', function($join)  {
        $join->on('productdepartments.id', '=', 'styles.productdepartment_id');
        })
        ->when(request('buyer_id'), function ($q) {
        return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
        })
        ->when(request('style_ref'), function ($q) {
        return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
        })
        ->when(request('style_description'), function ($q) {
        return $q->where('styles.style_description', 'like', '%'.request('style_description', 0).'%');
        })
        ->orderBy('styles.id','desc')
        ->get([
        'styles.*',
        'buyers.code as buyer_name',
        'uoms.name as uom_name',
        'seasons.name as season_name',
        'teams.name as team_name',
        'users.name as team_member_name',
        'productdepartments.department_name'
        ])
        ->map(function($rows) use($deptcategory){
            $rows->dept_category_id=$deptcategory[$rows->dept_category_id];
            return $rows;
        });

        echo json_encode($rows);
    }
}