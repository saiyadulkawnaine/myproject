<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\PlKnitRequest;

class PlDyeingController extends Controller {

    private $pldyeing;
    private $buyer;
    private $company;
    private $supplier;
    private $colorrange;
    private $gmtspart;
    private $assetquantitycost;

    public function __construct(
        PlDyeingRepository $pldyeing,
        BuyerRepository $buyer,
        CompanyRepository $company, 
        SupplierRepository $supplier, 
        ColorrangeRepository $colorrange,
        GmtspartRepository $gmtspart,
        AssetQuantityCostRepository $assetquantitycost
    ) {
        $this->pldyeing = $pldyeing;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->colorrange = $colorrange;
        $this->gmtspart = $gmtspart;
        $this->assetquantitycost = $assetquantitycost;
/*  
        $this->middleware('auth');
        $this->middleware('permission:view.pldyeings',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.pldyeings', ['only' => ['store']]);
        $this->middleware('permission:edit.pldyeings',   ['only' => ['update']]);
        $this->middleware('permission:delete.pldyeings', ['only' => ['destroy']]);

        */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         
        $rows=$this->pldyeing
        ->leftJoin('suppliers', function($join)  {
            $join->on('pl_dyeings.supplier_id', '=', 'suppliers.id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('pl_dyeings.company_id', '=', 'companies.id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','pl_dyeings.machine_id');
        })
        
        ->when(request('buyer'), function ($q) {
            return $q->where('pl_dyeings.buyer', '=', request('buyer', 0));
        })
          
        ->orderBy('pl_dyeings.id','desc')
        ->get([
            'pl_dyeings.*',
            'suppliers.name as supplier_id',
            'companies.name as company_id',
            'asset_quantity_costs.custom_no as machine_no',
		])
        ->map(function( $rows){
             $rows->pl_date=date('Y-m-d',strtotime($rows->pl_date));
             return $rows;

        });
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');


        return Template::LoadView('Subcontract.Dyeing.PlDyeing',[
            'company'=>$company,
            'buyer'=>$buyer,
            'supplier'=>$supplier,
            'colorrange'=>$colorrange,
            'fabriclooks'=>$fabriclooks,
            'fabricshape'=>$fabricshape,
            'gmtspart'=>$gmtspart
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlKnitRequest $request) {
        $max = $this->pldyeing->where([['company_id', $request->company_id]])->max('pl_no');
        $pl_no=$max+1;
        $pldyeing = $this->pldyeing->create([
            'pl_no'=>$pl_no,
            'company_id'=>$request->company_id,
            'pl_date'=>$request->pl_date,
            'supplier_id'=>$request->supplier_id,
            'machine_id'=>$request->machine_id,
            'remarks'=>$request->remarks
        ]);
        if($pldyeing){
            return response()->json(array('success' => true,'id' =>  $pldyeing->id,'message' => 'Save Successfully'),200);
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
        $pldyeing = 
        $this->pldyeing
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','pl_dyeings.machine_id');
        })
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->where([['pl_dyeings.id','=',$id]])
        ->get([
            'pl_dyeings.*',
            'asset_quantity_costs.custom_no as machine_no',
            'asset_acquisitions.brand',
            'asset_acquisitions.prod_capacity'
        ])->first();
        $row ['fromData'] = $pldyeing;
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
    public function update(PlKnitRequest $request, $id) {
        $pldyeing=$this->pldyeing->update($id,$request->except(['id','pl_no','company_id','machine_no']));
        if($pldyeing){
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
        if($this->pldyeing->delete($id)){
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