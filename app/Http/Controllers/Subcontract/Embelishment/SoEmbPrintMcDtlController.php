<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintMcDtlRequest;

class SoEmbPrintMcDtlController extends Controller
{

 private $soembprintmc;
 private $soembprintmcdtl;
 private $company;
 private $location;
 private $style;
 private $employeehr;

 public function __construct(
  SoEmbPrintMcDtlRepository $soembprintmcdtl,
  SoEmbPrintMcRepository $soembprintmc,
  CompanyRepository $company,
  LocationRepository $location,
  StyleRepository $style,
  SalesOrderRepository $salesorder,
  JobRepository $job,
  EmployeeHRRepository $employeehr
 ) {
  $this->soembprintmcdtl = $soembprintmcdtl;
  $this->soembprintmc = $soembprintmc;
  $this->company = $company;
  $this->location = $location;
  $this->style = $style;
  $this->salesorder = $salesorder;
  $this->job = $job;
  $this->employeehr = $employeehr;

  $this->middleware('auth');
  $this->middleware('permission:view.wstudylinesetupdtls',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.wstudylinesetupdtls', ['only' => ['store']]);
  $this->middleware('permission:edit.wstudylinesetupdtls',   ['only' => ['update']]);
  $this->middleware('permission:delete.wstudylinesetupdtls', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $rows = $this->soembprintmcdtl
   ->join('so_emb_print_mcs', function ($join) {
    $join->on('so_emb_print_mcs.id', '=', 'so_emb_print_mc_dtls.so_emb_print_mc_id');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('employee_h_rs.id', '=', 'so_emb_print_mc_dtls.employee_h_r_id');
   })
   ->where([['so_emb_print_mc_dtls.so_emb_print_mc_id', '=', request('so_emb_print_mc_id', 0)]])
   ->orderBy('so_emb_print_mc_dtls.from_date', 'desc')
   ->get([
    'so_emb_print_mc_dtls.*',
    'employee_h_rs.name as employee_name'
   ])
   ->map(function ($rows) {
    $rows->from_date = date('Y-m-d', strtotime($rows->from_date));
    $rows->to_date = date('Y-m-d', strtotime($rows->to_date));
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
 public function store(SoEmbPrintMcDtlRequest $request)
 {
  $detail = $this->soembprintmcdtl
   ->where([['so_emb_print_mc_id', '=', $request->so_emb_print_mc_id]])
   ->when(request('from_date'), function ($q) {
    return $q->where('so_emb_print_mc_dtls.from_date', '>=', request('from_date', 0));
   })
   ->when(request('from_date'), function ($q) {
    return $q->where('so_emb_print_mc_dtls.to_date', '<=', request('from_date', 0));
   })
   ->get()
   ->first();

  if ($detail) {
   return response()->json(array('success' => false, 'id' => '', 'message' => 'This Date range found for this master id'), 200);
  }

  $soembprintmcdtl = $this->soembprintmcdtl->create([
   'from_date' => $request->from_date,
   'to_date' => $request->from_date,
   'so_emb_print_mc_id' => $request->so_emb_print_mc_id,
   'operator' => $request->operator,
   'helper' => $request->helper,
   'employee_h_r_id' => $request->employee_h_r_id,
   'working_hour' => $request->working_hour,
   'overtime_hour' => $request->overtime_hour,
   'total_mnt' => $request->total_mnt,
   'target_per_hour' => $request->target_per_hour,
   'printing_start_at' => $request->printing_start_at,
   'printing_end_at' => $request->printing_end_at,
   'lunch_start_at' => $request->lunch_start_at,
   'lunch_end_at' => $request->lunch_end_at,
   'tiffin_start_at' => $request->tiffin_start_at,
   'tiffin_end_at' => $request->tiffin_end_at,
   'remarks' => $request->remarks
  ]);
  if ($soembprintmcdtl) {
   return response()->json(array('success' => true, 'id' => $soembprintmcdtl->id, 'message' => 'Save Successfully'), 200);
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
  $soembprintmcdtl = $this->soembprintmcdtl
   ->join('so_emb_print_mcs', function ($join) {
    $join->on('so_emb_print_mcs.id', '=', 'so_emb_print_mc_dtls.so_emb_print_mc_id');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('employee_h_rs.id', '=', 'so_emb_print_mc_dtls.employee_h_r_id');
   })
   ->where([['so_emb_print_mc_dtls.id', '=', $id]])
   ->get([
    'so_emb_print_mc_dtls.*',
    'employee_h_rs.name as employee_name'
   ])
   ->first();
  $row['fromData'] = $soembprintmcdtl;
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
 public function update(SoEmbPrintMcDtlRequest $request, $id)
 {
  $detail = $this->soembprintmcdtl
   ->when(request('from_date'), function ($q) {
    return $q->where('so_emb_print_mc_dtls.from_date', '>=', request('from_date', 0));
   })
   ->when(request('from_date'), function ($q) {
    return $q->where('so_emb_print_mc_dtls.to_date', '<=', request('from_date', 0));
   })
   ->where([['so_emb_print_mc_id', '=', $request->so_emb_print_mc_id]])
   ->where([['id', '!=', $id]])
   ->get()
   ->first();
  if ($detail) {
   return response()->json(array('success' => false, 'id' => '', 'message' => 'This Date range found for this master id'), 200);
  }

  $soembprintmcdtl = $this->soembprintmcdtl->update($id, [
   'from_date' => $request->from_date,
   'to_date' => $request->from_date,
   'operator' => $request->operator,
   'helper' => $request->helper,
   'employee_h_r_id' => $request->employee_h_r_id,
   'working_hour' => $request->working_hour,
   'overtime_hour' => $request->overtime_hour,
   'total_mnt' => $request->total_mnt,
   'target_per_hour' => $request->target_per_hour,
   'printing_start_at' => $request->printing_start_at,
   'printing_end_at' => $request->printing_end_at,
   'lunch_start_at' => $request->lunch_start_at,
   'lunch_end_at' => $request->lunch_end_at,
   'tiffin_start_at' => $request->tiffin_start_at,
   'tiffin_end_at' => $request->tiffin_end_at,
   'remarks' => $request->remarks
  ]);
  if ($soembprintmcdtl) {
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
  if ($this->soembprintmcdtl->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getEmployee()
 {
  $company_id = request('company_id');
  $employeehr = $this->employeehr
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'employee_h_rs.company_id');
   })
   ->join('designations', function ($join) {
    $join->on('designations.id', '=', 'employee_h_rs.designation_id');
   })
   ->join('departments', function ($join) {
    $join->on('departments.id', '=', 'employee_h_rs.department_id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('locations.id', '=', 'employee_h_rs.location_id');
   })
   ->leftJoin('divisions', function ($join) {
    $join->on('divisions.id', '=', 'employee_h_rs.division_id');
   })
   ->leftJoin('sections', function ($join) {
    $join->on('sections.id', '=', 'employee_h_rs.section_id');
   })
   ->leftJoin('subsections', function ($join) {
    $join->on('subsections.id', '=', 'employee_h_rs.subsection_id');
   })
   ->leftJoin('employee_h_rs as reportto', function ($join) {
    $join->on('reportto.id', '=', 'employee_h_rs.report_to_id');
   })
   ->when(request('designation_id'), function ($q) {
    return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
   })
   ->when(request('department_id'), function ($q) {
    return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
   })
   ->where([['employee_h_rs.status_id', '=', 1]])
   ->where([['employee_h_rs.company_id', '=', $company_id]])
   ->get([
    'employee_h_rs.id',
    'employee_h_rs.name as employee_name',
    'employee_h_rs.company_id',
    'companies.name as company_name',
    'employee_h_rs.designation_id',
    'designations.name as designation_name',
    'employee_h_rs.department_id',
    'departments.name as department_name',
    'employee_h_rs.date_of_birth',
    'employee_h_rs.gender_id',
    'employee_h_rs.date_of_join',
    'employee_h_rs.probation_days',
    'employee_h_rs.national_id',
    'employee_h_rs.salary as gross_salary',
    'employee_h_rs.contact as phone_no',
    'employee_h_rs.religion',
    'employee_h_rs.status_id',
    'employee_h_rs.location_id',
    'locations.name as location_name',
    'employee_h_rs.division_id',
    'divisions.name as division_name',
    'employee_h_rs.section_id',
    'sections.name as section_name',
    'employee_h_rs.subsection_id',
    'subsections.name as subsection_name',
    'employee_h_rs.inactive_date',
    'employee_h_rs.contact',
    'employee_h_rs.email',
    'employee_h_rs.address'
   ])
   ->map(function ($employeehr) {
    return $employeehr;
   });

  echo json_encode($employeehr);
 }
}
