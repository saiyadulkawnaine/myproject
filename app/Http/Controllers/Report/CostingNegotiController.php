<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
class CostingNegotiController extends Controller
{
	private $mktcost;
	private $company;
	private $buyer;
	private $team;
	private $teammember;
	private $style;
	public function __construct(MktCostRepository $mktcost,CompanyRepository $company,BuyerRepository $buyer,TeamRepository $team,TeammemberRepository $teammember,StyleRepository $style)
    {
		$this->mktcost=$mktcost;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->team = $team;
        $this->teammember = $teammember;
        $this->style = $style;
		$this->middleware('auth');
		
		$this->middleware('permission:view.quotestatementreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
		$teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function($join)  {
		$join->on('teammembers.user_id', '=', 'users.id');
		})
		->get([
			'teammembers.id',
			'users.name',
		]),'name','id'),'-Select-',0);
      return Template::loadView('Report.CostingNegoti',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember]);
    }
	public function reportData() {
		     $buyer_id=request('buyer_id',0);
		     $team_id=request('team_id',0);
		     $teammember_id=request('teammember_id',0);
		     $style_ref=request('style_ref',0);
		     $date_from=request('date_from',0);
		     $date_to=request('date_to',0);
		     $confirm_from=request('confirm_from',0);
		     $confirm_to=request('confirm_to',0);
		     $costing_from=request('costing_from',0);
		     $costing_to=request('costing_to',0);
		     $submission_from=request('submission_from',0);
		     $submission_to=request('submission_to',0);
		     $refused_from=request('refused_from',0);
		     $refused_to=request('refused_to',0);
		     $cancel_from=request('cancel_from',0);
		     $cancel_to=request('cancel_to',0);

		     $buyer='';
		     if($buyer_id){
		     	$buyer=" and styles.buyer_id= $buyer_id ";
		     }

		     $team='';
		     if($team_id){
		     	$team=" and styles.team_id= $team_id ";
		     }

		     $teammember='';
		     if($teammember_id){
		     	$teammember=" and styles.teammember_id= $teammember_id ";
		     }

		     

		     $style='';
		     if($style_ref){
		     	$style=" and styles.style_ref='". $style_ref ."'";
		     }

		     $est_ship_date='';
		     if($date_from && $date_to){
		     	$est_ship_date=" and mkt_costs.est_ship_date between '". $date_from ."' and '". $date_to ."'";
		     }

		     $confirm_date='';
		     if($confirm_from && $confirm_to){
		     	$confirm_date=" and mkt_cost_quote_prices.confirm_date between '". $confirm_from ."' and '". $confirm_to ."'";
		     }

		     $submission_date='';
		     if($submission_from && $submission_to){
		     	$submission_date=" and mkt_cost_quote_prices.submission_date between '". $submission_from ."' and '". $submission_to ."'";
		     }

		     $cancel_date='';
		     if($cancel_from && $cancel_to){
		     	$cancel_date=" and mkt_cost_quote_prices.cancel_date between '". $cancel_from ."' and '". $cancel_to ."'";
		     }

			 $refused_date='';
		     if($refused_from && $refused_to){
		     	$refused_date=" and mkt_cost_quote_prices.refused_date between '". $refused_from ."' and '". $refused_to ."'";
		     }

		     $quot_date='';
		     if($costing_from && $costing_to){
		     	$quot_date=" and mkt_costs.quot_date between '". $costing_from ."' and '". $costing_to ."'";
		     }


		     



			/*$data = DB::table("mkt_costs")
			->select("mkt_costs.*",
			"buyers.name as buyer_name",
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
			->join('seasons',function($join){
			$join->on('styles.season_id','=','seasons.id');
			})
			->join('productdepartments',function($join){
			$join->on('styles.productdepartment_id','=','productdepartments.id');
			})
			->join('uoms',function($join){
			$join->on('styles.uom_id','=','uoms.id');
			})
			->join('teammembers',function($join){
			$join->on('teammembers.id','=','styles.teammember_id');
			})
			->join('users',function($join){
			$join->on('users.id','=','teammembers.user_id');
			})
			->leftJoin('mkt_cost_quote_prices',function($join){
			$join->on('mkt_costs.id','=','mkt_cost_quote_prices.mkt_cost_id');
			})
			->leftJoin('mkt_cost_target_prices',function($join){
			$join->on('mkt_costs.id','=','mkt_cost_target_prices.mkt_cost_id');
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
			->orderBy('mkt_costs.id','desc')
			->get();*/

			$data = collect(
			\DB::select("
				select 
				m.id, 
				m.offer_qty,
				m.est_ship_date,
				m.quot_date,
				m.costing_unit_id,
				m.company_name, 
				m.buyer_name, 
				m.style_id, 
				m.style_ref, 
				m.style_description, 
				m.flie_src, 
				m.season_name, 
				m.department_name, 
				m.uom_code, 
				m.price, 
				m.submission_date, 
				m.confirm_date, 
				m.refused_date, 
				m.cancel_date, 
				m.t_price, 
				m.updated_at,
				m.team_member, 
				m.user_id,
				m.team_leader, 
				m.fab_amount, 
				m.fab_cons, 
				m.yarn_amount, 
				m.prod_amount, 
				m.knit_amount, 
				m.yd_amount, 
				m.fd_amount, 
				m.aop_amount, 
				m.burnout_amount, 
				m.finishing_amount, 
				m.fwash_amount, 
				m.trim_amount, 
				m.emb_amount, 
				m.sp_amount, 
				m.embr_amount, 
				m.spembr_amount, 
				m.gd_amount, 
				m.gw_amount, 
				m.cm_amount, 
				m.other_amount, 
				m.other_amount_frei_free, 
				m.frei_amount, 
				m.commercial_amount, 
				m.profit_amount, 
				m.commission_amount, 
				m.commission_rate 
				from (
				select 
				mkt_costs.id, 
				mkt_costs.offer_qty,
				mkt_costs.est_ship_date,
				mkt_costs.quot_date,
				mkt_costs.costing_unit_id,
				companies.code as company_name,
				buyers.name as buyer_name, 
				styles.id as style_id,
				styles.style_ref, 
				styles.style_description, 
				styles.flie_src, 
				seasons.name as season_name, 
				productdepartments.department_name, 
				uoms.code as uom_code, 
				mkt_cost_quote_prices.quote_price as price, 
				mkt_cost_quote_prices.submission_date, 
				mkt_cost_quote_prices.confirm_date, 
				mkt_cost_quote_prices.refused_date, 
				mkt_cost_quote_prices.cancel_date, 
				mkt_cost_target_prices.target_price as t_price, 
				mkt_cost_target_prices.updated_at, 
				users.name as team_member,
				users.id as user_id,
				teamleadernames.name as team_leader,

				(
				select  sum(mkt_cost_fabric_cons.cons)  from mkt_cost_fabric_cons 
				left join mkt_cost_fabrics on mkt_cost_fabrics.id = mkt_cost_fabric_cons.mkt_cost_fabric_id 
				where mkt_cost_fabrics.mkt_cost_id = mkt_costs.id
				group by mkt_cost_fabrics.mkt_cost_id
				) as fab_cons,

				(select sum(mkt_cost_fabrics.amount) from mkt_cost_fabrics
				where mkt_cost_fabrics.mkt_cost_id = mkt_costs.id
				group by mkt_cost_fabrics.mkt_cost_id) as fab_amount, 

				(select sum(mkt_cost_yarns.amount) from mkt_cost_yarns
				where mkt_cost_yarns.mkt_cost_id = mkt_costs.id
				group by mkt_cost_yarns.mkt_cost_id) as yarn_amount,

				(select sum(mkt_cost_fabric_prods.amount) from mkt_cost_fabric_prods
				where mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				group by mkt_cost_fabric_prods.mkt_cost_id) as prod_amount, 

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =5
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as yd_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =10
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as knit_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =20
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as fd_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =25
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as aop_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =28
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as burnout_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =30
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as finishing_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =35
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as fwash_amount,


				(select sum(mkt_cost_trims.amount) from mkt_cost_trims
				where mkt_cost_trims.mkt_cost_id = mkt_costs.id
				group by mkt_cost_trims.mkt_cost_id) as trim_amount, 



				(select sum(mkt_cost_embs.amount) from mkt_cost_embs
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				group by mkt_cost_embs.mkt_cost_id) as emb_amount,

				(
				select sum(mkt_cost_embs.amount) 
				from mkt_cost_embs
				left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =45
				group by mkt_cost_embs.mkt_cost_id
				) as sp_amount,

				(
				select sum(mkt_cost_embs.amount) 
				from mkt_cost_embs
				left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =50
				group by mkt_cost_embs.mkt_cost_id
				) as embr_amount,

				(
				select sum(mkt_cost_embs.amount) 
				from mkt_cost_embs
				left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =51
				group by mkt_cost_embs.mkt_cost_id
				) as spembr_amount, 

				(
				select sum(mkt_cost_embs.amount) 
				from mkt_cost_embs
				left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =58
				group by mkt_cost_embs.mkt_cost_id
				) as gd_amount, 

				(
				select sum(mkt_cost_embs.amount) 
				from mkt_cost_embs
				left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =60
				group by mkt_cost_embs.mkt_cost_id
				) as gw_amount, 


				(select sum(mkt_cost_cms.amount) from mkt_cost_cms
				where mkt_cost_cms.mkt_cost_id = mkt_costs.id
				group by mkt_cost_cms.mkt_cost_id) as cm_amount, 

				(select sum(mkt_cost_others.amount) from mkt_cost_others
				where mkt_cost_others.mkt_cost_id = mkt_costs.id
				group by mkt_cost_others.mkt_cost_id) as other_amount,

				(
				select sum(mkt_cost_others.amount) 
				from mkt_cost_others
				where mkt_cost_others.mkt_cost_id = mkt_costs.id
				and mkt_cost_others.cost_head_id !=15
				group by mkt_cost_others.mkt_cost_id
				) as other_amount_frei_free, 

				(
				select sum(mkt_cost_others.amount) 
				from mkt_cost_others
				where mkt_cost_others.mkt_cost_id = mkt_costs.id
				and mkt_cost_others.cost_head_id =15
				group by mkt_cost_others.mkt_cost_id
				) as frei_amount,

				(select sum(mkt_cost_commercials.amount) from mkt_cost_commercials
				where mkt_cost_commercials.mkt_cost_id = mkt_costs.id
				group by mkt_cost_commercials.mkt_cost_id) as commercial_amount, 

				(select sum(mkt_cost_profits.amount) from mkt_cost_profits
				where mkt_cost_profits.mkt_cost_id = mkt_costs.id
				group by mkt_cost_profits.mkt_cost_id) as profit_amount, 

				(select sum(mkt_cost_commissions.amount) from mkt_cost_commissions
				where mkt_cost_commissions.mkt_cost_id = mkt_costs.id
				group by mkt_cost_commissions.mkt_cost_id) as commission_amount, 

				(select sum(mkt_cost_commissions.rate) from mkt_cost_commissions
				where mkt_cost_commissions.mkt_cost_id = mkt_costs.id
				group by mkt_cost_commissions.mkt_cost_id) as commission_rate 

				from 
				mkt_costs 
				inner join styles on mkt_costs.style_id = styles.id 
				inner join buyers on styles.buyer_id = buyers.id 
				left join seasons on styles.season_id = seasons.id 
				left join productdepartments on styles.productdepartment_id = productdepartments.id 
				left join uoms on styles.uom_id = uoms.id

				left join teammembers on teammembers.id = styles.factory_merchant_id 
				left join users on users.id = teammembers.user_id 

				left join teammembers teamleaders on teamleaders.id = styles.teammember_id 
				left join users teamleadernames on teamleadernames.id = teamleaders.user_id

				left join mkt_cost_quote_prices on mkt_costs.id = mkt_cost_quote_prices.mkt_cost_id 
				left join mkt_cost_target_prices on mkt_costs.id = mkt_cost_target_prices.mkt_cost_id
				left join companies on companies.id = mkt_costs.company_id 

				where 1=1 $buyer $team $teammember $style $est_ship_date $confirm_date $quot_date $submission_date $refused_date $cancel_date
				--and mkt_cost_quote_prices.submission_date is not null

				union all

				select 
				mkt_costs.id, 
				mkt_costs.offer_qty, 
				mkt_costs.est_ship_date, 
				mkt_costs.quot_date, 
				mkt_costs.costing_unit_id,
				companies.code as company_name, 
				buyers.name as buyer_name, 
				styles.id as style_id, 
				styles.style_ref, 
				styles.style_description, 
				styles.flie_src, 
				seasons.name as season_name, 
				productdepartments.department_name, 
				uoms.code as uom_code, 
				mkt_cost_quote_price_audits.quote_price as price, 
				mkt_cost_quote_price_audits.submission_date, 
				mkt_cost_quote_price_audits.confirm_date, 
				mkt_cost_quote_price_audits.refused_date, 
				mkt_cost_quote_price_audits.cancel_date,
				mkt_cost_target_prices.target_price as t_price, 
				mkt_cost_quote_price_audits.updated_at,
				users.name as team_member,
				users.id as user_id,
				teamleadernames.name as team_leader,

				(
				select sum(mkt_cost_fabric_cons.cons)  from mkt_cost_fabric_cons 
				left join mkt_cost_fabrics on mkt_cost_fabrics.id = mkt_cost_fabric_cons.mkt_cost_fabric_id 
				where mkt_cost_fabrics.mkt_cost_id = mkt_costs.id
				group by mkt_cost_fabrics.mkt_cost_id
				) as fab_cons, 

				(select sum(mkt_cost_fabrics.amount) from mkt_cost_fabrics
				where mkt_cost_fabrics.mkt_cost_id = mkt_costs.id
				group by mkt_cost_fabrics.mkt_cost_id) as fab_amount, 

				(select sum(mkt_cost_yarns.amount) from mkt_cost_yarns
				where mkt_cost_yarns.mkt_cost_id = mkt_costs.id
				group by mkt_cost_yarns.mkt_cost_id) as yarn_amount, 

				(select sum(mkt_cost_fabric_prods.amount) from mkt_cost_fabric_prods
				where mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				group by mkt_cost_fabric_prods.mkt_cost_id) as prod_amount, 

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =5
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as yd_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =10
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as knit_amount, 

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =20
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as fd_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =25
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as aop_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =28
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as burnout_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =30
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as finishing_amount,

				(
				select sum(mkt_cost_fabric_prods.amount) 
				from mkt_cost_fabric_prods
				left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id
				where 
				mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =35
				group by mkt_cost_fabric_prods.mkt_cost_id
				) as fwash_amount,

				(select sum(mkt_cost_trims.amount) from mkt_cost_trims
				where mkt_cost_trims.mkt_cost_id = mkt_costs.id
				group by mkt_cost_trims.mkt_cost_id) as trim_amount, 

				(select sum(mkt_cost_embs.amount) from mkt_cost_embs
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				group by mkt_cost_embs.mkt_cost_id) as emb_amount,

				(
				select sum(mkt_cost_embs.amount) 
				from mkt_cost_embs
				left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =45
				group by mkt_cost_embs.mkt_cost_id
				) as sp_amount, 

				(
				select sum(mkt_cost_embs.amount) 
				from mkt_cost_embs
				left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =50
				group by mkt_cost_embs.mkt_cost_id
				) as embr_amount, 

				(
				select sum(mkt_cost_embs.amount) 
				from mkt_cost_embs
				left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =51
				group by mkt_cost_embs.mkt_cost_id
				) as spembr_amount, 

				(
				select sum(mkt_cost_embs.amount) 
				from mkt_cost_embs
				left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =58
				group by mkt_cost_embs.mkt_cost_id
				) as gd_amount, 

				(
				select sum(mkt_cost_embs.amount) 
				from mkt_cost_embs
				left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where mkt_cost_embs.mkt_cost_id = mkt_costs.id
				and production_processes.production_area_id =60
				group by mkt_cost_embs.mkt_cost_id
				) as gw_amount,

				(select sum(mkt_cost_cms.amount) from mkt_cost_cms
				where mkt_cost_cms.mkt_cost_id = mkt_costs.id
				group by mkt_cost_cms.mkt_cost_id) as cm_amount, 

				(select sum(mkt_cost_others.amount) from mkt_cost_others
				where mkt_cost_others.mkt_cost_id = mkt_costs.id
				group by mkt_cost_others.mkt_cost_id) as other_amount,

				(
				select sum(mkt_cost_others.amount) 
				from mkt_cost_others
				where mkt_cost_others.mkt_cost_id = mkt_costs.id
				and mkt_cost_others.cost_head_id !=15
				group by mkt_cost_others.mkt_cost_id
				) as other_amount_frei_free, 

				(
				select sum(mkt_cost_others.amount) 
				from mkt_cost_others
				where mkt_cost_others.mkt_cost_id = mkt_costs.id
				and mkt_cost_others.cost_head_id =15
				group by mkt_cost_others.mkt_cost_id
				) as frei_amount,

				(select sum(mkt_cost_commercials.amount) from mkt_cost_commercials
				where mkt_cost_commercials.mkt_cost_id = mkt_costs.id
				group by mkt_cost_commercials.mkt_cost_id) as commercial_amount, 

				(select sum(mkt_cost_profits.amount) from mkt_cost_profits
				where mkt_cost_profits.mkt_cost_id = mkt_costs.id
				group by mkt_cost_profits.mkt_cost_id) as profit_amount, 

				(select sum(mkt_cost_commissions.amount) from mkt_cost_commissions
				where mkt_cost_commissions.mkt_cost_id = mkt_costs.id
				group by mkt_cost_commissions.mkt_cost_id) as commission_amount,

				(select sum(mkt_cost_commissions.rate) from mkt_cost_commissions
				where mkt_cost_commissions.mkt_cost_id = mkt_costs.id
				group by mkt_cost_commissions.mkt_cost_id) as commission_rate 

				from 
				mkt_costs 
				inner join styles on mkt_costs.style_id = styles.id 
				inner join buyers on styles.buyer_id = buyers.id 
				left join seasons on styles.season_id = seasons.id 
				left join productdepartments on styles.productdepartment_id = productdepartments.id 
				left join uoms on styles.uom_id = uoms.id 

				left join teammembers on teammembers.id = styles.factory_merchant_id 
				left join users on users.id = teammembers.user_id 

				left join teammembers teamleaders on teamleaders.id = styles.teammember_id 
				left join users teamleadernames on teamleadernames.id = teamleaders.user_id

				left join mkt_cost_quote_prices on mkt_costs.id = mkt_cost_quote_prices.mkt_cost_id 
				left join mkt_cost_target_prices on mkt_costs.id = mkt_cost_target_prices.mkt_cost_id 
				left join mkt_cost_quote_price_audits on mkt_costs.id = mkt_cost_quote_price_audits.mkt_cost_id
				left join companies on companies.id = mkt_costs.company_id
				where 
				1=1 $buyer $team $teammember $style $est_ship_date $confirm_date $quot_date $submission_date $refused_date $cancel_date
				and mkt_cost_quote_price_audits.submission_date is not null
				) m order by m.id, m.updated_at desc
			")
			);



			$id='';
			$datas=array();
			$totOffer=0;
			$totAmt=0;
			foreach($data as $row){
				$row->amount=number_format($row->price*$row->offer_qty,2,'.',',');
				$row->offer_qty=number_format($row->offer_qty,0,'.',',');
				$row->price=number_format($row->price,4,'.',',');
				$row->t_price=number_format($row->t_price,4,'.',',');
				$row->est_ship_date=$row->est_ship_date?date("d-M-Y",strtotime($row->est_ship_date)):'';
				$row->quot_date=$row->quot_date?date("d-M-Y",strtotime($row->quot_date)):'';
				$row->submission_date=$row->submission_date?date("d-M-Y",strtotime($row->submission_date)):'';
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
				$row->knit_amount=$row->knit_amount;

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

				if($row->id==$id){
					$row->team_member='';
					$row->team_leader='';
					$row->buyer_name='';
					$row->company_name='';
					$row->style_ref='';
					$row->style_description='';
					$row->season_name='';
					$row->department_name='';
					$row->flie_src='';
					$row->uom_code='';
					$row->est_ship_date='';
					$row->quot_date='';
					$row->cm='';
					$row->fab_cons='';
					$row->fab_amount='';
					$row->yarn_amount='';
					$row->prod_amount='';
					$row->yd_amount='';
					$row->knit_amount='';
					$row->fd_amount='';
					$row->aop_amount='';
					$row->burnout_amount='';
					$row->fwash_amount='';
					$row->trim_amount='';
					$row->emb_amount='';
					$row->sp_amount='';
					$row->embr_amount='';
					$row->spembr_amount='';
					$row->gd_amount='';
					$row->gw_amount='';
					$row->other_amount_frei_free='';
					$row->frei_amount='';
					$row->cm_amount='';
					$row->other_amount='';
					$row->commercial_amount='';
					$row->commission_on_quoted_price_dzn='';
					$row->total_cost='';
					$row->cost_per_pcs='';
					$row->offer_qty='';
					$row->t_price='';
				}
				array_push($datas,$row);
				$id=$row->id;
			}
			echo json_encode($datas);
	}
	
	public function getMktCostFileSrc(){
		$filesrc=$this->style
		->leftJoin('style_file_uploads',function($join){
		   $join->on('style_file_uploads.style_id','=','styles.id');
		 })
		 
		->where([['style_id','=',request('style_id',0)]])
		->get([
			'styles.id as style_id',
			'styles.style_ref',
			'style_file_uploads.*'
		]);
		echo json_encode($filesrc);
	}

	public function getMktCostQuotePrice()
	{
		$mkt_cost_id=request('mkt_cost_id',0);

		$price = collect(
        \DB::select("
				select 
				m.qprice_date,
				m.quote_price,
				m.submission_date,
				m.updated_at
				from
				(
				select 
				mkt_cost_quote_prices.qprice_date,
				mkt_cost_quote_prices.quote_price,
				mkt_cost_quote_prices.submission_date,
				mkt_cost_quote_prices.updated_at
				from 
				mkt_cost_quote_prices
				where mkt_cost_quote_prices.mkt_cost_id=?
				and mkt_cost_quote_prices.deleted_at is null
				--and mkt_cost_quote_prices.submission_date is not null
				union
				select 
				mkt_cost_quote_price_audits.qprice_date,
				mkt_cost_quote_price_audits.quote_price,
				mkt_cost_quote_price_audits.submission_date,
				mkt_cost_quote_price_audits.updated_at
				from 
				mkt_cost_quote_price_audits
				where mkt_cost_quote_price_audits.mkt_cost_id=?
				and mkt_cost_quote_price_audits.deleted_at is null
				and mkt_cost_quote_price_audits.submission_date is not null
				) m  order by m.updated_at desc
            ",[$mkt_cost_id,$mkt_cost_id])
        )
        ->map(function($price){
        	$price->qprice_date=date('d-M-Y',strtotime($price->qprice_date));
        	//$price->submission_date=''
        	$price->submission_date=$price->submission_date?date('d-M-Y',strtotime($price->submission_date)):'';
        	return $price;
        })
        ;
        echo json_encode($price);
	}

	public function getTeamMemberDlm(){
		$dlmerchant = $this->teammember
		->join('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
		})	
		->leftJoin('employee_h_rs', function($join)  {
			$join->on('users.id', '=', 'employee_h_rs.user_id');
		})
		->where([['employee_h_rs.user_id','=',request('user_id',0)]])
		->get([
			//'users.id as user_id',
			/* 'users.name as team_member', */
			'employee_h_rs.name',
			'employee_h_rs.date_of_join',
			'employee_h_rs.last_education',
			'employee_h_rs.address',
			'employee_h_rs.email',
			'employee_h_rs.experience',
			'employee_h_rs.contact'
		])
		->map(function($dlmerchant){
			$dlmerchant->date_of_join=$dlmerchant->date_of_join?date('d-M-Y',strtotime($dlmerchant->date_of_join)):'';
			return $dlmerchant;
		});
		echo json_encode($dlmerchant);
	}
	
}
