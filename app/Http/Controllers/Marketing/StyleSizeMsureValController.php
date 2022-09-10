<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleSizeMsureValRepository;
use App\Repositories\Contracts\Marketing\StyleSizeRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Library\Template;
use App\Http\Requests\StyleSizeMsureValRequest;

class StyleSizeMsureValController extends Controller {

    private $stylesizemsureval;
    private $stylesize;
    private $style;
    private $stylegmts;
    private $uom;
    private $size;

    public function __construct(StyleSizeMsureValRepository $stylesizemsureval,StyleSizeRepository $stylesize,StyleRepository $style,StyleGmtsRepository $stylegmts,UomRepository $uom,SizeRepository $size) {
      $this->stylesizemsureval = $stylesizemsureval;
      $this->stylesize      = $stylesize;
      $this->style          = $style;
      $this->stylegmts      = $stylegmts;
      $this->uom            = $uom;
      $this->size           = $size;
      $this->middleware('auth');
      $this->middleware('permission:view.stylesizemsurevals',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylesizemsurevals', ['only' => ['store']]);
      $this->middleware('permission:edit.stylesizemsurevals',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylesizemsurevals', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        /*$stylesize=array_prepend(array_pluck($this->stylesize->leftJoin('sizes', function($join) {
		$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
		->get([
		'style_sizes.id',
		'sizes.name',
		]),'name','id'),'-Select-','');
        $style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');
		$stylegmts = array_prepend(array_pluck($this->stylegmts->leftJoin('item_accounts', function($join) {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->get([
		'style_gmts.id',
		'item_accounts.item_description',
		]),'item_description','id'),'-Select-',0);*/
     // $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
      //$size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');
      $stylesizemsurevals=array();
	    $rows=$this->stylesizemsureval
		->join('styles', function($join)  {
		$join->on('styles.id', '=', 'style_size_msure_vals.style_id');
		})
		->join('style_gmts', function($join)  {
		$join->on('style_gmts.id', '=', 'style_size_msure_vals.style_gmt_id');
		})
		->join('item_accounts', function($join)  {
		$join->on('item_accounts.id', '=', 'style_size_msure_vals.item_account_id');
		})
		->join('uoms', function($join)  {
		$join->on('uoms.id', '=', 'style_size_msure_vals.uom_id');
		})
		->get([
		'style_size_msure_vals.*',
		'styles.style_ref',
		'item_accounts.item_description',
		'uoms.name as uom_name'
		]);
  		foreach($rows as $row){
        $stylesizemsureval['id']=	$row->id;
        $stylesizemsureval['msurepoint'] =	$row->msure_point;
        $stylesizemsureval['tollerance'] =	$row->tollerance;
        $stylesizemsureval['msurevalue'] =	$row->msure_value;
        $stylesizemsureval['style']      =	$row->style_ref;
        $stylesizemsureval['stylegmts']  =	$row->item_description;
        $stylesizemsureval['uom']        =	$row->uom_name;
        //$stylesizemsureval['size']       =	$stylesize[$row->style_size_id];
  		   array_push($stylesizemsurevals,$stylesizemsureval);
  		}
        echo json_encode($stylesizemsurevals);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $stylesize=array_prepend(array_pluck($this->stylesize->get(),'name','id'),'-Select-','');
      $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
      $stylegmts=array_prepend(array_pluck($this->stylegmts->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
      $size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');
      return Template::loadView("Util.StyleSizeMsure", ["stylesize"=> $stylesize,'style'=>$style,'stylegmts'=>$stylegmts,'uom'=>$uom,'size'=>$size]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleSizeMsureValRequest $request) {
       /* $stylesizemsureval = $this->stylesizemsureval->create($request->except(['id']));
        if ($stylesizemsureval) {
            return response()->json(array('success' => true, 'id' => $stylesizemsureval->id, 'message' => 'Save Successfully'), 200);
        }*/

		$data=$request->only(['size','style_id','style_gmt_id','style_size_id','style_size_msure_id']);
		foreach($data['size'] as $style_size_id=>$msure_value){


			if($msure_value){
				$stylesizemsureval = $this->stylesizemsureval->updateOrCreate(
				['style_id' => $data['style_id'], 'style_gmt_id' => $data['style_gmt_id'],'style_size_msure_id' => $data['style_size_msure_id'],'style_size_id' => $style_size_id],
				['msure_value' => $msure_value]
				);
			}

		}
		return response()->json(array('success' => true, 'id' => $stylesizemsureval->id, 'message' => 'Save Successfully'), 200);
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
        $stylesizemsureval = $this->stylesizemsureval->find($id);

		$sizes=$this->stylesizemsureval->rightJoin('style_sizes', function($join) use($stylesizemsureval) {
		$join->on('style_size_msures.style_size_id', '=', 'style_sizes.id')
		     ->where('style_size_msures.style_gmt_id', '=', $stylesizemsureval->style_gmt_id);
		})
		->join('sizes', function($join) {
		$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
		->orderBy('style_sizes.sort_id')
		->get([
		'style_size_msures.*',
		'style_sizes.id as stylesize',
		'sizes.name',
		]);
		//dd(DB::getQueryLog());
        $row ['fromData'] = $stylesizemsureval;
        $dropdown['sizeMatrix'] = "'".Template::loadView('Marketing.SizeMatrix',['sizes'=>$sizes])."'";
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
    public function update(StyleSizeMsureValRequest $request, $id) {
        $stylesizemsureval = $this->stylesizemsureval->update($id, $request->except(['id']));
        if ($stylesizemsureval) {
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
        if ($this->stylesizemsureval->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
