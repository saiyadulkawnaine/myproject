<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\DepartmentFloorRepository;
use App\Repositories\Contracts\Util\FloorRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Library\Template;
use App\Http\Requests\DepartmentFloorRequest;

class DepartmentFloorController extends Controller {

    private $departmentfloor;
	private $floor;
    private $department;

    public function __construct(DepartmentFloorRepository $departmentfloor, DepartmentRepository $department,FloorRepository $floor) {
        $this->departmentfloor = $departmentfloor;
		$this->floor = $floor;
        $this->department = $department;
        $this->middleware('auth');
        // $this->middleware('permission:view.departmentfloors',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.departmentfloors', ['only' => ['store']]);
        // $this->middleware('permission:edit.departmentfloors',   ['only' => ['update']]);
        // $this->middleware('permission:delete.departmentfloors', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $departmentfloors=array();
        $rows=$this->departmentfloor->get();
        foreach ($rows as $row) {
          $departmentfloor['id']=$row->id;
          $departmentfloor['name']=$row->name;
          $departmentfloor['code']=$row->code;
          $departmentfloor['department']=$department[$row->department_id];
          array_push($departmentfloors,$departmentfloor);
        }
        echo json_encode($departmentfloors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$floor=$this->floor
		->leftJoin('department_floors', function($join)  {
			$join->on('department_floors.floor_id', '=', 'floors.id');
			$join->where('department_floors.department_id', '=', request('department_id',0));
			$join->whereNull('department_floors.deleted_at');
		})
       // ->where([['floors.nature_type','=',2]])
        ->orderBy('floors.name','asc')
		->get([
		'floors.id',
		'floors.name',
		'department_floors.id as department_floor_id'
		]);
		$saved = $floor->filter(function ($value) {
			if($value->department_floor_id){
				return $value;
			}
		})->values();
		
		$new = $floor->filter(function ($value) {
			if(!$value->department_floor_id){
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
    public function store(DepartmentFloorRequest $request) {

		foreach($request->floor_id as $index=>$val){
				$departmentfloor = $this->departmentfloor->updateOrCreate(
				[
                    'department_id' => $request->department_id,
                    'floor_id' => $request->floor_id[$index]
                    ]);
		}
        if ($departmentfloor) {
            return response()->json(array('success' => true, 'id' => $departmentfloor->id, 'message' => 'Save Successfully'), 200);
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
        $departmentfloor = $this->departmentfloor->find($id);
        $row ['fromData'] = $departmentfloor;
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
    public function update(DepartmentFloorRequest $request, $id) {
        $departmentfloor = $this->departmentfloor->update($id, $request->except(['id']));
        if ($departmentfloor) {
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
        if ($this->departmentfloor->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
