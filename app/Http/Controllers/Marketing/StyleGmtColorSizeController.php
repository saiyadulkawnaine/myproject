<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;

use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Marketing\StyleColorRepository;
use App\Repositories\Contracts\Marketing\StyleSizeRepository;

//use App\Model\Marketing\StyleGmtColorSize;
use App\Library\Template;
use App\Http\Requests\StyleGmtColorSizeRequest;

class StyleGmtColorSizeController extends Controller {

	private $stylegmtcolorsize;
	private $style;
	private $stylegmts;
	private $stylecolor;
	private $embelishmenttype;

    public function __construct(StyleGmtColorSizeRepository $stylegmtcolorsize, StyleRepository $style,StyleGmtsRepository $stylegmts,StyleColorRepository $stylecolor,StyleSizeRepository $stylesize) {
      $this->stylegmtcolorsize = $stylegmtcolorsize;
      $this->style = $style;
      $this->stylegmts = $stylegmts;
      $this->stylecolor = $stylecolor;
      $this->stylesize = $stylesize;
      $this->middleware('auth');
      $this->middleware('permission:view.stylegmtcolorsizes',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylegmtcolorsizes', ['only' => ['store']]);
      $this->middleware('permission:edit.stylegmtcolorsizes',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylegmtcolorsizes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$colorSize= $this->stylegmts
		->join('styles', function($join)  {
			$join->on('style_gmts.style_id', '=', 'styles.id');
		})
		->join('style_colors',function($join){
			$join->on('style_colors.style_id','=','styles.id');
		})
		->join('colors',function($join){
			$join->on('colors.id','=','style_colors.color_id');
		})
		->join('style_sizes',function($join){
			$join->on('style_sizes.style_id','=','styles.id');
		})
		->join('sizes',function($join){
			$join->on('sizes.id','=','style_sizes.size_id');
		})
		->leftJoin('style_gmt_color_sizes',function($join) {
			$join->on('style_gmts.id','=','style_gmt_color_sizes.style_gmt_id')
			->on('style_colors.id','=','style_gmt_color_sizes.style_color_id')
			->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id')
			->where('style_gmt_color_sizes.deleted_at','=',null);
		})
		->where('style_gmts.id','=',request('style_gmt_id',0))
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
		->get([
			'styles.id as style_id',
			'style_gmts.id as style_gmt_id',
			'style_colors.id as style_color_id',
			'style_sizes.id as style_size_id',
			'colors.name as color_name',
			'sizes.name as size_name',
			'style_gmt_color_sizes.id as style_gmt_color_size_id'
		]);
		
		$saved = $colorSize->filter(function ($value) {
			if($value->style_gmt_color_size_id){
				return $value;
			}
		})->values();
		
		$new = $colorSize->filter(function ($value) {
			if(!$value->style_gmt_color_size_id){
				return $value;
			}
		})->values();
		$row ['colorSize'] = $new;
		$row ['savedcolorSize'] = $saved;
		echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleGmtColorSizeRequest $request) {
		foreach($request->style_size_id as $index=>$val){
			if($val){
				$stylegmtcolorsize = $this->stylegmtcolorsize->updateOrCreate(
				['style_id' => $request->style_id,  'style_gmt_id' =>  $request->style_gmt_id, 'style_color_id' => $request->style_color_id[$index],'style_size_id' => $request->style_size_id[$index]]);
			}
		}
		return response()->json(array('success' => true, 'style_gmt_id' => $request->style_gmt_id, 'message' => 'Save Successfully'), 200);
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StyleGmtColorSizeRequest $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
		$stylegmtcolorsize=$this->stylegmtcolorsize->find($id);
       if ($this->stylegmtcolorsize->delete($id)) {
            return response()->json(array('success' => true, 'id' => $stylegmtcolorsize->id,'style_gmt_id' => $stylegmtcolorsize->style_gmt_id,'message' => 'Delete Successfully'), 200);
        }
		else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}

    }
	public function getGmtColor(){
		$rows=$this->stylegmtcolorsize
		->selectRaw(
		'style_colors.id,
		colors.name'
		)
		->join('style_colors', function($join)  {
		$join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
		})
		->join('colors', function($join)  {
		$join->on('colors.id', '=', 'style_colors.color_id');
		})
		->where([['style_gmt_color_sizes.style_gmt_id','=',request('style_gmt_id')]])
		->groupBy([
		'style_colors.id',
		'colors.name'
		])
		->get();
		echo json_encode($rows);
	}

}
