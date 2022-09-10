<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpriceRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpricedtlRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingMktCostQpricedtlRequest;

class SoDyeingMktCostQpricedtlController extends Controller {

   
    private $sodyeingmktcostqprice;
    private $sodyeingmktcostqpricedtl;
    private $sodyeingmktcost;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $keycontrol;


    public function __construct(
        SoDyeingMktCostQpriceRepository $sodyeingmktcostqprice,
        SoDyeingMktCostQpricedtlRepository $sodyeingmktcostqpricedtl,
        SoDyeingMktCostRepository $sodyeingmktcost,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        UomRepository $uom,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        KeycontrolRepository $keycontrol
    ) {
        $this->sodyeingmktcostqprice = $sodyeingmktcostqprice;
        $this->sodyeingmktcostqpricedtl = $sodyeingmktcostqpricedtl;
        $this->sodyeingmktcost = $sodyeingmktcost;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->keycontrol = $keycontrol;

        $this->middleware('auth');
      
        //$this->middleware('permission:view.sodyeingmktcostqpricedtls',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingmktcostqpricedtls', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingmktcostqpricedtls',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingmktcostqpricedtls', ['only' => ['destroy']]);
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

    public function store(SoDyeingMktCostQpricedtlRequest $request) {

        $approved=$this->sodyeingmktcostqprice->find($request->so_dyeing_mkt_cost_qprice_id);
        if($approved->final_approved_by){
            return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Save/Update/Delete not possible '), 200);
        }

        foreach($request->so_dyeing_mkt_cost_fab_id as $index=>$so_dyeing_mkt_cost_fab_id){
            if($so_dyeing_mkt_cost_fab_id && $request->quoted_price_bdt[$index])
            {
                $mktcostqpricedtl = $this->sodyeingmktcostqpricedtl->updateOrCreate(
                [
                    'so_dyeing_mkt_cost_fab_id' => $so_dyeing_mkt_cost_fab_id,
                    'so_dyeing_mkt_cost_qprice_id' => $request->so_dyeing_mkt_cost_qprice_id
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
        $rows=$this->sodyeingmktcostqpricedtl->find($id);
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

    public function update(SoDyeingMktCostQpricedtlRequest $request, $id) {
        $sodyeingmktcostqpricedtl=$this->sodyeingmktcostqpricedtl->update($id,$request->except(['id']));
        if($sodyeingmktcostqpricedtl){
            return response()->json(array('success' => true,'id' => $id,'so_dyeing_mkt_cost_id' => $request->so_dyeing_mkt_cost_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function destroy($id) {
        $mktcostqpricedetail=$this->sodyeingmktcostqpricedtl->find($id);
        $approved=$this->sodyeingmktcostqprice->find($mktcostqpricedetail->so_dyeing_mkt_cost_qprice_id);
        if($approved->final_approved_by){
            return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Save/Update/Delete not possible '), 200);
        }

        if($this->sodyeingmktcostqpricedtl->delete($id)){
           return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}