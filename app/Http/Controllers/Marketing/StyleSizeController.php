<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleSizeRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Library\Template;
use App\Http\Requests\StyleSizeRequest;

class StyleSizeController extends Controller {

    private $stylesize;
    private $style;
    private $stylegmts;
    private $size;

    public function __construct(StyleSizeRepository $stylesize, StyleRepository $style,StyleGmtsRepository $stylegmts,SizeRepository $size) {
        $this->stylesize = $stylesize;
        $this->style = $style;
        $this->stylegmts = $stylegmts;
        $this->size = $size;
        $this->middleware('auth');
        $this->middleware('permission:view.stylesizes',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.stylesizes', ['only' => ['store']]);
        $this->middleware('permission:edit.stylesizes',   ['only' => ['update']]);
        $this->middleware('permission:delete.stylesizes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
		//$style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');
		//$size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');
		$stylesizes=array();
		$rows=$this->stylesize->leftJoin('sizes', function($join) {
		$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
		->join('styles', function($join) use ($request) {
		$join->on('styles.id', '=', 'style_sizes.style_id');
		})
		->when(request('style_id'), function ($q) {
			return $q->where('style_sizes.style_id', '=', request('style_id', 0));
		})
		->orderBy('style_sizes.sort_id','asc')
		->get([
		'style_sizes.id',
		'style_sizes.style_id',
		'style_sizes.size_id',
		'style_sizes.sort_id',
		'styles.style_ref',
		'sizes.name',
		]);

		foreach($rows as $row){
			$stylesize['id']=	$row->id;
			$stylesize['sort']=	$row->sort_id;
			$stylesize['style_ref']=$row->style_ref;
			$stylesize['name']=	$row->name;
			array_push($stylesizes,$stylesize);
		}
		echo json_encode($stylesizes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
      $stylegmts=array_prepend(array_pluck($this->stylegmts->get(),'name','id'),'-Select-','');
      $size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');
        return Template::loadView('Marketing.StyleSize', ['style'=>$style,'stylegmts'=>$stylegmts,'size'=>$size]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleSizeRequest $request) {
		$size= $this->size->firstOrCreate(['name' => $request->size_id],['code' => $request->size_code]);
        $stylesize = $this->stylesize->create(['style_id' => $request->style_id,'style_gmt_id' => $request->style_gmt_id,'size_id' => $size->id,'size_code' => $request->size_code,'sort_id' => $request->sort_id]);
        if ($stylesize) {
            return response()->json(array('success' => true, 'id' => $stylesize->id, 'message' => 'Save Successfully'), 200);
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
        //$stylesize = $this->stylesize->find($id);

		$stylesize = $this->stylesize->leftJoin('sizes', function($join) {
		$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
		->join('styles', function($join)  {
		$join->on('style_sizes.style_id', '=', 'styles.id');
		})
		->where([['style_sizes.id',$id]])
		->get([
		'style_sizes.id',
		'style_sizes.style_id',
		'style_sizes.style_gmt_id',
		'style_sizes.sort_id',
		'sizes.name as size_id',
		'sizes.code as size_code',
		'styles.style_ref',
		]);
        $row ['fromData'] = $stylesize[0];
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
    public function update(StyleSizeRequest $request, $id) {
		$size= $this->size->firstOrCreate(['name' => $request->size_id],['code' => $request->size_code]);
        $stylesize = $this->stylesize->update($id, ['style_id' => $request->style_id,'style_gmt_id' => $request->style_gmt_id,'size_id' => $size->id,'size_code' => $request->size_code,'sort_id' => $request->sort_id]);
        if ($stylesize) {
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
        if ($this->stylesize->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
