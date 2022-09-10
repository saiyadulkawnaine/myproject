<?php

namespace App\Http\Controllers\Sample\Costing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sample\Costing\SmpCostYarnRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
//use App\Http\Requests\Sample\Costing\smpcostyarnRequest;

class SmpCostYarnController extends Controller {

    private $smpcostyarn;
    private $smpcost;
    private $smpcostfabric;
  	private $itemaccount;

    public function __construct(
    	SmpCostYarnRepository $smpcostyarn,
    	SmpCostRepository $smpcost,
    	SmpCostFabricRepository $smpcostfabric,
    	ItemAccountRepository $itemaccount
    ) {
        $this->smpcostyarn = $smpcostyarn;
        $this->smpcost = $smpcost;
        $this->smpcostfabric = $smpcostfabric;
    	$this->itemaccount = $itemaccount;
        $this->middleware('auth');
        //$this->middleware('permission:view.smpcostyarns',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.smpcostyarns', ['only' => ['store']]);
        //$this->middleware('permission:edit.smpcostyarns',   ['only' => ['update']]);
        //$this->middleware('permission:delete.smpcostyarns', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$yarnDescription=$this->itemaccount
		->join('item_account_ratios',function($join){
			$join->on('item_account_ratios.item_account_id','=','item_accounts.id');
		})
		->join('yarncounts',function($join){
			$join->on('yarncounts.id','=','item_accounts.yarncount_id');
		})
		->join('yarntypes',function($join){
			$join->on('yarntypes.id','=','item_accounts.yarntype_id');
		})
		->join('itemclasses',function($join){
			$join->on('itemclasses.id','=','item_accounts.itemclass_id');
		})
		->join('compositions',function($join){
			$join->on('compositions.id','=','item_account_ratios.composition_id');
		})
		->join('itemcategories',function($join){
			$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
		})
		->where([['itemcategories.identity','=',1]])
		->get([
		'item_accounts.id',
		'yarncounts.count',
		'yarncounts.symbol',
		'yarntypes.name as yarn_type',
		'itemclasses.name as itemclass_name',
		'compositions.name as composition_name',
		'item_account_ratios.ratio',
		]);
		$itemaccountArr=array();
		$yarnCompositionArr=array();
		foreach($yarnDescription as $row){
		$itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
		$itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
		$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
		$yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
		}
		$yarnDropdown=array();
		foreach($itemaccountArr as $key=>$value){
		$yarnDropdown[$key]=$value['itemclass_name']." ".$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
		}


		$smpcostyarns=array();
		$rows=$this->smpcostyarn->where([['smp_cost_yarns.smp_cost_id','=',request('smp_cost_id',0)]])->get();
		$tot=0;
		$totCons=0;
		foreach($rows as $row){
			$smpcostyarn['id']=	$row->id;
			$smpcostyarn['yarn_ratio']=	$row->ratio;
			$smpcostyarn['yarn_cons']=	$row->cons;
			$smpcostyarn['yarn_rate']=	$row->rate;
			$smpcostyarn['yarn_amount']=	$row->amount;
			$tot+=$row->amount;
			$totCons+=$row->cons;
			$smpcostyarn['yarn_des']=	$yarnDropdown[$row->item_account_id];
			array_push($smpcostyarns,$smpcostyarn);
		}
		$dd=array('total'=>1,'rows'=>$smpcostyarns,'footer'=>array(0=>array('yarn_des'=>'','yarn_ratio'=>'Total','yarn_cons'=>$totCons,'yarn_rate'=>'','yarn_amount'=>$tot)));
		echo json_encode($dd);
    }

