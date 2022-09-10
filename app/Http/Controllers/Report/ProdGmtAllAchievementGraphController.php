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
use Illuminate\Support\Carbon;

class ProdGmtAllAchievementGraphController extends Controller
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
        $from_date=date('Y-m')."-01";
        //$to=Carbon::parse($from_date);
        //$to->addDays(364);
        $to_date=date('Y-m-d');
    	return Template::loadView('Report.ProdGmtAllAchivmentGraph', ['from'=>$from_date,'to'=>$to_date]);
    }

    private function makeChart($from_date,$to_date,$companies,$sewboks,$sewprods,$cutboks,$cutprods,$spboks,$spprods,$finboks,$finprods,$exfboks,$exfprods,$stufprods){
        /*$date = Carbon::parse($from_date);
        $now = Carbon::parse($to_date);
        $diff = $date->diffInMonths($now);
        $configArr=[];
        for($i=0;$i<=$diff;$i++){
            $index=date('M-y',strtotime($date));
            $configArr[$index]=['Cap'=>0,'Bok'=>0,'Boku'=>0,'Prod'=>0,'Exf'=>0];
            $date->addMonth();
        }*/

        $configArr=[
            'Cutting'=>['Bok'=>0,'Prod'=>0],
            'Sr.Print'=>['Bok'=>0,'Prod'=>0],
            'Sewing'=>['Bok'=>0,'Prod'=>0],
            
            'Finishing'=>['Bok'=>0,'Prod'=>0],
            'Exfactory'=>['Bok'=>0,'Prod'=>0],
            'Stuffing'=>['Bok'=>0,'Prod'=>0],
        ];

        $com=[
            'All-Company'=>$configArr,
        ];
        
        foreach($companies as $company){
        $com[$company->company_code]=$configArr;
        }

        
       /* foreach($configArr as $index=>$value){
            foreach($caps as $cap)
            {
                $com['All-Company'][$index]['Cap']+=$cap->qty;
                $com[$cap->company_code][$index]['Cap']+=$cap->qty;
            }
        }*/

        foreach($cutboks as $cutbok){
            //$index=$bok->rcv_month."-".$bok->rcv_year;
            $com['All-Company']['Cutting']['Bok']+=$cutbok->qty;
            $com[$cutbok->company_code]['Cutting']['Bok']+=$cutbok->qty;
        }

        foreach($cutprods as $cutprod){
            $com['All-Company']['Cutting']['Prod']+=$cutprod->qty;
            $com[$cutprod->company_code]['Cutting']['Prod']+=$cutprod->qty;
        }

        foreach($spboks as $spbok){
            //$index=$bok->rcv_month."-".$bok->rcv_year;
            $com['All-Company']['Sr.Print']['Bok']+=$spbok->qty;
            $com[$spbok->company_code]['Sr.Print']['Bok']+=$spbok->qty;
        }

        foreach($spprods as $spprod){
            $com['All-Company']['Sr.Print']['Prod']+=$spprod->qty;
            $com[$spprod->company_code]['Sr.Print']['Prod']+=$spprod->qty;
        }

        foreach($sewboks as $sewbok){
            //$index=$bok->rcv_month."-".$bok->rcv_year;
            $com['All-Company']['Sewing']['Bok']+=$sewbok->qty;
            $com[$sewbok->company_code]['Sewing']['Bok']+=$sewbok->qty;
        }

        foreach($sewprods as $sewprod){
            $com['All-Company']['Sewing']['Prod']+=$sewprod->qty;
            $com[$sewprod->company_code]['Sewing']['Prod']+=$sewprod->qty;
        }

        foreach($finboks as $finbok){
            //$index=$bok->rcv_month."-".$bok->rcv_year;
            $com['All-Company']['Finishing']['Bok']+=$finbok->qty;
            $com[$finbok->company_code]['Finishing']['Bok']+=$finbok->qty;
        }

        foreach($finprods as $finprod){
            $com['All-Company']['Finishing']['Prod']+=$finprod->qty;
            $com[$finprod->company_code]['Finishing']['Prod']+=$finprod->qty;
        }


        foreach($exfboks as $exfbok){
            //$index=$bok->rcv_month."-".$bok->rcv_year;
            $com['All-Company']['Exfactory']['Bok']+=$exfbok->qty;
            $com[$exfbok->company_code]['Exfactory']['Bok']+=$exfbok->qty;
        }

        foreach($exfprods as $exfprod){
            $com['All-Company']['Exfactory']['Prod']+=$exfprod->qty;
            $com[$exfprod->company_code]['Exfactory']['Prod']+=$exfprod->qty;
        }

        foreach($stufprods as $stufprod){
            $com['All-Company']['Stuffing']['Bok']+=0;
            $com[$stufprod->company_code]['Stuffing']['Bok']+=0;

            $com['All-Company']['Stuffing']['Prod']+=$stufprod->qty;
            $com[$stufprod->company_code]['Stuffing']['Prod']+=$stufprod->qty;
        }

        

        

        foreach($com as $company_id=>$rows){
            $comdata=[];
            $comdatas=[];
            foreach($rows as $key=>$value){
                $comdata['name']=$key;
                $comdata['cap']=0;
                $comdata['bok']=$value['Bok'];
                $comdata['prod']=$value['Prod'];
                $comdata['exf']=0;
                array_push($comdatas,$comdata);
            }
            $com[$company_id]=$comdatas;
        }
        echo json_encode($com);

    }

    public function getGraphQty()
    {
        //$from_date=request('date_from',0);
        //$to_date=request('date_to',0);

        $date_to=request('date_to',0);
        $today=$date_to ? $date_to : date('y-m-d');
        $YM=date('Y-m',strtotime($today));
        $from_date=$YM."-1";
        $to_date=date('Y-m-t',strtotime($from_date));

    	
        $companies = collect(
        \DB::select("
                select companies.id,companies.code as company_code
                from 
                companies
                --where companies.sew_effic_per is not null
                where companies.id in(1,2,4,41)
                order by companies.id
            ")
        );

    	/*$caps = collect(
        \DB::select("
			select company_subsections.company_id,companies.code as company_code,sum(subsections.qty)*26 as qty
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

        $sewboks = collect(
        \DB::select("
				select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
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
				where target_transfers.process_id=8  
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
                and target_transfers.deleted_at is null 
                and  companies.sew_effic_per is not null
				) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
			",[$from_date,$to_date])
        );

        

        $sewprods = collect(
        \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (SELECT 
            sales_orders.produced_company_id as company_id,
            companies.code as company_code,
            prod_gmt_sewings.sew_qc_date,
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
            sales_orders.produced_company_id,
            companies.code,
            prod_gmt_sewing_qties.id,
            prod_gmt_sewings.sew_qc_date,
            prod_gmt_sewing_qties.qty) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );

        $cutboks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
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
                where 
                target_transfers.process_id=5  
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
                and target_transfers.deleted_at is null  
                and companies.cutting_capacity_qty is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $cutprods = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
                SELECT 
                suppliers.company_id,
                companies.code as company_code,
                prod_gmt_cuttings.cut_qc_date,
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
                prod_gmt_cutting_qties.qty) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );

        $spboks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
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
                where 
                target_transfers.process_id=6 
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
                and target_transfers.deleted_at is null  
                and companies.screen_print_capacity_qty is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $spprods = collect(
        \DB::select("
            select m.produced_company_id as company_id,m.company_code, m.rcv_month, m.rcv_month_no, m.rcv_year, sum(m.qty) as qty from (
            select 
            day_target_transfers.produced_company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
            day_target_transfers.process_id,
            sum(day_target_transfers.prod_qty) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            where 
            day_target_transfers.target_date>= ? and    
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =6 and
            companies.screen_print_capacity_qty is not null and
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id 
            ) 
            m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );

        $finboks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
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
                where target_transfers.process_id=9  
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
                and target_transfers.deleted_at is null 
                and  companies.sew_effic_per is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );

        $finprods = collect(
        \DB::select("
                select m.produced_company_id as company_id,m.company_code,sum(m.qty) as qty, sum(m.amount) as amount from (SELECT 
            sales_orders.id as sale_order_id,
            sales_orders.produced_company_id,
            companies.code as company_code,
            sum(style_pkg_ratios.qty) as qty ,
            carton.qty as no_of_carton,
            saleorders.rate,
            sum(style_pkg_ratios.qty)*saleorders.rate as amount
            FROM prod_gmt_carton_entries
            join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
            join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
            join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join companies on companies.id = sales_orders.produced_company_id
            left join (SELECT 
            sales_orders.id as sale_order_id,
            count(prod_gmt_carton_details.qty) as qty 
            FROM prod_gmt_carton_entries
            join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            where prod_gmt_carton_entries.carton_date>=? and 
            prod_gmt_carton_entries.carton_date<=?
            group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id
            left join (SELECT 
            sales_orders.id as sale_order_id,
            avg(sales_order_gmt_color_sizes.rate) as rate 
            FROM prod_gmt_carton_entries
            join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            where prod_gmt_carton_entries.carton_date>=? and 
            prod_gmt_carton_entries.carton_date<=?
            group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
            where prod_gmt_carton_entries.carton_date>=? and 
            prod_gmt_carton_entries.carton_date<=?
            group by sales_orders.id,sales_orders.produced_company_id,companies.code,carton.qty,saleorders.rate) m group by m.produced_company_id,m.company_code
            ",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );

        $exfboks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
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
                where target_transfers.process_id=10 
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
                and target_transfers.deleted_at is null 
                and  companies.sew_effic_per is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );


        $exfprods = collect(
        \DB::select("
                select m.produced_company_id as company_id,m.company_code,sum(m.qty) as qty, sum(m.amount) as amount from (SELECT 
            sales_orders.id as sale_order_id,
            sales_orders.produced_company_id,
            companies.code as company_code,
            sum(style_pkg_ratios.qty) as qty ,
            
            saleorders.rate,
            sum(style_pkg_ratios.qty)*saleorders.rate as amount
            FROM prod_gmt_carton_entries
            join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
            join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
            join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join companies on  companies.id=sales_orders.produced_company_id
            join jobs on jobs.id = sales_orders.job_id 
            join styles on styles.id = jobs.style_id
            and styles.id = style_pkgs.style_id
            join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
            join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

            left join (
            SELECT 
            sales_orders.id as sale_order_id,
            avg(sales_order_gmt_color_sizes.rate) as rate 
            FROM prod_gmt_carton_entries
            join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
            join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

            where prod_gmt_ex_factories.exfactory_date>=? and 
            prod_gmt_ex_factories.exfactory_date<=?
            and sales_order_gmt_color_sizes.qty>0 and sales_order_gmt_color_sizes.deleted_at is null
            group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
            where prod_gmt_ex_factories.exfactory_date>=? and 
            prod_gmt_ex_factories.exfactory_date<=?
            group by sales_orders.id,sales_orders.produced_company_id,companies.code,saleorders.rate) m group by m.produced_company_id,m.company_code
            ",[$from_date,$to_date,$from_date,$to_date])
        );

        $stufprods = collect(
        \DB::select("
            select
            companies.code as company_code,
            exp_lc_scs.beneficiary_id,
            sum(exp_invoice_orders.qty) as qty
            from
            exp_doc_submissions
            join exp_doc_sub_invoices on exp_doc_submissions.id=exp_doc_sub_invoices.exp_doc_submission_id
            join exp_invoices on exp_invoices.id=exp_doc_sub_invoices.exp_invoice_id
            join exp_invoice_orders on exp_invoice_orders.exp_invoice_id=exp_invoices.id
            join exp_lc_scs on exp_lc_scs.id=exp_doc_submissions.exp_lc_sc_id
            join companies on companies.id=exp_lc_scs.beneficiary_id
            where exp_doc_submissions.stuffing_date >=?
            and exp_doc_submissions.stuffing_date <=?
            group by
            exp_lc_scs.beneficiary_id,
            companies.code
            ",[$from_date,$to_date])
        );

        
        $this->makeChart($from_date,$to_date,$companies,$sewboks,$sewprods,$cutboks,$cutprods,$spboks,$spprods,$finboks,$finprods,$exfboks,$exfprods,$stufprods);
    }

    public function getGraphAmount()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
       

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
            select company_subsections.company_id,companies.code as company_code,sum(subsections.amount)*26 as qty
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
        );
        

        $boks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
                    SELECT 
                    target_transfers.produced_company_id as company_id,
                    companies.code as company_code,
                    target_transfers.date_to,
                    to_char(target_transfers.date_to, 'Mon') as rcv_month,
                    to_char(target_transfers.date_to, 'MM') as rcv_month_no,
                    to_char(target_transfers.date_to, 'yy') as rcv_year,
                    (target_transfers.qty * salesorders.rate) as qty
                    FROM target_transfers
                    join companies on companies.id=target_transfers.produced_company_id
                    join (
                    select sales_orders.id,round(sum(sales_order_gmt_color_sizes.amount) /sum(sales_order_gmt_color_sizes.qty),4) as rate
                    from 
                    sales_orders
                    join sales_order_countries on sales_orders.id=sales_order_countries.sale_order_id
                    join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
                    
                    where sales_orders.deleted_at is null
                    and sales_order_countries.deleted_at is null
                    and sales_order_gmt_color_sizes.deleted_at is null
                    and sales_order_gmt_color_sizes.qty > 0
                    and sales_order_gmt_color_sizes.rate > 0
                    and sales_order_gmt_color_sizes.amount > 0
                    group by sales_orders.id
                    ) salesorders on salesorders.id=target_transfers.sales_order_id
                    where 
                    target_transfers.process_id=8 
                    and target_transfers.date_to>=?   
                    and target_transfers.date_to<=? 
                    and target_transfers.deleted_at is null  
                    and companies.sew_effic_per is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        

        $prods = collect(
        \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
            SELECT 
            sales_orders.produced_company_id as company_id,
            companies.code as company_code,
            prod_gmt_sewings.sew_qc_date,
            to_char(prod_gmt_sewings.sew_qc_date, 'Mon') as rcv_month,
            to_char(prod_gmt_sewings.sew_qc_date, 'MM') as rcv_month_no,
            to_char(prod_gmt_sewings.sew_qc_date, 'yy') as rcv_year,
            (prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate) as qty
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
            prod_gmt_sewing_qties.qty,
            sales_order_gmt_color_sizes.id,
            sales_order_gmt_color_sizes.rate
            ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $exfs = collect(
        \DB::select("
            select m.produced_company_id as company_id,m.company_code, m.rcv_month, m.rcv_month_no, m.rcv_year, sum(m.qty) as qty from (
            SELECT 
            sales_orders.id as sale_order_id,
            sales_orders.produced_company_id,
            companies.code as company_code,
            prod_gmt_ex_factories.exfactory_date,
            to_char(prod_gmt_ex_factories.exfactory_date, 'Mon') as rcv_month,
            to_char(prod_gmt_ex_factories.exfactory_date, 'MM') as rcv_month_no,
            to_char(prod_gmt_ex_factories.exfactory_date, 'yy') as rcv_year,
            sum(style_pkg_ratios.qty) * saleorders.rate as qty 
            FROM prod_gmt_carton_entries
            join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
            join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
            join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join jobs on jobs.id = sales_orders.job_id 
            join styles on styles.id = jobs.style_id
            and styles.id = style_pkgs.style_id
            join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
            join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null
            join companies on companies.id=sales_orders.produced_company_id
            left join (
            SELECT 
            sales_orders.id as sale_order_id,
            avg(sales_order_gmt_color_sizes.rate) as rate 
            FROM prod_gmt_carton_entries
            join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
            join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

            where prod_gmt_ex_factories.exfactory_date>=? and 
            prod_gmt_ex_factories.exfactory_date<=?
            and sales_order_gmt_color_sizes.qty>0 and sales_order_gmt_color_sizes.deleted_at is null
            group by sales_orders.id
            ) saleorders on saleorders.sale_order_id=sales_orders.id


            where prod_gmt_ex_factories.exfactory_date>=? and 
            prod_gmt_ex_factories.exfactory_date<=? and
            companies.sew_effic_per is not null
            group by sales_orders.id,sales_orders.produced_company_id,companies.code,prod_gmt_ex_factories.exfactory_date,saleorders.rate) 
            m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            order by m.rcv_month_no
            ",[$from_date,$to_date,$from_date,$to_date])
        );
        $this->makeChart($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs);
    }

    public function getGraphQtyCut()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
      

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
            select companies.id as company_id,companies.code as company_code, companies.cutting_capacity_qty*26 as qty
                from 
                companies
                where companies.cutting_capacity_qty is not null
            ")
        );
        

        $boks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
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
                where 
                target_transfers.process_id=5  
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
                and target_transfers.deleted_at is null  
                and companies.cutting_capacity_qty is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $prods = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
                SELECT 
                suppliers.company_id,
                companies.code as company_code,
                prod_gmt_cuttings.cut_qc_date,
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
                prod_gmt_cutting_qties.qty) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $exfs = collect(
            \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
                SELECT 
                suppliers.company_id,
                companies.code as company_code,
                prod_gmt_cuttings.cut_qc_date,
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
                prod_gmt_cutting_qties.qty) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        $this->makeChart($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs);
    }

    public function getGraphAmountCut()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
       

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
            select companies.id as company_id,companies.code as company_code, companies.cutting_capacity_amount*26 as qty
                from 
                companies
                where companies.cutting_capacity_qty is not null
            ")
        );
        

        $boks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
                    SELECT 
                    target_transfers.produced_company_id as company_id,
                    companies.code as company_code,
                    target_transfers.date_to,
                    to_char(target_transfers.date_to, 'Mon') as rcv_month,
                    to_char(target_transfers.date_to, 'MM') as rcv_month_no,
                    to_char(target_transfers.date_to, 'yy') as rcv_year,
                    (target_transfers.qty * salesorders.rate) as qty
                    FROM target_transfers
                    join companies on companies.id=target_transfers.produced_company_id
                    join (
                    select sales_orders.id,round(sum(sales_order_gmt_color_sizes.amount) /sum(sales_order_gmt_color_sizes.qty),4) as rate
                    from 
                    sales_orders
                    join sales_order_countries on sales_orders.id=sales_order_countries.sale_order_id
                    join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
                    where sales_orders.deleted_at is null
                    and sales_order_countries.deleted_at is null
                    and sales_order_gmt_color_sizes.deleted_at is null
                    and sales_order_gmt_color_sizes.qty > 0
                    and sales_order_gmt_color_sizes.rate > 0
                    and sales_order_gmt_color_sizes.amount > 0
                    group by sales_orders.id
                    ) salesorders on salesorders.id=target_transfers.sales_order_id
                    where 
                    target_transfers.process_id=5  
                    and target_transfers.date_to>=?   
                    and target_transfers.date_to<=?
                    and target_transfers.deleted_at is null  
                    and companies.cutting_capacity_qty is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $prods = collect(
        \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
            SELECT 
            suppliers.company_id,
            companies.code as company_code,
            prod_gmt_cuttings.cut_qc_date,
            to_char(prod_gmt_cuttings.cut_qc_date, 'Mon') as rcv_month,
            to_char(prod_gmt_cuttings.cut_qc_date, 'MM') as rcv_month_no,
            to_char(prod_gmt_cuttings.cut_qc_date, 'yy') as rcv_year,
            (prod_gmt_cutting_qties.qty * sales_order_gmt_color_sizes.rate) as qty
            FROM prod_gmt_cuttings
            join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
            join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join jobs on jobs.id = sales_orders.job_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            and sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id
            join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
            join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id  
            join companies on companies.id=suppliers.company_id 
            where prod_gmt_cuttings.cut_qc_date>=? and 
            prod_gmt_cuttings.cut_qc_date<=?
            group by 
            suppliers.company_id,
            companies.code,
            prod_gmt_cutting_qties.id,
            prod_gmt_cuttings.cut_qc_date,
            prod_gmt_cutting_qties.qty,
            sales_order_gmt_color_sizes.id,
            sales_order_gmt_color_sizes.rate) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        

        $exfs = collect(
           \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
            SELECT 
            suppliers.company_id,
            companies.code as company_code,
            prod_gmt_cuttings.cut_qc_date,
            to_char(prod_gmt_cuttings.cut_qc_date, 'Mon') as rcv_month,
            to_char(prod_gmt_cuttings.cut_qc_date, 'MM') as rcv_month_no,
            to_char(prod_gmt_cuttings.cut_qc_date, 'yy') as rcv_year,
            (prod_gmt_cutting_qties.qty * sales_order_gmt_color_sizes.rate) as qty
            FROM prod_gmt_cuttings
            join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
            join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join jobs on jobs.id = sales_orders.job_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            and sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id
            join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
            join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id  
            join companies on companies.id=suppliers.company_id 
            where prod_gmt_cuttings.cut_qc_date>=? and 
            prod_gmt_cuttings.cut_qc_date<=?
            group by 
            suppliers.company_id,
            companies.code,
            prod_gmt_cutting_qties.id,
            prod_gmt_cuttings.cut_qc_date,
            prod_gmt_cutting_qties.qty,
            sales_order_gmt_color_sizes.id,
            sales_order_gmt_color_sizes.rate) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        $this->makeChart($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs);
    }

    public function getGraphQtySp()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
       

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
            select companies.id as company_id,companies.code as company_code,companies.screen_print_capacity_qty*26 as qty
                from 
                companies
                where companies.screen_print_capacity_qty is not null
            ")
        );
        

        $boks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
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
                where 
                target_transfers.process_id=6 
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
                and target_transfers.deleted_at is null  
                and companies.screen_print_capacity_qty is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $prods = collect(
        \DB::select("
            select m.produced_company_id as company_id,m.company_code, m.rcv_month, m.rcv_month_no, m.rcv_year, sum(m.qty) as qty from (select 
            day_target_transfers.produced_company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
            day_target_transfers.process_id,
            sum(day_target_transfers.prod_qty) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            where 
            day_target_transfers.target_date>= ? and    
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =6 and
            companies.screen_print_capacity_qty is not null and
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id 
            ) 
            m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        

        $exfs = collect(
        \DB::select("
            select m.produced_company_id as company_id,m.company_code, m.rcv_month, m.rcv_month_no, m.rcv_year, sum(m.qty) as qty from (select 
            day_target_transfers.produced_company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
            day_target_transfers.process_id,
            sum(day_target_transfers.prod_qty) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            where 
            day_target_transfers.target_date>= ? and    
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =6 and
            companies.screen_print_capacity_qty is not null and
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id 
            ) 
            m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        $this->makeChart($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs);
    }
    public function getGraphAmountSp()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);
      

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
            select companies.id as company_id,companies.code as company_code,companies.screen_print_capacity_amount*26 as qty
                from 
                companies
                where companies.screen_print_capacity_qty is not null
            ")
        );
        
        $boks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
                    SELECT 
                    target_transfers.produced_company_id as company_id,
                    companies.code as company_code,
                    target_transfers.date_to,
                    to_char(target_transfers.date_to, 'Mon') as rcv_month,
                    to_char(target_transfers.date_to, 'MM') as rcv_month_no,
                    to_char(target_transfers.date_to, 'yy') as rcv_year,
                    (target_transfers.qty * salesorders.rate) as qty
                    FROM target_transfers
                    join companies on companies.id=target_transfers.produced_company_id
                    join (
                    select sales_orders.id,round(sum(sales_order_gmt_color_sizes.amount) /sum(sales_order_gmt_color_sizes.qty),4) as rate
                    from 
                    sales_orders
                    join sales_order_countries on sales_orders.id=sales_order_countries.sale_order_id
                    join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
                    where sales_orders.deleted_at is null
                    and sales_order_countries.deleted_at is null
                    and sales_order_gmt_color_sizes.deleted_at is null
                    and sales_order_gmt_color_sizes.qty > 0
                    and sales_order_gmt_color_sizes.rate > 0
                    and sales_order_gmt_color_sizes.amount > 0
                    group by sales_orders.id
                    ) salesorders on salesorders.id=target_transfers.sales_order_id
                    where 
                    target_transfers.process_id=6
                    and target_transfers.date_to>=?   
                    and target_transfers.date_to<=? 
                    and target_transfers.deleted_at is null 
                    and companies.screen_print_capacity_qty is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $prods = collect(
        \DB::select("
            select m.produced_company_id as company_id,m.company_code, m.rcv_month, m.rcv_month_no, m.rcv_year, sum(m.qty) as qty from (select 
            day_target_transfers.produced_company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
            day_target_transfers.process_id,
            salesorders.rate,
            sum(day_target_transfers.prod_qty*salesorders.rate) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            join (
            select sales_orders.id,round(sum(sales_order_gmt_color_sizes.amount) /sum(sales_order_gmt_color_sizes.qty),4) as rate
            from 
            sales_orders
            join sales_order_countries on sales_orders.id=sales_order_countries.sale_order_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            where sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            and sales_order_gmt_color_sizes.qty > 0
            and sales_order_gmt_color_sizes.rate > 0
            and sales_order_gmt_color_sizes.amount > 0
            group by sales_orders.id
            ) salesorders on salesorders.id=day_target_transfers.sales_order_id
            where 
            day_target_transfers.target_date>= ? and    
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =6 and
            companies.screen_print_capacity_qty is not null and
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            day_target_transfers.sales_order_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id ,
            salesorders.rate
            ) 
            m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $exfs = collect(
        \DB::select("
            select m.produced_company_id as company_id,m.company_code, m.rcv_month, m.rcv_month_no, m.rcv_year, sum(m.qty) as qty from (select 
            day_target_transfers.produced_company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
            day_target_transfers.process_id,
            salesorders.rate,
            sum(day_target_transfers.prod_qty*salesorders.rate) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            join (
            select sales_orders.id,round(sum(sales_order_gmt_color_sizes.amount) /sum(sales_order_gmt_color_sizes.qty),4) as rate
            from 
            sales_orders
            join sales_order_countries on sales_orders.id=sales_order_countries.sale_order_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            where sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            and sales_order_gmt_color_sizes.qty > 0
            and sales_order_gmt_color_sizes.rate > 0
            and sales_order_gmt_color_sizes.amount > 0
            group by sales_orders.id
            ) salesorders on salesorders.id=day_target_transfers.sales_order_id
            where 
            day_target_transfers.target_date>= ? and    
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =6 and
            companies.screen_print_capacity_qty is not null and
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            day_target_transfers.sales_order_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id ,
            salesorders.rate
            ) 
            m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        $this->makeChart($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs);
    }

    public function getGraphQtyEmb()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);

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
                select companies.id as company_id,companies.code as company_code,companies.embroidery_capacity_qty*26 as qty
                from 
                companies
                where companies.embroidery_capacity_qty is not null
            ")
        );
        

        $boks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
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
                where 
                target_transfers.process_id=7 
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
                and target_transfers.deleted_at is null 
                and companies.embroidery_capacity_qty is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $prods = collect(\DB::select("
            select m.produced_company_id as company_id,m.company_code, m.rcv_month, m.rcv_month_no, m.rcv_year, sum(m.qty) as qty from (select 
            day_target_transfers.produced_company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
            day_target_transfers.process_id,
            sum(day_target_transfers.prod_qty) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            where 
            day_target_transfers.target_date>= ? and    
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =7 and
            companies.embroidery_capacity_qty is not null and
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id 
            ) 
            m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        


        $exfs = collect(
        \DB::select("
            select m.produced_company_id as company_id,m.company_code, m.rcv_month, m.rcv_month_no, m.rcv_year, sum(m.qty) as qty from (select 
            day_target_transfers.produced_company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
            day_target_transfers.process_id,
            sum(day_target_transfers.prod_qty) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            where 
            day_target_transfers.target_date>= ? and    
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =7 and
            companies.embroidery_capacity_qty is not null and
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id 
            ) 
            m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        $this->makeChart($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs);
    }

    public function getGraphAmountEmb()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);

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
            select companies.id as company_id,companies.code as company_code,companies.embroidery_capacity_amount*26 as qty
                from 
                companies
                where companies.embroidery_capacity_qty is not null
            ")
        );
        

        $boks = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
                    SELECT 
                    target_transfers.produced_company_id as company_id,
                    companies.code as company_code,
                    target_transfers.date_to,
                    to_char(target_transfers.date_to, 'Mon') as rcv_month,
                    to_char(target_transfers.date_to, 'MM') as rcv_month_no,
                    to_char(target_transfers.date_to, 'yy') as rcv_year,
                    (target_transfers.qty * salesorders.rate) as qty
                    FROM target_transfers
                    join companies on companies.id=target_transfers.produced_company_id
                    join (
                    select sales_orders.id,round(sum(sales_order_gmt_color_sizes.amount) /sum(sales_order_gmt_color_sizes.qty),4) as rate
                    from 
                    sales_orders
                    join sales_order_countries on sales_orders.id=sales_order_countries.sale_order_id
                    join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
                    where sales_orders.deleted_at is null
                    and sales_order_countries.deleted_at is null
                    and sales_order_gmt_color_sizes.deleted_at is null
                    and sales_order_gmt_color_sizes.qty > 0
                    and sales_order_gmt_color_sizes.rate > 0
                    and sales_order_gmt_color_sizes.amount > 0
                    group by sales_orders.id
                    ) salesorders on salesorders.id=target_transfers.sales_order_id
                    where 
                    target_transfers.process_id=7 
                    and target_transfers.date_to>=?   
                    and target_transfers.date_to<=?
                    and target_transfers.deleted_at is null 
                    and companies.embroidery_capacity_qty is not null
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        

        $prods = collect(
        \DB::select("
            select m.produced_company_id as company_id,m.company_code, m.rcv_month, m.rcv_month_no, m.rcv_year, sum(m.qty) as qty from (select 
            day_target_transfers.produced_company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
            day_target_transfers.process_id,
            salesorders.rate,
            sum(day_target_transfers.prod_qty*salesorders.rate) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            join (
            select sales_orders.id,round(sum(sales_order_gmt_color_sizes.amount) /sum(sales_order_gmt_color_sizes.qty),4) as rate
            from 
            sales_orders
            join sales_order_countries on sales_orders.id=sales_order_countries.sale_order_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            where sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            and sales_order_gmt_color_sizes.qty > 0
            and sales_order_gmt_color_sizes.rate > 0
            and sales_order_gmt_color_sizes.amount > 0
            group by sales_orders.id
            ) salesorders on salesorders.id=day_target_transfers.sales_order_id
            where 
            day_target_transfers.target_date>= ? and    
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =7 and
            companies.embroidery_capacity_qty is not null and
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            day_target_transfers.sales_order_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id ,
            salesorders.rate
            ) 
            m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        

        $exfs = collect(
            
        \DB::select("
            select m.produced_company_id as company_id,m.company_code, m.rcv_month, m.rcv_month_no, m.rcv_year, sum(m.qty) as qty from (select 
            day_target_transfers.produced_company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
            day_target_transfers.process_id,
            salesorders.rate,
            sum(day_target_transfers.prod_qty*salesorders.rate) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            join (
            select sales_orders.id,round(sum(sales_order_gmt_color_sizes.amount) /sum(sales_order_gmt_color_sizes.qty),4) as rate
            from 
            sales_orders
            join sales_order_countries on sales_orders.id=sales_order_countries.sale_order_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            where sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            and sales_order_gmt_color_sizes.qty > 0
            and sales_order_gmt_color_sizes.rate > 0
            and sales_order_gmt_color_sizes.amount > 0
            group by sales_orders.id
            ) salesorders on salesorders.id=day_target_transfers.sales_order_id
            where 
            day_target_transfers.target_date>= ? and    
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =7 and
            companies.embroidery_capacity_qty is not null and
            day_target_transfers.deleted_at is null
            group by
            day_target_transfers.produced_company_id,
            day_target_transfers.sales_order_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id ,
            salesorders.rate
            ) 
            m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );
        $this->makeChart($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs);
    }
}