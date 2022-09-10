<?php

namespace App\Http\Controllers\Bom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetFabricConRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetFabricConRequest;

class BudgetFabricConController extends Controller {

    private $budgetfabriccon;
    private $budgetfabric;
  	private $budget;
  	private $stylegmtcolorsize;
	  private $color;
    private $keycontrol;

    public function __construct(BudgetFabricConRepository $budgetfabriccon,BudgetFabricRepository $budgetfabric,BudgetRepository $budget,StyleGmtColorSizeRepository $stylegmtcolorsize,SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,ColorRepository $color,KeycontrolRepository $keycontrol) {
        $this->budgetfabriccon = $budgetfabriccon;
        $this->budgetfabric = $budgetfabric;
        $this->budget = $budget;
        $this->stylegmtcolorsize = $stylegmtcolorsize;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->color = $color;
        $this->keycontrol = $keycontrol;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetfabriccons', ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetfabriccons', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetfabriccons',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetfabriccons', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $budgetfabric=array_prepend(array_pluck($this->budgetfabric->get(),'name','id'),'-Select-','');
      $budgetfabriccons=array();
	    $rows=$this->budgetfabriccon->get();
  		foreach($rows as $row){
        $budgetfabriccon['id']=	$row->id;
        $budgetfabriccon['budgetfabric']=	$budgetfabric[$row->budget_fabric_id];
  		   array_push($budgetfabriccons,$budgetfabriccon);
  		}
        echo json_encode($budgetfabriccons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $budgetfabric=$this->budgetfabric->find(request('budget_fabric_id',0));
      $budget=$this->budget
      ->join('jobs',function($join){
      $join->on('jobs.id','=','budgets.job_id');
      })
      ->where([['budgets.id','=',$budgetfabric->budget_id]])
      ->get([
        'budgets.budget_date',
        'jobs.company_id'
      ])
      ->first();
      //echo json_encode($budget);

      

      $keycontrol=$this->keycontrol
      ->join('keycontrol_parameters', function($join)  {
      $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
      })
      ->where([['parameter_id','=',12]])
      ->where([['keycontrols.company_id','=',$budget->company_id]])
      ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$budget->budget_date])
      ->get([
      'keycontrol_parameters.value'
      ])->first();

      $unlayablePer=0;
      if($keycontrol){
        $unlayablePer=$keycontrol->value;
      }


