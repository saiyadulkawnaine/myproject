<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\SendMailDailyProfitLoss;
use Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DailyProfitLossCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyprofitloss:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Profit/Loss';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        \Log::info("Profit Loss Cron is working fine!");
        $month_start_date=date('Y-m')."-01";
        $month_end_date=date('Y-m-t');
        $number_of_day=26;//date('t');
        $str2=date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($str2)));

        $companies = \DB::select("
        select * from companies where id in (1,2,4)
        ");
        $company_datas=collect($companies);
        $comany_array=[];
        $sew_data_array=[];
        foreach($company_datas as $company_data){
           $comany_array[$company_data->id]=$company_data->code;
           $sew_data_array['day_qty'][$company_data->id]=0;
           $sew_data_array['day_cm'][$company_data->id]=0;
           $sew_data_array['day_rmc'][$company_data->id]=0;
           $sew_data_array['day_ve'][$company_data->id]=0;
           $sew_data_array['day_fe'][$company_data->id]=0;
        }


        $day_sewing = \DB::select("
        select
        m.company_id,
        sum(m.qty) as sew_qty,
        sum(m.amount) as sew_cm_amount
        from

        (
        SELECT 
        sales_orders.id as sales_order_id,
        sales_orders.produced_company_id as company_id,
        prod_gmt_sewings.sew_qc_date,
        wstudy_line_setups.id,
        prod_gmt_sewing_qties.qty,
        prod_gmt_sewing_qties.qty*budget_cms.cm_per_pcs as amount
        FROM prod_gmt_sewings
        join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
        join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
        join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
        wstudy_line_setup_dtls.from_date>='".$yesterday."' and 
        wstudy_line_setup_dtls.to_date<='".$yesterday."'
        join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join jobs on jobs.id = sales_orders.job_id
        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
        join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
        join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

        join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
        join budgets on budgets.style_id=style_gmts.style_id
        join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id
        where prod_gmt_sewings.sew_qc_date>='".$yesterday."' and 
        prod_gmt_sewings.sew_qc_date<='".$yesterday."'
        ) m group by m.company_id order by m.company_id
        ");
        $day_sewing_datas=collect($day_sewing);
        
        foreach($day_sewing_datas as $day_sewing_data){
          $sew_data_array['day_qty'][$day_sewing_data->company_id]=$day_sewing_data->sew_qty;
          $sew_data_array['day_cm'][$day_sewing_data->company_id]=$day_sewing_data->sew_cm_amount*85;
        }

        /*$day_poly = \DB::select("
        select
        m.company_id,
        
        sum(m.qty) as poly_qty,
        sum(m.amount) as poly_cm_amount
        from
        (
        SELECT 
        sales_orders.id as sales_order_id,
        sales_orders.produced_company_id as company_id,
        prod_gmt_polies.poly_qc_date,
        prod_gmt_poly_qties.qty,
        prod_gmt_poly_qties.qty*budget_cms.cm_per_pcs as amount
        FROM prod_gmt_polies
        join prod_gmt_poly_orders on prod_gmt_poly_orders.prod_gmt_poly_id = prod_gmt_polies.id
        join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join jobs on jobs.id = sales_orders.job_id
        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
        join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
        join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
        join prod_gmt_poly_qties on prod_gmt_poly_qties.prod_gmt_poly_order_id = prod_gmt_poly_orders.id  and prod_gmt_poly_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
        join budgets on budgets.style_id=style_gmts.style_id
        join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id
        where prod_gmt_polies.poly_qc_date>='".$yesterday."' and 
        prod_gmt_polies.poly_qc_date<='".$yesterday."'
        ) m group by m.company_id order by m.company_id
        ");
        $day_poly_datas=collect($day_poly);
        
        foreach($day_poly_datas as $day_poly_data){
          $data_array['day_poly_qty'][$day_poly_data->company_id]=$day_poly_data->poly_qty;
          $data_array['day_poly_cm'][$day_poly_data->company_id]=$day_poly_data->poly_cm_amount*85;
        }*/

        $expense = \DB::select("
        select
        acc_beps.company_id,
        companies.code as company_code,
        acc_beps.start_date,
        acc_beps.end_date,
        acc_chart_ctrl_heads.expense_type_id,
        sum(acc_bep_entries.amount) as expense
        from
        acc_beps
        join acc_bep_entries on acc_bep_entries.acc_bep_id=acc_beps.id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
        join companies on companies.id=acc_beps.company_id
        join profitcenters on profitcenters.id=acc_beps.profitcenter_id
        where acc_beps.start_date >= '".$month_start_date."'
        and acc_beps.end_date <= '".$month_end_date."'
        and profitcenters.id=2
        and acc_chart_ctrl_heads.statement_type_id=2
        and acc_chart_ctrl_heads.is_cm_expense=1
        group by
        acc_beps.company_id,
        companies.code,
        acc_beps.start_date,
        acc_beps.end_date,
        acc_chart_ctrl_heads.expense_type_id
        order by
        acc_beps.company_id
        ");
        $expense_datas=collect($expense);
        foreach($expense_datas as $expense_data){
            if($expense_data->expense_type_id==1){
            $sew_data_array['day_ve'][$expense_data->company_id]=$expense_data->expense/$number_of_day;
            $sew_data_array['day_cront_m'][$expense_data->company_id]=$sew_data_array['day_cm'][$expense_data->company_id] - $sew_data_array['day_ve'][$expense_data->company_id];
	            if($sew_data_array['day_cm'][$expense_data->company_id]){
	            	$sew_data_array['day_cront_m_per'][$expense_data->company_id]=($sew_data_array['day_ve'][$expense_data->company_id]/$sew_data_array['day_cm'][$expense_data->company_id])*100;
	            }
	            else{
	            	$sew_data_array['day_cront_m_per'][$expense_data->company_id]=0;
	            }
            }
            if($expense_data->expense_type_id==2){
            $sew_data_array['day_fe'][$expense_data->company_id]=$expense_data->expense/$number_of_day;
            $sew_data_array['day_pl'][$expense_data->company_id]=$sew_data_array['day_cront_m'][$expense_data->company_id]-$sew_data_array['day_fe'][$expense_data->company_id];

            }
        }

        $day_knit = \DB::select("
        select 
        sum (m.qty) as qc_qty,
        sum (m.amount) as amount
        from
        (
        select
        prod_knits.prod_date,
        prod_knit_qcs.qc_pass_qty as qty,
        CASE
        WHEN so_knit_items.rate is not null 
        THEN prod_knit_qcs.qc_pass_qty*so_knit_items.rate*so_knits.exch_rate
        WHEN po_knit_service_item_qties.rate is not null 
        THEN prod_knit_qcs.qc_pass_qty*po_knit_service_item_qties.rate*po_knit_services.exch_rate
        ELSE 0 
        END as amount
        from
        prod_knit_qcs
        join prod_knit_item_rolls on prod_knit_item_rolls.id=prod_knit_qcs.prod_knit_item_roll_id
        join prod_knit_items on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
        join prod_knits on prod_knits.id=prod_knit_items.prod_knit_id
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
        prod_knit_qcs.qc_date>='".$yesterday."'
        and prod_knit_qcs.qc_date<='".$yesterday."'
        and prod_knits.basis_id=1
        ) m 
        ");
        $day_knit_datas=collect($day_knit)->first();
        $knit_data_array=[];
        $knit_data_array['qc_qty']=$day_knit_datas->qc_qty;
        $knit_data_array['amount']=$day_knit_datas->amount;

        $expense_knit = \DB::select("
        select
        acc_beps.company_id,
        companies.code as company_code,
        acc_beps.start_date,
        acc_beps.end_date,
        acc_chart_ctrl_heads.expense_type_id,
        sum(acc_bep_entries.amount) as expense
        from
        acc_beps
        join acc_bep_entries on acc_bep_entries.acc_bep_id=acc_beps.id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
        join companies on companies.id=acc_beps.company_id
        join profitcenters on profitcenters.id=acc_beps.profitcenter_id
        where acc_beps.start_date >= '".$month_start_date."'
        and acc_beps.end_date <= '".$month_end_date."'
        and profitcenters.id=41
        and acc_chart_ctrl_heads.statement_type_id=2
        --and acc_chart_ctrl_heads.is_cm_expense=1
        group by
        acc_beps.company_id,
        companies.code,
        acc_beps.start_date,
        acc_beps.end_date,
        acc_chart_ctrl_heads.expense_type_id
        order by
        acc_beps.company_id
        ");
        $expense_knit_datas=collect($expense_knit);
        foreach($expense_knit_datas as $expense_knit_data){
            if($expense_knit_data->expense_type_id==1){
                $knit_data_array['day_ve']=$expense_knit_data->expense/$number_of_day;
                $knit_data_array['day_cront_m']=$knit_data_array['amount']-$knit_data_array['day_ve'];
                if($knit_data_array['amount']){
                   $knit_data_array['day_cront_m_per']=($knit_data_array['day_ve']/$knit_data_array['amount'])*100; 
                }
                else{
                    $knit_data_array['day_cront_m_per']=0;
                }
            }
            if($expense_knit_data->expense_type_id==2){
            $knit_data_array['day_fe']=$expense_knit_data->expense/$number_of_day;
            $knit_data_array['day_pl']=$knit_data_array['day_cront_m']-$knit_data_array['day_fe'];
            }
        }

        $day_dyeing = \DB::select("
            select 
            sum (m.qty) as qc_qty,
            sum (m.amount) as amount
            from
            (
            select
            prod_batch_finish_qcs.posting_date,
            prod_batch_finish_qc_rolls.qty as qty,
            CASE
            WHEN so_dyeing_items.rate is not null 
            THEN prod_batch_finish_qc_rolls.qty*so_dyeing_items.rate*so_dyeings.exch_rate
            WHEN po_dyeing_service_item_qties.rate is not null 
            THEN prod_batch_finish_qc_rolls.qty*po_dyeing_service_item_qties.rate*po_dyeing_services.exch_rate
            ELSE 0 
            END as amount
            from
            prod_batch_finish_qcs
            join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_finish_qc_id=prod_batch_finish_qcs.id
            join prod_batches on prod_batches.id=prod_batch_finish_qcs.prod_batch_id
            join prod_batch_rolls on prod_batch_rolls.id=prod_batch_finish_qc_rolls.prod_batch_roll_id
            join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
            join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
            join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
            join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id
            left join po_dyeing_services on po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id
            where 
            prod_batch_finish_qcs.posting_date>='".$yesterday."'
            and prod_batch_finish_qcs.posting_date<='".$yesterday."'
            and prod_batch_finish_qcs.prod_batch_id is not null
            ) m  
        ");
        $day_dyeing_datas=collect($day_dyeing)->first();
        $dyeing_data_array=[];
        $dyeing_data_array['qc_qty']=$day_dyeing_datas->qc_qty;
        $dyeing_data_array['amount']=$day_dyeing_datas->amount;

        $day_dyeing_add_bill = \DB::select("
            select
            sum(prod_finish_qc_bill_items.amount) as amount
            from
            prod_finish_dlvs
            join prod_finish_qc_bill_items on prod_finish_qc_bill_items.prod_finish_dlv_id=prod_finish_dlvs.id
            where prod_finish_dlvs.menu_id=288
            and prod_finish_dlvs.dlv_date>='".$yesterday."'
            and prod_finish_dlvs.dlv_date<='".$yesterday."'
            and prod_finish_dlvs.deleted_at is null
            and prod_finish_qc_bill_items.deleted_at is null 
        ");
        $day_dyeing_add_bill_datas=collect($day_dyeing_add_bill)->first();
        $dyeing_data_array['amount']+=$day_dyeing_add_bill_datas->amount;

        $day_dyeing_batch_wgt = \DB::select("
            select
            sum(prod_batches.batch_wgt) as batch_wgt
            from
            prod_batch_finish_qcs
            join prod_batches on prod_batches.id=prod_batch_finish_qcs.prod_batch_id
            where 
            prod_batch_finish_qcs.posting_date>='".$yesterday."'
            and prod_batch_finish_qcs.posting_date<='".$yesterday."'  
            and prod_batch_finish_qcs.prod_batch_id is not null
        ");
        $day_dyeing_batch_wgt_datas=collect($day_dyeing_batch_wgt)->first();

        $day_dyeing_mtr = \DB::select("
            select
            sum(inv_dye_chem_isu_items.qty) as qty,
            sum(inv_dye_chem_isu_items.amount) as amount
            from
            prod_batch_finish_qcs
            join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.prod_batch_id=prod_batch_finish_qcs.prod_batch_id
            join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rqs.id
            join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id=inv_dye_chem_isu_rq_items.id
            where 
            prod_batch_finish_qcs.posting_date>='".$yesterday."'
            and prod_batch_finish_qcs.posting_date<='".$yesterday."' 
            and prod_batch_finish_qcs.prod_batch_id is not null 
        ");
        $day_dyeing_mtr_datas=collect($day_dyeing_mtr)->first();
        $dyeing_data_array['day_mtr']=($day_dyeing_mtr_datas->amount/$day_dyeing_batch_wgt_datas->batch_wgt)*$day_dyeing_datas->qc_qty;

        $expense_dyeing = \DB::select("
        select
        acc_beps.company_id,
        companies.code as company_code,
        acc_beps.start_date,
        acc_beps.end_date,
        acc_chart_ctrl_heads.expense_type_id,
        sum(acc_bep_entries.amount) as expense
        from
        acc_beps
        join acc_bep_entries on acc_bep_entries.acc_bep_id=acc_beps.id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
        join companies on companies.id=acc_beps.company_id
        join profitcenters on profitcenters.id=acc_beps.profitcenter_id
        where acc_beps.start_date >= '".$month_start_date."'
        and acc_beps.end_date <= '".$month_end_date."'
        and profitcenters.id=4
        and acc_chart_ctrl_heads.statement_type_id=2
        --and acc_chart_ctrl_heads.is_cm_expense=1
        group by
        acc_beps.company_id,
        companies.code,
        acc_beps.start_date,
        acc_beps.end_date,
        acc_chart_ctrl_heads.expense_type_id
        order by
        acc_beps.company_id
        ");
        $expense_dyeing_datas=collect($expense_dyeing);
        foreach($expense_dyeing_datas as $expense_dyeing_data){
            if($expense_dyeing_data->expense_type_id==1){
                $dyeing_data_array['day_ve']=$expense_dyeing_data->expense/$number_of_day;

                $dyeing_data_array['day_cront_m']=$dyeing_data_array['amount']-($dyeing_data_array['day_mtr']+$dyeing_data_array['day_ve']);
                if($dyeing_data_array['amount']){
                   $dyeing_data_array['day_cront_m_per']=($dyeing_data_array['day_ve']/$dyeing_data_array['amount'])*100; 
                }
                else{
                    $dyeing_data_array['day_cront_m_per']=0;
                }
            }
            if($expense_dyeing_data->expense_type_id==2){
            $dyeing_data_array['day_fe']=$expense_dyeing_data->expense/$number_of_day;
            $dyeing_data_array['day_pl']=$dyeing_data_array['day_cront_m']-$dyeing_data_array['day_fe'];
            }
        }

        $day_aop = \DB::select("
        select 
        sum (m.qty) as qc_qty,
        sum (m.amount) as amount
        from
        (
        select
        prod_batch_finish_qcs.posting_date,
        prod_batch_finish_qc_rolls.qty as qty,
        CASE
        WHEN so_aop_items.rate is not null 
        THEN prod_batch_finish_qc_rolls.qty*so_aop_items.rate*so_aops.exch_rate
        WHEN po_aop_service_item_qties.rate is not null 
        THEN prod_batch_finish_qc_rolls.qty*po_aop_service_item_qties.rate*po_aop_services.exch_rate
        ELSE 0 
        END as amount
        from
        prod_batch_finish_qcs
        join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_finish_qc_id=prod_batch_finish_qcs.id
        join prod_aop_batches on prod_aop_batches.id=prod_batch_finish_qcs.prod_aop_batch_id
        join prod_aop_batch_rolls on prod_aop_batch_rolls.id=prod_batch_finish_qc_rolls.prod_aop_batch_roll_id

        join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id=prod_aop_batch_rolls.so_aop_fabric_isu_item_id
        join so_aop_fabric_isus on so_aop_fabric_isus.id=so_aop_fabric_isu_items.so_aop_fabric_isu_id

        join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id=so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
        join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id=so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id


        join so_aop_refs on so_aop_refs.id=so_aop_fabric_rcv_items.so_aop_ref_id
        join so_aops on so_aops.id=so_aop_refs.so_aop_id
        left join so_aop_items on so_aop_items.so_aop_ref_id=so_aop_refs.id
        left join so_aop_po_items on so_aop_po_items.so_aop_ref_id=so_aop_refs.id
        left join po_aop_service_item_qties on po_aop_service_item_qties.id=so_aop_po_items.po_aop_service_item_qty_id
        left join po_aop_service_items on po_aop_service_items.id=po_aop_service_item_qties.po_aop_service_item_id
        left join po_aop_services on po_aop_services.id=po_aop_service_items.po_aop_service_id
        where 
        prod_batch_finish_qcs.posting_date>='".$yesterday."'
        and prod_batch_finish_qcs.posting_date<='".$yesterday."'
        and prod_batch_finish_qcs.prod_aop_batch_id is not null
        ) m  
        ");
        $day_aop_datas=collect($day_aop)->first();
        $aop_data_array=[];
        $aop_data_array['qc_qty']=$day_aop_datas->qc_qty;
        $aop_data_array['amount']=$day_aop_datas->amount;

        $day_aop_batch_wgt = \DB::select("
            select
            sum(prod_aop_batches.fabric_wgt) as batch_wgt
            from
            prod_batch_finish_qcs
            join prod_aop_batches on prod_aop_batches.id=prod_batch_finish_qcs.prod_aop_batch_id
            where 
            prod_batch_finish_qcs.posting_date>='".$yesterday."'
            and prod_batch_finish_qcs.posting_date<='".$yesterday."'  
            and prod_batch_finish_qcs.prod_aop_batch_id is not null
        ");
        $day_aop_batch_wgt_datas=collect($day_aop_batch_wgt)->first();

        $day_aop_mtr = \DB::select("
            select
            sum(inv_dye_chem_isu_items.qty) as qty,
            sum(inv_dye_chem_isu_items.amount) as amount
            from
            prod_batch_finish_qcs
            join inv_dye_chem_isu_rqs on inv_dye_chem_isu_rqs.prod_aop_batch_id=prod_batch_finish_qcs.prod_aop_batch_id
            join inv_dye_chem_isu_rq_items on inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id=inv_dye_chem_isu_rqs.id
            join inv_dye_chem_isu_items on inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id=inv_dye_chem_isu_rq_items.id
            where 
            prod_batch_finish_qcs.posting_date>='".$yesterday."'
            and prod_batch_finish_qcs.posting_date<='".$yesterday."' 
            and prod_batch_finish_qcs.prod_aop_batch_id is not null 
        ");
        $day_aop_mtr_datas=collect($day_aop_mtr)->first();
        $aop_data_array['day_mtr']=($day_aop_mtr_datas->amount/$day_aop_batch_wgt_datas->batch_wgt)*$day_aop_datas->qc_qty;

        $expense_aop = \DB::select("
        select
        acc_beps.company_id,
        companies.code as company_code,
        acc_beps.start_date,
        acc_beps.end_date,
        acc_chart_ctrl_heads.expense_type_id,
        sum(acc_bep_entries.amount) as expense
        from
        acc_beps
        join acc_bep_entries on acc_bep_entries.acc_bep_id=acc_beps.id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
        join companies on companies.id=acc_beps.company_id
        join profitcenters on profitcenters.id=acc_beps.profitcenter_id
        where acc_beps.start_date >= '".$month_start_date."'
        and acc_beps.end_date <= '".$month_end_date."'
        and profitcenters.id=21
        and acc_chart_ctrl_heads.statement_type_id=2
        --and acc_chart_ctrl_heads.is_cm_expense=1
        group by
        acc_beps.company_id,
        companies.code,
        acc_beps.start_date,
        acc_beps.end_date,
        acc_chart_ctrl_heads.expense_type_id
        order by
        acc_beps.company_id
        ");
        $expense_aop_datas=collect($expense_aop);
        foreach($expense_aop_datas as $expense_aop_data){
            if($expense_aop_data->expense_type_id==1){
                $aop_data_array['day_ve']=$expense_aop_data->expense/$number_of_day;

                $aop_data_array['day_cront_m']=$aop_data_array['amount']-($aop_data_array['day_mtr']+$aop_data_array['day_ve']);
                if($aop_data_array['amount']){
                   $aop_data_array['day_cront_m_per']=($aop_data_array['day_ve']/$aop_data_array['amount'])*100; 
                }
                else{
                    $aop_data_array['day_cront_m_per']=0;
                }
            }
            if($expense_aop_data->expense_type_id==2){
            $aop_data_array['day_fe']=$expense_aop_data->expense/$number_of_day;
            $aop_data_array['day_pl']=$aop_data_array['day_cront_m']-$aop_data_array['day_fe'];
            }
        }


        $to=[
            'md@lithegroup.com',
            'siddiquee@lithegroup.com',
            'monzu@lithegroup.com',
        ];
        $data['companies']=$comany_array;
        $data['sew_data_array']=$sew_data_array;
        $data['knit_data_array']=$knit_data_array;
        $data['dyeing_data_array']=$dyeing_data_array;
        $data['aop_data_array']=$aop_data_array;
        
        Mail::to($to)->send(new SendMailDailyProfitLoss($data));
        
    }
}
