<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetTechnicalFeatureRepository;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
use App\Library\Template;
use App\Http\Requests\FAMS\AssetTechnicalFeatureRequest;

class AssetTechnicalFeatureController extends Controller {

    private $assetacquisition;
    private $assettechfeature;
    private $assetdisposal;


    public function __construct(
        AssetAcquisitionRepository $assetacquisition,
        AssetDisposalRepository $assetdisposal,
        AssetTechnicalFeatureRepository $assettechfeature
        
        ) {
        $this->assetacquisition = $assetacquisition;
        $this->assettechfeature = $assettechfeature;
        $this->assetdisposal = $assetdisposal;


        $this->middleware('auth');
        $this->middleware('permission:view.assettechfeatures',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assettechfeatures', ['only' => ['store']]);
        $this->middleware('permission:edit.assettechfeatures',   ['only' => ['update']]);
        $this->middleware('permission:delete.assettechfeatures', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $assetacquisition=array_prepend(array_pluck($this->assetacquisition->get(),'name','id'),'-Select-','');

        $assettechfeatures=array();
        $rows=$this->assettechfeature->where([['asset_acquisition_id','=',request('asset_acquisition_id',0)]])->get();
        foreach($rows as $row){
            $assettechfeature['id']=$row->id;
            $assettechfeature['asset_acquisition_id']=$assetacquisition[$row->asset_acquisition_id];
            $assettechfeature['dia_width']=$row->dia_width;
            $assettechfeature['gauge']=$row->gauge;
            $assettechfeature['extra_cylinder']=$row->extra_cylinder;
            $assettechfeature['no_of_feeder']=$row->no_of_feeder;
            $assettechfeature['attachment']=$row->attachment;

            array_push($assettechfeatures, $assettechfeature);
        }
        echo json_encode($assettechfeatures);

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
    public function store(AssetTechnicalFeatureRequest $request) {
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
        $assettechfeature=$this->assettechfeature->create([
            'asset_acquisition_id'=>$request->asset_acquisition_id,
            'dia_width'=>$request->dia_width,
            'gauge'=>$request->gauge,
            'extra_cylinder'=>$request->extra_cylinder,
            'no_of_feeder'=>$request->no_of_feeder,
            'attachment'=>$request->attachment,
            ]);
        if($assettechfeature){
            return response()->json(array('success'=>true,'id'=>$assettechfeature->id,'message'=>'Save Successfully'),200);
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
       $assettechfeature=$this->assettechfeature->find($id);
       $row['fromData']=$assettechfeature;
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
    public function update(AssetTechnicalFeatureRequest $request, $id) {
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
        $assettechfeature=$this->assettechfeature->update($id,[
            'asset_acquisition_id'=>$request->asset_acquisition_id,
            'dia_width'=>$request->dia_width,
            'gauge'=>$request->gauge,
            'extra_cylinder'=>$request->extra_cylinder,
            'no_of_feeder'=>$request->no_of_feeder,
            'attachment'=>$request->attachment,
        ]);
        if($assettechfeature){
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
        if($this->assettechfeature->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}       
    }
}
