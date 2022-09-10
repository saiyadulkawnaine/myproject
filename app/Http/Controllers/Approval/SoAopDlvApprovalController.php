<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Subcontract\AOP\SoAopDlvRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;

use App\Repositories\Contracts\Account\AccYearRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Account\AccChartSubGroupRepository;
use App\Library\Numbertowords;
use App\Library\Sms;
class SoAopDlvApprovalController extends Controller
{
    private $soaopdlv;
    private $user;
    private $buyer;
    private $company;
    private $currency;
	private $accyear;
    private $accchartctrlhead;
    private $accchartsubgroup;

    public function __construct(
		SoAopDlvRepository $soaopdlv,
		UserRepository $user,
		BuyerRepository $buyer,
		CompanyRepository $company,
		CurrencyRepository $currency,
		AccYearRepository $accyear,
		AccChartCtrlHeadRepository $accchartctrlhead,
		AccChartSubGroupRepository $accchartsubgroup

    ) {
        $this->soaopdlv = $soaopdlv;
        $this->user = $user;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->currency = $currency;
		$this->accyear    = $accyear;
		$this->accchartctrlhead = $accchartctrlhead;
        $this->accchartsubgroup = $accchartsubgroup;

        $this->middleware('auth');
        $this->middleware('permission:approve.soaopdlvs',   ['only' => ['approved', 'index','reportData']]);

    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
		$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.SoAopDlvApproval',['company'=>$company,'buyer'=>$buyer,'currency'=>$currency]);
    }
    
