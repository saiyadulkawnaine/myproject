<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\AutoyarnratioRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Library\Template;
use App\Http\Requests\AutoyarnratioRequest;

class AutoyarnratioController extends Controller {

    private $autoyarnratio;
    private $autoyarn;
    private $composition;
    private $yarncount;

    public function __construct(AutoyarnratioRepository $autoyarnratio, AutoyarnRepository $autoyarn, CompositionRepository $composition, YarncountRepository $yarncount) {
        $this->autoyarnratio = $autoyarnratio;
        $this->autoyarn = $autoyarn;
        $this->composition = $composition;
        $this->yarncount = $yarncount;

        $this->middleware('auth');
        $this->middleware('permission:view.autoyarnratios',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.autoyarnratios', ['only' => ['store']]);
        $this->middleware('permission:edit.autoyarnratios',   ['only' => ['update']]);
        $this->middleware('permission:delete.autoyarnratios', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
        $yarncount=array_prepend(array_pluck($this->yarncount->get(),'count','id'),'-Select-','');
        $autoyarnratios=array();
        $rows=$this->autoyarnratio
		->when(request('autoyarn_id'), function ($q) {
		return $q->where('autoyarn_id', '=', request('autoyarn_id', 0));
		})
		->orderBy('id','desc')
		->get();
        foreach ($rows as $row) {
          $autoyarnratio['id']=$row->id;
          $autoyarnratio['ratio']=$row->ratio;
          $autoyarnratio['composition']=$composition[$row->composition_id];
          $autoyarnratio['yarncount']=$yarncount[$row->yarncount_id];
          array_push($autoyarnratios,$autoyarnratio);
        }
        echo json_encode($autoyarnratios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
        $yarncount=array_prepend(array_pluck($this->yarncount->get(),'count','id'),'-Select-','');
        return Template::loadView("Util.Autoyarnratio",['composition'=>$composition, 'yarncount'=>$yarncount ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AutoyarnratioRequest $request) {
        $autoyarnratio = $this->autoyarnratio->create($request->except(['id']));
        if ($autoyarnratio) {
            return response()->json(array('success' => true, 'id' => $autoyarnratio->id, 'message' => 'Save Successfully'), 200);
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
        $autoyarnratio = $this->autoyarnratio->find($id);
        $row ['fromData'] = $autoyarnratio;
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
    public function update(AutoyarnratioRequest $request, $id) {
        $autoyarnratio = $this->autoyarnratio->update($id, $request->except(['id']));
        if ($autoyarnratio) {
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
        if ($this->autoyarnratio->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
