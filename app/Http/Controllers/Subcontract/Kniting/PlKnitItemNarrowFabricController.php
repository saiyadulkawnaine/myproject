<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemNarrowfabricRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderProductRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\SizeRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\PlKnitItemNarrowfabricRequest;

class PlKnitItemNarrowFabricController extends Controller {

    private $plknititemnarrowfabric;
    private $plknititem;
    private $subinbmarketing;
    private $company;
    private $buyer;
    private $uom;
    private $size;

    public function __construct(PlKnitItemNarrowfabricRepository $plknititemnarrowfabric,PlKnitItemRepository $plknititem,BuyerRepository $buyer,CompanyRepository $company, UomRepository $uom, SubInbMarketingRepository $subinbmarketing, ItemAccountRepository $itemaccount, ItemclassRepository $itemclass, ItemcategoryRepository $itemcategory, SubInbOrderProductRepository $subinborderproduct,SizeRepository $size) {
        $this->plknititemnarrowfabric = $plknititemnarrowfabric;
        $this->plknititem = $plknititem;
        $this->subinbmarketing = $subinbmarketing;
        $this->subinborderproduct = $subinborderproduct;
        $this->itemaccount = $itemaccount;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;
        $this->size = $size;
 
        $this->middleware('auth');
       /*  $this->middleware('permission:view.plknititemnarrowfabrics',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.plknititemnarrowfabrics', ['only' => ['store']]);
        $this->middleware('permission:edit.plknititemnarrowfabrics',   ['only' => ['update']]);
        $this->middleware('permission:delete.plknititemnarrowfabrics', ['only' => ['destroy']]); */

       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
         
        
        $rows=$this->plknititemnarrowfabric
        ->leftJoin('sizes', function($join)  {
            $join->on('sizes.id', '=', 'pl_knit_item_narrowfabrics.size_id');
        })
        ->orderBy('pl_knit_item_narrowfabrics.id','desc')
        ->get([
            'pl_knit_item_narrowfabrics.*',
            'sizes.name as size_id',
		]);
        echo json_encode($rows);
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
    public function store(PlKnitItemNarrowfabricRequest $request) {
        $size= $this->size->firstOrCreate(['name' => $request->gmt_size_id],['code' => $request->size_code]);
		$plknititemnarrowfabric=$this->plknititemnarrowfabric->create(['pl_knit_item_id'=>$request->pl_knit_item_id,'size_id'=>$size->id,'measurment'=>$request->measurment,'capacity'=>$request->capacity,'qty'=>$request->qty]);
        if($plknititemnarrowfabric){
            return response()->json(array('success' => true,'id' =>  $plknititemnarrowfabric->id,'message' => 'Save Successfully'),200);
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
         $rows=$this->plknititemnarrowfabric
        ->leftJoin('sizes', function($join)  {
            $join->on('sizes.id', '=', 'pl_knit_item_narrowfabrics.size_id');
        })
        ->where([['pl_knit_item_narrowfabrics.id','=',$id]])
        ->get([
            'pl_knit_item_narrowfabrics.*',
            'sizes.name as gmt_size_id',
        ])
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
    public function update(PlKnitItemNarrowfabricRequest $request, $id) {
        $plknititemnarrowfabric=$this->plknititemnarrowfabric->update($id,$request->except(['id']));
        if($plknititemnarrowfabric){
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
        if($this->plknititemnarrowfabric->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
    public function getsize(Request $request) {
        //return $this->plknititemnarrowfabric->where([['style_ref', 'LIKE', '%'.$request->q.'%']])->orderBy('style_ref', 'asc')->get(['style_ref as name']);
       return $this->plknititem
        ->join('so_knit_refs', function($join)  {
            $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
        })
        ->join('so_knit_po_items', function($join)  {
            $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
        })
        ->join('po_knit_service_item_qties',function($join){
              $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
        })
        ->join('po_knit_service_items',function($join){
                 $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                 ->whereNull('po_knit_service_items.deleted_at');
        })
        ->join('budget_fabric_prods',function($join){
                 $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
        })
        ->join('budget_fabrics',function($join){
             $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->join('style_fabrications',function($join){
             $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('budget_fabric_cons',function($join){
            $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');
            $join->on('budget_fabric_cons.dia','=','po_knit_service_item_qties.dia');
            $join->on('budget_fabric_cons.measurment','=','po_knit_service_item_qties.measurment');
        })
        ->join('sales_order_gmt_color_sizes',function($join){
            $join->on('sales_order_gmt_color_sizes.id','=','budget_fabric_cons.sales_order_gmt_color_size_id');
        })
        ->join('sales_order_countries',function($join){
            $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
            $join->on('sales_order_countries.sale_order_id','=','po_knit_service_item_qties.sales_order_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
        })
        ->join('style_gmt_color_sizes',function($join){
            $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->join('style_sizes',function($join){
            $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
            $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->where([['pl_knit_items.id','=',request('pl_knit_item_id',0)]])
        ->get(['sizes.name as name']);
        

    }
}