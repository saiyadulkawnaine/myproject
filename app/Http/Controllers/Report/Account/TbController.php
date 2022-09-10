<?php

namespace App\Http\Controllers\Report\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Account\AccYearRepository;

class TbController extends Controller
{
	private $company;
	private $accyear;
	public function __construct(CompanyRepository $company,AccYearRepository $accyear)
    {
		$this->company  = $company;
		$this->accyear    = $accyear;
		$this->middleware('auth');

		$this->middleware('permission:view.tbs',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
		
      return Template::loadView('Report.Account.Tb',['company'=>$company,'accyear'=>$accyear]);
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

		
        $start_date=$accYear->start_date;
		$end_date=request('date_to',0);
    	
    	if(!$end_date){
            $end_date=$accYear->end_date;
    	}

		
		

		$start_date=date('Y-m-d',strtotime($start_date));
		$end_date=date('Y-m-d',strtotime($end_date));

		
		


		

		$company=$this
		->company
		->where([['id','=',request('company_id', 0)]])->get()->first();


		$data = DB::table("acc_trans_chlds")
		->selectRaw('sum(acc_trans_chlds.amount) as amount, acc_chart_ctrl_heads.code,acc_chart_ctrl_heads.name,acc_chart_ctrl_heads.id')
		->join('acc_trans_prnts',function($join){
		$join->on('acc_trans_prnts.id','=','acc_trans_chlds.acc_trans_prnt_id');
		})
		->join('acc_periods',function($join){
		$join->on('acc_periods.id','=','acc_trans_prnts.acc_period_id');
		})
		->join('acc_chart_ctrl_heads',function($join){
		$join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
		})

		->when(request('company_id'), function ($q)  {
		return $q->where('acc_trans_prnts.company_id', '=', request('company_id', 0));
		})

		->when(request('date_from'), function ($q) use($start_date){
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
		->groupBy('acc_chart_ctrl_heads.id')
		->groupBy('acc_chart_ctrl_heads.name')
		->groupBy('acc_chart_ctrl_heads.code')
		->orderBy('acc_chart_ctrl_heads.code')
		->get()
		->map(function ($data){
		if($data->amount < 0 ){
		$data->amount_credit =$data->amount*-1;
		$data->amount_debit =0;

		}
		else
		{
		$data->amount_debit =$data->amount;
		$data->amount_credit =0;
		}
		return $data;

		});

		
		return ['data'=>$data,'company'=>$company,'start_date'=>$start_date,'end_date'=>$end_date];

     }
	public function html() {
		return Template::loadView('Report.Account.TbData',$this->getData());
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


	$view= \View::make('Defult.Report.Account.TbDataPdf',$data);
	$html_content=$view->render();
	$pdf->SetY(40);
	$pdf->WriteHtml($html_content, true, false,true,false,'');
    $filename = storage_path() . '/glPdf.pdf';
	//$pdf->output($filename);
	$pdf->output($filename,'I');
	exit();
    }
}
