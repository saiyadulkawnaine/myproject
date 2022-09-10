<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleSizeMsureRepository;
use App\Repositories\Contracts\Marketing\StyleSizeRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Library\Template;
use App\Http\Requests\StyleSizeMsureRequest;

class StyleSizeMsureController extends Controller {

    private $stylesizemsure;
    private $stylesize;
    private $style;
    private $stylegmts;
    private $uom;
    private $size;
	  private $stylegmtcolorsize;

    public function __construct(StyleSizeMsureRepository $stylesizemsure,StyleSizeRepository $stylesize,StyleRepository $style,StyleGmtsRepository $stylegmts,UomRepository $uom,SizeRepository $size,StyleGmtColorSizeRepository $stylegmtcolorsize) {
      $this->stylesizemsure = $stylesizemsure;
      $this->stylesize      = $stylesize;
      $this->style          = $style;
      $this->stylegmts      = $stylegmts;
      $this->uom            = $uom;
      $this->size           = $size;
	    $this->stylegmtcolorsize           = $stylegmtcolorsize;
      $this->middleware('auth');
      $this->middleware('permission:view.stylesizemsures',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylesizemsures', ['only' => ['store']]);
      $this->middleware('permission:edit.stylesizemsures',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylesizemsures', ['only' => ['destroy']]);
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
		]),'name','id'),'-Select-','');*/

		//$style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');

/*		$stylegmts = array_prepend(array_pluck($this->stylegmts->leftJoin('item_accounts', function($join) {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->get([
		'style_gmts.id',
		'item_accounts.item_description',
		]),'item_description','id'),'-Select-',0);*/

		//$uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');

		//$size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');


		$query = $this->stylesizemsure->query();
		$query->join('styles', function($join)  {
			$join->on('styles.id', '=', 'style_size_msures.style_id');
		});
		$query->join('style_gmts', function($join)  {
			$join->on('style_gmts.id', '=', 'style_size_msures.style_gmt_id');
		});
		$query->join('item_accounts', function($join)  {
			$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		});
		$query->join('uoms', function($join)  {
			$join->on('uoms.id', '=', 'style_size_msures.uom_id');
		});
		$query->when(request('style_id'), function ($q) {
			return $q->where('style_size_msures.style_id', '=', request('style_id', 0));
		});
		$query->when(request('style_gmt_id'), function ($q) {
			return $q->where('style_size_msures.style_gmt_id', '=', request('style_gmt_id', 0));
		});
		$rows=$query->get([
		'style_size_msures.*',
		'styles.style_ref',
		'item_accounts.item_description',
		'uoms.name as uom_name'
		]);

		$stylesizemsures=array();
		foreach($rows as $row){
		$stylesizemsure['id']=	$row->id;
		$stylesizemsure['msurepoint'] =	$row->msure_point;
		$stylesizemsure['tollerance'] =	$row->tollerance;
		$stylesizemsure['msurevalue'] =	$row->msure_value;
		$stylesizemsure['style']      =	$row->style_ref;
		$stylesizemsure['style_id']   =	$row->style_id;
		$stylesizemsure['stylegmts']  =	$row->item_description;
		$stylesizemsure['style_gmt_id']  =	$row->style_gmt_id;
		$stylesizemsure['uom']        =	$row->uom_name;
		$stylesizemsure['sort_id']    =	$row->sort_id;
		array_push($stylesizemsures,$stylesizemsure);
		}
		echo json_encode($stylesizemsures);
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
    public function store(StyleSizeMsureRequest $request) {
        $stylesizemsure = $this->stylesizemsure->create($request->except(['id','style_ref']));
        if ($stylesizemsure) {
            return response()->json(array('success' => true, 'id' => $stylesizemsure->id,'style_gmt_id' => $request->style_gmt_id, 'message' => 'Save Successfully'), 200);
        }

		/*$data=$request->only(['size','style_id','style_gmt_id','uom_id','msure_point','tollerance','style_size_id','msure_value']);
		foreach($data['size'] as $style_size_id=>$msure_value){

				$stylesizemsure = $this->stylesizemsure->updateOrCreate(
				['style_id' => $data['style_id'], 'style_gmt_id' => $data['style_gmt_id'], 'uom_id' => $data['uom_id'], 'msure_point' => $data['msure_point'],'tollerance' => $data['tollerance'],'style_size_id' => $style_size_id],
				['msure_value' => $msure_value]
				);

		}
		return response()->json(array('success' => true, 'id' => $stylesizemsure->id, 'message' => 'Save Successfully'), 200);*/
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
       // $stylesizemsure = $this->stylesizemsure->find($id);

		$stylesizemsure = $this->stylesizemsure->join('styles', function($join)  {
		$join->on('style_size_msures.style_id', '=', 'styles.id');
		})
		->where('style_size_msures.id','=',$id)
		->get([
			'style_size_msures.*',
			'styles.style_ref',
		]);

		/*$sizes=$this->stylesizemsure->rightJoin('style_sizes', function($join) use($id) {
		$join->on('style_size_msures.style_id', '=', 'style_sizes.style_id');

		})
		->leftJoin('style_size_msure_vals', function($join) use($id) {
		$join->on('style_sizes.id', '=', 'style_size_msure_vals.style_size_id');
		$join->on('style_size_msures.id', '=', 'style_size_msure_vals.style_size_msure_id');
		})
		->join('sizes', function($join) {
		$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
		->orderBy('style_sizes.sort_id')
		 ->where('style_size_msures.id', '=', $id)
		->get([
		'style_size_msures.*',
		'style_sizes.id as stylesize',
		'sizes.name',
		'style_size_msure_vals.msure_value'
		]);*/
	    $sizes=$this->stylegmtcolorsize->join('style_size_msures', function($join){
			$join->on('style_size_msures.style_gmt_id', '=', 'style_gmt_color_sizes.style_gmt_id');
		})
		->leftJoin('style_size_msure_vals', function($join) {
		$join->on('style_gmt_color_sizes.style_size_id', '=', 'style_size_msure_vals.style_size_id');
		$join->on('style_size_msures.id', '=', 'style_size_msure_vals.style_size_msure_id');
		})
		->join('style_sizes', function($join) {
		$join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
		})
		->join('sizes', function($join) {
		$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
		->orderBy('style_sizes.sort_id')
		->groupBy(['style_size_msures.id',
		'style_sizes.id',
		'style_sizes.sort_id',
		'sizes.name',
		'style_size_msure_vals.msure_value'])
		 ->where('style_size_msures.id', '=', $id)
		->get([
		'style_size_msures.id',
		'style_sizes.id as stylesize',
		'style_sizes.sort_id',
		'sizes.name',
		'style_size_msure_vals.msure_value'
		]);

        $row ['fromData'] = $stylesizemsure[0];
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
    public function update(StyleSizeMsureRequest $request, $id) {
        $stylesizemsure = $this->stylesizemsure->update($id, $request->except(['id','style_ref']));
        if ($stylesizemsure) {
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
        if ($this->stylesizemsure->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
