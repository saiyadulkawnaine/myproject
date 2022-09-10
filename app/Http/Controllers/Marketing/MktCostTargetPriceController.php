<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostTargetPriceRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Library\Template;
use App\Http\Requests\MktCostTargetPriceRequest;

class MktCostTargetPriceController extends Controller {

    private $mktcosttargetprice;
    private $mktcost;

    public function __construct(MktCostTargetPriceRepository $mktcosttargetprice,MktCostRepository $mktcost) {
        $this->mktcosttargetprice = $mktcosttargetprice;
        $this->mktcost = $mktcost;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcosttargetprices',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcosttargetprices', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcosttargetprices',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcosttargetprices', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$tot=0;
        $mktcosttargetprices=array();
	    $rows=$this->mktcosttargetprice->where([['mkt_cost_id','=',request('mkt_cost_id',0)]])->get();
  		foreach($rows as $row){
        $mktcosttargetprice['id']=	$row->id;
        $mktcosttargetprice['price_date']=	$row->price_date;
        $mktcosttargetprice['target_price']=	$row->target_price;
		$tot+=$row->amount;
  		   array_push($mktcosttargetprices,$mktcosttargetprice);
  		}
		$dd=array('total'=>1,'rows'=>$mktcosttargetprices,'footer'=>array(0=>array('id'=>'','price_date'=>'Total','target_price'=>$tot)));
        echo json_encode($dd);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return Template::loadView('Marketing.mktcosttargetprice', ['mktcost'=>$mktcost]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostTargetPriceRequest $request) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
		$mktcosttargetprice = $this->mktcosttargetprice->updateOrCreate(
				['mkt_cost_id' => $request->mkt_cost_id],['price_date' => $request->price_date,'target_price' => $request->target_price]
				);
		$totalPriceBeforeCommission=$this->mktcost->totalPriceBeforeCommission($request->mkt_cost_id);
        if ($mktcosttargetprice) {
            return response()->json(array('success' => true, 'id' => $mktcosttargetprice->id, 'message' => 'Save Successfully','price_before_commission' => $totalPriceBeforeCommission), 200);
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
        $mktcosttargetprice = $this->mktcosttargetprice->find($id);
        $row ['fromData'] = $mktcosttargetprice;
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
    public function update(MktCostTargetPriceRequest $request, $id) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcosttargetprice = $this->mktcosttargetprice->update($id, $request->except(['id']));
		$totalPriceBeforeCommission=$this->mktcost->totalPriceBeforeCommission($request->mkt_cost_id);
        if ($mktcosttargetprice) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully','price_before_commission' => $totalPriceBeforeCommission), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $mktcosttargetprice=$this->mktcosttargetprice->find($id);
        $approved=$this->mktcost->find($mktcosttargetprice->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        if ($this->mktcosttargetprice->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
