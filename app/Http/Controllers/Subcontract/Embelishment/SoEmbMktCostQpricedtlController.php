<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostQpriceRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostQpricedtlRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbMktCostQpricedtlRequest;

class SoEmbMktCostQpricedtlController extends Controller {

   
    private $soaopmktcostqprice;
    private $soaopmktcostqpricedtl;
    private $soaopmktcost;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $keycontrol;


    public function __construct(
        SoEmbMktCostQpriceRepository $soaopmktcostqprice,
        SoEmbMktCostQpricedtlRepository $soaopmktcostqpricedtl,
        SoEmbMktCostRepository $soaopmktcost,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        UomRepository $uom,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        KeycontrolRepository $keycontrol
    ) {
        $this->soaopmktcostqprice = $soaopmktcostqprice;
        $this->soaopmktcostqpricedtl = $soaopmktcostqpricedtl;
        $this->soaopmktcost = $soaopmktcost;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->keycontrol = $keycontrol;

        $this->middleware('auth');
      
        //$this->middleware('permission:view.soaopmktcostqpricedtls',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.soaopmktcostqpricedtls', ['only' => ['store']]);
        //$this->middleware('permission:edit.soaopmktcostqpricedtls',   ['only' => ['update']]);
        //$this->middleware('permission:delete.soaopmktcostqpricedtls', ['only' => ['destroy']]);
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

    public function store(SoEmbMktCostQpricedtlRequest $request) {
        $approved=$this->soaopmktcostqprice->find($request->so_aop_mkt_cost_qprice_id);
        if($approved->final_approved_by){
            return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Save/Update/Delete not possible '), 200);
        }
        //dd($request->All());die;
        foreach($request->so_aop_mkt_cost_param_id as $index=>$so_aop_mkt_cost_param_id){
            if($so_aop_mkt_cost_param_id && $request->quoted_price_bdt[$index])
            {
                $mktcostqpricedtl = $this->soaopmktcostqpricedtl->updateOrCreate(
                [
                    'so_aop_mkt_cost_param_id' => $so_aop_mkt_cost_param_id,
                    'so_aop_mkt_cost_qprice_id' => $request->so_aop_mkt_cost_qprice_id
                ],
                [
                    'cost_per_kg' => $request->cost_per_kg[$index],
                    'quoted_price_bdt' => $request->quoted_price_bdt[$index],
                    'quoted_price' => $request->quoted_price[$index],
                    'profit_amount_bdt' => $request->profit_amount_bdt[$index],
                    'profit_amount' => $request->profit_amount[$index],
                    'profit_per' => $request->profit_per[$index],
                    'remarks' => $request->remarks[$index],
                ]);
            }
        }
   

        if($mktcostqpricedtl){
            return response()->json(array('success' => true,'id' =>  $mktcostqpricedtl->id,'message' => 'Save Successfully'),200);
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
        $rows=$this->soaopmktcostqpricedtl->find($id);
        $row ['fromData'] = $rows;
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

    public function update(SoEmbMktCostQpricedtlRequest $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function destroy($id) {
        $qpricedtail=$this->soaopmktcostqpricedtl->find($id);
        $approved=$this->soaopmktcostqprice->find($qpricedtail->so_aop_mkt_cost_qprice_id);
        if($approved->final_approved_by){
            return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Save/Update/Delete not possible '), 200);
        }
      if($this->soaopmktcostqpricedtl->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }

}