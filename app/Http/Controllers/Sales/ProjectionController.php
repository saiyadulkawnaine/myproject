<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\ProjectionRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SeasonRepository;
use App\Library\Template;
use App\Http\Requests\ProjectionRequest;

class ProjectionController extends Controller {

    private $projection;
    private $company;
    private $buyer;
    private $stylegmts;
    private $currency;
    private $country;
    private $uom;
    private $season;

    public function __construct(ProjectionRepository $projection, CompanyRepository $company,BuyerRepository $buyer,StyleGmtsRepository $stylegmts,CurrencyRepository $currency,CountryRepository $country,UomRepository $uom,SeasonRepository $season) {
        $this->projection     = $projection;
        $this->company        = $company;
        $this->buyer          = $buyer;
        $this->stylegmts      = $stylegmts;
        $this->currency       = $currency;
        $this->country        = $country;
        $this->uom            = $uom;
        $this->season         = $season;
        $this->middleware('auth');
        $this->middleware('permission:view.projections',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.projections', ['only' => ['store']]);
        $this->middleware('permission:edit.projections',   ['only' => ['update']]);
        $this->middleware('permission:delete.projections', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $projections=array();
	    $rows=$this->projection
		->join('styles',function($join) {
			$join->on('styles.id','=','projections.style_id');
		})
		->join('buyers',function($join) {
			$join->on('buyers.id','=','styles.buyer_id');
		})
		->join('companies',function($join) {
			$join->on('companies.id','=','projections.company_id');
        })
        ->orderBy('projections.id','desc')
		->get([
		'projections.*',
		'styles.style_ref',
		'styles.buyer_id',
		'buyers.name',
		'companies.code as company_name'
		]);
  		foreach($rows as $row){
        $projection['id']=	$row->id;
		$projection['company']=	$row->company_name;
		$projection['proj_no']=	$row->proj_no;
		$projection['style_ref']=	$row->style_ref;
        $projection['buyer']=	$row->name;
        $projection['date']=date('d-m-Y',strtotime($row->date));
  		   array_push($projections,$projection);
  		}
        echo json_encode($projections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $stylegmts=array_prepend(array_pluck($this->stylegmts->get(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
      $season=array_prepend(array_pluck($this->season->get(),'name','id'),'-Select-','');
	  $cutoff=array_prepend(config('bprs.cutoff'),'-Select-','');

      return Template::loadView('Sales.Projection', ['company'=>$company,'buyer'=>$buyer,'stylegmts'=>$stylegmts,'currency'=>$currency,'country'=>$country,'uom'=>$uom,'season'=>$season,'cutoff'=>$cutoff]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectionRequest $request) {
        $projection = $this->projection->create($request->except(['id','style_ref']));
        if ($projection) {
            return response()->json(array('success' => true, 'id' => $projection->id, 'message' => 'Save Successfully'), 200);
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
        //$projection = $this->projection->find($id);
		 $projection=$this->projection
		->join('styles',function($join) {
			$join->on('styles.id','=','projections.style_id');
		})
		->join('buyers',function($join) {
			$join->on('buyers.id','=','styles.buyer_id');
		})
		->join('companies',function($join) {
			$join->on('companies.id','=','projections.company_id');
		})
		->where([['projections.id','=',$id]])
		->get([
		'projections.*',
		'styles.style_ref',
		'styles.buyer_id',
		'styles.season_id',
		'styles.uom_id',
		'buyers.name',
		'companies.name as company_name'
		])->first();
        $row ['fromData'] = $projection;
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
    public function update(ProjectionRequest $request, $id) {
        $projection = $this->projection->update($id, $request->except(['id','style_ref']));
        if ($projection) {
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
        if ($this->projection->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
