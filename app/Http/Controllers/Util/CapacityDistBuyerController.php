<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CapacityDistBuyerRepository;
use App\Repositories\Contracts\Util\CapacityDistRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\CapacityDistBuyerRequest;

class CapacityDistBuyerController extends Controller {

    private $capacitydistbuyer;
    private $capacitydist;
    private $buyer;

    public function __construct(CapacityDistBuyerRepository $capacitydistbuyer, CapacityDistRepository $capacitydist, BuyerRepository $buyer) {
        $this->capacitydistbuyer = $capacitydistbuyer;
        $this->capacitydist = $capacitydist;
        $this->buyer = $buyer;

        $this->middleware('auth');
        $this->middleware('permission:view.capacitydistbuyers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.capacitydistbuyers', ['only' => ['store']]);
        $this->middleware('permission:edit.capacitydistbuyers',   ['only' => ['update']]);
        $this->middleware('permission:delete.capacitydistbuyers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $capacitydist=array_prepend(array_pluck($this->capacitydist->get(),'name','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
      $capacitydistbuyers=array();
      $rows=$this->capacitydistbuyer->get();
      foreach($rows as $row){
        $capacitydist['id']=$row->id;
        $capacitydist['capacitydist']=$capacitydist[$row->capacity_dist_id];
        $capacitydist['buyer']=$buyer[$row->buyer_id];
        $capacitydist['distributedpercent']=$buyer[$row->distributed_percent];
        $capacitydist['prodsource']=$productionsource[$row->prod_source_id];
        $capacitydist['mktsmv']=$row->mkt_smv;
        $capacitydist['mktpcs']=$row->mkt_pcs;
        $capacitydist['prodsmv']=$row->prod_smv;
        $capacitydist['prodpcs']=$row->prod_pcs;
        array_push($capacitydistbuyers,$capacitydistbuyer);
      }
        echo json_encode($capacitydistbuyers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $capacitydist=array_prepend(array_pluck($this->capacitydist->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
        return Template::loadView("Util.CapacityDistBuyer",['capacitydist'=>$capacitydist,'buyer'=>$buyer,'productionsource'=>$productionsource]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CapacityDistBuyerRequest $request) {
        $capacitydistbuyer = $this->capacitydistbuyer->create($request->except(['id']));
        if ($capacitydistbuyer) {
            return response()->json(array('success' => true, 'id' => $capacitydistbuyer->id, 'message' => 'Save Successfully'), 200);
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
        $capacitydistbuyer = $this->capacitydistbuyer->find($id);
        $row ['fromData'] = $capacitydistbuyer;
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
    public function update(CapacityDistBuyerRequest $request, $id) {
        $capacitydistbuyer = $this->capacitydistbuyer->update($id, $request->except(['id']));
        if ($capacitydistbuyer) {
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
        if ($this->capacitydistbuyer->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
