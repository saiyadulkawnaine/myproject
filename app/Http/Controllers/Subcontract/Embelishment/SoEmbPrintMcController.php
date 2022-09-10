<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintMcRequest;

class SoEmbPrintMcController extends Controller
{

 private $soembprintmc;
 private $company;
 private $location;
 private $buyer;
 private $supplier;
 private $assetquantitycost;
 private $designation;
 private $department;

 public function __construct(
  SoEmbPrintMcRepository $soembprintmc,
  CompanyRepository $company,
  LocationRepository $location,
  BuyerRepository $buyer,
  SupplierRepository $supplier,
  AssetQuantityCostRepository $assetquantitycost,
  DesignationRepository $designation,
  DepartmentRepository $department
 ) {
  $this->soembprintmc = $soembprintmc;
  $this->company = $company;
  $this->location = $location;
  $this->buyer = $buyer;
  $this->supplier = $supplier;
  $this->assetquantitycost = $assetquantitycost;
  $this->designation = $designation;
  $this->department = $department;

  // $this->middleware('auth');
  // $this->middleware('permission:view.soembprintmcs',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.soembprintmcs', ['only' => ['store']]);
  // $this->middleware('permission:edit.soembprintmcs',   ['only' => ['update']]);
  // $this->middleware('permission:delete.soembprintmcs', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $row = $this->soembprintmc
   ->join("asset_quantity_costs", function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'so_emb_print_mcs.asset_quantity_cost_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'so_emb_print_mcs.company_id');
   })
   ->orderBy('so_emb_print_mcs.id', 'DESC')
   ->get([
    'so_emb_print_mcs.*',
    'companies.name as company_name',
    'asset_quantity_costs.asset_no'
   ]);
  echo json_encode($row);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->buyers(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $minuteadjustmentreasons = array_prepend(config('bprs.minuteadjustmentreasons'), '-Select-', '');
  $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '-Select-', '');
  $department = array_prepend(array_pluck($this->department->get(), 'name', 'id'), '-Select-', '');

  return Template::loadView("Subcontract.Embelishment.SoEmbPrintMc", ['company' => $company, 'location' => $location, 'yesno' => $yesno, 'buyer' => $buyer, 'minuteadjustmentreasons' => $minuteadjustmentreasons,  'designation' => $designation, 'department' => $department]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SoEmbPrintMcRequest $request)
 {
  $soembprintmc = $this->soembprintmc->create($request->except(['id', 'asset_no']));
  if ($soembprintmc) {
   return response()->json(array('success' => true, 'id' => $soembprintmc->id, 'message' => 'Save Successfully'), 200);
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
  $soembprintmc = $this->soembprintmc
   ->join("asset_quantity_costs", function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'so_emb_print_mcs.asset_quantity_cost_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'so_emb_print_mcs.company_id');
   })
   ->where([['so_emb_print_mcs.id', '=', $id]])
   ->get([
    'so_emb_print_mcs.*',
    'companies.name as company_name',
    'asset_quantity_costs.asset_no'
   ])
   ->first();
  $row['fromData'] = $soembprintmc;
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
 public function update(SoEmbPrintMcRequest $request, $id)
 {
  $soembprintmc = $this->soembprintmc->update($id, $request->except(['id', 'asset_no']));
  if ($soembprintmc) {
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
  if ($this->soembprintmc->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getPrintMcsetup()
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
   ->where([['asset_acquisitions.type_id', '=', 65]])
   ->where([['asset_acquisitions.production_area_id', '=', 45]])
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
