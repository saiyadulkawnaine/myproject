<?php

namespace App\Http\Controllers\Production\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\FloorRepository;
use App\Repositories\Contracts\Util\GmtssampleRepository;
use App\Library\Template;
use App\Http\Requests\Production\Kniting\ProdKnitRequest;

class ProdKnitController extends Controller
{

 private $prodknit;
 private $company;
 private $supplier;
 private $buyer;
 private $location;
 private $floor;
 private $gmtssample;

 public function __construct(
  ProdKnitRepository $prodknit,
  CompanyRepository $company,
  SupplierRepository $supplier,
  BuyerRepository $buyer,
  LocationRepository $location,
  FloorRepository $floor,
  GmtssampleRepository $gmtssample
 ) {
  $this->prodknit = $prodknit;
  $this->company = $company;
  $this->supplier = $supplier;
  $this->buyer = $buyer;
  $this->location = $location;
  $this->floor = $floor;
  $this->gmtssample = $gmtssample;
  $this->middleware('auth');

  $this->middleware('permission:view.prodknits',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.prodknits', ['only' => ['store']]);
  $this->middleware('permission:edit.prodknits',   ['only' => ['update']]);
  $this->middleware('permission:delete.prodknits', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $rows = $this->prodknit
   ->leftJoin('suppliers', function ($join) {
    $join->on('prod_knits.supplier_id', '=', 'suppliers.id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('prod_knits.location_id', '=', 'locations.id');
   })
   ->leftJoin('floors', function ($join) {
    $join->on('prod_knits.floor_id', '=', 'floors.id');
   })
   ->orderBy('prod_knits.id', 'desc')
   ->take(100)
   ->get([
    'prod_knits.*',
    'suppliers.name as supplier_name',
    'locations.name as location_name',
    'floors.name as floor_name',
   ])
   ->map(function ($rows) {
    $rows->shift_name = $rows->shift_name;
    $rows->prod_date = date('d-M-Y', strtotime($rows->prod_date));
    return $rows;
   });
  return response()->json($rows);
  //echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $supplier = array_prepend(array_pluck($this->supplier->knitSubcontractor(), 'name', 'id'), '-Select-', '');
  $yarnsupplier = array_prepend(array_pluck($this->supplier->yarnSupplier(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $floor = array_prepend(array_pluck($this->floor->get(), 'name', 'id'), '-Select-', '');
  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');
  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
  $gmtssample = array_prepend(array_pluck($this->gmtssample->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');


  return Template::loadView('Production.Kniting.ProdKnit', [
   'supplier' => $supplier,
   'location' => $location,
   'floor' => $floor,
   'productionsource' => $productionsource,
   'shiftname' => $shiftname,
   'fabriclooks' => $fabriclooks,
   'fabricshape' => $fabricshape,
   'gmtssample' => $gmtssample,
   'buyer' => $buyer,
   'yarnsupplier' => $yarnsupplier
  ]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProdKnitRequest $request)
 {
  $max = $this->prodknit->where([['basis_id', $request->basis_id]])->max('prod_no');
  $prod_no = $max + 1;
  $prodknit = $this->prodknit->create(['prod_no' => $prod_no, 'prod_date' => $request->prod_date, 'supplier_id' => $request->supplier_id, 'basis_id' => $request->basis_id, 'location_id' => $request->location_id, 'floor_id' => $request->floor_id, 'shift_id' => $request->shift_id, 'challan_no' => $request->challan_no]);
  if ($prodknit) {
   return response()->json(array('success' => true, 'id' =>  $prodknit->id, 'message' => 'Save Successfully'), 200);
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
  $prodknit = $this->prodknit->find($id);
  $prodknit['prod_date'] = date('Y-m-d', strtotime($prodknit->prod_date));
  $row['fromData'] = $prodknit;
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
 public function update(ProdKnitRequest $request, $id)
 {
  $prodknit = $this->prodknit->update($id, $request->except(['id', 'prod_no', 'basis_id', 'supplier_id']));
  if ($prodknit) {
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
  if ($this->prodknit->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getProd()
 {

  $rows = $this->prodknit
   ->leftJoin('suppliers', function ($join) {
    $join->on('prod_knits.supplier_id', '=', 'suppliers.id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('prod_knits.location_id', '=', 'locations.id');
   })
   ->leftJoin('floors', function ($join) {
    $join->on('prod_knits.floor_id', '=', 'floors.id');
   })
   ->when(request('from_prod_date'), function ($q) {
    return $q->where('prod_knits.prod_date', '>=', request('from_prod_date', 0));
   })
   ->when(request('to_prod_date'), function ($q) {
    return $q->where('prod_knits.prod_date', '<=', request('to_prod_date', 0));
   })
   ->orderBy('prod_knits.id', 'desc')

   ->get([
    'prod_knits.*',
    'suppliers.name as supplier_name',
    'locations.name as location_name',
    'floors.name as floor_name',
   ])
   ->map(function ($rows) {
    $rows->shift_name = $rows->shift_name;
    $rows->prod_date = date('d-M-Y', strtotime($rows->prod_date));
    return $rows;
   });
  return response()->json($rows);
 }
}
