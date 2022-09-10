<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetCommissionRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetCommissionRequest;

class BudgetCommissionController extends Controller {

    private $budgetcommission ;
    private $budget;

    public function __construct(BudgetCommissionRepository $budgetcommission,BudgetRepository $budget) {
        $this->budgetcommission  = $budgetcommission ;
        $this->budget = $budget;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetcommissions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetcommissions', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetcommissions',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetcommissions', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
	  $commissionfor=array_prepend([1=>"Local Agent",2=>"Foreign Agent"],'-Select-','');
      $budgetcommissions=array();
	    $rows=$this->budgetcommission
		->join('budgets',function($join){
			$join->on('budgets.id','=','budget_commissions.budget_id');
		})
		->where([['budget_id','=',request('budget_id',0)]])
		->get([
		'budget_commissions.*',
		'budgets.costing_unit_id'
		]);
		$tot=0;
		$tot_pcs=0;
  		foreach($rows as $row){
        $budgetcommission ['id']=	$row->id;
        $budgetcommission ['for_id']=	$commissionfor[$row->for_id];
        $budgetcommission ['rate']=	$row->rate;
        $budgetcommission ['amount']=	$row->amount;
		$tot+=$row->amount;
		$tot_pcs+=number_format($row->amount/$row->costing_unit_id,4);
  		array_push($budgetcommissions ,$budgetcommission );
  		}
		$dd=array('total'=>1,'rows'=>$budgetcommissions,'footer'=>array(0=>array('id'=>'','for_id'=>'','rate'=>'Total','amount'=>$tot,'rate'=>'','amount_pcs'=>$tot_pcs)));
        echo json_encode($dd);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $budget=array_prepend(array_pluck($this->budget->get(),'name','id'),'-Select-','');
        return Template::loadView('Bom.BudgetCommission', ['budget'=>$budget]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetCommissionRequest $request) {
        $budgetcommission  = $this->budgetcommission ->create($request->except(['id']));
		$totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgetcommission ) {
            return response()->json(array('success' => true, 'id' => $budgetcommission ->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
    public function edit($id) {
        $budgetcommission  = $this->budgetcommission ->find($id);
        $row ['fromData'] = $budgetcommission ;
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
    public function update(BudgetCommissionRequest $request, $id) {
        $budgetcommission  = $this->budgetcommission ->update($id, $request->except(['id']));
		$totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgetcommission ) {
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
        if ($this->budgetcommission ->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
