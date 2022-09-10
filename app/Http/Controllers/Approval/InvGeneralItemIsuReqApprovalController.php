<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRqRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
class InvGeneralItemIsuReqApprovalController extends Controller
{
	private $invgeneralisurq;
	private $company;
	private $buyer;
	private $team;
	private $teammember;
	public function __construct(
		InvGeneralIsuRqRepository $invgeneralisurq,
		CompanyRepository $company,
		BuyerRepository $buyer,
		TeamRepository $team,
		TeammemberRepository $teammember
	)
    {
		$this->invgeneralisurq=$invgeneralisurq;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->team = $team;
        $this->teammember = $teammember;
		$this->middleware('auth');

		$this->middleware('permission:approvefirst.invgeneralitemisureqs',   ['only' => ['firstapproved']]);
        $this->middleware('permission:approvesecond.invgeneralitemisureqs', ['only' => ['secondapproved']]);
        $this->middleware('permission:approvethird.invgeneralitemisureqs',   ['only' => ['thirdapproved']]);
        $this->middleware('permission:approvefinal.invgeneralitemisureqs', ['only' => ['finalapproved']]);
    }
    public function index() {
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
        return Template::loadView('Approval.InvGeneralItemIsuReqApproval',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember]);	
    }
	public function reportData() {
		$paymode=config('bprs.paymode');
		 //$location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        //$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-',''); 
		$approval_type_id=request('approval_type_id');
		$data = DB::table("inv_general_isu_rqs")
		->select("inv_general_isu_rqs.*",
		"companies.code as company_id",
		"locations.name as location_id"
		)
		->join('companies',function($join){
		$join->on('companies.id','=','inv_general_isu_rqs.company_id');
		})
		->leftJoin('locations',function($join){
		$join->on('locations.id','=','inv_general_isu_rqs.location_id');
		})
		/*->leftJoin('currencies',function($join){
		$join->on('currencies.id','=','inv_general_isu_rqs.currency_id');
		})*/
		->when(request('company_id'), function ($q) {
		return $q->where('inv_general_isu_rqs.company_id', '=', request('company_id', 0));
		})
		->when(request('req_date_from'), function ($q) {
		return $q->where('inv_general_isu_rqs.rq_date', '>=',request('req_date_from', 0));
		})
		->when(request('req_date_to'), function ($q) {
		return $q->where('inv_general_isu_rqs.rq_date', '<=',request('req_date_to', 0));
		})
		->when($approval_type_id, function ($q) use ($approval_type_id){
			if($approval_type_id==1){
			return $q->whereNull('inv_general_isu_rqs.first_approved_at');
			}
			if($approval_type_id==2){
			return $q->whereNotNull('inv_general_isu_rqs.first_approved_at')->whereNull('inv_general_isu_rqs.second_approved_at');
			}
			if($approval_type_id==3){
			return $q->whereNotNull('inv_general_isu_rqs.second_approved_at')->whereNull('inv_general_isu_rqs.third_approved_at');
			}
			if($approval_type_id==10){
			return $q->whereNotNull('inv_general_isu_rqs.third_approved_at')->whereNull('inv_general_isu_rqs.final_approved_at');
			}
		})
		//->whereNotNull('mkt_cost_quote_prices.confirm_date')
		->orderBy('inv_general_isu_rqs.id','desc')
		->get()
		->map(function($data) use($paymode){
          //$data->pay_mode=isset($paymode[$data->pay_mode])?$paymode[$data->pay_mode]:'';
          $data->req_date=date('d-M-Y',strtotime($data->rq_date));
          return $data;
		});
		$datas=array();
		foreach($data as $row){
			array_push($datas,$row);
		}
		echo json_encode($datas);
    }

    public function firstapproved (Request $request)
    {

    	foreach($request->id as $index=>$id){
			if($id){
				$user = \Auth::user(); 
				$first_approved_at=date('Y-m-d h:i:s');
				$invgeneralisurq = $this->invgeneralisurq->update($id,
				['first_approved_by' => $user->id,  'first_approved_at' =>  $first_approved_at]);
			}
		}
		return response()->json(array('success' => true,'type' => 'firstapproved', 'message' => 'Approved Successfully'), 200);

    }


    public function secondapproved (Request $request)
    {
    	foreach($request->id as $index=>$id){
			if($id){
				$user = \Auth::user(); 
				$second_approved_at=date('Y-m-d h:i:s');
				$invgeneralisurq = $this->invgeneralisurq->update($id,
				['second_approved_by' => $user->id,  'second_approved_at' =>  $second_approved_at]);
			}
		}
		return response()->json(array('success' => true, 'type' => 'secondapproved','message' => 'Approved Successfully'), 200);
    }

    public function thirdapproved (Request $request)
    {
    	foreach($request->id as $index=>$id){
			if($id){
				$user = \Auth::user(); 
				$third_approved_at=date('Y-m-d h:i:s');
				$invgeneralisurq = $this->invgeneralisurq->update($id,
				['third_approved_by' => $user->id,  'third_approved_at' =>  $third_approved_at]);
			}
		}
		return response()->json(array('success' => true,'type' => 'thirdapproved', 'message' => 'Approved Successfully'), 200);
    }
    public function finalapproved (Request $request)
    {
    	foreach($request->id as $index=>$id){
			if($id){
				$user = \Auth::user(); 
				$final_approved_at=date('Y-m-d h:i:s');
				$invgeneralisurq = $this->invgeneralisurq->update($id,
				['final_approved_by' => $user->id,  'final_approved_at' =>  $final_approved_at]);
			}
		}
		return response()->json(array('success' => true,'type' => 'finalapproved', 'message' => 'Approved Successfully'), 200);
    }
}
