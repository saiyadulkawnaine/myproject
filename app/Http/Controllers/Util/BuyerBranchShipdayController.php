<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerBranchShipdayRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Library\Template;
use App\Http\Requests\BuyerBranchShipdayRequest;

class BuyerBranchShipdayController extends Controller {

    private $buyerbranchshipday;
    private $buyer;
    private $buyerbranch;

    public function __construct(BuyerBranchShipdayRepository $buyerbranchshipday, BuyerRepository $buyer, BuyerBranchRepository $buyerbranch) {
        $this->buyerbranchshipday = $buyerbranchshipday;
        $this->buyer = $buyer;
        $this->buyerbranch = $buyerbranch;

        $this->middleware('auth');
        $this->middleware('permission:view.buyerbranchshipdays',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.buyerbranchshipdays', ['only' => ['store']]);
        $this->middleware('permission:edit.buyerbranchshipdays',   ['only' => ['update']]);
        $this->middleware('permission:delete.buyerbranchshipdays', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $buyerbranch=array_prepend(array_pluck($this->buyerbranch->get(),'name','id'),'-Select-','');
        $buyerbranchshipdays=array();
        $rows=$this->buyerbranchshipday->get();
        foreach ($rows as $row) {
          $buyerbranchshipday['id']=$row->id;
          $buyerbranchshipday['dayname']=$row->day_name;
          $buyerbranchshipday['buyer']=$buyer[$row->buyer_id];
          $buyerbranchshipday['buyerbranch']=$buyerbranch[$row->buyer_branch_id];
          array_push($buyerbranchshipdays,$buyerbranchshipday);
        }
        echo json_encode($buyerbranchshipdays);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $buyerbranch=array_prepend(array_pluck($this->buyerbranch->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.BuyerBranchShipday",['buyer'=>$buyer, 'buyerbranch'=>$buyerbranch]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyerBranchShipdayRequest $request) {
        $buyerbranchshipday = $this->buyerbranchshipday->create($request->except(['id']));
        if ($buyerbranchshipday) {
            return response()->json(array('success' => true, 'id' => $buyerbranchshipday->id, 'message' => 'Save Successfully'), 200);
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
        $buyerbranchshipday = $this->buyerbranchshipday->find($id);
        $row ['fromData'] = $buyerbranchshipday;
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
    public function update(BuyerBranchShipdayRequest $request, $id) {
        $buyerbranchshipday = $this->buyerbranchshipday->update($id, $request->except(['id']));
        if ($buyerbranchshipday) {
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
        if ($this->buyerbranchshipday->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