	public function getPopuplist(){
		$yarnDescription=$this->itemaccount
		->join('item_account_ratios',function($join){
		$join->on('item_account_ratios.item_account_id','=','item_accounts.id');
		})
		->join('yarncounts',function($join){
		$join->on('yarncounts.id','=','item_accounts.yarncount_id');
		})
		->join('yarntypes',function($join){
		$join->on('yarntypes.id','=','item_accounts.yarntype_id');
		})
		->join('itemclasses',function($join){
		$join->on('itemclasses.id','=','item_accounts.itemclass_id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','item_account_ratios.composition_id');
		})
		->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
	  ->where([['itemcategories.identity','=',1]])
		->get([
		'item_accounts.id',
		'yarncounts.count',
		'yarncounts.symbol',
		'yarntypes.name as yarn_type',
		'itemclasses.name as itemclass_name',
		'compositions.name as composition_name',
		'item_account_ratios.ratio',
		]);
		$itemaccountArr=array();
		$yarnCompositionArr=array();
		foreach($yarnDescription as $row){
		$itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
		$itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
		$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
		$yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
		}
		$yarnDropdown=array();
		foreach($itemaccountArr as $key=>$value){
		$yarnDropdown[$key]=$value['itemclass_name']." ".$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
		}
		$smpcostyarns=array();
		$rows=$this->smpcostyarn->where([['smp_cost_yarns.smp_cost_fabric_id','=',request('smp_cost_fabric_id',0)]])->get();
		$tot=0;
		foreach($rows as $row){
			$smpcostyarn['id']=	$row->id;
			$smpcostyarn['yarn_ratio']=	$row->ratio;
			$smpcostyarn['yarn_cons']=	$row->cons;
			$smpcostyarn['yarn_rate']=	$row->rate;
			$smpcostyarn['yarn_amount']=	$row->amount;
			$tot+=$row->amount;
			$smpcostyarn['yarn_des']=	$yarnDropdown[$row->item_account_id];
			array_push($smpcostyarns,$smpcostyarn);
		}
		$dd=array('total'=>1,'rows'=>$smpcostyarns,'footer'=>array(0=>array('yarn_des'=>'','yarn_ratio'=>'','yarn_cons'=>'','yarn_rate'=>'Total','yarn_amount'=>$tot)));
		echo json_encode($dd);
	}

