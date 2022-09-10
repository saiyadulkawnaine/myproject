<?php
namespace App\Http\Controllers\Report\Commercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
class MonthlyExpInvoiceReportController extends Controller
{
	private $lcsc;
	private $expinvoice;
	private $company;
	private $buyer;
	private $bank;
    private $bankbranch;
    
	public function __construct(
        ExpLcScRepository $lcsc,
        ExpInvoiceRepository $expinvoice,
        CompanyRepository $company, 
        BuyerRepository $buyer,BankRepository $bank,
		BankBranchRepository $bankbranch
        
        )
    {
		$this->lcsc    = $lcsc;
		$this->expinvoice    = $expinvoice;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->bank = $bank;
		$this->bankbranch = $bankbranch;
		$this->middleware('auth');
		//$this->middleware('permission:view.cashincentivefollowupreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $invoicestatus=array_prepend(config('bprs.invoicestatus'), '','');
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
        return Template::loadView('Report.Commercial.MonthlyExpInvoiceReport',['company'=>$company,'buyer'=>$buyer, 'bankbranch'=>$bankbranch, 'invoicestatus'=>$invoicestatus]);
    }

    
    public function getData()
    {
    	$data=$this->expinvoice
    	->selectRaw('
    		styles.style_ref,
    		buyers.name as buyer_name,
    		banks.name as lien_bank,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
    		exp_lc_scs.lc_sc_no,
    		exp_lc_scs.file_no,
    		exp_lc_scs.lc_sc_date,
    		exp_invoices.invoice_no,
            exp_invoices.id as invoice_id,
    		exp_invoices.invoice_date,
            
			exp_invoice_orders.qty,
			exp_invoice_orders.rate as invoice_rate,
			exp_invoice_orders.amount,
            exp_invoices.invoice_value,
            --exp_invoices.net_inv_value,
            exp_invoices.discount_amount,
            exp_invoices.bonus_amount,
            exp_invoices.claim_amount,
            exp_invoices.commission,
            
            exp_invoices.freight_by_supplier,
            exp_invoices.freight_by_buyer,
            exp_invoices.invoice_value*(exp_lc_scs.local_commission_per/100) as local_commission_invoice,
            exp_invoices.invoice_value*(exp_lc_scs.foreign_commission_per/100) as foreign_commission_invoice

    	')/*exp_invoices.net_inv_value,*/
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
		->leftJoin('companies', function($join)  {
			$join->on('companies.id', '=', 'sales_orders.produced_company_id');
		})
		->leftJoin('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('buyers', function($join)  {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->leftJoin('companies as bcompanies', function($join) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
		})
		->leftJoin('bank_branches', function($join) {
			$join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
		})
		->leftJoin('banks', function($join) {
			$join->on('banks.id', '=', 'bank_branches.bank_id');
		})
		->when(request('invoice_date_from',0), function ($q){
		return $q->where('exp_invoices.invoice_date', '>=',request('invoice_date_from',0));
		})
		->when(request('invoice_date_to',0), function ($q){
		return $q->where('exp_invoices.invoice_date', '<=',request('invoice_date_to',0));
		})
		->when(request('invoice_status_id',0), function ($q){
		return $q->where('exp_invoices.invoice_status_id', '=',request('invoice_status_id',0));
		})
		->when(request('exporter_bank_branch_id',0), function ($q){
		return $q->where('exp_lc_scs.exporter_bank_branch_id', '=',request('exporter_bank_branch_id',0));
		})
		->when(request('lc_sc_date_from',0), function ($q){
		return $q->where('exp_lc_scs.lc_sc_date', '>=',request('lc_sc_date_from',0));
		})
		->when(request('lc_sc_date_to',0), function ($q){
		return $q->where('exp_lc_scs.lc_sc_date', '<=',request('lc_sc_date_to',0));
		})
		->when(request('ex_factory_date_from',0), function ($q){
			return $q->where('exp_invoices.ex_factory_date', '>=',request('ex_factory_date_from',0));
		})
		->when(request('ex_factory_date_to',0), function ($q){
			return $q->where('exp_invoices.ex_factory_date', '<=',request('ex_factory_date_to',0));
		})
		->when(request('lc_sc_no',0), function ($q){
		return $q->where('exp_lc_scs.lc_sc_no', 'LIKE',"%".request('lc_sc_no',0)."%");
		})
		->when(request('buyer_id',0), function ($q){
		return $q->where('exp_lc_scs.buyer_id', '=',request('buyer_id',0));
		})
		->when(request('company_id',0), function ($q){
		return $q->where('jobs.company_id', '=',request('company_id',0));
		})
       // ->where([['exp_invoices.invoice_status_id','=',2]])
        ->orderBy('exp_invoices.invoice_date')
		->get()
		->map(function($data){
			$data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			$data->lc_sc_date=date('d-M-Y',strtotime($data->lc_sc_date));
			$data->invoice_date=date('d-M-Y',strtotime($data->invoice_date));
			$data->invoice_qty=number_format($data->qty,0);
			$data->invoice_amount=number_format($data->amount,2);
            $data->net_inv_value=$data->invoice_value-($data->bonus_amount+$data->discount_amount+$data->claim_amount+$data->local_commission_invoice+$data->foreign_commission_invoice+$data->freight_by_supplier+$data->freight_by_buyer+$data->deduction);
            return $data;
        });
        $month=[];
        $buyer=[];
        $company=[];
        foreach($data as $row){
        	$m=date('M-Y',strtotime($row->invoice_date));
        	$month[$m]['qty']=isset($month[$m]['qty'])?$month[$m]['qty']+=$row->qty:$row->qty;
        	$month[$m]['amount']=isset($month[$m]['amount'])?$month[$m]['amount']+=$row->amount:$row->amount;

            $month[$m]['net_inv_value'][$row->invoice_id]=$row->net_inv_value;

        	$month[$m]['no_of_invoice'][$row->invoice_no]=$row->invoice_no;

        	$buyer[$row->buyer_name]['qty']=isset($buyer[$row->buyer_name]['qty'])?$buyer[$row->buyer_name]['qty']+=$row->qty:$row->qty;
        	$buyer[$row->buyer_name]['amount']=isset($buyer[$row->buyer_name]['amount'])?$buyer[$row->buyer_name]['amount']+=$row->amount:$row->amount;

            $buyer[$row->buyer_name]['net_inv_value'][$row->invoice_id]=$row->net_inv_value;

        	$buyer[$row->buyer_name]['no_of_invoice'][$row->invoice_no]=$row->invoice_no;

        	$company[$row->company_id]['qty']=isset($company[$row->company_id]['qty'])?$company[$row->company_id]['qty']+=$row->qty:$row->qty;
        	$company[$row->company_id]['amount']=isset($company[$row->company_id]['amount'])?$company[$row->company_id]['amount']+=$row->amount:$row->amount;
            $company[$row->company_id]['net_inv_value'] [$row->invoice_id]=$row->net_inv_value;

        	$company[$row->company_id]['no_of_invoice'][$row->invoice_no]=$row->invoice_no;
        }
        $monthDatas=[];
        foreach($month as $key=>$value){
        	$monthData['month']=$key;
        	$monthData['invoice_qty']=number_format($value['qty'],0);
        	$monthData['invoice_amount']=number_format($value['amount'],2);
            $monthData['net_invoice_amount']=number_format(array_sum($value['net_inv_value']),2);
        	$monthData['no_of_invoice']=number_format(count($value['no_of_invoice']),0);
        	array_push($monthDatas, $monthData);
        }

        $buyerDatas=[];
        foreach($buyer as $key=>$value){
        	$buyerData['buyer_name']=$key;
        	$buyerData['invoice_qty']=number_format($value['qty'],0);
        	$buyerData['invoice_amount']=number_format($value['amount'],2);
            $buyerData['net_invoice_amount']=number_format(array_sum($value['net_inv_value']),2);
        	$buyerData['no_of_invoice']=number_format(count($value['no_of_invoice']),0);
        	array_push($buyerDatas, $buyerData);
        }

        $companyDatas=[];
        foreach($company as $key=>$value){
        	$companyData['company_name']=$key;
        	$companyData['invoice_qty']=number_format($value['qty'],0);
        	$companyData['invoice_amount']=number_format($value['amount'],2);
            $companyData['net_invoice_amount']=number_format(array_sum($value['net_inv_value']),2);
        	$companyData['no_of_invoice']=number_format(count($value['no_of_invoice']),0);
        	array_push($companyDatas, $companyData);
        }
        echo json_encode(['details'=>$data,'month'=>$monthDatas,'buyer'=>$buyerDatas,'company'=>$companyDatas]);
    }

