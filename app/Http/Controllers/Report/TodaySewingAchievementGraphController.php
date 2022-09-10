<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use App\Repositories\Contracts\Account\AccBepRepository;
use App\Repositories\Contracts\HRM\EmployeeAttendenceRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use Illuminate\Support\Carbon;

class TodaySewingAchievementGraphController extends Controller
{
    private $no_of_days;
	private $exch_rate;
	private $company;
    private $subsection;
	private $wstudylinesetup;
	private $prodgmtsewing;
	private $accbep;
	private $attendence;
	private $salesorder;
    private $keycontrol;
	public function __construct(
		CompanyRepository $company,
        SubsectionRepository $subsection,
        WstudyLineSetupRepository $wstudylinesetup,
		ProdGmtSewingRepository $prodgmtsewing,
		AccBepRepository $accbep,
		EmployeeAttendenceRepository $attendence,
		SalesOrderRepository $salesorder,
        KeycontrolRepository $keycontrol
	)
    {
        $this->no_of_days                = 26;
		$this->exch_rate                 = 82;
		$this->subsection                = $subsection;
        $this->company                   = $company;
		$this->wstudylinesetup           = $wstudylinesetup;
		$this->prodgmtsewing             = $prodgmtsewing;
		$this->accbep                    = $accbep;
		$this->attendence                = $attendence;
		$this->salesorder                = $salesorder;
        $this->keycontrol = $keycontrol;
		$this->middleware('auth');
		//$this->middleware('permission:view.prodgmtcapacityachievereports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
        $today=date('Y-m-d');
        $company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $yesno[0]='Freeze';

        

    	return Template::loadView('Report.TodaySewingAchivmentGraph', ['company'=>$company,'today'=>$today,'yesno'=>$yesno]);
    }

