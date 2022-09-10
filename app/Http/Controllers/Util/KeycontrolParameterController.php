<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\KeycontrolParameterRepository;
use App\Library\Template;
use App\Http\Requests\KeycontrolParameterRequest;

class KeycontrolParameterController extends Controller
{
    private $keycontrolparameter;

    public function __construct(KeycontrolParameterRepository $keycontrolparameter) {
        $this->keycontrolparameter = $keycontrolparameter;
        $this->middleware('auth');
        $this->middleware('permission:view.keycontrolparameters',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.keycontrolparameters', ['only' => ['store']]);
        $this->middleware('permission:edit.keycontrolparameters',   ['only' => ['update']]);
        $this->middleware('permission:delete.keycontrolparameters', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $keycontrolparameter=array_prepend(config('bprs.keycontrolparameter'),'-Select-','');
      $keycontrolparameters=array();
      //$rows= $this->keycontrolparameter->with('keycontrol')->get();
      $rows= $this->keycontrolparameter
      ->where([['keycontrol_id','=',request('keycontrol_id',0)]])
      ->get();
      //with('author')->get()
      foreach($rows as $row){
        $keycontrolparameter['id']=$row->id;
        $keycontrolparameter['parameter']=  $keycontrolparameter[$row->parameter_id];
        $keycontrolparameter['from_date']=date('Y-m-d',strtotime($row->from_date));
        $keycontrolparameter['to_date']=date('Y-m-d',strtotime($row->to_date));
        $keycontrolparameter['value']=  $row->value;
        $keycontrolparameter['working_hour']=  $row->keycontrol->working_hour;
        array_push($keycontrolparameters,$keycontrolparameter);
      }
        echo json_encode($keycontrolparameters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      	/* $keycontrolparameter=array_prepend(config('bprs.keycontrolparameter'),'-Select-','');
        return Template::loadView("Util\KeycontrolParameter",['keycontrolparameter'=>$keycontrolparameter]); */
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KeycontrolParameterRequest $request) {
        $keycontrolparameter= $this->keycontrolparameter->create($request->except(['id']));
        if ($keycontrolparameter) {
            return response()->json(array('success' => true, 'id' => $keycontrolparameter->id, 'message' => 'Save Successfully'), 200);
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
        $keycontrolparameter = $this->keycontrolparameter->find($id);
        $row ['fromData'] = $keycontrolparameter;
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
    public function update(KeycontrolParameterRequest $request, $id) {
        $keycontrolparameter = $this->keycontrolparameter->update($id, $request->except(['id']));
        if ($keycontrolparameter) {
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
        if ($this->keycontrolparameter->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
