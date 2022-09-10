<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StylePolyRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Library\Template;
use App\Http\Requests\StylePolyRequest;

class StylePolyController extends Controller {

  private $stylepoly;
  private $style;
  private $itemclass;

    public function __construct(StylePolyRepository $stylepoly,StyleRepository $style,ItemclassRepository $itemclass) {
      $this->stylepoly = $stylepoly;
      $this->style = $style;
      $this->itemclass = $itemclass;
      $this->middleware('auth');
      $this->middleware('permission:view.stylepolys',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylepolys', ['only' => ['store']]);
      $this->middleware('permission:edit.stylepolys',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylepolys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		/*$style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');
		$itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');*/

		/*$query = $this->stylepoly->query();
		$query->when(request('style_id'), function ($q) {
			return $q->where('style_id', '=', request('style_id', 0));
		});
		$query->when(request('style_gmt_id'), function ($q) {
			return $q->where('style_gmt_id', '=', request('style_gmt_id', 0));
		});
		$rows=$query->get();*/

		$rows = $this->stylepoly->selectRaw(
	    'style_polies.id,
		style_polies.style_id,
		style_polies.spec,
		style_polies.itemclass_id,
		styles.style_ref,
		itemclasses.name,
		sum(style_poly_ratios.gmt_ratio) as gmt_ratio'
		)
		->leftJoin('style_poly_ratios', function($join) {
		$join->on('style_poly_ratios.style_poly_id', '=', 'style_polies.id');
		})
		->join('styles', function($join)  {
		$join->on('styles.id', '=', 'style_polies.style_id');
		})
		->join('itemclasses', function($join)  {
		$join->on('itemclasses.id', '=', 'style_polies.itemclass_id');
		})
		->when(request('style_id'), function ($q) {
			return $q->where('style_polies.style_id', '=', request('style_id', 0));
		})
		->when(request('style_gmt_id'), function ($q) {
			return $q->where('style_polies.style_gmt_id', '=', request('style_gmt_id', 0));
		})
		->groupBy([
		'style_polies.id',
		'style_polies.style_id',
		'style_polies.spec',
		'style_polies.itemclass_id',
		'styles.style_ref',
		'itemclasses.name',
		])
		->get();

		$stylepolys=array();
		foreach($rows as $row){
		$stylepoly['id']=	$row->id;
		$stylepoly['spec']=	$row->spec;
		$stylepoly['style']=	$row->style_ref;
		$stylepoly['style_id']=	$row->style_id;
		$stylepoly['itemclass']=	$row->name;
		$stylepoly['gmt_ratio']=	$row->gmt_ratio;
		array_push($stylepolys,$stylepoly);
		}
		echo json_encode($stylepolys);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
      $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
        return Template::loadView('Util.StylePoly', ['style'=>$style,'itemclass'=>$itemclass]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StylePolyRequest $request) {
        $stylepoly = $this->stylepoly->create($request->except(['id','style_ref']));
        if ($stylepoly) {
            return response()->json(array('success' => true, 'id' => $stylepoly->id, 'message' => 'Save Successfully'), 200);
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
        //$stylepoly = $this->stylepoly->find($id);
		$stylepoly = $this->stylepoly->join('styles', function($join)  {
		$join->on('style_polies.style_id', '=', 'styles.id');
		})
		->where('style_polies.id','=',$id)
		->get([
			'style_polies.*',
			'styles.style_ref',
		]);
        $row ['fromData'] = $stylepoly[0];
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
    public function update(StylePolyRequest $request, $id) {
        $stylepoly = $this->stylepoly->update($id, $request->except(['id','style_ref']));
        if ($stylepoly) {
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
        if ($this->stylepoly->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
