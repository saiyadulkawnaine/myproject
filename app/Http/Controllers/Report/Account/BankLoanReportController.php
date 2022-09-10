<?php

namespace App\Http\Controllers\Report\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;

class BankLoanReportController extends Controller
{
	private $company;
	private $bank;
	private $bankbranch;
	public function __construct(
		CompanyRepository $company,
		BankRepository $bank,
		BankBranchRepository $bankbranch
	)
    {
		$this->company  = $company;
		$this->bank  = $bank;
		$this->bankbranch  = $bankbranch;
		$this->middleware('auth');
		//$this->middleware('permission:view.glbuys',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
		return Template::loadView('Report.Account.BankLoanReport',[
			'company'=>$company,
			'bank'=>$bank,
		]);
    }
    
    
	public function getData()
	{
		$date_to=request('date_to',0);
		$company_id=request('company_id',0);
		$bank_id=request('bank_id',0);
		$company_cond="";
		$bank_cond="";
		if($company_id){
			$company_cond=" and companies.id=".$company_id;
		}
		if($bank_id){
			$bank_cond=" and banks.id=".$bank_id;
		}
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
		$bankbranch=array_prepend(array_pluck($this->bankbranch->get(),'branch_name','id'),'-Select-','');
		$loans = collect(\DB::select("
		select 
		banks.id as bank_id,
		banks.name as bank_name,
		bank_branches.id as bank_branch_id,
		bank_branches.branch_name,
		bank_accounts.company_id,
		companies.name as company_name,
		bank_accounts.id as bank_account_id ,
		bank_accounts.account_no,
		bank_accounts.account_type_id,
		bank_accounts.limit,
		commercial_heads.name as commercial_head_name,
		term_loans.loan_amount,
		term_loans.no_installment,
		term_loan_paids.no_installment_paid,
		term_loan_paids.interest_amount_paid,
		term_loan_paids.principal_amount_paid,
		due_installments.no_of_installment as no_of_due_installment,
		due_installments.loan_amount as due_loan_amount,
		due_installment_paids.no_of_due_installment_paid,
		due_installment_paids.interest_amount_paid as due_interest_amount_paid,
		due_installment_paids.principal_amount_paid as due_principal_amount_paid
		from
		banks
		join bank_branches on bank_branches.bank_id=banks.id
		join bank_accounts on bank_accounts.bank_branch_id=bank_branches.id
		join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
		join companies on companies.id=bank_accounts.company_id
		left join(
		select 
		acc_term_loans.bank_account_id,
		count (acc_term_loan_installments.id) as no_installment,
		sum (acc_term_loan_installments.amount) as loan_amount
		from
		acc_term_loans
		join acc_term_loan_installments on acc_term_loan_installments.acc_term_loan_id=acc_term_loans.id
		where acc_term_loans.deleted_at is null
		and acc_term_loan_installments.deleted_at is null
		group by
		acc_term_loans.bank_account_id
		)term_loans on term_loans.bank_account_id=bank_accounts.id
		left join (
		select
		m.bank_account_id,
		count(m.acc_term_loan_installment_id) as no_installment_paid,
		sum(m.interest_amount_paid) as interest_amount_paid,
		sum(m.principal_amount_paid) as principal_amount_paid
		from
		(
		select 
		acc_term_loans.bank_account_id,
		acc_term_loan_payments.acc_term_loan_installment_id,
		sum (acc_term_loan_payments.interest_amount) as interest_amount_paid,
		sum (acc_term_loan_payments.amount) as principal_amount_paid
		from
		acc_term_loans
		join acc_term_loan_installments on acc_term_loan_installments.acc_term_loan_id=acc_term_loans.id
		join acc_term_loan_payments on acc_term_loan_payments.acc_term_loan_installment_id=acc_term_loan_installments.id
		where acc_term_loans.deleted_at is null
		and acc_term_loan_installments.deleted_at is null
		and acc_term_loan_payments.deleted_at is null
		group by
		acc_term_loans.bank_account_id,
		acc_term_loan_payments.acc_term_loan_installment_id
		) m  group by m.bank_account_id
		) term_loan_paids on term_loan_paids.bank_account_id=bank_accounts.id
		left join (
		select 
		acc_term_loans.bank_account_id,
		count(acc_term_loan_installments.id) as no_of_installment,
		sum(acc_term_loan_installments.amount) as loan_amount
		from
		acc_term_loans
		join acc_term_loan_installments on acc_term_loan_installments.acc_term_loan_id=acc_term_loans.id
		where acc_term_loan_installments.due_date < '".$date_to."'
		and acc_term_loans.deleted_at is null
		and acc_term_loan_installments.deleted_at is null
		group by acc_term_loans.bank_account_id
		) due_installments on due_installments.bank_account_id=bank_accounts.id
		left join (
		select
		m.bank_account_id,
		count(m.acc_term_loan_installment_id) as no_of_due_installment_paid,
		sum(m.interest_amount_paid) as interest_amount_paid,
		sum(m.principal_amount_paid) as principal_amount_paid
		from
		(
		select 
		acc_term_loans.bank_account_id,
		acc_term_loan_payments.acc_term_loan_installment_id,
		sum (acc_term_loan_payments.interest_amount) as interest_amount_paid,
		sum (acc_term_loan_payments.amount) as principal_amount_paid
		from
		acc_term_loans
		join acc_term_loan_installments on acc_term_loan_installments.acc_term_loan_id=acc_term_loans.id
		join acc_term_loan_payments on acc_term_loan_payments.acc_term_loan_installment_id=acc_term_loan_installments.id
		where acc_term_loan_installments.due_date < '".$date_to."'
		and acc_term_loans.deleted_at is null
		and acc_term_loan_installments.deleted_at is null
		and acc_term_loan_payments.deleted_at is null
		group by
		acc_term_loans.bank_account_id,
		acc_term_loan_payments.acc_term_loan_installment_id
		) m group by m.bank_account_id
		) due_installment_paids on due_installment_paids.bank_account_id=bank_accounts.id
		where commercial_heads.commercialhead_type_id in(3,4,5,6,8,9,27,28,29,30,31)
		$company_cond $bank_cond
		order by 
		companies.id,
		banks.id,
		bank_branches.id,
		commercial_heads.id
      "))
		->map(function($loans){
			$loans->outstandings=$loans->loan_amount-($loans->interest_amount_paid+$loans->principal_amount_paid);
			$loans->no_installment_outs=$loans->no_installment-$loans->no_installment_paid;
			$loans->no_due_installment_outs=$loans->no_of_due_installment-$loans->no_of_due_installment_paid;
			$loans->due_outstandings=$loans->due_loan_amount-($loans->due_interest_amount_paid+$loans->due_principal_amount_paid);
			return $loans;

		})
		->groupBy(['company_id','bank_id','bank_branch_id']);
		/*$data=[];
		foreach($loans as $loan){
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['commercial_head_name']=$loan->commercial_head_name;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['outstandings']=$loan->loan_amount-($loan->interest_amount_paid+$loan->principal_amount_paid);
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['no_installment_outs']=$loan->no_installment-$loan->no_installment_paid;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['no_due_installment_outs']=$loan->no_of_due_installment-$loan->no_of_due_installment_paid;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['due_outstandings']=$loan->due_loan_amount-($loan->due_interest_amount_paid+$loan->due_principal_amount_paid);
		}


		

		$btblcloans = collect(\DB::select("
		select
		m.company_id,
		m.bank_id,
		m.issuing_bank_branch_id as bank_branch_id,
		m.bank_account_id,
		sum(m.lc_amount) as lc_amount
		from
		(
		select 
		imp_lcs.id,
		imp_lcs.menu_id,
		imp_lcs.lc_date,
		imp_lcs.company_id,
		imp_lcs.lc_type_id,
		imp_lcs.exch_rate,
		imp_lcs.issuing_bank_branch_id,
		bank_branches.bank_id,
		bank_accounts.id as bank_account_id,
		commercial_heads.id as commercial_head_id,
		commercial_heads.commercialhead_type_id,
		commercial_heads.name as commercial_head_name,
		case when 
		imp_lcs.menu_id=1
		then sum(po_fabrics.amount) * imp_lcs.exch_rate
		when 
		imp_lcs.menu_id=2
		then sum(po_trims.amount) * imp_lcs.exch_rate
		when 
		imp_lcs.menu_id=3
		then sum(po_yarns.amount) * imp_lcs.exch_rate
		when 
		imp_lcs.menu_id=4
		then sum(po_knit_services.amount) * imp_lcs.exch_rate
		when 
		imp_lcs.menu_id=5
		then sum(po_aop_services.amount) * imp_lcs.exch_rate
		when 
		imp_lcs.menu_id=6
		then sum(po_dyeing_services.amount) * imp_lcs.exch_rate
		when 
		imp_lcs.menu_id=7
		then sum(po_dye_chems.amount) * imp_lcs.exch_rate
		when 
		imp_lcs.menu_id=8
		then sum(po_generals.amount) * imp_lcs.exch_rate
		when 
		imp_lcs.menu_id=9
		then sum(po_yarn_dyeings.amount) * imp_lcs.exch_rate
		when 
		imp_lcs.menu_id=10
		then sum(po_emb_services.amount) * imp_lcs.exch_rate
		when 
		imp_lcs.menu_id=11
		then sum(po_general_services.amount) * imp_lcs.exch_rate
		else 0
		end as lc_amount
		from imp_lcs 
		join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
		left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
		left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
		left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
		left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
		left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
		left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
		left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
		left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
		left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
		left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
		left join po_general_services on po_general_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=11
		left join bank_branches on bank_branches.id=imp_lcs.issuing_bank_branch_id
		left join bank_accounts on bank_accounts.bank_branch_id=bank_branches.id and bank_accounts.company_id=imp_lcs.company_id
		left join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
		where 
		imp_lcs.lc_date is not null 
		and  imp_lcs.lc_type_id=1
		and  commercial_heads.commercialhead_type_id=28
		and imp_lcs.lc_date <= '".$date_to."'
		group by 
		imp_lcs.id,
		imp_lcs.menu_id,
		imp_lcs.lc_date,
		imp_lcs.company_id,
		imp_lcs.lc_type_id,
		imp_lcs.exch_rate,
		imp_lcs.issuing_bank_branch_id,
		bank_branches.bank_id,

		bank_accounts.id,
		commercial_heads.id,
		commercial_heads.commercialhead_type_id,
		commercial_heads.name
		) m 
		group by
		m.company_id,
		m.bank_id,
		m.issuing_bank_branch_id,
		m.bank_account_id
		order by
		m.company_id
      "));
		foreach($btblcloans as $loan){
			//$acceptamount=$btblcacceptArr[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['amount'];
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['outstandings']=$loan->lc_amount;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['no_installment_outs']=0;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['no_due_installment_outs']=0;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['due_outstandings']=0;
		}

		$btblcacceptloans = collect(\DB::select("
		select
		m.company_id,
		m.bank_id,
		m.issuing_bank_branch_id as bank_branch_id,
		m.bank_account_id,
		sum(m.amount) as amount
		from
		(
		select 
		imp_lcs.id,
		imp_lcs.menu_id,
		imp_lcs.lc_date,
		imp_lcs.company_id,
		imp_lcs.lc_type_id,
		imp_lcs.exch_rate,
		imp_lcs.issuing_bank_branch_id,
		imp_doc_accepts.id as imp_doc_accept_id,
		bank_branches.bank_id,
		bank_accounts.id as bank_account_id,
		commercial_heads.id as commercial_head_id,
		commercial_heads.commercialhead_type_id,
		commercial_heads.name as commercial_head_name,
		imp_doc_accepts.doc_value*imp_lcs.exch_rate as amount

		from imp_lcs 
		left join imp_doc_accepts on imp_doc_accepts.imp_lc_id=imp_lcs.id
		left join bank_branches on bank_branches.id=imp_lcs.issuing_bank_branch_id
		left join bank_accounts on bank_accounts.bank_branch_id=bank_branches.id and bank_accounts.company_id=imp_lcs.company_id
		left join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
		where 
		imp_lcs.lc_date is not null 
		and  imp_lcs.lc_type_id=1
		and  commercial_heads.commercialhead_type_id=3
		and imp_doc_accepts.company_accep_date <= '".$date_to."'
		) m 
		group by
		m.company_id,
		m.bank_id,
		m.issuing_bank_branch_id,
		m.bank_account_id
		order by
		m.company_id
      "));
		foreach($btblcacceptloans as $loan){
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['outstandings']=$loan->amount;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['no_installment_outs']=0;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['no_due_installment_outs']=0;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['due_outstandings']=0;
		}

		

		$pcloans = collect(\DB::select("
			select
			exp_pre_credits.company_id,
			banks.id as bank_id,
			bank_branches.id as bank_branch_id,
			bank_accounts.id as bank_account_id,
			sum(exp_pre_credit_lc_scs.credit_taken) as loan_amount
			from
			exp_pre_credits
			join exp_pre_credit_lc_scs on exp_pre_credit_lc_scs.exp_pre_credit_id=exp_pre_credits.id
			join exp_lc_scs on exp_lc_scs.id=exp_pre_credit_lc_scs.exp_lc_sc_id
			join bank_branches on bank_branches.id=exp_lc_scs.exporter_bank_branch_id
			join banks on banks.id=bank_branches.bank_id
			left join bank_accounts on bank_accounts.bank_branch_id=bank_branches.id and bank_accounts.company_id=exp_pre_credits.company_id
			left join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
			where exp_pre_credits.deleted_at is null
			and  commercial_heads.commercialhead_type_id in(5,6)
			and exp_pre_credits.cr_date <= '".$date_to."'

			group by
			exp_pre_credits.company_id,
			banks.id,
			bank_branches.id,
			bank_accounts.id
			order by
			exp_pre_credits.company_id
      "));
		foreach($pcloans as $loan){
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['outstandings']=$loan->loan_amount;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['no_installment_outs']=0;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['no_due_installment_outs']=0;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['due_outstandings']=0;
		}

		$edfloans = collect(\DB::select("
			select
			m.company_id,
			m.bank_id,
			m.issuing_bank_branch_id as bank_branch_id,
			m.bank_account_id,
			sum(m.amount) as loan_amount
			from
			(
			select 
			imp_lcs.id,
			imp_lcs.menu_id,
			imp_lcs.lc_date,
			imp_lcs.company_id,
			imp_lcs.lc_type_id,
			imp_lcs.exch_rate,
			imp_lcs.issuing_bank_branch_id,
			imp_doc_accepts.id as imp_doc_accept_id,
			bank_branches.bank_id,
			bank_accounts.id as bank_account_id,
			commercial_heads.id as commercial_head_id,
			commercial_heads.commercialhead_type_id,
			commercial_heads.name as commercial_head_name,
			imp_liability_adjust_chlds.dom_currency as amount

			from imp_lcs 
			join imp_doc_accepts on imp_doc_accepts.imp_lc_id=imp_lcs.id
			join imp_liability_adjusts on imp_liability_adjusts.imp_doc_accept_id=imp_doc_accepts.id
			join imp_liability_adjust_chlds on imp_liability_adjust_chlds.imp_liability_adjust_id=imp_liability_adjusts.id
			join bank_branches on bank_branches.id=imp_lcs.issuing_bank_branch_id
			join bank_accounts on bank_accounts.bank_branch_id=bank_branches.id and bank_accounts.company_id=imp_lcs.company_id
			join commercial_heads on commercial_heads.id=bank_accounts.account_type_id and commercial_heads.id=imp_liability_adjust_chlds.adj_source
			where 
			imp_lcs.lc_date is not null 
			and  imp_lcs.lc_type_id=1
			and  commercial_heads.commercialhead_type_id=27
			and imp_liability_adjusts.payment_date <= '".$date_to."'
			) m 
			group by
			m.company_id,
			m.bank_id,
			m.issuing_bank_branch_id,
			m.bank_account_id
			order by
			m.company_id
      "));
		foreach($edfloans as $loan){
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['outstandings']=$loan->loan_amount;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['no_installment_outs']=0;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['no_due_installment_outs']=0;
			$data[$loan->company_id][$loan->bank_id][$loan->bank_branch_id][$loan->bank_account_id]['due_outstandings']=0;
		}*/

	   return Template::loadView('Report.Account.BankLoanReportData',[
	   	    'date_to'=>$date_to,
	   	    'company'=>$company,
	   	    'bank'=>$bank,
	   	    'bankbranch'=>$bankbranch,
			'loans'=>$loans,
		]);
	}
}
