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
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnIsuSamSecItemRequest;

class InvYarnIsuSamSecItemController extends Controller {

    private $invisu;
    private $invyarnisu;
    private $invyarnisuitem;
    private $store;
    private $rqyarn;
    private $itemaccount;
    private $invyarntransaction;
    private $invyarnitem;
    private $poyarndyeing;
    private $style;
    private $job;

    public function __construct(
        InvIsuRepository $invisu,
        InvYarnIsuRepository $invyarnisu, 
        InvYarnIsuItemRepository $invyarnisuitem, 
        StoreRepository $store,
        RqYarnRepository $rqyarn,
        ItemAccountRepository $itemaccount,
        InvYarnTransactionRepository $invyarntransaction,
        InvYarnItemRepository $invyarnitem,
        PoYarnDyeingRepository $poyarndyeing,
        StyleRepository $style,
        JobRepository $job
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
        $this->style = $style;
        $this->job = $job;
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
        ->leftJoin('styles',function($join){
        $join->on('styles.id','=','inv_yarn_isu_items.style_id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','inv_yarn_isu_items.sale_order_id');
        })
        ->join('style_samples', function($join)  {
        $join->on('style_samples.id', '=', 'inv_yarn_isu_items.style_sample_id');
        })
        ->join('gmtssamples', function($join)  {
        $join->on('gmtssamples.id', '=', 'style_samples.gmtssample_id');
        })
        
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
        'sales_orders.sale_order_no',
        'styles.style_ref',
        'buyers.name as buyer_name',
        'gmtssamples.name as sample_name',
       ])
       ->map(function($rows) use($yarnDropdown) {
            $rows->yarn_count=$rows->count."/".$rows->symbol;
            $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
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
    public function store(InvYarnIsuSamSecItemRequest $request) {

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
            'style_id'=>$request->style_id,
            'style_sample_id'=>$request->style_sample_id,
            'sale_order_id'=>$request->sale_order_id,
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

            $no_of_bag=$iss_qty/$invyarnrcvitem->wgt_per_bag;
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
       ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_yarn_items.supplier_id');
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
        ->leftJoin('styles',function($join){
        $join->on('styles.id','=','inv_yarn_isu_items.style_id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','inv_yarn_isu_items.sale_order_id');
        })
        
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
        'suppliers.name as supplier_name',
        'styles.id as style_id',
        'styles.style_ref',
        'buyers.name as buyer_name',
        'sales_orders.sale_order_no',
        'sales_orders.id as sale_order_id',
       ])
       ->map(function($rows) use($yarnDropdown) {
            $rows->yarn_count=$rows->count."/".$rows->symbol;
            $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
            return $rows;
       })
       ->first(); 
       $sampleDropDown = $this->sample($rows->style_id);
       $row ['fromData'] = $rows;
       $dropdown['att'] = '';
       $row ['dropDown'] = $dropdown;
       $row ['sampleDropDown'] = $sampleDropDown;
       echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvYarnIsuSamSecItemRequest $request, $id) {
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
            'qty'=>$request->qty,
            'returnable_qty'=>$request->returnable_qty,
            'returned_qty'=>$request->returned_qty,
            'inv_yarn_item_id'=>$request->inv_yarn_item_id,
            //'rq_yarn_item_id'=>$request->rq_yarn_item_id,
            //'po_yarn_dyeing_item_bom_qty_id'=>$request->po_yarn_dyeing_item_bom_qty_id,
            'style_id'=>$request->style_id,
            'style_sample_id'=>$request->style_sample_id,
            'sale_order_id'=>$request->sale_order_id,
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
                $no_of_bag=$iss_qty/$invyarnrcvitem->wgt_per_bag;
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
        return response()->json(array('success'=>false,'message'=>'Deleted Not Successfully'),200);

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
        if($this->invyarnisuitem->delete($id)){
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
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_yarn_items.supplier_id');
        })
        ->get([
        'inv_yarn_items.id',
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
        'suppliers.name as supplier_name',
        ])
        ->map(function($invyarnitem) use($yarnDropdown) {
            $invyarnitem->yarn_count=$invyarnitem->count."/".$invyarnitem->symbol;
            $invyarnitem->yarn_type=$invyarnitem->yarn_type;
            $invyarnitem->composition=isset($yarnDropdown[$invyarnitem->item_account_id])?$yarnDropdown[$invyarnitem->item_account_id]:'';
            return $invyarnitem;
        }); 
        echo json_encode($invyarnitem);

       
        
        
    }

    public function getStyle(){
        return response()->json(
            $this->style
            ->leftJoin('buyers', function($join)  {
            $join->on('styles.buyer_id', '=', 'buyers.id');
            })
            ->leftJoin('uoms', function($join)  {
            $join->on('styles.uom_id', '=', 'uoms.id');
            })
            ->leftJoin('seasons', function($join)  {
            $join->on('styles.season_id', '=', 'seasons.id');
            })
            ->leftJoin('teams', function($join)  {
            $join->on('styles.team_id', '=', 'teams.id');
            })
            ->leftJoin('teammembers', function($join)  {
            $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
            })
            ->leftJoin('users', function($join)  {
            $join->on('users.id', '=', 'teammembers.user_id');
            })
            ->leftJoin('productdepartments', function($join)  {
            $join->on('productdepartments.id', '=', 'styles.productdepartment_id');
            })
            ->leftJoin('teammembers as teamleaders', function($join)  {
            $join->on('styles.teammember_id', '=', 'teamleaders.id');
            })
            ->leftJoin('users as teamleadernames', function($join)  {
            $join->on('teamleadernames.id', '=', 'teamleaders.user_id');
            })
            ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
            })
            ->get([
                'styles.*',
                'buyers.name as buyer_name',

            ])
            ->map(function($rows){
            $rows->receivedate=date("d-M-Y",strtotime($rows->receive_date));
            $rows->buyer=$rows->buyer_name;
            $rows->deptcategory=$rows->dept_category_name;
            $rows->season=$rows->season_name;
            $rows->uom=$rows->uom_name;
            $rows->team=$rows->team_name;
            $rows->teammember=$rows->team_member_name;
            $rows->productdepartment=$rows->department_name;
            return $rows;
            })
        );
    }

    public function getSample(){
        $rows = $this->sample(request('style_id',0));
        echo json_encode($rows);
    }

    private function sample($style_id)
    {
        $rows =$this->style
        ->selectRaw(
        'style_samples.id,
        gmtssamples.name
        '
        )
        ->join('style_samples', function($join)  {
        $join->on('styles.id', '=', 'style_samples.style_id');
        })
        ->join('gmtssamples', function($join)  {
        $join->on('gmtssamples.id', '=', 'style_samples.gmtssample_id');
        })
        ->where([['styles.id','=',$style_id]])
        ->get();
        return $rows;
        
    }

    public function getOrder(){
        $invisu=$this->invisu->find(request('inv_isu_id',0));
        $salesorder=$this->job
        ->selectRaw('
         sales_orders.id,
         sales_orders.sale_order_no,
         sales_orders.ship_date,
         sales_orders.produced_company_id,
         styles.style_ref,
         styles.id as style_id,
         jobs.job_no,
         buyers.name as buyer_name,
         companies.name as company_name,
         produced_company.name as produced_company_name
         ')
        
        ->join('sales_orders', function($join)  {
             $join->on('jobs.id', '=', 'sales_orders.job_id');
         })
        ->join('companies', function($join)  {
             $join->on('companies.id', '=', 'jobs.company_id');
         })
        ->leftJoin('companies as produced_company', function($join)  {
            $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
        })
        ->join('styles', function($join)  {
             $join->on('styles.id', '=', 'jobs.style_id');
         })
        
         ->join('buyers', function($join)  {
         $join->on('buyers.id', '=', 'styles.buyer_id');
         })
         
        /* ->when(request('style_ref'), function ($q) {
             return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
         })
         ->when(request('style_id'), function ($q) {
             return $q->where('styles.id', '=', request('style_id', 0));
         })*/
         
         ->when(request('sale_order_no'), function ($q) {
             return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
         })
         ->where([['jobs.company_id','=',$invisu->company_id]])
        ->get()
        ->map(function ($salesorder){
          return $salesorder;
         });
        echo json_encode($salesorder);
    }
}