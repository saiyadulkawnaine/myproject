<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRollRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchTrimRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRolRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;


use App\Library\Template;
use App\Http\Requests\Production\Dyeing\ProdBatchRollRequest;

class ProdBatchRollController extends Controller {

    private $prodbatch;
    private $prodbatchroll;
    private $prodbatchtrim;
    private $company;
    private $color;
    private $colorrange;
    private $assetquantitycost;
    private $sodyeingfabricrcvrol;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $itemaccount;

    public function __construct(
        ProdBatchRepository $prodbatch,  
        ProdBatchRollRepository $prodbatchroll,
        ProdBatchTrimRepository $prodbatchtrim ,  
        CompanyRepository $company, 
        ColorRepository $color,
        ColorrangeRepository $colorrange,
        AssetQuantityCostRepository $assetquantitycost,
        SoDyeingFabricRcvRolRepository $sodyeingfabricrcvrol,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        UomRepository $uom,
        ItemAccountRepository $itemaccount

    ) {
        $this->prodbatch = $prodbatch;
        $this->prodbatchroll = $prodbatchroll;
        $this->prodbatchtrim = $prodbatchtrim;
        $this->company = $company;
        $this->color = $color;
        $this->colorrange = $colorrange;
        $this->assetquantitycost = $assetquantitycost;
        $this->sodyeingfabricrcvrol = $sodyeingfabricrcvrol;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');

        /*$this->middleware('permission:view.prodbatchrolls',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodbatchrolls', ['only' => ['store']]);
        $this->middleware('permission:edit.prodbatchrolls',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodbatchrolls', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $prodbatch=$this->prodbatch->find(request('prod_batch_id',0));
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'--','');


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
        if($prodbatch->batch_for==1){


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

         $yarn=$this->prodbatch
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
        ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
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
        ->where([['prod_batches.id','=',request('prod_batch_id',0)]])
        ->orderBy('inv_grey_fab_isu_items.id','desc')
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
        

        $prodknitqc=$this->prodbatch
        ->selectRaw('
        	prod_batches.batch_color_id,
            batch_colors.name as batch_color_name,
            prod_batch_rolls.id,
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
            batch.batch_qty as tot_batch_qty,
            sales_orders.sale_order_no,
            styles.style_ref,
            buyers. name as buyer_name,
            style_fabrications.dyeing_type_id,
            po_dyeing_service_item_qties.fabric_color_id,
            dyeingcolors.name as dyeing_color,
            inv_isus.issue_no,
            
            /*CASE 
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
            END as buyer_name,*/

            CASE 
            WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
            ELSE inhouseprods.customer_name
            END as customer_name
        ')
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
        ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
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
        
        ->leftJoin('gmtssamples',function($join){
            $join->on('gmtssamples.id','=','prod_knit_item_rolls.gmt_sample');
        })
        ->leftJoin('colors as dyeingcolors',function($join){
            $join->on('dyeingcolors.id','=','po_dyeing_service_item_qties.fabric_color_id');
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
        

        ->leftJoin(\DB::raw("(
            select 
            prod_batch_rolls.so_dyeing_fabric_rcv_rol_id,
            sum(prod_batch_rolls.qty) as batch_qty
            from 
            prod_batch_rolls
            join prod_batches on prod_batches.id=prod_batch_rolls.prod_batch_id
            where prod_batch_rolls.deleted_at is null
            and prod_batches.is_redyeing=0
            and prod_batches.root_batch_id is null
            group by 
            prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
        ) batch"),"batch.so_dyeing_fabric_rcv_rol_id","=","so_dyeing_fabric_rcv_rols.id")
        
        ->where([['prod_batches.id','=',request('prod_batch_id',0)]])

        ->orderBy('prod_batch_rolls.id','desc')
        ->get()
        ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$yarnDtls){
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->dyetype=$prodknitqc->dyeing_type_id?$dyetype[$prodknitqc->dyeing_type_id]:'';
            $prodknitqc->yarndtl=$prodknitqc->prod_knit_item_id?implode(',',$yarnDtls[$prodknitqc->prod_knit_item_id]):'';
            $prodknitqc->bal_qty=$prodknitqc->rcv_qty-($prodknitqc->tot_batch_qty-$prodknitqc->batch_qty);
            return $prodknitqc;
        });
        echo json_encode($prodknitqc);
        }
        if($prodbatch->batch_for==2){

            $prodknitqc=$this->prodbatch
            ->selectRaw('
            prod_batches.batch_color_id,
            batch_colors.name as batch_color_name,
            prod_batch_rolls.id,
            prod_batch_rolls.qty as batch_qty,
            so_dyeing_fabric_rcv_rols.id as so_dyeing_fabric_rcv_rol_id,
            so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id,
            so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
            so_dyeing_fabric_rcv_rols.qty as rcv_qty,

            so_dyeing_items.autoyarn_id,
            so_dyeing_items.gmtspart_id,
            so_dyeing_items.fabric_look_id,
            so_dyeing_items.fabric_shape_id,
            so_dyeing_items.gsm_weight,
            so_dyeing_items.dia as dia_width,
            so_dyeing_items.measurment as measurement,
            so_dyeing_items.colorrange_id,
            colorranges.name as colorrange_name,
            so_dyeing_items.fabric_color_id,
            so_dyeing_items.dyeing_type_id,
            batch.batch_qty as tot_batch_qty,
            so_dyeing_items.gmt_sale_order_no as sale_order_no,
            so_dyeing_items.gmt_style_ref as style_ref,
            buyers.name as buyer_name,
            dyeingcolors.name as dyeing_color,
            so_dyeing_fabric_rcv_items.yarn_des as yarndtl
            ')
            ->join('colors as batch_colors',function($join){
                $join->on('batch_colors.id','=','prod_batches.batch_color_id');
            })
            ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
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
            ->join('so_dyeing_items',function($join){
            $join->on('so_dyeing_refs.id', '=', 'so_dyeing_items.so_dyeing_ref_id');
            })
            ->join('so_dyeings',function($join){
            $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })

            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','so_dyeing_items.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })

            ->leftJoin ('colorranges',function($join){
            $join->on('colorranges.id', '=', 'so_dyeing_items.colorrange_id');
            }) 
            ->leftJoin ('buyers',function($join){
            $join->on('buyers.id', '=', 'so_dyeing_items.gmt_buyer');
            })
            ->leftJoin('colors as dyeingcolors',function($join){
            $join->on('dyeingcolors.id','=','so_dyeing_items.fabric_color_id');
            })
            ->leftJoin(\DB::raw("(
            select 
            prod_batch_rolls.so_dyeing_fabric_rcv_rol_id,
            sum(prod_batch_rolls.qty) as batch_qty
            from 
            prod_batch_rolls
            join prod_batches on prod_batches.id=prod_batch_rolls.prod_batch_id
            where prod_batch_rolls.deleted_at is null
            and prod_batches.is_redyeing=0
            and prod_batches.root_batch_id is null
            group by 
            prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
            ) batch"),"batch.so_dyeing_fabric_rcv_rol_id","=","so_dyeing_fabric_rcv_rols.id")

            ->where([['prod_batches.id','=',request('prod_batch_id',0)]])
            ->orderBy('prod_batch_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype){
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->dyetype=$prodknitqc->dyeing_type_id?$dyetype[$prodknitqc->dyeing_type_id]:'';
            $prodknitqc->bal_qty=$prodknitqc->rcv_qty-($prodknitqc->tot_batch_qty-$prodknitqc->batch_qty);
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
        
        $prodbatch=$this->prodbatch->find(request('prod_batch_id',0));

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

        if($prodbatch->batch_for==1){
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


            $yarn=$this->sodyeingfabricrcvrol
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
            ->where([['prod_knit_item_rolls.fabric_color','=',$prodbatch->fabric_color_id]])
            ->orderBy('inv_grey_fab_isu_items.id','desc')
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


            $prodknitqc=$this->sodyeingfabricrcvrol
            ->selectRaw('
            so_dyeing_fabric_rcv_rols.id,
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
            batch.batch_qty,
            sales_orders.sale_order_no,
            styles.style_ref,
            buyers. name as buyer_name,
            style_fabrications.dyeing_type_id,
            po_dyeing_service_item_qties.fabric_color_id,
            dyeingcolors.name as dyeing_color,
            inv_isus.issue_no,




            CASE 
            WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
            ELSE inhouseprods.customer_name
            END as customer_name,
            prod_batch_rolls.id as prod_batch_roll_id
            ')
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

            ->leftJoin('gmtssamples',function($join){
            $join->on('gmtssamples.id','=','prod_knit_item_rolls.gmt_sample');
            })
            ->leftJoin('colors as dyeingcolors',function($join){
            $join->on('dyeingcolors.id','=','po_dyeing_service_item_qties.fabric_color_id');
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


            ->leftJoin(\DB::raw("(
            select 
            prod_batch_rolls.so_dyeing_fabric_rcv_rol_id,
            sum(prod_batch_rolls.qty) as batch_qty
            from 
            prod_batch_rolls
            join prod_batches on prod_batches.id=prod_batch_rolls.prod_batch_id
            where prod_batch_rolls.deleted_at is null
            and prod_batches.is_redyeing=0
            and prod_batches.root_batch_id is null
            group by 
            prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
            ) batch"),"batch.so_dyeing_fabric_rcv_rol_id","=","so_dyeing_fabric_rcv_rols.id")
            ->leftJoin('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.so_dyeing_fabric_rcv_rol_id','=','so_dyeing_fabric_rcv_rols.id');
            $join->where([['prod_batch_rolls.prod_batch_id','=',request('prod_batch_id',0)]]);

            })
            ->where([['po_dyeing_service_item_qties.fabric_color_id','=',$prodbatch->fabric_color_id]])
            ->where([['prod_knit_item_rolls.fabric_color','=',$prodbatch->fabric_color_id]])
            ->when(request('issue_no'), function ($q) {
            return $q->where('inv_isus.issue_no', '=',request('issue_no', 0));
            })
            ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'like','%'.request('style_ref', 0).'%');
            })
            ->when(request('buyer_id'), function ($q) {
            return $q->where('buyers.id', '=',request('buyer_id', 0));
            })
            ->orderBy('inv_grey_fab_isu_items.id','desc')
            ->get()
            ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$yarnDtls){
            //$prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->dyetype=$prodknitqc->dyeing_type_id?$dyetype[$prodknitqc->dyeing_type_id]:'';
            $prodknitqc->yarndtl=$prodknitqc->prod_knit_item_id?implode(',',$yarnDtls[$prodknitqc->prod_knit_item_id]):'';
            $prodknitqc->bal_qty=number_format($prodknitqc->rcv_qty-$prodknitqc->batch_qty,2,'.','');
            $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,2,'.','');
            $prodknitqc->batch_qty=number_format($prodknitqc->batch_qty,2,'.','');
            return $prodknitqc;
            })
            ->filter(function($prodknitqc){
            if($prodknitqc->bal_qty>=1 && !$prodknitqc->prod_batch_roll_id){
            return $prodknitqc;
            }

            })
            ->values();
            echo json_encode($prodknitqc);
       }
       if($prodbatch->batch_for==2){
            $prodknitqc=$this->sodyeingfabricrcvrol
            ->selectRaw('
            so_dyeing_fabric_rcv_rols.id,
            so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id,
            so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
            so_dyeing_fabric_rcv_rols.qty as rcv_qty,

            so_dyeing_items.autoyarn_id,
            so_dyeing_items.gmtspart_id,
            so_dyeing_items.fabric_look_id,
            so_dyeing_items.fabric_shape_id,
            so_dyeing_items.gsm_weight,
            so_dyeing_items.dia as dia_width,
            so_dyeing_items.measurment as measurement,
            so_dyeing_items.colorrange_id,
            colorranges.name as colorrange_name,
            so_dyeing_items.fabric_color_id,
            so_dyeing_items.dyeing_type_id,
            batch.batch_qty,
            so_dyeing_items.gmt_sale_order_no as sale_order_no,
            so_dyeing_items.gmt_style_ref as style_ref,
            buyers.name as buyer_name,
            dyeingcolors.name as dyeing_color,
            so_dyeing_fabric_rcv_items.yarn_des as yarndtl,
            prod_batch_rolls.id as prod_batch_roll_id
            ')
            ->join('so_dyeing_fabric_rcv_items',function($join){
            $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
            })
            ->join('so_dyeing_refs',function($join){
            $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
            })
            ->join('so_dyeing_items',function($join){
            $join->on('so_dyeing_refs.id', '=', 'so_dyeing_items.so_dyeing_ref_id');
            })
            ->join('so_dyeings',function($join){
            $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })

            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','so_dyeing_items.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })

            ->leftJoin ('colorranges',function($join){
            $join->on('colorranges.id', '=', 'so_dyeing_items.colorrange_id');
            }) 
            ->leftJoin ('buyers',function($join){
            $join->on('buyers.id', '=', 'so_dyeing_items.gmt_buyer');
            })
            ->leftJoin('colors as dyeingcolors',function($join){
            $join->on('dyeingcolors.id','=','so_dyeing_items.fabric_color_id');
            })
            ->leftJoin(\DB::raw("(
            select 
            prod_batch_rolls.so_dyeing_fabric_rcv_rol_id,
            sum(prod_batch_rolls.qty) as batch_qty
            from 
            prod_batch_rolls
            join prod_batches on prod_batches.id=prod_batch_rolls.prod_batch_id
            where prod_batch_rolls.deleted_at is null
            and prod_batches.is_redyeing=0
            and prod_batches.root_batch_id is null
            group by 
            prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
            ) batch"),"batch.so_dyeing_fabric_rcv_rol_id","=","so_dyeing_fabric_rcv_rols.id")

            ->leftJoin('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.so_dyeing_fabric_rcv_rol_id','=','so_dyeing_fabric_rcv_rols.id');
            $join->where([['prod_batch_rolls.prod_batch_id','=',request('prod_batch_id',0)]]);
            })
            ->where([['so_dyeing_items.fabric_color_id','=',$prodbatch->fabric_color_id]])
            ->when(request('style_ref'), function ($q) {
            return $q->where('so_dyeing_items.gmt_style_ref', 'like','%'.request('style_ref', 0).'%');
            })
            ->when(request('buyer_id'), function ($q) {
            return $q->where('buyers.id', '=',request('buyer_id', 0));
            })

            ->orderBy('so_dyeing_fabric_rcv_rols.id','desc')
            ->get()
            ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype){
            //$prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->dyetype=$prodknitqc->dyeing_type_id?$dyetype[$prodknitqc->dyeing_type_id]:'';
            $prodknitqc->bal_qty=number_format($prodknitqc->rcv_qty-$prodknitqc->batch_qty,2,'.','');
            $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,2,'.','');
            $prodknitqc->batch_qty=number_format($prodknitqc->batch_qty,2,'.','');

            return $prodknitqc;
            })
            ->filter(function($prodknitqc){
            if($prodknitqc->bal_qty>=1 && !$prodknitqc->prod_batch_roll_id){
            return $prodknitqc;
            }
            })
            ->values();
            echo json_encode($prodknitqc);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdBatchRollRequest $request) {

        $batch=$this->prodbatch->find($request->prod_batch_id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $request->prod_batch_id,'message' => 'This Batch is Approved. Roll Adding Not Allowed'),200);
        }

        \DB::beginTransaction();
        try
        {
            foreach($request->so_dyeing_fabric_rcv_rol_id as $index=>$so_dyeing_fabric_rcv_rol_id)
            {
                $prodbatchroll = $this->prodbatchroll->create([
                    'prod_batch_id'=>$request->prod_batch_id,
                    'so_dyeing_fabric_rcv_rol_id'=>$so_dyeing_fabric_rcv_rol_id,
                    'qty'=>$request->qty[$index],
                ]);
            }

            $fabric_wgt=$this->prodbatchroll->where([['prod_batch_id','=',$request->prod_batch_id]])->sum('qty');
            $trim_wgt=$this->prodbatchtrim->where([['prod_batch_id','=',$request->prod_batch_id]])->sum('wgt_qty');
            $batch_wgt=$trim_wgt+$fabric_wgt;

            $prodbatch= $this->prodbatch->update($request->prod_batch_id,[
                'fabric_wgt'=>$fabric_wgt,
                'batch_wgt'=>$batch_wgt,
            ]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();
       
        if($prodbatchroll){
            return response()->json(array('success' => true,'id' =>  $prodbatchroll->id,'prod_batch_id'=>$request->prod_batch_id,'message' => 'Save Successfully'),200);
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
        $prodbatchroll=$this->prodbatchroll->find($id);
        $prodbatch=$this->prodbatch->find($prodbatchroll->prod_batch_id);
        if($prodbatch->batch_for==1){
            $data = $this->prodbatchroll
            ->join('prod_batches',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
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
            ->leftJoin(\DB::raw("(
                select 
                prod_batch_rolls.so_dyeing_fabric_rcv_rol_id,
                sum(prod_batch_rolls.qty) as batch_qty
                from 
                prod_batch_rolls
                join prod_batches on prod_batches.id=prod_batch_rolls.prod_batch_id
                where prod_batch_rolls.deleted_at is null
                and prod_batches.is_redyeing=0
                and prod_batches.root_batch_id is null
                group by 
                prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
                ) batch"),"batch.so_dyeing_fabric_rcv_rol_id","=","so_dyeing_fabric_rcv_rols.id"
            )
            ->where([['prod_batch_rolls.id','=',$id]])
            ->get([
            'prod_batch_rolls.*',
            'batch.batch_qty as tot_batch_qty',
            'inv_grey_fab_isu_items.qty as rcv_qty'
            ])
            ->map(function($data){
                $data->bal_qty=$data->rcv_qty-($data->tot_batch_qty-$data->qty);
                return $data;
            })
            ->first();
        }
        if($prodbatch->batch_for==2){
            $data = $this->prodbatchroll
            ->join('prod_batches',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('so_dyeing_fabric_rcv_rols',function($join){
            $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
            })
            ->join('so_dyeing_fabric_rcv_items',function($join){
            $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
            })
            ->leftJoin(\DB::raw("(
                select 
                prod_batch_rolls.so_dyeing_fabric_rcv_rol_id,
                sum(prod_batch_rolls.qty) as batch_qty
                from 
                prod_batch_rolls
                join prod_batches on prod_batches.id=prod_batch_rolls.prod_batch_id
                where prod_batch_rolls.deleted_at is null
                and prod_batches.is_redyeing=0
                and prod_batches.root_batch_id is null
                group by 
                prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
                ) batch"),"batch.so_dyeing_fabric_rcv_rol_id","=","so_dyeing_fabric_rcv_rols.id"
            )
            ->where([['prod_batch_rolls.id','=',$id]])
            ->get([
            'prod_batch_rolls.*',
            'batch.batch_qty as tot_batch_qty',
            'so_dyeing_fabric_rcv_rols.qty as rcv_qty'
            ])
            ->map(function($data){
                $data->bal_qty=$data->rcv_qty-($data->tot_batch_qty-$data->qty);
                return $data;
            })
            ->first();
        }

        
        $row ['fromData'] = $data;
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
    public function update(ProdBatchRollRequest $request, $id) {
        $prodbatchroll=$this->prodbatchroll->find($id);
        $prodbatch=$this->prodbatch->find($prodbatchroll->prod_batch_id);
        if($prodbatch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Update Not Allowed'),200);
        }

        if($prodbatch->batch_for==1){
            $data = $this->prodbatchroll
            ->join('prod_batches',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
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
            ->leftJoin(\DB::raw("(
                select 
                prod_batch_rolls.so_dyeing_fabric_rcv_rol_id,
                sum(prod_batch_rolls.qty) as batch_qty
                from 
                prod_batch_rolls
                join prod_batches on prod_batches.id=prod_batch_rolls.prod_batch_id
                where prod_batch_rolls.deleted_at is null
                and prod_batches.is_redyeing=0
                and prod_batches.root_batch_id is null
                group by 
                prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
                ) batch"),"batch.so_dyeing_fabric_rcv_rol_id","=","so_dyeing_fabric_rcv_rols.id"
            )
            ->where([['prod_batch_rolls.id','=',$id]])
            ->get([
            'prod_batch_rolls.*',
            'batch.batch_qty as tot_batch_qty',
            'inv_grey_fab_isu_items.qty as rcv_qty'
            ])
            ->map(function($data){
                $data->bal_qty=$data->rcv_qty-($data->tot_batch_qty-$data->qty);
                return $data;
            })
            ->first();
        }
        if($prodbatch->batch_for==2){
            $data = $this->prodbatchroll
            ->join('prod_batches',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('so_dyeing_fabric_rcv_rols',function($join){
            $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
            })
            ->join('so_dyeing_fabric_rcv_items',function($join){
            $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
            })
            ->leftJoin(\DB::raw("(
                select 
                prod_batch_rolls.so_dyeing_fabric_rcv_rol_id,
                sum(prod_batch_rolls.qty) as batch_qty
                from 
                prod_batch_rolls
                join prod_batches on prod_batches.id=prod_batch_rolls.prod_batch_id
                where prod_batch_rolls.deleted_at is null
                and prod_batches.is_redyeing=0
                and prod_batches.root_batch_id is null
                group by 
                prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
                ) batch"),"batch.so_dyeing_fabric_rcv_rol_id","=","so_dyeing_fabric_rcv_rols.id"
            )
            ->where([['prod_batch_rolls.id','=',$id]])
            ->get([
            'prod_batch_rolls.*',
            'batch.batch_qty as tot_batch_qty',
            'so_dyeing_fabric_rcv_items.qty as rcv_qty'
            ])
            ->map(function($data){
                $data->bal_qty=$data->rcv_qty-($data->tot_batch_qty-$data->qty);
                return $data;
            })
            ->first();
        }
        if($request->qty>$data->bal_qty){
            return response()->json(array('success' => false,'id' => $id,'prod_batch_id'=>$request->prod_batch_id,'message' => 'Batch Qty higher than balance Qty '),200);
        }

        \DB::beginTransaction();
        try
        {
            $prodbatch = $this->prodbatchroll->update($id,[
                'qty'=>$request->qty,
            ]);

            /*$tot=$this->prodbatchroll->where([['prod_batch_id','=',$request->prod_batch_id]])->sum('qty');

            $prodbatch= $this->prodbatch->update($request->prod_batch_id,[
                'fabric_wgt'=>$tot,
            ]);*/
            $fabric_wgt=$this->prodbatchroll->where([['prod_batch_id','=',$request->prod_batch_id]])->sum('qty');
            $trim_wgt=$this->prodbatchtrim->where([['prod_batch_id','=',$request->prod_batch_id]])->sum('wgt_qty');
            $batch_wgt=$trim_wgt+$fabric_wgt;

            $prodbatch= $this->prodbatch->update($request->prod_batch_id,[
                'fabric_wgt'=>$fabric_wgt,
                'batch_wgt'=>$batch_wgt,
            ]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();

        if($prodbatch){
            return response()->json(array('success' => true,'id' => $id,'prod_batch_id'=>$request->prod_batch_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $prodbatchroll=$this->prodbatchroll->find($id);
        $prodbatch=$this->prodbatch->find($prodbatchroll->prod_batch_id);
        if($prodbatch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Delete Not Allowed'),200);
        }

        if($this->prodbatchroll->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
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
        ->when(request('brand'), function ($q) {
        return $q->where('asset_acquisitions.brand', 'like','%'.request('brand', 0).'%');
        })
        ->when(request('machine_no'), function ($q) {
        return $q->where('asset_quantity_costs.custom_no', '=',request('machine_no', 0));
        })
        ->where([['asset_acquisitions.production_area_id','=',20]])
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
}