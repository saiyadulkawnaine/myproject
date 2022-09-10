<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleSampleRepository;
use App\Repositories\Contracts\Marketing\StyleSampleCsRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\GmtssampleRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Library\Template;
use App\Http\Requests\StyleSampleRequest;

class StyleSampleController extends Controller {

  private $stylesample;
  private $style;
  private $stylegmts;
  private $gmtssample;
  private $currency;
  private $stylesamplecs;
  private $stylegmtcolorsize;

    public function __construct(StyleSampleRepository $stylesample,StyleRepository $style,StyleGmtsRepository $stylegmts,GmtssampleRepository $gmtssample,CurrencyRepository $currency,StyleSampleCsRepository $stylesamplecs,StyleGmtColorSizeRepository $stylegmtcolorsize) {
      $this->stylesample  = $stylesample;
      $this->style        = $style;
      $this->stylegmts    = $stylegmts;
      $this->gmtssample   = $gmtssample;
      $this->currency     = $currency;
  	  $this->stylesamplecs     = $stylesamplecs;
  	  $this->stylegmtcolorsize     = $stylegmtcolorsize;
      $this->middleware('auth');
      $this->middleware('permission:view.stylesamples',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylesamples', ['only' => ['store']]);
      $this->middleware('permission:edit.stylesamples',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylesamples', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
		$rows = $this->stylesample->selectRaw(
	    'style_samples.id,
		style_samples.style_id,
		style_samples.style_gmt_id,
		style_samples.sort_id,
		style_samples.gmtssample_id,
		styles.style_ref,
		item_accounts.item_description,
		gmtssamples.name,
		sum(style_sample_cs.qty) as qty,
		sum(style_sample_cs.amount) as amount'
		)
		->leftJoin('style_sample_cs', function($join) {
		$join->on('style_sample_cs.style_sample_id', '=', 'style_samples.id');
		})
		->join('styles', function($join)  {
		$join->on('styles.id', '=', 'style_samples.style_id');
		})
		->join('style_gmts', function($join)  {
		$join->on('style_gmts.id', '=', 'style_samples.style_gmt_id');
		})
		->join('item_accounts', function($join)  {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->join('gmtssamples', function($join)  {
		$join->on('gmtssamples.id', '=', 'style_samples.gmtssample_id');
		})
		->when(request('style_id'), function ($q) {
			return $q->where('style_samples.style_id', '=', request('style_id', 0));
		})
		->when(request('style_gmt_id'), function ($q) {
			return $q->where('style_samples.style_gmt_id', '=', request('style_gmt_id', 0));
		})
		->groupBy([
		'style_samples.id',
		'style_samples.style_id',
		'style_samples.style_gmt_id',
		'style_samples.sort_id',
		'style_samples.gmtssample_id',
		'styles.style_ref',
		'item_accounts.item_description',
		'gmtssamples.name',
		])
		->get();
		$stylesamples=array();
		foreach($rows as $row){
			$stylesample['id']=	$row->id;
			$stylesample['sequence']=	$row->sort_id;
			$stylesample['style']=	$row->style_ref;
			$stylesample['qty']=	$row->qty;
			$stylesample['amount']=	$row->amount;
			if($row->qty && $row->amount){
				$stylesample['rate']=	$row->amount/$row->qty;
			}
			$stylesample['stylegmts']=$row->item_description;
			$stylesample['gmtssample']=	$row->name;
			array_push($stylesamples,$stylesample);
		}
		echo json_encode($stylesamples);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
      $stylegmts=array_prepend(array_pluck($this->stylegmts->get(),'name','id'),'-Select-','');
      $gmtssample=array_prepend(array_pluck($this->gmtssample->get(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
      return Template::loadView('Util.StyleSample', ['style'=>$style,'stylegmts'=>$stylegmts,'gmtssample'=>$gmtssample,'currency'=>$currency,'yesno'=>$yesno]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleSampleRequest $request) {
        $stylesample = $this->stylesample->create($request->except(['id','style_ref']));
        if ($stylesample) {
            return response()->json(array('success' => true, 'id' => $stylesample->id, 'message' => 'Save Successfully'), 200);
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
		$stylesample = $this->stylesample->find($id);
		$stylesamples = $this->stylesample->join('styles', function($join)  {
		$join->on('style_samples.style_id', '=', 'styles.id');
		})
		->where('style_samples.id','=',$id)
		->get([
			'style_samples.*',
			'styles.style_ref',
		]);
		//$stylesamplecs=$this->stylesamplecs->matrix($id);

		$colorsizes=$this->stylegmtcolorsize->join('style_samples', function($join){
			$join->on('style_samples.style_gmt_id', '=', 'style_gmt_color_sizes.style_gmt_id');
		})
		->join('style_colors', function($join) {
			$join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
		})
		->join('colors', function($join) {
			$join->on('style_colors.color_id', '=', 'colors.id');
		})
		->join('style_sizes', function($join) {
			$join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
		})
		->join('sizes', function($join) {
			$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
		->leftJoin('style_sample_cs',function($join){
			$join->on('style_sample_cs.style_sample_id','=','style_samples.id');
			$join->on('style_sample_cs.style_gmt_color_size_id','=','style_gmt_color_sizes.id');
		})
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
		->where('style_samples.id', '=', $id)
		->get([
		'style_gmt_color_sizes.id as style_gmt_color_size_id',
		'style_colors.id as stylecolor',
		'style_colors.sort_id',
		'colors.name as color_name',
		'colors.code as color_code',
		'style_sizes.id as stylesize',
		'style_sizes.sort_id',
		'sizes.name',
		'sizes.code',
		'style_sample_cs.qty',
		'style_sample_cs.rate',
		'style_sample_cs.amount'
		]);

		$row ['fromData'] = $stylesamples[0];
		$dropdown['scs'] = "'".Template::loadView('Marketing.ColorSizeMatrix',['colorsizes'=>$colorsizes])."'";
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
    public function update(StyleSampleRequest $request, $id) {
        $stylesample = $this->stylesample->update($id, $request->except(['id','style_ref']));
        if ($stylesample) {
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
        if ($this->stylesample->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
