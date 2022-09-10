<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetServiceRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\FAMS\AssetTechnicalFeatureRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\FAMS\AssetBreakdownRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\FAMS\AssetServiceRequest;

class AssetServiceController extends Controller
{

 private $assetservice;
 private $assetbreakdown;
 private $employeehr;
 private $assetquantitycost;
 private $company;
 private $location;
 private $uom;
 private $supplier;
 private $assettechfeature;
 private $itemaccount;
 private $itemclass;
 private $itemcategory;
 private $store;
 private $division;
 private $section;
 private $subsection;
 private $buyer;

 public function __construct(
  AssetServiceRepository $assetservice,
  CompanyRepository $company,
  LocationRepository $location,
  UomRepository $uom,
  SupplierRepository $supplier,
  AssetTechnicalFeatureRepository $assettechfeature,
  ItemAccountRepository $itemaccount,
  ItemclassRepository $itemclass,
  ItemcategoryRepository $itemcategory,
  StoreRepository $store,
  AssetQuantityCostRepository $assetquantitycost,
  EmployeeHRRepository $employeehr,
  DesignationRepository $designation,
  DepartmentRepository $department,
  DivisionRepository $division,
  SectionRepository $section,
  SubsectionRepository $subsection,
  BuyerRepository $buyer,
  AssetBreakdownRepository $assetbreakdown
 ) {

  $this->assetquantitycost = $assetquantitycost;
  $this->assetbreakdown = $assetbreakdown;
  $this->employeehr = $employeehr;
  $this->assetservice = $assetservice;
  $this->company = $company;
  $this->location = $location;
  $this->uom = $uom;
  $this->supplier = $supplier;
  $this->assettechfeature = $assettechfeature;
  $this->itemaccount = $itemaccount;
  $this->itemclass = $itemclass;
  $this->itemcategory = $itemcategory;
  $this->store = $store;
  $this->department = $department;
  $this->division = $division;
  $this->section = $section;
  $this->subsection = $subsection;
  $this->designation = $designation;
  $this->buyer = $buyer;


  $this->middleware('auth');
  /* $this->middleware('permission:view.assetservices',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetservices', ['only' => ['store']]);
        $this->middleware('permission:edit.assetservices',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetservices', ['only' => ['destroy']]); */
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $rows = $this->assetservice
   ->join("suppliers", function ($join) {
    $join->on("suppliers.id", "=", "asset_services.supplier_id");
   })
   ->orderBy("asset_services.id", "DESC")
   ->get([
    "asset_services.*",
    "suppliers.name as supplier_name",
   ])
   ->map(function ($rows) {
    $rows->out_date = date('Y-m-d', strtotime($rows->out_date));
    $rows->returnable_date = date('Y-m-d', strtotime($rows->returnable_date));
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
  $itemcategory = array_prepend(array_pluck($this->itemcategory->get(), 'name', 'id'), '-Select-', '');
  $itemclass = array_prepend(array_pluck($this->itemclass->get(), 'name', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  return Template::loadView('FAMS.AssetService', ['supplier' => $supplier, 'itemcategory' => $itemcategory, 'itemclass' => $itemclass]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(AssetServiceRequest $request)
 {
  $assetservice = $this->assetservice->create([
   "out_date" => $request->out_date,
   "returnable_date" => $request->returnable_date,
   "supplier_id" => $request->supplier_id,
   "remarks" => $request->remarks,
  ]);
  if ($assetservice) {
   return response()->json(array('success' => true, 'id' => $assetservice->id, 'message' => 'Save Successfully'), 200);
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
  $productionarea = array_prepend(config('bprs.productionarea'), '-Select-', '');
  $assetType = config('bprs.assetType');
  $reason = array_prepend(config('bprs.reason'), '', '');

  $assetservice = $this->assetservice
   ->join("suppliers", function ($join) {
    $join->on("suppliers.id", "=", "asset_services.supplier_id");
   })
   ->where("asset_services.id", "=", $id)
   ->get([
    'asset_services.*',
    'suppliers.id as supplier_id',
   ])
   ->map(function ($rows) {
    $rows->out_date = date('Y-m-d', strtotime($rows->out_date));
    $rows->returnable_date = date('Y-m-d', strtotime($rows->returnable_date));
    return $rows;
   })
   ->first();
  $row['fromData'] = $assetservice;
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
 public function update(AssetServiceRequest $request, $id)
 {
  $assetservice = $this->assetservice->update($id, [
   // "asset_breakdown_id" => $request->asset_breakdown_id,
   "out_date" => $request->out_date,
   "returnable_date" => $request->returnable_date,
   "supplier_id" => $request->supplier_id,
   "remarks" => $request->remarks,
  ]);
  if ($assetservice) {
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
  if ($this->assetservice->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }


 public function getAssetServicePdf()
 {
  $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '--', '');
  $assetType = config('bprs.assetType');
  $id = request('id', 0);

  $rows = $this->assetservice
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'asset_services.supplier_id');
   })
   ->join('asset_service_details', function ($join) {
    $join->on('asset_service_details.asset_service_id', '=', 'asset_services.id');
   })
   ->join('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'asset_service_details.asset_quantity_cost_id');
   })
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'asset_acquisitions.company_id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'asset_acquisitions.location_id');
   })
   ->leftJoin('users as createdby_user', function ($join) {
    $join->on('createdby_user.id', '=', 'asset_services.created_by');
   })
   ->leftJoin('employee_h_rs as createdby_employee', function ($join) {
    $join->on('createdby_user.id', '=', 'createdby_employee.user_id');
   })
   ->where([['asset_services.id', '=', $id]])
   ->get([
    'asset_services.*',
    'suppliers.name as supplier_name',
    'suppliers.address as supplier_address',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'locations.name as location_name',

    'createdby_employee.name as createdby_user_name',
    'createdby_employee.contact as createdby_contact',
    'createdby_employee.designation_id as   createdby_designation_id',
   ])
   ->map(function ($rows) use ($designation) {
    $rows->createdby_designation = $rows->createdby_designation_id ?
     $designation[$rows->createdby_designation_id] : null;
    $rows->out_date = date('d-M-Y', strtotime($rows->out_date));
    $rows->returnable_date = date('d-M-Y', strtotime($rows->returnable_date));
    return $rows;
   })
   ->first();

  $assetservicedetail = $this->assetservice
   ->selectRaw(
    'asset_acquisitions.name as asset_name,
     asset_acquisitions.asset_group,
     asset_acquisitions.brand,
     sum(asset_quantity_costs.qty) as qty,
     uoms.code as uom_code
    '
   )
   ->join('asset_service_details', function ($join) {
    $join->on('asset_service_details.asset_service_id', '=', 'asset_services.id');
   })
   ->join('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'asset_service_details.asset_quantity_cost_id');
   })
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->leftJoin('uoms', function ($join) {
    $join->on('uoms.id', '=', 'asset_acquisitions.uom_id');
   })
   ->where([['asset_services.id', '=', $id]])
   // ->orderBy('asset_service_details.id', 'DESC')
   ->groupBy([
    'asset_acquisitions.name',
    'asset_acquisitions.asset_group',
    'asset_acquisitions.brand',
    'uoms.code'
   ])
   ->get();

  $data = ['master' => $rows, 'details' => $assetservicedetail];

  $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', '', 10);
  $pdf->AddPage();
  $image_file = 'images/logo/' . $data['master']->logo;
  $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  $pdf->SetY(12);
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->Cell(0, 40, $data['master']->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M');
  $pdf->SetFont('helvetica', 'N', 8);
  $barcodestyle = array(
   'position' => '',
   'align' => 'C',
   'stretch' => false,
   'fitwidth' => true,
   'cellfitalign' => '',
   'border' => false,
   'hpadding' => 'auto',
   'vpadding' => 'auto',
   'fgcolor' => array(0, 0, 0),
   'bgcolor' => false, //array(255,255,255),
   'text' => true,
   'font' => 'helvetica',
   'fontsize' => 8,
   'stretchtext' => 4
  );

  $challan = str_pad($id, 10, 0, STR_PAD_LEFT);
  $pdf->SetY(5);
  $pdf->SetX(150);
  $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
  $pdf->SetY(18);
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->Cell(0, 40, 'Asset Out Challan', 0, false, 'C', 0, '', 0, false, 'T', 'M');
  $pdf->SetY(35);
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetTitle('Asset Out Challan');
  $view = \View::make('Defult.FAMS.AssetServicePdf', ['data' => $data]);
  $html_content = $view->render();
  $pdf->SetY(45);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $pdf->SetFont('helvetica', 'N', 8);
  $filename = storage_path() . '/AssetServicePdf.pdf';
  $pdf->output($filename, 'I');
  exit();
 }
}
