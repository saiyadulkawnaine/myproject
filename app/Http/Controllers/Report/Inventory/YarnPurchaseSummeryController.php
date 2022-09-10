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

class YarnPurchaseSummeryController extends Controller
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

  )
  {
    $this->invrcv=$invrcv;
    $this->itemaccount=$itemaccount;
    $this->supplier = $supplier;
    $this->company = $company;
    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {
    $supplier=array_prepend(array_pluck($this->supplier->yarnSupplier(),'name','id'),'-Select-','');
    $company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');


    return Template::loadView('Report.Inventory.YarnPurchaseSummery',['supplier'=>$supplier,'company'=>$company]);
  }
  public function reportData() {
  	

        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
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
        /*$results = collect(
          \DB::select("
                select 
                inv_yarn_items.item_account_id,
                yarncounts.count,
                yarncounts.symbol,
                yarntypes.name as yarn_type,
                itemclasses.name as item_class_name,
                sum(inv_yarn_rcv_items.store_qty) as qty,
                sum(inv_yarn_rcv_items.store_amount) as amount,
                sum(inv_yarn_rcv_items.no_of_bag) as no_of_bag

                from 
                inv_rcvs
                join
                inv_yarn_rcvs on inv_yarn_rcvs.inv_rcv_id=inv_rcvs.id
                join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_rcv_id=inv_yarn_rcvs.id
                join inv_yarn_items on inv_yarn_items.id=inv_yarn_rcv_items.inv_yarn_item_id
                join item_accounts on item_accounts.id=inv_yarn_items.item_account_id
                join yarncounts on yarncounts.id=item_accounts.yarncount_id
                join yarntypes on yarntypes.id=item_accounts.yarntype_id
                join itemclasses on itemclasses.id=item_accounts.itemclass_id
                where
                inv_rcvs.receive_date>=? and
                inv_rcvs.receive_date<=? and
                inv_rcvs.receive_against_id=3 and
                inv_rcvs.receive_basis_id=1 and 
                inv_rcvs.deleted_at is null and 
                inv_yarn_rcvs.deleted_at is null and 
                inv_yarn_rcv_items.deleted_at is null 
                group by 
                inv_yarn_items.item_account_id,
                yarncounts.count,
                yarncounts.symbol,
                yarntypes.name,
                itemclasses.name
                order by 
                inv_yarn_items.item_account_id", [$date_from,$date_to])
        )->map(function($results) use($yarnDropdown){
          $results->count_name=$results->count."/".$results->symbol;
          $results->composition=$yarnDropdown[$results->item_account_id];
          $results->rate=number_format($results->amount/$results->qty,2);
          $results->qty=number_format($results->qty,2);
          $results->amount=number_format($results->amount,2);
          $results->uom='Kg';
          return $results;
        });*/
        $results=$this->invrcv
        ->selectRaw('
        inv_yarn_items.item_account_id,
        yarncounts.count,
        yarncounts.symbol,
        yarntypes.name as yarn_type,
        itemclasses.name as item_class_name,
        sum(inv_yarn_rcv_items.store_qty) as qty,
        sum(inv_yarn_rcv_items.store_amount) as amount,
        sum(inv_yarn_rcv_items.no_of_bag) as no_of_bag
        ')
        ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_yarn_rcv_items',function($join){
        $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id');
        })
        ->join('inv_yarn_items',function($join){
        $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id');
        })
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_rcvs.supplier_id');
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
        ->where([['inv_rcvs.receive_against_id','=',3]])
        ->where([['inv_rcvs.receive_basis_id','=',1]])
        ->whereNull('inv_rcvs.deleted_at')
        ->whereNull('inv_yarn_rcvs.deleted_at')
        ->whereNull('inv_yarn_rcv_items.deleted_at')
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
        ->groupBy([
        'inv_yarn_items.item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name',
        'itemclasses.name'
        ])
        ->orderBy('yarncounts.count')
        ->get()
        ->map(function($results) use($yarnDropdown){
          $results->count_name=$results->count."/".$results->symbol;
          $results->composition=$yarnDropdown[$results->item_account_id];
          $results->rate=number_format($results->amount/$results->qty,2);
          $results->qty=number_format($results->qty,2);
          $results->amount=number_format($results->amount,2);
          $results->no_of_bag=number_format($results->no_of_bag,0);
          $results->uom='Kg';
          return $results;
        });
        echo json_encode($results);
    }

    public function getRcvQtyDtl(){
        $item_account_id=request('item_account_id',0);
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

        $results=$this->invrcv
        ->selectRaw('
        inv_yarn_items.item_account_id,
        inv_rcvs.supplier_id,
        suppliers.name as supplier_name,
        yarncounts.count,
        yarncounts.symbol,
        yarntypes.name as yarn_type,
        itemclasses.name as item_class_name,
        sum(inv_yarn_rcv_items.store_qty) as qty,
        sum(inv_yarn_rcv_items.store_amount) as amount,
        sum(inv_yarn_rcv_items.no_of_bag) as no_of_bag
        ')
        ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('inv_yarn_rcv_items',function($join){
        $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id');
        })
        ->join('inv_yarn_items',function($join){
        $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id');
        })
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_rcvs.supplier_id');
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
        ->where([['inv_yarn_items.item_account_id','=',$item_account_id]])
        ->where([['inv_rcvs.receive_against_id','=',3]])
        ->where([['inv_rcvs.receive_basis_id','=',1]])
        ->whereNull('inv_rcvs.deleted_at')
        ->whereNull('inv_yarn_rcvs.deleted_at')
        ->whereNull('inv_yarn_rcv_items.deleted_at')
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
        ->groupBy([
        'inv_yarn_items.item_account_id',
        'inv_rcvs.supplier_id',
        'suppliers.name',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name',
        'itemclasses.name'
        ])
        ->orderBy('yarncounts.count')
        ->get()
        ->map(function($results) use($yarnDropdown){
          $results->yarn_count=$results->count."/".$results->symbol;
          $results->composition=$yarnDropdown[$results->item_account_id];
          $results->rate=number_format($results->amount/$results->qty,2);
          $results->qty=number_format($results->qty,2);
          $results->amount=number_format($results->amount,2);
          $results->no_of_bag=number_format($results->no_of_bag,0);
          $results->uom='Kg';
          return $results;
        });
        echo json_encode($results);
    }
}
