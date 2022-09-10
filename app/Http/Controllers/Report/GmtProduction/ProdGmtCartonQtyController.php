<?php
namespace App\Http\Controllers\Report\GmtProduction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonEntryRepository;

class ProdGmtCartonQtyController extends Controller
{
	private $buyer;
	private $company;
	private $supplier;
	private $location;
	public function __construct(ProdGmtCartonEntryRepository $prodgmtcarton, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier, BuyerRepository $buyer)
    {
        $this->prodgmtcarton = $prodgmtcarton;
		$this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
		$this->middleware('auth');
		
		//$this->middleware('permission:view.prodgmtcartonqtyreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
		$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $ordersource=array_prepend(config('bprs.ordersource'),'-Select-','');
      return Template::loadView('Report.GmtProduction.ProdGmtCartonQty',['location'=> $location,'productionsource'=> $productionsource,'shiftname'=> $shiftname,'ordersource'=>$ordersource,'company'=>$company,'supplier'=>$supplier,'buyer'=>$buyer]);
    }
	public function reportData() {
      $data = $this->prodgmtcarton
        ->selectRaw(   
        'prod_gmt_carton_details.prod_gmt_carton_entry_id,
           prod_gmt_carton_entries.company_id,
           sales_orders.produced_company_id,
           produced_company.name,
           prod_gmt_carton_entries.buyer_id,
           teams.name as team_name,
           users.name as team_member_name,
           buyers.code as buyer_name,         
           companies.code as company_name,
           styles.style_ref,
           sales_orders.sale_order_no,              
           style_gmts.item_account_id,
           item_accounts.item_description,
           gmtCarton.no_of_carton,
           stylepkgratios.carton_qty,
           avg(sales_order_gmt_color_sizes.rate) as carton_rate,
           prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id,
           shipout.shipped_gmt_qty,
           shipout.shipped_gmt_rate,
           shipout.shipped_carton_no
        ')
        /* 
        prod_gmt_carton_entries.id,
        styles.id as style_id,
           produced_company.name as produced_company_name,
           sales_order_countries.id,
           sales_order_countries.country_id,
           gmtCarton.no_of_carton,
           item_accounts.item_description,
           styles.id as style_id, 
            */
           ->join('companies', function($join)  {
                $join->on('companies.id', '=', 'prod_gmt_carton_entries.company_id');
            })
            ->join('buyers', function($join)  {
                $join->on('buyers.id', '=', 'prod_gmt_carton_entries.buyer_id');
            })
            ->join('suppliers', function($join)  {
                $join->on('suppliers.id', '=', 'prod_gmt_carton_entries.supplier_id');
            })
            ->join('locations', function($join)  {
                $join->on('locations.id', '=', 'prod_gmt_carton_entries.location_id');
            })
		    ->rightJoin('prod_gmt_carton_details', function($join) {
                $join->on('prod_gmt_carton_details.prod_gmt_carton_entry_id', '=', 'prod_gmt_carton_entries.id');
            })
             ->leftJoin(\DB::raw("(SELECT prod_gmt_carton_details.prod_gmt_carton_entry_id, count (prod_gmt_carton_details.id) as no_of_carton FROM prod_gmt_carton_details right join prod_gmt_carton_entries on prod_gmt_carton_entries.id=prod_gmt_carton_details.prod_gmt_carton_entry_id group by prod_gmt_carton_details.prod_gmt_carton_entry_id) gmtCarton"), "gmtCarton.prod_gmt_carton_entry_id", "=", "prod_gmt_carton_details.prod_gmt_carton_entry_id")
            
             ->join(\DB::raw("(SELECT style_pkgs.id as style_pkg_id,sum(style_pkg_ratios.qty) as carton_qty FROM style_pkgs join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id   group by style_pkgs.id ) stylepkgratios"), "stylepkgratios.style_pkg_id", "=", "prod_gmt_carton_details.style_pkg_id")
             
            ->join('sales_order_countries', function($join) {
                $join->on('sales_order_countries.id', '=', 'prod_gmt_carton_details.sales_order_country_id');
            })             
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
            ->join('sales_order_gmt_color_sizes', function($join)  {
                $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
            })
            ->join('style_gmts',function($join){
                $join->on('style_gmts.id','=','sales_order_gmt_color_sizes.style_gmt_id');
            })
            ->join('item_accounts', function($join) {
                $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
            })

            ->join('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
           ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->join('teams', function($join)  {
                $join->on('styles.team_id', '=', 'teams.id');
            })
            ->join('teammembers', function($join)  {
                $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
            })
            ->join('users', function($join)  {
                $join->on('users.id', '=', 'teammembers.user_id');
            })
             ->join('prod_gmt_ex_factory_qties', function($join)  {
                $join->on('prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id', '=', 'prod_gmt_carton_details.id');
            })
            ->join('prod_gmt_ex_factories', function($join)  {
                $join->on('prod_gmt_ex_factories.id', '=', 'prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id');
                $join->whereNull('prod_gmt_ex_factory_qties.deleted_at');
            })
            ->join(\DB::raw("(select prod_gmt_carton_detail_id , count(prod_gmt_ex_factory_id) as shipped_carton_no, sum (sales_order_gmt_color_sizes.qty) as shipped_gmt_qty, avg(sales_order_gmt_color_sizes.rate) as shipped_gmt_rate from prod_gmt_ex_factory_qties join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id join prod_gmt_carton_details on prod_gmt_carton_details.id=prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id join sales_order_countries on sales_order_countries.id=prod_gmt_carton_details.sales_order_country_id join sales_orders on sales_orders.id=sales_order_countries.sale_order_id join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id group by prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id, prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id) shipout"), "shipout.prod_gmt_carton_detail_id","=","prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id")
            /*->join(\DB::raw("(select prod_gmt_ex_factory_id , count(prod_gmt_ex_factory_id) as shipped_carton_no, sum(sales_order_gmt_color_sizes.qty) as shipped_gmt_qty, avg(sales_order_gmt_color_sizes.rate) as shipped_gmt_rate from prod_gmt_ex_factory_qties
join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id
join prod_gmt_carton_details on prod_gmt_carton_details.id=prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id
left join prod_gmt_carton_entries on prod_gmt_carton_entries.id=prod_gmt_carton_details.prod_gmt_carton_entry_id
join sales_order_countries on sales_order_countries.id=prod_gmt_carton_details.sales_order_country_id
join sales_orders on sales_orders.id=sales_order_countries.sale_order_id
join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
group by prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id) shipout"),
       "shipout.prod_gmt_ex_factory_id","=","prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id "    
        )*/
            //->where([['prod_gmt_carton_entry_id',request('prod_gmt_carton_entry_id',0)]])
            ->when(request('company_id'), function ($q) {
             return $q->where('prod_gmt_carton_entries.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('prod_gmt_carton_entries.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('supplier_id'), function ($q) {
                return $q->where('prod_gmt_carton_entries.supplier_id', '=', request('supplier_id', 0));
            })
            ->when(request('location_id'), function ($q) {
                return $q->where('prod_gmt_carton_entries.location_id', '=', request('location_id', 0));
            })
            ->when(request('order_source_id'), function ($q) {
                return $q->where('prod_gmt_carton_entries.order_source_id', '=', request('order_source_id', 0));
            })
            ->when(request('prod_source_id'), function ($q) {
                return $q->where('prod_gmt_carton_entries.prod_source_id', '=', request('prod_source_id', 0));
            })
            ->when(request('shiftname_id'), function ($q) {
                return $q->where('prod_gmt_carton_entries.shiftname_id', '=', request('shiftname_id', 0));
            })
		   ->when(request('date_from'), function ($q) {
			return $q->where('prod_gmt_carton_entries.carton_date', '>=',request('date_from', 0));
		   })
		   ->when(request('date_to'), function ($q) {
			return $q->where('prod_gmt_carton_entries.carton_date', '<=',request('date_to', 0));
		   })	   
		   //->orderBy('prod_gmt_carton_entries.carton_date','desc')
		   ->groupBy([
                'prod_gmt_carton_details.prod_gmt_carton_entry_id',
                'prod_gmt_carton_entries.company_id',
                'sales_orders.produced_company_id',
                'produced_company.name',
                'prod_gmt_carton_entries.buyer_id',
                'teams.name',
                'users.name',
                'buyers.code',
                'companies.code',
                'styles.style_ref',
                'sales_orders.sale_order_no',
                'style_gmts.item_account_id',
                'item_accounts.item_description',
                'gmtCarton.no_of_carton',
                'stylepkgratios.carton_qty',
                'prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id',
                'shipout.shipped_gmt_qty',
                'shipout.shipped_gmt_rate',
                'shipout.shipped_carton_no'

      ]) 
        
        ->get()
        ->map(function($data){
            $data->carton_amount=number_format($data->carton_qty*$data->carton_rate,2,'.',',');
            $data->carton_qty=number_format($data->carton_qty,2,'.',',');
            $data->shipped_gmt_amount=number_format($data->shipped_gmt_qty*$data->shipped_gmt_rate,2,'.',',');
            $data->shipped_gmt_qty=number_format($data->shipped_gmt_qty,2,'.',',');
            return $data;
        });
	
		echo json_encode($data);
    }
}
