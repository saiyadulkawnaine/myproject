<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Template;
use App\Http\Requests\Purchase\PoEmbServiceItemQtyRequest;
use App\Repositories\Contracts\Purchase\PoEmbServiceItemQtyRepository;
use App\Repositories\Contracts\Purchase\PoEmbServiceItemRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
class PoEmbServiceItemQtyController extends Controller
{
   private $poembserviceitem;
   private $poembserviceitemqty;
   private $color;
   private $salesordergmtcolorsize;
   private $colorrange;

	public function __construct(
    PoEmbServiceItemRepository $poembserviceitem,
    PoEmbServiceItemQtyRepository $poembserviceitemqty,
    ColorRepository $color,
    SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
    ColorrangeRepository $colorrange
  )
	{
      $this->poembserviceitem    = $poembserviceitem;
      $this->poembserviceitemqty = $poembserviceitemqty;
      $this->color = $color;
      $this->salesordergmtcolorsize = $salesordergmtcolorsize;
      $this->colorrange = $colorrange;
      $this->middleware('auth');
      $this->middleware('permission:view.poembserviceitemqties',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.poembserviceitemqties', ['only' => ['store']]);
      $this->middleware('permission:edit.poembserviceitemqties',   ['only' => ['update']]);
      $this->middleware('permission:delete.poembserviceitemqties', ['only' => ['destroy']]);
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
      $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
      $fabric=$this->poembserviceitem
      ->selectRaw('
      budget_embs.id as budget_emb_id,
      
      sales_orders.id as sales_order_id,
      sales_orders.sale_order_no,
      sales_orders.ship_date,
      countries.name as country_name,
      colors.name as color_name,
      sizes.name as size_name,
      style_embelishments.embelishment_size_id,
      embelishments.name as embelishment_name,
      embelishment_types.name as embelishment_type,
      gmtsparts.name as gmtspart_name,
      budget_emb_cons.id as budget_emb_con_id,
      budget_emb_cons.req_cons as bom_qty,
      budget_emb_cons.cons as bom_ratio,
      budget_emb_cons.rate as budget_rate,
      budget_emb_cons.amount as bom_amount,
      budget_emb_cons.overhead_rate,
      po_emb_service_items.id as po_emb_service_item_id,
      cumulatives.cumulative_qty,
      po_emb_service_item_qties.id as po_emb_service_item_qty_id,
      po_emb_service_item_qties.qty,
      po_emb_service_item_qties.rate,
      po_emb_service_item_qties.amount,
      po_emb_service_item_qties.remarks
      '
      )/* 
        budget_embs.rate as bom_rate,
      */
      ->join('po_emb_services',function($join){
      $join->on('po_emb_services.id','=','po_emb_service_items.po_emb_service_id');
      })
      ->join('budget_embs',function($join){
      $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
      })
      ->join('style_embelishments',function($join){
      $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
      })
      ->join('gmtsparts',function($join){
      $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
      })
      ->join('embelishments',function($join){
      $join->on('embelishments.id','=','style_embelishments.embelishment_id');
      })
      ->join('embelishment_types',function($join){
      $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
      })
      
      ->join('budget_emb_cons',function($join){
      $join->on('budget_emb_cons.budget_emb_id','=','budget_embs.id')
      ->whereNull('budget_emb_cons.deleted_at');
      })
      ->join('budgets',function($join){
      $join->on('budgets.id','=','budget_embs.budget_id');
      })
      ->join('sales_order_gmt_color_sizes',function($join){
      $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
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
      ->join('colors',function($join){
      $join->on('colors.id','=','style_colors.color_id');
      })
      
      ->join('countries',function($join){
      $join->on('countries.id','=','sales_order_countries.country_id');
      })
      ->leftJoin(\DB::raw("(SELECT budget_emb_cons.id as budget_emb_con_id,sum(po_emb_service_item_qties.qty) as cumulative_qty FROM po_emb_service_item_qties join budget_emb_cons on budget_emb_cons.id =po_emb_service_item_qties.budget_emb_con_id join po_emb_service_items on  po_emb_service_items.id=po_emb_service_item_qties.po_emb_service_item_id  group by budget_emb_cons.id) cumulatives"), "cumulatives.budget_emb_con_id", "=", "budget_emb_cons.id")
      ->leftJoin('po_emb_service_item_qties',function($join){
        $join->on('po_emb_service_item_qties.po_emb_service_item_id','=','po_emb_service_items.id');
        $join->on('po_emb_service_item_qties.budget_emb_con_id','=','budget_emb_cons.id');
        $join->whereNull('po_emb_service_item_qties.deleted_at');
      })
      ->orderBy('sales_orders.id')
      ->orderBy('style_colors.sort_id')
      ->orderBy('style_sizes.sort_id')
      ->where([['po_emb_service_items.id','=',request('po_emb_service_item_id',0)]])
      ->get()
      ->map(function ($fabric) use($embelishmentsize){
        $fabric->ship_date  =  date('d-M-Y',strtotime($fabric->ship_date));
        $fabric->embelishment_size = $embelishmentsize[$fabric->embelishment_size_id];
        $fabric->prev_po_qty = $fabric->cumulative_qty-$fabric->qty;
        $fabric->balance_qty = $fabric->bom_qty-$fabric->prev_po_qty;
        $fabric->bom_rate = $fabric->budget_rate+$fabric->overhead_rate;
        return $fabric;
      });
      $saved = $fabric->filter(function ($value) {
        if($value->po_emb_service_item_qty_id){
          return $value;
        }
      });
      $new = $fabric->filter(function ($value) {
        if(!$value->po_emb_service_item_qty_id){
          return $value;
        }
      });
      $dropdown['poembserviceitemqtyscs'] = "'".Template::loadView('Purchase.PoEmbServiceItemQty',['colorsizes'=>$saved,'new'=>$new])."'";
      $row ['dropDown'] = $dropdown;
      echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoEmbServiceItemQtyRequest $request)
    {
        $poembServiceItemId=0;
        $poembserviceitemqty=1;
        foreach($request->po_emb_service_item_id as $index=>$po_emb_service_item_id)
        {
          $poembServiceItemId=$po_emb_service_item_id;
          if($po_emb_service_item_id && $request->qty[$index]>0){
            $poembserviceitemqty = $this->poembserviceitemqty->updateOrCreate(
            ['po_emb_service_item_id' => $po_emb_service_item_id,'budget_emb_con_id'=>$request->budget_emb_con_id[$index]],
            [
              'qty' => $request->qty[$index],
              'rate' => $request->rate[$index],
              'amount' => $request->amount[$index],
              'remarks' => $request->remarks[$index],
            ]
            );
          }
        }
        if ($poembserviceitemqty) 
        {
        return response()->json(array('success' => true, 'id' => $poembserviceitemqty->id,'po_emb_service_item_id' => $poembServiceItemId,  'message' => 'Save Successfully'), 200);
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