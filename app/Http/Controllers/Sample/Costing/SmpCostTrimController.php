<?php

namespace App\Http\Controllers\Sample\Costing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sample\Costing\SmpCostTrimRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Library\Template;
use App\Http\Requests\Sample\Costing\SmpCostTrimRequest;

class SmpCostTrimController extends Controller {

    private $smpcosttrim;
    private $mktcost;
    private $uom;
    private $itemaccount;
    private $itemclass;


    public function __construct(SmpCostTrimRepository $smpcosttrim,SmpCostRepository $mktcost,UomRepository $uom,ItemAccountRepository $itemaccount,ItemclassRepository $itemclass) {
        $this->smpcosttrim = $smpcosttrim;
        $this->mktcost = $mktcost;
        $this->uom = $uom;
    	$this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->middleware('auth');
        //$this->middleware('permission:view.smpcosttrims',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.smpcosttrims', ['only' => ['store']]);
        //$this->middleware('permission:edit.smpcosttrims',   ['only' => ['update']]);
        //$this->middleware('permission:delete.smpcosttrims', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

		$smpcosttrims=array();
		$rows=$this->smpcosttrim->join('itemclasses', function($join){
			$join->on('itemclasses.id', '=', 'smp_cost_trims.itemclass_id');
		})
		->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
		})
		->join('uoms', function($join){
			$join->on('uoms.id', '=', 'smp_cost_trims.uom_id');
		})
		->where([['smp_cost_trims.smp_cost_id','=',request('smp_cost_id', 0)]])
		->get([
		'smp_cost_trims.*',
		'itemclasses.name',
		'uoms.code'
		]);
		$tot=0;
  		foreach($rows as $row){
        $smpcosttrim['id']=	$row->id;
        $smpcosttrim['item_account_id']= $row->itemclass_id;
		$smpcosttrim['item_account']=	$row->name;
        $smpcosttrim['description']=	$row->description;
        $smpcosttrim['specification']=	$row->specification;
        $smpcosttrim['item_size']=	$row->item_size;
        $smpcosttrim['sup_ref']=	$row->sup_ref;
        $smpcosttrim['cons']=	$row->cons;
        //$smpcosttrim['bom_qty']=   $row->bom_qty;
        $smpcosttrim['rate']=	$row->rate;
        $smpcosttrim['amount']=	$row->amount;
        $smpcosttrim['uom']=	$row->code;
		$tot+=$row->amount;
  		   array_push($smpcosttrims,$smpcosttrim);
  		}
		$dd=array('total'=>1,'rows'=>$smpcosttrims,'footer'=>array(0=>array('id'=>'','item_account_id'=>'','item_account'=>'','specification'=>'','item_size'=>'','sup_ref'=>'','uom'=>'','cons'=>'','rate'=>'Total','amount'=>$tot)));
        echo json_encode($dd);
        //echo json_encode($smpcosttrims);
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
    public function store(SmpCostTrimRequest $request) {
        $smpcosttrim = $this->smpcosttrim->create($request->except(['id']));
		//$totalCost=$this->mktcost->totalCost($request->smp_cost_id);
        if ($smpcosttrim) {
            return response()->json(array('success' => true, 'id' => $smpcosttrim->id, 'message' => 'Save Successfully'), 200);
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
        $smpcosttrim = $this->smpcosttrim->find($id);
        $smpcosttrim->uom_name=$smpcosttrim->uom_id;
        $row ['fromData'] = $smpcosttrim;
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
    public function update(SmpCostTrimRequest $request, $id) {
        $smpcosttrim = $this->smpcosttrim->update($id, $request->except(['id']));
		//$totalCost=$this->mktcost->totalCost($request->smp_cost_id);
        if ($smpcosttrim) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->smpcosttrim->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function setuom() {
        $itemclass=$this->itemclass->find(request('itemclass_id',0));
        echo json_encode($itemclass);
    }

}
