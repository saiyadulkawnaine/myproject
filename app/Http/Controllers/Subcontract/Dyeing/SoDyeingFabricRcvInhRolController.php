<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvItemRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRolRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRefRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingFabricRcvInhRolRequest;

class SoDyeingFabricRcvInhRolController extends Controller {
   
    private $sodyeingfabricrcv;
    private $sodyeingfabricrcvitem;
    private $sodyeingfabricrcvrol;
    private $sodyeing;
    private $podyeingref;
    private $sodyeingitem;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $invisu;

    public function __construct(
		SoDyeingFabricRcvRepository $sodyeingfabricrcv,
		SoDyeingFabricRcvItemRepository $sodyeingfabricrcvitem,
		SoDyeingFabricRcvRolRepository $sodyeingfabricrcvrol,
		SoDyeingRepository $sodyeing, 
		SoDyeingRefRepository $podyeingref, 
		SoDyeingItemRepository $sodyeingitem, 
		AutoyarnRepository $autoyarn,
		GmtspartRepository $gmtspart,
		UomRepository $uom,
		ColorrangeRepository $colorrange,
		ColorRepository $color,
		InvIsuRepository $invisu
    ) {
        $this->sodyeingfabricrcv = $sodyeingfabricrcv;
        $this->sodyeingfabricrcvitem = $sodyeingfabricrcvitem;
        $this->sodyeingfabricrcvrol = $sodyeingfabricrcvrol;
        $this->sodyeing = $sodyeing;
        $this->podyeingref = $podyeingref;
        $this->sodyeingitem = $sodyeingitem;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->invisu = $invisu;
        $this->middleware('auth');
      
        //$this->middleware('permission:view.sodyeingfabricrcvinhitemrols',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingfabricrcvinhitemrols', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingfabricrcvinhitemrols',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingfabricrcvinhitemrols', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'--','');
		$color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
		$uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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

       



		

		$prodknitqc=$this->sodyeingfabricrcvitem
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
            

            

            suppliers.name as supplier_name,
            gmtssamples.name as gmt_sample,
            sales_orders.sale_order_no,
            styles.style_ref,
            buyers. name as buyer_name,
            inv_isus.issue_no,
            

            

            
           /* CASE 
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
        ->join('so_dyeing_fabric_rcv_rols',function($join){
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
       /* ->leftJoin('styles',function($join){
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
        })*/
        ->where([['so_dyeing_fabric_rcv_items.id','=',request('so_dyeing_fabric_rcv_item_id')]])
        
        ->orderBy('so_dyeing_fabric_rcv_rols.id','desc')
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,2);
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
		$so_dyeing_fabric_rcv_item_id=request('so_dyeing_fabric_rcv_item_id',0);
		$sodyeingfabricrcvitem=$this->sodyeingfabricrcvitem->find($so_dyeing_fabric_rcv_item_id);

