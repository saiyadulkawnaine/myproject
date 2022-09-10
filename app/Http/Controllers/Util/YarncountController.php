<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Library\Template;
use App\Http\Requests\YarncountRequest;

class YarncountController extends Controller
{
 private $yarncount;

 public function __construct(YarncountRepository $yarncount)
 {
  $this->yarncount = $yarncount;
  $this->middleware('auth');
  $this->middleware('permission:view.yarncounts',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.yarncounts', ['only' => ['store']]);
  $this->middleware('permission:edit.yarncounts',   ['only' => ['update']]);
  $this->middleware('permission:delete.yarncounts', ['only' => ['destroy']]);
 }
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $yarncounts = array();
  $rows = $this->yarncount->get();
  foreach ($rows as $row) {
   $yarncount['id'] = $row->id;
   $yarncount['count'] = $row->count;
   $yarncount['symbol'] = $row->symbol;
   array_push($yarncounts, $yarncount);
  }
  echo json_encode($yarncounts);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  return Template::loadView("Util.Yarncount");
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(YarncountRequest $request)
 {
  $yarncount = $this->yarncount->create($request->except(['id']));
  if ($yarncount) {
   return response()->json(array('success' => true, 'id' => $yarncount->id, 'message' => 'Save Successfully'), 200);
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
  $yarncount = $this->yarncount->find($id);
  $row['fromData'] = $yarncount;
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
 public function update(YarncountRequest $request, $id)
 {
  $yarncount = $this->yarncount->update($id, $request->except(['id']));
  if ($yarncount) {
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
  if ($this->yarncount->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
