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
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;



use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtDlvToEmbQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtDlvToEmbQtyController extends Controller {

    private $company;
    private $dlvtoembqty;
    private $location;
    private $buyer;

    public function __construct(ProdGmtDlvToEmbQtyRepository $dlvtoembqty, ProdGmtDlvToEmbOrderRepository $prodgmtdlvtoemborder,ProdGmtDlvToEmbRepository $prodgmtdlvtoemb, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier,CountryRepository $country, BuyerRepository $buyer, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->dlvtoembqty = $dlvtoembqty;
        $this->prodgmtdlvtoemborder = $prodgmtdlvtoemborder;
        $this->prodgmtdlvtoemb = $prodgmtdlvtoemb;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->country = $country;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtdlvtoembqtys',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtdlvtoembqtys', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtdlvtoembqtys',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtdlvtoembqtys', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		//
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
    public function store(ProdGmtDlvToEmbQtyRequest $request) {
        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            if($sales_order_gmt_color_size_id && $request->qty[$index])
            {
                $dlvtoembqty = $this->dlvtoembqty->updateOrCreate(
                ['sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,'prod_gmt_dlv_to_emb_order_id' => $request->prod_gmt_dlv_to_emb_order_id],
                ['qty' => $request->qty[$index]]);
            }
        }

        if($dlvtoembqty){
            return response()->json(array('success' => true,'id' =>  $dlvtoembqty->id,'message' => 'Save Successfully'),200);
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
        $dlvtoembqty = $this->dlvtoembqty->find($id);
        $row ['fromData'] = $dlvtoembqty;
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
    public function update(ProdGmtDlvToEmbQtyRequest $request, $id) {
        $dlvtoembqty=$this->dlvtoembqty->update($id,$request->except(['id']));
        if($dlvtoembqty){
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
        if($this->dlvtoembqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}