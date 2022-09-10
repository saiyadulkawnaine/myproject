<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ConstructionRepository;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Library\Template;
use App\Http\Requests\AutoyarnRequest;

class AutoyarnController extends Controller {

    private $autoyarn;
    private $itemclass;
    private $construction;
    private $composition;
    private $yarncount;

    public function __construct(AutoyarnRepository $autoyarn,ItemclassRepository $itemclass, ConstructionRepository $construction, CompositionRepository $composition, YarncountRepository $yarncount) {
        $this->autoyarn = $autoyarn;
        $this->itemclass = $itemclass;
        $this->construction = $construction;
        $this->composition = $composition;
        $this->yarncount = $yarncount;
        $this->middleware('auth');
        $this->middleware('permission:view.autoyarns',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.autoyarns', ['only' => ['store']]);
        $this->middleware('permission:edit.autoyarns',   ['only' => ['update']]);
        $this->middleware('permission:delete.autoyarns', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabrictype=array_prepend(config('bprs.fabrictype'),'-Select-','');
        $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
        $construction=array_prepend(array_pluck($this->construction->orderBy('id','desc')->get(),'name','id'),'-Select-','');
		$composition=$this->autoyarn->getComposition();
        $autoyarns=array();
        $rows=$this->autoyarn->orderBy('id','desc')->get();
        foreach ($rows as $row) {
          $autoyarn['id']=$row->id;
          $autoyarn['fabricnature']=$fabricnature[$row->fabric_nature_id];
          $autoyarn['fabrictype']=$row->fabric_type;
          $autoyarn['itemclass']=$itemclass[$row->itemclass_id];
          $autoyarn['construction']=$construction[$row->construction_id];
		  $autoyarn['composition']=$composition[$row->id];
          array_push($autoyarns,$autoyarn);
        }
        echo json_encode($autoyarns);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabrictype=array_prepend(config('bprs.fabrictype'),'-Select-','');
        $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
        $construction=array_prepend(array_pluck($this->construction->get(),'name','id'),'-Select-','');
        $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
        $yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
        return Template::loadView("Util.Autoyarn",['fabricnature'=>$fabricnature, 'fabrictype'=>$fabrictype,'itemclass'=>$itemclass, 'construction'=>$construction,'composition'=>$composition, 'yarncount'=>$yarncount]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AutoyarnRequest $request) {
        $autoyarn = $this->autoyarn->create($request->except(['id']));
        if ($autoyarn) {
            return response()->json(array('success' => true, 'id' => $autoyarn->id, 'message' => 'Save Successfully'), 200);
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
        $autoyarn = $this->autoyarn->find($id);
        $row ['fromData'] = $autoyarn;
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
    public function update(AutoyarnRequest $request, $id) {
        $autoyarn = $this->autoyarn->update($id, $request->except(['id']));
        if ($autoyarn) {
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
        if ($this->autoyarn->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
