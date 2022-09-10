<?php

namespace App\Http\Controllers\Subcontract\Inbound;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderProductRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\UomRepository;

use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Inbound\SubInbOrderProductRequest;

class SubInbOrderProductController extends Controller {

    private $subinborderproduct;
    private $subinborder;
    private $itemaccount;
    private $uom;


    public function __construct(SubInbOrderRepository $subinborder, SubInbOrderProductRepository $subinborderproduct, ItemAccountRepository $itemaccount, UomRepository $uom,ItemcategoryRepository $itemcategory, ItemclassRepository $itemclass) {
        $this->subinborderproduct = $subinborderproduct;
        $this->subinborder = $subinborder;
        $this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;
        $this->uom = $uom;

        $this->middleware('auth');
            $this->middleware('permission:view.subinborderproducts',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.subinborderproducts', ['only' => ['store']]);
            $this->middleware('permission:edit.subinborderproducts',   ['only' => ['update']]);
            $this->middleware('permission:delete.subinborderproducts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
        $subinborderproducts=array();
        $rows=$this->subinborderproduct
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','sub_inb_order_products.item_account_id');
        })
        ->when(request('color'), function ($q) {
            return $q->where('sub_inb_order_products.color', 'like', '%'.request('color', 0).'%');
        })
        ->when(request('size'), function ($q) {
            return $q->where('sub_inb_order_products.size', 'like', '%'.request('size', 0).'%');
        })
        ->where([['sub_inb_order_id','=',request('sub_inb_order_id',0)]])
        ->orderBy('sub_inb_order_products.id','desc')
        ->get([
            'sub_inb_order_products.*',
            'item_accounts.id as item_account_id',
            'item_accounts.item_description'
        ]);
        foreach($rows as $row){
            $subinborderproduct['id']=$row->id;
            $subinborderproduct['sub_inb_order_id']=$row->sub_inb_order_id;
            $subinborderproduct['item_account_id']=$row->item_account_id;
		    $subinborderproduct['item_description']=$row->item_description;
            $subinborderproduct['uom_id']=$uom[$row->uom_id];
            $subinborderproduct['gsm']=$row->gsm;
            $subinborderproduct['dia']=$row->dia;
            $subinborderproduct['color']=$row->color;
		    $subinborderproduct['size']=$row->size;
		    $subinborderproduct['smv']=$row->smv;
            $subinborderproduct['qty']=number_format($row->qty,2,'.',',');
            $subinborderproduct['rate']=$row->rate;
            $subinborderproduct['amount']=number_format($row->amount,2,'.',',');
            $subinborderproduct['delivery_date']=$row->delivery_date;
		    $subinborderproduct['delivery_point']=$row->delivery_point;

            array_push($subinborderproducts,$subinborderproduct);
        }
        echo json_encode($subinborderproducts);
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubInbOrderProductRequest $request) {
		$subinborderproduct=$this->subinborderproduct->create($request->except(['id','item_description']));
        if($subinborderproduct){
            return response()->json(array('success' => true,'id' =>  $subinborderproduct->id,'message' => 'Save Successfully'),200);
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
        $subinborderproduct = $this->subinborderproduct
/*         ->selectRaw('sub_inb_order_products.id,
            sub_inb_order_products.item_account_id,
            sub_inb_order_products.rate,
            sub_inb_order_products.rate,
            sub_inb_order_products.rate,
            sub_inb_order_products.rate,
            sub_inb_order_products.rate,
            sub_inb_order_products.rate,
            sub_inb_order_products.rate,
            item_accounts.item_description, 
            sub_inb_orders.id as sub_inb_order_id') */
            ->join('sub_inb_orders',function($join){
                $join->on('sub_inb_orders.id','=','sub_inb_order_products.sub_inb_order_id');
             })
            ->join('item_accounts',function($join){
                $join->on('item_accounts.id','=','sub_inb_order_products.item_account_id');
            })
            ->join('itemcategories',function($join){
                $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->join('itemclasses',function($join){
                $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
        
        ->where([['sub_inb_order_products.id','=',$id]])
        ->get([
           'sub_inb_order_products.*',
           'sub_inb_orders.id as sub_inb_order_id',
           'item_accounts.id as item_account_id',
           'item_accounts.item_description'
       ])
       ->first();
        $row ['fromData'] = $subinborderproduct;
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
    public function update(SubInbOrderProductRequest $request, $id) {
        $subinborderproduct=$this->subinborderproduct->update($id,$request->except(['id','item_description']));
        if($subinborderproduct){
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
        if($this->subinborderproduct->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
    public function getItemDescription(){
        $rows=$this->itemaccount
            ->join('itemcategories',function($join){
                $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->join('itemclasses',function($join){
                $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            /* ->leftJoin('yarncounts',function($join){
                $join->on('yarncounts.id','=','item_accounts.yarncount_id');
            })
            ->leftJoin('yarntypes',function($join){
                $join->on('yarntypes.id','=','item_accounts.yarntype_id');
            })
            ->leftJoin('compositions',function($join){
                $join->on('compositions.id','=','item_accounts.composition_id');
            })
            ->leftJoin('colors',function($join){
            $join->on('colors.id','=','item_accounts.color_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','item_accounts.size_id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','item_accounts.uom_id');
            }) */
            ->when(request('id'), function ($q) {
                return $q->where('item_accounts.id', '=', request('id', 0));
            })
            ->when(request('itemcategory_id'), function ($q) {
                return $q->where('item_accounts.itemcategory_id', '=', request('itemcategory_id', 0));
            })
            ->when(request('itemclass_id'), function ($q) {
                return $q->where('item_accounts.itemclass_id', '=', request('itemclass_id', 0));
            })
            ->orderBy('item_accounts.id','desc')
            ->get([
                'item_accounts.*',
                'itemcategories.name',
                'itemclasses.name as class_name'/* ,
                'yarncounts.count',
                'yarncounts.symbol',
                'yarntypes.name as yarn_type',
                'compositions.name as composition',
                'colors.name as color',
                'sizes.name as size',
                'uoms.code as uom' */
            ]);
      echo json_encode($rows);
    }

    public function getColor(Request $request) {
        return $this->subinborderproduct->where([['color', 'LIKE', '%'.$request->q.'%']])->orderBy('color', 'asc')->get(['color as name']);
    }

    public function getSize(Request $request) {
        return $this->subinborderproduct->where([['size', 'LIKE', '%'.$request->q.'%']])->orderBy('size', 'asc')->get(['size as name']);
    }

}