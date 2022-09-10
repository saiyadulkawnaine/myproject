<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostParamRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostParamFinRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostQpriceRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbMktCostParamFinRequest;

class SoEmbMktCostParamFinController extends Controller {

    private $soaopmktcost;
    private $soaopmktcostparam;
    private $soaopmktcostparamfin;
    private $productionprocess;
    private $soaopmktcostqprice;

    public function __construct(
        SoEmbMktCostRepository $soaopmktcost,
        SoEmbMktCostParamRepository $soaopmktcostparam, 
        SoEmbMktCostParamFinRepository $soaopmktcostparamfin,
        SoEmbMktCostQpriceRepository $soaopmktcostqprice,
        ProductionProcessRepository $productionprocess
    ) {
        $this->soaopmktcost = $soaopmktcost;
        $this->soaopmktcostparam = $soaopmktcostparam;
        $this->soaopmktcostparamfin = $soaopmktcostparamfin;
        $this->soaopmktcostqprice = $soaopmktcostqprice;
        $this->productionprocess = $productionprocess;

        $this->middleware('auth');

        //$this->middleware('permission:view.soaopmktcostparamfins',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.soaopmktcostparamfins', ['only' => ['store']]);
        //$this->middleware('permission:edit.soaopmktcostparamfins',   ['only' => ['update']]);
        //$this->middleware('permission:delete.soaopmktcostparamfins', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'','');
        $soaopmktcostparamfins=array();
        $rows = $this->soaopmktcostparamfin
        ->where([['so_aop_mkt_cost_param_id','=',request('so_aop_mkt_cost_param_id',0)]])
        ->orderBy('so_aop_mkt_cost_param_fins.id','desc')
        ->get();

        foreach($rows as $row){
            $soaopmktcostparamfin['id']=$row->id;
            $soaopmktcostparamfin['amount']=$row->amount;
            $soaopmktcostparamfin['sort_id']=$row->sort_id;
            $soaopmktcostparamfin['productionprocess']=$productionprocess[$row->production_process_id];
            array_push($soaopmktcostparamfins,$soaopmktcostparamfin);
        }

        echo json_encode($soaopmktcostparamfins);
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
    public function store(SoEmbMktCostParamFinRequest $request) {
        $soaopmktcostparam=$this->soaopmktcostparam->find($request->so_aop_mkt_cost_param_id);
        $approved=$this->soaopmktcostqprice
        ->where([['so_aop_mkt_cost_id','=', $soaopmktcostparam->so_aop_mkt_cost_id]])
        ->get(['first_approved_at'])
        ->first();

        if ($approved) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }

        $soaopmktcostparamfin = $this->soaopmktcostparamfin->create($request->except(['id']));
        if($soaopmktcostparamfin){
        return response()->json(array('success' =>true ,'id'=>$soaopmktcostparamfin->id,'message'=>'Saved Successfully'),200);
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

        $soaopmktcostparamfin = $this->soaopmktcostparamfin->find($id);
        $row ['fromData'] = $soaopmktcostparamfin;
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

    public function update(SoEmbMktCostParamFinRequest $request, $id) {
        $soaopmktcostparam=$this->soaopmktcostparam->find($request->so_aop_mkt_cost_param_id);
        $approved=$this->soaopmktcostqprice
        ->where([['so_aop_mkt_cost_id','=', $soaopmktcostparam->so_aop_mkt_cost_id]])
        ->get(['first_approved_at'])
        ->first();

        if ($approved) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }

        $soaopmktcostparamfin = $this->soaopmktcostparamfin->update($id, $request->except(['id']));

        if($soaopmktcostparamfin){
        return response()->json(array('success' =>true ,'id'=>$id, 'message'=>'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        return response()->json(array('success'=>false,'message'=>'Deleted not Successfully'),200);
        if($this->soaopmktcostparamfin->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


}