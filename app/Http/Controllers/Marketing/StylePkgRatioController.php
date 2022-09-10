<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StylePkgRatioRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StylePkgRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryQtyRepository;
use App\Library\Template;
use App\Http\Requests\StylePkgRatioRequest;

class StylePkgRatioController extends Controller {

  private $stylepkgratio;
  private $style;
  private $stylepkg;
  private $stylegmts;
  private $color;
  private $size;
  private $exfactoryqty;

    public function __construct(StylePkgRatioRepository $stylepkgratio,StyleRepository $style,StylePkgRepository $stylepkg,StyleGmtsRepository $stylegmts, ColorRepository $color,SizeRepository $size,ProdGmtExFactoryQtyRepository $exfactoryqty) {
      $this->stylepkgratio = $stylepkgratio;
      $this->style = $style;
      $this->stylepkg = $stylepkg;
      $this->stylegmts = $stylegmts;
      $this->color = $color;
      $this->size = $size;
      $this->exfactoryqty = $exfactoryqty;
      $this->middleware('auth');
      $this->middleware('permission:view.stylepkgratios',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylepkgratios', ['only' => ['store']]);
      $this->middleware('permission:edit.stylepkgratios',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylepkgratios', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    $stylepkgratios=array();
	  $rows=$this->stylepkgratio
	  ->join('styles',function($join){
		  $join->on('styles.id','=','style_pkg_ratios.style_id');
	  })
	 ->join('style_gmts',function($join){
			$join->on('style_gmts.id','=','style_pkg_ratios.style_gmt_id');
		})
	 ->join('item_accounts',function($join){
			$join->on('item_accounts.id','=','style_gmts.item_account_id');
		})
		->join('colors',function($join){
			$join->on('colors.id','=','style_pkg_ratios.color_id');
		})
		->join('sizes',function($join){
			$join->on('sizes.id','=','style_pkg_ratios.size_id');
		})
	  ->get([
	  'style_pkg_ratios.*',
	  'styles.style_ref',
	  'item_accounts.item_description',
	  'colors.name as color_name',
	   'sizes.name as size_name'
	  ]);
  		foreach($rows as $row){
        $stylepkgratio['id']=	$row->id;
        $stylepkgratio['qty']=	$row->qty;
        $stylepkgratio['style']=	$row->style_ref;
        $stylepkgratio['stylegmts']=$row->item_description;
        $stylepkgratio['color']=	$row->color_name;
        $stylepkgratio['size']=	$row->size_name;
  		   array_push($stylepkgratios,$stylepkgratio);
  		}
        echo json_encode($stylepkgratios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
      $stylepkg=array_prepend(array_pluck($this->stylepkg->get(),'name','id'),'-Select-','');
      $stylegmts=array_prepend(array_pluck($this->stylegmts->get(),'name','id'),'-Select-','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
      $size=array_prepend(array_pluck($this->size->get(),'name','id'),'-Select-','');
      return Template::loadView('Util.StylePkgRatio', ['style'=>$style,'stylepkg'=>$stylepkg,'stylegmts'=>$stylegmts,'color'=>$color,'size'=>$size]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StylePkgRatioRequest $request) {
      $prod_gmt_ex_factory_qties=$this->exfactoryqty
      ->join('prod_gmt_carton_details',function($join){
            $join->on('prod_gmt_carton_details.id', '=' , 'prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id');
        })
      ->join('style_pkgs',function($join){
            $join->on('style_pkgs.id', '=' , 'prod_gmt_carton_details.style_pkg_id');
        })
      ->where([['style_pkgs.id','=',$request->style_pkg_id]])
      ->get(['prod_gmt_ex_factory_qties.id'])
      ->count();
      if($prod_gmt_ex_factory_qties)
      {
        return response()->json(array('success' => false, 'id' => '', 'message' => 'Ex-Factory Found, So Update Not Possible '), 200);
      }
      
				foreach($request->qty as $index=>$val){
					if($val){
						$stylepkgratio = $this->stylepkgratio->updateOrCreate(
						['style_id' => $request->style_id, 'style_pkg_id' => $request->style_pkg_id, 'style_gmt_id' =>  $request->style_gmt_id[$index], 'style_color_id' => $request->style_color_id[$index],'style_size_id' => $request->style_size_id[$index],'style_gmt_color_size_id' => $request->style_gmt_color_size_id[$index]],
						['qty' => $val]
						);
					}
				}


		return response()->json(array('success' => true, 'id' => $stylepkgratio->id, 'message' => 'Save Successfully'), 200);
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
        $stylepkgratio = $this->stylepkgratio->find($id);
        $row ['fromData'] = $stylepkgratio;
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
    public function update(StylePkgRatioRequest $request, $id) {
      $prod_gmt_ex_factory_qties=$this->exfactoryqty
      ->join('prod_gmt_carton_details',function($join){
            $join->on('prod_gmt_carton_details.id', '=' , 'prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id');
        })
      ->join('style_pkgs',function($join){
            $join->on('style_pkgs.id', '=' , 'prod_gmt_carton_details.style_pkg_id');
        })
      ->where([['style_pkgs.id','=',$request->style_pkg_id]])
      ->get(['prod_gmt_ex_factory_qties.id'])
      ->count();
      if($prod_gmt_ex_factory_qties)
      {
        return response()->json(array('success' => false, 'id' => '', 'message' => 'Ex-Factory Found, So Update Not Possible '), 200);
      }

        $stylepkgratio = $this->stylepkgratio->update($id, $request->except(['id']));
        if ($stylepkgratio) {
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
      ->where([['style_pkgs.id','=',$request->style_pkg_id]])
      ->get(['prod_gmt_ex_factory_qties.id'])
      ->count();
      if($prod_gmt_ex_factory_qties)
      {
        return response()->json(array('success' => false, 'id' => '', 'message' => 'Ex-Factory Found, So Update Not Possible '), 200);
      }
        if ($this->stylepkgratio->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
