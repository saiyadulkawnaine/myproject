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

class YarnReceiveSummeryController extends Controller
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


    return Template::loadView('Report.Inventory.YarnReceiveSummery',['supplier'=>$supplier,'company'=>$company]);
  }
  public function reportData() {
  	

        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $rcv_id=request('rcv_id',0);
        $rcvidarr=explode(',',$rcv_id);
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
        inv_rcvs.id,
        inv_rcvs.receive_no,
        inv_rcvs.receive_date,
        inv_rcvs.challan_no,
        inv_yarn_rcv_items.id,
        suppliers.name as supplier_name,
        companies.code as company_name,
        inv_yarn_items.item_account_id,
        inv_yarn_items.lot,
        inv_yarn_items.brand,
        colors.name as yarn_color,
        yarncounts.count,
        yarncounts.symbol,
        yarntypes.name as yarn_type,
        itemclasses.name as item_class_name,
        inv_yarn_rcv_items.qty,
        inv_yarn_rcv_items.rate,
        inv_yarn_rcv_items.amount,
        inv_yarn_rcv_items.store_qty,
        inv_yarn_rcv_items.store_rate,
        inv_yarn_rcv_items.store_amount,
        inv_yarn_rcv_items.no_of_bag,
        inv_yarn_rcv_items.cone_per_bag,
        inv_yarn_rcv_items.wgt_per_cone,
        inv_yarn_rcv_items.remarks
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
        ->join('companies',function($join){
        $join->on('companies.id','=','inv_rcvs.company_id');
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
        ->when(request('rcv_id'), function ($q) use($rcvidarr){
        return $q->whereIn('inv_rcvs.id',$rcvidarr);
        })
        /*->groupBy([
        'inv_rcvs.id',
        'inv_rcvs.receive_no',
        'inv_rcvs.receive_date',
        'inv_rcvs.challan_no',
        'inv_yarn_rcv_items.id',
        'suppliers.name',
        'inv_yarn_items.id',
        'inv_yarn_items.item_account_id',
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'colors.name',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name',
        'itemclasses.name',
        'inv_yarn_rcv_items.qty',
        'inv_yarn_rcv_items.rate',
        'inv_yarn_rcv_items.amount',
        'inv_yarn_rcv_items.store_qty',
        'inv_yarn_rcv_items.store_rate',
        'inv_yarn_rcv_items.store_amount',
        'inv_yarn_rcv_items.no_of_bag'
        ])*/
        ->orderBy('inv_rcvs.id')
        ->get()
        ->map(function($results) use($yarnDropdown){
          $results->count_name=$results->count."/".$results->symbol;
          $results->composition=$yarnDropdown[$results->item_account_id];
          $results->receive_date=date('d-M-Y',strtotime($results->receive_date));
          $results->exch_rate=0;
          if($results->rate){
              $results->exch_rate=$results->store_rate/$results->rate;
          }
          $results->exch_rate=number_format($results->exch_rate,2);
          $results->rate=number_format($results->rate,2);
          $results->qty=number_format($results->qty,2);
          $results->amount=number_format($results->amount,2);
          $results->store_rate=number_format($results->store_rate,2);
          $results->store_amount=number_format($results->store_amount,2);
          $results->no_of_bag=number_format($results->no_of_bag,0);
          $results->wgt_per_bag=number_format($results->cone_per_bag*$results->wgt_per_cone,2);

          
          $results->uom='Kg';
          return $results;
        });
        echo json_encode($results);
    }

    public function getMrr() {
        $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');

        $rows = $this->invrcv
        ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('suppliers',function($join){
            $join->on('suppliers.id','=','inv_rcvs.supplier_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','inv_rcvs.company_id');
        })
        ->leftJoin(\DB::raw("(
          select
          inv_yarn_rcvs.id as inv_yarn_rcv_id,
          po_yarns.po_no,
          po_yarns.pi_no,
          importlc.lc_no_i,
          importlc.lc_no_ii,
          importlc.lc_no_iii,
          importlc.lc_no_iv
          from
          inv_yarn_rcvs
          join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_rcv_id=inv_yarn_rcvs.id
          join po_yarn_items on po_yarn_items.id=inv_yarn_rcv_items.po_yarn_item_id
          join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
          left join(
            select 
              imp_lc_pos.purchase_order_id,
              imp_lcs.lc_no_i,
              imp_lcs.lc_no_ii,
              imp_lcs.lc_no_iii,
              imp_lcs.lc_no_iv
              from imp_lc_pos
              join po_yarn_dyeings on imp_lc_pos.purchase_order_id=po_yarn_dyeings.id
              join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
              where imp_lcs.menu_id=3
            group by 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
          )importlc on importlc.purchase_order_id=po_yarns.id
          group by
          inv_yarn_rcvs.id,
          po_yarns.po_no,
          po_yarns.pi_no,
          importlc.lc_no_i,
          importlc.lc_no_ii,
          importlc.lc_no_iii,
          importlc.lc_no_iv
        ) poYarn"), "poYarn.inv_yarn_rcv_id", "=", "inv_yarn_rcvs.id")
        ->where([['inv_rcvs.menu_id','=',100]])
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
        ->orderBy('inv_rcvs.id')
        ->get([
            'inv_rcvs.*',
            'inv_yarn_rcvs.id as inv_yarn_rcv_id',
            'companies.name as company_id',
            'suppliers.name as supplier_id',
            'poYarn.po_no',
            'poYarn.pi_no',
            'poYarn.lc_no_i',
            'poYarn.lc_no_ii',
            'poYarn.lc_no_iii',
            'poYarn.lc_no_iv'

        ])
        ->map(function($rows) use($invreceivebasis){
            $rows->import_lc_no=$rows->lc_no_i.$rows->lc_no_ii.$rows->lc_no_iii.$rows->lc_no_iv;
            $rows->lc_no=($rows->lc_no_i!==null)?$rows->import_lc_no:'--';
            $rows->pi_no=($rows->pi_no)?$rows->pi_no:'--';
            $rows->receive_basis_id=$invreceivebasis[$rows->receive_basis_id];
            $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
            return $rows;
        });
        echo json_encode($rows);
    }
}
