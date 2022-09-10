<?php

namespace App\Http\Controllers\Report\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Account\AccChartSubGroupRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;

class CoaController extends Controller
{
	private $accchartctrlhead;
	private $accchartsubgroup;
    private $currency;
	public function __construct(AccChartCtrlHeadRepository $accchartctrlhead,AccChartSubGroupRepository $accchartsubgroup,CurrencyRepository $currency)
    {
		$this->accchartctrlhead    = $accchartctrlhead;
		$this->accchartsubgroup = $accchartsubgroup;
        $this->currency = $currency;

		$this->middleware('auth');
		
		$this->middleware('permission:view.coas',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		
        return Template::loadView('Report.Account.Coa');
    }

    public function html()
    {
    	$ctrlhead=$this->getData();
    	echo json_encode($ctrlhead);
    }
    
     private function getData()
     {
		$accchartsubgroup=array_prepend(array_pluck($this->accchartsubgroup->get(),'name','id'),'-Select-','');
		$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'','');
		$ctrlHead=array_prepend(array_pluck($this->accchartctrlhead->get(),'name','id'),'','');
		$ctrlheadtype=config('bprs.ctrlheadtype');
		$statementType=config('bprs.statementType');
		$controlname=config('bprs.controlname');
		$otherType=config('bprs.otherType');
		$normalbalance=config('bprs.normalbalance');
		$accchartgroup=config('bprs.accchartgroup');
		$status=config('bprs.status');
		$yesno=config('bprs.yesno');
	    $expenseType=config('bprs.expenseType');
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
		$accchartctrlhead['control_name_id']=isset($controlname[$row->control_name_id])?$controlname[$row->control_name_id]:'--';
		$accchartctrlhead['other_type_id']=isset($otherType[$row->other_type_id])?$otherType[$row->other_type_id]:'--';
		$accchartctrlhead['currency_id']=isset($currency[$row->currency_id])?$currency[$row->currency_id]:'';
		$accchartctrlhead['normal_balance_id']=isset($normalbalance[$row->normal_balance_id])?$normalbalance[$row->normal_balance_id]:'--';
		$accchartctrlhead['status']=isset($status[$row->row_status])?$status[$row->row_status]:'';
		$accchartctrlhead['is_cm_expense']=isset($yesno[$row->is_cm_expense])?$yesno[$row->is_cm_expense]:'--';
		$accchartctrlhead['expense_type_id']=isset($expenseType[$row->expense_type_id])?$expenseType[$row->expense_type_id]:'--';

		array_push($accchartctrlheads,$accchartctrlhead);
		}
        return $accchartctrlheads;
     }
	


    public function pdf() {
	    $accchartsubgroup=array_prepend(array_pluck($this->accchartsubgroup->get(),'name','id'),'-Select-','');
		$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
		$ctrlH=array_prepend(array_pluck($this->accchartctrlhead->get(),'name','id'),'-Select-','0');
		$ctrlheadtype=config('bprs.ctrlheadtype');
		$statementType=config('bprs.statementType');
		$controlname=config('bprs.controlname');
		$otherType=config('bprs.otherType');
		$normalbalance=config('bprs.normalbalance');
		$accchartgroup=config('bprs.accchartgroup');
		$status=config('bprs.status');
		$expenseType=config('bprs.expenseType');
		$yesno=config('bprs.yesno');
		$accchartctrlheads=array();
		$ctrlhead=$this->accchartctrlhead
		->join('acc_chart_sub_groups',function($join){
			$join->on('acc_chart_sub_groups.id','=','acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->orderBy('code','asc')
		->where([['ctrlhead_type_id','=',1]])
		->get([
			'acc_chart_ctrl_heads.*',
			'acc_chart_sub_groups.name as sub_group_name',
			'acc_chart_sub_groups.acc_chart_group_id'
		])
		->map(function($ctrlhead) use($ctrlH,$accchartgroup,$statementType,$controlname,$otherType,$currency,$normalbalance,$status,$expenseType,$yesno){
			$ctrlhead->root_id=isset($ctrlH[$ctrlhead->root_id])?$ctrlH[$ctrlhead->root_id]:'';
			$ctrlhead->accchartgroup=isset($accchartgroup[$ctrlhead->acc_chart_group_id])?$accchartgroup[$ctrlhead->acc_chart_group_id]:'';
			$ctrlhead->statement_type_id=isset($statementType[$ctrlhead->statement_type_id])?$statementType[$ctrlhead->statement_type_id]:'';
			$ctrlhead->control_name_id=isset($controlname[$ctrlhead->control_name_id])?$controlname[$ctrlhead->control_name_id]:'--';
			$ctrlhead->other_type_id=isset($otherType[$ctrlhead->other_type_id])?$otherType[$ctrlhead->other_type_id]:'--';
			$ctrlhead->currency_id=isset($currency[$ctrlhead->currency_id])?$currency[$ctrlhead->currency_id]:'';
			$ctrlhead->normal_balance_id=isset($normalbalance[$ctrlhead->normal_balance_id])?$normalbalance[$ctrlhead->normal_balance_id]:'--';
			$ctrlhead->status=isset($status[$ctrlhead->row_status])?$status[$ctrlhead->row_status]:'';
			$ctrlhead->is_cm_expense=isset($yesno[$ctrlhead->is_cm_expense])?$yesno[$ctrlhead->is_cm_expense]:'--';
          	$ctrlhead->expense_type_id=isset($expenseType[$ctrlhead->expense_type_id])?$expenseType[$ctrlhead->expense_type_id]:'--';
			return $ctrlhead;
		});
		
		$pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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

		//$ctrlhead=$this->getData();
		$txt = 'Chart Of Accounts';

	    $pdf->SetY(5);
	    $pdf->Text(130, 5, $txt);
	    $pdf->SetY(10);
	    $pdf->SetFont('helvetica', 'N', 10);
	    //$pdf->Text(60, 10, $data['company']->address);
		$pdf->SetFont('helvetica', '', 8);


		$view= \View::make('Defult.Report.Account.CoaPdf',['ctrlhead'=>$ctrlhead]);
		$html_content=$view->render();
		$pdf->SetY(15);
		$pdf->WriteHtml($html_content, true, false,true,false,'');
	    $filename = storage_path() . '/CoaPdf.pdf';
		//$pdf->output($filename);
		$pdf->output($filename,'I');
		exit();
    }
}
