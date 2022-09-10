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
use App\Repositories\Contracts\Util\ProfitcenterRepository;
use Illuminate\Support\Carbon;

class IncomeStatementController extends Controller
{
	private $company;
	private $accyear;
	private $accchartctrlhead;
	private $profitcenter;
   

	public function __construct(
		CompanyRepository $company,
		AccYearRepository $accyear,
		AccChartCtrlHeadRepository $accchartctrlhead,
		ProfitcenterRepository $profitcenter
	)
    {
		$this->company  = $company;
		$this->accyear    = $accyear;
		$this->accchartctrlhead = $accchartctrlhead;
		$this->profitcenter = $profitcenter;
       
		$this->middleware('auth');

		$this->middleware('permission:view.incomestatement',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
	    $profitcenter=array_prepend(array_pluck($this->profitcenter->get(),'name','id'),'-Select-','');

		
      return Template::loadView('Report.Account.IncomeStatement',['company'=>$company,'accyear'=>$accyear,'profitcenter'=>$profitcenter]);
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
     	$from_periods=request('from_periods',0);
     	$to_periods=request('to_periods',0);
     	$level=request('level',0);

		$accYear=$this->accyear
    	->where([['id','=',$acc_year_id]])
    	->where([['company_id','=',$company_id]])
    	->get()->first();

    	$FromPeriod=$this->accyear
    	->join('acc_periods',function($join){
			$join->on('acc_periods.acc_year_id','=','acc_years.id');
		})
		->where([['acc_years.company_id','=',$company_id]])
    	->where([['acc_years.id','=',$acc_year_id]])
    	->where([['acc_periods.id','=',$from_periods]])
    	->get()->first();

    	$ToPeriod=$this->accyear
    	->join('acc_periods',function($join){
			$join->on('acc_periods.acc_year_id','=','acc_years.id');
		})
		->where([['acc_years.company_id','=',$company_id]])
    	->where([['acc_years.id','=',$acc_year_id]])
    	->where([['acc_periods.id','=',$to_periods]])
    	->get()->first();
    	

    	$yearStart=date('Y-m-d',strtotime($accYear->start_date));
    	$yearEnd=date('Y-m-d',strtotime($accYear->end_date));

    	$fromPeriodStart=date('Y-m-d',strtotime($FromPeriod->start_date));
    	$fromPeriodEnd=date('Y-m-d',strtotime($FromPeriod->end_date));

    	$toPeriodStart=date('Y-m-d',strtotime($ToPeriod->start_date));
    	$toPeriodEnd=date('Y-m-d',strtotime($ToPeriod->end_date));

    	$d1 = new \DateTime($fromPeriodStart);
        $d2 = new \DateTime($toPeriodEnd);


    	 $date = Carbon::parse($fromPeriodStart);
         $now = Carbon::parse($toPeriodEnd);
         $diff = $date->diffInDays($now)+1;


	    $lastPeriodStart = new \DateTime($fromPeriodStart); // For today/now, don't pass an arg.
	    $lastPeriodStart->modify("- $diff days");
	    $lastPeriodStart->format("Y-m-d");

	    $lastPeriodEnd = new \DateTime($fromPeriodStart); // For today/now, don't pass an arg.
        $lastPeriodEnd->modify("-1 day");
        $lastPeriodEnd->format("Y-m-d");



        $group=array();

        if($level==1){
        $sql='acc_chart_sub_groups.acc_chart_group_id,
		acc_chart_sub_groups.id,
		acc_chart_sub_groups.name,

		(CASE WHEN acc_chart_sub_groups.acc_chart_group_id = 1 or acc_chart_sub_groups.acc_chart_group_id = 4 or acc_chart_sub_groups.acc_chart_group_id = 7 or acc_chart_sub_groups.acc_chart_group_id = 16 or acc_chart_sub_groups.acc_chart_group_id = 25
                      THEN abs(sum (acc_trans_chlds.amount))
                      ELSE sum (acc_trans_chlds.amount)
                  END ) AS amount';
		$group=['acc_chart_sub_groups.acc_chart_group_id','acc_chart_sub_groups.id','acc_chart_sub_groups.name'];
		$order='acc_chart_sub_groups.acc_chart_group_id';

        }
        if($level==2){
        $sql='acc_chart_sub_groups.acc_chart_group_id,
		acc_chart_ctrl_heads.root_id as id,
		(CASE WHEN acc_chart_sub_groups.acc_chart_group_id = 1 or acc_chart_sub_groups.acc_chart_group_id = 4 or acc_chart_sub_groups.acc_chart_group_id = 7 or acc_chart_sub_groups.acc_chart_group_id = 16 or acc_chart_sub_groups.acc_chart_group_id = 25
                      THEN abs(sum (acc_trans_chlds.amount))
                      ELSE sum (acc_trans_chlds.amount)
                  END ) AS amount';
		$group=['acc_chart_sub_groups.acc_chart_group_id','acc_chart_ctrl_heads.root_id'];
		$order='acc_chart_sub_groups.acc_chart_group_id';

        }
        if($level==3){
        $sql='acc_chart_sub_groups.acc_chart_group_id,
		acc_chart_ctrl_heads.id,
		acc_chart_ctrl_heads.code,
		acc_chart_ctrl_heads.name,
		(CASE WHEN acc_chart_sub_groups.acc_chart_group_id = 1 or acc_chart_sub_groups.acc_chart_group_id = 4 or acc_chart_sub_groups.acc_chart_group_id = 7 or acc_chart_sub_groups.acc_chart_group_id = 16 or acc_chart_sub_groups.acc_chart_group_id = 25
                      THEN abs(sum (acc_trans_chlds.amount))
                      ELSE sum (acc_trans_chlds.amount)
                  END ) AS amount';
		$group=['acc_chart_sub_groups.acc_chart_group_id','acc_chart_ctrl_heads.id','acc_chart_ctrl_heads.code','acc_chart_ctrl_heads.name'];
		$order='acc_chart_ctrl_heads.code';
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

		->when(request('company_id'), function ($q) use($fromPeriodStart) {
		return $q->where('acc_trans_prnts.trans_date', '>=',$fromPeriodStart);
		})
		->when(request('company_id'), function ($q) use($toPeriodEnd){
		return $q->where('acc_trans_prnts.trans_date', '<=',$toPeriodEnd);
		})
		->when(request('profitcenter_id'), function ($q) use($toPeriodEnd){
		return $q->where('acc_trans_chlds.profitcenter_id', '=',request('profitcenter_id'));
		})
		->whereNull('acc_trans_chlds.deleted_at')
		->where([['acc_chart_ctrl_heads.statement_type_id','=',2]])
		->groupBy($group)
		->orderBy($order)
		->get();



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
		->when(request('profitcenter_id'), function ($q) use($toPeriodEnd){
		return $q->where('acc_trans_chlds.profitcenter_id', '=',request('profitcenter_id'));
		})
		->whereNull('acc_trans_chlds.deleted_at')
		->where([['acc_chart_ctrl_heads.statement_type_id','=',2]])
		->groupBy($group)
		->orderBy($order)
		->get();


		$yearToDate = DB::table("acc_trans_chlds")
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

		->when(request('company_id'), function ($q) use($yearStart) {
		return $q->where('acc_trans_prnts.trans_date', '>=',$yearStart);
		})
		->when(request('company_id'), function ($q) use($toPeriodEnd){
		return $q->where('acc_trans_prnts.trans_date', '<=',$toPeriodEnd);
		})
		->when(request('profitcenter_id'), function ($q) use($toPeriodEnd){
		return $q->where('acc_trans_chlds.profitcenter_id', '=',request('profitcenter_id'));
		})
		->whereNull('acc_trans_chlds.deleted_at')
		->where([['acc_chart_ctrl_heads.statement_type_id','=',2]])
		->groupBy($group)
		
		->orderBy($order)
		->get();

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
			$reportData[$row->acc_chart_group_id][$row->id]['year_amount']=0;

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
			$reportData[$dataLastPeriodsRow->acc_chart_group_id][$dataLastPeriodsRow->id]['year_amount']=0;

		}

		foreach($yearToDate as $yearToDateRow){

			$reportData[$yearToDateRow->acc_chart_group_id][$yearToDateRow->id]['acc_chart_group_name']=$accchartgroup[$yearToDateRow->acc_chart_group_id];
			if($level==2)
			{
				$reportData[$yearToDateRow->acc_chart_group_id][$yearToDateRow->id]['particulars']=$accchartctrlhead[$yearToDateRow->id];
			}
			else
			{
				$reportData[$yearToDateRow->acc_chart_group_id][$yearToDateRow->id]['particulars']=$yearToDateRow->name;
			}

			if(isset($reportData[$yearToDateRow->acc_chart_group_id][$yearToDateRow->id]['current_amount'])){
				//continue;
			}else{
				$reportData[$yearToDateRow->acc_chart_group_id][$yearToDateRow->id]['current_amount']=0;
			}

			if(isset($reportData[$yearToDateRow->acc_chart_group_id][$yearToDateRow->id]['last_amount'])){
				//continue;
			}else{
				$reportData[$yearToDateRow->acc_chart_group_id][$yearToDateRow->id]['last_amount']=0;
			}
			
			$reportData[$yearToDateRow->acc_chart_group_id][$yearToDateRow->id]['year_amount']=$yearToDateRow->amount;

		}

        
		$company=$this->company->where([['id','=',request('company_id', 0)]])->get()->first();

		$ls=$lastPeriodStart->format("M");
		$le=$lastPeriodEnd->format("M");

		$lastPeriod=$ls."-".$le;


		$cs=$d1->format("M");
		$ce=$d2->format("M");
		$currentPeriod=$cs."-".$ce;
		$yearUpto="Upto ".$ce;

		$captionAccYear=$accYear->name;
		$profitcenterName=$this->profitcenter->where([['id','=',request('profitcenter_id')]])->get()->first();
		$profit_center_name=($profitcenterName)?"Profit Center: ".$profitcenterName['name']:'';

		return ['data'=>$reportData,'company'=>$company,'accchartgroup'=>$accchartgroup,'lastPeriod'=>$lastPeriod,'currentPeriod'=>$currentPeriod,'yearUpto'=>$yearUpto,'captionAccYear'=>$captionAccYear,'profit_center_name'=>$profit_center_name];
    }
    
	public function html() {
		return Template::loadView('Report.Account.IncomeStatementData',$this->getData());
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
    $pdf->SetFont('helvetica', 'N', 9);
    //$pdf->Text(60, 12, $data['company']->address);
	$pdf->Cell(0, 40, $data['company']->address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
	$pdf->SetFont('helvetica', '', 8);

	$view= \View::make('Defult.Report.Account.IncomeStatementDataPdf',$data);
	$html_content=$view->render();
	$pdf->SetY(35);
	$pdf->WriteHtml($html_content, true, false,true,false,'');
    $filename = storage_path() . '/IncomeStatementDataPdf.pdf';
	$pdf->output($filename,'I');
	exit();
    }
}
