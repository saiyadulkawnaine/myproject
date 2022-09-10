<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Template;


use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingItemBomQtyRepository;
use App\Repositories\Contracts\Bom\BudgetYarnRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Http\Requests\Purchase\PoYarnDyeingItemBomQtyRequest;

class PoYarnDyeingShortItemBomQtyController extends Controller
{
 private $poyarndyeing;
 private $poyarndyeingitem;
 private $poyarndyeingitembomqty;
 private $budgetyarn;
 private $itemaccount;
 private $salesorder;
 private $colorrange;

 public function __construct(
  PoYarnDyeingRepository $poyarndyeing,
  PoYarnDyeingItemRepository $poyarndyeingitem,
  PoYarnDyeingItemBomQtyRepository $poyarndyeingitembomqty,
  BudgetYarnRepository $budgetyarn,
  ItemAccountRepository $itemaccount,
  SalesOrderRepository $salesorder,
  ColorrangeRepository $colorrange
 ) {
  $this->poyarndyeing       = $poyarndyeing;
  $this->poyarndyeingitem   = $poyarndyeingitem;
  $this->poyarndyeingitembomqty      = $poyarndyeingitembomqty;
  $this->budgetyarn      = $budgetyarn;
  $this->itemaccount     = $itemaccount;
  $this->salesorder     = $salesorder;
  $this->colorrange     = $colorrange;
  $this->middleware('auth');
  // $this->middleware('permission:view.poyarndyeingshortitembomqties',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.poyarndyeingshortitembomqties', ['only' => ['store']]);
  // $this->middleware('permission:edit.poyarndyeingshortitembomqties',   ['only' => ['update']]);
  // $this->middleware('permission:delete.poyarndyeingshortitembomqties', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {

  $salesorder = $this->poyarndyeingitem
   ->selectRaw('
            sales_orders.id as sales_order_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.produced_company_id,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            buyers.code as buyer_name,
            companies.id as company_id,
            companies.name as company_name,
            produced_company.name as produced_company_name,
            budget_yarn_dyeing_cons.id as budget_yarn_dyeing_con_id,
            budget_yarn_dyeing_cons.bom_qty,
            budget_yarn_dyeing_cons.rate as bom_rate,
            budget_yarn_dyeing_cons.amount as bom_amount,
            colors.name as yarn_color_name,
            gmt_colors.name as gmt_color_name,
            style_fabrication_stripes.measurment,
            style_fabrication_stripes.feeder,
            po_yarn_dyeing_item_bom_qties.id,
            po_yarn_dyeing_item_bom_qties.qty,
            po_yarn_dyeing_item_bom_qties.rate,
            po_yarn_dyeing_item_bom_qties.amount,
            po_yarn_dyeing_item_bom_qties.process_loss_per,
            po_yarn_dyeing_item_bom_qties.colorrange_id,
            po_yarn_dyeing_item_bom_qties.req_cone,
            po_yarn_dyeing_item_bom_qties.wgt_per_cone,
            po_yarn_dyeing_item_bom_qties.remarks
            
         ')
   ->join('po_yarn_dyeing_item_bom_qties', function ($join) {
    $join->on('po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id', '=', 'po_yarn_dyeing_items.id');
   })
   ->join('budget_yarn_dyeing_cons', function ($join) {
    $join->on('budget_yarn_dyeing_cons.id', '=', 'po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id');
   })

   ->join('sales_orders', function ($join) {
    $join->on('budget_yarn_dyeing_cons.sales_order_id', '=', 'sales_orders.id');
   })

   ->join('style_fabrication_stripes', function ($join) {
    $join->on('style_fabrication_stripes.id', '=', 'budget_yarn_dyeing_cons.style_fabrication_stripe_id');
   })
   ->join('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_fabrication_stripes.style_color_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'style_fabrication_stripes.color_id');
   })
   ->join('colors as gmt_colors', function ($join) {
    $join->on('gmt_colors.id', '=', 'style_colors.color_id');
   })


   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->leftJoin('companies as produced_company', function ($join) {
    $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->when(request('style_ref'), function ($q) {
    return $q->where('styles.style_ref', 'LIKE', "%" . request('style_ref', 0) . "%");
   })
   ->when(request('job_no'), function ($q) {
    return $q->where('jobs.job_no', 'LIKE', "%" . request('job_no', 0) . "%");
   })
   ->when(request('sale_order_no'), function ($q) {
    return $q->where('sales_orders.sale_order_no', 'LIKE', "%" . request('sale_order_no', 0) . "%");
   })
   ->where([['po_yarn_dyeing_items.id', '=', request('po_yarn_dyeing_item_id', 0)]])
   ->orderBy('budget_yarn_dyeing_cons.id')
   ->get()
   ->map(function ($salesorder) {
    $salesorder->ship_date = date('d-M-Y', strtotime($salesorder->ship_date));
    return $salesorder;
   });
  echo json_encode($salesorder);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');

  $salesorder = $this->salesorder
   ->selectRaw('
            sales_orders.id as sales_order_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.produced_company_id,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            buyers.code as buyer_name,
            companies.id as company_id,
            companies.name as company_name,
            produced_company.name as produced_company_name,
            budget_yarn_dyeing_cons.id as budget_yarn_dyeing_con_id,
            budget_yarn_dyeing_cons.bom_qty,
            budget_yarn_dyeing_cons.rate as bom_rate,
            budget_yarn_dyeing_cons.amount as bom_amount,
            colors.name as yarn_color_name,
            gmt_colors.name as gmt_color_name,
            style_fabrication_stripes.measurment,
            style_fabrication_stripes.feeder
            
         ')
   ->join('budget_yarn_dyeing_cons', function ($join) {
    $join->on('budget_yarn_dyeing_cons.sales_order_id', '=', 'sales_orders.id');
   })
   ->join('style_fabrication_stripes', function ($join) {
    $join->on('style_fabrication_stripes.id', '=', 'budget_yarn_dyeing_cons.style_fabrication_stripe_id');
   })
   ->join('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_fabrication_stripes.style_color_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'style_fabrication_stripes.color_id');
   })
   ->join('colors as gmt_colors', function ($join) {
    $join->on('gmt_colors.id', '=', 'style_colors.color_id');
   })


   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->leftJoin('companies as produced_company', function ($join) {
    $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->when(request('style_ref'), function ($q) {
    return $q->where('styles.style_ref', 'LIKE', "%" . request('style_ref', 0) . "%");
   })
   ->when(request('job_no'), function ($q) {
    return $q->where('jobs.job_no', 'LIKE', "%" . request('job_no', 0) . "%");
   })
   ->when(request('sale_order_no'), function ($q) {
    return $q->where('sales_orders.sale_order_no', 'LIKE', "%" . request('sale_order_no', 0) . "%");
   })
   ->whereIn('budget_yarn_dyeing_cons.id', explode(',', request('budget_yarn_dyeing_con_id', 0)))
   ->groupBy([
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'sales_orders.ship_date',
    'sales_orders.produced_company_id',
    'styles.style_ref',
    'styles.id',
    'jobs.job_no',
    'buyers.code',
    'companies.id',
    'companies.name',
    'produced_company.name',
    'budget_yarn_dyeing_cons.id',
    'budget_yarn_dyeing_cons.bom_qty',
    'budget_yarn_dyeing_cons.rate',
    'budget_yarn_dyeing_cons.amount',
    'colors.name',
    'gmt_colors.name',
    'style_fabrication_stripes.measurment',
    'style_fabrication_stripes.feeder'
   ])
   ->orderBy('budget_yarn_dyeing_cons.id')
   ->get()
   ->map(function ($salesorder) {
    $salesorder->ship_date = date('d-M-Y', strtotime($salesorder->ship_date));
    return $salesorder;
   });
  return Template::loadView('Purchase.PoYarnDyeingItemBomQty', ['salesorder' => $salesorder, 'colorrange' => $colorrange]);
  //echo json_encode($salesorder);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(PoYarnDyeingItemBomQtyRequest $request)
 {
  $poyarndyeing = $this->poyarndyeing->find($request->po_yarn_dyeing_id);
  if ($poyarndyeing->approved_at) {
   return response()->json(array('success' => false,  'message' => 'Yarn Dyeing Purchase Order is Approved, So Save Or Update not Possible'), 200);
  } else {
   $poYarnItemId = 0;
   foreach ($request->budget_yarn_dyeing_con_id as $index => $budget_yarn_dyeing_con_id) {
    if ($budget_yarn_dyeing_con_id && $request->qty[$index] > 0) {
     $poyarndyeingitembomqty = $this->poyarndyeingitembomqty->updateOrCreate(
      ['po_yarn_dyeing_item_id' => $request->po_yarn_dyeing_item_id, 'budget_yarn_dyeing_con_id' => $budget_yarn_dyeing_con_id],
      [
       'qty' => $request->qty[$index],
       'rate' => $request->rate[$index],
       'amount' => $request->amount[$index],
       'colorrange_id' => $request->colorrange_id[$index],
       'process_loss_per' => $request->process_loss_per[$index],
       'req_cone' => $request->req_cone[$index],
       'wgt_per_cone' => $request->wgt_per_cone[$index],
       'remarks' => $request->remarks[$index],
      ]
     );
    }
   }
   if ($poyarndyeingitembomqty) {
    return response()->json(array('success' => true, 'id' => $poyarndyeingitembomqty->id, 'po_yarn_item_id' => $request->po_yarn_dyeing_item_id,  'message' => 'Save Successfully'), 200);
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
 }

 /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function edit($id)
 {
  $salesorder = $this->poyarndyeingitembomqty
   ->selectRaw('
            sales_orders.id as sales_order_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.produced_company_id,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            buyers.code as buyer_name,
            companies.id as company_id,
            companies.name as company_name,
            produced_company.name as produced_company_name,
            budget_yarn_dyeing_cons.id as budget_yarn_dyeing_con_id,
            budget_yarn_dyeing_cons.bom_qty,
            budget_yarn_dyeing_cons.rate as bom_rate,
            budget_yarn_dyeing_cons.amount as bom_amount,
            colors.name as yarn_color_name,
            gmt_colors.name as gmt_color_name,
            style_fabrication_stripes.measurment,
            style_fabrication_stripes.feeder,
            po_yarn_dyeing_item_bom_qties.id,
            po_yarn_dyeing_item_bom_qties.qty,
            po_yarn_dyeing_item_bom_qties.rate,
            po_yarn_dyeing_item_bom_qties.amount,
            po_yarn_dyeing_item_bom_qties.process_loss_per,
            po_yarn_dyeing_item_bom_qties.colorrange_id,
            po_yarn_dyeing_item_bom_qties.req_cone,
            po_yarn_dyeing_item_bom_qties.wgt_per_cone,
            po_yarn_dyeing_item_bom_qties.remarks
            
         ')
   ->join('po_yarn_dyeing_items', function ($join) {
    $join->on('po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id', '=', 'po_yarn_dyeing_items.id');
   })
   ->join('budget_yarn_dyeing_cons', function ($join) {
    $join->on('budget_yarn_dyeing_cons.id', '=', 'po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id');
   })

   ->join('sales_orders', function ($join) {
    $join->on('budget_yarn_dyeing_cons.sales_order_id', '=', 'sales_orders.id');
   })

   ->join('style_fabrication_stripes', function ($join) {
    $join->on('style_fabrication_stripes.id', '=', 'budget_yarn_dyeing_cons.style_fabrication_stripe_id');
   })
   ->join('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_fabrication_stripes.style_color_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'style_fabrication_stripes.color_id');
   })
   ->join('colors as gmt_colors', function ($join) {
    $join->on('gmt_colors.id', '=', 'style_colors.color_id');
   })


   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->leftJoin('companies as produced_company', function ($join) {
    $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->when(request('style_ref'), function ($q) {
    return $q->where('styles.style_ref', 'LIKE', "%" . request('style_ref', 0) . "%");
   })
   ->when(request('job_no'), function ($q) {
    return $q->where('jobs.job_no', 'LIKE', "%" . request('job_no', 0) . "%");
   })
   ->when(request('sale_order_no'), function ($q) {
    return $q->where('sales_orders.sale_order_no', 'LIKE', "%" . request('sale_order_no', 0) . "%");
   })
   ->where([['po_yarn_dyeing_item_bom_qties.id', '=', $id]])
   ->orderBy('budget_yarn_dyeing_cons.id')
   ->get()
   ->map(function ($salesorder) {
    $salesorder->ship_date = date('d-M-Y', strtotime($salesorder->ship_date));
    return $salesorder;
   })->first();

  $row['fromData'] = $salesorder;
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
 public function update(PoYarnDyeingItemBomQtyRequest $request, $id)
 {
  $poyarndyeing = $this->poyarndyeing->find($request->po_yarn_dyeing_id);
  if ($poyarndyeing->approved_at) {
   return response()->json(array('success' => false,  'message' => 'Yarn Dyeing Purchase Order is Approved, So Save Or Update not Possible'), 200);
  }

  $is_issued = $this->poyarndyeingitembomqty
   ->join('inv_yarn_isu_items', function ($join) {
    $join->on('inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id', '=', 'po_yarn_dyeing_item_bom_qties.id');
   })
   ->where([['po_yarn_dyeing_item_bom_qties.id', '=', $id]])
   ->get()
   ->first();
  if ($is_issued) {
   return response()->json(array('success' => false, 'message' => 'Yarn Issue Found, So update not allowed'), 200);
  }
  $poyarndyeingitembomqty = $this->poyarndyeingitembomqty->update($id, [
   'qty' => $request->qty,
   'rate' => $request->rate,
   'amount' => $request->amount,
   'colorrange_id' => $request->colorrange_id,
   'process_loss_per' => $request->process_loss_per,
   'req_cone' => $request->req_cone,
   'wgt_per_cone' => $request->wgt_per_cone,
   'remarks' => $request->remarks,
  ]);
  if ($poyarndyeingitembomqty) {
   return response()->json(array('success' => true, 'id' => $id, 'po_yarn_item_id' => $request->po_yarn_item_id, 'message' => 'Update Successfully'), 200);
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
 }


 public function getYarnDyeSaleOrder()
 {
  $poyarndyeing = $this->poyarndyeing->find(request('po_yarn_dyeing_id', 0));
  $salesorder = $this->salesorder
   ->selectRaw('
            sales_orders.id as sales_order_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.produced_company_id,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            buyers.code as buyer_name,
            companies.id as company_id,
            companies.name as company_name,
            produced_company.name as produced_company_name,
            budget_yarn_dyeing_cons.id as budget_yarn_dyeing_con_id,
            budget_yarn_dyeing_cons.bom_qty,
            budget_yarn_dyeing_cons.rate,
            budget_yarn_dyeing_cons.amount,
            colors.name as yarn_color_name,
            gmt_colors.name as gmt_color_name,
            style_fabrication_stripes.measurment,
            style_fabrication_stripes.feeder
         ')
   ->join('budget_yarn_dyeing_cons', function ($join) {
    $join->on('budget_yarn_dyeing_cons.sales_order_id', '=', 'sales_orders.id');
   })
   ->join('budget_yarn_dyeings', function ($join) {
    $join->on('budget_yarn_dyeing_cons.budget_yarn_dyeing_id', '=', 'budget_yarn_dyeings.id');
   })
   //->join('budget_approvals',function($join){
   //   $join->on('budget_yarn_dyeings.budget_id','=','budget_approvals.budget_id');
   //})
   ->join('style_fabrication_stripes', function ($join) {
    $join->on('style_fabrication_stripes.id', '=', 'budget_yarn_dyeing_cons.style_fabrication_stripe_id');
   })
   ->join('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_fabrication_stripes.style_color_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'style_fabrication_stripes.color_id');
   })
   ->join('colors as gmt_colors', function ($join) {
    $join->on('gmt_colors.id', '=', 'style_colors.color_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->leftJoin('companies as produced_company', function ($join) {
    $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->when(request('style_ref'), function ($q) {
    return $q->where('styles.style_ref', 'LIKE', "%" . request('style_ref', 0) . "%");
   })
   ->when(request('job_no'), function ($q) {
    return $q->where('jobs.job_no', 'LIKE', "%" . request('job_no', 0) . "%");
   })
   ->when(request('sale_order_no'), function ($q) {
    return $q->where('sales_orders.sale_order_no', 'LIKE', "%" . request('sale_order_no', 0) . "%");
   })
   ->where([['jobs.company_id', '=', $poyarndyeing->company_id]])
   //->whereNotNull('budget_approvals.yarndye_final_approved_at')
   ->orderBy('budget_yarn_dyeing_cons.id')
   ->get()
   ->map(function ($salesorder) {
    $salesorder->ship_date = date('d-M-Y', strtotime($salesorder->ship_date));
    return $salesorder;
   });
  echo json_encode($salesorder);
 }
}
