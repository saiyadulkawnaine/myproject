<?php
namespace App\Http\Controllers\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRtnRepository;
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


class ReceiveDeliveryController extends Controller
{

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

  private $sodyeingdlvitem;
  private $sodyeingfabricrcvitem;
  private $sodyeingitem;
  private $sodyeingfabricrtn;

  public function __construct(
        SoDyeingDlvRepository $sodyeingdlv,
        SoDyeingRepository $sodyeing,
        SoDyeingDlvItemRepository $sodyeingdlvitem,
        SoDyeingFabricRcvRepository $sodyeingfabricrcv, 
        SoDyeingFabricRcvItemRepository $sodyeingfabricrcvitem,
        SoDyeingFabricRtnRepository $sodyeingfabricrtn,
        CompanyRepository $company, 
        BuyerRepository $buyer,
        UomRepository $uom,
        CurrencyRepository $currency,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        SoDyeingRefRepository $podyeingref, 
        SoDyeingItemRepository $sodyeingitem, 
        ColorrangeRepository $colorrange,
        ColorRepository $color
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
        $this->sodyeingfabricrtn = $sodyeingfabricrtn;
        $this->colorrange = $colorrange;
        $this->color = $color;

    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {
    $from=date('Y-m')."-01";
    $to=date('Y-m-d');
    $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
    $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
    return Template::loadView('Report.Inventory.ReceiveDelivery',['from'=>$from,'to'=>$to,'buyer'=>$buyer,'company'=>$company]);
  }
  public function reportData() {

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

    $company_id=request('company_id',0);
    $buyer_id=request('buyer_id',0);
    $date_from=request('date_from',0);
    $date_to=request('date_to',0);

   $receive= $this->sodyeingfabricrcv
   ->selectRaw('
        companies.code as company_name,
        buyers.name as buyer_name,
        so_dyeing_fabric_rcvs.id,
        so_dyeing_fabric_rcvs.receive_no as ref_no,
        so_dyeing_fabric_rcvs.receive_date as ref_date,
        so_dyeing_items.autoyarn_id,
        so_dyeing_items.fabric_look_id,
        so_dyeing_items.fabric_shape_id,
        so_dyeing_items.gmtspart_id,
        so_dyeing_items.gsm_weight,
        so_dyeing_items.fabric_color_id,
        colors.name as dyeing_color,
        so_dyeing_items.colorrange_id,
        so_dyeing_items.dyeing_type_id,
        so_dyeing_items.dia,
        uoms.code as uom_code,
        1 as trans_type,
        sum(so_dyeing_fabric_rcv_items.qty) as ref_qty,
        0 as grey_used_qty,
        0 as no_of_roll
        '
    )
    ->join('so_dyeings', function($join)  {
      $join->on('so_dyeings.id', '=', 'so_dyeing_fabric_rcvs.so_dyeing_id');
    })
    ->join('buyers', function($join)  {
      $join->on('so_dyeings.buyer_id', '=', 'buyers.id');
    })
    ->join('companies', function($join)  {
        $join->on('so_dyeings.company_id', '=', 'companies.id');
    })
    ->join('so_dyeing_fabric_rcv_items', function($join)  {
        $join->on('so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id', '=', 'so_dyeing_fabric_rcvs.id');
    })
    ->join('so_dyeing_refs', function($join)  {
        $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
        $join->on('so_dyeing_refs.so_dyeing_id', '=', 'so_dyeings.id');
    })
    ->join('so_dyeing_items', function($join)  {
        $join->on('so_dyeing_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
    })
    ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_dyeing_items.uom_id');
    })
    ->leftJoin('colors',function($join){
      $join->on('colors.id','=','so_dyeing_items.fabric_color_id');
    })

    ->when(request('buyer_id'), function ($q)  {
      return $q->where('so_dyeings.buyer_id', '=', request('buyer_id',0));
    })
    ->when(request('date_from'), function ($q) {
      return $q->where('so_dyeing_fabric_rcvs.receive_date', '>=',request('date_from', 0));
    })
    ->when(request('date_to'), function ($q) {
      return $q->where('so_dyeing_fabric_rcvs.receive_date', '<=',request('date_to', 0));
    })
    ->when(request('company_id'), function ($q) {
      return $q->where('so_dyeings.company_id', '=',  request('company_id',0));
    })
    ->orderBy('so_dyeing_fabric_rcvs.id','desc')
    ->groupBy([
    'companies.code',
    'buyers.name',
    'so_dyeing_fabric_rcvs.id',
    'so_dyeing_fabric_rcvs.receive_no',
    'so_dyeing_fabric_rcvs.receive_date',
    'so_dyeing_items.autoyarn_id',
    'so_dyeing_items.fabric_look_id',
    'so_dyeing_items.fabric_shape_id',
    'so_dyeing_items.gmtspart_id',
    'so_dyeing_items.gsm_weight',
    'so_dyeing_items.fabric_color_id',
    'so_dyeing_items.dia',
    'colors.name',
    'so_dyeing_items.colorrange_id',
    'so_dyeing_items.dyeing_type_id',
    'uoms.code'
    ])
    ->get()
    ->map(function($receive) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
      $receive->fabrication=$gmtspart[$receive->gmtspart_id].", ".$desDropdown[$receive->autoyarn_id];
      $receive->fabriclooks=$fabriclooks[$receive->fabric_look_id];
      $receive->fabricshape=$fabricshape[$receive->fabric_shape_id];
      $receive->dyetype=$dyetype[$receive->dyeing_type_id];
      $receive->gsm_weight=$receive->gsm_weight;
      $receive->colorrange_id=$colorrange[$receive->colorrange_id];
      $receive->ref_type='Receives';
      $receive->ref_date=date('d-M-Y',strtotime($receive->ref_date));
      $receive->rcv_qty=number_format($receive->ref_qty,0);
      $receive->dlv_qty=number_format(0,0);
      $receive->rtn_qty=number_format(0,0);
      return $receive;
    });



