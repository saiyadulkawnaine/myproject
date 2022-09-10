<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnIsuRequest;

class InvYarnIsuSamSecController extends Controller
{

 private $invisu;
 private $invyarnisu;
 private $company;
 private $location;
 private $currency;
 private $supplier;
 private $store;
 private $itemaccount;
 private $autoyarn;

 public function __construct(
  InvIsuRepository $invisu,
  InvYarnIsuRepository $invyarnisu,
  CompanyRepository $company,
  LocationRepository $location,
  CurrencyRepository $currency,
  SupplierRepository $supplier,
  StoreRepository $store,
  ItemAccountRepository $itemaccount,
  AutoyarnRepository $autoyarn
 ) {
  $this->invisu = $invisu;
  $this->invyarnisu = $invyarnisu;
  $this->company = $company;
  $this->location = $location;
  $this->currency = $currency;
  $this->supplier = $supplier;
  $this->store = $store;
  $this->itemaccount = $itemaccount;
  $this->autoyarn = $autoyarn;
  $this->middleware('auth');
  //$this->middleware('permission:view.invyarnisu',   ['only' => ['create', 'index','show']]);
  //$this->middleware('permission:create.invyarnisu', ['only' => ['store']]);
  //$this->middleware('permission:edit.invyarnisu',   ['only' => ['update']]);
  //$this->middleware('permission:delete.invyarnisu', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $invissuebasis = array_prepend(config('bprs.invissuebasis'), '-Select-', '');
  $rows = $this->invisu
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'inv_isus.company_id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_isus.supplier_id');
   })
   ->orderBy('inv_isus.id', 'desc')
   ->where([['inv_isus.menu_id', '=', 104]])
   ->get(['inv_isus.*', 'companies.name as company_name', 'suppliers.name as supplier_name'])
   ->map(function ($rows) use ($invissuebasis) {
    $rows->isu_basis_id = $invissuebasis[$rows->isu_basis_id];
    $rows->issue_date = date('d-M-Y', strtotime($rows->issue_date));
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
  $purchasesource = array_prepend(config('bprs.purchasesource'), '-Select-', '');
  $invissuebasis = array_prepend(array_only(config('bprs.invissuebasis'), [6, 7, 8]), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');
  $currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
  $store = array_prepend(array_pluck($this->store->get(), 'name', 'id'), '-Select-', '');
  $menu = array_prepend(array_only(config('bprs.menu'), [0]), '-Select-', '');
  return Template::loadView('Inventory.Yarn.InvYarnIsuSamSec', ['company' => $company, 'currency' => $currency, 'purchasesource' => $purchasesource, 'invissuebasis' => $invissuebasis, 'supplier' => $supplier, 'store' => $store, 'menu' => $menu]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(InvYarnIsuRequest $request)
 {
  $max = $this->invisu
   ->where([['company_id', '=', $request->company_id]])
   ->whereIn('menu_id', [101, 104, 107, 111])
   ->max('issue_no');
  $issue_no = $max + 1;

  $invisu = $this->invisu->create([
   'menu_id' => 104,
   'issue_no' => $issue_no,
   'company_id' => $request->company_id,
   'supplier_id' => $request->supplier_id,
   'isu_basis_id' => $request->isu_basis_id,
   'isu_against_id' => $request->isu_against_id,
   'issue_date' => $request->issue_date,
   'driver_name' => $request->driver_name,
   'driver_contact_no' => $request->driver_contact_no,
   'driver_license_no' => $request->driver_license_no,
   'lock_no' => $request->lock_no,
   'truck_no' => $request->truck_no,
   'recipient' => $request->recipient,
   'remarks' => $request->remarks,
  ]);

  if ($invisu) {
   return response()->json(array('success' => true, 'id' => $invisu->id, 'issue_no' => $issue_no, 'message' => 'Saved Successfully'), 200);
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
  $invyarnisu = $this->invisu
   ->where([['inv_isus.id', '=', $id]])
   ->get()
   ->first();
  $row['fromData'] = $invyarnisu;
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
 public function update(InvYarnIsuRequest $request, $id)
 {
  $invisu = $this->invisu->update($id, $request->except(['id', 'company_id', 'supplier_id', 'isu_basis_id', 'isu_against_id']));
  if ($invisu) {
   return response()->json(array('success' => true, 'id' => $id, 'message' => 'Updated Successfully'), 200);
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
  return response()->json(array('success' => false, 'message' => 'Deleted Not Successfully'), 200);
  if ($this->invisu->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Deleted Successfully'), 200);
  }
 }


 public function getPdf()
 {
  $id = request('id', 0);
  $invissuebasis = array_prepend(config('bprs.invissuebasis'), '-Select-', '');
  $menu = array_prepend(config('bprs.menu'), '-Select-', '');
  $rows = $this->invisu
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'inv_isus.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_isus.supplier_id');
   })
   ->join('users', function ($join) {
    $join->on('users.id', '=', 'inv_isus.created_by');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('users.id', '=', 'employee_h_rs.user_id');
   })
   ->where([['inv_isus.id', '=', $id]])
   ->get([
    'inv_isus.*',
    'inv_isus.remarks as master_remarks',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'suppliers.name as supplier_name',
    'suppliers.address as supplier_address',
    'users.name as user_name',
    'employee_h_rs.contact'
   ])
   ->first();
  $rows->isu_basis_id = $invissuebasis[$rows->isu_basis_id];
  $rows->isu_against_id = $menu[$rows->isu_against_id];
  $rows->issue_date = date('d-M-Y', strtotime($rows->issue_date));
  //$rows->contact_detail=$rows->contact_person.','.$rows->designation.','.$rows->email;
  $autoyarn = $this->autoyarn
   ->join('autoyarnratios', function ($join) {
    $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
   })
   ->join('constructions', function ($join) {
    $join->on('autoyarns.construction_id', '=', 'constructions.id');
   })
   ->join('compositions', function ($join) {
    $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
   })
   ->when(request('construction_name'), function ($q) {
    return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
   })
   ->when(request('composition_name'), function ($q) {
    return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
   })
   ->orderBy('autoyarns.id', 'desc')
   ->get([
    'autoyarns.*',
    'constructions.name',
    'compositions.name as composition_name',
    'autoyarnratios.ratio'
   ]);

  $fabricDescriptionArr = array();
  $fabricCompositionArr = array();
  foreach ($autoyarn as $row) {
   $fabricDescriptionArr[$row->id] = $row->name;
   $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }

  $desDropdown = array();
  foreach ($fabricDescriptionArr as $key => $val) {
   $desDropdown[$key] = $val . "," . implode(",", $fabricCompositionArr[$key]);
  }

  $yarnDescription = $this->itemaccount
   ->leftJoin('item_account_ratios', function ($join) {
    $join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
   })
   ->leftJoin('compositions', function ($join) {
    $join->on('compositions.id', '=', 'item_account_ratios.composition_id');
   })
   ->leftJoin('itemclasses', function ($join) {
    $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
   })
   ->leftJoin('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })
   ->where([['itemcategories.identity', '=', 1]])
   ->orderBy('item_account_ratios.ratio', 'desc')
   ->get([
    'item_accounts.id',
    'compositions.name as composition_name',
    'item_account_ratios.ratio',
   ]);

  $itemaccountArr = array();
  $yarnCompositionArr = array();
  foreach ($yarnDescription as $row) {
   $itemaccountArr[$row->id]['count'] = $row->count . "/" . $row->symbol;
   $yarnCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }

  $yarnDropdown = array();
  foreach ($itemaccountArr as $key => $value) {
   $yarnDropdown[$key] = implode(",", $yarnCompositionArr[$key]);
  }

  $invyarnisuitem = $this->invisu
   ->join('inv_yarn_isu_items', function ($join) {
    $join->on('inv_yarn_isu_items.inv_isu_id', '=', 'inv_isus.id');
   })
   /*->join('inv_yarn_transactions',function($join){
        $join->on('inv_yarn_transactions.inv_yarn_isu_item_id','=','inv_yarn_isu_items.id');
        $join->whereNull('inv_yarn_transactions.deleted_at');
      })
      ->join('inv_yarn_rcv_items',function($join){
        $join->on('inv_yarn_rcv_items.id','=','inv_yarn_transactions.inv_yarn_rcv_item_id');
      })*/
   ->join('inv_yarn_items', function ($join) {
    $join->on('inv_yarn_items.id', '=', 'inv_yarn_isu_items.inv_yarn_item_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_yarn_items.supplier_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('inv_yarn_items.item_account_id', '=', 'item_accounts.id');
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
   ->join('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'item_accounts.uom_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'inv_yarn_items.color_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'inv_yarn_isu_items.style_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'inv_yarn_isu_items.sale_order_id');
   })
   ->join('style_samples', function ($join) {
    $join->on('style_samples.id', '=', 'inv_yarn_isu_items.style_sample_id');
   })
   ->join('gmtssamples', function ($join) {
    $join->on('gmtssamples.id', '=', 'style_samples.gmtssample_id');
   })

   ->where([['inv_isus.id', '=', $id]])
   ->orderBy('inv_yarn_isu_items.id', 'desc')
   ->get([
    'inv_yarn_isu_items.*',
    'inv_yarn_items.lot',
    'inv_yarn_items.brand',
    'colors.name as color_name',
    'itemcategories.name as itemcategory_name',
    'itemclasses.name as itemclass_name',
    'item_accounts.id as item_account_id',
    'yarncounts.count',
    'yarncounts.symbol',
    'yarntypes.name as yarn_type',
    'uoms.code as uom_code',
    'suppliers.name as supplier_name',
    'sales_orders.sale_order_no',
    'styles.style_ref',
    'buyers.name as buyer_name',
    'gmtssamples.name as sample_name',

   ])
   ->map(function ($invyarnisuitem) use ($yarnDropdown, $desDropdown) {
    $invyarnisuitem->store_qty = $invyarnisuitem->qty - $invyarnisuitem->returned_qty;
    $invyarnisuitem->yarn_count = $invyarnisuitem->count . "/" . $invyarnisuitem->symbol;
    $invyarnisuitem->composition = isset($yarnDropdown[$invyarnisuitem->item_account_id]) ? $yarnDropdown[$invyarnisuitem->item_account_id] : '';
    return $invyarnisuitem;
   });

  $data['master']    = $rows;
  $data['details']   = $invyarnisuitem;

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
  $challan = str_pad($data['master']->id, 10, 0, STR_PAD_LEFT);


  $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(true);
  $pdf->SetPrintFooter(true);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $header['logo'] = $rows->logo;
  $header['address'] = $rows->company_address;
  $header['title'] = 'Yarn Issue Challan / Gate Pass';
  $header['barcodestyle'] = $barcodestyle;
  $header['barcodeno'] = $challan;
  $pdf->setCustomHeader($header);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  //$pdf->SetY(10);
  //$image_file ='images/logo/'.$rows->logo;
  //$pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  //$pdf->SetY(13);
  //$pdf->SetFont('helvetica', 'N', 8);
  //$pdf->Text(115, 12, $rows->company_address);

  /*$pdf->SetY(3);
        $pdf->SetX(190);
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');*/
  $pdf->SetFont('helvetica', '', 8);
  $pdf->SetTitle('Yarn Issue Challan / Gate Pass');
  $view = \View::make('Defult.Inventory.Yarn.YarnIsuSamSecPdf', ['data' => $data]);
  $html_content = $view->render();
  $pdf->SetY(42);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/YarnIsuSamSecPdf.pdf';
  $pdf->output($filename);
 }

 public function getPdf2()
 {
  $id = request('id', 0);
  $invissuebasis = array_prepend(config('bprs.invissuebasis'), '-Select-', '');
  $menu = array_prepend(config('bprs.menu'), '-Select-', '');
  $rows = $this->invisu
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'inv_isus.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_isus.supplier_id');
   })
   ->join('users', function ($join) {
    $join->on('users.id', '=', 'inv_isus.created_by');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('users.id', '=', 'employee_h_rs.user_id');
   })
   ->where([['inv_isus.id', '=', $id]])
   ->get([
    'inv_isus.*',
    'inv_isus.remarks as master_remarks',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'suppliers.name as supplier_name',
    'suppliers.address as supplier_address',
    'users.name as user_name',
    'employee_h_rs.contact'
   ])
   ->first();
  $rows->isu_basis_id = $invissuebasis[$rows->isu_basis_id];
  $rows->isu_against_id = $menu[$rows->isu_against_id];
  $rows->issue_date = date('d-M-Y', strtotime($rows->issue_date));
  //$rows->contact_detail=$rows->contact_person.','.$rows->designation.','.$rows->email;
  $autoyarn = $this->autoyarn
   ->join('autoyarnratios', function ($join) {
    $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
   })
   ->join('constructions', function ($join) {
    $join->on('autoyarns.construction_id', '=', 'constructions.id');
   })
   ->join('compositions', function ($join) {
    $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
   })
   ->when(request('construction_name'), function ($q) {
    return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
   })
   ->when(request('composition_name'), function ($q) {
    return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
   })
   ->orderBy('autoyarns.id', 'desc')
   ->get([
    'autoyarns.*',
    'constructions.name',
    'compositions.name as composition_name',
    'autoyarnratios.ratio'
   ]);

  $fabricDescriptionArr = array();
  $fabricCompositionArr = array();
  foreach ($autoyarn as $row) {
   $fabricDescriptionArr[$row->id] = $row->name;
   $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }

  $desDropdown = array();
  foreach ($fabricDescriptionArr as $key => $val) {
   $desDropdown[$key] = $val . "," . implode(",", $fabricCompositionArr[$key]);
  }

  $yarnDescription = $this->itemaccount
   ->leftJoin('item_account_ratios', function ($join) {
    $join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
   })
   ->leftJoin('compositions', function ($join) {
    $join->on('compositions.id', '=', 'item_account_ratios.composition_id');
   })
   ->leftJoin('itemclasses', function ($join) {
    $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
   })
   ->leftJoin('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })
   ->where([['itemcategories.identity', '=', 1]])
   ->orderBy('item_account_ratios.ratio', 'desc')
   ->get([
    'item_accounts.id',
    'compositions.name as composition_name',
    'item_account_ratios.ratio',
   ]);

  $itemaccountArr = array();
  $yarnCompositionArr = array();
  foreach ($yarnDescription as $row) {
   $itemaccountArr[$row->id]['count'] = $row->count . "/" . $row->symbol;
   $yarnCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }

  $yarnDropdown = array();
  foreach ($itemaccountArr as $key => $value) {
   $yarnDropdown[$key] = implode(",", $yarnCompositionArr[$key]);
  }

  $invyarnisuitem = $this->invisu
   ->join('inv_yarn_isu_items', function ($join) {
    $join->on('inv_yarn_isu_items.inv_isu_id', '=', 'inv_isus.id');
   })
   /*->join('inv_yarn_transactions',function($join){
        $join->on('inv_yarn_transactions.inv_yarn_isu_item_id','=','inv_yarn_isu_items.id');
        $join->whereNull('inv_yarn_transactions.deleted_at');
      })
      ->join('inv_yarn_rcv_items',function($join){
        $join->on('inv_yarn_rcv_items.id','=','inv_yarn_transactions.inv_yarn_rcv_item_id');
      })*/
   ->join('inv_yarn_items', function ($join) {
    $join->on('inv_yarn_items.id', '=', 'inv_yarn_isu_items.inv_yarn_item_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_yarn_items.supplier_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('inv_yarn_items.item_account_id', '=', 'item_accounts.id');
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
   ->join('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'item_accounts.uom_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'inv_yarn_items.color_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'inv_yarn_isu_items.style_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'inv_yarn_isu_items.sale_order_id');
   })
   ->join('style_samples', function ($join) {
    $join->on('style_samples.id', '=', 'inv_yarn_isu_items.style_sample_id');
   })
   ->join('gmtssamples', function ($join) {
    $join->on('gmtssamples.id', '=', 'style_samples.gmtssample_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })


   ->where([['inv_isus.id', '=', $id]])
   ->orderBy('inv_yarn_isu_items.id', 'desc')
   ->get([
    'inv_yarn_isu_items.*',
    'inv_yarn_items.lot',
    'inv_yarn_items.brand',
    'colors.name as color_name',
    'itemcategories.name as itemcategory_name',
    'itemclasses.name as itemclass_name',
    'item_accounts.id as item_account_id',
    'yarncounts.count',
    'yarncounts.symbol',
    'yarntypes.name as yarn_type',
    'uoms.code as uom_code',
    'suppliers.name as supplier_name',
    'sales_orders.sale_order_no',
    'styles.style_ref',
    'buyers.name as buyer_name',
    'gmtssamples.name as sample_name',
    'companies.code as company_code'

   ])
   ->map(function ($invyarnisuitem) use ($yarnDropdown, $desDropdown) {
    $invyarnisuitem->store_qty = $invyarnisuitem->qty - $invyarnisuitem->returned_qty;
    $invyarnisuitem->yarn_count = $invyarnisuitem->count . "/" . $invyarnisuitem->symbol;
    $invyarnisuitem->composition = isset($yarnDropdown[$invyarnisuitem->item_account_id]) ? $yarnDropdown[$invyarnisuitem->item_account_id] : '';
    return $invyarnisuitem;
   });

  $data['master']    = $rows;
  $data['details']   = $invyarnisuitem;

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
  $challan = str_pad($data['master']->id, 10, 0, STR_PAD_LEFT);


  $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(true);
  $pdf->SetPrintFooter(true);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $header['logo'] = $rows->logo;
  $header['address'] = $rows->company_address;
  $header['title'] = 'Yarn Issue Challan / Gate Pass';
  $header['barcodestyle'] = $barcodestyle;
  $header['barcodeno'] = $challan;
  $pdf->setCustomHeader($header);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  //$pdf->SetY(10);
  //$image_file ='images/logo/'.$rows->logo;
  //$pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  //$pdf->SetY(13);
  //$pdf->SetFont('helvetica', 'N', 8);
  //$pdf->Text(115, 12, $rows->company_address);

  /*$pdf->SetY(3);
        $pdf->SetX(190);
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');*/
  $pdf->SetFont('helvetica', '', 8);
  $pdf->SetTitle('Yarn Issue Challan / Gate Pass');
  $view = \View::make('Defult.Inventory.Yarn.YarnIsuSamSecPdf2', ['data' => $data]);
  $html_content = $view->render();
  $pdf->SetY(42);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/YarnIsuSamSecPdf.pdf';
  $pdf->output($filename);
 }
}
