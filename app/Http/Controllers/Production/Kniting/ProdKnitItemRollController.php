<?php

namespace App\Http\Controllers\Production\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRollRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\GmtssampleRepository;
use App\Repositories\Contracts\Util\WeightMachineRepository;

use App\Library\Template;
use App\Http\Requests\Production\Kniting\ProdKnitItemRollRequest;
use GuzzleHttp\Client;

class ProdKnitItemRollController extends Controller
{


 private $prodknit;
 private $prodknititem;
 private $prodknititemroll;
 private $color;
 private $gmtssample;


 public function __construct(
  ProdKnitRepository $prodknit,
  ProdKnitItemRepository $prodknititem,
  ProdKnitItemRollRepository $prodknititemroll,
  ColorRepository $color,
  GmtssampleRepository $gmtssample,
  WeightMachineRepository $weightmachine
 ) {
  $this->prodknit = $prodknit;
  $this->prodknititem = $prodknititem;
  $this->prodknititemroll = $prodknititemroll;
  $this->color = $color;
  $this->gmtssample = $gmtssample;
  $this->weightmachine = $weightmachine;
  $this->middleware('auth');

  $this->middleware('permission:view.prodknititemrolls',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.prodknititemrolls', ['only' => ['store']]);
  $this->middleware('permission:edit.prodknititemrolls',   ['only' => ['update']]);
  $this->middleware('permission:delete.prodknititemrolls', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $rows = $this->prodknititemroll
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'prod_knit_item_rolls.fabric_color');
   })
   ->where([['prod_knit_item_rolls.prod_knit_item_id', '=', request('prod_knit_item_id', 0)]])
   ->orderBy('prod_knit_item_rolls.id', 'desc')
   ->get([
    'prod_knit_item_rolls.*',
    'colors.name as fabric_color'
   ])
   ->map(function ($rows) {
    $rows->roll_no = str_pad($rows->id, 10, 0, STR_PAD_LEFT);
    $rows->roll_weight = number_format($rows->roll_weight, 2, '.', ',');
    $rows->qty_pcs = number_format($rows->qty_pcs, 2, '.', ',');
    return $rows;
   });
  echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProdKnitItemRollRequest $request)
 {
  $prodknititem = $this->prodknititem->find($request->prod_knit_item_id);
  $prodknit = $this->prodknit->find($prodknititem->prod_knit_id);


  $max = $this->getCustomNo($request->prod_knit_item_id);
  $custom_no = $max + 1;
  $color = $this->getRollColor($request->prod_knit_item_id);

  $request->request->add(['custom_no' => $custom_no]);
  if ($color) {
   $request->request->add(['fabric_color' => $color->id]);
  }


  $tot_prod_item_qty = 0;
  $tot_prod_req_qty = 0;
  if ($prodknit->basis_id == 1) {

   $prodknititemQty = $this->prodknititem
    ->selectRaw('
        prod_knit_items.pl_knit_item_id,
        sum(prod_knit_item_rolls.roll_weight) as roll_weight
        ')
    ->leftJoin('prod_knit_item_rolls', function ($join) {
     $join->on('prod_knit_item_rolls.prod_knit_item_id', '=', 'prod_knit_items.id');
    })
    ->where([['prod_knit_items.pl_knit_item_id', '=', $prodknititem->pl_knit_item_id]])
    ->groupBy('prod_knit_items.pl_knit_item_id')
    ->get()
    ->first();
   $tot_prod_item_qty = $prodknititemQty->roll_weight;

   $pl_knit_item = $this->prodknititem
    ->leftJoin('pl_knit_items', function ($join) {
     $join->on('pl_knit_items.id', '=', 'prod_knit_items.pl_knit_item_id');
    })
    ->where([['pl_knit_items.id', '=', $prodknititem->pl_knit_item_id]])
    ->get()
    ->first();
   $tot_prod_req_qty = $pl_knit_item->qty;
  }
  if ($prodknit->basis_id == 5) {
   $prodknititemQty = $this->prodknititem
    ->selectRaw('
        prod_knit_items.po_knit_service_item_qty_id,
        sum(prod_knit_item_rolls.roll_weight) as roll_weight
        ')
    ->leftJoin('prod_knit_item_rolls', function ($join) {
     $join->on('prod_knit_item_rolls.prod_knit_item_id', '=', 'prod_knit_items.id');
    })
    ->where([['prod_knit_items.po_knit_service_item_qty_id', '=', $prodknititem->po_knit_service_item_qty_id]])
    ->groupBy('prod_knit_items.po_knit_service_item_qty_id')
    ->get()
    ->first();
   $tot_prod_item_qty = $prodknititemQty->roll_weight;

   $po_knit_service_item_qty = $this->prodknititem
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'prod_knit_items.po_knit_service_item_qty_id');
    })
    ->where([['po_knit_service_item_qties.id', '=', $prodknititem->po_knit_service_item_qty_id]])
    ->get()
    ->first();
   $tot_prod_req_qty = $po_knit_service_item_qty->qty;
  }

  $prod_qty = $tot_prod_item_qty + $request->roll_weight;
  if ($prod_qty > $tot_prod_req_qty) {
   return response()->json(array('success' => false, 'message' => 'Save Not Successfull, Production Qty is greater than Plan/Work Order Qty, Note: Plan/Work Order Qty: ' . $tot_prod_req_qty . ' Kg Production Qty: ' . $prod_qty . ' Kg'), 200);
  }



  $prodknititemroll = $this->prodknititemroll->create($request->except(['id', 'roll_no', 'fabric_color_id', 'gmt_sample_name']));
  if ($prodknititemroll) {
   return response()->json(array('success' => true, 'id' =>  $prodknititemroll->id, 'message' => 'Save Successfully'), 200);
  }
 }

 /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function show($id)
 {
  //
 }

 /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function edit($id)
 {
  $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '-Select-', 0);
  $gmtssample = array_prepend(array_pluck($this->gmtssample->get(), 'name', 'id'), '-Select-', 0);

  $prodknititemroll = $this->prodknititemroll->find($id);
  $prodknititemroll->roll_no = str_pad($prodknititemroll->id, 10, 0, STR_PAD_LEFT);
  $prodknititemroll->fabric_color = $prodknititemroll->fabric_color ? $color[$prodknititemroll->fabric_color] : null;
  $prodknititemroll->gmt_sample_name = $prodknititemroll->gmt_sample ? $gmtssample[$prodknititemroll->gmt_sample] : null;

  $row['fromData'] = $prodknititemroll;
  $dropdown['att'] = '';
  $row['dropDown'] = $dropdown;
  echo json_encode($row);
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function update(ProdKnitItemRollRequest $request, $id)
 {

  $prodknititem = $this->prodknititem->find($request->prod_knit_item_id);
  $prodknit = $this->prodknit->find($prodknititem->prod_knit_id);



  /*if($request->fabric_color && $request->fabric_color_id=='')
        {
            $color = $this->color->firstOrCreate(['name' => $request->fabric_color],['code' => $request->color_code]);
            $request->request->add(['fabric_color' => $color->id]);
        }
        else
        {
           $request->request->add(['fabric_color' => $request->fabric_color_id]); 
        }*/


  //$max=$this->getCustomNo($request->prod_knit_item_id);
  //$custom_no=$max+1;
  $color = $this->getRollColor($request->prod_knit_item_id);

  /*  if(!$color){
            return response()->json(array('success' => true,'message' => 'Update Not Successfull, Color not found'),200);
        }*/

  /* $roll_weight=0;
        if($request->roll_weight){
                $roll_weight=$request->roll_weight;
        }
        else{
            $roll_weight=$this->getWeight();
        }
        if(!$roll_weight){
            return response()->json(array('success' => true,'message' => 'Save Not Successfull, Roll Weight not found'),200);
        }*/

  //$request->request->add(['fabric_color' => $color->id]);
  if ($color) {
   $request->request->add(['fabric_color' => $color->id]);
  }
  // $request->request->add(['roll_weight' => $roll_weight]);

  $tot_prod_item_qty = 0;
  $tot_prod_req_qty = 0;

  if ($prodknit->basis_id == 1) {
   $prodknititemQty = $this->prodknititem
    ->selectRaw('
        prod_knit_items.pl_knit_item_id,
        sum(prod_knit_item_rolls.roll_weight) as roll_weight
        ')
    ->leftJoin('prod_knit_item_rolls', function ($join) {
     $join->on('prod_knit_item_rolls.prod_knit_item_id', '=', 'prod_knit_items.id');
    })
    ->where([['prod_knit_items.pl_knit_item_id', '=', $prodknititem->pl_knit_item_id]])
    ->where([['prod_knit_item_rolls.id', '!=', $id]])
    ->groupBy('prod_knit_items.pl_knit_item_id')
    ->get()
    ->first();
   $tot_prod_item_qty = $prodknititemQty->roll_weight;

   $pl_knit_item = $this->prodknititem
    ->leftJoin('pl_knit_items', function ($join) {
     $join->on('pl_knit_items.id', '=', 'prod_knit_items.pl_knit_item_id');
    })
    ->where([['pl_knit_items.id', '=', $prodknititem->pl_knit_item_id]])
    ->get()
    ->first();
   $tot_prod_req_qty = $pl_knit_item->qty;
  }

  if ($prodknit->basis_id == 5) {
   $prodknititemQty = $this->prodknititem
    ->selectRaw('
        prod_knit_items.po_knit_service_item_qty_id,
        sum(prod_knit_item_rolls.roll_weight) as roll_weight
        ')
    ->leftJoin('prod_knit_item_rolls', function ($join) {
     $join->on('prod_knit_item_rolls.prod_knit_item_id', '=', 'prod_knit_items.id');
    })
    ->where([['prod_knit_items.po_knit_service_item_qty_id', '=', $prodknititem->po_knit_service_item_qty_id]])
    ->where([['prod_knit_item_rolls.id', '!=', $id]])
    ->groupBy('prod_knit_items.po_knit_service_item_qty_id')
    ->get()
    ->first();

   $tot_prod_item_qty = $prodknititemQty->roll_weight;
   $po_knit_service_item_qty = $this->prodknititem
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'prod_knit_items.po_knit_service_item_qty_id');
    })
    ->where([['po_knit_service_item_qties.id', '=', $prodknititem->po_knit_service_item_qty_id]])
    ->get()
    ->first();
   $tot_prod_req_qty = $po_knit_service_item_qty->qty;
  }

  $prod_qty = $tot_prod_item_qty + $request->roll_weight;

  if ($prod_qty > $tot_prod_req_qty) {
   return response()->json(array('success' => false, 'message' => 'Save Not Successfull, Production Qty is greater than Plan/Work Order Qty, Note: Plan/Work Order Qty: ' . $tot_prod_req_qty . ' Kg Production Qty: ' . $prod_qty . ' Kg'), 200);
  }

  $roll_qc = $this->prodknititemroll
   ->join('prod_knit_qcs', function ($join) {
    $join->on('prod_knit_qcs.prod_knit_item_roll_id', '=', 'prod_knit_item_rolls.id');
   })
   ->where([['prod_knit_qcs.prod_knit_item_roll_id', '=', $id]])
   ->get()
   ->first();

  if ($roll_qc) {
   return response()->json(array('success' => false, 'message' => 'Update Not Possible, Roll is already QC Passed'), 200);
  }



  $prodknititemroll = $this->prodknititemroll->update($id, $request->except(['id', 'roll_no', 'custom_no', 'fabric_color_id', 'gmt_sample_name']));
  if ($prodknititemroll) {
   return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
  }
 }

 /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function destroy($id)
 {
  return response()->json(array('success' => false, 'message' => 'Delete Not Successfully'), 200);
  if ($this->prodknititemroll->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 private function getCustomNo($prod_knit_item_id)
 {

  $sales_orders = $this->getRollGmtOrder($prod_knit_item_id);

  $item = $this->prodknititem->find($prod_knit_item_id);
  $prod = $this->prodknit->find($item->prod_knit_id);
  $rows = null;
  if ($prod->basis_id == 1 && $sales_orders) {

   $rows = $this->prodknititemroll
    ->leftJoin('prod_knit_items', function ($join) {
     $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
    })
    ->leftJoin('pl_knit_items', function ($join) {
     $join->on('pl_knit_items.id', '=', 'prod_knit_items.pl_knit_item_id');
    })
    ->leftJoin('so_knit_refs', function ($join) {
     $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
    })
    ->leftJoin('so_knit_po_items', function ($join) {
     $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
    })
    ->join('colors', function ($join) {
     $join->on('colors.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->where([['po_knit_service_item_qties.sales_order_id', '=', $sales_orders->sales_order_id]])
    ->max('custom_no');
  } else if ($prod->basis_id == 5 && $sales_orders) {

   $rows = $this->prodknititemroll
    ->leftJoin('prod_knit_items', function ($join) {
     $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
    })
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'prod_knit_items.po_knit_service_item_qty_id');
    })
    ->join('colors', function ($join) {
     $join->on('colors.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->where([['po_knit_service_item_qties.sales_order_id', '=', $sales_orders->sales_order_id]])
    ->max('custom_no');
  }
  return $rows;
 }


 private function getRollGmtOrder($prod_knit_item_id)
 {

  $item = $this->prodknititem->find($prod_knit_item_id);
  $prod = $this->prodknit->find($item->prod_knit_id);
  $rows = null;
  if ($prod->basis_id == 1) {

   $rows = $this->prodknititem
    ->leftJoin('pl_knit_items', function ($join) {
     $join->on('pl_knit_items.id', '=', 'prod_knit_items.pl_knit_item_id');
    })
    ->leftJoin('so_knit_refs', function ($join) {
     $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
    })
    ->leftJoin('so_knit_po_items', function ($join) {
     $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
    })
    ->join('colors', function ($join) {
     $join->on('colors.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->where([['prod_knit_items.id', '=', $prod_knit_item_id]])
    ->get([
     'po_knit_service_item_qties.sales_order_id',
    ])
    ->first();
  } else if ($prod->basis_id == 5) {

   $rows = $this->prodknititem
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'prod_knit_items.po_knit_service_item_qty_id');
    })
    ->join('colors', function ($join) {
     $join->on('colors.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->where([['prod_knit_items.id', '=', $prod_knit_item_id]])
    ->get([
     'po_knit_service_item_qties.sales_order_id',
    ])
    ->first();
  }
  return $rows;
 }

 private function getRollColor($prod_knit_item_id)
 {

  $item = $this->prodknititem->find($prod_knit_item_id);
  $prod = $this->prodknit->find($item->prod_knit_id);
  $rows = null;
  if ($prod->basis_id == 1) {

   $rows = $this->prodknititem
    ->leftJoin('pl_knit_items', function ($join) {
     $join->on('pl_knit_items.id', '=', 'prod_knit_items.pl_knit_item_id');
    })
    ->leftJoin('so_knit_refs', function ($join) {
     $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
    })
    ->leftJoin('so_knit_po_items', function ($join) {
     $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
    })
    ->join('colors', function ($join) {
     $join->on('colors.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->where([['prod_knit_items.id', '=', $prod_knit_item_id]])
    ->get([
     'colors.id',
     'colors.name'
    ])
    ->first();
  } else if ($prod->basis_id == 5) {

   $rows = $this->prodknititem
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'prod_knit_items.po_knit_service_item_qty_id');
    })
    ->join('colors', function ($join) {
     $join->on('colors.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->where([['prod_knit_items.id', '=', $prod_knit_item_id]])
    ->get([
     'colors.id',
     'colors.name'
    ])
    ->first();
  }
  return $rows;
 }

 public function getFabricColor()
 {
  $prod_knit_item_id = request('prod_knit_item_id', 0);

  $item = $this->prodknititem->find($prod_knit_item_id);
  $prod = $this->prodknit->find($item->prod_knit_id);
  $rows = null;
  if ($prod->basis_id == 1) {
   /*$rows=$this->prodknititem
            ->leftJoin('pl_knit_items', function($join)  {
                $join->on('pl_knit_items.id', '=', 'prod_knit_items.pl_knit_item_id');
            })
            ->leftJoin('so_knit_refs', function($join)  {
                $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
            })
            ->leftJoin('so_knit_po_items', function($join)  {
                $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
            })
            ->leftJoin('po_knit_service_item_qties',function($join){
                $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
            ->whereNull('po_knit_service_items.deleted_at');
            })
            ->join('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
            })
            ->join('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->join('budget_fabric_cons',function($join){
                $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');
                $join->on('po_knit_service_item_qties.dia','=','budget_fabric_cons.dia');
                $join->on('po_knit_service_item_qties.measurment','=','budget_fabric_cons.measurment')
                ->whereNull('budget_fabric_cons.deleted_at');
            })
            ->join('sales_order_gmt_color_sizes',function($join){
                $join->on('sales_order_gmt_color_sizes.id','=','budget_fabric_cons.sales_order_gmt_color_size_id');
            })
            ->join('sales_order_countries',function($join){
                $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
                $join->on('po_knit_service_item_qties.sales_order_id','=','sales_orders.id');
            })
            ->join('colors',function($join){
                $join->on('colors.id','=','budget_fabric_cons.fabric_color');
            })
            ->where([['prod_knit_items.id','=',$prod_knit_item_id]])
            ->get([
            'colors.id',
            'colors.name'
            ]);*/

   //on 02-12-2020 I commented the previous query and add this


   $rows = $this->prodknititem
    ->leftJoin('pl_knit_items', function ($join) {
     $join->on('pl_knit_items.id', '=', 'prod_knit_items.pl_knit_item_id');
    })
    ->leftJoin('so_knit_refs', function ($join) {
     $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
    })
    ->leftJoin('so_knit_po_items', function ($join) {
     $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
    })
    ->join('colors', function ($join) {
     $join->on('colors.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->where([['prod_knit_items.id', '=', $prod_knit_item_id]])
    ->get([
     'colors.id',
     'colors.name'
    ]);
  } else if ($prod->basis_id == 5) {
   /*$rows=$this->prodknititem
           ->leftJoin('po_knit_service_item_qties',function($join){
              $join->on('po_knit_service_item_qties.id','=','prod_knit_items.po_knit_service_item_qty_id');
           })
           ->leftJoin('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
            ->whereNull('po_knit_service_items.deleted_at');
            })
            ->join('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
            })
            ->join('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->join('budget_fabric_cons',function($join){
                $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');
                $join->on('po_knit_service_item_qties.dia','=','budget_fabric_cons.dia');
                $join->on('po_knit_service_item_qties.measurment','=','budget_fabric_cons.measurment')
                ->whereNull('budget_fabric_cons.deleted_at');
            })
            ->join('sales_order_gmt_color_sizes',function($join){
                $join->on('sales_order_gmt_color_sizes.id','=','budget_fabric_cons.sales_order_gmt_color_size_id');
            })
            ->join('sales_order_countries',function($join){
                $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
                $join->on('po_knit_service_item_qties.sales_order_id','=','sales_orders.id');
            })
            ->join('colors',function($join){
                $join->on('colors.id','=','budget_fabric_cons.fabric_color');
            })
            ->where([['prod_knit_items.id','=',$prod_knit_item_id]])
            ->get([
            'colors.id',
            'colors.name'
            ]);*/

   //on 02-12-2020 I commented the previous query and add this
   $rows = $this->prodknititem
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'prod_knit_items.po_knit_service_item_qty_id');
    })
    ->join('colors', function ($join) {
     $join->on('colors.id', '=', 'po_knit_service_item_qties.fabric_color_id');
    })
    ->where([['prod_knit_items.id', '=', $prod_knit_item_id]])
    ->get([
     'colors.id',
     'colors.name'
    ]);
  }
  echo json_encode($rows);
 }

 public function getSample()
 {
  $prod_knit_item_id = request('prod_knit_item_id', 0);

  $item = $this->prodknititem->find($prod_knit_item_id);
  $prod = $this->prodknit->find($item->prod_knit_id);
  $rows = null;
  if ($prod->basis_id == 1) {
   $rows = $this->prodknititem
    ->leftJoin('pl_knit_items', function ($join) {
     $join->on('pl_knit_items.id', '=', 'prod_knit_items.pl_knit_item_id');
    })
    ->leftJoin('so_knit_refs', function ($join) {
     $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
    })
    ->leftJoin('so_knit_po_items', function ($join) {
     $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
    })
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
    })
    ->leftJoin('po_knit_service_items', function ($join) {
     $join->on('po_knit_service_items.id', '=', 'po_knit_service_item_qties.po_knit_service_item_id')
      ->whereNull('po_knit_service_items.deleted_at');
    })
    ->join('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_knit_service_items.budget_fabric_prod_id');
    })
    ->join('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->join('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->join('style_samples', function ($join) {
     $join->on('style_samples.style_id', '=', 'style_fabrications.style_id');
     $join->on('style_samples.style_gmt_id', '=', 'style_fabrications.style_gmt_id');
    })
    ->join('gmtssamples', function ($join) {
     $join->on('gmtssamples.id', '=', 'style_samples.gmtssample_id');
    })


    ->where([['prod_knit_items.id', '=', $prod_knit_item_id]])
    ->get([
     'gmtssamples.id',
     'gmtssamples.name'
    ]);
  } else if ($prod->basis_id == 5) {
   $rows = $this->prodknititem
    ->leftJoin('po_knit_service_item_qties', function ($join) {
     $join->on('po_knit_service_item_qties.id', '=', 'prod_knit_items.po_knit_service_item_qty_id');
    })
    ->leftJoin('po_knit_service_items', function ($join) {
     $join->on('po_knit_service_items.id', '=', 'po_knit_service_item_qties.po_knit_service_item_id')
      ->whereNull('po_knit_service_items.deleted_at');
    })
    ->join('budget_fabric_prods', function ($join) {
     $join->on('budget_fabric_prods.id', '=', 'po_knit_service_items.budget_fabric_prod_id');
    })
    ->join('budget_fabrics', function ($join) {
     $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
    })
    ->join('style_fabrications', function ($join) {
     $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
    })
    ->join('style_samples', function ($join) {
     $join->on('style_samples.style_id', '=', 'style_fabrications.style_id');
     $join->on('style_samples.style_gmt_id', '=', 'style_fabrications.style_gmt_id');
    })
    ->join('gmtssamples', function ($join) {
     $join->on('gmtssamples.id', '=', 'style_samples.gmtssample_id');
    })


    ->where([['prod_knit_items.id', '=', $prod_knit_item_id]])
    ->get([
     'gmtssamples.id',
     'gmtssamples.name'
    ]);
  }
  echo json_encode($rows);
 }


 private function getWeight()
 {
  $user = \Auth::user();
  $weightmachine = $this->weightmachine
   ->join('weight_machine_users', function ($join) {
    $join->on('weight_machine_users.weight_machine_id', '=', 'weight_machines.id');
   })
   ->where([['weight_machine_users.user_id', '=', $user->id]])
   ->get()
   ->first();
  $client = new Client();
  $headers = [
   'Accept'        => 'application/json',
   "Content-Type"  => "application/json"
  ];
  $res = $client->get('http://' . $weightmachine->machine_ip);
  $ApiStatus = json_decode($res->getBody());
  return $ApiStatus->weight;
 }

 public function getWgt()
 {
  $user = \Auth::user();
  $weightmachine = $this->weightmachine
   ->join('weight_machine_users', function ($join) {
    $join->on('weight_machine_users.weight_machine_id', '=', 'weight_machines.id');
   })
   ->where([['weight_machine_users.user_id', '=', $user->id]])
   ->get()
   ->first();
  $client = new Client();
  $headers = [
   'Accept'        => 'application/json',
   "Content-Type"  => "application/json"
  ];
  $res = $client->get('http://' . $weightmachine->machine_ip);
  $ApiStatus = json_decode($res->getBody());
  echo $ApiStatus->weight;
 }
}
