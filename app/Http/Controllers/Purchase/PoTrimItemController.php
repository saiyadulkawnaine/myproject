<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Purchase\PoTrimItemRepository;
use App\Repositories\Contracts\Bom\BudgetTrimRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PoTrimItemRequest;


class PoTrimItemController extends Controller
{
   private $potrim;
   private $potrimitem;
   private $budgettrim;

	public function __construct(PoTrimRepository $potrim,PoTrimItemRepository $potrimitem,BudgetTrimRepository $budgettrim)
	{
		$this->potrim = $potrim;
        $this->potrimitem = $potrimitem;
		$this->budgettrim = $budgettrim;
		$this->middleware('auth');
		$this->middleware('permission:view.potrimitems',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.potrimitems', ['only' => ['store']]);
		$this->middleware('permission:edit.potrimitems',   ['only' => ['update']]);
		$this->middleware('permission:delete.potrimitems', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    	$budgettrims=array();
		$rows=$this->budgettrim->join('itemclasses', function($join){
			$join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
		})
		->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
		})
		->join('budgets',function($join){
		$join->on('budgets.id','=','budget_trims.budget_id');
		})
		->join('jobs',function($join){
		$join->on('jobs.id','=','budgets.job_id');
		})
		->join('styles', function($join) {
		$join->on('styles.id', '=', 'jobs.style_id');
		})
		->join('buyers', function($join) {
		$join->on('buyers.id', '=', 'styles.buyer_id');
		})
		->join('uoms', function($join){
			$join->on('uoms.id', '=', 'budget_trims.uom_id');
		})
		->join('po_trim_items',function($join){
		$join->on('po_trim_items.budget_trim_id','=','budget_trims.id')
		->whereNull('po_trim_items.deleted_at');
		})
		->join('po_trims',function($join){
		$join->on('po_trims.id','=','po_trim_items.po_trim_id');
		})
		->where([['po_trims.id','=',request('po_trim_id',0)]])
		->orderBy('budget_trims.id','desc')
		->get([
		'budget_trims.*',
		'jobs.job_no',
		'styles.style_ref',
		'buyers.name as buyer_name',
		'itemclasses.name',
		'uoms.code',
		'po_trim_items.id',
		'po_trim_items.qty',
		'po_trim_items.rate as po_rate',
		'po_trim_items.amount as po_amount'
		]);
		$tot=0;
  		foreach($rows as $row){
	        $budgettrim['id']=	$row->id;
			$budgettrim['budget_id']=	$row->budget_id;
			$budgettrim['job_no']=	$row->job_no;
			$budgettrim['style_ref']=	$row->style_ref;
			$budgettrim['buyer_name']=	$row->buyer_name;
	        $budgettrim['item_account_id']= $row->itemclass_id;
			$budgettrim['item_account']=	$row->name;
	        $budgettrim['description']=	$row->description;
	        $budgettrim['specification']=	$row->specification;
	        $budgettrim['item_size']=	$row->item_size;
	        $budgettrim['sup_ref']=	$row->sup_ref;
	        $budgettrim['cons']=	$row->qty;
	        $budgettrim['rate']=	$row->po_rate;
	        $budgettrim['amount']=	$row->po_amount;
	        $budgettrim['uom']=	$row->code;
			$tot+=$row->amount;
	  		array_push($budgettrims,$budgettrim);
  		}
		echo json_encode($budgettrims);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoTrimItemRequest $request)
    {
    	$potrimapproved=$this->potrim->find($request->po_trim_id);
    	if($potrimapproved->approved_at){
    	  return response()->json(array('success' => false,  'message' => 'Trim Purchase Order is Approved, So Save Or Update not Possible'), 200);
    	}
		foreach($request->budget_trim_id as $index=>$budget_trim_id){
			if($request->po_trim_id){
				$potrimitem = $this->potrimitem->updateOrCreate(
				['po_trim_id' => $request->po_trim_id,'budget_trim_id' => $budget_trim_id],
				['qty' => '','rate' => '','amount' => '']
				);
			}
		}
		if ($potrimitem) {
			return response()->json(array('success' => true, 'id' => $potrimitem->id, 'message' => 'Save Successfully'), 200);
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
        //
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
    public function update(PoTrimItemRequest $request, $id)
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
    	$rcvs=$this->potrimitem
    	->join('po_trim_item_reports', function($join){
        $join->on('po_trim_item_reports.po_trim_item_id', '=', 'po_trim_items.id');
        })
		->join('inv_trim_rcv_items', function($join){
        $join->on('po_trim_item_reports.id', '=', 'inv_trim_rcv_items.po_trim_item_report_id');
        })
        ->where([['po_trim_items.id','=',$id]])
        ->get();
        if($rcvs->first()){
        	return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Receive  Found'), 200);
        }

        if($this->potrimitem->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
		else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }
    
	public function importTrim()
    {
    	$potrim = $this->potrim->find(request('po_trim_id',0));
    	$buyer_id=$potrim->buyer_id;
		$rows=$this->budgettrim
		->join('itemclasses', function($join){
			$join->on('itemclasses.id', '=', 'budget_trims.itemclass_id');
		})
		->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
		})
		->join('uoms', function($join){
			$join->on('uoms.id', '=', 'budget_trims.uom_id');
		})
		->join('budgets',function($join){
			$join->on('budgets.id','=','budget_trims.budget_id');
		})
		->join('budget_approvals',function($join){
	      $join->on('budgets.id','=','budget_approvals.budget_id');
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
		->join('sales_order_gmt_color_sizes',function($join){
			$join->on('sales_order_gmt_color_sizes.sale_order_country_id','=','sales_order_countries.id');
		})
		->join('budget_trim_cons',function($join){
			$join->on('budget_trim_cons.budget_trim_id','=','budget_trims.id');
			$join->on('budget_trim_cons.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
		})
		->join('styles', function($join) {
			$join->on('styles.id', '=', 'jobs.style_id');
		})
		->join('buyers', function($join) {
		$join->on('buyers.id', '=', 'styles.buyer_id');
		})
		->leftJoin(\DB::raw("(SELECT budget_trims.id as budget_trim_id,sum(po_trim_items.qty) as cumulative_qty FROM po_trim_items right join budget_trims on budget_trims.id = po_trim_items.budget_trim_id   group by budget_trims.id) cumulatives"), "cumulatives.budget_trim_id", "=", "budget_trims.id")
		->leftJoin('po_trim_items',function($join){
	        $join->on('po_trim_items.budget_trim_id','=','budget_trims.id');
			$join->where([['po_trim_items.po_trim_id','=',request('po_trim_id',0)]]);
        })
        ->when(request('style_ref'), function ($q) {
        return $q->where('styles.style_ref', 'LIKE',"%".request('style_ref', 0)."%");
        })
        ->when($buyer_id, function ($q) use($buyer_id) {
        return $q->where('styles.buyer_id', '=',$buyer_id);
        })
        ->when(request('job_no'), function ($q) {
        return $q->where('jobs.job_no', '=',request('job_no', 0));
        })
        ->when(request('budget_id'), function ($q) {
        return $q->where('budgets.budget_id', '=',request('budget_id', 0));
        })
        ->whereNotNull('budget_approvals.trim_final_approved_at')
		->where([['sales_orders.produced_company_id','=',request('company_id',0)]])
		->selectRaw(
		'
		budget_trims.id,
		budget_trims.description,
		budget_trims.sup_ref,
		budget_trims.cons,
		budget_trims.rate,
		budget_trims.amount,
		jobs.job_no,
		styles.style_ref,
		buyers.name as buyer_name,
		itemclasses.name as item_account,
		itemclasses.id as item_account_id,
		uoms.code,
		cumulatives.cumulative_qty,
		po_trim_items.id as po_trim_item_id

		')
		->groupby([
		'budget_trims.id',
		'budget_trims.description',
		'budget_trims.sup_ref',
		'budget_trims.cons',
		'budget_trims.rate',
		'budget_trims.amount',
		'jobs.job_no',
		'styles.style_ref',
		'buyers.name',
		'itemclasses.name',
		'itemclasses.id',
		'uoms.code',
		'cumulatives.cumulative_qty',
		'po_trim_items.id'
		])
		->get()
		->map(function ($rows) {
			$rows->prev_po_qty = $rows->cumulative_qty;
			$rows->balance = $rows->cons-$rows->prev_po_qty;
			return $rows;
		});

		$notsaved = $rows->filter(function ($value) {
			if(!$value->po_trim_item_id){
				return $value;
			}
		})
		->values();
		/*$budgettrims=array();
		foreach($notsaved as $row){
			array_push($budgettrims,$row);
		}*/
		echo json_encode($notsaved);
    }
}
