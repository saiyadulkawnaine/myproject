<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Library\Template;
use App\Http\Requests\SalesOrderCountryRequest;

class SalesOrderCountryController extends Controller {

    private $salesordercountry;
    private $salesorder;
    private $country;
    private $stylegmts;
  	private $salesordergmtcolorsize;
  	private $stylegmtcolorsize;

    public function __construct(SalesOrderCountryRepository $salesordercountry, SalesOrderRepository $salesorder,CountryRepository $country,StyleGmtsRepository $stylegmts,SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,StyleGmtColorSizeRepository $stylegmtcolorsize) {
        $this->salesordercountry = $salesordercountry;
        $this->salesorder = $salesorder;
        $this->country = $country;
        $this->stylegmts = $stylegmts;
    		$this->salesordergmtcolorsize = $salesordergmtcolorsize;
    		$this->stylegmtcolorsize = $stylegmtcolorsize;
        $this->middleware('auth');
        $this->middleware('permission:view.salesordercountrys',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.salesordercountrys', ['only' => ['store']]);
        $this->middleware('permission:edit.salesordercountrys',   ['only' => ['update']]);
        $this->middleware('permission:delete.salesordercountrys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$salesordercountrys=array();
		$rows = $this->salesordercountry->getAll();
		foreach($rows as $row){
			$salesordercountry['id']=	$row->id;
			$salesordercountry['shipdate']=	$row->country_ship_date;
			$salesordercountry['qty']=	$row->qty;
			if($row->qty && $row->amount){
				$salesordercountry['rate']=	$row->amount/$row->qty;
			}else{
				$salesordercountry['rate']='';
			}
			$salesordercountry['amount']=	$row->amount;
			$salesordercountry['sam']=	$row->sam;
			$salesordercountry['salesorder']=	$row->sale_order_no;
			$salesordercountry['country']=	$row->name;
			array_push($salesordercountrys,$salesordercountry);
		}
		echo json_encode($salesordercountrys);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
      $stylegmts=array_prepend(array_pluck($this->stylegmts->get(),'name','id'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      return Template::loadView('Sales.SalesOrderCountry', ['country'=>$country,'stylegmts'=>$stylegmts,'fabriclooks'=>$fabriclooks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalesOrderCountryRequest $request) {
        $salesordercountry = $this->salesordercountry->create($request->except(['id','sale_order_no']));
        if ($salesordercountry) {
            return response()->json(array('success' => true, 'id' => $salesordercountry->id, 'message' => 'Save Successfully'), 200);
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
		$salesordercountry = $this->salesordercountry->join('sales_orders', function($join)  {
		$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->where('sales_order_countries.id','=',$id)
		->get([
			'sales_order_countries.*',
			'sales_orders.sale_order_no',
		])->first();

		$colorsizes=$this->stylegmtcolorsize
		->join('style_gmts', function($join) {
		$join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->join('style_colors', function($join) {
			$join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
		})
		->join('colors', function($join) {
		$join->on('style_colors.color_id', '=', 'colors.id');
		})
		->join('style_sizes', function($join) {
		$join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
		})
		->join('sizes', function($join) {
		$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
		->join('styles', function($join) {
		$join->on('styles.id', '=', 'style_gmt_color_sizes.style_id');
		})
		->join('jobs', function($join) use($salesordercountry){
			$join->on('jobs.style_id', '=', 'styles.id');
		})
		->join('sales_orders', function($join) use($salesordercountry) {
			$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->join('sales_order_countries', function($join){
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('sales_order_gmt_color_sizes',function($join){
			$join->on('sales_order_countries.id', '=', 'sales_order_gmt_color_sizes.sale_order_country_id');
			//$join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
			$join->on('style_gmt_color_sizes.style_gmt_id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
			$join->on('style_gmt_color_sizes.style_color_id', '=', 'sales_order_gmt_color_sizes.style_color_id');
			$join->on('style_gmt_color_sizes.style_size_id', '=', 'sales_order_gmt_color_sizes.style_size_id');
		})
		->orderBy('style_gmt_color_sizes.style_gmt_id')
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
		->where('sales_order_countries.id', '=', $id)
		->get([
		'style_gmt_color_sizes.id as style_gmt_color_size_id',
		'style_gmt_color_sizes.style_gmt_id',
		'style_colors.id as style_color_id',
		'style_colors.sort_id as color_sort_id',
		'colors.name as color_name',
		'colors.code as color_code',
		'style_sizes.id as style_size_id',
		'style_sizes.sort_id',
		'sizes.name',
		'sizes.code',
		'item_accounts.item_description',
		'sales_order_gmt_color_sizes.qty',
		'sales_order_gmt_color_sizes.rate',
		'sales_order_gmt_color_sizes.amount',
		'sales_order_gmt_color_sizes.article_no',
		]);

        $row ['fromData'] = $salesordercountry;
        $dropdown['gmtcosi'] = "'".Template::loadView('Sales.GmtColorSizeMatrix',['colorsizes'=>$colorsizes])."'";
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
    public function update(SalesOrderCountryRequest $request, $id) {
        $salesordercountry = $this->salesordercountry->update($id, $request->except(['id','sale_order_no']));
        if ($salesordercountry) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->salesordercountry->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
