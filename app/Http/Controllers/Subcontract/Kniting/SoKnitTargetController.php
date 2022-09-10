<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitTargetRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\SoKnitTargetRequest;

class SoKnitTargetController extends Controller
{
    private $soknittarget;
    private $company;
    private $buyer;

    public function __construct(SoKnitTargetRepository $soknittarget, BuyerRepository $buyer, CompanyRepository $company)
    {
        $this->soknittarget = $soknittarget;
        $this->buyer = $buyer;
        $this->company = $company;  
        $this->middleware('auth');
           $this->middleware('permission:view.soknittargets',   ['only' => ['create', 'index','show']]);
           $this->middleware('permission:create.soknittargets', ['only' => ['store']]);
           $this->middleware('permission:edit.soknittargets',   ['only' => ['update']]);
           $this->middleware('permission:delete.soknittargets', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $soknittarget=$this->soknittarget
        ->leftJoin('buyers',function($join) {
            $join->on('buyers.id','=','so_knit_targets.buyer_id');
        })
        ->leftJoin('companies',function($join) {
            $join->on('companies.id','=','so_knit_targets.company_id');
        })
        ->get([
        'so_knit_targets.*',
        'companies.name as company_name',
        //'companies.id as company_id',
        //'buyers.id as buyer_id'
        'buyers.name as buyer_name'
        ])
        ->map(function ($soknittarget){
            $soknittarget->target_date=date('d-M-Y',strtotime($soknittarget->target_date));
            $soknittarget->execute_month=date('d-M-Y',strtotime($soknittarget->execute_month));
            return $soknittarget;
        });
        echo json_encode($soknittarget);
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
        return Template::loadView("Subcontract.Kniting.SoKnitTarget",['buyer'=>$buyer, 'company'=>$company]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoKnitTargetRequest $request)
    {
        $soknittarget=$this->soknittarget->create($request->except(['id','amount']));
        if($soknittarget){
            return response()->json(array('success' => true,'id' =>  $soknittarget->id,'message' => 'Save Successfully'),200);
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
         $soknittarget=$this->soknittarget
        ->leftJoin('buyers',function($join) {
            $join->on('buyers.id','=','so_knit_targets.buyer_id');
        })
        ->leftJoin('companies',function($join) {
            $join->on('companies.id','=','so_knit_targets.company_id');
        })

        ->where([['so_knit_targets.id','=',$id]])
        ->get([
                'so_knit_targets.*',
                'companies.id as company_id',
                'companies.name as company_name',
                'buyers.id as buyer_id',
                'buyers.name as buyer_name'
         ])
        ->map(function($soknittarget){
            $soknittarget->amount=$soknittarget->qty*$soknittarget->rate;
            return $soknittarget;
        })
        ->first();
               $row ['fromData'] = $soknittarget;
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
    public function update(SoKnitTargetRequest $request, $id)
    {
        $soknittarget=$this->soknittarget->update($id,$request->except(['id','amount']));
        if($soknittarget){
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
        if($this->soknittarget->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}
