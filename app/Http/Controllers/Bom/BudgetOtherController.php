<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetOtherRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetOtherRequest;

class BudgetOtherController extends Controller {

    private $budgetother;
    private $budget;
	private $job;

    public function __construct(BudgetOtherRepository $budgetother,BudgetRepository $budget,JobRepository $job) {
        $this->budgetother = $budgetother;
        $this->budget = $budget;
		$this->job      = $job;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetothers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetothers', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetothers',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetothers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      //$budget=array_prepend(array_pluck($this->budget->get(),'name','id'),'-Select-','');
	  $tot=0;
	  $totBom=0;
	  $othercosthead=array_prepend(config('bprs.othercosthead'),'-Select-','');
      $budgetothers=array();
	    $rows=$this->budgetother->where([['budget_id','=',request('budget_id',0)]])->get();
  		foreach($rows as $row){
         $budgetother['id']=	$row->id;
         $budgetother['cost_head_id']=	$othercosthead[$row->cost_head_id];
         $budgetother['amount']=	$row->amount;
		 $budgetother['bom_amount']=	$row->bom_amount;
		 $tot+=$row->amount;
		 $totBom+=$row->bom_amount;
  		 array_push($budgetothers,$budgetother);
  		}
		$dd=array('total'=>1,'rows'=>$budgetothers,'footer'=>array(0=>array('id'=>'','cost_head_id'=>'Total','amount'=>$tot,'bom_amount'=>$totBom)));
        echo json_encode($dd);
        //echo json_encode($budgetothers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $budget=array_prepend(array_pluck($this->budget->get(),'name','id'),'-Select-','');
        return Template::loadView('Bom.BudgetOther', ['budget'=>$budget]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetOtherRequest $request) {
		$budgetother = $this->budgetother->create($request->except(['id']));
		$totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgetother) {
            return response()->json(array('success' => true, 'id' => $budgetother->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        $budgetother = $this->budgetother->find($id);
        $row ['fromData'] = $budgetother;
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
    public function update(BudgetOtherRequest $request, $id) {
        $budgetother = $this->budgetother->update($id, $request->except(['id']));
		$totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgetother) {
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
        if ($this->budgetother->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
