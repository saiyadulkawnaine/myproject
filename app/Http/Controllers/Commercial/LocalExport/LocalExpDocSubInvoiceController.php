<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubAcceptRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubInvoiceRepository;

use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpDocSubInvoiceRequest;

class LocalExpDocSubInvoiceController extends Controller {

    private $localexplc;
    private $localexpdocsubaccept;
    private $localexpdocsubinvoice;
    

    public function __construct(LocalExpDocSubAcceptRepository $localexpdocsubaccept, LocalExpDocSubInvoiceRepository $localexpdocsubinvoice,LocalExpLcRepository $localexplc) {

        $this->localexplc = $localexplc;
        $this->localexpdocsubaccept = $localexpdocsubaccept;
        $this->localexpdocsubinvoice = $localexpdocsubinvoice;
        

        $this->middleware('auth');
        // $this->middleware('permission:view.localexpdocsubinvoices',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.localexpdocsubinvoices', ['only' => ['store']]);
        // $this->middleware('permission:edit.localexpdocsubinvoices',   ['only' => ['update']]);
        // $this->middleware('permission:delete.localexpdocsubinvoices', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {         
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        $impdocaccept=$this->localexpdocsubaccept
        ->selectRaw('
            local_exp_invoices.id as local_exp_invoice_id,
            local_exp_invoices.local_invoice_no,
            local_exp_invoices.local_invoice_date,
            local_exp_invoices.local_invoice_value,
            local_exp_lcs.local_lc_no,
            local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id
        ')
        ->join('local_exp_invoices', function($join)  {
            $join->on('local_exp_invoices.local_exp_lc_id', '=', 'local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('local_exp_lcs', function($join)  {
            $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
        })
        ->join('local_exp_doc_sub_invoices',function($join){
          $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
          $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
          $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',request('local_exp_doc_sub_accept_id',0)]])
        ->get()
        ->map(function ($impdocaccept){
            $impdocaccept->local_invoice_date=date('d-M-y',strtotime($impdocaccept->local_invoice_date));
            $impdocaccept->local_invoice_value=number_format($impdocaccept->local_invoice_value,2);
        return $impdocaccept;
        });

        $saved = $impdocaccept->filter(function ($value) {
            if($value->local_exp_doc_sub_invoice_id){
                return $value;
            }
        })->values();

        $impdocacceptnew=$this->localexpdocsubaccept
        ->selectRaw('
            local_exp_invoices.id as local_exp_invoice_id,
            local_exp_invoices.local_invoice_no,
            local_exp_invoices.local_invoice_date,
            local_exp_invoices.local_invoice_value,
            local_exp_lcs.local_lc_no,
            local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id
        ')
        ->join('local_exp_invoices', function($join)  {
            $join->on('local_exp_invoices.local_exp_lc_id', '=', 'local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('local_exp_lcs', function($join)  {
            $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
        })
        ->leftJoin('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
          $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',request('local_exp_doc_sub_accept_id',0)]])
        ->get()
        ->map(function ($impdocacceptnew){
            $impdocacceptnew->local_invoice_date=date('d-M-y',strtotime($impdocacceptnew->local_invoice_date));
            $impdocacceptnew->local_invoice_value=number_format($impdocacceptnew->local_invoice_value,2);
            return $impdocacceptnew;
        });
        $new = $impdocacceptnew->filter(function ($value) {
            if(!$value->local_exp_doc_sub_invoice_id)
            {
                return $value;
            }
        })
        ->values();
        
        echo json_encode(array('new'=>$new,'saved'=>$saved));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocalExpDocSubInvoiceRequest $request) {
        foreach($request->local_exp_invoice_id as $index=>$local_exp_invoice_id){
            if($local_exp_invoice_id)
            {
                $localexpdocsubinvoice = $this->localexpdocsubinvoice->create(
                ['local_exp_invoice_id' => $local_exp_invoice_id,'local_exp_doc_sub_accept_id' => $request->local_exp_doc_sub_accept_id]);
            }
        }
        if($localexpdocsubinvoice){
            return response()->json(array('success' => true,'id' =>  $localexpdocsubinvoice->id,'message' => 'Save Successfully'),200);
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
      //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LocalExpDocSubInvoiceRequest $request, $id) {
          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->localexpdocsubinvoice->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    // public function getInvoiceDetails(){
    //     $rows=$this->localexplc
    //     ->join('companies',function($join){
    //         $join->on('companies.id','=','local_exp_lcs.beneficiary_id');
    //     })
    //    ->join('buyers',function($join){
    //         $join->on('buyers.id','=','local_exp_lcs.buyer_id');
    //     })
    //    ->join('currencies',function($join){
    //         $join->on('currencies.id','=','local_exp_lcs.currency_id');
    //     })
    //     /* ->when(request('local_lc_no'), function ($q) {
    //         return $q->where('local_exp_lcs.local_lc_no', '=', request('local_lc_no', 0));
    //     }) */
    //     //->groupBy(['local_exp_lcs.id'])
    //     ->orderBy('local_exp_lcs.id','asc')
    //     ->get([
    //         'local_exp_lcs.*',
    //        /* 'local_exp_lcs.local_lc_no as local_exp_lc_id',
    //         'local_exp_lcs.beneficiary_id',
    //         'local_exp_lcs.buyer_id', 
    //         'local_exp_lcs.buyers_bank', 
    //         'local_exp_lcs.currency_id', */
    //         'buyers.name as buyer_id',
    //         'companies.name as beneficiary_id',
    //         'currencies.name as currency_id'
    //     ]);
    //         echo json_encode($rows);
    // }
}
