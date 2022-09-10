<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetFabricRequest;

class BudgetFabricController extends Controller {

    private $budgetfabric;
    private $budget;
    private $gmtspart;
    private $autoyarn;
    private $colorrange;
    private $uom;
    private $supplier;

    public function __construct(BudgetFabricRepository $budgetfabric,BudgetRepository $budget,GmtspartRepository $gmtspart,AutoyarnRepository $autoyarn,ColorrangeRepository $colorrange,UomRepository $uom,SupplierRepository $supplier) {
        $this->budgetfabric = $budgetfabric;
        $this->budget = $budget;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->colorrange = $colorrange;
        $this->uom = $uom;
	      $this->supplier = $supplier;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetfabrics',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetfabrics', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetfabrics',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetfabrics', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $budget=array_prepend(array_pluck($this->budget->get(),'name','id'),'-Select-','');
      $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
      $autoyarn=array_prepend(array_pluck($this->autoyarn->get(),'name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
      $budgetfabrics=array();
	    $rows=$this->budgetfabric->get();
  		foreach($rows as $row){
        $budgetfabric['id']=	$row->id;
        $budgetfabric['budget']=	$budget[$row->budget_id];
        $budgetfabric['gmtspart']=	$gmtspart[$row->gmtspart_id];
        $budgetfabric['autoyarn']=	$autoyarn[$row->autoyarn_id];
        $budgetfabric['colorrange']=	$colorrange[$row->colorrange_id];
        $budgetfabric['uom']=	$uom[$row->uom_id];
  		   array_push($budgetfabrics,$budgetfabric);
  		}
        echo json_encode($budgetfabrics);
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
		$fabricDescription=$this->budget
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		->where([['budgets.id','=',request('budget_id',0)]])
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

		$fabrics=$this->budget
		->selectRaw(
			'budgets.id as budget_id,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			gmtsparts.name as gmtspart_name,
			item_accounts.item_description,
			uoms.code as uom_name,
			smp_cost_fabrics.gsm_weight as smp_gsm_weight,
			budget_fabrics.gsm_weight,
			budget_fabrics.id,
			budget_fabrics.supplier_id,
			sum(budget_fabric_cons.grey_fab) as req_cons,
			avg(budget_fabric_cons.rate) as rate
			'
		)
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		->leftJoin('smp_cost_fabrics',function($join){
          $join->on('smp_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
        })
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.budget_id','=','budgets.id');
		$join->on('budget_fabrics.style_fabrication_id','=','style_fabrications.id');
		})
		->leftJoin('budget_fabric_cons',function($join){
		$join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');
		})
		->where([['budgets.id','=',request('budget_id',0)]])
		->where([['style_fabrications.is_narrow','=',0]])
		->groupBy([
		'budgets.id',
		'style_fabrications.id',
		'style_fabrications.material_source_id',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'gmtsparts.name',
		'item_accounts.item_description',
		'uoms.code',
		'smp_cost_fabrics.gsm_weight',
		'budget_fabrics.gsm_weight',
		'budget_fabrics.id',
		'budget_fabrics.supplier_id'
		])
		->get();
		$stylefabrications=array();
		$stylenarrowfabrications=array();
        foreach($fabrics as $row){
			  $stylefabrication['id']=	$row->id;
			  $stylefabrication['budget_id']=	$row->budget_id;
			  $stylefabrication['style_fabrication_id']=	$row->style_fabrication_id;
			  $stylefabrication['style_gmt']=	$row->item_description;
			  $stylefabrication['gmtspart']=	$row->gmtspart_name;
			  $stylefabrication['fabric_description']=	$desDropdown[$row->style_fabrication_id];
			  $stylefabrication['uom_name']=	$row->uom_name;
			  $stylefabrication['materialsourcing']=	$materialsourcing[$row->material_source_id];
			  $stylefabrication['fabricnature']=	$fabricnature[$row->fabric_nature_id];
			  $stylefabrication['fabriclooks']=	$fabriclooks[$row->fabric_look_id];
			  $stylefabrication['fabricshape']=	$fabricshape[$row->fabric_shape_id];
			  $stylefabrication['gsm_weight']=	$row->gsm_weight;
			  $stylefabrication['smp_gsm_weight']=	$row->smp_gsm_weight;
			  $stylefabrication['supplier_id']=	$row->supplier_id;
			  $stylefabrication['req_cons']=	$row->req_cons;
			  $stylefabrication['rate']=	$row->rate;
			  $stylefabrication['amount']=	$row->req_cons*$row->rate;
			 array_push($stylefabrications,$stylefabrication);
    	}

		$narrowfabrics=$this->budget
		->selectRaw(
			'budgets.id as budget_id,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			gmtsparts.name as gmtspart_name,
			item_accounts.item_description,
			uoms.code as uom_name,
            smp_cost_fabrics.gsm_weight as smp_gsm_weight,
			budget_fabrics.gsm_weight,
			budget_fabrics.id,
			budget_fabrics.supplier_id,
			sum(budget_fabric_cons.grey_fab) as req_cons,
			avg(budget_fabric_cons.rate) as rate
			'
		)
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		->leftJoin('smp_cost_fabrics',function($join){
          $join->on('smp_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
        })
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.budget_id','=','budgets.id');
		$join->on('budget_fabrics.style_fabrication_id','=','style_fabrications.id');
		})
		->leftJoin('budget_fabric_cons',function($join){
		$join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');
		})
		->where([['budgets.id','=',request('budget_id',0)]])
		->where([['style_fabrications.is_narrow','=',1]])
		->groupBy([
		'budgets.id',
		'style_fabrications.id',
		'style_fabrications.material_source_id',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'gmtsparts.name',
		'item_accounts.item_description',
		'uoms.code',
		'smp_cost_fabrics.gsm_weight',
		'budget_fabrics.gsm_weight',
		'budget_fabrics.id',
		'budget_fabrics.supplier_id'
		])
		->get();
		foreach($narrowfabrics as $narrowrow){
			  $stylenarrowfabrication['id']=	$narrowrow->id;
			  $stylenarrowfabrication['budget_id']=	$narrowrow->budget_id;
			  $stylenarrowfabrication['style_fabrication_id']=	$narrowrow->style_fabrication_id;
			  $stylenarrowfabrication['style_gmt']=	$narrowrow->item_description;
			  $stylenarrowfabrication['gmtspart']=	$narrowrow->gmtspart_name;
			  $stylenarrowfabrication['fabric_description']=	$desDropdown[$narrowrow->style_fabrication_id];
			  $stylenarrowfabrication['uom_name']=	$narrowrow->uom_name;
			  $stylenarrowfabrication['materialsourcing']=	$materialsourcing[$narrowrow->material_source_id];
			  $stylenarrowfabrication['fabricnature']=	$fabricnature[$narrowrow->fabric_nature_id];
			  $stylenarrowfabrication['fabriclooks']=	$fabriclooks[$narrowrow->fabric_look_id];
			  $stylenarrowfabrication['fabricshape']=	$fabricshape[$narrowrow->fabric_shape_id];
			  $stylenarrowfabrication['gsm_weight']=	$narrowrow->gsm_weight;
			  $stylenarrowfabrication['smp_gsm_weight']=	$narrowrow->smp_gsm_weight;
			   $stylenarrowfabrication['supplier_id']=	$narrowrow->supplier_id;
			  $stylenarrowfabrication['req_cons']=	$narrowrow->req_cons;
			  $stylenarrowfabrication['rate']=	$narrowrow->rate;
			  $stylenarrowfabrication['amount']=	$narrowrow->req_cons*$narrowrow->rate;
			 array_push($stylenarrowfabrications,$stylenarrowfabrication);

    	}
		$supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
		$fabric['fabricdiv'] = "'".Template::loadView('Bom.BudgetFabricMatrix', ['fabrics'=>$stylefabrications,'supplier'=>$supplier])."'";
		$fabric['narrowfabricdiv'] = "'".Template::loadView('Bom.BudgetFabricMatrix', ['fabrics'=>$stylenarrowfabrications,'supplier'=>$supplier])."'";
		$data ['dropDown'] = $fabric;
		echo json_encode($data);
        //return Template::loadView('Marketing.BudgetFabricMatrix', ['fabrics'=>$stylefabrications]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetFabricRequest $request) {
    	$budgetapp=$this->budget->find(request('bud_id',0));
    	if($budgetapp->approved_at){
    	$totalCost=$this->budget->totalCost($budgetapp->id);
    	return response()->json(array('success' => false, 'budget_id' => $budgetapp->id,  'message' => 'Budget is Approved, So Save Or Update not Possible','totalcost' => $totalCost), 200);

    	}

		$budgetId=0;
		foreach($request->budget_id as $index=>$budget_id){
			$budgetId=$budget_id;
			if($request->gsm_weight[$index]){
				$budgetfabric = $this->budgetfabric->updateOrCreate(
				['budget_id' => $budget_id,'style_fabrication_id' => $request->style_fabrication_id[$index]],
				['gsm_weight' => $request->gsm_weight[$index],'supplier_id' => $request->supplier_id[$index]]
				);
			}
		}
		$totalCost=$this->budget->totalCost($budgetId);
		return response()->json(array('success' => true, 'id' => $budgetfabric->id, 'budget_id' => $budgetId, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        $budgetfabric = $this->budgetfabric->find($id);
		$fabric=$this->budgetfabric
        ->join('budgets',function($join){
          $join->on('budgets.id','=','budget_fabrics.budget_id');
        })
		->leftJoin('cads',function($join){
          $join->on('cads.style_id','=','budgets.style_id');
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
		->leftJoin('budget_fabric_cons',function($join){
          $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');
		  $join->on('budget_fabric_cons.style_color_id','=','style_colors.id');
		  $join->on('budget_fabric_cons.style_size_id','=','style_sizes.id');
        })
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
        ->where([['budget_fabrics.id','=',$id]])
        ->get([
          'budgets.id as budget_id',
          'budget_fabrics.id as budget_fabric_id',
		  'cad_cons.style_size_id',
		  'style_colors.id as style_color_id',
		  'cad_cons.cons',
		  'sizes.name as size_name',
		  'sizes.code as size_code',
		  'colors.name as color_name',
		  'colors.code as color_code',
		  'style_sizes.sort_id as size_sort_id',
		  'style_colors.sort_id as color_sort_id',
		  'budget_fabric_cons.dia',
		  'budget_fabric_cons.cons',
		  'budget_fabric_cons.process_loss',
		  'budget_fabric_cons.req_cons',
		  'budget_fabric_cons.rate',
		  'budget_fabric_cons.amount',
        ]);
        $stylefabrications=array();
        foreach($fabric as $row){
          $stylefabrication['id']=	'';
          $stylefabrication['budget_id']=	$row->budget_id;
          $stylefabrication['budget_fabric_id']=	$row->budget_fabric_id;
          $stylefabrication['style_size_id']=	$row->style_size_id;
		  $stylefabrication['style_color_id']=	$row->style_color_id;
		  $stylefabrication['size_name']=	$row->size_name;
		  $stylefabrication['color_name']=	$row->color_name;
		  $stylefabrication['cons']=	$row->cons;
    	 array_push($stylefabrications,$stylefabrication);
    	}
        $row ['fromData'] = $budgetfabric;
        $dropdown['scs'] = "'".Template::loadView('Bom.BudgetFabricColorSizeMatrix',['colorsizes'=>$fabric])."'";
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
    public function update(BudgetFabricRequest $request, $id) {
    	$budgetapp=$this->budget->find($request->budget_id);
    	if($budgetapp->approved_at){
    	return response()->json(array('success' => false,  'message' => 'Budget is Approved, So Save Or Update not Possible'), 200);

    	}

        $budgetfabric = $this->budgetfabric->update($id, $request->except(['id']));
		$totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgetfabric) {
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
        if ($this->budgetfabric->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}