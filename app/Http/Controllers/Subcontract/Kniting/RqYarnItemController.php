<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnRepository;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnFabricationRepository;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnItemRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\RqYarnItemRequest;

class RqYarnItemController extends Controller {

    private $rqyarn;
    private $rqyarnfabrication;
    private $rqyarnitem;
    private $itemaccount;
    private $invyarnitem;
    


    public function __construct(
        RqYarnRepository $rqyarn,
        RqYarnFabricationRepository $rqyarnfabrication,
        RqYarnItemRepository $rqyarnitem,
        ItemAccountRepository $itemaccount,
        InvYarnItemRepository $invyarnitem
    ) {
        $this->rqyarn = $rqyarn;
        $this->rqyarnfabrication = $rqyarnfabrication;
        $this->rqyarnitem = $rqyarnitem;
        $this->itemaccount = $itemaccount;
        $this->invyarnitem = $invyarnitem;
/*  
        $this->middleware('auth');
        $this->middleware('permission:view.rqyarnitems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.rqyarnitems', ['only' => ['store']]);
        $this->middleware('permission:edit.rqyarnitems',   ['only' => ['update']]);
        $this->middleware('permission:delete.rqyarnitems', ['only' => ['destroy']]);

        */
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


        $invyarnitem=$this->rqyarnfabrication
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
        ->where([['rq_yarn_fabrications.id','=',request('rq_yarn_fabrication_id',0)]])
        ->get([
        'rq_yarn_items.id',
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
        ])
        ->map(function($invyarnitem) use($yarnDropdown) {
        $invyarnitem->yarn_count=$invyarnitem->count."/".$invyarnitem->symbol;
        $invyarnitem->yarn_type=$invyarnitem->yarn_type;
        $invyarnitem->composition=isset($yarnDropdown[$invyarnitem->item_account_id])?$yarnDropdown[$invyarnitem->item_account_id]:'';
        return $invyarnitem;
        }); 
        echo json_encode($invyarnitem);
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
    public function store(RqYarnItemRequest $request) {
        $master=$this->rqyarn->find($request->rq_yarn_id);
        if($master->approved_by && $master->approved_at){
            return response()->json(array('success' => false,'message' => 'It is Approved,So Update Not Possible'),200);
        }
        $rqyarnitem = $this->rqyarnitem->create(['inv_yarn_item_id'=>$request->inv_yarn_item_id,'rq_yarn_fabrication_id'=>$request->rq_yarn_fabrication_id,'qty'=>$request->qty,'remarks'=>$request->remarks]);
        if($rqyarnitem){
            return response()->json(array('success' => true,'id' =>  $rqyarnitem->id,'rq_yarn_fabrication_id' =>  $request->rq_yarn_fabrication_id,'message' => 'Save Successfully'),200);
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


        $rqyarnitem=$this->rqyarnitem
        ->join('rq_yarn_fabrications',function($join){
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
        ->where([['rq_yarn_items.id','=',$id]])
        ->get([
        'rq_yarn_items.id',
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
        ])
        ->map(function($rqyarnitem) use($yarnDropdown) {
        $rqyarnitem->yarn_count=$rqyarnitem->count."/".$rqyarnitem->symbol;
        $rqyarnitem->yarn_type=$rqyarnitem->yarn_type;
        $rqyarnitem->composition=isset($yarnDropdown[$rqyarnitem->item_account_id])?$yarnDropdown[$rqyarnitem->item_account_id]:'';
        return $rqyarnitem;
        })->first();
        $row ['fromData'] = $rqyarnitem;
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
    public function update(RqYarnItemRequest $request, $id) {
        $master=$this->rqyarn->find($request->rq_yarn_id);
        if($master->approved_by && $master->approved_at){
            return response()->json(array('success' => false,'message' => 'It is Approved,So Update Not Possible'),200);
        }

        $rqyarnitem=$this->rqyarnitem->update($id,$request->except(['id','rq_yarn_fabrication_id','inv_yarn_item_id','rq_yarn_id']));
        if($rqyarnitem){
            return response()->json(array('success' => true,'id' => $id,'rq_yarn_fabrication_id' =>  $request->rq_yarn_fabrication_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->rqyarnitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getItem()
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
        ->leftJoin(\DB::raw("(SELECT 
          inv_yarn_transactions.inv_yarn_item_id,
          sum(inv_yarn_transactions.store_qty) as qty 
          FROM inv_yarn_transactions 
          where  inv_yarn_transactions.deleted_at is null
          group by inv_yarn_transactions.inv_yarn_item_id
        ) stock"), "stock.inv_yarn_item_id", "=", "inv_yarn_items.id")
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
        'uoms.code as uom',
        'stock.qty as stock_qty',
        ])
        ->map(function($invyarnitem) use($yarnDropdown) {
        $invyarnitem->yarn_count=$invyarnitem->count."/".$invyarnitem->symbol;
        $invyarnitem->yarn_type=$invyarnitem->yarn_type;
        $invyarnitem->composition=isset($yarnDropdown[$invyarnitem->item_account_id])?$yarnDropdown[$invyarnitem->item_account_id]:'';
        return $invyarnitem;
        }); 
        echo json_encode($invyarnitem);
        
    }
}