<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintEntryRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintEntryRequest;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;

class SoEmbPrintEntryController extends Controller
{

 private $soembprintentry;
 private $location;
 private $company;
 private $buyer;
 public function __construct(
  SoEmbPrintEntryRepository $soembprintentry,
  LocationRepository $location,
  SupplierRepository $supplier,
  CompanyRepository $company,
  BuyerRepository $buyer
 ) {
  $this->soembprintentry = $soembprintentry;
  $this->supplier = $supplier;
  $this->location = $location;
  $this->company = $company;
  $this->buyer = $buyer;
  // $this->middleware('auth');
  // $this->middleware('permission:view.soembprintentrys',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.soembprintentrys', ['only' => ['store']]);
  // $this->middleware('permission:edit.soembprintentrys',   ['only' => ['update']]);
  // $this->middleware('permission:delete.soembprintentrys', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');

  $soembprintentrys = array();
  $rows = $this->soembprintentry
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'so_emb_print_entries.company_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'so_emb_print_entries.buyer_id');
   })
   ->orderBy('so_emb_print_entries.id', 'desc')
   ->get([
    'so_emb_print_entries.*',
    'companies.name as company_name',
    'buyers.name as buyer_name'
   ]);
  foreach ($rows as $row) {
   $soembprintentry['id'] = $row->id;
   $soembprintentry['company_name'] = $row->company_name;
   $soembprintentry['buyer_name'] = $row->buyer_name;
   $soembprintentry['prod_date'] = date('Y-m-d', strtotime($row->prod_date));
   $soembprintentry['shiftname_id'] = $shiftname[$row->shiftname_id];
   $soembprintentry['remarks'] = $row->remarks;
   array_push($soembprintentrys, $soembprintentry);
  }
  echo json_encode($soembprintentrys);
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
  $buyer = array_prepend(array_pluck($this->buyer->embelishmentSubcontact(), 'name', 'id'), '-Select-', '');
  return Template::loadView('Subcontract.Embelishment.SoEmbPrintEntry', ['location' => $location, 'productionsource' => $productionsource, 'shiftname' => $shiftname, 'supplier' => $supplier, 'company' => $company, 'buyer' => $buyer]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SoEmbPrintEntryRequest $request)
 {
  $soembprintentry = $this->soembprintentry->create($request->except(['id']));
  if ($soembprintentry) {
   return response()->json(array('success' => true, 'id' =>  $soembprintentry->id, 'message' => 'Save Successfully'), 200);
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
  $soembprintentry = $this->soembprintentry
   ->find($id);
  $row['fromData'] = $soembprintentry;
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
 public function update(SoEmbPrintEntryRequest $request, $id)
 {
  $soembprintentry = $this->soembprintentry->update($id, $request->except(['id']));
  if ($soembprintentry) {
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
  if ($this->soembprintentry->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
