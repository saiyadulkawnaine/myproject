<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;

use App\Library\Template;
use App\Http\Requests\Marketing\BuyerDevelopmentRequest;

class BuyerDevelopmentController extends Controller
{

 private $buyerdevelopment;
 private $company;
 private $buyer;
 private $team;
 private $teammember;
 private $currency;

 public function __construct(
  BuyerDevelopmentRepository $buyerdevelopment,
  CompanyRepository $company,
  BuyerRepository $buyer,
  TeamRepository $team,
  TeammemberRepository $teammember,
  CurrencyRepository $currency
 ) {
  $this->buyerdevelopment = $buyerdevelopment;
  $this->company = $company;
  $this->buyer = $buyer;
  $this->team = $team;
  $this->teammember = $teammember;
  $this->currency = $currency;

  $this->middleware('auth');
  /*$this->middleware('permission:view.targettransfers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.targettransfers', ['only' => ['store']]);
        $this->middleware('permission:edit.targettransfers',   ['only' => ['update']]);
        $this->middleware('permission:delete.targettransfers', ['only' => ['destroy']]);*/
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $meetingtype = array_prepend(config('bprs.meetingtype'), '-Select-', '');
  $credittype = array_prepend(config('bprs.credittype'), '-Select-', '');
  $buyerdlvstatus = array_prepend(config('bprs.buyerdlvstatus'), '-Select-', '');
  $fabricnature = array_prepend(config('bprs.fabricnature'), '-Select-', '');
  $payterm = array_prepend(config('bprs.payterm'), '-Select-', '');
  $rows = $this->buyerdevelopment
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'buyer_developments.buyer_id');
   })
   ->join('teams', function ($join) {
    $join->on('teams.id', '=', 'buyer_developments.team_id');
   })
   ->orderBy('buyer_developments.id', 'desc')
   ->get([
    'buyer_developments.*',
    'buyers.name as buyer_name',
    'teams.name as team_name'
   ])
   ->map(function ($rows) use ($credittype, $buyerdlvstatus, $fabricnature, $payterm) {
    $rows->product_type_id = $fabricnature[$rows->product_type_id];
    $rows->credit_type_id = $credittype[$rows->credit_type_id];
    $rows->pay_term_id = $payterm[$rows->pay_term_id];
    $rows->status_id = $buyerdlvstatus[$rows->status_id];
    return $rows;
   });

  echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create(Request $request)
 {

  $team = array_prepend(array_pluck($this->team->get(), 'name', 'id'), '-Select-', 0);
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
  $currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
  $meetingtype = array_prepend(config('bprs.meetingtype'), '-Select-', '');
  $credittype = array_prepend(config('bprs.credittype'), '-Select-', '');
  $buyerdlvstatus = array_prepend(config('bprs.buyerdlvstatus'), '-Select-', '');
  $fabricnature = array_prepend(config('bprs.fabricnature'), '-Select-', '');
  $payterm = array_prepend(config('bprs.payterm'), '-Select-', '');
  $teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function ($join) use ($request) {
   $join->on('teammembers.user_id', '=', 'users.id');
  })
   ->get([
    'teammembers.id',
    'users.name',
   ]), 'name', 'id'), '-Select-', 0);
  return Template::loadView('Marketing.BuyerDevelopment', [
   'team' => $team,
   'teammember' => $teammember,
   'buyer' => $buyer,
   'currency' => $currency,
   'meetingtype' => $meetingtype,
   'credittype' => $credittype,
   'buyerdlvstatus' => $buyerdlvstatus,
   'fabricnature' => $fabricnature,
   'payterm' => $payterm,
  ]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(BuyerDevelopmentRequest $request)
 {
  $buyerdevelopment = $this->buyerdevelopment->create($request->except(['id']));
  if ($buyerdevelopment) {
   return response()->json(array('success' => true, 'id' =>  $buyerdevelopment->id, 'message' => 'Save Successfully'), 200);
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
  $buyerdevelopment = $this->buyerdevelopment->find($id);
  $row['fromData'] = $buyerdevelopment;
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
 public function update(BuyerDevelopmentRequest $request, $id)
 {
  $buyerdevelopment = $this->buyerdevelopment->update($id, $request->except(['id']));
  if ($buyerdevelopment) {
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
  if ($this->buyerdevelopment->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
