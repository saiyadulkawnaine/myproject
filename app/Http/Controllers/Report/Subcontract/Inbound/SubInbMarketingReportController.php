<?php

namespace App\Http\Controllers\Report\Subcontract\Inbound;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;

use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
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
use App\Repositories\Contracts\Util\CurrencyRepository;
class SubInbMarketingReportController extends Controller
{
	private $subinbmarketing;
	private $subinbservice;
    private $company;
    private $buyer;
    private $team;
    private $teammember;
    private $user;
    private $colorrange;
    private $embelishmenttype;
    private $gmtspart;
    private $construction;
    private $yarncount;
    private $uom;
    private $currency;

	public function __construct(SubInbMarketingRepository $subinbmarketing,SubInbServiceRepository $subinbservice,BuyerRepository $buyer,CompanyRepository $company,TeamRepository $team,TeammemberRepository $teammember,UserRepository $user, ColorrangeRepository $colorrange, AopChargeRepository $aopcharge, EmbelishmentTypeRepository $embelishmenttype, GmtspartRepository $gmtspart, ConstructionRepository $construction, YarncountRepository $yarncount,UomRepository $uom,CurrencyRepository $currency)
    {
		$this->subinbmarketing = $subinbmarketing;
        $this->subinbservice = $subinbservice;
        $this->company = $company;
        $this->buyer = $buyer;
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
        $this->middleware('permission:view.subinbmarketingreports',   ['only' => ['create', 'index','show']]);
    }
    public function index(Request $request) {
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
        $teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function($join) use ($request) {
            $join->on('teammembers.user_id', '=', 'users.id');
        })
        ->get([
            'teammembers.id',
            'users.name',
        ]),'name','id'),'-Select-',0);
        
		$company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
		$uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
		$currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
        return Template::loadView('Report.Subcontract.Inbound.SubInbMarketingReport',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember,'productionarea'=>$productionarea,'uom'=>$uom,'currency'=>$currency]);
    }
	// public function reportData() {
	// 	//$user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
    //     $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');

    //     $images=$this->subinbmarketing
    //     ->selectRaw('
    //     sub_inb_marketings.id,
    //     sub_inb_images.file_src
    //         ')
    //     ->leftJoin('buyers', function($join)  {
    //         $join->on('sub_inb_marketings.buyer_id', '=', 'buyers.id');
    //     })
    //     ->leftJoin('companies', function($join)  {
    //         $join->on('sub_inb_marketings.company_id', '=', 'companies.id');
    //     })
    //     ->join('teams', function($join)  {
    //         $join->on('sub_inb_marketings.team_id', '=', 'teams.id');
    //     })
    //     ->leftJoin('sub_inb_images', function($join)  {
    //         $join->on('sub_inb_images.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
    //     })
    //     ->when(request('company_id'), function ($q) {
	// 		return $q->where('sub_inb_marketings.company_id', '=', request('company_id', 0));
	// 	})
	// 	->when(request('team_id'), function ($q) {
	// 		return $q->where('sub_inb_marketings.team_id', '=', request('team_id', 0));
	// 	})
	// 	->when(request('teammember_id'), function ($q) {
	// 		return $q->where('sub_inb_marketings.teammember_id', '=', request('teammember_id', 0));
	// 	})
	// 	->when(request('production_area_id'), function ($q) {
	// 		return $q->where('sub_inb_marketings.production_area_id', '=', request('production_area_id', 0));
    //     })
    //     ->when(request('date_from'), function ($q) {
    //         return $q->where('sub_inb_marketings.mkt_date', '>=',request('date_from', 0));
    //     })
    //     ->when(request('date_to'), function ($q) {
    //         return $q->where('sub_inb_marketings.mkt_date', '<=',request('date_to', 0));
    //     })
    //     ->when(request('id'),function($q){
    //         return $q->where('sub_inb_marketings.id','=',request('id',0));
    //     })
    //     ->get();
    //     $image_arr=array();
    //     foreach($images as $image)
    //     {
    //          $image_arr[$image->id]=$image->file_src;
    //     }


    //     $rows=$this->subinbmarketing
    //     ->selectRaw('
    //         sub_inb_marketings.id,
    //         sub_inb_marketings.company_id,
    //         sub_inb_marketings.currency_id,
    //         sub_inb_marketings.production_area_id,
    //         sub_inb_marketings.team_id,
    //         sub_inb_marketings.teammember_id,
    //         sub_inb_marketings.buyer_id,
    //         sub_inb_marketings.mkt_date,
    //         sub_inb_marketings.refered_by,
    //         sub_inb_marketings.contact,
    //         sub_inb_marketings.contact_no,
    //         sub_inb_marketings.remarks,
    //         companies.code as company_name,
    //         teams.name as team_name,
    //         buyers.name as buyer_name,
    //         currencies.code as currency_id,
    //         users.name as team_member_name,
    //         uoms.code as uom_id,
    //         sum (sub_inb_services.qty) as qty,
    //         avg (sub_inb_services.rate) as rate,
    //         sum (sub_inb_services.amount) as amount,
    //         sum(sub_inb_services.sample_req_qty) as sample_req_qty
    //         ')
    //     ->leftJoin('buyers', function($join)  {
    //         $join->on('sub_inb_marketings.buyer_id', '=', 'buyers.id');
    //     })
    //     ->leftJoin('companies', function($join)  {
    //         $join->on('sub_inb_marketings.company_id', '=', 'companies.id');
    //     })
    //     ->leftJoin('teams', function($join)  {
    //         $join->on('sub_inb_marketings.team_id', '=', 'teams.id');
    //     })
    //     ->leftJoin('currencies', function($join)  {
    //         $join->on('sub_inb_marketings.currency_id', '=', 'currencies.id');
    //     })
    //     ->leftJoin('sub_inb_services', function($join)  {
    //         $join->on('sub_inb_services.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
    //     })
    //     ->leftJoin('uoms', function($join)  {
    //         $join->on('sub_inb_services.uom_id', '=', 'uoms.id');
    //     })
    //     ->leftJoin('teammembers', function($join)  {
    //         $join->on('teammembers.id', '=', 'sub_inb_marketings.teammember_id');
    //     })
    //     ->leftJoin('users', function($join)  {
    //         $join->on('users.id', '=', 'teammembers.user_id');
    //     })
       
    //     ->when(request('company_id'), function ($q) {
	// 		return $q->where('sub_inb_marketings.company_id', '=', request('company_id', 0));
	// 	})
	// 	->when(request('team_id'), function ($q) {
	// 		return $q->where('sub_inb_marketings.team_id', '=', request('team_id', 0));
	// 	})
	// 	->when(request('teammember_id'), function ($q) {
	// 		return $q->where('sub_inb_marketings.teammember_id', '=', request('teammember_id', 0));
	// 	})
	// 	->when(request('production_area_id'), function ($q) {
	// 		return $q->where('sub_inb_marketings.production_area_id', '=', request('production_area_id', 0));
    //     })
    //     ->when(request('date_from'), function ($q) {
    //         return $q->where('sub_inb_marketings.mkt_date', '>=',request('date_from', 0));
    //     })
    //     ->when(request('date_to'), function ($q) {
    //         return $q->where('sub_inb_marketings.mkt_date', '<=',request('date_to', 0));
    //     })
    //     ->when(request('id'),function($q){
    //         return $q->where('sub_inb_marketings.id','=',request('id',0));
    //     })
    //     ->groupBy([
	// 		'sub_inb_marketings.id',			
	// 		'sub_inb_marketings.company_id',
	// 		'sub_inb_marketings.currency_id',
	// 		'sub_inb_marketings.production_area_id',
	// 		'sub_inb_marketings.team_id',
	// 		'sub_inb_marketings.teammember_id',
	// 		'sub_inb_marketings.buyer_id',
	// 		'sub_inb_marketings.mkt_date',
	// 		'sub_inb_marketings.refered_by',
	// 		'sub_inb_marketings.contact',
    //         'sub_inb_marketings.contact_no',
    //         'sub_inb_marketings.remarks',
    //         'uoms.code',
    //         'currencies.code',
	// 		'companies.code',
	// 		'teams.name',
	// 		'buyers.name',
    //         'users.name'
    //     ])
    //     ->get()
    //     ->map(function ($rows) use($productionarea,$image_arr)  {
    //         $rows->prod_area_name = $productionarea[$rows->production_area_id];
    //         $rows->teammember=$rows->team_member_name;
    //         $rows->file_src=$image_arr[$rows->id];
    //         $rows->mkt_date=date('d-M-y',strtotime($rows->mkt_date));
    //         $rows->qty=number_format($rows->qty,2,'.',',');
    //         $rows->amount=number_format($rows->amount,2,'.',',');
    //         $rows->sample_req_qty=number_format($rows->sample_req_qty,2,'.',',');
    //         return $rows;
    //     });
    //     return $rows;
    // }

    // public function html(){
    //     $subinbmarketing = $this->reportData();
    //     echo json_encode($subinbmarketing);
  
    // }

    public function getData(){
        $date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
        $company_id=request('company_id', 0);
        $team_id=request('team_id', 0);
        $teammember_id=request('teammember_id', 0);
        $production_area_id=request('production_area_id', 0);

        $datefrom=null;
        $dateto=null;
        $company=null;
        $team=null;
        $teammember=null;
        $productionareaId=null;

        if($date_from){
			$datefrom=" and sub_inb_marketings.mkt_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sub_inb_marketings.mkt_date<='".$date_to."' ";
		}
        if($company_id){
			$company=" and sub_inb_marketings.buyer_id=$buyer_id ";
		}
		if($team_id){
			$team=" and sub_inb_marketings.team_id=$team_id ";
		}
        if($teammember_id){
			$teammember=" and sub_inb_marketings.teammember_id=$teammember_id ";
		}
        if($production_area_id){
			$productionareaId=" and sub_inb_marketings.production_area_id=$production_area_id ";
		}

        $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');

        $rows=collect(
            \DB::select("
            select
            m.company_id,
            m.production_area_id,
            m.team_id,
            m.teammember_id,
            m.buyer_id,
            m.refered_by,
            m.company_code,
            m.team_name,
            m.teammember_name,
            m.buyer_name,
            m.est_dlv_month,
            m.est_dlv_month_no,
            m.est_dlv_year,
            m.est_dlv_year_full,
            sum(m.qty) as qty,
            avg(m.rate) as rate,
            sum(m.amount) as amount
            from
            (
                select
                sub_inb_marketings.id,
                sub_inb_marketings.company_id,
                sub_inb_marketings.production_area_id,
                sub_inb_marketings.team_id,
                sub_inb_marketings.teammember_id,
                sub_inb_marketings.buyer_id,
                sub_inb_marketings.mkt_date,
                sub_inb_marketings.refered_by,
                companies.code as company_code,
                teams.name as team_name,
                users.name as teammember_name,
                buyers.name as buyer_name,
                to_char(sub_inb_services.est_delv_date, 'Month') as est_dlv_month,
                to_char(sub_inb_services.est_delv_date, 'MM') as est_dlv_month_no,
                to_char(sub_inb_services.est_delv_date, 'yy') as est_dlv_year,
                to_char(sub_inb_services.est_delv_date, 'yyyy') as est_dlv_year_full,
                sub_inb_services.qty,
                sub_inb_services.rate,
                sub_inb_services.amount
                from
                sub_inb_marketings
                join companies on companies.id=sub_inb_marketings.company_id
                join teams on teams.id=sub_inb_marketings.team_id
                join teammembers on teammembers.id=sub_inb_marketings.teammember_id
                join users on users.id=teammembers.user_id
                join buyers on buyers.id=sub_inb_marketings.buyer_id
                join sub_inb_services on sub_inb_services.sub_inb_marketing_id=sub_inb_marketings.id
                where sub_inb_services.est_delv_date is not null
                $datefrom $dateto $company $team $teammember $productionareaId
            )m
            group by
            m.company_id,
            m.production_area_id,
            m.team_id,
            m.teammember_id,
            m.buyer_id,
            m.refered_by,
            m.company_code,
            m.team_name,
            m.teammember_name,
            m.buyer_name,
            m.est_dlv_month,
            m.est_dlv_month_no,
            m.est_dlv_year,
            m.est_dlv_year_full
            order by 
            m.company_id,
            m.est_dlv_year,
            m.est_dlv_month_no asc
            
        "))
        ->map(function($rows) use($productionarea){
            $rows->month=$rows->est_dlv_month."-".$rows->est_dlv_year;
			$rows->start_date=$rows->est_dlv_year_full."-".$rows->est_dlv_month_no."-01";
            $rows->production_area=$productionarea[$rows->production_area_id];
            return $rows;
        });

        $monthArr=[];
        $monthwiseArr=[];
        foreach ($rows as $row) {
            $monthArr[$row->month]=$row->month;
            $monthwiseArr[$row->company_id][$row->production_area_id][$row->team_id][$row->teammember_id][$row->buyer_id][$row->refered_by][$row->month]['qty']=$row->qty;
            $monthwiseArr[$row->company_id][$row->production_area_id][$row->team_id][$row->teammember_id][$row->buyer_id][$row->refered_by][$row->month]['rate']=$row->rate;
            $monthwiseArr[$row->company_id][$row->production_area_id][$row->team_id][$row->teammember_id][$row->buyer_id][$row->refered_by][$row->month]['amount']=$row->amount;
        }

        //dd($monthwiseArr);die;

        return Template::loadView('Report.Subcontract.Inbound.SubInbMarketingReportMatrix',['rows'=>$rows,'monthArr'=>$monthArr,'monthwiseArr'=>$monthwiseArr]);
    }

    public function getdetail()
    {
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $fabricshape=array_prepend(config('bprs.fabricshape'),'-Select-','');
	    $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $construction=array_prepend(array_pluck($this->construction->get(),'name','id'),'-Select-','');
        $yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        
         
        $subinbservices=array();
        $rows=$this->subinbservice
        ->join('sub_inb_marketings', function($join)  {
            $join->on('sub_inb_marketings.id', '=', 'sub_inb_services.sub_inb_marketing_id');
        })
        ->where([['sub_inb_marketing_id','=',request('id',0)]])
        ->get([
        	'sub_inb_services.*',
        	'sub_inb_marketings.production_area_id'
        ]);
        foreach($rows as $row){
            $subinbservice['id']=$row->id;
            $subinbservice['production_area_id']=$row->production_area_id;
            $subinbservice['sub_inb_marketing_id']=$row->sub_inb_marketing_id;
            $subinbservice['dyeing_type_id']=$dyetype[$row->dyeing_type_id];
		    $subinbservice['fabricshape']=$fabricshape[$row->fabric_shape_id];
            $subinbservice['colorrange']=$colorrange[$row->colorrange_id];
            $subinbservice['aop_type']=$embelishmenttype[$row->embelishment_type_id];
		    $subinbservice['from_impression']=$row->from_impression;
		    $subinbservice['to_impression']=$row->to_impression;
		    $subinbservice['from_coverage']=$row->from_coverage;
		    $subinbservice['to_coverage']=$row->to_coverage;
            $subinbservice['fabrication']=$construction[$row->construction_id];
            $subinbservice['fabric_look_id']=$fabriclooks[$row->fabric_look_id];
            $subinbservice['gmtspart_id']=$gmtspart[$row->gmtspart_id];
            $subinbservice['yarncount_id']=$yarncount[$row->yarncount_id];
            $subinbservice['uom_id']=$uom[$row->uom_id];
            $subinbservice['gauge']=$row->gauge;
            $subinbservice['from_gsm']=$row->from_gsm;
            $subinbservice['to_gsm']=$row->to_gsm;
            $subinbservice['qty']=$row->qty;
            $subinbservice['rate']=$row->rate;
            $subinbservice['amount']=$row->amount;
            $subinbservice['est_delv_date']=$row->est_delv_date;
            $subinbservice['remarks']=$row->remarks;
            $subinbservice['sample_req_qty']=$row->sample_req_qty;
            

            array_push($subinbservices,$subinbservice);
        }
        echo json_encode($subinbservices);
    }
}

