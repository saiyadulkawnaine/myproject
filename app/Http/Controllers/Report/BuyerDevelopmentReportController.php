<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentRepository;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentOrderQtyRepository;
use App\Repositories\Contracts\Util\TeamRepository;
class BuyerDevelopmentReportController extends Controller
{
	
	private $buyerdevelopment;
	private $buyerdevelopmentorderqty;
	private $buyer;
	private $team;
	
	public function __construct(
		BuyerDevelopmentRepository $buyerdevelopment,
		BuyerDevelopmentOrderQtyRepository $buyerdevelopmentorderqty,
		BuyerRepository $buyer,
		TeamRepository $team
		
	)
    {
		$this->buyerdevelopment    = $buyerdevelopment;
		$this->buyerdevelopmentorderqty    = $buyerdevelopmentorderqty;
		$this->buyer    = $buyer;
		$this->team    = $team;
		$this->middleware('auth');
		$this->middleware('permission:view.orderprogressreports',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'',0);
        $buyerdlvstatus=array_prepend(config('bprs.buyerdlvstatus'),'-Select-','');  
        $team=array_prepend(array_pluck($this->team->where([['team_type_id','=',1]])->where([['row_status','=',1]])->get(),'name','id'),'-Select-',0);
 
        return Template::loadView('Report.BuyerDevelopmentReport',['buyer'=>$buyer,'buyerdlvstatus'=>$buyerdlvstatus,'team'=>$team]);
    }

