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

class GroupReceivableController extends Controller
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
		CurrencyRepository $currency
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

		$this->middleware('auth');
		//$this->middleware('permission:view.glbuys',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
		return Template::loadView('Report.Account.GroupReceivable',['company'=>$company,'accyear'=>$accyear]);
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
			acc_trans_chlds.party_id,
			sum(acc_trans_chlds.amount) as amount,
			
			acc_chart_ctrl_heads.id as acc_id,
			acc_chart_ctrl_heads.name as acc_name
		'
		)
		->join('acc_trans_chlds',function($join){
		$join->on('acc_trans_prnts.id','=','acc_trans_chlds.acc_trans_prnt_id');
		})
		->join('acc_chart_ctrl_heads',function($join){
		$join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
		})
		->join('companies',function($join){
		$join->on('companies.id','=','acc_trans_prnts.company_id');
		})
		->leftJoin('buyers',function($join){
		$join->on('buyers.id','=','acc_trans_chlds.party_id');
		})
		->when(request('date_to'), function ($q){
		return $q->where('acc_trans_prnts.trans_date', '<=',request('date_to'));
		})
		->whereNull('acc_trans_chlds.deleted_at')
		->whereNull('acc_trans_prnts.deleted_at')
		->whereIn('acc_chart_ctrl_heads.control_name_id',[30,31,32])
		->whereNotIn('acc_chart_ctrl_heads.id',[534,530,538,5203])
		->groupBy([
			'acc_trans_prnts.company_id',
			'companies.code',
			'acc_trans_chlds.party_id',
			'buyers.name',
			
			'acc_chart_ctrl_heads.id',
			'acc_chart_ctrl_heads.name',
			'acc_chart_ctrl_heads.code'
		])
		->orderBy('acc_chart_ctrl_heads.code')
		->get();
		return $rows; 
	}

	public function html() {
		$ason=request('date_to',0);
		$rows=$this->getData2();
		//$rows2=$this->getData2();

		$comArr=[];
		$acchArr=[];
		$data=[];
		$acchTot=[];
		$comTot=[];
		
		foreach($rows as $row){
			$comArr[$row->company_id]=$row->company_code;
			$acchArr[$row->acc_id]=$row->acc_name;
			//$data[$row->acc_id][$row->company_id]['amount']=0;
		}
		foreach ($acchArr as $accid=>$accname)
		{
			foreach ($comArr as $comId=>$comName)
			{
				$data[$accid][$comId]['amount']=0;
				$acchTot['amount'][$accid]=0;
			    $comTot['amount'][$comId]=0;
			}
		}
		foreach($rows as $row){
			
			$data[$row->acc_id][$row->company_id]['amount']+=$row->amount;
			$acchTot['amount'][$row->acc_id]+=$row->amount;
			$comTot['amount'][$row->company_id]+=$row->amount;
		}

		

		
		return Template::loadView('Report.Account.GroupReceivableData',[
			'ason'=>$ason,
			'comp'=>$comArr,
			'acchArr'=>$acchArr,
			'data'=>$data,
			'acchTot'=>$acchTot,
			'comTot'=>$comTot,
		]);
    }

    public function getBuyerDetails(){
    	$date_to=request('date_to',0);
		$acc_id=request('acc_id',0);
		$company_id=request('company_id',0);
		$rows = DB::table("acc_trans_prnts")
		->selectRaw(
			'acc_trans_prnts.company_id,
			companies.code as company_code,
			buyers.name as buyer_name,
			acc_trans_chlds.party_id,
			sum(acc_trans_chlds.amount) as amount,
			acc_chart_ctrl_heads.id as acc_id,
			acc_chart_ctrl_heads.name as acc_name
		'
		)
		->join('acc_trans_chlds',function($join){
		$join->on('acc_trans_prnts.id','=','acc_trans_chlds.acc_trans_prnt_id');
		})
		->join('acc_chart_ctrl_heads',function($join){
		$join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
		})
		->join('companies',function($join){
		$join->on('companies.id','=','acc_trans_prnts.company_id');
		})
		->leftJoin('buyers',function($join){
		$join->on('buyers.id','=','acc_trans_chlds.party_id');
		})
		->when(request('date_to'), function ($q){
		return $q->where('acc_trans_prnts.trans_date', '<=',request('date_to'));
		})
		->when(request('company_id'), function ($q) {
		return $q->where('acc_trans_prnts.company_id', '=',request('company_id'));
		})
		//->where([['acc_trans_prnts.company_id','=',$company_id]])
		->where([['acc_trans_chlds.acc_chart_ctrl_head_id','=',$acc_id]])
		->whereNull('acc_trans_chlds.deleted_at')
		->whereNull('acc_trans_prnts.deleted_at')
		->whereIn('acc_chart_ctrl_heads.control_name_id',[30,31,32])
		->whereNotIn('acc_chart_ctrl_heads.id',[534,530,538,5203])
		->groupBy([
			'acc_trans_prnts.company_id',
			'companies.code',
			'acc_trans_chlds.party_id',
			'buyers.name',
			'acc_chart_ctrl_heads.id',
			'acc_chart_ctrl_heads.name',
			'acc_chart_ctrl_heads.code'
		])
		->orderBy('acc_chart_ctrl_heads.code')
		->get()
		->map(function($rows){
			$rows->amount=number_format($rows->amount,2);
			return $rows;
		});
		echo json_encode($rows);
    }

    
}
