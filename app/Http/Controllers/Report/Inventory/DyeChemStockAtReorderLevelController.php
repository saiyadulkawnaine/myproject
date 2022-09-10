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

class DyeChemStockAtReorderLevelController extends Controller
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


    return Template::loadView('Report.Inventory.DyeChemStockAtReorderLevel',['supplier'=>$supplier,'store'=>$store,'company'=>$company,'itemcategory'=>$itemcategory,'consumptionlevel'=>$consumptionlevel]);
  }
public function reportData() {
		$company_id=request('company_id',0);
		$date_to=request('date_to',0);
		$avg_of=request('avg_of',0);
		$req_for=request('req_for',0);
		$date_to=date('Y-m-d', strtotime($date_to));
		$date_from = date('Y-m-d', strtotime('-'.$avg_of.' days', strtotime($date_to)));
		$companyCond='';
		if($company_id){
		$companyCond= ' and inv_dye_chem_transactions.company_id= '.$company_id;
		}
		else{
		$companyCond= '';
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
		where inv_rcvs.receive_date<='".$date_to."' 
		--and inv_rcvs.receive_date<='".$date_to."'
		and inv_dye_chem_transactions.deleted_at is null
		and inv_dye_chem_rcv_items.deleted_at is null
		and inv_rcvs.deleted_at is null
		and inv_dye_chem_transactions.trans_type_id=1
		$companyCond
		group by inv_dye_chem_rcv_items.item_account_id

		) dyechem_rcv"), "dyechem_rcv.item_account_id", "=", "item_accounts.id")
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
		$companyCond
		group by inv_dye_chem_isu_items.item_account_id
		) regular_isu"), "regular_isu.item_account_id", "=", "item_accounts.id")
		->leftJoin(\DB::raw("(
		select 
		inv_dye_chem_isu_items.item_account_id,
		sum(inv_dye_chem_transactions.store_qty) as qty,
		sum(inv_dye_chem_transactions.store_amount) as amount
		from inv_dye_chem_isu_items
		join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
		join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
		where inv_isus.issue_date<='".$date_to."' 
		--and inv_isus.issue_date<='".$date_to."'
		and inv_dye_chem_transactions.deleted_at is null
		and inv_dye_chem_isu_items.deleted_at is null
		and inv_isus.deleted_at is null
		and inv_dye_chem_transactions.trans_type_id=2
		$companyCond
		group by inv_dye_chem_isu_items.item_account_id
		) dyechem_isu"), "dyechem_isu.item_account_id", "=", "item_accounts.id")

		->leftJoin(\DB::raw("(
		select 
		item_account_suppliers.item_account_id,
		min(item_account_suppliers.supplier_id) as supplier_id
		from item_account_suppliers
		group by item_account_suppliers.item_account_id
		) itemsupplier"), "itemsupplier.item_account_id", "=", "item_accounts.id")

		->leftJoin('suppliers',function($join){
		$join->on('suppliers.id','=','itemsupplier.supplier_id');
		})
		->whereIn('itemcategories.identity',[7,8])
		->when(request('item_category_id'), function ($q) {
		return $q->where('itemcategories.id', '=', request('item_category_id', 0));
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
		'item_accounts.sub_class_name',
		'item_accounts.reorder_level',
		'item_accounts.min_level',
		'uoms.code as uom_code',
		'dyechem_rcv.qty as receive_qty',
		'dyechem_rcv.amount as receive_amount',
		'regular_isu.qty as regular_issue_qty',
		'dyechem_isu.qty as issue_qty',
		'dyechem_isu.amount as issue_amount',
		'suppliers.name as supplier_name',
		])
		->map(function($invdyechemrcvitem) use($avg_of,$req_for) {
			$invdyechemrcvitem->item_desc=$invdyechemrcvitem->item_description.", ".$invdyechemrcvitem->specification;
			$invdyechemrcvitem->issue_qty=$invdyechemrcvitem->issue_qty*-1;
			$invdyechemrcvitem->stock_qty=$invdyechemrcvitem->receive_qty-$invdyechemrcvitem->issue_qty;
			$invdyechemrcvitem->stock_value=$invdyechemrcvitem->receive_amount-$invdyechemrcvitem->issue_amount;
			$invdyechemrcvitem->rate=0;
			if($invdyechemrcvitem->stock_qty){
			$invdyechemrcvitem->rate=$invdyechemrcvitem->stock_value/$invdyechemrcvitem->stock_qty;
			}
			$invdyechemrcvitem->avg_day_cons=($invdyechemrcvitem->regular_issue_qty/$avg_of);
			$invdyechemrcvitem->avg_month_cons=$invdyechemrcvitem->avg_day_cons*30;
			$invdyechemrcvitem->req_qty=$invdyechemrcvitem->avg_day_cons*$req_for;
			$invdyechemrcvitem->req_amount=$invdyechemrcvitem->req_qty*$invdyechemrcvitem->rate;
			$invdyechemrcvitem->reorder_level_o=$invdyechemrcvitem->reorder_level;
			$invdyechemrcvitem->stock_qty_o=$invdyechemrcvitem->stock_qty;
			$invdyechemrcvitem->stock_qty=number_format($invdyechemrcvitem->stock_qty,2);
			$invdyechemrcvitem->avg_day_cons=number_format($invdyechemrcvitem->avg_day_cons,2);
			$invdyechemrcvitem->avg_month_cons=number_format($invdyechemrcvitem->avg_month_cons,2);
			$invdyechemrcvitem->req_qty=number_format($invdyechemrcvitem->req_qty,2);
			$invdyechemrcvitem->rate=number_format($invdyechemrcvitem->rate,2);
			$invdyechemrcvitem->req_amount=number_format($invdyechemrcvitem->req_amount,2);
			$invdyechemrcvitem->reorder_level=number_format($invdyechemrcvitem->reorder_level,2);
			$invdyechemrcvitem->min_level=number_format($invdyechemrcvitem->min_level,2);
			return $invdyechemrcvitem;
		})
		->filter(function ($value) {
			if($value->stock_qty_o <= $value->reorder_level_o){
			return $value;
			}
		})
		->values()
		; 
		
		echo json_encode($invdyechemrcvitem);
	}
}
