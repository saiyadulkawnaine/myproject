<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CapacityDistBuyerTeamRepository;
use App\Repositories\Contracts\Util\CapacityDistBuyerRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Library\Template;
use App\Http\Requests\CapacityDistBuyerTeamRequest;

class CapacityDistBuyerTeamController extends Controller {

    private $capacitydistbuyerteam;
    private $capacitydistbuyer;
    private $teammember;

    public function __construct(CapacityDistBuyerTeamRepository $capacitydistbuyerteam, CapacityDistBuyerRepository $capacitydistbuyer, TeammemberRepository $teammember) {
        $this->capacitydistbuyerteam = $capacitydistbuyerteam;
        $this->capacitydistbuyer = $capacitydistbuyer;
        $this->teammember = $teammember;

        $this->middleware('auth');
        $this->middleware('permission:view.capacitydistbuyerteams',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.capacitydistbuyerteams', ['only' => ['store']]);
        $this->middleware('permission:edit.capacitydistbuyerteams',   ['only' => ['update']]);
        $this->middleware('permission:delete.capacitydistbuyerteams', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $capacitydistbuyer=array_prepend(array_pluck($this->capacitydistbuyer->get(),'name','id'),'-Select-','');
      $teammember=array_prepend(array_pluck($this->teammember->get(),'name','id'),'-Select-','');
      $capacitydistbuyerteams=array();
      $rows=$this->capacitydistbuyerteam->get();
      foreach($rows as $row){
        $capacitydist['id']=$row->id;
        $capacitydist['capacitydistbuyer']=$capacitydistbuyer[$row->capacity_dist_buyer_id];
        $capacitydist['teammember']=$teammember[$row->distributed_percent];
        $capacitydist['distributedpercent']=$row->distributed_percent;
        $capacitydist['mktsmv']=$row->mkt_smv;
        $capacitydist['mktpcs']=$row->mkt_pcs;
        $capacitydist['prodsmv']=$row->prod_smv;
        $capacitydist['prodpcs']=$row->prod_pcs;
        array_push($capacitydistbuyerteams,$capacitydistbuyerteam);
      }
        echo json_encode($capacitydistbuyerteams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $capacitydistbuyer=array_prepend(array_pluck($this->capacitydistbuyer->get(),'name','id'),'-Select-','');
        $teammember=array_prepend(array_pluck($this->teammember->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.CapacityDistBuyerTeam",['capacitydistbuyer'=>$capacitydistbuyer,'teammember'=>$teammember]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CapacityDistBuyerTeamRequest $request) {
        $capacitydistbuyerteam = $this->capacitydistbuyerteam->create($request->except(['id']));
        if ($capacitydistbuyerteam) {
            return response()->json(array('success' => true, 'id' => $capacitydistbuyerteam->id, 'message' => 'Save Successfully'), 200);
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
        $capacitydistbuyerteam = $this->capacitydistbuyerteam->find($id);
        $row ['fromData'] = $capacitydistbuyerteam;
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
    public function update(CapacityDistBuyerTeamRequest $request, $id) {
        $capacitydistbuyerteam = $this->capacitydistbuyerteam->update($id, $request->except(['id']));
        if ($capacitydistbuyerteam) {
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
        if ($this->capacitydistbuyerteam->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
