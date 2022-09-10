<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\PermissionUserRepository;
use App\Repositories\Contracts\System\PermissionRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\System\PermissionUserRequest;

class PermissionUserController extends Controller
{
    
    private $permissionuser;
    private $permission;
	private $user;
	
	public function __construct(PermissionUserRepository $permissionuser,PermissionRepository $permission,UserRepository $user) 
	{
		$this->permissionuser = $permissionuser;
        $this->permission = $permission;
		$this->user = $user;
		$this->middleware('auth');
		//$this->middleware('permission:view.permissions',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.permissions', ['only' => ['store']]);
        //$this->middleware('permission:edit.permissions',   ['only' => ['update']]);
		//$this->middleware('permission:delete.permissions', ['only' => ['destroy']]);
	}
	
	 /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $permissionusers=array();
        $rows=$this->permissionuser->get();
        foreach ($rows as $row) {
          $permissionuser['id']=$row->id;
          $permissionuser['name']=$row->name;
          $permissionuser['code']=$row->code;
          $permissionuser['user']=$user[$row->user_id];
          array_push($permissionusers,$permissionuser);
        }
        echo json_encode($permissionusers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $permission=$this->permission
        ->leftJoin('permission_user', function($join)  {
            $join->on('permission_user.permission_id', '=', 'permissions.id');
            $join->where('permission_user.user_id', '=', request('user_id',0));
            $join->whereNull('permission_user.deleted_at');
        })
        ->where([['permissions.slug','like','%approve%']])
        ->get([
        'permissions.id',
        'permissions.name',
        'permission_user.id as permission_user_id'
        ]);
        $saved = $permission->filter(function ($value) {
            if($value->permission_user_id){
                return $value;
            }
        })->values();
        
        $new = $permission->filter(function ($value) {
            if(!$value->permission_user_id){
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
    public function store(PermissionUserRequest $request) {
        foreach($request->permission_id as $index=>$val){
                $permissionuser = $this->permissionuser->updateOrCreate(
                ['user_id' => $request->user_id, 'permission_id' => $request->permission_id[$index]]);
        }
        if ($permissionuser) {
            return response()->json(array('success' => true, 'id' => $permissionuser->id, 'message' => 'Save Successfully'), 200);
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
        $permissionuser = $this->permissionuser->find($id);
        $row ['fromData'] = $permissionuser;
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
    public function update(PermissionUserRequest $request, $id) {
        $permissionuser = $this->permissionuser->update($id, $request->except(['id']));
        if ($permissionuser) {
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
        if ($this->permissionuser->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
