<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetEmbConRepository;
use App\Repositories\Contracts\Bom\BudgetEmbRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetEmbConRequest;

class BudgetEmbConController extends Controller {

    private $budgetembcon;
    private $budgetemb;
  	private $budget;
  	private $stylegmtcolorsize;

    public function __construct(BudgetEmbConRepository $budgetembcon,BudgetEmbRepository $budgetemb,BudgetRepository $budget,StyleGmtColorSizeRepository $stylegmtcolorsize,SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->budgetembcon = $budgetembcon;
        $this->budgetemb = $budgetemb;
        $this->budget = $budget;
        $this->stylegmtcolorsize = $stylegmtcolorsize;
		$this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetembcons',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetembcons', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetembcons',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetembcons', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $budgetemb=array_prepend(array_pluck($this->budgetemb->get(),'name','id'),'-Select-','');
      $budgetembcons=array();
	    $rows=$this->budgetembcon->get();
  		foreach($rows as $row){
        $budgetembcon['id']=	$row->id;
        $budgetembcon['budgetemb']=	$budgetemb[$row->budget_emb_id];
  		   array_push($budgetembcons,$budgetembcon);
  		}
        echo json_encode($budgetembcons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
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
		->join('budget_embs',function($join){
          $join->on('budget_embs.budget_id','=','budgets.id');
        })

        ->join('style_embelishments',function($join){
          $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
          $join->on('style_embelishments.style_gmt_id','=','sales_order_gmt_color_sizes.style_gmt_id');
        })

		

		->leftJoin('budget_emb_cons',function($join){
		  $join->on('budget_embs.id','=','budget_emb_cons.budget_emb_id')
		  ->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id')
		  ->whereNull('budget_emb_cons.deleted_at');
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
		->join('countries',function($join){
          $join->on('countries.id','=','sales_order_countries.country_id');
        })
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
        ->where([['budget_embs.id','=',request('budget_emb_id',0)]])
        ->get([
          'budget_embs.budget_id',
          'budget_embs.id as budget_emb_id',
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
		  'budget_emb_cons.cons',
		  'budget_emb_cons.req_cons',
		  'budget_emb_cons.rate',
		  'budget_emb_cons.amount',
		  'countries.name as country_name',
		  'sales_orders.sale_order_no',
		  'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id'
        ]);*/
      $fabric=$this->budgetemb
      ->join('budgets',function($join){
      $join->on('budgets.id','=','budget_Embs.budget_id');
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
      ->join('countries',function($join){
      $join->on('countries.id','=','sales_order_countries.country_id');
      })
      ->join('style_embelishments',function($join){
      $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
      $join->on('style_embelishments.style_gmt_id','=','sales_order_gmt_color_sizes.style_gmt_id');
      })
      ->leftJoin('budget_emb_cons',function($join){
      $join->on('budget_embs.id','=','budget_emb_cons.budget_emb_id')
      ->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id')
      ->whereNull('budget_emb_cons.deleted_at');
      })

      ->leftJoin('style_samples',function($join){
      $join->on('style_samples.style_gmt_id','=','style_embelishments.style_gmt_id');
      $join->where('style_samples.is_costing_allowed','=',1);
      })
      ->leftJoin('style_sample_cs',function($join){
      $join->on('style_sample_cs.style_sample_id','=','style_samples.id');
      $join->on('style_sample_cs.style_gmt_color_size_id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
      })
      ->leftJoin('smp_costs',function($join){
      $join->on('smp_costs.style_sample_id','=','style_samples.id');
      })
      ->leftJoin('smp_cost_embs',function($join){
      $join->on('smp_cost_embs.smp_cost_id','=','smp_costs.id');
      $join->on('smp_cost_embs.style_embelishment_id','=','budget_embs.style_embelishment_id');
      })
      ->leftJoin('smp_cost_emb_cons',function($join){
      $join->on('smp_cost_emb_cons.smp_cost_emb_id','=','smp_cost_embs.id');
      $join->on('smp_cost_emb_cons.style_sample_c_id','=','style_sample_cs.id');
      })
      ->orderBy('sales_orders.id')
      ->orderBy('style_colors.sort_id')
      ->orderBy('style_sizes.sort_id')
      ->where([['budget_embs.id','=',request('budget_emb_id',0)]])
      ->where([['sales_order_gmt_color_sizes.qty','>',0]])
      ->get([
      'budget_embs.budget_id',
      'budget_embs.id as budget_emb_id',
      'budget_embs.overhead_rate',
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
      'budget_emb_cons.id as budget_emb_con_id',
      'budget_emb_cons.cons',
      'budget_emb_cons.req_cons',
      'budget_emb_cons.rate',
      'budget_emb_cons.amount',
      'countries.name as country_name',
      'sales_orders.sale_order_no',
      'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id',
      'smp_cost_emb_cons.cons as smp_cons',
      'smp_cost_emb_cons.req_cons as smp_req_cons',
      'smp_cost_emb_cons.rate as smp_rate',
      'smp_cost_emb_cons.amount as smp_amount',
      ])
      ->map(function($fabric){
        $fabric->smp_req_cons=($fabric->smp_cons/$fabric->costing_unit_id)*$fabric->plan_cut_qty;
        $fabric->smp_amount=$fabric->smp_req_cons*($fabric->smp_rate/$fabric->costing_unit_id);
        return $fabric;
      });

      $saved = $fabric->filter(function ($value) {
        if($value->budget_emb_con_id){
        return $value;
        }
      });
      $new = $fabric->filter(function ($value) {
        if(!$value->budget_emb_con_id){
        return $value;
        }
      });

      $dropdown['embscs'] = "'".Template::loadView('Bom.BudgetEmbColorSizeMatrix',['colorsizes'=>$new,'saved'=>$saved])."'";
      $row ['dropDown'] = $dropdown;
      echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetEmbConRequest $request) {
      $budget_id=0;
      foreach($request->style_color_id as $index=>$style_color_id)
      {
        $budget_id=$request->budget_id[$index];
        if($request->cons[$index])
        {
          $overheadAmount=$request->req_cons[$index]*($request->overhead_rate[$index]/12);
          $budgetembcon = $this->budgetembcon->updateOrCreate(
          ['budget_emb_id' => $request->budget_emb_id[$index],'sales_order_gmt_color_size_id' =>$request->sales_order_gmt_color_size_id[$index]],
          ['cons' => $request->cons[$index],'req_cons' => $request->req_cons[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index],'style_color_id' => $style_color_id,'style_size_id' => $request->style_size_id[$index],'overhead_rate' => $request->overhead_rate[$index],'overhead_amount' => $overheadAmount]
          );
        }
      }
      $totalCost=$this->budget->totalCost($budget_id);
      return response()->json(array('success' => true, 'id' => $budgetembcon->id, 'budget_id' => $budget_id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        $budgetembcon = $this->budgetembcon->find($id);
        $row ['fromData'] = $budgetembcon;
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
    public function update(BudgetEmbConRequest $request, $id) {
        $budgetembcon = $this->budgetembcon->update($id, $request->except(['id']));
        if ($budgetembcon) {
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
        if ($this->budgetembcon->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
