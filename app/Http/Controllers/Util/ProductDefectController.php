<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ProductDefectRepository;
use App\Library\Template;
use App\Http\Requests\ProductDefectRequest;

class ProductDefectController extends Controller
{
 private $productdefect;

 public function __construct(ProductDefectRepository $productdefect)
 {
  $this->productdefect = $productdefect;
  $this->middleware('auth');
  // $this->middleware('permission:view.productdefectes',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.productdefectes', ['only' => ['store']]);
  // $this->middleware('permission:edit.productdefectes',   ['only' => ['update']]);
  // $this->middleware('permission:delete.productdefectes', ['only' => ['destroy']]);
 }
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $status = array_prepend(config('bprs.status'), '-Select-', '');
  $productdefectes = array();
  $rows = $this->productdefect
   ->orderBy('product_defects.id', '=', 'DESC')
   ->get();
  foreach ($rows as $row) {
   $productdefect['id'] = $row->id;
   $productdefect['defect_name'] = $row->defect_name;
   $productdefect['defect_code'] = $row->defect_code;
   $productdefect['productionarea'] = $productionarea[$row->production_area_id];
   $productdefect['status_id'] = $status[$row->status_id];
   array_push($productdefectes, $productdefect);
  }
  echo json_encode($productdefectes);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $status = array_prepend(config('bprs.status'), '-Select-', '');
  return Template::loadView("Util.ProductDefect", ['productionarea' => $productionarea, 'status' => $status]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProductDefectRequest $request)
 {
  $productdefect = $this->productdefect->create($request->except(['id']));
  if ($productdefect) {
   return response()->json(array('success' => true, 'id' => $productdefect->id, 'message' => 'Save Successfully'), 200);
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
  $productdefect = $this->productdefect->find($id);
  $row['fromData'] = $productdefect;
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
 public function update(ProductDefectRequest $request, $id)
 {
  $productdefect = $this->productdefect->update($id, $request->except(['id']));
  if ($productdefect) {
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
  if ($this->productdefect->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
