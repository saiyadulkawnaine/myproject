<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeHRJobRepository;
use App\Repositories\Contracts\HRM\EmployeeTransferRepository;
use App\Repositories\Contracts\HRM\EmployeeJobHistoryRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeTransferRequest;
use GuzzleHttp\Client;

class EmployeeTransferController extends Controller
{

 private $employeehr;
 private $employeehrjob;
 private $employeetransfer;
 private $employeejobhistory;
 private $designation;
 private $department;
 private $division;
 private $section;
 private $subsection;
 private $location;
 private $user;

 public function __construct(
  EmployeeHRRepository $employeehr,
  EmployeeHRJobRepository $employeehrjob,
  EmployeeTransferRepository $employeetransfer,
  EmployeeJobHistoryRepository $employeejobhistory,
  DesignationRepository $designation,
  DepartmentRepository $department,
  DivisionRepository $division,
  SectionRepository $section,
  SubsectionRepository $subsection,
  CompanyRepository $company,
  UserRepository $user,
  LocationRepository $location
 ) {
  $this->employeehr = $employeehr;
  $this->employeehrjob = $employeehrjob;
  $this->employeetransfer = $employeetransfer;
  $this->employeejobhistory = $employeejobhistory;
  $this->designation = $designation;
  $this->department = $department;
  $this->division = $division;
  $this->section = $section;
  $this->subsection = $subsection;
  $this->company = $company;
  $this->location = $location;
  $this->user = $user;

  $this->middleware('auth');
  $this->middleware('permission:view.employeetransfers',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.employeetransfers', ['only' => ['store']]);
  $this->middleware('permission:edit.employeetransfers',   ['only' => ['update']]);
  $this->middleware('permission:delete.employeetransfers', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $yesno = config('bprs.yesno');
  $employeetransfer = $this->employeetransfer
   ->join('employee_h_rs', function ($join) {
    $join->on('employee_h_rs.id', '=', 'employee_transfers.employee_h_r_id');
   })
   ->leftJoin('designations', function ($join) {
    $join->on('designations.id', '=', 'employee_h_rs.designation_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'employee_transfers.company_id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('locations.id', '=', 'employee_transfers.location_id');
   })
   ->leftJoin('divisions', function ($join) {
    $join->on('divisions.id', '=', 'employee_transfers.division_id');
   })
   ->leftJoin('departments', function ($join) {
    $join->on('departments.id', '=', 'employee_transfers.department_id');
   })
   ->leftJoin('sections', function ($join) {
    $join->on('sections.id', '=', 'employee_transfers.section_id');
   })
   ->leftJoin('subsections', function ($join) {
    $join->on('subsections.id', '=', 'employee_transfers.subsection_id');
   })
   ->leftJoin('employee_h_rs as reportto', function ($join) {
    $join->on('reportto.id', '=', 'employee_transfers.report_to_id');
   })

   ->leftJoin('companies as oldcompanies', function ($join) {
    $join->on('oldcompanies.id', '=', 'employee_transfers.old_company_id');
   })
   ->leftJoin('locations as oldlocations', function ($join) {
    $join->on('oldlocations.id', '=', 'employee_transfers.old_location_id');
   })
   ->leftJoin('divisions as olddivisions', function ($join) {
    $join->on('olddivisions.id', '=', 'employee_transfers.old_division_id');
   })
   ->leftJoin('departments as olddepartments', function ($join) {
    $join->on('olddepartments.id', '=', 'employee_transfers.old_department_id');
   })
   ->leftJoin('sections as oldsections', function ($join) {
    $join->on('oldsections.id', '=', 'employee_transfers.old_section_id');
   })
   ->leftJoin('subsections as oldsubsections', function ($join) {
    $join->on('oldsubsections.id', '=', 'employee_transfers.old_subsection_id');
   })
   ->leftJoin('employee_h_rs as oldreportto', function ($join) {
    $join->on('oldreportto.id', '=', 'employee_transfers.old_report_to_id');
   })
   ->orderBy('employee_transfers.id', 'desc')
   ->get([
    'employee_transfers.*',
    'employee_h_rs.name as employee_name',
    'designations.name as designation_name',
    'reportto.name as report_to_name',
    'companies.name as company_name',
    'departments.name as department_name',
    'divisions.name as division_name',
    'sections.name as section_name',
    'subsections.name as subsection_name',
    'locations.name as location_name',
    'reportto.name as report_to_name',

    'oldcompanies.name as old_company_name',
    'olddepartments.name as old_department_name',
    'olddivisions.name as old_division_name',
    'oldsections.name as old_section_name',
    'oldsubsections.name as old_subsection_name',
    'oldlocations.name as old_location_name',
    'oldreportto.name as old_report_to_name',
   ])
   ->map(function ($employeetransfer) use ($yesno) {
    $employeetransfer->api_status = $employeetransfer->api_status ? $yesno[$employeetransfer->api_status] : 'No';
    return $employeetransfer;
   });

  echo json_encode($employeetransfer);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '-Select-', '');
  $department = array_prepend(array_pluck($this->department->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $division = array_prepend(array_pluck($this->division->get(), 'name', 'id'), '-Select-', '');
  $section = array_prepend(array_pluck($this->section->get(), 'name', 'id'), '-Select-', '');
  $subsection = array_prepend(array_pluck($this->subsection->get(), 'code', 'id'), '-Select-', '');
  return Template::loadView('HRM.EmployeeTransfer', ['designation' => $designation, 'department' => $department, 'company' => $company, 'location' => $location, 'division' => $division, 'section' => $section, 'subsection' => $subsection]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(EmployeeTransferRequest $request)
 {
  $employeehr = $this->employeehr->find($request->employee_h_r_id);
  $request->request->add(['old_code' => $employeehr->code]);
  $request->request->add(['old_company_id' => $employeehr->company_id]);
  $request->request->add(['old_location_id' => $employeehr->location_id]);
  $request->request->add(['old_division_id' => $employeehr->division_id]);
  $request->request->add(['old_department_id' => $employeehr->department_id]);
  $request->request->add(['old_section_id' => $employeehr->section_id]);
  $request->request->add(['old_subsection_id' => $employeehr->subsection_id]);
  $request->request->add(['old_report_to_id' => $employeehr->report_to_id]);
  $request->request->add(['api_status' => 0]);

  \DB::beginTransaction();
  try {
   $employeetransfer = $this->employeetransfer->create([
    'employee_h_r_id' => $request->employee_h_r_id,
    'code' => $request->code,
    'transfer_date' => $request->transfer_date,
    'company_id' => $request->company_id,
    'location_id' => $request->location_id,
    'division_id' => $request->division_id,
    'department_id' => $request->department_id,
    'section_id' => $request->section_id,
    'subsection_id' => $request->subsection_id,
    'report_to_id' => $request->report_to_id,
    'old_code' => $request->old_code,
    'old_company_id' => $request->old_company_id,
    'old_location_id' => $request->old_location_id,
    'old_division_id' => $request->old_division_id,
    'old_department_id' => $request->old_department_id,
    'old_section_id' => $request->old_section_id,
    'old_subsection_id' => $request->old_subsection_id,
    'old_report_to_id' => $request->old_report_to_id,
    'remarks' => $request->remarks,
    'api_status' => $request->api_status,
   ]);

   $this->employeehr->update($employeehr->id, [
    'code' => $request->code,
    'company_id' => $request->company_id,
    'location_id' => $request->location_id,
    'division_id' => $request->division_id,
    'department_id' => $request->department_id,
    'section_id' => $request->section_id,
    'subsection_id' => $request->subsection_id,
    'report_to_id' => $request->report_to_id,
   ]);

   $employeehrjob = $this->employeehrjob->where([['employee_h_r_id', '=', $request->employee_h_r_id]])->orderBy('sort_id')->get();
   foreach ($employeehrjob as $row) {
    $this->employeejobhistory->create([
     'employee_h_r_job_id' => $row->id,
     'employee_transfer_id' => $employeetransfer->id,
     'job_description' => $row->job_description,
     'sort_id' => $row->sort_id
    ]);
   }
  } catch (EXCEPTION $e) {
   \DB::rollback();
   throw $e;
  }
  \DB::commit();


  $emp = $this->employeetransfer
   ->join('employee_h_rs', function ($join) {
    $join->on('employee_h_rs.id', '=', 'employee_transfers.employee_h_r_id');
   })
   ->leftJoin('designations', function ($join) {
    $join->on('designations.id', '=', 'employee_h_rs.designation_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'employee_transfers.company_id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('locations.id', '=', 'employee_transfers.location_id');
   })
   ->leftJoin('divisions', function ($join) {
    $join->on('divisions.id', '=', 'employee_transfers.division_id');
   })
   ->leftJoin('departments', function ($join) {
    $join->on('departments.id', '=', 'employee_transfers.department_id');
   })
   ->leftJoin('sections', function ($join) {
    $join->on('sections.id', '=', 'employee_transfers.section_id');
   })
   ->leftJoin('subsections', function ($join) {
    $join->on('subsections.id', '=', 'employee_transfers.subsection_id');
   })
   ->leftJoin('employee_h_rs as reportto', function ($join) {
    $join->on('reportto.id', '=', 'employee_transfers.report_to_id');
   })

   ->leftJoin('companies as oldcompanies', function ($join) {
    $join->on('oldcompanies.id', '=', 'employee_transfers.old_company_id');
   })
   ->leftJoin('locations as oldlocations', function ($join) {
    $join->on('oldlocations.id', '=', 'employee_transfers.old_location_id');
   })
   ->leftJoin('divisions as olddivisions', function ($join) {
    $join->on('olddivisions.id', '=', 'employee_transfers.old_division_id');
   })
   ->leftJoin('departments as olddepartments', function ($join) {
    $join->on('olddepartments.id', '=', 'employee_transfers.old_department_id');
   })
   ->leftJoin('sections as oldsections', function ($join) {
    $join->on('oldsections.id', '=', 'employee_transfers.old_section_id');
   })
   ->leftJoin('subsections as oldsubsections', function ($join) {
    $join->on('oldsubsections.id', '=', 'employee_transfers.old_subsection_id');
   })
   ->leftJoin('employee_h_rs as oldreportto', function ($join) {
    $join->on('oldreportto.id', '=', 'employee_transfers.old_report_to_id');
   })
   ->where([['employee_transfers.id', '=', $employeetransfer->id]])
   ->get([
    'employee_transfers.employee_h_r_id as emp_id',
    'employee_transfers.transfer_date',

    'employee_transfers.company_id as new_company_id',
    'employee_transfers.location_id as new_location_id',
    'employee_transfers.division_id as new_division_id',
    'employee_transfers.department_id as new_department_id',
    'employee_transfers.section_id as new_section_id',
    'employee_transfers.subsection_id as new_sub_section_id',

    'companies.name as new_company_name',
    'locations.name as new_location_name',
    'divisions.name as new_division_name',
    'departments.name as new_department_name',
    'sections.name as new_section_name',
    'subsections.name as new_sub_section_name',

    'employee_transfers.old_company_id',
    'employee_transfers.old_location_id',
    'employee_transfers.old_division_id',
    'employee_transfers.old_department_id',
    'employee_transfers.old_section_id',
    'employee_transfers.old_subsection_id as old_sub_section_id',

    'oldcompanies.name as old_company_name',
    'oldlocations.name as old_location_name',
    'olddivisions.name as old_division_name',
    'olddepartments.name as old_department_name',
    'oldsections.name as old_section_name',
    'oldsubsections.name as old_sub_section_name',
   ])
   ->map(function ($emp) {
    $emp->effect_date = date('Y-m-d', strtotime($emp->transfer_date));
    return $emp;
   })
   ->first();
  $data = json_encode($emp);

  try {
   $client = new Client();
   $response = $client->request(
    'POST',
    'http://192.168.32.10:8082/Token',
    [
     'form_params' => [
      'grant_type' => 'password',
      'username' => 'erpadmin',
      'password' => 'admin@erp',
     ]
    ]
   );
   //$code = $response->getStatusCode();
   $body = json_decode($response->getBody());
   $token = $body->access_token;
   $headers = [
    'Authorization' => 'Bearer ' . $token,
    'Accept'        => 'application/json',
    "Content-Type"  => "application/json"
   ];
   //echo $token; die;
   $res = $client->post('http://192.168.32.10:8082/Api/Erp/Transfer', ['body' => $data, 'headers' => $headers]);
   //echo $res->getBody();
   $ApiStatus = json_decode($res->getBody());
   $this->employeetransfer->update($employeetransfer->id, [
    'api_status' => $ApiStatus->Status,
   ]);
  } catch (\GuzzleHttp\Exception\RequestException $e) {
   if ($employeehr) {
    return response()->json(array('success' => true, 'id' =>  $employeetransfer->id, 'message' => 'Save Successfully'), 200);
   }
   throw $e;
  }


  return response()->json(array('success' => true, 'id' =>  $employeetransfer->id, 'message' => 'Save Successfully'), 200);
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
  $employeetransfer = $this->employeetransfer
   ->join('employee_h_rs', function ($join) {
    $join->on('employee_h_rs.id', '=', 'employee_transfers.employee_h_r_id');
   })
   ->leftJoin('designations', function ($join) {
    $join->on('designations.id', '=', 'employee_h_rs.designation_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'employee_transfers.company_id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('locations.id', '=', 'employee_transfers.location_id');
   })
   ->leftJoin('divisions', function ($join) {
    $join->on('divisions.id', '=', 'employee_transfers.division_id');
   })
   ->leftJoin('departments', function ($join) {
    $join->on('departments.id', '=', 'employee_transfers.department_id');
   })
   ->leftJoin('sections', function ($join) {
    $join->on('sections.id', '=', 'employee_transfers.section_id');
   })
   ->leftJoin('subsections', function ($join) {
    $join->on('subsections.id', '=', 'employee_transfers.subsection_id');
   })
   ->leftJoin('employee_h_rs as reportto', function ($join) {
    $join->on('reportto.id', '=', 'employee_transfers.report_to_id');
   })

   ->leftJoin('companies as oldcompanies', function ($join) {
    $join->on('oldcompanies.id', '=', 'employee_transfers.old_company_id');
   })
   ->leftJoin('locations as oldlocations', function ($join) {
    $join->on('oldlocations.id', '=', 'employee_transfers.old_location_id');
   })
   ->leftJoin('divisions as olddivisions', function ($join) {
    $join->on('olddivisions.id', '=', 'employee_transfers.old_division_id');
   })
   ->leftJoin('departments as olddepartments', function ($join) {
    $join->on('olddepartments.id', '=', 'employee_transfers.old_department_id');
   })
   ->leftJoin('sections as oldsections', function ($join) {
    $join->on('oldsections.id', '=', 'employee_transfers.old_section_id');
   })
   ->leftJoin('subsections as oldsubsections', function ($join) {
    $join->on('oldsubsections.id', '=', 'employee_transfers.old_subsection_id');
   })
   ->leftJoin('employee_h_rs as oldreportto', function ($join) {
    $join->on('oldreportto.id', '=', 'employee_transfers.old_report_to_id');
   })

   ->where([['employee_transfers.id', '=', $id]])
   ->get([
    'employee_transfers.*',
    'employee_h_rs.name as employee_name',
    'designations.name as designation_name',
    'reportto.name as new_report_to_name',
    'oldcompanies.name as company_name',
    'olddepartments.name as department_name',
    'olddivisions.name as division_name',
    'oldsections.name as section_name',
    'oldsubsections.name as subsection_name',
    'oldlocations.name as location_name',
    'oldreportto.name as old_report_to_name',
   ])
   ->first();

  $row['fromData'] = $employeetransfer;
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
 public function update(EmployeeTransferRequest $request, $id)
 {
  $request->request->add(['api_status' => 0]);
  \DB::beginTransaction();
  try {
   $employeetransfer = $this->employeetransfer->update($id, [
    'code' => $request->code,
    'transfer_date' => $request->transfer_date,
    'company_id' => $request->company_id,
    'location_id' => $request->location_id,
    'division_id' => $request->division_id,
    'department_id' => $request->department_id,
    'section_id' => $request->section_id,
    'subsection_id' => $request->subsection_id,
    'report_to_id' => $request->report_to_id,
    'remarks' => $request->remarks,
    'api_status' => $request->api_status,
   ]);
   $this->employeehr->update($request->employee_h_r_id, [
    'code' => $request->code,
    'company_id' => $request->company_id,
    'location_id' => $request->location_id,
    'division_id' => $request->division_id,
    'department_id' => $request->department_id,
    'section_id' => $request->section_id,
    'subsection_id' => $request->subsection_id,
    'report_to_id' => $request->report_to_id,
   ]);
  } catch (EXCEPTION $e) {
   \DB::rollback();
   throw $e;
  }
  \DB::commit();

  $emp = $this->employeetransfer
   ->join('employee_h_rs', function ($join) {
    $join->on('employee_h_rs.id', '=', 'employee_transfers.employee_h_r_id');
   })
   ->leftJoin('designations', function ($join) {
    $join->on('designations.id', '=', 'employee_h_rs.designation_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'employee_transfers.company_id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('locations.id', '=', 'employee_transfers.location_id');
   })
   ->leftJoin('divisions', function ($join) {
    $join->on('divisions.id', '=', 'employee_transfers.division_id');
   })
   ->leftJoin('departments', function ($join) {
    $join->on('departments.id', '=', 'employee_transfers.department_id');
   })
   ->leftJoin('sections', function ($join) {
    $join->on('sections.id', '=', 'employee_transfers.section_id');
   })
   ->leftJoin('subsections', function ($join) {
    $join->on('subsections.id', '=', 'employee_transfers.subsection_id');
   })
   ->leftJoin('employee_h_rs as reportto', function ($join) {
    $join->on('reportto.id', '=', 'employee_transfers.report_to_id');
   })

   ->leftJoin('companies as oldcompanies', function ($join) {
    $join->on('oldcompanies.id', '=', 'employee_transfers.old_company_id');
   })
   ->leftJoin('locations as oldlocations', function ($join) {
    $join->on('oldlocations.id', '=', 'employee_transfers.old_location_id');
   })
   ->leftJoin('divisions as olddivisions', function ($join) {
    $join->on('olddivisions.id', '=', 'employee_transfers.old_division_id');
   })
   ->leftJoin('departments as olddepartments', function ($join) {
    $join->on('olddepartments.id', '=', 'employee_transfers.old_department_id');
   })
   ->leftJoin('sections as oldsections', function ($join) {
    $join->on('oldsections.id', '=', 'employee_transfers.old_section_id');
   })
   ->leftJoin('subsections as oldsubsections', function ($join) {
    $join->on('oldsubsections.id', '=', 'employee_transfers.old_subsection_id');
   })
   ->leftJoin('employee_h_rs as oldreportto', function ($join) {
    $join->on('oldreportto.id', '=', 'employee_transfers.old_report_to_id');
   })
   ->where([['employee_transfers.id', '=', $id]])
   ->get([
    'employee_transfers.employee_h_r_id as emp_id',
    'employee_transfers.transfer_date',
    'employee_transfers.company_id as new_company_id',
    'employee_transfers.location_id as new_location_id',
    'employee_transfers.division_id as new_division_id',
    'employee_transfers.department_id as new_department_id',
    'employee_transfers.section_id as new_section_id',
    'employee_transfers.subsection_id as new_sub_section_id',

    'companies.name as new_company_name',
    'locations.name as new_location_name',
    'divisions.name as new_division_name',
    'departments.name as new_department_name',
    'sections.name as new_section_name',
    'subsections.name as new_sub_section_name',

    'employee_transfers.old_company_id',
    'employee_transfers.old_location_id',
    'employee_transfers.old_division_id',
    'employee_transfers.old_department_id',
    'employee_transfers.old_section_id',
    'employee_transfers.old_subsection_id as old_sub_section_id',
    'oldcompanies.name as old_company_name',
    'oldlocations.name as old_location_name',
    'olddivisions.name as old_division_name',
    'olddepartments.name as old_department_name',
    'oldsections.name as old_section_name',
    'oldsubsections.name as old_sub_section_name',
   ])
   ->map(function ($emp) {
    $emp->effect_date = date('Y-m-d', strtotime($emp->transfer_date));
    return $emp;
   })
   ->first();
  $data = json_encode($emp);

  try {
   $client = new Client();
   $response = $client->request(
    'POST',
    'http://192.168.32.10:8082/Token',
    [
     'form_params' => [
      'grant_type' => 'password',
      'username' => 'erpadmin',
      'password' => 'admin@erp',
     ]
    ]
   );
   //$code = $response->getStatusCode();
   $body = json_decode($response->getBody());
   $token = $body->access_token;
   $headers = [
    'Authorization' => 'Bearer ' . $token,
    'Accept'        => 'application/json',
    "Content-Type"  => "application/json"
   ];
   //echo $token; die;
   $res = $client->post('http://192.168.32.10:8082/Api/Erp/Transfer', ['body' => $data, 'headers' => $headers]);
   //echo $res->getBody();
   $ApiStatus = json_decode($res->getBody());
   $this->employeetransfer->update($id, [
    'api_status' => $ApiStatus->Status,
   ]);
  } catch (\GuzzleHttp\Exception\RequestException $e) {
   if ($employeehr) {
    return response()->json(array('success' => true, 'id' =>  $id, 'message' => 'Save Successfully'), 200);
   }
   throw $e;
  }

  return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
 }

 /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function destroy($id)
 {
  return response()->json(array('success' => false, 'message' => 'Delete Not Successfully'), 200);
  if ($this->employeetransfer->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }


 public function getEmployeeHr()
 {

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
   ->when(request('company_id'), function ($q) {
    return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
   })
   ->when(request('designation_id'), function ($q) {
    return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
   })
   ->when(request('department_id'), function ($q) {
    return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
   })
   ->where([['employee_h_rs.status_id', '=', 1]])
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
    'employee_h_rs.report_to_id',
    'reportto.name as report_to_name',
   ])
   ->map(function ($employeehr) {
    return $employeehr;
   });

  echo json_encode($employeehr);
 }

 public function getReportEmployee()
 {
  $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '-Select-', '');
  $department = array_prepend(array_pluck($this->department->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $section = array_prepend(array_pluck($this->section->get(), 'name', 'id'), '-Select-', '');

  $rows = $this->employeehr
   ->when(request('company_id'), function ($q) {
    return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
   })
   ->when(request('designation_id'), function ($q) {
    return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
   })
   ->when(request('department_id'), function ($q) {
    return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
   })
   ->get([
    'employee_h_rs.id',
    'employee_h_rs.name',
    'employee_h_rs.code',
    'employee_h_rs.designation_id',
    'employee_h_rs.department_id',
    'employee_h_rs.section_id',
    'employee_h_rs.company_id',
    'employee_h_rs.contact',
    'employee_h_rs.email',
   ])
   ->map(function ($rows) use ($company, $designation, $department, $section) {
    $rows->employee_name = $rows->name;
    $rows->company_id = $company[$rows->company_id];
    $rows->designation_id = isset($designation[$rows->designation_id]) ? $designation[$rows->designation_id] : '';
    $rows->department_id = isset($department[$rows->department_id]) ? $department[$rows->department_id] : '';
    $rows->section_id = isset($section[$rows->section_id]) ? $section[$rows->section_id] : '';
    return $rows;
   });

  echo json_encode($rows);
 }
}
