<?php
namespace App\Http\Controllers\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuItemRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;

class YarnIssueReceiveController extends Controller
{

  private $invisu;
  private $invyarnisu;
  private $invrcv;
  private $invyarnitem;
  private $itemaccount;
  private $supplier;
  private $company;
  private $store;
  private $implc;
  private $bankbranch;
  private $invyarnisuitem;

  public function __construct(
    InvRcvRepository $invrcv,
    InvYarnItemRepository $invyarnitem,
    InvYarnIsuItemRepository $invyarnisuitem, 
    InvIsuRepository $invisu,
    InvYarnIsuRepository $invyarnisu,
    ItemAccountRepository $itemaccount,
    SupplierRepository $supplier,
    CompanyRepository $company,
    ImpLcRepository $implc,
    StoreRepository $store,
    BankBranchRepository $bankbranch
  )
  {
    $this->invisu = $invisu;
    $this->invyarnisu = $invyarnisu;
    $this->invyarnisuitem = $invyarnisuitem;
    $this->invrcv = $invrcv;
    $this->invyarnitem=$invyarnitem;
    $this->itemaccount=$itemaccount;
    $this->supplier = $supplier;
    $this->company=$company;
    $this->store=$store;
    $this->implc=$implc;
    $this->bankbranch=$bankbranch;

    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {
    $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
    $company = array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
    $bankbranch=array_prepend(array_pluck(
        $this->bankbranch
        ->leftJoin('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->get([
          'bank_branches.id',
          'bank_branches.branch_name',
          'banks.name as bank_name',
        ])
        ->map(function($bankbranch){
          $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
          return $bankbranch;
        })
        ,'name','id'),'-Select-','');
    $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
    $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
    return Template::loadView('Report.Inventory.YarnIssueReceive',['supplier'=>$supplier,'company'=>$company,'bankbranch'=>$bankbranch,'lctype'=>$lctype,'store'=>$store]);
  }
	public function html() {
      $company_id=request('company_id',0);
      $supplier_id=request('supplier_id',0);
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $imp_lc_id=request('imp_lc_id',0);
      $store_id=request('store_id',0);

      $invyarn=$this->invrcv
        ->selectRaw('
          inv_rcvs.id as inv_rcv_id,
          inv_rcvs.receive_no,
          inv_rcvs.receive_date,
          inv_rcvs.receive_basis_id,
          inv_rcvs.receive_against_id,
          inv_rcvs.from_company_id, 
          inv_yarn_rcv_items.id as inv_yarn_rcv_item_id,
          inv_yarn_rcv_items.store_id,
          inv_yarn_rcv_items.qty,
          inv_yarn_rcv_items.rate,
          inv_yarn_rcv_items.amount,
          inv_yarn_rcv_items.store_amount,
          inv_yarn_rcv_items.store_rate,
          suppliers.name as supplier_code,
          uoms.code as uom_code,
          inv_yarn_items.item_account_id,
          inv_yarn_items.lot as yarn_lot,
          inv_yarn_items.brand as yarn_brand,
          colors.name as yarn_color,
          yarncounts.count as yarn_count,
          yarncounts.symbol,
          yarntypes.name as yarn_type,
          itemclasses.name as itemclass_name,
          currencies.code as currency_code,
          trans_in_company.code as from_company_code,
          po_yarns.pi_no,
          po_yarns.currency_id,
          trans_in.trans_in_qty,
          trans_in.trans_amount,
          yarn_rcv_lc.imp_lc_id,
          yarn_rcv_lc.lc_no_i,
          yarn_rcv_lc.lc_no_ii,
          yarn_rcv_lc.lc_no_iii,
          yarn_rcv_lc.lc_no_iv
        ')
        ->join('inv_yarn_rcvs',function($join){
          $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_yarn_rcv_items',function($join){
          $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id');
        })
        ->leftJoin(\DB::raw("(
          select 
            inv_rcvs.id as inv_rcv_id,
            inv_yarn_rcv_items.id as inv_yarn_rcv_item_id,
            sum(inv_yarn_transactions.store_qty) as trans_in_qty,
            sum(inv_yarn_transactions.store_amount) as trans_amount
          from inv_rcvs
            join inv_yarn_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
            join inv_yarn_rcv_items on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
            join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id 
          where inv_rcvs.receive_date>='".$date_from."'
            and inv_rcvs.receive_date<= '".$date_to."'
            and inv_rcvs.receive_basis_id=9
            and inv_yarn_transactions.deleted_at is null
            and inv_yarn_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_yarn_transactions.trans_type_id=1
          group by
            inv_rcvs.id,
            inv_yarn_rcv_items.id
          ) trans_in"), "trans_in.inv_yarn_rcv_item_id", "=", "inv_yarn_rcv_items.id")
        ->join('po_yarn_items',function($join){
          $join->on('po_yarn_items.id','=','inv_yarn_rcv_items.po_yarn_item_id');
        })
        ->join('po_yarns',function($join){
          $join->on('po_yarns.id','=','po_yarn_items.po_yarn_id');
        })
        ->leftJoin('suppliers',function($join){
          $join->on('suppliers.id','=','inv_rcvs.supplier_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','inv_rcvs.company_id');
        })
        ->leftJoin('companies as trans_in_company',function($join){
          $join->on('trans_in_company.id','=','inv_rcvs.from_company_id');
        })
        ->join('inv_yarn_items',function($join){
          $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id');
        })
        ->join('item_accounts',function($join){
          $join->on('item_accounts.id','=','inv_yarn_items.item_account_id');
        })
        ->leftJoin('yarncounts',function($join){
          $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
          $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
          $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('colors',function($join){
          $join->on('colors.id','=','inv_yarn_items.color_id');
        })
        ->join('uoms',function($join){
          $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->leftJoin('currencies',function($join){
          $join->on('currencies.id','=','po_yarns.currency_id');
        })
        ->leftJoin(\DB::raw("(
          select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.id as imp_lc_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            from
            imp_lcs
            join imp_lc_pos on imp_lc_pos.imp_lc_id=imp_lcs.id and imp_lcs.menu_id=3
            join po_yarns on imp_lc_pos.purchase_order_id=po_yarns.id
            join po_yarn_items on po_yarn_items.po_yarn_id=po_yarns.id
            join inv_yarn_rcv_items on inv_yarn_rcv_items.po_yarn_item_id=po_yarn_items.id
            join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
            where inv_rcvs.receive_date>='".$date_from."' 
            and inv_rcvs.receive_date<='".$date_to."'
            and inv_yarn_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            group by 
            imp_lc_pos.purchase_order_id,
            imp_lcs.id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
        ) yarn_rcv_lc"), "yarn_rcv_lc.purchase_order_id", "=", "po_yarns.id")
        
        ->when(request('date_from'), function ($q) {
          return $q->where('inv_rcvs.receive_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('inv_rcvs.receive_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) {
          return $q->where('inv_rcvs.company_id', '=',request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
          return $q->where('inv_rcvs.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('store_id'), function ($q) {
          return $q->where('inv_yarn_rcv_items.store_id', '=',request('store_id', 0));
        })
        ->when(request('imp_lc_id'), function ($q) {
          return $q->where('yarn_rcv_lc.imp_lc_id', '=',request('imp_lc_id', 0));
        })
        ->whereIn('inv_rcvs.receive_against_id',[3])
        ->whereIn('inv_rcvs.menu_id',[100,104,105,108])
        ->get();

      $invyarndyeing=$this->invrcv
        ->selectRaw('
          inv_rcvs.id as inv_rcv_id,
          inv_rcvs.receive_no,
          inv_rcvs.receive_date,
          inv_rcvs.receive_basis_id,
          inv_rcvs.receive_against_id,
          inv_rcvs.from_company_id,    
          inv_yarn_rcv_items.id as inv_yarn_rcv_item_id,
          inv_yarn_rcv_items.store_id,
          inv_yarn_rcv_items.qty,
          inv_yarn_rcv_items.rate,
          inv_yarn_rcv_items.amount,
          inv_yarn_rcv_items.store_amount,
          inv_yarn_rcv_items.store_rate,
          suppliers.name as supplier_code,
          uoms.code as uom_code,
          inv_yarn_items.item_account_id,
          inv_yarn_items.lot as yarn_lot,
          inv_yarn_items.brand as yarn_brand,
          colors.name as yarn_color,
          yarncounts.count as yarn_count,
          yarncounts.symbol,
          yarntypes.name as yarn_type,
          itemclasses.name as itemclass_name,  
          currencies.code as currency_code,
          trans_in_company.code as from_company_code,
          po_yarn_dyeings.pi_no,
          po_yarn_dyeings.currency_id,
          trans_in.trans_in_qty,
          trans_in.trans_amount,
          yarn_rcv_lc.imp_lc_id,
          yarn_rcv_lc.lc_no_i,
          yarn_rcv_lc.lc_no_ii,
          yarn_rcv_lc.lc_no_iii,
          yarn_rcv_lc.lc_no_iv
        ')
        ->join('inv_yarn_rcvs',function($join){
          $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_yarn_rcv_items',function($join){
          $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id');
        })
        ->leftJoin(\DB::raw("(
          select 
            inv_rcvs.id as inv_rcv_id,
            inv_yarn_rcv_items.id as inv_yarn_rcv_item_id,
            sum(inv_yarn_transactions.store_qty) as trans_in_qty,
            sum(inv_yarn_transactions.store_amount) as trans_amount
          from inv_rcvs
            join inv_yarn_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
            join inv_yarn_rcv_items on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
            join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id 
          where inv_rcvs.receive_date>='".$date_from."'
            and inv_rcvs.receive_date<= '".$date_to."'
            and inv_rcvs.receive_basis_id=9
            and inv_yarn_transactions.deleted_at is null
            and inv_yarn_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_yarn_transactions.trans_type_id=1
          group by
            inv_rcvs.id,
            inv_yarn_rcv_items.id
        ) trans_in"), "trans_in.inv_yarn_rcv_item_id", "=", "inv_yarn_rcv_items.id")

        ->join('inv_yarn_isu_items',function($join){
          $join->on('inv_yarn_isu_items.id','=','inv_yarn_rcv_items.inv_yarn_isu_item_id');
        })
        ->join('po_yarn_dyeing_item_bom_qties',function($join){
          $join->on('po_yarn_dyeing_item_bom_qties.id','=','inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id');
        })
        ->join('po_yarn_dyeing_items',function($join){
          $join->on('po_yarn_dyeing_items.id','=','po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id');
        })
        ->join('po_yarn_dyeings',function($join){
          $join->on('po_yarn_dyeings.id','=','po_yarn_dyeing_items.po_yarn_dyeing_id');
        }) 
        ->join('inv_yarn_items',function($join){
          $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
        ->whereNull('inv_yarn_rcv_items.deleted_at');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','inv_rcvs.supplier_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','inv_rcvs.company_id');
        })
        ->leftJoin('companies as trans_in_company',function($join){
          $join->on('trans_in_company.id','=','inv_rcvs.from_company_id');
        })
        ->join('item_accounts',function($join){
          $join->on('item_accounts.id','=','inv_yarn_items.item_account_id');
        })
        ->join('yarncounts',function($join){
          $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->join('yarntypes',function($join){
          $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->join('itemclasses',function($join){
          $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('colors',function($join){
          $join->on('colors.id','=','inv_yarn_items.color_id');
        })
        ->join('uoms',function($join){
          $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->leftJoin('currencies',function($join){
          $join->on('currencies.id','=','po_yarn_dyeings.currency_id');
        })
        ->leftJoin(\DB::raw("(
          select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.id as imp_lc_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
          from
            imp_lcs
            join imp_lc_pos on imp_lc_pos.imp_lc_id=imp_lcs.id and imp_lcs.menu_id=9
            join po_yarn_dyeings on imp_lc_pos.purchase_order_id=po_yarn_dyeings.id
            join po_yarn_dyeing_items on po_yarn_dyeing_items.po_yarn_dyeing_id=po_yarn_dyeings.id  
            join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id = po_yarn_dyeing_items.id   
            join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
            join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_isu_item_id=inv_yarn_isu_items.id
            join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
          where inv_rcvs.receive_date>='".$date_from."'
            and inv_rcvs.receive_date<='".$date_to."'
            and inv_yarn_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
          group by 
            imp_lc_pos.purchase_order_id,
            imp_lcs.id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
        ) yarn_rcv_lc"), "yarn_rcv_lc.purchase_order_id", "=", "po_yarn_dyeings.id")
        
      ->when(request('date_from'), function ($q) {
        return $q->where('inv_rcvs.receive_date', '>=',request('date_from', 0));
      })
      ->when(request('date_to'), function ($q) {
        return $q->where('inv_rcvs.receive_date', '<=',request('date_to', 0));
      })
      ->when(request('company_id'), function ($q) {
        return $q->where('inv_rcvs.company_id', '=',request('company_id', 0));
      })
      ->when(request('supplier_id'), function ($q) {
        return $q->where('inv_rcvs.supplier_id', '=',request('supplier_id', 0));
      })
      ->when(request('store_id'), function ($q) {
        return $q->where('inv_yarn_rcv_items.store_id', '=',request('store_id', 0));
      })
      ->when(request('imp_lc_id'), function ($q) {
        return $q->where('yarn_rcv_lc.imp_lc_id', '=',request('imp_lc_id', 0));
      })
      ->whereIn('inv_rcvs.receive_against_id',[9])
      ->whereIn('inv_rcvs.menu_id',[100,104,105,108])
      ->get();
      
      $independent=$this->invrcv
        ->selectRaw('
          inv_rcvs.id as inv_rcv_id,
          inv_rcvs.receive_no,
          inv_rcvs.receive_date,
          inv_rcvs.receive_basis_id,
          inv_rcvs.receive_against_id,
          inv_rcvs.from_company_id,    
          inv_yarn_rcv_items.id as inv_yarn_rcv_item_id,
          inv_yarn_rcv_items.store_id,
          inv_yarn_rcv_items.qty,
          inv_yarn_rcv_items.rate,
          inv_yarn_rcv_items.amount,
          inv_yarn_rcv_items.store_amount,
          inv_yarn_rcv_items.store_rate,
          suppliers.name as supplier_code,
          uoms.code as uom_code,
          inv_yarn_items.item_account_id,
          inv_yarn_items.lot as yarn_lot,
          inv_yarn_items.brand as yarn_brand,
          colors.name as yarn_color,
          yarncounts.count as yarn_count,
          yarncounts.symbol,
          yarntypes.name as yarn_type,
          itemclasses.name as itemclass_name,  
          trans_in_company.code as from_company_code,
          trans_in.trans_in_qty,
          trans_in.trans_amount
        ')
        ->join('inv_yarn_rcvs',function($join){
          $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_yarn_rcv_items',function($join){
          $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id')->whereNull('inv_yarn_rcv_items.deleted_at');
          
        })
        ->leftJoin(\DB::raw("(
          select 
            inv_rcvs.id as inv_rcv_id,
            inv_yarn_rcv_items.id as inv_yarn_rcv_item_id,
            sum(inv_yarn_transactions.store_qty) as trans_in_qty,
            sum(inv_yarn_transactions.store_amount) as trans_amount
          from inv_rcvs
            join inv_yarn_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
            join inv_yarn_rcv_items on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
            join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id 
          where inv_rcvs.receive_date>='".$date_from."'
            and inv_rcvs.receive_date<= '".$date_to."'
            and inv_rcvs.receive_basis_id=9
            and inv_yarn_transactions.deleted_at is null
            and inv_yarn_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_yarn_transactions.trans_type_id=1
          group by
            inv_rcvs.id,
            inv_yarn_rcv_items.id
        ) trans_in"), "trans_in.inv_yarn_rcv_item_id", "=", "inv_yarn_rcv_items.id")
  
        ->leftJoin('inv_yarn_items',function($join){
          $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
        ->whereNull('inv_yarn_rcv_items.deleted_at');
        })
        ->leftJoin('item_accounts',function($join){
          $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('yarncounts',function($join){
          $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
          $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
          $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('colors',function($join){
          $join->on('colors.id','=','inv_yarn_items.color_id');
        })
        ->leftJoin('uoms',function($join){
          $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->leftJoin('suppliers',function($join){
          $join->on('suppliers.id','=','inv_rcvs.supplier_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','inv_rcvs.company_id');
        })
        ->leftJoin('companies as trans_in_company',function($join){
          $join->on('trans_in_company.id','=','inv_rcvs.from_company_id');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('inv_rcvs.receive_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('inv_rcvs.receive_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) {
          return $q->where('inv_rcvs.company_id', '=',request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
          return $q->where('inv_rcvs.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('store_id'), function ($q) {
          return $q->where('inv_yarn_rcv_items.store_id', '=',request('store_id', 0));
        })
        ->whereIn('inv_rcvs.receive_against_id',[0,102])
        ->whereIn('inv_rcvs.menu_id',[100,104,105,108])
        ->get();
      
        //echo json_encode($invyarndyeing);
       $results=$invyarn->concat($invyarndyeing)->concat($independent)->all();
      //echo json_encode($results);
      return $results;
  }

  public function reportData(){
      $company_id=request('company_id',0);
      $supplier_id=request('supplier_id',0);
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $imp_lc_id=request('imp_lc_id',0);
      $store_id=request('store_id',0);
      $menu=array_prepend(config('bprs.menu'),'-Select-','');
      $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');
      $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');           
      $yarnDescription=$this->itemaccount
      ->leftJoin('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
      })
      ->leftJoin('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
      })
      ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->leftJoin('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->where([['itemcategories.identity','=',1]])
      ->orderBy('item_account_ratios.ratio','desc')
      ->get([
        'item_accounts.id',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
      ]);
      $itemaccountArr=array();
      $yarnCompositionArr=array();
      foreach($yarnDescription as $row){
        $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
        $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
      }

      $yarnDropdown=array();
      foreach($itemaccountArr as $key=>$value){
        $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
      }
      $collect=$this->html();
    //->map(function($collect)use($yarnDropdown,$invreceivebasis,$menu){
      foreach ($collect as $rows) {
          $rows['lc_no']=$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv;
          $rows['lc_pi_no']=$rows->lc_no." ; ".$rows->pi_no;
          $rows['receive_basis']=$invreceivebasis[$rows->receive_basis_id];
          $rows['store_id']=$store[$rows->store_id];
          $rows['receive_against']=$menu[$rows->receive_against_id];
          $rows['trans_date']=date('d-M-Y',strtotime($rows->receive_date));
          $rows['count_name']=$rows->count."/".$rows->symbol;
          $rows['composition']=$yarnDropdown[$rows->item_account_id];
          $rows['exch_rate']=0;
          if($rows['rate']){
              $rows['exch_rate']=$rows->store_rate/$rows->rate;
          }
          $rows['exch_rate']=number_format($rows->exch_rate,2); 
          
          if($rows['receive_basis_id']==4 && $rows['receive_against_id'] !==9){
            $rows['po_rate']=number_format(0,2);
            $rows['rcv_qty']=number_format(0,2);
            $rows['po_amount']=number_format(0,2);
            $rows['store_amount']=number_format(0,2);
            $rows['issue_rtn_qty']=number_format($rows->qty,2);
            $rows['issue_rtn_rate']=number_format($rows->rate,2);
            $rows['issue_rtn_amount']=number_format($rows->amount,2);
            $rows['trans_in_qty']=number_format(0,2);
            $rows['trans_amount']=number_format(0,2);
            $rows['other_issue_rtn_qty']=number_format(0,2);
            $rows['other_issue_rtn_rate']=number_format(0,2);
            $rows['other_issue_rtn_amount']=number_format(0,2);
          }
          else if($rows['receive_basis_id']==9 && $rows['receive_against_id'] ==0){
            $rows['po_rate']=number_format(0,2);
            $rows['rcv_qty']=number_format(0,2);
            $rows['po_amount']=number_format(0,2);
            $rows['store_amount']=number_format(0,2);
            $rows['issue_rtn_qty']=number_format(0,2);
            $rows['issue_rtn_rate']=number_format(0,2);
            $rows['issue_rtn_amount']=number_format(0,2);
            $rows['trans_in_qty']=number_format($rows->trans_in_qty,2);
            $rows['trans_amount']=number_format($rows->trans_amount,2);
            $rows['other_issue_rtn_qty']=number_format(0,2);
            $rows['other_issue_rtn_rate']=number_format(0,2);
            $rows['other_issue_rtn_amount']=number_format(0,2);
          }
          else if($rows['receive_against_id'] ==0 && $rows['receive_basis_id']==5 || $rows['receive_basis_id']==6 || $rows['receive_basis_id']==7){
            $rows['po_rate']=number_format(0,2);
            $rows['rcv_qty']=number_format(0,2);
            $rows['po_amount']=number_format(0,2);
            $rows['store_amount']=number_format(0,2);
            $rows['issue_rtn_qty']=number_format(0,2);
            $rows['issue_rtn_rate']=number_format(0,2);
            $rows['issue_rtn_amount']=number_format(0,2);
            $rows['trans_in_qty']=number_format(0,2);
            $rows['trans_amount']=number_format(0,2);
            $rows['other_issue_rtn_qty']=number_format($rows->qty,2);
            $rows['other_issue_rtn_rate']=number_format($rows->rate,2);
            $rows['other_issue_rtn_amount']=number_format($rows->amount,2);
          }
          else {
            $rows['po_rate']=number_format($rows->rate,2);
            $rows['rcv_qty']=number_format($rows->qty,2);
            $rows['po_amount']=number_format($rows->amount,2);
            $rows['store_amount']=number_format($rows->store_amount,2);
            $rows['issue_rtn_qty']=number_format(0,2);
            $rows['issue_rtn_rate']=number_format(0,2);
            $rows['issue_rtn_amount']=number_format(0,2);
            $rows['trans_in_qty']=number_format(0,2);
            $rows['trans_amount']=number_format(0,2);
            $rows['other_issue_rtn_qty']=number_format(0,2);
            $rows['other_issue_rtn_rate']=number_format(0,2);
            $rows['other_issue_rtn_amount']=number_format(0,2);
          }
      
          $rows['uom']='Kg';
      }
        
      // return $collect;
    //});

    echo json_encode($collect);
  }

  public function getYarnImportLc(){
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $bankbranch=array_prepend(array_pluck(
          $this->bankbranch
          ->leftJoin('banks',function($join){
              $join->on('banks.id','=','bank_branches.bank_id');
          })
          ->get([
              'bank_branches.id',
              'bank_branches.branch_name',
              'banks.name as bank_name',
          ])
          ->map(function($bankbranch){
              $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
              return $bankbranch;
          })
          ,'name','id'),'-Select-','');
          
      $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
      $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
          
      $rows=$this->implc
      ->whereIn('imp_lcs.menu_id',[3,9])
      ->when(request('company_id'), function ($q) {
          return $q->where('imp_lcs.company_id', '=', request('company_id', 0));
      })
      ->when(request('supplier_id'), function ($q) {
          return $q->where('imp_lcs.supplier_id', '=', request('supplier_id', 0));
      })
      ->when(request('issuing_bank_branch_id'), function ($q) {
          return $q->where('imp_lcs.issuing_bank_branch_id', '=', request('issuing_bank_branch_id', 0));
      })
      ->get([
          'imp_lcs.*'
      ])
      ->map(function($rows) use($company,$supplier,$bankbranch,$lctype,$payterm) {
          $rows->company_id = $company[$rows->company_id];
          $rows->supplier_id= $supplier[$rows->supplier_id];
          $rows->issuing_bank_branch_id=$bankbranch[$rows->issuing_bank_branch_id];
          $rows->lc_type_id=$lctype[$rows->lc_type_id];
          $rows->last_delilvery_date=date('d-M-Y',strtotime($rows->last_delilvery_date));
          $rows->expiry_date=date('d-M-Y',strtotime($rows->expiry_date));
          $rows->lc_no=$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv;
          $rows->pay_term_id=$payterm[$rows->pay_term_id];
          return $rows;
      });

      echo json_encode($rows);
  }

  public function issueHtml(){
    $company_id=request('company_id',0);
    $supplier_id=request('supplier_id',0);
    $date_from=request('date_from',0);
    $date_to=request('date_to',0);
    $imp_lc_id=request('imp_lc_id',0);
    $store_id=request('store_id',0);
    $invissuebasis=array_prepend(config('bprs.invissuebasis'), '','');
    $menu=array_prepend(config('bprs.menu'),'','');

    $yarnDescription=$this->itemaccount
    ->leftJoin('item_account_ratios',function($join){
      $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
    })
    ->leftJoin('compositions',function($join){
      $join->on('compositions.id','=','item_account_ratios.composition_id');
    })
    ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->leftJoin('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->where([['itemcategories.identity','=',1]])
    ->orderBy('item_account_ratios.ratio','desc')
    ->get([
      'item_accounts.id',
      'compositions.name as composition_name',
      'item_account_ratios.ratio',
    ]);

    $itemaccountArr=array();
    $yarnCompositionArr=array();
    foreach($yarnDescription as $row){
    $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
      $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
    }

    $yarnDropdown=array();
    foreach($itemaccountArr as $key=>$value){
      $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
    }

    $independent=$this->invisu
    ->join('companies',function($join){
      $join->on('companies.id','=','inv_isus.company_id');
    })
    ->leftJoin('companies as tocompanies',function($join){
      $join->on('tocompanies.id','=','inv_isus.to_company_id');
    })
    ->leftJoin('suppliers',function($join){
      $join->on('suppliers.id','=','inv_isus.supplier_id');
    })
    ->join('inv_yarn_isu_items',function($join){
      $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
    })
    ->join('inv_yarn_items',function($join){
      $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
    })
    ->join('item_accounts',function($join){
      $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
    })
    ->leftJoin('yarncounts',function($join){
      $join->on('yarncounts.id','=','item_accounts.yarncount_id');
    })
    ->leftJoin('yarntypes',function($join){
      $join->on('yarntypes.id','=','item_accounts.yarntype_id');
    })
    ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
    })
    ->join('colors',function($join){
      $join->on('colors.id','=','inv_yarn_items.color_id');
    })
    ->leftJoin(\DB::raw("(
      select 
      inv_yarn_isu_items.id as inv_yarn_isu_item_id,
      abs(sum(inv_yarn_transactions.store_qty)) as trans_out_qty,
      sum(inv_yarn_transactions.store_amount) as trans_out_amount
      from inv_yarn_isu_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
      join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_isus.isu_against_id=0
      and inv_yarn_transactions.trans_type_id=2
      group by inv_yarn_isu_items.id
      ) yarn_trans_out"), "yarn_trans_out.inv_yarn_isu_item_id", "=", "inv_yarn_isu_items.id")

    ->when(request('date_from'), function ($q) {
      return $q->where('inv_isus.issue_date', '>=',request('date_from', 0));
    })
    ->when(request('date_to'), function ($q) {
      return $q->where('inv_isus.issue_date', '<=',request('date_to', 0));
    })
    ->when(request('company_id'), function ($q) {
      return $q->where('inv_isus.company_id', '=',request('company_id', 0));
    })
    ->when(request('supplier_id'), function ($q) {
      return $q->where('inv_isus.supplier_id', '=',request('supplier_id', 0));
    })
    ->when(request('store_id'), function ($q) {
      return $q->where('inv_yarn_isu_items.store_id', '=',request('store_id', 0));
    })
    ->orderBy('inv_isus.issue_date','desc')
    ->where([['inv_isus.isu_against_id','=',0]])
    ->whereIn('inv_isus.menu_id',[101,104,107,111])
    ->get([
      'inv_isus.issue_no',
      'inv_isus.isu_basis_id',
      'inv_isus.isu_against_id',
      'inv_isus.issue_date',
      'companies.name as company_name',
      'suppliers.name as supplier_name',
      'inv_yarn_items.lot',
      'inv_yarn_items.brand',
      'colors.name as color_name',
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id as item_account_id',
      'yarncounts.count as yarn_count',
      'yarncounts.symbol',
      'yarntypes.name as yarn_type',
      'uoms.code as uom_code',
      'inv_yarn_isu_items.store_id',
      'inv_yarn_isu_items.qty',
      'inv_yarn_isu_items.amount',
      'inv_yarn_isu_items.rate',
      'yarn_trans_out.trans_out_qty',
      'yarn_trans_out.trans_out_amount',
      'tocompanies.code as to_company_code'
    ]);

    //dd($independent);
    //die;


    $poyarnisu = $this->invisu
    ->join('companies',function($join){
      $join->on('companies.id','=','inv_isus.company_id');
    })
    ->leftJoin('companies as tocompanies',function($join){
      $join->on('tocompanies.id','=','inv_isus.to_company_id');
    })
    ->leftJoin('suppliers',function($join){
      $join->on('suppliers.id','=','inv_isus.supplier_id');
    })
    ->join('inv_yarn_isu_items',function($join){
      $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
    })
    ->join('inv_yarn_items',function($join){
      $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
    })
    ->join('item_accounts',function($join){
      $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
    })
    ->leftJoin('yarncounts',function($join){
      $join->on('yarncounts.id','=','item_accounts.yarncount_id');
    })
    ->leftJoin('yarntypes',function($join){
      $join->on('yarntypes.id','=','item_accounts.yarntype_id');
    })
    ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
    })
    ->join('colors',function($join){
      $join->on('colors.id','=','inv_yarn_items.color_id');
    })
    ->leftJoin(\DB::raw("(
      select 
      inv_yarn_isu_items.id as inv_yarn_isu_item_id,
      abs(sum(inv_yarn_transactions.store_qty)) as trans_out_qty,
      sum(inv_yarn_transactions.store_amount) as trans_out_amount
      from inv_yarn_isu_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
      join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_isus.isu_against_id=102
      and inv_yarn_transactions.trans_type_id=2
      group by inv_yarn_isu_items.id
      ) yarn_trans_out"), "yarn_trans_out.inv_yarn_isu_item_id", "=", "inv_yarn_isu_items.id")
    
    ->when(request('date_from'), function ($q) {
      return $q->where('inv_isus.issue_date', '>=',request('date_from', 0));
    })
    ->when(request('date_to'), function ($q) {
      return $q->where('inv_isus.issue_date', '<=',request('date_to', 0));
    })
    ->when(request('company_id'), function ($q) {
      return $q->where('inv_isus.company_id', '=',request('company_id', 0));
    })
    ->when(request('supplier_id'), function ($q) {
      return $q->where('inv_isus.supplier_id', '=',request('supplier_id', 0));
    })
    ->when(request('store_id'), function ($q) {
      return $q->where('inv_yarn_isu_items.store_id', '=',request('store_id', 0));
    })
    ->orderBy('inv_isus.issue_date','desc')
    ->where([['inv_isus.isu_against_id','=',102]])
    ->whereIn('inv_isus.menu_id',[101,104,107,111])
    ->get([
      'inv_isus.issue_no',
      'inv_isus.isu_basis_id',
      'inv_isus.isu_against_id',
      'inv_isus.issue_date',
      'companies.name as company_name',
      'suppliers.name as supplier_name',
      'inv_yarn_items.lot',
      'inv_yarn_items.brand',
      'colors.name as color_name',
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id as item_account_id',
      'yarncounts.count as yarn_count',
      'yarncounts.symbol',
      'yarntypes.name as yarn_type',
      'uoms.code as uom_code',
      'inv_yarn_isu_items.store_id',
      'inv_yarn_isu_items.qty',
      'inv_yarn_isu_items.amount',
      'inv_yarn_isu_items.rate',
      'yarn_trans_out.trans_out_qty',
      'yarn_trans_out.trans_out_amount',
      'tocompanies.code as to_company_code',
      
    ]);

    $yarndyeingissue=$this->invisu
    ->join('companies',function($join){
      $join->on('companies.id','=','inv_isus.company_id');
    })
    ->leftJoin('companies as tocompanies',function($join){
      $join->on('tocompanies.id','=','inv_isus.to_company_id');
    })
    ->leftJoin('suppliers',function($join){
      $join->on('suppliers.id','=','inv_isus.supplier_id');
    })
    ->join('inv_yarn_isu_items',function($join){
      $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
    })
    ->join('inv_yarn_items',function($join){
      $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
    })
    ->join('item_accounts',function($join){
      $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
    })
    ->leftJoin('yarncounts',function($join){
      $join->on('yarncounts.id','=','item_accounts.yarncount_id');
    })
    ->leftJoin('yarntypes',function($join){
      $join->on('yarntypes.id','=','item_accounts.yarntype_id');
    })
    ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
    })
    ->join('colors',function($join){
      $join->on('colors.id','=','inv_yarn_items.color_id');
    })
    // ->leftJoin('po_yarn_dyeing_items',function($join){
    //   $join->on('po_yarn_dyeing_items.inv_yarn_item_id','=','inv_yarn_items.id'); 
    // })
    // ->join('po_yarn_dyeings',function($join){
    //   $join->on('po_yarn_dyeings.id','=','po_yarn_dyeing_items.po_yarn_dyeing_id');
    // })
    // ->join('po_yarn_dyeing_item_bom_qties',function($join){
    //     $join->on('po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id', '=' , 'po_yarn_dyeing_items.id');
    // })
    ->leftJoin(\DB::raw("(
      select 
      inv_yarn_isu_items.id as inv_yarn_isu_item_id,
      abs(sum(inv_yarn_transactions.store_qty)) as trans_out_qty,
      sum(inv_yarn_transactions.store_amount) as trans_out_amount
      from inv_yarn_isu_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
      join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_isus.isu_against_id=9
      and inv_yarn_transactions.trans_type_id=2
      group by inv_yarn_isu_items.id
      ) yarn_trans_out"), "yarn_trans_out.inv_yarn_isu_item_id", "=", "inv_yarn_isu_items.id")
    
    ->when(request('date_from'), function ($q) {
      return $q->where('inv_isus.issue_date', '>=',request('date_from', 0));
    })
    ->when(request('date_to'), function ($q) {
      return $q->where('inv_isus.issue_date', '<=',request('date_to', 0));
    })
    ->when(request('company_id'), function ($q) {
      return $q->where('inv_isus.company_id', '=',request('company_id', 0));
    })
    ->when(request('supplier_id'), function ($q) {
      return $q->where('inv_isus.supplier_id', '=',request('supplier_id', 0));
    })
    ->when(request('store_id'), function ($q) {
      return $q->where('inv_yarn_isu_items.store_id', '=',request('store_id', 0));
    })
    ->orderBy('inv_isus.issue_date','desc')
    ->where([['inv_isus.isu_against_id','=',9]])
    ->whereIn('inv_isus.menu_id',[101,104,107,111])
    ->get([
      'inv_isus.issue_no',
      'inv_isus.isu_basis_id',
      'inv_isus.isu_against_id',
      'inv_isus.issue_date',
      'companies.name as company_name',
      'suppliers.name as supplier_name',
      'inv_yarn_items.lot',
      'inv_yarn_items.brand',
      'colors.name as color_name',
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id as item_account_id',
      'yarncounts.count as yarn_count',
      'yarncounts.symbol',
      'yarntypes.name as yarn_type',
      'uoms.code as uom_code',
      'inv_yarn_isu_items.store_id',
      'inv_yarn_isu_items.qty',
      'inv_yarn_isu_items.amount',
      'inv_yarn_isu_items.rate',
      'yarn_trans_out.trans_out_qty',
      'yarn_trans_out.trans_out_amount',
      'tocompanies.code as to_company_code'
    ]);

    $results=$independent->concat($yarndyeingissue)->concat($poyarnisu)->all();
    return $results;
  }

  public function issueData(){
    
    $menu=array_prepend(config('bprs.menu'),'-Select-','');
    $invissuebasis=array_prepend(config('bprs.invissuebasis'), '-Select-','');
    $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');             
    $yarnDescription=$this->itemaccount
    ->leftJoin('item_account_ratios',function($join){
      $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
    })
    ->leftJoin('compositions',function($join){
      $join->on('compositions.id','=','item_account_ratios.composition_id');
    })
    ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->leftJoin('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->where([['itemcategories.identity','=',1]])
    ->orderBy('item_account_ratios.ratio','desc')
    ->get([
      'item_accounts.id',
      'compositions.name as composition_name',
      'item_account_ratios.ratio',
    ]);
    $itemaccountArr=array();
    $yarnCompositionArr=array();
    foreach($yarnDescription as $row){
      $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
      $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
    }

    $yarnDropdown=array();
    foreach($itemaccountArr as $key=>$value){
      $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
    }

    $collection=$this->issueHtml();
    foreach ($collection as $rows) {
      $rows['lc_no']=$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv;
      $rows['lc_pi_no']=$rows->lc_no." ; ".$rows->pi_no;
      $rows['issue_no']=$rows->issue_no;
      $rows['isu_basis']=$invissuebasis[$rows->isu_basis_id];
      $rows['store_id']=$store[$rows->store_id];
      $rows['issue_against']=$menu[$rows->isu_against_id];
      $rows['trans_date']=date('d-M-Y',strtotime($rows->issue_date));
      $rows['count_name']=$rows->count."/".$rows->symbol;
      $rows['composition']=$yarnDropdown[$rows->item_account_id];
      
      
      if($rows['isu_basis_id']==11 && $rows['isu_against_id']==0){
        $rows['issue_rate']=number_format(0,2);
        $rows['issue_qty']=number_format(0,2);
        $rows['issue_amount']=number_format(0,2);
        $rows['purchase_rtn_qty']=number_format($rows->qty,2);
        $rows['purchase_rtn_rate']=number_format($rows->rate,2);
        $rows['purchase_rtn_amount']=number_format($rows->amount,2);
        $rows['trans_out_qty']=number_format(0,2);
        $rows['trans_out_amount']=number_format(0,2);
      }
      elseif($rows['isu_basis_id']==9 && $rows['isu_against_id']==0){
        $rows['issue_rate']=number_format(0,2);
        $rows['issue_qty']=number_format(0,2);
        $rows['issue_amount']=number_format(0,2);
        $rows['purchase_rtn_qty']=number_format(0,2);
        $rows['purchase_rtn_rate']=number_format(0,2);
        $rows['purchase_rtn_amount']=number_format(0,2);
        $rows['trans_out_qty']=number_format($rows->trans_out_qty,2);
        $rows['trans_out_amount']=number_format($rows->trans_out_amount,2);
      }
     else {
        $rows['issue_rate']=number_format($rows->rate,2);
        $rows['issue_qty']=number_format($rows->qty,2);
        $rows['issue_amount']=number_format($rows->amount,2);
        $rows['purchase_rtn_qty']=number_format(0,2);
        $rows['purchase_rtn_rate']=number_format(0,2);
        $rows['purchase_rtn_amount']=number_format(0,2);
        $rows['trans_out_qty']=number_format(0,2);
        $rows['trans_out_amount']=number_format(0,2);
     }
     
      $rows['uom']='Kg';
    }
    echo json_encode($collection);
  }

  public function IsuRegular(){
    $date_from=request('date_from',0);
    $date_to=request('date_to',0);
    $company_id=request('company_id',0);
    $supplier_id=request('supplier_id',0);
    $imp_lc_id=request('imp_lc_id',0);
    $store_id=request('store_id',0);
    $datefrom=null;
    $dateto=null;
    $company=null;
    $supplier=null;
    $store=null;
    if($date_from){
			$datefrom=" and inv_isus.issue_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and inv_isus.issue_date<='".$date_to."' ";
		}
    if($company_id){
			$company=" and inv_isus.company_id = $company_id ";
		}
    if($supplier_id){
			$supplier=" and inv_isus.supplier_id = $supplier_id ";
		}
    if($store_id){
			$store=" and inv_yarn_isu_items.store_id = $store_id ";
		}
    $menu=array_prepend(config('bprs.menu'),'-Select-','');
    $yarnstore = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
    $yarnDescription=$this->itemaccount
    ->leftJoin('item_account_ratios',function($join){
      $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
    })
    ->leftJoin('compositions',function($join){
      $join->on('compositions.id','=','item_account_ratios.composition_id');
    })
    ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->leftJoin('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->where([['itemcategories.identity','=',1]])
    ->orderBy('item_account_ratios.ratio','desc')
    ->get([
      'item_accounts.id',
      'compositions.name as composition_name',
      'item_account_ratios.ratio',
    ]);

    $itemaccountArr=array();
    $yarnCompositionArr=array();
    foreach($yarnDescription as $row){
    $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
      $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
    }

    $yarnDropdown=array();
    foreach($itemaccountArr as $key=>$value){
      $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
    }

    $rows = collect(
      \DB::select("
        select
        m.company_name,
        m.sale_order_no,
        m.style_ref,
        m.buyer_name,
        m.issue_no,
        m.issue_date,
        m.isu_against_id,
        m.lot,
        m.brand,
        m.color_name,
        m.itemcategory_name,
        m.itemclass_name,
        m.item_account_id,
        m.count,
        m.symbol,
        m.yarn_type,
        m.uom_code,
        m.supplier_name,
        m.inv_yarn_isu_item_id,
        m.store_id,
        m.qty,
        m.amount,
        m.rate
        from(select 
        case 
            when  pl_sales_orders.company_name is null then po_sales_orders.company_name_po 
            else pl_sales_orders.company_name
            end as company_name,
            case 
            when  pl_sales_orders.sale_order_no is null then po_sales_orders.sale_order_no_po 
            else pl_sales_orders.sale_order_no
            end as sale_order_no,
            case 
            when  pl_sales_orders.style_ref is null then po_sales_orders.style_ref_po 
            else pl_sales_orders.style_ref
            end as style_ref,
            case 
            when  pl_sales_orders.buyer_name is null then po_sales_orders.buyer_name_po 
            else pl_sales_orders.buyer_name
            end as buyer_name,
            inv_isus.issue_no,
            inv_isus.issue_date,
            inv_isus.isu_against_id,
            inv_yarn_items.lot,
            inv_yarn_items.brand,
            colors.name as color_name,
            itemcategories.name as itemcategory_name,
            itemclasses.name as itemclass_name,
            item_accounts.id as item_account_id,
            yarncounts.count,
            yarncounts.symbol,
            yarntypes.name as yarn_type,
            uoms.code as uom_code,
            suppliers.name as supplier_name,
            inv_yarn_isu_items.id as inv_yarn_isu_item_id,
            inv_yarn_isu_items.store_id,
            inv_yarn_isu_items.qty,
            inv_yarn_isu_items.amount,
            inv_yarn_isu_items.rate
        from inv_isus 
        inner join suppliers on suppliers.id = inv_isus.supplier_id 
        inner join inv_yarn_isu_items on inv_yarn_isu_items.inv_isu_id = inv_isus.id 
        inner join inv_yarn_items on inv_yarn_items.id = inv_yarn_isu_items.inv_yarn_item_id 
        inner join item_accounts on inv_yarn_items.item_account_id = item_accounts.id 
        left join yarncounts on yarncounts.id = item_accounts.yarncount_id 
        left join yarntypes on yarntypes.id = item_accounts.yarntype_id 
        left join itemclasses on itemclasses.id = item_accounts.itemclass_id 
        inner join itemcategories on itemcategories.id = item_accounts.itemcategory_id 
        inner join uoms on uoms.id = item_accounts.uom_id 
        inner join colors on colors.id = inv_yarn_items.color_id 
        left join rq_yarn_items on rq_yarn_items.id = inv_yarn_isu_items.rq_yarn_item_id 
        left join rq_yarn_fabrications on rq_yarn_fabrications.id = rq_yarn_items.rq_yarn_fabrication_id 
        left join rq_yarns on rq_yarns.id = rq_yarn_fabrications.rq_yarn_id 
        left join (
            select 
            pl_knit_items.id,
            sales_orders.sale_order_no,
            companies.code  as company_name,
            styles.style_ref,
            buyers.name as buyer_name,
            style_fabrications.autoyarn_id,
            budget_fabrics.gsm_weight,
            colorranges.name as colorrange_name
            from pl_knit_items
            join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
            join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
            join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join companies on companies.id=jobs.company_id
            join buyers on buyers.id=styles.buyer_id
            join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
            join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
            join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            left join colorranges on colorranges.id=pl_knit_items.colorrange_id
            ) pl_sales_orders on pl_sales_orders.id = rq_yarn_fabrications.pl_knit_item_id 
        left join (select po_knit_service_item_qties.id,
            sales_orders.sale_order_no as sale_order_no_po,
            styles.style_ref as style_ref_po,
            buyers.name  as buyer_name_po,
            companies.code  as company_name_po,
            style_fabrications.autoyarn_id as autoyarn_id_po,
            budget_fabrics.gsm_weight as gsm_weight_po,
            colorranges.name as colorrange_name_po
            from po_knit_service_item_qties
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join companies on companies.id=jobs.company_id
            join buyers on buyers.id=styles.buyer_id
            join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
            join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
            join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
            ) po_sales_orders on po_sales_orders.id = rq_yarn_fabrications.po_knit_service_item_qty_id 
        where 1=1 $datefrom $dateto $company $supplier $store
        and (inv_isus.isu_basis_id = 1) 
        and inv_isus.deleted_at is null 
        ) m
        order by m.company_name,m.issue_no
      ")
    )
    ->map(function($rows) use($yarnDropdown,$menu,$yarnstore) {
        $rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));
        $rows->yarn_count=$rows->count."/".$rows->symbol;
        $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
        $rows->issue_against=$menu[$rows->isu_against_id];
        $rows->store_id=isset($yarnstore[$rows->store_id])?$yarnstore[$rows->store_id]:'';
        $rows->issue_rate=number_format($rows->rate,2);
        $rows->issue_qty=number_format($rows->qty,2);
        $rows->issue_amount=number_format($rows->amount,2);
        return $rows;
    }) ;
    
    echo json_encode($rows);
  }

  public function issueTransOut(){
    $date_from=request('date_from',0);
    $date_to=request('date_to',0);
    $company_id=request('company_id',0);
    $supplier_id=request('supplier_id',0);
    $imp_lc_id=request('imp_lc_id',0);
    $store_id=request('store_id',0);
    $menu=array_prepend(config('bprs.menu'),'-Select-','');
    $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
    $yarnDescription=$this->itemaccount
    ->leftJoin('item_account_ratios',function($join){
      $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
    })
    ->leftJoin('compositions',function($join){
      $join->on('compositions.id','=','item_account_ratios.composition_id');
    })
    ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->leftJoin('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->where([['itemcategories.identity','=',1]])
    ->orderBy('item_account_ratios.ratio','desc')
    ->get([
      'item_accounts.id',
      'compositions.name as composition_name',
      'item_account_ratios.ratio',
    ]);

    $itemaccountArr=array();
    $yarnCompositionArr=array();
    foreach($yarnDescription as $row){
      $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
      $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
    }

    $yarnDropdown=array();
    foreach($itemaccountArr as $key=>$value){
      $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
    }

    $rows = $this->invisu
    ->join('companies',function($join){
      $join->on('companies.id','=','inv_isus.company_id');
    })
    ->leftJoin('companies as tocompanies',function($join){
      $join->on('tocompanies.id','=','inv_isus.to_company_id');
    })
    ->join('inv_yarn_isu_items',function($join){
      $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
    })
    // ->join('inv_yarn_transactions',function($join){
    //   $join->on('inv_yarn_transactions.inv_yarn_isu_item_id','=','inv_yarn_isu_items.id');
    // })
    // ->leftJoin('suppliers',function($join){
    //   $join->on('suppliers.id','=','inv_yarn_transactions.supplier_id');
    // })
    ->join('inv_yarn_items',function($join){
      $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
    })
    ->join('item_accounts',function($join){
      $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
    })
    ->leftJoin('yarncounts',function($join){
      $join->on('yarncounts.id','=','item_accounts.yarncount_id');
    })
    ->leftJoin('yarntypes',function($join){
      $join->on('yarntypes.id','=','item_accounts.yarntype_id');
    })
    ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
    })
    ->join('colors',function($join){
      $join->on('colors.id','=','inv_yarn_items.color_id');
    })
    ->when(request('date_from'), function ($q) {
      return $q->where('inv_isus.issue_date', '>=',request('date_from', 0));
    })
    ->when(request('date_to'), function ($q) {
      return $q->where('inv_isus.issue_date', '<=',request('date_to', 0));
    })
    ->when(request('company_id'), function ($q) {
      return $q->where('inv_isus.company_id', '=',request('company_id', 0));
    })
    ->when(request('supplier_id'), function ($q) {
      return $q->where('inv_isus.supplier_id', '=',request('supplier_id', 0));
    })
    ->when(request('store_id'), function ($q) {
      return $q->where('inv_yarn_isu_items.store_id', '=',request('store_id', 0));
    })
    ->where([['inv_isus.menu_id','=',107]])
    //->where([['inv_isus.menu_id','=',107]])
    ->orderBy('inv_isus.issue_date','desc')
    ->orderBy('inv_yarn_isu_items.id','desc')
    ->get([
      'inv_isus.issue_no',
      'inv_isus.isu_basis_id',
      'inv_isus.isu_against_id',
      'inv_isus.issue_date',
      'companies.name as company_name',
      //'suppliers.name as supplier_name',
      'inv_yarn_isu_items.id as inv_yarn_isu_item_id',
      'inv_yarn_isu_items.store_id',
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
      'tocompanies.code as to_company_code',
      'inv_yarn_isu_items.qty',
      'inv_yarn_isu_items.rate',
      'inv_yarn_isu_items.amount',
      //'inv_yarn_transactions.store_qty',
      //'inv_yarn_transactions.store_rate',
      //'inv_yarn_transactions.store_amount',
    ])
    ->map(function($rows) use($yarnDropdown,$store) {
      $rows->yarn_count=$rows->count."/".$rows->symbol;
      $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
      $rows->store_id=$store[$rows->store_id];
      $rows->qty=number_format($rows->qty,2);
      $rows->rate=number_format($rows->rate,2);
      $rows->amount=number_format($rows->amount,2);
      $rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));
      return $rows;
    });
    echo json_encode($rows);
  }

  public function IssuePurRtn(){
    $date_from=request('date_from',0);
    $date_to=request('date_to',0);
    $company_id=request('company_id',0);
    $supplier_id=request('supplier_id',0);
    $imp_lc_id=request('imp_lc_id',0);
    $store_id=request('store_id',0);
    $menu=array_prepend(config('bprs.menu'),'-Select-','');
    $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
    $yarnDescription=$this->itemaccount
    ->leftJoin('item_account_ratios',function($join){
      $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
    })
    ->leftJoin('compositions',function($join){
      $join->on('compositions.id','=','item_account_ratios.composition_id');
    })
    ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->leftJoin('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->where([['itemcategories.identity','=',1]])
    ->orderBy('item_account_ratios.ratio','desc')
    ->get([
      'item_accounts.id',
      'compositions.name as composition_name',
      'item_account_ratios.ratio',
    ]);

    $itemaccountArr=array();
    $yarnCompositionArr=array();
    foreach($yarnDescription as $row){
      $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
      $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
    }

    $yarnDropdown=array();
    foreach($itemaccountArr as $key=>$value){
      $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
    }

    $rows = $this->invisu
    ->join('companies',function($join){
      $join->on('companies.id','=','inv_isus.company_id');
    })
    ->leftJoin('suppliers',function($join){
      $join->on('suppliers.id','=','inv_isus.supplier_id');
    })
    ->join('inv_yarn_isu_items',function($join){
      $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
    })
    // ->join('inv_yarn_transactions',function($join){
    //   $join->on('inv_yarn_transactions.inv_yarn_isu_item_id','=','inv_yarn_isu_items.id');
    // })
    ->join('inv_yarn_items',function($join){
      $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
    })
    ->join('item_accounts',function($join){
      $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
    })
    ->leftJoin('yarncounts',function($join){
      $join->on('yarncounts.id','=','item_accounts.yarncount_id');
    })
    ->leftJoin('yarntypes',function($join){
      $join->on('yarntypes.id','=','item_accounts.yarntype_id');
    })
    ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
    })
    ->join('colors',function($join){
      $join->on('colors.id','=','inv_yarn_items.color_id');
    })
    ->when(request('date_from'), function ($q) {
      return $q->where('inv_isus.issue_date', '>=',request('date_from', 0));
    })
    ->when(request('date_to'), function ($q) {
      return $q->where('inv_isus.issue_date', '<=',request('date_to', 0));
    })
    ->when(request('company_id'), function ($q) {
      return $q->where('inv_isus.company_id', '=',request('company_id', 0));
    })
    ->when(request('supplier_id'), function ($q) {
      return $q->where('inv_isus.supplier_id', '=',request('supplier_id', 0));
    })
    ->when(request('store_id'), function ($q) {
      return $q->where('inv_yarn_isu_items.store_id', '=',request('store_id', 0));
    })
    ->where([['inv_isus.menu_id','=',111]])
    ->orderBy('inv_isus.issue_date','desc')
    ->orderBy('inv_yarn_isu_items.id','desc')
    ->get([
      'inv_isus.issue_no',
      'inv_isus.isu_basis_id',
      'inv_isus.isu_against_id',
      'inv_isus.issue_date',
      'companies.name as company_name',
      'suppliers.name as supplier_name',
      'inv_yarn_isu_items.id as inv_yarn_isu_item_id',
      'inv_yarn_isu_items.store_id',
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
      'inv_yarn_isu_items.qty',
      'inv_yarn_isu_items.rate',
      'inv_yarn_isu_items.amount',
      //'inv_yarn_transactions.store_qty',
      //'inv_yarn_transactions.store_rate',
      //'inv_yarn_transactions.store_amount',
    ])
    ->map(function($rows) use($yarnDropdown,$store) {
      $rows->yarn_count=$rows->count."/".$rows->symbol;
      $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
      $rows->store_id=$store[$rows->store_id];
      $rows->qty=number_format($rows->qty,2);
      $rows->rate=number_format($rows->rate,2);
      $rows->amount=number_format($rows->amount,2);
      $rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));
      return $rows;
    });
    echo json_encode($rows);
  }

  public function IsuMrrPo(){
    $inv_yarn_isu_item_id=request('inv_yarn_isu_item_id',0);

      $rows=collect(
        \DB::select("
          select 
          inv_yarn_isu_items.id as inv_yarn_isu_item_id,
          inv_rcvs.receive_no,
          po_yarns.pi_no,
          po_yarns.po_no,
          po_yarns.exch_rate,
          avg(po_yarn_items.rate) as po_rate,
          abs(sum(inv_yarn_transactions.store_qty)) as issue_qty,
          sum(inv_yarn_transactions.store_amount) as store_amount
          from inv_yarn_isu_items
          join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
          join inv_yarn_rcv_items on inv_yarn_rcv_items.id=inv_yarn_transactions.inv_yarn_rcv_item_id
          join po_yarn_items on po_yarn_items.id=inv_yarn_rcv_items.po_yarn_item_id
          join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
          --join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
          --join imp_lcs on imp_lcs.id = imp_lc_pos.imp_lc_id and imp_lcs.menu_id=3
          --join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
          join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
          join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
          where inv_yarn_isu_items.id=?
          and inv_yarn_transactions.deleted_at is null
          and inv_yarn_isu_items.deleted_at is null
          and inv_yarn_transactions.trans_type_id=2
          
          group by 
          inv_yarn_isu_items.id,
          inv_rcvs.receive_no,
          po_yarns.pi_no,
          po_yarns.po_no,
          po_yarns.exch_rate
        ",[$inv_yarn_isu_item_id])
      )->map(function($rows){
        $rows->po_amount=$rows->issue_qty*$rows->po_rate;
        $rows->store_rate=0;
        if($rows->issue_qty){
          $rows->store_rate=($rows->store_amount/$rows->issue_qty)*1;
        }
        $rows->po_amount=number_format($rows->po_amount,2);
        $rows->issue_qty=number_format($rows->issue_qty,2);
        $rows->store_amount=number_format($rows->store_amount,2);
        $rows->store_rate=number_format($rows->store_rate,2);
        $rows->po_rate=number_format($rows->po_rate,2);
        return $rows;
      });
  
      echo json_encode($rows);
    
    
  }

}
// ->leftJoin(\DB::raw("(
    //     select 
    //       imp_lc_pos.purchase_order_id,
    //       imp_lcs.id as imp_lc_id,
    //       imp_lcs.lc_no_i,
    //       imp_lcs.lc_no_ii,
    //       imp_lcs.lc_no_iii,
    //       imp_lcs.lc_no_iv
    //     from
    //       imp_lcs
    //       join imp_lc_pos on imp_lc_pos.imp_lc_id=imp_lcs.id and imp_lcs.menu_id=9
    //       join po_yarn_dyeings on imp_lc_pos.purchase_order_id=po_yarn_dyeings.id
    //       join po_yarn_dyeing_items on po_yarn_dyeing_items.po_yarn_dyeing_id=po_yarn_dyeings.id  
    //       join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id = po_yarn_dyeing_items.id   
    //       join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
    //       join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_isu_item_id=inv_yarn_isu_items.id
    //       join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
    //       join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
    //     where inv_rcvs.receive_date>='".$date_from."'
    //       and inv_rcvs.receive_date<='".$date_to."'
    //       and inv_yarn_rcv_items.deleted_at is null
    //       and inv_rcvs.deleted_at is null
    //     group by 
    //       imp_lc_pos.purchase_order_id,
    //       imp_lcs.id,
    //       imp_lcs.lc_no_i,
    //       imp_lcs.lc_no_ii,
    //       imp_lcs.lc_no_iii,
    //       imp_lcs.lc_no_iv
    //   ) yarn_isu_lc"), "yarn_isu_lc.purchase_order_id", "=", "po_yarn_dyeings.id")