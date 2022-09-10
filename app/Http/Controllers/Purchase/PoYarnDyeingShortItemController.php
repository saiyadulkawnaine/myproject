<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingItemRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;


use App\Library\Template;
use App\Http\Requests\Purchase\PoYarnDyeingItemRequest;


class PoYarnDyeingShortItemController extends Controller
{
 private $poyarndyeing;
 private $poyarndyeingitem;
 private $buyer;
 private $invyarnitem;
 private $itemclass;
 private $itemcategory;
 private $salesorder;
 private $colorrange;


 public function __construct(
  PoYarnDyeingRepository $poyarndyeing,
  PoYarnDyeingItemRepository $poyarndyeingitem,
  StyleRepository $style,
  ItemAccountRepository $itemaccount,
  BuyerRepository $buyer,
  InvYarnItemRepository $invyarnitem,
  ItemclassRepository $itemclass,
  ItemcategoryRepository $itemcategory,
  SalesOrderRepository $salesorder,
  ColorrangeRepository $colorrange

 ) {
  $this->poyarndyeing = $poyarndyeing;
  $this->poyarndyeingitem = $poyarndyeingitem;
  $this->itemaccount = $itemaccount;
  $this->style = $style;
  $this->buyer = $buyer;
  $this->invyarnitem = $invyarnitem;
  $this->itemclass = $itemclass;
  $this->itemcategory = $itemcategory;
  $this->salesorder = $salesorder;
  $this->colorrange = $colorrange;
  $this->middleware('auth');

  // $this->middleware('permission:view.poyarndyeingshortitems',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.poyarndyeingshortitems', ['only' => ['store']]);
  // $this->middleware('permission:edit.poyarndyeingshortitems',   ['only' => ['update']]);
  // $this->middleware('permission:delete.poyarndyeingshortitems', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $yarnDescription = $this->invyarnitem
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'inv_yarn_items.item_account_id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_yarn_items.supplier_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'inv_yarn_items.color_id');
   })
   ->leftJoin('item_account_ratios', function ($join) {
    $join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
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
   ->leftJoin('compositions', function ($join) {
    $join->on('compositions.id', '=', 'item_account_ratios.composition_id');
   })
   ->leftJoin('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })
   ->where([['itemcategories.identity', '=', 1]])
   ->get([
    'inv_yarn_items.id as inv_yarn_item_id',
    'yarncounts.count',
    'yarncounts.symbol',
    'yarntypes.name as yarn_type',
    'itemclasses.name as itemclass_name',
    'compositions.name as composition_name',
    'item_account_ratios.ratio',
   ]);
  $itemaccountArr = array();
  $yarnCompositionArr = array();
  foreach ($yarnDescription as $row) {
   $itemaccountArr[$row->inv_yarn_item_id]['count'] = $row->count . "/" . $row->symbol;
   $itemaccountArr[$row->inv_yarn_item_id]['yarn_type'] = $row->yarn_type;
   $itemaccountArr[$row->inv_yarn_item_id]['itemclass_name'] = $row->itemclass_name;
   $yarnCompositionArr[$row->inv_yarn_item_id][] = $row->composition_name . " " . $row->ratio . "%";
  }
  $yarnDropdown = array();
  foreach ($itemaccountArr as $key => $value) {
   $yarnDropdown[$key] = $value['count'] . " " . implode(",", $yarnCompositionArr[$key]) . " " . $value['yarn_type'];
  }


  $rows = $this->poyarndyeing
   ->join('po_yarn_dyeing_items', function ($join) {
    $join->on('po_yarn_dyeings.id', '=', 'po_yarn_dyeing_items.po_yarn_dyeing_id');
   })

   ->leftJoin('inv_yarn_items', function ($join) {
    $join->on('inv_yarn_items.id', '=', 'po_yarn_dyeing_items.inv_yarn_item_id');
   })
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'inv_yarn_items.item_account_id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_yarn_items.supplier_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'inv_yarn_items.color_id');
   })

   ->where([['po_yarn_dyeing_items.po_yarn_dyeing_id', '=', request('po_yarn_dyeing_id', 0)]])
   ->orderBy('po_yarn_dyeing_items.id', 'desc')
   ->get([
    'po_yarn_dyeing_items.*',
    'suppliers.name as supplier_name',
    'inv_yarn_items.lot',
    'inv_yarn_items.brand',
    'colors.name as yarn_color_name',
   ])

   ->map(function ($rows) use ($yarnDropdown) {
    $rows['yarn_des'] = isset($yarnDropdown[$rows->inv_yarn_item_id]) ? $yarnDropdown[$rows->inv_yarn_item_id] : '';
    $rows->rate = 0;
    if ($rows->qty) {
     $rows->rate = number_format($rows->amount / $rows->qty, 4);
    }
    $rows->qty = number_format($rows->qty, 2);
    $rows->amount = number_format($rows->amount, 2);
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
  //
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(PoYarnDyeingItemRequest $request)
 {
  $poyarndyeing = $this->poyarndyeing->find($request->po_yarn_dyeing_id);
  if ($poyarndyeing->approved_at) {
   return response()->json(array('success' => false,  'message' => 'Yarn Dyeing Purchase Order is Approved, So Save Or Update not Possible'), 200);
  } else {
   $poyarndyeingitem = $this->poyarndyeingitem->create([
    'po_yarn_dyeing_id' => $request->po_yarn_dyeing_id,
    'inv_yarn_item_id' => $request->inv_yarn_item_id,
    'qty' => $request->qty,
    'amount' => $request->amount,
    'remarks' => $request->remarks
   ]);
   if ($poyarndyeingitem) {
    return response()->json(array('success' => true, 'id' =>  $poyarndyeingitem->id, 'message' => 'Save Successfully'), 200);
   }
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
  $yarnDescription = $this->invyarnitem
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'inv_yarn_items.item_account_id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_yarn_items.supplier_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'inv_yarn_items.color_id');
   })
   ->leftJoin('item_account_ratios', function ($join) {
    $join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
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
   ->leftJoin('compositions', function ($join) {
    $join->on('compositions.id', '=', 'item_account_ratios.composition_id');
   })
   ->leftJoin('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })
   ->where([['itemcategories.identity', '=', 1]])
   ->get([
    'inv_yarn_items.id as inv_yarn_item_id',
    'yarncounts.count',
    'yarncounts.symbol',
    'yarntypes.name as yarn_type',
    'itemclasses.name as itemclass_name',
    'compositions.name as composition_name',
    'item_account_ratios.ratio',
   ]);
  $itemaccountArr = array();
  $yarnCompositionArr = array();
  foreach ($yarnDescription as $row) {
   $itemaccountArr[$row->inv_yarn_item_id]['count'] = $row->count . "/" . $row->symbol;
   $itemaccountArr[$row->inv_yarn_item_id]['yarn_type'] = $row->yarn_type;
   $itemaccountArr[$row->inv_yarn_item_id]['itemclass_name'] = $row->itemclass_name;
   $yarnCompositionArr[$row->inv_yarn_item_id][] = $row->composition_name . " " . $row->ratio . "%";
  }
  $yarnDropdown = array();
  foreach ($itemaccountArr as $key => $value) {
   $yarnDropdown[$key] = $value['count'] . " " . implode(",", $yarnCompositionArr[$key]) . " " . $value['yarn_type'];
  }


  $poyarndyeingitem = $this->poyarndyeingitem
   ->leftJoin('po_yarn_dyeings', function ($join) {
    $join->on('po_yarn_dyeings.id', '=', 'po_yarn_dyeing_items.po_yarn_dyeing_id');
   })
   ->leftJoin('inv_yarn_items', function ($join) {
    $join->on('inv_yarn_items.id', '=', 'po_yarn_dyeing_items.inv_yarn_item_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'inv_yarn_items.color_id');
   })
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'inv_yarn_items.item_account_id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_yarn_items.supplier_id');
   })




   ->where([['po_yarn_dyeing_items.id', '=', $id]])
   ->get([
    'po_yarn_dyeing_items.*',
    'inv_yarn_items.lot',
    'inv_yarn_items.brand',
    'inv_yarn_items.supplier_id',
    'suppliers.name as supplier_name',
    'colors.name as yarn_color_name',
   ])
   ->map(function ($poyarndyeingitem) use ($yarnDropdown) {
    $poyarndyeingitem['yarn_des'] = isset($yarnDropdown[$poyarndyeingitem->inv_yarn_item_id]) ? $yarnDropdown[$poyarndyeingitem->inv_yarn_item_id] : '';
    return $poyarndyeingitem;
   })
   ->first();
  $row['fromData'] = $poyarndyeingitem;
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
 public function update(PoYarnDyeingItemRequest $request, $id)
 {
  $poyarndyeingitem = $this->poyarndyeingitem->update(
   $id,
   [
    //'po_yarn_dyeing_id'=>$request->po_yarn_dyeing_id,
    //'inv_yarn_item_id'=>$request->inv_yarn_item_id,
    //'qty'=>$request->qty,
    //'amount'=>$request->amount,
    'remarks' => $request->remarks
   ]
  );
  if ($poyarndyeingitem) {
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
  if ($this->poyarndyeingitem->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  } else {
   return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
  }
 }



 public function getRcvYarnItem()
 {
  $yarnDescription = $this->invyarnitem
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'inv_yarn_items.item_account_id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_yarn_items.supplier_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'inv_yarn_items.color_id');
   })
   ->leftJoin('item_account_ratios', function ($join) {
    $join->on('item_account_ratios.item_account_id', '=', 'item_accounts.id');
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
   ->leftJoin('compositions', function ($join) {
    $join->on('compositions.id', '=', 'item_account_ratios.composition_id');
   })
   ->leftJoin('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })
   ->where([['itemcategories.identity', '=', 1]])
   ->get([
    'inv_yarn_items.id',
    'yarncounts.count',
    'yarncounts.symbol',
    'yarntypes.name as yarn_type',
    'itemclasses.name as itemclass_name',
    'compositions.name as composition_name',
    'item_account_ratios.ratio',
   ]);
  $itemaccountArr = array();
  $yarnCompositionArr = array();
  foreach ($yarnDescription as $row) {
   $itemaccountArr[$row->id]['count'] = $row->count . "/" . $row->symbol;
   $itemaccountArr[$row->id]['yarn_type'] = $row->yarn_type;
   $itemaccountArr[$row->id]['itemclass_name'] = $row->itemclass_name;
   $yarnCompositionArr[$row->id][] = $row->composition_name . " " . $row->ratio . "%";
  }
  $yarnDropdown = array();
  foreach ($itemaccountArr as $key => $value) {
   $yarnDropdown[$key] = $value['count'] . " " . implode(",", $yarnCompositionArr[$key]) . " " . $value['yarn_type'];
  }

  $rows = $this->invyarnitem
   ->leftJoin('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'inv_yarn_items.item_account_id');
   })
   ->leftJoin('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'inv_yarn_items.supplier_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'inv_yarn_items.color_id');
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
   ->leftJoin('itemcategories', function ($join) {
    $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
   })
   ->where([['itemcategories.identity', '=', 1]])
   ->get([
    'inv_yarn_items.id',
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
   ->map(function ($rows) use ($yarnDropdown) {
    $rows->yarn_des = isset($yarnDropdown[$rows->id]) ? $yarnDropdown[$rows->id] : '';
    $rows->inv_yarn_item_id = $rows->id;
    return $rows;
   });
  echo json_encode($rows);
 }
}
