<?php
namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonDetailRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonEntryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonDetailQtyRepository;
use App\Repositories\Contracts\Marketing\StylePkgRatioRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;

use App\Http\Requests\Production\Garments\ProdGmtCartonDetailQtyRequest;
use App\Library\Template;

class ProdGmtCartonDetailQtyController extends Controller {

    private $cartondetailqty;
    private $prodgmtcartondetail;
    private $country;
    private $salesordercountry;
    private $stylepkg;
    private $stylegmtcolorsize;


    public function __construct( ProdGmtCartonDetailQtyRepository $cartondetailqty, StylePkgRatioRepository $stylepkg, StyleGmtColorSizeRepository $stylegmtcolorsize, ProdGmtCartonDetailRepository $prodgmtcartondetail, CountryRepository $country, SalesOrderCountryRepository $salesordercountry,ProdGmtCartonEntryRepository $prodgmtcarton) {
        $this->prodgmtcartondetail = $prodgmtcartondetail;
        $this->prodgmtcarton = $prodgmtcarton;
        $this->cartondetailqty = $cartondetailqty;
        $this->salesordercountry = $salesordercountry;
        $this->country = $country;
        $this->stylepkg = $stylepkg;
        $this->stylegmtcolorsize = $stylegmtcolorsize;


        $this->middleware('auth');
            $this->middleware('permission:view.prodgmtcartondetailqties',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtcartondetailqties', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtcartondetailqties',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtcartondetailqties', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         
        $cartondetailqtys=array();
        $rows=$this->cartondetailqty
        ->where([['prod_gmt_carton_details.id',request('prod_gmt_carton_detail_id',0)]])
        ->orderBy('prod_gmt_carton_detail_qties.id','desc')
        ->get();
        foreach($rows as $row){
            $cartondetailqty['id']=$row->id;
            $cartondetailqty['prod_gmt_carton_detail_id']=$row->prod_gmt_carton_detail_id;
            $cartondetailqty['style_gmt_color_size_id']=$row->style_gmt_color_size_id;
            $cartondetailqty['style_pkg_ratio_id']=$row->style_pkg_ratio_id;
            $cartondetailqty['qty']=$row->qty;

            array_push($cartondetailqtys,$cartondetailqty);
        }
        echo json_encode($cartondetailqtys);
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
    public function store(ProdGmtCartonDetailQtyRequest $request) {
		$cartondetailqty=$this->cartondetailqty->create($request->except(['id']));
        if($cartondetailqty){
            return response()->json(array('success' => true,'id' =>  $cartondetailqty->id,'message' => 'Save Successfully'),200);
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
        $cartondetailqty = $this->cartondetailqty->find($id);
        $row ['fromData'] = $cartondetailqty;
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
    public function update(ProdGmtCartonDetailQtyRequest $request, $id) {
        $cartondetailqty=$this->cartondetailqty->update($id,$request->except(['id']));
        if($cartondetailqty){
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
        if($this->cartondetailqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}