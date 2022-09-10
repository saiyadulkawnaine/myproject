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
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemRcvItemRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuItemRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;

class DyeIssueReceiveSummeryController extends Controller
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
    InvDyeChemIsuItemRepository $invdyechemisuitem,
    InvDyeChemRcvItemRepository $invdyechemrcvitem,
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
    $this->invdyechemisuitem = $invdyechemisuitem;
    $this->invdyechemrcvitem = $invdyechemrcvitem;
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
    $identity=array_prepend(array_only(config('bprs.identity'), [7,8]),'-Select-','');
    $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
    return Template::loadView('Report.Inventory.DyeIssueReceiveSummery',['supplier'=>$supplier,'company'=>$company,'identity'=>$identity,'store'=>$store]);
  }
	public function html() {
        $company_id=request('company_id',0);
        $supplier_id=request('supplier_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $identity=request('identity',0);
        $store_id=request('store_id',0);

        $supplierId=null;
        $companyCond=null;
        $storeId=null;

        if($company_id){
            $companyCond= ' and inv_rcvs.company_id= '.$company_id;
        }
        if($supplier_id){
            $supplierId=" and inv_rcvs.supplier_id = $supplier_id";
        }
        if($store_id){
            $storeId=" and inv_dye_chem_rcv_items.store_id = $store_id";
        }

      $invrcv=$this->invrcv
        ->selectRaw('
            item_accounts.id as item_account_id,
            itemcategories.name as itemcategory_name,
            itemclasses.name as itemclass_name,
            item_accounts.item_description,
            item_accounts.sub_class_name,
            item_accounts.specification,
            item_accounts.uom_id,
            uoms.code as uom_code,
            receive.rcv_qty,
            receive.rcv_rate,
            receive.rcv_amount,
            receive.store_amount,
            transfer_in.trans_in_qty,
            transfer_in.trans_in_amount,
            issue_rtn.issue_qty,
            issue_rtn.issue_amount,
            loan_rcv_rtn.loan_qty,
            loan_rcv_rtn.loan_amount
        ')
        ->join('inv_dye_chem_rcvs',function($join){
            $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_dye_chem_rcv_items',function($join){
            $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id')
            ->whereNull('inv_dye_chem_rcv_items.deleted_at');
        })
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_dye_chem_rcv_items.item_account_id');
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
        ->leftJoin(\DB::raw("(
            Select
                inv_dye_chem_rcv_items.item_account_id,
                sum(inv_dye_chem_rcv_items.qty) as rcv_qty,
                avg(inv_dye_chem_rcv_items.rate) as rcv_rate,
                sum(inv_dye_chem_rcv_items.amount) as rcv_amount,
                sum(inv_dye_chem_rcv_items.store_amount) as store_amount
            from inv_dye_chem_rcv_items
                join inv_dye_chem_rcvs on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
                join inv_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
            where inv_rcvs.receive_date>='".$date_from."' 
                and inv_rcvs.receive_date<='".$date_to."'
                and inv_rcvs.deleted_at is null
                and inv_rcvs.receive_basis_id in (1,2,3)
                and inv_rcvs.receive_against_id in (7,109)
                and inv_dye_chem_rcv_items.deleted_at is null
                $companyCond $supplierId $storeId
            group by 
                inv_dye_chem_rcv_items.item_account_id
            ) receive"),"receive.item_account_id","=","inv_dye_chem_rcv_items.item_account_id")
        ->leftJoin(\DB::raw("(
            Select
                inv_dye_chem_rcv_items.item_account_id,
                sum(inv_dye_chem_transactions.store_qty) as trans_in_qty,
                sum(inv_dye_chem_transactions.store_amount) as trans_in_amount
            from inv_dye_chem_rcv_items
                join inv_dye_chem_rcvs on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
                join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
                join inv_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
            where inv_rcvs.receive_date>='".$date_from."' 
                and inv_rcvs.receive_date<='".$date_to."'
                and inv_rcvs.deleted_at is null
                and inv_rcvs.receive_basis_id=9
                and inv_rcvs.receive_against_id=0
                and inv_dye_chem_rcv_items.deleted_at is null
                and inv_dye_chem_transactions.trans_type_id=1
                and inv_dye_chem_transactions.deleted_at is null
                $companyCond $supplierId $storeId
            group by 
                inv_dye_chem_rcv_items.item_account_id
            ) transfer_in"),"transfer_in.item_account_id","=","inv_dye_chem_rcv_items.item_account_id")
        ->leftJoin(\DB::raw("(
            Select
                inv_dye_chem_rcv_items.item_account_id,
                sum(inv_dye_chem_transactions.store_qty) as issue_qty,
                sum(inv_dye_chem_rcv_items.amount) as issue_amount
            from inv_dye_chem_rcv_items
                join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
                join inv_dye_chem_rcvs on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
                join inv_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
            where inv_rcvs.receive_date>='".$date_from."' 
                and inv_rcvs.receive_date<='".$date_to."'
                and inv_rcvs.deleted_at is null
                and inv_rcvs.receive_basis_id=4
                and inv_rcvs.receive_against_id=0
                and inv_dye_chem_rcv_items.deleted_at is null
                and inv_dye_chem_transactions.trans_type_id=1
                and inv_dye_chem_transactions.deleted_at is null
                $companyCond $supplierId $storeId
            group by 
                inv_dye_chem_rcv_items.item_account_id
            ) issue_rtn"),"issue_rtn.item_account_id","=","inv_dye_chem_rcv_items.item_account_id")
        ->leftJoin(\DB::raw("(
            Select
                inv_dye_chem_rcv_items.item_account_id,
                sum(inv_dye_chem_rcv_items.qty) as loan_qty,
                sum(inv_dye_chem_rcv_items.amount) as loan_amount
            from inv_dye_chem_rcv_items
                join inv_dye_chem_rcvs on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
                join inv_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
            where inv_rcvs.receive_date>='".$date_from."' 
                and inv_rcvs.receive_date<='".$date_to."'
                and inv_rcvs.deleted_at is null
                and inv_rcvs.receive_basis_id=10
                and inv_rcvs.receive_against_id in (7,109)
                and inv_dye_chem_rcv_items.deleted_at is null
                $companyCond $supplierId $storeId
            group by 
                inv_dye_chem_rcv_items.item_account_id
            ) loan_rcv_rtn"),"loan_rcv_rtn.item_account_id","=","inv_dye_chem_rcv_items.item_account_id")

        // ->leftJoin('companies as trans_in_company',function($join){
        //     $join->on('trans_in_company.id','=','inv_rcvs.from_company_id');
        // })
        // ->leftJoin('po_dye_chem_items',function($join){
        //     $join->on('po_dye_chem_items.id','=','inv_dye_chem_rcv_items.po_dye_chem_item_id');
        // })
        // ->leftJoin('po_dye_chems',function($join){
        //     $join->on('po_dye_chems.id','=','po_dye_chem_items.po_dye_chem_id');
        // })
        // ->leftJoin('inv_pur_req_items', function($join){
        //     $join->on('inv_pur_req_items.id', '=', 'inv_dye_chem_rcv_items.inv_pur_req_item_id');
        // })
        // ->leftJoin('inv_pur_reqs', function($join){
        //     $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
        // })
        // ->leftJoin('currencies',function($join){
        //     $join->on('currencies.id','=','po_dye_chems.currency_id');
        // })
        
        // ->when(request('supplier_id'), function ($q)  {
        //     return $q->where('inv_rcvs.supplier_id', '=', request('supplier_id',0));
        // })
        // ->when(request('company_id'), function ($q) {
        //     return $q->where('inv_rcvs.company_id', '=',  request('company_id',0));
        // })
        ->when(request('date_from'), function ($q) {
            return $q->where('inv_rcvs.receive_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('inv_rcvs.receive_date', '<=',request('date_to', 0));
        })
        ->when(request('identity'), function ($q) {
            return $q->where('itemcategories.identity', '=',  request('identity',0));
        })
        ->when(request('store_id'), function ($q) {
            return $q->where('inv_dye_chem_rcv_items.store_id', '=',  request('store_id',0));
        })
        ->groupBy([
            'item_accounts.id',
            'itemcategories.name',
            'itemclasses.name',
            'item_accounts.item_description',
            'item_accounts.sub_class_name',
            'item_accounts.specification',
            'item_accounts.uom_id',
            'uoms.code',
            'receive.rcv_qty',
            'receive.rcv_rate',
            'receive.rcv_amount',
            'receive.store_amount',
            'transfer_in.trans_in_qty',
            'transfer_in.trans_in_amount',
            'issue_rtn.issue_qty',
            'issue_rtn.issue_amount',
            'loan_rcv_rtn.loan_qty',
            'loan_rcv_rtn.loan_amount'
        ])
        //->orderBy('itemcategories.id')
        ->orderBy('item_accounts.id')
       // ->orderBy('item_accounts.item_description')
        ->get()
        ->map(function($invrcv) {
            $invrcv->item_desc=$invrcv->item_description.", ".$invrcv->specification;
            //$transIn->ref_type=$invreceivebasis[$transIn->receive_basis_id];
            //$transIn->receive_against_id=$menu[$transIn->receive_against_id];
            //$transIn->trans_date=date('d-M-Y',strtotime($transIn->receive_date));
            // if($transIn->receive_against_id==0 || $transIn->receive_against_id==109){
            //     $transIn->po_currency='BDT';
            //     $transIn->exch_rate=1;
            // }
            // if($transIn->receive_against_id==7){
            //     $transIn->po_currency=$transIn->currency_code;
            // }
            //$transIn->ref_type="Purchase";              
            $invrcv->rcv_qty=number_format($invrcv->rcv_qty,2);
            $invrcv->rcv_rate=number_format($invrcv->rcv_rate,4);
            $invrcv->rcv_amount=number_format($invrcv->rcv_amount,2);
            $invrcv->store_amount=number_format($invrcv->store_amount,2);
            $invrcv->trans_in_qty=number_format($invrcv->trans_in_qty,0);
            $invrcv->trans_in_amount=number_format($invrcv->trans_in_amount,4);
            $invrcv->issue_qty=number_format($invrcv->issue_qty,0);
            $invrcv->issue_amount=number_format($invrcv->issue_amount,2);
            $invrcv->loan_qty=number_format($invrcv->loan_qty,4);
            $invrcv->loan_amount=number_format($invrcv->loan_amount,2);

            return $invrcv;
      });

        return $invrcv;
    }



    public function reportData(){
        return response()->json($this->html());
    }

    public function issueData() {
        $company_id=request('company_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $identity=request('identity',0);
        $store_id=request('store_id',0);
        $storeId='';
        if($store_id){
            $storeId=" and inv_dye_chem_isu_items.store_id = $store_id";
        }else {
            $storeId='';
        }
        $companyCond='';
        if($company_id){
            $companyCond= ' and inv_isus.company_id= '.$company_id;
        }
        else{
            $companyCond= '';
        }
        $company = array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $menu=array_prepend(config('bprs.menu'),'-Select-','');

        $issue=$this->invisu
            ->selectRaw('
                item_accounts.id as item_account_id,
                itemcategories.name as itemcategory_name,
                itemclasses.name as itemclass_name,
                item_accounts.item_description,
                item_accounts.sub_class_name,
                item_accounts.specification,
                regular_isu.consumed_qty,
                regular_isu.po_rate,
                regular_isu.po_amount,
                loan_isu.loan_qty,
                loan_isu.loan_rate,
                loan_isu.loan_amount,
                trans_out_isu.trans_out_qty,
                trans_out_isu.trans_amount,
                rcv_rtn_isu.purchase_rtn_qty,  
                rcv_rtn_isu.purchase_rtn_amount
            ')/* //'trans_out_isu.trans_out_qty', */
            ->join('inv_dye_chem_isu_items',function($join){
                $join->on('inv_dye_chem_isu_items.inv_isu_id','=','inv_isus.id');
            })
            ->join('item_accounts',function($join){
                $join->on('inv_dye_chem_isu_items.item_account_id','=','item_accounts.id');
            })
            ->join('itemclasses', function($join){
                $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
            })
            ->join('itemcategories', function($join){
                $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','inv_isus.company_id');
            })
            ->leftJoin(\DB::raw("(
                select 
                inv_dye_chem_isu_items.item_account_id,
                sum(inv_dye_chem_isu_items.qty) as consumed_qty,
                avg(inv_dye_chem_isu_items.rate) as po_rate,
                sum(inv_dye_chem_isu_items.amount) as po_amount
                from inv_dye_chem_isu_items
                
                join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
                where inv_isus.issue_date>='".$date_from."' 
                and inv_isus.issue_date<='".$date_to."'
        
                and inv_dye_chem_isu_items.deleted_at is null
                and inv_isus.isu_basis_id in (1)
                and inv_isus.isu_against_id in (208,209,210,223)
                and inv_isus.deleted_at is null
                $companyCond $storeId
                group by inv_dye_chem_isu_items.item_account_id
                ) regular_isu"), "regular_isu.item_account_id", "=", "inv_dye_chem_isu_items.item_account_id")
            ->leftJoin(\DB::raw("(
                select 
                inv_dye_chem_isu_items.item_account_id,
                sum(inv_dye_chem_isu_items.qty) as loan_qty,
                avg(inv_dye_chem_isu_items.rate) as loan_rate,
                sum(inv_dye_chem_isu_items.amount) as loan_amount
                from inv_dye_chem_isu_items
                join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
                join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id = inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
                join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id = inv_dye_chem_isu_rqs.id
                where inv_isus.issue_date>='".$date_from."' 
                and inv_isus.issue_date<='".$date_to."'
                and inv_dye_chem_isu_items.deleted_at is null
                and inv_isus.deleted_at is null
                and inv_isus.isu_against_id=211
                and inv_dye_chem_isu_rqs.rq_basis_id in (5,6,7,8)
                $companyCond $storeId
                group by inv_dye_chem_isu_items.item_account_id
                ) loan_isu"), "loan_isu.item_account_id", "=", "inv_dye_chem_isu_items.item_account_id")
            //loan & other issue 
            ->leftJoin(\DB::raw("(
                select 
                inv_dye_chem_isu_items.item_account_id,
                abs(sum(inv_dye_chem_transactions.store_qty)) as trans_out_qty,
                sum(inv_dye_chem_transactions.store_amount) as trans_amount
                from inv_dye_chem_isu_items
                join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
                join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
                where inv_isus.issue_date>='".$date_from."' 
                and inv_isus.issue_date<='".$date_to."'
        
                and inv_isus.isu_basis_id  = 9
                and inv_isus.menu_id=215
                and inv_dye_chem_transactions.deleted_at is null
                and inv_dye_chem_isu_items.deleted_at is null
                and inv_isus.deleted_at is null
                and inv_dye_chem_transactions.trans_type_id=2
                $companyCond $storeId
                group by inv_dye_chem_isu_items.item_account_id
                ) trans_out_isu"), "trans_out_isu.item_account_id", "=", "inv_dye_chem_isu_items.item_account_id")
            ->leftJoin(\DB::raw("(
                select 
                inv_dye_chem_isu_items.item_account_id,
                abs(sum(inv_dye_chem_transactions.store_qty)) as purchase_rtn_qty,
                sum(inv_dye_chem_transactions.store_amount) as purchase_rtn_amount
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
                $companyCond $storeId
                group by inv_dye_chem_isu_items.item_account_id
                ) rcv_rtn_isu"), "rcv_rtn_isu.item_account_id", "=", "item_accounts.id")
            
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
            ->when(request('identity'), function ($q) {
                return $q->where('itemcategories.identity', '=',  request('identity',0));
            })
            ->whereIn('itemcategories.identity',[7,8])
            //->whereIn('inv_isus.isu_against_id',[208,209,210,223])
            ->groupBy([
                'item_accounts.id', 
                'itemcategories.name',
                'itemclasses.name',
                'item_accounts.item_description',
                'item_accounts.sub_class_name',
                'item_accounts.specification',
                'regular_isu.consumed_qty',
                'regular_isu.po_rate',
                'regular_isu.po_amount',
                'loan_isu.loan_qty',
                'loan_isu.loan_rate',
                'loan_isu.loan_amount',
                'trans_out_isu.trans_out_qty',
                'trans_out_isu.trans_amount',
                'rcv_rtn_isu.purchase_rtn_qty',
                //'trans_out_isu.trans_out_qty',
                'rcv_rtn_isu.purchase_rtn_amount'
            ])
            ->orderBy('item_accounts.id')
            ->get()
            ->map(function($issue){
                $issue->item_desc=$issue->item_description.', '.$issue->specification;
                //$issue->purchase_rtn_qty=0;
                if($issue->purchase_rtn_qty){
                    $issue->purchase_rtn_rate=$issue->purchase_rtn_amount/$issue->purchase_rtn_qty;
                }
                
                $issue->consumed_qty=number_format($issue->consumed_qty,2);
                $issue->po_rate=number_format($issue->po_rate,4);
                $issue->po_amount=number_format($issue->po_amount,2);
                $issue->loan_rate=number_format($issue->loan_rate,4);
                $issue->loan_amount=number_format($issue->loan_amount,2);
                $issue->loan_qty=number_format($issue->loan_qty,2);
                $issue->trans_out_qty=number_format($issue->trans_out_qty,2);
                $issue->trans_amount=number_format($issue->trans_amount,2);
                $issue->purchase_rtn_qty=number_format($issue->purchase_rtn_qty,0);
                $issue->purchase_rtn_amount=number_format($issue->purchase_rtn_amount,2);
                $issue->purchase_rtn_rate=number_format($issue->purchase_rtn_rate,4);
                return $issue;
            });

        echo json_encode($issue);
        
    }

    public function getRegularDtl(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $identity=request('identity',0);
        $store_id=request('store_id',0);
        $item_account_id=request('item_account_id',0);
        
        $datefrom=null;
		$dateto=null;
        $companyCond=null;
        $identityId=null;
        $itemAccountId=null;
        $storeId=null;
        if($company_id){
        $companyCond= ' and inv_isus.company_id= '.$company_id;
        }
        if($date_from){
			$datefrom=" and inv_isus.issue_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and inv_isus.issue_date<='".$date_to."' ";
		}
        if($item_account_id){
			$itemAccountId=" and inv_dye_chem_isu_items.item_account_id = $item_account_id ";
        }
        if($store_id){
			$storeId=" and inv_dye_chem_isu_items.store_id = $store_id ";
        }
        if($identity){
            $identityId=" and  itemcategories.identity = $identity";
        }
        
        $company = array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');

        $rows = \DB::select("
            select
            inv_dye_chem_isu_items.item_account_id,
            itemcategories.name as itemcategory_name,
            itemclasses.name as itemclass_name,
            item_accounts.item_description,
            item_accounts.specification,
            inv_isus.isu_basis_id,
            inv_isus.issue_no,
            inv_isus.company_id,
            inv_isus.to_company_id,
            inv_isus.issue_date,
            inv_isus.isu_against_id,
            uoms.code as uom_code,
            companies.code as company_code,
            inv_dye_chem_isu_items.id as inv_dye_chem_isu_item_id,
            po_dye_chems.exch_rate,
            po_dye_chems.po_no,
            inv_pur_reqs.requisition_no,
            po_dye_chem_items.qty as po_qty,
            po_dye_chem_items.amount as po_amount,
            inv_pur_req_items.qty as req_qty,
            inv_pur_req_items.amount as req_amount,
            inv_dye_chem_rcv_items.qty,
            inv_dye_chem_rcv_items.rate,
            inv_dye_chem_rcv_items.amount,
            inv_dye_chem_rcv_items.store_rate,
            inv_dye_chem_rcv_items.store_amount

            from
            inv_isus
            join inv_dye_chem_isu_items on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
            join item_accounts on item_accounts.id=inv_dye_chem_isu_items.item_account_id
            join itemclasses on itemclasses.id = item_accounts.itemclass_id
            join itemcategories on itemcategories.id = item_accounts.itemcategory_id
            join uoms on uoms.id=item_accounts.uom_id
            join companies on companies.id = inv_isus.company_id
            where inv_dye_chem_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_isus.isu_against_id in (208,209,210,223)
            $datefrom $dateto $companyCond $itemAccountId $identityId $storeId
            order by 
            itemcategories.id,
            itemclasses.id
        ");
        $data=collect($rows)
		->map(function($data) /* use($itemcomplexity) */{
            $data->item_desc=$data->item_description.', '.$data->specification;
			$data->qty=number_format($data->qty,2);
			$data->rate=number_format($data->rate,4);
			$data->amount=number_format($data->amount,2);
            $data->store_rate=number_format($data->store_rate,2);
            $data->store_amount=number_format($data->store_amount,2);
            $data->po_qty=number_format($data->po_qty,2);
            $data->po_amount=number_format($data->po_amount,2);
            $data->req_qty=number_format($data->req_qty,2);
            $data->req_amount=number_format($data->req_amount,2);
            $data->exch_rate=$data->exch_rate?$data->exch_rate:1;
			return $data;
		});
		echo json_encode($data);
    }

    public function getTransDtl(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $identity=request('identity',0);
        $item_account_id=request('item_account_id',0);
        $store_id=request('store_id',0);

        $datefrom=null;
		$dateto=null;
        $companyCond=null;
        $identityId=null;
        $itemAccountId=null;
        $storeId=null;
        if($company_id){
        $companyCond= ' and inv_isus.company_id= '.$company_id;
        }
        if($date_from){
			$datefrom=" and inv_isus.issue_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and inv_isus.issue_date<='".$date_to."' ";
		}
        if($item_account_id){
			$itemAccountId=" and inv_dye_chem_isu_items.item_account_id = $item_account_id ";
        }
        if($store_id){
			$storeId=" and inv_dye_chem_isu_items.store_id = $store_id ";
        }
        if($identity){
            $identityId=" and  itemcategories.identity = $identity";
        }
        
        $company = array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');

        $rows = \DB::select("
            select
            inv_dye_chem_isu_items.item_account_id,
            itemcategories.name as itemcategory_name,
            itemclasses.name as itemclass_name,
            item_accounts.item_description,
            item_accounts.specification,
            inv_isus.isu_basis_id,
            inv_isus.issue_no,
            inv_isus.company_id,
            inv_isus.to_company_id,
            inv_isus.issue_date,
            inv_isus.isu_against_id,
            uoms.code as uom_code,
            companies.code as company_code,
            inv_dye_chem_isu_items.id as inv_dye_chem_isu_item_id,
            abs(inv_dye_chem_transactions.store_qty) as trans_out_qty,
            inv_dye_chem_transactions.store_amount as trans_amount

            from
            inv_isus
            join inv_dye_chem_isu_items on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
            join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
            join item_accounts on item_accounts.id=inv_dye_chem_isu_items.item_account_id
            join itemclasses on itemclasses.id = item_accounts.itemclass_id
            join itemcategories on itemcategories.id = item_accounts.itemcategory_id
            join uoms on uoms.id=item_accounts.uom_id
            join companies on companies.id = inv_isus.company_id
            where inv_dye_chem_isu_items.deleted_at is null
            and inv_dye_chem_transactions.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_isus.isu_against_id=0
            and inv_isus.isu_basis_id=9
            and inv_dye_chem_transactions.trans_type_id=2
            $datefrom $dateto $companyCond $itemAccountId $identityId $storeId
            order by 
            itemcategories.id,
            itemclasses.id
        ");
        $data=collect($rows)
		->map(function($data) use($company){
            $data->item_desc=$data->item_description.', '.$data->specification;
            $data->to_company=$company[$data->to_company_id];
			$data->qty=number_format($data->trans_out_qty,2);
			$data->amount=number_format($data->trans_amount,2);
			return $data;
		});
		echo json_encode($data);
    }

    public function getRcvRtnDtl(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $identity=request('identity',0);
        $item_account_id=request('item_account_id',0);
        $store_id=request('store_id',0);
        
        $datefrom=null;
		$dateto=null;
        $companyCond=null;
        $identityId=null;
        $itemAccountId=null;
        $storeId=null;
        if($company_id){
        $companyCond= ' and inv_isus.company_id= '.$company_id;
        }
        if($date_from){
			$datefrom=" and inv_isus.issue_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and inv_isus.issue_date<='".$date_to."' ";
		}
        if($item_account_id){
			$itemAccountId=" and inv_dye_chem_isu_items.item_account_id = $item_account_id ";
        }
        if($store_id){
			$storeId=" and inv_dye_chem_isu_items.store_id = $store_id ";
        }
        if($identity){
            $identityId=" and  itemcategories.identity = $identity";
        }
        
        $company = array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');

        $rows = \DB::select("
            select
            inv_dye_chem_isu_items.item_account_id,
            itemcategories.name as itemcategory_name,
            itemclasses.name as itemclass_name,
            item_accounts.item_description,
            item_accounts.specification,
            inv_isus.isu_basis_id,
            inv_isus.issue_no,
            inv_isus.company_id,
            inv_isus.issue_date,
            inv_isus.isu_against_id,
            inv_isus.supplier_id,
            uoms.code as uom_code,
            companies.code as company_code,
            suppliers.name as supplier_name,
            inv_dye_chem_isu_items.id as inv_dye_chem_isu_item_id,
            abs(inv_dye_chem_transactions.store_qty) as rcv_rtn_qty,
            inv_dye_chem_transactions.store_amount as rcv_rtn_amount

            from
            inv_isus
            join inv_dye_chem_isu_items on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
            join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
            join item_accounts on item_accounts.id=inv_dye_chem_isu_items.item_account_id
            join itemclasses on itemclasses.id = item_accounts.itemclass_id
            join itemcategories on itemcategories.id = item_accounts.itemcategory_id
            join uoms on uoms.id=item_accounts.uom_id
            join companies on companies.id = inv_isus.company_id
            join suppliers on suppliers.id = inv_isus.supplier_id
            where inv_dye_chem_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_isus.isu_basis_id  = 11
            and inv_dye_chem_transactions.trans_type_id=2
            $datefrom $dateto $companyCond $itemAccountId $identityId $storeId
            order by 
            itemcategories.id,
            itemclasses.id
        ");
        $data=collect($rows)
		->map(function($data) /* use($itemcomplexity) */{
            $data->item_desc=$data->item_description.', '.$data->specification; 
			$data->qty=number_format($data->rcv_rtn_qty,2);
			$data->amount=number_format($data->rcv_rtn_amount,2);
			
			return $data;
		});
		echo json_encode($data);
    }

    public function getLoanDtl(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $identity=request('identity',0);
        $item_account_id=request('item_account_id',0);
        $store_id=request('store_id',0);
        
        $datefrom=null;
		$dateto=null;
        $companyCond=null;
        $identityId=null;
        $itemAccountId=null;
        $storeId=null;
        if($company_id){
        $companyCond= ' and inv_isus.company_id= '.$company_id;
        }
        if($date_from){
			$datefrom=" and inv_isus.issue_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and inv_isus.issue_date<='".$date_to."' ";
		}
        if($item_account_id){
			$itemAccountId=" and inv_dye_chem_isu_items.item_account_id = $item_account_id ";
        }
        if($store_id){
			$storeId=" and inv_dye_chem_isu_items.store_id = $store_id ";
        }
        if($identity){
            $identityId=" and  itemcategories.identity = $identity";
        }

        
        $rows = \DB::select("
            select
            inv_dye_chem_isu_items.item_account_id,
            itemcategories.name as itemcategory_name,
            itemclasses.name as itemclass_name,
            item_accounts.item_description,
            item_accounts.specification,
            inv_isus.issue_no,
            inv_isus.company_id,
            inv_isus.issue_date,
            uoms.code as uom_code,
            companies.code as company_code,
            inv_dye_chem_isu_rqs.supplier_id,
            suppliers.name as supplier_name,
            inv_dye_chem_isu_items.id as inv_dye_chem_isu_item_id,
            inv_dye_chem_isu_items.qty,
            inv_dye_chem_isu_items.rate,
            inv_dye_chem_isu_items.amount

            from
            inv_isus
            join inv_dye_chem_isu_items on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
            join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.id = inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id
            join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id = inv_dye_chem_isu_rqs.id
            left join suppliers on suppliers.id = inv_dye_chem_isu_rqs.supplier_id
            join item_accounts on item_accounts.id=inv_dye_chem_isu_items.item_account_id
            join itemclasses on itemclasses.id = item_accounts.itemclass_id
            join itemcategories on itemcategories.id = item_accounts.itemcategory_id
            join uoms on uoms.id=item_accounts.uom_id
            join companies on companies.id = inv_isus.company_id
            where inv_dye_chem_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_isus.isu_against_id=211
            and inv_dye_chem_isu_rqs.rq_basis_id=5
            $datefrom $dateto $companyCond $itemAccountId $identityId $storeId
            order by 
            itemcategories.id,
            itemclasses.id
        ");
        $data=collect($rows)
		->map(function($data) /* use($itemcomplexity) */{
            $data->item_desc=$data->item_description.', '.$data->specification;
			$data->qty=number_format($data->qty,2);
			$data->rate=number_format($data->rate,4);
			$data->amount=number_format($data->amount,2);
			return $data;
		});
		echo json_encode($data);
    }

    public function getRcvRegular(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $identity=request('identity',0);
        $item_account_id=request('item_account_id',0);
        $supplier_id=request('supplier_id',0);
        $store_id=request('store_id',0);
        
        
        $datefrom=null;
		$dateto=null;
        $companyCond=null;
        $identityId=null;
        $itemAccountId=null;
        $supplierId=null;
        $storeId=null;
        if($company_id){
        $companyCond= ' and inv_rcvs.company_id= '.$company_id;
        }
        if($date_from){
			$datefrom=" and inv_rcvs.receive_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and inv_rcvs.receive_date<='".$date_to."' ";
		}
        if($item_account_id){
			$itemAccountId=" and inv_dye_chem_rcv_items.item_account_id = $item_account_id ";
        }
        if($identity){
            $identityId=" and  itemcategories.identity = $identity";
        }
        if($supplier_id){
            $supplierId=" and inv_rcvs.supplier_id = $supplier_id";
        }
        if($store_id){
			$storeId=" and inv_dye_chem_rcv_items.store_id = $store_id ";
        }
        
        $invreceivebasis=array_prepend(config('bprs.invreceivebasis'),'-Select-','');
        $menu=array_prepend(config('bprs.menu'),'-Select-','');


        $rows = \DB::select("
            select
                inv_dye_chem_rcv_items.item_account_id,
                itemcategories.name as itemcategory_name,
                itemclasses.name as itemclass_name,
                item_accounts.item_description,
                item_accounts.specification,
                inv_rcvs.receive_basis_id,
                inv_rcvs.receive_no,
                inv_rcvs.company_id,
                inv_rcvs.receive_date,
                inv_rcvs.receive_against_id,
                uoms.code as uom_code,
                companies.code as company_code,
                suppliers.name as supplier_name,
                inv_dye_chem_rcv_items.id as inv_dye_chem_rcv_item_id,
                inv_dye_chem_rcv_items.qty,
                inv_dye_chem_rcv_items.rate,
                inv_dye_chem_rcv_items.amount,
                inv_dye_chem_rcv_items.store_amount
        
            from 
            inv_rcvs
                join inv_dye_chem_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
                join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
                join item_accounts on item_accounts.id=inv_dye_chem_rcv_items.item_account_id
                join itemclasses on itemclasses.id = item_accounts.itemclass_id
                join itemcategories on itemcategories.id = item_accounts.itemcategory_id
                join uoms on uoms.id=item_accounts.uom_id
                left Join po_dye_chem_items on po_dye_chem_items.id = inv_dye_chem_rcv_items.po_dye_chem_item_id
                left Join po_dye_chems on po_dye_chems.id = po_dye_chem_items.po_dye_chem_id
                left Join inv_pur_req_items  on inv_pur_req_items.id = inv_dye_chem_rcv_items.inv_pur_req_item_id
                left Join inv_pur_reqs on inv_pur_reqs.id = inv_pur_req_items.inv_pur_req_id 
                left Join currencies on currencies.id = po_dye_chems.currency_id 
                join companies on companies.id = inv_rcvs.company_id
                join suppliers on suppliers.id = inv_rcvs.supplier_id
                where inv_dye_chem_rcv_items.deleted_at is null
                and inv_rcvs.deleted_at is null
                and inv_rcvs.receive_basis_id in (1,2,3)
                and inv_rcvs.receive_against_id in (7,109)
                $datefrom $dateto $companyCond $itemAccountId $supplierId $identityId $storeId
            order by 
                itemcategories.id,
                itemclasses.id
        ");
        $data=collect($rows)
		->map(function($data) use($invreceivebasis,$menu){
            $data->item_desc=$data->item_description.', '.$data->specification;
            $data->receive_date=date('d-M-Y',strtotime($data->receive_date));
            $data->receive_basis_id=$invreceivebasis[$data->receive_basis_id];
            $data->receive_against_id=$menu[$data->receive_against_id];
			$data->qty=number_format($data->qty,2);
			$data->rate=number_format($data->rate,4);
			$data->amount=number_format($data->amount,2);
			return $data;
		});
		echo json_encode($data);
    }

    public function getRcvTransIn(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $identity=request('identity',0);
        $item_account_id=request('item_account_id',0);
        $store_id=request('store_id',0);
               
        $datefrom=null;
		$dateto=null;
        $companyCond=null;
        $identityId=null;
        $itemAccountId=null;
        $storeId=null;

        if($company_id){
        $companyCond= ' and inv_rcvs.company_id= '.$company_id;
        }
        if($date_from){
			$datefrom=" and inv_rcvs.receive_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and inv_rcvs.receive_date<='".$date_to."' ";
		}
        if($item_account_id){
			$itemAccountId=" and inv_dye_chem_rcv_items.item_account_id = $item_account_id ";
        }
        if($store_id){
			$storeId=" and inv_dye_chem_rcv_items.store_id = $store_id ";
        }
        if($identity){
            $identityId=" and  itemcategories.identity = $identity";
        }
        
        //$invreceivebasis=array_prepend(config('bprs.invreceivebasis'),'-Select-','');
        //$menu=array_prepend(config('bprs.menu'),'-Select-','');
        $company = array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');

        $rows = \DB::select("
            select
                inv_dye_chem_rcv_items.item_account_id,
                itemcategories.name as itemcategory_name,
                itemclasses.name as itemclass_name,
                item_accounts.item_description,
                item_accounts.specification,
                inv_rcvs.receive_basis_id,
                inv_rcvs.receive_no,
                inv_rcvs.company_id,
                inv_rcvs.receive_date,
                inv_rcvs.receive_against_id,
                inv_rcvs.from_company_id,
                uoms.code as uom_code,
                companies.code as company_code,
                inv_dye_chem_rcv_items.id as inv_dye_chem_rcv_item_id,
                abs(inv_dye_chem_transactions.store_qty) as qty,
                inv_dye_chem_transactions.store_amount as amount
        
            from 
            inv_rcvs
                join inv_dye_chem_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
                join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
                join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
                join item_accounts on item_accounts.id=inv_dye_chem_rcv_items.item_account_id
                join itemclasses on itemclasses.id = item_accounts.itemclass_id
                join itemcategories on itemcategories.id = item_accounts.itemcategory_id
                join uoms on uoms.id=item_accounts.uom_id
                left Join po_dye_chem_items on po_dye_chem_items.id = inv_dye_chem_rcv_items.po_dye_chem_item_id
                left Join po_dye_chems on po_dye_chems.id = po_dye_chem_items.po_dye_chem_id
                left Join inv_pur_req_items  on inv_pur_req_items.id = inv_dye_chem_rcv_items.inv_pur_req_item_id 
                left Join inv_pur_reqs on inv_pur_reqs.id = inv_pur_req_items.inv_pur_req_id 
                left Join currencies on currencies.id = po_dye_chems.currency_id 
                join companies on companies.id = inv_rcvs.company_id
                where inv_dye_chem_rcv_items.deleted_at is null
                and inv_rcvs.deleted_at is null
                and inv_rcvs.receive_basis_id=9
                and inv_rcvs.receive_against_id=0
                and inv_dye_chem_transactions.trans_type_id=1
                $datefrom $dateto $companyCond $itemAccountId $identityId $storeId
            order by 
                itemcategories.id,
                itemclasses.id
        ");
        $data=collect($rows)
		->map(function($data) use($company){
            $data->item_desc=$data->item_description.', '.$data->specification;
            $data->receive_date=date('d-M-Y',strtotime($data->receive_date));
            $data->from_company=$company[$data->from_company_id];
			$data->qty=number_format($data->qty,2);
			$data->amount=number_format($data->amount,2);
			return $data;
		});
		echo json_encode($data);
    }

    public function getIsuRtn(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $identity=request('identity',0);
        $item_account_id=request('item_account_id',0);
        $store_id=request('store_id',0);
        
        
        $datefrom=null;
		$dateto=null;
        $companyCond=null;
        $identityId=null;
        $itemAccountId=null;
        $storeId=null;
        if($company_id){
        $companyCond= ' and inv_rcvs.company_id= '.$company_id;
        }
        if($date_from){
			$datefrom=" and inv_rcvs.receive_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and inv_rcvs.receive_date<='".$date_to."' ";
		}
        if($item_account_id){
			$itemAccountId=" and inv_dye_chem_rcv_items.item_account_id = $item_account_id ";
        }
        if($identity){
            $identityId=" and  itemcategories.identity = $identity";
        }
        if($store_id){
			$storeId=" and inv_dye_chem_rcv_items.store_id = $store_id ";
        }
        
        $invreceivebasis=array_prepend(config('bprs.invreceivebasis'),'-Select-','');
        $menu=array_prepend(config('bprs.menu'),'-Select-','');


        $rows = \DB::select("
            select
                inv_dye_chem_rcv_items.item_account_id,
                itemcategories.name as itemcategory_name,
                itemclasses.name as itemclass_name,
                item_accounts.item_description,
                item_accounts.specification,
                inv_rcvs.receive_basis_id,
                inv_rcvs.receive_no,
                inv_rcvs.company_id,
                inv_rcvs.receive_date,
                inv_rcvs.receive_against_id,
                uoms.code as uom_code,
                companies.code as company_code,
                inv_dye_chem_rcv_items.id as inv_dye_chem_rcv_item_id,
                inv_dye_chem_transactions.store_qty as qty,
                inv_dye_chem_transactions.store_amount as amount
        
            from 
            inv_rcvs
                join inv_dye_chem_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
                join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
                join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
                join item_accounts on item_accounts.id=inv_dye_chem_rcv_items.item_account_id
                join itemclasses on itemclasses.id = item_accounts.itemclass_id
                join itemcategories on itemcategories.id = item_accounts.itemcategory_id
                join uoms on uoms.id=item_accounts.uom_id
                join companies on companies.id = inv_rcvs.company_id
                where inv_dye_chem_rcv_items.deleted_at is null
                and inv_rcvs.deleted_at is null
                and inv_rcvs.receive_basis_id=4
                and inv_rcvs.receive_against_id=0
                and inv_dye_chem_transactions.trans_type_id=1
                $datefrom $dateto $companyCond $itemAccountId $identityId $storeId
            order by 
                itemcategories.id,
                itemclasses.id
        ");
        $data=collect($rows)
		->map(function($data) use($invreceivebasis,$menu){
            $data->item_desc=$data->item_description.', '.$data->specification;
            $data->receive_date=date('d-M-Y',strtotime($data->receive_date));
            $data->receive_basis_id=$invreceivebasis[$data->receive_basis_id];
            $data->receive_against_id=$menu[$data->receive_against_id];
			$data->qty=number_format($data->qty,2);
			//$data->rate=number_format($data->rate,4);
			$data->amount=number_format($data->amount,2);
			return $data;
		});
		echo json_encode($data);
    }

    public function getRcvLoan(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $identity=request('identity',0);
        $item_account_id=request('item_account_id',0);
        $supplier_id=request('supplier_id',0);
        $store_id=request('store_id',0);
        
        
        $datefrom=null;
		$dateto=null;
        $companyCond=null;
        $identityId=null;
        $itemAccountId=null;
        $storeId=null;
        $supplierId=null;
        if($company_id){
        $companyCond= ' and inv_rcvs.company_id= '.$company_id;
        }
        if($date_from){
			$datefrom=" and inv_rcvs.receive_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and inv_rcvs.receive_date<='".$date_to."' ";
		}
        if($item_account_id){
			$itemAccountId=" and inv_dye_chem_rcv_items.item_account_id = $item_account_id ";
        }
        if($store_id){
			$storeId=" and inv_dye_chem_rcv_items.store_id = $store_id ";
        }
        if($identity){
            $identityId=" and  itemcategories.identity = $identity";
        }
        if($supplier_id){
            $supplierId=" and inv_rcvs.supplier_id = $supplier_id";
        }
        
        $invreceivebasis=array_prepend(config('bprs.invreceivebasis'),'-Select-','');
        $menu=array_prepend(config('bprs.menu'),'-Select-','');


        $rows = \DB::select("
            select
                inv_dye_chem_rcv_items.item_account_id,
                itemcategories.name as itemcategory_name,
                itemclasses.name as itemclass_name,
                item_accounts.item_description,
                item_accounts.specification,
                inv_rcvs.receive_basis_id,
                inv_rcvs.receive_no,
                inv_rcvs.company_id,
                inv_rcvs.receive_date,
                inv_rcvs.receive_against_id,
                uoms.code as uom_code,
                companies.code as company_code,
                suppliers.name as supplier_name,
                inv_dye_chem_rcv_items.id as inv_dye_chem_rcv_item_id,
                inv_dye_chem_rcv_items.qty,
                inv_dye_chem_rcv_items.rate,
                inv_dye_chem_rcv_items.amount,
                inv_dye_chem_rcv_items.store_amount
        
            from 
            inv_rcvs
                join inv_dye_chem_rcvs on inv_dye_chem_rcvs.inv_rcv_id=inv_rcvs.id
                join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.inv_dye_chem_rcv_id=inv_dye_chem_rcvs.id
                join item_accounts on item_accounts.id=inv_dye_chem_rcv_items.item_account_id
                join itemclasses on itemclasses.id = item_accounts.itemclass_id
                join itemcategories on itemcategories.id = item_accounts.itemcategory_id
                join uoms on uoms.id=item_accounts.uom_id
                join companies on companies.id = inv_rcvs.company_id
                join suppliers on suppliers.id = inv_rcvs.supplier_id
                where inv_dye_chem_rcv_items.deleted_at is null
                and inv_rcvs.deleted_at is null
                and inv_rcvs.receive_basis_id=10
                and inv_rcvs.receive_against_id in (7,109)
                $datefrom $dateto $companyCond $itemAccountId $supplierId $identityId $storeId
            order by 
                itemcategories.id,
                itemclasses.id
        ");
        $data=collect($rows)
		->map(function($data) {
            $data->item_desc=$data->item_description.', '.$data->specification;
            $data->receive_date=date('d-M-Y',strtotime($data->receive_date));
			$data->qty=number_format($data->qty,2);
			$data->rate=number_format($data->rate,4);
			$data->amount=number_format($data->amount,2);
			return $data;
		});
		echo json_encode($data);
    }
}