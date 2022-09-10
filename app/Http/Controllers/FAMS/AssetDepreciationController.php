<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetDepreciationRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
use App\Library\Template;
use App\Http\Requests\FAMS\AssetDepreciationRequest;

class AssetDepreciationController extends Controller {

    private $assetdepc;
    private $assetacquisition;
    private $assetdisposal;



    public function __construct(
        AssetAcquisitionRepository $assetacquisition,
        AssetDisposalRepository $assetdisposal,
        AssetDepreciationRepository $assetdepc) {
        $this->assetdepc = $assetdepc;
        $this->assetacquisition = $assetacquisition;
        $this->assetdisposal = $assetdisposal;



        $this->middleware('auth');
        $this->middleware('permission:view.assetdepreciations',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetdepreciations', ['only' => ['store']]);
        $this->middleware('permission:edit.assetdepreciations',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetdepreciations', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $depMethod=array_prepend(config('bprs.depMethod'),'-Select-','');
        $rows=$this->assetdepc->where([['id','=',request('id',0)]])
        ->get([
            'asset_acquisitions.*'
        ])
        ->map(function($rows) use($depMethod){
            $rows->depreciation_method=isset($depMethod[$rows->depreciation_method_id])?$depMethod[$rows->depreciation_method_id]:'';
            return $rows;
        });

        echo json_encode($rows);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetDepreciationRequest $request) {
        //
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
       $assetdepc=$this->assetdepc->find($id);
       $row['fromData']=$assetdepc;
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
    public function update(AssetDepreciationRequest $request, $id) {
        $assetdisposal=$this->assetdisposal
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_disposals.asset_quantity_cost_id');
        })
        ->where([['asset_quantity_costs.asset_acquisition_id','=',$id]])
        ->get(['asset_disposals.id'])
        ->first();

        if ($assetdisposal) {
            return response()->json(array('success'=>false,'message'=>'Update Not Successful. Asset Disposal Entry Found'),200);
        }

        $assetdepc=$this->assetdepc->update($id,[
            //'accumulated_dep'=>$request->accumulated_dep,
            //'salvage_value' =>$request->salvage_value,
            'depreciation_method_id' => $request->depreciation_method_id,
            'depreciation_rate' => $request->depreciation_rate,
            //'life_time' => $request->life_time
        ]);
        if($assetdepc){
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

        //
    }

}
