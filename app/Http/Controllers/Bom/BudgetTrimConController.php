<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetTrimConRepository;
use App\Repositories\Contracts\Bom\BudgetTrimRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetTrimConRequest;

class BudgetTrimConController extends Controller {

    private $budgettrimcon;
    private $budgettrim;
  	private $budget;
  	private $stylegmtcolorsize;
	private $salesordergmtcolorsize;
	private $color;

    public function __construct(BudgetTrimConRepository $budgettrimcon,BudgetTrimRepository $budgettrim,BudgetRepository $budget,StyleGmtColorSizeRepository $stylegmtcolorsize,SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,ColorRepository $color) {
        $this->budgettrimcon = $budgettrimcon;
        $this->budgettrim = $budgettrim;
    	$this->budget = $budget;
    	$this->stylegmtcolorsize = $stylegmtcolorsize;
		$this->salesordergmtcolorsize = $salesordergmtcolorsize;
		$this->color = $color;
        $this->middleware('auth');
        $this->middleware('permission:view.budgettrimcons',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgettrimcons', ['only' => ['store']]);
        $this->middleware('permission:edit.budgettrimcons',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgettrimcons', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $budgettrim=array_prepend(array_pluck($this->budgettrim->get(),'name','id'),'-Select-','');
      $budgettrimcons=array();
	    $rows=$this->budgettrimcon->get();
  		foreach($rows as $row){
        $budgettrimcon['id']=	$row->id;
        // $budgettrimcon['process_id']=	$row->process_id;
        // $budgettrimcon['cons']=	$row->cons;
        // $budgettrimcon['rate']=	$row->rate;
        // $budgettrimcon['amount']=	$row->amount;
        $budgettrimcon['budgettrim']=	$budgettrim[$row->budget_trim_id];
  		   array_push($budgettrimcons,$budgettrimcon);
  		}
        echo json_encode($budgettrimcons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$color_arr=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
		/*$fabric=$this->salesordergmtcolorsize
		->join('jobs',function($join){
          $join->on('jobs.id','=','sales_order_gmt_color_sizes.job_id');
        })
		->join('sales_orders',function($join){
          $join->on('sales_orders.job_id','=','jobs.id');
        })
		->join('sales_order_countries',function($join){
		  $join->on('sales_order_countries.sale_order_id','=','sales_orders.id');
          $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
        })
		->join('budgets',function($join){
          $join->on('budgets.job_id','=','jobs.id');
        })
		->join('budget_trims',function($join){
          $join->on('budget_trims.budget_id','=','budgets.id');
        })
		->leftJoin('budget_trim_cons',function($join){
		  $join->on('budget_trims.id','=','budget_trim_cons.budget_trim_id')
		 ->on('sales_order_gmt_color_sizes.id','=','budget_trim_cons.sales_order_gmt_color_size_id')
		  ->whereNull('budget_trim_cons.deleted_at');
        })
		->join('style_sizes',function($join){
          $join->on('style_sizes.id','=','sales_order_gmt_color_sizes.style_size_id');
        })
		->join('sizes',function($join){
          $join->on('sizes.id','=','style_sizes.size_id');
        })
		->join('style_colors',function($join){
          $join->on('style_colors.id','=','sales_order_gmt_color_sizes.style_color_id');
        })
		->join('colors',function($join){
          $join->on('colors.id','=','style_colors.color_id');
        })
		->join('style_gmts',function($join){
          $join->on('style_gmts.id','=','sales_order_gmt_color_sizes.style_gmt_id');
        })
		->join('item_accounts',function($join){
          $join->on('item_accounts.id','=','style_gmts.item_account_id');
        })
		->join('countries',function($join){
          $join->on('countries.id','=','sales_order_countries.country_id');
        })
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
        ->where([['budget_trims.id','=',request('budget_trim_id',0)]])
		->get([
          'budget_trims.budget_id',
          'budget_trims.id as budget_trim_id',
		  'style_sizes.id as style_size_id',
		  'style_colors.id as style_color_id',
		  'sizes.name as size_name',
		  'sizes.code as size_code',
		  'colors.name as color_name',
		  'colors.code as color_code',
		  'style_sizes.sort_id as size_sort_id',
		  'style_colors.sort_id as color_sort_id',
		  'sales_order_gmt_color_sizes.qty as plan_cut_qty',
		  'budget_trim_cons.cons',
		  'budget_trim_cons.process_loss',
		  'budget_trim_cons.req_cons',
		  'budget_trim_cons.rate',
		  'budget_trim_cons.amount',
		  'budget_trim_cons.trim_color',
		  'budget_trim_cons.measurment',
		  'budget_trim_cons.req_trim',
		  'budget_trim_cons.bom_trim',
		  'countries.name as country_name',
		  'sales_orders.sale_order_no',
		  'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id',
		  'item_accounts.item_description'
        ]);*/

        $trim=$this->budgettrim
        ->join('budgets',function($join){
          $join->on('budgets.id','=','budget_trims.budget_id');
        })
        ->join('jobs',function($join){
          $join->on('jobs.id','=','budgets.job_id');
        })
       ->join('sales_orders',function($join){
          $join->on('sales_orders.job_id','=','jobs.id');
        })
       ->join('sales_order_countries',function($join){
          $join->on('sales_order_countries.sale_order_id','=','sales_orders.id');
        })
        
        ->leftJoin('sales_order_gmt_color_sizes',function($join){
         $join->on('sales_order_gmt_color_sizes.sale_order_country_id','=','sales_order_countries.id')
          ->whereNull('sales_order_gmt_color_sizes.deleted_at');
        })
        
        
        ->join('style_sizes',function($join){
          $join->on('style_sizes.id','=','sales_order_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
          $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('style_colors',function($join){
          $join->on('style_colors.id','=','sales_order_gmt_color_sizes.style_color_id');
        })
        ->join('colors',function($join){
          $join->on('colors.id','=','style_colors.color_id');
        })
        ->join('style_gmts',function($join){
          $join->on('style_gmts.id','=','sales_order_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts',function($join){
          $join->on('item_accounts.id','=','style_gmts.item_account_id');
        })
        ->join('countries',function($join){
          $join->on('countries.id','=','sales_order_countries.country_id');
        })

        ->leftJoin('budget_trim_cons',function($join){
          $join->on('budget_trims.id','=','budget_trim_cons.budget_trim_id')
         ->on('sales_order_gmt_color_sizes.id','=','budget_trim_cons.sales_order_gmt_color_size_id')
          ->whereNull('budget_trim_cons.deleted_at');
        })

        ->leftJoin('style_samples',function($join){
          $join->on('style_samples.style_gmt_id','=','style_gmts.id');
          $join->where('style_samples.is_costing_allowed','=',1);
        })
        ->leftJoin('style_sample_cs',function($join){
          $join->on('style_sample_cs.style_sample_id','=','style_samples.id');
          $join->on('style_sample_cs.style_gmt_color_size_id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->leftJoin('smp_costs',function($join){
          $join->on('smp_costs.style_sample_id','=','style_samples.id');
        })
        ->leftJoin('smp_cost_trims',function($join){
          $join->on('smp_cost_trims.smp_cost_id','=','smp_costs.id');
          $join->on('smp_cost_trims.itemclass_id','=','budget_trims.itemclass_id');
        })
        ->leftJoin('smp_cost_trim_cons',function($join){
          $join->on('smp_cost_trim_cons.smp_cost_trim_id','=','smp_cost_trims.id');
          $join->on('smp_cost_trim_cons.style_sample_c_id','=','style_sample_cs.id');
        })

        ->orderBy('sales_orders.id')

        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
        ->where([['budget_trims.id','=',request('budget_trim_id',0)]])
        ->where([['sales_order_gmt_color_sizes.qty','>',0]])
        
        ->get([
          'budget_trims.budget_id',
          'budget_trims.id as budget_trim_id',
          'budgets.costing_unit_id',
          'style_sizes.id as style_size_id',
          'style_colors.id as style_color_id',
          'sizes.name as size_name',
          'sizes.code as size_code',
          'colors.name as color_name',
          'colors.code as color_code',
          'style_sizes.sort_id as size_sort_id',
          'style_colors.sort_id as color_sort_id',
          'sales_order_gmt_color_sizes.qty as plan_cut_qty',
          'budget_trim_cons.id as budget_trim_con_id',
          'budget_trim_cons.cons',
          'budget_trim_cons.process_loss',
          'budget_trim_cons.req_cons',
          'budget_trim_cons.rate',
          'budget_trim_cons.amount',
          'budget_trim_cons.trim_color',
          'budget_trim_cons.measurment',
          'budget_trim_cons.req_trim',
          'budget_trim_cons.bom_trim',
          'countries.name as country_name',
          'sales_orders.sale_order_no',
          'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id',
          'item_accounts.item_description',

          'smp_cost_trim_cons.cons as smp_cons',
          'smp_cost_trim_cons.process_loss as smp_process_loss',
          'smp_cost_trim_cons.req_cons as smp_req_cons',
          'smp_cost_trim_cons.rate as smp_rate',
          'smp_cost_trim_cons.amount as smp_amount',
          'smp_cost_trim_cons.trim_color as smp_trim_color',
          'smp_cost_trim_cons.measurment as smp_measurment',
          'smp_cost_trim_cons.req_trim as smp_req_trim',
          'smp_cost_trim_cons.bom_trim as smp_bom_trim',
        ])->map(function($trim){
            $trim->smp_req_trim=($trim->smp_cons/$trim->costing_unit_id)*$trim->plan_cut_qty;
            $trim->smp_bom_trim=($trim->smp_req_cons/$trim->costing_unit_id)*$trim->plan_cut_qty;
            $trim->smp_amount=$trim->smp_bom_trim*$trim->smp_rate;
            return $trim;

        });

        $saved = $trim->filter(function ($value) {
            if($value->budget_trim_con_id){
                return $value;
            }
        });
        $new = $trim->filter(function ($value) {
            if(!$value->budget_trim_con_id){
                return $value;
            }
        });

        $dropdown['trimscs'] = "'".Template::loadView('Bom.BudgetTrimColorSizeMatrix',['colorsizes'=>$new,'saved'=>$saved,'color_arr'=>$color_arr])."'";
		    $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetTrimConRequest $request) {
		$budget_id=0;
		foreach($request->style_color_id as $index=>$style_color_id){
			$budget_id=$request->budget_id[$index];
			$color = $this->color->firstOrCreate(['name' => $request->trim_color[$index]],['code' => '']);
				if($request->cons[$index]){
				$budgettrimcon = $this->budgettrimcon->updateOrCreate(
				['budget_trim_id' => $request->budget_trim_id[$index],'sales_order_gmt_color_size_id' => $request->sales_order_gmt_color_size_id[$index],'style_gmt_id' => $request->style_gmt_id[$index],'style_color_id' => $style_color_id,'style_size_id' => $request->style_size_id[$index]],
				['trim_color' => $color->id,'measurment' => $request->measurment[$index],'cons' => $request->cons[$index],'req_trim' => $request->req_trim[$index],'process_loss' => $request->process_loss[$index],'req_cons' => $request->req_cons[$index],'bom_trim' => $request->bom_trim[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
				);
				}
			}
			$totalCost=$this->budget->totalCost($budget_id);
		return response()->json(array('success' => true, 'id' => $budgettrimcon->id, 'budget_id' => $budget_id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);

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
        $budgettrimcon = $this->budgettrimcon->find($id);
        $row ['fromData'] = $budgettrimcon;
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
    public function update(BudgetTrimConRequest $request, $id) {
        $budgettrimcon = $this->budgettrimcon->update($id, $request->except(['id']));
        if ($budgettrimcon) {
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
        if ($this->budgettrimcon->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
