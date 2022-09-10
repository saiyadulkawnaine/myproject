<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerDyingChargeRepository;
use App\Repositories\Contracts\Util\DyingChargeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\BuyerDyingChargeRequest;

class BuyerDyingChargeController extends Controller {

    private $buyerdyingcharge;
    private $dyingcharge;
    private $buyer;

    public function __construct(BuyerDyingChargeRepository $buyerdyingcharge,DyingChargeRepository $dyingcharge,BuyerRepository $buyer) {
        $this->buyerdyingcharge = $buyerdyingcharge;
        $this->dyingcharge = $dyingcharge;
        $this->buyer = $buyer;

        $this->middleware('auth');
        $this->middleware('permission:view.buyerdyingcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.buyerdyingcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.buyerdyingcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.buyerdyingcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $buyerdyingcharges=array();
        $rows=$this->buyerdyingcharge->get();
        foreach ($rows as $row) {
          $buyerdyingcharge['id']=$row->id;
          $buyerdyingcharge['rate']=$row->rate;
          $buyerdyingcharge['buyer']=$buyer[$row->buyer_id];
          array_push($buyerdyingcharges,$buyerdyingcharge);
        }
        echo json_encode($buyerdyingcharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $dyingcharge=array_prepend(array_pluck($this->dyingcharge-get(),'name','id'),'-Select-',0);
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);
        return Template::loadView("Util.BuyerDyingCharge",['dyingcharge'=>$dyingcharge,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyerDyingChargeRequest $request) {
        $buyerdyingcharge = $this->buyerdyingcharge->create($request->except(['id']));
        if ($buyerdyingcharge) {
            return response()->json(array('success' => true, 'id' => $buyerdyingcharge->id, 'message' => 'Save Successfully'), 200);
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
        $buyerdyingcharge = $this->buyerdyingcharge->find($id);
        $row ['fromData'] = $buyerdyingcharge;
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
    public function update(BuyerDyingChargeRequest $request, $id) {
        $buyerdyingcharge = $this->buyerdyingcharge->update($id, $request->except(['id']));
        if ($buyerdyingcharge) {
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
        if ($this->buyerdyingcharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
