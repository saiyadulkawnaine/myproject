<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetTechImageRepository;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
//use App\Repositories\Contracts\FAMS\AssetTechnicalFeatureRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Library\Template;
use App\Http\Requests\FAMS\AssetTechImageRequest;

class AssetTechImageController extends Controller {

    private $assettechimage;
    private $assetdisposal;
    private $assetacquisition;


    public function __construct(
        AssetTechImageRepository $assettechimage, 
        AssetDisposalRepository $assetdisposal,
        AssetAcquisitionRepository $assetacquisition
    ) {
        $this->assettechimage = $assettechimage;
        $this->assetdisposal = $assetdisposal;
        $this->assetacquisition = $assetacquisition;


        $this->middleware('auth');
        $this->middleware('permission:view.assettechimages',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assettechimages', ['only' => ['store']]);
        $this->middleware('permission:edit.assettechimages',   ['only' => ['update']]);
        $this->middleware('permission:delete.assettechimages', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $assetacquisition=array_prepend(array_pluck($this->assetacquisition->get(),'name','id'),'-Select-','');


        $assettechimages=array();
        $rows=$this->assettechimage->where([['asset_acquisition_id','=',request('asset_acquisition_id',0)]])->get();
        //$rows=$this->assettechimage->getAll();
        foreach($rows as $row){
            $assettechimage['id']=$row->id;
            //$assettechimage['asset_acquisition_id']=$assetacquisition[$row->asset_acquisition_id];
            $assettechimage['asset_acquisition_id']=$assetacquisition[$row->asset_acquisition_id];
            $assettechimage['file_src']=$row->file_src;

            array_push($assettechimages, $assettechimage);
        }
        echo json_encode($assettechimages);

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
    public function store(AssetTechImageRequest $request) {

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
        
        if($request->id)
        {
            $this->update($request, $request->id);
                return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);

        }
        else{
            $name =time().'.'.$request->file_src->getClientOriginalExtension();
            $request->file_src->move(public_path('images'), $name);
            $assettechimage=$this->assettechimage->create([
            'asset_acquisition_id'=>$request->asset_acquisition_id,
            'file_src'=> $name,
            ]);
            if($assettechimage){
            return response()->json(array('success'=>true,'id'=>$assettechimage->id,'message'=>'Save Successfully'),200);
            }
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
       $assettechimage=$this->assettechimage->find($id);
       $row['fromData']=$assettechimage;
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
    public function update(AssetTechImageRequest $request, $id) {
        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);
        $assettechimage=$this->assettechimage->update($request->id,[
        'asset_acquisition_id'=>$request->asset_acquisition_id,
        'file_src'=> $name,
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->assettechimage->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
        // else{
        //      return response()->json(array('success' => false, 'message' => 'Delete Not Successfull'), 200);
        // }
        
    }

}
