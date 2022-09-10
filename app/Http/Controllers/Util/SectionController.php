<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\FloorRepository;

use App\Library\Template;
use App\Http\Requests\SectionRequest;

class SectionController extends Controller
{
	private $section;
	private $floor;

	public function __construct(SectionRepository $section,FloorRepository $floor)
	{
		$this->section = $section;
		$this->floor = $floor;
		$this->middleware('auth');
		$this->middleware('permission:view.sections',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.sections', ['only' => ['store']]);
		$this->middleware('permission:edit.sections',   ['only' => ['update']]);
		$this->middleware('permission:delete.sections', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
			$sections=array();
      $rows=$this->section->get();
      foreach ($rows as $row) {
				$section['id']=$row->id;
				$section['name']=$row->name;
				$section['code']=$row->code;
        $section['chief_name']=$row->chief_name;
        array_push($sections,$section);
      }
        echo json_encode($sections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		  return Template::loadView("Util.Section");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionRequest $request)
    {
      $section=$this->section->create($request->except(['id']));
      if($section){
        return response()->json(array('success' => true,'id' =>  $section->id,'message' => 'Save Successfully'),200);
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
      $section = $this->section->find($id);
      $row ['fromData'] = $section;
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
    public function update(SectionRequest $request, $id)
    {
      $res=$this->section->update($id,$request->except(['id']));
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
        if($this->section->delete($id)){
			  return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
  }
}
