<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\Purchase\PoFabricItemRepository;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PoFabricItemRequest;


class PoFabricItemController extends Controller
{
   private $pofabric;
   private $pofabricitem;
   private $budgetfabric;

	public function __construct(
		PoFabricRepository $pofabric,
		PoFabricItemRepository $pofabricitem,
		BudgetFabricRepository $budgetfabric
	)
	{
        $this->pofabric = $pofabric;
        $this->pofabricitem = $pofabricitem;
		$this->budgetfabric = $budgetfabric;
		// $this->middleware('auth');
		// $this->middleware('permission:view.pofabricitems',   ['only' => ['create', 'index','show']]);
		// $this->middleware('permission:create.pofabricitems', ['only' => ['store']]);
		// $this->middleware('permission:edit.pofabricitems',   ['only' => ['update']]);
		// $this->middleware('permission:delete.pofabricitems', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
		$fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		$fabricDescription=$this->budgetfabric
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->join('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->join('budgets',function($join){
		$join->on('budgets.id','=','budget_fabrics.budget_id');
		})
		->join('jobs',function($join){
		$join->on('jobs.id','=','budgets.job_id');
		})
		->join('styles', function($join) {
		$join->on('styles.id', '=', 'jobs.style_id');
		})
		->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
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
		->join('po_fabric_items',function($join){
		$join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id')
		->whereNull('po_fabric_items.deleted_at');
		})
		->join('po_fabrics',function($join){
		$join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
		})
		->where([['po_fabrics.id','=',request('po_fabric_id',0)]])
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
		
		
		
