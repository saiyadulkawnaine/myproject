<?php

namespace App\Http\Controllers\Report\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Account\AccYearRepository;
use App\Repositories\Contracts\Account\AccPeriodRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;

use App\Repositories\Contracts\Account\AccChartLocationRepository;
use App\Repositories\Contracts\Account\AccChartDivisionRepository;
use App\Repositories\Contracts\Account\AccChartDepartmentRepository;
use App\Repositories\Contracts\Account\AccChartSectionRepository;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use Illuminate\Support\Carbon;

class BalanceSheetController extends Controller
{
	private $company;
	private $accyear;
	private $accchartctrlhead;
   

	public function __construct(CompanyRepository $company,AccYearRepository $accyear,AccChartCtrlHeadRepository $accchartctrlhead)
    {
		$this->company  = $company;
		$this->accyear    = $accyear;
		$this->accchartctrlhead = $accchartctrlhead;
       

		$this->middleware('auth');

		$this->middleware('permission:view.balancesheets',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
		
      return Template::loadView('Report.Account.BalanceSheet',['company'=>$company,'accyear'=>$accyear]);
    }
    public function getYear(){
    	$allYear=$this->accyear
    	->where([['company_id','=',request('company_id',0)]])
    	->get()
    	->map(function ($allYear){
    		$allYear->start_date=date('Y-m-d',strtotime($allYear->start_date));
    		$allYear->end_date=date('Y-m-d',strtotime($allYear->end_date));
    		return $allYear;
    	});
    	echo json_encode($allYear);
    }

    public function getPeriods(){
    	$periods=$this->accyear
    	->join('acc_periods',function($join){
			$join->on('acc_periods.acc_year_id','=','acc_years.id');
		})
    	->where([['acc_years.id','=',request('acc_year_id',0)]])
    	->where([['acc_periods.period','>',0]])
    	->where([['acc_periods.period','<=',12]])
    	->orderBy('acc_periods.period')
    	->get([
    		'acc_periods.id',
    		'acc_periods.name',
    	]);
    	echo json_encode($periods);
    }

     private function getData()
     {
     	$company_id=request('company_id',0);
     	$acc_year_id=request('acc_year_id',0);
     	$level=request('level',0);

		$accYear=$this->accyear
    	->where([['id','=',$acc_year_id]])
    	->where([['company_id','=',$company_id]])
    	->get()->first();

    	
    	

    	$yearStart=date('Y-m-d',strtotime($accYear->start_date));
    	$yearEnd=date('Y-m-d',strtotime($accYear->end_date));

    	$start_date=$accYear->start_date;
		$end_date=request('date_to',0);
    	
    	if(!$end_date){
            $end_date=$accYear->end_date;
    	}

    	

    	$date = Carbon::parse($start_date);
        $now = Carbon::parse($yearEnd);

        $year=date('Y',strtotime($start_date));
        $leapyear=date('L', mktime(0, 0, 0, 1, 1, $year));
		$diff = $date->diffInDays($now)+1;
		
		
		//echo $diff; die;


	    $lastPeriodStart = new \DateTime($start_date); 
	    $leapyear?$lastPeriodStart->modify("- 366 days"):$lastPeriodStart->modify("- 365 days");
	    //$lastPeriodStart->modify("- $diff days");
	    $lastPeriodStart->format("Y-m-d");

	    $lastPeriodEnd = new \DateTime($start_date);
        $lastPeriodEnd->modify("-1 day");
        $lastPeriodEnd->format("Y-m-d");
        //echo $bbb; die;



        $group=array();

        if($level==1)
        {
        	
        $sql='acc_chart_sub_groups.acc_chart_group_id,
		acc_chart_sub_groups.id,
		acc_chart_sub_groups.name,

		(CASE WHEN acc_chart_sub_groups.acc_chart_group_id = 1 or acc_chart_sub_groups.acc_chart_group_id = 4 or acc_chart_sub_groups.acc_chart_group_id = 7 or acc_chart_sub_groups.acc_chart_group_id = 16 or acc_chart_sub_groups.acc_chart_group_id = 25
                      THEN (sum (acc_trans_chlds.amount)*-1)
                      ELSE sum (acc_trans_chlds.amount)
                  END ) AS amount';

		$group=['acc_chart_sub_groups.acc_chart_group_id','acc_chart_sub_groups.id','acc_chart_sub_groups.name'];
		$order='acc_chart_sub_groups.acc_chart_group_id';

		$sql1='acc_chart_sub_groups.acc_chart_group_id,
		acc_chart_sub_groups.id,
		acc_chart_sub_groups.name';
		$group1=['acc_chart_sub_groups.acc_chart_group_id','acc_chart_sub_groups.id','acc_chart_sub_groups.name'];

		$order1='acc_chart_sub_groups.acc_chart_group_id';

        }
        if($level==2){
        $sql='acc_chart_sub_groups.acc_chart_group_id,
		acc_chart_ctrl_heads.root_id as id,
		(CASE WHEN acc_chart_sub_groups.acc_chart_group_id = 1 or acc_chart_sub_groups.acc_chart_group_id = 4 or acc_chart_sub_groups.acc_chart_group_id = 7 or acc_chart_sub_groups.acc_chart_group_id = 16 or acc_chart_sub_groups.acc_chart_group_id = 25
                      THEN (sum (acc_trans_chlds.amount)*-1)
                      ELSE sum (acc_trans_chlds.amount)
                  END ) AS amount';
		$group=['acc_chart_sub_groups.acc_chart_group_id','acc_chart_ctrl_heads.root_id'];
		$order='acc_chart_sub_groups.acc_chart_group_id';

		$sql1='acc_chart_sub_groups.acc_chart_group_id,
		acc_chart_ctrl_heads.root_id as id';
		$group1=['acc_chart_sub_groups.acc_chart_group_id','acc_chart_ctrl_heads.root_id'];
		$order1='acc_chart_sub_groups.acc_chart_group_id';

        }
        if($level==3){
        $sql='acc_chart_sub_groups.acc_chart_group_id,
		acc_chart_ctrl_heads.id,
		acc_chart_ctrl_heads.code,
		acc_chart_ctrl_heads.name,
		(CASE WHEN acc_chart_sub_groups.acc_chart_group_id = 1 or acc_chart_sub_groups.acc_chart_group_id = 4 or acc_chart_sub_groups.acc_chart_group_id = 7 or acc_chart_sub_groups.acc_chart_group_id = 16 or acc_chart_sub_groups.acc_chart_group_id = 25
                      THEN (sum (acc_trans_chlds.amount)*-1)
                      ELSE sum (acc_trans_chlds.amount)
                  END ) AS amount';
		$group=['acc_chart_sub_groups.acc_chart_group_id','acc_chart_ctrl_heads.id','acc_chart_ctrl_heads.code','acc_chart_ctrl_heads.name'];
		$order='acc_chart_ctrl_heads.code';
		$sql1='acc_chart_sub_groups.acc_chart_group_id,
		acc_chart_ctrl_heads.id,
		acc_chart_ctrl_heads.code,
		acc_chart_ctrl_heads.name';
		$group1=['acc_chart_sub_groups.acc_chart_group_id','acc_chart_ctrl_heads.id','acc_chart_ctrl_heads.code','acc_chart_ctrl_heads.name'];
		$order1='acc_chart_ctrl_heads.code';
        }

        

        $data = DB::table("acc_trans_chlds")
		->selectRaw($sql)
		->join('acc_trans_prnts',function($join){
		$join->on('acc_trans_prnts.id','=','acc_trans_chlds.acc_trans_prnt_id');
		})
		->join('acc_periods',function($join){
		$join->on('acc_periods.id','=','acc_trans_prnts.acc_period_id');
		})
		->join('acc_chart_ctrl_heads',function($join){
		$join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
		})
		->join('acc_chart_sub_groups',function($join){
		$join->on('acc_chart_sub_groups.id','=','acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('acc_trans_prnts.company_id', '=', request('company_id', 0));
		})

		->when(request('company_id'), function ($q) use($start_date) {
		return $q->where('acc_trans_prnts.trans_date', '>=',$start_date);
		})
		->when(request('company_id'), function ($q) use($end_date){
		return $q->where('acc_trans_prnts.trans_date', '<=',$end_date);
		})
		->whereNull('acc_trans_chlds.deleted_at')
		->whereIn('acc_chart_ctrl_heads.statement_type_id',[1,3])
		->groupBy($group)
		->orderBy($order)
		->get();



		$dataAcc = DB::table("acc_chart_ctrl_heads")
		->selectRaw($sql1)
		
		->join('acc_chart_sub_groups',function($join){
		$join->on('acc_chart_sub_groups.id','=','acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->groupBy($group1)
		
		->whereNull('acc_chart_ctrl_heads.deleted_at')
		->where([['acc_chart_ctrl_heads.statement_type_id','=',3]])
		->get();

		


		$dataIncome = DB::table("acc_trans_chlds")
		->selectRaw('acc_chart_sub_groups.acc_chart_group_id,
		(CASE WHEN acc_chart_sub_groups.acc_chart_group_id = 1 or acc_chart_sub_groups.acc_chart_group_id = 4 or acc_chart_sub_groups.acc_chart_group_id = 7 or acc_chart_sub_groups.acc_chart_group_id = 16 or acc_chart_sub_groups.acc_chart_group_id = 25
                      THEN abs(sum (acc_trans_chlds.amount))
                      ELSE sum (acc_trans_chlds.amount)
                  END ) AS amount')
		->join('acc_trans_prnts',function($join){
		$join->on('acc_trans_prnts.id','=','acc_trans_chlds.acc_trans_prnt_id');
		})
		->join('acc_periods',function($join){
		$join->on('acc_periods.id','=','acc_trans_prnts.acc_period_id');
		})
		->join('acc_chart_ctrl_heads',function($join){
		$join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
		})
		->join('acc_chart_sub_groups',function($join){
		$join->on('acc_chart_sub_groups.id','=','acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('acc_trans_prnts.company_id', '=', request('company_id', 0));
		})

		->when(request('company_id'), function ($q) use($start_date) {
		return $q->where('acc_trans_prnts.trans_date', '>=',$start_date);
		})
		->when(request('company_id'), function ($q) use($end_date){
		return $q->where('acc_trans_prnts.trans_date', '<=',$end_date);
		})
		->whereNull('acc_trans_chlds.deleted_at')
		->where([['acc_chart_ctrl_heads.statement_type_id','=',2]])
		->groupBy('acc_chart_sub_groups.acc_chart_group_id')
		->orderBy('acc_chart_sub_groups.acc_chart_group_id')
		->get()->pluck('amount', 'acc_chart_group_id')->toArray();
        
	    $OperatingRevenue=isset($dataIncome[16])?$dataIncome[16]:0;
		$CostofGoodsSold=isset($dataIncome[19])?$dataIncome[19]:0;
		$OperatingExpenses=isset($dataIncome[22])?$dataIncome[22]:0;
		$FinancialExpenses=isset($dataIncome[24])?$dataIncome[24]:0;
		$NonOperatingRevenue=isset($dataIncome[25])?$dataIncome[25]:0;
		$NonOperatingExpenses=isset($dataIncome[28])?$dataIncome[28]:0;
		$ExtraOrdinaryItems=isset($dataIncome[50])?$dataIncome[50]:0;
		$TaxExpenses=isset($dataIncome[55])?$dataIncome[55]:0;
		$currentGrossprofit=$OperatingRevenue - $CostofGoodsSold;
		$currentOperatingprofit=$currentGrossprofit-$OperatingExpenses;
		$currentprofitBfrNonOperatingExpenses=($currentOperatingprofit-$FinancialExpenses)+($NonOperatingRevenue);
		$currentprofitBfrTax=($currentprofitBfrNonOperatingExpenses-$NonOperatingExpenses-$ExtraOrdinaryItems);
		$currentNetprofit=($currentprofitBfrTax-$TaxExpenses);



		
		
	







		$dataLastPeriods = DB::table("acc_trans_chlds")
		->selectRaw($sql)
		->join('acc_trans_prnts',function($join){
		$join->on('acc_trans_prnts.id','=','acc_trans_chlds.acc_trans_prnt_id');
		})
		->join('acc_periods',function($join){
		$join->on('acc_periods.id','=','acc_trans_prnts.acc_period_id');
		})
		->join('acc_chart_ctrl_heads',function($join){
		$join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
		})
		->join('acc_chart_sub_groups',function($join){
		$join->on('acc_chart_sub_groups.id','=','acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('acc_trans_prnts.company_id', '=', request('company_id', 0));
		})

		->when(request('company_id'), function ($q) use($lastPeriodStart) {
		return $q->where('acc_trans_prnts.trans_date', '>=',$lastPeriodStart);
		})
		->when(request('company_id'), function ($q) use($lastPeriodEnd){
		return $q->where('acc_trans_prnts.trans_date', '<=',$lastPeriodEnd);
		})
		->whereNull('acc_trans_chlds.deleted_at')
		->whereIn('acc_chart_ctrl_heads.statement_type_id',[1,3])
		->groupBy($group)
		->orderBy($order)
		->get();


		$dataIncomeLast = DB::table("acc_trans_chlds")
		->selectRaw('acc_chart_sub_groups.acc_chart_group_id,
		(CASE WHEN acc_chart_sub_groups.acc_chart_group_id = 1 or acc_chart_sub_groups.acc_chart_group_id = 4 or acc_chart_sub_groups.acc_chart_group_id = 7 or acc_chart_sub_groups.acc_chart_group_id = 16 or acc_chart_sub_groups.acc_chart_group_id = 25
                      THEN abs(sum (acc_trans_chlds.amount))
                      ELSE sum (acc_trans_chlds.amount)
                  END ) AS amount')
		->join('acc_trans_prnts',function($join){
		$join->on('acc_trans_prnts.id','=','acc_trans_chlds.acc_trans_prnt_id');
		})
		->join('acc_periods',function($join){
		$join->on('acc_periods.id','=','acc_trans_prnts.acc_period_id');
		})
		->join('acc_chart_ctrl_heads',function($join){
		$join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
		})
		->join('acc_chart_sub_groups',function($join){
		$join->on('acc_chart_sub_groups.id','=','acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('acc_trans_prnts.company_id', '=', request('company_id', 0));
		})

		->when(request('company_id'), function ($q) use($lastPeriodStart) {
		return $q->where('acc_trans_prnts.trans_date', '>=',$lastPeriodStart);
		})
		->when(request('company_id'), function ($q) use($lastPeriodEnd){
		return $q->where('acc_trans_prnts.trans_date', '<=',$lastPeriodEnd);
		})
		->whereNull('acc_trans_chlds.deleted_at')
		->where([['acc_chart_ctrl_heads.statement_type_id','=',2]])
		->groupBy('acc_chart_sub_groups.acc_chart_group_id')
		->orderBy('acc_chart_sub_groups.acc_chart_group_id')
		->get()->pluck('amount', 'acc_chart_group_id')->toArray();
        
	    $LastOperatingRevenue=isset($dataIncomeLast[16])?$dataIncomeLast[16]:0;
		$LastCostofGoodsSold=isset($dataIncomeLast[19])?$dataIncomeLast[19]:0;
		$LastOperatingExpenses=isset($dataIncomeLast[22])?$dataIncomeLast[22]:0;
		$LastFinancialExpenses=isset($dataIncomeLast[24])?$dataIncomeLast[24]:0;
		$LastNonOperatingRevenue=isset($dataIncomeLast[25])?$dataIncomeLast[25]:0;
		$LastNonOperatingExpenses=isset($dataIncomeLast[28])?$dataIncomeLast[28]:0;
		$LastExtraOrdinaryItems=isset($dataIncomeLast[50])?$dataIncomeLast[50]:0;
		$LastTaxExpenses=isset($dataIncomeLast[55])?$dataIncomeLast[55]:0;

		$lastGrossprofit=$LastOperatingRevenue - $LastCostofGoodsSold;
		$lastOperatingprofit=$lastGrossprofit-$LastOperatingExpenses;
		$lastprofitBfrNonOperatingExpenses=($lastOperatingprofit-$LastFinancialExpenses)+($LastNonOperatingRevenue);
		$lastprofitBfrTax=($lastprofitBfrNonOperatingExpenses-$LastNonOperatingExpenses-$LastExtraOrdinaryItems);
		$lastNetprofit=($lastprofitBfrTax-$LastTaxExpenses);


		

		$accchartgroup=config('bprs.accchartgroup');

		$accchartctrlhead=array_prepend(array_pluck($this->accchartctrlhead->get(),'name','id'),'-Select-','0');

		$reportData=array();

		foreach($data as $row){
			
			$reportData[$row->acc_chart_group_id][$row->id]['acc_chart_group_name']=$accchartgroup[$row->acc_chart_group_id];
			if($level==2)
			{
				$reportData[$row->acc_chart_group_id][$row->id]['particulars']=$accchartctrlhead[$row->id];
			}
			else
			{
				$reportData[$row->acc_chart_group_id][$row->id]['particulars']=$row->name;
			}

			
			$reportData[$row->acc_chart_group_id][$row->id]['current_amount']=$row->amount;
			$reportData[$row->acc_chart_group_id][$row->id]['last_amount']=0;

		}

		

		

		foreach($dataLastPeriods as $dataLastPeriodsRow){
			$reportData[$dataLastPeriodsRow->acc_chart_group_id][$dataLastPeriodsRow->id]['acc_chart_group_name']=$accchartgroup[$dataLastPeriodsRow->acc_chart_group_id];
			if($level==2)
			{
				$reportData[$dataLastPeriodsRow->acc_chart_group_id][$dataLastPeriodsRow->id]['particulars']=$accchartctrlhead[$dataLastPeriodsRow->id];
			}
			else
			{
				$reportData[$dataLastPeriodsRow->acc_chart_group_id][$dataLastPeriodsRow->id]['particulars']=$dataLastPeriodsRow->name;
			}

			if(isset($reportData[$dataLastPeriodsRow->acc_chart_group_id][$dataLastPeriodsRow->id]['current_amount'])){
				//continue;
			}else{
				$reportData[$dataLastPeriodsRow->acc_chart_group_id][$dataLastPeriodsRow->id]['current_amount']=0;
			}
			
			$reportData[$dataLastPeriodsRow->acc_chart_group_id][$dataLastPeriodsRow->id]['last_amount']=$dataLastPeriodsRow->amount;

		}

		foreach($dataAcc as $dataAccrow){
			
			$reportData[$dataAccrow->acc_chart_group_id][$dataAccrow->id]['acc_chart_group_name']=$accchartgroup[$dataAccrow->acc_chart_group_id];
			if($level==2)
			{
				$reportData[$dataAccrow->acc_chart_group_id][$dataAccrow->id]['particulars']=$accchartctrlhead[$dataAccrow->id];
			}
			else
			{
				$reportData[$dataAccrow->acc_chart_group_id][$dataAccrow->id]['particulars']=$dataAccrow->name;
			}

			if(isset($reportData[$dataAccrow->acc_chart_group_id][$dataAccrow->id]['current_amount'])){
				$reportData[$dataAccrow->acc_chart_group_id][$dataAccrow->id]['current_amount']+=$currentNetprofit;
			}else{
				$reportData[$dataAccrow->acc_chart_group_id][$dataAccrow->id]['current_amount']=$currentNetprofit;
			}

			if(isset($reportData[$dataAccrow->acc_chart_group_id][$dataAccrow->id]['last_amount'])){
				$reportData[$dataAccrow->acc_chart_group_id][$dataAccrow->id]['last_amount']+=$lastNetprofit;
			}else{
				$reportData[$dataAccrow->acc_chart_group_id][$dataAccrow->id]['last_amount']=$lastNetprofit;
			}

		}
        
		$company=$this->company->where([['id','=',request('company_id', 0)]])->get()->first();
        $asat=date('d-M-Y',strtotime($end_date));
		return ['data'=>$reportData,'company'=>$company,'accchartgroup'=>$accchartgroup,'asat'=>$asat];



		
		

		


		


		

		


		

     }
	public function html() {
		return Template::loadView('Report.Account.BalanceSheetData',$this->getData());
    }


    public function pdf() {
	$pdf = new \TCPDF('PDF_PAGE_ORIENTATION', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$pdf->SetFont('helvetica', 'B', 12);
	$pdf->AddPage();
	$pdf->SetY(10);

	$data=$this->getData();
	//$txt = $data['company']->name;

    //$pdf->SetY(5);
    //$pdf->Text(90, 5, $txt);
    //$image_file = url('/').'/images/logo/'.$data['company']->logo;
    $image_file ='images/logo/'.$data['company']->logo;
    $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    $pdf->SetY(10);
    $pdf->SetFont('helvetica', 'N', 10);
    //$pdf->Text(60, 12, $data['company']->address);
	$pdf->Cell(0, 40, $data['company']->address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
	$pdf->SetFont('helvetica', '', 8);


	$view= \View::make('Defult.Report.Account.BalanceSheetDataPdf',$data);
	$html_content=$view->render();
	$pdf->SetY(42);
	$pdf->WriteHtml($html_content, true, false,true,false,'');
    $filename = storage_path() . '/IncomeStatementDataPdf.pdf';
	$pdf->output($filename,'I');
	exit();
    }
}
