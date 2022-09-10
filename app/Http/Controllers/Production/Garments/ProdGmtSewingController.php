<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;

use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtSewingRequest;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;

class ProdGmtSewingController extends Controller
{

 private $prodgmtsewing;
 private $location;

 public function __construct(ProdGmtSewingRepository $prodgmtsewing, LocationRepository $location, SupplierRepository $supplier)
 {
  $this->prodgmtsewing = $prodgmtsewing;
  $this->supplier = $supplier;
  $this->location = $location;
  $this->middleware('auth');
  $this->middleware('permission:view.prodgmtsewings',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.prodgmtsewings', ['only' => ['store']]);
  $this->middleware('permission:edit.prodgmtsewings',   ['only' => ['update']]);
  $this->middleware('permission:delete.prodgmtsewings', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');

  $prodgmtsewings = array();
  $rows = $this->prodgmtsewing
   ->orderBy('prod_gmt_sewings.id', 'desc')
   ->get();
  foreach ($rows as $row) {
   $prodgmtsewing['id'] = $row->id;
   $prodgmtsewing['sew_qc_date'] = date('Y-m-d', strtotime($row->sew_qc_date));
   $prodgmtsewing['shiftname_id'] = $shiftname[$row->shiftname_id];
   $prodgmtsewing['remarks'] = $row->remarks;
   array_push($prodgmtsewings, $prodgmtsewing);
  }
  echo json_encode($prodgmtsewings);
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
  return Template::loadView('Production.Garments.ProdGmtSewing', ['location' => $location, 'productionsource' => $productionsource, 'shiftname' => $shiftname, 'supplier' => $supplier]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProdGmtSewingRequest $request)
 {
  $prodgmtsewing = $this->prodgmtsewing->create($request->except(['id']));
  if ($prodgmtsewing) {
   return response()->json(array('success' => true, 'id' =>  $prodgmtsewing->id, 'message' => 'Save Successfully'), 200);
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
  $prodgmtsewing = $this->prodgmtsewing->find($id);
  $row['fromData'] = $prodgmtsewing;
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
 public function update(ProdGmtSewingRequest $request, $id)
 {
  $prodgmtsewing = $this->prodgmtsewing->update($id, $request->except(['id']));
  if ($prodgmtsewing) {
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
  if ($this->prodgmtsewing->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
