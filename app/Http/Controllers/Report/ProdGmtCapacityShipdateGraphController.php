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

class ProdGmtCapacityShipdateGraphController extends Controller
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
    	return Template::loadView('Report.CapacityShipdateGraph', ['from'=>$from_date,'to'=>$to_date]);
    }
    
    private function makeChartSew($from_date,$to_date,$companies,$caps,$boks){
        $date = Carbon::parse($from_date);
        $now = Carbon::parse($to_date);
        $diff = $date->diffInMonths($now);
        $configArr=[];
        for($i=0;$i<=$diff;$i++){
            $index=date('M-y',strtotime($date));
            $configArr[$index]=['Cap'=>0,'Bok'=>0,'Boku'=>0];
            $date->addMonth();
        }

        $com=[
            'All-Company'=>$configArr,
        ];
        
        foreach($companies as $company){
        $com[$company->company_code]=$configArr;
        }
        
       
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

        

        foreach($com as $company_id=>$rows){
            $comdata=[];
            $comdatas=[];
            foreach($rows as $key=>$value){
                $comdata['name']=$key;
                $comdata['cap']=$value['Cap'];
                $comdata['bok']=$value['Bok'];
                array_push($comdatas,$comdata);
            }
            $com[$company_id]=$comdatas;
        }
        echo json_encode($com);

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
            sum(sewing_capacity_dates.mkt_cap_mint) as qty
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
                        select 
                        sales_orders.id,
                        sales_orders.produced_company_id as company_id,
                        companies.code as company_code,
                        to_char(sales_orders.ship_date, 'Mon') as rcv_month,
                        to_char(sales_orders.ship_date, 'MM') as rcv_month_no,
                        to_char(sales_orders.ship_date, 'yy') as rcv_year,
                        sales_orders.ship_date,

                        (sales_order_gmt_color_sizes.qty*style_gmts.smv) as qty
                        from sales_orders
                        join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
                        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                        join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
                        join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
                        join companies on companies.id = sales_orders.produced_company_id
                        where 
                        sales_orders.ship_date>= ? and
                        sales_orders.ship_date<= ?  and 
                        sales_orders.order_status !=2 and 
                        sales_orders.deleted_at is null and
                        sales_orders.deleted_at is null and 
                        companies.sew_effic_per is not null
				) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year
			",[$from_date,$to_date])
			
        );

        

       

        $this->makeChartSew($from_date,$to_date,$companies,$caps,$boks);
    }
}