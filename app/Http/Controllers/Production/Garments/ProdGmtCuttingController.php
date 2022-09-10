<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtCuttingRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtCuttingController extends Controller {

    private $prodgmtcutting;
    private $location;
    private $company;
    private $supplier;
    private $uom;

    public function __construct(ProdGmtCuttingRepository $prodgmtcutting, LocationRepository $location, CompanyRepository $company,SupplierRepository $supplier, UomRepository $uom) {
        $this->prodgmtcutting = $prodgmtcutting;
        $this->location = $location;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->location = $location;
        $this->uom = $uom;

        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtcuttings',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtcuttings', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtcuttings',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtcuttings', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
         
        $prodgmtcuttings=array();
        $rows=$this->prodgmtcutting
        ->orderBy('prod_gmt_cuttings.id','desc')
        ->get();
        foreach($rows as $row){
            $prodgmtcutting['id']=$row->id;
            $prodgmtcutting['cut_qc_date']=date('Y-m-d',strtotime($row->cut_qc_date));
            $prodgmtcutting['shiftname_id']=$shiftname[$row->shiftname_id];
            $prodgmtcutting['remarks']=$row->remarks;
            array_push($prodgmtcuttings,$prodgmtcutting);
        }
        echo json_encode($prodgmtcuttings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');

        return Template::loadView('Production.Garments.ProdGmtCutting', ['location'=> $location,'productionsource'=> $productionsource,'shiftname'=> $shiftname, 'company'=> $company, 'fabriclooks'=>$fabriclooks,'supplier'=>$supplier,'uom'=>$uom]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtCuttingRequest $request) {
		$prodgmtcutting=$this->prodgmtcutting->create($request->except(['id']));
        if($prodgmtcutting){
            return response()->json(array('success' => true,'id' =>  $prodgmtcutting->id,'message' => 'Save Successfully'),200);
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
        $prodgmtcutting = $this->prodgmtcutting->find($id);
        $prodgmtcutting['cut_qc_date']=date('Y-m-d',strtotime($prodgmtcutting->cut_qc_date));
        $row ['fromData'] = $prodgmtcutting;
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
    public function update(ProdGmtCuttingRequest $request, $id) {
        $prodgmtcutting=$this->prodgmtcutting->update($id,$request->except(['id']));
        if($prodgmtcutting){
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
        if($this->prodgmtcutting->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}