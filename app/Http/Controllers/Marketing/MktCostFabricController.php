<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostFabricRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Library\Template;
use App\Http\Requests\MktCostFabricRequest;

class MktCostFabricController extends Controller {

    private $mktcostfabric;
    private $mktcost;
    private $gmtspart;
    private $autoyarn;
    private $colorrange;
    private $uom;

    public function __construct(MktCostFabricRepository $mktcostfabric,MktCostRepository $mktcost,GmtspartRepository $gmtspart,AutoyarnRepository $autoyarn,ColorrangeRepository $colorrange,UomRepository $uom) {
        $this->mktcostfabric = $mktcostfabric;
        $this->mktcost = $mktcost;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->colorrange = $colorrange;
        $this->uom = $uom;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcostfabrics',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcostfabrics', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcostfabrics',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcostfabrics', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
      $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
      $autoyarn=array_prepend(array_pluck($this->autoyarn->get(),'name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
      $mktcostfabrics=array();
	    $rows=$this->mktcostfabric->get();
  		foreach($rows as $row){
        $mktcostfabric['id']=	$row->id;
        $mktcostfabric['mktcost']=	$mktcost[$row->mkt_cost_id];
        $mktcostfabric['gmtspart']=	$gmtspart[$row->gmtspart_id];
        $mktcostfabric['autoyarn']=	$autoyarn[$row->autoyarn_id];
        $mktcostfabric['colorrange']=	$colorrange[$row->colorrange_id];
        $mktcostfabric['uom']=	$uom[$row->uom_id];
  		   array_push($mktcostfabrics,$mktcostfabric);
  		}
        echo json_encode($mktcostfabrics);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
	    $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		$fabricDescription=$this->mktcost
		->join('styles',function($join){
		$join->on('styles.id','=','mkt_costs.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','mkt_costs.style_id');
		})
		->join('autoyarns',function($join){
		$join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
		})
		->join('autoyarnratios',function($join){
		$join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','autoyarnratios.composition_id');
		})
		->join('constructions',function($join){
		$join->on('constructions.id','=','autoyarns.construction_id');
		})
		->where([['mkt_costs.id','=',request('mkt_cost_id',0)]])

		->get([
		'style_fabrications.id',
		'constructions.name as construction',
		'autoyarnratios.composition_id',
		'compositions.name',
		'autoyarnratios.ratio',
		]);
		$fabricDescriptionArr=array();
		$fabricCompositionArr=array();
		foreach($fabricDescription as $row){
			$fabricDescriptionArr[$row->id]=$row->construction;
			$fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
		}
		$desDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
		$desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}
		$fabrics=$this->mktcost
		->selectRaw(
			'mkt_costs.id as mkt_cost_id,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			gmtsparts.name as gmtspart_name,
			gmtsparts.part_type_id,
			item_accounts.item_description,
			uoms.code as uom_name,
			mkt_cost_fabrics.gsm_weight,
			mkt_cost_fabrics.id,
			avg(mkt_cost_fabric_cons.req_cons) as req_cons,
			avg(mkt_cost_fabric_cons.rate) as rate
			'
		)
		->join('styles',function($join){
		$join->on('styles.id','=','mkt_costs.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','mkt_costs.style_id');
		})
		->join('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
		})
		->join('autoyarns',function($join){
		$join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
		})

		->join('uoms',function($join){
		$join->on('uoms.id','=','style_fabrications.uom_id');
		})
		->leftJoin('mkt_cost_fabrics',function($join){
		$join->on('mkt_cost_fabrics.mkt_cost_id','=','mkt_costs.id');
		$join->on('mkt_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
		})
		->leftJoin('mkt_cost_fabric_cons',function($join){
		$join->on('mkt_cost_fabric_cons.mkt_cost_fabric_id','=','mkt_cost_fabrics.id');
		})

		->where([['mkt_costs.id','=',request('mkt_cost_id',0)]])
		->where([['style_fabrications.is_narrow','=',0]])
		->groupBy([
		'mkt_costs.id',
		'style_fabrications.id',
		'style_fabrications.material_source_id',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'gmtsparts.name',
		'gmtsparts.part_type_id',
		'item_accounts.item_description',
		'uoms.code',
		'mkt_cost_fabrics.gsm_weight',
		'mkt_cost_fabrics.id',
		])
		->get();
		$stylefabrications=array();
		$stylenarrowfabrications=array();
        foreach($fabrics as $row){
			  $stylefabrication['id']=	$row->id;
			  $stylefabrication['mkt_cost_id']=	$row->mkt_cost_id;
			  $stylefabrication['style_fabrication_id']=	$row->style_fabrication_id;
			  $stylefabrication['style_gmt']=	$row->item_description;
			  $stylefabrication['gmtspart']=	$row->gmtspart_name;
			  $stylefabrication['part_type_id']=	$row->part_type_id;
			  $stylefabrication['fabric_description']=	$desDropdown[$row->style_fabrication_id];
			  $stylefabrication['uom_name']=	$row->uom_name;
			  $stylefabrication['materialsourcing']=	$materialsourcing[$row->material_source_id];
			  $stylefabrication['fabricnature']=	$fabricnature[$row->fabric_nature_id];
			  $stylefabrication['fabriclooks']=	$fabriclooks[$row->fabric_look_id];
			  $stylefabrication['fabricshape']=	$fabricshape[$row->fabric_shape_id];
			  $stylefabrication['gsm_weight']=	$row->gsm_weight;
			  $stylefabrication['req_cons']=	$row->req_cons;
			  $stylefabrication['rate']=	$row->rate;
			  $stylefabrication['amount']=	number_format($row->req_cons*$row->rate,4);
			 array_push($stylefabrications,$stylefabrication);
    	}

		$narrowfabrics=$this->mktcost
		->selectRaw(
			'mkt_costs.id as mkt_cost_id,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			gmtsparts.name as gmtspart_name,
			gmtsparts.part_type_id,
			item_accounts.item_description,
			uoms.code as uom_name,
			mkt_cost_fabrics.gsm_weight,
			mkt_cost_fabrics.id,
			avg(mkt_cost_fabric_cons.req_cons) as req_cons,
			avg(mkt_cost_fabric_cons.rate) as rate
			'
		)
		->join('styles',function($join){
		$join->on('styles.id','=','mkt_costs.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','mkt_costs.style_id');
		})
		->join('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
		})
		->join('autoyarns',function($join){
		$join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
		})

		->join('uoms',function($join){
		$join->on('uoms.id','=','style_fabrications.uom_id');
		})
		->leftJoin('mkt_cost_fabrics',function($join){
		$join->on('mkt_cost_fabrics.mkt_cost_id','=','mkt_costs.id');
		$join->on('mkt_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
		})
		->leftJoin('mkt_cost_fabric_cons',function($join){
		$join->on('mkt_cost_fabric_cons.mkt_cost_fabric_id','=','mkt_cost_fabrics.id');
		})
		->where([['mkt_costs.id','=',request('mkt_cost_id',0)]])
		->where([['style_fabrications.is_narrow','=',1]])
		->groupBy([
		'mkt_costs.id',
		'style_fabrications.id',
		'style_fabrications.material_source_id',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'gmtsparts.name',
		'gmtsparts.part_type_id',
		'item_accounts.item_description',
		'uoms.code',
		'mkt_cost_fabrics.gsm_weight',
		'mkt_cost_fabrics.id',
		])
		->get();
		foreach($narrowfabrics as $narrowrow){
			  $stylenarrowfabrication['id']=	$narrowrow->id;
			  $stylenarrowfabrication['mkt_cost_id']=	$narrowrow->mkt_cost_id;
			  $stylenarrowfabrication['style_fabrication_id']=	$narrowrow->style_fabrication_id;
			  $stylenarrowfabrication['style_gmt']=	$narrowrow->item_description;
			  $stylenarrowfabrication['gmtspart']=	$narrowrow->gmtspart_name;
			   $stylenarrowfabrication['part_type_id']=	$narrowrow->part_type_id;
			  $stylenarrowfabrication['fabric_description']=	$desDropdown[$narrowrow->style_fabrication_id];
			  $stylenarrowfabrication['uom_name']=	$narrowrow->uom_name;
			  $stylenarrowfabrication['materialsourcing']=	$materialsourcing[$narrowrow->material_source_id];
			  $stylenarrowfabrication['fabricnature']=	$fabricnature[$narrowrow->fabric_nature_id];
			  $stylenarrowfabrication['fabriclooks']=	$fabriclooks[$narrowrow->fabric_look_id];
			  $stylenarrowfabrication['fabricshape']=	$fabricshape[$narrowrow->fabric_shape_id];
			  $stylenarrowfabrication['gsm_weight']=	$narrowrow->gsm_weight;
			  $stylenarrowfabrication['req_cons']=	$narrowrow->req_cons;
			  $stylenarrowfabrication['rate']=	$narrowrow->rate;
			  $stylenarrowfabrication['amount']=	number_format($narrowrow->req_cons*$narrowrow->rate,4);
			 array_push($stylenarrowfabrications,$stylenarrowfabrication);

    	}
		$fabric['fabricdiv'] = "'".Template::loadView('Marketing.MktCostFabricMatrix', ['fabrics'=>$stylefabrications])."'";
		$fabric['narrowfabricdiv'] = "'".Template::loadView('Marketing.MktCostFabricMatrix', ['fabrics'=>$stylenarrowfabrications])."'";
		$data ['dropDown'] = $fabric;
		echo json_encode($data);
        //return Template::loadView('Marketing.MktCostFabricMatrix', ['fabrics'=>$stylefabrications]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostFabricRequest $request) {
		$mktCostId=$request->mkt_cost_id;
		$approved=$this->mktcost->find($mktCostId);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
		foreach($request->style_fabrication_id as $index=>$style_fabrication_id){
			if($request->gsm_weight[$index]){
				$mktcostfabric = $this->mktcostfabric->updateOrCreate([
					'mkt_cost_id' => $request->mkt_cost_id,
					'style_fabrication_id' => $request->style_fabrication_id[$index]
				],[
					'gsm_weight' => $request->gsm_weight[$index]
				]);
			}
		}
		$totalCost=$this->mktcost->totalCost($mktCostId);
		return response()->json(array('success' => true, 'id' => $mktcostfabric->id, 'mkt_cost_id' => $mktCostId, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
        /*$mktcostfabric = $this->mktcostfabric->create($request->except(['id']));
		$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
        if ($mktcostfabric) {
            return response()->json(array('success' => true, 'id' => $mktcostfabric->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
        }*/
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
        $mktcostfabric = $this->mktcostfabric->find($id);
		$fabric=$this->mktcostfabric
        ->join('mkt_costs',function($join){
          $join->on('mkt_costs.id','=','mkt_cost_fabrics.mkt_cost_id');
        })
		->leftJoin('cads',function($join){
          $join->on('cads.style_id','=','mkt_costs.style_id');
        })
		->leftJoin('cad_cons',function($join){
          $join->on('cad_cons.cad_id','=','cads.id');
        })
		->join('style_sizes',function($join){
          $join->on('style_sizes.id','=','cad_cons.style_size_id');
        })
		->join('sizes',function($join){
          $join->on('sizes.id','=','style_sizes.size_id');
        })
		->join('style_colors',function($join){
          $join->on('style_colors.style_id','=','cads.style_id');
        })
		->join('colors',function($join){
          $join->on('colors.id','=','style_colors.color_id');
        })
		->leftJoin('mkt_cost_fabric_cons',function($join){
          $join->on('mkt_cost_fabric_cons.mkt_cost_fabric_id','=','mkt_cost_fabrics.id');
		  $join->on('mkt_cost_fabric_cons.style_color_id','=','style_colors.id');
		  $join->on('mkt_cost_fabric_cons.style_size_id','=','style_sizes.id');
        })
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
        ->where([['mkt_cost_fabrics.id','=',$id]])
        ->get([
          'mkt_costs.id as mkt_cost_id',
          'mkt_cost_fabrics.id as mkt_cost_fabric_id',
		  'cad_cons.style_size_id',
		  'style_colors.id as style_color_id',
		  'cad_cons.cons',
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
        ]);
        $stylefabrications=array();
        foreach($fabric as $row){
          $stylefabrication['id']=	'';
          $stylefabrication['mkt_cost_id']=	$row->mkt_cost_id;
          $stylefabrication['mkt_cost_fabric_id']=	$row->mkt_cost_fabric_id;
          $stylefabrication['style_size_id']=	$row->style_size_id;
		  $stylefabrication['style_color_id']=	$row->style_color_id;
		  $stylefabrication['size_name']=	$row->size_name;
		  $stylefabrication['color_name']=	$row->color_name;
		  $stylefabrication['cons']=	$row->cons;
    	 array_push($stylefabrications,$stylefabrication);
    	}
        $row ['fromData'] = $mktcostfabric;
        $dropdown['scs'] = "'".Template::loadView('Marketing.MktCostFabricColorSizeMatrix',['colorsizes'=>$fabric])."'";
		$row ['dropDown'] = $dropdown;
		$row ['consdata'] = $stylefabrications;

        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MktCostFabricRequest $request, $id) {
        /*$mktcostfabric = $this->mktcostfabric->update($id, $request->except(['id']));
		$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
        if ($mktcostfabric) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully','totalcost' => $totalCost), 200);
        }*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        /*if ($this->mktcostfabric->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }*/
    }

}
