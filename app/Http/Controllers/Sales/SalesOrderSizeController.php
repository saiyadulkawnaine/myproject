<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderSizeRepository;
use App\Repositories\Contracts\Sales\SalesOrderColorRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Marketing\StyleSizeRepository;
use App\Library\Template;
use App\Http\Requests\SalesOrderSizeRequest;

class SalesOrderSizeController extends Controller {

    private $salesordersize;
    private $salesordercountry;
    private $stylesize;
	  private $salesordercolor;

    public function __construct(SalesOrderSizeRepository $salesordersize, SalesOrderCountryRepository $salesordercountry,StyleSizeRepository $stylesize, SalesOrderColorRepository $salesordercolor) {
        $this->salesordersize = $salesordersize;
        $this->salesordercountry = $salesordercountry;
        $this->stylesize = $stylesize;
        $this->salesordercolor = $salesordercolor;
        $this->middleware('auth');
        $this->middleware('permission:view.salesordersizes',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.salesordersizes', ['only' => ['store']]);
        $this->middleware('permission:edit.salesordersizes',   ['only' => ['update']]);
        $this->middleware('permission:delete.salesordersizes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $salesordercountry=array_prepend(array_pluck($this->salesordercountry->get(),'name','id'),'-Select-','');
      $salesordersizes=array();
	    $rows=$this->salesordersize->get();
  		foreach($rows as $row){
        $salesordersize['id']=	$row->id;
        $salesordersize['sort']=	$row->sort_id;
        $salesordersize['salesordercountry']=	$salesordercountry[$row->sale_order_country_id];
       // $salesordersize['gmtsize']=	$gmtsize[$row->gmt_size_id];
  		   array_push($salesordersizes,$salesordersize);
  		}
        echo json_encode($salesordersizes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
       /* $stylesize=array_pluck($this->stylesize->leftJoin('sizes', function($join) {
			$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
		->where([['style_sizes.style_id','=',request('style_id',0)]])
		->get([
		'style_sizes.id',
		'sizes.name',
		]),'name','id');*/


		$stylesize=$this->salesordercolor->join('jobs', function($join)  {
		$join->on('sales_order_colors.job_id', '=', 'jobs.id');
		})
		->rightJoin('style_sizes', function($join)  {
		$join->on('jobs.style_id', '=', 'style_sizes.style_id');

		})
		->leftJoin('sales_order_sizes', function($join)  {
		$join->on('style_sizes.id', '=', 'sales_order_sizes.style_size_id');
		$join->on('sales_order_colors.id', '=', 'sales_order_sizes.sale_order_color_id');
		})
		->join('sizes', function($join) {
		$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
		->orderBy('style_sizes.sort_id')
		 ->where('jobs.style_id', '=',request('style_id',0))
		->get([
		'sales_order_colors.*',
		'style_sizes.id as stylesize',
		'style_sizes.size_code',
		'sizes.name',
		'sales_order_sizes.qty',
		'sales_order_sizes.rate',
		'sales_order_sizes.amount'
		]);

        return Template::loadView('Sales.SalesOrderSize', ['stylesize'=>$stylesize]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		//print_r($request->qty[21]);
		//$data=$request->only(['qty','style_sample_id']);
		//foreach($data['qty'] as $colorId=>$sizes){
			foreach($request->size as $index=>$style_size_id){
				if($request->qty[$index]){
				$salesordersize = $this->salesordersize->updateOrCreate(
				['job_id' => $request->job_id,'sale_order_id' => $request->sale_order_id,'sale_order_country_id' => $request->sale_order_country_id,'sale_order_color_id' => $request->sale_order_color_id, 'style_size_id' => $style_size_id],
				['qty' => $request->qty[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
				);
				}
			}
			return response()->json(array('success' => true, 'id' => $salesordersize->id, 'message' => 'Save Successfully'), 200);
		//}
        /*$salesordersize = $this->salesordersize->create($request->except(['id']));
        if ($salesordersize) {
            return response()->json(array('success' => true, 'id' => $salesordersize->id, 'message' => 'Save Successfully'), 200);
        }*/
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
        $salesordersize = $this->salesordersize->find($id);
        $row ['fromData'] = $salesordersize;
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
    public function update(SalesOrderSizeRequest $request, $id) {
        $salesordersize = $this->salesordersize->update($id, $request->except(['id']));
        if ($salesordersize) {
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
        if ($this->salesordersize->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