		$fabrics=$this->budgetfabric
		->selectRaw('
		jobs.job_no,
		styles.style_ref,
		buyers.name as buyer_name,
		po_fabric_items.id,
		po_fabric_items.qty,
		po_fabric_items.rate,
		po_fabric_items.amount,
		budget_fabrics.id as budget_fabric_id,
		budget_fabrics.budget_id,
		budget_fabrics.style_fabrication_id,
		budget_fabrics.gsm_weight,
		
		style_fabrications.fabric_nature_id,
		style_fabrications.gmtspart_id,
		style_fabrications.autoyarn_id,
		style_fabrications.fabric_look_id,
		style_fabrications.material_source_id,
		style_fabrications.is_stripe,
		style_fabrications.fabric_shape_id,
		style_fabrications.uom_id,
		style_fabrications.is_narrow,
		gmtsparts.name as gmtspart_name,
		item_accounts.item_description,
		uoms.code as uom_name
		')
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->join('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->join('budgets',function($join){
		$join->on('budgets.id','=','budget_fabrics.budget_id');
		})
		->join('jobs',function($join){
		$join->on('jobs.id','=','budgets.job_id');
		})
		->join('styles', function($join) {
		$join->on('styles.id', '=', 'jobs.style_id');
		})
		->join('buyers', function($join) {
		$join->on('buyers.id', '=', 'styles.buyer_id');
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
		
		->join('po_fabric_items',function($join){
		$join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id')
		->whereNull('po_fabric_items.deleted_at');
		})
		->join('po_fabrics',function($join){
		$join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
		})
		->where([['po_fabrics.id','=',request('po_fabric_id',0)]])
		->orderBy('po_fabric_items.id','desc')
		->groupBy([
		'jobs.job_no',
		'styles.style_ref',
		'buyers.name',
		'po_fabric_items.id',
		'po_fabric_items.qty',
		'po_fabric_items.rate',
		'po_fabric_items.amount',
		'budget_fabrics.id',
		'budget_fabrics.budget_id',
		'budget_fabrics.style_fabrication_id',
		'budget_fabrics.gsm_weight',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.gmtspart_id',
		'style_fabrications.autoyarn_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.material_source_id',
		'style_fabrications.is_stripe',
		'style_fabrications.fabric_shape_id',
		'style_fabrications.uom_id',
		'style_fabrications.is_narrow',
		'gmtsparts.name',
		'item_accounts.item_description',
		'uoms.code'
		])
		->get();
		$stylefabrications=array();
		$stylenarrowfabrications=array();
		foreach($fabrics as $row){
			$stylefabrication['id']=	$row->id;
			$stylefabrication['budget_id']=	$row->budget_id;
			$stylefabrication['style_fabrication_id']=	$row->style_fabrication_id;
			$stylefabrication['style_gmt']=	$row->item_description;
			$stylefabrication['style_ref']=	$row->style_ref;
			$stylefabrication['buyer_name']=	$row->buyer_name;
			$stylefabrication['gmtspart']=	$row->gmtspart_name;
			$stylefabrication['fabric_description']=	$desDropdown[$row->style_fabrication_id];
			$stylefabrication['uom_name']=	$row->uom_name;
			$stylefabrication['materialsourcing']=	$materialsourcing[$row->material_source_id];
			$stylefabrication['fabricnature']=	$fabricnature[$row->fabric_nature_id];
			$stylefabrication['fabriclooks']=	$fabriclooks[$row->fabric_look_id];
			$stylefabrication['fabricshape']=	$fabricshape[$row->fabric_shape_id];
			$stylefabrication['gsm_weight']=	$row->gsm_weight;
			$stylefabrication['supplier_id']=	$row->supplier_id;
			$stylefabrication['qty']=	$row->qty;
			$stylefabrication['rate']=	$row->rate;
			$stylefabrication['amount']=$row->amount;
			array_push($stylefabrications,$stylefabrication);
		}
		echo json_encode($stylefabrications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoFabricItemRequest $request)
    {
    	$approved=$this->pofabric->find($request->po_fabric_id);
    	if($approved->approved_at){
    	  return response()->json(array('success' => false,  'message' => 'Approved, Save Or Update not Possible'), 200);
    	}
		foreach($request->budget_fabric_id as $index=>$budget_fabric_id){
			if($request->po_fabric_id){
				$pofabricitem = $this->pofabricitem->updateOrCreate(
				['po_fabric_id' => $request->po_fabric_id,'budget_fabric_id' => $budget_fabric_id],
				['qty' => '','rate' => '','amount' => '']
				);
			}
		}
		if ($pofabricitem) {
			return response()->json(array('success' => true, 'id' => $pofabricitem->id, 'message' => 'Save Successfully'), 200);
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PurFabricRequest $request, $id)
    {
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->pofabricitem->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
		else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }
	public function importFabric()
    {
		$materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
		$fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		$fabricDescription=$this->budgetfabric
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->join('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->join('budgets',function($join){
		$join->on('budgets.id','=','budget_fabrics.budget_id');
		})
		->join('jobs',function($join){
		$join->on('jobs.id','=','budgets.job_id');
		})
		->join('styles', function($join) {
		$join->on('styles.id', '=', 'jobs.style_id');
		})
		->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
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
		->where([['jobs.company_id','=',request('company_id',0)]])
		->whereIn('style_fabrications.material_source_id', [1,10])
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
		
		
		
		$fabrics=$this->budgetfabric
		->selectRaw('
		jobs.job_no,
		styles.style_ref,
		buyers.name as buyer_name,
		budget_fabrics.id,
		budget_fabrics.budget_id,
		budget_fabrics.style_fabrication_id,
		budget_fabrics.gsm_weight,
		budget_fabrics.fabric_cons,
		budget_fabrics.rate,
		budget_fabrics.amount,
		style_fabrications.fabric_nature_id,
		style_fabrications.gmtspart_id,
		style_fabrications.autoyarn_id,
		style_fabrications.fabric_look_id,
		style_fabrications.material_source_id,
		style_fabrications.is_stripe,
		style_fabrications.fabric_shape_id,
		style_fabrications.uom_id,
		style_fabrications.is_narrow,
		gmtsparts.name as gmtspart_name,
		item_accounts.item_description,
		uoms.code as uom_name,
		cumulatives.cumulative_qty,
		pur_fabrics.id as pur_fabric_id,
		suppliers.name as supplier_name
		')
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->join('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->join('budgets',function($join){
		$join->on('budgets.id','=','budget_fabrics.budget_id');
		})
		//->join('budget_approvals',function($join){
		//$join->on('budgets.id','=','budget_approvals.budget_id');
		//})
		->join('jobs',function($join){
		$join->on('jobs.id','=','budgets.job_id');
		})
		->join('styles', function($join) {
		$join->on('styles.id', '=', 'jobs.style_id');
		})
		->join('buyers', function($join) {
		$join->on('buyers.id', '=', 'styles.buyer_id');
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
		->leftJoin('suppliers',function($join){
		$join->on('suppliers.id','=','budget_fabrics.supplier_id');
		})
		->leftJoin(\DB::raw("(SELECT budget_fabrics.id as budget_fabric_id,sum(pur_fabrics.qty) as cumulative_qty FROM pur_fabrics right join budget_fabrics on budget_fabrics.id = pur_fabrics.budget_fabric_id   group by budget_fabrics.id) cumulatives"), "cumulatives.budget_fabric_id", "=", "budget_fabrics.id")
		->leftJoin('pur_fabrics',function($join){
          $join->on('pur_fabrics.budget_fabric_id','=','budget_fabrics.id');
		  $join->where([['pur_fabrics.purchase_order_id','=',request('purchase_order_id',0)]]);
        })
		->where([['jobs.company_id','=',request('company_id',0)]])
		->whereIn('style_fabrications.material_source_id', [1])
		//->whereNotNull('budget_approvals.fabric_final_approved_by')
		->get()
		->map(function ($fabrics) use($desDropdown,$materialsourcing,$fabricnature,$fabriclooks,$fabricshape)  {
		$fabrics->prev_po_qty = $fabrics->cumulative_qty-$fabrics->qty;
		$fabrics->balance = $fabrics->fabric_cons-$fabrics->prev_po_qty;
		$fabrics->fabric_description = $desDropdown[$fabrics->style_fabrication_id];
		$fabrics->materialsourcing = $materialsourcing[$fabrics->material_source_id];
		$fabrics->fabricnature = $fabricnature[$fabrics->fabric_nature_id];
		$fabrics->fabriclooks = $fabriclooks[$fabrics->fabric_look_id];
		$fabrics->fabricshape = $fabricshape[$fabrics->fabric_shape_id];
		return $fabrics;
		});
		
		$notsaved = $fabrics->filter(function ($value) {
			if(!$value->pur_fabric_id){
				return $value;
			}
		});
		$stylefabrications=array();
		foreach($notsaved as $row){
			array_push($stylefabrications,$row);
		}
		echo json_encode($stylefabrications);
    }
}
