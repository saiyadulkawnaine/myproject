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

class TodayShipmentController extends Controller
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
		$this->middleware('permission:view.todayshipmentreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
        return Template::loadView('Report.TodayShipment',[]);
    }
    

    public function formatOne(){
    	$rows=$this->reportData();
        $itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
        $styles=array();
		foreach($rows as $row){
			$style['id']=	$row->id;
			$style['receivedate']=	$row->receive_date;
			$style['style_ref']=	$row->style_ref;
			$style['flie_src']=	$row->flie_src;
			$style['buyer']=	$row->buyer_name;
			$style['season']=	$row->season_name;
			$style['uom_name']=	$row->uom_name;
			$style['team']=	$row->team_name;
			$style['teammember']=	$row->team_member_name;
			$style['productdepartment']=$row->department_name;
			$style['company_code']=$row->company_code;
			$style['produced_company_code']=$row->produced_company_code;
			$style['sale_order_no']=$row->sale_order_no;
			$style['sale_order_receive_date']=date('d-M-Y',strtotime($row->sale_order_receive_date));
			$style['remarks']=$row->remarks;

			
			$style['internal_ref']=$row->internal_ref;
			$style['delivery_date']=date('d-M-Y',strtotime($row->ship_date));
			$style['delivery_month']=date('M',strtotime($row->ship_date));
			$style['item_description']=$row->item_description;
			$style['item_complexity']=$itemcomplexity[$row->item_complexity];
			$style['qty']=number_format($row->qty,'0','.',',');
			$style['rate']=number_format($row->rate,'4','.',',');
			$style['amount']=number_format($row->amount,'2','.',',');
			array_push($styles,$style);
		}
		echo json_encode($styles);
    }

    public function reportData() {
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
		sum(sales_order_gmt_color_sizes.amount) as amount
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
		$join->on('budgets.style_id', '=', 'styles.id');
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
		->when(request('date_from'), function ($q) {
		return $q->where('sales_orders.ship_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
		return $q->where('sales_orders.ship_date', '<=',request('date_to', 0));
		})
		/*->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})*/
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
		'sales_orders.remarks'
		])
		->orderBy('sales_orders.ship_date')
		->get();
		return $rows;
    }
}