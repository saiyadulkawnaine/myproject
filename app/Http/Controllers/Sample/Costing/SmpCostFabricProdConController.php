<?php

namespace App\Http\Controllers\Sample\Costing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricProdConRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricProdRepository;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Sample\Costing\SmpCostFabricProdConRequest;

class SmpCostFabricProdConController extends Controller {

    private $smpcostfabricprodcon;
    private $smpcostfabricprod;
	private $smpcost;

    public function __construct(
        SmpCostFabricProdConRepository $smpcostfabricprodcon,
        SmpCostFabricProdRepository $smpcostfabricprod,
        SmpCostRepository $smpcost
    ) {
        $this->smpcostfabricprodcon = $smpcostfabricprodcon;
        $this->smpcostfabricprod = $smpcostfabricprod;
    	$this->smpcost = $smpcost;
        $this->middleware('auth');
        //$this->middleware('permission:view.budgetfabricprodcons',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.budgetfabricprodcons', ['only' => ['store']]);
        //$this->middleware('permission:edit.budgetfabricprodcons',   ['only' => ['update']]);
        //$this->middleware('permission:delete.budgetfabricprodcons', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $fabric=$this->smpcostfabricprod
        ->selectRaw('
          smp_cost_fabrics.smp_cost_id,
          smp_cost_fabrics.id as smp_cost_fabric_id,
          smp_cost_fabric_prods.id as smp_cost_fabric_prod_id,
          smp_cost_fabric_cons.fabric_color as fabric_color_id,
          fabric_colors.name as fabric_color,
          sum(smp_cost_fabric_cons.grey_fab) as grey_fab,
          smp_cost_fabric_prod_cons.id as smp_cost_fabric_prod_con_id,
          smp_cost_fabric_prod_cons.bom_qty,
          smp_cost_fabric_prod_cons.rate,
          smp_cost_fabric_prod_cons.amount
            ')
        ->join('smp_cost_fabrics',function($join){
          $join->on('smp_cost_fabrics.id','=','smp_cost_fabric_prods.smp_cost_fabric_id');
        })
        ->join('smp_cost_fabric_cons',function($join){
          $join->on('smp_cost_fabrics.id','=','smp_cost_fabric_cons.smp_cost_fabric_id')
          ->whereNull('smp_cost_fabric_cons.deleted_at');
        })
        ->leftJoin('colors as fabric_colors',function($join){
          $join->on('fabric_colors.id','=','smp_cost_fabric_cons.fabric_color');
        })
        ->leftJoin('smp_cost_fabric_prod_cons',function($join){
          $join->on('smp_cost_fabric_prod_cons.fabric_color_id','=','smp_cost_fabric_cons.fabric_color')
          ->on('smp_cost_fabric_prod_cons.smp_cost_fabric_prod_id','=','smp_cost_fabric_prods.id')
          ->whereNull('smp_cost_fabric_prod_cons.deleted_at');
        })
        ->where([['smp_cost_fabric_prods.id','=',request('smp_cost_fabric_prod_id',0)]])
        ->groupBy([
          'smp_cost_fabrics.smp_cost_id',
          'smp_cost_fabrics.id',
          'smp_cost_fabric_prods.id',
          'smp_cost_fabric_cons.fabric_color',
          'fabric_colors.name',
          'smp_cost_fabric_prod_cons.id',
          'smp_cost_fabric_prod_cons.bom_qty',
          'smp_cost_fabric_prod_cons.rate',
          'smp_cost_fabric_prod_cons.amount'
        ])->get();

        $dropdown['smpcostfabricprodconscs'] = "'".Template::loadView('Sample.Costing.SmpCostFabricProdColorSizeMatrix',['dataArr'=>$fabric])."'";
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmpCostFabricProdConRequest $request) {
		$budget_id=0;
		foreach($request->smp_cost_fabric_prod_id as $index=>$smp_cost_fabric_prod_id){
			$smp_cost_id=$request->smp_cost_id[$index];
				if($request->bom_qty[$index]){
				$smpcostfabricprodcon = $this->smpcostfabricprodcon->updateOrCreate(
				['smp_cost_fabric_prod_id' => $smp_cost_fabric_prod_id,'fabric_color_id' => $request->fabric_color_id[$index]],
				['bom_qty' => $request->bom_qty[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
				);
				}
			}
		return response()->json(array('success' => true, 'id' => $smpcostfabricprodcon->id, 'smp_cost_id' => $smp_cost_id, 'message' => 'Save Successfully'), 200);

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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SmpCostFabricProdConRequest $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

}