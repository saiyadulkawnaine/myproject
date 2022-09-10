<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlMinajRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintMcDtlMinajRequest;

class SoEmbPrintMcDtlMinajController extends Controller
{

 private $soemboprintmc;
 private $soemboprintmcdtl;
 private $soemboprintmcdtlminaj;

 public function __construct(
  SoEmbPrintMcDtlMinajRepository $soemboprintmcdtlminaj,
  SoEmbPrintMcDtlRepository $soemboprintmcdtl,
  SoEmbPrintMcRepository $soemboprintmc
 ) {
  $this->soemboprintmcdtl = $soemboprintmcdtl;
  $this->soemboprintmcdtlminaj = $soemboprintmcdtlminaj;
  $this->soemboprintmc = $soemboprintmc;


  $this->middleware('auth');
  // $this->middleware('permission:view.soemboprintmcdtlminajs',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.soemboprintmcdtlminajs', ['only' => ['store']]);
  // $this->middleware('permission:edit.soemboprintmcdtlminajs',   ['only' => ['update']]);
  // $this->middleware('permission:delete.soemboprintmcdtlminajs', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $minuteadjustmentreasons = array_prepend(config('bprs.minuteadjustmentreasons'), '-Select-', '');
  $rows = $this->soemboprintmcdtlminaj
   ->where([['so_emb_print_mc_dtl_id', '=', request('so_emb_print_mc_dtl_id', 0)]])
   ->orderBy('so_emb_print_mc_dtl_minajs.id', 'desc')
   ->get([
    'so_emb_print_mc_dtl_minajs.*',
   ])
   ->map(function ($rows) use ($minuteadjustmentreasons) {
    $rows->minute_adj_reason_id = $minuteadjustmentreasons[$rows->minute_adj_reason_id];
    return $rows;
   });

  echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  //
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SoEmbPrintMcDtlMinajRequest $request)
 {
  $soemboprintmcdtlminaj = $this->soemboprintmcdtlminaj->create([
   'so_emb_print_mc_dtl_id' => $request->so_emb_print_mc_dtl_id,
   'minute_adj_reason_id' => $request->minute_adj_reason_id,
   'no_of_hour' => $request->no_of_hour,
   'no_of_resource' => $request->no_of_resource,
   'no_of_minute' => $request->no_of_minute
  ]);
  if ($soemboprintmcdtlminaj) {
   return response()->json(array('success' => true, 'id' => $soemboprintmcdtlminaj->id, 'message' => 'Save Successfully'), 200);
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
  $soemboprintmcdtlminaj = $this->soemboprintmcdtlminaj->find($id);
  $row['fromData'] = $soemboprintmcdtlminaj;
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
 public function update(SoEmbPrintMcDtlMinajRequest $request, $id)
 {


  $soemboprintmcdtlminaj = $this->soemboprintmcdtlminaj->update($id, [
   'so_emb_print_mc_dtl_id' => $request->so_emb_print_mc_dtl_id,
   'minute_adj_reason_id' => $request->minute_adj_reason_id,
   'no_of_hour' => $request->no_of_hour,
   'no_of_resource' => $request->no_of_resource,
   'no_of_minute' => $request->no_of_minute
  ]);
  if ($soemboprintmcdtlminaj) {
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
  if ($this->soemboprintmcdtlminaj->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
