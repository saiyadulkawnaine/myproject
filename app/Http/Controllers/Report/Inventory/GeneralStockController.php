<?php
namespace App\Http\Controllers\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;

class GeneralStockController extends Controller
{

  private $supplier;
  private $itemaccount;
  private $store;
  private $company;
  private $itemcategory;

  public function __construct(
    SupplierRepository $supplier,
    ItemAccountRepository $itemaccount,
    StoreRepository $store,
    CompanyRepository $company,
    ItemcategoryRepository $itemcategory
  )
  {
    $this->supplier = $supplier;
    $this->itemaccount=$itemaccount;
    $this->store=$store;
    $this->company=$company;
    $this->itemcategory=$itemcategory;
    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {
    $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');
    $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
    $company = array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
    $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-','');
    $consumptionlevel=array_prepend(config('bprs.consumptionlevel'),'-Select-','');
    return Template::loadView('Report.Inventory.GeneralStock',['supplier'=>$supplier,'store'=>$store,'company'=>$company,'itemcategory'=>$itemcategory,'consumptionlevel'=>$consumptionlevel]);
  }
	public function reportData() {
      $company_id=request('company_id',0);
      $store_id=request('store_id',0);
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $start_date=date('Y-m-d', strtotime($date_from));
      $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
      $companyCond='';
      if($company_id){
       $companyCond= ' and inv_general_transactions.company_id= '.$company_id;
      }
      else{
       $companyCond= '';
      }

      $storeCond='';
      if($store_id){
       $storeCond= ' and inv_general_transactions.store_id= '.$store_id;
      }
      else{
       $storeCond= '';
      }
      



      $invgeneralrcvitem=$this->itemaccount
      ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
      })
      ->join(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date <='".$date_to."'
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id
      ) all_rcv"), "all_rcv.item_account_id", "=", "item_accounts.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date <'".$date_from."'
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id
      ) open_general_rcv"), "open_general_rcv.item_account_id", "=", "all_rcv.item_account_id")
      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id in (1,2,3)
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) pur_rcv"), "pur_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 9
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) trans_in_rcv"), "trans_in_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 4
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) isu_rtn_rcv"), "isu_rtn_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) general_rcv"), "general_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date<'".$date_from."'
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id

      ) open_general_isu"), "open_general_isu.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      abs(sum(inv_general_transactions.store_qty)) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id in (1,2)
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) regular_isu"), "regular_isu.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      abs(sum(inv_general_transactions.store_qty)) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 9
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) trans_out_isu"), "trans_out_isu.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      abs(sum(inv_general_transactions.store_qty)) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 11
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) rcv_rtn_isu"), "rcv_rtn_isu.item_account_id", "=", "all_rcv.item_account_id")
      
      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) general_isu"), "general_isu.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      max(inv_rcvs.receive_date) as receive_date
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where 
      inv_rcvs.receive_basis_id in (1,2,3)
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) max_rcv_dt"), "max_rcv_dt.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      inv_rcvs.receive_date as receive_date,
      sum(inv_general_transactions.store_qty) as qty
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where  inv_rcvs.receive_basis_id in (1,2,3)
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by 
      inv_general_rcv_items.item_account_id,
      inv_rcvs.receive_date

      ) max_rcv_qty"), [["max_rcv_qty.receive_date", "=", "max_rcv_dt.receive_date"],["max_rcv_qty.item_account_id", "=", "all_rcv.item_account_id"]])


      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      max(inv_isus.issue_date) as issue_date
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where  inv_isus.isu_basis_id in (1,2)
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) max_isu_dt"), "max_isu_dt.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      inv_isus.issue_date as issue_date,
      abs(sum(inv_general_transactions.store_qty)) as qty
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where 
      inv_isus.isu_basis_id in (1,2)
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id,inv_isus.issue_date
      ) max_isu_qty"), [["max_isu_qty.issue_date", "=", "max_isu_dt.issue_date"],["max_isu_qty.item_account_id", "=", "all_rcv.item_account_id"]])
      
      ->where([['itemcategories.identity','=',9]])
      ->when(request('item_category_id'), function ($q) {
		return $q->where('itemcategories.id', '=', request('item_category_id', 0));
	})
      ->when(request('consumption_level_id'), function ($q) {
            return $q->where('item_accounts.consumption_level_id', '=', request('consumption_level_id', 0));
      })
      ->orderBy('itemcategories.id')
      ->orderBy('itemclasses.id')
      ->orderBy('item_accounts.item_description')
      ->get([
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id',
      'item_accounts.item_description',
      'item_accounts.specification',
      'uoms.code as uom_code',

      'pur_rcv.qty as pur_qty',
      'trans_in_rcv.qty as trans_in_qty',
      'isu_rtn_rcv.qty as isu_rtn_qty',
      'general_rcv.qty as receive_qty',
      'general_rcv.amount as receive_amount',
      'open_general_rcv.qty as open_receive_qty',
      'open_general_rcv.amount as open_receive_amount',
      'regular_isu.qty as regular_issue_qty',
      'trans_out_isu.qty as trans_out_issue_qty',
      'rcv_rtn_isu.qty as rcv_rtn_issue_qty',
      'general_isu.qty as issue_qty',
      'general_isu.amount as issue_amount',
      'open_general_isu.qty as open_issue_qty',
      'open_general_isu.amount as open_issue_amount',
      'max_rcv_dt.receive_date as max_receive_date',
      'max_rcv_qty.qty as max_receive_qty',
      'max_isu_dt.issue_date as max_issue_date',
      'max_isu_qty.qty as max_issue_qty',
      ])
      ->map(function($invgeneralrcvitem)  {
      $invgeneralrcvitem->item_desc=$invgeneralrcvitem->item_description.", ".$invgeneralrcvitem->specification;

      $invgeneralrcvitem->issue_qty=$invgeneralrcvitem->issue_qty*-1;

      $invgeneralrcvitem->issue_amount=$invgeneralrcvitem->issue_amount;

      $invgeneralrcvitem->opening_qty=$invgeneralrcvitem->open_receive_qty-($invgeneralrcvitem->open_issue_qty*-1);
      $invgeneralrcvitem->opening_amount=$invgeneralrcvitem->open_receive_amount-($invgeneralrcvitem->open_issue_amount);
      
      //$invgeneralrcvitem->stock_qty=($invgeneralrcvitem->opening_qty+$invgeneralrcvitem->receive_qty)-($invgeneralrcvitem->issue_qty);
      //$invgeneralrcvitem->stock_value=($invgeneralrcvitem->opening_amount+$invgeneralrcvitem->receive_amount)-($invgeneralrcvitem->issue_amount);
      
      $invgeneralrcvitem->receive_qty=$invgeneralrcvitem->receive_qty+$invgeneralrcvitem->opening_qty;
      $invgeneralrcvitem->receive_amount=$invgeneralrcvitem->receive_amount+$invgeneralrcvitem->opening_amount;
      $invgeneralrcvitem->stock_qty=($invgeneralrcvitem->receive_qty)-($invgeneralrcvitem->issue_qty);
      $invgeneralrcvitem->stock_value=($invgeneralrcvitem->receive_amount)-($invgeneralrcvitem->issue_amount);

      $invgeneralrcvitem->rate=0;
      if($invgeneralrcvitem->stock_qty){
      $invgeneralrcvitem->rate=$invgeneralrcvitem->stock_value/$invgeneralrcvitem->stock_qty;
      }

      

      if($invgeneralrcvitem->max_receive_date){
      $invgeneralrcvitem->last_receive=date('d-M-Y',strtotime($invgeneralrcvitem->max_receive_date));//."<br/>".$invgeneralrcvitem->max_receive_qty;
      }
      else{
	      $invgeneralrcvitem->last_receive='';
      }

      if($invgeneralrcvitem->max_issue_date){
      $invgeneralrcvitem->last_issue=date('d-M-Y',strtotime($invgeneralrcvitem->max_issue_date));//."<br/>".$invgeneralrcvitem->max_issue_qty;
      }
      else{
	      $invgeneralrcvitem->last_issue='';
      }

		$now = time(); // or your date as well
		$max_issue_date = strtotime($invgeneralrcvitem->max_issue_date);
		$datediff = $now - $max_issue_date;
		if($invgeneralrcvitem->max_issue_date)
		{
	    $invgeneralrcvitem->diff_days=round($datediff / (60 * 60 * 24));
		}
		else
		{
	    $invgeneralrcvitem->diff_days='';
		}


      $invgeneralrcvitem->opening_qty=number_format($invgeneralrcvitem->opening_qty,2);

      $invgeneralrcvitem->pur_qty=number_format($invgeneralrcvitem->pur_qty,2);
      $invgeneralrcvitem->trans_in_qty=number_format($invgeneralrcvitem->trans_in_qty,2);
      $invgeneralrcvitem->isu_rtn_qty=number_format($invgeneralrcvitem->isu_rtn_qty,2);
      $invgeneralrcvitem->receive_qty=number_format($invgeneralrcvitem->receive_qty,2);

      $invgeneralrcvitem->regular_issue_qty=number_format($invgeneralrcvitem->regular_issue_qty,2);
      $invgeneralrcvitem->trans_out_issue_qty=number_format($invgeneralrcvitem->trans_out_issue_qty,2);
      $invgeneralrcvitem->rcv_rtn_issue_qty=number_format($invgeneralrcvitem->rcv_rtn_issue_qty,2);
      $invgeneralrcvitem->issue_qty=number_format($invgeneralrcvitem->issue_qty,2);

      $invgeneralrcvitem->stock_qty=number_format($invgeneralrcvitem->stock_qty,2);
      $invgeneralrcvitem->rate=number_format($invgeneralrcvitem->rate,2);
      $invgeneralrcvitem->stock_value=number_format($invgeneralrcvitem->stock_value,2);
      $invgeneralrcvitem->max_receive_qty=number_format($invgeneralrcvitem->max_receive_qty,2);
      $invgeneralrcvitem->max_issue_qty=number_format($invgeneralrcvitem->max_issue_qty,2);
      return $invgeneralrcvitem;
      })
      /*->filter(function($invgeneralrcvitem){
            if($invgeneralrcvitem->opening_qty*1 || $invgeneralrcvitem->receive_qty*1){
                  return $invgeneralrcvitem;
            }

      })
      ->values()*/; 
      echo json_encode($invgeneralrcvitem);
    }
}
