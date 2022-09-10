<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Template;
use App\Repositories\Contracts\Purchase\PoDyeingServiceItemQtyRepository;
use App\Repositories\Contracts\Purchase\PoDyeingServiceItemRepository;
use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;

use App\Http\Requests\Purchase\PoDyeingServiceItemQtyRequest;

class PoDyeingServiceItemQtyController extends Controller
{
   private $podyeingservice;
   private $podyeingserviceitem;
   private $podyeingserviceitemqty;
   private $color;
   private $salesordergmtcolorsize;
   private $colorrange;

	public function __construct(
    PoDyeingServiceRepository $podyeingservice,
    PoDyeingServiceItemRepository $podyeingserviceitem,
    PoDyeingServiceItemQtyRepository $podyeingserviceitemqty,
    ColorRepository $color,
    SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
    ColorrangeRepository $colorrange
  )
	{
      $this->podyeingservice    = $podyeingservice;
      $this->podyeingserviceitem    = $podyeingserviceitem;
      $this->podyeingserviceitemqty = $podyeingserviceitemqty;
      $this->color = $color;
      $this->salesordergmtcolorsize = $salesordergmtcolorsize;
      $this->colorrange = $colorrange;
      $this->middleware('auth');
      $this->middleware('permission:view.podyeingserviceitemqties',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.podyeingserviceitemqties', ['only' => ['store']]);
      $this->middleware('permission:edit.podyeingserviceitemqties',   ['only' => ['update']]);
      $this->middleware('permission:delete.podyeingserviceitemqties', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
	  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
          
        $fabric=$this->podyeingserviceitem
        ->selectRaw('
        budget_fabric_prods.id as budget_fabric_prod_id,
        budget_fabric_prods.rate as bom_rate_1,
        budgetfabricprodcons.rate as bom_rate,
        sales_orders.id as sales_order_id,
        sales_orders.sale_order_no,
        sales_orders.ship_date,
        budget_fabric_cons.dia,
        budget_fabric_cons.measurment,
        budget_fabric_cons.fabric_color,
        fabric_colors.name as fabric_color_name,
        sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
        sum(budget_fabric_cons.grey_fab) as grey_fab,
        cumulatives.cumulative_qty,
        po_dyeing_service_items.id as po_dyeing_service_item_id,
        po_dyeing_service_item_qties.id as po_dyeing_service_item_qty_id,
        po_dyeing_service_item_qties.qty,
        po_dyeing_service_item_qties.pcs_qty,
        po_dyeing_service_item_qties.rate,
        po_dyeing_service_item_qties.amount,
        po_dyeing_service_item_qties.colorrange_id,
        po_dyeing_service_item_qties.pl_dia,
        po_dyeing_service_item_qties.pl_gsm_weight,
        po_dyeing_service_item_qties.pl_stitch_length,
        po_dyeing_service_item_qties.pl_spandex_stitch_length,
        po_dyeing_service_item_qties.pl_draft_ratio,
        po_dyeing_service_item_qties.pl_machine_gg
        '
        )
        ->join('po_dyeing_services',function($join){
        $join->on('po_dyeing_services.id','=','po_dyeing_service_items.po_dyeing_service_id');
        })
        ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        })
        ->join('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->join('budget_fabric_cons',function($join){
        $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');
        $join->on('budget_fabric_cons.grey_fab','>',0)
        ->whereNull('budget_fabric_cons.deleted_at');
        })
        ->join('budgets',function($join){
        $join->on('budgets.id','=','budget_fabrics.budget_id');
        })
        //->join('budget_approvals',function($join){
        //$join->on('budgets.id','=','budget_approvals.budget_id');
        //})
        ->join('sales_order_gmt_color_sizes',function($join){
        $join->on('sales_order_gmt_color_sizes.id','=','budget_fabric_cons.sales_order_gmt_color_size_id');
        })
        ->join('sales_order_countries',function($join){
        $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
        })
        ->join('sales_orders',function($join){
        $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
        })
        ->join('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->join('style_gmt_color_sizes',function($join){
        $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->join('style_sizes',function($join){
        $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
        $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('style_colors',function($join){
        $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        })
        ->join('colors as gmt_colors',function($join){
        $join->on('gmt_colors.id','=','style_colors.color_id');
        })
        ->join('colors as fabric_colors',function($join){
        $join->on('fabric_colors.id','=','budget_fabric_cons.fabric_color');
        })
        ->join('countries',function($join){
        $join->on('countries.id','=','sales_order_countries.country_id');
        })
        ->leftJoin(\DB::raw("(
        select
        avg(budget_fabric_prod_cons.rate) as rate, 

        budget_fabric_prod_cons.budget_fabric_prod_id,
        budget_fabric_prod_cons.fabric_color_id,
        budget_fabric_prod_cons.sales_order_id
        from budget_fabric_prod_cons

        where budget_fabric_prod_cons.deleted_at is null
        group by 
        budget_fabric_prod_cons.budget_fabric_prod_id,
        budget_fabric_prod_cons.fabric_color_id,
        budget_fabric_prod_cons.sales_order_id
        ) budgetfabricprodcons"), [
        ["budget_fabric_cons.fabric_color", "=", "budgetfabricprodcons.fabric_color_id"],
        ["sales_orders.id", "=", "budgetfabricprodcons.sales_order_id"],
        ["budget_fabric_prods.id", "=", "budgetfabricprodcons.budget_fabric_prod_id"]
        ])
        ->leftJoin(\DB::raw("(
        SELECT 
        po_dyeing_service_items.budget_fabric_prod_id,
        po_dyeing_service_item_qties.sales_order_id,
        po_dyeing_service_item_qties.dia,
        po_dyeing_service_item_qties.measurment,
        po_dyeing_service_item_qties.fabric_color_id,
        sum(po_dyeing_service_item_qties.qty) as cumulative_qty 
        FROM po_dyeing_service_item_qties 
        join po_dyeing_service_items on  po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
          and po_dyeing_service_items.deleted_at is null
          join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
        where po_dyeing_service_item_qties.deleted_at is null 
        group by
        po_dyeing_service_item_qties.sales_order_id,
        po_dyeing_service_items.budget_fabric_prod_id, 
        po_dyeing_service_item_qties.dia,
        po_dyeing_service_item_qties.measurment,
        po_dyeing_service_item_qties.fabric_color_id
        ) cumulatives"), [
          ["cumulatives.dia", "=", "budget_fabric_cons.dia"],
          ["cumulatives.measurment", "=", "budget_fabric_cons.measurment"],
          ["cumulatives.sales_order_id", "=", "sales_orders.id"],
          ["cumulatives.budget_fabric_prod_id", "=", "budget_fabric_prods.id"],
          ["cumulatives.fabric_color_id", "=", "budget_fabric_cons.fabric_color"]
        ])

        ->leftJoin('po_dyeing_service_item_qties',function($join){
        $join->on('po_dyeing_service_item_qties.po_dyeing_service_item_id','=','po_dyeing_service_items.id');
        $join->on('po_dyeing_service_item_qties.dia','=','budget_fabric_cons.dia');
        $join->on('po_dyeing_service_item_qties.measurment','=','budget_fabric_cons.measurment');
        $join->on('po_dyeing_service_item_qties.fabric_color_id','=','budget_fabric_cons.fabric_color');
        $join->on('po_dyeing_service_item_qties.sales_order_id','=','sales_orders.id');
        $join->whereNull('po_dyeing_service_item_qties.deleted_at');
        })
        //->whereNotNull('budget_approvals.fabricprod_final_approved_by')
        ->groupBy([
          'budget_fabric_prods.id',
          'budget_fabric_prods.rate',
          'budgetfabricprodcons.rate',
          'sales_orders.id',
          'sales_orders.sale_order_no',
          'sales_orders.ship_date',
          'budget_fabric_cons.dia',
          'budget_fabric_cons.measurment',
          'budget_fabric_cons.fabric_color',
          'fabric_colors.name',
          'cumulatives.cumulative_qty',
          'po_dyeing_service_items.id',
          'po_dyeing_service_item_qties.id',
          'po_dyeing_service_item_qties.qty',
          'po_dyeing_service_item_qties.pcs_qty',
          'po_dyeing_service_item_qties.rate',
          'po_dyeing_service_item_qties.amount',
          'po_dyeing_service_item_qties.colorrange_id',
          'po_dyeing_service_item_qties.pl_dia',
          'po_dyeing_service_item_qties.pl_gsm_weight',
          'po_dyeing_service_item_qties.pl_stitch_length',
          'po_dyeing_service_item_qties.pl_spandex_stitch_length',
          'po_dyeing_service_item_qties.pl_draft_ratio',
          'po_dyeing_service_item_qties.pl_machine_gg'
        ])
        ->where([['po_dyeing_service_items.id','=',request('po_dyeing_service_item_id',0)]])
        ->get()
        ->map(function ($fabric){
          $fabric->ship_date  =  date('d-M-Y',strtotime($fabric->ship_date));
          $fabric->bom_amount  =  $fabric->grey_fab*$fabric->bom_rate;
          $fabric->prev_po_qty = $fabric->cumulative_qty-$fabric->qty;
          $fabric->balance_qty = $fabric->grey_fab-$fabric->prev_po_qty;
          return $fabric;
        });

          $new = $fabric->filter(function ($value) {
          if(!$value->po_dyeing_service_item_qty_id){
          return $value;
          }
          });

          /*$saved = $fabric->filter(function ($value) {
          if($value->po_dyeing_service_item_qty_id){
          return $value;
          }
          });*/

      $fabricsaved=$this->podyeingserviceitem
      ->selectRaw('
      budget_fabric_prods.id as budget_fabric_prod_id,
      budget_fabric_prods.rate as bom_rate_1,
      budgetfabricprodcons.rate as bom_rate,
      sales_orders.id as sales_order_id,
      sales_orders.sale_order_no,
      sales_orders.ship_date,


      fabric_colors.name as fabric_color_name,
      budgetfabriccons.grey_fab as grey_fab,
      cumulatives.cumulative_qty,
      po_dyeing_service_items.id as po_dyeing_service_item_id,
      po_dyeing_service_item_qties.id as po_dyeing_service_item_qty_id,
      po_dyeing_service_item_qties.qty,
      po_dyeing_service_item_qties.pcs_qty,
      po_dyeing_service_item_qties.rate,
      po_dyeing_service_item_qties.amount,
      po_dyeing_service_item_qties.fabric_color_id as fabric_color,
      po_dyeing_service_item_qties.colorrange_id,
      po_dyeing_service_item_qties.dia,
      po_dyeing_service_item_qties.measurment,
      po_dyeing_service_item_qties.pl_dia,
      po_dyeing_service_item_qties.pl_gsm_weight,
      po_dyeing_service_item_qties.pl_stitch_length,
      po_dyeing_service_item_qties.pl_spandex_stitch_length,
      po_dyeing_service_item_qties.pl_draft_ratio,
      po_dyeing_service_item_qties.pl_machine_gg
      '
      )
      ->join('po_dyeing_services',function($join){
      $join->on('po_dyeing_services.id','=','po_dyeing_service_items.po_dyeing_service_id');
      })
      ->join('po_dyeing_service_item_qties',function($join){
      $join->on('po_dyeing_service_item_qties.po_dyeing_service_item_id','=','po_dyeing_service_items.id');
      $join->whereNull('po_dyeing_service_item_qties.deleted_at');
      })

      ->join('sales_orders',function($join){
      $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
      })

      ->join('budget_fabric_prods',function($join){
      $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
      })
      ->join('budget_fabrics',function($join){
      $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
      })

      ->leftJoin(\DB::raw("(
      select
      sum(budget_fabric_cons.grey_fab) as grey_fab, 
      budget_fabric_cons.budget_fabric_id,
      budget_fabric_cons.dia,
      budget_fabric_cons.measurment,
      budget_fabric_cons.fabric_color,
      sales_order_countries.sale_order_id
      from budget_fabric_cons
      left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id 
      left join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
      where budget_fabric_cons.deleted_at is null
      and budget_fabric_cons.grey_fab>0
      group by 
      budget_fabric_cons.budget_fabric_id,
      budget_fabric_cons.dia,
      budget_fabric_cons.measurment,
      budget_fabric_cons.fabric_color,
      sales_order_countries.sale_order_id
      ) budgetfabriccons"), [
      ["po_dyeing_service_item_qties.dia", "=", "budgetfabriccons.dia"],
      ["po_dyeing_service_item_qties.measurment", "=", "budgetfabriccons.measurment"],
      ["po_dyeing_service_item_qties.fabric_color_id", "=", "budgetfabriccons.fabric_color"],
      ["sales_orders.id", "=", "budgetfabriccons.sale_order_id"],
      ["budget_fabrics.id", "=", "budgetfabriccons.budget_fabric_id"]
      ])

