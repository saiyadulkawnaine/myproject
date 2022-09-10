<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
class ApprovalController extends Controller
{
	private $mktcost;
	private $company;
	private $buyer;
	private $team;
	private $teammember;
	
	public function __construct(
		MktCostRepository $mktcost,
		CompanyRepository $company,
		BuyerRepository $buyer,
		TeamRepository $team,
		TeammemberRepository $teammember)
    {
		$this->mktcost=$mktcost;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->team = $team;
        $this->teammember = $teammember;
		
		$this->middleware('auth');
    }
    public function create() {
    	$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
		$teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function($join)
		{
			$join->on('teammembers.user_id', '=', 'users.id');
		})
		->get([
			'teammembers.id',
			'users.name',
		]),'name','id'),'-Select-',0);
        return Template::loadView('Approval.Approval',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember]);
    }
}
