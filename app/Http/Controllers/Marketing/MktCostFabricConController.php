<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostFabricConRepository;
use App\Repositories\Contracts\Marketing\MktCostFabricRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Library\Template;
use App\Http\Requests\MktCostFabricConRequest;

class MktCostFabricConController extends Controller {

    private $mktcostfabriccon;
    private $mktcostfabric;
  	private $mktcost;
  	private $stylegmtcolorsize;

    public function __construct(MktCostFabricConRepository $mktcostfabriccon,MktCostFabricRepository $mktcostfabric,MktCostRepository $mktcost,StyleGmtColorSizeRepository $stylegmtcolorsize) {
        $this->mktcostfabriccon = $mktcostfabriccon;
        $this->mktcostfabric = $mktcostfabric;
    	$this->mktcost = $mktcost;
    	$this->stylegmtcolorsize = $stylegmtcolorsize;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcostfabriccons',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcostfabriccons', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcostfabriccons',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcostfabriccons', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $mktcostfabric=array_prepend(array_pluck($this->mktcostfabric->get(),'name','id'),'-Select-','');
      $mktcostfabriccons=array();
	    $rows=$this->mktcostfabriccon->get();
  		foreach($rows as $row){
        $mktcostfabriccon['id']=	$row->id;
        // $mktcostfabriccon['process_id']=	$row->process_id;
        // $mktcostfabriccon['cons']=	$row->cons;
        // $mktcostfabriccon['rate']=	$row->rate;
        // $mktcostfabriccon['amount']=	$row->amount;
        $mktcostfabriccon['mktcostfabric']=	$mktcostfabric[$row->mkt_cost_fabric_id];
  		   array_push($mktcostfabriccons,$mktcostfabriccon);
  		}
        echo json_encode($mktcostfabriccons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$mkt_cost_fabric=$this->mktcostfabric->find(request('mkt_cost_fabric_id',0));
		$gmtsparts_type=$this->mktcostfabric
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','mkt_cost_fabrics.style_fabrication_id');
		})
		->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
		})
		->where([['mkt_cost_fabrics.id','=',request('mkt_cost_fabric_id',0)]])
		->get(['gmtsparts.part_type_id'])->first();
		
		
		
		$fabric=$this->stylegmtcolorsize
		->join('style_fabrications',function($join){
          $join->on('style_fabrications.style_gmt_id','=','style_gmt_color_sizes.style_gmt_id');
        })
		->join('mkt_cost_fabrics',function($join){
          $join->on('mkt_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
        })
		->leftJoin('mkt_cost_fabric_cons',function($join){
		  $join->on('mkt_cost_fabrics.id','=','mkt_cost_fabric_cons.mkt_cost_fabric_id')
		  //->on('style_gmt_color_sizes.style_color_id','=','mkt_cost_fabric_cons.style_color_id')
		  //->on('style_gmt_color_sizes.style_size_id','=','mkt_cost_fabric_cons.style_size_id')
		  ->on('style_gmt_color_sizes.id','=','mkt_cost_fabric_cons.style_gmt_color_size_id')
		  ->whereNull('mkt_cost_fabric_cons.deleted_at');
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
		->join('colors',function($join){
          $join->on('colors.id','=','style_colors.color_id');
        })
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
        ->where([['mkt_cost_fabrics.id','=',request('mkt_cost_fabric_id',0)]])
        ->get([
          'mkt_cost_fabrics.mkt_cost_id',
          'mkt_cost_fabrics.id as mkt_cost_fabric_id',
		  'style_sizes.id as style_size_id',
		  'style_colors.id as style_color_id',
		  'style_gmt_color_sizes.id as style_gmt_color_size_id',
		  'sizes.name as size_name',
		  'sizes.code as size_code',
		  'colors.name as color_name',
		  'colors.code as color_code',
		  'style_sizes.sort_id as size_sort_id',
		  'style_colors.sort_id as color_sort_id',
		  'mkt_cost_fabric_cons.dia',
		  'mkt_cost_fabric_cons.cons',
		  'mkt_cost_fabric_cons.process_loss',
		  'mkt_cost_fabric_cons.req_cons',
		  'mkt_cost_fabric_cons.rate',
		  'mkt_cost_fabric_cons.amount',
		  
		  'mkt_cost_fabric_cons.body_lenght',
		  'mkt_cost_fabric_cons.body_sewing_margin',
		  'mkt_cost_fabric_cons.body_hem_margin',
		  'mkt_cost_fabric_cons.sleeve_lenght',
		  'mkt_cost_fabric_cons.sleeve_sewing_margin',
		  'mkt_cost_fabric_cons.sleeve_hem_margin',
		  'mkt_cost_fabric_cons.chest_lenght',
		  'mkt_cost_fabric_cons.chest_sewing_margin',
		  
		  'mkt_cost_fabric_cons.frontraise_lenght',
		  'mkt_cost_fabric_cons.frontraise_sewing_margin',
		  'mkt_cost_fabric_cons.westband_lenght',
		  'mkt_cost_fabric_cons.westband_sewing_margin',
		  'mkt_cost_fabric_cons.inseam_lenght',
		  'mkt_cost_fabric_cons.inseam_sewing_margin',
		  'mkt_cost_fabric_cons.inseam_hem_margin',
		  'mkt_cost_fabric_cons.thai_lenght',
		  'mkt_cost_fabric_cons.thai_sewing_margin',
        ]);
		//dd($fabric);
        $dropdown['scs'] = "'".Template::loadView('Marketing.MktCostFabricColorSizeMatrix',['colorsizes'=>$fabric,'gmtsparts_type'=>$gmtsparts_type->part_type_id,'gsm'=>$mkt_cost_fabric->gsm_weight])."'";
		$row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostFabricConRequest $request) {
		$mkt_cost_id=$request->mkt_cost_id;
        $approved=$this->mktcost->find($mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
		foreach($request->style_color_id as $index=>$style_color_id){
				if($request->cons[$index]){
				$mktcostfabriccon = $this->mktcostfabriccon->updateOrCreate([
                    'mkt_cost_fabric_id' => $request->mkt_cost_fabric_id[$index],
                    'style_gmt_color_size_id' => $request->style_gmt_color_size_id[$index]
                ],[
                    'dia' => $request->dia[$index],
                    'cons' => $request->cons[$index],
                    'process_loss' => $request->process_loss[$index],
                    'req_cons' => $request->req_cons[$index],
                    'rate' => $request->rate[$index],
                    'amount' =>$request->amount[$index],
                    'body_lenght' =>$request->body_lenght[$index],
                    'body_sewing_margin' =>$request->body_sewing_margin[$index],
                    'body_hem_margin' =>$request->body_hem_margin[$index],
                    'sleeve_lenght' =>$request->sleeve_lenght[$index],
                    'sleeve_sewing_margin' =>$request->sleeve_sewing_margin[$index],
                    'sleeve_hem_margin' =>$request->sleeve_hem_margin[$index],
                    'chest_lenght' =>$request->chest_lenght[$index],
                    'chest_sewing_margin' =>$request->chest_sewing_margin[$index],
                    'frontraise_lenght' =>$request->frontraise_lenght[$index],
                    'frontraise_sewing_margin' =>$request->frontraise_sewing_margin[$index],
                    'westband_lenght' =>$request->westband_lenght[$index],
                    'westband_sewing_margin' =>$request->westband_sewing_margin[$index],
                    'inseam_lenght' =>$request->inseam_lenght[$index],
                    'inseam_sewing_margin' =>$request->inseam_sewing_margin[$index],
                    'inseam_hem_margin' =>$request->inseam_hem_margin[$index],
                    'thai_lenght' =>$request->thai_lenght[$index],
                    'thai_sewing_margin' =>$request->thai_sewing_margin[$index],
                ]);
				}
			}
			$totalCost=$this->mktcost->totalCost($mkt_cost_id);
		return response()->json(array('success' => true, 'id' => $mktcostfabriccon->id, 'mkt_cost_id' => $mkt_cost_id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);

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
        $mktcostfabriccon = $this->mktcostfabriccon->find($id);
        $row ['fromData'] = $mktcostfabriccon;
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
    public function update(MktCostFabricConRequest $request, $id) {
        /*$mktcostfabriccon = $this->mktcostfabriccon->update($id, $request->except(['id']));
        if ($mktcostfabriccon) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        /*if ($this->mktcostfabriccon->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }*/
    }

}
