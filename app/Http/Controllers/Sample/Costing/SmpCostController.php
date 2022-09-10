<?php

namespace App\Http\Controllers\Sample\Costing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Repositories\Contracts\Util\WashChargeRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Repositories\Contracts\Marketing\StyleSampleRepository;
use App\Library\Template;
use App\Library\Sms;
use App\Http\Requests\Sample\Costing\SmpCostRequest;

class SmpCostController extends Controller {

  private $smpcost;
  private $mktcost;
  private $style;
  private $stylesample;
  private $currency;
  private $buyer;
  private $uom;
  private $team;
  private $itemaccount;
  private $productionprocess;
  private $itemclass;
  private $yarncount;
  private $colorrange;
  private $washcharge;
  private $buyerbranch;
  private $company;
  private $keycontrol;

  public function __construct(SmpCostRepository $smpcost,StyleSampleRepository $stylesample,StyleRepository $style,CurrencyRepository $currency, BuyerRepository $buyer,UomRepository $uom,TeamRepository $team,ItemAccountRepository $itemaccount,ProductionProcessRepository $productionprocess,ItemclassRepository $itemclass,YarncountRepository $yarncount,ColorrangeRepository $colorrange, WashChargeRepository $washcharge,BuyerBranchRepository $buyerbranch,CompanyRepository $company,KeycontrolRepository $keycontrol,MktCostRepository $mktcost) {
      $this->smpcost = $smpcost;
      $this->mktcost = $mktcost;
      $this->style = $style;
      $this->stylesample = $stylesample;
      $this->currency = $currency;
      $this->buyer = $buyer;
      $this->uom = $uom;
      $this->team = $team;
      $this->itemaccount = $itemaccount;
      $this->productionprocess = $productionprocess;
      $this->itemclass = $itemclass;
      $this->yarncount = $yarncount;
      $this->colorrange = $colorrange;
      $this->washcharge = $washcharge;
      $this->buyerbranch = $buyerbranch;
      $this->company = $company;
      $this->keycontrol = $keycontrol;
  $this->middleware('auth');
  /*$this->middleware('permission:view.smpcosts',   ['only' => ['create', 'index','show']]);
  $this->middleware('permission:create.smpcosts', ['only' => ['store']]);
  $this->middleware('permission:edit.smpcosts',   ['only' => ['update']]);
  $this->middleware('permission:delete.smpcosts', ['only' => ['destroy']]);*/
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index() {
    $costingunit=array_prepend(config('bprs.costingunit'),'-Select-','');
    $rows=$this->smpcost
    ->selectRaw(
    'smp_costs.id,
    style_samples.id as style_sample_id,
    style_samples.style_id,
    style_samples.style_gmt_id,
    style_samples.sort_id,
    style_samples.gmtssample_id,
    styles.style_description,
    styles.style_ref,
    styles.buyer_id,
    styles.uom_id,
    styles.team_id,
    buyers.name as buyer_id,
    item_accounts.item_description,
    gmtssamples.name as gmtssample,
    sum(style_sample_cs.qty) as qty,
    avg(style_sample_cs.rate) as rate,
    sum(style_sample_cs.amount) as amount'
    )
    ->leftJoin('style_samples',function($join){
      $join->on('style_samples.id','=','smp_costs.style_sample_id');
    })
    ->leftJoin('style_sample_cs', function($join) {
    $join->on('style_sample_cs.style_sample_id', '=', 'style_samples.id');
    })
    ->leftJoin('styles',function($join){
      $join->on('styles.id','=','style_samples.style_id');
    })
    ->leftJoin('style_gmts', function($join)  {
    $join->on('style_gmts.id', '=', 'style_samples.style_gmt_id');
    })
    ->leftJoin('item_accounts', function($join)  {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
    })
    ->leftJoin('gmtssamples', function($join)  {
    $join->on('gmtssamples.id', '=', 'style_samples.gmtssample_id');
    })
    ->leftJoin('buyers',function($join){
      $join->on('buyers.id','=','styles.buyer_id');
    })
    ->leftJoin('teams',function($join){
      $join->on('teams.id','=','styles.team_id');
    })
    ->leftJoin('currencies',function($join){
      $join->on('currencies.id','=','smp_costs.currency_id');
    })
    ->leftJoin('uoms',function($join){
      $join->on('uoms.id','=','styles.uom_id');
    })
    ->groupBy([
    'smp_costs.id',
    'style_samples.id',
    'style_samples.style_id',
    'style_samples.style_gmt_id',
    'style_samples.sort_id',
    'style_samples.gmtssample_id',
    'styles.style_description',
    'styles.style_ref',
    'styles.buyer_id',
    'styles.uom_id',
    'styles.team_id',
    'buyers.name',
    'item_accounts.item_description',
    'gmtssamples.name'
    ])
    ->orderBy('smp_costs.id','desc')
    ->get();
    echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create() {
      $costingunit=array_prepend(config('bprs.costingunit'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
      $team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
      $status=config('bprs.status');
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');

      $productionprocess=array_prepend(array_pluck($this->productionprocess->where([['production_area_id','=',30]])->get(),'process_name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
      $trimgroup=array_prepend(array_pluck($this->itemclass->getAccessories(),'name','id'),'-Select-','');
      $cmmethod=config('bprs.cmmethod');


      return Template::loadView('Sample.Costing.SmpCost', ['costingunit'=>$costingunit,'currency'=>$currency,'buyer'=>$buyer,'uom'=>$uom,'team'=>$team,'status'=> $status,'company'=>$company,'productionprocess'=>$productionprocess,'colorrange'=> $colorrange,'yarncount'=> $yarncount,'trimgroup'=> $trimgroup,'cmmethod'=>$cmmethod]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmpCostRequest $request) {
        $smpcost = $this->smpcost->create($request->except(['id','style_ref','buyer_id','uom_id','team_id','status_id']));
        if ($smpcost) {
            return response()->json(array('success' => true, 'id' => $smpcost->id, 'message' => 'Save Successfully'), 200);
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

    $smpcost = $this->smpcost
    ->selectRaw(
    'smp_costs.id,
    smp_costs.company_id,
    smp_costs.costing_date,
    smp_costs.currency_id,
    smp_costs.costing_unit_id,
    smp_costs.exchange_rate,
    smp_costs.remarks,
    style_samples.id as style_sample_id,
    styles.style_ref,
    styles.buyer_id,
    styles.uom_id,
    styles.team_id,
    buyers.name as buyer_id,
    sum(style_sample_cs.qty) as qty'
    )
    ->join('style_samples',function($join){
      $join->on('style_samples.id','=','smp_costs.style_sample_id');
    })
    ->leftJoin('style_sample_cs', function($join) {
    $join->on('style_sample_cs.style_sample_id', '=', 'style_samples.id');
    })
    ->join('styles',function($join){
      $join->on('styles.id','=','style_samples.style_id');
    })
    ->leftJoin('buyers',function($join){
      $join->on('buyers.id','=','styles.buyer_id');
    })
    ->where([['smp_costs.id','=',$id]])
    ->groupBy([
    'smp_costs.id',
    'smp_costs.company_id',
    'smp_costs.costing_date',
    'smp_costs.currency_id',
    'smp_costs.costing_unit_id',
    'smp_costs.exchange_rate',
    'smp_costs.remarks',
    'style_samples.id',
    'styles.style_ref',
    'styles.buyer_id',
    'styles.uom_id',
    'styles.team_id',
    'buyers.name'
    ])
    ->get()
    ->first();
    
    $row ['fromData'] = $smpcost;
    $row ['dropDown'] = '';
    $row ['fabric'] = '';
    echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SmpCostRequest $request, $id) {
        $smpcost = $this->smpcost->update($id, $request->except(['id','style_ref','buyer_id','uom_id','team_id']));
        if ($smpcost) {
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
        if ($this->smpcost->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

  public function getStyleSample(){
    $rows = $this->stylesample->selectRaw(
    'style_samples.id,
    style_samples.style_id,
    style_samples.style_gmt_id,
    style_samples.sort_id,
    style_samples.gmtssample_id,
    styles.style_description,
    styles.style_ref,
    styles.buyer_id,
    styles.uom_id,
    styles.team_id,
    buyers.name as buyer_id,
    item_accounts.item_description,
    gmtssamples.name as gmtssample,
    sum(style_sample_cs.qty) as qty,
    avg(style_sample_cs.rate) as rate,
    sum(style_sample_cs.amount) as amount'
    )
    ->leftJoin('style_sample_cs', function($join) {
    $join->on('style_sample_cs.style_sample_id', '=', 'style_samples.id');
    })
    ->leftJoin('styles', function($join)  {
    $join->on('styles.id', '=', 'style_samples.style_id');
    })
    ->leftJoin('buyers',function($join){
      $join->on('buyers.id','=','styles.buyer_id');
    })
    ->leftJoin('style_gmts', function($join)  {
    $join->on('style_gmts.id', '=', 'style_samples.style_gmt_id');
    })
    ->leftJoin('item_accounts', function($join)  {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
    })
    ->leftJoin('gmtssamples', function($join)  {
    $join->on('gmtssamples.id', '=', 'style_samples.gmtssample_id');
    })
    ->when(request('buyer_id'), function ($q) {
      return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
      })
      ->when(request('style_ref'), function ($q) {
      return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
      })
      ->when(request('style_description'), function ($q) {
      return $q->where('styles.style_description', 'like', '%'.request('style_description', 0).'%');
    })
    ->where([['style_samples.is_costing_allowed','=',1]])
    ->groupBy([
    'style_samples.id',
    'style_samples.style_id',
    'style_samples.style_gmt_id',
    'style_samples.sort_id',
    'style_samples.gmtssample_id',
    'styles.style_description',
    'styles.style_ref',
    'styles.buyer_id',
    'styles.uom_id',
    'styles.team_id',
    'buyers.name',
    'item_accounts.item_description',
    'gmtssamples.name'
    ])
    ->where([['gmtssamples.type_id','=',1]])
    ->get();
    echo json_encode($rows);
  }

  public function getTotal()
  {
    $id=request('smp_cost_id',0);
    $total_cost=$this->smpcost->totalFabricCost($id)+$this->smpcost->totalYarnCost($id)+$this->smpcost->totalFabricProdCost($id)+$this->smpcost->totalTrimCost($id)+$this->smpcost->totalEmbCost($id)+$this->smpcost->totalCmCost($id);

    $smpcost = $this->smpcost
    ->selectRaw(
    'smp_costs.id,
    sum(style_sample_cs.qty) as qty'
    )
    ->join('style_samples',function($join){
      $join->on('style_samples.id','=','smp_costs.style_sample_id');
    })
    ->leftJoin('style_sample_cs', function($join) {
    $join->on('style_sample_cs.style_sample_id', '=', 'style_samples.id');
    })
    ->join('styles',function($join){
      $join->on('styles.id','=','style_samples.style_id');
    })
    ->where([['smp_costs.id','=',$id]])
    ->groupBy([
    'smp_costs.id',
    ])
    ->get()
    ->first();

    if($smpcost->qty)
    {
      
    $total_cost_pcs=number_format($total_cost/$smpcost->qty,4);
    }
    echo json_encode(['total_cost'=>$total_cost,'total_cost_pcs'=>$total_cost_pcs]);
  }
}
