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
use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;



use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtCuttingQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtCuttingQtyController extends Controller {

    private $company;
    private $cuttingqty;
    private $location;
    private $buyer;

    public function __construct(ProdGmtCuttingQtyRepository $cuttingqty, ProdGmtCuttingOrderRepository $prodgmtcuttingorder,ProdGmtCuttingRepository $prodgmtcutting, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier,CountryRepository $country, BuyerRepository $buyer, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->cuttingqty = $cuttingqty;
        $this->prodgmtcuttingorder = $prodgmtcuttingorder;
        $this->prodgmtcutting = $prodgmtcutting;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->country = $country;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtcuttingqtys',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtcuttingqtys', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtcuttingqtys',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtcuttingqtys', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
         
        $cuttingqtys=array();
        $rows=$this->cuttingqty
        ->orderBy('prod_gmt_cuttings.id','desc')
        ->get();
        foreach($rows as $row){
            $cuttingqty['id']=$row->id;
            $cuttingqty['sew_qc_date']=date('Y-m-d',strtotime($row->sew_qc_date));
            $cuttingqty['shiftname_id']=$shiftname[$row->shiftname_id];
            array_push($cuttingqtys,$cuttingqty);
        }
        echo json_encode($cuttingqtys);
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
    public function store(ProdGmtCuttingQtyRequest $request) {
		//$cuttingqty=$this->cuttingqty->create($request->except(['id']));
        //$impDocAcceptId=0;
        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            //$expInvoiceId=$request->exp_invoice_id[$index];
            if($sales_order_gmt_color_size_id && $request->qty[$index])
            {
                $cuttingqty = $this->cuttingqty->updateOrCreate(
                ['sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,'prod_gmt_cutting_order_id' => $request->prod_gmt_cutting_order_id],
                ['qty' => $request->qty[$index],'alter_qty' => $request->alter_qty[$index],'spot_qty' => $request->spot_qty[$index],'reject_qty' => $request->reject_qty[$index],'replace_qty' => $request->replace_qty[$index]]);
            }
        }


        if($cuttingqty){
            return response()->json(array('success' => true,'id' =>  $cuttingqty->id,'message' => 'Save Successfully'),200);
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
        $cuttingqty = $this->cuttingqty->find($id);
        $row ['fromData'] = $cuttingqty;
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
    public function update(ProdGmtCuttingQtyRequest $request, $id) {
        $cuttingqty=$this->cuttingqty->update($id,$request->except(['id']));
        if($cuttingqty){
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
        if($this->cuttingqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}