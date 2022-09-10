<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\DelaycauseRepository;
use App\Library\Template;
use App\Http\Requests\DelaycauseRequest;

class DelaycauseController extends Controller {

    private $delaycause;

    public function __construct(DelaycauseRepository $delaycause) {
        $this->delaycause = $delaycause;
        $this->middleware('auth');
        $this->middleware('permission:view.delaycauses',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.delaycauses', ['only' => ['store']]);
        $this->middleware('permission:edit.delaycauses',   ['only' => ['update']]);
        $this->middleware('permission:delete.delaycauses', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $delayfor=array_prepend(config('bprs.delayfor'),'-Select-','');
      $delaycauses=array();
      $rows=$this->delaycause->get();
      foreach ($rows as $row) {
        $delaycause['id']=$row->id;
        $delaycause['name']=$row->name;
        $delaycause['delayfor']=$delayfor[$row->delay_for_id];
        array_push($delaycauses,$delaycause);
      }
        echo json_encode($delaycauses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $delayfor=array_prepend(config('bprs.delayfor'),'-Select-','');
        return Template::loadView("Util.Delaycause",['delayfor'=>$delayfor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DelaycauseRequest $request) {
        $delaycause = $this->delaycause->create($request->except(['id']));
        if ($delaycause) {
            return response()->json(array('success' => true, 'id' => $delaycause->id, 'message' => 'Save Successfully'), 200);
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
        $delaycause = $this->delaycause->find($id);
        $row ['fromData'] = $delaycause;
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
    public function update(DelaycauseRequest $request, $id) {
        $delaycause = $this->delaycause->update($id, $request->except(['id']));
        if ($delaycause) {
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
        if ($this->delaycause->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
