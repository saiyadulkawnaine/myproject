<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\WagevariableRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Library\Template;
use App\Http\Requests\WagevariableRequest;

class WagevariableController extends Controller
{
    private $wagevariable;
    private $productionprocess;

    public function __construct(WagevariableRepository $wagevariable,ProductionProcessRepository $productionprocess) {
        $this->wagevariable = $wagevariable;
        $this->productionprocess = $productionprocess;
        $this->middleware('auth');
        $this->middleware('permission:view.wagevariables',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.wagevariables', ['only' => ['store']]);
        $this->middleware('permission:edit.wagevariables',   ['only' => ['update']]);
        $this->middleware('permission:delete.wagevariables', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-','');
      $wagevariables=array();
      $rows=$this->wagevariable->get();
      foreach ($rows as $row) {
        $wagevariable['id']=$row->id;
        $wagevariable['name']=$row->name;
        $wagevariable['productionprocess']=$productionprocess[$row->production_process_id];
        array_push($wagevariables,$wagevariable);
      }
        echo json_encode($wagevariables);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-','');
        return Template::loadView("Util.Wagevariable",['productionprocess'=>$productionprocess]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WagevariableRequest $request) {
        $wagevariable = $this->wagevariable->create($request->except(['id']));
        if ($wagevariable) {
            return response()->json(array('success' => true, 'id' => $wagevariable->id, 'message' => 'Save Successfully'), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $wagevariable = $this->wagevariable->find($id);
        $row ['fromData'] = $wagevariable;
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
    public function update(WagevariableRequest $request, $id) {
        $wagevariable = $this->wagevariable->update($id, $request->except(['id']));
        if ($wagevariable) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->wagevariable->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
