<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetTechFileUploadRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
use App\Library\Template;
use App\Http\Requests\FAMS\AssetTechFileUploadRequest;

class AssetTechFileUploadController extends Controller {

    private $assettechfileUpload;
    private $assetacquisition;
    private $assetdisposal;


    public function __construct(
        AssetTechFileUploadRepository $assettechfileUpload, 
        AssetDisposalRepository $assetdisposal, 
        AssetAcquisitionRepository $assetacquisition
        ) {
        $this->assettechfileUpload = $assettechfileUpload;
        $this->assetacquisition = $assetacquisition; 
        $this->assetdisposal = $assetdisposal; 


        $this->middleware('auth');
        $this->middleware('permission:view.assettechfileuploads',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assettechfileuploads', ['only' => ['store']]);
        $this->middleware('permission:edit.assettechfileuploads',   ['only' => ['update']]);
        $this->middleware('permission:delete.assettechfileuploads', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $assetacquisition=array_prepend(array_pluck($this->assetacquisition->get(),'name','id'),'-Select-','');

        $assettechfileUploads=array();
        $rows=$this->assettechfileUpload->where([['asset_acquisition_id','=',request('asset_acquisition_id',0)]])->get();
        foreach($rows as $row){
            $assettechfileUpload['id']=$row->id;
            $assettechfileUpload['asset_acquisition_id']=$assetacquisition[$row->asset_acquisition_id];
            $assettechfileUpload['file_src']=$row->file_src;

            array_push($assettechfileUploads, $assettechfileUpload);
        }
        echo json_encode($assettechfileUploads);

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
    public function store(AssetTechFileUploadRequest $request) {
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
        
        if($request->id){
           $this->update($request, $request->id);
           return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);
        }
        else{
            $name =time().'.'.$request->file_src->getClientOriginalExtension();
            $request->file_src->move(public_path('images'), $name);
            $assettechfileUpload=$this->assettechfileUpload->create([
            'asset_acquisition_id'=>$request->asset_acquisition_id,
            'file_src'=> $name,
            ]);
            if($assettechfileUpload){
            return response()->json(array('success'=>true,'id'=>$assettechfileUpload->id,'message'=>'Save Successfully'),200);
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
       $assettechfileUpload=$this->assettechfileUpload->find($id);
       $row['fromData']=$assettechfileUpload;
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
    public function update(AssetTechFileUploadRequest $request, $id) {    
        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);   
        $assettechfileUpload=$this->assettechfileUpload->update($request->id,[
            'asset_acquisition_id'=>$request->asset_acquisition_id,
            'file_src'=>$name
            ]);
               
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->assettechfileUpload->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
        // else{
        //      return response()->json(array('success' => false, 'message' => 'Delete Not Successfull'), 200);
        // }
        
    }

}
