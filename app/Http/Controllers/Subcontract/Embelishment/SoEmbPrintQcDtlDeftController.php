<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcDtlDeftRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcDtlRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\ProductDefectRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintQcDtlDeftRequest;

class SoEmbPrintQcDtlDeftController extends Controller
{

 private $soembprintqcdtl;
 private $soembprintqcdtldeft;
 private $location;
 private $productdefect;

 public function __construct(SoEmbPrintQcDtlDeftRepository $soembprintqcdtldeft, SoEmbPrintQcDtlRepository $soembprintqcdtl, LocationRepository $location, ProductDefectRepository $productdefect)
 {

  $this->soembprintqcdtl = $soembprintqcdtl;
  $this->soembprintqcdtldeft = $soembprintqcdtldeft;
  $this->location = $location;
  $this->productdefect = $productdefect;
  $this->middleware('auth');
  // $this->middleware('permission:view.soembprintqcdtldefts',   ['only' => ['create', 'index', 'show']]);
  // $this->middleware('permission:create.soembprintqcdtldefts', ['only' => ['store']]);
  // $this->middleware('permission:edit.soembprintqcdtldefts',   ['only' => ['update']]);
  // $this->middleware('permission:delete.soembprintqcdtldefts', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $soembprintqcdtldefts = array();
  $rows = $this->soembprintqcdtldeft
   ->where([['so_emb_print_qc_dtl_defts.so_emb_print_qc_dtl_id', '=', request('so_emb_print_qc_dtl_id', 0)]])
   ->get();
  foreach ($rows as $row) {
   $soembprintqcdtldeft['id'] = $row->id;
   $soembprintqcdtldeft['no_of_defect'] = $row->no_of_defect;
   $soembprintqcdtldeft['so_emb_print_qc_dtl_id'] = $row->so_emb_print_qc_dtl_id;
   array_push($soembprintqcdtldefts, $soembprintqcdtldeft);
  }
  echo json_encode($soembprintqcdtldefts);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $soembprintqcdtl = $this->productdefect
   ->leftJoin('so_emb_print_qc_dtl_defts', function ($join) {
    $join->on('product_defects.id', '=', 'so_emb_print_qc_dtl_defts.product_defect_id');
   })
   ->get([
    'so_emb_print_qc_dtl_defts.id as so_emb_print_qc_dtl_deft_id',
    'product_defects.id as product_defect_id',
    'product_defects.defect_name',
    'product_defects.defect_code'
   ]);

  $soembprintqcdtlsave = $this->productdefect
   ->leftJoin('so_emb_print_qc_dtl_defts', function ($join) {
    $join->on('product_defects.id', '=', 'so_emb_print_qc_dtl_defts.product_defect_id');
   })
   ->get([
    'so_emb_print_qc_dtl_defts.id as so_emb_print_qc_dtl_deft_id',
    'so_emb_print_qc_dtl_defts.no_of_defect',
    'product_defects.id as product_defect_id',
    'product_defects.defect_name',
    'product_defects.defect_code'
   ]);

  $new = $soembprintqcdtl->filter(function ($value) {
   if (!$value->so_emb_print_qc_dtl_deft_id) {
    return $value;
   }
  });
  // dd($new);
  // exit();

  $saved = $soembprintqcdtlsave->filter(function ($value) {
   if ($value->so_emb_print_qc_dtl_deft_id) {
    return $value;
   }
  });

  return Template::loadView('Subcontract.Embelishment.SoEmbPrintQcDtlDeft', ['soembprintqcdtls' => $new, 'saved' => $saved]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SoEmbPrintQcDtlDeftRequest $request)
 {
  foreach ($request->product_defect_id as $index => $product_defect_id) {

   if ($product_defect_id && $request->no_of_defect[$index]) {
    $soembprintqcdtldeft = $this->soembprintqcdtldeft->updateOrCreate(
     [
      'product_defect_id' => $product_defect_id,
      'so_emb_print_qc_dtl_id' => $request->so_emb_print_qc_dtl_id
     ],
     [
      'no_of_defect' => $request->no_of_defect[$index]
     ]
    );
   }
  }
  if ($soembprintqcdtldeft) {
   return response()->json(array('success' => true, 'id' =>  $soembprintqcdtldeft->id, 'so_emb_print_qc_dtl_id' =>  $request->so_emb_print_qc_dtl_id, 'message' => 'Save Successfully'), 200);
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
 public function update(SoEmbPrintQcDtlDeftRequest $request, $id)
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
  $soembprintqcdtldeft = $this->soembprintqcdtldeft->find($id);
  $expdoc = $this->soembprintqcdtldeft
   ->join('exp_invoices', function ($join) {
    $join->on('exp_invoices.id', '=', 'exp_invoice_orders.so_emb_print_qc_dtl_id');
   })
   ->leftJoin('exp_doc_sub_invoices', function ($join) {
    $join->on('exp_doc_sub_invoices.so_emb_print_qc_dtl_id', '=', 'exp_invoices.id');
    $join->whereNull('exp_doc_sub_invoices.deleted_at');
   })
   ->where([['exp_invoice_orders.id', '=', $id]])
   ->first();

  if ($expdoc->so_emb_print_qc_dtl_id) {
   return response()->json(array('success' => false, 'so_emb_print_qc_dtl_id' =>  $expdoc->so_emb_print_qc_dtl_id, 'message' => 'Delete Not Successful.Invoice found in Document Submission to Bank'),  200);
  } else if ($this->soembprintqcdtldeft->delete($id)) {
   return response()->json(array('success' => true, 'so_emb_print_qc_dtl_id' =>  $soembprintqcdtldeft->so_emb_print_qc_dtl_id, 'message' => 'Delete Successfully'), 200);
  } else {
   return response()->json(array('success' => false, 'so_emb_print_qc_dtl_id' =>   $soembprintqcdtldeft->so_emb_print_qc_dtl_id,  'message' => 'Delete Not Successfull Because Subsequent Entry Found'),  200);
  }
 }
}
