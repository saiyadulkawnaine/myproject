<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcSetupRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Library\Template;
use Illuminate\Support\Carbon;
use App\Http\Requests\Production\Dyeing\ProdFinishMcSetupRequest;

class ProdFinishMcSetupController extends Controller {

    private $prodfinishmcsetup;
    private $assetquantitycost;
    private $company;
    private $designation;
    private $department;

    public function __construct(
        ProdFinishMcSetupRepository $prodfinishmcsetup,
        AssetQuantityCostRepository $assetquantitycost,
        CompanyRepository $company,
        DesignationRepository $designation,
        DepartmentRepository $department
     ) {

        $this->prodfinishmcsetup = $prodfinishmcsetup;
        $this->company = $company;
        $this->assetquantitycost = $assetquantitycost;
        $this->designation = $designation;
        $this->department = $department;
        $this->middleware('auth');

        // $this->middleware('permission:view.prodfinishmcsetups',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.prodfinishmcsetups', ['only' => ['store']]);
        // $this->middleware('permission:edit.prodfinishmcsetups',   ['only' => ['update']]);
        // $this->middleware('permission:delete.prodfinishmcsetups', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $prodfinishmcsetups = array();

        $rows=$this->prodfinishmcsetup
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_finish_mc_setups.machine_id');
        })
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','asset_acquisitions.company_id');
        })
        ->orderBy('prod_finish_mc_setups.id','desc')
        ->get([
            'prod_finish_mc_setups.*',
            'asset_quantity_costs.custom_no',
            'companies.name as company_name',
             'asset_acquisitions.name as asset_name'
        ]);

        foreach($rows as $row){
           $prodfinishmcsetup['id']=$row->id;
           $prodfinishmcsetup['custom_no']=$row->custom_no;
           $prodfinishmcsetup['company_name']=$row->company_name;
           $prodfinishmcsetup['asset_name']=$row->asset_name;
           $prodfinishmcsetup['remarks']=$row->remarks;
           array_push($prodfinishmcsetups,$prodfinishmcsetup);
        }
        echo json_encode($prodfinishmcsetups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        return Template::loadView('Production.Dyeing.ProdFinishMcSetup',['shiftname'=>$shiftname,'company'=>$company,'designation'=>$designation,'department'=>$department]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdFinishMcSetupRequest $request) {
        $prodfinishmcsetup = $this->prodfinishmcsetup->create([
         'machine_id'=>$request->machine_id,
         'remarks'=>$request->remarks,
        ]);
        if($prodfinishmcsetup){
            return response()->json(array('success' => true,'id' =>  $prodfinishmcsetup->id,'message' => 'Save Successfully'),200);
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
        $prodfinishmcsetup=$this->prodfinishmcsetup
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_finish_mc_setups.machine_id');
        })
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','asset_acquisitions.company_id');
        })
        ->where([['prod_finish_mc_setups.id','=',$id]])
        ->get([
            'prod_finish_mc_setups.*',
            'asset_quantity_costs.custom_no as machine_no',
            'asset_acquisitions.name as asset_name',
            'companies.name as company_name',
        ])
        ->first();

        $row ['fromData'] = $prodfinishmcsetup;
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
    public function update(ProdFinishMcSetupRequest $request, $id) {
        $prodfinishmcsetup=$this->prodfinishmcsetup->update($id,[
         'machine_id'=>$request->machine_id,
         'remarks'=>$request->remarks,
        ]);
        if($prodfinishmcsetup){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodfinishmcsetup->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getMachine()
    {
        $machine=$this->assetquantitycost
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','asset_acquisitions.company_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_acquisitions.id','=','asset_technical_features.asset_acquisition_id');
        })
        ->when(request('company_id'), function ($q) {
            return $q->where('asset_acquisitions.company_id', '=',request('company_id', 0));
        })
        ->when(request('machine_no'), function ($q) {
            return $q->where('asset_quantity_costs.custom_no', 'like','%'.request('machine_no', 0).'%');
        })
        ->where([['asset_acquisitions.production_area_id','=',30]])
        ->orderBy('asset_acquisitions.id','asc')
        ->orderBy('asset_quantity_costs.id','asc')
        ->get([
            'asset_quantity_costs.*',
            'asset_acquisitions.prod_capacity',
            'asset_acquisitions.name as asset_name',
            'companies.name as company_name',
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
