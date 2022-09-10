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
use App\Repositories\Contracts\Production\Kniting\ProdKnitQcRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\GmtssampleRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\Production\Kniting\ProdKnitRcvByQcRequest;

class ProdKnitQcController extends Controller {

    
    private $prodknit;
    private $prodknititem;
    private $prodknititemroll;
    private $prodknitrcvbyqc;
    private $prodknitqc;
    private $color;
    private $gmtssample;
    private $gmtspart;
    private $autoyarn;
    private $company;
    private $supplier;
    private $buyer;
  

    public function __construct(
        ProdKnitRcvByQcRepository $prodknitrcvbyqc,
        ProdKnitQcRepository $prodknitqc,
        ProdKnitRepository $prodknit, 
        ProdKnitItemRepository $prodknititem, 
        ProdKnitItemRollRepository $prodknititemroll,
        ColorRepository $color,
        GmtssampleRepository $gmtssample,
        GmtspartRepository $gmtspart,
        AutoyarnRepository $autoyarn,
        CompanyRepository $company,
        SupplierRepository $supplier,
        BuyerRepository $buyer
        ) 
    {
        $this->prodknitrcvbyqc = $prodknitrcvbyqc;
        $this->prodknitqc = $prodknitqc;
        $this->prodknit = $prodknit;
        $this->prodknititem = $prodknititem;
        $this->prodknititemroll = $prodknititemroll;
        $this->color = $color;
        $this->gmtssample = $gmtssample;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->buyer = $buyer;
        $this->middleware('auth');

        $this->middleware('permission:view.prodknitqcs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodknitqcs', ['only' => ['store']]);
        $this->middleware('permission:edit.prodknitqcs',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodknitqcs', ['only' => ['destroy']]); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $rollqcresult=array_prepend(config('bprs.rollqcresult'),'-Select-','');


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

        $qc_date=request('qc_date',0)?request('qc_date',0):date('Y-m-d');

        $prodknitqc=$this->prodknitqc
        ->selectRaw(' 
            prod_knit_qcs.id,   
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
            colors.name as fabric_color_name,
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
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
        })

        ->leftJoin(\DB::raw("(
            select 
            pl_knit_items.id,
            colorranges.name as colorrange_name,
            customer.name as customer_name,
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
            WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
            ELSE styles.style_ref
            END as style_ref,
            CASE 
            WHEN buyers.name IS NULL THEN outbuyers.name 
            ELSE buyers.name
            END as buyer_name
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
        ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
        ->leftJoin(\DB::raw("(
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        buyers.name as buyer_name,
        companies.name as customer_name  
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
        ->where([['prod_knit_qcs.qc_date','=',$qc_date]])
        //->where([['prod_knit_qcs.qc_date','<=',request('to_qc_date',0)]])
        ->orderBy('prod_knit_qcs.id','desc')
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart,$rollqcresult){
            $prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';

             $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            $prodknitqc->qc_result=$rollqcresult[$prodknitqc->qc_result];
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

        
        $rollqcresult=array_prepend(config('bprs.rollqcresult'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');

        return Template::loadView('Production.Kniting.ProdKnitQc' ,['rollqcresult'=>$rollqcresult,'company'=>$company,'supplier'=>$supplier,'buyer'=>$buyer] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdKnitRcvByQcRequest $request) {
        $other_roll=request('other_prod_knit_item_roll_id');
        $other_roll_arr=explode(',',$other_roll);
        array_unshift($other_roll_arr,$request->prod_knit_item_roll_id);
        $other_roll_arr_id=array_unique($other_roll_arr);
        \DB::beginTransaction();
        try
        {
            foreach( $other_roll_arr_id as $key=>$prod_knit_item_roll_id)
            {
                $prodknitrcvbyqc=$this->prodknitrcvbyqc->create([
                'prod_knit_item_roll_id'=>$prod_knit_item_roll_id,
                'receive_date'=>$request->qc_date,
                ]);
                $prodknititemroll=$this->prodknititemroll->find($prod_knit_item_roll_id);
                $qc_pass_qty=$prodknititemroll->roll_weight-$request->reject_qty;

                $prodknitqc=$this->prodknitqc->create([
                'prod_knit_rcv_by_qc_id'=>$prodknitrcvbyqc->id,
                'prod_knit_item_roll_id'=>$prod_knit_item_roll_id,
                'qc_date'=>$request->qc_date,
                'gsm_weight'=>$request->gsm_weight,
                'dia_width'=>$request->dia_width,
                'measurement'=>$request->measurement,
                'roll_length'=>$request->roll_length,
                'shrink_per'=>$request->shrink_per,
                'reject_qty'=>$request->reject_qty,
                //'qc_pass_qty'=>$request->qc_pass_qty,
                'qc_pass_qty'=>$qc_pass_qty,
                'reject_qty_pcs'=>$request->reject_qty_pcs,
                'qc_pass_qty_pcs'=>$request->qc_pass_qty_pcs,
                'qc_result'=>$request->qc_result
                ]);
            }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
		

        if($prodknitqc){
            return response()->json(array('success' => true,'id' =>  $prodknitqc->id,'message' => 'Save Successfully'),200);
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
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
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
        foreach($fabricDescription as $data){
            $fabricDescriptionArr[$data->id]=$data->construction;
            $fabricCompositionArr[$data->id][]=$data->name." ".$data->ratio."%";
        }
        
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $prodknitqc=$this->prodknitqc
        ->selectRaw('
            prod_knit_qcs.id,   
            prod_knit_qcs.prod_knit_item_roll_id as roll_no,   
            prod_knit_qcs.qc_date,   
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
            colors.name as fabric_color_name,
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
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
        })

        ->leftJoin(\DB::raw("(
            select 
            pl_knit_items.id,
            colorranges.name as colorrange_name,
            customer.name as customer_name,
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
            WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
            ELSE styles.style_ref
            END as style_ref,
            CASE 
            WHEN buyers.name IS NULL THEN outbuyers.name 
            ELSE buyers.name
            END as buyer_name
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
        ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
        ->leftJoin(\DB::raw("(
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        buyers.name as buyer_name,
        companies.name as customer_name  
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
        ->where([['prod_knit_qcs.id','=',$id]])
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';

             $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            return $prodknitqc;
        })->first();
        $row ['fromData'] = $prodknitqc;
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
        
        $prodknitqc=$this->prodknitqc->update($id,[
                //'prod_knit_item_roll_id'=>$request->prod_knit_item_roll_id,
                'qc_date'=>$request->qc_date,
                'gsm_weight'=>$request->gsm_weight,
                'dia_width'=>$request->dia_width,
                'measurement'=>$request->measurement,
                'roll_length'=>$request->roll_length,
                'shrink_per'=>$request->shrink_per,
                'reject_qty'=>$request->reject_qty,
                'qc_pass_qty'=>$request->qc_pass_qty,
                'reject_qty_pcs'=>$request->reject_qty_pcs,
                'qc_pass_qty_pcs'=>$request->qc_pass_qty_pcs,
                'qc_result'=>$request->qc_result
                ]);
        if($prodknitqc){
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
        return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
        if($this->prodknitqc->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function importProdKnitRoll(){


        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
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



        $prodknitqc=$this->prodknititemroll
        ->selectRaw('   
            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,
            prod_knit_item_rolls.roll_length,
            prod_knit_item_rolls.roll_weight,
            prod_knit_item_rolls.width,
            prod_knit_item_rolls.measurment as measurement,
            prod_knit_item_rolls.qty_pcs,
            prod_knit_item_rolls.fabric_color,
            colors.name as fabric_color_name,
            prod_knit_item_rolls.gmt_sample,
            prod_knit_items.prod_knit_id,
            prod_knit_items.stitch_length,
            prod_knit_items.gsm_weight,
            prod_knit_items.dia as dia_width,
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
            prod_knit_rcv_by_qcs.id as prod_knit_rcv_by_qc_id

            
        ')
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
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
        })

        ->leftJoin(\DB::raw("(
            select 
            pl_knit_items.id,
            colorranges.name as colorrange_name,
            customer.name as customer_name,
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
            WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
            ELSE styles.style_ref
            END as style_ref,
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
        ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
        ->leftJoin(\DB::raw("(
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        buyers.name as buyer_name,
        buyers.id as buyer_id,
        companies.name as customer_name  
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

        ->leftJoin('prod_knit_rcv_by_qcs',function($join){
            $join->on('prod_knit_rcv_by_qcs.prod_knit_item_roll_id','=','prod_knit_item_rolls.id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','outhouseprods.buyer_id');
            $join->Oron('buyers.id','=','inhouseprods.buyer_id');
        })
        ->when(request('prod_knit_item_roll_id',0), function ($q) {
        return $q->where('prod_knit_item_rolls.id', '!=', request('prod_knit_item_roll_id', 0));
        })
        ->when(request('date_from',0), function ($q) {
        return $q->where('prod_knits.prod_date', '>=', request('date_from', 0));
        })
        ->when(request('date_to',0), function ($q) {
        return $q->where('prod_knits.prod_date', '<=', request('date_to', 0));
        })
        ->when(request('supplier_id',0), function ($q) {
        return $q->where('prod_knits.supplier_id', '=', request('supplier_id', 0));
        })
        ->when(request('buyer_id',0), function ($q){
        return $q->where('buyers.id', '=', request('buyer_id',0));
        })
        ->where([['prod_knit_item_rolls.id','>=',126753]])
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
            $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            return $prodknitqc;
        })
        ->filter(function ($prodknitqc) {
            if(!$prodknitqc->prod_knit_rcv_by_qc_id){
                return $prodknitqc;
            }
        })
        ->values();
        echo json_encode($prodknitqc);
    }

    public function searchRoll(){

    	$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
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

        //$qc_date=request('qc_date',0)?request('qc_date',0):date('Y-m-d');

        $prodknitqc=$this->prodknitqc
        ->selectRaw(' 
            prod_knit_qcs.id,   
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
            colors.name as fabric_color_name,
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
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
        })

        ->leftJoin(\DB::raw("(
            select 
            pl_knit_items.id,
            colorranges.name as colorrange_name,
            customer.name as customer_name,
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
            WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
            ELSE styles.style_ref
            END as style_ref,
            CASE 
            WHEN buyers.name IS NULL THEN outbuyers.name 
            ELSE buyers.name
            END as buyer_name
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
        ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
        ->leftJoin(\DB::raw("(
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        buyers.name as buyer_name,
        companies.name as customer_name  
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
        //->where([['prod_knit_qcs.qc_date','>=',request('from_qc_date',0)]])
        //->where([['prod_knit_qcs.qc_date','<=',request('to_qc_date',0)]])
        ->when(request('from_qc_date',0), function ($q) {
        return $q->where('prod_knit_qcs.qc_date', '>=', request('from_qc_date', 0));
        })
        ->when(request('to_qc_date',0), function ($q) {
        return $q->where('prod_knit_qcs.qc_date', '<=', request('to_qc_date', 0));
        })
        ->get()
        ->map(function($prodknitqc) use($shiftname,$desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
            $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';

             $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
            $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
            $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
            return $prodknitqc;
        });
        echo json_encode($prodknitqc);

    }
}