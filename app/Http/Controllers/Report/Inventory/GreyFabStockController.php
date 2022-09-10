<?php
namespace App\Http\Controllers\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabItemRepository;

class GreyFabStockController extends Controller
{

  private $supplier;
  private $itemaccount;
  private $store;
  private $company;
  private $itemcategory;
  private $invgreyfabitem;
  private $gmtspart;

  public function __construct(
    SupplierRepository $supplier,
    ItemAccountRepository $itemaccount,
    StoreRepository $store,
    CompanyRepository $company,
    ItemcategoryRepository $itemcategory,
    InvGreyFabItemRepository $invgreyfabitem,
    GmtspartRepository $gmtspart
  )
  {
    $this->supplier = $supplier;
    $this->itemaccount=$itemaccount;
    $this->store=$store;
    $this->company=$company;
    $this->itemcategory=$itemcategory;
    $this->invgreyfabitem=$invgreyfabitem;
    $this->gmtspart=$gmtspart;
    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {
    $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');
    $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
    $company = array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
    $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-','');


    return Template::loadView('Report.Inventory.GreyFabStock',['supplier'=>$supplier,'store'=>$store,'company'=>$company,'itemcategory'=>$itemcategory]);
  }
	public function reportData() {
      $company_id=request('company_id',0);
      $store_id=request('store_id',0);
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $start_date=date('Y-m-d', strtotime($date_from));
      $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
      $companyCond='';
      if($company_id){
       $companyCond= ' and inv_grey_fab_transactions.company_id= '.$company_id;
      }
      else{
       $companyCond= '';
      }

      $storeCond='';
      if($store_id){
       $storeCond= ' and inv_grey_fab_transactions.store_id= '.$store_id;
      }
      else{
       $storeCond= '';
      }

      $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
      //$gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
      $fabricDescription = collect(\DB::select("
            select
            autoyarns.id,
            constructions.name as construction,
            compositions.name,
            autoyarnratios.ratio
            FROM autoyarns
            join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
            join compositions on compositions.id = autoyarnratios.composition_id
            join constructions on constructions.id = autoyarns.construction_id
      "
      ));

      $fabricDescriptionArr=array();
      $fabricCompositionArr=array();
      foreach($fabricDescription as $row){
      $fabricDescriptionArr[$row->id]=$row->construction;
      $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
      }

      $desDropdown=array();
      foreach($fabricDescriptionArr as $key=>$val){
      $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
      }
      



      $invgreyfabrcvitem=$this->invgreyfabitem
      ->join('autoyarns',function($join){
      $join->on('autoyarns.id','=','inv_grey_fab_items.autoyarn_id');
      })
      ->join('gmtsparts',function($join){
      $join->on('gmtsparts.id','=','inv_grey_fab_items.gmtspart_id');
      })
      ->leftJoin('colorranges',function($join){
      $join->on('colorranges.id','=','inv_grey_fab_items.colorrange_id');
      })
      ->join(\DB::raw("(
      select 
      inv_grey_fab_rcv_items.inv_grey_fab_item_id,
      sum(inv_grey_fab_transactions.store_qty) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_rcv_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
      join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
      where inv_rcvs.receive_date <='".$date_to."'
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_grey_fab_rcv_items.inv_grey_fab_item_id
      ) all_rcv"), "all_rcv.inv_grey_fab_item_id", "=", "inv_grey_fab_items.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_rcv_items.inv_grey_fab_item_id,
      sum(inv_grey_fab_transactions.store_qty) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_rcv_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
      join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
      where inv_rcvs.receive_date <'".$date_from."'
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_grey_fab_rcv_items.inv_grey_fab_item_id
      ) open_grey_fab_rcv"), "open_grey_fab_rcv.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")
      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_rcv_items.inv_grey_fab_item_id,
      sum(inv_grey_fab_transactions.store_qty) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_rcv_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
      join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id in (1,2,3)
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

      ) pur_rcv"), "pur_rcv.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_rcv_items.inv_grey_fab_item_id,
      sum(inv_grey_fab_transactions.store_qty) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_rcv_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
      join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 9
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

      ) trans_in_rcv"), "trans_in_rcv.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_rcv_items.inv_grey_fab_item_id,
      sum(inv_grey_fab_transactions.store_qty) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_rcv_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
      join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 4
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

      ) isu_rtn_rcv"), "isu_rtn_rcv.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_rcv_items.inv_grey_fab_item_id,
      sum(inv_grey_fab_transactions.store_qty) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_rcv_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
      join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

      ) greyfab_rcv"), "greyfab_rcv.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_isu_items.inv_grey_fab_item_id,
      sum(inv_grey_fab_transactions.store_qty) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_isu_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
      join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
      where inv_isus.issue_date<'".$date_from."'
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_grey_fab_isu_items.inv_grey_fab_item_id

      ) open_grey_fab_isu"), "open_grey_fab_isu.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_isu_items.inv_grey_fab_item_id,
      abs(sum(inv_grey_fab_transactions.store_qty)) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_isu_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
      join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id in (1,2)
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_grey_fab_isu_items.inv_grey_fab_item_id
      ) regular_isu"), "regular_isu.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_isu_items.inv_grey_fab_item_id,
      abs(sum(inv_grey_fab_transactions.store_qty)) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_isu_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
      join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 9
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_grey_fab_isu_items.inv_grey_fab_item_id
      ) trans_out_isu"), "trans_out_isu.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_isu_items.inv_grey_fab_item_id,
      abs(sum(inv_grey_fab_transactions.store_qty)) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_isu_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
      join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 11
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_grey_fab_isu_items.inv_grey_fab_item_id
      ) rcv_rtn_isu"), "rcv_rtn_isu.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")
      
      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_isu_items.inv_grey_fab_item_id,
      sum(inv_grey_fab_transactions.store_qty) as qty,
      sum(inv_grey_fab_transactions.store_amount) as amount
      from inv_grey_fab_isu_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
      join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_grey_fab_isu_items.inv_grey_fab_item_id
      ) greyfab_isu"), "greyfab_isu.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_rcv_items.inv_grey_fab_item_id,
      max(inv_rcvs.receive_date) as receive_date
      from inv_grey_fab_rcv_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
      join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
      where 
      inv_rcvs.receive_basis_id in (1,2,3)
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

      ) max_rcv_dt"), "max_rcv_dt.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_rcv_items.inv_grey_fab_item_id,
      inv_rcvs.receive_date as receive_date,
      sum(inv_grey_fab_transactions.store_qty) as qty
      from inv_grey_fab_rcv_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
      join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
      where  inv_rcvs.receive_basis_id in (1,2,3)
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by 
      inv_grey_fab_rcv_items.inv_grey_fab_item_id,
      inv_rcvs.receive_date

      ) max_rcv_qty"), [["max_rcv_qty.receive_date", "=", "max_rcv_dt.receive_date"],["max_rcv_qty.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id"]])


      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_isu_items.inv_grey_fab_item_id,
      max(inv_isus.issue_date) as issue_date
      from inv_grey_fab_isu_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
      join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
      where  inv_isus.isu_basis_id in (1,2)
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_grey_fab_isu_items.inv_grey_fab_item_id
      ) max_isu_dt"), "max_isu_dt.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_grey_fab_isu_items.inv_grey_fab_item_id,
      inv_isus.issue_date as issue_date,
      abs(sum(inv_grey_fab_transactions.store_qty)) as qty
      from inv_grey_fab_isu_items
      join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
      join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
      where 
      inv_isus.isu_basis_id in (1,2)
      and inv_grey_fab_transactions.deleted_at is null
      and inv_grey_fab_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_grey_fab_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_grey_fab_isu_items.inv_grey_fab_item_id,inv_isus.issue_date
      ) max_isu_qty"), [["max_isu_qty.issue_date", "=", "max_isu_dt.issue_date"],["max_isu_qty.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id"]])
      
      //->where([['itemcategories.identity','=',9]])
      /*->whereIn('itemcategories.identity',[7,8])
      ->when(request('item_category_id'), function ($q) {
            return $q->where('itemcategories.id', '=', request('item_category_id', 0));
      })*/
      
      ->orderBy('inv_grey_fab_items.id')
      ->get([
      'inv_grey_fab_items.id',
      'inv_grey_fab_items.autoyarn_id',
      'inv_grey_fab_items.gmtspart_id',
      'inv_grey_fab_items.fabric_look_id',
      'inv_grey_fab_items.fabric_shape_id',
      'inv_grey_fab_items.gsm_weight',
      'inv_grey_fab_items.dia',
      'inv_grey_fab_items.measurment',
      'inv_grey_fab_items.roll_length',
      'inv_grey_fab_items.stitch_length',
      'inv_grey_fab_items.shrink_per',
      'inv_grey_fab_items.colorrange_id',
      'gmtsparts.name as gmtspart_name',
      'colorranges.name as colorrange_name',

      'pur_rcv.qty as pur_qty',
      'trans_in_rcv.qty as trans_in_qty',
      'isu_rtn_rcv.qty as isu_rtn_qty',
      'greyfab_rcv.qty as receive_qty',
      'greyfab_rcv.amount as receive_amount',
      'open_grey_fab_rcv.qty as open_receive_qty',
      'open_grey_fab_rcv.amount as open_receive_amount',

      'regular_isu.qty as regular_issue_qty',
      'trans_out_isu.qty as trans_out_issue_qty',
      'rcv_rtn_isu.qty as rcv_rtn_issue_qty',
      'greyfab_isu.qty as issue_qty',
      'greyfab_isu.amount as issue_amount',
      'open_grey_fab_isu.qty as open_issue_qty',
      'open_grey_fab_isu.amount as open_issue_amount',
      'max_rcv_dt.receive_date as max_receive_date',

      'max_rcv_qty.qty as max_receive_qty',
      'max_isu_dt.issue_date as max_issue_date',
      'max_isu_qty.qty as max_issue_qty',
      ])
      ->map(function($invgreyfabrcvitem) use($shiftname,$desDropdown,$fabriclooks,$fabricshape) {
            //$invgreyfabrcvitem->item_desc=$invgreyfabrcvitem->item_description.", ".$invgreyfabrcvitem->specification;
            $invgreyfabrcvitem->shift_name=$shiftname[$invgreyfabrcvitem->shift_id];
            $invgreyfabrcvitem->fabrication=$invgreyfabrcvitem->autoyarn_id?$desDropdown[$invgreyfabrcvitem->autoyarn_id]:'';
            $invgreyfabrcvitem->fabric_look=$invgreyfabrcvitem->fabric_look_id?$fabriclooks[$invgreyfabrcvitem->fabric_look_id]:'';
            $invgreyfabrcvitem->fabric_shape=$invgreyfabrcvitem->fabric_shape_id?$fabricshape[$invgreyfabrcvitem->fabric_shape_id]:'';
            //$invgreyfabrcvitem->body_part=$invgreyfabrcvitem->gmtspart_id?$gmtspart[$invgreyfabrcvitem->gmtspart_id]:'';


            $invgreyfabrcvitem->issue_qty=$invgreyfabrcvitem->issue_qty*-1;
            $invgreyfabrcvitem->issue_amount=$invgreyfabrcvitem->issue_amount;
            $invgreyfabrcvitem->opening_qty=$invgreyfabrcvitem->open_receive_qty-($invgreyfabrcvitem->open_issue_qty*-1);
            $invgreyfabrcvitem->opening_amount=$invgreyfabrcvitem->open_receive_amount-($invgreyfabrcvitem->open_issue_amount);
            $invgreyfabrcvitem->stock_qty=($invgreyfabrcvitem->opening_qty+$invgreyfabrcvitem->receive_qty)-($invgreyfabrcvitem->issue_qty);
            $invgreyfabrcvitem->stock_value=($invgreyfabrcvitem->opening_amount+$invgreyfabrcvitem->receive_amount)-($invgreyfabrcvitem->issue_amount);
            $invgreyfabrcvitem->rate=0;
            if($invgreyfabrcvitem->stock_qty){
                  $invgreyfabrcvitem->rate=$invgreyfabrcvitem->stock_value/$invgreyfabrcvitem->stock_qty;
            }

            if($invgreyfabrcvitem->max_receive_date){
                  $invgreyfabrcvitem->last_receive=date('d-M-Y',strtotime($invgreyfabrcvitem->max_receive_date));
            }
            else{
      	      $invgreyfabrcvitem->last_receive='';
            }

            if($invgreyfabrcvitem->max_issue_date){
                  $invgreyfabrcvitem->last_issue=date('d-M-Y',strtotime($invgreyfabrcvitem->max_issue_date));
            }
            else{
      	      $invgreyfabrcvitem->last_issue='';
            }

            $now = time(); // or your date as well
            $max_issue_date = strtotime($invgreyfabrcvitem->max_issue_date);
            $datediff = $now - $max_issue_date;
            if($invgreyfabrcvitem->max_issue_date)
            {
                  $invgreyfabrcvitem->diff_days=round($datediff / (60 * 60 * 24));
            }
            else
            {
                  $invgreyfabrcvitem->diff_days='';
            }


            $invgreyfabrcvitem->opening_qty=number_format($invgreyfabrcvitem->opening_qty,2);
            $invgreyfabrcvitem->pur_qty=number_format($invgreyfabrcvitem->pur_qty,2);
            $invgreyfabrcvitem->trans_in_qty=number_format($invgreyfabrcvitem->trans_in_qty,2);
            $invgreyfabrcvitem->isu_rtn_qty=number_format($invgreyfabrcvitem->isu_rtn_qty,2);
            $invgreyfabrcvitem->receive_qty=number_format($invgreyfabrcvitem->receive_qty,2);
            $invgreyfabrcvitem->regular_issue_qty=number_format($invgreyfabrcvitem->regular_issue_qty,2);
            $invgreyfabrcvitem->trans_out_issue_qty=number_format($invgreyfabrcvitem->trans_out_issue_qty,2);
            $invgreyfabrcvitem->rcv_rtn_issue_qty=number_format($invgreyfabrcvitem->rcv_rtn_issue_qty,2);
            $invgreyfabrcvitem->issue_qty=number_format($invgreyfabrcvitem->issue_qty,2);
            $invgreyfabrcvitem->stock_qty=number_format($invgreyfabrcvitem->stock_qty,2);
            $invgreyfabrcvitem->rate=number_format($invgreyfabrcvitem->rate,2);
            $invgreyfabrcvitem->stock_value=number_format($invgreyfabrcvitem->stock_value,2);
            $invgreyfabrcvitem->max_receive_qty=number_format($invgreyfabrcvitem->max_receive_qty,2);
            $invgreyfabrcvitem->max_issue_qty=number_format($invgreyfabrcvitem->max_issue_qty,2);
            return $invgreyfabrcvitem;
      }); 
      echo json_encode($invgreyfabrcvitem);
    }
}
