<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpriceRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingMktCostRequest;

class SoDyeingMktCostController extends Controller {

    private $sodyeingmktcost;
    private $sodyeingmktcostqprice;
    private $sodyeing;
    private $company;
    private $buyer;
    private $uom;
    private $currency;
    private $accchartctrlhead;
    private $autoyarn;
    private $gmtspart;
    private $colorrange;
    private $color;
    private $subinbservice;
    private $productionprocess;

    public function __construct(
        SoDyeingMktCostRepository $sodyeingmktcost,
        SoDyeingMktCostQpriceRepository $sodyeingmktcostqprice,
        SoDyeingRepository $sodyeing,
        SubInbServiceRepository $subinbservice, 
        CompanyRepository $company, 
        BuyerRepository $buyer,
        UomRepository $uom, 
        CurrencyRepository $currency,
        AccChartCtrlHeadRepository $accchartctrlhead,

        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        ProductionProcessRepository $productionprocess
        ) {
        $this->sodyeingmktcost = $sodyeingmktcost;
        $this->sodyeingmktcostqprice = $sodyeingmktcostqprice;
        $this->sodyeing = $sodyeing;
        $this->subinbservice = $subinbservice;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->currency = $currency;
        $this->accchartctrlhead = $accchartctrlhead;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->productionprocess = $productionprocess;
        $this->middleware('auth');
        //$this->middleware('permission:view.sodyeingmktcosts',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingmktcosts', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingmktcosts',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingmktcosts', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

      $rows=$this->sodyeingmktcost
      ->join('sub_inb_services', function($join)  {
        $join->on('sub_inb_services.id', '=', 'so_dyeing_mkt_costs.sub_inb_service_id');
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
      ->orderBy('so_dyeing_mkt_costs.id','desc')
      ->get([
        'so_dyeing_mkt_costs.*',
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
        $autoyarn=array_prepend(array_pluck($this->autoyarn->get(),'name','id'),'-Select-','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $productionprocess=array_prepend(array_pluck($this->productionprocess->whereIn('production_area_id',[30])->get(),'process_name','id'),'-Select-','');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-','');

        return Template::LoadView('Subcontract.Dyeing.SoDyeingMktCost',[
          'company'=>$company,
          'buyer'=>$buyer,
          'uom'=>$uom,
          'colorrange'=>$colorrange,
          'autoyarn'=>$autoyarn,
          'dyetype'=>$dyetype,
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
    public function store(SoDyeingMktCostRequest $request) {

        $sodyeingmktcost=$this->sodyeingmktcost->create([
          'sub_inb_service_id'=>$request->sub_inb_service_id,
          'costing_date'=>$request->costing_date,
          'exch_rate'=>$request->exch_rate,
          'remarks'=>$request->remarks,
        ]);
        
        if($sodyeingmktcost){
          return response()->json(array('success' => true,'id' =>  $sodyeingmktcost->id,'message' => 'Save Successfully'),200);
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
      $sodyeingmktcost = $this->sodyeingmktcost
      ->join('sub_inb_services', function($join)  {
        $join->on('sub_inb_services.id', '=', 'so_dyeing_mkt_costs.sub_inb_service_id');
      })
      ->join('sub_inb_marketings', function($join)  {
        $join->on('sub_inb_marketings.id', '=', 'sub_inb_services.sub_inb_marketing_id');
      })
      ->where([['so_dyeing_mkt_costs.id','=',$id]])
      ->get([
        'so_dyeing_mkt_costs.*',
        'sub_inb_services.amount',
        'sub_inb_marketings.buyer_id',
        'sub_inb_marketings.company_id',
        'sub_inb_marketings.currency_id',
      ])
      ->first();
      $row ['fromData'] = $sodyeingmktcost;
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
    public function update(SoDyeingMktCostRequest $request, $id) {
        $sodyeingmktcostqprice=$this->sodyeingmktcostqprice
        ->where([['so_dyeing_mkt_cost_id','=',$id]])
        ->get()->first();
        if ($sodyeingmktcostqprice->final_approved_at) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }
        $sodyeingmktcost=$this->sodyeingmktcost->update($id,[
          'sub_inb_service_id'=>$request->sub_inb_service_id,
          'costing_date'=>$request->costing_date,
          'exch_rate'=>$request->exch_rate,
          'remarks'=>$request->remarks,
        ]);
        if($sodyeingmktcost){
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
        $sodyeingmktcostqprice=$this->sodyeingmktcostqprice
        ->where([['so_dyeing_mkt_cost_id','=',$id]])
        ->get()->first();
        if ($sodyeingmktcostqprice->final_approved_at) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }
        if($this->sodyeingmktcost->delete($id)){
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
        ->where([['sub_inb_marketings.production_area_id','=',20]])
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