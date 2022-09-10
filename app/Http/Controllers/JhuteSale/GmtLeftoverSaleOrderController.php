<?php

namespace App\Http\Controllers\JhuteSale;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Library\Template;
use App\Library\Sms;
use App\Http\Requests\JhuteSale\GmtLeftoverSaleOrderRequest;

class GmtLeftoverSaleOrderController extends Controller
{
 private $company;
 private $location;
 private $currency;
 private $jhutesaledlvorder;
 private $buyer;
 private $user;
 private $uom;
 private $ctrlHead;
 private $designation;

 public function __construct(CompanyRepository $company, LocationRepository $location, CurrencyRepository $currency, JhuteSaleDlvOrderRepository $jhutesaledlvorder, BuyerRepository $buyer, UserRepository $user, UomRepository $uom, AccChartCtrlHeadRepository $ctrlHead, DesignationRepository $designation)
 {
  $this->company = $company;
  $this->location = $location;
  $this->currency = $currency;
  $this->jhutesaledlvorder = $jhutesaledlvorder;
  $this->buyer = $buyer;
  $this->user = $user;
  $this->uom = $uom;
  $this->ctrlHead = $ctrlHead;
  $this->designation = $designation;

  $this->middleware('auth');
  // $this->middleware('permission:view.gmtleftoversaledlvorders',   ['only' => ['create', 'index','show']]);
  // $this->middleware('permission:create.gmtleftoversaledlvorders', ['only' => ['store']]);
  // $this->middleware('permission:edit.gmtleftoversaledlvorders',   ['only' => ['update']]);
  // $this->middleware('permission:delete.gmtleftoversaledlvorders', ['only' => ['destroy']]);
 }
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');

