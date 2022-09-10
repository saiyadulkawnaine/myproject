<?php

namespace App\Http\Controllers\Workstudy;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupDtlRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupMinAdjRepository;
use App\Library\Template;
use App\Http\Requests\Workstudy\WstudyLineSetupMinAdjRequest;

class WstudyLineSetupMinAdjController extends Controller
{

 private $lineresourcesetup;
 private $setupdetail;
 private $wstudylinesetupminadj;

 public function __construct(
  WstudyLineSetupMinAdjRepository $wstudylinesetupminadj,
  WstudyLineSetupDtlRepository $setupdetail,
  WstudyLineSetupRepository $lineresourcesetup
 ) {
  $this->setupdetail = $setupdetail;
  $this->wstudylinesetupminadj = $wstudylinesetupminadj;
  $this->lineresourcesetup = $lineresourcesetup;


  $this->middleware('auth');
  // $this->middleware('permission:view.wstudylinesetupminadjs',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.wstudylinesetupminadjs', ['only' => ['store']]);
  // $this->middleware('permission:edit.wstudylinesetupminadjs',   ['only' => ['update']]);
  // $this->middleware('permission:delete.wstudylinesetupminadjs', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $minuteadjustmentreasons = array_prepend(config('bprs.minuteadjustmentreasons'), '-Select-', '');
  $setupdetails = array();
  $rows = $this->wstudylinesetupminadj
   ->where([['wstudy_line_setup_dtl_id', '=', request('wstudy_line_setup_dtl_id', 0)]])
   ->orderBy('wstudy_line_setup_min_adjs.id', 'desc')
   ->get([
    'wstudy_line_setup_min_adjs.*',
   ]);
  foreach ($rows as $row) {
   $setupdetail['id'] = $row->id;
   $setupdetail['minute_adj_reason_id'] = $minuteadjustmentreasons[$row->minute_adj_reason_id];
   $setupdetail['no_of_resource'] = $row->no_of_resource;
   $setupdetail['no_of_hour'] = $row->no_of_hour;
   $setupdetail['no_of_minute'] = $row->no_of_minute;
   array_push($setupdetails, $setupdetail);
  }
  echo json_encode($setupdetails);
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
 public function store(WstudyLineSetupMinAdjRequest $request)
 {
  $wstudylinesetupminadj = $this->wstudylinesetupminadj->create([
   'wstudy_line_setup_dtl_id' => $request->wstudy_line_setup_dtl_id,
   'minute_adj_reason_id' => $request->minute_adj_reason_id,
   'no_of_hour' => $request->no_of_hour,
   'no_of_resource' => $request->no_of_resource,
   'no_of_minute' => $request->no_of_minute
  ]);
  if ($wstudylinesetupminadj) {
   return response()->json(array('success' => true, 'id' => $wstudylinesetupminadj->id, 'message' => 'Save Successfully'), 200);
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
  $setupdetail = $this->wstudylinesetupminadj->find($id);
  $row['fromData'] = $setupdetail;
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
 public function update(WstudyLineSetupMinAdjRequest $request, $id)
 {


  $wstudylinesetupminadj = $this->wstudylinesetupminadj->update($id, [
   'wstudy_line_setup_dtl_id' => $request->wstudy_line_setup_dtl_id,
   'minute_adj_reason_id' => $request->minute_adj_reason_id,
   'no_of_hour' => $request->no_of_hour,
   'no_of_resource' => $request->no_of_resource,
   'no_of_minute' => $request->no_of_minute
  ]);
  if ($wstudylinesetupminadj) {
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
  if ($this->wstudylinesetupminadj->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
