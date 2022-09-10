<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\GmtsProcessLossPerRepository;
use App\Repositories\Contracts\Util\GmtsProcessLossRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Template;
use App\Http\Requests\GmtsProcessLossPerRequest;

class GmtsProcessLossPerController extends Controller {

    private $gmtsprocesslossper;
    private $gmtprocessloss;
    private $productionprocess;
    private $embelishmenttype;

    public function __construct(GmtsProcessLossPerRepository $gmtsprocesslossper,GmtsProcessLossRepository $gmtprocessloss, ProductionProcessRepository $productionprocess, EmbelishmentTypeRepository $embelishmenttype) {
      $this->gmtsprocesslossper = $gmtsprocesslossper;
      $this->gmtprocessloss = $gmtprocessloss;
      $this->productionprocess = $productionprocess;
      $this->embelishmenttype = $embelishmenttype;

      $this->middleware('auth');
      $this->middleware('permission:view.gmtsprocesslosspers',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.gmtsprocesslosspers', ['only' => ['store']]);
      $this->middleware('permission:edit.gmtsprocesslosspers',   ['only' => ['update']]);
      $this->middleware('permission:delete.gmtsprocesslosspers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      //$productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-','');
      //$embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->get(),'name','id'),'-Select-','');
      $gmtsprocesslossespers=array();
      $rows=$this->gmtsprocesslossper
	   ->join('gmts_process_losses',function($join){
		  $join->on('gmts_process_losses.id','=','gmts_process_loss_pers.gmts_process_loss_id');
	   })
	  ->join('production_processes',function($join){
		  $join->on('production_processes.id','=','gmts_process_loss_pers.production_process_id');
	   })
	   ->leftJoin('embelishment_types',function($join){
		  $join->on('embelishment_types.id','=','gmts_process_loss_pers.embelishment_type_id');
	   })
	   ->where([['gmts_process_loss_pers.gmts_process_loss_id','=',request('gmts_process_loss_id',0)]])
	   ->orderBy('gmts_process_loss_pers.id','desc')
	  ->get([
	  'gmts_process_loss_pers.*',
	  'production_processes.process_name as productionprocess',
	  'embelishment_types.name as embelishmenttype'
	  ]);
      /*foreach($rows as $row){
          $gmtsprocesslossesper['id']=$row->id;
          $gmtsprocesslossesper['productionprocess'] =  $productionprocess[$row->production_process_id];
          $gmtsprocesslossesper['embelishmenttype'] =  $embelishmenttype[$row->embelishment_type_id];
          $gmtsprocesslossesper['process_loss_per'] =  $row->process_loss_per;
          array_push($gmtsprocesslossespers,$gmtsprocesslossesper);

      }*/

        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return Template::loadView("Util.GmtsProcessLossPer");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GmtsProcessLossPerRequest $request) {
        $gmtsprocesslossper = $this->gmtsprocesslossper->create($request->except(['id']));
        if ($gmtsprocesslossper) {
            return response()->json(array('success' => true, 'id' => $gmtsprocesslossper->id, 'message' => 'Save Successfully'), 200);
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
        $gmtsprocesslossper = $this->gmtsprocesslossper->find($id);
        $row ['fromData'] = $gmtsprocesslossper;
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
    public function update(GmtsProcessLossPerRequest $request, $id) {
        $gmtsprocesslossper = $this->gmtsprocesslossper->update($id, $request->except(['id']));
        if ($gmtsprocesslossper) {
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
        if ($this->gmtsprocesslossper->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
