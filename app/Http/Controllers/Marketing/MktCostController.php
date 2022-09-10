<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
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
use App\Repositories\Contracts\Marketing\BuyerDevelopmentOrderQtyRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Library\Template;
use App\Library\Sms;
use App\Http\Requests\MktCostRequest;

class MktCostController extends Controller {

	private $mktcost;
	private $style;
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
  private $buyerdevelopmentorderqty;
  private $user;
  private $designation;

	public function __construct(
    MktCostRepository $mktcost,
    StyleRepository $style,
    CurrencyRepository $currency, 
    BuyerRepository $buyer,
    UomRepository $uom,
    TeamRepository $team,
    ItemAccountRepository $itemaccount,
    ProductionProcessRepository $productionprocess,
    ItemclassRepository $itemclass,
    YarncountRepository $yarncount,
    ColorrangeRepository $colorrange, 
    WashChargeRepository $washcharge,
    BuyerBranchRepository $buyerbranch,
    CompanyRepository $company,
    KeycontrolRepository $keycontrol,
    BuyerDevelopmentOrderQtyRepository $buyerdevelopmentorderqty,
    UserRepository $user,
    DesignationRepository $designation
    
  ) {
      $this->mktcost = $mktcost;
      $this->style = $style;
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
      $this->buyerdevelopmentorderqty = $buyerdevelopmentorderqty;
      $this->designation = $designation;
      $this->user = $user;
      $this->middleware('auth');
      $this->middleware('permission:view.mktcosts',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.mktcosts', ['only' => ['store']]);
      $this->middleware('permission:edit.mktcosts',   ['only' => ['update']]);
      $this->middleware('permission:delete.mktcosts', ['only' => ['destroy']]);
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $incoterm=array_prepend([1=>"FOB",2=>"CFR",3=>"CIF"],'-Select-','');
      $costingunit=array_prepend(config('bprs.costingunit'),'-Select-','');
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $mktcosts=array();
      $rows=$this->mktcost->join('styles',function($join){
      $join->on('styles.id','=','mkt_costs.style_id');
      })
      ->join('buyers',function($join){
      $join->on('buyers.id','=','styles.buyer_id');
      })
      ->join('teams',function($join){
      $join->on('teams.id','=','styles.team_id');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','mkt_costs.currency_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','styles.uom_id');
      })
      ->orderBy('mkt_costs.id','desc')
      ->take(500)
      ->get([
      'mkt_costs.*',
      'styles.style_ref',
      'styles.style_description',
      'buyers.code as buyer_name',
      'teams.name as team_name',
      'currencies.code as currency_code',
      'uoms.code as uom_code'
      ]);
      foreach($rows as $row){
      $mktcost['id']=	$row->id;
      $mktcost['costingunit']=	$costingunit[$row->costing_unit_id];
      $mktcost['quotdate']=	$row->quot_date;
      $mktcost['incoterm']=	$incoterm[$row->incoterm_id];
      $mktcost['incotermplace']=	$row->incoterm_place;
      $mktcost['offerqty']=	$row->offer_qty;
      $mktcost['estshipdate']=	$row->est_ship_date;
      $mktcost['opdate']=	$row->op_date;
      $mktcost['style']=	$row->style_ref;
      $mktcost['style_description']=	$row->style_description;
      $mktcost['currency']=	$row->currency_code;
      $mktcost['buyer']=	$row->buyer_name;
      $mktcost['uom']=	$row->uom_code;
      $mktcost['team']=	$row->team_name;
      $mktcost['company']=	$company[$row->company_id];
      $mktcost['approval_status']=$row->first_approved_by?"Approved":"--";
      array_push($mktcosts,$mktcost);
      }
      echo json_encode($mktcosts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $incoterm=array_prepend([1=>"FOB",2=>"CFR",3=>"CIF"],'-Select-','');
      $commissionfor=array_prepend([1=>"Local Agent",2=>"Foreign Agent"],'-Select-','');
      $cmmethod=config('bprs.cmmethod');
      $costingunit=array_prepend(config('bprs.costingunit'),'-Select-','');
      $othercosthead=array_prepend(config('bprs.othercosthead'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
      $team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
      $trimgroup=array_prepend(array_pluck($this->itemclass->getAccessories(),'name','id'),'-Select-','');
      $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
      $status=config('bprs.status');
      $yesno=config('bprs.yesno');
      $company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');

      

      return Template::loadView('Marketing.MktCost', [
        'costingunit'=>$costingunit,
        'currency'=>$currency,
        'buyer'=>$buyer,
        'uom'=>$uom,
        'team'=>$team,
        'incoterm'=>$incoterm,
        'trimgroup'=>$trimgroup,
        'productionprocess'=>$productionprocess,
        'commissionfor'=>$commissionfor,
        'cmmethod'=>$cmmethod,
        'othercosthead'=> $othercosthead,
        'colorrange'=> $colorrange,
        'yarncount'=> $yarncount,
        'status'=> $status,
        'yesno'=> $yesno,
        'company'=>$company,
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostRequest $request) {
        $mktcost = $this->mktcost->create($request->except(['id','style_ref','buyer_id','uom_id','team_id','status_id']));
        if ($mktcost) {
            return response()->json(array('success' => true, 'id' => $mktcost->id, 'message' => 'Save Successfully'), 200);
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
      $mktcost = $this->mktcost
      ->join('styles',function($join){
      $join->on('styles.id','=','mkt_costs.style_id');
      })
      ->where([['mkt_costs.id','=',$id]])
      ->get([
      'mkt_costs.*',
      'styles.style_ref',
      'styles.buyer_id',
      'styles.uom_id',
      'styles.team_id'
      ])->first();

      /*$materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
      $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
      $fabricDescription=$this->mktcost
      ->join('styles',function($join){
      $join->on('styles.id','=','mkt_costs.style_id');
      })
      ->join('style_fabrications',function($join){
      $join->on('style_fabrications.style_id','=','mkt_costs.style_id');
      })
      ->join('autoyarns',function($join){
      $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
      })
      ->join('autoyarnratios',function($join){
      $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
      })
      ->join('compositions',function($join){
      $join->on('compositions.id','=','autoyarnratios.composition_id');
      })
      ->where([['mkt_costs.id','=',$mktcost->id]])
      ->get([
      'style_fabrications.id',
      'autoyarnratios.composition_id',
      'compositions.name',
      'autoyarnratios.ratio',
      ]);
      $fabricDescriptionArr=array();
      foreach($fabricDescription as $row){
      $fabricDescriptionArr[$row->id][]=$row->name." ".$row->ratio."%";
      }


      $fabric=$this->mktcost
      ->join('styles',function($join){
      $join->on('styles.id','=','mkt_costs.style_id');
      })
      ->join('style_fabrications',function($join){
      $join->on('style_fabrications.style_id','=','mkt_costs.style_id');
      })
      ->join('style_gmts',function($join){
      $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
      })
      ->join('item_accounts', function($join) {
      $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('gmtsparts',function($join){
      $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
      })
      ->join('autoyarns',function($join){
      $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
      })

      ->join('uoms',function($join){
      $join->on('uoms.id','=','style_fabrications.uom_id');
      })
      ->leftJoin('mkt_cost_fabrics',function($join){
      $join->on('mkt_cost_fabrics.mkt_cost_id','=','mkt_costs.id');
      })
      ->where([['mkt_costs.id','=',$mktcost->id]])
      ->get([
      'mkt_costs.id as mkt_cost_id',
      'style_fabrications.id as style_fabrication_id',
      'style_fabrications.material_source_id',
      'style_fabrications.fabric_nature_id',
      'style_fabrications.fabric_look_id',
      'style_fabrications.fabric_shape_id',
      'gmtsparts.name as gmtspart_name',
      'item_accounts.item_description',
      'uoms.code as uom_name',
      'mkt_cost_fabrics.gsm_weight',
      'mkt_cost_fabrics.id',
      ]);*/

      $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
      $emb=$this->mktcost
      ->join('styles',function($join){
      $join->on('styles.id','=','mkt_costs.style_id');
      })
      ->join('style_embelishments',function($join){
      $join->on('style_embelishments.style_id','=','mkt_costs.style_id');
      })
      ->join('style_gmts',function($join){
      $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
      })
      ->join('item_accounts', function($join) {
      $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('embelishments',function($join){
      $join->on('embelishments.id','=','style_embelishments.embelishment_id');
      })
      ->join('embelishment_types',function($join){
      $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
      })

      ->leftJoin('mkt_cost_embs',function($join){
      $join->on('mkt_cost_embs.mkt_cost_id','=','mkt_costs.id');
      $join->on('mkt_cost_embs.style_embelishment_id','=','style_embelishments.id');
      })
      ->where([['mkt_costs.id','=',$mktcost->id]])
      ->get([
      'mkt_costs.id as mkt_cost_id',
      'mkt_costs.costing_unit_id',
      'style_embelishments.id as style_embelishment_id',
      'style_embelishments.embelishment_id',
      'style_embelishments.embelishment_type_id',
      'style_embelishments.embelishment_size_id',
      'embelishments.name as embelishment_name',
      'embelishment_types.name as embelishment_type',
      'item_accounts.item_description',
      'mkt_cost_embs.id',
      'mkt_cost_embs.cons',
      'mkt_cost_embs.rate',
      'mkt_cost_embs.amount',
      ])
      ->map(function ($emb) use ($embelishmentsize) {
      $emb->embelishment_size_name=$embelishmentsize[$emb->embelishment_size_id];
      return $emb ;
      });

      /*$stylefabrications=array();
      foreach($fabric as $row){
      $stylefabrication['id']=	$row->id;
      $stylefabrication['mkt_cost_id']=	$row->mkt_cost_id;
      $stylefabrication['style_fabrication_id']=	$row->style_fabrication_id;
      $stylefabrication['style_gmt']=	$row->item_description;
      $stylefabrication['gmtspart']=	$row->gmtspart_name;
      $stylefabrication['fabric_description']=	implode(" ",$fabricDescriptionArr[$row->style_fabrication_id]);
      $stylefabrication['uom_name']=	$row->uom_name;
      $stylefabrication['materialsourcing']=	$materialsourcing[$row->material_source_id];
      $stylefabrication['fabricnature']=	$fabricnature[$row->fabric_nature_id];
      $stylefabrication['fabriclooks']=	$fabriclooks[$row->fabric_look_id];
      $stylefabrication['fabricshape']=	$fabricshape[$row->fabric_shape_id];
      $stylefabrication['gsm_weight']=	$row->gsm_weight;
      array_push($stylefabrications,$stylefabrication);
      }*/
      $row ['fromData'] = $mktcost;
      $dropdown['emb'] = "'".Template::loadView('Marketing.MktCostEmbMatrix',['embs'=>$emb,'costing_unit_id'=>$mktcost->costing_unit_id])."'";
      $row ['dropDown'] = $dropdown;
      //$row ['fabric'] = $stylefabrications;
      $row ['totalcost'] = $this->mktcost->totalCost($mktcost->id);
      $row ['price_before_commission'] =$this->mktcost->totalPriceBeforeCommission($mktcost->id);
      $row ['price_after_commission'] =$this->mktcost->totalPriceAfterCommission($mktcost->id);
      echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MktCostRequest $request, $id) {
        $approved=$this->mktcost->find($id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcost = $this->mktcost->update($id, $request->except(['id','style_ref','buyer_id','uom_id','team_id']));
        if ($mktcost) {
			/*$text="FAMKAM ERP\n";
			$text.="Price Submited\n";
			$text.="Style:2048305\n";
			$text.="Buyer:Lian Fung\n";
			$text.="Item:Long Sleeve T-Shirt\n";
			$text.="Cost/Pcs:1.9404\n";
			$text.="Price/Pcs:1.98\n";
			$text.="Team-A";
			$sms=Sms::send_sms($text, '8801913955201');*/
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
        $approved=$this->mktcost->find($id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        if ($this->mktcost->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getMktCostList(){
        $incoterm=array_prepend([1=>"FOB",2=>"CFR",3=>"CIF"],'-Select-','');
        $costingunit=array_prepend(config('bprs.costingunit'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $mktcosts=array();
        $rows=$this->mktcost->join('styles',function($join){
          $join->on('styles.id','=','mkt_costs.style_id');
        })
        ->join('buyers',function($join){
          $join->on('buyers.id','=','styles.buyer_id');
        })
        ->join('teams',function($join){
          $join->on('teams.id','=','styles.team_id');
        })
        ->join('currencies',function($join){
          $join->on('currencies.id','=','mkt_costs.currency_id');
        })
        ->join('uoms',function($join){
          $join->on('uoms.id','=','styles.uom_id');
        })
        ->when(request('from_date'), function ($q) {
            return $q->where('mkt_costs.quot_date', '>=', request('from_date', 0));
        })
        ->when(request('to_date'), function ($q) {
            return $q->where('mkt_costs.quot_date', '<=', request('to_date', 0));
        })
        ->orderBy('mkt_costs.id','desc')
        ->get([
          'mkt_costs.*',
          'styles.style_ref',
          'styles.style_description',
          'buyers.code as buyer_name',
          'teams.name as team_name',
          'currencies.code as currency_code',
          'uoms.code as uom_code'
        ]);
        foreach($rows as $row){
          $mktcost['id']=   $row->id;
          $mktcost['costingunit']=  $costingunit[$row->costing_unit_id];
          $mktcost['quotdate']= $row->quot_date;
          $mktcost['incoterm']= $incoterm[$row->incoterm_id];
          $mktcost['incotermplace']=    $row->incoterm_place;
          $mktcost['offerqty']= $row->offer_qty;
          $mktcost['estshipdate']=  $row->est_ship_date;
          $mktcost['opdate']=   $row->op_date;
          $mktcost['style']=    $row->style_ref;
          $mktcost['style_description']=    $row->style_description;
          $mktcost['currency']= $row->currency_code;
          $mktcost['buyer']=    $row->buyer_name;
          $mktcost['uom']=  $row->uom_code;
          $mktcost['team']= $row->team_name;
          $mktcost['company']=  $company[$row->company_id];
          $mktcost['approval_status']=$row->first_approved_by?"Approved":"--";
          array_push($mktcosts,$mktcost);
        }
        echo json_encode($mktcosts);
    }

    public function getBuyerDevelopmentOrderQty(){
        $buyer_id=request('buyer_id', 0);
        $rows=$this->buyerdevelopmentorderqty
          ->join("buyer_development_orders",function($join){
            $join->on("buyer_development_orders.id","=","buyer_development_order_qties.buyer_development_order_id");
          })
          ->join("buyer_development_intms",function($join){
            $join->on("buyer_development_intms.id","=","buyer_development_orders.buyer_development_intm_id");
          })
          ->join("buyers as brands",function($join){
            $join->on("brands.id","=","buyer_development_intms.buyer_id");
          })
          ->join("buyer_developments",function($join){
            $join->on("buyer_developments.id","=","buyer_development_intms.buyer_development_id");
          })
          ->join("teams",function($join){
            $join->on("teams.id","=","buyer_developments.team_id");
          })
          ->join('buyers',function($join){
            $join->on('buyers.id','=','buyer_developments.buyer_id');
          })
          ->when(request('style_description'), function ($q) {
            return $q->where('buyer_development_orders.style_description', 'like', '%'.request('style_description', 0).'%');
          })
          ->when(request('date_from'), function ($q) {
            return $q->where('buyer_development_order_qties.est_ship_date', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
            return $q->where('buyer_development_order_qties.est_ship_date', '<=',request('date_to', 0));
          })
          ->where([['buyers.id','=',$buyer_id]])
          ->orderBy("buyer_development_order_qties.id","DESC")
          ->get([
            "buyer_development_order_qties.*",
            "buyer_development_orders.style_description",
            "brands.name as brand_name",
            "teams.name as team_name",
            "buyers.name as buyer_name"
          ]);

        echo json_encode($rows);
    }


    private function getData ($id) {
        //$id=$id;
        $incoterm=array_prepend([1=>"FOB",2=>"CFR",3=>"CIF"],'-Select-','');
        $costingunit=array_prepend(config('bprs.costingunit'),'-Select-','');
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),' ','');

        $mktcosts=array();
        $rows=$this->mktcost->join('styles',function($join){
            $join->on('styles.id','=','mkt_costs.style_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
        ->join('teams',function($join){
            $join->on('teams.id','=','styles.team_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','mkt_costs.currency_id');
        })
        ->join('uoms',function($join){
            $join->on('uoms.id','=','styles.uom_id');
        })
        ->join('seasons',function($join){
            $join->on('seasons.id','=','styles.season_id');
        })
        ->leftJoin('users as first_approval',function($join){
          $join->on('first_approval.id','=','mkt_costs.first_approved_by');
        })
        ->leftJoin('employee_h_rs as first_approval_emp',function($join){
            $join->on('first_approval.id','=','first_approval_emp.user_id');
        })
        ->leftJoin('users as second_approval',function($join){
            $join->on('second_approval.id','=','mkt_costs.second_approved_by');
        })
        ->leftJoin('employee_h_rs as second_approval_emp',function($join){
          $join->on('second_approval.id','=','second_approval_emp.user_id');
        })
        ->leftJoin('users as third_approval',function($join){
            $join->on('third_approval.id','=','mkt_costs.third_approved_by');
        })
        ->leftJoin('employee_h_rs as third_approval_emp',function($join){
          $join->on('third_approval.id','=','third_approval_emp.user_id');
        })
        ->leftJoin('users as final_approval',function($join){
            $join->on('final_approval.id','=','mkt_costs.final_approved_by');
        })
        ->leftJoin('employee_h_rs as final_approval_emp',function($join){
          $join->on('final_approval.id','=','final_approval_emp.user_id');
        })
        ->where([['mkt_costs.id','=',$id]])
        ->get([
            'mkt_costs.*',
            'styles.style_ref',
            'styles.flie_src',
            'buyers.name as buyer_name',
            'teams.name as team_name',
            'currencies.code as currency_code',
            'uoms.code as uom_code',
            'seasons.name as season_name',

            'first_approval.name as first_approval_name',
            'first_approval.signature_file as first_approval_signature',
            'first_approval_emp.name as first_approval_emp_name',
            'first_approval_emp.contact as first_approval_emp_contact',
            'first_approval_emp.designation_id as first_approval_emp_designation',
            'second_approval_emp.name as second_approval_name',
            'second_approval.signature_file as second_approval_signature',
            'third_approval_emp.name as third_approval_name',
            'third_approval.signature_file as third_approval_signature',
            'final_approval_emp.name as final_approval_name',
            'final_approval.signature_file as final_approval_signature',
        ]);
        foreach($rows as $row){
            $mktcost['id']= $row->id;
            $mktcost['company_id']= $row->company_id;
            $mktcost['costingunit']=  $costingunit[$row->costing_unit_id];
            $mktcost['costingunitqty']=$row->costing_unit_id;
            $mktcost['quotdate']= $row->quot_date;
            $mktcost['incoterm']= $incoterm[$row->incoterm_id];
            $mktcost['incotermplace']=  $row->incoterm_place;
            $mktcost['offerqty']= $row->offer_qty;
            $mktcost['estshipdate']=  date("d-m-Y",strtotime($row->est_ship_date));
            $mktcost['opdate']= date("d-m-Y",strtotime($row->op_date));
            $mktcost['style']=  $row->style_ref;
            $mktcost['currency']= $row->currency_code;
            $mktcost['buyer']=  $row->buyer_name;
            $mktcost['uom']=  $row->uom_code;
            $mktcost['team']= $row->team_name;
            //$mktcost['quote_price']=  $row->quote_price;
            //$mktcost['target_price']= $row->target_price;
            $mktcost['season_name']=  $row->season_name;
            $mktcost['flie_src']= $row->flie_src;
            $mktcost['sewing_smv']= $row->sewing_smv;
            $mktcost['sewing_effi_per']= $row->sewing_effi_per;
            $mktcost['production_per_hr']= $row->production_per_hr;
            $mktcost['first_approval_name']=$row->first_approval_name;
            $mktcost['first_approval_emp_name']=$row->first_approval_emp_name;
            $mktcost['first_approval_emp_contact']=$row->first_approval_emp_contact;
            $mktcost['first_approval_emp_designation']=$designation[$row->first_approval_emp_designation];

            $mktcost['second_approval_name']=$row->second_approval_name;
            $mktcost['third_approval_name']=$row->third_approval_name;
            $mktcost['final_approval_name']=$row->final_approval_name;

            $mktcost['first_approved_at']=$row->first_approved_at?date('d-M-Y h:i:s ',strtotime($row->first_approved_at)):'';
            $mktcost['second_approved_at']=$row->second_approved_at?date('d-M-Y h:i:s ',strtotime($row->second_approved_at)):'';
            $mktcost['third_approved_at']=$row->third_approved_at?date('d-M-Y h:i:s ',strtotime($row->third_approved_at)):'';
            $mktcost['final_approved_at']=$row->final_approved_at?date('d-M-Y h:i:s ',strtotime($row->final_approved_at)):'';
            $mktcost['first_approval_signature']=$row->first_approval_signature?'images/signature/'.$row->first_approval_signature:null;
            $mktcost['second_approval_signature']=$row->second_approval_signature?'images/signature/'.$row->second_approval_signature:null;
            $mktcost['third_approval_signature']=$row->third_approval_signature?'images/signature/'.$row->third_approval_signature:null;
            $mktcost['final_approval_signature']=$row->final_approval_signature?'images/signature/'.$row->final_approval_signature:null;
        }

        $company=$this->company->where([['id','=',$mktcost['company_id']]])->get()->first();
        $mktcost['company_data']=$company;

        $status = $this->mktcost->selectRaw(
        'mkt_costs.id,
        mkt_cost_quote_prices.qprice_date,
        mkt_cost_quote_prices.submission_date,
        mkt_cost_quote_prices.confirm_date,
        mkt_cost_quote_prices.refused_date,
        mkt_cost_quote_prices.cancel_date,
        mkt_cost_quote_prices.quote_price'
        )
        ->leftJoin('mkt_cost_quote_prices', function($join) {
        $join->on('mkt_cost_quote_prices.mkt_cost_id', '=', 'mkt_costs.id');
        })
        ->where([['mkt_costs.id','=',$id]])
        ->get()->first();

        $cofirmed="";
        if($status->confirm_date){
        $cofirmed="Confirmed";
        }
        else if ( $status->submission_date ){
        $cofirmed="Submited";
        }
        else if ( $status->refused_date ){
        $cofirmed="Refused";
        }
        else if ( $status->cancel_date ){
        $cofirmed="Cancel";
        }

        $othercosthead=array_prepend(config('bprs.othercosthead'),'-Select-','');
        $other=$this->mktcost->otherCost($id);
        $otherArr=array();
        foreach($other as $key=>$value){
        $otherArr[$key]['cost_head']=$othercosthead[$key];
        $otherArr[$key]['amount']=$value;

        }
        $mktcost['commission']=$this->mktcost->Commission($id);
        foreach($mktcost['commission'] as $comm=>$commValue){
        }

        $mktcost['QuotedPrice']=$this->mktcost->totalQuotePrice($id);
        $mktcost['grossFobPrice']=number_format($mktcost['QuotedPrice']->quote_price*$mktcost['costingunitqty'],4);
        $mktcost['grossFobPrice_pcs']=number_format($mktcost['QuotedPrice']->quote_price,4);
        $totCom=0;
        foreach($mktcost['commission'] as $comm=>$commValue){
        $totCom+=($mktcost['grossFobPrice']*$commValue['rate']/100);
        }

        $mktcost['TargetPrice']=$this->mktcost->totalTargetPrice($id);
        $mktcost['grossTargetPrice']=number_format($mktcost['TargetPrice']->target_price*$mktcost['costingunitqty'],4);
        $mktcost['grossTargetPricePcs']=number_format($mktcost['TargetPrice']->target_price,4);

        $mktcost['totalPriceAfterCommission']=number_format($this->mktcost->totalPriceAfterCommission($id),4);
        $mktcost['totalCommission']=number_format($totCom,4);
        $mktcost['netFobValue']=number_format($mktcost['grossFobPrice']-$mktcost['totalCommission'],4);
        $mktcost['totalYarnCost']=number_format($this->mktcost->totalYarnCost($id),4);
        $mktcost['totalFabricProdCost']=number_format($this->mktcost->totalFabricProdCost($id),4);
        $mktcost['totalFabricCons']=number_format($this->mktcost->totalFabricCons($id),4);
        $mktcost['avgFabricProcessLoss']=number_format($this->mktcost->avgFabricProcessLoss($id),4);
        $mktcost['totalFabricCost']=number_format($this->mktcost->totalFabricCost($id),4);
        $mktcost['totalTrimCost']=number_format($this->mktcost->totalTrimCost($id),4);
        $mktcost['totalEmbCost']=number_format($this->mktcost->totalEmbCost($id),4);
        $otherCost=$this->mktcost->otherCost($id);
        $totalOperatingCost=isset($otherCost[20])?$otherCost[20]:0;
        $totalDepreciationCost=isset($otherCost[25])?$otherCost[25]:0;
        $totalIcomeTaxCost=isset($otherCost[35])?$otherCost[35]:0;
        $totalInterestCost=isset($otherCost[30])?$otherCost[30]:0;

        $mktcost['totalOtherCost']=number_format($this->mktcost->totalOtherCost($id)-(number_format($totalOperatingCost,4)+number_format($totalDepreciationCost,4)+number_format($totalIcomeTaxCost,4)),4);

        $mktcost['costOfMaterial']=number_format($mktcost['totalYarnCost']+$mktcost['totalFabricProdCost']+$mktcost['totalFabricCost']+$mktcost['totalTrimCost']+$mktcost['totalEmbCost']+$mktcost['totalOtherCost'],4);
        $mktcost['contribution']=number_format($mktcost['netFobValue']-$mktcost['costOfMaterial'],4);
        $mktcost['totalCmCost']=number_format($this->mktcost->totalCmCost($id),4);
        $mktcost['grossProfit']=$mktcost['contribution']-$mktcost['totalCmCost'];
        $mktcost['totalCommercialCost']=number_format($this->mktcost->totalCommercialCost($id),4);
        $mktcost['totalOperatingCost']=number_format($totalOperatingCost,4);
        $mktcost['operatingProfitLoss']=number_format($mktcost['grossProfit']-($mktcost['totalCommercialCost']+$mktcost['totalOperatingCost']),4);
        $mktcost['totalDepreciationCost']=number_format($totalDepreciationCost,4);
        $mktcost['totalInterestCost']=number_format($totalInterestCost,4);
        $mktcost['totalIcomeTaxCost']=number_format($totalIcomeTaxCost,4);
        $mktcost['netProfit']=$mktcost['operatingProfitLoss']-($mktcost['totalDepreciationCost']+$mktcost['totalInterestCost']+$mktcost['totalIcomeTaxCost']);
        //==========value============
        $mktcost['grossFobvalue']=number_format($mktcost['grossFobPrice']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['totalCommissionvalue']=number_format($mktcost['totalCommission']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['netFobValuevalue']=number_format($mktcost['grossFobvalue']-$mktcost['totalCommissionvalue'],4,'.','');
        $mktcost['totalYarnCostvalue']=number_format($mktcost['totalYarnCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['totalFabricProdCostvalue']=number_format($mktcost['totalFabricProdCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['totalFabricCostvalue']=number_format($mktcost['totalFabricCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['totalTrimCostvalue']=number_format($mktcost['totalTrimCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['totalEmbCostvalue']=number_format($mktcost['totalEmbCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['totalOtherCostvalue']=number_format($mktcost['totalOtherCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['costOfMaterialvalue']=number_format($mktcost['totalYarnCostvalue']+$mktcost['totalFabricProdCostvalue']+$mktcost['totalFabricCostvalue']+$mktcost['totalTrimCostvalue']+$mktcost['totalEmbCostvalue']+$mktcost['totalOtherCostvalue'],4,'.','');
        $mktcost['contributionvalue']=number_format($mktcost['netFobValuevalue']-$mktcost['costOfMaterialvalue'],4,'.','');
        $mktcost['totalCmCostvalue']=number_format($mktcost['totalCmCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['grossProfitvalue']=number_format($mktcost['contributionvalue']-$mktcost['totalCmCostvalue'],4,'.','');
        $mktcost['totalCommercialCostvalue']=number_format($mktcost['totalCommercialCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');

        $mktcost['totalOperatingCostvalue']=number_format($mktcost['totalOperatingCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['operatingProfitLossvalue']=number_format($mktcost['grossProfitvalue']-($mktcost['totalCommercialCostvalue']+$mktcost['totalOperatingCostvalue']),4,'.','');
        $mktcost['totalDepreciationCostvalue']=number_format($mktcost['totalDepreciationCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['totalInterestCostvalue']=number_format($mktcost['totalInterestCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['totalIcomeTaxCostvalue']=number_format($mktcost['totalIcomeTaxCost']*($mktcost['offerqty']/$mktcost['costingunitqty']),4,'.','');
        $mktcost['netProfitvalue']=number_format($mktcost['operatingProfitLossvalue']-($mktcost['totalDepreciationCostvalue']+$mktcost['totalInterestCostvalue']+$mktcost['totalIcomeTaxCostvalue']),4,'.','');

        //==========percent============
        $grossFobvalue=$mktcost['grossFobPrice']*($mktcost['offerqty']/$mktcost['costingunitqty'])?$mktcost['grossFobvalue']:1;

        $mktcost['totalCommissionPercent']=number_format(($mktcost['totalCommissionvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['netFobValuePercent']=number_format(100-$mktcost['totalCommissionPercent'],2,'.','');

        $mktcost['totalYarnCostPercent']=number_format(($mktcost['totalYarnCostvalue']/$grossFobvalue)*100,2,'.','');

        $mktcost['totalFabricProdCostPercent']=number_format(($mktcost['totalFabricProdCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['totalFabricCostPercent']=number_format(($mktcost['totalFabricCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['totalTrimCostPercent']=number_format(($mktcost['totalTrimCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['totalEmbCostPercent']=number_format(($mktcost['totalEmbCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['totalOtherCostPercent']=number_format(($mktcost['totalOtherCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['costOfMaterialPercent']=number_format($mktcost['totalYarnCostPercent']+$mktcost['totalFabricProdCostPercent']+$mktcost['totalFabricCostPercent']+$mktcost['totalTrimCostPercent']+$mktcost['totalEmbCostPercent']+$mktcost['totalOtherCostPercent'],2,'.','');

        $mktcost['contributionPercent']=number_format($mktcost['netFobValuePercent']-$mktcost['costOfMaterialPercent'],2,'.','');
        $mktcost['totalCmCostPercent']=number_format(($mktcost['totalCmCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['grossProfitPercent']=number_format($mktcost['contributionPercent']-$mktcost['totalCmCostPercent'],2,'.','');

        $mktcost['totalCommercialCostPercent']=number_format(($mktcost['totalCommercialCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['totalOperatingCostPercent']=number_format(($mktcost['totalOperatingCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['operatingProfitLossPercent']=number_format($mktcost['grossProfitPercent']-($mktcost['totalCommercialCostPercent']+$mktcost['totalOperatingCostPercent']),2,'.','');


        $mktcost['totalDepreciationCostPercent']=number_format(($mktcost['totalDepreciationCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['totalInterestCostPercent']=number_format(($mktcost['totalInterestCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['totalIcomeTaxCostPercent']=number_format(($mktcost['totalIcomeTaxCostvalue']/$grossFobvalue)*100,2,'.','');
        $mktcost['netProfitPercent']=number_format($mktcost['operatingProfitLossPercent']-($mktcost['totalDepreciationCostPercent']+$mktcost['totalInterestCostPercent']+$mktcost['totalIcomeTaxCostPercent']),2,'.','');

        $mktcost['fabrics']=$this->mktcost->fabricCost($id);

        $yarnDescription=$this->itemaccount
        ->join('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->join('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->join('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->join('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->where([['itemcategories.identity','=',1]])
        ->get([
        'item_accounts.id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'itemclasses.name as itemclass_name',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);
        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
        $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
        $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
        $itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
        $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
        $yarnDropdown[$key]=$value['itemclass_name']." ".$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
        }

        $yarns=$this->mktcost->yarnCost($id);

        $totYarnCons=0;

        $mktcostyarns=array();
        foreach($yarns as $row){
        $totYarnCons+=$row->cons;
        $mktcostyarn['id']= $row->id;
        $mktcostyarn['yarn_cons']=  $row->cons;
        $mktcostyarn['yarn_rate']=  $row->rate;
        $mktcostyarn['yarn_amount']=  $row->amount;
        $mktcostyarn['yarn_des']= array_key_exists($row->item_account_id,$yarnDropdown)?$yarnDropdown[$row->item_account_id]:'';
        array_push($mktcostyarns,$mktcostyarn);
        }
        $mktcost['yarns']=$mktcostyarns;
        $mktcost['totYarnCons']=$totYarnCons;

        $mktcostfabricprods=array();
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        $fabricDescription=$this->mktcost
        ->join('styles',function($join){
        $join->on('styles.id','=','mkt_costs.style_id');
        })
        ->join('style_fabrications',function($join){
        $join->on('style_fabrications.style_id','=','mkt_costs.style_id');
        })
        ->join('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->join('autoyarnratios',function($join){
        $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->join('mkt_cost_fabrics',function($join){
        $join->on('mkt_cost_fabrics.mkt_cost_id','=','mkt_costs.id');
        $join->on('mkt_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
        })
        ->join('constructions',function($join){
        $join->on('constructions.id','=','autoyarns.construction_id');
        })
        ->join('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->join('style_gmts',function($join){
        $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->where([['mkt_costs.id','=',$id]])
        ->groupBy([
        'mkt_cost_fabrics.id',
        'style_fabrications.fabric_nature_id',
        'style_fabrications.fabric_look_id',
        'style_fabrications.fabric_shape_id',
        'item_accounts.item_description',
        'gmtsparts.name',
        'autoyarnratios.composition_id',
        'constructions.name',
        'compositions.name',
        'autoyarnratios.ratio',
        ])
        ->get([
        'mkt_cost_fabrics.id',
        'style_fabrications.fabric_nature_id',
        'style_fabrications.fabric_look_id',
        'style_fabrications.fabric_shape_id',
        'gmtsparts.name as gmtspart_name',
        'item_accounts.item_description',
        'autoyarnratios.composition_id',
        'constructions.name as construction',
        'compositions.name',
        'autoyarnratios.ratio',
        ]);
        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
        $fabricDescriptionArr[$row->id]=$row->item_description." ".$row->gmtspart_name." ".$fabricnature[$row->fabric_nature_id]." ".$fabriclooks[$row->fabric_look_id]." ".$fabricshape[$row->fabric_shape_id]." ".$row->construction;
        $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }
        $prod=$this->mktcost->fabricProdCost($id);
        $mktcostfabricprods=array();
        foreach($prod as $row){
        $mktcostfabricprod['id']= $row->id;
        $mktcostfabricprod['process_id']= $row->process_name;
        $mktcostfabricprod['cons']= $row->cons;
        $mktcostfabricprod['rate']= $row->rate;
        $mktcostfabricprod['amount']= $row->amount;
        $mktcostfabricprod['mktcostfabric']=  $desDropdown[$row->mkt_cost_fabric_id];
        array_push($mktcostfabricprods,$mktcostfabricprod);
        }
        $mktcost['fabricProd']=$mktcostfabricprods;
        $mktcost['trims']=$this->mktcost->TrimCost($id);
        $mktcost['embs']=$this->mktcost->EmbCost($id);

        $mktcost['other']=$otherArr;
        $mktcost['cm']=$this->mktcost->CmCost($id);
        $mktcost['commercial']=$this->mktcost->CommercialCost($id);
        $mktcost['total_cost']=$this->mktcost->totalCost($id);
        $mktcost['profit']=$this->mktcost->totalProfit($id);
        $mktcost['profitRate']=$this->mktcost->totalProfitRate($id);

        $mktcost['price_bfr_commission']=$this->mktcost->totalPriceBeforeCommission($id);
        $price_aft_commission=$this->mktcost->totalPriceAfterCommission($id);
        $mktcost['price_aft_commission']=$price_aft_commission;
        $mktcost['price_aft_commission_pcs']=number_format($price_aft_commission/$mktcost['costingunitqty'],4);
        $mktcost['status']=$cofirmed;


        $keycontrol=$this->keycontrol
        ->join('keycontrol_parameters', function($join)  {
        $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
        })
        ->where([['parameter_id','=',4]])
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$mktcost['quotdate']])
        ->get([
        'keycontrol_parameters.value'
        ])->first();
        $mktcost['cpm']=$keycontrol->value;

        $comment_histories=$this->mktcost
        ->join('approval_comment_histories',function($join){
        $join->on('mkt_costs.id','=','approval_comment_histories.model_id');
        })
        ->join('users',function($join){
        $join->on('users.id','=','approval_comment_histories.comments_by');
        })
        ->where([['approval_comment_histories.model_type','=','mkt_costs']])
        ->where([['mkt_costs.id','=',$id]])
        ->orderBy('approval_comment_histories.id')
        ->get(['approval_comment_histories.*','users.name as user_name']);
        $mktcost['comment_histories']=$comment_histories;
        return $mktcost;
    }

	public function getPdf () {
        $id=request('id',0);
        $mktcost=$this->getData($id);
        $company=$mktcost['company_data'];
        $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $header=['logo'=>$company->logo,'address'=>$company->address,'title'=>'Marketing Cost'];
        $pdf->setCustomHeader($header);
        $pdf->SetPrintHeader(true);
        $pdf->SetPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        //$pdf->SetY(10);
        //$txt = "Marketing Cost";
        //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
        //$pdf->SetY(5);
        //$pdf->Text(90, 5, $txt);
        /*$image_file ='images/logo/logo_21.png';
        $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(15);
        $pdf->SetFont('helvetica', 'N', 10);
        $pdf->Text(90, 12, 'Marketing Cost');
        $pdf->SetY(25);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle('Marketing Cost');*/
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Marketing.MktCostPdf',[
          'mktcost'=>$mktcost,
          'is_html'=>0,
        ]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/MktCostPdf.pdf';
        $pdf->output($filename);
        exit();
    }

    public function getHtml () {
        $id=request('id',0);
        $approval_type=request('approval_type',0);
        $mktcost=$this->getData($id);
        $company=$mktcost['company_data'];
        return Template::loadView('Marketing.MktCostPdf', [
            'mktcost'=>$mktcost,
            'company'=>$company,
            'is_html'=>1,
            'approval_type'=>$approval_type,
        ]);
    }


    public function getPdfQuote () {
      $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      $pdf->SetY(10);
      $txt = "Lithe Group";
      //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetY(5);
      $pdf->Text(90, 5, $txt);
      $pdf->SetY(10);
      $pdf->Text(90, 10, "Price Offer");
      $pdf->SetFont('helvetica', '', 8);
      //$pdf->SetTitle('Price Offer');
      $id=request('id',0);

      $costingunit=array_prepend(config('bprs.costingunit'),'-Select-','');
      //$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $buyercontact=array_prepend(array_pluck($this->buyerbranch->get(),'contact_person','buyer_id'),'-Select-','');


      $mktcosts=array();
      $rows=$this->mktcost->join('styles',function($join){
      $join->on('styles.id','=','mkt_costs.style_id');
      })
      ->join('buyers',function($join){
      $join->on('buyers.id','=','styles.buyer_id');
      })
      ->join('teams',function($join){
      $join->on('teams.id','=','styles.team_id');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','mkt_costs.currency_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','styles.uom_id');
      })
      ->join('seasons',function($join){
      $join->on('seasons.id','=','styles.season_id');
      })
      ->where([['mkt_costs.id','=',$id]])
      ->get([
      'mkt_costs.*',
      'styles.style_ref',
      'styles.flie_src',
      'buyers.name as buyer_name',
      'buyers.buying_agent_id',
      'teams.name as team_name',
      'currencies.code as currency_code',
      'uoms.code as uom_code',
      'seasons.name as season_name'
      ]);
      foreach($rows as $row){
      $mktcost['id']=$row->id;
      $mktcost['costingunit']=isset($costingunit[$row->costing_unit_id])?$costingunit[$row->costing_unit_id]:'';
      $mktcost['costingunitqty']=$row->costing_unit_id;
      $mktcost['quotdate']=	$row->quot_date;
      $mktcost['offerqty']=	$row->offer_qty;
      $mktcost['estshipdate']=date("d-m-Y",strtotime($row->est_ship_date));
      $mktcost['style']=$row->style_ref;
      $mktcost['currency']=$row->currency_code;
      $mktcost['buyer']=$row->buyer_name;
      $mktcost['buyer_agent']=isset($buyercontact[$row->buying_agent_id])?$buyercontact[$row->buying_agent_id]:'';
      }
      $mktcost['QuotedPrice']=$this->mktcost->totalQuotePrice($id);





      //$mktcost['fabrics']=$this->mktcost->fabricCost($id);


      $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
      $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
      $fabricDescription=$this->mktcost->selectRaw(
      'style_fabrications.id,
      constructions.name as construction,
      autoyarnratios.composition_id,
      compositions.name,
      autoyarnratios.ratio'
      )
      ->join('styles',function($join){
      $join->on('styles.id','=','mkt_costs.style_id');
      })
      ->join('style_fabrications',function($join){
      $join->on('style_fabrications.style_id','=','mkt_costs.style_id');
      })
      ->join('autoyarns',function($join){
      $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
      })
      ->join('autoyarnratios',function($join){
      $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
      })
      ->join('compositions',function($join){
      $join->on('compositions.id','=','autoyarnratios.composition_id');
      })
      ->join('constructions',function($join){
      $join->on('constructions.id','=','autoyarns.construction_id');
      })
      ->where([['mkt_costs.id','=',$id]])
      ->get();
      $fabricDescriptionArr=array();
      $fabricCompositionArr=array();
      foreach($fabricDescription as $row){
      $fabricDescriptionArr[$row->id]=$row->construction;
      $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
      }
      $desDropdown=array();
      foreach($fabricDescriptionArr as $key=>$val){
      $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
      }

      $fabrics=$this->mktcost->selectRaw(
      'mkt_costs.id as mkt_cost_id,
      style_fabrications.id as style_fabrication_id,
      style_fabrications.material_source_id,
      style_fabrications.fabric_nature_id,
      style_fabrications.fabric_look_id,
      style_fabrications.fabric_shape_id,
      style_fabrications.is_narrow,
      style_gmts.id as style_gmt_id,
      gmtsparts.name as gmtspart_name,
      item_accounts.item_description,
      uoms.code as uom_name,
      mkt_cost_fabrics.gsm_weight,
      mkt_cost_fabrics.id
      '
      )
      ->join('styles',function($join){
      $join->on('styles.id','=','mkt_costs.style_id');
      })
      ->join('style_fabrications',function($join){
      $join->on('style_fabrications.style_id','=','mkt_costs.style_id');
      })
      ->join('style_gmts',function($join){
      $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
      })
      ->join('item_accounts', function($join) {
      $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('gmtsparts',function($join){
      $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
      })
      ->join('autoyarns',function($join){
      $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
      })

      ->join('uoms',function($join){
      $join->on('uoms.id','=','style_fabrications.uom_id');
      })
      ->leftJoin('mkt_cost_fabrics',function($join){
      $join->on('mkt_cost_fabrics.mkt_cost_id','=','mkt_costs.id');
      $join->on('mkt_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
      })
      ->where([['mkt_costs.id','=',$id]])
      ->orderBy('mkt_cost_fabrics.id','asc')
      ->get();
      $stylefabrications=array();
      $styleitems=array();
      foreach($fabrics as $row){
      $stylefabrication['style_gmt']=	$row->item_description;
      $stylefabrication['gmtspart']=	$row->gmtspart_name;
      $stylefabrication['fabric_description']=	$desDropdown[$row->style_fabrication_id];
      $stylefabrication['fabriclooks']=	$fabriclooks[$row->fabric_look_id];
      $stylefabrication['gsm_weight']=	$row->gsm_weight;
      $styleitems[$row->mkt_cost_id][$row->style_gmt_id]=$row->item_description;
      array_push($stylefabrications,$stylefabrication);
      }

      $mktcost['fabrics']=$stylefabrications;
      $mktcost['stylegmt']=implode(",",$styleitems[$id]);
      $view= \View::make('Defult.Marketing.MktCostquotePdf',['mktcost'=>$mktcost]);
      $html_content=$view->render();
      $pdf->SetY(15);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/MktCostquotePdf.pdf';
      //$pdf->output($filename);
      $pdf->output($filename,'I');
      exit();
      //$pdf->output($filename,'F');
      //return response()->download($filename);
    }
}
