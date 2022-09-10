<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerKnitChargeRepository;
use App\Repositories\Contracts\Util\KnitChargeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\BuyerKnitChargeRequest;

class BuyerKnitChargeController extends Controller {

    private $buyerknitcharge;
    private $knitcharge;
    private $buyer;

    public function __construct(BuyerKnitChargeRepository $buyerknitcharge,KnitChargeRepository $knitcharge,BuyerRepository $buyer) {
        $this->buyerknitcharge = $buyerknitcharge;
        $this->knitcharge = $knitcharge;
        $this->buyer = $buyer;

        $this->middleware('auth');
        $this->middleware('permission:view.buyerknitcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.buyerknitcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.buyerknitcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.buyerknitcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $buyerknitcharges=array();
        $rows=$this->buyerknitcharge->get();
        foreach ($rows as $row) {
          $buyerknitcharge['id']=$row->id;
          $buyerknitcharge['knitcharge_id']=$row->knit_charge_id;
          $buyerknitcharge['rate']=$row->rate;
          $buyerknitcharge['buyer']=$buyer[$row->buyer_id];
          array_push($buyerknitcharges,$buyerknitcharge);
        }
        echo json_encode($buyerknitcharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $knitcharge=array_prepend(array_pluck($this->knitcharge-get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.BuyerKnitCharge",['knitcharge'=>$knitcharge,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyerKnitChargeRequest $request) {
        $buyerknitcharge = $this->buyerknitcharge->create($request->except(['id']));
        if ($buyerknitcharge) {
            return response()->json(array('success' => true, 'id' => $buyerknitcharge->id, 'message' => 'Save Successfully'), 200);
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
        $buyerknitcharge = $this->buyerknitcharge->find($id);
        $row ['fromData'] = $buyerknitcharge;
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
    public function update(BuyerKnitChargeRequest $request, $id) {
        $buyerknitcharge = $this->buyerknitcharge->update($id, $request->except(['id']));
        if ($buyerknitcharge) {
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
        if ($this->buyerknitcharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
