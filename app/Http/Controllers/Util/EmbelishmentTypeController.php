<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Library\Template;
use App\Http\Requests\EmbelishmentTypeRequest;

class EmbelishmentTypeController extends Controller {

    private $embelishmenttype;
    private $embelishment;

    public function __construct(EmbelishmentTypeRepository $embelishmenttype,EmbelishmentRepository $embelishment) {
        $this->embelishmenttype = $embelishmenttype;
        $this->embelishment = $embelishment;

        $this->middleware('auth');
          $this->middleware('permission:view.embelishmenttypes',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.embelishmenttypes', ['only' => ['store']]);
        $this->middleware('permission:edit.embelishmenttypes',   ['only' => ['update']]);
        $this->middleware('permission:delete.embelishmenttypes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'-Select-','');
      $embelishmenttypes=array();
      $rows=$this->embelishmenttype
	  ->when(request('embelishment_id'), function ($q) {
		return $q->where('embelishment_id', '=', request('embelishment_id', 0));
		})
	  ->orderBy('id','desc')
	  ->get();
      foreach ($rows as $row) {
        $embelishmenttype['id']=$row->id;
        $embelishmenttype['name']=$row->name;
        $embelishmenttype['code']=$row->code;
        $embelishmenttype['embelishment']=$embelishment[$row->embelishment_id];
        array_push($embelishmenttypes,$embelishmenttype);
      }
        echo json_encode($embelishmenttypes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.EmbelishmentType",['embelishment'=>$embelishment]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmbelishmentTypeRequest $request) {
        $embelishmenttype = $this->embelishmenttype->create($request->except(['id']));
        if ($embelishmenttype) {
            return response()->json(array('success' => true, 'id' => $embelishmenttype->id, 'message' => 'Save Successfully'), 200);
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
        $embelishmenttype = $this->embelishmenttype->find($id);
        $row ['fromData'] = $embelishmenttype;
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
    public function update(EmbelishmentTypeRequest $request, $id) {
        $embelishmenttype = $this->embelishmenttype->update($id, $request->except(['id']));
        if ($embelishmenttype) {
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
        if ($this->embelishmenttype->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
