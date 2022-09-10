<?php

namespace App\Http\Controllers\Report\Knitting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;

class KnittingWorkLoadController extends Controller
{
	private $style;
	private $budget;
    private $company;
    private $buyer;
	public function __construct(
		StyleRepository $style,
		BudgetRepository $budget,
		CompanyRepository $company, 
		BuyerRepository $buyer)
    {
    	$this->style    = $style;
		$this->budget    = $budget;
        $this->company = $company;
        $this->buyer = $buyer;
		$this->middleware('auth');
		//$this->middleware('permission:view.liabilitycoveragereports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-',0);
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
        return Template::loadView('Report.Knitting.KnittingWorkLoad',['company'=>$company,'buyer'=>$buyer]);
    }
}