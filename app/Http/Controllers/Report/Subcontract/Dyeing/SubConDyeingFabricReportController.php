<?php
namespace App\Http\Controllers\Report\Subcontract\Dyeing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvItemRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvItemRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRefRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingItemRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiRepository;
use Illuminate\Support\Carbon;

class SubConDyeingFabricReportController extends Controller
{
  private $localexppi;
  private $soknityarnrcv;
  private $itemaccount;
  private $autoyarn;
  private $buyerbranch;
  private $company;
  private $buyer;
  private $gmtspart;
  private $colorrange;
  private $color;
  private $sodyeing;
  private $uom;
  private $teammember;

  private $sodyeingdlvitem;
  private $sodyeingfabricrcvitem;
  private $sodyeingitem;

  public function __construct(
        SoDyeingDlvRepository $sodyeingdlv,
        SoDyeingRepository $sodyeing,
        SoDyeingDlvItemRepository $sodyeingdlvitem,
        SoDyeingFabricRcvRepository $sodyeingfabricrcv, 
        SoDyeingFabricRcvItemRepository $sodyeingfabricrcvitem,
        CompanyRepository $company, 
        BuyerRepository $buyer,
        UomRepository $uom,
        CurrencyRepository $currency,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        SoDyeingRefRepository $podyeingref, 
        SoDyeingItemRepository $sodyeingitem, 
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        TeammemberRepository $teammember,
        LocalExpPiRepository $localexppi
    ) {
        $this->sodyeingdlv = $sodyeingdlv;
        $this->sodyeing = $sodyeing;
        $this->sodyeingfabricrcv = $sodyeingfabricrcv;
        $this->sodyeingfabricrcvitem = $sodyeingfabricrcvitem;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->currency = $currency;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->sodyeingdlvitem = $sodyeingdlvitem;
        
        $this->podyeingref = $podyeingref;
        $this->sodyeingitem = $sodyeingitem;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->teammember = $teammember;
        $this->localexppi = $localexppi;

    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {
    $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
    $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');

    return Template::loadView('Report.Subcontract.Dyeing.SubConDyeingFabricReport',['buyer'=>$buyer,'company'=>$company]);
  }

  public function html(){
    $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
    $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
    $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
    $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
    $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
    $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
    $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

    $autoyarn=$this->autoyarn
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

    $rows=$this->reportData()->map(function($rows) use ($desDropdown,$color,$fabriclooks,$fabricshape,$gmtspart){
      $receive_date = Carbon::parse($rows->receive_date);
      $delivery_date = Carbon::parse($rows->delivery_date);
      if ($receive_date || $delivery_date) {
        $diff = $receive_date->diffInDays($delivery_date);
        $rows->lead_time=$diff;
      }
      
      $rows->receive_date=($rows->receive_date)?date('d-M-Y',strtotime($rows->receive_date)):'';
      $rows->delivery_date=($rows->delivery_date)?date('d-M-Y',strtotime($rows->delivery_date)):'';

      $rows->fabrication=/*$gmtspart[$rows->gmtspart_id].", ".*/$desDropdown[$rows->autoyarn_id]/*.", ".$fabriclooks[$rows->fabric_look_id].", ".$rows->gsm_weight.", ".$rows->dia*/;
      $rows->fabric_color=$color[$rows->fabric_color_id];
      $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
      $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
      $rows->gmtspart=$gmtspart[$rows->gmtspart_id];
      $batch_wip=$rows->grey_rcv_qty-$rows->batch_qty;
      $batch_bal=$rows->qty-$rows->batch_qty;
      $dyeing_wip=$rows->batch_qty-$rows->dyeing_qty;
      $dyeing_bal=$rows->qty-$rows->dyeing_qty;
      $fin_wip=$rows->dyeing_qty-$rows->fin_qty;
      $fin_bal=$rows->qty-$rows->fin_qty;
      $dlv_wip=$rows->fin_qty-$rows->dlv_qty;
      $dlv_bal=$rows->qty-$rows->dlv_qty;
      $bill_value=$rows->dlv_qty*$rows->rate;
      $bill_value_bal=$rows->amount-$bill_value;
      

      $rows->batch_wip=number_format($batch_wip,2);
      $rows->batch_bal=number_format($batch_bal,2);
      $rows->dyeing_wip=number_format($dyeing_wip,2);
      $rows->dyeing_bal=number_format($dyeing_bal,2);
      $rows->fin_wip=number_format($fin_wip,2);
      $rows->fin_bal=number_format($fin_bal,2);
      $rows->dlv_wip=number_format($dlv_wip,2);
      $rows->dlv_bal=number_format($dlv_bal,2);
      $rows->bill_value=number_format($bill_value,2);
      $rows->bill_value_bal=number_format($bill_value_bal,2);
     

      $rows->qty=number_format($rows->qty,2);
      $rows->grey_rcv_qty=number_format($rows->grey_rcv_qty,2);
      $rows->amount=number_format($rows->amount,2);
      $rows->rate=number_format($rows->rate,2);

      $rows->batch_qty=number_format($rows->batch_qty,2);
      $rows->dyeing_qty=number_format($rows->dyeing_qty,2);
      $rows->fin_qty=number_format($rows->fin_qty,2);  
      $rows->dlv_qty=number_format($rows->dlv_qty,2);
      $rows->grey_used=number_format($rows->grey_used,2);
      return $rows;
    });

    echo json_encode($rows);
  }

  private function reportData() {
    $company_id=request('company_id',0);
    $buyer_id=request('buyer_id',0);
    $sales_order_no=request('sales_order_no',0);
    $rcv_date_from=request('rcv_date_from',0);
    $rcv_date_to=request('rcv_date_to',0);
    $dlv_date_from=request('dlv_date_from',0);
    $dlv_date_to=request('dlv_date_to',0);

    $company=null;
    $buyer=null;
    $salesorder=null;
    $rcvdatefrom=null;
    $rcvdateto=null;
    $dlvdatefrom=null;
    $dlvdateto=null;
    if($company_id){
        $company=" and so_dyeings.company_id=$company_id";
    }
    if($buyer_id){
        $buyer=" and so_dyeings.buyer_id=$buyer_id";
    }
    if($sales_order_no){
        $salesorder=" and so_dyeings.sales_order_no like '%".$sales_order_no."%' ";
    }
    if($rcv_date_from){
        $rcvdatefrom=" and so_dyeings.receive_date >='".$rcv_date_from."'";
    }
    if($rcv_date_to){
        $rcvdateto=" and so_dyeings.receive_date <='".$rcv_date_to."'";
    }
    if($dlv_date_from){
        $dlvdatefrom=" and so_dyeing_items.delivery_date>='".$dlv_date_from."' ";
    }
    if($dlv_date_to){
        $dlvdateto=" and so_dyeing_items.delivery_date<='".$dlv_date_to."' ";
    }


    $rows = collect(
        \DB::select("
        select 
          so_dyeing_refs.id as so_dyeing_ref_id,
          so_dyeings.id as so_dyeing_id,
          so_dyeing_items.autoyarn_id,
          max(so_dyeing_items.delivery_date) as delivery_date,
          so_dyeing_items.fabric_color_id,
          so_dyeing_items.fabric_look_id,
          so_dyeing_items.fabric_shape_id,
          so_dyeing_items.gmtspart_id,
          so_dyeing_items.dia,
          so_dyeing_items.gsm_weight,
          so_dyeings.sales_order_no,
          so_dyeings.receive_date,
    
          companies.code as company_name,
          buyers.name as buyer_name,
          currencies.code as currency_code,
          gmt_buyers.name as gmt_buyer,
    
          sum(subconrcvs.grey_rcv_qty) as grey_rcv_qty,
          avg(subconrcvs.process_loss_per) as process_loss_per,
          sum(prodbatch.batch_qty) as batch_qty,
          sum(batchUnload.dyeing_qty) as dyeing_qty,
          sum(finishQc.fin_qty) as fin_qty,
          
          sum(finishDlv.dlv_qty) as dlv_qty,
          sum(finishDlv.grey_used) as grey_used,
    
          sum(so_dyeing_items.qty) as qty,
          avg(so_dyeing_items.rate) as rate,
          sum(so_dyeing_items.amount) as amount
      from 
      so_dyeings
      join so_dyeing_refs on so_dyeing_refs.so_dyeing_id=so_dyeings.id
      join so_dyeing_items on so_dyeing_refs.id=so_dyeing_items.so_dyeing_ref_id
      left join sub_inb_marketings on sub_inb_marketings.id=so_dyeings.sub_inb_marketing_id
      join companies on companies.id=so_dyeings.company_id
      join buyers on buyers.id=so_dyeings.buyer_id
      left join  buyers gmt_buyers on gmt_buyers.id=so_dyeing_items.gmt_buyer
      left join currencies on currencies.id=so_dyeings.currency_id
    
      left join(
        select 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        sum(so_dyeing_fabric_rcv_items.qty) as grey_rcv_qty,
        avg(so_dyeing_fabric_rcv_items.process_loss_per) as process_loss_per
        from
        so_dyeing_fabric_rcv_items
        where
        so_dyeing_fabric_rcv_items.deleted_at is null
        group by 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
      ) subconrcvs on subconrcvs.so_dyeing_ref_id=so_dyeing_refs.id
    
      left join (
        select
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        sum(prod_batch_rolls.qty) as batch_qty
        from prod_batches
        join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
        join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
        join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
        where prod_batches.batch_for=2
        and prod_batches.is_redyeing=0
        and so_dyeing_fabric_rcv_items.deleted_at is null
        and prod_batches.deleted_at is null
        and prod_batch_rolls.deleted_at is null
        group by so_dyeing_fabric_rcv_items.so_dyeing_ref_id
      ) prodbatch on prodbatch.so_dyeing_ref_id=so_dyeing_refs.id
      
      left join (
        select
            so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
            sum(prod_batch_rolls.qty) as dyeing_qty
        from prod_batches
            join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
            join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
            join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id      
        where prod_batches.batch_for=2 
            and prod_batches.loaded_at is not null
            and prod_batches.unloaded_at is not null
            and prod_batches.root_batch_id is  null
            and so_dyeing_fabric_rcv_items.deleted_at is null
        group by so_dyeing_fabric_rcv_items.so_dyeing_ref_id
      ) batchUnload on batchUnload.so_dyeing_ref_id=so_dyeing_refs.id
    
      left join (
        select
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        sum(prod_batch_finish_qc_rolls.qty) as fin_qty
        from
        prod_batch_finish_qcs
        join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_finish_qc_id=prod_batch_finish_qcs.id
        join prod_batch_rolls on prod_batch_rolls.id=prod_batch_finish_qc_rolls.prod_batch_roll_id
        join prod_batches on prod_batches.id=prod_batch_rolls.prod_batch_id
        join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
        join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
        where
        prod_batches.batch_for=2 and
        prod_batches.is_redyeing=0 and 
        prod_batches.deleted_at is null and 
        prod_batch_rolls.deleted_at is null  and
        prod_batches.unloaded_at is not null 
        group by
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
      )finishQc on finishQc.so_dyeing_ref_id=so_dyeing_refs.id
    
      left join (
        select
        so_dyeing_dlv_items.so_dyeing_ref_id,
        sum(so_dyeing_dlv_items.qty) as dlv_qty,
        sum(so_dyeing_dlv_items.grey_used) as grey_used
        from
        so_dyeing_dlvs
        join so_dyeing_dlv_items on so_dyeing_dlv_items.so_dyeing_dlv_id=so_dyeing_dlvs.id
        where so_dyeing_dlv_items.deleted_at is null
        and so_dyeing_dlvs.deleted_at is null
        group by
        so_dyeing_dlv_items.so_dyeing_ref_id
      )finishDlv on finishDlv.so_dyeing_ref_id=so_dyeing_refs.id
      
      where 1=1
          $company $buyer $rcvdatefrom $rcvdateto $salesorder $dlvdatefrom $dlvdateto
    group by
      so_dyeing_refs.id,
      so_dyeings.id,
      so_dyeing_items.autoyarn_id,
      so_dyeing_items.fabric_look_id,
      so_dyeing_items.fabric_shape_id,
      so_dyeing_items.gmtspart_id,
      so_dyeing_items.dia,
      so_dyeing_items.gsm_weight,
      so_dyeings.sales_order_no,
      so_dyeings.receive_date,
      companies.code,
      buyers.name,
      currencies.code,
      so_dyeing_items.fabric_color_id,
      gmt_buyers.name
        "));
       return $rows;
  }
  
   
}