		$colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'--','');
		$color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
		$uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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
		

		$prodknitqc=$this->invisu
        ->selectRaw('
            inv_isus.issue_no,
            inv_grey_fab_isu_items.id,
            inv_grey_fab_isu_items.qty as isu_qty,
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

            suppliers.name as supplier_name,
            gmtssamples.name as gmt_sample,

            
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
            END as customer_name,
            sodyeingfabricrcvrols.id as so_dyeing_fabric_rcv_rol_id

            
        ')
        ->join('inv_grey_fab_isu_items',function($join){
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
            style_fabrications.id as style_fabrication_id,
            budget_fabrics.id as budget_fabric_id,
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
            where sales_orders.id=" .request('sales_order_id'). "
            and style_fabrications.id=" .request('style_fabrication_id'). "
            and budget_fabrics.id=" .request('budget_fabric_id'). "
        ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
        ->leftJoin(\DB::raw("(
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        colorranges.id as colorrange_id,
        style_fabrications.id as style_fabrication_id, 
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        budget_fabrics.id as budget_fabric_id,
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
        where sales_orders.id=" .request('sales_order_id'). "
        and style_fabrications.id=" .request('style_fabrication_id'). "
        and budget_fabrics.id=" .request('budget_fabric_id'). "
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
        ->leftJoin('style_fabrications',function($join){
            $join->on('style_fabrications.id','=','outhouseprods.style_fabrication_id');
            $join->Oron('style_fabrications.id','=','inhouseprods.style_fabrication_id');
        })
        ->leftJoin('budget_fabrics',function($join){
            $join->on('budget_fabrics.id','=','outhouseprods.budget_fabric_id');
            $join->Oron('budget_fabrics.id','=','inhouseprods.budget_fabric_id');
        })

        ->leftJoin(\DB::raw("(
        select
        so_dyeing_fabric_rcv_rols.id,
        so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
        from so_dyeing_fabric_rcv_rols
        where so_dyeing_fabric_rcv_rols.deleted_at is null
        ) sodyeingfabricrcvrols"),"sodyeingfabricrcvrols.inv_grey_fab_isu_item_id","=","inv_grey_fab_isu_items.id")

        ->where([['inv_grey_fab_items.autoyarn_id','=',request('autoyarn_id')]])
        ->where([['inv_grey_fab_items.gmtspart_id','=',request('gmtspart_id')]])
        ->where([['inv_grey_fab_items.fabric_look_id','=',request('fabric_look_id')]])
        ->where([['inv_grey_fab_items.fabric_shape_id','=',request('fabric_shape_id')]])
        ->where([['sales_orders.id','=',request('sales_order_id')]])
        ->where([['style_fabrications.id','=',request('style_fabrication_id')]])
        ->where([['budget_fabrics.id','=',request('budget_fabric_id')]])
        ->where([['prod_knit_item_rolls.fabric_color','=',request('fabric_color_id')]])
        ->where([['inv_isus.menu_id','=',220]])
        ->where([['inv_isus.isu_basis_id','=',1]])
        ->orderBy('inv_grey_fab_isu_items.id','desc')
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->isu_qty=number_format($prodknitqc->isu_qty,2);
            return $prodknitqc;
        })
        ->filter(function($prodknitqc){
            if(!$prodknitqc->so_dyeing_fabric_rcv_rol_id){
                return $prodknitqc; 
            }
        })
        ->values();
        echo json_encode($prodknitqc);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoDyeingFabricRcvInhRolRequest $request) {
		$inv_grey_fab_isu_item_id_arr=explode(',',request('inv_grey_fab_isu_item_id',0));
		foreach($inv_grey_fab_isu_item_id_arr as $index=>$inv_grey_fab_isu_item_id)
		{
			$sodyeingfabricrcvrol=$this->sodyeingfabricrcvrol->create([
			'so_dyeing_fabric_rcv_item_id'=>$request->so_dyeing_fabric_rcv_item_id,
			'inv_grey_fab_isu_item_id'=>$inv_grey_fab_isu_item_id,
			'qty'=>0,
			'rate'=>0,
			'amount'=>0,
			]);
		}

		if($sodyeingfabricrcvrol){
			return response()->json(array('success' => true,'id' =>  $sodyeingfabricrcvrol->id,'so_dyeing_fabric_rcv_item_id' =>  $request->so_dyeing_fabric_rcv_item_id,'message' => 'Save Successfully'),200);
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
        $rows=$this->sodyeingfabricrcvitem->find($id);
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
    public function update(SoDyeingFabricRcvInhRolRequest $request, $id) {
		if($request->qty <= 0){
			return response()->json(array('success' => false,'id' => $id,'so_dyeing_fabric_rcv_id' => $request->so_dyeing_fabric_rcv_id,'message' => '0 Qty Not Allowed'),200);
		}
		if($request->rate <= 0){
			return response()->json(array('success' => false,'id' => $id,'so_dyeing_fabric_rcv_id' => $request->so_dyeing_fabric_rcv_id,'message' => '0 Rate Not Allowed'),200);
		}
		$sodyeingfabricrcvitem=$this->sodyeingfabricrcvitem->update($id,$request->except(['id','so_dyeing_fabric_rcv_id','so_dyeing_ref_id']));

		if($sodyeingfabricrcvitem){
			return response()->json(array('success' => true,'id' => $id,'so_dyeing_fabric_rcv_id' => $request->so_dyeing_fabric_rcv_id,'message' => 'Update Successfully'),200);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
		if($this->sodyeingfabricrcvitem->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}