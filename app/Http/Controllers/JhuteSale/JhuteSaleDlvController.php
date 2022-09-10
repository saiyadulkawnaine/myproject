<?php

namespace App\Http\Controllers\JhuteSale;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Http\Requests\JhuteSale\JhuteSaleDlvRequest;

class JhuteSaleDlvController extends Controller
{
 private $company;
 private $location;
 private $currency;
 private $jhutesaledlvorder;
 private $buyer;
 private $user;
 private $uom;
 private $ctrlHead;
 private $jhutesaledlv;
 private $store;
 private $designation;

 public function __construct(JhuteSaleDlvRepository $jhutesaledlv, CompanyRepository $company, LocationRepository $location, CurrencyRepository $currency, JhuteSaleDlvOrderRepository $jhutesaledlvorder, BuyerRepository $buyer, UserRepository $user, UomRepository $uom, AccChartCtrlHeadRepository $ctrlHead, StoreRepository $store, DesignationRepository $designation)
 {
  $this->company = $company;
  $this->location = $location;
  $this->currency = $currency;
  $this->jhutesaledlvorder = $jhutesaledlvorder;
  $this->buyer = $buyer;
  $this->user = $user;
  $this->uom = $uom;
  $this->ctrlHead = $ctrlHead;
  $this->jhutesaledlv = $jhutesaledlv;
  $this->store = $store;
  $this->designation = $designation;

  $this->middleware('auth');
  // $this->middleware('permission:view.jhutesaledlvs',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.jhutesaledlvs', ['only' => ['store']]);
  // $this->middleware('permission:edit.jhutesaledlvs',   ['only' => ['update']]);
  // $this->middleware('permission:delete.jhutesaledlvs', ['only' => ['destroy']]);
 }
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */

