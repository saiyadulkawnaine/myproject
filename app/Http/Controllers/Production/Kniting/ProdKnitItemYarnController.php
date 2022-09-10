<?php

namespace App\Http\Controllers\Production\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitItemYarnRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\GmtssampleRepository;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Production\Kniting\ProdKnitItemYarnRequest;

class ProdKnitItemYarnController extends Controller {

    
    private $prodknit;
    private $prodknititem;
    private $prodknititemyarn;
    private $color;
    private $gmtssample;
    private $invisu;
    private $itemaccount;
  

    public function __construct(
        ProdKnitRepository $prodknit, 
        ProdKnitItemRepository $prodknititem, 
        ProdKnitItemYarnRepository $prodknititemyarn,
        ColorRepository $color,
        GmtssampleRepository $gmtssample,
        InvIsuRepository $invisu,
        ItemAccountRepository $itemaccount

        ) 
    {
        $this->prodknit = $prodknit;
        $this->prodknititem = $prodknititem;
        $this->prodknititemyarn = $prodknititemyarn;
        $this->color = $color;
        $this->gmtssample = $gmtssample;
        $this->invisu = $invisu;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');

        $this->middleware('permission:view.prodknititemyarns',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodknititemyarns', ['only' => ['store']]);
        $this->middleware('permission:edit.prodknititemyarns',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodknititemyarns', ['only' => ['destroy']]); 
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

        $rows=$this->prodknititem
         ->leftJoin('prod_knit_item_yarns', function($join)  {
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_yarns.prod_knit_item_id');
        })
        ->leftJoin('inv_yarn_isu_items', function($join)  {
            $join->on('inv_yarn_isu_items.id', '=', 'prod_knit_item_yarns.inv_yarn_isu_item_id');
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
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_yarn_items.supplier_id');
        })
         ->where([['prod_knit_items.id','=',request('prod_knit_item_id',0)]])
         ->orderBy('prod_knit_item_yarns.id','desc')
        ->get([
                'prod_knit_item_yarns.*',
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
    public function store(ProdKnitItemYarnRequest $request) {
        
        
		$prodknititemyarn=$this->prodknititemyarn->create($request->except(['id','inv_yarn_item_id']));
        if($prodknititemyarn){
            return response()->json(array('success' => true,'id' =>  $prodknititemyarn->id,'message' => 'Save Successfully'),200);
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
        
       // $prodknititemyarn=$this->prodknititemyarn->find($id);
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
        $rows=$this->prodknititemyarn
         ->leftJoin('prod_knit_items', function($join)  {
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_yarns.prod_knit_item_id');
        })
        ->leftJoin('inv_yarn_isu_items', function($join)  {
            $join->on('inv_yarn_isu_items.id', '=', 'prod_knit_item_yarns.inv_yarn_isu_item_id');
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
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_yarn_items.supplier_id');
        })
        ->where([['prod_knit_item_yarns.id','=',$id]])
        ->orderBy('prod_knit_item_yarns.id','desc')
        ->get([
                'prod_knit_item_yarns.*',
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
                'suppliers.name as supplier_name',
        ])
        ->map(function($rows) use($yarnDropdown) {
        $rows->yarn_count=$rows->count."/".$rows->symbol;
        $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
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
    public function update(ProdKnitItemYarnRequest $request, $id) {
        
        $prodknititemyarn=$this->prodknititemyarn->update($id,$request->except(['id','inv_yarn_item_id']));
        if($prodknititemyarn){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodknititemyarn->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getYarn()
    {
        $prod_knit_item_id=request('prod_knit_item_id',0);
        $item=$this->prodknititem->find($prod_knit_item_id);
        $prod=$this->prodknit->find($item->prod_knit_id);
        
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
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_yarn_items.supplier_id');
        })
        ->where([['inv_isus.supplier_id','=',$prod->supplier_id]])
        ->when(request('lot'), function ($q) {
        return $q->where('inv_yarn_items.lot', 'like','%'.request('lot', 0).'%');
        })
        ->when(request('brand'), function ($q) {
        return $q->where('inv_yarn_items.brand', 'like','%'.request('brand', 0).'%');
        })
        ->when(request('yarn_supplier_id'), function ($q) {
        return $q->where('inv_yarn_items.supplier_id', '=',request('yarn_supplier_id', 0));
        })
        
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
        'suppliers.name as supplier_name',
        ])
        ->map(function($rows) use($yarnDropdown) {
        $rows->yarn_count=$rows->count."/".$rows->symbol;
        $rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
        return $rows;
        });
        echo json_encode($rows);
    }
}