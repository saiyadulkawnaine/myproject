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

class MrrCheckController extends Controller
{
	private $company;
	private $accyear;
	private $accchartctrlhead;
	private $profitcenter;
   

	public function __construct(
		CompanyRepository $company,
		BuyerRepository $buyer,
		SupplierRepository $supplier,
		AccYearRepository $accyear,
		AccChartCtrlHeadRepository $accchartctrlhead,
		ProfitcenterRepository $profitcenter
	)
    {
		$this->company  = $company;
		$this->buyer  = $buyer;
		$this->supplier  = $supplier;
		$this->accyear    = $accyear;
		$this->accchartctrlhead = $accchartctrlhead;
		$this->profitcenter = $profitcenter;
       
		$this->middleware('auth');

		//$this->middleware('permission:view.incomestatement',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
		$supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
		$accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
	    $profitcenter=array_prepend(array_pluck($this->profitcenter->get(),'name','id'),'-Select-','');
		$menu=array_prepend(array_only(config('bprs.menu'),[1,2,3,4,5,6,7,8,9,10,11]),'-Select-','');
		
      return Template::loadView('Report.Account.MrrCheck',['company'=>$company,'accyear'=>$accyear,'profitcenter'=>$profitcenter,'buyer'=>$buyer,'supplier'=>$supplier,'menu'=>$menu]);
    }


	private function reportData()
	{
		$mrr_no=request('mrr_no');
		$results = \DB::select("
			select 
			acc_trans_prnts.id, 
			acc_trans_prnts.company_id, 
			acc_trans_prnts.acc_year_id, 
			acc_trans_prnts.acc_period_id, 
			acc_trans_prnts.trans_date, 
			acc_trans_prnts.page_id, 
			acc_trans_prnts.trans_type_id, 
			acc_trans_prnts.trans_no, 
			acc_trans_prnts.bank_id, 
			acc_trans_prnts.instrument_no, 
			acc_trans_prnts.pay_to, 
			acc_trans_prnts.place_date, 
			acc_trans_prnts.amount, 
			acc_trans_prnts.amount_foreign, 
			acc_trans_prnts.is_reversed, 
			acc_trans_prnts.is_locked, 
			acc_trans_prnts.narration, 
			users.name as user_name,
			updatedbys.name as updated_by,
			acc_trans_prnts.created_at,
			acc_trans_prnts.updated_at,
			sum(acc_trans_chlds.amount) as amount

			from
			acc_trans_chlds
			join acc_trans_prnts on acc_trans_prnts.id=acc_trans_chlds.acc_trans_prnt_id
			join acc_periods on acc_periods.id=acc_trans_prnts.acc_period_id
			join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_chlds.acc_chart_ctrl_head_id
			join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
			join users on acc_trans_prnts.created_by=users.id
			join users updatedbys on acc_trans_prnts.updated_by=updatedbys.id
			where acc_trans_prnts.company_id=1
			and acc_trans_prnts.deleted_at is null
			and acc_trans_chlds.deleted_at is null
			and (acc_trans_prnts.narration like '%".$mrr_no."%' or acc_trans_chlds.chld_narration like '%920%')


			group by 
			acc_trans_prnts.id, 
			acc_trans_prnts.company_id, 
			acc_trans_prnts.acc_year_id, 
			acc_trans_prnts.acc_period_id, 
			acc_trans_prnts.trans_date, 
			acc_trans_prnts.page_id, 
			acc_trans_prnts.trans_type_id, 
			acc_trans_prnts.trans_no, 
			acc_trans_prnts.bank_id, 
			acc_trans_prnts.instrument_no, 
			acc_trans_prnts.pay_to, 
			acc_trans_prnts.place_date, 
			acc_trans_prnts.amount, 
			acc_trans_prnts.amount_foreign, 
			acc_trans_prnts.is_reversed, 
			acc_trans_prnts.is_locked, 
			acc_trans_prnts.narration,
			acc_trans_prnts.created_at,
			acc_trans_prnts.updated_at,
			users.name,
			updatedbys.name
			");
		$rows=collect($results)
		->map(function($rows){
			$rows->trans_date=date('d-M-Y',strtotime($rows->trans_date));
			$rows->created_at=date('d-M-Y h:i:s',strtotime($rows->created_at));
			$rows->updated_at=date('d-M-Y h:i:s',strtotime($rows->updated_at));
			return $rows;

		});
		return $rows;
	}
	public function html() {
		return response()->json($this->reportData());
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
	$pdf->Cell(0, 40, $data['company']->address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
   // $pdf->Text(60, 12, $data['company']->address);
	$pdf->SetFont('helvetica', '', 8);


	$view= \View::make('Defult.Report.Account.IncomeStatementDataPdf',$data);
	$html_content=$view->render();
	$pdf->SetY(40);
	$pdf->WriteHtml($html_content, true, false,true,false,'');
    $filename = storage_path() . '/IncomeStatementDataPdf.pdf';
	$pdf->output($filename,'I');
	exit();
    }
}
