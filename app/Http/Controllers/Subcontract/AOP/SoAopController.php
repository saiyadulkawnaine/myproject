<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopPoItemRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Purchase\PoAopServiceRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopPoRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRefRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopRequest;

class SoAopController extends Controller {

    private $soaop;
    private $soaoppoitem;
    private $subinbmarketing;
    private $company;
    private $buyer;
    private $uom;
    private $gmtspart;
    private $poaopservice;
    private $poaoppo;
    private $poaopref;
    private $currency;
    private $colorrange;
    private $color;
    private $embelishmenttype;
    private $teammember;

    public function __construct(
        SoAopRepository $soaop,
        SoAopPoItemRepository $soaoppoitem,
        BuyerRepository $buyer,
        CompanyRepository $company, 
        UomRepository $uom, 
        SubInbMarketingRepository $subinbmarketing, 
        GmtspartRepository $gmtspart,
        PoAopServiceRepository $poaopservice,
        SoAopPoRepository $poaoppo,
        SoAopRefRepository $poaopref,
        CurrencyRepository $currency,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        EmbelishmentTypeRepository $embelishmenttype,
        TeammemberRepository $teammember
        ) {
        $this->soaop = $soaop;
        $this->soaoppoitem = $soaoppoitem;
        $this->subinbmarketing = $subinbmarketing;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->gmtspart = $gmtspart;
        $this->poaopservice = $poaopservice;
        $this->poaoppo = $poaoppo;
        $this->poaopref = $poaopref;
        $this->currency = $currency;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->embelishmenttype = $embelishmenttype;
        $this->teammember = $teammember;
        $this->middleware('auth');
         
        
        $this->middleware('permission:view.soaops',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soaops', ['only' => ['store']]);
        $this->middleware('permission:edit.soaops',   ['only' => ['update']]);
        $this->middleware('permission:delete.soaops', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       return response()->json(
          $this->soaop
          ->leftJoin('buyers', function($join)  {
            $join->on('so_aops.buyer_id', '=', 'buyers.id');
          })
          ->leftJoin('companies', function($join)  {
            $join->on('so_aops.company_id', '=', 'companies.id');
          })
          ->leftJoin('currencies', function($join)  {
            $join->on('currencies.id', '=', 'so_aops.currency_id');
          })
          ->leftJoin('sub_inb_marketings', function($join)  {
            $join->on('so_aops.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
          })
          ->leftJoin('teammembers', function($join)  {
            $join->on('teammembers.id', '=', 'so_aops.teammember_id');
          })
          ->leftJoin('users', function($join)  {
            $join->on('teammembers.user_id', '=', 'users.id');
          })
          ->orderBy('so_aops.id','desc')
          ->take(500)
          ->get([
            'so_aops.*',
            //'sub_inb_marketings.id as sub_inb_marketing_id',
            'buyers.name as buyer_name',
            'companies.name as company_name',
            'currencies.name as currency_name',
            'users.name as teammember_name'
          ])
          ->map(function($rows) {
            $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
            return $rows;
          })
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $fabriclooks=array_prepend(array_only(config('bprs.fabriclooks'), [25]),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $billfor=array_prepend(config('bprs.billfor'),'-Select-','');
        $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
        $team=$this->teammember
        ->leftJoin('teams', function($join)  {
        $join->on('teammembers.team_id', '=', 'teams.id');
        })
        ->leftJoin('users', function($join)  {
        $join->on('teammembers.user_id', '=', 'users.id');
        })
        ->get([
        'teammembers.id',
        'users.name',
        'teams.name as team_name',
        ])
        ->map(function($team){
          $team->name=$team->name." (".$team->team_name." )";
          return $team;
        });

        $teammember = array_prepend(array_pluck($team,'name','id'),'-Select-',0);
        
        return Template::LoadView('Subcontract.AOP.SoAop',['company'=>$company,'buyer'=>$buyer,'uom'=>$uom,'fabriclooks'=>$fabriclooks,'fabricshape'=>$fabricshape,'gmtspart'=>$gmtspart,'currency'=>$currency,'colorrange'=>$colorrange,'color'=>$color,'dyetype'=>$dyetype,'aoptype'=>$aoptype,'teammember'=>$teammember,'billfor'=>$billfor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopRequest $request) {
        $soaop=$this->soaop->create($request->except(['id','po_aop_service_id']));
        if($request->po_aop_service_id)
        {
          $poaopservice=$this->poaopservice->find($request->po_aop_service_id);
          $this->soaop->update($soaop->id,['currency_id'=>$poaopservice->currency_id,'exch_rate'=>$poaopservice->exch_rate]);

          $this->poaoppo->create([
              'so_aop_id'=>$soaop->id,'po_aop_service_id'=>$request->po_aop_service_id
              ]);
          $poaopserviceitems=$this->poaopservice
          ->join('po_aop_service_items',function($join){
                $join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id')
            ->whereNull('po_aop_service_items.deleted_at');
          })
          ->join('po_aop_service_item_qties',function($join){
                $join->on('po_aop_service_item_qties.po_aop_service_item_id','=','po_aop_service_items.id');
          })
          ->where([['po_aop_services.id','=',$request->po_aop_service_id]])
          ->get(['po_aop_service_item_qties.id as po_aop_service_item_qty_id']);

          foreach($poaopserviceitems as $poaopserviceitem){
            $poaopref=$this->poaopref->create(['so_aop_id'=>$soaop->id]);
            
            $soaoppoitem=$this->soaoppoitem->create(['so_aop_ref_id'=>$poaopref->id,'po_aop_service_item_qty_id'=>$poaopserviceitem->po_aop_service_item_qty_id]);
            }
        }
        if($soaop){
          return response()->json(array('success' => true,'id' =>  $soaop->id,'message' => 'Save Successfully'),200);
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
        $soaop = $this->soaop
        ->leftJoin('so_aop_pos', function($join) {
          $join->on('so_aop_pos.so_aop_id', '=', 'so_aops.id');
        })
        ->where([['so_aops.id','=',$id]])
        ->get([
            'so_aops.*',
            'so_aop_pos.po_aop_service_id',
		])
        ->first();

        $row ['fromData'] = $soaop;
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
    public function update(SoAopRequest $request, $id) {
        if($request->po_aop_service_id)
        {
          $soaop=$this->soaop->update($id,$request->except(['id','po_aop_service_id','currency_id','exch_rate']));
        }
        else{
          $soaop=$this->soaop->update($id,$request->except(['id','po_aop_service_id']));
        }
        if($soaop){
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
        if($this->soaop->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getMktRef(){
        return response()->json(
            $this->subinbmarketing
            ->leftJoin('buyers', function($join)  {
                $join->on('sub_inb_marketings.buyer_id', '=', 'buyers.id');
            })
            ->leftJoin('companies', function($join)  {
                $join->on('sub_inb_marketings.company_id', '=', 'companies.id');
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
            ->when(request('company_id'), function ($q) {
                return $q->where('sub_inb_marketings.company_id', '=', request('company_id', 0));
            })
            ->when(request('production_area_id'), function ($q) {
                return $q->where('sub_inb_marketings.production_area_id', '=' , request('production_area_id',0));
            })
            ->when(request('buyer_id'), function($q) {
                return $q->where('sub_inb_marketings.buyer_id', '=' , request('buyer_id',0));
            })
            ->when(request('mkt_date'), function($q) {
                return $q->where('sub_inb_marketings.mkt_date', '=' , request('mkt_date',0));
            })
            ->orderBy('sub_inb_marketings.id','desc')
            ->get(['sub_inb_marketings.*',
                'buyers.name as buyer_id',
                'companies.name as company_id',
                'teams.name as team_name',
                'users.name as team_member_name'
    		])
        );
    }

    public function getPo()
    {
        $poaopservice=$this->poaopservice
        ->leftJoin('companies', function($join)  {
            $join->on('companies.id', '=', 'po_aop_services.company_id');
        })
        ->leftJoin('currencies', function($join)  {
            $join->on('currencies.id', '=', 'po_aop_services.currency_id');
        })
        ->leftJoin('so_aop_pos', function($join) {
          $join->on('so_aop_pos.po_aop_service_id', '=', 'po_aop_services.id');
        })
        ->when(request('po_no'), function ($q) {
            return $q->where('po_aop_services.po_no', '=', request('po_no', 0));
        })
        ->whereNotNull('po_aop_services.approved_at')
        ->get([
            'po_aop_services.*',
            'companies.name as company_name',
            'currencies.name as currency_code',
            'so_aop_pos.po_aop_service_id',
        ]);
        
        $data=$poaopservice->filter(function ($poaopservice) {
          if(!$poaopservice->po_aop_service_id){
              return $poaopservice;
          }
        })->values();

        return response()->json($data);

    }

    public function getTeammember (){
        $buyer_id=request('buyer_id',0);
        $results = collect(
        \DB::select("
        select 
        teammembers.id,
        users.name
        from buyers
        left join teams on teams.id=buyers.team_id
        left join teammembers on teammembers.team_id=teams.id
        left join users on users.id=teammembers.user_id
        where 
        buyers.id = ?
        ", [$buyer_id])
        );
        echo json_encode($results);
    }

    public function getSoAopList(){
        $rows=$this->soaop
        ->leftJoin('buyers', function($join)  {
            $join->on('so_aops.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('so_aops.company_id', '=', 'companies.id');
        })
        ->leftJoin('currencies', function($join)  {
            $join->on('currencies.id', '=', 'so_aops.currency_id');
        })
        ->leftJoin('sub_inb_marketings', function($join)  {
            $join->on('so_aops.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
        })
        ->leftJoin('teammembers', function($join)  {
            $join->on('teammembers.id', '=', 'so_aops.teammember_id');
        })
        ->leftJoin('users', function($join)  {
            $join->on('teammembers.user_id', '=', 'users.id');
        })
        ->when(request('customer_id'), function ($q) {
            return $q->where('so_aops.buyer_id', '=', request('customer_id', 0));
        })
        ->when(request('from_date'),function($q){
            return $q->where('so_aops.receive_date','>=',request('from_date',0));
        })
        ->when(request('to_date'),function($q){
            return $q->where('so_aops.receive_date','<=',request('to_date',0));
        })
        ->orderBy('so_aops.id','desc')
        ->get([
            'so_aops.*',
            //'sub_inb_marketings.id as sub_inb_marketing_id',
            'buyers.name as buyer_name',
            'companies.name as company_name',
            'currencies.name as currency_name',
            'users.name as teammember_name'
        ])
        ->map(function($rows) {
            $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
            return $rows;
        });

        echo json_encode($rows);
    }
}