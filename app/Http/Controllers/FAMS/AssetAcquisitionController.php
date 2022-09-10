<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetTechnicalFeatureRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
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
use App\Library\Template;
use App\Http\Requests\FAMS\AssetAcquisitionRequest;

class AssetAcquisitionController extends Controller
{

 private $assetacquisition;
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
 private $assetdisposal;

 public function __construct(
  AssetAcquisitionRepository $assetacquisition,
  AssetTechnicalFeatureRepository $assettechfeature,
  AssetQuantityCostRepository $assetquantitycost,
  AssetDisposalRepository $assetdisposal,
  CompanyRepository $company,
  LocationRepository $location,
  UomRepository $uom,
  SupplierRepository $supplier,
  ItemAccountRepository $itemaccount,
  ItemclassRepository $itemclass,
  ItemcategoryRepository $itemcategory,
  StoreRepository $store,
  EmployeeHRRepository $employeehr,
  DesignationRepository $designation,
  DepartmentRepository $department,
  DivisionRepository $division,
  SectionRepository $section,
  SubsectionRepository $subsection
 ) {

  $this->assetacquisition = $assetacquisition;
  $this->assetquantitycost = $assetquantitycost;
  $this->assettechfeature = $assettechfeature;
  $this->assetdisposal = $assetdisposal;
  $this->employeehr = $employeehr;
  $this->company = $company;
  $this->location = $location;
  $this->uom = $uom;
  $this->supplier = $supplier;
  $this->itemaccount = $itemaccount;
  $this->itemclass = $itemclass;
  $this->itemcategory = $itemcategory;
  $this->store = $store;
  $this->department = $department;
  $this->division = $division;
  $this->section = $section;
  $this->subsection = $subsection;
  $this->designation = $designation;


  $this->middleware('auth');
  /* $this->middleware('permission:view.assetacquisitions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetacquisitions', ['only' => ['store']]);
        $this->middleware('permission:edit.assetacquisitions',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetacquisitions', ['only' => ['destroy']]); */
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $depMethod = array_prepend(config('bprs.depMethod'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '', '');
  $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '', '');
  $assetType = config('bprs.assetType');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  $store = array_prepend(array_pluck($this->store->get(), 'name', 'id'), '-Select-', '');
  $assetacquisitions = array();
  $rows = $this->assetacquisition
   ->orderBy('asset_acquisitions.id', 'desc')
   ->get();
  foreach ($rows as $row) {
   $assetacquisition['id'] = $row->id;
   $assetacquisition['company_id'] = isset($company[$row->company_id]) ? $company[$row->company_id] : '';
   $assetacquisition['location_id'] = isset($location[$row->location_id]) ? $location[$row->location_id] : '';
   $assetacquisition['name'] = $row->name;
   $assetacquisition['type_id'] = isset($assetType[$row->type_id]) ? $assetType[$row->type_id] : '';
   $assetacquisition['store_id'] = isset($store[$row->store_id]) ? $store[$row->store_id] : ''; //if asset type is machinary than production area otherwise no need
   $assetacquisition['production_area_id'] = isset($productionarea[$row->production_area_id]) ? $productionarea[$row->production_area_id] : '';
   $assetacquisition['asset_group'] = $row->asset_group;
   $assetacquisition['supplier_id'] = isset($supplier[$row->supplier_id]) ? $supplier[$row->supplier_id] : '';
   $assetacquisition['iregular_supplier'] = $row->iregular_supplier;
   $assetacquisition['brand'] = $row->brand;
   $assetacquisition['origin'] = $row->origin;
   $assetacquisition['purchase_date'] = ($row->purchase_date) !== null ? date('Y-m-d', strtotime($row->purchase_date)) : '';
   $assetacquisition['qty'] = $row->qty;
   $assetacquisition['accumulated_dep'] = $row->accumulated_dep;
   $assetacquisition['salvage_value'] = $row->salvage_value;
   $assetacquisition['depreciation_mathod_id'] = isset($depMethod[$row->depreciation_mathod_id]) ? $depMethod[$row->depreciation_mathod_id] : '';
   $assetacquisition['depreciation_rate'] = $row->depreciation_rate;
   $assetacquisition['life_time'] = $row->life_time;
   $assetacquisition['prod_capacity'] = $row->prod_capacity;
   $assetacquisition['uom_id'] = isset($uom[$row->uom_id]) ? $uom[$row->uom_id] : '';
   $assetacquisition['sort_id'] = $row->sort_id;
   array_push($assetacquisitions, $assetacquisition);
  }
  echo json_encode($assetacquisitions);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create(Request $request)
 {
  $assetacquisition = array_prepend(array_pluck($this->assetacquisition->get(), 'name', 'id'), '-Select-', '');
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $depMethod = array_prepend(config('bprs.depMethod'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '', '');
  $assetType = array_prepend(config('bprs.assetType'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->where([['status_id', '=', 1]])->get(), 'name', 'id'), '-Select-', '');

  $assettechfeature = array_prepend(array_pluck($this->assettechfeature->get(), 'name', 'id'), '-Select-', '');

  $itemaccount = array_prepend(array_pluck($this->itemaccount->get(), 'item_description', 'id'), '-Select-', '');
  $itemcategory = array_prepend(array_pluck($this->itemcategory->where([['identity', '=', 9]])->get(), 'name', 'id'), '-Select-', '');
  $itemclass = array_prepend(array_pluck($this->itemclass->get(), 'name', 'id'), '-Select-', '');
  $itemnature = array_prepend(config('bprs.itemnature'), '-Select-', '');

  $employeehr = array_prepend(array_pluck($this->employeehr->get(), 'name', 'id'), '-Select-', '');

  $store = array_prepend(array_pluck($this->store->get(), 'name', 'id'), '-Select-', '');
  $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '-Select-', '');
  $department = array_prepend(array_pluck($this->department->get(), 'name', 'id'), '-Select-', '');
  $division = array_prepend(array_pluck($this->division->get(), 'name', 'id'), '-Select-', '');
  $section = array_prepend(array_pluck($this->section->get(), 'name', 'id'), '', '');
  $subsection = array_prepend(array_pluck($this->subsection->get(), 'name', 'id'), '', '');

  return Template::loadView('FAMS.AssetAcquisition', ['company' => $company, 'location' => $location, 'uom' => $uom, 'assetType' => $assetType, 'supplier' => $supplier, 'depMethod' => $depMethod, 'productionarea' => $productionarea, 'assetacquisition' => $assetacquisition, 'assettechfeature' => $assettechfeature, 'itemaccount' => $itemaccount, 'itemcategory' => $itemcategory, 'itemclass' => $itemclass, 'itemnature' => $itemnature, 'store' => $store, 'employeehr' => $employeehr, 'designation' => $designation, 'department' => $department, 'division' => $division, 'section' => $section, 'subsection' => $subsection]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(AssetAcquisitionRequest $request)
 {
  $assetacquisition = $this->assetacquisition->create($request->except(['id']));
  if ($assetacquisition) {
   return response()->json(array('success' => true, 'id' => $assetacquisition->id, 'message' => 'Save Successfully'), 200);
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
  $assetacquisition = $this->assetacquisition->find($id);
  $row['fromData'] = $assetacquisition;
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
 public function update(AssetAcquisitionRequest $request, $id)
 {
  $assetdisposal = $this->assetdisposal
   ->join('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'asset_disposals.asset_quantity_cost_id');
   })
   ->where([['asset_quantity_costs.asset_acquisition_id', '=', $id]])
   ->get(['asset_disposals.id'])
   ->first();

  //dd($assetdisposal);die;
  if ($assetdisposal) {
   return response()->json(array('success' => false, 'message' => 'Update Not Successful. Asset Disposal Entry Found'), 200);
  }

  $assetacquisition = $this->assetacquisition->update($id, $request->except(['id']));
  if ($assetacquisition) {
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
  $assetdisposal = $this->assetdisposal
   ->join('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'asset_disposals.asset_quantity_cost_id');
   })
   ->where([['asset_quantity_costs.asset_acquisition_id', '=', $id]])
   ->get(['asset_disposals.id'])
   ->first();
  if ($assetdisposal) {
   return response()->json(array('success' => false, 'message' => 'Delete unsuccessful. Asset Disposal Entry Found'), 200);
  }

  if ($this->assetacquisition->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
