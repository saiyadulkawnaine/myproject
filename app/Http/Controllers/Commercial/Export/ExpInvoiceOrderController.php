<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceOrderRepository;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpInvoiceOrderRequest;

class ExpInvoiceOrderController extends Controller
{

 private $expinvoice;
 private $expinvoiceorder;
 private $location;

 public function __construct(ExpInvoiceOrderRepository $expinvoiceorder, ExpInvoiceRepository $expinvoice, LocationRepository $location)
 {

  $this->expinvoice = $expinvoice;
  $this->expinvoiceorder = $expinvoiceorder;
  $this->location = $location;
  $this->middleware('auth');
  $this->middleware('permission:view.expinvoiceorders',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.expinvoiceorders', ['only' => ['store']]);
  $this->middleware('permission:edit.expinvoiceorders',   ['only' => ['update']]);
  $this->middleware('permission:delete.expinvoiceorders', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $expinvoiceorders = array();
  $rows = $this->expinvoiceorder
   ->where([['exp_invoice_id', '=', request('exp_invoice_id', 0)]])
   ->get();
  foreach ($rows as $row) {
   $expinvoiceorder['id'] = $row->id;
   $expinvoiceorder['acceptance_value'] = $row->acceptance_value;
   $expinvoiceorder['exp_invoice_id'] = $row->exp_invoice_id;
   array_push($expinvoiceorders, $expinvoiceorder);
  }
  echo json_encode($expinvoiceorders);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');

  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');

  $impdocaccept = $this->expinvoice
   ->selectRaw('sales_orders.id,
        sales_orders.sale_order_no,
        sales_orders.ship_date,
        exp_pi_orders.sales_order_id,
        exp_invoices.id as exp_invoice_id,
        exp_pi_orders.id as exp_pi_order_id,
        exp_pi_orders.qty,
        exp_pi_orders.rate,
        exp_invoice_orders.id as exp_invoice_order_id,
        exp_invoice_orders.qty as invoice_qty,
        exp_invoice_orders.rate as invoice_rate,
        exp_invoice_orders.amount as invoice_amount,
        exp_invoice_orders.production_source_id,
        exp_invoice_orders.location_id,
        exp_invoice_orders.commodity,
        cumulatives.cumulative_amount,
        cumulatives.cumulative_qty,
        users.name as marchent
        ')
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
   })
   ->leftJoin('exp_rep_lc_scs', function ($join) {
    $join->on('exp_rep_lc_scs.exp_lc_sc_id', '=', 'exp_lc_scs.id');
   })
   ->join('exp_lc_sc_pis', function ($join) {
    $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
    $join->orOn('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
   })
   ->join('exp_pis', function ($join) {
    $join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
   })
   ->join('exp_pi_orders', function ($join) {
    $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
    $join->whereNull('exp_pi_orders.deleted_at');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('teammembers', function ($join) {
    $join->on('teammembers.id', '=', 'styles.teammember_id');
   })
   ->leftJoin('users', function ($join) {
    $join->on('users.id', '=', 'teammembers.user_id');
   })
   ->leftJoin(\DB::raw("(SELECT exp_pi_orders.id as exp_pi_order_id,sum(exp_invoice_orders.qty) as cumulative_qty,sum(exp_invoice_orders.amount) as cumulative_amount FROM exp_invoice_orders join exp_pi_orders on exp_pi_orders.id =exp_invoice_orders.exp_pi_order_id join exp_invoices on  exp_invoices.id=exp_invoice_orders.exp_invoice_id where exp_invoice_orders.deleted_at is null  group by exp_pi_orders.id) cumulatives"), "cumulatives.exp_pi_order_id", "=", "exp_pi_orders.id")
   ->leftJoin('exp_invoice_orders', function ($join) {
    $join->on('exp_invoice_orders.exp_pi_order_id', '=', 'exp_pi_orders.id');
    $join->on('exp_invoice_orders.exp_invoice_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_invoice_orders.deleted_at');
   })
   ->where([['exp_invoices.id', '=', request('exp_invoice_id', 0)]])
   ->groupBy([
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'sales_orders.ship_date',
    'exp_pi_orders.sales_order_id',
    'exp_invoices.id',
    'exp_pi_orders.id',
    'exp_pi_orders.qty',
    'exp_pi_orders.rate',
    'exp_invoice_orders.id',
    'exp_invoice_orders.qty',
    'exp_invoice_orders.rate',
    'exp_invoice_orders.amount',
    'exp_invoice_orders.production_source_id',
    'exp_invoice_orders.location_id',
    'exp_invoice_orders.commodity',
    'cumulatives.cumulative_amount',
    'cumulatives.cumulative_qty',
    'users.name'
   ])
   ->get()
   ->map(function ($impdocaccept) {
    $impdocaccept->ship_date = date('d-M-y', strtotime($impdocaccept->ship_date));
    $impdocaccept->order_value = $impdocaccept->invoice_qty * $impdocaccept->rate;
    return $impdocaccept;
   });

  $saved = $impdocaccept->filter(function ($value) {
   if ($value->exp_invoice_order_id) {
    return $value;
   }
  });
  $new = $impdocaccept->filter(function ($value) {
   if (!$value->exp_invoice_order_id) {
    return $value;
   }
  });
  return Template::LoadView('Commercial.Export.ExpInvoiceOrder', ['impdocaccepts' => $new, 'saved' => $saved, 'productionsource' => $productionsource, 'location' => $location]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ExpInvoiceOrderRequest $request)
 {

  $impDocAcceptId = 0;
  foreach ($request->exp_pi_order_id as $index => $exp_pi_order_id) {
   $expInvoiceId = $request->exp_invoice_id[$index];
   if ($exp_pi_order_id && $request->qty[$index]) {
    $expinvoiceorder = $this->expinvoiceorder->updateOrCreate(
     [
      'exp_pi_order_id' => $exp_pi_order_id,
      'exp_invoice_id' => $request->exp_invoice_id[$index]
     ],
     [
      'qty' => $request->qty[$index],
      'rate' => $request->rate[$index],
      'amount' => $request->amount[$index],
      'production_source_id' => $request->production_source_id[$index],
      'location_id' => $request->location_id[$index],
      'commodity' => $request->commodity[$index],
     ]
    );
   }
  }
  if ($expinvoiceorder) {
   return response()->json(array('success' => true, 'id' =>  $expinvoiceorder->id, 'exp_invoice_id' =>  $expInvoiceId, 'message' => 'Save Successfully'), 200);
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
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function update(ExpInvoiceOrderRequest $request, $id)
 {
 }

 /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function destroy($id)
 {
  $expinvoiceorder = $this->expinvoiceorder->find($id);
  $expdoc = $this->expinvoiceorder
   ->join('exp_invoices', function ($join) {
    $join->on('exp_invoices.id', '=', 'exp_invoice_orders.exp_invoice_id');
   })
   ->leftJoin('exp_doc_sub_invoices', function ($join) {
    $join->on('exp_doc_sub_invoices.exp_invoice_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_doc_sub_invoices.deleted_at');
   })
   ->where([['exp_invoice_orders.id', '=', $id]])
   ->first();

  if ($expdoc->exp_invoice_id) {
   return response()->json(array('success' => false, 'exp_invoice_id' =>  $expdoc->exp_invoice_id, 'message' => 'Delete Not Successful.Invoice found in Document Submission to Bank'),  200);
  } else if ($this->expinvoiceorder->delete($id)) {
   return response()->json(array('success' => true, 'exp_invoice_id' =>  $expinvoiceorder->exp_invoice_id, 'message' => 'Delete Successfully'), 200);
  } else {
   return response()->json(array('success' => false, 'exp_invoice_id' =>   $expinvoiceorder->exp_invoice_id,  'message' => 'Delete Not Successfull Because Subsequent Entry Found'),  200);
  }
 }
}