  $rows = $this->jhutesaledlvorder
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jhute_sale_dlv_orders.company_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'jhute_sale_dlv_orders.buyer_id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('locations.id', '=', 'jhute_sale_dlv_orders.location_id');
   })
   ->leftJoin('currencies', function ($join) {
    $join->on('currencies.id', '=', 'jhute_sale_dlv_orders.currency_id');
   })
   ->leftJoin('users', function ($join) {
    $join->on('users.id', '=', 'jhute_sale_dlv_orders.advised_by_id');
   })
   ->leftJoin('users as price_verifies', function ($join) {
    $join->on('price_verifies.id', '=', 'jhute_sale_dlv_orders.price_verified_by_id');
   })
   ->where([['jhute_sale_dlv_orders.do_for', '=', 2]])
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
  $ctrlHead = array_prepend(array_pluck($this->ctrlHead
   ->where([['acc_chart_sub_group_id', '=', 64]])
   ->where([['ctrlhead_type_id', '=', 1]])
   ->get(), 'name', 'id'), '', '');

  $status = array_prepend(config('bprs.status'), '-Select-', '');

  return Template::loadView('JhuteSale.GmtLeftoverSaleOrder', ['company' => $company, 'location' => $location, 'currency' => $currency, 'buyer' => $buyer, 'yesno' => $yesno, 'user' => $user, 'uom' => $uom, 'ctrlHead' => $ctrlHead, 'status' => $status]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(GmtLeftoverSaleOrderRequest $request)
 {
  $max = $this->jhutesaledlvorder
   ->where([['company_id', $request->company_id]])
   ->max('do_no');
  $do_no = $max + 1;
  $jhutesaledlvorder = $this->jhutesaledlvorder->create([
   'do_no' => $do_no,
   'company_id' => $request->company_id,
   'location_id' => $request->location_id,
   'do_date' => $request->do_date,
   'currency_id' => $request->currency_id,
   'etd_date' => $request->etd_date,
   'buyer_id' => $request->buyer_id,
   'advised_by_id' => $request->advised_by_id,
   'price_verified_by_id' => $request->price_verified_by_id,
   'payment_before_dlv_id' => $request->payment_before_dlv_id,
   'remarks' => $request->remarks,
   'do_for' => 2,
   'status_id' => $request->status_id,
   'ready_to_approve_id' => 0,
  ]);
  if ($jhutesaledlvorder) {
   return response()->json(array('success' => true, 'id' => $jhutesaledlvorder->id, 'do_no' => $do_no, 'message' => 'Save Successfully'), 200);
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
  $jhutesaledlvorder = $this->jhutesaledlvorder->find($id);
  $row['fromData'] = $jhutesaledlvorder;
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

 public function update(GmtLeftoverSaleOrderRequest $request, $id)
 {
  $dlvOrder = $this->jhutesaledlvorder->find($id);
  if ($dlvOrder->approved_by && $dlvOrder->approved_at) {
   return response()->json(array('success' => false,  'message' => 'Approved, Update not Possible.'), 200);
  }

  $orderItem = $this->jhutesaledlvorder
   ->join('jhute_sale_dlv_order_items', function ($join) {
    $join->on('jhute_sale_dlv_order_items.jhute_sale_dlv_order_id', '=', 'jhute_sale_dlv_orders.id');
   })
   ->where([['jhute_sale_dlv_orders.id', '=', $id]])
   ->get(['jhute_sale_dlv_order_items.qty'])
   ->first();
  //   dd($yarnqty);die;

  if ($request->ready_to_approve_id == 1 && $orderItem == null) {
   return response()->json(array('success' => false, 'message' => 'Jhute Sale delivery order is not ready to approve. Item Details not found'), 200);
  }

  $jhutesaledlvorder = $this->jhutesaledlvorder->update($id, $request->except(['id', 'do_no', 'company_id']));

  if ($jhutesaledlvorder) {
   if ($request->ready_to_approve_id == 1 && $request->approved_by == null) {
    $rows = $this->jhutesaledlvorder
     ->leftJoin('buyers', function ($join) {
      $join->on('buyers.id', '=', 'jhute_sale_dlv_orders.buyer_id');
     })
     ->leftJoin(\DB::raw("(
                    select
                    jhute_sale_dlv_orders.id as jhute_sale_dlv_order_id,
                    sum(jhute_sale_dlv_order_items.qty) as item_qty,
                    sum(jhute_sale_dlv_order_items.amount) as item_amount
                    from jhute_sale_dlv_orders
                    join jhute_sale_dlv_order_items on jhute_sale_dlv_order_items.jhute_sale_dlv_order_id=jhute_sale_dlv_orders.id
                    group by jhute_sale_dlv_orders.id
                ) saleitems"), "saleitems.jhute_sale_dlv_order_id", "=", "jhute_sale_dlv_orders.id")
     ->leftJoin(\DB::raw("(
                    select
                    jhute_sale_dlv_orders.id as jhute_sale_dlv_order_id,
                    sum(jhute_sale_dlv_order_payments.amount) as paid_amount
                    from jhute_sale_dlv_orders
                    join jhute_sale_dlv_order_payments on jhute_sale_dlv_order_payments.jhute_sale_dlv_order_id=jhute_sale_dlv_orders.id
                    group by jhute_sale_dlv_orders.id
                ) payments"), "payments.jhute_sale_dlv_order_id", "=", "jhute_sale_dlv_orders.id")
     ->where([['jhute_sale_dlv_orders.id', '=', $id]])
     ->get([
      'jhute_sale_dlv_orders.*',
      'buyers.name as customer_name',
      'saleitems.item_amount',
      'payments.paid_amount',
     ])
     ->first();

    $rows->do_date = date('d-M-Y', strtotime($rows->do_date));

    $approveuser = $this->user
     ->join('permission_user', function ($join) {
      $join->on('users.id', '=', 'permission_user.user_id');
     })
     ->join('permissions', function ($join) {
      $join->on('permissions.id', '=', 'permission_user.permission_id');
     })
     ->join('employee_h_rs', function ($join) {
      $join->on('users.id', '=', 'employee_h_rs.user_id');
     })
     ->where([['permissions.id', '=', 3090]])
     ->get([
      'permissions.id',
      'employee_h_rs.contact'
     ]);

    $approvalArr = [];
    foreach ($approveuser as $data) {
     $approvalArr[3090][] = '88' . $data->contact;
    }

    $approvalusercontact = implode(',', $approvalArr[3090]);

    $title = 'DO Approval Request';
    $text =
     $title . "\n" .
     'DO No:' . $rows->do_no . "\n" .
     'DO Date:' . $rows->do_date . "\n" .
     'DO Amount:' . $rows->item_amount . "\n" .
     'Received Amount:' . $rows->paid_amount . "\n" .
     'Customer:' . $rows->customer_name;
    //dd($text);die;
    $sms = Sms::send_sms($text, $approvalusercontact);
    return response()->json(array('success' => true, 'sms' => $sms, 'id' => $id, 'message' => 'Update Successfully'), 200);
   } else {
    return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
   }
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
  if ($this->jhutesaledlvorder->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Deleted Successfully'), 200);
  }
 }

 public function getPdf()
 {
  $id = request('id', 0);
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
  $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '--', '');

  $master = $this->jhutesaledlvorder
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
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('users.id', '=', 'employee_h_rs.user_id');
   })
   ->leftJoin('users as price_varify_user', function ($join) {
    $join->on('price_varify_user.id', '=', 'jhute_sale_dlv_orders.price_verified_by_id');
   })
   ->leftJoin('employee_h_rs as price_verify_employee', function ($join) {
    $join->on('price_varify_user.id', '=', 'price_verify_employee.user_id');
   })
   ->leftJoin('users as createdby_user', function ($join) {
    $join->on('createdby_user.id', '=', 'jhute_sale_dlv_orders.created_by');
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
   ->leftJoin(\DB::raw(
    '(
            select 
            jhute_sale_dlv_order_payments.jhute_sale_dlv_order_id,
            sum(jhute_sale_dlv_order_payments.amount) as payment_received
            from jhute_sale_dlv_order_payments
            where 
            jhute_sale_dlv_order_payments.deleted_at is null
            group by 
            jhute_sale_dlv_order_payments.jhute_sale_dlv_order_id
            ) payments'
   ), 'payments.jhute_sale_dlv_order_id', '=', 'jhute_sale_dlv_orders.id')
   ->where([['jhute_sale_dlv_orders.id', '=', $id]])
   ->get([
    'jhute_sale_dlv_orders.*',
    'locations.name as location_name',
    'currencies.code as currency_code',
    'currencies.hundreds_name',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'buyers.name as buyer_name',
    'buyers.cell_no as buyer_cell_no',
    'payments.payment_received',

    'users.signature_file as advisedby_signature',
    'employee_h_rs.name as advisedby_user_name',
    'employee_h_rs.contact as advisedby_contact',
    'employee_h_rs.designation_id as advisedby_designation_id',

    'price_varify_user.signature_file as price_varify_signature',
    'price_verify_employee.name as price_verify_user_name',
    'price_verify_employee.contact as price_verify_contact',
    'price_verify_employee.designation_id as price_verify_designation_id',

    'createdby_user.signature_file as createdby_signature',
    'createdby_employee.name as createdby_user_name',
    'createdby_employee.contact as createdby_contact',
    'createdby_employee.designation_id as createdby_designation_id',

    'approvedby_user.signature_file as approvedby_signature',
    'approvedby_employee.name as approvedby_user_name',
    'approvedby_employee.contact as approvedby_contact',
    'approvedby_employee.designation_id as approvedby_designation_id',
   ])
   ->map(function ($master) use ($yesno, $designation) {
    $master->payment_before_dlv = $yesno[$master->payment_before_dlv_id];
    $master->do_date = date('Y-m-d', strtotime($master->do_date));
    $master->etd_date = $master->etd_date ? date('Y-m-d', strtotime($master->etd_date)) : '--';

    $master->advisedby_designation = $master->advisedby_designation_id ? $designation[$master->advisedby_designation_id] : null;
    $master->price_verify_designation = $master->price_verify_designation_id ? $designation[$master->price_verify_designation_id] : null;
    $master->createdby_designation = $master->createdby_designation_id ? $designation[$master->createdby_designation_id] : null;
    $master->approvedby_designation = $master->approvedby_designation_id ? $designation[$master->approvedby_designation_id] : null;

    $master->advisedby_signature = $master->advisedby_signature ? 'images/signature/' . $master->advisedby_signature : null;
    $master->price_varify_signature = $master->price_varify_signature ? 'images/signature/' . $master->price_varify_signature : null;
    $master->createdby_signature = $master->createdby_signature ? 'images/signature/' . $master->createdby_signature : null;
    $master->approvedby_signature = $master->approvedby_signature ? 'images/signature/' . $master->approvedby_signature : null;
    return $master;
   })
   ->first();

  // dd($master);die;

  $rows = $this->jhutesaledlvorder
   ->join('jhute_sale_dlv_order_items', function ($join) {
    $join->on('jhute_sale_dlv_order_items.jhute_sale_dlv_order_id', '=', 'jhute_sale_dlv_orders.id');
   })
   ->join('acc_chart_ctrl_heads', function ($join) {
    $join->on('jhute_sale_dlv_order_items.acc_chart_ctrl_head_id', '=', 'acc_chart_ctrl_heads.id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'jhute_sale_dlv_order_items.uom_id');
   })
   ->where([['jhute_sale_dlv_orders.id', '=', $id]])
   ->get([
    'acc_chart_ctrl_heads.name as ctrl_head_name',
    'jhute_sale_dlv_order_items.qty',
    'jhute_sale_dlv_order_items.rate',
    'jhute_sale_dlv_order_items.amount',
    'jhute_sale_dlv_order_items.remarks as item_remarks',
    'uoms.code as uom_code'
   ]);

  $accounts = collect(\DB::select('
        select 
        acc_trans_sales.buyer_id,
        sum(acc_trans_sales.amount) as amount 
        from acc_trans_prnts
        join acc_trans_sales on acc_trans_sales.acc_trans_prnt_id=acc_trans_prnts.id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_sales.acc_chart_ctrl_head_id
        where acc_trans_sales.buyer_id=? and 
        acc_trans_prnts.company_id=? and
        acc_trans_sales.deleted_at is null and 
        acc_trans_prnts.deleted_at is null and 
        acc_chart_ctrl_heads.control_name_id=30
        group by acc_trans_sales.buyer_id', [$master->buyer_id, $master->company_id]))
   ->first();

  $previousOutStanding = 0;
  if ($accounts) {
   $previousOutStanding = $accounts->amount;
  } else {
   $previousOutStanding = 0;
  }

  $master->receivable = $previousOutStanding;

  $data['master']    = $master;
  $data['details']    = $rows;

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

  $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetPrintHeader(true);
  $pdf->SetPrintFooter(true);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, '45', PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $header['logo'] = $data['master']->logo;
  $header['address'] = $data['master']->company_address;
  $header['title'] = 'Delivery Order for Garments Leftover';
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
  $pdf->SetFont('helvetica', 'N', 8);
  $pdf->SetTitle('Delivery Order for Garments Leftover');
  $view = \View::make('Defult.JhuteSale.GmtLeftoverSaleDlvOrderPdf', ['data' => $data]);

  $html_content = $view->render();
  $pdf->SetY(45);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/GmtLeftoverSaleDlvOrderPdf.pdf';
  $pdf->output($filename);
 }
}
