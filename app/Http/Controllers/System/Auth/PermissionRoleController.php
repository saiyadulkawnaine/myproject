<?php

namespace App\Http\Controllers\System\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\Auth\PermissionRoleRepository;
use App\Repositories\Contracts\System\PermissionRepository;
use App\Repositories\Contracts\System\Auth\RoleRepository;
use App\Library\Template;
use App\Http\Requests\System\PermissionRoleRequest;

class PermissionRoleController extends Controller
{
    
    private $permissionrole;
    private $permission;
	private $role;
	
	public function __construct(PermissionRoleRepository $permissionrole,PermissionRepository $permission,RoleRepository $role) 
	{
		$this->permissionrole = $permissionrole;
        $this->permission = $permission;
		$this->role = $role;
		$this->middleware('auth');
		//$this->middleware('permission:view.permissionroles',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.permissionroles', ['only' => ['store']]);
        //$this->middleware('permission:edit.permissionroles',   ['only' => ['update']]);
		//$this->middleware('permission:delete.permissionroles', ['only' => ['destroy']]);
	}
	
	 /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $role=array_prepend(array_pluck($this->role->get(),'name','id'),'-Select-','');
        $permissionroles=array();
        $rows=$this->permissionrole
	->where([['permission_roles.role_id', '=', request('role_id',0)]])
	->get();
        foreach ($rows as $row) {
          $permissionrole['id']=$row->id;
          $permissionrole['name']=$row->name;
          $permissionrole['code']=$row->code;
          $permissionrole['role']=$role[$row->role_id];
          array_push($permissionroles,$permissionrole);
        }
        echo json_encode($permissionroles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $permission=$this->permission
        ->leftJoin('permission_role', function($join)  {
            $join->on('permission_role.permission_id', '=', 'permissions.id');
            $join->where('permission_role.role_id', '=', request('role_id',0));
            $join->whereNull('permission_role.deleted_at');
        })
        ->get([
        'permissions.id',
        'permissions.name',
        'permissions.slug',
        'permission_role.id as permission_role_id'
        ]);
        $saved = $permission->filter(function ($value) {
            if($value->permission_role_id){
                return $value;
            }
        })->values();
        
        $new = $permission->filter(function ($value) {
            if(!$value->permission_role_id){
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
    public function store(PermissionRoleRequest $request) {
        foreach($request->permission_id as $index=>$val){
                $permissionrole = $this->permissionrole->updateOrCreate(
                ['role_id' => $request->role_id, 'permission_id' => $request->permission_id[$index]]);
        }
        if ($permissionrole) {
            return response()->json(array('success' => true, 'id' => $permissionrole->id, 'message' => 'Save Successfully'), 200);
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
        $permissionrole = $this->permissionrole->find($id);
        $row ['fromData'] = $permissionrole;
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
    public function update(PermissionRoleRequest $request, $id) {
        $permissionrole = $this->permissionrole->update($id, $request->except(['id']));
        if ($permissionrole) {
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
        if ($this->permissionrole->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
