<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;

use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Account\AccBepRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;

class TodayBepController extends Controller
{
	private $no_of_days;
	private $exch_rate;
	private $style;
	private $company;
	private $buyer;
	private $itemaccount;
	private $budget;
	private $embelishmenttype;
	private $salesordergmtcolorsize;
	private $accbep;
	private $subsection;
	public function __construct(
		StyleRepository $style,
		CompanyRepository $company,
		BuyerRepository $buyer,
		ItemAccountRepository $itemaccount,
		BudgetRepository $budget,
		EmbelishmentTypeRepository $embelishmenttype,
		SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
		AccBepRepository $accbep,
		SubsectionRepository $subsection
	)
    {
    	$this->no_of_days                = 26;
		$this->exch_rate                 = 82;
		$this->style                     =$style;
		$this->company                   = $company;
		$this->buyer                     = $buyer;
		$this->itemaccount               = $itemaccount;
		$this->budget                    = $budget;
		$this->embelishmenttype          = $embelishmenttype;
		$this->salesordergmtcolorsize    = $salesordergmtcolorsize;
		$this->accbep    = $accbep;
		$this->subsection    = $subsection;
		$this->middleware('auth');
		//$this->middleware('permission:view.todayshipmentreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
    	$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');

        return Template::loadView('Report.TodayBep',['company'=>$company]);
    }
    

    

    public function formatOne() {
		$bep_date_from=request('bep_date_from',0);
		$bep_date_to=request('bep_date_to',0);
    	$company_id=request('company_id',0);
    	$accbepmst=$this->accbep
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->when(request('bep_date_from'), function ($q) use ($bep_date_from){
		return $q->where('acc_beps.start_date', '>=',$bep_date_from);
		})
		->when(request('bep_date_to'), function ($q) use ($bep_date_to){
		return $q->where('acc_beps.end_date', '<=',$bep_date_to);
		})
		->get()
		->first();
		if($accbepmst){
			$this->exch_rate=$accbepmst->exch_rate;
		}
		if(!$this->exch_rate){
			$this->exch_rate=82;
		}
		if(!$accbepmst->unit_price){
			$accbepmst->unit_price=1;
		}


		$earnings=$this->accbep
		->join('acc_bep_entries', function($join)  {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join)  {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->join('acc_chart_sub_groups', function($join)  {
			$join->on('acc_chart_sub_groups.id', '=', 'acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->where([['acc_chart_sub_groups.acc_chart_group_id','=',16]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->when(request('bep_date_from'), function ($q) use ($bep_date_from){
		return $q->where('acc_beps.start_date', '>=',$bep_date_from);
		})
		->when(request('bep_date_to'), function ($q) use ($bep_date_to){
		return $q->where('acc_beps.end_date', '<=',$bep_date_to);
		})
		//->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($earnings){
			$earnings->per_day=$earnings->amount/$this->no_of_days;
			return $earnings;
		});

		$fexpense=$this->accbep
		->join('acc_bep_entries', function($join)  {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',2]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->when(request('bep_date_from'), function ($q) use ($bep_date_from){
		return $q->where('acc_beps.start_date', '>=',$bep_date_from);
		})
		->when(request('bep_date_to'), function ($q) use ($bep_date_to){
		return $q->where('acc_beps.end_date', '<=',$bep_date_to);
		})
		->orderBy('acc_bep_entries.id')
		//->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($fexpense){
			$fexpense->per_day=$fexpense->amount/$this->no_of_days;
			return $fexpense;
		});

		$vexpense=$this->accbep
		->join('acc_bep_entries', function($join)  {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',1]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->when(request('bep_date_from'), function ($q) use ($bep_date_from){
		return $q->where('acc_beps.start_date', '>=',$bep_date_from);
		})
		->when(request('bep_date_to'), function ($q) use ($bep_date_to){
		return $q->where('acc_beps.end_date', '<=',$bep_date_to);
		})
		->orderBy('acc_bep_entries.id')
		//->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($vexpense){
			$vexpense->per_day=$vexpense->amount/$this->no_of_days;
			$vexpense->amount_usd=$vexpense->amount/$this->exch_rate;
			$vexpense->per_day_usd=$vexpense->amount_usd/$this->no_of_days;
			return $vexpense;

		});

		$subsection=$this->subsection
		->selectRaw('
			company_subsections.company_id,
			sum(subsections.qty) as qty,
			count(subsections.id) as id
			')
		->join('company_subsections', function($join) {
			$join->on('company_subsections.subsection_id', '=', 'subsections.id');
		})
		->where([['subsections.is_treat_sewing_line','=',1]])
		->where([['subsections.projected_line_id','=',0]])
		->where([['subsections.status_id','=',1]])
		->where([['company_subsections.company_id','=',request('company_id',0)]])
		->whereNull('company_subsections.deleted_at')
		->groupby([
		'company_subsections.company_id',
		])
		->get()
		->first();

		return Template::loadView('Report.TodayBepDetails', ['fexpense'=>$fexpense,'vexpense'=>$vexpense,'earnings'=>$earnings,'exch_rate'=>$this->exch_rate,'accbepmst'=>$accbepmst,'subsection'=>$subsection]);
    }
}