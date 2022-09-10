<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetBreakdownRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Library\Template;
use App\Library\Sms;
use App\Http\Requests\FAMS\AssetBreakdownRequest;

class AssetBreakdownController extends Controller {

    private $assetbreakdown;
    private $assetacquisition;
    private $employeehr;
    private $assetquantitycost;
    private $company;
    private $location;
    private $uom;
    private $supplier;
    private $itemaccount;
    private $itemclass;
    private $itemcategory;

    public function __construct(
        AssetBreakdownRepository $assetbreakdown, 
        AssetAcquisitionRepository $assetacquisition, 
        CompanyRepository $company, 
        LocationRepository $location, 
        UomRepository $uom, 
        SupplierRepository $supplier, 
        ItemAccountRepository $itemaccount, 
        ItemclassRepository $itemclass, 
        ItemcategoryRepository $itemcategory, 
        AssetQuantityCostRepository $assetquantitycost,
        EmployeeHRRepository $employeehr,
        DesignationRepository $designation,
        DepartmentRepository $department
        ) {

        $this->assetbreakdown = $assetbreakdown;
        $this->assetquantitycost = $assetquantitycost;
        $this->employeehr = $employeehr;
        $this->assetacquisition = $assetacquisition;
        $this->company = $company;
        $this->location = $location;
        $this->uom = $uom;
        $this->supplier = $supplier;
        $this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;
        $this->department = $department;
        $this->designation = $designation;


        $this->middleware('auth');
        /* $this->middleware('permission:view.assetbreakdowns',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetbreakdowns', ['only' => ['store']]);
        $this->middleware('permission:edit.assetbreakdowns',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetbreakdowns', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $reason=array_prepend(config('bprs.reason'),'','');
        $decision = array_prepend(config('bprs.decision'),'','');
        $productionarea=array_prepend(config('bprs.productionarea'),'','');
        $assetType = config('bprs.assetType');
        $assetbreakdowns=array();
        $rows=$this->assetbreakdown
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
        })
       ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin(\DB::raw("(
            select 
            asset_quantity_costs.id as asset_quantity_cost_id,
            asset_manpowers.employee_h_r_id,
            employee_h_rs.name as employee_name
            from asset_manpowers
            join asset_quantity_costs on asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id
            join employee_h_rs on employee_h_rs.id=asset_manpowers.employee_h_r_id
            where asset_manpowers.id=(select max(asset_manpowers.id) from asset_manpowers where asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id)
            group by 
            asset_quantity_costs.id,
            asset_manpowers.employee_h_r_id,
            employee_h_rs.name
            ) cumulatives"), "cumulatives.asset_quantity_cost_id", "=", "asset_quantity_costs.id")
        ->orderBy('asset_breakdowns.id','desc')
        ->take(100)
        ->get([
            'asset_breakdowns.*',
            'asset_quantity_costs.custom_no',
            'asset_quantity_costs.serial_no',
            'asset_acquisitions.name as asset_name',
            'asset_acquisitions.type_id',
            'asset_acquisitions.production_area_id',
            'asset_acquisitions.asset_group',
            'asset_acquisitions.brand',
            'asset_acquisitions.origin',
            'asset_acquisitions.purchase_date',
	    'asset_acquisitions.prod_capacity',
            'cumulatives.employee_name',
        ]);
        foreach($rows as $row){
            $assetbreakdown['id']=$row->id;
            $assetbreakdown['custom_no']=$row->custom_no;
            $assetbreakdown['employee_name']=$row->employee_name;
            $assetbreakdown['remarks']=$row->remarks;
            $assetbreakdown['reason_id']=isset($reason[$row->reason_id])?$reason[$row->reason_id]:'';
            $assetbreakdown['decision_id']=isset($decision[$row->decision_id])?$decision[$row->decision_id]:'';
            $assetbreakdown['name']=$row->name;
            $assetbreakdown['breakdown_date']=date('Y-m-d',strtotime($row->breakdown_at));
            $assetbreakdown['breakdown_time']=date('h:i:s A',strtotime($row->breakdown_at));
            $assetbreakdown['function_date']=($row->function_at!==null)?date('Y-m-d',strtotime($row->function_at)):null;
            $assetbreakdown['function_time']=($row->function_at!==null)?date('h:i:s A',strtotime($row->function_at)):null;
            $assetbreakdown['asset_name']=$row->asset_name.", ".$row->asset_group.", ".$row->brand.", ".$row->origin;
            $assetbreakdown['prod_capacity']=$row->prod_capacity;
            $assetbreakdown['type_id']=isset($assetType[$row->type_id])?$assetType[$row->type_id]:'';
            array_push($assetbreakdowns, $assetbreakdown);
        }
        echo json_encode($assetbreakdowns);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $reason=array_prepend(config('bprs.reason'),'','');
        $decision = array_prepend(config('bprs.decision'),'-Select-','');
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
        return Template::loadView('FAMS.AssetBreakdown',['reason'=>$reason,'decision'=>$decision,'designation'=>$designation,'department'=>$department,'company'=>$company,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetBreakdownRequest $request) {
        
        if($request->breakdown_date && $request->breakdown_time){
            $breakdown_date_time=date('Y-m-d H:i:s',strtotime($request->breakdown_date." ".$request->breakdown_time));
        }else{
            $breakdown_date_time=''; 
        }

        if ($request->estimated_recovery_date && $request->estimated_recovery_time) {
            $estimated_date_time=date('Y-m-d H:i:s',strtotime($request->estimated_recovery_date." ".$request->estimated_recovery_time));
        }else{
            $estimated_date_time='';
        }

        $machine=$this->assetbreakdown
        ->where([['asset_quantity_cost_id','=',$request->asset_quantity_cost_id]])
        ->whereNull('function_at')
        ->whereNull('deleted_at')
        ->get();
        if($machine->first()){
           return response()->json(array('success'=>false,'message'=>'Machine Already in breakdown, Please functioning it first'),200);
        }

        $assetbreakdown=$this->assetbreakdown->create([
            'breakdown_at'=>$breakdown_date_time,
            'estimated_recovery_at'=>$estimated_date_time,
            'reason_id'=>$request->reason_id,
            'decision_id'=>$request->decision_id,
            'asset_quantity_cost_id'=>$request->asset_quantity_cost_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
            'remarks'=>$request->remarks,
        ]);

        if($assetbreakdown){
            $productionarea=array_prepend(config('bprs.productionarea'),'','');
            $reason=array_prepend(config('bprs.reason'),'','');
            $decision = array_prepend(config('bprs.decision'),'','');
            $breakdownsms=$this->assetbreakdown
            ->join('asset_quantity_costs',function($join){
                $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
            })
            ->join('asset_acquisitions',function($join){
                $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
            })
            ->leftJoin('employee_h_rs as maintenance_by',function($join){
                $join->on('maintenance_by.id','=','asset_breakdowns.employee_h_r_id');
            })
            ->where([['asset_breakdowns.id','=',$assetbreakdown->id]])
            ->get([
                'asset_breakdowns.*',
                'asset_quantity_costs.custom_no',
                'asset_quantity_costs.serial_no',
                'asset_acquisitions.name as asset_name',
                'maintenance_by.name as maintenance_name',
                'asset_acquisitions.type_id',
                'asset_acquisitions.production_area_id',
                'asset_acquisitions.asset_group',
                'asset_acquisitions.brand',
                'asset_acquisitions.origin',
                'asset_acquisitions.purchase_date',
                'asset_acquisitions.prod_capacity',
            ])
            ->map(function($breakdownsms) use($productionarea,$reason,$decision){
                $breakdownsms->prod_area=$productionarea[$breakdownsms->production_area_id];
                $breakdownsms->reason=$reason[$breakdownsms->reason_id];
                $breakdownsms->decision=$decision[$breakdownsms->decision_id];
                $breakdownsms->breakdown_date=date('d-M-Y',strtotime($breakdownsms->breakdown_at));
                $breakdownsms->breakdown_time=date('h:i A',strtotime($breakdownsms->breakdown_at));
                $breakdownsms->estimated_recovery_date=date('d-M-Y',strtotime($breakdownsms->estimated_recovery_at));
                $breakdownsms->estimated_recovery_time=date('h:i A',strtotime($breakdownsms->estimated_recovery_at));
                return $breakdownsms;
            })
            ->first();
          //  $title ="FamKam ERP (".date('d-M-Y').")\n";
            $text =
                "M/C Idle Message\n".
                "Date:".date('d-M-Y')."\n".
                "M/C No: ".$breakdownsms->custom_no.",Prod Area:".$breakdownsms->prod_area."\n".
                "M/C Name: ".$breakdownsms->asset_name."\n".
                "Breakdown Date: ".$breakdownsms->breakdown_date." ".$breakdownsms->breakdown_time."\n".
                "Estimated Recovery Date: ".$breakdownsms->estimated_recovery_date." ".$breakdownsms->estimated_recovery_time."\n".
                "Reason: ".$breakdownsms->reason."\n".
                "Details: ".$breakdownsms->remarks."\n".
                "Decision: ".$breakdownsms->decision."\n".
                "Maintenance By: ".$breakdownsms->maintenance_name."\n".
                "M/C Brand: ".$breakdownsms->brand.";".$breakdownsms->origin."\n".
                "Capacity: ".$breakdownsms->prod_capacity;
            $sms=Sms::send_sms($text, '8801711563231,8801830573685,8801786651983,8801713241051,8801713043117,8801620913828,8801730595836,01715618727,01718835743,01711228305,01725758333');
        
            return response()->json(array('success'=>true,'id'=>$assetbreakdown->id,'sms'=>$sms ,'message'=>'Save Successfully'),200);
        }
    }

    /**
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
       $assetType = array_prepend(config('bprs.assetType'),'-Select-','');
       $assetbreakdown=$this->assetbreakdown
       ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
        })
       ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('asset_manpowers',function($join){
            $join->on('asset_acquisitions.id','=','asset_manpowers.asset_acquisition_id');
            $join->on('asset_quantity_costs.id','=','asset_manpowers.asset_quantity_cost_id');
        })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','asset_manpowers.employee_h_r_id');
        })
        ->leftJoin('employee_h_rs as maintenance_by',function($join){
            $join->on('maintenance_by.id','=','asset_breakdowns.employee_h_r_id');
        })
       ->where([['asset_breakdowns.id','=',$id]])
       ->get([
           'asset_breakdowns.*',
           'asset_quantity_costs.custom_no',
            'asset_quantity_costs.serial_no',
            'asset_acquisitions.type_id',
            'asset_acquisitions.name as asset_name',
            'asset_acquisitions.production_area_id',
            'asset_acquisitions.asset_group',
            'asset_acquisitions.brand',
            'asset_acquisitions.origin',
            'asset_acquisitions.purchase_date',
            'asset_acquisitions.prod_capacity',
            'employee_h_rs.name as employee_name',
            'maintenance_by.name',
       ])
       ->first();
       $assetbreakdown->type_id=isset($assetbreakdown->type_id)?$assetType[$assetbreakdown->type_id]:'';
       $assetbreakdown->breakdown_date=date('Y-m-d',strtotime($assetbreakdown->breakdown_at));
       $assetbreakdown->breakdown_time=date('H:i:s A',strtotime($assetbreakdown->breakdown_at));
       $assetbreakdown->function_date=($assetbreakdown->function_at!==null)?date('Y-m-d',strtotime($assetbreakdown->function_at)):null;
       $assetbreakdown->function_time=($assetbreakdown->function_at!==null)?date('H:i:s A',strtotime($assetbreakdown->function_at)):null;
       $assetbreakdown->estimated_recovery_date=($assetbreakdown->estimated_recovery_at)?date('Y-m-d',strtotime($assetbreakdown->estimated_recovery_at)):null;
       $assetbreakdown->estimated_recovery_time=($assetbreakdown->estimated_recovery_at)?date('H:i:s A',strtotime($assetbreakdown->estimated_recovery_at)):null;
       $row['fromData']=$assetbreakdown;
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
    public function update(AssetBreakdownRequest $request, $id) {
        
        if ($request->breakdown_date && $request->breakdown_time) {
            $breakdown_date_time=date('Y-m-d H:i:s',strtotime($request->breakdown_date." ".$request->breakdown_time));
        }else{
            $breakdown_date_time='';
        }
        if ($request->estimated_recovery_date && $request->estimated_recovery_time) {
            $estimated_date_time=date('Y-m-d H:i:s',strtotime($request->estimated_recovery_date." ".$request->estimated_recovery_time));
        }else{
           $estimated_date_time='';
        }

        

        $assetbreakdown=$this->assetbreakdown->update($id,[
            'breakdown_at'=>$breakdown_date_time,
            'estimated_recovery_at'=>$estimated_date_time,
            'reason_id'=>$request->reason_id,
            'decision_id'=>$request->decision_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
            'remarks'=>$request->remarks,
        ]);
        if($assetbreakdown){
            return response()->json(array('success'=>true,'id'=>$id,/* 'sms'=>$sms, */'message'=>'Update Successfully'),200);
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->assetbreakdown->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
        
    }

    public function getAssetDtls(){
        $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
        $depMethod=array_prepend(config('bprs.depMethod'),'-Select-','');
        $assetType = config('bprs.assetType');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $assetquantitycost = $this->assetquantitycost
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('asset_disposals',function($join){
            $join->on('asset_quantity_costs.id','=','asset_disposals.asset_quantity_cost_id');
        })
        ->leftJoin(\DB::raw("(
            select 
            asset_quantity_costs.id as asset_quantity_cost_id,
            asset_manpowers.employee_h_r_id,
            employee_h_rs.name as employee_name
            from asset_manpowers
            join asset_quantity_costs on asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id
            join employee_h_rs on employee_h_rs.id=asset_manpowers.employee_h_r_id
            where asset_manpowers.id=(select max(asset_manpowers.id) from asset_manpowers where asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id)
            group by 
            asset_quantity_costs.id,
            asset_manpowers.employee_h_r_id,
            employee_h_rs.name
            ) cumulatives"), "cumulatives.asset_quantity_cost_id", "=", "asset_quantity_costs.id")

        // ->join('asset_manpowers',function($join){
        //     //$join->on('asset_manpowers.asset_acquisition_id','=','asset_acquisitions.id');
        //     $join->on('asset_manpowers.asset_quantity_cost_id','=','asset_quantity_costs.id');
        // })
        // ->join('employee_h_rs',function($join){
        //     $join->on('asset_manpowers.employee_h_r_id','=','employee_h_rs.id');
        // })
        ->when(request('asset_no'), function ($q) {
            return $q->where('asset_quantity_costs.asset_no', '=',request('asset_no', 0));
         })
        ->when(request('custom_no'), function ($q) {
            return $q->where('asset_quantity_costs.custom_no', '=',request('custom_no', 0));
        })
        ->when(request('asset_name'), function ($q) {
            return $q->where('asset_acquisitions.name','like', '%'.request('asset_name', 0).'%');
        })
        ->whereNull('asset_disposals.asset_quantity_cost_id')
        ->get([
            'asset_quantity_costs.id',
            'asset_quantity_costs.serial_no',
            'asset_quantity_costs.custom_no',
            'asset_quantity_costs.asset_no',
            'asset_acquisitions.name as asset_name',
            'asset_acquisitions.company_id',
            'asset_acquisitions.location_id',
            'asset_acquisitions.supplier_id',
            'asset_acquisitions.iregular_supplier',
            'asset_acquisitions.type_id',
            'asset_acquisitions.production_area_id',
            'asset_acquisitions.asset_group',
            'asset_acquisitions.brand',
            'asset_acquisitions.origin',
            'asset_acquisitions.purchase_date',
            'asset_acquisitions.prod_capacity',
            'cumulatives.employee_name',
        ])
        ->map(function($assetquantitycost) use($assetType, $productionarea, $supplier, $company, $location) {
            $assetquantitycost->type_id =isset($assetType[$assetquantitycost->type_id])?$assetType[$assetquantitycost->type_id]:'';
            $assetquantitycost->production_area_id=isset($productionarea[$assetquantitycost->production_area_id])?$productionarea[$assetquantitycost->production_area_id]:'';
            $assetquantitycost->supplier_id=isset($supplier[$assetquantitycost->supplier_id])?$supplier[$assetquantitycost->supplier_id]:'';
            $assetquantitycost->company_id=isset($company[$assetquantitycost->company_id])?$company[$assetquantitycost->company_id]:'';
            $assetquantitycost->location_id=isset($location[$assetquantitycost->location_id])?$location[$assetquantitycost->location_id]:'';
            return $assetquantitycost;
        });

        echo json_encode($assetquantitycost);
    }

    public function getBreakdownList(){
        $reason=array_prepend(config('bprs.reason'),'-Select-','');
        $decision = array_prepend(config('bprs.decision'),'-Select-','');
        $assetType = config('bprs.assetType');
        $rows=$this->assetbreakdown
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
        })
       ->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin(\DB::raw("(
            select 
            asset_quantity_costs.id as asset_quantity_cost_id,
            asset_manpowers.employee_h_r_id,
            employee_h_rs.name as employee_name
            from asset_manpowers
            join asset_quantity_costs on asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id
            join employee_h_rs on employee_h_rs.id=asset_manpowers.employee_h_r_id
            where asset_manpowers.id=(select max(asset_manpowers.id) from asset_manpowers where asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id)
            group by 
            asset_quantity_costs.id,
            asset_manpowers.employee_h_r_id,
            employee_h_rs.name
            ) cumulatives"), "cumulatives.asset_quantity_cost_id", "=", "asset_quantity_costs.id")
        ->when(request('from_date'), function ($q) {
            return $q->whereDate('asset_breakdowns.breakdown_at', '>=', request('from_date', 0));
        })
        ->when(request('to_date'), function ($q) {
            return $q->whereDate('asset_breakdowns.breakdown_at', '<=', request('to_date', 0));
        })
        ->orderBy('asset_breakdowns.id','desc')
        ->get([
            'asset_breakdowns.*',
            'asset_quantity_costs.custom_no',
            'asset_quantity_costs.serial_no',
            'asset_acquisitions.name as asset_name',
            'asset_acquisitions.type_id',
            'asset_acquisitions.production_area_id',
            'asset_acquisitions.asset_group',
            'asset_acquisitions.brand',
            'asset_acquisitions.origin',
            'asset_acquisitions.purchase_date',
            'asset_acquisitions.prod_capacity',
            'cumulatives.employee_name',
        ])
        ->map(function($rows) use($reason,$decision,$assetType){
            $rows->asset_name=$rows->asset_name.", ".$rows->asset_group.", ".$rows->brand.", ".$rows->origin;
            $rows->reason_id=isset($reason[$rows->reason_id])?$reason[$rows->reason_id]:'';
            $rows->decision_id=isset($decision[$rows->decision_id])?$decision[$rows->decision_id]:'';
            $rows->breakdown_date=date('Y-m-d',strtotime($rows->breakdown_at));
            $rows->breakdown_time=date('h:i:s A',strtotime($rows->breakdown_at));
            $rows->function_date=($rows->function_at)?date('Y-m-d',strtotime($rows->function_at)):null;
            $rows->function_time=($rows->function_at)?date('h:i:s A',strtotime($rows->function_at)):null;
            $rows->type_id=isset($assetType[$rows->type_id])?$assetType[$rows->type_id]:'';
            return $rows;
        });

        echo json_encode($rows);
    }

    public function getEmployeeHr(){
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
           $employeehr['national_id']=$row->national_id; 
		   $employeehr['email']=$row->email;
		   $employeehr['contact']=$row->contact;
		   $employeehr['address']=$row->address;
           array_push($employeehrs,$employeehr);
        }
        echo json_encode($employeehrs);
    }

}
