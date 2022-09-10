<?php

namespace App\Http\Controllers\Sample\Costing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sample\Costing\SmpCostCmRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Library\Template;
use App\Http\Requests\Sample\Costing\SmpCostCmRequest;

class SmpCostCmController extends Controller {

    private $smpcostcm;
    private $smpcost;
	private $keycontrol;

    public function __construct(
    	SmpCostCmRepository $smpcostcm,
    	SmpCostRepository $smpcost,
    	KeycontrolRepository $keycontrol) {
        $this->smpcostcm = $smpcostcm;
        $this->smpcost = $smpcost;
		$this->keycontrol = $keycontrol;
        $this->middleware('auth');
        //$this->middleware('permission:view.smpcostcms',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.smpcostcms', ['only' => ['store']]);
        //$this->middleware('permission:edit.smpcostcms',   ['only' => ['update']]);
        //$this->middleware('permission:delete.smpcostcms', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $smpcost=array_prepend(array_pluck($this->smpcost->get(),'name','id'),'-Select-','');
      $smpcostcms=array();
	    $rows=$this->smpcostcm->where([['smp_cost_id','=',request('smp_cost_id',0)]])->get();
  		foreach($rows as $row){
         $smpcostcm['id']=	$row->id;
         $smpcostcm['method_id']=	$row->method_id;
         $smpcostcm['amount']=	$row->amount;
         $smpcostcm['bom_amount']=	$row->bom_amount;
  		 array_push($smpcostcms,$smpcostcm);
  		}
        echo json_encode($smpcostcms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $smpcost=array_prepend(array_pluck($this->smpcost->get(),'name','id'),'-Select-','');
        return Template::loadView('Marketing.SmpCostCm', ['smpcost'=>$smpcost]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmpCostCmRequest $request) {
		$smpcost=$this->smpcost->find($request->smp_cost_id);
		
		$keycontrol=$this->keycontrol
		->join('keycontrol_parameters', function($join)  {
		$join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
		})
		->where([['parameter_id','=',4]])
		->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$smpcost->costing_date])
		->get([
		'keycontrol_parameters.value'
		])->first();
		if(!$keycontrol->value){
			return response()->json(array('success' => false, 'message' => 'CPM Not found'), 200);
		}
		
		
		$smvrows=$this->smpcost
		->join('style_samples',function($join){
         $join->on('style_samples.id','=','smp_costs.style_sample_id');
        })
	    ->join('style_gmts',function($join){
         $join->on('style_gmts.id','=','style_samples.style_gmt_id');
        })
		->where([['smp_costs.id','=',$smpcost->id]])
		->get([
		'style_gmts.smv',
		'style_gmts.sewing_effi_per'
		]);


		
		if(!$smvrows->count()){
			return response()->json(array('success' => false, 'message' => 'GMT SMV and Efficiency % not found'), 200);
		}
		$amount=0;
		foreach($smvrows as $smvrow){
			$amount+=($smvrow->smv*$keycontrol->value*$smpcost->costing_unit_id)/($smvrow->sewing_effi_per/100);
		}
		
		if($amount===0){
			return response()->json(array('success' => false, 'message' => 'GMT SMV and Efficiency % not found'), 200);
		}
		$bom_amount=$request->qty/$smpcost->costing_unit_id*$amount;
		//$amount=$request->amount;
		//$bom_amount=$request->bom_amount;
		$amount=$amount;
		$bom_amount=$bom_amount;
		$smpcostcm = $this->smpcostcm->updateOrCreate(['smp_cost_id'=>$request->smp_cost_id],['method_id'=>$request->method_id,'amount'=>$amount,'bom_amount'=>$bom_amount]);
		
		//$totalCost=$this->smpcost->totalCost($request->smp_cost_id);
		//$priceBfrCommission=$this->smpcost->totalPriceBeforeCommission($request->smp_cost_id);
        if ($smpcostcm) {
            return response()->json(array('success' => true, 'id' => $smpcostcm->id, 'message' => 'Save Successfully'), 200);
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
        $smpcostcm = $this->smpcostcm->find($id);
        $row ['fromData'] = $smpcostcm;
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
    public function update(SmpCostCmRequest $request, $id) {
		
		$smpcost=$this->smpcost->find($request->smp_cost_id);
		$keycontrol=$this->keycontrol
		->join('keycontrol_parameters', function($join)  {
		$join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
		})
		->where([['parameter_id','=',4]])
		->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$smpcost->costing_date])
		->get([
		'keycontrol_parameters.value'
		])->first();
		if(!$keycontrol->value){
			return response()->json(array('success' => false, 'message' => 'CPM Not found'), 200);
		}
		
		
		$smvrows=$this->smpcost
		->join('style_samples',function($join){
         $join->on('style_samples.id','=','smp_costs.style_sample_id');
        })
	    ->join('style_gmts',function($join){
         $join->on('style_gmts.id','=','style_samples.style_gmt_id');
        })
		->where([['smp_costs.id','=',$smpcost->id]])
		->get([
		'style_gmts.smv',
		'style_gmts.sewing_effi_per'
		]);
		
		if(!$smvrows->count()){
			return response()->json(array('success' => false, 'message' => 'GMT SMV and Efficiency % not found'), 200);
		}
		$amount=0;
		foreach($smvrows as $smvrow){
			$amount+=($smvrow->smv*$keycontrol->value*$smpcost->costing_unit_id)/($smvrow->sewing_effi_per/100);
		}
		
		if($amount===0){
			return response()->json(array('success' => false, 'message' => 'GMT SMV and Efficiency % not found'), 200);
		}
		
		$bom_amount=$request->qty/$smpcost->costing_unit_id*$amount;
		//$amount=$request->amount;
		//$bom_amount=$request->bom_amount;
		$amount=$amount;
		$bom_amount=$bom_amount;
        $smpcostcm = $this->smpcostcm->update($id, ['amount'=>$amount,'bom_amount'=>$bom_amount]);
		//$totalCost=$this->smpcost->totalCost($request->smp_cost_id);
        if ($smpcostcm) {
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
        if ($this->smpcostcm->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
