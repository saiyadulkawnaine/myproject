<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetFabricProdConRepository;
use App\Repositories\Contracts\Bom\BudgetFabricProdRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetFabricProdConRequest;

class BudgetFabricProdConController extends Controller {

    private $budgetfabricprodcon;
    private $budgetfabricprod;
	private $budget;
	private $stylegmtcolorsize;
	private $salesordergmtcolorsize;
	private $color;

    public function __construct(BudgetFabricProdConRepository $budgetfabricprodcon,BudgetFabricProdRepository $budgetfabricprod,BudgetRepository $budget,StyleGmtColorSizeRepository $stylegmtcolorsize,SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,ColorRepository $color) {
        $this->budgetfabricprodcon = $budgetfabricprodcon;
        $this->budgetfabricprod = $budgetfabricprod;
    	$this->budget = $budget;
    	$this->stylegmtcolorsize = $stylegmtcolorsize;
		$this->salesordergmtcolorsize = $salesordergmtcolorsize;
		 $this->color = $color;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetfabricprodcons',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetfabricprodcons', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetfabricprodcons',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetfabricprodcons', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $budgetfabricprod=array_prepend(array_pluck($this->budgetfabricprod->get(),'name','id'),'-Select-','');
      $budgetfabricprodcons=array();
	    $rows=$this->budgetfabricprodcon->get();
  		foreach($rows as $row){
        $budgetfabricprodcon['id']=	$row->id;
        // $budgetfabricprodcon['process_id']=	$row->process_id;
        // $budgetfabricprodcon['cons']=	$row->cons;
        // $budgetfabricprodcon['rate']=	$row->rate;
        // $budgetfabricprodcon['amount']=	$row->amount;
        $budgetfabricprodcon['budgetfabricprod']=	$budgetfabricprod[$row->budget_fabric_id];
  		   array_push($budgetfabricprodcons,$budgetfabricprodcon);
  		}
        echo json_encode($budgetfabricprodcons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $budgetfabricprod=$this->budgetfabricprod->find(request('budget_fabric_prod_id',0));
      $dataArr=$this->budgetfabricprod
      ->selectRaw('
      budget_fabrics.budget_id,
      budget_fabrics.id as budget_fabric_id,
      sales_orders.sale_order_no,
      sales_orders.id as sale_order_id,
      budget_fabric_prods.id as budget_fabric_prod_id,
      budget_fabric_prods.overhead_rate,
      production_processes.production_area_id,
      budget_fabric_cons.fabric_color as fabric_color_id,
      colors.name as fabric_color,
      sum(budget_fabric_cons.grey_fab) as grey_fab,
      sum(budget_fabric_cons.fin_fab) as fin_fab,
      budget_fabric_prod_cons.id as budget_fabric_prod_con_id,
      budget_fabric_prod_cons.bom_qty,
      budget_fabric_prod_cons.rate,
      budget_fabric_prod_cons.amount,
      smp_cost_fabric_prod_cons.rate as smp_rate
      ')
      ->join('budget_fabrics',function($join){
      $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
      })
      ->join('budgets',function($join){
      $join->on('budgets.id','=','budget_fabrics.budget_id');
      })
      ->join('jobs',function($join){
      $join->on('jobs.id','=','budgets.job_id');
      })
      ->join('sales_orders',function($join){
      $join->on('sales_orders.job_id','=','jobs.id');
      })
      ->join('sales_order_gmt_color_sizes',function($join){
      $join->on('sales_order_gmt_color_sizes.sale_order_id','=','sales_orders.id')
      ->whereNull('sales_order_gmt_color_sizes.deleted_at');
      })
      ->join('style_gmt_color_sizes',function($join){
      $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id')
      ->whereNull('style_gmt_color_sizes.deleted_at');
      })
      ->leftJoin('style_samples',function($join){
      $join->on('style_samples.style_gmt_id','=','style_gmt_color_sizes.style_gmt_id');
      $join->where('style_samples.is_costing_allowed','=',1);
      })
      ->leftJoin('style_sample_cs',function($join){
      $join->on('style_sample_cs.style_sample_id','=','style_samples.id');
      $join->on('style_sample_cs.style_gmt_color_size_id','=','style_gmt_color_sizes.id');
      })
      ->join('budget_fabric_cons',function($join){
      $join->on('budget_fabrics.id','=','budget_fabric_cons.budget_fabric_id');
      $join->on('budget_fabric_cons.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id')
      ->whereNull('budget_fabric_cons.deleted_at');
      })
      ->join('colors',function($join){
      $join->on('colors.id','=','budget_fabric_cons.fabric_color');
      })
      ->leftJoin('smp_costs',function($join){
      $join->on('smp_costs.style_sample_id','=','style_samples.id');
      })
      ->leftJoin('smp_cost_fabrics',function($join){
      $join->on('smp_cost_fabrics.smp_cost_id','=','smp_costs.id');
      $join->on('smp_cost_fabrics.style_fabrication_id','=','budget_fabrics.style_fabrication_id');
      })
      ->leftJoin('smp_cost_fabric_prods',function($join) use($budgetfabricprod){
      $join->on('smp_cost_fabric_prods.smp_cost_fabric_id','=','smp_cost_fabrics.id')
      ->where('smp_cost_fabric_prods.production_process_id','=',$budgetfabricprod->production_process_id);
      })
      ->leftJoin('smp_cost_fabric_prod_cons',function($join){
      $join->on('smp_cost_fabric_prod_cons.smp_cost_fabric_prod_id','=','smp_cost_fabric_prods.id');
      $join->on('smp_cost_fabric_prod_cons.fabric_color_id','=','budget_fabric_cons.fabric_color');
      })
      ->leftJoin('budget_fabric_prod_cons',function($join){
      $join->on('budget_fabric_prod_cons.fabric_color_id','=','budget_fabric_cons.fabric_color')
      ->on('budget_fabric_prod_cons.budget_fabric_prod_id','=','budget_fabric_prods.id')
      ->on('budget_fabric_prod_cons.sales_order_id','=','sales_orders.id')
      ->whereNull('budget_fabric_prod_cons.deleted_at');
      })
      ->join('production_processes',function($join){
      $join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
      })
      ->orderBy('sales_orders.id')
      ->orderBy('budget_fabric_prod_cons.id')
      ->where([['budget_fabric_prods.id','=',request('budget_fabric_prod_id',0)]])
      ->groupBy([
      'budget_fabrics.budget_id',
      'budget_fabrics.id',
      'sales_orders.sale_order_no',
      'sales_orders.id',
      'budget_fabric_prods.id',
      'budget_fabric_prods.overhead_rate',
      'production_processes.production_area_id',
      'budget_fabric_cons.fabric_color',
      'colors.name',
      'budget_fabric_prod_cons.id',
      'budget_fabric_prod_cons.bom_qty',
      'budget_fabric_prod_cons.rate',
      'budget_fabric_prod_cons.amount',
      'smp_cost_fabric_prod_cons.rate'
      ])
      ->get()
      ->map(function($dataArr){
        if($dataArr->production_area_id==25)
        {
          $dataArr->grey_fab=$dataArr->fin_fab;
        }
        else
        {
          $dataArr->grey_fab=$dataArr->grey_fab;
        }
        return $dataArr;
      });

     /* $saved = $dataArr->filter(function ($value) {
        if($value->budget_fabric_prod_con_id){
        return $value;
        }
      });*/
      $new = $dataArr->filter(function ($value) {
        if(!$value->budget_fabric_prod_con_id && $value->grey_fab){
        return $value;
        }
      });

      $saved=$this->budgetfabricprod
      ->selectRaw('
      budget_fabric_prods.id as budget_fabric_prod_id,
      budget_fabric_prods.budget_id,
      budget_fabric_prods.overhead_rate,
      budget_fabric_prod_cons.id as budget_fabric_prod_con_id,
      budget_fabric_prod_cons.fabric_color_id,
      colors.name as fabric_color,
      budget_fabric_prod_cons.sales_order_id,
      sales_orders.id as sale_order_id,
      sales_orders.sale_order_no,
      budget_fabric_prod_cons.bom_qty,
      budget_fabric_prod_cons.rate,
      budget_fabric_prod_cons.amount,
      budget_fabric_cons.fin_fab,
      budget_fabric_cons.grey_fab,
      po_dyeing_service_item_qties.qty as po_qty
      ')
      ->join('budget_fabric_prod_cons',function($join){
      $join->on('budget_fabric_prod_cons.budget_fabric_prod_id','=','budget_fabric_prods.id');
      })
      ->join('production_processes',function($join){
      $join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
      })
      ->join('colors',function($join){
      $join->on('colors.id','=','budget_fabric_prod_cons.fabric_color_id');
      })
      ->join('sales_orders',function($join){
      $join->on('sales_orders.id','=','budget_fabric_prod_cons.sales_order_id');
      })
      ->leftJoin(\DB::raw("(select 
      budget_fabric_cons.budget_fabric_id,
      budget_fabric_cons.fabric_color,
      sales_orders.id as sales_order_id,
      sum(budget_fabric_cons.grey_fab) as grey_fab,
      sum(budget_fabric_cons.fin_fab) as fin_fab
      from budget_fabric_cons
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id=budget_fabric_cons.sales_order_gmt_color_size_id
      join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id
      where budget_fabric_cons.deleted_at is null
      and  sales_order_gmt_color_sizes.deleted_at is null
      and  sales_orders.deleted_at is null
      group by 
      budget_fabric_cons.budget_fabric_id,
      budget_fabric_cons.fabric_color,
      sales_orders.id) budget_fabric_cons "), [["budget_fabric_cons.budget_fabric_id", "=", "budget_fabric_prods.budget_fabric_id"],["budget_fabric_cons.fabric_color", "=", "budget_fabric_prod_cons.fabric_color_id"],["budget_fabric_cons.sales_order_id", "=", "budget_fabric_prod_cons.sales_order_id"]])
      ->leftJoin(\DB::raw("(select 
      po_dyeing_service_item_qties.budget_fabric_prod_con_id,
      sum(po_dyeing_service_item_qties.qty) as qty
      from 
      po_dyeing_service_item_qties
      where po_dyeing_service_item_qties.deleted_at is null
      group by
      po_dyeing_service_item_qties.budget_fabric_prod_con_id) po_dyeing_service_item_qties "), "po_dyeing_service_item_qties.budget_fabric_prod_con_id", "=", "budget_fabric_prod_cons.id")
      ->orderBy('sales_orders.id')
      ->orderBy('budget_fabric_prod_cons.id')
      ->where([['budget_fabric_prods.id','=',request('budget_fabric_prod_id',0)]])
      ->get()
      ->map(function($dataArr){
      if($dataArr->production_area_id==25)
      {
      $dataArr->grey_fab=$dataArr->fin_fab;
      }
      else
      {
      $dataArr->grey_fab=$dataArr->grey_fab;
      }
      return $dataArr;
      });
      

      return Template::LoadView('Bom.BudgetFabricProdColorSizeMatrix',['dataArr'=>$new,'saved'=>$saved]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetFabricProdConRequest $request) {
		$budget_id=0;
		foreach($request->budget_fabric_prod_id as $index=>$budget_fabric_prod_id){
			$budget_id=$request->budget_id[$index];
			//$color = $this->color->firstOrCreate(['name' => $request->fabric_color_id[$index]],['code' => '']);
				if($request->bom_qty[$index]){
        $overhead_amount=$request->bom_qty[$index]*$request->overhead_rate[$index];
				$budgetfabricprodcon = $this->budgetfabricprodcon->updateOrCreate(
				['budget_fabric_prod_id' => $budget_fabric_prod_id,'sales_order_id'=> $request->sales_order_id[$index],'fabric_color_id' => $request->fabric_color_id[$index]],
				['bom_qty' => $request->bom_qty[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index],'overhead_rate' =>$request->overhead_rate[$index],'overhead_amount' =>$overhead_amount]
				);
				}
			}
      
			$totalCost=$this->budget->totalCost($budget_id);
		  return response()->json(array('success' => true, 'id' => $budgetfabricprodcon->id, 'budget_id' => $budget_id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);

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
        /*$budgetfabricprodcon = $this->budgetfabricprodcon->find($id);
        $row ['fromData'] = $budgetfabricprodcon;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BudgetFabricProdConRequest $request, $id) {
        /*$budgetfabricprodcon = $this->budgetfabricprodcon->update($id, $request->except(['id']));
        if ($budgetfabricprodcon) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $thisObj=$this->budgetfabricprodcon->find($id);
        $prntObj=$this->budgetfabricprod->find($thisObj->budget_fabric_prod_id);
        $budget_id=$prntObj->budget_id;

        $po=$this->budgetfabricprodcon
        ->join('po_dyeing_service_item_qties',function($join){
        $join->on('po_dyeing_service_item_qties.budget_fabric_prod_con_id','=','budget_fabric_prod_cons.id');
        })
        ->where([['budget_fabric_prod_cons.id','=',$id]])
        ->get()
        ->first();
        if($po)
        {
        return response()->json(array('success' => false, 'message' => 'Delete Not Possible, Dyeing Work Order Found'), 200);
        }

        $poaop=$this->budgetfabricprodcon
        ->join('po_aop_service_item_qties',function($join){
        $join->on('po_aop_service_item_qties.budget_fabric_prod_con_id','=','budget_fabric_prod_cons.id');
        })
        ->where([['budget_fabric_prod_cons.id','=',$id]])
        ->get()
        ->first();
        if($poaop)
        {
        return response()->json(array('success' => false, 'message' => 'Delete Not Possible, AOP Work Order Found'), 200);
        }

        $poknit=$this->budgetfabricprodcon
        ->join('po_knit_service_items',function($join){
        $join->on('po_knit_service_items.budget_fabric_prod_id','=','budget_fabric_prod_cons.budget_fabric_prod_id');
        })
        ->where([['budget_fabric_prod_cons.id','=',$id]])
        ->get()
        ->first();
        if($poknit)
        {
        return response()->json(array('success' => false, 'message' => 'Delete Not Possible, Knitting Work Order Found'), 200);
        }


        if ($this->budgetfabricprodcon->delete($id)) {
        $totalCost=$this->budget->totalCost($budget_id);
        return response()->json(array('success' => true,'id' => $id, 'budget_id' => $budget_id, 'message' => 'Delete Successfully','totalcost' => $totalCost), 200);
        }
    }

}
