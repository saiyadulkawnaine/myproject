<?php

namespace App\Http\Controllers\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Contracts\Purchase\PoEmbServiceItemRepository;
use App\Repositories\Contracts\Bom\BudgetEmbRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PoEmbServiceItemRequest;

class PoEmbServiceItemController extends Controller
{
    private $poembservice;
    private $poembserviceitem;
    private $budgetemb;
	public function __construct(
        PoEmbServiceRepository $poembservice,
        PoEmbServiceItemRepository $poembserviceitem,
        BudgetEmbRepository $budgetemb
        )
	{
        $this->poembservice = $poembservice;
        $this->poembserviceitem = $poembserviceitem;
		$this->budgetemb = $budgetemb;
		$this->middleware('auth');
		$this->middleware('permission:view.poembserviceitems',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.poembserviceitems', ['only' => ['store']]);
		$this->middleware('permission:edit.poembserviceitems',   ['only' => ['update']]);
		$this->middleware('permission:delete.poembserviceitems', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
        
        $fabrics=$this->poembservice
        ->selectRaw('
        jobs.job_no,
        styles.style_ref,
        budget_embs.id as budget_emb_id,
        budget_embs.budget_id,
        po_emb_service_items.qty,
        po_emb_service_items.rate,
        po_emb_service_items.amount,
        style_embelishments.embelishment_size_id,
        embelishments.name as embelishment_name,
        embelishment_types.name as embelishment_type,
        gmtsparts.name as gmtspart_name,
        item_accounts.item_description,
        po_emb_service_items.id,
        buyers.name as buyer_name
        ')
        ->join('po_emb_service_items',function($join){
        $join->on('po_emb_service_items.po_emb_service_id','=','po_emb_services.id')
        ->whereNull('po_emb_service_items.deleted_at');
        })
        ->join('budget_embs',function($join){
        $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
        })
        
        ->join('style_embelishments',function($join){
        $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
        })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
            $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('budgets',function($join){
            $join->on('budgets.id','=','budget_embs.budget_id');
        })
        ->join('jobs',function($join){
            $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.job_id','=','jobs.id');
        })
        ->join('styles', function($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('buyers', function($join) {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->join('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
        })
        ->join('embelishments',function($join){
        $join->on('embelishments.id','=','style_embelishments.embelishment_id');
        })
        ->join('embelishment_types',function($join){
        $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
        })
        
        ->where([['po_emb_services.id','=',request('po_emb_service_id',0)]])
        
        ->groupBy([
            'jobs.job_no',
            'styles.style_ref',
            'budget_embs.id',
            'budget_embs.budget_id',
            'style_embelishments.embelishment_size_id',
            'embelishments.name',
            'embelishment_types.name',
            'po_emb_service_items.qty',
            'po_emb_service_items.rate',
            'po_emb_service_items.amount',
            'gmtsparts.name',
            'item_accounts.item_description',
            'po_emb_service_items.id',
            'buyers.name'
        ])
        ->get()
        ->map(function ($fabrics) use($embelishmentsize)
        {
            $fabrics->embelishment_size = $embelishmentsize[$fabrics->embelishment_size_id];
            return $fabrics;
        });
        echo json_encode($fabrics);
        
        
        
        /*$fabrics=$this->budgetemb
        ->selectRaw('
        jobs.job_no,
        styles.style_ref,
        buyers.name as buyer_name,
        po_emb_service_items.id,
        po_emb_service_items.qty,
        po_emb_service_items.rate,
        po_emb_service_items.amount,
        budget_fabric_prods.id as budget_emb_id,
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
        ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.budget_fabric_id','=','budget_fabrics.id');
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
        
        ->join('po_emb_service_items',function($join){
        $join->on('po_emb_service_items.budget_emb_id','=','budget_fabric_prods.id')
        ->whereNull('po_emb_service_items.deleted_at');
        })
        ->join('po_emb_services',function($join){
        $join->on('po_emb_services.id','=','po_emb_service_items.po_emb_service_id');
        })
        ->where([['po_emb_services.id','=',request('po_emb_service_id',0)]])
        ->groupBy([
        'jobs.job_no',
        'styles.style_ref',
        'buyers.name',
        'po_emb_service_items.id',
        'po_emb_service_items.qty',
        'po_emb_service_items.rate',
        'po_emb_service_items.amount',
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
            $stylefabrication['dyeing_type_id']=  $dyetype[$row->dyeing_type_id];
            $stylefabrication['fabricnature']=  $fabricnature[$row->fabric_nature_id];
            $stylefabrication['fabriclooks']=   $fabriclooks[$row->fabric_look_id];
            $stylefabrication['fabricshape']=   $fabricshape[$row->fabric_shape_id];
            $stylefabrication['gsm_weight']=    $row->gsm_weight;
           //$stylefabrication['supplier_id']=   $row->supplier_id;
            $stylefabrication['qty']=   $row->qty;
            $stylefabrication['rate']=  $row->rate;
            $stylefabrication['amount']=$row->amount;
            array_push($stylefabrications,$stylefabrication);
        }
        echo json_encode($stylefabrications);*/
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
    public function store(PoEmbServiceItemRequest $request)
    {
        $poembservice=$this->poembservice->find($request->po_emb_service_id);
        if($poembservice->approved_at){
            return response()->json(array('success' => false,  'message' => 'Embelishment Work Order is Approved, Save or Update not Possible'), 200);
        }else {
            foreach($request->budget_emb_id as $index=>$budget_emb_id){
                if($request->po_emb_service_id){
                    $poembserviceitem = $this->poembserviceitem->updateOrCreate(
                    ['po_emb_service_id' => $request->po_emb_service_id,'budget_emb_id' => $budget_emb_id],
                    ['qty' => '','rate' => '','amount' => '']
                    );
                }
            }
            if ($poembserviceitem) {
                return response()->json(array('success' => true, 'id' => $poembserviceitem->id, 'message' => 'Save Successfully'), 200);
            }
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
    public function update(PoEmbServiceRequest $request, $id)
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
        return response()->json(array('success' => false, 'message' => 'You have no Delete Permission'), 200);

        if($this->poembserviceitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
        else{
             return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
        }
    }

    public function importFabric()
    {
        
        $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');


        /*$fabricDescription=$this->budgetemb
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
        ->where([['production_processes.production_area_id','=',10]])
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
        }*/
        
        $production_area_id=request('production_area_id', 0);
        $fabrics=$this->budgetemb
        ->selectRaw('
        jobs.job_no,
        styles.style_ref,
        budget_embs.id,
        budget_embs.budget_id,
        budget_embs.cons as qty,
        budget_embs.rate,
        budget_embs.amount,
        style_embelishments.embelishment_size_id,
        embelishments.name as embelishment_name,
        embelishment_types.name as embelishment_type,
        
        gmtsparts.name as gmtspart_name,
        item_accounts.item_description,
        cumulatives.cumulative_qty,
        po_emb_service_items.id as po_emb_service_item_id,
        buyers.name as buyer_name
        ')
        
        ->join('style_embelishments',function($join){
        $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
        })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
            $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('budgets',function($join){
            $join->on('budgets.id','=','budget_embs.budget_id');
        })
        //->join('budget_approvals',function($join){
        //    $join->on('budgets.id','=','budget_approvals.budget_id');
        //})
        ->join('jobs',function($join){
            $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('sales_orders',function($join){
			$join->on('sales_orders.job_id','=','jobs.id');
		})
        ->join('styles', function($join) {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('buyers', function($join) {
            $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->join('gmtsparts',function($join){
            $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
        })
        ->join('embelishments',function($join){
            $join->on('embelishments.id','=','style_embelishments.embelishment_id');
        })
        ->join('embelishment_types',function($join){
            $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
        })
        ->join('production_processes',function($join){
            $join->on('production_processes.id','=','embelishments.production_process_id');
        })
        ->leftJoin(\DB::raw("(SELECT budget_embs.id as budget_emb_id,sum(po_emb_service_items.qty) as cumulative_qty FROM po_emb_service_items right join budget_embs on budget_embs.id = po_emb_service_items.budget_emb_id   group by budget_embs.id) cumulatives"), "cumulatives.budget_emb_id", "=", "budget_embs.id")
        ->leftJoin('po_emb_service_items',function($join){
          $join->on('po_emb_service_items.budget_emb_id','=','budget_embs.id');
          $join->where([['po_emb_service_items.po_emb_service_id','=',request('po_emb_service_id',0)]]);
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
        ->where([['production_processes.production_area_id','=',$production_area_id]])
        //->whereNotNull('budget_approvals.embel_final_approved_at')
        ->groupBy([
        'jobs.job_no',
        'styles.style_ref',
        'budget_embs.id',
        'budget_embs.budget_id',
        'style_embelishments.embelishment_size_id',
        'embelishments.name',
        'embelishment_types.name',
        'budget_embs.cons',
        'budget_embs.rate',
        'budget_embs.amount',
        'gmtsparts.name',
        'item_accounts.item_description',
        'cumulatives.cumulative_qty',
        'po_emb_service_items.id',
        'buyers.name'
        ])
        ->get()
        ->map(function ($fabrics) use($embelishmentsize)
        {
            $fabrics->prev_po_qty = $fabrics->cumulative_qty-$fabrics->qty;
            $fabrics->balance = $fabrics->fabric_cons-$fabrics->prev_po_qty;
            $fabrics->embelishment_size = $embelishmentsize[$fabrics->embelishment_size_id];
            return $fabrics;
        });
        
        $notsaved = $fabrics->filter(function ($value) {
            if(!$value->po_emb_service_item_id){
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