    $results = $this->sodyeingdlv
    ->selectRaw('
    companies.code as company_name,
    buyers.name as buyer_name,
    so_dyeing_dlvs.id ,
    so_dyeing_dlvs.issue_no as ref_no,
    so_dyeing_dlvs.issue_date as ref_date,
    so_dyeing_items.autoyarn_id,
    so_dyeing_items.fabric_look_id,
    so_dyeing_items.fabric_shape_id,
    so_dyeing_items.gmtspart_id,
    so_dyeing_items.gsm_weight,
    so_dyeing_items.fabric_color_id,
    so_dyeing_items.dia,
    colors.name as dyeing_color,
    so_dyeing_items.colorrange_id,
    so_dyeing_items.dyeing_type_id,
    uoms.code as uom_code,

    2 as trans_type,
    sum(so_dyeing_dlv_items.qty) as ref_qty,
    sum(so_dyeing_dlv_items.grey_used) as grey_used_qty,
    sum(so_dyeing_dlv_items.no_of_roll) as no_of_roll
    '
    )

    ->join('buyers', function($join)  {
      $join->on('so_dyeing_dlvs.buyer_id', '=', 'buyers.id');
    })
    ->join('companies', function($join)  {
        $join->on('so_dyeing_dlvs.company_id', '=', 'companies.id');
    })
        
