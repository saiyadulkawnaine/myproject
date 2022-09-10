<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ConstructionRepository;
use App\Library\Template;
use App\Http\Requests\ConstructionRequest;

class ConstructionController extends Controller {

    private $construction;

    public function __construct(ConstructionRepository $construction) {
        $this->construction = $construction;
        $this->middleware('auth');
        $this->middleware('permission:view.constructions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.constructions', ['only' => ['store']]);
        $this->middleware('permission:edit.constructions',   ['only' => ['update']]);
        $this->middleware('permission:delete.constructions', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $constructions=array();
        $rows=$this->construction->get();
        foreach ($rows as $row) {
          $construction['id']=$row->id;
          $construction['name']=$row->name;
          $construction['fabricnature']=$fabricnature[$row->fabric_nature_id];
          array_push($constructions,$construction);
        }
        echo json_encode($constructions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        return Template::loadView("Util.Construction",['fabricnature'=>$fabricnature]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConstructionRequest $request) {
        $construction = $this->construction->create($request->except(['id']));
        if ($construction) {
            return response()->json(array('success' => true, 'id' => $construction->id, 'message' => 'Save Successfully'), 200);
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
        $construction = $this->construction->find($id);
        $row ['fromData'] = $construction;
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
    public function update(ConstructionRequest $request, $id) {
        $construction = $this->construction->update($id, $request->except(['id']));
        if ($construction) {
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
        if ($this->construction->delete($id)) {
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}
