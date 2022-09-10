<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\WeightMachineRepository;
//use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\WeightMachineRequest;

class WeightMachineController extends Controller
{
    private $weightmachine;
	  //private $user;

    public function __construct(WeightMachineRepository $weightmachine) {
        $this->weightmachine = $weightmachine;
		   //$this->user = $user;,UserRepository $user
        $this->middleware('auth');
        $this->middleware('permission:view.weightmachines',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.weightmachines', ['only' => ['store']]);
        $this->middleware('permission:edit.weightmachines',   ['only' => ['update']]);
        $this->middleware('permission:delete.weightmachines', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $rows=$this->weightmachine->get();
     
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
        return Template::loadView("Util.WeightMachine",[]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WeightMachineRequest $request) {
        $weightmachine = $this->weightmachine->create($request->except(['id']));
        if ($weightmachine) {
            return response()->json(array('success' => true, 'id' => $weightmachine->id, 'message' => 'Save Successfully'), 200);
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
        $weightmachine = $this->weightmachine->find($id);
        $row ['fromData'] = $weightmachine;
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
    public function update(WeightMachineRequest $request, $id) {
        $weightmachine = $this->weightmachine->update($id, $request->except(['id']));
        if ($weightmachine) {
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
        if ($this->weightmachine->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
