<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderProductRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemStripeRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemQtyRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\PlKnitItemRequest;

class PlKnitItemController extends Controller
{

 private $plknititem;
 private $subinbmarketing;
 private $company;
 private $buyer;
 private $uom;
 private $itemaccount;
 private $autoyarn;
 private $soknit;
 private $gmtspart;
 private $assetquantitycost;
 private $plknititemstripe;
 private $plknititemqty;

 public function __construct(
  PlKnitItemRepository $plknititem,
  BuyerRepository $buyer,
  CompanyRepository $company,
  UomRepository $uom,
  SubInbMarketingRepository $subinbmarketing,
  ItemAccountRepository $itemaccount,
  ItemclassRepository $itemclass,
  ItemcategoryRepository $itemcategory,
  SubInbOrderProductRepository $subinborderproduct,
  AutoyarnRepository $autoyarn,
  SoKnitRepository $soknit,
  GmtspartRepository $gmtspart,
  AssetQuantityCostRepository $assetquantitycost,
  PlKnitItemStripeRepository $plknititemstripe,
  PlKnitItemQtyRepository $plknititemqty
 ) {
  $this->plknititem = $plknititem;
  $this->subinbmarketing = $subinbmarketing;
  $this->subinborderproduct = $subinborderproduct;
  $this->company = $company;
  $this->buyer = $buyer;
  $this->uom = $uom;
  $this->itemaccount = $itemaccount;
  $this->autoyarn = $autoyarn;
  $this->soknit = $soknit;
  $this->gmtspart = $gmtspart;
  $this->assetquantitycost = $assetquantitycost;
  $this->plknititemstripe = $plknititemstripe;
  $this->plknititemqty = $plknititemqty;
  /*  
        $this->middleware('auth');
        $this->middleware('permission:view.plknititems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.plknititems', ['only' => ['store']]);
        $this->middleware('permission:edit.plknititems',   ['only' => ['update']]);
        $this->middleware('permission:delete.plknititems', ['only' => ['destroy']]);

        */
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {

  $autoyarn = $this->autoyarn
   ->join('autoyarnratios', function ($join) {
    $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
   })
   ->join('constructions', function ($join) {
    $join->on('autoyarns.construction_id', '=', 'constructions.id');
   })
   ->join('compositions', function ($join) {
    $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
   })
   ->when(request('construction_name'), function ($q) {
    return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
   })
   ->when(request('composition_name'), function ($q) {
    return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
   })
   ->orderBy('autoyarns.id', 'desc')
   ->get([
    'autoyarns.*',
    'constructions.name',
    'compositions.name as composition_name',
    'autoyarnratios.ratio'
   ]);

  $fabricDescriptionArr = array();
  $fabricCompositionArr = array();
  foreach ($autoyarn as $row) {
   $fabricDescriptionArr[$row->id] = $row->name;
   $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }
  $desDropdown = array();
  foreach ($fabricDescriptionArr as $key => $val) {
   $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
  }

  $rows = $this->plknititem
   ->join('pl_knits', function ($join) {
    $join->on('pl_knits.id', '=', 'pl_knit_items.pl_knit_id');
   })
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'pl_knit_items.machine_id');
   })
   ->join('colorranges', function ($join) {
    $join->on('colorranges.id', '=', 'pl_knit_items.colorrange_id');
   })
   ->join('so_knit_refs', function ($join) {
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
   ->leftJoin('budget_fabric_prods', function ($join) {
    $join->on('budget_fabric_prods.id', '=', 'po_knit_service_items.budget_fabric_prod_id');
   })
   ->leftJoin('budget_fabrics', function ($join) {
    $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
   })
   ->leftJoin('style_fabrications', function ($join) {
    $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
   })
   ->leftJoin('so_knit_items', function ($join) {
    $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
   })
   ->leftJoin('colors as so_color', function ($join) {
    $join->on('so_color.id', '=', 'so_knit_items.fabric_color_id');
   })
   ->leftJoin('colors as po_color', function ($join) {
    $join->on('po_color.id', '=', 'po_knit_service_item_qties.fabric_color_id');
   })
   ->orderBy('pl_knit_items.id', 'desc')
   ->where([['pl_knits.id', '=', request('pl_knit_id', 0)]])
   ->get([
    'pl_knit_items.*',
    'colorranges.name as colorrange_id',
    'style_fabrications.autoyarn_id',
    'so_knit_items.autoyarn_id as c_autoyarn_id',
    'asset_quantity_costs.custom_no as machine_no',
    'so_color.name as c_fabric_color_name',
    'po_color.name as fabric_color_name',

   ])
   ->map(function ($rows) use ($desDropdown) {
    $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
    $rows->fabric_color = $rows->fabric_color_name ? $rows->fabric_color_name : $rows->c_fabric_color_name;

    return $rows;
   });
  echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create(Request $request)
 {
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(PlKnitItemRequest $request)
 {
  $plknititem = $this->plknititem->create($request->except(['id', 'fabrication', 'knitting_sales_order', 'machine_no']));
  // NEW CODE From 12/10/2020
  $filled = 0;
  $free = 0;
  $date_from = $request->pl_start_date;
  $qty = $request->qty;
  $no_of_days = ceil($request->qty / $request->capacity);
  for ($i = 0; $i < $no_of_days; $i++) {
   $MonthYear = date('M-y', strtotime($date_from));
   if ($qty >= $request->capacity) {
    $this->plknititemqty->create(['pl_knit_item_id' => $plknititem->id, 'pl_date' => $date_from, 'qty' => $request->capacity, 'filled' => 100, 'free' => 0]);
    $qty = $qty - $request->capacity;
   } else {
    $filled = ($qty / $request->capacity) * 100;
    $free = 100 - $filled;
    $this->plknititemqty->create(['pl_knit_item_id' => $plknititem->id, 'pl_date' => $date_from, 'qty' => $qty, 'filled' => $filled, 'free' => $free]);
   }

   $date_from = date('Y-m-d H:i:s', strtotime($date_from . ' +1 day'));
  }




  $stripes = $this->plknititem
   ->join('so_knit_refs', function ($join) {
    $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
   })
   ->join('so_knits', function ($join) {
    $join->on('so_knits.id', '=', 'so_knit_refs.so_knit_id');
   })
   ->join('so_knit_pos', function ($join) {
    $join->on('so_knit_pos.so_knit_id', '=', 'so_knits.id');
   })
   ->join('so_knit_po_items', function ($join) {
    $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
   })
   ->join('po_knit_service_item_qties', function ($join) {
    $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
   })
   ->join('po_knit_service_items', function ($join) {
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
   ->join('style_fabrication_stripes', function ($join) {
    $join->on('style_fabrication_stripes.style_fabrication_id', '=', 'style_fabrications.id');
   })
   ->join('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_fabrication_stripes.style_color_id');
   })
   ->orderBy('pl_knit_items.id', 'desc')
   ->where([['pl_knit_items.id', '=', $plknititem->id]])
   ->get([
    'style_fabrication_stripes.id as style_fabrication_stripe_id',
    'style_colors.color_id as gmt_color_id',
    'style_fabrication_stripes.color_id as stripe_color_id',
    'style_fabrication_stripes.measurment',
    'style_fabrication_stripes.feeder',
    'so_knit_pos.po_knit_service_id',
   ]);
  if ($stripes) {
   foreach ($stripes as $stripe) {
    $plknititemstripe = $this->plknititemstripe->create(['pl_knit_item_id' => $plknititem->id, 'style_fabrication_stripe_id' => $stripe->style_fabrication_stripe_id, 'gmt_color_id' => $stripe->gmt_color_id, 'stripe_color_id' => $stripe->stripe_color_id, 'no_of_feeder' => $stripe->feeder, 'measurment' => $stripe->measurment]);
   }
  }

  if ($plknititem) {
   return response()->json(array('success' => true, 'id' =>  $plknititem->id, 'message' => 'Save Successfully'), 200);
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
  $autoyarn = $this->autoyarn
   ->join('autoyarnratios', function ($join) {
    $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
   })
   ->join('constructions', function ($join) {
    $join->on('autoyarns.construction_id', '=', 'constructions.id');
   })
   ->join('compositions', function ($join) {
    $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
   })
   ->when(request('construction_name'), function ($q) {
    return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
   })
   ->when(request('composition_name'), function ($q) {
    return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
   })
   ->orderBy('autoyarns.id', 'desc')
   ->get([
    'autoyarns.*',
    'constructions.name',
    'compositions.name as composition_name',
    'autoyarnratios.ratio'
   ]);

  $fabricDescriptionArr = array();
  $fabricCompositionArr = array();
  foreach ($autoyarn as $row) {
   $fabricDescriptionArr[$row->id] = $row->name;
   $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }
  $desDropdown = array();
  foreach ($fabricDescriptionArr as $key => $val) {
   $desDropdown[$key] = $val . " " . implode(",", $fabricCompositionArr[$key]);
  }

  $plknititem = $this->plknititem
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'pl_knit_items.machine_id');
   })
   ->join('colorranges', function ($join) {
    $join->on('colorranges.id', '=', 'pl_knit_items.colorrange_id');
   })
   ->join('so_knit_refs', function ($join) {
    $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
   })
   ->join('so_knits', function ($join) {
    $join->on('so_knits.id', '=', 'so_knit_refs.so_knit_id');
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
   ->leftJoin('budget_fabric_prods', function ($join) {
    $join->on('budget_fabric_prods.id', '=', 'po_knit_service_items.budget_fabric_prod_id');
   })
   ->leftJoin('budget_fabrics', function ($join) {
    $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
   })
   ->leftJoin('style_fabrications', function ($join) {
    $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
   })
   ->leftJoin('so_knit_items', function ($join) {
    $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
   })
   ->leftJoin('colors as so_color', function ($join) {
    $join->on('so_color.id', '=', 'so_knit_items.fabric_color_id');
   })
   ->leftJoin('colors as po_color', function ($join) {
    $join->on('po_color.id', '=', 'po_knit_service_item_qties.fabric_color_id');
   })
   ->orderBy('pl_knit_items.id', 'desc')
   ->where([['pl_knit_items.id', '=', $id]])
   ->get([
    'pl_knit_items.*',
    'style_fabrications.autoyarn_id',
    'style_fabrications.fabric_shape_id',
    'so_knit_items.autoyarn_id as c_autoyarn_id',
    'so_knit_items.fabric_shape_id as c_fabric_shape_id',
    'so_knits.sales_order_no as knitting_sales_order',
    'asset_quantity_costs.custom_no as machine_no',
    'so_color.name as c_fabric_color_name',
    'po_color.name as fabric_color_name'
   ])
   ->map(function ($plknititem) use ($desDropdown) {
    $plknititem->fabrication = $plknititem->autoyarn_id ? $desDropdown[$plknititem->autoyarn_id] : $desDropdown[$plknititem->c_autoyarn_id];
    $plknititem->fabric_shape_id = $plknititem->fabric_shape_id ? $plknititem->fabric_shape_id : $plknititem->c_fabric_shape_id;
    $plknititem->autoyarn_id = $plknititem->autoyarn_id ? $plknititem->autoyarn_id : $plknititem->c_autoyarn_id;
    $plknititem->fabric_color = $plknititem->fabric_color_name ? $plknititem->fabric_color_name : $plknititem->c_fabric_color_name;


    return $plknititem;
   })->first();
  $row['fromData'] = $plknititem;
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
 public function update(PlKnitItemRequest $request, $id)
 {
  $plknititem = $this->plknititem->update($id, $request->except(['id', 'fabrication', 'knitting_sales_order', 'machine_no', 'pl_end_date'/*,'qty','capacity'*/]));


  // OLD CODE before 12/10/2020
  /*$earlier = new \DateTime($request->pl_start_date);
        $later = new \DateTime($request->pl_end_date);
        $diff = $later->diff($earlier)->format("%a")+1;
        $date_from=$request->pl_start_date;
        $point=$request->qty%$request->capacity;
        $round=$request->qty-$point;
        $perDayQty=0;
        if($diff)
        {
            if($point && ($diff-1))
            {
               $perDayQty=$round/($diff-1); 
            }
            else
            {
                $perDayQty=$round/($diff);
            }
            
        }
        else
        {
            $perDayQty=$round/1;
        }

        $this->plknititemqty->where([['pl_knit_item_id','=',$id]])->forceDelete();
         $filled=0;
         $free=0;
        for($i=0;$i<$diff;$i++)
        {
            
            $MonthYear=date('M-y',strtotime($date_from));
            if($i==($diff-1) && $point)
            {
                $filled=($point/$request->capacity)*100;
                $free=100-$filled;
                $this->plknititemqty->create(['pl_knit_item_id'=>$id,'pl_date'=>$date_from,'qty'=>$point,'filled'=>$filled,'free'=>$free]);
            }
            else
            {
                $filled=($perDayQty/$request->capacity)*100;
                $free=100-$filled;
                $this->plknititemqty->create(['pl_knit_item_id'=>$id,'pl_date'=>$date_from,'qty'=>$perDayQty,'filled'=>$filled,'free'=>$free]);

            }
            $date_from = date('Y-m-d H:i:s', strtotime($date_from . ' +1 day'));
        }*/

  // NEW CODE From 12/10/2020


  /*$this->plknititemqty->where([['pl_knit_item_id','=',$id]])->forceDelete();
        $filled=0;
        $free=0;
        $date_from=$request->pl_start_date;
        $qty=$request->qty;
        $no_of_days= ceil($request->qty/$request->capacity); 
        for($i=0;$i<$no_of_days;$i++)
        {
            $MonthYear=date('M-y',strtotime($date_from));
            if($qty>=$request->capacity){
                $this->plknititemqty->create(['pl_knit_item_id'=>$id,'pl_date'=>$date_from,'qty'=>$request->capacity,'filled'=>100,'free'=>0]);
                $qty=$qty-$request->capacity;
            }
            else{
                $filled=($qty/$request->capacity)*100;
                $free=100-$filled;
                $this->plknititemqty->create(['pl_knit_item_id'=>$id,'pl_date'=>$date_from,'qty'=>$qty,'filled'=>$filled,'free'=>$free]);
            }

            $date_from = date('Y-m-d H:i:s', strtotime($date_from . ' +1 day'));
        }*/
  if ($plknititem) {
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
  if ($this->plknititem->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getItem()
 {
  $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '-Select-', '');

  $autoyarn = $this->autoyarn->join('autoyarnratios', function ($join) {
   $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
  })
   ->join('constructions', function ($join) {
    $join->on('autoyarns.construction_id', '=', 'constructions.id');
   })
   ->join('compositions', function ($join) {
    $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
   })
   ->when(request('construction_name'), function ($q) {
    return $q->where('constructions.name', 'LIKE', "%" . request('construction_name', 0) . "%");
   })
   ->when(request('composition_name'), function ($q) {
    return $q->where('compositions.name', 'LIKE', "%" . request('composition_name', 0) . "%");
   })
   ->orderBy('autoyarns.id', 'desc')
   ->get([
    'autoyarns.*',
    'constructions.name',
    'compositions.name as composition_name',
    'autoyarnratios.ratio'
   ]);

  $fabricDescriptionArr = array();
  $fabricCompositionArr = array();
  foreach ($autoyarn as $row) {
   $fabricDescriptionArr[$row->id] = $row->name;
   $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }
  $desDropdown = array();
  foreach ($fabricDescriptionArr as $key => $val) {
   $desDropdown[$key] = implode(",", $fabricCompositionArr[$key]);
  }
  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
  $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');


  $rows = $this->soknit
   ->join('so_knit_refs', function ($join) {
    $join->on('so_knit_refs.so_knit_id', '=', 'so_knits.id');
   })
   ->leftJoin('so_knit_pos', function ($join) {
    $join->on('so_knit_pos.so_knit_id', '=', 'so_knits.id');
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
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'po_knit_service_item_qties.sales_order_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('budget_fabric_prods', function ($join) {
    $join->on('budget_fabric_prods.id', '=', 'po_knit_service_items.budget_fabric_prod_id');
   })
   ->leftJoin('budget_fabrics', function ($join) {
    $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
   })
   ->leftJoin('style_fabrications', function ($join) {
    $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
   })
   ->leftJoin('autoyarns', function ($join) {
    $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
   })
   ->leftJoin('constructions', function ($join) {
    $join->on('autoyarns.construction_id', '=', 'constructions.id');
   })
   ->leftJoin('so_knit_items', function ($join) {
    $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->leftJoin('buyers as gmt_buyer', function ($join) {
    $join->on('gmt_buyer.id', '=', 'so_knit_items.gmt_buyer');
   })
   ->leftJoin('buyers as customers', function ($join) {
    $join->on('customers.id', '=', 'so_knits.buyer_id');
   })
   ->leftJoin('colors as so_color', function ($join) {
    $join->on('so_color.id', '=', 'so_knit_items.fabric_color_id');
   })
   ->leftJoin('colors as po_color', function ($join) {
    $join->on('po_color.id', '=', 'po_knit_service_item_qties.fabric_color_id');
   })
   ->leftJoin(\DB::raw("(SELECT so_knit_refs.id as so_knit_ref_id,sum(pl_knit_items.qty) as cumulative_qty FROM pl_knit_items  join so_knit_refs on so_knit_refs.id = pl_knit_items.so_knit_ref_id   group by so_knit_refs.id) cumulatives"), "cumulatives.so_knit_ref_id", "=", "so_knit_refs.id")
   ->selectRaw(
    '
              so_knits.sales_order_no as knitting_sales_order,
              so_knit_refs.id,
              so_knit_refs.so_knit_id,
              style_fabrications.autoyarn_id,
              constructions.name as constructions_name,
              style_fabrications.fabric_look_id,
              style_fabrications.fabric_shape_id,
              style_fabrications.gmtspart_id,
              budget_fabrics.gsm_weight,
              po_knit_service_item_qties.dia,
              po_knit_service_item_qties.measurment,
              po_knit_service_item_qties.qty,
              po_knit_service_item_qties.pcs_qty,
              po_knit_service_item_qties.rate,
              po_knit_service_item_qties.amount,
              so_knit_items.autoyarn_id as c_autoyarn_id,
              so_knit_items.fabric_look_id as c_fabric_look_id,
              so_knit_items.fabric_shape_id as c_fabric_shape_id,
              so_knit_items.gmtspart_id as c_gmtspart_id,
              so_knit_items.gsm_weight as c_gsm_weight,
              so_knit_items.dia as c_dia,
              so_knit_items.measurment as c_measurment,
              so_knit_items.qty as c_qty,
              so_knit_items.rate as c_rate,
              so_knit_items.amount as c_amount,
              cumulatives.cumulative_qty,
              styles.style_ref,
              sales_orders.sale_order_no,
              so_knit_items.gmt_style_ref,
              so_knit_items.gmt_sale_order_no,
              buyers.code as buyer_name,
              gmt_buyer.code as gmt_buyer_name,
              customers.name as customer_name,
              so_color.name as c_fabric_color_name,
              po_color.name as fabric_color_name
              '
   )
   ->when(request('sale_oreder_no'), function ($q) {
    return $q->where('so_knits.sales_order_no', 'LIKE', '%' . request('sale_oreder_no', 0) . '%');
   })
   ->when(request('buyer_id'), function ($q) {
    return $q->where('so_knits.buyer_id', '=', request('buyer_id', 0));
   })
   ->orderBy('so_knit_refs.id', 'desc')

   ->get()
   ->map(function ($rows) use ($desDropdown, $gmtspart, $fabriclooks, $fabricshape, $uom) {
    $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->c_autoyarn_id];
    $rows->gmtspart = $rows->gmtspart_id ? $gmtspart[$rows->gmtspart_id] : $gmtspart[$rows->c_gmtspart_id];
    $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];
    $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
    $rows->fabric_shape_id = $rows->fabric_shape_id ? $rows->fabric_shape_id : $rows->c_fabric_shape_id;
    $rows->gsm_weight = $rows->gsm_weight ? $rows->gsm_weight : $rows->c_gsm_weight;
    $rows->dia = $rows->dia ? $rows->dia : $rows->c_dia;
    $rows->measurment = $rows->measurment ? $rows->measurment : $rows->c_measurment;
    $rows->qty = $rows->qty ? $rows->qty : $rows->c_qty;
    $rows->rate = $rows->rate ? $rows->rate : $rows->c_rate;
    $rows->amount = $rows->amount ? $rows->amount : $rows->c_amount;
    $rows->prev_pl_qty = $rows->cumulative_qty;
    $rows->balance = $rows->qty - $rows->prev_pl_qty;
    $rows->gmt_style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
    $rows->gmt_buyer = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
    $rows->gmt_sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
    $rows->fabric_color = $rows->fabric_color_name ? $rows->fabric_color_name : $rows->c_fabric_color_name;
    return $rows;
   });
  $data = $rows->filter(function ($rows) {
   if ($rows->balance > 0) {
    return $rows;
   }
  })->values();
  return response()->json($data, 200);
 }
 public function getMachine()
 {
  $machine = $this->assetquantitycost
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->leftJoin('asset_technical_features', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_technical_features.asset_acquisition_id');
   })
   ->when(request('dia_width'), function ($q) {
    return $q->where('asset_technical_features.dia_width', '=>', request('dia_width', 0));
   })
   ->when(request('no_of_feeder'), function ($q) {
    return $q->where('asset_technical_features.no_of_feeder', '<=', request('no_of_feeder', 0));
   })
   ->where([['asset_acquisitions.production_area_id', '=', 10]])
   ->orderBy('asset_acquisitions.id', 'asc')
   ->orderBy('asset_quantity_costs.id', 'asc')
   ->get([
    'asset_quantity_costs.*',
    'asset_acquisitions.prod_capacity',
    'asset_acquisitions.name as asset_name',
    'asset_acquisitions.origin',
    'asset_acquisitions.brand',
    'asset_technical_features.dia_width',
    'asset_technical_features.gauge',
    'asset_technical_features.extra_cylinder',
    'asset_technical_features.no_of_feeder'

   ]);
  echo json_encode($machine);
 }

 public function getPdf()
 {
  $id = request('id', 0);
  $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $autoyarn = $this->autoyarn->join('autoyarnratios', function ($join) {
   $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
  })
   ->join('constructions', function ($join) {
    $join->on('autoyarns.construction_id', '=', 'constructions.id');
   })
   ->join('compositions', function ($join) {
    $join->on('compositions.id', '=', 'autoyarnratios.composition_id');
   })
   ->orderBy('autoyarns.id', 'desc')
   ->get([
    'autoyarns.*',
    'constructions.name',
    'compositions.name as composition_name',
    'autoyarnratios.ratio'
   ]);

  $fabricDescriptionArr = array();
  $fabricCompositionArr = array();
  foreach ($autoyarn as $row) {
   $fabricDescriptionArr[$row->id] = $row->name;
   $fabricCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }
  $desDropdown = array();
  foreach ($fabricDescriptionArr as $key => $val) {
   $desDropdown[$key] = $fabricDescriptionArr[$key] . "," . implode(",", $fabricCompositionArr[$key]);
  }

  $yarnDescription = $this->itemaccount
   ->leftJoin('item_account_ratios', function ($join) {
    $join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
   })
   ->leftJoin('compositions', function ($join) {
    $join->on('compositions.id', '=', 'item_account_ratios.composition_id');
   })
   ->leftJoin('itemclasses', function ($join) {
    $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
   })
   ->leftJoin('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })

   ->where([['itemcategories.identity', '=', 1]])
   ->orderBy('item_account_ratios.ratio', 'desc')
   ->get([
    'item_accounts.id',
    'compositions.name as composition_name',
    'item_account_ratios.ratio',
   ]);

  $itemaccountArr = array();
  $yarnCompositionArr = array();
  foreach ($yarnDescription as $row) {
   $itemaccountArr[$row->id]['count'] = $row->count . "/" . $row->symbol;
   $yarnCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }

  $yarnDropdown = array();
  foreach ($itemaccountArr as $key => $value) {
   $yarnDropdown[$key] = implode(",", $yarnCompositionArr[$key]);
  }




  $rows = $this->plknititem
   ->join('pl_knits', function ($join) {
    $join->on('pl_knits.id', '=', 'pl_knit_items.pl_knit_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'pl_knits.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'pl_knits.supplier_id');
   })
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'pl_knit_items.machine_id');
   })
   ->leftJoin('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->leftJoin('asset_technical_features', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_technical_features.asset_acquisition_id');
   })
   ->leftJoin('so_knit_refs', function ($join) {
    $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
   })
   ->leftJoin('so_knit_po_items', function ($join) {
    $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
   })
   ->leftJoin('so_knit_items', function ($join) {
    $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
   })
   ->leftJoin('po_knit_service_item_qties', function ($join) {
    $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
   })
   ->leftJoin('po_knit_service_items', function ($join) {
    $join->on('po_knit_service_items.id', '=', 'po_knit_service_item_qties.po_knit_service_item_id')
     ->whereNull('po_knit_service_items.deleted_at');
   })
   ->leftJoin('budget_fabric_prods', function ($join) {
    $join->on('budget_fabric_prods.id', '=', 'po_knit_service_items.budget_fabric_prod_id');
   })
   ->leftJoin('budget_fabrics', function ($join) {
    $join->on('budget_fabrics.id', '=', 'budget_fabric_prods.budget_fabric_id');
   })
   ->leftJoin('style_fabrications', function ($join) {
    $join->on('style_fabrications.id', '=', 'budget_fabrics.style_fabrication_id');
   })
   ->leftJoin('autoyarns', function ($join) {
    $join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'po_knit_service_item_qties.sales_order_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->leftJoin('buyers as gmt_buyer', function ($join) {
    $join->on('gmt_buyer.id', '=', 'so_knit_items.gmt_buyer');
   })
   ->leftJoin('colorranges', function ($join) {
    $join->on('colorranges.id', '=', 'pl_knit_items.colorrange_id');
   })
   ->where([['pl_knit_items.id', '=', $id]])
   ->get([
    'pl_knits.*',
    'suppliers.name as supplier_name',
    'suppliers.address as supplier_address',
    'asset_quantity_costs.custom_no',
    'asset_technical_features.dia_width',
    'asset_acquisitions.brand',
    'pl_knits.pl_date',
    'styles.style_ref',
    'style_fabrications.fabric_shape_id',
    'style_fabrications.fabric_look_id',
    'sales_orders.sale_order_no',
    'so_knit_items.gmt_style_ref',
    'so_knit_items.gmt_sale_order_no',
    'so_knit_items.fabric_look_id as c_fabric_look_id',
    'so_knit_items.fabric_shape_id as c_fabric_shape_id',
    'buyers.name as buyer_name',
    'gmt_buyer.name as gmt_buyer_name',
    'pl_knit_items.pl_start_date',
    'pl_knit_items.pl_end_date',
    'style_fabrications.autoyarn_id',
    'so_knit_items.autoyarn_id as so_autoyarn_id',
    'pl_knit_items.capacity',
    'pl_knit_items.qty',
    'pl_knit_items.dia',
    'pl_knit_items.gsm_weight',
    'pl_knit_items.stitch_length',
    'pl_knit_items.spandex_stitch_length',
    'pl_knit_items.draft_ratio',
    'pl_knit_items.machine_gg',
    'pl_knit_items.no_of_feeder',
    'pl_knit_items.remarks as dtl_remarks',
    'colorranges.name as colorrange_name'
   ])
   ->first();
  $rows->pl_date = date('d-M-Y', strtotime($rows->pl_date));
  $rows->style_ref = $rows->style_ref ? $rows->style_ref : $rows->gmt_style_ref;
  $rows->buyer_name = $rows->buyer_name ? $rows->buyer_name : $rows->gmt_buyer_name;
  $rows->sale_order_no = $rows->sale_order_no ? $rows->sale_order_no : $rows->gmt_sale_order_no;
  $rows->pl_start_date = date('d-M-Y', strtotime($rows->pl_start_date));
  $rows->pl_end_date = date('d-M-Y', strtotime($rows->pl_end_date));
  $rows->fabrication = $rows->autoyarn_id ? $desDropdown[$rows->autoyarn_id] : $desDropdown[$rows->so_autoyarn_id];
  $rows->fabricshape = $rows->fabric_shape_id ? $fabricshape[$rows->fabric_shape_id] : $fabricshape[$rows->c_fabric_shape_id];
  $rows->fabriclooks = $rows->fabric_look_id ? $fabriclooks[$rows->fabric_look_id] : $fabriclooks[$rows->c_fabric_look_id];

  $company = $this->company
   ->where([['id', '=', $rows->company_id]])
   ->get()->first();

  $plknititemstripe = $this->plknititem
   ->leftJoin('pl_knit_item_stripes', function ($join) {
    $join->on('pl_knit_item_stripes.pl_knit_item_id', '=', 'pl_knit_items.id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'pl_knit_item_stripes.gmt_color_id');
   })
   ->leftJoin('colors as stripe_color', function ($join) {
    $join->on('stripe_color.id', '=', 'pl_knit_item_stripes.stripe_color_id');
   })
   ->where([['pl_knit_items.id', '=', $id]])
   ->get([
    'pl_knit_item_stripes.id',
    'pl_knit_item_stripes.pl_knit_item_id',
    'pl_knit_item_stripes.style_fabrication_stripe_id',
    'pl_knit_item_stripes.measurment',
    'pl_knit_item_stripes.no_of_feeder',
    'colors.name as gmt_color_id',
    'stripe_color.name as stripe_color_id'
   ]);

  $narrowfabric = $this->plknititem

   ->join('so_knit_refs', function ($join) {
    $join->on('so_knit_refs.id', '=', 'pl_knit_items.so_knit_ref_id');
   })
   ->join('so_knits', function ($join) {
    $join->on('so_knits.id', '=', 'so_knit_refs.so_knit_id');
   })
   ->leftJoin('so_knit_pos', function ($join) {
    $join->on('so_knit_pos.so_knit_id', '=', 'so_knits.id');
   })
   ->leftJoin('so_knit_po_items', function ($join) {
    $join->on('so_knit_po_items.so_knit_ref_id', '=', 'so_knit_refs.id');
   })
   ->leftJoin('so_knit_items', function ($join) {
    $join->on('so_knit_items.so_knit_ref_id', '=', 'so_knit_refs.id');
   })
   ->leftJoin('po_knit_service_item_qties', function ($join) {
    $join->on('po_knit_service_item_qties.id', '=', 'so_knit_po_items.po_knit_service_item_qty_id');
   })
   ->leftJoin('po_knit_service_items', function ($join) {
    $join->on('po_knit_service_items.id', '=', 'po_knit_service_item_qties.po_knit_service_item_id')
     ->whereNull('po_knit_service_items.deleted_at');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'po_knit_service_item_qties.sales_order_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('pl_knit_item_narrowfabrics', function ($join) {
    $join->on('pl_knit_item_narrowfabrics.pl_knit_item_id', '=', 'pl_knit_items.id');
   })
   ->leftJoin('sizes', function ($join) {
    $join->on('sizes.id', '=', 'pl_knit_item_narrowfabrics.size_id');
   })
   ->where([['pl_knit_items.id', '=', $id]])
   ->orderBy('pl_knit_item_narrowfabrics.id', 'desc')
   ->get([
    'styles.style_ref',
    'sales_orders.sale_order_no',
    'so_knit_items.gmt_style_ref',
    'so_knit_items.gmt_sale_order_no',
    'pl_knit_item_narrowfabrics.*',
    'sizes.name as size_id',
   ])
   ->map(function ($narrowfabric) {
    $narrowfabric->gmt_style_ref = $narrowfabric->style_ref ? $narrowfabric->style_ref : $narrowfabric->gmt_style_ref;
    $narrowfabric->gmt_sale_order_no = $narrowfabric->sale_order_no ? $narrowfabric->sale_order_no : $narrowfabric->gmt_sale_order_no;
    return $narrowfabric;
   });


  $yarns = $this->plknititem
   ->join('rq_yarn_fabrications', function ($join) {
    $join->on('rq_yarn_fabrications.pl_knit_item_id', '=', 'pl_knit_items.id');
   })
   ->join('rq_yarn_items', function ($join) {
    $join->on('rq_yarn_items.rq_yarn_fabrication_id', '=', 'rq_yarn_fabrications.id');
   })
   ->join('rq_yarns', function ($join) {
    $join->on('rq_yarns.id', '=', 'rq_yarn_fabrications.rq_yarn_id');
   })

   ->join('inv_yarn_items', function ($join) {
    $join->on('inv_yarn_items.id', '=', 'rq_yarn_items.inv_yarn_item_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('inv_yarn_items.item_account_id', '=', 'item_accounts.id');
   })
   ->leftJoin('yarncounts', function ($join) {
    $join->on('yarncounts.id', '=', 'item_accounts.yarncount_id');
   })
   ->leftJoin('yarntypes', function ($join) {
    $join->on('yarntypes.id', '=', 'item_accounts.yarntype_id');
   })
   ->leftJoin('itemclasses', function ($join) {
    $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
   })
   ->join('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })
   ->join('uoms', function ($join) {
    $join->on('uoms.id', '=', 'item_accounts.uom_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'inv_yarn_items.color_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_yarn_items.supplier_id');
   })
   ->where([['pl_knit_items.id', '=', $id]])
   ->get([
    'rq_yarns.rq_no',
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
    'suppliers.name as supplier_name',
   ])
   ->map(function ($yarns) use ($yarnDropdown) {
    $yarns->yarn_count = $yarns->count . "/" . $yarns->symbol;
    $yarns->yarn_type = $yarns->yarn_type;
    $yarns->composition = isset($yarnDropdown[$yarns->item_account_id]) ? $yarnDropdown[$yarns->item_account_id] : '';
    return $yarns;
   });



  $plKnit['master'] = $rows;
  $plKnit['plknititemstripe'] = $plknititemstripe;
  $plKnit['narrowfabric'] = $narrowfabric;
  $plKnit['yarns'] = $yarns;


  $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $header = ['logo' => $company->logo, 'address' => $company->address, 'title' => 'Knitting Card / Program'];
  $pdf->setCustomHeader($header);
  $pdf->SetPrintHeader(true);
  //$pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(true);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->AddPage();
  $barcodestyle = array(
   'position' => '',
   'align' => 'C',
   'stretch' => false,
   'fitwidth' => true,
   'cellfitalign' => '',
   'border' => false,
   'hpadding' => 'auto',
   'vpadding' => 'auto',
   'fgcolor' => array(0, 0, 0),
   'bgcolor' => false, //array(255,255,255),
   'text' => true,
   'font' => 'helvetica',
   'fontsize' => 8,
   'stretchtext' => 4
  );
  $pdf->SetY(0);
  $pdf->SetX(150);
  $challan = str_pad($id, 10, 0, STR_PAD_LEFT);
  $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
  $pdf->SetFont('helvetica', '', 8);
  $view = \View::make('Defult.Subcontract.Kniting.PlKnitPdf', ['plKnit' => $plKnit]);
  $html_content = $view->render();
  $pdf->SetY(42);
  $pdf->WriteHtml($html_content, true, false, true, false, '');
  $filename = storage_path() . '/PlKnitPdf.pdf';
  $pdf->output($filename);
  exit();
 }
}
