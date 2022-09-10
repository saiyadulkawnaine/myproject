<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;

class ReceiptsPaymentsAccountController extends Controller
{
    private $company;
    private $buyer;
    private $supplier;
	private $employee;
	public function __construct(
		CompanyRepository $company,
        BuyerRepository $buyer,
        SupplierRepository $supplier,
        EmployeeRepository $employee
	)
    {
        $this->company=$company;
        $this->buyer=$buyer;
        $this->supplier=$supplier;
        $this->employee=$employee;
		$this->middleware('auth');
		//$this->middleware('permission:view.prodgmtcapacityachievereports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
    	$date_to=date('Y-m-d');
    	return Template::loadView('Report.ReceiptsPaymentsAccount',['date_to'=>$date_to]);
    }

    private function getdata(){
        $trans_date_from=request('trans_date_from',0);
        $trans_date_to=request('trans_date_to',0);
        $month_inflows = collect(\DB::select("
        select m.company_id, m.head_name,m.id, sum(m.amount) as amount from (select 
        acc_trans_prnts.id as acc_trans_prnt_id,
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
        accchartctrlheads.id
        ELSE
        0
        END
        as id
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
        acc_trans_prnts.trans_date >= '".$trans_date_from."'
        and acc_trans_prnts.trans_date<= '".$trans_date_to."'
        and acc_chart_ctrl_heads.other_type_id in(1,2)

        and acc_trans_chlds.amount>0 
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.id,
        acc_trans_prnts.company_id,
        opposite_head.count_id,
        opposite_head.acc_chart_ctrl_head_id,
        accchartctrlheads.name,
        accchartctrlheads.id
        order by acc_trans_prnts.id) m
        group by m.company_id, m.head_name,m.id
        order by m.company_id"
        ));
        
        $month_inflow_arr=array();
        $month_inflow_com_total=array();
        $month_inflow_row_total=array();
        foreach($month_inflows as $month_inflow)
        {
            $index=$month_inflow->id."::".$month_inflow->head_name;
            $month_inflow_arr[$index][$month_inflow->company_id]=$month_inflow->amount;
            $month_inflow_com_total[$month_inflow->company_id][]=$month_inflow->amount;
            $month_inflow_row_total[$index][]=$month_inflow->amount;
        }



        


        $month_outflows = collect(\DB::select("
        select m.company_id, m.head_name,m.id, sum(m.amount) as amount from (select 
        acc_trans_prnts.id as acc_trans_prnt_id,
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
        accchartctrlheads.id
        ELSE
        0
        END
        as id
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
        acc_trans_prnts.trans_date >= '".$trans_date_from."'
        and acc_trans_prnts.trans_date<= '".$trans_date_to."'
        and acc_chart_ctrl_heads.other_type_id in(1,2)

        and acc_trans_chlds.amount<0 
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.id,
        acc_trans_prnts.company_id,
        opposite_head.count_id,
        opposite_head.acc_chart_ctrl_head_id,
        accchartctrlheads.name,
        accchartctrlheads.id
        order by acc_trans_prnts.id) m
        group by m.company_id, m.head_name,m.id
        order by m.id"
        ));
        
        $month_outflow_arr=array();
        $month_outflow_com_total=array();
        $month_outflow_row_total=array();
        foreach($month_outflows as $month_outflow)
        {
            $index=$month_outflow->id."::".$month_outflow->head_name;
            $month_outflow_arr[$index][$month_outflow->company_id]=$month_outflow->amount;
            $month_outflow_com_total[$month_outflow->company_id][]=$month_outflow->amount;
            $month_outflow_row_total[$index][]=$month_outflow->amount;
        }
        return [
            'month_inflow_arr'=>$month_inflow_arr,
            'month_inflow_com_total'=>$month_inflow_com_total,
            'month_inflow_row_total'=>$month_inflow_row_total,
            'month_outflow_arr'=>$month_outflow_arr,
            'month_outflow_com_total'=>$month_outflow_com_total,
            'month_outflow_row_total'=>$month_outflow_row_total,
        ];

    }
    
    public function reportData() {
        $data=$this->getdata();
    	return Template::loadView('Report.ReceiptsPaymentsAccountData',$data);
    }

    public function reportDataToday() {
        $data=$this->getdata();
        return Template::loadView('Report.ReceiptsPaymentsAccountDataToday',$data);
    }

