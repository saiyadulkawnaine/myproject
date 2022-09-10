<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetServiceRepository;
use App\Repositories\Contracts\FAMS\AssetServiceDetailRepository;
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
use App\Library\Template;
use App\Http\Requests\FAMS\AssetServiceDetailRequest;

use function GuzzleHttp\json_encode;

class AssetServiceDetailController extends Controller
{
 private $assetacquisition;
 private $assetservice;
 private $assetservicedetail;
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
  AssetServiceRepository $assetservice,
  AssetServiceDetailRepository $assetservicedetail,
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
  $this->assetservice = $assetservice;
  $this->assetservicedetail = $assetservicedetail;
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
  /* $this->middleware('permission:view.assetservicedetails',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetservicedetails', ['only' => ['store']]);
        $this->middleware('permission:edit.assetservicedetails',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetservicedetails', ['only' => ['destroy']]); */
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $assetType = config('bprs.assetType');
  $rows = $this->assetservicedetail
   ->join('asset_services', function ($join) {
    $join->on('asset_services.id', '=', 'asset_service_details.asset_service_id');
   })
   ->join('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'asset_service_details.asset_quantity_cost_id');
   })
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->where([['asset_service_details.asset_service_id', '=', request('asset_service_id', 0)]])
   ->orderBy('asset_service_details.id', "DESC")
   ->get([
    'asset_service_details.*',
    'asset_acquisitions.name as asset_name',
    'asset_acquisitions.type_id',
    'asset_acquisitions.production_area_id',
    'asset_acquisitions.asset_group',
    'asset_acquisitions.brand',
    'asset_acquisitions.origin',
    'asset_acquisitions.purchase_date',

   ])
   ->map(function ($rows) use ($productionarea, $assetType) {
    $rows->type_id = isset($assetType[$rows->type_id]) ? $assetType[$rows->type_id] : '';
    $rows->production_area_id = isset($productionarea[$rows->production_area_id]) ? $productionarea[$rows->production_area_id] : '';
    $rows->purchase_date = date('Y-m-d', strtotime($rows->purchase_date));
    // $rows->asset_no = str_pad($rows->id, 6, 0, STR_PAD_LEFT);
    return $rows;
   });
  echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create(Request $request)
 {
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(AssetServiceDetailRequest $request)
 {
  $assetservicedetail = $this->assetservicedetail->create([
   "asset_service_id" => $request->asset_service_id,
   "asset_quantity_cost_id" => $request->asset_quantity_cost_id,
   "remarks" => $request->remarks,
  ]);
  if ($assetservicedetail) {
   return response()->json(array('success' => true, 'id' => $assetservicedetail->id, 'message' => 'Save Successfully'), 200);
  }
 }

 /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function show($id)
 {
  //
 }

 /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function edit($id)
 {
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $assetType = config('bprs.assetType');
  $assetservicedetail = $this->assetservicedetail
   ->join("asset_services", function ($join) {
    $join->on("asset_services.id", "=", "asset_service_details.asset_service_id");
   })
   ->join('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'asset_service_details.asset_quantity_cost_id');
   })
   ->join("asset_acquisitions", function ($join) {
    $join->on("asset_acquisitions.id", "=", "asset_quantity_costs.asset_acquisition_id");
   })
   ->where("asset_service_details.id", "=", $id)
   ->get([
    'asset_service_details.*',
    'asset_acquisitions.name as asset_name',
    'asset_acquisitions.type_id',
    'asset_acquisitions.production_area_id',
    'asset_acquisitions.asset_group',
    'asset_acquisitions.brand',
    'asset_acquisitions.origin',
    'asset_acquisitions.purchase_date',
   ])
   ->map(function ($assetservicedetail) use ($productionarea, $assetType) {
    $assetservicedetail->type_id = isset($assetType[$assetservicedetail->type_id]) ? $assetType[$assetservicedetail->type_id] : '';
    $assetservicedetail->production_area_id = isset($productionarea[$assetservicedetail->production_area_id]) ? $productionarea[$assetservicedetail->production_area_id] : '';
    $assetservicedetail->purchase_date = date('Y-m-d', strtotime($assetservicedetail->purchase_date));
    // $rows->asset_no = str_pad($rows->id, 6, 0, STR_PAD_LEFT);
    return $assetservicedetail;
   })
   ->first();
  $row['fromData'] = $assetservicedetail;
  $dropdown['att'] = '';
  $row['dropDown'] = $dropdown;
  echo json_encode($row);
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function update(AssetServiceDetailRequest $request, $id)
 {
  $assetservicedetail = $this->assetservicedetail->update($id, [
   "asset_service_id" => $request->asset_service_id,
   "asset_quantity_cost_id" => $request->asset_quantity_cost_id,
   "remarks" => $request->remarks,
  ]);
  if ($assetservicedetail) {
   return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
  }
 }

 /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function destroy($id)
 {
  if ($this->assetservicedetail->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getAsset()
 {
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $assetType = config('bprs.assetType');
  $rows = $this->assetquantitycost
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_quantity_costs.asset_acquisition_id', '=', 'asset_acquisitions.id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'asset_acquisitions.company_id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'asset_acquisitions.location_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'asset_acquisitions.supplier_id');
   })
   ->when(request('asset_no'), function ($q) {
    return $q->where('asset_quantity_costs.asset_no', '=', request('asset_no', 0));
   })
   ->when(request('custom_no'), function ($q) {
    return $q->where('asset_quantity_costs.custom_no', '=', request('custom_no', 0));
   })
   ->when(request('asset_name'), function ($q) {
    return $q->where('asset_acquisitions.name', 'like', '%' . request('asset_name', 0) . '%');
   })
   ->orderBy('asset_quantity_costs.id', 'DESC')
   ->get([
    'asset_quantity_costs.*',
    'asset_acquisitions.name as asset_name',
    'asset_acquisitions.type_id',
    'asset_acquisitions.production_area_id',
    'asset_acquisitions.asset_group',
    'asset_acquisitions.brand',
    'asset_acquisitions.origin',
    'asset_acquisitions.purchase_date',
    'asset_acquisitions.prod_capacity',
    'companies.name as company_name',
    'locations.name as location_name',
    'suppliers.name as supplier_name',
   ])
   ->map(function ($rows) use ($productionarea, $assetType) {
    $rows->type_id = isset($assetType[$rows->type_id]) ? $assetType[$rows->type_id] : '';
    $rows->production_area_id = isset($productionarea[$rows->production_area_id]) ? $productionarea[$rows->production_area_id] : '';
    $rows->purchase_date = date('Y-m-d', strtotime($rows->purchase_date));
    $rows->asset_no = str_pad($rows->id, 6, 0, STR_PAD_LEFT);
    return $rows;
   });
  echo json_encode($rows);
 }
}
