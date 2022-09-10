<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtIronRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Http\Requests\Production\Garments\ProdGmtIronRequest;
use App\Library\Template;

class ProdGmtIronController extends Controller
{

 private $prodgmtiron;
 private $location;
 private $supplier;

 public function __construct(ProdGmtIronRepository $prodgmtiron, LocationRepository $location, SupplierRepository $supplier)
 {
  $this->prodgmtiron = $prodgmtiron;
  $this->location = $location;
  $this->supplier = $supplier;

  $this->middleware('auth');
  $this->middleware('permission:view.prodgmtirons',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.prodgmtirons', ['only' => ['store']]);
  $this->middleware('permission:edit.prodgmtirons',   ['only' => ['update']]);
  $this->middleware('permission:delete.prodgmtirons', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');

  $prodgmtirons = array();
  $rows = $this->prodgmtiron
   ->orderBy('prod_gmt_irons.id', 'desc')
   ->get();
  foreach ($rows as $row) {
   $prodgmtiron['id'] = $row->id;
   $prodgmtiron['iron_qc_date'] = date('Y-m-d', strtotime($row->iron_qc_date));
   $prodgmtiron['shiftname_id'] = $shiftname[$row->shiftname_id];
   $prodgmtiron['remarks'] = $row->remarks;
   array_push($prodgmtirons, $prodgmtiron);
  }
  echo json_encode($prodgmtirons);
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
  return Template::loadView('Production.Garments.ProdGmtIron', ['location' => $location, 'productionsource' => $productionsource, 'shiftname' => $shiftname, 'supplier' => $supplier]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProdGmtIronRequest $request)
 {
  $prodgmtiron = $this->prodgmtiron->create($request->except(['id']));
  if ($prodgmtiron) {
   return response()->json(array('success' => true, 'id' =>  $prodgmtiron->id, 'message' => 'Save Successfully'), 200);
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
  $prodgmtiron = $this->prodgmtiron->find($id);
  $row['fromData'] = $prodgmtiron;
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
 public function update(ProdGmtIronRequest $request, $id)
 {
  $prodgmtiron = $this->prodgmtiron->update($id, $request->except(['id']));
  if ($prodgmtiron) {
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
  if ($this->prodgmtiron->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
