<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvItemRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransactionRepository;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Purchase\PoYarnItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemAccountRatioRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnRcvItemRequest;

class InvYarnRcvItemController extends Controller {

    
    private $invrcv;
    private $invyarnrcv;
    private $invyarnitem;
    private $invyarnrcvitem;
    private $invyarntransaction;
    private $poyarnitem;
    private $poyarn;
    private $poyarndyeing;
    private $itemaccount;
    private $itemaccountratio;
    private $store;
    private $color;

    public function __construct(
        InvRcvRepository $invrcv,
        InvYarnRcvRepository $invyarnrcv, 
        InvYarnItemRepository $invyarnitem,
        InvYarnRcvItemRepository $invyarnrcvitem,
        InvYarnTransactionRepository $invyarntransaction,
        PoYarnRepository $poyarn,
        PoYarnItemRepository $poyarnitem,
        PoYarnDyeingRepository $poyarndyeing,
        ItemAccountRepository $itemaccount,
        ItemAccountRatioRepository $itemaccountratio,
        StoreRepository $store,
        ColorRepository $color
    ) {
        $this->invrcv = $invrcv;
        $this->invyarnrcv = $invyarnrcv;
        $this->invyarnitem = $invyarnitem;
        $this->invyarnrcvitem = $invyarnrcvitem;
        $this->invyarntransaction = $invyarntransaction;
        $this->poyarnitem = $poyarnitem;
        $this->poyarn = $poyarn;
        $this->poyarndyeing = $poyarndyeing;
        $this->itemaccount = $itemaccount;
        $this->itemaccountratio = $itemaccountratio;
        $this->store = $store;
        $this->color = $color;

        $this->middleware('auth');
            /*$this->middleware('permission:view.invyarnrcvitems',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.invyarnrcvitems', ['only' => ['store']]);
            $this->middleware('permission:edit.invyarnrcvitems',   ['only' => ['update']]);
            $this->middleware('permission:delete.invyarnrcvitems', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$inv_yarn_rcv_id=request('inv_yarn_rcv_id',0);
        $invyarnrcv=$this->invyarnrcv->find($inv_yarn_rcv_id);
        $invcv=$this->invrcv->find($invyarnrcv->inv_rcv_id);
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

        $invyarnrcvitem='';
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
            ->where([['inv_yarn_rcvs.id','=',$inv_yarn_rcv_id]])
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
        }
        if($invcv->receive_against_id==9)
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
            ->where([['inv_yarn_rcvs.id','=',$inv_yarn_rcv_id]])
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
        }
        echo json_encode($invyarnrcvitem);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $po_yarn_item_id=explode(',',request('po_yarn_item_id',0));
        $invyarnrcv=$this->invyarnrcv->find(request('inv_yarn_rcv_id',0));
        $invcv=$this->invrcv->find($invyarnrcv->inv_rcv_id);

        $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');

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
            $poyarn=$this->poyarn
            ->join('po_yarn_items',function($join){
                $join->on('po_yarn_items.po_yarn_id','=','po_yarns.id')
                ->whereNull('po_yarn_items.deleted_at');
            })
            ->join('item_accounts',function($join){
                $join->on('po_yarn_items.item_account_id','=','item_accounts.id');
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
            ->when(request('po_no'), function($q) {
                return $q->where('po_yarns.po_no', '=' , request('po_no',0));
            })
            ->when(request('pi_no'), function($q) {
                return $q->where('po_yarns.pi_no', '=' , request('pi_no',0));
            })
            ->whereIn('po_yarn_items.id',$po_yarn_item_id)
            //->where([['po_yarns.supplier_id','=', $invyarnrcv->supplier_id]])
            //->where([['po_yarns.company_id','=', $invyarnrcv->company_id]])
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
                'po_yarn_items.qty',
                'po_yarn_items.rate',
                'po_yarn_items.amount',
                'currencies.code as currency_code'
            ])
            ->map(function($poyarn) use($yarnDropdown) {
                $poyarn->yarn_count=$poyarn->count."/".$poyarn->symbol;
                $poyarn->yarn_type=$poyarn->yarn_type;
                $poyarn->composition=isset($yarnDropdown[$poyarn->item_account_id])?$yarnDropdown[$poyarn->item_account_id]:'';
                return $poyarn;
            }); 
        }
        if($invcv->receive_against_id==9)
        {
            $poyarn=$this->poyarndyeing
            ->join('po_yarn_dyeing_items',function($join){
            $join->on('po_yarn_dyeings.id','=','po_yarn_dyeing_items.po_yarn_dyeing_id');
            })
            ->join('po_yarn_dyeing_item_bom_qties',function($join){
            $join->on('po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id','=','po_yarn_dyeing_items.id');
            })
            ->leftJoin('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','po_yarn_dyeing_items.inv_yarn_item_id'); 
            })
            ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_yarn_items.item_account_id'); 
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
            ->join('inv_yarn_isu_items',function($join){
            $join->on('inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id','=','po_yarn_dyeing_item_bom_qties.id');
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
            ->join('colors',function($join){
            $join->on('colors.id','=','style_fabrication_stripes.color_id');
            })
            ->when(request('po_no'), function($q) {
            return $q->where('po_yarn_dyeings.po_no', '=' , request('po_no',0));
            })
            ->when(request('pi_no'), function($q) {
            return $q->where('po_yarn_dyeings.pi_no', '=' , request('pi_no',0));
            })
            //->where([['po_yarn_dyeings.supplier_id','=', $invyarnrcv->supplier_id]])
            //->where([['po_yarn_dyeings.company_id','=', $invyarnrcv->company_id]])
            //->whereIn('po_yarn_dyeing_items.id',$po_yarn_item_id)
            ->whereIn('inv_yarn_isu_items.id',$po_yarn_item_id)
            ->get([
            'po_yarn_dyeings.po_no',
            'po_yarn_dyeings.pi_no',
            'po_yarn_dyeings.currency_id',
            'po_yarn_dyeings.exch_rate',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',
            'po_yarn_dyeing_items.id as po_yarn_dyeing_item_id',
            'po_yarn_dyeing_items.qty',
            'po_yarn_dyeing_item_bom_qties.rate as po_yarn_dyeing_item_rate',
            'po_yarn_dyeing_items.amount as po_yarn_dyeing_item_amount',
            'currencies.code as currency_code',
            'colors.name as yarn_color',
            'inv_yarn_isu_items.id as inv_yarn_isu_item_id',
            'inv_yarn_isu_items.rate as inv_yarn_isu_item_rate'
            ])
            ->map(function($poyarn) use($yarnDropdown) {
            $poyarn->yarn_count=$poyarn->count."/".$poyarn->symbol;
            $poyarn->yarn_type=$poyarn->yarn_type;
            $issue_rate=0;
            if($poyarn->currency_id==2){
               $issue_rate=$poyarn->inv_yarn_isu_item_rate;
            }
            else{
                $issue_rate=$poyarn->inv_yarn_isu_item_rate/$poyarn->exch_rate;
            }
            //$poyarn->currency_code='BDT';
            //$poyarn->exch_rate=1;
            $poyarn->rate=$issue_rate+$poyarn->po_yarn_dyeing_item_rate;
            $poyarn->composition=isset($yarnDropdown[$poyarn->item_account_id])?$yarnDropdown[$poyarn->item_account_id]:'';
            return $poyarn;
            });
        }
        return Template::loadView('Inventory.Yarn.InvYarnRcvItemMatrix',['poyarn'=>$poyarn,'store'=>$store,'receive_against_id'=>$invcv->receive_against_id]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvYarnRcvItemRequest $request) {
        $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);

        $issueNo=$this->invyarntransaction
        ->join('inv_yarn_isu_items',function($join){
        $join->on('inv_yarn_isu_items.id','=','inv_yarn_transactions.inv_yarn_isu_item_id');
        })
        ->join('inv_isus',function($join){
        $join->on('inv_isus.id','=','inv_yarn_isu_items.inv_isu_id');
        })
        ->join('inv_yarn_rcv_items',function($join){
        $join->on('inv_yarn_rcv_items.id','=','inv_yarn_transactions.inv_yarn_rcv_item_id');
        })
        ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcvs.id','=','inv_yarn_rcv_items.inv_yarn_rcv_id');
        })
        ->join('inv_rcvs',function($join){
        $join->on('inv_rcvs.id','=','inv_yarn_rcvs.inv_rcv_id');
        })
        ->where([['inv_rcvs.id','=',$invyarnrcv->id]])
        ->where([['inv_yarn_transactions.trans_type_id','=',2]])
        ->get(['inv_isus.issue_no'])
        ->first();
        if($issueNo){
        return response()->json(array('success' => false,'message' => 'New Item Add No Possible, Issue no '.$issueNo->issue_no.' Found '),200);
        }

        if($invyarnrcv->receive_basis_id==2 || $invyarnrcv->receive_basis_id==3) 
        {
            $max = $this->poyarn->where([['company_id', $invyarnrcv->company_id]])->max('po_no');
            $po_no=$max+1;
            \DB::beginTransaction();
            try
            {
                $poyarn = $this->poyarn->create(['po_no'=>$po_no,'po_type_id'=>1,'po_date'=>$invyarnrcv->receive_date,'company_id'=>$invyarnrcv->company_id,'source_id'=>10,'basis_id'=>20,'supplier_id'=>$invyarnrcv->supplier_id,'currency_id'=>2,'exch_rate'=>1,'delv_start_date'=>$invyarnrcv->receive_date,'delv_end_date'=>$invyarnrcv->receive_date,'pay_mode'=>6,'remarks'=>'For Opening Balance Or Unknown','is_system_generated'=>1]);

                $poyarnitem = $this->poyarnitem->create(['po_yarn_id' => $poyarn->id,'item_account_id' => $request->item_account_id,'qty' => $request->qty,'rate' => $request->rate,'amount' => $request->amount,'no_of_bag' => $request->no_of_bag,'remarks' => 'For Opening Balance Or Unknown']);

                $color_name=strtoupper($request->color_id);
                $color = $this->color->firstOrCreate(['name' =>$color_name],['code' => '']);
                $store_qty=$request->qty*1;
                $store_rate=$request->rate*$request->exch_rate;
                $store_amount=$request->amount*$request->exch_rate;
                
                $invyarnitem=$this->invyarnitem->firstOrCreate(['item_account_id' =>$request->item_account_id,'supplier_id'=>$invyarnrcv->supplier_id,'color_id'=>$color->id,'lot'=>strtoupper($request->lot),'brand'=>strtoupper($request->brand)],['deleted_ip' => '']);

                $invyarnrcvitem = $this->invyarnrcvitem->create(
                [
                'inv_yarn_rcv_id'=> $request->inv_yarn_rcv_id,         
                'po_yarn_item_id'=> $poyarnitem->id,
                'inv_yarn_item_id'=> $invyarnitem->id,        
                'store_id'=> $request->store_id,
                'cone_per_bag'=> $request->cone_per_bag,     
                'wgt_per_cone'=> $request->wgt_per_cone,     
                'wgt_per_bag'=> $request->wgt_per_bag,     
                'no_of_bag'=> $request->no_of_bag,
                'qty' => $request->qty,
                'rate' => $request->rate,
                'amount'=> $request->amount,
                'used_yarn'=> $request->used_yarn,
                'store_qty' => $store_qty,
                'store_rate' => $store_rate,
                'store_amount'=> $store_amount,
                'room'=> $request->room,     
                'rack'=> $request->rack,     
                'shelf'=> $request->shelf,
                'remarks' => $request->remarks     
                ]);

                $invyarntransaction=$this->invyarntransaction->create([
                'trans_type_id'=>1,
                'trans_date'=>$invyarnrcv->receive_date,
                'inv_yarn_rcv_item_id'=>$invyarnrcvitem->id,
                'inv_yarn_item_id'=>$invyarnitem->id,
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
        }
        else
        {
            \DB::beginTransaction();
            foreach($request->store_id as $index=>$store_id)
            {
                if($request->qty[$index])
                {
                    $color_name=strtoupper($request->color_id[$index]);
                    $color = $this->color->firstOrCreate(['name' =>$color_name],['code' => '']);
                    $store_qty=$request->qty[$index]*1;
                    $store_rate=$request->rate[$index]*$request->exch_rate[$index];
                    $store_amount=$request->amount[$index]*$request->exch_rate[$index];
                    try
                    {
                        if($invyarnrcv->receive_against_id==3)
                        {
                            $invyarnitem=$this->invyarnitem->firstOrCreate(
                            [
                            'item_account_id'=>$request->item_account_id[$index],
                            'supplier_id'=>$invyarnrcv->supplier_id,
                            'color_id'=>$color->id,
                            'lot'=>strtoupper($request->lot[$index]),
                            'brand'=>strtoupper($request->brand[$index])
                            ],
                            [
                            'deleted_ip' => ''
                            ]);
                        }
                        if($invyarnrcv->receive_against_id==9)
                        {
                            $itemaccount=$this->itemaccount->find($request->item_account_id[$index]);
                            $itemaccount->itemclass_id=2;
                            //$itemaccountArr=$itemaccount->toArray();
                            //array_forget($itemaccountArr, 'id');
                            $itemaccount_new=$this->itemaccount->firstOrCreate([
                                'itemcategory_id'=>$itemaccount->itemcategory_id,
                                'itemclass_id'=>$itemaccount->itemclass_id,
                                'sub_class_name'=>$itemaccount->sub_class_name,
                                'sub_class_code'=>$itemaccount->sub_class_code,
                                'item_nature_id'=>$itemaccount->item_nature_id,
                                'yarncount_id'=>$itemaccount->yarncount_id,
                                'yarntype_id'=>$itemaccount->yarntype_id,
                                'composition_id'=>$itemaccount->composition_id,
                                'item_description'=>$itemaccount->item_description,
                                'specification'=>$itemaccount->specification,
                                'color_id'=>$itemaccount->color_id,
                                'size_id'=>$itemaccount->size_id,
                                'gmt_position'=>$itemaccount->gmt_position,
                                'gmt_category'=>$itemaccount->gmt_category,
                                'gmtspart_id'=>$itemaccount->gmtspart_id,
                                'autoyarn_id'=>$itemaccount->autoyarn_id,
                                'gsm'=>$itemaccount->gsm,
                                'dia'=>$itemaccount->dia,
                                'stitch_length'=>$itemaccount->stitch_length,
                                'mc_gg'=>$itemaccount->mc_gg,
                                'fabric_looks'=>$itemaccount->fabric_looks,
                                'reorder_level'=>$itemaccount->reorder_level,
                                'max_level'=>$itemaccount->max_level,
                                'min_level'=>$itemaccount->min_level,
                                'uom_id'=>$itemaccount->uom_id,
                                'custom_code'=>$itemaccount->custom_code
                            ],[
                                'created_by'=>$itemaccount->created_by,
                                'created_at'=>$itemaccount->created_at,
                                'updated_by'=>$itemaccount->updated_by,
                                'updated_at'=>$itemaccount->updated_at,
                                'deleted_at'=>$itemaccount->deleted_at,
                                'created_ip'=>$itemaccount->created_ip,
                                'updated_ip'=>$itemaccount->updated_ip,
                                'deleted_ip'=>$itemaccount->deleted_ip,
                                'row_status'=>$itemaccount->row_status,
                                'status_id'=>$itemaccount->status_id,
                                'consumption_level_id'=>$itemaccount->consumption_level_id

                            ]);

                            $itemaccountratio=$this->itemaccountratio->where([['item_account_id','=',$request->item_account_id[$index]]])->get();
                            foreach($itemaccountratio as $itemaccountratioRow){
                            $this->itemaccountratio->firstOrCreate([
                            'item_account_id'=>$itemaccount_new->id,
                            'composition_id'=>$itemaccountratioRow->composition_id,
                            'ratio'=>$itemaccountratioRow->ratio,
                            ],[]);
                            }

                            

                            $invyarnitem=$this->invyarnitem->firstOrCreate(
                            [
                            'item_account_id'=>$itemaccount_new->id,
                            'supplier_id'=>$invyarnrcv->supplier_id,
                            'color_id'=>$color->id,
                            'lot'=>strtoupper($request->lot[$index]),
                            'brand'=>strtoupper($request->brand[$index])
                            ],
                            [
                            'deleted_ip' => ''
                            ]);
                        }

                        $invyarnrcvitem = $this->invyarnrcvitem->create(
                        [
                        'inv_yarn_rcv_id'=> $request->inv_yarn_rcv_id,         
                        'po_yarn_item_id'=> $request->po_yarn_item_id[$index],
                        'inv_yarn_isu_item_id'=> $request->inv_yarn_isu_item_id[$index],
                        'inv_yarn_item_id'=> $invyarnitem->id,          
                        'store_id'=> $request->store_id[$index],
                        'cone_per_bag'=> $request->cone_per_bag[$index],     
                        'wgt_per_cone'=> $request->wgt_per_cone[$index],     
                        'wgt_per_bag'=> $request->wgt_per_bag[$index],     
                        'no_of_bag'=> $request->no_of_bag[$index],
                        'qty' => $request->qty[$index],
                        'rate' => $request->rate[$index],
                        'yarn_dyeing_rate' => $request->yarn_dyeing_rate[$index],
                        'amount'=> $request->amount[$index],
                        'used_yarn'=> $request->used_yarn[$index],
                        'store_qty' => $store_qty,
                        'store_rate' => $store_rate,
                        'store_amount'=> $store_amount,
                        'room'=> $request->room[$index],     
                        'rack'=> $request->rack[$index],     
                        'shelf'=> $request->shelf[$index],
                        'remarks' => $request->remarks[$index]     
                        ]);

                        $invyarntransaction=$this->invyarntransaction->create(
                        [
                        'trans_type_id'=>1,
                        'trans_date'=>$invyarnrcv->receive_date,
                        'inv_yarn_rcv_item_id'=>$invyarnrcvitem->id,
                        'inv_yarn_item_id'=>$invyarnitem->id,
                        'company_id'=>$invyarnrcv->company_id,
                        'supplier_id'=>$invyarnrcv->supplier_id,
                        'store_id'=>$request->store_id[$index],
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
                }
                \DB::commit();
            }
        }

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
        //$invyarnrcvitem = $this->invyarnrcvitem->find($id);
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
        //invrcv
        $rcv_item=$this->invyarnrcvitem->find($id);
        $invyarnrcv=$this->invyarnrcv->find($rcv_item->inv_yarn_rcv_id);
        $invcv=$this->invrcv->find($invyarnrcv->inv_rcv_id);

        if($invcv->receive_against_id==3){
            $invyarnrcvitem=$this->invyarnrcvitem
            ->join('inv_yarn_rcvs',function($join){
                $join->on('inv_yarn_rcvs.id','=','inv_yarn_rcv_items.inv_yarn_rcv_id');
            })
            ->join('inv_rcvs',function($join){
                $join->on('inv_rcvs.id','=','inv_yarn_rcvs.inv_rcv_id');
            })
            ->join('po_yarn_items',function($join){
                $join->on('po_yarn_items.id','=','inv_yarn_rcv_items.po_yarn_item_id');
            })
            ->join('po_yarns',function($join){
                $join->on('po_yarns.id','=','po_yarn_items.po_yarn_id');
            })
            ->join('inv_yarn_items',function($join){
                $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id');
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
            ->where([['inv_yarn_rcv_items.id','=',$id]])
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
                'inv_yarn_rcv_items.id',
                'inv_yarn_rcv_items.store_id',
                'inv_yarn_items.lot',
                'inv_yarn_items.brand',
                'colors.name as color_id',
                'inv_yarn_rcv_items.cone_per_bag',
                'inv_yarn_rcv_items.wgt_per_cone',
                'inv_yarn_rcv_items.no_of_bag',
                'inv_yarn_rcv_items.qty',
                'inv_yarn_rcv_items.rate',
                'inv_yarn_rcv_items.amount',
                'inv_yarn_rcv_items.store_qty',
                'inv_yarn_rcv_items.used_yarn',
                'uoms.code as uom',
                'inv_yarn_rcv_items.store_rate',
                'inv_yarn_rcv_items.store_amount',
                'inv_yarn_rcv_items.room',
                'inv_yarn_rcv_items.rack',
                'inv_yarn_rcv_items.shelf',
                'inv_yarn_rcv_items.remarks',
            ])
            ->map(function($invyarnrcvitem) use($yarnDropdown) {
                $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
                $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
                $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
                return $invyarnrcvitem;
            })
            ->first();
        } 
        if($invcv->receive_against_id==9){
            $invyarnrcvitem=$this->invyarnrcvitem
            ->join('inv_yarn_rcvs',function($join){
                $join->on('inv_yarn_rcvs.id','=','inv_yarn_rcv_items.inv_yarn_rcv_id');
            })
            ->join('inv_rcvs',function($join){
                $join->on('inv_rcvs.id','=','inv_yarn_rcvs.inv_rcv_id');
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
                $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id');
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
            ->where([['inv_yarn_rcv_items.id','=',$id]])
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
                'inv_yarn_rcv_items.id',
                'inv_yarn_rcv_items.store_id',
                'inv_yarn_items.lot',
                'inv_yarn_items.brand',
                'colors.name as color_id',
                'inv_yarn_rcv_items.cone_per_bag',
                'inv_yarn_rcv_items.wgt_per_cone',
                'inv_yarn_rcv_items.no_of_bag',
                'inv_yarn_rcv_items.qty',
                'inv_yarn_rcv_items.rate',
                'inv_yarn_rcv_items.amount',
                'inv_yarn_rcv_items.store_qty',
                'inv_yarn_rcv_items.used_yarn',
                'uoms.code as uom',
                'inv_yarn_rcv_items.store_rate',
                'inv_yarn_rcv_items.store_amount',
                'inv_yarn_rcv_items.room',
                'inv_yarn_rcv_items.rack',
                'inv_yarn_rcv_items.shelf',
                'inv_yarn_rcv_items.remarks',
            ])
            ->map(function($invyarnrcvitem) use($yarnDropdown) {
                $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
                $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
                $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
                return $invyarnrcvitem;
            })
            ->first();
        }


        $row ['fromData'] = $invyarnrcvitem;
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
    public function update(InvYarnRcvItemRequest $request, $id) {
        
                $issueNo=$this->invyarntransaction
                ->join('inv_yarn_isu_items',function($join){
                $join->on('inv_yarn_isu_items.id','=','inv_yarn_transactions.inv_yarn_isu_item_id');
                })
                ->join('inv_isus',function($join){
                $join->on('inv_isus.id','=','inv_yarn_isu_items.inv_isu_id');
                })
                ->where([['inv_yarn_transactions.inv_yarn_rcv_item_id','=',$id]])
                ->where([['inv_yarn_transactions.trans_type_id','=',2]])
                ->get(['inv_isus.issue_no'])
                ->first();
                if($issueNo){
                return response()->json(array('success' => false,'message' => 'Update No Possible, Issue no '.$issueNo->issue_no.' Found '),200);

                }


                $invyarnrcv=$this->invrcv->find($request->inv_rcv_id);
                $color_name=strtoupper($request->color_id);
                $color = $this->color->firstOrCreate(['name' =>$color_name],['code' => '']);
                $store_qty=$request->qty*1;
                $store_rate=$request->rate*$request->exch_rate;
                $store_amount=$request->amount*$request->exch_rate;
                \DB::beginTransaction();
                try
                {
                    $invyarnitem=$this->invyarnitem->firstOrCreate(['item_account_id' =>$request->item_account_id,'supplier_id'=>$invyarnrcv->supplier_id,'color_id'=>$color->id,'lot'=>strtoupper($request->lot),'brand'=>strtoupper($request->brand)],['deleted_ip' => '']);


                    $invyarnrcvitem = $this->invyarnrcvitem->update($id,
                    [
                    'inv_yarn_item_id'=> $invyarnitem->id,         
                    'store_id'=> $request->store_id, 
                    'cone_per_bag'=> $request->cone_per_bag,     
                    'wgt_per_cone'=> $request->wgt_per_cone,     
                    'wgt_per_bag'=> $request->wgt_per_bag,     
                    'no_of_bag'=> $request->no_of_bag,
                    'qty' => $request->qty,
                    'rate' => $request->rate,
                    'amount'=> $request->amount,
                    'used_yarn'=> $request->used_yarn,
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
                    'inv_yarn_item_id'=>$invyarnitem->id,
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

        if($this->invyarnrcvitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getYarnItem(){
        $invyarnrcv=$this->invrcv->find(request('inv_rcv_id',0));

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

        if($invyarnrcv->receive_against_id==3)
        {
            $poyarn=$this->poyarn
            ->join('po_yarn_items',function($join){
            $join->on('po_yarn_items.po_yarn_id','=','po_yarns.id')
            ->whereNull('po_yarn_items.deleted_at');
            })
            ->join('item_accounts',function($join){
            $join->on('po_yarn_items.item_account_id','=','item_accounts.id');
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
            ->when(request('po_no'), function($q) {
            return $q->where('po_yarns.po_no', '=' , request('po_no',0));
            })
            ->when(request('pi_no'), function($q) {
            return $q->where('po_yarns.pi_no', '=' , request('pi_no',0));
            })
            ->whereNotNull('po_yarns.approved_at')
            ->where([['po_yarns.supplier_id','=', $invyarnrcv->supplier_id]])
            ->where([['po_yarns.company_id','=', $invyarnrcv->company_id]])
            ->get([
            'po_yarns.po_no',
            'po_yarns.pi_no',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',
            'po_yarn_items.id as po_yarn_item_id',
            'po_yarn_items.qty',
            'po_yarn_items.rate',
            'po_yarn_items.amount',
            'currencies.code as currency_code'
            ])
            ->map(function($poyarn) use($yarnDropdown) {
            $poyarn->yarn_count=$poyarn->count."/".$poyarn->symbol;
            $poyarn->yarn_type=$poyarn->yarn_type;
            $poyarn->composition=isset($yarnDropdown[$poyarn->item_account_id])?$yarnDropdown[$poyarn->item_account_id]:'';
            return $poyarn;
            });
        }
        if($invyarnrcv->receive_against_id==9)
        {
            $poyarn=$this->poyarndyeing
            ->join('po_yarn_dyeing_items',function($join){
            $join->on('po_yarn_dyeings.id','=','po_yarn_dyeing_items.po_yarn_dyeing_id');
            })
            ->join('po_yarn_dyeing_item_bom_qties',function($join){
            $join->on('po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id','=','po_yarn_dyeing_items.id');
            })
            ->leftJoin('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','po_yarn_dyeing_items.inv_yarn_item_id'); 
            })
            ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_yarn_items.item_account_id'); 
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
            ->join('inv_yarn_isu_items',function($join){
            $join->on('inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id','=','po_yarn_dyeing_item_bom_qties.id');
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
            ->join('colors',function($join){
            $join->on('colors.id','=','style_fabrication_stripes.color_id');
            })
            ->when(request('po_no'), function($q) {
            return $q->where('po_yarn_dyeings.po_no', '=' , request('po_no',0));
            })
            ->when(request('pi_no'), function($q) {
            return $q->where('po_yarn_dyeings.pi_no', '=' , request('pi_no',0));
            })
            ->whereNotNull('po_yarn_dyeings.approved_by')
            ->where([['po_yarn_dyeings.supplier_id','=', $invyarnrcv->supplier_id]])
            ->where([['po_yarn_dyeings.company_id','=', $invyarnrcv->company_id]])
            ->get([
            'po_yarn_dyeings.po_no',
            'po_yarn_dyeings.pi_no',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',
            'po_yarn_dyeing_items.id',
            'inv_yarn_isu_items.id as po_yarn_item_id',
            'inv_yarn_isu_items.qty',
            'inv_yarn_isu_items.amount',
            //'po_yarn_dyeing_items.qty',
            //'po_yarn_dyeing_items.rate',
            //'po_yarn_dyeing_items.amount',
            'currencies.code as currency_code',
            'colors.name as yarn_color'
            ])
            ->map(function($poyarn) use($yarnDropdown) {
            $poyarn->yarn_count=$poyarn->count."/".$poyarn->symbol;
            $poyarn->yarn_type=$poyarn->yarn_type;
            $poyarn->composition=isset($yarnDropdown[$poyarn->item_account_id])?$yarnDropdown[$poyarn->item_account_id]:'';
            return $poyarn;
            });
        }
        echo json_encode($poyarn);
    }

    public function importyarn()
    {
            //$poyarn=$this->poyarn->find(request('po_yarn_id',0));
            $yarnDescription=$this->itemaccount
            ->join('item_account_ratios',function($join){
            $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
            })
            ->join('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
            })
            ->join('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
            })
            ->join('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->join('compositions',function($join){
            $join->on('compositions.id','=','item_account_ratios.composition_id');
            })
            ->join('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->where([['itemcategories.identity','=',1]])
            ->get([
            'item_accounts.id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'compositions.name as composition_name',
            'item_account_ratios.ratio',
            ]);
            $itemaccountArr=array();
            $yarnCompositionArr=array();
            foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
            $itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
            }
            $yarnDropdown=array();
            foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
            }

            $rows=$this->itemaccount
            ->join('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
            })
            ->join('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
            })
            ->join('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->join('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->join('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->where([['itemcategories.identity','=',1]])
            ->get([
            'item_accounts.id',
            'uoms.code as uom',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'itemcategories.name as itemcategory_name',
            ])
            ->map(function ($rows) use($yarnDropdown)  {
            $rows->composition = isset($yarnDropdown[$rows->id])?$yarnDropdown[$rows->id]:'';
            $rows->yarn_count = $rows->count."/".$rows->symbol;;
            $rows->item_account_id = $rows->id;
            return $rows;
            });
            echo json_encode($rows);
    }
}