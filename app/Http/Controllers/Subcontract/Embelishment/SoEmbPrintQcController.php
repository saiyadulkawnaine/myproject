<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintQcRequest;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;

class SoEmbPrintQcController extends Controller
{

 private $soembprintqc;
 private $location;
 private $company;
 private $buyer;
 public function __construct(
  SoEmbPrintQcRepository $soembprintqc,
  LocationRepository $location,
  SupplierRepository $supplier,
  CompanyRepository $company,
  BuyerRepository $buyer
 ) {
  $this->soembprintqc = $soembprintqc;
  $this->supplier = $supplier;
  $this->location = $location;
  $this->company = $company;
  $this->buyer = $buyer;
  // $this->middleware('auth');
  // $this->middleware('permission:view.soembprintqcs',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.soembprintqcs', ['only' => ['store']]);
  // $this->middleware('permission:edit.soembprintqcs',   ['only' => ['update']]);
  // $this->middleware('permission:delete.soembprintqcs', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');

  $soembprintqcs = array();
  $rows = $this->soembprintqc
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'so_emb_print_qcs.company_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'so_emb_print_qcs.buyer_id');
   })
   ->orderBy('so_emb_print_qcs.id', 'desc')
   ->get([
    'so_emb_print_qcs.*',
    'companies.name as company_name',
    'buyers.name as buyer_name'
   ]);
  foreach ($rows as $row) {
   $soembprintqc['id'] = $row->id;
   $soembprintqc['company_name'] = $row->company_name;
   $soembprintqc['buyer_name'] = $row->buyer_name;
   $soembprintqc['so_qc_date'] = date('Y-m-d', strtotime($row->so_qc_date));
   $soembprintqc['shiftname_id'] = $shiftname[$row->shiftname_id];
   $soembprintqc['remarks'] = $row->remarks;
   array_push($soembprintqcs, $soembprintqc);
  }
  echo json_encode($soembprintqcs);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->garmentSubcontractors(), 'name', 'id'), '', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
  return Template::loadView('Subcontract.Embelishment.SoEmbPrintQc', ['location' => $location, 'productionsource' => $productionsource, 'shiftname' => $shiftname, 'supplier' => $supplier, 'company' => $company, 'buyer' => $buyer]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SoEmbPrintQcRequest $request)
 {
  $soembprintqc = $this->soembprintqc->create($request->except(['id']));
  if ($soembprintqc) {
   return response()->json(array('success' => true, 'id' =>  $soembprintqc->id, 'message' => 'Save Successfully'), 200);
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
  $soembprintqc = $this->soembprintqc
   ->find($id);
  $row['fromData'] = $soembprintqc;
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
 public function update(SoEmbPrintQcRequest $request, $id)
 {
  $soembprintqc = $this->soembprintqc->update($id, $request->except(['id']));
  if ($soembprintqc) {
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
  if ($this->soembprintqc->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
