<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpScOrderRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScPiRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;

use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Commercial\Export\ExpPiRepository;
use App\Repositories\Contracts\Commercial\Export\ExpPiOrderRepository;

use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;

use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpLcScRequest;

class ExpLcScController extends Controller {

    private $explcsc;
    private $currency;
    private $buyer;
    private $supplier;
    private $bank;
    private $company;
    private $exppiorder;
    private $buyerbranch;

    private $budgetfabric;
    private $budget;
    private $gmtspart;
    private $autoyarn;
    private $colorrange;
    private $bankbranch;

    public function __construct(ExpLcScRepository $explcsc,CurrencyRepository $currency,BuyerRepository $buyer,SupplierRepository $supplier,BankRepository $bank,CompanyRepository $company,ExpScOrderRepository $expscorder,ItemAccountRepository $itemaccount, SalesOrderRepository $salesorder, StyleRepository $style, JobRepository $job,ExpPiRepository $exppi, ExpPiOrderRepository $exppiorder,ExpLcScPiRepository $lcscpi, BuyerBranchRepository $buyerbranch, 
    BudgetFabricRepository $budgetfabric,BudgetRepository $budget,GmtspartRepository $gmtspart,AutoyarnRepository $autoyarn,ColorrangeRepository $colorrange, BankBranchRepository $bankbranch) {
        $this->explcsc = $explcsc;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
        $this->bank = $bank;
        $this->bankbranch = $bankbranch;
        $this->company = $company;
        $this->buyerbranch = $buyerbranch;
        $this->expscorder = $expscorder;
        $this->itemaccount = $itemaccount;
        $this->salesorder = $salesorder;
        $this->job = $job;
        $this->style = $style;
        $this->exppi = $exppi;
        $this->exppiorder = $exppiorder;
        $this->lcscpi = $lcscpi;

        $this->budgetfabric = $budgetfabric;
        $this->budget = $budget;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->colorrange = $colorrange;


        $this->middleware('auth');
        $this->middleware('permission:view.explcscs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.explcscs', ['only' => ['store']]);
        $this->middleware('permission:edit.explcscs',   ['only' => ['update']]);
        $this->middleware('permission:delete.explcscs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
        
        $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'code','id'),'-Select-','');
        $contractNature = array_prepend(config('bprs.contractNature'), '-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        $exportingItem = array_prepend(config('bprs.exportingItem'), '-Select-','');
        $deliveryMode = array_prepend(config('bprs.deliveryMode'), '-Select-','');
        $consignee=array_prepend(array_pluck($this->buyer->consignee(),'name','id'),'','');
        $notifyingParties=array_prepend(array_pluck($this->buyer->notifyingParties(),'name','id'),'','');
        $forwardingAgents=array_prepend(array_pluck($this->supplier->forwardingAgents(),'name','id'),'','');
        $shippingLines=array_prepend(array_pluck($this->supplier->shippingLines(),'name','id'),'','');

        $rows=$this->explcsc->where([['sc_or_lc','=',1]])
        ->orderBy('exp_lc_scs.id','desc')
        ->get(['exp_lc_scs.*'])
        ->map(function($rows) use($company,$buyer,$bankbranch,$contractNature,$currency,$incoterm,$payterm,$notifyingParties,$consignee,$exportingItem){
            $rows->beneficiary=$company[$rows->beneficiary_id];//combo
            $rows->buyer=$buyer[$rows->buyer_id];
            $rows->exporter_bank_branch=isset($bankbranch[$rows->exporter_bank_branch_id])?$bankbranch[$rows->exporter_bank_branch_id]:'';
            $rows->lc_sc_date=date('Y-m-d',strtotime($rows->lc_sc_date));
            $rows->last_delivery_date=date('Y-m-d',strtotime($rows->last_delivery_date));
            $rows->lc_sc_nature_id=isset($contractNature[$rows->lc_sc_nature_id])?$contractNature[$rows->lc_sc_nature_id]:'';
            $rows->currency=isset($currency[$rows->currency_id])?$currency[$rows->currency_id]:'';
            $rows->incoterm=isset($incoterm[$rows->incoterm_id])?$incoterm[$rows->incoterm_id]:'';
            $rows->pay_term=isset($payterm[$rows->pay_term_id])?$payterm[$rows->pay_term_id]:'';
            $rows->notifying_party=isset($notifyingParties[$rows->notifying_party_id])?$notifyingParties[$rows->notifying_party_id]:'';
            $rows->consignee=isset($consignee[$rows->consignee_id])?$consignee[$rows->consignee_id]:'';
            $rows->exporting_item=isset($exportingItem[$rows->exporting_item_id])?$exportingItem[$rows->exporting_item_id]:'';
            $rows->lc_sc_value=number_format($rows->lc_sc_value,2);
            return $rows;
        });
        
       
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
        $bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-','');
        $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        $exportingItem = array_prepend(config('bprs.exportingItem'), '-Select-','');
        $deliveryMode = array_prepend(config('bprs.deliveryMode'), '-Select-','');
        $contractNature = array_prepend(config('bprs.contractNature'), '-Select-','');
        $consignee=array_prepend(array_pluck($this->buyer->consignee(),'name','id'),'','');
        $notifyingParties=array_prepend(array_pluck($this->buyer->notifyingParties(),'name','id'),'','');

        $forwardingAgents=array_prepend(array_pluck($this->supplier->forwardingAgents(),'name','id'),'','');
        $shippingLines=array_prepend(array_pluck($this->supplier->shippingLines(),'name','id'),'','');
        

        return Template::LoadView('Commercial.Export.ExpLcSc',['company'=>$company,'currency'=>$currency,'buyer'=>$buyer,'supplier'=>$supplier,'bank'=>$bank,'payterm'=>$payterm,'incoterm'=>$incoterm,'exportingItem'=>$exportingItem,'deliveryMode'=>$deliveryMode,'contractNature'=>$contractNature,'consignee'=>$consignee,'notifyingParties'=>$notifyingParties,'forwardingAgents'=>$forwardingAgents,'shippingLines'=>$shippingLines,'yesno'=>$yesno,'bankbranch'=>$bankbranch]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpLcScRequest $request) {
        $request->request->add(['sc_or_lc' =>1]);
		$explcsc=$this->explcsc->create($request->except(['id']));
		if($explcsc){
			return response()->json(array('success' => true,'id' =>  $explcsc->id,'message' => 'Save Successfully'),200);
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
       $explcsc = $this->explcsc->find($id);
	   $row ['fromData'] = $explcsc;
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
    public function update(ExpLcScRequest $request, $id) {
        $expprecreditlcsc=$this->explcsc
        ->join('exp_pre_credit_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_pre_credit_lc_scs.exp_lc_sc_id');
        })
        ->where([['exp_lc_scs.id','=',$id]])
        ->get()->first();
        if ($expprecreditlcsc) {
            $explcsc=$this->explcsc->update($id,$request->except(['id','exporter_bank_branch_id']));
        }
        
        $explcsc=$this->explcsc->update($id,$request->except(['id']));
		if($explcsc){
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
        if($this->explcsc->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getPdf () {
        $id=request('id',0);

        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $contractNature = array_prepend(config('bprs.contractNature'), '','');
        $consignee=array_prepend(array_pluck($this->buyer->consignee(),'name','id'),'','');
        $notifyingParties=array_prepend(array_pluck($this->buyer->notifyingParties(),'name','id'),'','');
        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
        $bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'','');
        $payterm = array_prepend(config('bprs.payterm'), '','');
        

        $explcscs=array();
        $rows=$this->explcsc->where([['id','=',$id]])->get();
        foreach($rows as $row){
            $explcsc['id']=$row->id;
            $explcsc['lc_sc_no']=$row->lc_sc_no;
            $explcsc['beneficiary_id']=$company[$row->beneficiary_id];
            $explcsc['company_id']=$row->beneficiary_id;
            $explcsc['buyer']=$buyer[$row->buyer_id];
            $explcsc['buyer_id']=$row->buyer_id;
            $explcsc['lc_sc_date']=date('Y-m-d',strtotime($row->lc_sc_date));
            $explcsc['lc_sc_nature']=$contractNature[$row->lc_sc_nature_id];
            $explcsc['consignee_id']=$consignee[$row->consignee_id];
            $explcsc['notifying_party_id']=$notifyingParties[$row->notifying_party_id];
            $explcsc['buyers_bank']=$row->buyers_bank;
            $explcsc['lc_sc_value']=$row->lc_sc_value;
            $explcsc['currency']=$currency[$row->currency_id];
            $explcsc['pay_term_id']=$payterm[$row->pay_term_id];
            $explcsc['exporter_bank_branch_id']=$bankbranch[$row->exporter_bank_branch_id];
            $explcsc['bank_branch_id']=$row->exporter_bank_branch_id;
            //array_push($explcscs,$explcsc);
        }
        //echo json_encode($explcscs);
        $orders=$this->salesorder
        ->selectRaw('
            exp_pi_orders.id,
            sales_orders.id as sales_order_id,
            styles.id as style_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            jobs.company_id,
            companies.name as company_id,
            styles.style_ref,
            jobs.job_no,
            uoms.code as uom_name,
            item_accounts.id as item_account_id,
            item_accounts.item_description,
            exp_pi_orders.qty,
            exp_pi_orders.rate,
            exp_pi_orders.amount
        ')
       ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
       ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
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
        ->join('uoms', function($join)  {
            $join->on('styles.uom_id', '=', 'uoms.id');
        })
        ->join('exp_pi_orders', function($join)  {
            $join->on('exp_pi_orders.sales_order_id', '=', 'sales_orders.id');
        })
        ->join('exp_pis', function($join)  {
            $join->on('exp_pis.id', '=', 'exp_pi_orders.exp_pi_id');
        })
        ->join('exp_lc_sc_pis', function($join)  {
            $join->on('exp_lc_sc_pis.exp_pi_id', '=', 'exp_pis.id');
        })
        /*->join('exp_lc_scs', function($join)  {
            $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
        })*/
        ->where([['exp_lc_sc_pis.exp_lc_sc_id','=', $id]])
        ->groupBy([
            'exp_pi_orders.id',
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'styles.id',
            'sales_orders.ship_date',
            'jobs.company_id',
            'companies.name',
            'styles.style_ref',
            'jobs.job_no',
            'uoms.code',
            'item_accounts.id',
            'item_accounts.item_description',
            'exp_pi_orders.qty',
            'exp_pi_orders.rate',
            'exp_pi_orders.amount',
       ])
       ->get();
       $lcsc=array();
       $rows=array();
       foreach($orders as $order){
        $lcsc[$order->id]['id']=$order->id;
        $lcsc[$order->id]['sale_order_no']=$order->sale_order_no;
        $lcsc[$order->id]['company_id']=$order->company_id;
        $lcsc[$order->id]['ship_date']=$order->ship_date;
        $lcsc[$order->id]['sales_order_id']=$order->sales_order_id;
        $lcsc[$order->id]['style_ref']=$order->style_ref;
        $lcsc[$order->id]['job_no']=$order->job_no;
        $lcsc[$order->id]['item_description'][$order->item_account_id]=$order->item_description;
        $lcsc[$order->id]['qty']=$order->qty;
        $lcsc[$order->id]['amount']=$order->amount;  
        
       }

        $datas=array();
        foreach($lcsc as $orderid=>$value){
            $value['item_description']=implode(',',$value['item_description']);
            $value['rate']=number_format($value['amount']/$value['qty'],4);
            array_push($datas, $value);
        }


        $company=$this->company->where([['id','=',  $explcsc['company_id']]])->get()->first();
        $buyerbranch=$this->buyerbranch->where([['buyer_id','=', $explcsc['buyer_id']]])->get()->first();
        $bankbranch=$this->bankbranch->where([['id','=', $explcsc['bank_branch_id']]])->get()->first();
       

        $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $header=['logo'=>$company->logo,'address'=>$company->address,'title'=>'SALES CONTRACT'];
        $pdf->setCustomHeader($header);
        $pdf->SetPrintHeader(true);
        $pdf->SetPrintFooter(false);
	    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
       
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.Export.ExpLcScPdf',['explcsc'=>$explcsc,'orders'=>$orders,'company'=>$company,'buyerbranch'=>$buyerbranch,'bankbranch'=>$bankbranch]);
        $html_content=$view->render();
        $pdf->SetY(23);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/ExpLcScPdf.pdf';
        $pdf->output($filename,'I');
        exit();
        
    }

    public function getScLienLetter()
    {
        $id=request('id',0);

        $rows=$this->explcsc
        ->leftJoin('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->where([['exp_lc_scs.id','=',$id]])
        ->where([['exp_lc_scs.sc_or_lc','=',1]])
        ->get([            
            'exp_lc_scs.*',
            'buyers.name as buyer_name',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'companies.name as beneficiary_id',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
        ])
        ->map(function($rows){
            $rows->sc_or_lc_name='Sales Contract';
            $rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
            return $rows;
        })
        ->first();

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
        ->where([['exp_rep_lc_scs.exp_lc_sc_id','=',$id]])
        ->get();

        $replaceArr=[];
        foreach ($replcsc as $date) {
            $replaceArr[$date->exp_lc_sc_id][]=$date->lc_sc_no.", dt:".date('d-M-Y',strtotime($date->lc_sc_date)).", Value:".$date->currency_symbol." ".$date->lc_sc_value;
        }

        $replacedScLc=[];
        foreach($replaceArr as $key=>$val){
            $replacedScLc[$key]=implode('; ',$val);
        }
        
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
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'N', 10);
        
        $sub="Sub : Lien of Sales Contract no: ".$rows['lc_sc_no']." Date :".$rows['lc_sc_date']." for ".$rows['currency_code']." ".$rows['currency_symbol'] .$rows['lc_sc_value'];

        $body="Referring to Sales Contract number as mentioned in subject line, we have, hereby, submitted said Sales Contract received   from  ".$rows['buyer_name']." as lien to have working capital finance.";

        //$ttp1="";

        $ttp2="Therefore we are requested to kindly acknowledge upon receipt the above-mentioned Sales Contract and take necessary steps accordingly.";
        $view= \View::make('Defult.Commercial.Export.ExpLcScLienLetterPdf',['rows'=>$rows,'sub'=>$sub,'body'=>$body,/* 'ttp1'=>$ttp1, */'ttp2'=>$ttp2,'replacedScLc'=>$replacedScLc]);
        $html_content=$view->render();
        $pdf->SetY(40);
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
        //$qrc=$rows->bank_name.', VALUE USD '.number_format($rows->total_invoice_net_inv_value,2).", ".$rows->buyer_id;

        $qrc =  'Contract value:'.$rows['currency_symbol'].' '.number_format($rows['lc_sc_value'],2).
                ',Contract No:'.$rows['lc_sc_no'].',Lien Date :'.$rows['lien_date'].',Lien Bank:'.$rows['bank_name'];
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 220, 40, 40, $barcodestyle, 'N');
        $pdf->Text(170, 244, 'File No:'.$rows['file_no']);
        $pdf->Text(170, 247, 'Sub ID :'.$id);

        $pdf->SetFont('helvetica', 'N', 10);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/ExpLcScLienLetterPdf.pdf';
        $pdf->output($filename);
    }

    public function getScAmendmentLetter()
    {
        $id=request('id',0);

        $rows=$this->explcsc
        ->leftJoin('exp_lc_sc_revises', function($join){
            $join->on('exp_lc_sc_revises.exp_lc_sc_id', '=', 'exp_lc_scs.id');
        })
        ->leftJoin('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->where([['exp_lc_scs.id','=',$id]])
        ->where([['exp_lc_scs.sc_or_lc','=',1]])
        ->get([            
            'exp_lc_scs.*',
            'exp_lc_sc_revises.last_amendment_date',
            'exp_lc_sc_revises.remarks',
            'buyers.name as buyer_name',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'companies.name as beneficiary_id',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
        ])
        ->map(function($rows){
            $rows->sc_or_lc_name='Sales Contract';
            $rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
            $rows->last_amendment_date=date('d-M-Y',strtotime($rows->last_amendment_date));
            return $rows;
        })
        ->first();

        
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
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'N', 10);
        
        $sub="Sub : Amendment of ".$rows['remarks']." of Sales Contract no: ".$rows['lc_sc_no']." Date :".$rows['lc_sc_date']." for ".$rows['currency_code']." ".$rows['currency_symbol'] .$rows['lc_sc_value'];

        $body="Referring to Sales Contract number as mentioned in subject line, we have, hereby, submitted said Sales Contract received   from  ".$rows['buyer_name']." as amendment of existing lien to have working capital finance.";

        //$ttp1="";

        $ttp2="Therefore we are requested to kindly acknowledge upon receipt the above-mentioned Sales Contract and take necessary steps accordingly.";
        $view= \View::make('Defult.Commercial.Export.ExpLcScAmendmentLetterPdf',['rows'=>$rows,'sub'=>$sub,'body'=>$body,/* 'ttp1'=>$ttp1, */'ttp2'=>$ttp2]);
        $html_content=$view->render();
        $pdf->SetY(40);
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
        //$qrc=$rows->bank_name.', VALUE USD '.number_format($rows->total_invoice_net_inv_value,2).", ".$rows->buyer_id;

        $qrc =  'Contract value:'.$rows['currency_symbol'].' '.number_format($rows['lc_sc_value'],2).
                ',Contract No:'.$rows['lc_sc_no'].',Lien Date :'.$rows['lien_date'].',Lien Bank:'.$rows['bank_name'];
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 220, 40, 40, $barcodestyle, 'N');
        $pdf->Text(170, 244, 'File No:'.$rows['file_no']);
        $pdf->Text(170, 247, 'Sub ID :'.$id);

        $pdf->SetFont('helvetica', 'N', 10);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/ExpLcScLienLetterPdf.pdf';
        $pdf->output($filename);
    }

}
