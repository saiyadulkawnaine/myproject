<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRqRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuItemRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralTransactionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvGeneralIsuItemRequest;

class InvGeneralIsuItemController extends Controller {

   
    private $invisu;
    private $invgeneralisurq;
    private $invgeneralisuitem;
    private $invgeneraltransaction;
    private $itemaccount;
    private $job;

    public function __construct(
        InvIsuRepository $invisu,
        InvGeneralIsuRqRepository $invgeneralisurq,
        InvGeneralIsuItemRepository $invgeneralisuitem,
        InvGeneralTransactionRepository $invgeneraltransaction, 
        ItemAccountRepository $itemaccount,
        JobRepository $job
    ) {
        
        $this->invisu = $invisu;
        $this->invgeneralisurq = $invgeneralisurq;
        $this->invgeneralisuitem = $invgeneralisuitem;
        $this->invgeneraltransaction = $invgeneraltransaction;
        $this->itemaccount = $itemaccount;
        $this->job = $job;
        $this->middleware('auth');
        //$this->middleware('permission:view.invgeneralrcv',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invgeneralrcv', ['only' => ['store']]);
        //$this->middleware('permission:edit.invgeneralrcv',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invgeneralrcv', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
          $inv_isu_id=request('inv_isu_id',0);
          $rows = $this->invisu
          ->join('inv_general_isu_items',function($join){
          $join->on('inv_general_isu_items.inv_isu_id','=','inv_isus.id');
          })
          ->join('inv_general_isu_rq_items',function($join){
          $join->on('inv_general_isu_rq_items.id','=','inv_general_isu_items.inv_general_isu_rq_item_id');
          })
          ->join('item_accounts',function($join){
          $join->on('inv_general_isu_items.item_account_id','=','item_accounts.id');
          })
          ->join('itemclasses', function($join){
          $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
          })
          ->join('itemcategories', function($join){
          $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
          })
          ->leftJoin('uoms', function($join){
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
          })
          ->leftJoin('sales_orders', function($join){
          $join->on('sales_orders.id', '=', 'inv_general_isu_rq_items.sale_order_id');
          })
          ->leftJoin('jobs', function($join){
          $join->on('jobs.id', '=', 'sales_orders.job_id');
          })
          ->leftJoin('styles', function($join){
          $join->on('styles.id', '=', 'jobs.style_id');
          })
          ->leftJoin('departments', function($join){
          $join->on('departments.id', '=', 'inv_general_isu_rq_items.department_id');
          })
          ->where([['inv_isus.id','=',$inv_isu_id]])
          ->orderBy('inv_isus.id','desc')
          ->get([
          'inv_general_isu_items.*',
          'itemcategories.name as category_name',
          'itemclasses.name as class_name',
          'item_accounts.sub_class_name',
          'item_accounts.item_description',
          'item_accounts.specification',
          'uoms.code as uom_name',
          'styles.style_ref',
          'sales_orders.sale_order_no',
          'departments.name as department_name',
          ])
          ->map(function($rows){
            return $rows;
          });
          echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvGeneralIsuItemRequest $request) {
        $invisu=$this->invisu->find($request->inv_isu_id);
        $itemaccount=$this->itemaccount->find($request->item_account_id);

        $invgeneraltransaction=$this->invgeneraltransaction
        ->selectRaw(
        'inv_general_transactions.store_id,
         inv_general_transactions.company_id,
        sum(inv_general_transactions.store_qty) as store_qty'
        )
        ->where([['inv_general_transactions.store_id','=',$request->store_id]])
        ->where([['inv_general_transactions.company_id','=',$invisu->company_id]])
        ->where([['inv_general_transactions.item_account_id','=',$request->item_account_id]])
        ->groupBy([
        'inv_general_transactions.store_id',
        'inv_general_transactions.company_id',
        'inv_general_transactions.item_account_id'
        ])
        ->get()
        ->first();
        if(!$invgeneraltransaction){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock'),200);
        }
        if($invgeneraltransaction->store_qty < $request->qty ){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock'),200);
        }

        $trans_type_id=2;
        \DB::beginTransaction();
        $invgeneralisuitem = $this->invgeneralisuitem->create(
        [
        'inv_isu_id'=> $request->inv_isu_id,         
        'item_account_id'=> $request->item_account_id,        
        'inv_general_isu_rq_item_id'=> $request->inv_general_isu_rq_item_id,        
        'store_id'=> $request->store_id,
        'qty' => $request->qty,
        'room'=> $request->room,     
        'rack'=> $request->rack,     
        'shelf'=> $request->shelf,
        'box'=> $request->box,
        'remarks' => $request->remarks     
        ]);

        $x=$request->qty;
        $total_store_amount=0;
        try
        {
          while($x > 0) {
			$invgeneralrcvitem=$this->invgeneraltransaction
			->selectRaw(
			'inv_general_transactions.store_id,
			inv_general_transactions.item_account_id,
			inv_general_transactions.inv_general_rcv_item_id,
			inv_general_rcv_items.store_rate,
			sum(inv_general_transactions.store_qty) as store_qty'
			)
			->join('inv_general_rcv_items',function($join){
			$join->on('inv_general_rcv_items.id','=','inv_general_transactions.inv_general_rcv_item_id');
			})
			->where([['inv_general_transactions.store_id','=',$request->store_id]])
			->where([['inv_general_transactions.company_id','=',$invisu->company_id]])
			->where([['inv_general_transactions.item_account_id','=',$request->item_account_id]])
			->groupBy([
			'inv_general_transactions.store_id',
			'inv_general_transactions.company_id',
			'inv_general_transactions.item_account_id',
			'inv_general_transactions.inv_general_rcv_item_id',
			'inv_general_rcv_items.store_rate',
			])
			->havingRaw('sum(inv_general_transactions.store_qty) > 0')
			->orderBy('inv_general_transactions.inv_general_rcv_item_id')
			->get()
			->map(function($invgeneralrcvitem){
			return $invgeneralrcvitem;
			})
			->first();

			if(!$invgeneralrcvitem){
			return response()->json(array('success' =>false , 'message'=>'Stock not found'),200);
			}

            if($x >= $invgeneralrcvitem->store_qty)
            {
              $iss_qty = $invgeneralrcvitem->store_qty;
            }
            else
            {
              $iss_qty = $x;
            }

            $store_amount=$iss_qty*$invgeneralrcvitem->store_rate;
            $total_store_amount+=$store_amount;
            $invgeneraltransaction=$this->invgeneraltransaction->create([
            'trans_type_id'=>$trans_type_id,
            'trans_date'=>$invisu->issue_date,
            'inv_general_rcv_item_id'=>$invgeneralrcvitem->inv_general_rcv_item_id,
            'inv_general_isu_item_id'=>$invgeneralisuitem->id,
            'item_account_id'=>$request->item_account_id,
            'company_id'=>$invisu->company_id,
            'supplier_id'=>$invisu->supplier_id,
            'store_id'=>$request->store_id,
            'store_qty' => $iss_qty*-1,
            'store_rate' => $invgeneralrcvitem->store_rate,
            'store_amount'=> $store_amount
            ]);
            $x=$x - $invgeneralrcvitem->store_qty;
          }
         $this->invgeneralisuitem->update($invgeneralisuitem->id,['amount'=>$total_store_amount,'rate'=>$total_store_amount/$request->qty]);

        }
        catch(EXCEPTION $e)
        {
          \DB::rollback();
          throw $e;
        }
        \DB::commit();

      if($invgeneralisuitem){
        return response()->json(array('success' =>true ,'id'=>$invgeneralisuitem->id, 'inv_isu_id'=>$request->inv_isu_id,'message'=>'Saved Successfully'),200);
      }
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
          $rows =$this->invgeneralisuitem
          ->join('inv_isus',function($join){
          $join->on('inv_isus.id','=','inv_general_isu_items.inv_isu_id');
          })
          ->join('inv_general_isu_rq_items',function($join){
          $join->on('inv_general_isu_rq_items.id','=','inv_general_isu_items.inv_general_isu_rq_item_id');
          })
          ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','inv_general_isu_rq_items.asset_quantity_cost_id');
          })
          
          
          ->join('item_accounts',function($join){
          $join->on('inv_general_isu_items.item_account_id','=','item_accounts.id');
          })
          ->join('itemclasses', function($join){
          $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
          })
          ->join('itemcategories', function($join){
          $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
          })
          ->leftJoin('uoms', function($join){
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
          })
          ->leftJoin('sales_orders', function($join){
          $join->on('sales_orders.id', '=', 'inv_general_isu_rq_items.sale_order_id');
          })
          ->where([['inv_general_isu_items.id','=',$id]])
          ->orderBy('inv_general_isu_items.id','desc')
          ->get([
          'inv_general_isu_items.*',
          'inv_isus.id as inv_isu_id',
          'itemcategories.name as category_name',
          'itemclasses.name as class_name',
          'item_accounts.sub_class_name',
          'item_accounts.id as item_id',
          'item_accounts.item_description as item_desc',
          'item_accounts.specification',
          'uoms.code as uom_code',
          'sales_orders.sale_order_no',
          'inv_general_isu_rq_items.department_id',
          'inv_general_isu_rq_items.purpose_id',
          'asset_quantity_costs.custom_no',
          ])
          ->map(function($rows){
            return $rows;
          })->first();
       
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
    public function update(InvGeneralIsuItemRequest $request, $id) {

    	/*$is_received=$this->invgeneralisuitem
        ->join('inv_general_rcv_items',function($join){
        $join->on('inv_general_rcv_items.inv_general_isu_item_id','=','inv_general_isu_items.id');
        })
        ->where([['inv_general_isu_items.id','=',$id]])
        ->get()
        ->first();
        if($is_received){
        return response()->json(array('success' =>false , 'message'=>'Received Found, So update not allowed'),200);
        }*/
      
        $invisu=$this->invisu->find($request->inv_isu_id);
        $itemaccount=$this->itemaccount->find($request->item_account_id);
        \DB::beginTransaction();
        $this->invgeneraltransaction->where([['inv_general_isu_item_id','=',$id]])->delete();

        $invgeneraltransaction=$this->invgeneraltransaction
        ->selectRaw(
        'inv_general_transactions.store_id,
        inv_general_transactions.company_id,
        sum(inv_general_transactions.store_qty) as store_qty'
        )
        ->where([['inv_general_transactions.store_id','=',$request->store_id]])
        ->where([['inv_general_transactions.company_id','=',$invisu->company_id]])
        ->where([['inv_general_transactions.item_account_id','=',$request->item_account_id]])
        ->groupBy([
        'inv_general_transactions.store_id',
        'inv_general_transactions.company_id',
        'inv_general_transactions.item_account_id'
        ])
        ->get()
        ->first();
        if(!$invgeneraltransaction){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock'),200);
        }

        if($invgeneraltransaction->store_qty < $request->qty ){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock'),200);
        }

        $trans_type_id=2;
        

        $invgeneralisuitem = $this->invgeneralisuitem->update($id,
        [
        //'inv_isu_id'=> $request->inv_isu_id,         
        'item_account_id'=> $request->item_account_id,        
        'inv_general_isu_rq_item_id'=> $request->inv_general_isu_rq_item_id,        
        'store_id'=> $request->store_id,
        'qty' => $request->qty,
        'room'=> $request->room,     
        'rack'=> $request->rack,     
        'shelf'=> $request->shelf,
        'box'=> $request->box,
        'remarks' => $request->remarks     
        ]);

        $x=$request->qty;
        $total_store_amount=0;
        try
        {
          while($x > 0) {
            $invgeneralrcvitem=$this->invgeneraltransaction
            ->selectRaw(
            'inv_general_transactions.store_id,
            inv_general_transactions.item_account_id,
            inv_general_transactions.inv_general_rcv_item_id,
            inv_general_rcv_items.store_rate,
            sum(inv_general_transactions.store_qty) as store_qty'
            )
            ->join('inv_general_rcv_items',function($join){
            $join->on('inv_general_rcv_items.id','=','inv_general_transactions.inv_general_rcv_item_id');
            })
            ->where([['inv_general_transactions.store_id','=',$request->store_id]])
            ->where([['inv_general_transactions.company_id','=',$invisu->company_id]])
            ->where([['inv_general_transactions.item_account_id','=',$request->item_account_id]])
            ->groupBy([
            'inv_general_transactions.store_id',
            'inv_general_transactions.item_account_id',
            'inv_general_transactions.company_id',
            'inv_general_transactions.inv_general_rcv_item_id',
            'inv_general_rcv_items.store_rate',
            ])
            ->havingRaw('sum(inv_general_transactions.store_qty) > 0')
            ->orderBy('inv_general_transactions.inv_general_rcv_item_id')
            ->get()
            ->map(function($invgeneralrcvitem){
            return $invgeneralrcvitem;
            })
            ->first();

            if(!$invgeneralrcvitem){
            return response()->json(array('success' =>false , 'message'=>'Stock not found'),200);
            }

            if($x >= $invgeneralrcvitem->store_qty)
            {
              $iss_qty = $invgeneralrcvitem->store_qty;
            }
            else
            {
              $iss_qty = $x;
            }

            $store_amount=$iss_qty*$invgeneralrcvitem->store_rate;
            $total_store_amount+=$store_amount;
            $invgeneraltransaction=$this->invgeneraltransaction->create([
            'trans_type_id'=>$trans_type_id,
            'trans_date'=>$invisu->issue_date,
            'inv_general_rcv_item_id'=>$invgeneralrcvitem->inv_general_rcv_item_id,
            'inv_general_isu_item_id'=>$id,
            'item_account_id'=>$request->item_account_id,
            'company_id'=>$invisu->company_id,
            'supplier_id'=>$invisu->supplier_id,
            'store_id'=>$request->store_id,
            'store_qty' => $iss_qty*-1,
            'store_rate' => $invgeneralrcvitem->store_rate,
            'store_amount'=> $store_amount
            ]);
            $x=$x - $invgeneralrcvitem->store_qty;
          }
         $this->invgeneralisuitem->update($id,['amount'=>$total_store_amount,'rate'=>$total_store_amount/$request->qty]);

        }
        catch(EXCEPTION $e)
        {
          \DB::rollback();
          throw $e;
        }
        \DB::commit();

      if($invgeneralisuitem){
        return response()->json(array('success' =>true ,'id'=>$id, 'inv_isu_id'=>$request->inv_isu_id,'message'=>'Saved Successfully'),200);
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
      return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
       /* if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }*/
    }


    public function getItem()
    {
          $invisu=$this->invisu->find(request('inv_isu_id',0));
          $rows=$this->invgeneralisurq
          ->join('inv_general_isu_rq_items', function($join){
            $join->on('inv_general_isu_rq_items.inv_general_isu_rq_id', '=', 'inv_general_isu_rqs.id');
          })
          ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','inv_general_isu_rq_items.asset_quantity_cost_id');
          })
          ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'inv_general_isu_rq_items.item_account_id');
          })
          ->join('itemclasses', function($join){
            $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
          })
          ->join('itemcategories', function($join){
            $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
          })
          ->leftJoin('uoms', function($join){
            $join->on('uoms.id', '=', 'item_accounts.uom_id');
          })
          ->leftJoin('sales_orders', function($join){
            $join->on('sales_orders.id', '=', 'inv_general_isu_rq_items.sale_order_id');
          })
          ->whereIn('itemcategories.identity',[9])
          ->where([['inv_general_isu_rqs.company_id','=',$invisu->company_id]])
          ->whereNotNull('inv_general_isu_rqs.final_approved_at')
          ->selectRaw(
          '
          itemcategories.name as category_name,
          itemclasses.name as class_name,
          item_accounts.id as item_account_id,
          item_accounts.sub_class_name,
          item_accounts.item_description,
          item_accounts.specification,
          uoms.code as uom_name,
          sales_orders.sale_order_no,
          inv_general_isu_rq_items.id,
          inv_general_isu_rq_items.department_id,
          inv_general_isu_rq_items.purpose_id,
          inv_general_isu_rq_items.qty,
          inv_general_isu_rqs.rq_no,
          asset_quantity_costs.custom_no
          ')
          ->get()
          ->map(function($rows){
          	$rows->item_desc=$rows->item_description.", ".$rows->specification;
            return $rows;
          });
          echo json_encode($rows);
        
    }

    public function getOrder()
    {

          $invisu=$this->invisu->find(request('inv_isu_id',0));
        
          $rows=$this->job
          ->join('sales_orders', function($join){
            $join->on('sales_orders.job_id', '=', 'jobs.id');
          })
          ->join('styles', function($join){
            $join->on('styles.id', '=', 'jobs.style_id');
          })
          ->leftJoin('buyers', function($join){
            $join->on('buyers.id', '=', 'styles.buyer_id');
          })
          ->leftJoin('companies', function($join){
            $join->on('companies.id', '=', 'jobs.company_id');
          })
          ->where([['jobs.company_id','=',$invisu->company_id]])
          ->selectRaw(
          '
          styles.style_ref,
          buyers.name as buyer_name,
          sales_orders.id,
          sales_orders.id as sale_order_id,
          sales_orders.sale_order_no,
          sales_orders.qty,
          sales_orders.rate,
          sales_orders.amount,
          companies.code as company_name
          ')
          ->get()
          ->map(function($rows){
            return $rows;
          });
          echo json_encode($rows);
        
    }
}