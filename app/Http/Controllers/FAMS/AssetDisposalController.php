<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\FAMS\AssetTechnicalFeatureRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
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
use App\Http\Requests\FAMS\AssetDisposalRequest;

class AssetDisposalController extends Controller
{

 private $assetdisposal;
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
  AssetDisposalRepository $assetdisposal,
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
  BuyerRepository $buyer
 ) {

  $this->assetquantitycost = $assetquantitycost;
  $this->employeehr = $employeehr;
  $this->assetdisposal = $assetdisposal;
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
  /* $this->middleware('permission:view.assetdisposals',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetdisposals', ['only' => ['store']]);
        $this->middleware('permission:edit.assetdisposals',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetdisposals', ['only' => ['destroy']]); */
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {

  $rows = $this->assetdisposal
   ->join("asset_quantity_costs", function ($join) {
    $join->on("asset_quantity_costs.id", "=", "asset_disposals.asset_quantity_cost_id");
   })
   ->join("buyers", function ($join) {
    $join->on("buyers.id", "=", "asset_disposals.buyer_id");
   })
   ->orderBy("asset_disposals.id", "DESC")
   ->get([
    "asset_disposals.*",
    "buyers.name as buyer_name",
    "asset_quantity_costs.custom_no"
   ]);

  echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create(Request $request)
 {
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '', '');
  $depMethod = array_prepend(config('bprs.depMethod'), '-Select-', '');
  $disposal_type = array_prepend(config('bprs.disposaltype'), '-Select-', '');
  return Template::loadView('FAMS.AssetDisposal', ['buyer' => $buyer, 'depMethod' => $depMethod, 'disposal_type' => $disposal_type]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(AssetDisposalRequest $request)
 {
  $assetdisposal = $this->assetdisposal->create([
   "asset_quantity_cost_id" => $request->asset_quantity_cost_id,
   "disposal_date" => $request->disposal_date,
   "buyer_id" => $request->buyer_id,
   "sold_amount" => $request->sold_amount,
   "disposal_type_id" => $request->disposal_type_id,
  ]);
  if ($assetdisposal) {
   return response()->json(array('success' => true, 'id' => $assetdisposal->id, 'message' => 'Save Successfully'), 200);
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
  $depMethod = array_prepend(config('bprs.depMethod'), '-Select-', '');
  $assetType = config('bprs.assetType');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $assetdisposal = $this->assetdisposal
   ->join("asset_quantity_costs", function ($join) {
    $join->on("asset_quantity_costs.id", "=", "asset_disposals.asset_quantity_cost_id");
   })
   ->join("buyers", function ($join) {
    $join->on("buyers.id", "=", "asset_disposals.buyer_id");
   })
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->where("asset_disposals.id", "=", $id)
   ->get([
    'asset_disposals.*',
    'asset_quantity_costs.serial_no',
    'asset_quantity_costs.custom_no',
    'asset_quantity_costs.asset_no',
    'asset_quantity_costs.total_cost as origin_cost',
    'asset_quantity_costs.accumulated_dep',
    'asset_acquisitions.name as asset_name',
    'asset_acquisitions.company_id',
    'asset_acquisitions.location_id',
    'asset_acquisitions.supplier_id',
    'asset_acquisitions.type_id',
    'asset_acquisitions.production_area_id',
    'asset_acquisitions.asset_group',
    'asset_acquisitions.brand',
    'asset_acquisitions.origin',
    'asset_acquisitions.purchase_date',
    'asset_acquisitions.prod_capacity',
    'asset_acquisitions.salvage_value',
    'asset_acquisitions.depreciation_method_id',
    'asset_acquisitions.depreciation_rate',
   ])
   ->map(function ($assetdisposal) use ($assetType, $productionarea, $supplier, $company, $location, $depMethod) {
    $assetdisposal->type_id = isset($assetType[$assetdisposal->type_id]) ? $assetType[$assetdisposal->type_id] : '';
    $assetdisposal->production_area_id = isset($productionarea[$assetdisposal->production_area_id]) ? $productionarea[$assetdisposal->production_area_id] : '';
    $assetdisposal->supplier_id = isset($supplier[$assetdisposal->supplier_id]) ? $supplier[$assetdisposal->supplier_id] : '';
    $assetdisposal->company_id = isset($company[$assetdisposal->company_id]) ? $company[$assetdisposal->company_id] : '';
    $assetdisposal->location_id = isset($location[$assetdisposal->location_id]) ? $location[$assetdisposal->location_id] : '';
    $assetdisposal->depreciation_method_id = isset($depMethod[$assetdisposal->depreciation_method_id]) ? $depMethod[$assetdisposal->depreciation_method_id] : '';
    $assetdisposal->written_down_value = $assetdisposal->origin_cost - $assetdisposal->accumulated_dep;
    $assetdisposal->gain_loss = $assetdisposal->sold_amount - $assetdisposal->written_down_value;
    return $assetdisposal;
   })
   ->first();
  $row['fromData'] = $assetdisposal;
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
 public function update(AssetDisposalRequest $request, $id)
 {
  $assetdisposal = $this->assetdisposal->update($id, [
   "asset_quantity_cost_id" => $request->asset_quantity_cost_id,
   "disposal_date" => $request->disposal_date,
   "buyer_id" => $request->buyer_id,
   "sold_amount" => $request->sold_amount,
   "disposal_type_id" => $request->disposal_type_id,
  ]);
  if ($assetdisposal) {
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
  if ($this->assetdisposal->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getAsset()
 {
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $depMethod = array_prepend(config('bprs.depMethod'), '-Select-', '');
  // $depMethod= config('bprs.depMethod');
  $assetType = config('bprs.assetType');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $assetquantitycost = $this->assetquantitycost
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->leftJoin(\DB::raw("(
            SELECT 
            asset_manpowers.asset_quantity_cost_id,
            employee_h_rs.name  as employee_name
            FROM asset_manpowers 
            join employee_h_rs on employee_h_rs.id = asset_manpowers.employee_h_r_id
            where 
            '" . "' between  asset_manpowers.tenure_start and asset_manpowers.tenure_end
            group by 
            asset_manpowers.asset_quantity_cost_id,
            employee_h_rs.name
        ) manpowers"), "manpowers.asset_quantity_cost_id", "=", "asset_quantity_costs.id")
   ->when(request('asset_no'), function ($q) {
    return $q->where('asset_quantity_costs.asset_no', '=', request('asset_no', 0));
   })
   ->when(request('custom_no'), function ($q) {
    return $q->where('asset_quantity_costs.custom_no', '=', request('custom_no', 0));
   })
   ->when(request('asset_name'), function ($q) {
    return $q->where('asset_acquisitions.name', 'like', '%' . request('asset_name', 0) . '%');
   })
   ->get([
    'asset_quantity_costs.id',
    'asset_quantity_costs.serial_no',
    'asset_quantity_costs.custom_no',
    'asset_quantity_costs.asset_no',
    'asset_quantity_costs.total_cost as origin_cost',
    'asset_quantity_costs.accumulated_dep',
    'asset_acquisitions.name as asset_name',
    'asset_acquisitions.company_id',
    'asset_acquisitions.location_id',
    'asset_acquisitions.supplier_id',
    'asset_acquisitions.iregular_supplier',
    'asset_acquisitions.type_id',
    'asset_acquisitions.production_area_id',
    'asset_acquisitions.asset_group',
    'asset_acquisitions.brand',
    'asset_acquisitions.origin',
    'asset_acquisitions.purchase_date',
    'asset_acquisitions.prod_capacity',
    'asset_acquisitions.salvage_value',
    'asset_acquisitions.depreciation_method_id',
    'asset_acquisitions.depreciation_rate',
    'manpowers.employee_name'
   ])
   ->map(function ($assetquantitycost) use ($assetType, $productionarea, $supplier, $company, $location, $depMethod) {
    $assetquantitycost->type_id = isset($assetType[$assetquantitycost->type_id]) ? $assetType[$assetquantitycost->type_id] : '';
    $assetquantitycost->production_area_id = isset($productionarea[$assetquantitycost->production_area_id]) ? $productionarea[$assetquantitycost->production_area_id] : '';
    $assetquantitycost->supplier_id = isset($supplier[$assetquantitycost->supplier_id]) ? $supplier[$assetquantitycost->supplier_id] : '';
    $assetquantitycost->company_id = isset($company[$assetquantitycost->company_id]) ? $company[$assetquantitycost->company_id] : '';
    $assetquantitycost->location_id = isset($location[$assetquantitycost->location_id]) ? $location[$assetquantitycost->location_id] : '';
    $assetquantitycost->depreciation_method_id = isset($depMethod[$assetquantitycost->depreciation_method_id]) ? $depMethod[$assetquantitycost->depreciation_method_id] : '';
    $assetquantitycost->written_down_value = $assetquantitycost->origin_cost - $assetquantitycost->accumulated_dep;
    return $assetquantitycost;
   });

  echo json_encode($assetquantitycost);
 }
}
