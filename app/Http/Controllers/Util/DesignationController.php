<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Library\Template;
use App\Http\Requests\DesignationRequest;

class DesignationController extends Controller
{
 private $designation;

 public function __construct(DesignationRepository $designation)
 {
  $this->designation = $designation;
  $this->middleware('auth');
  $this->middleware('permission:view.designations',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.designations', ['only' => ['store']]);
  $this->middleware('permission:edit.designations',   ['only' => ['update']]);
  $this->middleware('permission:delete.designations', ['only' => ['destroy']]);
 }
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $designationlevel = array_prepend(config('bprs.designationlevel'), '--', '');
  $employeecategory = array_prepend(config('bprs.employeecategory'), '--', '');
  $designations = array();
  $rows = $this->designation
   ->when(request('id'), function ($q) {
    return $q->where('designations.id', '=', request('id', 0));
   })
   ->orderBy('designations.id', 'desc')
   ->get();
  foreach ($rows as $row) {
   $designation['id'] = $row->id;
   $designation['name'] = $row->name;
   $designation['grade'] = $row->grade;
   $designation['designation_level_id'] = $designationlevel[$row->designation_level_id];
   $designation['employee_category_id'] = $employeecategory[$row->employee_category_id];
   array_push($designations, $designation);
  }
  echo json_encode($designations);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $designationlevel = array_prepend(config('bprs.designationlevel'), '', '');
  $employeecategory = array_prepend(config('bprs.employeecategory'), '-Select-', '');
  return Template::loadView("Util.Designation", ['designationlevel' => $designationlevel, 'employeecategory' => $employeecategory]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(DesignationRequest $request)
 {
  $designation = $this->designation->create($request->except(['id']));
  if ($designation) {
   return response()->json(array('success' => true, 'id' =>  $designation->id, 'message' => 'Save Successfully'), 200);
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
  $designation = $this->designation->find($id);
  $row['fromData'] = $designation;
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
 public function update(DesignationRequest $request, $id)
 {
  $designation = $this->designation->update($id, $request->except(['id']));
  if ($designation) {
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
  if ($this->designation->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
