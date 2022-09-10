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
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use App\Repositories\Contracts\Util\SewingCapacityRepository;
use App\Repositories\Contracts\Util\SewingCapacityDateRepository;

class MonthlySewingCapacityReportController extends Controller
{
 private $subsection;
 private $wstudylinesetup;
 private $prodgmtsewing;
 private $sewingcapacity;
 private $sewingcapacitydate;
 private $buyer;
 private $company;
 private $supplier;
 private $location;
 public function __construct(
  SewingCapacityRepository $sewingcapacity,
  SewingCapacityDateRepository $sewingcapacitydate,
  SubsectionRepository $subsection,
  WstudyLineSetupRepository $wstudylinesetup,
  ProdGmtSewingRepository $prodgmtsewing,
  CompanyRepository $company,
  LocationRepository $location,
  SupplierRepository $supplier,
  BuyerRepository $buyer
 ) {
  $this->sewingcapacity           = $sewingcapacity;
  $this->sewingcapacitydate       = $sewingcapacitydate;
  $this->subsection               = $subsection;
  $this->wstudylinesetup          = $wstudylinesetup;
  $this->prodgmtsewing            = $prodgmtsewing;
  $this->company = $company;
  $this->buyer = $buyer;
  $this->location = $location;
  $this->supplier = $supplier;
  $this->middleware('auth');
  //$this->middleware('permission:view.prodgmtcartonqtyreports',   ['only' => ['create', 'index','show']]);
 }

