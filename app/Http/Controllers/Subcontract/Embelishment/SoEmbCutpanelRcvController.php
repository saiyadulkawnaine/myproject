<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
// use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbItemRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
// use App\Repositories\Contracts\Util\ItemAccountRepository;
// use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbCutpanelRcvRequest;

class SoEmbCutpanelRcvController extends Controller
{

 private $soemb;
 private $soembitem;
 private $soembcutpanelrcv;
 private $company;
 private $buyer;
 private $itemaccount;
 private $gmtspart;

 public function __construct(
  SoEmbRepository $soemb,
  // SoEmbItemRepository $soembitem,
  SoEmbCutpanelRcvRepository $soembcutpanelrcv,
  CompanyRepository $company,
  BuyerRepository $buyer
  // ItemAccountRepository $itemaccount,
  // GmtspartRepository $gmtspart
 ) {
  $this->soemb = $soemb;
  // $this->soembitem = $soembitem;
  $this->soembcutpanelrcv = $soembcutpanelrcv;
  $this->company = $company;
  $this->buyer = $buyer;
  // $this->itemaccount = $itemaccount;
  // $this->gmtspart = $gmtspart;
  $this->middleware('auth');
  // $this->middleware('permission:view.soembcutpanelrcvs',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.soembcutpanelrcvs', ['only' => ['store']]);
  // $this->middleware('permission:edit.soembcutpanelrcvs',   ['only' => ['update']]);
  // $this->middleware('permission:delete.soembcutpanelrcvs', ['only' => ['destroy']]);

 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');
  $productionarea = array_prepend(array_only(config('bprs.productionarea'), [45, 50]), '-Select-', '');
  $rows = $this->soembcutpanelrcv
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'so_emb_cutpanel_rcvs.company_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'so_emb_cutpanel_rcvs.buyer_id');
   })
   ->where([['so_emb_cutpanel_rcvs.is_self', '=', 1]])
   ->orderBy('so_emb_cutpanel_rcvs.id', 'DESC')
   ->get([
    'so_emb_cutpanel_rcvs.*',
    'companies.name as company_name',
    'buyers.name as buyer_name'
   ])
   ->map(function ($rows) use ($productionarea, $shiftname) {
    $rows->receive_date = date('d-M-Y', strtotime($rows->receive_date));
    $rows->shift_name = $shiftname[$rows->shift_id];
    $rows->production_area = $productionarea[$rows->production_area_id];
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
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  // $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->soEmbCutpanel(), 'name', 'id'), '-Select-', '');
  $productionarea = array_prepend(array_only(config('bprs.productionarea'), [45, 50]), '-Select-', '');
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');
  // $itemaccount = array_prepend(array_pluck($this->itemaccount->where([['item_accounts.itemcategory_id', '=', 21]])->get(), 'item_description', 'id'), '-Select-', '');
  // $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
  // $soembitem = array_prepend(array_pluck(
  //  $this->soembitem
  //  -join('so_emb_refs')
  // ));
  return Template::LoadView('Subcontract.Embelishment.SoEmbCutpanelRcv', ['company' => $company, 'buyer' => $buyer, 'shiftname' => $shiftname, 'productionarea' => $productionarea]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SoEmbCutpanelRcvRequest $request)
 {
  $soembcutpanelrcv = $this->soembcutpanelrcv->create([
   'challan_no' => $request->challan_no,
   'company_id' => $request->company_id,
   'buyer_id' => $request->buyer_id,
   'production_area_id' => $request->production_area_id,
   'receive_date' => $request->receive_date,
   'shift_id' => $request->shift_id,
   'remarks' => $request->remarks,
   'is_self' => 1
  ]);
  if ($soembcutpanelrcv) {
   return response()->json(array('success' => true, 'id' =>  $soembcutpanelrcv->id, 'message' => 'Save Successfully'), 200);
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
  $soembcutpanelrcv = $this->soembcutpanelrcv->find($id);
  $row['fromData'] = $soembcutpanelrcv;
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
 public function update(SoEmbCutpanelRcvRequest $request, $id)
 {
  $soembcutpanelrcv = $this->soembcutpanelrcv->update($id, [
   'challan_no' => $request->challan_no,
   'company_id' => $request->company_id,
   'buyer_id' => $request->buyer_id,
   'production_area_id' => $request->production_area_id,
   'receive_date' => $request->receive_date,
   'shift_id' => $request->shift_id,
   'remarks' => $request->remarks
  ]);
  if ($soembcutpanelrcv) {
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
  if ($this->soembcutpanelrcv->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
