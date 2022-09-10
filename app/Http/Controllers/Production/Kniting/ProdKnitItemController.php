<?php

namespace App\Http\Controllers\Production\Kniting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceItemQtyRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Library\Template;
use App\Http\Requests\Production\Kniting\ProdKnitItemRequest;

class ProdKnitItemController extends Controller {
    private $prodknit;
    private $prodknititem;
    private $assetquantitycost;
    private $plknititem;
    private $plknit;
    private $poknitserviceitemqty;
    private $poknitservice;
    private $autoyarn;
    private $gmtspart;
    public function __construct(
        ProdKnitRepository $prodknit, 
        ProdKnitItemRepository $prodknititem, 
        AssetQuantityCostRepository $assetquantitycost,
        PlKnitItemRepository $plknititem,
        PlKnitRepository $plknit,
        PoKnitServiceItemQtyRepository $poknitserviceitemqty,
        PoKnitServiceRepository $poknitservice,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart
        ) 
    {
        $this->prodknit = $prodknit;
        $this->prodknititem = $prodknititem;
        $this->assetquantitycost = $assetquantitycost;
        $this->plknititem = $plknititem;
        $this->plknit = $plknit;
        $this->poknitserviceitemqty = $poknitserviceitemqty;
        $this->poknitservice = $poknitservice;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->middleware('auth');
        
        $this->middleware('permission:view.prodknititems',['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodknititems',['only' => ['store']]);
        $this->middleware('permission:edit.prodknititems',['only' => ['update']]);
        $this->middleware('permission:delete.prodknititems',['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
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

        $prod=$this->prodknit->find(request('prod_knit_id',0));
        $rows=null;
        if($prod->basis_id==1)
        {
        $rows=$this->prodknititem
        ->leftJoin('pl_knit_items', function($join)  {
            $join->on('pl_knit_items.id', '=', 'prod_knit_items.pl_knit_item_id');
        })
        ->leftJoin('pl_knits', function($join)  {
            $join->on('pl_knits.id', '=', 'pl_knit_items.pl_knit_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
        })
        ->leftJoin('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'pl_knit_items.colorrange_id');
        })
        ->leftJoin('so_knit_refs', function($join)  {
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
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','po_knit_service_item_qties.fabric_color_id');
        })
        ->leftJoin('colors as so_colors',function($join){
            $join->on('so_colors.id','=','so_knit_items.fabric_color_id');
        })
        ->where([['prod_knit_items.prod_knit_id','=',request('prod_knit_id',0)]])
        ->orderBy('prod_knit_items.id','desc')
        ->get([
            'prod_knit_items.*',
            'pl_knits.pl_no',
            'colorranges.name as colorrange_name',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'colors.name as fabric_color',
            'so_knit_items.autoyarn_id as so_autoyarn_id',
            'so_knit_items.gmtspart_id as so_gmtspart_id',
            'so_knit_items.fabric_look_id as so_fabric_look_id',
            'so_knit_items.fabric_shape_id as so_fabric_shape_id',
            'so_colors.name as so_fabric_color',
            
            'asset_quantity_costs.custom_no as machine_no',
            'asset_technical_features.dia_width',
            'asset_technical_features.gauge',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            'so_knit_items.gmt_sale_order_no',
            'so_knit_items.gmt_style_ref',
        ])
        ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->so_autoyarn_id];
             $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->so_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->so_fabric_shape_id];
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->so_gmtspart_id];
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            $rows->fabriccolor= $rows->fabric_color?$rows->fabric_color:$rows->so_fabric_color;
            return $rows;
        });
        }
        else if($prod->basis_id==5)
        {
            $rows=$this->prodknititem
            ->leftJoin('po_knit_service_item_qties',function($join){
            $join->on('po_knit_service_item_qties.id','=','prod_knit_items.po_knit_service_item_qty_id');
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
            $join->on('po_knit_services.id','=','po_knit_service_items.po_knit_service_id')
            ->whereNull('po_knit_services.deleted_at');
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
            ->leftJoin('colors',function($join){
            $join->on('colors.id','=','po_knit_service_item_qties.fabric_color_id');
            })
            ->where([['prod_knit_items.prod_knit_id','=',request('prod_knit_id',0)]])
            ->orderBy('prod_knit_items.id','desc')
            ->get([
            'prod_knit_items.*',
            'po_knit_services.po_no as pl_no',
            'po_knit_service_item_qties.pl_machine_gg as gauge',
            'po_knit_service_item_qties.pl_stitch_length as stitch_length',
            'colorranges.name as colorrange_name',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'colors.name as fabric_color',
            'styles.style_ref',
            'sales_orders.sale_order_no as order_no',
            ])
            ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:null;
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:null;
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:null;
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:null;
            $rows->fabriccolor= $rows->fabric_color?$rows->fabric_color:'';
            return $rows;
            }); 
        }
        echo json_encode($rows);
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
    public function store(ProdKnitItemRequest $request) 
    {
        $prodknititem=$this->prodknititem->create($request->except(['id','fabrication','custom_no','operator_name']));
        if($prodknititem)
        {
            return response()->json(array('success' => true,'id' =>  $prodknititem->id,'message' => 'Save Successfully'),200);
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

        $item=$this->prodknititem->find($id);
        $prod=$this->prodknit->find($item->prod_knit_id);
        $rows=null;
        if($prod->basis_id==1)
        {
        $rows=$this->prodknititem
        ->leftJoin('pl_knit_items', function($join)  {
            $join->on('pl_knit_items.id', '=', 'prod_knit_items.pl_knit_item_id');
        })
        ->leftJoin('pl_knits', function($join)  {
            $join->on('pl_knits.id', '=', 'pl_knit_items.pl_knit_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
        })
        ->leftJoin('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'pl_knit_items.colorrange_id');
        })
        ->leftJoin('so_knit_refs', function($join)  {
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
        ->leftJoin('employee_h_rs', function($join)  {
            $join->on('employee_h_rs.id', '=', 'prod_knit_items.operator_id');
        })

        
        ->where([['prod_knit_items.id','=',$id]])
        ->get([
            'prod_knit_items.*',
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
            'asset_quantity_costs.custom_no as custom_no',
            'asset_technical_features.dia_width as machine_dia',
            'asset_technical_features.gauge as machine_gg',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            'so_knit_items.gmt_sale_order_no',
            'so_knit_items.gmt_style_ref',
            'employee_h_rs.name as operator_name',
        ])
        ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->so_autoyarn_id];
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->so_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->so_fabric_shape_id];
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->so_gmtspart_id];
            $rows->order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->fabric_look_id=$rows->fabric_look_id?$rows->fabric_look_id:$rows->so_fabric_look_id;
            $rows->fabric_shape_id=$rows->fabric_shape_id?$rows->fabric_shape_id:$rows->so_fabric_shape_id;
            return $rows;
        })
        ->first();
        }
        else if($prod->basis_id==5)
        {
            $rows=$this->prodknititem
            ->leftJoin('po_knit_service_item_qties',function($join){
            $join->on('po_knit_service_item_qties.id','=','prod_knit_items.po_knit_service_item_qty_id');
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
            $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id');
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
            ->where([['prod_knit_items.id','=',$id]])
            ->get([
            'prod_knit_items.*',
            'po_knit_service_item_qties.pl_machine_gg as gauge',
            'po_knit_service_item_qties.pl_stitch_length as stitch_length',
            'sales_orders.sale_order_no as order_no',
            'styles.style_ref',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            ])
            ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:null;
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:null;
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:null;
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:null;
            return $rows;
            })
            ->first(); 
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
    public function update(ProdKnitItemRequest $request, $id) 
    {
        $prodknititem=$this->prodknititem->update($id,$request->except(['id','fabrication','custom_no','operator_name','pl_knit_item_id','po_knit_service_item_qty_id']));
        if($prodknititem){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        if($this->prodknititem->delete($id))
        {
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    private function getPlan()
    {
        $prod=$this->prodknit->find(request('prod_id',0));

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
        ->leftJoin('colors',function($join){
        $join->on('colors.id','=','po_knit_service_item_qties.fabric_color_id');
        })
        ->leftJoin('colors as so_colors',function($join){
        $join->on('so_colors.id','=','so_knit_items.fabric_color_id');
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
        ->where([['pl_knits.supplier_id','=',$prod->supplier_id]])
        ->orderBy('style_fabrications.gmtspart_id')
        ->orderBy('colors.id')
        ->get([
            'pl_knit_items.*',
            'pl_knit_items.id as pl_knit_item_id',
            'pl_knits.pl_no',
            'colorranges.name as colorrange_name',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'colors.name as fabric_color',
            'so_knit_items.autoyarn_id as so_autoyarn_id',
            'so_knit_items.gmtspart_id as so_gmtspart_id',
            'so_knit_items.fabric_look_id as so_fabric_look_id',
            'so_knit_items.fabric_shape_id as so_fabric_shape_id',
            'so_colors.name as so_fabric_color',
            'asset_quantity_costs.custom_no as machine_no',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            'so_knit_items.gmt_sale_order_no',
            'so_knit_items.gmt_style_ref',
        ])
        ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$prod){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->so_autoyarn_id];
             $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->so_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->so_fabric_shape_id];

            $rows->fabric_look_id=$rows->fabric_look_id?$rows->fabric_look_id:$rows->so_fabric_look_id;
            $rows->fabric_shape_id=$rows->fabric_shape_id?$rows->fabric_shape_id:$rows->so_fabric_shape_id;

            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->so_gmtspart_id];
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            $rows->fabriccolor=$rows->fabric_color?$rows->fabric_color:$rows->so_fabric_color;
            return $rows;
        });
        return $rows;
    }


    private function getPo()
    {
        $prod=$this->prodknit->find(request('prod_id',0));

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
        ->leftJoin('colors',function($join){
        $join->on('colors.id','=','po_knit_service_item_qties.fabric_color_id');
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
        ->where([['po_knit_services.supplier_id','=',$prod->supplier_id]])
        ->orderBy('style_fabrications.gmtspart_id','desc')
        ->orderBy('colors.id','desc')
        ->get([
            'po_knit_service_item_qties.*',
            'po_knit_service_item_qties.id as po_knit_service_item_qty_id',
            'po_knit_service_item_qties.pl_stitch_length as stitch_length',
            'po_knit_services.po_no as pl_no',
            'colorranges.name as colorrange_name',
            'style_fabrications.autoyarn_id',
            'style_fabrications.gmtspart_id',
            'style_fabrications.fabric_look_id',
            'style_fabrications.fabric_shape_id',
            'budget_fabrics.gsm_weight',
            'styles.style_ref',
            'sales_orders.sale_order_no as order_no',
            'colors.name as fabric_color'
        ])
        ->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$prod){
            $rows->fabrication=$desDropdown[$rows->autoyarn_id];
            $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
            $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
            $rows->gmtspart=$gmtspart[$rows->gmtspart_id];
            $rows->fabriccolor=$rows->fabric_color?$rows->fabric_color:'';

            return $rows;
        });
        return $rows;
    }

    public function getItem()
    {

        $prod=$this->prodknit->find(request('prod_id',0));
        $rows=null;
        if($prod->basis_id==1)
        {
            $rows=$this->getPlan();
        }
        else if($prod->basis_id==5)
        {
           $rows=$this->getPo(); 
        }


        
        echo json_encode($rows);
        
    }

    public function getMachine()
    {
        $machine=$this->assetquantitycost
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_acquisitions.id','=','asset_technical_features.asset_acquisition_id');
        })
        ->when(request('dia_width'), function ($q) {
        return $q->where('asset_technical_features.dia_width', '=>',request('dia_width', 0));
        })
        ->when(request('no_of_feeder'), function ($q) {
        return $q->where('asset_technical_features.no_of_feeder', '<=',request('no_of_feeder', 0));
        })
        ->where([['asset_acquisitions.production_area_id','=',10]])
        ->orderBy('asset_acquisitions.id','asc')
        ->orderBy('asset_quantity_costs.id','asc')
        ->get([
            'asset_quantity_costs.*',
            'asset_acquisitions.prod_capacity',
            'asset_acquisitions.name as asset_name',
            'asset_acquisitions.origin',
            'asset_acquisitions.brand',
            'asset_technical_features.dia_width',
            'asset_technical_features.gauge',
            'asset_technical_features.extra_cylinder',
            'asset_technical_features.no_of_feeder'
        ]);
        echo json_encode($machine);
    }

    private function operator($asset_quantity_cost_id,$tenure_start,$tenure_end)
    {
        $operator=$this->assetquantitycost
        ->join('asset_manpowers',function($join){
            $join->on('asset_manpowers.asset_quantity_cost_id','=','asset_quantity_costs.id');
        })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','asset_manpowers.employee_h_r_id');
        })
        ->whereRaw('? between asset_manpowers.tenure_start and asset_manpowers.tenure_end', [$tenure_start])
        ->where([['asset_quantity_costs.id','=',$asset_quantity_cost_id]])
        ->get([
            'employee_h_rs.name',
            'employee_h_rs.id'
        ]);
        return $operator;
    }

    public function getOperator()
    {
        $asset_quantity_cost_id=request('asset_quantity_cost_id', 0);
        $prod_date=request('prod_date', 0);
        $operator=$this->operator($asset_quantity_cost_id,$prod_date,$prod_date);
        echo json_encode($operator);
    }
}