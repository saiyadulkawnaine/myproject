<?php
namespace App\Http\Controllers\Report\GmtProduction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryRepository;

class ProdGmtDailyExFactoryReportController extends Controller
{
  private $prodgmtsewing;
	private $buyer;
	private $company;
    private $supplier;
    private $exfactory;
	public function __construct(
    SalesOrderRepository $salesorder,
    CompanyRepository $company, 
    BuyerRepository $buyer,
    SupplierRepository $supplier,
    ProdGmtExFactoryRepository $exfactory
  )
    {

      $this->salesorder = $salesorder;
      $this->company = $company;
      $this->buyer = $buyer;
      $this->supplier = $supplier;
      $this->exfactory = $exfactory;

      $this->middleware('auth');
		//$this->middleware('permission:view.prodgmtdailyexfactoryreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');
      return Template::loadView('Report.GmtProduction.ProdGmtDailyExFactoryReport',['company'=>$company,'buyer'=>$buyer,'supplier'=>$supplier]);
    }
	public function reportData() {
        $date_from =request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $produced_company_id=request('produced_company_id',0);
            $dailyexfactory=$this->exfactory
            ->selectRaw('  
                sales_orders.id as sales_order_id,
                sales_orders.sale_order_no,
                sales_orders.produced_company_id,
                sales_orders.ship_date,
                prod_gmt_ex_factories.id,
                prod_gmt_ex_factories.exfactory_date,
                styles.style_ref,
                styles.flie_src,
                buyers.name as buyer_name,
                users.name as dl_marchent,
                jobs.job_no,
                bcompany.code as company_code,
                companies.name as pcompany_code,
                saleorders.rate,
                exp_invoices.invoice_no,
                sum(style_pkg_ratios.qty) as exfactory_qty , 
                sum(style_pkg_ratios.qty)*saleorders.rate as exfactory_amount
            ')
            ->join('prod_gmt_ex_factory_qties', function($join) use($date_to)  {
                $join->on( 'prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id', '=', 'prod_gmt_ex_factories.id' );
                $join->whereNull('prod_gmt_ex_factory_qties.deleted_at');
            })
            ->join('prod_gmt_carton_details', function($join) {
                $join->on('prod_gmt_carton_details.id', '=', 'prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id');
            })
            ->join('prod_gmt_carton_entries', function($join) {
                $join->on('prod_gmt_carton_entries.id', '=', 'prod_gmt_carton_details.prod_gmt_carton_entry_id');
            })
            ->join('style_pkgs',function($join){
                $join->on('style_pkgs.id', '=' , 'prod_gmt_carton_details.style_pkg_id');
            })
            ->join('style_pkg_ratios', function($join) {
                $join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
            })
            ->join('sales_order_countries', function($join) {
                $join->on('sales_order_countries.id', '=', 'prod_gmt_carton_details.sales_order_country_id');
            }) 
            ->join('sales_orders', function($join)  {
                $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
             ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
                $join->on('styles.id', '=', 'style_pkgs.style_id');
            })
            ->join('companies as bcompany', function($join)  {
                $join->on('bcompany.id', '=', 'jobs.company_id');
            })
            ->leftJoin('companies', function($join)  {
                $join->on('companies.id', '=', 'sales_orders.produced_company_id');
            })
           
            ->leftJoin('teammembers', function($join)  {
                $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
            })
            ->leftJoin('users', function($join)  {
                $join->on('users.id', '=', 'teammembers.user_id');
            })
            ->leftJoin('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->leftJoin('exp_invoices',function($join){
                $join->on('exp_invoices.id','=','prod_gmt_ex_factories.exp_invoice_id');
            })
            // ->leftJoin(\DB::raw("(
            //     SELECT 
            //     sales_orders.id as sale_order_id,
            //     avg(sales_order_gmt_color_sizes.rate) as rate 
            //     FROM prod_gmt_carton_entries
            //     join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
            //     join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
            //     join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            //     join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            //     join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
            //     join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null
            //     and sales_order_gmt_color_sizes.deleted_at is null
            //     and sales_order_gmt_color_sizes.qty >0
            //     group by sales_orders.id) saleorders"), "saleorders.sale_order_id", "=", "sales_orders.id") 
            ->leftJoin(\DB::raw("(
                SELECT 
                    sales_orders.id as sale_order_id,
                    avg(sales_order_gmt_color_sizes.rate) as rate 
                FROM sales_orders
                    join sales_order_countries on sales_orders.id = sales_order_countries.sale_order_id
                    join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                    where sales_order_gmt_color_sizes.deleted_at is null
                    and sales_order_gmt_color_sizes.qty >0 
                    group by sales_orders.id
            ) saleorders"), "saleorders.sale_order_id", "=", "sales_orders.id") 
            ->when($date_from, function ($q) use($date_from){
                return $q->where('prod_gmt_ex_factories.exfactory_date', '>=',$date_from);
            })
            ->when($date_to, function ($q) use($date_to) {
                return $q->where('prod_gmt_ex_factories.exfactory_date', '<=',$date_to);
            })
            ->when(request('produced_company_id'), function ($q) {
                return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id', 0));
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('jobs.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('style_ref'), function ($q) {
                return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
            })
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->orderBY('sales_orders.id')
            ->groupBy([
                'sales_orders.id',
                'sales_orders.sale_order_no',
                'sales_orders.produced_company_id',
                'sales_orders.ship_date',
                'prod_gmt_ex_factories.id',
                'prod_gmt_ex_factories.exfactory_date',
                'styles.style_ref',
                'styles.flie_src',
                'buyers.name',
                'users.name',
                'jobs.job_no',
                'bcompany.code',
                'companies.name',
                'saleorders.rate',
                'exp_invoices.invoice_no',
            ])
            ->get()
            ->map(function($dailyexfactory) use ($date_to){
                $dailyexfactory->ship_date=date('d-M-Y',strtotime($dailyexfactory->ship_date));
                $dailyexfactory->exfactory_date = date('d-M-Y',strtotime($dailyexfactory->exfactory_date));
                $dailyexfactory->challan_no = str_pad($dailyexfactory->id,10,0,STR_PAD_LEFT );
                $dailyexfactory->exfactory_qty=number_format($dailyexfactory->exfactory_qty,0);
                $dailyexfactory->exfactory_amount=number_format($dailyexfactory->exfactory_amount,2);
                $delayDays = (strtotime($dailyexfactory->ship_date) - strtotime($dailyexfactory->exfactory_date)) / (60 * 60 * 24);
                $dailyexfactory->delayDays=number_format($delayDays,0);
            
            return $dailyexfactory;
            });
            echo json_encode($dailyexfactory);
        
    }

}
