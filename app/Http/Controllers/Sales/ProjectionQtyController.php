<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\ProjectionQtyRepository;
use App\Repositories\Contracts\Sales\ProjectionRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SeasonRepository;
use App\Library\Template;
use App\Http\Requests\ProjectionQtyRequest;

class ProjectionQtyController extends Controller {

    private $projectionqty;
    private $projection;
    private $style;
    private $stylegmts;
    private $currency;
    private $country;
    private $uom;
    private $season;

    public function __construct(ProjectionQtyRepository $projectionqty, ProjectionRepository $projection,StyleRepository $style,StyleGmtsRepository $stylegmts,CurrencyRepository $currency,CountryRepository $country,UomRepository $uom,SeasonRepository $season) {
      $this->projectionqty  = $projectionqty;
      $this->projection     = $projection;
      $this->style          = $style;
      $this->stylegmts      = $stylegmts;
      $this->currency       = $currency;
      $this->country        = $country;
      $this->uom            = $uom;
      $this->seasons        = $season;
      $this->middleware('auth');
      $this->middleware('permission:view.projectionqtys',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.projectionqtys', ['only' => ['store']]);
      $this->middleware('permission:edit.projectionqtys',   ['only' => ['update']]);
      $this->middleware('permission:delete.projectionqtys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $projection=array_prepend(array_pluck($this->projection->get(),'name','id'),'-Select-','');
      $style=array_prepend(array_pluck($this->style->get(),'name','id'),'-Select-','');
      $stylegmts=array_prepend(array_pluck($this->stylegmts->get(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
      $season=array_prepend(array_pluck($this->seasons->get(),'name','id'),'-Select-','');
      $projectionqtys=array();
	    $rows=$this->projectionqty->get();
  		foreach($rows as $row){
        $projectionqty['id']=	$row->id;
        $projectionqty['qty']=	$row->qty;
        $projectionqty['rate']=	$row->rate;
        $projectionqty['amount']=	$row->amount;
        $projectionqty['projno']=	$row->proj_no;
        $projectionqty['shipdate']=	$row->ship_date;
        $projectionqty['fileno']=	$row->file_no;
        $projectionqty['exchrate']=	$row->exch_rate;
        $projectionqty['projection']=	$projection[$row->projection_id];
        $projectionqty['style']=	$style[$row->style_id];
        $projectionqty['stylegmts']=	$stylegmts[$row->style_gmt_id];
        $projectionqty['currency']=	$currency[$row->currency_id];
        $projectionqty['country']=	$country[$row->country_id];
        $projectionqty['uom']=	$uom[$row->uom_id];
        $projectionqty['season']=	$season[$row->season_id];
  		   array_push($projectionqtys,$projectionqty);
  		}
        echo json_encode($projectionqtys);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

      $stylegmts=$this->stylegmts->join('projections',function($join){
		  $join->on('projections.style_id','=','style_gmts.style_id');
	  })
	  ->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
	  })
	  ->join('projection_countries',function($join){
		  $join->on('projection_countries.projection_id','=','projections.id');
	  })
	  ->leftJoin('projection_qties',function($join){
		  $join->on('projection_qties.projection_country_id','=','projection_countries.id');
		  $join->on('projection_qties.style_gmt_id','=','style_gmts.id');
	  })
	  ->where('projection_countries.id','=',request('projection_country_id',0))
	  ->get([
	  'style_gmts.id as style_gmt_id',
	  'item_accounts.item_description',
	  'projection_countries.id as projection_country_id',
	  'projection_qties.sam',
	  'projection_qties.qty',
	  'projection_qties.rate',
	  'projection_qties.amount'
	  ]);
        return Template::loadView('Sales.ProjectionQty', ['stylegmts'=>$stylegmts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectionQtyRequest $request) {
		foreach($request->sam as $index=>$sam){
				if($request->qty[$index]){
				$projectionqty = $this->projectionqty->updateOrCreate(
				['projection_country_id' => $request->projection_country_id,'style_gmt_id' => $request->style_gmt_id[$index]],
				['sam' => $sam,'qty' => $request->qty[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
				);
				}
			}
			 return response()->json(array('success' => true, 'id' => $projectionqty->id, 'message' => 'Save Successfully'), 200);
        /*$projectionqty = $this->projectionqty->create($request->except(['id']));
        if ($projectionqty) {
            return response()->json(array('success' => true, 'id' => $projectionqty->id, 'message' => 'Save Successfully'), 200);
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
        $projectionqty = $this->projectionqty->find($id);
        $row ['fromData'] = $projectionqty;
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
    public function update(ProjectionQtyRequest $request, $id) {
        $projectionqty = $this->projectionqty->update($id, $request->except(['id']));
        if ($projectionqty) {
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
        if ($this->projectionqty->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
