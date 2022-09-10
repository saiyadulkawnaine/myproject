<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\TnataskRepository;
use Illuminate\Support\Carbon;

class TargetAchievementReportController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $user;
	private $buyernature;
	private $itemaccount;
	private $tnatask;
	public function __construct(
		StyleRepository $style,
		CompanyRepository $company,
		BuyerRepository $buyer,
		BuyerNatureRepository $buyernature,
		UserRepository $user,
		ItemAccountRepository $itemaccount,
		TnataskRepository $tnatask
	)
    {
		$this->style=$style;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->user = $user;
		$this->buyernature = $buyernature;
		$this->itemaccount = $itemaccount;
		$this->tnatask = $tnatask;
		$this->middleware('auth');
		//$this->middleware('permission:view.orderprogressreports',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {

        return Template::loadView('Report.TargetAchievementReport');
    }

    public function getData() {
      $tergetProcess=array_prepend(config('bprs.tergetProcess'),'-Select-','');
      $date_from=request('date_from',0);
      $date_to =request('date_to',0);
      $data = \DB::select("
		select 
		target_transfers.process_id,
		sum(target_transfers.qty) as target_qty,
		so_knit_tgts.so_knit_tgt_qty,
		so_dyeing_tgts.so_dyeing_tgt_qty,
		so_aop_tgts.so_aop_tgt_qty,
		prod_knit.knit_qty,
		prod_dyeing.dyeing_qty,
		prod_aop.prod_aop_qty,
		prod_cut.prod_cut_qty,
		prod_sp.prod_sp_qty,
		prod_emb.prod_emb_qty,
		prod_sew.prod_sew_qty
		FROM target_transfers

		left join (
		select
		sum(so_knit_targets.qty) as so_knit_tgt_qty,
		1 as process_id
		from 
		so_knit_targets
		where 
		so_knit_targets.execute_month>='".$date_from."'  and 
		so_knit_targets.execute_month<='".$date_to."'
		and so_knit_targets.deleted_at is null
		)so_knit_tgts on so_knit_tgts.process_id=target_transfers.process_id

		left join (
		select
		sum(so_dyeing_targets.qty) as so_dyeing_tgt_qty,
		2 as process_id
		from 
		so_dyeing_targets
		where 
		so_dyeing_targets.execute_month>='".$date_from."'  and 
		so_dyeing_targets.execute_month<='".$date_to."'
		and so_dyeing_targets.deleted_at is null
		)so_dyeing_tgts on so_dyeing_tgts.process_id=target_transfers.process_id

		left join (
		select
		sum(so_aop_targets.qty) as so_aop_tgt_qty,
		4 as process_id
		from 
		so_aop_targets
		where 
		so_aop_targets.execute_month>='".$date_from."'  and 
		so_aop_targets.execute_month<='".$date_to."'
		and so_aop_targets.deleted_at is null
		)so_aop_tgts on so_aop_tgts.process_id=target_transfers.process_id

		left join (
		SELECT 
		1 as process_id,
		sum(prod_knit_item_rolls.roll_weight) as knit_qty
		FROM prod_knits
		join prod_knit_items on prod_knit_items.prod_knit_id = prod_knits.id
		join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id = prod_knit_items.id
		where prod_knits.prod_date>='".$date_from."'  and 
		prod_knits.prod_date<='".$date_to."' and 
		prod_knits.basis_id=1 
		) prod_knit on prod_knit.process_id=target_transfers.process_id
		left join (
		select 
		2 as process_id,
		sum(prod_batches.batch_wgt) as dyeing_qty
		from 
		prod_batches
		where 
		prod_batches.unload_date>= '".$date_from."'  and 
		prod_batches.unload_date<= '".$date_to."'  and 
		prod_batches.deleted_at is null and 
		prod_batches.unloaded_at is not null 
		) prod_dyeing on prod_dyeing.process_id=target_transfers.process_id
		left join (
		select
		sum(prod_aop_batch_rolls.qty) as prod_aop_qty,
		4 as process_id
		from
		prod_batch_finish_progs
		join prod_batch_finish_prog_rolls on prod_batch_finish_prog_rolls.prod_batch_finish_prog_id=prod_batch_finish_progs.id
		join prod_aop_batch_rolls on prod_batch_finish_prog_rolls.prod_aop_batch_roll_id=prod_aop_batch_rolls.id
		join production_processes on production_processes.id=prod_batch_finish_progs.production_process_id
		where
		prod_batch_finish_progs.posting_date>='".$date_from."'
		and prod_batch_finish_progs.posting_date<='".$date_to."'
		and production_processes.production_area_id=25
		) prod_aop on prod_aop.process_id=target_transfers.process_id
		left join (
		SELECT 
		sum(prod_gmt_cutting_qties.qty) as prod_cut_qty,
		5 as process_id
		FROM prod_gmt_cuttings
		join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
		join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
		where prod_gmt_cuttings.cut_qc_date>='".$date_from."' and 
		prod_gmt_cuttings.cut_qc_date<='".$date_to."' and 
		prod_gmt_cutting_orders.prod_source_id=1

		) prod_cut on prod_cut.process_id=target_transfers.process_id

		left join (
		select 
		sum(prod_gmt_print_rcv_qties.qty) as prod_sp_qty,
		6 as process_id
		from 
		prod_gmt_print_rcvs
		join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
		join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id
		where prod_gmt_print_rcvs.receive_date>='".$date_from."' and 
		prod_gmt_print_rcvs.receive_date<='".$date_to."' and
		prod_gmt_print_rcv_orders.prod_source_id=1 
		) prod_sp on prod_sp.process_id=target_transfers.process_id

		left join (
		select 
		sum(prod_gmt_emb_rcv_qties.qty) as prod_emb_qty,
		7 as process_id
		from 
		prod_gmt_emb_rcvs
		join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id = prod_gmt_emb_rcvs.id
		join prod_gmt_emb_rcv_qties on prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id = prod_gmt_emb_rcv_orders.id
		where prod_gmt_emb_rcvs.receive_date>='".$date_from."' and 
		prod_gmt_emb_rcvs.receive_date<='".$date_to."' and 
		prod_gmt_emb_rcv_orders.prod_source_id=1 
		) prod_emb on prod_emb.process_id=target_transfers.process_id

		left join (
		SELECT 
		8 as process_id,
		sum(prod_gmt_sewing_qties.qty) as prod_sew_qty
		FROM prod_gmt_sewings
		join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
		join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id 
		where 
		prod_gmt_sewings.sew_qc_date>='".$date_from."' and 
		prod_gmt_sewings.sew_qc_date<='".$date_to."' and
		prod_gmt_sewing_orders.prod_source_id=1   
		) prod_sew on prod_sew.process_id=target_transfers.process_id

		where 
		target_transfers.date_from >= '".$date_from."' 
		and target_transfers.date_to<='".$date_to."'
		and target_transfers.deleted_at is null
		and target_transfers.prod_source_id=1
		group by 
		target_transfers.process_id,
		so_knit_tgts.so_knit_tgt_qty,
		so_dyeing_tgts.so_dyeing_tgt_qty,
		so_aop_tgts.so_aop_tgt_qty,
		prod_knit.knit_qty,
		prod_dyeing.dyeing_qty,
		prod_aop.prod_aop_qty,
		prod_cut.prod_cut_qty,
		prod_sp.prod_sp_qty,
		prod_emb.prod_emb_qty,
		prod_sew.prod_sew_qty
		order by 
		target_transfers.process_id
      ");

      $rows=collect($data)
      ->map(function($rows) use($tergetProcess){
      	$rows->process=$tergetProcess[$rows->process_id];
      	if($rows->process_id==1){
      	 $rows->prod_qty=$rows->knit_qty;
      	 $rows->target_qty+=$rows->so_knit_tgt_qty;
      	}
      	if($rows->process_id==2){
      	 $rows->prod_qty=$rows->dyeing_qty;
      	 $rows->target_qty+=$rows->so_dyeing_tgt_qty;
      	}
      	if($rows->process_id==4){
      	 $rows->prod_qty=$rows->prod_aop_qty;
      	 $rows->target_qty+=$rows->so_aop_tgt_qty;
      	}
      	if($rows->process_id==5){
      	 $rows->prod_qty=$rows->prod_cut_qty;
      	}
      	if($rows->process_id==6){
      	 $rows->prod_qty=$rows->prod_sp_qty;
      	}
      	if($rows->process_id==7){
      	 $rows->prod_qty=$rows->prod_emb_qty;
      	}
      	if($rows->process_id==8){
      	 $rows->prod_qty=$rows->prod_sew_qty;
      	}

      	$rows->achv_per=($rows->prod_qty/$rows->target_qty)*100;
      	return $rows;
      });

      $datab = \DB::select("
		select 
		target_transfers.process_id,
		sum(target_transfers.qty) as target_qty,
		prod_knit.knit_qty,
		prod_cut.prod_cut_qty,
		prod_sp.prod_sp_qty,
		prod_emb.prod_emb_qty,
		prod_sew.prod_sew_qty
		FROM target_transfers

		left join (
		SELECT 
		1 as process_id,
		sum(prod_knit_item_rolls.roll_weight) as knit_qty
		FROM prod_knits
		join prod_knit_items on prod_knit_items.prod_knit_id = prod_knits.id
		join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id = prod_knit_items.id
		where prod_knits.prod_date>='".$date_from."'  and 
		prod_knits.prod_date<='".$date_to."' and 
		prod_knits.basis_id=5
		) prod_knit on prod_knit.process_id=target_transfers.process_id
		
		
		left join (
		SELECT 
		sum(prod_gmt_cutting_qties.qty) as prod_cut_qty,
		5 as process_id
		FROM prod_gmt_cuttings
		join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
		join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
		where prod_gmt_cuttings.cut_qc_date>='".$date_from."' and 
		prod_gmt_cuttings.cut_qc_date<='".$date_to."' and 
		prod_gmt_cutting_orders.prod_source_id=5

		) prod_cut on prod_cut.process_id=target_transfers.process_id

		left join (
		select 
		sum(prod_gmt_print_rcv_qties.qty) as prod_sp_qty,
		6 as process_id
		from 
		prod_gmt_print_rcvs
		join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
		join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id
		where prod_gmt_print_rcvs.receive_date>='".$date_from."' and 
		prod_gmt_print_rcvs.receive_date<='".$date_to."' and
		prod_gmt_print_rcv_orders.prod_source_id=5 
		) prod_sp on prod_sp.process_id=target_transfers.process_id

		left join (
		select 
		sum(prod_gmt_emb_rcv_qties.qty) as prod_emb_qty,
		7 as process_id
		from 
		prod_gmt_emb_rcvs
		join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id = prod_gmt_emb_rcvs.id
		join prod_gmt_emb_rcv_qties on prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id = prod_gmt_emb_rcv_orders.id
		where prod_gmt_emb_rcvs.receive_date>='".$date_from."' and 
		prod_gmt_emb_rcvs.receive_date<='".$date_to."' and 
		prod_gmt_emb_rcv_orders.prod_source_id=5
		) prod_emb on prod_emb.process_id=target_transfers.process_id

		left join (
		SELECT 
		8 as process_id,
		sum(prod_gmt_sewing_qties.qty) as prod_sew_qty
		FROM prod_gmt_sewings
		join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
		join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id 
		where 
		prod_gmt_sewings.sew_qc_date>='".$date_from."' and 
		prod_gmt_sewings.sew_qc_date<='".$date_to."' and
		prod_gmt_sewing_orders.prod_source_id=5   
		) prod_sew on prod_sew.process_id=target_transfers.process_id

		where 
		target_transfers.date_from >= '".$date_from."' 
		and target_transfers.date_to<='".$date_to."'
		and target_transfers.deleted_at is null
		and target_transfers.prod_source_id=5
		group by 
		target_transfers.process_id,
		prod_knit.knit_qty,
		prod_cut.prod_cut_qty,
		prod_sp.prod_sp_qty,
		prod_emb.prod_emb_qty,
		prod_sew.prod_sew_qty
		order by 
		target_transfers.process_id
      ");

      $rowbs=collect($datab)
      ->map(function($rowbs) use($tergetProcess){
      	$rowbs->process=$tergetProcess[$rowbs->process_id];
      	if($rowbs->process_id==1){
      	 $rowbs->prod_qty=$rowbs->knit_qty;
      	}
      	if($rowbs->process_id==5){
      	 $rowbs->prod_qty=$rowbs->prod_cut_qty;
      	}
      	if($rowbs->process_id==6){
      	 $rowbs->prod_qty=$rowbs->prod_sp_qty;
      	}
      	if($rowbs->process_id==7){
      	 $rowbs->prod_qty=$rowbs->prod_emb_qty;
      	}
      	if($rowbs->process_id==8){
      	 $rowbs->prod_qty=$rowbs->prod_sew_qty;
      	}

      	$rowbs->achv_per=($rowbs->prod_qty/$rowbs->target_qty)*100;
      	return $rowbs;
      });
      return Template::loadView('Report.TargetAchievementReportMatrix',[
        'rows'=>$rows,
        'rowbs'=>$rowbs,
      ]);
    }


}