 public function index()
 {
  $rows = $this->jhutesaledlv
   ->join('jhute_sale_dlv_orders', function ($join) {
    $join->on('jhute_sale_dlv_orders.id', '=', 'jhute_sale_dlvs.jhute_sale_dlv_order_id');
   })
   ->join('stores', function ($join) {
    $join->on('stores.id', '=', 'jhute_sale_dlvs.store_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jhute_sale_dlv_orders.company_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'jhute_sale_dlv_orders.buyer_id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'jhute_sale_dlv_orders.location_id');
   })
   ->orderBy('jhute_sale_dlvs.id', 'desc')
   ->get(
    [
     'jhute_sale_dlvs.*',
     'jhute_sale_dlv_orders.do_no',
     'companies.name as company_name',
     'buyers.name as buyer_name',
     'locations.name as location_name',
     'stores.name as store_name'
    ]
   )
   ->map(function ($rows) {
    $rows->dlv_date = date('Y-m-d', strtotime($rows->dlv_date));
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
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '', '');
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
  $user = array_prepend(array_pluck($this->user->get(), 'name', 'id'), '', '');
  $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');
  $store = array_prepend(array_pluck($this->store->get(), 'name', 'id'), '-Select-', '');
  $ctrlHead = array_prepend(array_pluck($this->ctrlHead
   ->where([['acc_chart_sub_group_id', '=', 64]])
   ->where([['ctrlhead_type_id', '=', 1]])
   //->whereNotIn('ctrlhead_type_id',[1])
   ->get(), 'name', 'id'), '', '');

  return Template::loadView('JhuteSale.JhuteSaleDlv', ['company' => $company, 'location' => $location, 'currency' => $currency, 'buyer' => $buyer, 'yesno' => $yesno, 'user' => $user, 'uom' => $uom, 'ctrlHead' => $ctrlHead, 'store' => $store]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(JhuteSaleDlvRequest $request)
 {
  $max = $this->jhutesaledlv
   ->join('jhute_sale_dlv_orders', function ($join) {
    $join->on('jhute_sale_dlv_orders.id', '=', 'jhute_sale_dlvs.jhute_sale_dlv_order_id');
   })
   ->where([['jhute_sale_dlv_orders.company_id', '=', $request->company_id]])
   ->max('jhute_sale_dlvs.dlv_no');
  $dlv_no = $max + 1;

  $jhutesaledlv = $this->jhutesaledlv->create([
   'dlv_no' => $dlv_no,
   'jhute_sale_dlv_order_id' => $request->jhute_sale_dlv_order_id,
   'dlv_date' => $request->dlv_date,
   'store_id' => $request->store_id,
   'driver_name' => $request->driver_name,
   'driver_contact_no' => $request->driver_contact_no,
   'driver_license_no' => $request->driver_license_no,
   'lock_no' => $request->lock_no,
   'truck_no' => $request->truck_no,
   'remarks' => $request->remarks,
  ]);
  if ($jhutesaledlv) {
   return response()->json(array('success' => true, 'id' => $jhutesaledlv->id, 'dlv_no' => $dlv_no, 'message' => 'Save Successfully'), 200);
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
  $jhutesaledlv = $this->jhutesaledlv
   ->join('jhute_sale_dlv_orders', function ($join) {
    $join->on('jhute_sale_dlv_orders.id', '=', 'jhute_sale_dlvs.jhute_sale_dlv_order_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jhute_sale_dlv_orders.company_id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'jhute_sale_dlv_orders.location_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'jhute_sale_dlv_orders.buyer_id');
   })
   ->where([['jhute_sale_dlvs.id', '=', $id]])
   ->get([
    'jhute_sale_dlvs.*',
    'jhute_sale_dlv_orders.do_no',
    'companies.name as company_name',
    'locations.name as location_name',
    'buyers.name as buyer_name'
   ])
   ->first();
  $row['fromData'] = $jhutesaledlv;
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
 public function update(JhuteSaleDlvRequest $request, $id)
 {
  $jhutesaledlv = $this->jhutesaledlv->update($id, [
   // 'dlv_no'=>$dlv_no,
   // 'dlv_no'=>$request->dlv_no,
   //'jhute_sale_dlv_order_id'=>$request->jhute_sale_dlv_order_id,
   'dlv_date' => $request->dlv_date,
   'store_id' => $request->store_id,
   'driver_name' => $request->driver_name,
   'driver_contact_no' => $request->driver_contact_no,
   'driver_license_no' => $request->driver_license_no,
   'lock_no' => $request->lock_no,
   'truck_no' => $request->truck_no,
   'remarks' => $request->remarks,
  ]);
  if ($jhutesaledlv) {
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
  if ($this->jhutesaledlv->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Deleted Successfully'), 200);
  }
 }

 public function getJhuteSaleDlvOrder()
 {
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');

  $rows = $this->jhutesaledlvorder
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jhute_sale_dlv_orders.company_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'jhute_sale_dlv_orders.buyer_id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'jhute_sale_dlv_orders.location_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'jhute_sale_dlv_orders.currency_id');
   })
   ->leftJoin('users', function ($join) {
    $join->on('users.id', '=', 'jhute_sale_dlv_orders.advised_by_id');
   })
   ->leftJoin('users as price_verifies', function ($join) {
    $join->on('price_verifies.id', '=', 'jhute_sale_dlv_orders.price_verified_by_id');
   })
   ->whereNotNull('jhute_sale_dlv_orders.approved_by')
   ->orderBy('jhute_sale_dlv_orders.id', 'desc')
   ->get(
    [
     'jhute_sale_dlv_orders.*',
     'companies.name as company_name',
     'buyers.name as buyer_name',
     'locations.name as location_name',
     'currencies.code as currency_code',
     'users.name as advised_by',
     'price_verifies.name as price_verified_by'
    ]
   )
   ->map(function ($rows) use ($yesno) {
    $rows->payment_before_dlv = $yesno[$rows->payment_before_dlv_id];
    $rows->do_date = date('Y-m-d', strtotime($rows->do_date));
    $rows->etd_date = $rows->etd_date ? date('Y-m-d', strtotime($rows->etd_date)) : '--';
    return $rows;
   });

  echo json_encode($rows);
 }

 public function getBillPdf()
 {
  $id = request('id', 0);

  $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '--', '');

  $rows = $this->jhutesaledlv
   ->join('jhute_sale_dlv_orders', function ($join) {
    $join->on('jhute_sale_dlv_orders.id', '=', 'jhute_sale_dlvs.jhute_sale_dlv_order_id');
   })
   ->join('stores', function ($join) {
    $join->on('stores.id', '=', 'jhute_sale_dlvs.store_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jhute_sale_dlv_orders.company_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'jhute_sale_dlv_orders.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyer_branches.buyer_id', '=', 'buyers.id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'jhute_sale_dlv_orders.location_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'jhute_sale_dlv_orders.currency_id');
   })
   ->leftJoin('users as createdby_user', function ($join) {
    $join->on('createdby_user.id', '=', 'jhute_sale_dlvs.created_by');
   })
   ->leftJoin('employee_h_rs as createdby_employee', function ($join) {
    $join->on('createdby_user.id', '=', 'createdby_employee.user_id');
   })
   ->leftJoin('users as approvedby_user', function ($join) {
    $join->on('approvedby_user.id', '=', 'jhute_sale_dlv_orders.approved_by');
   })
   ->leftJoin('employee_h_rs as approvedby_employee', function ($join) {
    $join->on('approvedby_user.id', '=', 'approvedby_employee.user_id');
   })
   ->where([['jhute_sale_dlvs.id', '=', $id]])
   ->get([
    'jhute_sale_dlvs.*',
    'jhute_sale_dlv_orders.do_no',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'buyers.name as buyer_name',
    'buyer_branches.address as buyer_address',
    'locations.name as location_name',
    'stores.name as store_name',
    'currencies.code as currency_code',
    'currencies.hundreds_name',

    'createdby_user.signature_file as createdby_signature',
    'createdby_employee.name as createdby_user_name',
    'createdby_employee.contact as createdby_contact',
    'createdby_employee.designation_id as createdby_designation_id',

    'approvedby_user.signature_file as approvedby_signature',
    'approvedby_employee.name as approvedby_user_name',
    'approvedby_employee.contact as approvedby_contact',
    'approvedby_employee.designation_id as approvedby_designation_id',
   ])
   ->map(function ($rows) use ($designation) {
    $rows->dlv_date = date('Y-m-d', strtotime($rows->dlv_date));
    $rows->createdby_designation = $rows->createdby_designation_id ? $designation[$rows->createdby_designation_id] : null;
    $rows->approvedby_designation = $rows->approvedby_designation_id ? $designation[$rows->approvedby_designation_id] : null;
    $rows->createdby_signature = $rows->createdby_signature ? 'images/signature/' . $rows->createdby_signature : null;
    $rows->approvedby_signature = $rows->approvedby_signature ? 'images/signature/' . $rows->approvedby_signature : null;
    return $rows;
   })
   ->first();

  $jhutesaledlvitem = $this->jhutesaledlv
   ->join('jhute_sale_dlv_items', function ($join) {
    $join->on('jhute_sale_dlvs.id', '=', 'jhute_sale_dlv_items.jhute_sale_dlv_id');
   })
   ->join('jhute_sale_dlv_order_items', function ($join) {
    $join->on('jhute_sale_dlv_order_items.id', '=', 'jhute_sale_dlv_items.jhute_sale_dlv_order_item_id');
   })
   ->leftJoin('uoms', function ($join) {
    $join->on('uoms.id', '=', 'jhute_sale_dlv_order_items.uom_id');
   })
   ->join('acc_chart_ctrl_heads', function ($join) {
    $join->on('acc_chart_ctrl_heads.id', '=', 'jhute_sale_dlv_order_items.acc_chart_ctrl_head_id');
   })
   ->where([['jhute_sale_dlvs.id', '=', $id]])
   ->orderBy('jhute_sale_dlv_items.id', 'desc')
   ->get([
    'jhute_sale_dlv_items.*',
    'acc_chart_ctrl_heads.name as acc_chart_ctrl_head_name',
    'jhute_sale_dlv_order_items.remarks as order_item_remarks',
    'uoms.code as uom_code',
    'jhute_sale_dlv_order_items.rate'

   ]);

  $amount = $jhutesaledlvitem->sum('amount');
  $inword = Numbertowords::ntow(number_format($amount, 2, '.', ''), $rows->currency_code, $rows->hundreds_name);
  $rows->inword = $inword;

  $data['master'] = $rows;
  $data['details'] = $jhutesaledlvitem;

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
  $pdf->Cell(0, 40, 'Jhute Sales Bill', 0, false, 'C', 0, '', 0, false, 'T', 'M');
  $pdf->SetY(45);
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetTitle('Jhute Delivery Bill');
  $view = \View::make('Defult.JhuteSale.JhuteDeliveryBillPdf', ['data' => $data]);
  $html_content = $view->render();
  //$pdf->SetY(55);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $pdf->SetFont('helvetica', 'N', 8);
  $filename = storage_path() . '/JhuteDeliveryBillPdf.pdf';
  $pdf->output($filename, 'I');
  exit();
 }

 public function getChallanPdf()
 {
  $id = request('id', 0);

  $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '--', '');

  $rows = $this->jhutesaledlv
   ->join('jhute_sale_dlv_orders', function ($join) {
    $join->on('jhute_sale_dlv_orders.id', '=', 'jhute_sale_dlvs.jhute_sale_dlv_order_id');
   })
   ->join('stores', function ($join) {
    $join->on('stores.id', '=', 'jhute_sale_dlvs.store_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jhute_sale_dlv_orders.company_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'jhute_sale_dlv_orders.buyer_id');
   })
   ->leftJoin('buyer_branches', function ($join) {
    $join->on('buyer_branches.buyer_id', '=', 'buyers.id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'jhute_sale_dlv_orders.location_id');
   })
   ->leftJoin('users as createdby_user', function ($join) {
    $join->on('createdby_user.id', '=', 'jhute_sale_dlvs.created_by');
   })
   ->leftJoin('employee_h_rs as createdby_employee', function ($join) {
    $join->on('createdby_user.id', '=', 'createdby_employee.user_id');
   })
   ->leftJoin('users as approvedby_user', function ($join) {
    $join->on('approvedby_user.id', '=', 'jhute_sale_dlv_orders.approved_by');
   })
   ->leftJoin('employee_h_rs as approvedby_employee', function ($join) {
    $join->on('approvedby_user.id', '=', 'approvedby_employee.user_id');
   })
   ->where([['jhute_sale_dlvs.id', '=', $id]])
   ->get(
    [
     'jhute_sale_dlvs.*',
     'jhute_sale_dlv_orders.do_no',
     'companies.name as company_name',
     'companies.logo as logo',
     'companies.address as company_address',
     'buyers.name as buyer_name',
     'buyer_branches.address as buyer_address',
     'locations.name as location_name',
     'stores.name as store_name',

     'createdby_user.signature_file as createdby_signature',
     'createdby_employee.name as createdby_user_name',
     'createdby_employee.contact as createdby_contact',
     'createdby_employee.designation_id as createdby_designation_id',

     'approvedby_user.signature_file as approvedby_signature',
     'approvedby_employee.name as approvedby_user_name',
     'approvedby_employee.contact as approvedby_contact',
     'approvedby_employee.designation_id as approvedby_designation_id',
    ]
   )
   ->map(function ($rows) use ($designation) {
    $rows->dlv_date = date('Y-m-d', strtotime($rows->dlv_date));
    $rows->createdby_designation = $rows->createdby_designation_id ? $designation[$rows->createdby_designation_id] : null;
    $rows->approvedby_designation = $rows->approvedby_designation_id ? $designation[$rows->approvedby_designation_id] : null;
    $rows->createdby_signature = $rows->createdby_signature ? 'images/signature/' . $rows->createdby_signature : null;
    $rows->approvedby_signature = $rows->approvedby_signature ? 'images/signature/' . $rows->approvedby_signature : null;
    return $rows;
   })
   ->first();

  $jhutesaledlvitem = $this->jhutesaledlv
   ->join('jhute_sale_dlv_items', function ($join) {
    $join->on('jhute_sale_dlvs.id', '=', 'jhute_sale_dlv_items.jhute_sale_dlv_id');
   })
   ->join('jhute_sale_dlv_order_items', function ($join) {
    $join->on('jhute_sale_dlv_order_items.id', '=', 'jhute_sale_dlv_items.jhute_sale_dlv_order_item_id');
   })
   ->leftJoin('uoms', function ($join) {
    $join->on('uoms.id', '=', 'jhute_sale_dlv_order_items.uom_id');
   })
   ->join('acc_chart_ctrl_heads', function ($join) {
    $join->on('acc_chart_ctrl_heads.id', '=', 'jhute_sale_dlv_order_items.acc_chart_ctrl_head_id');
   })
   ->where([['jhute_sale_dlvs.id', '=', $id]])
   ->orderBy('jhute_sale_dlv_items.id', 'desc')
   ->get([
    'jhute_sale_dlv_items.*',
    'acc_chart_ctrl_heads.name as acc_chart_ctrl_head_name',
    'uoms.code as uom_code',
    'jhute_sale_dlv_order_items.remarks as order_item_remarks',
    'jhute_sale_dlv_order_items.rate'

   ]);



  $data['master'] = $rows;
  $data['details'] = $jhutesaledlvitem;

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
  $pdf->Cell(0, 40, 'Jhute Delivery Challan', 0, false, 'C', 0, '', 0, false, 'T', 'M');
  $pdf->SetY(45);
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetTitle('Jhute Delivery Challan');
  $view = \View::make('Defult.JhuteSale.JhuteDeliveryChallanPdf', ['data' => $data]);
  $html_content = $view->render();
  //$pdf->SetY(55);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $pdf->SetFont('helvetica', '', 8);
  $filename = storage_path() . '/JhuteDeliveryChallanPdf.pdf';
  $pdf->output($filename, 'I');
  exit();
 }
}
