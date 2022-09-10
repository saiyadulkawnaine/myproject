<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\RegionRepository;
use App\Library\Template;
use App\Http\Requests\RegionRequest;

class RegionController extends Controller
{

	private $region;

	public function __construct(RegionRepository $region)
	{
		$this->region = $region;
		$this->middleware('auth');
		$this->middleware('permission:view.regions',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.regions', ['only' => ['store']]);
		$this->middleware('permission:edit.regions',   ['only' => ['update']]);
		$this->middleware('permission:delete.regions', ['only' => ['destroy']]);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo json_encode($this->region->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return Template::loadView("Util.Region");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegionRequest $request)
    {
        $region=$this->region->create($request->except(['id']));
		if($region){
			return response()->json(array('success' => true,'id' =>  $region->id,'message' => 'Save Successfully'),200);
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
       $region = $this->region->find($id);
		   $row ['fromData'] = $region;
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
    public function update(RegionRequest $request, $id)
    {
        $region=$this->region->update($id,$request->except(['id']));
		if($region){
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
        if($this->region->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
