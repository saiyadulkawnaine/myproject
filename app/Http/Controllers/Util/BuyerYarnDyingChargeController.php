<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerYarnDyingChargeRepository;
use App\Repositories\Contracts\Util\YarnDyingChargeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\BuyerYarnDyingChargeRequest;

class BuyerYarnDyingChargeController extends Controller {

    private $buyeryarndyingcharge;
    private $yarndyingcharge;
    private $buyer;

    public function __construct(BuyerYarnDyingChargeRepository $buyeryarndyingcharge,YarnDyingChargeRepository $yarndyingcharge,BuyerRepository $buyer) {
        $this->buyeryarndyingcharge = $buyeryarndyingcharge;
        $this->yarndyingcharge = $yarndyingcharge;
        $this->buyer = $buyer;
        
        $this->middleware('auth');
        $this->middleware('permission:view.buyeryarndyingcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.buyeryarndyingcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.buyeryarndyingcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.buyeryarndyingcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $buyeryarndyingcharges=array();
        $rows=$this->buyeryarndyingcharge->get();
        foreach ($rows as $row) {
          $buyeryarndyingcharge['id']=$row->id;
          $buyeryarndyingcharge['rate']=$row->rate;
          $buyeryarndyingcharge['buyer']=$buyer[$row->buyer_id];
          array_push($buyeryarndyingcharges,$buyeryarndyingcharge);
        }

        echo json_encode($buyeryarndyingcharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $yarndyingcharge=array_prepend(array_pluck($this->yarndyingcharge-get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.BuyerYarnDyingCharge",['yarndyingcharge'=>$yarndyingcharge,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyerYarnDyingChargeRequest $request) {
        $buyeryarndyingcharge = $this->buyeryarndyingcharge->create($request->except(['id']));
        if ($buyeryarndyingcharge) {
            return response()->json(array('success' => true, 'id' => $buyeryarndyingcharge->id, 'message' => 'Save Successfully'), 200);
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
        $buyeryarndyingcharge = $this->buyeryarndyingcharge->find($id);
        $row ['fromData'] = $buyeryarndyingcharge;
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
    public function update(BuyerYarnDyingChargeRequest $request, $id) {
        $buyeryarndyingcharge = $this->buyeryarndyingcharge->update($id, $request->except(['id']));
        if ($buyeryarndyingcharge) {
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
        if ($this->buyeryarndyingcharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
