<?php
namespace App\Http\Controllers\Report\Subcontract\Dyeing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;

use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Util\UomRepository;


class SubConDyeingBomController extends Controller
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

  public function __construct(
    SoKnitYarnRcvRepository $soknityarnrcv,
    ItemAccountRepository $itemaccount,
    AutoyarnRepository $autoyarn,
    BuyerBranchRepository $buyerbranch,
    CompanyRepository $company,
    BuyerRepository $buyer,
    GmtspartRepository $gmtspart,
    ColorrangeRepository $colorrange,
    ColorRepository $color,
    SoDyeingRepository $sodyeing,
    UomRepository $uom
  )
  {
    $this->soknityarnrcv=$soknityarnrcv;
    $this->itemaccount=$itemaccount;
    $this->autoyarn=$autoyarn;
    $this->buyerbranch=$buyerbranch;
    $this->company=$company;
    $this->buyer=$buyer;
    $this->gmtspart = $gmtspart;
    $this->colorrange = $colorrange;
    $this->color = $color;
    $this->sodyeing = $sodyeing;
    $this->uom = $uom;
    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {
    $from=date('Y-m')."-01";
    $to=date('Y-m-t',strtotime($from));
    $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
    $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
    return Template::loadView('Report.Subcontract.Dyeing.SubConDyeingBom',['from'=>$from,'to'=>$to,'buyer'=>$buyer,'company'=>$company]);
  }
  public function reportData() {
        $company_id=request('company_id',0);
        $buyer_id=request('buyer_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $exch_rate=request('exch_rate',0);
        $company='';
        if($company_id){
            $company=" and so_dyeings.company_id = $company_id ";
        }
        $buyer='';
        if($buyer_id){
            $buyer=" and so_dyeings.buyer_id = $buyer_id ";
        }
        $datecon='';
        if($date_from && $date_to){
            $datecon=" and (orderamount.delivery_date >='".$date_from."' or orderamount.delivery_datepo >= '".$date_from."')   and (orderamount.delivery_date <= '".$date_to."' or orderamount.delivery_datepo <= '".$date_to."')";
        }
        $results = collect(
        \DB::select("
            select
            so_dyeings.id,
            so_dyeings.sales_order_no,
            so_dyeings.currency_id,
            companies.code as company_name,
            buyers.name as buyer_name,
            teamleaders.name as teamleader_name,
            sub_inb_marketings.id as marketing_ref,
            currencies.code as currency_code,
            orderamount.qty,
            orderamount.amount,
            orderamount.qtypo,
            orderamount.amountpo,
            orderamount.delivery_date,
            orderamount.delivery_datepo,
            fabric_dlv.fin_qty,
            fabric_dlv.fin_amount,
            fabric_dlv.grey_used_qty,
            fabric_dlv.grey_used_amount,
            dyescost.amount as dye_cost,
            chemcost.amount as chem_cost,
            overheads.amount as overhead_cost,
            subconrcvs.qty as sub_con_rcv_qty,
            inhrcvs.qty as inh_rcv_qty

            from 
            so_dyeings
            left join 
            sub_inb_marketings on sub_inb_marketings.id=so_dyeings.sub_inb_marketing_id
            left join 
            companies on companies.id=so_dyeings.company_id
            left join 
            buyers on buyers.id=so_dyeings.buyer_id
            left join 
            currencies on currencies.id=so_dyeings.currency_id
            left join 
            teams on teams.id=buyers.team_id

            left join (
            select
            users.name,
            teammembers.team_id
            from 
            teammembers
            left join users on users.id=teammembers.user_id
            where teammembers.type_id=2
            group by 
            users.name,
            teammembers.team_id) teamleaders on teamleaders.team_id=teams.id

            left join (
            select 
            so_dyeing_refs.so_dyeing_id,
            sum(so_dyeing_items.qty) as  qty,
            sum(so_dyeing_items.amount) as  amount,
            sum(po_dyeing_service_item_qties.qty)as qtypo,
            sum(po_dyeing_service_item_qties.amount)as amountpo,
            max(so_dyeing_items.delivery_date) as delivery_date,
            max(po_dyeing_services.delv_start_date) as delivery_datepo
            from
            so_dyeing_refs
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
            left join po_dyeing_services on po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id 
            where 
            so_dyeing_refs.deleted_at is null and
            so_dyeing_items.deleted_at is null and 
            po_dyeing_service_item_qties.deleted_at is null
            group by so_dyeing_refs.so_dyeing_id
            ) orderamount on orderamount.so_dyeing_id=so_dyeings.id


            left join (
                select
                m.so_dyeing_id, 
                sum(m.fin_qty) as fin_qty,
                sum(m.grey_used_qty) as grey_used_qty,
                sum(m.fin_amount) as fin_amount,
                sum(m.grey_used_amount) as grey_used_amount
                from 
                (
                select
                so_dyeing_refs.so_dyeing_id,
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
                join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_dlv_items.so_dyeing_ref_id
                join(
                select 
                so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
                avg(so_dyeing_fabric_rcv_items.rate) as rate
                from so_dyeing_fabric_rcv_items
                where so_dyeing_fabric_rcv_items.qty>0
                and so_dyeing_fabric_rcv_items.rate >0 
                group by 
                so_dyeing_fabric_rcv_items.so_dyeing_ref_id
                ) greyusedrate on greyusedrate.so_dyeing_ref_id=so_dyeing_dlv_items.so_dyeing_ref_id
                where 
                so_dyeing_dlv_items.deleted_at is null
                and so_dyeing_dlvs.deleted_at is null
                group by 
                so_dyeing_refs.so_dyeing_id,
                so_dyeing_dlv_items.so_dyeing_ref_id,
                greyusedrate.rate
                ) m group by m.so_dyeing_id
            ) fabric_dlv on fabric_dlv.so_dyeing_id=so_dyeings.id

            left join (
                select 
                so_dyeing_boms.so_dyeing_id,
                sum(so_dyeing_bom_fabric_items.amount) as amount
                from
                so_dyeing_boms
                join so_dyeing_bom_fabrics on so_dyeing_bom_fabrics.so_dyeing_bom_id=so_dyeing_boms.id
                join so_dyeing_bom_fabric_items on so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id=so_dyeing_bom_fabrics.id
                join item_accounts on item_accounts.id=so_dyeing_bom_fabric_items.item_account_id
                join itemclasses on itemclasses.id=item_accounts.itemclass_id
                join itemcategories on itemcategories.id=itemclasses.itemcategory_id
                where itemcategories.identity=7
                and so_dyeing_bom_fabrics.deleted_at is null
                and so_dyeing_bom_fabric_items.deleted_at is null
                group by 
                so_dyeing_boms.so_dyeing_id
            ) dyescost on dyescost.so_dyeing_id=so_dyeings.id

            left join (
                select 
                so_dyeing_boms.so_dyeing_id,
                sum(so_dyeing_bom_fabric_items.amount) as amount
                from
                so_dyeing_boms
                join so_dyeing_bom_fabrics on so_dyeing_bom_fabrics.so_dyeing_bom_id=so_dyeing_boms.id
                join so_dyeing_bom_fabric_items on so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id=so_dyeing_bom_fabrics.id
                join item_accounts on item_accounts.id=so_dyeing_bom_fabric_items.item_account_id
                join itemclasses on itemclasses.id=item_accounts.itemclass_id
                join itemcategories on itemcategories.id=itemclasses.itemcategory_id
                where itemcategories.identity=8
                and so_dyeing_bom_fabrics.deleted_at is null
                and so_dyeing_bom_fabric_items.deleted_at is null
                group by 
                so_dyeing_boms.so_dyeing_id
            ) chemcost on chemcost.so_dyeing_id=so_dyeings.id

            left join (
                select 
                so_dyeing_boms.so_dyeing_id,
                sum(so_dyeing_bom_overheads.amount) as amount
                from
                so_dyeing_boms
                join so_dyeing_bom_overheads on so_dyeing_bom_overheads.so_dyeing_bom_id=so_dyeing_boms.id
                where
                so_dyeing_bom_overheads.deleted_at is null
                group by 
                so_dyeing_boms.so_dyeing_id
            ) overheads on overheads.so_dyeing_id=so_dyeings.id

            left join(
                select 
                so_dyeing_fabric_rcvs.so_dyeing_id,
                sum(so_dyeing_fabric_rcv_items.qty) as qty
                from
                so_dyeing_fabric_rcvs
                join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id=so_dyeing_fabric_rcvs.id
                join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id=so_dyeing_fabric_rcv_items.id
                join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
                join so_dyeing_items on so_dyeing_refs.id=so_dyeing_items.so_dyeing_ref_id
                where
                so_dyeing_fabric_rcvs.deleted_at is null and
                so_dyeing_fabric_rcv_items.deleted_at is null
                group by 
                so_dyeing_fabric_rcvs.so_dyeing_id
            ) subconrcvs on subconrcvs.so_dyeing_id=so_dyeings.id

            left join(
                select 
                so_dyeing_fabric_rcvs.so_dyeing_id,
                sum(inv_grey_fab_isu_items.qty) as qty
                from
                so_dyeing_fabric_rcvs
                join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id=so_dyeing_fabric_rcvs.id
                join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id=so_dyeing_fabric_rcv_items.id
                join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
                join so_dyeing_po_items on so_dyeing_refs.id=so_dyeing_po_items.so_dyeing_ref_id
                join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id=so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
                where
                so_dyeing_fabric_rcvs.deleted_at is null and
                so_dyeing_fabric_rcv_items.deleted_at is null and
                inv_grey_fab_isu_items.deleted_at is null 
                group by 
                so_dyeing_fabric_rcvs.so_dyeing_id
            ) inhrcvs on inhrcvs.so_dyeing_id=so_dyeings.id

            where so_dyeings.deleted_at is null   $company $buyer  $datecon
            
            group by
            so_dyeings.id,
            so_dyeings.sales_order_no,
            so_dyeings.currency_id,
            companies.code,
            buyers.name ,
            teamleaders.name,
            sub_inb_marketings.id,
            currencies.code,
            orderamount.qty,
            orderamount.amount,
            orderamount.qtypo,
            orderamount.amountpo,
            orderamount.delivery_date,
            orderamount.delivery_datepo,
            fabric_dlv.fin_qty,
            fabric_dlv.fin_amount,
            fabric_dlv.grey_used_qty,
            fabric_dlv.grey_used_amount,
            dyescost.amount,
            chemcost.amount,
            overheads.amount,
            subconrcvs.qty,
            inhrcvs.qty
            order by overheads.amount
        ")
        )
        ->map(function($results) use($exch_rate){
            $exchrate=0;
            if($results->currency_id==2){
               $exchrate=1;
            }
            else{
                $exchrate=$exch_rate;
            }

            $results->order_qty= $results->qty?$results->qty:$results->qtypo;
            $results->order_val= $results->amount?$results->amount:$results->amountpo;
            $results->order_val=$results->order_val*$exchrate;
            $results->fin_amount=$results->fin_amount*$exchrate;
            $results->dye_cost=$results->dye_cost*$exchrate;
            $results->chem_cost=$results->chem_cost*$exchrate;
            $results->overhead_cost=$results->overhead_cost*$exchrate;

            $results->rcv_qty=$results->sub_con_rcv_qty?$results->sub_con_rcv_qty:$results->inh_rcv_qty;


            $results->delivery_date= $results->delivery_date?$results->delivery_date:$results->delivery_datepo;

            $results->order_rate=0;
            if($results->order_qty){
            $results->order_rate=$results->order_val/$results->order_qty;
            }

            $results->bal_qty= $results->order_qty-$results->fin_qty;
            $results->dye_chem_cost= $results->dye_cost+$results->chem_cost;
            $results->total_cost= $results->dye_chem_cost+$results->overhead_cost;
            $results->profit_loss= $results->order_val-$results->total_cost;

            $results->profit_loss_per=0;
            if($results->order_val){
            $results->profit_loss_per= ($results->profit_loss/$results->order_val)*100;
            }
            $results->delivery_date=date('d-M-Y',strtotime($results->delivery_date));


            $results->order_qty=number_format($results->order_qty,2);
            $results->order_val=number_format($results->order_val,2);
            $results->order_rate=number_format($results->order_rate,4);
            $results->fin_qty=number_format($results->fin_qty,2);
            $results->grey_used_qty=number_format($results->grey_used_qty,2);
            $results->fin_amount=number_format($results->fin_amount,2);
            //$results->grey_used_amount=number_format($results->grey_used_amount,2);
            $results->bal_qty=number_format($results->bal_qty,2);
            $results->dye_cost=number_format($results->dye_cost,2);
            $results->chem_cost=number_format($results->chem_cost,2);
            $results->dye_chem_cost=number_format($results->dye_chem_cost,2);
            $results->overhead_cost=number_format($results->overhead_cost,2);
            $results->total_cost=number_format($results->total_cost,2);
            $results->profit_loss=number_format($results->profit_loss,2);
            $results->profit_loss_per=number_format($results->profit_loss_per,2);
            $results->rcv_qty=number_format($results->rcv_qty,2);
            return $results;
        });
        
        echo json_encode($results);
    }


    public function reportSummary() {
        $company_id=request('company_id',0);
        $buyer_id=request('buyer_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $exch_rate=request('exch_rate',0);
        $company='';
        if($company_id){
            $company=" and so_dyeings.company_id = $company_id ";
        }
        $buyer='';
        if($buyer_id){
            $buyer=" and so_dyeings.buyer_id = $buyer_id ";
        }
        $datecon='';
        if($date_from && $date_to){
            $datecon=" and (orderamount.delivery_date >='".$date_from."' or orderamount.delivery_datepo >= '".$date_from."')   and (orderamount.delivery_date <= '".$date_to."' or orderamount.delivery_datepo <= '".$date_to."')";
        }
        $results = collect(
        \DB::select("
            select
            m.buyer_id,
            m.buyer_name,
            m.teamleader_name,
            sum(m.qty) as qty,
            sum(m.amount) as amount,
            sum(m.qtypo) as qtypo,
            sum(m.amountpo) as amountpo,
            sum(m.fin_qty) as fin_qty,
            sum(m.grey_used_qty) as grey_used_qty,
            sum(m.fin_amount) as fin_amount,
            sum(m.grey_used_amount) as grey_used_amount,
            sum(m.dye_cost) as dye_cost,
            sum(m.chem_cost) as chem_cost,
            sum(m.overhead_cost) as overhead_cost
            from
            (
            select
            so_dyeings.id,
            so_dyeings.currency_id,
            so_dyeings.buyer_id,
            so_dyeings.sales_order_no,
            companies.code as company_name,
            buyers.name as buyer_name,
            teamleaders.name as teamleader_name,
            sub_inb_marketings.id as marketing_ref,
            currencies.code as currency_code,
            orderamount.qty,
            CASE so_dyeings.currency_id
                WHEN 2 THEN orderamount.amount
                ELSE orderamount.amount * $exch_rate
            END as amount,
            --orderamount.amount,
            orderamount.qtypo,
            CASE so_dyeings.currency_id
                WHEN 2 THEN orderamount.amountpo
                ELSE orderamount.amountpo * $exch_rate
            END as amountpo,
            --orderamount.amountpo,
            orderamount.delivery_date,
            orderamount.delivery_datepo,
            fabric_dlv.fin_qty,
            CASE so_dyeings.currency_id
                WHEN 2 THEN fabric_dlv.fin_amount
                ELSE fabric_dlv.fin_amount * $exch_rate
            END as fin_amount,
            --fabric_dlv.fin_amount,
            fabric_dlv.grey_used_qty,
            fabric_dlv.grey_used_amount,
            CASE so_dyeings.currency_id
                WHEN 2 THEN dyescost.amount
                ELSE dyescost.amount * $exch_rate
            END as dye_cost,
            --dyescost.amount as dye_cost,
            CASE so_dyeings.currency_id
                WHEN 2 THEN chemcost.amount
                ELSE chemcost.amount * $exch_rate
            END as chem_cost,
            --chemcost.amount as chem_cost,
            CASE so_dyeings.currency_id
                WHEN 2 THEN overheads.amount
                ELSE overheads.amount * $exch_rate
            END as overhead_cost
            --overheads.amount as overhead_cost

            from 
            so_dyeings
            left join 
            sub_inb_marketings on sub_inb_marketings.id=so_dyeings.sub_inb_marketing_id
            left join 
            companies on companies.id=so_dyeings.company_id
            left join 
            buyers on buyers.id=so_dyeings.buyer_id
            left join 
            currencies on currencies.id=so_dyeings.currency_id
            left join 
            teams on teams.id=buyers.team_id

            left join (
            select
            users.name,
            teammembers.team_id
            from 
            teammembers
            left join users on users.id=teammembers.user_id
            where teammembers.type_id=2
            group by 
            users.name,
            teammembers.team_id) teamleaders on teamleaders.team_id=teams.id

            left join (
            select 
            so_dyeing_refs.so_dyeing_id,
            sum(so_dyeing_items.qty) as  qty,
            sum(so_dyeing_items.amount) as  amount,
            sum(po_dyeing_service_item_qties.qty)as qtypo,
            sum(po_dyeing_service_item_qties.amount)as amountpo,
            max(so_dyeing_items.delivery_date) as delivery_date,
            max(po_dyeing_services.delv_start_date) as delivery_datepo
            from
            so_dyeing_refs
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
            left join po_dyeing_services on po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id 
            where 
            so_dyeing_refs.deleted_at is null and
            so_dyeing_items.deleted_at is null and 
            po_dyeing_service_item_qties.deleted_at is null
            group by so_dyeing_refs.so_dyeing_id
            ) orderamount on orderamount.so_dyeing_id=so_dyeings.id


            left join (
                select
                m.so_dyeing_id, 
                sum(m.fin_qty) as fin_qty,
                sum(m.grey_used_qty) as grey_used_qty,
                sum(m.fin_amount) as fin_amount,
                sum(m.grey_used_amount) as grey_used_amount
                from 
                (
                select
                so_dyeing_refs.so_dyeing_id,
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
                join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_dlv_items.so_dyeing_ref_id
                join(
                select 
                so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
                avg(so_dyeing_fabric_rcv_items.rate) as rate
                from so_dyeing_fabric_rcv_items
                where so_dyeing_fabric_rcv_items.qty>0
                and so_dyeing_fabric_rcv_items.rate >0 
                group by 
                so_dyeing_fabric_rcv_items.so_dyeing_ref_id
                ) greyusedrate on greyusedrate.so_dyeing_ref_id=so_dyeing_dlv_items.so_dyeing_ref_id
                where 
                so_dyeing_dlv_items.deleted_at is null
                and so_dyeing_dlvs.deleted_at is null
                group by 
                so_dyeing_refs.so_dyeing_id,
                so_dyeing_dlv_items.so_dyeing_ref_id,
                greyusedrate.rate
                ) m group by m.so_dyeing_id
            ) fabric_dlv on fabric_dlv.so_dyeing_id=so_dyeings.id

            left join (
                select 
                so_dyeing_boms.so_dyeing_id,
                sum(so_dyeing_bom_fabric_items.amount) as amount
                from
                so_dyeing_boms
                join so_dyeing_bom_fabrics on so_dyeing_bom_fabrics.so_dyeing_bom_id=so_dyeing_boms.id
                join so_dyeing_bom_fabric_items on so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id=so_dyeing_bom_fabrics.id
                join item_accounts on item_accounts.id=so_dyeing_bom_fabric_items.item_account_id
                join itemclasses on itemclasses.id=item_accounts.itemclass_id
                join itemcategories on itemcategories.id=itemclasses.itemcategory_id
                where itemcategories.identity=7
                and so_dyeing_bom_fabrics.deleted_at is null
                and so_dyeing_bom_fabric_items.deleted_at is null
                group by 
                so_dyeing_boms.so_dyeing_id
            ) dyescost on dyescost.so_dyeing_id=so_dyeings.id

            left join (
                select 
                so_dyeing_boms.so_dyeing_id,
                sum(so_dyeing_bom_fabric_items.amount) as amount
                from
                so_dyeing_boms
                join so_dyeing_bom_fabrics on so_dyeing_bom_fabrics.so_dyeing_bom_id=so_dyeing_boms.id
                join so_dyeing_bom_fabric_items on so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id=so_dyeing_bom_fabrics.id
                join item_accounts on item_accounts.id=so_dyeing_bom_fabric_items.item_account_id
                join itemclasses on itemclasses.id=item_accounts.itemclass_id
                join itemcategories on itemcategories.id=itemclasses.itemcategory_id
                where itemcategories.identity=8
                and so_dyeing_bom_fabrics.deleted_at is null
                and so_dyeing_bom_fabric_items.deleted_at is null
                group by 
                so_dyeing_boms.so_dyeing_id
            ) chemcost on chemcost.so_dyeing_id=so_dyeings.id

            left join (
                select 
                so_dyeing_boms.so_dyeing_id,
                sum(so_dyeing_bom_overheads.amount) as amount
                from
                so_dyeing_boms
                join so_dyeing_bom_overheads on so_dyeing_bom_overheads.so_dyeing_bom_id=so_dyeing_boms.id
                where
                so_dyeing_bom_overheads.deleted_at is null
                group by 
                so_dyeing_boms.so_dyeing_id
            ) overheads on overheads.so_dyeing_id=so_dyeings.id

            where so_dyeings.deleted_at is null   $company $buyer $datecon
            group by
            so_dyeings.id,
            so_dyeings.currency_id,
            so_dyeings.buyer_id,
            so_dyeings.sales_order_no,
            companies.code,
            buyers.name ,
            teamleaders.name,
            sub_inb_marketings.id,
            currencies.code,
            orderamount.qty,
            orderamount.amount,
            orderamount.qtypo,
            orderamount.amountpo,
            orderamount.delivery_date,
            orderamount.delivery_datepo,
            fabric_dlv.fin_qty,
            fabric_dlv.fin_amount,
            fabric_dlv.grey_used_qty,
            fabric_dlv.grey_used_amount,
            dyescost.amount,
            chemcost.amount,
            overheads.amount
            order by overheads.amount
            ) m 
            group by 
            m.buyer_id,
            m.buyer_name,
            m.teamleader_name
        ")
        )
        ->map(function($results){

            $results->order_qty= $results->qty?$results->qty:$results->qtypo;
            $results->order_val= $results->amount?$results->amount:$results->amountpo;
            $results->order_rate=0;
            if($results->order_qty){
            $results->order_rate=$results->order_val/$results->order_qty;
            }
            $results->bal_qty= $results->order_qty-$results->fin_qty;
            $results->dye_chem_cost= $results->dye_cost+$results->chem_cost;
            $results->total_cost= $results->dye_chem_cost+$results->overhead_cost;
            $results->profit_loss= $results->order_val-$results->total_cost;
            $results->profit_loss_per=0;
            if($results->order_val){
            $results->profit_loss_per= ($results->profit_loss/$results->order_val)*100;
            }

            $results->order_qty=number_format($results->order_qty,2);
            $results->order_val=number_format($results->order_val,2);
            $results->order_rate=number_format($results->order_rate,4);
            $results->fin_qty=number_format($results->fin_qty,2);
            $results->grey_used_qty=number_format($results->grey_used_qty,2);
            $results->fin_amount=number_format($results->fin_amount,2);
            $results->bal_qty=number_format($results->bal_qty,2);
            $results->dye_cost=number_format($results->dye_cost,2);
            $results->chem_cost=number_format($results->chem_cost,2);
            $results->dye_chem_cost=number_format($results->dye_chem_cost,2);
            $results->overhead_cost=number_format($results->overhead_cost,2);
            $results->total_cost=number_format($results->total_cost,2);
            $results->profit_loss=number_format($results->profit_loss,2);
            $results->profit_loss_per=number_format($results->profit_loss_per,2);
            return $results;
        });
        
        echo json_encode($results);
    }

    public function reportChart() {
        $company_id=request('company_id',0);
        $buyer_id=request('buyer_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $exch_rate=request('exch_rate',0);
        $company='';
        if($company_id){
            $company=" and so_dyeings.company_id = $company_id ";
        }
        $buyer='';
        if($buyer_id){
            $buyer=" and so_dyeings.buyer_id = $buyer_id ";
        }
        $datecon='';
        if($date_from && $date_to){
            $datecon=" and (orderamount.delivery_date >='".$date_from."' or orderamount.delivery_datepo >= '".$date_from."')   and (orderamount.delivery_date <= '".$date_to."' or orderamount.delivery_datepo <= '".$date_to."')";
        }
        $results = collect(
        \DB::select("
            select
            m.buyer_id,
            m.buyer_name,
            m.teamleader_name,
            sum(m.qty) as qty,
            sum(m.amount) as amount,
            sum(m.qtypo) as qtypo,
            sum(m.amountpo) as amountpo,
            sum(m.fin_qty) as fin_qty,
            sum(m.fin_amount) as fin_amount,
            sum(m.grey_used_qty) as grey_used_qty,
            sum(m.grey_used_amount) as grey_used_amount,
            sum(m.dye_cost) as dye_cost,
            sum(m.chem_cost) as chem_cost,
            sum(m.overhead_cost) as overhead_cost
            from
            (
            select
            so_dyeings.id,
            so_dyeings.currency_id,
            so_dyeings.buyer_id,
            so_dyeings.sales_order_no,
            companies.code as company_name,
            buyers.name as buyer_name,
            teamleaders.name as teamleader_name,
            sub_inb_marketings.id as marketing_ref,
            currencies.code as currency_code,
            orderamount.qty,
            CASE so_dyeings.currency_id
                WHEN 2 THEN orderamount.amount
                ELSE orderamount.amount * $exch_rate
            END as amount,
            --orderamount.amount,
            orderamount.qtypo,
            CASE so_dyeings.currency_id
                WHEN 2 THEN orderamount.amountpo
                ELSE orderamount.amountpo * $exch_rate
            END as amountpo,
            --orderamount.amountpo,
            orderamount.delivery_date,
            orderamount.delivery_datepo,
            fabric_dlv.fin_qty,
            

            CASE so_dyeings.currency_id
                WHEN 2 THEN fabric_dlv.fin_amount
                ELSE fabric_dlv.fin_amount * $exch_rate
            END as fin_amount,
            --fabric_dlv.fin_amount,
            fabric_dlv.grey_used_qty,
            fabric_dlv.grey_used_amount,
            CASE so_dyeings.currency_id
                WHEN 2 THEN dyescost.amount
                ELSE dyescost.amount * $exch_rate
            END as dye_cost,
            --dyescost.amount as dye_cost,
            CASE so_dyeings.currency_id
                WHEN 2 THEN chemcost.amount
                ELSE chemcost.amount * $exch_rate
            END as chem_cost,
            --chemcost.amount as chem_cost,
            CASE so_dyeings.currency_id
                WHEN 2 THEN overheads.amount
                ELSE overheads.amount * $exch_rate
            END as overhead_cost
            --overheads.amount as overhead_cost

            from 
            so_dyeings
            left join 
            sub_inb_marketings on sub_inb_marketings.id=so_dyeings.sub_inb_marketing_id
            left join 
            companies on companies.id=so_dyeings.company_id
            left join 
            buyers on buyers.id=so_dyeings.buyer_id
            left join 
            currencies on currencies.id=so_dyeings.currency_id
            left join 
            teams on teams.id=buyers.team_id
            


            left join (
            select
            users.name,
            teammembers.team_id
            from 
            teammembers
            left join users on users.id=teammembers.user_id
            where teammembers.type_id=2
            group by 
            users.name,
            teammembers.team_id) teamleaders on teamleaders.team_id=teams.id

            left join (
            select 
            so_dyeing_refs.so_dyeing_id,
            sum(so_dyeing_items.qty) as  qty,
            sum(so_dyeing_items.amount) as  amount,
            sum(po_dyeing_service_item_qties.qty)as qtypo,
            sum(po_dyeing_service_item_qties.amount)as amountpo,
            max(so_dyeing_items.delivery_date) as delivery_date,
            max(po_dyeing_services.delv_start_date) as delivery_datepo
            from
            so_dyeing_refs
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
            left join po_dyeing_services on po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id 
            where 
            so_dyeing_refs.deleted_at is null and
            so_dyeing_items.deleted_at is null and 
            po_dyeing_service_item_qties.deleted_at is null
            group by so_dyeing_refs.so_dyeing_id
            ) orderamount on orderamount.so_dyeing_id=so_dyeings.id


            left join (
                select
                m.so_dyeing_id, 
                sum(m.fin_qty) as fin_qty,
                sum(m.fin_amount) as fin_amount,
                sum(m.grey_used_qty) as grey_used_qty,
                sum(m.grey_used_amount) as grey_used_amount
                from 
                (
                select
                so_dyeing_refs.so_dyeing_id,
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
                join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_dlv_items.so_dyeing_ref_id
                join(
                select 
                so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
                avg(so_dyeing_fabric_rcv_items.rate) as rate
                from so_dyeing_fabric_rcv_items
                where so_dyeing_fabric_rcv_items.qty>0
                and so_dyeing_fabric_rcv_items.rate >0 
                group by 
                so_dyeing_fabric_rcv_items.so_dyeing_ref_id
                ) greyusedrate on greyusedrate.so_dyeing_ref_id=so_dyeing_dlv_items.so_dyeing_ref_id
                where 
                so_dyeing_dlv_items.deleted_at is null
                and so_dyeing_dlvs.deleted_at is null
                group by 
                so_dyeing_refs.so_dyeing_id,
                so_dyeing_dlv_items.so_dyeing_ref_id,
                greyusedrate.rate
                ) m group by m.so_dyeing_id
            ) fabric_dlv on fabric_dlv.so_dyeing_id=so_dyeings.id

            left join (
                select 
                so_dyeing_boms.so_dyeing_id,
                sum(so_dyeing_bom_fabric_items.amount) as amount
                from
                so_dyeing_boms
                join so_dyeing_bom_fabrics on so_dyeing_bom_fabrics.so_dyeing_bom_id=so_dyeing_boms.id
                join so_dyeing_bom_fabric_items on so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id=so_dyeing_bom_fabrics.id
                join item_accounts on item_accounts.id=so_dyeing_bom_fabric_items.item_account_id
                join itemclasses on itemclasses.id=item_accounts.itemclass_id
                join itemcategories on itemcategories.id=itemclasses.itemcategory_id
                where itemcategories.identity=7
                and so_dyeing_bom_fabrics.deleted_at is null
                and so_dyeing_bom_fabric_items.deleted_at is null
                group by 
                so_dyeing_boms.so_dyeing_id
            ) dyescost on dyescost.so_dyeing_id=so_dyeings.id

            left join (
                select 
                so_dyeing_boms.so_dyeing_id,
                sum(so_dyeing_bom_fabric_items.amount) as amount
                from
                so_dyeing_boms
                join so_dyeing_bom_fabrics on so_dyeing_bom_fabrics.so_dyeing_bom_id=so_dyeing_boms.id
                join so_dyeing_bom_fabric_items on so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id=so_dyeing_bom_fabrics.id
                join item_accounts on item_accounts.id=so_dyeing_bom_fabric_items.item_account_id
                join itemclasses on itemclasses.id=item_accounts.itemclass_id
                join itemcategories on itemcategories.id=itemclasses.itemcategory_id
                where itemcategories.identity=8
                and so_dyeing_bom_fabrics.deleted_at is null
                and so_dyeing_bom_fabric_items.deleted_at is null
                group by 
                so_dyeing_boms.so_dyeing_id
            ) chemcost on chemcost.so_dyeing_id=so_dyeings.id

            left join (
                select 
                so_dyeing_boms.so_dyeing_id,
                sum(so_dyeing_bom_overheads.amount) as amount
                from
                so_dyeing_boms
                join so_dyeing_bom_overheads on so_dyeing_bom_overheads.so_dyeing_bom_id=so_dyeing_boms.id
                where
                so_dyeing_bom_overheads.deleted_at is null
                group by 
                so_dyeing_boms.so_dyeing_id
            ) overheads on overheads.so_dyeing_id=so_dyeings.id

            where so_dyeings.deleted_at is null   $company $buyer $datecon
            group by
            so_dyeings.id,
            so_dyeings.currency_id,
            so_dyeings.buyer_id,
            so_dyeings.sales_order_no,
            companies.code,
            buyers.name ,
            teamleaders.name,
            sub_inb_marketings.id,
            currencies.code,
            orderamount.qty,
            orderamount.amount,
            orderamount.qtypo,
            orderamount.amountpo,
            orderamount.delivery_date,
            orderamount.delivery_datepo,
            fabric_dlv.fin_qty,
            fabric_dlv.fin_amount,
            fabric_dlv.grey_used_qty,
            fabric_dlv.grey_used_amount,
            dyescost.amount,
            chemcost.amount,
            overheads.amount
            order by overheads.amount
            ) m 
            group by 
            m.buyer_id,
            m.buyer_name,
            m.teamleader_name
        ")
        )
        ->map(function($results){

            $results->order_qty= $results->qty?$results->qty:$results->qtypo;
            $results->order_val= $results->amount?$results->amount:$results->amountpo;
            $results->order_rate=0;
            if($results->order_qty){
            $results->order_rate=$results->order_val/$results->order_qty;
            }
            $results->bal_qty= $results->order_qty-$results->fin_qty;
            $results->dye_chem_cost= $results->dye_cost+$results->chem_cost;
            $results->total_cost= $results->dye_chem_cost+$results->overhead_cost;
            $results->profit_loss= $results->order_val-$results->total_cost;
            $results->profit_loss_per=0;
            if($results->order_val){
            $results->profit_loss_per= ($results->profit_loss/$results->order_val)*100;
            }
            return $results;
        });
        $data=['order_val'=>0,'dye_cost'=>0,'chem_cost'=>0,'overhead_cost'=>0,'profit_loss'=>0];
        foreach($results as $result){
           $data['order_val'] += $result->order_val;
           $data['dye_cost'] += $result->dye_cost;
           $data['chem_cost'] += $result->chem_cost;
           $data['overhead_cost'] += $result->overhead_cost;
           $data['profit_loss'] += $result->profit_loss;
        }
        echo json_encode($data);
    }

    public function getBuyerInfo(){
        $buyer_id=request('buyer_id',0);

        $results = collect(\DB::select("
        select 
        buyers.name as buyer_name,
        buyerbranches.contact_person,
        buyerbranches.designation,
        buyerbranches.email,
        buyers.cell_no,
        buyerbranches.address
        
        from buyers
        left join( 
        select 
        count(buyer_branches.id) as tid,
        buyer_branches.buyer_id,
        buyer_branches.contact_person,
        buyer_branches.email,
        buyer_branches.designation,
        buyer_branches.address
        from buyer_branches
        group by 
        buyer_branches.buyer_id,
        buyer_branches.contact_person,
        buyer_branches.email,
        buyer_branches.designation,
        buyer_branches.address
        ) buyerbranches on buyerbranches.buyer_id=buyers.id
        where buyers.id = ? 
        ", [$buyer_id])
        );
        echo json_encode($results);
    }

    public function getOrderQty(){

        $company_id=request('company_id',0);
        $buyer_id=request('buyer_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $exch_rate=request('exch_rate',0);
        $company='';
        if($company_id){
            $company=" and so_dyeings.company_id = $company_id ";
        }
        $buyer='';
        if($buyer_id){
            $buyer=" and so_dyeings.buyer_id = $buyer_id ";
        }
        $datecon='';
        if($date_from && $date_to){
            $datecon=" and (orderamount.delivery_date >='".$date_from."' or orderamount.delivery_datepo >= '".$date_from."')   and (orderamount.delivery_date <= '".$date_to."' or orderamount.delivery_datepo <= '".$date_to."')";
        }

        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->sodyeing
        ->join('so_dyeing_refs',function($join){
        $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_pos',function($join){
        $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_po_items',function($join){
        $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('po_dyeing_service_item_qties',function($join){
        $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
        })
        // ->leftJoin('budget_fabric_prod_cons',function($join){
        // $join->on('po_dyeing_service_item_qties.budget_fabric_prod_con_id','=','budget_fabric_prod_cons.id');
        // })
        ->leftJoin('po_dyeing_service_items',function($join){
        $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
        ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->leftJoin('po_dyeing_services',function($join){
        $join->on('po_dyeing_services.id','=','po_dyeing_service_items.po_dyeing_service_id')
        ->whereNull('po_dyeing_services.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        //$join->on('budget_fabric_prod_cons.budget_fabric_prod_id','=','budget_fabric_prods.id');
        })
        ->leftJoin('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->leftJoin('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->leftJoin('so_dyeing_items',function($join){
        $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('buyers as gmt_buyer',function($join){
        $join->on('gmt_buyer.id','=','so_dyeing_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->leftJoin('uoms as so_uoms',function($join){
        $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
        })
        ->leftJoin('colors as so_color',function($join){
        $join->on('so_color.id','=','so_dyeing_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
        $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
        })

        ->leftJoin(\DB::raw("(select 
            so_dyeing_refs.so_dyeing_id,
            sum(so_dyeing_items.qty) as  qty,
            sum(so_dyeing_items.amount) as  amount,
            sum(po_dyeing_service_item_qties.qty)as qtypo,
            sum(po_dyeing_service_item_qties.amount)as amountpo,
            max(so_dyeing_items.delivery_date) as delivery_date,
            max(po_dyeing_services.delv_start_date) as delivery_datepo
            from
            so_dyeing_refs
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
            left join po_dyeing_services on po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id 
            where 
            so_dyeing_refs.deleted_at is null and
            so_dyeing_items.deleted_at is null and 
            po_dyeing_service_item_qties.deleted_at is null
            group by so_dyeing_refs.so_dyeing_id) orderamount "), "orderamount.so_dyeing_id", "=", "so_dyeings.id")



        ->when(request('so_dyeing_id'), function ($q) {
        return $q->where('so_dyeings.id', '=', request('so_dyeing_id', 0));
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('so_dyeings.company_id', '=', request('company_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
        return $q->where('so_dyeings.buyer_id', '=', request('buyer_id', 0));
        })
        ->when($datecon, function ($q) use($date_from,$date_to){
        return $q->whereRaw('(orderamount.delivery_date >=? or orderamount.delivery_datepo >= ? )  and (orderamount.delivery_date <= ? or orderamount.delivery_datepo <= ?)', [$date_from,$date_from,$date_to,$date_to]);
        })
                    

        //->where([['so_dyeings.id','=',request('so_dyeing_id')]])
        ->selectRaw('
         
          so_dyeing_refs.id as so_dyeing_ref_id,
          so_dyeing_refs.so_dyeing_id,
          so_dyeings.currency_id,
          constructions.name as construction_name,
          style_fabrications.autoyarn_id,
          style_fabrications.fabric_look_id,
          style_fabrications.fabric_shape_id,
          style_fabrications.gmtspart_id,
          style_fabrications.dyeing_type_id,
          budget_fabrics.gsm_weight,
          po_dyeing_service_item_qties.fabric_color_id,
          po_dyeing_service_item_qties.colorrange_id,
          po_dyeing_service_item_qties.qty as fabric_wgt,
          po_dyeing_service_item_qties.pcs_qty,
          po_dyeing_service_item_qties.rate,
          po_dyeing_service_item_qties.amount,
          po_dyeing_services.delv_start_date as delivery_date,
          so_dyeing_items.autoyarn_id as c_autoyarn_id,
          so_dyeing_items.fabric_look_id as c_fabric_look_id,
          so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
          so_dyeing_items.gmtspart_id as c_gmtspart_id,
          so_dyeing_items.gsm_weight as c_gsm_weight,
          so_dyeing_items.fabric_color_id as c_fabric_color_id,
          so_dyeing_items.colorrange_id as c_colorrange_id,
          so_dyeing_items.qty as c_qty,
          so_dyeing_items.rate as c_rate,
          so_dyeing_items.amount as c_amount,
          so_dyeing_items.dyeing_type_id as c_dyeing_type_id,
          so_dyeing_items.delivery_date as c_delivery_date,
          styles.style_ref,
          sales_orders.sale_order_no,
          so_dyeing_items.gmt_style_ref,
          so_dyeing_items.gmt_sale_order_no,
          buyers.name as buyer_name,
          gmt_buyer.name as gmt_buyer_name,
          uoms.code as uom_name,
          so_uoms.code as so_uom_name
          '
        )
        ->orderBy('so_dyeing_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr,$exch_rate){
            $exchrate=0;
            if($rows->currency_id==2){
               $exchrate=1;
            }
            else{
                $exchrate=$exch_rate;
            }

            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
            $rows->construction_name=$rows->autoyarn_id?$fabricDescriptionArr[$rows->autoyarn_id]:$fabricDescriptionArr[$rows->c_autoyarn_id];
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
            $rows->uom_id=$rows->uom_id?$uom[$rows->uom_id]:'';
            $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
            $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:$color[$rows->c_fabric_color_id];
            $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];
            $rows->qty=$rows->fabric_wgt?$rows->fabric_wgt:$rows->c_qty;
            $rows->pcs_qty=$rows->pcs_qty;
            $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
            $rows->order_val=$rows->amount?$rows->amount:$rows->c_amount;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            $rows->uom_name=$rows->uom_name?$rows->uom_name:$rows->so_uom_name;
            $rows->delivery_date=$rows->delivery_date?$rows->delivery_date:$rows->c_delivery_date;
            $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:$dyetype[$rows->c_dyeing_type_id];

            $rows->fabrication=$rows->gmtspart.",".$rows->fabrication.",".$rows->fabriclooks.",".$rows->fabricshape.",".$rows->gsm_weight.",".$rows->fabric_color;
            $rows->qty=number_format($rows->qty,2,'.',',');
            $rows->rate=number_format($rows->rate*$exchrate,4,'.',',');
            $rows->order_val=number_format($rows->order_val*$exchrate,2,'.',','); 
            $rows->delivery_date=date('d-M-Y',strtotime($rows->delivery_date));
            return $rows;
        });
        echo json_encode($rows);

    }

    public function getDlvQty(){
        $company_id=request('company_id',0);
        $buyer_id=request('buyer_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $exch_rate=request('exch_rate',0);

        $company='';
        if($company_id){
            $company=" and so_dyeings.company_id = $company_id ";
        }
        $buyer='';
        if($buyer_id){
            $buyer=" and so_dyeings.buyer_id = $buyer_id ";
        }
        $datecon='';
        if($date_from && $date_to){
            $datecon=" and (orderamount.delivery_date >='".$date_from."' or orderamount.delivery_datepo >= '".$date_from."')   and (orderamount.delivery_date <= '".$date_to."' or orderamount.delivery_datepo <= '".$date_to."')";
        }

        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->sodyeing
        ->join('so_dyeing_refs',function($join){
        $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
        ->join('so_dyeing_dlv_items',function($join){
        $join->on('so_dyeing_dlv_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->join('so_dyeing_dlvs',function($join){
        $join->on('so_dyeing_dlvs.id','=','so_dyeing_dlv_items.so_dyeing_dlv_id');
        })
        ->leftJoin('so_dyeing_pos',function($join){
        $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_po_items',function($join){
        $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('po_dyeing_service_item_qties',function($join){
        $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
        })
        // ->leftJoin('budget_fabric_prod_cons',function($join){
        // $join->on('po_dyeing_service_item_qties.budget_fabric_prod_con_id','=','budget_fabric_prod_cons.id');
        // })
        ->leftJoin('po_dyeing_service_items',function($join){
        $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
        ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->leftJoin('po_dyeing_services',function($join){
        $join->on('po_dyeing_services.id','=','po_dyeing_service_items.po_dyeing_service_id')
        ->whereNull('po_dyeing_services.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        //$join->on('budget_fabric_prod_cons.budget_fabric_prod_id','=','budget_fabric_prods.id');
        })
        ->leftJoin('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->leftJoin('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->leftJoin('so_dyeing_items',function($join){
        $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('buyers as gmt_buyer',function($join){
        $join->on('gmt_buyer.id','=','so_dyeing_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->leftJoin('uoms as so_uoms',function($join){
        $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
        })
        ->leftJoin('colors as so_color',function($join){
        $join->on('so_color.id','=','so_dyeing_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
        $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
        })
        //->where([['so_dyeings.id','=',request('so_dyeing_id')]])
        ->leftJoin(\DB::raw("(select 
            so_dyeing_refs.so_dyeing_id,
            sum(so_dyeing_items.qty) as  qty,
            sum(so_dyeing_items.amount) as  amount,
            sum(po_dyeing_service_item_qties.qty)as qtypo,
            sum(po_dyeing_service_item_qties.amount)as amountpo,
            max(so_dyeing_items.delivery_date) as delivery_date,
            max(po_dyeing_services.delv_start_date) as delivery_datepo
            from
            so_dyeing_refs
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
            left join po_dyeing_services on po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id 
            where 
            so_dyeing_refs.deleted_at is null and
            so_dyeing_items.deleted_at is null and 
            po_dyeing_service_item_qties.deleted_at is null
            group by so_dyeing_refs.so_dyeing_id) orderamount "), "orderamount.so_dyeing_id", "=", "so_dyeings.id")



        ->when(request('so_dyeing_id'), function ($q) {
        return $q->where('so_dyeings.id', '=', request('so_dyeing_id', 0));
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('so_dyeings.company_id', '=', request('company_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
        return $q->where('so_dyeings.buyer_id', '=', request('buyer_id', 0));
        })
        ->when($datecon, function ($q) use($date_from,$date_to){
        return $q->whereRaw('(orderamount.delivery_date >=? or orderamount.delivery_datepo >= ? )  and (orderamount.delivery_date <= ? or orderamount.delivery_datepo <= ?)', [$date_from,$date_from,$date_to,$date_to]);
        })
        ->selectRaw('
         
          so_dyeing_refs.id as so_dyeing_ref_id,
          so_dyeing_refs.so_dyeing_id,
          so_dyeings.currency_id,
          constructions.name as construction_name,
          style_fabrications.autoyarn_id,
          style_fabrications.fabric_look_id,
          style_fabrications.fabric_shape_id,
          style_fabrications.gmtspart_id,
          style_fabrications.dyeing_type_id,
          budget_fabrics.gsm_weight,
          
          po_dyeing_service_item_qties.fabric_color_id,
          po_dyeing_service_item_qties.colorrange_id,
          po_dyeing_service_item_qties.qty as fabric_wgt,
          po_dyeing_service_item_qties.pcs_qty,
          po_dyeing_service_item_qties.rate,
          po_dyeing_service_item_qties.amount,
          po_dyeing_services.delv_start_date as delivery_date,

          so_dyeing_items.autoyarn_id as c_autoyarn_id,
          so_dyeing_items.fabric_look_id as c_fabric_look_id,
          so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
          so_dyeing_items.gmtspart_id as c_gmtspart_id,
          so_dyeing_items.gsm_weight as c_gsm_weight,
          so_dyeing_items.fabric_color_id as c_fabric_color_id,
          so_dyeing_items.colorrange_id as c_colorrange_id,
          so_dyeing_items.qty as c_qty,
          so_dyeing_items.rate as c_rate,
          so_dyeing_items.amount as c_amount,
          so_dyeing_items.dyeing_type_id as c_dyeing_type_id,
          so_dyeing_items.delivery_date as c_delivery_date,

          styles.style_ref,
          sales_orders.sale_order_no,
          so_dyeing_items.gmt_style_ref,
          so_dyeing_items.gmt_sale_order_no,
          buyers.name as buyer_name,
          gmt_buyer.name as gmt_buyer_name,
          uoms.code as uom_name,
          so_uoms.code as so_uom_name,
          so_dyeing_dlv_items.qty,
          so_dyeing_dlv_items.grey_used,
          so_dyeing_dlv_items.rate,
          so_dyeing_dlv_items.amount,
          so_dyeing_dlvs.issue_no,
          so_dyeing_dlvs.issue_date
          '
        )
        ->orderBy('so_dyeing_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr,$exch_rate){
            $exchrate=0;
            if($rows->currency_id==2){
               $exchrate=1;
            }
            else{
                $exchrate=$exch_rate;
            }

            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
            $rows->construction_name=$rows->autoyarn_id?$fabricDescriptionArr[$rows->autoyarn_id]:$fabricDescriptionArr[$rows->c_autoyarn_id];
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
            $rows->uom_id=$rows->uom_id?$uom[$rows->uom_id]:'';
            $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
            $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:$color[$rows->c_fabric_color_id];
            $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];

            /*$rows->qty=$rows->qty?$rows->qty:$rows->c_qty;
            $rows->pcs_qty=$rows->pcs_qty;
            $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
            $rows->order_val=$rows->amount?$rows->amount:$rows->c_amount;*/
            //$rows->delivery_date=$rows->delivery_date?$rows->delivery_date:$rows->c_delivery_date;


            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            $rows->uom_name=$rows->uom_name?$rows->uom_name:$rows->so_uom_name;
            $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:$dyetype[$rows->c_dyeing_type_id];

            $rows->fabrication=$rows->gmtspart.",".$rows->fabrication.",".$rows->fabriclooks.",".$rows->fabricshape.",".$rows->gsm_weight.",".$rows->fabric_color;

            $rows->qty=number_format($rows->qty,2,'.',',');
            $rows->rate=number_format($rows->rate*$exchrate,4,'.',',');
            $rows->amount=number_format($rows->amount*$exchrate,2,'.',','); 
            $rows->delivery_date=date('d-M-Y',strtotime($rows->issue_date));
            return $rows;
        });
        echo json_encode($rows);

    }
    public function getDyeQty(){
        $company_id=request('company_id',0);
        $buyer_id=request('buyer_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $exch_rate=request('exch_rate',0);

        $company='';
        if($company_id){
            $company=" and so_dyeings.company_id = $company_id ";
        }
        $buyer='';
        if($buyer_id){
            $buyer=" and so_dyeings.buyer_id = $buyer_id ";
        }
        $datecon='';
        if($date_from && $date_to){
            $datecon=" and (orderamount.delivery_date >='".$date_from."' or orderamount.delivery_datepo >= '".$date_from."')   and (orderamount.delivery_date <= '".$date_to."' or orderamount.delivery_datepo <= '".$date_to."')";
        }

        $dyes = $this->sodyeing
        ->join('so_dyeing_boms',function($join){
        $join->on('so_dyeing_boms.so_dyeing_id','=','so_dyeings.id');
        })
        ->join('so_dyeing_bom_fabrics',function($join){
        $join->on('so_dyeing_bom_fabrics.so_dyeing_bom_id','=','so_dyeing_boms.id');
        })
        ->join('so_dyeing_bom_fabric_items',function($join){
        $join->on('so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id','=','so_dyeing_bom_fabrics.id');
        })
        ->join('item_accounts',function($join){
        $join->on('so_dyeing_bom_fabric_items.item_account_id','=','item_accounts.id');
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
        ->leftJoin(\DB::raw("(select 
            so_dyeing_refs.so_dyeing_id,
            sum(so_dyeing_items.qty) as  qty,
            sum(so_dyeing_items.amount) as  amount,
            sum(po_dyeing_service_item_qties.qty)as qtypo,
            sum(po_dyeing_service_item_qties.amount)as amountpo,
            max(so_dyeing_items.delivery_date) as delivery_date,
            max(po_dyeing_services.delv_start_date) as delivery_datepo
            from
            so_dyeing_refs
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
            left join po_dyeing_services on po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id 
            where 
            so_dyeing_refs.deleted_at is null and
            so_dyeing_items.deleted_at is null and 
            po_dyeing_service_item_qties.deleted_at is null
            group by so_dyeing_refs.so_dyeing_id) orderamount "), "orderamount.so_dyeing_id", "=", "so_dyeings.id")



        ->when(request('so_dyeing_id'), function ($q) {
        return $q->where('so_dyeings.id', '=', request('so_dyeing_id', 0));
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('so_dyeings.company_id', '=', request('company_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
        return $q->where('so_dyeings.buyer_id', '=', request('buyer_id', 0));
        })
        ->when($datecon, function ($q) use($date_from,$date_to){
        return $q->whereRaw('(orderamount.delivery_date >=? or orderamount.delivery_datepo >= ? )  and (orderamount.delivery_date <= ? or orderamount.delivery_datepo <= ?)', [$date_from,$date_from,$date_to,$date_to]);
        })
        ->where([['itemcategories.identity','=',7]])
        //->where([['so_dyeings.id','=',request('so_dyeing_id')]])
        ->selectRaw('
        so_dyeings.currency_id,
        item_accounts.id as item_account_id,
        itemcategories.name as category_name,
        itemclasses.name as class_name,
        item_accounts.sub_class_name,
        item_accounts.item_description,
        item_accounts.specification,
        uoms.code as uom_name,
        sum(so_dyeing_bom_fabric_items.qty) as qty,
        avg(so_dyeing_bom_fabric_items.rate) as rate,
        sum(so_dyeing_bom_fabric_items.amount) as amount
          '
        )
        ->groupBy([
        'so_dyeings.currency_id',
        'item_accounts.id',
        'itemcategories.name',
        'itemclasses.name',
        'item_accounts.sub_class_name',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code'
        ])
        ->get()
        ->map(function($dyes)use($exch_rate){
        $exchrate=0;
        if($dyes->currency_id==2){
        $exchrate=1;
        }
        else{
        $exchrate=$exch_rate;
        }
        $dyes->qty=number_format($dyes->qty,2,'.',',');
        $dyes->rate=number_format($dyes->rate*$exchrate,4,'.',',');
        $dyes->amount=number_format($dyes->amount*$exchrate,2,'.',',');
        return $dyes;
        });
        echo json_encode($dyes);

    }

    public function getChemQty(){
        $company_id=request('company_id',0);
        $buyer_id=request('buyer_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $exch_rate=request('exch_rate',0);

        $company='';
        if($company_id){
            $company=" and so_dyeings.company_id = $company_id ";
        }
        $buyer='';
        if($buyer_id){
            $buyer=" and so_dyeings.buyer_id = $buyer_id ";
        }
        $datecon='';
        if($date_from && $date_to){
            $datecon=" and (orderamount.delivery_date >='".$date_from."' or orderamount.delivery_datepo >= '".$date_from."')   and (orderamount.delivery_date <= '".$date_to."' or orderamount.delivery_datepo <= '".$date_to."')";
        }

        $chems = $this->sodyeing
        ->join('so_dyeing_boms',function($join){
        $join->on('so_dyeing_boms.so_dyeing_id','=','so_dyeings.id');
        })
        ->join('so_dyeing_bom_fabrics',function($join){
        $join->on('so_dyeing_bom_fabrics.so_dyeing_bom_id','=','so_dyeing_boms.id');
        })
        ->join('so_dyeing_bom_fabric_items',function($join){
        $join->on('so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id','=','so_dyeing_bom_fabrics.id');
        })
        ->join('item_accounts',function($join){
        $join->on('so_dyeing_bom_fabric_items.item_account_id','=','item_accounts.id');
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
        
        ->leftJoin(\DB::raw("(select 
            so_dyeing_refs.so_dyeing_id,
            sum(so_dyeing_items.qty) as  qty,
            sum(so_dyeing_items.amount) as  amount,
            sum(po_dyeing_service_item_qties.qty)as qtypo,
            sum(po_dyeing_service_item_qties.amount)as amountpo,
            max(so_dyeing_items.delivery_date) as delivery_date,
            max(po_dyeing_services.delv_start_date) as delivery_datepo
            from
            so_dyeing_refs
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
            left join po_dyeing_services on po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id 
            where 
            so_dyeing_refs.deleted_at is null and
            so_dyeing_items.deleted_at is null and 
            po_dyeing_service_item_qties.deleted_at is null
            group by so_dyeing_refs.so_dyeing_id) orderamount "), "orderamount.so_dyeing_id", "=", "so_dyeings.id")



        ->when(request('so_dyeing_id'), function ($q) {
        return $q->where('so_dyeings.id', '=', request('so_dyeing_id', 0));
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('so_dyeings.company_id', '=', request('company_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
        return $q->where('so_dyeings.buyer_id', '=', request('buyer_id', 0));
        })
        ->when($datecon, function ($q) use($date_from,$date_to){
        return $q->whereRaw('(orderamount.delivery_date >=? or orderamount.delivery_datepo >= ? )  and (orderamount.delivery_date <= ? or orderamount.delivery_datepo <= ?)', [$date_from,$date_from,$date_to,$date_to]);
        })
        //->where([['so_dyeings.id','=',request('so_dyeing_id')]])
        ->where([['itemcategories.identity','=',8]])
        ->selectRaw('
        so_dyeings.currency_id,
        item_accounts.id as item_account_id,
        itemcategories.name as category_name,
        itemclasses.name as class_name,
        item_accounts.sub_class_name,
        item_accounts.item_description,
        item_accounts.specification,
        uoms.code as uom_name,
        sum(so_dyeing_bom_fabric_items.qty) as qty,
        avg(so_dyeing_bom_fabric_items.rate) as rate,
        sum(so_dyeing_bom_fabric_items.amount) as amount
          '
        )
        ->groupBy([
        'so_dyeings.currency_id',
        'item_accounts.id',
        'itemcategories.name',
        'itemclasses.name',
        'item_accounts.sub_class_name',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code',
        ])
        ->get()
        ->map(function($chems)use($exch_rate){
        $exchrate=0;
        if($chems->currency_id==2){
        $exchrate=1;
        }
        else{
        $exchrate=$exch_rate;
        }
        $chems->qty=number_format($chems->qty,2,'.',',');
        $chems->rate=number_format($chems->rate*$exchrate,4,'.',',');
        $chems->amount=number_format($chems->amount*$exchrate,2,'.',',');
        return $chems;
        });
        echo json_encode($chems);

    }

    public function getOhQty(){
        $company_id=request('company_id',0);
        $buyer_id=request('buyer_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $exch_rate=request('exch_rate',0);
        $company='';
        if($company_id){
            $company=" and so_dyeings.company_id = $company_id ";
        }
        $buyer='';
        if($buyer_id){
            $buyer=" and so_dyeings.buyer_id = $buyer_id ";
        }
        $datecon='';
        if($date_from && $date_to){
            $datecon=" and (orderamount.delivery_date >='".$date_from."' or orderamount.delivery_datepo >= '".$date_from."')   and (orderamount.delivery_date <= '".$date_to."' or orderamount.delivery_datepo <= '".$date_to."')";
        }
        $heads=$this->sodyeing
        ->join('so_dyeing_boms',function($join){
        $join->on('so_dyeing_boms.so_dyeing_id','=','so_dyeings.id');
        })
        ->join('so_dyeing_bom_overheads', function($join){
        $join->on('so_dyeing_boms.id', '=', 'so_dyeing_bom_overheads.so_dyeing_bom_id');
        })
        ->join('acc_chart_ctrl_heads', function($join){
        $join->on('acc_chart_ctrl_heads.id', '=', 'so_dyeing_bom_overheads.acc_chart_ctrl_head_id');
        })
        /*->leftJoin(\DB::raw("(select 
          so_dyeing_refs.so_dyeing_id,
          sum(so_dyeing_items.amount) as  amountso,
          sum(po_dyeing_service_item_qties.amount)as amountpo,
          max(so_dyeing_items.delivery_date) as delivery_date,
          max(po_dyeing_services.delv_start_date) as delivery_datepo
          from
          so_dyeing_refs
          left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
          left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
          left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id 
          where 
          so_dyeing_refs.deleted_at is null and
          so_dyeing_items.deleted_at is null and 
          po_dyeing_service_item_qties.deleted_at is null
          group by so_dyeing_refs.so_dyeing_id
          ) orderamount"), "orderamount.so_dyeing_id", "=", "so_dyeings.id")*/
        //->where([['so_dyeings.id','=',request('so_dyeing_id')]])
        ->leftJoin(\DB::raw("(select 
            so_dyeing_refs.so_dyeing_id,
            sum(so_dyeing_items.qty) as  qty,
            sum(so_dyeing_items.amount) as  amountso,
            sum(po_dyeing_service_item_qties.qty)as qtypo,
            sum(po_dyeing_service_item_qties.amount)as amountpo,
            max(so_dyeing_items.delivery_date) as delivery_date,
            max(po_dyeing_services.delv_start_date) as delivery_datepo
            from
            so_dyeing_refs
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
            left join po_dyeing_services on po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id 
            where 
            so_dyeing_refs.deleted_at is null and
            so_dyeing_items.deleted_at is null and 
            po_dyeing_service_item_qties.deleted_at is null
            group by so_dyeing_refs.so_dyeing_id) orderamount "), "orderamount.so_dyeing_id", "=", "so_dyeings.id")



        ->when(request('so_dyeing_id'), function ($q) {
        return $q->where('so_dyeings.id', '=', request('so_dyeing_id', 0));
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('so_dyeings.company_id', '=', request('company_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
        return $q->where('so_dyeings.buyer_id', '=', request('buyer_id', 0));
        })
        ->when($datecon, function ($q) use($date_from,$date_to){
        return $q->whereRaw('(orderamount.delivery_date >=? or orderamount.delivery_datepo >= ? )  and (orderamount.delivery_date <= ? or orderamount.delivery_datepo <= ?)', [$date_from,$date_from,$date_to,$date_to]);
        })
        ->get([
        'so_dyeing_bom_overheads.*',
        'so_dyeings.currency_id',
        'acc_chart_ctrl_heads.name as acc_head',
        'orderamount.amountso',
        'orderamount.amountpo'
        ])
        ->map(function($heads) use($exch_rate){
            $exchrate=0;
            if($heads->currency_id==2){
               $exchrate=1;
            }
            else{
                $exchrate=$exch_rate;
            }
            $heads->order_val= $heads->amountso?$heads->amountso:$heads->amountpo;
            if(!$heads->order_val){
             $heads->order_val=0; 
            }
            $heads->order_val=number_format($heads->order_val*$exchrate,2);
            $heads->amount=number_format($heads->amount*$exchrate,2);
            return $heads;

          });
        echo json_encode($heads);
    }
}
