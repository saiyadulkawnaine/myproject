<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetCmRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetCmRequest;

class BudgetCmController extends Controller {

    private $budgetcm;
    private $budget;
    private $keycontrol;
    private $job;
    private $stylegmts;

    public function __construct(
        BudgetCmRepository $budgetcm,
        BudgetRepository $budget,
        KeycontrolRepository $keycontrol,
        JobRepository $job,
        StyleGmtsRepository $stylegmts
    ) {
        $this->budgetcm = $budgetcm;
        $this->budget = $budget;
        $this->keycontrol = $keycontrol;
        $this->job = $job;
        $this->stylegmts = $stylegmts;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetcms',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetcms', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetcms',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetcms', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $cmmethod=config('bprs.cmmethod');
        $budget=array_prepend(array_pluck($this->budget->get(),'name','id'),'-Select-','');
        $budgetcms=array();

        $rows=$this->budgetcm
        ->leftJoin('style_gmts', function($join)  {
        $join->on('style_gmts.id', '=', 'budget_cms.style_gmt_id');
        })
        ->leftJoin('item_accounts', function($join)  {
        $join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
        })
        ->where([['budget_id','=',request('budget_id',0)]])
        ->get([
            'budget_cms.*',
            'style_gmts.gmt_qty',
            'item_accounts.item_description as name',
        ]);
        /*foreach($rows as $row){
        $budgetcm['id']=	$row->id;
        $budgetcm['method_id']=	$cmmethod[$row->method_id];
        $budgetcm['amount']=	$row->amount;
        $budgetcm['bom_amount']=	$row->bom_amount;
        array_push($budgetcms,$budgetcm);
        }*/

        $cms=$this->budget
        ->selectRaw('
        budgets.id,
        sum(smp_cost_cms.amount) as amount
        ')
        ->join('jobs',function($join){
        $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('style_samples',function($join){
        $join->on('style_samples.style_id','=','styles.id');
        })
        ->leftJoin('smp_costs',function($join){
        $join->on('smp_costs.style_sample_id','=','style_samples.id');
        })
        ->leftJoin('smp_cost_cms',function($join){
        $join->on('smp_cost_cms.smp_cost_id','=','smp_costs.id');
        })
        ->where([['budgets.id','=',request('budget_id',0)]])
        ->groupBy([
        'budgets.id'
        ])
        ->get()->first();
        echo json_encode(['listdata'=>$rows,'cmsamount'=>$cms->amount]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $budget=array_prepend(array_pluck($this->budget->get(),'name','id'),'-Select-','');
        return Template::loadView('Bom.BudgetCm', ['budget'=>$budget]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetCmRequest $request) {

        $budget=$this->budget->find($request->budget_id);
        $JobQty=$this->job->totalJobGmtItemQty($budget->job_id,$request->style_gmt_id);
        $keycontrol=$this->keycontrol
        ->join('keycontrol_parameters', function($join)  {
        $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
        })
        ->where([['parameter_id','=',4]])
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$budget->budget_date])
        ->get([
        'keycontrol_parameters.value'
        ])->first();
        if(!$keycontrol->value){
            return response()->json(array('success' => false, 'message' => 'CPM Not found'), 200);
        }
        
        
        $smvrows=$this->budget
        ->join('jobs',function($join){
         $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('styles',function($join){
         $join->on('styles.id','=','jobs.style_id');
        })
        ->join('style_gmts',function($join){
         $join->on('style_gmts.style_id','=','styles.id');
        })
        ->where([['style_gmts.id','=',$request->style_gmt_id]])
        ->get([
        'style_gmts.smv',
        'style_gmts.gmt_qty',
        'style_gmts.sewing_effi_per'
        ])
        ->first();
        
        if(!$smvrows){
            return response()->json(array('success' => false, 'message' => 'GMT item ratio not found'), 200);
        }
        $cm_per_pcs=$request->smv*$keycontrol->value/($request->sewing_effi_per/100);
        $amount=$cm_per_pcs*$smvrows->gmt_qty*$budget->costing_unit_id;
        $prod_per_hour=(60*$request->no_of_man_power*($request->sewing_effi_per/100))/$request->smv;
        $bom_amount=$cm_per_pcs*$JobQty;
        /*$amount=0;
        foreach($smvrows as $smvrow){
            $amount+=($smvrow->smv*$smvrow->gmt_qty*$keycontrol->value*$budget->costing_unit_id)/($smvrow->sewing_effi_per/100);
        }
        
        if($amount===0){
            return response()->json(array('success' => false, 'message' => 'GMT SMV and Efficiency % not found'), 200);
        }*/
        
        //$request->request->add(['amount' => $amount]);
        //$request->request->add(['bom_amount' => $bom_amount]);
        \DB::beginTransaction();
        try
        {
		$budgetcm = $this->budgetcm->create([
        'method_id'=>1,
        'budget_id'=>$request->budget_id,
        'style_gmt_id'=>$request->style_gmt_id,
        'smv'=>$request->smv,
        'sewing_effi_per'=>$request->sewing_effi_per,
        'cpm'=>$keycontrol->value,
        'cm_per_pcs'=>$cm_per_pcs,
        'no_of_man_power'=>$request->no_of_man_power,
        'prod_per_hour'=>$prod_per_hour,
        'amount'=>$amount,
        'bom_amount'=>$bom_amount,

        ]);
        $this->stylegmts->update($request->style_gmt_id, [
        'smv'=>$request->smv,
        'sewing_effi_per'=>$request->sewing_effi_per,
        ]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

		$totalCost=$this->budget->totalCost($request->budget_id);
		//$priceBfrCommission=$this->budget->totalPriceBeforeCommission($request->budget_id);
        if ($budgetcm) {
            return response()->json(array('success' => true, 'id' => $budgetcm->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        //$budgetcm = $this->budgetcm->find($id);
        $budgetcm = $this->budgetcm
        ->leftJoin('style_gmts', function($join)  {
        $join->on('style_gmts.id', '=', 'budget_cms.style_gmt_id');
        })
        ->leftJoin('item_accounts', function($join)  {
        $join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
        })
        ->where([['budget_cms.id','=',$id]])
        ->get([
            'budget_cms.*',
            'style_gmts.gmt_qty',
            'item_accounts.item_description as style_gmt_name',
        ])
        ->first();
        $row ['fromData'] = $budgetcm;
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
    public function update(BudgetCmRequest $request, $id) {
        $budget=$this->budget->find($request->budget_id);
        $JobQty=$this->job->totalJobGmtItemQty($budget->job_id,$request->style_gmt_id);
        $keycontrol=$this->keycontrol
        ->join('keycontrol_parameters', function($join)  {
        $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
        })
        ->where([['parameter_id','=',4]])
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$budget->budget_date])
        ->get([
        'keycontrol_parameters.value'
        ])->first();
        if(!$keycontrol->value){
            return response()->json(array('success' => false, 'message' => 'CPM Not found'), 200);
        }
        
        
        $smvrows=$this->budget
        ->join('jobs',function($join){
         $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('styles',function($join){
         $join->on('styles.id','=','jobs.style_id');
        })
        ->join('style_gmts',function($join){
         $join->on('style_gmts.style_id','=','styles.id');
        })
        ->where([['style_gmts.id','=',$request->style_gmt_id]])
        ->get([
        'style_gmts.smv',
        'style_gmts.gmt_qty',
        'style_gmts.sewing_effi_per'
        ])
        ->first();
        
        if(!$smvrows){
            return response()->json(array('success' => false, 'message' => 'GMT item ratio not found'), 200);
        }
        $cm_per_pcs=$request->smv*$keycontrol->value/($request->sewing_effi_per/100);
        $amount=$cm_per_pcs*$smvrows->gmt_qty*$budget->costing_unit_id;
        $prod_per_hour=(60*$request->no_of_man_power*($request->sewing_effi_per/100))/$request->smv;
        $bom_amount=$cm_per_pcs*$JobQty;
       /* $amount=0;
        foreach($smvrows as $smvrow){
            $amount+=($smvrow->smv*$smvrow->gmt_qty*$keycontrol->value*$budget->costing_unit_id)/($smvrow->sewing_effi_per/100);
        }
        
        if($amount===0){
            return response()->json(array('success' => false, 'message' => 'GMT SMV and Efficiency % not found'), 200);
        }
        $bom_amount=$amount/$budget->costing_unit_id*$JobQty;
        $request->request->add(['amount' => $amount]);
        $request->request->add(['bom_amount' => $bom_amount]);*/
        \DB::beginTransaction();
        try
        {
            $budgetcm = $this->budgetcm->update($id,[ 
            'style_gmt_id'=>$request->style_gmt_id,
            'smv'=>$request->smv,
            'sewing_effi_per'=>$request->sewing_effi_per,
            'cpm'=>$keycontrol->value,
            'cm_per_pcs'=>$cm_per_pcs,
            'no_of_man_power'=>$request->no_of_man_power,
            'prod_per_hour'=>$prod_per_hour,
            'amount'=>$amount,
            'bom_amount'=>$bom_amount, 
            ]);
            $this->stylegmts->update($request->style_gmt_id, [
            'smv'=>$request->smv,
            'sewing_effi_per'=>$request->sewing_effi_per,
            ]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
		$totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgetcm) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully','totalcost' => $totalCost), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        /*$budgetcm=$this->budgetcm->find($id);
        $budget=$this->budget->find($budgetcm->budget_id);
        $JobQty=$this->job->totalJobQty($budget->job_id);
        $keycontrol=$this->keycontrol
        ->join('keycontrol_parameters', function($join)  {
        $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
        })
        ->where([['parameter_id','=',4]])
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$budget->budget_date])
        ->get([
        'keycontrol_parameters.value'
        ])->first();
        if(!$keycontrol->value){
            return response()->json(array('success' => false, 'message' => 'CPM Not found'), 200);
        }


        $style_gmts = collect(\DB::select("
            select budget_cms.id,style_gmts.gmt_qty,style_gmts.smv,style_gmts.sewing_effi_per 
            from budget_cms
            join style_gmts on  style_gmts.id=budget_cms.style_gmt_id
            where style_gmts.smv is not null
            and style_gmts.sewing_effi_per is not null
            and style_gmts.sewing_effi_per>0
            order by budget_cms.id 
        "));
        \DB::beginTransaction();
        try
        {
        foreach($style_gmts as $style_gmt){
            $cm_per_pcs=$style_gmt->smv*$keycontrol->value/($style_gmt->sewing_effi_per/100);
            $amount=$cm_per_pcs*$style_gmt->gmt_qty*$budget->costing_unit_id;
            //$prod_per_hour=(60*$request->no_of_man_power*($request->sewing_effi_per/100))/$request->smv;
            $bom_amount=$cm_per_pcs*$JobQty;
        
        $this->budgetcm->where([['id','=',$style_gmt->id]])->update([
            'smv'=>$style_gmt->smv,
            'sewing_effi_per'=>$style_gmt->sewing_effi_per,
            'cpm'=>$keycontrol->value,
            'cm_per_pcs'=>$cm_per_pcs,
            'amount'=>$amount,
            'bom_amount'=>$bom_amount, 
        ]);
        }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);*/
        if ($this->budgetcm->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getGmtItem(){
        $budget=$this->budget->find(request('budget_id',0));
        $job=$this->job->find($budget->job_id);
        $keycontrol=$this->keycontrol
        ->join('keycontrol_parameters', function($join)  {
        $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
        })
        ->where([['parameter_id','=',4]])
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$budget->budget_date])
        ->get([
        'keycontrol_parameters.value'
        ])->first();
        $cpm='';
        if($keycontrol){
            $cpm=$keycontrol->value;
        }

        $itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
        $stylegmtss=array();
        $rows = $this->stylegmts
        ->leftJoin('item_accounts', function($join)  {
        $join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
        })
        ->join('styles', function($join)  {
        $join->on('style_gmts.style_id', '=', 'styles.id');
        })
        ->join('itemcategories', function($join)  {
        $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
        })
        ->when(request('style_id'), function ($q) {
        return $q->where('style_id', '=', request('style_id', 0));
        })
        ->leftJoin('users', function($join){
        $join->on('users.id','=','style_gmts.created_by');
        })
        ->leftJoin('users as updated_users', function($join){
        $join->on('updated_users.id','=','style_gmts.updated_by');
        })
        ->where([['style_gmts.style_id','=',$job->style_id]])
        ->get([
        'style_gmts.*',
        'styles.style_ref',
        'item_accounts.item_description as name',
        'itemcategories.name as itemcaegory_name',
        'users.name as created_by_user',
        'updated_users.name as updated_by_user'
        ]);

        foreach($rows as $row){
        $stylegmts['id']=   $row->id;
        $stylegmts['gmtqty']=   $row->gmt_qty;
        $stylegmts['gmtcategory']=  $row->itemcaegory_name;
        $stylegmts['style']=    $row->style_ref;
        $stylegmts['style_id']= $row->style_id;
        $stylegmts['style_ref']=$row->style_ref;
        $stylegmts['itemcomplexity']=   $itemcomplexity[$row->item_complexity];
        $stylegmts['itemaccount']=  $row->name;
        $stylegmts['name']= $row->name;
        $stylegmts['sewing_effi_per']= $row->sewing_effi_per;
        $stylegmts['smv']= $row->smv;
        $stylegmts['remarks']= $row->remarks;
        $stylegmts['article']= $row->article;
        $stylegmts['no_of_man_power']= $row->no_of_man_power;
        $stylegmts['prod_per_hour']= $row->prod_per_hour;
        $stylegmts['created_by_user']= $row->created_by_user;
        $stylegmts['updated_by_user']=  $row->updated_by_user;
        $stylegmts['created_at']= date('d-M-Y h:i A',strtotime($row->created_at));
        $stylegmts['updated_at']=date('d-M-Y h:i A',strtotime($row->updated_at));
        $stylegmts['cpm']=$cpm;
        array_push($stylegmtss,$stylegmts);
        }
        echo json_encode($stylegmtss);
    }

}
