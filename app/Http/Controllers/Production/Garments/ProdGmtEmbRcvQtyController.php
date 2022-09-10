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
use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtEmbRcvQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtEmbRcvQtyController extends Controller {

    private $company;
    private $embrcvqty;
    private $location;
    private $buyer;

    public function __construct(ProdGmtEmbRcvQtyRepository $embrcvqty, ProdGmtEmbRcvOrderRepository $prodgmtembrcvorder,ProdGmtEmbRcvRepository $prodgmtembrcv, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier,CountryRepository $country, BuyerRepository $buyer, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->embrcvqty = $embrcvqty;
        $this->prodgmtembrcvorder = $prodgmtembrcvorder;
        $this->prodgmtembrcv = $prodgmtembrcv;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->country = $country;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtembrcvqtys',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtembrcvqtys', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtembrcvqtys',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtembrcvqtys', ['only' => ['destroy']]);*/
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
    public function store(ProdGmtEmbRcvQtyRequest $request) {
        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            if($sales_order_gmt_color_size_id && $request->qty[$index])
            {
                $embrcvqty = $this->embrcvqty->updateOrCreate(
                [
                    'sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,'prod_gmt_emb_rcv_order_id' => $request->prod_gmt_emb_rcv_order_id
                ],
                [
                    'qty' => $request->qty[$index],
                    'reject_qty' => $request->reject_qty[$index]                    
                ]);
            }
        }


        if($embrcvqty){
            return response()->json(array('success' => true,'id' =>  $embrcvqty->id,'message' => 'Save Successfully'),200);
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
        $embrcvqty = $this->embrcvqty->find($id);
        $row ['fromData'] = $embrcvqty;
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
    public function update(ProdGmtEmbRcvQtyRequest $request, $id) {
        $embrcvqty=$this->embrcvqty->update($id,$request->except(['id']));
        if($embrcvqty){
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
        if($this->embrcvqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}