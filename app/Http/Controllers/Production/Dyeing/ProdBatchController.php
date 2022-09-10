<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\BuyerRepository;


use App\Library\Template;
use App\Http\Requests\Production\Dyeing\ProdBatchRequest;

class ProdBatchController extends Controller {

    private $prodbatch;
    private $company;
    private $location;
    private $color;
    private $colorrange;
    private $assetquantitycost;
    private $uom;
    private $productionprocess;
    private $autoyarn;
    private $gmtspart;
    private $itemaccount;
    private $buyer;

    public function __construct(
        ProdBatchRepository $prodbatch,  
        CompanyRepository $company, 
        LocationRepository $location,
        ColorRepository $color,
        ColorrangeRepository $colorrange,
        AssetQuantityCostRepository $assetquantitycost,
        UomRepository $uom,
        ProductionProcessRepository $productionprocess, 
        AutoyarnRepository $autoyarn ,
        GmtspartRepository $gmtspart,
        ItemAccountRepository $itemaccount,
        BuyerRepository $buyer
    ) {
        $this->prodbatch = $prodbatch;
        $this->company = $company;
        $this->location = $location;
        $this->color = $color;
        $this->colorrange = $colorrange;
        $this->assetquantitycost = $assetquantitycost;
        $this->uom = $uom;
        $this->productionprocess = $productionprocess;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->itemaccount = $itemaccount;
        $this->buyer = $buyer;
        $this->middleware('auth');

        /*
        $this->middleware('permission:view.prodbatches',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodbatches', ['only' => ['store']]);
        $this->middleware('permission:edit.prodbatches',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodbatches', ['only' => ['destroy']]);
        */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $rows=$this->prodbatch
        ->join('companies',function($join){
		    $join->on('companies.id','=','prod_batches.company_id');
		})
        ->leftJoin('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
         ->leftJoin('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
        ->where([['prod_batches.is_redyeing','=',0]])
        ->whereNull('prod_batches.root_batch_id')
        ->orderBy('prod_batches.id','desc')
        ->take(100)
        ->get([
            'prod_batches.*',
            'companies.code as company_code',
            'colors.name as color_name',
            'batch_colors.name as batch_color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'colorranges.name as color_range_name',
            'locations.name as location_name',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
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
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
        $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'','');
        //$process_name=array_prepend(array_pluck($this->productionprocess->where([['production_area_id','=',20]])->get(),'process_name','id'),'','');
        $process_name=array_prepend(array_pluck($this->productionprocess->whereIn('production_area_id',[20,30])->get(),'process_name','id'),'','');

        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $location = array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $buyer = array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');



        return Template::loadView('Production.Dyeing.ProdBatch', [ 'company'=> $company,'color'=>$color,'colorrange'=>$colorrange,'batchfor'=>$batchfor,'uom'=>$uom,'process_name'=>$process_name,'location'=>$location,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdBatchRequest $request) {
        $request->request->add(['is_redyeing' => 0]);
       
        $prodbatch = $this->prodbatch->create($request->except(['id','machine_no','fabric_wgt','batch_wgt']));
        if($prodbatch){
            return response()->json(array('success' => true,'id' =>  $prodbatch->id,'message' => 'Save Successfully'),200);
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
        $prodbatch = $this->prodbatch
        ->join('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
        ->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
         ->leftJoin('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
       
        ->where([['prod_batches.id','=',$id]])
        ->get([
            'prod_batches.*',
            'asset_quantity_costs.custom_no as machine_no',
            'asset_acquisitions.brand',
            'asset_acquisitions.prod_capacity'
        ])
        ->first();
        $prodbatch->batch_date=date('Y-m-d',strtotime($prodbatch->batch_date));
        $row ['fromData'] = $prodbatch;
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
    public function update(ProdBatchRequest $request, $id) {
        $batch=$this->prodbatch->find($id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Update Not Allowed'),200);
        }

        $batchroll=$this->prodbatch
        ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
        })
        ->where([['prod_batches.id','=',$id]])
        ->get();
        $prodbatch=0;
        if($batchroll->first()){
            $prodbatch = $this->prodbatch->update($id,
                [
                    'company_id'=>$request->company_id,
                    'location_id'=>$request->location_id,
                    'batch_no'=>$request->batch_no,
                    'batch_date'=>$request->batch_date,
                    'machine_id'=>$request->machine_id,
                    'lap_dip_no'=>$request->lap_dip_no,
                    'batch_color_id'=>$request->batch_color_id,
                    'colorrange_id'=>$request->colorrange_id,
                    'target_load_date'=>$request->target_load_date,
                    'remarks'=>$request->remarks,
                    'colorrange_id'=>$request->colorrange_id,
                ]
            );

        }
        else{
          $prodbatch = $this->prodbatch->update($id,$request->except(['id','machine_no','fabric_wgt','batch_wgt']));
        }


        if($prodbatch){
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
        $batch=$this->prodbatch->find($id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Delete Not Allowed'),200);
        }

        if($this->prodbatch->delete($id)){
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

    public function getBatch(){

        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $rows=$this->prodbatch
        ->join('companies',function($join){
            $join->on('companies.id','=','prod_batches.company_id');
        })
        ->leftJoin('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
         ->leftJoin('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
        ->where([['prod_batches.is_redyeing','=',0]])
        ->whereNull('prod_batches.root_batch_id')
        ->when(request('from_batch_date'), function ($q) {
        return $q->where('prod_batches.batch_date', '>=', request('from_batch_date', 0));
        })
        ->when(request('to_batch_date'), function ($q) {
        return $q->where('prod_batches.batch_date', '<=', request('to_batch_date', 0));
        })
        ->orderBy('prod_batches.id','desc')
       
        ->get([
            'prod_batches.*',
            'companies.code as company_code',
            'colors.name as color_name',
            'batch_colors.name as batch_color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'colorranges.name as color_range_name',
            'locations.name as location_name',
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
        $rows=$this->prodbatch
        ->join('companies',function($join){
            $join->on('companies.id','=','prod_batches.company_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
        ->leftJoin('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
        ->join('users',function($join){
            $join->on('users.id','=','prod_batches.created_by');
        })
        ->leftJoin(\DB::raw("(
        select
        prod_batches.id,
        prod_batches.batch_no
        from prod_batches
        where prod_batches.root_batch_id is  null
        ) rootbatches"),"rootbatches.id","=","prod_batches.root_batch_id"
        )
        ->where([['prod_batches.id','=',$id]])
        ->orderBy('prod_batches.id','desc')
        ->get([
            'prod_batches.*',
            'companies.name as company_name',
            'companies.logo as logo',
            'companies.address as company_address',
            'colors.name as color_name',
            'batch_colors.name as batch_color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'colorranges.name as color_range_name',
            'rootbatches.id as org_batch_id',
            'rootbatches.batch_no as org_batch_no',
            'users.name as created_by',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batchfor=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('d-M-Y',strtotime($rows->batch_date));
            $rows->created_date=date('d-M-Y',strtotime($rows->created_at));
            $rows->created_time=date('h:i:s a',strtotime($rows->created_at));
            $rows->re_dyeing=$rows->is_redyeing?'( Re-Dyeing )':'';
            return $rows;
        })
        ->first();
        $batch['master']=$rows;

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
        if($rows->batch_for==1){


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
        ->where([['prod_batches.id','=',$id]])
        ->orderBy('inv_grey_fab_isu_items.id','desc')
        ->get()
        ->map(function($yarn) use($yarnDropdown){
            $yarn->yarn_count=$yarn->count."/".$yarn->symbol;
            $yarn->composition=$yarn->item_account_id?$yarnDropdown[$yarn->item_account_id]:'';
            return $yarn;
        });
        $yarnDtls=[];
        foreach($yarn as $yar){
            $yarnDtls[$yar->prod_knit_item_id][$yar->prod_knit_item_yarn_id]=/*$yar->itemclass_name." ".*/$yar->yarn_count." ".$yar->composition." ".$yar->yarn_type." ".$yar->brand." ".$yar->lot." ".$yar->color_name;

        }
        

        $prodknitqc=$this->prodbatch
        ->selectRaw('
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
            sales_orders.ship_date,
            styles.style_ref,
            buyers. name as buyer_name,
            style_fabrications.dyeing_type_id,
            po_dyeing_service_item_qties.fabric_color_id,
            dyeingcolors.name as dyeing_color,
            customers.name as customer_name
            
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
            END as buyer_name,

            CASE 
            WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
            ELSE inhouseprods.customer_name
            END as customer_name*/
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
        ->join('so_dyeing_refs',function($join){
            $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
        })
        ->join('so_dyeings',function($join){
            $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
         ->join('buyers as customers',function($join){
            $join->on('customers.id','=','so_dyeings.buyer_id');
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
            $join->on('colors.id','=','inv_grey_fab_items.color_id');
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
        
        ->where([['prod_batches.id','=',$id]])
        ->orderBy('sales_orders.ship_date','desc')
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
        }
        if($rows->batch_for==2){

            $prodknitqc=$this->prodbatch
            ->selectRaw('
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
            customers.name as customer_name,
            so_dyeing_fabric_rcv_items.yarn_des as yarndtl
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
            ->join('so_dyeing_refs',function($join){
            $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
            })
            ->join('so_dyeing_items',function($join){
            $join->on('so_dyeing_refs.id', '=', 'so_dyeing_items.so_dyeing_ref_id');
            })
            ->join('so_dyeings',function($join){
            $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->join('buyers as customers',function($join){
            $join->on('customers.id','=','so_dyeings.buyer_id');
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

            ->where([['prod_batches.id','=',$id]])
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
        }
            $fabDtl=[];
            $ordDtl=[];
            foreach($prodknitqc as $data){
                $index=$data->gmtspart_id."-".$data->autoyarn_id."-".$data->gsm_weight."-".$data->dia_width."-".$data->fabric_look_id."-".$data->fabric_shape_id."-".$data->dyeing_type_id."-".$data->fabric_shape_id."-".$data->stitch_length;
                
                $fabDtl[$index]['body_part']=$data->body_part;
                $fabDtl[$index]['fabrication']=$data->fabrication;
                $fabDtl[$index]['gsm_weight']=$data->gsm_weight;
                $fabDtl[$index]['dia_width']=$data->dia_width;
                $fabDtl[$index]['fabric_look']=$data->fabric_look;
                $fabDtl[$index]['fabric_shape']=$data->fabric_shape;
                $fabDtl[$index]['dyetype']=$data->dyetype;
                $fabDtl[$index]['stitch_length']=$data->stitch_length;
                $fabDtl[$index]['batch_qty'][]=$data->batch_qty;
                $fabDtl[$index]['no_of_roll'][]=1;
                $fabDtl[$index]['yarnDtl'][$data->yarndtl]=$data->yarndtl;
                //$fabDtl[$index]['machine_dia'][$data->machine_dia." x ".$data->machine_gg]=$data->machine_dia." x ".$data->machine_gg;
                if ($data->machine_dia || $data->machine_gg) {
                    $fabDtl[$index]['machine_dia'][$data->machine_dia." x ".$data->machine_gg]=$data->machine_dia." x ".$data->machine_gg;
                }else {
                    $fabDtl[$index]['machine_dia'][$data->machine_dia." x ".$data->machine_gg]='';
                }
                $fabDtl[$index]['machine_no'][$data->machine_no]=$data->machine_no;
                 $ordDtl['sale_order_no'][$data->sale_order_no]=$data->sale_order_no;
                 $ordDtl['style_ref'][$data->style_ref]=$data->style_ref;
                 $ordDtl['buyer_name'][$data->buyer_name]=$data->buyer_name;
                 $ordDtl['ship_date'][$data->ship_date]=$data->ship_date?date("d-M-Y",strtotime($data->ship_date)):'';
                 $ordDtl['customer_name'][$data->customer_name]=$data->customer_name;



            }

            $batch['fabDtl']=$fabDtl;
            $batch['ordDtl']=$ordDtl;

            $prodbatchtrim=$this->prodbatch
            ->join('prod_batch_trims', function($join)  {
            $join->on('prod_batch_trims.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('itemclasses', function($join)  {
            $join->on('itemclasses.id', '=', 'prod_batch_trims.itemclass_id');
            })
            ->join('uoms', function($join)  {
            $join->on('uoms.id', '=', 'prod_batch_trims.uom_id');
            })
            ->where([['prod_batches.id','=',$id]])
            ->get([
            'prod_batch_trims.*',
            'itemclasses.name as itemclass_name',
            'uoms.code as uom_code',
            ]);

            $batch['trimDtl']=$prodbatchtrim;

            $prodbatchprocess=$this->prodbatch
            ->join('prod_batch_processes', function($join)  {
            $join->on('prod_batch_processes.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('production_processes', function($join)  {
            $join->on('production_processes.id', '=', 'prod_batch_processes.production_process_id');
            })

            ->where([['prod_batches.id','=',$id]])
            ->orderBy('prod_batch_processes.sort_id')
            ->get([
            'prod_batch_processes.*',
            'production_processes.process_name',
            ]);
            $proarr=[];
            foreach($prodbatchprocess as $process){
                $proarr[]=$process->sort_id.". ".$process->process_name;
            }
            $batch['proarr']=$proarr;


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
        $view= \View::make('Defult.Production.Dyeing.ProdBatchPdf',['batch'=>$batch]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');

        $filename = storage_path() . '/ProdBatchPdf.pdf';
        $pdf->output($filename);
    }

    public function getPdfRoll()
    {
      $id=request('id',0);
      $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $rows=$this->prodbatch
        ->join('companies',function($join){
            $join->on('companies.id','=','prod_batches.company_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
         ->leftJoin('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
        ->join('users',function($join){
        $join->on('users.id','=','prod_batches.created_by');
        })
        ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->leftJoin(\DB::raw("(
        select
        prod_batches.id,
        prod_batches.batch_no
        from prod_batches
        where prod_batches.root_batch_id is  null
        ) rootbatches"),"rootbatches.id","=","prod_batches.root_batch_id"
        )
        ->where([['prod_batches.id','=',$id]])
        ->orderBy('prod_batches.id','desc')
        ->get([
            'prod_batches.*',
            'companies.name as company_name',
            'companies.logo as logo',
            'companies.address as company_address',
            'colors.name as color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'colorranges.name as color_range_name',
            'rootbatches.id as org_batch_id',
            'rootbatches.batch_no as org_batch_no',
            'users.name as user_name',
            'employee_h_rs.contact'
        ])
        ->map(function($rows) use($batchfor){
            $rows->batchfor=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('d-M-Y',strtotime($rows->batch_date));
            $rows->re_dyeing=$rows->is_redyeing?'( Re-Dyeing )':'';
            return $rows;
        })
        ->first();
        $batch['master']=$rows;

        


        
        if($rows->batch_for==1){
        $prodknitqc=$this->prodbatch
        ->selectRaw('
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
            knit_company.name as knit_company_name,

            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,
            prod_knit_items.id as prod_knit_item_id,

            suppliers.name as supplier_name,
            asset_quantity_costs.custom_no as machine_no,
            asset_technical_features.dia_width as machine_dia,
            asset_technical_features.gauge as machine_gg,
            gmtssamples.name as gmt_sample,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            styles.style_ref,
            buyers. name as buyer_name,
            style_fabrications.dyeing_type_id,
            po_dyeing_service_item_qties.fabric_color_id,
            po_dyeing_service_item_qties.dia as req_dia,
            dyeingcolors.name as dyeing_color,
            
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
        ->leftJoin ('suppliers as knit_company',function($join){
            $join->on('knit_company.id', '=', 'prod_knits.supplier_id');
        })
        ->join ('suppliers',function($join){
            $join->on('suppliers.id', '=', 'inv_grey_fab_items.supplier_id');
        })
        ->leftJoin ('colorranges',function($join){
            $join->on('colorranges.id', '=', 'inv_grey_fab_items.colorrange_id');
        })
        
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_grey_fab_items.color_id');
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
        ->where([['prod_batches.id','=',$id]])
        ->orderBy('prod_knit_item_rolls.custom_no')
        ->get()
        ->map(function($prodknitqc){
            $prodknitqc->barcode_no=str_pad($prodknitqc->prod_knit_item_roll_id,10,0,STR_PAD_LEFT);
            return $prodknitqc;
        });
        }
        if($rows->batch_for==2){

            $prodknitqc=$this->prodbatch
            ->selectRaw('
            prod_batch_rolls.id,
            prod_batch_rolls.qty as batch_qty,
            so_dyeing_fabric_rcv_rols.id as so_dyeing_fabric_rcv_rol_id,
            so_dyeing_fabric_rcv_rols.id as custom_no,
            so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id,
            so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
            so_dyeing_fabric_rcv_rols.qty as rcv_qty,

            so_dyeing_items.autoyarn_id,
            so_dyeing_items.gmtspart_id,
            so_dyeing_items.fabric_look_id,
            so_dyeing_items.fabric_shape_id,
            so_dyeing_items.gsm_weight,
            so_dyeing_items.dia as dia_width,
            so_dyeing_items.dia as req_dia,
            so_dyeing_items.measurment as measurement,
            so_dyeing_items.colorrange_id,
            colorranges.name as colorrange_name,
            so_dyeing_items.fabric_color_id,
            so_dyeing_items.dyeing_type_id,
            so_dyeing_items.gmt_sale_order_no as sale_order_no,
            so_dyeing_items.gmt_style_ref as style_ref,
            buyers.name as buyer_name,
            dyeingcolors.name as dyeing_color,
            so_dyeing_fabric_rcv_items.yarn_des as yarndtl
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
            ->where([['prod_batches.id','=',$id]])
            ->orderBy('prod_batch_rolls.id')
            ->get()
            ->map(function($prodknitqc){
                $prodknitqc->machine_no='';
                $prodknitqc->barcode_no='';
                $prodknitqc->knit_company_name='';
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
        $view= \View::make('Defult.Production.Dyeing.ProdBatchRollPdf',['batch'=>$batch,'groups'=>$groups]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');

        $filename = storage_path() . '/ProdBatchPdf.pdf';
        $pdf->output($filename);
    }
}