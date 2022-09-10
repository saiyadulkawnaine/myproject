<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostQpriceRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\Approval\ApprovalCommentHistoryRepository;

class SoAopMktCostQpriceApprovalController extends Controller
{
	private $soaopmktcostqprice;
    private $soaopmktcost;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $keycontrol;
	private $company;
	private $buyer;
	private $team;
	private $teammember;
	private $approvalcommenthistory;
	public function __construct(
		SoAopMktCostQpriceRepository $soaopmktcostqprice,
        SoAopMktCostRepository $soaopmktcost,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        UomRepository $uom,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        KeycontrolRepository $keycontrol,
		CompanyRepository $company,
		BuyerRepository $buyer,
		TeamRepository $team,
		TeammemberRepository $teammember,
		ApprovalCommentHistoryRepository $approvalcommenthistory
	)
    {
		$this->soaopmktcostqprice = $soaopmktcostqprice;
        $this->soaopmktcost = $soaopmktcost;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->keycontrol = $keycontrol;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->team = $team;
        $this->teammember = $teammember;
        $this->approvalcommenthistory = $approvalcommenthistory;

		$this->middleware('auth');

		$this->middleware('permission:approvefirst.soaopmktcostqprices',   ['only' => ['firstapproved']]);
        $this->middleware('permission:approvesecond.soaopmktcostqprices', ['only' => ['secondapproved']]);
        $this->middleware('permission:approvethird.soaopmktcostqprices',   ['only' => ['thirdapproved']]);
        $this->middleware('permission:approvefinal.soaopmktcostqprices', ['only' => ['finalapproved']]);
    }

