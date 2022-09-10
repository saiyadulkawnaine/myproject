<?php
namespace App\Http\Controllers\Report\FabricProduction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuItemRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use Illuminate\Support\Carbon;

class ProdFinishQcBatchCostingController extends Controller {

    private $prodbatch;
    private $invdyechemisurq;
    private $invdyechemisuitem;
    private $invisu;
    private $buyer;
    private $company;
    private $autoyarn;
    private $gmtspart;
    private $colorrange;
    private $color;

    public function __construct(
        ProdBatchRepository $prodbatch,
        InvIsuRepository $invisu,
        InvDyeChemIsuRqRepository $invdyechemisurq,
        InvDyeChemIsuItemRepository $invdyechemisuitem,
        BuyerRepository $buyer,
        CompanyRepository $company, 
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        ColorrangeRepository $colorrange,
        ColorRepository $color
        ) 
    {
        $this->prodbatch = $prodbatch;
        $this->invdyechemisurq = $invdyechemisurq;
        $this->invdyechemisuitem = $invdyechemisuitem;
        $this->invisu = $invisu;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->colorrange = $colorrange;
        $this->color = $color;

        $this->middleware('auth');
      //$this->middleware('permission:view.prodgmtcartonqtyreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        return Template::loadView('Report.FabricProduction.ProdFinishQcBatchCosting',['company'=>$company,'batchfor'=>$batchfor]);
    }

