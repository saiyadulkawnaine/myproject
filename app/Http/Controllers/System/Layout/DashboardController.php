<?php

namespace App\Http\Controllers\System\Layout;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Template;

use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
class DashboardController extends Controller
{
    private $company;
    private $buyer;
    private $team;
    private $teammember;
    private $location;
    private $supplier;

	  public function __construct(
        CompanyRepository $company,
        BuyerRepository $buyer,
        TeamRepository $team,
        TeammemberRepository $teammember,
        LocationRepository $location,
        SupplierRepository $supplier

    ){
		//$this->middleware('auth');
        $this->company  = $company;
        $this->buyer    = $buyer;
        $this->team = $team;
        $this->teammember = $teammember;
        $this->location = $location;
        $this->supplier = $supplier;
	  }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
	
    public function index()
    {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-','');
        $team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
        $orderstage=array_prepend(config('bprs.orderstage'),'-Select-','');
        $teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function($join)  {
        $join->on('teammembers.user_id', '=', 'users.id');
        })
        ->get([
            'teammembers.id',
            'users.name',
        ]),'name','id'),'-Select-',0);
    $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
      $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
      $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
      $ordersource=array_prepend(config('bprs.ordersource'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');

		return Template::loadView("System.Layout.dashboard",['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember,'orderstage'=>$orderstage,'location'=> $location,'productionsource'=> $productionsource,'shiftname'=> $shiftname,'ordersource'=>$ordersource,'supplier'=>$supplier]);
    }
}
