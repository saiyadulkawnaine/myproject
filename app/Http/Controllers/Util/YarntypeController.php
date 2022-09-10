<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\YarntypeRepository;
use App\Library\Template;
use App\Http\Requests\YarntypeRequest;

class YarntypeController extends Controller {

    private $yarntype;

    public function __construct(YarntypeRepository $yarntype) {
        $this->yarntype = $yarntype;
        $this->middleware('auth');
        $this->middleware('permission:view.yarntypes',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.yarntypes', ['only' => ['store']]);
        $this->middleware('permission:edit.yarntypes',   ['only' => ['update']]);
        $this->middleware('permission:delete.yarntypes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $yarntypes=array();
			$rows=$this->yarntype->get();
			foreach ($rows as $row) {
  				$yarntype['id']=$row->id;
  				$yarntype['name']=$row->name;
  				$yarntype['code']=$row->code;
  				array_push($yarntypes,$yarntype);
  			}
        echo json_encode($this->yarntype->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return Template::loadView("Util.Yarntype");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(YarntypeRequest $request) {
        $yarntype = $this->yarntype->create($request->except(['id']));
        if ($yarntype) {
            return response()->json(array('success' => true, 'id' => $yarntype->id, 'message' => 'Save Successfully'), 200);
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
        $yarntype = $this->yarntype->find($id);
        $row ['fromData'] = $yarntype;
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
    public function update(YarntypeRequest $request, $id) {
        $yarntype = $this->yarntype->update($id, $request->except(['id']));
        if ($yarntype) {
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
        if ($this->yarntype->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
