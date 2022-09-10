<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopMktCostRequest;

class SoAopMktCostController extends Controller {

    private $soaopmktcost;
    private $soaop;
    private $company;
    private $buyer;
    private $uom;
    private $currency;
    private $colorrange;
    private $color;
    private $subinbservice;
    private $productionprocess;
    private $embelishmenttype;

    public function __construct(
        SoAopMktCostRepository $soaopmktcost,
        SoAopRepository $soaop,
        SubInbServiceRepository $subinbservice, 
        CompanyRepository $company, 
        BuyerRepository $buyer,
        UomRepository $uom, 
        CurrencyRepository $currency,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        ProductionProcessRepository $productionprocess,
        EmbelishmentTypeRepository $embelishmenttype
      ) {
        $this->soaopmktcost = $soaopmktcost;
        $this->soaop = $soaop;
        $this->subinbservice = $subinbservice;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->currency = $currency;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->productionprocess = $productionprocess;
        $this->embelishmenttype = $embelishmenttype;

        $this->middleware('auth');
        //$this->middleware('permission:view.soaopmktcosts',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.soaopmktcosts', ['only' => ['store']]);
        //$this->middleware('permission:edit.soaopmktcosts',   ['only' => ['update']]);
        //$this->middleware('permission:delete.soaopmktcosts', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

      $rows=$this->soaopmktcost
      ->join('sub_inb_services', function($join)  {
        $join->on('sub_inb_services.id', '=', 'so_aop_mkt_costs.sub_inb_service_id');
      })
      ->join('sub_inb_marketings', function($join)  {
        $join->on('sub_inb_marketings.id', '=', 'sub_inb_services.sub_inb_marketing_id');
      })
      ->join('buyers', function($join)  {
        $join->on('sub_inb_marketings.buyer_id', '=', 'buyers.id');
      })
      ->leftJoin('buyer_branches', function($join)  {
        $join->on('sub_inb_marketings.buyer_branch_id', '=', 'buyer_branches.id');
      })
      ->join('companies', function($join)  {
        $join->on('sub_inb_marketings.company_id', '=', 'companies.id');
      })
      ->leftJoin('currencies', function($join)  {
        $join->on('sub_inb_marketings.currency_id', '=', 'currencies.id');
      })
      ->orderBy('so_aop_mkt_costs.id','desc')
      ->get([
        'so_aop_mkt_costs.*',
        'sub_inb_services.sub_inb_marketing_id',
        'sub_inb_services.amount',
        'buyers.name as buyer_name',
        'companies.name as company_name',
        'currencies.code as currency_code'
      ])
      ->map(function($rows){
        $rows->costing_date=date('d-M-Y',strtotime($rows->costing_date));
        return $rows;
      });

      echo json_encode($rows);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
        $productionprocess=array_prepend(array_pluck($this->productionprocess->whereIn('production_area_id',[30])->get(),'process_name','id'),'-Select-','');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-','');

        return Template::LoadView('Subcontract.AOP.SoAopMktCost',[
          'company'=>$company,
          'buyer'=>$buyer,
          'uom'=>$uom,
          'colorrange'=>$colorrange,
          'embelishmenttype'=>$embelishmenttype,
          'currency'=>$currency,
          'yesno'=>$yesno,
          'productionprocess'=>$productionprocess
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopMktCostRequest $request) {

        $soaopmktcost=$this->soaopmktcost->create([
          'sub_inb_service_id'=>$request->sub_inb_service_id,
          'costing_date'=>$request->costing_date,
          'exch_rate'=>$request->exch_rate,
          'remarks'=>$request->remarks,
        ]);
        
        if($soaopmktcost){
          return response()->json(array('success' => true,'id' =>  $soaopmktcost->id,'message' => 'Save Successfully'),200);
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
      $soaopmktcost = $this->soaopmktcost
      ->join('sub_inb_services', function($join)  {
        $join->on('sub_inb_services.id', '=', 'so_aop_mkt_costs.sub_inb_service_id');
      })
      ->join('sub_inb_marketings', function($join)  {
        $join->on('sub_inb_marketings.id', '=', 'sub_inb_services.sub_inb_marketing_id');
      })
      ->where([['so_aop_mkt_costs.id','=',$id]])
      ->get([
        'so_aop_mkt_costs.*',
        'sub_inb_services.amount',
        'sub_inb_marketings.buyer_id',
        'sub_inb_marketings.company_id',
        'sub_inb_marketings.currency_id',
      ])
      ->first();
      $row ['fromData'] = $soaopmktcost;
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
    public function update(SoAopMktCostRequest $request, $id) {
        $quotationApproved=$this->soaopmktcost
        ->join('so_aop_mkt_cost_qprices', function($join)  {
            $join->on('so_aop_mkt_cost_qprices.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->where([['so_aop_mkt_costs.id','=',$id]])
        ->get(['so_aop_mkt_cost_qprices.final_approved_by'])
        ->first();

        if ($quotationApproved) {
            return response()->json(array('success' => false,'message' => 'Update not possible. Quotation Approved'),200);
        }
        
        $soaopmktcost=$this->soaopmktcost->update($id,[
          'sub_inb_service_id'=>$request->sub_inb_service_id,
          'costing_date'=>$request->costing_date,
          'exch_rate'=>$request->exch_rate,
          'remarks'=>$request->remarks,
        ]);
        if($soaopmktcost){
          return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->soaopmktcost->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getSubInbService()
    {
        $rows=$this->subinbservice
        ->join('sub_inb_marketings', function($join)  {
          $join->on('sub_inb_marketings.id', '=', 'sub_inb_services.sub_inb_marketing_id');
        })
        ->join('buyers', function($join)  {
          $join->on('sub_inb_marketings.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('buyer_branches', function($join)  {
          $join->on('sub_inb_marketings.buyer_branch_id', '=', 'buyer_branches.id');
        })
        ->join('companies', function($join)  {
          $join->on('sub_inb_marketings.company_id', '=', 'companies.id');
        })
        ->leftJoin('currencies', function($join)  {
          $join->on('sub_inb_marketings.currency_id', '=', 'currencies.id');
        })
        ->leftJoin('uoms', function($join)  {
          $join->on('sub_inb_services.uom_id', '=', 'uoms.id');
        })
        ->leftJoin('teams', function($join)  {
          $join->on('sub_inb_marketings.team_id', '=', 'teams.id');
        })
        ->leftJoin('teammembers', function($join)  {
          $join->on('teammembers.id', '=', 'sub_inb_marketings.teammember_id');
        })
        ->leftJoin('users', function($join)  {
          $join->on('users.id', '=', 'teammembers.user_id');
        })
        ->when(request('buyer_id'), function ($q) {
          return $q->where('sub_inb_marketings.buyer_id', '=', request('buyer_id', 0));
        })
        ->when(request('company_id'), function ($q) {
          return $q->where('sub_inb_marketings.company_id', '=', request('company_id', 0));
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('sub_inb_services.est_delv_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('sub_inb_services.est_delv_date', '<=',request('date_to', 0));
        })
        ->where([['sub_inb_marketings.production_area_id','=',25]])
        ->whereNotNull('sub_inb_marketings.currency_id')
        ->orderBy('sub_inb_services.id','desc')
        ->get([
          'sub_inb_services.*',
          'sub_inb_marketings.buyer_id',
          'sub_inb_marketings.company_id',
          'sub_inb_marketings.currency_id',
          'buyers.name as buyer_name',
          'companies.name as company_name',
          'currencies.code as currency_code',
          'uoms.code as uom_code',
          'teams.name as team_name',
          'users.name as team_member_name',
          'buyer_branches.contact_person',
          'buyer_branches.email',
          'buyer_branches.designation',
          'buyer_branches.address',
        ])
        ->map(function($rows){
          $rows->contact_no=$rows->email." ".$rows->address;
          $rows->est_delv_date=$rows->est_delv_date?date('d-M-Y',strtotime($rows->est_delv_date)):'--';
          return $rows;
        });

        echo json_encode($rows);
    }

}
//est_delv_date