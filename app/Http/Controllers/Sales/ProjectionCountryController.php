<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\ProjectionCountryRepository;
use App\Repositories\Contracts\Sales\ProjectionRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Library\Template;
use App\Http\Requests\ProjectionCountryRequest;

class ProjectionCountryController extends Controller {

    private $projectioncountry;
    private $projection;
    private $country;
    private $stylegmts;
  	private $projectiongmtcolorsize;
  	private $stylegmtcolorsize;

    public function __construct(ProjectionCountryRepository $projectioncountry, ProjectionRepository $projection,CountryRepository $country) {
        $this->projectioncountry = $projectioncountry;
        $this->projection = $projection;
        $this->country = $country;
        $this->middleware('auth');
        $this->middleware('permission:view.projectioncountrys',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.projectioncountrys', ['only' => ['store']]);
        $this->middleware('permission:edit.projectioncountrys',   ['only' => ['update']]);
        $this->middleware('permission:delete.projectioncountrys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		 $cutoff=config('bprs.cutoff');
		$projectioncountrys=array();
		$rows = $this->projectioncountry->getAll();
		foreach($rows as $row){
			$projectioncountry['id']=	$row->id;
			$projectioncountry['shipdate']=	$row->country_ship_date;
			$projectioncountry['cut_off_date']=	$row->cut_off_date;
			//$projectioncountry['cut_off']=	 $cutoff[$row->cut_off];
			$projectioncountry['qty']=	$row->qty;
			if($row->qty && $row->amount){
				$projectioncountry['rate']=	$row->amount/$row->qty;
			}else{
				$projectioncountry['rate']='';
			}
			$projectioncountry['amount']=	$row->amount;
			$projectioncountry['proj_no']=	$row->proj_no;
			$projectioncountry['country']=	$row->name;
			array_push($projectioncountrys,$projectioncountry);
		}
		echo json_encode($projectioncountrys);
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
      return Template::loadView('Sales.ProjectionCountry', ['country'=>$country,'stylegmts'=>$stylegmts,'fabriclooks'=>$fabriclooks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectionCountryRequest $request) {
        $projectioncountry = $this->projectioncountry->create($request->except(['id','sale_order_no']));
        if ($projectioncountry) {
            return response()->json(array('success' => true, 'id' => $projectioncountry->id, 'message' => 'Save Successfully'), 200);
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
		$projectioncountry = $this->projectioncountry->join('projections', function($join)  {
		$join->on('projections.id', '=', 'projection_countries.projection_id');
		})
		->where('projection_countries.id','=',$id)
		->get([
			'projection_countries.*',
			'projections.proj_no',
		])->first();

		/*$colorsizes=$this->stylegmtcolorsize
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
		->join('jobs', function($join) use($projectioncountry){
			$join->on('jobs.style_id', '=', 'styles.id');
		})
		->join('sales_orders', function($join) use($projectioncountry) {
			$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->join('sales_order_countries', function($join){
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('sales_order_gmt_color_sizes',function($join){
			$join->on('sales_order_countries.id', '=', 'sales_order_gmt_color_sizes.sale_order_country_id');
			$join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
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
		]);*/

        $row ['fromData'] = $projectioncountry;
        $dropdown['gmtcosi'] = '';
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
    public function update(ProjectionCountryRequest $request, $id) {
        $projectioncountry = $this->projectioncountry->update($id, $request->except(['id','sale_order_no']));
        if ($projectioncountry) {
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
        if ($this->projectioncountry->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
