<?php

namespace App\Http\Controllers\Report\FAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetServiceRepairRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\FAMS\AssetTechnicalFeatureRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\FAMS\AssetBreakdownRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Util\BuyerRepository;

class AssetRepairBackReportController extends Controller
{
 private $assetacquisition;
 private $assetservicerepair;
 private $assetbreakdown;
 private $employeehr;
 private $assetquantitycost;
 private $company;
 private $location;
 private $uom;
 private $supplier;
 private $assettechfeature;
 private $itemaccount;
 private $itemclass;
 private $itemcategory;
 private $store;
 private $division;
 private $section;
 private $subsection;
 private $buyer;
 public function __construct(
  AssetAcquisitionRepository $assetacquisition,
  AssetServiceRepairRepository $assetservicerepair,
  CompanyRepository $company,
  LocationRepository $location,
  UomRepository $uom,
  SupplierRepository $supplier,
  AssetTechnicalFeatureRepository $assettechfeature,
  ItemAccountRepository $itemaccount,
  ItemclassRepository $itemclass,
  ItemcategoryRepository $itemcategory,
  StoreRepository $store,
  AssetQuantityCostRepository $assetquantitycost,
  EmployeeHRRepository $employeehr,
  DesignationRepository $designation,
  DepartmentRepository $department,
  DivisionRepository $division,
  SectionRepository $section,
  SubsectionRepository $subsection,
  BuyerRepository $buyer,
  AssetBreakdownRepository $assetbreakdown
 ) {
  $this->assetacquisition = $assetacquisition;
  $this->assetquantitycost = $assetquantitycost;
  $this->assetbreakdown = $assetbreakdown;
  $this->employeehr = $employeehr;
  $this->assetservicerepair = $assetservicerepair;
  $this->company = $company;
  $this->location = $location;
  $this->uom = $uom;
  $this->supplier = $supplier;
  $this->assettechfeature = $assettechfeature;
  $this->itemaccount = $itemaccount;
  $this->itemclass = $itemclass;
  $this->itemcategory = $itemcategory;
  $this->store = $store;
  $this->department = $department;
  $this->division = $division;
  $this->section = $section;
  $this->subsection = $subsection;
  $this->designation = $designation;
  $this->buyer = $buyer;

  $this->middleware('auth');
  //$this->middleware('permission:view.prodgmtcartonqtyreports',   ['only' => ['create', 'index','show']]);
 }
 public function index()
 {
  $company = array_prepend(array_pluck($this->company->orderBy('name')->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $assetName = array_prepend(array_pluck($this->assetacquisition->orderBy('name')->get(), 'name', 'id'), '-Select-', '');
  $assetType = config('bprs.assetType');
  $selected_year = date('Y');

  return Template::loadView('Report.FAM.AssetRepairBackReport', ['company' => $company, 'location' => $location, 'productionarea' => $productionarea, 'selected_year' => $selected_year, 'assetName' => $assetName, 'assetType' => $assetType]);
 }

 public function reportData()
 {
  $company_id = request('company_id', 0);
  $date_from = request('date_from', 0);
  $date_to = request('date_to', 0);
  $location_id = request('location_id', 0);
  $asset_id = request('asset_id', 0);
  $type_id = request('type_id', 0);
  $production_area_id = request('production_area_id', 0);

  $companyId = null;
  $locationId = null;
  $productionarea = null;
  $datefrom = null;
  $dateto = null;
  $assetType = null;

  if ($date_from) {
   $date_from = " and asset_service_repairs.out_date >='" . $date_from . "' ";
  }
  if ($date_to) {
   $dateto = " and asset_service_repairs.out_date <='" . $date_to . "' ";
  }

  if ($company_id) {
   $companyId = " and asset_acquisitions.company_id = $company_id ";
  }
  if ($location_id) {
   $locationId = " and asset_acquisitions.location_id = $location_id ";
  }
  if ($production_area_id) {
   $productionarea = " and asset_acquisitions.production_area_id = $production_area_id ";
  }

  $company = array_prepend(array_pluck($this->company->orderBy('name')->get(), 'name', 'id'), '-Select-', '');
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $assetType = config('bprs.assetType');
  $results = $this->assetservicerepair
   ->join('asset_breakdowns', function ($join) {
    $join->on('asset_breakdowns.id', '=', 'asset_service_repairs.asset_breakdown_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'asset_service_repairs.supplier_id');
   })
   ->join('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'asset_breakdowns.asset_quantity_cost_id');
   })
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'asset_acquisitions.company_id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'asset_acquisitions.location_id');
   })
   ->join('asset_service_repair_parts', function ($join) {
    $join->on('asset_service_repair_parts.asset_service_repair_id', '=', 'asset_service_repairs.id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'asset_service_repair_parts.item_account_id');
   })
   ->join('asset_return_details', function ($join) {
    $join->on('asset_return_details.asset_part_id', '=', 'asset_service_repair_parts.id');
   })
   ->join('asset_returns', function ($join) {
    $join->on('asset_returns.id', '=', 'asset_return_details.asset_return_id');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('employee_h_rs.id', '=', 'asset_breakdowns.employee_h_r_id');
   })
   ->selectRaw(
    'asset_service_repairs.id,
     asset_service_repairs.out_date,
     asset_service_repairs.returnable_date,
     companies.name as company_name,
     companies.address as company_address,
     locations.name as location_name,
     asset_quantity_costs.custom_no,
     asset_acquisitions.name as asset_name,
     asset_acquisitions.id as asset_bd_id,
     asset_acquisitions.type_id,
     asset_acquisitions.production_area_id,
     asset_returns.return_date,
     asset_returns.id as rtn_id,
     suppliers.name as supplier_name,
     asset_breakdowns.remarks as problems,
     employee_h_rs.name as responsible_name,
     employee_h_rs.contact as responsible_contact'
   )
   ->groupBy([
    'asset_service_repairs.id'
   ])
   // ->orderBy('asset_acquisitions.company_id')
   ->get()
   ->map(function ($results) use ($productionarea, $assetType) {
    $results->type_id = isset($assetType[$results->type_id]) ? $assetType[$results->type_id] : '';
    $results->production_area_id = isset($productionarea[$results->production_area_id]) ? $productionarea[$results->production_area_id] : '';
    $results->days_taken = $results->out_date - $results->return_date;
    return $results;
   });
  return Template::loadView('Report.FAM.AssetRepairBackReportMatrix', ['results' => $results]);
 }
}
