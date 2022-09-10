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
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtDlvInputQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtDlvInputQtyController extends Controller {

    private $company;
    private $dlvinputqty;
    private $location;
    private $buyer;

    public function __construct(ProdGmtDlvInputQtyRepository $dlvinputqty, ProdGmtDlvInputOrderRepository $prodgmtdlvinputorder,ProdGmtDlvInputRepository $prodgmtdlvinput, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier,CountryRepository $country, BuyerRepository $buyer, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->dlvinputqty = $dlvinputqty;
        $this->prodgmtdlvinputorder = $prodgmtdlvinputorder;
        $this->prodgmtdlvinput = $prodgmtdlvinput;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->country = $country;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtdlvinputqtys',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtdlvinputqtys', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtdlvinputqtys',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtdlvinputqtys', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		/* $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
         
        $dlvinputqtys=array();
        $rows=$this->dlvinputqty
        ->orderBy('prod_gmt_dlvinputs.id','desc')
        ->get();
        foreach($rows as $row){
            $dlvinputqty['id']=$row->id;
            $dlvinputqty['dlv_qc_date']=date('Y-m-d',strtotime($row->dlv_qc_date));
            $dlvinputqty['shiftname_id']=$shiftname[$row->shiftname_id];
            array_push($dlvinputqtys,$dlvinputqty);
        }
        echo json_encode($dlvinputqtys); */
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
    public function store(ProdGmtDlvInputQtyRequest $request) {
		//$dlvinputqty=$this->dlvinputqty->create($request->except(['id']));
        //$impDocAcceptId=0;
        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            //$expInvoiceId=$request->exp_invoice_id[$index];
            if($sales_order_gmt_color_size_id && $request->qty[$index])
            {
                $dlvinputqty = $this->dlvinputqty->updateOrCreate(
                [
                    'sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,'prod_gmt_dlv_input_order_id' => $request->prod_gmt_dlv_input_order_id
                ],
                [
                    'qty' => $request->qty[$index]
                ]);
            }
        }


        if($dlvinputqty){
            return response()->json(array('success' => true,'id' =>  $dlvinputqty->id,'message' => 'Save Successfully'),200);
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
        $dlvinputqty = $this->dlvinputqty->find($id);
        $row ['fromData'] = $dlvinputqty;
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
    public function update(ProdGmtDlvInputQtyRequest $request, $id) {
        $dlvinputqty=$this->dlvinputqty->update($id,$request->except(['id']));
        if($dlvinputqty){
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
        if($this->dlvinputqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}