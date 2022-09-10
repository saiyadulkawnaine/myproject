<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\SoKnitYarnRcvItemRequest;

class SoKnitYarnRcvItemController extends Controller {

    private $soknit;
    private $soknitpoitem;
    private $soknityarnrcv;
    private $soknityarnrcvitem;
    private $uom;
    private $itemaccount;
    private $color;

    public function __construct(
        SoKnitRepository $soknit,
        SoKnitPoItemRepository $soknitpoitem,
        SoKnitYarnRcvRepository $soknityarnrcv,
        SoKnitYarnRcvItemRepository $soknityarnrcvitem,
        UomRepository $uom,
        ItemAccountRepository $itemaccount,
        ColorRepository $color
 
        ) {
        $this->soknit = $soknit;
        $this->soknitpoitem = $soknitpoitem;
        $this->soknityarnrcv = $soknityarnrcv;
        $this->soknityarnrcvitem = $soknityarnrcvitem;
        $this->uom = $uom;
        $this->itemaccount = $itemaccount;
        $this->color = $color;
         
        $this->middleware('auth');
        $this->middleware('permission:view.soknityarnrcvitems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soknityarnrcvitems', ['only' => ['store']]);
        $this->middleware('permission:edit.soknityarnrcvitems',   ['only' => ['update']]);
        $this->middleware('permission:delete.soknityarnrcvitems', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
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
        /*->leftJoin('smp_cost_yarns',function($join){
        $join->on('smp_cost_yarns.item_account_id','=','item_accounts.id');
        })*/
        ->where([['itemcategories.identity','=',1]])
        ->when(request('count_name'), function ($q) {
            return $q->where('yarncounts.count', 'LIKE', "%".request('count_name', 0)."%");
        })
        ->when(request('type_name'), function ($q) {
            return $q->where('yarntypes.name', 'LIKE', "%".request('type_name', 0)."%");
        })
        ->get([
        'item_accounts.id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'itemclasses.name as itemclass_name',
        'compositions.name as composition_name',
        'item_account_ratios.ratio'
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

       return response()->json(
          $this->soknityarnrcv
          ->leftJoin('so_knit_yarn_rcv_items', function($join)  {
            $join->on('so_knit_yarn_rcvs.id', '=', 'so_knit_yarn_rcv_items.so_knit_yarn_rcv_id');
          })
          ->leftJoin('item_accounts', function($join)  {
            $join->on('item_accounts.id', '=', 'so_knit_yarn_rcv_items.item_account_id');
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
          ->leftJoin('uoms', function($join)  {
            $join->on('uoms.id', '=', 'so_knit_yarn_rcv_items.uom_id');
          })
          ->leftJoin('colors', function($join)  {
            $join->on('colors.id', '=', 'so_knit_yarn_rcv_items.color_id');
          })
          ->where([['so_knit_yarn_rcvs.id','=',request('so_knit_yarn_rcv_id',0)]])
          ->orderBy('so_knit_yarn_rcv_items.id','desc')
          ->get([
            'so_knit_yarn_rcv_items.*',
            'yarncounts.count',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'yarncounts.symbol',
            'colors.name as color_name',
            'uoms.name as uom_name'
          ])
          ->map(function($rows) use($yarnDropdown){
            $rows->composition=$yarnDropdown[$rows->item_account_id];
            $rows->count=$rows->count."/".$rows->symbol;
            return $rows;
          })
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoKnitYarnRcvItemRequest $request) {
        $color = $this->color->firstOrCreate(['name' => $request->yarn_color],['code' => '']);
        $request->request->add(['color_id' => $color->id]);


        $soknityarnrcvitem=$this->soknityarnrcvitem->create($request->except(['id','yarn_color']));
        
        if($soknityarnrcvitem){
          return response()->json(array('success' => true,'id' =>  $soknityarnrcvitem->id,'so_knit_yarn_rcv_id' =>  $request->so_knit_yarn_rcv_id,'message' => 'Save Successfully'),200);
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
        /*->leftJoin('smp_cost_yarns',function($join){
        $join->on('smp_cost_yarns.item_account_id','=','item_accounts.id');
        })*/
        ->where([['itemcategories.identity','=',1]])
        ->when(request('count_name'), function ($q) {
            return $q->where('yarncounts.count', 'LIKE', "%".request('count_name', 0)."%");
        })
        ->when(request('type_name'), function ($q) {
            return $q->where('yarntypes.name', 'LIKE', "%".request('type_name', 0)."%");
        })
        ->get([
        'item_accounts.id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'itemclasses.name as itemclass_name',
        'compositions.name as composition_name',
        'item_account_ratios.ratio'
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
        $soknityarnrcvitem=$this->soknityarnrcvitem
        ->leftJoin('so_knit_yarn_rcvs', function($join)  {
        $join->on('so_knit_yarn_rcvs.id', '=', 'so_knit_yarn_rcv_items.so_knit_yarn_rcv_id');
        })
        ->leftJoin('item_accounts', function($join)  {
        $join->on('item_accounts.id', '=', 'so_knit_yarn_rcv_items.item_account_id');
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
        ->leftJoin('uoms', function($join)  {
        $join->on('uoms.id', '=', 'so_knit_yarn_rcv_items.uom_id');
        })
        ->leftJoin('colors', function($join)  {
        $join->on('colors.id', '=', 'so_knit_yarn_rcv_items.color_id');
        })
        ->where([['so_knit_yarn_rcv_items.id','=',$id]])
        ->get([
        'so_knit_yarn_rcv_items.*',
        'yarncounts.count',
        'yarntypes.name as yarn_type',
        'itemclasses.name as itemclass_name',
        'yarncounts.symbol',
        'colors.name as yarn_color',
        'uoms.name as uom_name'
        ])
        ->map(function($rows) use($yarnDropdown){
        $rows->item_description=$yarnDropdown[$rows->item_account_id];
        $rows->count=$rows->count."/".$rows->symbol;
        return $rows;
        })
        ->first();
        $row ['fromData'] = $soknityarnrcvitem;
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
    public function update(SoKnitYarnRcvItemRequest $request, $id) {
        $color = $this->color->firstOrCreate(['name' => $request->yarn_color],['code' => '']);
        $request->request->add(['color_id' => $color->id]);
        $soknityarnrcvitem=$this->soknityarnrcvitem->update($id,$request->except(['id','yarn_color']));
        if($soknityarnrcvitem){
            return response()->json(array('success' => true,'id' => $id,'so_knit_yarn_rcv_id' =>  $request->so_knit_yarn_rcv_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->soknityarnrcvitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    

    public function getItem()
    {
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
        /*->leftJoin('smp_cost_yarns',function($join){
        $join->on('smp_cost_yarns.item_account_id','=','item_accounts.id');
        })*/
        ->where([['itemcategories.identity','=',1]])
        ->when(request('count_name'), function ($q) {
            return $q->where('yarncounts.count', 'LIKE', "%".request('count_name', 0)."%");
        })
        ->when(request('type_name'), function ($q) {
            return $q->where('yarntypes.name', 'LIKE', "%".request('type_name', 0)."%");
        })
        ->get([
        'item_accounts.id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'itemclasses.name as itemclass_name',
        'compositions.name as composition_name',
        'item_account_ratios.ratio'
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

        
      $yarn=array();
      $yarns=array();
      foreach($yarnDescription as $row){
        $yarn[$row->id]['id']=$row->id;
        $yarn[$row->id]['itemclass_name']=$row->itemclass_name;
        $yarn[$row->id]['count']=$row->count."/".$row->symbol;
        $yarn[$row->id]['yarn_type']=$row->yarn_type;
        $yarn[$row->id]['composition_name']=$yarnDropdown[$row->id];
        $yarn[$row->id]['smp_ratio']=$row->smp_ratio;
        $yarn[$row->id]['rate']=$row->rate;
      }
      foreach($yarn as $row){
        array_push($yarns,$row);
      }
      echo json_encode($yarns);

    }
}