<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccChartSectionRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccChartSectionRequest;

class AccChartSectionController extends Controller {

    private $ctrlHead;
    private $accchartsection;
    private $section;
    //private $ctrlHead;

    public function __construct(AccChartCtrlHeadRepository $ctrlHead,AccChartSectionRepository $accchartsection,SectionRepository $section) {
        $this->ctrlHead = $ctrlHead;
        $this->accchartsection = $accchartsection;
        $this->section = $section;


        $this->middleware('auth');
        //$this->middleware('permission:view.accchartsections',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.accchartsections', ['only' => ['store']]);
        //$this->middleware('permission:edit.accchartsections',   ['only' => ['update']]);
        //$this->middleware('permission:delete.accchartsections', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $ctrlHead=array_prepend(array_pluck($this->ctrlHead->get(),'name','id'),'-Select-','');
        $accchartsections=array();
        $rows=$this->accchartsection->get();
        foreach ($rows as $row) {
          $accchartsection['id']=$row->id;
          $accchartsection['name']=$row->name;
          $accchartsection['code']=$row->code;
          $accchartsection['ctrlHead']=$ctrlHead[$row->acc_chart_ctrl_head_id];
          array_push($accchartsections,$accchartsection);
        }
        echo json_encode($accchartsections);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $section=$this->section
		->leftJoin('acc_chart_sections', function($join)  {
			$join->on('acc_chart_sections.section_id', '=', 'sections.id');
			$join->where('acc_chart_sections.acc_chart_ctrl_head_id', '=', request('acc_chart_ctrl_head_id',0));
			$join->whereNull('acc_chart_sections.deleted_at');
		})
		->get([
		'sections.id',
		'sections.name',
		'acc_chart_sections.id as acc_chart_section_id'
		]);
		$saved = $section->filter(function ($value) {
			if($value->acc_chart_section_id){
				return $value;
			}
		})->values();
		
		$new = $section->filter(function ($value) {
			if(!$value->acc_chart_section_id){
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
    public function store(AccChartSectionRequest $request) {
		foreach($request->section_id as $index=>$val){
				$accchartsection = $this->accchartsection->updateOrCreate(
				['acc_chart_ctrl_head_id' => $request->acc_chart_ctrl_head_id, 'section_id' => $request->section_id[$index]]);
		}
        if ($accchartsection) {
            return response()->json(array('success' => true, 'id' => $accchartsection->id, 'message' => 'Save Successfully'), 200);
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
       $accchartsection = $this->accchartsection->find($id);
	   $row ['fromData'] = $accchartsection;
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
    public function update(AccChartSectionRequest $request, $id) {
        $accchartsection=$this->accchartsection->update($id,$request->except(['id']));
		if($accchartsection){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->accchartsection->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
