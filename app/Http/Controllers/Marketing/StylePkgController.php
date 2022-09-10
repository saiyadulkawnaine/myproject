<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StylePkgRepository;
use App\Repositories\Contracts\Marketing\StylePkgRatioRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryQtyRepository;
use App\Library\Template;
use App\Http\Requests\StylePkgRequest;

class StylePkgController extends Controller {

  private $stylepkg;
  private $style;
  private $itemclass;
  private $stylepkgratio;
  private $stylegmts;
  private $stylegmtcolorsize;
  private $exfactoryqty;

    public function __construct(StylePkgRepository $stylepkg,StyleRepository $style,ItemclassRepository $itemclass,StylePkgRatioRepository $stylepkgratio,StyleGmtsRepository $stylegmts,StyleGmtColorSizeRepository $stylegmtcolorsize,ProdGmtExFactoryQtyRepository $exfactoryqty) {
      $this->stylepkg = $stylepkg;
      $this->style = $style;
      $this->itemclass = $itemclass;
  	  $this->stylepkgratio = $stylepkgratio;
  	  $this->stylegmts = $stylegmts;
  	  $this->stylegmtcolorsize = $stylegmtcolorsize;
  	  $this->exfactoryqty = $exfactoryqty;
      $this->middleware('auth');
      $this->middleware('permission:view.stylepkgs',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylepkgs', ['only' => ['store']]);
      $this->middleware('permission:edit.stylepkgs',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylepkgs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		/*$style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');
		$itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');

		$query = $this->stylepkg->query();
		$query->when(request('style_id'), function ($q) {
		return $q->where('style_id', '=', request('style_id', 0));
		});
		$query->when(request('style_gmt_id'), function ($q) {
		return $q->where('style_gmt_id', '=', request('style_gmt_id', 0));
		});
		$rows=$query->get();*/

		$rows = $this->stylepkg->selectRaw(
	    'style_pkgs.id,
		style_pkgs.style_id,
		style_pkgs.spec,
		style_pkgs.assortment_name,
		style_pkgs.itemclass_id,
		styles.style_ref,
		itemclasses.name,
		sum(style_pkg_ratios.qty) as qty'
		)
		->leftJoin('style_pkg_ratios', function($join) {
		$join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
		})
		->join('styles', function($join)  {
		$join->on('styles.id', '=', 'style_pkgs.style_id');
		})
		->join('itemclasses', function($join)  {
		$join->on('itemclasses.id', '=', 'style_pkgs.itemclass_id');
		})
		->when(request('style_id'), function ($q) {
			return $q->where('style_pkgs.style_id', '=', request('style_id', 0));
		})
		->when(request('style_gmt_id'), function ($q) {
			return $q->where('style_pkgs.style_gmt_id', '=', request('style_gmt_id', 0));
		})
		->when(request('assortment_name'), function ($q) {
			return $q->where('style_pkgs.assortment_name', 'like', '%'.request('assortment_name', 0).'%');
		})
		->when(request('spec'), function ($q) {
			return $q->where('style_pkgs.spec', 'like', '%'.request('spec', 0).'%');
		})
		->groupBy([
		'style_pkgs.id',
		'style_pkgs.style_id',
		'style_pkgs.spec',
		'style_pkgs.assortment_name',
		'style_pkgs.itemclass_id',
		'styles.style_ref',
		'itemclasses.name',
		])
		->orderBy('style_pkgs.id','DESC')
		->get();


		$stylepkgs=array();
		foreach($rows as $row){
		$stylepkg['id'] = $row->id;
		$stylepkg['spec'] = $row->spec;
		$stylepkg['style'] =	$row->style_ref;
		$stylepkg['style_id'] = $row->style_id;
		$stylepkg['itemclass'] =	$row->name;
		$stylepkg['assortment_name'] =	$row->assortment_name;
		$stylepkg['qty'] =	$row->qty;
		array_push($stylepkgs,$stylepkg);
		}
		echo json_encode($stylepkgs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
      $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
        return Template::loadView('Util.StylePkg', ['style'=>$style,'itemclass'=>$itemclass]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StylePkgRequest $request) {
        $stylepkg = $this->stylepkg->create($request->except(['id','style_ref']));
        if ($stylepkg) {
            return response()->json(array('success' => true, 'id' => $stylepkg->id, 'message' => 'Save Successfully'), 200);
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

		$stylepkgs = $this->stylepkg->join('styles', function($join)  {
		$join->on('style_pkgs.style_id', '=', 'styles.id');
		})
		->where('style_pkgs.id','=',$id)
		->get([
			'style_pkgs.*',
			'styles.style_ref',
		])->first();




		$colorsizes=$this->stylegmtcolorsize
		->join('style_pkgs', function($join){
			$join->on('style_pkgs.style_id', '=', 'style_gmt_color_sizes.style_id');
		})
		->join('style_gmts', function($join) {
		$join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
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
		->leftJoin('style_pkg_ratios',function($join){
			$join->on('style_pkg_ratios.style_pkg_id','=','style_pkgs.id');
			$join->on('style_pkg_ratios.style_gmt_color_size_id','=','style_gmt_color_sizes.id');
			$join->whereNull('style_pkg_ratios.deleted_at');
		})
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
		 ->where('style_pkgs.id', '=', $id)
		->get([
		'style_gmt_color_sizes.id as style_gmt_color_size_id',
		'style_gmt_color_sizes.style_gmt_id',
		'style_colors.id as style_color_id',
		'style_colors.sort_id as color_sort_id',
		'colors.name as color_name',
		'colors.code as color_code',
		'style_sizes.id as style_size_id',
		'style_sizes.sort_id',
		'sizes.name',
		'sizes.code',
		'item_accounts.item_description',
		'style_pkg_ratios.qty'
		]);

        $row ['fromData'] = $stylepkgs;
        $dropdown['pkgcs'] = "'".Template::loadView('Marketing.GmtColorSizeMatrix',['colorsizes'=>$colorsizes])."'";
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
    public function update(StylePkgRequest $request, $id) {
    	$prod_gmt_ex_factory_qties=$this->exfactoryqty
      ->join('prod_gmt_carton_details',function($join){
            $join->on('prod_gmt_carton_details.id', '=' , 'prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id');
        })
      ->join('style_pkgs',function($join){
            $join->on('style_pkgs.id', '=' , 'prod_gmt_carton_details.style_pkg_id');
        })
      ->where([['style_pkgs.id','=',$id]])
      ->get(['prod_gmt_ex_factory_qties.id'])
      ->count();
      if($prod_gmt_ex_factory_qties)
      {
        return response()->json(array('success' => false, 'id' => '', 'message' => 'Ex-Factory Found, So Update Not Possible '), 200);
      }
        $stylepkg = $this->stylepkg->update($id, $request->except(['id','style_ref']));
        if ($stylepkg) {
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
    	
		$prod_gmt_ex_factory_qties=$this->exfactoryqty
		->join('prod_gmt_carton_details',function($join){
		$join->on('prod_gmt_carton_details.id', '=' , 'prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id');
		})
		->join('style_pkgs',function($join){
		$join->on('style_pkgs.id', '=' , 'prod_gmt_carton_details.style_pkg_id');
		})
		->where([['style_pkgs.id','=',$id]])
		->get(['prod_gmt_ex_factory_qties.id'])
		->count();
		if($prod_gmt_ex_factory_qties)
		{
		return response()->json(array('success' => false, 'id' => '', 'message' => 'Ex-Factory Found, So Delete Not Possible '), 200);
		}

        if ($this->stylepkg->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
		}
		
		public function getAssortmentName(Request $request) {
			return $this->stylepkg->where([['assortment_name', 'LIKE', '%'.$request->q.'%']])->orderBy('assortment_name', 'asc')->get(['assortment_name as name']);
		}
	
		public function getSpec(Request $request) {
			return $this->stylepkg->where([['spec', 'LIKE', '%'.$request->q.'%']])->orderBy('spec', 'asc')->get(['spec as name']);
		}

}
