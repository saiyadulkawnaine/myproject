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

class ProdGmtCapacityAchievementGraphController extends Controller
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
        $to=Carbon::parse($from_date);
        $to->addDays(364);
        $to_date=date('Y-m-d',strtotime($to));
    	return Template::loadView('Report.CapacityAchivmentGraph', ['from'=>$from_date,'to'=>$to_date]);
    }
    
    private function makeChartSew($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs){
        $date = Carbon::parse($from_date);
        $now = Carbon::parse($to_date);
        $diff = $date->diffInMonths($now);
        $configArr=[];
        for($i=0;$i<=$diff;$i++){
            $index=date('M-y',strtotime($date));
            $configArr[$index]=['Cap'=>0,'Bok'=>0,'Boku'=>0,'Prod'=>0,'Exf'=>0];
            $date->addMonth();
        }

        $com=[
            'All-Company'=>$configArr,
        ];
        
        foreach($companies as $company){
        $com[$company->company_code]=$configArr;
        }
        
        /*foreach($configArr as $index=>$value){
            foreach($caps as $cap)
            {
                $com['All-Company'][$index]['Cap']+=$cap->qty;
                $com[$cap->company_code][$index]['Cap']+=$cap->qty;
            }
        }*/
        foreach($caps as $cap){
            $index=$cap->cap_month."-".$cap->cap_year;
            $com['All-Company'][$index]['Cap']+=$cap->qty;
            $com[$cap->company_code][$index]['Cap']+=$cap->qty;
        }

        foreach($boks as $bok){
            $index=$bok->rcv_month."-".$bok->rcv_year;
            $com['All-Company'][$index]['Bok']+=$bok->qty;
            $com[$bok->company_code][$index]['Bok']+=$bok->qty;
        }

        foreach($prods as $prod){
            $index=$prod->rcv_month."-".$prod->rcv_year;
            $com['All-Company'][$index]['Prod']+=$prod->qty;
            $com[$prod->company_code][$index]['Prod']+=$prod->qty;
        }

        foreach($exfs as $exf){
            $index=$exf->rcv_month."-".$exf->rcv_year;
            $com['All-Company'][$index]['Exf']+=$exf->qty;
            $com[$exf->company_code][$index]['Exf']+=$exf->qty;
        }

        foreach($com as $company_id=>$rows){
            $comdata=[];
            $comdatas=[];
            foreach($rows as $key=>$value){
                $comdata['name']=$key;
                $comdata['cap']=$value['Cap'];
                $comdata['bok']=$value['Bok'];
                $comdata['prod']=$value['Prod'];
                $comdata['exf']=$value['Exf'];
                array_push($comdatas,$comdata);
            }
            $com[$company_id]=$comdatas;
        }
        echo json_encode($com);

    }

    private function makeChart($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs){
        $date = Carbon::parse($from_date);
        $now = Carbon::parse($to_date);
        $diff = $date->diffInMonths($now);
        $configArr=[];
        for($i=0;$i<=$diff;$i++){
            $index=date('M-y',strtotime($date));
            $configArr[$index]=['Cap'=>0,'Bok'=>0,'Boku'=>0,'Prod'=>0,'Exf'=>0];
            $date->addMonth();
        }

        $com=[
            'All-Company'=>$configArr,
        ];
        
        foreach($companies as $company){
        $com[$company->company_code]=$configArr;
        }
        
        foreach($configArr as $index=>$value){
            foreach($caps as $cap)
            {
                $com['All-Company'][$index]['Cap']+=$cap->qty;
                $com[$cap->company_code][$index]['Cap']+=$cap->qty;
            }
        }

        foreach($boks as $bok){
            $index=$bok->rcv_month."-".$bok->rcv_year;
            $com['All-Company'][$index]['Bok']+=$bok->qty;
            $com[$bok->company_code][$index]['Bok']+=$bok->qty;
        }

        foreach($prods as $prod){
            $index=$prod->rcv_month."-".$prod->rcv_year;
            $com['All-Company'][$index]['Prod']+=$prod->qty;
            $com[$prod->company_code][$index]['Prod']+=$prod->qty;
        }

        foreach($exfs as $exf){
            $index=$exf->rcv_month."-".$exf->rcv_year;
            $com['All-Company'][$index]['Exf']+=$exf->qty;
            $com[$exf->company_code][$index]['Exf']+=$exf->qty;
        }

        foreach($com as $company_id=>$rows){
            $comdata=[];
            $comdatas=[];
            foreach($rows as $key=>$value){
                $comdata['name']=$key;
                $comdata['cap']=$value['Cap'];
                $comdata['bok']=$value['Bok'];
                $comdata['prod']=$value['Prod'];
                $comdata['exf']=$value['Exf'];
                array_push($comdatas,$comdata);
            }
            $com[$company_id]=$comdatas;
        }
        echo json_encode($com);

    }

    public function getGraphQty()
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
        $caps = collect(
        \DB::select("
            select m.company_id,m.company_code,m.cap_month,m.cap_month_no,m.cap_year,sum(m.qty) as qty from (
            SELECT
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
            order by 
            sewing_capacity_dates.capacity_date) m group by m.company_id,m.company_code,m.cap_month,m.cap_month_no,m.cap_year
            ",[$from_date,$to_date])
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
				where target_transfers.process_id=8  
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
                and target_transfers.deleted_at is null 
                and  companies.sew_effic_per is not null
				) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
			",[$from_date,$to_date])
        );

        /*$prods = collect(
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
            prod_gmt_sewing_qties.qty) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
			",[$from_date,$to_date])
        );*/

        $prods = collect(
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

        $exfs = collect(
        \DB::select("
			select m.produced_company_id as company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
            companies.code as company_code,
			prod_gmt_ex_factories.exfactory_date,
			to_char(prod_gmt_ex_factories.exfactory_date, 'Mon') as rcv_month,
			to_char(prod_gmt_ex_factories.exfactory_date, 'MM') as rcv_month_no,
			to_char(prod_gmt_ex_factories.exfactory_date, 'yy') as rcv_year,
			sum(style_pkg_ratios.qty) as qty 
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

			where prod_gmt_ex_factories.exfactory_date>=? and 
			prod_gmt_ex_factories.exfactory_date<=? and
            companies.sew_effic_per is not null
			group by sales_orders.id,sales_orders.produced_company_id,companies.code,prod_gmt_ex_factories.exfactory_date) 
			m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
			order by m.rcv_month_no
			",[$from_date,$to_date])
        );
        $this->makeChartSew($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs);
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

    public function getGraphMint()
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
        $caps = collect(
        \DB::select("
            select m.company_id,m.company_code,m.cap_month,m.cap_month_no,m.cap_year,sum(m.qty) as qty from (
            SELECT
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
            order by 
            sewing_capacity_dates.capacity_date) m group by m.company_id,m.company_code,m.cap_month,m.cap_month_no,m.cap_year
            ",[$from_date,$to_date])
        );

        /*$boks = collect(
        \DB::select("
				select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
						select 
						wstudy_line_setups.id,
						wstudy_line_setups.company_id,
						companies.code as company_code,
						to_char(wstudy_line_setup_dtls.from_date, 'Mon') as rcv_month,
						to_char(wstudy_line_setup_dtls.from_date, 'MM') as rcv_month_no,
						to_char(wstudy_line_setup_dtls.from_date, 'yy') as rcv_year,
						wstudy_line_setup_dtls.from_date,
						wstudy_line_setup_dtls.to_date,
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
				) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
			",[$from_date,$to_date])
			
        );*/

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
                    (target_transfers.qty * style_gmts.smv) as qty
                    FROM target_transfers
                    join companies on companies.id=target_transfers.produced_company_id
                    join style_gmts on target_transfers.style_gmt_id=style_gmts.id
                    
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
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (SELECT 
            sales_orders.produced_company_id as company_id,
            companies.code as company_code,
            prod_gmt_sewings.sew_qc_date,
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
            sales_orders.produced_company_id,
            companies.code,
            prod_gmt_sewing_qties.id,
            prod_gmt_sewings.sew_qc_date,
            prod_gmt_sewing_qties.qty,
            style_gmts.smv
            ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
            ",[$from_date,$to_date])
        );

        $exfs = collect(
        /*\DB::select("
			select m.produced_company_id as company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,sum(m.qty) as qty from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
            companies.code as company_code,
			prod_gmt_ex_factories.exfactory_date,
			to_char(prod_gmt_ex_factories.exfactory_date, 'Mon') as rcv_month,
			to_char(prod_gmt_ex_factories.exfactory_date, 'MM') as rcv_month_no,
			to_char(prod_gmt_ex_factories.exfactory_date, 'yy') as rcv_year,
			sum(style_pkg_ratios.qty) as qty 
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

			where prod_gmt_ex_factories.exfactory_date>=? and 
			prod_gmt_ex_factories.exfactory_date<=? and
            companies.sew_effic_per is not null
			group by sales_orders.id,sales_orders.produced_company_id,companies.code,prod_gmt_ex_factories.exfactory_date) 
			m group by m.produced_company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
			order by m.rcv_month_no
			",[$from_date,$to_date])*/
        );

        $this->makeChartSew($from_date,$to_date,$companies,$caps,$boks,$prods,$exfs);
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