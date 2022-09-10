<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabFinRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpriceRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingMktCostFabFinRequest;

class SoDyeingMktCostFabFinController extends Controller {

    private $sodyeingmktcost;
    private $sodyeingmktcostfab;
    private $sodyeingmktcostfabfin;
    private $sodyeingmktcostqprice;
    private $productionprocess;

    public function __construct(
        SoDyeingMktCostRepository $sodyeingmktcost,
        SoDyeingMktCostFabRepository $sodyeingmktcostfab, 
        SoDyeingMktCostFabFinRepository $sodyeingmktcostfabfin,
        SoDyeingMktCostQpriceRepository $sodyeingmktcostqprice,
        ProductionProcessRepository $productionprocess
    ) {
        $this->sodyeingmktcost = $sodyeingmktcost;
        $this->sodyeingmktcostfab = $sodyeingmktcostfab;
        $this->sodyeingmktcostfabfin = $sodyeingmktcostfabfin;
        $this->sodyeingmktcostqprice = $sodyeingmktcostqprice;
        $this->productionprocess = $productionprocess;

        $this->middleware('auth');

        //$this->middleware('permission:view.sodyeingmktcostfabfins',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingmktcostfabfins', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingmktcostfabfins',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingmktcostfabfins', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'','');
        $sodyeingmktcostfabfins=array();
        $rows = $this->sodyeingmktcostfabfin
        ->where([['so_dyeing_mkt_cost_fab_id','=',request('so_dyeing_mkt_cost_fab_id',0)]])
        ->orderBy('so_dyeing_mkt_cost_fab_fins.id','desc')
        ->get();

        foreach($rows as $row){
            $sodyeingmktcostfab['id']=$row->id;
            $sodyeingmktcostfab['amount']=$row->amount;
            $sodyeingmktcostfab['sort_id']=$row->sort_id;
            $sodyeingmktcostfab['productionprocess']=$productionprocess[$row->production_process_id];
            array_push($sodyeingmktcostfabfins,$sodyeingmktcostfab);
        }

        echo json_encode($sodyeingmktcostfabfins);
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
    public function store(SoDyeingMktCostFabFinRequest $request) {
        $approved=$this->sodyeingmktcostfab
        ->join('so_dyeing_mkt_costs',function($join){
            $join->on('so_dyeing_mkt_cost_fabs.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->join('so_dyeing_mkt_cost_qprices',function($join){
            $join->on('so_dyeing_mkt_cost_qprices.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->where([['so_dyeing_mkt_cost_fabs.id','=', $request->so_dyeing_mkt_cost_fab_id]])
        ->get(['so_dyeing_mkt_cost_qprices.final_approved_at'])->first();

        if ($approved->final_approved_at) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }

        $sodyeingmktcostfabfin = $this->sodyeingmktcostfabfin->create($request->except(['id']));
        if($sodyeingmktcostfabfin){
        return response()->json(array('success' =>true ,'id'=>$sodyeingmktcostfabfin->id,'message'=>'Saved Successfully'),200);
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

        $sodyeingmktcostfabfin = $this->sodyeingmktcostfabfin->find($id);
        $row ['fromData'] = $sodyeingmktcostfabfin;
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

    public function update(SoDyeingMktCostFabFinRequest $request, $id) {
        $approved=$this->sodyeingmktcostfab
        ->join('so_dyeing_mkt_costs',function($join){
            $join->on('so_dyeing_mkt_cost_fabs.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->join('so_dyeing_mkt_cost_qprices',function($join){
            $join->on('so_dyeing_mkt_cost_qprices.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->where([['so_dyeing_mkt_cost_fabs.id','=', $request->so_dyeing_mkt_cost_fab_id]])
        ->get(['so_dyeing_mkt_cost_qprices.final_approved_at'])->first();

        if ($approved->final_approved_at) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }
        
      $sodyeingmktcostfabfin = $this->sodyeingmktcostfabfin->update($id, $request->except(['id']));

        if($sodyeingmktcostfabfin){
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
        if($this->sodyeingmktcostfabfin->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


}