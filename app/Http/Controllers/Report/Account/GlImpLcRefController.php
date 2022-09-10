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
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;

class GlImpLcRefController extends Controller
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
	private $bankbranch;
	private $implc;

	public function __construct(CompanyRepository $company,AccYearRepository $accyear,BuyerRepository $buyer,SupplierRepository $supplier,AccChartLocationRepository $location,AccChartDivisionRepository $division,AccChartDepartmentRepository $department,AccChartSectionRepository $section,EmployeeRepository $employee,BankBranchRepository $bankbranch,ImpLcRepository $implc)
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
		$this->bankbranch = $bankbranch;
		$this->implc = $implc;

		//$this->middleware('auth');
		//$this->middleware('permission:view.glimplcref',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
		
      return Template::loadView('Report.Account.GlImpLcRef',['company'=>$company,'accyear'=>$accyear]);
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
     	$supplier_id=request('supplier_id',0);
	    $idarray=explode(',',$supplier_id);
     	$acc_year_id=request('acc_year_id',0);
		$accYear=$this->accyear
    	->where([['id','=',$acc_year_id]])
    	->where([['company_id','=',request('company_id',0)]])
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
			->selectRaw('sum(acc_trans_chlds.amount) as amount, acc_chart_ctrl_heads.code,acc_chart_ctrl_heads.name as acc_ctrl_head_name,acc_trans_chlds.import_lc_ref_no')
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
			->when(request('import_lc_ref_name'), function ($q) use ($idarray) {
				return $q->where('acc_trans_chlds.import_lc_ref_no','like','%'.request('import_lc_ref_name', 0).'%');
		    })
			->whereNotNull('acc_trans_chlds.import_lc_ref_no')
			->whereNull('acc_trans_chlds.deleted_at')
			->groupBy('acc_chart_ctrl_heads.code')
			->groupBy('acc_trans_chlds.import_lc_ref_no')
			->groupBy('acc_chart_ctrl_heads.name')
			->get()
			->groupBy(['import_lc_ref_no','code'])->toArray();
			//->pluck('amount','code');
			//echo json_encode($opening);
		}
		else{
			$openingBalanceStartDate=$start_date;



			$opening = DB::table("acc_trans_chlds")
			->selectRaw('sum(acc_trans_chlds.amount) as amount, acc_chart_ctrl_heads.code,acc_chart_ctrl_heads.name as acc_ctrl_head_name,acc_trans_chlds.import_lc_ref_no')
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
			->when(request('import_lc_ref_name'), function ($q) use ($idarray) {
			return $q->where('acc_trans_chlds.import_lc_ref_no','like','%'.request('import_lc_ref_name', 0).'%');
		    })
			->whereNull('acc_trans_chlds.deleted_at')
			->whereNotNull('acc_trans_chlds.import_lc_ref_no')
			->where([['acc_trans_prnts.trans_type_id','=',0]])
			->where([['acc_periods.period','=',0]])
			->groupBy('acc_chart_ctrl_heads.code')
			->groupBy('acc_trans_chlds.import_lc_ref_no')
			->groupBy('acc_chart_ctrl_heads.name')
			->get()
			->groupBy(['import_lc_ref_no','code'])->toArray();
			//->tosql();
			//dd($opening);
			//->pluck('amount','code');
		}


		

		$journalType=array_prepend(config('bprs.journalType'),'-Select-','');
		$company=$this->company
		->where([['id','=',request('company_id', 0)]])->get()->first();

		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'',''); //$this->buyer->get();
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');//$this->supplier->get();
        $otherPartise=array_prepend(array_pluck($this->supplier->otherPartise(),'name','id'),'-Select-','');//$this->supplier->otherPartise();
        $employee=array_prepend(array_pluck($this->employee->get(),'name','id'),'','');//$this->employee->get();


		$data = DB::table("acc_trans_chlds")
		->select("acc_trans_prnts.id",
		"acc_chart_ctrl_heads.code",
		"acc_chart_ctrl_heads.name",
		"acc_chart_ctrl_heads.control_name_id",
		"acc_trans_prnts.company_id",
		"acc_trans_prnts.trans_date",
		"acc_trans_prnts.trans_no",
		"acc_trans_prnts.trans_type_id",
		"acc_trans_chlds.amount",
		
		"acc_trans_chlds.import_lc_ref_no",
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
		->when(request('import_lc_ref_name'), function ($q) use ($idarray) {
			return $q->where('acc_trans_chlds.import_lc_ref_no','like','%'.request('import_lc_ref_name', 0).'%');
		})
		->whereNull('acc_trans_chlds.deleted_at')
		->whereNotNull('acc_trans_chlds.import_lc_ref_no')
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


        $data->import_lc_ref_name = $data->import_lc_ref_no;//isset($otherPartise[$data->supplier_id])?$otherPartise[$data->supplier_id]:'';

		$data->trans_type=$journalType[$data->trans_type_id];
		$data->account=$data->code.":".$data->name;
		$data->trans_date=date("d-M-Y",strtotime($data->trans_date));
		return $data;

		});

		$grouped = $data->groupBy(['import_lc_ref_no','account']);

		$grouped->toArray();
		return ['data'=>$grouped,'opening'=>$opening,'company'=>$company,'start_date'=>$start_date,'end_date'=>$end_date,'supplier'=>$otherPartise];

	}
	
	public function getImportLcRef(){
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
        $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
         
        $implcs=array();
        $rows=$this->implc
        ->when(request('company_id'), function ($q) {
            return $q->where('imp_lcs.company_id', '=', request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
            return $q->where('imp_lcs.supplier_id', '=', request('supplier_id', 0));
        })
        ->when(request('issuing_bank_branch_id'), function ($q) {
            return $q->where('imp_lcs.issuing_bank_branch_id', '=', request('issuing_bank_branch_id', 0));
        })
        ->get();
        foreach($rows as $row){
            $implc['id']=$row->id;
            $implc['company_id'] = $company[$row->company_id];
            $implc['supplier_id']= $supplier[$row->supplier_id];
            $implc['issuing_bank_branch_id']=$bankbranch[$row->issuing_bank_branch_id];
            $implc['lc_type_id']=  $lctype[$row->lc_type_id];
            $implc['last_delilvery_date']=date('Y-m-d',strtotime($row->last_delilvery_date));
            $implc['expiry_date']=date('Y-m-d',strtotime($row->expiry_date));
            $implc['lc_no']=$row->lc_no_i.$row->lc_no_ii.$row->lc_no_iii.$row->lc_no_iv;
            $implc['pay_term_id']=$payterm[$row->pay_term_id];
            $implc['exch_rate']=$row->exch_rate;
            $implc['tenor']=$row->tenor;
            
            array_push($implcs,$implc);
        }
        echo json_encode($implcs);
	}

	public function html() {
		return Template::loadView('Report.Account.GlDataImpLcRef',$this->getData());
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
    // $image_file = url('/').'/images/logo/'.$data['company']->logo;
    $image_file ='images/logo/'.$data['company']->logo;
    $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    $pdf->SetY(10);
    $pdf->SetFont('helvetica', 'N', 10);
    //$pdf->Text(60, 12, $data['company']->address);
	$pdf->Cell(0, 40, $data['company']->address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
	$pdf->SetFont('helvetica', '', 8);


	$view= \View::make('Defult.Report.Account.GlDataPdfImpLcRef',$data);
	$html_content=$view->render();
	$pdf->SetY(40);
	$pdf->WriteHtml($html_content, true, false,true,false,'');
    $filename = storage_path() . '/glImpLcRefPdf.pdf';
	$pdf->output($filename,'I');
	exit();
    }
}
