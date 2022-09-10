<?php

namespace App\Http\Controllers\System\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\Auth\RoleRepository as Role;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
	private $user;
	private $role;
	
	public function __construct(UserRepository $user,Role $role) 
	{
		$this->user = $user;
		$this->role = $role;
		$this->middleware('auth');
		$this->middleware('permission:view.users',   ['only' => ['create','index','show']]);
        $this->middleware('permission:create.users', ['only' => ['store']]);
        $this->middleware('permission:edit.users',   ['only' => ['update']]);
		$this->middleware('permission:delete.users', ['only' => ['destroy']]);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$user=$this->user
    	->join('role_user',function($join){
		$join->on('role_user.user_id','=','users.id');
		})
    	->join('roles',function($join){
		$join->on('roles.id','=','role_user.role_id');
		})
    	->orderBy('users.id','desc')
    	->get(['users.*','roles.name as role_name']);
       echo json_encode($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role=array_prepend(array_pluck($this->role->get(),'name','id'),'-Select-',0);
		return Template::loadView("System.Auth.User", ['role'=>$role]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
		$user = $this->user->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        $user->attachRole($request->input('role_id'));
		if($user){
			return response()->json(array('success' => true,'id' =>  $user->id,'message' => 'Save Successfully'),200);
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
		$user = $this->user->find($id);
		$user->role_id=0;
		foreach($user->roles as $role){
			$user->role_id=$role->pivot->role_id;
		}
		$row ['fromData'] = $user;
		$dropdown['permission_dropDown'] = '';
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
    public function update(UserRequest $request, $id)
    {
        $res = $this->user->update($id,[
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
		$user = $this->user->find($id);
		$user->syncRoles([$request->input('role_id')]);
		return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->user->find($id);
	    if($this->user->delete($id)){
			$user->detachAllRoles();
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