	public function getfabriclist(){
		$materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
	    $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		$fabricDescription=$this->smpcost

		->leftJoin('style_samples',function($join){
		$join->on('style_samples.id','=','smp_costs.style_sample_id');
		})
		->leftJoin('style_gmts', function($join)  {
		$join->on('style_gmts.id', '=', 'style_samples.style_gmt_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_gmt_id','=','style_gmts.id');
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

		->leftJoin('styles',function($join){
		$join->on('styles.id','=','style_gmts.style_id');
		})
		->where([['smp_costs.id','=',request('smp_cost_id',0)]])
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

		$fabrics=$this->smpcost
		->selectRaw(
			'smp_costs.id as smp_cost_id,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			gmtsparts.name as gmtspart_name,
			gmtsparts.part_type_id,
			item_accounts.item_description,
			uoms.code as uom_name,
			smp_cost_fabrics.gsm_weight,
			smp_cost_fabrics.id,
			sum(smp_cost_fabric_cons.grey_fab) as grey_fab,
			sum(smp_cost_fabric_cons.amount) as amount
			'
		)
		->leftJoin('style_samples',function($join){
		$join->on('style_samples.id','=','smp_costs.style_sample_id');
		})

		->leftJoin('style_gmts', function($join)  {
		$join->on('style_gmts.id', '=', 'style_samples.style_gmt_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_gmt_id','=','style_gmts.id');
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
		->join('smp_cost_fabrics',function($join){
		$join->on('smp_cost_fabrics.smp_cost_id','=','smp_costs.id');
		$join->on('smp_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
		})
		->join('smp_cost_fabric_cons',function($join){
		$join->on('smp_cost_fabric_cons.smp_cost_fabric_id','=','smp_cost_fabrics.id');
		})

		->where([['smp_costs.id','=',request('smp_cost_id',0)]])
		->groupBy([
		'smp_costs.id',
		'style_fabrications.id',
		'style_fabrications.material_source_id',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'gmtsparts.name',
		'gmtsparts.part_type_id',
		'item_accounts.item_description',
		'uoms.code',
		'smp_cost_fabrics.gsm_weight',
		'smp_cost_fabrics.id',
		])
		->get();
		$stylefabrications=array();
		$stylenarrowfabrications=array();
        foreach($fabrics as $row){
			  $stylefabrication['id']=	$row->id;
			  $stylefabrication['smp_cost_id']=	$row->smp_cost_id;
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
			  $stylefabrication['req_cons']=	$row->grey_fab;
			  $stylefabrication['rate']=	number_format($row->amount/$row->grey_fab,4);
			  $stylefabrication['amount']=	$row->amount;
			 array_push($stylefabrications,$stylefabrication);
    	}

		
		$fabric['fabricyarndiv'] = "'".Template::loadView('Sample.Costing.SmpCostYarnMatrix', ['fabrics'=>$stylefabrications])."'";
		$data ['dropDown'] = $fabric;
		echo json_encode($data);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

    	


		$fabricyarn=$this->smpcostfabric
		->selectRaw(
			'smp_cost_fabrics.id,
			smp_cost_fabrics.smp_cost_id,
			sum(smp_cost_fabric_cons.grey_fab) as req_cons
			'
		)
        ->join('smp_cost_fabric_cons',function($join){
          $join->on('smp_cost_fabric_cons.smp_cost_fabric_id','=','smp_cost_fabrics.id');
        })
		->groupBy([
		'smp_cost_fabrics.id',
		'smp_cost_fabrics.smp_cost_id',
		])
		->where([['smp_cost_fabrics.id','=',request('smp_cost_fabric_id',0)]])
        ->get()->first();

        
		$yarnDescription=$this->itemaccount
      ->join('item_account_ratios',function($join){
      $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
      })
	  ->join('yarncounts',function($join){
      $join->on('yarncounts.id','=','item_accounts.yarncount_id');
      })
	  ->join('yarntypes',function($join){
      $join->on('yarntypes.id','=','item_accounts.yarntype_id');
      })
	  ->join('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
	  ->join('compositions',function($join){
      $join->on('compositions.id','=','item_account_ratios.composition_id');
      })
	  ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
	  ->where([['itemcategories.identity','=',1]])
      ->get([
	  'item_accounts.id',
		'yarncounts.count',
		'yarncounts.symbol',
		'yarntypes.name as yarn_type',
		'itemclasses.name as itemclass_name',
		'compositions.name as composition_name',
		'item_account_ratios.ratio',
	  ]);
       $itemaccountArr=array();
	   $yarnCompositionArr=array();
      foreach($yarnDescription as $row){
		  $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
		  $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
		  $itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
          $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
      }
	  $yarnDropdown=array();
	  foreach($itemaccountArr as $key=>$value){
		  $yarnDropdown[$key]=$value['itemclass_name']." ".$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
	  }
		return Template::loadView('Sample.Costing.SmpCostYarn',['yarnDropdown'=>$yarnDropdown,'fabricyarn'=>$fabricyarn]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $smpcostyarn = $this->smpcostyarn->create($request->except(['id','fab_cons','item_description']));
        if ($smpcostyarn) {
			return response()->json(array('success' => true, 'id' => $smpcostyarn->id, 'smp_cost_id' => $request->smp_cost_id, 'message' => 'Save Successfully'), 200);
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
		$yarnDescription=$this->itemaccount
		->join('item_account_ratios',function($join){
		$join->on('item_account_ratios.item_account_id','=','item_accounts.id');
		})
		->join('yarncounts',function($join){
		$join->on('yarncounts.id','=','item_accounts.yarncount_id');
		})
		->join('yarntypes',function($join){
		$join->on('yarntypes.id','=','item_accounts.yarntype_id');
		})
		->join('itemclasses',function($join){
		$join->on('itemclasses.id','=','item_accounts.itemclass_id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','item_account_ratios.composition_id');
		})
		->join('itemcategories',function($join){
		$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
		})
		->where([['itemcategories.identity','=',1]])
		->get([
		'item_accounts.id',
		'yarncounts.count',
		'yarncounts.symbol',
		'yarntypes.name as yarn_type',
		'itemclasses.name as itemclass_name',
		'compositions.name as composition_name',
		'item_account_ratios.ratio',
		]);
		$itemaccountArr=array();
		$yarnCompositionArr=array();
		foreach($yarnDescription as $row){
		$itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
		$itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
		$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
		$yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
		}
		$yarnDropdown=array();
		foreach($itemaccountArr as $key=>$value){
		$yarnDropdown[$key]=$value['itemclass_name']." ".$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
		}
		$smpcostyarn = $this->smpcostyarn
		->where([['id','=',$id]])
		->get()
		->map(function ($smpcostyarn) use($yarnDropdown) {
			$smpcostyarn->item_description= $yarnDropdown[$smpcostyarn->item_account_id];
			return $smpcostyarn;
		});
        $row ['fromData'] = $smpcostyarn[0];
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
    public function update(Request $request, $id) {
        $smpcostyarn = $this->smpcostyarn->update($id, $request->except(['id','fab_cons','item_description']));
        if ($smpcostyarn) {
			//$totalCost=$this->smpcost->totalCost($request->mkt_cost_id);
			return response()->json(array('success' => true, 'id' => $id, 'smp_cost_id' => $request->smp_cost_id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->smpcostyarn->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	
	public function getyarn(){
		$yarnDescription=$this->itemaccount
		->join('item_account_ratios',function($join){
		$join->on('item_account_ratios.item_account_id','=','item_accounts.id');
		})
		->join('yarncounts',function($join){
		$join->on('yarncounts.id','=','item_accounts.yarncount_id');
		})
		->join('yarntypes',function($join){
		$join->on('yarntypes.id','=','item_accounts.yarntype_id');
		})
		->join('itemclasses',function($join){
		$join->on('itemclasses.id','=','item_accounts.itemclass_id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','item_account_ratios.composition_id');
		})
		->join('itemcategories',function($join){
		$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
		})
		->where([['itemcategories.identity','=',1]])
		->when(request('count_name'), function ($q) {
			return $q->where('yarncounts.count', 'LIKE', "%".request('count_name', 0)."%");
		})
		->when(request('type_name'), function ($q) {
			return $q->where('yarntypes.name', 'LIKE', "%".request('type_name', 0)."%");
		})
		->get([
		'item_accounts.id',
		'yarncounts.count',
		'yarncounts.symbol',
		'yarntypes.name as yarn_type',
		'itemclasses.name as itemclass_name',
		'compositions.name as composition_name',
		'item_account_ratios.ratio',
		]);
		
		 
		$itemaccountArr=array();
		$yarnCompositionArr=array();
		foreach($yarnDescription as $row){
		$itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
		$itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
		$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
		$yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
		}
		
		$yarnDropdown=array();
		foreach($itemaccountArr as $key=>$value){
		$yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
		}
		
	  $yarn=array();
	  $yarns=array();
      foreach($yarnDescription as $row){
        $yarn[$row->id]['id']=$row->id;
		$yarn[$row->id]['itemclass_name']=$row->itemclass_name;
		$yarn[$row->id]['count']=$row->count."/".$row->symbol;
		$yarn[$row->id]['yarn_type']=$row->yarn_type;
		$yarn[$row->id]['composition_name']=$yarnDropdown[$row->id];
      }
	  foreach($yarn as $row){
        
		array_push($yarns,$row);
      }
	  		 echo json_encode($yarns);
	}

}
