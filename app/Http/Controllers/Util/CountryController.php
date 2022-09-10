<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Library\Template;
use App\Http\Requests\CountryRequest;

class CountryController extends Controller
{
	private $country;

	public function __construct(CountryRepository $country)
	{
		$this->country = $country;
		$this->middleware('auth');
		$this->middleware('permission:view.countrys',   ['only' => ['create', 'index','show']]);
    $this->middleware('permission:create.countrys', ['only' => ['store']]);
    $this->middleware('permission:edit.countrys',   ['only' => ['update']]);
		$this->middleware('permission:delete.countrys', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
			$countries=array();
      $rows=$this->country
      ->orderBy('countries.sort_id','desc')
      ->get();
			foreach ($rows as $row) {
				$country['id']=$row->id;
				$country['name']=$row->name;
				$country['code']=$row->code;
				$country['sort_id']=$row->sort_id;
				array_push($countries,$country);
			}
        echo json_encode($countries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$cutoff = array_prepend(config('bprs.cutoff'),'-Select-',0);
		$region =array_prepend(config('bprs.region'),'-Select-',0);
		$elevel =array_prepend(config('bprs.economylevel'),'-Select-',0);
		$pst    =array_prepend(config('bprs.politicalstability'),'-Select-',0);
		return Template::loadView("Util.Country",['cutoff'=>$cutoff,'region'=>$region,'elevel'=>$elevel,'pst'=>$pst]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryRequest $request)
    {
        $country=$this->country->create($request->except(['id']));
		if($country){
			return response()->json(array('success' => true,'id' =>  $country->id,'message' => 'Save Successfully'),200);
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
       $country = $this->country->find($id);
	   $row ['fromData'] = $country;
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
    public function update(CountryRequest $request, $id)
    {
        $country=$this->country->update($id,$request->except(['id']));
		if($country){
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
        if($this->country->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
