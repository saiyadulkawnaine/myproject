<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetCommercialRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetCommercialRequest;

class BudgetCommercialController extends Controller {

    private $budgetcommercial;
    private $budget;

    public function __construct(BudgetCommercialRepository $budgetcommercial,BudgetRepository $budget) {
        $this->budgetcommercial = $budgetcommercial;
        $this->budget = $budget;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetcommercials',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetcommercials', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetcommercials',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetcommercials', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $budget=array_prepend(array_pluck($this->budget->get(),'name','id'),'-Select-','');
      $budgetcommercials=array();
	    $rows=$this->budgetcommercial->where([['budget_id','=',request('budget_id',0)]])->get();
  		foreach($rows as $row){
        $budgetcommercial['id']=	$row->id;
        $budgetcommercial['rate']=	$row->rate;
        $budgetcommercial['amount']=	$row->amount;
  		array_push($budgetcommercials,$budgetcommercial);
  		}
        echo json_encode($budgetcommercials);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $budget=array_prepend(array_pluck($this->budget->get(),'name','id'),'-Select-','');
        return Template::loadView('Bom.BudgetCommercial', ['budget'=>$budget]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetCommercialRequest $request) {
		$cost=$this->budget->totalFabricCost($request->budget_id)+$this->budget->totalYarnCost($request->budget_id)+$this->budget->totalFabricProdCost($request->budget_id)+$this->budget->totalTrimCost($request->budget_id)+$this->budget->totalEmbCost($request->budget_id)+$this->budget->totalYarnDyeingCost($request->budget_id);
		$amount=($request->rate/100)*$cost;
		//$request->amount=$amount;
	    $budgetcommercial = $this->budgetcommercial->create(["budget_id"=>$request->budget_id,"rate"=>$request->rate,"amount"=>$amount]);
		$totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgetcommercial) {
            return response()->json(array('success' => true, 'id' => $budgetcommercial->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        $budgetcommercial = $this->budgetcommercial->find($id);
        $row ['fromData'] = $budgetcommercial;
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
    public function update(BudgetCommercialRequest $request, $id) {
		$cost=$this->budget->totalFabricCost($request->budget_id)+$this->budget->totalYarnCost($request->budget_id)+$this->budget->totalFabricProdCost($request->budget_id)+$this->budget->totalTrimCost($request->budget_id)+$this->budget->totalEmbCost($request->budget_id)+$this->budget->totalYarnDyeingCost($request->budget_id);
		$amount=($request->rate/100)*$cost;
		$request->amount=$amount;
        $budgetcommercial = $this->budgetcommercial->update($id, ["budget_id"=>$request->budget_id,"rate"=>$request->rate,"amount"=>$amount]);
		$totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgetcommercial) {
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
        if ($this->budgetcommercial->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }



}
