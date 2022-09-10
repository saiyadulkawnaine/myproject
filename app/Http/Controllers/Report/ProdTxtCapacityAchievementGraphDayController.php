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

class ProdTxtCapacityAchievementGraphDayController extends Controller
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
    	return Template::loadView('Report.TxtCapacityAchivmentGraphDay', ['from'=>$from,'to'=>$to]);
    }

    private function makeChart($from_day,$to_day,$companies,$caps,$boks,$outboks,$tgts,$prods){
        $configArr = [];

        for($i=$from_day;$i<=$to_day;$i++){
        $configArr[$i]=['Cap'=>0,'Bok'=>0,'Prod'=>0,'Tgt'=>0];
        }
        
        
        foreach($companies as $company){
        $com[$company->company_code]=$configArr;
        }

        for($x=$from_day;$x<=$to_day;$x++){
            foreach($caps as $cap)
            {
                $com[$cap->company_code][$x]['Cap']+=$cap->qty;
            }
        }

        for($y=$from_day;$y<=$to_day;$y++)
        {
            foreach($boks as $bok){
                $com[$bok->company_code][$y]['Bok']+=$bok->qty;
            }
        }

        for($z=$from_day;$z<=$to_day;$z++)
        {
            foreach($outboks as $outbok){
                $com[$outbok->company_code][$z]['Bok']+=$outbok->qty;
            }
        }

       /* foreach($outboks as $outbok){
            $p=date('j',strtotime($outbok->execute_month));
            $com[$outbok->company_code][$p]['Bok']+=$outbok->qty;
        }*/

        foreach($tgts as $tgt){
            /*$tgt_from_day=date('j',strtotime($tgt->from_date));
            $tgt_to_day=date('j',strtotime($tgt->to_date));

            for($t=$tgt_from_day;$t<=$tgt_to_day;$t++)
            {
                $com[$tgt->company_code][$t]['Tgt']+=$tgt->qty;
            }*/
            $t=date('j',strtotime($tgt->pl_date));
            $com[$tgt->company_code][$t]['Tgt']+=$tgt->qty;
        }

        foreach($prods as $prod){
            $p=date('j',strtotime($prod->prod_date));
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
                select companies.id as company_id,companies.code as company_code,companies.knitting_capacity_qty as qty
                from 
                companies
                where 
                companies.knitting_capacity_qty is not null
                union
                select companies.id as company_id,companies.code as company_code,companies.dyeing_capacity_qty  as qty
                from 
                companies
                where 
                companies.dyeing_capacity_qty is not null
                union
                select companies.id as company_id,companies.code as company_code,companies.aop_capacity_qty   as qty
                from 
                companies
                where 
                companies.aop_capacity_qty is not null
            ")
        );

        $boks = collect(
        \DB::select("
				select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty)/26 as qty from (
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
                target_transfers.process_id = 1
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=? 
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
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
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
                and target_transfers.date_to>=?   
                and target_transfers.date_to<=?
                and companies.aop_capacity_qty is not null
				) m where m.rcv_month_no=".$to_month." group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id 
			",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );

        $outboks = collect(
        \DB::select("
            select m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id,sum(m.qty)/26 as qty from (
            select
            so_knit_targets.id, 
            so_knit_targets.company_id,
            companies.code as company_code,
            so_knit_targets.execute_month,
            to_char(so_knit_targets.execute_month, 'Mon') as rcv_month,
            to_char(so_knit_targets.execute_month, 'MM') as rcv_month_no,
            to_char(so_knit_targets.execute_month, 'yy') as rcv_year,
            so_knit_targets.qty,
            1 as process_id
            from 
            so_knit_targets
            join companies on companies.id=so_knit_targets.company_id
            where 
            so_knit_targets.execute_month>=?   
            and so_knit_targets.execute_month<=?
            and so_knit_targets.deleted_at is null
            and companies.knitting_capacity_qty is not null

            union
            select 
            so_dyeing_targets.id, 
            so_dyeing_targets.company_id,
            companies.code as company_code,
            so_dyeing_targets.execute_month,
            to_char(so_dyeing_targets.execute_month, 'Mon') as rcv_month,
            to_char(so_dyeing_targets.execute_month, 'MM') as rcv_month_no,
            to_char(so_dyeing_targets.execute_month, 'yy') as rcv_year,
            so_dyeing_targets.qty,
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
            to_char(so_aop_targets.execute_month, 'Mon') as rcv_month,
            to_char(so_aop_targets.execute_month, 'MM') as rcv_month_no,
            to_char(so_aop_targets.execute_month, 'yy') as rcv_year,

            so_aop_targets.qty,
            4 as process_id
            from 
            so_aop_targets
            join companies on companies.id=so_aop_targets.company_id
            where 
            so_aop_targets.execute_month>=?  and 
            so_aop_targets.execute_month<=?
            and so_aop_targets.deleted_at is null 
            and companies.aop_capacity_qty is not null
            ) m where m.rcv_month_no=".$to_month." group by m.company_id,m.company_code,m.rcv_month,m.rcv_month_no,m.rcv_year,m.process_id
            ",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );

        $tgts = collect(
        \DB::select("
            select 
            pl_knits.company_id,
            companies.code as company_code,
            pl_knit_item_qties.pl_date,
            1 as process_id,
            sum(pl_knit_item_qties.qty) as qty
            from pl_knits
            join pl_knit_items on pl_knits.id=pl_knit_items.pl_knit_id
            join pl_knit_item_qties on pl_knit_items.id=pl_knit_item_qties.pl_knit_item_id
            join companies on companies.id=pl_knits.company_id
            where 
            pl_knit_item_qties.pl_date>= ?  and 
            pl_knit_item_qties.pl_date<= ?  and 

            pl_knits.deleted_at is null and
            pl_knit_items.deleted_at is null and 
            pl_knit_item_qties.deleted_at is null and
            companies.knitting_capacity_qty is not null
            group by 
            pl_knits.company_id,
            companies.code,
            pl_knit_item_qties.pl_date

            union 
            select 
            day_target_transfers.produced_company_id as company_id,
            companies.code as company_code,
            day_target_transfers.target_date as pl_date,
            day_target_transfers.process_id,
            sum(day_target_transfers.qty) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            where 
            day_target_transfers.target_date>= ?  and 
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =2 and
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
            day_target_transfers.target_date as pl_date,
            day_target_transfers.process_id,
            sum(day_target_transfers.qty) as qty
            from 
            day_target_transfers
            join companies on companies.id=day_target_transfers.produced_company_id
            where 
            day_target_transfers.target_date>= ?  and 
            day_target_transfers.target_date<= ?  and 
            day_target_transfers.process_id =4 and 
            day_target_transfers.deleted_at is null and
            companies.aop_capacity_qty is not null
            group by
            day_target_transfers.produced_company_id,
            companies.code,
            day_target_transfers.target_date,
            day_target_transfers.process_id 

			",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );

       


	    /*$prods = collect(
        \DB::select("
                select 
                suppliers.company_id,
                companies.code as company_code,
                prod_knits.prod_date,
                1 as process_id,
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
                prod_knit_item_rolls.deleted_at is null 
                and companies.knitting_capacity_qty is not null
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
                sum(day_target_transfers.prod_qty) as qty
                from 
                day_target_transfers
                join companies on companies.id=day_target_transfers.produced_company_id
                where 
                day_target_transfers.target_date>= ?  and 
                day_target_transfers.target_date<= ?  and 
                day_target_transfers.process_id  =2 and
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
                sum(day_target_transfers.prod_qty) as qty
                from 
                day_target_transfers
                join companies on companies.id=day_target_transfers.produced_company_id
                where 
                day_target_transfers.target_date>= ?  and 
                day_target_transfers.target_date<= ?  and 
                day_target_transfers.process_id =4 and
                day_target_transfers.deleted_at is null and 
                companies.aop_capacity_qty is not null
                group by
                day_target_transfers.produced_company_id,
                companies.code,
                day_target_transfers.target_date,
                day_target_transfers.process_id 


			",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );*/

        $prods = collect(
        \DB::select("
                select 
                suppliers.company_id,
                companies.code as company_code,
                prod_knits.prod_date,
                1 as process_id,
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
                prod_knit_item_rolls.deleted_at is null 
                and companies.knitting_capacity_qty is not null
                group by
                suppliers.company_id,
                companies.code,
                prod_knits.prod_date

                union 
                select 
                prod_batches.company_id,
                companies.code as company_code,
                prod_batches.unload_posting_date as prod_date,
                2 as process_id,

                sum(prod_batches.batch_wgt) as qty
                from 
                prod_batches
                join companies on companies.id=prod_batches.company_id
                where 
                prod_batches.unload_posting_date>= ?  and 
                prod_batches.unload_posting_date<= ?  and 
                --prod_batches.is_redyeing=0 and 
                prod_batches.deleted_at is null and 
                prod_batches.unloaded_at is not null and
                companies.dyeing_capacity_qty is not null

                group by
                prod_batches.company_id,
                companies.code,
                prod_batches.unload_posting_date

                union 
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
                day_target_transfers.process_id =4 and
                day_target_transfers.deleted_at is null and 
                companies.aop_capacity_qty is not null
                group by
                day_target_transfers.produced_company_id,
                companies.code,
                day_target_transfers.target_date,
                day_target_transfers.process_id 


            ",[$from_date,$to_date,$from_date,$to_date,$from_date,$to_date])
        );
        $this->makeChart($from_day,$to_day,$companies,$caps,$boks,$outboks,$tgts,$prods);
    }
}