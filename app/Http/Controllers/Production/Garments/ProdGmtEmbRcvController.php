<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;

use App\Library\Template;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Http\Requests\Production\Garments\ProdGmtEmbRcvRequest;

class ProdGmtEmbRcvController extends Controller {

    private $prodgmtembrcv;
    private $location;
    private $company;
    private $supplier;

    public function __construct(ProdGmtEmbRcvRepository $prodgmtembrcv, LocationRepository $location, CompanyRepository $company, SupplierRepository $supplier,AssetAcquisitionRepository $assetacquisition,AssetQuantityCostRepository $assetquantitycost,ProdGmtDlvToEmbRepository $prodgmtdlvtoemb) {
        $this->prodgmtembrcv = $prodgmtembrcv;
        $this->location = $location;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->assetacquisition = $assetacquisition;
        $this->assetquantitycost = $assetquantitycost;
        $this->prodgmtdlvtoemb = $prodgmtdlvtoemb;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtembrcvs',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtembrcvs', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtembrcvs',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtembrcvs', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
         
        $prodgmtembrcvs=array();
        $rows=$this->prodgmtembrcv
        ->orderBy('prod_gmt_emb_rcvs.id','desc')
        ->get();
        foreach($rows as $row){
            $prodgmtembrcv['id']=$row->id;
            $prodgmtembrcv['receive_no']=$row->receive_no;
            $prodgmtembrcv['party_challan_no']=$row->party_challan_no;
            $prodgmtembrcv['receive_date']=date('Y-m-d',strtotime($row->receive_date));
            $prodgmtembrcv['shiftname_id']=isset($shiftname[$row->shiftname_id])?$shiftname[$row->shiftname_id]:'';
            $prodgmtembrcv['remarks']=$row->remarks;
            array_push($prodgmtembrcvs,$prodgmtembrcv);
        }
        echo json_encode($prodgmtembrcvs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->embellishmentSubcontractor(),'name','id'),'','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $assetquantitycost = array_prepend(array_pluck($this->assetquantitycost->leftJoin('asset_acquisitions', function($join) use ($request) {
            $join->on('asset_quantity_costs.asset_acquisition_id', '=', 'asset_acquisitions.id');
            })
            ->where(['asset_acquisitions.production_area_id' => 45])
            ->get([
                'asset_quantity_costs.id',
                'asset_quantity_costs.custom_no'
            ]),'custom_no','id'),'-Select-',0);

        return Template::loadView('Production.Garments.ProdGmtEmbRcv', ['location'=> $location,'productionsource'=> $productionsource,'shiftname'=> $shiftname, 'company'=> $company, 'fabriclooks'=>$fabriclooks,'supplier'=>$supplier,'assetquantitycost'=>$assetquantitycost]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtEmbRcvRequest $request) {
        $year=date('Y');
        $max = $this->prodgmtembrcv->where([['year','=', $year]])->max('receive_no');
        $receive_no=$max+1;
        $prodgmtembrcv = $this->prodgmtembrcv->create([
            'receive_no'=>$receive_no,
            'party_challan_no'=>$request->party_challan_no,
            'prod_gmt_dlv_to_emb_id'=>$request->prod_gmt_dlv_to_emb_id,
            'year'=>$year,        
            'receive_date'=>$request->receive_date,
            'shiftname_id'=>$request->shiftname_id,
            'remarks'=>$request->remarks
        ]);
        if($prodgmtembrcv){
            return response()->json(array('success' => true,'id' =>  $prodgmtembrcv->id,'message' => 'Save Successfully'),200);
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
        $prodgmtembrcv = $this->prodgmtembrcv
        ->leftJoin('prod_gmt_dlv_to_embs',function($join){
            $join->on('prod_gmt_dlv_to_embs.id','=','prod_gmt_emb_rcvs.prod_gmt_dlv_to_emb_id');
        })
        ->leftJoin('suppliers',function($join){
            $join->on('prod_gmt_dlv_to_embs.supplier_id','=','suppliers.id');
        })
        ->leftJoin('locations',function($join){
            $join->on('prod_gmt_dlv_to_embs.location_id','=','locations.id');
        })
        ->where([['prod_gmt_emb_rcvs.id','=',$id]])
        ->get([
            'prod_gmt_emb_rcvs.*',
            'prod_gmt_dlv_to_embs.supplier_id',
            'prod_gmt_dlv_to_embs.location_id',
            'prod_gmt_dlv_to_embs.challan_no',
            'suppliers.name as supplier_name',
            'locations.name as location_id'
        ])
        ->first();
        $row ['fromData'] = $prodgmtembrcv;
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
    public function update(ProdGmtEmbRcvRequest $request, $id) {
        $prodgmtembrcv=$this->prodgmtembrcv->update($id,$request->except(['id','receive_no','challan_no']));
        if($prodgmtembrcv){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodgmtembrcv->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
    public function getDlvToEmb(){
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
         
        $prodgmtdlvtoembs=array();
        $rows=$this->prodgmtdlvtoemb
        ->orderBy('prod_gmt_dlv_to_embs.id','desc')
        ->get();
        foreach($rows as $row){
            $prodgmtdlvtoemb['id']=$row->id;
            $prodgmtdlvtoemb['challan_no']=$row->challan_no;
            $prodgmtdlvtoemb['supplier_id']=$supplier[$row->supplier_id];
            $prodgmtdlvtoemb['delivery_date']=date('Y-m-d',strtotime($row->delivery_date));
            $prodgmtdlvtoemb['location_id']=$location[$row->location_id];
            $prodgmtdlvtoemb['shiftname_id']=$shiftname[$row->shiftname_id];
            array_push($prodgmtdlvtoembs,$prodgmtdlvtoemb);
        }
        echo json_encode($prodgmtdlvtoembs);
    }
}