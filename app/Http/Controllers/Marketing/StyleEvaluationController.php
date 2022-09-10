<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleEvaluationRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Library\Template;
use App\Http\Requests\StyleEvaluationRequest;

class StyleEvaluationController extends Controller {

  private $styleevaluation;
    private $style;

    public function __construct(StyleEvaluationRepository $styleevaluation,StyleRepository $style) {
      $this->styleevaluation = $styleevaluation;
      $this->style = $style;
      $this->middleware('auth');
      $this->middleware('permission:view.styleevaluations',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.styleevaluations', ['only' => ['store']]);
      $this->middleware('permission:edit.styleevaluations',   ['only' => ['update']]);
      $this->middleware('permission:delete.styleevaluations', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		//$style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');
		$styleevaluations=array();
		$query = $this->styleevaluation->query();
		$query->join('styles', function($join) {
		$join->on('styles.id', '=', 'style_evaluations.style_id');
		});
		$query->when(request('style_id'), function ($q) {
		return $q->where('style_id', '=', request('style_id', 0));
		});
		$query->when(request('style_gmt_id'), function ($q) {
		return $q->where('style_gmt_id', '=', request('style_gmt_id', 0));
		});
		$rows=$query->get([
		'style_evaluations.id',
		'style_evaluations.risk',
		'style_evaluations.favorable',
		'styles.style_ref',
		]);
		foreach($rows as $row){
		$styleevaluation['id']=	$row->id;
		$styleevaluation['risk']=	$row->risk;
		$styleevaluation['favorable']=	$row->favorable;
		$styleevaluation['style']=	$row->style_ref;
		array_push($styleevaluations,$styleevaluation);
		}
		echo json_encode($styleevaluations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
        return Template::loadView('Util.StyleEvaluation', ['style'=>$style]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleEvaluationRequest $request) {
        $styleevaluation = $this->styleevaluation->create($request->except(['id','style_ref']));
        if ($styleevaluation) {
            return response()->json(array('success' => true, 'id' => $styleevaluation->id, 'message' => 'Save Successfully'), 200);
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
       // $styleevaluation = $this->styleevaluation->find($id);

			$styleevaluation = $this->styleevaluation->join('styles', function($join)  {
		$join->on('style_evaluations.style_id', '=', 'styles.id');
		})
		->where('style_evaluations.id','=',$id)
		->get([
			'style_evaluations.*',
			'styles.style_ref',
		]);
        $row ['fromData'] = $styleevaluation[0];
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
    public function update(StyleEvaluationRequest $request, $id) {
        $styleevaluation = $this->styleevaluation->update($id, $request->except(['id','style_ref']));
        if ($styleevaluation) {
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
        if ($this->styleevaluation->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
