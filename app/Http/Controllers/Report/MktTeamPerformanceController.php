<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use Illuminate\Support\Carbon;

class MktTeamPerformanceController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $user;
	private $buyernature;
	private $itemaccount;
	private $autoyarn;
	private $teammember;
    private $team;
	public function __construct(
		StyleRepository $style,
		CompanyRepository $company,
		BuyerRepository $buyer,
		BuyerNatureRepository $buyernature,
		UserRepository $user,
		ItemAccountRepository $itemaccount,
		AutoyarnRepository $autoyarn,
		TeamRepository $team,
		TeammemberRepository $teammember
	)
    {
		$this->style=$style;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->user = $user;
		$this->buyernature = $buyernature;
		$this->itemaccount = $itemaccount;
		$this->autoyarn = $autoyarn;
		$this->team = $team;
		$this->teammember = $teammember;

		$this->middleware('auth');
		//$this->middleware('permission:view.mktteamperformance',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
		$sortby=array_prepend(config('bprs.sortby'), '-Select-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.MktTeamPerformance',['company'=>$company,'buyer'=>$buyer,'status'=>$status,'sortby'=>$sortby,'team'=>$team]);
    }

    public function getData(){
        $status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
        $date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $order_status=request('order_status',0);

        $datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
        $orderstatus=null;

        if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}
        if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}

        $rows=collect(
			\DB::select("
                select
                m.sip_month,
                m.sip_month_no,
                m.sip_year,
                m.teamleader_id,
                m.teamleader_name,
                sum(m.qty) as qty,
                sum(m.amount) as amount
                from
                (SELECT
                sales_orders.id,
                sales_orders.ship_date,
                to_char(sales_orders.ship_date, 'Mon') as sip_month,
                to_char(sales_orders.ship_date, 'MM') as sip_month_no,
                to_char(sales_orders.ship_date, 'yy') as sip_year,
                teamleadernames.id as  teamleader_id,
                teamleadernames.name as teamleader_name,
                
                sales_order_gmt_color_sizes.qty,
                sales_order_gmt_color_sizes.amount
                FROM sales_orders
                join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join jobs on jobs.id = sales_orders.job_id
                join styles on styles.id = jobs.style_id
                join teams on teams.id=styles.team_id
                join teammembers teamleaders on teamleaders.id=styles.teammember_id
                join users teamleadernames on teamleadernames.id=teamleaders.user_id
                where  1=1 $datefrom $dateto $receivedatefrom $receivedateto $orderstatus
                ) m
                group by m.sip_month,
                m.sip_month_no,
                m.sip_year,
                m.teamleader_id,
                m.teamleader_name
                order by m.sip_year,m.sip_month_no         
            "))
			->map(function($rows) {
                $rows->month=$rows->sip_month."-".$rows->sip_year;
                $rows->team=$rows->teamleader_id."-".$rows->teamleader_name;
                return $rows;
            });

            $datas=$rows->groupBy('teamleader_id');
           
            $teamArr=[];
            $monthArr=[];
            foreach($rows as $data){
                $teamArr[$data->teamleader_id]['team_name']=$data->team;
               // $teamArr[$data->teamleader_id]['month']=$data->month;
                $monthArr[$data->month]['qty']=$data->qty;
                $monthArr[$data->month]['amount']=$data->amount;
            }

         //   dd($monthArr);die;


         return Template::loadView('Report.MktTeamPerformanceMatrix',['datas'=>$datas,'status'=>$status,'teamArr'=>$teamArr,'monthArr'=>$monthArr]);
    }

    // public function reportData() {
	// 	return response()->json($this->getData());
	// }

}
