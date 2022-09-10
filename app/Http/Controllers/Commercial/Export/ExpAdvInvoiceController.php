<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Commercial\Export\ExpAdvInvoiceRequest;

class ExpAdvInvoiceController extends Controller {

    private $expadvinvoice;
    private $bankaccount;
    private $company;
    private $country;
    private $explcsc;
    private $buyer;
    private $budgetfabric;
    private $budget;
    private $salesorder;

    public function __construct(ExpAdvInvoiceRepository $expadvinvoice,BankAccountRepository $bankaccount, CompanyRepository $company, ExpLcScRepository $explcsc, CountryRepository $country,BuyerRepository $buyer, BudgetFabricRepository $budgetfabric,BudgetRepository $budget, SalesOrderRepository $salesorder) {

        $this->explcsc = $explcsc;
        $this->expadvinvoice = $expadvinvoice;
        $this->bankaccount = $bankaccount;
        $this->company = $company;
        $this->country = $country;
        $this->buyer = $buyer;
        $this->budgetfabric = $budgetfabric;
        $this->budget = $budget;
        $this->salesorder = $salesorder;

        $this->middleware('auth');
        // $this->middleware('permission:view.expadvinvoices',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.expadvinvoices', ['only' => ['store']]);
        // $this->middleware('permission:edit.expadvinvoices',   ['only' => ['update']]);
        // $this->middleware('permission:delete.expadvinvoices', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
         
        $expadvinvoices=array();
        $rows=$this->expadvinvoice
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_adv_invoices.exp_lc_sc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->orderBy('exp_adv_invoices.id','desc')
        ->get([
            'exp_lc_scs.id',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id',
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'exp_lc_scs.lien_date',
            'exp_lc_scs.hs_code',
            'exp_lc_scs.re_imbursing_bank',
            'exp_adv_invoices.*'
        ]);
        foreach($rows as $row){
            $expadvinvoice['id']=$row->id;
            $expadvinvoice['exp_lc_sc_id']=$row->exp_lc_sc_id;
            $expadvinvoice['lc_sc_no']=$row->lc_sc_no;
            $expadvinvoice['invoice_no']=$row->invoice_no;
            $expadvinvoice['invoice_date']=date('Y-m-d',strtotime($row->invoice_date));
            $expadvinvoice['invoice_value']=number_format($row->invoice_value,2);
            //$expadvinvoice['net_inv_value']=number_format($row->net_inv_value,2);
            $expadvinvoice['exp_form_no']=$row->exp_form_no;
            $expadvinvoice['exp_form_date']=($row->exp_form_date !== null)?date('Y-m-d',strtotime($row->exp_form_date)):null;
            $expadvinvoice['actual_ship_date']=($row->actual_ship_date !==null)?date('Y-m-d',strtotime($row->actual_ship_date)):null;
            $expadvinvoice['country_id']=$country[$row->country_id];
            $expadvinvoice['net_wgt_exp_qty']=number_format($row->net_wgt_exp_qty,2);
            $expadvinvoice['gross_wgt_exp_qty']=number_format($row->gross_wgt_exp_qty,2);
            $expadvinvoice['remarks']=$row->remarks;

            array_push($expadvinvoices,$expadvinvoice);
        }
        echo json_encode($expadvinvoices);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        $deliveryMode = array_prepend(config('bprs.deliveryMode'), '-Select-','');
        return Template::LoadView('Commercial.Export.ExpAdvInvoice',['company'=>$company,'country'=>$country,'incoterm'=>$incoterm,'deliveryMode'=>$deliveryMode]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpAdvInvoiceRequest $request) {
        $expadvinvoice=$this->expadvinvoice->create($request->except(['id','lc_sc_no','buyer_id','company_id','beneficiary_id','lien_date','hs_code']));
     
        if($expadvinvoice){
            return response()->json(array('success' => true,'id' =>  $expadvinvoice->id,'message' => 'Save Successfully'),200);
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
       $expadvinvoice = $this->expadvinvoice
      
       ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_adv_invoices.exp_lc_sc_id');
        })
       ->join('companies',function($join){
            $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
        })
       ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->where([['exp_adv_invoices.id','=',$id]])
        ->get([
            'exp_adv_invoices.*',
            'exp_lc_scs.id as exp_lc_sc_id',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.lien_date',
            'exp_lc_scs.hs_code',
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id'
        ])
       ->first();
       $expadvinvoice->invoice_amount=$expadvinvoice->invoice_value;
       $row ['fromData'] = $expadvinvoice;
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
    public function update(ExpAdvInvoiceRequest $request, $id) {
        $expadvinvoice=$this->expadvinvoice->update($id,$request->except(['id','lc_sc_no','buyer_id','company_id','beneficiary_id','lien_date','hs_code']));
        if($expadvinvoice){
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
        if($this->expadvinvoice->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getLcSc(){
        
        $contractNature = array_prepend(config('bprs.contractNature'), '-Select-','');         
        $rows=$this->explcsc
        ->join('companies',function($join){
            $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
        })
       ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
       ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
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
        //->groupBy(['exp_lc_scs.id'])
        ->orderBy('exp_lc_scs.id','desc')
        ->get([
            'exp_lc_scs.*',
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id'
        ])
        ->map(function ($rows) use($contractNature){
            $rows->contractNature=$contractNature[$rows->lc_sc_nature_id];
            return $rows;
            });
        echo json_encode($rows);
    }
	public function getOrderWiseAdvExpCi(){
        $payterm = array_prepend(config('bprs.payterm'), '','');
        $incoterm = array_prepend(config('bprs.incoterm'), '','');
        $deliveryMode = array_prepend(config('bprs.deliveryMode'), '','');

        $id=request('id', 0);

        $rows=$this->expadvinvoice
            ->join('exp_lc_scs',function($join){
                $join->on('exp_lc_scs.id','=','exp_adv_invoices.exp_lc_sc_id');
            })
            ->leftJoin('bank_branches', function($join){
                $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
            })
            ->leftJoin('bank_accounts', function($join){
                $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id')
                ->where([['bank_accounts.account_type_id','=',17]]);
            })
            ->leftJoin('banks', function($join){
                $join->on('banks.id', '=', 'bank_branches.bank_id');
            })
            ->join('buyers',function($join){
                $join->on('buyers.id','=','exp_lc_scs.buyer_id');
            })
            ->leftJoin('buyer_branches',function($join){
                $join->on('buyers.id','=','buyer_branches.buyer_id');
            })
            ->leftJoin('buyers as notifying_party',function($join){
                $join->on('notifying_party.id','=','exp_lc_scs.notifying_party_id');
            })
            ->leftJoin('buyer_branches as notify_branch', function($join){
                $join->on('notify_branch.buyer_id', '=', 'notifying_party.id');
            })
            ->leftJoin('buyers as consignee',function($join){
                $join->on('consignee.id','=','exp_lc_scs.consignee_id');
            })
            ->leftJoin('buyer_branches as consignee_branch', function($join){
                $join->on('consignee_branch.buyer_id', '=', 'consignee.id');
            })
            ->join('companies', function($join)  {
                $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
            })
            ->join('currencies',function($join){
                $join->on('currencies.id','=','exp_lc_scs.currency_id');
            })
            ->leftJoin('countries',function($join){
                $join->on('countries.id','=','currencies.country_id');
            })
        ->where([['exp_adv_invoices.id','=',$id]])
        ->get([
            'exp_adv_invoices.*',
            'exp_lc_scs.id',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.sc_or_lc',
            'exp_lc_scs.lc_sc_date',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id',
            'exp_lc_scs.tenor',
            'exp_lc_scs.pay_term_id',
            'exp_lc_scs.buyers_bank',
            'exp_lc_scs.lien_date',
            'exp_lc_scs.hs_code',
            'exp_lc_scs.re_imbursing_bank',
            'exp_lc_scs.transfer_bank',
            'exp_lc_scs.advise_bank',
            'exp_lc_scs.incoterm_id',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'notifying_party.name as notifying_party_name',
            'notify_branch.address as notify_branch_address',
            'consignee.name as consignee_name',
            'consignee_branch.address as consignee_branch_address',
            'companies.name as beneficiary_name',
            'companies.name as company_name',
            'companies.logo as logo',
            'companies.address as company_address',
            'companies.rex_no',
            'companies.rex_date',
            'companies.epb_reg_no',
            'companies.vat_number',
            'companies.erc_no',
            'currencies.code as currency_name',
            'banks.name as bank_name',
            'banks.swift_code',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'bank_accounts.account_no',
            'countries.region_id',
        ])
        ->map(function($rows) use($payterm,$incoterm,$deliveryMode){
            $rows->pay_term_id=$payterm[$rows->pay_term_id];
            $rows->incoterm_id=$incoterm[$rows->incoterm_id];
            $rows->ship_mode_id=$deliveryMode[$rows->ship_mode_id];

            $rows->exp_form_date=($rows->exp_form_date!==null)?date('d.m.Y',strtotime($rows->exp_form_date)):null;

            if ($rows->region_id==1) {
                $rows->region="European Union";
            }
            elseif ($rows->region_id==5) {
                $rows->region="United States of America";
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

            if($rows->sc_or_lc==1)
            {
              $rows->sc_or_lc_name='Sales Contract No'; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_or_lc_name='Export LC No'; 
            }

            return $rows;
        })
        ->first();

        

        $expinvoiceorder=$this->expadvinvoice
        ->selectRaw('
            sales_orders.id,
            styles.style_ref,
            sales_orders.sale_order_no,
            exp_pi_orders.sales_order_id,
            exp_adv_invoices.id as exp_adv_invoice_id,
            exp_pi_orders.id as exp_pi_order_id,
            exp_adv_invoice_orders.id as exp_adv_invoice_order_id,
            exp_adv_invoice_orders.commodity,
            exp_adv_invoice_orders.qty as invoice_qty,
            exp_adv_invoice_orders.rate as invoice_rate,
            exp_adv_invoice_orders.amount as invoice_amount
        ')
        ->join('exp_adv_invoice_orders',function($join){
            $join->on('exp_adv_invoice_orders.exp_adv_invoice_id','=','exp_adv_invoices.id');
            $join->whereNull('exp_adv_invoice_orders.deleted_at');
        })
        ->join('exp_pi_orders', function($join)  {
            $join->on('exp_adv_invoice_orders.exp_pi_order_id','=','exp_pi_orders.id');
        })
        ->join('exp_pis', function($join)  {
            $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
            //$join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
        })
        ->join('sales_orders', function($join)  {
            $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
        })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->where([['exp_adv_invoices.id','=',$id]])
        ->groupBy([
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'styles.style_ref',
            'exp_pi_orders.sales_order_id',
            'exp_adv_invoices.id',
            'exp_pi_orders.id',
            'exp_adv_invoice_orders.id',
            'exp_adv_invoice_orders.commodity',
            'exp_adv_invoice_orders.qty',
            'exp_adv_invoice_orders.rate',
            'exp_adv_invoice_orders.amount',
        ])
        ->get()
        ->map(function ($expinvoiceorder){
            $expinvoiceorder->ship_date=date('d-M-y',strtotime($expinvoiceorder->ship_date));
            return $expinvoiceorder;
        });

       
        $amount=$rows->invoice_value;
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents only');
        $rows->inword=$inword;
        $data=$expinvoiceorder;
        

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
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->AddPage();

        
        $pdf->SetY(10);
        $image_file ='images/logo/'.$rows->logo;
        $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(12);
        $pdf->SetFont('helvetica', 'N', 8);
        $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        //$pdf->Text(70, 12, $rows->company_address);
        $pdf->SetY(16);
        //$pdf->AddPage();
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
        $pdf->SetY(5);
        $pdf->SetX(150);
        $challan=str_pad($id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode('A'.str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', 'N', 7);
        //$pdf->SetTitle('General Item Purchase Order');
        $view= \View::make('Defult.Commercial.Export.AdvanceOrderWiseCIPdf',['rows'=>$rows,'data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/AdvanceOrderWiseCIPdf.pdf';
        $pdf->output($filename);
        exit();
    }
    
    public function getBoe(){
        $payterm = array_prepend(config('bprs.payterm'), '','');

        $id=request('id',0);

        $rows=$this->expadvinvoice
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_adv_invoices.exp_lc_sc_id');
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
        ->where([['exp_adv_invoices.id',$id]])
        ->get([            
            'exp_adv_invoices.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.lc_sc_date',
            'exp_lc_scs.file_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.buyers_bank', 
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exch_rate', 
            'exp_lc_scs.sc_or_lc',
            'exp_lc_scs.pay_term_id',
            'buyers.name as buyer_id',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'companies.name as company_name',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_name',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
        ])
        ->map(function($rows) use ($payterm) {

            $rows->pay_term_id=$payterm[$rows->pay_term_id];
            if($rows->submission_type_id==1){
                $rows->submission_type_id='Purchase/Collection';
            }else{
                $rows->submission_type_id='Collection';
            }
            if($rows->sc_or_lc==1)
            {
              $rows->sc_or_lc_name='Sales Contract'; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_or_lc_name='Export'; 
            }
            $rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
            return $rows;
        })
        ->first();
        $invoice_detail=$this->expadvinvoice
        ->selectRaw('
            exp_adv_invoices.id as exp_adv_invoice_id,
            exp_adv_invoices.invoice_no,
            exp_adv_invoices.invoice_date,
            exp_adv_invoices.invoice_value,
            exp_adv_invoices.gross_wgt_exp_qty,  
            exp_adv_invoices.net_wgt_exp_qty,
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.sc_or_lc,
            exp_lc_scs.lc_sc_date,
            exp_lc_scs.pay_term_id,
            cumulatives.cumulative_qty
        ')
        ->join('exp_lc_scs', function($join)  {
            $join->on('exp_lc_scs.id', '=', 'exp_adv_invoices.exp_lc_sc_id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            exp_adv_invoices.id as exp_adv_invoice_id,
            sum(exp_adv_invoice_orders.qty) as cumulative_qty,
            sum(exp_adv_invoice_orders.amount) as cumulative_amount 
            FROM exp_adv_invoices 
            join exp_adv_invoice_orders on  exp_adv_invoices.id=exp_adv_invoice_orders.exp_adv_invoice_id 
            where exp_adv_invoice_orders.deleted_at is null  
            group by exp_adv_invoices.id
             ) cumulatives"), "cumulatives.exp_adv_invoice_id", "=", "exp_adv_invoices.id")
        ->where([['exp_adv_invoices.id','=',$id]])
        ->get()
        ->map(function($invoice_detail){
            $invoice_detail->lc_sc_date=date('d-M-Y',strtotime($invoice_detail->lc_sc_date));
            $invoice_detail->invoice_date=date('d-M-Y',strtotime($invoice_detail->invoice_date));
            return $invoice_detail;
        });
        $rows->total_invoice_net_inv_value=$invoice_detail->sum('invoice_value');
        $rows->invoice_qty=$invoice_detail->sum('cumulative_qty');

        $amount=$rows->total_invoice_net_inv_value;
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
        $rows->inword=$inword;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->AddPage();
        $image_file ='images/logo/'.$rows['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(12);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $rows['company_address']);
        $pdf->Cell(0, 40, $rows['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        $pdf->SetFont('helvetica', 'N', 9);
        $view= \View::make('Defult.Commercial.Export.AdvanceBillOfExchangePdf',['rows'=>$rows,'image_file'=>$image_file]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/AdvanceBillOfExchangePdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function getForwardLetter()
    {
        $id=request('id',0);

        $rows=$this->expadvinvoice
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_adv_invoices.exp_lc_sc_id');
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
        ->where([['exp_adv_invoices.id',$id]])
        ->get([            
            'exp_adv_invoices.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.lc_sc_date',
            'exp_lc_scs.file_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.buyers_bank', 
            'exp_lc_scs.currency_id',
            'exp_lc_scs.exch_rate', 
            'exp_lc_scs.sc_or_lc', 
            'buyers.name as buyer_id',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'companies.name as beneficiary_id',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
        ])
        ->map(function($rows){
            if($rows->sc_or_lc==1)
            {
              $rows->sc_or_lc_name='Sales Cont'; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_or_lc_name='Export'; 
            }
            $rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
            return $rows;
        })
        ->first();
        

        $invoice_detail=$this->expadvinvoice
        ->selectRaw('
            exp_adv_invoices.id as exp_adv_invoice_id,
            exp_adv_invoices.invoice_no,
            exp_adv_invoices.invoice_date,
            exp_adv_invoices.invoice_value, 
            exp_adv_invoices.gross_wgt_exp_qty,  
            exp_adv_invoices.net_wgt_exp_qty,
            exp_adv_invoices.total_ctn_qty,
            exp_lc_scs.local_commission_per,
            exp_lc_scs.foreign_commission_per,
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.sc_or_lc,
            exp_lc_scs.lc_sc_date,
            cumulatives.cumulative_qty
        ')
        ->join('exp_lc_scs', function($join)  {
            $join->on('exp_lc_scs.id', '=', 'exp_adv_invoices.exp_lc_sc_id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            exp_adv_invoices.id as exp_adv_invoice_id,
            sum(exp_adv_invoice_orders.qty) as cumulative_qty,
            sum(exp_adv_invoice_orders.amount) as cumulative_amount 
            FROM exp_adv_invoices 
            join exp_adv_invoice_orders on  exp_adv_invoices.id=exp_adv_invoice_orders.exp_adv_invoice_id    
            where exp_adv_invoice_orders.deleted_at is null  
            group by exp_adv_invoices.id
             ) cumulatives"), "cumulatives.exp_adv_invoice_id", "=", "exp_adv_invoices.id")
        ->where([['exp_adv_invoices.id','=',$id]])
        ->get()
        ->map(function($invoice_detail){
            $invoice_detail->lc_sc_date=date('d-M-Y',strtotime($invoice_detail->lc_sc_date));
            $invoice_detail->invoice_date=date('d-M-Y',strtotime($invoice_detail->invoice_date));
            $invoice_detail->local_commission=$invoice_detail->invoice_value*($invoice_detail->local_commission_per/100);
            $invoice_detail->foreign_commission=$invoice_detail->invoice_value*($invoice_detail->foreign_commission_per/100);
            //$invoice_detail->net_inv_value=$invoice_detail->invoice_value-($invoice_detail->deduction+$invoice_detail->freight+$invoice_detail->local_commission+ $invoice_detail->foreign_commission)+$invoice_detail->up_charge_amount;
            return $invoice_detail;
        });
        $rows->total_invoice_net_inv_value=$invoice_detail->sum('invoice_value');
        $rows->invoice_qty=$invoice_detail->sum('cumulative_qty');
        $rows->total_ctn=$invoice_detail->sum('total_ctn_qty');

        $arrInvoice=array();
        foreach($invoice_detail as $bar){
            $arrInvoice[$bar->id]=$bar->invoice_no.", Dated: ".date('d-M-Y',strtotime($bar->invoice_date));
        }

       // $rows->invoice_no=implode(", ",$arrInvoice[$rows->exp_adv_invoice_id]);

        //dd($arrInvoice);die;
        $rows->gross_wgt_exp_qty=$invoice_detail->sum('gross_wgt_exp_qty');
        $rows->net_wgt_exp_qty=$invoice_detail->sum('net_wgt_exp_qty');



        $orders=$this->expadvinvoice
        ->join('exp_lc_scs', function($join)  {
            $join->on('exp_lc_scs.id', '=', 'exp_adv_invoices.exp_lc_sc_id');
        })
        ->join('exp_lc_sc_pis', function($join) {
            $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
            //$join->orOn('exp_lc_sc_pis.exp_lc_sc_id','=','exp_rep_lc_scs.replaced_lc_sc_id');
        })
        ->join('exp_pis', function($join)  {
            $join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
        }) 
        ->join('exp_adv_invoice_orders',function($join){ 
            $join->on('exp_adv_invoice_orders.exp_adv_invoice_id','=','exp_adv_invoices.id');
            $join->whereNull('exp_adv_invoice_orders.deleted_at');
        })
        ->join('exp_pi_orders', function($join)  {
            $join->on('exp_adv_invoice_orders.exp_pi_order_id','=','exp_pi_orders.id');
            $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
        })
        ->join('sales_orders', function($join)  {
            $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
        })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('styles', function($join)  {
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
        ->where([['exp_adv_invoices.id','=',$id]])
        ->get([
            'exp_adv_invoices.id',
            'exp_adv_invoice_orders.id as exp_adv_invoice_order_id',
            'exp_pi_orders.id as exp_pi_order_id',
            'sales_orders.id as sales_order_id',
            //'uoms.code as uom_name',
            'item_accounts.id as item_account_id',
            'item_accounts.item_description',
        ]);

        $row=array();
        $dsrows=array();
        foreach($orders as $order){
            $row['id'][]=$order->exp_adv_invoice_id;
            $dsrows[$order->item_account_id]=$order->item_description;
        }
        $doc=array();
        foreach($dsrows as $key=>$item){
            $doc[]=$item;
        }
        $rows->gmt_item=implode(',',$doc);
        //dd(implode(',',$doc));
        //die();
        
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
        
        $pdf->SetFont('helvetica', 'N', 9);
        
        $sub="Sub : Request for purchase/collection of our documents no: ".$rows['invoice_no']." for ".$rows['currency_code']." ".$rows['currency_symbol'] .$rows['total_invoice_net_inv_value']. " against ".$rows['sc_or_lc_name']." no: ".$rows['lc_sc_no']."; Date:".$rows['lc_sc_date'];

        $body="We have submitted export documents against delivery of ". $rows['invoice_qty'] ." PCS =".$rows['total_ctn']." CTN,  " .$rows['gmt_item']." against ".$rows['sc_or_lc_name']." no: ".$rows['lc_sc_no']." ; Date : ".($rows['lc_sc_date'])." as follows ";

        $ttp2="Therefore we request you to take neccessary steps to purchase/collection bills as soon as possible.";

        $view= \View::make('Defult.Commercial.Export.ExpAdvInvoiceForwardLetterPdf',['rows'=>$rows,'sub'=>$sub,'body'=>$body,'ttp2'=>$ttp2,'invoice_detail'=>$invoice_detail]);
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

        $qrc =  'Document value:'.$rows['currency_symbol'].' '.number_format($rows['total_invoice_net_inv_value'],2).
                ',Garment qty:'.$rows['invoice_qty'].',Carton Qty :'.$rows['total_ctn'].',Bank:'.$rows['bank_name'].',Buyer:'.$rows['buyer_id'].',Export LC/SC :'.$rows['lc_sc_no'].',Garment Item:'.$rows['gmt_item'].',Gross weight:'.$rows['gross_wgt_exp_qty'].'Net weight :'.$rows['net_wgt_exp_qty'];
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 220, 40, 40, $barcodestyle, 'N');
        $pdf->Text(170, 244, 'File No:'.$rows['file_no']);
        $pdf->Text(170, 247, 'Sub ID :'.$id);

        $pdf->SetFont('helvetica', 'N', 10);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/ExpAdvInvoiceForwardLetterPdf.pdf';
        $pdf->output($filename);
    }


}
