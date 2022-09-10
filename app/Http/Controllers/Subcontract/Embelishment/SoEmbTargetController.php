<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbTargetRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbTargetRequest;

class SoEmbTargetController extends Controller
{
    private $soembtarget;
    private $company;
    private $buyer;
    private $embelishment;

    public function __construct(SoEmbTargetRepository $soembtarget, BuyerRepository $buyer, CompanyRepository $company, EmbelishmentRepository $embelishment)
    {
        $this->soembtarget = $soembtarget;
        $this->buyer = $buyer;
        $this->company = $company;  
        $this->embelishment = $embelishment;  
        $this->middleware('auth');
           // $this->middleware('permission:view.soembtargets',   ['only' => ['create', 'index','show']]);
           // $this->middleware('permission:create.soembtargets', ['only' => ['store']]);
           // $this->middleware('permission:edit.soembtargets',   ['only' => ['update']]);
           // $this->middleware('permission:delete.soembtargets', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $soembtarget=$this->soembtarget
        ->leftJoin('buyers',function($join) {
            $join->on('buyers.id','=','so_emb_targets.buyer_id');
        })
        ->leftJoin('companies',function($join) {
            $join->on('companies.id','=','so_emb_targets.company_id');
        })
        ->leftJoin('embelishments', function($join){
            $join->on('embelishments.id','=','so_emb_targets.emb_id');
        })
        ->get([
        'so_emb_targets.*',
        'companies.name as company_name',
        //'companies.id as company_id',
        //'buyers.id as buyer_id'
        'buyers.name as buyer_name',
       // 'embelishments.id as emb_id',
        'embelishments.name as emb_name'
        ])
        ->map(function ($soembtarget){
            $soembtarget->target_date=date('d-M-Y',strtotime($soembtarget->target_date));
            $soembtarget->execute_month=date('d-M-Y',strtotime($soembtarget->execute_month));
            return $soembtarget;
        });
        echo json_encode($soembtarget);
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
         $embelishment=array_prepend(array_pluck($this->embelishment->getEmbelishments(),'name','id'),'-Select-','');
        return Template::loadView("Subcontract.Embelishment.SoEmbTarget",['buyer'=>$buyer, 'company'=>$company, 'embelishment' => $embelishment]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoEmbTargetRequest $request)
    {
        $soembtarget=$this->soembtarget->create($request->except(['id','amount']));
        if($soembtarget){
            return response()->json(array('success' => true,'id' =>  $soembtarget->id,'message' => 'Save Successfully'),200);
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
         $soembtarget=$this->soembtarget
        ->leftJoin('buyers',function($join) {
            $join->on('buyers.id','=','so_emb_targets.buyer_id');
        })
        ->leftJoin('companies',function($join) {
            $join->on('companies.id','=','so_emb_targets.company_id');
        })
        ->leftJoin('embelishments', function($join){
            $join->on('embelishments.id','=','so_emb_targets.emb_id');
        })

        ->where([['so_emb_targets.id','=',$id]])
        ->get([
                'so_emb_targets.*',
                'companies.id as company_id',
                'companies.name as company_name',
                'buyers.id as buyer_id',
                'buyers.name as buyer_name',
                'embelishments.id as emb_id',
                'embelishments.name as emb_name'
         ])
        ->map(function($soembtarget){
            $soembtarget->amount=$soembtarget->qty*$soembtarget->rate;
            return $soembtarget;
        })
        ->first();

               $row ['fromData'] = $soembtarget;
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
    public function update(SoEmbTargetRequest $request, $id)
    {
        $soembtarget=$this->soembtarget->update($id,$request->except(['id','amount']));
        if($soembtarget){
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
        if($this->soembtarget->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}