    private function getData()
    {
		$buyer_id=request('buyer_id', 0);
		$team_id=request('team_id', 0);
		$status_id=request('status_id',0);
		$date_from=request('date_from',0);
    	$date_to=request('date_to',0);

		$meetingtype=array_prepend(config('bprs.meetingtype'),'-Select-','');   
        $credittype=array_prepend(config('bprs.credittype'),'-Select-','');   
        $buyerdlvstatus=array_prepend(config('bprs.buyerdlvstatus'),'-Select-','');   
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');	
        $payterm=array_prepend(config('bprs.payterm'),'-Select-',''); 


		$buyer=null;
		$team=null;
		$status_id=null;
		$date_from=null;
		$date_to=null;
		if($buyer_id){
			$buyer=" and buyer_developments.buyer_id=$buyer_id ";
		}

		if($team_id){
			$team=" and buyer_developments.team_id=$team_id ";
		}
		
		if($status_id){
			$status_id=" and buyer_developments.status_id = $status_id ";
		}
		if($date_from){
			$datefrom=" and buyer_development_order_qties.est_ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and buyer_development_order_qties.est_ship_date<='".$date_to."' ";
		}
		
		$rows=$this->buyerdevelopment
		->selectRaw(
	    'buyer_developments.id,
		buyer_developments.product_type_id,
		buyer_developments.end_user_market,
		buyer_developments.existing_supplier,
		buyer_developments.credit_rating,
		buyer_developments.credit_type_id,
		buyer_developments.pay_term_id,
		buyer_developments.penalty_clause,
		buyer_developments.compliance_req,
		buyer_developments.remarks,
		buyer_developments.status_id,
		buyers.name as buyer_name,
		teams.name as team_name,
		events.meeting_summary,
		events.next_action_plan,
		buyinghouse.buyer_house_id
		'
		)
		->leftJoin('buyers', function($join)  {
		$join->on('buyer_developments.buyer_id', '=', 'buyers.id');
		})
		->leftJoin('teams', function($join)  {
		$join->on('buyer_developments.team_id', '=', 'teams.id');
		})

		->leftJoin(\DB::raw("(SELECT
		buyer_development_intms.buyer_development_id, 
		max(buyer_development_intms.id) as id 
		from buyer_development_intms 
		join buyers on buyers.id = buyer_development_intms.buyer_id
		group by
		buyer_development_intms.buyer_development_id) buyinghouseid"), "buyinghouseid.buyer_development_id", "=", "buyer_developments.id")

		->leftJoin(\DB::raw("(SELECT
		buyer_development_intms.id, 
		buyers.name as buyer_house_id
		from buyer_development_intms 
		join buyers on buyers.id = buyer_development_intms.buyer_id
		group by
		buyer_development_intms.id,buyers.name) buyinghouse"), "buyinghouse.id", "=", "buyinghouseid.id")

		->leftJoin(\DB::raw("(SELECT
		buyer_development_events.buyer_development_id, 
		max(buyer_development_events.id) as id
		from buyer_development_events 
		group by
		buyer_development_events.buyer_development_id) eventsid"), "eventsid.buyer_development_id", "=", "buyer_developments.id")

		->leftJoin(\DB::raw("(SELECT
		buyer_development_events.id,
		buyer_development_events.meeting_summary,
		buyer_development_events.next_action_plan
		from buyer_development_events 
		) events"), "events.id", "=", "eventsid.id")

		->when(request('buyer_id'), function ($q) {
		return $q->where('buyer_developments.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('team_id'), function ($q) {
		return $q->where('buyer_developments.team_id', '=', request('team_id', 0));
		})
		
		->when(request('status_id'), function ($q) {
		return $q->where('buyer_developments.status_id', '=',request('status_id', 0));
		})
		/*->groupBy([
		
		])*/
		->orderby('buyer_developments.id')
		->get()
		->map(function($rows) use($fabricnature,$payterm,$buyerdlvstatus,$credittype){
		$rows->product_type_id=$fabricnature[$rows->product_type_id];
		$rows->credit_type_id=$credittype[$rows->credit_type_id];
		$rows->pay_term_id=$payterm[$rows->pay_term_id];
		$rows->status_id=$buyerdlvstatus[$rows->status_id];
		$rows->doc_upload='View';
		return $rows;
		});
		return $rows;
    }

    


	public function reportData() {
		return response()->json($this->getData());
	}

	public function getEvents(){

        $meetingtype=array_prepend(config('bprs.meetingtype'),'-Select-','');   
        $rows=$this->buyerdevelopment
        ->leftJoin('buyer_development_events', function($join)  {
		$join->on('buyer_developments.id', '=', 'buyer_development_events.buyer_development_id');
		})
        ->where([['buyer_development_events.buyer_development_id','=',request('id',0)]])
        ->orderBy('buyer_development_events.id','desc')
        ->get([
            'buyer_development_events.*'
        ])
        ->map(function($rows) use($meetingtype){
            $rows->meeting_type_id=$meetingtype[$rows->meeting_type_id];
            $rows->meeting_date=date('d-M-Y',strtotime($rows->meeting_date));
        	return $rows;
        });
        echo json_encode($rows);   
	}

	public function getIntms(){

        $rows=$this->buyerdevelopment
        ->join('buyer_development_intms', function($join)  {
		$join->on('buyer_developments.id', '=', 'buyer_development_intms.buyer_development_id');
		})
		->join('buyers', function($join)  {
		$join->on('buyers.id', '=', 'buyer_development_intms.buyer_id');
		})
		->leftJoin('teams', function($join)  {
		$join->on('buyers.team_id', '=', 'teams.id');
		})
		->leftJoin('companies', function($join)  {
		$join->on('buyers.company_id', '=', 'companies.id');
		})
		->leftJoin('suppliers', function($join)  {
		$join->on('buyers.supplier_id', '=', 'suppliers.id');
		})
		->leftJoin('buyers as buyinghouse', function($join)  {
		$join->on('buyers.buying_agent_id', '=', 'buyinghouse.id');
		})
		->leftJoin('teammembers', function($join)  {
		$join->on('teammembers.id', '=', 'buyers.teammember_id');
		})
		->leftJoin('users', function($join)  {
		$join->on('users.id', '=', 'teammembers.user_id');
		})
        ->where([['buyer_development_intms.buyer_development_id','=',request('id',0)]])
        ->orderBy('buyer_development_intms.id','desc')
        ->get([
        	'buyer_development_intms.id',
        	'buyers.id as buyer_id',
            'buyers.name as buyer_name',
            'buyers.code',
            'buyers.vendor_code',
            'buyers.sew_effin_percent',
            'buyers.contact_person',
            'buyers.designation',
            'buyers.email',
            'buyers.cell_no',
            'buyers.address',
            'teams.name as team_name',
            'companies.name as company_name',
            'suppliers.name as supplier_name',
            'buyinghouse.name as buyinghouse_name',
            'users.name as teammember_name'
        ])
        ->map(function($rows){
        	return $rows;
        });
        echo json_encode($rows);   
	}

	public function getBuys(){

        $rows=$this->buyerdevelopment
		->join('buyers', function($join)  {
		$join->on('buyers.id', '=', 'buyer_developments.buyer_id');
		})
		->leftJoin('teams', function($join)  {
		$join->on('buyers.team_id', '=', 'teams.id');
		})
		->leftJoin('companies', function($join)  {
		$join->on('buyers.company_id', '=', 'companies.id');
		})
		->leftJoin('suppliers', function($join)  {
		$join->on('buyers.supplier_id', '=', 'suppliers.id');
		})
		->leftJoin('buyers as buyinghouse', function($join)  {
		$join->on('buyers.buying_agent_id', '=', 'buyinghouse.id');
		})
		->leftJoin('teammembers', function($join)  {
		$join->on('teammembers.id', '=', 'buyers.teammember_id');
		})
		->leftJoin('users', function($join)  {
		$join->on('users.id', '=', 'teammembers.user_id');
		})
        ->where([['buyer_developments.id','=',request('id',0)]])
        ->orderBy('buyer_developments.id','desc')
        ->get([
        	'buyers.id',
        	'buyers.id as buyer_id',
            'buyers.name as buyer_name',
            'buyers.code',
            'buyers.vendor_code',
            'buyers.sew_effin_percent',
            'buyers.contact_person',
            'buyers.designation',
            'buyers.email',
            'buyers.cell_no',
            'buyers.address',
            'teams.name as team_name',
            'companies.name as company_name',
            'suppliers.name as supplier_name',
            'buyinghouse.name as buyinghouse_name',
            'users.name as teammember_name'
        ])
        ->map(function($rows){
        	return $rows;
        });
        echo json_encode($rows);   
	}

	public function getDocs(){

        $rows=$this->buyerdevelopment
        ->leftJoin('buyer_development_docs', function($join)  {
		$join->on('buyer_developments.id', '=', 'buyer_development_docs.buyer_development_id');
		})
        ->where([['buyer_development_docs.buyer_development_id','=',request('id',0)]])
        ->orderBy('buyer_development_docs.id','desc')
        ->get([
            'buyer_development_docs.*'
        ])
        ->map(function($rows){
        	return $rows;
        });
        echo json_encode($rows);   
	}

	public function getBuyCont()
	{

		$rows=$this->buyer
		
		->leftJoin('buyer_branches', function($join)  {
		$join->on('buyer_branches.buyer_id', '=', 'buyers.id');
		})
		->leftJoin('countries', function($join)  {
		$join->on('countries.id', '=', 'buyer_branches.country_id');
		})
		
        ->where([['buyers.id','=',request('buyer_id',0)]])
        ->orderBy('buyers.id','desc')
        ->get([
        	'buyers.id',
            'buyers.name as buyer_name',
            'countries.name as country_name',
            'buyer_branches.contact_person',
            'buyer_branches.designation',
            'buyer_branches.email',
            'buyer_branches.shipment_day',
            'buyer_branches.address'
           
        ])
        ->map(function($rows){
        	return $rows;
        });
        echo json_encode($rows);   

	}

	public function getOrderForcasting(){
		
		$buyer_id=request('buyer_id', 0);
		$team_id=request('team_id', 0);
		$status_id=request('status_id',0);
		$date_from=request('date_from',0);
    	$date_to=request('date_to',0);
		$buyer=null;
		$team=null;
		$status=null;
		$datefrom=null;
		$dateto=null;
		
		if($buyer_id){
			$buyer=" and buyer_developments.buyer_id=$buyer_id ";
		}

		if($team_id){
			$team=" and buyer_developments.team_id=$team_id ";
		}
		
		if($status_id){
			$status=" and buyer_developments.status_id = $status_id ";
		}
		if($date_from){
			$datefrom=" and buyer_development_order_qties.est_ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and buyer_development_order_qties.est_ship_date<='".$date_to."' ";
		}

		$rows=collect(\DB::select("
			select
			m.id,
			m.team_id,
			m.teammember_id,
			m.team_name,
			m.teammember_name,
			m.buyer_name,
			m.brand_name,
			m.buyer_id,
			m.brand_id,
			m.buyer_development_order_id,
			m.style_description,
			m.remarks,
			m.smv,
			m.est_ship_month,
			m.est_ship_month_no,
			m.est_ship_year,
			m.est_ship_year_full,
			sum(m.qty) as qty,
			avg(m.rate) as rate,
			sum(m.amount) as amount,
			sum(m.rcv_qty) as rcv_qty,
			avg(m.rcv_rate) as rcv_rate,
			sum(m.rcv_amount) as rcv_amount
			from
			(
			select
				buyer_developments.id,
				buyer_developments.team_id,
				buyer_developments.buyer_id,
				buyer_developments.teammember_id,
				buyer_development_intms.buyer_id as brand_id,
				teams.name as team_name,
				users.name as teammember_name,
				users.id as user_id,
				buyers.name as buyer_name,
				brand.name as brand_name,
				buyer_development_orders.id as buyer_development_order_id,
				buyer_development_orders.smv,
				buyer_development_orders.style_description,
				buyer_development_orders.remarks,
				
				to_char(buyer_development_order_qties.est_ship_date, 'Month') as est_ship_month,
				to_char(buyer_development_order_qties.est_ship_date, 'MM') as est_ship_month_no,
				to_char(buyer_development_order_qties.est_ship_date, 'yy') as est_ship_year,
				to_char(buyer_development_order_qties.est_ship_date, 'yyyy') as est_ship_year_full,
				buyer_development_order_qties.qty,
				buyer_development_order_qties.rate,
				buyer_development_order_qties.amount,
				buyer_development_order_qties.rcv_qty,
				buyer_development_order_qties.rcv_rate,
				buyer_development_order_qties.rcv_amount,
				buyer_development_order_qties.est_ship_date
				from buyer_developments
				join buyer_development_intms on buyer_development_intms.buyer_development_id=buyer_developments.id
				join buyer_development_orders on buyer_development_orders.buyer_development_intm_id=buyer_development_intms.id
				join buyer_development_order_qties on buyer_development_order_qties.buyer_development_order_id=buyer_development_orders.id
				left join teams on teams.id=buyer_developments.team_id
				left join teammembers on buyer_developments.teammember_id=teammembers.id
				left join users on users.id=teammembers.user_id
				left join buyers on buyers.id=buyer_developments.buyer_id
				left join buyers brand on brand.id=buyer_development_intms.buyer_id
				where 1=1 $buyer $status $datefrom $dateto $team
			) m
			group by
			m.id,
			m.team_id,
			m.teammember_id,
			m.team_name,
			m.teammember_name,
			m.buyer_name,
			m.brand_name,
			m.buyer_id,
			m.brand_id,
			m.buyer_development_order_id,
			m.style_description,
			m.remarks,
			m.smv,
			m.est_ship_month,
			m.est_ship_month_no,
			m.est_ship_year,
			m.est_ship_year_full
			order by
			m.est_ship_year asc,
			m.est_ship_month_no,
			m.team_id,
			m.teammember_id
			
		"))
		->map(function($rows) {
			$rows->month=$rows->est_ship_month."-".$rows->est_ship_year;
			$rows->start_date=$rows->est_ship_year_full."-".$rows->est_ship_month_no."-01";
			$rows->yet_to_rcv_qty=$rows->qty-$rows->rcv_qty;
			$rows->yet_to_rcv_amount=$rows->amount-$rows->rcv_amount;
			$rows->std_allowed_hr=($rows->qty*$rows->smv)/60;
			$rows->rcv_std_allowed_hr=($rows->rcv_qty*$rows->smv)/60;
			$rows->yet_rcv_std_allowed_hr=($rows->yet_to_rcv_qty*$rows->smv)/60;
			return $rows;
		});

		$monthArr=[];
		$monthStartDateArr=[];
		$styleDescArr=[];
		//$teamMemberArr=[];
		$monthwiseArr=[];
		foreach($rows as $data){
			$monthArr[$data->month]=$data->month;
			$monthStartDateArr[$data->month]['start_date']=$data->start_date;
			$monthStartDateArr[$data->month]['end_date']=date('Y-m-t',strtotime($data->start_date));
			$styleDescArr[$data->buyer_development_order_id]['id']=$data->id;
			$styleDescArr[$data->buyer_development_order_id]['team_name']=$data->team_name;
			$styleDescArr[$data->buyer_development_order_id]['team_id']=$data->team_id;
			$styleDescArr[$data->buyer_development_order_id]['teammember_name']=$data->teammember_name;
			$styleDescArr[$data->buyer_development_order_id]['brand_id']=$data->brand_id;
			$styleDescArr[$data->buyer_development_order_id]['buyer_name']=$data->buyer_name;
			$styleDescArr[$data->buyer_development_order_id]['buyer_id']=$data->buyer_id;
			$styleDescArr[$data->buyer_development_order_id]['brand_name']=$data->brand_name;
			$styleDescArr[$data->buyer_development_order_id]['brand_id']=$data->brand_id;
			$styleDescArr[$data->buyer_development_order_id]['remarks']=$data->remarks;
			$styleDescArr[$data->buyer_development_order_id]['style_description']=$data->style_description;

			// $monthwiseArr[$data->team_id][$data->buyer_id][$data->brand_id][$data->style_description][$data->month]['qty']=$data->qty;
			// $monthwiseArr[$data->team_id][$data->buyer_id][$data->brand_id][$data->style_description][$data->month]['amount']=$data->amount;
			// $monthwiseArr[$data->team_id][$data->buyer_id][$data->brand_id][$data->style_description][$data->month]['rcv_qty']=$data->rcv_qty;
			// $monthwiseArr[$data->team_id][$data->buyer_id][$data->brand_id][$data->style_description][$data->month]['rcv_amount']=$data->rcv_amount;
			// $monthwiseArr[$data->team_id][$data->buyer_id][$data->brand_id][$data->style_description][$data->month]['yet_to_rcv_qty']=$data->yet_to_rcv_qty;
			// $monthwiseArr[$data->team_id][$data->buyer_id][$data->brand_id][$data->style_description][$data->month]['yet_to_rcv_amount']=$data->yet_to_rcv_amount;
			// $monthwiseArr[$data->team_id][$data->buyer_id][$data->brand_id][$data->style_description][$data->month]['std_allowed_hr']=$data->std_allowed_hr;
			// $monthwiseArr[$data->team_id][$data->buyer_id][$data->brand_id][$data->style_description][$data->month]['rcv_std_allowed_hr']=$data->rcv_std_allowed_hr;
			// $monthwiseArr[$data->team_id][$data->buyer_id][$data->brand_id][$data->style_description][$data->month]['yet_rcv_std_allowed_hr']=$data->yet_rcv_std_allowed_hr;

			$monthwiseArr[$data->id][$data->brand_id][$data->buyer_development_order_id][$data->month]['qty']=$data->qty;
			$monthwiseArr[$data->id][$data->brand_id][$data->buyer_development_order_id][$data->month]['amount']=$data->amount;
			$monthwiseArr[$data->id][$data->brand_id][$data->buyer_development_order_id][$data->month]['rcv_qty']=$data->rcv_qty;
			$monthwiseArr[$data->id][$data->brand_id][$data->buyer_development_order_id][$data->month]['rcv_amount']=$data->rcv_amount;
			$monthwiseArr[$data->id][$data->brand_id][$data->buyer_development_order_id][$data->month]['yet_to_rcv_qty']=$data->yet_to_rcv_qty;
			$monthwiseArr[$data->id][$data->brand_id][$data->buyer_development_order_id][$data->month]['yet_to_rcv_amount']=$data->yet_to_rcv_amount;
			$monthwiseArr[$data->id][$data->brand_id][$data->buyer_development_order_id][$data->month]['std_allowed_hr']=$data->std_allowed_hr;
			$monthwiseArr[$data->id][$data->brand_id][$data->buyer_development_order_id][$data->month]['rcv_std_allowed_hr']=$data->rcv_std_allowed_hr;
			$monthwiseArr[$data->id][$data->brand_id][$data->buyer_development_order_id][$data->month]['yet_rcv_std_allowed_hr']=$data->yet_rcv_std_allowed_hr;
		}
		//dd($monthwiseArr);die;

		$buyerdev=$rows->groupBy(['buyer_development_order_id']);
		return Template::loadView('Report.BuyerDevelopmentReportMatrix',
		[
			'datefrom'=>$date_from,
			'dateto'=>$date_to,
			'rows'=>$buyerdev,
			'monthArr'=>$monthArr,
			'styleDescArr'=>$styleDescArr,
			'monthwiseArr'=>$monthwiseArr,
			'monthStartDateArr'=>$monthStartDateArr
		]);
	}

	public function getMktCost(){
		$buyer_development_order_id=request('buyer_development_order_id', 0);
		$start_date=request('start_date', 0);
		$end_date=request('end_date', 0);

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
		->join('buyer_development_order_qties',function($join){
			$join->on('buyer_development_order_qties.id','=','mkt_costs.buyer_development_order_qty_id');
		})
		->join('buyer_development_orders',function($join){
			$join->on('buyer_development_orders.id','=','buyer_development_order_qties.buyer_development_order_id');
		})
		->where([['buyer_development_orders.id','=',$buyer_development_order_id]])
		->when($start_date, function ($q) use($start_date) {
			return $q->where('buyer_development_order_qties.est_ship_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
			return $q->where('buyer_development_order_qties.est_ship_date', '<=',$end_date);
		})
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



}
