<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SupplierUserRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\SupplierUserRequest;

class SupplierUserController extends Controller {

    private $supplieruser;
    private $supplier;
    private $user;

    public function __construct(SupplierUserRepository $supplieruser, UserRepository $user,SupplierRepository $supplier) {
        $this->supplieruser = $supplieruser;
		$this->supplier = $supplier;
        $this->user = $user;
        $this->middleware('auth');
        $this->middleware('permission:view.supplierusers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.supplierusers', ['only' => ['store']]);
        $this->middleware('permission:edit.supplierusers',   ['only' => ['update']]);
        $this->middleware('permission:delete.supplierusers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $supplierusers=array();
        $rows=$this->$supplieruser->get();
        foreach ($rows as $row) {
          $supplieruser['id']=$row->id;
          $supplieruser['name']=$row->name;
          $supplieruser['code']=$row->code;
          $supplieruser['user']=$user[$row->user_id];
          array_push($supplierusers,$supplieruser);
        }
        echo json_encode($supplierusers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$supplier=$this->supplier
		->leftJoin('supplier_users', function($join)  {
			$join->on('supplier_users.supplier_id', '=', 'suppliers.id');
			$join->where('supplier_users.user_id', '=', request('user_id',0));
			$join->whereNull('supplier_users.deleted_at');
		})
		->get([
		'suppliers.id',
		'suppliers.name',
		'supplier_users.id as supplier_user_id'
		]);
		$saved = $supplier->filter(function ($value) {
			if($value->supplier_user_id){
				return $value;
			}
		})->values();
		
		$new = $supplier->filter(function ($value) {
			if(!$value->supplier_user_id){
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
    public function store(SupplierUserRequest $request) {
		foreach($request->supplier_id as $index=>$val){
				$supplieruser = $this->supplieruser->updateOrCreate(
				['user_id' => $request->user_id, 'supplier_id' => $request->supplier_id[$index]]);
		}
        if ($supplieruser) {
            return response()->json(array('success' => true, 'id' => $supplieruser->id, 'message' => 'Save Successfully'), 200);
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
        $supplieruser = $this->supplieruser->find($id);
        $row ['fromData'] = $supplieruser;
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
    public function update(SupplierUserRequest $request, $id) {
        $supplieruser = $this->supplieruser->update($id, $request->except(['id']));
        if ($supplieruser) {
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
        if ($this->supplieruser->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
