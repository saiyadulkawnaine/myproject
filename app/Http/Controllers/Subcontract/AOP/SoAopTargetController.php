<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopTargetRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopTargetRequest;

class SoAopTargetController extends Controller
{
    private $soaoptarget;
    private $company;
    private $buyer;
    private $teammember;

    public function __construct(
        SoAopTargetRepository $soaoptarget, 
        BuyerRepository $buyer, 
        CompanyRepository $company,
        TeammemberRepository $teammember
    )
    {
        $this->soaoptarget = $soaoptarget;
        $this->buyer = $buyer;
        $this->company = $company; 
        $this->teammember = $teammember; 

        $this->middleware('auth');
           // $this->middleware('permission:view.soaoptargets',   ['only' => ['create', 'index','show']]);
           // $this->middleware('permission:create.soaoptargets', ['only' => ['store']]);
           // $this->middleware('permission:edit.soaoptargets',   ['only' => ['update']]);
           // $this->middleware('permission:delete.soaoptargets', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      $soaoptarget=$this->soaoptarget
        ->leftJoin('buyers',function($join) {
            $join->on('buyers.id','=','so_aop_targets.buyer_id');
        })
        ->leftJoin('companies',function($join) {
            $join->on('companies.id','=','so_aop_targets.company_id');
        })
        ->leftJoin('teammembers', function($join)  {
            $join->on('teammembers.id', '=', 'so_aop_targets.teammember_id');
        })
        ->leftJoin('users', function($join)  {
            $join->on('teammembers.user_id', '=', 'users.id');
        })
        ->get([
        'so_aop_targets.*',
        'companies.name as company_name',
        //'companies.id as company_id',
        //'buyers.id as buyer_id'
        'buyers.name as buyer_name',
        'users.name as teammember_id'
        ])
        ->map(function ($soaoptarget) {
            $soaoptarget->target_date=date('d-M-Y',strtotime($soaoptarget->target_date));
            $soaoptarget->execute_month=date('d-M-Y',strtotime($soaoptarget->execute_month));
            return $soaoptarget;
        });
        echo json_encode($soaoptarget);
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
        return Template::loadView("Subcontract.AOP.SoAopTarget",['buyer'=>$buyer, 'company'=>$company,'teammember'=>$teammember]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopTargetRequest $request)
    {
        $soaoptarget=$this->soaoptarget->create($request->except(['id','amount']));
        if($soaoptarget){
            return response()->json(array('success' => true,'id' =>  $soaoptarget->id,'message' => 'Save Successfully'),200);
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
         $soaoptarget=$this->soaoptarget
        ->leftJoin('buyers',function($join) {
            $join->on('buyers.id','=','so_aop_targets.buyer_id');
        })
        ->leftJoin('companies',function($join) {
            $join->on('companies.id','=','so_aop_targets.company_id');
        })

        ->where([['so_aop_targets.id','=',$id]])
        ->get([
                'so_aop_targets.*',
                'companies.id as company_id',
                'companies.name as company_name',
                'buyers.id as buyer_id',
                'buyers.name as buyer_name'
         ])
        ->map(function($soaoptarget){
            $soaoptarget->amount=$soaoptarget->qty*$soaoptarget->rate;
            return $soaoptarget;
        })
        ->first();

               $row ['fromData'] = $soaoptarget;
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
    public function update(SoAopTargetRequest $request, $id)
    {
        $soaoptarget=$this->soaoptarget->update($id,$request->except(['id','amount']));
        if($soaoptarget){
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
        if($this->soaoptarget->delete($id)){
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
