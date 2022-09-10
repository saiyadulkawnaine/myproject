<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetYarnDyeingConRepository;
use App\Repositories\Contracts\Bom\BudgetYarnDyeingRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetYarnDyeingConRequest;

class BudgetYarnDyeingConController extends Controller {

    private $budgetyarndyeingcon;
    private $budgetyarndyeing;
    private $budget;
    private $stylegmtcolorsize;
    private $salesordergmtcolorsize;
    private $color;

    public function __construct(
      BudgetYarnDyeingConRepository $budgetyarndyeingcon,
      BudgetYarnDyeingRepository $budgetyarndyeing,
      BudgetRepository $budget,
      StyleGmtColorSizeRepository $stylegmtcolorsize,
      SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
      ColorRepository $color) {
        $this->budgetyarndyeingcon = $budgetyarndyeingcon;
        $this->budgetyarndyeing = $budgetyarndyeing;
        $this->budget = $budget;
        $this->stylegmtcolorsize = $stylegmtcolorsize;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->color = $color;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetyarndyeingcons',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetyarndyeingcons', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetyarndyeingcons',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetyarndyeingcons', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $budgetyarndyeing=array_prepend(array_pluck($this->budgetyarndyeing->get(),'name','id'),'-Select-','');
      $budgetyarndyeingcons=array();
      $rows=$this->budgetyarndyeingcon->get();
      foreach($rows as $row){
      $budgetyarndyeingcon['id']=	$row->id;
      $budgetyarndyeingcon['budgetyarndyeing']=	$budgetyarndyeing[$row->budget_fabric_id];
      array_push($budgetyarndyeingcons,$budgetyarndyeingcon);
      }
      echo json_encode($budgetyarndyeingcons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $budgetyarndyeing=$this->budgetyarndyeing->find(request('budget_yarn_dyeing_id',0));
        $dataArr=$this->budgetyarndyeing
        ->selectRaw('
          budget_fabrics.budget_id,
          budget_fabrics.id as budget_fabric_id,
          sales_orders.sale_order_no,
          sales_orders.id as sale_order_id,
          budget_fabrics.fabric_cons,
          sum(budget_fabric_cons.grey_fab) as grey_fab,
          style_fabrication_stripes.id as style_fabrication_stripe_id,
          style_fabrication_stripes.color_id,
          colors.name as yarn_color,
          gmt_colors.name as gmt_color_name,
          style_fabrication_stripes.measurment,
          style_fabrication_stripes.feeder,
          style_fabrication_stripes.is_dye_wash,
          stripes.measurment as total_measurment,
          stripes.feeder as total_feeder,

          budget_yarn_dyeings.id as budget_yarn_dyeing_id,
          budget_yarn_dyeings.overhead_rate,
          budget_yarn_dyeing_cons.id as budget_yarn_dyeing_con_id,
          budget_yarn_dyeing_cons.bom_qty,
          budget_yarn_dyeing_cons.rate,
          budget_yarn_dyeing_cons.amount
          ')
        ->join('budget_fabrics',function($join){
          $join->on('budget_fabrics.id','=','budget_yarn_dyeings.budget_fabric_id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_fabrication_stripes',function($join){
          $join->on('style_fabrication_stripes.style_fabrication_id','=','style_fabrications.id');
        })
        ->join('style_colors',function($join){
          $join->on('style_colors.id','=','style_fabrication_stripes.style_color_id');
        })

        ->leftJoin(\DB::raw("(select style_fabrication_stripes.style_fabrication_id, 
        sum(style_fabrication_stripes.measurment) as measurment,
        sum(style_fabrication_stripes.feeder) as feeder
        from  style_fabrication_stripes
        group by style_fabrication_stripes.style_fabrication_id) stripes"), "stripes.style_fabrication_id", "=", "style_fabrications.id")
        
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
          $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
          $join->on('style_gmt_color_sizes.style_color_id','=','style_colors.id')
          ->whereNull('style_gmt_color_sizes.deleted_at');
        })
       
        ->join('budget_fabric_cons',function($join){
          $join->on('budget_fabrics.id','=','budget_fabric_cons.budget_fabric_id');
          $join->on('budget_fabric_cons.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id')
          ->whereNull('budget_fabric_cons.deleted_at');
        })
        ->join('colors',function($join){
          $join->on('colors.id','=','style_fabrication_stripes.color_id');
        })

        ->join('colors as gmt_colors',function($join){
          $join->on('gmt_colors.id','=','style_colors.color_id');
        })
        ->leftJoin('budget_yarn_dyeing_cons',function($join){
          $join->on('budget_yarn_dyeing_cons.style_fabrication_stripe_id','=','style_fabrication_stripes.id')
          ->on('budget_yarn_dyeing_cons.budget_yarn_dyeing_id','=','budget_yarn_dyeings.id')
          ->on('budget_yarn_dyeing_cons.sales_order_id','=','sales_orders.id')
          ->whereNull('budget_yarn_dyeing_cons.deleted_at');
        })
        ->orderBy('sales_orders.id')
        ->orderBy('budget_yarn_dyeing_cons.id')
        ->where([['budget_yarn_dyeings.id','=',request('budget_yarn_dyeing_id',0)]])
        ->groupBy([
          'budget_fabrics.budget_id',
          'budget_fabrics.id',
          'budget_fabrics.fabric_cons',
          'sales_orders.sale_order_no',
          'sales_orders.id',
          'budget_yarn_dyeings.id',
          'budget_yarn_dyeings.overhead_rate',
          'budget_yarn_dyeing_cons.id',
          'budget_yarn_dyeing_cons.bom_qty',
          'budget_yarn_dyeing_cons.rate',
          'budget_yarn_dyeing_cons.amount',
          'style_fabrication_stripes.id',
          'style_fabrication_stripes.color_id',
          'colors.name',
          'gmt_colors.name',
          'style_fabrication_stripes.measurment',
          'style_fabrication_stripes.feeder',
          'style_fabrication_stripes.is_dye_wash',
          'stripes.measurment',
          'stripes.feeder',
          'style_colors.id',
        ])
        ->get()
        ->map(function($dataArr) use($yesno){
          $stripeTotal=0;

          if($dataArr->total_measurment){
            $stripeTotal=$dataArr->total_measurment;
          }
          else{
            $stripeTotal=$dataArr->total_feeder;
          }
          $stripe=0;
          if($dataArr->measurment){
            $stripe=$dataArr->measurment;
          }
          else{
            $stripe=$dataArr->feeder;
          }

          $dataArr->req_qty=0;
          if($stripeTotal){
          $dataArr->req_qty=number_format(($dataArr->grey_fab/$stripeTotal)*$stripe,4,'.','');
          }
          $dataArr->is_dye_wash_name=$yesno[$dataArr->is_dye_wash];
          return $dataArr;

        });

        $saved = $dataArr->filter(function ($value) {
            if($value->budget_yarn_dyeing_con_id){
                return $value;
            }
        });
        $new = $dataArr->filter(function ($value) {
            if(!$value->budget_yarn_dyeing_con_id && $value->grey_fab){
                return $value;
            }
        });

        return Template::LoadView('Bom.BudgetYarnDyeingColorSizeMatrix',['dataArr'=>$new,'saved'=>$saved]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetYarnDyeingConRequest $request) {
		$budget_id=0;
		foreach($request->budget_yarn_dyeing_id as $index=>$budget_yarn_dyeing_id){
			$budget_id=$request->budget_id[$index];
				if($request->bom_qty[$index]){
        $overhead_amount=$request->bom_qty[$index]*$request->overhead_rate[$index];
				$budgetyarndyeingcon = $this->budgetyarndyeingcon->updateOrCreate(
				['budget_yarn_dyeing_id' => $budget_yarn_dyeing_id,'sales_order_id'=> $request->sales_order_id[$index],'style_fabrication_stripe_id' => $request->style_fabrication_stripe_id[$index]],
				['bom_qty' => $request->bom_qty[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index],'overhead_rate' =>$request->overhead_rate[$index],'overhead_amount' =>$overhead_amount]
				);
				}
			}
      
			$totalCost=$this->budget->totalCost($budget_id);
		  return response()->json(array('success' => true, 'id' => $budgetyarndyeingcon->id, 'budget_id' => $budget_id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);

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
    public function update(BudgetYarnDyeingConRequest $request, $id) {
        
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
