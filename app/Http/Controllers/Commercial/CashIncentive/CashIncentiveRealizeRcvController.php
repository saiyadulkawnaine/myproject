<?php

namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRealizeRcvRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveRealizeRcvRequest;



class CashIncentiveRealizeRcvController extends Controller
{

 private $cashincentiverealizercv;
 private $cashincentiveref;
 private $commercialhead;


 public function __construct(CashIncentiveRealizeRcvRepository $cashincentiverealizercv, CashIncentiveRefRepository $cashincentiveref, CommercialHeadRepository $commercialhead)
 {
  $this->cashincentiverealizercv = $cashincentiverealizercv;
  $this->cashincentiveref = $cashincentiveref;
  $this->commercialhead = $commercialhead;

  $this->middleware('auth');
  $this->middleware('permission:view.cashincentiverealizercvs',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.cashincentiverealizercvs', ['only' => ['store']]);
  $this->middleware('permission:edit.cashincentiverealizercvs',   ['only' => ['update']]);
  $this->middleware('permission:delete.cashincentiverealizercvs', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $commercialhead = array_prepend(array_pluck($this->commercialhead->get(), 'name', 'id'), '-Select-', '');
  $rows = $this->cashincentiverealizercv
   ->join('cash_incentive_realizes', function ($join) {
    $join->on('cash_incentive_realizes.id', '=', 'cash_incentive_realize_rcvs.cash_incentive_realize_id');
   })
   ->where([['cash_incentive_realize_id', '=', request('cash_incentive_realize_id', 0)]])
   ->orderBy('cash_incentive_realize_rcvs.id', 'desc')
   ->get([
    'cash_incentive_realize_rcvs.*',
    'cash_incentive_realizes.sanctioned_amount'
   ])
   ->map(function ($rows) use ($commercialhead) {
    $rows->commercial_head_id = $commercialhead[$rows->commercial_head_id];
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
 public function store(CashIncentiveRealizeRcvRequest $request)
 {
  $cashincentiverealizercv = $this->cashincentiverealizercv->create([
   'cash_incentive_realize_id' => $request->cash_incentive_realize_id,
   'receive_date' => $request->receive_date,
   'commercial_head_id' => $request->commercial_head_id,
   'tax_percent' => $request->tax_percent,
   'amount' => $request->amount,
   'remarks' => $request->remarks,
  ]);

  if ($cashincentiverealizercv) {
   return response()->json(array('success' => true, 'id' =>  $cashincentiverealizercv->id, 'message' => 'Save Successfully'), 200);
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
  $cashincentiverealizercv = $this->cashincentiverealizercv->find($id);
  $row['fromData'] = $cashincentiverealizercv;
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
 public function update(CashIncentiveRealizeRcvRequest $request, $id)
 {
  $cashincentiverealizercv = $this->cashincentiverealizercv->update($id, [
   'cash_incentive_realize_id' => $request->cash_incentive_realize_id,
   'receive_date' => $request->receive_date,
   'commercial_head_id' => $request->commercial_head_id,
   'amount' => $request->amount,
   'remarks' => $request->remarks,
  ]);
  if ($cashincentiverealizercv) {
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
  if ($this->cashincentiverealizercv->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
