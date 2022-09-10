<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ProfitcenterRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\ProfitcenterRequest;

class ProfitcenterController extends Controller
{
	private $profitcenter;
	private $company;

	public function __construct(ProfitcenterRepository $profitcenter,CompanyRepository $company)
	{
		$this->profitcenter = $profitcenter;
		$this->company = $company;
		$this->middleware('auth');
		$this->middleware('permission:view.profitcenters',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.profitcenters', ['only' => ['store']]);
		$this->middleware('permission:edit.profitcenters',   ['only' => ['update']]);
		$this->middleware('permission:delete.profitcenters', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $profitcenters=array();
      $rows=$this->profitcenter->get();
      foreach ($rows as $row) {
				$profitcenter['id']=$row->id;
				$profitcenter['name']=$row->name;
        $profitcenter['code']=$row->code;
        array_push($profitcenters,$profitcenter);
      }
        echo json_encode($profitcenters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		// $company=array_pluck($this->company->get(),'name','id');
		// $permited=[];
		return Template::loadView("Util.Profitcenter"/* , ['company'=>$company,'permited'=>$permited] */);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfitcenterRequest $request)
    {
      //$company=explode(",",$request->input('company_id'));
      $profitcenter=$this->profitcenter->create($request->except(['id'/* ,'company_id' */]));
      //$res=$profitcenter->companies()->sync($company);
      //if($res){
      if($profitcenter){
        return response()->json(array('success' => true,'id' =>  $profitcenter->id,'message' => 'Save Successfully'),200);
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
      //$companies = $this->company->get();
      //$participants = $this->profitcenter->find($id)->companies->sortBy('id');
      //$avaiable = $companies->diff($participants);
      $profitcenter = $this->profitcenter->find($id);
      $row ['fromData'] = $profitcenter;
      //$dropdown['company_dropDown'] = "'".Template::loadView('Util.CompanyDropDown',['company'=>array_pluck($avaiable,'name','id'),'permited'=>array_pluck(	$participants,'name','id')])."'";
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
    public function update(ProfitcenterRequest $request, $id)
    {
      //$company=explode(",",$request->input('company_id'));
      $res=$this->profitcenter->update($id,$request->except(['id'/* ,'company_id' */]));
      //$profitcenter = $this->profitcenter->find($id);
      //$profitcenter->companies()->sync($company);
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
        if($this->profitcenter->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
