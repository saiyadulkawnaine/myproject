<?php
namespace App\Http\Controllers\Report\Subcontract\Kniting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;

class YarnStockKnitingSubConPartyController extends Controller
{

  private $soknityarnrcv;
  private $itemaccount;
  private $autoyarn;

  public function __construct(
    SoKnitYarnRcvRepository $soknityarnrcv,
    ItemAccountRepository $itemaccount,
    AutoyarnRepository $autoyarn
  )
  {
    $this->soknityarnrcv=$soknityarnrcv;
    $this->itemaccount=$itemaccount;
    $this->autoyarn=$autoyarn;
    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {

    return Template::loadView('Report.Subcontract.Kniting.YarnStockSubConKnitingParty',[]);
  }
  public function reportData() {
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $results = collect(
        \DB::select("
                    select 
                    buyers.id,
                    buyers.name as buyer_name,
                    yarn_rcv_opening.qty as rcv_open_qty,
                    yarn_rcv_opening.amount as rcv_open_amount,

                    yarn_used_opening.qty as dlv_grey_used_open_qty,
                    yarn_used_opening.amount as dlv_grey_used_open_amount,
                    yarn_dlv_opening.qty as dlv_fin_open_qty,

                    yarn_rtn_opening.qty as rtn_open_qty,
                    yarn_rtn_opening.amount as rtn_open_amount,

                    yarn_rcv.qty as rcv_qty,
                    yarn_rcv.amount as rcv_amount,

                    yarn_used.qty as dlv_grey_used_qty,
                    yarn_used.amount as dlv_grey_used_amount,
                    yarn_dlv.qty as dlv_fin_qty,

                    yarn_rtn.qty as rtn_qty,
                    yarn_rtn.amount as rtn_amount
                    from buyers
                    join buyer_natures on buyers.id=buyer_natures.buyer_id
                    join (
                    select
                    so_knits.buyer_id,
                    sum(so_knit_yarn_rcv_items.qty) as qty,
                    sum(so_knit_yarn_rcv_items.amount) as amount
                    from 
                    so_knit_yarn_rcvs
                    join so_knit_yarn_rcv_items on so_knit_yarn_rcvs.id=so_knit_yarn_rcv_items.so_knit_yarn_rcv_id
                    join so_knits on so_knits.id=so_knit_yarn_rcvs.so_knit_id
                    where 
                    so_knit_yarn_rcvs.receive_date < ?
                    and so_knit_yarn_rcv_items.deleted_at is null
                    and so_knit_yarn_rcvs.deleted_at is null
                    and so_knits.deleted_at is null
                    group by 
                    so_knits.buyer_id
                    ) yarn_rcv_all on yarn_rcv_all.buyer_id=buyers.id


                    left join (
                    select
                    so_knits.buyer_id,
                    sum(so_knit_yarn_rcv_items.qty) as qty,
                    sum(so_knit_yarn_rcv_items.amount) as amount
                    from 
                    so_knit_yarn_rcvs
                    join so_knit_yarn_rcv_items on so_knit_yarn_rcvs.id=so_knit_yarn_rcv_items.so_knit_yarn_rcv_id
                    join so_knits on so_knits.id=so_knit_yarn_rcvs.so_knit_id
                    where 
                    so_knit_yarn_rcvs.receive_date < ?
                    and so_knit_yarn_rcv_items.deleted_at is null
                    and so_knit_yarn_rcvs.deleted_at is null
                    and so_knits.deleted_at is null
                    group by 
                    so_knits.buyer_id
                    ) yarn_rcv_opening on yarn_rcv_opening.buyer_id=buyers.id

                    left join (
                    select
                    so_knit_dlvs.buyer_id,
                    sum(so_knit_dlv_item_yarns.qty) as qty,
                    sum(so_knit_dlv_item_yarns.qty*so_knit_yarn_rcv_items.rate) as amount
                    from 
                    so_knit_dlvs
                    join so_knit_dlv_items on so_knit_dlvs.id=so_knit_dlv_items.so_knit_dlv_id
                    join so_knit_dlv_item_yarns on so_knit_dlv_item_yarns.so_knit_dlv_item_id=so_knit_dlv_items.id
                    join so_knit_yarn_rcv_items on so_knit_yarn_rcv_items.id=so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id
                    where 
                    so_knit_dlvs.issue_date < ?
                    and so_knit_dlvs.deleted_at is null
                    and so_knit_dlv_items.deleted_at is null
                    and so_knit_dlv_item_yarns.deleted_at is null
                    group by 
                    so_knit_dlvs.buyer_id
                    ) yarn_used_opening on yarn_used_opening.buyer_id=buyers.id


                    left join (
                    select
                    so_knit_dlvs.buyer_id,
                    sum(so_knit_dlv_items.qty) as qty
                    from 
                    so_knit_dlvs
                    join so_knit_dlv_items on so_knit_dlvs.id=so_knit_dlv_items.so_knit_dlv_id
                    where 
                    so_knit_dlvs.issue_date < ?
                    and so_knit_dlvs.deleted_at is null
                    and so_knit_dlv_items.deleted_at is null
                    group by 
                    so_knit_dlvs.buyer_id
                    ) yarn_dlv_opening on yarn_dlv_opening.buyer_id=buyers.id



                    left join (
                    select
                    so_knit_yarn_rtns.buyer_id,
                    sum(so_knit_yarn_rtn_items.qty) as qty,
                    sum(so_knit_yarn_rtn_items.amount) as amount
                    from 
                    so_knit_yarn_rtns
                    join so_knit_yarn_rtn_items on so_knit_yarn_rtns.id=so_knit_yarn_rtn_items.so_knit_yarn_rtn_id
                    where 
                    so_knit_yarn_rtns.return_date < ?
                    and so_knit_yarn_rtns.deleted_at is null
                    and so_knit_yarn_rtn_items.deleted_at is null
                    group by 
                    so_knit_yarn_rtns.buyer_id
                    ) yarn_rtn_opening on yarn_rtn_opening.buyer_id=buyers.id

                    left join (
                    select
                    so_knits.buyer_id,
                    sum(so_knit_yarn_rcv_items.qty) as qty,
                    sum(so_knit_yarn_rcv_items.amount) as amount
                    from 
                    so_knit_yarn_rcvs
                    join so_knit_yarn_rcv_items on so_knit_yarn_rcvs.id=so_knit_yarn_rcv_items.so_knit_yarn_rcv_id
                    join so_knits on so_knits.id=so_knit_yarn_rcvs.so_knit_id
                    where 
                    so_knit_yarn_rcvs.receive_date >= ?
                    and so_knit_yarn_rcvs.receive_date <= ?
                    and so_knit_yarn_rcv_items.deleted_at is null
                    and so_knit_yarn_rcvs.deleted_at is null
                    and so_knits.deleted_at is null
                    group by 
                    so_knits.buyer_id
                    ) yarn_rcv on yarn_rcv.buyer_id=buyers.id

                    left join (
                    select
                    so_knit_dlvs.buyer_id,
                    sum(so_knit_dlv_item_yarns.qty) as qty,
                    sum(so_knit_dlv_item_yarns.qty*so_knit_yarn_rcv_items.rate) as amount
                    from 
                    so_knit_dlvs
                    join so_knit_dlv_items on so_knit_dlvs.id=so_knit_dlv_items.so_knit_dlv_id
                    join so_knit_dlv_item_yarns on so_knit_dlv_item_yarns.so_knit_dlv_item_id=so_knit_dlv_items.id
                    join so_knit_yarn_rcv_items on so_knit_yarn_rcv_items.id=so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id
                    where 
                    so_knit_dlvs.issue_date >= ?
                    and so_knit_dlvs.issue_date <= ?
                    and so_knit_dlvs.deleted_at is null
                    and so_knit_dlv_items.deleted_at is null
                    and so_knit_dlv_item_yarns.deleted_at is null
                    group by 
                    so_knit_dlvs.buyer_id
                    ) yarn_used on yarn_used.buyer_id=buyers.id

                    left join (
                    select
                    so_knit_dlvs.buyer_id,
                    sum(so_knit_dlv_items.qty) as qty
                    from 
                    so_knit_dlvs
                    join so_knit_dlv_items on so_knit_dlvs.id=so_knit_dlv_items.so_knit_dlv_id
                    where 
                    so_knit_dlvs.issue_date >= ?
                    and so_knit_dlvs.issue_date <= ?
                    and so_knit_dlvs.deleted_at is null
                    and so_knit_dlv_items.deleted_at is null
                    group by 
                    so_knit_dlvs.buyer_id
                    ) yarn_dlv on yarn_dlv.buyer_id=buyers.id

                    left join (
                    select
                    so_knit_yarn_rtns.buyer_id,
                    sum(so_knit_yarn_rtn_items.qty) as qty,
                    sum(so_knit_yarn_rtn_items.amount) as amount
                    from 
                    so_knit_yarn_rtns
                    join so_knit_yarn_rtn_items on so_knit_yarn_rtns.id=so_knit_yarn_rtn_items.so_knit_yarn_rtn_id
                    where 
                    so_knit_yarn_rtns.return_date >= ?
                    and so_knit_yarn_rtns.return_date <= ?
                    and so_knit_yarn_rtns.deleted_at is null
                    and so_knit_yarn_rtn_items.deleted_at is null
                    group by 
                    so_knit_yarn_rtns.buyer_id
                    ) yarn_rtn on yarn_rtn.buyer_id=buyers.id
                    where 
                    buyer_natures.contact_nature_id = 2
                    and buyer_natures.deleted_at is null
                    order by 
                    buyers.name
        ",[ $date_to, $date_from, $date_from, $date_from, $date_from, $date_from,$date_to, $date_from,$date_to, $date_from,$date_to, $date_from,$date_to])
        )
        ->map(function($results){
        	$results->opening_qty=$results->rcv_open_qty-($results->dlv_grey_used_open_qty+$results->rtn_open_qty);
        	$results->opening_amount=$results->rcv_open_amount-($results->dlv_grey_used_open_amount+$results->rtn_open_amount);
        	$results->total_rcv_qty=$results->rcv_qty+$results->opening_qty;
        	$results->total_rcv_amount=$results->rcv_amount+$results->opening_amount;

        	$results->total_adjusted=$results->dlv_grey_used_qty+$results->rtn_qty;
        	$results->total_adjusted_amount=$results->dlv_grey_used_amount+$results->rtn_amount;
        	$results->stock_qty=$results->total_rcv_qty-$results->total_adjusted;
        	$results->stock_value=$results->total_rcv_amount-$results->total_adjusted_amount;
            $results->rate=0;
            if($results->stock_qty){
            $results->rate=$results->stock_value/$results->stock_qty;
            }
        	$results->opening_qty=number_format($results->opening_qty,2);
        	$results->rcv_qty=number_format($results->rcv_qty,2);
        	$results->total_rcv_qty=number_format($results->total_rcv_qty,2);
        	$results->dlv_fin_qty=number_format($results->dlv_fin_qty,2);
        	$results->dlv_grey_used_qty=number_format($results->dlv_grey_used_qty,2);
        	$results->rtn_qty=number_format($results->rtn_qty,2);
        	$results->total_adjusted=number_format($results->total_adjusted,2);
        	$results->stock_qty=number_format($results->stock_qty,2);
        	$results->rate=number_format($results->rate,2);
        	$results->stock_value=number_format($results->stock_value,2);

        return $results;
        });
        echo json_encode($results);
    }

    

    public function receiveDtl(){

        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $buyer_id=request('buyer_id',0);

        $buyerCond='';
        if($buyer_id){
          $buyerCond= ' and so_knits.buyer_id= '.$buyer_id;
        }
        else{
          $buyerCond= '';
        }

        /*$autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
          $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
          $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
          return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
          return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
          'autoyarns.*',
          'constructions.name',
          'compositions.name as composition_name',
          'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
          $fabricDescriptionArr[$row->id]=$row->name;
          $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
          $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');*/

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

        

        

        $results = collect(
          \DB::select("
                select
                so_knits.buyer_id,
                so_knit_yarn_rcv_items.item_account_id,
                yarncounts.count,
                yarncounts.symbol,
                yarntypes.name as yarn_type,
                itemclasses.name as item_class_name,
                so_knit_yarn_rcv_items.lot,
                so_knit_yarn_rcv_items.supplier_name,
                so_knit_yarn_rcv_items.color_id,
                colors.name as yarn_color,
                sum(so_knit_yarn_rcv_items.qty) as qty,
                avg(so_knit_yarn_rcv_items.rate) as rate,
                sum(so_knit_yarn_rcv_items.amount) as amount
                from 
                so_knit_yarn_rcvs
                join so_knit_yarn_rcv_items on so_knit_yarn_rcvs.id=so_knit_yarn_rcv_items.so_knit_yarn_rcv_id
                join so_knits on so_knits.id=so_knit_yarn_rcvs.so_knit_id
                join item_accounts on item_accounts.id=so_knit_yarn_rcv_items.item_account_id
                join yarncounts on yarncounts.id=item_accounts.yarncount_id
                join yarntypes on yarntypes.id=item_accounts.yarntype_id
                join itemclasses on itemclasses.id=item_accounts.itemclass_id
                left join colors on colors.id=so_knit_yarn_rcv_items.color_id
                where 
                so_knit_yarn_rcvs.receive_date >= ?
                and so_knit_yarn_rcvs.receive_date <= ?
                $buyerCond
                and so_knits.company_id=4

                and so_knit_yarn_rcv_items.deleted_at is null
                and so_knit_yarn_rcvs.deleted_at is null
                and so_knits.deleted_at is null
                group by 
                so_knits.buyer_id,
                so_knit_yarn_rcv_items.item_account_id,
                yarncounts.count,
                yarncounts.symbol,
                yarntypes.name,
                itemclasses.name,

                so_knit_yarn_rcv_items.lot,
                so_knit_yarn_rcv_items.supplier_name,
                so_knit_yarn_rcv_items.color_id,
                colors.name
          ", [$date_from, $date_to])
        )
        ->map(function($results) use($yarnDropdown){
          $results->yarn_desc=$yarnDropdown[$results->item_account_id];
          $results->yarn_count=$results->count."/".$results->symbol;
          $results->qty=number_format($results->qty,2);
          $results->amount=number_format($results->amount,2);
          $results->rate=number_format($results->rate,2);
          return $results;
        });
        echo json_encode($results);
    }

    public function usedDtl(){

        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $buyer_id=request('buyer_id',0);

        $buyerCond='';
        if($buyer_id){
          $buyerCond= ' and so_knit_dlvs.buyer_id= '.$buyer_id;
        }
        else{
          $buyerCond= '';
        }

        /*$autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
          $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
          $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
          return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
          return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
          'autoyarns.*',
          'constructions.name',
          'compositions.name as composition_name',
          'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
          $fabricDescriptionArr[$row->id]=$row->name;
          $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
          $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');*/

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

        

        

        $results = collect(
          \DB::select("
            select
            so_knit_dlvs.buyer_id,
            so_knit_yarn_rcv_items.item_account_id,
            yarncounts.count,
            yarncounts.symbol,
            yarntypes.name as yarn_type,
            itemclasses.name as item_class_name,
            so_knit_yarn_rcv_items.lot,
            so_knit_yarn_rcv_items.supplier_name,
            so_knit_yarn_rcv_items.color_id,
            colors.name as yarn_color,
            sum(so_knit_dlv_item_yarns.qty) as qty,
            avg(so_knit_yarn_rcv_items.rate) as rate,
            sum(so_knit_dlv_item_yarns.qty*so_knit_yarn_rcv_items.rate) as amount
            from 
            so_knit_dlvs
            join so_knit_dlv_items on so_knit_dlvs.id=so_knit_dlv_items.so_knit_dlv_id
            join so_knit_dlv_item_yarns on so_knit_dlv_item_yarns.so_knit_dlv_item_id=so_knit_dlv_items.id
            join so_knit_yarn_rcv_items on so_knit_yarn_rcv_items.id=so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id
            join item_accounts on item_accounts.id=so_knit_yarn_rcv_items.item_account_id
            join yarncounts on yarncounts.id=item_accounts.yarncount_id
            join yarntypes on yarntypes.id=item_accounts.yarntype_id
            join itemclasses on itemclasses.id=item_accounts.itemclass_id
            left join colors on colors.id=so_knit_yarn_rcv_items.color_id
            where 
            so_knit_dlvs.issue_date >= ?
            and so_knit_dlvs.issue_date <= ?
            $buyerCond
            and so_knit_dlvs.company_id=4

            and so_knit_dlvs.deleted_at is null
            and so_knit_dlv_items.deleted_at is null
            and so_knit_dlv_item_yarns.deleted_at is null
            group by 
            so_knit_dlvs.buyer_id,
            so_knit_yarn_rcv_items.item_account_id,
            yarncounts.count,
            yarncounts.symbol,
            yarntypes.name,
            itemclasses.name,
            so_knit_yarn_rcv_items.lot,
            so_knit_yarn_rcv_items.supplier_name,
            so_knit_yarn_rcv_items.color_id,
            colors.name
          ", [$date_from, $date_to])
        )
        ->map(function($results) use($yarnDropdown){
          $results->yarn_desc=$yarnDropdown[$results->item_account_id];
          $results->yarn_count=$results->count."/".$results->symbol;
          $results->qty=number_format($results->qty,2);
          $results->amount=number_format($results->amount,2);
          $results->rate=number_format($results->rate,2);
          return $results;
        });
        echo json_encode($results);
    }

    public function returnDtl(){

        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $buyer_id=request('buyer_id',0);

        $buyerCond='';
        if($buyer_id){
          $buyerCond= ' and so_knit_yarn_rtns.buyer_id= '.$buyer_id;
        }
        else{
          $buyerCond= '';
        }

        /*$autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
          $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
          $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
          return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
          return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
          'autoyarns.*',
          'constructions.name',
          'compositions.name as composition_name',
          'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
          $fabricDescriptionArr[$row->id]=$row->name;
          $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
          $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');*/

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

        

        

        $results = collect(
          \DB::select("
                select
                so_knit_yarn_rtns.buyer_id,
                so_knit_yarn_rcv_items.item_account_id,
                yarncounts.count,
                yarncounts.symbol,
                yarntypes.name as yarn_type,
                itemclasses.name as item_class_name,
                so_knit_yarn_rcv_items.lot,
                so_knit_yarn_rcv_items.supplier_name,
                so_knit_yarn_rcv_items.color_id,
                colors.name as yarn_color,
                sum(so_knit_yarn_rtn_items.qty) as qty,
                avg(so_knit_yarn_rcv_items.rate) as rate,
                sum(so_knit_yarn_rtn_items.amount) as amount
                from 
                so_knit_yarn_rtns
                join so_knit_yarn_rtn_items on so_knit_yarn_rtns.id=so_knit_yarn_rtn_items.so_knit_yarn_rtn_id
                join so_knit_yarn_rcv_items on so_knit_yarn_rcv_items.id=so_knit_yarn_rtn_items.so_knit_yarn_rcv_item_id
                join item_accounts on item_accounts.id=so_knit_yarn_rcv_items.item_account_id
                join yarncounts on yarncounts.id=item_accounts.yarncount_id
                join yarntypes on yarntypes.id=item_accounts.yarntype_id
                join itemclasses on itemclasses.id=item_accounts.itemclass_id
                left join colors on colors.id=so_knit_yarn_rcv_items.color_id
                where 
                so_knit_yarn_rtns.return_date >= ?
                and so_knit_yarn_rtns.return_date <= ?
                $buyerCond
                and so_knit_yarn_rtns.company_id=4
                and so_knit_yarn_rtns.deleted_at is null
                and so_knit_yarn_rtn_items.deleted_at is null
                group by 
                so_knit_yarn_rtns.buyer_id,
                so_knit_yarn_rcv_items.item_account_id,
                yarncounts.count,
                yarncounts.symbol,
                yarntypes.name,
                itemclasses.name,
                so_knit_yarn_rcv_items.lot,
                so_knit_yarn_rcv_items.supplier_name,
                so_knit_yarn_rcv_items.color_id,
                colors.name
          ", [$date_from, $date_to])
        )
        ->map(function($results) use($yarnDropdown){
          $results->yarn_desc=$yarnDropdown[$results->item_account_id];
          $results->yarn_count=$results->count."/".$results->symbol;
          $results->qty=number_format($results->qty,2);
          $results->amount=number_format($results->amount,2);
          $results->rate=number_format($results->rate,2);
          return $results;
        });
        echo json_encode($results);
    }

    public function closingDtl(){

        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $buyer_id=request('buyer_id',0);

        $buyerCond='';
        if($buyer_id){
          $buyerCond= ' and so_knits.buyer_id= '.$buyer_id;
        }
        else{
          $buyerCond= '';
        }

        /*$autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
          $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
          $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
          return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
          return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
          'autoyarns.*',
          'constructions.name',
          'compositions.name as composition_name',
          'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
          $fabricDescriptionArr[$row->id]=$row->name;
          $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
          $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');*/

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

        

        

        $results = collect(
          \DB::select("
            select 
            m.buyer_id,
            m.item_account_id,
            m.count,
            m.symbol,
            m.yarn_type,
            m.item_class_name,
            m.lot,
            m.supplier_name,
            m.color_id,
            m.yarn_color,


            sum(m.rcv_open_qty) as rcv_open_qty,
            sum(m.rcv_open_amount) as rcv_open_amount,

            sum(m.dlv_grey_used_open_qty) as dlv_grey_used_open_qty,
            sum(m.dlv_grey_used_open_amount) as dlv_grey_used_open_amount,

            sum(m.rtn_open_qty) as rtn_open_qty,
            sum(m.rtn_open_amount) as rtn_open_amount,

            sum(m.rcv_qty) as rcv_qty,
            sum(m.rcv_amount) as rcv_amount,

            sum(m.dlv_grey_used_qty) as dlv_grey_used_qty,
            sum(m.dlv_grey_used_amount) as dlv_grey_used_amount,

            sum(m.rtn_qty) as rtn_qty,
            sum(m.rtn_amount) as rtn_amount
            from
            (
            select
            so_knits.buyer_id,
            so_knit_yarn_rcv_items.id,
            so_knit_yarn_rcv_items.item_account_id,
            yarncounts.count,
            yarncounts.symbol,
            yarntypes.name as yarn_type,
            itemclasses.name as item_class_name,
            so_knit_yarn_rcv_items.lot,
            so_knit_yarn_rcv_items.supplier_name,
            so_knit_yarn_rcv_items.color_id,
            colors.name as yarn_color,
            sum(so_knit_yarn_rcv_items.qty) as qty,
            sum(so_knit_yarn_rcv_items.amount) as amount,
            yarn_rcv_opening.qty as rcv_open_qty,
            yarn_rcv_opening.amount as rcv_open_amount,
            yarn_used_opening.qty as dlv_grey_used_open_qty,
            yarn_used_opening.amount as dlv_grey_used_open_amount,
            yarn_rtn_opening.qty as rtn_open_qty,
            yarn_rtn_opening.amount as rtn_open_amount,
            yarn_rcv.qty as rcv_qty,
            yarn_rcv.amount as rcv_amount,
            yarn_used.qty as dlv_grey_used_qty,
            yarn_used.amount as dlv_grey_used_amount,
            yarn_rtn.qty as rtn_qty,
            yarn_rtn.amount as rtn_amount
            from 
            so_knit_yarn_rcvs
            join so_knit_yarn_rcv_items on so_knit_yarn_rcvs.id=so_knit_yarn_rcv_items.so_knit_yarn_rcv_id
            join so_knits on so_knits.id=so_knit_yarn_rcvs.so_knit_id
            join item_accounts on item_accounts.id=so_knit_yarn_rcv_items.item_account_id
            join yarncounts on yarncounts.id=item_accounts.yarncount_id
            join yarntypes on yarntypes.id=item_accounts.yarntype_id
            join itemclasses on itemclasses.id=item_accounts.itemclass_id
            left join colors on colors.id=so_knit_yarn_rcv_items.color_id

            left join (
            select
            so_knit_yarn_rcv_items.id ,
            sum(so_knit_yarn_rcv_items.qty) as qty,
            sum(so_knit_yarn_rcv_items.amount) as amount
            from 
            so_knit_yarn_rcvs
            join so_knit_yarn_rcv_items on so_knit_yarn_rcvs.id=so_knit_yarn_rcv_items.so_knit_yarn_rcv_id
            join so_knits on so_knits.id=so_knit_yarn_rcvs.so_knit_id
            where 
            so_knit_yarn_rcvs.receive_date < ?
            and so_knit_yarn_rcv_items.deleted_at is null
            and so_knit_yarn_rcvs.deleted_at is null
            and so_knits.deleted_at is null
            group by 
            so_knit_yarn_rcv_items.id) yarn_rcv_opening on yarn_rcv_opening.id=so_knit_yarn_rcv_items.id

            left join (
            select
            so_knit_yarn_rcv_items.id,
            sum(so_knit_dlv_item_yarns.qty) as qty,
            sum(so_knit_dlv_item_yarns.qty*so_knit_yarn_rcv_items.rate) as amount
            from 
            so_knit_dlvs
            join so_knit_dlv_items on so_knit_dlvs.id=so_knit_dlv_items.so_knit_dlv_id
            join so_knit_dlv_item_yarns on so_knit_dlv_item_yarns.so_knit_dlv_item_id=so_knit_dlv_items.id
            join so_knit_yarn_rcv_items on so_knit_yarn_rcv_items.id=so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id
            where 
            so_knit_dlvs.issue_date < ?
            and so_knit_dlvs.deleted_at is null
            and so_knit_dlv_items.deleted_at is null
            and so_knit_dlv_item_yarns.deleted_at is null
            group by 
            so_knit_yarn_rcv_items.id
            ) yarn_used_opening on yarn_used_opening.id=so_knit_yarn_rcv_items.id

            left join (
            select
            so_knit_yarn_rcv_items.id,
            sum(so_knit_yarn_rtn_items.qty) as qty,
            sum(so_knit_yarn_rtn_items.amount) as amount
            from 
            so_knit_yarn_rtns
            join so_knit_yarn_rtn_items on so_knit_yarn_rtns.id=so_knit_yarn_rtn_items.so_knit_yarn_rtn_id
            join so_knit_yarn_rcv_items on so_knit_yarn_rcv_items.id=so_knit_yarn_rtn_items.so_knit_yarn_rcv_item_id
            where 
            so_knit_yarn_rtns.return_date < ?
            and so_knit_yarn_rtns.deleted_at is null
            and so_knit_yarn_rtn_items.deleted_at is null
            group by 
            so_knit_yarn_rcv_items.id
            ) yarn_rtn_opening on yarn_rtn_opening.id=so_knit_yarn_rcv_items.id

            left join (
            select
            so_knit_yarn_rcv_items.id,
            sum(so_knit_yarn_rcv_items.qty) as qty,
            sum(so_knit_yarn_rcv_items.amount) as amount
            from 
            so_knit_yarn_rcvs
            join so_knit_yarn_rcv_items on so_knit_yarn_rcvs.id=so_knit_yarn_rcv_items.so_knit_yarn_rcv_id
            join so_knits on so_knits.id=so_knit_yarn_rcvs.so_knit_id
            where 
            so_knit_yarn_rcvs.receive_date >= ?
            and so_knit_yarn_rcvs.receive_date <= ?
            and so_knit_yarn_rcv_items.deleted_at is null
            and so_knit_yarn_rcvs.deleted_at is null
            and so_knits.deleted_at is null
            group by 
            so_knit_yarn_rcv_items.id
            ) yarn_rcv on yarn_rcv.id=so_knit_yarn_rcv_items.id

            left join (
            select
            so_knit_yarn_rcv_items.id,
            sum(so_knit_dlv_item_yarns.qty) as qty,
            sum(so_knit_dlv_item_yarns.qty*so_knit_yarn_rcv_items.rate) as amount
            from 
            so_knit_dlvs
            join so_knit_dlv_items on so_knit_dlvs.id=so_knit_dlv_items.so_knit_dlv_id
            join so_knit_dlv_item_yarns on so_knit_dlv_item_yarns.so_knit_dlv_item_id=so_knit_dlv_items.id
            join so_knit_yarn_rcv_items on so_knit_yarn_rcv_items.id=so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id
            where 
            so_knit_dlvs.issue_date >= ?
            and so_knit_dlvs.issue_date <= ?
            and so_knit_dlvs.deleted_at is null
            and so_knit_dlv_items.deleted_at is null
            and so_knit_dlv_item_yarns.deleted_at is null
            group by 
            so_knit_yarn_rcv_items.id
            ) yarn_used on yarn_used.id=so_knit_yarn_rcv_items.id

            left join (
            select
            so_knit_yarn_rcv_items.id,
            sum(so_knit_yarn_rtn_items.qty) as qty,
            sum(so_knit_yarn_rtn_items.amount) as amount
            from 
            so_knit_yarn_rtns
            join so_knit_yarn_rtn_items on so_knit_yarn_rtns.id=so_knit_yarn_rtn_items.so_knit_yarn_rtn_id
            join so_knit_yarn_rcv_items on so_knit_yarn_rcv_items.id=so_knit_yarn_rtn_items.so_knit_yarn_rcv_item_id
            where 
            so_knit_yarn_rtns.return_date >= ?
            and so_knit_yarn_rtns.return_date <= ?
            and so_knit_yarn_rtns.deleted_at is null
            and so_knit_yarn_rtn_items.deleted_at is null
            group by 
            so_knit_yarn_rcv_items.id
            ) yarn_rtn on yarn_rtn.id=so_knit_yarn_rcv_items.id

            where 
            so_knit_yarn_rcvs.receive_date < ?
            $buyerCond
            and so_knits.company_id=4
            and so_knit_yarn_rcv_items.deleted_at is null
            and so_knit_yarn_rcvs.deleted_at is null
            and so_knits.deleted_at is null
            group by 
            so_knits.buyer_id,
            so_knit_yarn_rcv_items.id,
            so_knit_yarn_rcv_items.item_account_id,
            yarncounts.count,
            yarncounts.symbol,
            yarntypes.name,
            itemclasses.name,
            so_knit_yarn_rcv_items.lot,
            so_knit_yarn_rcv_items.supplier_name,
            so_knit_yarn_rcv_items.color_id,
            colors.name,
            yarn_rcv_opening.qty,
            yarn_rcv_opening.amount,
            yarn_used_opening.qty ,
            yarn_used_opening.amount ,
            yarn_rtn_opening.qty,
            yarn_rtn_opening.amount,
            yarn_rcv.qty,
            yarn_rcv.amount,
            yarn_used.qty,
            yarn_used.amount,
            yarn_rtn.qty,
            yarn_rtn.amount
            ) m 
            group by 
            m.buyer_id,
            m.item_account_id,
            m.count,
            m.symbol,
            m.yarn_type,
            m.item_class_name,
            m.lot,
            m.supplier_name,
            m.color_id,
            m.yarn_color
            order by 
            m.item_account_id,
            m.count,
            m.symbol,
            m.yarn_type

			 
          ", [$date_from,$date_from,$date_from, $date_from, $date_to,$date_from, $date_to,$date_from, $date_to, $date_to])
        )
        ->map(function($results) use($yarnDropdown){
			$results->yarn_desc=$yarnDropdown[$results->item_account_id];
            $results->yarn_count=$results->count."/".$results->symbol;

			$results->opening_qty=$results->rcv_open_qty-($results->dlv_grey_used_open_qty+$results->rtn_open_qty);
			$results->opening_amount=$results->rcv_open_amount-($results->dlv_grey_used_open_amount+$results->rtn_open_amount);
			$results->total_rcv_qty=$results->rcv_qty+$results->opening_qty;

			$results->total_rcv_amount=$results->rcv_amount+$results->opening_amount;

			$results->total_adjusted=$results->dlv_grey_used_qty+$results->rtn_qty;
			$results->total_adjusted_amount=$results->dlv_grey_used_amount+$results->rtn_amount;
			$results->stock_qty=$results->total_rcv_qty-$results->total_adjusted;
			$results->stock_value=$results->total_rcv_amount-$results->total_adjusted_amount;
			$results->rate=0;
			if($results->stock_qty){
			$results->rate=$results->stock_value/$results->stock_qty;
			}
			$results->opening_qty=number_format($results->opening_qty,2);
			$results->rcv_qty=number_format($results->rcv_qty,2);
			$results->total_rcv_qty=number_format($results->total_rcv_qty,2);
			//$results->dlv_fin_qty=number_format($results->dlv_fin_qty,2);
			$results->dlv_grey_used_qty=number_format($results->dlv_grey_used_qty,2);
			$results->rtn_qty=number_format($results->rtn_qty,2);
			$results->total_adjusted=number_format($results->total_adjusted,2);
			$results->stock_qty=number_format($results->stock_qty,2);
			$results->rate=number_format($results->rate,2);
			$results->stock_value=number_format($results->stock_value,2);

			return $results;
        });
        echo json_encode($results);
    }
}
