<?php

namespace App\Http\Controllers\Production\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRollRepository;
use App\Repositories\Contracts\Production\AOP\ProdAopBatchRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;


use App\Library\Template;
use App\Http\Requests\Production\AOP\ProdAopBatchFinishQcRollRequest;

class ProdAopBatchFinishQcRollController extends Controller {

    private $prodbatchfinishqc;
    private $prodbatchfinishqcroll;
    private $prodaopbatch;
    private $autoyarn;
    private $gmtspart;
    private $itemaccount;

    public function __construct(
        ProdBatchFinishQcRepository $prodbatchfinishqc,  
        ProdBatchFinishQcRollRepository $prodbatchfinishqcroll, 
        ProdAopBatchRepository $prodaopbatch,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        ItemAccountRepository $itemaccount
    ) {
        $this->prodbatchfinishqc = $prodbatchfinishqc;
        $this->prodbatchfinishqcroll = $prodbatchfinishqcroll;
        $this->prodaopbatch = $prodaopbatch;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        
        /*$this->middleware('permission:view.prodaopbatchfinishqcrolls',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodaopbatchfinishqcrolls', ['only' => ['store']]);
        $this->middleware('permission:edit.prodaopbatchfinishqcrolls',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodaopbatchfinishqcrolls', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $prodbatchfinishqc=$this->prodbatchfinishqc->find(request('prod_aop_batch_finish_qc_id',0));
        $prodaopbatch=$this->prodaopbatch->find($prodbatchfinishqc->prod_aop_batch_id);
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

        if($prodaopbatch->batch_for==1){
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
            inv_isus.issue_no as kint_issue_no
            
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
            ->where([['prod_batch_finish_qcs.id','=',$prodbatchfinishqc->id]])
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
                $prodknitqc->reject_qty=number_format($prodknitqc->reject_qty,2,'.','');
                return $prodknitqc;
            })
            ;
            echo json_encode($prodknitqc);
        }
        if($prodaopbatch->batch_for==2){
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
            so_aop_fabric_rcv_rols.id as prod_knit_item_roll_id,
            so_aop_fabric_rcv_rols.custom_no,
            so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id,
            so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id,
            so_aop_fabric_rcv_rols.qty as rcv_qty,

            so_aop_items.autoyarn_id,
            so_aop_items.gmtspart_id,
            so_aop_items.fabric_look_id,
            so_aop_items.fabric_shape_id,
            so_aop_items.gsm_weight as dyeing_gsm_weight,
            so_aop_items.colorrange_id,
            so_aop_items.fabric_color_id,
            so_aop_items.gmt_sale_order_no as sale_order_no,
            so_aop_items.gmt_style_ref as style_ref,
            buyers.name as buyer_name,
            fabriccolors.name as fabric_color
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
            ->join('so_aop_refs',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_fabric_rcv_items.so_aop_ref_id');
            })
            ->join('so_aop_items',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_items.so_aop_ref_id');
            })
            ->join('so_aops',function($join){
            $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','so_aop_items.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin ('buyers',function($join){
            $join->on('buyers.id', '=', 'so_aop_items.gmt_buyer');
            })
            ->leftJoin('colors as fabriccolors',function($join){
            $join->on('fabriccolors.id','=','so_aop_items.fabric_color_id');
            })
            ->where([['prod_batch_finish_qcs.id','=',$prodbatchfinishqc->id]])
            ->orderBy('prod_aop_batch_finish_qc_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult){
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';

            $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,2,'.','');
            $prodknitqc->qc_pass_qty=number_format($prodknitqc->qc_pass_qty,2,'.','');
            $prodknitqc->reject_qty=number_format($prodknitqc->reject_qty,2,'.','');
            return $prodknitqc;
            });
            echo json_encode($prodknitqc);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $prod_aop_batch_roll_ids=explode(",",request('prod_aop_batch_roll_ids',0));
        $prodbatchfinishqc=$this->prodbatchfinishqc->find(request('prod_aop_batch_finish_qc_id',0));
        $prodaopbatch=$this->prodaopbatch->find($prodbatchfinishqc->prod_aop_batch_id);
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

        if($prodaopbatch->batch_for==1){
            $prodknitqc=$this->prodaopbatch
            ->selectRaw('
            prod_aop_batch_rolls.id,
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
            ->join('prod_aop_batch_rolls',function($join){
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
            //$join->on('sales_orders.id','=','budget_fabric_prod_cons.sales_order_id');
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
            ->where([['prod_aop_batches.id','=',$prodaopbatch->id]])
            ->whereIn('prod_aop_batch_rolls.id',$prod_aop_batch_roll_ids)
            ->orderBy('prod_aop_batch_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,2,'.','');
                return $prodknitqc;
            })
            ;
            //echo json_encode($prodknitqc);
        }
        if($prodaopbatch->batch_for==2){
            $prodknitqc=$this->prodaopbatch
            ->selectRaw('
            prod_aop_batch_rolls.id,
            so_aop_fabric_isus.issue_no,
            so_aop_fabric_isu_items.id as so_aop_fabric_isu_item_id,
            so_aop_fabric_rcv_rols.id as so_aop_fabric_rcv_rol_id,
            so_aop_fabric_rcv_rols.id as prod_knit_item_roll_id,
            so_aop_fabric_rcv_rols.custom_no,
            so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id,
            so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id,
            so_aop_fabric_rcv_rols.qty as rcv_qty,

            so_aop_items.autoyarn_id,
            so_aop_items.gmtspart_id,
            so_aop_items.fabric_look_id,
            so_aop_items.fabric_shape_id,
            so_aop_items.gsm_weight as dyeing_gsm_weight,
            so_aop_items.colorrange_id,
            so_aop_items.fabric_color_id,
            so_aop_items.gmt_sale_order_no as sale_order_no,
            so_aop_items.gmt_style_ref as style_ref,
            buyers.name as buyer_name,
            fabriccolors.name as fabric_color
            ')
            ->join('prod_aop_batch_rolls',function($join){
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
            ->join('so_aop_refs',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_fabric_rcv_items.so_aop_ref_id');
            })
            ->join('so_aop_items',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_items.so_aop_ref_id');
            })
            ->join('so_aops',function($join){
            $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','so_aop_items.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin ('buyers',function($join){
            $join->on('buyers.id', '=', 'so_aop_items.gmt_buyer');
            })
            ->leftJoin('colors as fabriccolors',function($join){
            $join->on('fabriccolors.id','=','so_aop_items.fabric_color_id');
            })
            ->where([['prod_aop_batches.id','=',$prodaopbatch->id]])
            ->whereIn('prod_aop_batch_rolls.id',$prod_aop_batch_roll_ids)
            ->orderBy('prod_aop_batch_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype){
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,2,'.','');
            return $prodknitqc;
            });
            //echo json_encode($prodknitqc);
        }
        return Template::loadView('Production.AOP.ProdAopBatchFinishQcMatrix', [ 
            'data'=> $prodknitqc,
            'rollqcresult'=> $rollqcresult,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdAopBatchFinishQcRollRequest $request) {
       

        foreach($request->prod_aop_batch_roll_id as $index=>$prod_aop_batch_roll_id)
        {
            $prodbatchfinishqcroll = $this->prodbatchfinishqcroll->create([
            'prod_batch_finish_qc_id'=>$request->prod_aop_batch_finish_qc_id,
            'prod_aop_batch_roll_id'=>$prod_aop_batch_roll_id,
            'qty'=>$request->qty[$index],
            'reject_qty'=>$request->reject_qty[$index],
            'grade_id'=>$request->grade_id[$index],
            'gsm_weight'=>$request->gsm_weight[$index],
            'dia_width'=>$request->dia_width[$index],
            ]);
        }
        if($prodbatchfinishqcroll){
            return response()->json(array('success' => true,'id' =>  $prodbatchfinishqcroll->id,'message' => 'Save Successfully'),200);
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
        //$rows=$this->prodbatchfinishqcroll->find($id);
        $rows=$this->prodbatchfinishqcroll
        ->join('prod_aop_batch_rolls',function($join){
            $join->on('prod_aop_batch_rolls.id', '=', 'prod_batch_finish_qc_rolls.prod_aop_batch_roll_id');
        })
        ->where([['prod_batch_finish_qc_rolls.id','=',$id]])
        ->get([
            'prod_batch_finish_qc_rolls.*',
            'prod_aop_batch_rolls.qty as batch_qty',
        ])
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
    public function update(ProdAopBatchFinishQcRollRequest $request, $id) {
        $prodbatchfinishqcroll = $this->prodbatchfinishqcroll->update($id,$request->except(['id','batch_qty','prod_aop_batch_roll_id','prod_aop_batch_finish_qc_id']));

        if($prodbatchfinishqcroll){
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
        if($this->prodbatchfinishqcroll->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getRoll(){

        $prodbatchfinishqc=$this->prodbatchfinishqc->find(request('prod_aop_batch_finish_qc_id',0));
        $prodaopbatch=$this->prodaopbatch->find($prodbatchfinishqc->prod_aop_batch_id);
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'--','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');



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

        if($prodaopbatch->batch_for==1){
            $prodknitqc=$this->prodaopbatch
            ->selectRaw('
            prod_aop_batch_rolls.id,
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
            prodbatchfinishqcrolls.id as prod_batch_finish_qc_roll_id
            
            ')
            ->join('prod_aop_batch_rolls',function($join){
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
            prod_batch_finish_qc_rolls.id,
            prod_batch_finish_qc_rolls.prod_aop_batch_roll_id
            from prod_batch_finish_qc_rolls
            where 
            prod_batch_finish_qc_rolls.deleted_at is null 
            ) prodbatchfinishqcrolls"),"prodbatchfinishqcrolls.prod_aop_batch_roll_id","=","prod_aop_batch_rolls.id")
            ->where([['prod_aop_batches.id','=',$prodaopbatch->id]])
            ->orderBy('prod_aop_batch_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,2,'.','');
                return $prodknitqc;
            })
            ->filter(function($prodknitqc){
                if(!$prodknitqc->prod_batch_finish_qc_roll_id){
                  return   $prodknitqc;
                }
            })
            ->values();
            
            echo json_encode($prodknitqc);
        }
        if($prodaopbatch->batch_for==2){
            $prodknitqc=$this->prodaopbatch
            ->selectRaw('
            prod_aop_batch_rolls.id,
            so_aop_fabric_isus.issue_no,
            so_aop_fabric_isu_items.id as so_aop_fabric_isu_item_id,
            so_aop_fabric_rcv_rols.id as so_aop_fabric_rcv_rol_id,
            so_aop_fabric_rcv_rols.id as prod_knit_item_roll_id,
            so_aop_fabric_rcv_rols.custom_no,
            so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id,
            so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id,
            so_aop_fabric_rcv_rols.qty as rcv_qty,

            so_aop_items.autoyarn_id,
            so_aop_items.gmtspart_id,
            so_aop_items.fabric_look_id,
            so_aop_items.fabric_shape_id,
            so_aop_items.gsm_weight as dyeing_gsm_weight,
            so_aop_items.colorrange_id,
            so_aop_items.fabric_color_id,
            so_aop_items.gmt_sale_order_no as sale_order_no,
            so_aop_items.gmt_style_ref as style_ref,
            buyers.name as buyer_name,
            fabriccolors.name as fabric_color,
            prodbatchfinishqcrolls.id as prod_batch_finish_qc_roll_id
            ')
            ->join('prod_aop_batch_rolls',function($join){
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
            ->join('so_aop_refs',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_fabric_rcv_items.so_aop_ref_id');
            })
            ->join('so_aop_items',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_items.so_aop_ref_id');
            })
            ->join('so_aops',function($join){
            $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','so_aop_items.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin ('buyers',function($join){
            $join->on('buyers.id', '=', 'so_aop_items.gmt_buyer');
            })
            ->leftJoin('colors as fabriccolors',function($join){
            $join->on('fabriccolors.id','=','so_aop_items.fabric_color_id');
            })
            ->leftJoin(\DB::raw("(
            select
            prod_batch_finish_qc_rolls.id,
            prod_batch_finish_qc_rolls.prod_aop_batch_roll_id
            from prod_batch_finish_qc_rolls
            where 
            prod_batch_finish_qc_rolls.deleted_at is null 
            ) prodbatchfinishqcrolls"),"prodbatchfinishqcrolls.prod_aop_batch_roll_id","=","prod_aop_batch_rolls.id")
            ->where([['prod_aop_batches.id','=',$prodaopbatch->id]])
            ->orderBy('prod_aop_batch_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype){
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,2,'.','');
            return $prodknitqc;
            })
            ->filter(function($prodknitqc){
                if(!$prodknitqc->prod_batch_finish_qc_roll_id){
                  return   $prodknitqc;
                }
            })
            ->values();
            echo json_encode($prodknitqc);
        }
    }

    public function importRoll(Request $request) {

        $rollqcresult=config('bprs.rollqcresult');
        if($request->file_src->getClientOriginalExtension()!=='csv'){
            return response()->json(array('success' => false,  'message' => 'Wrong File Format, Please Select a .csv file'), 200);
        }

        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);
        $path= public_path('images').'/'.$name;
        $row = 1;
        \DB::beginTransaction();
        
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                    if($row==1){
                    }
                    else{
                        try
                        {
                            if($data[8]!=NULL && $data[3]!=NULL){
                                $grade_id = array_search(strtoupper($data[5]), $rollqcresult);
                                $prodbatchfinishqcroll = $this->prodbatchfinishqcroll->updateOrCreate([
                                    'prod_batch_finish_qc_id'=>$request->prod_aop_batch_finish_qc_id,
                                    'prod_aop_batch_roll_id'=>$data[8],
                                ],
                                [
                                    'qty'=>$data[3],
                                    'reject_qty'=>$data[4],
                                    'grade_id'=>$grade_id,
                                    'gsm_weight'=>$data[6],
                                    'dia_width'=>$data[7],
                                ]
                                );
                            }
                        }
                        catch(EXCEPTION $e)
                        {
                            \DB::rollback();
                            unlink($path);
                            throw $e;
                        }
                    }
                $row++;
            }
            fclose($handle);
        }
        \DB::commit();
        unlink($path);
        return response()->json(array('success' => true,'id' =>  $prodbatchfinishqcroll->id,'message' => 'Save Successfully'),200);


        /*foreach($request->prod_batch_roll_id as $index=>$prod_batch_roll_id)
        {
            $prodbatchfinishqcroll = $this->prodbatchfinishqcroll->create([
            'prod_batch_finish_qc_id'=>$request->prod_batch_finish_qc_id,
            'prod_batch_roll_id'=>$prod_batch_roll_id,
            'qty'=>$request->qty[$index],
            'reject_qty'=>$request->reject_qty[$index],
            'grade_id'=>$request->grade_id[$index],
            'gsm_weight'=>$request->gsm_weight[$index],
            'dia_width'=>$request->dia_width[$index],
            ]);
        }
        if($prodbatchfinishqcroll){
            return response()->json(array('success' => true,'id' =>  $prodbatchfinishqcroll->id,'message' => 'Save Successfully'),200);
        }*/
    }
}