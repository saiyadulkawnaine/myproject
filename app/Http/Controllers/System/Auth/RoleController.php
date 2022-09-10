<?php
namespace App\Http\Controllers\System\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\Auth\RoleRepository;
use App\Repositories\Contracts\System\PermissionRepository as Permission;
use App\Library\Template;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller {
	
	private $role;
	private $permission;
	
	public function __construct(RoleRepository $role, Permission $permission) 
	{
		$this->role = $role;
		$this->permission = $permission;
		$this->middleware('auth');
		$this->middleware('permission:view.roles',   ['only' => ['create','index','show']]);
      $this->middleware('permission:create.roles', ['only' => ['store']]);
      $this->middleware('permission:edit.roles',   ['only' => ['update','edit']]);
		$this->middleware('permission:delete.roles', ['only' => ['destroy']]);
	}
	
	 /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		$role=$this->role
		->orderBy('roles.id','asc')
		->get();
		echo json_encode($role);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
		//$permission_arr=array_pluck($this->permission->get(),'name','id');, ['permission_arr'=>$permission_arr]
		return Template::loadView("System.Auth.Role");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(RoleRequest $request)
    {
		//$permissions=explode(",",$request->input('permission_id'));
    $role=$this->role->create($request->except(['id','permission_id']));
		//$res=$role->syncPermissions($permissions);
		if($role){
			return response()->json(array('success' => true,'id' =>  $role->id,'message' => 'Save Successfully'),200);
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
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
		//$permissions = $this->permission->get();
		//$participants = $this->role->find($id)->permissions->sortBy('id');
		//$avaiable = $permissions->diff($participants);
		$record = $this->role->find($id);
    $row ['fromData'] = $record;
    $dropdown['permission_dropDown'] = '';
		//$dropdown['permission_dropDown'] = "'".Template::loadView('System.Auth.PermissionDropDown',['permission_arr'=>array_pluck($avaiable,'name','id'),'permited'=>array_pluck($participants,'name','id')])."'";
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
    public function update(RoleRequest $request, $id)
    {
		//$permissions=explode(",",$request->input('permission_id'));
    $res=$this->role->update($id,$request->except(['id']));
		//$role = $this->role->find($id);
		//$role->syncPermissions($permissions);
		if($res){
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
		//$role = $this->role->find($id);
	    if($this->role->delete($id)){
			//$role->detachAllPermissions();
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
