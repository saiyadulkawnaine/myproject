<?php

namespace App\Http\Controllers\Workstudy;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Library\Template;
use App\Http\Requests\Workstudy\WstudyLineSetupRequest;

class WstudyLineSetupController extends Controller
{

 private $wstudylinesetup;
 private $company;
 private $location;
 private $buyer;

 public function __construct(WstudyLineSetupRepository $wstudylinesetup, CompanyRepository $company, LocationRepository $location, BuyerRepository $buyer)
 {
  $this->wstudylinesetup = $wstudylinesetup;
  $this->company = $company;
  $this->location = $location;
  $this->buyer = $buyer;

  $this->middleware('auth');
  $this->middleware('permission:view.wstudylinesetups',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.wstudylinesetups', ['only' => ['store']]);
  $this->middleware('permission:edit.wstudylinesetups',   ['only' => ['update']]);
  $this->middleware('permission:delete.wstudylinesetups', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  //$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
  //$location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
  $yesno = array_prepend(config('bprs.yesno'), '', '');

  $subsections = $this->wstudylinesetup
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
   })
   ->join('wstudy_line_setup_lines', function ($join) {
    $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   })
   ->join('subsections', function ($join) {
    $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
   })
   ->join('floors', function ($join) {
    $join->on('floors.id', '=', 'subsections.floor_id');
   })
   ->when(request('location_id'), function ($q) {
    return $q->where('wstudy_line_setups.location_id', '=', request('location_id', 0));
   })
   ->get([
    'wstudy_line_setups.id',
    'subsections.name',
    'subsections.code',
    'floors.name as floor_name'
   ]);
  $lineNames = array();
  $lineCode = array();
  $lineFloor = array();
  foreach ($subsections as $subsection) {
   $lineNames[$subsection->id][] = $subsection->name;
   $lineCode[$subsection->id][] = $subsection->code;
   $lineFloor[$subsection->id][] = $subsection->floor_name;
  }


  $wstudylinesetups = array();
  $rows = $this->wstudylinesetup
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
   })
   ->orderBy('wstudy_line_setups.id', 'desc')
   ->get([
    'wstudy_line_setups.*',
    'companies.name as company_id',
    'locations.name as location_id'
   ]);
  foreach ($rows as $row) {
   $wstudylinesetup['id'] = $row->id;
   $wstudylinesetup['company_id'] = $row->company_id;
   $wstudylinesetup['location_id'] = $row->location_id;
   $wstudylinesetup['line_merged_id'] = $yesno[$row->line_merged_id];
   $wstudylinesetup['line_code'] = isset($lineCode[$row->id]) ? implode(',', $lineCode[$row->id]) : '';
   $wstudylinesetup['line_floor'] = isset($lineFloor[$row->id]) ? implode(',', $lineFloor[$row->id]) : '';
   array_push($wstudylinesetups, $wstudylinesetup);
  }
  echo json_encode($wstudylinesetups);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->buyers(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $minuteadjustmentreasons = array_prepend(config('bprs.minuteadjustmentreasons'), '-Select-', '');
  return Template::loadView("Workstudy.WstudyLineSetup", ['company' => $company, 'location' => $location, 'yesno' => $yesno, 'buyer' => $buyer, 'minuteadjustmentreasons' => $minuteadjustmentreasons]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(WstudyLineSetupRequest $request)
 {
  $wstudylinesetup = $this->wstudylinesetup->create($request->except(['id']));
  if ($wstudylinesetup) {
   return response()->json(array('success' => true, 'id' => $wstudylinesetup->id, 'message' => 'Save Successfully'), 200);
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
  $wstudylinesetup = $this->wstudylinesetup->find($id);
  $row['fromData'] = $wstudylinesetup;
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
 public function update(WstudyLineSetupRequest $request, $id)
 {
  $wstudylinesetup = $this->wstudylinesetup->update($id, $request->except(['id']));
  if ($wstudylinesetup) {
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
  if ($this->wstudylinesetup->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }
}
