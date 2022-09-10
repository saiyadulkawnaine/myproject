<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Util\CompanyRepository;

class TodayAccountController extends Controller
{
	private $company;
	public function __construct(
		CompanyRepository $company
	)
    {
		$this->middleware('auth');
		//$this->middleware('permission:view.prodgmtcapacityachievereports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
    	$date_to=date('Y-m-d');
    	return Template::loadView('Report.TodayAccount',['date_to'=>$date_to]);
    }
    
    public function reportData() {
    	$trans_date=request('trans_date',0);
        $last_trans_date=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $trans_date) ) ));

    	$start_date=date('Y-m', strtotime($trans_date))."-01";
    	$end_date=date("Y-m-t", strtotime($trans_date));
    	$up_to_last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));

        $today_revenues = collect(\DB::select("
            select 
            acc_chart_sub_groups.acc_chart_group_id,
            companies.id company_id,
            abs(sum (acc_trans_chlds.amount)) as amount
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join companies on companies.id=acc_trans_prnts.company_id

            where 
            acc_trans_prnts.trans_date = '".$trans_date."' 
            --and acc_trans_prnts.trans_date <= '".$trans_date."'
            and acc_chart_sub_groups.acc_chart_group_id in(16,25)
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_chart_sub_groups.acc_chart_group_id ,
            companies.id
            order by 
            companies.id"
        ));
        
        $today_revenue_arr=array();
        $today_revenue_com_total=array();
        foreach($today_revenues as $today_revenue)
        {
            $today_revenue_arr[$today_revenue->acc_chart_group_id][$today_revenue->company_id]=$today_revenue->amount;
            $today_revenue_com_total[$today_revenue->company_id][]=$today_revenue->amount;
        }

        $month_revenues = collect(\DB::select("
            select 
            acc_chart_sub_groups.acc_chart_group_id,
            companies.id company_id,
            abs(sum (acc_trans_chlds.amount)) as amount
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join companies on companies.id=acc_trans_prnts.company_id

            where 
            acc_trans_prnts.trans_date >= '".$start_date."' 
            and acc_trans_prnts.trans_date <= '".$end_date."'
            and acc_chart_sub_groups.acc_chart_group_id in(16,25)
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_chart_sub_groups.acc_chart_group_id ,
            companies.id
            order by 
            companies.id"
        ));

        $month_revenue_arr=array();
        $month_revenue_com_total=array();
        foreach($month_revenues as $month_revenue)
        {
            $month_revenue_arr[$month_revenue->acc_chart_group_id][$month_revenue->company_id]=$month_revenue->amount;
            $month_revenue_com_total[$month_revenue->company_id][]=$month_revenue->amount;

        }


        $today_inflows = collect(\DB::select("
            select m.company_id, m.name, m.id, sum(m.amount) as amount from (select 
            acc_trans_prnts.id as acc_trans_prnt_id,
            acc_trans_prnts.company_id,
            acc_chart_ctrl_heads.id,
            acc_chart_ctrl_heads.name,
            abs(sum(acc_trans_chlds.amount)) as amount
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join(
            select 
            acc_trans_prnts.id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            count (acc_trans_chlds.id) as count_id,
            min (acc_chart_ctrl_heads.id) as acc_chart_ctrl_head_id
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            and acc_trans_chlds.deleted_at is null 
            and acc_trans_chlds.amount < 0 
            --and acc_chart_ctrl_heads.control_name_id in( 5,6,30,31,100)
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            group by 
            acc_trans_prnts.id
            ) opposite_head on opposite_head.id=acc_trans_prnts.id
            where 
            acc_trans_prnts.trans_date = '".$trans_date."' 
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            and acc_trans_chlds.amount>0 
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            acc_chart_ctrl_heads.id,
            acc_chart_ctrl_heads.name
            order by 
            acc_trans_prnts.id) m
            group by m.company_id, m.name,m.id
            order by m.company_id"
        ));
        
        $today_inflow_arr=array();
        $today_inflow_com_total=array();
        $today_inflow_row_total=array();
        foreach($today_inflows as $today_inflow)
        {
            $index=$today_inflow->id."::".$today_inflow->name;
            $today_inflow_arr[$index][$today_inflow->company_id]=$today_inflow->amount;
            $today_inflow_com_total[$today_inflow->company_id][]=$today_inflow->amount;
            $today_inflow_row_total[$index][]=$today_inflow->amount;
        }


        $month_inflows = collect(\DB::select("
            select m.company_id, m.name,m.id, sum(m.amount) as amount from (select 
            acc_trans_prnts.id as acc_trans_prnt_id,
            acc_trans_prnts.company_id,
            acc_chart_ctrl_heads.id,
            acc_chart_ctrl_heads.name,
            abs(sum(acc_trans_chlds.amount)) as amount
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join(
            select 
            acc_trans_prnts.id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            count (acc_trans_chlds.id) as count_id,
            min (acc_chart_ctrl_heads.id) as acc_chart_ctrl_head_id
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            and acc_trans_chlds.deleted_at is null 
            and acc_trans_chlds.amount < 0 
            --and acc_chart_ctrl_heads.control_name_id in( 5,6,30,31,100)
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            group by 
            acc_trans_prnts.id
            ) opposite_head on opposite_head.id=acc_trans_prnts.id
            where 
            acc_trans_prnts.trans_date >= '".$start_date."'
            and acc_trans_prnts.trans_date <= '".$end_date."' 
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            and acc_trans_chlds.amount>0 
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            acc_chart_ctrl_heads.id,
            acc_chart_ctrl_heads.name
            order by 
            acc_trans_prnts.id) m
            group by m.company_id, m.name,m.id
            order by m.company_id"
        ));
        
        $month_inflow_arr=array();
        $month_inflow_com_total=array();
        $month_inflow_row_total=array();
        foreach($month_inflows as $month_inflow)
        {
            $index=$month_inflow->id."::".$month_inflow->name;
            $month_inflow_arr[$index][$month_inflow->company_id]=$month_inflow->amount;
            $month_inflow_com_total[$month_inflow->company_id][]=$month_inflow->amount;
            $month_inflow_row_total[$index][]=$month_inflow->amount;
        }



        $today_outflows = collect(\DB::select("
            select m.company_id, m.name, m.id, sum(m.amount) as amount from (select 
            acc_trans_prnts.id as acc_trans_prnt_id,
            acc_trans_prnts.company_id,
            acc_chart_ctrl_heads.id,
            acc_chart_ctrl_heads.name,
            abs(sum(acc_trans_chlds.amount)) as amount
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join(
            select 
            acc_trans_prnts.id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            count (acc_trans_chlds.id) as count_id,
            min (acc_chart_ctrl_heads.id) as acc_chart_ctrl_head_id
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            and acc_trans_chlds.deleted_at is null 
            and acc_trans_chlds.amount > 0 
            --and acc_chart_ctrl_heads.control_name_id in( 5,6,30,31,100)
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            group by 
            acc_trans_prnts.id
            ) opposite_head on opposite_head.id=acc_trans_prnts.id
            where 
            acc_trans_prnts.trans_date = '".$trans_date."' 
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            and acc_trans_chlds.amount<0 
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            acc_chart_ctrl_heads.id,
            acc_chart_ctrl_heads.name
            order by 
            acc_trans_prnts.id) m
            group by m.company_id, m.name,m.id
            order by m.company_id"
        ));
        
        $today_outflow_arr=array();
        $today_outflow_com_total=array();
        $today_outflow_row_total=array();
        foreach($today_outflows as $today_outflow)
        {
            $index=$today_outflow->id."::".$today_outflow->name;
            $today_outflow_arr[$index][$today_outflow->company_id]=$today_outflow->amount;
            $today_outflow_com_total[$today_outflow->company_id][]=$today_outflow->amount;
            $today_outflow_row_total[$index][]=$today_outflow->amount;
        }



        $month_outflows = collect(\DB::select("
            select m.company_id, m.name,m.id, sum(m.amount) as amount from (select 
            acc_trans_prnts.id as acc_trans_prnt_id,
            acc_trans_prnts.company_id,
            acc_chart_ctrl_heads.id,
            acc_chart_ctrl_heads.name,
            abs(sum(acc_trans_chlds.amount)) as amount
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join(
            select 
            acc_trans_prnts.id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            count (acc_trans_chlds.id) as count_id,
            min (acc_chart_ctrl_heads.id) as acc_chart_ctrl_head_id
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            and acc_trans_chlds.deleted_at is null 
            and acc_trans_chlds.amount > 0 
            --and acc_chart_ctrl_heads.control_name_id in( 5,6,30,31,100)
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            group by 
            acc_trans_prnts.id
            ) opposite_head on opposite_head.id=acc_trans_prnts.id
            where 
            acc_trans_prnts.trans_date >= '".$start_date."' 
            and acc_trans_prnts.trans_date <= '".$end_date."'
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            and acc_trans_chlds.amount<0 
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            acc_chart_ctrl_heads.id,
            acc_chart_ctrl_heads.name
            order by 
            acc_trans_prnts.id) m
            group by m.company_id, m.name,m.id
            order by m.company_id"
        ));
        
        $month_outflow_arr=array();
        $month_outflow_com_total=array();
        $month_outflow_row_total=array();
        foreach($month_outflows as $month_outflow)
        {
            $index=$month_outflow->id."::".$month_outflow->name;
            $month_outflow_arr[$index][$month_outflow->company_id]=$month_outflow->amount;
            $month_outflow_com_total[$month_outflow->company_id][]=$month_outflow->amount;
            $month_outflow_row_total[$index][]=$month_outflow->amount;
        }

        $today_receivable_openings = collect(\DB::select("
        select 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id,
        CASE 
        WHEN acc_chart_ctrl_heads.control_name_id=30
        THEN 
        'Opening Balance: Non-LC'
        ELSE
        'Opening Balance: LC'
        END as name,

        abs(sum(acc_trans_chlds.amount)) as amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id

        where 
        acc_trans_prnts.trans_date <= '".$last_trans_date."' 
        and acc_chart_ctrl_heads.control_name_id in(30,31)
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id
        order by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id"
        ));

        $today_receivable_opening_arr=array();
        $today_receivable_opening_row_total=array();
        $today_receivable_com_total=array();
        foreach($today_receivable_openings as $today_receivable_opening)
        {
            $today_receivable_opening_arr[$today_receivable_opening->name][$today_receivable_opening->company_id]=$today_receivable_opening->amount;
            $today_receivable_opening_row_total[$today_receivable_opening->name][]=$today_receivable_opening->amount;

            $today_receivable_com_total[$today_receivable_opening->company_id][]=$today_receivable_opening->amount;
        }


        $today_receivables = collect(\DB::select("
        select 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id,
        CASE 
        WHEN acc_chart_ctrl_heads.control_name_id=30
        THEN 
        'Today Addition: Non-LC'
        ELSE
        'Today Addition: LC'
        END as name,

        abs(sum(acc_trans_chlds.amount)) as amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id

        where 
        acc_trans_prnts.trans_date = '".$trans_date."' 
        and acc_chart_ctrl_heads.control_name_id in(30,31)
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id
        order by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id"
        ));

        $today_receivable_arr=array();
        $today_receivable_row_total=array();
        foreach($today_receivables as $today_receivable)
        {
            $today_receivable_arr[$today_receivable->name][$today_receivable->company_id]=$today_receivable->amount;
            $today_receivable_row_total[$today_receivable->name][]=$today_receivable->amount;
            $today_receivable_com_total[$today_receivable->company_id][]=$today_receivable->amount;
        }



        $month_receivable_openings = collect(\DB::select("
        select 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id,
        CASE 
        WHEN acc_chart_ctrl_heads.control_name_id=30
        THEN 
        'Opening Balance: Non-LC'
        ELSE
        'Opening Balance: LC'
        END as name,

        abs(sum(acc_trans_chlds.amount)) as amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id

        where 
        acc_trans_prnts.trans_date <= '".$up_to_last_month."' 
        and acc_chart_ctrl_heads.control_name_id in(30,31)
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id
        order by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id"
        ));

        $month_receivable_opening_arr=array();
        $month_receivable_opening_row_total=array();
        $month_receivable_com_total=array();
        foreach($month_receivable_openings as $month_receivable_opening)
        {
            $month_receivable_opening_arr[$month_receivable_opening->name][$month_receivable_opening->company_id]=$month_receivable_opening->amount;
            $month_receivable_opening_row_total[$month_receivable_opening->name][]=$month_receivable_opening->amount;

            $month_receivable_com_total[$month_receivable_opening->company_id][]=$month_receivable_opening->amount;
        }


        $month_receivables = collect(\DB::select("
        select 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id,
        CASE 
        WHEN acc_chart_ctrl_heads.control_name_id=30
        THEN 
        'Month Addition: Non-LC'
        ELSE
        'Month Addition: LC'
        END as name,

        abs(sum(acc_trans_chlds.amount)) as amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id

        where 
        acc_trans_prnts.trans_date >= '".$start_date."'
        and acc_trans_prnts.trans_date <= '".$end_date."' 
        and acc_chart_ctrl_heads.control_name_id in(30,31)
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id
        order by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id"
        ));

        $month_receivable_arr=array();
        $month_receivable_row_total=array();
        foreach($month_receivables as $month_receivable)
        {
            $month_receivable_arr[$month_receivable->name][$month_receivable->company_id]=$month_receivable->amount;
            $month_receivable_row_total[$month_receivable->name][]=$month_receivable->amount;
            $month_receivable_com_total[$month_receivable->company_id][]=$month_receivable->amount;
        }




        $today_payable_openings = collect(\DB::select("
        select 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id,
        CASE 
        WHEN acc_chart_ctrl_heads.control_name_id=1
        THEN 
        'Opening Balance: Non-LC'
        ELSE
        'Opening Balance: LC'
        END as name,

        abs(sum(acc_trans_chlds.amount)) as amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id

        where 
        acc_trans_prnts.trans_date <= '".$last_trans_date."' 
        and acc_chart_ctrl_heads.control_name_id in(1,2)
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id
        order by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id"
        ));

        $today_payable_opening_arr=array();
        $today_payable_opening_row_total=array();
        $today_payable_com_total=array();
        foreach($today_payable_openings as $today_payable_opening)
        {
            $today_payable_opening_arr[$today_payable_opening->name][$today_payable_opening->company_id]=$today_payable_opening->amount;
            $today_payable_opening_row_total[$today_payable_opening->name][]=$today_payable_opening->amount;

            $today_payable_com_total[$today_payable_opening->company_id][]=$today_payable_opening->amount;
        }


        $today_payables = collect(\DB::select("
        select 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id,
        CASE 
        WHEN acc_chart_ctrl_heads.control_name_id=1
        THEN 
        'Today Addition: Non-LC'
        ELSE
        'Today Addition: LC'
        END as name,

        abs(sum(acc_trans_chlds.amount)) as amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id

        where 
        acc_trans_prnts.trans_date = '".$trans_date."' 
        and acc_chart_ctrl_heads.control_name_id in(1,2)
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id
        order by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id"
        ));

        $today_payable_arr=array();
        $today_payable_row_total=array();
        foreach($today_payables as $today_payable)
        {
            $today_payable_arr[$today_payable->name][$today_payable->company_id]=$today_payable->amount;
            $today_payable_row_total[$today_payable->name][]=$today_payable->amount;
            $today_payable_com_total[$today_payable->company_id][]=$today_payable->amount;
        }



        $month_payable_openings = collect(\DB::select("
        select 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id,
        CASE 
        WHEN acc_chart_ctrl_heads.control_name_id=1
        THEN 
        'Opening Balance: Non-LC'
        ELSE
        'Opening Balance: LC'
        END as name,

        abs(sum(acc_trans_chlds.amount)) as amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id

        where 
        acc_trans_prnts.trans_date <= '".$up_to_last_month."' 
        and acc_chart_ctrl_heads.control_name_id in(1,2)
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id
        order by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id"
        ));

        $month_payable_opening_arr=array();
        $month_payable_opening_row_total=array();
        $month_payable_com_total=array();
        foreach($month_payable_openings as $month_payable_opening)
        {
            $month_payable_opening_arr[$month_payable_opening->name][$month_payable_opening->company_id]=$month_payable_opening->amount;
            $month_payable_opening_row_total[$month_payable_opening->name][]=$month_payable_opening->amount;

            $month_payable_com_total[$month_payable_opening->company_id][]=$month_payable_opening->amount;
        }


        $month_payables = collect(\DB::select("
        select 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id,
        CASE 
        WHEN acc_chart_ctrl_heads.control_name_id=1
        THEN 
        'Month Addition: Non-LC'
        ELSE
        'Month Addition: LC'
        END as name,

        abs(sum(acc_trans_chlds.amount)) as amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id

        where 
        acc_trans_prnts.trans_date >= '".$start_date."'
        and acc_trans_prnts.trans_date <= '".$end_date."' 
        and acc_chart_ctrl_heads.control_name_id in(1,2)
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id
        order by 
        acc_trans_prnts.company_id,
        acc_chart_ctrl_heads.control_name_id"
        ));

        $month_payable_arr=array();
        $month_payable_row_total=array();
        foreach($month_payables as $month_payable)
        {
            $month_payable_arr[$month_payable->name][$month_payable->company_id]=$month_payable->amount;
            $month_payable_row_total[$month_payable->name][]=$month_payable->amount;
            $month_payable_com_total[$month_payable->company_id][]=$month_payable->amount;
        }


        
    	return Template::loadView('Report.TodayAccountData',[
            'today_revenue_arr'=>$today_revenue_arr,
            'month_revenue_arr'=>$month_revenue_arr,
            'today_revenue_com_total'=>$today_revenue_com_total,
            'month_revenue_com_total'=>$month_revenue_com_total,
            'today_inflow_arr'=>$today_inflow_arr,
            'today_inflow_com_total'=>$today_inflow_com_total,
            'today_inflow_row_total'=>$today_inflow_row_total,
            'month_inflow_arr'=>$month_inflow_arr,
            'month_inflow_com_total'=>$month_inflow_com_total,
            'month_inflow_row_total'=>$month_inflow_row_total,
            'today_outflow_arr'=>$today_outflow_arr,
            'today_outflow_com_total'=>$today_outflow_com_total,
            'today_outflow_row_total'=>$today_outflow_row_total,
            'month_outflow_arr'=>$month_outflow_arr,
            'month_outflow_com_total'=>$month_outflow_com_total,
            'month_outflow_row_total'=>$month_outflow_row_total,

            'today_receivable_opening_arr'=>$today_receivable_opening_arr,
            'today_receivable_opening_row_total'=>$today_receivable_opening_row_total,
            'today_receivable_arr'=>$today_receivable_arr,
            'today_receivable_row_total'=>$today_receivable_row_total,
            'today_receivable_com_total'=>$today_receivable_com_total,

            'month_receivable_opening_arr'=>$month_receivable_opening_arr,
            'month_receivable_opening_row_total'=>$month_receivable_opening_row_total,
            'month_receivable_arr'=>$month_receivable_arr,
            'month_receivable_row_total'=>$month_receivable_row_total,
            'month_receivable_com_total'=>$month_receivable_com_total,

            'today_payable_opening_arr'=>$today_payable_opening_arr,
            'today_payable_opening_row_total'=>$today_payable_opening_row_total,
            'today_payable_arr'=>$today_payable_arr,
            'today_payable_row_total'=>$today_payable_row_total,
            'today_payable_com_total'=>$today_payable_com_total,

            'month_payable_opening_arr'=>$month_payable_opening_arr,
            'month_payable_opening_row_total'=>$month_payable_opening_row_total,
            'month_payable_arr'=>$month_payable_arr,
            'month_payable_row_total'=>$month_payable_row_total,
            'month_payable_com_total'=>$month_payable_com_total,
        ]);
    }

    public function todayInflow()
    {
        $trans_date=request('trans_date',0);
        $company_id=request('company_id',0);
        $head_id=request('head_id',0);
        $company="";
        if($company_id)
        {
            $company=" and acc_trans_prnts.company_id= '".$company_id."' ";
        }
        else
        {
          $company="";  
        }
        $head="";
        if($head_id)
        {
            $head=" and acc_chart_ctrl_heads.id= '".$head_id."' ";
        }
        else
        {
          $head="";  
        }

        $todayInflow = collect(\DB::select("
            select  m.head_name,m.head_code, sum(m.amount) as amount from 
            (
            select 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            opposite_head.count_id,
            opposite_head.acc_chart_ctrl_head_id,
            CASE 
            WHEN opposite_head.count_id = 1
            THEN
            accchartctrlheads.name
            ELSE
            'Multiple Head'
            END
            as head_name,
            CASE 
            WHEN opposite_head.count_id = 1
            THEN
            accchartctrlheads.code
            ELSE
            0
            END
            as head_code
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join(
            select 
            acc_trans_prnts.id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            count (acc_trans_chlds.id) as count_id,
            min (acc_chart_ctrl_heads.id) as acc_chart_ctrl_head_id
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            and acc_trans_chlds.deleted_at is null 
            and acc_trans_chlds.amount < 0 
            --and acc_chart_ctrl_heads.control_name_id in( 5,6,30,31,100)
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            group by 
            acc_trans_prnts.id
            ) opposite_head on opposite_head.id=acc_trans_prnts.id
            join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
            where 
            acc_trans_prnts.trans_date >= '".$trans_date."'
            and acc_trans_prnts.trans_date<= '".$trans_date."'
            $company
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            $head 

            and acc_trans_chlds.amount>0 
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            opposite_head.count_id,
            opposite_head.acc_chart_ctrl_head_id,
            accchartctrlheads.name,
            accchartctrlheads.code
            order by acc_trans_prnts.id
            ) m
            group by  m.head_name,m.head_code
            order by m.head_code"
        ))->map(function($todayInflow){
            $todayInflow->amount=number_format($todayInflow->amount,0);
            return $todayInflow;
        });
        echo json_encode($todayInflow);
    }


    public function monthInflow()
    {
        $trans_date=request('trans_date',0);
        $start_date=date('Y-m', strtotime($trans_date))."-01";
        $end_date=date("Y-m-t", strtotime($trans_date));
        $company_id=request('company_id',0);
        $head_id=request('head_id',0);
        $company="";
        if($company_id)
        {
            $company=" and acc_trans_prnts.company_id= '".$company_id."' ";
        }
        else
        {
          $company="";  
        }
        $head="";
        if($head_id)
        {
            $head=" and acc_chart_ctrl_heads.id= '".$head_id."' ";
        }
        else
        {
          $head="";  
        }

        $monthInflow = collect(\DB::select("
            select  m.head_name,m.head_code, sum(m.amount) as amount from 
            (
            select 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            opposite_head.count_id,
            opposite_head.acc_chart_ctrl_head_id,
            CASE 
            WHEN opposite_head.count_id = 1
            THEN
            accchartctrlheads.name
            ELSE
            'Multiple Head'
            END
            as head_name,
            CASE 
            WHEN opposite_head.count_id = 1
            THEN
            accchartctrlheads.code
            ELSE
            0
            END
            as head_code
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join(
            select 
            acc_trans_prnts.id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            count (acc_trans_chlds.id) as count_id,
            min (acc_chart_ctrl_heads.id) as acc_chart_ctrl_head_id
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            and acc_trans_chlds.deleted_at is null 
            and acc_trans_chlds.amount < 0 
            --and acc_chart_ctrl_heads.control_name_id in( 5,6,30,31,100)
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            group by 
            acc_trans_prnts.id
            ) opposite_head on opposite_head.id=acc_trans_prnts.id
            join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
            where 
            acc_trans_prnts.trans_date >= '".$start_date."'
            and acc_trans_prnts.trans_date<= '".$end_date."'
            $company
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            $head 
            and acc_trans_chlds.amount>0 
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            opposite_head.count_id,
            opposite_head.acc_chart_ctrl_head_id,
            accchartctrlheads.name,
            accchartctrlheads.code
            order by acc_trans_prnts.id
            ) m
            group by  m.head_name,m.head_code
            order by m.head_code"
        ))->map(function($monthInflow){
            $monthInflow->amount=number_format($monthInflow->amount,0);
            return $monthInflow;
        });
        echo json_encode($monthInflow);
    }


    public function todayOutflow()
    {
        $trans_date=request('trans_date',0);
        $company_id=request('company_id',0);
        $head_id=request('head_id',0);
        $company="";
        if($company_id)
        {
            $company=" and acc_trans_prnts.company_id= '".$company_id."' ";
        }
        else
        {
          $company="";  
        }
        $head="";
        if($head_id)
        {
            $head=" and acc_chart_ctrl_heads.id= '".$head_id."' ";
        }
        else
        {
          $head="";  
        }

        $todayOutflow = collect(\DB::select("
            select  m.head_name,m.head_code, sum(m.amount) as amount from 
            (
            select 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            opposite_head.count_id,
            opposite_head.acc_chart_ctrl_head_id,
            CASE 
            WHEN opposite_head.count_id = 1
            THEN
            accchartctrlheads.name
            ELSE
            'Multiple Head'
            END
            as head_name,
            CASE 
            WHEN opposite_head.count_id = 1
            THEN
            accchartctrlheads.code
            ELSE
            0
            END
            as head_code
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join(
            select 
            acc_trans_prnts.id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            count (acc_trans_chlds.id) as count_id,
            min (acc_chart_ctrl_heads.id) as acc_chart_ctrl_head_id
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            and acc_trans_chlds.deleted_at is null 
            and acc_trans_chlds.amount > 0 
            --and acc_chart_ctrl_heads.control_name_id in( 5,6,30,31,100)
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            group by 
            acc_trans_prnts.id
            ) opposite_head on opposite_head.id=acc_trans_prnts.id
            join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
            where 
            acc_trans_prnts.trans_date >= '".$trans_date."'
            and acc_trans_prnts.trans_date<= '".$trans_date."'
            $company
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            $head 

            and acc_trans_chlds.amount<0 
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            opposite_head.count_id,
            opposite_head.acc_chart_ctrl_head_id,
            accchartctrlheads.name,
            accchartctrlheads.code
            order by acc_trans_prnts.id
            ) m
            group by  m.head_name,m.head_code
            order by m.head_code"
        ))->map(function($todayOutflow){
            $todayOutflow->amount=number_format($todayOutflow->amount,0);
            return $todayOutflow;
        });
        echo json_encode($todayOutflow);
    }

    public function monthOutflow()
    {
        $trans_date=request('trans_date',0);
        $start_date=date('Y-m', strtotime($trans_date))."-01";
        $end_date=date("Y-m-t", strtotime($trans_date));
        $company_id=request('company_id',0);
        $head_id=request('head_id',0);
        $company="";
        if($company_id)
        {
            $company=" and acc_trans_prnts.company_id= '".$company_id."' ";
        }
        else
        {
          $company="";  
        }
        $head="";
        if($head_id)
        {
            $head=" and acc_chart_ctrl_heads.id= '".$head_id."' ";
        }
        else
        {
          $head="";  
        }

        $monthOutflow = collect(\DB::select("
            select  m.head_name,m.head_code, sum(m.amount) as amount from 
            (
            select 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            opposite_head.count_id,
            opposite_head.acc_chart_ctrl_head_id,
            CASE 
            WHEN opposite_head.count_id = 1
            THEN
            accchartctrlheads.name
            ELSE
            'Multiple Head'
            END
            as head_name,
            CASE 
            WHEN opposite_head.count_id = 1
            THEN
            accchartctrlheads.code
            ELSE
            0
            END
            as head_code
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join(
            select 
            acc_trans_prnts.id,
            abs(sum(acc_trans_chlds.amount)) as amount,
            count (acc_trans_chlds.id) as count_id,
            min (acc_chart_ctrl_heads.id) as acc_chart_ctrl_head_id
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            and acc_trans_chlds.deleted_at is null 
            and acc_trans_chlds.amount > 0 
            --and acc_chart_ctrl_heads.control_name_id in( 5,6,30,31,100)
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            group by 
            acc_trans_prnts.id
            ) opposite_head on opposite_head.id=acc_trans_prnts.id
            join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
            where 
            acc_trans_prnts.trans_date >= '".$start_date."'
            and acc_trans_prnts.trans_date<= '".$end_date."'
            $company
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            $head 
            and acc_trans_chlds.amount<0 
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            opposite_head.count_id,
            opposite_head.acc_chart_ctrl_head_id,
            accchartctrlheads.name,
            accchartctrlheads.code
            order by acc_trans_prnts.id
            ) m
            group by  m.head_name,m.head_code
            order by m.head_code"
        ))->map(function($monthOutflow){
            $monthOutflow->amount=number_format($monthOutflow->amount,0);
            return $monthOutflow;
        });
        echo json_encode($monthOutflow);
    }


    public function todayRevenue(){

        $trans_date=request('trans_date',0);
        $start_date=date('Y-m', strtotime($trans_date))."-01";
        $end_date=date("Y-m-t", strtotime($trans_date));
        $company_id=request('company_id',0);
        $head_id=request('head_id',0);
        $company="";
        if($company_id)
        {
            $company=" and acc_trans_prnts.company_id= '".$company_id."' ";
        }
        else
        {
          $company="";  
        }
        $head="";
        if($head_id)
        {
            $head=" and acc_chart_sub_groups.acc_chart_group_id = '".$head_id."' ";
        }
        else
        {
          $head=" and acc_chart_sub_groups.acc_chart_group_id in(16,25) ";  
        }

        $todayRevenue = collect(\DB::select("
        select 
        companies.id company_id,
        companies.code company_code,
        acc_trans_prnts.id acc_trans_prnt_id,
        acc_trans_prnts.trans_no,
        acc_chart_ctrl_heads.name as head_name,
        acc_chart_ctrl_heads.code as head_code,
        acc_trans_chlds.bill_no,
        acc_trans_chlds.party_id,
        acc_trans_chlds.chld_narration,
        abs(sum (acc_trans_chlds.amount)) as amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
        join companies on companies.id=acc_trans_prnts.company_id

        where 
        acc_trans_prnts.trans_date = '".$trans_date."'
        $company
        $head
        and acc_trans_chlds.deleted_at is null
        group by 
        companies.id,
        companies.code,
        acc_trans_prnts.id,
        acc_trans_prnts.trans_no,
        acc_trans_chlds.id,
        acc_trans_chlds.party_id,
        acc_chart_ctrl_heads.name,
        acc_chart_ctrl_heads.code,
        acc_trans_chlds.bill_no,
        acc_trans_chlds.chld_narration
        order by 
        companies.id"
        ))
        ->groupBy('company_code');
        $datas=array();
        foreach($todayRevenue as $company_name=>$value)
        {
            $amount=0;
            foreach($value as $row)
            {
                $amount+=$row->amount;
                $row->amount=number_format($row->amount,0);
                array_push($datas,$row);
            }
            $subTot = collect(['company_code'=>'Sub Total','amount'=>number_format($amount,'0','.',','),'fin_fab'=>'']);
            array_push($datas,$subTot);
        }
        echo json_encode($datas);

    }


    public function monthRevenue(){

        $trans_date=request('trans_date',0);
        $start_date=date('Y-m', strtotime($trans_date))."-01";
        $end_date=date("Y-m-t", strtotime($trans_date));
        $company_id=request('company_id',0);
        $head_id=request('head_id',0);
        $company="";
        if($company_id)
        {
            $company=" and acc_trans_prnts.company_id= '".$company_id."' ";
        }
        else
        {
          $company="";  
        }
        $head="";
        if($head_id)
        {
            $head=" and acc_chart_sub_groups.acc_chart_group_id = '".$head_id."' ";
        }
        else
        {
          $head=" and acc_chart_sub_groups.acc_chart_group_id in(16,25) ";  
        }

        $monthRevenue = collect(\DB::select("
        select 
        companies.id company_id,
        companies.code company_code,
        acc_trans_prnts.id acc_trans_prnt_id,
        acc_trans_prnts.trans_no,
        acc_chart_ctrl_heads.name as head_name,
        acc_chart_ctrl_heads.code as head_code,
        acc_trans_chlds.bill_no,
        acc_trans_chlds.party_id,
        acc_trans_chlds.chld_narration,
        abs(sum (acc_trans_chlds.amount)) as amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
        join companies on companies.id=acc_trans_prnts.company_id

        where 
        acc_trans_prnts.trans_date >= '".$start_date."'
        and acc_trans_prnts.trans_date <= '".$end_date."'
        $company
        $head
        and acc_trans_chlds.deleted_at is null
        group by 
        companies.id,
        companies.code,
        acc_trans_prnts.id,
        acc_trans_prnts.trans_no,
        acc_trans_chlds.id,
        acc_trans_chlds.party_id,
        acc_chart_ctrl_heads.name,
        acc_chart_ctrl_heads.code,
        acc_trans_chlds.bill_no,
        acc_trans_chlds.chld_narration
        order by 
        companies.id"
        ))
        ->groupBy('company_code');
        $datas=array();
        foreach($monthRevenue as $company_name=>$value)
        {
            $amount=0;
            foreach($value as $row)
            {
                $amount+=number_format($row->amount,4,'.','');
                $row->amount=number_format($row->amount,4);
                array_push($datas,$row);
            }
            $subTot = collect(['company_code'=>'Sub Total','amount'=>number_format($amount,'0','.',','),'fin_fab'=>'']);
            array_push($datas,$subTot);
        }
        echo json_encode($datas);

    }
}