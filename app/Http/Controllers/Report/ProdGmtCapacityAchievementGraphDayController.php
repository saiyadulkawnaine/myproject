<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use App\Repositories\Contracts\Account\AccBepRepository;
use App\Repositories\Contracts\HRM\EmployeeAttendenceRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;

class ProdGmtCapacityAchievementGraphDayController extends Controller
{
	private $no_of_days;
	private $exch_rate;
	private $subsection;
	private $wstudylinesetup;
	private $prodgmtsewing;
	private $accbep;
	private $attendence;
	private $salesorder;
	public function __construct(
		SubsectionRepository $subsection,
        WstudyLineSetupRepository $wstudylinesetup,
		ProdGmtSewingRepository $prodgmtsewing,
		AccBepRepository $accbep,
		EmployeeAttendenceRepository $attendence,
		SalesOrderRepository $salesorder
	)
    {
		$this->no_of_days                = 26;
		$this->exch_rate                 = 82;
		$this->subsection                = $subsection;
		$this->wstudylinesetup           = $wstudylinesetup;
		$this->prodgmtsewing             = $prodgmtsewing;
		$this->accbep                    = $accbep;
		$this->attendence                = $attendence;
		$this->salesorder                = $salesorder;
		$this->middleware('auth');
		//$this->middleware('permission:view.prodgmtcapacityachievereports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
        $from=date('Y-m')."-01";
        $to=date('Y-m-t',strtotime($from));
    	return Template::loadView('Report.CapacityAchivmentGraphDay', ['from'=>$from,'to'=>$to]);
    }
    private function makeChartSew($from_day,$to_day,$companies,$caps,$boks,$tgts,$prods){
        $configArr = [];

        for($i=$from_day;$i<=$to_day;$i++){
        $configArr[$i]=['Cap'=>0,'Bok'=>0,'Prod'=>0,'Tgt'=>0];
        }
        $com=[
            'All-Company'=>$configArr,
        ];
        
        foreach($companies as $company){
        $com[$company->company_code]=$configArr;
        }

       /* for($x=$from_day;$x<=$to_day;$x++){
            foreach($caps as $cap)
            {
                $com['All-Company'][$x]['Cap']+=$cap->qty;
                $com[$cap->company_code][$x]['Cap']+=$cap->qty;
            }
        }*/

        foreach($caps as $cap){
            $c=date('j',strtotime($cap->capacity_date));
            $com['All-Company'][$c]['Cap']+=$cap->qty;
            $com[$cap->company_code][$c]['Cap']+=$cap->qty;
        }

        for($y=$from_day;$y<=$to_day;$y++)
        {
            foreach($boks as $bok){
                $com['All-Company'][$y]['Bok']+=$bok->qty;
                $com[$bok->company_code][$y]['Bok']+=$bok->qty;
            }
        }

        foreach($tgts as $tgt){
            $tgt_from_day=date('j',strtotime($tgt->from_date));
            $tgt_to_day=date('j',strtotime($tgt->to_date));

            for($t=$tgt_from_day;$t<=$tgt_to_day;$t++)
            {
                $com['All-Company'][$t]['Tgt']+=$tgt->qty;
                $com[$tgt->company_code][$t]['Tgt']+=$tgt->qty;
            }
        }

        foreach($prods as $prod){
            $p=date('j',strtotime($prod->prod_date));
            $com['All-Company'][$p]['Prod']+=$prod->qty;
            $com[$prod->company_code][$p]['Prod']+=$prod->qty;
        }

        foreach($com as $company_id=>$rows){
            $comdata=[];
            $comdatas=[];
            foreach($rows as $key=>$value){
                $comdata['name']=$key;
                $comdata['cap']=$value['Cap'];
                $comdata['bok']=$value['Bok'];
                $comdata['prod']=$value['Prod'];
                $comdata['tgt']=$value['Tgt'];
                array_push($comdatas,$comdata);
            }
            $com[$company_id]=$comdatas;
        }
        echo json_encode($com);
    }

