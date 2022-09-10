<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;

use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
class ProjectionProgressController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	public function __construct(StyleRepository $style,CompanyRepository $company,BuyerRepository $buyer)
    {
		$this->style=$style;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->middleware('auth');
		$this->middleware('permission:view.projectionprogressreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-',0);
      return Template::loadView('Report.ProjectionProgress',['company'=>$company,'buyer'=>$buyer]);
    }
	public function reportData() {
      $styles=array();
		$rows=$this->style
		->join('buyers', function($join)  {
		$join->on('styles.buyer_id', '=', 'buyers.id');
		})
		->join('uoms', function($join)  {
		$join->on('styles.uom_id', '=', 'uoms.id');
		})
		->join('seasons', function($join)  {
		$join->on('styles.season_id', '=', 'seasons.id');
		})
		->join('teams', function($join)  {
		$join->on('styles.team_id', '=', 'teams.id');
		})
		->join('teammembers', function($join)  {
		$join->on('styles.teammember_id', '=', 'teammembers.id');
		})
		->join('users', function($join)  {
		$join->on('users.id', '=', 'teammembers.user_id');
		})
		->join('productdepartments', function($join)  {
		$join->on('productdepartments.id', '=', 'styles.productdepartment_id');
		})
		->leftJoin('projections', function($join)  {
		$join->on('projections.style_id', '=', 'styles.id');
		})
		->join('companies', function($join)  {
		$join->on('companies.id', '=', 'projections.company_id');
		})
		->join('projection_countries', function($join)  {
			$join->on('projections.id', '=', 'projection_countries.projection_id');
		})
		
		->join('projection_qties', function($join)  {
			$join->on('projection_countries.id', '=', 'projection_qties.projection_country_id');
		})
		
		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('projections.company_id', '=', request('company_id', 0));
		})
		->when(request('proj_no'), function ($q) {
		return $q->where('projections.proj_no', 'like', '%'.request('proj_no', 0).'%');
		})
		->when(request('date_from'), function ($q) {
		return $q->where('projection_countries.country_ship_date', '>=', request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
		return $q->where('projection_countries.country_ship_date', '<=', request('date_to', 0));
		})
		->get([
		'styles.style_ref',
		'buyers.name as buyer_name',
		'uoms.code as uom_name',
		'seasons.name as season_name',
		'teams.name as team_name',
		'users.name as team_member_name',
		'productdepartments.department_name',
		'projections.id',
		'projections.proj_no',
		'projections.created_at',
		'projections.date',
		'companies.code as company_code',
		'projection_countries.country_ship_date',
		'projection_qties.style_gmt_id',
		'projection_qties.qty',
		'projection_qties.amount'
		]);
		$month=array();
		$style=array();
		$totAmt=0;
		$totQty=0;
		foreach($rows as $row){
		$index=$row->id."and".$row->country_ship_date;
		$m=date('M-y',strtotime($row->country_ship_date));
		$style[$index]['id']=	$row->id;
		$style[$index]['style_ref']=	$row->style_ref;
		$style[$index]['buyer']=	$row->buyer_name;
		$style[$index]['season']=	$row->season_name;
		$style[$index]['season_id']=	$row->season_id;
		$style[$index]['uom']=	$row->uom_name;
		$style[$index]['uom_id']=	$row->uom_id;
		$style[$index]['team']=	$row->team_name;
		$style[$index]['teammember']=	$row->team_member_name;
		$style[$index]['productdepartment']=$row->department_name;
		$style[$index]['company_code']=$row->company_code;
		$style[$index]['proj_no']=$row->proj_no;
		$style[$index]['country_ship_date']=$row->country_ship_date;
		$style[$index]['month']=$m;
		if(isset($style[$index]['qty'])){
			$style[$index]['qty']+=$row->qty;
		}else{
			$style[$index]['qty']=$row->qty;
		}
		if(isset($style[$index]['amount'])){
			$style[$index]['amount']+=$row->amount;
		}else{
			$style[$index]['amount']=$row->amount;
		}
		$totAmt+=$row->amount;
		$totQty+=$row->qty;
		
		if(isset($month[$m]['qty'])){
		$month[$m]['qty']+=$row->qty;
		}else{
			$month[$m]['qty']=$row->qty;
		}
		if(isset($month[$m]['amount'])){
		$month[$m]['amount']+=$row->amount;
		}else{
			$month[$m]['amount']=$row->amount;
		}
		
		}
		$datas=array();
		foreach($style as $key=>$value){
			$value['rate']=number_format($value['amount']/$value['qty'],4,'.',',');
			$value['qty']=number_format($value['qty'],0,'.',',');
			$value['amount']=number_format($value['amount'],2,'.',',');
		    array_push($datas,$value);
		}
		$ms=array();
		$mss=array();
		foreach($month as $k=>$v){
			$ms['name']=$k;
			$ms['qty']=$v['qty'];
			$ms['amount']=$v['amount'];
			array_push($mss,$ms);
		}
		$dd=array('total'=>1,'rows'=>$datas,'footer'=>array(0=>array('company_code'=>'','teammember'=>'','buyer'=>'','style_ref'=>'','proj_no'=>'','productdepartment'=>'','country_ship_date'=>'','qty'=>number_format($totQty,0,'.',','),'uom'=>'','rate'=>'','amount'=>number_format($totAmt,2,'.',','))));
		echo json_encode(array('datad'=>$dd,'month'=>$mss));
    }
}
