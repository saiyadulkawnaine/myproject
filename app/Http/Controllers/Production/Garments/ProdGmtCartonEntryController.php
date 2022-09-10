<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonEntryRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtCartonEntryRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtCartonEntryController extends Controller {

    private $company;
    private $prodgmtcarton;
    private $location;
    private $buyer;

    public function __construct(ProdGmtCartonEntryRepository $prodgmtcarton, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier,CountryRepository $country, BuyerRepository $buyer) {
        $this->prodgmtcarton = $prodgmtcarton;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->country = $country;
        $this->middleware('auth');
            $this->middleware('permission:view.prodgmtcartonentries',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtcartonentries', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtcartonentries',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtcartonentries', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
		$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $ordersource=config('bprs.ordersource');
         
        $prodgmtcartons=array();
        $rows=$this->prodgmtcarton
        ->orderBy('prod_gmt_carton_entries.id','desc')
        ->get();
        foreach($rows as $row){
            $prodgmtcarton['id']=$row->id;
            $prodgmtcarton['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:'';
            $prodgmtcarton['buyer_id']=isset($buyer[$row->buyer_id])?$buyer[$row->buyer_id]:'';
            $prodgmtcarton['carton_date']=date('Y-m-d',strtotime($row->carton_date));
		    $prodgmtcarton['order_source_id']=isset($ordersource[$row->order_source_id])?$ordersource[$row->order_source_id]:'';
            $prodgmtcarton['prod_source_id']=isset($productionsource[$row->prod_source_id])?$productionsource[$row->prod_source_id]:'';
            $prodgmtcarton['supplier_id']=isset($supplier[$row->supplier_id])?$supplier[$row->supplier_id]:'';
            $prodgmtcarton['location_id']=isset($location[$row->location_id])?$location[$row->location_id]:'';
            $prodgmtcarton['shiftname_id']=isset($shiftname[$row->shiftname_id])?$shiftname[$row->shiftname_id]:'';
            array_push($prodgmtcartons,$prodgmtcarton);
        }
        echo json_encode($prodgmtcartons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
		$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $ordersource=array_prepend(config('bprs.ordersource'),'-Select-','');

        return Template::loadView('Production.Garments.ProdGmtCartonEntry', ['location'=> $location,'productionsource'=> $productionsource,'shiftname'=> $shiftname,'ordersource'=>$ordersource,'company'=>$company,'supplier'=>$supplier,'country'=>$country,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtCartonEntryRequest $request) {
		$prodgmtcarton=$this->prodgmtcarton->create($request->except(['id']));
        if($prodgmtcarton){
            return response()->json(array('success' => true,'id' =>  $prodgmtcarton->id,'message' => 'Save Successfully'),200);
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
        $prodgmtcarton = $this->prodgmtcarton->find($id);
        $prodgmtcarton['carton_date']=date('Y-m-d',strtotime($prodgmtcarton->carton_date));
        $row ['fromData'] = $prodgmtcarton;
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
    public function update(ProdGmtCartonEntryRequest $request, $id) {
        $prodgmtcarton=$this->prodgmtcarton->update($id,$request->except(['id','buyer_id']));
        if($prodgmtcarton){
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
        if($this->prodgmtcarton->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function showList(){
        $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
		$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $ordersource=config('bprs.ordersource');
         
        $prodgmtcartons=array();
        $rows=$this->prodgmtcarton
        ->orderBy('prod_gmt_carton_entries.id','desc')
        ->get();
        foreach($rows as $row){
            $prodgmtcarton['id']=$row->id;
            $prodgmtcarton['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:'';
            $prodgmtcarton['buyer_id']=isset($buyer[$row->buyer_id])?$buyer[$row->buyer_id]:'';
            $prodgmtcarton['carton_date']=date('Y-m-d',strtotime($row->carton_date));
		    $prodgmtcarton['order_source_id']=isset($ordersource[$row->order_source_id])?$ordersource[$row->order_source_id]:'';
            $prodgmtcarton['prod_source_id']=isset($productionsource[$row->prod_source_id])?$productionsource[$row->prod_source_id]:'';
            $prodgmtcarton['supplier_id']=isset($supplier[$row->supplier_id])?$supplier[$row->supplier_id]:'';
            $prodgmtcarton['location_id']=isset($location[$row->location_id])?$location[$row->location_id]:'';
            $prodgmtcarton['shiftname_id']=isset($shiftname[$row->shiftname_id])?$shiftname[$row->shiftname_id]:'';
            array_push($prodgmtcartons,$prodgmtcarton);
        }
        echo json_encode($prodgmtcartons);
    }

}