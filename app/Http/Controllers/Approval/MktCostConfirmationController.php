<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\Marketing\MktCostQuotePriceRepository;
use App\Repositories\Contracts\Approval\ApprovalCommentHistoryRepository;

class MktCostConfirmationController extends Controller
{
	private $mktcost;
	private $company;
	private $buyer;
	private $team;
	private $teammember;
    private $mktcostquoteprice;
    private $approvalcommenthistory;

	public function __construct(
        MktCostRepository $mktcost,
        CompanyRepository $company,
        BuyerRepository $buyer,
        TeamRepository $team,
        TeammemberRepository $teammember,
        MktCostQuotePriceRepository $mktcostquoteprice,
        ApprovalCommentHistoryRepository $approvalcommenthistory
        
        )
    {
		$this->mktcost=$mktcost;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->team = $team;
        $this->teammember = $teammember;
        $this->mktcostquoteprice = $mktcostquoteprice;
        $this->approvalcommenthistory = $approvalcommenthistory;

		$this->middleware('auth');

		// $this->middleware('permission:confirm.mktcosts',   ['only' => ['confirmed']]);
        // $this->middleware('permission:approvesecond.mktcosts', ['only' => ['secondapproved']]);
        // $this->middleware('permission:approvethird.mktcosts',   ['only' => ['thirdapproved']]);
        // $this->middleware('permission:approvefinal.mktcosts', ['only' => ['approved']]);
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
        return Template::loadView('Approval.MktCostConfirmation',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember]);
		
    }
	public function reportData() {
		$data = DB::table("mkt_costs")
		->select("mkt_costs.*",
		"buyers.code as buyer_name",
		"styles.style_ref",
		"styles.style_description",
		"styles.flie_src",
		"seasons.name as season_name",
		"productdepartments.department_name",
		"uoms.code as uom_code",
		'mkt_cost_quote_prices.quote_price as price',
		'mkt_cost_quote_prices.submission_date',
		'mkt_cost_quote_prices.confirm_date',
		'mkt_cost_quote_prices.refused_date',
		'mkt_cost_quote_prices.cancel_date',
		'mkt_cost_target_prices.target_price as  t_price',
		'users.name as team_member',
		'teams.name as team_name',
		'createdby.name as created_by',
		DB::raw("(SELECT SUM(mkt_cost_fabrics.amount) FROM mkt_cost_fabrics
		WHERE mkt_cost_fabrics.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_fabrics.mkt_cost_id) as fab_amount"),

		DB::raw("(SELECT SUM(mkt_cost_yarns.amount) FROM mkt_cost_yarns
		WHERE mkt_cost_yarns.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_yarns.mkt_cost_id) as yarn_amount"),

		DB::raw("(SELECT SUM(mkt_cost_fabric_prods.amount) FROM mkt_cost_fabric_prods
		WHERE mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_fabric_prods.mkt_cost_id) as prod_amount"),

		DB::raw("(SELECT SUM(mkt_cost_trims.amount) FROM mkt_cost_trims
		WHERE mkt_cost_trims.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_trims.mkt_cost_id) as trim_amount"),

		DB::raw("(SELECT SUM(mkt_cost_embs.amount) FROM mkt_cost_embs
		WHERE mkt_cost_embs.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_embs.mkt_cost_id) as emb_amount"),

		DB::raw("(SELECT SUM(mkt_cost_cms.amount) FROM mkt_cost_cms
		WHERE mkt_cost_cms.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_cms.mkt_cost_id) as cm_amount"),

		DB::raw("(SELECT SUM(mkt_cost_others.amount) FROM mkt_cost_others
		WHERE mkt_cost_others.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_others.mkt_cost_id) as other_amount"),

		DB::raw("(SELECT SUM(mkt_cost_commercials.amount) FROM mkt_cost_commercials
		WHERE mkt_cost_commercials.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_commercials.mkt_cost_id) as commercial_amount"),

		DB::raw("(SELECT SUM(mkt_cost_profits.amount) FROM mkt_cost_profits
		WHERE mkt_cost_profits.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_profits.mkt_cost_id) as profit_amount"),

		DB::raw("(SELECT SUM(mkt_cost_commissions.amount) FROM mkt_cost_commissions
		WHERE mkt_cost_commissions.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_commissions.mkt_cost_id) as commission_amount"),

		DB::raw("(SELECT SUM(mkt_cost_commissions.rate) FROM mkt_cost_commissions
		WHERE mkt_cost_commissions.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_commissions.mkt_cost_id) as commission_rate")
		)
		->join('styles',function($join){
		$join->on('mkt_costs.style_id','=','styles.id');
		})
		->join('buyers',function($join){
		$join->on('styles.buyer_id','=','buyers.id');
		})
		->leftJoin('seasons',function($join){
		$join->on('styles.season_id','=','seasons.id');
		})
		->leftJoin('productdepartments',function($join){
		$join->on('styles.productdepartment_id','=','productdepartments.id');
		})
		->leftJoin('uoms',function($join){
		$join->on('styles.uom_id','=','uoms.id');
		})
		->leftJoin('teammembers',function($join){
		$join->on('teammembers.id','=','styles.teammember_id');
		})
		->leftJoin('users',function($join){
		$join->on('users.id','=','teammembers.user_id');
		})
		->leftJoin('mkt_cost_quote_prices',function($join){
		$join->on('mkt_costs.id','=','mkt_cost_quote_prices.mkt_cost_id');
		})
		->leftJoin('mkt_cost_target_prices',function($join){
		$join->on('mkt_costs.id','=','mkt_cost_target_prices.mkt_cost_id');
		})
		->leftJoin('teams',function($join){
			$join->on('teams.id','=','styles.team_id');
		})
		->leftJoin('users as createdby',function($join){
			$join->on('createdby.id','=','mkt_costs.created_by');
		})
		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('team_id'), function ($q) {
		return $q->where('styles.team_id', '=', request('team_id', 0));
		})
		->when(request('teammember_id'), function ($q) {
		return $q->where('styles.teammember_id', '=', request('teammember_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
		})
		->when(request('date_from'), function ($q) {
		return $q->where('mkt_costs.est_ship_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
		return $q->where('mkt_costs.est_ship_date', '<=',request('date_to', 0));
		})
		->when(request('confirm_from'), function ($q) {
		return $q->where('mkt_cost_quote_prices.confirm_date', '>=',request('confirm_from', 0));
		})
		->when(request('confirm_to'), function ($q) {
		return $q->where('mkt_cost_quote_prices.confirm_date', '<=',request('confirm_to', 0));
		})
		->when(request('costing_from'), function ($q) {
		return $q->where('mkt_costs.quot_date', '>=',request('costing_from', 0));
		})
		->when(request('costing_to'), function ($q) {
		return $q->where('mkt_costs.quot_date', '<=',request('costing_to', 0));
		})
		->whereDate('mkt_costs.quot_date','>','2021-12-31')
		->whereNotNull('mkt_cost_quote_prices.qprice_date')
		->whereNull('mkt_costs.confirmed_at')
		->whereNull('mkt_costs.returned_at')
		->whereNotNull('mkt_cost_quote_prices.confirm_date')
		->orderBy('mkt_costs.id','desc')
		->get();
		$datas=array();
		$totOffer=0;
		$totAmt=0;
		foreach($data as $row){
		$row->amount=number_format($row->price*$row->offer_qty,2,'.',',');
		$row->offer_qty=number_format($row->offer_qty,0,'.',',');
		$row->price=number_format($row->price,4,'.',',');
		$row->est_ship_date=date("d-M-Y",strtotime($row->est_ship_date));
		$row->quot_date=date("d-M-Y",strtotime($row->quot_date));
		$row->price_bfr_commission=$row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$row->profit_amount;
		$row->price_aft_commission=number_format(($row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$row->profit_amount+$row->commission_amount)/$row->costing_unit_id,4,'.',',');
		$commission_on_quoted_price_dzn=((($row->price*$row->costing_unit_id))*$row->commission_rate)/100;
		$commission_on_quoted_price_pcs=$commission_on_quoted_price_dzn/$row->costing_unit_id;
		$row->commission_on_quoted_price_dzn=number_format($commission_on_quoted_price_dzn,4,'.',',');
		$row->commission_on_quoted_price_pcs=number_format($commission_on_quoted_price_pcs,4,'.',',');

		$row->total_cost=$row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$commission_on_quoted_price_dzn;

		$cost_per_pcs=$row->total_cost/$row->costing_unit_id;
		$row->cost_per_pcs=number_format($cost_per_pcs,4,'.',',');

		$row->comments=($row->cost_per_pcs > $row->price)?"Less Than Cost":"";
		$row->cm=number_format(($row->price*$row->costing_unit_id)-($row->total_cost-$row->cm_amount),4,'.',',');

		$status="Preparing";
		if($row->submission_date){
		$status="Submited";
		}
		if($row->confirm_date){
		$status="Confirmed";
		}
		if($row->refused_date){
		$status="Refused";
		}
		if($row->cancel_date){
		$status="Cancel";
		}
		$row->status=$status;
		$totOffer+=$row->offer_qty;
		$totAmt+= $row->amount;
		array_push($datas,$row);
		}
		echo json_encode($datas);
    }

    public function reportDataReturned() {
		$data = DB::table("mkt_costs")
		->select("mkt_costs.*",
		"buyers.code as buyer_name",
		"styles.style_ref",
		"styles.style_description",
		"styles.flie_src",
		"seasons.name as season_name",
		"productdepartments.department_name",
		"uoms.code as uom_code",
		'mkt_cost_quote_prices.quote_price as price',
		'mkt_cost_quote_prices.submission_date',
		'mkt_cost_quote_prices.confirm_date',
		'mkt_cost_quote_prices.refused_date',
		'mkt_cost_quote_prices.cancel_date',
		'mkt_cost_target_prices.target_price as  t_price',
		'users.name as team_member',
		'teams.name as team_name',
		'returned.name as returned_by',
		'createdby.name as created_by',
		DB::raw("(SELECT SUM(mkt_cost_fabrics.amount) FROM mkt_cost_fabrics
		WHERE mkt_cost_fabrics.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_fabrics.mkt_cost_id) as fab_amount"),

		DB::raw("(SELECT SUM(mkt_cost_yarns.amount) FROM mkt_cost_yarns
		WHERE mkt_cost_yarns.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_yarns.mkt_cost_id) as yarn_amount"),

		DB::raw("(SELECT SUM(mkt_cost_fabric_prods.amount) FROM mkt_cost_fabric_prods
		WHERE mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_fabric_prods.mkt_cost_id) as prod_amount"),

		DB::raw("(SELECT SUM(mkt_cost_trims.amount) FROM mkt_cost_trims
		WHERE mkt_cost_trims.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_trims.mkt_cost_id) as trim_amount"),

		DB::raw("(SELECT SUM(mkt_cost_embs.amount) FROM mkt_cost_embs
		WHERE mkt_cost_embs.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_embs.mkt_cost_id) as emb_amount"),

		DB::raw("(SELECT SUM(mkt_cost_cms.amount) FROM mkt_cost_cms
		WHERE mkt_cost_cms.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_cms.mkt_cost_id) as cm_amount"),

		DB::raw("(SELECT SUM(mkt_cost_others.amount) FROM mkt_cost_others
		WHERE mkt_cost_others.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_others.mkt_cost_id) as other_amount"),

		DB::raw("(SELECT SUM(mkt_cost_commercials.amount) FROM mkt_cost_commercials
		WHERE mkt_cost_commercials.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_commercials.mkt_cost_id) as commercial_amount"),

		DB::raw("(SELECT SUM(mkt_cost_profits.amount) FROM mkt_cost_profits
		WHERE mkt_cost_profits.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_profits.mkt_cost_id) as profit_amount"),

		DB::raw("(SELECT SUM(mkt_cost_commissions.amount) FROM mkt_cost_commissions
		WHERE mkt_cost_commissions.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_commissions.mkt_cost_id) as commission_amount"),

		DB::raw("(SELECT SUM(mkt_cost_commissions.rate) FROM mkt_cost_commissions
		WHERE mkt_cost_commissions.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_commissions.mkt_cost_id) as commission_rate")
		)
		->join('styles',function($join){
		$join->on('mkt_costs.style_id','=','styles.id');
		})
		->join('buyers',function($join){
		$join->on('styles.buyer_id','=','buyers.id');
		})
		->leftJoin('seasons',function($join){
		$join->on('styles.season_id','=','seasons.id');
		})
		->leftJoin('productdepartments',function($join){
		$join->on('styles.productdepartment_id','=','productdepartments.id');
		})
		->leftJoin('uoms',function($join){
		$join->on('styles.uom_id','=','uoms.id');
		})
		->leftJoin('teammembers',function($join){
		$join->on('teammembers.id','=','styles.teammember_id');
		})
		->leftJoin('users',function($join){
		$join->on('users.id','=','teammembers.user_id');
		})
		->leftJoin('mkt_cost_quote_prices',function($join){
		$join->on('mkt_costs.id','=','mkt_cost_quote_prices.mkt_cost_id');
		})
		->leftJoin('mkt_cost_target_prices',function($join){
		$join->on('mkt_costs.id','=','mkt_cost_target_prices.mkt_cost_id');
		})
		->leftJoin('users as returned',function($join){
			$join->on('returned.id','=','mkt_costs.returned_by');
		})
		->leftJoin('teams',function($join){
			$join->on('teams.id','=','styles.team_id');
		})
		->leftJoin('users as createdby',function($join){
			$join->on('createdby.id','=','mkt_costs.created_by');
		})
		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('team_id'), function ($q) {
		return $q->where('styles.team_id', '=', request('team_id', 0));
		})
		->when(request('teammember_id'), function ($q) {
		return $q->where('styles.teammember_id', '=', request('teammember_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
		})
		->when(request('date_from'), function ($q) {
		return $q->where('mkt_costs.est_ship_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
		return $q->where('mkt_costs.est_ship_date', '<=',request('date_to', 0));
		})
		->when(request('confirm_from'), function ($q) {
		return $q->where('mkt_cost_quote_prices.confirm_date', '>=',request('confirm_from', 0));
		})
		->when(request('confirm_to'), function ($q) {
		return $q->where('mkt_cost_quote_prices.confirm_date', '<=',request('confirm_to', 0));
		})
		->when(request('costing_from'), function ($q) {
		return $q->where('mkt_costs.quot_date', '>=',request('costing_from', 0));
		})
		->when(request('costing_to'), function ($q) {
		return $q->where('mkt_costs.quot_date', '<=',request('costing_to', 0));
		})
		->whereDate('mkt_costs.quot_date','>','2021-12-31')
		->whereNotNull('mkt_cost_quote_prices.qprice_date')
		->whereNull('mkt_costs.confirmed_at')
		->whereNotNull('mkt_costs.returned_at')
		->orderBy('mkt_costs.id','desc')
		->get();
		$datas=array();
		$totOffer=0;
		$totAmt=0;
		foreach($data as $row){
		$row->returned_at=$row->returned_by. " at ".date("d-M-Y h:i:s",strtotime($row->returned_at)) ;
		$row->amount=number_format($row->price*$row->offer_qty,2,'.',',');
		$row->offer_qty=number_format($row->offer_qty,0,'.',',');
		$row->price=number_format($row->price,4,'.',',');
		$row->est_ship_date=date("d-M-Y",strtotime($row->est_ship_date));
		$row->quot_date=date("d-M-Y",strtotime($row->quot_date));
		$row->price_bfr_commission=$row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$row->profit_amount;
		$row->price_aft_commission=number_format(($row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$row->profit_amount+$row->commission_amount)/$row->costing_unit_id,4,'.',',');
		$commission_on_quoted_price_dzn=((($row->price*$row->costing_unit_id))*$row->commission_rate)/100;
		$commission_on_quoted_price_pcs=$commission_on_quoted_price_dzn/$row->costing_unit_id;
		$row->commission_on_quoted_price_dzn=number_format($commission_on_quoted_price_dzn,4,'.',',');
		$row->commission_on_quoted_price_pcs=number_format($commission_on_quoted_price_pcs,4,'.',',');

		$row->total_cost=$row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$commission_on_quoted_price_dzn;

		$cost_per_pcs=$row->total_cost/$row->costing_unit_id;
		$row->cost_per_pcs=number_format($cost_per_pcs,4,'.',',');

		$row->comments=($row->cost_per_pcs > $row->price)?"Less Than Cost":"";
		$row->cm=number_format(($row->price*$row->costing_unit_id)-($row->total_cost-$row->cm_amount),4,'.',',');

		$status="Preparing";
		if($row->submission_date){
		$status="Submited";
		}
		if($row->confirm_date){
		$status="Confirmed";
		}
		if($row->refused_date){
		$status="Refused";
		}
		if($row->cancel_date){
		$status="Cancel";
		}
		$row->status=$status;
		$totOffer+=$row->offer_qty;
		$totAmt+= $row->amount;
		array_push($datas,$row);
		}
		echo json_encode($datas);
    }

    public function confirmed (Request $request)
    {
    	$id=request('id',0);
    	$returned_coments=request('returned_coments',0);
    	$master=$this->mktcost->find($id);
		$user = \Auth::user();
		$confirmed_at=date('Y-m-d h:i:s');
        $confirm_date=date('Y-m-d');

        $master->confirmed_by=$user->id;
        $master->confirmed_at=$confirmed_at;
        $master->timestamps=false;
        $master->returned_by = NULL; 
		$master->returned_at =  NULL;
		$master->returned_coments = NULL;
        \DB::beginTransaction();
        try
        {
            $mktcost=$master->save();

            $mktcostquoteprice = $this->mktcostquoteprice->updateOrCreate(
                ['mkt_cost_id' => $master->id],
                ['confirm_date' => $confirm_date,]
            );

			$this->approvalcommenthistory->create([
			'model_id'=>$master->id,
			'model_type'=>'mkt_costs',
			'comments'=>$returned_coments,
			'comments_by'=>$master->confirmed_by,
			'comments_at'=>$master->confirmed_at
			]);
            
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        
		if($mktcostquoteprice){
		return response()->json(array('success' => true,  'message' => 'Confirmed Successfully'), 200);
		}
    }

    public function reportDataApproved() {
		$data = DB::table("mkt_costs")
		->select("mkt_costs.*",
		"buyers.code as buyer_name",
		"styles.style_ref",
		"styles.style_description",
		"styles.flie_src",
		"seasons.name as season_name",
		"productdepartments.department_name",
		"uoms.code as uom_code",
		'mkt_cost_quote_prices.quote_price as price',
		'mkt_cost_quote_prices.submission_date',
		'mkt_cost_quote_prices.confirm_date',
		'mkt_cost_quote_prices.refused_date',
		'mkt_cost_quote_prices.cancel_date',
		'mkt_cost_target_prices.target_price as  t_price',
		'users.name as team_member',
		'teams.name as team_name',
		'first_approval.name as ie',
		'second_approval.name as dmd',
		'final_approval.name as md',
		'createdby.name as created_by',
		DB::raw("(SELECT SUM(mkt_cost_fabrics.amount) FROM mkt_cost_fabrics
		WHERE mkt_cost_fabrics.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_fabrics.mkt_cost_id) as fab_amount"),

		DB::raw("(SELECT SUM(mkt_cost_yarns.amount) FROM mkt_cost_yarns
		WHERE mkt_cost_yarns.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_yarns.mkt_cost_id) as yarn_amount"),

		DB::raw("(SELECT SUM(mkt_cost_fabric_prods.amount) FROM mkt_cost_fabric_prods
		WHERE mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_fabric_prods.mkt_cost_id) as prod_amount"),

		DB::raw("(SELECT SUM(mkt_cost_trims.amount) FROM mkt_cost_trims
		WHERE mkt_cost_trims.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_trims.mkt_cost_id) as trim_amount"),

		DB::raw("(SELECT SUM(mkt_cost_embs.amount) FROM mkt_cost_embs
		WHERE mkt_cost_embs.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_embs.mkt_cost_id) as emb_amount"),

		DB::raw("(SELECT SUM(mkt_cost_cms.amount) FROM mkt_cost_cms
		WHERE mkt_cost_cms.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_cms.mkt_cost_id) as cm_amount"),

		DB::raw("(SELECT SUM(mkt_cost_others.amount) FROM mkt_cost_others
		WHERE mkt_cost_others.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_others.mkt_cost_id) as other_amount"),

		DB::raw("(SELECT SUM(mkt_cost_commercials.amount) FROM mkt_cost_commercials
		WHERE mkt_cost_commercials.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_commercials.mkt_cost_id) as commercial_amount"),

		DB::raw("(SELECT SUM(mkt_cost_profits.amount) FROM mkt_cost_profits
		WHERE mkt_cost_profits.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_profits.mkt_cost_id) as profit_amount"),

		DB::raw("(SELECT SUM(mkt_cost_commissions.amount) FROM mkt_cost_commissions
		WHERE mkt_cost_commissions.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_commissions.mkt_cost_id) as commission_amount"),

		DB::raw("(SELECT SUM(mkt_cost_commissions.rate) FROM mkt_cost_commissions
		WHERE mkt_cost_commissions.mkt_cost_id = mkt_costs.id
		GROUP BY mkt_cost_commissions.mkt_cost_id) as commission_rate")
		)
		->join('styles',function($join){
		$join->on('mkt_costs.style_id','=','styles.id');
		})
		->join('buyers',function($join){
		$join->on('styles.buyer_id','=','buyers.id');
		})
		->leftJoin('seasons',function($join){
		$join->on('styles.season_id','=','seasons.id');
		})
		->leftJoin('productdepartments',function($join){
		$join->on('styles.productdepartment_id','=','productdepartments.id');
		})
		->leftJoin('uoms',function($join){
		$join->on('styles.uom_id','=','uoms.id');
		})
		->leftJoin('teammembers',function($join){
		$join->on('teammembers.id','=','styles.teammember_id');
		})
		->leftJoin('users',function($join){
		$join->on('users.id','=','teammembers.user_id');
		})
		->leftJoin('teams',function($join){
			$join->on('teams.id','=','styles.team_id');
		})
		->leftJoin('mkt_cost_quote_prices',function($join){
		$join->on('mkt_costs.id','=','mkt_cost_quote_prices.mkt_cost_id');
		})
		->leftJoin('mkt_cost_target_prices',function($join){
		$join->on('mkt_costs.id','=','mkt_cost_target_prices.mkt_cost_id');
		})
		->leftJoin('users as first_approval',function($join){
			$join->on('first_approval.id','=','mkt_costs.first_approved_by');
		})
  		->leftJoin('users as second_approval',function($join){
			  $join->on('second_approval.id','=','mkt_costs.second_approved_by');
		})
  		->leftJoin('users as third_approval',function($join){
			  $join->on('third_approval.id','=','mkt_costs.third_approved_by');
		})
   		->leftJoin('users as final_approval',function($join){
			  $join->on('final_approval.id','=','mkt_costs.final_approved_by');
		})
		->leftJoin('users as createdby',function($join){
			$join->on('createdby.id','=','mkt_costs.created_by');
		})
		->when(request('buyer_id'), function ($q) {
			return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('team_id'), function ($q) {
			return $q->where('styles.team_id', '=', request('team_id', 0));
		})
		->when(request('teammember_id'), function ($q) {
			return $q->where('styles.teammember_id', '=', request('teammember_id', 0));
		})
		->when(request('style_ref'), function ($q) {
			return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
		})
		->when(request('date_from'), function ($q) {
			return $q->where('mkt_costs.est_ship_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
			return $q->where('mkt_costs.est_ship_date', '<=',request('date_to', 0));
		})
		->when(request('confirm_from'), function ($q) {
			return $q->where('mkt_cost_quote_prices.confirm_date', '>=',request('confirm_from', 0));
		})
		->when(request('confirm_to'), function ($q) {
			return $q->where('mkt_cost_quote_prices.confirm_date', '<=',request('confirm_to', 0));
		})
		->when(request('costing_from'), function ($q) {
			return $q->where('mkt_costs.quot_date', '>=',request('costing_from', 0));
		})
		->when(request('costing_to'), function ($q) {
			return $q->where('mkt_costs.quot_date', '<=',request('costing_to', 0));
		})
		->when(request('first_approved_from'), function ($q) {
			return $q->whereDate('mkt_costs.first_approved_at', '>=',request('first_approved_from', 0));
		})
		->when(request('first_approved_to'), function ($q) {
			return $q->whereDate('mkt_costs.first_approved_at', '<=',request('first_approved_to', 0));
		})
		->when(request('second_approved_from'), function ($q) {
			return $q->whereDate('mkt_costs.second_approved_at', '>=',request('second_approved_from', 0));
		})
		->when(request('second_approved_to'), function ($q) {
			return $q->whereDate('mkt_costs.second_approved_at', '<=',request('second_approved_to', 0));
		})
		->when(request('third_approved_from'), function ($q) {
			return $q->whereDate('mkt_costs.third_approved_at', '>=',request('third_approved_from', 0));
		})
		->when(request('third_approved_to'), function ($q) {
			return $q->whereDate('mkt_costs.third_approved_at', '<=',request('third_approved_to', 0));
		})
		//->whereDate('mkt_costs.quot_date','>','2020-12-31')
		->whereNotNull('mkt_cost_quote_prices.qprice_date')
		->whereNotNull('mkt_costs.confirmed_at')
		->whereNotNull('mkt_cost_quote_prices.confirm_date')
		//->whereNotNull('mkt_costs.first_approved_at')
		->orderBy('mkt_costs.third_approved_at','desc')
		->orderBy('mkt_costs.second_approved_at','desc')
		->orderBy('mkt_costs.first_approved_at','desc')
		->get();
		$datas=array();
		$totOffer=0;
		$totAmt=0;
		foreach($data as $row){
		if($row->ie){
		$row->ie= $row->ie." at ". date("d-M-Y h:i:s ",strtotime($row->first_approved_at)) ;	
		}
		else{
			$row->ie='--';
		}
		if($row->dmd){
			$row->dmd= $row->dmd." at ". date("d-M-Y h:i:s ",strtotime($row->second_approved_at)) ;
		}
		else{
			$row->dmd='--';
		}
		if($row->md){
			$row->md= $row->md." at ". date("d-M-Y h:i:s ",strtotime($row->third_approved_at)) ;
		}
		else{
			$row->md='--';
		}
		/*if($row->md){
		$row->md= $row->md." at ". date("d-M-Y h:i:s ",strtotime($row->final_approved_at)) ;	
		}
		else{
			$row->md='--';
		}*/
		
		
		$row->cm=number_format(($row->offer_qty/$row->costing_unit_id)*$row->cm_amount,0,'.',',');
		$row->amount=number_format($row->price*$row->offer_qty,0,'.',',');
		$row->offer_qty=number_format($row->offer_qty,0,'.',',');
		$row->price=number_format($row->price,4,'.',',');
		$row->est_ship_date=date("d-M-Y",strtotime($row->est_ship_date));
		$row->quot_date=date("d-M-Y",strtotime($row->quot_date));
		$row->price_bfr_commission=$row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$row->profit_amount;
		$row->price_aft_commission=number_format(($row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$row->profit_amount+$row->commission_amount)/$row->costing_unit_id,4,'.',',');
		$commission_on_quoted_price_dzn=((($row->price*$row->costing_unit_id))*$row->commission_rate)/100;
		$commission_on_quoted_price_pcs=$commission_on_quoted_price_dzn/$row->costing_unit_id;
		$row->commission_on_quoted_price_dzn=number_format($commission_on_quoted_price_dzn,4,'.',',');
		$row->commission_on_quoted_price_pcs=number_format($commission_on_quoted_price_pcs,4,'.',',');

		$row->total_cost=$row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$commission_on_quoted_price_dzn;

		$cost_per_pcs=$row->total_cost/$row->costing_unit_id;
		$row->cost_per_pcs=number_format($cost_per_pcs,4,'.',',');

		$row->comments=($row->cost_per_pcs > $row->price)?"Less Than Cost":"";
		
		//$row->cm=number_format(($row->price*$row->costing_unit_id)-($row->total_cost-$row->cm_amount),4,'.',',');

		$status="Preparing";
		if($row->submission_date){
		$status="Submited";
		}
		if($row->confirm_date){
		$status="Confirmed";
		}
		if($row->refused_date){
		$status="Refused";
		}
		if($row->cancel_date){
		$status="Cancel";
		}
		$row->status=$status;
		$totOffer+=$row->offer_qty;
		$totAmt+= $row->amount;
		array_push($datas,$row);
		}
		echo json_encode($datas);
    }

}
