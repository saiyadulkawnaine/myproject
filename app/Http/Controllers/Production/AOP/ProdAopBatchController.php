<?php

namespace App\Http\Controllers\Production\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Production\AOP\ProdAopBatchRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;


use App\Library\Template;
use App\Http\Requests\Production\AOP\ProdAopBatchRequest;

class ProdAopBatchController extends Controller {

    private $prodaopbatch;
    private $soaop;
    private $company;
    private $buyer;
    private $location;
    private $color;
    private $colorrange;
    private $assetquantitycost;
    private $uom;
    private $productionprocess;
    private $autoyarn;
    private $gmtspart;
    private $itemaccount;
    private $designation;
    private $department;

    public function __construct(
        ProdAopBatchRepository $prodaopbatch,  
        SoAopRepository $soaop,  
        CompanyRepository $company, 
        BuyerRepository $buyer, 
        LocationRepository $location,
        ColorRepository $color,
        ColorrangeRepository $colorrange,
        AssetQuantityCostRepository $assetquantitycost,
        UomRepository $uom,
        ProductionProcessRepository $productionprocess, 
        AutoyarnRepository $autoyarn ,
        GmtspartRepository $gmtspart,
        ItemAccountRepository $itemaccount,
        DesignationRepository $designation,
        DepartmentRepository $department
    ) {
        $this->prodaopbatch = $prodaopbatch;
        $this->soaop = $soaop;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->color = $color;
        $this->colorrange = $colorrange;
        $this->assetquantitycost = $assetquantitycost;
        $this->uom = $uom;
        $this->productionprocess = $productionprocess;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->itemaccount = $itemaccount;
        $this->designation = $designation;
        $this->department = $department;
        $this->middleware('auth');
        $this->middleware('permission:view.prodaopbatchs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodaopbatchs', ['only' => ['store']]);
        $this->middleware('permission:edit.prodaopbatchs',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodaopbatchs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $rows=$this->prodaopbatch
        ->join('so_aops',function($join){
            $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
        })
        ->join('companies',function($join){
		    $join->on('companies.id','=','so_aops.company_id');
		})
        ->join('buyers',function($join){
            $join->on('buyers.id','=','so_aops.buyer_id');
        })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_aop_batches.batch_color_id');
        })
        ->orderBy('prod_aop_batches.id','desc')
        ->take(100)
        ->get([
            'prod_aop_batches.*',
            'companies.code as company_code',
            'buyers.name as customer_name',
            'batch_colors.name as batch_color_name',
            'so_aops.sales_order_no'
        ])
        ->map(function($rows) use($batchfor){
            $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            $rows->fabric_wgt=number_format($rows->fabric_wgt,2);
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
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
        $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'','');
        //$process_name=array_prepend(array_pluck($this->productionprocess->where([['production_area_id','=',20]])->get(),'process_name','id'),'','');
        $process_name=array_prepend(array_pluck($this->productionprocess->whereIn('production_area_id',[20,25,30])->get(),'process_name','id'),'','');

        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $location = array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');




        return Template::loadView('Production.AOP.ProdAopBatch', [ 
            'company'=> $company,
            'buyer'=> $buyer,
            'location'=>$location,
            'color'=>$color,
            'colorrange'=>$colorrange,
            'batchfor'=>$batchfor,
            'uom'=>$uom,
            'process_name'=>$process_name,
            'shiftname'=>$shiftname,
            'designation'=>$designation,
            'department'=>$department,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdAopBatchRequest $request) {
       
        $prodaopbatch = $this->prodaopbatch->create($request->except(['id','fabric_wgt','sales_order_no']));
        if($prodaopbatch){
            return response()->json(array('success' => true,'id' =>  $prodaopbatch->id,'message' => 'Save Successfully'),200);
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
        $prodaopbatch = $this->prodaopbatch
        ->join('so_aops',function($join){
            $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
        })
        ->where([['prod_aop_batches.id','=',$id]])
        ->get([
            'prod_aop_batches.*',
            'so_aops.sales_order_no',
            'so_aops.company_id',
            'so_aops.buyer_id',
        ])
        ->first();
        $prodaopbatch->batch_date=date('Y-m-d',strtotime($prodaopbatch->batch_date));
        $row ['fromData'] = $prodaopbatch;
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
    public function update(ProdAopBatchRequest $request, $id) {
        $batch=$this->prodaopbatch->find($id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Update Not Allowed'),200);
        }

        $batchroll=$this->prodaopbatch
        ->join('prod_aop_batch_rolls',function($join){
            $join->on('prod_aop_batch_rolls.prod_aop_batch_id', '=', 'prod_aop_batches.id');
        })
        ->where([['prod_aop_batches.id','=',$id]])
        ->get();
        $prodaopbatch=0;
        if($batchroll->first()){
            $prodaopbatch = $this->prodaopbatch->update($id,
                [
                    'batch_no'=>$request->batch_no,
                    'batch_date'=>$request->batch_date,
                    'design_no'=>$request->design_no,
                    'paste_wgt'=>$request->paste_wgt,
                    'target_load_date'=>$request->target_load_date,
                    'remarks'=>$request->remarks,
                ]
            );

        }
        else{
          $prodaopbatch = $this->prodaopbatch->update($id,$request->except(['id','fabric_wgt','paste_wgt','sales_order_no']));
        }


        if($prodaopbatch){
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
        $batch=$this->prodaopbatch->find($id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Delete Not Allowed'),200);
        }

        if($this->prodaopbatch->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getSo()
    {
        return response()->json(
          $soaop=$this->soaop
          ->join('so_aop_fabric_isus', function($join)  {
          $join->on('so_aop_fabric_isus.so_aop_id', '=', 'so_aops.id');
          })
          ->join('companies', function($join)  {
          $join->on('companies.id', '=', 'so_aops.company_id');
          })
          ->leftJoin('buyers', function($join)  {
          $join->on('so_aops.buyer_id', '=', 'buyers.id');
          })
          ->when(request('so_no'), function ($q) {
            return $q->where('sales_order_no', 'LIKE', "%".request('so_no', 0)."%");
          })
          ->get([
          'so_aops.*',
          'buyers.name as buyer_name',
          'companies.name as company_name',
          'so_aop_fabric_isus.issue_no',
          'so_aop_fabric_isus.issue_date',
          ])
        );
    }

    public function getBatch(){

        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $rows=$this->prodaopbatch
        ->join('so_aops',function($join){
            $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','so_aops.company_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','so_aops.buyer_id');
        })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_aop_batches.batch_color_id');
        })
        ->orderBy('prod_aop_batches.id','desc')
        ->when(request('from_batch_date'), function ($q) {
        return $q->where('prod_aop_batches.batch_date', '>=', request('from_batch_date', 0));
        })
        ->when(request('to_batch_date'), function ($q) {
        return $q->where('prod_aop_batches.batch_date', '<=', request('to_batch_date', 0));
        })
        ->orderBy('prod_aop_batches.id','desc')
        ->get([
            'prod_aop_batches.*',
            'companies.code as company_code',
            'buyers.name as customer_name',
            'batch_colors.name as batch_color_name',
            'so_aops.sales_order_no'
        ])
        ->map(function($rows) use($batchfor){
            $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            return $rows;
        });
        echo json_encode($rows);

    }

    public function getPdf()
    {
       $id=request('id',0);
       $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
       $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');

        $rows=$this->prodaopbatch
        ->join('so_aops',function($join){
            $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','so_aops.company_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','so_aops.buyer_id');
        })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_aop_batches.batch_color_id');
        })
        ->join('users',function($join){
            $join->on('users.id','=','prod_aop_batches.created_by');
        })
        
        ->where([['prod_aop_batches.id','=',$id]])
        ->orderBy('prod_aop_batches.id','desc')
        ->get([
            'prod_aop_batches.*',
            'companies.name as company_name',
            'companies.logo as logo',
            'companies.address as company_address',
            'buyers.name as customer_name',
            'batch_colors.name as batch_color_name',
            'users.name as created_by',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batchfor=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('d-M-Y',strtotime($rows->batch_date));
            $rows->created_date=date('d-M-Y',strtotime($rows->created_at));
            $rows->created_time=date('h:i:s a',strtotime($rows->created_at));
            return $rows;
        })
        ->first();
        $batch['master']=$rows;

        $prodaopbatch=$this->prodaopbatch->find(request('prod_aop_batch_id',0));
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
        if($rows->batch_for==1){


        
        

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
            customers.name as customer_name
            
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
            ->join('buyers as customers',function($join){
            $join->on('customers.id','=','so_aops.buyer_id');
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
            ->where([['prod_aop_batches.id','=',$rows->id]])
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
        }
        if($rows->batch_for==2){

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
            customers.name as customer_name
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
            ->join('buyers as customers',function($join){
            $join->on('customers.id','=','so_aops.buyer_id');
            })
            ->leftJoin('colors as fabriccolors',function($join){
            $join->on('fabriccolors.id','=','so_aop_items.fabric_color_id');
            })
            ->where([['prod_aop_batches.id','=',$rows->id]])
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
        }
            $fabDtl=[];
            $ordDtl=[];
            foreach($prodknitqc as $data){
                $index=$data->gmtspart_id."-".$data->autoyarn_id."-".$data->gsm_weight."-".$data->dia_width."-".$data->fabric_look_id."-".$data->fabric_shape_id."-".$data->aop_type_id."-".$data->fabric_shape_id."-".$data->stitch_length;
                
                $fabDtl[$index]['body_part']=$data->body_part;
                $fabDtl[$index]['fabrication']=$data->fabrication;
                $fabDtl[$index]['dyeing_gsm_weight']=$data->dyeing_gsm_weight;
                $fabDtl[$index]['dyeing_dia_width']=$data->dyeing_dia_width;
                $fabDtl[$index]['fabric_look']=$data->fabric_look;
                $fabDtl[$index]['fabric_shape']=$data->fabric_shape;
                $fabDtl[$index]['stitch_length']=$data->stitch_length;
                $fabDtl[$index]['batch_qty'][]=$data->rcv_qty;
                $fabDtl[$index]['no_of_roll'][]=1;
                if ($data->machine_dia || $data->machine_gg) {
                    $fabDtl[$index]['machine_dia'][$data->machine_dia." x ".$data->machine_gg]=$data->machine_dia." x ".$data->machine_gg;
                }else {
                    $fabDtl[$index]['machine_dia'][$data->machine_dia." x ".$data->machine_gg]='';
                }
                 $ordDtl['sale_order_no'][$data->sale_order_no]=$data->sale_order_no;
                 $ordDtl['style_ref'][$data->style_ref]=$data->style_ref;
                 $ordDtl['buyer_name'][$data->buyer_name]=$data->buyer_name;
                 $ordDtl['ship_date'][$data->ship_date]=$data->ship_date?date("d-M-Y",strtotime($data->ship_date)):'';
                 $ordDtl['customer_name'][$data->customer_name]=$data->customer_name;



            }

            $batch['fabDtl']=$fabDtl;
            $batch['ordDtl']=$ordDtl;

            

            $prodaopbatchprocess=$this->prodaopbatch
            ->join('prod_aop_batch_processes',function($join){
            $join->on('prod_aop_batch_processes.prod_aop_batch_id','=','prod_aop_batches.id');
            })
            ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_aop_batch_processes.asset_quantity_cost_id');
            })
            ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
            })
            ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
            })
            ->join('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','prod_aop_batch_processes.supervisor_id');
            })
            ->join('production_processes', function($join)  {
            $join->on('production_processes.id', '=', 'prod_aop_batch_processes.production_process_id');
            })
            ->where([['prod_aop_batches.id','=',$rows->id]])
            ->orderby('prod_aop_batch_processes.sort_id')
            ->get([
            'prod_aop_batch_processes.*',
            'asset_quantity_costs.custom_no as machine_no',
            'asset_acquisitions.prod_capacity',
            'asset_acquisitions.origin',
            'asset_acquisitions.brand',
            'production_processes.process_name',
            'employee_h_rs.name as supervisor_name',
            ])
            ->map(function($prodaopbatchprocess) use($shiftname){
                $prodaopbatchprocess->shift_name=$shiftname[$prodaopbatchprocess->shift_id];
                $prodaopbatchprocess->prod_date=date('d-M-Y',strtotime($prodaopbatchprocess->prod_date));
                return $prodaopbatchprocess;

            });
            /*$proarr=[];
            foreach($prodaopbatchprocess as $process){
                $proarr[]=$process->sort_id.". ".$process->process_name;
            }*/
            $batch['proarr']=$prodaopbatchprocess;


      $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      $pdf->SetY(10);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(12);
      $pdf->SetFont('helvetica', 'N', 8);
      //$pdf->Text(115, 14, $rows->company_address);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      $pdf->SetY(16);
      //$pdf->AddPage();
        $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetY(5);
        $pdf->SetX(190);
        $challan=str_pad($batch['master']->id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetFont('helvetica', 'N', 10);
        //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);
        //$pdf->SetTitle('General Item Purchase Order');
        $view= \View::make('Defult.Production.AOP.ProdAopBatchPdf',['batch'=>$batch]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');

        $filename = storage_path() . '/ProdAopBatchPdf.pdf';
        $pdf->output($filename);
    }

    public function getPdfRoll()
    {
      $id=request('id',0);
       $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
       $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');

        $rows=$this->prodaopbatch
        ->join('so_aops',function($join){
            $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','so_aops.company_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','so_aops.buyer_id');
        })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_aop_batches.batch_color_id');
        })
        ->join('users',function($join){
            $join->on('users.id','=','prod_aop_batches.created_by');
        })
        
        ->where([['prod_aop_batches.id','=',$id]])
        ->orderBy('prod_aop_batches.id','desc')
        ->get([
            'prod_aop_batches.*',
            'companies.name as company_name',
            'companies.logo as logo',
            'companies.address as company_address',
            'buyers.name as customer_name',
            'batch_colors.name as batch_color_name',
            'users.name as created_by',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batchfor=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('d-M-Y',strtotime($rows->batch_date));
            $rows->created_date=date('d-M-Y',strtotime($rows->created_at));
            $rows->created_time=date('h:i:s a',strtotime($rows->created_at));
            return $rows;
        })
        ->first();
        $batch['master']=$rows;

        


        
        if($rows->batch_for==1){
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
            inv_grey_fab_items.measurment as knited_measurement,
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
            customers.name as customer_name,
            budget_fabrics.gsm_weight as req_gsm_weight
            
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
            ->join('buyers as customers',function($join){
            $join->on('customers.id','=','so_aops.buyer_id');
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
            ->where([['prod_aop_batches.id','=',$rows->id]])
            ->orderBy('prod_aop_batch_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc){
            $prodknitqc->barcode_no=str_pad($prodknitqc->prod_knit_item_roll_id,10,0,STR_PAD_LEFT);
            return $prodknitqc;
            });
        }

        if($rows->batch_for==2){

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
            customers.name as customer_name
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
            ->join('buyers as customers',function($join){
            $join->on('customers.id','=','so_aops.buyer_id');
            })
            ->leftJoin('colors as fabriccolors',function($join){
            $join->on('fabriccolors.id','=','so_aop_items.fabric_color_id');
            })
            ->where([['prod_aop_batches.id','=',$rows->id]])
            ->orderBy('prod_aop_batch_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc){
                $prodknitqc->machine_no='';
                $prodknitqc->barcode_no='';
                $prodknitqc->knit_company_name='';
                $prodknitqc->dyeing_dia_width='';
                return $prodknitqc;
            });
        }
        $groups = $prodknitqc->split(2)->toArray();
        
      //$batch['rolls']=$prodknitqc;
      $pdf = new \TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      $pdf->SetY(10);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(12);
      $pdf->SetFont('helvetica', 'N', 8);
      //$pdf->Text(70, 14, $rows->company_address);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      $pdf->SetY(16);
      //$pdf->AddPage();
        $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetY(5);
        $pdf->SetX(150);
        $challan=str_pad($batch['master']->id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetFont('helvetica', 'N', 10);
        //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);
        //$pdf->SetTitle('General Item Purchase Order');
        $view= \View::make('Defult.Production.AOP.ProdAopBatchRollPdf',['batch'=>$batch,'groups'=>$groups]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');

        $filename = storage_path() . '/ProdAopBatchRollPdf.pdf';
        $pdf->output($filename);
    }
}