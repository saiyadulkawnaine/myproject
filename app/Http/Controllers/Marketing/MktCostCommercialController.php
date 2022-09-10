<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostCommercialRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Library\Template;
use App\Http\Requests\MktCostCommercialRequest;

class MktCostCommercialController extends Controller {

    private $mktcostcommercial;
    private $mktcost;

    public function __construct(MktCostCommercialRepository $mktcostcommercial,MktCostRepository $mktcost) {
        $this->mktcostcommercial = $mktcostcommercial;
        $this->mktcost = $mktcost;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcostcommercials',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcostcommercials', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcostcommercials',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcostcommercials', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
      $mktcostcommercials=array();
	    $rows=$this->mktcostcommercial->where([['mkt_cost_id','=',request('mkt_cost_id',0)]])->get();
  		foreach($rows as $row){
        $mktcostcommercial['id']=	$row->id;
        $mktcostcommercial['rate']=	$row->rate;
        $mktcostcommercial['amount']=	$row->amount;
  		array_push($mktcostcommercials,$mktcostcommercial);
  		}
        echo json_encode($mktcostcommercials);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
        return Template::loadView('Marketing.MktCostCommercial', ['mktcost'=>$mktcost]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostCommercialRequest $request) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
		$cost=$this->mktcost->totalFabricCost($request->mkt_cost_id)+$this->mktcost->totalYarnCost($request->mkt_cost_id)+$this->mktcost->totalFabricProdCost($request->mkt_cost_id)+$this->mktcost->totalTrimCost($request->mkt_cost_id)+$this->mktcost->totalEmbCost($request->mkt_cost_id);
		$amount=($request->rate/100)*$cost;
		//$request->amount=$amount;
	    $mktcostcommercial = $this->mktcostcommercial->create(["mkt_cost_id"=>$request->mkt_cost_id,"rate"=>$request->rate,"amount"=>$amount]);
		$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
        if ($mktcostcommercial) {
            return response()->json(array('success' => true, 'id' => $mktcostcommercial->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        $mktcostcommercial = $this->mktcostcommercial->find($id);
        $row ['fromData'] = $mktcostcommercial;
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
    public function update(MktCostCommercialRequest $request, $id) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
		$cost=$this->mktcost->totalFabricCost($request->mkt_cost_id)+$this->mktcost->totalYarnCost($request->mkt_cost_id)+$this->mktcost->totalFabricProdCost(        $request->mkt_cost_id)+$this->mktcost->totalTrimCost($request->mkt_cost_id)+$this->mktcost->totalEmbCost($request->mkt_cost_id);
		$amount=($request->rate/100)*$cost;
		$request->amount=$amount;
        $mktcostcommercial = $this->mktcostcommercial->update($id, ["mkt_cost_id"=>$request->mkt_cost_id,"rate"=>$request->rate,"amount"=>$amount]);
		$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
        if ($mktcostcommercial) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully','totalcost' => $totalCost), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $mktcostcommercial=$this->mktcostcommercial->find($id);
        $approved=$this->mktcost->find($mktcostcommercial->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        if ($this->mktcostcommercial->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }



}
