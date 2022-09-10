<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;

use App\Library\Sms;
use Illuminate\Support\Carbon;
use App\Library\Numbertowords;

class PoYarnDyeingShortApprovalController extends Controller
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
  InvYarnItemRepository $invyarnitem

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

  $this->middleware('auth');

  // $this->middleware('permission:approve.poyarndyeingshorts',   ['only' => ['approved', 'index','reportData','reportDataApp','unapproved']]);
 }

 public function index()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  // $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
  $supplier = array_prepend(array_pluck($this->supplier->where([['status_id', '=', 1]])->get(), 'name', 'id'), '-Select-', '');
  $menu = array_prepend(config('bprs.menu'), '-Select-', '');
  return Template::loadView('Approval.PoYarnDyeingShortApproval', ['company' => $company, 'supplier' => $supplier, 'menu' => $menu]);
 }

 public function reportData()
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
          po_yarn_dyeings.remarks,
          po_yarn_dyeings.approved_by,
          companies.code as company_code,
          suppliers.name as supplier_code,
          currencies.code as currency_code,
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
   ->when(request('company_id'), function ($q) {
    return $q->where('po_yarn_dyeings.company_id', '=', request('company_id', 0));
   })
   ->when(request('supplier_id'), function ($q) {
    return $q->where('po_yarn_dyeings.supplier_id', '=', request('supplier_id', 0));
   })
   ->when(request('date_from'), function ($q) {
    return $q->where('po_yarn_dyeings.po_date', '=', request('date_from', 0));
   })
   ->when(request('date_to'), function ($q) {
    return $q->where('po_yarn_dyeings.po_date', '=', request('date_to', 0));
   })
   ->whereNull('po_yarn_dyeings.approved_by')
   ->where([['po_yarn_dyeings.po_type_id', '=', 2]])
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
    'po_yarn_dyeings.remarks',
    'companies.code',
    'suppliers.name',
    'currencies.code',
    'po_yarn_dyeings.amount',
    'po_yarn_dyeings.approved_by'
   ])
   ->get()
   ->map(function ($rows) use ($source, $paymode) {
    $rows->source = isset($source[$rows->source_id]) ? $source[$rows->source_id] : '';
    $rows->paymode = isset($paymode[$rows->pay_mode]) ? $paymode[$rows->pay_mode] : '';
    $rows->item_qty = number_format($rows->item_qty, 2);
    $rows->amount = number_format($rows->amount, 2);
    $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
    $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
    return $rows;
   });
  echo json_encode($rows);
 }

 public function approved(Request $request)
 {
  $id = request('id', 0);
  $master = $this->poyarndyeing->find($id);
  $user = \Auth::user();
  $approved_at = date('Y-m-d h:i:s');
  $master->approved_by = $user->id;
  $master->approved_at = $approved_at;
  $master->unapproved_by = NULL;
  $master->unapproved_at = NULL;
  $master->timestamps = false;
  $poyarndyeing = $master->save();
  if ($poyarndyeing) {
   return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
  }
 }

 public function reportDataApp()
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
         po_yarn_dyeings.remarks,
         po_yarn_dyeings.approved_by,
         companies.code as company_code,
         suppliers.name as supplier_code,
         currencies.code as currency_code,
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
   ->when(request('company_id'), function ($q) {
    return $q->where('po_yarn_dyeings.company_id', '=', request('company_id', 0));
   })
   ->when(request('supplier_id'), function ($q) {
    return $q->where('po_yarn_dyeings.supplier_id', '=', request('supplier_id', 0));
   })
   ->when(request('date_from'), function ($q) {
    return $q->where('po_yarn_dyeings.po_date', '=', request('date_from', 0));
   })
   ->when(request('date_to'), function ($q) {
    return $q->where('po_yarn_dyeings.po_date', '=', request('date_to', 0));
   })
   ->whereNotNull('po_yarn_dyeings.approved_by')
   ->where([['po_yarn_dyeings.po_type_id', '=', 2]])
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
    'po_yarn_dyeings.remarks',
    'companies.code',
    'suppliers.name',
    'currencies.code',
    'po_yarn_dyeings.amount',
    'po_yarn_dyeings.approved_by'
   ])
   ->get()
   ->map(function ($rows) use ($source, $paymode) {
    $rows->source = isset($source[$rows->source_id]) ? $source[$rows->source_id] : '';
    $rows->paymode = isset($paymode[$rows->pay_mode]) ? $paymode[$rows->pay_mode] : '';
    $rows->item_qty = number_format($rows->item_qty, 2);
    $rows->amount = number_format($rows->amount, 2);
    $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
    $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
    return $rows;
   });
  echo json_encode($rows);
 }

 public function unapproved(Request $request)
 {
  $id = request('id', 0);
  $master = $this->poyarndyeing->find($id);
  $user = \Auth::user();
  $unapproved_at = date('Y-m-d h:i:s');
  $unapproved_count = $master->unapproved_count + 1;
  $master->approved_by = NUll;
  $master->approved_at = NUll;
  $master->unapproved_by = $user->id;
  $master->unapproved_at = $unapproved_at;
  $master->unapproved_count = $unapproved_count;
  $master->timestamps = false;
  $poyarndyeing = $master->save();

  if ($poyarndyeing) {
   return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
  }
 }
}
