<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Template;
use App\Http\Requests\Purchase\PoTrimItemQtyRequest;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Purchase\PoTrimItemQtyRepository;
use App\Repositories\Contracts\Purchase\PoTrimItemRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;

class PoTrimItemQtyController extends Controller
{
   private $potrim;
   private $potrimitem;
   private $potrimitemqty;
   private $color;
   private $salesordergmtcolorsize;

	public function __construct(
		PoTrimRepository $potrim,
		PoTrimItemRepository $potrimitem,
		PoTrimItemQtyRepository $potrimitemqty,
		ColorRepository $color,
		SalesOrderGmtColorSizeRepository $salesordergmtcolorsize
	)
	{
		$this->potrim = $potrim;
        $this->potrimitem    = $potrimitem;
		$this->potrimitemqty = $potrimitemqty;
		$this->color = $color;
		$this->salesordergmtcolorsize = $salesordergmtcolorsize;
		$this->middleware('auth');
		$this->middleware('permission:view.potrimitemqties',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.potrimitemqties', ['only' => ['store']]);
		$this->middleware('permission:edit.potrimitemqties',   ['only' => ['update']]);
		$this->middleware('permission:delete.potrimitemqties', ['only' => ['destroy']]);
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
    	$potrimitem=$this->potrimitem
    	->join('po_trims',function($join){
			$join->on('po_trims.id','=','po_trim_items.po_trim_id');
		})
		->where([['po_trim_items.id','=',request('po_trim_item_id',0)]])
		->get(['po_trims.company_id'])
		->first();
		
		$trim=$this->salesordergmtcolorsize
		->selectRaw('
			jobs.currency_id,
			jobs.exch_rate,
			po_trims.currency_id as po_currency_id,
			budget_trims.budget_id,
			budget_trims.id as budget_trim_id,
			style_sizes.id as style_size_id,
			style_colors.id as style_color_id,
			sizes.name as size_name,
			sizes.code as size_code,
			gmt_colors.name as color_name,
			gmt_colors.code as color_code,
			trim_colors.name as trim_color_name,
			trim_colors.code as trim_color_code,
			style_sizes.sort_id as size_sort_id,
			style_colors.sort_id as color_sort_id,
			sales_order_gmt_color_sizes.plan_cut_qty,
			po_trim_items.id as po_trim_item_id,
			budget_trim_cons.id as budget_trim_con_id,
			budget_trim_cons.measurment,
			budget_trim_cons.cons,
			budget_trim_cons.process_loss,
			budget_trim_cons.req_cons,
			budget_trim_cons.rate as bom_rate,
			budget_trim_cons.amount as bom_amount,
			budget_trim_cons.trim_color,
			budget_trim_cons.measurment,
			budget_trim_cons.req_trim,
			budget_trim_cons.bom_trim,
			countries.name as country_name,
			sales_orders.sale_order_no,
			sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
			cumulatives.cumulative_qty,
			po_trim_item_qties.id as po_trim_item_qty_id,
			po_trim_item_qties.description,
			po_trim_item_qties.qty,
			po_trim_item_qties.rate,
			po_trim_item_qties.amount
		')
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
		->join('po_trim_items',function($join){
			$join->on('po_trim_items.budget_trim_id','=','budget_trims.id');
		})
		->join('po_trims',function($join){
			$join->on('po_trim_items.po_trim_id','=','po_trims.id');
		})
		->join('budget_trim_cons',function($join){
			$join->on('budget_trim_cons.budget_trim_id','=','po_trim_items.budget_trim_id')
			->on('budget_trim_cons.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id')
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
		->join('colors as gmt_colors',function($join){
			$join->on('gmt_colors.id','=','style_colors.color_id');
		})
		->join('colors as trim_colors',function($join){
			$join->on('trim_colors.id','=','budget_trim_cons.trim_color');
		})
		->join('countries',function($join){
			$join->on('countries.id','=','sales_order_countries.country_id');
		})
		->leftJoin(\DB::raw("(SELECT budget_trim_cons.id as budget_trim_con_id,sum(po_trim_item_qties.qty) as cumulative_qty FROM po_trim_item_qties join budget_trim_cons on budget_trim_cons.id =po_trim_item_qties.budget_trim_con_id join po_trim_items on  po_trim_items.id=po_trim_item_qties.po_trim_item_id  group by budget_trim_cons.id) cumulatives"), "cumulatives.budget_trim_con_id", "=", "budget_trim_cons.id")
		->leftJoin('po_trim_item_qties',function($join){
			$join->on('po_trim_item_qties.po_trim_item_id','=','po_trim_items.id');
			$join->on('po_trim_item_qties.budget_trim_con_id','=','budget_trim_cons.id');
		})
		->orderBy('sales_orders.id')
		->orderBy('style_colors.sort_id')
		->orderBy('style_sizes.sort_id')
		->where([['po_trim_items.id','=',request('po_trim_item_id',0)]])
		->where([['sales_orders.produced_company_id','=',$potrimitem->company_id]])
		->get()
		->map(function ($trim)  {
			$trim->prev_po_qty = $trim->cumulative_qty-$trim->qty;
			$trim->balance_qty = $trim->bom_trim-$trim->prev_po_qty;
			$trim->balance_amount = $trim->balance_qty*$trim->bom_rate;
			return $trim;
		});

		$saved = $trim->filter(function ($value) {
			if($value->po_trim_item_qty_id){
				return $value;
			}
		});
		$new = $trim->filter(function ($value) {
			if(!$value->po_trim_item_qty_id){
				return $value;
			}
		});
		$dropdown['purtrimqtyscs'] = "'".Template::loadView('Purchase.PoTrimItemQty',['colorsizes'=>$saved,'new'=>$new])."'";
		$row ['dropDown'] = $dropdown;
		echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoTrimItemQtyRequest $request)
    {
 		$potrimapproved=$this->potrim->find(request('po_trim_id',0));
		if($potrimapproved->approved_by){
			return response()->json(array('success' => false,  'message' => 'Trim Purchase Order is Approved, So Save Or Update not Possible'), 200);
		}

		$po_trim_item_id=$request->po_trim_item_id[1];

		$rcvs=$this->potrimitem
    	->join('po_trim_item_reports', function($join){
        $join->on('po_trim_item_reports.po_trim_item_id', '=', 'po_trim_items.id');
        })
		->join('inv_trim_rcv_items', function($join){
        $join->on('po_trim_item_reports.id', '=', 'inv_trim_rcv_items.po_trim_item_report_id');
        })
        ->where([['po_trim_items.id','=',$po_trim_item_id]])
        ->get();
        if($rcvs->first()){
        	return response()->json(array('success' => false, 'message' => 'Update Not Successfull Because Receive  Found'), 200);
        }

		$poTrimItemId=0;
		foreach($request->po_trim_item_id as $index=>$po_trim_item_id)
		{
			$poTrimItemId=$po_trim_item_id;
			if($po_trim_item_id && $request->qty[$index]>0)
			{
				$potrimitemqty = $this->potrimitemqty->updateOrCreate([
					'po_trim_item_id' => $po_trim_item_id,
					'budget_trim_con_id' => $request->budget_trim_con_id[$index]
				],[
					'qty' => $request->qty[$index],
					'rate' => $request->rate[$index],
					'amount' => $request->amount[$index],
					'description' => $request->description[$index]
				]);
			}
		}

		$poTrimItem=$this->potrimitem
		->join('budget_trims',function($join){
			$join->on('budget_trims.id','=','po_trim_items.budget_trim_id');
		})
		->join('itemclasses',function($join){
			$join->on('itemclasses.id','=','budget_trims.itemclass_id');
		})
		->where([['po_trim_items.id','=',$poTrimItemId]])
		->get(['itemclasses.sensivity_id'])
		->first();

		\DB::table('po_trim_item_reports')->where('po_trim_item_id', '=', $poTrimItemId)->delete();

		if($poTrimItem->sensivity_id==15)
		{
			\DB::insert("
			insert into  
			po_trim_item_reports
			(
				po_trim_item_id, 
				sensivity_id, 
				sales_order_id, 
				style_size_id,
				style_color_id,
				trim_color,
				measurment,
				description,
				rate,
				qty,
				amount
			) 
			SELECT 
			po_trim_items.id as po_trim_item_id,
			itemclasses.sensivity_id,
			sales_orders.id as sales_order_id,
			style_sizes.id as style_size_id,
			style_colors.id as style_color_id,
			budget_trim_cons.trim_color,
			budget_trim_cons.measurment,
			po_trim_item_qties.description,
			po_trim_item_qties.rate,
			sum(po_trim_item_qties.qty) as qty,
			sum(po_trim_item_qties.amount) as amount 
			from po_trim_items
			join po_trim_item_qties on po_trim_item_qties.po_trim_item_id = po_trim_items.id 
			and po_trim_item_qties.deleted_at is null
			inner join budget_trims on budget_trims.id= po_trim_items.budget_trim_id
			inner join itemclasses on itemclasses.id= budget_trims.itemclass_id
			inner join budget_trim_cons on budget_trim_cons.budget_trim_id = budget_trims.id
			and budget_trim_cons.id = po_trim_item_qties.budget_trim_con_id
			and budget_trim_cons.deleted_at is null 
			inner join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id
			and sales_order_gmt_color_sizes.deleted_at is null
			inner join sales_order_countries on  sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			inner join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			inner join jobs on jobs.id = sales_orders.job_id
			inner join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id 
			inner join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id 
			inner join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id 
			where (po_trim_items.id = ?)

			group by 
			po_trim_items.id,
			itemclasses.sensivity_id,
			sales_orders.id,
			style_sizes.id,
			style_colors.id,
			budget_trim_cons.trim_color,
			budget_trim_cons.measurment,
			po_trim_item_qties.description,
			po_trim_item_qties.rate 
			order by 
			sales_orders.id asc",[$poTrimItemId]);

		}

		else if($poTrimItem->sensivity_id==10)
		{
			\DB::insert("
			insert into  
			po_trim_item_reports
			(
				po_trim_item_id, 
				sensivity_id, 
				sales_order_id, 
				style_size_id,
				trim_color,
				measurment,
				description,
				rate,
				qty,
				amount
			) 
			SELECT 
			po_trim_items.id as po_trim_item_id,
			itemclasses.sensivity_id,
			sales_orders.id as sales_order_id,
			style_sizes.id as style_size_id,
			budget_trim_cons.trim_color,
			budget_trim_cons.measurment,
			po_trim_item_qties.description,
			po_trim_item_qties.rate,
			sum(po_trim_item_qties.qty) as qty,
			sum(po_trim_item_qties.amount) as amount 
			from po_trim_items
			join po_trim_item_qties on po_trim_item_qties.po_trim_item_id = po_trim_items.id 
			and po_trim_item_qties.deleted_at is null
			inner join budget_trims on budget_trims.id= po_trim_items.budget_trim_id
			inner join itemclasses on itemclasses.id= budget_trims.itemclass_id
			inner join budget_trim_cons on budget_trim_cons.budget_trim_id = budget_trims.id
			and budget_trim_cons.id = po_trim_item_qties.budget_trim_con_id
			and budget_trim_cons.deleted_at is null 
			inner join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id
			and sales_order_gmt_color_sizes.deleted_at is null
			inner join sales_order_countries on  sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			inner join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			inner join jobs on jobs.id = sales_orders.job_id
			inner join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id 
			inner join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id 
			inner join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id 
			where (po_trim_items.id = ?)

			group by 
			po_trim_items.id,
			itemclasses.sensivity_id,
			sales_orders.id,
			style_sizes.id,
			budget_trim_cons.trim_color,
			budget_trim_cons.measurment,
			po_trim_item_qties.description,
			po_trim_item_qties.rate 
			order by 
			sales_orders.id asc",[$poTrimItemId]);
		}

		else if($poTrimItem->sensivity_id==1)
		{
			\DB::insert("
			insert into  
			po_trim_item_reports
			(
				po_trim_item_id, 
				sensivity_id, 
				sales_order_id, 
				style_color_id,
				trim_color,
				measurment,
				description,
				rate,
				qty,
				amount
			) 
			SELECT 
			po_trim_items.id as po_trim_item_id,
			itemclasses.sensivity_id,
			sales_orders.id as sales_order_id,
			style_colors.id as style_color_id,
			budget_trim_cons.trim_color,
			budget_trim_cons.measurment,
			po_trim_item_qties.description,
			po_trim_item_qties.rate,
			sum(po_trim_item_qties.qty) as qty,
			sum(po_trim_item_qties.amount) as amount 
			from po_trim_items
			join po_trim_item_qties on po_trim_item_qties.po_trim_item_id = po_trim_items.id 
			and po_trim_item_qties.deleted_at is null
			inner join budget_trims on budget_trims.id= po_trim_items.budget_trim_id
			inner join itemclasses on itemclasses.id= budget_trims.itemclass_id
			inner join budget_trim_cons on budget_trim_cons.budget_trim_id = budget_trims.id
			and budget_trim_cons.id = po_trim_item_qties.budget_trim_con_id
			and budget_trim_cons.deleted_at is null 
			inner join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id
			and sales_order_gmt_color_sizes.deleted_at is null
			inner join sales_order_countries on  sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			inner join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			inner join jobs on jobs.id = sales_orders.job_id
			inner join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id 
			inner join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id 
			inner join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id 
			where (po_trim_items.id = ?)

			group by 
			po_trim_items.id,
			itemclasses.sensivity_id,
			sales_orders.id,
			style_colors.id,
			budget_trim_cons.trim_color,
			budget_trim_cons.measurment,
			po_trim_item_qties.description,
			po_trim_item_qties.rate 
			order by 
			sales_orders.id asc",[$poTrimItemId]);

		}

		else
		{
			\DB::insert("
			insert into  
			po_trim_item_reports
			(
				po_trim_item_id, 
				sensivity_id, 
				sales_order_id, 
				trim_color,
				measurment,
				description,
				rate,
				qty,
				amount
			) 
			SELECT 
			po_trim_items.id as po_trim_item_id,
			itemclasses.sensivity_id,
			sales_orders.id as sales_order_id,
			budget_trim_cons.trim_color,
			budget_trim_cons.measurment,
			po_trim_item_qties.description,
			po_trim_item_qties.rate,
			sum(po_trim_item_qties.qty) as qty,
			sum(po_trim_item_qties.amount) as amount 
			from po_trim_items
			join po_trim_item_qties on po_trim_item_qties.po_trim_item_id = po_trim_items.id 
			and po_trim_item_qties.deleted_at is null
			inner join budget_trims on budget_trims.id= po_trim_items.budget_trim_id
			inner join itemclasses on itemclasses.id= budget_trims.itemclass_id
			inner join budget_trim_cons on budget_trim_cons.budget_trim_id = budget_trims.id
			and budget_trim_cons.id = po_trim_item_qties.budget_trim_con_id
			and budget_trim_cons.deleted_at is null 
			inner join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id
			and sales_order_gmt_color_sizes.deleted_at is null
			inner join sales_order_countries on  sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			inner join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			inner join jobs on jobs.id = sales_orders.job_id
			inner join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id 
			inner join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id 
			inner join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id 
			where (po_trim_items.id = ?)

			group by 
			po_trim_items.id,
			itemclasses.sensivity_id,
			sales_orders.id,
			budget_trim_cons.trim_color,
			budget_trim_cons.measurment,
			po_trim_item_qties.description,
			po_trim_item_qties.rate 
			order by 
			sales_orders.id asc",[$poTrimItemId]);
		}
		if ($potrimitemqty) {
		return response()->json(array('success' => true, 'id' => $potrimitemqty->id,'po_trim_item_id' => $poTrimItemId,  'message' => 'Save Successfully'), 200);
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
    public function update(PoTrimItemQtyRequest $request, $id)
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