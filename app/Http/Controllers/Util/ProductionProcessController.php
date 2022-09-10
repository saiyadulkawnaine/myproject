<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Library\Template;
use App\Http\Requests\ProductionProcessRequest;

class ProductionProcessController extends Controller
{
 private $productionprocess;

 public function __construct(ProductionProcessRepository $productionprocess)
 {
  $this->productionprocess = $productionprocess;
  $this->middleware('auth');
  $this->middleware('permission:view.productionprocesses',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.productionprocesses', ['only' => ['store']]);
  $this->middleware('permission:edit.productionprocesses',   ['only' => ['update']]);
  $this->middleware('permission:delete.productionprocesses', ['only' => ['destroy']]);
 }
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $productionprocesses = array();
  $rows = $this->productionprocess->get();
  foreach ($rows as $row) {
   $productionprocess['id'] = $row->id;
   $productionprocess['processname'] = $row->process_name;
   $productionprocess['productionarea'] = $productionarea[$row->production_area_id];
   array_push($productionprocesses, $productionprocess);
  }
  echo json_encode($productionprocesses);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  return Template::loadView("Util.ProductionProcess", ['productionarea' => $productionarea]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProductionProcessRequest $request)
 {
  $productionprocess = $this->productionprocess->create($request->except(['id']));
  if ($productionprocess) {
   return response()->json(array('success' => true, 'id' => $productionprocess->id, 'message' => 'Save Successfully'), 200);
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
  $productionprocess = $this->productionprocess->find($id);
  $row['fromData'] = $productionprocess;
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
 public function update(ProductionProcessRequest $request, $id)
 {
  $productionprocess = $this->productionprocess->update($id, $request->except(['id']));
  if ($productionprocess) {
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
  if ($this->productionprocess->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
