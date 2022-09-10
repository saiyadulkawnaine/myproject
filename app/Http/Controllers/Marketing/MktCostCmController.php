<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostCmRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;

use App\Library\Template;
use App\Http\Requests\MktCostCmRequest;

class MktCostCmController extends Controller {

    private $mktcostcm;
    private $mktcost;
	private $keycontrol;
    private $stylegmts;

    public function __construct(
        MktCostCmRepository $mktcostcm,
        MktCostRepository $mktcost,
        KeycontrolRepository $keycontrol,
        StyleGmtsRepository $stylegmts
    ) {
        $this->mktcostcm = $mktcostcm;
        $this->mktcost = $mktcost;
		$this->keycontrol = $keycontrol;
        $this->stylegmts = $stylegmts;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcostcms',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcostcms', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcostcms',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcostcms', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
        $rows=$this->mktcostcm
        ->join('style_gmts', function($join)  {
        $join->on('style_gmts.id', '=', 'mkt_cost_cms.style_gmt_id');
        })
        ->join('item_accounts', function($join)  {
        $join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
        })
        ->where([['mkt_cost_id','=',request('mkt_cost_id',0)]])
        ->get([
            'mkt_cost_cms.*',
            'style_gmts.gmt_qty',
            'item_accounts.item_description as name',
        ]);
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostCmRequest $request) {
		$mktcost=$this->mktcost->find($request->mkt_cost_id);
        if($mktcost->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
		$keycontrol=$this->keycontrol
		->join('keycontrol_parameters', function($join)  {
		$join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
		})
		->where([['parameter_id','=',4]])
		->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$mktcost->quot_date])
		->get([
		'keycontrol_parameters.value'
		])->first();
		if(!$keycontrol->value){
			return response()->json(array('success' => false, 'message' => 'CPM Not found'), 200);
		}

		
		
		$smvrows=$this->mktcost
	    ->join('style_gmts',function($join){
         $join->on('style_gmts.style_id','=','mkt_costs.style_id');
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
        $amount=$cm_per_pcs*$smvrows->gmt_qty*$mktcost->costing_unit_id;
        $prod_per_hour=(60*$request->no_of_man_power*($request->sewing_effi_per/100))/$request->smv;

		
		\DB::beginTransaction();
        try
        {
    		$mktcostcm = $this->mktcostcm->updateOrCreate([
                'mkt_cost_id'=>$request->mkt_cost_id,
                'style_gmt_id'=>$request->style_gmt_id,
            ],[
        		'method_id'=>1,
        		'amount'=>$amount,
        		'smv'=>$request->smv,
                'sewing_effi_per'=>$request->sewing_effi_per,
                'cpm'=>$keycontrol->value,
                'cm_per_pcs'=>$cm_per_pcs,
                'no_of_man_power'=>$request->no_of_man_power,
                'prod_per_hour'=>$prod_per_hour,
    		]);
            /*$this->stylegmts->update($request->style_gmt_id, [
                'smv'=>$request->smv,
                'sewing_effi_per'=>$request->sewing_effi_per,
            ]);*/
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
		
		$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
		$priceBfrCommission=$this->mktcost->totalPriceBeforeCommission($request->mkt_cost_id);
        if ($mktcostcm) {
            return response()->json(array('success' => true, 'id' => $mktcostcm->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        //$mktcostcm = $this->mktcostcm->find($id);
        $mktcostcm=$this->mktcostcm
        ->join('style_gmts', function($join)  {
        $join->on('style_gmts.id', '=', 'mkt_cost_cms.style_gmt_id');
        })
        ->join('item_accounts', function($join)  {
        $join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
        })
        ->where([['mkt_cost_cms.id','=',$id]])
        ->get([
            'mkt_cost_cms.*',
            'style_gmts.gmt_qty',
            'item_accounts.item_description as style_gmt_name',
        ])
        ->first();
        $row ['fromData'] = $mktcostcm;
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
    public function update(MktCostCmRequest $request, $id) {
		
		$mktcost=$this->mktcost->find($request->mkt_cost_id);
        if($mktcost->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }

		$keycontrol=$this->keycontrol
        ->join('keycontrol_parameters', function($join)  {
        $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
        })
        ->where([['parameter_id','=',4]])
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$mktcost->quot_date])
        ->get([
        'keycontrol_parameters.value'
        ])->first();
        if(!$keycontrol->value){
            return response()->json(array('success' => false, 'message' => 'CPM Not found'), 200);
        }

        
        
        $smvrows=$this->mktcost
        ->join('style_gmts',function($join){
         $join->on('style_gmts.style_id','=','mkt_costs.style_id');
        })
        ->where([['style_gmts.id','=',$request->style_gmt_id]])
        ->get([
        'style_gmts.smv',
        'style_gmts.gmt_qty',
        'style_gmts.sewing_effi_per'
        ])
        ->first();
        
        if(!$smvrows){
            return response()->json(array('success' => false, 'message' => 'GMT Item Ratio not found'), 200);
        }

        $cm_per_pcs=$request->smv*$keycontrol->value/($request->sewing_effi_per/100);
        $amount=$cm_per_pcs*$smvrows->gmt_qty*$mktcost->costing_unit_id;
        $prod_per_hour=(60*$request->no_of_man_power*($request->sewing_effi_per/100))/$request->smv;

        \DB::beginTransaction();
        try
        {
            $mktcostcm = $this->mktcostcm->update($id, [
            	'style_gmt_id'=>$request->style_gmt_id,
                'amount'=>$amount,
                'smv'=>$request->smv,
                'sewing_effi_per'=>$request->sewing_effi_per,
                'cpm'=>$keycontrol->value,
                'cm_per_pcs'=>$cm_per_pcs,
                'no_of_man_power'=>$request->no_of_man_power,
                'prod_per_hour'=>$prod_per_hour,
            ]);
            /*$this->stylegmts->update($request->style_gmt_id, [
                'smv'=>$request->smv,
                'sewing_effi_per'=>$request->sewing_effi_per,
            ]);*/
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        

		$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
        if ($mktcostcm) {
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
        $mktcostcm=$this->mktcostcm->find($id);
        $mktcost=$this->mktcost->find($mktcostcm->mkt_cost_id);
        if($mktcost->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        /*$style_gmts = collect(\DB::select("
        select mkt_costs.id as  mkt_cost_id,min(style_gmts.id) as style_gmt_id, count(style_gmts.id) 
        from mkt_costs
        join style_gmts on  mkt_costs.style_id=style_gmts.style_id
        group by mkt_costs.id having count(style_gmts.id)>2
        "));
        \DB::beginTransaction();
        try
        {
        foreach($style_gmts as $style_gmt){
        
        $this->mktcostcm->where([['mkt_cost_id','=',$style_gmt->mkt_cost_id]])->update(['style_gmt_id'=>$style_gmt->style_gmt_id]);
        }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);*/

        if ($this->mktcostcm->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getGmtItem(){
        $mktcost=$this->mktcost->find(request('mkt_cost_id',0));
        $keycontrol=$this->keycontrol
        ->join('keycontrol_parameters', function($join)  {
        $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
        })
        ->where([['parameter_id','=',4]])
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$mktcost->quot_date])
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
        ->where([['style_gmts.style_id','=',$mktcost->style_id]])
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
