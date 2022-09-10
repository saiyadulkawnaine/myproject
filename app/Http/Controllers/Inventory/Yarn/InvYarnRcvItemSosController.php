<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvItemRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvItemSosRepository;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Purchase\PoYarnItemRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnRcvItemSosRequest;

class InvYarnRcvItemSosController extends Controller {
    private $invyarnrcv;
    private $invyarnrcvitem;
    private $invyarnrcvitemsos;
    private $poyarnitem;
    private $poyarn;
    private $itemaccount;
    private $salesorder;

    public function __construct(
        InvYarnRcvRepository $invyarnrcv, 
        InvYarnRcvItemRepository $invyarnrcvitem,
        InvYarnRcvItemSosRepository $invyarnrcvitemsos,
        PoYarnRepository $poyarn,
        PoYarnItemRepository $poyarnitem,
        ItemAccountRepository $itemaccount,
        SalesOrderRepository $salesorder
    ) {
        $this->invyarnrcv = $invyarnrcv;
        $this->invyarnrcvitem = $invyarnrcvitem;
        $this->invyarnrcvitemsos = $invyarnrcvitemsos;
        $this->poyarnitem = $poyarnitem;
        $this->poyarn = $poyarn;
        $this->itemaccount = $itemaccount;
        $this->salesorder = $salesorder;
        $this->middleware('auth');
        /*$this->middleware('permission:view.prodgmtinvyarnrcvitems',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtinvyarnrcvitems', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtinvyarnrcvitems',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtinvyarnrcvitems', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $invyarnrcvitemsos=$this->invyarnrcvitemsos
        ->join('inv_yarn_rcv_items',function($join){
            $join->on('inv_yarn_rcv_items.id','=','inv_yarn_rcv_item_sos.inv_yarn_rcv_item_id');
        })
        ->join('po_yarn_items',function($join){
            $join->on('po_yarn_items.id','=','inv_yarn_rcv_items.po_yarn_item_id');
        })
        ->join('po_yarn_item_bom_qties',function($join){
            $join->on('po_yarn_item_bom_qties.id','=','inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id','=','po_yarn_item_bom_qties.sale_order_id');
        })
        ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->join('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','jobs.company_id');
        })
        ->leftJoin(\DB::raw("(
        SELECT 
        inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id,
        sum(inv_yarn_rcv_item_sos.qty) as cumulative_qty 
        FROM inv_yarn_rcv_item_sos 
        join po_yarn_item_bom_qties on po_yarn_item_bom_qties.id =inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id
        group by inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id
        ) cumulatives"), "cumulatives.po_yarn_item_bom_qty_id", "=", "po_yarn_item_bom_qties.id")
        ->where([['inv_yarn_rcv_items.id','=',request('inv_yarn_rcv_item_id')]])
        ->get([
            'inv_yarn_rcv_item_sos.id',
            'inv_yarn_rcv_item_sos.inv_yarn_rcv_item_id',
            'inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id',
            'inv_yarn_rcv_item_sos.qty',
            'inv_yarn_rcv_items.rate',
            'sales_orders.id as sales_order_id',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'styles.style_ref',
            'companies.name as company_name',
            'buyers.name as buyer_name',
            'po_yarn_item_bom_qties.qty as po_qty',
            'cumulatives.cumulative_qty as cumulative_qty',
        ])
        ->map(function($invyarnrcvitemsos){
            // $invyarnrcvitemsos->amount=$invyarnrcvitemsos->qty*$invyarnrcvitemsos->rate;
            // $invyarnrcvitemsos->cumulative_qty=$invyarnrcvitemsos->cumulative_qty-$invyarnrcvitemsos->qty;
            // $invyarnrcvitemsos->balance_qty=$invyarnrcvitemsos->po_qty-$invyarnrcvitemsos->cumulative_qty;
            $amount=$invyarnrcvitemsos->qty*$invyarnrcvitemsos->rate;
            $cumulative_qty=$invyarnrcvitemsos->cumulative_qty-$invyarnrcvitemsos->qty;
            $balance_qty=$invyarnrcvitemsos->po_qty-$cumulative_qty;
            $invyarnrcvitemsos->amount=number_format($amount,2);
            $invyarnrcvitemsos->cumulative_qty=number_format($cumulative_qty,4);
            $invyarnrcvitemsos->balance_qty=number_format($balance_qty,2);
            $invyarnrcvitemsos->qty=number_format($invyarnrcvitemsos->qty,2);
            $invyarnrcvitemsos->rate=number_format($invyarnrcvitemsos->rate,2);
            return $invyarnrcvitemsos;

        });
        echo json_encode($invyarnrcvitemsos);
		
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
    public function store(InvYarnRcvItemSosRequest $request) {
        //$this->invyarnrcvitemsos
        $invyarnrcvitemsos = $this->invyarnrcvitemsos->create($request->except(['id','sales_order_no']));
        if ($invyarnrcvitemsos) {
            return response()->json(array('success' => true, 'id' => $invyarnrcvitemsos->id,'inv_yarn_rcv_item_id'=>$request->inv_yarn_rcv_item_id, 'message' => 'Save Successfully'), 200);
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
        $invyarnrcvitemsos=$this->invyarnrcvitemsos
        ->join('inv_yarn_rcv_items',function($join){
            $join->on('inv_yarn_rcv_items.id','=','inv_yarn_rcv_item_sos.inv_yarn_rcv_item_id');
        })
        ->join('po_yarn_items',function($join){
            $join->on('po_yarn_items.id','=','inv_yarn_rcv_items.po_yarn_item_id');
        })
        ->join('po_yarn_item_bom_qties',function($join){
            $join->on('po_yarn_item_bom_qties.id','=','inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id','=','po_yarn_item_bom_qties.sale_order_id');
        })
        ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->join('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','jobs.company_id');
        })
        ->where([['inv_yarn_rcv_item_sos.id','=',$id]])
        ->get([
            'inv_yarn_rcv_item_sos.id',
            'inv_yarn_rcv_item_sos.inv_yarn_rcv_item_id',
            'inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id',
            'inv_yarn_rcv_item_sos.qty',
            'sales_orders.id as sales_order_id',
            'sales_orders.sale_order_no as sales_order_no',
            'sales_orders.ship_date',
            'styles.style_ref',
            'companies.name as company_name',
            'buyers.name as buyer_name',
        ])->first();
        $row ['fromData'] = $invyarnrcvitemsos;
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
    public function update(InvYarnRcvItemSosRequest $request, $id) {
        $invyarnrcvitemsos = $this->invyarnrcvitemsos->update($id,$request->except(['id','sales_order_no']));
        if ($invyarnrcvitemsos) {
            return response()->json(array('success' => true, 'id' => $id,'inv_yarn_rcv_item_id'=>$request->inv_yarn_rcv_item_id, 'message' => 'Save Successfully'), 200);
        }
                
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        return response()->json(array('success'=>false,'message'=>'Deleted Not Successfully'),200);

        if($this->invyarnrcvitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getSalesOrder(){
        //$salesorder=$this->salesorder
        $salesorder=$this->invyarnrcvitem
        ->join('po_yarn_items',function($join){
            $join->on('po_yarn_items.id','=','inv_yarn_rcv_items.po_yarn_item_id');
        })
        ->join('po_yarn_item_bom_qties',function($join){
            $join->on('po_yarn_item_bom_qties.po_yarn_item_id','=','po_yarn_items.id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id','=','po_yarn_item_bom_qties.sale_order_id');
        })
        ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->join('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','jobs.company_id');
        })

        ->leftJoin(\DB::raw("(
        SELECT 
        inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id,
        sum(inv_yarn_rcv_item_sos.qty) as cumulative_qty 
        FROM inv_yarn_rcv_item_sos 
        join po_yarn_item_bom_qties on po_yarn_item_bom_qties.id =inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id
        group by inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id
        ) cumulatives"), "cumulatives.po_yarn_item_bom_qty_id", "=", "po_yarn_item_bom_qties.id")
        ->where([['inv_yarn_rcv_items.id','=',request('inv_yarn_rcv_item_id',0)]])
        ->get([
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'styles.style_ref',
            'companies.name as company_name',
            'buyers.name as buyer_name',
            'po_yarn_item_bom_qties.id as po_yarn_item_bom_qty_id',
            'po_yarn_item_bom_qties.qty as po_qty',
            'cumulatives.cumulative_qty as cumulative_qty',
        ])
        ->map(function($salesorder){
            $salesorder->balance_qty=$salesorder->po_qty-$salesorder->cumulative_qty;
            return $salesorder;
        });
        echo json_encode($salesorder);
    }

    public function importyarn()
    {

            
    }

}