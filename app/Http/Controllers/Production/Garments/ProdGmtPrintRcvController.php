<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;

use App\Library\Template;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Http\Requests\Production\Garments\ProdGmtPrintRcvRequest;

class ProdGmtPrintRcvController extends Controller
{

 private $prodgmtprintrcv;
 private $prodgmtdlvprint;
 private $location;
 private $company;
 private $supplier;

 public function __construct(ProdGmtPrintRcvRepository $prodgmtprintrcv, LocationRepository $location, CompanyRepository $company, SupplierRepository $supplier, ProdGmtDlvPrintRepository $prodgmtdlvprint, AssetAcquisitionRepository $assetacquisition, AssetQuantityCostRepository $assetquantitycost)
 {

  $this->prodgmtprintrcv = $prodgmtprintrcv;
  $this->prodgmtdlvprint = $prodgmtdlvprint;
  $this->location = $location;
  $this->company = $company;
  $this->supplier = $supplier;
  $this->assetacquisition = $assetacquisition;
  $this->assetquantitycost = $assetquantitycost;

  $this->middleware('auth');
  /*$this->middleware('permission:view.prodgmtprintrcvs',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtprintrcvs', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtprintrcvs',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtprintrcvs', ['only' => ['destroy']]);*/
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');

  $prodgmtprintrcvs = array();
  $rows = $this->prodgmtprintrcv
   ->orderBy('prod_gmt_print_rcvs.id', 'desc')
   ->get();
  foreach ($rows as $row) {
   $prodgmtprintrcv['id'] = $row->id;
   $prodgmtprintrcv['receive_no'] = $row->receive_no;
   $prodgmtprintrcv['party_challan_no'] = $row->party_challan_no;
   $prodgmtprintrcv['receive_date'] = date('Y-m-d', strtotime($row->receive_date));
   $prodgmtprintrcv['shiftname_id'] = isset($shiftname[$row->shiftname_id]) ? $shiftname[$row->shiftname_id] : '';
   $prodgmtprintrcv['remarks'] = $row->remarks;

   array_push($prodgmtprintrcvs, $prodgmtprintrcv);
  }
  echo json_encode($prodgmtprintrcvs);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create(Request $request)
 {
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->embellishmentSubcontractor(), 'name', 'id'), '', '');
  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $years = array_prepend(config('bprs.years'), '-Select-', '');
  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');
  //$assetquantitycost = array_prepend(array_pluck($this->assetquantitycost->get(),'custom_no','id'),'','');
  $assetquantitycost = array_prepend(array_pluck($this->assetquantitycost->leftJoin('asset_acquisitions', function ($join) use ($request) {
   $join->on('asset_quantity_costs.asset_acquisition_id', '=', 'asset_acquisitions.id');
  })
   ->where(['asset_acquisitions.production_area_id' => 45])
   ->get([
    'asset_quantity_costs.id',
    'asset_quantity_costs.custom_no'
   ]), 'custom_no', 'id'), '-Select-', 0);

  return Template::loadView('Production.Garments.ProdGmtPrintRcv', ['location' => $location, 'productionsource' => $productionsource, 'shiftname' => $shiftname, 'company' => $company, 'fabriclooks' => $fabriclooks, 'supplier' => $supplier, 'assetquantitycost' => $assetquantitycost, 'years' => $years]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProdGmtPrintRcvRequest $request)
 {
  $year = date('Y');
  $max = $this->prodgmtprintrcv->where([['year', '=', $year]])->max('receive_no');
  $receive_no = $max + 1;
  $prodgmtprintrcv = $this->prodgmtprintrcv->create([
   'receive_no' => $receive_no,
   'party_challan_no' => $request->party_challan_no,
   'prod_gmt_dlv_print_id' => $request->prod_gmt_dlv_print_id,
   'year' => $year,
   'receive_date' => $request->receive_date,
   'shiftname_id' => $request->shiftname_id,
   'remarks' => $request->remarks
  ]);
  if ($prodgmtprintrcv) {
   return response()->json(array('success' => true, 'id' =>  $prodgmtprintrcv->id,/*  'receive_no' => $receive_no , */ 'message' => 'Save Successfully'), 200);
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
  $prodgmtprintrcv = $this->prodgmtprintrcv
   ->leftJoin('prod_gmt_dlv_prints', function ($join) {
    $join->on('prod_gmt_dlv_prints.id', '=', 'prod_gmt_print_rcvs.prod_gmt_dlv_print_id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('prod_gmt_dlv_prints.supplier_id', '=', 'suppliers.id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('prod_gmt_dlv_prints.location_id', '=', 'locations.id');
   })
   ->where([['prod_gmt_print_rcvs.id', '=', $id]])
   ->get([
    'prod_gmt_print_rcvs.*',
    'prod_gmt_dlv_prints.supplier_id',
    'prod_gmt_dlv_prints.location_id',
    'prod_gmt_dlv_prints.challan_no',
    'suppliers.name as supplier_name',
    'locations.name as location_id'
   ])
   ->first();
  $row['fromData'] = $prodgmtprintrcv;
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
 public function update(ProdGmtPrintRcvRequest $request, $id)
 {
  $prodgmtprintrcv = $this->prodgmtprintrcv->update($id, $request->except(['id', 'receive_no', 'challan_no']));
  if ($prodgmtprintrcv) {
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
  if ($this->prodgmtprintrcv->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getPrintChallan()
 {

  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');

  $rows = $this->prodgmtdlvprint
   ->leftJoin('suppliers', function ($join) {
    $join->on('prod_gmt_dlv_prints.supplier_id', '=', 'suppliers.id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('prod_gmt_dlv_prints.location_id', '=', 'locations.id');
   })
   ->when(request('supplier_id'), function ($q) {
    return $q->where('prod_gmt_dlv_prints.supplier_id', '=', request('supplier_id', 0));
   })
   ->when(request('delivery_date'), function ($q) {
    return $q->where('prod_gmt_dlv_prints.delivery_date', '=', request('delivery_date', 0));
   })
   ->orderBy('prod_gmt_dlv_prints.id', 'desc')
   ->get([
    'prod_gmt_dlv_prints.*',
    'suppliers.name as supplier_name',
    'suppliers.id as supplier_id',
    'locations.name as location_id'
   ])
   ->map(function ($rows) use ($shiftname) {
    $rows->shiftname_id = $shiftname[$rows->shiftname_id];
    return $rows;
   });
  echo json_encode($rows);
 }
}
