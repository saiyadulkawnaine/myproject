<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingTargetRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingTargetRequest;
class SoDyeingTargetController extends Controller
{
    private $sodyeingtarget;
    private $company;
    private $buyer;
    private $teammember;

    public function __construct(
        SoDyeingTargetRepository $sodyeingtarget, 
        BuyerRepository $buyer, 
        CompanyRepository $company,
        TeammemberRepository $teammember
    )
    {
        $this->sodyeingtarget = $sodyeingtarget;
        $this->buyer = $buyer;
        $this->company = $company;  
        $this->teammember = $teammember;  
        $this->middleware('auth');
           // $this->middleware('permission:view.sodyeingtargets',   ['only' => ['create', 'index','show']]);
           // $this->middleware('permission:create.sodyeingtargets', ['only' => ['store']]);
           // $this->middleware('permission:edit.sodyeingtargets',   ['only' => ['update']]);
           // $this->middleware('permission:delete.sodyeingtargets', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $sodyeingtarget=$this->sodyeingtarget
        ->leftJoin('buyers',function($join) {
            $join->on('buyers.id','=','so_dyeing_targets.buyer_id');
        })
        ->leftJoin('companies',function($join) {
            $join->on('companies.id','=','so_dyeing_targets.company_id');
        })
        ->leftJoin('teammembers', function($join)  {
            $join->on('teammembers.id', '=', 'so_dyeing_targets.teammember_id');
        })
        ->leftJoin('users', function($join)  {
            $join->on('teammembers.user_id', '=', 'users.id');
        })
        ->get([
        'so_dyeing_targets.*',
        'companies.name as company_name',
        //'companies.id as company_id',
        //'buyers.id as buyer_id'
        'buyers.name as buyer_name',
        'users.name as teammember_id'
        ])
        ->map(function ($sodyeingtarget) {
            $sodyeingtarget->target_date=date('d-M-Y',strtotime($sodyeingtarget->target_date));
            $sodyeingtarget->execute_month=date('d-M-Y',strtotime($sodyeingtarget->execute_month));
            return $sodyeingtarget;
        });
        echo json_encode($sodyeingtarget);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
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
        return Template::loadView("Subcontract.Dyeing.SoDyeingTarget",['buyer'=>$buyer, 'company'=>$company,'teammember'=>$teammember]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoDyeingTargetRequest $request)
    {
        $sodyeingtarget=$this->sodyeingtarget->create($request->except(['id','amount']));
        if($sodyeingtarget){
            return response()->json(array('success' => true,'id' =>  $sodyeingtarget->id,'message' => 'Save Successfully'),200);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {    
         $sodyeingtarget=$this->sodyeingtarget
        ->leftJoin('buyers',function($join) {
            $join->on('buyers.id','=','so_dyeing_targets.buyer_id');
        })
        ->leftJoin('companies',function($join) {
            $join->on('companies.id','=','so_dyeing_targets.company_id');
        })

        ->where([['so_dyeing_targets.id','=',$id]])
        ->get([
                'so_dyeing_targets.*',
                'companies.id as company_id',
                'companies.name as company_name',
                'buyers.id as buyer_id',
                'buyers.name as buyer_name'
         ])
        ->map(function($sodyeingtarget){
            $sodyeingtarget->amount=$sodyeingtarget->qty*$sodyeingtarget->rate;
            return $sodyeingtarget;
        })
        ->first();
               $row ['fromData'] = $sodyeingtarget;
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
    public function update(SoDyeingTargetRequest $request, $id)
    {
        $sodyeingtarget=$this->sodyeingtarget->update($id,$request->except(['id','amount']));
        if($sodyeingtarget){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->sodyeingtarget->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
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
}
