<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\WeightMachineUserRepository;
use App\Repositories\Contracts\Util\WeightMachineRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\WeightMachineUserRequest;

class WeightMachineUserController extends Controller {

    private $weightmachineuser;
    private $weightmachine;
    private $user;

    public function __construct(WeightMachineUserRepository $weightmachineuser, UserRepository $user,WeightMachineRepository $weightmachine) {
        $this->weightmachineuser = $weightmachineuser;
		$this->weightmachine = $weightmachine;
        $this->user = $user;
        $this->middleware('auth');
        $this->middleware('permission:view.weightmachineusers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.weightmachineusers', ['only' => ['store']]);
        $this->middleware('permission:edit.weightmachineusers',   ['only' => ['update']]);
        $this->middleware('permission:delete.weightmachineusers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        /*$user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $weightmachineusers=array();
        $rows=$this->$weightmachineuser->get();
        foreach ($rows as $row) {
          $weightmachineuser['id']=$row->id;
          $weightmachineuser['name']=$row->name;
          $weightmachineuser['code']=$row->code;
          $weightmachineuser['user']=$user[$row->user_id];
          array_push($weightmachineusers,$weightmachineuser);
        }
        echo json_encode($weightmachineusers);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$user=$this->user
		->leftJoin('weight_machine_users', function($join)  {
			$join->on('weight_machine_users.user_id', '=', 'users.id');
			$join->where('weight_machine_users.weight_machine_id', '=', request('weight_machine_id',0));
			$join->whereNull('weight_machine_users.deleted_at');
		})
		->get([
		'users.id',
		'users.name',
		'weight_machine_users.id as weight_machine_user_id'
		]);
		$saved = $user->filter(function ($value) {
			if($value->weight_machine_user_id){
				return $value;
			}
		})->values();
		
		$new = $user->filter(function ($value) {
			if(!$value->weight_machine_user_id){
				return $value;
			}
		})->values();
		$row ['unsaved'] = $new;
		$row ['saved'] = $saved;
		echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WeightMachineUserRequest $request) {
		foreach($request->user_id as $index=>$val){
				$weightmachineuser = $this->weightmachineuser->updateOrCreate(
				['user_id' => $request->user_id[$index], 'weight_machine_id' => $request->weight_machine_id]);
		}
        if ($weightmachineuser) {
            return response()->json(array('success' => true, 'id' => $weightmachineuser->id, 'message' => 'Save Successfully'), 200);
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
        $weightmachineuser = $this->weightmachineuser->find($id);
        $row ['fromData'] = $weightmachineuser;
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
    public function update(WeightMachineUserRequest $request, $id) {
        $weightmachineuser = $this->weightmachineuser->update($id, $request->except(['id']));
        if ($weightmachineuser) {
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
        if ($this->weightmachineuser->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
