<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\StyleGmtsRequest;

class StyleGmtsController extends Controller {

  private $stylegmts;
  private $style;
  private $itemaccount;
  private $stylegmtcolorsize;

    public function __construct(StyleGmtsRepository $stylegmts, StyleRepository $style,ItemAccountRepository $itemaccount,StyleGmtColorSizeRepository $stylegmtcolorsize) {
      $this->stylegmts = $stylegmts;
      $this->style = $style;
      $this->itemaccount = $itemaccount;
  	  $this->stylegmtcolorsize = $stylegmtcolorsize;
      $this->middleware('auth');
      $this->middleware('permission:view.stylegmtss',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylegmtss', ['only' => ['store']]);
      $this->middleware('permission:edit.stylegmtss',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylegmtss', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
		$stylegmtss=array();
		$rows = $this->stylegmts->leftJoin('item_accounts', function($join)  {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->join('styles', function($join)  {
		$join->on('style_gmts.style_id', '=', 'styles.id');
		})
		->join('itemcategories', function($join)  {
		$join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
		})
		->when(request('style_id'), function ($q) {
		return $q->where('style_id', '=', request('style_id', 0));
		})
		->leftJoin('users', function($join){
		$join->on('users.id','=','style_gmts.created_by');
		})
		->leftJoin('users as updated_users', function($join){
		$join->on('updated_users.id','=','style_gmts.updated_by');
		})
		->get([
		'style_gmts.*',
		'styles.style_ref',
		'item_accounts.item_description as name',
		'itemcategories.name as itemcaegory_name',
		'users.name as created_by_user',
		'updated_users.name as updated_by_user'
		]);

		foreach($rows as $row){
		$stylegmts['id']=	$row->id;
		$stylegmts['gmtqty']=	$row->gmt_qty;
		$stylegmts['gmtcategory']=	$row->itemcaegory_name;
		$stylegmts['style']=	$row->style_ref;
		$stylegmts['style_id']=	$row->style_id;
		$stylegmts['style_ref']=$row->style_ref;
		$stylegmts['itemcomplexity']=	$itemcomplexity[$row->item_complexity];
		$stylegmts['itemaccount']=	$row->name;
		$stylegmts['name']=	$row->name;
		$stylegmts['sewing_effi_per']= $row->sewing_effi_per;
		$stylegmts['smv']= $row->smv;
		$stylegmts['remarks']= $row->remarks;
		$stylegmts['article']= $row->article;
		$stylegmts['no_of_man_power']= $row->no_of_man_power;
		$stylegmts['prod_per_hour']= $row->prod_per_hour;
		$stylegmts['created_by_user']= $row->created_by_user;
		$stylegmts['updated_by_user']=  $row->updated_by_user;
		$stylegmts['created_at']= date('d-M-Y h:i A',strtotime($row->created_at));
		$stylegmts['updated_at']=date('d-M-Y h:i A',strtotime($row->updated_at));
		array_push($stylegmtss,$stylegmts);
		}
		echo json_encode($stylegmtss);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
      $itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
      $itemaccount=array_prepend(array_pluck($this->itemaccount->get(),'name','id'),'-Select-','');
        return Template::loadView('Marketing.StyleGmts', ['style'=> $style,'itemcomplexity'=>$itemcomplexity,'itemaccount'=>$itemaccount]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleGmtsRequest $request) {
        $stylegmts = $this->stylegmts->create($request->except(['id','style_ref']));
        if ($stylegmts) {
            return response()->json(array('success' => true, 'id' => $stylegmts->id, 'style_id' => $stylegmts->style_id,'message' => 'Save Successfully'), 200);
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
		$stylegmts = $this->stylegmts->join('styles', function($join)  {
		$join->on('style_gmts.style_id', '=', 'styles.id');
		})
		->join('item_accounts', function($join)  {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->where('style_gmts.id','=',$id)
		->get([
			'style_gmts.*',
			'item_accounts.gmt_category as gmt_catg',
			'styles.style_ref',

		])->first();

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
		->leftJoin('style_gmt_color_sizes',function($join) use($id,$stylegmts){
			$join->on('style_gmts.id','=','style_gmt_color_sizes.style_gmt_id')
			->on('style_colors.id','=','style_gmt_color_sizes.style_color_id')
			->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id')
			->where('style_gmts.style_id','=',$stylegmts->style_id)
			->where('style_gmt_color_sizes.deleted_at','=',null);

		})
		->where('style_gmts.id','=',$id)
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
		->get([
		'styles.id as style_id',
		'style_gmts.id as style_gmt_id',
		'style_colors.id as style_color_id',
		'style_sizes.id as style_size_id',
		'colors.name as color_name',
		'sizes.name as size_name',
		'style_gmt_color_sizes.id as ck'
		]);
		
		$saved = $colorSize->filter(function ($value) {
			if($value->ck){
				return $value;
			}
		});
		$new = $colorSize->filter(function ($value) {
			if(!$value->ck){
				return $value;
			}
		});
        $row ['fromData'] = $stylegmts;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
		$row ['colorSize'] = $new;
		$row ['savedcolorSize'] = $saved;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StyleGmtsRequest $request, $id) {
        $stylegmts = $this->stylegmts->update($id, $request->except(['id','style_ref']));
        if ($stylegmts) {
            return response()->json(array('success' => true, 'id' => $id,'style_id' => $request->style_id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->stylegmts->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
