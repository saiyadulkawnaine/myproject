<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetServiceRepairRepository;
use App\Repositories\Contracts\FAMS\AssetServiceRepository;
use App\Repositories\Contracts\FAMS\AssetReturnRepository;
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
use App\Http\Requests\FAMS\AssetReturnRequest;

class AssetReturnController extends Controller
{
  private $assetservicerepair;
  private $assetservice;
  private $assetreturn;
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
    AssetServiceRepairRepository $assetservicerepair,
    AssetServiceRepository $assetservice,
    AssetReturnRepository $assetreturn,
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
    $this->assetservicerepair = $assetservicerepair;
    $this->assetservice = $assetservice;
    $this->assetreturn = $assetreturn;
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
    /* $this->middleware('permission:view.assetreturns',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetreturns', ['only' => ['store']]);
        $this->middleware('permission:edit.assetreturns',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetreturns', ['only' => ['destroy']]); */
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $rows = $this->assetreturn
      ->join("suppliers", function ($join) {
        $join->on("suppliers.id", "=", "asset_returns.supplier_id");
      })
      ->orderBy("asset_returns.id", "DESC")
      ->get([
        "asset_returns.*",
        "suppliers.name as supplier_name",
      ])
      ->map(function ($rows) {
        $rows->return_date = date('Y-m-d', strtotime($rows->return_date));
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
    $menu = array_prepend(array_only(config('bprs.menu'), [380, 381]), '-Select', '');
    $itemcategory = array_prepend(array_pluck($this->itemcategory->get(), 'name', 'id'), '-Select-', '');
    $itemclass = array_prepend(array_pluck($this->itemclass->get(), 'name', 'id'), '-Select-', '');
    $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
    return Template::loadView('FAMS.AssetReturn', ['supplier' => $supplier, 'itemcategory' => $itemcategory, 'itemclass' => $itemclass, 'menu' => $menu]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(AssetReturnRequest $request)
  {
    $assetreturn = $this->assetreturn->create($request->except(['id', 'supplier_name']));
    if ($assetreturn) {
      return response()->json(array('success' => true, 'id' => $assetreturn->id, 'message' => 'Save Successfully'), 200);
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
    $assetreturn = $this->assetreturn
      ->join("suppliers", function ($join) {
        $join->on("suppliers.id", "=", "asset_returns.supplier_id");
      })
      ->where("asset_returns.id", "=", $id)
      ->get([
        'asset_returns.*',
        // 'suppliers.id as supplier_id',
        "suppliers.name as supplier_name"
      ])
      ->map(function ($rows) {
        $rows->return_date = date('Y-m-d', strtotime($rows->return_date));
        return $rows;
      })
      ->first();
    $row['fromData'] = $assetreturn;
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
  public function update(AssetReturnRequest $request, $id)
  {
    $assetreturn = $this->assetreturn->update($id, $request->except(['id', 'supplier_name', 'menu_id']));
    if ($assetreturn) {
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
    if ($this->assetreturn->delete($id)) {
      return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
    }
  }

  public function getVendor()
  {
    $menu_id = request('menu_id', 0);
    //repair
    if ($menu_id == 380) {
      $rows = $this->assetservicerepair
        ->selectRaw(
          'suppliers.id,
     suppliers.name as supplier_name,
     suppliers.code as supplier_code,
     suppliers.address as supplier_address
   '
        )
        ->join('suppliers', function ($join) {
          $join->on('suppliers.id', '=', 'asset_service_repairs.supplier_id');
        })
        ->orderBy('suppliers.id', 'DESC')
        ->groupBy(
          [
            'suppliers.id',
            'suppliers.name',
            'suppliers.code',
            'suppliers.address'
          ]
        )
        ->get();
      echo json_encode($rows);
    }
    //service
    if ($menu_id == 381) {
      $rows = $this->assetservice
        ->selectRaw(
          'suppliers.id,
     suppliers.name as supplier_name,
     suppliers.code as supplier_code,
     suppliers.address as supplier_address'
        )
        ->join('suppliers', function ($join) {
          $join->on('asset_services.supplier_id', '=', 'suppliers.id');
        })
        ->orderBy('suppliers.id', 'DESC')
        ->groupBy([
          'suppliers.id',
          'suppliers.name',
          'suppliers.code',
          'suppliers.address'
        ])
        ->get();
      echo json_encode($rows);
    }
  }

  public function getAssetReturnPdf()
  {
    $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '--', '');
    $assetType = config('bprs.assetType');
    $id = request('id', 0);

    $rows = $this->assetreturn
      ->join('suppliers', function ($join) {
        $join->on('suppliers.id', '=', 'asset_returns.supplier_id');
      })
      ->join('asset_return_details', function ($join) {
        $join->on('asset_return_details.asset_return_id', '=', 'asset_returns.id');
      })
      ->join('asset_quantity_costs', function ($join) {
        $join->on('asset_quantity_costs.id', '=', 'asset_return_details.asset_quantity_cost_id');
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
        $join->on('createdby_user.id', '=', 'asset_returns.created_by');
      })
      ->leftJoin('employee_h_rs as createdby_employee', function ($join) {
        $join->on('createdby_user.id', '=', 'createdby_employee.user_id');
      })
      ->where([['asset_returns.id', '=', $id]])
      ->get([
        'asset_returns.*',
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

    $assetreturndetail = $this->assetreturn
      ->selectRaw(
        'asset_acquisitions.name as asset_name,
     asset_acquisitions.asset_group,
     asset_acquisitions.brand,
     sum(asset_quantity_costs.qty) as qty,
     uoms.code as uom_code
    '
      )
      ->join('asset_return_details', function ($join) {
        $join->on('asset_return_details.asset_return_id', '=', 'asset_returns.id');
      })
      ->join('asset_quantity_costs', function ($join) {
        $join->on('asset_quantity_costs.id', '=', 'asset_return_details.asset_quantity_cost_id');
      })
      ->join('asset_acquisitions', function ($join) {
        $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
      })
      ->leftJoin('uoms', function ($join) {
        $join->on('uoms.id', '=', 'asset_acquisitions.uom_id');
      })
      ->where([['asset_returns.id', '=', $id]])
      // ->orderBy('asset_return_details.id', 'DESC')
      ->groupBy([
        'asset_acquisitions.name',
        'asset_acquisitions.asset_group',
        'asset_acquisitions.brand',
        'uoms.code'
      ])
      ->get();

    $data = ['master' => $rows, 'details' => $assetreturndetail];

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
    $view = \View::make('Defult.FAMS.AssetReturnPdf', ['data' => $data]);
    $html_content = $view->render();
    $pdf->SetY(45);
    $pdf->WriteHtml($html_content, true, false, true, false, '');
    $pdf->SetFont('helvetica', 'N', 8);
    $filename = storage_path() . '/AssetReturnPdf.pdf';
    $pdf->output($filename, 'I');
    exit();
  }
}
