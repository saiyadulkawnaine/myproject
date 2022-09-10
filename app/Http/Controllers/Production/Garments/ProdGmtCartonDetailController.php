<?php
namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Http\Requests\Production\Garments\ProdGmtCartonDetailRequest;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonDetailRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonEntryRepository;
use App\Repositories\Contracts\Marketing\StylePkgRepository;
use App\Repositories\Contracts\Marketing\StylePkgRatioRepository;
use App\Library\Template;

class ProdGmtCartonDetailController extends Controller {

    private $country;
    private $prodgmtcartondetail;
    private $salesordercountry;
    private $stylepkg;
    private $stylepkgratio;


    public function __construct(ProdGmtCartonDetailRepository $prodgmtcartondetail, CountryRepository $country, SalesOrderCountryRepository $salesordercountry,ProdGmtCartonEntryRepository $prodgmtcarton,StylePkgRepository $stylepkg,StylePkgRatioRepository $stylepkgratio) {
        $this->prodgmtcartondetail = $prodgmtcartondetail;
        $this->prodgmtcarton = $prodgmtcarton;
        $this->salesordercountry = $salesordercountry;
        $this->country = $country;
        $this->stylepkg = $stylepkg;
        $this->stylepkgratio = $stylepkgratio;


        $this->middleware('auth');
            $this->middleware('permission:view.prodgmtcartondetails',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtcartondetails', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtcartondetails',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtcartondetails', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         
        $rows=$this->prodgmtcartondetail
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
        sum(sales_order_gmt_color_sizes.qty) as order_qty,
        avg(sales_order_gmt_color_sizes.rate) as order_rate,
        sum(sales_order_gmt_color_sizes.amount) as order_amount,
        stylepkgratios.qty,
        stylepkgratios.assortment_name,
        stylepkgratios.packing_type,
        prod_gmt_ex_factory_qties.id as prod_gmt_ex_factory_qty_id
        ')
        ->leftJoin('prod_gmt_carton_entries', function($join) {
            $join->on('prod_gmt_carton_entries.id', '=', 'prod_gmt_carton_details.prod_gmt_carton_entry_id');
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
        ->leftJoin(\DB::raw("(SELECT style_pkgs.id as style_pkg_id,style_pkgs.assortment_name,style_pkgs.packing_type,sum(style_pkg_ratios.qty) as qty FROM style_pkgs join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id   group by style_pkgs.id,style_pkgs.assortment_name,style_pkgs.packing_type) stylepkgratios"), "stylepkgratios.style_pkg_id", "=", "prod_gmt_carton_details.style_pkg_id")
        ->leftJoin('prod_gmt_ex_factory_qties', function($join)  {
        $join->on('prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id', '=', 'prod_gmt_carton_details.id');
        $join->whereNull('prod_gmt_ex_factory_qties.deleted_at');
        })
        ->where([['prod_gmt_carton_entry_id',request('prod_gmt_carton_entry_id',0)]])
        ->orderBy('prod_gmt_carton_details.id','desc')
        ->groupBy([
        'prod_gmt_carton_details.id',
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
        'prod_gmt_ex_factory_qties.id'
       ])
        ->get()
        ->map(function($rows){
            $rows->carton_amount=number_format($rows->qty*$rows->order_rate,2,'.',',');
            $rows->qty=number_format($rows->qty,2,'.',',');
            return $rows;
        })
        ->toJson();
        echo $rows;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtCartonDetailRequest $request) {
        for($i=1;$i<=$request->qty;$i++)
        {
          $prodgmtcartondetail=$this->prodgmtcartondetail->create(['prod_gmt_carton_entry_id'=>$request->prod_gmt_carton_entry_id,'sales_order_country_id'=>$request->sales_order_country_id,'style_pkg_id'=>$request->style_pkg_id,'qty'=>$i]);
        }
        if($prodgmtcartondetail){
            return response()->json(array('success' => true,'id' =>  $prodgmtcartondetail->id,'message' => 'Save Successfully'),200);
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
        $prodgmtcartondetail = $this->prodgmtcartondetail->find($id);
        $row ['fromData'] = $prodgmtcartondetail;
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
    public function update(ProdGmtCartonDetailRequest $request, $id) {
        $prodgmtcartondetail=$this->prodgmtcartondetail->update($id,['prod_gmt_carton_entry_id'=>$request->prod_gmt_carton_entry_id,'sales_order_country_id'=>$request->sales_order_country_id,'qty'=>$request->qty]);
        if($prodgmtcartondetail){
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
        if($this->prodgmtcartondetail->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getCartonSalesOrder()
    {

        $prodgmtcarton=$this->prodgmtcarton->find(request('prodgmtcartonid',0));
        
        

       

       $salesordercountry=$this->salesordercountry
       ->selectRaw('
        sales_order_countries.id,
        sales_order_countries.country_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        styles.id as style_id,
        jobs.job_no,
        buyers.code as buyer_name,
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
       ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
       ->join('sales_order_gmt_color_sizes', function($join)  {
        $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
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
        ->where([['jobs.company_id','=',$prodgmtcarton->company_id]])
        ->where([['styles.buyer_id','=',$prodgmtcarton->buyer_id]])
        ->groupBy([
        'sales_order_countries.id',
        'sales_order_countries.country_id',
        'sales_orders.sale_order_no',
        'styles.style_ref',
        'styles.id',
        'jobs.job_no',
        'buyers.code',
        'countries.name'
       ])
       ->get()
       ->map(function ($salesordercountry){
         //$salesordercountry->country_id=$countries[$salesordercountry->id];
         return $salesordercountry;
        });
       
       echo json_encode($salesordercountry);
    }

    public function getpkgratio()
    {
        $assortment=array_prepend(config('bprs.assortment'),'-Select-','');
        $rows = $this->stylepkg->selectRaw(
        'style_pkgs.id,
        style_pkgs.style_id,
        style_pkgs.spec,
        style_pkgs.assortment,
        style_pkgs.assortment_name,
        style_pkgs.packing_type,
        style_pkgs.itemclass_id,
        styles.style_ref,
        itemclasses.name,
        sum(style_pkg_ratios.qty) as qty'
        )
        ->leftJoin('style_pkg_ratios', function($join) {
        $join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
        })
        ->join('styles', function($join)  {
        $join->on('styles.id', '=', 'style_pkgs.style_id');
        })
        ->join('itemclasses', function($join)  {
        $join->on('itemclasses.id', '=', 'style_pkgs.itemclass_id');
        })
        ->when(request('style_id'), function ($q) {
        return $q->where('style_pkgs.style_id', '=', request('style_id', 0));
        })
        ->when(request('style_gmt_id'), function ($q) {
        return $q->where('style_pkgs.style_gmt_id', '=', request('style_gmt_id', 0));
        })
        ->where([['styles.id','=',request('style_id',0)]])
        ->groupBy([
        'style_pkgs.id',
        'style_pkgs.style_id',
        'style_pkgs.spec',
        'style_pkgs.assortment_name',
        'style_pkgs.packing_type',
        'style_pkgs.assortment',
        'style_pkgs.itemclass_id',
        'styles.style_ref',
        'itemclasses.name',
        ])
        ->get()->map(function ($rows) use($assortment){
         $rows->style_pkg_name=$assortment[$rows->assortment];
         return $rows;
        });
        echo json_encode($rows);
    }
}