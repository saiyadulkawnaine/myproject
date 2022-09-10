<?php

namespace App\Http\Controllers\Subcontract\Inbound;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;

use App\Repositories\Contracts\Util\AopChargeRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\ConstructionRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\UomRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Inbound\SubInbMarketingRequest;
use App\Repositories\Contracts\Util\CurrencyRepository;

class SubInbMarketingController extends Controller {

    private $subinbmarketing;
    private $subinbservice;
    private $company;
    private $buyer;
    private $buyerbranch;
    private $team;
    private $teammember;

    private $colorrange;
    private $embelishmenttype;
    private $gmtspart;
    private $construction;
    private $yarncount;
    private $user;
    private $uom;
    private $currency;

    public function __construct(
        SubInbMarketingRepository $subinbmarketing,
        BuyerRepository $buyer,
        BuyerBranchRepository $buyerbranch,
        CompanyRepository $company,
        TeamRepository $team,
        TeammemberRepository $teammember,
        ColorrangeRepository $colorrange, 
        AopChargeRepository $aopcharge, 
        EmbelishmentTypeRepository $embelishmenttype, 
        GmtspartRepository $gmtspart, 
        ConstructionRepository $construction, 
        YarncountRepository $yarncount, 
        SubInbServiceRepository $subinbservice,
        UserRepository $user, 
        UomRepository $uom, 
        CurrencyRepository $currency
    ) {
        $this->subinbmarketing = $subinbmarketing;
        $this->subinbservice = $subinbservice;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->buyerbranch = $buyerbranch;
        $this->team = $team;
        $this->teammember = $teammember;
        $this->user = $user;

        $this->colorrange = $colorrange;
        $this->aopcharge = $aopcharge;
        $this->embelishmenttype = $embelishmenttype;
        $this->construction = $construction;
        $this->gmtspart = $gmtspart;
        $this->yarncount = $yarncount;
        $this->uom = $uom;
        $this->currency = $currency;

        $this->middleware('auth');
        $this->middleware('permission:view.subinbmarketings',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.subinbmarketings', ['only' => ['store']]);
        $this->middleware('permission:edit.subinbmarketings',   ['only' => ['update']]);
        $this->middleware('permission:delete.subinbmarketings', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
         
        $subinbmarketings=array();
        $rows=$this->subinbmarketing
        ->leftJoin('buyers', function($join)  {
            $join->on('sub_inb_marketings.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('buyer_branches', function($join)  {
            $join->on('sub_inb_marketings.buyer_branch_id', '=', 'buyer_branches.id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('sub_inb_marketings.company_id', '=', 'companies.id');
        })
        ->leftJoin('currencies', function($join)  {
            $join->on('sub_inb_marketings.currency_id', '=', 'currencies.id');
        })
        ->leftJoin('teams', function($join)  {
            $join->on('sub_inb_marketings.team_id', '=', 'teams.id');
        })
        ->leftJoin('teammembers', function($join)  {
            $join->on('teammembers.id', '=', 'sub_inb_marketings.teammember_id');
        })
        ->leftJoin('users', function($join)  {
            $join->on('users.id', '=', 'teammembers.user_id');
        })
        ->orderBy('sub_inb_marketings.id','desc')
        ->get(['sub_inb_marketings.*',
            'buyers.name as buyer_id',
            'companies.name as company_id',
            'teams.name as team_name',
            'users.name as team_member_name',
            'buyer_branches.contact_person',
            'buyer_branches.email',
            'buyer_branches.designation',
            'buyer_branches.address',
            'currencies.code as currency_code'
		]);
        foreach($rows as $row){
            $subinbmarketing['id']=$row->id;
            $subinbmarketing['company_id']=$row->company_id;
            $subinbmarketing['production_area_id']=$productionarea[$row->production_area_id];
            /* $subinbmarketing['team']=$row->team_id;
            $subinbmarketing['teammember']=$row->teammember_id; */
            $subinbmarketing['currency_code']=$row->currency_code;
            $subinbmarketing['team_name']=	$row->team_name;
		    $subinbmarketing['team_id']=	$row->team_id;
		    $subinbmarketing['teammember']=	$row->team_member_name;
            $subinbmarketing['buyer_id']=$row->buyer_id;
            $subinbmarketing['mkt_date']=$row->mkt_date?date('Y-m-d',strtotime($row->mkt_date)):'--';
            $subinbmarketing['refered_by']=$row->refered_by;
            $subinbmarketing['contact']=$row->contact_person;
            $subinbmarketing['contact_no']=$row->email." ".$row->address;

            array_push($subinbmarketings,$subinbmarketing);
        }
        echo json_encode($subinbmarketings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
        /* $teammember=array_prepend(array_pluck($this->teammember->get(),'name','id'),'-Select-',0); */
        $teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function($join) use ($request) {
            $join->on('teammembers.user_id', '=', 'users.id');
        })
        ->get([
            'teammembers.id',
            'users.name',
        ]),'name','id'),'-Select-',0);
        
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
		$currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        //$productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
         $productionarea=array_prepend(array_only(config('bprs.productionarea'),[10,20,25]),'-Select-','');

        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $fabricshape=array_prepend(config('bprs.fabricshape'),'-Select-','');
	    $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $construction=array_prepend(array_pluck($this->construction->get(),'name','id'),'-Select-','');
        $yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$meetingtype=array_prepend(config('bprs.meetingtype'),'-Select-','');


        return Template::LoadView('Subcontract.Inbound.SubInbMarketing',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'productionarea'=>$productionarea,'colorrange'=>$colorrange,'fabricshape'=>$fabricshape,'dyetype'=>$dyetype,'teammember'=>$teammember,'embelishmenttype'=>$embelishmenttype,'gmtspart'=>$gmtspart,'construction'=>$construction,'fabriclooks'=>$fabriclooks,'yarncount'=>$yarncount,'uom'=>$uom,'currency'=>$currency,'meetingtype'=>$meetingtype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubInbMarketingRequest $request) {
		$subinbmarketing=$this->subinbmarketing->create($request->except(['id','name','email','address','country','designation']));
        if($subinbmarketing){
            return response()->json(array('success' => true,'id' =>  $subinbmarketing->id,'message' => 'Save Successfully'),200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $subinbmarketing = $this->subinbmarketing
        ->leftJoin('buyer_branches', function($join)  {
            $join->on('sub_inb_marketings.buyer_branch_id', '=', 'buyer_branches.id');
        })
        ->leftJoin('countries', function($join)  {
            $join->on('buyer_branches.country_id', '=', 'countries.id');
        })
        ->where([['sub_inb_marketings.id','=',$id]])
        ->get([
            'sub_inb_marketings.*',
            'buyer_branches.contact_person as name',
            'buyer_branches.email',
            'buyer_branches.designation',
            'buyer_branches.address',
            'countries.name as country',
        ])
        ->first();
        $subinbmarketing['mkt_date']=date('Y-m-d',strtotime($subinbmarketing->mkt_date));
        $row ['fromData'] = $subinbmarketing;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubInbMarketingRequest $request, $id) {
        $subinbmarketing=$this->subinbmarketing->update($id,$request->except(['id','name','email','address','country','designation']));
        if($subinbmarketing){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->subinbmarketing->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getBuyerBranch(){
        $buyerbranch=$this->buyerbranch
        ->leftJoin('countries', function($join)  {
            $join->on('buyer_branches.country_id', '=', 'countries.id');
        })
        ->where([['buyer_id','=',request('buyer_id', 0)]])
        ->get([
            'buyer_branches.*',
            'countries.name as country',
        ]);
        echo json_encode($buyerbranch);
    }

}
