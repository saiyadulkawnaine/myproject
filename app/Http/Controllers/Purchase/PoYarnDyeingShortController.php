<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;

use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Purchase\PoYarnDyeingRequest;



class PoYarnDyeingShortController extends Controller
{
  private $poyarndyeing;
  private $company;
  private $supplier;
  private $currency;
  private $uom;
  private $termscondition;
  private $purchasetermscondition;
  private $colorrange;
  private $color;
  private $itemclass;
  private $itemcategory;
  private $invyarnitem;
  private $section;
  private $designation;
  private $department;


  public function __construct(
    PoYarnDyeingRepository $poyarndyeing,
    CompanyRepository $company,
    SupplierRepository $supplier,
    CurrencyRepository $currency,
    UomRepository $uom,
    TermsConditionRepository $termscondition,
    PurchaseTermsConditionRepository $purchasetermscondition,
    ItemAccountRepository $itemaccount,
    ColorrangeRepository $colorrange,
    ColorRepository $color,
    BuyerRepository $buyer,
    ItemclassRepository $itemclass,
    ItemcategoryRepository $itemcategory,
    InvYarnItemRepository $invyarnitem,
    SectionRepository $section,
    DesignationRepository $designation,
    DepartmentRepository $department
  ) {
    $this->poyarndyeing = $poyarndyeing;
    $this->company = $company;
    $this->supplier = $supplier;
    $this->currency = $currency;
    $this->uom = $uom;
    $this->itemcategory = $itemcategory;
    $this->termscondition = $termscondition;
    $this->purchasetermscondition = $purchasetermscondition;
    $this->itemaccount = $itemaccount;
    $this->colorrange = $colorrange;
    $this->color = $color;
    $this->buyer = $buyer;
    $this->itemclass = $itemclass;
    $this->itemcategory = $itemcategory;
    $this->invyarnitem = $invyarnitem;
    $this->section = $section;
    $this->designation = $designation;
    $this->department = $department;

    $this->middleware('auth');
    // $this->middleware('permission:view.poyarndyeingshorts',   ['only' => ['create', 'index', 'show']]);
    // $this->middleware('permission:create.poyarndyeingshorts', ['only' => ['store']]);
    // $this->middleware('permission:edit.poyarndyeingshorts',   ['only' => ['update']]);
    // $this->middleware('permission:delete.poyarndyeingshorts', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
    $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
    $rows = $this->poyarndyeing
      ->selectRaw('
          po_yarn_dyeings.id,
          po_yarn_dyeings.po_no,
          po_yarn_dyeings.pi_no,
          po_yarn_dyeings.company_id,
          po_yarn_dyeings.supplier_id,
          po_yarn_dyeings.source_id,
          po_yarn_dyeings.pay_mode,
          po_yarn_dyeings.delv_start_date,
          po_yarn_dyeings.delv_end_date,
          po_yarn_dyeings.exch_rate,
          po_yarn_dyeings.remarks,
          companies.code as company_code,
          suppliers.name as supplier_code,
          currencies.code as currency_code,
          po_yarn_dyeings.approved_by,
          po_yarn_dyeings.amount,
          sum(po_yarn_dyeing_items.qty) as item_qty
        ')
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'po_yarn_dyeings.company_id');
      })
      ->join('suppliers', function ($join) {
        $join->on('suppliers.id', '=', 'po_yarn_dyeings.supplier_id');
      })
      ->join('currencies', function ($join) {
        $join->on('currencies.id', '=', 'po_yarn_dyeings.currency_id');
      })
      ->leftJoin('po_yarn_dyeing_items', function ($join) {
        $join->on('po_yarn_dyeing_items.po_yarn_dyeing_id', '=', 'po_yarn_dyeings.id');
      })
      ->where([['po_type_id', 2]])
      ->orderBy('po_yarn_dyeings.id', 'desc')
      ->groupBy([
        'po_yarn_dyeings.id',
        'po_yarn_dyeings.po_no',
        'po_yarn_dyeings.pi_no',
        'po_yarn_dyeings.company_id',
        'po_yarn_dyeings.supplier_id',
        'po_yarn_dyeings.source_id',
        'po_yarn_dyeings.pay_mode',
        'po_yarn_dyeings.delv_start_date',
        'po_yarn_dyeings.delv_end_date',
        'po_yarn_dyeings.exch_rate',
        'po_yarn_dyeings.remarks',
        'companies.code',
        'suppliers.name',
        'currencies.code',
        'po_yarn_dyeings.approved_by',
        'po_yarn_dyeings.amount'
      ])
      ->get()
      ->map(function ($rows) use ($source, $paymode) {
        $rows->source = isset($source[$rows->source_id]) ? $source[$rows->source_id] : '';
        $rows->paymode = isset($paymode[$rows->pay_mode]) ? $paymode[$rows->pay_mode] : '';
        $rows->item_qty = number_format($rows->item_qty, 2);
        $rows->amount = number_format($rows->amount, 2);
        $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
        $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
        $rows->approve_status = ($rows->approved_by) ? 'Approved' : '--';
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
    $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
    $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
    $basis = array_only(config('bprs.pur_order_basis'), [20]);
    $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
    $supplier = array_prepend(array_pluck($this->supplier->YarnDyeingSubcontractor(), 'name', 'id'), '-Select-', '');
    $currency = array_prepend(array_pluck($this->currency->get(), 'code', 'id'), '-Select-', '');
    $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
    $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');
    $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '', '');
    $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '', '');
    $itemcategory = array_prepend(array_pluck($this->itemcategory->get(), 'name', 'id'), '', '');
    $itemclass = array_prepend(array_pluck($this->itemclass->get(), 'name', 'id'), '', '');
    $section = array_prepend(array_pluck($this->section->get(), 'code', 'id'), '-Select-', '');
    $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '', '');
    $department = array_prepend(array_pluck($this->department->get(), 'name', 'id'), '', '');
    $shorttype = array_prepend(config('bprs.shorttype'), '-Select-', '');
    return Template::loadView("Purchase.PoYarnDyeingShort", ['company' => $company, 'source' => $source, 'supplier' => $supplier, 'currency' => $currency, 'paymode' => $paymode, 'order_type_id' => 1, 'basis' => $basis, 'uom' => $uom, 'colorrange' => $colorrange, 'color' => $color, 'buyer' => $buyer, 'itemcategory' => $itemcategory, 'itemclass' => $itemclass, 'section' => $section, 'shorttype' => $shorttype, 'designation' => $designation, 'department' => $department]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(PoYarnDyeingRequest $request)
  {
    $max = $this->poyarndyeing->where([['company_id', $request->company_id]])->max('po_no');
    $po_no = $max + 1;
    $poyarndyeing = $this->poyarndyeing->create(['po_no' => $po_no, 'po_type_id' => 2, 'po_date' => $request->po_date, 'company_id' => $request->company_id, 'source_id' => $request->source_id, 'basis_id' => 20, 'supplier_id' => $request->supplier_id, 'currency_id' => $request->currency_id, 'exch_rate' => $request->exch_rate, 'delv_start_date' => $request->delv_start_date, 'delv_end_date' => $request->delv_end_date, 'pay_mode' => $request->pay_mode, 'pi_no' => $request->pi_no, 'pi_date' => $request->pi_date, 'remarks' => $request->remarks]);

    $termscondition = $this->termscondition->where([['menu_id', '=', 9]])->orderBy('sort_id')->get();
    foreach ($termscondition as $row) {
      $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id' => $poyarndyeing->id, 'term' => $row->term, 'sort_id' => $row->sort_id, 'menu_id' => 9]);
    }

    if ($poyarndyeing) {
      return response()->json(array('success' => true, 'id' => $poyarndyeing->id, 'message' => 'Save Successfully'), 200);
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
    $poyarndyeing = $this->poyarndyeing
      ->find($id);
    $row['fromData'] = $poyarndyeing;
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
  public function update(PoYarnDyeingRequest $request, $id)
  {
    $poyarndyeingapproved = $this->poyarndyeing->find($id);
    if ($poyarndyeingapproved->approved_at) {
      $this->poyarndyeing->update($id, ['pi_no' => $request->pi_no, 'pi_date' => $request->pi_date, 'remarks' => $request->remarks]);
      return response()->json(array('success' => false, 'message' => 'Yarn Dyeing Purchase Order is Approved, Update not Possible Except PI No , PI Date & Remarks'), 200);
    }
    $poyarndyeing = $this->poyarndyeing->update($id, $request->except(['id', 'company_id']));
    if ($poyarndyeing) {
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
    if ($this->poyarndyeing->delete($id)) {
      return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
    }
  }

  public function getPdf()
  {

    $id = request('id', 0);
    $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
    $rows = $this->poyarndyeing
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'po_yarn_dyeings.company_id');
      })
      ->join('currencies', function ($join) {
        $join->on('currencies.id', '=', 'po_yarn_dyeings.currency_id');
      })
      ->join('suppliers', function ($join) {
        $join->on('suppliers.id', '=', 'po_yarn_dyeings.supplier_id');
      })
      ->join('users', function ($join) {
        $join->on('users.id', '=', 'po_yarn_dyeings.created_by');
      })
      ->leftJoin('employee_h_rs', function ($join) {
        $join->on('users.id', '=', 'employee_h_rs.user_id');
      })
      ->where([['po_yarn_dyeings.id', '=', $id]])
      ->get([
        'po_yarn_dyeings.*',
        'po_yarn_dyeings.id as po_yarn_dyeing_id',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'currencies.code as currency_name',
        'suppliers.name as supplier_name',
        'suppliers.address as supplier_address',
        'suppliers.contact_person',
        'suppliers.designation',
        'suppliers.email',
        'users.name as user_name',
        'employee_h_rs.contact'
      ])
      ->first();

    $rows->pay_mode = $paymode[$rows->pay_mode];
    $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
    $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
    $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
    $rows->contact_detail = $rows->contact_person . ',' . $rows->designation . ',' . $rows->email;

    $yarnDescription = $this->invyarnitem
      ->leftJoin('item_accounts', function ($join) {
        $join->on('item_accounts.id', '=', 'inv_yarn_items.item_account_id');
      })
      ->leftJoin('suppliers', function ($join) {
        $join->on('suppliers.id', '=', 'inv_yarn_items.supplier_id');
      })
      ->leftJoin('colors', function ($join) {
        $join->on('colors.id', '=', 'inv_yarn_items.color_id');
      })
      ->leftJoin('item_account_ratios', function ($join) {
        $join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
      })
      ->leftJoin('yarncounts', function ($join) {
        $join->on('yarncounts.id', '=', 'item_accounts.yarncount_id');
      })
      ->leftJoin('yarntypes', function ($join) {
        $join->on('yarntypes.id', '=', 'item_accounts.yarntype_id');
      })
      ->leftJoin('itemclasses', function ($join) {
        $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
      })
      ->leftJoin('compositions', function ($join) {
        $join->on('compositions.id', '=', 'item_account_ratios.composition_id');
      })
      ->leftJoin('itemcategories', function ($join) {
        $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
      })
      ->where([['itemcategories.identity', '=', 1]])
      ->get([
        'inv_yarn_items.id as inv_yarn_item_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'itemclasses.name as itemclass_name',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
      ]);
    $itemaccountArr = array();
    $yarnCompositionArr = array();
    foreach ($yarnDescription as $row) {
      $itemaccountArr[$row->inv_yarn_item_id]['count'] = $row->count . "/" . $row->symbol;
      $itemaccountArr[$row->inv_yarn_item_id]['yarn_type'] = $row->yarn_type;
      $itemaccountArr[$row->inv_yarn_item_id]['itemclass_name'] = $row->itemclass_name;
      $yarnCompositionArr[$row->inv_yarn_item_id][] = $row->composition_name . " " . $row->ratio . "%";
    }
    $yarnDropdown = array();
    foreach ($itemaccountArr as $key => $value) {
      $yarnDropdown[$key] = $value['count'] . " " . implode(",", $yarnCompositionArr[$key]) . " " . $value['yarn_type'];
    }

    $data = $this->poyarndyeing
      ->selectRaw('
          po_yarn_dyeing_items.id as po_yarn_dyeing_item_id,
          po_yarn_dyeing_items.inv_yarn_item_id,
          inv_yarn_items.lot,
          inv_yarn_items.brand,
          sales_orders.id as sales_order_id,
          sales_orders.sale_order_no,    
          sales_orders.produced_company_id,
          styles.style_ref,
          buyers.name as buyer_name,
          companies.id as company_id,
          companies.name as company_name,
          produced_company.name as produced_company_name,
          budget_yarn_dyeing_cons.id as budget_yarn_dyeing_con_id,
          colors.name as yarn_color_name,
          gmt_colors.name as gmt_color_name,
          po_yarn_dyeing_item_bom_qties.id,
          po_yarn_dyeing_item_bom_qties.qty,
          po_yarn_dyeing_item_bom_qties.rate,
          po_yarn_dyeing_item_bom_qties.amount,
          po_yarn_dyeing_item_bom_qties.process_loss_per,
          po_yarn_dyeing_item_bom_qties.req_cone,
          po_yarn_dyeing_item_bom_qties.wgt_per_cone,
          po_yarn_dyeing_item_bom_qties.remarks as qty_remarks
        ')
      ->join('po_yarn_dyeing_items', function ($join) {
        $join->on('po_yarn_dyeing_items.po_yarn_dyeing_id', '=', 'po_yarn_dyeings.id');
      })
      ->leftJoin('inv_yarn_items', function ($join) {
        $join->on('inv_yarn_items.id', '=', 'po_yarn_dyeing_items.inv_yarn_item_id');
      })
      ->leftJoin('item_accounts', function ($join) {
        $join->on('item_accounts.id', '=', 'inv_yarn_items.item_account_id');
      })

      ->join('po_yarn_dyeing_item_bom_qties', function ($join) {
        $join->on('po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id', '=', 'po_yarn_dyeing_items.id');
      })
      ->join('budget_yarn_dyeing_cons', function ($join) {
        $join->on('budget_yarn_dyeing_cons.id', '=', 'po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id');
      })
      ->join('sales_orders', function ($join) {
        $join->on('budget_yarn_dyeing_cons.sales_order_id', '=', 'sales_orders.id');
      })
      ->join('style_fabrication_stripes', function ($join) {
        $join->on('style_fabrication_stripes.id', '=', 'budget_yarn_dyeing_cons.style_fabrication_stripe_id');
      })
      ->join('style_colors', function ($join) {
        $join->on('style_colors.id', '=', 'style_fabrication_stripes.style_color_id');
      })
      ->join('colors', function ($join) {
        $join->on('colors.id', '=', 'style_fabrication_stripes.color_id');
      })
      ->join('colors as gmt_colors', function ($join) {
        $join->on('gmt_colors.id', '=', 'style_colors.color_id');
      })
      ->leftJoin('jobs', function ($join) {
        $join->on('jobs.id', '=', 'sales_orders.job_id');
      })
      ->leftJoin('companies', function ($join) {
        $join->on('companies.id', '=', 'jobs.company_id');
      })
      ->leftJoin('companies as produced_company', function ($join) {
        $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
      })
      ->leftJoin('styles', function ($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
      })
      ->leftJoin('buyers', function ($join) {
        $join->on('buyers.id', '=', 'styles.buyer_id');
      })
      ->where([['po_yarn_dyeings.id', '=', $id]])
      ->groupBy([
        'po_yarn_dyeing_items.id',
        'po_yarn_dyeing_items.inv_yarn_item_id',
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'sales_orders.id',
        'sales_orders.sale_order_no',
        'sales_orders.produced_company_id',
        'styles.style_ref',
        'buyers.name',
        'companies.id',
        'companies.name',
        'produced_company.name',
        'budget_yarn_dyeing_cons.id',
        'colors.name',
        'gmt_colors.name',
        'po_yarn_dyeing_item_bom_qties.id',
        'po_yarn_dyeing_item_bom_qties.qty',
        'po_yarn_dyeing_item_bom_qties.rate',
        'po_yarn_dyeing_item_bom_qties.amount',
        'po_yarn_dyeing_item_bom_qties.process_loss_per',
        'po_yarn_dyeing_item_bom_qties.req_cone',
        'po_yarn_dyeing_item_bom_qties.wgt_per_cone',
        'po_yarn_dyeing_item_bom_qties.remarks'
      ])
      ->get()
      ->map(function ($data) use ($yarnDropdown) {
        $data->yarn_desc = isset($yarnDropdown[$data->inv_yarn_item_id]) ? $yarnDropdown[$data->inv_yarn_item_id] : '';
        return $data;
      });
    $amount = $data->sum('amount');


    $inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $rows->currency_name, 'cents');
    $rows->inword = $inword;
    $purOrder['master'] = $rows;
    $purOrder['created_at'] = date('d-M-Y', strtotime($rows->created_at));
    $purchasetermscondition = $this->purchasetermscondition->where([['purchase_order_id', '=', $id]])->where([['menu_id', '=', 9]])->orderBy('sort_id')->get();
    $purOrder['purchasetermscondition'] = $purchasetermscondition;
    $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(true);
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(5);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->AddPage();
    $pdf->SetY(10);
    $image_file = 'images/logo/' . $rows->logo;
    $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    $pdf->SetFont('helvetica', 'N', 10);
    $pdf->SetY(12);
    $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M');
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
    $pdf->SetY(5);
    $pdf->SetX(210);
    $challan = str_pad($purOrder['master']->po_yarn_dyeing_id, 10, 0, STR_PAD_LEFT);
    $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetY(35);
    $pdf->Write(0, 'Short Yarn Dyeing Work Order', '', 0, 'C', true, 0, false, false, 0);
    $pdf->SetFont('helvetica', '', 8);
    $view = \View::make('Defult.Purchase.PoYarnDyeingShortPdf', ['purOrder' => $purOrder, 'data' => $data]);
    $html_content = $view->render();
    $pdf->SetY(40);
    $pdf->WriteHtml($html_content, true, false, true, false, '');
    $filename = storage_path() . '/PoYarnDyeingShortPdf.pdf';
    $pdf->output($filename);
  }
}
