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

class OrderWiseYarnReportController extends Controller
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
		//$this->middleware('permission:view.orderwiseyarnreports',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
        return Template::loadView('Report.OrderwiseYarnReport',['company'=>$company,'buyer'=>$buyer,'status'=>$status]);
    }

    private function getData()
    {
    	$company_id=request('company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$original_date_from=request('original_date_from', 0);
        $original_date_to=request('original_date_to', 0);

		$company=null;
		$buyer=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$originaldatefrom=null;
		$originaldateto=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($original_date_from){
			$originaldatefrom=" and sales_orders.org_ship_date>='".$original_date_from."' ";
		}
		if($original_date_to){
			$originaldateto=" and sales_orders.org_ship_date<='".$original_date_to."' ";
		}

		//$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);

		$rows=collect(
			\DB::select("
			select
            sales_orders.id,
            sales_orders.sale_order_no,
            sales_orders.org_ship_date,
            sales_orders.ship_date,
            sales_orders.receive_date,
            styles.id as style_id,
            styles.style_ref,
            styles.flie_src,
            companies.code as company_code,
            produced_company.code as produced_company_code,
            buyers.name as buyer_name,
            salesorder.qty,
			salesorder.amount,
            yarnrq.yarn_req_qty,
            poyarn.qty as po_yarn_qty,
            poyarnlc.qty as po_yarn_lc_qty,
            yarnrcv.yarn_rcv_qty

            from
            sales_orders
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id
            join companies on companies.id=jobs.company_id
            left join companies produced_company on produced_company.id = sales_orders.produced_company_id 

            left join(
				SELECT
				sales_orders.id as sales_order_id,
				sum(sales_order_gmt_color_sizes.qty) as qty,
				sum(sales_order_gmt_color_sizes.amount) as amount
				FROM sales_orders
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				join jobs on jobs.id = sales_orders.job_id
				join styles on styles.id = jobs.style_id
				where   sales_order_gmt_color_sizes.deleted_at is null
				group by sales_orders.id
			)salesorder on salesorder.sales_order_id=sales_orders.id

            left join (
	            SELECT
	            sales_orders.id as sales_order_id,
	            sum(budget_fabric_cons.grey_fab) as yarn_req_qty
	            FROM sales_orders
	            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
	            join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
				join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
				join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
	            join jobs on jobs.id = sales_orders.job_id
	            join styles on styles.id = jobs.style_id
	            where  budget_fabric_cons.deleted_at is null
	            and sales_order_gmt_color_sizes.deleted_at is null
				and style_fabrications.material_source_id !=1
	            group by sales_orders.id
            ) yarnrq on yarnrq.sales_order_id=sales_orders.id

            left join (
	            SELECT
	            sales_orders.id as sales_order_id,
	            sum(budget_fabric_cons.fin_fab) as fin_fab_req_qty
	            FROM sales_orders
	            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
	            join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
	            join jobs on jobs.id = sales_orders.job_id
	            join styles on styles.id = jobs.style_id
	            where  budget_fabric_cons.deleted_at is null
	            and sales_order_gmt_color_sizes.deleted_at is null
	            group by sales_orders.id
            ) finfabreq on finfabreq.sales_order_id=sales_orders.id

            left join (
	            select
	            po_yarn_item_bom_qties.sale_order_id,
	            sum(po_yarn_item_bom_qties.qty) as qty
	            from
	            po_yarn_item_bom_qties
	            join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
	            join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
	            where po_yarn_item_bom_qties.deleted_at is null
	            and po_yarn_items.deleted_at is null
	            and po_yarns.deleted_at is null
	            group by
	            po_yarn_item_bom_qties.sale_order_id
            ) poyarn on poyarn.sale_order_id=sales_orders.id

            left join (
	            select
	            po_yarn_item_bom_qties.sale_order_id,
	            sum(po_yarn_item_bom_qties.qty) as qty
	            from
	            po_yarn_item_bom_qties
	            join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
	            join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
	            join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
	            join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
	            where imp_lcs.menu_id=3
	            and po_yarn_item_bom_qties.deleted_at is null
	            and po_yarn_items.deleted_at is null
	            and po_yarns.deleted_at is null
	            and imp_lc_pos.deleted_at is null
	            and imp_lcs.deleted_at is null
	            group by
	            po_yarn_item_bom_qties.sale_order_id
            ) poyarnlc on poyarnlc.sale_order_id=sales_orders.id

            left join (
	            select
	            po_yarn_item_bom_qties.sale_order_id as sales_order_id,
	            sum(inv_yarn_rcv_item_sos.qty) as yarn_rcv_qty
	            from
	            po_yarn_item_bom_qties
	            join inv_yarn_rcv_item_sos on inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id=po_yarn_item_bom_qties.id
	            where po_yarn_item_bom_qties.deleted_at is null and  inv_yarn_rcv_item_sos.deleted_at is null
	            group by po_yarn_item_bom_qties.sale_order_id
            ) yarnrcv on yarnrcv.sales_order_id=sales_orders.id

			where sales_orders.order_status !=2 and sales_orders.deleted_at is null
			$company $buyer $datefrom $dateto $originaldatefrom $originaldateto $orderstatus
		"));

		

		$data=$rows->map(function($rows) {
			$rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
			$rows->org_ship_date=date('d-M-Y',strtotime($rows->org_ship_date));
			$rows->po_yarn_bal_qty=number_format(($rows->yarn_req_qty-$rows->po_yarn_qty),2,'.',',');
			$rows->po_yarn_lc_bal_qty=number_format(($rows->yarn_req_qty-$rows->po_yarn_lc_qty),2,'.',',');
			$rows->yarn_req_qty=number_format($rows->yarn_req_qty,0,'.',',');
			$rows->po_yarn_qty=number_format($rows->po_yarn_qty,2,'.',',');
			$rows->po_yarn_lc_qty=number_format($rows->po_yarn_lc_qty,2,'.',',');
			$rows->yarn_rcv_qty=number_format($rows->yarn_rcv_qty,2,'.',',');
			$rows->qty=number_format($rows->qty,2,'.',',');
			$rows->amount=number_format($rows->amount,2,'.',',');
		
			return $rows;
		});

		return $data;
    }

    


	public function reportData() {
		return response()->json($this->getData());
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

	public function getOrderStyle(){
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
