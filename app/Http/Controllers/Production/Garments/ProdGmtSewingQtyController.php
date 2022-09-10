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
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;



use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtSewingQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtSewingQtyController extends Controller {

    private $company;
    private $sewingqty;
    private $location;
    private $buyer;

    public function __construct(ProdGmtSewingQtyRepository $sewingqty, ProdGmtSewingOrderRepository $prodgmtsewingorder,ProdGmtSewingRepository $prodgmtsewing, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier,CountryRepository $country, BuyerRepository $buyer, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->sewingqty = $sewingqty;
        $this->prodgmtsewingorder = $prodgmtsewingorder;
        $this->prodgmtsewing = $prodgmtsewing;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->country = $country;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            $this->middleware('permission:view.prodgmtsewingqtys',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtsewingqtys', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtsewingqtys',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtsewingqtys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
         
        $sewingqtys=array();
        $rows=$this->sewingqty
        ->orderBy('prod_gmt_sewings.id','desc')
        ->get();
        foreach($rows as $row){
            $sewingqty['id']=$row->id;
            $sewingqty['sew_qc_date']=date('Y-m-d',strtotime($row->sew_qc_date));
            $sewingqty['shiftname_id']=$shiftname[$row->shiftname_id];
            array_push($sewingqtys,$sewingqty);
        }
        echo json_encode($sewingqtys);
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
    public function store(ProdGmtSewingQtyRequest $request) {
		//$sewingqty=$this->sewingqty->create($request->except(['id']));
        //$impDocAcceptId=0;
        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            //$expInvoiceId=$request->exp_invoice_id[$index];
            if($sales_order_gmt_color_size_id && $request->qty[$index])
            {
                $sewingqty = $this->sewingqty->updateOrCreate(
                ['sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,'prod_gmt_sewing_order_id' => $request->prod_gmt_sewing_order_id],
                ['qty' => $request->qty[$index],'alter_qty' => $request->alter_qty[$index],'spot_qty' => $request->spot_qty[$index],'reject_qty' => $request->reject_qty[$index],'replace_qty' => $request->replace_qty[$index]]);
            }
        }


        if($sewingqty){
            return response()->json(array('success' => true,'id' =>  $sewingqty->id,'message' => 'Save Successfully'),200);
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
        $sewingqty = $this->sewingqty->find($id);
        $row ['fromData'] = $sewingqty;
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
    public function update(ProdGmtSewingQtyRequest $request, $id) {
        $sewingqty=$this->sewingqty->update($id,$request->except(['id']));
        if($sewingqty){
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
        if($this->sewingqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}