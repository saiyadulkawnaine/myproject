<?php

namespace App\Http\Controllers\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CompanyRepository;

class DyeChemPurchaseLcWiseController extends Controller
{

 private $invrcv;
 private $itemaccount;
 private $supplier;
 private $company;

 public function __construct(
  InvRcvRepository $invrcv,
  ItemAccountRepository $itemaccount,
  SupplierRepository $supplier,
  CompanyRepository $company

 ) {
  $this->invrcv = $invrcv;
  $this->itemaccount = $itemaccount;
  $this->supplier = $supplier;
  $this->company = $company;
  $this->middleware('auth');
  //$this->middleware('permission:view.dyechempurchaselcwise',   ['only' => ['create', 'index','show']]);
 }
 public function index()
 {
  $supplier = array_prepend(array_pluck($this->supplier->garmentSubcontractors(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->where([['nature_id', '=', 1]])->get(), 'name', 'id'), '-Select-', '');


  return Template::loadView('Report.Inventory.DyeChemPurchaseLcWise', ['supplier' => $supplier, 'company' => $company]);
 }
 public function reportData()
 {

  $date_from = request('date_from', 0);
  $date_to = request('date_to', 0);

  $pi = collect(
   \DB::select("
            select
            imp_lcs.id as imp_lc_id,
            po_dye_chems.pi_no,
            po_dye_chems.pi_date
            from 
            imp_lcs
            join imp_lc_pos on imp_lc_pos.imp_lc_id=imp_lcs.id
            join po_dye_chems on imp_lc_pos.purchase_order_id=po_dye_chems.id
            where
            imp_lcs.lc_date>= ? and 
            imp_lcs.lc_date<= ? and
            imp_lcs.menu_id=7 and 
            po_dye_chems.deleted_at is null and 
            imp_lc_pos.deleted_at is null  
            group by 
            imp_lcs.id,
            po_dye_chems.pi_no,
            po_dye_chems.pi_date
            order by 
            imp_lcs.id
          ", [$date_from, $date_to])
  )
   ->map(function ($pi) {
    $pi->pi_no = $pi->pi_no . ", " . date('d-M-Y', strtotime($pi->pi_date));
    return $pi;
   });
  $piarr = [];
  foreach ($pi as $row) {
   $piarr[$row->imp_lc_id][] = $row->pi_no;
  }

  $buyer = collect(
   \DB::select("
          select
          imp_lcs.id as imp_lc_id,
          exp_lc_scs.buyer_id,
          buyers.name as buyer_name
          from 
          imp_lcs
          join imp_backed_exp_lc_scs on imp_backed_exp_lc_scs.imp_lc_id=imp_lcs.id
          join exp_lc_scs on imp_backed_exp_lc_scs.exp_lc_sc_id=exp_lc_scs.id
          join buyers on buyers.id=exp_lc_scs.buyer_id
          where
          imp_lcs.lc_date>= ? and 
          imp_lcs.lc_date<=  ? and
          imp_lcs.menu_id=7 and 
          exp_lc_scs.deleted_at is null and 
          imp_backed_exp_lc_scs.deleted_at is null  
          group by 
          imp_lcs.id,
          exp_lc_scs.buyer_id,
          buyers.name
          order by 
          imp_lcs.id
          ", [$date_from, $date_to])
  )->map(function ($buyer) {
   $buyer->buyer_name = $buyer->buyer_name;
   return $buyer;
  });
  $buyerarr = [];
  foreach ($buyer as $row) {
   $buyerarr[$row->imp_lc_id][] = $row->buyer_name;
  }

  $results = collect(
   \DB::select("
            select
            imp_lcs.id as imp_lc_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv,
            imp_lcs.lc_date,
            imp_lcs.supplier_id,
            companies.code as company_code,
            suppliers.name as supplier_name,
            currencies.code as currency_code,
            sum(po_dye_chem_items.qty) as lc_qty,
            sum(po_dye_chem_items.amount) as lc_amount,
            rcv.qty,
            rcv.amount,
            rcv_rtn.qty as rcv_rtn_qty,
            rcv_rtn.amount as rcv_rtn_amount,
            acctp.acceptance_value
            from 
            imp_lcs
            join imp_lc_pos on imp_lc_pos.imp_lc_id=imp_lcs.id
            join suppliers on suppliers.id=imp_lcs.supplier_id
            join companies on companies.id=imp_lcs.company_id
            join currencies on currencies.id=imp_lcs.currency_id
            join po_dye_chems on imp_lc_pos.purchase_order_id=po_dye_chems.id
            join po_dye_chem_items on po_dye_chem_items.po_dye_chem_id=po_dye_chems.id

            left join(
            select 
            imp_lcs.id as imp_lc_id, 
            sum(inv_dye_chem_rcv_items.qty) as qty,
            sum(inv_dye_chem_rcv_items.amount) as amount
            from 
            imp_lcs
            join imp_lc_pos on imp_lc_pos.imp_lc_id=imp_lcs.id
            join po_dye_chems on imp_lc_pos.purchase_order_id=po_dye_chems.id
            join po_dye_chem_items on po_dye_chem_items.po_dye_chem_id=po_dye_chems.id
            join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.po_dye_chem_item_id=po_dye_chem_items.id
            join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
            where
            imp_lcs.menu_id=7 and 
            imp_lcs.deleted_at is null and 
            imp_lc_pos.deleted_at is null and 
            po_dye_chems.deleted_at is null  and 
            po_dye_chem_items.deleted_at is null and 
            inv_dye_chem_rcv_items.deleted_at is null and 
            inv_dye_chem_rcvs.deleted_at is null and 
            inv_rcvs.deleted_at is null and
            inv_rcvs.receive_against_id=7 and
            inv_rcvs.receive_basis_id=1   
            group by
            imp_lcs.id
            ) rcv on rcv.imp_lc_id=imp_lcs.id

            left join(
              select 
              m.imp_lc_id,
              sum(m.qty) as qty,
              sum(m.amount) as amount
              from
              (
              select 
              imp_lcs.id as imp_lc_id,
              inv_dye_chem_rcv_items.id as inv_dye_chem_rcv_item_id,
              inv_dye_chem_rcv_items.rate,
              inv_dye_chem_rcv_items.qty,
              (inv_dye_chem_isu_items.qty*inv_dye_chem_rcv_items.rate) as  amount
              from inv_dye_chem_isu_items
              join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
              join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
              join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.id=inv_dye_chem_isu_items.INV_DYE_CHEM_RCV_ITEM_ID
              join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
              join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
              join po_dye_chem_items on po_dye_chem_items.id=inv_dye_chem_rcv_items.po_dye_chem_item_id
              join po_dye_chems on po_dye_chems.id=po_dye_chem_items.po_dye_chem_id
              join imp_lc_pos on imp_lc_pos.purchase_order_id=po_dye_chems.id
              join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
              where 
              imp_lcs.menu_id=7 and 
              imp_lcs.deleted_at is null and 
              imp_lc_pos.deleted_at is null and 
              po_dye_chems.deleted_at is null  and 
              po_dye_chem_items.deleted_at is null and 
              inv_dye_chem_rcv_items.deleted_at is null and 
              inv_dye_chem_rcvs.deleted_at is null and 
              inv_rcvs.deleted_at is null 

              and inv_isus.isu_basis_id  = 11
              and inv_dye_chem_transactions.deleted_at is null
              and inv_dye_chem_isu_items.deleted_at is null
              and inv_isus.deleted_at is null
              and inv_dye_chem_transactions.trans_type_id=2
              ) m 
              group by 
              m.imp_lc_id
            ) rcv_rtn on rcv_rtn.imp_lc_id=imp_lcs.id

            left join(
            select 
            imp_lcs.id as imp_lc_id, 
            sum(imp_acc_com_details.acceptance_value) as acceptance_value
            from 
            imp_lcs
            join imp_doc_accepts on imp_doc_accepts.imp_lc_id=imp_lcs.id
            join imp_acc_com_details on imp_acc_com_details.imp_doc_accept_id=imp_doc_accepts.id
            join imp_lc_pos on imp_lc_pos.id=imp_acc_com_details.imp_lc_po_id
            join po_dye_chems on imp_lc_pos.purchase_order_id=po_dye_chems.id

            
            where
            imp_lcs.menu_id=7 and 
            imp_lcs.deleted_at is null and 
            imp_doc_accepts.deleted_at is null and 
            imp_acc_com_details.deleted_at is null and 
            imp_lc_pos.deleted_at is null and 
            po_dye_chems.deleted_at is null   
            
            group by
            imp_lcs.id
            ) acctp on acctp.imp_lc_id=imp_lcs.id


            where
            imp_lcs.lc_date>= ? and 
            imp_lcs.lc_date<= ? and
            imp_lcs.menu_id=7 and 
            po_dye_chems.deleted_at is null and 
            imp_lc_pos.deleted_at is null and 
            po_dye_chem_items.deleted_at is null  
            group by 
            imp_lcs.id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv,
            imp_lcs.lc_date,
            imp_lcs.supplier_id,
            companies.code,
            suppliers.name,
            currencies.code,
            rcv.qty,
            rcv.amount,
            rcv_rtn.qty,
            rcv_rtn.amount,
            acctp.acceptance_value
            order by 
            imp_lcs.lc_date
            ", [$date_from, $date_to])
  )->map(function ($results) use ($piarr, $buyerarr) {
   $results->lc_no = $results->lc_no_i . " " . $results->lc_no_ii . " " . $results->lc_no_iii . " " . $results->lc_no_iv;
   $results->lc_date = date('d-M-Y', strtotime($results->lc_date));
   $results->lc_rate = 0;
   if ($results->lc_qty) {
    $results->lc_rate = $results->lc_amount / $results->lc_qty;
   }
   $results->rate = 0;
   if ($results->qty) {
    $results->rate = $results->amount / $results->qty;
   }
   $balance_qty = $results->lc_qty - $results->qty;
   $balance_amount = $balance_qty * $results->lc_rate;
   $results->balance_amount = number_format($balance_amount, 2);
   $results->balance_qty = number_format($balance_qty, 2);
   $results->balance_acpt = number_format($results->amount - $results->acceptance_value, 2);
   $results->lc_qty = number_format($results->lc_qty, 2);
   $results->lc_amount = number_format($results->lc_amount, 2);
   $results->qty = number_format($results->qty, 2);
   $results->amount = number_format($results->amount, 2);
   $results->acceptance_value = number_format($results->acceptance_value, 2);
   $results->lc_rate = number_format($results->lc_rate, 2);
   $results->rate = number_format($results->rate, 2);
   // $results->pi_no=implode(",",$piarr[$results->imp_lc_id]);
   // $results->buyer=implode(",",$buyerarr[$results->imp_lc_id]);
   $results->pi_no = isset($piarr[$results->imp_lc_id]) ? implode(",", $piarr[$results->imp_lc_id]) : '';
   $results->buyer = isset($buyerarr[$results->imp_lc_id]) ? implode(",", $buyerarr[$results->imp_lc_id]) : '';
   return $results;
  });

  echo json_encode($results);
 }

 public function getLcQtyDtl()
 {
  $imp_lc_id = request('imp_lc_id', 0);
  $results = collect(
   \DB::select("
            select
            po_dye_chem_items.id,
            po_dye_chem_items.inv_pur_req_item_id,
            inv_pur_req_items.item_account_id,
            itemcategories.name as itemcategory_name,
            itemclasses.name as itemclass_name,
            item_accounts.id,
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name,

            po_dye_chem_items.qty,
            po_dye_chem_items.rate,
            po_dye_chem_items.amount,
            po_dye_chem_items.remarks,
            imp_lcs.id as imp_lc_id
            from 
            imp_lcs
            join imp_lc_pos on imp_lc_pos.imp_lc_id=imp_lcs.id
            join po_dye_chems on imp_lc_pos.purchase_order_id=po_dye_chems.id
            join po_dye_chem_items on po_dye_chem_items.po_dye_chem_id=po_dye_chems.id
            join inv_pur_req_items on inv_pur_req_items.id = po_dye_chem_items.inv_pur_req_item_id
            join item_accounts on item_accounts.id = inv_pur_req_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            join itemclasses on itemclasses.id=item_accounts.itemclass_id

            where
            imp_lcs.id=? and 
            imp_lcs.deleted_at is null and 
            imp_lc_pos.deleted_at is null and 
            po_dye_chems.deleted_at is null  and 
            po_dye_chem_items.deleted_at is null  

            group by
            po_dye_chem_items.id,
            po_dye_chem_items.inv_pur_req_item_id,
            inv_pur_req_items.item_account_id,

            itemcategories.name,
            itemclasses.name,
            item_accounts.id,
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name, 

            po_dye_chem_items.qty,
            po_dye_chem_items.rate,
            po_dye_chem_items.amount,
            po_dye_chem_items.remarks,
            imp_lcs.id
            ", [$imp_lc_id])
  )->map(function ($results) {
   $results->item_desc = $results->item_description;
   $results->qty = number_format($results->qty, 2);
   $results->rate = number_format($results->rate, 2);
   $results->amount = number_format($results->amount, 2);
   return $results;
  });

  echo json_encode($results);
 }

 public function getRcvQtyDtl()
 {
  $imp_lc_id = request('imp_lc_id', 0);

  $results = collect(
   \DB::select("
        select 
            imp_lcs.id as imp_lc_id,
            inv_rcvs.id as inv_rcv_id,
            inv_rcvs.receive_no,
            inv_rcvs.receive_date,
            inv_dye_chem_rcv_items.id,
            inv_dye_chem_rcv_items.remarks,
            inv_dye_chem_rcv_items.item_account_id,
            itemcategories.name as itemcategory_name,
            itemclasses.name as itemclass_name,
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name,
            inv_dye_chem_rcv_items.qty,
            inv_dye_chem_rcv_items.rate,
            inv_dye_chem_rcv_items.amount,
            rcv_rtn.qty as rcv_rtn_qty,
            rcv_rtn.amount as rcv_rtn_amount
        from 
          imp_lcs
            join imp_lc_pos on imp_lc_pos.imp_lc_id=imp_lcs.id
            join po_dye_chems on imp_lc_pos.purchase_order_id=po_dye_chems.id
            join po_dye_chem_items on po_dye_chem_items.po_dye_chem_id=po_dye_chems.id
            join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.po_dye_chem_item_id=po_dye_chem_items.id
            join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
            join inv_pur_req_items on inv_pur_req_items.id=po_dye_chem_items.inv_pur_req_item_id
            join item_accounts on item_accounts.id=inv_pur_req_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            join itemclasses on itemclasses.id=item_accounts.itemclass_id
            left join(
              select 
              imp_lcs.id as imp_lc_id,
              inv_dye_chem_rcv_items.id as inv_dye_chem_rcv_item_id,
              inv_dye_chem_rcv_items.rate,
              inv_dye_chem_isu_items.qty,
              (inv_dye_chem_isu_items.qty*inv_dye_chem_rcv_items.rate) as  amount
              from inv_dye_chem_isu_items
              join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
              join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
              join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.id=inv_dye_chem_isu_items.inv_dye_chem_rcv_item_id
              join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
              join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
              join po_dye_chem_items on po_dye_chem_items.id=inv_dye_chem_rcv_items.po_dye_chem_item_id
              join po_dye_chems on po_dye_chems.id=po_dye_chem_items.po_dye_chem_id
              join imp_lc_pos on imp_lc_pos.purchase_order_id=po_dye_chems.id
              join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
              where 
              imp_lcs.menu_id=7 and 
              imp_lcs.deleted_at is null and 
              imp_lc_pos.deleted_at is null and 
              po_dye_chems.deleted_at is null  and 
              po_dye_chem_items.deleted_at is null and 
              inv_dye_chem_rcv_items.deleted_at is null and 
              inv_dye_chem_rcvs.deleted_at is null and 
              inv_rcvs.deleted_at is null 
      
              and inv_isus.isu_basis_id  = 11
              and inv_dye_chem_transactions.deleted_at is null
              and inv_dye_chem_isu_items.deleted_at is null
              and inv_isus.deleted_at is null
              and inv_dye_chem_transactions.trans_type_id=2
           ) rcv_rtn on rcv_rtn.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id and  rcv_rtn.imp_lc_id=imp_lcs.id
          where
            imp_lcs.id=? and 
            imp_lcs.deleted_at is null and 
            imp_lc_pos.deleted_at is null and 
            po_dye_chems.deleted_at is null  and 
            po_dye_chem_items.deleted_at is null and 
            inv_dye_chem_rcv_items.deleted_at is null and 
            inv_dye_chem_rcvs.deleted_at is null and 
            inv_rcvs.deleted_at is null and
            inv_rcvs.receive_against_id=7 and
            inv_rcvs.receive_basis_id=1   
          group by
            imp_lcs.id,
            inv_rcvs.id,
            inv_rcvs.receive_no,
            inv_rcvs.receive_date, 
            inv_dye_chem_rcv_items.id,
            inv_dye_chem_rcv_items.remarks,
            inv_dye_chem_rcv_items.item_account_id,
            itemcategories.name,
            itemclasses.name,
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name,
            inv_dye_chem_rcv_items.qty,
            inv_dye_chem_rcv_items.rate,
            inv_dye_chem_rcv_items.amount,
            rcv_rtn.qty,
            rcv_rtn.amount
          order by
             inv_rcvs.id
            ", [$imp_lc_id])
  )->map(function ($results) {
   $results->item_desc = $results->item_description;
   $results->net_qty = number_format($results->qty - $results->rcv_rtn_qty, 2);
   $results->net_amount = number_format($results->amount - $results->rcv_rtn_amount, 2);
   $results->qty = number_format($results->qty, 2);
   $results->rate = number_format($results->rate, 2);
   $results->amount = number_format($results->amount, 2);
   $results->receive_date = date('d-M-Y', strtotime($results->receive_date));
   $results->rcv_rtn_qty = number_format($results->rcv_rtn_qty, 0);
   $results->rcv_rtn_amount = number_format($results->rcv_rtn_amount, 0);
   return $results;
  });

  echo json_encode($results);
 }
}
