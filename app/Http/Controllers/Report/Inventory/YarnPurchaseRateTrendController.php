<?php
namespace App\Http\Controllers\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;

class YarnPurchaseRateTrendController extends Controller
{

  private $invrcv;
  private $itemaccount;

  public function __construct(
    InvRcvRepository $invrcv,
    ItemAccountRepository $itemaccount
  )
  {
    $this->invrcv=$invrcv;
    $this->itemaccount=$itemaccount;
    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {

    return Template::loadView('Report.Inventory.YarnPurchaseRateTrend',[]);
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

        /*$invrcv=$this->invrcv
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
        ->where([['inv_rcvs.receive_against_id','=',3]])*/

        $results = collect(
          \DB::select("
          select 
          m.supplier_id,
          m.supplier_name,
          m.item_account_id,
          m.count,
          m.symbol,
          m.yarn_type,
          m.item_class_name,
          m.rcv_year,
          m.rcv_month,
          m.rcv_month_no,
          m.store_rate, 
          m.rate 
          from 
          (
          select 
          count(inv_yarn_rcv_items.id) as id,
          inv_rcvs.supplier_id,
          suppliers.name as supplier_name,
          inv_yarn_items.item_account_id,
          yarncounts.count,
          yarncounts.symbol,
          yarntypes.name as yarn_type,
          itemclasses.name as item_class_name,
          inv_rcvs.receive_date,
          to_char(inv_rcvs.receive_date, 'Mon') as rcv_month,
          to_char(inv_rcvs.receive_date, 'MM') as rcv_month_no,
          to_char(inv_rcvs.receive_date, 'yy') as rcv_year,
          inv_yarn_rcv_items.store_rate,
          inv_yarn_rcv_items.rate
          from 
          inv_rcvs
          join
          inv_yarn_rcvs on inv_yarn_rcvs.inv_rcv_id=inv_rcvs.id
          join 
          inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_rcv_id=inv_yarn_rcvs.id
          join 
          inv_yarn_items on inv_yarn_items.id=inv_yarn_rcv_items.inv_yarn_item_id
          join suppliers on suppliers.id=inv_rcvs.supplier_id
          join item_accounts on item_accounts.id=inv_yarn_items.item_account_id
          join yarncounts on yarncounts.id=item_accounts.yarncount_id
          join yarntypes on yarntypes.id=item_accounts.yarntype_id
          join itemclasses on itemclasses.id=item_accounts.itemclass_id
          where
          inv_rcvs.receive_date >= ? and
          inv_rcvs.receive_date <= ? and  
          inv_rcvs.receive_against_id=3 and
          inv_rcvs.receive_basis_id=1 and 
          inv_rcvs.deleted_at is null and 
          inv_yarn_rcvs.deleted_at is null and 
          inv_yarn_rcv_items.deleted_at is null 
          group by 
          inv_rcvs.supplier_id,
          suppliers.name,
          inv_yarn_items.item_account_id,
          yarncounts.count,
          yarncounts.symbol,
          yarntypes.name,
          itemclasses.name,
          inv_rcvs.receive_date,
          inv_yarn_rcv_items.store_rate,
          inv_yarn_rcv_items.rate
          order by 
          inv_yarn_items.item_account_id,
          inv_rcvs.receive_date
          ) m 
          group by 
          m.supplier_id,
          m.supplier_name,
          m.item_account_id,
          m.count,
          m.symbol,
          m.yarn_type,
          m.item_class_name,
          m.rcv_year,
          m.rcv_month,
          m.rcv_month_no,
          m.store_rate,
          m.rate
          order by
          m.item_account_id,
          m.rcv_year,
          m.rcv_month_no", [$date_from,$date_to])
        )->map(function($results) use($yarnDropdown){
          if($results->rate)
          {
            $results->exch_rate=$results->store_rate/$results->rate;
          }
          else{
            $results->exch_rate=$results->store_rate/1;
          }
          $results->count_name=$results->count."/".$results->symbol;
          $results->composition=$yarnDropdown[$results->item_account_id];
          $results->month=$results->rcv_month."-".$results->rcv_year;
          $results->store_rate=number_format($results->store_rate,2);
          $results->rate=number_format($results->rate,2);
          return $results;
        });
        echo json_encode($results);
    }
}
