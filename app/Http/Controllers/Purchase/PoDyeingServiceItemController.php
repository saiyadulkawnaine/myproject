<?php

namespace App\Http\Controllers\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Repositories\Contracts\Purchase\PoDyeingServiceItemRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PoDyeingServiceItemRequest;

class PoDyeingServiceItemController extends Controller
{
    private $podyeingservice;
    private $podyeingserviceitem;
    private $budgetfabric;
	public function __construct(
        PoDyeingServiceRepository $podyeingservice,
        PoDyeingServiceItemRepository $podyeingserviceitem,
        BudgetFabricRepository $budgetfabric
        )
	{
        $this->podyeingservice = $podyeingservice;
        $this->podyeingserviceitem = $podyeingserviceitem;
		$this->budgetfabric = $budgetfabric;
		$this->middleware('auth');
		$this->middleware('permission:view.podyeingserviceitems',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.podyeingserviceitems', ['only' => ['store']]);
		$this->middleware('permission:edit.podyeingserviceitems',   ['only' => ['update']]);
		$this->middleware('permission:delete.podyeingserviceitems', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
		$materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        $fabricDescription=$this->podyeingservice

        ->join('po_dyeing_service_items',function($join){
        $join->on('po_dyeing_service_items.po_dyeing_service_id','=','po_dyeing_services.id')
        ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        })
        ->join('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
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
        
        ->where([['po_dyeing_services.id','=',request('po_dyeing_service_id',0)]])
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

        $fabrics=$this->podyeingservice
        ->selectRaw('
        jobs.job_no,
        styles.style_ref,
        buyers.name as buyer_name,
        po_dyeing_service_items.id,
        po_dyeing_service_items.qty,
        po_dyeing_service_items.rate,
        po_dyeing_service_items.amount,
        budget_fabric_prods.id as budget_fabric_prod_id,
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
        style_fabrications.dyeing_type_id,
        gmtsparts.name as gmtspart_name,
        item_accounts.item_description,
        uoms.code as uom_name
        ')

        ->join('po_dyeing_service_items',function($join){
        $join->on('po_dyeing_service_items.po_dyeing_service_id','=','po_dyeing_services.id')
        ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        })
        ->join('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
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
        ->join('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
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
        
        
        ->where([['po_dyeing_services.id','=',request('po_dyeing_service_id',0)]])
        ->groupBy([
        'jobs.job_no',
        'styles.style_ref',
        'buyers.name',
        'po_dyeing_service_items.id',
        'po_dyeing_service_items.qty',
        'po_dyeing_service_items.rate',
        'po_dyeing_service_items.amount',
        'budget_fabric_prods.id',
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
        'style_fabrications.dyeing_type_id',
        'gmtsparts.name',
        'item_accounts.item_description',
        'uoms.code'
        ])
        ->get();
        $stylefabrications=array();
        $stylenarrowfabrications=array();
        foreach($fabrics as $row){
            $stylefabrication['id']=    $row->id;
            $stylefabrication['budget_id']= $row->budget_id;
            $stylefabrication['style_ref']= $row->style_ref;
            $stylefabrication['buyer_name']= $row->buyer_name;
            $stylefabrication['style_fabrication_id']=  $row->style_fabrication_id;
            $stylefabrication['style_gmt']= $row->item_description;
            $stylefabrication['gmtspart']=  $row->gmtspart_name;
            $stylefabrication['fabric_description']=    $desDropdown[$row->style_fabrication_id];
            $stylefabrication['uom_name']=  $row->uom_name;
            //$stylefabrication['materialsourcing']=  $materialsourcing[$row->material_source_id];
            $stylefabrication['fabricnature']=  $fabricnature[$row->fabric_nature_id];
            $stylefabrication['fabriclooks']=   $fabriclooks[$row->fabric_look_id];
            $stylefabrication['fabricshape']=   $fabricshape[$row->fabric_shape_id];
            $stylefabrication['gsm_weight']=    $row->gsm_weight;
            $stylefabrication['dyeing_type_id']=  $dyetype[$row->dyeing_type_id];
            //$stylefabrication['supplier_id']=   $row->supplier_id;
            $stylefabrication['qty']=   $row->qty;
            $stylefabrication['rate']=  $row->rate;
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
    public function store(PoDyeingServiceItemRequest $request)
    {
        $approved=$this->podyeingservice->find($request->po_dyeing_service_id);
    	if($approved->approved_at){
    	  return response()->json(array('success' => false,  'message' => 'Approved, Save Or Update not Possible'), 200);
    	}
        
        foreach($request->budget_fabric_prod_id as $index=>$budget_fabric_prod_id){
            if($request->po_dyeing_service_id){
                $podyeingserviceitem = $this->podyeingserviceitem->updateOrCreate(
                ['po_dyeing_service_id' => $request->po_dyeing_service_id,'budget_fabric_prod_id' => $budget_fabric_prod_id],
                ['qty' => '','rate' => '','amount' => '']
                );
            }
        }
        if ($podyeingserviceitem) {
            return response()->json(array('success' => true, 'id' => $podyeingserviceitem->id, 'message' => 'Save Successfully'), 200);
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
    public function update(PoDyeingServiceRequest $request, $id)
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
        if($this->podyeingserviceitem->delete($id)){
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
        ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('production_processes',function($join){
        $join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
        })
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
        ->where([['production_processes.production_area_id','=',20]])
        //->where([['jobs.company_id','=',request('company_id',0)]])
        //->whereIn('style_fabrications.material_source_id', [1,10])
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
        budget_fabric_prods.id,
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
        po_dyeing_service_items.id as po_dyeing_service_item_id,
        suppliers.name as supplier_name,
        buyers.name as buyer_name
        ')
        ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('production_processes',function($join){
        $join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
        })
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
        /* ->join('sales_orders',function($join){
			$join->on('sales_orders.job_id','=','jobs.id');
		}) */
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
        ->leftJoin(\DB::raw("(SELECT budget_fabric_prods.id as budget_fabric_prod_id,sum(po_dyeing_service_items.qty) as cumulative_qty FROM po_dyeing_service_items right join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id   group by budget_fabric_prods.id) cumulatives"), "cumulatives.budget_fabric_prod_id", "=", "budget_fabric_prods.id")
        ->leftJoin('po_dyeing_service_items',function($join){
          $join->on('po_dyeing_service_items.budget_fabric_prod_id','=','budget_fabric_prods.id');
          $join->where([['po_dyeing_service_items.po_dyeing_service_id','=',request('po_dyeing_service_id',0)]]);
        })
        ->when(request('budget_id'), function ($q) {
        return $q->where('budgets.budget_id', '=',request('budget_id', 0));
        })
        ->when(request('job_no'), function ($q) {
        return $q->where('jobs.job_no', '=',request('job_no', 0));
        })
        ->when(request('style_ref'), function ($q) {
        return $q->where('styles.style_ref', 'LIKE',"%".request('style_ref', 0)."%");
        })
        ->where([['jobs.company_id','=',request('company_id',0)]])
        ->where([['production_processes.production_area_id','=',20]])
        //->whereNotNull('budget_approvals.fabricprod_final_approved_by')
        ->get()
        ->map(function ($fabrics) use($desDropdown,$materialsourcing,$fabricnature,$fabriclooks,$fabricshape)
        {
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
            if(!$value->po_dyeing_service_item_id){
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
