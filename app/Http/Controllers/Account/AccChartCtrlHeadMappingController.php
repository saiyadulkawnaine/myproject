<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccChartSubGroupRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadMappingRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccChartCtrlHeadMappingRequest;

class AccChartCtrlHeadMappingController extends Controller {

    private $accchartctrlheadmapping;
    private $accchartctrlhead;
    private $accchartsubgroup;
    private $currency;
    private $buyer;
    private $supplier;

    public function __construct(AccChartCtrlHeadMappingRepository $accchartctrlheadmapping,AccChartCtrlHeadRepository $accchartctrlhead,AccChartSubGroupRepository $accchartsubgroup,CurrencyRepository $currency,BuyerRepository $buyer,SupplierRepository $supplier) {
        $this->accchartctrlheadmapping = $accchartctrlheadmapping;
        $this->accchartctrlhead = $accchartctrlhead;
        $this->accchartsubgroup = $accchartsubgroup;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->supplier = $supplier;


        $this->middleware('auth');
        $this->middleware('permission:view.accchartctrlheadmappings',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.accchartctrlheadmappings', ['only' => ['store']]);
        $this->middleware('permission:edit.accchartctrlheadmappings',   ['only' => ['update']]);
        $this->middleware('permission:delete.accchartctrlheadmappings', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
     
        $accchartctrlheads=array();
        $rows=$this->accchartctrlheadmapping
        ->join('acc_chart_ctrl_heads',function($join){
            $join->on('acc_chart_ctrl_heads.id', '=', 'acc_chart_ctrl_head_mappings.acc_chart_ctrl_head_id');
        })
        ->join('acc_chart_ctrl_heads as accumulated_depreciation',function($join){
            $join->on('accumulated_depreciation.id', '=', 'acc_chart_ctrl_head_mappings.acc_acumulate_ctrl_head_id');
        })
        ->get([
            'acc_chart_ctrl_head_mappings.*',
            'acc_chart_ctrl_heads.name as asset_head_name',
            'acc_chart_ctrl_heads.code as asset_head_code',
           'accumulated_depreciation.name as accumulate_head_name',
           'accumulated_depreciation.code as accumulate_head_code',
        ]);
        foreach ($rows as $row) {
        $accchartctrlhead['id']=$row->id;
          $accchartctrlhead['asset_head_code']=$row->asset_head_code;
          $accchartctrlhead['asset_head_name']=$row->asset_head_name;
           $accchartctrlhead['accumulate_head_name']=$row->accumulate_head_name;
           $accchartctrlhead['accumulate_head_code']=$row->accumulate_head_code;
          array_push($accchartctrlheads,$accchartctrlhead);
        }
        echo json_encode($accchartctrlheads);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

       // $ctrlHead=array_prepend(array_pluck($this->accchartctrlhead->where([['ctrlhead_type_id','=',0]])->get(),'name','id'),'','');
        //$ctrlHead=array_prepend(array_pluck($this->accchartctrlhead->where([['ctrlhead_type_id','=',0]])->get(),'name','id'),'','');
		return Template::loadView('Account.AccChartCtrlHeadMapping');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccChartCtrlHeadMappingRequest $request) {
		$accchartctrlhead=$this->accchartctrlheadmapping->create([
            'acc_chart_ctrl_head_id' => $request->acc_chart_ctrl_head_id,
            'acc_acumulate_ctrl_head_id' => $request->acc_acumulate_ctrl_head_id,
        ]);
		if($accchartctrlhead){
			return response()->json(array('success' => true,'id' =>  $accchartctrlhead->id,'message' => 'Save Successfully'),200);
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
       $accchartctrlhead = $this->accchartctrlheadmapping
       ->join('acc_chart_ctrl_heads',function($join){
            $join->on('acc_chart_ctrl_heads.id', '=', 'acc_chart_ctrl_head_mappings.acc_chart_ctrl_head_id');
        })
        ->join('acc_chart_ctrl_heads as accumulated_depreciation',function($join){
            $join->on('accumulated_depreciation.id', '=', 'acc_chart_ctrl_head_mappings.acc_acumulate_ctrl_head_id');
        })
       ->where('acc_chart_ctrl_head_mappings.id','=',$id)
       ->get([
           'acc_chart_ctrl_head_mappings.*',
           'acc_chart_ctrl_heads.name as asset_head_name',
           'accumulated_depreciation.name as accumulate_head_name'
       ])
       ->first();
	   $row ['fromData'] = $accchartctrlhead;
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
    public function update(AccChartCtrlHeadMappingRequest $request, $id) {
        $accchartctrlhead=$this->accchartctrlheadmapping->update($id,[
            'acc_chart_ctrl_head_id' => $request->acc_chart_ctrl_head_id,
            'acc_acumulate_ctrl_head_id' => $request->acc_acumulate_ctrl_head_id,
        ]);
		if($accchartctrlhead){
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
        if($this->accchartctrlheadmapping->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
        else{
             return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
        }
    }

    public function getAssetHead(){
    	$accchartsubgroup=array_prepend(array_pluck($this->accchartsubgroup->get(),'name','id'),'-Select-','');
		$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
		$ctrlHead=array_prepend(array_pluck($this->accchartctrlhead->get(),'name','id'),'-Select-','0');
		$ctrlheadtype=array_prepend(config('bprs.ctrlheadtype'),'-Select-','0');
		$statementType=array_prepend(config('bprs.statementType'),'-Select-','0');
		$controlname=array_prepend(config('bprs.controlname'),'-Select-','0');
		$otherType=array_prepend(config('bprs.otherType'),'-Select-','0');
		$normalbalance=array_prepend(config('bprs.normalbalance'),'-Select-','0');
		$accchartgroup=array_prepend(config('bprs.accchartgroup'),'-Select-','0');
		$status=array_prepend(config('bprs.status'),'-Select-','');
		$accchartctrlheads=array();
		$rows=$this->accchartctrlhead
		->join('acc_chart_sub_groups',function($join){
			$join->on('acc_chart_sub_groups.id','=','acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->orderBy('code','asc')
		->where([['acc_chart_ctrl_heads.ctrlhead_type_id','=',1]])
		->where([['acc_chart_sub_groups.acc_chart_group_id','=',10]])
		->get([
			'acc_chart_ctrl_heads.*',
			'acc_chart_sub_groups.name as sub_group_name',
			'acc_chart_sub_groups.acc_chart_group_id'
		]);
		foreach ($rows as $row) {
		$accchartctrlhead['id']=$row->id;
		$accchartctrlhead['asset_head_name']=$row->name;
		$accchartctrlhead['code']=$row->code;
		$accchartctrlhead['sort_id']=$row->sort_id;
		$accchartctrlhead['root_id']=isset($ctrlHead[$row->root_id])?$ctrlHead[$row->root_id]:0;
		$accchartctrlhead['sub_group_name']=$row->sub_group_name;
		$accchartctrlhead['accchartgroup']=isset($accchartgroup[$row->acc_chart_group_id])?$accchartgroup[$row->acc_chart_group_id]:0;

		$accchartctrlhead['ctrlhead_type_id']=isset($ctrlheadtype[$row->ctrlhead_type_id])?$ctrlheadtype[$row->ctrlhead_type_id]:'';
		$accchartctrlhead['statement_type_id']=isset($statementType[$row->statement_type_id])?$statementType[$row->statement_type_id]:'';
		$accchartctrlhead['retained_earning_account_id']=$row->retained_earning_account_id;
		$accchartctrlhead['control_name_id']=isset($controlname[$row->control_name_id])?$controlname[$row->control_name_id]:'';
		$accchartctrlhead['other_type_id']=isset($otherType[$row->other_type_id])?$otherType[$row->other_type_id]:'';
		$accchartctrlhead['currency_id']=isset($currency[$row->currency_id])?$currency[$row->currency_id]:'';
		$accchartctrlhead['normal_balance_id']=isset($normalbalance[$row->normal_balance_id])?$normalbalance[$row->normal_balance_id]:'';

		$accchartctrlhead['status']=isset($status[$row->row_status])?$status[$row->row_status]:'';

		array_push($accchartctrlheads,$accchartctrlhead);
		}
        echo json_encode($accchartctrlheads);

    }

    public function getAccumulatedHead(){
    	$accchartsubgroup=array_prepend(array_pluck($this->accchartsubgroup->get(),'name','id'),'-Select-','');
		$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
		$ctrlHead=array_prepend(array_pluck($this->accchartctrlhead->get(),'name','id'),'-Select-','0');
		$ctrlheadtype=array_prepend(config('bprs.ctrlheadtype'),'-Select-','0');
		$statementType=array_prepend(config('bprs.statementType'),'-Select-','0');
		$controlname=array_prepend(config('bprs.controlname'),'-Select-','0');
		$otherType=array_prepend(config('bprs.otherType'),'-Select-','0');
		$normalbalance=array_prepend(config('bprs.normalbalance'),'-Select-','0');
		$accchartgroup=array_prepend(config('bprs.accchartgroup'),'-Select-','0');
		$status=array_prepend(config('bprs.status'),'-Select-','');
		$accumulatedheads=array();
		$rows=$this->accchartctrlhead
		->join('acc_chart_sub_groups',function($join){
			$join->on('acc_chart_sub_groups.id','=','acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->orderBy('code','asc')
		->where([['acc_chart_ctrl_heads.ctrlhead_type_id','=',1]])
		->where([['acc_chart_sub_groups.id','=',30]])
		->get([
			'acc_chart_ctrl_heads.*',
			'acc_chart_sub_groups.name as sub_group_name',
			'acc_chart_sub_groups.acc_chart_group_id'
		]);
		foreach ($rows as $row) {
		$accchartctrlhead['id']=$row->id;
		$accchartctrlhead['accumulate_head_name']=$row->name;
		$accchartctrlhead['code']=$row->code;
		$accchartctrlhead['sort_id']=$row->sort_id;
		$accchartctrlhead['root_id']=isset($ctrlHead[$row->root_id])?$ctrlHead[$row->root_id]:0;
		$accchartctrlhead['sub_group_name']=$row->sub_group_name;
		$accchartctrlhead['accchartgroup']=isset($accchartgroup[$row->acc_chart_group_id])?$accchartgroup[$row->acc_chart_group_id]:0;

		$accchartctrlhead['ctrlhead_type_id']=isset($ctrlheadtype[$row->ctrlhead_type_id])?$ctrlheadtype[$row->ctrlhead_type_id]:'';
		$accchartctrlhead['statement_type_id']=isset($statementType[$row->statement_type_id])?$statementType[$row->statement_type_id]:'';
		$accchartctrlhead['retained_earning_account_id']=$row->retained_earning_account_id;
		$accchartctrlhead['control_name_id']=isset($controlname[$row->control_name_id])?$controlname[$row->control_name_id]:'';
		$accchartctrlhead['other_type_id']=isset($otherType[$row->other_type_id])?$otherType[$row->other_type_id]:'';
		$accchartctrlhead['currency_id']=isset($currency[$row->currency_id])?$currency[$row->currency_id]:'';
		$accchartctrlhead['normal_balance_id']=isset($normalbalance[$row->normal_balance_id])?$normalbalance[$row->normal_balance_id]:'';

		$accchartctrlhead['status']=isset($status[$row->row_status])?$status[$row->row_status]:'';

		array_push($accumulatedheads,$accchartctrlhead);
		}
        echo json_encode($accumulatedheads);

    }

}