<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnPoRtnRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnPoRtnItemRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuItemRepository;

use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvItemRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransactionRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnPoRtnItemRequest;

class InvYarnPoRtnItemController extends Controller {

    private $invyarnportn;
    private $invyarnportnitem;
    private $invisu;
    private $invyarnisu;
    private $invyarnisuitem;
    private $invrcv;
    private $invyarnrcv;
    private $invyarnrcvitem;
    private $store;
    private $rqyarn;
    private $itemaccount;
    private $invyarntransaction;
    private $invyarnitem;
    private $poyarndyeing;
    private $salesorder;

    public function __construct(
        InvYarnPoRtnRepository $invyarnportn,
        InvYarnPoRtnItemRepository $invyarnportnitem,
        InvIsuRepository $invisu,
        InvYarnIsuRepository $invyarnisu,
        InvYarnIsuItemRepository $invyarnisuitem,
        InvRcvRepository $invrcv,
        InvYarnRcvRepository $invyarnrcv, 
        InvYarnRcvItemRepository $invyarnrcvitem, 
        StoreRepository $store,
        RqYarnRepository $rqyarn,
        ItemAccountRepository $itemaccount,
        InvYarnTransactionRepository $invyarntransaction,
        InvYarnItemRepository $invyarnitem,
        PoYarnDyeingRepository $poyarndyeing,
        SalesOrderRepository $salesorder
    ) 
    {
        $this->invyarnportn = $invyarnportn;
        $this->invyarnportnitem = $invyarnportnitem;
        $this->invisu = $invisu;
        $this->invyarnisu = $invyarnisu;
        $this->invyarnisuitem = $invyarnisuitem;
        $this->invrcv = $invrcv;
        $this->invyarnrcv = $invyarnrcv;
        $this->invyarnrcvitem = $invyarnrcvitem;
        $this->store = $store;
        $this->rqyarn = $rqyarn;
        $this->itemaccount = $itemaccount;
        $this->invyarntransaction = $invyarntransaction;
        $this->invyarnitem = $invyarnitem;
        $this->poyarndyeing = $poyarndyeing;
        $this->salesorder = $salesorder;
        
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
        /*$yarnDescription=$this->itemaccount
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
        
        $rows = $this->invyarntransout
        ->join('inv_yarn_trans_out_items',function($join){
        $join->on('inv_yarn_trans_out_items.inv_yarn_trans_out_id','=','inv_yarn_trans_outs.id');
        })
        
        
        ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_trans_out_items.inv_yarn_item_id');
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
       ->where([['inv_yarn_trans_outs.id','=',request('inv_yarn_trans_out_id',0)]])
       ->orderBy('inv_yarn_trans_out_items.id','desc')
       ->get([
        'inv_yarn_trans_out_items.*',
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
       ->map(function($rows) use($yarnDropdown) {
            $rows->yarn_count=$rows->count."/".$rows->symbol;
            $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
            return $rows;
        });
       echo json_encode($rows);*/

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

    private function remainder($dividend, $divisor)
    {
        $tempMod = (float)($dividend / $divisor);
        $tempMod = ($tempMod - (int)$tempMod)*$divisor;
        return $tempMod;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(InvYarnPoRtnItemRequest $request) {
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
            ->where([['inv_yarn_transactions.inv_yarn_rcv_item_id','=',$request->inv_yarn_rcv_item_id]])
            ->groupBy([
            'inv_yarn_transactions.store_id',
            'inv_yarn_transactions.inv_yarn_item_id'
            ])
            ->get()
            ->first();
            if(!$invyarntransaction){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock1'),200);
            }
            if($invyarntransaction->store_qty < $request->qty ){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock2'),200);
            }



            $trans_type_id=2;
            \DB::beginTransaction();
            $invyarnisuitem=$this->invyarnisuitem->create([
            'inv_isu_id'=>$request->inv_isu_id,
            'store_id'=>$request->store_id,
            'inv_yarn_item_id'=>$request->inv_yarn_item_id,
            'inv_yarn_rcv_item_id'=>$request->inv_yarn_rcv_item_id,
            'qty'=>$request->qty,
            'rate'=>$request->rate,
            'amount'=>$request->amount,
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
            ->where([['inv_yarn_transactions.inv_yarn_rcv_item_id','=',$request->inv_yarn_rcv_item_id]])

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
            'supplier_id'=>$invisu->supplier_id,
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
       ->join('inv_yarn_rcv_items',function($join){
          $join->on('inv_yarn_rcv_items.id','=','inv_yarn_isu_items.inv_yarn_rcv_item_id');
          })
         ->join('inv_yarn_rcvs',function($join){
          $join->on('inv_yarn_rcvs.id','=','inv_yarn_rcv_items.inv_yarn_rcv_id');
          })
         ->join('inv_rcvs',function($join){
          $join->on('inv_rcvs.id','=','inv_yarn_rcvs.inv_rcv_id');
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
        
        ->where([['inv_yarn_isu_items.id','=',$id]])
       ->orderBy('inv_yarn_isu_items.id','desc')
       ->get([
        'inv_yarn_isu_items.*',
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'colors.name as yarn_color_name',
        'itemcategories.name as itemcategory_name',
        'itemclasses.name as itemclass_name',
        'item_accounts.id as item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'uoms.code as uom_code',
        'suppliers.name as supplier_name',
        'inv_rcvs.id as inv_rcv_id'
       ])
       ->map(function($rows) use($yarnDropdown) {
            $rows->yarn_count=$rows->count."/".$rows->symbol;
            $rows->yarn_des=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
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
    public function update(InvYarnPoRtnItemRequest $request, $id) {
            $maintain_bag=0;
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
            ->where([['inv_yarn_transactions.inv_yarn_rcv_item_id','=',$request->inv_yarn_rcv_item_id]])
            ->groupBy([
            'inv_yarn_transactions.store_id',
            'inv_yarn_transactions.inv_yarn_item_id'
            ])
            ->get()
            ->first();
            if(!$invyarntransaction){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock1'),200);
            }
            if($invyarntransaction->store_qty < $request->qty ){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock2'),200);
            }



            $trans_type_id=2;
            $invyarnisuitem=$this->invyarnisuitem->update($id,[
            //'inv_isu_id'=>$request->inv_isu_id,
            'store_id'=>$request->store_id,
            'inv_yarn_item_id'=>$request->inv_yarn_item_id,
            'inv_yarn_rcv_item_id'=>$request->inv_yarn_rcv_item_id,
            'qty'=>$request->qty,
            'rate'=>$request->rate,
            'amount'=>$request->amount,
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
            ->where([['inv_yarn_transactions.inv_yarn_rcv_item_id','=',$request->inv_yarn_rcv_item_id]])

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
            'inv_yarn_isu_item_id'=>$id,
            'inv_yarn_item_id'=>$request->inv_yarn_item_id,
            'company_id'=>$invisu->company_id,
            'supplier_id'=>$invisu->supplier_id,
            'store_id'=>$request->store_id,
            'store_qty' => $iss_qty*-1,
            'store_rate' => $invyarnrcvitem->store_rate,
            'store_amount'=> $store_amount
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
            return response()->json(array('success' =>true ,'id'=>$id,'inv_isu_id'=>$request->inv_isu_id,'message'=>'Saved Successfully'),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        return response()->json(array('success'=>false,'message'=>'Deleted Not Successfully'),200);

        /*if($this->invyarnisurtn->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }*/
    }

    public function getMrrItem()
    {
        
        $inv_rcv_id=request('inv_rcv_id',0);
        $inv_rcv_id= (int) $inv_rcv_id;
        //$invyarnrcv=$this->invyarnrcv->find($inv_yarn_rcv_id);
        $invcv=$this->invrcv->find($inv_rcv_id);
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

        if($invcv->receive_against_id==3)
        {
            $invyarnrcvitem=$this->invrcv
            ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
            })
            ->join('inv_yarn_rcv_items',function($join){
            $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
            })
            ->join('po_yarn_items',function($join){
            $join->on('po_yarn_items.id','=','inv_yarn_rcv_items.po_yarn_item_id');
            })
            ->join('po_yarns',function($join){
            $join->on('po_yarns.id','=','po_yarn_items.po_yarn_id');
            })
            ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
            })
            ->join('suppliers',function($join){
            $join->on('inv_yarn_items.supplier_id','=','suppliers.id');
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
            ->join('currencies',function($join){
            $join->on('currencies.id','=','po_yarns.currency_id');
            })
            ->join('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id');
            })
            ->where([['inv_rcvs.id','=',$inv_rcv_id]])
            ->where([['inv_rcvs.company_id','=',$invisu->company_id]])
            ->where([['inv_rcvs.supplier_id','=',$invisu->supplier_id]])
            ->orderBy('inv_yarn_rcv_items.id','desc')
            ->get([
            'po_yarns.po_no',
            'po_yarns.pi_no',
            'po_yarns.exch_rate',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',
            'po_yarn_items.id as po_yarn_item_id',

            'currencies.code as currency_code',
            'inv_rcvs.challan_no',
            'inv_yarn_rcv_items.id',
            'inv_yarn_rcv_items.inv_yarn_item_id',
            'inv_yarn_items.lot',
            'inv_yarn_items.brand',
            'colors.name as color_name',
            'inv_yarn_rcv_items.qty',
            'inv_yarn_rcv_items.rate',
            'inv_yarn_rcv_items.amount',
            'inv_yarn_rcv_items.store_id',
            'inv_yarn_rcv_items.store_qty',
            'uoms.code as uom',
            'inv_yarn_rcv_items.store_rate',
            'inv_yarn_rcv_items.store_amount',
            'suppliers.name as supplier_name',
            'inv_rcvs.id as inv_rcv_id',
            'inv_yarn_rcvs.id as inv_yarn_rcv_id',
            ])
            ->map(function($invyarnrcvitem) use($yarnDropdown) {
            $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
            $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
            $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';

            $invyarnrcvitem->yarn_desc= $invyarnrcvitem->yarn_count.",".$invyarnrcvitem->composition.",".$invyarnrcvitem->yarn_type.",".$invyarnrcvitem->brand.",".$invyarnrcvitem->color_name;

            return $invyarnrcvitem;
            }); 
        }
        else if($invcv->receive_against_id==9)
        {
            $invyarnrcvitem=$this->invrcv
            ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
            })
            ->join('inv_yarn_rcv_items',function($join){
            $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
            })
            ->join('inv_yarn_isu_items',function($join){
            $join->on('inv_yarn_isu_items.id','=','inv_yarn_rcv_items.inv_yarn_isu_item_id');
            })
            ->join('po_yarn_dyeing_item_bom_qties',function($join){
            $join->on('po_yarn_dyeing_item_bom_qties.id','=','inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id');
            })
            ->join('po_yarn_dyeing_items',function($join){
            $join->on('po_yarn_dyeing_items.id','=','po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id');
            })
            ->join('po_yarn_dyeings',function($join){
            $join->on('po_yarn_dyeings.id','=','po_yarn_dyeing_items.po_yarn_dyeing_id');
            })
            
            ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
            })
            ->join('suppliers',function($join){
            $join->on('inv_yarn_items.supplier_id','=','suppliers.id');
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
            ->join('currencies',function($join){
            $join->on('currencies.id','=','po_yarn_dyeings.currency_id');
            })
            ->join('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id');
            })
            ->where([['inv_rcvs.id','=',$inv_rcv_id]])
            ->where([['inv_rcvs.company_id','=',$invisu->company_id]])
            ->where([['inv_rcvs.supplier_id','=',$invisu->supplier_id]])
            ->orderBy('inv_yarn_rcv_items.id','desc')
            ->get([
            'po_yarn_dyeings.po_no',
            'po_yarn_dyeings.pi_no',
            'po_yarn_dyeings.exch_rate',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',
            'po_yarn_dyeing_items.id as po_yarn_dyeing_item_id',

            'currencies.code as currency_code',
            'inv_rcvs.challan_no',
            'inv_yarn_rcv_items.id',
            'inv_yarn_rcv_items.inv_yarn_item_id',
            'inv_yarn_items.lot',
            'inv_yarn_items.brand',
            'colors.name as color_name',
            'inv_yarn_rcv_items.qty',
            'inv_yarn_rcv_items.rate',
            'inv_yarn_rcv_items.amount',
            'inv_yarn_rcv_items.store_id',
            'inv_yarn_rcv_items.store_qty',
            'uoms.code as uom',
            'inv_yarn_rcv_items.store_rate',
            'inv_yarn_rcv_items.store_amount',
            'suppliers.name as supplier_name',
            'inv_rcvs.id as inv_rcv_id',
            'inv_yarn_rcvs.id as inv_yarn_rcv_id',
            ])
            ->map(function($invyarnrcvitem) use($yarnDropdown) {
            $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
            $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
            $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
            $invyarnrcvitem->yarn_desc= $invyarnrcvitem->yarn_count.",".$invyarnrcvitem->composition.",".$invyarnrcvitem->yarn_type.",".$invyarnrcvitem->brand.",".$invyarnrcvitem->color_name;

            return $invyarnrcvitem;
            });
        }
        else{
            $invyarnrcvitem=$this->invrcv
            ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
            })
            ->join('inv_yarn_rcv_items',function($join){
            $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
            })

            ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
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
            ->leftJoin('sales_orders',function($join){
            $join->on('sales_orders.id','=','inv_yarn_rcv_items.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
            })
            //->where([['inv_yarn_rcvs.id','=',$inv_yarn_rcv_id]])
            ->where([['inv_rcvs.id','=',$inv_rcv_id]])
            ->where([['inv_rcvs.company_id','=',$invisu->company_id]])
            ->where([['inv_rcvs.supplier_id','=',$invisu->supplier_id]])
            ->orderBy('inv_yarn_rcv_items.id','desc')
            ->get([
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',

            'inv_rcvs.id as inv_rcv_id',
            'inv_yarn_rcvs.id as inv_yarn_rcv_id',
            'inv_yarn_rcv_items.id',
            'inv_yarn_rcv_items.inv_yarn_item_id',
            'inv_yarn_items.lot',
            'inv_yarn_items.brand',
            'colors.name as color_name',
            'inv_yarn_rcv_items.qty',
            'inv_yarn_rcv_items.rate',
            'inv_yarn_rcv_items.amount',
            'inv_yarn_rcv_items.store_qty',
            'inv_yarn_rcv_items.store_id',
            'uoms.code as uom',
            'inv_yarn_rcv_items.store_rate',
            'inv_yarn_rcv_items.store_amount',
            'styles.style_ref',
            'buyers.name as buyer_name',
            'sales_orders.sale_order_no'
            ])
            ->map(function($invyarnrcvitem) use($yarnDropdown) {
            $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
            $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
            $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
            return $invyarnrcvitem;
            });
        }
        echo json_encode($invyarnrcvitem);
    }

    

   
}