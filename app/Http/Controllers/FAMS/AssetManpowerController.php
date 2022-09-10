<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\FAMS\AssetManpowerRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\FAMS\AssetManpowerRequest;
 

class AssetManpowerController extends Controller {

    private $assetacquisition;
    private $assetmanpower;
    private $employeehr;
    private $assetquantitycost;
    private $department;
    private $designation;
    private $company;
    private $assetdisposal;


    public function __construct(
        AssetAcquisitionRepository $assetacquisition,
        AssetDisposalRepository $assetdisposal,
        AssetManpowerRepository $assetmanpower,
        EmployeeHRRepository $employeehr,
        AssetQuantityCostRepository $assetquantitycost,
        DesignationRepository $designation,
        DepartmentRepository $department,
        CompanyRepository $company
        ) {
        $this->assetacquisition = $assetacquisition;
        $this->assetdisposal = $assetdisposal;
        $this->assetmanpower = $assetmanpower;
        $this->employeehr = $employeehr;
        $this->assetquantitycost = $assetquantitycost;
        $this->department = $department;
        $this->designation = $designation;
        $this->company = $company;

        $this->middleware('auth');
        /* $this->middleware('permission:view.assetmanpowers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetmanpowers', ['only' => ['store']]);
        $this->middleware('permission:edit.assetmanpowers',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetmanpowers', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $assetmanpowers=array();
        $rows=$this->assetmanpower
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_manpowers.asset_quantity_cost_id');
        })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','asset_manpowers.employee_h_r_id');
        })
        ->where([['asset_manpowers.asset_acquisition_id','=',request('asset_acquisition_id',0)]])
        ->orderBy('asset_manpowers.id','desc')
        ->get([
            'asset_manpowers.*',
            'asset_quantity_costs.custom_no',
            'employee_h_rs.name'
        ]);
        foreach($rows as $row){
            $assetmanpower['id']=$row->id;
            $assetmanpower['employee_h_r_id']=$row->name;
            $assetmanpower['custom_no']=$row->custom_no; 
            $assetmanpower['tenure_start']=date('d-M-Y',strtotime($row->tenure_start));
            $assetmanpower['tenure_end']=date('d-M-Y',strtotime($row->tenure_end));
            array_push($assetmanpowers, $assetmanpower);
        }
        
        echo json_encode($assetmanpowers);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetManpowerRequest $request) {
        $assetdisposal=$this->assetdisposal
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_disposals.asset_quantity_cost_id');
        })
        ->where([['asset_quantity_costs.id','=',$request->asset_quantity_cost_id]])
        ->get(['asset_disposals.id'])
        ->first();

        if ($assetdisposal) {
            return response()->json(array('success'=>false,'message'=>'Save Not Successful. Asset Disposal Entry Found'),200);
        }

        $assetmanpower=$this->assetmanpower->create([
            'asset_acquisition_id'=>$request->asset_acquisition_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
            'asset_quantity_cost_id'=>$request->asset_quantity_cost_id,
            'tenure_start'=>$request->tenure_start,
            'tenure_end'=>$request->tenure_end,
            
            
            ]);
        if($assetmanpower){
            return response()->json(array('success'=>true,'id'=>$assetmanpower->id,'message'=>'Save Successfully'),200);
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
       $assetmanpower=$this->assetmanpower
        /*  ->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_manpowers.asset_acquisition_id');
        }) */
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_manpowers.asset_quantity_cost_id');
        })
        ->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_manpowers.asset_acquisition_id');
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','asset_manpowers.employee_h_r_id');
        })
        ->where([['asset_manpowers.id','=',$id]])
        ->get([
           'asset_manpowers.*',
           'employee_h_rs.name',
           'asset_quantity_costs.custom_no'
       ])
       ->first();
       $row['fromData']=$assetmanpower;
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
    

    public function update(AssetManpowerRequest $request, $id) {
        $assetdisposal=$this->assetdisposal
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_disposals.asset_quantity_cost_id');
        })
        ->where([['asset_quantity_costs.id','=',$request->asset_quantity_cost_id]])
        ->get(['asset_disposals.id'])
        ->first();

        if ($assetdisposal) {
            return response()->json(array('success'=>false,'message'=>'Update Not Successful. Asset Disposal Entry Found'),200);
        }

        $assetmanpower=$this->assetmanpower->update($id,
        [
            'asset_acquisition_id'=>$request->asset_acquisition_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
            'asset_quantity_cost_id'=>$request->asset_quantity_cost_id,
            'tenure_start'=>$request->tenure_start,
            'tenure_end'=>$request->tenure_end,
            
            
            ]);
        if($assetmanpower){
            return response()->json(array('success'=>true,'id'=>$id,'message'=>'Update Successfully'),200);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->assetmanpower->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}       
    }

    public function getEmployee(){
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $yesno = config('bprs.yesno');
        $employeehrs=array();
        $rows=$this->employeehr
        ->orderBy('employee_h_rs.id','desc')
        ->when(request('designation_id'), function ($q) {
            return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
        })
        ->when(request('department_id'), function ($q) {
            return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
        })
        ->when(request('company_id'), function ($q) {
            return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
        })
        ->get(['employee_h_rs.*']);
        foreach($rows as $row){
           $employeehr['id']=$row->id; 
           $employeehr['name']=$row->name; 
           $employeehr['code']=$row->code;
           $employeehr['company_id']=$company[$row->company_id];
           $employeehr['designation_id']=isset($designation[$row->designation_id])?$designation[$row->designation_id]:''; 
           $employeehr['department_id']=isset($department[$row->department_id])?$department[$row->department_id]:''; 
           $employeehr['date_of_join']=($row->date_of_join !== null)?date("Y-m-d",strtotime($row->date_of_join)):null; 
           $employeehr['national_id']=$row->national_id; 
           //$employeehr['address']=$row->address;
           //$employeehr['yesno']=$yesno[$row->is_advanced_applicable];
           $employeehr['last_education']=$row->last_education;
           $employeehr['experience']=$row->experience;
		   $employeehr['email']=$row->email;
		   $employeehr['contact']=$row->contact;
           array_push($employeehrs,$employeehr);
        }
        echo json_encode($employeehrs);
    }

    public function getMachine()
    {
        $machineId=request('acquisitionid',0);
        $machine=$this->assetquantitycost
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_acquisitions.id','=','asset_technical_features.asset_acquisition_id');
        })
        ->leftJoin('asset_disposals',function($join){
            $join->on('asset_quantity_costs.id','=','asset_disposals.asset_quantity_cost_id');
        })
        ->when(request('dia_width'), function ($q) {
            return $q->where('asset_technical_features.dia_width', '=>',request('dia_width', 0));
        })
        ->when(request('no_of_feeder'), function ($q) {
            return $q->where('asset_technical_features.no_of_feeder', '<=',request('no_of_feeder', 0));
        })
        ->whereNull('asset_disposals.asset_quantity_cost_id')
        ->where([['asset_acquisitions.id','=',$machineId]])
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
