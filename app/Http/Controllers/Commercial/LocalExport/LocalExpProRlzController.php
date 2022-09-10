<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzDeductRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzAmountRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubBankRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpProRlzRequest;

class LocalExpProRlzController extends Controller {

    private $localexplc;
    private $localexpprorlz;
    private $localexpprorlzdeduct;
    private $localexpprorlzamount;
    private $currency;
    private $buyer;
    private $company;
    private $localexpdocsubbank;
    private $commercialhead;
    

    public function __construct(LocalExpProRlzRepository $localexpprorlz,LocalExpProRlzDeductRepository $localexpprorlzdeduct,LocalExpProRlzAmountRepository $localexpprorlzamount,LocalExpLcRepository $localexplc,CurrencyRepository $currency,BuyerRepository $buyer,CompanyRepository $company, LocalExpDocSubBankRepository $localexpdocsubbank,CommercialHeadRepository $commercialhead) {
        $this->localexplc = $localexplc;
        $this->localexpprorlz = $localexpprorlz;
        $this->localexpprorlzdeduct = $localexpprorlzdeduct;
        $this->localexpprorlzamount = $localexpprorlzamount;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->localexpdocsubbank = $localexpdocsubbank;
        $this->commercialhead = $commercialhead;

        $this->middleware('auth');

        // $this->middleware('permission:view.localexpprorlzs',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.localexpprorlzs', ['only' => ['store']]);
        // $this->middleware('permission:edit.localexpprorlzs',   ['only' => ['update']]);
        // $this->middleware('permission:delete.localexpprorlzs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $submissiontype=array_prepend(config('bprs.submissiontype'), '','');
        $rows=$this->localexpdocsubbank
        ->join('local_exp_doc_sub_accepts',function($join){
            $join->on('local_exp_doc_sub_banks.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
         })
        ->leftJoin('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->join('local_exp_pro_rlzs',function($join){
            $join->on('local_exp_pro_rlzs.local_exp_doc_sub_bank_id','=','local_exp_doc_sub_banks.id');
        })
        // ->leftJoin(\DB::raw("(SELECT
        //  local_exp_doc_sub_banks.id,
        //  sum(exp_invoice_orders.amount) as bank_ref_amount 
        //  FROM local_exp_doc_sub_banks
        //   right join exp_doc_sub_invoices on exp_doc_sub_invoices.local_exp_doc_sub_bank_id = local_exp_doc_sub_banks.id 
        //   right join exp_invoices on exp_invoices.id = exp_doc_sub_invoices.exp_invoice_id 
        //   right join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id 
        //   where exp_doc_sub_invoices.deleted_at is null and exp_invoice_orders.deleted_at is null  
        //   group by local_exp_doc_sub_banks.id) Docsub"), "Docsub.id", "=", "local_exp_doc_sub_banks.id")
        ->leftJoin(\DB::raw("(SELECT 
            local_exp_doc_sub_banks.id,
            sum(local_exp_invoices.local_invoice_value) as bank_ref_amount 
            FROM local_exp_doc_sub_banks 
            join local_exp_doc_sub_accepts 
               on local_exp_doc_sub_banks.local_exp_doc_sub_accept_id = local_exp_doc_sub_accepts.id
            join local_exp_doc_sub_invoices on local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id = local_exp_doc_sub_accepts.id 
            join local_exp_invoices on local_exp_invoices.id = local_exp_doc_sub_invoices.local_exp_invoice_id  
            where 
            local_exp_doc_sub_invoices.deleted_at is null 
            group by local_exp_doc_sub_banks.id 
            ) netinvval"), "netinvval.id", "=", "local_exp_doc_sub_banks.id")

        ->leftJoin(\DB::raw("(
            SELECT
         local_exp_doc_sub_banks.id,
         sum(local_exp_doc_sub_trans.doc_value) as negotiated_amount 
         FROM local_exp_doc_sub_banks 
          right join local_exp_doc_sub_trans
           on local_exp_doc_sub_trans.local_exp_doc_sub_bank_id = local_exp_doc_sub_banks.id  
          where local_exp_doc_sub_trans.deleted_at is null  
          group by local_exp_doc_sub_banks.id) DocsubTra"), "DocsubTra.id", "=", "local_exp_doc_sub_banks.id")
        ->when(request('bank_ref_bill_no'), function ($q) {
            return $q->where('local_exp_doc_sub_banks.bank_ref_bill_no', 'LIKE', "%".request('bank_ref_bill_no', 0)."%");
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('local_exp_doc_sub_banks.bank_ref_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('local_exp_doc_sub_banks.bank_ref_date', '<=',request('date_to', 0));
        })
        ->get([            
            'local_exp_doc_sub_banks.id as local_exp_doc_sub_bank_id',
            'local_exp_doc_sub_banks.bank_ref_bill_no',
            'local_exp_doc_sub_banks.bank_ref_date',
            'local_exp_doc_sub_banks.courier_recpt_no',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.beneficiary_id',
            'local_exp_lcs.buyer_id',
            'local_exp_lcs.buyers_bank',
            'local_exp_lcs.currency_id',
            'local_exp_pro_rlzs.id',
            'local_exp_pro_rlzs.realization_date',
            'local_exp_pro_rlzs.remarks',
            'currencies.name as currency_id',
            'netinvval.bank_ref_amount',
            'DocsubTra.negotiated_amount'
        ])
        ->map(function ($rows){
            $rows->bank_ref_date=date('d-M-y',strtotime($rows->bank_ref_date));
            $rows->realization_date=date('d-M-y',strtotime($rows->realization_date));
            return $rows;
        });
        echo json_encode ($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');

        return Template::LoadView('Commercial.LocalExport.LocalExpProRlz',['company'=>$company,'currency'=>$currency,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocalExpProRlzRequest $request) {
        \DB::beginTransaction();
        $localexpprorlz=$this->localexpprorlz->create(['local_exp_doc_sub_bank_id'=>$request->local_exp_doc_sub_bank_id,'realization_date'=>$request->realization_date,'remarks'=>$request->remarks]);
        try
        {
            if($request->exists('commercial_head_id'))
            {
                foreach($request->commercial_head_id as $index=>$commercial_head_id)
                {
                $this->localexpprorlzdeduct->create(['local_exp_pro_rlz_id'=>$localexpprorlz->id,'commercial_head_id'=>$request->commercial_head_id[$index],'doc_value'=>$request->doc_value[$index],'exch_rate'=>$request->exch_rate[$index],'dom_value'=>$request->dom_value[$index]]);
                }
            }
            
            foreach($request->a_commercial_head_id as $index=>$a_commercial_head_id)
            {
                $this->localexpprorlzamount->create(['local_exp_pro_rlz_id'=>$localexpprorlz->id,'commercial_head_id'=>$request->a_commercial_head_id[$index],'doc_value'=>$request->a_doc_value[$index],'exch_rate'=>$request->a_exch_rate[$index],'dom_value'=>$request->a_dom_value[$index],'ac_loan_no'=>$request->a_ac_loan_no[$index]]);
            }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        
        if($localexpprorlz){
            return response()->json(array('success' => true,'id' =>  $localexpprorlz->id,'message' => 'Save Successfully'),200);
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
        $localexpprorlz = $this->localexpprorlz->find($id);

        $localexpprorlzdeduct=$this->localexpprorlzdeduct
        ->join('commercial_heads',function($join){
            $join->on('commercial_heads.id','=','local_exp_pro_rlz_deducts.commercial_head_id');
        })
        ->where([['local_exp_pro_rlz_id','=',$id]])
        ->get(['local_exp_pro_rlz_deducts.*','commercial_heads.name']);

        $localexpprorlzamount=$this->localexpprorlzamount
        ->join('commercial_heads',function($join){
            $join->on('commercial_heads.id','=','local_exp_pro_rlz_amounts.commercial_head_id');
        })
        ->where([['local_exp_pro_rlz_id','=',$id]])
        ->get(['local_exp_pro_rlz_amounts.*','commercial_heads.name']);

        $commercialhead=$this->commercialhead->get(['id','name']);

        $row ['fromData'] = $localexpprorlz;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['localexpprorlzdeduct'] = $localexpprorlzdeduct;
        $row ['localexpprorlzamount'] = $localexpprorlzamount;
        $row ['head'] = $commercialhead;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LocalExpProRlzRequest $request, $id) {
        \DB::beginTransaction();
        $this->localexpprorlzdeduct->where([['local_exp_pro_rlz_id','=',$id]])->delete();
        $this->localexpprorlzamount->where([['local_exp_pro_rlz_id','=',$id]])->delete();
        $localexpprorlz=$this->localexpprorlz->update($id,['realization_date'=>$request->realization_date,'remarks'=>$request->remarks]);
        try
        {
            if($request->exists('commercial_head_id'))
            {
                foreach($request->commercial_head_id as $index=>$commercial_head_id)
                {
                    $this->localexpprorlzdeduct->create(['local_exp_pro_rlz_id'=>$id,'commercial_head_id'=>$request->commercial_head_id[$index],'doc_value'=>$request->doc_value[$index],'exch_rate'=>$request->exch_rate[$index],'dom_value'=>$request->dom_value[$index]]);
                }
            }
            foreach($request->a_commercial_head_id as $index=>$a_commercial_head_id)
            {
                $this->localexpprorlzamount->create(['local_exp_pro_rlz_id'=>$id,'commercial_head_id'=>$request->a_commercial_head_id[$index],'doc_value'=>$request->a_doc_value[$index],'exch_rate'=>$request->a_exch_rate[$index],'dom_value'=>$request->a_dom_value[$index],'ac_loan_no'=>$request->a_ac_loan_no[$index]]);
            }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        
        if($localexpprorlz){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->localexpprorlz->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
    public function gethead()
    {
        $commercialhead=$this->commercialhead->get(['id','name']);
        $commercial_head_id=$this->commercialhead->where([['commercialhead_type_id','=',4]])->get(['id','name'])->first();
        $rows=$this->localexpdocsubbank
        ->leftJoin(\DB::raw("(SELECT local_exp_doc_sub_banks.id,sum(local_exp_doc_sub_trans.doc_value) as doc_value,sum(local_exp_doc_sub_trans.dom_value) as dom_value,avg(local_exp_doc_sub_trans.exch_rate) as exch_rate  FROM local_exp_doc_sub_banks  right join local_exp_doc_sub_trans on local_exp_doc_sub_trans.local_exp_doc_sub_bank_id = local_exp_doc_sub_banks.id  where local_exp_doc_sub_trans.deleted_at is null  group by local_exp_doc_sub_banks.id) DocsubTra"), "DocsubTra.id", "=", "local_exp_doc_sub_banks.id")
        ->where([['local_exp_doc_sub_banks.id','=',request('local_exp_doc_sub_bank_id',0)]])
        ->get(['DocsubTra.doc_value','DocsubTra.dom_value','DocsubTra.exch_rate'])
        ->map(function ($rows) use($commercial_head_id){
            $rows->commercial_head_id=$commercial_head_id->id;
            $rows->name=$commercial_head_id->name;
            return $rows;
        });
        $head=['head'=>$commercialhead,'localexpprorlzdeduct'=>[],'localexpprorlzamount'=>$rows];
        echo json_encode($head);
    }

    public function importLocalDocSubBank(){
        $submissiontype=array_prepend(config('bprs.submissiontype'), '','');
        $rows=$this->localexpdocsubbank
        ->join('local_exp_doc_sub_accepts',function($join){
            $join->on('local_exp_doc_sub_banks.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
         })
        ->leftJoin('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->leftJoin('local_exp_pro_rlzs',function($join){
            $join->on('local_exp_pro_rlzs.local_exp_doc_sub_bank_id','=','local_exp_doc_sub_banks.id');
        })
        // ->leftJoin(\DB::raw("(SELECT
        //  local_exp_doc_sub_banks.id,
        //  sum(local_exp_invoice_orders.amount) as bank_ref_amount 
        //  FROM local_exp_doc_sub_banks 
        //   right join local_exp_doc_sub_invoices
        //    on local_exp_doc_sub_invoices.local_exp_doc_sub_bank_id = local_exp_doc_sub_banks.id 
        //  right join local_exp_invoices 
        //   on local_exp_invoices.id = local_exp_doc_sub_invoices.local_exp_invoice_id
        // right join local_exp_invoice_orders
        //  on local_exp_invoice_orders.local_exp_invoice_id = local_exp_invoices.id 
        // where local_exp_doc_sub_invoices.deleted_at is null 
        // and local_exp_invoice_orders.deleted_at is null  
        // group by local_exp_doc_sub_banks.id) Docsub"), "Docsub.id", "=", "local_exp_doc_sub_banks.id")
        ->leftJoin(\DB::raw("(SELECT 
            local_exp_doc_sub_banks.id,
            sum(local_exp_invoices.local_invoice_value) as bank_ref_amount 
            FROM local_exp_doc_sub_banks 
            join local_exp_doc_sub_accepts 
               on local_exp_doc_sub_banks.local_exp_doc_sub_accept_id = local_exp_doc_sub_accepts.id
            join local_exp_doc_sub_invoices on local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id = local_exp_doc_sub_accepts.id 
            join local_exp_invoices on local_exp_invoices.id = local_exp_doc_sub_invoices.local_exp_invoice_id  
            where 
            local_exp_doc_sub_invoices.deleted_at is null 
            group by local_exp_doc_sub_banks.id
            ) netinvval"), "netinvval.id", "=", "local_exp_doc_sub_banks.id")
        ->leftJoin(\DB::raw("(SELECT local_exp_doc_sub_banks.id,sum(local_exp_doc_sub_trans.doc_value) as negotiated_amount FROM local_exp_doc_sub_banks  right join local_exp_doc_sub_trans on local_exp_doc_sub_trans.local_exp_doc_sub_bank_id = local_exp_doc_sub_banks.id  where local_exp_doc_sub_trans.deleted_at is null  group by local_exp_doc_sub_banks.id) DocsubTra"), "DocsubTra.id", "=", "local_exp_doc_sub_banks.id")
        ->when(request('bank_ref_bill_no'), function ($q) {
            return $q->where('local_exp_doc_sub_banks.bank_ref_bill_no', 'LIKE', "%".request('bank_ref_bill_no', 0)."%");
        })
        ->when(request('date_from'), function ($q) {
			return $q->where('local_exp_doc_sub_banks.bank_ref_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
			return $q->where('local_exp_doc_sub_banks.bank_ref_date', '<=',request('date_to', 0));
		})
        ->get([            
            'local_exp_doc_sub_banks.id as local_exp_doc_sub_bank_id',
            'local_exp_doc_sub_banks.bank_ref_bill_no',
            'local_exp_doc_sub_banks.bank_ref_date',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.beneficiary_id',
            'local_exp_lcs.buyer_id', 
            'local_exp_lcs.buyers_bank', 
            'local_exp_lcs.currency_id', 
            'local_exp_pro_rlzs.id',
            'local_exp_pro_rlzs.realization_date',
            'local_exp_pro_rlzs.remarks',
            'currencies.name as currency_id',
            'netinvval.bank_ref_amount',
            'DocsubTra.negotiated_amount'
        ])
        ->map(function ($rows){
            $rows->bank_ref_date=date('d-M-y',strtotime($rows->bank_ref_date));
            $rows->local_lc_no=$rows->local_lc_no;
            return $rows;
        });
       echo json_encode ($rows);
    }
}
