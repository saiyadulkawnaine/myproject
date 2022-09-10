<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ResourceRepository;
use App\Library\Template;
use App\Http\Requests\ResourceRequest;

class ResourceController extends Controller {

    private $resource;

    public function __construct(ResourceRepository $resource) {
        $this->resource = $resource;

        $this->middleware('auth');
        $this->middleware('permission:view.resources',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.resources', ['only' => ['store']]);
        $this->middleware('permission:edit.resources',   ['only' => ['update']]);
        $this->middleware('permission:delete.resources', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $resourcemeans=array_prepend(config('bprs.resourcemeans'),'-Select-','');
      $resources=array();
      $rows=$this->resource->orderBy('resources.sort_id')->get();
      foreach ($rows as $row) {
        $resource['id']=$row->id;
        $resource['name']=$row->name;
        $resource['code']=$row->code;
        $resource['sort_id']=$row->sort_id;
        $resource['resourcemeans']=$resourcemeans[$row->resource_means_id];
        array_push($resources,$resource);
      }
        echo json_encode($resources);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $resourcemeans=array_prepend(config('bprs.resourcemeans'),'-Select-','');
        return Template::loadView("Util.Resource",['resourcemeans'=>$resourcemeans]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResourceRequest $request) {
        $resource = $this->resource->create($request->except(['id']));
        if ($resource) {
            return response()->json(array('success' => true, 'id' => $resource->id, 'message' => 'Save Successfully'), 200);
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
        $resource = $this->resource->find($id);
        $row ['fromData'] = $resource;
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
    public function update(ResourceRequest $request, $id) {
        $resource = $this->resource->update($id, $request->except(['id']));
        if ($resource) {
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
        if ($this->resource->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
