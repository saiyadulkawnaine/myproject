<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\FabricprocesslossPercentRepository;
use App\Repositories\Contracts\Util\FabricprocesslossRepository;
use App\Library\Template;
use App\Http\Requests\FabricprocesslossPercentRequest;

class FabricprocesslossPercentController extends Controller {

    private $fabricprocesslosspercent;
    private $fabricprocessloss;

    public function __construct(FabricprocesslossPercentRepository $fabricprocesslosspercent) {
        $this->fabricprocesslosspercent = $fabricprocesslosspercent;
        //$this->fabricprocessloss = $fabricprocessloss;

        $this->middleware('auth');
        $this->middleware('permission:view.fabricprocesslosspercents',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.fabricprocesslosspercents', ['only' => ['store']]);
        $this->middleware('permission:edit.fabricprocesslosspercents',   ['only' => ['update']]);
        $this->middleware('permission:delete.fabricprocesslosspercents', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');

      $fabricprocesslosspercents=array();
      $rows=$this->fabricprocesslosspercent->get();
      foreach ($rows as $row) {
        $fabricprocesslosspercent['id']=$row->id;
        $fabricprocesslosspercent['lossarea']=$productionarea[$row->loss_area_id];
        $fabricprocesslosspercent['losspercent']=$row->loss_percent;
        array_push($fabricprocesslosspercents,$fabricprocesslosspercent);
      }
        echo json_encode($fabricprocesslosspercents);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //$fabricprocessloss=array_prepend(array_pluck($this->fabricprocessloss->get(),'name','id'),
        return Template::loadView("Util.FabricprocesslossPercent");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FabricprocesslossPercentRequest $request) {
        $fabricprocesslosspercent = $this->fabricprocesslosspercent->create($request->except(['id']));
        if ($fabricprocesslosspercent) {
            return response()->json(array('success' => true, 'id' => $fabricprocesslosspercent->id, 'message' => 'Save Successfully'), 200);
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
        $fabricprocesslosspercent = $this->fabricprocesslosspercent->find($id);
        $row ['fromData'] = $fabricprocesslosspercent;
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
    public function update(FabricprocesslossPercentRequest $request, $id) {
        $fabricprocesslosspercent = $this->fabricprocesslosspercent->update($id, $request->except(['id']));
        if ($fabricprocesslosspercent) {
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
        if ($this->fabricprocesslosspercent->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
