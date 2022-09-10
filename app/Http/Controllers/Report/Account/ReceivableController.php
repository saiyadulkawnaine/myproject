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
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Numbertowords;

class ReceivableController extends Controller
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
    private $user;

	public function __construct(
		CompanyRepository $company,
		AccYearRepository $accyear,
		BuyerRepository $buyer,
		SupplierRepository $supplier,
		AccChartLocationRepository $location,
		AccChartDivisionRepository $division,
		AccChartDepartmentRepository $department,
		AccChartSectionRepository $section,
		EmployeeRepository $employee,
		AccChartCtrlHeadRepository $accchartctrlhead,
		AccChartSubGroupRepository $accchartsubgroup,
		CurrencyRepository $currency,
		UserRepository $user
	)
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
        $this->user = $user;

		$this->middleware('auth');
		//$this->middleware('permission:view.glbuys',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
		return Template::loadView('Report.Account.Receivable',['company'=>$company,'accyear'=>$accyear]);
    }
    
    
	private function getData()
	{
		$buyer_id=request('buyer_id',0);
		$coa_id=request('coa_id',0);
		$idarray=explode(',',$buyer_id);
		$coaidarray=explode(',',$coa_id);

		$rows = DB::table("acc_trans_prnts")
		->selectRaw(
			'acc_trans_prnts.company_id,
			companies.code as company_code,
			buyers.name as buyer_name,
			acc_trans_sales.buyer_id,
			sum(acc_trans_sales.amount) as amount,
			debits.amount as d_amount,
			credits.amount as c_amount
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

        ->when(request('coa_id'), function ($q) use ($coaidarray){
			return $q->whereIn('acc_chart_ctrl_heads.id',$coaidarray);
		})
		->when(request('date_to'), function ($q){
		return $q->where('acc_trans_prnts.trans_date', '<=',request('date_to'));
		})
		
		->when(request('buyer_id'), function ($q) use ($idarray) {
			return $q->whereIn('acc_trans_sales.buyer_id',$idarray);
		})
		->whereNull('acc_trans_sales.deleted_at')
		->whereNull('acc_trans_prnts.deleted_at')
		//->where([['acc_trans_prnts.trans_type_id','>',0]])
		->whereIn('acc_chart_ctrl_heads.control_name_id',[30,31])
		->groupBy([
			'acc_trans_prnts.company_id',
			'companies.code',
			'acc_trans_sales.buyer_id',
			'buyers.name',
			'debits.amount',
			'credits.amount'
		])
		->orderBy('acc_trans_prnts.company_id')
		->orderBy('acc_trans_sales.buyer_id')
		->get();
		return $rows; 
	}


	private function getData2()
	{
		$buyer_id=request('buyer_id',0);
		$coa_id=request('coa_id',0);
		$idarray=explode(',',$buyer_id);
		$coaidarray=explode(',',$coa_id);

		$rows = DB::table("acc_trans_prnts")
		->selectRaw(
			'acc_trans_prnts.company_id,
			companies.code as company_code,
			buyers.name as buyer_name,
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

        ->when(request('coa_id'), function ($q) use ($coaidarray){
			return $q->whereIn('acc_chart_ctrl_heads.id',$coaidarray);
		})
		->when(request('date_to'), function ($q){
		return $q->where('acc_trans_prnts.trans_date', '<=',request('date_to'));
		})
		
		->when(request('buyer_id'), function ($q) use ($idarray) {
			return $q->whereIn('acc_trans_sales.buyer_id',$idarray);
		})
		->whereNull('acc_trans_sales.deleted_at')
		->whereNull('acc_trans_prnts.deleted_at')
		//->where([['acc_trans_prnts.trans_type_id','>',0]])
		->whereIn('acc_chart_ctrl_heads.control_name_id',[30,31])
		->groupBy([
			'acc_trans_prnts.company_id',
			'companies.code',
			'acc_trans_sales.buyer_id',
			'buyers.name',
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

	public function html() {
		$ason=date('d-M-Y', strtotime(request('date_to',0)));
		$rows=$this->getData();
		//$rows2=$this->getData2();

		$comArr=[];
		$buyArr=[];
		$comTot=[];
		$buyTot=[];
		$data=[];
		foreach($rows as $row){
			$comArr[$row->company_id]=$row->company_code;
			$comTot['amount'][$row->company_id]=0;
			$comTot['d_amount'][$row->company_id]=0;
			$comTot['c_amount'][$row->company_id]=0;
			$buyArr[$row->buyer_id]=$row->buyer_name;
			$buyTot['amount'][$row->buyer_id]=0;
			$buyTot['d_amount'][$row->buyer_id]=0;
			$buyTot['c_amount'][$row->buyer_id]=0;
		}

		foreach ($buyArr as $buyerId=>$buyerName)
		{
			foreach ($comArr as $comId=>$comName)
			{
				$data[$buyerId][$comId]['amount']=0;
				$data[$buyerId][$comId]['d_amount']=0;
				$data[$buyerId][$comId]['c_amount']=0;
			}
		}

		foreach($rows as $row){
			$data[$row->buyer_id][$row->company_id]['amount']=$row->amount;
			$buyTot['amount'][$row->buyer_id]+=$row->amount;
			$comTot['amount'][$row->company_id]+=$row->amount;

			$data[$row->buyer_id][$row->company_id]['d_amount']=$row->d_amount;
			$data[$row->buyer_id][$row->company_id]['c_amount']=$row->c_amount;
			$buyTot['d_amount'][$row->buyer_id]+=$row->d_amount;
			$buyTot['c_amount'][$row->buyer_id]+=$row->c_amount;
			$comTot['d_amount'][$row->company_id]+=$row->d_amount;
			$comTot['c_amount'][$row->company_id]+=$row->c_amount;
		}
		return Template::loadView('Report.Account.ReceivableData',['comp'=>$comArr,'buy'=>$buyArr,'data'=>$data,'comTot'=>$comTot,'buyTot'=>$buyTot, 'ason'=>$ason]);
    }

    public function htmll() {
		$ason=date('d-M-Y', strtotime(request('date_to',0)));
		$rows=$this->getData();
		//$rows2=$this->getData2();

		$comArr=[];
		$buyArr=[];
		$comTot=[];
		$buyTot=[];
		$data=[];
		foreach($rows as $row){
			$comArr[$row->company_id]=$row->company_code;
			$comTot['amount'][$row->company_id]=0;
			$comTot['d_amount'][$row->company_id]=0;
			$comTot['c_amount'][$row->company_id]=0;
			$buyArr[$row->buyer_id]=$row->buyer_name;
			$buyTot['amount'][$row->buyer_id]=0;
			$buyTot['d_amount'][$row->buyer_id]=0;
			$buyTot['c_amount'][$row->buyer_id]=0;
		}

		foreach ($buyArr as $buyerId=>$buyerName)
		{
			foreach ($comArr as $comId=>$comName)
			{
				$data[$buyerId][$comId]['amount']=0;
				$data[$buyerId][$comId]['d_amount']=0;
				$data[$buyerId][$comId]['c_amount']=0;
			}
		}

		foreach($rows as $row){
			$data[$row->buyer_id][$row->company_id]['amount']=$row->amount;
			$buyTot['amount'][$row->buyer_id]+=$row->amount;
			$comTot['amount'][$row->company_id]+=$row->amount;

			$data[$row->buyer_id][$row->company_id]['d_amount']=$row->d_amount;
			$data[$row->buyer_id][$row->company_id]['c_amount']=$row->c_amount;
			$buyTot['d_amount'][$row->buyer_id]+=$row->d_amount;
			$buyTot['c_amount'][$row->buyer_id]+=$row->c_amount;
			$comTot['d_amount'][$row->company_id]+=$row->d_amount;
			$comTot['c_amount'][$row->company_id]+=$row->c_amount;
		}


		
		return Template::loadView('Report.Account.ReceivableDatal',['comp'=>$comArr,'buy'=>$buyArr,'data'=>$data,'comTot'=>$comTot,'buyTot'=>$buyTot, 'ason'=>$ason]);
    }

    public function htmld() {
		$ason=date('d-M-Y', strtotime(request('date_to',0)));
		//$rows=$this->getData();
		$rows2=$this->getData2();
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
		return Template::loadView('Report.Account.ReceivableDatad',['comp2'=>$comArr2,'buy2'=>$buyArr2,'data2'=>$data2,'comTot2'=>$comTot2,'buyTot2'=>$buyTot2,'acc'=>$accArr2,'accTot2'=>$accTot2,'ason'=>$ason]);
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
		->whereIn('control_name_id',[30,31])
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
		->when(request('date_to'), function ($q){
		return $q->where('acc_trans_prnts.trans_date', '<=',request('date_to'));
		})
		
		->when(request('buyer_id'), function ($q) use ($idarray) {
			return $q->whereIn('acc_trans_sales.buyer_id',$idarray);
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
		$ason=date('d-M-Y', strtotime(request('date_to',0)));
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