    private function makeChart($from_day,$to_day,$companies,$caps,$boks,$tgts,$prods){
        $configArr = [];

        for($i=$from_day;$i<=$to_day;$i++){
        $configArr[$i]=['Cap'=>0,'Bok'=>0,'Prod'=>0,'Tgt'=>0];
        }
        $com=[
            'All-Company'=>$configArr,
        ];
        
        foreach($companies as $company){
        $com[$company->company_code]=$configArr;
        }

        for($x=$from_day;$x<=$to_day;$x++){
            foreach($caps as $cap)
            {
                $com['All-Company'][$x]['Cap']+=$cap->qty;
                $com[$cap->company_code][$x]['Cap']+=$cap->qty;
            }
        }

        for($y=$from_day;$y<=$to_day;$y++)
        {
            foreach($boks as $bok){
                $com['All-Company'][$y]['Bok']+=$bok->qty;
                $com[$bok->company_code][$y]['Bok']+=$bok->qty;
            }
        }

        foreach($tgts as $tgt){
            $tgt_from_day=date('j',strtotime($tgt->from_date));
            $tgt_to_day=date('j',strtotime($tgt->to_date));

            for($t=$tgt_from_day;$t<=$tgt_to_day;$t++)
            {
                $com['All-Company'][$t]['Tgt']+=$tgt->qty;
                $com[$tgt->company_code][$t]['Tgt']+=$tgt->qty;
            }
        }

        foreach($prods as $prod){
            $p=date('j',strtotime($prod->prod_date));
            $com['All-Company'][$p]['Prod']+=$prod->qty;
            $com[$prod->company_code][$p]['Prod']+=$prod->qty;
        }

        foreach($com as $company_id=>$rows){
            $comdata=[];
            $comdatas=[];
            foreach($rows as $key=>$value){
                $comdata['name']=$key;
                $comdata['cap']=$value['Cap'];
                $comdata['bok']=$value['Bok'];
                $comdata['prod']=$value['Prod'];
                $comdata['tgt']=$value['Tgt'];
                array_push($comdatas,$comdata);
            }
            $com[$company_id]=$comdatas;
        }
        echo json_encode($com);
    }

    public function getGraph()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
        $from_month_year=strtotime(date('m-Y',strtotime($from_date)));
        $to_month_year=strtotime(date('m-Y',strtotime($from_date)));
        if($from_month_year !=  $to_month_year)
        {
        echo "Cross Date not allowed"; 
        die;

        }
        $from_month=date('m',strtotime($from_date));
        $to_month=date('m',strtotime($to_date));

        $from_day=date('j',strtotime($from_date));
        $to_day=date('j',strtotime($to_date));