      $color_arr=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
      $fabric=$this->salesordergmtcolorsize
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
      ->join('budget_fabrics',function($join){
      $join->on('budget_fabrics.budget_id','=','budgets.id');
      })
      ->join('style_fabrications',function($join){
      $join->on('style_fabrications.style_gmt_id','=','sales_order_gmt_color_sizes.style_gmt_id');
      $join->on('budget_fabrics.style_fabrication_id','=','style_fabrications.id');
      })
      ->leftJoin('style_samples',function($join){
      $join->on('style_samples.style_gmt_id','=','style_fabrications.style_gmt_id');
      $join->where('style_samples.is_costing_allowed','=',1);
      })
      ->leftJoin('style_sample_cs',function($join){
      $join->on('style_sample_cs.style_sample_id','=','style_samples.id');
      $join->on('style_sample_cs.style_gmt_color_size_id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
      })
      ->leftJoin('smp_costs',function($join){
      $join->on('smp_costs.style_sample_id','=','style_samples.id');
      })
      ->leftJoin('smp_cost_fabrics',function($join){
      $join->on('smp_cost_fabrics.smp_cost_id','=','smp_costs.id');
      $join->on('smp_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
      })
      ->leftJoin('smp_cost_fabric_cons',function($join){
      $join->on('smp_cost_fabric_cons.smp_cost_fabric_id','=','smp_cost_fabrics.id');
      $join->on('smp_cost_fabric_cons.style_sample_c_id','=','style_sample_cs.id');
      })
      ->leftJoin('cads',function($join){
      $join->on('cads.style_id','=','jobs.style_id');
      })
      ->leftJoin('cad_cons',function($join){
      $join->on('cad_cons.cad_id','=','cads.id');
      $join->on('cad_cons.style_fabrication_id','=','budget_fabrics.style_fabrication_id');
      $join->on('cad_cons.style_gmt_color_size_id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
      })

      ->leftJoin('budget_fabric_cons',function($join){
      $join->on('budget_fabrics.id','=','budget_fabric_cons.budget_fabric_id')
      ->on('sales_order_gmt_color_sizes.id','=','budget_fabric_cons.sales_order_gmt_color_size_id')
      ->whereNull('budget_fabric_cons.deleted_at');
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
      ->orderBy('sales_orders.id')
      ->orderBy('style_colors.sort_id')
      ->orderBy('style_sizes.sort_id')
      ->where([['budget_fabrics.id','=',request('budget_fabric_id',0)]])
      ->where([['sales_order_gmt_color_sizes.plan_cut_qty','>',0]])
      ->get([
      'budget_fabrics.budget_id',
      'budget_fabrics.id as budget_fabric_id',
      'style_sizes.id as style_size_id',
      'style_colors.id as style_color_id',
      'sizes.name as size_name',
      'sizes.code as size_code',
      'colors.name as color_name',
      'colors.code as color_code',
      'style_sizes.sort_id as size_sort_id',
      'style_colors.sort_id as color_sort_id',
      'sales_order_gmt_color_sizes.plan_cut_qty',
      'budget_fabric_cons.id as budget_fabric_con_id',
      'budget_fabric_cons.dia',
      'budget_fabric_cons.cons',
      'budget_fabric_cons.process_loss',
      'budget_fabric_cons.req_cons',
      'budget_fabric_cons.rate',
      'budget_fabric_cons.amount',
      'budget_fabric_cons.fabric_color',
      'budget_fabric_cons.measurment',
      'budget_fabric_cons.fin_fab',
      'budget_fabric_cons.grey_fab',
      'budget_fabric_cons.unlayable_per',
      'countries.name as country_name',
      'sales_orders.sale_order_no',
      'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id',
      'cad_cons.cons as cad_cons',
      'cad_cons.dia as cad_dia',
      'smp_cost_fabric_cons.dia as smp_dia',
      'smp_cost_fabric_cons.cons as smp_cons',
      'smp_cost_fabric_cons.process_loss as smp_process_loss',
      'smp_cost_fabric_cons.req_cons as smp_req_cons',
      'smp_cost_fabric_cons.rate as smp_rate',
      'smp_cost_fabric_cons.amount as smp_amount'

      ]);

      $saved = $fabric->filter(function ($value) {
      if($value->budget_fabric_con_id){
      return $value;
      }
      });
      $new = $fabric->filter(function ($value) {
      if(!$value->budget_fabric_con_id){
      return $value;
      }
      });

      return Template::LoadView('Bom.BudgetFabricColorSizeMatrix',['colorsizes'=>$new,'saved'=>$saved,'color_arr'=>$color_arr,'unlayablePer'=>$unlayablePer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetFabricConRequest $request) {
      $budgetapp=$this->budget->find(request('bud_id',0));
      if($budgetapp->approved_at){
      return response()->json(array('success' => false,  'message' => 'Budget is Approved, So Save Or Update not Possible'), 200);

      }
		$budget_id=0;
		foreach($request->style_color_id as $index=>$style_color_id){
			$budget_id=$request->budget_id[$index];
      $budget_fabric_id=$request->budget_fabric_id[$index];
			$color = $this->color->firstOrCreate(['name' => $request->fabric_color[$index]],['code' => '']);
				//if($request->cons[$index]){
				$budgetfabriccon = $this->budgetfabriccon->updateOrCreate(
				[
          'budget_fabric_id' => $request->budget_fabric_id[$index],
          'sales_order_gmt_color_size_id' => $request->sales_order_gmt_color_size_id[$index],
          'style_color_id' => $style_color_id,
          'style_size_id' => $request->style_size_id[$index]
        ],
				[
          'dia' => $request->dia[$index]?$request->dia[$index]:'any',
          'fabric_color' => $color->id,
          'measurment' => $request->measurment[$index]?$request->measurment[$index]:'.',
          'cons' => $request->cons[$index],
          'fin_fab' => $request->fin_fab[$index],
          'process_loss' => $request->process_loss[$index],
          'req_cons' => $request->req_cons[$index],
          'grey_fab' => $request->grey_fab[$index],
          'rate' => $request->rate[$index],
          'amount' =>$request->amount[$index],
          'unlayable_per' =>$request->unlayable_per[$index]
        ]
				);
				//}
			}
			$totalCost=$this->budget->totalCost($budget_id);
		return response()->json(array('success' => true, 'id' => $budgetfabriccon->id, 'budget_id' => $budget_id,'budget_fabric_id'=>$budget_fabric_id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);

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
        $budgetfabriccon = $this->budgetfabriccon->find($id);
        $row ['fromData'] = $budgetfabriccon;
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
    public function update(BudgetFabricConRequest $request, $id) {
        $budgetapp=$this->budget->find($request->budget_id);
        if($budgetapp->approved_at){
        return response()->json(array('success' => false,  'message' => 'Budget is Approved, So Save Or Update not Possible'), 200);

        }
        $budgetfabriccon = $this->budgetfabriccon->update($id, $request->except(['id']));
        if ($budgetfabriccon) {
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
        if ($this->budgetfabriccon->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
