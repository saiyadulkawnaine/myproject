<?php

namespace App\Http\Controllers\Production\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\AOP\ProdFinishAopMcSetupRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Library\Template;
use Illuminate\Support\Carbon;
use App\Http\Requests\Production\AOP\ProdFinishAopMcSetupRequest;

class ProdFinishAopMcSetupController extends Controller {

    private $prodfinishaopmcsetup;
    private $company;
    private $assetquantitycost;
    private $department;
    private $designation;

    public function __construct(
        ProdFinishAopMcSetupRepository $prodfinishaopmcsetup,
        CompanyRepository $company,
        AssetQuantityCostRepository $assetquantitycost,
        DepartmentRepository $department,
        DesignationRepository $designation
     ) {

        $this->prodfinishaopmcsetup = $prodfinishaopmcsetup;
        $this->company = $company;
        $this->assetquantitycost = $assetquantitycost;
        $this->department = $department;
        $this->designation = $designation;

        $this->middleware('auth');

        // $this->middleware('permission:view.prodfinishaopmcsetups',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.prodfinishaopmcsetups', ['only' => ['store']]);
        // $this->middleware('permission:edit.prodfinishaopmcsetups',   ['only' => ['update']]);
        // $this->middleware('permission:delete.prodfinishaopmcsetups', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

       $prodfinishaopmcsetups = array();
       $rows=$this->prodfinishaopmcsetup
       ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_finish_aop_mc_setups.machine_id');
        })
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','asset_acquisitions.company_id');
        })
       ->orderBy('prod_finish_aop_mc_setups.id','desc')
       ->get([
            'prod_finish_aop_mc_setups.*',
            'companies.name as company_name',
            'asset_acquisitions.name as asset_name',
            'asset_quantity_costs.custom_no',
       ]);
       foreach($rows as $row){
           $prodfinishaopmcsetup['id']=$row->id;
           $prodfinishaopmcsetup['custom_no']=$row->custom_no;
           $prodfinishaopmcsetup['company_name']=$row->company_name;
           $prodfinishaopmcsetup['asset_name']=$row->asset_name;
           $prodfinishaopmcsetup['remarks']=$row->remarks;
           array_push($prodfinishaopmcsetups,$prodfinishaopmcsetup);
       }
       echo json_encode($prodfinishaopmcsetups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        return Template::loadView('Production.AOP.ProdFinishAopMcSetup',['company'=>$company,'shiftname'=>$shiftname,'batchfor'=>$batchfor,'department'=>$department,'designation'=>$designation]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdFinishAopMcSetupRequest $request) {
        $prodfinishaopmcsetup = $this->prodfinishaopmcsetup->create([
         'machine_id'=>$request->machine_id,
         'remarks'=>$request->remarks,
        ]);
        if($prodfinishaopmcsetup){
            return response()->json(array('success' => true,'id' =>  $prodfinishaopmcsetup->id,'message' => 'Save Successfully'),200);
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
        $prodfinishaopmcsetup=$this->prodfinishaopmcsetup
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_finish_aop_mc_setups.machine_id');
        })
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','asset_acquisitions.company_id');
        })
        ->where([['prod_finish_aop_mc_setups.id','=',$id]])
       ->get([
         'prod_finish_aop_mc_setups.*',
         'asset_quantity_costs.custom_no',
         'companies.name as company_name',
        ])
        ->first();
        $row ['fromData'] = $prodfinishaopmcsetup;
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
    public function update(ProdFinishAopMcSetupRequest $request, $id) {
        $prodfinishaopmcsetup=$this->prodfinishaopmcsetup->update($id,[
         'machine_id'=>$request->machine_id,
         'remarks'=>$request->remarks,
        ]);
        if($prodfinishaopmcsetup){
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
        if($this->prodfinishaopmcsetup->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getFinishMachine()
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
        ->whereIn('production_area_id',[25,30])
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