 public function index()
 {
  $company = array_prepend(array_pluck($this->company->orderBy('name')->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');
  $months = array_prepend(config('bprs.months'), '-Select-', '');
  $years = array_prepend(config('bprs.years'), '-Select-', '');
  //$first_selected_month=date('n')+1;
  $selected_year = date('Y');

  return Template::loadView('Report.GmtProduction.MonthlySewingCapacityReport', ['company' => $company, 'location' => $location, 'productionsource' => $productionsource, 'months' => $months, 'years' => $years, 'selected_year' => $selected_year]);
 }

 public function reportData()
 {
  $company_id = request('company_id', 0);
  $location_id = request('location_id', 0);
  $prod_source_id = request('prod_source_id', 0);
  $year = request('year', 0);
  $month_from = request('month_from', 0);
  $month_to = request('month_to', 0);
  $first_date = date('Y-m-d', strtotime($year . '-' . str_pad($month_from, 2, "0", STR_PAD_LEFT) . '-01'));
  $first_date_last_month = date('Y-m-d', strtotime($year . '-' . str_pad($month_to, 2, "0", STR_PAD_LEFT) . '-01'));
  $last_date = date("Y-m-t", strtotime($first_date_last_month));

  $companyId = null;
  $locationId = null;
  $prodsource = null;
  $monthfrom = null;
  $monthto = null;

  if ($month_from) {
   $monthfrom = " and sewing_capacity_dates.capacity_date >='" . $first_date . "' ";
  }
  if ($month_to) {
   $monthto = " and sewing_capacity_dates.capacity_date <='" . $last_date . "' ";
  }

  if ($company_id) {
   $companyId = " and sewing_capacities.company_id = $company_id ";
  }
  if ($location_id) {
   $locationId = " and sewing_capacities.location_id = $location_id ";
  }
  if ($prod_source_id) {
   $prodsource = " and sewing_capacities.prod_source_id = $prod_source_id ";
  }

  $company = array_prepend(array_pluck($this->company->orderBy('name')->get(), 'name', 'id'), '-Select-', '');
  $months = array_prepend(config('bprs.months'), '-Select-', '');
  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');

  $results = $this->sewingcapacity
   ->join('sewing_capacity_dates', function ($join) {
    $join->on('sewing_capacities.id', '=', 'sewing_capacity_dates.sewing_capacity_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'sewing_capacities.company_id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'sewing_capacities.location_id');
   }) //->whereMonth('created_at', '12')
   ->when($month_from, function ($q) use ($month_from) {
    return $q->whereMonth('sewing_capacity_dates.capacity_date', '>=', $month_from);
   })
   ->when($month_to, function ($q) use ($month_to) {
    return $q->whereMonth('sewing_capacity_dates.capacity_date', '<=', $month_to);
   })
   ->when(request('year', 0), function ($q) use ($year) {
    return $q->where('sewing_capacities.year', '=', $year);
   })
   ->when(request('company_id', 0), function ($q) use ($company_id) {
    return $q->where('sewing_capacities.company_id', '=', $company_id);
   })
   ->when(request('location_id', 0), function ($q) use ($location_id) {
    return $q->where('sewing_capacities.location_id', '=', $location_id);
   })
   ->when(request('prod_source_id', 0), function ($q) use ($prod_source_id) {
    return $q->where('sewing_capacities.prod_source_id', '=', $prod_source_id);
   })
   ->selectRaw('
        sewing_capacities.company_id,
        sewing_capacities.location_id,
        sewing_capacities.prod_source_id,
        sewing_capacities.year,
        companies.name as company_name,
        locations.name as location_name,
        sum(sewing_capacity_dates.mkt_cap_mint) as marketing_minute,
        (sum(sewing_capacity_dates.mkt_cap_mint)/60) as marketing_hour,
        sum(sewing_capacity_dates.mkt_cap_pcs) as marketing_basic_qty,
        sum(sewing_capacity_dates.prod_cap_mint) as prod_minute,
        (sum(sewing_capacity_dates.prod_cap_mint)/60) as prod_hour,
        sum(sewing_capacity_dates.prod_cap_pcs) as prod_basic_qty
    ')
   ->groupBy([
    'sewing_capacities.company_id',
    'sewing_capacities.location_id',
    'sewing_capacities.prod_source_id',
    'sewing_capacities.year',
    'companies.name',
    'locations.name',
   ])
   ->orderBy('sewing_capacities.company_id')
   ->get()
   ->map(function ($results) use ($productionsource) {
    $results->prod_source_id = $productionsource[$results->prod_source_id];
    return $results;
   });

  $capacity = collect(
   \DB::select("
            select
            m.company_id,
            m.company_name,
            m.location_name,
            m.prod_source_id,
            m.year,
            m.sew_month,
            m.cap_month_no,
            sum(m.mkt_cap_mint) as marketing_minute,
            (sum(m.mkt_cap_mint)/60) as marketing_hour,
            sum(m.mkt_cap_pcs) as marketing_basic_qty,
            sum(m.prod_cap_mint) as prod_minute,
            (sum(m.prod_cap_mint)/60) as prod_hour,
            sum(m.prod_cap_pcs) as prod_basic_qty
            from 
                (
                SELECT
                    sewing_capacities.company_id,
                    sewing_capacities.location_id,
                    sewing_capacities.prod_source_id,
                    sewing_capacities.year,
                    companies.name as company_name,
                    locations.name as location_name,
                    to_char(sewing_capacity_dates.capacity_date, 'Month') as sew_month,
                    to_char(sewing_capacity_dates.capacity_date, 'MM') as cap_month_no,
                    to_char(sewing_capacity_dates.capacity_date, 'yy') as cap_year,
                    sewing_capacity_dates.mkt_cap_mint,
                    sewing_capacity_dates.mkt_cap_pcs,
                    sewing_capacity_dates.prod_cap_mint,
                    sewing_capacity_dates.prod_cap_pcs
                    FROM sewing_capacities
                    join sewing_capacity_dates on sewing_capacity_dates.sewing_capacity_id = sewing_capacities.id
                    join companies on companies.id=sewing_capacities.company_id
                    left join locations on locations.id=sewing_capacities.location_id
                    where sewing_capacity_dates.day_status=1
                    $monthfrom $monthto $companyId $locationId $prodsource
                )m
            group by
            m.company_id,
            m.company_name,
            m.location_name,
            m.prod_source_id,
            m.year,
            m.sew_month,
            m.cap_month_no
            order by m.company_id,m.cap_month_no
        ")
  )->map(function ($capacity) use ($productionsource) {
   $capacity->prod_source_id = $productionsource[$capacity->prod_source_id];
   return $capacity;
  });

  $datas = $capacity->groupBy('company_id');
  return Template::loadView('Report.GmtProduction.MonthlySewingCapacityReportMatrix', ['results' => $results, 'months' => $months, 'month_from' => $month_from, 'month_to' => $month_to, 'datas' => $datas, 'company' => $company]);
 }
}