    public function index() {

    	$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
		$team=array_prepend(array_pluck($this->team->where([['team_type_id','=',1]])->where([['row_status','=',1]])->get(),'name','id'),'-Select-',0);
		$teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function($join)
		{
			$join->on('teammembers.user_id', '=', 'users.id');
		})
		->get([
			'teammembers.id',
			'users.name',
		]),'name','id'),'-Select-',0);
        return Template::loadView('Approval.SoAopMktCostQpriceApproval',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember]);
		
    }

	public function reportData() {
		$approval_type_id=request('approval_type_id');
		$data = $this->soaopmktcostqprice
		->selectRaw('
			so_aop_mkt_cost_qprices.id,
			so_aop_mkt_cost_qprices.qprice_no,
			so_aop_mkt_cost_qprices.qprice_date,
			so_aop_mkt_cost_qprices.remarks,
			buyers.code as buyer_name,
			users.name as user_name,
			so_aop_mkt_costs.id as so_aop_mkt_cost_id,
			so_aop_mkt_costs.costing_date,
			so_aop_mkt_costs.exch_rate,
			sub_inb_services.est_delv_date,
			sum(fabricDtls.fabric_wgt) as fabric_wgt,
			sum(fabricDtls.overhead_amount) as overhead_amount,
			sum(fabricItemDyesCost.dyes_cost) as dyes_cost,
            sum(fabricItemChemicalCost.chem_cost) as chem_cost,
            sum(fabricSpecialFinishCost.special_chem_cost) as special_chem_cost,
            sum(so_aop_mkt_cost_qpricedtls.cost_per_kg) as cost_per_kg,
            sum(so_aop_mkt_cost_qpricedtls.quoted_price_bdt) as quoted_price_bdt,
            sum(so_aop_mkt_cost_qpricedtls.quoted_price) as quoted_price,
            sum(so_aop_mkt_cost_qpricedtls.profit_amount_bdt) as profit_amount_bdt,
            sum(so_aop_mkt_cost_qpricedtls.profit_amount) as profit_amount,
            avg(so_aop_mkt_cost_qpricedtls.profit_per) as profit_per
		')
		->join('so_aop_mkt_costs',function($join){
            $join->on('so_aop_mkt_cost_qprices.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
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
        ->join('users',function($join){
            $join->on('users.id','=','so_aop_mkt_costs.created_by');
        })
        ->join('so_aop_mkt_cost_qpricedtls',function($join){
            $join->on('so_aop_mkt_cost_qpricedtls.so_aop_mkt_cost_qprice_id','=','so_aop_mkt_cost_qprices.id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
			so_aop_mkt_cost_qpricedtls.id,
			sum(so_aop_mkt_cost_param_items.amount) as dyes_cost 
			FROM so_aop_mkt_cost_qpricedtls
			join so_aop_mkt_cost_params on so_aop_mkt_cost_params.id=so_aop_mkt_cost_qpricedtls.so_aop_mkt_cost_param_id
			join so_aop_mkt_cost_param_items on so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id=so_aop_mkt_cost_params.id
			join item_accounts on item_accounts.id=so_aop_mkt_cost_param_items.item_account_id
			join itemcategories on itemcategories.id=item_accounts.itemcategory_id
			where  so_aop_mkt_cost_param_items.deleted_at is null
			and itemcategories.identity=7
			group by 
			so_aop_mkt_cost_qpricedtls.id
        ) fabricItemDyesCost"), "fabricItemDyesCost.id", "=", "so_aop_mkt_cost_qpricedtls.id")
        ->leftJoin(\DB::raw("(
            SELECT 
			so_aop_mkt_cost_qpricedtls.id,
			sum(so_aop_mkt_cost_param_items.amount) as chem_cost 
			FROM so_aop_mkt_cost_qpricedtls
			join so_aop_mkt_cost_params on so_aop_mkt_cost_params.id=so_aop_mkt_cost_qpricedtls.so_aop_mkt_cost_param_id
			join so_aop_mkt_cost_param_items on so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id=so_aop_mkt_cost_params.id
			join item_accounts on item_accounts.id=so_aop_mkt_cost_param_items.item_account_id
			join itemcategories on itemcategories.id=item_accounts.itemcategory_id
			where  so_aop_mkt_cost_param_items.deleted_at is null
			and itemcategories.identity=8
			group by 
			so_aop_mkt_cost_qpricedtls.id
        ) fabricItemChemicalCost"), "fabricItemChemicalCost.id", "=", "so_aop_mkt_cost_qpricedtls.id")
        ->leftJoin(\DB::raw("(
            SELECT 
			so_aop_mkt_cost_qpricedtls.id,
			sum(so_aop_mkt_cost_param_fins.amount) as special_chem_cost 
			FROM so_aop_mkt_cost_qpricedtls
			join so_aop_mkt_cost_params on so_aop_mkt_cost_params.id=so_aop_mkt_cost_qpricedtls.so_aop_mkt_cost_param_id
			join so_aop_mkt_cost_param_fins on so_aop_mkt_cost_param_fins.so_aop_mkt_cost_param_id=so_aop_mkt_cost_params.id
			where  so_aop_mkt_cost_param_fins.deleted_at is null
			group by 
			so_aop_mkt_cost_qpricedtls.id
        ) fabricSpecialFinishCost"), "fabricSpecialFinishCost.id", "=", "so_aop_mkt_cost_qpricedtls.id")
		->leftJoin(\DB::raw("(
            select 
			so_aop_mkt_cost_qpricedtls.id,
			sum(so_aop_mkt_cost_params.fabric_wgt) as fabric_wgt, 
			sum(so_aop_mkt_cost_params.overhead_amount) as overhead_amount 
			from so_aop_mkt_cost_qpricedtls
			join so_aop_mkt_cost_params on so_aop_mkt_cost_params.id=so_aop_mkt_cost_qpricedtls.so_aop_mkt_cost_param_id
			where  so_aop_mkt_cost_qpricedtls.deleted_at is null
			group by 
			so_aop_mkt_cost_qpricedtls.id
        ) fabricDtls"), "fabricDtls.id", "=", "so_aop_mkt_cost_qpricedtls.id")
		->when(request('buyer_id'), function ($q) {
			return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('qprice_no'), function ($q) {
			return $q->where('so_aop_mkt_cost_qprices.qprice_no', '=', request('qprice_no', 0));
		})
		->when(request('submission_from'), function ($q) {
			return $q->where('so_aop_mkt_cost_qprices.qprice_date', '>=',request('submission_from', 0));
		})
		->when(request('submission_to'), function ($q) {
			return $q->where('so_aop_mkt_cost_qprices.qprice_date', '<=',request('submission_to', 0));
		})
		->when(request('costing_from'), function ($q) {
			return $q->where('so_aop_mkt_costs.quot_date', '>=',request('costing_from', 0));
		})
		->when(request('costing_to'), function ($q) {
			return $q->where('so_aop_mkt_costs.quot_date', '<=',request('costing_to', 0));
		})
		->when($approval_type_id, function ($q) use ($approval_type_id){
			if($approval_type_id==1){
				return $q->whereNull('so_aop_mkt_cost_qprices.first_approved_at');
			}
			if($approval_type_id==2){
				return $q->whereNotNull('so_aop_mkt_cost_qprices.first_approved_at')->whereNull('so_aop_mkt_cost_qprices.second_approved_at');
			}
			if($approval_type_id==3){
				return $q->whereNotNull('so_aop_mkt_cost_qprices.second_approved_at')->whereNull('so_aop_mkt_cost_qprices.third_approved_at');
			}
			if($approval_type_id==10){
				return $q->whereNotNull('so_aop_mkt_cost_qprices.third_approved_at')
				->whereNull('so_aop_mkt_cost_qprices.final_approved_at');
			}
		})
		->where([['so_aop_mkt_cost_qprices.ready_to_approve_id','=',1]])
		->groupBy([
			'so_aop_mkt_cost_qprices.id',
			'so_aop_mkt_cost_qprices.qprice_no',
			'so_aop_mkt_cost_qprices.qprice_date',
			'so_aop_mkt_cost_qprices.remarks',
			'buyers.code',
			'users.name',
			'so_aop_mkt_costs.id',
			'so_aop_mkt_costs.costing_date',
			'sub_inb_services.est_delv_date',
			'so_aop_mkt_costs.exch_rate'
		])
		->orderBy('so_aop_mkt_cost_qprices.id','desc')
		->get()
		->map(function($data){
			$data->total_cost=$data->dyes_cost+$data->chem_cost+$data->overhead_amount+$data->special_chem_cost;
            $data->cost_per_kg_bdt=$data->total_cost/$data->fabric_wgt;
            //$data->cost_per_kg=$data->cost_per_kg_bdt/$data->exch_rate;
			return $data;
		});
		
		echo json_encode($data);
    }

    public function firstapproved (Request $request)
    {

    	foreach($request->id as $index=>$id){
			if($id){
				$user = \Auth::user(); 
				$first_approved_at=date('Y-m-d h:i:s');
				$soaopmktcostqprice = $this->soaopmktcostqprice->update($id,
				['first_approved_by' => $user->id,  'first_approved_at' =>  $first_approved_at]);
			}
		}
		return response()->json(array('success' => true,'type' => 'firstapproved', 'message' => 'Approved Successfully'), 200);

    }


    public function secondapproved (Request $request)
    {
    	foreach($request->id as $index=>$id){
			if($id){
				$user = \Auth::user(); 
				$second_approved_at=date('Y-m-d h:i:s');
				$soaopmktcostqprice = $this->soaopmktcostqprice->update($id,
				['second_approved_by' => $user->id,  'second_approved_at' =>  $second_approved_at]);
			}
		}
		return response()->json(array('success' => true, 'type' => 'secondapproved','message' => 'Approved Successfully'), 200);
    }

    public function thirdapproved (Request $request)
    {
    	foreach($request->id as $index=>$id){
			if($id){
				$user = \Auth::user(); 
				$third_approved_at=date('Y-m-d h:i:s');
				$soaopmktcostqprice = $this->soaopmktcostqprice->update($id,[
					'third_approved_by' => $user->id,  
					'third_approved_at' =>  $third_approved_at,
					'final_approved_by' => $user->id,  
					'final_approved_at' =>  $third_approved_at,
				]);
			}
		}
		return response()->json(array('success' => true,'type' => 'thirdapproved', 'message' => 'Approved Successfully'), 200);
    }
    public function finalapproved (Request $request)
    {
    	foreach($request->id as $index=>$id){
			if($id){
				$user = \Auth::user(); 
				$final_approved_at=date('Y-m-d h:i:s');
				$soaopmktcostqprice = $this->soaopmktcostqprice->update($id,[
					'third_approved_by' => $user->id,  
					'third_approved_at' =>  $final_approved_at,
					'final_approved_by' => $user->id,  
					'final_approved_at' =>  $final_approved_at,
				]);
			}
		}
		return response()->json(array('success' => true,'type' => 'finalapproved', 'message' => 'Approved Successfully'), 200);
    }

    public function approvalReturn(Request $request){
    	$id=$request->id;
    	$returned_coments=$request->returned_coments;
    	$aproval_type=$request->aproval_type;
		$user = \Auth::user(); 
		$returned_at=date('Y-m-d h:i:s');
		$soaopmktcostqprice = $this->soaopmktcostqprice->update($id,[
			'returned_by' => $user->id,  
			'returned_at' =>  $returned_at,
			'returned_coments' =>  $returned_coments,
			//'confirmed_by' => NULL,  
			//'confirmed_at' =>  NULL,
			'first_approved_by' => NULL,  
			'first_approved_at' =>  NULL,
			'second_approved_by' => NULL,  
			'second_approved_at' =>  NULL,
			'third_approved_by' => NULL,  
			'third_approved_at' =>  NULL,
			'final_approved_by' => NULL,  
			'final_approved_at' =>  NULL,
		]);

		$this->approvalcommenthistory->create([
        	'model_id'=>$id,
        	'model_type'=>'so_aop_mkt_cost_qprices',
        	'comments'=>$returned_coments,
        	'comments_by'=>$user->id,
        	'comments_at'=>$returned_at
        ]);

		return response()->json(array('success' => true,'type' => $aproval_type, 'message' => 'Returned Successfully'), 200);


    }
}
