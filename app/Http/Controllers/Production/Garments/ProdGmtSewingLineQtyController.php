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
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtSewingLineQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtSewingLineQtyController extends Controller {

    private $company;
    private $sewinglineqty;
    private $location;
    private $buyer;

    public function __construct(ProdGmtSewingLineQtyRepository $sewinglineqty, ProdGmtSewingLineOrderRepository $prodgmtsewinglineorder,ProdGmtSewingLineRepository $prodgmtsewingline, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier,CountryRepository $country, BuyerRepository $buyer, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->sewinglineqty = $sewinglineqty;
        $this->prodgmtsewinglineorder = $prodgmtsewinglineorder;
        $this->prodgmtsewingline = $prodgmtsewingline;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->country = $country;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtsewinglineqtys',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtsewinglineqtys', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtsewinglineqtys',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtsewinglineqtys', ['only' => ['destroy']]);*/
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
    public function store(ProdGmtSewingLineQtyRequest $request) {
        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            if($sales_order_gmt_color_size_id && $request->qty[$index])
            {
                $sewinglineqty = $this->sewinglineqty->updateOrCreate(
                ['sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,'prod_gmt_sewing_line_order_id' => $request->prod_gmt_sewing_line_order_id
            ],
                [
                    'qty' => $request->qty[$index]
                ]);
            }
        }


        if($sewinglineqty){
            return response()->json(array('success' => true,'id' =>  $sewinglineqty->id,'message' => 'Save Successfully'),200);
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
        $sewinglineqty = $this->sewinglineqty->find($id);
        $row ['fromData'] = $sewinglineqty;
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
    public function update(ProdGmtSewingLineQtyRequest $request, $id) {
        $sewinglineqty=$this->sewinglineqty->update($id,$request->except(['id']));
        if($sewinglineqty){
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
        if($this->sewinglineqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}