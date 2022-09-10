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

class DyeChemStockController extends Controller
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


    return Template::loadView('Report.Inventory.DyeChemStock',['supplier'=>$supplier,'store'=>$store,'company'=>$company,'itemcategory'=>$itemcategory,'consumptionlevel'=>$consumptionlevel]);
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
       $companyCond= ' and inv_dye_chem_transactions.company_id= '.$company_id;
      }
      else{
       $companyCond= '';
      }

      $storeCond='';
      if($store_id){
       $storeCond= ' and inv_dye_chem_transactions.store_id= '.$store_id;
      }
      else{
       $storeCond= '';
      }
      



      $invdyechemrcvitem=$this->itemaccount
      ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
      })
      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date<'".$date_from."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id
      ) open_dye_chem_rcv"), "open_dye_chem_rcv.item_account_id", "=", "item_accounts.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id in (1,2,3)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) pur_rcv"), "pur_rcv.item_account_id", "=", "item_accounts.id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 9
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) trans_in_rcv"), "trans_in_rcv.item_account_id", "=", "item_accounts.id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 4
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) isu_rtn_rcv"), "isu_rtn_rcv.item_account_id", "=", "item_accounts.id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) dyechem_rcv"), "dyechem_rcv.item_account_id", "=", "item_accounts.id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date<'".$date_from."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id

      ) open_dye_chem_isu"), "open_dye_chem_isu.item_account_id", "=", "item_accounts.id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id in (1,2)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) regular_isu"), "regular_isu.item_account_id", "=", "item_accounts.id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 9
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) trans_out_isu"), "trans_out_isu.item_account_id", "=", "item_accounts.id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 11
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) rcv_rtn_isu"), "rcv_rtn_isu.item_account_id", "=", "item_accounts.id")
      
      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) dyechem_isu"), "dyechem_isu.item_account_id", "=", "item_accounts.id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      max(inv_rcvs.receive_date) as receive_date
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where 
      inv_rcvs.receive_basis_id in (1,2,3)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) max_rcv_dt"), "max_rcv_dt.item_account_id", "=", "item_accounts.id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      inv_rcvs.receive_date as receive_date,
      sum(inv_dye_chem_transactions.store_qty) as qty
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where  inv_rcvs.receive_basis_id in (1,2,3)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by 
      inv_dye_chem_rcv_items.item_account_id,
      inv_rcvs.receive_date

      ) max_rcv_qty"), [["max_rcv_qty.receive_date", "=", "max_rcv_dt.receive_date"],["max_rcv_qty.item_account_id", "=", "item_accounts.id"]])


      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      max(inv_isus.issue_date) as issue_date
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where  inv_isus.isu_basis_id in (1,2)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) max_isu_dt"), "max_isu_dt.item_account_id", "=", "item_accounts.id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      inv_isus.issue_date as issue_date,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where 
      inv_isus.isu_basis_id in (1,2)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id,inv_isus.issue_date
      ) max_isu_qty"), [["max_isu_qty.issue_date", "=", "max_isu_dt.issue_date"],["max_isu_qty.item_account_id", "=", "item_accounts.id"]])
      
      //->where([['itemcategories.identity','=',9]])
      ->whereIn('itemcategories.identity',[7,8])
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
      'dyechem_rcv.qty as receive_qty',
      'dyechem_rcv.amount as receive_amount',
      'open_dye_chem_rcv.qty as open_receive_qty',
      'open_dye_chem_rcv.amount as open_receive_amount',
      'regular_isu.qty as regular_issue_qty',
      'trans_out_isu.qty as trans_out_issue_qty',
      'rcv_rtn_isu.qty as rcv_rtn_issue_qty',
      'dyechem_isu.qty as issue_qty',
      'dyechem_isu.amount as issue_amount',
      'open_dye_chem_isu.qty as open_issue_qty',
      'open_dye_chem_isu.amount as open_issue_amount',
      'max_rcv_dt.receive_date as max_receive_date',
      'max_rcv_qty.qty as max_receive_qty',
      'max_isu_dt.issue_date as max_issue_date',
      'max_isu_qty.qty as max_issue_qty',
      ])
      ->map(function($invdyechemrcvitem)  {
      $invdyechemrcvitem->item_desc=$invdyechemrcvitem->item_description.", ".$invdyechemrcvitem->specification;

      $invdyechemrcvitem->issue_qty=$invdyechemrcvitem->issue_qty*-1;

      $invdyechemrcvitem->issue_amount=$invdyechemrcvitem->issue_amount;

      $invdyechemrcvitem->opening_qty=$invdyechemrcvitem->open_receive_qty-($invdyechemrcvitem->open_issue_qty*-1);
      $invdyechemrcvitem->opening_amount=$invdyechemrcvitem->open_receive_amount-($invdyechemrcvitem->open_issue_amount);
      $invdyechemrcvitem->stock_qty=($invdyechemrcvitem->opening_qty+$invdyechemrcvitem->receive_qty)-($invdyechemrcvitem->issue_qty);
      $invdyechemrcvitem->stock_value=$invdyechemrcvitem->opening_amount+($invdyechemrcvitem->receive_amount-$invdyechemrcvitem->issue_amount);

      $invdyechemrcvitem->rate=0;
      if($invdyechemrcvitem->stock_qty){
      $invdyechemrcvitem->rate=$invdyechemrcvitem->stock_value/$invdyechemrcvitem->stock_qty;
      }

      

      if($invdyechemrcvitem->max_receive_date){
      $invdyechemrcvitem->last_receive=date('d-M-Y',strtotime($invdyechemrcvitem->max_receive_date));
      }
      else{
	      $invdyechemrcvitem->last_receive='';
      }

      if($invdyechemrcvitem->max_issue_date){
      $invdyechemrcvitem->last_issue=date('d-M-Y',strtotime($invdyechemrcvitem->max_issue_date));//
      }
      else{
	      $invdyechemrcvitem->last_issue='';
      }

		$now = time(); 
		$max_issue_date = strtotime($invdyechemrcvitem->max_issue_date);
		$datediff = $now - $max_issue_date;
		if($invdyechemrcvitem->max_issue_date)
		{
	    $invdyechemrcvitem->diff_days=round($datediff / (60 * 60 * 24));
		}
		else
		{
	    $invdyechemrcvitem->diff_days='';
		}


      $invdyechemrcvitem->opening_qty=number_format($invdyechemrcvitem->opening_qty,2);
      $invdyechemrcvitem->pur_qty=number_format($invdyechemrcvitem->pur_qty,2);
      $invdyechemrcvitem->trans_in_qty=number_format($invdyechemrcvitem->trans_in_qty,2);
      $invdyechemrcvitem->isu_rtn_qty=number_format($invdyechemrcvitem->isu_rtn_qty,2);
      $invdyechemrcvitem->receive_qty=number_format($invdyechemrcvitem->receive_qty,2);
      $invdyechemrcvitem->regular_issue_qty=number_format($invdyechemrcvitem->regular_issue_qty,2);
      $invdyechemrcvitem->trans_out_issue_qty=number_format($invdyechemrcvitem->trans_out_issue_qty,2);
      $invdyechemrcvitem->rcv_rtn_issue_qty=number_format($invdyechemrcvitem->rcv_rtn_issue_qty,2);
      $invdyechemrcvitem->issue_qty=number_format($invdyechemrcvitem->issue_qty,2);
      $invdyechemrcvitem->stock_qty=number_format($invdyechemrcvitem->stock_qty,2);
      $invdyechemrcvitem->rate=number_format($invdyechemrcvitem->rate,2);
      $invdyechemrcvitem->stock_value=number_format($invdyechemrcvitem->stock_value,2);
      $invdyechemrcvitem->max_receive_qty=number_format($invdyechemrcvitem->max_receive_qty,2);
      $invdyechemrcvitem->max_issue_qty=number_format($invdyechemrcvitem->max_issue_qty,2);
      return $invdyechemrcvitem;
      }); 
      echo json_encode($invdyechemrcvitem);
    }
}
