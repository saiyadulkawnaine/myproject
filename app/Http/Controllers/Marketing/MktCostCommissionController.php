<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostCommissionRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Library\Template;
use App\Http\Requests\MktCostCommissionRequest;

class MktCostCommissionController extends Controller {

    private $mktcostcommission ;
    private $mktcost;

    public function __construct(MktCostCommissionRepository $mktcostcommission,MktCostRepository $mktcost) {
        $this->mktcostcommission  = $mktcostcommission ;
        $this->mktcost = $mktcost;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcostcommissions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcostcommissions', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcostcommissions',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcostcommissions', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      //$mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
	  $commissionfor=array_prepend([1=>"Local Agent",2=>"Foreign Agent"],'-Select-','');
      $mktcostcommissions=array();
	    $rows=$this->mktcostcommission
		->join('mkt_costs',function($join){
			$join->on('mkt_costs.id','=','mkt_cost_commissions.mkt_cost_id');
		})
		->where([['mkt_cost_id','=',request('mkt_cost_id',0)]])
		->get([
		'mkt_cost_commissions.*',
		'mkt_costs.costing_unit_id',
		]);
		$tot=0;
		$tot_pcs=0;
  		foreach($rows as $row){
        $mktcostcommission ['id']=	$row->id;
         $mktcostcommission ['for_id']=	$commissionfor[$row->for_id];
         $mktcostcommission ['rate']=	$row->rate;
         $mktcostcommission ['amount']=	$row->amount;
		 $mktcostcommission['rate_pcs']=	number_format($row->rate/$row->costing_unit_id,4);
		$mktcostcommission['amount_pcs']=	number_format($row->amount/$row->costing_unit_id,4);
		 $tot+=$row->amount;
		 $tot_pcs+=number_format($row->amount/$row->costing_unit_id,4);
  		   array_push($mktcostcommissions ,$mktcostcommission );
  		}
		$dd=array('total'=>1,'rows'=>$mktcostcommissions,'footer'=>array(0=>array('id'=>'','for_id'=>'','rate'=>'Total','amount'=>$tot,'rate'=>'','amount_pcs'=>$tot_pcs)));
        echo json_encode($dd);
        //echo json_encode($mktcostcommissions );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
        return Template::loadView('Marketing.MktCostCommission', ['mktcost'=>$mktcost]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostCommissionRequest $request) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcostcommission  = $this->mktcostcommission ->create($request->except(['id']));
		$price_after_commission=$this->mktcost->totalPriceAfterCommission($request->mkt_cost_id);
        if ($mktcostcommission ) {
            return response()->json(array('success' => true, 'id' => $mktcostcommission ->id, 'message' => 'Save Successfully','price_after_commission'=>$price_after_commission), 200);
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
        $mktcostcommission  = $this->mktcostcommission ->find($id);
        $row ['fromData'] = $mktcostcommission ;
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
    public function update(MktCostCommissionRequest $request, $id) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcostcommission  = $this->mktcostcommission ->update($id, $request->except(['id']));
		$price_after_commission=$this->mktcost->totalPriceAfterCommission($request->mkt_cost_id);
        if ($mktcostcommission ) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully','price_after_commission'=>$price_after_commission), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $mktcostcommission=$this->mktcostcommission->find($id);
        $approved=$this->mktcost->find($mktcostcommission->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        if ($this->mktcostcommission ->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
