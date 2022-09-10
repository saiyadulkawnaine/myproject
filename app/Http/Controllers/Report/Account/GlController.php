<?php

namespace App\Http\Controllers\Report\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Account\AccYearRepository;

use App\Repositories\Contracts\Account\AccChartLocationRepository;
use App\Repositories\Contracts\Account\AccChartDivisionRepository;
use App\Repositories\Contracts\Account\AccChartDepartmentRepository;
use App\Repositories\Contracts\Account\AccChartSectionRepository;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;

use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Account\AccChartSubGroupRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;

class GlController extends Controller
{
	private $company;
	private $accyear;
	private $buyer;
    private $supplier;
    private $location;
    private $division;
    private $department;
    private $section;
    private $employee;
    private $accchartctrlhead;
    private $accchartsubgroup;
    private $currency;

	public function __construct(CompanyRepository $company,AccYearRepository $accyear,BuyerRepository $buyer,SupplierRepository $supplier,AccChartLocationRepository $location,AccChartDivisionRepository $division,AccChartDepartmentRepository $department,AccChartSectionRepository $section,EmployeeRepository $employee,AccChartCtrlHeadRepository $accchartctrlhead,AccChartSubGroupRepository $accchartsubgroup,CurrencyRepository $currency)
    {
		$this->company  = $company;
		$this->accyear    = $accyear;
		$this->buyer = $buyer;
        $this->supplier = $supplier;
        $this->location = $location;
        $this->division = $division;
        $this->department = $department;
        $this->section = $section;
        $this->employee = $employee;
        $this->accchartctrlhead = $accchartctrlhead;
        $this->accchartsubgroup = $accchartsubgroup;
        $this->currency = $currency;

		$this->middleware('auth');

		$this->middleware('permission:view.gl',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
		
      return Template::loadView('Report.Account.Gl',['company'=>$company,'accyear'=>$accyear]);
    }
    public function getYear(){
    	/*$currentYear=$this->accyear
    	->where([['company_id','=',request('company_id',0)]])
    	->where([['is_current','=',1]])
    	->get()
    	->map(function ($currentYear){
    		$currentYear->start_date=date('Y-m-d',strtotime($currentYear->start_date));
    		$currentYear->end_date=date('Y-m-d',strtotime($currentYear->end_date));
    		return $currentYear;
    	})
    	->first();
    	echo json_encode($currentYear);*/
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
    public function getDateRange(){
    	$allYear=$this->accyear
    	->where([['id','=',request('acc_year_id',0)]])
    	->get()
    	->map(function ($allYear){
    		$allYear->start_date=date('Y-m-d',strtotime($allYear->start_date));
    		$allYear->end_date=date('Y-m-d',strtotime($allYear->end_date));
    		return $allYear;
    	});
    	echo json_encode($allYear);
    }
     private function getData()
     {
     	$acc_year_id=request('acc_year_id',0);
		$accYear=$this->accyear
    	->where([['id','=',$acc_year_id]])
    	->get()->first();

    	$yearStart=date('Y-m-d',strtotime($accYear->start_date));
    	$yearEnd=date('Y-m-d',strtotime($accYear->end_date));

    	$start_date=request('date_from',0);
		$end_date=request('date_to',0);

    	if(!$start_date){
            $start_date=$accYear->start_date;
    	}
    	if(!$end_date){
            $end_date=$accYear->end_date;
    	}

		
		

		$start_date=date('Y-m-d',strtotime($start_date));
		$end_date=date('Y-m-d',strtotime($end_date));

		//$opening=array();

		$openingBalanceStartDate='';

		if($start_date < $yearStart){
			return response()->json(array('success' => false,'message' => 'Start is not in this year'),200);
		}
		else if($start_date>$yearStart){
			$openingBalanceStartDate=$yearStart;
			$openingBalanceEndDate = new \DateTime($start_date); // For today/now, don't pass an arg.
            $openingBalanceEndDate->modify("-1 day");
            $openingBalanceEndDate->format("Y-m-d"); 

			$opening = DB::table("acc_trans_chlds")
			->selectRaw('sum(acc_trans_chlds.amount) as amount, acc_chart_ctrl_heads.code')
			->join('acc_trans_prnts',function($join){
			$join->on('acc_trans_prnts.id','=','acc_trans_chlds.acc_trans_prnt_id');
			})
			->join('acc_periods',function($join){
			$join->on('acc_periods.id','=','acc_trans_prnts.acc_period_id');
			})
			->join('acc_chart_ctrl_heads',function($join){
			$join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
			})

			->when(request('company_id'), function ($q) {
			return $q->where('acc_trans_prnts.company_id', '=', request('company_id', 0));
			})
			->when(request('coa_id'), function ($q) {
			return $q->where('acc_chart_ctrl_heads.id', '=', request('coa_id', 0));
			})

			->when(request('date_from'), function ($q) use($openingBalanceStartDate) {
			return $q->where('acc_trans_prnts.trans_date', '>=',$openingBalanceStartDate);
			})
			->when(request('date_to'), function ($q) use($openingBalanceEndDate){
			return $q->where('acc_trans_prnts.trans_date', '<=',$openingBalanceEndDate->format("Y-m-d"));
			})
			->when(request('code_from'), function ($q) {
			return $q->where('acc_chart_ctrl_heads.code', '>=',request('code_from', 0));
			})
			->when(request('code_to'), function ($q) {
			return $q->where('acc_chart_ctrl_heads.code', '<=',request('code_to', 0));
			})
			->whereNull('acc_trans_chlds.deleted_at')
			->groupBy('acc_chart_ctrl_heads.code')
			->get()
			->pluck('amount','code');
			//echo json_encode($opening);
		}
		else{
			$openingBalanceStartDate=$start_date;



			$opening = DB::table("acc_trans_chlds")
			->selectRaw('sum(acc_trans_chlds.amount) as amount, acc_chart_ctrl_heads.code')
			->join('acc_trans_prnts',function($join){
			$join->on('acc_trans_prnts.id','=','acc_trans_chlds.acc_trans_prnt_id');
			})
			->join('acc_periods',function($join){
			$join->on('acc_periods.id','=','acc_trans_prnts.acc_period_id');
			})
			->join('acc_chart_ctrl_heads',function($join){
			$join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
			})
            ->when(request('coa_id'), function ($q) {
			return $q->where('acc_chart_ctrl_heads.id', '=', request('coa_id', 0));
			})
			->when(request('company_id'), function ($q) {
			return $q->where('acc_trans_prnts.company_id', '=', request('company_id', 0));
			})

			->when(request('date_from'), function ($q) use($openingBalanceStartDate) {
			return $q->where('acc_trans_prnts.trans_date', '>=',$openingBalanceStartDate);
			})
			->when(request('date_to'), function ($q) use($start_date){
			return $q->where('acc_trans_prnts.trans_date', '<=',$start_date);
			})
			->when(request('code_from'), function ($q) {
			return $q->where('acc_chart_ctrl_heads.code', '>=',request('code_from', 0));
			})
			->when(request('code_to'), function ($q) {
			return $q->where('acc_chart_ctrl_heads.code', '<=',request('code_to', 0));
			})
			->whereNull('acc_trans_chlds.deleted_at')
			->where([['acc_trans_prnts.trans_type_id','=',0]])
			->where([['acc_periods.period','=',0]])
			->groupBy('acc_chart_ctrl_heads.code')
			->get()
			->pluck('amount','code');
		}


		

		$journalType=array_prepend(config('bprs.journalType'),'-Select-','');
		$company=$this->company
		->where([['id','=',request('company_id', 0)]])->get()->first();
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
        $otherPartise=array_prepend(array_pluck($this->supplier->otherPartise(),'name','id'),'-Select-','');
        $employee=array_prepend(array_pluck($this->employee->get(),'name','id'),'','');


		$data = DB::table("acc_trans_chlds")
		->select("acc_trans_prnts.id",
		"acc_chart_ctrl_heads.code",
		"acc_chart_ctrl_heads.name",
		"acc_chart_ctrl_heads.control_name_id",
		"acc_trans_prnts.company_id",
		"acc_trans_prnts.trans_date",
		"acc_trans_prnts.trans_no",
		"acc_trans_prnts.instrument_no",
		"acc_trans_prnts.trans_type_id",
		"acc_trans_chlds.amount",
		"acc_trans_chlds.bill_no",
		"acc_trans_chlds.party_id",
		"acc_trans_chlds.employee_id",
		'acc_periods.end_date',
		'acc_periods.period',
		'acc_trans_chlds.chld_narration'
		)
		->join('acc_trans_prnts',function($join){
		$join->on('acc_trans_prnts.id','=','acc_trans_chlds.acc_trans_prnt_id');
		})
		->join('acc_periods',function($join){
		$join->on('acc_periods.id','=','acc_trans_prnts.acc_period_id');
		})
		->join('acc_chart_ctrl_heads',function($join){
		$join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
		})
        ->when(request('coa_id'), function ($q) {
			return $q->where('acc_chart_ctrl_heads.id', '=', request('coa_id', 0));
		})
		->when(request('company_id'), function ($q) {
		return $q->where('acc_trans_prnts.company_id', '=', request('company_id', 0));
		})

		->when(request('date_from'), function ($q) use($start_date) {
		return $q->where('acc_trans_prnts.trans_date', '>=',$start_date);
		})
		->when(request('date_to'), function ($q) use($end_date){
		return $q->where('acc_trans_prnts.trans_date', '<=',$end_date);
		})
		->when(request('code_from'), function ($q) {
		return $q->where('acc_chart_ctrl_heads.code', '>=',request('code_from', 0));
		})
		->when(request('code_to'), function ($q) {
		return $q->where('acc_chart_ctrl_heads.code', '<=',request('code_to', 0));
		})
		->whereNull('acc_trans_chlds.deleted_at')
		->where([['acc_periods.period','>=',0]])
		->where([['acc_trans_prnts.trans_type_id','>=',0]])
		->orderBy('acc_trans_prnts.trans_date')
		->orderBy('acc_trans_prnts.trans_no')
		->get()
		->map(function ($data) use ($journalType,$supplier,$buyer,$otherPartise,$employee) {
		if($data->amount < 0 ){
		$data->amount_credit =$data->amount*-1;
		$data->amount_debit =0;

		}
		else
		{
		$data->amount_debit =$data->amount;
		$data->amount_credit =0;
		}

//==============
		$data->party_name='';
		if($data->control_name_id ==1 || $data->control_name_id ==2 || $data->control_name_id ==10 || $data->control_name_id ==15 || $data->control_name_id == 20 || $data->control_name_id ==35 || $data->control_name_id == 62)
        {//purchase
        	$data->party_name =isset($supplier[$data->party_id])?$supplier[$data->party_id]:'';
        }

        else if($data->control_name_id ==5 || $data->control_name_id ==6 || $data->control_name_id ==30 || $data->control_name_id ==31 || $data->control_name_id == 40 || $data->control_name_id ==45 || $data->control_name_id ==50 || $data->control_name_id ==60)
        {//sales
                    
            $data->party_name =isset($buyer[$data->party_id])?$buyer[$data->party_id]:'';

        }

         else if ($data->control_name_id==38)
         {//other Party
            $data->party_name =isset($otherPartise[$data->party_id])?$otherPartise[$data->party_id]:'';
         }
		//===============
        $data->employee_name =isset($employee[$data->employee_id])?$employee[$data->employee_id]:'';
        $partyarr=array();
         if($data->party_name)
         {
            array_push($partyarr,$data->party_name);

         }
         if($data->employee_name)
         {
            array_push($partyarr,$data->employee_name);

         }
        $data->emp_party_name=implode(',',$partyarr);
		$data->trans_type=$journalType[$data->trans_type_id];
		$data->account=$data->code.":".$data->name;
		$data->trans_date=date("d-M-Y",strtotime($data->trans_date));
		return $data;
		});
		$grouped = $data->groupBy('account');
		$grouped->toArray();
		$user = \Auth::user();           
        $user = $user->id;
		return ['data'=>$grouped,'opening'=>$opening,'company'=>$company,'start_date'=>$start_date,'end_date'=>$end_date,'user'=>$user];

     }
	public function html() {
		return Template::loadView('Report.Account.GlData',$this->getData());
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


	$view= \View::make('Defult.Report.Account.GlDataPdf',$data);
	$html_content=$view->render();
	$pdf->SetY(42);
	$pdf->WriteHtml($html_content, true, false,true,false,'');
    $filename = storage_path() . '/glPdf.pdf';
	$pdf->output($filename,'I');
	exit();
    }

    public function getCode(){
    	$accchartsubgroup=array_prepend(array_pluck($this->accchartsubgroup->get(),'name','id'),'-Select-','');
		$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
		$ctrlHead=array_prepend(array_pluck($this->accchartctrlhead->get(),'name','id'),'-Select-','0');
		$ctrlheadtype=array_prepend(config('bprs.ctrlheadtype'),'-Select-','0');
		$statementType=array_prepend(config('bprs.statementType'),'-Select-','0');
		$controlname=array_prepend(config('bprs.controlname'),'-Select-','0');
		$otherType=array_prepend(config('bprs.otherType'),'-Select-','0');
		$normalbalance=array_prepend(config('bprs.normalbalance'),'-Select-','0');
		$accchartgroup=array_prepend(config('bprs.accchartgroup'),'-Select-','0');
		$status=array_prepend(config('bprs.status'),'-Select-','');
		$accchartctrlheads=array();
		$rows=$this->accchartctrlhead
		->join('acc_chart_sub_groups',function($join){
			$join->on('acc_chart_sub_groups.id','=','acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->orderBy('code','asc')
		->where([['ctrlhead_type_id','=',1]])
		->get([
			'acc_chart_ctrl_heads.*',
			'acc_chart_sub_groups.name as sub_group_name',
			'acc_chart_sub_groups.acc_chart_group_id'
		]);
		foreach ($rows as $row) {
		$accchartctrlhead['id']=$row->id;
		$accchartctrlhead['name']=$row->name;
		$accchartctrlhead['code']=$row->code;
		$accchartctrlhead['sort_id']=$row->sort_id;
		$accchartctrlhead['root_id']=isset($ctrlHead[$row->root_id])?$ctrlHead[$row->root_id]:0;
		$accchartctrlhead['sub_group_name']=$row->sub_group_name;
		$accchartctrlhead['accchartgroup']=isset($accchartgroup[$row->acc_chart_group_id])?$accchartgroup[$row->acc_chart_group_id]:0;

		$accchartctrlhead['ctrlhead_type_id']=isset($ctrlheadtype[$row->ctrlhead_type_id])?$ctrlheadtype[$row->ctrlhead_type_id]:'';
		$accchartctrlhead['statement_type_id']=isset($statementType[$row->statement_type_id])?$statementType[$row->statement_type_id]:'';
		$accchartctrlhead['retained_earning_account_id']=$row->retained_earning_account_id;
		$accchartctrlhead['control_name_id']=isset($controlname[$row->control_name_id])?$controlname[$row->control_name_id]:'';
		$accchartctrlhead['other_type_id']=isset($otherType[$row->other_type_id])?$otherType[$row->other_type_id]:'';
		$accchartctrlhead['currency_id']=isset($currency[$row->currency_id])?$currency[$row->currency_id]:'';
		$accchartctrlhead['normal_balance_id']=isset($normalbalance[$row->normal_balance_id])?$normalbalance[$row->normal_balance_id]:'';

		$accchartctrlhead['status']=isset($status[$row->row_status])?$status[$row->row_status]:'';

		array_push($accchartctrlheads,$accchartctrlhead);
		}
        echo json_encode($accchartctrlheads);

    }
}
