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


use App\Library\Template;
use App\Http\Requests\Production\Dyeing\ProdBatchRdRequest;

class ProdBatchRdController extends Controller {

    private $prodbatch;
    private $company;
    private $location;
    private $color;
    private $colorrange;
    private $assetquantitycost;
    private $uom;
    private $productionprocess;

    public function __construct(
        ProdBatchRepository $prodbatch,  
        CompanyRepository $company,
        LocationRepository $location, 
        ColorRepository $color,
        ColorrangeRepository $colorrange,
        AssetQuantityCostRepository $assetquantitycost,
        UomRepository $uom,
        ProductionProcessRepository $productionprocess 
    ) {
        $this->prodbatch = $prodbatch;
        $this->company = $company;
        $this->location = $location;
        $this->color = $color;
        $this->colorrange = $colorrange;
        $this->assetquantitycost = $assetquantitycost;
        $this->uom = $uom;
        $this->productionprocess = $productionprocess;
        $this->middleware('auth');
        
        /*$this->middleware('permission:view.prodbatchrds',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodbatchrds', ['only' => ['store']]);
        $this->middleware('permission:edit.prodbatchrds',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodbatchrds', ['only' => ['destroy']]);*/
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
         ->leftJoin(\DB::raw("(
            select
            prod_batches.id,
            prod_batches.batch_no
            from prod_batches
            where prod_batches.root_batch_id is  null
        ) rootbatches"),"rootbatches.id","=","prod_batches.root_batch_id")
        ->where([['prod_batches.is_redyeing','=',1]])
        ->whereNotNull('prod_batches.root_batch_id')
        ->orderBy('prod_batches.id','desc')
        ->take(100)
        ->get([
            'prod_batches.*',
            'companies.code as company_code',
            'colors.name as color_name',
            'batch_colors.name as batch_color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'colorranges.name as color_range_name',
            'rootbatches.batch_no as root_batch_no',
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
        $process_name=array_prepend(array_pluck($this->productionprocess->whereIn('production_area_id',[20,30])->get(),'process_name','id'),'','');
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $location = array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');



        return Template::loadView('Production.Dyeing.ProdBatchRd', [ 'company'=> $company,'color'=>$color,'colorrange'=>$colorrange,'batchfor'=>$batchfor,'uom'=>$uom,'process_name'=>$process_name,'location'=>$location]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdBatchRdRequest $request) {
        $no_of_redyeing_batch=$this->prodbatch->where([['root_batch_id','=',$request->root_batch_id]])->get();
        $batch_ext_no=$no_of_redyeing_batch->count()+1;

        $prodrootbatch=$this->prodbatch->find($request->root_batch_id);
        $request->request->add(['is_redyeing' => 1]);
        $request->request->add(['company_id' => $prodrootbatch->company_id]);
        $request->request->add(['batch_for' => $prodrootbatch->batch_for]);
        $request->request->add(['fabric_color_id' => $prodrootbatch->fabric_color_id]);
        $request->request->add(['batch_color_id' => $prodrootbatch->batch_color_id]);
        $request->request->add(['colorrange_id' => $prodrootbatch->colorrange_id]);
        $request->request->add(['batch_ext_no' => $batch_ext_no]);
        $request->request->add(['location_id' => $prodrootbatch->location_id]);
        $request->request->add(['lap_dip_no' => $prodrootbatch->lap_dip_no]);
        $request->request->add(['target_load_date' => $prodrootbatch->target_load_date]);
        $prodbatch = $this->prodbatch->create($request->except(['id','machine_no','fabric_wgt','batch_wgt','root_batch_no']));
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
        ->leftJoin(\DB::raw("(
            select
            prod_batches.id,
            prod_batches.batch_no
            from prod_batches
            where prod_batches.root_batch_id is  null
        ) rootbatches"),"rootbatches.id","=","prod_batches.root_batch_id")
       
        ->where([['prod_batches.id','=',$id]])
        ->get([
            'prod_batches.*',
            'asset_quantity_costs.custom_no as machine_no',
            'asset_acquisitions.brand',
            'asset_acquisitions.prod_capacity',
            'rootbatches.batch_no as root_batch_no',
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
    public function update(ProdBatchRdRequest $request, $id) {
        $batch=$this->prodbatch->find($id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Update Not Allowed'),200);
        }
        $prodbatch = $this->prodbatch->update($id,$request->except(['id','machine_no','fabric_wgt','batch_wgt','root_batch_id','root_batch_no','company_id','batch_for','location_id','lap_dip_no','fabric_color_id','colorrange_id','batch_color_id']));

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

    public function getRootbatch(){
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
        ->where([['prod_batches.is_redyeing','=',0]])
        ->whereNull('prod_batches.root_batch_id')
        ->when(request('batch_no'), function ($q) {
        return $q->where('prod_batches.batch_no', '=',request('batch_no', 0));
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('prod_batches.company_id', '=',request('company_id', 0));
        })
        ->when(request('batch_for'), function ($q) {
        return $q->where('prod_batches.batch_for', '=',request('batch_for', 0));
        })
        ->orderBy('prod_batches.id','desc')
        ->get([
            'prod_batches.*',
            'companies.code as company_code',
            'colors.name as color_name',
            'batch_colors.name as batch_color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'colorranges.name as color_range_name',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batchfor=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            return $rows;
        });
        echo json_encode($rows);

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
         ->leftJoin(\DB::raw("(
            select
            prod_batches.id,
            prod_batches.batch_no
            from prod_batches
            where prod_batches.root_batch_id is  null
        ) rootbatches"),"rootbatches.id","=","prod_batches.root_batch_id")
        ->where([['prod_batches.is_redyeing','=',1]])
        ->whereNotNull('prod_batches.root_batch_id')
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
            'rootbatches.batch_no as root_batch_no',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            return $rows;
        });
        echo json_encode($rows);
    }
}