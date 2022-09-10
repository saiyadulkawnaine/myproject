<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Bom\CadRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\Approval\ApprovalCommentHistoryRepository;

class CadApprovalController extends Controller
{
	private $cad;
	private $company;
	private $buyer;
	private $team;
	private $teammember;
	private $approvalcommenthistory;

	public function __construct(
		CadRepository $cad,
		CompanyRepository $company,
		BuyerRepository $buyer,
		TeamRepository $team,
		TeammemberRepository $teammember,
		ApprovalCommentHistoryRepository $approvalcommenthistory
	)
    {
		$this->cad=$cad;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->team = $team;
        $this->teammember = $teammember;
        $this->approvalcommenthistory = $approvalcommenthistory;

		$this->middleware('auth');

		$this->middleware('permission:approvefirst.cads',   ['only' => ['firstapproved']]);
        $this->middleware('permission:approvesecond.cads', ['only' => ['secondapproved']]);
        $this->middleware('permission:approvethird.cads',   ['only' => ['thirdapproved']]);
        $this->middleware('permission:approvefinal.cads', ['only' => ['finalapproved']]);
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
        return Template::loadView('Approval.CadApproval',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember]);	
    }
	public function reportData() {
		 //$location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        //$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-',''); 
		$approval_type_id=request('approval_type_id');

		$data = DB::table("cads")
		->select("cads.*",
		"buyers.code as buyer_name",
		"styles.style_ref",
		"styles.style_description",
		"styles.flie_src",
		"seasons.name as season_name",
		"productdepartments.department_name",
		"uoms.code as uom_code",
		'users.name as team_member',
		'teams.name as team_name'
		)
		->join('styles',function($join){
			$join->on('cads.style_id','=','styles.id');
		})
		->join('buyers',function($join){
			$join->on('styles.buyer_id','=','buyers.id');
		})
		->leftJoin('seasons',function($join){
			$join->on('styles.season_id','=','seasons.id');
		})
		->leftJoin('productdepartments',function($join){
			$join->on('styles.productdepartment_id','=','productdepartments.id');
		})
		->leftJoin('uoms',function($join){
			$join->on('styles.uom_id','=','uoms.id');
		})
		->leftJoin('teammembers',function($join){
			$join->on('teammembers.id','=','styles.teammember_id');
		})
		->leftJoin('users',function($join){
			$join->on('users.id','=','teammembers.user_id');
		})
		->leftJoin('teams',function($join){
			$join->on('teams.id','=','styles.team_id');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('cads.company_id', '=', request('company_id', 0));
		})
		->when(request('cad_date_from'), function ($q) {
		return $q->where('cads.cad_date', '>=',request('cad_date_from', 0));
		})
		->when(request('req_date_to'), function ($q) {
		return $q->where('cads.cad_date', '<=',request('cad_date_to', 0));
		})
		->when($approval_type_id, function ($q) use ($approval_type_id){
			if($approval_type_id==1){
			return $q->whereNull('cads.first_approved_at');
			}
			if($approval_type_id==2){
			return $q->whereNotNull('cads.first_approved_at')->whereNull('cads.second_approved_at');
			}
			if($approval_type_id==3){
			return $q->whereNotNull('cads.second_approved_at')->whereNull('cads.third_approved_at');
			}
			if($approval_type_id==10){
			return $q->whereNotNull('cads.third_approved_at')->whereNull('cads.final_approved_at');
			}
		})
		->where([['cads.ready_to_approve_id','=',1]])
		->orderBy('cads.id','desc')
		->get()
		->map(function($data){
          $data->cad_date=date('d-M-Y',strtotime($data->cad_date));
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
				$cad = $this->cad->update($id,
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
				$cad = $this->cad->update($id,
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
				$cad = $this->cad->update($id,
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
				$cad = $this->cad->update($id,
				['final_approved_by' => $user->id,  'final_approved_at' =>  $final_approved_at]);
			}
		}
		return response()->json(array('success' => true,'type' => 'finalapproved', 'message' => 'Approved Successfully'), 200);
    }

    public function approvalReturn(Request $request){
    	$id=$request->id;
    	$returned_coments=$request->returned_coments;
    	$aproval_type=$request->aproval_type;
		$user = \Auth::user(); 
		$returned_at=date('Y-m-d h:i:s');
		$cad = $this->cad->update($id,[
			'returned_by' => $user->id,  
			'returned_at' =>  $returned_at,
			'returned_coments' =>  $returned_coments,
			'first_approved_by' => NULL,  
			'first_approved_at' =>  NULL,
			'second_approved_by' => NULL,  
			'second_approved_at' =>  NULL,
			'third_approved_by' => NULL,  
			'third_approved_at' =>  NULL,
			'final_approved_by' => NULL,  
			'final_approved_at' =>  NULL,
		]);

		$this->approvalcommenthistory->create([
            	'model_id'=>$id,
            	'model_type'=>'cads',
            	'comments'=>$returned_coments,
            	'comments_by'=>$user->id,
            	'comments_at'=>$returned_at
        ]);

		return response()->json(array('success' => true,'type' => $aproval_type, 'message' => 'Returned Successfully'), 200);
    }
}
