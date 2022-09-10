<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpInvoiceRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpInvoiceRequest;

class LocalExpInvoiceController extends Controller {

    private $localexpinvoice;
    private $bankaccount;
    private $company;
    private $country;
    private $localexplc;
    private $buyer;

    public function __construct(LocalExpInvoiceRepository $localexpinvoice,BankAccountRepository $bankaccount, CompanyRepository $company, LocalExpLcRepository $localexplc, CountryRepository $country,BuyerRepository $buyer, CurrencyRepository $currency) {

        $this->localexplc = $localexplc;
        $this->localexpinvoice = $localexpinvoice;
        $this->bankaccount = $bankaccount;
        $this->company = $company;
        $this->country = $country;
        $this->buyer = $buyer;
        $this->currency = $currency;

        $this->middleware('auth');
        // $this->middleware('permission:view.localexpinvoices',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.localexpinvoices', ['only' => ['store']]);
        // $this->middleware('permission:edit.localexpinvoices',   ['only' => ['update']]);
        // $this->middleware('permission:delete.localexpinvoices', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         
        $localexpinvoices=array();
        $rows=$this->localexpinvoice
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->orderBy('local_exp_invoices.id','desc')
        ->get([
            'local_exp_lcs.id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.beneficiary_id',
            'local_exp_lcs.buyer_id',
            'buyers.name as buyer_id',
            'companies.code as beneficiary',
            'local_exp_lcs.lien_date',
            'local_exp_lcs.hs_code',
            'local_exp_invoices.*'
            ]);
        foreach($rows as $row){
            $localexpinvoice['id']=$row->id;
            $localexpinvoice['local_exp_lc_id']=$row->local_exp_lc_id;
            $localexpinvoice['local_lc_no']=$row->local_lc_no;
            $localexpinvoice['local_invoice_no']=$row->local_invoice_no;
            $localexpinvoice['local_invoice_date']=date('Y-m-d',strtotime($row->local_invoice_date));
            $localexpinvoice['local_invoice_value']=number_format($row->local_invoice_value,2);
            $localexpinvoice['actual_delivery_date']=($row->actual_delivery_date !==null)?date('Y-m-d',strtotime($row->actual_delivery_date)):null;
            $localexpinvoice['remarks']=$row->remarks;
            $localexpinvoice['beneficiary']=$row->beneficiary;

            array_push($localexpinvoices,$localexpinvoice);
        }
        echo json_encode($localexpinvoices);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        return Template::LoadView('Commercial.LocalExport.LocalExpInvoice',['company'=>$company]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocalExpInvoiceRequest $request) {
        $localexpinvoice=$this->localexpinvoice->create($request->except(['id','local_lc_no','buyer_id','company_id','beneficiary_id','lien_date','hs_code']));
     
        if($localexpinvoice){
            return response()->json(array('success' => true,'id' =>  $localexpinvoice->id,'message' => 'Save Successfully'),200);
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
       $localexpinvoice = $this->localexpinvoice
       ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
        })
       ->join('companies',function($join){
            $join->on('companies.id','=','local_exp_lcs.beneficiary_id');
        })
       ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->where([['local_exp_invoices.id','=',$id]])
        ->get([
            'local_exp_invoices.*',
            'local_exp_lcs.id as local_exp_lc_id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.beneficiary_id',
            'local_exp_lcs.buyer_id', 
            'local_exp_lcs.lien_date',
            'local_exp_lcs.hs_code',
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id'
           
        ])
       ->first();
       $localexpinvoice->invoice_amount=$localexpinvoice->invoice_value;
       $row ['fromData'] = $localexpinvoice;
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
    public function update(LocalExpInvoiceRequest $request, $id) {
        $localexpinvoice=$this->localexpinvoice->update($id,$request->except(['id','local_lc_no','buyer_id','company_id','beneficiary_id','lien_date','hs_code']));
        if($localexpinvoice){
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
        if($this->localexpinvoice->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getLocalLc(){
        $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'code','id'),'-Select-','');
         
        $localexplcs=array();
        $rows=$this->localexplc
        ->orderBy('local_exp_lcs.id','desc')
        ->get();
        foreach($rows as $row){
            $localexplc['id']=$row->id;
            $localexplc['local_lc_no']=$row->local_lc_no;
            $localexplc['beneficiary_id']=$company[$row->beneficiary_id];//combo
            $localexplc['buyer_id']=$buyer[$row->buyer_id];
            $localexplc['lc_date']=date('Y-m-d',strtotime($row->lc_sc_date));
            $localexplc['lc_value']=$row->lc_value;
            $localexplc['currency']=$currency[$row->currency_id];
            $localexplc['exch_rate']=$row->exch_rate;
            $localexplc['hs_code']=$row->hs_code;
            $localexplc['lien_date']=$row->lien_date;
            array_push($localexplcs,$localexplc);
        }
        echo json_encode($localexplcs);
    }
}
