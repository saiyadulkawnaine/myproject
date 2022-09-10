<?php

namespace App\Http\Controllers\Report\Commercial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;

class LiabilityCoverageReportController extends Controller
{
	private $accchartctrlhead;
	private $accchartsubgroup;
    private $currency;
    private $bank;
    private $itemaccount; 

	public function __construct(ExpLcScRepository $expsalescontract,CurrencyRepository $currency, CompanyRepository $company, BuyerRepository $buyer,BankRepository $bank,ItemAccountRepository $itemaccount, BudgetFabricRepository $budgetfabric)
    {
		$this->expsalescontract    = $expsalescontract;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->bank = $bank;
        $this->itemaccount = $itemaccount;
        $this->budgetfabric = $budgetfabric;

		$this->middleware('auth');
		$this->middleware('permission:view.liabilitycoveragereports',   ['only' => ['create', 'index','show']]);
    }
    
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
		$bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
    	//$data=$this->getData();
        return Template::loadView('Report.Commercial.LiabilityCoverageReport',[/*'datas'=>$data,*/'company'=>$company,'buyer'=>$buyer,'bank'=>$bank]);
    }

    public function html()
    {
    	$data=$this->getData();
        return Template::loadView('Report.Commercial.LiabilityCoverageReportData',['datas'=>$data]);
    }

    public function htmlgrid()
    {
    	$rows=$this->getData()->map(function ($rows){
        $rows->last_delivery_date=date('d-M-Y',strtotime($rows->last_delivery_date));
        $rows->so_qty=number_format($rows->so_qty,0,'.',',');
        $rows->so_rate=number_format($rows->so_rate,4,'.',',');
        $rows->so_amount=number_format($rows->so_amount,2,'.',',');
        $rows->lc_amount=number_format($rows->lc_amount,2,'.',',');
        $rows->limit_btb_open=number_format($rows->limit_btb_open,2,'.',',');
        $rows->limit_pc_taken=number_format($rows->limit_pc_taken,2,'.',',');
        $rows->limit_doc_pur=number_format($rows->limit_doc_pur,2,'.',',');
        $rows->btb_open_amount=number_format($rows->btb_open_amount,2,'.',',');
        $rows->pc_taken_mount=number_format($rows->pc_taken_mount,2,'.',',');
        $rows->doc_taken_amount=number_format($rows->doc_taken_amount,2,'.',',');
        $rows->yet_btb_open=number_format($rows->yet_btb_open,2,'.',',');
        $rows->yet_pc_taken=number_format($rows->yet_pc_taken,2,'.',',');
        $rows->yet_doc_pur=number_format($rows->yet_doc_pur,2,'.',',');
        $rows->btb_liab_amount=number_format($rows->btb_liab_amount,2,'.',',');
        $rows->pc_liab_amount=number_format($rows->pc_liab_amount,2,'.',',');
        $rows->doc_liab_amount=number_format($rows->doc_liab_amount,2,'.',',');
        $rows->tot_liab_amount=number_format($rows->tot_liab_amount,2,'.',',');
        $rows->sh_qty=number_format($rows->sh_qty,2,'.',',');
        $rows->sh_amount=number_format($rows->sh_amount,2,'.',',');
        $rows->doc_in_process=number_format($rows->doc_in_process,2,'.',',');
        $rows->un_rlz_amount=number_format($rows->un_rlz_amount,2,'.',',');
        $rows->yet_to_ship_amount=number_format($rows->yet_to_ship_amount,2,'.',',');
        $rows->security=number_format($rows->security,2,'.',',');

        $rows->net_realised=number_format($rows->net_realised,2,'.',',');
        $rows->inc_claimable=number_format($rows->inc_claimable,2,'.',',');
        $rows->inc_claimed_usd=number_format($rows->inc_claimed_usd,2,'.',',');
        $rows->inc_claimed_tk=number_format($rows->inc_claimed_tk,2,'.',',');
        $rows->inc_advence_usd=number_format($rows->inc_advence_usd,2,'.',',');
        $rows->inc_advence_tk=number_format($rows->inc_advence_tk,2,'.',',');
        $rows->inc_realised=number_format($rows->inc_realised,2,'.',',');
		return $rows;
		});
    	echo json_encode($rows);
    }
    
    private function getData()
    {
		$rows=$this->expsalescontract
        ->selectRaw('
        exp_lc_scs.file_no,
        max(exp_lc_scs.last_delivery_date) as last_delivery_date,
        buyers.name as buyer_name,
        banks.id as bank_id,
        sum(exp_lc_scs.lc_sc_value) as lc_amount,
        LcScRep.replaced_amount,
        ExpPC.pc_taken_mount,
        ExpSO.so_qty,
        ExpSO.so_amount,
       
        BTB.btb_open_amount,
        BtbAdj.btb_adjust_amount,
        (BTB.btb_open_amount- BtbAdj.btb_adjust_amount) as btb_liab_amount,
        PCAdj1.pc_adj_1_amount,
        PCAdj2.pc_adj_2_amount,
        (ExpPC.pc_taken_mount - (PCAdj1.pc_adj_1_amount+PCAdj2.pc_adj_2_amount)) as pc_liab_amount,
        DocPur.doc_taken_amount,
        DocAdj.doc_adj_amount, 
        (DocPur.doc_taken_amount - DocAdj.doc_adj_amount) as doc_liab_amount,
        ShipOut.sh_qty,
        ShipOut.sh_amount,
        Docsub.doc_sub_amount,
        DocsubBuyer.doc_sub_amount_buyer,
        DocRlzAmt.doc_rlz_amount,
        DocRlzDeduAmt.doc_rlz_dedu_amount,
        IncentiveClaim.net_realized_amount,
        IncentiveClaim.claim_amount,
        IncentiveClaim.claim_amount_tk,
        IncentiveLoan.advance_amount_tk,
        IncentiveLoan.advance_amount_usd
		')
        ->leftJoin('buyers',function($join){
			$join->on('buyers.id','=','exp_lc_scs.buyer_id');
		})
		->leftJoin('bank_branches', function($join) {
			$join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
		})
		->leftJoin('banks', function($join) {
			$join->on('banks.id', '=', 'bank_branches.bank_id');
		})
		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_rep_lc_scs.replaced_amount) as replaced_amount FROM exp_rep_lc_scs right join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   group by exp_lc_scs.file_no) LcScRep"), "LcScRep.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_pi_orders.qty) as so_qty,avg(exp_pi_orders.rate) as so_rate,sum(exp_pi_orders.amount) as so_amount 
			FROM exp_lc_sc_pis 
			left join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
			left join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
			left join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id  
			where exp_pi_orders.deleted_at is null
			group by exp_lc_scs.file_no
		) ExpSO"), "ExpSO.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(imp_backed_exp_lc_scs.amount) as btb_open_amount FROM imp_backed_exp_lc_scs right join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id right join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id    group by exp_lc_scs.file_no) BTB"), "BTB.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(
			SELECT 
			m.file_no,
			sum(imp_liability_adjust_chlds.amount) as btb_adjust_amount
			FROM 
			imp_liability_adjusts
			join imp_liability_adjust_chlds on imp_liability_adjust_chlds.imp_liability_adjust_id = imp_liability_adjusts.id
			join (
			SELECT 
			exp_lc_scs.file_no,
			imp_liability_adjusts.id

			FROM 
			imp_liability_adjusts
			left join imp_liability_adjust_chlds on imp_liability_adjust_chlds.imp_liability_adjust_id = imp_liability_adjusts.id  
			left join imp_doc_accepts on imp_doc_accepts.id =  imp_liability_adjusts.imp_doc_accept_id
			left join imp_backed_exp_lc_scs on imp_doc_accepts.imp_lc_id = imp_backed_exp_lc_scs.imp_lc_id 
			left join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id
			group by 
			exp_lc_scs.file_no,
			imp_liability_adjusts.id
			) m on m.id=imp_liability_adjusts.id
			group by
			m.file_no) BtbAdj"), "BtbAdj.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_doc_sub_transections.doc_value) as pc_adj_1_amount FROM exp_doc_submissions right join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id right join exp_doc_sub_transections on exp_doc_sub_transections.exp_doc_submission_id = exp_doc_submissions.id right join commercial_heads on commercial_heads.id = exp_doc_sub_transections.commercialhead_id where commercial_heads.commercialhead_type_id=5  group by exp_lc_scs.file_no) PCAdj1"), "PCAdj1.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_pro_rlz_amounts.doc_value) as pc_adj_2_amount FROM exp_doc_submissions right join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id right join exp_pro_rlzs on exp_pro_rlzs.exp_doc_submission_id = exp_doc_submissions.id right join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id = exp_pro_rlzs.id right join commercial_heads on commercial_heads.id = exp_pro_rlz_amounts.commercial_head_id where commercial_heads.commercialhead_type_id=5 and exp_pro_rlz_amounts.deleted_at is null  group by exp_lc_scs.file_no) PCAdj2"), "PCAdj2.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_doc_sub_transections.doc_value) as doc_taken_amount FROM exp_doc_submissions right join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id right join exp_doc_sub_transections on exp_doc_sub_transections.exp_doc_submission_id = exp_doc_submissions.id and exp_doc_sub_transections.deleted_at is null  group by exp_lc_scs.file_no) DocPur"), "DocPur.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_pro_rlz_amounts.doc_value) as doc_adj_amount FROM exp_doc_submissions right join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id right join exp_pro_rlzs on exp_pro_rlzs.exp_doc_submission_id = exp_doc_submissions.id right join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id = exp_pro_rlzs.id right join commercial_heads on commercial_heads.id = exp_pro_rlz_amounts.commercial_head_id where commercial_heads.commercialhead_type_id=4 and exp_pro_rlz_amounts.deleted_at is null  group by exp_lc_scs.file_no) DocAdj"), "DocAdj.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_pre_credit_lc_scs.equivalent_fc) as pc_taken_mount FROM exp_pre_credit_lc_scs right join exp_lc_scs on exp_lc_scs.id = exp_pre_credit_lc_scs.exp_lc_sc_id where exp_pre_credit_lc_scs.deleted_at is null  group by exp_lc_scs.file_no) ExpPC"), "ExpPC.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(select 
			exp_lc_scs.file_no,
			sum(exp_invoice_orders.qty) as sh_qty,
			avg(exp_invoice_orders.rate) as sh_rate,
			sum(exp_invoice_orders.amount) as sh_amount
			from exp_invoices
			inner join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id
			inner join exp_pi_orders on exp_pi_orders.id= exp_invoice_orders.exp_pi_order_id 
			inner join sales_orders on exp_pi_orders.sales_order_id = sales_orders.id 
			inner join exp_lc_scs on exp_lc_scs.id = exp_invoices.exp_lc_sc_id 
			left join jobs on jobs.id = sales_orders.job_id 
			left join styles on styles.id = jobs.style_id 
			left join buyers on buyers.id = styles.buyer_id 
			where exp_invoices.deleted_at is null
			and exp_invoice_orders.deleted_at is null
			and exp_invoices.invoice_status_id = 2
			group by 
			exp_lc_scs.file_no) ShipOut"), "ShipOut.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_invoice_orders.amount) as doc_sub_amount FROM exp_doc_submissions right join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id right join exp_doc_sub_invoices on exp_doc_sub_invoices.exp_doc_submission_id = exp_doc_submissions.id right join exp_invoices on exp_invoices.id = exp_doc_sub_invoices.exp_invoice_id right join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id and exp_invoice_orders.deleted_at is null 
			where exp_doc_submissions.doc_submitted_to_id=1 
			and exp_doc_sub_invoices.deleted_at is null  
			and exp_invoices.invoice_status_id = 2
			group by exp_lc_scs.file_no) Docsub"), "Docsub.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_invoice_orders.amount) as doc_sub_amount_buyer FROM exp_doc_submissions right join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id right join exp_doc_sub_invoices on exp_doc_sub_invoices.exp_doc_submission_id = exp_doc_submissions.id right join exp_invoices on exp_invoices.id = exp_doc_sub_invoices.exp_invoice_id right join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id and exp_invoice_orders.deleted_at is null 
			where exp_doc_submissions.doc_submitted_to_id=2 
			and exp_doc_sub_invoices.deleted_at is null 
			and exp_invoices.invoice_status_id = 2 
			group by exp_lc_scs.file_no) DocsubBuyer"), "DocsubBuyer.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_pro_rlz_amounts.doc_value) as doc_rlz_amount 
			FROM exp_doc_submissions 
			right join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id 
			right join exp_pro_rlzs on exp_pro_rlzs.exp_doc_submission_id = exp_doc_submissions.id 
			right join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id = exp_pro_rlzs.id 
			where  exp_pro_rlz_amounts.deleted_at is null  
			group by exp_lc_scs.file_no) DocRlzAmt"), "DocRlzAmt.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(SELECT exp_lc_scs.file_no,sum(exp_pro_rlz_deducts.doc_value) as doc_rlz_dedu_amount FROM exp_doc_submissions right join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id right join exp_pro_rlzs on exp_pro_rlzs.exp_doc_submission_id = exp_doc_submissions.id right join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id = exp_pro_rlzs.id and exp_pro_rlz_deducts.deleted_at is null  where  exp_pro_rlz_deducts.deleted_at is null  group by exp_lc_scs.file_no) DocRlzDeduAmt"), "DocRlzDeduAmt.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(select 
			exp_lc_scs.file_no, 
			sum(cash_incentive_claims.net_realized_amount) as net_realized_amount,
			sum(cash_incentive_claims.claim_amount) as claim_amount, 
			sum(cash_incentive_claims.local_cur_amount) as claim_amount_tk 
			from exp_lc_scs
			join cash_incentive_refs on cash_incentive_refs.exp_lc_sc_id=exp_lc_scs.id
			join cash_incentive_claims on cash_incentive_claims.cash_incentive_ref_id=cash_incentive_refs.id and cash_incentive_claims.deleted_at is null
			
			group by exp_lc_scs.file_no) IncentiveClaim"), "IncentiveClaim.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(select 
			exp_lc_scs.file_no, 
			sum(cash_incentive_loans.advance_amount_tk) as advance_amount_tk , 
			sum(cash_incentive_loans.advance_amount_usd) as advance_amount_usd
			from exp_lc_scs
			join cash_incentive_refs on cash_incentive_refs.exp_lc_sc_id=exp_lc_scs.id
			join cash_incentive_loans on cash_incentive_loans.cash_incentive_ref_id=cash_incentive_refs.id and cash_incentive_loans.deleted_at is null
			group by exp_lc_scs.file_no) IncentiveLoan"), "IncentiveLoan.file_no", "=", "exp_lc_scs.file_no")

		->when(request('lc_sc_no'), function ($q) {
			return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%".request('lc_sc_no', 0)."%");
		})
		->when(request('date_from'), function ($q) {
			return $q->where('exp_lc_scs.lc_sc_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
			return $q->where('exp_lc_scs.lc_sc_date', '<=',request('date_to', 0));
		})
		->when(request('last_delivery_date_from'), function ($q) {
			return $q->where('exp_lc_scs.last_delivery_date', '>=',request('last_delivery_date_from', 0));
		})
		->when(request('last_delivery_date_to'), function ($q) {
			return $q->where('exp_lc_scs.last_delivery_date', '<=',request('last_delivery_date_to', 0));
		})
		->when(request('file_no'), function ($q) {
			return $q->where('exp_lc_scs.file_no', 'LIKE', "%".request('file_no', 0)."%");
		})
		->when(request('beneficiary_id'), function ($q) {
			return $q->where('exp_lc_scs.beneficiary_id', '=', request('beneficiary_id', 0));
		})
		->when(request('buyer_id'), function ($q) {
			return $q->where('exp_lc_scs.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('bank_id'), function ($q) {
			return $q->where('banks.id', '=', request('bank_id', 0));
		})
		->groupBy([
			'exp_lc_scs.file_no',
			'buyers.name',
			'banks.id',
			'LcScRep.replaced_amount',
			'ExpPC.pc_taken_mount',
			'ExpSO.so_qty',
			
			'ExpSO.so_amount',
			'BTB.btb_open_amount',
			'BtbAdj.btb_adjust_amount',
			'PCAdj1.pc_adj_1_amount',
			'PCAdj2.pc_adj_2_amount',
			'DocPur.doc_taken_amount',
			'DocAdj.doc_adj_amount',
			'ShipOut.sh_qty',
            'ShipOut.sh_amount',
            'Docsub.doc_sub_amount',
            'DocsubBuyer.doc_sub_amount_buyer',
            'DocRlzAmt.doc_rlz_amount',
            'DocRlzDeduAmt.doc_rlz_dedu_amount',
            'IncentiveClaim.net_realized_amount',
	        'IncentiveClaim.claim_amount',
	        'IncentiveClaim.claim_amount_tk',
	        'IncentiveLoan.advance_amount_tk',
	        'IncentiveLoan.advance_amount_usd'
		])
		->orderBy('exp_lc_scs.file_no')
		->get()
		->map(function ($rows){
		$rows->so_rate=0;
		if($rows->so_qty){
		$rows->so_rate=$rows->so_amount/$rows->so_qty;
		}
		$rows->lc_amount=$rows->lc_amount-$rows->replaced_amount;

		$rows->btb_liab_amount=$rows->btb_open_amount-$rows->btb_adjust_amount;
		$rows->pc_liab_amount=$rows->pc_taken_mount-($rows->pc_adj_1_amount+$rows->pc_adj_2_amount);
		$rows->doc_liab_amount=$rows->doc_taken_amount-$rows->doc_adj_amount;

		$rows->limit_btb_open=($rows->lc_amount*70)/100;
		if($rows->bank_id==62){
			$rows->limit_pc_taken=($rows->lc_amount*10)/100;
		}
		
		else{
			$rows->limit_pc_taken=($rows->lc_amount*15)/100;
		}
		
		$rows->limit_doc_pur=($rows->lc_amount*5)/100;

		$rows->yet_btb_open=$rows->limit_btb_open-$rows->btb_open_amount;
		$rows->yet_pc_taken=$rows->limit_pc_taken-$rows->pc_taken_mount;
		$rows->yet_doc_pur=$rows->limit_doc_pur-$rows->doc_taken_amount;

		$rows->tot_liab_amount=$rows->btb_liab_amount+$rows->pc_liab_amount+$rows->doc_liab_amount;
		$rows->un_rlz_amount=$rows->doc_sub_amount-($rows->doc_rlz_amount+$rows->doc_rlz_dedu_amount);
        $rows->yet_to_ship_amount=$rows->so_amount-$rows->sh_amount;

        $rows->doc_in_process=$rows->sh_amount-$rows->doc_sub_amount;
        
        

        $rows->last_delivery_date=date('d-M-Y',strtotime($rows->last_delivery_date));
        if(! $rows->doc_in_process){
            $rows->doc_in_process=$rows->sh_amount-$rows->doc_sub_amount_buyer;
        }
        $rows->security=$rows->doc_in_process+$rows->un_rlz_amount+$rows->yet_to_ship_amount;
        if($rows->tot_liab_amount>$rows->security){
        	$rows->comments='Under Risk';
        }
        if($rows->tot_liab_amount<=$rows->security){
        	$rows->comments='Coverd';
        }
        $rows->net_realised=$rows->net_realized_amount;
        $rows->inc_claimable=($rows->net_realized_amount*.8)*.06;
        $rows->inc_claimed_usd=$rows->claim_amount;
        $rows->inc_claimed_tk=$rows->claim_amount_tk;
        $rows->inc_advence_usd=$rows->advance_amount_usd;
        $rows->inc_advence_tk=$rows->advance_amount_tk;
        $rows->inc_realised=0;

		return $rows;
		});

        return $rows;
    }

    // public function order(){
	// 	$results = \DB::select('
	// 		select 
	// 		buyers.name as buyer_name,
	// 		styles.style_ref,
	// 		sales_orders.id,
	// 		sales_orders.sale_order_no,
	// 		sales_orders.ship_date,
	// 		sum(exp_pi_orders.qty) as qty,
	// 		avg(exp_pi_orders.rate) as rate,
	// 		sum(exp_pi_orders.amount) as amount, 
	// 		sh.sh_qty,
	// 		sh.sh_rate,
	// 		sh.sh_amount
	// 		FROM exp_lc_sc_pis 
	// 		left join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
	// 		left join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
	// 		left join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id
	// 		left join sales_orders  on sales_orders.id=exp_pi_orders.sales_order_id
	// 		left join jobs  on jobs.id=sales_orders.job_id
	// 		left join styles  on styles.id=jobs.style_id
	// 		left join buyers  on buyers.id=styles.buyer_id
	// 		left join (
	// 		SELECT sales_orders.id,
	// 		sum(sales_order_gmt_color_sizes.qty) as sh_qty,
	// 		avg(sales_order_gmt_color_sizes.rate) as sh_rate,
	// 		sum(sales_order_gmt_color_sizes.amount) as sh_amount 
	// 		FROM sales_orders 
	// 		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id 
	// 		where sales_orders.order_status=4 and sales_order_gmt_color_sizes.deleted_at is null  
	// 		group by sales_orders.id
	// 		) sh on sh.id = sales_orders.id
	// 		where  exp_lc_scs.file_no=? 
	// 		group by buyers.name,
	// 		styles.style_ref,
	// 		sales_orders.id,
	// 		sales_orders.sale_order_no,
	// 		sales_orders.ship_date,
	// 		sh.sh_qty,
	// 		sh.sh_rate,
	// 		sh.sh_amount
	// 		', [request('file_no',0)]);
	// 	$rows = collect($results);
	// 	$rows->map(function ($rows){

		
	// 	$rows->balance_qty=number_format($rows->qty-$rows->sh_qty,2,'.','');
	// 	$rows->balance_amount=number_format($rows->amount-$rows->sh_amount,2,'.','');
	// 	$rows->qty=number_format($rows->qty,2,'.','');
	// 	$rows->rate=number_format($rows->rate,4,'.','');
	// 	$rows->amount=number_format($rows->amount,2,'.','');
	// 	$rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
	// 	return $rows;
	// 	});
	// 	echo json_encode($rows);
    // }

    public function order(){
		$results = \DB::select('
			select 
				exp_lc_scs.file_no,
				buyers.name as buyer_name,
				styles.style_ref,
				users.id as user_id,
				users.name as team_member_name,
				sales_orders.id,
				sales_orders.sale_order_no,
				sales_orders.ship_date,
				sum(exp_pi_orders.qty) as qty,
				avg(exp_pi_orders.rate) as rate,
				sum(exp_pi_orders.amount) as amount, 
				sh.sh_qty,
				sh.sh_rate,
				sh.sh_amount,
				yarnrq.yarn_req,
				yarnrq.yarn_req_amount,
				trimrq.trim_amount,
				finfabreq.fin_fab_req,
				finfabreq.fin_fab_req_amount

				FROM exp_lc_sc_pis 
				left join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
				left join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
				left join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id
				left join sales_orders  on sales_orders.id=exp_pi_orders.sales_order_id
				left join jobs  on jobs.id=sales_orders.job_id
				left join styles  on styles.id=jobs.style_id
				left join buyers  on buyers.id=styles.buyer_id
				left join teammembers on teammembers.id=styles.factory_merchant_id
				left join users on users.id=teammembers.user_id
				left join (
					SELECT sales_orders.id,
					sum(sales_order_gmt_color_sizes.qty) as sh_qty,
					avg(sales_order_gmt_color_sizes.rate) as sh_rate,
					sum(sales_order_gmt_color_sizes.amount) as sh_amount 
					FROM sales_orders 
					left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id 
					where sales_orders.order_status=4 and sales_order_gmt_color_sizes.deleted_at is null  
					group by sales_orders.id
				) sh on sh.id = sales_orders.id
				left join (
					select
						sales_orders.id as sales_order_id,
						sum(budget_fabric_cons.grey_fab) as fin_fab_req,
						sum(budget_fabric_cons.amount) as fin_fab_req_amount
						from sales_orders 
						join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
						join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
						join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
						join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
						join jobs on jobs.id = sales_orders.job_id 
						join styles on styles.id = jobs.style_id 
						where sales_orders.order_status !=2
						and style_fabrications.material_source_id=1
						and sales_order_gmt_color_sizes.qty > 0
					group by
					sales_orders.id
				)finfabreq on finfabreq.sales_order_id=sales_orders.id
				left join (
					select 
					m.id as sale_order_id,
					sum(m.yarn) as yarn_req,
					sum(m.yarn_amount) as yarn_req_amount  
					from (
						select 
						budget_yarns.id as budget_yarn_id ,
						budget_yarns.ratio,
						budget_yarns.cons,
						budget_yarns.rate,
						budget_yarns.amount,
						sum(budget_fabric_cons.grey_fab) as grey_fab,
						sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,
						(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount,SALES_ORDERS.ID as id  
						from budget_yarns 
						join budget_fabric_cons on budget_yarns.BUDGET_FABRIC_ID=budget_fabric_cons.BUDGET_FABRIC_ID 
						join SALES_ORDER_GMT_COLOR_SIZES on SALES_ORDER_GMT_COLOR_SIZES.id = budget_fabric_cons.SALES_ORDER_GMT_COLOR_SIZE_ID 
						join SALES_ORDERS on SALES_ORDERS.id=SALES_ORDER_GMT_COLOR_SIZES.SALE_ORDER_ID 
						
						group by 
						budget_yarns.id,
						budget_yarns.ratio,
						budget_yarns.cons,
						budget_yarns.rate,
						budget_yarns.amount,
						SALES_ORDERS.ID,
						SALES_ORDERS.SALE_ORDER_NO
					) m 
					group by m.id
				) yarnrq on yarnrq.sale_order_id=sales_orders.id
				left join(
					select 
					sales_orders.id as sales_order_id,
					sum(budget_trim_cons.amount) as trim_amount 
					from sales_orders 
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
					join budget_trim_cons on budget_trim_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
					join budget_trims on budget_trims.id = budget_trim_cons.budget_trim_id
					where sales_order_gmt_color_sizes.qty > 0
					group by sales_orders.id
				)trimrq on trimrq.sales_order_id=sales_orders.id
				where  exp_lc_scs.file_no=?
				and exp_pi_orders.deleted_at is null
				group by 
				exp_lc_scs.file_no,
				buyers.name,
				styles.style_ref,
				users.id,
				users.name,
				sales_orders.id,
				sales_orders.sale_order_no,
				sales_orders.ship_date,
				sh.sh_qty,
				sh.sh_rate,
				sh.sh_amount,
				yarnrq.yarn_req,
				yarnrq.yarn_req_amount,
				trimrq.trim_amount,
				finfabreq.fin_fab_req,
				finfabreq.fin_fab_req_amount
			', [request('file_no',0)]);
		$rows = collect($results);
		$rows->map(function ($rows){
			$rows->balance_qty=number_format($rows->qty-$rows->sh_qty,2,'.','');
			$rows->balance_amount=number_format($rows->amount-$rows->sh_amount,2,'.','');
			$rows->qty=number_format($rows->qty,2,'.','');
			$rows->rate=number_format($rows->rate,4,'.','');
			$rows->amount=number_format($rows->amount,2,'.','');
			$rows->yarn_req=number_format($rows->yarn_req,2,'.','');
			$rows->yarn_req_amount=number_format($rows->yarn_req_amount,2,'.','');
			$rows->trim_amount=number_format($rows->trim_amount,2,'.','');
			$rows->fin_fab_req=number_format($rows->fin_fab_req,2,'.','');
			$rows->fin_fab_req_amount=number_format($rows->fin_fab_req_amount,2,'.','');
			$rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
			return $rows;
		});
		echo json_encode($rows);
    }

    public function getYarnRq(){
		$saleorderid=request('id',0);
		$file=request('file_no',0);
		$yarnDescription=$this->itemaccount
		->leftJoin('item_account_ratios',function($join){
		$join->on('item_account_ratios.item_account_id','=','item_accounts.id');
		})
		->leftJoin('compositions',function($join){
		$join->on('compositions.id','=','item_account_ratios.composition_id');
		})
		->leftJoin('itemclasses',function($join){
		$join->on('itemclasses.id','=','item_accounts.itemclass_id');
		})
		->leftJoin('itemcategories',function($join){
		$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
		})

		->where([['itemcategories.identity','=',1]])
		->orderBy('item_account_ratios.ratio','desc')
		->get([
		'item_accounts.id',
		'compositions.name as composition_name',
		'item_account_ratios.ratio',
		]);

		$itemaccountArr=array();
		$yarnCompositionArr=array();
		foreach($yarnDescription as $row){
		$itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
		$yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
		}

		$yarnDropdown=array();
		foreach($itemaccountArr as $key=>$value){
		$yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
		}

		if ($saleorderid && $file) {
			// $results = \DB::select('
			// select 
			// styles.id as style_id,
			// 	styles.style_ref,
			// 	sales_orders.id,
			// 	sales_orders.sale_order_no,
			// 	yarnrq.item_account_id,
			// 	yarnrq.count,
			// 	yarnrq.symbol,
			// 	yarnrq.yarn_type,
			// 	yarnrq.yarn_req,
			// 	yarnrq.rate,
			// 	yarnrq.req_amount
			// 	from exp_lc_sc_pis 
			// 	left join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
			// 	left join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
			// 	left join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id
			// 	left join sales_orders  on sales_orders.id=exp_pi_orders.sales_order_id
			// 	left join jobs  on jobs.id=sales_orders.job_id
			// 	left join styles  on styles.id=jobs.style_id
			// left join(
			// select
			// 	n.sales_order_id, 
			// 	n.item_account_id,
			// 	n.count,
			// 	n.symbol,
			// 	n.yarn_type,
			// 	sum(n.yarn_req) as yarn_req,
			// 	avg(n.rate) as rate,
			// 	sum(n.req_amount) as req_amount
			// 	from 
			// 	(
			// 		select
			// 		m.sales_order_id, 
			// 		m.id,
			// 		m.ratio,
			// 		m.item_account_id,
			// 		m.count,
			// 		m.symbol,
			// 		m.yarn_type,
			// 		m.grey_fab,
			// 		m.yarn_req,
			// 		m.rate,
			// 		m.req_amount
			// 		from 
			// 		(
			// 		select 
			// 		sales_orders.id as sales_order_id,
			// 		budget_yarns.id,
			// 		budget_yarns.ratio,
			// 		budget_yarns.item_account_id,
			// 		yarncounts.count,
			// 		yarncounts.symbol,
			// 		yarntypes.name as yarn_type,
			
			// 		sum(budget_fabric_cons.grey_fab) as grey_fab,
			// 		(sum(budget_fabric_cons.grey_fab)*budget_yarns.ratio)/100 as yarn_req,
			// 		budget_yarns.rate,
			// 		(sum(budget_fabric_cons.grey_fab)*budget_yarns.ratio)/100*budget_yarns.rate as req_amount
			
			// 		FROM sales_orders 
			// 		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
			// 		join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
			// 		join budget_yarns on budget_yarns.budget_fabric_id = budget_fabric_cons.budget_fabric_id
			// 		join item_accounts on item_accounts.id = budget_yarns.item_account_id
			// 		join yarncounts on yarncounts.id = item_accounts.yarncount_id
			// 		join yarntypes on yarntypes.id = item_accounts.yarntype_id
			// 		join itemclasses on itemclasses.id = item_accounts.itemclass_id
			// 		join itemcategories on itemcategories.id = item_accounts.itemcategory_id
			// 		join jobs on jobs.id = sales_orders.job_id 
			// 		join styles on styles.id = jobs.style_id 
			// 		where sales_orders.order_status !=2
			// 		group by 
			// 		sales_orders.id,
			// 		sales_orders.sale_order_no,
			// 		budget_yarns.id,
			// 		budget_yarns.ratio,
			// 		budget_yarns.rate,
			// 		budget_yarns.item_account_id,
			// 		yarncounts.count,
			// 		yarncounts.symbol,
			// 		yarntypes.name
			// 		order by sales_orders.id
			// 		) m 
			// 	) n 
			// 	group by 
			// 	n.sales_order_id, 
			// 	n.item_account_id,
			// 	n.count,
			// 	n.symbol,
			// 	n.yarn_type) yarnrq on yarnrq.sales_order_id=sales_orders.id
			// where  exp_lc_scs.file_no=? 
			// and sales_orders.id=? 
			// and sales_orders.order_status !=2
			// group by 
			// styles.id,
			// 	styles.style_ref,
			// 	sales_orders.id,
			// 	sales_orders.sale_order_no,
			// 	yarnrq.item_account_id,
			// 	yarnrq.count,
			// 	yarnrq.symbol,
			// 	yarnrq.yarn_type,
			// 	yarnrq.yarn_req,
			// 	yarnrq.rate,
			// 	yarnrq.req_amount
			// order by 
			// styles.id,
			// sales_orders.id
			// ', [request('file_no',0),request('id',0)]);

			$results = \DB::select('
			select 
            yarnrq.item_account_id,
            yarnrq.count,
            yarnrq.symbol,
            yarnrq.yarn_type,
            sum(yarnrq.yarn_req) as yarn_req,
            avg(yarnrq.rate) as rate,
            sum(yarnrq.req_amount) as req_amount,
            sum(poyarn.po_qty) as po_qty,
            sum(poyarn.po_amount) as po_amount,
            sum(lcyarn.lc_qty) as lc_qty,
            sum(lcyarn.lc_amount) as lc_amount
            from exp_lc_sc_pis 
            join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
            join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
            join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id
            join sales_orders  on sales_orders.id=exp_pi_orders.sales_order_id      
            join jobs  on jobs.id=sales_orders.job_id
            join styles  on styles.id=jobs.style_id
			left join(
                select
				n.sales_order_id, 
				n.item_account_id,
				n.count,
				n.symbol,
				n.yarn_type,
				sum(n.yarn_req) as yarn_req,
				avg(n.rate) as rate,
				sum(n.req_amount) as req_amount
				from 
				(
					select
					m.sales_order_id, 
					m.id,
					m.ratio,
					m.item_account_id,
					m.count,
					m.symbol,
					m.yarn_type,
					m.grey_fab,
					m.yarn_req,
					m.rate,
					m.req_amount
					from 
					(
					select 
					sales_orders.id as sales_order_id,
					budget_yarns.id,
					budget_yarns.ratio,
					budget_yarns.item_account_id,
					yarncounts.count,
					yarncounts.symbol,
					yarntypes.name as yarn_type,
			
					sum(budget_fabric_cons.grey_fab) as grey_fab,
					(sum(budget_fabric_cons.grey_fab)*budget_yarns.ratio)/100 as yarn_req,
					budget_yarns.rate,
					(sum(budget_fabric_cons.grey_fab)*budget_yarns.ratio)/100*budget_yarns.rate as req_amount
			
					FROM sales_orders 
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
					join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
					join budget_yarns on budget_yarns.budget_fabric_id = budget_fabric_cons.budget_fabric_id
					join item_accounts on item_accounts.id = budget_yarns.item_account_id
					join yarncounts on yarncounts.id = item_accounts.yarncount_id
					join yarntypes on yarntypes.id = item_accounts.yarntype_id
					join itemclasses on itemclasses.id = item_accounts.itemclass_id
					join itemcategories on itemcategories.id = item_accounts.itemcategory_id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where sales_orders.order_status !=2 
					group by 
					sales_orders.id,
					sales_orders.sale_order_no,
					budget_yarns.id,
					budget_yarns.ratio,
					budget_yarns.rate,
					budget_yarns.item_account_id,
					yarncounts.count,
					yarncounts.symbol,
					yarntypes.name
					order by sales_orders.id
					) m 
				) n 
				group by 
				n.sales_order_id, 
				n.item_account_id,
				n.count,
				n.symbol,
				n.yarn_type
			) yarnrq on yarnrq.sales_order_id=sales_orders.id
			left join(
                select
               sales_orders.id as sales_order_id,
                budget_yarns.item_account_id,
                sum(po_yarn_item_bom_qties.qty) as po_qty,
                sum(po_yarn_item_bom_qties.amount) as po_amount
                from
                sales_orders
                join po_yarn_item_bom_qties on sales_orders.id=po_yarn_item_bom_qties.sale_order_id
                join budget_yarns on budget_yarns.id = po_yarn_item_bom_qties.budget_yarn_id
                join item_accounts on item_accounts.id = budget_yarns.item_account_id
                join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
                join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
                where po_yarn_item_bom_qties.deleted_at is null
                and po_yarn_items.deleted_at is null
                and po_yarns.deleted_at is null and sales_orders.order_status !=2
                group by 
                sales_orders.id,
                budget_yarns.item_account_id
			)poyarn on 
			poyarn.sales_order_id=sales_orders.id and 
			yarnrq.item_account_id=poyarn.item_account_id
			left join(
                SELECT    
                po_yarn_item_bom_qties.sale_order_id,
                budget_yarns.item_account_id,
                sum(po_yarn_item_bom_qties.qty) as lc_qty,
                sum(po_yarn_item_bom_qties.amount) as lc_amount
                from
                sales_orders
                join po_yarn_item_bom_qties on sales_orders.id=po_yarn_item_bom_qties.sale_order_id
                join budget_yarns on budget_yarns.id = po_yarn_item_bom_qties.budget_yarn_id
                join item_accounts on item_accounts.id = budget_yarns.item_account_id
                join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
                join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
                join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
                join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id

                join jobs on jobs.id = sales_orders.job_id 
                join styles on styles.id = jobs.style_id
                where imp_lcs.menu_id=3 and sales_orders.order_status !=2
                    and po_yarn_item_bom_qties.deleted_at is null
                    and po_yarn_items.deleted_at is null
                    and po_yarns.deleted_at is null
                    and imp_lc_pos.deleted_at is null
                    and imp_lcs.deleted_at is null
                group by
               po_yarn_item_bom_qties.sale_order_id,
                budget_yarns.item_account_id
			)lcyarn on 
			lcyarn.sale_order_id=sales_orders.id and 
			lcyarn.item_account_id=yarnrq.item_account_id
			where  exp_lc_scs.file_no=? 
			and sales_orders.id=?
			and sales_orders.order_status !=2 
			and yarnrq.item_account_id is not null
			and exp_pi_orders.deleted_at is null
			group by 
				yarnrq.item_account_id,
				yarnrq.count,
				yarnrq.symbol,
				yarnrq.yarn_type
			', [request('file_no',0),request('id',0)]);
		}
		if(request('file_no',0) && !request('id',0)) {
			$results = \DB::select('
			select 
            yarnrq.item_account_id,
            yarnrq.count,
            yarnrq.symbol,
            yarnrq.yarn_type,
            sum(yarnrq.yarn_req) as yarn_req,
            avg(yarnrq.rate) as rate,
            sum(yarnrq.req_amount) as req_amount,
            sum(poyarn.po_qty) as po_qty,
            sum(poyarn.po_amount) as po_amount,
            sum(lcyarn.lc_qty) as lc_qty,
            sum(lcyarn.lc_amount) as lc_amount
            from exp_lc_sc_pis 
            join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
            join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
            join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id
            join sales_orders  on sales_orders.id=exp_pi_orders.sales_order_id      
            join jobs  on jobs.id=sales_orders.job_id
            join styles  on styles.id=jobs.style_id
			left join(
                select
				n.sales_order_id, 
				n.item_account_id,
				n.count,
				n.symbol,
				n.yarn_type,
				sum(n.yarn_req) as yarn_req,
				avg(n.rate) as rate,
				sum(n.req_amount) as req_amount
				from 
				(
					select
					m.sales_order_id, 
					m.id,
					m.ratio,
					m.item_account_id,
					m.count,
					m.symbol,
					m.yarn_type,
					m.grey_fab,
					m.yarn_req,
					m.rate,
					m.req_amount
					from 
					(
					select 
					sales_orders.id as sales_order_id,
					budget_yarns.id,
					budget_yarns.ratio,
					budget_yarns.item_account_id,
					yarncounts.count,
					yarncounts.symbol,
					yarntypes.name as yarn_type,
			
					sum(budget_fabric_cons.grey_fab) as grey_fab,
					(sum(budget_fabric_cons.grey_fab)*budget_yarns.ratio)/100 as yarn_req,
					budget_yarns.rate,
					(sum(budget_fabric_cons.grey_fab)*budget_yarns.ratio)/100*budget_yarns.rate as req_amount
			
					FROM sales_orders 
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
					join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
					join budget_yarns on budget_yarns.budget_fabric_id = budget_fabric_cons.budget_fabric_id
					join item_accounts on item_accounts.id = budget_yarns.item_account_id
					join yarncounts on yarncounts.id = item_accounts.yarncount_id
					join yarntypes on yarntypes.id = item_accounts.yarntype_id
					join itemclasses on itemclasses.id = item_accounts.itemclass_id
					join itemcategories on itemcategories.id = item_accounts.itemcategory_id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where sales_orders.order_status !=2 
					group by 
					sales_orders.id,
					sales_orders.sale_order_no,
					budget_yarns.id,
					budget_yarns.ratio,
					budget_yarns.rate,
					budget_yarns.item_account_id,
					yarncounts.count,
					yarncounts.symbol,
					yarntypes.name
					order by sales_orders.id
					) m 
				) n 
				group by 
				n.sales_order_id, 
				n.item_account_id,
				n.count,
				n.symbol,
				n.yarn_type
			) yarnrq on yarnrq.sales_order_id=sales_orders.id
			left join(
                select
               sales_orders.id as sales_order_id,
                budget_yarns.item_account_id,
                sum(po_yarn_item_bom_qties.qty) as po_qty,
                sum(po_yarn_item_bom_qties.amount) as po_amount
                from
                sales_orders
                join po_yarn_item_bom_qties on sales_orders.id=po_yarn_item_bom_qties.sale_order_id
                join budget_yarns on budget_yarns.id = po_yarn_item_bom_qties.budget_yarn_id
                join item_accounts on item_accounts.id = budget_yarns.item_account_id
                join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
                join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
                where po_yarn_item_bom_qties.deleted_at is null
                and po_yarn_items.deleted_at is null
                and po_yarns.deleted_at is null and sales_orders.order_status !=2
                group by 
                sales_orders.id,
                budget_yarns.item_account_id
			)poyarn on 
			poyarn.sales_order_id=sales_orders.id and 
			yarnrq.item_account_id=poyarn.item_account_id
			left join(
                SELECT    
                po_yarn_item_bom_qties.sale_order_id,
                budget_yarns.item_account_id,
                sum(po_yarn_item_bom_qties.qty) as lc_qty,
                sum(po_yarn_item_bom_qties.amount) as lc_amount
                from
                sales_orders
                join po_yarn_item_bom_qties on sales_orders.id=po_yarn_item_bom_qties.sale_order_id
                join budget_yarns on budget_yarns.id = po_yarn_item_bom_qties.budget_yarn_id
                join item_accounts on item_accounts.id = budget_yarns.item_account_id
                join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
                join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
                join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
                join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id

                join jobs on jobs.id = sales_orders.job_id 
                join styles on styles.id = jobs.style_id
                where imp_lcs.menu_id=3 and sales_orders.order_status !=2
                    and po_yarn_item_bom_qties.deleted_at is null
                    and po_yarn_items.deleted_at is null
                    and po_yarns.deleted_at is null
                    and imp_lc_pos.deleted_at is null
                    and imp_lcs.deleted_at is null
                group by
               po_yarn_item_bom_qties.sale_order_id,
                budget_yarns.item_account_id
			)lcyarn on 
			lcyarn.sale_order_id=sales_orders.id and 
			lcyarn.item_account_id=yarnrq.item_account_id
			where  exp_lc_scs.file_no=? 
			and sales_orders.order_status !=2 
			and yarnrq.item_account_id is not null
			and exp_pi_orders.deleted_at is null
			group by 
				yarnrq.item_account_id,
				yarnrq.count,
				yarnrq.symbol,
				yarnrq.yarn_type
			', [request('file_no',0)]);

			
		}
		$rows = collect($results);
		$rows->map(function ($rows) use($yarnDropdown,$file){
			$rows->file_no=$file;
			$rows->count_name=$rows->count."/".$rows->symbol;
			$rows->composition=isset($yarnDropdown[$rows->item_account_id])?$yarnDropdown[$rows->item_account_id]:'';
			$rows->yarn_des=$rows->count_name." ".$rows->composition." ".$rows->yarn_type;
			$rows->po_bal_qty=$rows->yarn_req-$rows->po_qty;
			$rows->po_bal_amount=$rows->req_amount-$rows->po_amount;
			$rows->lc_bal_qty=$rows->yarn_req-$rows->lc_qty;
			$rows->lc_bal_amount=$rows->req_amount-$rows->lc_amount;
			$rows->po_bal_qty=number_format($rows->po_bal_qty,4,'.','');
			$rows->po_bal_amount=number_format($rows->po_bal_amount,2,'.','');
			$rows->lc_bal_qty=number_format($rows->lc_bal_qty,2,'.','');
			$rows->lc_bal_amount=number_format($rows->lc_bal_amount,2,'.','');
			$rows->rate=number_format($rows->rate,4,'.','');
			$rows->yarn_req=number_format($rows->yarn_req,2,'.','');
			$rows->req_amount=number_format($rows->req_amount,2,'.','');
			$rows->po_qty=number_format($rows->po_qty,2,'.','');
			$rows->po_amount=number_format($rows->po_amount,2,'.','');
			$rows->lc_qty=number_format($rows->lc_qty,2,'.','');
			$rows->lc_amount=number_format($rows->lc_amount,2,'.','');
			return $rows;
		});
		echo json_encode($rows);
		
	}

	public function getFinFabRq(){
		$saleorderid=request('id',0);
		$file=request('file_no',0);
		$materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
		$fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		$fabricDescription=$this->budgetfabric
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->join('autoyarns',function($join){
		$join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
		})
		->join('autoyarnratios',function($join){
			$join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
		})
		->join('compositions',function($join){
			$join->on('compositions.id','=','autoyarnratios.composition_id');
		})
		->join('constructions',function($join){
			$join->on('constructions.id','=','autoyarns.construction_id');
		})
		->get([
		'style_fabrications.id',
		'constructions.name as construction',
		'autoyarnratios.composition_id',
		'compositions.name',
		'autoyarnratios.ratio',
		]);
		$fabricDescriptionArr=array();
		$fabricCompositionArr=array();
		foreach($fabricDescription as $row){
			$fabricDescriptionArr[$row->id]=$row->construction;
			$fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
		}
		
		$desDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
			$desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}

		if ($saleorderid && $file) {

			$results = \DB::select('
			select 
			finfabrq.style_fabrication_id,
			finfabrq.material_source_id,
			finfabrq.fabric_nature_id,
			finfabrq.fabric_look_id,
			finfabrq.fabric_shape_id,
			finfabrq.gmtspart_name,
			finfabrq.item_description,
			finfabrq.uom_name,
			finfabrq.gsm_weight,
			sum(finfabrq.fin_fab_req) as fin_fab_req,
			sum(finfabrq.fin_fab_req_amount) as fin_fab_req_amount,
			avg(finfabrq.fin_fab_rate) as fin_fab_rate,
			sum(pofabric.po_qty) as po_qty,
			sum(pofabric.po_amount) as po_amount,
			sum(lcfabric.lc_qty) as lc_qty,
			sum(lcfabric.lc_amount) as lc_amount
			from exp_lc_sc_pis 
			join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
			join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
			join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id
			join sales_orders  on sales_orders.id=exp_pi_orders.sales_order_id      
			join jobs  on jobs.id=sales_orders.job_id
			join styles  on styles.id=jobs.style_id
			left join(
				select
				sales_orders.id as sales_order_id,
				style_fabrications.id as style_fabrication_id,
				style_fabrications.material_source_id,
				style_fabrications.fabric_nature_id,
				style_fabrications.fabric_look_id,
				style_fabrications.fabric_shape_id,
				style_fabrications.gmtspart_id,
				gmtsparts.name as gmtspart_name,
				item_accounts.item_description,
				uoms.code as uom_name,
				budget_fabrics.gsm_weight,
				sum(budget_fabric_cons.grey_fab) as fin_fab_req,
				sum(budget_fabric_cons.amount) as fin_fab_req_amount,
				avg(budget_fabric_cons.rate) as fin_fab_rate
				from sales_orders 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
				join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
				join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
				join style_gmts on style_gmts.id = style_fabrications.style_gmt_id 
				join item_accounts on item_accounts.id = style_gmts.item_account_id
				join gmtsparts on gmtsparts.id = style_fabrications.gmtspart_id 
				join uoms on uoms.id=style_fabrications.uom_id
				where sales_orders.order_status !=2
				and style_fabrications.material_source_id=1
				and sales_order_gmt_color_sizes.qty > 0
				group by 
				sales_orders.id,
				style_fabrications.id,
				style_fabrications.material_source_id,
				style_fabrications.fabric_nature_id,
				style_fabrications.fabric_look_id,
				style_fabrications.fabric_shape_id,
				style_fabrications.gmtspart_id,
				gmtsparts.name,
				item_accounts.item_description,
				uoms.code,
				budget_fabrics.gsm_weight
			) finfabrq on finfabrq.sales_order_id=sales_orders.id
			left join (
				select
				sales_orders.id as sales_order_id,
				style_fabrications.id as style_fabrication_id,
				budget_fabrics.gsm_weight,
				sum(po_fabric_item_qties.qty) as po_qty,
				avg(po_fabric_item_qties.amount) as po_amount
				from sales_orders 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
				join po_fabric_item_qties on po_fabric_item_qties.budget_fabric_con_id = budget_fabric_cons.id
				join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
				join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
				where sales_orders.order_status !=2
				and style_fabrications.material_source_id=1
				and sales_order_gmt_color_sizes.qty > 0
				group by 
				sales_orders.id,
				style_fabrications.id,
				budget_fabrics.gsm_weight
			) pofabric on pofabric.sales_order_id=sales_orders.id
			and pofabric.style_fabrication_id=finfabrq.style_fabrication_id 
			and pofabric.gsm_weight=finfabrq.gsm_weight
			left join (
				select
				sales_orders.id as sales_order_id,
				style_fabrications.id as style_fabrication_id,
				budget_fabrics.gsm_weight,
				sum(po_fabric_item_qties.qty) as lc_qty,
				avg(po_fabric_item_qties.amount) as lc_amount
				from sales_orders 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
				join po_fabric_item_qties on po_fabric_item_qties.budget_fabric_con_id = budget_fabric_cons.id
				join po_fabric_items on po_fabric_items.id=po_fabric_item_qties.po_fabric_item_id
				join po_fabrics on po_fabrics.id=po_fabric_items.po_fabric_id
				join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
				join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
				join imp_lc_pos on imp_lc_pos.purchase_order_id=po_fabrics.id
				join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
				where sales_orders.order_status !=2
				and style_fabrications.material_source_id=1
				and sales_order_gmt_color_sizes.qty > 0
				and imp_lcs.menu_id=1
				group by 
				sales_orders.id,
				style_fabrications.id,
				budget_fabrics.gsm_weight
			) lcfabric on lcfabric.sales_order_id=sales_orders.id
			and lcfabric.style_fabrication_id=finfabrq.style_fabrication_id 
			and lcfabric.gsm_weight=finfabrq.gsm_weight
			where  exp_lc_scs.file_no=? 
			and sales_orders.id=?
			and sales_orders.order_status !=2 
			and exp_pi_orders.deleted_at is null
			group by 
			finfabrq.style_fabrication_id,
			finfabrq.material_source_id,
			finfabrq.fabric_nature_id,
			finfabrq.fabric_look_id,
			finfabrq.fabric_shape_id,
			finfabrq.gmtspart_name,
			finfabrq.item_description,
			finfabrq.uom_name,
			finfabrq.gsm_weight
			', [request('file_no',0),request('id',0)]);
		}
		if(request('file_no',0) && !request('id',0)) {
			$results = \DB::select('
			select 
			finfabrq.style_fabrication_id,
			finfabrq.material_source_id,
			finfabrq.fabric_nature_id,
			finfabrq.fabric_look_id,
			finfabrq.fabric_shape_id,
			finfabrq.gmtspart_name,
			finfabrq.item_description,
			finfabrq.uom_name,
			finfabrq.gsm_weight,
			sum(finfabrq.fin_fab_req) as fin_fab_req,
			sum(finfabrq.fin_fab_req_amount) as fin_fab_req_amount,
			avg(finfabrq.fin_fab_rate) as fin_fab_rate,
			sum(pofabric.po_qty) as po_qty,
			sum(pofabric.po_amount) as po_amount,
			sum(lcfabric.lc_qty) as lc_qty,
			sum(lcfabric.lc_amount) as lc_amount
			from exp_lc_sc_pis 
			join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
			join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
			join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id
			join sales_orders  on sales_orders.id=exp_pi_orders.sales_order_id      
			join jobs  on jobs.id=sales_orders.job_id
			join styles  on styles.id=jobs.style_id
			left join(
				select
				sales_orders.id as sales_order_id,
				style_fabrications.id as style_fabrication_id,
				style_fabrications.material_source_id,
				style_fabrications.fabric_nature_id,
				style_fabrications.fabric_look_id,
				style_fabrications.fabric_shape_id,
				style_fabrications.gmtspart_id,
				gmtsparts.name as gmtspart_name,
				item_accounts.item_description,
				uoms.code as uom_name,
				budget_fabrics.gsm_weight,
				sum(budget_fabric_cons.grey_fab) as fin_fab_req,
				sum(budget_fabric_cons.amount) as fin_fab_req_amount,
				avg(budget_fabric_cons.rate) as fin_fab_rate
				from sales_orders 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
				join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
				join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
				join style_gmts on style_gmts.id = style_fabrications.style_gmt_id 
				join item_accounts on item_accounts.id = style_gmts.item_account_id
				join gmtsparts on gmtsparts.id = style_fabrications.gmtspart_id 
				join uoms on uoms.id=style_fabrications.uom_id
				where sales_orders.order_status !=2
				and style_fabrications.material_source_id=1
				and sales_order_gmt_color_sizes.qty > 0
				group by 
				sales_orders.id,
				style_fabrications.id,
				style_fabrications.material_source_id,
				style_fabrications.fabric_nature_id,
				style_fabrications.fabric_look_id,
				style_fabrications.fabric_shape_id,
				style_fabrications.gmtspart_id,
				gmtsparts.name,
				item_accounts.item_description,
				uoms.code,
				budget_fabrics.gsm_weight
			) finfabrq on finfabrq.sales_order_id=sales_orders.id
			left join (
				select
				sales_orders.id as sales_order_id,
				style_fabrications.id as style_fabrication_id,
				budget_fabrics.gsm_weight,
				sum(po_fabric_item_qties.qty) as po_qty,
				avg(po_fabric_item_qties.amount) as po_amount
				from sales_orders 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
				join po_fabric_item_qties on po_fabric_item_qties.budget_fabric_con_id = budget_fabric_cons.id
				join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
				join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
				where sales_orders.order_status !=2
				and style_fabrications.material_source_id=1
				and sales_order_gmt_color_sizes.qty > 0
				group by 
				sales_orders.id,
				style_fabrications.id,
				budget_fabrics.gsm_weight
			) pofabric on pofabric.sales_order_id=sales_orders.id
			and pofabric.style_fabrication_id=finfabrq.style_fabrication_id 
			and pofabric.gsm_weight=finfabrq.gsm_weight
			left join (
				select
				sales_orders.id as sales_order_id,
				style_fabrications.id as style_fabrication_id,
				budget_fabrics.gsm_weight,
				sum(po_fabric_item_qties.qty) as lc_qty,
				avg(po_fabric_item_qties.amount) as lc_amount
				from sales_orders 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
				join po_fabric_item_qties on po_fabric_item_qties.budget_fabric_con_id = budget_fabric_cons.id
				join po_fabric_items on po_fabric_items.id=po_fabric_item_qties.po_fabric_item_id
				join po_fabrics on po_fabrics.id=po_fabric_items.po_fabric_id
				join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
				join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
				join imp_lc_pos on imp_lc_pos.purchase_order_id=po_fabrics.id
				join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
				where sales_orders.order_status !=2
				and style_fabrications.material_source_id=1
				and sales_order_gmt_color_sizes.qty > 0
				and imp_lcs.menu_id=1
				group by 
				sales_orders.id,
				style_fabrications.id,
				budget_fabrics.gsm_weight
			) lcfabric on lcfabric.sales_order_id=sales_orders.id
			and lcfabric.style_fabrication_id=finfabrq.style_fabrication_id 
			and lcfabric.gsm_weight=finfabrq.gsm_weight
			where  exp_lc_scs.file_no=? 
			and sales_orders.order_status !=2 
			and exp_pi_orders.deleted_at is null
			group by 
			finfabrq.style_fabrication_id,
			finfabrq.material_source_id,
			finfabrq.fabric_nature_id,
			finfabrq.fabric_look_id,
			finfabrq.fabric_shape_id,
			finfabrq.gmtspart_name,
			finfabrq.item_description,
			finfabrq.uom_name,
			finfabrq.gsm_weight
			', [request('file_no',0)]);

			
		}
		$rows = collect($results);
		$rows->map(function ($rows) use($desDropdown,$materialsourcing,$fabricnature,$fabriclooks,$fabricshape,$file){
			$rows->file_no=$file;
			$rows->fabrication =  isset($desDropdown[$rows->style_fabrication_id])?$desDropdown[$rows->style_fabrication_id]:'';
			$rows->materialsourcing =  $materialsourcing[$rows->material_source_id];
			$rows->fabricnature =  isset($fabricnature[$rows->fabric_nature_id])?$fabricnature[$rows->fabric_nature_id]:'';
			$rows->fabriclooks = isset($fabriclooks[$rows->fabric_look_id])?$fabriclooks[$rows->fabric_look_id]:'';
			$rows->fabricshape = isset($fabricshape[$rows->fabric_shape_id])?$fabricshape[$rows->fabric_shape_id]:'';
			$rows->po_bal_qty=$rows->fin_fab_req-$rows->po_qty;
			$rows->po_bal_amount=$rows->fin_fab_req_amount-$rows->po_amount;
			$rows->lc_bal_qty=$rows->fin_fab_req-$rows->lc_qty;
			$rows->lc_bal_amount=$rows->fin_fab_req_amount-$rows->lc_amount;
			$rows->po_bal_qty=number_format($rows->po_bal_qty,4,'.','');
			$rows->po_bal_amount=number_format($rows->po_bal_amount,2,'.','');
			$rows->lc_bal_qty=number_format($rows->lc_bal_qty,2,'.','');
			$rows->lc_bal_amount=number_format($rows->lc_bal_amount,2,'.','');
			$rows->rate=number_format($rows->fin_fab_rate,4,'.','');
			$rows->fin_fab_req=number_format($rows->fin_fab_req,2,'.','');
			$rows->fin_fab_req_amount=number_format($rows->fin_fab_req_amount,2,'.','');
			$rows->po_qty=number_format($rows->po_qty,2,'.','');
			$rows->po_amount=number_format($rows->po_amount,2,'.','');
			$rows->lc_qty=number_format($rows->lc_qty,2,'.','');
			$rows->lc_amount=number_format($rows->lc_amount,2,'.','');
			return $rows;
		});
		echo json_encode($rows);
		
	}


    public function getInvoiceQty(){
		$results = \DB::select('
			select 
			exp_lc_scs.file_no,
			buyers.name as buyer_name,
			banks.name as lien_bank,
			companies.code as company_id,
			exp_lc_scs.lc_sc_no,
			exp_lc_scs.lc_sc_date,
			exp_invoices.invoice_no,
			exp_invoices.invoice_date,
			sum(exp_invoice_orders.qty) as invoice_qty,
			avg(exp_invoice_orders.rate) as invoice_rate,
			sum(exp_invoice_orders.amount) as invoice_amount
			from exp_lc_scs
			inner join exp_invoices on exp_invoices.exp_lc_sc_id = exp_lc_scs.id and exp_invoices.deleted_at is null

			inner join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id
			inner join exp_pi_orders on exp_pi_orders.id= exp_invoice_orders.exp_pi_order_id 
			inner join sales_orders on exp_pi_orders.sales_order_id = sales_orders.id 
			left join jobs on jobs.id = sales_orders.job_id 
			left join styles on styles.id = jobs.style_id 
			left join buyers on buyers.id = styles.buyer_id 
			left join companies on companies.id=jobs.company_id
			left join bank_branches on bank_branches.id=exp_lc_scs.exporter_bank_branch_id
			left join banks on banks.id=bank_branches.bank_id
			where exp_lc_scs.file_no=?
			and exp_invoices.invoice_status_id = 2
			and exp_invoice_orders.deleted_at is null
			group by 
			exp_lc_scs.file_no,
			buyers.name,
			banks.name,
			companies.code,
			exp_lc_scs.lc_sc_no,
			exp_lc_scs.lc_sc_date,
			exp_invoices.id,
			exp_invoices.invoice_no,
			exp_invoices.invoice_date
			order by exp_invoices.id
			', [request('file_no',0)]);
		$rows = collect($results);
		$rows->map(function ($rows){
		$rows->invoice_qty=number_format($rows->invoice_qty,2,'.','');
		$rows->invoice_rate=number_format($rows->invoice_rate,4,'.','');
		$rows->invoice_amount=number_format($rows->invoice_amount,2,'.','');
		$rows->invoice_date=date('d-M-Y',strtotime($rows->invoice_date));
		$rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
		return $rows;
		});
		echo json_encode($rows);
    }

    public function lcsc(){
		$results = \DB::select('select exp_lc_scs.id,exp_lc_scs.file_no,exp_lc_scs.lc_sc_nature_id, exp_lc_scs.lc_sc_no,buyers.name as buyer_name,
		exp_lc_scs.lc_sc_date,exp_lc_scs.lc_sc_value as amount,exp_lc_scs.last_delivery_date,
		min(exp_rep_lc_scs.replaced_lc_sc_id) as replaced_lc_sc_id
			FROM exp_lc_scs 
			left join buyers on buyers.id=exp_lc_scs.buyer_id 
			left join exp_rep_lc_scs on exp_rep_lc_scs.exp_lc_sc_id=exp_lc_scs.id 
			where exp_lc_scs.file_no=? 
			group by
			exp_lc_scs.id,exp_lc_scs.file_no,exp_lc_scs.lc_sc_nature_id, exp_lc_scs.lc_sc_no,buyers.name ,
			exp_lc_scs.lc_sc_date,exp_lc_scs.lc_sc_value,exp_lc_scs.last_delivery_date

			order by exp_lc_scs.id', [request('file_no',0)]);
		$rows = collect($results)
		->map(function ($rows){
		$rows->amount=number_format($rows->amount,2,'.','');
		$rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
		$rows->last_delivery_date=date('d-M-Y',strtotime($rows->last_delivery_date));
		return $rows;
		});

		$ReplaceableSalesContract = $rows->filter(function ($value) {
            if(($value->lc_sc_nature_id==2 || $value->lc_sc_nature_id==3) && !$value->replaced_lc_sc_id){
                return $value;
            }
        })->values();

        $direct = $rows->filter(function ($value) {
            if($value->lc_sc_nature_id==1){
                return $value;
            }
        })->values();

         $Replaced = $rows->filter(function ($value) {
            if($value->replaced_lc_sc_id){
                return $value;
            }
        })->values();


		echo json_encode(['ReplaceableSalesContract'=>$ReplaceableSalesContract,'direct'=>$direct,'Replaced'=>$Replaced]);
    }

    public function btbopen(){
		$payterm = array_prepend(config('bprs.payterm'), '-Select-','');
		$results = \DB::select("
			select 
			imp_lcs.id,
			imp_lcs.lc_no_i,
			imp_lcs.lc_no_ii,
			imp_lcs.lc_no_iii,
			imp_lcs.lc_no_iv,
			imp_lcs.lc_date,
			banks.name as bank_name,
			suppliers.name as supplier_name,
			companies.code as company_name,
			
			imp_lcs.last_delivery_date,
			imp_lcs.expiry_date,
			imp_lcs.pay_term_id,
			case when 
		imp_lcs.menu_id=1
        then 'Fabric'
		when
        imp_lcs.menu_id=2
        then 'Trims'
        when 
        imp_lcs.menu_id=3
        then 'Yarns'
        when 
        imp_lcs.menu_id=4
        then 'Knit Service'
        when 
        imp_lcs.menu_id=5
        then 'Aop Service'
        when 
        imp_lcs.menu_id=6
        then 'Dyeing Service'
        when 
        imp_lcs.menu_id=7
        then 'Dyes & Chem'
        when 
        imp_lcs.menu_id=8
        then 'General'
        when 
        imp_lcs.menu_id=9
        then 'Yarn Dyeing'
        when 
        imp_lcs.menu_id=10
        then 'Embelishment'
        when 
        imp_lcs.menu_id=11
        then 'General Service'
        else null
        end as menu_name, 
        sum(imp_backed_exp_lc_scs.amount) as amount
			FROM 
			imp_backed_exp_lc_scs 
			left join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			left join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
			left join bank_branches on bank_branches.id=imp_lcs.issuing_bank_branch_id
			left join banks on banks.id=bank_branches.bank_id
			left join suppliers on suppliers.id=imp_lcs.supplier_id 
			left join companies on companies.id=imp_lcs.company_id
			where 
			exp_lc_scs.file_no=? 
			group by 
			imp_lcs.id,
			imp_lcs.lc_no_i,
			imp_lcs.lc_no_ii,
			imp_lcs.lc_no_iii,
			imp_lcs.lc_no_iv,
			imp_lcs.lc_date,
			imp_lcs.menu_id,
			banks.name,
			suppliers.name,
			companies.code,
			imp_lcs.last_delivery_date,
			imp_lcs.expiry_date,
			imp_lcs.pay_term_id 
			order by imp_lcs.id", [request('file_no',0)]);
		$rows = collect($results)
		->groupBy('menu_name')
		/*->map(function ($rows) use($payterm){
		$rows->lc_no=$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv;
		$rows->pay_term=$payterm[$rows->pay_term_id];
		$rows->amount=number_format($rows->amount,2,'.','');
		$rows->lc_date=date('d-M-Y',strtotime($rows->lc_date));
		$rows->last_delivery_date=date('d-M-Y',strtotime($rows->last_delivery_date));
		$rows->expiry_date=date('d-M-Y',strtotime($rows->expiry_date));
		return $rows;
		})*/;
		$datas=array();
        foreach($rows as $menu_name=>$value)
        {
            $amount=0;
            foreach($value as $row)
            {
            	$amount+=$row->amount;
				$row->lc_no=$row->lc_no_i." ".$row->lc_no_ii." ".$row->lc_no_iii." ".$row->lc_no_iv;
				$row->pay_term=$payterm[$row->pay_term_id];
				$row->amount=number_format($row->amount,2,'.','');
				$row->lc_date=$row->lc_date?date('d-M-Y',strtotime($row->lc_date)):'--';
				$row->last_delivery_date=date('d-M-Y',strtotime($row->last_delivery_date));
				$row->expiry_date=date('d-M-Y',strtotime($row->expiry_date));
                array_push($datas,$row);
            }
            $subTot = collect(['lc_no'=>'Sub Total','amount'=>number_format($amount,'2','.',',')]);
            array_push($datas,$subTot);
        }
		echo json_encode($datas);
    }

    public function btbadjust(){
     	$payterm = array_prepend(config('bprs.payterm'), '-Select-','');
		$results = \DB::select('select 
		imp_lcs.id as imp_lc_id,
		imp_lcs.lc_no_i,
		imp_lcs.lc_no_ii,
		imp_lcs.lc_no_iii,
		imp_lcs.lc_no_iv,
		imp_lcs.lc_date,
		banks.name as bank_name,
		suppliers.code as supplier_name,
		sum(imp_backed_exp_lc_scs.amount) as btb_amount,
		m.amount,
		n.accept_amount 
		FROM imp_backed_exp_lc_scs 
		left join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
		left join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
		left join bank_branches on bank_branches.id=imp_lcs.issuing_bank_branch_id
		left join banks on banks.id=bank_branches.bank_id
		left join suppliers on suppliers.id=imp_lcs.supplier_id 
		left join (
		SELECT 
		imp_doc_accepts.imp_lc_id,
		sum(imp_liability_adjust_chlds.amount)amount
		FROM 
		imp_liability_adjusts
		left join imp_liability_adjust_chlds on imp_liability_adjust_chlds.imp_liability_adjust_id = imp_liability_adjusts.id  
		left join imp_doc_accepts on imp_doc_accepts.id =  imp_liability_adjusts.imp_doc_accept_id
		group by 
		imp_doc_accepts.imp_lc_id
		) m on m.imp_lc_id=imp_lcs.id
		left join (
		SELECT 
		imp_doc_accepts.imp_lc_id,
		sum(imp_acc_com_details.acceptance_value) as accept_amount
		FROM 
		imp_doc_accepts
		left join imp_acc_com_details on imp_acc_com_details.imp_doc_accept_id =  imp_doc_accepts.id
		group by 
		imp_doc_accepts.imp_lc_id
		) n on n.imp_lc_id=imp_lcs.id
		where exp_lc_scs.file_no=? 
		group by 
		imp_lcs.id,
		imp_lcs.lc_no_i,
		imp_lcs.lc_no_ii,
		imp_lcs.lc_no_iii,
		imp_lcs.lc_no_iv,
		imp_lcs.lc_date,
		banks.name ,
		suppliers.code,
		m.amount,
		n.accept_amount
		order by 
		imp_lcs.id', [request('file_no',0)]);
		$rows = collect($results)
		->map(function ($rows) use($payterm){
		$rows->lc_no=$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv;
		$rows->balance=number_format($rows->btb_amount-$rows->amount,2,'.','');
		$rows->amount=number_format($rows->amount,2,'.','');
		$rows->btb_amount=number_format($rows->btb_amount,2,'.','');
		$rows->accept_amount=number_format($rows->accept_amount,2,'.','');
		return $rows;
		});
		echo json_encode($rows);
    }
	public function btbadjustAcceptDtail(){
		$payterm = array_prepend(config('bprs.payterm'), '-Select-','');
		$results = \DB::select('select 
        imp_lcs.id as imp_lc_id,
		imp_lcs.lc_no_i,
		imp_lcs.lc_no_ii,
		imp_lcs.lc_no_iii,
		imp_lcs.lc_no_iv,
		imp_lcs.lc_date,
		banks.name as bank_name,
		suppliers.code as supplier_name,
		n.acceptance_id,
        n.invoice_no,
        n.invoice_date,
        n.bank_ref,
        n.bank_accep_date,
        n.doc_value,
        n.accept_amount
		FROM imp_backed_exp_lc_scs 
		left join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
		left join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
		left join bank_branches on bank_branches.id=imp_lcs.issuing_bank_branch_id
		left join banks on banks.id=bank_branches.bank_id
		left join suppliers on suppliers.id=imp_lcs.supplier_id 
		left join (
		SELECT 
		imp_doc_accepts.imp_lc_id,
		imp_doc_accepts.id as acceptance_id,
        imp_doc_accepts.invoice_no,
        imp_doc_accepts.invoice_date,
        imp_doc_accepts.bank_ref,
        imp_doc_accepts.bank_accep_date,
		imp_doc_accepts.doc_value,
		sum(imp_acc_com_details.acceptance_value) as accept_amount
		FROM 
		imp_doc_accepts
		left join imp_acc_com_details on imp_acc_com_details.imp_doc_accept_id =  imp_doc_accepts.id
		group by 
		imp_doc_accepts.imp_lc_id,
		imp_doc_accepts.id,
        imp_doc_accepts.invoice_no,
        imp_doc_accepts.invoice_date,
        imp_doc_accepts.bank_ref,
        imp_doc_accepts.bank_accep_date,
        imp_doc_accepts.doc_value
		) n on n.imp_lc_id=imp_lcs.id
		where imp_lcs.id=?
		group by 
		imp_lcs.id,
		imp_lcs.lc_no_i,
		imp_lcs.lc_no_ii,
		imp_lcs.lc_no_iii,
		imp_lcs.lc_no_iv,
		imp_lcs.lc_date,
		banks.name ,
		suppliers.code,
		n.acceptance_id,
        n.invoice_no,
        n.invoice_date,
        n.bank_ref,
        n.bank_accep_date,
        n.doc_value,
        n.accept_amount
		order by 
		imp_lcs.id', [request('imp_lc_id',0)]);
		$rows = collect($results)
		->map(function ($rows) use($payterm){
			$rows->lc_no=$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv;
			//$rows->balance=number_format($rows->btb_amount-$rows->amount,2,'.','');
			//$rows->amount=number_format($rows->amount,2,'.','');
			//$rows->btb_amount=number_format($rows->btb_amount,2,'.','');
			$rows->doc_value=number_format($rows->doc_value,2,'.','');
		return $rows;
		});
		echo json_encode($rows);
	}

    public function pctaken(){
		$results = \DB::select('select 
		exp_pre_credits.loan_no,
		exp_pre_credit_lc_scs.equivalent_fc as amount ,
		exp_lc_scs.lc_sc_no,
		exp_pre_credit_lc_scs.credit_taken,
		exp_pre_credit_lc_scs.exch_rate,
		exp_pre_credits.cr_date,
		exp_pre_credits.tenor,
		exp_pre_credits.maturity_date
		FROM exp_pre_credits
		left join exp_pre_credit_lc_scs on exp_pre_credit_lc_scs.exp_pre_credit_id =exp_pre_credits.id
	 	left join exp_lc_scs on exp_lc_scs.id =exp_pre_credit_lc_scs.exp_lc_sc_id
	  	where exp_lc_scs.file_no=? and exp_pre_credit_lc_scs.deleted_at is null order by exp_pre_credits.id', [request('file_no',0)]);
		$rows = collect($results)
		->map(function ($rows){
		$rows->amount=number_format($rows->amount,2,'.','');
		$rows->credit_taken=number_format($rows->credit_taken,2,'.','');
		$rows->cr_date=date('d-M-Y',strtotime($rows->cr_date));
		$rows->maturity_date=date('d-M-Y',strtotime($rows->maturity_date));
		return $rows;
		});
		echo json_encode($rows);
    }

    public function pcadjust(){
		$pctaken = \DB::select('select exp_pre_credits.loan_no,exp_pre_credit_lc_scs.equivalent_fc as amount ,exp_pre_credits.cr_date,exp_pre_credits.tenor,exp_pre_credits.maturity_date FROM exp_pre_credits left join exp_pre_credit_lc_scs on exp_pre_credit_lc_scs.exp_pre_credit_id =exp_pre_credits.id left join exp_lc_scs on exp_lc_scs.id =exp_pre_credit_lc_scs.exp_lc_sc_id where exp_lc_scs.file_no=? and exp_pre_credit_lc_scs.deleted_at is null order by exp_pre_credits.id', [request('file_no',0)]);
		$rowspctaken = collect($pctaken)
		->map(function ($rowspctaken){
		$rowspctaken->amount=number_format($rowspctaken->amount,2,'.','');
		$rowspctaken->cr_date=date('d-M-Y',strtotime($rowspctaken->cr_date));
		$rowspctaken->maturity_date=date('d-M-Y',strtotime($rowspctaken->maturity_date));
		return $rowspctaken;
		});

		$results = \DB::select("select exp_lc_scs.file_no,
		exp_doc_submissions.bank_ref_bill_no as bill_no,
		exp_pro_rlzs.realization_date as ad_date,
		'Realization' as event,
		exp_pro_rlz_amounts.doc_value as amount 
		FROM exp_doc_submissions 
		left join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id 
		left join exp_pro_rlzs on exp_pro_rlzs.exp_doc_submission_id = exp_doc_submissions.id 
		left join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id = exp_pro_rlzs.id 
		left join commercial_heads on commercial_heads.id = exp_pro_rlz_amounts.commercial_head_id 
		where commercial_heads.commercialhead_type_id=5 and exp_lc_scs.file_no=? and exp_pro_rlz_amounts.deleted_at is null
		union all
		SELECT 
		exp_lc_scs.file_no,
		exp_doc_submissions.bank_ref_bill_no as bill_no,
		exp_doc_submissions.submission_date as ad_date,
		'Doc Purchase' as event,
		exp_doc_sub_transections.doc_value as amount 
		FROM exp_doc_submissions 
		left join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id 
		left join exp_doc_sub_transections on exp_doc_sub_transections.exp_doc_submission_id = exp_doc_submissions.id 
		left join commercial_heads on commercial_heads.id = exp_doc_sub_transections.commercialhead_id 
		where commercial_heads.commercialhead_type_id=5 and exp_lc_scs.file_no=?", [request('file_no',0),request('file_no',0)]);
		$rows = collect($results)
		->map(function ($rows){
			$rows->amount=number_format($rows->amount,2,'.','');
			$rows->ad_date=date('d-M-Y',strtotime($rows->ad_date));
		return $rows;
		});


		echo json_encode(['pctaken'=>$rowspctaken,'adjust'=>$rows]);
    }

	public function docpur(){
		$results = \DB::select('select exp_doc_submissions.bank_ref_bill_no, exp_doc_submissions.bank_ref_date, 
		sum(exp_doc_sub_transections.doc_value) as amount ,
		pc.pc_amount,
		cd.cd_amount
		from exp_doc_submissions 
		join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id 
		join exp_doc_sub_transections on exp_doc_sub_transections.exp_doc_submission_id = exp_doc_submissions.id 
		left join (
		SELECT exp_doc_submissions.id,sum(exp_doc_sub_transections.doc_value) as pc_amount 
		FROM exp_doc_sub_transections 
		right join exp_doc_submissions on exp_doc_submissions.id = exp_doc_sub_transections.exp_doc_submission_id
		right join commercial_heads on commercial_heads.id = exp_doc_sub_transections.commercialhead_id where  commercial_heads.commercialhead_type_id=5 group by exp_doc_submissions.id
		) pc on pc.id = exp_doc_submissions.id 
		left join (
		SELECT exp_doc_submissions.id,sum(exp_doc_sub_transections.doc_value) as cd_amount 
		FROM exp_doc_sub_transections 
		right join exp_doc_submissions on exp_doc_submissions.id = exp_doc_sub_transections.exp_doc_submission_id
		right join commercial_heads on commercial_heads.id = exp_doc_sub_transections.commercialhead_id where  commercial_heads.commercialhead_type_id=8 group by exp_doc_submissions.id
		) cd on cd.id = exp_doc_submissions.id
		where exp_doc_sub_transections.deleted_at is null and exp_lc_scs.file_no=? 
		group by exp_doc_submissions.id, exp_doc_submissions.bank_ref_bill_no, exp_doc_submissions.bank_ref_date, pc.pc_amount,cd.cd_amount', [request('file_no',0)]);
		$rows = collect($results);
		$rows->map(function ($rows){
		$rows->other=number_format($rows->amount-($rows->pc_amount+$rows->cd_amount),2,'.',',');
		$rows->amount=number_format($rows->amount,2,'.','');
		$rows->pc_amount=number_format($rows->pc_amount,2,'.','');
		$rows->cd_amount=number_format($rows->cd_amount,2,'.','');
		$rows->bank_ref_date=date('d-M-Y',strtotime($rows->bank_ref_date));
		return $rows;
		});
		echo json_encode($rows);
	}

	public function docadjust(){
		$results = \DB::select('select exp_doc_submissions.bank_ref_bill_no, exp_doc_submissions.bank_ref_date, 
		sum(exp_doc_sub_transections.doc_value) as amount ,
		adjust.realization_date,
		adjust.doc_adj_amount
		from exp_doc_submissions 
		join exp_lc_scs on exp_lc_scs.id = exp_doc_submissions.exp_lc_sc_id 
		join exp_doc_sub_transections on exp_doc_sub_transections.exp_doc_submission_id = exp_doc_submissions.id 
		left join (
			select exp_doc_submissions.id,exp_pro_rlzs.realization_date,sum(exp_pro_rlz_amounts.doc_value) as doc_adj_amount 
			FROM exp_doc_submissions
			left join exp_pro_rlzs on exp_pro_rlzs.exp_doc_submission_id = exp_doc_submissions.id 
			left join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id = exp_pro_rlzs.id 
			left join commercial_heads on commercial_heads.id = exp_pro_rlz_amounts.commercial_head_id 
			where commercial_heads.commercialhead_type_id=4 and exp_pro_rlz_amounts.deleted_at is null  
			group by exp_doc_submissions.id,exp_pro_rlzs.realization_date
		) adjust on adjust.id = exp_doc_submissions.id 
		where exp_doc_sub_transections.deleted_at is null and exp_lc_scs.file_no=? 
		group by exp_doc_submissions.id, exp_doc_submissions.bank_ref_bill_no, exp_doc_submissions.bank_ref_date,
		adjust.realization_date,adjust.doc_adj_amount', [request('file_no',0)]);
		$rows = collect($results);
		$rows->map(function ($rows){
			$rows->balance=number_format($rows->amount-$rows->doc_adj_amount,2,'.','');
			$rows->amount=number_format($rows->amount,2,'.','');
			$rows->doc_adj_amount=number_format($rows->doc_adj_amount,2,'.','');
			$rows->realization_date=date('d-M-Y',strtotime($rows->realization_date));
			return $rows;
		});
		echo json_encode($rows);
	}

	public function getFilePdf(){
		$file=request('file_no',0);

		$rows=$this->expsalescontract 
		->leftJoin('imp_backed_exp_lc_scs',function($join){
			$join->on('imp_backed_exp_lc_scs.exp_lc_sc_id','=','exp_lc_scs.id');
		})
		->leftJoin('buyers', function($join){
			$join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
		})
		->leftJoin('currencies', function($join){
			$join->on('currencies.id', '=', 'exp_lc_scs.currency_id');
		})
		->leftJoin('companies', function($join){
			$join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
		})
		->leftJoin('bank_branches', function($join) {
			$join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
		})
		->leftJoin('banks', function($join) {
			$join->on('banks.id', '=', 'bank_branches.bank_id');
		})
		->leftJoin(\DB::raw("(
			select 
			exp_lc_scs.file_no,
			sum(exp_lc_scs.lc_sc_value) as lc_sc_value 
			from exp_lc_scs 
			group by
			exp_lc_scs.file_no
			) lcscvalue"
		), "lcscvalue.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			SELECT 
				exp_lc_scs.file_no,
				sum(exp_rep_lc_scs.replaced_amount) as replaced_amount 
				FROM exp_rep_lc_scs 
				right join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   
				group by exp_lc_scs.file_no) LcScRep"
		), "LcScRep.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			SELECT 
			exp_lc_scs.file_no,
			sum(imp_backed_exp_lc_scs.amount) as btb_opened_amount 
			FROM imp_backed_exp_lc_scs 
			right join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			right join imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id   
			where  imp_lcs.lc_date is not null  
			group by exp_lc_scs.file_no) BTBOpened"
		), "BTBOpened.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			SELECT 
			exp_lc_scs.file_no,
			sum(imp_backed_exp_lc_scs.amount) as btb_opening_amount 
			FROM imp_backed_exp_lc_scs 
			right join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			right join imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id   
			where  imp_lcs.lc_date is null 
			group by exp_lc_scs.file_no) BTB"
		), "BTB.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			select 
			  exp_lc_scs.file_no, 
			  sum(imp_backed_exp_lc_scs.amount) as fabric_btb_amount
			FROM 
			imp_backed_exp_lc_scs 
			join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
			where imp_lcs.menu_id=1
			group by exp_lc_scs.file_no) fabricbtb"
		), "fabricbtb.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			select 
			  exp_lc_scs.file_no, 
			  sum(imp_backed_exp_lc_scs.amount) as trims_btb_amount
			FROM 
			imp_backed_exp_lc_scs 
			join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
			where imp_lcs.menu_id=2
			group by exp_lc_scs.file_no) trimsbtb"
		), "trimsbtb.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			select 
			  exp_lc_scs.file_no, 
			  sum(imp_backed_exp_lc_scs.amount) as yarn_btb_amount
			FROM 
			imp_backed_exp_lc_scs 
			join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
			where imp_lcs.menu_id=3
			group by exp_lc_scs.file_no) yarnbtb"
		), "yarnbtb.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			select 
			  exp_lc_scs.file_no, 
			  sum(imp_backed_exp_lc_scs.amount) as knit_btb_amount
			FROM 
			imp_backed_exp_lc_scs 
			join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
			where imp_lcs.menu_id=4
			group by exp_lc_scs.file_no) knitbtb"
		), "knitbtb.file_no", "=", "exp_lc_scs.file_no")      
		->leftJoin(\DB::raw("(
			select 
			  exp_lc_scs.file_no, 
			  sum(imp_backed_exp_lc_scs.amount) as aop_btb_amount
			FROM 
			imp_backed_exp_lc_scs 
			join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
			where imp_lcs.menu_id=5
			group by exp_lc_scs.file_no) aopbtb"
		), "aopbtb.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			select 
			  exp_lc_scs.file_no, 
			  sum(imp_backed_exp_lc_scs.amount) as dyeing_service_btb_amount
			FROM 
			imp_backed_exp_lc_scs 
			join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
			where imp_lcs.menu_id=6
			group by exp_lc_scs.file_no) dyeingservicebtb"
		), "dyeingservicebtb.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			select 
			  exp_lc_scs.file_no, 
			  sum(imp_backed_exp_lc_scs.amount) as dye_chem_btb_amount
			FROM 
			imp_backed_exp_lc_scs 
			join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
			where imp_lcs.menu_id=7
			group by exp_lc_scs.file_no) dyechembtb"
		), "dyechembtb.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			select 
			  exp_lc_scs.file_no, 
			  sum(imp_backed_exp_lc_scs.amount) as yarn_dyeing_btb_amount
			from imp_backed_exp_lc_scs 
			join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
			where imp_lcs.menu_id=9
			group by exp_lc_scs.file_no) yarndyeingbtb"
		  ), "yarndyeingbtb.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			select 
			  exp_lc_scs.file_no, 
			  sum(imp_backed_exp_lc_scs.amount) as others_btb_amount
			FROM 
			imp_backed_exp_lc_scs 
			join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
			join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
			where imp_lcs.menu_id in (8,10,11)
			group by exp_lc_scs.file_no) othersbtb"
		), "othersbtb.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			select
			n.file_no,
			sum(n.yarn_req_amount) as yarn_req_amount,
			sum(n.trim_amount) as trim_req_amount,
			sum(n.fin_fab_req_amount) as fin_fab_req_amount,
			sum(n.dying_amount) as dying_amount,
			sum(n.overhead_amount) as overhead_amount,
			sum(n.kniting_amount) as kniting_amount,
			sum(n.aop_amount) as aop_amount,
			sum(n.aop_overhead_amount) as aop_overhead_amount,
			sum(n.dyed_yarn_rq_amount) as dyed_yarn_rq_amount
			from(
			select 
				exp_lc_scs.file_no,
				sales_orders.id,
				budgetYarn.yarn_req_amount,
				budgetTrim.trim_amount,
				budgetFinfab.fin_fab_req_amount,
				budgetDyeing.dying_amount,
				budgetDyeing.overhead_amount,
				budgetKniting.kniting_amount,
				budgetAop.aop_amount,
				budgetAop.aop_overhead_amount ,
				budgetYarnDyeing.dyed_yarn_rq_amount
				from exp_lc_sc_pis 
				join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
				join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
				join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id
				join sales_orders  on sales_orders.id=exp_pi_orders.sales_order_id
				join jobs  on jobs.id=sales_orders.job_id
				join styles  on styles.id=jobs.style_id
				left join (
					select 
					m.id as sale_order_id,
					sum(m.yarn) as yarn_req,
					sum(m.yarn_amount) as yarn_req_amount  
					from (
						select 
						budget_yarns.id as budget_yarn_id ,
						budget_yarns.ratio,
						budget_yarns.cons,
						budget_yarns.rate,
						budget_yarns.amount,
						sum(budget_fabric_cons.grey_fab) as grey_fab,
						sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,
						(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount,sales_orders.id as id  
						from budget_yarns 
						join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id 
						join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id 
						join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id 
						--where sales_order_gmt_color_sizes.qty > 0
						group by 
						budget_yarns.id,
						budget_yarns.ratio,
						budget_yarns.cons,
						budget_yarns.rate,
						budget_yarns.amount,
						sales_orders.id,
						sales_orders.sale_order_no
					) m 
					group by m.id
				) budgetYarn on budgetYarn.sale_order_id=sales_orders.id
				left join (
					SELECT 
					sales_orders.id as sales_order_id,
					sum(budget_yarn_dyeing_cons.amount) as dyed_yarn_rq_amount
					FROM sales_orders 
					join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id
					group by sales_orders.id
				  )budgetYarnDyeing on budgetYarnDyeing.sales_order_id=sales_orders.id
				left join(
					select 
					sales_orders.id as sales_order_id,
					sum(budget_trim_cons.amount) as trim_amount 
					from sales_orders 
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
					join budget_trim_cons on budget_trim_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
					join budget_trims on budget_trims.id = budget_trim_cons.budget_trim_id
					where sales_order_gmt_color_sizes.qty > 0
					group by sales_orders.id
				)budgetTrim on budgetTrim.sales_order_id=sales_orders.id
				left join (
					select
						sales_orders.id as sales_order_id,
						sum(budget_fabric_cons.amount) as fin_fab_req_amount
						from sales_orders 
						join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
						join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
						join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
						join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
						join jobs on jobs.id = sales_orders.job_id 
						join styles on styles.id = jobs.style_id 
						where sales_orders.order_status !=2
						and style_fabrications.material_source_id=1
						and sales_order_gmt_color_sizes.qty > 0
					group by
					sales_orders.id
				)budgetFinfab on budgetFinfab.sales_order_id=sales_orders.id
				left join (
					select 
					sales_orders.id as sale_order_id,
					production_processes.production_area_id,
					sum(budget_fabric_prod_cons.amount) as dying_amount,
					sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
					from budget_fabric_prod_cons 
					left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
					left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
					left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
					where production_processes.production_area_id =20
					group by sales_orders.id,
					production_processes.production_area_id
				)budgetDyeing on budgetDyeing.sale_order_id=sales_orders.id
				left join (
					select 
					sales_orders.id as sale_order_id,
					production_processes.production_area_id,
					sum(budget_fabric_prod_cons.amount) as kniting_amount
				from budget_fabric_prod_cons 
				left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
				left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
				left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
				where production_processes.production_area_id =10
				group by 
				sales_orders.id,
				production_processes.production_area_id
				)budgetKniting on budgetKniting.sale_order_id=sales_orders.id
				left join (
					select sales_orders.id as sale_order_id,
					production_processes.production_area_id,
					sum(budget_fabric_prod_cons.amount) as aop_amount,
					sum(budget_fabric_prod_cons.overhead_amount) as aop_overhead_amount
				from budget_fabric_prod_cons 
				left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
				left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
				left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
				where production_processes.production_area_id =25
				group by 
				sales_orders.id,
				production_processes.production_area_id
				)budgetAop on budgetAop.sale_order_id=sales_orders.id
				where exp_pi_orders.deleted_at is null
				group by 
				exp_lc_scs.file_no,
				sales_orders.id,
				budgetYarn.yarn_req_amount,
				budgetTrim.trim_amount,
				budgetFinfab.fin_fab_req_amount,
				budgetDyeing.dying_amount,
				budgetDyeing.overhead_amount,
				budgetKniting.kniting_amount,
				budgetAop.aop_amount,
				budgetAop.aop_overhead_amount,
				budgetYarnDyeing.dyed_yarn_rq_amount
			) n
			group by n.file_no) budgetReq"
		), "budgetReq.file_no", "=", "exp_lc_scs.file_no")
		->leftJoin(\DB::raw("(
			SELECT 
			exp_lc_scs.file_no,
			sum(exp_pi_orders.qty) as so_qty,
			avg(exp_pi_orders.rate) as so_rate,
			sum(exp_pi_orders.amount) as so_amount 
			FROM exp_lc_sc_pis 
			left join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
			left join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
			left join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id  
			group by 
			exp_lc_scs.file_no
		  ) ExpSO"), "ExpSO.file_no", "=", "exp_lc_scs.file_no")
		->where([['exp_lc_scs.file_no','=',$file]])
		->get([
			'exp_lc_scs.file_no',
			'companies.name as company_name',
			'companies.address as company_address',
			'currencies.code as currency_code',
			'currencies.symbol as currency_symbol',
			'banks.name as bank_name',
        	'bank_branches.branch_name',
        	'bank_branches.address as bank_address',
        	'bank_branches.contact',
			'lcscvalue.lc_sc_value',
			'LcScRep.replaced_amount',
			'BTBOpened.btb_opened_amount',
        	'BTB.btb_opening_amount',
			'buyers.name as buyer_name',
			'fabricbtb.fabric_btb_amount',
			'trimsbtb.trims_btb_amount',
			'yarnbtb.yarn_btb_amount',
			'knitbtb.knit_btb_amount',
			'aopbtb.aop_btb_amount',
			'dyeingservicebtb.dyeing_service_btb_amount',
			'dyechembtb.dye_chem_btb_amount',
			'othersbtb.others_btb_amount',
			'yarndyeingbtb.yarn_dyeing_btb_amount',
			'budgetReq.fin_fab_req_amount',
			'budgetReq.trim_req_amount',
			'budgetReq.yarn_req_amount',
			'budgetReq.dying_amount',
			'budgetReq.overhead_amount',
			'budgetReq.kniting_amount',
			'budgetReq.aop_amount',
			'budgetReq.aop_overhead_amount',
			'budgetReq.aop_overhead_amount',
			'ExpSO.so_amount',
		])
		->first();
	
		$rows->lc_sc_value=$rows->lc_sc_value-$rows->replaced_amount;
		$rows->limit_btb_open=($rows->lc_sc_value*70)/100;
		$rows->yet_btb_open=$rows->limit_btb_open-$rows->btb_opened_amount;
		$rows->limit_btb_booked=$rows->btb_opening_amount-$rows->lc_amount;
		$rows->fund_available=$rows->yet_btb_open-$rows->limit_btb_booked;
		$rows->dying_req_amount=$rows->dying_amount+$rows->overhead_amount;
		$rows->aop_req_amount=$rows->aop_amount+$rows->aop_overhead_amount;

		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->AddPage();

		$pdf->SetFont('helvetica', 'N', 8);
		$view= \View::make('Defult.Report.Commercial.LiabilityCoverageReportFileDetailPdf',['rows'=>$rows]);
		$html_content=$view->render();
		$pdf->SetY(15);
		$pdf->WriteHtml($html_content, true, false,true,false,'');
		$filename = storage_path() . '/LiabilityCoverageReportFileDetailPdf.pdf';
		$pdf->output($filename,'I');
		exit();
	}
}