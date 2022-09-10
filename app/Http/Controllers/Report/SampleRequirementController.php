<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use Illuminate\Support\Carbon;
class SampleRequirementController extends Controller
{
	private $mktcost;
	private $company;
	private $buyer;
	private $team;
	private $teammember;
	private $buyernature;
	private $style;
	public function __construct(MktCostRepository $mktcost,CompanyRepository $company,BuyerRepository $buyer,TeamRepository $team,TeammemberRepository $teammember,BuyerNatureRepository $buyernature,UserRepository $user,StyleRepository $style,ItemAccountRepository $itemaccount)
    {
		$this->mktcost=$mktcost;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->team = $team;
		$this->teammember = $teammember;
		$this->buyernature = $buyernature;
		$this->itemaccount               = $itemaccount;
		$this->user = $user;
		$this->style = $style;
		$this->middleware('auth');
		
		$this->middleware('permission:view.quotestatementreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
		 $teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function($join)  {
		$join->on('teammembers.user_id', '=', 'users.id');
		})
		->get([
			'teammembers.id',
			'users.name',
		]),'name','id'),'-Select-',0);
		$orderstage=array_prepend(config('bprs.orderstage'),'-Select-','');
      return Template::loadView('Report.SampleRequirement',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember,'orderstage'=>$orderstage]);
    }
	public function reportData() {
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);
		$fabricinstruction=array_prepend(config('bprs.fabricinstructions'),'-Select-','');

		$fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
    $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		
		$fabricDescription=$this->style
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','styles.id');
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
		->join('style_gmts',function($join){
			$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		})
		->join('item_accounts', function($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})

	->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
	})
	->where([['style_fabrications.gmtspart_id','=',2]])
		->get([
		'style_fabrications.id',
		'constructions.name as construction',
		'autoyarnratios.composition_id',
		'compositions.name',
		'autoyarnratios.ratio',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'gmtsparts.name as gmtspart_name',
		'item_accounts.item_description'

		]);

		$fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
          $fabricDescriptionArr[$row->id]=$row->item_description.", ".$row->gmtspart_name.", ".$fabricnature[$row->fabric_nature_id].", ".$fabriclooks[$row->fabric_look_id].", ".$fabricshape[$row->fabric_shape_id].", ".$row->construction/* .", ".$row->gsm_weight */;
          $fabricCompositionArr[$row->id][]=$row->name.", ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
          $desDropdown[$key]=$val.", ".implode(",",$fabricCompositionArr[$key]);
				}
				
			$yarnDescription=$this->itemaccount
			// ->leftJoin('style_gmts',function($join){
			// 	$join->on('style_gmts.style_id','=','styles.id');
			// })
			// ->leftJoin('item_accounts',function($join){
			// 	$join->on('item_accounts.id','=','style_gmts.item_account_id');
			// })
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
				->orderBY('item_accounts.id')
				->orderBY('item_account_ratios.id')
					->where([['itemcategories.identity','=',1]])
				->get([
				'item_accounts.id as item_account_id',
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
				$yarnDropdown[$key]=$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
			}

      $data = DB::table("style_samples")
			->selectRaw(
			'style_samples.id,
			style_samples.fabric_instruction_id,
			style_samples.remarks,
			style_samples.pattern_from,
			style_samples.pattern_to,
			style_samples.sample_booking_from,
			style_samples.sample_booking_to,
			style_samples.yarn_inhouse_from,
			style_samples.yarn_inhouse_to,
			style_samples.yarn_dyeing_from,
			style_samples.yarn_dyeing_to,
			style_samples.knitting_from,
			style_samples.knitting_to,
			style_samples.dyeing_from,
			style_samples.dyeing_to,
			style_samples.aop_from,
			style_samples.aop_to,
			style_samples.finishing_from,
			style_samples.finishing_to,
			style_samples.cutting_from,
			style_samples.cutting_to,
			style_samples.print_emb_from,
			style_samples.print_emb_to,
			style_samples.emb_from,
			style_samples.emb_to,
			style_samples.washing_from,
			style_samples.washing_to,
			style_samples.trims_from,
			style_samples.trims_to,
			style_samples.sewing_from,
			style_samples.sewing_to,

			style_samples.sub_from,
			style_samples.sub_to,
			style_samples.app_from,
			style_samples.app_to,

			buyers.name as buyer_name,
			buyers.id as buyer_id,
			styles.id as style_id,
			styles.style_ref,
			styles.style_description,
			styles.flie_src,
			styles.file_name,
			styles.ship_date,
			styles.receive_date,		
			styles.contact,
			styles.buying_agent_id,
			style_fabrications.id as style_fabrication_id,
			
			seasons.name as season_name,
			productdepartments.department_name,
			uoms.code as uom_code,
			teamleadernames.name as team_name,
			teamleadernames.id as teamleader_id,
			users.name as team_member,
			users.id as user_id,
			gmtssamples.name as sample_name,
			item_accounts.id as item_account_id,
			item_accounts.item_description,
			sum(style_sample_cs.qty) as qty,
			avg(style_sample_cs.rate) as rate,
			sum(style_sample_cs.amount) as amount
			'
			)
      ->join('style_sample_cs',function($join){
			   $join->on('style_sample_cs.style_sample_id','=','style_samples.id');
		   })
		   ->leftJoin('styles',function($join){
			   $join->on('style_samples.style_id','=','styles.id');
		   })
		   ->leftJoin('style_gmts',function($join){
			   $join->on('style_gmts.style_id','=','styles.id');
			   $join->on('style_gmts.id','=','style_samples.style_gmt_id');
			 })
			 ->join('style_fabrications',function($join){
				$join->on('style_fabrications.style_id','=','styles.id');
				})
			 /* ->leftJoin('style_file_uploads',function($join){
				$join->on('style_file_uploads.style_id','=','styles.id');
				}) */
		   	->leftJoin('item_accounts',function($join){
			   $join->on('item_accounts.id','=','style_gmts.item_account_id');
		   })
		   	->leftJoin('gmtssamples', function($join)  {
					$join->on('gmtssamples.id', '=', 'style_samples.gmtssample_id');
				})
		   ->leftJoin('buyers',function($join){
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
		  ->leftJoin('teams', function($join)  {
			$join->on('styles.team_id', '=', 'teams.id');
			})
			->leftJoin('teammembers', function($join)  {
			$join->on('styles.factory_merchant_id', '=', 'teammembers.id');
			})
			->leftJoin('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
			})

			->leftJoin('teammembers as teamleaders', function($join)  {
			$join->on('styles.teammember_id', '=', 'teamleaders.id');
			})

			->leftJoin('users as teamleadernames', function($join)  {
			$join->on('teamleadernames.id', '=', 'teamleaders.user_id');
			})

		  /*->leftJoin(\DB::raw("(select styles.id as style_id,teams.name ,users.name as team_name  from styles
			left join teams on teams.id=styles.team_id
			left join teammembers on teams.id=teammembers.team_id
			left join users on teammembers.user_id=users.id
			where teammembers.type_id=2 group by styles.id,teams.name,users.name) teamleader"), "teamleader.style_id","=","styles.id")*/ 
		   ->when(request('buyer_id'), function ($q) {
			return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		   })
		   ->when(request('team_id'), function ($q) {
			return $q->where('styles.team_id', '=', request('team_id', 0));
		   })
		    ->when(request('teammember_id'), function ($q) {
			return $q->where('styles.factory_merchant_id', '=', request('teammember_id', 0));
		   })
		   ->when(request('style_ref'), function ($q) {
			return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
		   })
		   ->when(request('date_from'), function ($q) {
			return $q->where('styles.ship_date', '>=',request('date_from', 0));
		   })
		   ->when(request('date_to'), function ($q) {
			return $q->where('styles.ship_date', '<=',request('date_to', 0));
		   })
		   ->when(request('orderstage_id'), function ($q) {
			return $q->where('style_samples.approval_priority', '=',request('orderstage_id', 0));
		   })

		   
		   //->where([['gmtssamples.type_id','=',1]])
		   ->groupBy([
		   	'style_samples.id',
		   	'style_samples.remarks',
		   	'style_samples.fabric_instruction_id',
		   	'style_samples.pattern_from',
				'style_samples.pattern_to',
				'style_samples.sample_booking_from',
				'style_samples.sample_booking_to',
				'style_samples.yarn_inhouse_from',
				'style_samples.yarn_inhouse_to',
		   	'style_samples.yarn_dyeing_from',
				'style_samples.yarn_dyeing_to',
				'style_samples.knitting_from',
				'style_samples.knitting_to',
				'style_samples.dyeing_from',
				'style_samples.dyeing_to',
				'style_samples.aop_from',
				'style_samples.aop_to',
				'style_samples.finishing_from',
				'style_samples.finishing_to',
				'style_samples.cutting_from',
				'style_samples.cutting_to',
				'style_samples.print_emb_from',
				'style_samples.print_emb_to',
				'style_samples.emb_from',
				'style_samples.emb_to',
				'style_samples.washing_from',
				'style_samples.washing_to',
				'style_samples.trims_from',
				'style_samples.trims_to',
				'style_samples.sewing_from',
				'style_samples.sewing_to',
				'style_samples.sub_from',
				'style_samples.sub_to',
				'style_samples.app_from',
				'style_samples.app_to',
				'buyers.name',
				'buyers.id',
				'styles.id',
				'styles.style_ref',
				'styles.style_description',
				'styles.flie_src',
				'styles.file_name',
				'styles.ship_date',
				'styles.receive_date',
				'styles.contact',
				'styles.buying_agent_id',
				'style_fabrications.id',
				'seasons.name',
				'productdepartments.department_name',
				'uoms.code',
				'teamleadernames.name',
				'teamleadernames.id',
				'users.name',
				'users.id',
				'gmtssamples.name',
				'item_accounts.id',
				'item_accounts.item_description'
		   ])
		   ->get()
		   ->map(function($data) use ($fabricinstruction,$buyinghouses,$desDropdown,$yarnDropdown){

		   	$receive_date = Carbon::parse($data->receive_date);
		   	$sub_to = Carbon::parse($data->sub_to);
		   	$today=Carbon::parse(date('Y-m-d'));
		    $pasedDays = $receive_date->diffInDays($today);
		    $delayDays = $sub_to->diffInDays($today);
		    $data->pasedDays=$pasedDays;
		    $data->delayDays=$delayDays;

		   	$data->ship_date='';
			if($data->ship_date)
			{
		   	 $data->ship_date=date("d-M-Y",strtotime($data->ship_date));
			}

		  /*style_samples.yarn_dyeing_from,
			style_samples.yarn_dyeing_to,
			style_samples.knitting_from,
			style_samples.knitting_to,
			style_samples.dyeing_from,
			style_samples.dyeing_to,
			style_samples.aop_from,
			style_samples.aop_to,
			style_samples.finishing_from,
			style_samples.finishing_to,
			style_samples.cutting_from,
			style_samples.cutting_to,
			style_samples.print_emb_from,
			style_samples.print_emb_to,
			style_samples.sweing_from,
			style_samples.sweing_to,*/
			$data->yarn_dyeing_tna='';
			if($data->yarn_dyeing_from && $data->yarn_dyeing_to)
			{
			$data->yarn_dyeing_tna=date("d-M-Y",strtotime($data->yarn_dyeing_from))."<br/> To  <br/>". date("d-M-Y",strtotime($data->yarn_dyeing_to));
			}

			$data->knitting_tna='';
			if($data->knitting_from && $data->knitting_to)
			{
			$data->knitting_tna=date("d-M-Y",strtotime($data->knitting_from))."<br/> To  <br/>". date("d-M-Y",strtotime($data->knitting_to));
			}

			$data->dyeing_tna='';
			if($data->dyeing_from && $data->dyeing_to)
			{
			$data->dyeing_tna=date("d-M-Y",strtotime($data->dyeing_from))."<br/> To  <br/>". date("d-M-Y",strtotime($data->dyeing_to));
			}


			$data->aop_tna='';
			if($data->aop_from && $data->aop_to)
			{
			$data->aop_tna=date("d-M-Y",strtotime($data->aop_from))."<br/> To  <br/>". date("d-M-Y",strtotime($data->aop_to));
			}

			$data->finishing_tna='';
			if($data->finishing_from && $data->finishing_from)
			{
			$data->finishing_tna=date("d-M-Y",strtotime($data->finishing_from))."<br/> To  <br/>". date("d-M-Y",strtotime($data->finishing_from));
			}

			$data->cutting_tna='';
			if($data->cutting_from && $data->cutting_to)
			{
			$data->cutting_tna=date("d-M-Y",strtotime($data->cutting_from))."<br/> To  <br/>". date("d-M-Y",strtotime($data->cutting_to));
			}

			$data->print_emb_tna='';
			if($data->print_emb_from && $data->print_emb_to)
			{
			$data->print_emb_tna=date("d-M-Y",strtotime($data->print_emb_from))."<br/> To  <br/>". date("d-M-Y",strtotime($data->print_emb_to));
			}

			$data->sewing_tna='';
			if($data->sewing_from && $data->sewing_to)
			{
			$data->sewing_tna=date("d-M-Y",strtotime($data->sewing_from))."<br/> To  <br/>". date("d-M-Y",strtotime($data->sewing_to));
			}


			$data->sub_tna='';
			if($data->sub_from && $data->sub_to)
			{
			$data->sub_tna=date("d-M-Y",strtotime($data->sub_from))."<br/> To  <br/> ". date("d-M-Y",strtotime($data->sub_to));
			}


			$data->app_tna='';
			if($data->app_from && $data->app_to)
			{
			$data->app_tna=date("d-M-Y",strtotime($data->app_from))."<br/> To  <br/> ". date("d-M-Y",strtotime($data->app_to));
			}


			$data->pattern_tna='';
			if($data->pattern_from && $data->pattern_to)
			{
			$data->pattern_tna=date("d-M-Y",strtotime($data->pattern_from))."<br/> To  <br/> ". date("d-M-Y",strtotime($data->pattern_to));
			}


			$data->sample_booking_tna='';
			if($data->sample_booking_from && $data->sample_booking_to)
			{
			$data->sample_booking_tna=date("d-M-Y",strtotime($data->sample_booking_from))."<br/> To  <br/> ". date("d-M-Y",strtotime($data->sample_booking_to));
			}


			$data->yarn_inhouse_tna='';
			if($data->yarn_inhouse_from && $data->yarn_inhouse_to)
			{
			$data->yarn_inhouse_tna=date("d-M-Y",strtotime($data->yarn_inhouse_from))."<br/> To  <br/> ". date("d-M-Y",strtotime($data->yarn_inhouse_to));
			}


			$data->emb_tna='';
			if($data->emb_from && $data->emb_to)
			{
			$data->emb_tna=date("d-M-Y",strtotime($data->emb_from))."<br/> To  <br/> ". date("d-M-Y",strtotime($data->emb_to));
			}


			$data->washing_tna='';
			if($data->washing_from && $data->washing_to)
			{
			$data->washing_tna=date("d-M-Y",strtotime($data->washing_from))."<br/> To  <br/> ". date("d-M-Y",strtotime($data->washing_to));
			}


			$data->trims_tna='';
			if($data->trims_from && $data->trims_to)
			{
			$data->trims_tna=date("d-M-Y",strtotime($data->trims_from))."<br/> To  <br/> ". date("d-M-Y",strtotime($data->trims_to));
			}


			$data->fabric_instruction_id=$fabricinstruction[$data->fabric_instruction_id];
			$data->qty=number_format($data->qty,0);
			$data->amount=number_format($data->amount,2);

			$data->agent_name=	isset($buyinghouses[$data->buying_agent_id])? $buyinghouses[$data->buying_agent_id]:'';

			$data->buying_agent_name=	$data->agent_name.'  '. $data->contact;
			$data->receive_date=date('d-M-Y',strtotime($data->receive_date));

			$data->fabric_description=isset($desDropdown[$data->style_fabrication_id])?$desDropdown[$data->style_fabrication_id]:'';
			$data->yarn_description=isset($yarnDropdown[$data->item_account_id])?$yarnDropdown[$data->item_account_id]:'';

			return $data;
		   });
		   echo json_encode($data);
		}
		
		public function getDealMerchant()
		{
			$dlmerchant = $this->user
			->leftJoin('employee_h_rs', function($join)  {
				$join->on('users.id', '=', 'employee_h_rs.user_id');
			})
			->when(request('buyer_id'), function ($q) {
				return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
			})
			->when(request('team_id'), function ($q) {
				return $q->where('styles.team_id', '=', request('team_id', 0));
			})
			->when(request('teammember_id'), function ($q) {
				return $q->where('styles.factory_merchant_id', '=', request('teammember_id', 0));
			})
			->when(request('style_ref'), function ($q) {
				return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
			})
			->where([['user_id','=',request('user_id',0)]])
			->get([
				'users.id as user_id',
				/* 'users.name as team_member', */
				'employee_h_rs.name',
				'employee_h_rs.date_of_join',
				'employee_h_rs.last_education',
				'employee_h_rs.address',
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
		public function getFileSrc(){
			$filesrc=$this->style
			->leftJoin('style_file_uploads',function($join){
			   $join->on('style_file_uploads.style_id','=','styles.id');
			 })
			 ->when(request('buyer_id'), function ($q) {
				return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
				 })
				 ->when(request('team_id'), function ($q) {
				return $q->where('styles.team_id', '=', request('team_id', 0));
				 })
					->when(request('teammember_id'), function ($q) {
				return $q->where('styles.factory_merchant_id', '=', request('teammember_id', 0));
				 })
				 ->when(request('style_ref'), function ($q) {
				return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
				 })
				 ->when(request('date_from'), function ($q) {
				return $q->where('styles.ship_date', '>=',request('date_from', 0));
				 })
				 ->when(request('date_to'), function ($q) {
				return $q->where('styles.ship_date', '<=',request('date_to', 0));
				 })
				 ->when(request('orderstage_id'), function ($q) {
				return $q->where('style_samples.approval_priority', '=',request('orderstage_id', 0));
				 })
			->where([['style_id','=',request('style_id',0)]])
			->get([
				'styles.id as style_id',
				'styles.style_ref',
				'style_file_uploads.*'
			]);
			echo json_encode($filesrc);
		}
}
