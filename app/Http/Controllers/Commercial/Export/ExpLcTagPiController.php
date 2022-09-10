<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpLcScPiRepository;
use App\Repositories\Contracts\Commercial\Export\ExpPiRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpLcScPiRequest;

class ExpLcTagPiController extends Controller {

    private $lcscpi;
    private $explcsc;
    private $exppi;

    public function __construct(ExpLcScPiRepository $lcscpi,ExpPiRepository $exppi,ExpLcScRepository $explcsc) {
        $this->lcscpi = $lcscpi;
        $this->explcsc = $explcsc;
        $this->exppi = $exppi;

        $this->middleware('auth');
        $this->middleware('permission:view.explcsctagpis',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.explcsctagpis', ['only' => ['store']]);
        $this->middleware('permission:edit.explcsctagpis',   ['only' => ['update']]);
        $this->middleware('permission:delete.explcsctagpis', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $exppi =$this->lcscpi
       ->selectRaw('
        exp_lc_sc_pis.id,
        exp_pis.id as exp_pi_id,
        exp_pis.pi_no,
        sum(exp_pi_orders.qty) as qty,
        avg(exp_pi_orders.rate) as rate,
        sum(exp_pi_orders.amount) as amount
        ')
       ->join('exp_pis', function($join)  {
        $join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
        })
       ->join('exp_pi_orders', function($join)  {
        $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
        })
       ->join('sales_orders', function($join)  {
        $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
        })
       ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
       ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
       ->where([['exp_lc_sc_pis.exp_lc_sc_id','=',request('exp_lc_sc_id',0)]])
       ->groupBy([
        'exp_lc_sc_pis.id',
        'exp_pis.id',
        'exp_pis.pi_no',
       ])
       ->get();
       echo json_encode($exppi);


       
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
    public function store(ExpLcScPiRequest $request) {
        foreach($request->exp_pi_id as $index=>$exp_pi_id){
            if($exp_pi_id)
            {
                $lcscpi = $this->lcscpi->create(
                ['exp_pi_id' => $exp_pi_id,'exp_lc_sc_id' => $request->exp_lc_sc_id]);
            }
        }
        if($lcscpi){
            return response()->json(array('success' => true,'id' =>  $lcscpi->id,'message' => 'Save Successfully'),200);
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
       $lcscpi=$this->lcscpi->find($id);
       $row['fromData']=$lcscpi;
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
    public function update(ExpLcScPiRequest $request, $id) {
        $lcscpi=$this->lcscpi->update($id,$request->except(['id']));
        if($lcscpi){
            return response()->json(array('success'=>true,'id'=>$id,'message'=>'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->lcscpi->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function importpi()
    {

       $explcsc=$this->explcsc->find(request('expsaleconid',0));

       $exppi =$this->exppi
       ->selectRaw('
        exp_pis.id,
        exp_pis.pi_no,
        exp_lc_sc_pis.exp_pi_id,
        sum(exp_pi_orders.qty) as qty,
        avg(exp_pi_orders.rate) as rate,
        sum(exp_pi_orders.amount) as amount
        ')
       ->join('exp_pi_orders', function($join)  {
        $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
        })
       ->join('sales_orders', function($join)  {
        $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
        })
       ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
       ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
       ->leftJoin('exp_lc_sc_pis',function($join){
          $join->on('exp_lc_sc_pis.exp_pi_id','=','exp_pis.id');
        })
       ->when(request('pi_no'), function ($q) {
            return $q->where('exp_pis.pi_no', '=', request('pi_no', 0));
        })
       ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', '=', request('style_ref', 0));
        })
       ->when(request('job_no'), function ($q) {
            return $q->where('jobs.job_no', '=', request('job_no', 0));
        })
       ->when(request('order_no'), function ($q) {
            return $q->where('sales_orders.order_no', '=', request('order_no', 0));
        })
       ->where([['exp_pis.company_id','=',$explcsc->beneficiary_id]])
       ->where([['exp_pis.buyer_id','=',$explcsc->buyer_id]])
       ->groupBy([
        'exp_pis.id',
        'exp_pis.pi_no',
        'exp_lc_sc_pis.exp_pi_id'
       ])
       ->get();
       $notsaved = $exppi->filter(function ($value) {
            if(!$value->exp_pi_id){
                return $value;
            }
        })->values();
       echo json_encode($notsaved);
        
        
    }

}
