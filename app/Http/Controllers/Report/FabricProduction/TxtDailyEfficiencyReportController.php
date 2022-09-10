<?php
namespace App\Http\Controllers\Report\FabricProduction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use Illuminate\Support\Carbon;

class TxtDailyEfficiencyReportController extends Controller
{
    private $subsection;
    private $wstudylinesetup;
    private $prodgmtsewing;
  private $buyer;
  private $company;
  private $supplier;
  private $location;
  public function __construct(
    SubsectionRepository $subsection,
    WstudyLineSetupRepository $wstudylinesetup,
    ProdGmtSewingRepository $prodgmtsewing,
    CompanyRepository $company, 
    LocationRepository $location, 
    SupplierRepository $supplier, 
    BuyerRepository $buyer
  )
    {
      $this->subsection                = $subsection;
      $this->wstudylinesetup           = $wstudylinesetup;
      $this->prodgmtsewing             = $prodgmtsewing;
      $this->company = $company;
      $this->buyer = $buyer;
      $this->location = $location;
      $this->supplier = $supplier;
      $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtcartonqtyreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
      $company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->orderBy('name')->get(),'name','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
      $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');
      $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
      $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
      $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
      $ordersource=array_prepend(config('bprs.ordersource'),'-Select-','');
      return Template::loadView('Report.FabricProduction.TxtDailyEfficiencyReport',[
        'location'=> $location,
        'productionsource'=> $productionsource,
        'shiftname'=> $shiftname,
        'ordersource'=>$ordersource,
        'company'=>$company,
        'supplier'=>$supplier,
        'buyer'=>$buyer
      ]);
    }
  public function reportData() {
      $date_from=request('date_to',0);
      $date_to =request('date_to',0);
      $company_id=request('company_id',0);
      $company=array_prepend(array_pluck($this->company->orderBy('name')->get(),'code','id'),'-Select-','');

      $machines = \DB::select("
      select
      asset_quantity_costs.*,
      asset_acquisitions.prod_capacity,
      asset_acquisitions.name as asset_name,
      asset_acquisitions.origin,
      asset_acquisitions.brand,
      asset_acquisitions.production_area_id, 
      asset_acquisitions.asset_group,
      asset_technical_features.dia_width,
      asset_technical_features.gauge,
      asset_technical_features.extra_cylinder,
      asset_technical_features.no_of_feeder
      from
      asset_quantity_costs
      join asset_acquisitions on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
      left join asset_technical_features on asset_technical_features.asset_acquisition_id=asset_acquisitions.id
        
      ");
      $machinearr=[];
      foreach($machines as $machine)
      {
          $machinearr[$machine->production_area_id][$machine->id]=$machine->prod_capacity;
      }

      $knitings = collect(\DB::select("
        select 
        pl_knit_items.machine_id,
        pl_knit_item_qties.pl_date,
        sum(pl_knit_items.hour) as hour,
        sum(pl_knit_items.capacity) as day_target,
        sum(pl_knit_item_qties.qty) as program_qty,
        sum(pl_knit_item_qties.adjusted_minute) as adjusted_minute,
        prods.prod_qty,
        prods.amount,

        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        asset_acquisitions.prod_capacity as mc_capacity,
        asset_quantity_costs.custom_no


        from 
        pl_knits
        join pl_knit_items on pl_knit_items.pl_knit_id=pl_knits.id
        join pl_knit_item_qties on pl_knit_item_qties.pl_knit_item_id=pl_knit_items.id

        left join (
        select 
        m.machine_id,
        m.prod_date,
        sum (m.qty) as prod_qty,
        sum (m.amount) as amount
        from
        (
        select
        prod_knits.prod_date,
        prod_knit_items.asset_quantity_cost_id as machine_id,
        prod_knit_item_rolls.roll_weight as qty,
        CASE
        WHEN so_knit_items.rate is not null and so_knits.currency_id=1
        THEN prod_knit_item_rolls.roll_weight*so_knit_items.rate*83
        WHEN so_knit_items.rate is not null and so_knits.currency_id=2
        THEN prod_knit_item_rolls.roll_weight*so_knit_items.rate

        WHEN po_knit_service_item_qties.rate is not null and po_knit_services.currency_id=1
        THEN prod_knit_item_rolls.roll_weight*po_knit_service_item_qties.rate*83
        WHEN po_knit_service_item_qties.rate is not null and po_knit_services.currency_id=2
        THEN prod_knit_item_rolls.roll_weight*po_knit_service_item_qties.rate
        ELSE 0 
        END as amount
        from
        prod_knits
        join prod_knit_items on prod_knit_items.prod_knit_id=prod_knits.id
        join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id=prod_knit_items.id

        join pl_knit_items on pl_knit_items.id=prod_knit_items.pl_knit_item_id
        join pl_knits on pl_knit_items.pl_knit_id=pl_knits.id

        join so_knit_refs on so_knit_refs.id = pl_knit_items.so_knit_ref_id
        join so_knits on so_knits.id = so_knit_refs.so_knit_id
        left join so_knit_items on so_knit_items.so_knit_ref_id = so_knit_refs.id
        left join so_knit_po_items on so_knit_po_items.so_knit_ref_id = so_knit_refs.id
        left join po_knit_service_item_qties on po_knit_service_item_qties.id = so_knit_po_items.po_knit_service_item_qty_id
        left join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
        left join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id
        where 
        prod_knits.prod_date>='".$date_from."'
        and prod_knits.prod_date<='".$date_to."'
        ) m 
        group by
        m.machine_id,
        m.prod_date
        ) prods on prods.machine_id=pl_knit_items.machine_id and prods.prod_date=pl_knit_item_qties.pl_date
        left join asset_quantity_costs on asset_quantity_costs.id=pl_knit_items.machine_id
        left join asset_acquisitions on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id

        where
        pl_knit_item_qties.pl_date>='".$date_from."'
        and pl_knit_item_qties.pl_date<='".$date_to."'
        and pl_knit_items.machine_id is not null
        group by
        pl_knit_items.machine_id,
        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        asset_acquisitions.prod_capacity,
        asset_quantity_costs.custom_no,
        pl_knit_item_qties.pl_date,
        prods.prod_qty,
        prods.amount
        order by
        pl_knit_items.machine_id
      "))
      ->map(function($knitings){
        $knitings->smv=($knitings->hour*60)/$knitings->day_target;
        $knitings->used_mint=(24*60) - $knitings->adjusted_minute;
        //$knitings->produced_mint=($knitings->hour*60) - $knitings->adjusted_minute;
        $knitings->produced_mint=$knitings->prod_qty*$knitings->smv;
        $knitings->effi_per=($knitings->produced_mint/$knitings->used_mint)*100;
        $knitings->achv_per=($knitings->prod_qty/$knitings->day_target)*100;
        return $knitings;
      });
      $data=[];
      foreach($knitings as $kniting)
      {
          $data['knit']['no_of_mc']=isset($data['knit']['no_of_mc'])?$data['knit']['no_of_mc']+=1:$data['knit']['no_of_mc']=1;
          $data['knit']['mc_capacity']=isset($data['knit']['mc_capacity'])?$data['knit']['mc_capacity']+=$kniting->mc_capacity:$data['knit']['mc_capacity']=$kniting->mc_capacity;

          $data['knit']['day_target']=isset($data['knit']['day_target'])?$data['knit']['day_target']+=$kniting->day_target:$data['knit']['day_target']=$kniting->day_target;

          $data['knit']['prod_qty']=isset($data['knit']['prod_qty'])?$data['knit']['prod_qty']+=$kniting->prod_qty:$data['knit']['prod_qty']=$kniting->prod_qty;
          $data['knit']['amount']=isset($data['knit']['amount'])?$data['knit']['amount']+=$kniting->amount:$data['knit']['amount']=$kniting->amount;

          $data['knit']['used_mint']=isset($data['knit']['used_mint'])?$data['knit']['used_mint']+=$kniting->used_mint:$data['knit']['used_mint']=$kniting->used_mint;
          
          $data['knit']['produced_mint']=isset($data['knit']['produced_mint'])?$data['knit']['produced_mint']+=$kniting->produced_mint:$data['knit']['produced_mint']=$kniting->produced_mint;
          $data['knit']['effi_per']=($data['knit']['produced_mint']/$data['knit']['used_mint'])*100;
          $data['knit']['achv_per']=($data['knit']['prod_qty']/$data['knit']['day_target'])*100;

      }

      $dyeings = collect(\DB::select("
      select
      m.machine_id,
      m.unload_posting_date,
      m.brand,
      m.asset_group,
      m.mc_capacity,
      m.custom_no,
      sum(batch_wgt) as batch_wgt,
      sum(used_mint) as used_mint,
      sum(produced_mint) as produced_mint
      from
      (
      select
      prod_batches.id,
      prod_batches.machine_id,
      prod_batches.unload_posting_date,
      prod_batches.batch_wgt,
      asset_acquisitions.brand,
      asset_acquisitions.asset_group,
      asset_acquisitions.prod_capacity as mc_capacity,
      asset_quantity_costs.custom_no,
      extract( day from (1440* (prod_batches.unloaded_at-prod_batches.loaded_at))) as used_mint,
      (prod_batches.tgt_hour*60) as produced_mint,
      prod_batches.unloaded_at,
      prod_batches.loaded_at
      from
      prod_batches
      left join asset_quantity_costs on asset_quantity_costs.id=prod_batches.machine_id
      left join asset_acquisitions on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
      where prod_batches.unloaded_at is not null
      and prod_batches.unload_posting_date>='".$date_from."'
      and prod_batches.unload_posting_date<='".$date_to."'
      ) m group by
      m.machine_id,
      m.unload_posting_date,
      m.brand,
      m.asset_group,
      m.mc_capacity,
      m.custom_no
      "))
      ->map(function($dyeings){
        //$dyeings->used_mint=24*60;
        //$dyeings->produced_mint=$dyeings->working_minute - $dyeings->adjusted_minute;
        $dyeings->prod_qty=$dyeings->batch_wgt;
        $dyeings->day_target=$dyeings->batch_wgt;
        $dyeings->effi_per=0;
        if($dyeings->used_mint){
          $dyeings->effi_per=($dyeings->produced_mint/$dyeings->used_mint)*100;
        }
        $dyeings->achv_per=($dyeings->prod_qty/$dyeings->day_target)*100;
        $dyeings->amount=0;
        return $dyeings;
      });

      foreach($dyeings as $dyeing)
      {
          $data['dyeing']['no_of_mc']=isset($data['dyeing']['no_of_mc'])?$data['dyeing']['no_of_mc']+=1:$data['dyeing']['no_of_mc']=1;
          $data['dyeing']['mc_capacity']=isset($data['dyeing']['mc_capacity'])?$data['dyeing']['mc_capacity']+=$dyeing->mc_capacity:$data['dyeing']['mc_capacity']=$dyeing->mc_capacity;

          $data['dyeing']['day_target']=isset($data['dyeing']['day_target'])?$data['dyeing']['day_target']+=$dyeing->day_target:$data['dyeing']['day_target']=$dyeing->day_target;

          $data['dyeing']['prod_qty']=isset($data['dyeing']['prod_qty'])?$data['dyeing']['prod_qty']+=$dyeing->prod_qty:$data['dyeing']['prod_qty']=$dyeing->prod_qty;
          $data['dyeing']['amount']=isset($data['dyeing']['amount'])?$data['dyeing']['amount']+=$dyeing->amount:$data['dyeing']['amount']=$dyeing->amount;

          $data['dyeing']['used_mint']=isset($data['dyeing']['used_mint'])?$data['dyeing']['used_mint']+=$dyeing->used_mint:$data['dyeing']['used_mint']=$dyeing->used_mint;
          
          $data['dyeing']['produced_mint']=isset($data['dyeing']['produced_mint'])?$data['dyeing']['produced_mint']+=$dyeing->produced_mint:$data['dyeing']['produced_mint']=$dyeing->produced_mint;
          $data['dyeing']['effi_per']=0;
          if($data['dyeing']['used_mint']){
            $data['dyeing']['effi_per']=($data['dyeing']['produced_mint']/$data['dyeing']['used_mint'])*100;
          }
          $data['dyeing']['achv_per']=($data['dyeing']['prod_qty']/$data['dyeing']['day_target'])*100;

      }

      // =====dyeingfinfab==
      //   select 
      //   prod_finish_mc_setups.machine_id,
      //   prod_finish_mc_dates.target_date as pl_date,
      //   sum(prod_batches.fabric_wgt) as day_target,
      //   sum(prod_finish_mc_parameters.working_minute) as working_minute,
      //   sum(prod_finish_mc_dates.adjusted_minute) as adjusted_minute,
      //   prod.prod_qty,
      //   asset_acquisitions.brand,
      //   asset_acquisitions.asset_group,
      //   asset_acquisitions.prod_capacity as mc_capacity,
      //   asset_quantity_costs.custom_no

      //   from 
      //   prod_finish_mc_setups
      //   join prod_finish_mc_dates on prod_finish_mc_dates.prod_finish_mc_setup_id=prod_finish_mc_setups.id
      //   join prod_finish_mc_parameters on prod_finish_mc_parameters.prod_finish_mc_date_id=prod_finish_mc_dates.id
      //   join prod_batches on prod_finish_mc_parameters.prod_batch_id=prod_batches.id
      //   left join asset_quantity_costs on asset_quantity_costs.id=prod_finish_mc_setups.machine_id
      //   left join asset_acquisitions on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
      //   left join (
      //   select
      //   prod_batch_finish_qcs.machine_id,
      //   prod_batch_finish_qcs.posting_date, 
      //   sum(prod_batch_rolls.qty) as prod_qty
      //   from
      //   prod_batch_finish_qcs
      //   join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_finish_qc_id=prod_batch_finish_qcs.id
      //   join prod_batch_rolls on prod_batch_finish_qc_rolls.prod_batch_roll_id=prod_batch_rolls.id
      //   where
      //   prod_batch_finish_qcs.posting_date>='".$date_from."'
      //   and prod_batch_finish_qcs.posting_date<='".$date_to."'
      //   group by 
      //   prod_batch_finish_qcs.machine_id,
      //   prod_batch_finish_qcs.posting_date
      //   )  prod  on prod.machine_id=prod_finish_mc_setups.machine_id and prod.posting_date=prod_finish_mc_dates.target_date
      //   where
      //   prod_finish_mc_dates.target_date>='".$date_from."'
      //   and prod_finish_mc_dates.target_date<='".$date_to."'
      //   group by 
      //   prod_finish_mc_setups.machine_id,
      //   prod_finish_mc_dates.target_date,
      //   asset_acquisitions.brand,
      //   asset_acquisitions.asset_group,
      //   asset_acquisitions.prod_capacity,
      //   asset_quantity_costs.custom_no,
      //   prod.prod_qty


      $fabfins = collect(\DB::select("
        select 
        prod_finish_mc_setups.machine_id,
        prod_finish_mc_dates.target_date as pl_date,
        sum(prod_batches.fabric_wgt) as day_target,
        sum(prod_finish_mc_parameters.working_minute) as working_minute,
        prod_finish_mc_dates.adjusted_minute,
        prod.prod_qty,
        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        asset_acquisitions.prod_capacity as mc_capacity,
        asset_quantity_costs.custom_no

        from 
        prod_finish_mc_setups
        join prod_finish_mc_dates on prod_finish_mc_dates.prod_finish_mc_setup_id=prod_finish_mc_setups.id
        join prod_finish_mc_parameters on prod_finish_mc_parameters.prod_finish_mc_date_id=prod_finish_mc_dates.id
        join prod_batches on prod_finish_mc_parameters.prod_batch_id=prod_batches.id
        left join asset_quantity_costs on asset_quantity_costs.id=prod_finish_mc_setups.machine_id
        left join asset_acquisitions on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
        left join (
          select
          prod_batch_finish_progs.machine_id,
          prod_batch_finish_progs.posting_date,
          sum(prod_batch_rolls.qty) as prod_qty
          from
          prod_batch_finish_progs
          join prod_batch_finish_prog_rolls on prod_batch_finish_prog_rolls.prod_batch_finish_prog_id=prod_batch_finish_progs.id
          join prod_batch_rolls on prod_batch_finish_prog_rolls.prod_batch_roll_id=prod_batch_rolls.id
          where
          prod_batch_finish_progs.posting_date>='".$date_from."'
          and prod_batch_finish_progs.posting_date<='".$date_to."'
          group by 
          prod_batch_finish_progs.machine_id,
          prod_batch_finish_progs.posting_date
        )  prod  on prod.machine_id=prod_finish_mc_setups.machine_id and prod.posting_date=prod_finish_mc_dates.target_date
        where
        prod_finish_mc_dates.target_date>='".$date_from."'
        and prod_finish_mc_dates.target_date<='".$date_to."'
        group by 
        prod_finish_mc_setups.machine_id,
        prod_finish_mc_dates.target_date,
        prod_finish_mc_dates.adjusted_minute,
        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        asset_acquisitions.prod_capacity,
        asset_quantity_costs.custom_no,
        prod.prod_qty
      "))
      ->map(function($fabfins){
        $fabfins->smv=$fabfins->working_minute/$fabfins->day_target;
        $fabfins->used_mint=(24*60)- $fabfins->adjusted_minute;
        $fabfins->produced_mint=$fabfins->prod_qty*$fabfins->smv;
        $fabfins->effi_per=($fabfins->produced_mint/$fabfins->used_mint)*100;
        $fabfins->achv_per=($fabfins->prod_qty/$fabfins->day_target)*100;
        $fabfins->amount=0;
        return $fabfins;
      });

      foreach($fabfins as $fabfin)
      {
          $data['fabfin']['no_of_mc']=isset($data['fabfin']['no_of_mc'])?$data['fabfin']['no_of_mc']+=1:$data['fabfin']['no_of_mc']=1;
          $data['fabfin']['mc_capacity']=isset($data['fabfin']['mc_capacity'])?$data['fabfin']['mc_capacity']+=$fabfin->mc_capacity:$data['fabfin']['mc_capacity']=$fabfin->mc_capacity;

          $data['fabfin']['day_target']=isset($data['fabfin']['day_target'])?$data['fabfin']['day_target']+=$fabfin->day_target:$data['fabfin']['day_target']=$fabfin->day_target;

          $data['fabfin']['prod_qty']=isset($data['fabfin']['prod_qty'])?$data['fabfin']['prod_qty']+=$fabfin->prod_qty:$data['fabfin']['prod_qty']=$fabfin->prod_qty;
          $data['fabfin']['amount']=isset($data['fabfin']['amount'])?$data['fabfin']['amount']+=$fabfin->amount:$data['fabfin']['amount']=$fabfin->amount;

          $data['fabfin']['used_mint']=isset($data['fabfin']['used_mint'])?$data['fabfin']['used_mint']+=$fabfin->used_mint:$data['fabfin']['used_mint']=$fabfin->used_mint;
          
          $data['fabfin']['produced_mint']=isset($data['fabfin']['produced_mint'])?$data['fabfin']['produced_mint']+=$fabfin->produced_mint:$data['fabfin']['produced_mint']=$fabfin->produced_mint;
          $data['fabfin']['effi_per']=($data['fabfin']['produced_mint']/$data['fabfin']['used_mint'])*100;
          $data['fabfin']['achv_per']=($data['fabfin']['prod_qty']/$data['fabfin']['day_target'])*100;

      }

      /*$aops = collect(\DB::select("
        select 
        prod_aop_mc_setups.machine_id,
        prod_aop_mc_dates.target_date as pl_date,
        sum(prod_aop_mc_parameters.production_per_hr) as production_per_hr,
        sum(prod_aop_mc_parameters.tgt_qty) as day_target,
        prod_aop_mc_dates.adjusted_minute,
        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        asset_acquisitions.prod_capacity as mc_capacity,
        asset_quantity_costs.custom_no,
        prod.prod_qty
        from 
        prod_aop_mc_setups
        join prod_aop_mc_dates on prod_aop_mc_dates.prod_aop_mc_setup_id=prod_aop_mc_setups.id
        join prod_aop_mc_parameters on prod_aop_mc_parameters.prod_aop_mc_date_id=prod_aop_mc_dates.id
        left join asset_quantity_costs on asset_quantity_costs.id=prod_aop_mc_setups.machine_id
        left join asset_acquisitions on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
        left join (
        select
        prod_batch_finish_progs.machine_id,
        prod_batch_finish_progs.posting_date, 
        sum(prod_aop_batch_rolls.qty) as prod_qty
        from
        prod_batch_finish_progs
        join prod_batch_finish_prog_rolls on prod_batch_finish_prog_rolls.prod_batch_finish_prog_id=prod_batch_finish_progs.id
        join prod_aop_batch_rolls on prod_batch_finish_prog_rolls.prod_aop_batch_roll_id=prod_aop_batch_rolls.id
        join production_processes on production_processes.id=prod_batch_finish_progs.production_process_id
        where
        prod_batch_finish_progs.posting_date>='".$date_from."'
        and prod_batch_finish_progs.posting_date<='".$date_to."'
        and production_processes.production_area_id=25
        group by 
        prod_batch_finish_progs.machine_id,
        prod_batch_finish_progs.posting_date
        )  prod  on prod.machine_id=prod_aop_mc_setups.machine_id and prod.posting_date=prod_aop_mc_dates.target_date
        where
        prod_aop_mc_dates.target_date>='".$date_from."'
        and prod_aop_mc_dates.target_date<='".$date_to."'
        group by 
        prod_aop_mc_setups.machine_id,
        prod_aop_mc_dates.target_date,
        prod_aop_mc_dates.adjusted_minute,
        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        asset_acquisitions.prod_capacity,
        asset_quantity_costs.custom_no,
        prod.prod_qty
      "))
      ->map(function($aops){
        $aops->smv=60/$aops->production_per_hr;
        $aops->used_mint=(24*60) - $aops->adjusted_minute;
        $aops->produced_mint=$aops->prod_qty*$aops->smv;
        $aops->effi_per=($aops->produced_mint/$aops->used_mint)*100;
        $aops->achv_per=0;
        if($aops->day_target){
         $aops->achv_per=($aops->prod_qty/$aops->day_target)*100; 
        }
        
        $aops->amount=0;
        return $aops;
      });*/

      $aops = collect(\DB::select("
        select 
        prod_aop_mc_setups.machine_id,
        prod_aop_mc_dates.target_date as pl_date,
        sum(prod_aop_mc_parameters.production_per_hr) as production_per_hr,
        sum(prod_aop_mc_parameters.tgt_qty) as day_target,
        prod_aop_mc_dates.adjusted_minute,
        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        asset_acquisitions.prod_capacity as mc_capacity,
        asset_quantity_costs.custom_no,
        prod.prod_qty,
        prod.produced_mint
        from 
        prod_aop_mc_setups
        join prod_aop_mc_dates on prod_aop_mc_dates.prod_aop_mc_setup_id=prod_aop_mc_setups.id
        join prod_aop_mc_parameters on prod_aop_mc_parameters.prod_aop_mc_date_id=prod_aop_mc_dates.id
        left join asset_quantity_costs on asset_quantity_costs.id=prod_aop_mc_setups.machine_id
        left join asset_acquisitions on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
        left join (
        select
        m.machine_id,
        m.posting_date,
        sum(m.prod_qty) as prod_qty,
        sum(m.produced_mint) as produced_mint
        from
        (
        select
        prod_batch_finish_progs.prod_aop_batch_id,
        prod_batch_finish_progs.machine_id,
        prod_batch_finish_progs.posting_date,
        prod_aop_mc_parameters.production_per_hr, 
        prod_aop_batch_rolls.qty as prod_qty,
        60/prod_aop_mc_parameters.production_per_hr as smv,
        prod_aop_batch_rolls.qty*(60/prod_aop_mc_parameters.production_per_hr) as produced_mint
        from
        prod_batch_finish_progs
        join prod_batch_finish_prog_rolls on prod_batch_finish_prog_rolls.prod_batch_finish_prog_id=prod_batch_finish_progs.id
        join prod_aop_batch_rolls on prod_batch_finish_prog_rolls.prod_aop_batch_roll_id=prod_aop_batch_rolls.id
        join production_processes on production_processes.id=prod_batch_finish_progs.production_process_id
        join prod_aop_mc_setups on prod_aop_mc_setups.machine_id=prod_batch_finish_progs.machine_id
        join prod_aop_mc_dates on prod_aop_mc_dates.prod_aop_mc_setup_id=prod_aop_mc_setups.id
        and prod_batch_finish_progs.posting_date=prod_aop_mc_dates.target_date
        join prod_aop_mc_parameters on prod_aop_mc_parameters.prod_aop_mc_date_id=prod_aop_mc_dates.id
        and prod_aop_mc_parameters.prod_aop_batch_id=prod_batch_finish_progs.prod_aop_batch_id
        where
        prod_batch_finish_progs.posting_date>='".$date_from."' 
        and prod_batch_finish_progs.posting_date<='".$date_to."' 
        and production_processes.production_area_id=25

        order by prod_batch_finish_progs.prod_aop_batch_id
        ) m group by 
        m.machine_id,
        m.posting_date
        )  prod  on prod.machine_id=prod_aop_mc_setups.machine_id and prod.posting_date=prod_aop_mc_dates.target_date
        where
        prod_aop_mc_dates.target_date>='".$date_from."'
        and prod_aop_mc_dates.target_date<='".$date_to."'
        group by 
        prod_aop_mc_setups.machine_id,
        prod_aop_mc_dates.target_date,
        prod_aop_mc_dates.adjusted_minute,
        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        asset_acquisitions.prod_capacity,
        asset_quantity_costs.custom_no,
        prod.prod_qty,
        prod.produced_mint
      "))
      ->map(function($aops){
        //$aops->smv=60/$aops->production_per_hr;
        $aops->used_mint=(24*60) - $aops->adjusted_minute;
        //$aops->produced_mint=$aops->prod_qty*$aops->smv;
        $aops->effi_per=($aops->produced_mint/$aops->used_mint)*100;
        $aops->achv_per=0;
        if($aops->day_target){
         $aops->achv_per=($aops->prod_qty/$aops->day_target)*100; 
        }
        
        $aops->amount=0;
        return $aops;
      });



      foreach($aops as $aop)
      {
          $data['aop']['no_of_mc']=isset($data['aop']['no_of_mc'])?$data['aop']['no_of_mc']+=1:$data['aop']['no_of_mc']=1;
          $data['aop']['mc_capacity']=isset($data['aop']['mc_capacity'])?$data['aop']['mc_capacity']+=$aop->mc_capacity:$data['aop']['mc_capacity']=$aop->mc_capacity;

          $data['aop']['day_target']=isset($data['aop']['day_target'])?$data['aop']['day_target']+=$aop->day_target:$data['aop']['day_target']=$aop->day_target;

          $data['aop']['prod_qty']=isset($data['aop']['prod_qty'])?$data['aop']['prod_qty']+=$aop->prod_qty:$data['aop']['prod_qty']=$aop->prod_qty;
          $data['aop']['amount']=isset($data['aop']['amount'])?$data['aop']['amount']+=$aop->amount:$data['aop']['amount']=$aop->amount;

          $data['aop']['used_mint']=isset($data['aop']['used_mint'])?$data['aop']['used_mint']+=$aop->used_mint:$data['aop']['used_mint']=$aop->used_mint;
          
          $data['aop']['produced_mint']=isset($data['aop']['produced_mint'])?$data['aop']['produced_mint']+=$aop->produced_mint:$data['aop']['produced_mint']=$aop->produced_mint;
          $data['aop']['effi_per']=($data['aop']['produced_mint']/$data['aop']['used_mint'])*100;
          $data['aop']['achv_per']=0;
          if($data['aop']['day_target']){
            $data['aop']['achv_per']=($data['aop']['prod_qty']/$data['aop']['day_target'])*100;
          }
          

      }

      

      $aopfins = collect(\DB::select("
        select 
        prod_finish_aop_mc_setups.machine_id,
        prod_finish_aop_mc_dates.target_date as pl_date,
        sum(prod_aop_batches.fabric_wgt) as day_target,
        sum(prod_finish_aop_mc_parameters.working_minute) as working_minute,
        prod_finish_aop_mc_dates.adjusted_minute,
        prod.prod_qty,
        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        asset_acquisitions.prod_capacity as mc_capacity,
        asset_quantity_costs.custom_no

        from 
        prod_finish_aop_mc_setups
        join prod_finish_aop_mc_dates on prod_finish_aop_mc_dates.prod_finish_aop_mc_setup_id=prod_finish_aop_mc_setups.id
        join prod_finish_aop_mc_parameters on prod_finish_aop_mc_parameters.prod_finish_aop_mc_date_id=prod_finish_aop_mc_dates.id
        join prod_aop_batches on prod_finish_aop_mc_parameters.prod_aop_batch_id=prod_aop_batches.id
        left join asset_quantity_costs on asset_quantity_costs.id=prod_finish_aop_mc_setups.machine_id
        left join asset_acquisitions on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
        left join (
          select
          prod_batch_finish_progs.machine_id,
          prod_batch_finish_progs.posting_date, 
          sum(prod_aop_batch_rolls.qty) as prod_qty
          from
          prod_batch_finish_progs
          join prod_batch_finish_prog_rolls on prod_batch_finish_prog_rolls.prod_batch_finish_prog_id=prod_batch_finish_progs.id
          join prod_aop_batch_rolls on prod_batch_finish_prog_rolls.prod_aop_batch_roll_id=prod_aop_batch_rolls.id
          where
          prod_batch_finish_progs.posting_date>='".$date_from."'
          and prod_batch_finish_progs.posting_date<='".$date_to."'
          group by 
          prod_batch_finish_progs.machine_id,
          prod_batch_finish_progs.posting_date
        )  prod  on prod.machine_id=prod_finish_aop_mc_setups.machine_id and prod.posting_date=prod_finish_aop_mc_dates.target_date
        where
        prod_finish_aop_mc_dates.target_date>='".$date_from."'
        and prod_finish_aop_mc_dates.target_date<='".$date_to."'
        group by 
        prod_finish_aop_mc_setups.machine_id,
        prod_finish_aop_mc_dates.target_date,
        prod_finish_aop_mc_dates.adjusted_minute,
        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        asset_acquisitions.prod_capacity,
        asset_quantity_costs.custom_no,
        prod.prod_qty
      "))
      ->map(function($aopfins){
        $aopfins->smv=$aopfins->working_minute/$aopfins->day_target;
        $aopfins->used_mint=(24*60)- $aopfins->adjusted_minute;
        $aopfins->produced_mint=$aopfins->prod_qty * $aopfins->smv;
        $aopfins->effi_per=($aopfins->produced_mint/$aopfins->used_mint)*100;
        $aopfins->achv_per=($aopfins->prod_qty/$aopfins->day_target)*100;
        $aopfins->amount=0;
        return $aopfins;
      });

      

      foreach($aopfins as $aopfin)
      {
          $data['aopfin']['no_of_mc']=isset($data['aopfin']['no_of_mc'])?$data['aopfin']['no_of_mc']+=1:$data['aopfin']['no_of_mc']=1;
          $data['aopfin']['mc_capacity']=isset($data['aopfin']['mc_capacity'])?$data['aopfin']['mc_capacity']+=$aopfin->mc_capacity:$data['aopfin']['mc_capacity']=$aopfin->mc_capacity;

          $data['aopfin']['day_target']=isset($data['aopfin']['day_target'])?$data['aopfin']['day_target']+=$aopfin->day_target:$data['aopfin']['day_target']=$aopfin->day_target;

          $data['aopfin']['prod_qty']=isset($data['aopfin']['prod_qty'])?$data['aopfin']['prod_qty']+=$aopfin->prod_qty:$data['aopfin']['prod_qty']=$aopfin->prod_qty;
          $data['aopfin']['amount']=isset($data['aopfin']['amount'])?$data['aopfin']['amount']+=$aopfin->amount:$data['aopfin']['amount']=$aopfin->amount;

          $data['aopfin']['used_mint']=isset($data['aopfin']['used_mint'])?$data['aopfin']['used_mint']+=$aopfin->used_mint:$data['aopfin']['used_mint']=$aopfin->used_mint;
          
          $data['aopfin']['produced_mint']=isset($data['aopfin']['produced_mint'])?$data['aopfin']['produced_mint']+=$aopfin->produced_mint:$data['aopfin']['produced_mint']=$aopfin->produced_mint;
          $data['aopfin']['effi_per']=($data['aopfin']['produced_mint']/$data['aopfin']['used_mint'])*100;
          $data['aopfin']['achv_per']=($data['aopfin']['prod_qty']/$data['aopfin']['day_target'])*100;

      }

      


      


      
    return Template::loadView('Report.FabricProduction.TxtDailyEfficiencyReportMatrix',[
      'company'=>$company,
      'summary'=> $data,
      'knitings'=>$knitings,
      'dyeings'=>$dyeings,
      'fabfins'=>$fabfins,
      'aopfins'=>$aopfins,
      'aops'=>$aops,
    ]);
    }

    public function reportDataMonthly() {
      $date_from=request('date_from',0);
      $date_to =request('date_to',0);
      $company_id=request('company_id',0);
      $company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->orderBy('name')->get(),'code','id'),'-Select-','');
    }

    
}