    public function getLine(){

        $company_id=request('company_id',0);
        $date_to=request('date_to',0);
        $today=$date_to ? $date_to : date('y-m-d');
        //$today='2020-03-23';

        $subsections=$this->wstudylinesetup
        ->join('companies', function($join)  {
        $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
        })
        ->join('wstudy_line_setup_lines', function($join)  {
        $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('wstudy_line_setup_dtls', function($join) {
        $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->leftJoin('subsections', function($join)  {
        $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
        })
        ->leftJoin('floors', function($join)  {
        $join->on('floors.id', '=', 'subsections.floor_id');
        })
        ->leftJoin('employees', function($join)  {
        $join->on('employees.id', '=', 'subsections.employee_id');
        })
        ->when($today, function ($q) use($today){
        return $q->where('wstudy_line_setup_dtls.from_date', '>=',$today);
        })
        ->when($today, function ($q) use($today){
        return $q->where('wstudy_line_setup_dtls.to_date', '<=',$today);
        })
        ->where([['wstudy_line_setups.company_id','=',$company_id]])
        ->orderBy('wstudy_line_setups.id')
        ->orderBy('subsections.id')
        
        ->get([
        'wstudy_line_setups.id',
        'subsections.name',
        'subsections.code',
        'floors.name as floor_name',
        'employees.name as employee_name',
        'subsections.qty',
        'subsections.amount'
        ]);
        $lineNames=Array();
        $lineCode=Array();
        $lineFloor=Array();
        $lineCheif=Array();
        $capacityQty=Array();
        $capacityAmount=Array();
        foreach($subsections as $subsection)
        {
        //$lineNames[$subsection->id][]=$subsection->name;
        $lineCode[$subsection->id][]=$subsection->code;
        //$lineFloor[$subsection->id][]=$subsection->floor_name;
        //$lineCheif[$subsection->id][]=$subsection->employee_name;
        //$capacityQty[$subsection->id][]=$subsection->qty;
        //$capacityAmount[$subsection->id][]=$subsection->amount;
        }
        $lines=[];

        foreach($lineCode as $key=>$value){
            $line['id']=$key;
            $line['name']=implode(',', $value);
            array_push($lines, $line);
        }
        echo json_encode($lines);

    }

    private function makeChart($today,$companies,$lineCode,$tgts,$prods){
        $configArr=[];
        foreach($tgts as $tgt){
           $configArr[$tgt->id]=['Tgt'=>0,'Prod'=>0];
        }
        foreach($prods as $prod){
           $configArr[$prod->id]=['Tgt'=>0,'Prod'=>0];
        }



        
        
        foreach($companies as $company){
        $com[$company->company_code]=$configArr;
        }

        
       $lineBep=0;
       
       
        foreach($tgts as $tgt){
            $index=$tgt->id;
            $com[$tgt->company_code][$index]['Tgt']+=$tgt->tgt_cm;
            $lineBep=$tgt->line_bep;
        }

        $totProdCm=0;
        foreach($prods as $prod){
            $index=$prod->id;
            $com[$prod->company_code][$index]['Prod']+=$prod->prod_cm;
            $totProdCm+=$prod->prod_cm;
        }

        

        

        foreach($com as $company_id=>$rows){
            $comdata=[];
            $comdatas=[];
            foreach($rows as $key=>$value){
                $comdata['name']=implode(',',$lineCode[$key]);
                $comdata['tgt']=$value['Tgt'];
                $comdata['prod']=$value['Prod'];
                array_push($comdatas,$comdata);
            }
            $com[$company_id]=$comdatas;
        }
        $tempdata="'".Template::loadView('Report.TodaySewingAchivmentGraphData1',[
            'lineBep'=>$lineBep,
            'totProdCm'=>$totProdCm
        ])."'";
        echo json_encode(['graphdata'=>$com,'tempdata'=>$tempdata]);

    }
    public function getGraph()
    {
        $company_id=request('company_id',0);
        $date_to=request('date_to',0);
        $today=$date_to ? $date_to : date('y-m-d');
        //$today='2020-03-23';
    	
        $companies = collect(
        \DB::select("
                select companies.id,companies.code as company_code
                from 
                companies
                where companies.id=$company_id 
                order by companies.id
            ")
        );

        $subsections=$this->wstudylinesetup
        ->join('companies', function($join)  {
        $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
        })
        ->join('wstudy_line_setup_lines', function($join)  {
        $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('wstudy_line_setup_dtls', function($join) {
        $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->leftJoin('subsections', function($join)  {
        $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
        })
        ->leftJoin('floors', function($join)  {
        $join->on('floors.id', '=', 'subsections.floor_id');
        })
        ->leftJoin('employees', function($join)  {
        $join->on('employees.id', '=', 'subsections.employee_id');
        })
        ->when($today, function ($q) use($today){
        return $q->where('wstudy_line_setup_dtls.from_date', '>=',$today);
        })
        ->when($today, function ($q) use($today){
        return $q->where('wstudy_line_setup_dtls.to_date', '<=',$today);
        })
        ->where([['wstudy_line_setups.company_id','=',$company_id]])
        ->orderBy('wstudy_line_setups.id')
        ->orderBy('subsections.id')
        
        ->get([
        'wstudy_line_setups.id',
        'subsections.name',
        'subsections.code',
        'floors.name as floor_name',
        'employees.name as employee_name',
        'subsections.qty',
        'subsections.amount'
        ]);
        $lineNames=Array();
        $lineCode=Array();
        $lineFloor=Array();
        $lineCheif=Array();
        $capacityQty=Array();
        $capacityAmount=Array();
        foreach($subsections as $subsection)
        {
        $lineNames[$subsection->id][]=$subsection->name;
        $lineCode[$subsection->id][]=$subsection->code;
        $lineFloor[$subsection->id][]=$subsection->floor_name;
        $lineCheif[$subsection->id][]=$subsection->employee_name;
        $capacityQty[$subsection->id][]=$subsection->qty;
        $capacityAmount[$subsection->id][]=$subsection->amount;
        }

    	

        $tgts = collect(
        \DB::select("
            select m.id,
            m.company_id,
            m.company_code, 
            sum(tgt_cm) as tgt_cm_hour,
            sum(m.line_bep) as line_bep,
            sum(m.tgt_cm_day) as tgt_cm,
            sum(m.qty) as target_qty,
            sum(m.target_per_hour) as target_per_hour,  
            sum(m.operator) as operator, 
            sum(m.helper) as helper,  
            sum(m.working_hour) as working_hour,  
            sum(m.overtime_hour) as overtime_hour  
            from 
            (
                select
                wstudy_line_setups.id,
                companies.id as company_id,
                companies.code as company_code,
                wstudy_line_setup_dtls.target_per_hour,
                wstudy_line_setup_dtls.operator,
                wstudy_line_setup_dtls.helper,
                wstudy_line_setup_dtls.working_hour,
                wstudy_line_setup_dtls.overtime_hour,
                --(keycontrol.value*wstudy_line_setup_dtls.operator*60*(wstudy_line_setup_dtls.working_hou--r+wstudy_line_setup_dtls.overtime_hour)) line_bep,
                (keycontrol.value*factoryMachine.value*60*10) line_bep,

                
                dtlOrds.qty,
                dtlOrds.amount,
                dtlOrds.tgt_cm_day,
                dtlOrds.tgt_cm_day / (wstudy_line_setup_dtls.working_hour+overtime_hour) as tgt_cm
                from
                wstudy_line_setups
                join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id=wstudy_line_setups.id
                join companies on companies.id=wstudy_line_setups.company_id

                left join(
                    select 
                    h.wstudy_line_setup_dtl_id,
                    sum(h.qty) as qty ,
                    sum(h.tgt_cm_day) as tgt_cm_day ,
                    sum(h.cm_per_pcs) as amount 
                    from
                    (
                        select 
                        wstudy_line_setup_dtl_ords.wstudy_line_setup_dtl_id,
                        wstudy_line_setup_dtl_ords.qty,
                        budget_cms.cm_per_pcs,
                        (budget_cms.cm_per_pcs*wstudy_line_setup_dtl_ords.qty) as tgt_cm_day
                        from 
                        wstudy_line_setup_dtl_ords
                        join sales_orders on sales_orders.id=wstudy_line_setup_dtl_ords.sales_order_id
                        join jobs on jobs.id=sales_orders.job_id
                        join budgets on budgets.job_id=jobs.id
                        join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=wstudy_line_setup_dtl_ords.style_gmt_id
                        group by 
                        wstudy_line_setup_dtl_ords.wstudy_line_setup_dtl_id,
                        wstudy_line_setup_dtl_ords.qty,
                        budget_cms.cm_per_pcs
                    ) h 
                    group by 
                    h.wstudy_line_setup_dtl_id
                ) dtlOrds on dtlOrds.wstudy_line_setup_dtl_id=wstudy_line_setup_dtls.id
                left join(
                    select
                    keycontrols.company_id,
                    keycontrol_parameters.value
                    from 
                    keycontrols
                    join keycontrol_parameters on keycontrol_parameters.keycontrol_id=keycontrols.id
                    where keycontrol_parameters.parameter_id=4
                    and ? between keycontrol_parameters.from_date and keycontrol_parameters.to_date
                ) keycontrol on keycontrol.company_id=wstudy_line_setups.company_id
                 left join(
                    select
                    keycontrols.company_id,
                    keycontrol_parameters.value
                    from 
                    keycontrols
                    join keycontrol_parameters on keycontrol_parameters.keycontrol_id=keycontrols.id
                    where keycontrol_parameters.parameter_id=3
                    and ? between keycontrol_parameters.from_date and keycontrol_parameters.to_date
                ) factoryMachine on factoryMachine.company_id=wstudy_line_setups.company_id
                where wstudy_line_setups.company_id=?
                and wstudy_line_setup_dtls.from_date=?
            ) m 
            group by 
            m.id,
            m.company_id,
            m.company_code 
            order by 
            m.id
            ",[$today,$today,$company_id,$today])
        );
        
        

        $prods = collect(
        \DB::select("
            select m.id, m.company_id,m.company_code,sum(m.prod_cm) as prod_cm from (
            SELECT
            wstudy_line_setups.id, 
            sales_orders.produced_company_id as company_id,
            companies.code as company_code,
            prod_gmt_sewings.sew_qc_date,
            to_char(prod_gmt_sewings.sew_qc_date, 'Mon') as rcv_month,
            to_char(prod_gmt_sewings.sew_qc_date, 'MM') as rcv_month_no,
            to_char(prod_gmt_sewings.sew_qc_date, 'yy') as rcv_year,
            prod_gmt_sewing_qties.qty,
            (budget_cms.cm_per_pcs*prod_gmt_sewing_qties.qty) as prod_cm
            FROM prod_gmt_sewings
            join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
            join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id 
            join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id
            join wstudy_line_setup_dtls on wstudy_line_setups.id = wstudy_line_setup_dtls.wstudy_line_setup_id 
            and wstudy_line_setup_dtls.from_date >=?
            and wstudy_line_setup_dtls.to_date <=?
            join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join companies on companies.id=wstudy_line_setups.company_id
            join jobs on jobs.id = sales_orders.job_id
            join budgets on budgets.job_id=jobs.id
            
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            and sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id
            join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
            join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id
            where 
            wstudy_line_setups.company_id=? and 
            prod_gmt_sewings.sew_qc_date>=? and 
            prod_gmt_sewings.sew_qc_date<=?  and 
            companies.sew_effic_per is not null
            group by 
            wstudy_line_setups.id,
            sales_orders.produced_company_id,
            companies.code,
            prod_gmt_sewing_qties.id,
            prod_gmt_sewings.sew_qc_date,
            prod_gmt_sewing_qties.qty,
            budget_cms.cm_per_pcs
            
            ) m group by m.id,m.company_id,m.company_code order by m.id
            ",[$today,$today,$company_id,$today,$today])
        );

        
        $this->makeChart($today,$companies,$lineCode,$tgts,$prods);
    }


    private function makeChartTwo($today,$companies,$lineCode,$tgts,$prods,$lineCheif){
        $configArr=[
            '8:00am'=>['Tgt'=>0,'Prod'=>0],
            '9:00am'=>['Tgt'=>0,'Prod'=>0],
            '10:00am'=>['Tgt'=>0,'Prod'=>0],
            '11:00am'=>['Tgt'=>0,'Prod'=>0],
            '12:00pm'=>['Tgt'=>0,'Prod'=>0],
            '1:00pm'=>['Tgt'=>0,'Prod'=>0],
            '2:00pm'=>['Tgt'=>0,'Prod'=>0],
            '3:00pm'=>['Tgt'=>0,'Prod'=>0],
            '4:00pm'=>['Tgt'=>0,'Prod'=>0],
            '5:00pm'=>['Tgt'=>0,'Prod'=>0],
            '6:00pm'=>['Tgt'=>0,'Prod'=>0],
            '7:00pm'=>['Tgt'=>0,'Prod'=>0],
            '8:00pm'=>['Tgt'=>0,'Prod'=>0],
            '9:00pm'=>['Tgt'=>0,'Prod'=>0],
            '10:00pm'=>['Tgt'=>0,'Prod'=>0],
            '11:00pm'=>['Tgt'=>0,'Prod'=>0],
            '12:00am'=>['Tgt'=>0,'Prod'=>0],
            '1:00am'=>['Tgt'=>0,'Prod'=>0],
            '2:00am'=>['Tgt'=>0,'Prod'=>0],
            '3:00am'=>['Tgt'=>0,'Prod'=>0],
            '4:00am'=>['Tgt'=>0,'Prod'=>0],
            '5:00am'=>['Tgt'=>0,'Prod'=>0],
            '6:00am'=>['Tgt'=>0,'Prod'=>0],
            '7:00am'=>['Tgt'=>0,'Prod'=>0],
            ''=>['Tgt'=>0,'Prod'=>0],
        ];

        /*foreach($tgts as $tgt){
           $configArr[$tgt->id]=['Tgt'=>0,'Prod'=>0];
        }*/
        foreach($prods as $prod){
           $line[$prod->id]=$configArr;
        }



        
        
        /*foreach($companies as $company){
        $com[$company->company_code]=$configArr;
        }*/

        
       $lineBep=0;
       $tgtCmPerHour=0;
       $tgtCmPerDay=0;
       $targetQtyDay=0;
       $targetPerHour=0;
       $operator=0;
       $helper=0;
       $working_hour=0;
       $overtime_hour=0;
        foreach($configArr as $hour=>$hvalue){
        foreach($tgts as $tgt){
            $index=$tgt->id;
            $totHour=$tgt->working_hour+$tgt->overtime_hour;

            if($totHour==15 && ($hour=='8:00am'|| $hour=='2:00pm'  || $hour=='1:00am' || $hour=='2:00am' || $hour=='3:00am' || $hour=='4:00am' || $hour=='5:00am' || $hour=='6:00am' || $hour=='7:00am' || $hour=='')){
                $line[$index][$hour]['Tgt']+=0;
            }

            else if($totHour==14 && ($hour=='8:00am'|| $hour=='2:00pm' ||  $hour=='12:00am' || $hour=='1:00am' || $hour=='2:00am' || $hour=='3:00am' || $hour=='4:00am' || $hour=='5:00am' || $hour=='6:00am' || $hour=='7:00am' || $hour=='')){
                $line[$index][$hour]['Tgt']+=0;

            }

            else if($totHour==13 && ($hour=='8:00am'|| $hour=='2:00pm' ||   $hour=='11:00pm' || $hour=='12:00am' || $hour=='1:00am' || $hour=='2:00am' || $hour=='3:00am' || $hour=='4:00am' || $hour=='5:00am' || $hour=='6:00am' || $hour=='7:00am' || $hour=='')){
                $line[$index][$hour]['Tgt']+=0;

            }

           else if($totHour==12 && ($hour=='8:00am'|| $hour=='2:00pm' ||  $hour=='10:00pm' || $hour=='11:00pm' || $hour=='12:00am' || $hour=='1:00am' || $hour=='2:00am' || $hour=='3:00am' || $hour=='4:00am' || $hour=='5:00am' || $hour=='6:00am' || $hour=='7:00am' || $hour=='')){
                $line[$index][$hour]['Tgt']+=0;

            }

            else if($totHour==11 && ($hour=='8:00am'|| $hour=='2:00pm' || $hour=='9:00pm'|| $hour=='10:00pm' || $hour=='11:00pm' || $hour=='12:00am' || $hour=='1:00am' || $hour=='2:00am' || $hour=='3:00am' || $hour=='4:00am' || $hour=='5:00am' || $hour=='6:00am' || $hour=='7:00am' || $hour=='')){
                $line[$index][$hour]['Tgt']+=0;

            }
            else if($totHour==10 && ($hour=='8:00am'|| $hour=='2:00pm' || $hour=='9:00pm'|| $hour=='10:00pm' || $hour=='11:00pm' || $hour=='12:00am' || $hour=='1:00am' || $hour=='2:00am' || $hour=='3:00am' || $hour=='4:00am' || $hour=='5:00am' || $hour=='6:00am' || $hour=='7:00am' || $hour=='8:00pm' || $hour=='')){
                $line[$index][$hour]['Tgt']+=0;

            }
            else if($totHour==9 && ($hour=='8:00am'|| $hour=='2:00pm' || $hour=='9:00pm'|| $hour=='10:00pm' || $hour=='11:00pm' || $hour=='12:00am' || $hour=='1:00am' || $hour=='2:00am' || $hour=='3:00am' || $hour=='4:00am' || $hour=='5:00am' || $hour=='6:00am' || $hour=='7:00am' || $hour=='7:00pm'  || $hour=='8:00pm' || $hour=='')){
                $line[$index][$hour]['Tgt']+=0;

            }
            else if($totHour==8 && ($hour=='8:00am'|| $hour=='2:00pm' || $hour=='9:00pm'|| $hour=='10:00pm' || $hour=='11:00pm' || $hour=='12:00am' || $hour=='1:00am' || $hour=='2:00am' || $hour=='3:00am' || $hour=='4:00am' || $hour=='5:00am' || $hour=='6:00am' || $hour=='7:00am' || $hour=='6:00pm' || $hour=='7:00pm' || $hour=='8:00pm' || $hour=='')){
                $line[$index][$hour]['Tgt']+=0;

            }
            else{
                $line[$index][$hour]['Tgt']+=$tgt->tgt_cm;
            }
            
            /*if($totHour==8 && ($hour=='6:00pm'|| $hour=='7:00pm' || $hour=='8:00pm') ){
                    $line[$index][$hour]['Tgt']+=0;
            }
            if($totHour==9 && ($hour=='7:00pm'|| $hour=='8:00pm') ){
                    $line[$index][$hour]['Tgt']+=0;
            }
            if($totHour==10 && $hour=='8:00pm'){
                    $line[$index][$hour]['Tgt']+=0;
            }*/
            $lineBep=$tgt->line_bep;
            $tgtCmPerHour=$tgt->tgt_cm;
            $tgtCmPerDay=$tgt->tgt_cm_day;
            $targetQtyDay=$tgt->target_qty;
            $targetPerHour=$tgt->target_per_hour;
            $operator=$tgt->operator;
            $helper=$tgt->helper;
            $working_hour=$tgt->working_hour;
            $overtime_hour=$tgt->overtime_hour;
        }
        }
        $prodHourArr=[];
        $totProdCm=0;
        $qc_pass_qty=0;
        $alter_qty=0;
        $spot_qty=0;
        $reject_qty=0;
        $replace_qty=0;
        $produced_mint=0;
        $minute_addjust=0;
        //$alter=0;
        //$productionYield=0;

        $sewing_start_at = '';
        $sewing_end_at = '';
        $lunch_start_at = '';
        $lunch_end_at = '';
        $lunch='';

        foreach($prods as $prod){
            $index=$prod->id;
            //$line[$index][$prod->prod_hour]['Tgt']+=$tgt_arr[$index];
            $prodHourArr[$prod->prod_hour]=$prod->prod_hour;
            $line[$index][$prod->prod_hour]['Prod']+=$prod->prod_cm;
            $totProdCm+=$prod->prod_cm;
            $qc_pass_qty+=$prod->qc_pass_qty;
            $alter_qty+=$prod->alter_qty;
            $spot_qty+=$prod->spot_qty;
            $reject_qty+=$prod->reject_qty;
            $replace_qty+=$prod->replace_qty;
            $produced_mint+=$prod->produced_mint;
            $prod->minute_addjust=$prod->no_of_minute_plus-$prod->no_of_minute_minus;
            $minute_addjust=$prod->minute_addjust;

            $sewing_start_at = Carbon::parse($prod->sewing_start_at);
            $sewing_end_at = Carbon::parse($prod->sewing_end_at);
            $lunch_start_at = Carbon::parse($prod->lunch_start_at);
            $lunch_end_at = Carbon::parse($prod->lunch_end_at);
            $lunch=$lunch_start_at->diffInHours($lunch_end_at);

            //$rejection+=($prod->reject_qty/($prod->qc_pass_qty+$prod->alter_qty+$prod->spot_qty+$prod->reject_qty))*100;
            //$alter+=($prod->alter_qty/($prod->qc_pass_qty+$prod->alter_qty+$prod->spot_qty+$prod->reject_qty))*100;
            //$productionYield+=(($prod->qc_pass_qty+$prod->spot_qty)/($prod->qc_pass_qty+$prod->alter_qty+$prod->spot_qty+$prod->reject_qty))*100;
        }
        $rejection=0;
        if($qc_pass_qty){
          $rejection=($reject_qty/($qc_pass_qty+$alter_qty+$spot_qty+$reject_qty))*100;  
        }
        $alter=0;
        if($qc_pass_qty){
            $alter=($alter_qty/($qc_pass_qty+$alter_qty+$spot_qty+$reject_qty))*100;
        }
        $productionYield=0;
        if($qc_pass_qty){
            $productionYield=(($qc_pass_qty+$spot_qty)/($qc_pass_qty+$alter_qty+$spot_qty+$reject_qty))*100;
        }

        

        $now = Carbon::now();
        $hourUpto = $sewing_start_at->diffInHours($sewing_end_at);
        if($now <= $sewing_end_at){
        $hourUpto = $sewing_start_at->diffInHours($now);
        }
        if($now>=$lunch_end_at){
        $hourUpto=$hourUpto-$lunch;
        }

        if($today !=date('Y-m-d')){
        $hourUpto=$sewing_start_at->diffInHours($sewing_end_at);
        $hourUpto=$hourUpto-$lunch;
        }
        $hourUpto=$hourUpto?$hourUpto:count($prodHourArr);


        $tergetUpto=$targetPerHour*$hourUpto;
        $achivement=0;
        if($tergetUpto){
            $achivement=($qc_pass_qty/$tergetUpto)*100;
        }

        $mp=$operator+$helper;
        $used_mint=($mp*60*$hourUpto)+($minute_addjust);

        $efficiency=0;
        if($mp){
            $efficiency=($produced_mint/($used_mint))*100;
        }
        $remaimingHour=($working_hour+$overtime_hour)-$hourUpto;

        

        /*sum(m.qty) as  qc_pass_qty,
            sum(m.alter_qty) as  alter_qty,
            sum(m.spot_qty) as  spot_qty,
            sum(m.reject_qty) as  reject_qty,
            sum(m.replace_qty) as  replace_qty*/

        

        

        foreach($line as $line_id=>$rows){
            $comdata=[];
            $comdatas=[];
            foreach($rows as $key=>$value){
                $comdata['name']=$key;
                $comdata['linename']=implode(',',$lineCode[$line_id]);
                $comdata['linechef']=$lineCheif[$line_id];
                $comdata['tgt']=$value['Tgt'];
                $comdata['prod']=$value['Prod'];
                array_push($comdatas,$comdata);
            }
            $line[$line_id]=$comdatas;
        }
        $tempdata="'".Template::loadView('Report.TodaySewingAchivmentGraphData',[
            'lineBep'=>$lineBep,
            'totProdCm'=>$totProdCm,
            'tgtCmPerHour'=>$tgtCmPerHour, 
            'tgtCmPerDay'=>$tgtCmPerDay,
            'rejection'=>$rejection,
            'alter'=>$alter,
            'productionYield'=>$productionYield,
            'achivement'=>$achivement,
            'efficiency'=>$efficiency,
            'remaimingHour'=>$remaimingHour,
        ])."'";
        echo json_encode(['graphdata'=>$line,'tempdata'=>$tempdata]);

    }

    public function getGraphTwo()
    {
        $company_id=request('company_id',0);
        $date_to=request('date_to',0);
        $today=$date_to ? $date_to : date('y-m-d');
        //$today='2020-03-23';


       /* $keycontrol=$this->keycontrol
        ->join('keycontrol_parameters', function($join)  {
        $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
        })
        ->where([['parameter_id','=',4]])
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$budget->budget_date])
        ->get([
        'keycontrol_parameters.value'
        ])->first();*/


        
        $companies = collect(
        \DB::select("
                select companies.id,companies.code as company_code
                from 
                companies
                where companies.id=$company_id 
                order by companies.id
            ")
        );

        $subsections=$this->wstudylinesetup
        ->join('companies', function($join)  {
        $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
        })
        ->join('wstudy_line_setup_lines', function($join)  {
        $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('wstudy_line_setup_dtls', function($join) {
        $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->leftJoin('subsections', function($join)  {
        $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
        })
        ->leftJoin('floors', function($join)  {
        $join->on('floors.id', '=', 'subsections.floor_id');
        })
        ->leftJoin('employees', function($join)  {
        $join->on('employees.id', '=', 'subsections.employee_id');
        })
        ->when($today, function ($q) use($today){
        return $q->where('wstudy_line_setup_dtls.from_date', '>=',$today);
        })
        ->when($today, function ($q) use($today){
        return $q->where('wstudy_line_setup_dtls.to_date', '<=',$today);
        })
        ->when(request('line_id'), function ($q) {
        return $q->where('wstudy_line_setups.id', '>', request('line_id', 0));
        })
        ->when(request('recall_line_id'), function ($q) {
        return $q->where('wstudy_line_setups.id', '=', request('recall_line_id', 0));
        })
        ->where([['wstudy_line_setups.company_id','=',$company_id]])
        ->orderBy('wstudy_line_setups.id')
        ->orderBy('subsections.id')
        ->get([
        'wstudy_line_setups.id',
        'subsections.name',
        'subsections.code',
        'floors.name as floor_name',
        'employees.name as employee_name',
        'subsections.qty',
        'subsections.amount',
        'wstudy_line_setup_dtls.line_chief'
        ]);
        $lineNames=Array();
        $lineCode=Array();
        $lineFloor=Array();
        $lineCheif=Array();
        $capacityQty=Array();
        $capacityAmount=Array();
        foreach($subsections as $subsection)
        {
        $lineNames[$subsection->id][]=$subsection->name;
        $lineCode[$subsection->id][]=$subsection->code;
        $lineFloor[$subsection->id][]=$subsection->floor_name;
        //$lineCheif[$subsection->id][]=$subsection->line_chief;
        $lineCheif[$subsection->id]=$subsection->line_chief;
        $capacityQty[$subsection->id][]=$subsection->qty;
        $capacityAmount[$subsection->id][]=$subsection->amount;
        }

        $fline=$subsections->first();
        //echo $fline->id;

        /*$tgts = collect(
        \DB::select("
                select m.id,
                m.company_id,
                m.company_code, 
                sum(tgt_cm) as tgt_cm,
                sum(m.line_bep) as line_bep,
                sum(m.tgt_cm_day) as tgt_cm_day,
                sum(m.qty) as target_qty,
                sum(m.target_per_hour) as target_per_hour  
                from 
                (
                select
                wstudy_line_setups.id,
                companies.id as company_id,
                companies.code as company_code,
                wstudy_line_setup_dtl_ords.qty,
                wstudy_line_setup_dtls.target_per_hour,
                budget_cms.amount,
                ((budget_cms.amount/budgets.costing_unit_id)*wstudy_line_setup_dtl_ords.qty) as tgt_cm_day,

                (((budget_cms.amount/budgets.costing_unit_id)*wstudy_line_setup_dtl_ords.qty)/(wstudy_line_setup_dtls.working_hour+overtime_hour)) as tgt_cm,
                (keycontrol.value*wstudy_line_setup_dtls.operator*60*(wstudy_line_setup_dtls.working_hour+wstudy_line_setup_dtls.overtime_hour)) line_bep
                from
                wstudy_line_setups
                join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id=wstudy_line_setups.id
                join wstudy_line_setup_dtl_ords on wstudy_line_setup_dtl_ords.wstudy_line_setup_dtl_id=wstudy_line_setup_dtls.id
                join sales_orders on sales_orders.id=wstudy_line_setup_dtl_ords.sales_order_id
                join jobs on jobs.id=sales_orders.job_id
                join budgets on budgets.job_id=jobs.id
                join budget_cms on budget_cms.budget_id=budgets.id
                join companies on companies.id=wstudy_line_setups.company_id

                left join(
                select
                keycontrols.company_id,
                keycontrol_parameters.value
                from 
                keycontrols
                join keycontrol_parameters on keycontrol_parameters.keycontrol_id=keycontrols.id
                where keycontrol_parameters.parameter_id=4
                and ? between keycontrol_parameters.from_date and keycontrol_parameters.to_date
                ) keycontrol on keycontrol.company_id=wstudy_line_setups.company_id

                where wstudy_line_setups.company_id=?
                and wstudy_line_setup_dtls.from_date=?
                and wstudy_line_setups.id=?
                ) m group by m.id,m.company_id,m.company_code order by m.id
            ",[$today,$company_id,$today,$fline->id])
        );*/


        $tgts = collect(
        \DB::select("
            select m.id,
            m.company_id,
            m.company_code, 
            sum(tgt_cm) as tgt_cm,
            sum(m.line_bep) as line_bep,
            sum(m.tgt_cm_day) as tgt_cm_day,
            sum(m.qty) as target_qty,
            sum(m.target_per_hour) as target_per_hour,  
            sum(m.operator) as operator, 
            sum(m.helper) as helper,  
            sum(m.working_hour) as working_hour,  
            sum(m.overtime_hour) as overtime_hour  
            from 
            (
                select
                wstudy_line_setups.id,
                companies.id as company_id,
                companies.code as company_code,
                wstudy_line_setup_dtls.target_per_hour,
                wstudy_line_setup_dtls.operator,
                wstudy_line_setup_dtls.helper,
                wstudy_line_setup_dtls.working_hour,
                wstudy_line_setup_dtls.overtime_hour,
                (keycontrol.value*wstudy_line_setup_dtls.operator*60*(wstudy_line_setup_dtls.working_hour+wstudy_line_setup_dtls.overtime_hour)) line_bep,
                dtlOrds.qty,
                dtlOrds.amount,
                dtlOrds.tgt_cm_day,
                dtlOrds.tgt_cm_day / (wstudy_line_setup_dtls.working_hour+overtime_hour) as tgt_cm
                from
                wstudy_line_setups
                join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id=wstudy_line_setups.id
                join companies on companies.id=wstudy_line_setups.company_id

                left join(
                    select 
                    h.wstudy_line_setup_dtl_id,
                    sum(h.qty) as qty ,
                    sum(h.tgt_cm_day) as tgt_cm_day ,
                    sum(h.cm_per_pcs) as amount 
                    from
                    (
                        select 
                        wstudy_line_setup_dtl_ords.wstudy_line_setup_dtl_id,
                        wstudy_line_setup_dtl_ords.qty,
                        budget_cms.cm_per_pcs,
                        (budget_cms.cm_per_pcs*wstudy_line_setup_dtl_ords.qty) as tgt_cm_day
                        from 
                        wstudy_line_setup_dtl_ords
                        join sales_orders on sales_orders.id=wstudy_line_setup_dtl_ords.sales_order_id
                        join jobs on jobs.id=sales_orders.job_id
                        join budgets on budgets.job_id=jobs.id
                        join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=wstudy_line_setup_dtl_ords.style_gmt_id
                        group by 
                        wstudy_line_setup_dtl_ords.wstudy_line_setup_dtl_id,
                        wstudy_line_setup_dtl_ords.qty,
                        budget_cms.cm_per_pcs
                    ) h 
                    group by 
                    h.wstudy_line_setup_dtl_id
                ) dtlOrds on dtlOrds.wstudy_line_setup_dtl_id=wstudy_line_setup_dtls.id
                left join(
                    select
                    keycontrols.company_id,
                    keycontrol_parameters.value
                    from 
                    keycontrols
                    join keycontrol_parameters on keycontrol_parameters.keycontrol_id=keycontrols.id
                    where keycontrol_parameters.parameter_id=4
                    and ? between keycontrol_parameters.from_date and keycontrol_parameters.to_date
                ) keycontrol on keycontrol.company_id=wstudy_line_setups.company_id
                where wstudy_line_setups.company_id=?
                and wstudy_line_setup_dtls.from_date=?
                and wstudy_line_setups.id=?
            ) m 
            group by 
            m.id,
            m.company_id,
            m.company_code 
            order by 
            m.id
            ",[$today,$company_id,$today,$fline->id])
        );
        
        

        $prods = collect(
        \DB::select("
            select 
            m.id,
            m.prod_hour,
            m.sewing_start_at,      
            m.sewing_end_at,        
            m.lunch_start_at,        
            m.lunch_end_at,
            sum(m.prod_cm) as  prod_cm,
            sum(m.qty) as  qc_pass_qty,
            sum(m.alter_qty) as  alter_qty,
            sum(m.spot_qty) as  spot_qty,
            sum(m.reject_qty) as  reject_qty,
            sum(m.replace_qty) as  replace_qty,
            sum(m.smv) as  produced_mint,
            sum(m.no_of_minute_minus) as  no_of_minute_minus,
            sum(m.no_of_minute_plus) as  no_of_minute_plus

            from (
            select
            wstudy_line_setups.id,
            companies.id as company_id,
            companies.code as company_code,
            wstudy_line_setup_dtls.sewing_start_at,      
            wstudy_line_setup_dtls.sewing_end_at,        
            wstudy_line_setup_dtls.lunch_start_at,        
            wstudy_line_setup_dtls.lunch_end_at,
            prods.prod_hour,
            prods.prod_cm,
            prods.qty,
            prods.alter_qty,
            prods.spot_qty,
            prods.reject_qty,
            prods.replace_qty,
            prods.smv,
            minusaddj.no_of_minute_minus,
            plusaddj.no_of_minute_plus
            from
            wstudy_line_setups
            join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id=wstudy_line_setups.id
            join companies on companies.id=wstudy_line_setups.company_id

            left join(
            SELECT 
            prod_gmt_sewing_orders.wstudy_line_setup_id,
            prod_gmt_sewing_orders.prod_hour,
            prod_gmt_sewing_qties.qty,
            budget_cms.cm_per_pcs,
            (budget_cms.cm_per_pcs*prod_gmt_sewing_qties.qty) as prod_cm,
            prod_gmt_sewing_qties.alter_qty,
            prod_gmt_sewing_qties.spot_qty,
            prod_gmt_sewing_qties.reject_qty,
            prod_gmt_sewing_qties.replace_qty,
            prod_gmt_sewing_qties.qty*style_gmts.smv as smv
            FROM prod_gmt_sewings
            join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
            join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join jobs on jobs.id = sales_orders.job_id
            join budgets on budgets.job_id=jobs.id
            

            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

            join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
            join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id

            where 
            prod_gmt_sewings.sew_qc_date>=? and 
            prod_gmt_sewings.sew_qc_date<=? 
            ) prods on prods.wstudy_line_setup_id = wstudy_line_setups.id

            left join(
                SELECT 
                wstudy_line_setups.id,
                wstudy_line_setup_dtls.from_date,
                sum(wstudy_line_setup_min_adjs.no_of_minute) as no_of_minute_minus
                from
                wstudy_line_setups
                join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id 
                join wstudy_line_setup_min_adjs on wstudy_line_setup_min_adjs.wstudy_line_setup_dtl_id = wstudy_line_setup_dtls.id 
                where wstudy_line_setup_min_adjs.minute_adj_reason_id in(1,2,3,4,5,7)
                and wstudy_line_setup_dtls.from_date>=? and 
                wstudy_line_setup_dtls.to_date<=?
                group by 
                wstudy_line_setups.id,
                wstudy_line_setup_dtls.from_date
            ) minusaddj on minusaddj.id=wstudy_line_setups.id

            left join(
                SELECT 
                wstudy_line_setups.id,
                wstudy_line_setup_dtls.from_date,
                sum(wstudy_line_setup_min_adjs.no_of_minute) as no_of_minute_plus
                from
                wstudy_line_setups
                join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id 
                join wstudy_line_setup_min_adjs on wstudy_line_setup_min_adjs.wstudy_line_setup_dtl_id = wstudy_line_setup_dtls.id 
                where wstudy_line_setup_min_adjs.minute_adj_reason_id in(6)
                and wstudy_line_setup_dtls.from_date>=? and 
                wstudy_line_setup_dtls.to_date<=?
                group by 
                wstudy_line_setups.id,
                wstudy_line_setup_dtls.from_date
            ) plusaddj on plusaddj.id=wstudy_line_setups.id

            where wstudy_line_setups.company_id=?
            and wstudy_line_setup_dtls.from_date=?
            and wstudy_line_setups.id=?
            ) m 
            group by
            m.id,
            m.prod_hour,
            m.sewing_start_at,      
            m.sewing_end_at,        
            m.lunch_start_at,        
            m.lunch_end_at
            --having sum(m.prod_cm)>0
            order by m.id
            ",[$today,$today,$today,$today,$today,$today,$company_id,$today,$fline->id])
        );

        
        $this->makeChartTwo($today,$companies,$lineCode,$tgts,$prods,$lineCheif);
    }
}