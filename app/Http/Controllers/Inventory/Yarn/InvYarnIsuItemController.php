<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuItemRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransactionRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnIsuItemRequest;

class InvYarnIsuItemController extends Controller {

    private $invisu;
    private $invyarnisu;
    private $invyarnisuitem;
    private $store;
    private $rqyarn;
    private $itemaccount;
    private $invyarntransaction;
    private $invyarnitem;
    private $poyarndyeing;

    public function __construct(
        InvIsuRepository $invisu,
        InvYarnIsuRepository $invyarnisu, 
        InvYarnIsuItemRepository $invyarnisuitem, 
        StoreRepository $store,
        RqYarnRepository $rqyarn,
        ItemAccountRepository $itemaccount,
        InvYarnTransactionRepository $invyarntransaction,
        InvYarnItemRepository $invyarnitem,
        PoYarnDyeingRepository $poyarndyeing
    ) {
        $this->invisu = $invisu;
        $this->invyarnisu = $invyarnisu;
        $this->invyarnisuitem = $invyarnisuitem;
        $this->store = $store;
        $this->rqyarn = $rqyarn;
        $this->itemaccount = $itemaccount;
        $this->invyarntransaction = $invyarntransaction;
        $this->invyarnitem = $invyarnitem;
        $this->poyarndyeing = $poyarndyeing;
        $this->middleware('auth');
        //$this->middleware('permission:view.invyarnisuitem',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invyarnisuitem', ['only' => ['store']]);
        //$this->middleware('permission:edit.invyarnisuitem',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invyarnisuitem', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })

        ->where([['itemcategories.identity','=',1]])
        ->orderBy('item_account_ratios.ratio','desc')
        ->get([
        'item_accounts.id',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);

        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
        $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
        $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
        }
       $rows = $this->invisu
       ->join('inv_yarn_isu_items',function($join){
        $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
       })
       ->join('inv_yarn_items',function($join){
        $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
        })
        ->join('item_accounts',function($join){
        $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->join('uoms',function($join){
        $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->join('colors',function($join){
        $join->on('colors.id','=','inv_yarn_items.color_id');
        })
        ->leftJoin('rq_yarn_items',function($join){
        $join->on('rq_yarn_items.id','=','inv_yarn_isu_items.rq_yarn_item_id');
        })
        ->leftJoin('rq_yarn_fabrications',function($join){
            $join->on('rq_yarn_fabrications.id','=','rq_yarn_items.rq_yarn_fabrication_id');
        })
        ->leftJoin(\DB::raw("(select pl_knit_items.id,sales_orders.sale_order_no,styles.style_ref,buyers.name as buyer_name
            from pl_knit_items
            join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
            join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
            join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id) pl_sales_orders"), "pl_sales_orders.id", "=", "rq_yarn_fabrications.pl_knit_item_id")
            ->leftJoin(\DB::raw("(select po_knit_service_item_qties.id,sales_orders.sale_order_no as sale_order_no_po,styles.style_ref as style_ref_po,buyers.name  as buyer_name_po
            from po_knit_service_item_qties
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id) po_sales_orders"), "po_sales_orders.id", "=", "rq_yarn_fabrications.po_knit_service_item_qty_id")
            ->leftJoin(\DB::raw("(select 
            po_yarn_dyeing_item_bom_qties.id,
            sales_orders.sale_order_no as sale_order_no_yd,
            styles.style_ref as style_ref_yd,
            buyers.name  as buyer_name_yd
            from po_yarn_dyeing_item_bom_qties
            join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.id=po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id
            join sales_orders on sales_orders.id=budget_yarn_dyeing_cons.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id) yd_sales_orders"), "yd_sales_orders.id", "=", "inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id")
        ->where([['inv_isus.id','=',request('inv_isu_id',0)]])
       ->orderBy('inv_yarn_isu_items.id','desc')
       ->get([
        'inv_yarn_isu_items.*',
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'colors.name as color_name',
        'itemcategories.name as itemcategory_name',
        'itemclasses.name as itemclass_name',
        'item_accounts.id as item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'uoms.code as uom_code',
        'pl_sales_orders.sale_order_no',
        'pl_sales_orders.style_ref',
        'pl_sales_orders.buyer_name',
        'po_sales_orders.sale_order_no_po',
        'po_sales_orders.style_ref_po',
        'po_sales_orders.buyer_name_po',
        'yd_sales_orders.sale_order_no_yd',
        'yd_sales_orders.style_ref_yd',
        'yd_sales_orders.buyer_name_yd'
       ])
       ->map(function($rows) use($yarnDropdown) {
            $rows->yarn_count=$rows->count."/".$rows->symbol;
            $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->sale_order_no_po;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->style_ref_po;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->buyer_name_po;
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->sale_order_no_yd;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->style_ref_yd;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->buyer_name_yd;
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
    private function remainder($dividend, $divisor)
    {
        $tempMod = (float)($dividend / $divisor);
        $tempMod = ($tempMod - (int)$tempMod)*$divisor;
        return $tempMod;
    }
    public function store(InvYarnIsuItemRequest $request) {

        $maintain_bag=0;

        $invisu=$this->invisu->find($request->inv_isu_id);
        $invyarnitem=$this->invyarnitem->find($request->inv_yarn_item_id);

        $invyarntransaction=$this->invyarntransaction
        ->selectRaw(
        'inv_yarn_transactions.store_id,
        sum(inv_yarn_transactions.store_qty) as store_qty'
        )
        ->where([['inv_yarn_transactions.store_id','=',$request->store_id]])
        //->where([['inv_yarn_transactions.company_id','=',$invisu->company_id]])
        ->where([['inv_yarn_transactions.inv_yarn_item_id','=',$request->inv_yarn_item_id]])
        ->groupBy([
        'inv_yarn_transactions.store_id',
        'inv_yarn_transactions.inv_yarn_item_id'
        ])
        ->get()
        ->first();
        if(!$invyarntransaction){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock'),200);
        }
        if($invyarntransaction->store_qty < $request->qty ){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock'),200);
        }

       
        
        $trans_type_id=2;
        \DB::beginTransaction();
        $invyarnisuitem=$this->invyarnisuitem->create([
            'inv_isu_id'=>$request->inv_isu_id,
            'store_id'=>$request->store_id,
            'qty'=>$request->qty,
            'returnable_qty'=>$request->returnable_qty,
            'returned_qty'=>$request->returned_qty,
            'remarks'=>$request->remarks,
            'inv_yarn_item_id'=>$request->inv_yarn_item_id,
            'rq_yarn_item_id'=>$request->rq_yarn_item_id,
            'po_yarn_dyeing_item_bom_qty_id'=>$request->po_yarn_dyeing_item_bom_qty_id,
        ]);

        $x=$request->qty;
        $total_store_amount=0;
        try
        {

        while($x > 0) {
            $invyarnrcvitem=$this->invyarntransaction
            ->selectRaw(
            'inv_yarn_transactions.store_id,
            inv_yarn_transactions.inv_yarn_item_id,
            inv_yarn_transactions.inv_yarn_rcv_item_id,
            inv_yarn_rcv_items.cone_per_bag,
            inv_yarn_rcv_items.wgt_per_cone,
            inv_yarn_rcv_items.store_rate,
            sum(inv_yarn_transactions.store_qty) as store_qty'
            )
            ->join('inv_yarn_rcv_items',function($join){
            $join->on('inv_yarn_rcv_items.id','=','inv_yarn_transactions.inv_yarn_rcv_item_id');
            })
            ->where([['inv_yarn_transactions.store_id','=',$request->store_id]])
            //->where([['inv_yarn_transactions.company_id','=',$invisu->company_id]])
            ->where([['inv_yarn_transactions.inv_yarn_item_id','=',$request->inv_yarn_item_id]])
            ->groupBy([
            'inv_yarn_transactions.store_id',
            'inv_yarn_transactions.inv_yarn_item_id',
            'inv_yarn_transactions.inv_yarn_rcv_item_id',
            'inv_yarn_rcv_items.cone_per_bag',
            'inv_yarn_rcv_items.wgt_per_cone',
            'inv_yarn_rcv_items.store_rate',
            ])
            ->havingRaw('sum(inv_yarn_transactions.store_qty) > 0')
            ->orderBy('inv_yarn_transactions.inv_yarn_rcv_item_id')
            ->get()
            ->map(function($invyarnrcvitem){
                $invyarnrcvitem->wgt_per_bag=$invyarnrcvitem->cone_per_bag*$invyarnrcvitem->wgt_per_cone;
                return $invyarnrcvitem;
            })
            ->first();

			if(!$invyarnrcvitem){
			return response()->json(array('success' =>false , 'message'=>'Stock not found'),200);
			}

            if($maintain_bag)
            {
                if($x >= $invyarnrcvitem->store_qty)
                {
                    $iss_qty = $invyarnrcvitem->store_qty;
                }
                else
                {
                    $mm=$this->remainder($x,$invyarnrcvitem->wgt_per_bag);
                    if($mm==0){
                        $iss_qty = $x;
                    }
                    else
                    {
                        $add_qty=$request->qty + ( $invyarnrcvitem->wgt_per_bag - ( $mm ) );
                        $mu=$request->qty - ($mm );
                        \DB::rollback();
                        return response()->json(array('success' =>false , 'message'=>'Insufficient Bag Stock, please issue either  '.$mu.' or '.$add_qty.' kg'),200);
                    }
                }
                $no_of_bag=$iss_qty/$invyarnrcvitem->wgt_per_bag;
            }
            else
            {
                if($x >= $invyarnrcvitem->store_qty)
                {
                    $iss_qty = $invyarnrcvitem->store_qty;
                }
                else
                {
                    $iss_qty = $x;
                }
            }

            $store_amount=$iss_qty*$invyarnrcvitem->store_rate;
            $total_store_amount+=$store_amount;
            $invyarntransaction=$this->invyarntransaction->create([
                'trans_type_id'=>$trans_type_id,
                'trans_date'=>$invisu->issue_date,
                'inv_yarn_rcv_item_id'=>$invyarnrcvitem->inv_yarn_rcv_item_id,
                'inv_yarn_isu_item_id'=>$invyarnisuitem->id,
                'inv_yarn_item_id'=>$request->inv_yarn_item_id,
                'company_id'=>$invisu->company_id,
                'supplier_id'=>$invyarnitem->supplier_id,
                'store_id'=>$request->store_id,
                'store_qty' => $iss_qty*-1,
                'store_rate' => $invyarnrcvitem->store_rate,
                'store_amount'=> $store_amount
            ]);
            $x=$x - $invyarnrcvitem->store_qty;
        } 
        $this->invyarnisuitem->update($invyarnisuitem->id,['amount'=>$total_store_amount,'rate'=>$total_store_amount/$request->qty]);
    }
    catch(EXCEPTION $e)
    {
        \DB::rollback();
        throw $e;
    }
    \DB::commit();
    return response()->json(array('success' =>true ,'id'=>$invyarnisuitem->id,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Saved Successfully'),200);
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
        $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })

        ->where([['itemcategories.identity','=',1]])
        ->orderBy('item_account_ratios.ratio','desc')
        ->get([
        'item_accounts.id',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);

        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
        $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
        $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
        }
       $rows = $this->invyarnisuitem
       ->join('inv_isus',function($join){
        $join->on('inv_yarn_isu_items.inv_isu_id','=','inv_isus.id');
       })
       ->join('inv_yarn_items',function($join){
        $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
        })
        ->join('item_accounts',function($join){
        $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->join('uoms',function($join){
        $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->join('colors',function($join){
        $join->on('colors.id','=','inv_yarn_items.color_id');
        })
        ->leftJoin('rq_yarn_items',function($join){
        $join->on('rq_yarn_items.id','=','inv_yarn_isu_items.rq_yarn_item_id');
        })
        ->leftJoin('rq_yarn_fabrications',function($join){
            $join->on('rq_yarn_fabrications.id','=','rq_yarn_items.rq_yarn_fabrication_id');
        })
        ->leftJoin(\DB::raw("(select pl_knit_items.id,sales_orders.sale_order_no,styles.style_ref,buyers.name as buyer_name
            from pl_knit_items
            join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
            join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
            join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id) pl_sales_orders"), "pl_sales_orders.id", "=", "rq_yarn_fabrications.pl_knit_item_id")
            ->leftJoin(\DB::raw("(select po_knit_service_item_qties.id,sales_orders.sale_order_no as sale_order_no_po,styles.style_ref as style_ref_po,buyers.name  as buyer_name_po
            from po_knit_service_item_qties
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id) po_sales_orders"), "po_sales_orders.id", "=", "rq_yarn_fabrications.po_knit_service_item_qty_id")

            ->leftJoin(\DB::raw("(select 
            po_yarn_dyeing_item_bom_qties.id,
            sales_orders.sale_order_no as sale_order_no_yd,
            styles.style_ref as style_ref_yd,
            buyers.name  as buyer_name_yd
            from po_yarn_dyeing_item_bom_qties
            join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.id=po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id
            join sales_orders on sales_orders.id=budget_yarn_dyeing_cons.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id) yd_sales_orders"), "yd_sales_orders.id", "=", "inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id")

        ->where([['inv_yarn_isu_items.id','=',$id]])
       ->orderBy('inv_yarn_isu_items.id','desc')
       ->get([
        'inv_yarn_isu_items.*',
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'colors.name as color_id',
        'itemcategories.name as itemcategory_name',
        'itemclasses.name as itemclass_name',
        'item_accounts.id as item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'uoms.code as uom_code',
        'pl_sales_orders.sale_order_no',
        'pl_sales_orders.style_ref',
        'pl_sales_orders.buyer_name',
        'po_sales_orders.sale_order_no_po',
        'po_sales_orders.style_ref_po',
        'po_sales_orders.buyer_name_po',
        'yd_sales_orders.sale_order_no_yd',
        'yd_sales_orders.style_ref_yd',
        'yd_sales_orders.buyer_name_yd'
       ])
       ->map(function($rows) use($yarnDropdown) {
            $rows->yarn_count=$rows->count."/".$rows->symbol;
            $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->sale_order_no_po;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->style_ref_po;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->buyer_name_po;
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->sale_order_no_yd;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->style_ref_yd;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->buyer_name_yd;

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
    public function update(InvYarnIsuItemRequest $request, $id) {
        $maintain_bag=0;
        $is_received=$this->invyarnisuitem
        ->join('inv_yarn_rcv_items',function($join){
        $join->on('inv_yarn_rcv_items.inv_yarn_isu_item_id','=','inv_yarn_isu_items.id');
        })
        ->where([['inv_yarn_isu_items.id','=',$id]])
        ->get()
        ->first();
        if($is_received){
        return response()->json(array('success' =>false , 'message'=>'Received Found, So update not allowed'),200);
        }


        $invisu=$this->invisu->find($request->inv_isu_id);
        $invyarnitem=$this->invyarnitem->find($request->inv_yarn_item_id);
        \DB::beginTransaction();
        $this->invyarntransaction->where([['inv_yarn_isu_item_id','=',$id]])->delete();
        $invyarntransaction=$this->invyarntransaction
        ->selectRaw(
        'inv_yarn_transactions.store_id,
        sum(inv_yarn_transactions.store_qty) as store_qty'
        )
        ->where([['inv_yarn_transactions.store_id','=',$request->store_id]])
        //->where([['inv_yarn_transactions.company_id','=',$invisu->company_id]])
        ->where([['inv_yarn_transactions.inv_yarn_item_id','=',$request->inv_yarn_item_id]])
        ->groupBy([
        'inv_yarn_transactions.store_id',
        'inv_yarn_transactions.inv_yarn_item_id'
        ])
        ->get()
        ->first();
        if($invyarntransaction->store_qty < $request->qty ){
            \DB::rollback();
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock'),200);
        }

       
      
        $trans_type_id=2;        
        $invyarnisuitem=$this->invyarnisuitem->update($id,[
            'store_id'=>$request->store_id,
            'inv_yarn_item_id'=>$request->inv_yarn_item_id,
            'rq_yarn_item_id'=>$request->rq_yarn_item_id,
            'po_yarn_dyeing_item_bom_qty_id'=>$request->po_yarn_dyeing_item_bom_qty_id,
            'qty'=>$request->qty,
            'returnable_qty'=>$request->returnable_qty,
            'returned_qty'=>$request->returned_qty,
            'remarks'=>$request->remarks,
        ]);

        $x=$request->qty;
        $total_store_amount=0;
        try
        {
            
            while($x > 0) {
                $invyarnrcvitem=$this->invyarntransaction
                ->selectRaw(
                'inv_yarn_transactions.store_id,
                inv_yarn_transactions.inv_yarn_item_id,
                inv_yarn_transactions.inv_yarn_rcv_item_id,
                inv_yarn_rcv_items.cone_per_bag,
                inv_yarn_rcv_items.wgt_per_cone,
                inv_yarn_rcv_items.store_rate,
                sum(inv_yarn_transactions.store_qty) as store_qty'
                )
                ->join('inv_yarn_rcv_items',function($join){
                $join->on('inv_yarn_rcv_items.id','=','inv_yarn_transactions.inv_yarn_rcv_item_id');
                })
                ->where([['inv_yarn_transactions.store_id','=',$request->store_id]])
                //->where([['inv_yarn_transactions.company_id','=',$invisu->company_id]])
                ->where([['inv_yarn_transactions.inv_yarn_item_id','=',$request->inv_yarn_item_id]])
                ->groupBy([
                'inv_yarn_transactions.store_id',
                'inv_yarn_transactions.inv_yarn_item_id',
                'inv_yarn_transactions.inv_yarn_rcv_item_id',
                'inv_yarn_rcv_items.cone_per_bag',
                'inv_yarn_rcv_items.wgt_per_cone',
                'inv_yarn_rcv_items.store_rate',
                ])
                ->havingRaw('sum(inv_yarn_transactions.store_qty) > 0')
                ->orderBy('inv_yarn_transactions.inv_yarn_rcv_item_id')
                ->get()
                ->map(function($invyarnrcvitem){
                $invyarnrcvitem->wgt_per_bag=$invyarnrcvitem->cone_per_bag*$invyarnrcvitem->wgt_per_cone;
                return $invyarnrcvitem;
                })
                ->first();

				if(!$invyarnrcvitem){
					return response()->json(array('success' =>false , 'message'=>'Stock not found'),200);
				}

                if($maintain_bag)
                {
                    if($x >= $invyarnrcvitem->store_qty)
                    {
                        $iss_qty = $invyarnrcvitem->store_qty;
                    }
                    else
                    {
                        $mm=$this->remainder($x,$invyarnrcvitem->wgt_per_bag);
                        if($mm==0){
                            $iss_qty = $x;
                        }
                        else{
                            $add_qty=$request->qty + ( $invyarnrcvitem->wgt_per_bag - ( $mm) );
                            $mu=$request->qty - ($mm);
                            
                            \DB::rollback();
                            return response()->json(array('success' =>false , 'message'=>'Insufficient Bag Stock, please issue either  '.$mu.' or '.$add_qty.' kg'),200);
                        }
                    }
                    $no_of_bag=$iss_qty/$invyarnrcvitem->wgt_per_bag;
                }
                else
                {
                    if($x >= $invyarnrcvitem->store_qty)
                    {
                        $iss_qty = $invyarnrcvitem->store_qty;
                    }
                    else
                    {
                        $iss_qty = $x;
                    }
                }
                $store_amount=$iss_qty*$invyarnrcvitem->store_rate;
                $total_store_amount+=$store_amount;
                $invyarntransaction=$this->invyarntransaction->create([
                    'trans_type_id'=>$trans_type_id,
                    'trans_date'=>$invisu->issue_date,
                    'inv_yarn_rcv_item_id'=>$invyarnrcvitem->inv_yarn_rcv_item_id,
                    'inv_yarn_isu_item_id'=>$id,
                    'inv_yarn_item_id'=>$request->inv_yarn_item_id,
                    'company_id'=>$invisu->company_id,
                    'supplier_id'=>$invyarnitem->supplier_id,
                    'store_id'=>$request->store_id,
                    'store_qty' => $iss_qty*-1,
                    'store_rate' => $invyarnrcvitem->store_rate,
                    'store_amount'=>  $store_amount
                ]);
                $x=$x - $invyarnrcvitem->store_qty;
            }
            $this->invyarnisuitem->update($id,['amount'=>$total_store_amount,'rate'=>$total_store_amount/$request->qty]); 
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        if($invyarnisuitem){
            return response()->json(array('success'=> true, 'id' =>$id,'inv_isu_id'=>$request->inv_isu_id, 'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        return response()->json(array('success'=>false,'message'=>'Deleted Not Possible'),200);

        $is_received=$this->invyarnisuitem
        ->join('inv_yarn_rcv_items',function($join){
        $join->on('inv_yarn_rcv_items.inv_yarn_isu_item_id','=','inv_yarn_isu_items.id');
        })
        ->where([['inv_yarn_isu_items.id','=',$id]])
        ->get()
        ->first();
        if($is_received){
        return response()->json(array('success' =>false , 'message'=>'Received Found, So delete not allowed'),200);
        }
        if($this->invisu->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }

    public function getYarnItem()
    {
        $invisu=$this->invisu->find(request('inv_isu_id',0));

        $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })

        ->where([['itemcategories.identity','=',1]])
        ->orderBy('item_account_ratios.ratio','desc')
        ->get([
        'item_accounts.id',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);

        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
        $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
        $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
        }

        if($invisu->isu_against_id==0){
            $invyarnitem=$this->invyarnitem
            ->join('item_accounts',function($join){
            $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
            })
            ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
            })
            ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
            })
            ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->join('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->join('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->join('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id');
            })
            ->get([
            'inv_yarn_items.id as inv_yarn_item_id',
            'inv_yarn_items.lot',
            'inv_yarn_items.brand',
            'colors.name as color_name',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',
            ])
            ->map(function($invyarnitem) use($yarnDropdown) {
                $invyarnitem->yarn_count=$invyarnitem->count."/".$invyarnitem->symbol;
                $invyarnitem->yarn_type=$invyarnitem->yarn_type;
                $invyarnitem->composition=isset($yarnDropdown[$invyarnitem->item_account_id])?$yarnDropdown[$invyarnitem->item_account_id]:'';
                return $invyarnitem;
            }); 
            echo json_encode($invyarnitem);

        }
        else if($invisu->isu_against_id==9){

        $invyarnitem=$this->poyarndyeing
        ->join('po_yarn_dyeing_items',function($join){
            $join->on('po_yarn_dyeings.id','=','po_yarn_dyeing_items.po_yarn_dyeing_id');
        })

        
        ->join('po_yarn_dyeing_item_bom_qties',function($join){
            $join->on('po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id', '=' , 'po_yarn_dyeing_items.id');
         })
        ->join('budget_yarn_dyeing_cons',function($join){
            $join->on('budget_yarn_dyeing_cons.id', '=' , 'po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id');
         })

        ->join('sales_orders',function($join){
            $join->on('budget_yarn_dyeing_cons.sales_order_id', '=' , 'sales_orders.id');
         })

        ->join('style_fabrication_stripes',function($join){
            $join->on('style_fabrication_stripes.id', '=' , 'budget_yarn_dyeing_cons.style_fabrication_stripe_id');
         })
        ->join('style_colors',function($join){
            $join->on('style_colors.id', '=' , 'style_fabrication_stripes.style_color_id');
         })
        ->leftJoin('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
         })
        ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
         })
        
        
        ->leftJoin('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','po_yarn_dyeing_items.inv_yarn_item_id'); 
        })
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_yarn_items.item_account_id'); 
        })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','inv_yarn_items.supplier_id'); 
        })
        ->leftJoin('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->join('uoms',function($join){
        $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id'); 
        })
        ->join('colors as gmt_colors',function($join){
          $join->on('gmt_colors.id','=','style_colors.color_id');
        })
        ->join('colors as dyed_yarn_colors',function($join){
          $join->on('dyed_yarn_colors.id','=','style_fabrication_stripes.color_id');
        })
		->when(request('po_no'), function ($q) {
		return $q->where('po_yarn_dyeings.po_no', '=', request('po_no', 0));
		})
        ->whereNotNull('po_yarn_dyeings.approved_by')
        ->where([['po_yarn_dyeings.company_id','=',$invisu->company_id]])
		->where([['po_yarn_dyeings.supplier_id','=',$invisu->supplier_id]])
        ->orderBy('po_yarn_dyeing_items.id','desc')
        ->get([
            'po_yarn_dyeings.po_no as rq_no',
            'po_yarn_dyeing_item_bom_qties.id',
            'po_yarn_dyeing_item_bom_qties.id as po_yarn_dyeing_item_bom_qty_id',
            'po_yarn_dyeing_item_bom_qties.qty',
            'inv_yarn_items.id as inv_yarn_item_id',
            'inv_yarn_items.lot',
            'inv_yarn_items.brand',
            'colors.name as color_name',
            'gmt_colors.name as gmt_color_name',
            'dyed_yarn_colors.name as dyed_yarn_color_name',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            'buyers.name as buyer_name',
        ])

        ->map(function($invyarnitem) use($yarnDropdown){
            $invyarnitem->yarn_count=$invyarnitem->count."/".$invyarnitem->symbol;
            $invyarnitem->yarn_type=$invyarnitem->yarn_type;
            $invyarnitem->composition=isset($yarnDropdown[$invyarnitem->item_account_id])?$yarnDropdown[$invyarnitem->item_account_id]:'';
            return $invyarnitem;
        });
        echo json_encode($invyarnitem);
        }
        else if($invisu->isu_against_id==102){
            $invyarnitem=$this->rqyarn
            ->join('rq_yarn_fabrications',function($join){
            $join->on('rq_yarns.id','=','rq_yarn_fabrications.rq_yarn_id');
            })
            ->join('rq_yarn_items',function($join){
            $join->on('rq_yarn_items.rq_yarn_fabrication_id','=','rq_yarn_fabrications.id');
            })
            
            ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','rq_yarn_items.inv_yarn_item_id');
            })
            ->join('item_accounts',function($join){
            $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
            })
            ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
            })
            ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
            })
            ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->join('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->join('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->join('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id');
            })
            ->leftJoin(\DB::raw("(select pl_knit_items.id,sales_orders.sale_order_no,styles.style_ref,buyers.name as buyer_name
            from pl_knit_items
            join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
            join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
            join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id) pl_sales_orders"), "pl_sales_orders.id", "=", "rq_yarn_fabrications.pl_knit_item_id")
            ->leftJoin(\DB::raw("(select po_knit_service_item_qties.id,sales_orders.sale_order_no as sale_order_no_po,styles.style_ref as style_ref_po,buyers.name  as buyer_name_po
            from po_knit_service_item_qties
            join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join buyers on buyers.id=styles.buyer_id) po_sales_orders"), "po_sales_orders.id", "=", "rq_yarn_fabrications.po_knit_service_item_qty_id")
            ->when(request('rq_no'), function ($q) {
            return $q->where('rq_yarns.rq_no', '=', request('rq_no', 0));
            })
            ->where([['rq_yarns.company_id','=',$invisu->company_id]])
            ->where([['rq_yarns.supplier_id','=',$invisu->supplier_id]])
            ->whereNotNull('rq_yarns.approved_at')
            ->get([
            'rq_yarns.rq_no',
            'rq_yarn_items.id',
            'rq_yarn_items.id as rq_yarn_item_id',
            'rq_yarn_items.qty',
            'rq_yarn_items.remarks',
            'rq_yarn_fabrications.id as rq_yarn_fabrication_id',
            'inv_yarn_items.id as inv_yarn_item_id',
            'inv_yarn_items.lot',
            'inv_yarn_items.brand',
            'colors.name as color_name',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',
            'pl_sales_orders.sale_order_no',
            'pl_sales_orders.style_ref',
            'pl_sales_orders.buyer_name',
            'po_sales_orders.sale_order_no_po',
            'po_sales_orders.style_ref_po',
            'po_sales_orders.buyer_name_po'
            ])
            ->map(function($invyarnitem) use($yarnDropdown) {
                $invyarnitem->yarn_count=$invyarnitem->count."/".$invyarnitem->symbol;
                $invyarnitem->yarn_type=$invyarnitem->yarn_type;
                $invyarnitem->composition=isset($yarnDropdown[$invyarnitem->item_account_id])?$yarnDropdown[$invyarnitem->item_account_id]:'';
                $invyarnitem->sale_order_no=$invyarnitem->sale_order_no?$invyarnitem->sale_order_no:$invyarnitem->sale_order_no_po;
                $invyarnitem->style_ref=$invyarnitem->style_ref?$invyarnitem->style_ref:$invyarnitem->style_ref_po;
                $invyarnitem->buyer_name=$invyarnitem->buyer_name?$invyarnitem->buyer_name:$invyarnitem->buyer_name_po;
                return $invyarnitem;
            }); 
            echo json_encode($invyarnitem);
        }
    }
}