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
class QuotationStatementController extends Controller
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
      return Template::loadView('Report.QuotationStatement',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember]);
    }
	public function reportData() {
			$data = DB::table("mkt_costs")
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
			/*->join('style_file_uploads',function($join){
			$join->on('style_file_uploads.style_id','=','styles.id');
			})*/
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
			$dd=array('total'=>1,'rows'=>$datas,'footer'=>array(0=>array('ID'=>'','buyer_name'=>'','style_ref'=>'','style_description'=>'','season_name'=>'','department_name'=>'','offer_qty'=>'','uom_code'=>'','price'=>'','amount'=>'')));
			echo json_encode($dd);
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
}
