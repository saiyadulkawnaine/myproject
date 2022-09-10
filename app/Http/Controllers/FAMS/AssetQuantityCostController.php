<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Util\FloorRepository;

use App\Library\Template;
use App\Http\Requests\FAMS\AssetQuantityCostRequest;

class AssetQuantityCostController extends Controller
{

 private $assetacquisition;
 private $assetquantitycost;
 private $assetdisposal;
 private $company;
 private $location;
 private $division;
 private $section;
 private $subsection;
 private $floor;

 public function __construct(
  AssetAcquisitionRepository $assetacquisition,
  AssetQuantityCostRepository $assetquantitycost,
  AssetDisposalRepository $assetdisposal,
  CompanyRepository $company,
  LocationRepository $location,
  DepartmentRepository $department,
  DivisionRepository $division,
  SectionRepository $section,
  SubsectionRepository $subsection,
  FloorRepository $floor
 ) {

  $this->assetacquisition = $assetacquisition;
  $this->assetquantitycost = $assetquantitycost;
  $this->assetdisposal = $assetdisposal;
  $this->company = $company;
  $this->location = $location;
  $this->division = $division;
  $this->section = $section;
  $this->subsection = $subsection;
  $this->department = $department;
  $this->floor = $floor;


  $this->middleware('auth');
  $this->middleware('permission:view.assetquantitycosts',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.assetquantitycosts', ['only' => ['store']]);
  $this->middleware('permission:edit.assetquantitycosts',   ['only' => ['update']]);
  $this->middleware('permission:delete.assetquantitycosts', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $assetacquisition = array_prepend(array_pluck($this->assetacquisition->get(), 'name', 'id'), '-Select-', '');
  $assetquantitycosts = array();
  $rows = $this->assetquantitycost->get(); //->where([['asset_acquisition_id','=',request('asset_acquisition_id',0)]])
  foreach ($rows as $row) {
   $assetquantitycost['id'] = $row->id;
   $assetquantitycost['asset_no'] = $row->asset_no;
   $assetquantitycost['asset_acquisition_id'] = $assetacquisition[$row->asset_acquisition_id];
   $assetquantitycost['serial_no'] = $row->serial_no; //str_pad($row->id,6,0,STR_PAD_LEFT);
   $assetquantitycost['custom_no'] = $row->custom_no;
   $assetquantitycost['qty'] = $row->qty;
   $assetquantitycost['rate'] = $row->rate;
   $assetquantitycost['vendor_price'] = $row->rate;
   $assetquantitycost['landed_price'] = $row->landed_price;
   $assetquantitycost['machanical_cost'] = $row->machanical_cost;
   $assetquantitycost['civil_cost'] = $row->civil_cost;
   $assetquantitycost['salvage_value'] = $row->civil_cost;
   $assetquantitycost['life_time'] = $row->life_time;
   $assetquantitycost['accumulated_dep'] = $row->accumulated_dep;
   $assetquantitycost['civil_cost'] = $row->civil_cost;
   $assetquantitycost['electrical_cost'] = $row->electrical_cost;
   $assetquantitycost['warrantee_close'] = date('Y-m-d', strtotime($row->warrantee_close));
   $assetquantitycost['total_cost'] = $row->total_cost = ($row->vendor_price + $row->landed_price + $row->machanical_cost + $row->civil_cost + $row->electrical_cost);
   array_push($assetquantitycosts, $assetquantitycost);
  }
  echo json_encode($assetquantitycosts);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $department = array_prepend(array_pluck($this->department->get(), 'name', 'id'), '', '');
  $division = array_prepend(array_pluck($this->division->get(), 'name', 'id'), '', '');
  $section = array_prepend(array_pluck($this->section->get(), 'name', 'id'), '', '');
  $subsection = array_prepend(array_pluck($this->subsection->get(), 'name', 'id'), '', '');
  $floor = array_prepend(array_pluck($this->floor->get(), 'name', 'id'), '', '');

  //['asset_acquisition_id'=>request('id',0) ,
  $assetquantitycost = $this->assetquantitycost
   ->selectRaw(
    'asset_acquisitions.qty,
            asset_quantity_costs.id,
            asset_quantity_costs.asset_acquisition_id,
            asset_quantity_costs.asset_no,
            asset_quantity_costs.custom_no,
            asset_quantity_costs.serial_no,
            asset_quantity_costs.qty,
            asset_quantity_costs.rate,
            asset_quantity_costs.vendor_price,
            asset_quantity_costs.landed_price,
            asset_quantity_costs.machanical_cost,
            asset_quantity_costs.civil_cost,
            asset_quantity_costs.electrical_cost,
            asset_quantity_costs.total_cost,
            asset_quantity_costs.accumulated_dep,
            asset_quantity_costs.salvage_value,
            asset_quantity_costs.life_time,
            asset_quantity_costs.warrantee_close,
            asset_quantity_costs.company_id,
            asset_quantity_costs.location_id,
            asset_quantity_costs.department_id,
            asset_quantity_costs.division_id,
            asset_quantity_costs.section_id,
            asset_quantity_costs.subsection_id,
            asset_quantity_costs.floor_id,
            asset_quantity_costs.room_no,
            asset_quantity_costs.remarks'
   )
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->where([['asset_acquisition_id', '=', request('id', 0)]])
   ->get([
    'asset_quantity_costs.*',
    'asset_acquisitions.id as asset_acquisition_id',
    'asset_acquisitions.qty',
    'asset_acquisitions.type_id',
   ]);
  //echo json_encode($assetquantitycost);
  //$dropdown['qtc'] = "'".Template::loadView('FAMS.SetAssetQuantity',['asset_acquisition_id'=>request('id',0),'qty'=>request('qty',0),'assetquantitycost'=>$assetquantitycost])."'";
  //$row ['dropDown'] = $dropdown;
  //echo json_encode($row);
  if ($assetquantitycost->count()) {
   return Template::loadView('FAMS.EditAssetQuantity', [
    'asset_acquisition_id' => request('id', 0), 'data' => $assetquantitycost, 'qty' => request('qty', 0), 'company' => $company, 'location' => $location,
    'department' => $department, 'division' => $division, 'section' => $section, 'subsection' => $subsection, 'floor' => $floor
   ]);
  } else {
   return Template::loadView('FAMS.SetAssetQuantity', ['asset_acquisition_id' => request('id', 0), 'qty' => request('qty', 0), 'company' => $company, 'location' => $location, 'department' => $department, 'division' => $division, 'section' => $section, 'subsection' => $subsection, 'floor' => $floor]);
  }
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(AssetQuantityCostRequest $request)
 {
  if ($request->is_edit == 0) {
   foreach ($request->asset_acquisition_id as $index => $asset_acquisition_id) {
    if ($request->qty[$index]) {
     $assetquantitycost = $this->assetquantitycost->create(
      [
       'asset_acquisition_id' => $request->asset_acquisition_id[$index],
       'asset_no' => $request->asset_no[$index],
       'custom_no' => $request->custom_no[$index],
       'serial_no' => $request->serial_no[$index],
       'qty' => $request->qty[$index],
       'rate' => $request->rate[$index],
       'vendor_price' => $request->vendor_price[$index],
       'landed_price' => $request->landed_price[$index],
       'machanical_cost' => $request->machanical_cost[$index],
       'civil_cost' => $request->civil_cost[$index],
       'electrical_cost' => $request->electrical_cost[$index],
       'warrantee_close' => $request->warrantee_close[$index],
       'accumulated_dep' => $request->accumulated_dep[$index],
       'salvage_value' => $request->salvage_value[$index],
       'life_time' => $request->life_time[$index],
       'total_cost' => $request->total_cost[$index],
       'company_id' => $request->company_id[$index],
       'location_id' => $request->location_id[$index],
       'department_id' => $request->department_id[$index],
       'division_id' => $request->division_id[$index],
       'section_id' => $request->section_id[$index],
       'subsection_id' => $request->subsection_id[$index],
       'floor_id' => $request->floor_id[$index],
       'room_no' => $request->room_no[$index],
       'remarks' => $request->remarks[$index],
      ]
     );
    }
   }
   if ($assetquantitycost) {
    return response()->json(array('success' => true, 'id' => $assetquantitycost->id, 'message' => 'Save Successfully'), 200);
   }
  }
  if ($request->is_edit == 1) {
   $this->update($request, $request->id);
   return response()->json(array('success' => true, 'id' => $request->id, 'message' => 'Update Successfully'), 200);
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
  $assetquantitycost = $this->assetquantitycost->find($id);
  $row['fromData'] = $assetquantitycost;
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
 public function update(AssetQuantityCostRequest $request, $id)
 {
  foreach ($request->asset_acquisition_id as $index => $asset_acquisition_id) {
   if ($request->qty[$index]) {
    $assetquantitycost = $this->assetquantitycost->update(
     $request->id[$index],
     [
      'asset_acquisition_id' => $request->asset_acquisition_id[$index],
      'asset_no' => $request->asset_no[$index],
      'custom_no' => $request->custom_no[$index],
      'serial_no' => $request->serial_no[$index],
      'qty' => $request->qty[$index],
      'rate' => $request->rate[$index],
      'vendor_price' => $request->vendor_price[$index],
      'landed_price' => $request->landed_price[$index],
      'machanical_cost' => $request->machanical_cost[$index],
      'civil_cost' => $request->civil_cost[$index],
      'electrical_cost' => $request->electrical_cost[$index],
      'warrantee_close' => $request->warrantee_close[$index],
      'total_cost' => $request->total_cost[$index],
      'accumulated_dep' => $request->accumulated_dep[$index],
      'salvage_value' => $request->salvage_value[$index],
      'life_time' => $request->life_time[$index],
      'company_id' => $request->company_id[$index],
      'location_id' => $request->location_id[$index],
      'department_id' => $request->department_id[$index],
      'division_id' => $request->division_id[$index],
      'section_id' => $request->section_id[$index],
      'subsection_id' => $request->subsection_id[$index],
      'floor_id' => $request->floor_id[$index],
      'room_no' => $request->room_no[$index],
      'remarks' => $request->remarks[$index],
     ]
    );
   }
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
  if ($this->assetquantitycost->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
