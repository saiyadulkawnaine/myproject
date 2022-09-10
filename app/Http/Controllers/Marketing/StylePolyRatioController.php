<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StylePolyRatioRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StylePolyRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Library\Template;
use App\Http\Requests\StylePolyRatioRequest;

class StylePolyRatioController extends Controller {

  private $stylepolyratio;
  private $style;
  private $stylepoly;
  private $stylegmts;

    public function __construct(StylePolyRatioRepository $stylepolyratio, StyleRepository $style, StylePolyRepository $stylepoly, StyleGmtsRepository $stylegmts) {
      $this->stylepolyratio = $stylepolyratio;
      $this->style = $style;
      $this->stylepoly = $stylepoly;
      $this->stylegmts = $stylegmts;
      $this->middleware('auth');
      $this->middleware('permission:view.stylepolyratios',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylepolyratios', ['only' => ['store']]);
      $this->middleware('permission:edit.stylepolyratios',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylepolyratios', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		/*$style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');

		$stylepoly=array_prepend(array_pluck($this->stylepoly->get(),'name','id'),'-Select-','');

		$stylegmts = array_prepend(array_pluck($this->stylegmts->leftJoin('item_accounts', function($join)  {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->get([
		'style_gmts.id',
		'item_accounts.item_description',
		]),'item_description','id'),'-Select-',0);*/

		$rows=$this->stylepolyratio
		->join('styles', function($join)  {
		$join->on('styles.id', '=', 'style_poly_ratios.style_id');
		})
		->join('style_gmts', function($join)  {
		$join->on('style_gmts.id', '=', 'style_poly_ratios.style_gmt_id');
		})
		->join('item_accounts', function($join)  {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->where([['style_poly_id','=',request('style_poly_id',0)]])
		->get([
		'style_poly_ratios.id',
		'style_poly_ratios.style_id',
		'style_poly_ratios.style_gmt_id',
		'style_poly_ratios.gmt_ratio',
		'styles.style_ref',
		'item_accounts.item_description',
		]);

		$stylepolyratios=array();
		foreach($rows as $row){
			$stylepolyratio['id']=	$row->id;
			$stylepolyratio['gmtratio']=$row->gmt_ratio;
			$stylepolyratio['style']=$row->style_ref;
			$stylepolyratio['stylegmts']=$row->item_description;
			array_push($stylepolyratios,$stylepolyratio);
		}
		echo json_encode($stylepolyratios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
      $stylepoly=array_prepend(array_pluck($this->stylepoly->get(),'name','id'),'-Select-','');
      $stylegmts=array_prepend(array_pluck($this->stylegmts->get(),'name','id'),'-Select-','');
        return Template::loadView('Util.StylePolyRatio', ['style'=>$style,'stylepoly'=>$stylepoly,'stylegmts'=>$stylegmts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StylePolyRatioRequest $request) {
        $stylepolyratio = $this->stylepolyratio->create($request->except(['id']));
        if ($stylepolyratio) {
            return response()->json(array('success' => true, 'id' => $stylepolyratio->id, 'message' => 'Save Successfully'), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
		$stylepoly=$this->stylepoly->where([['id','=',$id]])->get();

		$style_id=0;
		foreach($stylepoly as $row){
			$style_id=$row->style_id;
		}
		$style=array_prepend(array_pluck($this->style->where([['id','=',$style_id]])->get(),'style_description','id'),'-Select-','');


		$stylegmts = array_prepend(array_pluck($this->stylegmts->leftJoin('item_accounts', function($join)  {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->where("style_id",$style_id)
		->get([
			'style_gmts.id',
			'item_accounts.item_description',
		]),'item_description','id'),'-Select-',0);

        return Template::loadView('Marketing.StylePolyRatio', ['style'=>$style,'stylepoly_id'=>$id,'stylegmts'=>$stylegmts]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $stylepolyratio = $this->stylepolyratio->find($id);
        $row ['fromData'] = $stylepolyratio;
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
    public function update(StylePolyRatioRequest $request, $id) {
        $stylepolyratio = $this->stylepolyratio->update($id, $request->except(['id']));
        if ($stylepolyratio) {
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
        if ($this->stylepolyratio->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
