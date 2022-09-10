<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransOutRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransOutItemRepository;

use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuItemRepository;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransactionRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnTransOutRequest;

class InvYarnTransOutItemController extends Controller {

    private $invyarntransout;
    private $invyarntransoutitem;
    private $invisu;
    private $invyarnisu;
    private $invyarnisuitem;
    private $invyarnitem;
    private $invyarntransaction;
    private $company;
    private $store;
    private $itemaccount;

    public function __construct(
        InvYarnTransOutRepository $invyarntransout,
        InvYarnTransOutItemRepository $invyarntransoutitem,
        InvIsuRepository $invisu,
        InvYarnIsuRepository $invyarnisu, 
        InvYarnIsuItemRepository $invyarnisuitem, 
        InvYarnItemRepository $invyarnitem,
        InvYarnTransactionRepository $invyarntransaction,
        CompanyRepository $company, 
        StoreRepository $store,
        ItemAccountRepository $itemaccount
    ) {
        $this->invyarntransout = $invyarntransout;
        $this->invyarntransoutitem = $invyarntransoutitem;
        $this->invisu = $invisu;
        $this->invyarnisu = $invyarnisu;
        $this->invyarnisuitem = $invyarnisuitem;
        $this->invyarnitem = $invyarnitem;
        $this->invyarntransaction = $invyarntransaction;
        $this->company = $company;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        //$this->middleware('permission:view.invyarnisu',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invyarnisu', ['only' => ['store']]);
        //$this->middleware('permission:edit.invyarnisu',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invyarnisu', ['only' => ['destroy']]);
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
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.Yarn.InvYarnTransOut',['company'=>$company,'store'=>$store]);
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
    public function store(InvYarnTransOutRequest $request) {
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
            'inv_yarn_item_id'=>$request->inv_yarn_item_id,
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

            //$no_of_bag=$iss_qty/$invyarnrcvitem->wgt_per_bag;
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
    public function update(InvYarnTransOutRequest $request, $id) {
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
                //$no_of_bag=$iss_qty/$invyarnrcvitem->wgt_per_bag;
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

        $rows=$this->invyarnitem
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_yarn_items.item_account_id'); 
        })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','inv_yarn_items.supplier_id'); 
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id'); 
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
        ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->where([['itemcategories.identity','=',1]])
        ->when(request('supplier_id'), function ($q) {
            return $q->where('inv_yarn_items.supplier_id', '=', request('supplier_id',0));
        })
        ->when(request('lot'), function ($q) {
            return $q->where('inv_yarn_items.lot', 'like', '%'.request('lot', 0).'%');
        })
        ->when(request('brand'), function ($q) {
            return $q->where('inv_yarn_items.brand', 'like', '%'.request('brand', 0).'%');
        })
        ->get([
            'inv_yarn_items.id',
            'inv_yarn_items.lot',
            'inv_yarn_items.brand',
            'inv_yarn_items.supplier_id',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'suppliers.name as supplier_name',
            'itemcategories.name as itemcategory_name',
            'colors.name as yarn_color_name'
        ])
        ->map(function ($rows) use($yarnDropdown)  {

            $rows->yarn_des = isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
            $rows->inv_yarn_item_id = $rows->id;
            $rows->yarn_count = $rows->count."/".$rows->symbol;
            return $rows;
        });
        echo json_encode($rows);
    }

    public function getRate()
    {
        $inv_yarn_item_id=request('inv_yarn_item_id',0);
        $rate=$this->invyarnitem
        ->join('inv_yarn_rcv_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
        })
        ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcvs.id','=','inv_yarn_rcv_items.inv_yarn_rcv_id');
        })
        ->where([['inv_yarn_items.id','=',$inv_yarn_item_id]])
        ->orderBy('inv_yarn_rcvs.id','desc')
        ->orderBy('inv_yarn_rcv_items.id','desc')
        ->get(['inv_yarn_rcv_items.store_rate'])
        ->first();
        echo json_encode($rate);
    }
}