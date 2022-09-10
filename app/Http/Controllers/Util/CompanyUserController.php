<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyUserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\CompanyUserRequest;

class CompanyUserController extends Controller {

    private $companyuser;
	private $company;
    private $buyer;

    public function __construct(CompanyUserRepository $companyuser, UserRepository $user,CompanyRepository $company) {
        $this->companyuser = $companyuser;
		$this->company = $company;
        $this->user = $user;
        $this->middleware('auth');
        $this->middleware('permission:view.companyusers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.companyusers', ['only' => ['store']]);
        $this->middleware('permission:edit.companyusers',   ['only' => ['update']]);
        $this->middleware('permission:delete.companyusers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $companyusers=array();
        $rows=$this->companyuser->get();
        foreach ($rows as $row) {
          $companyuser['id']=$row->id;
          $companyuser['name']=$row->name;
          $companyuser['code']=$row->code;
          $companyuser['user']=$user[$row->user_id];
          array_push($companyusers,$companyuser);
        }
        echo json_encode($companyusers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$company=$this->company
		->leftJoin('company_users', function($join)  {
			$join->on('company_users.company_id', '=', 'companies.id');
			$join->where('company_users.user_id', '=', request('user_id',0));
			$join->whereNull('company_users.deleted_at');
		})
		->get([
		'companies.id',
		'companies.name',
		'company_users.id as company_user_id'
		]);
		$saved = $company->filter(function ($value) {
			if($value->company_user_id){
				return $value;
			}
		})->values();
		
		$new = $company->filter(function ($value) {
			if(!$value->company_user_id){
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
    public function store(CompanyUserRequest $request) {
		foreach($request->company_id as $index=>$val){
				$companyuser = $this->companyuser->updateOrCreate(
				['user_id' => $request->user_id, 'company_id' => $request->company_id[$index]]);
		}
        if ($companyuser) {
            return response()->json(array('success' => true, 'id' => $companyuser->id, 'message' => 'Save Successfully'), 200);
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
        $companyuser = $this->companyuser->find($id);
        $row ['fromData'] = $companyuser;
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
    public function update(CompanyUserRequest $request, $id) {
        $companyuser = $this->companyuser->update($id, $request->except(['id']));
        if ($companyuser) {
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
        if ($this->companyuser->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
