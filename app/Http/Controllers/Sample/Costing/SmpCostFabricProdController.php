<?php

namespace App\Http\Controllers\Sample\Costing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricProdRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\KnitChargeRepository;
use App\Repositories\Contracts\Util\AopChargeRepository;
use App\Repositories\Contracts\Util\YarnDyingChargeRepository;
use App\Repositories\Contracts\Util\DyingChargeRepository;
use App\Repositories\Contracts\Marketing\StyleFabricationRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Library\Template;
use App\Http\Requests\Sample\Costing\SmpCostFabricProdRequest;

class SmpCostFabricProdController extends Controller {

    private $smpcostfabricprod;
    private $smpcost;
    private $smpcostfabric;
    private $yarn;
	private $itemaccount;
	private $productionprocess;
	private $stylefabriction;
	private $autoyarn;
	private $knitcharge;
	private $yarndyeingharge;
	private $dyeingcharge;
	private $yarncount;
	private $aopcharge;

    public function __construct(SmpCostFabricProdRepository $smpcostfabricprod,SmpCostRepository $smpcost,SmpCostFabricRepository $smpcostfabric,ItemAccountRepository $itemaccount,ProductionProcessRepository $productionprocess,StyleFabricationRepository $stylefabriction,AutoyarnRepository $autoyarn, KnitChargeRepository $knitcharge,YarnDyingChargeRepository $yarndyeingharge,DyingChargeRepository $dyeingcharge,AopChargeRepository $aopcharge,YarncountRepository $yarncount) {
        $this->smpcostfabricprod = $smpcostfabricprod;
        $this->smpcost = $smpcost;
        $this->smpcostfabric = $smpcostfabric;
		$this->itemaccount = $itemaccount;
		$this->productionprocess = $productionprocess;
		$this->knitcharge = $knitcharge;
		$this->yarndyeingharge = $yarndyeingharge;
		$this->dyeingcharge = $dyeingcharge;
		$this->stylefabriction = $stylefabriction;
		$this->autoyarn = $autoyarn;
		$this->yarncount = $yarncount;
		$this->aopcharge = $aopcharge;
        $this->middleware('auth');
        //$this->middleware('permission:view.smpcostfabricprods',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.smpcostfabricprods', ['only' => ['store']]);
        //$this->middleware('permission:edit.smpcostfabricprods',   ['only' => ['update']]);
        //$this->middleware('permission:delete.smpcostfabricprods', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $smpcostfabricprods=array();
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
		->join('smp_cost_fabrics',function($join){
		$join->on('smp_cost_fabrics.smp_cost_id','=','smp_costs.id');
		$join->on('smp_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
		})
		->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
		})
		
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})


      /*->join('styles',function($join){
      $join->on('styles.id','=','smp_costs.style_id');
      })
      ->join('style_fabrications',function($join){
      $join->on('style_fabrications.style_id','=','smp_costs.style_id');
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
      ->join('smp_cost_fabrics',function($join){
      $join->on('smp_cost_fabrics.smp_cost_id','=','smp_costs.id');
	   $join->on('smp_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
      })
	  ->join('constructions',function($join){
		$join->on('constructions.id','=','autoyarns.construction_id');
		})
		->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
		})
		->join('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})*/
      ->where([['smp_costs.id','=',request('smp_cost_id',0)]])
	  ->groupBy([
	  'smp_cost_fabrics.id',
	  'style_fabrications.fabric_nature_id',
	  'style_fabrications.fabric_look_id',
	  'style_fabrications.fabric_shape_id',
	  'item_accounts.item_description',
	  'gmtsparts.name',
      'autoyarnratios.composition_id',
	  'constructions.name',
      'compositions.name',
      'autoyarnratios.ratio',
	  ])
      ->get([
      'smp_cost_fabrics.id',
	  'style_fabrications.fabric_nature_id',
	  'style_fabrications.fabric_look_id',
	  'style_fabrications.fabric_shape_id',
	  'gmtsparts.name as gmtspart_name',
	  'item_accounts.item_description',
      'autoyarnratios.composition_id',
	  'constructions.name as construction',
      'compositions.name',
      'autoyarnratios.ratio',
      ]);
      $fabricDescriptionArr=array();
	  $fabricCompositionArr=array();
      foreach($fabricDescription as $row){
		  $fabricDescriptionArr[$row->id]=$row->item_description." ".$row->gmtspart_name." ".$fabricnature[$row->fabric_nature_id]." ".$fabriclooks[$row->fabric_look_id]." ".$fabricshape[$row->fabric_shape_id]." ".$row->construction;
		  $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
      }
      $desDropdown=array();
      foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
      }
	    $rows=$this->smpcostfabricprod
      ->join('production_processes',function($join){
        $join->on('production_processes.id','=','smp_cost_fabric_prods.production_process_id');
      })
	  ->where([['smp_cost_fabric_prods.smp_cost_id','=',request('smp_cost_id',0)]])
	  ->where([['production_processes.production_area_id','=',request('production_area_id',0)]])

	  
      ->get([
        'smp_cost_fabric_prods.*',
        'production_processes.process_name',
		'production_processes.production_area_id'

      ]);
	    $tot=0;
  		foreach($rows as $row){
        $smpcostfabricprod['id']=	$row->id;
        $smpcostfabricprod['process_id']=	$row->process_name;
		$smpcostfabricprod['production_area_id']=	$row->production_area_id;
        $smpcostfabricprod['cons']=	$row->cons;
        $smpcostfabricprod['rate']=	$row->rate;
        $smpcostfabricprod['amount']=	$row->amount;
        $smpcostfabricprod['smpcostfabric']=	$desDropdown[$row->smp_cost_fabric_id];
		$smpcostfabricprod['smp_cost_fabric_id']=	$row->smp_cost_fabric_id;
		$tot+=$row->amount;
  		   array_push($smpcostfabricprods,$smpcostfabricprod);
  		}
		$dd=array('total'=>1,'rows'=>$smpcostfabricprods,'footer'=>array(0=>array('id'=>'','smpcostfabric'=>'','process_id'=>'','cons'=>'','rate'=>'Total','amount'=>$tot)));
        echo json_encode($dd);
        //echo json_encode($smpcostfabricprods);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
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
		->join('smp_cost_fabrics',function($join){
		$join->on('smp_cost_fabrics.smp_cost_id','=','smp_costs.id');
		$join->on('smp_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
		})
		->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
		})
		
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->where([['smp_costs.id','=',request('smp_cost_id',0)]])
		->groupBy([
		'smp_cost_fabrics.id',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'item_accounts.item_description',
		'gmtsparts.name',
		'autoyarnratios.composition_id',
		'constructions.name',
		'compositions.name',
		'autoyarnratios.ratio',
		])
		->get([
		'smp_cost_fabrics.id',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'gmtsparts.name as gmtspart_name',
		'item_accounts.item_description',
		'autoyarnratios.composition_id',
		'constructions.name as construction',
		'compositions.name',
		'autoyarnratios.ratio',
		]);
		$fabricDescriptionArr=array();
		$fabricCompositionArr=array();
		foreach($fabricDescription as $row){
		$fabricDescriptionArr[$row->id]=$row->item_description." ".$row->gmtspart_name." ".$fabricnature[$row->fabric_nature_id]." ".$fabriclooks[$row->fabric_look_id]." ".$fabricshape[$row->fabric_shape_id]." ".$row->construction;
		$fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
		}
		$desDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
		$desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}
		echo json_encode($desDropdown);
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmpCostFabricProdRequest $request) {
        $smpcostfabricprod = $this->smpcostfabricprod->create($request->except(['id','production_area_id']));
		//$totalCost=$this->smpcost->totalCost($request->smp_cost_id);
        if ($smpcostfabricprod) {
            return response()->json(array('success' => true, 'id' => $smpcostfabricprod->id, 'message' => 'Save Successfully'), 200);
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
        $smpcostfabricprod = $this->smpcostfabricprod->find($id);
		$productionprocess=$this->productionprocess->find($smpcostfabricprod->production_process_id);
		$fabric=$this->smpcostfabric->find($smpcostfabricprod->smp_cost_fabric_id);
		$smpcostfabricprod->req_cons=$fabric->fabric_cons;
		$smpcostfabricprod->production_area_id=$productionprocess->production_area_id;
        $row ['fromData'] = $smpcostfabricprod;
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
    public function update(SmpCostFabricProdRequest $request, $id) {
        $smpcostfabricprod = $this->smpcostfabricprod->update($id, $request->except(['id','production_area_id']));
		//$totalCost=$this->smpcost->totalCost($request->smp_cost_id);
        if ($smpcostfabricprod) {
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
        if ($this->smpcostfabricprod->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	public function getCons(){
		//$smpcostfabric
		 $rows =$this->smpcostfabric->selectRaw(
			'smp_cost_fabrics.id,
			sum(smp_cost_fabric_cons.grey_fab) as req_cons'
			)
			->leftJoin('smp_cost_fabric_cons', function($join) {
			$join->on('smp_cost_fabric_cons.smp_cost_fabric_id', '=', 'smp_cost_fabrics.id');
			})
			->where([['smp_cost_fabrics.id','=',request('smp_cost_fabric_id',0)]])
			->groupBy([
			'smp_cost_fabrics.id',
			])
			->get();
			 echo json_encode($rows);
	}
	
	public function getYarncount(){
		$yarncount=$this->itemaccount
		->join('yarncounts',function($join){
		$join->on('yarncounts.id','=','item_accounts.yarncount_id');
		})
		->join('smp_cost_yarns',function($join){
		$join->on('smp_cost_yarns.item_account_id','=','item_accounts.id');
		})
		->join('itemcategories',function($join){
		$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
		})
		->where([['itemcategories.identity','=',1]])
		->where([['smp_cost_yarns.smp_cost_fabric_id','=',request('smp_cost_fabric_id',0)]])
		->groupBy([
		'yarncounts.id',
		'yarncounts.count',
		'yarncounts.symbol',
		])
		->get([
		'yarncounts.id',
		'yarncounts.count as name',
		'yarncounts.symbol',
		]);
		if($yarncount->count()>=1){
			echo json_encode($yarncount);
		}
		else
		{
			$yarncount=$this->yarncount->get()->map(function ($yarncount) {
			$yarncount->name=$yarncount->count;
			return $yarncount;
			});
			echo json_encode($yarncount);
		}
	}
	
	public function getrate(){
		
		$productionprocess=$this->productionprocess->find(request('production_process_id',0));
		$smpcostfabric=$this->smpcostfabric->find(request('smp_cost_fabric_id',0));
		$smpcost=$this->smpcost->find($smpcostfabric->smp_cost_id);
		$stylefabriction=$this->stylefabriction->find($smpcostfabric->style_fabrication_id);
		$autoyarn=$this->autoyarn->find($stylefabriction->autoyarn_id);
		if($productionprocess->production_area_id==5){//5 yarn dyeing
			$row=$this->yarndyeingharge
			->where([['colorrange_id','=',request('colorrange_id',0)]])
			->where([['yarncount_id','=',request('yarncount_id',0)]])
			->get(['rate'])->first();
		}
		if($productionprocess->production_area_id==10){//10 kniting
			$row=$this->knitcharge
			->where([['construction_id','=',$autoyarn->construction_id]])
			->where([['fabric_look_id','=',$stylefabriction->fabric_look_id]])
			->get(['in_house_rate as rate'])->first();
		}
		if($productionprocess->production_area_id==20){//20 dyeing
			$row=$this->dyeingcharge
			->where([['autoyarn_id','=',$stylefabriction->autoyarn_id]])
			->where([['colorrange_id','=',request('colorrange_id',0)]])
			->where([['fabric_shape_id','=',$stylefabriction->fabric_shape_id]])
			->where([['dyeing_type_id','=',$stylefabriction->dyeing_type_id]])
			->get(['rate'])->first();
		}
		if($productionprocess->production_area_id==25){//25 AOP
			$row=$this->aopcharge
			->where([['autoyarn_id','=',$stylefabriction->autoyarn_id]])
			->where([['embelishment_type_id','=',$stylefabriction->embelishment_type_id]])
			->whereRaw('? between from_gsm and to_gsm', [$smpcostfabric->gsm_weight])
			->whereRaw('? between from_coverage and to_coverage', [$stylefabriction->coverage])
			->whereRaw('? between from_impression and to_impression', [$stylefabriction->impression])
			->get(['rate'])->first();
		}
		echo json_encode($row);
	}
	public function getproductionarea(){
		$productionprocess=$this->productionprocess->find(request('production_process_id',0));
		echo json_encode($productionprocess);
	}

}
