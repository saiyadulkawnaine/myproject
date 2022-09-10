<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\FabricprocesslossRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;

use App\Library\Template;
use App\Http\Requests\FabricprocesslossRequest;

class FabricprocesslossController extends Controller {

    private $fabricprocessloss;
    private $buyer;
    private $composition;
    private $colorrange;
    private $productionprocess;

    public function __construct(FabricprocesslossRepository $fabricprocessloss,BuyerRepository $buyer, CompositionRepository $composition, ColorrangeRepository $colorrange,ProductionProcessRepository $productionprocess) {
        $this->fabricprocessloss = $fabricprocessloss;
        $this->buyer = $buyer;
        $this->composition = $composition;
        $this->colorrange = $colorrange;
        $this->productionprocess = $productionprocess;

        $this->middleware('auth');
        $this->middleware('permission:view.fabricprocesslosses',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.fabricprocesslosses', ['only' => ['store']]);
        $this->middleware('permission:edit.fabricprocesslosses',   ['only' => ['update']]);
        $this->middleware('permission:delete.fabricprocesslosses', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
      $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $fabricprocesslosses=array();
      $rows=$this->fabricprocessloss->get();
      foreach ($rows as $row) {
        $fabricprocessloss['id']=$row->id;
        $fabricprocessloss['buyer']=$buyer[$row->buyer_id];
        $fabricprocessloss['fabricnature']=$fabricnature[$row->fabric_nature_id];
        $fabricprocessloss['composition']=$composition[$row->composition_id];
        $fabricprocessloss['colorrange']=$colorrange[$row->colorrange_id];
        array_push($fabricprocesslosses,$fabricprocessloss);
      }
        echo json_encode($fabricprocesslosses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
        $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-','');
        return Template::loadView("Util.Fabricprocessloss",['buyer'=>$buyer,'fabricnature'=>$fabricnature,'composition'=>$composition,'colorrange'=>$colorrange,'productionprocess'=>$productionprocess,'productionarea'=>$productionarea]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FabricprocesslossRequest $request) {
        $fabricprocessloss = $this->fabricprocessloss->create($request->except(['id']));
        if ($fabricprocessloss) {
            return response()->json(array('success' => true, 'id' => $fabricprocessloss->id, 'message' => 'Save Successfully'), 200);
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
        $fabricprocessloss = $this->fabricprocessloss->find($id);
        $row ['fromData'] = $fabricprocessloss;
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
    public function update(FabricprocesslossRequest $request, $id) {
        $fabricprocessloss = $this->fabricprocessloss->update($id, $request->except(['id']));
        if ($fabricprocessloss) {
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
        if ($this->fabricprocessloss->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
