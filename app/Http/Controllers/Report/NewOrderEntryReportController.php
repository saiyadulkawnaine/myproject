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

class NewOrderEntryReportController extends Controller
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
	) {
		$this->style = $style;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->user = $user;
		$this->buyernature = $buyernature;
		$this->itemaccount = $itemaccount;
		$this->autoyarn = $autoyarn;
		$this->team = $team;
		$this->teammember = $teammember;

		$this->middleware('auth');
		//$this->middleware('permission:view.neworderentryreports',['only' => ['create', 'index','show']]);
	}

	public function index()
	{
		$company = array_prepend(array_pluck($this->company->where([['nature_id', '=', 1]])->get(), 'name', 'id'), '-Select-', '');
		$buyer = array_prepend(array_pluck($this->buyer->buyers(), 'name', 'id'), '', '');
		$status = array_prepend(array_only(config('bprs.status'), [1, 4]), '-All-', '');
		$sortby = array_prepend(config('bprs.sortby'), '-Select-', '');
		$team = array_prepend(array_pluck($this->team->get(), 'name', 'id'), '-Select-', '');
		return Template::loadView('Report.NewOrderEntryReport', ['company' => $company, 'buyer' => $buyer, 'status' => $status, 'sortby' => $sortby, 'team' => $team]);
	}

	private function getData()
	{
		$company_id = request('company_id', 0);
		$produced_company_id = request('produced_company_id', 0);
		$buyer_id = request('buyer_id', 0);
		$style_ref = request('style_ref', 0);
		$style_id = request('style_id', 0);
		$factory_merchant_id = request('factory_merchant_id', 0);
		$order_status = request('order_status', 0);

		$date_from = request('date_from', 0);
		$date_to = request('date_to', 0);
		$receive_date_from = request('receive_date_from', 0);
		$receive_date_to = request('receive_date_to', 0);
		$entry_date_from = request('entry_date_from', 0);
		$entry_date_to = request('entry_date_to', 0);

		$company = null;
		$producedcompany = null;
		$buyer = null;
		$style = null;
		$styleid = null;
		$factorymerchant = null;
		$orderstatus = null;
		$datefrom = null;
		$dateto = null;
		$receivedatefrom = null;
		$receivedateto = null;
		if ($company_id) {
			$company = " and jobs.company_id = $company_id ";
		}
		if ($produced_company_id) {
			$producedcompany = " and sales_orders.produced_company_id = $produced_company_id ";
		}
		if ($buyer_id) {
			$buyer = " and styles.buyer_id=$buyer_id ";
		}

		if ($style_ref) {
			$style = " and styles.style_ref like '%" . $style_ref . "%' ";
		}
		if ($style_id) {
			$styleid = " and styles.id = $style_id ";
		}
		if ($factory_merchant_id) {
			$factorymerchant = " and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if ($order_status) {
			$orderstatus = " and sales_orders.order_status = $order_status ";
		}
		if ($date_from) {
			$datefrom = " and sales_orders.ship_date>='" . $date_from . "' ";
		}
		if ($date_to) {
			$dateto = " and sales_orders.ship_date<='" . $date_to . "' ";
		}
		if ($receive_date_from) {
			$receivedatefrom = " and sales_orders.receive_date>='" . $receive_date_from . "' ";
		}
		if ($receive_date_to) {
			$receivedateto = " and sales_orders.receive_date<='" . $receive_date_to . "' ";
		}

		if ($entry_date_from) {
			$entrydatefrom = " and sales_orders.created_at>='" . $entry_date_from . "' ";
		}
		if ($entry_date_to) {
			$entrydateto = " and sales_orders.created_at<='" . $entry_date_to . "' ";
		}

		//$company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto

		//echo $style; die;

		$str2 = date('Y-m-d');
		$yesterday = date('Y-m-d', strtotime('-1 days', strtotime($str2)));
		$itemcomplexity = array_prepend(config('bprs.gmtcomplexity'), '-Select-', '');
		$buyinghouses = array_prepend(array_pluck($this->buyernature->getBuyingHouses(), 'name', 'id'), '-Select-', 0);

		$rows = $this->style
			->selectRaw(
				'styles.id as style_id,
			styles.style_ref,
			styles.flie_src,
			styles.buying_agent_id,
			styles.contact,
			buyers.id as buyer_id,
			buyers.name as buyer_name,
			uoms.code as uom_name,
			seasons.name as season_name,
			teams.name as team_name,
			teamleadernames.id as teamleader_id,
			teamleadernames.name as team_name,
			users.id as user_id,
			users.name as team_member_name,
			productdepartments.department_name,
			jobs.job_no,
			companies.code as company_code,
			produced_company.code as produced_company_code,
			sales_orders.id as sale_order_id,
			sales_orders.sale_order_no,
			sales_orders.receive_date as sale_order_receive_date,
			sales_orders.internal_ref,
			sales_orders.ship_date,
			sales_orders.order_status,
			sales_orders.created_at,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			bookedsmv.smv,
			bookedsmv.booked_minute,
			bookedsmv.sewing_effi_per,
			explcsc.lc_sc_no,
			salesorder_enteredby.name as order_created_by
		'
			)
			->join('buyers', function ($join) {
				$join->on('styles.buyer_id', '=', 'buyers.id');
			})
			->join('uoms', function ($join) {
				$join->on('styles.uom_id', '=', 'uoms.id');
			})
			->leftJoin('seasons', function ($join) {
				$join->on('styles.season_id', '=', 'seasons.id');
			})
			->join('teams', function ($join) {
				$join->on('styles.team_id', '=', 'teams.id');
			})
			->leftJoin('teammembers', function ($join) {
				$join->on('styles.factory_merchant_id', '=', 'teammembers.id');
			})
			->leftJoin('users', function ($join) {
				$join->on('users.id', '=', 'teammembers.user_id');
			})
			->leftJoin('productdepartments', function ($join) {
				$join->on('productdepartments.id', '=', 'styles.productdepartment_id');
			})
			->leftJoin('teammembers as teamleaders', function ($join) {
				$join->on('styles.teammember_id', '=', 'teamleaders.id');
			})
			->leftJoin('users as teamleadernames', function ($join) {
				$join->on('teamleadernames.id', '=', 'teamleaders.user_id');
			})
			->join('jobs', function ($join) {
				$join->on('jobs.style_id', '=', 'styles.id');
			})
			->join('companies', function ($join) {
				$join->on('companies.id', '=', 'jobs.company_id');
			})
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.job_id', '=', 'jobs.id');
			})
			->join('users as salesorder_enteredby', function ($join) {
				$join->on('salesorder_enteredby.id', '=', 'sales_orders.created_by');
			})
			->leftJoin('companies as produced_company', function ($join) {
				$join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
			})
			->leftJoin(\DB::raw('(
			select count(exp_lc_scs.lc_sc_no) as lc_sc_no,
			sales_orders.sale_order_no,exp_pi_orders.sales_order_id 
			from exp_pi_orders
			join sales_orders on sales_orders.id=exp_pi_orders.sales_order_id
			join exp_pis on exp_pis.id=exp_pi_orders.exp_pi_id
			join exp_lc_sc_pis on exp_lc_sc_pis.exp_pi_id=exp_pis.id
			join exp_lc_scs on exp_lc_scs.id=exp_lc_sc_pis.exp_lc_sc_id group by exp_pi_orders.sales_order_id,sales_orders.sale_order_no 
			) explcsc'), "explcsc.sales_order_id", "=", "sales_orders.id")
			->leftJoin(\DB::raw('(select 
			m.sales_order_id,
			avg(m.smv) as smv,
			avg(m.sewing_effi_per) as sewing_effi_per,
			sum(m.booked_minute) as booked_minute
			from 
			(
			SELECT 
			sales_orders.id as sales_order_id,
			style_gmts.smv,
			style_gmts.sewing_effi_per,
			sales_order_gmt_color_sizes.qty as qty,
			sales_order_gmt_color_sizes.qty * style_gmts.smv as booked_minute
			FROM sales_orders 
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			where  1=1) m group by m.sales_order_id) 
		bookedsmv'), "bookedsmv.sales_order_id", "=", "sales_orders.id")
			->when(request('buyer_id'), function ($q) {
				return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
			})
			->when(request('style_ref'), function ($q) {
				return $q->where('styles.style_ref', 'like', '%' . request('style_ref', 0) . '%');
			})
			->when(request('style_id'), function ($q) {
				return $q->where('styles.id', '=', request('style_id', 0));
			})
			->when(request('factory_merchant_id'), function ($q) {
				return $q->where('styles.factory_merchant_id', '=', request('factory_merchant_id', 0));
			})
			->when(request('company_id'), function ($q) {
				return $q->where('jobs.company_id', '=', request('company_id', 0));
			})
			->when(request('produced_company_id'), function ($q) {
				return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id', 0));
			})
			->when(request('job_no'), function ($q) {
				return $q->where('jobs.job_no', 'like', '%' . request('job_no', 0) . '%');
			})
			->when(request('date_from'), function ($q) {
				return $q->where('sales_orders.ship_date', '>=', request('date_from', 0));
			})
			->when(request('date_to'), function ($q) {
				return $q->where('sales_orders.ship_date', '<=', request('date_to', 0));
			})
			->when(request('receive_date_from'), function ($q) {
				return $q->where('sales_orders.receive_date', '>=', request('receive_date_from', 0));
			})
			->when(request('receive_date_to'), function ($q) {
				return $q->where('sales_orders.receive_date', '<=', request('receive_date_to', 0));
			})
			->when(request('entry_date_from'), function ($q) {
				return $q->where('sales_orders.created_at', '>=', request('entry_date_from', 0));
			})
			->when(request('entry_date_to'), function ($q) {
				return $q->where('sales_orders.created_at', '<=', request('entry_date_to', 0));
			})
			->when(request('order_status'), function ($q) {
				return $q->where('sales_orders.order_status', '=', request('order_status', 0));
			})
			->where([['sales_orders.order_status', '!=', 2]])
			->groupBy([
				'styles.id',
				'styles.style_ref',
				'styles.flie_src',
				'styles.buying_agent_id',
				'styles.contact',
				'buyers.id',
				'buyers.name',
				'uoms.code',
				'seasons.name',
				'teams.name',
				'teamleadernames.id',
				'teamleadernames.name',
				'users.id',
				'users.name',
				'productdepartments.department_name',
				'jobs.job_no',
				'companies.code',
				'produced_company.code',
				'sales_orders.id',
				'sales_orders.sale_order_no',
				'sales_orders.receive_date',
				'sales_orders.internal_ref',
				'sales_orders.ship_date',
				'sales_orders.order_status',
				'sales_orders.created_at',
				'bookedsmv.smv',
				'bookedsmv.sewing_effi_per',
				'bookedsmv.booked_minute',
				'explcsc.lc_sc_no',
				'salesorder_enteredby.name'
			])
			->orderby('sales_orders.ship_date')
			->get();

		//	dd($rows);die;

		$data = $rows->map(function ($rows) use ($itemcomplexity, $buyinghouses) {
			$receive_date = Carbon::parse($rows->sale_order_receive_date);
			$ship_date = Carbon::parse($rows->ship_date);
			$diff = $receive_date->diffInDays($ship_date);
			if ($diff > 1) {
				$diff .= " Days";
			} else {
				$diff .= " Day";
			}
			$rows->lead_time = $diff;

			$min_cut_qc_date = Carbon::parse($rows->min_cut_qc_date);
			$max_exfactory_date = Carbon::parse($rows->max_exfactory_date);
			$cuttoshipdays = $min_cut_qc_date->diffInDays($max_exfactory_date);
			if ($cuttoshipdays > 1) {
				$cuttoshipdays .= " Days";
			} else {
				$cuttoshipdays .= " Day";
			}
			if ($rows->order_status == 4) {
				$rows->cut_to_ship_days = $cuttoshipdays;
			} else {
				$rows->cut_to_ship_days = '--';
			}

			$rows->agent_name =	isset($buyinghouses[$rows->buying_agent_id]) ? $buyinghouses[$rows->buying_agent_id] : '';
			$rows->buying_agent_name = $rows->agent_name . ", " . $rows->contact;
			$rows->yet_to_ship_qty = $rows->ship_qty - $rows->qty;
			$rows->yet_to_ship_value = $rows->ship_value - $rows->amount;

			$rows->sale_order_receive_date = date('d-M-Y', strtotime($rows->sale_order_receive_date));
			$rows->delivery_date = date('d-M-Y', strtotime($rows->ship_date));
			$rows->delivery_month = date('M', strtotime($rows->ship_date));
			$rows->sale_order_entry_date = date('d-M-Y', strtotime($rows->created_at));
			$rows->sale_order_entry_time = date('h:i A', strtotime($rows->created_at));
			$rows->booked_minute = number_format($rows->booked_minute, 2);
			$rows->smv = number_format($rows->smv, 2);
			$rows->sewing_effi_per = number_format($rows->sewing_effi_per, 2);

			$rows->qty = number_format($rows->qty, '0', '.', ',');
			$rows->rate = number_format($rows->rate, '2', '.', ',');
			$rows->amount = number_format($rows->amount, '2', '.', ',');
			return $rows;
		});

		return $data;
	}

	public function reportData()
	{
		return response()->json($this->getData());
	}

	public function getDealMerchant()
	{
		$dlmerchant = $this->user
			->leftJoin('employee_h_rs', function ($join) {
				$join->on('users.id', '=', 'employee_h_rs.user_id');
			})
			->where([['user_id', '=', request('user_id', 0)]])
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
			->map(function ($dlmerchant) {
				$dlmerchant->date_of_join = date('d-M-Y', strtotime($dlmerchant->date_of_join));
				return $dlmerchant;
			});
		echo json_encode($dlmerchant);
	}

	public function getBuyingHouse()
	{
		$rows = $this->buyer
			->selectRaw(
				'buyers.id as buyer_id,
				buyers.name as buyer_name,
				buyer_branches.name as branch_name,
				buyer_branches.contact_person,
				buyer_branches.email,
				buyer_branches.designation,
				buyer_branches.address'
			)
			->leftJoin('buyer_branches', function ($join) {
				$join->on('buyer_branches.buyer_id', '=', 'buyers.id');
			})
			->where([['buyers.id', '=', request('buyer_id', 0)]])
			->get([
				'buyers.id as buyer_id',
				'buyers.name',
				'buyer_branches.name',
				'buyer_branches.contact_person',
				'buyer_branches.email',
				'buyer_branches.designation',
				'buyer_branches.address'
			]);
		echo json_encode($rows);
	}

	public function getOpFileSrc()
	{
		return response()->json($this->style
			->leftJoin('style_file_uploads', function ($join) {
				$join->on('style_file_uploads.style_id', '=', 'styles.id');
			})
			->where([['style_id', '=', request('style_id', 0)]])
			->get([
				'styles.id as style_id',
				'styles.style_ref',
				'style_file_uploads.*'
			]));
	}

	public function getLCSc()
	{
		$sale_order_id = request('sale_order_id', 0);
		$payterm = array_prepend(config('bprs.payterm'), '-Select-', '');
		$incoterm = array_prepend(config('bprs.incoterm'), '-Select-', '');
		$contractNature = array_prepend(array_only(config('bprs.contractNature'), [1, 3, 2]), '-Select-', '');


		$results = collect(
			\DB::select("
				select 
				exp_lc_scs.lc_sc_no,
				exp_lc_scs.lc_sc_date,
				exp_lc_scs.lc_sc_value,
				exp_lc_scs.file_no ,
				buyers.name as buyer_name,
				currencies.code as currency_code,
				exp_lc_scs.pay_term_id,
				exp_lc_scs.incoterm_id,
				exp_lc_scs.lc_sc_nature_id,
				exp_lc_scs.remarks
				from 
				exp_pi_orders
				join exp_pis on exp_pis.id=exp_pi_orders.exp_pi_id
				join exp_lc_sc_pis on exp_lc_sc_pis.exp_pi_id=exp_pis.id
				join exp_lc_scs on exp_lc_scs.id=exp_lc_sc_pis.exp_lc_sc_id
				join buyers on buyers.id=exp_lc_scs.buyer_id
				join currencies on currencies.id=exp_lc_scs.currency_id
				where exp_pi_orders.sales_order_id=?
          ", [$sale_order_id])
		)
			->map(function ($results) use ($payterm, $incoterm, $contractNature) {
				$results->pay_term = $results->pay_term_id ? $payterm[$results->pay_term_id] : '';
				$results->inco_term = $results->incoterm_id ? $incoterm[$results->incoterm_id] : '';
				$results->lc_nature = $results->lc_sc_nature_id ? $contractNature[$results->lc_sc_nature_id] : '';
				$results->lc_sc_date = date('d-M-Y', strtotime($results->lc_sc_date));
				$results->lc_sc_value = number_format($results->lc_sc_value, 2);
				return $results;
			});
		echo json_encode($results);
	}

	public function getOrderQty()
	{
		$company_id = request('company_id', 0);
		$produced_company_id = request('produced_company_id', 0);
		$buyer_id = request('buyer_id', 0);
		$style_ref = request('style_ref', 0);
		$style_id = request('style_id', 0);
		$factory_merchant_id = request('factory_merchant_id', 0);
		$order_status = request('order_status', 0);

		$date_from = request('date_from', 0);
		$date_to = request('date_to', 0);
		$receive_date_from = request('receive_date_from', 0);
		$receive_date_to = request('receive_date_to', 0);
		$sale_order_id = request('sale_order_id', 0);

		$company = null;
		$producedcompany = null;
		$buyer = null;
		$style = null;
		$styleid = null;
		$factorymerchant = null;
		$orderstatus = null;
		$datefrom = null;
		$dateto = null;
		$receivedatefrom = null;
		$receivedateto = null;
		$saleorderid = null;
		if ($company_id) {
			$company = " and jobs.company_id = $company_id ";
		}
		if ($produced_company_id) {
			$producedcompany = " and sales_orders.produced_company_id = $produced_company_id ";
		}
		if ($buyer_id) {
			$buyer = " and styles.buyer_id=$buyer_id ";
		}

		if ($style_ref) {
			$style = " and styles.style_ref like '%" . $style_ref . "%' ";
		}
		if ($style_id) {
			$styleid = " and styles.id = $style_id ";
		}
		if ($factory_merchant_id) {
			$factorymerchant = " and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if ($order_status) {
			$orderstatus = " and sales_orders.order_status = $order_status ";
		}
		if ($date_from) {
			$datefrom = " and sales_orders.ship_date>='" . $date_from . "' ";
		}
		if ($date_to) {
			$dateto = " and sales_orders.ship_date<='" . $date_to . "' ";
		}
		if ($receive_date_from) {
			$receivedatefrom = " and sales_orders.receive_date>='" . $receive_date_from . "' ";
		}
		if ($receive_date_to) {
			$receivedateto = " and sales_orders.receive_date<='" . $receive_date_to . "' ";
		}

		if ($sale_order_id) {
			$saleorderid = " and sales_orders.id = $sale_order_id ";
		}

		//$company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		$itemcomplexity = array_prepend(config('bprs.gmtcomplexity'), '-Select-', '');


		$results = \DB::select("
		select
		styles.id as style_id,
		styles.style_ref,
		styles.flie_src,
		styles.buying_agent_id,
		styles.contact,
		buyers.id as buyer_id,
		buyers.name as buyer_name,
		uoms.code as uom_name,
		seasons.name as season_name,
		teams.name as team_name,
		teamleadernames.id as teamleader_id,
		teamleadernames.name as team_name,
		users.id as user_id,
		users.name as team_member_name,
		productdepartments.department_name,
		jobs.job_no,
		companies.code as company_code,
		produced_company.code as produced_company_code,
		sales_orders.id as sale_order_id,
		sales_orders.sale_order_no,
		sales_orders.receive_date as sale_order_receive_date,
		sales_orders.internal_ref,
		sales_orders.ship_date,
		countries.name as country_name,
		item_accounts.item_description,
		colors.name as color_name,
		sizes.name as size_name,
		sales_order_gmt_color_sizes.qty as qty,
		sales_order_gmt_color_sizes.plan_cut_qty as plan_cut_qty,
		sales_order_gmt_color_sizes.rate as rate,
		sales_order_gmt_color_sizes.amount as amount,
		style_gmts.smv,
		style_gmts.item_complexity,
		(style_gmts.smv*sales_order_gmt_color_sizes.qty)  as booked_minute

		from
		styles
		join buyers on buyers.id=styles.buyer_id
		join uoms on uoms.id=styles.uom_id
		join seasons on seasons.id=styles.season_id
		join teams on teams.id=styles.team_id
		left join teammembers on teammembers.id=styles.factory_merchant_id
		left join users on users.id=teammembers.user_id
		left join productdepartments on productdepartments.id=styles.productdepartment_id
		left join teammembers  teamleaders on teamleaders.id=styles.teammember_id
		left join users teamleadernames on teamleadernames.id=teamleaders.user_id
		left join jobs on jobs.style_id=styles.id
		left join companies on companies.id=jobs.company_id
		left join sales_orders on sales_orders.job_id=jobs.id
		left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
		join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
		join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id
		join item_accounts on item_accounts.id = style_gmts.item_account_id
		join colors on colors.id = style_colors.color_id
		join sizes on sizes.id = style_sizes.size_id
		join countries on countries.id = sales_order_countries.country_id
		where sales_orders.order_status !=2  $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		order by 
        style_colors.sort_id,
        style_sizes.sort_id");
		$data = collect($results)
			->map(function ($data) use ($itemcomplexity) {
				$data->qty = number_format($data->qty, 0);
				$data->item_complexity = $itemcomplexity[$data->item_complexity];
				$data->rate = number_format($data->rate, 2);
				$data->amount = number_format($data->amount, 2);
				$data->smv = number_format($data->smv, 2);
				$data->booked_minute = number_format($data->booked_minute, 2);
				return $data;
			});
		echo json_encode($data);
	}

	public function getOrderStyle()
	{
		return response()->json($this->style->getAll()->map(function ($rows) {
			$rows->receivedate = date("d-M-Y", strtotime($rows->receive_date));
			$rows->buyer = $rows->buyer_name;
			$rows->deptcategory = $rows->dept_category_name;
			$rows->season = $rows->season_name;
			$rows->uom = $rows->uom_name;
			$rows->team = $rows->team_name;
			$rows->teammember = $rows->team_member_name;
			$rows->productdepartment = $rows->department_name;
			return $rows;
		}));
	}

	public function getTeamMemberDlm()
	{
		$membertype = array_prepend(config('bprs.membertype'), '-Select-', 0);
		$teammember = $this->teammember
			->join('users', function ($join) {
				$join->on('users.id', '=', 'teammembers.user_id');
			})
			->join('teams', function ($join) {
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
			->map(function ($teammember) use ($membertype) {
				$teammember->type_id = $membertype[$teammember->type_id];
				return $teammember;
			});
		echo json_encode($teammember);
	}
}
