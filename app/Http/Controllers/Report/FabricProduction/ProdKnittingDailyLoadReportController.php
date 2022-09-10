<?php
namespace App\Http\Controllers\Report\FabricProduction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceItemQtyRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use Illuminate\Support\Carbon;

class ProdKnittingDailyLoadReportController extends Controller {
    private $prodknit;
    private $prodknititem;
    private $assetquantitycost;
    private $plknititem;
    private $plknit;
    private $poknitserviceitemqty;
    private $poknitservice;
    private $autoyarn;
    private $gmtspart;
    private $buyer;
    private $company;
    public function __construct(
        ProdKnitRepository $prodknit, 
        ProdKnitItemRepository $prodknititem, 
        AssetQuantityCostRepository $assetquantitycost,
        PlKnitItemRepository $plknititem,
        PlKnitRepository $plknit,
        PoKnitServiceItemQtyRepository $poknitserviceitemqty,
        PoKnitServiceRepository $poknitservice,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        BuyerRepository $buyer,
        CompanyRepository $company,
        SupplierRepository $supplier
        ) 
    {
        $this->prodknit = $prodknit;
        $this->prodknititem = $prodknititem;
        $this->assetquantitycost = $assetquantitycost;
        $this->plknititem = $plknititem;
        $this->plknit = $plknit;
        $this->poknitserviceitemqty = $poknitserviceitemqty;
        $this->poknitservice = $poknitservice;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->middleware('auth');
      //$this->middleware('permission:view.prodgmtcartonqtyreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
        $date_to=date('Y-m-d');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.FabricProduction.ProdKnittingDailyLoadReport',['supplier'=>$supplier,'date_to'=>$date_to]);
    }

