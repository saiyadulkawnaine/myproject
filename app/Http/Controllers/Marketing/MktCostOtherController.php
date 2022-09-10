<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostOtherRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Library\Template;
use App\Http\Requests\MktCostOtherRequest;

class MktCostOtherController extends Controller {

    private $mktcostother;
    private $mktcost;

    public function __construct(MktCostOtherRepository $mktcostother,MktCostRepository $mktcost) {
        $this->mktcostother = $mktcostother;
        $this->mktcost = $mktcost;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcostothers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcostothers', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcostothers',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcostothers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      //$mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
	  $tot=0;
	  $othercosthead=array_prepend(config('bprs.othercosthead'),'-Select-','');
      $mktcostothers=array();
	    $rows=$this->mktcostother->where([['mkt_cost_id','=',request('mkt_cost_id',0)]])->get();
  		foreach($rows as $row){
         $mktcostother['id']=	$row->id;
         $mktcostother['cost_head_id']=	$othercosthead[$row->cost_head_id];
         $mktcostother['amount']=	$row->amount;
		 $tot+=$row->amount;
  		 array_push($mktcostothers,$mktcostother);
  		}
		$dd=array('total'=>1,'rows'=>$mktcostothers,'footer'=>array(0=>array('id'=>'','cost_head_id'=>'Total','amount'=>$tot)));
        echo json_encode($dd);
        //echo json_encode($mktcostothers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
        return Template::loadView('Marketing.MktCostOther', ['mktcost'=>$mktcost]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostOtherRequest $request) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
		$mktcostother = $this->mktcostother->create($request->except(['id']));
		$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
        if ($mktcostother) {
            return response()->json(array('success' => true, 'id' => $mktcostother->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        $mktcostother = $this->mktcostother->find($id);
        $row ['fromData'] = $mktcostother;
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
    public function update(MktCostOtherRequest $request, $id) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcostother = $this->mktcostother->update($id, $request->except(['id']));
		$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
        if ($mktcostother) {
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
        $mktcostother=$this->mktcostother->find($id);
        $approved=$this->mktcost->find($mktcostother->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        if ($this->mktcostother->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
