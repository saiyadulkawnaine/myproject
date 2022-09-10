<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Library\Template;
use App\Http\Requests\DivisionRequest;

class DivisionController extends Controller
{
	private $division;

	public function __construct(DivisionRepository $division)
	{
		$this->division = $division;
		$this->middleware('auth');
		$this->middleware('permission:view.divisions',   ['only' => ['create', 'index','show']]);
    $this->middleware('permission:create.divisions', ['only' => ['store']]);
    $this->middleware('permission:edit.divisions',   ['only' => ['update']]);
		$this->middleware('permission:delete.divisions', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo json_encode($this->division->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return Template::loadView("Util.Division");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DivisionRequest $request)
    {
        $division=$this->division->create($request->except(['id']));
		if($division){
			return response()->json(array('success' => true,'id' =>  $division->id,'message' => 'Save Successfully'),200);
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
       $division = $this->division->find($id);
	   $row ['fromData'] = $division;
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
    public function update(DivisionRequest $request, $id)
    {
        $division=$this->division->update($id,$request->except(['id']));
		if($division){
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
        if($this->division->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
