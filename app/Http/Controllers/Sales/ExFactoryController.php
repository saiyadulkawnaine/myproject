<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\ExFactoryRepository;
use App\Library\Template;
use App\Http\Requests\ExFactoryRequest;

class ExFactoryController extends Controller {

    private $salesorder;
    private $exfactory;
    private $job;
    private $style;

    public function __construct(SalesOrderRepository $salesorder, ExFactoryRepository $exfactory, JobRepository $job,StyleRepository $style) {
        $this->salesorder = $salesorder;
        $this->exfactory = $exfactory;
        $this->job = $job;
        $this->style = $style;

        $this->middleware('auth');
        $this->middleware('permission:view.exfactories',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.exfactories', ['only' => ['store']]);
        $this->middleware('permission:edit.exfactories',   ['only' => ['update']]);
        $this->middleware('permission:delete.exfactories', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $salesorder=array_prepend(array_pluck($this->salesorder->get(),'sale_order_no','id'),'-Select-','');
      $exfactories=array();
        $rows=$this->exfactory
       /*  ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
       ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        }) */
        //->leftJoin('ex_factories', function($join)  {
           // $join->on('ex_factories.sale_order_id', '=', 'sales_orders.id');
       // })
        //->where([['id','=',request('id',0)]])
        //->orderBy('id','desc')
        ->get();
  		foreach($rows as $row){
        $exfactory['id']= $row->id;
        $exfactory['sale_order_id']= $row->sale_order_id;
        $exfactory['ship_date']= date('Y-m-d',strtotime($row->ship_date));
        $exfactory['rate']=	$row->rate;
        $exfactory['qty']=	$row->qty;
        $exfactory['amount']= $row->amount;
  		   array_push($exfactories,$exfactory);
  		}
        echo json_encode($exfactories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $salesorder=array_prepend(array_pluck($this->salesorder->get(),'sale_order_no','id'),'-Select-','');
        return Template::loadView('Sales.ExFactory', ['salesorder'=>$salesorder]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExFactoryRequest $request) {
		$exfactory = $this->exfactory->create(['sale_order_id'=>$request->sale_order_id,'ship_date'=> $request->ship_date,'qty' => $request->qty,'rate' => $request->rate,'amount' =>$request->amount]);
        if ($exfactory) {
            return response()->json(array('success' => true, 'id' => $exfactory->id, 'message' => 'Save Successfully'), 200);
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
        $exfactory = $this->exfactory
        ->selectRaw('ex_factories.id,
            ex_factories.sale_order_id,
            sales_orders.id as sale_order_id,
            sales_orders.sale_order_no,
            ex_factories.ship_date,
            ex_factories.qty,   
            ex_factories.rate,
            ex_factories.amount')
       /*  ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        }) */
        ->join('sales_orders', function($join)  {
            $join->on('sales_orders.id', '=', 'ex_factories.sale_order_id');
        })
        ->where([['ex_factories.id','=',$id]])
        ->get([
           'ex_factories.*',
           'sales_orders.sale_order_no'
       ])
       ->first();
        $row ['fromData'] = $exfactory;
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
    public function update(ExFactoryRequest $request, $id) {
        $exfactory = $this->exfactory->update($id, ['sale_order_id'=>$request->sale_order_id,'ship_date'=> $request->ship_date,'qty' => $request->qty,'rate' => $request->rate,'amount' =>$request->amount]);
        if ($exfactory) {
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
        if ($this->exfactory->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getSalesJS(){

       $orders=$this->salesorder
       ->selectRaw('
        sales_orders.id,
        sales_orders.sale_order_no,
        styles.style_ref,
        jobs.job_no
        ')
       ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
       ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
        })
        ->when(request('job_no'), function ($q) {
            return $q->where('jobs.job_no', 'LIKE', "%".request('job_no', 0)."%");
        })
       ->get(['sales_orders.id',
       'sales_orders.sale_order_no',
       'styles.style_ref',
       'jobs.job_no',]);
       //->toSql();
        
       echo json_encode($orders);
    }

}
