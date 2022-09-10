<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Library\Template;
use App\Http\Requests\LocationRequest;

class LocationController extends Controller
{

 private $location;

 public function __construct(LocationRepository $location)
 {
  $this->location = $location;
  $this->middleware('auth');
  $this->middleware('permission:view.locations',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.locations', ['only' => ['store']]);
  $this->middleware('permission:edit.locations',   ['only' => ['update']]);
  $this->middleware('permission:delete.locations', ['only' => ['destroy']]);
 }
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  echo json_encode($this->location->get());
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  return Template::loadView("Util.Location");
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(LocationRequest $request)
 {
  $location = $this->location->create($request->except(['id']));
  if ($location) {
   return response()->json(array('success' => true, 'id' =>  $location->id, 'message' => 'Save Successfully'), 200);
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
  $location = $this->location->find($id);
  $row['fromData'] = $location;
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
 public function update(LocationRequest $request, $id)
 {
  $location = $this->location->update($id, $request->except(['id']));
  if ($location) {
   return response()->json(array('success' => true, 'id' =>  $id, 'message' => 'Update Successfully'), 200);
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
  if ($this->location->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
