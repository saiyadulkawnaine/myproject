<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostProfitRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Library\Template;
use App\Http\Requests\MktCostProfitRequest;

class MktCostProfitController extends Controller {

    private $mktcostprofit;
    private $mktcost;

    public function __construct(MktCostProfitRepository $mktcostprofit,MktCostRepository $mktcost) {
        $this->mktcostprofit = $mktcostprofit;
        $this->mktcost = $mktcost;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcostprofits',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcostprofits', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcostprofits',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcostprofits', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$tot=0;
        $mktcostprofits=array();
	    $rows=$this->mktcostprofit
		->join('mkt_costs',function($join){
			$join->on('mkt_costs.id','=','mkt_cost_profits.mkt_cost_id');
		})
		->where([['mkt_cost_id','=',request('mkt_cost_id',0)]])
		->get([
		'mkt_cost_profits.*',
		'mkt_costs.costing_unit_id',
		]);
  		foreach($rows as $row){
        $mktcostprofit['id']=	$row->id;
        $mktcostprofit['rate']=	$row->rate;
        $mktcostprofit['amount']=	$row->amount;
		$mktcostprofit['rate_pcs']=	number_format($row->rate/$row->costing_unit_id,4);
		$mktcostprofit['amount_pcs']=	number_format($row->amount/$row->costing_unit_id,4);
		$tot+=$row->amount;
  		   array_push($mktcostprofits,$mktcostprofit);
  		}
		$dd=array('total'=>1,'rows'=>$mktcostprofits,'footer'=>array(0=>array('id'=>'','rate'=>'Total','amount'=>$tot)));
        echo json_encode($dd);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
        return Template::loadView('Marketing.MktCostProfit', ['mktcost'=>$mktcost]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostProfitRequest $request) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcostprofit = $this->mktcostprofit->create($request->except(['id']));
		$totalPriceBeforeCommission=$this->mktcost->totalPriceBeforeCommission($request->mkt_cost_id);
        if ($mktcostprofit) {
            return response()->json(array('success' => true, 'id' => $mktcostprofit->id, 'message' => 'Save Successfully','price_before_commission' => $totalPriceBeforeCommission), 200);
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
        $mktcostprofit = $this->mktcostprofit->find($id);
        $row ['fromData'] = $mktcostprofit;
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
    public function update(MktCostProfitRequest $request, $id) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcostprofit = $this->mktcostprofit->update($id, $request->except(['id']));
		$totalPriceBeforeCommission=$this->mktcost->totalPriceBeforeCommission($request->mkt_cost_id);
        if ($mktcostprofit) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully','price_before_commission' => $totalPriceBeforeCommission), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $mktcostprofit=$this->mktcostprofit->find($id);
        $approved=$this->mktcost->find($mktcostprofit->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        if ($this->mktcostprofit->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
