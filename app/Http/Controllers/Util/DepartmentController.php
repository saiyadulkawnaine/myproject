<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\FloorRepository;
use App\Library\Template;
use App\Http\Requests\DepartmentRequest;

class DepartmentController extends Controller
{
	private $department;
	private $floor;

	public function __construct(DepartmentRepository $department,FloorRepository $floor)
	{
		$this->department = $department;
		$this->floor = $floor;
		$this->middleware('auth');
		$this->middleware('permission:view.departments',   ['only' => ['create', 'index','show']]);
    $this->middleware('permission:create.departments', ['only' => ['store']]);
    $this->middleware('permission:edit.departments',   ['only' => ['update']]);
		$this->middleware('permission:delete.departments', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//	$floor=array_pluck($this->floor->get(),'name','id');
			$departments=array();
			$rows=$this->department
			->orderBy('departments.id','desc')
			->get();
			foreach ($rows as $row) {
				$department['id']=$row->id;
				$department['name']=$row->name;
				$department['code']=$row->code;
				$department['chief_name']=$row->chief_name;

				array_push($departments,$department);
			}
        echo json_encode($departments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		//$floor=array_pluck($this->floor->get(),'name','id');
		//$permited=[];
		return Template::loadView("Util.Department"/*,  ['floor'=>$floor,'permited'=>$permited] */);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
		  //$floor=explode(",",$request->input('floor_id'));
    $department=$this->department->create($request->except(['id']));
		  //$res=$department->floors()->sync($floor);
		  //$user->roles()->sync([1, 2, 3]);
		if($department){
			return response()->json(array('success' => true,'id' =>  $department->id,'message' => 'Save Successfully'),200);
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
		//$floors = $this->floor->get();
		//$participants = $this->department->find($id)->floors->sortBy('id');
		//$avaiable = $floors->diff($participants);

    $department = $this->department->find($id);
    $row ['fromData'] = $department;
    //$dropdown['floor_dropDown'] = "'".Template::loadView('Util.FloorDropDown',['floor'=>array_pluck($avaiable,'name','id'),'permited'=>array_pluck($participants,'name','id')])."'";
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
    public function update(DepartmentRequest $request, $id)
    {
		//$floor=explode(",",$request->input('floor_id'));
    $res=$this->department->update($id,$request->except(['id'/* ,'floor_id' */]));
		//$department = $this->department->find($id);
		//$department->floors()->sync($floor);
		if($res){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
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
        if($this->department->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
