<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqAssetBreakdownRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;
use App\Repositories\Contracts\FAMS\AssetBreakdownRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvPurReqAssetBreakdownRequest;

class InvPurReqAssetBreakdownController extends Controller {

    private $invpurreq;
    private $invpurreqassetbreakdown;
    private $assetbreakdown;

    public function __construct(
        InvPurReqAssetBreakdownRepository $invpurreq,
        InvPurReqAssetBreakdownRepository $invpurreqassetbreakdown,
        AssetBreakdownRepository $assetbreakdown
    ) {
        $this->invpurreq = $invpurreq;
        $this->invpurreqassetbreakdown = $invpurreqassetbreakdown;
        $this->assetbreakdown = $assetbreakdown;

        $this->middleware('auth');

        // $this->middleware('permission:view.invpurreqassetbreakdowns',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.invpurreqassetbreakdowns', ['only' => ['store']]);
        // $this->middleware('permission:edit.invpurreqassetbreakdowns',   ['only' => ['update']]);
        // $this->middleware('permission:delete.invpurreqassetbreakdowns', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $reason=array_prepend(config('bprs.reason'),'-Select-','');
        $decision = array_prepend(config('bprs.decision'),'-Select-','');
        $assetType = config('bprs.assetType');

        $invpurreqassetbreakdowns=array();
        $rows=$this->invpurreqassetbreakdown
        ->leftJoin('asset_breakdowns',function($join){
            $join->on('asset_breakdowns.id','=','inv_pur_req_asset_breakdowns.asset_breakdown_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
        })
        ->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->where([['inv_pur_req_id','=',request('inv_pur_req_id',0)]])
        ->orderBy('inv_pur_req_asset_breakdowns.id','desc')
        ->get([
            'inv_pur_req_asset_breakdowns.*',
            'asset_breakdowns.breakdown_at',
            'asset_breakdowns.function_at',
            'asset_breakdowns.reason_id',
            'asset_breakdowns.decision_id',
            'asset_breakdowns.action_taken',
            'asset_quantity_costs.custom_no',
            'asset_acquisitions.name as asset_name',
        ]);

        foreach($rows as $row){
            $invpurreqassetbreakdown['id']=$row->id;
            $invpurreqassetbreakdown['asset_breakdown_id']=$row->asset_breakdown_id;
            $invpurreqassetbreakdown['custom_no']=$row->custom_no;
            $invpurreqassetbreakdown['asset_name']=$row->asset_name;
            $invpurreqassetbreakdown['remarks']=$row->remarks;
            $invpurreqassetbreakdown['action_taken']=$row->action_taken;
            $invpurreqassetbreakdown['reason_id']=isset($reason[$row->reason_id])?$reason[$row->reason_id]:'';
            $invpurreqassetbreakdown['decision_id']=isset($decision[$row->decision_id])?$decision[$row->decision_id]:'';
            $invpurreqassetbreakdown['breakdown_date']=date('Y-m-d',strtotime($row->breakdown_at));
            $invpurreqassetbreakdown['breakdown_time']=date('h:i:s A',strtotime($row->breakdown_at));
            $invpurreqassetbreakdown['function_date']=($row->function_at)?date('Y-m-d',strtotime($row->function_at)):null;
            $invpurreqassetbreakdown['function_time']=($row->function_at)?date('h:i:s A',strtotime($row->function_at)):null;
            array_push($invpurreqassetbreakdowns,$invpurreqassetbreakdown);
        }
        echo json_encode($invpurreqassetbreakdowns);
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
    public function store(InvPurReqAssetBreakdownRequest $request) {

        $invpurreqassetbreakdown = $this->invpurreqassetbreakdown->create([
            'inv_pur_req_id'=>$request->inv_pur_req_id,
            'asset_breakdown_id'=>$request->asset_breakdown_id,
            ]);
		if($invpurreqassetbreakdown){
			return response()->json(array('success' => true,'id' =>  $invpurreqassetbreakdown->id,'message' => 'Save Successfully'),200);
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
        $reason=array_prepend(config('bprs.reason'),'-Select-','');
        $decision = array_prepend(config('bprs.decision'),'-Select-','');
        $invpurreqassetbreakdown = $this->invpurreqassetbreakdown
        ->leftJoin('asset_breakdowns',function($join){
            $join->on('asset_breakdowns.id','=','inv_pur_req_asset_breakdowns.asset_breakdown_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
        })
        ->where([['inv_pur_req_asset_breakdowns.id','=',$id]])
        ->get([
            'inv_pur_req_asset_breakdowns.*',
            'asset_breakdowns.breakdown_at',
            'asset_breakdowns.function_at',
            'asset_breakdowns.reason_id',
            'asset_breakdowns.decision_id',
            'asset_breakdowns.action_taken',
            'asset_quantity_costs.custom_no',
        ])
        ->map(function($rows) use($reason,$decision){
            $rows->reason_id=isset($reason[$rows->reason_id])?$reason[$rows->reason_id]:'';
            $rows->decision_id=isset($decision[$rows->decision_id])?$decision[$rows->decision_id]:'';
            $rows->breakdown_date=date('Y-m-d',strtotime($rows->breakdown_at));
            $rows->breakdown_time=date('h:i:s A',strtotime($rows->breakdown_at));
            $rows->function_date=($rows->function_at)?date('Y-m-d',strtotime($rows->function_at)):null;
            $rows->function_time=($rows->function_at)?date('h:i:s A',strtotime($rows->function_at)):null;
            return $rows;
        })
        ->first();
        $row ['fromData'] = $invpurreqassetbreakdown;
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
    public function update(InvPurReqAssetBreakdownRequest $request, $id) {
        $invpurreqassetbreakdown=$this->invpurreqassetbreakdown->update($id,[
            //'inv_pur_req_id'=>$request->inv_pur_req_id,
            'asset_breakdown_id'=>$request->asset_breakdown_id,
        ]);
        
		if($invpurreqassetbreakdown){
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
        if($this->invpurreqassetbreakdown->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        } 
    }

    public function getAssetBreakdown(){
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
        ->when(request('custom_no'), function ($q) {
            return $q->where('asset_quantity_costs.custom_no', '=',request('custom_no', 0));
        })
        ->when(request('asset_name'), function ($q) {
            return $q->where('asset_acquisitions.name','like', '%'.request('asset_name', 0).'%');
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
}
