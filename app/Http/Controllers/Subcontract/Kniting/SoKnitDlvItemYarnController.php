<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvItemYarnRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\SoKnitDlvItemYarnRequest;

class SoKnitDlvItemYarnController extends Controller {

    private $soknitdlvitemyarn;
    private $soknitdlv;
    private $soknitdlvitem;
    private $soknit;
    private $soknitpoitem;
    private $soknityarnrcv;
    private $soknityarnrcvitem;
    private $uom;
    private $itemaccount;
    private $color;
    private $autoyarn;
    private $gmtspart;
    private $colorrange;

    public function __construct(
        SoKnitDlvItemYarnRepository $soknitdlvitemyarn,
        SoKnitDlvRepository $soknitdlv,
        SoKnitDlvItemRepository $soknitdlvitem,
        SoKnitRepository $soknit,
        SoKnitPoItemRepository $soknitpoitem,
        SoKnitYarnRcvRepository $soknityarnrcv,
        SoKnitYarnRcvItemRepository $soknityarnrcvitem,
        UomRepository $uom,
        ItemAccountRepository $itemaccount,
        ColorRepository $color,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        ColorrangeRepository $colorrange

 
        ) {
        $this->soknitdlvitemyarn = $soknitdlvitemyarn;
        $this->soknitdlv = $soknitdlv;
        $this->soknitdlvitem = $soknitdlvitem;
        $this->soknit = $soknit;
        $this->soknitpoitem = $soknitpoitem;
        $this->soknityarnrcv = $soknityarnrcv;
        $this->soknityarnrcvitem = $soknityarnrcvitem;
        $this->uom = $uom;
        $this->itemaccount = $itemaccount;
        $this->color = $color;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->colorrange = $colorrange;
         
        $this->middleware('auth');
        $this->middleware('permission:view.soknitdlvitemyarns',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soknitdlvitemyarns', ['only' => ['store']]);
        $this->middleware('permission:edit.soknitdlvitemyarns',   ['only' => ['update']]);
        $this->middleware('permission:delete.soknitdlvitemyarns', ['only' => ['destroy']]);
       
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
            $this->soknitdlvitem
            ->join('so_knit_dlv_item_yarns', function($join)  {
            $join->on('so_knit_dlv_item_yarns.so_knit_dlv_item_id', '=', 'so_knit_dlv_items.id');
            })

            ->join('so_knit_yarn_rcv_items', function($join)  {
            $join->on('so_knit_yarn_rcv_items.id', '=', 'so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id');
            })

            ->join('so_knit_yarn_rcvs', function($join)  {
            $join->on('so_knit_yarn_rcvs.id', '=', 'so_knit_yarn_rcv_items.so_knit_yarn_rcv_id');
            })

            ->join('item_accounts', function($join)  {
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
            ->join('so_knits',function($join){
            $join->on('so_knits.id','=','so_knit_yarn_rcvs.so_knit_id');
            })
            ->join('so_knit_refs',function($join){
            $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            $join->on('so_knit_dlv_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->where([['so_knit_dlv_items.id','=',request('so_knit_dlv_item_id',0)]])
            ->orderBy('so_knit_dlv_item_yarns.id','desc')
            ->get([
            'so_knit_dlv_item_yarns.*',
            'so_knit_yarn_rcv_items.item_account_id',
            'so_knit_yarn_rcv_items.lot',
            'so_knit_yarn_rcv_items.supplier_name',
            'yarncounts.count',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'yarncounts.symbol',
            'colors.name as color_name',
            'uoms.code as uom_name'
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
    public function store(SoKnitDlvItemYarnRequest $request) {

        
        $soknitdlvitemyarn=$this->soknitdlvitemyarn->create([
        'so_knit_dlv_item_id'=>$request->so_knit_dlv_item_id,
        'so_knit_yarn_rcv_item_id'=>$request->so_knit_yarn_rcv_item_id,
        'qty'=>$request->qty,
        'remarks'=>$request->remarks,
        ]);

        if($soknitdlvitemyarn){
        return response()->json(array('success' => true,'id' =>  $soknitdlvitemyarn->id,'so_knit_dlv_item_id' =>  $request->so_knit_dlv_item_id,'message' => 'Save Successfully'),200);
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

        $rows=$this->soknitdlvitemyarn
            ->join('so_knit_dlv_items', function($join)  {
            $join->on('so_knit_dlv_item_yarns.so_knit_dlv_item_id', '=', 'so_knit_dlv_items.id');
            })

            ->join('so_knit_yarn_rcv_items', function($join)  {
            $join->on('so_knit_yarn_rcv_items.id', '=', 'so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id');
            })

            ->join('so_knit_yarn_rcvs', function($join)  {
            $join->on('so_knit_yarn_rcvs.id', '=', 'so_knit_yarn_rcv_items.so_knit_yarn_rcv_id');
            })

            ->join('item_accounts', function($join)  {
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
            ->join('so_knits',function($join){
            $join->on('so_knits.id','=','so_knit_yarn_rcvs.so_knit_id');
            })
            ->join('so_knit_refs',function($join){
            $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            })
            ->where([['so_knit_dlv_item_yarns.id','=',$id]])
            ->orderBy('so_knit_dlv_item_yarns.id','desc')
            ->get([
            'so_knit_dlv_item_yarns.*',
            'so_knit_yarn_rcv_items.item_account_id',
            'so_knit_yarn_rcv_items.lot',
            'so_knit_yarn_rcv_items.supplier_name as supplier',
            'yarncounts.count',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'yarncounts.symbol',
            'colors.name as color_name',
            'uoms.code as uom_name'
            ])
            ->map(function($rows) use($yarnDropdown){
            $rows->item_desc=$yarnDropdown[$rows->item_account_id];
            $rows->yarn_count=$rows->count."/".$rows->symbol;
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
    public function update(SoKnitDlvItemYarnRequest $request, $id) {
        $soknitdlvitemyarn=$this->soknitdlvitemyarn->update($id,[
        'so_knit_dlv_item_id'=>$request->so_knit_dlv_item_id,
        'so_knit_yarn_rcv_item_id'=>$request->so_knit_yarn_rcv_item_id,
        'qty'=>$request->qty,
        'remarks'=>$request->remarks,
        ]);
        
        if($soknitdlvitemyarn){
        return response()->json(array('success' => true,'id' =>  $id,'so_knit_dlv_item_id' =>  $request->so_knit_dlv_item_id,'message' => 'Save Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->soknitdlvitemyarn->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    

    public function getItem()
    {
            $soknitdlvitem=$this->soknitdlvitem->find(request('so_knit_dlv_item_id',0));

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
            ->join('so_knits',function($join){
            $join->on('so_knits.id','=','so_knit_yarn_rcvs.so_knit_id');
            })
            ->join('so_knit_refs',function($join){
            $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            })
            ->where([['so_knit_refs.id','=',$soknitdlvitem->so_knit_ref_id]])
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
}