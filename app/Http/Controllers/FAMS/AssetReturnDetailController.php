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
use App\Http\Requests\FAMS\AssetReturnDetailRequest;
use PDO;

class AssetReturnDetailController extends Controller
{
 private $assetservicerepair;
 private $assetservice;
 private $assetreturn;
 private $assetreturndetail;
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
  /* $this->middleware('permission:view.assetreturndetails',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetreturndetails', ['only' => ['store']]);
        $this->middleware('permission:edit.assetreturndetails',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetreturndetails', ['only' => ['destroy']]); */
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $assetreturn = $this->assetreturn->find(request('asset_return_id', 0));
  // Repair
  if ($assetreturn->menu_id == 380) {
   $assetServiceRepair = $this->assetreturndetail
    ->join('asset_returns', function ($join) {
     $join->on('asset_returns.id', '=', 'asset_return_details.asset_return_id');
    })
    ->join('asset_service_repair_parts', function ($join) {
     $join->on('asset_service_repair_parts.id', '=', 'asset_return_details.asset_part_id');
    })
    ->join('asset_service_repairs', function ($join) {
     $join->on('asset_service_repairs.id', '=', 'asset_service_repair_parts.asset_service_repair_id');
    })
    ->join('asset_breakdowns', function ($join) {
     $join->on('asset_breakdowns.id', '=', 'asset_service_repairs.asset_breakdown_id');
    })
    ->join('asset_quantity_costs', function ($join) {
     $join->on('asset_quantity_costs.id', '=', 'asset_breakdowns.asset_quantity_cost_id');
    })
    ->join('asset_acquisitions', function ($join) {
     $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
    })
    ->where([['asset_return_details.asset_return_id', '=', request('asset_return_id', 0)]])
    ->orderBy('asset_return_details.id', 'DESC')
    ->get([
     'asset_return_details.*',
     'asset_service_repairs.out_date',
     'asset_service_repairs.returnable_date',
     'asset_acquisitions.name as asset_name'
    ])
    ->map(function ($assetServiceRepair) {
     $assetServiceRepair->out_date = date('Y-m-d', strtotime($assetServiceRepair->out_date));
     $assetServiceRepair->returnable_date = date('Y-m-d', strtotime($assetServiceRepair->returnable_date));
     return $assetServiceRepair;
    });
   echo json_encode($assetServiceRepair);
  }
  // service 
  if ($assetreturn->menu_id == 381) {
   $assetService = $this->assetreturndetail
    ->join('asset_returns', function ($join) {
     $join->on('asset_returns.id', '=', 'asset_return_details.asset_return_id');
    })
    ->join('asset_service_details', function ($join) {
     $join->on('asset_service_details.id', '=', 'asset_return_details.asset_part_id');
    })
    ->join('asset_services', function ($join) {
     $join->on('asset_services.id', '=', 'asset_service_details.asset_service_id');
    })
    ->join('asset_quantity_costs', function ($join) {
     $join->on('asset_quantity_costs.id', 'asset_service_details.asset_quantity_cost_id');
    })
    ->join('asset_acquisitions', function ($join) {
     $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
    })
    ->where([['asset_return_details.asset_return_id', '=', request('asset_return_id', 0)]])
    ->orderBy('asset_return_details.id', 'DESC')
    ->get([
     'asset_return_details.*',
     'asset_services.out_date',
     'asset_services.returnable_date',
     'asset_acquisitions.name as asset_name'
    ])
    ->map(function ($assetService) {
     $assetService->out_date = date('Y-m-d', strtotime($assetService->out_date));
     $assetService->returnable_date = date('Y-m-d', strtotime($assetService->returnable_date));
     return $assetService;
    });
   echo json_encode($assetService);
  }
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
 public function store(AssetReturnDetailRequest $request)
 {
  $assetreturndetail = $this->assetreturndetail->create($request->except(['id', 'asset_part']));
  if ($assetreturndetail) {
   return response()->json(array('success' => true, 'id' => $assetreturndetail->id, 'message' => 'Save Successfully'), 200);
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

  $assetreturndetailID = $this->assetreturndetail->find($id);
  $assetreturn = $this->assetreturn->find($assetreturndetailID->asset_return_id);


  if ($assetreturn->menu_id == 380) {
   $assetreturndetail = $this->assetreturndetail
    ->join('asset_returns', function ($join) {
     $join->on('asset_returns.id', '=', 'asset_return_details.asset_return_id');
    })
    ->join('asset_service_repair_parts', function ($join) {
     $join->on('asset_service_repair_parts.id', '=', 'asset_return_details.asset_part_id');
    })
    ->join('asset_service_repairs', function ($join) {
     $join->on('asset_service_repairs.id', '=', 'asset_service_repair_parts.asset_service_repair_id');
    })
    ->join('asset_breakdowns', function ($join) {
     $join->on('asset_breakdowns.id', '=', 'asset_service_repairs.asset_breakdown_id');
    })
    ->join('asset_quantity_costs', function ($join) {
     $join->on('asset_quantity_costs.id', '=', 'asset_breakdowns.asset_quantity_cost_id');
    })
    ->join('asset_acquisitions', function ($join) {
     $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
    })
    ->join('item_accounts', function ($join) {
     $join->on('item_accounts.id', '=', 'asset_service_repair_parts.item_account_id');
    })
    ->where([['asset_return_details.id', '=', $id]])
    ->orderBy('asset_return_details.id', 'DESC')
    ->get([
     'asset_return_details.*',
     'asset_service_repairs.out_date',
     'asset_service_repairs.returnable_date',
     'asset_acquisitions.name as asset_name',
     'item_accounts.item_description',
     'item_accounts.specification'
    ])
    ->map(function ($assetreturndetail) {
     $assetreturndetail->out_date = date('Y-m-d', strtotime($assetreturndetail->out_date));
     $assetreturndetail->returnable_date = date('Y-m-d', strtotime($assetreturndetail->returnable_date));
     $assetreturndetail->asset_part = $assetreturndetail->item_description . ',' . $assetreturndetail->specification;
     return $assetreturndetail;
    })
    ->first();

   $row['fromData'] = $assetreturndetail;
   $dropdown['att'] = '';
   $row['dropDown'] = $dropdown;
   echo json_encode($row);
  }

  if ($assetreturn->menu_id == 381) {
   $assetreturndetail = $this->assetreturndetail
    ->join('asset_returns', function ($join) {
     $join->on('asset_returns.id', '=', 'asset_return_details.asset_return_id');
    })
    ->join('asset_service_details', function ($join) {
     $join->on('asset_service_details.id', '=', 'asset_return_details.asset_part_id');
    })
    ->join('asset_services', function ($join) {
     $join->on('asset_services.id', '=', 'asset_service_details.asset_service_id');
    })
    ->join('asset_quantity_costs', function ($join) {
     $join->on('asset_quantity_costs.id', 'asset_service_details.asset_quantity_cost_id');
    })
    ->join('asset_acquisitions', function ($join) {
     $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
    })
    ->where([['asset_return_details.id', '=', $id]])
    ->get([
     'asset_return_details.*',
     'asset_services.out_date',
     'asset_services.returnable_date',
     'asset_acquisitions.name as asset_name',
     'asset_acquisitions.asset_group',
     'asset_acquisitions.brand',
    ])
    ->map(function ($assetreturndetail) {
     $assetreturndetail->out_date = date('Y-m-d', strtotime($assetreturndetail->out_date));
     $assetreturndetail->returnable_date = date('Y-m-d', strtotime($assetreturndetail->returnable_date));
     $assetreturndetail->asset_part = $assetreturndetail->asset_group . ',' . $assetreturndetail->brand;
     return $assetreturndetail;
    })
    ->first();
   $row['fromData'] = $assetreturndetail;
   $dropdown['att'] = '';
   $row['dropDown'] = $dropdown;
   echo json_encode($row);
  }
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function update(AssetReturnDetailRequest $request, $id)
 {
  $assetreturndetail = $this->assetreturndetail->update($id, [
   'asset_part_id' => $request->asset_part_id
  ]);
  if ($assetreturndetail) {
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
  if ($this->assetreturndetail->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getAssetPart()
 {
  $menu_id = request('menu_id', 0);
  // Repair
  if ($menu_id == 380) {
   $rows = $this->assetservicerepair
    ->join('asset_breakdowns', function ($join) {
     $join->on('asset_breakdowns.id', '=', 'asset_service_repairs.asset_breakdown_id');
    })
    ->join('asset_quantity_costs', function ($join) {
     $join->on('asset_quantity_costs.id', '=', 'asset_breakdowns.asset_quantity_cost_id');
    })
    ->join('asset_acquisitions', function ($join) {
     $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
    })
    ->join('asset_service_repair_parts', function ($join) {
     $join->on('asset_service_repair_parts.asset_service_repair_id', '=', 'asset_service_repairs.id');
    })
    ->join('item_accounts', function ($join) {
     $join->on('item_accounts.id', '=', 'asset_service_repair_parts.item_account_id');
    })
    ->orderBy('asset_service_repair_parts.id', 'DESC')
    ->get([
     'asset_service_repair_parts.id',
     'asset_service_repair_parts.qty',
     'asset_service_repairs.out_date',
     'asset_service_repairs.returnable_date',
     'asset_acquisitions.name as asset_name',
     'item_accounts.item_description',
     'item_accounts.specification'
    ])
    ->map(function ($rows) {
     $rows->out_date = date('Y-m-d', strtotime($rows->out_date));
     $rows->returnable_date = date('Y-m-d', strtotime('$rows->returnable_date'));
     $rows->item_description = $rows->item_description . ',' . $rows->specification;
     return $rows;
    });
   echo json_encode($rows);
  }
  // service 
  if ($menu_id == 381) {
   $rows = $this->assetservice
    ->join('asset_service_details', function ($join) {
     $join->on('asset_service_details.asset_service_id', '=', 'asset_services.id');
    })
    ->join('asset_quantity_costs', function ($join) {
     $join->on('asset_quantity_costs.id', '=', 'asset_service_details.asset_quantity_cost_id');
    })
    ->join('asset_acquisitions', function ($join) {
     $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
    })
    ->orderBy('asset_service_details.id', 'DESC')
    ->get([
     'asset_service_details.id',
     'asset_services.out_date',
     'asset_services.returnable_date',
     'asset_acquisitions.name as asset_name',
     'asset_acquisitions.asset_group',
     'asset_acquisitions.brand',
    ])
    ->map(function ($rows) {
     $rows->out_date = date('Y-m-d', strtotime($rows->out_date));
     $rows->returnable_date = date('Y-m-d', strtotime('$rows->returnable_date'));
     $rows->item_description = $rows->asset_group . ',' . $rows->brand;
     return $rows;
    });
   echo json_encode($rows);
  }
 }
}
