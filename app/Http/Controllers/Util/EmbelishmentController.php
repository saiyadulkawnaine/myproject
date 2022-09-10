<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Library\Template;
use App\Http\Requests\EmbelishmentRequest;

class EmbelishmentController extends Controller {

    private $embelishment;
	private $productionprocess;

    public function __construct(EmbelishmentRepository $embelishment,ProductionProcessRepository $productionprocess) {
        $this->embelishment = $embelishment;
		$this->productionprocess=$productionprocess;
        $this->middleware('auth');
        $this->middleware('permission:view.embelishments',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.embelishments', ['only' => ['store']]);
        $this->middleware('permission:edit.embelishments',   ['only' => ['update']]);
        $this->middleware('permission:delete.embelishments', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        echo json_encode($this->embelishment->orderBy('id','desc')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'-Select-','');
	  $productionprocess=array_prepend(array_pluck($this->productionprocess->whereIn('production_area_id', [25,45,50,51,58,60])->get(),'process_name','id'),'-Select-','');
        return Template::loadView("Util.Embelishment",['embelishment'=>$embelishment,'productionprocess'=>$productionprocess]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmbelishmentRequest $request) {
        $embelishment = $this->embelishment->create($request->except(['id']));
        if ($embelishment) {
            return response()->json(array('success' => true, 'id' => $embelishment->id, 'message' => 'Save Successfully'), 200);
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
        $embelishment = $this->embelishment->find($id);
        $row ['fromData'] = $embelishment;
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
    public function update(EmbelishmentRequest $request, $id) {
        $embelishment = $this->embelishment->update($id, $request->except(['id']));
        if ($embelishment) {
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
        if ($this->embelishment->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
