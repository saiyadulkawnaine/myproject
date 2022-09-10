<?php
namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
// use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveDocPrepRepository;
// use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveYarnBtpLcRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveClaimRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use PDF;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveRefRequest;

class CashIncentiveRefController extends Controller {

    private $cashincentiveref;
    private $cashincentiveclaim;
    private $explcsc;
    private $country;
    private $supplier;
    private $itemaccount;

    public function __construct(CashIncentiveRefRepository $cashincentiveref,ExpLcScRepository $explcsc,CountryRepository $country,ItemAccountRepository $itemaccount,SupplierRepository $supplier,CompanyRepository $company,CashIncentiveClaimRepository $cashincentiveclaim) {
        $this->cashincentiveref = $cashincentiveref;
    	$this->cashincentiveclaim = $cashincentiveclaim;
        $this->explcsc = $explcsc;
        $this->country = $country;
        $this->itemaccount = $itemaccount;
        $this->supplier = $supplier;
        $this->company = $company;

        $this->middleware('auth');

        $this->middleware('permission:view.cashincentiverefs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.cashincentiverefs', ['only' => ['store']]);
        $this->middleware('permission:edit.cashincentiverefs',   ['only' => ['update']]);
        $this->middleware('permission:delete.cashincentiverefs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $region=config('bprs.region');
        $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
         
        $replcsc=$this->explcsc
        ->selectRaw('
            exp_rep_lc_scs.exp_lc_sc_id ,
            exp_rep_lc_scs.replaced_lc_sc_id ,
            replaced_scs.lc_sc_no ,
            replaced_scs.lc_sc_date ,
            replaced_scs.lc_sc_value,
            currencies.symbol as currency_symbol
        ')
        ->join('exp_rep_lc_scs', function($join)  {
            $join->on('exp_lc_scs.id', '=', 'exp_rep_lc_scs.exp_lc_sc_id');
        })
        ->join('exp_lc_scs as replaced_scs', function($join)  {
            $join->on('replaced_scs.id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->get();

        $replaceArr=[];
        foreach ($replcsc as $date) {
            $replaceArr[$date->exp_lc_sc_id][]=$date->lc_sc_no;
        }

        $replacedScLc=[];
        foreach($replaceArr as $key=>$val){
            $replacedScLc[$key]=implode('; ',$val);
        }

        $cashincentiverefs=array();
        $rows=$this->cashincentiveref
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','cash_incentive_refs.company_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->orderBy('cash_incentive_refs.id','desc')
        ->get([
            'cash_incentive_refs.*',
            'exp_lc_scs.lc_sc_no',
            'companies.name as company_name',
            'buyers.name as buyer_name',
        ]);
        
        foreach($rows as $row){
            $cashincentiveref['id']=$row->id;
            $cashincentiveref['incentive_no']=$row->incentive_no;
            $cashincentiveref['lc_sc_no']=$row->lc_sc_no;
            $cashincentiveref['remarks']=$row->remarks;
            $cashincentiveref['bank_file_no']=($row->bank_file_no)?$row->bank_file_no:'--';
            $cashincentiveref['claim_sub_date']=($row->claim_sub_date!==null)?date('Y-m-d',strtotime($row->claim_sub_date)):'--';
            $cashincentiveref['region_id']=$region[$row->region_id];
            $cashincentiveref['company_name']=$row->company_name;
            $cashincentiveref['buyer_name']=$row->buyer_name;
            $cashincentiveref['replaced_lc_sc_no']=isset($replacedScLc[$row->exp_lc_sc_id])?$replacedScLc[$row->exp_lc_sc_id]:'--';
            
            array_push($cashincentiverefs,$cashincentiveref);
        }
        echo json_encode($cashincentiverefs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        //$country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-','');
        $region = array_prepend(config('bprs.region'), '-Select-','');
        $filequery = array_prepend(config('bprs.filequery'), '-Select-','');

        return Template::LoadView('Commercial.CashIncentive.CashIncentiveRef',['region'=>$region,'supplier'=>$supplier,'company'=>$company,'yesno'=>$yesno,'filequery'=>$filequery]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashIncentiveRefRequest $request) {
        $max = $this->cashincentiveref->where([['company_id', $request->company_id]])->max('incentive_no');
		$incentive_no=$max+1;
        $cashincentiveref=$this->cashincentiveref->create([
            'incentive_no'=>$incentive_no,
            'bank_file_no'=>$request->bank_file_no,
            'company_id'=>$request->company_id,
            'region_id'=>$request->region_id,'claim_sub_date'=>$request->claim_sub_date,
            'exp_lc_sc_id'=>$request->exp_lc_sc_id,
            'remarks'=>$request->remarks,
        ]);
        if($cashincentiveref){
            return response()->json(array('success' => true,'id' =>  $cashincentiveref->id, 'incentive_no' => $incentive_no ,'message' => 'Save Successfully'),200);
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
       $cashincentiveref = $this->cashincentiveref
       ->leftJoin('companies',function($join){
            $join->on('companies.id','=','cash_incentive_refs.company_id');
        })
       ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
       ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->leftJoin('bank_branches',function($join){
            $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        ->get([
            'cash_incentive_refs.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.file_no',
            'exp_lc_scs.lc_sc_value',
            'exp_lc_scs.buyer_id',
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exporter_bank_branch_id',
            'buyers.name as buyer_id',
            'currencies.name as currency_id',
            'companies.name as company_name','bank_branches.branch_name',
            'banks.name as bank_name',
        ])
        ->map(function($cashincentiveref){
            $cashincentiveref->exporter_branch_name=$cashincentiveref->bank_name.' (' .$cashincentiveref->branch_name. ' )';
            return $cashincentiveref;
        })
       ->first();
        $row ['fromData'] = $cashincentiveref;
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
    public function update(CashIncentiveRefRequest $request, $id) {
        $cashincentiveref=$this->cashincentiveref->update($id,[
            //'incentive_no'=>$incentive_no,
            'bank_file_no'=>$request->bank_file_no,
            //'company_id'=>$request->company_id,
            'region_id'=>$request->region_id,'claim_sub_date'=>$request->claim_sub_date,
            //'exp_lc_sc_id'=>$request->exp_lc_sc_id,
            'remarks'=>$request->remarks
        ]);
        if($cashincentiveref){
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
        if($this->cashincentiveref->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function expLcCashRef(){
        
        $contractNature = array_prepend(config('bprs.contractNature'), '-Select-','');
        
        $replcsc=$this->explcsc
        ->selectRaw('
            exp_rep_lc_scs.exp_lc_sc_id ,
            exp_rep_lc_scs.replaced_lc_sc_id ,
            replaced_scs.lc_sc_no ,
            replaced_scs.lc_sc_date ,
            replaced_scs.lc_sc_value,
            currencies.symbol as currency_symbol
        ')
        ->join('exp_rep_lc_scs', function($join)  {
            $join->on('exp_lc_scs.id', '=', 'exp_rep_lc_scs.exp_lc_sc_id');
        })
        ->join('exp_lc_scs as replaced_scs', function($join)  {
            $join->on('replaced_scs.id', '=', 'exp_rep_lc_scs.replaced_lc_sc_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->get();

        $replaceArr=[];
        foreach ($replcsc as $date) {
            $replaceArr[$date->exp_lc_sc_id][]=$date->lc_sc_no;
        }

        $replacedScLc=[];
        foreach($replaceArr as $key=>$val){
            $replacedScLc[$key]=implode('; ',$val);
        }

        $rows=$this->explcsc
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
        })
       ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
       ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->leftJoin('bank_branches',function($join){
            $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%".request('lc_sc_no', 0)."%");
        }) 
        ->when(request('beneficiary_id'), function ($q) {
            return $q->where('exp_lc_scs.beneficiary_id', '=', request('beneficiary_id', 0));
        })
        ->when(request('lc_sc_date'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_date', '=',request('lc_sc_date', 0));
        })
        ->orderBy('exp_lc_scs.id','desc')
        ->get([
            'exp_lc_scs.*',
            'buyers.name as buyer_id',
            'companies.name as company_name',
            'currencies.name as currency_id',
            'bank_branches.branch_name',
            'banks.name as bank_name',
        ])
        ->map(function ($rows) use($contractNature,$replacedScLc){
            $rows->replace_lc_sc_no=isset($replacedScLc[$rows->id])?$replacedScLc[$rows->id]:'--';
            $rows->lc_sc_nature_id=$contractNature[$rows->lc_sc_nature_id];
            $rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
            $rows->last_delivery_date=date('d-M-Y',strtotime($rows->last_delivery_date));
            $rows->exporter_branch_name=$rows->bank_name.' (' .$rows->branch_name. ' )';
            $rows->lc_sc_value=number_format($rows->lc_sc_value,2);
            return $rows;
        });
        echo json_encode($rows);
    }

     public function getKhaForm(){
        $id=request('id',0);
        $rows=$this->cashincentiveref
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','cash_incentive_refs.company_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->leftJoin('bank_branches',function($join){
            $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
                exp_lc_scs.id as replaced_lc_sc_id,
                exp_lc_scs.lc_sc_no, 
                exp_lc_scs.lc_sc_value,
                exp_lc_scs.lc_sc_date,
                exp_rep_lc_scs.exp_lc_sc_id 
            FROM exp_rep_lc_scs 
                join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   
            group by 
                exp_lc_scs.id,
                exp_lc_scs.lc_sc_no,
                exp_lc_scs.lc_sc_value,
                exp_lc_scs.lc_sc_date,   
                exp_rep_lc_scs.exp_lc_sc_id
                ) cumulatives"), "cumulatives.exp_lc_sc_id", "=", "cash_incentive_refs.exp_lc_sc_id")
        ->where([['cash_incentive_refs.id','=',$id]])
        ->orderBy('cash_incentive_refs.id','desc')
        ->get([
            'cash_incentive_refs.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exporter_bank_branch_id',
            'exp_lc_scs.lc_sc_value',
            'exp_lc_scs.lc_sc_date',
            'exp_lc_scs.sc_or_lc',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
            'bank_branches.branch_name',
            'banks.name as bank_name',
            'companies.name as company_name',
            'companies.address as company_address',
            'companies.erc_no',
            'buyers.name as buyer_name',
            'cumulatives.replaced_lc_sc_id',
            'cumulatives.lc_sc_no as replaced_lc_sc_no',
            'cumulatives.lc_sc_value as replaced_lc_value',
            'cumulatives.lc_sc_date as replaced_lc_date',
        ])
        ->map(function($rows){
            $rows->claim_sub_date=date('d-M-Y',strtotime($rows->claim_sub_date));
            $rows->lc_sc_date=($rows->lc_sc_date !== '')?date('d-M-Y',strtotime($rows->lc_sc_date)):'';
            $rows->lc_sc_value=number_format($rows->lc_sc_value,2);
            $rows->replaced_lc_date=($rows->replaced_lc_date !== '')?date('d-M-Y',strtotime($rows->replaced_lc_date)):'';
            $rows->replaced_lc_value=number_format($rows->replaced_lc_value,2);
            if($rows->sc_or_lc==1)
            {
              $rows->sc_lc='Sales Contract No: '.$rows->lc_sc_no.' Dt: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_lc='Export L/C No: '.$rows->lc_sc_no.' Dt: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            $lcscyarr=array();
            if($rows->replaced_lc_sc_no){
                array_push($lcscyarr,$rows->replaced_lc_sc_no);
            }
            $rows->replaced_lc_sc_no= implode(',',$lcscyarr);
            if ($rows->replaced_lc_sc_id !== null) {
                $rows->replaces_lc_sc='Under S/C No: '.$rows->replaced_lc_sc_no.' Dt: '.$rows->replaced_lc_date.', Value:'.$rows->currency_symbol.' '.$rows->replaced_lc_value;
            }else if($rows->replaced_lc_sc_id == null){
                $rows->replaces_lc_sc='';
            }
            if($rows->region_id==1){
                $rows->claim_per=6;
            }else{
                $rows->claim_per=4;
            }
            return $rows;
        })
        ->first();

        $incentiveyarnbtblc=$this->cashincentiveref
        ->selectRaw('
            cash_incentive_refs.id as cash_incentive_ref_id,
            cash_incentive_yarn_btb_lcs.imp_lc_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv,
            imp_lcs.lc_date,
            poYarns.lc_yarn_qty,
            poYarns.rate,
            poYarns.lc_yarn_amount,
            suppliers.name as supplier_name
        ')
        ->join('cash_incentive_yarn_btb_lcs', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_yarn_btb_lcs.cash_incentive_ref_id');
        })
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'cash_incentive_yarn_btb_lcs.imp_lc_id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            imp_lcs.id as imp_lc_id,
            sum(po_yarn_items.qty) as lc_yarn_qty,
            avg(po_yarn_items.rate) as rate,
            sum(po_yarn_items.amount) as lc_yarn_amount
            FROM imp_lcs 
            join imp_lc_pos on imp_lc_pos.imp_lc_id = imp_lcs.id and imp_lcs.menu_id=3
            join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id
            join po_yarn_items on po_yarn_items.po_yarn_id=po_yarns.id
            group by 
            imp_lcs.id
        ) poYarns"), "poYarns.imp_lc_id", "=", "cash_incentive_yarn_btb_lcs.imp_lc_id")
        // ->join('po_yarn_items', function($join)  {
        //     $join->on('po_yarn_items.id', '=', 'cash_incentive_yarn_btb_lcs.po_yarn_item_id');
        // })
        ->join('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        //->orderBy('cash_incentive_yarn_btb_lcs.id','desc')
        ->groupBy([
            'cash_incentive_refs.id',
            'cash_incentive_yarn_btb_lcs.imp_lc_id',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'imp_lcs.lc_date',
            'suppliers.name',
            'poYarns.lc_yarn_qty',
            'poYarns.rate',
            'poYarns.lc_yarn_amount',
        ])
        ->get()
        ->map(function($incentiveyarnbtblc){
            $incentiveyarnbtblc->lc_no=$incentiveyarnbtblc->lc_no_i."".$incentiveyarnbtblc->lc_no_ii."".$incentiveyarnbtblc->lc_no_iii."".$incentiveyarnbtblc->lc_no_iv;
            return $incentiveyarnbtblc;
        });

        
        $gmtcategory=array_prepend(config('bprs.gmtcategory'),'-Select-','');
        $invoice=$this->cashincentiveref
        ->join('cash_incentive_claims', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_claims.cash_incentive_ref_id');
        })
        ->join('exp_doc_sub_invoices', function($join)  {
            $join->on('exp_doc_sub_invoices.id', '=', 'cash_incentive_claims.exp_doc_sub_invoice_id');
        })
        ->join('exp_doc_submissions', function($join)  {
            $join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
        })
        ->join('exp_invoices',function($join){
            $join->on('exp_invoices.exp_lc_sc_id', '=', 'exp_doc_submissions.exp_lc_sc_id');
            $join->on('exp_doc_sub_invoices.exp_invoice_id','=','exp_invoices.id');
        })
        ->join('exp_invoice_orders', function($join){
            $join->on('exp_invoice_orders.exp_invoice_id', '=', 'exp_invoices.id');
            $join->whereNull('exp_invoice_orders.deleted_at');
        })
        ->join('exp_pi_orders', function($join){
            $join->on('exp_pi_orders.id', '=', 'exp_invoice_orders.exp_pi_order_id');
        })
        ->join('sales_orders', function($join){
            $join->on('exp_pi_orders.sales_order_id', '=', 'sales_orders.id');
        })
        ->join('exp_lc_scs', function($join){
            $join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
        })
        ->leftJoin('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
        })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','sales_order_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
            $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        ->orderBy('exp_invoices.id','desc')
        ->get([
            'exp_invoices.id as exp_invoice_id',
            //'exp_invoice_orders.id',
            'item_accounts.item_description',
            'item_accounts.gmt_category',
            'item_accounts.id as item_account_id',
            'cash_incentive_claims.exch_rate',
        ]);

        $itemDesc=array();
        foreach($invoice as $order){
            $itemDesc[$order->exp_invoice_id]=$gmtcategory[$order->gmt_category];
        }
        //dd($itemDesc);
        //die();

        $cashincentiveclaim = $this->cashincentiveref
        ->selectRaw('
            cash_incentive_refs.id as cash_incentive_ref_id,
            cash_incentive_claims.bank_bill_no,
            cash_incentive_claims.bl_date,
            cash_incentive_claims.exp_form_no,
            cash_incentive_claims.invoice_amount,
            cash_incentive_claims.realized_amount,
            cash_incentive_claims.realized_date,
            cash_incentive_claims.cost_of_export,
            cash_incentive_claims.claim,
            cash_incentive_claims.freight,
            cash_incentive_claims.claim_amount,
            avg(cash_incentive_claims.exch_rate) as exch_rate,
            cash_incentive_claims.local_cur_amount,
            exp_invoices.id as exp_invoice_id,
            cash_incentive_claims.invoice_qty
        ')
        ->join('cash_incentive_claims', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_claims.cash_incentive_ref_id');
        })
        ->join('exp_doc_sub_invoices', function($join)  {
            $join->on('exp_doc_sub_invoices.id', '=', 'cash_incentive_claims.exp_doc_sub_invoice_id');
        })
        ->join('exp_doc_submissions', function($join)  {
            $join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
        })
        ->join('exp_invoices',function($join){
            $join->on('exp_invoices.exp_lc_sc_id', '=', 'exp_doc_submissions.exp_lc_sc_id');
            $join->on('exp_doc_sub_invoices.exp_invoice_id','=','exp_invoices.id');
        })
        ->join('exp_invoice_orders',function($join){
            $join->on('exp_invoice_orders.exp_invoice_id','=','exp_invoices.id');  
            $join->whereNull('exp_invoice_orders.deleted_at');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        //->orderBy('cash_incentive_claims.id','desc')
        ->groupBy([
            'cash_incentive_refs.id',
            //'cash_incentive_claims.id',
            'cash_incentive_claims.bank_bill_no',
            'cash_incentive_claims.bl_date',
            'cash_incentive_claims.exp_form_no',
            'cash_incentive_claims.invoice_amount',
            'cash_incentive_claims.realized_amount',
            'cash_incentive_claims.cost_of_export',
            'cash_incentive_claims.realized_date',
            'cash_incentive_claims.claim',
            'cash_incentive_claims.freight',
            'cash_incentive_claims.claim_amount',
            'cash_incentive_claims.local_cur_amount',
            'exp_invoices.id',
            'cash_incentive_claims.invoice_qty',
        ])
        ->get()
        ->map(function($cashincentiveclaim) use($itemDesc){
            $cashincentiveclaim->exp_date=date('d-M-Y',strtotime($cashincentiveclaim->exp_date));
            $cashincentiveclaim->bl_date=date('d-M-Y',strtotime($cashincentiveclaim->bl_date));
            $cashincentiveclaim->realized_date=date('d-M-Y',strtotime($cashincentiveclaim->realized_date));
            $cashincentiveclaim->item_desc=$itemDesc[$cashincentiveclaim->exp_invoice_id];
            return $cashincentiveclaim;
        });


        $data = [
        'rows'=>$rows,
        'incentiveyarnbtblc'=>$incentiveyarnbtblc,
        'cashincentiveclaim'=>$cashincentiveclaim,
        ];
        
        $pdf = PDF::loadView('Defult.Commercial.CashIncentive.khaformPdf', $data);
        return $pdf->stream('khaformPdf.pdf');

        /*$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->AddPage();
        
       // $pdf->SetFont('kalpurush', '',9);
        
        $view= \View::make('Defult.Commercial.CashIncentive.khaformPdf',['rows'=>$rows,'incentiveyarnbtblc'=>$incentiveyarnbtblc,'cashincentiveclaim'=>$cashincentiveclaim]);
        $html_content=$view->render();
        $pdf->WriteHtml($html_content, true, false,true,false,'');
          
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/khaformPdf.pdf';
        $pdf->output($filename,'I');
        exit();*/
    }


    public function getCOP(){
        $id=request('id',0);
        $rows=$this->cashincentiveref
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','cash_incentive_refs.company_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->leftJoin('bank_branches',function($join){
            $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
                exp_lc_scs.id as replaced_lc_sc_id,
                exp_lc_scs.lc_sc_no, 
                exp_lc_scs.lc_sc_value,
                exp_lc_scs.lc_sc_date,
                exp_rep_lc_scs.exp_lc_sc_id 
            FROM exp_rep_lc_scs 
                join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   
            group by 
                exp_lc_scs.id,
                exp_lc_scs.lc_sc_no,
                exp_lc_scs.lc_sc_value,
                exp_lc_scs.lc_sc_date,   
                exp_rep_lc_scs.exp_lc_sc_id
                ) cumulatives"), "cumulatives.exp_lc_sc_id", "=", "cash_incentive_refs.exp_lc_sc_id")
        ->join('cash_incentive_claims', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_claims.cash_incentive_ref_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        ->orderBy('cash_incentive_refs.id','desc')
        ->get([
            'cash_incentive_refs.*',
            'exp_lc_scs.sc_or_lc',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exporter_bank_branch_id',
            'exp_lc_scs.lc_sc_value',
            'exp_lc_scs.lc_sc_date',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
            'currencies.hundreds_name',
            'bank_branches.branch_name',
            'banks.name as bank_name',
            'companies.name as company_name',
            'companies.address as company_address',
            'buyers.name as buyer_name',
            'cumulatives.replaced_lc_sc_id',
            'cumulatives.lc_sc_no as replaced_lc_sc_no',
            'cumulatives.lc_sc_value as replaced_lc_value',
            'cumulatives.lc_sc_date as replaced_lc_date',
            'cash_incentive_claims.knitting_charge_per_kg',
            'cash_incentive_claims.dyeing_charge_per_kg',
        ])
        ->map(function($rows){
            $rows->claim_sub_date=date('d-M-Y',strtotime($rows->claim_sub_date));
            $rows->lc_sc_date=($rows->lc_sc_date !== '')?date('d-M-Y',strtotime($rows->lc_sc_date)):'';
            $rows->lc_sc_value=number_format($rows->lc_sc_value,2);
            $rows->replaced_lc_date=($rows->replaced_lc_date !== '')?date('d-M-Y',strtotime($rows->replaced_lc_date)):'';
            $rows->replaced_lc_value=number_format($rows->replaced_lc_value,2);
            if($rows->sc_or_lc==1)
            {
              $rows->sc_lc='Sales Contract No: '.$rows->lc_sc_no.' Dt: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_lc='Export L/C No: '.$rows->lc_sc_no.' Dt: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }

            $lcscyarr=array();
            if($rows->replaced_lc_sc_no){
                array_push($lcscyarr,$rows->replaced_lc_sc_no);
            }
            $rows->replaced_lc_sc_no= implode(',',$lcscyarr);
            if ($rows->replaced_lc_sc_id !== null) {
                $rows->replaces_lc_sc='under S/C No: '.$rows->replaced_lc_sc_no.' Dt: '.$rows->replaced_lc_date.', Value:'.$rows->currency_symbol.' '.$rows->replaced_lc_value;
            }else if($rows->replaced_lc_sc_id == null){
                $rows->replaces_lc_sc='';
            }

            return $rows;
        })
        ->first();

        $yarnDescription=$this->itemaccount
        ->join('item_account_ratios',function($join){
            $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->join('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->join('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->join('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('compositions',function($join){
            $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->join('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->where([['itemcategories.identity','=',1]])
        ->get([
            'item_accounts.id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
           // 'itemclasses.name as itemclass_name',
            'compositions.name as composition_name',
            'item_account_ratios.ratio',
        ]);
        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
        $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
        $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
        //$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
        $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
        $yarnDropdown[$key]=/* $value['itemclass_name']." ". */$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
        }
        $usedYarn=$this->cashincentiveref
        ->selectRaw('
            cash_incentive_refs.id as cash_incentive_ref_id,
            item_accounts.id as item_account_id,
            sum(cash_incentive_yarn_btb_lcs.consumed_qty) as consumed_qty,
            sum(cash_incentive_yarn_btb_lcs.comsumed_amount) as comsumed_amount
        ')
        ->join('cash_incentive_yarn_btb_lcs', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_yarn_btb_lcs.cash_incentive_ref_id');
        })
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'cash_incentive_yarn_btb_lcs.imp_lc_id');
        })
        ->join('po_yarn_items', function($join)  {
            $join->on('po_yarn_items.id', '=', 'cash_incentive_yarn_btb_lcs.po_yarn_item_id');
        })
        ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'po_yarn_items.item_account_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        ->groupBy([
            'cash_incentive_refs.id',
            'item_accounts.id',
            
        ])
        ->get(/* [
            'cash_incentive_yarn_btb_lcs.*',
            'po_yarn_items.item_account_id',
        ] */)
        ->map(function($usedYarn) use($yarnDropdown){
            $usedYarn->item_description = $yarnDropdown[$usedYarn->item_account_id];
            if($usedYarn->consumed_qty){
                $usedYarn->con_rate=$usedYarn->comsumed_amount/$usedYarn->consumed_qty;
            }
            $usedYarn->con_rate=number_format($usedYarn->con_rate,2);
            $usedYarn->consumed_qty=number_format($usedYarn->consumed_qty,2);
            $usedYarn->comsumed_amount=number_format($usedYarn->comsumed_amount,2);
            return $usedYarn;
        });

        $incentiveyarnbtblc=$this->cashincentiveref
        ->selectRaw('
            cash_incentive_refs.id as cash_incentive_ref_id,
            cash_incentive_yarn_btb_lcs.imp_lc_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv,
            imp_lcs.lc_date,
            poYarns.lc_yarn_qty,
            poYarns.lc_yarn_amount,
            sum(cash_incentive_yarn_btb_lcs.consumed_qty) as consumed_qty,
            sum(cash_incentive_yarn_btb_lcs.comsumed_amount) as comsumed_amount,
            suppliers.name as supplier_name
        ')
        ->join('cash_incentive_yarn_btb_lcs', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_yarn_btb_lcs.cash_incentive_ref_id');
        })
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'cash_incentive_yarn_btb_lcs.imp_lc_id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            imp_lcs.id as imp_lc_id,
            sum(po_yarn_items.qty) as lc_yarn_qty,
            avg(po_yarn_items.rate) as rate,
            sum(po_yarn_items.amount) as lc_yarn_amount
            FROM imp_lcs 
            join imp_lc_pos on imp_lc_pos.imp_lc_id = imp_lcs.id and imp_lcs.menu_id=3
            join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id
            join po_yarn_items on po_yarn_items.po_yarn_id=po_yarns.id
            group by 
            imp_lcs.id
        ) poYarns"), "poYarns.imp_lc_id", "=", "cash_incentive_yarn_btb_lcs.imp_lc_id")

        // ->join('po_yarn_items', function($join)  {
        //     $join->on('po_yarn_items.id', '=', 'cash_incentive_yarn_btb_lcs.po_yarn_item_id');
        // })
        ->join('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        //->orderBy('cash_incentive_yarn_btb_lcs.id','desc')
        ->groupBy([
            'cash_incentive_refs.id',
            'cash_incentive_yarn_btb_lcs.imp_lc_id',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'imp_lcs.lc_date',
            'suppliers.name',
            'poYarns.lc_yarn_qty',
            'poYarns.lc_yarn_amount',
        ])
        ->get()
        ->map(function($incentiveyarnbtblc){
            $incentiveyarnbtblc->lc_no=$incentiveyarnbtblc->lc_no_i."".$incentiveyarnbtblc->lc_no_ii."".$incentiveyarnbtblc->lc_no_iii."".$incentiveyarnbtblc->lc_no_iv;
            return $incentiveyarnbtblc;
        });

        $totalConsumedQty=$incentiveyarnbtblc->sum('consumed_qty');
        $totalConsumedAmount=$incentiveyarnbtblc->sum('comsumed_amount');
        $totalKnitCharge=$totalConsumedQty*$rows->knitting_charge_per_kg;
        $totalDyeingCharge=$totalConsumedQty*$rows->dyeing_charge_per_kg;
        $totalFabricCost=$totalConsumedAmount+($totalKnitCharge)+($totalDyeingCharge);

        $amount=$totalConsumedAmount+$totalKnitCharge+$totalDyeingCharge;
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,$rows->hundreds_name);
        $rows->inword=$inword;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins('25', PDF_MARGIN_TOP, '20');
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->AddPage();
        $pdf->SetY(50);
        $pdf->SetFont('helvetica', '', 8);
        
        $view= \View::make('Defult.Commercial.CashIncentive.CostOfProductionPdf',['rows'=>$rows,'incentiveyarnbtblc'=>$incentiveyarnbtblc,'usedYarn'=>$usedYarn]);
        $html_content=$view->render();
        //$pdf->SetY(55);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetX(150);
        $qrc='Reference ID :'.$id.", LC/SC No: ".$rows['lc_sc_no'].", Company: ".$rows['company_name'].", Bank name: ".$rows['bank_name'].", Buyer: ".$rows['buyer_name'];
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 240, 20, 20, $barcodestyle, 'N');
        $pdf->Text(170, 260, $id);
        // $pdf->Text(172, 254, 'LC ID :'.$implc->id);

         // $pdf->SetFont('helvetica', 'N', 10);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/CostOfProductionPdf.pdf';
        //$pdf->output($filename);
        $pdf->output($filename,'I');
        exit();
    }

    // public function getCOP(){
    //     $id=request('id',0);
    //     $rows=$this->cashincentiveref
    //     ->leftJoin('exp_lc_scs',function($join){
    //         $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
    //     })
    //     ->leftJoin('companies',function($join){
    //         $join->on('companies.id','=','cash_incentive_refs.company_id');
    //     })
    //     ->leftJoin('buyers',function($join){
    //         $join->on('buyers.id','=','exp_lc_scs.buyer_id');
    //     })
    //     ->join('currencies',function($join){
    //         $join->on('currencies.id','=','exp_lc_scs.currency_id');
    //     })
    //     ->leftJoin('bank_branches',function($join){
    //         $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
    //     })
    //     ->leftJoin('banks',function($join){
    //         $join->on('banks.id','=','bank_branches.bank_id');
    //     })
    //     ->leftJoin('cash_incentive_claims', function($join)  {
    //         $join->on('cash_incentive_refs.id', '=', 'cash_incentive_claims.cash_incentive_ref_id');
    //     })
    //     ->leftJoin(\DB::raw("(
    //         SELECT 
    //             exp_lc_scs.id as replaced_lc_sc_id,
    //             exp_lc_scs.lc_sc_no, 
    //             exp_lc_scs.lc_sc_value,
    //             exp_lc_scs.lc_sc_date,
    //             exp_rep_lc_scs.exp_lc_sc_id 
    //         FROM exp_rep_lc_scs 
    //             join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   
    //         group by 
    //             exp_lc_scs.id,
    //             exp_lc_scs.lc_sc_no,
    //             exp_lc_scs.lc_sc_value,
    //             exp_lc_scs.lc_sc_date,   
    //             exp_rep_lc_scs.exp_lc_sc_id
    //             ) cumulatives"), "cumulatives.exp_lc_sc_id", "=", "cash_incentive_refs.exp_lc_sc_id")
    //     ->where([['cash_incentive_refs.id','=',$id]])
    //     ->orderBy('cash_incentive_refs.id','desc')
    //     ->get([
    //         'cash_incentive_refs.*',
    //         'exp_lc_scs.sc_or_lc',
    //         'exp_lc_scs.lc_sc_no',
    //         'exp_lc_scs.currency_id',
    //         'exp_lc_scs.exporter_bank_branch_id',
    //         'exp_lc_scs.lc_sc_value',
    //         'exp_lc_scs.lc_sc_date',
    //         'currencies.code as currency_code',
    //         'currencies.symbol as currency_symbol',
    //         'currencies.hundreds_name',
    //         'bank_branches.branch_name',
    //         'banks.name as bank_name',
    //         'companies.name as company_name',
    //         'companies.address as company_address',
    //         'buyers.name as buyer_name',
    //         'cumulatives.replaced_lc_sc_id',
    //         'cumulatives.lc_sc_no as replaced_lc_sc_no',
    //         'cumulatives.lc_sc_value as replaced_lc_value',
    //         'cumulatives.lc_sc_date as replaced_lc_date',
    //         'cash_incentive_claims.knitting_charge_per_kg',
    //         'cash_incentive_claims.dyeing_charge_per_kg',
    //     ])
    //     ->map(function($rows){
    //         $rows->claim_sub_date=date('d-M-Y',strtotime($rows->claim_sub_date));
    //         $rows->lc_sc_date=($rows->lc_sc_date !== '')?date('d-M-Y',strtotime($rows->lc_sc_date)):'';
    //         $rows->lc_sc_value=number_format($rows->lc_sc_value,2);
    //         $rows->replaced_lc_date=($rows->replaced_lc_date !== '')?date('d-M-Y',strtotime($rows->replaced_lc_date)):'';
    //         $rows->replaced_lc_value=number_format($rows->replaced_lc_value,2);
    //         if($rows->sc_or_lc==1)
    //         {
    //           $rows->sc_lc='Sales Contract no:'.$rows->lc_sc_no.' dt: '.$rows->lc_sc_date.', value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
    //         }
    //         else if($rows->sc_or_lc==2){
    //           $rows->sc_lc='Export L/C no: '.$rows->lc_sc_no.' dt: '.$rows->lc_sc_date.', value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
    //         }

    //         // if ($rows->replaced_lc_sc_id !== null) {
    //         //     $rows->replaces_lc_sc='under S/C no:'.$rows->replaced_lc_sc_no.' dt: '.$rows->replaced_lc_date.', value:'.$rows->currency_symbol.' '.$rows->replaced_lc_value;
    //         // }else if($rows->replaced_lc_sc_id == null){
    //         //     $rows->replaces_lc_sc='';
    //         // }
    //         $lcscyarr=array();
    //         if($rows->replaced_lc_sc_no){
    //             array_push($lcscyarr,$rows->replaced_lc_sc_no);
    //         }
    //         $rows->replaced_lc_sc_no= implode(',',$lcscyarr);
    //         if ($rows->replaced_lc_sc_id !== null) {
    //             $rows->replaces_lc_sc='under S/C no:'.$rows->replaced_lc_sc_no.' dt: '.$rows->replaced_lc_date.', value:'.$rows->currency_symbol.' '.$rows->replaced_lc_value;
    //         }else if($rows->replaced_lc_sc_id == null){
    //             $rows->replaces_lc_sc='';
    //         }

    //         return $rows;
    //     })
    //     ->first();

    //     $incentiveyarnbtblc=$this->cashincentiveref
    //     ->selectRaw('
    //         cash_incentive_refs.id as cash_incentive_ref_id,
    //         cash_incentive_yarn_btb_lcs.imp_lc_id,
    //         imp_lcs.lc_no_i,
    //         imp_lcs.lc_no_ii,
    //         imp_lcs.lc_no_iii,
    //         imp_lcs.lc_no_iv,
    //         imp_lcs.lc_date,
    //         sum(po_yarn_items.qty) as lc_yarn_qty,
    //         avg(po_yarn_items.rate) as rate,
    //         sum(po_yarn_items.amount) as lc_yarn_amount,
    //         sum(cash_incentive_yarn_btb_lcs.consumed_qty) as consumed_qty,
    //         sum(cash_incentive_yarn_btb_lcs.comsumed_amount) as comsumed_amount,
    //         suppliers.name as supplier_name
    //     ')
    //     ->join('cash_incentive_yarn_btb_lcs', function($join)  {
    //         $join->on('cash_incentive_refs.id', '=', 'cash_incentive_yarn_btb_lcs.cash_incentive_ref_id');
    //     })
    //     ->join('imp_lcs', function($join)  {
    //         $join->on('imp_lcs.id', '=', 'cash_incentive_yarn_btb_lcs.imp_lc_id');
    //     })
    //     ->join('po_yarn_items', function($join)  {
    //         $join->on('po_yarn_items.id', '=', 'cash_incentive_yarn_btb_lcs.po_yarn_item_id');
    //     })
    //     ->join('suppliers', function($join)  {
    //         $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
    //     })
    //     ->where([['cash_incentive_refs.id','=',$id]])
    //     //->orderBy('cash_incentive_yarn_btb_lcs.id','desc')
    //     ->groupBy([
    //         'cash_incentive_refs.id',
    //         'cash_incentive_yarn_btb_lcs.imp_lc_id',
    //         'imp_lcs.lc_no_i',
    //         'imp_lcs.lc_no_ii',
    //         'imp_lcs.lc_no_iii',
    //         'imp_lcs.lc_no_iv',
    //         'imp_lcs.lc_date',
    //         'suppliers.name',
    //     ])
    //     ->get()
    //     ->map(function($incentiveyarnbtblc){
    //         $incentiveyarnbtblc['lc_no']=$incentiveyarnbtblc->lc_no_i."".$incentiveyarnbtblc->lc_no_ii."".$incentiveyarnbtblc->lc_no_iii."".$incentiveyarnbtblc->lc_no_iv;
    //         return $incentiveyarnbtblc;
    //     });

    //     $totalConsumedQty=$incentiveyarnbtblc->sum('consumed_qty');
    //     $totalConsumedAmount=$incentiveyarnbtblc->sum('comsumed_amount');
    //     $totalKnitCharge=$totalConsumedQty*$rows->knitting_charge_per_kg;
    //     $totalDyeingCharge=$totalConsumedQty*$rows->dyeing_charge_per_kg;
    //     $totalFabricCost=$totalConsumedAmount+($totalKnitCharge)+($totalDyeingCharge);

    //     $amount=$totalConsumedAmount+$totalKnitCharge+$totalDyeingCharge;
    //     $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,$rows->hundreds_name);
    //     $rows->inword=$inword;

    //     // $cashincentiveclaim = $this->cashincentiveref
    //     //     ->selectRaw('
    //     //         cash_incentive_refs.id as cash_incentive_ref_id,
    //     //         currencies.code as currency_code,
    //     //         currencies.symbol as currency_symbol,
    //     //         cash_incentive_claims.net_wgt_exp_qty,
    //     //         cash_incentive_claims.knitting_charge_per_kg,
    //     //         cash_incentive_claims.dyeing_charge_per_kg,
    //     //         cash_incentive_refs.avg_rate
    //     //     ')
    //     //     ->join('cash_incentive_claims', function($join)  {
    //     //         $join->on('cash_incentive_refs.id', '=', 'cash_incentive_claims.cash_incentive_ref_id');
    //     //     })
    //     //     ->join('exp_lc_scs',function($join){
    //     //         $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
    //     //     })
    //     //     ->join('currencies',function($join){
    //     //         $join->on('currencies.id','=','exp_lc_scs.currency_id');
    //     //     })
    //     //     ->where([['cash_incentive_refs.id','=',$id]])
    //     //    // ->orderBy('cash_incentive_claims.id','desc')
    //     //     ->groupBy([
    //     //         'cash_incentive_refs.id',
    //     //         'currencies.code',
    //     //         'currencies.symbol',
    //     //         'cash_incentive_claims.net_wgt_exp_qty',
    //     //         'cash_incentive_claims.knitting_charge_per_kg',
    //     //         'cash_incentive_claims.dyeing_charge_per_kg',
    //     //         'cash_incentive_refs.avg_rate',
    //     //     ])
    //     //     ->get()
    //     //     ->map(function($cashincentiveclaim){
    //     //         $cashincentiveclaim->exp_date=date('d-M-Y',strtotime($cashincentiveclaim->exp_date));
    //     //         $cashincentiveclaim->bl_date=date('d-M-Y',strtotime($cashincentiveclaim->bl_date));
    //     //         $cashincentiveclaim->realized_date=date('d-M-Y',strtotime($cashincentiveclaim->realized_date));
    //     //         $net_wgt_exp_qty=$cashincentiveclaim->net_wgt_exp_qty*$cashincentiveclaim->avg_rate;
    //     //         $cashincentiveclaim->knitting_charge_per_kg=$cashincentiveclaim->knitting_charge_per_kg*$cashincentiveclaim->net_wgt_exp_qty;
    //     //         $cashincentiveclaim->dyeing_charge_per_kg=$cashincentiveclaim->dyeing_charge_per_kg*$cashincentiveclaim->net_wgt_exp_qty;
    //     //         $cashincentiveclaim->total_fabric_cost=$net_wgt_exp_qty+$cashincentiveclaim->knitting_charge_per_kg+$cashincentiveclaim->dyeing_charge_per_kg;
    //     //         $cashincentiveclaim->net_wgt=$net_wgt_exp_qty;
                
    //     //         return $cashincentiveclaim;
    //     // });

    //     //$cashincentiveclaim->total_net_wgt_qty=sum('net_wgt_exp_qty');

    //     $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    //     $pdf->SetPrintHeader(false);
    //     $pdf->SetPrintFooter(false);
    //     $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    //     $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    //     $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    //     $pdf->SetMargins('30', PDF_MARGIN_TOP, '20');
    //     $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //     $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    //     $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    //     $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    //     $pdf->AddPage();
    //     $pdf->SetY(60);
    //     $pdf->SetFont('helvetica', '', 10);
        
    //     $view= \View::make('Defult.Commercial.CashIncentive.CostOfProductionPdf',['rows'=>$rows,'incentiveyarnbtblc'=>$incentiveyarnbtblc/*,'cashincentiveclaim'=>$cashincentiveclaim*/]);
    //     $html_content=$view->render();
    //     //$pdf->SetY(55);
    //     $pdf->WriteHtml($html_content, true, false,true,false,'');
    //     $barcodestyle = array(
    //         'position' => '',
    //         'align' => 'C',
    //         'stretch' => false,
    //         'fitwidth' => true,
    //         'cellfitalign' => '',
    //         'border' => false,
    //         'hpadding' => 'auto',
    //         'vpadding' => 'auto',
    //         'fgcolor' => array(0,0,0),
    //         'bgcolor' => false, //array(255,255,255),
    //         'text' => true,
    //         'font' => 'helvetica',
    //         'fontsize' => 8,
    //         'stretchtext' => 4
    //     );
    //     $pdf->SetX(150);
    //     $qrc='Reference ID :'.$id.", LC/SC No: ".$rows['lc_sc_no'].", Company: ".$rows['company_name'].", Bank name: ".$rows['bank_name'].", Buyer: ".$rows['buyer_name'];
    //     $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 230, 20, 20, $barcodestyle, 'N');
    //     $pdf->Text(170, 250, $id);
    //     // $pdf->Text(172, 254, 'LC ID :'.$implc->id);

    //      // $pdf->SetFont('helvetica', 'N', 10);
    //     $pdf->SetFont('helvetica', '', 8);
    //     $filename = storage_path() . '/CostOfProductionPdf.pdf';
    //     //$pdf->output($filename);
    //     $pdf->output($filename,'I');
    //     exit();
    // }

    public function forwardLetter(){
        $id=request('id',0);
        $region=config('bprs.region');
        $rows=$this->cashincentiveref
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','cash_incentive_refs.company_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->leftJoin('bank_branches',function($join){
            $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
                exp_lc_scs.id as replaced_lc_sc_id,
                exp_lc_scs.lc_sc_no, 
                exp_lc_scs.lc_sc_value,
                exp_lc_scs.lc_sc_date,
                exp_rep_lc_scs.exp_lc_sc_id 
            FROM exp_rep_lc_scs 
                join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   
            group by 
                exp_lc_scs.id,
                exp_lc_scs.lc_sc_no,
                exp_lc_scs.lc_sc_value,
                exp_lc_scs.lc_sc_date,   
                exp_rep_lc_scs.exp_lc_sc_id
                ) cumulatives"), "cumulatives.exp_lc_sc_id", "=", "cash_incentive_refs.exp_lc_sc_id")
        ->where([['cash_incentive_refs.id','=',$id]])
        ->orderBy('cash_incentive_refs.id','desc')
        ->get([
            'cash_incentive_refs.*',
            'exp_lc_scs.sc_or_lc',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exporter_bank_branch_id',
            'exp_lc_scs.lc_sc_value',
            'exp_lc_scs.lc_sc_date',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'banks.name as bank_name',
            'companies.name as company_name',
            'companies.address as company_address',
            'buyers.name as buyer_name',
            'cumulatives.replaced_lc_sc_id',
            'cumulatives.lc_sc_no as replaced_lc_sc_no',
            'cumulatives.lc_sc_value as replaced_lc_value',
            'cumulatives.lc_sc_date as replaced_lc_date',
        ])
        ->map(function($rows){
            //'region' =>[1=>"Europe",5=>"America",10=>"Australia",15=>"Asia",20=>"Africa",25=>"North America",30=>"South America"]
            if ($rows->region_id==1) {
                $rows->region="European";
            }
            elseif ($rows->region_id==5) {
                $rows->region="American";
            }
            elseif ($rows->region_id==10) {
                $rows->region="Australian";
            }
            elseif ($rows->region_id==15) {
                $rows->region="Asian";
            }
            elseif ($rows->region_id==20) {
                $rows->region="African";
            }
            elseif ($rows->region_id==25) {
                $rows->region="North American";
            }
            elseif ($rows->region_id==30) {
                $rows->region="South American";
            }
            $rows->claim_sub_date=($rows->claim_sub_date !== null)?date('d-M-Y',strtotime($rows->claim_sub_date)):null;
            $rows->lc_sc_date=($rows->lc_sc_date !== '')?date('d-M-Y',strtotime($rows->lc_sc_date)):'';
            $rows->lc_sc_value=number_format($rows->lc_sc_value,2);
            $rows->replaced_lc_date=($rows->replaced_lc_date !== '')?date('d-M-Y',strtotime($rows->replaced_lc_date)):'';
            $rows->replaced_lc_value=number_format($rows->replaced_lc_value,2);
            if($rows->sc_or_lc==1)
            {
              $rows->sc_lc='Sales Contract no: '.$rows->lc_sc_no.' Dt: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_lc='Export L/C no: '.$rows->lc_sc_no.' Dt: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            $lcscyarr=array();
            if($rows->replaced_lc_sc_no){
                array_push($lcscyarr,$rows->replaced_lc_sc_no);
            }
            $rows->replaced_lc_sc_no= implode(',',$lcscyarr);
            if ($rows->replaced_lc_sc_id !== null) {
                $rows->replaces_lc_sc='under S/C no: '.$rows->replaced_lc_sc_no.' dt: '.$rows->replaced_lc_date.', value:'.$rows->currency_symbol.' '.$rows->replaced_lc_value;
            }else if($rows->replaced_lc_sc_id == null){
                $rows->replaces_lc_sc='';
            }

            return $rows;
        })
        ->first();

        $cashincentiveclaim = $this->cashincentiveref
            ->selectRaw('
                cash_incentive_refs.id as cash_incentive_ref_id,
                cash_incentive_claims.invoice_qty,
                exp_invoices.id as exp_invoice_id,
                exp_invoices.invoice_no
            ')
            ->join('cash_incentive_claims', function($join)  {
                $join->on('cash_incentive_refs.id', '=', 'cash_incentive_claims.cash_incentive_ref_id');
            })
            ->join('exp_lc_scs',function($join){
                $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
            })
            ->leftJoin('exp_doc_sub_invoices', function($join)  {
                $join->on('exp_doc_sub_invoices.id', '=', 'cash_incentive_claims.exp_doc_sub_invoice_id');
            })
            ->leftJoin('exp_doc_submissions', function($join)  {
                $join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
            })
            ->leftJoin('exp_invoices',function($join){
                $join->on('exp_invoices.exp_lc_sc_id', '=', 'exp_doc_submissions.exp_lc_sc_id');
                $join->on('exp_doc_sub_invoices.exp_invoice_id','=','exp_invoices.id');
            })
            ->where([['cash_incentive_refs.id','=',$id]])
           // ->orderBy('cash_incentive_claims.id','desc')
            ->groupBy([
                'cash_incentive_refs.id',
               'cash_incentive_claims.invoice_qty',
                'exp_invoices.id',
                'exp_invoices.invoice_no',
            ])
            ->get();
            $invoiceQty=0;
            $invArr=array();
            foreach ($cashincentiveclaim as $invoice) {
                $invoiceQty+=$invoice->invoice_qty;
                $invArr[$invoice->exp_invoice_id]=$invoice->invoice_no;
            }
 

        // $rows['master']=$claim;
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins('30', PDF_MARGIN_TOP, '20');
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();
        $pdf->SetY(45);
        $pdf->SetFont('helvetica', 'N', 12);

        $sub="Sub : Application for cash incentive against export of total: ".$invoiceQty." pcs  finished garments under invoice no: ".implode(' ,',$invArr)." against ".$rows['sc_lc'];

        $body="We are pleased to inform you that we have exported the above mentioned merchandise to ".$rows['region']." country using local yarn in our composite factory and the amount of the same export has already been realised.All relevent papers in support of the cash incentive are enclosed herewith for your kind consideration. ";

        $ttp2="Therefore, we request you to take necessary steps to have said incentive.";
        
        $view= \View::make('Defult.Commercial.CashIncentive.ForwardLetterPdf',['rows'=>$rows,'sub'=>$sub,'body'=>$body,'ttp2'=>$ttp2]);
        $html_content=$view->render();
        //$pdf->SetY(55);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetX(150);
        $qrc='Reference ID :'.$id.", LC/SC No: ".$rows['lc_sc_no'].", Company: ".$rows['company_name'].", Bank name: ".$rows['bank_name'].", Buyer: ".$rows['buyer_name'];
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 230, 20, 20, $barcodestyle, 'N');
        $pdf->Text(170, 250, $id);
             // $pdf->Text(172, 254, 'LC ID :'.$implc->id);

         // $pdf->SetFont('helvetica', 'N', 10);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/ForwardLetterPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function declareLetter(){
        $id=request('id',0);

        $rows=$this->cashincentiveref
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','cash_incentive_refs.company_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->leftJoin('bank_branches',function($join){
            $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
        })
        ->join('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
                exp_lc_scs.id as replaced_lc_sc_id,
                exp_lc_scs.lc_sc_no, 
                exp_lc_scs.lc_sc_value,
                exp_lc_scs.lc_sc_date,
                exp_rep_lc_scs.exp_lc_sc_id 
            FROM exp_rep_lc_scs 
                join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   
            group by 
                exp_lc_scs.id,
                exp_lc_scs.lc_sc_no,
                exp_lc_scs.lc_sc_value,
                exp_lc_scs.lc_sc_date,   
                exp_rep_lc_scs.exp_lc_sc_id
                ) cumulatives"), "cumulatives.exp_lc_sc_id", "=", "cash_incentive_refs.exp_lc_sc_id")
        ->where([['cash_incentive_refs.id','=',$id]])
        ->orderBy('cash_incentive_refs.id','desc')
        ->get([
            'cash_incentive_refs.*',
            'exp_lc_scs.sc_or_lc',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exporter_bank_branch_id',
            'exp_lc_scs.lc_sc_value',
            'exp_lc_scs.lc_sc_date',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'banks.name as bank_name',
            'companies.name as company_name',
            'companies.address as company_address',
            'buyers.name as buyer_name',
            'cumulatives.replaced_lc_sc_id',
            'cumulatives.lc_sc_no as replaced_lc_sc_no',
            'cumulatives.lc_sc_value as replaced_lc_value',
            'cumulatives.lc_sc_date as replaced_lc_date',
        ])
        ->map(function($rows){
            $rows->claim_sub_date=($rows->claim_sub_date !== null)?date('d-M-Y',strtotime($rows->claim_sub_date)):null;
            $rows->lc_sc_date=($rows->lc_sc_date !== '')?date('d-M-Y',strtotime($rows->lc_sc_date)):'';
            $rows->lc_sc_value=number_format($rows->lc_sc_value,2);
            $rows->replaced_lc_date=($rows->replaced_lc_date !== '')?date('d-M-Y',strtotime($rows->replaced_lc_date)):'';
            $rows->replaced_lc_value=number_format($rows->replaced_lc_value,2);
            if($rows->sc_or_lc==1)
            {
              $rows->sc_lc='Sales Contract No: '.$rows->lc_sc_no.' Date: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_lc='Export L/C No: '.$rows->lc_sc_no.' Date: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            $lcscyarr=array();
            if($rows->replaced_lc_sc_no){
                array_push($lcscyarr,$rows->replaced_lc_sc_no);
            }
            $rows->replaced_lc_sc_no= implode(',',$lcscyarr);
            if ($rows->replaced_lc_sc_id !== null) {
                $rows->replaces_lc_sc='as replacement of S/C No: '.$rows->replaced_lc_sc_no.' Date: '.$rows->replaced_lc_date.', Value: '.$rows->currency_symbol.' '.$rows->replaced_lc_value;
            }else if($rows->replaced_lc_sc_id == null){
                $rows->replaces_lc_sc='';
            }
            return $rows;
        })
        ->first();

        $cashincentiveclaim = $this->cashincentiveref
        ->selectRaw('
            cash_incentive_refs.id as cash_incentive_ref_id,
            cash_incentive_claims.invoice_qty,
            exp_invoices.id as exp_invoice_id,
            exp_invoices.invoice_no
        ')
        ->join('cash_incentive_claims', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_claims.cash_incentive_ref_id');
        })
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->leftJoin('exp_doc_sub_invoices', function($join)  {
            $join->on('exp_doc_sub_invoices.id', '=', 'cash_incentive_claims.exp_doc_sub_invoice_id');
        })
        ->leftJoin('exp_doc_submissions', function($join)  {
            $join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
        })
        ->leftJoin('exp_invoices',function($join){
            $join->on('exp_invoices.exp_lc_sc_id', '=', 'exp_doc_submissions.exp_lc_sc_id');
            $join->on('exp_doc_sub_invoices.exp_invoice_id','=','exp_invoices.id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        // ->orderBy('cash_incentive_claims.id','desc')
        ->groupBy([
            'cash_incentive_refs.id',
            'cash_incentive_claims.invoice_qty',
            'exp_invoices.id',
            'exp_invoices.invoice_no',
        ])
        ->get();

        $invoiceQty=0;
        $invArr=array();
        foreach ($cashincentiveclaim as $invoice) {
            $invoiceQty+=$invoice->invoice_qty;
            $invArr[$invoice->exp_invoice_id]=$invoice->invoice_no;
        }

        $incentiveyarnbtblc=$this->cashincentiveref
        ->selectRaw('
            cash_incentive_refs.id as cash_incentive_ref_id,
            cash_incentive_yarn_btb_lcs.imp_lc_id,
            imp_lcs.id as imp_lc_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv,
            imp_lcs.lc_date,
            imp_lcs.currency_id,
            sum(po_yarn_items.qty) as lc_yarn_qty,
            avg(po_yarn_items.rate) as rate,
            sum(po_yarn_items.amount) as lc_yarn_amount,
            suppliers.name as supplier_name,
            currencies.symbol as currency_symbol
        ')
        ->join('cash_incentive_yarn_btb_lcs', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_yarn_btb_lcs.cash_incentive_ref_id');
        })
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'cash_incentive_yarn_btb_lcs.imp_lc_id');
        })
        ->join('po_yarn_items', function($join)  {
            $join->on('po_yarn_items.id', '=', 'cash_incentive_yarn_btb_lcs.po_yarn_item_id');
        })
        ->join('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','imp_lcs.currency_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        //->orderBy('cash_incentive_yarn_btb_lcs.id','desc')
        ->groupBy([
            'cash_incentive_refs.id',
            'cash_incentive_yarn_btb_lcs.imp_lc_id',
            'imp_lcs.id',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'imp_lcs.currency_id',
            'imp_lcs.lc_date',
            'currencies.symbol',
            'imp_lcs.lc_date',
            'suppliers.name',
            //'po_yarn_items.qty as lc_yarn_qty',
        ])
        ->get()
        ->map(function($incentiveyarnbtblc){
            $incentiveyarnbtblc->lc_no=$incentiveyarnbtblc->lc_no_i."".$incentiveyarnbtblc->lc_no_ii."".$incentiveyarnbtblc->lc_no_iii."".$incentiveyarnbtblc->lc_no_iv;
            $incentiveyarnbtblc->consumed_qty=$incentiveyarnbtblc->consumed_qty;
            $incentiveyarnbtblc->comsumed_amount=$incentiveyarnbtblc->comsumed_amount;
            $incentiveyarnbtblc->lc_yarn_qty=$incentiveyarnbtblc->lc_yarn_qty;
            $incentiveyarnbtblc->lc_yarn_amount=number_format($incentiveyarnbtblc->lc_yarn_amount,2);
            $incentiveyarnbtblc->lc_date=($incentiveyarnbtblc->lc_date !== null)?date('d-M-Y',strtotime($incentiveyarnbtblc->lc_date)):null;
            return $incentiveyarnbtblc;
        });

        //$btblcQty=0;
        $yarnArr=array();
        foreach ($incentiveyarnbtblc as $btblc) {
            //$btblcQty+=$btblc->lc_yarn_qty;
            $yarnArr[$btblc->imp_lc_id]="LC No: ".$btblc->lc_no.", Date: ".$btblc->lc_date.", Value: ".$btblc->currency_symbol."".$btblc->lc_yarn_amount.", Supplier: ".$btblc->supplier_name;
        }
 

        // $rows['master']=$claim;
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();
        $pdf->SetY(45);
        $pdf->SetFont('helvetica', '', 10);

        $body="We hereby declare that we have purchased locally manufactured yarn through BTB ".implode('; ',$yarnArr)." under ".$rows->sc_lc." ".$rows->replaces_lc_sc." and produced ".number_format($invoiceQty,0)." Pcs finished garments in our composite unit using said yarn.";

        $view= \View::make('Defult.Commercial.CashIncentive.DeclarationLetterPdf',['rows'=>$rows,'body'=>$body]);
        $html_content=$view->render();
        //$pdf->SetY(55);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
            $barcodestyle = array(
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0,0,0),
                'bgcolor' => false, //array(255,255,255),
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 8,
                'stretchtext' => 4
            );
            $pdf->SetX(140);
            $qrc='Reference ID :'.$id.", LC/SC No: ".$rows['lc_sc_no'].", Company: ".$rows['company_name'].", Bank name: ".$rows['bank_name'].", Buyer: ".$rows['buyer_name'];
            $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 220, 30, 30, $barcodestyle, 'N');
            $pdf->Text(170, 250, $id);
             // $pdf->Text(172, 254, 'LC ID :'.$implc->id);

         // $pdf->SetFont('helvetica', 'N', 10);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/DeclarationLetterPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function getNetWgt(){
        $id=request('id',0);

        $rows=$this->cashincentiveref
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        ->orderBy('cash_incentive_refs.id','desc')
        ->get([
            'cash_incentive_refs.*',
            'exp_lc_scs.sc_or_lc',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.file_no',
        ])
        ->map(function($rows){
            if($rows->sc_or_lc==1)
            {
              $rows->sc_lc='Sales Contract No: '.$rows->lc_sc_no.' (File# '.$rows->file_no.')'; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_lc='Export L/C No: '.$rows->lc_sc_no.' (File# '.$rows->file_no.')';
            }
            return $rows;
        })
        ->first();

        //dd($rows);die;

        $incentiveclaim=$this->cashincentiveref
        ->selectRaw('
            cash_incentive_refs.id as cash_incentive_ref_id,
            cash_incentive_claims.invoice_no,
            cash_incentive_claims.net_wgt_exp_qty,
            cash_incentive_claims.claim,
            countries.code as country_name
        ')
        ->join('cash_incentive_claims', function($join)  {
            $join->on('cash_incentive_claims.cash_incentive_ref_id', '=','cash_incentive_refs.id');
        })
        ->join('exp_doc_sub_invoices', function($join)  {
            $join->on('exp_doc_sub_invoices.id', '=', 'cash_incentive_claims.exp_doc_sub_invoice_id');
        })
        ->join('exp_doc_submissions', function($join)  {
            $join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
        })
        ->join('exp_invoices',function($join){
            $join->on('exp_invoices.exp_lc_sc_id', '=', 'exp_doc_submissions.exp_lc_sc_id');
            $join->on('exp_doc_sub_invoices.exp_invoice_id','=','exp_invoices.id');
        })
        ->leftJoin('countries',function($join){
            $join->on('countries.id','=','exp_invoices.country_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        //->orderBy('cash_incentive_claims.id','desc')
        ->get();


        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();
        $pdf->SetY(45);
        $pdf->SetFont('helvetica', '', 9);

        $view= \View::make('Defult.Commercial.CashIncentive.NetWgtSummaryPdf',['rows'=>$rows,'incentiveclaim'=>$incentiveclaim]);
        $html_content=$view->render();
        //$pdf->SetY(55);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetX(150);
        $qrc='Reference ID :'.$id.", LC/SC No: ".$rows['lc_sc_no'].", Company: ".$rows['company_name'].", Bank name: ".$rows['bank_name'].", Buyer: ".$rows['buyer_name'];
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 230, 20, 20, $barcodestyle, 'N');
        $pdf->Text(170, 250, $id);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/DeclarationLetterPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    
    public function BTBcertificate(){
        $id=request('id',0);
        $rows=$this->cashincentiveref
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->leftJoin('bank_branches',function($join){
            $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
        })
        ->join('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        ->orderBy('cash_incentive_refs.id','desc')
        ->get([
            'cash_incentive_refs.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.file_no',
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exporter_bank_branch_id',
            'exp_lc_scs.lc_sc_value',
            'exp_lc_scs.lc_sc_date',
            'exp_lc_scs.sc_or_lc',
            'companies.name as company_name',
            'companies.address as company_address',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'banks.name as bank_name',
            
        ])
        ->map(function($rows){
            $rows->lc_sc_date=($rows->lc_sc_date)?date('d-M-Y',strtotime($rows->lc_sc_date)):'';
            $rows->lc_sc_value=number_format($rows->lc_sc_value,2);
            if($rows->sc_or_lc==1)
            {
              $rows->sc_lc='Sales Contract No: '.$rows->lc_sc_no.' Dt: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_lc='Export L/C No: '.$rows->lc_sc_no.' Dt: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            return $rows;
        })
        ->first();

        $explcsc=collect(\DB::select("
            select 
            exp_lc_scs.id,
            exp_lc_scs.file_no,
            exp_lc_scs.lc_sc_nature_id,
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.sc_or_lc,
            exp_lc_scs.lc_sc_date,
            exp_lc_scs.lc_sc_value,
            currencies.code as currency_code,
            currencies.symbol as currency_symbol,
            min(exp_rep_lc_scs.replaced_lc_sc_id) as replaced_lc_sc_id
            FROM exp_lc_scs 
            left join buyers on buyers.id=exp_lc_scs.buyer_id 
            left join exp_rep_lc_scs on exp_rep_lc_scs.exp_lc_sc_id=exp_lc_scs.id 
            left join currencies on currencies.id=exp_lc_scs.currency_id
            where exp_lc_scs.file_no='".$rows->file_no."'
            group by
            exp_lc_scs.id,
            exp_lc_scs.file_no,
            exp_lc_scs.lc_sc_nature_id,
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.sc_or_lc,
            exp_lc_scs.lc_sc_date,
            exp_lc_scs.lc_sc_value,
            currencies.code,
            currencies.symbol
            order by exp_lc_scs.id 
        "))
        ->map(function($explcsc){
            $explcsc->lc_sc_date=($explcsc->lc_sc_date)?date('d-M-Y',strtotime($explcsc->lc_sc_date)):'';
            $explcsc->replaced_lc_sc_value=$explcsc->lc_sc_value;
            $explcsc->lc_sc_value=number_format($explcsc->lc_sc_value,2);
            if($explcsc->sc_or_lc==1)
            {
              $explcsc->sc_lc='Sales Contract No: '.$explcsc->lc_sc_no.' Dt: '.$explcsc->lc_sc_date.', Value:'.$explcsc->currency_symbol.' '.$explcsc->lc_sc_value; 
            }
            else if($explcsc->sc_or_lc==2){
              $explcsc->sc_lc='Export L/C No: '.$explcsc->lc_sc_no.' Dt: '.$explcsc->lc_sc_date.', Value:'.$explcsc->currency_symbol.' '.$explcsc->lc_sc_value; 
            }
            
            return $explcsc;
        });
        //dd($replaced);die;
        $ReplaceableSalesContract = $explcsc->filter(function ($value) {
            if(($value->lc_sc_nature_id==1 || $value->lc_sc_nature_id==2 || $value->lc_sc_nature_id==3) && !$value->replaced_lc_sc_id){
                return $value;
            }
        })->values();

         $Replaced = $explcsc->filter(function ($value) {
            if($value->replaced_lc_sc_id){
                return $value;
            }
        })->values();

       //dd($ReplaceableSalesContract);die;

        $menu=[2=>"Accessories",3=>"Yarn"];
        $btblc= collect(\DB::select("
            select 
            imp_lcs.id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv,
            imp_lcs.lc_date,
            imp_lcs.menu_id,
            case  when
            imp_lcs.menu_id=2
            then 'Accessories'
            when 
            imp_lcs.menu_id=3
            then 'Yarns'
            
            else null
            end as menu_name, 
            sum(imp_backed_exp_lc_scs.amount) as lc_amount
                FROM 
                imp_backed_exp_lc_scs 
                left join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
                left join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
                left join bank_branches on bank_branches.id=imp_lcs.issuing_bank_branch_id
                left join banks on banks.id=bank_branches.bank_id
                left join suppliers on suppliers.id=imp_lcs.supplier_id 
                left join companies on companies.id=imp_lcs.company_id
                where 
                exp_lc_scs.file_no='".$rows->file_no."' 
                and imp_lcs.menu_id in (3,2)
                and imp_lcs.lc_no_iii = '04'
                group by 
                imp_lcs.id,
                imp_lcs.lc_no_i,
                imp_lcs.lc_no_ii,
                imp_lcs.lc_no_iii,
                imp_lcs.lc_no_iv,
                imp_lcs.lc_date,
                imp_lcs.menu_id 
                order by imp_lcs.menu_id
        "))
        ->map(function($btblc) use($menu){
            $btblc->lc_date=($btblc->lc_date !== null)?date("Y-m-d",strtotime($btblc->lc_date)):'--';
            $btblc->lc_no=$btblc->lc_no_i." ".$btblc->lc_no_ii." ".$btblc->lc_no_iii." ".$btblc->lc_no_iv;
            $btblc->menu_id=isset($menu[$btblc->menu_id])?$menu[$btblc->menu_id]:'--';
            return $btblc;
        });
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(25, '60', PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();
        $pdf->SetY(70);
        $pdf->SetFont('helvetica', '', 9);

        $view= \View::make('Defult.Commercial.CashIncentive.BTBCertificatePdf',['rows'=>$rows,/* 'replaced'=>$replaced, */'btblc'=>$btblc,'ReplaceableSalesContract'=>$ReplaceableSalesContract,'Replaced'=>$Replaced]);
        $html_content=$view->render();
        $pdf->WriteHtml($html_content, true, false,true,false,'');

        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/BTBCertificatePdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function underTaking(){
        $id=request('id',0);
        $rows=$this->cashincentiveref
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','cash_incentive_refs.exp_lc_sc_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->leftJoin('bank_branches',function($join){
            $join->on('bank_branches.id','=','exp_lc_scs.exporter_bank_branch_id');
        })
        ->join('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','cash_incentive_refs.company_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        ->orderBy('cash_incentive_refs.id','desc')
        ->get([
            'cash_incentive_refs.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.file_no',
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exporter_bank_branch_id',
            'exp_lc_scs.lc_sc_value',
            'exp_lc_scs.lc_sc_date',
            'exp_lc_scs.sc_or_lc',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'banks.name as bank_name',
            'companies.name as company_name',
            'companies.address as company_address',
        ])
        ->map(function($rows){
            $rows->lc_sc_date=($rows->lc_sc_date)?date('d-M-Y',strtotime($rows->lc_sc_date)):'';
            $rows->lc_sc_value=number_format($rows->lc_sc_value,2);
            if($rows->sc_or_lc==1)
            {
              $rows->sc_lc='Sales Contract No: '.$rows->lc_sc_no.' Dt: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_lc='Export L/C No: '.$rows->lc_sc_no.' Dt: '.$rows->lc_sc_date.', Value:'.$rows->currency_symbol.' '.$rows->lc_sc_value; 
            }
            return $rows;
        })
        ->first();

        $explcsc=collect(\DB::select("
            select 
            exp_lc_scs.id,
            exp_lc_scs.file_no,
            exp_lc_scs.lc_sc_nature_id,
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.sc_or_lc,
            exp_lc_scs.lc_sc_date,
            exp_lc_scs.lc_sc_value,
            currencies.code as currency_code,
            currencies.symbol as currency_symbol,
            min(exp_rep_lc_scs.replaced_lc_sc_id) as replaced_lc_sc_id
            FROM exp_lc_scs 
            left join buyers on buyers.id=exp_lc_scs.buyer_id 
            left join exp_rep_lc_scs on exp_rep_lc_scs.exp_lc_sc_id=exp_lc_scs.id 
            left join currencies on currencies.id=exp_lc_scs.currency_id
            where exp_lc_scs.file_no='".$rows->file_no."'
            group by
            exp_lc_scs.id,
            exp_lc_scs.file_no,
            exp_lc_scs.lc_sc_nature_id,
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.sc_or_lc,
            exp_lc_scs.lc_sc_date,
            exp_lc_scs.lc_sc_value,
            currencies.code,
            currencies.symbol
            order by exp_lc_scs.id 
        "))
        ->map(function($explcsc){
            $explcsc->lc_sc_date=($explcsc->lc_sc_date)?date('d-M-Y',strtotime($explcsc->lc_sc_date)):'';
            $explcsc->replaced_lc_sc_value=$explcsc->lc_sc_value;
            $explcsc->lc_sc_value=number_format($explcsc->lc_sc_value,2);
            $explcsc->rep_sc_lc=$explcsc->lc_sc_no.', Dt:'.$explcsc->lc_sc_date.', Value:'.$explcsc->currency_symbol.''.$explcsc->lc_sc_value; 
            return $explcsc;
        });
        //dd($replaced);die;
        $ReplaceableScLc = $explcsc->filter(function ($value) {
            if(($value->lc_sc_nature_id==1 || $value->lc_sc_nature_id==2 || $value->lc_sc_nature_id==3) && !$value->replaced_lc_sc_id){
                return $value;
            }
        })->values();

        $relatedContractArr=[];
        foreach($ReplaceableScLc as $data){
            $relatedContractArr[$data->id]=$data->rep_sc_lc;
        }
        $ReplaceableSalesContract=implode('; ',$relatedContractArr);

        $incentiveclaim=$this->cashincentiveref
        ->selectRaw('
            cash_incentive_refs.id as cash_incentive_ref_id,
            max(countries.name) as country_name,
            sum(exp_invoices.net_inv_value) as invoice_value
        ')
        ->join('cash_incentive_claims', function($join)  {
            $join->on('cash_incentive_claims.cash_incentive_ref_id', '=','cash_incentive_refs.id');
        })
        ->join('exp_doc_sub_invoices', function($join)  {
            $join->on('exp_doc_sub_invoices.id', '=', 'cash_incentive_claims.exp_doc_sub_invoice_id');
        })
        ->join('exp_doc_submissions', function($join)  {
            $join->on('exp_doc_sub_invoices.exp_doc_submission_id','=','exp_doc_submissions.id');
        })
        ->join('exp_invoices',function($join){
            $join->on('exp_invoices.exp_lc_sc_id', '=', 'exp_doc_submissions.exp_lc_sc_id');
            $join->on('exp_doc_sub_invoices.exp_invoice_id','=','exp_invoices.id');
        })
        ->leftJoin('countries',function($join){
            $join->on('countries.id','=','exp_invoices.country_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        ->groupBy(['cash_incentive_refs.id'])
        ->get()
        ->first();

        $incentiveyarnbtblc=$this->cashincentiveref
        ->selectRaw('
            cash_incentive_refs.id as cash_incentive_ref_id,
            cash_incentive_yarn_btb_lcs.imp_lc_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv,
            imp_lcs.lc_date,
            poYarns.lc_yarn_qty,
            poYarns.rate,
            poYarns.lc_yarn_amount,
            suppliers.name as supplier_name
        ')
        ->join('cash_incentive_yarn_btb_lcs', function($join)  {
            $join->on('cash_incentive_refs.id', '=', 'cash_incentive_yarn_btb_lcs.cash_incentive_ref_id');
        })
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'cash_incentive_yarn_btb_lcs.imp_lc_id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            imp_lcs.id as imp_lc_id,
            sum(po_yarn_items.qty) as lc_yarn_qty,
            avg(po_yarn_items.rate) as rate,
            sum(po_yarn_items.amount) as lc_yarn_amount
            FROM imp_lcs 
            join imp_lc_pos on imp_lc_pos.imp_lc_id = imp_lcs.id and imp_lcs.menu_id=3
            join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id
            join po_yarn_items on po_yarn_items.po_yarn_id=po_yarns.id
            group by 
            imp_lcs.id
        ) poYarns"), "poYarns.imp_lc_id", "=", "cash_incentive_yarn_btb_lcs.imp_lc_id")
        ->join('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
        ->where([['cash_incentive_refs.id','=',$id]])
        ->groupBy([
            'cash_incentive_refs.id',
            'cash_incentive_yarn_btb_lcs.imp_lc_id',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'imp_lcs.lc_date',
            'suppliers.name',
            'poYarns.lc_yarn_qty',
            'poYarns.rate',
            'poYarns.lc_yarn_amount',
        ])
        ->get()
        ->map(function($incentiveyarnbtblc){
            $incentiveyarnbtblc->lc_no=$incentiveyarnbtblc->lc_no_i."".$incentiveyarnbtblc->lc_no_ii."".$incentiveyarnbtblc->lc_no_iii."".$incentiveyarnbtblc->lc_no_iv;
            $incentiveyarnbtblc->lc_date=date('d-M-Y',strtotime($incentiveyarnbtblc->lc_date));
            return $incentiveyarnbtblc;
        });

        $rows->replacable_contract_no=$ReplaceableSalesContract;
        $rows->country_name=$incentiveclaim->country_name;
        $rows->invoice_value=$incentiveclaim->invoice_value;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, '100', PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();
        $pdf->SetY(100);
        $pdf->SetFont('helvetica', '', 9);

        $view= \View::make('Defult.Commercial.CashIncentive.UndertakingPdf',['rows'=>$rows,'incentiveyarnbtblc'=>$incentiveyarnbtblc]);
        $html_content=$view->render();
        $pdf->WriteHtml($html_content, true, false,true,false,'');

        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/UndertakingPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }
}
