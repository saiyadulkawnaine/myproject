<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostEmbRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\WashChargeRepository;
use App\Repositories\Contracts\Marketing\StyleEmbelishmentRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Library\Template;
use App\Http\Requests\MktCostEmbRequest;

class MktCostEmbController extends Controller {

    private $mktcostemb;
    private $mktcost;
    private $embelishmenttype;
    private $embelishment;
	private $washcharge;
	private $styleembelishment;
	private $productionprocess;

    public function __construct(MktCostEmbRepository $mktcostemb,MktCostRepository $mktcost,EmbelishmentTypeRepository $embelishmenttype,EmbelishmentRepository $embelishment, WashChargeRepository $washcharge,StyleEmbelishmentRepository $styleembelishment,ProductionProcessRepository $productionprocess) {
        $this->mktcostemb = $mktcostemb;
        $this->mktcost = $mktcost;
        $this->embelishmenttype = $embelishmenttype;
        $this->embelishment = $embelishment;
		$this->washcharge = $washcharge;
		$this->styleembelishment = $styleembelishment;
		$this->productionprocess = $productionprocess;
		
        $this->middleware('auth');
        $this->middleware('permission:view.mktcostembs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcostembs', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcostembs',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcostembs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
      $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'-Select-','');
      $embelishmenttype=array_prepend(array_pluck($this->$embelishmenttype->get(),'name','id'),'-Select-','');
      $mktcostembs=array();
	    $rows=$this->mktcostemb
		->join('embelishments',function($join){
			$join->on('embelishments.id','=','mkt_cost_embs.embelishment_id');
			})
		->join('embelishment_types',function($join){
			$join->on('embelishment_types.id','=','mkt_cost_embs.embelishment_type_id');
		})
		->get([
		'mkt_cost_embs.*',
		'embelishments.name as emb_name',
		'embelishment_types.name as emb_type',
		]);
  		foreach($rows as $row){
        $mktcostemb['id']=	$row->id;
        $mktcostemb['cons']=	$row->cons;
        $mktcostemb['rate']=	$row->rate;
        $mktcostemb['amount']=	$row->amount;
        $mktcostemb['mktcost']=	$mktcost[$row->mkt_cost_id];
        $mktcostemb['embelishment']=	$uom[$row->embelishment_id];
        $mktcostemb['embelishmenttype']=	$uom[$row->embelishment_type_id];
  		   array_push($mktcostembs,$mktcostemb);
  		}
        echo json_encode($mktcostembs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
      $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'-Select-','');
      $embelishmenttype=array_prepend(array_pluck($this->$embelishmenttype->get(),'name','id'),'-Select-','');
        return Template::loadView('Marketing.MktCostEmb', ['mktcost'=>$mktcost,'embelishment'=>$embelishment,'embelishmenttype'=>$embelishmenttype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostEmbRequest $request) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
		$mktCostId=$request->mkt_cost_id;
		foreach($request->style_embelishment_id as $index=>$style_embelishment_id){
			    //$mktCostId=$mkt_cost_id;
				if($request->cons[$index]){
				$mktcostemb = $this->mktcostemb->updateOrCreate([
                    'mkt_cost_id' => $mktCostId,
                    'style_embelishment_id' => $request->style_embelishment_id[$index],
                ],[
                    'cons' => $request->cons[$index],
                    'rate' => $request->rate[$index],
                    'amount' =>$request->amount[$index],
                ]);
				}
			}
			$totalCost=$this->mktcost->totalCost($mktCostId);
		    return response()->json(array('success' => true, 'id' => $mktcostemb->id, 'mkt_cost_id' => $mktCostId,'message' => 'Save Successfully','totalcost' => $totalCost), 200);
        
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
        $mktcostemb = $this->mktcostemb->find($id);
        $row ['fromData'] = $mktcostemb;
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
    public function update(MktCostEmbRequest $request, $id) {
        /*$mktcostemb = $this->mktcostemb->update($id, $request->except(['id']));
        if ($mktcostemb) {
            return response()->json(array('success' => true, 'id' => $id, 'mkt_cost_id' => $mktCostId, 'message' => 'Update Successfully'), 200);
        }*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        /*if ($this->mktcostemb->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }*/
    }
	
	public function getrate(){
		$styleembelishment=$this->styleembelishment->find(request('style_embelishment_id',0));
		$embelishment=$this->embelishment->find($styleembelishment->embelishment_id);
		$productionprocess=$this->productionprocess->find($embelishment->production_process_id);
		if($productionprocess->production_area_id==45 || $productionprocess->production_area_id==50){
			$row=$this->washcharge
			->where([['embelishment_id','=',$styleembelishment->embelishment_id]])
			->where([['embelishment_type_id','=',$styleembelishment->embelishment_type_id]])
			->where([['embelishment_size_id','=',$styleembelishment->embelishment_size_id]])
			->get()
			->first();

		}
		else{
			$row=$this->washcharge
			->where([['embelishment_id','=',$styleembelishment->embelishment_id]])
			->where([['embelishment_type_id','=',$styleembelishment->embelishment_type_id]])
			->get()
			->first();

		}
		
		 echo json_encode($row);
	}

}
