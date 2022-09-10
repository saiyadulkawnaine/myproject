<?php
namespace App\Http\Controllers\Production\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRollRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitRcvByQcRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\GmtssampleRepository;
use App\Library\Template;
use App\Http\Requests\Production\Kniting\ProdKnitRcvByQcRequest;

class ProdKnitRcvByQcController extends Controller {

    
    private $prodknit;
    private $prodknititem;
    private $prodknititemroll;
    private $prodknitrcvbyqc;
    private $color;
    private $gmtssample;
  

    public function __construct(
        ProdKnitRcvByQcRepository $prodknitrcvbyqc,
        ProdKnitRepository $prodknit, 
        ProdKnitItemRepository $prodknititem, 
        ProdKnitItemRollRepository $prodknititemroll,
        ColorRepository $color,
        GmtssampleRepository $gmtssample
        ) 
    {
        $this->prodknitrcvbyqc = $prodknitrcvbyqc;
        $this->prodknit = $prodknit;
        $this->prodknititem = $prodknititem;
        $this->prodknititemroll = $prodknititemroll;
        $this->color = $color;
        $this->gmtssample = $gmtssample;
        $this->middleware('auth');
           /*  $this->middleware('permission:view.prodknititems',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodknititems', ['only' => ['store']]);
            $this->middleware('permission:edit.prodknititems',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodknititems', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // $prodknitrcvbyqc=$this->prodknititemroll
        // ->selectRaw('
            
        //     prod_knit_item_rolls.id as prod_knit_item_roll_id,
        //     prod_knit_item_rolls.custom_no,
        //     prod_knit_item_rolls.roll_length,
        //     prod_knit_item_rolls.roll_weight,
        //     prod_knit_item_rolls.width,
        //     prod_knit_item_rolls.qty_pcs,
        //     prod_knit_item_rolls.fabric_color,
        //     prod_knit_item_rolls.gmt_sample,
        //     prod_knit_item_rolls.measurment,
        //     prod_knit_items.id as prod_knit_item_id,
        //     prod_knits.id as prod_knit_id
        // ')
        // /* 
        //     prod_knit_rcv_by_qcs.id,
        //     prod_knit_rcv_by_qcs.receive_date,
        //     prod_knit_rcv_by_qcs.prod_knit_item_roll_id,
        // */
        // ->leftJoin('prod_knit_items',function($join){
        //     $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
        // })
        // ->leftJoin('prod_knits',function($join){
        //     $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
        // })
        // /* ->leftJoin('prod_knit_rcv_by_qcs',function($join){
        //     $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
        // }) */
        // ->groupBy([
        //     // 'prod_knit_rcv_by_qcs.id',
        //     // 'prod_knit_rcv_by_qcs.receive_date',
        //     // 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id',
        //     'prod_knit_item_rolls.id',
        //     'prod_knit_item_rolls.custom_no',
        //     'prod_knit_item_rolls.roll_length',
        //     'prod_knit_item_rolls.roll_weight',
        //     'prod_knit_item_rolls.width',
        //     'prod_knit_item_rolls.qty_pcs',
        //     'prod_knit_item_rolls.fabric_color',
        //     'prod_knit_item_rolls.gmt_sample',
        //     'prod_knit_item_rolls.measurment',
        //     'prod_knit_items.id',
        //     'prod_knits.id',
        // ])
        // ->get();
        // echo json_encode($prodknitrcvbyqc);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        
        return Template::loadView('Production.Kniting.ProdKnitRcvByQc'/* ,['prodknitrcvbyqc'=>$prodknitrcvbyqc] */);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdKnitRcvByQcRequest $request) {
		$prodknitrcvbyqc=$this->prodknitrcvbyqc->updateOrCreate([
            'prod_knit_item_roll_id'=>$request->prod_knit_item_roll_id
        ],[

        ]);
        if($prodknitrcvbyqc){
            return response()->json(array('success' => true,'id' =>  $prodknitrcvbyqc->id,'message' => 'Save Successfully'),200);
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
        $prodknitrcvbyqc = $this->prodknitrcvbyqc->find($id);  
        $row ['fromData'] = $prodknitrcvbyqc;
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
    public function update(ProdKnitRcvByQcRequest $request, $id) {
        
        $prodknitrcvbyqc=$this->prodknitrcvbyqc->update($id,$request->except(['id','roll_no','fabric_color_id','gmt_sample_name']));
        if($prodknitrcvbyqc){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodknitrcvbyqc->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function importProdKnitRoll(){
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        $fabricDescription = collect(\DB::select("
			select
			autoyarns.id,
			constructions.name as construction,
			compositions.name,
			autoyarnratios.ratio
			FROM autoyarns
			join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
			join compositions on compositions.id = autoyarnratios.composition_id
			join constructions on constructions.id = autoyarns.construction_id
			"
	    ));

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

        $prodknitrcvbyqc=$this->prodknititemroll
        ->selectRaw('   
            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.prod_knit_item_id,
            prod_knit_item_rolls.custom_no,
            prod_knit_item_rolls.roll_length,
            prod_knit_item_rolls.roll_weight,
            prod_knit_item_rolls.width,
            prod_knit_item_rolls.measurment,
            prod_knit_item_rolls.qty_pcs,
            prod_knit_item_rolls.fabric_color,
            prod_knit_item_rolls.gmt_sample,
            
            prod_knit_items.prod_knit_id,
            prod_knit_items.stitch_length,
            prod_knit_items.gsm_weight,
            prod_knit_items.dia as machine_dia,

            prod_knits.shift_id,
            prod_knits.supplier_id,
            prod_knits.location_id,
            prod_knits.floor_id,

            

            asset_quantity_costs.custom_no,
            asset_technical_features.gauge as machine_gg,


            suppliers.name as supplier_name,
            locations.name as location_name,
            floors.name as floor_name
        ')
        /* 
        plknititems.knit_sales_order_no,
			plknititems.autoyarn_id,
			plknititems.gmt_buyer,
			plknititems.gmt_style_ref,
			plknititems.gmt_sale_order_no,

            prod_knit_rcv_by_qcs.id,
            prod_knit_rcv_by_qcs.receive_date,
            prod_knit_rcv_by_qcs.prod_knit_item_roll_id,
        */
        ->join('prod_knit_items',function($join){
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
        })
        ->leftJoin('prod_knit_item_pl_items',function($join){
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_pl_items.prod_knit_item_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id', '=', 'prod_knit_items.asset_quantity_cost_id');
            //$join->on('asset_quantity_costs.id', '=', 'prod_knit_item_pl_items.asset_quantity_cost_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id', '=', 'asset_technical_features.asset_acquisition_id');
            //$join->on('asset_quantity_costs.id', '=', 'prod_knit_item_pl_items.asset_quantity_cost_id');
        })
        ->leftJoin('prod_knit_item_po_items',function($join){
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_po_items.prod_knit_item_id');
        })
        ->leftJoin('pl_knit_items',function($join){
            $join->on('pl_knit_items.id', '=', 'prod_knit_item_pl_items.pl_knit_item_id');
        })
        ->join ('prod_knits',function($join){
            $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
        })
        ->join ('suppliers',function($join){
            $join->on('suppliers.id', '=', 'prod_knits.supplier_id');
        })
        ->leftJoin ('locations',function($join){
            $join->on('locations.id', '=', 'prod_knits.location_id');
        })
        ->leftJoin ('floors',function($join){
            $join->on('floors.id', '=', 'prod_knits.floor_id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            pl_knit_items.id,
            pl_knit_items.colorrange_id,
            po_knit_service_item_qties.sales_order_id,
            po_knit_service_items.budget_fabric_prod_id, 
            so_knits.sales_order_no as knit_sales_order_no,
            so_knit_items.autoyarn_id,
            so_knit_items.gmt_buyer,
            so_knit_items.gmt_style_ref,
            so_knit_items.gmt_sale_order_no
            from 
            pl_knit_items
            join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
            join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
            join so_knits on so_knits.id=so_knit_refs.so_knit_id
            left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
            left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
            left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
            left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
            and po_knit_service_items.deleted_at is null
        ) plknititems"),"plknititems.id","=","prod_knit_items.pl_knit_item_id")
        /* ->leftJoin('prod_knit_rcv_by_qcs',function($join){
            $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
        }) */
        ->groupBy([
            // 'prod_knit_rcv_by_qcs.id',
            // 'prod_knit_rcv_by_qcs.receive_date',
            // 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id',
            'prod_knit_item_rolls.id',
            'prod_knit_item_rolls.prod_knit_item_id',
            'prod_knit_item_rolls.custom_no',
            'prod_knit_item_rolls.roll_length',
            'prod_knit_item_rolls.roll_weight',
            'prod_knit_item_rolls.width',
            'prod_knit_item_rolls.measurment',
            'prod_knit_item_rolls.qty_pcs',
            'prod_knit_item_rolls.fabric_color',
            'prod_knit_item_rolls.gmt_sample',

            'prod_knit_items.prod_knit_id',
            'prod_knit_items.stitch_length',
            'prod_knit_items.gsm_weight',
            'prod_knit_items.dia',

            'prod_knits.shift_id',
            'prod_knits.supplier_id',
            'prod_knits.location_id',
            'prod_knits.floor_id',

            // 'plknititems.knit_sales_order_no',
			// 'plknititems.autoyarn_id',
			// 'plknititems.gmt_buyer',
			// 'plknititems.gmt_style_ref',
			// 'plknititems.gmt_sale_order_no',

            'asset_quantity_costs.custom_no',
            'asset_technical_features.gauge',


            'suppliers.name',
            'locations.name',
            'floors.name',

        ])
        //->where('')
        ->get()
        ->map(function($prodknitrcvbyqc) use($shiftname){
            $prodknitrcvbyqc->shift_id=$shiftname[$prodknitrcvbyqc->shift_id];
            return $prodknitrcvbyqc;
        });
        echo json_encode($prodknitrcvbyqc);
    }

}