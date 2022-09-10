<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Sms;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\RqYarnRequest;

class RqYarnController extends Controller
{

 private $rqyarn;
 private $buyer;
 private $company;
 private $supplier;
 private $colorrange;
 private $gmtspart;
 private $autoyarn;
 private $itemaccount;
 private $user;

 public function __construct(
  RqYarnRepository $rqyarn,
  BuyerRepository $buyer,
  CompanyRepository $company,
  SupplierRepository $supplier,
  ColorrangeRepository $colorrange,
  GmtspartRepository $gmtspart,
  AutoyarnRepository $autoyarn,
  UserRepository $user,
  ItemAccountRepository $itemaccount
 ) {
  $this->rqyarn = $rqyarn;
  $this->buyer = $buyer;
  $this->company = $company;
  $this->supplier = $supplier;
  $this->colorrange = $colorrange;
  $this->gmtspart = $gmtspart;
  $this->autoyarn = $autoyarn;
  $this->user = $user;
  $this->itemaccount = $itemaccount;
  /*  
        $this->middleware('auth');
        $this->middleware('permission:view.rqyarns',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.rqyarns', ['only' => ['store']]);
        $this->middleware('permission:edit.rqyarns',   ['only' => ['update']]);
        $this->middleware('permission:delete.rqyarns', ['only' => ['destroy']]);

        */
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $menu = array_prepend(config('bprs.menu'), '-Select-', '');
  $yesno = array_prepend(config('bprs.yesno'), '--', '');

  $rqyarns = array();
  $rows = $this->rqyarn

   ->leftJoin('companies', function ($join) {
    $join->on('rq_yarns.company_id', '=', 'companies.id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('rq_yarns.supplier_id', '=', 'suppliers.id');
   })
   ->when(request('buyer'), function ($q) {
    return $q->where('rq_yarns.buyer', '=', request('buyer', 0));
   })
   ->orderBy('rq_yarns.id', 'desc')
   ->take(1000)
   ->get([
    'rq_yarns.*',
    'companies.name as company_name',
    'suppliers.name as supplier_name'
   ]);
  foreach ($rows as $row) {
   $rqyarn['id'] = $row->id;
   $rqyarn['company_name'] = $row->company_name;
   $rqyarn['rq_no'] = $row->rq_no;
   $rqyarn['basis_name'] = $menu[$row->rq_against_id];
   $rqyarn['ready_to_approve'] = $yesno[$row->ready_to_approve_id];
   $rqyarn['remarks'] = $row->remarks;
   $rqyarn['supplier_name'] = $row->supplier_name;
   $rqyarn['rq_date'] = date('Y-m-d', strtotime($row->rq_date));
   $rqyarn['approved_by'] = $row->approved_by;
   array_push($rqyarns, $rqyarn);
  }
  echo json_encode($rqyarns);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create(Request $request)
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  //$supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
  $supplier = array_prepend(array_pluck($this->supplier->knitSubcontractor(), 'name', 'id'), '-Select-', '');
  $menu = array_prepend(array_only(config('bprs.menu'), [4, 50]), '-Select-', '');
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
  return Template::LoadView('Subcontract.Kniting.RqYarn', ['company' => $company, 'menu' => $menu, 'supplier' => $supplier, 'yesno' => $yesno]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(RqYarnRequest $request)
 {
  $max = $this->rqyarn->where([['company_id', $request->company_id]])->max('rq_no');
  $rq_no = $max + 1;
  $rqyarn = $this->rqyarn->create(['rq_no' => $rq_no, 'company_id' => $request->company_id, 'rq_against_id' => $request->rq_against_id, 'supplier_id' => $request->supplier_id, 'rq_date' => $request->rq_date, 'remarks' => $request->remarks, 'rq_date' => $request->rq_date, 'remarks' => $request->remarks, 'ready_to_approve_id' => $request->ready_to_approve_id]);
  if ($rqyarn) {
   return response()->json(array('success' => true, 'id' =>  $rqyarn->id, 'message' => 'Save Successfully'), 200);
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
  $rqyarn = $this->rqyarn->find($id);
  $row['fromData'] = $rqyarn;
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
 public function update(RqYarnRequest $request, $id)
 {
  $master = $this->rqyarn->find($id);
  if ($master->approved_by && $master->approved_at) {
   return response()->json(array('success' => false, 'id' => $id, 'message' => 'It is Approved,So Update Not Possible'), 200);
  }
  $yarnqty = $this->rqyarn
   ->join('rq_yarn_fabrications', function ($join) {
    $join->on('rq_yarn_fabrications.rq_yarn_id', '=', 'rq_yarns.id');
   })
   ->join('rq_yarn_items', function ($join) {
    $join->on('rq_yarn_fabrications.id', '=', 'rq_yarn_items.rq_yarn_fabrication_id');
   })
   ->where([['rq_yarns.id', '=', $id]])
   ->get(['rq_yarn_items.qty'])
   ->first();
  //   dd($yarnqty);die;

  if ($request->ready_to_approve_id == 1 && $yarnqty == null) {
   return response()->json(array('success' => false, 'message' => 'Requisition is not ready to approve. Yarn Details not found'), 200);
  }

  $rqyarn = $this->rqyarn->update($id, $request->except(['id', 'rq_no', 'company_id', 'supplier_id']));

  if ($rqyarn) {
   if ($request->ready_to_approve_id == 1 && $request->approved_by == null) {
    $rows = $this->rqyarn
     ->join('companies', function ($join) {
      $join->on('companies.id', '=', 'rq_yarns.company_id');
     })
     ->join('suppliers', function ($join) {
      $join->on('suppliers.id', '=', 'rq_yarns.supplier_id');
     })
     ->leftJoin(\DB::raw("(
                    select
                    rq_yarns.id as rq_yarn_id,
                    sum(rq_yarn_items.qty) as yarn_qty
                    from
                    rq_yarns
                    join rq_yarn_fabrications on rq_yarn_fabrications.rq_yarn_id=rq_yarns.id
                    join rq_yarn_items on rq_yarn_items.rq_yarn_fabrication_id=rq_yarn_fabrications.id
                    where rq_yarn_items.deleted_at is null
                    and rq_yarn_fabrications.deleted_at is null
                    group by
                    rq_yarns.id
                ) rqyarnqty"), "rqyarnqty.rq_yarn_id", "=", "rq_yarns.id")
     ->where([['rq_yarns.id', '=', $id]])
     ->get([
      'rq_yarns.*',
      'companies.name as company_nane',
      'suppliers.name as supplier_name',
      'rqyarnqty.yarn_qty',
     ])
     ->first();

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
     ->where([['permissions.id', '=', 2730]])
     ->get([
      'permissions.id',
      'employee_h_rs.contact'
     ]);

    $approvalArr = [];
    foreach ($approveuser as $data) {
     $approvalArr[2730][] = '88' . $data->contact; //rqyarnapproval permission ID=2730
    }

    $approvalusercontact = implode(',', $approvalArr[2730]);

    $title = 'Yarn Issue Requisition Approval Request';
    $text =
     $title . "\n" .
     'Requision No:' . $rows->rq_no . "\n" .
     'Yarn Qty:' . $rows->yarn_qty . "\n" .
     'Knit Company:' . $rows->supplier_name . "\n" .
     'Remarks:' . $rows->remarks;
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
  if ($this->rqyarn->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getPdf()
 {
  $id = request('id', 0);
  $invissuebasis = array_prepend(config('bprs.invissuebasis'), '-Select-', '');
  $menu = array_prepend(config('bprs.menu'), '-Select-', '');

  $rows = $this->rqyarn
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'rq_yarns.company_id');
   })

   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'rq_yarns.supplier_id');
   })
   ->join('users', function ($join) {
    $join->on('users.id', '=', 'rq_yarns.created_by');
   })
   ->leftJoin('employee_h_rs', function ($join) {
    $join->on('users.id', '=', 'employee_h_rs.user_id');
   })
   ->where([['rq_yarns.id', '=', $id]])
   ->get([
    'rq_yarns.*',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'suppliers.name as supplier_name',
    'suppliers.address as supplier_address',
    'users.name as user_name',
    'employee_h_rs.contact'
   ])
   ->first();

  $rows->rq_against_name = $menu[$rows->rq_against_id];
  $rows->rq_date = date('d-M-Y', strtotime($rows->rq_date));
  $rows->contact_detail = $rows->contact_person . ',' . $rows->designation . ',' . $rows->email;
  $rows->po_pl_no = '';
  if ($rows->rq_against_id == 4) {
   $rows->po_pl_no = 'WO No';
  }
  if ($rows->rq_against_id == 50) {
   $rows->po_pl_no = 'PL No';
  }


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


  $invyarnisuitem = $this->rqyarn
   ->leftJoin('rq_yarn_fabrications', function ($join) {
    $join->on('rq_yarn_fabrications.rq_yarn_id', '=', 'rq_yarns.id');
   })
   ->leftJoin('rq_yarn_items', function ($join) {
    $join->on('rq_yarn_items.rq_yarn_fabrication_id', '=', 'rq_yarn_fabrications.id');
   })
   ->join('inv_yarn_items', function ($join) {
    $join->on('inv_yarn_items.id', '=', 'rq_yarn_items.inv_yarn_item_id');
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
   ->leftJoin(\DB::raw("(
          select pl_knits.pl_no,
          pl_knit_items.id,
          sales_orders.sale_order_no,
          styles.style_ref,
          buyers.name as buyer_name,
          style_fabrications.autoyarn_id,
          budget_fabrics.gsm_weight
            from 
            pl_knit_items
            join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
            join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
            join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
            join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id
            join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
            join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
            join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            ) pl_sales_orders"), "pl_sales_orders.id", "=", "rq_yarn_fabrications.pl_knit_item_id")
   ->leftJoin(\DB::raw("(
              select po_knit_services.po_no,
              po_knit_service_item_qties.id,
              sales_orders.sale_order_no as sale_order_no_po,
              styles.style_ref as style_ref_po,
              buyers.name  as buyer_name_po,
              style_fabrications.autoyarn_id as autoyarn_id_po,
              budget_fabrics.gsm_weight as gsm_weight_po
            from po_knit_service_item_qties
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id
            join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
            join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id

            join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
            join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            ) po_sales_orders"), "po_sales_orders.id", "=", "rq_yarn_fabrications.po_knit_service_item_qty_id")
   ->where([['rq_yarns.id', '=', $id]])
   ->orderBy('rq_yarn_items.id', 'desc')
   ->get([
    'rq_yarn_items.*',
    'rq_yarns.rq_against_id',
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
    'pl_sales_orders.pl_no',
    'pl_sales_orders.sale_order_no',
    'pl_sales_orders.style_ref',
    'pl_sales_orders.buyer_name',
    'pl_sales_orders.autoyarn_id',
    'pl_sales_orders.gsm_weight',
    'po_sales_orders.po_no',
    'po_sales_orders.sale_order_no_po',
    'po_sales_orders.style_ref_po',
    'po_sales_orders.buyer_name_po',
    'po_sales_orders.autoyarn_id_po',
    'po_sales_orders.gsm_weight_po',
    'suppliers.name as supplier_name',
   ])
   ->map(function ($invyarnisuitem) use ($yarnDropdown, $desDropdown) {
    $invyarnisuitem->store_qty = $invyarnisuitem->qty;
    $invyarnisuitem->yarn_count = $invyarnisuitem->count . "/" . $invyarnisuitem->symbol;
    $invyarnisuitem->composition = isset($yarnDropdown[$invyarnisuitem->item_account_id]) ? $yarnDropdown[$invyarnisuitem->item_account_id] : '';
    $invyarnisuitem->sale_order_no = $invyarnisuitem->sale_order_no ? $invyarnisuitem->sale_order_no : $invyarnisuitem->sale_order_no_po;
    $invyarnisuitem->style_ref = $invyarnisuitem->style_ref ? $invyarnisuitem->style_ref : $invyarnisuitem->style_ref_po;
    $invyarnisuitem->buyer_name = $invyarnisuitem->buyer_name ? $invyarnisuitem->buyer_name : $invyarnisuitem->buyer_name_po;

    $invyarnisuitem->gsm_weight = $invyarnisuitem->gsm_weight ? $invyarnisuitem->gsm_weight : $invyarnisuitem->gsm_weight_po;

    if ($invyarnisuitem->autoyarn_id || $invyarnisuitem->autoyarn_id_po) {
     $invyarnisuitem->fabrication = $invyarnisuitem->autoyarn_id ? $desDropdown[$invyarnisuitem->autoyarn_id] : $desDropdown[$invyarnisuitem->autoyarn_id_po];
    } else {
     $invyarnisuitem->fabrication = null;
    }

    if ($invyarnisuitem->rq_against_id == 4) {
     $invyarnisuitem->pl_po_no = $invyarnisuitem->po_no;
    }
    if ($invyarnisuitem->rq_against_id == 50) {
     $invyarnisuitem->pl_po_no = $invyarnisuitem->pl_no;
    }


    return $invyarnisuitem;
   });

  $data['master']    = $rows;
  $data['details']   = $invyarnisuitem;

  $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $pdf->SetY(10);
  //$txt = "Trim Purchase Order";
  //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
  $image_file = 'images/logo/' . $rows->logo;
  $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
  $pdf->SetY(13);
  $pdf->SetFont('helvetica', 'N', 8);
  //$pdf->Text(115, 12, $rows->company_address);
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
  $pdf->SetY(3);
  $pdf->SetX(190);
  $challan = str_pad($data['master']->id, 10, 0, STR_PAD_LEFT);
  $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

  $pdf->SetY(16);
  $pdf->SetFont('helvetica', 'N', 10);
  $pdf->Write(0, 'Yarn Requisition Report ', '', 0, 'C', true, 0, false, false, 0);
  $pdf->SetFont('helvetica', '', 8);
  $pdf->SetTitle('Yarn Requisition Report');
  $view = \View::make('Defult.Subcontract.Kniting.RqYarnPdf', ['data' => $data]);
  $html_content = $view->render();
  $pdf->SetY(35);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/RqYarnPdf.pdf';
  $pdf->output($filename);
 }

 public function getSearchRqYarn()
 {
  $menu = array_prepend(config('bprs.menu'), '-Select-', '');
  $yesno = array_prepend(config('bprs.yesno'), '--', '');

  $rqyarns = array();
  $rows = $this->rqyarn

   ->leftJoin('companies', function ($join) {
    $join->on('rq_yarns.company_id', '=', 'companies.id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('rq_yarns.supplier_id', '=', 'suppliers.id');
   })
   ->when(request('from_date'), function ($q) {
    return $q->where('rq_yarns.rq_date', '>=', request('from_date'));
   })
   ->when(request('to_date'), function ($q) {
    return $q->where('rq_yarns.rq_date', '<=', request('to_date'));
   })
   ->orderBy('rq_yarns.id', 'desc')
   ->get([
    'rq_yarns.*',
    'companies.name as company_name',
    'suppliers.name as supplier_name'
   ]);
  foreach ($rows as $row) {
   $rqyarn['id'] = $row->id;
   $rqyarn['company_name'] = $row->company_name;
   $rqyarn['rq_no'] = $row->rq_no;
   $rqyarn['basis_name'] = $menu[$row->rq_against_id];
   $rqyarn['ready_to_approve'] = $yesno[$row->ready_to_approve_id];
   $rqyarn['remarks'] = $row->remarks;
   $rqyarn['supplier_name'] = $row->supplier_name;
   $rqyarn['rq_date'] = date('Y-m-d', strtotime($row->rq_date));
   $rqyarn['approved_by'] = $row->approved_by;
   array_push($rqyarns, $rqyarn);
  }
  echo json_encode($rqyarns);
 }
}