	public function reportData() {
		return response()->json(
			$this->soaopdlv
			->leftJoin('buyers', function($join)  {
			$join->on('so_aop_dlvs.buyer_id', '=', 'buyers.id');
			})
			->leftJoin('companies', function($join)  {
			$join->on('so_aop_dlvs.company_id', '=', 'companies.id');
			})
			->leftJoin('currencies', function($join)  {
			$join->on('so_aop_dlvs.currency_id', '=', 'currencies.id');
			})
			->when(request('company_id'), function ($q) {
			return $q->where('so_aop_dlvs.company_id', '=',request('company_id', 0));
			})
			->when(request('buyer_id'), function ($q) {
			return $q->where('so_aop_dlvs.buyer_id', '=',request('buyer_id', 0));
			})

			->when(request('date_from'), function ($q) {
			return $q->where('so_aop_dlvs.issue_date', '>=',request('date_from', 0));
			})
			->when(request('date_to'), function ($q) {
			return $q->where('so_aop_dlvs.issue_date', '<=',request('date_to', 0));
			})
			->whereNull('so_aop_dlvs.approved_at')
			->orderBy('so_aop_dlvs.id','desc')
			->get([
			'so_aop_dlvs.*',
			'buyers.name as buyer_name',
			'companies.name as company_name',
			'currencies.code as currency_code'
			])
			->map(function($rows){
			$rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));
			$rows->as_on_date=date('Y-m-d');
			return $rows;
			})
        );
    }

    public function approved (Request $request)
    {
    	$id=request('id',0);
    	$master=$this->soaopdlv->find($id);
		$accounts =collect(\DB::select('
		select 
		acc_trans_sales.buyer_id,
		sum(acc_trans_sales.amount) as amount 
		from acc_trans_prnts
		join acc_trans_sales on acc_trans_sales.acc_trans_prnt_id=acc_trans_prnts.id
		join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_sales.acc_chart_ctrl_head_id
		where acc_trans_sales.buyer_id=? and 
		acc_trans_prnts.company_id=? and
		acc_trans_sales.deleted_at is null and 
		acc_trans_prnts.deleted_at is null and 
		acc_chart_ctrl_heads.control_name_id=30
		group by acc_trans_sales.buyer_id', [$master->buyer_id,$master->company_id]))
		->first();

		$fabrcvbal =collect(\DB::select('
		select m.buyer_id, sum(m.amount) as amount from (select 
      so_aops.buyer_id,
      so_aop_fabric_rcv_items.so_aop_ref_id,
      sum(so_aop_fabric_rcv_items.qty) as qty ,
      avg(so_aop_fabric_rcv_items.rate) as rate,
      used.qty as used_qty,
      returned.qty as return_qty,
      CASE 
      WHEN used.qty is not null and returned.qty is not null THEN
      ((sum(so_aop_fabric_rcv_items.qty) - (used.qty+returned.qty) )*avg(so_aop_fabric_rcv_items.rate)) 
      WHEN used.qty is not null and returned.qty is null THEN
      ((sum(so_aop_fabric_rcv_items.qty) - used.qty )*avg(so_aop_fabric_rcv_items.rate))
      WHEN used.qty is null and returned.qty is not null THEN
      ((sum(so_aop_fabric_rcv_items.qty) - returned.qty )*avg(so_aop_fabric_rcv_items.rate))
      ELSE sum(so_aop_fabric_rcv_items.qty)*avg(so_aop_fabric_rcv_items.rate) 
      END as amount

      from so_aops
      join so_aop_fabric_rcvs on so_aop_fabric_rcvs.so_aop_id=so_aops.id
      join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.so_aop_fabric_rcv_id=so_aop_fabric_rcvs.id
      left join (
      select so_aop_dlvs.buyer_id,
      so_aop_dlv_items.so_aop_ref_id,
      sum(so_aop_dlv_items.grey_used) as qty 
      from so_aop_dlvs
      join so_aop_dlv_items on so_aop_dlv_items.so_aop_dlv_id=so_aop_dlvs.id
      where so_aop_dlvs.buyer_id=? and 
      so_aop_dlvs.company_id=? and
      so_aop_dlvs.deleted_at is null and 
      so_aop_dlv_items.deleted_at is null 
      group by 
      so_aop_dlvs.buyer_id,
      so_aop_dlv_items.so_aop_ref_id
      ) used on so_aop_fabric_rcv_items.so_aop_ref_id=used.so_aop_ref_id

      left join (
      select so_aop_fabric_rtns.buyer_id,
      so_aop_fabric_rtn_items.so_aop_ref_id,
      sum(so_aop_fabric_rtn_items.qty)  as qty
      from so_aop_fabric_rtns
      join so_aop_fabric_rtn_items on so_aop_fabric_rtn_items.so_aop_fabric_rtn_id=so_aop_fabric_rtns.id
      where so_aop_fabric_rtns.buyer_id=? and 
      so_aop_fabric_rtns.company_id=? and
      so_aop_fabric_rtns.deleted_at is null and 
      so_aop_fabric_rtn_items.deleted_at is null 
      group by 
      so_aop_fabric_rtns.buyer_id,
      so_aop_fabric_rtn_items.so_aop_ref_id
      ) returned on 
      so_aop_fabric_rcv_items.so_aop_ref_id=returned.so_aop_ref_id

      where so_aops.buyer_id=? and 
      so_aops.company_id=? and
      so_aops.deleted_at is null and 
      so_aop_fabric_rcvs.deleted_at is null and 
      so_aop_fabric_rcv_items.deleted_at is null and
      so_aop_fabric_rcv_items.qty >0 and
      so_aop_fabric_rcv_items.rate >0 
      group by 
      so_aops.buyer_id,
      so_aop_fabric_rcv_items.so_aop_ref_id,
      used.qty,returned.qty) m group by m.buyer_id', [$master->buyer_id,$master->company_id,$master->buyer_id,$master->company_id,$master->buyer_id,$master->company_id]))
		->first();

		

		$currentBill =collect(\DB::select('
		select so_aop_dlvs.id,so_aop_dlvs.currency_id,sum(so_aop_dlv_items.amount) as amount 
		from so_aop_dlvs
		join so_aop_dlv_items on so_aop_dlv_items.so_aop_dlv_id=so_aop_dlvs.id
		where 
		so_aop_dlvs.id=? and
		so_aop_dlvs.deleted_at is null and 
		so_aop_dlv_items.deleted_at is null 
		group by so_aop_dlvs.id,so_aop_dlvs.currency_id', [$id]))
		->first();
		$currentbillamount=0;

		if($currentBill->currency_id==1){
             $currentbillamount=$currentBill->amount*82;
		}else{
			$currentbillamount=$currentBill->amount;
		}

		//$receivable=$accounts->amount+$currentbillamount;
		$receivable=0;
		if($accounts){
		$receivable=$accounts->amount+$currentbillamount;
		}
		else{
		$receivable=0;
		}

		$bal=$fabrcvbal->amount-$receivable;
		if(($receivable > $fabrcvbal->amount) && \Auth::user()->level() < 5){
			return response()->json(array('success' => false,  'message' => "Stock Security amount ".$fabrcvbal->amount.",<br/> Receivable amount ".$receivable.",<br/> Balance Amount ".$bal." <br/> So approving not possible"), 200);

		}
		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');
		$soaopdlv = $this->soaopdlv->update($id,[
			'approved_by' => $user->id,  
			'approved_at' =>  $approved_at
		]);

		if($soaopdlv){
		return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
		}
    }

    public function reportDataApp() {
        return response()->json(
			$this->soaopdlv
			->leftJoin('buyers', function($join)  {
				$join->on('so_aop_dlvs.buyer_id', '=', 'buyers.id');
			})
			->leftJoin('companies', function($join)  {
				$join->on('so_aop_dlvs.company_id', '=', 'companies.id');
			})
			->when(request('company_id'), function ($q) {
				return $q->where('so_aop_dlvs.company_id', '=',request('company_id', 0));
			})
			->when(request('buyer_id'), function ($q) {
				return $q->where('so_aop_dlvs.buyer_id', '=',request('buyer_id', 0));
			})
			->when(request('date_from'), function ($q) {
				return $q->where('so_aop_dlvs.issue_date', '>=',request('date_from', 0));
			})
			->when(request('date_to'), function ($q) {
				return $q->where('so_aop_dlvs.issue_date', '<=',request('date_to', 0));
			})
        	->whereNotNull('so_aop_dlvs.approved_at')
        	->orderBy('so_aop_dlvs.id','desc')
			->get([
				'so_aop_dlvs.*',
				'buyers.name as buyer_id',
				'companies.name as company_id'
			])
			->map(function($rows){
				$rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));
			return $rows;
			})
        );
    }

    public function unapproved (Request $request)
    {
        $id=request('id',0);
        $master=$this->soaopdlv->find($id);
        $user = \Auth::user();
        $unapproved_at=date('Y-m-d h:i:s');
        $unapproved_count=$master->unapproved_count+1;
        $master->approved_by=NUll;
        $master->approved_at=NUll;
        $master->unapproved_by=$user->id;
        $master->unapproved_at=$unapproved_at;
        $master->unapproved_count=$unapproved_count;
        $master->timestamps=false;
        $soaopdlv=$master->save();
        

        if($soaopdlv){
            return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
        }
    }

	private function getDataReq()
	{
		$buyer_id=request('buyer_id',0);
		//$coa_id=request('coa_id',0);
		$idarray=explode(',',$buyer_id);
		//$coaidarray=explode(',',$coa_id);

		$rows = DB::table("acc_trans_prnts")
		->selectRaw(
			'acc_trans_prnts.company_id,
			companies.code as company_code,
			buyers.name as buyer_name,
			buyers.cr_limit_amt,
			buyers.address,
			acc_trans_sales.buyer_id,
			sum(acc_trans_sales.amount) as amount,
			debits.amount as d_amount,
			credits.amount as c_amount,
			acc_chart_ctrl_heads.id as acc_id,
			acc_chart_ctrl_heads.name as acc_name
		'
		)
		->join('acc_trans_sales',function($join){
		$join->on('acc_trans_prnts.id','=','acc_trans_sales.acc_trans_prnt_id');
		})
		->join('acc_chart_ctrl_heads',function($join){
		$join->on('acc_chart_ctrl_heads.id','=','acc_trans_sales.acc_chart_ctrl_head_id');
		})
		->join('companies',function($join){
		$join->on('companies.id','=','acc_trans_prnts.company_id');
		})
		->join('buyers',function($join){
		$join->on('buyers.id','=','acc_trans_sales.buyer_id');
		})
		->leftJoin(\DB::raw("(select 
		acc_trans_prnts.company_id,
		acc_trans_sales.buyer_id,
		sum(acc_trans_sales.amount) as amount 
		from acc_trans_prnts
		join acc_trans_sales on acc_trans_sales.acc_trans_prnt_id=acc_trans_prnts.id
		join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_sales.acc_chart_ctrl_head_id
		where 
		acc_trans_sales.deleted_at is null and 
		acc_trans_prnts.deleted_at is null and 
		--acc_trans_prnts.trans_type_id > 0 and
		acc_chart_ctrl_heads.control_name_id in (30,31) and 
		acc_trans_sales.amount>=0
		group by acc_trans_prnts.company_id,acc_trans_sales.buyer_id
		order by acc_trans_prnts.company_id,acc_trans_sales.buyer_id) debits"), [["debits.company_id", "=", "acc_trans_prnts.company_id"],["debits.buyer_id", "=", "acc_trans_sales.buyer_id"]])

		->leftJoin(\DB::raw("(select 
		acc_trans_prnts.company_id,
		acc_trans_sales.buyer_id,
		sum(acc_trans_sales.amount) as amount 
		from acc_trans_prnts
		join acc_trans_sales on acc_trans_sales.acc_trans_prnt_id=acc_trans_prnts.id
		join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_sales.acc_chart_ctrl_head_id
		where 
		acc_trans_sales.deleted_at is null and 
		acc_trans_prnts.deleted_at is null and 
		--acc_trans_prnts.trans_type_id > 0 and
		acc_chart_ctrl_heads.control_name_id in (30,31) and 
		acc_trans_sales.amount<0
		group by acc_trans_prnts.company_id,acc_trans_sales.buyer_id
		order by acc_trans_prnts.company_id,acc_trans_sales.buyer_id) credits"), [["credits.company_id", "=", "acc_trans_prnts.company_id"],["credits.buyer_id", "=", "acc_trans_sales.buyer_id"]])

        // ->when(request('coa_id'), function ($q) use ($coaidarray){
		// 	return $q->whereIn('acc_chart_ctrl_heads.id',[527,528,529,531,532,533,535,536,537]);
		// })
		->when(request('as_on_date'), function ($q){
		return $q->where('acc_trans_prnts.trans_date', '<=',request('as_on_date'));
		})
		
		->when(request('buyer_id'), function ($q) {
			return $q->where('acc_trans_sales.buyer_id', '=',request('buyer_id'));
		})
		->whereNull('acc_trans_sales.deleted_at')
		->whereNull('acc_trans_prnts.deleted_at')
		//->where([['acc_trans_prnts.trans_type_id','>',0]])
		->whereIn('acc_chart_ctrl_heads.control_name_id',[30,31])
		->whereIn('acc_chart_ctrl_heads.id',[527,528,529,531,532,533,535,536,537])
		->groupBy([
			'acc_trans_prnts.company_id',
			'companies.code',
			'acc_trans_sales.buyer_id',
			'buyers.name',
			'buyers.cr_limit_amt',
			'buyers.address',
			'debits.amount',
			'credits.amount',
			'acc_chart_ctrl_heads.id',
			'acc_chart_ctrl_heads.name'
		])
		->orderBy('acc_trans_prnts.company_id')
		->orderBy('acc_trans_sales.buyer_id')
		->get();
		return $rows; 
	}

	public function pdf() {
		$ason=date('d-M-Y', strtotime(request('as_on_date',0)));
		$buyerinfo=$this->buyer->where([['id','=',request('buyer_id',0)]])->get(['buyers.*'])->first();
		$user = \Auth::user();

		$userinfo=$this->user
		->join('employee_h_rs', function($join){
			$join->on('employee_h_rs.user_id','=','users.id');
		})
		->join('designations', function($join){
			$join->on('employee_h_rs.designation_id','=','designations.id');
		})
		->join('departments', function($join){
			$join->on('employee_h_rs.department_id','=','departments.id');
		})
		->where([['users.id','=',$user->id]])
		->get([
			'users.signature_file',
			'employee_h_rs.name as employee_name',
			'designations.name as designation',
			'departments.name as department'
		])->first();

		$userinfo->signature_file=$userinfo->signature_file?'images/signature/'.$userinfo->signature_file:null;
		
		$rows2=$this->getDataReq();
		$comArr2=[];
		$buyArr2=[];
		
		$comTot2=[];
		$buyTot2=[];
		$accArr2=[];
		$accTot2=[];
		$data2=[];
		foreach($rows2 as $row2){
			$comArr2[$row2->company_id]=$row2->company_code;
			$comTot2['amount'][$row2->company_id]=0;
			$buyArr2[$row2->buyer_id]=$row2->buyer_name;
			$accArr2[$row2->acc_id]=$row2->acc_name;
		}

		

		foreach ($buyArr2 as $buyerId2=>$buyerName2)
		{
			foreach ($accArr2 as $accId2=>$accName2)
			{
				$accTot2['amount'][$buyerId2][$accId2]=0;
				
				foreach ($comArr2 as $comId2=>$comName2)
				{
				$buyTot2['amount'][$buyerId2][$comId2]=0;
				$data2[$buyerId2][$accId2][$comId2]['amount']=0;
				}
			}
		}

		foreach($rows2 as $row2){
			$data2[$row2->buyer_id][$row2->acc_id][$row2->company_id]['amount']=$row2->amount;
			$buyTot2['amount'][$row2->buyer_id][$row2->company_id]+=$row2->amount;
			$accTot2['amount'][$row2->buyer_id][$row2->acc_id]+=$row2->amount;
			$comTot2['amount'][$row2->company_id]+=$row2->amount;
		}

		$buyerinfo->amount=$rows2->sum('amount');
		$inword=Numbertowords::ntow(number_format($buyerinfo->amount,2,'.',''),'Taka','Paisa only');
        $buyerinfo->inword=$inword;

		//$opening=$this->openingBalance();
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
	
		//$rows=$this->getData();
		//$datas=$this->getReceiveData();

		// $image_file ='images/logo/'.$data['company']->logo;
		// $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
		// $pdf->SetY(10);
		// $pdf->SetFont('helvetica', 'N', 10);
		// $pdf->Text(60, 12, $data['company']->address);
		 $pdf->SetFont('helvetica', 'N', 8);
	
	
		$view= \View::make('Defult.Report.Account.ReceivablePaymentRequestLetter',['comp2'=>$comArr2,'buy2'=>$buyArr2,'data2'=>$data2,'comTot2'=>$comTot2,'buyTot2'=>$buyTot2,'acc'=>$accArr2,'accTot2'=>$accTot2,'ason'=>$ason,'buyerinfo'=>$buyerinfo,'userinfo'=>$userinfo]);
		$html_content=$view->render();
		$pdf->SetY(25);
		$pdf->WriteHtml($html_content, true, false,true,false,'');
		$filename = storage_path() . '/ReceivablePaymentRequestLetter.pdf';
		$pdf->output($filename,'I');
		exit();
	}
}
