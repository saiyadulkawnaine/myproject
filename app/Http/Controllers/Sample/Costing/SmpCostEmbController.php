<?php

namespace App\Http\Controllers\Sample\Costing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sample\Costing\SmpCostEmbRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\WashChargeRepository;
use App\Repositories\Contracts\Marketing\StyleEmbelishmentRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Library\Template;
use App\Http\Requests\Sample\Costing\SmpCostEmbRequest;

class SmpCostEmbController extends Controller {

    private $smpcostemb;
    private $smpcost;
    private $embelishmenttype;
    private $embelishment;
	private $washcharge;
	private $styleembelishment;
	private $productionprocess;

    public function __construct(SmpCostEmbRepository $smpcostemb,SmpCostRepository $smpcost,EmbelishmentTypeRepository $embelishmenttype,EmbelishmentRepository $embelishment, WashChargeRepository $washcharge,StyleEmbelishmentRepository $styleembelishment,ProductionProcessRepository $productionprocess) {
        $this->smpcostemb = $smpcostemb;
        $this->smpcost = $smpcost;
        $this->embelishmenttype = $embelishmenttype;
        $this->embelishment = $embelishment;
		$this->washcharge = $washcharge;
		$this->styleembelishment = $styleembelishment;
		$this->productionprocess = $productionprocess;
		
        $this->middleware('auth');
       /*  $this->middleware('permission:view.smpcostembs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.smpcostembs', ['only' => ['store']]);
        $this->middleware('permission:edit.smpcostembs',   ['only' => ['update']]);
        $this->middleware('permission:delete.smpcostembs', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $smpcost = $this->smpcost
        ->leftJoin('style_samples',function($join){
        $join->on('style_samples.id','=','smp_costs.style_sample_id');
        })
        ->leftJoin('style_gmts', function($join)  {
        $join->on('style_gmts.id', '=', 'style_samples.style_gmt_id');
        })
        ->join('styles',function($join){
            $join->on('styles.id','=','style_gmts.style_id');
        })
        ->where([['smp_costs.id','=',request('smp_cost_id',0)]])
        ->get([
        'smp_costs.*',
        'styles.style_ref',
        'styles.buyer_id',
        'styles.uom_id',
        'styles.team_id'
        ])->first();


        $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
        $emb=$this->smpcost
        ->selectRaw(
        'smp_costs.id as smp_cost_id,
        smp_costs.costing_unit_id,
        style_embelishments.id as style_embelishment_id,
        style_embelishments.embelishment_id,
        style_embelishments.embelishment_type_id,
        style_embelishments.embelishment_size_id,
        embelishments.name as embelishment_name,
        embelishment_types.name as embelishment_type,
        item_accounts.item_description,
        smp_cost_embs.id,
        smp_cost_embs.cons,
        smp_cost_embs.rate,
        smp_cost_embs.amount,
        sum(style_sample_cs.qty) as qty
        '
        )
        ->leftJoin('style_samples',function($join){
        $join->on('style_samples.id','=','smp_costs.style_sample_id');
        })
        ->leftJoin('style_sample_cs', function($join) {
        $join->on('style_sample_cs.style_sample_id', '=', 'style_samples.id');
        })
        ->leftJoin('style_gmts', function($join)  {
        $join->on('style_gmts.id', '=', 'style_samples.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('style_embelishments',function($join){
        $join->on('style_embelishments.style_gmt_id','=','style_gmts.id');
        })
        ->join('embelishments',function($join){
        $join->on('embelishments.id','=','style_embelishments.embelishment_id');
        })
        ->join('embelishment_types',function($join){
        $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
        })
        ->join('styles',function($join){
        $join->on('styles.id','=','style_gmts.style_id');
        })
        ->leftJoin('smp_cost_embs',function($join){
        $join->on('smp_cost_embs.smp_cost_id','=','smp_costs.id');
        $join->on('smp_cost_embs.style_embelishment_id','=','style_embelishments.id');
        })
        ->where([['smp_costs.id','=',request('smp_cost_id',0)]])
        ->groupBy([
        'smp_costs.id',
        'smp_costs.costing_unit_id',
        'style_embelishments.id',
        'style_embelishments.embelishment_id',
        'style_embelishments.embelishment_type_id',
        'style_embelishments.embelishment_size_id',
        'embelishments.name',
        'embelishment_types.name',
        'item_accounts.item_description',
        'smp_cost_embs.id',
        'smp_cost_embs.cons',
        'smp_cost_embs.rate',
        'smp_cost_embs.amount',
        ])
        ->get()
        ->map(function ($emb) use ($embelishmentsize) {
        $emb->embelishment_size_name=$embelishmentsize[$emb->embelishment_size_id];
        return $emb ;
        });
        $dropdown['emb'] = "'".Template::loadView('Sample.Costing.SmpCostEmbMatrix',['embs'=>$emb,'costing_unit_id'=>$smpcost->costing_unit_id])."'";
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmpCostEmbRequest $request) {
        $smpCostId=0;
        foreach($request->smp_cost_id as $index=>$smp_cost_id){
        $smpCostId=$smp_cost_id;
        if($request->cons[$index]){
        $smpcostemb = $this->smpcostemb->updateOrCreate(
        ['smp_cost_id' => $smp_cost_id,'style_embelishment_id' => $request->style_embelishment_id[$index]],
        ['cons' => $request->cons[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
        );
        }
        }
        //$totalCost=$this->smpcost->totalCost($smpCostId);
        return response()->json(array('success' => true, 'id' => $smpcostemb->id, 'smp_cost_id' => $smpCostId,'message' => 'Save Successfully'), 200);
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SmpCostEmbRequest $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }
}
