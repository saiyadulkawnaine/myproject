<?php

namespace App\Http\Controllers\Sample\Costing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sample\Costing\SmpCostTrimConRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostTrimRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Marketing\StyleSampleCsRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Sample\Costing\SmpCostTrimConRequest;
                                     

class SmpCostTrimConController extends Controller {

    private $smpcosttrimcon;
    private $smpcosttrim;
  	private $smpcost;
    private $stylesamplecs;
  	private $stylegmtcolorsize;
    private $color;

    public function __construct(
        SmpCostTrimConRepository $smpcosttrimcon,
        SmpCostTrimRepository $smpcosttrim,
        SmpCostRepository $smpcost,
        StyleSampleCsRepository $stylesamplecs,
        StyleGmtColorSizeRepository $stylegmtcolorsize,
        ColorRepository $color
    ) {
        $this->smpcosttrimcon = $smpcosttrimcon;
        $this->smpcosttrim = $smpcosttrim;
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
		$trim=$this->smpcosttrim
        ->join('smp_costs',function($join){
          $join->on('smp_costs.id','=','smp_cost_trims.smp_cost_id');
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
        ->leftJoin('smp_cost_trim_cons',function($join){
          $join->on('smp_cost_trims.id','=','smp_cost_trim_cons.smp_cost_trim_id')
          ->on('style_sample_cs.id','=','smp_cost_trim_cons.style_sample_c_id')
          ->whereNull('smp_cost_trim_cons.deleted_at');
        })
        ->leftJoin('colors as trim_colors',function($join){
          $join->on('trim_colors.id','=','smp_cost_trim_cons.trim_color');
        })
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
        ->where([['smp_cost_trims.id','=',request('smp_cost_trim_id',0)]])
        ->get([
          'smp_cost_trims.smp_cost_id',
          'smp_cost_trims.id as smp_cost_trim_id',
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
          'trim_colors.name as trim_color',
          'smp_cost_trim_cons.measurment',
          'smp_cost_trim_cons.cons',
          'smp_cost_trim_cons.req_trim',
          'smp_cost_trim_cons.process_loss',
          'smp_cost_trim_cons.req_cons',
          'smp_cost_trim_cons.bom_trim',
          'smp_cost_trim_cons.rate',
          'smp_cost_trim_cons.amount'
        ]);
        $dropdown['smpcosttrimscs'] = "'".Template::loadView('Sample.Costing.SmpCostTrimColorSizeMatrix',['colorsizes'=>$trim])."'";
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmpCostTrimConRequest $request) {
		$smp_cost_id=0;
		foreach($request->style_sample_c_id as $index=>$style_sample_c_id)
    {
			    $smp_cost_id=$request->smp_cost_id[$index];
          $color = $this->color->firstOrCreate(['name' => $request->trim_color[$index]],['code' => '']);

				if($request->cons[$index]){
				$smpcosttrimcon = $this->smpcosttrimcon->updateOrCreate(
				['smp_cost_trim_id' => $request->smp_cost_trim_id[$index],'style_sample_c_id' => $style_sample_c_id],
				['measurment' => $request->measurment[$index],'trim_color' => $color->id,'cons' => $request->cons[$index],'req_trim' => $request->req_trim[$index],'process_loss' => $request->process_loss[$index],'req_cons' => $request->req_cons[$index],'bom_trim' => $request->bom_trim[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
				);
				}
			}
		return response()->json(array('success' => true, 'id' => $smpcosttrimcon->id, 'smp_cost_id' => $smp_cost_id, 'message' => 'Save Successfully'), 200);

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
