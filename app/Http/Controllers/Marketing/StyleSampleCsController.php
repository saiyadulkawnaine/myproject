<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleSampleCsRepository;
use App\Repositories\Contracts\Marketing\StyleSampleRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Library\Template;
use App\Http\Requests\StyleSampleCsRequest;

class StyleSampleCsController extends Controller {

  private $stylesamplecs;
  private $stylesample;
  private $color;
  private $size;

    public function __construct(StyleSampleCsRepository $stylesamplecs,StyleSampleRepository $stylesample,ColorRepository $color,SizeRepository $size) {
      $this->stylesamplecs = $stylesamplecs;
      $this->stylesample = $stylesample;
      $this->color = $color;
      $this->size = $size;
      $this->middleware('auth');
      $this->middleware('permission:view.stylesamplecss',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylesamplecss', ['only' => ['store']]);
      $this->middleware('permission:edit.stylesamplecss',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylesamplecss', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      //$stylesample=array_prepend(array_pluck($this->stylesample->get(),'name','id'),'-Select-','');
      //$color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
      //$size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');
      $stylesamplecss=array();
	    $rows=$this->stylesamplecs
		->join('colors',function($join){
			$join->on('colors.id','=','style_sample_cs.color_id');
		})
		->join('sizes',function($join){
			$join->on('sizes.id','=','style_sample_cs.size_id');
		})
		->get([
		'style_sample_cs.*',
		'colors.name as color_name',
		'sizes.name as size_name',
		]);
  		foreach($rows as $row){
        $stylesamplecs['id']=	$row->id;
        //$stylesamplecs['stylesample']=	$stylesample[$row->style_sample_id];
        $stylesamplecs['color']=	$row->color_name;
        $stylesamplecs['size']=	$row->size_name;
  		   array_push($stylesamplecss,$stylesamplecs);
  		}
        echo json_encode($stylesamplecss);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $stylesample=array_prepend(array_pluck($this->stylesample->get(),'name','id'),'-Select-','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
      $size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');
        return Template::loadView('Util.StyleSampleCs', ['stylesample'=>$stylesample,'color'=>$color,'size'=>$size]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleSampleCsRequest $request) {

		foreach($request->color as $index=>$style_color_id){
				if($request->qty[$index]){
				$stylesamplecs = $this->stylesamplecs->updateOrCreate(
				['style_sample_id' => $request->style_sample_id,'style_gmt_color_size_id' => $request->style_gmt_color_size_id[$index],'style_color_id' => $style_color_id,'style_size_id' => $request->size[$index]],
				['qty' => $request->qty[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
				);
				}
			}
		/*$data=$request->only(['qty','style_sample_id']);
		foreach($data['qty'] as $colorId=>$sizes){
			foreach($sizes as $sizeId=>$val){
				if($val){
				$stylesamplecs = $this->stylesamplecs->updateOrCreate(
				['style_sample_id' => $data['style_sample_id'], 'style_color_id' => $colorId,'style_size_id' => $sizeId],
				['qty' => $val]
				);
				}
			}
		}*/
		return response()->json(array('success' => true, 'id' => $stylesamplecs->id, 'message' => 'Save Successfully'), 200);

        /*$stylesamplecs = $this->stylesamplecs->create($request->except(['id']));
        if ($stylesamplecs) {
            return response()->json(array('success' => true, 'id' => $stylesamplecs->id, 'message' => 'Save Successfully'), 200);
        }*/
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
        $stylesamplecs = $this->stylesamplecs->find($id);
        $row ['fromData'] = $stylesamplecs;
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
    public function update(StyleSampleCsRequest $request, $id) {
        $stylesamplecs = $this->stylesamplecs->update($id, $request->except(['id']));
        if ($stylesamplecs) {
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
        if ($this->stylesamplecs->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
