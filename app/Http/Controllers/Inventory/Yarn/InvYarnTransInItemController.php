<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvItemRepository;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransInRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransInItemRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransactionRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransOutItemRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuItemRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnTransInItemRequest;

class InvYarnTransInItemController extends Controller {
    private $invrcv;
    private $invyarnrcv;
    private $invyarnrcvitem;
    private $invyarntransin;
    private $invyarntransinitem;
    private $invyarnitem;
    private $invyarntransaction;
    private $invyarntransoutitem;
    private $invyarnisuitem;
    private $company;
    private $store;
    private $itemaccount;

    public function __construct(
        InvRcvRepository $invrcv,
        InvYarnRcvRepository $invyarnrcv, 
        InvYarnRcvItemRepository $invyarnrcvitem,
        InvYarnTransInRepository $invyarntransin,
        InvYarnTransInItemRepository $invyarntransinitem,
        InvYarnItemRepository $invyarnitem,
        InvYarnTransactionRepository $invyarntransaction,
        InvYarnTransOutItemRepository $invyarntransoutitem,
        InvYarnIsuItemRepository $invyarnisuitem,
        CompanyRepository $company, 
        StoreRepository $store,
        ItemAccountRepository $itemaccount
    ) {
        $this->invrcv = $invrcv;
        $this->invyarnrcv = $invyarnrcv;
        $this->invyarnrcvitem = $invyarnrcvitem;
        $this->invyarntransin = $invyarntransin;
        $this->invyarntransinitem = $invyarntransinitem;
        $this->invyarnitem = $invyarnitem;
        $this->invyarntransaction = $invyarntransaction;
        $this->invyarntransoutitem = $invyarntransoutitem;
        $this->invyarnisuitem = $invyarnisuitem;
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
        
        $rows = $this->invyarntransin
        ->join('inv_yarn_trans_in_items',function($join){
        $join->on('inv_yarn_trans_in_items.inv_yarn_trans_in_id','=','inv_yarn_trans_ins.id');
        })
        ->join('inv_yarn_trans_out_items',function($join){
        $join->on('inv_yarn_trans_out_items.id','=','inv_yarn_trans_in_items.inv_yarn_trans_out_item_id');
        })
        ->join('inv_yarn_trans_outs',function($join){
            $join->on('inv_yarn_trans_outs.id','=','inv_yarn_trans_out_items.inv_yarn_trans_out_id');
        })
        
        
        ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_trans_in_items.inv_yarn_item_id');
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
       ->where([['inv_yarn_trans_ins.id','=',request('inv_yarn_trans_in_id',0)]])
       ->orderBy('inv_yarn_trans_in_items.id','desc')
       ->get([
        'inv_yarn_trans_in_items.*',
        'inv_yarn_trans_outs.transfer_no',
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
       
        $inv_yarn_rcv_id=request('inv_yarn_rcv_id',0);
        $invyarnrcv=$this->invyarnrcv->find($inv_yarn_rcv_id);
        $invcv=$this->invrcv->find($invyarnrcv->inv_rcv_id);

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
        
        
        ->where([['inv_yarn_rcvs.id','=',$inv_yarn_rcv_id]])
        ->orderBy('inv_yarn_rcv_items.id','desc')
        ->get([
        'itemcategories.name as itemcategory_name',
        'itemclasses.name as itemclass_name',
        'item_accounts.id as item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'uoms.code as uom_code',

        'inv_yarn_rcv_items.id',
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'colors.name as color_name',
        'inv_yarn_rcv_items.qty',
        'inv_yarn_rcv_items.rate',
        'inv_yarn_rcv_items.amount',
        'inv_yarn_rcv_items.store_qty',
        'uoms.code as uom',
        'inv_yarn_rcv_items.store_rate',
        'inv_yarn_rcv_items.store_amount',
        ])
        ->map(function($invyarnrcvitem) use($yarnDropdown) {
        $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
        $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
        $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
        return $invyarnrcvitem;
        });
        echo json_encode($invyarnrcvitem);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.Yarn.InvYarnTransIn',['company'=>$company,'store'=>$store]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvYarnTransInItemRequest $request) {
      /*$invyarntransin=$this->invyarntransin->find($request->inv_yarn_trans_in_id);
      $invyarnitem=$this->invyarnitem->find($request->inv_yarn_item_id);
        \DB::beginTransaction();
        $store_qty=$request->qty;
        $store_rate=$request->rate;
        $store_amount=$request->amount;
        try
        {
          $invyarntransinitem = $this->invyarntransinitem->create([
          'inv_yarn_trans_in_id'=> $request->inv_yarn_trans_in_id,         
          'inv_yarn_item_id'=> $request->inv_yarn_item_id,          
          'inv_yarn_trans_out_item_id'=> $request->inv_yarn_trans_out_item_id,          
          'store_id'=> $request->store_id,
          'qty' => $request->qty,
          'rate' => $request->rate,
          'amount'=> $request->amount,
          'room'=> $request->room,     
          'remarks' => $request->remarks    
          ]);

          $invyarntransaction=$this->invyarntransaction->create([
          'trans_type_id'=>3,
          'trans_date'=>$invyarntransin->receive_date,
          'inv_yarn_trans_in_item_id'=>$invyarntransinitem->id,
          'inv_yarn_item_id'=>$request->inv_yarn_item_id,
          'company_id'=>$invyarntransin->company_id,
          'supplier_id'=>$invyarnitem->supplier_id,
          'store_id'=>$request->store_id,
          'store_qty' => $store_qty,
          'store_rate' => $store_rate,
          'store_amount'=> $store_amount
          ]);
        }
        catch(EXCEPTION $e)
        {
          \DB::rollback();
          throw $e;
        }
        \DB::commit();


        if($invyarntransinitem){
            return response()->json(array('success' => true,'id' =>  $invyarntransinitem->id,'inv_yarn_trans_in_id' => $request->inv_yarn_trans_in_id,'message' => 'Save Successfully'),200);
        }*/

        $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);
        \DB::beginTransaction();
        $store_qty=$request->qty;
        $store_rate=$request->rate;
        $store_amount=$request->amount;
        try
        {
            $invyarnrcvitem = $this->invyarnrcvitem->create(
            [
                'inv_yarn_rcv_id'=> $request->inv_yarn_rcv_id,         
                'inv_yarn_item_id'=> $request->inv_yarn_item_id,          
                'inv_yarn_isu_item_id'=> $request->inv_yarn_isu_item_id,          
                'store_id'=> $request->store_id,
                'cone_per_bag'=> 0,     
                'wgt_per_cone'=> 0,     
                'wgt_per_bag'=> 0,     
                'no_of_bag'=> 0,
                'qty' => $request->qty,
                'rate' => $request->rate,
                'amount'=> $request->amount,
                'store_qty' => $store_qty,
                'store_rate' => $store_rate,
                'store_amount'=> $store_amount,
                'room'=> $request->room,     
                'rack'=> $request->rack,     
                'shelf'=> $request->shelf,
                'remarks' => $request->remarks     
            ]);

            $invyarntransaction=$this->invyarntransaction->create(
            [
                'trans_type_id'=>1,
                'trans_date'=>$invyarnrcv->receive_date,
                'inv_yarn_rcv_item_id'=>$invyarnrcvitem->id,
                'inv_yarn_item_id'=>$request->inv_yarn_item_id,
                'company_id'=>$invyarnrcv->company_id,
                'supplier_id'=>$invyarnrcv->supplier_id,
                'store_id'=>$request->store_id,
                'store_qty' => $store_qty,
                'store_rate' => $store_rate,
                'store_amount'=> $store_amount
            ]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        if($invyarnrcvitem){
        return response()->json(array('success' => true,'id' =>  $invyarnrcvitem->id,'inv_yarn_rcv_id' => $request->inv_yarn_rcv_id,'message' => 'Save Successfully'),200);
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

        /*$rows = $this->invyarntransinitem
        ->join('inv_yarn_trans_ins',function($join){
        $join->on('inv_yarn_trans_in_items.inv_yarn_trans_in_id','=','inv_yarn_trans_ins.id');
        })

        ->join('inv_yarn_trans_out_items',function($join){
        $join->on('inv_yarn_trans_out_items.id','=','inv_yarn_trans_in_items.inv_yarn_trans_out_item_id');
        })
        ->join('inv_yarn_trans_outs',function($join){
            $join->on('inv_yarn_trans_outs.id','=','inv_yarn_trans_out_items.inv_yarn_trans_out_id');
        })
        
        
        ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_trans_in_items.inv_yarn_item_id');
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
       ->where([['inv_yarn_trans_in_items.id','=',$id]])
       ->get([
        'inv_yarn_trans_in_items.*',
        'inv_yarn_trans_outs.transfer_no',
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
        })->first();
        $row ['fromData'] = $rows;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);*/

        $rows=$this->invyarnrcvitem
        ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcvs.id','=','inv_yarn_rcv_items.inv_yarn_rcv_id');
        })
        ->join('inv_rcvs',function($join){
        $join->on('inv_rcvs.id','=','inv_yarn_rcvs.inv_rcv_id');
        })
         ->join('inv_yarn_isu_items',function($join){
        $join->on('inv_yarn_isu_items.id','=','inv_yarn_rcv_items.inv_yarn_isu_item_id');
        })
        ->join('inv_isus',function($join){
            $join->on('inv_isus.id','=','inv_yarn_isu_items.inv_isu_id');
        })
        ->join('inv_yarn_items',function($join){
        $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id');
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
        
        ->where([['inv_yarn_rcv_items.id','=',$id]])
        ->orderBy('inv_yarn_rcv_items.id','desc')
        ->get([
        'inv_yarn_rcv_items.*',
        'inv_isus.issue_no as transfer_no',
        'itemcategories.name as itemcategory_name',
        'itemclasses.name as itemclass_name',
        'item_accounts.id as item_account_id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'uoms.code as uom_code',

        
        'inv_yarn_items.lot',
        'inv_yarn_items.brand',
        'suppliers.name as supplier_name',
        'colors.name as yarn_color_name',
        'uoms.code as uom',
        ])
        ->map(function($rows) use($yarnDropdown) {
            $rows->yarn_count=$rows->count."/".$rows->symbol;
            $rows->yarn_des=$rows->yarn_count.",".$yarnDropdown[$rows->item_account_id].",".$rows->yarn_type.",".$rows->brand.",".$rows->color_name.",".$rows->lot;
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
    public function update(InvYarnTransInItemRequest $request, $id) {
        $issueNo=$this->invyarntransaction
        ->join('inv_yarn_isu_items',function($join){
        $join->on('inv_yarn_isu_items.id','=','inv_yarn_transactions.inv_yarn_isu_item_id');
        })
        ->join('inv_isus',function($join){
        $join->on('inv_isus.id','=','inv_yarn_isu_items.inv_isu_id');
        })
        ->where([['inv_yarn_rcv_item_id','=',$id]])
        ->where([['trans_type_id','=',2]])
        ->get(['inv_isus.issue_no'])
        ->first();
        
        if($issueNo){
        return response()->json(array('success' => false,'message' => 'Update No Possible, Issue no '.$issueNo->issue_no.' Found '),200);

        }


        $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);
        $store_qty=$request->qty;
        $store_rate=$request->rate;
        $store_amount=$request->amount;
        \DB::beginTransaction();
        try
        {
        $invyarnrcvitem = $this->invyarnrcvitem->update($id,
        [
            'inv_yarn_item_id'=> $request->inv_yarn_item_id,         
            'store_id'=> $request->store_id, 
            'inv_yarn_isu_item_id'=> $request->inv_yarn_isu_item_id,          
            'cone_per_bag'=> 0,     
            'wgt_per_cone'=> 0,     
            'wgt_per_bag'=> 0,     
            'no_of_bag'=> 0,
            'qty' => $request->qty,
            'rate' => $request->rate,
            'amount'=> $request->amount,
            'store_qty' => $store_qty,
            'store_rate' => $store_rate,
            'store_amount'=> $store_amount,
            'room'=> $request->room,     
            'rack'=> $request->rack,     
            'shelf'=> $request->shelf,
            'remarks' => $request->remarks     
        ]);

        $invyarntransaction=$this->invyarntransaction
        ->where([['inv_yarn_rcv_item_id','=',$id]])
        ->where([['trans_type_id','=',1]])
        ->update([
            'trans_date'=>$invyarnrcv->receive_date,
            'inv_yarn_item_id'=>$request->inv_yarn_item_id, 
            'company_id'=>$invyarnrcv->company_id,
            'supplier_id'=>$invyarnrcv->supplier_id,
            'store_id'=>$request->store_id,
            'store_qty' => $store_qty,
            'store_rate' => $store_rate,
            'store_amount'=> $store_amount
        ]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        if($invyarnrcvitem){
            return response()->json(array('success' => true,'id' => $id,'inv_yarn_rcv_id' => $request->inv_yarn_rcv_id,'message' => 'Update Successfully'),200);
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

        if($this->invyarntransinitem->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


   public function getYarnItem()
    {
        $invcv=$this->invrcv->find(request('inv_rcv_id'));
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

        //$rows=$this->invyarnitem;
        $rows=$this->invyarnisuitem
        ->join('inv_isus',function($join){
            $join->on('inv_isus.id','=','inv_yarn_isu_items.inv_isu_id');
        })
        ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_isu_items.inv_yarn_item_id');
        })
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
        ->where([['inv_isus.isu_basis_id','=',9]])
        ->where([['inv_isus.company_id','=',$invcv->from_company_id]])
        ->where([['inv_yarn_items.supplier_id','=',$invcv->supplier_id]])
        ->when(request('lot'), function ($q) {
            return $q->where('inv_yarn_items.lot', 'like', '%'.request('lot', 0).'%');
        })
        ->when(request('brand'), function ($q) {
            return $q->where('inv_yarn_items.brand', 'like', '%'.request('brand', 0).'%');
        })
        ->get([
            'inv_isus.issue_no as transfer_no',
            'inv_yarn_isu_items.id as inv_yarn_isu_item_id',
            'inv_yarn_isu_items.qty',
            'inv_yarn_isu_items.rate',
            'inv_yarn_isu_items.amount',
            'inv_yarn_items.id as inv_yarn_item_id',
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
            $rows->yarn_count = $rows->count."/".$rows->symbol;
            return $rows;
        });
        echo json_encode($rows);
    }
}