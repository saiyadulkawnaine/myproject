<?php

namespace App\Http\Controllers\Inventory\FinishFabric;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvItemRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabTransactionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\FinishFabric\InvFinishFabIsuRtnItemRequest;

class InvFinishFabIsuRtnItemController extends Controller {

    private $invrcv;
    private $invfinishfabrcv;
    private $invfinishfabrcvitem;
    private $invfinishfabtransaction;
    private $itemaccount;
    private $invisu;
    private $gmtspart;
    private $autoyarn;

    public function __construct(
        InvRcvRepository $invrcv,
        InvFinishFabRcvRepository $invfinishfabrcv, 
        InvFinishFabRcvItemRepository $invfinishfabrcvitem,
        InvFinishFabTransactionRepository $invfinishfabtransaction, 
        ItemAccountRepository $itemaccount,
        InvIsuRepository $invisu,
        GmtspartRepository $gmtspart,
        AutoyarnRepository $autoyarn
    ) {
        $this->invrcv = $invrcv;
        $this->invfinishfabrcv = $invfinishfabrcv;
        $this->invfinishfabrcvitem = $invfinishfabrcvitem;
        $this->invfinishfabtransaction = $invfinishfabtransaction;
        $this->itemaccount = $itemaccount;
        $this->invisu = $invisu;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->middleware('auth');
        $this->middleware('permission:view.invfinishfabisurtnitems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invfinishfabisurtnitems', ['only' => ['store']]);
        $this->middleware('permission:edit.invfinishfabisurtnitems',   ['only' => ['update']]);
        $this->middleware('permission:delete.invfinishfabisurtnitems', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $inv_finish_fab_rcv_id=request('inv_finish_fab_rcv_id',0);
            $invfinishfabrcv=$this->invfinishfabrcv->find($inv_finish_fab_rcv_id);
            $invcv=$this->invrcv->find($invfinishfabrcv->inv_rcv_id);
            $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'--','');
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


            $yarnDescription=$this->itemaccount
            ->leftJoin('item_account_ratios',function($join){
            $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
            })
            ->leftJoin('compositions',function($join){
            $join->on('compositions.id','=','item_account_ratios.composition_id');
            })
            ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })

            ->where([['itemcategories.identity','=',1]])
            ->orderBy('item_account_ratios.ratio','desc')
            ->get([
            'item_accounts.id',
            'compositions.name as composition_name',
            'item_account_ratios.ratio',
            ]);

            $itemaccountArr=array();
            $yarnCompositionArr=array();
            foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
                $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
            }

            $yarnDropdown=array();
            foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
            }

             $yarn=$this->invrcv
            ->selectRaw('
                prod_knits.prod_no,
                prod_knit_items.id as prod_knit_item_id,
                prod_knit_item_rolls.id as prod_knit_item_roll_id,
                prod_knit_item_rolls.custom_no,
                prod_knit_item_yarns.id as prod_knit_item_yarn_id,
                inv_yarn_items.lot,
                inv_yarn_items.brand,
                colors.name as color_name,
                itemcategories.name as itemcategory_name,
                itemclasses.name as itemclass_name,
                item_accounts.id as item_account_id,
                yarncounts.count,
                yarncounts.symbol,
                yarntypes.name as yarn_type,
                uoms.code as uom_code
            ')
            ->join('inv_finish_fab_rcvs',function($join){
            $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
            })
            ->join('inv_finish_fab_rcv_items',function($join){
            $join->on('inv_finish_fab_rcv_items.inv_finish_fab_rcv_id', '=', 'inv_finish_fab_rcvs.id');
            })
            ->join('inv_finish_fab_items',function($join){
            $join->on('inv_finish_fab_items.id', '=', 'inv_finish_fab_rcv_items.inv_finish_fab_item_id');
            })

            ->join('prod_finish_dlv_rolls',function($join){
            //$join->on('prod_finish_dlvs.id', '=', 'prod_finish_dlv_rolls.prod_finish_dlv_id');
            $join->on('inv_finish_fab_rcv_items.prod_finish_dlv_roll_id', '=', 'prod_finish_dlv_rolls.id');
            })
            ->join('prod_finish_dlvs',function($join){
            $join->on('prod_finish_dlvs.id', '=', 'prod_finish_dlv_rolls.prod_finish_dlv_id');
            })
            ->join('prod_batch_finish_qc_rolls',function($join){
            $join->on('prod_batch_finish_qc_rolls.id', '=', 'prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id');
            })
            ->join('prod_batch_finish_qcs',function($join){
            $join->on('prod_batch_finish_qc_rolls.prod_batch_finish_qc_id', '=', 'prod_batch_finish_qcs.id');
            })
            ->join('prod_batches',function($join){
            $join->on('prod_batch_finish_qcs.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.id', '=', 'prod_batch_finish_qc_rolls.prod_batch_roll_id');
            })
            ->join('so_dyeing_fabric_rcv_rols',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
            })
            ->join('so_dyeing_fabric_rcv_items',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
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
            ->leftJoin('prod_knit_item_yarns',function($join){
                $join->on('prod_knit_items.id', '=', 'prod_knit_item_yarns.prod_knit_item_id');
            })
            ->leftJoin('inv_yarn_isu_items',function($join){
                $join->on('inv_yarn_isu_items.id', '=', 'prod_knit_item_yarns.inv_yarn_isu_item_id');
            })
            ->leftJoin('inv_yarn_items',function($join){
                $join->on('inv_yarn_items.id', '=', 'inv_yarn_isu_items.inv_yarn_item_id');
            })
            ->leftJoin('item_accounts',function($join){
            $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
            })
            ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
            })
            ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
            })
            ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id');
            })

            ->join ('prod_knits',function($join){
                $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
            })
            ->where([['inv_rcvs.id','=',$invfinishfabrcv->inv_rcv_id]])
            ->orderBy('inv_finish_fab_rcv_items.id','desc')
            ->get()
            ->map(function($yarn) use($yarnDropdown){
                $yarn->yarn_count=$yarn->count."/".$yarn->symbol;
                $yarn->composition=$yarn->item_account_id?$yarnDropdown[$yarn->item_account_id]:'';
                return $yarn;
            });
            $yarnDtls=[];
            foreach($yarn as $yar){
                $yarnDtls[$yar->prod_knit_item_id][$yar->prod_knit_item_yarn_id]=$yar->itemclass_name." ".$yar->yarn_count." ".$yar->composition." ".$yar->yarn_type." ".$yar->brand." ".$yar->lot." ".$yar->color_name;

            }
            

            /*$prodknitqc=$this->invrcv
            ->selectRaw('
                inv_finish_fab_rcv_items.id,
                inv_finish_fab_rcv_items.qty,
                prod_finish_dlv_rolls.id as prod_finish_dlv_roll_id,
                prod_batch_finish_qc_rolls.id as prod_batch_finish_qc_roll_id,
                prod_batch_finish_qc_rolls.qty as roll_weight,
                prod_batch_finish_qc_rolls.reject_qty,
                prod_batch_finish_qc_rolls.gsm_weight as qc_gsm_weight,
                prod_batch_finish_qc_rolls.dia_width as qc_dia_width,
                prod_batch_finish_qc_rolls.grade_id,
                prod_batch_rolls.id as prod_batch_roll_id,
                prod_batch_rolls.qty as batch_qty,
                so_dyeing_fabric_rcv_rols.id as so_dyeing_fabric_rcv_rol_id,
                so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id,
                so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
                inv_grey_fab_isu_items.qty as rcv_qty,

                inv_grey_fab_items.autoyarn_id,
                inv_grey_fab_items.gmtspart_id,
                inv_grey_fab_items.fabric_look_id,
                inv_grey_fab_items.fabric_shape_id,
                inv_grey_fab_items.gsm_weight,
                inv_grey_fab_items.dia as dia_width,
                inv_grey_fab_items.measurment as measurement,
                inv_grey_fab_items.roll_length,
                inv_grey_fab_items.stitch_length,
                inv_grey_fab_items.shrink_per,
                inv_grey_fab_items.colorrange_id,
                colorranges.name as colorrange_name,
                inv_grey_fab_items.color_id,
                colors.name as fabric_color,
                inv_grey_fab_items.supplier_id,

                inv_grey_fab_rcv_items.inv_grey_fab_item_id,
                inv_grey_fab_rcv_items.store_id,
                prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id, 
                prod_knits.prod_no,

                prod_knit_item_rolls.id as prod_knit_item_roll_id,
                prod_knit_item_rolls.custom_no,
                prod_knit_items.id as prod_knit_item_id,

                suppliers.name as supplier_name,
                asset_quantity_costs.custom_no as machine_no,
                asset_technical_features.dia_width as machine_dia,
                asset_technical_features.gauge as machine_gg,
                gmtssamples.name as gmt_sample,
                
                sales_orders.sale_order_no,
                styles.style_ref,
                buyers. name as buyer_name,
                style_fabrications.dyeing_type_id,
                po_dyeing_service_item_qties.fabric_color_id,
                dyeingcolors.name as fabric_color_name,
                batch_colors.name as batch_color_name,
                batch_colors.id as batch_color_id,
                stores.name as store_name,
                CASE 
                WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
                ELSE inhouseprods.customer_name
                END as customer_name
            ')

            ->join('inv_finish_fab_rcvs',function($join){
            $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
            })
            ->join('inv_finish_fab_rcv_items',function($join){
            $join->on('inv_finish_fab_rcv_items.inv_finish_fab_rcv_id', '=', 'inv_finish_fab_rcvs.id');
            })
            ->join('inv_finish_fab_items',function($join){
            $join->on('inv_finish_fab_items.id', '=', 'inv_finish_fab_rcv_items.inv_finish_fab_item_id');
            })

            
            ->join('prod_finish_dlv_rolls',function($join){
            //$join->on('prod_finish_dlvs.id', '=', 'prod_finish_dlv_rolls.prod_finish_dlv_id');
            $join->on('inv_finish_fab_rcv_items.prod_finish_dlv_roll_id', '=', 'prod_finish_dlv_rolls.id');
            })
            ->join('prod_finish_dlvs',function($join){
            $join->on('prod_finish_dlvs.id', '=', 'prod_finish_dlv_rolls.prod_finish_dlv_id');
            })
            ->join('prod_batch_finish_qc_rolls',function($join){
            $join->on('prod_batch_finish_qc_rolls.id', '=', 'prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id');
            })
            ->join('prod_batch_finish_qcs',function($join){
            $join->on('prod_batch_finish_qc_rolls.prod_batch_finish_qc_id', '=', 'prod_batch_finish_qcs.id');
            })
            ->join('prod_batches',function($join){
            $join->on('prod_batch_finish_qcs.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.id', '=', 'prod_batch_finish_qc_rolls.prod_batch_roll_id');
            })
            ->join('so_dyeing_fabric_rcv_rols',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
            })
            ->join('so_dyeing_fabric_rcv_items',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
            })
            ->join('so_dyeing_refs',function($join){
                $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
            })
            ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_pos',function($join){
                $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
            })
            ->join('so_dyeing_po_items',function($join){
                $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
            })
            ->join('po_dyeing_service_item_qties',function($join){
            $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
            })
            ->join('po_dyeing_service_items',function($join){
            $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
            })

            ->join('sales_orders',function($join){
            $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
            })
            ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->join('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
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
            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
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
            
            ->leftJoin('gmtssamples',function($join){
                $join->on('gmtssamples.id','=','prod_knit_item_rolls.gmt_sample');
            })
            ->leftJoin('colors as dyeingcolors',function($join){
                $join->on('dyeingcolors.id','=','po_dyeing_service_item_qties.fabric_color_id');
            })
            ->leftJoin('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
            })
            ->leftJoin('stores',function($join){
            $join->on('stores.id','=','inv_finish_fab_rcv_items.store_id');
            })

            ->leftJoin(\DB::raw("(
                select 
                pl_knit_items.id,
                colorranges.name as colorrange_name,
                colorranges.id as colorrange_id,
                customer.name as customer_name,
                companies.id as company_id,
                CASE 
                WHEN  style_fabrications.autoyarn_id IS NULL THEN so_knit_items.autoyarn_id 
                ELSE style_fabrications.autoyarn_id
                END as autoyarn_id,

                CASE 
                WHEN  style_fabrications.gmtspart_id IS NULL THEN so_knit_items.gmtspart_id 
                ELSE style_fabrications.gmtspart_id
                END as gmtspart_id,

                CASE 
                WHEN  style_fabrications.fabric_look_id IS NULL THEN so_knit_items.fabric_look_id 
                ELSE style_fabrications.fabric_look_id
                END as fabric_look_id,

                CASE 
                WHEN  style_fabrications.fabric_shape_id IS NULL THEN so_knit_items.fabric_shape_id 
                ELSE style_fabrications.fabric_shape_id
                END as fabric_shape_id,
                CASE 
                WHEN sales_orders.sale_order_no IS NULL THEN so_knit_items.gmt_sale_order_no 
                ELSE sales_orders.sale_order_no
                END as sale_order_no,
                CASE 
                WHEN sales_orders.id IS NULL THEN 0
                ELSE sales_orders.id
                END as sale_order_id,
                CASE 
                WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
                ELSE styles.style_ref
                END as style_ref,
                CASE 
                WHEN styles.id IS NULL THEN 0 
                ELSE styles.id
                END as style_id,
                CASE 
                WHEN buyers.name IS NULL THEN outbuyers.name 
                ELSE buyers.name
                END as buyer_name,

                CASE 
                WHEN buyers.id IS NULL THEN outbuyers.id 
                ELSE buyers.id
                END as buyer_id

                from pl_knit_items
                join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
                left join colorranges on colorranges.id=pl_knit_items.colorrange_id
                join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
                left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
                left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
                left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
                and po_knit_service_items.deleted_at is null
                left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
                left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
                left join so_knits on so_knits.id=so_knit_refs.so_knit_id
                left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                left join jobs on jobs.id=sales_orders.job_id
                left join styles on styles.id=jobs.style_id
                left join buyers on buyers.id=styles.buyer_id
                left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
                left join buyers customer on customer.id=so_knits.buyer_id
                left join companies  on companies.id=customer.company_id
            ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
            ->leftJoin(\DB::raw("(
                select 
                po_knit_service_item_qties.id,
                colorranges.name as colorrange_name,
                colorranges.id as colorrange_id,
                style_fabrications.autoyarn_id,
                style_fabrications.gmtspart_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                sales_orders.sale_order_no,
                sales_orders.id as sale_order_id,
                styles.style_ref,
                styles.id as style_id,
                buyers.name as buyer_name,
                buyers.id as buyer_id,
                companies.name as customer_name,
                companies.id as company_id   
                from 
                po_knit_service_item_qties
                join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
                join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
                left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
                join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
                join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                
                left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                left join jobs on jobs.id=sales_orders.job_id
                left join styles on styles.id=jobs.style_id
                left join buyers on buyers.id=styles.buyer_id
                left join companies on companies.id=po_knit_services.company_id
                order by po_knit_service_item_qties.id
            ) outhouseprods"),"outhouseprods.id","=","prod_knit_items.po_knit_service_item_qty_id")
            ->leftJoin('companies',function($join){
                $join->on('companies.id','=','outhouseprods.company_id');
                $join->Oron('companies.id','=','inhouseprods.company_id');
            })
            ->where([['inv_rcvs.id','=',$invfinishfabrcv->inv_rcv_id]])
            ->orderBy('inv_finish_fab_rcv_items.id','desc')
            ->get()
            ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$yarnDtls,$rollqcresult){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->dyetype=$prodknitqc->dyeing_type_id?$dyetype[$prodknitqc->dyeing_type_id]:'';
                $prodknitqc->yarndtl=$prodknitqc->prod_knit_item_id?implode(',',$yarnDtls[$prodknitqc->prod_knit_item_id]):'';
                $prodknitqc->bal_qty=$prodknitqc->rcv_qty-($prodknitqc->tot_batch_qty-$prodknitqc->batch_qty);
                $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';

                return $prodknitqc;
            });
            echo json_encode($prodknitqc);*/
            $prodknitqc = collect(
            \DB::select("
            select 
            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,

            prod_knit_item_rolls.roll_weight,
            prod_knit_item_rolls.width,
            prod_knit_item_rolls.qty_pcs,
            prod_knit_items.id as prod_knit_item_id,
            prod_knit_items.prod_knit_id,
            prod_knit_items.stitch_length,
            prod_knits.prod_no,
            prod_knit_qcs.measurement,   
            prod_knit_qcs.roll_length,   
            prod_knit_qcs.shrink_per, 
            inv_finish_fab_items.autoyarn_id,
            inv_finish_fab_items.gmtspart_id,
            inv_finish_fab_items.fabric_look_id,
            inv_finish_fab_items.fabric_shape_id,
            prod_batch_finish_qc_rolls.gsm_weight,   
            prod_batch_finish_qc_rolls.dia_width,
            prod_batch_finish_qc_rolls.grade_id,

            styles.style_ref,
            buyers.name as buyer_name,
            sales_orders.sale_order_no,
            CASE 
            WHEN  dyeingbatch.batch_color_name IS NULL THEN aopbatch.batch_color_name 
            ELSE dyeingbatch.batch_color_name
            END as batch_color_name,

            CASE 
            WHEN  dyeingbatch.customer_name IS NULL THEN aopbatch.customer_name 
            ELSE dyeingbatch.customer_name
            END as customer_name,

            CASE 
            WHEN  dyeingbatch.dyeing_batch_no IS NULL THEN aopbatch.dyeing_batch_no 
            ELSE dyeingbatch.dyeing_batch_no
            END as dyeing_batch_no,
            aopbatch.aop_batch_no, 

            inv_finish_fab_rcv_items.id, 
            inv_finish_fab_rcv_items.store_qty as qty,
            inv_finish_fab_rcv_items.inv_finish_fab_item_id,
            inv_finish_fab_rcv_items.store_id,
            stores.name as store_name



            from 
            inv_rcvs

            inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
            inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
            inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
            inner join prod_finish_dlv_rolls on inv_finish_fab_rcv_items.prod_finish_dlv_roll_id = prod_finish_dlv_rolls.id 
            inner join prod_finish_dlvs on prod_finish_dlv_rolls.prod_finish_dlv_id=prod_finish_dlvs.id

            and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
            inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
            inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
            left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
            left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
            left join stores on stores.id=inv_finish_fab_rcv_items.store_id

            left join (
            select 
            prod_batches.id,
            prod_batches.batch_no as dyeing_batch_no,
            prod_batch_rolls.id as prod_batch_roll_id,
            batch_colors.name as batch_color_name,
            customers.name as customer_name,
            po_dyeing_service_item_qties.sales_order_id,
            budget_fabric_prods.budget_fabric_id,
            so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id

            from 
            prod_batches
            inner join prod_batch_rolls on prod_batch_rolls.prod_batch_id = prod_batches.id
            inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
            inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
            inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
            inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
            inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
            inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
            inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
            inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
            left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
            left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
            inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
            inner join buyers  customers on customers.id = so_dyeings.buyer_id 
            ) dyeingbatch on dyeingbatch.prod_batch_roll_id=prod_batch_finish_qc_rolls.prod_batch_roll_id

            left join (
            select 
            prod_aop_batches.id,
            prod_aop_batches.batch_no as aop_batch_no,
            prod_aop_batch_rolls.id as prod_aop_batch_roll_id,
            batch_colors.name as batch_color_name,
            customers.name as customer_name,
            po_aop_service_item_qties.sales_order_id,
            budget_fabric_prods.budget_fabric_id,
            so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
            prod_batches.batch_no as dyeing_batch_no



            from 
            prod_aop_batches
            inner join prod_aop_batch_rolls on prod_aop_batch_rolls.prod_aop_batch_id = prod_aop_batches.id
            inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
            inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
            inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
            inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
            inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id
            inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id

            inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
            inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
            inner join colors batch_colors on batch_colors.id = prod_batches.batch_color_id
            inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id

            inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
            inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
            inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
            inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
            inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
            inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
            inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
            inner join buyers  customers on customers.id = so_aops.buyer_id 

            ) aopbatch on aopbatch.prod_aop_batch_roll_id=prod_batch_finish_qc_rolls.prod_aop_batch_roll_id

            inner join sales_orders on  (sales_orders.id = dyeingbatch.sales_order_id or sales_orders.id = aopbatch.sales_order_id) 
            inner join jobs on jobs.id = sales_orders.job_id
            inner join styles on styles.id = jobs.style_id
            inner join buyers on buyers.id = styles.buyer_id

            inner join budget_fabrics on (budget_fabrics.id = dyeingbatch.budget_fabric_id or budget_fabrics.id = aopbatch.budget_fabric_id) 
            inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
            inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
            inner join constructions on constructions.id = autoyarns.construction_id

            inner join inv_grey_fab_isu_items on (inv_grey_fab_isu_items.id = dyeingbatch.inv_grey_fab_isu_item_id or inv_grey_fab_isu_items.id = aopbatch.inv_grey_fab_isu_item_id)
            inner join inv_isus grey_fab_isus on grey_fab_isus.id = inv_grey_fab_isu_items.inv_isu_id
            inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
            inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id

            inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
            inner join inv_rcvs grey_fab_rcvs  on grey_fab_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
            inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
            inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
            and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id

            inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
            inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
            inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
            inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
            inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
            left join colors on  colors.id=prod_knit_item_rolls.fabric_color
            where inv_rcvs.id = ?  and inv_rcvs.deleted_at is null
            order by inv_finish_fab_rcv_items.id desc
            ",[$invfinishfabrcv->inv_rcv_id])
            )
            ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';
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
    public function store(InvFinishFabIsuRtnItemRequest $request) {
        if($request->qty==0){
         return response()->json(array('success' =>false ,'id'=>0,'inv_isu_id'=>$request->inv_finish_fab_rcv_id,'message'=>'Zero qty  not allowed'),200);
        }

      $invfinishfabrcv=$this->invrcv->find($request->inv_rcv_id);
      \DB::beginTransaction();
      try
      {
        $invfinishfabrcvitem = $this->invfinishfabrcvitem->create(
        [
        'inv_finish_fab_rcv_id'=> $request->inv_finish_fab_rcv_id,         
        'prod_finish_dlv_roll_id'=> $request->prod_finish_dlv_roll_id,
        'inv_finish_fab_item_id'=> $request->inv_finish_fab_item_id,          
        'inv_finish_fab_isu_item_id'=> $request->inv_finish_fab_isu_item_id,          
        'store_id'=> $request->store_id,
        'qty' => $request->qty,
        'rate' => 0,
        'amount'=> 0,
        'store_qty' => $request->qty,
        'store_rate' => 0,
        'store_amount'=> 0,
        'room'=> $request->room,     
        'rack'=> $request->rack,     
        'shelf'=> $request->shelf,
        'remarks' => $request->remarks     
        ]);

        $invfinishfabtransaction=$this->invfinishfabtransaction->create(
        [
        'trans_type_id'=>1,
        'trans_date'=>$invfinishfabrcv->receive_date,
        'inv_finish_fab_rcv_item_id'=>$invfinishfabrcvitem->id,
        'inv_finish_fab_isu_item_id'=> $request->inv_finish_fab_isu_item_id,
        'inv_finish_fab_item_id'=>$request->inv_finish_fab_item_id,
        'company_id'=>$invfinishfabrcv->company_id,
        'supplier_id'=>$invfinishfabrcv->supplier_id,
        'store_id'=>$request->store_id,
        'store_qty' => $request->qty,
        'store_rate' => 0,
        'store_amount'=> 0
        ]);
      }
      catch(EXCEPTION $e)
      {
        \DB::rollback();
        throw $e;
      }
      \DB::commit();
      if($invfinishfabrcvitem){
        return response()->json(array('success' =>true ,'id'=>$invfinishfabrcvitem->id, 'inv_finish_fab_rcv_id'=>$request->inv_finish_fab_rcv_id,'message'=>'Saved Successfully'),200);
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
        $invfinishfabrcvitem=$this->invfinishfabrcvitem->find($id);
        $row ['fromData'] = $invfinishfabrcvitem;
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
    public function update(InvFinishFabIsuRtnItemRequest $request, $id) {
    if($request->qty==0){
     return response()->json(array('success' =>false ,'id'=>0,'inv_isu_id'=>$request->inv_finish_fab_rcv_id,'message'=>'Zero qty  not allowed'),200);
    }
      $invfinishfabrcv=$this->invrcv->find($request->inv_rcv_id);
      \DB::beginTransaction();
      try
      {
        $invfinishfabrcvitem = $this->invfinishfabrcvitem->update($id,
        [
        //'inv_finish_fab_rcv_id'=> $request->inv_finish_fab_rcv_id,         
        //'prod_finish_dlv_roll_id'=> $request->prod_finish_dlv_roll_id,
        'inv_finish_fab_item_id'=> $request->inv_finish_fab_item_id,          
        //'inv_finish_fab_isu_item_id'=> $request->inv_finish_fab_isu_item_id,          
        'store_id'=> $request->store_id,
        'qty' => $request->qty,
        'rate' => 0,
        'amount'=> 0,
        'store_qty' => $request->qty,
        'store_rate' => 0,
        'store_amount'=> 0,
        'room'=> $request->room,     
        'rack'=> $request->rack,     
        'shelf'=> $request->shelf,
        'remarks' => $request->remarks     
        ]);

        $invfinishfabtransaction=$this->invfinishfabtransaction->create(
        [
        'trans_type_id'=>1,
        'trans_date'=>$invfinishfabrcv->receive_date,
        //'inv_finish_fab_rcv_item_id'=>$invfinishfabrcvitem->id,
        //'inv_finish_fab_isu_item_id'=> $request->inv_finish_fab_isu_item_id,
        'inv_finish_fab_item_id'=>$request->inv_finish_fab_item_id,
        'company_id'=>$invfinishfabrcv->company_id,
        'supplier_id'=>$invfinishfabrcv->supplier_id,
        'store_id'=>$request->store_id,
        'store_qty' => $request->qty,
        'store_rate' => 0,
        'store_amount'=> 0
        ]);
      }
      catch(EXCEPTION $e)
      {
        \DB::rollback();
        throw $e;
      }
      \DB::commit();
      if($invfinishfabrcvitem){
        return response()->json(array('success' =>true ,'id'=>$id, 'inv_finish_fab_rcv_id'=>$request->inv_finish_fab_rcv_id,'message'=>'Saved Successfully'),200);
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
      return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
        if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


    public function getItem()
    {
        $invrcv=$this->invrcv->find(request('inv_rcv_id',0));

        /*$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
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


        $prodknitqc=$this->invisu
        ->selectRaw('
            inv_grey_fab_isu_items.id,
            inv_grey_fab_isu_items.qty as rcv_qty,

            inv_grey_fab_rcv_items.inv_grey_fab_item_id,
            inv_grey_fab_rcv_items.store_id,
            prod_knit_dlvs.store_id,
            prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id, 
            prod_knit_qcs.id as prod_knit_qc_id,   
            prod_knit_qcs.gsm_weight,   
            prod_knit_qcs.dia_width,   
            prod_knit_qcs.measurement,   
            prod_knit_qcs.roll_length,   
            prod_knit_qcs.shrink_per,   
            prod_knit_qcs.reject_qty,   
            prod_knit_qcs.qc_pass_qty,   
            prod_knit_qcs.reject_qty_pcs,   
            prod_knit_qcs.qc_pass_qty_pcs,   
            prod_knit_qcs.qc_result,

            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,
            prod_knit_item_rolls.roll_weight,
            prod_knit_item_rolls.width,
            prod_knit_item_rolls.qty_pcs,
            prod_knit_item_rolls.fabric_color,
            prod_knit_item_rolls.gmt_sample,
            prod_knit_items.prod_knit_id,
            prod_knit_items.stitch_length,

            prod_knits.shift_id,
            prod_knits.prod_no,
            prod_knits.supplier_id,
            prod_knits.location_id,
            prod_knits.floor_id,

            suppliers.name as supplier_name,
            locations.name as location_name,
            floors.name as floor_name,
            asset_quantity_costs.custom_no as machine_no,
            asset_technical_features.dia_width as machine_dia,
            asset_technical_features.gauge as machine_gg,
            gmtssamples.name as gmt_sample,
            CASE 
            WHEN  inhouseprods.autoyarn_id IS NULL THEN outhouseprods.autoyarn_id 
            ELSE inhouseprods.autoyarn_id
            END as autoyarn_id,
            CASE 
            WHEN  inhouseprods.gmtspart_id IS NULL THEN outhouseprods.gmtspart_id 
            ELSE inhouseprods.gmtspart_id
            END as gmtspart_id,
            CASE 
            WHEN  inhouseprods.fabric_look_id IS NULL THEN outhouseprods.fabric_look_id 
            ELSE inhouseprods.fabric_look_id
            END as fabric_look_id,

            CASE 
            WHEN  inhouseprods.fabric_shape_id IS NULL THEN outhouseprods.fabric_shape_id 
            ELSE inhouseprods.fabric_shape_id
            END as fabric_shape_id,

            CASE 
            WHEN  inhouseprods.colorrange_name IS NULL THEN outhouseprods.colorrange_name 
            ELSE inhouseprods.colorrange_name
            END as colorrange_name,

            CASE 
            WHEN  inhouseprods.colorrange_id IS NULL THEN outhouseprods.colorrange_id 
            ELSE inhouseprods.colorrange_id
            END as colorrange_id,

            
            CASE 
            WHEN  inhouseprods.sale_order_no IS NULL THEN outhouseprods.sale_order_no 
            ELSE inhouseprods.sale_order_no
            END as sale_order_no,
            CASE 
            WHEN  inhouseprods.style_ref IS NULL THEN outhouseprods.style_ref 
            ELSE inhouseprods.style_ref
            END as style_ref,

            CASE 
            WHEN  inhouseprods.buyer_name IS NULL THEN outhouseprods.buyer_name 
            ELSE inhouseprods.buyer_name
            END as buyer_name,

            CASE 
            WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
            ELSE inhouseprods.customer_name
            END as customer_name

            
        ')
        ->join('inv_grey_fab_isu_items',function($join){
            $join->on('inv_isus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
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
            $join->on('suppliers.id', '=', 'prod_knits.supplier_id');
        })
        ->leftJoin ('locations',function($join){
            $join->on('locations.id', '=', 'prod_knits.location_id');
        })
        ->leftJoin ('floors',function($join){
            $join->on('floors.id', '=', 'prod_knits.floor_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
        })
        ->leftJoin('gmtssamples',function($join){
            $join->on('gmtssamples.id','=','prod_knit_item_rolls.gmt_sample');
        })

        ->leftJoin(\DB::raw("(
            select 
            pl_knit_items.id,
            colorranges.name as colorrange_name,
            colorranges.id as colorrange_id,
            customer.name as customer_name,
            companies.id as company_id,
            CASE 
            WHEN  style_fabrications.autoyarn_id IS NULL THEN so_knit_items.autoyarn_id 
            ELSE style_fabrications.autoyarn_id
            END as autoyarn_id,

            CASE 
            WHEN  style_fabrications.gmtspart_id IS NULL THEN so_knit_items.gmtspart_id 
            ELSE style_fabrications.gmtspart_id
            END as gmtspart_id,

            CASE 
            WHEN  style_fabrications.fabric_look_id IS NULL THEN so_knit_items.fabric_look_id 
            ELSE style_fabrications.fabric_look_id
            END as fabric_look_id,

            CASE 
            WHEN  style_fabrications.fabric_shape_id IS NULL THEN so_knit_items.fabric_shape_id 
            ELSE style_fabrications.fabric_shape_id
            END as fabric_shape_id,
            CASE 
            WHEN sales_orders.sale_order_no IS NULL THEN so_knit_items.gmt_sale_order_no 
            ELSE sales_orders.sale_order_no
            END as sale_order_no,
            CASE 
            WHEN sales_orders.id IS NULL THEN 0
            ELSE sales_orders.id
            END as sale_order_id,
            CASE 
            WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
            ELSE styles.style_ref
            END as style_ref,
            CASE 
            WHEN styles.id IS NULL THEN 0 
            ELSE styles.id
            END as style_id,
            CASE 
            WHEN buyers.name IS NULL THEN outbuyers.name 
            ELSE buyers.name
            END as buyer_name,

            CASE 
            WHEN buyers.id IS NULL THEN outbuyers.id 
            ELSE buyers.id
            END as buyer_id

            from pl_knit_items
            join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
            left join colorranges on colorranges.id=pl_knit_items.colorrange_id
            join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
            left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
            left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
            left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
            and po_knit_service_items.deleted_at is null
            left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
            left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
            left join so_knits on so_knits.id=so_knit_refs.so_knit_id
            left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            left join jobs on jobs.id=sales_orders.job_id
            left join styles on styles.id=jobs.style_id
            left join buyers on buyers.id=styles.buyer_id
            left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
            left join buyers customer on customer.id=so_knits.buyer_id
            left join companies  on companies.id=customer.company_id
        ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
        ->leftJoin(\DB::raw("(
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        colorranges.id as colorrange_id,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        sales_orders.id as sale_order_id,
        styles.style_ref,
        styles.id as style_id,
        buyers.name as buyer_name,
        buyers.id as buyer_id,
        companies.name as customer_name,
        companies.id as company_id   
        from 
        po_knit_service_item_qties
        join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
        join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
        left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
        join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
        join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
        
        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join companies on companies.id=po_knit_services.company_id
        order by po_knit_service_item_qties.id
        ) outhouseprods"),"outhouseprods.id","=","prod_knit_items.po_knit_service_item_qty_id")
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','outhouseprods.company_id');
            $join->Oron('companies.id','=','inhouseprods.company_id');
        })
        ->leftJoin('styles',function($join){
            $join->on('styles.id','=','outhouseprods.style_id');
            $join->Oron('styles.id','=','inhouseprods.style_id');
        })
        ->leftJoin('sales_orders',function($join){
            $join->on('sales_orders.id','=','outhouseprods.sale_order_id');
            $join->Oron('sales_orders.id','=','inhouseprods.sale_order_id');
        })
        
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','outhouseprods.buyer_id');
            $join->Oron('buyers.id','=','inhouseprods.buyer_id');
        })
        ->where([['inv_isus.isu_basis_id','!=',9]])
        ->where([['inv_isus.supplier_id','=',$invrcv->supplier_id]])
       
        ->orderBy('inv_grey_fab_isu_items.id','desc')
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            return $prodknitqc;
        });

        echo json_encode($prodknitqc);*/
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
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


            $yarnDescription=$this->itemaccount
            ->leftJoin('item_account_ratios',function($join){
            $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
            })
            ->leftJoin('compositions',function($join){
            $join->on('compositions.id','=','item_account_ratios.composition_id');
            })
            ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })

            ->where([['itemcategories.identity','=',1]])
            ->orderBy('item_account_ratios.ratio','desc')
            ->get([
            'item_accounts.id',
            'compositions.name as composition_name',
            'item_account_ratios.ratio',
            ]);

            $itemaccountArr=array();
            $yarnCompositionArr=array();
            foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
                $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
            }

            $yarnDropdown=array();
            foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
            }

             $yarn=$this->invisu
            ->selectRaw('
                prod_knits.prod_no,
                prod_knit_items.id as prod_knit_item_id,
                prod_knit_item_rolls.id as prod_knit_item_roll_id,
                prod_knit_item_rolls.custom_no,
                prod_knit_item_yarns.id as prod_knit_item_yarn_id,
                inv_yarn_items.lot,
                inv_yarn_items.brand,
                colors.name as color_name,
                itemcategories.name as itemcategory_name,
                itemclasses.name as itemclass_name,
                item_accounts.id as item_account_id,
                yarncounts.count,
                yarncounts.symbol,
                yarntypes.name as yarn_type,
                uoms.code as uom_code
            ')
            ->join('inv_finish_fab_isu_items',function($join){
            $join->on('inv_finish_fab_isu_items.inv_isu_id', '=', 'inv_isus.id');
            })
            ->join('inv_finish_fab_rcv_items',function($join){
            $join->on('inv_finish_fab_rcv_items.id', '=', 'inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id');
            })

            ->join('inv_finish_fab_rcvs',function($join){
            $join->on('inv_finish_fab_rcvs.id', '=', 'inv_finish_fab_rcv_items.inv_finish_fab_rcv_id');
            })
            ->join('inv_rcvs',function($join){
            $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
            })
            ->join('inv_finish_fab_items',function($join){
            $join->on('inv_finish_fab_items.id', '=', 'inv_finish_fab_isu_items.inv_finish_fab_item_id');
            })

            ->join('prod_finish_dlvs',function($join){
            $join->on('prod_finish_dlvs.id', '=', 'inv_finish_fab_rcvs.prod_finish_dlv_id');
            })
            ->join('prod_finish_dlv_rolls',function($join){
            $join->on('prod_finish_dlvs.id', '=', 'prod_finish_dlv_rolls.prod_finish_dlv_id');
            $join->on('inv_finish_fab_rcv_items.prod_finish_dlv_roll_id', '=', 'prod_finish_dlv_rolls.id');
            })
            ->join('prod_batch_finish_qc_rolls',function($join){
            $join->on('prod_batch_finish_qc_rolls.id', '=', 'prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id');
            })
            ->join('prod_batch_finish_qcs',function($join){
            $join->on('prod_batch_finish_qc_rolls.prod_batch_finish_qc_id', '=', 'prod_batch_finish_qcs.id');
            })
            ->join('prod_batches',function($join){
            $join->on('prod_batch_finish_qcs.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.id', '=', 'prod_batch_finish_qc_rolls.prod_batch_roll_id');
            })
            ->join('so_dyeing_fabric_rcv_rols',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
            })
            ->join('so_dyeing_fabric_rcv_items',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
            })
           
            ->join('inv_grey_fab_isu_items',function($join){
                $join->on('inv_grey_fab_isu_items.id', '=', 'so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id');
            })
            ->join('inv_isus as greyisus',function($join){
                $join->on('greyisus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
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
            ->leftJoin('prod_knit_item_yarns',function($join){
                $join->on('prod_knit_items.id', '=', 'prod_knit_item_yarns.prod_knit_item_id');
            })
            ->leftJoin('inv_yarn_isu_items',function($join){
                $join->on('inv_yarn_isu_items.id', '=', 'prod_knit_item_yarns.inv_yarn_isu_item_id');
            })
            ->leftJoin('inv_yarn_items',function($join){
                $join->on('inv_yarn_items.id', '=', 'inv_yarn_isu_items.inv_yarn_item_id');
            })
            ->leftJoin('item_accounts',function($join){
            $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
            })
            ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
            })
            ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
            })
            ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id');
            })

            ->join ('prod_knits',function($join){
                $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
            })
            ->where([['inv_isus.isu_basis_id','!=',9]])
            ->where([['inv_isus.supplier_id','=',$invrcv->supplier_id]])
            ->orderBy('inv_finish_fab_isu_items.id','desc')
            ->get()
            ->map(function($yarn) use($yarnDropdown){
                $yarn->yarn_count=$yarn->count."/".$yarn->symbol;
                $yarn->composition=$yarn->item_account_id?$yarnDropdown[$yarn->item_account_id]:'';
                return $yarn;
            });
            $yarnDtls=[];
            foreach($yarn as $yar){
                $yarnDtls[$yar->prod_knit_item_id][$yar->prod_knit_item_yarn_id]=$yar->itemclass_name." ".$yar->yarn_count." ".$yar->composition." ".$yar->yarn_type." ".$yar->brand." ".$yar->lot." ".$yar->color_name;

            }
            

            /*$prodknitqc=$this->invisu
            ->selectRaw('
                inv_finish_fab_isu_items.id,
                inv_finish_fab_isu_items.qty as rcv_qty,
                inv_finish_fab_rcv_items.inv_finish_fab_item_id,
                inv_finish_fab_rcv_items.store_id,
                prod_finish_dlv_rolls.id as prod_finish_dlv_roll_id,
                prod_batch_finish_qc_rolls.id as prod_batch_finish_qc_roll_id,
                prod_batch_finish_qc_rolls.qty as roll_weight,
                prod_batch_finish_qc_rolls.reject_qty,
                prod_batch_finish_qc_rolls.gsm_weight as qc_gsm_weight,
                prod_batch_finish_qc_rolls.dia_width as qc_dia_width,
                prod_batch_finish_qc_rolls.grade_id,
                prod_batch_rolls.id as prod_batch_roll_id,
                prod_batch_rolls.qty as batch_qty,
                so_dyeing_fabric_rcv_rols.id as so_dyeing_fabric_rcv_rol_id,
                so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id,
                so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,

                inv_grey_fab_items.autoyarn_id,
                inv_grey_fab_items.gmtspart_id,
                inv_grey_fab_items.fabric_look_id,
                inv_grey_fab_items.fabric_shape_id,
                inv_grey_fab_items.gsm_weight,
                inv_grey_fab_items.dia as dia_width,
                inv_grey_fab_items.measurment as measurement,
                inv_grey_fab_items.roll_length,
                inv_grey_fab_items.stitch_length,
                inv_grey_fab_items.shrink_per,
                inv_grey_fab_items.colorrange_id,
                colorranges.name as colorrange_name,
                inv_grey_fab_items.color_id,
                colors.name as fabric_color,
                inv_grey_fab_items.supplier_id,

                inv_grey_fab_rcv_items.inv_grey_fab_item_id,
                inv_grey_fab_rcv_items.store_id,
                prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id, 
                prod_knits.prod_no,

                prod_knit_item_rolls.id as prod_knit_item_roll_id,
                prod_knit_item_rolls.custom_no,
                prod_knit_items.id as prod_knit_item_id,

                suppliers.name as supplier_name,
                asset_quantity_costs.custom_no as machine_no,
                asset_technical_features.dia_width as machine_dia,
                asset_technical_features.gauge as machine_gg,
                gmtssamples.name as gmt_sample,
                
                sales_orders.sale_order_no,
                styles.style_ref,
                buyers. name as buyer_name,
                style_fabrications.dyeing_type_id,
                po_dyeing_service_item_qties.fabric_color_id,
                dyeingcolors.name as fabric_color_name,
                batch_colors.name as batch_color_name,
                batch_colors.id as batch_color_id,
                stores.name as store_name,
                CASE 
                WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
                ELSE inhouseprods.customer_name
                END as customer_name
            ')
            ->join('inv_finish_fab_isu_items',function($join){
            $join->on('inv_finish_fab_isu_items.inv_isu_id', '=', 'inv_isus.id');
            })
            ->join('inv_finish_fab_rcv_items',function($join){
            $join->on('inv_finish_fab_rcv_items.id', '=', 'inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id');
            })

            ->join('inv_finish_fab_rcvs',function($join){
            $join->on('inv_finish_fab_rcvs.id', '=', 'inv_finish_fab_rcv_items.inv_finish_fab_rcv_id');
            })
            ->join('inv_rcvs',function($join){
            $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
            })
            
            ->join('inv_finish_fab_items',function($join){
            $join->on('inv_finish_fab_items.id', '=', 'inv_finish_fab_isu_items.inv_finish_fab_item_id');
            })

            ->join('prod_finish_dlvs',function($join){
            $join->on('prod_finish_dlvs.id', '=', 'inv_finish_fab_rcvs.prod_finish_dlv_id');
            })
            ->join('prod_finish_dlv_rolls',function($join){
            $join->on('prod_finish_dlvs.id', '=', 'prod_finish_dlv_rolls.prod_finish_dlv_id');
            $join->on('inv_finish_fab_rcv_items.prod_finish_dlv_roll_id', '=', 'prod_finish_dlv_rolls.id');
            })
            ->join('prod_batch_finish_qc_rolls',function($join){
            $join->on('prod_batch_finish_qc_rolls.id', '=', 'prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id');
            })
            ->join('prod_batch_finish_qcs',function($join){
            $join->on('prod_batch_finish_qc_rolls.prod_batch_finish_qc_id', '=', 'prod_batch_finish_qcs.id');
            })
            ->join('prod_batches',function($join){
            $join->on('prod_batch_finish_qcs.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.id', '=', 'prod_batch_finish_qc_rolls.prod_batch_roll_id');
            })
            ->join('so_dyeing_fabric_rcv_rols',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
            })
            ->join('so_dyeing_fabric_rcv_items',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
            })
            ->join('so_dyeing_refs',function($join){
                $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
            })
            ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_pos',function($join){
                $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
            })
            ->join('so_dyeing_po_items',function($join){
                $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
            })
            ->join('po_dyeing_service_item_qties',function($join){
            $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
            })
            ->join('po_dyeing_service_items',function($join){
            $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
            })

            ->join('sales_orders',function($join){
            $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
            })
            ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->join('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
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
            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
            })
            ->join('inv_grey_fab_isu_items',function($join){
                $join->on('inv_grey_fab_isu_items.id', '=', 'so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id');
            })
            ->join('inv_isus as greyisus',function($join){
                $join->on('greyisus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
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
            
            ->leftJoin('gmtssamples',function($join){
                $join->on('gmtssamples.id','=','prod_knit_item_rolls.gmt_sample');
            })
            ->leftJoin('colors as dyeingcolors',function($join){
                $join->on('dyeingcolors.id','=','po_dyeing_service_item_qties.fabric_color_id');
            })
            ->leftJoin('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
            })
            ->leftJoin('stores',function($join){
            $join->on('stores.id','=','inv_finish_fab_rcv_items.store_id');
            })

            ->leftJoin(\DB::raw("(
                select 
                pl_knit_items.id,
                colorranges.name as colorrange_name,
                colorranges.id as colorrange_id,
                customer.name as customer_name,
                companies.id as company_id,
                CASE 
                WHEN  style_fabrications.autoyarn_id IS NULL THEN so_knit_items.autoyarn_id 
                ELSE style_fabrications.autoyarn_id
                END as autoyarn_id,

                CASE 
                WHEN  style_fabrications.gmtspart_id IS NULL THEN so_knit_items.gmtspart_id 
                ELSE style_fabrications.gmtspart_id
                END as gmtspart_id,

                CASE 
                WHEN  style_fabrications.fabric_look_id IS NULL THEN so_knit_items.fabric_look_id 
                ELSE style_fabrications.fabric_look_id
                END as fabric_look_id,

                CASE 
                WHEN  style_fabrications.fabric_shape_id IS NULL THEN so_knit_items.fabric_shape_id 
                ELSE style_fabrications.fabric_shape_id
                END as fabric_shape_id,
                CASE 
                WHEN sales_orders.sale_order_no IS NULL THEN so_knit_items.gmt_sale_order_no 
                ELSE sales_orders.sale_order_no
                END as sale_order_no,
                CASE 
                WHEN sales_orders.id IS NULL THEN 0
                ELSE sales_orders.id
                END as sale_order_id,
                CASE 
                WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
                ELSE styles.style_ref
                END as style_ref,
                CASE 
                WHEN styles.id IS NULL THEN 0 
                ELSE styles.id
                END as style_id,
                CASE 
                WHEN buyers.name IS NULL THEN outbuyers.name 
                ELSE buyers.name
                END as buyer_name,

                CASE 
                WHEN buyers.id IS NULL THEN outbuyers.id 
                ELSE buyers.id
                END as buyer_id

                from pl_knit_items
                join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
                left join colorranges on colorranges.id=pl_knit_items.colorrange_id
                join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
                left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
                left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
                left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
                and po_knit_service_items.deleted_at is null
                left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
                left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
                left join so_knits on so_knits.id=so_knit_refs.so_knit_id
                left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                left join jobs on jobs.id=sales_orders.job_id
                left join styles on styles.id=jobs.style_id
                left join buyers on buyers.id=styles.buyer_id
                left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
                left join buyers customer on customer.id=so_knits.buyer_id
                left join companies  on companies.id=customer.company_id
            ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
            ->leftJoin(\DB::raw("(
                select 
                po_knit_service_item_qties.id,
                colorranges.name as colorrange_name,
                colorranges.id as colorrange_id,
                style_fabrications.autoyarn_id,
                style_fabrications.gmtspart_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                sales_orders.sale_order_no,
                sales_orders.id as sale_order_id,
                styles.style_ref,
                styles.id as style_id,
                buyers.name as buyer_name,
                buyers.id as buyer_id,
                companies.name as customer_name,
                companies.id as company_id   
                from 
                po_knit_service_item_qties
                join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
                join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
                left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
                join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
                join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                
                left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                left join jobs on jobs.id=sales_orders.job_id
                left join styles on styles.id=jobs.style_id
                left join buyers on buyers.id=styles.buyer_id
                left join companies on companies.id=po_knit_services.company_id
                order by po_knit_service_item_qties.id
            ) outhouseprods"),"outhouseprods.id","=","prod_knit_items.po_knit_service_item_qty_id")
           
            ->leftJoin('companies',function($join){
                $join->on('companies.id','=','outhouseprods.company_id');
                $join->Oron('companies.id','=','inhouseprods.company_id');
            })
            ->where([['inv_isus.isu_basis_id','!=',9]])
            ->where([['inv_isus.supplier_id','=',$invrcv->supplier_id]])
           
            ->orderBy('inv_finish_fab_isu_items.id','desc')
            ->get()
            ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$yarnDtls,$rollqcresult){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->dyetype=$prodknitqc->dyeing_type_id?$dyetype[$prodknitqc->dyeing_type_id]:'';
                $prodknitqc->yarndtl=$prodknitqc->prod_knit_item_id?implode(',',$yarnDtls[$prodknitqc->prod_knit_item_id]):'';
                $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';

                return $prodknitqc;
            });
            echo json_encode($prodknitqc);*/
            $prodknitqc = collect(
            \DB::select("
            select 
            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,

            prod_knit_item_rolls.roll_weight,
            prod_knit_item_rolls.width,
            prod_knit_item_rolls.qty_pcs,
            prod_knit_items.id as prod_knit_item_id,
            prod_knit_items.prod_knit_id,
            prod_knit_items.stitch_length,
            prod_knits.prod_no,
            prod_knit_qcs.measurement,   
            prod_knit_qcs.roll_length,   
            prod_knit_qcs.shrink_per, 
            inv_finish_fab_items.autoyarn_id,
            inv_finish_fab_items.gmtspart_id,
            inv_finish_fab_items.fabric_look_id,
            inv_finish_fab_items.fabric_shape_id,
            prod_batch_finish_qc_rolls.gsm_weight,   
            prod_batch_finish_qc_rolls.dia_width,
            prod_batch_finish_qc_rolls.grade_id,

            styles.style_ref,
            buyers.name as buyer_name,
            sales_orders.sale_order_no,
            CASE 
            WHEN  dyeingbatch.batch_color_name IS NULL THEN aopbatch.batch_color_name 
            ELSE dyeingbatch.batch_color_name
            END as batch_color_name,

            CASE 
            WHEN  dyeingbatch.customer_name IS NULL THEN aopbatch.customer_name 
            ELSE dyeingbatch.customer_name
            END as customer_name,

            CASE 
            WHEN  dyeingbatch.dyeing_batch_no IS NULL THEN aopbatch.dyeing_batch_no 
            ELSE dyeingbatch.dyeing_batch_no
            END as dyeing_batch_no,
            aopbatch.aop_batch_no, 

            inv_finish_fab_rcv_items.id as inv_finish_fab_rcv_item_id, 
            --inv_finish_fab_rcv_items.store_qty as rcv_qty,
            inv_finish_fab_rcv_items.inv_finish_fab_item_id,
            inv_finish_fab_rcv_items.store_id,
            prod_finish_dlv_rolls.id as prod_finish_dlv_roll_id,
            inv_finish_fab_isu_items.id,
            inv_finish_fab_isu_items.qty as rcv_qty,
            invfinishfabrcvitems.id as inv_finish_fab_rcv_item_id

            from 
            inv_isus
            inner join inv_finish_fab_isu_items on  inv_finish_fab_isu_items.inv_isu_id=inv_isus.id

            inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.id=inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id

            
            inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.id=inv_finish_fab_rcv_items.inv_finish_fab_rcv_id
            inner join inv_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id

            
            inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
            inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
            inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
            and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
            inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
            inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
            left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
            left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 

            left join (
            select 
            prod_batches.id,
            prod_batches.batch_no as dyeing_batch_no,
            prod_batch_rolls.id as prod_batch_roll_id,
            batch_colors.name as batch_color_name,
            customers.name as customer_name,
            po_dyeing_service_item_qties.sales_order_id,
            budget_fabric_prods.budget_fabric_id,
            so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id

            from 
            prod_batches
            inner join prod_batch_rolls on prod_batch_rolls.prod_batch_id = prod_batches.id
            inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
            inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
            inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
            inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
            inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
            inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
            inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
            inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
            left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
            left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
            inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
            inner join buyers  customers on customers.id = so_dyeings.buyer_id 
            ) dyeingbatch on dyeingbatch.prod_batch_roll_id=prod_batch_finish_qc_rolls.prod_batch_roll_id

            left join (
            select 
            prod_aop_batches.id,
            prod_aop_batches.batch_no as aop_batch_no,
            prod_aop_batch_rolls.id as prod_aop_batch_roll_id,
            batch_colors.name as batch_color_name,
            customers.name as customer_name,
            po_aop_service_item_qties.sales_order_id,
            budget_fabric_prods.budget_fabric_id,
            so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
            prod_batches.batch_no as dyeing_batch_no



            from 
            prod_aop_batches
            inner join prod_aop_batch_rolls on prod_aop_batch_rolls.prod_aop_batch_id = prod_aop_batches.id
            inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
            inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
            inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
            inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
            inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id
            inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id

            inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
            inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
            inner join colors batch_colors on batch_colors.id = prod_batches.batch_color_id
            inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id

            inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
            inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
            inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
            inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
            inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
            inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
            inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
            inner join buyers  customers on customers.id = so_aops.buyer_id 

            ) aopbatch on aopbatch.prod_aop_batch_roll_id=prod_batch_finish_qc_rolls.prod_aop_batch_roll_id

            inner join sales_orders on  (sales_orders.id = dyeingbatch.sales_order_id or sales_orders.id = aopbatch.sales_order_id) 
            inner join jobs on jobs.id = sales_orders.job_id
            inner join styles on styles.id = jobs.style_id
            inner join buyers on buyers.id = styles.buyer_id

            inner join budget_fabrics on (budget_fabrics.id = dyeingbatch.budget_fabric_id or budget_fabrics.id = aopbatch.budget_fabric_id) 
            inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
            inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
            inner join constructions on constructions.id = autoyarns.construction_id

            inner join inv_grey_fab_isu_items on (inv_grey_fab_isu_items.id = dyeingbatch.inv_grey_fab_isu_item_id or inv_grey_fab_isu_items.id = aopbatch.inv_grey_fab_isu_item_id)
            inner join inv_isus grey_fab_isus on grey_fab_isus.id = inv_grey_fab_isu_items.inv_isu_id
            inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
            inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id

            inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
            inner join inv_rcvs grey_fab_rcvs  on grey_fab_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
            inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
            inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
            and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id

            inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
            inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
            inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
            inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 

            inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
            left join colors on  colors.id=prod_knit_item_rolls.fabric_color
            left join inv_finish_fab_rcv_items invfinishfabrcvitems on invfinishfabrcvitems.inv_finish_fab_isu_item_id=inv_finish_fab_isu_items.id
            where inv_isus.supplier_id = ? and inv_isus.isu_basis_id !=? and inv_isus.deleted_at is null
            order by inv_finish_fab_isu_items.id desc
            ",[$invrcv->supplier_id,9])
            )
            ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';
                return $prodknitqc;
            })
            ->filter(function($prodknitqc){
                if(!$prodknitqc->inv_finish_fab_rcv_item_id){
                return $prodknitqc;
                }
            })
            ->values()
            ;
           
            echo json_encode($prodknitqc);
    }
}