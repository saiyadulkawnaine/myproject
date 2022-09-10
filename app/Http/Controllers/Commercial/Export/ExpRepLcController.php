<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpRepLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpRepLcScRequest;

class ExpRepLcController extends Controller
{

 private $expreplcsc;
 private $explcsc;

 public function __construct(ExpRepLcScRepository $expreplcsc, ExpLcScRepository $explcsc)
 {
  $this->expreplcsc = $expreplcsc;
  $this->explcsc = $explcsc;

  $this->middleware('auth');
  $this->middleware('permission:view.expreplcs',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.expreplcs', ['only' => ['store']]);
  $this->middleware('permission:edit.expreplcs',   ['only' => ['update']]);
  $this->middleware('permission:delete.expreplcs', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  /*$expreplcscs=array();
        $rows=$this->expreplcsc
        ->where([['exp_lc_sc_id','=',request('exp_lc_sc_id',0)]])
        ->get();
        foreach($rows as $row){
            $expreplcsc['id']=$row->id;
            $expreplcsc['exp_lc_sc_id']=$row->exp_lc_sc_id;
            $expreplcsc['lc_sc_value']=$row->lc_sc_value;       
            $expreplcsc['replaced_amount']=$row->replaced_amount;
            array_push($expreplcscs, $expreplcsc);
        }
        echo json_encode($expreplcscs);*/

  $rows = $this->expreplcsc
   ->selectRaw('
            exp_rep_lc_scs.id,
            exp_rep_lc_scs.exp_lc_sc_id ,
            exp_rep_lc_scs.replaced_lc_sc_id ,
            exp_rep_lc_scs.replaced_amount ,
            exp_lc_scs.lc_sc_no ,
            exp_lc_scs.lc_sc_date ,
            exp_lc_scs.lc_sc_value ,
            buyers.code as buyer,
            companies.code as company,
            cumulatives.total_replaced
        ')
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin(\DB::raw("(SELECT exp_lc_scs.id as exp_lc_sc_id,sum(exp_rep_lc_scs.replaced_amount) as total_replaced FROM exp_rep_lc_scs right join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   group by exp_lc_scs.id) cumulatives"), "cumulatives.exp_lc_sc_id", "=", "exp_lc_scs.id")

   ->where([['exp_rep_lc_scs.exp_lc_sc_id', '=', request('exp_lc_sc_id', 0)]])
   ->groupBy([
    'exp_rep_lc_scs.id',
    'exp_rep_lc_scs.exp_lc_sc_id',
    'exp_rep_lc_scs.replaced_lc_sc_id',
    'exp_rep_lc_scs.replaced_amount',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.lc_sc_value',
    'buyers.code',
    'companies.code',
    'cumulatives.total_replaced'
   ])

   ->get()
   ->map(function ($rows) {
    $rows->balance = $rows->lc_sc_value - $rows->replaced_amount;

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
 public function store(ExpRepLcScRequest $request)
 {
  $expreplcsc = $this->expreplcsc->create(['exp_lc_sc_id' => $request->exp_lc_sc_id, 'replaced_lc_sc_id' => $request->replaced_lc_sc_id, 'replaced_amount' => $request->replaced_amount]);

  if ($expreplcsc) {
   return response()->json(array('success' => true, 'id' => $expreplcsc->id, 'message' => 'Save Successfully'), 200);
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
  $rows = $this->expreplcsc
   ->selectRaw('
            exp_rep_lc_scs.id,
            exp_rep_lc_scs.exp_lc_sc_id ,
            exp_rep_lc_scs.replaced_lc_sc_id ,
            exp_rep_lc_scs.replaced_amount ,
            exp_lc_scs.lc_sc_no ,
            exp_lc_scs.lc_sc_date ,
            exp_lc_scs.lc_sc_value ,
            buyers.code as buyer_id,
            companies.code as company,
            cumulatives.total_replaced
        ')
   ->join('exp_lc_scs', function ($join) {
    $join->on('exp_lc_scs.id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin(\DB::raw("(SELECT exp_lc_scs.id as exp_lc_sc_id,sum(exp_rep_lc_scs.replaced_amount) as total_replaced FROM exp_rep_lc_scs right join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   group by exp_lc_scs.id) cumulatives"), "cumulatives.exp_lc_sc_id", "=", "exp_lc_scs.id")

   ->where([['exp_rep_lc_scs.id', '=', $id]])

   ->groupBy([
    'exp_rep_lc_scs.id',
    'exp_rep_lc_scs.exp_lc_sc_id',
    'exp_rep_lc_scs.replaced_lc_sc_id',
    'exp_rep_lc_scs.replaced_amount',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.lc_sc_value',
    'buyers.code',
    'companies.code',
    'cumulatives.total_replaced'
   ])
   ->get()
   ->map(function ($rows) {
    $rows->balance = $rows->lc_sc_value - $rows->total_replaced;
    return $rows;
   })->first();
  $row['fromData'] = $rows;
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
 public function update(ExpRepLcScRequest $request, $id)
 {

  $expreplcsc = $this->expreplcsc->update($id, ['replaced_amount' => $request->replaced_amount]);

  if ($expreplcsc) {
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
  if ($this->expreplcscs->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function importreplc()
 {
  $rows = $this->explcsc
   ->selectRaw('
            exp_lc_scs.id ,
            exp_lc_scs.lc_sc_no ,
            exp_lc_scs.lc_sc_date ,
            exp_lc_scs.lc_sc_value ,
            buyers.code as buyer,
            companies.code as company,
            cumulatives.total_replaced
        ')
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
   })
   ->leftJoin(\DB::raw("(SELECT exp_lc_scs.id as exp_lc_sc_id,sum(exp_rep_lc_scs.replaced_amount) as total_replaced FROM exp_rep_lc_scs right join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   group by exp_lc_scs.id) cumulatives"), "cumulatives.exp_lc_sc_id", "=", "exp_lc_scs.id")
   ->when(request('lc_sc_no'), function ($q) {
    return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%" . request('lc_sc_no', 0) . "%");
   })
   ->when(request('beneficiary_id'), function ($q) {
    return $q->where('exp_lc_scs.beneficiary_id', '=', request('beneficiary_id', 0));
   })
   ->when(request('buyer_id'), function ($q) {
    return $q->where('exp_lc_scs.buyer_id', '=', request('buyer_id', 0));
   })
   ->where([['lc_sc_nature_id', '=', 2]])
   ->where([['exp_lc_scs.file_no', '=', request('file_no')]])
   ->groupBy([
    'exp_lc_scs.id',
    'exp_lc_scs.lc_sc_no',
    'exp_lc_scs.lc_sc_date',
    'exp_lc_scs.lc_sc_value',
    'buyers.code',
    'companies.code',
    'cumulatives.total_replaced'
   ])
   ->get()
   ->map(function ($rows) {
    $rows->balance = $rows->lc_sc_value - $rows->total_replaced;
    return $rows;
   });
  echo json_encode($rows);
 }
}
