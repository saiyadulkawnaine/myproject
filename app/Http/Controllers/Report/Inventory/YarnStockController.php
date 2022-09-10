<?php
namespace App\Http\Controllers\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\StoreRepository;

class YarnStockController extends Controller
{

  private $supplier;
  private $invyarnitem;
  private $itemaccount;
  private $store;

  public function __construct(
    SupplierRepository $supplier,
    InvYarnItemRepository $invyarnitem,
    ItemAccountRepository $itemaccount,
    StoreRepository $store
  )
  {
    $this->supplier = $supplier;
    $this->invyarnitem=$invyarnitem;
    $this->itemaccount=$itemaccount;
    $this->store=$store;
    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {
    $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');
    $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');

    return Template::loadView('Report.Inventory.YarnStock',['supplier'=>$supplier,'store'=>$store]);
  }
	public function reportData() {
      $store_id=request('store_id',0);
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $start_date=date('Y-m-d', strtotime($date_from));
      $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
      $storeCond='';
      if($store_id){
       $storeCond= ' and inv_yarn_transactions.store_id= '.$store_id;
      }
      else{
       $storeCond= '';
      }
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

      $invyarnrcvitem=$this->invyarnitem
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
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','inv_yarn_items.supplier_id');
      })
      ->leftJoin(\DB::raw("(
      select 
      inv_yarn_rcv_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_rcv_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
      join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_yarn_transactions.trans_type_id=1
      $storeCond
      group by inv_yarn_rcv_items.inv_yarn_item_id

      ) yarn_rcv"), "yarn_rcv.inv_yarn_item_id", "=", "inv_yarn_items.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_yarn_rcv_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_rcv_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
      join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
      where inv_rcvs.receive_date<'".$date_from."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_yarn_transactions.trans_type_id=1
      $storeCond
      group by inv_yarn_rcv_items.inv_yarn_item_id

      ) open_yarn_rcv"), "open_yarn_rcv.inv_yarn_item_id", "=", "inv_yarn_items.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_yarn_isu_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_isu_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
      join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_yarn_transactions.trans_type_id=2
      $storeCond
      group by inv_yarn_isu_items.inv_yarn_item_id

      ) yarn_isu"), "yarn_isu.inv_yarn_item_id", "=", "inv_yarn_items.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_yarn_isu_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_isu_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
      join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
      where inv_isus.issue_date<'".$date_from."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_yarn_transactions.trans_type_id=2
      $storeCond
      group by inv_yarn_isu_items.inv_yarn_item_id

      ) open_yarn_isu"), "open_yarn_isu.inv_yarn_item_id", "=", "inv_yarn_items.id")
      //->where([['inv_yarn_rcvs.id','=',$inv_yarn_rcv_id]])
      ->orderBy('yarncounts.count','desc')
      ->get([
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id as item_account_id',
      'yarncounts.count',
      'yarncounts.symbol',
      'yarntypes.name as yarn_type',
      'uoms.code as uom_code',
      'inv_yarn_items.id',
      'inv_yarn_items.lot',
      'inv_yarn_items.brand',
      'colors.name as color_name',
      'suppliers.name as supplier_name',
      'yarn_rcv.qty as receive_qty',
      'yarn_rcv.amount as receive_amount',
      'open_yarn_rcv.qty as open_receive_qty',
      'open_yarn_rcv.amount as open_receive_amount',
      'yarn_isu.qty as issue_qty',
      'yarn_isu.amount as issue_amount',
      'open_yarn_isu.qty as open_issue_qty',
      'open_yarn_isu.amount as open_issue_amount',
      ])
      ->map(function($invyarnrcvitem) use($yarnDropdown) {
      $invyarnrcvitem->count_name=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
      $invyarnrcvitem->type_name=$invyarnrcvitem->yarn_type;
      $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
      $invyarnrcvitem->issue_qty=$invyarnrcvitem->issue_qty*-1;

      $invyarnrcvitem->issue_amount=$invyarnrcvitem->issue_amount;

      $invyarnrcvitem->opening_qty=$invyarnrcvitem->open_receive_qty-($invyarnrcvitem->open_issue_qty*-1);
      $invyarnrcvitem->opening_amount=$invyarnrcvitem->open_receive_amount-($invyarnrcvitem->open_issue_amount);
      $invyarnrcvitem->stock_qty=($invyarnrcvitem->opening_qty+$invyarnrcvitem->receive_qty)-($invyarnrcvitem->issue_qty);
      $invyarnrcvitem->stock_value=($invyarnrcvitem->opening_amount+$invyarnrcvitem->receive_amount)-($invyarnrcvitem->issue_amount);
      $invyarnrcvitem->rate=0;
      if($invyarnrcvitem->stock_qty){
      $invyarnrcvitem->rate=$invyarnrcvitem->stock_value/$invyarnrcvitem->stock_qty;
      }
      $invyarnrcvitem->opening_qty=number_format($invyarnrcvitem->opening_qty,2);
      $invyarnrcvitem->receive_qty=number_format($invyarnrcvitem->receive_qty,2);
      $invyarnrcvitem->issue_qty=number_format($invyarnrcvitem->issue_qty,2);
      $invyarnrcvitem->stock_qty=number_format($invyarnrcvitem->stock_qty,2);
      $invyarnrcvitem->rate=number_format($invyarnrcvitem->rate,2);
      $invyarnrcvitem->stock_value=number_format($invyarnrcvitem->stock_value,2);
      return $invyarnrcvitem;
      }); 
      echo json_encode($invyarnrcvitem);
    }

     public function getReceiveQty(){
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $style_id=request('style_id',0);
      $style_gmt_name=request('style_gmt_name',0);
      $color_name=request('color_name',0);
      $size_name=request('size_name',0);

      $receiveqty=collect(\DB::select("
		select srm_product_receive_dtls.id as srm_product_receive_dtl_id,
		srm_product_receives.receive_date,
		exp_invoices.invoice_no,
		srm_product_receive_dtls.style_id,
		srm_product_receive_dtls.style_ref,
		srm_product_receive_dtls.style_gmt_name,
		srm_product_receive_dtls.size_name,
		srm_product_receive_dtls.color_name,
		sum(srm_product_receive_dtls.qty) as qty,
		avg(srm_product_receive_dtls.rate) as receive_rate,
		sum(srm_product_receive_dtls.amount) as receive_amount
		from srm_product_receives
		join exp_invoices on exp_invoices.id = srm_product_receives.exp_invoice_id 
		join srm_product_receive_dtls on srm_product_receives.id=srm_product_receive_dtls.srm_product_receive_id
		where srm_product_receives.receive_date>='".$date_from."' 
		and srm_product_receives.receive_date<='".$date_to."'
		and srm_product_receive_dtls.style_id = '".$style_id."'
		and srm_product_receive_dtls.style_gmt_name = '".$style_gmt_name."'
		and srm_product_receive_dtls.color_name = '".$color_name."'
		and srm_product_receive_dtls.size_name = '".$size_name."'

		GROUP BY 
		srm_product_receive_dtls.id,
		srm_product_receives.receive_date,
		exp_invoices.invoice_no,
		srm_product_receive_dtls.style_id,
		srm_product_receive_dtls.style_ref,
		srm_product_receive_dtls.style_gmt_name,
		srm_product_receive_dtls.size_name,
		srm_product_receive_dtls.color_name"))
      ->map(function($receiveqty){
        $receiveqty->receive_qty=number_format($receiveqty->qty,0);
        $receiveqty->receive_rate=number_format($receiveqty->receive_rate,4);
        $receiveqty->receive_amount=number_format($receiveqty->receive_amount,2);
        $receiveqty->receive_date=date('d-M-Y',strtotime($receiveqty->receive_date));
        return $receiveqty;
      });
      echo json_encode($receiveqty);
    }

    public function getSalesQty(){
      
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $style_id=request('style_id',0);
      $style_gmt_name=request('style_gmt_name',0);
      $color_name=request('color_name',0);
      $size_name=request('size_name',0);

      $salesqty=collect(\DB::select("
        select
        srm_product_receive_dtls.style_id,
        srm_product_receive_dtls.style_ref,
        srm_product_receive_dtls.style_gmt_name,
        srm_product_receive_dtls.size_name,
        srm_product_receive_dtls.color_name,
        srm_product_sales.scan_date,
        sum(srm_product_scans.qty) as qty,
        sum(srm_product_scans.amount) as amount,
        avg(srm_product_scans.sales_rate) as sales_rate

        FROM srm_product_sales
          left join srm_product_scans on 
          	srm_product_sales.id=srm_product_scans.srm_product_sale_id
          left join srm_product_receive_dtls on
            srm_product_scans.srm_product_receive_dtl_id=srm_product_receive_dtls.id

        
          where
          srm_product_sales.scan_date>='".$date_from."' 
          and srm_product_sales.scan_date<='".$date_to."'
          and srm_product_receive_dtls.style_id = '".$style_id."'
          and srm_product_receive_dtls.style_gmt_name = '".$style_gmt_name."'
          and srm_product_receive_dtls.color_name = '".$color_name."'
          and srm_product_receive_dtls.size_name = '".$size_name."'

        GROUP BY 
          srm_product_sales.scan_date,
          srm_product_receive_dtls.style_id,
          srm_product_receive_dtls.style_ref,
          srm_product_receive_dtls.style_gmt_name,
          srm_product_receive_dtls.size_name,
          srm_product_receive_dtls.color_name
      
      "))
      ->map(function($salesqty){
          $salesqty->sale_qty=number_format($salesqty->qty,0);
          $salesqty->sale_rate=number_format($salesqty->sales_rate,4);
          $salesqty->sale_amount=number_format($salesqty->amount,2);
          $salesqty->scan_date=date('d-M-Y',strtotime($salesqty->scan_date));
        return $salesqty;
      });
      echo json_encode($salesqty);
    }
}
