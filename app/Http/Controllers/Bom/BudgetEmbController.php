<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetEmbRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Marketing\StyleEmbelishmentRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetEmbRequest;

class BudgetEmbController extends Controller {

    private $budgetemb;
    private $budget;
    private $embelishmenttype;
    private $embelishment;
    private $company;
    private $styleembelishment;
    private $keycontrol;

    public function __construct(BudgetEmbRepository $budgetemb,BudgetRepository $budget,EmbelishmentTypeRepository $embelishmenttype,EmbelishmentRepository $embelishment,CompanyRepository $company,StyleEmbelishmentRepository $styleembelishment,ProductionProcessRepository $productionprocess,KeycontrolRepository $keycontrol) {
        $this->budgetemb = $budgetemb;
        $this->budget = $budget;
        $this->embelishmenttype = $embelishmenttype;
        $this->embelishment = $embelishment;
        $this->company = $company;
        $this->styleembelishment = $styleembelishment;
        $this->productionprocess = $productionprocess;
        $this->keycontrol = $keycontrol;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetembs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetembs', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetembs',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetembs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $budget=array_prepend(array_pluck($this->budget->get(),'name','id'),'-Select-','');
      $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'-Select-','');
      $embelishmenttype=array_prepend(array_pluck($this->$embelishmenttype->get(),'name','id'),'-Select-','');
      $budgetembs=array();
	    $rows=$this->budgetemb
		->join('embelishments',function($join){
			$join->on('embelishments.id','=','budget_embs.embelishment_id');
			})
		->join('embelishment_types',function($join){
			$join->on('embelishment_types.id','=','budget_embs.embelishment_type_id');
		})
		->get([
		'budget_embs.*',
		'embelishments.name as emb_name',
		'embelishment_types.name as emb_type',
		]);
  		foreach($rows as $row){
        $budgetemb['id']=	$row->id;
        $budgetemb['cons']=	$row->cons;
        $budgetemb['rate']=	$row->rate;
        $budgetemb['amount']=	$row->amount;
        $budgetemb['budget']=	$budget[$row->budget_id];
        $budgetemb['embelishment']=	$uom[$row->embelishment_id];
        $budgetemb['embelishmenttype']=	$uom[$row->embelishment_type_id];
  		   array_push($budgetembs,$budgetemb);
  		}
        echo json_encode($budgetembs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
	$budget = $this->budget->find(request('budget_id',0));
    $company=array_prepend(array_pluck($this->company->where([['nature_id','=',15]])->get(),'name','id'),'-Select-','');
     $emb=$this->budget
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_embelishments',function($join){
		$join->on('style_embelishments.style_id','=','budgets.style_id');
		})
		->join('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->join('embelishments',function($join){
		$join->on('embelishments.id','=','style_embelishments.embelishment_id');
		})
		->join('embelishment_types',function($join){
		$join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
		})

		->leftJoin('budget_embs',function($join){
		$join->on('budget_embs.budget_id','=','budgets.id');
		$join->on('budget_embs.style_embelishment_id','=','style_embelishments.id');
		})
		->where([['budgets.id','=',request('budget_id',0)]])
		->get([
		'budgets.id as budget_id',
		'budgets.costing_unit_id',
		'style_embelishments.id as style_embelishment_id',
		'embelishments.name as embelishment_name',
		'embelishment_types.name as embelishment_type',
		'item_accounts.item_description',
		'budget_embs.id',
        'budget_embs.company_id',
		'budget_embs.cons',
		'budget_embs.rate',
		'budget_embs.amount',
        'budget_embs.overhead_rate',
        'budget_embs.overhead_amount'
		]);
		$dropdown['emb'] = "'".Template::loadView('Bom.BudgetEmbMatrix',['embs'=>$emb,'costing_unit_id'=>$budget->costing_unit_id,'company'=>$company])."'";
		$row ['dropDown'] = $dropdown;
		echo json_encode($row);
        //return Template::loadView('Bom.BudgetEmb', ['budget'=>$budget,'embelishment'=>$embelishment,'embelishmenttype'=>$embelishmenttype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetEmbRequest $request) {
        $budgetId=0;
        foreach($request->budget_id as $index=>$budget_id)
        {
            $styleembelishment=$this->styleembelishment->find($request->style_embelishment_id[$index]);
            $embelishment=$this->embelishment->find($styleembelishment->embelishment_id);
            $productionprocess=$this->productionprocess->find($embelishment->production_process_id);

            $budget=$this->budget->find($budget_id);
            $budgetembdata=$this->budgetemb->find($request->budget_emb_id[$index]);
            $overheadRate=0;
            if($productionprocess->production_area_id==45)
            {
                $keycontrol=$this->keycontrol
                ->join('keycontrol_parameters', function($join)  {
                $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
                })
                ->where([['parameter_id','=',10]])
                ->where([['keycontrols.company_id','=',$request->company_id[$index]]])
                ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$budget->budget_date])
                ->get([
                'keycontrol_parameters.value'
                ])->first();
                $overheadRate=$keycontrol->value;
            }
            /*if($request->budget_emb_id[$index])
            {
                $overheadRate=$budgetembdata->overhead_rate;
            }
            else
            {
                
            }*/
            $request->request->add(['overhead_rate' => $overheadRate]);
            $budgetId=$budget_id;
            $budgetemb = $this->budgetemb->updateOrCreate(
            ['budget_id' => $budget_id,'style_embelishment_id' => $request->style_embelishment_id[$index]],
            ['cons' => $request->cons[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index],'company_id' =>$request->company_id[$index],'overhead_rate' =>$overheadRate]
            );
        }
        $totalCost=$this->budget->totalCost($budgetId);
        return response()->json(array('success' => true, 'id' => $budgetemb->id, 'budget_id' => $budgetId,'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        $budgetemb = $this->budgetemb->find($id);
        $row ['fromData'] = $budgetemb;
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
    public function update(BudgetEmbRequest $request, $id) {
        $budgetemb = $this->budgetemb->update($id, $request->except(['id']));
        if ($budgetemb) {
            return response()->json(array('success' => true, 'id' => $id, 'budget_id' => $budgetId, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->budgetemb->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
