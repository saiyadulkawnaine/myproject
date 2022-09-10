<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\GmtssampleRepository;
use App\Library\Template;
use App\Http\Requests\GmtssampleRequest;

class GmtssampleController extends Controller {

    private $gmtssample;

    public function __construct(GmtssampleRepository $gmtssample) {
        $this->gmtssample = $gmtssample;
        $this->middleware('auth');
        $this->middleware('permission:view.gmtssamples',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.gmtssamples', ['only' => ['store']]);
        $this->middleware('permission:edit.gmtssamples',   ['only' => ['update']]);
        $this->middleware('permission:delete.gmtssamples', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $sampletype=array_prepend(config('bprs.sampletype'),'-Select-',0);
      $gmtssamples=array();
      $rows=$this->gmtssample->get();
      foreach ($rows as $row) {
        $gmtssample['id']=$row->id;
        $gmtssample['name']=$row->name;
        $gmtssample['type']=$sampletype[$row->type_id];
        array_push($gmtssamples,$gmtssample);
      }
        echo json_encode($gmtssamples);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $sampletype=array_prepend(config('bprs.sampletype'),'-Select-',0);
        return Template::loadView("Util.Gmtssample",['sampletype'=>$sampletype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GmtssampleRequest $request) {
        $gmtssample = $this->gmtssample->create($request->except(['id']));
        if ($gmtssample) {
            return response()->json(array('success' => true, 'id' => $gmtssample->id, 'message' => 'Save Successfully'), 200);
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
        $gmtssample = $this->gmtssample->find($id);
        $row ['fromData'] = $gmtssample;
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
    public function update(GmtssampleRequest $request, $id) {
        $gmtssample = $this->gmtssample->update($id, $request->except(['id']));
        if ($gmtssample) {
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
        if ($this->gmtssample->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
