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
use App\Repositories\Contracts\Production\Garments\ProdGmtPolyRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPolyOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPolyQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;



use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtPolyQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtPolyQtyController extends Controller {

    private $company;
    private $prodgmtpolyqty;
    private $location;
    private $buyer;

    public function __construct(ProdGmtPolyQtyRepository $prodgmtpolyqty, ProdGmtPolyOrderRepository $prodgmtpolyorder,ProdGmtPolyRepository $prodgmtpoly, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier,CountryRepository $country, BuyerRepository $buyer, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->prodgmtpolyqty = $prodgmtpolyqty;
        $this->prodgmtpolyorder = $prodgmtpolyorder;
        $this->prodgmtpoly = $prodgmtpoly;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->country = $country;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        
        $this->middleware('auth');
        // $this->middleware('permission:view.prodgmtpolyqties',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.prodgmtpolyqties', ['only' => ['store']]);
        // $this->middleware('permission:edit.prodgmtpolyqties',   ['only' => ['update']]);
        // $this->middleware('permission:delete.prodgmtpolyqties', ['only' => ['destroy']]);
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtPolyQtyRequest $request) {

        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            if($sales_order_gmt_color_size_id && $request->qty[$index])
            {
                $prodgmtpolyqty = $this->prodgmtpolyqty->updateOrCreate(
                ['sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,'prod_gmt_poly_order_id' => $request->prod_gmt_poly_order_id],
                ['qty' => $request->qty[$index],'alter_qty' => $request->alter_qty[$index],'spot_qty' => $request->spot_qty[$index],'reject_qty' => $request->reject_qty[$index],'replace_qty' => $request->replace_qty[$index]]);
            }
        }


        if($prodgmtpolyqty){
            return response()->json(array('success' => true,'id' =>  $prodgmtpolyqty->id,'message' => 'Save Successfully'),200);
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
        $prodgmtpolyqty = $this->prodgmtpolyqty->find($id);
        $row ['fromData'] = $prodgmtpolyqty;
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
    public function update(ProdGmtPolyQtyRequest $request, $id) {
        $prodgmtpolyqty=$this->prodgmtpolyqty->update($id,$request->except(['id']));
        if($prodgmtpolyqty){
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
        if($this->prodgmtpolyqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}