      ->leftJoin(\DB::raw("(
      select
      avg(budget_fabric_prod_cons.rate) as rate, 
      
      budget_fabric_prod_cons.budget_fabric_prod_id,
      budget_fabric_prod_cons.fabric_color_id,
      budget_fabric_prod_cons.sales_order_id
      from budget_fabric_prod_cons
      
      where budget_fabric_prod_cons.deleted_at is null
      group by 
      budget_fabric_prod_cons.budget_fabric_prod_id,
      budget_fabric_prod_cons.fabric_color_id,
      budget_fabric_prod_cons.sales_order_id
      ) budgetfabricprodcons"), [
      ["po_dyeing_service_item_qties.fabric_color_id", "=", "budgetfabricprodcons.fabric_color_id"],
      ["sales_orders.id", "=", "budgetfabricprodcons.sales_order_id"],
      ["budget_fabric_prods.id", "=", "budgetfabricprodcons.budget_fabric_prod_id"]
      ])

      ->join('colors as fabric_colors',function($join){
      $join->on('fabric_colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
      })

      ->leftJoin(\DB::raw("(
      SELECT 
      po_dyeing_service_items.budget_fabric_prod_id,
      po_dyeing_service_item_qties.sales_order_id,
      po_dyeing_service_item_qties.dia,
      po_dyeing_service_item_qties.measurment,
      po_dyeing_service_item_qties.fabric_color_id,
      sum(po_dyeing_service_item_qties.qty) as cumulative_qty 
      FROM po_dyeing_service_item_qties 
      join po_dyeing_service_items on  po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
      and po_dyeing_service_items.deleted_at is null
      join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
      where po_dyeing_service_item_qties.deleted_at is null 
      group by
      po_dyeing_service_item_qties.sales_order_id,
      po_dyeing_service_items.budget_fabric_prod_id, 
      po_dyeing_service_item_qties.dia,
      po_dyeing_service_item_qties.measurment,
      po_dyeing_service_item_qties.fabric_color_id
      ) cumulatives"), [
      ["cumulatives.dia", "=", "budgetfabriccons.dia"],
      ["cumulatives.measurment", "=", "budgetfabriccons.measurment"],
      ["cumulatives.sales_order_id", "=", "sales_orders.id"],
      ["cumulatives.budget_fabric_prod_id", "=", "budget_fabric_prods.id"],
      ["cumulatives.fabric_color_id", "=", "budgetfabriccons.fabric_color"]
      ])
      ->where([['po_dyeing_service_items.id','=',request('po_dyeing_service_item_id',0)]])
      /*->toSql();
      dd($fabricsaved);
      die;*/
      ->get()
      ->map(function ($fabricsaved){
      $fabricsaved->ship_date  =  date('d-M-Y',strtotime($fabricsaved->ship_date));
      $fabricsaved->bom_amount  =  $fabricsaved->grey_fab*$fabricsaved->bom_rate;
      $fabricsaved->prev_po_qty = $fabricsaved->cumulative_qty-$fabricsaved->qty;
      $fabricsaved->balance_qty = $fabricsaved->grey_fab-$fabricsaved->prev_po_qty;
      return $fabricsaved;
      });
      
      $saved = $fabricsaved->filter(function ($value) {
      if($value->po_dyeing_service_item_qty_id){
      return $value;
      }
      });
        $dropdown['podyeingserviceitemqtyscs'] = "'".Template::loadView('Purchase.PoDyeingServiceItemQty',['colorsizes'=>$saved,'new'=>$new,'colorrange'=>$colorrange])."'";
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoDyeingServiceItemQtyRequest $request)
    {

      $approved=$this->podyeingservice->find(request('po_dyeing_service_id',0));
    	if($approved->approved_at){
    	  return response()->json(array('success' => false,  'message' => 'Approved, Save Or Update not Possible'), 200);
    	}

        $podyeingServiceItemId=0;
        $podyeingserviceitemqty=1;
        foreach($request->po_dyeing_service_item_id as $index=>$po_dyeing_service_item_id)
        {
          $podyeingServiceItemId=$po_dyeing_service_item_id;
          if($po_dyeing_service_item_id && $request->qty[$index]>0)
          {
            $podyeingserviceitemqty = $this->podyeingserviceitemqty->updateOrCreate(
              [
              'po_dyeing_service_item_id' => $po_dyeing_service_item_id,
              //'budget_fabric_prod_con_id'=>$request->budget_fabric_prod_con_id[$index],
              'sales_order_id'=>$request->sales_order_id[$index],
              'dia' => $request->dia[$index],
              'measurment' => $request->measurment[$index],
              'fabric_color_id' => $request->fabric_color_id[$index]
              ],
              [
              'qty' => $request->qty[$index],
              'pcs_qty' => $request->pcs_qty[$index],
              'rate' => $request->rate[$index],
              'amount' => $request->amount[$index],
              'colorrange_id' => $request->colorrange_id[$index],
              'pl_dia' => $request->pl_dia[$index],
              'pl_gsm_weight' => $request->pl_gsm_weight[$index],
              'pl_stitch_length' => $request->pl_stitch_length[$index],
              'pl_spandex_stitch_length' => $request->pl_spandex_stitch_length[$index],
              'pl_draft_ratio' => $request->pl_draft_ratio[$index],
              'pl_machine_gg' => $request->pl_machine_gg[$index]
              ]
            );
          }
        }
        if ($podyeingserviceitemqty) 
        {
        return response()->json(array('success' => true, 'id' => $podyeingserviceitemqty->id,'po_dyeing_service_item_id' => $podyeingServiceItemId,  'message' => 'Save Successfully'), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PurFabricQtyRequest $request, $id)
    {
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}