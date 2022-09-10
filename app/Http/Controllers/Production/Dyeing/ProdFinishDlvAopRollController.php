<?php

namespace App\Http\Controllers\Production\Dyeing;

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
use App\Http\Requests\Production\Dyeing\ProdFinishDlvRollRequest;

class ProdFinishDlvAopRollController extends Controller {
    
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
       /*$this->middleware('permission:view.prodfinishdlvaoprolls',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodfinishdlvaoprolls', ['only' => ['store']]);
        $this->middleware('permission:edit.prodfinishdlvaoprolls',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodfinishdlvaoprolls', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
            //$prodbatchfinishqc=$this->prodbatchfinishqc->find(request('prod_batch_finish_qc_id',0));
            //$prodbatch=$this->prodbatch->find($prodbatchfinishqc->prod_batch_id);
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

             $yarn=$this->prodfinishdlv
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
            ->join('prod_finish_dlv_rolls',function($join){
            $join->on('prod_finish_dlv_rolls.prod_finish_dlv_id', '=', 'prod_finish_dlvs.id');
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
            ->where([['prod_finish_dlvs.id','=',request('prod_finish_dlv_id',0)]])
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
            

            $prodknitqc=$this->prodfinishdlv
            ->selectRaw('
                prod_finish_dlv_rolls.id,
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
                dyeingcompanies.name as dyeing_company,
                dyeingcustomers.name as dyeing_customer,
                prodcompanies.name as prod_company_name,
                bnfcompanies.name as bnf_company_name,
                so_dyeings.sales_order_no as dyeing_sales_order_no,
                
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
            ->join('prod_finish_dlv_rolls',function($join){
                $join->on('prod_finish_dlv_rolls.prod_finish_dlv_id', '=', 'prod_finish_dlvs.id');
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
            ->leftJoin('colors as batch_colors',function($join){
                $join->on('batch_colors.id','=','prod_batches.batch_color_id');
            })

            ->leftJoin(\DB::raw("(
                select 
                pl_knit_items.id,
                colorranges.name as colorrange_name,
                colorranges.id as colorrange_id,
                customer.name as customer_name,
                companies.id as company_id

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
            ->leftJoin('companies as dyeingcompanies',function($join){
                $join->on('dyeingcompanies.id','=','so_dyeings.company_id');
            })
            ->leftJoin('companies as bnfcompanies',function($join){
                $join->on('bnfcompanies.id','=','jobs.company_id');
            })
            ->leftJoin('companies as prodcompanies',function($join){
                $join->on('prodcompanies.id','=','sales_orders.produced_company_id');
            })
            ->leftJoin('buyers as dyeingcustomers',function($join){
                $join->on('dyeingcustomers.id','=','so_dyeings.buyer_id');
            })
            ->where([['prod_finish_dlvs.id','=',request('prod_finish_dlv_id',0)]])
            ->orderBy('inv_grey_fab_isu_items.id','desc')
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
                $prodknitqc->roll_weight=number_format($prodknitqc->roll_weight,2);
                $prodknitqc->batch_qty=number_format($prodknitqc->batch_qty,2);
                return $prodknitqc;
            });
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
    public function store(ProdFinishDlvRollRequest $request) {
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
        /*$max = $this->prodfinishdlv->max('dlv_no');
        $dlv_no=$max+1;
        $prodfinishdlv=$this->prodfinishdlv->create(['dlv_no'=>$dlv_no,'dlv_date'=>$request->dlv_date,'company_id'=>$request->company_id,'buyer_id'=>$request->buyer_id,'store_id'=>$request->store_id,'remarks'=>$request->remarks]);
        if($prodfinishdlv)
        {
            return response()->json(array('success' => true,'id' =>  $prodfinishdlv->id,'message' => 'Save Successfully'),200);
        }*/
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
        /*$prodfinishdlv = $this->prodfinishdlv->find($id);
        $prodfinishdlv->dlv_date=date('Y-m-d',strtotime($prodfinishdlv->dlv_date));
        $row ['fromData'] = $prodfinishdlv;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProdFinishDlvRollRequest $request, $id) {
        /*$prodfinishdlv=$this->prodfinishdlv->update($id,$request->except(['id','dlv_no','company_id','buyer_id']));
        if($prodfinishdlv){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }*/
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

