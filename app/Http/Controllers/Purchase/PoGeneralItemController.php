<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoGeneralRepository;
use App\Repositories\Contracts\Purchase\PoGeneralItemRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqItemRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvItemRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PoGeneralItemRequest;


class PoGeneralItemController extends Controller
{
   private $pogeneral;
   private $pogeneralitem;
   private $invpurreqitem;
   private $itemclass;
   private $itemcategory;
   private $invgeneralrcvitem;

	public function __construct(
		PoGeneralRepository $pogeneral,
		PoGeneralItemRepository $pogeneralitem,
		InvPurReqItemRepository $invpurreqitem,
		ItemclassRepository $itemclass,
		InvGeneralRcvItemRepository $invgeneralrcvitem,
		ItemcategoryRepository $itemcategory
	)
	{
        $this->pogeneral = $pogeneral;
        $this->pogeneralitem = $pogeneralitem;
		$this->invpurreqitem = $invpurreqitem;
		$this->itemclass     = $itemclass;
		$this->itemcategory  = $itemcategory;
		$this->invgeneralrcvitem = $invgeneralrcvitem;

		$this->middleware('auth');
		$this->middleware('permission:view.pogeneralitems',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.pogeneralitems', ['only' => ['store']]);
		$this->middleware('permission:edit.pogeneralitems',   ['only' => ['update']]);
		$this->middleware('permission:delete.pogeneralitems', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$rows=$this->pogeneral
		->selectRaw(
			'
			inv_pur_req_items.id as inv_pur_req_item_id,
			inv_pur_req_items.qty as req_qty,
			inv_pur_req_items.rate as req_rate,
			inv_pur_req_items.amount as req_amount,
			inv_pur_reqs.requisition_no,
			itemcategories.name as category_name,
			itemclasses.name as class_name,
	
			item_accounts.sub_class_name,
			item_accounts.item_description,
			item_accounts.specification,
			po_general_items.id,
			po_general_items.qty,
			po_general_items.rate,
			po_general_items.amount,
			cumulatives.cumulative_qty,
			uoms.code as uom_name
			')
    	->join('po_general_items', function($join){
			$join->on('po_general_items.po_general_id', '=', 'po_generals.id');
		})
		->join('inv_pur_req_items', function($join){
			$join->on('inv_pur_req_items.id', '=', 'po_general_items.inv_pur_req_item_id');
		})
		->join('item_accounts', function($join){
			$join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
		})
		->join('itemclasses', function($join){
			$join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
		})
		->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
		})
		->join('inv_pur_reqs', function($join){
			$join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
		})
		->leftJoin('uoms', function($join){
			$join->on('uoms.id', '=', 'item_accounts.uom_id');
		})
		->leftJoin(\DB::raw("(SELECT inv_pur_req_items.id as inv_pur_req_item_id,sum(po_general_items.qty) as cumulative_qty FROM po_general_items  join inv_pur_req_items on inv_pur_req_items.id = po_general_items.inv_pur_req_item_id   group by inv_pur_req_items.id) cumulatives"), "cumulatives.inv_pur_req_item_id", "=", "inv_pur_req_items.id")
		
		->where([['po_generals.id','=',request('po_general_id',0)]])
		->orderBy('po_general_items.id','desc')
		->groupBy([
			'inv_pur_req_items.id',
			'inv_pur_req_items.qty',
			'inv_pur_req_items.rate',
			'inv_pur_req_items.amount',
			'inv_pur_reqs.requisition_no',
			'itemcategories.name',
			'itemclasses.name',
	
			'item_accounts.sub_class_name',
			'item_accounts.item_description',
			'item_accounts.specification',
			'po_general_items.id',
			'po_general_items.qty',
			'po_general_items.rate',
			'po_general_items.amount',
			'cumulatives.cumulative_qty',
			'uoms.code'
		])
		->get()
		->map(function ($rows) {
			$rows->qty = number_format($rows->qty,2);
			$rows->amount = number_format($rows->amount,2);
			$rows->prev_po_qty = $rows->cumulative_qty-$rows->qty;
			$rows->balance_qty = $rows->req_qty-$rows->prev_po_qty;
			return $rows;
		});
		echo json_encode($rows);


    	
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    	$pogeneral=$this->pogeneral->find(request('po_general_id',0));
		$rows=$this->invpurreqitem
		->join('item_accounts', function($join){
			$join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
		})
		->join('itemclasses', function($join){
			$join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
		})
		->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
		})
		->join('inv_pur_reqs', function($join){
			$join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
		})
		->leftJoin('uoms', function($join){
			$join->on('uoms.id', '=', 'item_accounts.uom_id');
		})
		->leftJoin(\DB::raw("(SELECT inv_pur_req_items.id as inv_pur_req_item_id,sum(po_general_items.qty) as cumulative_qty FROM po_general_items  join inv_pur_req_items on inv_pur_req_items.id = po_general_items.inv_pur_req_item_id   group by inv_pur_req_items.id) cumulatives"), "cumulatives.inv_pur_req_item_id", "=", "inv_pur_req_items.id")
		->leftJoin('po_general_items',function($join){
	        $join->on('po_general_items.inv_pur_req_item_id','=','inv_pur_req_items.id');
			$join->where([['po_general_items.po_general_id','=',request('po_general_id',0)]]);
        })
       
