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
use App\Repositories\Contracts\Production\Garments\ProdGmtIronRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtIronOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtIronQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;



use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtIronQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtIronQtyController extends Controller {

    private $company;
    private $ironqty;
    private $location;
    private $buyer;

    public function __construct(ProdGmtIronQtyRepository $ironqty, ProdGmtIronOrderRepository $prodgmtironorder,ProdGmtIronRepository $prodgmtiron, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier,CountryRepository $country, BuyerRepository $buyer, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->ironqty = $ironqty;
        $this->prodgmtironorder = $prodgmtironorder;
        $this->prodgmtiron = $prodgmtiron;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->country = $country;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        
        $this->middleware('auth');
        $this->middleware('permission:view.prodgmtironqtys',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodgmtironqtys', ['only' => ['store']]);
        $this->middleware('permission:edit.prodgmtironqtys',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodgmtironqtys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
         
        $ironqtys=array();
        $rows=$this->ironqty
        ->orderBy('prod_gmt_irons.id','desc')
        ->get();
        foreach($rows as $row){
            $ironqty['id']=$row->id;
            $ironqty['iron_qc_date']=date('Y-m-d',strtotime($row->iron_qc_date));
            $ironqty['shiftname_id']=$shiftname[$row->shiftname_id];
            array_push($ironqtys,$ironqty);
        }
        echo json_encode($ironqtys);
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
    public function store(ProdGmtIronQtyRequest $request) {
		//$ironqty=$this->ironqty->create($request->except(['id']));
        //$impDocAcceptId=0;
        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            //$expInvoiceId=$request->exp_invoice_id[$index];
            if($sales_order_gmt_color_size_id && $request->qty[$index])
            {
                $ironqty = $this->ironqty->updateOrCreate(
                ['sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,'prod_gmt_iron_order_id' => $request->prod_gmt_iron_order_id],
                ['qty' => $request->qty[$index],'alter_qty' => $request->alter_qty[$index],'spot_qty' => $request->spot_qty[$index],'reject_qty' => $request->reject_qty[$index],'replace_qty' => $request->replace_qty[$index]]);
            }
        }


        if($ironqty){
            return response()->json(array('success' => true,'id' =>  $ironqty->id,'message' => 'Save Successfully'),200);
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
        $ironqty = $this->ironqty->find($id);
        $row ['fromData'] = $ironqty;
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
    public function update(ProdGmtIronQtyRequest $request, $id) {
        $ironqty=$this->ironqty->update($id,$request->except(['id']));
        if($ironqty){
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
        if($this->ironqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}