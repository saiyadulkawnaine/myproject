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
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use Illuminate\Support\Carbon;


class ProdDyeFinDailyLoadReportController extends Controller {
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
    private $invdyechemisurq;
    private $productionprocess;

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
        SupplierRepository $supplier,
        ProductionProcessRepository $productionprocess ,
        InvDyeChemIsuRqRepository $invdyechemisurq
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
        $this->productionprocess = $productionprocess;

        $this->middleware('auth');
      //$this->middleware('permission:view.prodgmtcartonqtyreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
        $date_to=date('Y-m-d');
        $date_from=date('Y-m-d');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $process_name=array_prepend(array_pluck($this->productionprocess->whereIn('production_area_id',[20,30])->get(),'process_name','id'),'','');
        return Template::loadView('Report.FabricProduction.ProdDyeFinDailyLoadReport',['supplier'=>$supplier,'date_from'=>$date_from,'date_to'=>$date_to,'process_name'=>$process_name]);
    }

    public function reportData(){

        $date_from =request('date_from',0);
        $date_to=request('date_to',0);
        $production_process_id=request('production_process_id', 0);

        $prodprocess=null;
        if ($production_process_id) {
            $prodprocess="and prod_batch_finish_progs.production_process_id=$production_process_id";
        }

        $process_name=array_prepend(array_pluck($this->productionprocess->whereIn('production_area_id',[20,30])->get(),'process_name','id'),'','');


        $rows=collect(
            \DB::select("
            select
            asset_quantity_costs.custom_no as machine_no,
            asset_acquisitions.brand,
            asset_acquisitions.origin,
            asset_acquisitions.prod_capacity,
            asset_acquisitions.asset_group,
            batches.production_process_id,
            batches.posting_date,

            breakdown.breakdown_at,
            breakdown.reason_id,
            breakdown.remarks,
            breakdown.function_at,
            batches.roll_qty
            from
            asset_acquisitions
            join asset_quantity_costs on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
            left join (
                select
                n.production_process_id,
                n.machine_id,
                n.posting_date,
                sum(n.roll_qty) as roll_qty
                from
                (select
                    prod_batch_finish_progs.production_process_id,
                    prod_batch_finish_progs.machine_id,
                    prod_batch_finish_progs.posting_date,
                    fin_prog_roll.roll_qty as roll_qty
                    from
                    prod_batch_finish_progs
                    join prod_batches on prod_batches.id=prod_batch_finish_progs.prod_batch_id
                    left join (
                        select
                        prod_batch_finish_prog_rolls.prod_batch_finish_prog_id,
                        sum(prod_batch_rolls.qty) as roll_qty
                        from
                        prod_batch_finish_prog_rolls
                        join prod_batch_rolls on prod_batch_rolls.id=prod_batch_finish_prog_rolls.prod_batch_roll_id
                        group by
                        prod_batch_finish_prog_rolls.prod_batch_finish_prog_id
                    )fin_prog_roll on fin_prog_roll.prod_batch_finish_prog_id=prod_batch_finish_progs.id
                    where 
                    prod_batch_finish_progs.posting_date >=  '".$date_from."'
                    and prod_batch_finish_progs.posting_date <= '".$date_to."'
                    $prodprocess
                    and prod_batch_finish_progs.deleted_at is null
                )n
                group by
                n.production_process_id,
                n.machine_id,
                n.posting_date
            ) batches on batches.machine_id=asset_quantity_costs.id
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
                asset_acquisitions.production_area_id in (30,35)
                and asset_breakdowns.function_at is null
                order by asset_quantity_costs.id
            ) breakdown on breakdown.asset_quantity_cost_id=asset_quantity_costs.id
            where asset_acquisitions.production_area_id in (30,35)
            and asset_acquisitions.deleted_at is null
            and asset_quantity_costs.deleted_at is null
        
            order by 
            batches.production_process_id,
            batches.posting_date,
            asset_quantity_costs.custom_no
            ")
        )
        ->map(function($rows) use($process_name){
            $rows->production_process_id=$process_name[$rows->production_process_id];
            $now=Carbon::now();
            $idleTime=Carbon::parse($rows->breakdown_at);
            $rows->idle_hour=$now->diffInHours($idleTime);
            $unused_prod_capacity=$rows->prod_capacity-$rows->roll_qty;
            $rows->unused_prod_capacity=number_format($unused_prod_capacity,2);
            $rows->roll_qty=number_format($rows->roll_qty,2);
            $rows->posting_date=$rows->posting_date?date('d-M-Y',strtotime($rows->posting_date)):'--';
            $rows->idle_date=$rows->breakdown_at?date('d-M-Y',strtotime($rows->breakdown_at)):'--';
            $rows->idle_time=$rows->breakdown_at?date('h:i A',strtotime($rows->breakdown_at)):'--';

            return $rows;
        });

        echo json_encode($rows);
    }

}