        $companies = collect(
        \DB::select("
        select companies.id,companies.code as company_code
        from 
        companies
        where companies.sew_effic_per is not null
        order by companies.id
        ")
        );




        /*$caps = collect(
        \DB::select("
        select company_subsections.company_id,companies.code as company_code,sum(subsections.qty) as qty
        from 
        subsections
        join company_subsections on company_subsections.subsection_id=subsections.id
        join companies on companies.id=company_subsections.company_id
        where subsections.is_treat_sewing_line=1
        and subsections.projected_line_id=0
        and subsections.status_id=1
        and subsections.deleted_at is null
        and company_subsections.deleted_at is null
        and companies.sew_effic_per is not null
        group by company_subsections.company_id,companies.code
        ")
        );*/
        $caps = collect(
        \DB::select("
			select
			sewing_capacities.company_id,
			companies.code as company_code,
			sewing_capacity_dates.capacity_date,
			to_char(sewing_capacity_dates.capacity_date, 'Mon') as cap_month,
			to_char(sewing_capacity_dates.capacity_date, 'MM') as cap_month_no,
			to_char(sewing_capacity_dates.capacity_date, 'yy') as cap_year,
			sum(sewing_capacity_dates.prod_cap_pcs) as qty
			FROM sewing_capacities
			join sewing_capacity_dates on sewing_capacity_dates.sewing_capacity_id = sewing_capacities.id
			join companies on companies.id=sewing_capacities.company_id
			where 
			sewing_capacity_dates.capacity_date>=? 
			and sewing_capacity_dates.capacity_date <= ?
			group by
			sewing_capacities.company_id,
			companies.code,
			sewing_capacity_dates.capacity_date
        ",[$from_date,$to_date])
        );




        $boks = collect(
        \DB::select("
        select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty)/26 as qty from (
        SELECT 
        target_transfers.produced_company_id as company_id,
        companies.code as company_code,
        target_transfers.date_to,
        to_char(target_transfers.date_to, 'Mon') as rcv_month,
        to_char(target_transfers.date_to, 'MM') as rcv_month_no,
        to_char(target_transfers.date_to, 'yy') as rcv_year,
        target_transfers.qty
        FROM target_transfers
        join companies on companies.id=target_transfers.produced_company_id
        where target_transfers.process_id=8 and target_transfers.deleted_at is null and companies.sew_effic_per is not null
        ) m where m.rcv_month_no=".$to_month." group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year 
        ")
        );



        $tgts = collect(
        \DB::select("
        select 
        wstudy_line_setups.id,
        wstudy_line_setups.company_id,
        companies.code as company_code,
        wstudy_line_setup_dtls.working_hour,
        wstudy_line_setup_dtls.overtime_hour,
        wstudy_line_setup_dtls.target_per_hour,
        wstudy_line_setup_dtls.from_date,
        wstudy_line_setup_dtls.to_date,
        wstudy_line_setup_dtls.target_per_hour*(wstudy_line_setup_dtls.working_hour+wstudy_line_setup_dtls.overtime_hour) as qty

        from wstudy_line_setups
        join wstudy_line_setup_dtls on wstudy_line_setups.id=wstudy_line_setup_dtls.wstudy_line_setup_id
        join companies on companies.id=wstudy_line_setups.company_id

        where 
        wstudy_line_setup_dtls.from_date>= ? and
        wstudy_line_setup_dtls.to_date<= ?  and 
        wstudy_line_setup_dtls.to_date >= wstudy_line_setup_dtls.from_date and
        wstudy_line_setups.deleted_at is null and
        wstudy_line_setup_dtls.deleted_at is null and 
        companies.sew_effic_per is not null
        ",[$from_date,$to_date])
        );

        /*$prods = collect(
        \DB::select("
        select 
        sales_orders.produced_company_id as company_id,
        companies.code as company_code,
        prod_gmt_sewings.sew_qc_date as prod_date,
        to_char(prod_gmt_sewings.sew_qc_date, 'Mon') as rcv_month,
        to_char(prod_gmt_sewings.sew_qc_date, 'MM') as rcv_month_no,
        to_char(prod_gmt_sewings.sew_qc_date, 'yy') as rcv_year,
        prod_gmt_sewing_qties.qty
        FROM prod_gmt_sewings
        join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
        join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id 
        join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id
        join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join companies on companies.id=sales_orders.produced_company_id
        join jobs on jobs.id = sales_orders.job_id
        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
        and sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id
        join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
        join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
        where 
        prod_gmt_sewings.sew_qc_date>=? and 
        prod_gmt_sewings.sew_qc_date<=?  and 
        companies.sew_effic_per is not null
        group by 
        sales_orders.produced_company_id,
        companies.code,
        prod_gmt_sewing_qties.id,
        prod_gmt_sewings.sew_qc_date,
        prod_gmt_sewing_qties.qty 
        ",[$from_date,$to_date])
        );*/

        $prods = collect(
        \DB::select("
        select 
        wstudy_line_setups.company_id,
        companies.code as company_code,
        prod_gmt_sewings.sew_qc_date as prod_date,
        to_char(prod_gmt_sewings.sew_qc_date, 'Mon') as rcv_month,
        to_char(prod_gmt_sewings.sew_qc_date, 'MM') as rcv_month_no,
        to_char(prod_gmt_sewings.sew_qc_date, 'yy') as rcv_year,
        prod_gmt_sewing_qties.qty
        FROM prod_gmt_sewings
        join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
        join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id 
        join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id
        join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join companies on companies.id=wstudy_line_setups.company_id
        join jobs on jobs.id = sales_orders.job_id
        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
        and sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id

        join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
        join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
        where 
        prod_gmt_sewings.sew_qc_date>=? and 
        prod_gmt_sewings.sew_qc_date<=?  and 
        companies.sew_effic_per is not null
        group by 
        wstudy_line_setups.company_id,
        companies.code,
        prod_gmt_sewing_qties.id,
        prod_gmt_sewings.sew_qc_date,
        prod_gmt_sewing_qties.qty 
        ",[$from_date,$to_date])
        );
        $this->makeChartSew($from_day,$to_day,$companies,$caps,$boks,$tgts,$prods);
    }

    public function getGraphCut()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
        $from_month_year=strtotime(date('m-Y',strtotime($from_date)));
        $to_month_year=strtotime(date('m-Y',strtotime($from_date)));
        if($from_month_year !=  $to_month_year)
        {
        echo "Cross Date not allowed"; 
        die;

        }
        $from_month=date('m',strtotime($from_date));
        $to_month=date('m',strtotime($to_date));

        $from_day=date('j',strtotime($from_date));
        $to_day=date('j',strtotime($to_date));

        $companies = collect(
        \DB::select("
        select companies.id,companies.code as company_code
        from 
        companies
        where companies.cutting_capacity_qty is not null
        order by companies.id
        ")
        );

        $caps = collect(
        \DB::select("
        select companies.id as company_id,companies.code as company_code,companies.cutting_capacity_qty as qty
        from 
        companies
        where companies.cutting_capacity_qty is not null
        ")
        );


        $boks = collect(
        \DB::select("
        select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty)/26 as qty from (
        SELECT 
        target_transfers.produced_company_id as company_id,
        companies.code as company_code,
        target_transfers.date_to,
        to_char(target_transfers.date_to, 'Mon') as rcv_month,
        to_char(target_transfers.date_to, 'MM') as rcv_month_no,
        to_char(target_transfers.date_to, 'yy') as rcv_year,
        target_transfers.qty
        FROM target_transfers
        join companies on companies.id=target_transfers.produced_company_id
        where target_transfers.process_id=5 and target_transfers.deleted_at is null and companies.cutting_capacity_qty is not null
        ) m where m.rcv_month_no=".$to_month." group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year 
        ")
        );



        $tgts = collect(
        \DB::select("
        select 
        day_target_transfers.produced_company_id as company_id,
        companies.code as company_code,
        day_target_transfers.target_date as from_date,
        day_target_transfers.target_date as to_date,
        day_target_transfers.process_id,
        sum(day_target_transfers.qty) as qty
        from 
        day_target_transfers
        join companies on companies.id=day_target_transfers.produced_company_id
        where 
        day_target_transfers.target_date>= ?  and 
        day_target_transfers.target_date<= ?  and 
        day_target_transfers.process_id =5 and
        companies.cutting_capacity_qty is not null and
        day_target_transfers.deleted_at is null
        group by
        day_target_transfers.produced_company_id,
        companies.code,
        day_target_transfers.target_date,
        day_target_transfers.process_id 
        ",[$from_date,$to_date])
        );




        $prods = collect(
        \DB::select("
        select 
        suppliers.company_id,
        companies.code as company_code,
        prod_gmt_cuttings.cut_qc_date as prod_date,
        to_char(prod_gmt_cuttings.cut_qc_date, 'Mon') as rcv_month,
        to_char(prod_gmt_cuttings.cut_qc_date, 'MM') as rcv_month_no,
        to_char(prod_gmt_cuttings.cut_qc_date, 'yy') as rcv_year,
        prod_gmt_cutting_qties.qty
        FROM prod_gmt_cuttings
        join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
        join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join jobs on jobs.id = sales_orders.job_id
        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
        join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
        join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
        join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
        join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id  
        join companies on companies.id=suppliers.company_id
        where prod_gmt_cuttings.cut_qc_date>=? and 
        prod_gmt_cuttings.cut_qc_date<=? and
        companies.cutting_capacity_qty is not null
        group by 
        suppliers.company_id,
        companies.code,
        prod_gmt_cutting_qties.id,
        prod_gmt_cuttings.cut_qc_date,
        prod_gmt_cutting_qties.qty 
        ",[$from_date,$to_date])
        );
        $this->makeChart($from_day,$to_day,$companies,$caps,$boks,$tgts,$prods);
    }

    public function getGraphSp()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
        $from_month_year=strtotime(date('m-Y',strtotime($from_date)));
        $to_month_year=strtotime(date('m-Y',strtotime($from_date)));
        if($from_month_year !=  $to_month_year)
        {
        echo "Cross Date not allowed"; 
        die;

        }
        $from_month=date('m',strtotime($from_date));
        $to_month=date('m',strtotime($to_date));

        $from_day=date('j',strtotime($from_date));
        $to_day=date('j',strtotime($to_date));


        $companies = collect(
        \DB::select("
        select companies.id,companies.code as company_code
        from 
        companies
        where companies.screen_print_capacity_qty is not null
        order by companies.id
        ")
        );

        $caps = collect(
        \DB::select("
        select companies.id as company_id,companies.code as company_code,companies.screen_print_capacity_qty as qty
        from 
        companies
        where companies.screen_print_capacity_qty is not null
        ")
        );


        $boks = collect(
        \DB::select("
        select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty)/26 as qty from (
        SELECT 
        target_transfers.produced_company_id as company_id,
        companies.code as company_code,
        target_transfers.date_to,
        to_char(target_transfers.date_to, 'Mon') as rcv_month,
        to_char(target_transfers.date_to, 'MM') as rcv_month_no,
        to_char(target_transfers.date_to, 'yy') as rcv_year,
        target_transfers.qty
        FROM target_transfers
        join companies on companies.id=target_transfers.produced_company_id
        where target_transfers.process_id=6 and target_transfers.deleted_at is null and companies.screen_print_capacity_qty is not null
        ) m where m.rcv_month_no=".$to_month." group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year 
        ")
        );



        $tgts = collect(
        \DB::select("
        select 
        day_target_transfers.produced_company_id as company_id,
        companies.code as company_code,
        day_target_transfers.target_date as from_date,
        day_target_transfers.target_date as to_date,
        day_target_transfers.process_id,
        sum(day_target_transfers.qty) as qty
        from 
        day_target_transfers
        join companies on companies.id=day_target_transfers.produced_company_id
        where 
        day_target_transfers.target_date>= ?  and 
        day_target_transfers.target_date<= ?  and 
        day_target_transfers.process_id =6 and
        companies.screen_print_capacity_qty is not null and
        day_target_transfers.deleted_at is null
        group by
        day_target_transfers.produced_company_id,
        companies.code,
        day_target_transfers.target_date,
        day_target_transfers.process_id 
        ",[$from_date,$to_date])
        );




        $prods = collect(\DB::select("
        select 
        day_target_transfers.produced_company_id as company_id,
        companies.code as company_code,
        day_target_transfers.target_date as prod_date,
        day_target_transfers.process_id,
        sum(day_target_transfers.prod_qty) as qty
        from 
        day_target_transfers
        join companies on companies.id=day_target_transfers.produced_company_id
        where 
        day_target_transfers.target_date>= ?  and 
        day_target_transfers.target_date<= ?  and 
        day_target_transfers.process_id =6 and
        companies.screen_print_capacity_qty is not null and
        day_target_transfers.deleted_at is null
        group by
        day_target_transfers.produced_company_id,
        companies.code,
        day_target_transfers.target_date,
        day_target_transfers.process_id 
        ",[$from_date,$to_date])
        );
        $this->makeChart($from_day,$to_day,$companies,$caps,$boks,$tgts,$prods);
    }

    public function getGraphEmb()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
        $from_month_year=strtotime(date('m-Y',strtotime($from_date)));
        $to_month_year=strtotime(date('m-Y',strtotime($from_date)));
        if($from_month_year !=  $to_month_year)
        {
        echo "Cross Date not allowed"; 
        die;

        }
        $from_month=date('m',strtotime($from_date));
        $to_month=date('m',strtotime($to_date));

        $from_day=date('j',strtotime($from_date));
        $to_day=date('j',strtotime($to_date));

        $companies = collect(
        \DB::select("
        select companies.id,companies.code as company_code
        from 
        companies
        where companies.embroidery_capacity_qty is not null
        order by companies.id
        ")
        );

        $caps = collect(
        \DB::select("
        select companies.id as company_id,companies.code as company_code,companies.embroidery_capacity_qty as qty
        from 
        companies
        where companies.embroidery_capacity_qty is not null
        ")
        );
        

        $boks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty)/26 as qty from (
                SELECT 
                target_transfers.produced_company_id as company_id,
                companies.code as company_code,
                target_transfers.date_to,
                to_char(target_transfers.date_to, 'Mon') as rcv_month,
                to_char(target_transfers.date_to, 'MM') as rcv_month_no,
                to_char(target_transfers.date_to, 'yy') as rcv_year,
                target_transfers.qty
                FROM target_transfers
                join companies on companies.id=target_transfers.produced_company_id
                where target_transfers.process_id=7 and target_transfers.deleted_at is null and companies.embroidery_capacity_qty is not null
                ) m where m.rcv_month_no=".$to_month." group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year 
            ")
        );
        
        

        $tgts = collect(
        \DB::select("
            select 
            day_target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            day_target_transfers.target_date from_date,
            day_target_transfers.target_date to_date,
            day_target_transfers.process_id,
            sum(day_target_transfers.qty) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            where 
            day_target_transfers.target_date>= ?  and 
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =7 and
            companies.embroidery_capacity_qty is not null and 
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id 
            ",[$from_date,$to_date])
        );

      


        $prods = collect(\DB::select("
            select 
            day_target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            day_target_transfers.target_date prod_date,
            day_target_transfers.process_id,
            sum(day_target_transfers.prod_qty) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            where 
            day_target_transfers.target_date>= ?  and 
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =7 and
            companies.embroidery_capacity_qty is not null and 
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id 
            ",[$from_date,$to_date])
        );
        $this->makeChart($from_day,$to_day,$companies,$caps,$boks,$tgts,$prods);
    }

    public function getGraphFin()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
        $from_month_year=strtotime(date('m-Y',strtotime($from_date)));
        $to_month_year=strtotime(date('m-Y',strtotime($from_date)));
        if($from_month_year !=  $to_month_year)
        {
        echo "Cross Date not allowed"; 
        die;
        }
        $from_month=date('m',strtotime($from_date));
        $to_month=date('m',strtotime($to_date));

        $from_day=date('j',strtotime($from_date));
        $to_day=date('j',strtotime($to_date));

        $companies = collect(
        \DB::select("
        select companies.id,companies.code as company_code
        from 
        companies
        where companies.cartoning_capacity_qty is not null
        order by companies.id
        ")
        );

        $caps = collect(
        \DB::select("
        select companies.id as company_id,companies.code as company_code,companies.cartoning_capacity_qty as qty
        from 
        companies
        where companies.cartoning_capacity_qty is not null
        ")
        );
        

        $boks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty)/26 as qty from (
                SELECT 
                target_transfers.produced_company_id as company_id,
                companies.code as company_code,
                target_transfers.date_to,
                to_char(target_transfers.date_to, 'Mon') as rcv_month,
                to_char(target_transfers.date_to, 'MM') as rcv_month_no,
                to_char(target_transfers.date_to, 'yy') as rcv_year,
                target_transfers.qty
                FROM target_transfers
                join companies on companies.id=target_transfers.produced_company_id
                where target_transfers.process_id=9 and target_transfers.deleted_at is null and companies.cartoning_capacity_qty is not null
                ) m where m.rcv_month_no=".$to_month." group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year 
            ")
        );
        
        

        $tgts = collect(
        \DB::select("
            select 
            day_target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            day_target_transfers.target_date from_date,
            day_target_transfers.target_date to_date,
            day_target_transfers.process_id,
            sum(day_target_transfers.qty) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            where 
            day_target_transfers.target_date>= ?  and 
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =9 and
            companies.cartoning_capacity_qty is not null and 
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id 
            ",[$from_date,$to_date])
        );

      


        $prods = collect(\DB::select("
            select 
            sales_orders.produced_company_id as company_id,
            companies.code as company_code,
            sum(style_pkg_ratios.qty) as qty,
            prod_gmt_carton_entries.carton_date  as prod_date,
            to_char(prod_gmt_carton_entries.carton_date, 'Mon') as rcv_month,
            to_char(prod_gmt_carton_entries.carton_date, 'MM') as rcv_month_no,
            to_char(prod_gmt_carton_entries.carton_date, 'yy') as rcv_year

            FROM prod_gmt_carton_entries
            join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
            join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
            join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join companies on companies.id=sales_orders.produced_company_id
            where 
            prod_gmt_carton_entries.carton_date>=? and 
            prod_gmt_carton_entries.carton_date<=?  and 
            companies.cartoning_capacity_qty is not null and
            prod_gmt_carton_entries.deleted_at is null and 
            prod_gmt_carton_details.deleted_at is null  and
            style_pkg_ratios.deleted_at is null
            group by 
            sales_orders.produced_company_id,
            companies.code,
            prod_gmt_carton_entries.carton_date 
            ",[$from_date,$to_date])
        );
        $this->makeChart($from_day,$to_day,$companies,$caps,$boks,$tgts,$prods);
    }

    public function getGraphIron()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
        $from_month_year=strtotime(date('m-Y',strtotime($from_date)));
        $to_month_year=strtotime(date('m-Y',strtotime($from_date)));
        if($from_month_year !=  $to_month_year)
        {
            echo "Cross Date not allowed"; 
            die;
        }
        $from_month=date('m',strtotime($from_date));
        $to_month=date('m',strtotime($to_date));

        $from_day=date('j',strtotime($from_date));
        $to_day=date('j',strtotime($to_date));

        $companies = collect(
        \DB::select("
        select companies.id,companies.code as company_code
        from 
        companies
        where companies.iron_capacity_qty is not null
        order by companies.id
        ")
        );

        $caps = collect(
        \DB::select("
        select companies.id as company_id,companies.code as company_code,companies.iron_capacity_qty as qty
        from 
        companies
        where companies.iron_capacity_qty is not null
        ")
        );


        $boks = collect(
        \DB::select("
        select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty)/26 as qty from (
        SELECT 
        target_transfers.produced_company_id as company_id,
        companies.code as company_code,
        target_transfers.date_to,
        to_char(target_transfers.date_to, 'Mon') as rcv_month,
        to_char(target_transfers.date_to, 'MM') as rcv_month_no,
        to_char(target_transfers.date_to, 'yy') as rcv_year,
        target_transfers.qty
        FROM target_transfers
        join companies on companies.id=target_transfers.produced_company_id
        where target_transfers.process_id=11 and target_transfers.deleted_at is null and companies.iron_capacity_qty is not null
        ) m where m.rcv_month_no=".$to_month." group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year 
        ")
        );



        $tgts = collect(
        \DB::select("
        select 
        day_target_transfers.produced_company_id as company_id,
        companies.code as company_code,
        day_target_transfers.target_date as from_date,
        day_target_transfers.target_date as to_date,
        day_target_transfers.process_id,
        sum(day_target_transfers.qty) as qty
        from 
        day_target_transfers
        join companies on companies.id=day_target_transfers.produced_company_id
        where 
        day_target_transfers.target_date>= ?  and 
        day_target_transfers.target_date<= ?  and 
        day_target_transfers.process_id =11 and
        companies.iron_capacity_qty is not null and
        day_target_transfers.deleted_at is null
        group by
        day_target_transfers.produced_company_id,
        companies.code,
        day_target_transfers.target_date,
        day_target_transfers.process_id 
        ",[$from_date,$to_date])
        );




        $prods = collect(
        \DB::select("
            select 
            companies.id as company_id,
            companies.code as company_code,
            prod_gmt_irons.iron_qc_date as prod_date,
            to_char(prod_gmt_irons.iron_qc_date, 'Mon') as rcv_month,
            to_char(prod_gmt_irons.iron_qc_date, 'MM') as rcv_month_no,
            to_char(prod_gmt_irons.iron_qc_date, 'yy') as rcv_year,
            prod_gmt_iron_qties.qty
            FROM prod_gmt_irons
            join prod_gmt_iron_orders on prod_gmt_iron_orders.prod_gmt_iron_id = prod_gmt_irons.id
            join sales_order_countries on sales_order_countries.id = prod_gmt_iron_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join jobs on jobs.id = sales_orders.job_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
            join prod_gmt_iron_qties on prod_gmt_iron_qties.prod_gmt_iron_order_id = prod_gmt_iron_orders.id
            --join suppliers on suppliers.id=prod_gmt_iron_orders.supplier_id  
            join companies on companies.id=sales_orders.produced_company_id
            where 
            prod_gmt_irons.iron_qc_date>=? and 
            prod_gmt_irons.iron_qc_date<=? and
            companies.iron_capacity_qty is not null
            group by 
            companies.id,
            companies.code,
            prod_gmt_iron_qties.id,
            prod_gmt_irons.iron_qc_date,
            prod_gmt_iron_qties.qty  
        ",[$from_date,$to_date])
        );
        $this->makeChart($from_day,$to_day,$companies,$caps,$boks,$tgts,$prods);
    }

    public function getGraphPoly()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
        $from_month_year=strtotime(date('m-Y',strtotime($from_date)));
        $to_month_year=strtotime(date('m-Y',strtotime($from_date)));
        if($from_month_year !=  $to_month_year)
        {
            echo "Cross Date not allowed"; 
            die;
        }
        $from_month=date('m',strtotime($from_date));
        $to_month=date('m',strtotime($to_date));

        $from_day=date('j',strtotime($from_date));
        $to_day=date('j',strtotime($to_date));

        $companies = collect(
        \DB::select("
        select companies.id,companies.code as company_code
        from 
        companies
        where companies.poly_capacity_qty is not null
        order by companies.id
        ")
        );

        $caps = collect(
        \DB::select("
        select companies.id as company_id,companies.code as company_code,companies.poly_capacity_qty as qty
        from 
        companies
        where companies.poly_capacity_qty is not null
        ")
        );


        $boks = collect(
        \DB::select("
        select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty)/26 as qty from (
        SELECT 
        target_transfers.produced_company_id as company_id,
        companies.code as company_code,
        target_transfers.date_to,
        to_char(target_transfers.date_to, 'Mon') as rcv_month,
        to_char(target_transfers.date_to, 'MM') as rcv_month_no,
        to_char(target_transfers.date_to, 'yy') as rcv_year,
        target_transfers.qty
        FROM target_transfers
        join companies on companies.id=target_transfers.produced_company_id
        where target_transfers.process_id=12 and target_transfers.deleted_at is null and companies.poly_capacity_qty is not null
        ) m where m.rcv_month_no=".$to_month." group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year 
        ")
        );



        $tgts = collect(
        \DB::select("
        select 
        day_target_transfers.produced_company_id as company_id,
        companies.code as company_code,
        day_target_transfers.target_date as from_date,
        day_target_transfers.target_date as to_date,
        day_target_transfers.process_id,
        sum(day_target_transfers.qty) as qty
        from 
        day_target_transfers
        join companies on companies.id=day_target_transfers.produced_company_id
        where 
        day_target_transfers.target_date>= ?  and 
        day_target_transfers.target_date<= ?  and 
        day_target_transfers.process_id =12 and
        companies.poly_capacity_qty is not null and
        day_target_transfers.deleted_at is null
        group by
        day_target_transfers.produced_company_id,
        companies.code,
        day_target_transfers.target_date,
        day_target_transfers.process_id 
        ",[$from_date,$to_date])
        );




        $prods = collect(
        \DB::select("
            select 
            companies. id as company_id,
            companies.code as company_code,
            prod_gmt_polies.poly_qc_date as prod_date,
            to_char(prod_gmt_polies.poly_qc_date, 'Mon') as rcv_month,
            to_char(prod_gmt_polies.poly_qc_date, 'MM') as rcv_month_no,
            to_char(prod_gmt_polies.poly_qc_date, 'yy') as rcv_year,
            prod_gmt_poly_qties.qty
            FROM prod_gmt_polies
            join prod_gmt_poly_orders on prod_gmt_poly_orders.prod_gmt_poly_id = prod_gmt_polies.id
            join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join jobs on jobs.id = sales_orders.job_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
            join prod_gmt_poly_qties on prod_gmt_poly_qties.prod_gmt_poly_order_id = prod_gmt_poly_orders.id
            --join suppliers on suppliers.id=prod_gmt_poly_orders.supplier_id  
            join companies on companies.id=sales_orders.produced_company_id
            where 
            prod_gmt_polies.poly_qc_date>=? and 
            prod_gmt_polies.poly_qc_date<=? and
            companies.poly_capacity_qty is not null
            group by 
            companies.id,
            companies.code,
            prod_gmt_poly_qties.id,
            prod_gmt_polies.poly_qc_date,
            prod_gmt_poly_qties.qty  
        ",[$from_date,$to_date])
        );
        $this->makeChart($from_day,$to_day,$companies,$caps,$boks,$tgts,$prods);
    }

    public function getGraphSewMintProd()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
        $from_month_year=strtotime(date('m-Y',strtotime($from_date)));
        $to_month_year=strtotime(date('m-Y',strtotime($from_date)));
        if($from_month_year !=  $to_month_year)
        {
        echo "Cross Date not allowed"; 
        die;

        }
        $from_month=date('m',strtotime($from_date));
        $to_month=date('m',strtotime($to_date));

        $from_day=date('j',strtotime($from_date));
        $to_day=date('j',strtotime($to_date));


        $companies = collect(
        \DB::select("
        select companies.id,companies.code as company_code
        from 
        companies
        where companies.sew_effic_per is not null
        order by companies.id
        ")
        );
        
        $caps = collect(
        \DB::select("
            select
            sewing_capacities.company_id,
            companies.code as company_code,
            sewing_capacity_dates.capacity_date,
            to_char(sewing_capacity_dates.capacity_date, 'Mon') as cap_month,
            to_char(sewing_capacity_dates.capacity_date, 'MM') as cap_month_no,
            to_char(sewing_capacity_dates.capacity_date, 'yy') as cap_year,
            sum(sewing_capacity_dates.prod_cap_mint) as qty
            FROM sewing_capacities
            join sewing_capacity_dates on sewing_capacity_dates.sewing_capacity_id = sewing_capacities.id
            join companies on companies.id=sewing_capacities.company_id
            where 
            sewing_capacity_dates.capacity_date>=? 
            and sewing_capacity_dates.capacity_date <= ?
            group by
            sewing_capacities.company_id,
            companies.code,
            sewing_capacity_dates.capacity_date
        ",[$from_date,$to_date])
        );




        $boks = collect(
            /*\DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty)/26 as qty from (
            SELECT 
            target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            target_transfers.date_to,
            to_char(target_transfers.date_to, 'Mon') as rcv_month,
            to_char(target_transfers.date_to, 'MM') as rcv_month_no,
            to_char(target_transfers.date_to, 'yy') as rcv_year,
            target_transfers.qty
            FROM target_transfers
            join companies on companies.id=target_transfers.produced_company_id
            where target_transfers.process_id=8 and target_transfers.deleted_at is null and companies.sew_effic_per is not null
            ) m where m.rcv_month_no=".$to_month." group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year 
            ")*/
            []
        );

        $tgts = collect(
        \DB::select("
            select 
            m.id,
            m.company_id,
            m.company_code,
            m.working_hour,
            m.overtime_hour,
            m.target_per_hour,
            m.from_date,
            m.to_date,
            sum(m.old_qty) as old_qty,
            sum(m.qty) as qty
            from 
            (
            select 
            wstudy_line_setups.id,
            wstudy_line_setups.company_id,
            companies.code as company_code,
            wstudy_line_setup_dtls.working_hour,
            wstudy_line_setup_dtls.overtime_hour,
            wstudy_line_setup_dtls.target_per_hour,
            wstudy_line_setup_dtls.from_date,
            wstudy_line_setup_dtls.to_date,
            wstudy_line_setup_dtls.target_per_hour*(wstudy_line_setup_dtls.working_hour+wstudy_line_setup_dtls.overtime_hour) as old_qty,
            (wstudy_line_setup_dtl_ords.qty*style_gmts.smv) as qty

            from wstudy_line_setups
            join wstudy_line_setup_dtls on wstudy_line_setups.id=wstudy_line_setup_dtls.wstudy_line_setup_id
            join companies on companies.id=wstudy_line_setups.company_id
            join wstudy_line_setup_dtl_ords on wstudy_line_setup_dtl_ords.wstudy_line_setup_dtl_id=wstudy_line_setup_dtls.id
            join style_gmts on style_gmts.id=wstudy_line_setup_dtl_ords.style_gmt_id

            where 
            wstudy_line_setup_dtls.from_date>= ? and
            wstudy_line_setup_dtls.to_date<= ?  and 
            wstudy_line_setup_dtls.to_date >= wstudy_line_setup_dtls.from_date and
            wstudy_line_setups.deleted_at is null and
            wstudy_line_setup_dtls.deleted_at is null and 
            companies.sew_effic_per is not null
            ) m
            group by
            m.id,
            m.company_id,
            m.company_code,
            m.working_hour,
            m.overtime_hour,
            m.target_per_hour,
            m.from_date,
            m.to_date
        ",[$from_date,$to_date])
        );

        $prods = collect(
        \DB::select("
        select 
        wstudy_line_setups.company_id,
        companies.code as company_code,
        prod_gmt_sewings.sew_qc_date as prod_date,
        to_char(prod_gmt_sewings.sew_qc_date, 'Mon') as rcv_month,
        to_char(prod_gmt_sewings.sew_qc_date, 'MM') as rcv_month_no,
        to_char(prod_gmt_sewings.sew_qc_date, 'yy') as rcv_year,
        (prod_gmt_sewing_qties.qty*style_gmts.smv) as qty
        FROM prod_gmt_sewings
        join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
        join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id 
        join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id
        join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join companies on companies.id=wstudy_line_setups.company_id
        join jobs on jobs.id = sales_orders.job_id
        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
        and sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id

        join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
        join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
        where 
        prod_gmt_sewings.sew_qc_date>=? and 
        prod_gmt_sewings.sew_qc_date<=?  and 
        companies.sew_effic_per is not null
        group by 
        wstudy_line_setups.company_id,
        companies.code,
        prod_gmt_sewing_qties.id,
        prod_gmt_sewings.sew_qc_date,
        prod_gmt_sewing_qties.qty,
        style_gmts.smv
        ",[$from_date,$to_date])
        );
        $this->makeChartSew($from_day,$to_day,$companies,$caps,$boks,$tgts,$prods);
    }
}