<?php

namespace App\Http\Controllers\Sample\Costing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sample\Costing\SmpCostEmbConRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostEmbRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Marketing\StyleSampleCsRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Sample\Costing\SmpCostEmbConRequest;
                                     

class SmpCostEmbConController extends Controller {

    private $smpcostembcon;
    private $smpcostemb;
  	private $smpcost;
    private $stylesamplecs;
  	private $stylegmtcolorsize;
    private $color;

    public function __construct(
        SmpCostEmbConRepository $smpcostembcon,
        SmpCostEmbRepository $smpcostemb,
        SmpCostRepository $smpcost,
        StyleSampleCsRepository $stylesamplecs,
        StyleGmtColorSizeRepository $stylegmtcolorsize,
        ColorRepository $color
    ) {
        $this->smpcostembcon = $smpcostembcon;
        $this->smpcostemb = $smpcostemb;
        $this->smpcost = $smpcost;
        $this->stylesamplecs = $stylesamplecs;
        $this->stylegmtcolorsize = $stylegmtcolorsize;
        $this->color = $color;
        $this->middleware('auth');
        // $this->middleware('permission:view.mktcostfabriccons',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.mktcostfabriccons', ['only' => ['store']]);
        // $this->middleware('permission:edit.mktcostfabriccons',   ['only' => ['update']]);
        // $this->middleware('permission:delete.mktcostfabriccons', ['only' => ['destroy']]);
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
		//$smpcosttrim=$this->smpcostfabric->find(request('smp_cost_trim_id',0));
		$emb=$this->smpcostemb
        ->join('smp_costs',function($join){
          $join->on('smp_costs.id','=','smp_cost_embs.smp_cost_id');
        })
        ->join('style_samples',function($join){
          $join->on('style_samples.id','=','smp_costs.style_sample_id');
        })
        ->join('style_sample_cs',function($join){
          $join->on('style_sample_cs.style_sample_id','=','style_samples.id');
        })
        ->join('style_gmt_color_sizes',function($join){
          $join->on('style_gmt_color_sizes.id','=','style_sample_cs.style_gmt_color_size_id');
        })
        ->join('style_sizes',function($join){
          $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
          $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('style_colors',function($join){
          $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        })
        ->leftJoin('colors',function($join){
          $join->on('colors.id','=','style_colors.color_id');
        })
        ->leftJoin('smp_cost_emb_cons',function($join){
          $join->on('smp_cost_embs.id','=','smp_cost_emb_cons.smp_cost_emb_id')
          ->on('style_sample_cs.id','=','smp_cost_emb_cons.style_sample_c_id')
          ->whereNull('smp_cost_emb_cons.deleted_at');
        })
        
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
        ->where([['smp_cost_embs.id','=',request('smp_cost_emb_id',0)]])
        ->get([
          'smp_cost_embs.smp_cost_id',
          'smp_cost_embs.id as smp_cost_emb_id',
          'style_sizes.id as style_size_id',
          'style_colors.id as style_color_id',
          'style_sample_cs.id as style_sample_c_id',
          'style_sample_cs.qty as plan_cut_qty',
          'sizes.name as size_name',
          'sizes.code as size_code',
          'colors.name as color_name',
          'colors.code as color_code',
          'style_sizes.sort_id as size_sort_id',
          'style_colors.sort_id as color_sort_id',
          'smp_cost_emb_cons.cons',
          'smp_cost_emb_cons.req_cons',
          'smp_cost_emb_cons.rate',
          'smp_cost_emb_cons.amount'
        ]);
        $dropdown['smpcostembscs'] = "'".Template::loadView('Sample.Costing.SmpCostEmbColorSizeMatrix',['colorsizes'=>$emb])."'";
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmpCostEmbConRequest $request) {
		$smp_cost_id=0;
		foreach($request->style_sample_c_id as $index=>$style_sample_c_id)
    {
			    $smp_cost_id=$request->smp_cost_id[$index];

				if($request->cons[$index]){
				$smpcostembcon = $this->smpcostembcon->updateOrCreate(
				['smp_cost_emb_id' => $request->smp_cost_emb_id[$index],'style_sample_c_id' => $style_sample_c_id],
				['cons' => $request->cons[$index],'req_cons' => $request->req_cons[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
				);
				}
			}
		return response()->json(array('success' => true, 'id' => $smpcostembcon->id, 'smp_cost_id' => $smp_cost_id, 'message' => 'Save Successfully'), 200);

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
    public function update(SmpCostFabricConRequest $request, $id) {
        
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
