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

class OrderPendingController extends Controller
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
		//$this->middleware('permission:view.orderpending',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
		$sortby=array_prepend(config('bprs.sortby'), '-Select-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.OrderPending',['company'=>$company,'buyer'=>$buyer,'status'=>$status,'sortby'=>$sortby,'team'=>$team]);
    }

    public function reportData()
    {
    	$company_id=request('company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);

		$company=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}
		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($date_from){
			$datefrom=" and styles.created_at >=TO_DATE('".$date_from."','YYYY/MM/DD') ";
		}
		if($date_to){
			$dateto=" and styles.created_at <= TO_DATE('".$date_to."','YYYY/MM/DD') ";
		}
		if($receive_date_from){
			$receivedatefrom=" and styles.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and styles.receive_date<='".$receive_date_to."' ";
		}

		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);

		$rows=collect(
			\DB::select("
			select
				styles.id as style_id,
				styles.style_ref,
				styles.flie_src,
				styles.buying_agent_id,
				styles.contact,
				styles.ship_date,
				styles.receive_date,
				styles.remarks,
				styles.style_description,
				styles.offer_qty,
				styles.created_at,
				buyers.id as buyer_id,
				buyers.name as buyer_name,
				uoms.code as uom_code,
				seasons.name as season_name,
				teams.name as team_name,
				teamleadernames.id as teamleader_id,
				teamleadernames.name as teamleader_name,
				users.id as user_id,
				users.name as team_member_name,
				productdepartments.department_name,
				jobs.job_no,
				companies.code as company_code
			from styles
			left join buyers on styles.buyer_id=buyers.id
			left join uoms on styles.uom_id=uoms.id
			left join seasons on seasons.id=styles.season_id
			left join teams on styles.team_id=teams.id
			left join teammembers on styles.factory_merchant_id=teammembers.id
			left join users on users.id=teammembers.user_id
			left join productdepartments on productdepartments.id=styles.productdepartment_id
			left join teammembers teamleaders on styles.teammember_id=teamleaders.id
			left join  users teamleadernames on teamleadernames.id = teamleaders.user_id 
			left join jobs on jobs.style_id=styles.id
			left join companies on companies.id=jobs.company_id
			left join sales_orders on sales_orders.job_id=jobs.id
			where sales_orders.job_id is null 
			$company $buyer $style  $styleid $factorymerchant $datefrom $dateto $receivedatefrom $receivedateto
			order by styles.id desc
		"))->map(function($rows) use($buyinghouses){
			$rows->ship_date=$rows->ship_date?date('d-M-Y',strtotime($rows->ship_date)):'--';
			$rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
			$rows->agent_name=	isset($buyinghouses[$rows->buying_agent_id])? $buyinghouses[$rows->buying_agent_id]:'';
			$rows->buying_agent_name=$rows->agent_name."". $rows->contact;
			$rows->qty=number_format($rows->offer_qty,0);
			return $rows;
		});

		$month=[];
		foreach($rows as $summery){
			$m=date('M-Y',strtotime($summery->created_at));
			$month[$m]['offer_qty']=isset($month[$m]['offer_qty'])?$month[$m]['offer_qty']+=$summery->offer_qty:$summery->offer_qty;
		}

		$monthDatas=[];
		foreach($month as $key=>$value){
			$monthData['month']=$key;
			$monthData['qty']=number_format($value['offer_qty'],0);
			array_push($monthDatas, $monthData);
		}
		

		echo json_encode(['details'=>$rows,'month'=>$monthDatas]);
    }
	

	public function getDealMerchant(){
			$dlmerchant = $this->user
			->leftJoin('employee_h_rs', function($join)  {
				$join->on('users.id', '=', 'employee_h_rs.user_id');
			})
			->where([['user_id','=',request('user_id',0)]])
			->get([
				'users.id as user_id',
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
				$dlmerchant->date_of_join=date('d-M-Y',strtotime($dlmerchant->date_of_join));
				return $dlmerchant;
			});
			echo json_encode($dlmerchant);
	}

	public function getBuyingHouse(){
			$rows= $this->buyer
			->selectRaw(
				'buyers.id as buyer_id,
				buyers.name as buyer_name,
				buyer_branches.name as branch_name,
				buyer_branches.contact_person,
				buyer_branches.email,
				buyer_branches.designation,
				buyer_branches.address'
			)
			->leftJoin('buyer_branches',function($join){
				$join->on('buyer_branches.buyer_id','=','buyers.id');
			})
			->where([['buyers.id','=',request('buyer_id',0)]])
			->get([
			    'buyers.id as buyer_id',
				'buyers.name',
				'buyer_branches.name',
				'buyer_branches.contact_person',
				'buyer_branches.email',
				'buyer_branches.designation',
				'buyer_branches.address'
		 ]);
			echo json_encode($rows) ;
	}

	public function getOpFileSrc(){
		return response()->json($this->style
		->leftJoin('style_file_uploads',function($join){
		$join->on('style_file_uploads.style_id','=','styles.id');
		})
		->where([['style_id','=',request('style_id',0)]])
		->get([
		'styles.id as style_id',
		'styles.style_ref',
		'style_file_uploads.*'
		]));
	}

	public function getStyle(){
		return response()->json($this->style->getAll()->map(function($rows){
			$rows->receivedate=date("d-M-Y",strtotime($rows->receive_date));
			$rows->buyer=$rows->buyer_name;
			$rows->deptcategory=$rows->dept_category_name;
			$rows->season=$rows->season_name;
			$rows->uom=$rows->uom_name;
			$rows->team=$rows->team_name;
			$rows->teammember=$rows->team_member_name;
			$rows->productdepartment=$rows->department_name;
			return $rows;
		}));
	}

	public function getTeamMemberDlm(){
		$membertype=array_prepend(config('bprs.membertype'),'-Select-',0);
		$teammember = $this->teammember
		->join('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
		})
		->join('teams', function($join)  {
			$join->on('teammembers.team_id', '=', 'teams.id');
		})
		
		->when(request('team_id'), function ($q) {
			return $q->where('teammembers.team_id', '=', request('team_id', 0));
		})
		->get([
			'users.id as user_id',
			'teammembers.id as factory_merchant_id',
			'teammembers.type_id',
			'teams.name as team_name',
			'users.name as dlm_name',
			
		])
		->map(function($teammember)use($membertype){
			$teammember->type_id=$membertype[$teammember->type_id];	
			return $teammember;
		});
		echo json_encode($teammember);
	}
}
