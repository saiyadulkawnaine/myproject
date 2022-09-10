<?php
namespace App\Http\Controllers\Report\Commercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiOrderRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzDeductRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzAmountRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubBankRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
//use App\Library\Numbertowords;
use Illuminate\Support\Carbon;

class LocalExpLcProgressReportController extends Controller
{
    private $localexppi;
    private $buyer;
    private $salesorder;
    private $company;
    private $itemaccount;
    private $termscondition;
    private $localexppiorder;
    private $currency;
    private $soaop;
    private $localexplc;
    private $localexpprorlz;
    private $localexpprorlzdeduct;
    private $localexpprorlzamount;
    
    public function __construct(LocalExpPiRepository $localexppi,BuyerRepository $buyer,ItemAccountRepository $itemaccount,SalesOrderRepository $salesorder, CompanyRepository $company, TermsConditionRepository $termscondition,
    PurchaseTermsConditionRepository $purchasetermscondition, LocalExpPiOrderRepository $localexppiorder,
    SoKnitRepository $soknit,
    SoDyeingRepository $sodyeing,
    GmtspartRepository $gmtspart,
    AutoyarnRepository $autoyarn,
    UomRepository $uom,
    ColorrangeRepository $colorrange,
    CurrencyRepository $currency,
    ColorRepository $color,
    EmbelishmentTypeRepository $embelishmenttype,
    SoAopRepository $soaop,LocalExpProRlzRepository $localexpprorlz,LocalExpProRlzDeductRepository $localexpprorlzdeduct,LocalExpProRlzAmountRepository $localexpprorlzamount,LocalExpLcRepository $localexplc,BankRepository $bank
    , BankBranchRepository $bankbranch
  ) {
        $this->localexppi = $localexppi;
        $this->localexppiorder = $localexppiorder;
        $this->buyer = $buyer;
        $this->salesorder = $salesorder;
        $this->company = $company;
        $this->itemaccount = $itemaccount;
        $this->termscondition = $termscondition;
        $this->purchasetermscondition = $purchasetermscondition;
        $this->soknit = $soknit;
        $this->sodyeing = $sodyeing;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->currency= $currency;
        $this->embelishmenttype = $embelishmenttype;
        $this->soaop = $soaop;
        $this->localexplc = $localexplc;
        $this->localexpprorlz = $localexpprorlz;
        $this->localexpprorlzdeduct = $localexpprorlzdeduct;
        $this->localexpprorlzamount = $localexpprorlzamount;
        $this->bank = $bank;
        $this->bankbranch = $bankbranch;

        $this->middleware('auth');
        //$this->middleware('permission:view.localexppireports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $productionarea=array_prepend(array_only(config('bprs.productionarea'),[5,10,20,25,45,50]),'-Select-','');
        $years=array_prepend(config('bprs.years'),'-Select-','');
        $selected_year=date('Y');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-','');
        $availabledocs=array_prepend(array_only(config('bprs.availDocs'),[4,5,6,7]),'-Select-','');
        
        return Template::loadView('Report.Commercial.LocalExpLcProgressReport',['company'=>$company,'buyer'=>$buyer,'productionarea'=>$productionarea,'years'=>$years,'selected_year'=>$selected_year,'payterm'=>$payterm,'incoterm'=>$incoterm,
        'aoptype'=>$aoptype,'yesno'=>$yesno,'availabledocs'=>$availabledocs]);
    }

    
    public function getData()
     {   
       // $bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
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

        $productionarea=array_prepend(array_only(config('bprs.productionarea'),[5,10,20,25,45,50]),'-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $production_area_id=request('production_area_id', 0);
        $local_lc_no=request('local_lc_no', 0);
        $date_from =request('date_from',0);
        $date_to=request('date_to',0);
        $maturity_date_from =request('maturity_date_from',0);
        $maturity_date_to=request('maturity_date_to',0);

       $localexplc=$this->localexplc
        ->selectRaw('
            local_exp_lcs.id as local_exp_lc_id,
            local_exp_lcs.buyer_id,
            --local_exp_lcs.lc_value as tagged_lc_value,
            local_exp_lcs.beneficiary_id,
            local_exp_lcs.production_area_id,
            local_exp_lcs.exporter_bank_branch_id,
            local_exp_lcs.local_lc_no,
            local_exp_lcs.lc_date,
            local_exp_lcs.pay_term_id,
            local_exp_lcs.tenor,
            local_exp_lcs.currency_id,
            companies.code as company_code,
            buyers.name as buyer_name,
            currencies.code as currency_code,
            local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
            local_exp_doc_sub_accepts.submission_date as accept_submit_date,
            local_exp_doc_sub_accepts.accept_receive_date,
            local_exp_doc_sub_banks.submission_date as bank_submit_date,
            local_exp_doc_sub_banks.maturity_rcv_date,
            local_exp_doc_sub_banks.bank_ref_bill_no,
            local_exp_doc_sub_banks.negotiation_date,
            local_exp_doc_sub_banks.place_for_purchase,
            realizedDocBank.realization_date,
            bankTrans.purchase_amount,
            realizedDocBank.realized_amount,
            taggedLc.tagged_lc_qty,
            invoice.invoice_value
        ')/* local_exp_doc_sub_banks.doc_value as purchase_amount, */
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->join(\DB::raw("(SELECT
        local_exp_lcs.id as local_exp_lc_id,
            sum(local_exp_pis.qty) as tagged_lc_qty,
            sum(local_exp_pis.amount) as tagged_lc_value
            FROM local_exp_lcs 
        join local_exp_lc_tag_pis on local_exp_lcs.id = local_exp_lc_tag_pis.local_exp_lc_id 
        join local_exp_pis on local_exp_pis.id = local_exp_lc_tag_pis.local_exp_pi_id 
        group by local_exp_lcs.id) taggedLc"), "taggedLc.local_exp_lc_id", "=", "local_exp_lcs.id")
    ->join('local_exp_doc_sub_accepts',function($join){
       // $join->on('local_exp_doc_sub_accepts.local_exp_lc_id', '=', 'local_exp_invoices.local_exp_lc_id');
       $join->on('local_exp_lcs.id', '=', 'local_exp_doc_sub_accepts.local_exp_lc_id');
    })
    ->leftJoin('local_exp_doc_sub_banks',function($join){
        $join->on('local_exp_doc_sub_accepts.id','=','local_exp_doc_sub_banks.local_exp_doc_sub_accept_id');
    })
    ->join(\DB::raw("(
        select local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
        local_exp_invoices.local_invoice_value as invoice_value
        from local_exp_doc_sub_accepts
        join local_exp_doc_sub_invoices on local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id=local_exp_doc_sub_accepts.id
        join local_exp_invoices on local_exp_invoices.id=local_exp_doc_sub_invoices.local_exp_invoice_id
        group by 
        local_exp_doc_sub_accepts.id,
        local_exp_invoices.local_invoice_value) invoice"), 
        "invoice.local_exp_doc_sub_accept_id", "=", "local_exp_doc_sub_accepts.id")
    ->leftJoin(\DB::raw("(select
    local_exp_doc_sub_banks.id as local_exp_doc_sub_bank_id,
    sum(local_exp_doc_sub_trans.doc_value) as purchase_amount
from local_exp_doc_sub_banks 
    join local_exp_doc_sub_trans on local_exp_doc_sub_banks.id = local_exp_doc_sub_trans.local_exp_doc_sub_bank_id 
    group by local_exp_doc_sub_banks.id) bankTrans"), "bankTrans.local_exp_doc_sub_bank_id", "=", "local_exp_doc_sub_banks.id")
    // ->leftJoin('local_exp_pro_rlzs',function($join){
    //     $join->on('local_exp_pro_rlzs.local_exp_doc_sub_bank_id','=','local_exp_doc_sub_banks.id');
    // })
    ->leftJoin(\DB::raw("(select
    local_exp_doc_sub_banks.id as local_exp_doc_sub_bank_id,
    local_exp_pro_rlzs.realization_date,
    sum(local_exp_pro_rlz_amounts.doc_value) as realized_amount
 from local_exp_doc_sub_banks 
    join local_exp_pro_rlzs on local_exp_doc_sub_banks.id = local_exp_pro_rlzs.local_exp_doc_sub_bank_id 
    join local_exp_pro_rlz_amounts on local_exp_pro_rlzs.id = local_exp_pro_rlz_amounts.local_exp_pro_rlz_id 
    where local_exp_pro_rlz_amounts.deleted_at is null
    group by 
    local_exp_doc_sub_banks.id,
    local_exp_pro_rlzs.realization_date) realizedDocBank"), "realizedDocBank.local_exp_doc_sub_bank_id", "=", "local_exp_doc_sub_banks.id")
    ->when(request('production_area_id'), function ($q,$production_area_id) {
        return $q->where('local_exp_lcs.production_area_id', '=', $production_area_id);
    })
    ->when(request('beneficiary_id'), function ($q) {
        return $q->where('local_exp_lcs.beneficiary_id', '=', request('beneficiary_id', 0));
    })
    ->when(request('buyer_id'), function ($q) {
        return $q->where('local_exp_lcs.buyer_id', '=', request('buyer_id', 0));
    })
    ->when(request('local_lc_no'), function ($q) {
        return $q->where('local_exp_lcs.local_lc_no', 'LIKE', "%".request('local_lc_no', 0)."%");
    })
    ->when($date_from, function ($q) use($date_from){
        return $q->where('local_exp_lcs.lc_date', '>=',$date_from);
    })
    ->when($date_to, function ($q) use($date_to) {
        return $q->where('local_exp_lcs.lc_date', '<=',$date_to);
    })
    ->when($maturity_date_from, function ($q) use($maturity_date_from){
        return $q->where('local_exp_doc_sub_banks.maturity_rcv_date', '>=',$maturity_date_from);
    })
    ->when($maturity_date_to, function ($q) use($maturity_date_to) {
        return $q->where('local_exp_doc_sub_banks.maturity_rcv_date', '<=',$maturity_date_to);
    })
    ->orderBy('local_exp_lcs.id','desc')
    ->groupBy([
        'local_exp_lcs.id',
        'local_exp_lcs.buyer_id',
        'local_exp_lcs.beneficiary_id',
        'local_exp_lcs.production_area_id',
        //'local_exp_lcs.lc_value',
        'local_exp_lcs.exporter_bank_branch_id',
        'local_exp_lcs.local_lc_no',
        'local_exp_lcs.lc_date',
        'local_exp_lcs.pay_term_id',
        'local_exp_lcs.tenor',
        'local_exp_lcs.currency_id',
        'companies.code',
        'buyers.name',
        'currencies.code',
        'local_exp_doc_sub_accepts.id',
        'local_exp_doc_sub_accepts.submission_date',
        'local_exp_doc_sub_accepts.accept_receive_date',
        'local_exp_doc_sub_banks.submission_date',
        'local_exp_doc_sub_banks.maturity_rcv_date',
        'local_exp_doc_sub_banks.place_for_purchase',
        'local_exp_doc_sub_banks.bank_ref_bill_no',
        'local_exp_doc_sub_banks.negotiation_date',
        //'local_exp_doc_sub_banks.doc_value',
        'realizedDocBank.realization_date',
        'bankTrans.purchase_amount',
        'realizedDocBank.realized_amount',
        'taggedLc.tagged_lc_qty',
        'invoice.invoice_value',
    ])
    ->get()
    ->map(function($localexplc) use ($payterm,$productionarea, $bankbranch){
        $localexplc->production_area_id=$productionarea[$localexplc->production_area_id];
        $localexplc->lien_bank=$bankbranch[$localexplc->exporter_bank_branch_id];
        $localexplc->pay_term_id=$payterm[$localexplc->pay_term_id];
        $localexplc->lc_date=($localexplc->lc_date !== null)?date('d-M-Y',strtotime($localexplc->lc_date)):null;
        $localexplc->accept_submit_date=($localexplc->accept_submit_date !== null)?date('d-M-Y',strtotime($localexplc->accept_submit_date)):null;
        $localexplc->accept_receive_date=($localexplc->accept_receive_date !== null)?date('d-M-Y',strtotime($localexplc->accept_receive_date)):null;
        $localexplc->bank_submit_date=($localexplc->bank_submit_date !== null)?date('d-M-Y',strtotime($localexplc->bank_submit_date)):null;
        $localexplc->maturity_rcv_date=($localexplc->maturity_rcv_date !== null)?date('d-M-Y',strtotime($localexplc->maturity_rcv_date)):null;
        $localexplc->realization_date=($localexplc->realization_date !== null)?date('d-M-Y',strtotime($localexplc->realization_date)):null;
        $localexplc->place_for_purchase=($localexplc->place_for_purchase !== null)?date('d-M-Y',strtotime($localexplc->place_for_purchase)):null;
        $localexplc->negotiation_date=($localexplc->negotiation_date !== null)?date('d-M-Y',strtotime($localexplc->negotiation_date)):null;

        if($localexplc->accept_receive_date){
            $acceptsubmission = Carbon::parse($localexplc->accept_submit_date);
            $acceptreceive = Carbon::parse($localexplc->accept_receive_date);
            $days_taken_to_accept = $acceptreceive->diffInDays($acceptsubmission);
            $localexplc->days_taken_to_accept=$days_taken_to_accept;
        }
        if($localexplc->maturity_rcv_date){
            $banksubmission = Carbon::parse($localexplc->bank_submit_date);
            $bankreceive = Carbon::parse($localexplc->maturity_rcv_date);
            $days_taken_to_maturity = $bankreceive->diffInDays($banksubmission);
            $localexplc->days_taken_to_maturity=$days_taken_to_maturity;

             if ($localexplc->tenor) {
                $datesum = date('d-M-Y', strtotime($localexplc->maturity_rcv_date.' + '.$localexplc->tenor.' days'));
            }else{
                $datesum = date('d-M-Y', strtotime($localexplc->maturity_rcv_date.' + '. '0 days'));
            }
            $localexplc->maturity_date=$datesum;
        }

        $maturity_date = Carbon::parse($localexplc->maturity_date);
            $today=Carbon::parse(date('Y-m-d'));
            $pasedDays = $maturity_date->lessThan($today);
            $realized = Carbon::parse($localexplc->realization_date);
            $lessMaturityDays = $maturity_date->lessThan($realized);

            if($localexplc->realization_date=='' && $pasedDays){
                //$localexplc->overdue_days="No Overdue";
                $today=Carbon::parse(date('Y-m-d'));
                $maturityday = Carbon::parse($localexplc->maturity_date);
                $overdue_days = $today->diffInDays($maturityday);
                $localexplc->overdue_days=$overdue_days;
            }
            if($localexplc->realization_date && $lessMaturityDays ){
                $realized = Carbon::parse($localexplc->realization_date);
                $maturityday = Carbon::parse($localexplc->maturity_date);
                $overdue_days = $realized->diffInDays($maturityday);
                $localexplc->overdue_days=$overdue_days;
            }
        
            if($localexplc->realization_date){
                $bankmaturity = Carbon::parse($localexplc->maturity_date);
                $realized = Carbon::parse($localexplc->realization_date);
                $days_taken_to_realized = $realized->diffInDays($bankmaturity,false);
                $localexplc->days_taken_to_realized=$days_taken_to_realized;    
            }

            $localexplc->tagged_lc_value = number_format($localexplc->tagged_lc_value,2);
            $localexplc->purchase_amount = number_format($localexplc->purchase_amount,2);
            $localexplc->realized_amount = number_format($localexplc->realized_amount,2);
            $localexplc->tagged_lc_qty = number_format($localexplc->tagged_lc_qty,0);
            $localexplc->invoice_value = number_format($localexplc->invoice_value,2);
            
            $localexplc->accept_submit_date=($localexplc->accept_submit_date)?date('d-M-Y',strtotime($localexplc->accept_submit_date)):'--';
            $localexplc->accept_receive_date=($localexplc->accept_receive_date)?date('d-M-Y',strtotime($localexplc->accept_receive_date)):'--';
            $localexplc->bank_submit_date=($localexplc->bank_submit_date)?date('d-M-Y',strtotime($localexplc->bank_submit_date)):'--';
            $localexplc->maturity_rcv_date=($localexplc->maturity_rcv_date)?date('d-M-Y',strtotime($localexplc->maturity_rcv_date)):'--';
            $localexplc->realization_date=($localexplc->realization_date)?date('d-M-Y',strtotime($localexplc->realization_date)):'--';
            $localexplc->place_for_purchase=($localexplc->place_for_purchase)?date('d-M-Y',strtotime($localexplc->place_for_purchase)):'--';
            $localexplc->negotiation_date=($localexplc->negotiation_date)?date('d-M-Y',strtotime($localexplc->negotiation_date)):'--';
            $localexplc->maturity_date=($localexplc->maturity_date)?date('d-M-Y',strtotime($localexplc->maturity_date)):'--';
            $localexplc->days_taken_to_accept=$localexplc->days_taken_to_accept?$localexplc->days_taken_to_accept:'--';
            $localexplc->days_taken_to_maturity=$localexplc->days_taken_to_maturity?$localexplc->days_taken_to_maturity:'--';
            $localexplc->overdue_days=$localexplc->overdue_days?$localexplc->overdue_days:'--';
            $localexplc->days_taken_to_realized=$localexplc->days_taken_to_realized?$localexplc->days_taken_to_realized:'--';
            $localexplc->bank_ref_bill_no=$localexplc->bank_ref_bill_no?$localexplc->bank_ref_bill_no:'--';
        return $localexplc;
    });

        $data=0;
    
        // if(request('available_doc_id',0)==1 && request('status_id',0)==1)
        // {
        //     $data = $localexplc->filter(function ($value) {
        //         if($value->invoice_value){
        //             return $value;
        //         }
        //     })->values();
        // }
        // else if(request('available_doc_id',0)==1 && request('status_id',0)==0)
        // {
        //     $data = $localexplc->filter(function ($value) {
        //         if(!$value->invoice_value){
        //             return $value;
        //         }
        //     })->values();
        // }
        if(request('available_doc_id',0)==2 && request('status_id',0)==1)
        {
            $data = $localexplc->filter(function ($value) {
                if($value->accept_submit_date){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==2 && request('status_id',0)==0)
        {
            $data = $localexplc->filter(function ($value) {
                if(!$value->accept_submit_date){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==3 && request('status_id',0)==1)
        {
            $data = $localexplc->filter(function ($value) {
                if($value->accept_receive_date){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==3 && request('status_id',0)==0)
        {
            $data = $localexplc->filter(function ($value) {
                if(!$value->accept_receive_date){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==4 && request('status_id',0)==1)
        {
            $data = $localexplc->filter(function ($value) {
                if($value->bank_submit_date){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==4 && request('status_id',0)==0)
        {
            $data = $localexplc->filter(function ($value) {
                if(!$value->bank_submit_date){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==5 && request('status_id',0)==1)
        {
            $data = $localexplc->filter(function ($value) {
                if($value->maturity_rcv_date){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==5 && request('status_id',0)==0)
        {
            $data = $localexplc->filter(function ($value) {
                if(!$value->maturity_rcv_date){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==6 && request('status_id',0)==1)
        {
            $data = $localexplc->filter(function ($value) {
                if($value->place_for_purchase){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==6 && request('status_id',0)==0)
        {
            $data = $localexplc->filter(function ($value) {
                if(!$value->place_for_purchase){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==7 && request('status_id',0)==1)
        {
            $data = $localexplc->filter(function ($value) {
                if($value->realization_date){
                    return $value;
                }
            })->values();
        }
        else if(request('available_doc_id',0)==7 && request('status_id',0)==0)
        {
            $data = $localexplc->filter(function ($value) {
                if(!$value->realization_date){
                    return $value;
                }
            })->values();
        }
        else{
            $data=$localexplc;
        }
       
        echo json_encode($data);
    }
 
    public function getLocalInvoice(){
        $invoiceId=request('local_exp_lc_id', 0);
        $localexpinvoice=$this->localexplc
        ->join('local_exp_invoices', function($join)  {
            $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
        })
        ->leftJoin(\DB::raw("(SELECT
            local_exp_invoices.id as local_exp_invoice_id,
            sum(local_exp_invoice_orders.qty) as invoice_qty,
            sum(local_exp_invoice_orders.amount) as invoice_amount 
        FROM local_exp_lcs
            join local_exp_invoices on  local_exp_invoices.local_exp_lc_id=local_exp_lcs.id
            left join local_exp_invoice_orders on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
            join local_exp_doc_sub_invoices on local_exp_invoices.id=local_exp_doc_sub_invoices.local_exp_invoice_id
            join local_exp_doc_sub_accepts on local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id=local_exp_doc_sub_accepts.id
        where local_exp_invoice_orders.deleted_at is null
        group by local_exp_invoices.id) cumulatives"), "cumulatives.local_exp_invoice_id", "=", "local_exp_invoices.id")
        ->where([['local_exp_lcs.id','=',$invoiceId]])
        ->get([
            'local_exp_lcs.id as local_exp_lc_id',
            'local_exp_lcs.local_lc_no',
            'local_exp_invoices.*',
            'local_exp_invoices.id as local_exp_invoice_id',
            'cumulatives.invoice_qty',
            'cumulatives.invoice_amount'
        ]);

        echo json_encode($localexpinvoice);
    }

    public function getLocalTransec(){
        $docsubbankId=request('local_exp_doc_sub_bank_id', 0);
        $localtransenction=$this->localexplc
        ->leftJoin('local_exp_doc_sub_accepts',function($join){
            $join->on('local_exp_doc_sub_accepts.local_exp_lc_id','=','local_exp_lcs.id');
        })
        ->leftJoin('local_exp_doc_sub_banks',function($join){
            $join->on('local_exp_doc_sub_banks.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
        })
        ->leftJoin(\DB::raw("(select
        local_exp_doc_sub_banks.id as local_exp_doc_sub_bank_id,
        local_exp_doc_sub_trans.commercialhead_id,
        sum(local_exp_doc_sub_trans.doc_value) as purchase_amount
        from local_exp_doc_sub_banks 
        left join local_exp_doc_sub_trans on local_exp_doc_sub_banks.id = local_exp_doc_sub_trans.local_exp_doc_sub_bank_id 
        group by local_exp_doc_sub_banks.id,
        local_exp_doc_sub_trans.commercialhead_id) bankTrans"), "bankTrans.local_exp_doc_sub_bank_id", "=", "local_exp_doc_sub_banks.id")
        ->where([['local_exp_doc_sub_banks.id','=',$docsubbankId]])
        ->get([
            'local_exp_doc_sub_banks.*',
            'bankTrans.commercialhead_id'
        ]);

        echo json_encode($localtransenction);
    }
    
    public function getLocalLc(){
        $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'code','id'),'','');
         
        $localexplcs=array();
        $rows=$this->localexplc
        ->orderBy('local_exp_lcs.id','desc')
        ->get([
            'local_exp_lcs.id as local_exp_lc_id',
            'local_exp_lcs.*'
        ]);

        foreach($rows as $row){
            $localexplc['local_exp_lc_id']=$row->local_exp_lc_id;
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