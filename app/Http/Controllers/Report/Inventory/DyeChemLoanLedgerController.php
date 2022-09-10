<?php

namespace App\Http\Controllers\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemRcvRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;

class DyeChemLoanLedgerController extends Controller
{
 private $invisu;
 private $invrcv;
 private $company;
 private $invdyechemrcv;
 private $supplier;
 private $itemaccount;
 private $store;
 private $itemcategory;

 public function __construct(
  InvRcvRepository $invrcv,
  InvDyeChemRcvRepository $invdyechemrcv,
  SupplierRepository $supplier,
  ItemAccountRepository $itemaccount,
  StoreRepository $store,
  CompanyRepository $company,
  ItemcategoryRepository $itemcategory,
  InvIsuRepository $invisu
 ) {
  $this->invrcv = $invrcv;
  $this->company = $company;
  $this->invisu = $invisu;
  $this->invdyechemrcv = $invdyechemrcv;
  $this->supplier = $supplier;
  $this->itemaccount = $itemaccount;
  $this->store = $store;
  $this->itemcategory = $itemcategory;

  $this->middleware('auth');

  //$this->middleware('permission:view.dyechemloanledger',   ['only' => ['create', 'index','show']]);
 }

 public function index()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $store = array_prepend(array_pluck($this->store->get(), 'name', 'id'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');

  return Template::loadView('Report.Inventory.DyeChemLoanLedger', ['company' => $company, 'store' => $store, 'supplier' => $supplier]);
 }

 public function reportData()
 {
  $company_id = request('company_id', 0);
  $supplier_id = request('supplier_id', 0);
  $date_from = request('date_from', 0);
  $date_to = request('date_to', 0);
  $start_date = date('Y-m-d', strtotime($date_from));
  $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
  $store_id = request('store_id', 0);
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');

  $companyRcv = '';
  if ($company_id) {
   $companyRcv = ' and inv_rcvs.company_id= ' . $company_id;
  } else {
   $companyRcv = '';
  }

  $companyIsu = '';
  if ($company_id) {
   $companyIsu = ' and inv_isus.company_id= ' . $company_id;
  } else {
   $companyIsu = '';
  }

  $storeCond = '';
  if ($store_id) {
   $storeCond = ' and inv_dye_chem_transactions.store_id= ' . $store_id;
  } else {
   $storeCond = '';
  }

  $supplierRcv = '';
  if ($supplier_id) {
   $supplierRcv = ' and inv_rcvs.supplier_id= ' . $supplier_id;
  } else {
   $supplierRcv = '';
  }

  $supplierIsu = '';
  if ($supplier_id) {
   $supplierIsu = ' and inv_dye_chem_isu_rqs.supplier_id= ' . $supplier_id;
  } else {
   $supplierIsu = '';
  }

  $rows = collect(
   \DB::select("
        select
        m.id,
        m.trans_no,
        m.trans_date,
        m.supplier_id,
        m.trans_item_id,
        m.item_account_id,
        m.trans_type_id,
        m.item_description,
        m.sub_class_name,
        m.specification,
        m.uom_code,
        m.qty,
        m.amount,
        m.isu_qty,
        m.isu_amount
        from
        (
        select
        0 as id ,
        0 as trans_no,
        TO_DATE('" . $yesterday . "', 'YYYY/MM/DD') as trans_date,
        m.supplier_id,
        0 as trans_item_id,
        0 as item_account_id,
        0 as trans_type_id,
        null as item_description,
        null as sub_class_name,
        null as specification,
        null as uom_code,
        rcvs.qty,
        rcvs.amount,
        isus.qty as isu_qty,
        isus.amount as isu_amount
        from  
        (
        select
        supplier_id
        from
        inv_rcvs
        where
        inv_rcvs.receive_basis_id=10
        $companyRcv
        $supplierRcv
        union

        select
        inv_dye_chem_isu_rqs.supplier_id
        from
        inv_isus
        join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_isu_id=inv_isus.id
        join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id=inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
        join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.id=inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id
        where
        inv_isus.isu_against_id=211 and
        inv_dye_chem_isu_rqs.rq_basis_id=5
        $companyIsu
        $supplierIsu
        ) m

        left join (
          select
          inv_rcvs.supplier_id,
          abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
          sum(inv_dye_chem_transactions.store_amount) as amount
          from
          inv_rcvs
          join inv_dye_chem_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
          join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
          join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
          where
          inv_rcvs.receive_basis_id=10
          and inv_dye_chem_transactions.trans_type_id=1
          and inv_rcvs.receive_date < TO_DATE('" . $date_from . "', 'YYYY/MM/DD')
          $companyRcv
          $supplierRcv 
          $storeCond
          group by
          inv_rcvs.supplier_id
        ) rcvs on rcvs.supplier_id=m.supplier_id
        
        left join (
          select
          inv_dye_chem_isu_rqs.supplier_id,
          abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
          sum(inv_dye_chem_transactions.store_amount) as amount
          from
          inv_isus
          join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_isu_id=inv_isus.id
          join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id=inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
          join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.id=inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id
          join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
          where
          inv_isus.isu_against_id=211 and
          inv_dye_chem_isu_rqs.rq_basis_id=5 and
          inv_dye_chem_transactions.trans_type_id=2
          and inv_isus.issue_date < TO_DATE('" . $date_from . "', 'YYYY/MM/DD')
          $companyIsu
          $supplierIsu
          $storeCond
          group by
          inv_dye_chem_isu_rqs.supplier_id
        ) isus on isus.supplier_id=m.supplier_id
        
        union
        
        select
        inv_rcvs.id,
        inv_rcvs.receive_no as trans_no,
        inv_rcvs.receive_date as trans_date,
        inv_rcvs.supplier_id,
        inv_dye_chem_rcv_items.id as trans_item_id,
        inv_dye_chem_rcv_items.item_account_id,
        inv_dye_chem_transactions.trans_type_id,
        item_accounts.item_description,
        item_accounts.sub_class_name,
        item_accounts.specification,
        uoms.code as uom_code,
        abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
        sum(inv_dye_chem_transactions.store_amount) as amount,
        0 as isu_qty,
        0 as isu_amount
        from
        inv_rcvs
        join inv_dye_chem_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
        join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
        join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
        join item_accounts on inv_dye_chem_rcv_items.item_account_id = item_accounts.id
        join uoms on item_accounts.uom_id = uoms.id 
        where
        inv_rcvs.receive_basis_id=10
        and inv_dye_chem_transactions.trans_type_id=1
        and inv_rcvs.receive_date >= TO_DATE('" . $date_from . "', 'YYYY/MM/DD')
        and inv_rcvs.receive_date <= TO_DATE('" . $date_to . "', 'YYYY/MM/DD')
        $companyRcv
        $supplierRcv
        $storeCond
        group by
        inv_rcvs.supplier_id,
        inv_rcvs.id,
        inv_dye_chem_rcvs.id,
        inv_rcvs.receive_no,
        inv_rcvs.receive_date,
        inv_dye_chem_rcv_items.id,
        inv_dye_chem_rcv_items.item_account_id,
        inv_dye_chem_transactions.trans_type_id,
        item_accounts.item_description,
        item_accounts.sub_class_name,
        item_accounts.specification,
        uoms.code

        union

        select
        inv_isus.id,
        inv_isus.issue_no as trans_no,
        inv_isus.issue_date as trans_date,
        inv_dye_chem_isu_rqs.supplier_id,
        inv_dye_chem_isu_items.id as trans_item_id,
        inv_dye_chem_isu_items.item_account_id,
        inv_dye_chem_transactions.trans_type_id,
        item_accounts.item_description,
        item_accounts.sub_class_name,
        item_accounts.specification,
        uoms.code as uom_code,
        abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
        sum(inv_dye_chem_transactions.store_amount) as amount,
        0 as isu_qty,
        0 as isu_amount
        from
        inv_isus
        join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_isu_id=inv_isus.id
        join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id=inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
        join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.id=inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id
        join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
        join item_accounts on inv_dye_chem_isu_items.item_account_id = item_accounts.id
        join uoms on item_accounts.uom_id = uoms.id 
        where
        inv_isus.isu_against_id=211 and
        inv_dye_chem_isu_rqs.rq_basis_id=5 and
        inv_dye_chem_transactions.trans_type_id=2
        and inv_isus.issue_date >= TO_DATE('" . $date_from . "', 'YYYY/MM/DD')
        and inv_isus.issue_date <= TO_DATE('" . $date_to . "', 'YYYY/MM/DD')
        $companyIsu
        $supplierIsu
        $storeCond
        group by
        inv_isus.id,
        inv_isus.issue_no,
        inv_isus.issue_date,
        inv_dye_chem_isu_rqs.supplier_id,
        inv_dye_chem_isu_items.id,
        inv_dye_chem_isu_items.item_account_id,
        inv_dye_chem_transactions.trans_type_id,
        item_accounts.item_description,
        item_accounts.sub_class_name,
        item_accounts.specification,
        uoms.code
        ) m order by m.supplier_id,m.trans_date ,m.trans_type_id      
      
        ")
  )
   ->map(function ($rows) use ($supplier) {
    $rows->trans_date = date('d-M-Y', strtotime($rows->trans_date));
    $rows->supplier_name = $supplier[$rows->supplier_id];
    $rows->open_balance_qty = 0;
    $rows->open_balance_amount = 0;
    if ($rows->trans_type_id == 0) {
     $rows->open_balance_qty = $rows->qty - $rows->isu_qty;
     $rows->open_balance_amount = $rows->amount - $rows->isu_amount;
    }
    $rows->rcv_qty = 0;
    $rows->rcv_amount = 0;
    if ($rows->trans_type_id == 1) {
     $rows->rcv_qty = $rows->qty;
     $rows->rcv_amount = $rows->amount;
    }
    $rows->isu_qty = 0;
    $rows->isu_amount = 0;
    if ($rows->trans_type_id == 2) {
     $rows->isu_qty = $rows->qty;
     $rows->isu_amount = $rows->amount;
    }
    return $rows;
   });
  $data = $rows->groupBy(['supplier_id']);
  return Template::loadView('Report.Inventory.DyeChemLoanLedgerMatrix', ['data' => $data, 'supplier' => $supplier]);
 }



 public function ledgerPdf()
 {
  $company_id = request('company_id', 0);
  $supplier_id = request('supplier_id', 0);
  $date_from = request('date_from', 0);
  $date_to = request('date_to', 0);
  $start_date = date('Y-m-d', strtotime($date_from));
  $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
  $store_id = request('store_id', 0);
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '-Select-', '');

  $companyRcv = '';
  if ($company_id) {
   $companyRcv = ' and inv_rcvs.company_id= ' . $company_id;
  } else {
   $companyRcv = '';
  }

  $companyIsu = '';
  if ($company_id) {
   $companyIsu = ' and inv_isus.company_id= ' . $company_id;
  } else {
   $companyIsu = '';
  }

  $storeCond = '';
  if ($store_id) {
   $storeCond = ' and inv_dye_chem_transactions.store_id= ' . $store_id;
  } else {
   $storeCond = '';
  }

  $supplierRcv = '';
  if ($supplier_id) {
   $supplierRcv = ' and inv_rcvs.supplier_id= ' . $supplier_id;
  } else {
   $supplierRcv = '';
  }

  $supplierIsu = '';
  if ($supplier_id) {
   $supplierIsu = ' and inv_dye_chem_isu_rqs.supplier_id= ' . $supplier_id;
  } else {
   $supplierIsu = '';
  }

  $rows = collect(
   \DB::select("
        select
        m.id,
        m.trans_no,
        m.trans_date,
        m.supplier_id,
        m.trans_item_id,
        m.item_account_id,
        m.trans_type_id,
        m.item_description,
        m.sub_class_name,
        m.specification,
        m.uom_code,
        m.qty,
        m.amount,
        m.isu_qty,
        m.isu_amount
        from
        (
        select
        0 as id ,
        0 as trans_no,
        TO_DATE('" . $yesterday . "', 'YYYY/MM/DD') as trans_date,
        m.supplier_id,
        0 as trans_item_id,
        0 as item_account_id,
        0 as trans_type_id,
        null as item_description,
        null as sub_class_name,
        null as specification,
        null as uom_code,
        rcvs.qty,
        rcvs.amount,
        isus.qty as isu_qty,
        isus.amount as isu_amount
        from  
        (
        select
        supplier_id
        from
        inv_rcvs
        where
        inv_rcvs.receive_basis_id=10
        $companyRcv
        $supplierRcv
        union

        select
        inv_dye_chem_isu_rqs.supplier_id
        from
        inv_isus
        join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_isu_id=inv_isus.id
        join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id=inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
        join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.id=inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id
        where
        inv_isus.isu_against_id=211 and
        inv_dye_chem_isu_rqs.rq_basis_id=5
        $companyIsu
        $supplierIsu
        ) m

        left join (
          select
          inv_rcvs.supplier_id,
          abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
          sum(inv_dye_chem_transactions.store_amount) as amount
          from
          inv_rcvs
          join inv_dye_chem_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
          join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
          join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
          where
          inv_rcvs.receive_basis_id=10
          and inv_dye_chem_transactions.trans_type_id=1
          and inv_rcvs.receive_date < TO_DATE('" . $date_from . "', 'YYYY/MM/DD')
          $companyRcv
          $supplierRcv 
          $storeCond
          group by
          inv_rcvs.supplier_id
        ) rcvs on rcvs.supplier_id=m.supplier_id
        
        left join (
          select
          inv_dye_chem_isu_rqs.supplier_id,
          abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
          sum(inv_dye_chem_transactions.store_amount) as amount
          from
          inv_isus
          join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_isu_id=inv_isus.id
          join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id=inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
          join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.id=inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id
          join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
          where
          inv_isus.isu_against_id=211 and
          inv_dye_chem_isu_rqs.rq_basis_id=5 and
          inv_dye_chem_transactions.trans_type_id=2
          and inv_isus.issue_date < TO_DATE('" . $date_from . "', 'YYYY/MM/DD')
          $companyIsu
          $supplierIsu
          $storeCond
          group by
          inv_dye_chem_isu_rqs.supplier_id
        ) isus on isus.supplier_id=m.supplier_id
        
        union
        
        select
        inv_rcvs.id,
        inv_rcvs.receive_no as trans_no,
        inv_rcvs.receive_date as trans_date,
        inv_rcvs.supplier_id,
        inv_dye_chem_rcv_items.id as trans_item_id,
        inv_dye_chem_rcv_items.item_account_id,
        inv_dye_chem_transactions.trans_type_id,
        item_accounts.item_description,
        item_accounts.sub_class_name,
        item_accounts.specification,
        uoms.code as uom_code,
        abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
        sum(inv_dye_chem_transactions.store_amount) as amount,
        0 as isu_qty,
        0 as isu_amount
        from
        inv_rcvs
        join inv_dye_chem_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
        join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
        join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
        join item_accounts on inv_dye_chem_rcv_items.item_account_id = item_accounts.id
        join uoms on item_accounts.uom_id = uoms.id 
        where
        inv_rcvs.receive_basis_id=10
        and inv_dye_chem_transactions.trans_type_id=1
        and inv_rcvs.receive_date >= TO_DATE('" . $date_from . "', 'YYYY/MM/DD')
        and inv_rcvs.receive_date <= TO_DATE('" . $date_to . "', 'YYYY/MM/DD')
        $companyRcv
        $supplierRcv
        $storeCond
        group by
        inv_rcvs.supplier_id,
        inv_rcvs.id,
        inv_dye_chem_rcvs.id,
        inv_rcvs.receive_no,
        inv_rcvs.receive_date,
        inv_dye_chem_rcv_items.id,
        inv_dye_chem_rcv_items.item_account_id,
        inv_dye_chem_transactions.trans_type_id,
        item_accounts.item_description,
        item_accounts.sub_class_name,
        item_accounts.specification,
        uoms.code

        union

        select
        inv_isus.id,
        inv_isus.issue_no as trans_no,
        inv_isus.issue_date as trans_date,
        inv_dye_chem_isu_rqs.supplier_id,
        inv_dye_chem_isu_items.id as trans_item_id,
        inv_dye_chem_isu_items.item_account_id,
        inv_dye_chem_transactions.trans_type_id,
        item_accounts.item_description,
        item_accounts.sub_class_name,
        item_accounts.specification,
        uoms.code as uom_code,
        abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
        sum(inv_dye_chem_transactions.store_amount) as amount,
        0 as isu_qty,
        0 as isu_amount
        from
        inv_isus
        join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_isu_id=inv_isus.id
        join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id=inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
        join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.id=inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id
        join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
        join item_accounts on inv_dye_chem_isu_items.item_account_id = item_accounts.id
        join uoms on item_accounts.uom_id = uoms.id 
        where
        inv_isus.isu_against_id=211 and
        inv_dye_chem_isu_rqs.rq_basis_id=5 and
        inv_dye_chem_transactions.trans_type_id=2
        and inv_isus.issue_date >= TO_DATE('" . $date_from . "', 'YYYY/MM/DD')
        and inv_isus.issue_date <= TO_DATE('" . $date_to . "', 'YYYY/MM/DD')
        $companyIsu
        $supplierIsu
        $storeCond
        group by
        inv_isus.id,
        inv_isus.issue_no,
        inv_isus.issue_date,
        inv_dye_chem_isu_rqs.supplier_id,
        inv_dye_chem_isu_items.id,
        inv_dye_chem_isu_items.item_account_id,
        inv_dye_chem_transactions.trans_type_id,
        item_accounts.item_description,
        item_accounts.sub_class_name,
        item_accounts.specification,
        uoms.code
        ) m order by m.supplier_id,m.trans_date ,m.trans_type_id      
      
        ")
  )
   ->map(function ($rows) use ($supplier) {
    $rows->trans_date = date('d-M-Y', strtotime($rows->trans_date));
    $rows->supplier_name = $supplier[$rows->supplier_id];
    $rows->open_balance_qty = 0;
    $rows->open_balance_amount = 0;
    if ($rows->trans_type_id == 0) {
     $rows->open_balance_qty = $rows->qty - $rows->isu_qty;
     $rows->open_balance_amount = $rows->amount - $rows->isu_amount;
    }
    $rows->rcv_qty = 0;
    $rows->rcv_amount = 0;
    if ($rows->trans_type_id == 1) {
     $rows->rcv_qty = $rows->qty;
     $rows->rcv_amount = $rows->amount;
    }
    $rows->isu_qty = 0;
    $rows->isu_amount = 0;
    if ($rows->trans_type_id == 2) {
     $rows->isu_qty = $rows->qty;
     $rows->isu_amount = $rows->amount;
    }
    return $rows;
   });
  $data = $rows->groupBy(['supplier_id']);


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


  //$txt = "Dyes & Chemical Loan Ledger";
  //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
  //$pdf->SetY(5);
  //$pdf->Text(100, 5, $txt);
  //$pdf->SetY(10);
  $pdf->SetFont('helvetica', 'N', 10);



  $view = \View::make('Defult.Report.Inventory.DyeChemLoanLedgerPdf', ['data' => $data, 'supplier' => $supplier, 'date_from' => $date_from, 'date_to' => $date_to]);
  $html_content = $view->render();
  $pdf->SetY(15);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/DyeChemLoanLedgerPdf.pdf';
  //echo $html_content;
  //$pdf->output($filename);
  $pdf->output($filename, 'I');
  exit();
 }
}