    public function getReceipt(){
        //$trans_date=request('trans_date',0);
        $trans_date_from=request('trans_date_from',0);
        $trans_date_to=request('trans_date_to',0);

        $company_id=request('company_id',0);
        $head_id=request('head_id',0);
        $is_multiple=request('is_multiple',0);
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

        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
        $otherPartise=array_prepend(array_pluck($this->supplier->otherPartise(),'name','id'),'-Select-','');
        $employee=array_prepend(array_pluck($this->employee->get(),'name','id'),'','');
        if($is_multiple==0){
            $receipts = collect(\DB::select("
            select m.acc_trans_prnt_id,m.trans_no, m.head_name,m.head_code,m.id,m.chld_narration,m.party_id, m.control_name_id,sum(m.amount) as amount from 
            (
            select 
            acc_trans_prnts.id as acc_trans_prnt_id,
            acc_trans_prnts.trans_no,
            acc_trans_chlds.id,

            accchartctrlheads.name as head_name,
            accchartctrlheads.code as head_code,
            accchartctrlheads.control_name_id,
            opposite_head.acc_chart_ctrl_head_id,
            opposite_head.chld_narration,
            opposite_head.party_id,
            abs(sum(opposite_head.amount)) as amount

            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join(
            select 
            acc_trans_prnts.id as acc_trans_prnt_id,
            acc_trans_chlds.id,
            acc_trans_chlds.chld_narration,
            acc_trans_chlds.party_id,
            acc_chart_ctrl_heads.id as acc_chart_ctrl_head_id,
            abs(sum(acc_trans_chlds.amount)) as amount
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            and acc_trans_chlds.deleted_at is null 
            and acc_trans_chlds.amount < 0 
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            $company
            $head
            group by 
            acc_trans_prnts.id,
            acc_trans_chlds.id,
            acc_trans_chlds.chld_narration,
            acc_trans_chlds.party_id,
            acc_chart_ctrl_heads.id
            ) opposite_head on opposite_head.acc_trans_prnt_id=acc_trans_prnts.id
            join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
            where 
            acc_trans_prnts.trans_date >= '".$trans_date_from."'
            and acc_trans_prnts.trans_date<= '".$trans_date_to."'
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            and acc_trans_chlds.amount>0
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.trans_no,
            acc_trans_chlds.id,
            opposite_head.acc_chart_ctrl_head_id,
            opposite_head.chld_narration,
            opposite_head.party_id,
            accchartctrlheads.name,
            accchartctrlheads.id,
            accchartctrlheads.control_name_id,
            accchartctrlheads.code
            order by acc_trans_prnts.id
            ) m
            group by
            m.acc_trans_prnt_id, 
            m.trans_no, 
            m.head_name,
            m.head_code,
            m.id,
            m.chld_narration,
            m.party_id,
            m.control_name_id
            order by m.trans_no"
            ));
        }
        else{

            $receipts = collect(\DB::select("select m.acc_trans_prnt_id,m.trans_no, m.head_name,m.head_code,m.id,m.chld_narration,m.party_id, m.control_name_id,sum(m.amount) as amount from 
            (
            select 
            acc_trans_prnts.id as acc_trans_prnt_id,
            acc_trans_prnts.trans_no,
            acc_trans_chlds.id,

            accchartctrlheads.name as head_name,
            accchartctrlheads.code as head_code,
            accchartctrlheads.control_name_id,
            opposite_head.acc_chart_ctrl_head_id,
            opposite_head.chld_narration,
            opposite_head.party_id,
            abs(sum(opposite_head.amount)) as amount

            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            join(
            select 
            acc_trans_prnts.id as acc_trans_prnt_id,
            acc_trans_chlds.id,
            acc_trans_chlds.chld_narration,
            acc_trans_chlds.party_id,
            acc_chart_ctrl_heads.id as acc_chart_ctrl_head_id,
            abs(sum(acc_trans_chlds.amount)) as amount
            from 
            acc_trans_prnts
            join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
            join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
            join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
            and acc_trans_chlds.deleted_at is null 
            and acc_trans_chlds.amount < 0 
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            $company and 
            acc_trans_prnts.id in 
            (
            select 
            acc_trans_prnts.id as acc_trans_prnt_id
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
            and acc_chart_ctrl_heads.other_type_id not in(1,2)
            group by 
            acc_trans_prnts.id having count (acc_trans_chlds.id)>1
            ) opposite_head on opposite_head.id=acc_trans_prnts.id
            join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
            where 
            acc_trans_prnts.trans_date >= '".$trans_date_from."'
            and acc_trans_prnts.trans_date<= '".$trans_date_to."'
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            and acc_trans_chlds.amount>0 
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.company_id,
            opposite_head.count_id,
            opposite_head.acc_chart_ctrl_head_id,
            accchartctrlheads.name,
            accchartctrlheads.id
            )
            group by 
            acc_trans_prnts.id,
            acc_trans_chlds.id,
            acc_trans_chlds.chld_narration,
            acc_trans_chlds.party_id,
            acc_chart_ctrl_heads.id
            ) opposite_head on opposite_head.acc_trans_prnt_id=acc_trans_prnts.id
            join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
            where 
            acc_trans_prnts.trans_date >= '".$trans_date_from."'
            and acc_trans_prnts.trans_date<= '".$trans_date_to."'
            and acc_chart_ctrl_heads.other_type_id in(1,2)
            and acc_trans_chlds.amount>0
            and acc_trans_chlds.deleted_at is null
            group by 
            acc_trans_prnts.id,
            acc_trans_prnts.trans_no,
            acc_trans_chlds.id,
            opposite_head.acc_chart_ctrl_head_id,
            opposite_head.chld_narration,
            opposite_head.party_id,
            accchartctrlheads.name,
            accchartctrlheads.id,
            accchartctrlheads.control_name_id,
            accchartctrlheads.code
            order by acc_trans_prnts.id
            ) m
            group by
            m.acc_trans_prnt_id, 
            m.trans_no, 
            m.head_name,
            m.head_code,
            m.id,
            m.chld_narration,
            m.party_id,
            m.control_name_id
            order by m.trans_no
            "
            ));

        }
        $data=$receipts->map(function($receipts) use($buyer,$supplier,$otherPartise,$employee){
        if($receipts->control_name_id ==1 || $receipts->control_name_id ==2 || $receipts->control_name_id ==10 || $receipts->control_name_id ==15 || $receipts->control_name_id == 20 || $receipts->control_name_id ==35 || $receipts->control_name_id == 62)
        {//purchase
        $receipts->party_name =isset($supplier[$receipts->party_id])?$supplier[$receipts->party_id]:'';
        }

        else if($receipts->control_name_id ==5 || $receipts->control_name_id ==6 || $receipts->control_name_id ==30 || $receipts->control_name_id ==31 || $receipts->control_name_id == 40 || $receipts->control_name_id ==45 || $receipts->control_name_id ==50 || $receipts->control_name_id ==60)
        {//sales

        $receipts->party_name =isset($buyer[$receipts->party_id])?$buyer[$receipts->party_id]:'';

        }

        else if ($receipts->control_name_id==38)
        {//other Party
        $receipts->party_name =isset($otherPartise[$receipts->party_id])?$otherPartise[$receipts->party_id]:'';
        }
        return $receipts;
        })
        ;
        echo json_encode($data);
    }
    
    public function getPayment(){
        //$trans_date=request('trans_date',0);
        $trans_date_from=request('trans_date_from',0);
        $trans_date_to=request('trans_date_to',0);
        $company_id=request('company_id',0);
        $head_id=request('head_id',0);
        $is_multiple=request('is_multiple',0);
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

        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
        $otherPartise=array_prepend(array_pluck($this->supplier->otherPartise(),'name','id'),'-Select-','');
        $employee=array_prepend(array_pluck($this->employee->get(),'name','id'),'','');
        

        if($is_multiple==0)
        {

        $receipts = collect(\DB::select("
        select m.acc_trans_prnt_id,m.trans_no, m.head_name,m.head_code,m.id,m.chld_narration,m.party_id, m.employee_id,m.control_name_id,sum(m.amount) as amount from 
        (
        select 
        acc_trans_prnts.id as acc_trans_prnt_id,
        acc_trans_prnts.trans_no,
        acc_trans_chlds.id,

        accchartctrlheads.name as head_name,
        accchartctrlheads.code as head_code,
        accchartctrlheads.control_name_id,
        opposite_head.acc_chart_ctrl_head_id,
        opposite_head.chld_narration,
        opposite_head.party_id,
        opposite_head.employee_id,
        abs(sum(opposite_head.amount)) as amount

        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
        join(
                select 
                acc_trans_prnts.id as acc_trans_prnt_id,
                acc_trans_chlds.id,
                acc_trans_chlds.chld_narration,
                acc_trans_chlds.party_id,
                acc_trans_chlds.employee_id,
                acc_chart_ctrl_heads.id as acc_chart_ctrl_head_id,
                abs(sum(acc_trans_chlds.amount)) as amount
                from 
                acc_trans_prnts
                join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
                join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
                join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
                and acc_trans_chlds.deleted_at is null 
                and acc_trans_chlds.amount > 0 
                and acc_chart_ctrl_heads.other_type_id not in(1,2)
                $company
                $head
                group by 
                acc_trans_prnts.id,
                acc_trans_chlds.id,
                acc_trans_chlds.chld_narration,
                acc_trans_chlds.party_id,
                acc_trans_chlds.employee_id,
                acc_chart_ctrl_heads.id
        ) opposite_head on opposite_head.acc_trans_prnt_id=acc_trans_prnts.id
        join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
        where 
        acc_trans_prnts.trans_date >= '".$trans_date_from."'
        and acc_trans_prnts.trans_date<= '".$trans_date_to."'
        and acc_chart_ctrl_heads.other_type_id in(1,2)
        and acc_trans_chlds.amount<0
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.id,
        acc_trans_prnts.trans_no,
        acc_trans_chlds.id,
        opposite_head.acc_chart_ctrl_head_id,
        opposite_head.chld_narration,
        opposite_head.party_id,
        opposite_head.employee_id,
        accchartctrlheads.name,
        accchartctrlheads.id,
        accchartctrlheads.control_name_id,
        accchartctrlheads.code
        order by acc_trans_prnts.id
        ) m
        group by 
        m.acc_trans_prnt_id,
        m.trans_no, 
        m.head_name,
        m.head_code,
        m.id,
        m.chld_narration,
        m.party_id,
        m.employee_id,
        m.control_name_id
        order by m.trans_no"
        ));
        }
        else
        {
            //echo "ddddd";

            $receipts = collect(\DB::select("
        select m.acc_trans_prnt_id,m.trans_no, m.head_name,m.head_code,m.id,m.chld_narration,m.party_id, m.employee_id,m.control_name_id,sum(m.amount) as amount from 
        (
        select 
        acc_trans_prnts.id as acc_trans_prnt_id,
        acc_trans_prnts.trans_no,
        acc_trans_chlds.id,

        accchartctrlheads.name as head_name,
        accchartctrlheads.code as head_code,
        accchartctrlheads.control_name_id,
        opposite_head.acc_chart_ctrl_head_id,
        opposite_head.chld_narration,
        opposite_head.party_id,
        opposite_head.employee_id,
        abs(sum(opposite_head.amount)) as amount

        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
        join(
                select 
                acc_trans_prnts.id as acc_trans_prnt_id,
                acc_trans_chlds.id,
                acc_trans_chlds.chld_narration,
                acc_trans_chlds.party_id,
                acc_trans_chlds.employee_id,
                acc_chart_ctrl_heads.id as acc_chart_ctrl_head_id,
                abs(sum(acc_trans_chlds.amount)) as amount
                from 
                acc_trans_prnts
                join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
                join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
                join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
                and acc_trans_chlds.deleted_at is null 
                and acc_trans_chlds.amount > 0 
                and acc_chart_ctrl_heads.other_type_id not in(1,2) 
                $company and 
                acc_trans_prnts.id in
                (
                    select 
                    acc_trans_prnts.id as acc_trans_prnt_id
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
                    and acc_chart_ctrl_heads.other_type_id not in(1,2)
                    group by 
                    acc_trans_prnts.id having count (acc_trans_chlds.id)>1
                    ) opposite_head on opposite_head.id=acc_trans_prnts.id
                    join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
                    where 
                    acc_trans_prnts.trans_date >= '".$trans_date_from."'
                    and acc_trans_prnts.trans_date<= '".$trans_date_to."'
                    and acc_chart_ctrl_heads.other_type_id in(1,2)
                    and acc_trans_chlds.amount<0 
                    and acc_trans_chlds.deleted_at is null
                    group by 
                    acc_trans_prnts.id,
                    acc_trans_prnts.company_id,
                    opposite_head.count_id,
                    opposite_head.acc_chart_ctrl_head_id,
                    accchartctrlheads.name,
                    accchartctrlheads.id
                    
                    )
                group by 
                acc_trans_prnts.id,
                acc_trans_chlds.id,
                acc_trans_chlds.chld_narration,
                acc_trans_chlds.party_id,
                acc_trans_chlds.employee_id,
                acc_chart_ctrl_heads.id
        ) opposite_head on opposite_head.acc_trans_prnt_id=acc_trans_prnts.id
        join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
        where 
        acc_trans_prnts.trans_date >= '".$trans_date_from."'
        and acc_trans_prnts.trans_date<= '".$trans_date_to."'
        and acc_chart_ctrl_heads.other_type_id in(1,2)
        and acc_trans_chlds.amount<0
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.id,
        acc_trans_prnts.trans_no,
        acc_trans_chlds.id,
        opposite_head.acc_chart_ctrl_head_id,
        opposite_head.chld_narration,
        opposite_head.party_id,
        opposite_head.employee_id,
        accchartctrlheads.name,
        accchartctrlheads.id,
        accchartctrlheads.control_name_id,
        accchartctrlheads.code
        order by acc_trans_prnts.id
        ) m
        group by
        m.acc_trans_prnt_id, 
        m.trans_no, 
        m.head_name,
        m.head_code,
        m.id,
        m.chld_narration,
        m.party_id,
        m.employee_id,
        m.control_name_id
        order by m.trans_no"
        ));

        }
        $data=$receipts->map(function($receipts) use($buyer,$supplier,$otherPartise,$employee){
            $receipts->party_name='';
            if($receipts->control_name_id ==1 || $receipts->control_name_id ==2 || $receipts->control_name_id ==10 || $receipts->control_name_id ==15 || $receipts->control_name_id == 20 || $receipts->control_name_id ==35 || $receipts->control_name_id == 62)
            {//purchase
            $receipts->party_name =isset($supplier[$receipts->party_id])?$supplier[$receipts->party_id]:'';
            }

            else if($receipts->control_name_id ==5 || $receipts->control_name_id ==6 || $receipts->control_name_id ==30 || $receipts->control_name_id ==31 || $receipts->control_name_id == 40 || $receipts->control_name_id ==45 || $receipts->control_name_id ==50 || $receipts->control_name_id ==60)
            {//sales

            $receipts->party_name =isset($buyer[$receipts->party_id])?$buyer[$receipts->party_id]:'';

            }

            else if ($receipts->control_name_id==38)
            {//other Party
            $receipts->party_name =isset($otherPartise[$receipts->party_id])?$otherPartise[$receipts->party_id]:'';
            }
            $receipts->employee_name =isset($employee[$receipts->employee_id])?$employee[$receipts->employee_id]:'';
            if(!$receipts->party_name){
               $receipts->party_name= $receipts->employee_name;
            }
          return $receipts;
        })
        ;
        echo json_encode($data);
    }

    public function getMultipleHeadPayment()
    {
        //$trans_date=request('trans_date',0);
        $trans_date_from=request('trans_date_from',0);
        $trans_date_to=request('trans_date_to',0);
        $company_id=request('company_id',0);
        $head_id=request('head_id',0);
        $is_multiple=request('is_multiple',0);
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

        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
        $otherPartise=array_prepend(array_pluck($this->supplier->otherPartise(),'name','id'),'-Select-','');
        $employee=array_prepend(array_pluck($this->employee->get(),'name','id'),'','');
        $receipts = collect(\DB::select("
        select 
        acc_trans_prnts.id as acc_trans_prnt_id,
        acc_trans_prnts.trans_no,
        acc_trans_chlds.id,
        acc_trans_chlds.party_id,
        acc_trans_chlds.employee_id,
        acc_trans_chlds.chld_narration,
        acc_chart_ctrl_heads.other_type_id,
        acc_chart_ctrl_heads.control_name_id,
        acc_chart_ctrl_heads.name as head_name,
        acc_chart_ctrl_heads.code as head_code,
        acc_trans_chlds.amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
        where 
        acc_trans_prnts.trans_date >= '".$trans_date_from."'
        and acc_trans_prnts.trans_date<= '".$trans_date_to."'
        and acc_trans_prnts.id in(
        select 
        acc_trans_prnts.id as acc_trans_prnt_id
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
        and acc_chart_ctrl_heads.other_type_id not in(1,2)
        $company
        group by 
        acc_trans_prnts.id having count (acc_trans_chlds.id)>1
        ) opposite_head on opposite_head.id=acc_trans_prnts.id
        join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
        where 
        acc_trans_prnts.trans_date >= '".$trans_date_from."'
        and acc_trans_prnts.trans_date<= '".$trans_date_to."'
        and acc_chart_ctrl_heads.other_type_id in(1,2)
        and acc_trans_chlds.amount<0 
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.id,
        acc_trans_prnts.company_id,
        opposite_head.count_id,
        opposite_head.acc_chart_ctrl_head_id,
        accchartctrlheads.name,
        accchartctrlheads.id
        )
        and acc_trans_chlds.deleted_at is null
        order by acc_trans_prnts.id
        "
        ));
        $data=$receipts->map(function($receipts) use($buyer,$supplier,$otherPartise,$employee){
        $receipts->party_name='';
        if($receipts->control_name_id ==1 || $receipts->control_name_id ==2 || $receipts->control_name_id ==10 || $receipts->control_name_id ==15 || $receipts->control_name_id == 20 || $receipts->control_name_id ==35 || $receipts->control_name_id == 62)
        {//purchase
        $receipts->party_name =isset($supplier[$receipts->party_id])?$supplier[$receipts->party_id]:'';
        }

        else if($receipts->control_name_id ==5 || $receipts->control_name_id ==6 || $receipts->control_name_id ==30 || $receipts->control_name_id ==31 || $receipts->control_name_id == 40 || $receipts->control_name_id ==45 || $receipts->control_name_id ==50 || $receipts->control_name_id ==60)
        {//sales

        $receipts->party_name =isset($buyer[$receipts->party_id])?$buyer[$receipts->party_id]:'';

        }

        else if ($receipts->control_name_id==38)
        {//other Party
        $receipts->party_name =isset($otherPartise[$receipts->party_id])?$otherPartise[$receipts->party_id]:'';
        }
        $receipts->employee_name =isset($employee[$receipts->employee_id])?$employee[$receipts->employee_id]:'';
        if(!$receipts->party_name){
        $receipts->party_name= $receipts->employee_name;
        }
        $receipts->debit_amount=0;
        $receipts->credit_amount=0;

        if($receipts->amount>0){
            $receipts->debit_amount=$receipts->amount;

        }else{
            $receipts->credit_amount=$receipts->amount*-1;
        }
        if($receipts->other_type_id==1 || $receipts->other_type_id==2){
            $receipts->pay_amount=$receipts->credit_amount;
        }
        else{
           $receipts->pay_amount=0; 
        }
        return $receipts;
        })->groupBy('acc_trans_prnt_id');
        
        $datas=array();
        foreach($data as $acc_trans_prnt_id=>$value)
        {
            $debit_amount=0;
            $credit_amount=0;
            $pay_amount=0;
            foreach($value as $row)
            {
                $debit_amount+=$row->debit_amount;
                $credit_amount+=$row->credit_amount;
                $pay_amount+=$row->pay_amount;
                $row->debit_amount=number_format($row->debit_amount,0);
                $row->credit_amount=number_format($row->credit_amount,0);
                $row->pay_amount=number_format($row->pay_amount,0);
                array_push($datas,$row);
            }
            $subTot = collect(['party_name'=>'Sub Total','debit_amount'=>number_format($debit_amount,'0','.',','),'credit_amount'=>number_format($credit_amount,'0','.',','),'pay_amount'=>number_format($pay_amount,'0','.',',')]);
            array_push($datas,$subTot);
        }

        echo json_encode($datas);
    }

    public function getMultipleHeadReceipt()
    {
        //$trans_date=request('trans_date',0);
        $trans_date_from=request('trans_date_from',0);
        $trans_date_to=request('trans_date_to',0);
        $company_id=request('company_id',0);
        $head_id=request('head_id',0);
        $is_multiple=request('is_multiple',0);
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

        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
        $otherPartise=array_prepend(array_pluck($this->supplier->otherPartise(),'name','id'),'-Select-','');
        $employee=array_prepend(array_pluck($this->employee->get(),'name','id'),'','');
        $receipts = collect(\DB::select("
        select 
        acc_trans_prnts.id as acc_trans_prnt_id,
        acc_trans_prnts.trans_no,
        acc_trans_chlds.id,
        acc_trans_chlds.party_id,
        acc_trans_chlds.employee_id,
        acc_trans_chlds.chld_narration,
        acc_chart_ctrl_heads.other_type_id,
        acc_chart_ctrl_heads.control_name_id,
        acc_chart_ctrl_heads.name as head_name,
        acc_chart_ctrl_heads.code as head_code,
        acc_trans_chlds.amount
        from 
        acc_trans_prnts
        join acc_trans_chlds on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
        join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
        where 
        acc_trans_prnts.trans_date >= '".$trans_date_from."'
        and acc_trans_prnts.trans_date<= '".$trans_date_to."'
        and acc_trans_prnts.id in(
        select 
        acc_trans_prnts.id as acc_trans_prnt_id
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
        and acc_chart_ctrl_heads.other_type_id not in(1,2)
        $company
        group by 
        acc_trans_prnts.id having count (acc_trans_chlds.id)>1
        ) opposite_head on opposite_head.id=acc_trans_prnts.id
        join acc_chart_ctrl_heads accchartctrlheads on accchartctrlheads.id=opposite_head.acc_chart_ctrl_head_id
        where 
        acc_trans_prnts.trans_date >= '".$trans_date_from."'
        and acc_trans_prnts.trans_date<= '".$trans_date_to."'
        and acc_chart_ctrl_heads.other_type_id in(1,2)
        and acc_trans_chlds.amount>0 
        and acc_trans_chlds.deleted_at is null
        group by 
        acc_trans_prnts.id,
        acc_trans_prnts.company_id,
        opposite_head.count_id,
        opposite_head.acc_chart_ctrl_head_id,
        accchartctrlheads.name,
        accchartctrlheads.id
        )
        and acc_trans_chlds.deleted_at is null
        order by acc_trans_prnts.id
        "
        ));
        $data=$receipts->map(function($receipts) use($buyer,$supplier,$otherPartise,$employee){
        $receipts->party_name='';
        if($receipts->control_name_id ==1 || $receipts->control_name_id ==2 || $receipts->control_name_id ==10 || $receipts->control_name_id ==15 || $receipts->control_name_id == 20 || $receipts->control_name_id ==35 || $receipts->control_name_id == 62)
        {//purchase
        $receipts->party_name =isset($supplier[$receipts->party_id])?$supplier[$receipts->party_id]:'';
        }

        else if($receipts->control_name_id ==5 || $receipts->control_name_id ==6 || $receipts->control_name_id ==30 || $receipts->control_name_id ==31 || $receipts->control_name_id == 40 || $receipts->control_name_id ==45 || $receipts->control_name_id ==50 || $receipts->control_name_id ==60)
        {//sales

        $receipts->party_name =isset($buyer[$receipts->party_id])?$buyer[$receipts->party_id]:'';

        }

        else if ($receipts->control_name_id==38)
        {//other Party
        $receipts->party_name =isset($otherPartise[$receipts->party_id])?$otherPartise[$receipts->party_id]:'';
        }
        $receipts->employee_name =isset($employee[$receipts->employee_id])?$employee[$receipts->employee_id]:'';
        if(!$receipts->party_name){
        $receipts->party_name= $receipts->employee_name;
        }
        $receipts->debit_amount=0;
        $receipts->credit_amount=0;

        if($receipts->amount>0){
            $receipts->debit_amount=$receipts->amount;

        }else{
            $receipts->credit_amount=$receipts->amount*-1;
        }
        if($receipts->other_type_id==1 || $receipts->other_type_id==2){
            $receipts->pay_amount=$receipts->credit_amount;
        }
        else{
           $receipts->pay_amount=0; 
        }
        return $receipts;
        })->groupBy('acc_trans_prnt_id');
        
        $datas=array();
        foreach($data as $acc_trans_prnt_id=>$value)
        {
            $debit_amount=0;
            $credit_amount=0;
            $pay_amount=0;
            foreach($value as $row)
            {
                $debit_amount+=$row->debit_amount;
                $credit_amount+=$row->credit_amount;
                $pay_amount+=$row->pay_amount;
                $row->debit_amount=number_format($row->debit_amount,0);
                $row->credit_amount=number_format($row->credit_amount,0);
                $row->pay_amount=number_format($row->pay_amount,0);
                array_push($datas,$row);
            }
            $subTot = collect(['party_name'=>'Sub Total','debit_amount'=>number_format($debit_amount,'0','.',','),'credit_amount'=>number_format($credit_amount,'0','.',','),'pay_amount'=>number_format($pay_amount,'0','.',',')]);
            array_push($datas,$subTot);
        }

        echo json_encode($datas);
    }
}