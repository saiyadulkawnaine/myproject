<?php

namespace App\Http\Controllers\Production\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRollRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\BuyerRepository;


use App\Library\Template;
use App\Http\Requests\Production\AOP\ProdAopFinishDlvRollRequest;

class ProdAopFinishDlvRollController extends Controller {
    
    private $prodfinishdlv;
    private $prodbatchfinishqc;
    private $prodfinishdlvroll;
    private $gmtspart;
    private $itemaccount;
    private $autoyarn;
    private $buyer;
   

    public function __construct(
        ProdFinishDlvRepository $prodfinishdlv, 
        ProdBatchFinishQcRepository $prodbatchfinishqc,
        ProdFinishDlvRollRepository $prodfinishdlvroll,
        GmtspartRepository $gmtspart ,
        ItemAccountRepository $itemaccount,
        AutoyarnRepository $autoyarn,
        BuyerRepository $buyer
    ) 
    {
        $this->prodfinishdlv = $prodfinishdlv;
        $this->prodbatchfinishqc = $prodbatchfinishqc;
        $this->prodfinishdlvroll = $prodfinishdlvroll;
        $this->gmtspart = $gmtspart;
        $this->itemaccount = $itemaccount;
        $this->autoyarn = $autoyarn;
        $this->buyer = $buyer;
        $this->middleware('auth');
        
       /*$this->middleware('permission:view.prodaopfinishdlvrolls',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodaopfinishdlvrolls', ['only' => ['store']]);
        $this->middleware('permission:edit.prodaopfinishdlvrolls',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodaopfinishdlvrolls', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $prodfinishdlv=$this->prodfinishdlv->find(request('prod_finish_dlv_id',0));

        //$prodbatchfinishqc=$this->prodbatchfinishqc->find(request('prod_aop_batch_finish_qc_id',0));
        //$prodaopbatch=$this->prodaopbatch->find($prodbatchfinishqc->prod_aop_batch_id);

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'--','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $rollqcresult=array_prepend(config('bprs.rollqcresult'),'-Select-','');



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

        $prodknitqc=$this->prodfinishdlv
            ->selectRaw('
            prod_finish_dlv_rolls.id,
            prod_aop_batch_finish_qc_rolls.id as aop_batch_finish_qc_roll_id,
            prod_aop_batch_finish_qc_rolls.qty as qc_pass_qty,
            prod_aop_batch_finish_qc_rolls.reject_qty,
            prod_aop_batch_finish_qc_rolls.gsm_weight as qc_gsm_weight,
            prod_aop_batch_finish_qc_rolls.dia_width as qc_dia_width,
            prod_aop_batch_finish_qc_rolls.grade_id,
            prod_aop_batch_rolls.id as prod_aop_batch_roll_id,
            so_aop_fabric_isus.issue_no,
            so_aop_fabric_isu_items.id as so_aop_fabric_isu_item_id,
            so_aop_fabric_rcv_rols.id as so_aop_fabric_rcv_rol_id,
            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,
            so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id,
            so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id,
            prod_batch_finish_qc_rolls.qty as rcv_qty,
            prod_batch_finish_qc_rolls.gsm_weight as dyeing_gsm_weight,
            prod_batch_finish_qc_rolls.dia_width as dyeing_dia_width,
            fabriccolors.name as fabric_color,
            inv_grey_fab_items.autoyarn_id,
            inv_grey_fab_items.gmtspart_id,
            inv_grey_fab_items.fabric_look_id,
            inv_grey_fab_items.fabric_shape_id,
            inv_grey_fab_items.gsm_weight as knited_gsm_weight,
            inv_grey_fab_items.dia as knited_dia_width,
            inv_grey_fab_items.measurment as measurement,
            inv_grey_fab_items.roll_length,
            inv_grey_fab_items.stitch_length,
            inv_grey_fab_items.shrink_per,
            inv_grey_fab_items.colorrange_id,
            colorranges.name as colorrange_name,
            inv_grey_fab_items.color_id,
            colors.name as knit_fabric_color,
            inv_grey_fab_items.supplier_id,

            inv_grey_fab_rcv_items.inv_grey_fab_item_id,
            inv_grey_fab_rcv_items.store_id,
            prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id, 
            prod_knits.prod_no,
            prod_knit_items.id as prod_knit_item_id,
            suppliers.name as supplier_name,
            asset_quantity_costs.custom_no as machine_no,
            asset_technical_features.dia_width as machine_dia,
            asset_technical_features.gauge as machine_gg,
            sales_orders.sale_order_no,
            styles.style_ref,
            buyers. name as buyer_name,
            inv_isus.issue_no as kint_issue_no
            
            ')
            ->join('prod_finish_dlv_rolls as prod_aop_finish_dlv_rolls',function($join){
            $join->on('prod_aop_finish_dlv_rolls.prod_finish_dlv_id', '=', 'prod_finish_dlvs.id');
            })
            ->join('prod_batch_finish_qc_rolls as prod_aop_batch_finish_qc_rolls',function($join){
            $join->on('prod_aop_batch_finish_qc_rolls.id', '=', 'prod_aop_finish_dlv_rolls.prod_batch_finish_qc_roll_id');
            })
            ->join('prod_batch_finish_qcs',function($join){
            $join->on('prod_aop_batch_finish_qc_rolls.prod_batch_finish_qc_id', '=', 'prod_batch_finish_qcs.id');
            })
            ->join('prod_aop_batches',function($join){
            $join->on('prod_aop_batches.id', '=', 'prod_batch_finish_qcs.prod_aop_batch_id');
            })
            ->join('prod_aop_batch_rolls',function($join){
            $join->on('prod_aop_batch_rolls.id', '=', 'prod_aop_batch_finish_qc_rolls.prod_aop_batch_roll_id');
            })
            

            ->join('so_aop_fabric_isu_items',function($join){
            $join->on('so_aop_fabric_isu_items.id', '=', 'prod_aop_batch_rolls.so_aop_fabric_isu_item_id');
            })
            ->join('so_aop_fabric_isus',function($join){
            $join->on('so_aop_fabric_isus.id', '=', 'so_aop_fabric_isu_items.so_aop_fabric_isu_id');
            })
            ->join('so_aop_fabric_rcv_rols',function($join){
            $join->on('so_aop_fabric_rcv_rols.id', '=', 'so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id');
            })
            ->join('so_aop_fabric_rcv_items',function($join){
            $join->on('so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id', '=', 'so_aop_fabric_rcv_items.id');
            })
            ->join('prod_finish_dlv_rolls',function($join){
            $join->on('so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id', '=', 'prod_finish_dlv_rolls.id');
            })
            ->join('prod_batch_finish_qc_rolls',function($join){
            $join->on('prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id', '=', 'prod_batch_finish_qc_rolls.id');
            })
            ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_finish_qc_rolls.prod_batch_roll_id', '=', 'prod_batch_rolls.id');
            })
            ->join('prod_batches',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('colors as fabriccolors',function($join){
            $join->on('fabriccolors.id','=','prod_batches.batch_color_id');
            })
            ->join('so_aop_refs',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_fabric_rcv_items.so_aop_ref_id');
            })
            ->join('so_aops',function($join){
            $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
            $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->join('so_aop_po_items',function($join){
            $join->on('so_aop_po_items.so_aop_ref_id', '=', 'so_aop_refs.id');
            })
            ->join('po_aop_service_item_qties',function($join){
            $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->join('po_aop_service_items',function($join){
            $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            // ->join('budget_fabric_prod_cons',function($join){
            // $join->on('budget_fabric_prod_cons.id','=','po_aop_service_item_qties.budget_fabric_prod_con_id');
            // })
            ->join('sales_orders',function($join){
            // $join->on('sales_orders.id','=','budget_fabric_prod_cons.sales_order_id');
            $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
            })
            ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->join('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
            })
            ->join('budget_fabric_prods',function($join){
            $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
            })
            ->join('budget_fabrics',function($join){
            $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->join('style_fabrications',function($join){
            $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
            })
            ->join('so_dyeing_fabric_rcv_rols',function($join){
            $join->on('prod_batch_rolls.so_dyeing_fabric_rcv_rol_id', '=', 'so_dyeing_fabric_rcv_rols.id');
            })
            ->join('inv_grey_fab_isu_items',function($join){
            $join->on('inv_grey_fab_isu_items.id', '=', 'so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id');
            })
            ->join('inv_isus',function($join){
            $join->on('inv_isus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
            })
            ->join('inv_grey_fab_items',function($join){
            $join->on('inv_grey_fab_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_item_id');
            })
            ->join('inv_grey_fab_rcv_items',function($join){
            $join->on('inv_grey_fab_rcv_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id');
            })
            ->join('inv_grey_fab_rcvs',function($join){
            $join->on('inv_grey_fab_rcvs.id', '=', 'inv_grey_fab_rcv_items.inv_grey_fab_rcv_id');
            })
            ->join('inv_rcvs',function($join){
            $join->on('inv_rcvs.id', '=', 'inv_grey_fab_rcvs.inv_rcv_id');
            })
            ->join('prod_knit_dlvs',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'inv_grey_fab_rcvs.prod_knit_dlv_id');
            })
            ->join('prod_knit_dlv_rolls',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'prod_knit_dlv_rolls.prod_knit_dlv_id');
            $join->on('inv_grey_fab_rcv_items.prod_knit_dlv_roll_id', '=', 'prod_knit_dlv_rolls.id');
            })
            ->join('prod_knit_qcs',function($join){
            $join->on('prod_knit_qcs.id', '=', 'prod_knit_dlv_rolls.prod_knit_qc_id');
            })
            ->join('prod_knit_rcv_by_qcs',function($join){
            $join->on('prod_knit_rcv_by_qcs.id', '=', 'prod_knit_qcs.prod_knit_rcv_by_qc_id');
            })
            ->join('prod_knit_item_rolls',function($join){
            $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
            })
            ->join('prod_knit_items',function($join){
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
            })
            ->join ('prod_knits',function($join){
            $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
            })
            ->join ('suppliers',function($join){
            $join->on('suppliers.id', '=', 'inv_grey_fab_items.supplier_id');
            })
            ->leftJoin ('colorranges',function($join){
            $join->on('colorranges.id', '=', 'inv_grey_fab_items.colorrange_id');
            })
            ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
            })
            ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
            })
            ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
            })
            
            ->where([['prod_finish_dlvs.id','=',$prodfinishdlv->id]])
            ->orderBy('prod_aop_batch_finish_qc_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,2,'.','');
                $prodknitqc->qc_pass_qty=number_format($prodknitqc->qc_pass_qty,2,'.','');
                return $prodknitqc;
            })
            ;
            echo json_encode($prodknitqc);
            
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
    public function store(ProdAopFinishDlvRollRequest $request) {
        $rolls=request('roll_id');
        $rolls_arr=explode(',',$rolls);
        $roll_id=array_unique($rolls_arr);
        foreach( $roll_id as $key=>$prod_batch_finish_qc_roll_id)
        {
        $prodfinishdlvroll=$this->prodfinishdlvroll->create([
        'prod_finish_dlv_id'=>$request->prod_finish_dlv_id,
        'prod_batch_finish_qc_roll_id'=>$prod_batch_finish_qc_roll_id,
        ]);
        }
        if($prodfinishdlvroll)
        {
        return response()->json(array('success' => true,'id' =>  $prodfinishdlvroll->id,'message' => 'Save Successfully'),200);
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProdFinishDlvRollRequest $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodfinishdlvroll->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function importProdFinishQcRoll(){
        $prodfinishdlv=$this->prodfinishdlv->find(request('prod_finish_dlv_id',0));

        //$prodbatchfinishqc=$this->prodbatchfinishqc->find(request('prod_aop_batch_finish_qc_id',0));
        //$prodaopbatch=$this->prodaopbatch->find($prodbatchfinishqc->prod_aop_batch_id);

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'--','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $rollqcresult=array_prepend(config('bprs.rollqcresult'),'-Select-','');



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

        $prodknitqc=$this->prodbatchfinishqc
            ->selectRaw('
            prod_aop_batch_finish_qc_rolls.id,
            prod_aop_batch_finish_qc_rolls.qty as qc_pass_qty,
            prod_aop_batch_finish_qc_rolls.reject_qty,
            prod_aop_batch_finish_qc_rolls.gsm_weight as qc_gsm_weight,
            prod_aop_batch_finish_qc_rolls.dia_width as qc_dia_width,
            prod_aop_batch_finish_qc_rolls.grade_id,
            prod_aop_batch_rolls.id as prod_aop_batch_roll_id,
            so_aop_fabric_isus.issue_no,
            so_aop_fabric_isu_items.id as so_aop_fabric_isu_item_id,
            so_aop_fabric_rcv_rols.id as so_aop_fabric_rcv_rol_id,
            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,
            so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id,
            so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id,
            prod_batch_finish_qc_rolls.qty as rcv_qty,
            prod_batch_finish_qc_rolls.gsm_weight as dyeing_gsm_weight,
            prod_batch_finish_qc_rolls.dia_width as dyeing_dia_width,
            fabriccolors.name as fabric_color,
            inv_grey_fab_items.autoyarn_id,
            inv_grey_fab_items.gmtspart_id,
            inv_grey_fab_items.fabric_look_id,
            inv_grey_fab_items.fabric_shape_id,
            inv_grey_fab_items.gsm_weight as knited_gsm_weight,
            inv_grey_fab_items.dia as knited_dia_width,
            inv_grey_fab_items.measurment as measurement,
            inv_grey_fab_items.roll_length,
            inv_grey_fab_items.stitch_length,
            inv_grey_fab_items.shrink_per,
            inv_grey_fab_items.colorrange_id,
            colorranges.name as colorrange_name,
            inv_grey_fab_items.color_id,
            colors.name as knit_fabric_color,
            inv_grey_fab_items.supplier_id,

            inv_grey_fab_rcv_items.inv_grey_fab_item_id,
            inv_grey_fab_rcv_items.store_id,
            prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id, 
            prod_knits.prod_no,
            prod_knit_items.id as prod_knit_item_id,
            suppliers.name as supplier_name,
            asset_quantity_costs.custom_no as machine_no,
            asset_technical_features.dia_width as machine_dia,
            asset_technical_features.gauge as machine_gg,
            sales_orders.sale_order_no,
            styles.style_ref,
            buyers. name as buyer_name,
            inv_isus.issue_no as kint_issue_no,
            prodbatchfinishdlvrolls.id as prod_finish_dlv_roll_id
            
            ')
            ->join('prod_batch_finish_qc_rolls as prod_aop_batch_finish_qc_rolls',function($join){
            $join->on('prod_batch_finish_qcs.id', '=', 'prod_aop_batch_finish_qc_rolls.prod_batch_finish_qc_id');
            })
            ->join('prod_aop_batch_rolls',function($join){
            $join->on('prod_aop_batch_rolls.id', '=', 'prod_aop_batch_finish_qc_rolls.prod_aop_batch_roll_id');
            })
            ->join('prod_aop_batches',function($join){
            $join->on('prod_aop_batches.id', '=', 'prod_aop_batch_rolls.prod_aop_batch_id');
            })

            ->join('so_aop_fabric_isu_items',function($join){
            $join->on('so_aop_fabric_isu_items.id', '=', 'prod_aop_batch_rolls.so_aop_fabric_isu_item_id');
            })
            ->join('so_aop_fabric_isus',function($join){
            $join->on('so_aop_fabric_isus.id', '=', 'so_aop_fabric_isu_items.so_aop_fabric_isu_id');
            })
            ->join('so_aop_fabric_rcv_rols',function($join){
            $join->on('so_aop_fabric_rcv_rols.id', '=', 'so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id');
            })
            ->join('so_aop_fabric_rcv_items',function($join){
            $join->on('so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id', '=', 'so_aop_fabric_rcv_items.id');
            })
            ->join('prod_finish_dlv_rolls',function($join){
            $join->on('so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id', '=', 'prod_finish_dlv_rolls.id');
            })
            ->join('prod_batch_finish_qc_rolls',function($join){
            $join->on('prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id', '=', 'prod_batch_finish_qc_rolls.id');
            })
            ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_finish_qc_rolls.prod_batch_roll_id', '=', 'prod_batch_rolls.id');
            })
            ->join('prod_batches',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('colors as fabriccolors',function($join){
            $join->on('fabriccolors.id','=','prod_batches.batch_color_id');
            })
            ->join('so_aop_refs',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_fabric_rcv_items.so_aop_ref_id');
            })
            ->join('so_aops',function($join){
            $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
            $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->join('so_aop_po_items',function($join){
            $join->on('so_aop_po_items.so_aop_ref_id', '=', 'so_aop_refs.id');
            })
            ->join('po_aop_service_item_qties',function($join){
            $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->join('po_aop_service_items',function($join){
            $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            // ->join('budget_fabric_prod_cons',function($join){
            // $join->on('budget_fabric_prod_cons.id','=','po_aop_service_item_qties.budget_fabric_prod_con_id');
            // })
            ->join('sales_orders',function($join){
            // $join->on('sales_orders.id','=','budget_fabric_prod_cons.sales_order_id');
            $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
            })
            ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->join('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
            })
            ->join('budget_fabric_prods',function($join){
            $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
            })
            ->join('budget_fabrics',function($join){
            $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->join('style_fabrications',function($join){
            $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
            })
            ->join('so_dyeing_fabric_rcv_rols',function($join){
            $join->on('prod_batch_rolls.so_dyeing_fabric_rcv_rol_id', '=', 'so_dyeing_fabric_rcv_rols.id');
            })
            ->join('inv_grey_fab_isu_items',function($join){
            $join->on('inv_grey_fab_isu_items.id', '=', 'so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id');
            })
            ->join('inv_isus',function($join){
            $join->on('inv_isus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
            })
            ->join('inv_grey_fab_items',function($join){
            $join->on('inv_grey_fab_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_item_id');
            })
            ->join('inv_grey_fab_rcv_items',function($join){
            $join->on('inv_grey_fab_rcv_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id');
            })
            ->join('inv_grey_fab_rcvs',function($join){
            $join->on('inv_grey_fab_rcvs.id', '=', 'inv_grey_fab_rcv_items.inv_grey_fab_rcv_id');
            })
            ->join('inv_rcvs',function($join){
            $join->on('inv_rcvs.id', '=', 'inv_grey_fab_rcvs.inv_rcv_id');
            })
            ->join('prod_knit_dlvs',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'inv_grey_fab_rcvs.prod_knit_dlv_id');
            })
            ->join('prod_knit_dlv_rolls',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'prod_knit_dlv_rolls.prod_knit_dlv_id');
            $join->on('inv_grey_fab_rcv_items.prod_knit_dlv_roll_id', '=', 'prod_knit_dlv_rolls.id');
            })
            ->join('prod_knit_qcs',function($join){
            $join->on('prod_knit_qcs.id', '=', 'prod_knit_dlv_rolls.prod_knit_qc_id');
            })
            ->join('prod_knit_rcv_by_qcs',function($join){
            $join->on('prod_knit_rcv_by_qcs.id', '=', 'prod_knit_qcs.prod_knit_rcv_by_qc_id');
            })
            ->join('prod_knit_item_rolls',function($join){
            $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
            })
            ->join('prod_knit_items',function($join){
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
            })
            ->join ('prod_knits',function($join){
            $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
            })
            ->join ('suppliers',function($join){
            $join->on('suppliers.id', '=', 'inv_grey_fab_items.supplier_id');
            })
            ->leftJoin ('colorranges',function($join){
            $join->on('colorranges.id', '=', 'inv_grey_fab_items.colorrange_id');
            })
            ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
            })
            ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
            })
            ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
            })
            ->leftJoin(\DB::raw("(
            select
            prod_finish_dlv_rolls.id,
            prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
            from prod_finish_dlv_rolls
            where 
            prod_finish_dlv_rolls.deleted_at is null 
            ) prodbatchfinishdlvrolls"),"prodbatchfinishdlvrolls.prod_batch_finish_qc_roll_id","=","prod_aop_batch_finish_qc_rolls.id")
            ->when(request('from_qc_date',0), function ($q){
                return $q->where('prod_batch_finish_qcs.posting_date', '>=',request('from_qc_date',0));
            })
            ->when(request('to_qc_date',0), function ($q){
                return $q->where('prod_batch_finish_qcs.posting_date', '<=',request('to_qc_date',0));
            })
            ->when(request('batch_no'), function ($q) {
                return $q->where('prod_aop_batches.batch_no', '=',request('batch_no', 0));
            })
            ->when(request('batch_date_from',0), function ($q){
                return $q->where('prod_aop_batches.batch_date', '>=',request('batch_date_from',0));
            })
            ->when(request('batch_date_to',0), function ($q){
                return $q->where('prod_aop_batches.batch_date', '<=',request('batch_date_to',0));
            })
            ->where([['so_aops.buyer_id','=',$prodfinishdlv->buyer_id]])
            ->orderBy('prod_aop_batch_finish_qc_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,2);
                $prodknitqc->qc_pass_qty=number_format($prodknitqc->qc_pass_qty,2);
                $prodknitqc->reject_qty=number_format($prodknitqc->reject_qty,2);
                return $prodknitqc;
            })
            ->filter(function($prodknitqc){
                if(!$prodknitqc->prod_finish_dlv_roll_id){
                  return   $prodknitqc;
                }
            })
            ->values()
            ;
            echo json_encode($prodknitqc);
    }
}