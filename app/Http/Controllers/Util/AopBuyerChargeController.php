<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\AopBuyerChargeRepository;
use App\Repositories\Contracts\Util\AopChargeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\AopBuyerChargeRequest;

class AopBuyerChargeController extends Controller {

    private $aopbuyercharge;
    private $aopcharge;
    private $buyer;

    public function __construct(AopBuyerChargeRepository $aopbuyercharge,AopChargeRepository $aopcharge,BuyerRepository $buyer) {
        $this->aopbuyercharge = $aopbuyercharge;
        $this->aopcharge = $aopcharge;
        $this->buyer = $buyer;

        $this->middleware('auth');
        $this->middleware('permission:view.aopbuyercharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.aopbuyercharges', ['only' => ['store']]);
        $this->middleware('permission:edit.aopbuyercharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.aopbuyercharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $aopbuyercharges=array();
        $rows=$this->aopbuyercharge->get();
        foreach ($rows as $row) {
          $aopbuyercharge['id']=$row->id;
          $aopbuyercharge['aopcharge_id']=$row->aop_charge_id;
          $aopbuyercharge['rate']=$row->rate;
          $aopbuyercharge['buyer']=$buyer[$row->buyer_id];
          array_push($aopbuyercharges,$aopbuyercharge);
        }
        echo json_encode($aopbuyercharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $aopcharge=array_prepend(array_pluck($this->aopcharge-get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.BuyerAopCharge",['aopcharge'=>$aopcharge,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AopBuyerChargeRequest $request) {
        $aopbuyercharge = $this->aopbuyercharge->create($request->except(['id']));
        if ($aopbuyercharge) {
            return response()->json(array('success' => true, 'id' => $aopbuyercharge->id, 'message' => 'Save Successfully'), 200);
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
        $aopbuyercharge = $this->aopbuyercharge->find($id);
        $row ['fromData'] = $aopbuyercharge;
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
    public function update(AopBuyerChargeRequest $request, $id) {
        $aopbuyercharge = $this->aopbuyercharge->update($id, $request->except(['id']));
        if ($aopbuyercharge) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->aopbuyercharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
