<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetTrimRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetTrimRequest;

class BudgetTrimController extends Controller {

    private $budgettrim;
    private $budget;
    private $uom;
	private $itemaccount;
    private $itemclass;


    public function __construct(BudgetTrimRepository $budgettrim,BudgetRepository $budget,UomRepository $uom,ItemAccountRepository $itemaccount,ItemclassRepository $itemclass) {
        $this->budgettrim = $budgettrim;
        $this->budget = $budget;
        $this->uom = $uom;
		$this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->middleware('auth');
        $this->middleware('permission:view.budgettrims',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgettrims', ['only' => ['store']]);
        $this->middleware('permission:edit.budgettrims',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgettrims', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

		$budgettrims=array();
		$rows=$this->budgettrim->join('itemclasses', function($join){
			$join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
		})
		->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
		})
		->join('uoms', function($join){
			$join->on('uoms.id', '=', 'budget_trims.uom_id');
		})
        ->where([['budget_trims.budget_id','=',request('budget_id', 0)]])
        ->orderBy('budget_trims.id','desc')
		->get([
            'budget_trims.*',
            'itemclasses.name',
            'uoms.code'
		]);
		$tot=0;
  		foreach($rows as $row){
        $budgettrim['id']=	$row->id;
		$budgettrim['budget_id']=	$row->budget_id;
        $budgettrim['item_account_id']= $row->itemclass_id;
		$budgettrim['item_account']=	$row->name;
        $budgettrim['description']=	$row->description;
        //$budgettrim['specification']=	$row->specification;
        //$budgettrim['item_size']=	$row->item_size;
        $budgettrim['sup_ref']=	$row->sup_ref;
        $budgettrim['cons']=	$row->cons;
        $budgettrim['rate']=	$row->rate;
        $budgettrim['amount']=	$row->amount;
        $budgettrim['uom']=	$row->code;
		$tot+=$row->amount;
  		   array_push($budgettrims,$budgettrim);
  		}
		$dd=array('total'=>1,'rows'=>$budgettrims,'footer'=>array(0=>array('id'=>'','item_account_id'=>'','item_account'=>'','specification'=>'','item_size'=>'','sup_ref'=>'','uom'=>'','cons'=>'','rate'=>'Total','amount'=>$tot)));
        echo json_encode($dd);
        //echo json_encode($budgettrims);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $budget=array_prepend(array_pluck($this->budget->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
        return Template::loadView('Bom.BudgetTrim', ['budget'=>$budget,'uom'=>$uom]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetTrimRequest $request) {
        $budgettrim = $this->budgettrim->create($request->except(['id']));
		$totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgettrim) {
            return response()->json(array('success' => true, 'id' => $budgettrim->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        $budgettrim = $this->budgettrim->find($id);
        $budgettrim->uom_name=$budgettrim->uom_id;
        $row ['fromData'] = $budgettrim;
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
    public function update(BudgetTrimRequest $request, $id) {
        $budgettrim = $this->budgettrim->update($id, $request->except(['id']));
		$totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgettrim) {
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
        if ($this->budgettrim->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function setuom() {
        $itemclass=$this->itemclass->find(request('itemclass_id',0));
        echo json_encode($itemclass);
    }

}
