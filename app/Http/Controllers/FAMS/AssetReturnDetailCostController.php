<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetServiceRepairRepository;
use App\Repositories\Contracts\FAMS\AssetServiceRepository;
use App\Repositories\Contracts\FAMS\AssetReturnRepository;
use App\Repositories\Contracts\FAMS\AssetReturnDetailRepository;
use App\Repositories\Contracts\FAMS\AssetReturnDetailCostRepository;
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
use App\Http\Requests\FAMS\AssetReturnDetailCostRequest;
use PDO;

class AssetReturnDetailCostController extends Controller
{
 private $assetservicerepair;
 private $assetservice;
 private $assetreturn;
 private $assetreturndetail;
 private $assetreturndetailcost;
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
  AssetServiceRepairRepository $assetservicerepair,
  AssetServiceRepository $assetservice,
  AssetReturnRepository $assetreturn,
  AssetReturnDetailRepository $assetreturndetail,
  AssetReturnDetailCostRepository $assetreturndetailcost,
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

  $this->assetquantitycost = $assetquantitycost;
  $this->assetbreakdown = $assetbreakdown;
  $this->employeehr = $employeehr;
  $this->assetservicerepair = $assetservicerepair;
  $this->assetservice = $assetservice;
  $this->assetreturn = $assetreturn;
  $this->assetreturndetail = $assetreturndetail;
  $this->assetreturndetailcost = $assetreturndetailcost;
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
  /* $this->middleware('permission:view.assetreturndetailcosts',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetreturndetailcosts', ['only' => ['store']]);
        $this->middleware('permission:edit.assetreturndetailcosts',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetreturndetailcosts', ['only' => ['destroy']]); */
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $rows = $this->assetreturndetailcost
   ->join('asset_return_details', function ($join) {
    $join->on('asset_return_details.id', '=', 'asset_return_detail_costs.asset_return_detail_id');
   })
   ->where([['asset_return_detail_costs.asset_return_detail_id', '=', request('asset_return_detail_id', 0)]])
   ->orderBy('asset_return_detail_costs.id', 'DESC')
   ->get([
    'asset_return_detail_costs.*',
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
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(AssetReturnDetailCostRequest $request)
 {
  $assetreturndetailcost = $this->assetreturndetailcost->create($request->except(['id']));
  if ($assetreturndetailcost) {
   return response()->json(array('success' => true, 'id' => $assetreturndetailcost->id, 'message' => 'Save Successfully'), 200);
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

  $assetreturndetailcost = $this->assetreturndetailcost->find($id);
  $row['fromData'] = $assetreturndetailcost;
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
 public function update(AssetReturnDetailCostRequest $request, $id)
 {
  $assetreturndetailcost = $this->assetreturndetailcost->update($id, $request->except('id'));
  if ($assetreturndetailcost) {
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
  if ($this->assetreturndetailcost->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