        ->when(request('requisition_no'), function ($q) {
        return $q->where('inv_pur_reqs.requisition_no', '=',request('requisition_no', 0));
        })
         ->when(request('item_description'), function ($q) {
        return $q->where('item_accounts.item_description', 'LIKE',"%".request('item_description', 0)."%");
        })
        
		->whereIn('inv_pur_req_items.id',explode(',',request("inv_pur_req_item_id",0)))
		->selectRaw(
		'
		inv_pur_req_items.id as inv_pur_req_item_id,
		inv_pur_req_items.qty as req_qty,
		inv_pur_req_items.rate as req_rate,
		inv_pur_req_items.amount as req_amount,
		inv_pur_reqs.requisition_no,
		itemcategories.name as category_name,
		itemclasses.name as class_name,

		item_accounts.sub_class_name,
		item_accounts.item_description,
		item_accounts.specification,
		cumulatives.cumulative_qty,
		po_general_items.qty,
		uoms.code as uom_name
		')
		->get()
		->map(function ($rows) {
			$rows->prev_po_qty = $rows->cumulative_qty-$rows->qty;
			$rows->balance_qty = $rows->req_qty-$rows->prev_po_qty;
			return $rows;
		});;
		return Template::loadView('Purchase.PoGeneralItem',['rows'=>$rows]);
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoGeneralItemRequest $request)
    {
    	$pogeneral=$this->pogeneral->find($request->po_general_id);
		if($pogeneral->approved_at){
			return response()->json(array('success' => false,  'message' => 'General Item Purchase Order is Approved, Save or Update not Possible'), 200);
		}else {
			foreach($request->inv_pur_req_item_id as $index=>$inv_pur_req_item_id){
				if($request->po_general_id && $request->qty[$index]){
					$pogeneralitem = $this->pogeneralitem->updateOrCreate(
					['po_general_id' => $request->po_general_id,'inv_pur_req_item_id' => $inv_pur_req_item_id],
					['qty' => $request->qty[$index],'rate' => $request->rate[$index],'amount' => $request->amount[$index],'remarks' => $request->remarks[$index]]
					);
				}
			}
			if ($pogeneralitem) {
				return response()->json(array('success' => true, 'id' => $pogeneralitem->id, 'message' => 'Save Successfully'), 200);
			}
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
    	$rows=$this->pogeneral
    	->join('po_general_items', function($join){
			$join->on('po_general_items.po_general_id', '=', 'po_generals.id');
		})
		->join('inv_pur_req_items', function($join){
			$join->on('inv_pur_req_items.id', '=', 'po_general_items.inv_pur_req_item_id');
		})
		->join('item_accounts', function($join){
			$join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
		})
		->join('itemclasses', function($join){
			$join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
		})
		->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
		})
		->join('inv_pur_reqs', function($join){
			$join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
		})
		->leftJoin(\DB::raw("(SELECT inv_pur_req_items.id as inv_pur_req_item_id,sum(po_general_items.qty) as cumulative_qty FROM po_general_items  join inv_pur_req_items on inv_pur_req_items.id = po_general_items.inv_pur_req_item_id   group by inv_pur_req_items.id) cumulatives"), "cumulatives.inv_pur_req_item_id", "=", "inv_pur_req_items.id")
		->where([['po_general_items.id','=',$id]])
		->selectRaw(
		'
		inv_pur_req_items.id as inv_pur_req_item_id,
		inv_pur_req_items.qty as req_qty,
		inv_pur_req_items.rate as req_rate,
		inv_pur_req_items.amount as req_amount,
		inv_pur_reqs.requisition_no,
		itemcategories.name as category_name,
		itemclasses.name as class_name,
		item_accounts.sub_class_name,
		item_accounts.item_description,
		item_accounts.specification,
		po_general_items.id,
		po_general_items.po_general_id,
		po_general_items.remarks,
		po_general_items.qty,
		po_general_items.rate,
		po_general_items.amount,
		cumulatives.cumulative_qty
		')
		->get()
		->map(function ($rows) {
			$rows->prev_po_qty = $rows->cumulative_qty-$rows->qty;
			$rows->balance_qty = $rows->req_qty-$rows->prev_po_qty;
			return $rows;
		})
		->first();
		$row ['fromData'] = $rows;
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
    public function update(PoGeneralItemRequest $request, $id)
    {
    	$pogeneral=$this->pogeneral->find($request->po_general_id);
		if($pogeneral->approved_at){
			return response()->json(array('success' => false,  'message' => 'General Item Purchase Order is Approved, Update not Possible'), 200);
		}

		$invgeneralrcvitem=$this->invgeneralrcvitem
		->where([['po_general_item_id','=',$id]])
		->get()->first();

		if ($invgeneralrcvitem) {
			return response()->json(array('success' => false, 'message' => 'MRR Found. Update not possible'),200);
		}

    	if($request->qty<=0 || $request->qty==''){
    		return response()->json(array('success' => false,'id' => '','message' => 'Please insert qty'),200);
    	}

    	$pogeneralitem=$this->pogeneralitem->update($id,['qty'=>$request->qty,'rate'=>$request->rate,'amount'=>$request->amount,'remarks'=>$request->remarks]);
		if($pogeneralitem){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
		}
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->pogeneralitem->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
		else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }
    
	public function importItem()
    {
    	$pogeneral=$this->pogeneral->find(request('po_general_id',0));
		$rows=$this->invpurreqitem
		->join('item_accounts', function($join){
			$join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
		})
		->join('itemclasses', function($join){
			$join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
		})
		->join('itemcategories', function($join){
			$join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
		})
		->join('inv_pur_reqs', function($join){
			$join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
		})
		->leftJoin('uoms', function($join){
			$join->on('uoms.id', '=', 'item_accounts.uom_id');
		})

		->leftJoin(\DB::raw("(SELECT inv_pur_req_items.id as inv_pur_req_item_id,sum(po_general_items.qty) as cumulative_qty FROM po_general_items  join inv_pur_req_items on inv_pur_req_items.id = po_general_items.inv_pur_req_item_id   group by inv_pur_req_items.id) cumulatives"), "cumulatives.inv_pur_req_item_id", "=", "inv_pur_req_items.id")
		->leftJoin('po_general_items',function($join){
	        $join->on('po_general_items.inv_pur_req_item_id','=','inv_pur_req_items.id');
			$join->where([['po_general_items.po_general_id','=',request('po_general_id',0)]]);
        })
       
        ->when(request('requisition_no'), function ($q) {
        	return $q->where('inv_pur_reqs.requisition_no', '=',request('requisition_no', 0));
        })
         ->when(request('item_description'), function ($q) {
        	return $q->where('item_accounts.item_description', 'LIKE',"%".request('item_description', 0)."%");
        })
        
		->where([['inv_pur_reqs.company_id','=',$pogeneral->company_id]])
		->whereIn('inv_pur_reqs.pay_mode',[2,3,4])
		->where([['itemcategories.identity','=',9]])
		->whereNotNull('inv_pur_reqs.final_approved_at')
		->selectRaw(
		'
		inv_pur_req_items.id,
		inv_pur_req_items.qty as req_qty,
		inv_pur_req_items.rate as req_rate,
		inv_pur_req_items.amount as req_amount,
		inv_pur_reqs.requisition_no,
		itemcategories.name as category_name,
		itemclasses.name as class_name,

		item_accounts.sub_class_name,
		item_accounts.item_description,
		item_accounts.specification,
		po_general_items.id as po_general_item_id,
		cumulatives.cumulative_qty,
		po_general_items.qty,
		uoms.code as uom_name
		')
		->get()
		->map(function ($rows) {
			$rows->prev_po_qty = $rows->cumulative_qty-$rows->qty;
			$rows->balance_qty = $rows->req_qty-$rows->prev_po_qty;
			return $rows;
		});

		$notsaved = $rows->filter(function ($value) {
			if(!$value->po_general_item_id){
				return $value;
			}
		})->values();
		
		echo json_encode($notsaved);
    }
}
