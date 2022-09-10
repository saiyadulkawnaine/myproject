<?php

namespace App\Http\Controllers\Sample\Costing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricConRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Marketing\StyleSampleCsRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Library\Template;
use App\Http\Requests\Sample\Costing\SmpCostFabricConRequest;

class SmpCostFabricConController extends Controller {

    private $smpcostfabriccon;
    private $smpcostfabric;
  	private $smpcost;
    private $stylesamplecs;
  	private $stylegmtcolorsize;
    private $color;
    private $keycontrol;

    public function __construct(
        SmpCostFabricConRepository $smpcostfabriccon,
        SmpCostFabricRepository $smpcostfabric,
        SmpCostRepository $smpcost,
        StyleSampleCsRepository $stylesamplecs,
        StyleGmtColorSizeRepository $stylegmtcolorsize,
        ColorRepository $color,
        KeycontrolRepository $keycontrol
    ) {
        $this->smpcostfabriccon = $smpcostfabriccon;
        $this->smpcostfabric = $smpcostfabric;
        $this->smpcost = $smpcost;
        $this->stylesamplecs = $stylesamplecs;
        $this->stylegmtcolorsize = $stylegmtcolorsize;
        $this->color = $color;
        $this->keycontrol = $keycontrol;
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

        $smpcostfabric=$this->smpcostfabric->find(request('smp_cost_fabric_id',0));
        $smpcost=$this->smpcost->find($smpcostfabric->smp_cost_id);

        $keycontrol=$this->keycontrol
        ->join('keycontrol_parameters', function($join)  {
        $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
        })
        ->where([['parameter_id','=',12]])
        ->where([['keycontrols.company_id','=',$smpcost->company_id]])
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$smpcost->costing_date])
        ->get([
        'keycontrol_parameters.value'
        ])
        ->first();

        $unlayablePer=0;
        if($keycontrol){
        $unlayablePer=$keycontrol->value;
        }


        $gmtsparts_type=$this->smpcostfabric
        ->join('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','smp_cost_fabrics.style_fabrication_id');
        })
        ->join('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->where([['smp_cost_fabrics.id','=',request('smp_cost_fabric_id',0)]])
        ->get(['gmtsparts.part_type_id'])->first();
        $fabric=$this->smpcostfabric
        ->join('smp_costs',function($join){
        $join->on('smp_costs.id','=','smp_cost_fabrics.smp_cost_id');
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


        ->leftJoin('cads',function($join){
        $join->on('cads.style_id','=','style_samples.style_id');
        })
        ->leftJoin('cad_cons',function($join){
        $join->on('cad_cons.cad_id','=','cads.id');
        $join->on('cad_cons.style_fabrication_id','=','smp_cost_fabrics.style_fabrication_id');
        $join->on('cad_cons.style_gmt_color_size_id','=','style_gmt_color_sizes.id');
        })

        ->leftJoin('smp_cost_fabric_cons',function($join){
        $join->on('smp_cost_fabrics.id','=','smp_cost_fabric_cons.smp_cost_fabric_id')
        ->on('style_sample_cs.id','=','smp_cost_fabric_cons.style_sample_c_id')
        ->whereNull('smp_cost_fabric_cons.deleted_at');
        })
        ->leftJoin('colors as fabric_colors',function($join){
        $join->on('fabric_colors.id','=','smp_cost_fabric_cons.fabric_color');
        })
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
        ->where([['smp_cost_fabrics.id','=',request('smp_cost_fabric_id',0)]])
        ->get([
        'smp_cost_fabrics.smp_cost_id',
        'smp_cost_fabrics.id as smp_cost_fabric_id',
        'style_sizes.id as style_size_id',
        'style_colors.id as style_color_id',
        'style_sample_cs.id as style_sample_c_id',
        'style_sample_cs.qty',
        'sizes.name as size_name',
        'sizes.code as size_code',
        'colors.name as color_name',
        'colors.code as color_code',
        'style_sizes.sort_id as size_sort_id',
        'style_colors.sort_id as color_sort_id',
        'smp_cost_fabric_cons.dia',
        'fabric_colors.name as fabric_color',
        'smp_cost_fabric_cons.cons',
        'smp_cost_fabric_cons.fin_fab',
        'smp_cost_fabric_cons.process_loss',
        'smp_cost_fabric_cons.req_cons',
        'smp_cost_fabric_cons.grey_fab',
        'smp_cost_fabric_cons.rate',
        'smp_cost_fabric_cons.amount',
        'smp_cost_fabric_cons.body_lenght',
        'smp_cost_fabric_cons.body_sewing_margin',
        'smp_cost_fabric_cons.body_hem_margin',
        'smp_cost_fabric_cons.sleeve_lenght',
        'smp_cost_fabric_cons.sleeve_sewing_margin',
        'smp_cost_fabric_cons.sleeve_hem_margin',
        'smp_cost_fabric_cons.chest_lenght',
        'smp_cost_fabric_cons.chest_sewing_margin',
        'smp_cost_fabric_cons.frontraise_lenght',
        'smp_cost_fabric_cons.frontraise_sewing_margin',
        'smp_cost_fabric_cons.westband_lenght',
        'smp_cost_fabric_cons.westband_sewing_margin',
        'smp_cost_fabric_cons.inseam_lenght',
        'smp_cost_fabric_cons.inseam_sewing_margin',
        'smp_cost_fabric_cons.inseam_hem_margin',
        'smp_cost_fabric_cons.thai_lenght',
        'smp_cost_fabric_cons.thai_sewing_margin',
        'smp_cost_fabric_cons.unlayable_per',
        'cad_cons.cons as cad_cons'
        ]);
        $dropdown['scs'] = "'".Template::loadView('Sample.Costing.SmpCostFabricColorSizeMatrix',['colorsizes'=>$fabric,'gmtsparts_type'=>$gmtsparts_type->part_type_id,'gsm'=>$smpcostfabric->gsm_weight,'unlayablePer'=>$unlayablePer])."'";
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmpCostFabricConRequest $request) {
		$smp_cost_id=0;
		foreach($request->style_color_id as $index=>$style_color_id){
			    $smp_cost_id=$request->smp_cost_id[$index];
          $color = $this->color->firstOrCreate(['name' => $request->fabric_color[$index]],['code' => '']);

				if($request->cons[$index]){
				$smpcostfabriccon = $this->smpcostfabriccon->updateOrCreate(
				['smp_cost_fabric_id' => $request->smp_cost_fabric_id[$index],'style_sample_c_id' => $request->style_sample_c_id[$index]],
				['dia' => $request->dia[$index],'fabric_color' => $color->id,'cons' => $request->cons[$index],'fin_fab' => $request->fin_fab[$index],'process_loss' => $request->process_loss[$index],'req_cons' => $request->req_cons[$index],'grey_fab' => $request->grey_fab[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index],'unlayable_per' =>$request->unlayable_per[$index],'body_lenght' =>$request->body_lenght[$index],'body_sewing_margin' =>$request->body_sewing_margin[$index],'body_hem_margin' =>$request->body_hem_margin[$index],'sleeve_lenght' =>$request->sleeve_lenght[$index],'sleeve_sewing_margin' =>$request->sleeve_sewing_margin[$index],'sleeve_hem_margin' =>$request->sleeve_hem_margin[$index],'chest_lenght' =>$request->chest_lenght[$index],'chest_sewing_margin' =>$request->chest_sewing_margin[$index],'frontraise_lenght' =>$request->frontraise_lenght[$index],'frontraise_sewing_margin' =>$request->frontraise_sewing_margin[$index],'westband_lenght' =>$request->westband_lenght[$index],'westband_sewing_margin' =>$request->westband_sewing_margin[$index],'inseam_lenght' =>$request->inseam_lenght[$index],'inseam_sewing_margin' =>$request->inseam_sewing_margin[$index],'inseam_hem_margin' =>$request->inseam_hem_margin[$index],'thai_lenght' =>$request->thai_lenght[$index],'thai_sewing_margin' =>$request->thai_sewing_margin[$index]]
				);
				}
			}
		return response()->json(array('success' => true, 'id' => $smpcostfabriccon->id, 'smp_cost_id' => $smp_cost_id, 'message' => 'Save Successfully'), 200);

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
