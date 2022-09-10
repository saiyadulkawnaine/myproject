<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostTrimRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Library\Template;
use App\Http\Requests\MktCostTrimRequest;

class MktCostTrimController extends Controller {

    private $mktcosttrim;
    private $mktcost;
    private $uom;
    private $itemaccount;
    private $itemclass;


    public function __construct(MktCostTrimRepository $mktcosttrim,MktCostRepository $mktcost,UomRepository $uom,ItemAccountRepository $itemaccount,ItemclassRepository $itemclass) {
        $this->mktcosttrim = $mktcosttrim;
        $this->mktcost = $mktcost;
        $this->uom = $uom;
    	$this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcosttrims',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcosttrims', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcosttrims',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcosttrims', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

		$mktcosttrims=array();
		$rows=$this->mktcosttrim->join('itemclasses', function($join){
			$join->on('itemclasses.id', '=', 'mkt_cost_trims.itemclass_id');
		})
		->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
		})
		->join('uoms', function($join){
			$join->on('uoms.id', '=', 'mkt_cost_trims.uom_id');
		})
		->where([['mkt_cost_trims.mkt_cost_id','=',request('mkt_cost_id', 0)]])
		->get([
		'mkt_cost_trims.*',
		'itemclasses.name',
		'uoms.code'
		]);
		$tot=0;
  		foreach($rows as $row){
        $mktcosttrim['id']=	$row->id;
        $mktcosttrim['item_account_id']= $row->itemclass_id;
		$mktcosttrim['item_account']=	$row->name;
        $mktcosttrim['description']=	$row->description;
        $mktcosttrim['specification']=	$row->specification;
        $mktcosttrim['item_size']=	$row->item_size;
        $mktcosttrim['sup_ref']=	$row->sup_ref;
        $mktcosttrim['cons']=	$row->cons;
        $mktcosttrim['rate']=	$row->rate;
        $mktcosttrim['amount']=	$row->amount;
        $mktcosttrim['uom']=	$row->code;
		$tot+=$row->amount;
  		   array_push($mktcosttrims,$mktcosttrim);
  		}
		$dd=array('total'=>1,'rows'=>$mktcosttrims,'footer'=>array(0=>array('id'=>'','item_account_id'=>'','item_account'=>'','specification'=>'','item_size'=>'','sup_ref'=>'','uom'=>'','cons'=>'','rate'=>'Total','amount'=>$tot)));
        echo json_encode($dd);
        //echo json_encode($mktcosttrims);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $mktcost=array_prepend(array_pluck($this->mktcost->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
        return Template::loadView('Marketing.MktCostTrim', ['mktcost'=>$mktcost,'uom'=>$uom]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostTrimRequest $request) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcosttrim = $this->mktcosttrim->create($request->except(['id']));
		$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
        if ($mktcosttrim) {
            return response()->json(array('success' => true, 'id' => $mktcosttrim->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        $mktcosttrim = $this->mktcosttrim->find($id);
        $mktcosttrim->uom_name=$mktcosttrim->uom_id;
        $row ['fromData'] = $mktcosttrim;
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
    public function update(MktCostTrimRequest $request, $id) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcosttrim = $this->mktcosttrim->update($id, $request->except(['id']));
		$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
        if ($mktcosttrim) {
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
        $mktcosttrim=$this->mktcosttrim->find($id);
        $approved=$this->mktcost->find($mktcosttrim->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        if ($this->mktcosttrim->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function setuom() {
        $itemclass=$this->itemclass->find(request('itemclass_id',0));
        echo json_encode($itemclass);
    }

}
