<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\FloorRepository;
use App\Library\Template;
use App\Http\Requests\FloorRequest;


class FloorController extends Controller
{
    private $floor;

	public function __construct(FloorRepository $floor)
	{
		$this->floor = $floor;
		$this->middleware('auth');
		$this->middleware('permission:view.floors',   ['only' => ['create', 'index','show']]);
    $this->middleware('permission:create.floors', ['only' => ['store']]);
    $this->middleware('permission:edit.floors',   ['only' => ['update']]);
		$this->middleware('permission:delete.floors', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
      $floors=array();
      $rows=$this->floor->get();
      foreach ($rows as $row) {
        $floor['id']=$row->id;
        $floor['name']=$row->name;
        $floor['code']=$row->code;
        $floor['chief_name']=$row->chief_name;
        //$floor['productionarea']=$productionarea[$row->productionarea_id];
        array_push($floors,$floor);
      }
        echo json_encode($floors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
		return Template::loadView("Util.Floor",['productionarea'=>$productionarea]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FloorRequest $request)
    {
        $floor=$this->floor->create($request->except(['id']));
		if($floor){
			return response()->json(array('success' => true,'id' =>  $floor->id,'message' => 'Save Successfully'),200);
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
       $floor = $this->floor->find($id);
	   $row ['fromData'] = $floor;
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
    public function update(FloorRequest $request, $id)
    {
        $floor=$this->floor->update($id,$request->except(['id']));
		if($floor){
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
        if($this->floor->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
