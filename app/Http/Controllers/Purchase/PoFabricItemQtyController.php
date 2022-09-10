<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Template;
use App\Http\Requests\Purchase\PoFabricItemQtyRequest;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Purchase\PoFabricItemQtyRepository;
use App\Repositories\Contracts\Purchase\PoFabricItemRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
class PoFabricItemQtyController extends Controller
{
   private $pofabric;
   private $pofabricitem;
   private $pofabricitemqty;
   private $color;
   private $salesordergmtcolorsize;

	public function __construct(
    PoFabricRepository $pofabric,
    PoFabricItemRepository $pofabricitem,
    PoFabricItemQtyRepository $pofabricitemqty,
    ColorRepository $color,
    SalesOrderGmtColorSizeRepository $salesordergmtcolorsize
  )
	{
      $this->pofabric    = $pofabric;
      $this->pofabricitem    = $pofabricitem;
      $this->pofabricitemqty = $pofabricitemqty;
      $this->color = $color;
      $this->salesordergmtcolorsize = $salesordergmtcolorsize;
      // $this->middleware('auth');
      // $this->middleware('permission:view.bulkfabricpurchases',   ['only' => ['create', 'index','show']]);
      // $this->middleware('permission:create.bulkfabricpurchases', ['only' => ['store']]);
      // $this->middleware('permission:edit.bulkfabricpurchases',   ['only' => ['update']]);
      // $this->middleware('permission:delete.bulkfabricpurchases', ['only' => ['destroy']]);
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
        $fabric=$this->salesordergmtcolorsize
        ->selectRaw('
        budget_fabrics.budget_id,
        budget_fabrics.id as budget_fabric_id,
        style_sizes.id as style_size_id,
        style_colors.id as style_color_id,
        sizes.name as size_name,
        sizes.code as size_code,
        gmt_colors.name as color_name,
        gmt_colors.code as color_code,
        fabric_colors.name as fabric_color_name,
        fabric_colors.code as fabric_color_code,
        style_sizes.sort_id as size_sort_id,
        style_colors.sort_id as color_sort_id,
        sales_order_gmt_color_sizes.plan_cut_qty,
        po_fabric_items.id as po_fabric_item_id,
        budget_fabric_cons.id as budget_fabric_con_id,
        budget_fabric_cons.dia,
        budget_fabric_cons.cons,
        budget_fabric_cons.process_loss,
        budget_fabric_cons.req_cons,
        budget_fabric_cons.rate as bom_rate,
        budget_fabric_cons.amount as bom_amount,
        budget_fabric_cons.fabric_color,
        budget_fabric_cons.measurment,
        budget_fabric_cons.fin_fab,
        budget_fabric_cons.grey_fab,
        countries.name as country_name,
        sales_orders.sale_order_no,
        sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
        cumulatives.cumulative_qty,
        po_fabric_item_qties.id as po_fabric_item_qty_id,
        po_fabric_item_qties.qty,
        po_fabric_item_qties.rate,
        po_fabric_item_qties.amount')
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
        ->join('po_fabric_items',function($join){
        $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('budget_fabric_cons',function($join){
        $join->on('budget_fabric_cons.budget_fabric_id','=','po_fabric_items.budget_fabric_id')
        ->on('budget_fabric_cons.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id')
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
        ->join('colors as gmt_colors',function($join){
        $join->on('gmt_colors.id','=','style_colors.color_id');
        })
        ->join('colors as fabric_colors',function($join){
        $join->on('fabric_colors.id','=','budget_fabric_cons.fabric_color');
        })

        ->join('countries',function($join){
        $join->on('countries.id','=','sales_order_countries.country_id');
        })
        ->leftJoin(\DB::raw("(SELECT budget_fabric_cons.id as budget_fabric_con_id,sum(po_fabric_item_qties.qty) as cumulative_qty FROM po_fabric_item_qties join budget_fabric_cons on budget_fabric_cons.id =po_fabric_item_qties.budget_fabric_con_id join po_fabric_items on  po_fabric_items.id=po_fabric_item_qties.po_fabric_item_id  group by budget_fabric_cons.id) cumulatives"), "cumulatives.budget_fabric_con_id", "=", "budget_fabric_cons.id")
        ->leftJoin('po_fabric_item_qties',function($join){
        $join->on('po_fabric_item_qties.po_fabric_item_id','=','po_fabric_items.id');
        $join->on('po_fabric_item_qties.budget_fabric_con_id','=','budget_fabric_cons.id');
        })
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
        ->where([['po_fabric_items.id','=',request('po_fabric_item_id',0)]])
        ->get()
        ->map(function ($fabric)  {
        $fabric->prev_po_qty = $fabric->cumulative_qty-$fabric->qty;
        $fabric->balance_qty = $fabric->grey_fab-$fabric->prev_po_qty;
        return $fabric;
        });

        $saved = $fabric->filter(function ($value) {
        if($value->po_fabric_item_qty_id){
        return $value;
        }
        });
        $new = $fabric->filter(function ($value) {
        if(!$value->po_fabric_item_qty_id){
        return $value;
        }
        });

        $dropdown['pofabricitemqtyscs'] = "'".Template::loadView('Purchase.PoFabricItemQty',['colorsizes'=>$saved,'new'=>$new])."'";
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoFabricItemQtyRequest $request)
    {
        $approved=$this->pofabric->find(request('po_fabric_id',0));
        if($approved->approved_at){
          return response()->json(array('success' => false,  'message' => 'Approved, Save Or Update not Possible'), 200);
        }
        $poFabricItemId=0;
        $fabricpurchaseqty=1;
        foreach($request->po_fabric_item_id as $index=>$po_fabric_item_id)
        {
          $poFabricItemId=$po_fabric_item_id;
          if($po_fabric_item_id && $request->qty[$index]>0){
            $fabricpurchaseqty = $this->pofabricitemqty->updateOrCreate(
            ['po_fabric_item_id' => $po_fabric_item_id,'budget_fabric_con_id' => $request->budget_fabric_con_id[$index]],
            ['qty' => $request->qty[$index],'rate' => $request->rate[$index],'amount' => $request->amount[$index]]
            );
          }
        }
        if ($fabricpurchaseqty) 
        {
        return response()->json(array('success' => true, 'id' => $fabricpurchaseqty->id,'po_fabric_item_id' => $poFabricItemId,  'message' => 'Save Successfully'), 200);
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
