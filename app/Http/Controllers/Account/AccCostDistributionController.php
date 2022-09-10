<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccCostDistributionRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccCostDistributionRequest;

class AccCostDistributionController extends Controller
{

 private $acccostdistribution;
 private $accchartctrlhead;
 private $profitcenter;

 public function __construct(AccCostDistributionRepository $acccostdistribution)
 {
  $this->acccostdistribution = $acccostdistribution;

  $this->middleware('auth');
  // $this->middleware('permission:view.acccostdistributions',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.acccostdistributions', ['only' => ['store']]);
  // $this->middleware('permission:edit.acccostdistributions',   ['only' => ['update']]);
  // $this->middleware('permission:delete.acccostdistributions', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $costTypeId = [1 => 'Commercial Exp', 2 => 'Couries'];
  $acccostdistributions = array();
  $rows = $this->acccostdistribution
   ->orderBy('acc_cost_distributions.id', 'desc')
   ->get();
  foreach ($rows as $row) {
   $acccostdistribution['id'] = $row->id;
   $acccostdistribution['form_date'] = date('Y-m-d', strtotime($row->form_date));
   $acccostdistribution['to_date'] = date('Y-m-d', strtotime($row->to_date));
   $acccostdistribution['to_date'] = date('Y-m-d', strtotime($row->to_date));
   $acccostdistribution['cost_type_id'] = $costTypeId[$row->cost_type_id];
   $acccostdistribution['amount'] = $row->amount;
   $acccostdistribution['remarks'] = $row->remarks;

   array_push($acccostdistributions, $acccostdistribution);
  }
  echo json_encode($acccostdistributions);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  return Template::loadView('Account.AccCostDistribution');
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(AccCostDistributionRequest $request)
 {

  $year = date('Y', strtotime($request->form_date));
  $month = date('F', strtotime($request->form_date));
  $acccostdistribution = $this->acccostdistribution->create([
   'form_date' => $request->form_date,
   'to_date' => $request->to_date,
   'cost_type_id' => $request->cost_type_id,
   'year' => $year,
   'month' => $month,
   'amount' => $request->amount,
   'remarks' => $request->remarks
  ]);
  if ($acccostdistribution) {
   return response()->json(array('success' => true, 'id' =>  $acccostdistribution->id, 'message' => 'Save Successfully'), 200);
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
  $acccostdistribution = $this->acccostdistribution->find($id);
  $acccostdistribution['form_date'] = date('Y-m-d', strtotime($acccostdistribution->form_date));
  $acccostdistribution['to_date'] = date('Y-m-d', strtotime($acccostdistribution->to_date));
  $row['fromData'] = $acccostdistribution;
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
 public function update(AccCostDistributionRequest $request, $id)
 {
  $year = date('Y', strtotime($request->form_date));
  $month = date('F', strtotime($request->form_date));
  $acccostdistribution = $this->acccostdistribution->update($id, [
   'form_date' => $request->form_date,
   'to_date' => $request->to_date,
   'cost_type_id' => $request->cost_type_id,
   'year' => $year,
   'month' => $month,
   'amount' => $request->amount,
   'remarks' => $request->remarks
  ]);
  if ($acccostdistribution) {
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
  if ($this->acccostdistribution->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
