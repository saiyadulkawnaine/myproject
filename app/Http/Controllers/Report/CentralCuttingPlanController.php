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
use Illuminate\Support\Carbon;
class CentralCuttingPlanController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $user;
	private $buyernature;
	private $itemaccount;
	public function __construct(
		StyleRepository $style,
		CompanyRepository $company,
		BuyerRepository $buyer,
		BuyerNatureRepository $buyernature,
		UserRepository $user,
		ItemAccountRepository $itemaccount
	)
    {
		$this->style=$style;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->user = $user;
		$this->buyernature = $buyernature;
		$this->itemaccount = $itemaccount;
		$this->middleware('auth');
		//$this->middleware('permission:view.orderprogressreports',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-',0);
        return Template::loadView('Report.CentralCuttingPlan',['company'=>$company,'buyer'=>$buyer]);
    }

    private function getData()
    {
    	$company_id=request('company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);

		$company=null;
		$buyer=null;
		$datefrom=null;
		$dateto=null;

		if($company_id){
			$company=" and target_transfers.produced_company_id = $company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}
		if($date_from){
			$datefrom=" and target_transfers.date_to >='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and target_transfers.date_to <='".$date_to."' ";
		}

		$gmtitems = collect(
        \DB::select("
				select 
				target_transfers.entry_id,
				item_accounts.item_description
				from
				target_transfers
				join sales_orders on sales_orders.id=target_transfers.sales_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				join style_gmts on styles.id=style_gmts.style_id
				join item_accounts on item_accounts.id=style_gmts.item_account_id
			    where target_transfers.process_id=5 $company $buyer  $datefrom  $dateto
				order by target_transfers.entry_id desc
			")
        );

        $gmtArr=[];

        foreach($gmtitems as $gmtitem)
        {
        	$gmtArr[$gmtitem->entry_id][]=$gmtitem->item_description;

        }


		$data = collect(
        \DB::select("
			select 
			target_transfers.entry_id,
			companies.code as company_code,
			pcompanies.code as produced_company_code,
			teamleadernames.name as team_ld_name,
			users.name as team_member_name,
			buyers.name as buyer_name,
			buying_houses.name as buying_house_name,
			styles.style_ref,
			sales_orders.sale_order_no,
			sales_orders.ship_date,
			productdepartments.department_name,
			styles.flie_src,
			target_transfers.date_from,
			target_transfers.date_to,
			target_transfers.qty
			from
			target_transfers
			join sales_orders on sales_orders.id=target_transfers.sales_order_id
			join jobs on jobs.id=sales_orders.job_id
			join styles on styles.id=jobs.style_id
			join companies on companies.id=jobs.company_id
			join companies  pcompanies on pcompanies.id=target_transfers.produced_company_id
			left join teammembers teamleaders on styles.teammember_id=teamleaders.id
			left join users  teamleadernames on teamleadernames.id=teamleaders.user_id
			left join teammembers  on styles.factory_merchant_id=teammembers.id
			left join users on users.id=teammembers.user_id
			join buyers on buyers.id=styles.buyer_id
			left join buyers   buying_houses on buying_houses.id=styles.buying_agent_id
			left join productdepartments on productdepartments.id=styles.productdepartment_id
			where target_transfers.process_id=5 $company $buyer  $datefrom  $dateto
			order by target_transfers.entry_id desc
			")
        )
        ->map(function($data) use($gmtArr) {

        	$ship_date = Carbon::parse($data->ship_date);
			$date_from = Carbon::parse($data->date_from);
			$date_to = Carbon::parse($data->date_to);
			$cuttingDays = $date_to->diffInDays($date_from)+1;

        	$data->ship_date=date('d-M-Y',strtotime($data->ship_date));
        	$data->date_from=date('d-M-Y',strtotime($data->date_from));
        	$data->date_to=date('d-M-Y',strtotime($data->date_to));
        	$data->cutting_days=$cuttingDays;
        	$data->item_description=implode(',',$gmtArr[$data->entry_id]);
        	$data->remarks='';
			if($date_to->greaterThan($ship_date)){
				$data->remarks='Ask buyer to extend shipment date';
			}
			$data->no_of_line=0;//ceil($data->qty/$cuttingDays/2200);
			$data->qty=number_format($data->qty,0);
        	return $data;
        });

        return $data;
		
    }

    public function reportData() {
		return response()->json($this->getData());
	}
}
