<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetMaintenanceRepository;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Library\Template;
use App\Http\Requests\FAMS\AssetMaintenanceRequest;

class AssetMaintenanceController extends Controller {

    private $assetacquisition;
    private $assetmaintenance;
    private $assetdisposal;
    private $itemaccount;
    private $itemclass;
    private $itemcategory;

    public function __construct(
        AssetAcquisitionRepository $assetacquisition, 
        AssetMaintenanceRepository $assetmaintenance, 
        AssetDisposalRepository $assetdisposal,
        ItemAccountRepository $itemaccount, 
        ItemclassRepository $itemclass, 
        ItemcategoryRepository $itemcategory
        ) {
        $this->assetacquisition = $assetacquisition;
        $this->assetmaintenance = $assetmaintenance;
        $this->assetdisposal = $assetdisposal;
        $this->itemaccount = $itemaccount;

        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;



        $this->middleware('auth');
        $this->middleware('permission:view.assetmaintenances',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetmaintenances', ['only' => ['store']]);
        $this->middleware('permission:edit.assetmaintenances',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetmaintenances', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $itemnature=array_prepend(config('bprs.itemnature'),'-Select-','');
        $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-','');
        $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');

        $assetacquisition=array_prepend(array_pluck($this->assetacquisition->get(),'name','id'),'-Select-','');
        $itemaccount=array_prepend(array_pluck($this->itemaccount->get(),'name','id'),'-Select-','');
        $assetmaintenances=array();
        $rows=$this->assetmaintenance
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','asset_maintenances.item_account_id');
        })
        ->where([['asset_acquisition_id','=',request('asset_acquisition_id',0)]])
        //->get()
        ->get([
            'asset_maintenances.*',
            'item_accounts.id as item_account_id',
            'item_accounts.item_description'
        ]);
        foreach($rows as $row){
            $assetmaintenance['id']=$row->id;
            $assetmaintenance['asset_acquisition_id']=$assetacquisition[$row->asset_acquisition_id];
            $assetmaintenance['item_description']=$row->item_description;
            $assetmaintenance['rate']=$row->rate;
            array_push($assetmaintenances, $assetmaintenance);
        }
        echo json_encode($assetmaintenances);
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
    public function store(AssetMaintenanceRequest $request) {
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

        $assetmaintenance=$this->assetmaintenance->create([
            'asset_acquisition_id'=>$request->asset_acquisition_id,
            'item_account_id'=>$request->item_account_id,
            'rate'=>$request->rate
        ]);
        if($assetmaintenance){
            return response()->json(array('success'=>true,'id'=>$assetmaintenance->id,'message'=>'Save Successfully'),200);
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
       $assetmaintenance=$this->assetmaintenance
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','asset_maintenances.item_account_id');
        })
        ->join('asset_acquisitions',function($join){
           $join->on('asset_acquisitions.id','=','asset_maintenances.asset_acquisition_id');
        })
        ->where([['asset_maintenances.id','=',$id]])
        ->get([
           'asset_maintenances.*',
           'asset_acquisitions.id',
           'item_accounts.item_description'
       ])
       ->first();
       $row['fromData']=$assetmaintenance;
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
    public function update(AssetMaintenanceRequest $request, $id) {
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

        if($assetmaintenance){
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
        $assetmaintenance=$this->assetmaintenance->find($id);
        $assetdisposal=$this->assetdisposal
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','asset_disposals.asset_quantity_cost_id');
        })
        ->where([['asset_quantity_costs.asset_acquisition_id','=',$assetmaintenance->asset_acquisition_id]])
        ->get(['asset_disposals.id'])
        ->first();

        if ($assetdisposal) {
            return response()->json(array('success'=>false,'message'=>'Delete Not Successful. Asset Disposal Entry Found'),200);
        }

        if($this->assetmaintenance->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
        
    }

}
