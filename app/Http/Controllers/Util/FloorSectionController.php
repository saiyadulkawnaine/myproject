<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\FloorSectionRepository;
use App\Repositories\Contracts\Util\FloorRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Library\Template;
use App\Http\Requests\FloorSectionRequest;

class FloorSectionController extends Controller {

    private $floorsection;
	private $floor;
    private $section;

    public function __construct(FloorSectionRepository $floorsection, SectionRepository $section,FloorRepository $floor) {
        $this->floorsection = $floorsection;
		$this->floor = $floor;
        $this->section = $section;
        $this->middleware('auth');
        // $this->middleware('permission:view.floorsections',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.floorsections', ['only' => ['store']]);
        // $this->middleware('permission:edit.floorsections',   ['only' => ['update']]);
        // $this->middleware('permission:delete.floorsections', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $section=array_prepend(array_pluck($this->section->get(),'name','id'),'-Select-','');
        $floorsections=array();
        $rows=$this->floorsection->get();
        foreach ($rows as $row) {
          $floorsection['id']=$row->id;
          $floorsection['name']=$row->name;
          $floorsection['code']=$row->code;
          $floorsection['section']=$section[$row->section_id];
          array_push($floorsections,$floorsection);
        }
        echo json_encode($floorsections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$floor=$this->floor
		->leftJoin('floor_sections', function($join)  {
			$join->on('floor_sections.floor_id', '=', 'floors.id');
			$join->where('floor_sections.section_id', '=', request('section_id',0));
			$join->whereNull('floor_sections.deleted_at');
		})
       // ->where([['floors.nature_type','=',2]])
        ->orderBy('floors.name','asc')
		->get([
            'floors.id',
            'floors.name',
            'floor_sections.id as floor_section_id'
		]);
		$saved = $floor->filter(function ($value) {
			if($value->floor_section_id){
				return $value;
			}
		})->values();
		
		$new = $floor->filter(function ($value) {
			if(!$value->floor_section_id){
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
    public function store(FloorSectionRequest $request) {

		foreach($request->floor_id as $index=>$val){
				$floorsection = $this->floorsection->updateOrCreate(
				[
                    'section_id' => $request->section_id,
                    'floor_id' => $request->floor_id[$index],
                ]);
		}
        if ($floorsection) {
            return response()->json(array('success' => true, 'id' => $floorsection->id, 'message' => 'Save Successfully'), 200);
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
        $floorsection = $this->floorsection->find($id);
        $row ['fromData'] = $floorsection;
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
    public function update(FloorSectionRequest $request, $id) {
        $floorsection = $this->floorsection->update($id, $request->except(['id']));
        if ($floorsection) {
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
        if ($this->floorsection->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