    public function reportData(){
        $company_id=request('company_id',0);
        $date_from =request('date_from',0);
        $date_to=request('date_to',0);
        $batch_for=request('batch_for',0);
        $batch_no=request('batch_no',0);

        if($date_from){
			$datefrom=" and prod_batches.unload_posting_date >='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and prod_batches.unload_posting_date <='".$date_to."' ";
		}
        $companyId=null;
        if($company_id){
            $companyId= "and prod_batches.company_id=".$company_id;
        }
        $batchfor=null;
        if($batch_for){
            $batchfor= "and prod_batches.batch_for=".$batch_for;
        }
        $batchNo=null;
        if($batch_no){
            $batchNo= "and prod_batches.batch_no=".$batch_no;
        }



        $rows=collect(
            \DB::select("
            select
                prod_batches.id as prod_batch_id,
                prod_batches.batch_no,
                customers.name as customer_name,
                buyers.name as buyer_name,
                styles.style_ref,
                c_buyers.name as c_buyer_name,
                so_dyeing_items.gmt_style_ref,
                
                prod_batches.lap_dip_no,
                prod_batches.loaded_at,
                prod_batches.unloaded_at,
                prod_batches.tgt_hour,
                colors.name as fabric_color_name,
                colorranges.name as colorrange,
                asset_quantity_costs.custom_no,
                asset_acquisitions.prod_capacity,
                sum(prod_batch_finish_qc_rolls.qty) as qc_pass_qty,
                sum(prod_batch_rolls.qty) as batch_qty,
                avg(po_dyeing_service_item_qties.rate) as rate,
                avg(so_dyeing_items.rate) as c_rate,
                dyescost.dyes_cost_amount,
                chemcost.chem_cost_amount,
                overhead.cost_per,
                colorRatio.per_on_batch_wgt,
                avg(so_dyeings.exch_rate) as exch_rate
                
            from
            prod_batch_finish_qcs
            join prod_batches on prod_batch_finish_qcs.prod_batch_id=prod_batches.id
            join asset_quantity_costs on asset_quantity_costs.id=prod_batches.machine_id
            join asset_acquisitions on asset_acquisitions.id=asset_quantity_costs.asset_acquisition_id
            join colors on colors.id=prod_batches.fabric_color_id
            join colorranges on colorranges.id=prod_batches.colorrange_id
            join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_finish_qc_id=prod_batch_finish_qcs.id
            join prod_batch_rolls on prod_batch_rolls.id=prod_batch_finish_qc_rolls.prod_batch_roll_id
            --JOIN PROD_BATCH_ROLLS ON PROD_BATCH_ROLLS.PROD_BATCH_ID=PROD_BATCHES.ID
            join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
            join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
            join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
            join so_dyeings on so_dyeing_refs.so_dyeing_id=so_dyeings.id
            join buyers customers on customers.id=so_dyeings.buyer_id
            
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join buyers c_buyers on c_buyers.id=so_dyeing_items.gmt_buyer
            
            left join so_dyeing_pos on so_dyeing_pos.so_dyeing_id=so_dyeings.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
            left join jobs on jobs.id=sales_orders.job_id
            left join styles on styles.id=jobs.style_id
            left join buyers on buyers.id=styles.buyer_id
            left join (
                select 
                prod_batches.id as prod_batch_id,
                sum(inv_dye_chem_transactions.store_amount) as dyes_cost_amount
                from 
                prod_batches
                join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.prod_batch_id=prod_batches.id
                join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rqs.id
                join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id=inv_dye_chem_isu_rq_items.id
                join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
                
                join item_accounts on item_accounts.id=inv_dye_chem_isu_rq_items.item_account_id
                join itemcategories on itemcategories.id=item_accounts.itemcategory_id
                where  inv_dye_chem_transactions.deleted_at is null
                and inv_dye_chem_isu_rq_items.deleted_at is null
                and inv_dye_chem_transactions.trans_type_id=2
                and itemcategories.identity=7 
                group by 
                prod_batches.id
            ) dyescost on dyescost.prod_batch_id=prod_batches.id
            left join (
                select 
                prod_batches.id as prod_batch_id,
                sum(inv_dye_chem_transactions.store_amount) as chem_cost_amount
                from 
                prod_batches
                join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.prod_batch_id=prod_batches.id
                join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rqs.id
                join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id=inv_dye_chem_isu_rq_items.id
                join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
                join item_accounts on item_accounts.id=inv_dye_chem_isu_rq_items.item_account_id
                join itemcategories on itemcategories.id=item_accounts.itemcategory_id 
                where  inv_dye_chem_transactions.deleted_at is null
                and inv_dye_chem_isu_rq_items.deleted_at is null
                and inv_dye_chem_transactions.trans_type_id=2
                and itemcategories.identity=8 
                group by 
                prod_batches.id
            ) chemcost on chemcost.prod_batch_id=prod_batches.id
            left join (
                select
                cost_standards.company_id,
                sum(cost_standard_heads.cost_per) as cost_per
                from
                companies
                join cost_standards on companies.id=cost_standards.company_id
                join cost_standard_heads on cost_standard_heads.cost_standard_id=cost_standards.id
                where cost_standards.configuration_type_id=140
                group by
                cost_standards.company_id
            ) overhead on overhead.company_id=prod_batches.company_id
            left join (
                select 
                prod_batches.id as prod_batch_id,
                sum(inv_dye_chem_isu_rq_items.per_on_batch_wgt) as per_on_batch_wgt
                from 
                prod_batches
                join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.prod_batch_id=prod_batches.id
                join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rqs.id
                join item_accounts on item_accounts.id=inv_dye_chem_isu_rq_items.item_account_id
                join itemcategories on itemcategories.id=item_accounts.itemcategory_id 
                where  inv_dye_chem_isu_rq_items.deleted_at is null
                and itemcategories.identity=7 
                group by 
                prod_batches.id
            ) colorRatio on colorRatio.prod_batch_id=prod_batches.id
            where prod_batches.unloaded_at is not null 
            and prod_batches.deleted_at is null
            and prod_batch_rolls.deleted_at is null
            $datefrom $dateto $batchfor $batchNo $companyId
            group by
                prod_batches.id,
                prod_batches.batch_no,
                customers.name,
                buyers.name,
                styles.style_ref,
                c_buyers.name,
                so_dyeing_items.gmt_style_ref,
                prod_batches.lap_dip_no,
                prod_batches.loaded_at,
                prod_batches.unloaded_at,
                prod_batches.tgt_hour,
                colors.name,
                colorranges.name,
                asset_quantity_costs.custom_no,
                asset_acquisitions.prod_capacity,
                dyescost.dyes_cost_amount,
                chemcost.chem_cost_amount,
                overhead.cost_per,
                colorRatio.per_on_batch_wgt
            order by
            prod_batches.id
            ")
        )
        ->map(function($rows) {
            $rows->ratio=$rows->per_on_batch_wgt.' %';
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->c_buyer_name;
            $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
            $revenue=$rows->batch_qty*$rows->rate*$rows->exch_rate;
            $overhead=($revenue*$rows->cost_per)/100;
            $profit=$revenue-($rows->dyes_cost_amount+$rows->chem_cost_amount+$overhead);

            $revenue_per=0;
            if ($revenue) {
                $revenue_per=(($rows->dyes_cost_amount+$rows->chem_cost_amount)/$revenue)*100;
            }
            $profit_per=0;
            if ($revenue) {
                $profit_per=($profit/$revenue)*100;
            }
            $utilize_per=0;
            if ($rows->prod_capacity) {
                $utilize_per=($rows->batch_qty/$rows->prod_capacity)*100;
            }
            $process_loss=$rows->batch_qty-$rows->qc_pass_qty;
            $process_loss_per=0;
            if ($rows->batch_qty) {
                $process_loss_per=($process_loss/$rows->batch_qty)*100;
            }
            $rows->revenue=number_format($revenue,2);
            $rows->revenue_per=number_format($revenue_per,2).' %';
            $rows->overhead=number_format($overhead,2);
            $rows->profit=number_format($profit,2);
            $rows->profit_per=number_format($profit_per,2).' %';
            $rows->utilize_per=number_format($utilize_per,2).' %';
            $rows->process_loss=number_format($process_loss,2);
            $rows->process_loss_per=number_format($process_loss_per,2).' %';
            $rows->batch_qty=number_format($rows->batch_qty,2);
            $rows->qc_pass_qty=number_format($rows->qc_pass_qty,2);
            $rows->prod_capacity=number_format($rows->prod_capacity,2);
            $rows->dyes_cost_amount=number_format($rows->dyes_cost_amount,2);
            $rows->chem_cost_amount=number_format($rows->chem_cost_amount,2);
            $loaded_at=Carbon::parse($rows->loaded_at);
            $unloaded_at=Carbon::parse($rows->unloaded_at);
            $rows->hour_used=$unloaded_at->diffInHours($loaded_at);
            $rows->additional_hour=$rows->tgt_hour-$rows->hour_used;
            $rows->loaded_at=$rows->loaded_at?date('d-M-Y h:i A',strtotime($rows->loaded_at)):'--';
            $rows->unloaded_at=$rows->unloaded_at?date('d-M-Y h:i A',strtotime($rows->unloaded_at)):'--';
            return $rows;
        });

        echo json_encode($rows);
    }

    public function getCostSheet(){
        $prod_batch_id=request('prod_batch_id', 0);
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');

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

        $salesorder=$this->prodbatch
        ->selectRaw('
            prod_batches.id as prod_batch_id,
            sales_orders.org_ship_date,
            sales_orders.ship_date,
            sales_orders.sale_order_no,
            so_dyeing_items.gmt_sale_order_no,
            so_dyeing_items.autoyarn_id as c_autoyarn_id,
            inv_grey_fab_items.autoyarn_id
        ')
        ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
        })
        ->join('so_dyeing_fabric_rcv_rols',function($join){
            $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
        })
        ->join('so_dyeing_fabric_rcv_items',function($join){
            $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
        })
        ->leftJoin('inv_grey_fab_isu_items',function($join){
            $join->on('inv_grey_fab_isu_items.id', '=', 'so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id');
        })
        ->leftJoin('inv_grey_fab_items',function($join){
            $join->on('inv_grey_fab_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_item_id');
        })
        ->join('so_dyeing_refs',function($join){
            $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
        })
        ->join('so_dyeings',function($join){
            $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
         ->join('buyers as customers',function($join){
            $join->on('customers.id','=','so_dyeings.buyer_id');
        })
        ->leftJoin('so_dyeing_items',function($join){
            $join->on('so_dyeing_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
        })
        ->leftJoin ('buyers',function($join){
            $join->on('buyers.id', '=', 'so_dyeing_items.gmt_buyer');
        })
        ->leftJoin('so_dyeing_pos',function($join){
            $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_po_items',function($join){
            $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
        })
        ->leftJoin('po_dyeing_service_item_qties',function($join){
            $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
        })
        ->leftJoin('po_dyeing_service_items',function($join){
            $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
            $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
        })
        ->where([['prod_batches.id','=',$prod_batch_id]])
        ->groupBy([
            'prod_batches.id',
            'sales_orders.org_ship_date',
            'sales_orders.ship_date',
            'sales_orders.sale_order_no',
            'so_dyeing_items.gmt_sale_order_no',
            'so_dyeing_items.autoyarn_id',
            'inv_grey_fab_items.autoyarn_id'
        ])
        ->get()
        ->map(function($salesorder) use($desDropdown){
            $salesorder->sale_order_no=$salesorder->sale_order_no?$salesorder->sale_order_no:$salesorder->gmt_sale_order_no;
            $salesorder->org_ship_date=$salesorder->org_ship_date?date('d-M-Y',strtotime($salesorder->org_ship_date)):'';
            $salesorder->autoyarn_id=$salesorder->autoyarn_id?$salesorder->autoyarn_id:$salesorder->c_autoyarn_id;
            $salesorder->fabrication=isset($desDropdown[$salesorder->autoyarn_id])?$desDropdown[$salesorder->autoyarn_id]:'';
            return $salesorder;
        });

        //dd($salesorder);die;

        $order=[];
        $shipdate=[];
        $batchFabricDescription=[];
        foreach($salesorder as $so){
            $order[$so->prod_batch_id][$so->sale_order_no]=$so->sale_order_no;
            $shipdate[$so->prod_batch_id][$so->org_ship_date]=$so->org_ship_date;
            $batchFabricDescription[$so->prod_batch_id][$so->autoyarn_id]=$so->fabrication;
        }

        // dd(implode(',',$batchFabricDescription[$so->prod_batch_id]));die;
        


        $rows=collect(
            \DB::select("
            select
                prod_batches.id as prod_batch_id,
                prod_batches.batch_no,
                prod_batches.batch_for,
                prod_batches.batch_date,
                prod_batches.lap_dip_no,
                prod_batches.loaded_at,
                prod_batches.unloaded_at,
                prod_batches.tgt_hour,
                customers.name as customer_name,
                buyers.name as buyer_name,
                styles.style_ref,
                styles.flie_src,
                c_buyers.name as c_buyer_name,
                so_dyeing_items.gmt_style_ref,
                
                batch_colors.name as batch_color_name,
                colors.name as fabric_color_name,
                colorranges.name as colorrange,
                asset_quantity_costs.custom_no,
                asset_acquisitions.prod_capacity,
                companies.name as company_name,
                companies.logo,
                companies.address as company_address,
                sum(prod_batch_finish_qc_rolls.qty) as qc_pass_qty,
                sum(prod_batch_rolls.qty) as batch_qty,
                avg(po_dyeing_service_item_qties.rate) as rate,
                avg(so_dyeing_items.rate) as c_rate,
                dyescost.dyes_cost_amount,
                chemcost.chem_cost_amount,
                overhead.cost_per,
                colorRatio.per_on_batch_wgt,
                avg(so_dyeings.exch_rate) as exch_rate
                
            from
            prod_batch_finish_qcs
            join prod_batches on prod_batch_finish_qcs.prod_batch_id=prod_batches.id
            join asset_quantity_costs on asset_quantity_costs.id=prod_batches.machine_id
            join asset_acquisitions on asset_acquisitions.id=asset_quantity_costs.asset_acquisition_id
            join colors on colors.id=prod_batches.fabric_color_id
            join colors batch_colors on batch_colors.id = prod_batches.batch_color_id
            join colorranges on colorranges.id=prod_batches.colorrange_id
            join companies on companies.id=prod_batches.company_id
            join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_finish_qc_id=prod_batch_finish_qcs.id
            join prod_batch_rolls on prod_batch_rolls.id=prod_batch_finish_qc_rolls.prod_batch_roll_id
            --JOIN PROD_BATCH_ROLLS ON PROD_BATCH_ROLLS.PROD_BATCH_ID=PROD_BATCHES.ID
            join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
            join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
            join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
            join so_dyeings on so_dyeing_refs.so_dyeing_id=so_dyeings.id
            join buyers customers on customers.id=so_dyeings.buyer_id
            
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join buyers c_buyers on c_buyers.id=so_dyeing_items.gmt_buyer
            
            left join so_dyeing_pos on so_dyeing_pos.so_dyeing_id=so_dyeings.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
            left join jobs on jobs.id=sales_orders.job_id
            left join styles on styles.id=jobs.style_id
            left join buyers on buyers.id=styles.buyer_id
            left join (
                select 
                prod_batches.id as prod_batch_id,
                sum(inv_dye_chem_transactions.store_amount) as dyes_cost_amount
                from 
                prod_batches
                join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.prod_batch_id=prod_batches.id
                join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rqs.id
                join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id=inv_dye_chem_isu_rq_items.id
                join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
                
                join item_accounts on item_accounts.id=inv_dye_chem_isu_rq_items.item_account_id
                join itemcategories on itemcategories.id=item_accounts.itemcategory_id
                where  inv_dye_chem_transactions.deleted_at is null
                and inv_dye_chem_isu_rq_items.deleted_at is null
                and inv_dye_chem_transactions.trans_type_id=2
                and itemcategories.identity=7 
                group by 
                prod_batches.id
            ) dyescost on dyescost.prod_batch_id=prod_batches.id
            left join (
                select 
                prod_batches.id as prod_batch_id,
                sum(inv_dye_chem_transactions.store_amount) as chem_cost_amount
                from 
                prod_batches
                join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.prod_batch_id=prod_batches.id
                join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rqs.id
                join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id=inv_dye_chem_isu_rq_items.id
                join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
                join item_accounts on item_accounts.id=inv_dye_chem_isu_rq_items.item_account_id
                join itemcategories on itemcategories.id=item_accounts.itemcategory_id 
                where  inv_dye_chem_transactions.deleted_at is null
                and inv_dye_chem_isu_rq_items.deleted_at is null
                and inv_dye_chem_transactions.trans_type_id=2
                and itemcategories.identity=8 
                group by 
                prod_batches.id
            ) chemcost on chemcost.prod_batch_id=prod_batches.id
            left join (
                select
                cost_standards.company_id,
                sum(cost_standard_heads.cost_per) as cost_per
                from
                companies
                join cost_standards on companies.id=cost_standards.company_id
                join cost_standard_heads on cost_standard_heads.cost_standard_id=cost_standards.id
                where cost_standards.configuration_type_id=140
                group by
                cost_standards.company_id
            ) overhead on overhead.company_id=prod_batches.company_id
            left join (
                select 
                prod_batches.id as prod_batch_id,
                sum(inv_dye_chem_isu_rq_items.per_on_batch_wgt) as per_on_batch_wgt
                from 
                prod_batches
                join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.prod_batch_id=prod_batches.id
                join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rqs.id
                join item_accounts on item_accounts.id=inv_dye_chem_isu_rq_items.item_account_id
                join itemcategories on itemcategories.id=item_accounts.itemcategory_id 
                where  inv_dye_chem_isu_rq_items.deleted_at is null
                and itemcategories.identity=7 
                group by 
                prod_batches.id
            ) colorRatio on colorRatio.prod_batch_id=prod_batches.id
            where prod_batches.unloaded_at is not null 
            and prod_batches.deleted_at is null
            and prod_batch_rolls.deleted_at is null
            and prod_batches.id=?
            group by
                prod_batches.id,
                prod_batches.batch_no,
                prod_batches.batch_for,
                prod_batches.batch_date,
                prod_batches.lap_dip_no,
                prod_batches.loaded_at,
                prod_batches.unloaded_at,
                prod_batches.tgt_hour,
                customers.name,
                buyers.name,
                styles.style_ref,
                styles.flie_src,
                c_buyers.name,
                so_dyeing_items.gmt_style_ref,
                colors.name,
                batch_colors.name,
                colorranges.name,
                asset_quantity_costs.custom_no,
                asset_acquisitions.prod_capacity,
                companies.name,
                companies.logo,
                companies.address,
                dyescost.dyes_cost_amount,
                chemcost.chem_cost_amount,
                overhead.cost_per,
                colorRatio.per_on_batch_wgt
            order by
            prod_batches.id
            ",[$prod_batch_id])
        )
        ->map(function($rows) use($order,$shipdate,$batchfor,$batchFabricDescription){
            $rows->ratio=$rows->per_on_batch_wgt.' %';
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->c_buyer_name;
            $rows->batch_date=$rows->batch_date?date('d-M-Y',strtotime($rows->batch_date)):'--';
            $rows->sale_order_no=$order[$rows->prod_batch_id]?implode(', ',$order[$rows->prod_batch_id]):'';
            $rows->org_ship_date=$shipdate[$rows->prod_batch_id]?implode(', ',$shipdate[$rows->prod_batch_id]):'';
            $rows->fabrication=$batchFabricDescription[$rows->prod_batch_id]?implode(' ; ',$batchFabricDescription[$rows->prod_batch_id]):'';
            $rows->batch_for=$batchfor[$rows->batch_for];
            $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
            $revenue=$rows->batch_qty*$rows->rate*$rows->exch_rate;
            $overhead=($revenue*$rows->cost_per)/100;
            $profit=$revenue-($rows->dyes_cost_amount+$rows->chem_cost_amount+$overhead);

            $revenue_per=0;
            if ($revenue) {
                $revenue_per=(($rows->dyes_cost_amount+$rows->chem_cost_amount)/$revenue)*100;
            }
            $profit_per=0;
            if ($revenue) {
                $profit_per=($profit/$revenue)*100;
            }
            $utilize_per=0;
            if ($rows->prod_capacity) {
                $utilize_per=($rows->batch_qty/$rows->prod_capacity)*100;
            }
            $process_loss=$rows->batch_qty-$rows->qc_pass_qty;
            $process_loss_per=0;
            if ($rows->batch_qty) {
                $process_loss_per=($process_loss/$rows->batch_qty)*100;
            }
            $rows->revenue=$revenue;
            $rows->revenue_per=$revenue_per;
            $rows->overhead=$overhead;
            $rows->profit=$profit;
            $rows->profit_per=$profit_per;
            $rows->utilize_per=$utilize_per;
            $rows->process_loss=$process_loss;
            $rows->process_loss_per=$process_loss_per;
            $rows->batch_qty=$rows->batch_qty;
            $rows->qc_pass_qty=$rows->qc_pass_qty;
            $rows->prod_capacity=$rows->prod_capacity;
            $rows->dyes_cost_amount=$rows->dyes_cost_amount;
            $rows->chem_cost_amount=$rows->chem_cost_amount;
            $loaded_at=Carbon::parse($rows->loaded_at);
            $unloaded_at=Carbon::parse($rows->unloaded_at);
            $rows->hour_used=$unloaded_at->diffInHours($loaded_at);
            $rows->additional_hour=$rows->tgt_hour-$rows->hour_used;
            $rows->loaded_at=$rows->loaded_at?date('d-M-Y h:i A',strtotime($rows->loaded_at)):'--';
            $rows->unloaded_at=$rows->unloaded_at?date('d-M-Y h:i A',strtotime($rows->unloaded_at)):'--';
            return $rows;
        })
        ->first();

        $coststandardOverhead=$this->prodbatch
        ->selectRaw('
            cost_standard_heads.id as cost_standard_head_id,
            cost_standard_heads.cost_per,
            acc_chart_ctrl_heads.name as acc_chart_ctrl_head_name
        ')
        ->join('companies',function($join){
            $join->on('companies.id', '=', 'prod_batches.company_id');
        })
        ->join('cost_standards',function($join){
            $join->on('companies.id', '=', 'cost_standards.company_id');
        })
        ->join('cost_standard_heads',function($join){
            $join->on('cost_standard_heads.cost_standard_id', '=', 'cost_standards.id');
        })
        ->join('acc_chart_ctrl_heads', function($join){
            $join->on('acc_chart_ctrl_heads.id', '=', 'cost_standard_heads.acc_chart_ctrl_head_id');
        })
        ->where([['prod_batches.id','=',$prod_batch_id]])
        ->get();

        $dyecost=collect(\DB::select("
            select 
            prod_batches.id as prod_batch_id,
            itemclasses.name as itemclass_name,
            sum(inv_dye_chem_isu_items.qty) as qty,
            avg(inv_dye_chem_isu_items.rate) as rate,
            sum(inv_dye_chem_transactions.store_amount) as amount
            from 
            prod_batches
            join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.prod_batch_id=prod_batches.id
            join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rqs.id
            join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id=inv_dye_chem_isu_rq_items.id
            join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
            
            join item_accounts on item_accounts.id=inv_dye_chem_isu_rq_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            join itemclasses on itemclasses.id=item_accounts.itemclass_id
            where  inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_isu_rq_items.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=2
            and itemcategories.identity=7 
            and prod_batches.id=?
            group by 
            prod_batches.id,
            itemclasses.name
        ",[$prod_batch_id]));

        $chemcost=collect(\DB::select("
            select 
            prod_batches.id as prod_batch_id,
            itemclasses.name as itemclass_name,
            sum(inv_dye_chem_isu_items.qty) as qty,
            avg(inv_dye_chem_isu_items.rate) as rate,
            sum(inv_dye_chem_transactions.store_amount) as amount
            from 
            prod_batches
            join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.prod_batch_id=prod_batches.id
            join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rqs.id
            join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id=inv_dye_chem_isu_rq_items.id
            join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
            
            join item_accounts on item_accounts.id=inv_dye_chem_isu_rq_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            join itemclasses on itemclasses.id=item_accounts.itemclass_id
            where  inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_isu_rq_items.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=2
            and itemcategories.identity=8 
            and prod_batches.id=?
            group by 
            prod_batches.id,
            itemclasses.name
        ",[$prod_batch_id]));

        $requisitionRecipe=$this->invdyechemisurq
        ->where([['inv_dye_chem_isu_rqs.prod_batch_id','=',$prod_batch_id]])
        ->get([
            'inv_dye_chem_isu_rqs.prod_batch_id',
            'inv_dye_chem_isu_rqs.id as inv_dye_chem_isu_rq_id',
            'inv_dye_chem_isu_rqs.rq_no'
        ]);
        $recipeArr=[];
        foreach ($requisitionRecipe as $rq) {
            $recipeArr[$rq->prod_batch_id][]=$rq->inv_dye_chem_isu_rq_id;
        }

        $invisu = $this->invisu
        ->selectRaw('
            inv_dye_chem_isu_rqs.prod_batch_id,
            inv_isus.issue_no
        ')
        ->join('inv_dye_chem_isu_items',function($join){
          $join->on('inv_dye_chem_isu_items.inv_isu_id','=','inv_isus.id');
        })
        ->join('inv_dye_chem_isu_rq_items',function($join){
          $join->on('inv_dye_chem_isu_rq_items.id','=','inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id');
        })
        ->join('inv_dye_chem_isu_rqs',function($join){
          $join->on('inv_dye_chem_isu_rqs.id','=','inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id');
        })
        ->where([['inv_dye_chem_isu_rqs.prod_batch_id','=',$prod_batch_id]])
        ->groupBy([
            'inv_dye_chem_isu_rqs.prod_batch_id',
            'inv_isus.issue_no',
        ])
        ->get();

        $issueNoArr=[];
        foreach ($invisu as $isu) {
            $issueNoArr[$isu->prod_batch_id][]=$isu->issue_no;
        }
        //dd($issueNoArr);

        $datas['overheads']=$coststandardOverhead;
        $datas['dyeingcost']=$dyecost;
        $datas['chemicalcost']=$chemcost;
        $datas['inv_dye_chem_rq_id']=implode(', ',$recipeArr[$rq->prod_batch_id]);
        $datas['issue_no']=implode(', ',$issueNoArr[$isu->prod_batch_id]);

       //dd($rows->org_ship_date);die;
        
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(7, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(5);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        $image_file ='images/logo/'.$rows->logo;
        $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(12);
        $pdf->SetFont('helvetica', 'N', 8);
        //$pdf->Text(115, 14, $rows->company_address);
        $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        $pdf->SetY(16);
        $pdf->SetFont('helvetica', 'N', 8);
        $view= \View::make('Defult.Report.FabricProduction.ProdFinishQcBatchCostingPdf',['rows'=>$rows,'datas'=>$datas]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/ProdFinishQcBatchCostingPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function searchBatch(){
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');

        $rows=$this->prodbatch
        ->join('companies',function($join){
            $join->on('companies.id','=','prod_batches.company_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
         ->leftJoin('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
        ->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->when(request('batch_date_from'), function ($q) {
            return $q->where('prod_batches.batch_date', '>=',request('batch_date_from', 0));
        })
        ->when(request('batch_date_to'), function ($q) {
            return $q->where('prod_batches.batch_date', '<=',request('batch_date_to', 0));
        })
        ->when(request('batch_no'), function ($q) {
        return $q->where('prod_batches.batch_no', '=',request('batch_no', 0));
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('prod_batches.company_id', '=',request('company_id', 0));
        })
        ->when(request('batch_for'), function ($q) {
        return $q->where('prod_batches.batch_for', '=',request('batch_for', 0));
        })
        ->orderBy('prod_batches.id','desc')
        ->get([
            'prod_batches.*',
            'companies.code as company_code',
            'colors.name as color_name',
            'batch_colors.name as batch_color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'asset_acquisitions.brand',
            'asset_acquisitions.prod_capacity',
            'colorranges.name as color_range_name',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batchfor=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            return $rows;
        });
        echo json_encode($rows);

    }

    public function getSoDyeingDtl(){
        $prod_batch_id=request('prod_batch_id', 0);

        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');

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

        $rows=collect(
            \DB::select("
            select 
                so_dyeing_refs.id,
                so_dyeing_refs.so_dyeing_id,
                so_dyeings.sales_order_no as dye_sales_order_no,
                so_dyeings.exch_rate,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                style_fabrications.dyeing_type_id,
                budget_fabrics.gsm_weight,
            
                po_dyeing_service_item_qties.fabric_color_id,
                po_dyeing_service_item_qties.budget_fabric_prod_con_id,
                po_dyeing_service_item_qties.colorrange_id,
                po_dyeing_service_item_qties.dia,
                po_dyeing_service_item_qties.measurment,
                po_dyeing_service_item_qties.qty,
                po_dyeing_service_item_qties.pcs_qty,
                po_dyeing_service_item_qties.rate,
                po_dyeing_service_item_qties.amount,
            
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.gmtspart_id as c_gmtspart_id,
                so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.colorrange_id as c_colorrange_id,
                so_dyeing_items.dia as c_dia,
                so_dyeing_items.measurment as c_measurment,
                so_dyeing_items.qty as c_qty,
                so_dyeing_items.rate as c_rate,
                so_dyeing_items.amount as c_amount,
                so_dyeing_items.dyeing_type_id as c_dyeing_type_id,
                so_dyeing_items.gmt_style_ref,
                so_dyeing_items.gmt_sale_order_no,
            
                styles.style_ref,
                sales_orders.sale_order_no,
                buyers.name as buyer_name,
                gmt_buyer.name as gmt_buyer_name,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name
            from prod_batches 
            inner join prod_batch_rolls on prod_batch_rolls.prod_batch_id = prod_batches.id 
            inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id 
            inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id = so_dyeing_fabric_rcv_items.id 
            inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id 
            inner join so_dyeings on so_dyeing_refs.so_dyeing_id = so_dyeings.id 
            left join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id 
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id 
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id 
            left join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id 
            and po_dyeing_service_items.deleted_at is null 
            left join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id 
            left join jobs on jobs.id = sales_orders.job_id 
            left join styles on styles.id = jobs.style_id 
            left join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id 
            left join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id 
            left join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id 
            left join so_dyeing_items on so_dyeing_refs.id = so_dyeing_items.so_dyeing_ref_id 
            left join autoyarns on autoyarns.id = so_dyeing_items.autoyarn_id 
            left join colorranges on colorranges.id = so_dyeing_items.colorrange_id 
            left join buyers on buyers.id = so_dyeing_items.gmt_buyer 
            left join buyers gmt_buyer on gmt_buyer.id = so_dyeing_items.gmt_buyer 
            left join uoms on uoms.id = style_fabrications.uom_id 
            left join uoms so_uoms on so_uoms.id = so_dyeing_items.uom_id 
            where prod_batches.id = ?
            and prod_batches.deleted_at is null 
            group by 
                so_dyeing_refs.id, 
                so_dyeing_refs.so_dyeing_id, 
                so_dyeings.sales_order_no,
                so_dyeings.exch_rate,
                style_fabrications.autoyarn_id, 
                style_fabrications.fabric_look_id, 
                style_fabrications.fabric_shape_id, 
                style_fabrications.gmtspart_id, 
                style_fabrications.dyeing_type_id, 
                budget_fabrics.gsm_weight, 
                po_dyeing_service_item_qties.fabric_color_id, 
                po_dyeing_service_item_qties.budget_fabric_prod_con_id, 
                po_dyeing_service_item_qties.colorrange_id, 
                po_dyeing_service_item_qties.dia, 
                po_dyeing_service_item_qties.measurment, 
                po_dyeing_service_item_qties.qty, 
                po_dyeing_service_item_qties.pcs_qty, 
                po_dyeing_service_item_qties.rate, 
                po_dyeing_service_item_qties.amount, 
                so_dyeing_items.autoyarn_id, 
                so_dyeing_items.fabric_look_id, 
                so_dyeing_items.fabric_shape_id, 
                so_dyeing_items.gmtspart_id, 
                so_dyeing_items.gsm_weight, 
                so_dyeing_items.fabric_color_id, 
                so_dyeing_items.colorrange_id, 
                so_dyeing_items.dia, 
                so_dyeing_items.measurment, 
                so_dyeing_items.qty, 
                so_dyeing_items.rate, 
                so_dyeing_items.amount, 
                so_dyeing_items.dyeing_type_id, 
                so_dyeing_items.gmt_style_ref, 
                so_dyeing_items.gmt_sale_order_no, 
                styles.style_ref, 
                sales_orders.sale_order_no, 
                buyers.name, 
                gmt_buyer.name, 
                uoms.code, 
                so_uoms.code
        ",[$prod_batch_id]))
    
        ->map(function($rows)use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$dyetype,$fabricDescriptionArr){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
        
            $rows->construction_name=$rows->autoyarn_id?$fabricDescriptionArr[$rows->autoyarn_id]:$fabricDescriptionArr[$rows->c_autoyarn_id];
        
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
            $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
        
            $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:$color[$rows->c_fabric_color_id];
            $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];
        
            $rows->qty=$rows->qty?$rows->qty:$rows->c_qty;
            $rows->pcs_qty=$rows->pcs_qty;
            $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
            $rows->amount=$rows->amount?$rows->amount:$rows->c_amount;
            $rows->dia=$rows->dia?$rows->dia:$rows->c_dia;
            $rows->measurment=$rows->measurment?$rows->measurment:$rows->c_measurment;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            $rows->uom_name=$rows->uom_name?$rows->uom_name:$rows->so_uom_name;
            $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:$dyetype[$rows->c_dyeing_type_id];
            $rows->qty=number_format($rows->qty,2,'.',',');
            $rows->pcs_qty=number_format($rows->pcs_qty,0,'.',',');
            $rows->amount=number_format($rows->amount,2,'.',','); 
            return $rows;
        });

        echo json_encode($rows);
        // dd($rows);die;
    }

}