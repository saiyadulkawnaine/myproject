<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\StoreRequest;

class StoreController extends Controller {

    private $store;
    private $company;
    private $location;
    private $user;
    

    public function __construct(StoreRepository $store, CompanyRepository $company,LocationRepository $location, UserRepository $user) {
        $this->store = $store;
        $this->company = $company;
        $this->location = $location;
        $this->user = $user;
        
        $this->middleware('auth');
        $this->middleware('permission:view.stores',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.stores', ['only' => ['store']]);
        $this->middleware('permission:edit.stores',   ['only' => ['update']]);
        $this->middleware('permission:delete.stores', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $stores=array();
        $rows=$this->store->get();
        foreach ($rows as $row){
            $store['id']=$row->id;
            $store['name']=$row->name;
            $store['address']=$row->address;
            $store['user_id']=isset($user[$row->user_id])?$user[$row->user_id]:'';
            $store['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:'';
            $store['location_id']=isset($location[$row->location_id])?$location[$row->location_id]:'';
            array_push($stores,$store);
        }
        echo json_encode($stores);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
		return Template::loadView('Util.Store', ['company'=>$company,'location'=>$location,'user'=>$user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request) {
		$store=$this->store->create($request->except(['id']));
        if($store){
            return response()->json(array('success'=>true,'id'=>$store->id,'message'=>'Save Successfully'),200);
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
        $store = $this->store->find($id);
        $row ['fromData'] = $store;
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
    public function update(StoreRequest $request, $id) {
        $store=$this->store->update($id,$request->except(['id']));
        if($store){
            return response()->json(array('success'=>true,'id'=>$id,'message'=>'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->store->delete($id)){
            return response()->json(array('success'=>true,'message' => 'Delete Successfully'),200);
        }
    }

}
