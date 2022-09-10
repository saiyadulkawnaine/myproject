<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomFabricRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRefRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingBomFabricRequest;

class SoDyeingBomFabricController extends Controller {

   
    private $sodyeingbom;
    private $sodyeingbomfabric;
    private $sodyeing;
    private $sodyeingref;
    private $sodyeingitem;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;


    public function __construct(
      SoDyeingBomRepository $sodyeingbom,
      SoDyeingBomFabricRepository $sodyeingbomfabric,
      SoDyeingRepository $sodyeing, 
      SoDyeingRefRepository $sodyeingref, 
      SoDyeingItemRepository $sodyeingitem, 
      AutoyarnRepository $autoyarn,
      GmtspartRepository $gmtspart,
      UomRepository $uom,
      ColorrangeRepository $colorrange,
      ColorRepository $color
    ) {
        $this->sodyeingbom = $sodyeingbom;
        $this->sodyeingbomfabric = $sodyeingbomfabric;
        $this->sodyeing = $sodyeing;
        $this->sodyeingref = $sodyeingref;
        $this->sodyeingitem = $sodyeingitem;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->middleware('auth');
      
        //$this->middleware('permission:view.sodyeingbomfabrics',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingbomfabrics', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingbomfabrics',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingbomfabrics', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->sodyeingbom
        ->join('so_dyeing_bom_fabrics',function($join){
        $join->on('so_dyeing_bom_fabrics.so_dyeing_bom_id','=','so_dyeing_boms.id');
        })
        ->join('so_dyeing_refs',function($join){
        $join->on('so_dyeing_refs.id','=','so_dyeing_bom_fabrics.so_dyeing_ref_id');
        })
         ->join('so_dyeings',function($join){
        $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_pos',function($join){
        $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_po_items',function($join){
        $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('po_dyeing_service_item_qties',function($join){
        $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
        })
        // ->leftJoin('budget_fabric_prod_cons',function($join){
        // $join->on('po_dyeing_service_item_qties.budget_fabric_prod_con_id','=','budget_fabric_prod_cons.id');
        // })
        ->leftJoin('po_dyeing_service_items',function($join){
        $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
        ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        //$join->on('budget_fabric_prod_cons.budget_fabric_prod_id','=','budget_fabric_prods.id');
        })
        ->leftJoin('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->leftJoin('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->leftJoin('so_dyeing_items',function($join){
        $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('buyers as gmt_buyer',function($join){
        $join->on('gmt_buyer.id','=','so_dyeing_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->leftJoin('uoms as so_uoms',function($join){
        $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
        })
        ->leftJoin('colors as so_color',function($join){
        $join->on('so_color.id','=','so_dyeing_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
        $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
        })
        ->where([['so_dyeing_boms.id','=',request('so_dyeing_bom_id',0)]])
        ->selectRaw('
          so_dyeing_bom_fabrics.id,
          so_dyeing_bom_fabrics.so_dyeing_bom_id,
          so_dyeing_bom_fabrics.liqure_ratio,
          so_dyeing_bom_fabrics.liqure_wgt,
          so_dyeing_refs.id as so_dyeing_ref_id,
          so_dyeing_refs.so_dyeing_id,
          constructions.name as construction_name,
          
          style_fabrications.autoyarn_id,
          style_fabrications.fabric_look_id,
          style_fabrications.fabric_shape_id,
          style_fabrications.gmtspart_id,
          style_fabrications.dyeing_type_id,
          budget_fabrics.gsm_weight,
          po_dyeing_service_item_qties.fabric_color_id,
          po_dyeing_service_item_qties.budget_fabric_prod_con_id,
          po_dyeing_service_item_qties.colorrange_id,
          po_dyeing_service_item_qties.qty,
          po_dyeing_service_item_qties.pcs_qty,
          po_dyeing_service_item_qties.rate,
          po_dyeing_service_item_qties.amount,
          so_dyeing_items.autoyarn_id as c_autoyarn_id,
          so_dyeing_items.fabric_look_id as c_fabric_look_id,
          so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
          so_dyeing_items.gmtspart_id as c_gmtspart_id,
          so_dyeing_items.gsm_weight as c_gsm_weight,
          so_dyeing_items.fabric_color_id as c_fabric_color_id,
          so_dyeing_items.colorrange_id as c_colorrange_id,
          so_dyeing_items.qty as c_qty,
          so_dyeing_items.rate as c_rate,
          so_dyeing_items.amount as c_amount,
          so_dyeing_items.dyeing_type_id as c_dyeing_type_id,
          styles.style_ref,
          sales_orders.sale_order_no,
          so_dyeing_items.gmt_style_ref,
          so_dyeing_items.gmt_sale_order_no,
          buyers.name as buyer_name,
          gmt_buyer.name as gmt_buyer_name,
          uoms.code as uom_name,
          so_uoms.code as so_uom_name
          '
        )
        ->orderBy('so_dyeing_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
            $rows->construction_name=$rows->autoyarn_id?$fabricDescriptionArr[$rows->autoyarn_id]:$fabricDescriptionArr[$rows->c_autoyarn_id];
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
            $rows->uom_id=$rows->uom_id?$uom[$rows->uom_id]:'';
            $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
            $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:$color[$rows->c_fabric_color_id];
            $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];
            $rows->qty=$rows->qty?$rows->qty:$rows->c_qty;
            $rows->pcs_qty=$rows->pcs_qty;
            $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
            $rows->order_val=$rows->amount?$rows->amount:$rows->c_amount;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            $rows->uom_name=$rows->uom_name?$rows->uom_name:$rows->so_uom_name;
            $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:$dyetype[$rows->c_dyeing_type_id];
            $rows->qty=number_format($rows->qty,2,'.',',');
            $rows->pcs_qty=number_format($rows->pcs_qty,0,'.',',');
            $rows->order_val=number_format($rows->order_val,2,'.',','); 
            return $rows;
        });
        echo json_encode($rows);
      
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoDyeingBomFabricRequest $request) {
        $sodyeingbomfabric=$this->sodyeingbomfabric->create([
        'so_dyeing_bom_id'=>$request->so_dyeing_bom_id,
        'so_dyeing_ref_id'=>$request->so_dyeing_ref_id,
        'liqure_ratio'=>$request->liqure_ratio,
        'liqure_wgt'=>$request->liqure_wgt,
        'remarks'=>$request->remarks
        ]);

        if($sodyeingbomfabric){
        return response()->json(array('success' => true,'id' =>  $sodyeingbomfabric->id,'so_dyeing_bom_id' =>  $request->so_dyeing_bom_id,'message' => 'Save Successfully'),200);
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
        //$rows=$this->sodyeingbomfabric->find($id);
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->sodyeingbomfabric
        ->join('so_dyeing_boms',function($join){
        $join->on('so_dyeing_bom_fabrics.so_dyeing_bom_id','=','so_dyeing_boms.id');
        })
        ->join('so_dyeing_refs',function($join){
        $join->on('so_dyeing_refs.id','=','so_dyeing_bom_fabrics.so_dyeing_ref_id');
        })
         ->join('so_dyeings',function($join){
        $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_pos',function($join){
        $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_po_items',function($join){
        $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('po_dyeing_service_item_qties',function($join){
        $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
        })
        ->leftJoin('po_dyeing_service_items',function($join){
        $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
        ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        })
        ->leftJoin('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->leftJoin('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->leftJoin('so_dyeing_items',function($join){
        $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('buyers as gmt_buyer',function($join){
        $join->on('gmt_buyer.id','=','so_dyeing_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->leftJoin('uoms as so_uoms',function($join){
        $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
        })
        ->leftJoin('colors as so_color',function($join){
        $join->on('so_color.id','=','so_dyeing_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
        $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
        })
        ->where([['so_dyeing_bom_fabrics.id','=',$id]])
        ->selectRaw('
          so_dyeing_bom_fabrics.id,
          so_dyeing_bom_fabrics.so_dyeing_bom_id,
          so_dyeing_bom_fabrics.liqure_ratio,
          so_dyeing_bom_fabrics.liqure_wgt,
          so_dyeing_bom_fabrics.remarks,
          so_dyeing_refs.id as so_dyeing_ref_id,
          so_dyeing_refs.so_dyeing_id,
          constructions.name as construction_name,
          po_dyeing_service_item_qties.fabric_color_id,
          style_fabrications.autoyarn_id,
          style_fabrications.fabric_look_id,
          style_fabrications.fabric_shape_id,
          style_fabrications.gmtspart_id,
          style_fabrications.dyeing_type_id,
          budget_fabrics.gsm_weight,
          po_dyeing_service_item_qties.budget_fabric_prod_con_id,
          po_dyeing_service_item_qties.colorrange_id,
          po_dyeing_service_item_qties.qty,
          po_dyeing_service_item_qties.pcs_qty,
          po_dyeing_service_item_qties.rate,
          po_dyeing_service_item_qties.amount,
          so_dyeing_items.autoyarn_id as c_autoyarn_id,
          so_dyeing_items.fabric_look_id as c_fabric_look_id,
          so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
          so_dyeing_items.gmtspart_id as c_gmtspart_id,
          so_dyeing_items.gsm_weight as c_gsm_weight,
          so_dyeing_items.fabric_color_id as c_fabric_color_id,
          so_dyeing_items.colorrange_id as c_colorrange_id,
          so_dyeing_items.qty as c_qty,
          so_dyeing_items.rate as c_rate,
          so_dyeing_items.amount as c_amount,
          so_dyeing_items.dyeing_type_id as c_dyeing_type_id,
          styles.style_ref,
          sales_orders.sale_order_no,
          so_dyeing_items.gmt_style_ref,
          so_dyeing_items.gmt_sale_order_no,
          buyers.name as buyer_name,
          gmt_buyer.name as gmt_buyer_name,
          uoms.code as uom_name,
          so_uoms.code as so_uom_name
          '
        )
        ->orderBy('so_dyeing_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
            $rows->construction_name=$rows->autoyarn_id?$fabricDescriptionArr[$rows->autoyarn_id]:$fabricDescriptionArr[$rows->c_autoyarn_id];
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
            $rows->uom_id=$rows->uom_id?$uom[$rows->uom_id]:'';
            $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
            $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:$color[$rows->c_fabric_color_id];
            $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];
            $rows->fabric_wgt=$rows->qty?$rows->qty:$rows->c_qty;
            $rows->pcs_qty=$rows->pcs_qty;
            $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
            $rows->order_val=$rows->amount?$rows->amount:$rows->c_amount;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            $rows->uom_name=$rows->uom_name?$rows->uom_name:$rows->so_uom_name;
            $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:$dyetype[$rows->c_dyeing_type_id];
            return $rows;
        })
        ->first();
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
    public function update(SoDyeingFabricRcvItemRequest $request, $id) {
      if($request->liqure_ratio <= 0){
        return response()->json(array('success' => false,'id' => $id,'so_dyeing_bom_id' => $request->so_dyeing_bom_id,'message' => '0 Qty Not Allowed'),200);
      }
      if($request->liqure_wgt <= 0){
        return response()->json(array('success' => false,'id' => $id,'so_dyeing_bom_id' => $request->so_dyeing_bom_id,'message' => '0 Rate Not Allowed'),200);
      }
      $sodyeingbomfabric=$this->sodyeingbomfabric->update($id,$request->except(['id','so_dyeing_bom_id','so_dyeing_ref_id']));

      if($sodyeingbomfabric){
        return response()->json(array('success' => true,'id' => $id,'so_dyeing_bom_id' => $request->so_dyeing_bom_id,'message' => 'Update Successfully'),200);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
      if($this->sodyeingbomfabric->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }

    public function getFabric()
    {
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->sodyeing
        ->join('so_dyeing_refs',function($join){
        $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_pos',function($join){
        $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_po_items',function($join){
        $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('po_dyeing_service_item_qties',function($join){
        $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
        })
        ->leftJoin('po_dyeing_service_items',function($join){
        $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
        ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        //$join->on('budget_fabric_prod_cons.budget_fabric_prod_id','=','budget_fabric_prods.id');
        })
        ->leftJoin('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->leftJoin('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->leftJoin('so_dyeing_items',function($join){
        $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('buyers as gmt_buyer',function($join){
        $join->on('gmt_buyer.id','=','so_dyeing_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->leftJoin('uoms as so_uoms',function($join){
        $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
        })
        ->leftJoin('colors as so_color',function($join){
        $join->on('so_color.id','=','so_dyeing_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
        $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
        })
        ->where([['so_dyeings.id','=',request('so_dyeing_id',0)]])
        ->selectRaw('
          so_dyeing_refs.id,
          so_dyeing_refs.so_dyeing_id,
          constructions.name as construction_name,
          style_fabrications.autoyarn_id,
          style_fabrications.fabric_look_id,
          style_fabrications.fabric_shape_id,
          style_fabrications.gmtspart_id,
          style_fabrications.dyeing_type_id,
          budget_fabrics.gsm_weight,
          po_dyeing_service_item_qties.fabric_color_id,
          po_dyeing_service_item_qties.budget_fabric_prod_con_id,
          po_dyeing_service_item_qties.colorrange_id,
          po_dyeing_service_item_qties.dia,
          po_dyeing_service_item_qties.measurment,
          po_dyeing_service_item_qties.qty,
          po_dyeing_service_item_qties.pcs_qty,
          po_dyeing_service_item_qties.rate,
          po_dyeing_service_item_qties.amount,
          so_dyeing_items.autoyarn_id as c_autoyarn_id,
          so_dyeing_items.fabric_look_id as c_fabric_look_id,
          so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
          so_dyeing_items.gmtspart_id as c_gmtspart_id,
          so_dyeing_items.gsm_weight as c_gsm_weight,
          so_dyeing_items.fabric_color_id as c_fabric_color_id,
          so_dyeing_items.colorrange_id as c_colorrange_id,
          so_dyeing_items.qty as c_qty,
          so_dyeing_items.rate as c_rate,
          so_dyeing_items.amount as c_amount,
          so_dyeing_items.dyeing_type_id as c_dyeing_type_id,
          styles.style_ref,
          sales_orders.sale_order_no,
          so_dyeing_items.gmt_style_ref,
          so_dyeing_items.gmt_sale_order_no,
          buyers.name as buyer_name,
          gmt_buyer.name as gmt_buyer_name,
          uoms.code as uom_name,
          so_uoms.code as so_uom_name
          '
        )
        ->orderBy('so_dyeing_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
            $rows->construction_name=$rows->autoyarn_id?$fabricDescriptionArr[$rows->autoyarn_id]:$fabricDescriptionArr[$rows->c_autoyarn_id];
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
            $rows->uom_id=$rows->uom_id?$uom[$rows->uom_id]:'';
            $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
            $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:$color[$rows->c_fabric_color_id];
            $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];
            $rows->qty=$rows->qty?$rows->qty:$rows->c_qty;
            $rows->pcs_qty=$rows->pcs_qty;
            $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
            $rows->amount=$rows->amount?$rows->amount:$rows->c_amount;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            $rows->uom_name=$rows->uom_name?$rows->uom_name:$rows->so_uom_name;
            $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:$dyetype[$rows->c_dyeing_type_id];
           // $rows->qty=number_format($rows->qty,2,'.',',');
            //$rows->pcs_qty=number_format($rows->pcs_qty,0,'.',',');
            //$rows->amount=number_format($rows->amount,2,'.',','); 
            return $rows;
        });
        echo json_encode($rows);
    }
}