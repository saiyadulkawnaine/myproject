<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;

use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use Illuminate\Support\Carbon;

class PendingShipmentController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $itemaccount;
	private $budget;
	private $embelishmenttype;
	private $salesordergmtcolorsize;
	public function __construct(StyleRepository $style,CompanyRepository $company,BuyerRepository $buyer,ItemAccountRepository $itemaccount,BudgetRepository $budget,EmbelishmentTypeRepository $embelishmenttype,SalesOrderGmtColorSizeRepository $salesordergmtcolorsize)
    {
		$this->style                     =$style;
		$this->company                   = $company;
		$this->buyer                     = $buyer;
		$this->itemaccount               = $itemaccount;
		$this->budget                    = $budget;
		$this->embelishmenttype          = $embelishmenttype;
		$this->salesordergmtcolorsize    = $salesordergmtcolorsize;
		$this->middleware('auth');
		$this->middleware('permission:view.pendingshipmentreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
        return Template::loadView('Report.PendingShipment',[]);
    }
    public function formatOne(){
    	echo $this->reportData()->map(function ($rows){
		$sale_order_receive_date = Carbon::parse($rows->sale_order_receive_date);
		$ship_date = Carbon::parse($rows->ship_date);
		$leadDays = $sale_order_receive_date->diffInDays($ship_date)+1;
		$today=Carbon::parse(date('Y-m-d'));
		$delayDays = $ship_date->diffInDays($today);
		$rows->sale_order_receive_date=date('d-M-Y',strtotime($rows->sale_order_receive_date));
		$rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
		$rows->lead_days=$leadDays;
		$rows->delay_days=$delayDays;
		$rows->ship_value=number_format($rows->ship_qty*$rows->rate,2,'.',',');
		$rows->balance=$rows->qty-$rows->ship_qty;
		$rows->ship_balance=$rows->qty-$rows->ship_qty;
		$rows->ship_balance_value=number_format($rows->ship_balance*$rows->rate,2,'.',',');
		$rows->ship_balance=number_format($rows->qty-$rows->ship_qty,2,'.',',');
		$rows->carton_qty=number_format($rows->carton_qty,0,'.',',');
		$rows->ship_qty=number_format($rows->ship_qty,0,'.',',');
		$rows->qty=number_format($rows->qty,0,'.',',');
		$rows->rate=number_format($rows->rate,2,'.',',');
		$rows->amount=number_format($rows->amount,2,'.',',');
		return $rows;
        })
        ->filter(function ($value) {
			if($value->balance>0)
			{
	           return $value;
			}
		})
		->values()
		->toJson();
    }

    public function reportData() {
    	$str2=request('date_to', 0);
        $date_to = date('Y-m-d', strtotime('-1 days', strtotime($str2)));
		$rows=$this->style
		->selectRaw(
		'styles.style_ref,
		styles.flie_src,
		buyers.name as buyer_name,
		uoms.code as uom_name,
		seasons.name as season_name,
		teams.name as team_name,
		users.name as team_member_name,
		productdepartments.department_name,
		jobs.job_no,
		companies.id as company_id,
		companies.code as company_code,
		produced_company.code as produced_company_code,
		sales_orders.id,
		sales_orders.sale_order_no,
		sales_orders.receive_date as sale_order_receive_date,
		sales_orders.internal_ref,
		sales_orders.ship_date,
		sales_orders.remarks,
		sum(sales_order_gmt_color_sizes.qty) as qty,
		avg(sales_order_gmt_color_sizes.rate) as rate,
		sum(sales_order_gmt_color_sizes.amount) as amount,
		carton.qty as carton_qty,
		exfactory.qty as ship_qty
		'
		)
		->leftJoin('buyers', function($join)  {
		$join->on('styles.buyer_id', '=', 'buyers.id');
		})
		->leftJoin('uoms', function($join)  {
		$join->on('styles.uom_id', '=', 'uoms.id');
		})
		->leftJoin('seasons', function($join)  {
		$join->on('styles.season_id', '=', 'seasons.id');
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
		->leftJoin('productdepartments', function($join)  {
		$join->on('productdepartments.id', '=', 'styles.productdepartment_id');
		})
		->leftJoin('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.job_id', '=', 'jobs.id');
		})
		->leftJoin('companies as companies', function($join)  {
		$join->on('companies.id', '=', 'jobs.company_id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('companies as produced_company', function($join)  {
		$join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		$join->whereNull('sales_order_gmt_color_sizes.deleted_at');
		})
		->leftJoin(\DB::raw("(SELECT sales_orders.id as sale_order_id,sum(style_pkg_ratios.qty) as qty FROM sales_orders  
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			join style_pkgs on style_pkgs.style_id = styles.id 
			join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
			and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id
			where sales_orders.order_status <> 4 
			and  sales_orders.order_status <> 2 
			and sales_orders.ship_date <= '".$date_to."'
			group by sales_orders.id) carton"), "carton.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT sales_orders.id as sale_order_id,sum(style_pkg_ratios.qty) as qty FROM sales_orders  
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			join style_pkgs on style_pkgs.style_id = styles.id 
			join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
			and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id 
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
			where sales_orders.order_status <> 4 
			and  sales_orders.order_status <> 2
			and sales_orders.ship_date <= '".$date_to."'
			group by sales_orders.id) exfactory"), "exfactory.sale_order_id", "=", "sales_orders.id")

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when(request('date_to'), function ($q) use ($date_to){
		return $q->where('sales_orders.ship_date', '<=',$date_to);
		})
		->where([['sales_orders.order_status','!=',4]])
		->where([['sales_orders.order_status','!=',2]])
		->groupBy([
		'styles.style_ref',
		'styles.flie_src',
		'buyers.name',
		'uoms.code',
		'seasons.name',
		'teams.name',
		'users.name',
		'productdepartments.department_name',
		'jobs.job_no',
		'companies.id',
		'companies.code',
		'produced_company.code',
		'sales_orders.id',
		'sales_orders.sale_order_no',
		'sales_orders.receive_date',
		'sales_orders.internal_ref',
		'sales_orders.ship_date',
		'sales_orders.remarks',
		'carton.qty',
		'exfactory.qty'
		])
		->orderBy('sales_orders.ship_date')
		->get();
		return $rows;
    }
}