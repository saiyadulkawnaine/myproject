<?php
namespace App\Http\Controllers\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;

class YarnStockYarnDyeingPartyController extends Controller
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

    return Template::loadView('Report.Inventory.YarnStockYarnDyeingParty',[]);
  }
  public function reportData() {
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        /*$yarnDescription=$this->itemaccount
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
        }*/

        

        $results = collect(
          \DB::select("
              select 
              suppliers.id,
              suppliers.name as supplier_name,
               
              yarn_isu_opening.qty as isu_qty_opening,
              yarn_isu_opening.amount as isu_amount_opening,
              yarn_used_opening.qty as used_qty_opening,
              yarn_used_opening.amount as used_amount_opening,
              yarn_return_opening.qty as return_qty_opening,
              yarn_return_opening.amount as return_amount_opening,

              yarn_isu.qty as isu_qty, 
              yarn_isu.amount as isu_amount,
              yarn_used.qty as used_qty,
              yarn_used.amount as used_amount,
              yarn_return.qty as return_qty,
              yarn_return.amount as return_amount   
              from suppliers
              join supplier_natures on suppliers.id=supplier_natures.supplier_id

              left join (
              select
              inv_isus.supplier_id,
              abs(sum(inv_yarn_transactions.store_qty)) as qty,
              sum(inv_yarn_transactions.store_amount) as amount
              from 
              inv_isus
              join inv_yarn_isu_items on inv_isus.id=inv_yarn_isu_items.inv_isu_id
              join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
              where 
               inv_isus.issue_date < ?
              and inv_yarn_transactions.deleted_at is null
              and inv_yarn_isu_items.deleted_at is null
              and inv_isus.deleted_at is null
              and inv_yarn_transactions.trans_type_id=2
              group by 
              inv_isus.supplier_id
              ) yarn_isu_opening on yarn_isu_opening.supplier_id=suppliers.id

              left join (
                select 
                inv_isus.supplier_id,
                sum(inv_yarn_rcv_items.qty) as qty,
                sum(inv_yarn_rcv_items.qty*inv_yarn_isu_items.rate) as amount
                from inv_yarn_rcv_items
                join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
                join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
                join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
                join inv_yarn_isu_items on inv_yarn_isu_items.id=inv_yarn_rcv_items.inv_yarn_isu_item_id
                join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
                where
                inv_rcvs.receive_date < ?
                and inv_yarn_transactions.deleted_at is null
                and inv_yarn_rcv_items.deleted_at is null
                and inv_rcvs.deleted_at is null
                and inv_yarn_transactions.trans_type_id=1
                and inv_rcvs.receive_against_id=9
                group by inv_isus.supplier_id
              ) yarn_used_opening on yarn_used_opening.supplier_id=suppliers.id

              left join (
                select 
                inv_rcvs.return_from_id,
                sum(inv_yarn_transactions.store_qty) as qty,
                sum(inv_yarn_transactions.store_amount) as amount
                from inv_yarn_rcv_items
                join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
                join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
                join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
                where
                 
                inv_rcvs.receive_date < ? 
                and inv_yarn_transactions.deleted_at is null
                and inv_yarn_rcv_items.deleted_at is null
                and inv_rcvs.deleted_at is null
                and inv_yarn_transactions.trans_type_id=1
                and inv_rcvs.menu_id in (105,106)
                group by inv_rcvs.return_from_id
              ) yarn_return_opening on yarn_return_opening.return_from_id=suppliers.id



              left join (
              select
              inv_isus.supplier_id,
              abs(sum(inv_yarn_transactions.store_qty)) as qty,
              sum(inv_yarn_transactions.store_amount) as amount
              from 
              inv_isus
              join inv_yarn_isu_items on inv_isus.id=inv_yarn_isu_items.inv_isu_id
              join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
              where 
              inv_isus.issue_date>=? 
              and inv_isus.issue_date<=?
              and inv_yarn_transactions.deleted_at is null
              and inv_yarn_isu_items.deleted_at is null
              and inv_isus.deleted_at is null
              and inv_yarn_transactions.trans_type_id=2
              group by 
              inv_isus.supplier_id
              ) yarn_isu on yarn_isu.supplier_id=suppliers.id

              

              left join (
                select 
                inv_isus.supplier_id,
                sum(inv_yarn_rcv_items.qty) as qty,
                sum(inv_yarn_rcv_items.qty*inv_yarn_isu_items.rate) as amount
                from inv_yarn_rcv_items
                join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
                join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
                join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
                join inv_yarn_isu_items on inv_yarn_isu_items.id=inv_yarn_rcv_items.inv_yarn_isu_item_id
                join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
                where
                inv_rcvs.receive_date >= ?
                and inv_rcvs.receive_date <= ?
                and inv_yarn_transactions.deleted_at is null
                and inv_yarn_rcv_items.deleted_at is null
                and inv_rcvs.deleted_at is null
                and inv_yarn_transactions.trans_type_id=1
                and inv_rcvs.receive_against_id=9
                group by inv_isus.supplier_id
              ) yarn_used on yarn_used.supplier_id=suppliers.id

              left join (
                select 
                inv_rcvs.return_from_id,
                sum(inv_yarn_transactions.store_qty) as qty,
                sum(inv_yarn_transactions.store_amount) as amount
                from inv_yarn_rcv_items
                join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
                join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
                join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
                where
                inv_rcvs.receive_date>=? 
                and inv_rcvs.receive_date<=? 
                and inv_yarn_transactions.deleted_at is null
                and inv_yarn_rcv_items.deleted_at is null
                and inv_rcvs.deleted_at is null
                and inv_yarn_transactions.trans_type_id=1
                and inv_rcvs.menu_id in (105,106)
                group by inv_rcvs.return_from_id
              ) yarn_return on yarn_return.return_from_id=suppliers.id

              where 
              supplier_natures.contact_nature_id=56
              and supplier_natures.deleted_at is null
              order by suppliers.name
          ", [$date_from, $date_from, $date_from, $date_from, $date_to, $date_from, $date_to, $date_from, $date_to])
        )
        ->map(function($results){
          $results->isu_qty_opening=$results->isu_qty_opening-($results->used_qty_opening+$results->return_qty_opening);
          $results->isu_amount_opening=$results->isu_amount_opening-($results->used_amount_opening+$results->return_amount_opening);

          $results->total_issue_qty=$results->isu_qty+$results->isu_qty_opening;
          $results->total_issue_amount=$results->isu_amount+$results->isu_amount_opening;
          $results->total_adjusted=$results->used_qty+$results->return_qty;
          $results->total_adjusted_amount=$results->used_amount+$results->return_amount;
          $results->stock_qty=$results->total_issue_qty-$results->total_adjusted;
          $results->stock_value=$results->total_issue_amount-$results->total_adjusted_amount;
          $results->rate=0;
          if($results->stock_qty){
          $results->rate=$results->stock_value/$results->stock_qty;
          }else{
          $results->rate=0;
          }

          $results->issue_qty=number_format($results->isu_qty,2);
          $results->opening_qty=number_format($results->isu_qty_opening,2);
          $results->total_issue_qty=number_format($results->total_issue_qty,2);

          $results->used_qty=number_format($results->used_qty,2);
          $results->return_qty=number_format($results->return_qty,2);
          $results->total_adjusted=number_format($results->total_adjusted,2);

          $results->stock_qty=number_format($results->stock_qty,2);
          $results->stock_value=number_format($results->stock_value,2);
          $results->rate=number_format($results->rate,2);
          return $results;
        });
        echo json_encode($results);
    }

    

    public function issueDtl(){

        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $supplier_id=request('supplier_id',0);

        $supplierCond='';
        if($supplier_id){
          $supplierCond= ' and inv_isus.supplier_id= '.$supplier_id;
        }
        else{
          $supplierCond= '';
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

        

        $results = collect(
          \DB::select("
            select
            inv_isus.id,
            inv_isus.issue_no,
            inv_isus.issue_date,
            inv_isus.supplier_id,
            inv_yarn_isu_items.id as inv_yarn_isu_item_id,
            inv_yarn_isu_items.remarks,
            inv_yarn_items.id,
            inv_yarn_items.lot,
            inv_yarn_items.brand,
            yarn_supplier.name as yarn_supplier_name,
            colors.name as yarn_color,
            inv_yarn_items.item_account_id,
            yarncounts.count,
            yarncounts.symbol,
            yarntypes.name as yarn_type,
            itemclasses.name as item_class_name,
            pl_sales_orders.sale_order_no,
            po_sales_orders.sale_order_no_po,
            abs(sum(inv_yarn_transactions.store_qty)) as qty,
            sum(inv_yarn_transactions.store_amount) as amount
            from 
            inv_isus
            join inv_yarn_isu_items on inv_isus.id=inv_yarn_isu_items.inv_isu_id
            join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
            join inv_yarn_items on inv_yarn_items.id=inv_yarn_isu_items.inv_yarn_item_id
            join item_accounts on item_accounts.id=inv_yarn_items.item_account_id
            join yarncounts on yarncounts.id=item_accounts.yarncount_id
            join yarntypes on yarntypes.id=item_accounts.yarntype_id
            join itemclasses on itemclasses.id=item_accounts.itemclass_id
            join suppliers yarn_supplier on yarn_supplier.id=inv_yarn_items.supplier_id
            join colors on colors.id=inv_yarn_items.color_id
            join suppliers on suppliers.id=inv_isus.supplier_id
            join supplier_natures on suppliers.id=supplier_natures.supplier_id
            left join rq_yarn_items on rq_yarn_items.id=inv_yarn_isu_items.rq_yarn_item_id
            left join rq_yarn_fabrications on rq_yarn_fabrications.id=rq_yarn_items.rq_yarn_fabrication_id
            left join rq_yarns on rq_yarns.id=rq_yarn_fabrications.rq_yarn_id
            left join (
            select 
            pl_knit_items.id,
            sales_orders.sale_order_no,
            styles.style_ref,
            buyers.name as buyer_name,
            style_fabrications.autoyarn_id,
            budget_fabrics.gsm_weight,
            colorranges.name as colorrange_name
            from pl_knit_items
            join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
            join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
            join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id
            join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
            join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
            join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            left join colorranges on colorranges.id=pl_knit_items.colorrange_id
            ) pl_sales_orders on pl_sales_orders.id=rq_yarn_fabrications.pl_knit_item_id
            left join (
            select po_knit_service_item_qties.id,
            sales_orders.sale_order_no as sale_order_no_po,
            styles.style_ref as style_ref_po,
            buyers.name  as buyer_name_po,
            style_fabrications.autoyarn_id as autoyarn_id_po,
            budget_fabrics.gsm_weight as gsm_weight_po,
            colorranges.name as colorrange_name_po
            from po_knit_service_item_qties
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id
            join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
            join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
            join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id

            ) po_sales_orders on po_sales_orders.id=rq_yarn_fabrications.po_knit_service_item_qty_id
            where 
            inv_isus.issue_date >= ?
            and inv_isus.issue_date <= ?
            $supplierCond
            and supplier_natures.contact_nature_id=56
            and supplier_natures.deleted_at is null
            and inv_yarn_transactions.deleted_at is null
            and inv_yarn_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_yarn_transactions.trans_type_id=2
            group by 
            inv_isus.id,
            inv_isus.issue_no,
            inv_isus.issue_date,
            inv_isus.supplier_id,
            inv_yarn_isu_items.id,
            inv_yarn_isu_items.remarks,
            inv_yarn_items.id,
            inv_yarn_items.lot,
            inv_yarn_items.brand,
            yarn_supplier.name,
            colors.name,
            inv_yarn_items.item_account_id,
            yarncounts.count,
            yarncounts.symbol,
            yarntypes.name,
            itemclasses.name,
            pl_sales_orders.sale_order_no,
            po_sales_orders.sale_order_no_po
          ", [$date_from, $date_to])
        )
        ->map(function($results) use($yarnDropdown){
          $results->yarn_desc=$yarnDropdown[$results->item_account_id];
          $results->yarn_count=$results->count."/".$results->symbol;
          $results->issue_date=date('d-M-Y',strtotime($results->issue_date));
          $results->sale_order_no=$results->sale_order_no?$results->sale_order_no:$results->sale_order_no_po;


         
          $results->rate=0;
          if($results->qty){
          $results->rate=$results->amount/$results->qty;
          }

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
        $supplier_id=request('supplier_id',0);

        $supplierCond='';
        if($supplier_id){
          $supplierCond= ' and inv_isus.supplier_id= '.$supplier_id;
        }
        else{
          $supplierCond= '';
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

        

        $results = collect(
          \DB::select("
            select 
              inv_rcvs.id,
              inv_rcvs.receive_no,
              inv_rcvs.receive_date,
              inv_isus.supplier_id,
              inv_yarn_rcv_items.id as inv_yarn_rcv_item_id,
              inv_yarn_rcv_items.remarks,
              inv_yarn_items.id as inv_yarn_item_id,
              inv_yarn_items.lot,
              inv_yarn_items.brand,
              yarn_supplier.name as yarn_supplier_name,
              colors.name as yarn_color,
              inv_yarn_items.item_account_id,
              yarncounts.count,
              yarncounts.symbol,
              yarntypes.name as yarn_type,
              itemclasses.name as item_class_name,
              sales_orders.sale_order_no,

              sum(inv_yarn_rcv_items.used_yarn) as qty,
              sum(inv_yarn_rcv_items.used_yarn*inv_yarn_isu_items.rate) as amount
              from inv_yarn_rcv_items
              join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
              join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
              join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
              join inv_yarn_isu_items on inv_yarn_isu_items.id=inv_yarn_rcv_items.inv_yarn_isu_item_id
              join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
              join inv_yarn_items on inv_yarn_items.id=inv_yarn_isu_items.inv_yarn_item_id
              join item_accounts on item_accounts.id=inv_yarn_items.item_account_id
              join yarncounts on yarncounts.id=item_accounts.yarncount_id
              join yarntypes on yarntypes.id=item_accounts.yarntype_id
              join itemclasses on itemclasses.id=item_accounts.itemclass_id
              join suppliers yarn_supplier on yarn_supplier.id=inv_yarn_items.supplier_id
              join colors on colors.id=inv_yarn_items.color_id
              join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.id=inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id
              join po_yarn_dyeing_items on po_yarn_dyeing_items.id=po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id
              join po_yarn_dyeings on po_yarn_dyeings.id=po_yarn_dyeing_items.po_yarn_dyeing_id
              join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.id=po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id
              join sales_orders on sales_orders.id=budget_yarn_dyeing_cons.sales_order_id
              where
              inv_rcvs.receive_date >= ?
              and inv_rcvs.receive_date <= ? 
              $supplierCond
              and inv_yarn_transactions.deleted_at is null
              and inv_yarn_rcv_items.deleted_at is null
              and inv_rcvs.deleted_at is null
              and inv_yarn_transactions.trans_type_id=1
              and inv_rcvs.receive_against_id=9
              group by 
              inv_rcvs.id,
              inv_rcvs.receive_no,
              inv_rcvs.receive_date,
              inv_isus.supplier_id,
              inv_yarn_rcv_items.id,
              inv_yarn_rcv_items.remarks,
              inv_yarn_items.id,
              inv_yarn_items.lot,
              inv_yarn_items.brand,
              yarn_supplier.name,
              colors.name,
              inv_yarn_items.item_account_id,
              yarncounts.count,
              yarncounts.symbol,
              yarntypes.name,
              itemclasses.name,
              sales_orders.sale_order_no
          ", [$date_from, $date_to])
        )
        ->map(function($results) use($yarnDropdown){
          $results->yarn_desc=$yarnDropdown[$results->item_account_id];
          $results->yarn_count=$results->count."/".$results->symbol;
          $results->prod_date=date('d-M-Y',strtotime($results->receive_date));
          $results->rate=0;
          if($results->qty){
          $results->rate=$results->amount/$results->qty;
          }

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
        $supplier_id=request('supplier_id',0);

        $supplierCond='';
        if($supplier_id){
          $supplierCond= ' and inv_rcvs.return_from_id= '.$supplier_id;
        }
        else{
          $supplierCond= '';
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

        

        $results = collect(
          \DB::select("
            select 
            inv_rcvs.id,
            inv_rcvs.receive_no,
            inv_rcvs.receive_date,
            inv_rcvs.return_from_id,
            inv_yarn_rcv_items.id as inv_yarn_rcv_item_id,
            inv_yarn_rcv_items.remarks,
            inv_yarn_items.id as inv_yarn_item_id,
            inv_yarn_items.lot,
            inv_yarn_items.brand,
            yarn_supplier.name as yarn_supplier_name,
            colors.name as yarn_color,
            inv_yarn_items.item_account_id,
            yarncounts.count,
            yarncounts.symbol,
            yarntypes.name as yarn_type,
            itemclasses.name as item_class_name,
            sales_orders.sale_order_no,
            sum(inv_yarn_transactions.store_qty) as qty,
            sum(inv_yarn_transactions.store_amount) as amount
            from inv_yarn_rcv_items
            join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
            join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
            join inv_yarn_items on inv_yarn_items.id=inv_yarn_rcv_items.inv_yarn_item_id
            join item_accounts on item_accounts.id=inv_yarn_items.item_account_id
            join yarncounts on yarncounts.id=item_accounts.yarncount_id
            join yarntypes on yarntypes.id=item_accounts.yarntype_id
            join itemclasses on itemclasses.id=item_accounts.itemclass_id
            join suppliers yarn_supplier on yarn_supplier.id=inv_yarn_items.supplier_id
            join colors on colors.id=inv_yarn_items.color_id
            left join sales_orders on sales_orders.id=inv_yarn_rcv_items.sales_order_id
            where
            inv_rcvs.receive_date>= ?
            and inv_rcvs.receive_date<= ?
            $supplierCond
            and inv_yarn_transactions.deleted_at is null
            and inv_yarn_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_yarn_transactions.trans_type_id=1
            and inv_rcvs.menu_id in (105,106)
            group by 
            inv_rcvs.id,
            inv_rcvs.receive_no,
            inv_rcvs.receive_date,
            inv_rcvs.return_from_id,
            inv_yarn_rcv_items.id,
            inv_yarn_rcv_items.remarks,
            inv_yarn_items.id,
            inv_yarn_items.lot,
            inv_yarn_items.brand,
            yarn_supplier.name,
            colors.name,
            inv_yarn_items.item_account_id,
            yarncounts.count,
            yarncounts.symbol,
            yarntypes.name,
            itemclasses.name,
            sales_orders.sale_order_no
          ", [$date_from, $date_to])
        )
        ->map(function($results) use($yarnDropdown){
          $results->yarn_desc=$yarnDropdown[$results->item_account_id];
          $results->yarn_count=$results->count."/".$results->symbol;
          $results->receive_date=date('d-M-Y',strtotime($results->receive_date));
          $results->rate=0;
          if($results->qty){
          $results->rate=$results->amount/$results->qty;
          }

          $results->qty=number_format($results->qty,2);
          $results->amount=number_format($results->amount,2);
          $results->rate=number_format($results->rate,2);
          return $results;
        });
        echo json_encode($results);
    }
}
