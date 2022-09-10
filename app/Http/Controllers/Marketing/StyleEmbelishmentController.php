<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleEmbelishmentRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;

use App\Library\Template;
use App\Http\Requests\StyleEmbelishmentRequest;

class StyleEmbelishmentController extends Controller {

  private $styleembelishment;
  private $style;
  private $stylegmts;
  private $embelishment;
  private $embelishmenttype;
  private $productionprocess;

    public function __construct(
      StyleEmbelishmentRepository $styleembelishment, 
      StyleRepository $style,
      StyleGmtsRepository $stylegmts,
      EmbelishmentRepository $embelishment,
      EmbelishmentTypeRepository $embelishmenttype,
      ProductionProcessRepository $productionprocess) {
      $this->styleembelishment = $styleembelishment;
      $this->style = $style;
      $this->stylegmts = $stylegmts;
      $this->embelishment = $embelishment;
      $this->embelishmenttype = $embelishmenttype;
	  $this->productionprocess = $productionprocess;
      $this->middleware('auth');
      $this->middleware('permission:view.styleembelishments',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.styleembelishments', ['only' => ['store']]);
      $this->middleware('permission:edit.styleembelishments',   ['only' => ['update']]);
      $this->middleware('permission:delete.styleembelishments', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
		//$style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');

		/*$stylegmts = array_prepend(array_pluck($this->stylegmts->leftJoin('item_accounts', function($join) use ($request) {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->get([
		'style_gmts.id',
		'item_accounts.item_description',
		]),'item_description','id'),'-Select-',0);*/

		//$embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'-Select-','');

		//$embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->get(),'name','id'),'-Select-','');

       $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
		$query = $this->styleembelishment->query();
		$query->join('styles',function($join){
			$join->on('styles.id','=','style_embelishments.style_id');
		});
		$query->join('style_gmts',function($join){
			$join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
		});
		$query->join('item_accounts',function($join){
			$join->on('item_accounts.id','=','style_gmts.item_account_id');
		});
		$query->join('embelishments',function($join){
			$join->on('embelishments.id','=','style_embelishments.embelishment_id');
		});
		$query->join('embelishment_types',function($join){
			$join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
		});
		$query->when(request('style_id'), function ($q) {
		return $q->where('style_embelishments.style_id', '=', request('style_id', 0));
		});

		$query->when(request('style_gmt_id'), function ($q) {
		return $q->where('style_embelishments.style_gmt_id', '=', request('style_gmt_id', 0));
		});
		$rows=$query->get([
		'style_embelishments.*',
		'styles.style_ref',
		'item_accounts.item_description',
		'embelishments.name as embelishment_name',
		'embelishment_types.name as embelishment_type_name'
		]);
		$styleembelishments=array();
		foreach($rows as $row){
			$styleembelishment['id']=	$row->id;
			$styleembelishment['sort']=	$row->sort_id;
			$styleembelishment['style']=	$row->style_ref;
			$styleembelishment['stylegmts']=	$row->item_description;
			$styleembelishment['embelishment']=	$row->embelishment_name;
			$styleembelishment['embelishment_id']=	$row->embelishment_id;
			$styleembelishment['embelishmenttype']=	$row->embelishment_type_name;
			$styleembelishment['embelishmentsize']=	 $embelishmentsize[$row->embelishment_size_id];
			$styleembelishment['embelishment_size_id']=	 $row->embelishment_size_id;
			array_push($styleembelishments,$styleembelishment);
		}
		echo json_encode($styleembelishments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
      $stylegmts=array_prepend(array_pluck($this->stylegmts->get(),'name','id'),'-Select-','');
      $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'-Select-','');
      $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->get(),'name','id'),'-Select-','');
      return Template::loadView('Marketing.StyleEmbelishment', ['style'=>$style,'stylegmts'=>$stylegmts,'embelishment'=>$embelishment,'embelishmenttype'=>$embelishmenttype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleEmbelishmentRequest $request) {
        $styleembelishment = $this->styleembelishment->create($request->except(['id','style_ref','production_area_id']));
        if ($styleembelishment) {
            return response()->json(array('success' => true, 'id' => $styleembelishment->id, 'message' => 'Save Successfully'), 200);
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
        //$styleembelishment = $this->styleembelishment->find($id);
		$styleembelishment = $this->styleembelishment->join('styles', function($join)  {
		$join->on('style_embelishments.style_id', '=', 'styles.id');
		})
		->where('style_embelishments.id','=',$id)
		->get([
			'style_embelishments.*',
			'styles.style_ref',
		]);
        $row ['fromData'] = $styleembelishment[0];
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
    public function update(StyleEmbelishmentRequest $request, $id) {
        $styleembelishment = $this->styleembelishment->update($id, $request->except(['id','style_ref','production_area_id']));
        if ($styleembelishment) {
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
        if ($this->styleembelishment->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	
	public function getEmbtype(){
		$embelishment=$this->embelishment->find(request('embelishment_id',0));
		$productionprocess=$this->productionprocess->find($embelishment->production_process_id);

		$row['embelishmenttype']=$this->embelishmenttype->where([['embelishment_id','=',request('embelishment_id',0)]])->get();
		$row['embelishment']=['production_area_id'=>$productionprocess->production_area_id];
		 echo json_encode($row);
	}

}
