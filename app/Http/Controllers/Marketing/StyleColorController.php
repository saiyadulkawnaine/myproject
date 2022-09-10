<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleColorRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\StyleColorRequest;

class StyleColorController extends Controller {

    private $stylecolor;
    private $style;
    private $stylegmts;
    private $color;

    public function __construct(
        StyleColorRepository $stylecolor, 
        StyleRepository $style,
        StyleGmtsRepository $stylegmts,
        ColorRepository $color
    ) {
        $this->stylecolor = $stylecolor;
        $this->style = $style;
        $this->stylegmts = $stylegmts;
        $this->color = $color;
        $this->middleware('auth');
        $this->middleware('permission:view.stylecolors',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.stylecolors', ['only' => ['store']]);
        $this->middleware('permission:edit.stylecolors',   ['only' => ['update']]);
        $this->middleware('permission:delete.stylecolors', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
		$stylecolors=array();
		$query = $this->stylecolor->query();
		$query->join('styles', function($join) {
			$join->on('styles.id', '=', 'style_colors.style_id');
		});
		$query->leftJoin('colors', function($join) {
			$join->on('colors.id', '=', 'style_colors.color_id');
		});
		$query->when(request('style_id'), function ($q) {
			return $q->where('style_id', '=', request('style_id', 0));
		});
		$query->orderBy('style_colors.sort_id','asc');
		$rows=$query->get(['style_colors.id','style_colors.style_id','style_colors.color_id','style_colors.sort_id','styles.style_ref','colors.name','colors.code']);

		foreach($rows as $row){
			$stylecolor['id']=	$row->id;
			$stylecolor['sort']=	$row->sort_id;
			$stylecolor['style']=	$row->style_ref;
			$stylecolor['name']=	$row->name;
			array_push($stylecolors,$stylecolor);
		}
		echo json_encode($stylecolors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      return Template::loadView('Marketing.StyleColor');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleColorRequest $request) {
		$color = $this->color->firstOrCreate(['name' => $request->color_id],['code' => $request->color_code]);
        $stylecolor = $this->stylecolor->create(['style_id' => $request->style_id,'style_gmt_id' => $request->style_gmt_id,'color_id' => $color->id,'color_code' => $request->color_code,'sort_id' => $request->sort_id]);
        if ($stylecolor) {
            return response()->json(array('success' => true, 'id' => $stylecolor->id, 'message' => 'Save Successfully'), 200);
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
        //$stylecolor = $this->stylecolor->find($id);

		$stylecolor = $this->stylecolor
		->leftJoin('colors', function($join) {
		$join->on('style_colors.color_id', '=', 'colors.id');
		})
		->join('styles', function($join)  {
		$join->on('style_colors.style_id', '=', 'styles.id');
		})
		->where([['style_colors.id',$id]])
		->get([
		'style_colors.id',
		'style_colors.style_id',
		'style_colors.style_gmt_id',
		'style_colors.sort_id',
		'colors.name as color_id',
		'colors.code as color_code',
		'styles.style_ref',
		]);

        $row ['fromData'] = $stylecolor[0];
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
    public function update(StyleColorRequest $request, $id) {
        $color = $this->color->firstOrCreate(['name' => $request->color_id],['code' => $request->color_code]);
        $stylecolor = $this->stylecolor->update($id,['style_id' => $request->style_id,'style_gmt_id' => $request->style_gmt_id,'color_id' => $color->id,'color_code' => $request->color_code,'sort_id' => $request->sort_id]);
        if ($stylecolor) {
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
        if ($this->stylecolor->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
