<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\PermissionRepository;
use App\Repositories\Contracts\System\Auth\RoleRepository as Role;
use App\Library\Template;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    
    private $permission;
	private $role;
	
	public function __construct(PermissionRepository $permission,Role $role) 
	{
		$this->permission = $permission;
		$this->role = $role;
		$this->middleware('auth');
		$this->middleware('permission:view.permissions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.permissions', ['only' => ['store']]);
        $this->middleware('permission:edit.permissions',   ['only' => ['update']]);
		$this->middleware('permission:delete.permissions', ['only' => ['destroy']]);
	}
	
	 /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		echo json_encode($this->permission->orderBy('permissions.id','desc')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
		return Template::loadView("System.Menu.Permission");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PermissionRequest $request)
    {
		$permission=$this->permission->create($request->except(['id']));
		$role = $this->role->find(1);
        $role->attachPermission($permission);
		if($permission){
			return response()->json(array('success' => true,'id' =>  $permission->id,'message' => 'Save Successfully'),200);
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
	    $row ['fromData'] = $this->permission->find($id);
		$dropdown['permission_dropDown'] = '';
		$row ['dropDown'] = $dropdown;
		echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PermissionRequest $request, $id)
    {
		$permission=$this->permission->update($id,$request->except(['id']));
		if($permission){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
		if($this->permission->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
