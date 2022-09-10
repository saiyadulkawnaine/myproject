<?php
namespace App\Http\Controllers\Report\Subcontract\Dyeing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;

class FabricStockDyeingSubConPartyController extends Controller
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

    return Template::loadView('Report.Subcontract.Dyeing.FabricStockSubConDyeingParty',[]);
  }
  public function reportData() {
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $results = collect(
        \DB::select("
        select
        m.id,
        m.buyer_name as buyer_name,
        sum(m.rcv_all_qty) as rcv_all_qty,
        avg(m.rcv_all_rate) as rcv_all_rate,
        sum(m.rcv_all_amount) as rcv_all_amount,
        sum(m.rcv_open_qty) as rcv_open_qty,
        avg(m.rcv_open_rate) as rcv_open_rate,
        sum(m.rcv_open_amount) as rcv_open_amount,

        sum(m.dlv_fin_open_qty) as dlv_fin_open_qty,
        avg(m.dlv_fin_open_rate) as dlv_fin_open_rate,
        sum(m.dlv_fin_open_amount) as dlv_fin_open_amount,
        sum(m.dlv_grey_used_open_qty) as dlv_grey_used_open_qty,
        avg(m.dlv_grey_used_open_rate) as dlv_grey_used_open_rate,
        sum(m.dlv_grey_used_open_amount) as dlv_grey_used_open_amount,

        sum(m.rtn_open_qty) as rtn_open_qty,
        avg(m.rtn_open_rate) as rtn_open_rate,
        sum(m.rtn_open_amount) as rtn_open_amount,

        sum(m.rcv_qty) as rcv_qty,
        avg(m.rcv_rate) as rcv_rate,
        sum(m.rcv_amount) as rcv_amount,

        sum(m.dlv_fin_qty) as dlv_fin_qty,
        avg(m.dlv_fin_rate) as dlv_fin_rate,
        sum(m.dlv_fin_amount) as dlv_fin_amount,
        sum(m.dlv_grey_used_qty) as dlv_grey_used_qty,
        avg(m.dlv_grey_used_rate) as dlv_grey_used_rate,
        sum(m.dlv_grey_used_amount) as dlv_grey_used_amount,

        sum(m.rtn_qty) as rtn_qty,
        avg(m.rtn_rate) as rtn_rate,
        sum(m.rtn_amount) as rtn_amount

        from (select 
        buyers.id,
        buyers.name as buyer_name,
        fabric_rcv_all.qty as rcv_all_qty,
        fabric_rcv_all.rate as rcv_all_rate,
        fabric_rcv_all.amount as rcv_all_amount,

        fabric_rcv_opening.qty as rcv_open_qty,
        fabric_rcv_opening.rate as rcv_open_rate,
        fabric_rcv_opening.amount as rcv_open_amount,

        fabric_dlv_opening.fin_qty as dlv_fin_open_qty,
        fabric_dlv_opening.fin_rate as dlv_fin_open_rate,
        fabric_dlv_opening.fin_amount as dlv_fin_open_amount,
        fabric_dlv_opening.grey_used_qty as dlv_grey_used_open_qty,
        fabric_dlv_opening.grey_used_rate as dlv_grey_used_open_rate,
        fabric_dlv_opening.grey_used_amount as dlv_grey_used_open_amount,

        fabric_rtn_opening.qty as rtn_open_qty,
        fabric_rtn_opening.rate as rtn_open_rate,
        fabric_rtn_opening.amount as rtn_open_amount,

        fabric_rcv.qty as rcv_qty,
        fabric_rcv.rate as rcv_rate,
        fabric_rcv.amount as rcv_amount,

        fabric_dlv.fin_qty as dlv_fin_qty,
        fabric_dlv.fin_rate as dlv_fin_rate,
        fabric_dlv.fin_amount as dlv_fin_amount,
        fabric_dlv.grey_used_qty as dlv_grey_used_qty,
        fabric_dlv.grey_used_rate as dlv_grey_used_rate,
        fabric_dlv.grey_used_amount as dlv_grey_used_amount,

        fabric_rtn.qty as rtn_qty,
        fabric_rtn.rate as rtn_rate,
        fabric_rtn.amount as rtn_amount

        from buyers
        join buyer_natures on buyers.id=buyer_natures.buyer_id
        join (
        select
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        sum(so_dyeing_fabric_rcv_items.qty) as qty,
        avg(so_dyeing_fabric_rcv_items.rate) as rate,
        sum(so_dyeing_fabric_rcv_items.amount) as amount
        from 
        so_dyeing_fabric_rcvs
        join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcvs.id=so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id
        join so_dyeings on so_dyeings.id=so_dyeing_fabric_rcvs.so_dyeing_id
        where 
        so_dyeing_fabric_rcvs.receive_date <= ?
        and so_dyeing_fabric_rcv_items.deleted_at is null
        and so_dyeing_fabric_rcvs.deleted_at is null
        and so_dyeings.deleted_at is null
        --and so_dyeings.company_id = 5
        group by 
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        ) fabric_rcv_all on fabric_rcv_all.buyer_id=buyers.id

        left join (
        select
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        sum(so_dyeing_fabric_rcv_items.qty) as qty,
        avg(so_dyeing_fabric_rcv_items.rate) as rate,
        sum(so_dyeing_fabric_rcv_items.amount) as amount
        from 
        so_dyeing_fabric_rcvs
        join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcvs.id=so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id
        join so_dyeings on so_dyeings.id=so_dyeing_fabric_rcvs.so_dyeing_id
        where 
        so_dyeing_fabric_rcvs.receive_date < ?
        and so_dyeing_fabric_rcv_items.deleted_at is null
        and so_dyeing_fabric_rcvs.deleted_at is null
        and so_dyeings.deleted_at is null
        group by 
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        ) fabric_rcv_opening on fabric_rcv_opening.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_rcv_opening.so_dyeing_ref_id

        left join (
        select
        so_dyeing_dlvs.buyer_id,
        so_dyeing_dlv_items.so_dyeing_ref_id,
        sum(so_dyeing_dlv_items.qty) as fin_qty,
        avg(so_dyeing_dlv_items.rate) as fin_rate,
        sum(so_dyeing_dlv_items.amount) as fin_amount,
        sum(so_dyeing_dlv_items.grey_used) as grey_used_qty,
        greyusedrate.rate as grey_used_rate,
        sum(so_dyeing_dlv_items.grey_used)*greyusedrate.rate as grey_used_amount
        from 
        so_dyeing_dlvs
        join so_dyeing_dlv_items on so_dyeing_dlvs.id=so_dyeing_dlv_items.so_dyeing_dlv_id
        join(
        select 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        avg(so_dyeing_fabric_rcv_items.rate) as rate
        from so_dyeing_fabric_rcv_items
        where so_dyeing_fabric_rcv_items.qty>0
        and so_dyeing_fabric_rcv_items.rate >0 
        group by 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        )greyusedrate on greyusedrate.so_dyeing_ref_id=so_dyeing_dlv_items.so_dyeing_ref_id
        where 
        so_dyeing_dlvs.issue_date < ?
        and so_dyeing_dlv_items.deleted_at is null
        and so_dyeing_dlvs.deleted_at is null
        group by 
        so_dyeing_dlvs.buyer_id,
        so_dyeing_dlv_items.so_dyeing_ref_id,
        greyusedrate.rate
        ) fabric_dlv_opening on fabric_dlv_opening.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_dlv_opening.so_dyeing_ref_id

        left join (
        select
        so_dyeing_fabric_rtns.buyer_id,
        so_dyeing_fabric_rtn_items.so_dyeing_ref_id,

        sum(so_dyeing_fabric_rtn_items.qty) as qty,
        greyrtnrate.rate as rate,
        sum(so_dyeing_fabric_rtn_items.qty)*greyrtnrate.rate as amount
        from 
        so_dyeing_fabric_rtns
        join so_dyeing_fabric_rtn_items on so_dyeing_fabric_rtns.id=so_dyeing_fabric_rtn_items.so_dyeing_fabric_rtn_id
        join(
        select 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        avg(so_dyeing_fabric_rcv_items.rate) as rate
        from so_dyeing_fabric_rcv_items
        where so_dyeing_fabric_rcv_items.qty>0
        and so_dyeing_fabric_rcv_items.rate >0 
        group by 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        )greyrtnrate on greyrtnrate.so_dyeing_ref_id=so_dyeing_fabric_rtn_items.so_dyeing_ref_id


        where 
        so_dyeing_fabric_rtns.return_date < ?
        and so_dyeing_fabric_rtn_items.deleted_at is null
        and so_dyeing_fabric_rtns.deleted_at is null
        group by 
        so_dyeing_fabric_rtns.buyer_id,
        so_dyeing_fabric_rtn_items.so_dyeing_ref_id,
        greyrtnrate.rate
        ) fabric_rtn_opening on fabric_rtn_opening.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_rtn_opening.so_dyeing_ref_id

        left join (
        select
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        sum(so_dyeing_fabric_rcv_items.qty) as qty,
        avg(so_dyeing_fabric_rcv_items.rate) as rate,
        sum(so_dyeing_fabric_rcv_items.amount) as amount
        from 
        so_dyeing_fabric_rcvs
        join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcvs.id=so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id
        join so_dyeings on so_dyeings.id=so_dyeing_fabric_rcvs.so_dyeing_id
        where 
        so_dyeing_fabric_rcvs.receive_date >= ?
        and so_dyeing_fabric_rcvs.receive_date <= ?
        and so_dyeing_fabric_rcv_items.deleted_at is null
        and so_dyeing_fabric_rcvs.deleted_at is null
        and so_dyeings.deleted_at is null
        group by 
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        ) fabric_rcv on fabric_rcv.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_rcv.so_dyeing_ref_id


        left join (
        select
        so_dyeing_dlvs.buyer_id,
        so_dyeing_dlv_items.so_dyeing_ref_id,
        sum(so_dyeing_dlv_items.qty) as fin_qty,
        avg(so_dyeing_dlv_items.rate) as fin_rate,
        sum(so_dyeing_dlv_items.amount) as fin_amount,
        sum(so_dyeing_dlv_items.grey_used) as grey_used_qty,
        greyusedrate.rate as grey_used_rate,
        sum(so_dyeing_dlv_items.grey_used)*greyusedrate.rate as grey_used_amount
        from 
        so_dyeing_dlvs
        join so_dyeing_dlv_items on so_dyeing_dlvs.id=so_dyeing_dlv_items.so_dyeing_dlv_id
        join(
        select 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        avg(so_dyeing_fabric_rcv_items.rate) as rate
        from so_dyeing_fabric_rcv_items
        where so_dyeing_fabric_rcv_items.qty>0
        and so_dyeing_fabric_rcv_items.rate >0 
        group by 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        )greyusedrate on greyusedrate.so_dyeing_ref_id=so_dyeing_dlv_items.so_dyeing_ref_id
        where 
        so_dyeing_dlvs.issue_date >= ?
        and so_dyeing_dlvs.issue_date <= ?
        and so_dyeing_dlv_items.deleted_at is null
        and so_dyeing_dlvs.deleted_at is null
        group by 
        so_dyeing_dlvs.buyer_id,
        so_dyeing_dlv_items.so_dyeing_ref_id,
        greyusedrate.rate
        ) fabric_dlv on fabric_dlv.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_dlv.so_dyeing_ref_id

        left join (
        select
        so_dyeing_fabric_rtns.buyer_id,
        so_dyeing_fabric_rtn_items.so_dyeing_ref_id,

        sum(so_dyeing_fabric_rtn_items.qty) as qty,
        greyrtnrate.rate as rate,
        sum(so_dyeing_fabric_rtn_items.qty)*greyrtnrate.rate as amount
        from 
        so_dyeing_fabric_rtns
        join so_dyeing_fabric_rtn_items on so_dyeing_fabric_rtns.id=so_dyeing_fabric_rtn_items.so_dyeing_fabric_rtn_id
        join(
        select 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        avg(so_dyeing_fabric_rcv_items.rate) as rate
        from so_dyeing_fabric_rcv_items
        where so_dyeing_fabric_rcv_items.qty>0
        and so_dyeing_fabric_rcv_items.rate >0 
        group by 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        )greyrtnrate on greyrtnrate.so_dyeing_ref_id=so_dyeing_fabric_rtn_items.so_dyeing_ref_id


        where 
        so_dyeing_fabric_rtns.return_date >= ?
        and so_dyeing_fabric_rtns.return_date <= ?
        and so_dyeing_fabric_rtn_items.deleted_at is null
        and so_dyeing_fabric_rtns.deleted_at is null
        group by 
        so_dyeing_fabric_rtns.buyer_id,
        so_dyeing_fabric_rtn_items.so_dyeing_ref_id,
        greyrtnrate.rate
        ) fabric_rtn on fabric_rtn.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_rtn.so_dyeing_ref_id
        where buyer_natures.contact_nature_id = 3) m group by m.buyer_name,m.id order by m.id
        ", [$date_to,$date_from, $date_from, $date_from, $date_from, $date_to, $date_from, $date_to, $date_from, $date_to])
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
            if ($results->stock_qty) {
                $results->rate=$results->stock_value/$results->stock_qty;
            }
        	//$results->rate=$results->stock_value/$results->stock_qty;
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
          $buyerCond= ' and so_dyeings.buyer_id= '.$buyer_id;
        }
        else{
          $buyerCond= '';
        }

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

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        

        

        $results = collect(
          \DB::select("
				select
				so_dyeings.buyer_id,
				so_dyeing_items.autoyarn_id,
				so_dyeing_items.fabric_look_id,
				so_dyeing_items.fabric_shape_id,
				so_dyeing_items.dyeing_type_id,
				gmtsparts.name as gmtsparts_name,
				colorranges.name as color_range_name,
				so_dyeing_items.gsm_weight,
				colors.name as fabric_color_name,
				sum(so_dyeing_fabric_rcv_items.qty) as qty,
				avg(so_dyeing_fabric_rcv_items.rate) as rate,
				sum(so_dyeing_fabric_rcv_items.amount) as amount
				from 
				so_dyeing_fabric_rcvs
				join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcvs.id=so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id
				join so_dyeings on so_dyeings.id=so_dyeing_fabric_rcvs.so_dyeing_id
				join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
				left join colors on colors.id=so_dyeing_items.fabric_color_id
				left join colorranges on colorranges.id=so_dyeing_items.colorrange_id
				left join gmtsparts on gmtsparts.id=so_dyeing_items.gmtspart_id
				where 
				so_dyeing_fabric_rcvs.receive_date >= ?
				and so_dyeing_fabric_rcvs.receive_date <= ?
				$buyerCond
				--and so_dyeings.company_id=5
				and so_dyeing_fabric_rcv_items.deleted_at is null
				and so_dyeing_fabric_rcvs.deleted_at is null
				and so_dyeings.deleted_at is null
				group by 
				so_dyeings.buyer_id,
				so_dyeing_items.autoyarn_id,
				so_dyeing_items.fabric_look_id,
				so_dyeing_items.fabric_shape_id,
				so_dyeing_items.fabric_shape_id,
				so_dyeing_items.dyeing_type_id,
				gmtsparts.id,
				gmtsparts.name,
				colorranges.id,
				colorranges.name,
				so_dyeing_items.gsm_weight,
				colors.name
				order by 
				gmtsparts.id,
				so_dyeing_items.autoyarn_id,
				so_dyeing_items.fabric_look_id,
				so_dyeing_items.fabric_shape_id,
				so_dyeing_items.fabric_shape_id,
				colorranges.id,
				so_dyeing_items.dyeing_type_id
          ", [$date_from, $date_to])
        )
        ->map(function($results) use($desDropdown,$fabriclooks,$fabricshape,$dyetype){
          $results->fabric_desc=$desDropdown[$results->autoyarn_id];
          $results->fabric_shape_name=$fabricshape[$results->fabric_shape_id];
          $results->fabric_look_name=$fabriclooks[$results->fabric_look_id];
          $results->dyeing_type_name=$dyetype[$results->dyeing_type_id];
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
          $buyerCond= ' and so_dyeing_dlvs.buyer_id= '.$buyer_id;
        }
        else{
          $buyerCond= '';
        }

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

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        

        

        $results = collect(
          \DB::select("
			select
			so_dyeing_dlvs.buyer_id,
			so_dyeing_items.autoyarn_id,
			so_dyeing_items.fabric_look_id,
			so_dyeing_items.fabric_shape_id,
			so_dyeing_items.dyeing_type_id,
			gmtsparts.name as gmtsparts_name,
			colorranges.name as color_range_name,
			so_dyeing_items.gsm_weight,
			colors.name as fabric_color_name,
			sum(so_dyeing_dlv_items.qty) as fin_qty,
			avg(so_dyeing_dlv_items.rate) as fin_rate,
			sum(so_dyeing_dlv_items.amount) as fin_amount,
			sum(so_dyeing_dlv_items.grey_used) as qty,
			greyusedrate.rate as rate,
			sum(so_dyeing_dlv_items.grey_used)*greyusedrate.rate as amount
			from 
			so_dyeing_dlvs
			join so_dyeing_dlv_items on so_dyeing_dlvs.id=so_dyeing_dlv_items.so_dyeing_dlv_id
			join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_dlv_items.so_dyeing_ref_id
			left join colors on colors.id=so_dyeing_items.fabric_color_id
			left join colorranges on colorranges.id=so_dyeing_items.colorrange_id
			left join gmtsparts on gmtsparts.id=so_dyeing_items.gmtspart_id
			join(
			select 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
			avg(so_dyeing_fabric_rcv_items.rate) as rate
			from so_dyeing_fabric_rcv_items
			where so_dyeing_fabric_rcv_items.qty>0
			and so_dyeing_fabric_rcv_items.rate >0 
			group by 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			)greyusedrate on greyusedrate.so_dyeing_ref_id=so_dyeing_dlv_items.so_dyeing_ref_id
			where 
			so_dyeing_dlvs.issue_date >= ?
			and so_dyeing_dlvs.issue_date <= ?
			$buyerCond
			--and so_dyeing_dlvs.company_id=5
			and so_dyeing_dlv_items.deleted_at is null
			and so_dyeing_dlvs.deleted_at is null
			group by 
			so_dyeing_dlvs.buyer_id,
			greyusedrate.rate,
			so_dyeing_items.autoyarn_id,
			so_dyeing_items.fabric_look_id,
			so_dyeing_items.fabric_shape_id,
			so_dyeing_items.dyeing_type_id,
			gmtsparts.id,
			gmtsparts.name,
			colorranges.id,
			colorranges.name,
			so_dyeing_items.gsm_weight,
			colors.name
			order by 
			gmtsparts.id,
			so_dyeing_items.autoyarn_id,
			so_dyeing_items.fabric_look_id,
			so_dyeing_items.fabric_shape_id,
			so_dyeing_items.fabric_shape_id,
			colorranges.id,
			so_dyeing_items.dyeing_type_id
          ", [$date_from, $date_to])
        )
        ->map(function($results) use($desDropdown,$fabriclooks,$fabricshape,$dyetype){
          $results->fabric_desc=$desDropdown[$results->autoyarn_id];
          $results->fabric_shape_name=$fabricshape[$results->fabric_shape_id];
          $results->fabric_look_name=$fabriclooks[$results->fabric_look_id];
          $results->dyeing_type_name=$dyetype[$results->dyeing_type_id];
          $results->fin_qty=number_format($results->fin_qty,2);
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
          $buyerCond= ' and so_dyeing_fabric_rtns.buyer_id= '.$buyer_id;
        }
        else{
          $buyerCond= '';
        }

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

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        

        

        $results = collect(
          \DB::select("
			select
			so_dyeing_fabric_rtns.buyer_id,
			so_dyeing_items.autoyarn_id,
			so_dyeing_items.fabric_look_id,
			so_dyeing_items.fabric_shape_id,
			so_dyeing_items.dyeing_type_id,
			gmtsparts.name as gmtsparts_name,
			colorranges.name as color_range_name,
			so_dyeing_items.gsm_weight,
			colors.name as fabric_color_name,
			sum(so_dyeing_fabric_rtn_items.qty) as qty,
			greyrtnrate.rate as rate,
			sum(so_dyeing_fabric_rtn_items.qty)*greyrtnrate.rate as amount
			from 
			so_dyeing_fabric_rtns
			join so_dyeing_fabric_rtn_items on so_dyeing_fabric_rtns.id=so_dyeing_fabric_rtn_items.so_dyeing_fabric_rtn_id
			join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_fabric_rtn_items.so_dyeing_ref_id
			left join colors on colors.id=so_dyeing_items.fabric_color_id
			left join colorranges on colorranges.id=so_dyeing_items.colorrange_id
			left join gmtsparts on gmtsparts.id=so_dyeing_items.gmtspart_id
			join(
			select 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
			avg(so_dyeing_fabric_rcv_items.rate) as rate
			from so_dyeing_fabric_rcv_items
			where so_dyeing_fabric_rcv_items.qty>0
			and so_dyeing_fabric_rcv_items.rate >0 
			group by 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			)greyrtnrate on greyrtnrate.so_dyeing_ref_id=so_dyeing_fabric_rtn_items.so_dyeing_ref_id
			where 
			so_dyeing_fabric_rtns.return_date >= ?
			and so_dyeing_fabric_rtns.return_date <= ?
			$buyerCond
			--and so_dyeing_fabric_rtns.company_id=5
			and so_dyeing_fabric_rtn_items.deleted_at is null
			and so_dyeing_fabric_rtns.deleted_at is null
			group by 
			so_dyeing_fabric_rtns.buyer_id,
			greyrtnrate.rate,
			so_dyeing_items.autoyarn_id,
			so_dyeing_items.fabric_look_id,
			so_dyeing_items.fabric_shape_id,
			so_dyeing_items.dyeing_type_id,
			gmtsparts.id,
			gmtsparts.name,
			colorranges.id,
			colorranges.name,
			so_dyeing_items.gsm_weight,
			colors.name
			order by 
			gmtsparts.id,
			so_dyeing_items.autoyarn_id,
			so_dyeing_items.fabric_look_id,
			so_dyeing_items.fabric_shape_id,
			so_dyeing_items.fabric_shape_id,
			colorranges.id,
			so_dyeing_items.dyeing_type_id
          ", [$date_from, $date_to])
        )
        ->map(function($results) use($desDropdown,$fabriclooks,$fabricshape,$dyetype){
          $results->fabric_desc=$desDropdown[$results->autoyarn_id];
          $results->fabric_shape_name=$fabricshape[$results->fabric_shape_id];
          $results->fabric_look_name=$fabriclooks[$results->fabric_look_id];
          $results->dyeing_type_name=$dyetype[$results->dyeing_type_id];
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
          $buyerCond= ' and so_dyeings.buyer_id= '.$buyer_id;
        }
        else{
          $buyerCond= '';
        }

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

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        

        

        $results = collect(
          \DB::select("
          	select 
          	m.buyer_id,
			m.autoyarn_id,
			m.fabric_look_id,
			m.fabric_shape_id,
			m.dyeing_type_id,
			m.gmtspart_id,
			m.gmtsparts_name,
			m.colorrange_id,
			m.color_range_name,
			m.gsm_weight,
			m.fabric_color_name,
			

			sum(m.rcv_open_qty) as rcv_open_qty,
			sum(m.rcv_open_rate) as rcv_open_rate,
			sum(m.rcv_open_amount) as rcv_open_amount,

			sum(m.dlv_fin_open_qty) as dlv_fin_open_qty,
			sum(m.dlv_fin_open_rate) as dlv_fin_open_rate,
			sum(m.dlv_fin_open_amount) as dlv_fin_open_amount,
			sum(m.dlv_grey_used_open_qty) as dlv_grey_used_open_qty,
			sum(m.dlv_grey_used_open_rate) as dlv_grey_used_open_rate,
			sum(m.dlv_grey_used_open_amount) as dlv_grey_used_open_amount,

			sum(m.rtn_open_qty) as rtn_open_qty,
			sum(m.rtn_open_rate) as rtn_open_rate,
			sum(m.rtn_open_amount) as rtn_open_amount,

			sum(m.rcv_qty) as rcv_qty,
			sum(m.rcv_rate) as rcv_rate,
			sum(m.rcv_amount) as rcv_amount,

			sum(m.dlv_fin_qty) as dlv_fin_qty,
			sum(m.dlv_fin_rate) as dlv_fin_rate,
			sum(m.dlv_fin_amount) as dlv_fin_amount,
			sum(m.dlv_grey_used_qty) as dlv_grey_used_qty,
			sum(m.dlv_grey_used_rate) as dlv_grey_used_rate,
			sum(m.dlv_grey_used_amount) as dlv_grey_used_amount,

			sum(m.rtn_qty) as rtn_qty,
			sum(m.rtn_rate) as rtn_rate,
			sum(m.rtn_amount) as rtn_amount
			from
          	(
          	select 
			so_dyeings.buyer_id,
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
			so_dyeing_items.autoyarn_id,
			so_dyeing_items.fabric_look_id,
			so_dyeing_items.fabric_shape_id,
			so_dyeing_items.dyeing_type_id,
			gmtsparts.id as gmtspart_id,
			gmtsparts.name as gmtsparts_name,
			colorranges.id as colorrange_id,
			colorranges.name as color_range_name,
			so_dyeing_items.gsm_weight,
			colors.name as fabric_color_name,

			fabric_rcv_opening.qty as rcv_open_qty,
			fabric_rcv_opening.rate as rcv_open_rate,
			fabric_rcv_opening.amount as rcv_open_amount,

			fabric_dlv_opening.fin_qty as dlv_fin_open_qty,
			fabric_dlv_opening.fin_rate as dlv_fin_open_rate,
			fabric_dlv_opening.fin_amount as dlv_fin_open_amount,
			fabric_dlv_opening.grey_used_qty as dlv_grey_used_open_qty,
			fabric_dlv_opening.grey_used_rate as dlv_grey_used_open_rate,
			fabric_dlv_opening.grey_used_amount as dlv_grey_used_open_amount,

			fabric_rtn_opening.qty as rtn_open_qty,
			fabric_rtn_opening.rate as rtn_open_rate,
			fabric_rtn_opening.amount as rtn_open_amount,

			fabric_rcv.qty as rcv_qty,
			fabric_rcv.rate as rcv_rate,
			fabric_rcv.amount as rcv_amount,

			fabric_dlv.fin_qty as dlv_fin_qty,
			fabric_dlv.fin_rate as dlv_fin_rate,
			fabric_dlv.fin_amount as dlv_fin_amount,
			fabric_dlv.grey_used_qty as dlv_grey_used_qty,
			fabric_dlv.grey_used_rate as dlv_grey_used_rate,
			fabric_dlv.grey_used_amount as dlv_grey_used_amount,

			fabric_rtn.qty as rtn_qty,
			fabric_rtn.rate as rtn_rate,
			fabric_rtn.amount as rtn_amount

			from 
			so_dyeing_fabric_rcvs
			join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcvs.id=so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id
			join so_dyeings on so_dyeings.id=so_dyeing_fabric_rcvs.so_dyeing_id
			join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			left join colors on colors.id=so_dyeing_items.fabric_color_id
			left join colorranges on colorranges.id=so_dyeing_items.colorrange_id
			left join gmtsparts on gmtsparts.id=so_dyeing_items.gmtspart_id
			

			

			left join (
			select
			so_dyeings.buyer_id,
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
			sum(so_dyeing_fabric_rcv_items.qty) as qty,
			avg(so_dyeing_fabric_rcv_items.rate) as rate,
			sum(so_dyeing_fabric_rcv_items.amount) as amount
			from 
			so_dyeing_fabric_rcvs
			join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcvs.id=so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id
			join so_dyeings on so_dyeings.id=so_dyeing_fabric_rcvs.so_dyeing_id
			where 
			so_dyeing_fabric_rcvs.receive_date < ?
			and so_dyeing_fabric_rcv_items.deleted_at is null
			and so_dyeing_fabric_rcvs.deleted_at is null
			and so_dyeings.deleted_at is null
			group by 
			so_dyeings.buyer_id,
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			) fabric_rcv_opening on  so_dyeing_fabric_rcv_items.so_dyeing_ref_id=fabric_rcv_opening.so_dyeing_ref_id

			left join (
			select
			so_dyeing_dlvs.buyer_id,
			so_dyeing_dlv_items.so_dyeing_ref_id,
			sum(so_dyeing_dlv_items.qty) as fin_qty,
			avg(so_dyeing_dlv_items.rate) as fin_rate,
			sum(so_dyeing_dlv_items.amount) as fin_amount,
			sum(so_dyeing_dlv_items.grey_used) as grey_used_qty,
			greyusedrate.rate as grey_used_rate,
			sum(so_dyeing_dlv_items.grey_used)*greyusedrate.rate as grey_used_amount
			from 
			so_dyeing_dlvs
			join so_dyeing_dlv_items on so_dyeing_dlvs.id=so_dyeing_dlv_items.so_dyeing_dlv_id
			join(
			select 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
			avg(so_dyeing_fabric_rcv_items.rate) as rate
			from so_dyeing_fabric_rcv_items
			where so_dyeing_fabric_rcv_items.qty>0
			and so_dyeing_fabric_rcv_items.rate >0 
			group by 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			)greyusedrate on greyusedrate.so_dyeing_ref_id=so_dyeing_dlv_items.so_dyeing_ref_id


			where 
			so_dyeing_dlvs.issue_date < ?
			and so_dyeing_dlv_items.deleted_at is null
			and so_dyeing_dlvs.deleted_at is null
			group by 
			so_dyeing_dlvs.buyer_id,
			so_dyeing_dlv_items.so_dyeing_ref_id,
			greyusedrate.rate
			) fabric_dlv_opening on  so_dyeing_fabric_rcv_items.so_dyeing_ref_id=fabric_dlv_opening.so_dyeing_ref_id

			left join (
			select
			so_dyeing_fabric_rtns.buyer_id,
			so_dyeing_fabric_rtn_items.so_dyeing_ref_id,

			sum(so_dyeing_fabric_rtn_items.qty) as qty,
			greyrtnrate.rate as rate,
			sum(so_dyeing_fabric_rtn_items.qty)*greyrtnrate.rate as amount
			from 
			so_dyeing_fabric_rtns
			join so_dyeing_fabric_rtn_items on so_dyeing_fabric_rtns.id=so_dyeing_fabric_rtn_items.so_dyeing_fabric_rtn_id
			join(
			select 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
			avg(so_dyeing_fabric_rcv_items.rate) as rate
			from so_dyeing_fabric_rcv_items
			where so_dyeing_fabric_rcv_items.qty>0
			and so_dyeing_fabric_rcv_items.rate >0 
			group by 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			)greyrtnrate on greyrtnrate.so_dyeing_ref_id=so_dyeing_fabric_rtn_items.so_dyeing_ref_id


			where 
			so_dyeing_fabric_rtns.return_date < ?
			and so_dyeing_fabric_rtn_items.deleted_at is null
			and so_dyeing_fabric_rtns.deleted_at is null
			group by 
			so_dyeing_fabric_rtns.buyer_id,
			so_dyeing_fabric_rtn_items.so_dyeing_ref_id,
			greyrtnrate.rate
			) fabric_rtn_opening on  so_dyeing_fabric_rcv_items.so_dyeing_ref_id=fabric_rtn_opening.so_dyeing_ref_id

			left join (
			select
			so_dyeings.buyer_id,
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
			sum(so_dyeing_fabric_rcv_items.qty) as qty,
			avg(so_dyeing_fabric_rcv_items.rate) as rate,
			sum(so_dyeing_fabric_rcv_items.amount) as amount
			from 
			so_dyeing_fabric_rcvs
			join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcvs.id=so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id
			join so_dyeings on so_dyeings.id=so_dyeing_fabric_rcvs.so_dyeing_id
			where 
			so_dyeing_fabric_rcvs.receive_date >= ?
			and so_dyeing_fabric_rcvs.receive_date <= ?
			and so_dyeing_fabric_rcv_items.deleted_at is null
			and so_dyeing_fabric_rcvs.deleted_at is null
			and so_dyeings.deleted_at is null
			group by 
			so_dyeings.buyer_id,
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			) fabric_rcv on  so_dyeing_fabric_rcv_items.so_dyeing_ref_id=fabric_rcv.so_dyeing_ref_id


			left join (
			select
			so_dyeing_dlvs.buyer_id,
			so_dyeing_dlv_items.so_dyeing_ref_id,
			sum(so_dyeing_dlv_items.qty) as fin_qty,
			avg(so_dyeing_dlv_items.rate) as fin_rate,
			sum(so_dyeing_dlv_items.amount) as fin_amount,
			sum(so_dyeing_dlv_items.grey_used) as grey_used_qty,
			greyusedrate.rate as grey_used_rate,
			sum(so_dyeing_dlv_items.grey_used)*greyusedrate.rate as grey_used_amount
			from 
			so_dyeing_dlvs
			join so_dyeing_dlv_items on so_dyeing_dlvs.id=so_dyeing_dlv_items.so_dyeing_dlv_id
			join(
			select 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
			avg(so_dyeing_fabric_rcv_items.rate) as rate
			from so_dyeing_fabric_rcv_items
			where so_dyeing_fabric_rcv_items.qty>0
			and so_dyeing_fabric_rcv_items.rate >0 
			group by 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			)greyusedrate on greyusedrate.so_dyeing_ref_id=so_dyeing_dlv_items.so_dyeing_ref_id


			where 
			so_dyeing_dlvs.issue_date >= ?
			and so_dyeing_dlvs.issue_date <= ?
			and so_dyeing_dlv_items.deleted_at is null
			and so_dyeing_dlvs.deleted_at is null
			
			group by 
			so_dyeing_dlvs.buyer_id,
			so_dyeing_dlv_items.so_dyeing_ref_id,
			greyusedrate.rate
			) fabric_dlv on  so_dyeing_fabric_rcv_items.so_dyeing_ref_id=fabric_dlv.so_dyeing_ref_id

			left join (
			select
			so_dyeing_fabric_rtns.buyer_id,
			so_dyeing_fabric_rtn_items.so_dyeing_ref_id,

			sum(so_dyeing_fabric_rtn_items.qty) as qty,
			greyrtnrate.rate as rate,
			sum(so_dyeing_fabric_rtn_items.qty)*greyrtnrate.rate as amount
			from 
			so_dyeing_fabric_rtns
			join so_dyeing_fabric_rtn_items on so_dyeing_fabric_rtns.id=so_dyeing_fabric_rtn_items.so_dyeing_fabric_rtn_id
			join(
			select 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
			avg(so_dyeing_fabric_rcv_items.rate) as rate
			from so_dyeing_fabric_rcv_items
			where so_dyeing_fabric_rcv_items.qty>0
			and so_dyeing_fabric_rcv_items.rate >0 
			group by 
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			)greyrtnrate on greyrtnrate.so_dyeing_ref_id=so_dyeing_fabric_rtn_items.so_dyeing_ref_id


			where 
			so_dyeing_fabric_rtns.return_date >= ?
			and so_dyeing_fabric_rtns.return_date <= ?
			and so_dyeing_fabric_rtn_items.deleted_at is null
			and so_dyeing_fabric_rtns.deleted_at is null
			group by 
			so_dyeing_fabric_rtns.buyer_id,
			so_dyeing_fabric_rtn_items.so_dyeing_ref_id,
			greyrtnrate.rate
			) fabric_rtn on   so_dyeing_fabric_rcv_items.so_dyeing_ref_id=fabric_rtn.so_dyeing_ref_id
			where 
			so_dyeing_fabric_rcvs.receive_date <= ?
			$buyerCond
			--and so_dyeings.company_id=5
			and so_dyeing_fabric_rcv_items.deleted_at is null
			and so_dyeing_fabric_rcvs.deleted_at is null
			and so_dyeings.deleted_at is null
			and so_dyeing_items.deleted_at is null
			group by
			so_dyeings.buyer_id,
			so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
			so_dyeing_items.autoyarn_id,
			so_dyeing_items.fabric_look_id,
			so_dyeing_items.fabric_shape_id,
			so_dyeing_items.dyeing_type_id,
			gmtsparts.id,
			gmtsparts.name,
			colorranges.id,
			colorranges.name,
			so_dyeing_items.gsm_weight,
			colors.name,
			fabric_rcv_opening.qty,
			fabric_rcv_opening.rate,
			fabric_rcv_opening.amount,

			fabric_dlv_opening.fin_qty,
			fabric_dlv_opening.fin_rate,
			fabric_dlv_opening.fin_amount,
			fabric_dlv_opening.grey_used_qty,
			fabric_dlv_opening.grey_used_rate,
			fabric_dlv_opening.grey_used_amount,

			fabric_rtn_opening.qty,
			fabric_rtn_opening.rate,
			fabric_rtn_opening.amount,

			fabric_rcv.qty,
			fabric_rcv.rate,
			fabric_rcv.amount,

			fabric_dlv.fin_qty,
			fabric_dlv.fin_rate,
			fabric_dlv.fin_amount,
			fabric_dlv.grey_used_qty,
			fabric_dlv.grey_used_rate,
			fabric_dlv.grey_used_amount,
			fabric_rtn.qty,
			fabric_rtn.rate,
			fabric_rtn.amount
          	) m 
          	group by 
          	m.buyer_id,
			m.autoyarn_id,
			m.fabric_look_id,
			m.fabric_shape_id,
			m.dyeing_type_id,
			m.gmtspart_id,
			m.gmtsparts_name,
			m.colorrange_id,
			m.color_range_name,
			m.gsm_weight,
			m.fabric_color_name
			order by 
			m.gmtspart_id,
			m.autoyarn_id,
			m.fabric_look_id,
			m.fabric_shape_id,
			m.fabric_shape_id,
			m.colorrange_id,
			m.dyeing_type_id

			 
          ", [$date_from,$date_from,$date_from, $date_from, $date_to,$date_from, $date_to,$date_from, $date_to, $date_to])
        )
        ->map(function($results) use($desDropdown,$fabriclooks,$fabricshape,$dyetype){
			$results->fabric_desc=$desDropdown[$results->autoyarn_id];
			$results->fabric_shape_name=$fabricshape[$results->fabric_shape_id];
			$results->fabric_look_name=$fabriclooks[$results->fabric_look_id];
			$results->dyeing_type_name=$dyetype[$results->dyeing_type_id];
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
}
