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

class DyeIssueReceiveController extends Controller
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
  )
  {
      	$this->invrcv = $invrcv;
      	$this->company=$company;
    	$this->invisu = $invisu;
    	$this->invdyechemrcv = $invdyechemrcv;
    	$this->supplier = $supplier;
    	$this->itemaccount=$itemaccount;
    	$this->store=$store;
    	$this->itemcategory=$itemcategory;
    	$this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {
    $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
    $company = array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
    $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
    
    return Template::loadView('Report.Inventory.DyeIssueReceive',['supplier'=>$supplier,'company'=>$company,'store'=>$store]);
  }
	public function html() {
      $company_id=request('company_id',0);
      $supplier_id=request('supplier_id',0);
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $store_id=request('store_id',0);


      $invreceivebasis=array_prepend(config('bprs.invreceivebasis'),'-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');

      $transIn=$this->invrcv
        ->join('inv_dye_chem_rcvs',function($join){
            $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','inv_rcvs.company_id');
        })
        ->join('suppliers',function($join){
            $join->on('suppliers.id','=','inv_rcvs.supplier_id');
        })
        ->join('inv_dye_chem_rcv_items',function($join){
            $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id')
            ->whereNull('inv_dye_chem_rcv_items.deleted_at');
            // $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id')
            // ->where([['inv_rcvs.menu_id','=',200]]);
        })
        ->leftJoin('inv_dye_chem_transactions',function($join){
            $join->on('inv_dye_chem_transactions.inv_dye_chem_rcv_item_id','=','inv_dye_chem_rcv_items.id')
            ->whereNull('inv_dye_chem_transactions.deleted_at')
            ->where([['inv_dye_chem_transactions.trans_type_id','=',1]]);
            $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id')
            ->whereNull('inv_dye_chem_rcv_items.deleted_at');
            $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id')
            ->whereNull('inv_rcvs.deleted_at')
            ->where([['inv_rcvs.receive_basis_id','=',9]]);
        })
        ->leftJoin('inv_dye_chem_transactions as issue_rtn',function($join){
            $join->on('issue_rtn.inv_dye_chem_rcv_item_id','=','inv_dye_chem_rcv_items.id')
            ->where([['issue_rtn.trans_type_id','=',1]])
            ->whereNull('issue_rtn.deleted_at');
            $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id')
            ->whereNull('inv_dye_chem_rcv_items.deleted_at');
            $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id')
            ->whereNull('inv_rcvs.deleted_at')
            ->where([['inv_rcvs.receive_basis_id','=',4]]);
        })
        ->leftJoin('companies as trans_in_company',function($join){
            $join->on('trans_in_company.id','=','inv_rcvs.from_company_id');
        })
        ->leftJoin('po_dye_chem_items',function($join){
            $join->on('po_dye_chem_items.id','=','inv_dye_chem_rcv_items.po_dye_chem_item_id');
        })
        ->leftJoin('po_dye_chems',function($join){
            $join->on('po_dye_chems.id','=','po_dye_chem_items.po_dye_chem_id');
        })
        ->leftJoin('inv_pur_req_items', function($join){
            $join->on('inv_pur_req_items.id', '=', 'inv_dye_chem_rcv_items.inv_pur_req_item_id');
        })
        ->leftJoin('inv_pur_reqs', function($join){
            $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
        })
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_dye_chem_rcv_items.item_account_id');
        })
        ->leftJoin('itemclasses', function($join){
            $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories', function($join){
            $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
            $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_dye_chems.currency_id');
        })
        ->leftJoin('stores',function($join){
            $join->on('stores.id','=','inv_dye_chem_rcv_items.store_id');
        })
        ->when(request('supplier_id'), function ($q)  {
            return $q->where('inv_rcvs.supplier_id', '=', request('supplier_id',0));
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('inv_rcvs.receive_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('inv_rcvs.receive_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) {
            return $q->where('inv_rcvs.company_id', '=',  request('company_id',0));
        })
        ->when(request('store_id'), function ($q) {
            return $q->where('inv_dye_chem_rcv_items.store_id', '=',  request('store_id',0));
        })
        ->whereIn('itemcategories.identity',[7,8])
        ->whereIn('inv_rcvs.menu_id',[200,214,216])
        //->where(['inv_rcvs.receive_basis_id','=',9]) 
        ->orderBy('itemcategories.id')
        ->orderBy('itemclasses.id')
        ->orderBy('item_accounts.item_description')
        ->get([
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.item_description',
            'item_accounts.specification',
            'uoms.code as uom_code',

            'po_dye_chems.po_no',
            'po_dye_chems.pi_no',
            'po_dye_chems.exch_rate',
            'inv_pur_reqs.requisition_no as rq_no',
            'item_accounts.id as item_account_id',
            'item_accounts.sub_class_name',
            'currencies.code as currency_code',
            'stores.name as store_name',
            'inv_dye_chem_rcv_items.id',
            'inv_dye_chem_rcv_items.batch',
            'inv_dye_chem_rcv_items.qty as pur_qty',
            'inv_dye_chem_rcv_items.rate as po_rate',
            'inv_dye_chem_rcv_items.amount as po_amount',

            'inv_dye_chem_rcv_items.store_rate',
            'inv_dye_chem_rcv_items.store_amount',

            'inv_rcvs.receive_basis_id',
            'inv_rcvs.receive_no',
            'inv_rcvs.supplier_id',
            'inv_rcvs.company_id',
            'inv_rcvs.from_company_id',
            'inv_rcvs.receive_date',
            'inv_rcvs.receive_against_id',
            'po_dye_chems.exch_rate',
            'po_dye_chems.currency_id',
            'suppliers.name as supplier_code',
            'currencies.code as currency_code',
            'trans_in_company.code as from_company_code',
            //'inv_dye_chem_rcv_items.company_id as from_company_id',
            'inv_dye_chem_transactions.store_qty as trans_in_qty',
            'inv_dye_chem_transactions.store_amount as trans_amount',
            'issue_rtn.store_qty as issue_rtn_qty',
            'issue_rtn.store_rate as issue_rtn_rate',
            'issue_rtn.store_amount as issue_rtn_amount',

        ])
        ->map(function($transIn) use($invreceivebasis) {

            $transIn->sum_pur_qty=$transIn->pur_qty;
            $transIn->sum_po_amount=$transIn->po_amount;
            $transIn->sum_store_amount=$transIn->store_amount;
            $transIn->sum_trans_in_qty=$transIn->trans_in_qty;
            $transIn->sum_trans_amount=$transIn->trans_amount;
            $transIn->sum_issue_rtn_qty=$transIn->issue_rtn_qty;
            $transIn->sum_issue_rtn_amount=$transIn->issue_rtn_amount;
            
            $transIn->item_desc=$transIn->item_description.", ".$transIn->specification;
            $transIn->ref_type=$invreceivebasis[$transIn->receive_basis_id];
            //$transIn->receive_against_id=$menu[$transIn->receive_against_id];
            $transIn->trans_date=date('d-M-Y',strtotime($transIn->receive_date));
            if($transIn->receive_against_id==0 || $transIn->receive_against_id==109){
                $transIn->po_currency='BDT';
                $transIn->exch_rate=1;
            }
            if($transIn->receive_against_id==7){
                $transIn->po_currency=$transIn->currency_code;
            }
        //$transIn->ref_type="Purchase";
            if ($transIn->receive_basis_id==9) {
                
                $transIn->pur_qty=number_format(0,2);
                $transIn->po_rate=number_format(0,4);
                $transIn->po_amount=number_format(0,2);
                $transIn->store_amount=number_format(0,2);
                $transIn->trans_in_qty=number_format($transIn->trans_in_qty,0);
                $transIn->trans_amount=number_format($transIn->trans_amount,4);
                $transIn->issue_rtn_qty=number_format(0,0);
                $transIn->issue_rtn_rate=number_format(0,4);
                $transIn->issue_rtn_amount=number_format(0,2);
            }
            elseif ($transIn->receive_basis_id==4) {
                $transIn->pur_qty=number_format(0,2);
                $transIn->po_rate=number_format(0,4);
                $transIn->po_amount=number_format(0,2);
                $transIn->store_amount=number_format(0,2);
                $transIn->trans_in_qty=number_format(0,0);
                $transIn->trans_amount=number_format(0,4);
                $transIn->issue_rtn_qty=number_format($transIn->issue_rtn_qty,0);
                $transIn->issue_rtn_rate=number_format($transIn->issue_rtn_rate,4);
                $transIn->issue_rtn_amount=number_format($transIn->issue_rtn_amount,2);
            }
            else{
                $transIn->pur_qty=number_format($transIn->pur_qty,2);
                $transIn->po_rate=number_format($transIn->po_rate,4);
                $transIn->po_amount=number_format($transIn->po_amount,2);
                $transIn->store_amount=number_format($transIn->store_amount,0);
                $transIn->trans_in_qty=number_format(0,0);
                $transIn->trans_amount=number_format(0,4);
                $transIn->issue_rtn_qty=number_format(0,0);
                $transIn->issue_rtn_rate=number_format(0,4);
                $transIn->issue_rtn_amount=number_format(0,2);
            }

            return $transIn;
      });
     // echo json_encode($rows);\
     //$concatenated = $receive->concat($transIn)->all();
    //echo json_encode($transIn);
    return $transIn;
    }

    public function reportData(){
        return response()->json($this->html());
    }

    public function issueData() {
        $company_id=request('company_id',0);
        //$supplier_id=request('supplier_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company = array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'), '-Select-','');
        //$invreceivebasis=array_prepend(config('bprs.invreceivebasis'),'-Select-','');
        $menu=array_prepend(config('bprs.menu'),'-Select-','');
  
        $rows = $this->invisu
        ->join('companies',function($join){
            $join->on('companies.id','=','inv_isus.company_id');
        })
        ->join('inv_dye_chem_isu_items',function($join){
            $join->on('inv_dye_chem_isu_items.inv_isu_id','=','inv_isus.id');
        })
        ->leftJoin(\DB::raw("(
            select 
            inv_isus.id as inv_isu_id,
            inv_dye_chem_isu_items.id as inv_dye_chem_isu_item_id,
            abs(sum(inv_dye_chem_transactions.store_qty)) as store_qty,
            avg(inv_dye_chem_transactions.store_rate) as store_rate,
            sum(inv_dye_chem_transactions.store_amount) as store_amount
            from inv_isus
            join inv_dye_chem_isu_items
             on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
            join inv_dye_chem_transactions
             on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
            where 
            inv_isus.issue_date>='".$date_from."' 
            and inv_isus.issue_date<='".$date_to."'
            and inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=2
            group by inv_isus.id,
            inv_dye_chem_isu_items.id
        ) trans_out"), "trans_out.inv_dye_chem_isu_item_id", "=", "inv_dye_chem_isu_items.id")
        ->leftJoin(\DB::raw("(
            select 
            inv_isus.id as inv_isu_id,
            inv_dye_chem_isu_items.id as inv_dye_chem_isu_item_id,
            abs(sum(inv_dye_chem_transactions.store_qty)) as other_loan_qty,
            avg(inv_dye_chem_transactions.store_rate) as other_loan_rate,
            sum(inv_dye_chem_transactions.store_amount) as other_loan_amount
            from inv_isus
            join inv_dye_chem_isu_items
            on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
            join inv_dye_chem_transactions
            on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
            join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id=inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
            join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.id=inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id
            where 
            inv_isus.issue_date>='".$date_from."' 
            and inv_isus.issue_date<='".$date_to."'
            and inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=2
            and inv_isus.ISU_AGAINST_ID=211
            and inv_dye_chem_isu_rqs.RQ_BASIS_ID in (7,8)
        group by inv_isus.id,
        inv_dye_chem_isu_items.id
        ) other_rq"), "other_rq.inv_dye_chem_isu_item_id", "=", "inv_dye_chem_isu_items.id")
        ->leftJoin(\DB::raw("(
            select 
            inv_isus.id as inv_isu_id,
            inv_dye_chem_isu_items.id as inv_dye_chem_isu_item_id,
            abs(sum(inv_dye_chem_transactions.store_qty)) as loan_qty,
            avg(inv_dye_chem_transactions.store_rate) as loan_rate,
            sum(inv_dye_chem_transactions.store_amount) as loan_amount
            from inv_isus
            join inv_dye_chem_isu_items
            on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
            join inv_dye_chem_transactions
            on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
            join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id=inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
            join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.id=inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id
            where 
            inv_isus.issue_date>='".$date_from."' 
            and inv_isus.issue_date<='".$date_to."'
            and inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=2
            and inv_isus.ISU_AGAINST_ID=211
            and inv_dye_chem_isu_rqs.RQ_BASIS_ID=5
        group by inv_isus.id,
        inv_dye_chem_isu_items.id
        ) loan_rq"), "loan_rq.inv_dye_chem_isu_item_id", "=", "inv_dye_chem_isu_items.id")
        ->leftJoin(\DB::raw("(
            select 
            inv_isus.id as inv_isu_id,
            inv_dye_chem_isu_items.id as inv_dye_chem_isu_item_id,
            abs(sum(inv_dye_chem_transactions.store_qty)) as machine_wash_qty,
            avg(inv_dye_chem_transactions.store_rate) as machine_wash_rate,
            sum(inv_dye_chem_transactions.store_amount) as machine_wash_amount
            from inv_isus
            join inv_dye_chem_isu_items
            on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
            join inv_dye_chem_transactions
            on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
            join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id=inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
            join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.id=inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id
            where 
            inv_isus.issue_date>='".$date_from."' 
            and inv_isus.issue_date<='".$date_to."'
            and inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=2
            and inv_isus.ISU_AGAINST_ID=211
            and inv_dye_chem_isu_rqs.RQ_BASIS_ID in (6)
        group by inv_isus.id,
        inv_dye_chem_isu_items.id
        ) machine_wash_rq"), "machine_wash_rq.inv_dye_chem_isu_item_id", "=", "inv_dye_chem_isu_items.id")
        ->join('item_accounts',function($join){
            $join->on('inv_dye_chem_isu_items.item_account_id','=','item_accounts.id');
        })
        ->join('itemclasses', function($join){
            $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->join('itemcategories', function($join){
            $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
            $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->leftJoin('stores',function($join){
            $join->on('stores.id','=','inv_dye_chem_isu_items.store_id');
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('inv_isus.issue_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('inv_isus.issue_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) {
            return $q->where('inv_isus.company_id', '=',  request('company_id',0));
        })
        ->when(request('store_id'), function ($q) {
            return $q->where('inv_dye_chem_isu_items.store_id', '=',  request('store_id',0));
        })
        //->whereIn('itemcategories.identity',[7,8])
        ->whereIn('inv_isus.menu_id',[212,213,215])
        //->where(['inv_rcvs.receive_basis_id','=',9])
        ->orderBy('itemcategories.id')
        ->orderBy('itemclasses.id')
        ->orderBy('item_accounts.item_description')
        ->get([
            'item_accounts.id as item_account_id',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.item_description',
            'item_accounts.sub_class_name',
            'item_accounts.specification',
            'inv_dye_chem_isu_items.id',
            'inv_dye_chem_isu_items.qty as consumed_qty',
            'inv_dye_chem_isu_items.rate as po_rate',
            'inv_dye_chem_isu_items.amount as po_amount',
            'inv_isus.id as inv_isu_id',
            'inv_isus.isu_basis_id',
            'inv_isus.issue_no',
            'inv_isus.company_id',
            'inv_isus.to_company_id',
            'inv_isus.issue_date',
            'inv_isus.isu_against_id',

            'stores.name as store_name',
            'uoms.code as uom_code',
            'companies.code as company_code',
            'trans_out.store_qty',
            'trans_out.store_rate',
            'trans_out.store_amount',
            'other_rq.other_loan_qty',
            'other_rq.other_loan_rate',
            'other_rq.other_loan_amount',
            'loan_rq.loan_qty',
            'loan_rq.loan_rate',
            'loan_rq.loan_amount',
            'machine_wash_rq.machine_wash_qty',
            'machine_wash_rq.machine_wash_rate',
            'machine_wash_rq.machine_wash_amount',
        ])
        ->map(function($rows) use($company){
            $rows->item_desc=$rows->item_description.', '.$rows->specification;
            $rows->to_company_code=$company[$rows->to_company_id];
            if ($rows->isu_basis_id==1 && $rows->isu_against_id==211) {
                $rows->issue_type="Loan Return";
                $rows->loan_qty=number_format($rows->loan_qty,2);
                $rows->loan_rate=number_format($rows->loan_rate,4);
                $rows->loan_amount=number_format($rows->loan_amount,2);
                $rows->other_loan_qty=number_format($rows->other_loan_qty,2);
                $rows->other_loan_rate=number_format($rows->other_loan_rate,4);
                $rows->other_loan_amount=number_format($rows->other_loan_amount,2);
                $rows->machine_wash_qty=number_format($rows->machine_wash_qty,2);
                $rows->machine_wash_rate=number_format($rows->machine_wash_rate,4);
                $rows->machine_wash_amount=number_format($rows->machine_wash_amount,2);
                $rows->po_rate=number_format(0,4);
                $rows->po_amount=number_format(0,2);
                $rows->trans_out_qty=number_format(0,0);
                $rows->trans_amount=number_format(0,4);
                $rows->purchase_rtn_qty=number_format(0,0);
                $rows->purchase_rtn_rate=number_format(0,4);
                $rows->purchase_rtn_amount=number_format(0,2);
                $rows->consumed_qty=number_format(0,2);
            }
            elseif($rows->isu_basis_id==9 && $rows->isu_against_id==0){
                $rows->issue_type="Transfer Out";
                $rows->consumed_qty=number_format(0,2);
                $rows->po_rate=number_format(0,4);
                $rows->po_amount=number_format(0,2);
                $rows->trans_out_qty=number_format($rows->store_qty,0);
                $rows->trans_amount=number_format($rows->store_amount,4);
                $rows->purchase_rtn_qty=number_format(0,0);
                $rows->purchase_rtn_rate=number_format(0,4);
                $rows->purchase_rtn_amount=number_format(0,2);
                $rows->loan_qty=number_format(0,2);
                $rows->loan_rate=number_format(0,4);
                $rows->loan_amount=number_format(0,2);
                $rows->other_loan_qty=number_format(0,2);
                $rows->other_loan_rate=number_format(0,4);
                $rows->other_loan_amount=number_format(0,2);
                $rows->machine_wash_qty=number_format(0,2);
                $rows->machine_wash_rate=number_format(0,4);
                $rows->machine_wash_amount=number_format(0,2);
            }elseif($rows->isu_basis_id==11 && $rows->isu_against_id==0){
                $rows->issue_type="Purchase return";
                $rows->consumed_qty=number_format(0,2);
                $rows->po_rate=number_format(0,4);
                $rows->po_amount=number_format(0,2);
                $rows->trans_out_qty=number_format(0,0);
                $rows->trans_amount=number_format(0,4);
                $rows->purchase_rtn_qty=number_format($rows->store_qty,0);
                $rows->purchase_rtn_rate=number_format($rows->store_rate,4);
                $rows->purchase_rtn_amount=number_format($rows->store_amount,2);
                $rows->loan_qty=number_format(0,2);
                $rows->loan_rate=number_format(0,4);
                $rows->loan_amount=number_format(0,2);
                $rows->other_loan_qty=number_format(0,2);
                $rows->other_loan_rate=number_format(0,4);
                $rows->other_loan_amount=number_format(0,2);
                $rows->machine_wash_qty=number_format(0,2);
                $rows->machine_wash_rate=number_format(0,4);
                $rows->machine_wash_amount=number_format(0,2);
            }else {
                $rows->issue_type="Issue against";
                $rows->consumed_qty=number_format($rows->consumed_qty,2);
                $rows->po_rate=number_format($rows->po_rate,4);
                $rows->po_amount=number_format($rows->po_amount,2);
                $rows->loan_qty=number_format(0,2);
                $rows->loan_rate=number_format(0,4);
                $rows->loan_amount=number_format(0,2);
                $rows->trans_out_qty=number_format(0,0);
                $rows->trans_amount=number_format(0,4);
                $rows->purchase_rtn_qty=number_format(0,0);
                $rows->purchase_rtn_rate=number_format(0,4);
                $rows->purchase_rtn_amount=number_format(0,2);
                $rows->other_loan_qty=number_format(0,2);
                $rows->other_loan_rate=number_format(0,4);
                $rows->other_loan_amount=number_format(0,2);
                $rows->machine_wash_qty=number_format(0,2);
                $rows->machine_wash_rate=number_format(0,4);
                $rows->machine_wash_amount=number_format(0,2);
            }
            
            //$transIn->receive_against_id=$menu[$transIn->receive_against_id];
            $rows->trans_date=date('d-M-Y',strtotime($rows->issue_date));
            return $rows;
        });
        echo json_encode($rows);
    }

}