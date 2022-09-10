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

class ProdTxtCapacityAchievementGraphController extends Controller
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
    	return Template::loadView('Report.TxtCapacityAchivmentGraph', ['from'=>$from_date,'to'=>$to_date]);
    }

    private function makeChart($from_date,$to_date,$companies,$caps,$boks,$outboks,$prods,$exfs){

         $date = Carbon::parse($from_date);
         $now = Carbon::parse($to_date);
         $diff = $date->diffInMonths($now);
         $configArr=[];
         for($i=0;$i<=$diff;$i++){
            $index=date('M-y',strtotime($date));
            $configArr[$index]=['Cap'=>0,'Bok'=>0,'Boku'=>0,'Prod'=>0,'Exf'=>0];
            $date->addMonth();
         }
         

        foreach($companies as $company){
        $com[$company->company_code]=$configArr;
        }

        /*foreach($caps as $cap){
            $com[$cap->company_code]['Jan']['Cap']+=$cap->qty;
            $com[$cap->company_code]['Feb']['Cap']+=$cap->qty;
            $com[$cap->company_code]['Mar']['Cap']+=$cap->qty;
            $com[$cap->company_code]['Apr']['Cap']+=$cap->qty;
            $com[$cap->company_code]['May']['Cap']+=$cap->qty;
            $com[$cap->company_code]['Jun']['Cap']+=$cap->qty;
            $com[$cap->company_code]['Jul']['Cap']+=$cap->qty;
            $com[$cap->company_code]['Aug']['Cap']+=$cap->qty;
            $com[$cap->company_code]['Sep']['Cap']+=$cap->qty;
            $com[$cap->company_code]['Oct']['Cap']+=$cap->qty;
            $com[$cap->company_code]['Nov']['Cap']+=$cap->qty;
            $com[$cap->company_code]['Dec']['Cap']+=$cap->qty;
        }*/

        foreach($configArr as $index=>$value){
            foreach($caps as $cap)
            {
                $com[$cap->company_code][$index]['Cap']+=$cap->qty;
            }
        }

        foreach($boks as $bok){
            $index=$bok->rcv_month."-".$bok->rcv_year;
            
            $com[$bok->company_code][$index]['Bok']+=$bok->qty;
        }
        

        foreach($outboks as $outbok){
            $index=$outbok->rcv_month."-".$outbok->rcv_year;
            $com[$outbok->company_code][$index]['Boku']+=$outbok->qty;
        }

        foreach($prods as $prod){
            $index=$prod->rcv_month."-".$prod->rcv_year;
            $com[$prod->company_code][$index]['Prod']+=$prod->qty;
        }

        foreach($exfs as $exf){
            $index=$exf->rcv_month."-".$exf->rcv_year;
            $com[$exf->company_code][$index]['Exf']+=$exf->qty;
        }

        foreach($com as $company_id=>$rows){
            $comdata=[];
            $comdatas=[];
            foreach($rows as $key=>$value){
                $comdata['name']=$key;
                $comdata['cap']=$value['Cap'];
                $comdata['bok']=$value['Bok'];
                $comdata['boku']=$value['Boku'];
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

        
         /*$date = Carbon::parse('2020-06-01 00:00:00');
         $now = Carbon::parse('2021-05-31 00:00:00');
         $diff = $date->diffInMonths($now);
         $arr=[];
         for($i=0;$i<=$diff;$i++){
            $index=date('M-y',strtotime($date));
            $arr[$index]=['Cap'=>0,'Bok'=>0,'Boku'=>0,'Prod'=>0,'Exf'=>0];
            $date->addMonth();
         }*/

        $from_date=request('date_from',0);
        $to_date=request('date_to',0);



    	
    	$companies = collect(
        \DB::select("
                select companies.id as company_id,companies.code as company_code,companies.knitting_capacity_qty as qty
                from 
                companies
                where 
                companies.knitting_capacity_qty is not null
                union
                select companies.id as company_id,companies.code as company_code,companies.dyeing_capacity_qty as qty
                from 
                companies
                where 
                companies.dyeing_capacity_qty is not null
                union
                select companies.id as company_id,companies.code as company_code,companies.aop_capacity_qty as qty
                from 
                companies
                where 
                companies.aop_capacity_qty is not null
            ")
        );
    	$caps = collect(
        \DB::select("
                select companies.id as company_id,companies.code as company_code,companies.knitting_capacity_qty * 26 as qty
                from 
                companies
                where 
                companies.knitting_capacity_qty is not null
                union
                select companies.id as company_id,companies.code as company_code,companies.dyeing_capacity_qty * 26 as qty
                from 
                companies
                where 
                companies.dyeing_capacity_qty is not null
                union
                select companies.id as company_id,companies.code as company_code,companies.aop_capacity_qty * 26  as qty
                from 
                companies
                where 
                companies.aop_capacity_qty is not null
			")
        );
        
        $boks = collect(
        \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty) as qty from (
                    SELECT 
                    target_transfers.id,
                    target_transfers.produced_company_id as company_id,
                    companies.code as company_code,
                    target_transfers.date_to,
                    target_transfers.process_id,
                    to_char(target_transfers.date_to, 'Mon') as rcv_month,
                    to_char(target_transfers.date_to, 'MM') as rcv_month_no,
                    to_char(target_transfers.date_to, 'yy') as rcv_year,
                    target_transfers.qty
                    FROM target_transfers
                    join companies on companies.id=target_transfers.produced_company_id
                    where 
                    target_transfers.process_id =1 
                    and target_transfers.date_to >= ?   
                    and target_transfers.date_to<=?
                    and target_transfers.deleted_at is null
                    and companies.knitting_capacity_qty is not null

                    union 
                    SELECT 
                    target_transfers.id,
                    target_transfers.produced_company_id as company_id,
                    companies.code as company_code,
                    target_transfers.date_to,
                    target_transfers.process_id,
                    to_char(target_transfers.date_to, 'Mon') as rcv_month,
                    to_char(target_transfers.date_to, 'MM') as rcv_month_no,
                    to_char(target_transfers.date_to, 'yy') as rcv_year,
                    target_transfers.qty
                    FROM target_transfers
                    join companies on companies.id=target_transfers.produced_company_id
                    where 
                    target_transfers.process_id =2 
                    and target_transfers.date_to >= ?   
                    and target_transfers.date_to<=?
                    and target_transfers.deleted_at is null
                    and companies.dyeing_capacity_qty is not null

                    union 
                    SELECT
                    target_transfers.id, 
                    target_transfers.produced_company_id as company_id,
                    companies.code as company_code,
                    target_transfers.date_to,
                    target_transfers.process_id,
                    to_char(target_transfers.date_to, 'Mon') as rcv_month,
                    to_char(target_transfers.date_to, 'MM') as rcv_month_no,
                    to_char(target_transfers.date_to, 'yy') as rcv_year,
                    target_transfers.qty
                    FROM target_transfers
                    join companies on companies.id=target_transfers.produced_company_id
                    where 
                    target_transfers.process_id =4 
                    and target_transfers.date_to >= ?  
                    and target_transfers.date_to<=?
                    and target_transfers.deleted_at is null
                    and companies.aop_capacity_qty is not null
            ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id
			",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );

        $outboks = collect(
        \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty) as qty from (
            select
            so_knit_targets.id, 
            so_knit_targets.company_id,
            companies.code as company_code,
            so_knit_targets.execute_month,
            so_knit_targets.qty,
            to_char(so_knit_targets.execute_month, 'Mon') as rcv_month,
            to_char(so_knit_targets.execute_month, 'MM') as rcv_month_no,
            to_char(so_knit_targets.execute_month, 'yy') as rcv_year,
            1 as process_id
            from 
            so_knit_targets
            join companies on companies.id=so_knit_targets.company_id
            where 
            so_knit_targets.execute_month>=?  and 
            so_knit_targets.execute_month<=?
            and so_knit_targets.deleted_at is null
            and companies.knitting_capacity_qty is not null
            union
            select 
            so_dyeing_targets.id,
            so_dyeing_targets.company_id,
            companies.code as company_code,
            so_dyeing_targets.execute_month,
            so_dyeing_targets.qty,
            to_char(so_dyeing_targets.execute_month, 'Mon') as rcv_month,
            to_char(so_dyeing_targets.execute_month, 'MM') as rcv_month_no,
            to_char(so_dyeing_targets.execute_month, 'yy') as rcv_year,
            2 as process_id
            from 
            so_dyeing_targets
            join companies on companies.id=so_dyeing_targets.company_id
            where 
            so_dyeing_targets.execute_month>=?   
            and so_dyeing_targets.execute_month<=?
            and so_dyeing_targets.deleted_at is null
            and companies.dyeing_capacity_qty is not null

            union
            select 
            so_aop_targets.id,
            so_aop_targets.company_id,
            companies.code as company_code,
            so_aop_targets.execute_month,
            so_aop_targets.qty,
            to_char(so_aop_targets.execute_month, 'Mon') as rcv_month,
            to_char(so_aop_targets.execute_month, 'MM') as rcv_month_no,
            to_char(so_aop_targets.execute_month, 'yy') as rcv_year,
            4 as process_id
            from 
            so_aop_targets
            join companies on companies.id=so_aop_targets.company_id
            where 
            so_aop_targets.execute_month>=?   
            and so_aop_targets.execute_month<=?
            and so_aop_targets.deleted_at is null
            and companies.aop_capacity_qty is not null
            ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id
            ",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );

        /*$prods = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty) as qty from (SELECT 
                suppliers.company_id,
                companies.code as company_code,
                prod_knits.prod_date,
                1 as process_id,
                to_char(prod_knits.prod_date, 'Mon') as rcv_month,
                to_char(prod_knits.prod_date, 'MM') as rcv_month_no,
                to_char(prod_knits.prod_date, 'yy') as rcv_year,
                sum(prod_knit_item_rolls.roll_weight) as qty
                
                FROM prod_knits
                join prod_knit_items on prod_knit_items.prod_knit_id = prod_knits.id
                join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id = prod_knit_items.id
                join suppliers on suppliers.id = prod_knits.supplier_id
                join companies on companies.id=suppliers.company_id
                where prod_knits.prod_date>=? and 
                prod_knits.prod_date<=? and 
                prod_knits.basis_id=1 and
                prod_knits.deleted_at is null and
                prod_knit_items.deleted_at is null and
                prod_knit_item_rolls.deleted_at is null and
                companies.knitting_capacity_qty is not null
                group by
                suppliers.company_id,
                companies.code,
                prod_knits.prod_date

                union

                select 
                day_target_transfers.produced_company_id as company_id,
                companies.code as company_code,
                day_target_transfers.target_date as prod_date,
                day_target_transfers.process_id,
                to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
                to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
                to_char(day_target_transfers.target_date, 'yy') as rcv_year,
                sum(day_target_transfers.prod_qty) as qty
                from 
                day_target_transfers
                join companies on companies.id=day_target_transfers.produced_company_id
                where 
                day_target_transfers.target_date>= ?  and 
                day_target_transfers.target_date<= ?  and 
                day_target_transfers.process_id = 2 and
                 
                day_target_transfers.deleted_at is null and
                companies.dyeing_capacity_qty is not null 
                group by
                day_target_transfers.produced_company_id,
                companies.code,
                day_target_transfers.target_date,
                day_target_transfers.process_id

                union
                select 
                day_target_transfers.produced_company_id as company_id,
                companies.code as company_code,
                day_target_transfers.target_date as prod_date,
                day_target_transfers.process_id,
                to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
                to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
                to_char(day_target_transfers.target_date, 'yy') as rcv_year,
                sum(day_target_transfers.prod_qty) as qty
                from 
                day_target_transfers
                join companies on companies.id=day_target_transfers.produced_company_id
                where 
                day_target_transfers.target_date>= ?  and 
                day_target_transfers.target_date<= ?  and 
                day_target_transfers.process_id  = 4 and
                 
                day_target_transfers.deleted_at is null and
                companies.aop_capacity_qty is not null
                group by
                day_target_transfers.produced_company_id,
                companies.code,
                day_target_transfers.target_date,
                day_target_transfers.process_id
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id
			",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );*/

        $prods = collect(
        \DB::select("
                select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty) as qty from (SELECT 
                suppliers.company_id,
                companies.code as company_code,
                prod_knits.prod_date,
                1 as process_id,
                to_char(prod_knits.prod_date, 'Mon') as rcv_month,
                to_char(prod_knits.prod_date, 'MM') as rcv_month_no,
                to_char(prod_knits.prod_date, 'yy') as rcv_year,
                sum(prod_knit_item_rolls.roll_weight) as qty
                
                FROM prod_knits
                join prod_knit_items on prod_knit_items.prod_knit_id = prod_knits.id
                join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id = prod_knit_items.id
                join suppliers on suppliers.id = prod_knits.supplier_id
                join companies on companies.id=suppliers.company_id
                where prod_knits.prod_date>=? and 
                prod_knits.prod_date<=? and 
                prod_knits.basis_id=1 and
                prod_knits.deleted_at is null and
                prod_knit_items.deleted_at is null and
                prod_knit_item_rolls.deleted_at is null and
                companies.knitting_capacity_qty is not null
                group by
                suppliers.company_id,
                companies.code,
                prod_knits.prod_date

                union
                select 
                prod_batches.company_id,
                companies.code as company_code,
                prod_batches.unload_date as prod_date,
                2 as process_id,
                to_char(prod_batches.unload_date, 'Mon') as rcv_month,
                to_char(prod_batches.unload_date, 'MM') as rcv_month_no,
                to_char(prod_batches.unload_date, 'yy') as rcv_year,

                sum(prod_batches.batch_wgt) as qty
                from 
                prod_batches
                join companies on companies.id=prod_batches.company_id
                where 
                prod_batches.unload_date>= ?  and 
                prod_batches.unload_date<= ?  and 
                --prod_batches.is_redyeing=0 and 
                prod_batches.deleted_at is null and 
                prod_batches.unloaded_at is not null and
                companies.dyeing_capacity_qty is not null

                group by
                prod_batches.company_id,
                companies.code,
                prod_batches.unload_date

                union
                select 
                day_target_transfers.produced_company_id as company_id,
                companies.code as company_code,
                day_target_transfers.target_date as prod_date,
                day_target_transfers.process_id,
                to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
                to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
                to_char(day_target_transfers.target_date, 'yy') as rcv_year,
                sum(day_target_transfers.prod_qty) as qty
                from 
                day_target_transfers
                join companies on companies.id=day_target_transfers.produced_company_id
                where 
                day_target_transfers.target_date>= ?  and 
                day_target_transfers.target_date<= ?  and 
                day_target_transfers.process_id  = 4 and
                 
                day_target_transfers.deleted_at is null and
                companies.aop_capacity_qty is not null
                group by
                day_target_transfers.produced_company_id,
                companies.code,
                day_target_transfers.target_date,
                day_target_transfers.process_id
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id
            ",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );
        


        $exfs = collect(
        \DB::select("
			select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty) as qty from (SELECT 
                suppliers.company_id,
                companies.code as company_code,
                prod_knits.prod_date,
                1 as process_id,
                to_char(prod_knits.prod_date, 'Mon') as rcv_month,
                to_char(prod_knits.prod_date, 'MM') as rcv_month_no,
                to_char(prod_knits.prod_date, 'yy') as rcv_year,
                prod_knit_item_rolls.roll_weight as qty
                
                FROM prod_knits
                join prod_knit_items on prod_knit_items.prod_knit_id = prod_knits.id
                join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id = prod_knit_items.id
                join suppliers on suppliers.id = prod_knits.supplier_id
                join companies on companies.id=suppliers.company_id
                where prod_knits.prod_date>=? and 
                prod_knits.prod_date<=? and 
                prod_knits.basis_id=1 and
                prod_knits.deleted_at is null and
                prod_knit_items.deleted_at is null and
                prod_knit_item_rolls.deleted_at is null and
                companies.knitting_capacity_qty is not null

                union

                select 
                day_target_transfers.produced_company_id as company_id,
                companies.code as company_code,
                day_target_transfers.target_date as prod_date,
                day_target_transfers.process_id,
                to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
                to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
                to_char(day_target_transfers.target_date, 'yy') as rcv_year,
                sum(day_target_transfers.prod_qty) as qty
                from 
                day_target_transfers
                join companies on companies.id=day_target_transfers.produced_company_id
                where 
                day_target_transfers.target_date>= ?  and 
                day_target_transfers.target_date<= ?  and 
                day_target_transfers.process_id = 2 and
                 
                day_target_transfers.deleted_at is null and
                companies.dyeing_capacity_qty is not null 
                group by
                day_target_transfers.produced_company_id,
                companies.code,
                day_target_transfers.target_date,
                day_target_transfers.process_id

                union
                select 
                day_target_transfers.produced_company_id as company_id,
                companies.code as company_code,
                day_target_transfers.target_date as prod_date,
                day_target_transfers.process_id,
                to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
                to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
                to_char(day_target_transfers.target_date, 'yy') as rcv_year,
                sum(day_target_transfers.prod_qty) as qty
                from 
                day_target_transfers
                join companies on companies.id=day_target_transfers.produced_company_id
                where 
                day_target_transfers.target_date>= ?  and 
                day_target_transfers.target_date<= ?  and 
                day_target_transfers.process_id  = 4 and
                 
                day_target_transfers.deleted_at is null and
                companies.aop_capacity_qty is not null
                group by
                day_target_transfers.produced_company_id,
                companies.code,
                day_target_transfers.target_date,
                day_target_transfers.process_id
                ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id
			",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );
        $this->makeChart($from_date,$to_date,$companies,$caps,$boks,$outboks,$prods,$exfs);

    }


    public function getGraphAmount()
    {
        $from_date=request('date_from',0);
        $to_date=request('date_to',0);

        $companies = collect(
        \DB::select("
                select companies.id as company_id,companies.code as company_code,companies.knitting_capacity_qty as qty
                from 
                companies
                where 
                companies.knitting_capacity_qty is not null
                union
                select companies.id as company_id,companies.code as company_code,companies.dyeing_capacity_qty as qty
                from 
                companies
                where 
                companies.dyeing_capacity_qty is not null
                union
                select companies.id as company_id,companies.code as company_code,companies.aop_capacity_qty as qty
                from 
                companies
                where 
                companies.aop_capacity_qty is not null
            ")
        );
        $caps = collect(
        \DB::select("
                select companies.id as company_id,companies.code as company_code,companies.knitting_capacity_amount*26 as qty
                from 
                companies
                where 
                companies.knitting_capacity_qty is not null
                union
                select companies.id as company_id,companies.code as company_code,companies.dyeing_capacity_amount*26 as qty
                from 
                companies
                where 
                companies.dyeing_capacity_qty is not null
                union
                select companies.id as company_id,companies.code as company_code,companies.aop_capacity_amount*26 as qty
                from 
                companies
                where 
                companies.aop_capacity_qty is not null
            ")
        );

       

        $boks = collect(
        \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty) as qty from (
            SELECT
            target_transfers.id, 
            target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            target_transfers.sales_order_id,
            target_transfers.date_to,
            target_transfers.process_id,
            to_char(target_transfers.date_to, 'Mon') as rcv_month,
            to_char(target_transfers.date_to, 'MM') as rcv_month_no,
            to_char(target_transfers.date_to, 'yy') as rcv_year,
            target_transfers.qty,
            target_transfers.qty*kniting.rate as amount
            FROM target_transfers
            join companies on companies.id=target_transfers.produced_company_id
            left join(
            select 
            sales_orders.id,
            avg(budget_fabric_prod_cons.rate) as rate
            from
            sales_orders
            join budget_fabric_prod_cons on budget_fabric_prod_cons.sales_order_id=sales_orders.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id
            join production_processes on production_processes.id=budget_fabric_prods.production_process_id             
            where 
            production_processes.production_area_id=10
            and sales_orders.deleted_at is null
            and budget_fabric_prod_cons.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id
            ) kniting on kniting.id=target_transfers.sales_order_id

            where 
            target_transfers.process_id =1
            and companies.knitting_capacity_qty is not null 
            and target_transfers.date_to>=?   
            and target_transfers.date_to<=?
            and target_transfers.deleted_at is null

            union
            SELECT 
            target_transfers.id,
            target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            target_transfers.sales_order_id,
            target_transfers.date_to,
            target_transfers.process_id,
            to_char(target_transfers.date_to, 'Mon') as rcv_month,
            to_char(target_transfers.date_to, 'MM') as rcv_month_no,
            to_char(target_transfers.date_to, 'yy') as rcv_year,
            target_transfers.qty,
            target_transfers.qty*dyeing.rate as amount
            FROM target_transfers
            join companies on companies.id=target_transfers.produced_company_id
            left join(
            select 
            sales_orders.id,
            avg(budget_fabric_prod_cons.rate) as rate
            from
            sales_orders
            join budget_fabric_prod_cons on budget_fabric_prod_cons.sales_order_id=sales_orders.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id
            join production_processes on production_processes.id=budget_fabric_prods.production_process_id             
            where 
            production_processes.production_area_id=20
            and sales_orders.deleted_at is null
            and budget_fabric_prod_cons.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id
            ) dyeing on dyeing.id=target_transfers.sales_order_id

            where 
            target_transfers.process_id =2
            and companies.dyeing_capacity_qty is not null 
            and target_transfers.date_to>=?   
            and target_transfers.date_to<=?
            and target_transfers.deleted_at is null
            union
            SELECT 
            target_transfers.id,
            target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            target_transfers.sales_order_id,
            target_transfers.date_to,
            target_transfers.process_id,
            to_char(target_transfers.date_to, 'Mon') as rcv_month,
            to_char(target_transfers.date_to, 'MM') as rcv_month_no,
            to_char(target_transfers.date_to, 'yy') as rcv_year,
            target_transfers.qty,
            target_transfers.qty*aop.rate as amount
            FROM target_transfers
            join companies on companies.id=target_transfers.produced_company_id
            left join(
            select 
            sales_orders.id,
            avg(budget_fabric_prod_cons.rate) as rate
            from
            sales_orders
            join budget_fabric_prod_cons on budget_fabric_prod_cons.sales_order_id=sales_orders.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id
            join production_processes on production_processes.id=budget_fabric_prods.production_process_id             
            where 
            production_processes.production_area_id=25
            and sales_orders.deleted_at is null
            and budget_fabric_prod_cons.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id
            ) aop on aop.id=target_transfers.sales_order_id

            where 
            target_transfers.process_id =4
            and companies.aop_capacity_qty is not null 
            and target_transfers.date_to>=?   
            and target_transfers.date_to<=?
            and target_transfers.deleted_at is null
            ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id
            ",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );


        $outboks = collect(
        \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty) as qty from (
            select 
            so_knit_targets.id,
            so_knit_targets.company_id,
            companies.code as company_code,
            so_knit_targets.execute_month,
            so_knit_targets.qty * so_knit_targets.rate as qty,
            to_char(so_knit_targets.execute_month, 'Mon') as rcv_month,
            to_char(so_knit_targets.execute_month, 'MM') as rcv_month_no,
            to_char(so_knit_targets.execute_month, 'yy') as rcv_year,
            1 as process_id
            from 
            so_knit_targets
            join companies on companies.id=so_knit_targets.company_id
            where 
            so_knit_targets.execute_month>=?  and 
            so_knit_targets.execute_month<=?
            and so_knit_targets.deleted_at is null
            and companies.knitting_capacity_qty is not null

            union
            select 
            so_dyeing_targets.id,
            so_dyeing_targets.company_id,
            companies.code as company_code,
            so_dyeing_targets.execute_month,
            so_dyeing_targets.qty * so_dyeing_targets.rate  as qty,
            to_char(so_dyeing_targets.execute_month, 'Mon') as rcv_month,
            to_char(so_dyeing_targets.execute_month, 'MM') as rcv_month_no,
            to_char(so_dyeing_targets.execute_month, 'yy') as rcv_year,
            2 as process_id
            from 
            so_dyeing_targets
            join companies on companies.id=so_dyeing_targets.company_id
            where 
            so_dyeing_targets.execute_month>=?  and 
            so_dyeing_targets.execute_month<=?
            and so_dyeing_targets.deleted_at is null
            and companies.dyeing_capacity_qty is not null

            union
            select 
            so_aop_targets.id,
            so_aop_targets.company_id,
            companies.code as company_code,
            so_aop_targets.execute_month,
            so_aop_targets.qty * so_aop_targets.rate as qty,
            to_char(so_aop_targets.execute_month, 'Mon') as rcv_month,
            to_char(so_aop_targets.execute_month, 'MM') as rcv_month_no,
            to_char(so_aop_targets.execute_month, 'yy') as rcv_year,
            4 as process_id
            from 
            so_aop_targets
            join companies on companies.id=so_aop_targets.company_id
            where 
            so_aop_targets.execute_month>=?  and 
            so_aop_targets.execute_month<=?
            and so_aop_targets.deleted_at is null
            and companies.aop_capacity_qty is not null
            ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id
            ",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );

        $prods = collect(
        \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty) as qty from (SELECT 
            suppliers.company_id,
            companies.code as company_code,
            prod_knits.prod_date,
            1 as process_id,
            to_char(prod_knits.prod_date, 'Mon') as rcv_month,
            to_char(prod_knits.prod_date, 'MM') as rcv_month_no,
            to_char(prod_knits.prod_date, 'yy') as rcv_year,
            (prod_knit_item_rolls.roll_weight)*(po_knit_service_item_qties.rate/po_knit_services.exch_rate) as qty
            FROM prod_knits
            join prod_knit_items on prod_knit_items.prod_knit_id = prod_knits.id
            join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id = prod_knit_items.id
            join suppliers on suppliers.id = prod_knits.supplier_id
            join pl_knit_items on pl_knit_items.id = prod_knit_items.pl_knit_item_id
            join so_knit_refs on so_knit_refs.id = pl_knit_items.so_knit_ref_id
            join so_knit_po_items on so_knit_po_items.so_knit_ref_id = so_knit_refs.id
            join po_knit_service_item_qties on po_knit_service_item_qties.id = so_knit_po_items.po_knit_service_item_qty_id
            join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
            join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id
            join companies on companies.id=suppliers.company_id
            where prod_knits.prod_date>=? and 
            prod_knits.prod_date<=? and 
            prod_knits.basis_id=1 and
            prod_knits.deleted_at is null and
            prod_knit_items.deleted_at is null and
            prod_knit_item_rolls.deleted_at is null and 
            pl_knit_items.deleted_at is null and 
            so_knit_refs.deleted_at is null and
            so_knit_po_items.deleted_at is null and
            po_knit_service_item_qties.deleted_at is null and
            po_knit_service_items.deleted_at is null and
            po_knit_services.deleted_at is null
            and companies.knitting_capacity_qty is not null

            union
            select 
            day_target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            day_target_transfers.process_id,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
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
            day_target_transfers.target_date>= ?  and 
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id = 2 and

            day_target_transfers.deleted_at is null and
            companies.dyeing_capacity_qty is not null 
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id

            union
            select 
            day_target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            day_target_transfers.process_id,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
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
            day_target_transfers.target_date>= ?  and 
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id  = 4 and

            day_target_transfers.deleted_at is null and
            companies.aop_capacity_qty is not null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id
            ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id
            ",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );
        


        $exfs = collect(
        \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty) as qty from (SELECT 
            suppliers.company_id,
            companies.code as company_code,
            prod_knits.prod_date,
            1 as process_id,
            to_char(prod_knits.prod_date, 'Mon') as rcv_month,
            to_char(prod_knits.prod_date, 'MM') as rcv_month_no,
            to_char(prod_knits.prod_date, 'yy') as rcv_year,
            (prod_knit_item_rolls.roll_weight)*(po_knit_service_item_qties.rate/po_knit_services.exch_rate) as qty
            FROM prod_knits
            join prod_knit_items on prod_knit_items.prod_knit_id = prod_knits.id
            join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id = prod_knit_items.id
            join suppliers on suppliers.id = prod_knits.supplier_id
            join pl_knit_items on pl_knit_items.id = prod_knit_items.pl_knit_item_id
            join so_knit_refs on so_knit_refs.id = pl_knit_items.so_knit_ref_id
            join so_knit_po_items on so_knit_po_items.so_knit_ref_id = so_knit_refs.id
            join po_knit_service_item_qties on po_knit_service_item_qties.id = so_knit_po_items.po_knit_service_item_qty_id
            join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
            join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id
            join companies on companies.id=suppliers.company_id
            where prod_knits.prod_date>=? and 
            prod_knits.prod_date<=? and 
            prod_knits.basis_id=1 and
            prod_knits.deleted_at is null and
            prod_knit_items.deleted_at is null and
            prod_knit_item_rolls.deleted_at is null and 
            pl_knit_items.deleted_at is null and 
            so_knit_refs.deleted_at is null and
            so_knit_po_items.deleted_at is null and
            po_knit_service_item_qties.deleted_at is null and
            po_knit_service_items.deleted_at is null and
            po_knit_services.deleted_at is null
            and companies.knitting_capacity_qty is not null

            union
            select 
            day_target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            day_target_transfers.process_id,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
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
            day_target_transfers.target_date>= ?  and 
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id = 2 and

            day_target_transfers.deleted_at is null and
            companies.dyeing_capacity_qty is not null 
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id

            union
            select 
            day_target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            day_target_transfers.target_date as prod_date,
            day_target_transfers.process_id,
            to_char(day_target_transfers.target_date, 'Mon') as rcv_month,
            to_char(day_target_transfers.target_date, 'MM') as rcv_month_no,
            to_char(day_target_transfers.target_date, 'yy') as rcv_year,
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
            day_target_transfers.target_date>= ?  and 
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id  = 4 and

            day_target_transfers.deleted_at is null and
            companies.aop_capacity_qty is not null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id
            ) m group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id
            ",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );
        $this->makeChart($from_date,$to_date,$companies,$caps,$boks,$outboks,$prods,$exfs);
    }
}