	//  public function invoiceDetails(){
			// 	$deliveryMode = array_prepend(config('bprs.deliveryMode'), '-Select-','');
			// 	$rows=$this->expinvoice
			// 	->join('exp_lc_scs', function($join){
			// 		$join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
			// 	})
			// 	->join('bank_branches', function($join) {
			// 		$join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
			// 	})
			// 	->join('banks', function($join) {
			// 		$join->on('banks.id', '=', 'bank_branches.bank_id');
			// 	})
			// 	->join('buyers', function($join)  {
			// 		$join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
			// 	})
			// 	->leftJoin(\DB::raw("(
			// 		select
			// 		exp_invoices.id as exp_invoice_id,
			// 		exp_doc_submissions.stuffing_date
			// 		from
			// 		exp_invoices
			// 		join exp_doc_sub_invoices on exp_doc_sub_invoices.exp_invoice_id=exp_invoices.id
			// 		join exp_doc_submissions on exp_doc_submissions.id=exp_doc_sub_invoices.exp_doc_submission_id
			// 		join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id 
			// 		and exp_doc_submissions.exp_lc_sc_id=exp_lc_scs.id
			// 		group by 
			// 		exp_invoices.id,
			// 		exp_doc_submissions.stuffing_date
			// 	) expdocsubmission"), "expdocsubmission.exp_invoice_id", "=", "exp_invoices.id")
			// 	->when(request('invoice_date_from',0), function ($q){
			// 		return $q->where('exp_invoices.invoice_date', '>=',request('invoice_date_from',0));
			// 	})
			// 	->when(request('invoice_date_to',0), function ($q){
			// 		return $q->where('exp_invoices.invoice_date', '<=',request('invoice_date_to',0));
			// 	})
			// 	->when(request('invoice_status_id',0), function ($q){
			// 		return $q->where('exp_invoices.invoice_status_id', '=',request('invoice_status_id',0));
			// 	})
			// 	->when(request('lc_sc_date_from',0), function ($q){
			// 		return $q->where('exp_lc_scs.lc_sc_date', '>=',request('lc_sc_date_from',0));
			// 	})
			// 	->when(request('lc_sc_date_to',0), function ($q){
			// 		return $q->where('exp_lc_scs.lc_sc_date', '<=',request('lc_sc_date_to',0));
			// 	})
			// 	->when(request('exporter_bank_branch_id',0), function ($q){
			// 		return $q->where('exp_lc_scs.exporter_bank_branch_id', '=',request('exporter_bank_branch_id',0));
			// 	})
			// 	->when(request('lc_sc_no',0), function ($q){
			// 		return $q->where('exp_lc_scs.lc_sc_no', 'LIKE',"%".request('lc_sc_no',0)."%");
			// 	})
			// 	->when(request('buyer_id',0), function ($q){
			// 		return $q->where('exp_lc_scs.buyer_id', '=',request('buyer_id',0));
			// 	})
			// 	->when(request('ex_factory_date_from',0), function ($q){
			// 		return $q->where('exp_invoices.ex_factory_date', '>=',request('ex_factory_date_from',0));
			// 	})
			// 	->when(request('ex_factory_date_to',0), function ($q){
			// 		return $q->where('exp_invoices.ex_factory_date', '<=',request('ex_factory_date_to',0));
			// 	})
			// 	// ->when(request('company_id',0), function ($q){
			// 	// return $q->where('jobs.company_id', '=',request('company_id',0));
			// 	// })
			// 	//->where([['exp_invoices.invoice_status_id','=',2]])
			// 	->orderBy('exp_invoices.invoice_date')
			// 	->get([
			// 		'exp_invoices.*',
			// 		'exp_invoices.id as invoice_id',
			// 		'exp_lc_scs.lc_sc_no',
		 //    		'exp_lc_scs.lc_sc_date',
			// 		'exp_lc_scs.local_commission_per',
			// 		'exp_lc_scs.foreign_commission_per',
			// 		'banks.name as lien_bank',
			// 		'buyers.name as buyer_name',
			// 		'expdocsubmission.stuffing_date'
			// 	])
			// 	->map(function($rows) use($deliveryMode){
			// 		$rows->ship_mode_id=$deliveryMode[$rows->ship_mode_id];
			// 		$rows->local_commission_invoice=$rows->invoice_value*($rows->local_commission_per/100);
		 //            $rows->foreign_commission_invoice=$rows->invoice_value*($rows->foreign_commission_per/100);
		 //            $rows->ex_factory_date=$rows->ex_factory_date?date('d-M-Y',strtotime($rows->ex_factory_date)):'--';
			// 		$rows->stuffing_date=$rows->stuffing_date?date('d-M-Y',strtotime($rows->stuffing_date)):'--';
			// 		$rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
			// 		$rows->invoice_date=date('d-M-Y',strtotime($rows->invoice_date));
			// 		if ($rows->invoice_qty) {
			// 			$rows->invoice_rate=$rows->invoice_value/$rows->invoice_qty;
			// 		}
			// 		$rows->net_inv_value=$rows->invoice_value-($rows->bonus_amount+$rows->discount_amount+$rows->claim_amount+$rows->local_commission_invoice+$rows->foreign_commission_invoice+$rows->freight_by_supplier+$rows->freight_by_buyer+$rows->deduction);
			// 		$rows->invoice_qty=number_format($rows->invoice_qty,0);
			// 		$rows->invoice_amount=number_format($rows->invoice_value,2);
			// 		$rows->net_invoice_amount=number_format($rows->net_inv_value,2);
			// 		$rows->invoice_rate=number_format($rows->invoice_rate,2);
		 //            return $rows;
		 //        });