    ->join('so_dyeing_dlv_items',function($join){
      $join->on('so_dyeing_dlv_items.so_dyeing_dlv_id','=','so_dyeing_dlvs.id');
    })
    ->join('so_dyeing_refs',function($join){
      $join->on('so_dyeing_refs.id','=','so_dyeing_dlv_items.so_dyeing_ref_id');
    })
    ->join('so_dyeing_items',function($join){
        $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
    })
    ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_dyeing_items.uom_id');
    })
    ->leftJoin('colors',function($join){
      $join->on('colors.id','=','so_dyeing_items.fabric_color_id');
    })
    
    
    ->when(request('buyer_id'), function ($q)  {
			return $q->where('so_dyeing_dlvs.buyer_id', '=', request('buyer_id',0));
		})
    ->when(request('date_from'), function ($q) {
      return $q->where('so_dyeing_dlvs.issue_date', '>=',request('date_from', 0));
    })
    ->when(request('date_to'), function ($q) {
      return $q->where('so_dyeing_dlvs.issue_date', '<=',request('date_to', 0));
    })
		->when(request('company_id'), function ($q) {
			return $q->where('so_dyeing_dlvs.company_id', '=',  request('company_id',0));
    })
    ->orderBy('so_dyeing_dlvs.id','desc')
    ->groupBy([
    'companies.code',
    'buyers.name',
    'so_dyeing_dlvs.id' ,
    'so_dyeing_dlvs.issue_no',
    'so_dyeing_dlvs.issue_date',
    'so_dyeing_items.autoyarn_id',
    'so_dyeing_items.fabric_look_id',
    'so_dyeing_items.fabric_shape_id',
    'so_dyeing_items.gmtspart_id',
    'so_dyeing_items.gsm_weight',
    'so_dyeing_items.fabric_color_id',
    'so_dyeing_items.dia',
    'colors.name',
    'so_dyeing_items.colorrange_id',
    'so_dyeing_items.dyeing_type_id',
    'uoms.code'
    ])
    ->get()
    ->map(function($results) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
      $results->fabrication=$gmtspart[$results->gmtspart_id].", ".$desDropdown[$results->autoyarn_id];
      $results->fabriclooks=$fabriclooks[$results->fabric_look_id];
      $results->fabricshape=$fabricshape[$results->fabric_shape_id];
      $results->dyetype=$dyetype[$results->dyeing_type_id];
      $results->gsm_weight=$results->gsm_weight;
      $results->colorrange_id=$colorrange[$results->colorrange_id];
      $results->ref_type='Issue';
      $results->ref_date=date('d-M-Y',strtotime($results->ref_date));
      $results->rcv_qty=number_format(0,0);
      $results->dlv_qty=number_format($results->ref_qty,0);
      $results->rtn_qty=number_format(0,0);
      $results->grey_used_qty=number_format($results->grey_used_qty,0);
      $results->no_of_roll=number_format($results->no_of_roll,0);
      return $results;
    });


    $return = $this->sodyeingfabricrtn
    ->selectRaw('
    companies.code as company_name,
    buyers.name as buyer_name,
    so_dyeing_fabric_rtns.id,
    so_dyeing_fabric_rtns.id as ref_no,
    so_dyeing_fabric_rtns.return_date as ref_date,
    so_dyeing_items.autoyarn_id,
    so_dyeing_items.fabric_look_id,
    so_dyeing_items.fabric_shape_id,
    so_dyeing_items.gmtspart_id,
    so_dyeing_items.gsm_weight,
    so_dyeing_items.fabric_color_id,
    so_dyeing_items.dia,
    colors.name as dyeing_color,
    so_dyeing_items.colorrange_id,
    so_dyeing_items.dyeing_type_id,
    uoms.code as uom_code,

    3 as trans_type,
    sum(so_dyeing_fabric_rtn_items.qty) as ref_qty,
    0 as grey_used_qty,
    sum(so_dyeing_fabric_rtn_items.no_of_roll) as no_of_roll
    '
    )

    ->join('buyers', function($join)  {
      $join->on('so_dyeing_fabric_rtns.buyer_id', '=', 'buyers.id');
    })
    ->join('companies', function($join)  {
        $join->on('so_dyeing_fabric_rtns.company_id', '=', 'companies.id');
    })
        
    ->join('so_dyeing_fabric_rtn_items',function($join){
      $join->on('so_dyeing_fabric_rtn_items.so_dyeing_fabric_rtn_id','=','so_dyeing_fabric_rtns.id');
    })
    ->join('so_dyeing_refs',function($join){
      $join->on('so_dyeing_refs.id','=','so_dyeing_fabric_rtn_items.so_dyeing_ref_id');
    })
    ->join('so_dyeing_items',function($join){
        $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
    })
    ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_dyeing_items.uom_id');
    })
    ->leftJoin('colors',function($join){
      $join->on('colors.id','=','so_dyeing_items.fabric_color_id');
    })
    
    
    ->when(request('buyer_id'), function ($q)  {
      return $q->where('so_dyeing_fabric_rtns.buyer_id', '=', request('buyer_id',0));
    })
    ->when(request('date_from'), function ($q) {
      return $q->where('so_dyeing_fabric_rtns.return_date', '>=',request('date_from', 0));
    })
    ->when(request('date_to'), function ($q) {
      return $q->where('so_dyeing_fabric_rtns.return_date', '<=',request('date_to', 0));
    })
    ->when(request('company_id'), function ($q) {
      return $q->where('so_dyeing_fabric_rtns.company_id', '=',  request('company_id',0));
    })
    ->orderBy('so_dyeing_fabric_rtns.id','desc')
    ->groupBy([
    'companies.code',
    'buyers.name',
    'so_dyeing_fabric_rtns.id' ,
    'so_dyeing_fabric_rtns.return_date',
    'so_dyeing_items.autoyarn_id',
    'so_dyeing_items.fabric_look_id',
    'so_dyeing_items.fabric_shape_id',
    'so_dyeing_items.gmtspart_id',
    'so_dyeing_items.gsm_weight',
    'so_dyeing_items.fabric_color_id',
    'so_dyeing_items.dia',
    'colors.name',
    'so_dyeing_items.colorrange_id',
    'so_dyeing_items.dyeing_type_id',
    'uoms.code'
    ])
    ->get()
    ->map(function($return) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
      $return->fabrication=$gmtspart[$return->gmtspart_id].", ".$desDropdown[$return->autoyarn_id];
      $return->fabriclooks=$fabriclooks[$return->fabric_look_id];
      $return->fabricshape=$fabricshape[$return->fabric_shape_id];
      $return->dyetype=$dyetype[$return->dyeing_type_id];
      $return->gsm_weight=$return->gsm_weight;
      $return->colorrange_id=$colorrange[$return->colorrange_id];
      $return->ref_type='Return';
      $return->ref_date=date('d-M-Y',strtotime($return->ref_date));
      $return->rcv_qty=number_format(0,0);
      $return->dlv_qty=number_format(0,0);
      $return->rtn_qty=number_format($return->ref_qty,0);
      $return->grey_used_qty=number_format(0,0);
      $return->no_of_roll=number_format($return->no_of_roll,0);
      return $return;
    });


    $concatenated = $receive->concat($results)->concat($return)->all();
    echo json_encode($concatenated);
  }



  public function getSoDlvItem(){
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
	  
	  $rows=$this->sodyeingdlv
	  ->join('so_dyeing_dlv_items',function($join){
	    $join->on('so_dyeing_dlv_items.so_dyeing_dlv_id','=','so_dyeing_dlvs.id');
	  })
	  ->join('so_dyeing_refs',function($join){
	    $join->on('so_dyeing_refs.id','=','so_dyeing_dlv_items.so_dyeing_ref_id');
	  })
	  ->leftJoin('so_dyeing_items',function($join){
	    $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
	  })
	  ->leftJoin('uoms',function($join){
	    $join->on('uoms.id','=','so_dyeing_items.uom_id');
	  })
	  ->leftJoin('colors',function($join){
	    $join->on('colors.id','=','so_dyeing_items.fabric_color_id');
	  })
	  ->where([['so_dyeing_dlv_id','=',request('so_dyeing_dlv_id')]])
	  ->selectRaw('
	      so_dyeing_dlvs.id as so_dyeing_dlv_id,
	      so_dyeing_dlv_items.id,
	      so_dyeing_dlv_items.qty,
	      so_dyeing_dlv_items.rate,
	      so_dyeing_dlv_items.amount,
	      so_dyeing_dlv_items.batch_no,
	      so_dyeing_dlv_items.process_name,
	      so_dyeing_dlv_items.fin_dia,
	      so_dyeing_dlv_items.fin_gsm,
	      so_dyeing_dlv_items.grey_used,
	      so_dyeing_dlv_items.no_of_roll,
	      so_dyeing_dlv_items.remarks,
	      so_dyeing_refs.id as so_dyeing_ref_id,
	      so_dyeing_refs.so_dyeing_id,
	      so_dyeing_items.autoyarn_id,
	      so_dyeing_items.fabric_look_id,
	      so_dyeing_items.fabric_shape_id,
	      so_dyeing_items.gmtspart_id,
	      so_dyeing_items.gsm_weight,
	      so_dyeing_items.fabric_color_id,
	      so_dyeing_items.dyeing_type_id,
	      colors.name as dyeing_color,
	      so_dyeing_items.colorrange_id,
	      uoms.code as uom_code
	      '
	    )
	  ->orderBy('so_dyeing_dlv_items.id','desc')
	  ->get()
	  ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
	    $rows->fabrication=$gmtspart[$rows->gmtspart_id].", ".$desDropdown[$rows->autoyarn_id];
	    $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
	    $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
	    $rows->dyetype=$dyetype[$rows->dyeing_type_id];
	    $rows->gsm_weight=$rows->gsm_weight;
	    $rows->colorrange_id=$colorrange[$rows->colorrange_id];
	    return $rows;
	  });
	  
	  echo json_encode($rows);
	  }
   
}