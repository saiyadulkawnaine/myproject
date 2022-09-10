<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetRecoveryRepository;
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
use App\Http\Requests\FAMS\AssetRecoveryRequest;

class AssetRecoveryController extends Controller {

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

    public function __construct(AssetRecoveryRepository $assetbreakdown,
     AssetAcquisitionRepository $assetacquisition, CompanyRepository $company, LocationRepository $location, UomRepository $uom, SupplierRepository $supplier, ItemAccountRepository $itemaccount, ItemclassRepository $itemclass, ItemcategoryRepository $itemcategory, AssetQuantityCostRepository $assetquantitycost,EmployeeHRRepository $employeehr,DesignationRepository $department,DepartmentRepository $designation) {

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
        ->where([['asset_breakdowns.id','=',request('id',0)]])
       // ->orderBy('asset_breakdowns.id','desc')
        //->take(100)
        ->get([
            'asset_breakdowns.*',
            'asset_quantity_costs.custom_no',
            'asset_quantity_costs.serial_no',
            'asset_acquisitions.type_id',
            'asset_acquisitions.production_area_id',
            'asset_acquisitions.asset_group',
            'asset_acquisitions.brand',
            'asset_acquisitions.origin',
            'asset_acquisitions.purchase_date',
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
            // $assetbreakdown['breakdown_date']=date('Y-m-d',strtotime($row->breakdown_at));
            // $assetbreakdown['breakdown_time']=date('h:i:s A',strtotime($row->breakdown_at));
            $assetbreakdown['function_date']=($row->function_at!==null)?date('Y-m-d',strtotime($row->function_at)):null;
            $assetbreakdown['function_time']=($row->function_at!==null)?date('h:i:s A',strtotime($row->function_at)):null;
            $assetbreakdown['action_taken']=$row->action_taken;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetRecoveryRequest $request) {
        //
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
       ])
       ->first();
       $assetbreakdown->type_id=isset($assetbreakdown->type_id)?$assetType[$assetbreakdown->type_id]:'';
       $assetbreakdown->function_date=($assetbreakdown->function_at)?date('Y-m-d',strtotime($assetbreakdown->function_at)):null;
       $assetbreakdown->function_time=($assetbreakdown->function_at)?date('h:i:s A',strtotime($assetbreakdown->function_at)):null;
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
    public function update(AssetRecoveryRequest $request, $id) {
        if ($request->function_date && $request->function_time) {
            $function_date_time=date('Y-m-d H:i:s',strtotime($request->function_date." ".$request->function_time));
        }else{
            $function_date_time='';
        }

        $recovery=$this->assetbreakdown->find($id);
        if ($recovery->function_at) {
            $assetbreakdown=$this->assetbreakdown->update($id,[
                'function_at'=>$function_date_time,
                'action_taken'=>$request->action_taken,
            ]);
            return response()->json(array('success'=>true,'id'=>$id,'message'=>'Update Successfully'),200);
        }
        else {
            $assetbreakdown=$this->assetbreakdown->update($id,[
                'function_at'=>$function_date_time,
                'action_taken'=>$request->action_taken,
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
                ->where([['asset_breakdowns.id','=',$id]])
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
                ])
                ->map(function($breakdownsms) use($productionarea,$reason,$decision){
                    $breakdownsms->prod_area=$productionarea[$breakdownsms->production_area_id];
                    $breakdownsms->reason=$reason[$breakdownsms->reason_id];
                    $breakdownsms->decision=$decision[$breakdownsms->decision_id];
                    $breakdownsms->function_date=date('d-M-Y',strtotime($breakdownsms->function_at));
                    $breakdownsms->function_time=date('h:i A',strtotime($breakdownsms->function_at));
                    return $breakdownsms;
                })
                ->first();
               // $title ="FamKam ERP (".date('d-M-Y').")\n";
                    $text = 
                    //$title."\n".
                    "M/C Running Message(".date('d-M-Y').")\n".
                    "M/C No: ".$breakdownsms->custom_no.",Prod Area:".$breakdownsms->prod_area."\n".
                    "M/C Name: ".$breakdownsms->asset_name."\n".
                    "Recovery Date: ".$breakdownsms->function_date." ".$breakdownsms->function_time."\n".
                    "Action Taken: ".$breakdownsms->action_taken."\n".
                    "Reason: ".$breakdownsms->reason."\n".
                    "Details: ".$breakdownsms->remarks."\n".
                    "Decision: ".$breakdownsms->decision."\n".
                    "M/C Brand: ".$breakdownsms->brand.";".$breakdownsms->origin."\n".
                    "Capacity: ".$breakdownsms->prod_capacity;
                $sms=Sms::send_sms($text, '8801711563231,8801830573685,8801786651983,8801713241051,8801713043117,8801620913828,8801730595836,01715618727,01718835743,01711228305,01725758333');
                return response()->json(array('success'=>true,'id'=>$id,'sms'=>$sms,'message'=>'Update Successfully'),200);
                
            }
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
        // else{
        //      return response()->json(array('success' => false, 'message' => 'Delete Not Successfull'), 200);
        // }
        
    }

}
