<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetUtilityDetailRepository;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;

use App\Library\Template;
use App\Http\Requests\FAMS\AssetUtilityDetailRequest;

class AssetUtilityDetailController extends Controller {

    private $assetacquisition;
    private $assetutildetail;
    private $assetdisposal;


    public function __construct(
        AssetAcquisitionRepository $assetacquisition,
        AssetDisposalRepository $assetdisposal,
        AssetUtilityDetailRepository $assetutildetail
        ) {
        $this->assetacquisition = $assetacquisition;
        $this->assetutildetail = $assetutildetail;
        $this->assetdisposal = $assetdisposal;


        $this->middleware('auth');
        $this->middleware('permission:view.assetutildetails',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetutildetails', ['only' => ['store']]);
        $this->middleware('permission:edit.assetutildetails',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetutildetails', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $assetacquisition=array_prepend(array_pluck($this->assetacquisition->get(),'name','id'),'-Select-','');
        $assetutildetails=array();
        $rows=$this->assetutildetail->where([['asset_acquisition_id','=',request('asset_acquisition_id',0)]])->get();
        foreach($rows as $row){
            $assetutildetail['id']=$row->id;
            $assetutildetail['asset_acquisition_id']=$assetacquisition[$row->asset_acquisition_id];
            $assetutildetail['power_consumption']=$row->power_consumption;
            $assetutildetail['water_consumption']=$row->water_consumption;
            $assetutildetail['air_consumption']=$row->air_consumption;
            $assetutildetail['steam_consumption']=$row->steam_consumption;
            $assetutildetail['gas_consumption']=$row->gas_consumption;
            $assetutildetail['power_stating_load']=$row->power_stating_load;
            $assetutildetail['power_running_load']=$row->power_running_load;
            $assetutildetail['power_rate']=$row->power_rate;
            $assetutildetail['water_rate']=$row->water_rate;
            $assetutildetail['air_rate']=$row->air_rate;
            $assetutildetail['steam_rate']=$row->steam_rate;
            $assetutildetail['gas_rate']=$row->gas_rate;
            array_push($assetutildetails, $assetutildetail);
        }
        echo json_encode($assetutildetails);

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
    public function store(AssetUtilityDetailRequest $request) {
        $assetdisposal=$this->assetdisposal
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_disposals.asset_quantity_cost_id');
        })
        ->where([['asset_quantity_costs.asset_acquisition_id','=',$request->asset_acquisition_id]])
        ->get(['asset_disposals.id'])
        ->first();

        if ($assetdisposal) {
            return response()->json(array('success'=>false,'message'=>'Save Not Successful. Asset Disposal Entry Found'),200);
        }
        $assetutildetail=$this->assetutildetail->create($request->except(['id']));
        if($assetutildetail){
            return response()->json(array('success'=>true,'id'=>$assetutildetail->id,'message'=>'Save Successfully'),200);
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
       $assetutildetail=$this->assetutildetail->find($id);
       $row['fromData']=$assetutildetail;
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
    public function update(AssetUtilityDetailRequest $request, $id) {
        $assetdisposal=$this->assetdisposal
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_disposals.asset_quantity_cost_id');
        })
        ->where([['asset_quantity_costs.asset_acquisition_id','=',$request->asset_acquisition_id]])
        ->get(['asset_disposals.id'])
        ->first();

        if ($assetdisposal) {
            return response()->json(array('success'=>false,'message'=>'Update Not Successful. Asset Disposal Entry Found'),200);
        }
        
        $assetutildetail=$this->assetutildetail->update($id,$request->except(['id']));
        if($assetutildetail){
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
        if($this->assetutildetail->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
        // else{
        //      return response()->json(array('success' => false, 'message' => 'Delete Not Successfull'), 200);
        // }
        
    }

}
