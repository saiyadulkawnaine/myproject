<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRefRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Purchase\PoAopServiceItemQtyRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopItemRequest;

class SoAopItemController extends Controller {

   
    private $soaop;
    private $poaopref;
    private $soaopitem;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $poaopserviceitemqty;


    public function __construct(
      SoAopRepository $soaop,
      SoAopRefRepository $poaopref, 
      SoAopItemRepository $soaopitem, 
      AutoyarnRepository $autoyarn,
      GmtspartRepository $gmtspart,
      UomRepository $uom,
      ColorrangeRepository $colorrange,
      ColorRepository $color,
      EmbelishmentTypeRepository $embelishmenttype, 
      PoAopServiceItemQtyRepository $poaopserviceitemqty
  
    ) {
        $this->soaop = $soaop;
        $this->poaopref = $poaopref;
        $this->soaopitem = $soaopitem;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->embelishmenttype = $embelishmenttype;
        $this->poaopserviceitemqty = $poaopserviceitemqty;
        
        $this->middleware('auth');

      $this->middleware('permission:view.soaopitems',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.soaopitems', ['only' => ['store']]);
      $this->middleware('permission:edit.soaopitems',   ['only' => ['update']]);
      $this->middleware('permission:delete.soaopitems', ['only' => ['destroy']]);

         
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
      $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
      $billfor=array_prepend(config('bprs.billfor'),'-Select-','');
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
          $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
        }

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->soaop
        ->join('so_aop_refs',function($join){
          $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
        })
        ->leftJoin('so_aop_pos',function($join){
          $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
        })
        ->leftJoin('so_aop_po_items',function($join){
          $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->leftJoin('po_aop_service_item_qties',function($join){
          $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
        })
        ->leftJoin('po_aop_service_items',function($join){
          $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
          ->whereNull('po_aop_service_items.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
          $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
          $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
          $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
          $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
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
        ->leftJoin('so_aop_items',function($join){
          $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->leftJoin('buyers',function($join){
          $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('buyers as gmt_buyer',function($join){
          $join->on('gmt_buyer.id','=','so_aop_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
          $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->leftJoin('uoms as so_uoms',function($join){
          $join->on('so_uoms.id','=','so_aop_items.uom_id');
        })
        ->leftJoin('colors as so_color',function($join){
          $join->on('so_color.id','=','so_aop_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
            $join->on('po_color.id','=','po_aop_service_item_qties.fabric_color_id');
        })
        ->where([['so_aops.id','=',request('so_aop_id',0)]])
        ->selectRaw('
            so_aop_refs.id,
            so_aop_refs.so_aop_id,
            constructions.name as construction_name,
            style_fabrications.autoyarn_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            style_fabrications.gmtspart_id,
            budget_fabrics.gsm_weight,
            po_aop_service_item_qties.fabric_color_id,
            po_aop_service_item_qties.budget_fabric_prod_con_id,
            po_aop_service_item_qties.colorrange_id,
            po_aop_service_item_qties.embelishment_type_id,
            po_aop_service_item_qties.dia,
            po_aop_service_item_qties.measurment,
            po_aop_service_item_qties.coverage,
            po_aop_service_item_qties.impression,
            po_aop_service_item_qties.qty,

            po_aop_service_item_qties.rate,
            po_aop_service_item_qties.amount,
            so_aop_items.autoyarn_id as c_autoyarn_id,
            so_aop_items.fabric_look_id as c_fabric_look_id,
            so_aop_items.fabric_shape_id as c_fabric_shape_id,
            so_aop_items.gmtspart_id as c_gmtspart_id,
            so_aop_items.gsm_weight as c_gsm_weight,
            so_aop_items.fabric_color_id as c_fabric_color_id,
            so_aop_items.colorrange_id as c_colorrange_id,
            so_aop_items.embelishment_type_id as c_embelishment_type_id,
            so_aop_items.dia as c_dia,
            so_aop_items.measurment as c_measurment,
            so_aop_items.coverage as c_coverage,
            so_aop_items.impression as c_impression,
            so_aop_items.qty as c_qty,
            so_aop_items.rate as c_rate,
            so_aop_items.amount as c_amount,
            so_aop_items.bill_for,
            styles.style_ref,
            sales_orders.sale_order_no,
            so_aop_items.gmt_style_ref,
            so_aop_items.gmt_sale_order_no,
            buyers.name as buyer_name,
            gmt_buyer.name as gmt_buyer_name,
            uoms.code as uom_name,
            so_uoms.code as so_uom_name
            '
          )
        ->orderBy('so_aop_refs.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$fabricDescriptionArr,$aoptype,$billfor){
          $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
          $rows->construction_name=$rows->autoyarn_id?$fabricDescriptionArr[$rows->autoyarn_id]:$fabricDescriptionArr[$rows->c_autoyarn_id];
          $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
          $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
          $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
          $rows->uom_id=$rows->uom_id?$uom[$rows->uom_id]:'';
          $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
          $rows->fabric_color_id=$rows->fabric_color_id?$color[$rows->fabric_color_id]:$color[$rows->c_fabric_color_id];
          $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];
          $rows->embelishment_type_id=$rows->embelishment_type_id?$aoptype[$rows->embelishment_type_id]:$aoptype[$rows->c_embelishment_type_id];
          $rows->dia=$rows->dia?$rows->dia:$rows->c_dia;
          $rows->measurment=$rows->measurment?$rows->measurment:$rows->c_measurment;
          $rows->coverage=$rows->coverage?$rows->coverage:$rows->c_coverage;
          $rows->impression=$rows->impression?$rows->impression:$rows->c_impression;
          $rows->qty=$rows->qty?$rows->qty:$rows->c_qty;
          $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
          $rows->amount=$rows->amount?$rows->amount:$rows->c_amount;
          $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
          $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
          $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
          $rows->uom_name=$rows->uom_name?$rows->uom_name:$rows->so_uom_name;
          $rows->qty=number_format($rows->qty,2,'.',',');
          $rows->bill_for=$rows->bill_for?$billfor[$rows->bill_for]:'';
          $rows->amount=number_format($rows->amount,2,'.',','); 
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
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopItemRequest $request) {
      \DB::beginTransaction();
      try {
        $poaopref=$this->poaopref->create(  ['so_aop_id'=>$request->so_aop_id]);
        $request->request->add(['so_aop_ref_id' => $poaopref->id]);
        $color = $this->color->firstOrCreate(['name' => $request->fabric_color],['code' => '']);
        $request->request->add(['fabric_color_id' => $color->id]);
        $soaopitem=$this->soaopitem->create($request->except(['id','fabrication','po_aop_service_item_id','fabric_color']));
      } catch (EXCEPTION $e) {
        \DB::rollback();
        throw $e;
      }
      \DB::commit();
      if($soaopitem){
        return response()->json(array('success' => true,'id' =>  $soaopitem->id,'message' => 'Save Successfully'),200);
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
      //$colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      
      $autoyarn=$this->autoyarn->join('autoyarnratios', function($join)  {
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

      $rows=$this->soaop
        ->join('so_aop_refs',function($join){
            $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
        })
        ->leftJoin('so_aop_pos',function($join){
            $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
        })
        ->leftJoin('so_aop_po_items',function($join){
            $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->leftJoin('po_aop_service_item_qties',function($join){
            $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
        })
        ->leftJoin('po_aop_service_items',function($join){
          $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
          ->whereNull('po_aop_service_items.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
          $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
          $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
          $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
          $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
         // $join->on('budget_fabric_prod_cons.budget_fabric_prod_id','=','budget_fabric_prods.id');
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
        ->leftJoin('so_aop_items',function($join){
          $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->leftJoin('buyers',function($join){
          $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('buyers as gmt_buyer',function($join){
            $join->on('gmt_buyer.id','=','so_aop_items.gmt_buyer');
        })
        ->leftJoin('colors as so_color',function($join){
          $join->on('so_color.id','=','so_aop_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
            $join->on('po_color.id','=','po_aop_service_item_qties.fabric_color_id');
        })
        ->where([['so_aop_refs.id','=',$id]])
        ->selectRaw('
          so_aop_items.id,
          so_aop_refs.id as so_aop_ref_id,
          so_aop_refs.so_aop_id,
          style_fabrications.autoyarn_id,
          style_fabrications.fabric_look_id,
          style_fabrications.fabric_shape_id,
          style_fabrications.gmtspart_id,
          budget_fabrics.gsm_weight,
          po_aop_service_item_qties.fabric_color_id,
          style_fabrications.uom_id,
          po_aop_service_items.id as po_aop_service_item_id,
          po_aop_service_item_qties.budget_fabric_prod_con_id,
          po_aop_service_item_qties.colorrange_id,
          po_aop_service_item_qties.embelishment_type_id,
          po_aop_service_item_qties.dia,
          po_aop_service_item_qties.measurment,
          po_aop_service_item_qties.coverage,
          po_aop_service_item_qties.impression,
          po_aop_service_item_qties.bill_for,
          po_aop_service_item_qties.qty,
          po_aop_service_item_qties.rate,
          po_aop_service_item_qties.amount,
          so_aop_items.autoyarn_id as c_autoyarn_id,
          so_aop_items.fabric_look_id as c_fabric_look_id,
          so_aop_items.fabric_shape_id as c_fabric_shape_id,
          so_aop_items.gmtspart_id as c_gmtspart_id,
          so_aop_items.gsm_weight as c_gsm_weight,
          so_aop_items.fabric_color_id as c_fabric_color_id,
          so_aop_items.colorrange_id as c_colorrange_id,
          so_aop_items.embelishment_type_id as c_embelishment_type_id,
          so_aop_items.dia as c_dia,
          so_aop_items.measurment as c_measurment,
          so_aop_items.coverage as c_coverage,
          so_aop_items.impression as c_impression,
          so_aop_items.qty as c_qty,
          so_aop_items.rate as c_rate,
          so_aop_items.amount as c_amount,
          so_aop_items.delivery_date,
          so_aop_items.delivery_point,
          so_aop_items.uom_id as so_uom_id,
          so_aop_items.bill_for as c_bill_for,
          styles.style_ref,
          sales_orders.sale_order_no,
          so_aop_items.gmt_style_ref,
          so_aop_items.gmt_sale_order_no,
          buyers.id as buyer_name,
          gmt_buyer.id as gmt_buyer_name,
          
          so_color.name as c_fabric_color_name,
          po_color.name as fabric_color_name')
        ->orderBy('so_aop_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown){
          $rows->autoyarn_id=$rows->autoyarn_id?$rows->autoyarn_id:$rows->c_autoyarn_id;
          $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
          $rows->gmtspart_id=$rows->gmtspart_id?$rows->gmtspart_id:$rows->c_gmtspart_id;
          $rows->fabric_look_id=$rows->fabric_look_id?$rows->fabric_look_id:$rows->c_fabric_look_id;
          $rows->fabric_shape_id=$rows->fabric_shape_id?$rows->fabric_shape_id:$rows->c_fabric_shape_id;
          $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
          $rows->fabric_color_id=$rows->fabric_color_id?$rows->fabric_color_id:$rows->c_fabric_color_id;
          $rows->colorrange_id=$rows->colorrange_id?$rows->colorrange_id:$rows->c_colorrange_id;
          $rows->embelishment_type_id=$rows->embelishment_type_id?$rows->embelishment_type_id:$rows->c_embelishment_type_id;
          $rows->dia=$rows->dia?$rows->dia:$rows->c_dia;
          $rows->measurment=$rows->measurment?$rows->measurment:$rows->c_measurment;
          $rows->coverage=$rows->coverage?$rows->coverage:$rows->c_coverage;
          $rows->impression=$rows->impression?$rows->impression:$rows->c_impression;
          $rows->qty=$rows->qty?$rows->qty:$rows->c_qty;
          $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
          $rows->amount=$rows->amount?$rows->amount:$rows->c_amount;
          $rows->gmt_style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
          $rows->gmt_buyer=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
          $rows->gmt_sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
          $rows->uom_id=$rows->uom_id?$rows->uom_id:$rows->so_uom_id;
          $rows->bill_for=$rows->bill_for?$rows->bill_for:$rows->c_bill_for;
          $rows->fabric_color=$rows->fabric_color_name?$rows->fabric_color_name:$rows->c_fabric_color_name;
          return $rows;
        })
        ->first();
        if(!$rows->id){
          $rows->id=$rows->so_aop_ref_id;
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
    public function update(SoAopItemRequest $request, $id) {

      if($request->po_aop_service_item_id){
        $this->poaopserviceitemqty
        ->where([['po_aop_service_item_id','=',$request->po_aop_service_item_id]])
        ->update(['bill_for'=>$request->bill_for]);
        return response()->json(array('success' => false,'message' => 'Update no possible, AOP Service Order Found'),200);
      }
      else{
        \DB::beginTransaction();
        try {
          $color = $this->color->firstOrCreate(['name' => $request->fabric_color],['code' => '']);
          $request->request->add(['fabric_color_id' => $color->id]);
          $soaopitem=$this->soaopitem->update($id,$request->except(['id','fabrication','po_aop_service_item_id','fabric_color']));
        } catch (EXCEPTION $e) {
          \DB::rollback();
          throw $e;
        }
        \DB::commit();
        
        if($soaopitem){
          return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->soaopitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getItem()
    {
      $autoyarn=$this->autoyarn->join('autoyarnratios', function($join)  {
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
          $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
        }
      
      $fab=array();
      $fabs=array();
        foreach($autoyarn as $row){
          $fab[$row->id]['id']=$row->id;
          $fab[$row->id]['name']=$row->name;
          $fab[$row->id]['composition_name']=$desDropdown[$row->id];
        }
      foreach($fab as $row){
          
      array_push($fabs,$row);
        }
      echo json_encode($fabs);
  }
}