<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnRepository;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnFabricationRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\RqYarnFabricationRequest;

class RqYarnFabricationController extends Controller {

    private $rqyarn;
    private $rqyarnfabrication;
    private $plknit;
    private $plknititem;
    private $poknitservice;
    private $company;
    private $buyer;
    private $autoyarn;
    private $gmtspart;


    public function __construct(
        RqYarnRepository $rqyarn,
        RqYarnFabricationRepository $rqyarnfabrication,
        PlKnitRepository $plknit,
        PlKnitItemRepository $plknititem,
        PoKnitServiceRepository $poknitservice,
        CompanyRepository $company, 
        BuyerRepository $buyer,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart
    ) {
        $this->rqyarn = $rqyarn;
        $this->rqyarnfabrication = $rqyarnfabrication;
        $this->plknit = $plknit;
        $this->plknititem = $plknititem;
        $this->poknitservice = $poknitservice;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;

/*  
        $this->middleware('auth');
        $this->middleware('permission:view.rqyarnfabrications',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.rqyarnfabrications', ['only' => ['store']]);
        $this->middleware('permission:edit.rqyarnfabrications',   ['only' => ['update']]);
        $this->middleware('permission:delete.rqyarnfabrications', ['only' => ['destroy']]);

        */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $rqyarn=$this->rqyarn->find(request('rq_yarn_id',0));
         
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
        return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
        return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
        'autoyarns.*',
        'constructions.name',
        'compositions.name as composition_name',
        'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
        $fabricDescriptionArr[$row->id]=$row->name;
        $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $rows=null;
        if($rqyarn->rq_against_id==50)
        {
        $rows=$this->rqyarn
        ->join('rq_yarn_fabrications', function($join)  {
            $join->on('rq_yarn_fabrications.rq_yarn_id', '=', 'rq_yarns.id');
        })
        ->join('pl_knit_items', function($join)  {
            $join->on('pl_knit_items.id', '=', 'rq_yarn_fabrications.pl_knit_item_id');
        })
        ->join('pl_knits', function($join)  {
            $join->on('pl_knit_items.pl_knit_id', '=', 'pl_knits.id');
        })
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','pl_knit_items.machine_id');
        })
        ->join('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'pl_knit_items.colorrange_id');
        })
        ->join('so_knit_refs', function($join)  {
            $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
        })
        ->leftJoin('so_knit_po_items', function($join)  {
            $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
        })
        ->leftJoin('po_knit_service_item_qties',function($join){
              $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
        })
        ->leftJoin('sales_orders',function($join){
              $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
              $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
              $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('po_knit_service_items',function($join){
                 $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                 ->whereNull('po_knit_service_items.deleted_at');
        })
        ->leftJoin('po_knit_services',function($join){
                 $join->on('po_knit_services.id','=','po_knit_service_items.po_knit_service_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
                 $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
        })
        ->leftJoin('budget_fabrics',function($join){
             $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
             $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('so_knit_items', function($join)  {
            $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
        })
        ->leftJoin('so_knits', function($join)  {
            $join->on('so_knits.id', '=', 'so_knit_refs.so_knit_id');
        })
        
        ->where([['rq_yarns.id','=',request('rq_yarn_id',0)]])
        ->orderBy('pl_knit_items.id','desc')
        ->get([
            'rq_yarn_fabrications.*',
            //'pl_knit_items.*',
            'pl_knit_items.colorrange_id',
            'pl_knit_items.gsm_weight', 
            'pl_knit_items.dia', 
            'pl_knit_items.measurment', 
            'pl_knit_items.stitch_length', 
            'pl_knit_items.spandex_stitch_length', 
            'pl_knit_items.draft_ratio', 
            'pl_knit_items.no_of_feeder', 'pl_knit_items.machine_id', 
            'pl_knit_items.machine_gg',
            'pl_knit_items.id as pl_knit_item_id',
            'pl_knits.pl_no',
            'colorranges.name as colorrange_name',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'so_knit_items.autoyarn_id as so_autoyarn_id',
            'so_knit_items.gmtspart_id as so_gmtspart_id',
            'so_knit_items.fabric_look_id as so_fabric_look_id',
            'so_knit_items.fabric_shape_id as so_fabric_shape_id',
            'asset_quantity_costs.custom_no as machine_no',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            'so_knit_items.gmt_sale_order_no',
            'so_knit_items.gmt_style_ref',
        ])
        ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->so_autoyarn_id];
             $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->so_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->so_fabric_shape_id];

            $rows->fabric_look_id=$rows->fabric_look_id?$rows->fabric_look_id:$rows->so_fabric_look_id;
            $rows->fabric_shape_id=$rows->fabric_shape_id?$rows->fabric_shape_id:$rows->so_fabric_shape_id;

            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->so_gmtspart_id];
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            return $rows;
        });

        }
        else if($rqyarn->rq_against_id==4){
            $rows= $this->rqyarn
            ->join('rq_yarn_fabrications', function($join)  {
            $join->on('rq_yarn_fabrications.rq_yarn_id', '=', 'rq_yarns.id');
            })
            ->leftJoin('po_knit_service_item_qties',function($join){
            $join->on('po_knit_service_item_qties.id','=','rq_yarn_fabrications.po_knit_service_item_qty_id');
            })
            ->leftJoin('sales_orders',function($join){
            $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
            $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
            ->whereNull('po_knit_service_items.deleted_at');
            })
            ->leftJoin('po_knit_services',function($join){
            $join->on('po_knit_services.id','=','po_knit_service_items.po_knit_service_id');
            })
            ->leftJoin('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'po_knit_service_item_qties.colorrange_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
            $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
            $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
            $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })

            ->where([['rq_yarns.id','=',request('rq_yarn_id',0)]])
            //->orderBy('pl_knit_items.id','desc')
            ->get([
            'rq_yarn_fabrications.*',
            'po_knit_service_item_qties.colorrange_id',
            'po_knit_service_item_qties.pl_gsm_weight as gsm_weight', 
            'po_knit_service_item_qties.dia', 
            'po_knit_service_item_qties.measurment', 
            'po_knit_service_item_qties.pl_stitch_length as stitch_length', 
            'po_knit_service_item_qties.pl_spandex_stitch_length', 
            'po_knit_service_item_qties.pl_draft_ratio', 
            'po_knit_service_item_qties.pl_machine_gg',
            'po_knit_service_item_qties.id as po_knit_service_item_qty_id',
            'po_knit_services.po_no as pl_no',
            'colorranges.name as colorrange_name',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'style_fabrications.autoyarn_id as so_autoyarn_id',
            'style_fabrications.gmtspart_id as so_gmtspart_id',
            'style_fabrications.fabric_look_id as so_fabric_look_id',
            'style_fabrications.fabric_shape_id as so_fabric_shape_id',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            ])
            ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:null;
             $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:null;
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:null;

            $rows->fabric_look_id=$rows->fabric_look_id?$rows->fabric_look_id:null;
            $rows->fabric_shape_id=$rows->fabric_shape_id?$rows->fabric_shape_id:null;

            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:null;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:null;
            $rows->order_no=$rows->sale_order_no?$rows->sale_order_no:null;
            return $rows;
            }); 
        }


        
        //return $rows;
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        return Template::LoadView('Subcontract.Kniting.RqYarn',['company'=>$company]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RqYarnFabricationRequest $request) {
        $master=$this->rqyarn->find($request->rq_yarn_id);
        if($master->approved_by && $master->approved_at){
            return response()->json(array('success' => false,'message' => 'It is Approved,So Save/Update Not Possible'),200);
        }
        $rqyarnfabrication = $this->rqyarnfabrication->create(['rq_yarn_id'=>$request->rq_yarn_id,'pl_knit_item_id'=>$request->pl_knit_item_id,'po_knit_service_item_qty_id'=>$request->po_knit_service_item_qty_id,'remarks'=>$request->remarks]);
        if($rqyarnfabrication){
            return response()->json(array('success' => true,'id' =>  $rqyarnfabrication->id,'rq_yarn_id'=>$request->rq_yarn_id,'message' => 'Save Successfully'),200);
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
        $rqyarnfabrication = $this->rqyarnfabrication->find($id);
        $rqyarn=$this->rqyarn->find($rqyarnfabrication->rq_yarn_id);

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
        return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
        return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
        'autoyarns.*',
        'constructions.name',
        'compositions.name as composition_name',
        'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
        $fabricDescriptionArr[$row->id]=$row->name;
        $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }
        $rows=null;
        if($rqyarn->rq_against_id==50){
        $rows=$this->rqyarnfabrication
        ->join('rq_yarns', function($join)  {
            $join->on('rq_yarn_fabrications.rq_yarn_id', '=', 'rq_yarns.id');
        })
        /*->join('rq_yarn_fabrications', function($join)  {
            $join->on('rq_yarn_fabrications.rq_yarn_id', '=', 'rq_yarns.id');
        })*/
        ->join('pl_knit_items', function($join)  {
            $join->on('pl_knit_items.id', '=', 'rq_yarn_fabrications.pl_knit_item_id');
        })
        ->join('pl_knits', function($join)  {
            $join->on('pl_knit_items.pl_knit_id', '=', 'pl_knits.id');
        })
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','pl_knit_items.machine_id');
        })
        ->join('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'pl_knit_items.colorrange_id');
        })
        ->join('so_knit_refs', function($join)  {
            $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
        })
        ->leftJoin('so_knit_po_items', function($join)  {
            $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
        })
        ->leftJoin('po_knit_service_item_qties',function($join){
              $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
        })
        ->leftJoin('sales_orders',function($join){
              $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
              $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
              $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('buyers',function($join){
              $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('po_knit_service_items',function($join){
                 $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                 ->whereNull('po_knit_service_items.deleted_at');
        })
        ->leftJoin('po_knit_services',function($join){
                 $join->on('po_knit_services.id','=','po_knit_service_items.po_knit_service_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
                 $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
        })
        ->leftJoin('budget_fabrics',function($join){
             $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
             $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('so_knit_items', function($join)  {
            $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
        })
        ->leftJoin('so_knits', function($join)  {
            $join->on('so_knits.id', '=', 'so_knit_refs.so_knit_id');
        })
        
        ->where([['rq_yarn_fabrications.id','=',$id]])
        ->orderBy('pl_knit_items.id','desc')
        ->get([
            'rq_yarn_fabrications.*',
            //'pl_knit_items.*',
            'pl_knit_items.colorrange_id',
            'pl_knit_items.gsm_weight', 
            'pl_knit_items.dia', 
            'pl_knit_items.measurment', 
            'pl_knit_items.stitch_length', 
            'pl_knit_items.spandex_stitch_length', 
            'pl_knit_items.draft_ratio', 
            'pl_knit_items.no_of_feeder', 'pl_knit_items.machine_id', 
            'pl_knit_items.machine_gg',
            'pl_knit_items.id as pl_knit_item_id',
            'pl_knit_items.qty as plan_qty',
            'pl_knit_items.gsm_weight',
            'pl_knits.pl_no',
            'colorranges.name as colorrange_name',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'so_knit_items.autoyarn_id as so_autoyarn_id',
            'so_knit_items.gmtspart_id as so_gmtspart_id',
            'so_knit_items.fabric_look_id as so_fabric_look_id',
            'so_knit_items.fabric_shape_id as so_fabric_shape_id',
            'asset_quantity_costs.custom_no as machine_no',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            'so_knit_items.gmt_sale_order_no',
            'so_knit_items.gmt_style_ref',
            'buyers.name as buyer_name',
        ])
        ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rows->composition=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->so_autoyarn_id];
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->so_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->so_fabric_shape_id];
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->so_gmtspart_id];

            $rows->fabrication=$rows->gmtspart." ".$rows->composition." ".$rows->fabriclooks." ".$rows->fabricshape." ".$rows->gsm_weight;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            return $rows;
        })->first();
        }

        else if($rqyarn->rq_against_id==4){

            //$rows= $this->rqyarn
            $rows=$this->rqyarnfabrication
            ->join('rq_yarns', function($join)  {
            $join->on('rq_yarn_fabrications.rq_yarn_id', '=', 'rq_yarns.id');
            })

            /*->join('rq_yarn_fabrications', function($join)  {
            $join->on('rq_yarn_fabrications.rq_yarn_id', '=', 'rq_yarns.id');
            })*/
            ->leftJoin('po_knit_service_item_qties',function($join){
            $join->on('po_knit_service_item_qties.id','=','rq_yarn_fabrications.po_knit_service_item_qty_id');
            })
            ->leftJoin('sales_orders',function($join){
            $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
            $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
            ->whereNull('po_knit_service_items.deleted_at');
            })
            ->leftJoin('po_knit_services',function($join){
            $join->on('po_knit_services.id','=','po_knit_service_items.po_knit_service_id');
            })
            ->leftJoin('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'po_knit_service_item_qties.colorrange_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
            $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
            $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
            $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })

            ->where([['rq_yarn_fabrications.id','=',$id]])
            //->orderBy('pl_knit_items.id','desc')
            ->get([
            'rq_yarn_fabrications.*',
            'po_knit_service_item_qties.qty as plan_qty',
            'po_knit_service_item_qties.colorrange_id',
            'po_knit_service_item_qties.pl_gsm_weight as gsm_weight', 
            'po_knit_service_item_qties.dia', 
            'po_knit_service_item_qties.measurment', 
            'po_knit_service_item_qties.pl_stitch_length as stitch_length', 
            'po_knit_service_item_qties.pl_spandex_stitch_length', 
            'po_knit_service_item_qties.pl_draft_ratio', 
            'po_knit_service_item_qties.pl_machine_gg',
            'po_knit_service_item_qties.id as po_knit_service_item_qty_id',
            'po_knit_services.po_no as pl_no',
            'colorranges.name as colorrange_name',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'style_fabrications.autoyarn_id as so_autoyarn_id',
            'style_fabrications.gmtspart_id as so_gmtspart_id',
            'style_fabrications.fabric_look_id as so_fabric_look_id',
            'style_fabrications.fabric_shape_id as so_fabric_shape_id',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            ])
            ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:null;
             $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:null;
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:null;

            $rows->fabric_look_id=$rows->fabric_look_id?$rows->fabric_look_id:null;
            $rows->fabric_shape_id=$rows->fabric_shape_id?$rows->fabric_shape_id:null;

            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:null;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:null;
            $rows->order_no=$rows->sale_order_no?$rows->sale_order_no:null;
            return $rows;
            })->first(); 
        }

        $row ['fromData'] = $rows;
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
    public function update(RqYarnFabricationRequest $request, $id) {
        $master=$this->rqyarn->find($request->rq_yarn_id);
        if($master->approved_by && $master->approved_at){
            return response()->json(array('success' => false,'message' => 'It is Approved,So Update Not Possible'),200);
        }
        $rqyarnfabrication=$this->rqyarnfabrication->update($id,$request->except(['id','rq_yarn_id','pl_knit_item_id','po_knit_service_item_qty_id','pl_no']));
        if($rqyarnfabrication){
            return response()->json(array('success' => true,'id' => $id,'rq_yarn_id'=>$request->rq_yarn_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->rqyarnfabrication->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
    private function getPlan()
    {
        $rqyarn=$this->rqyarn->find(request('rq_yarn_id',0));
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
        return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
        return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
        'autoyarns.*',
        'constructions.name',
        'compositions.name as composition_name',
        'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
        $fabricDescriptionArr[$row->id]=$row->name;
        $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }


        $rows=$this->plknit
        ->join('pl_knit_items', function($join)  {
            $join->on('pl_knit_items.pl_knit_id', '=', 'pl_knits.id');
        })
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','pl_knit_items.machine_id');
        })
        ->join('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'pl_knit_items.colorrange_id');
        })
        ->join('so_knit_refs', function($join)  {
            $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
        })
        ->join('so_knit_po_items', function($join)  {
            $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
        })
        ->join('po_knit_service_item_qties',function($join){
              $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
        })
        ->join('sales_orders',function($join){
              $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
        })
        ->join('jobs',function($join){
              $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->join('styles',function($join){
              $join->on('styles.id','=','jobs.style_id');
        })
        ->join('buyers',function($join){
              $join->on('buyers.id','=','styles.buyer_id');
        })
        ->join('po_knit_service_items',function($join){
                 $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                 ->whereNull('po_knit_service_items.deleted_at');
        })
        ->join('po_knit_services',function($join){
                 $join->on('po_knit_services.id','=','po_knit_service_items.po_knit_service_id');
        })
        ->join('budget_fabric_prods',function($join){
                 $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
        })
        ->join('budget_fabrics',function($join){
             $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->join('style_fabrications',function($join){
             $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('so_knit_items', function($join)  {
            $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
        })
        ->join('so_knits', function($join)  {
            $join->on('so_knits.id', '=', 'so_knit_refs.so_knit_id');
        })
        ->when(request('buyer_id'), function ($q) {
        return $q->where('so_knits.buyer_id', '=',request('buyer_id', 0));
        })
        ->when(request('pl_no'), function ($q) {
        return $q->where('pl_knits.pl_no', '=',request('pl_no', 0));
        })
        ->when(request('po_no'), function ($q) {
        return $q->where('po_knit_services.po_no', '=',request('po_no', 0));
        })
        ->when(request('dia'), function ($q) {
        return $q->where('pl_knit_items.dia', 'like', '%'.request('dia', 0).'%');
        })
        ->when(request('gsm'), function ($q) {
        return $q->where('pl_knit_items.gsm_weight', 'like', '%'.request('gsm', 0).'%');
        })
        ->where([['pl_knits.supplier_id','=',$rqyarn->supplier_id]])
        ->orderBy('pl_knit_items.id','desc')
        ->get([
            'pl_knit_items.*',
            'pl_knit_items.id as pl_knit_item_id',
            'pl_knit_items.qty as plan_qty',
            'pl_knits.pl_no',
            'colorranges.name as colorrange_name',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'so_knit_items.autoyarn_id as so_autoyarn_id',
            'so_knit_items.gmtspart_id as so_gmtspart_id',
            'so_knit_items.fabric_look_id as so_fabric_look_id',
            'so_knit_items.fabric_shape_id as so_fabric_shape_id',
            'asset_quantity_costs.custom_no as machine_no',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            'so_knit_items.gmt_sale_order_no',
            'so_knit_items.gmt_style_ref',
            'buyers.name as buyer_name'
        ])
        ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rows->composition=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->so_autoyarn_id];

            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;

            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->so_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->so_fabric_shape_id];


            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->so_gmtspart_id];
            $rows->fabrication=$rows->gmtspart." ".$rows->composition." ".$rows->fabriclooks." ".$rows->fabricshape." ".$rows->gsm_weight;

            
            return $rows;
        });
        return $rows;
    }
    private function getPo()
    {
        $rqyarn=$this->rqyarn->find(request('rq_yarn_id',0));

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
        return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
        return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
        'autoyarns.*',
        'constructions.name',
        'compositions.name as composition_name',
        'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
        $fabricDescriptionArr[$row->id]=$row->name;
        $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }



        $rows=$this->poknitservice
        ->join('po_knit_service_items', function($join)  {
            $join->on('po_knit_service_items.po_knit_service_id', '=', 'po_knit_services.id');
            $join->whereNull('po_knit_service_items.deleted_at');
        })
        ->join('po_knit_service_item_qties', function($join)  {
            $join->on('po_knit_service_item_qties.po_knit_service_item_id', '=', 'po_knit_service_items.id');
            $join->whereNull('po_knit_service_item_qties.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
              $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
        })
        ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
        })
        ->join('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
             $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('styles',function($join){
             $join->on('styles.id','=','style_fabrications.style_id');
        })
        ->leftJoin('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'po_knit_service_item_qties.colorrange_id');
        })
        ->when(request('buyer_id'), function ($q) {
        return $q->where('styles.buyer_id', '=',request('buyer_id', 0));
        })
        
        ->when(request('po_no'), function ($q) {
        return $q->where('po_knit_services.po_no', '=',request('po_no', 0));
        })
        ->when(request('dia'), function ($q) {
        return $q->where('po_knit_service_item_qties.dia', 'like', '%'.request('dia', 0).'%');
        })
        ->when(request('gsm'), function ($q) {
        return $q->where('budget_fabrics.gsm_weight', 'like', '%'.request('gsm', 0).'%');
        })
        ->where([['po_knit_services.supplier_id','=',$rqyarn->supplier_id]])
        ->orderBy('po_knit_service_item_qties.id','desc')
        ->get([
            'po_knit_service_item_qties.*',
            'po_knit_service_item_qties.id as po_knit_service_item_qty_id',
            'po_knit_service_item_qties.pl_stitch_length as stitch_length',
            'po_knit_service_item_qties.qty as plan_qty',
            'po_knit_services.po_no as pl_no',
            'colorranges.name as colorrange_name',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'budget_fabrics.gsm_weight',
            'styles.style_ref',
            'sales_orders.sale_order_no'
        ])
        ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rows->composition=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->so_autoyarn_id];

            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;

            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->so_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->so_fabric_shape_id];


            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->so_gmtspart_id];
            $rows->fabrication=$rows->gmtspart." ".$rows->composition." ".$rows->fabriclooks." ".$rows->fabricshape." ".$rows->gsm_weight;
            return $rows;
        });
        return $rows;
    }

    public function getFabrication()
    {
        $rqyarn=$this->rqyarn->find(request('rq_yarn_id',0));
        $rows=null;
        if($rqyarn->rq_against_id==50)
        {
            $rows=$this->getPlan();
        }
        else if($rqyarn->rq_against_id==4)
        {
           $rows=$this->getPo(); 
        }
        
        echo json_encode($rows);
    }
}