    public function reportData(){
        //$date_from =request('date_from',0);
        $date_to=request('date_to',0);
        $supplier_id=request('supplier_id',0);
        $supplierId='';
        if($supplier_id){
        $supplierId= ' and prod_knits.supplier_id= '.$supplier_id;
        }
        else{
        $supplierId= '';
        }

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
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

        $reason=array_prepend(config('bprs.reason'),'','');

        $rows=collect(
            \DB::select("
                select
                asset_quantity_costs.id,
                asset_quantity_costs.custom_no as machine_no,
                asset_acquisitions.brand,
                asset_acquisitions.origin,
                asset_acquisitions.asset_group,
                asset_acquisitions.prod_capacity,
                knit.prod_knit_qty,
                knit.knit_charge_usd,
                knit.knit_charge_bdt,
                knit.prod_date,
                breakdown.function_at,
                breakdown.breakdown_at,
                breakdown.reason_id,
                breakdown.remarks
                
                from
                asset_acquisitions
                join asset_quantity_costs on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
                left join(
                    select
                    asset_breakdowns.asset_quantity_cost_id,
                    asset_quantity_costs.custom_no as machine_no,
                    asset_acquisitions.brand,
                    asset_acquisitions.prod_capacity,
                    asset_breakdowns.breakdown_at,
                    asset_breakdowns.reason_id,
                    asset_breakdowns.remarks,
                    asset_breakdowns.function_at
                    from
                    asset_acquisitions
                    join asset_quantity_costs on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
                    join asset_breakdowns on asset_breakdowns.asset_quantity_cost_id=asset_quantity_costs.id
                    where
                    asset_acquisitions.production_area_id=10
                    and asset_breakdowns.function_at is null
                    order by asset_quantity_costs.id
                ) breakdown on breakdown.asset_quantity_cost_id=asset_quantity_costs.id
                left join (
                    select
                    n.asset_quantity_cost_id,
                    n.prod_date,
                    sum(n.prod_knit_qty) as prod_knit_qty,
                    sum(n.knit_charge_usd) as knit_charge_usd,
                    sum(n.knit_charge_bdt) as knit_charge_bdt
                    from(
                    select
                        prod_knit_items.asset_quantity_cost_id,
                        prod_knits.prod_date,
                        sum(prod_knit_item_rolls.roll_weight) as prod_knit_qty,
                        inhprods.knit_charge_usd,
                        inhprods.knit_charge_bdt
                        from
                        prod_knits
                        join prod_knit_items on prod_knit_items.prod_knit_id=prod_knits.id
                        join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id=prod_knit_items.id
                        join asset_quantity_costs on asset_quantity_costs.id=prod_knit_items.asset_quantity_cost_id
                        left join(
                        select 
                        m.prod_knit_item_id,
                        (m.rate*m.prod_kni_qty) as knit_charge_usd,
                        (m.exch_rate*m.rate*m.prod_kni_qty) as knit_charge_bdt
                        from(
                            select
                                prod_knit_items.id as prod_knit_item_id,
                                so_knits.exch_rate,
                                sum(prod_knit_item_rolls.roll_weight) as prod_kni_qty,
                                case
                                    when so_knit_items.rate is null then po_knit_service_item_qties.rate
                                    else so_knit_items.rate
                                    end as rate
                                from 
                                prod_knit_items
                                join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id=prod_knit_items.id
                                join prod_knits on prod_knits.id=prod_knit_items.prod_knit_id
                                join pl_knit_items on prod_knit_items.pl_knit_item_id=pl_knit_items.id
                                join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
                                join so_knit_refs on pl_knit_items.so_knit_ref_id=so_knit_refs.id
                                left join so_knit_po_items on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
                                left join  so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
                                join  so_knits on so_knits.id=so_knit_refs.so_knit_id
                                left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
                                where prod_knits.basis_id=1
                                $supplierId
                                and prod_knits.prod_date >= '".$date_to."'
                                and prod_knits.prod_date <= '".$date_to."'
                                group by
                                prod_knit_items.id,
                                so_knits.exch_rate,so_knit_items.rate,po_knit_service_item_qties.rate
                            )m
                        )inhprods on inhprods.prod_knit_item_id=prod_knit_items.id
                        where prod_knit_items.deleted_at is null
                        and prod_knits.prod_date >= '".$date_to."'
                        and prod_knits.prod_date <= '".$date_to."'
                        $supplierId
                        and prod_knits.basis_id=1
                        group by
                        prod_knit_items.asset_quantity_cost_id,
                        prod_knits.prod_date,inhprods.knit_charge_usd,
                        inhprods.knit_charge_bdt
                        )n
                        group by 
                        n.asset_quantity_cost_id,
                        n.prod_date
                )knit on knit.asset_quantity_cost_id=asset_quantity_costs.id
                where asset_acquisitions.production_area_id=10
                and asset_acquisitions.deleted_at is null
                and asset_quantity_costs.deleted_at is null
            order by asset_quantity_costs.id
            ")
        )
        ->map(function($rows) use($reason){
            //$loadTime=Carbon::parse($rows->loaded_at);
            $now=Carbon::now();
            //$rows->running_hour=$now->diffInHours($loadTime);
            $idleTime=Carbon::parse($rows->breakdown_at);
            $rows->idle_hour=$now->diffInHours($idleTime);

            $unused_prod_capacity=$rows->prod_capacity-$rows->prod_knit_qty;
            $rows->unused_prod_capacity=number_format($unused_prod_capacity,2);
            $rows->prod_capacity=number_format($rows->prod_capacity,2);
            $rows->knit_charge_usd=number_format($rows->knit_charge_usd,2);
            $rows->knit_charge_bdt=number_format($rows->knit_charge_bdt,2);
            $rows->prod_knit_qty=number_format($rows->prod_knit_qty,2);
            $rows->prod_date=$rows->prod_date?date('d-M-Y',strtotime($rows->prod_date)):'--';
            $rows->idle_date=$rows->breakdown_at?date('d-M-Y',strtotime($rows->breakdown_at)):'--';
            $rows->idle_time=$rows->breakdown_at?date('h:i A',strtotime($rows->breakdown_at)):'--';
            $rows->reason=$rows->reason_id?$reason[$rows->reason_id]:'--';
            return $rows;
        });

        echo json_encode($rows);
    }

    /* 
    
    select
                asset_quantity_costs.id,
                asset_quantity_costs.custom_no as machine_no,
                asset_acquisitions.brand,
                asset_acquisitions.prod_capacity,
                knit.prod_knit_qty,
                knit.prod_date,
                breakdown.function_at,
                breakdown.breakdown_at,
                breakdown.reason_id,
                breakdown.remarks
                
                from
                asset_acquisitions
                join asset_quantity_costs on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
                left join(
                    select
                    asset_breakdowns.asset_quantity_cost_id,
                    asset_quantity_costs.custom_no as machine_no,
                    asset_acquisitions.brand,
                    asset_acquisitions.prod_capacity,
                    asset_breakdowns.breakdown_at,
                    asset_breakdowns.reason_id,
                    asset_breakdowns.remarks,
                    asset_breakdowns.function_at
                    from
                    asset_acquisitions
                    join asset_quantity_costs on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
                    join asset_breakdowns on asset_breakdowns.asset_quantity_cost_id=asset_quantity_costs.id
                    where
                    asset_acquisitions.production_area_id=10
                    and asset_breakdowns.function_at is null
                    order by asset_quantity_costs.id
                ) breakdown on breakdown.asset_quantity_cost_id=asset_quantity_costs.id
                left join (
                    select
                    prod_knit_items.asset_quantity_cost_id,
                    prod_knits.prod_date,
                    sum(prod_knit_item_rolls.roll_weight) as prod_knit_qty
                    from
                    prod_knits
                    join prod_knit_items on prod_knit_items.prod_knit_id=prod_knits.id
                    join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id=prod_knit_items.id
                    join asset_quantity_costs on asset_quantity_costs.id=prod_knit_items.asset_quantity_cost_id
                    where prod_knit_items.deleted_at is null
                    and prod_knits.prod_date >= '".$date_to."'
                    and prod_knits.prod_date <= '".$date_to."'
                    $supplierId
                    group by
                    prod_knit_items.asset_quantity_cost_id,
                    prod_knits.prod_date
                )knit on knit.asset_quantity_cost_id=asset_quantity_costs.id
                where asset_acquisitions.production_area_id=10
                and asset_acquisitions.deleted_at is null
                and asset_quantity_costs.deleted_at is null
            --group by
               -- asset_quantity_costs.id,
               -- asset_quantity_costs.custom_no,
               -- asset_acquisitions.brand,
               -- asset_acquisitions.prod_capacity,
              --  knit.prod_knit_qty,
              --  knit.prod_date,
              --  breakdown.function_at,
              --  breakdown.breakdown_at,
              --  breakdown.reason_id,
             --   breakdown.remarks
            order by asset_quantity_costs.id

    */

}