    public function importAopProdFinishQcRoll(){
        $prodfinishdlv=$this->prodfinishdlv->find(request('prod_finish_dlv_id',0));
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $rollqcresult=array_prepend(config('bprs.rollqcresult'),'-Select-','');
        //echo json_encode($prodfinishdlv); die;
        $buyer=$this->buyer->find($prodfinishdlv->buyer_id);

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

            $prodknitqc=$this->prodbatchfinishqc
            ->selectRaw('
                prod_batch_finish_qc_rolls.id,
                prod_batch_finish_qc_rolls.qty as qc_pass_qty,
                prod_batch_finish_qc_rolls.reject_qty,
                prod_batch_finish_qc_rolls.gsm_weight as qc_gsm_weight,
                prod_batch_finish_qc_rolls.dia_width as qc_dia_width,
                prod_batch_finish_qc_rolls.grade_id,
                prod_batch_rolls.id as prod_batch_roll_id,
                prod_batch_rolls.qty as batch_qty,
                prod_batches.batch_no,
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
                dyeingcolors.name as dyeing_color,
                batch_colors.name as batch_color,
                prodbatchfinishdlvrolls.id as prod_finish_dlv_roll_id,
                dyeingcompanies.name as dyeing_company,
                dyeingcustomers.name as dyeing_customer,
                prodcompanies.name as prod_company_name,
                bnfcompanies.name as bnf_company_name,
                so_dyeings.sales_order_no as dyeing_sales_order_no
                --,
                --CASE 
                --WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
                --ELSE inhouseprods.customer_name
                --END as customer_name
            ')
            ->join('prod_batch_finish_qc_rolls',function($join){
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
            ->leftJoin('colors as batch_colors',function($join){
                $join->on('batch_colors.id','=','prod_batches.batch_color_id');
            })
            ->leftJoin(\DB::raw("(
                select 
                pl_knit_items.id,
                colorranges.name as colorrange_name,
                colorranges.id as colorrange_id,
                customer.name as customer_name,
                companies.id as company_id

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
            ->leftJoin('companies as dyeingcompanies',function($join){
                $join->on('dyeingcompanies.id','=','so_dyeings.company_id');
            })
            ->leftJoin('companies as bnfcompanies',function($join){
                $join->on('bnfcompanies.id','=','jobs.company_id');
            })
            ->leftJoin('companies as prodcompanies',function($join){
                $join->on('prodcompanies.id','=','sales_orders.produced_company_id');
            })
            ->leftJoin('buyers as dyeingcustomers',function($join){
                $join->on('dyeingcustomers.id','=','so_dyeings.buyer_id');
            })
            ->leftJoin(\DB::raw("(
            select
            prod_finish_dlv_rolls.id,
            prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
            from prod_finish_dlv_rolls
            where 
            prod_finish_dlv_rolls.deleted_at is null 
            ) prodbatchfinishdlvrolls"),"prodbatchfinishdlvrolls.prod_batch_finish_qc_roll_id","=","prod_batch_finish_qc_rolls.id")
            ->when(request('aop_from_qc_date',0), function ($q){
                return $q->where('prod_batch_finish_qcs.posting_date', '>=',request('aop_from_qc_date',0));
            })
            ->when(request('aop_to_qc_date',0), function ($q){
                return $q->where('prod_batch_finish_qcs.posting_date', '<=',request('aop_to_qc_date',0));
            })
            ->when(request('aop_batch_no'), function ($q) {
                return $q->where('prod_batches.batch_no', '=',request('aop_batch_no', 0));
            })
            ->when(request('aop_batch_date_from',0), function ($q){
                return $q->where('prod_batches.batch_date', '>=',request('aop_batch_date_from',0));
            })
            ->when(request('aop_batch_date_to',0), function ($q){
                return $q->where('prod_batches.batch_date', '<=',request('aop_batch_date_to',0));
            })
            ->where([['so_dyeings.buyer_id','=',$prodfinishdlv->buyer_id]])
            //->where([['style_fabrications.fabric_look_id','=',25]])
            ->orderBy('prod_batches.id','desc')
            ->orderBy('prod_knit_item_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype,$rollqcresult){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->dyetype=$prodknitqc->dyeing_type_id?$dyetype[$prodknitqc->dyeing_type_id]:'';
                $prodknitqc->bal_qty=$prodknitqc->rcv_qty-($prodknitqc->tot_batch_qty-$prodknitqc->batch_qty);
                $prodknitqc->grade=$prodknitqc->grade_id?$rollqcresult[$prodknitqc->grade_id]:'';
                $prodknitqc->batch_qty=number_format($prodknitqc->batch_qty,2);
                $prodknitqc->qc_pass_qty=number_format($prodknitqc->qc_pass_qty,2);
                $prodknitqc->reject_qty=number_format($prodknitqc->reject_qty,2);
                return $prodknitqc;
            })
            ->filter(function($prodknitqc){
                if(!$prodknitqc->prod_finish_dlv_roll_id){
                  return   $prodknitqc;
                }
            })
            ->values();
            echo json_encode($prodknitqc);
    }
}