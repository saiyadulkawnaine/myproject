<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPolyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Http\Requests\Production\Garments\ProdGmtPolyRequest;
use App\Library\Template;

class ProdGmtPolyController extends Controller {

    private $prodgmtpoly;
    private $location;
    private $supplier;

    public function __construct(ProdGmtPolyRepository $prodgmtpoly, LocationRepository $location, SupplierRepository $supplier) {
        $this->prodgmtpoly = $prodgmtpoly;
        $this->location = $location;
        $this->supplier = $supplier;

        $this->middleware('auth');
        // $this->middleware('permission:view.prodgmtpolies',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.prodgmtpolies', ['only' => ['store']]);
        // $this->middleware('permission:edit.prodgmtpolies',   ['only' => ['update']]);
        // $this->middleware('permission:delete.prodgmtpolies', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
         
        $prodgmtpolys=array();
        $rows=$this->prodgmtpoly
        ->orderBy('prod_gmt_polies.id','desc')
        ->get();
        foreach($rows as $row){
            $prodgmtpoly['id']=$row->id;
            $prodgmtpoly['poly_qc_date']=date('Y-m-d',strtotime($row->poly_qc_date));
            $prodgmtpoly['shiftname_id']=$shiftname[$row->shiftname_id];
            $prodgmtpoly['remarks']=$row->remarks;
            array_push($prodgmtpolys,$prodgmtpoly);
        }
        echo json_encode($prodgmtpolys);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
		$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'','');
        return Template::loadView('Production.Garments.ProdGmtPoly', ['location'=> $location,'productionsource'=> $productionsource,'shiftname'=> $shiftname,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtPolyRequest $request) {
		$prodgmtpoly=$this->prodgmtpoly->create($request->except(['id']));
        if($prodgmtpoly){
            return response()->json(array('success' => true,'id' =>  $prodgmtpoly->id,'message' => 'Save Successfully'),200);
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
        $prodgmtpoly = $this->prodgmtpoly->find($id);
        $row ['fromData'] = $prodgmtpoly;
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
    public function update(ProdGmtPolyRequest $request, $id) {
        $prodgmtpoly=$this->prodgmtpoly->update($id,$request->except(['id']));
        if($prodgmtpoly){
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
        if($this->prodgmtpoly->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}