			// 	echo json_encode($rows);
	// }

	public function invoiceDetails(){
		$deliveryMode = array_prepend(config('bprs.deliveryMode'), '-Select-','');
		$rows=$this->expinvoice
		->join('exp_lc_scs', function($join){
			$join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
		})
		->join('currencies', function($join){
			$join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
		})
		->join('bank_branches', function($join) {
			$join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
		})
		->join('banks', function($join) {
			$join->on('banks.id', '=', 'bank_branches.bank_id');
		})
		->join('buyers', function($join)  {
			$join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
		})
		->leftJoin(\DB::raw("(
			select
			exp_invoices.id as exp_invoice_id,
			exp_doc_submissions.id as exp_doc_submission_id,
			exp_doc_submissions.bank_ref_bill_no,
			exp_doc_submissions.bank_ref_date,
			exp_doc_submissions.stuffing_date
			from
			exp_invoices
			join exp_doc_sub_invoices on exp_doc_sub_invoices.exp_invoice_id=exp_invoices.id
			join exp_doc_submissions on exp_doc_submissions.id=exp_doc_sub_invoices.exp_doc_submission_id
			join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id 
			and exp_doc_submissions.exp_lc_sc_id=exp_lc_scs.id
			group by 
			exp_invoices.id,
			exp_doc_submissions.id,
			exp_doc_submissions.bank_ref_bill_no,
			exp_doc_submissions.bank_ref_date,
			exp_doc_submissions.stuffing_date
		) expdocsubmission"), "expdocsubmission.exp_invoice_id", "=", "exp_invoices.id")
		->when(request('invoice_date_from',0), function ($q){
			return $q->where('exp_invoices.invoice_date', '>=',request('invoice_date_from',0));
		})
		->when(request('invoice_date_to',0), function ($q){
			return $q->where('exp_invoices.invoice_date', '<=',request('invoice_date_to',0));
		})
		->when(request('invoice_status_id',0), function ($q){
			return $q->where('exp_invoices.invoice_status_id', '=',request('invoice_status_id',0));
		})
		->when(request('lc_sc_date_from',0), function ($q){
			return $q->where('exp_lc_scs.lc_sc_date', '>=',request('lc_sc_date_from',0));
		})
		->when(request('lc_sc_date_to',0), function ($q){
			return $q->where('exp_lc_scs.lc_sc_date', '<=',request('lc_sc_date_to',0));
		})
		->when(request('ex_factory_date_from',0), function ($q){
			return $q->where('exp_invoices.ex_factory_date', '>=',request('ex_factory_date_from',0));
		})
		->when(request('ex_factory_date_to',0), function ($q){
			return $q->where('exp_invoices.ex_factory_date', '<=',request('ex_factory_date_to',0));
		})
		->when(request('exporter_bank_branch_id',0), function ($q){
			return $q->where('exp_lc_scs.exporter_bank_branch_id', '=',request('exporter_bank_branch_id',0));
		})
		->when(request('lc_sc_no',0), function ($q){
			return $q->where('exp_lc_scs.lc_sc_no', 'LIKE',"%".request('lc_sc_no',0)."%");
		})
		->when(request('buyer_id',0), function ($q){
			return $q->where('exp_lc_scs.buyer_id', '=',request('buyer_id',0));
		})
		->when(request('company_id',0), function ($q){
			return $q->where('exp_lc_scs.beneficiary_id', '=',request('company_id',0));
		})
		//->where([['exp_invoices.invoice_status_id','=',2]])
		->orderBy('exp_invoices.invoice_date')
		->get([
			'exp_invoices.*',
			'exp_invoices.id as invoice_id',
			'exp_lc_scs.lc_sc_no',
			'exp_lc_scs.file_no',
    		'exp_lc_scs.lc_sc_date',
			'exp_lc_scs.local_commission_per',
			'exp_lc_scs.foreign_commission_per',
			'banks.name as lien_bank',
			'buyers.name as buyer_name',
			'expdocsubmission.stuffing_date',
			'expdocsubmission.exp_doc_submission_id',
			'expdocsubmission.bank_ref_bill_no',
			'expdocsubmission.bank_ref_date',
			'currencies.code as currency_code'
		])
		->map(function($rows) use($deliveryMode){
			$rows->ship_mode_id=$deliveryMode[$rows->ship_mode_id];
			$rows->local_commission_invoice=$rows->invoice_value*($rows->local_commission_per/100);
            $rows->foreign_commission_invoice=$rows->invoice_value*($rows->foreign_commission_per/100);
			$rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
			$rows->ex_factory_date=$rows->ex_factory_date?date('d-M-Y',strtotime($rows->ex_factory_date)):'--';
			$rows->stuffing_date=$rows->stuffing_date?date('d-M-Y',strtotime($rows->stuffing_date)):'--';
			$rows->bl_cargo_date=$rows->bl_cargo_date?date('d-M-Y',strtotime($rows->bl_cargo_date)):'--';
			$rows->origin_bl_rev_date=$rows->origin_bl_rev_date?date('d-M-Y',strtotime($rows->origin_bl_rev_date)):'--';
			$rows->ic_recv_date=$rows->ic_recv_date?date('d-M-Y',strtotime($rows->ic_recv_date)):'--';
			$rows->advice_date=$rows->advice_date?date('d-M-Y',strtotime($rows->advice_date)):'--';
			$rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
			$rows->invoice_date=date('d-M-Y',strtotime($rows->invoice_date));
			$rows->bank_ref_date=$rows->bank_ref_date?date('d-M-Y',strtotime($rows->bank_ref_date)):'--';
			$rows->bank_ref_bill_no=$rows->bank_ref_bill_no?$rows->bank_ref_bill_no:'--';
			$rows->bl_cargo_no=$rows->bl_cargo_no?$rows->bl_cargo_no:'--';
			$rows->exp_doc_submission_id=$rows->exp_doc_submission_id?$rows->exp_doc_submission_id:'--';

			if ($rows->invoice_qty) {
				$rows->invoice_rate=$rows->invoice_value/$rows->invoice_qty;
			}
			$rows->net_inv_value=$rows->invoice_value-($rows->bonus_amount+$rows->discount_amount+$rows->claim_amount+$rows->local_commission_invoice+$rows->foreign_commission_invoice+$rows->freight_by_supplier+$rows->freight_by_buyer+$rows->deduction);
			$rows->invoice_qty=number_format($rows->invoice_qty,0);
			$rows->invoice_amount=number_format($rows->invoice_value,2);
			$rows->net_invoice_amount=number_format($rows->net_inv_value,2);
			$rows->invoice_rate=number_format($rows->invoice_rate,2);
            return $rows;
        });

		echo json_encode($rows);
	}
}