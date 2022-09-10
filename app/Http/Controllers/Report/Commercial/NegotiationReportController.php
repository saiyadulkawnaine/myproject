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
use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use Illuminate\Support\Carbon;

class NegotiationReportController extends Controller
{
	private $accchartctrlhead;
	private $accchartsubgroup;
    private $currency;
    private $bank;
    private $expdocsubmission;
	public function __construct(
		ExpLcScRepository $expsalescontract,
		CurrencyRepository $currency, 
		CompanyRepository $company, 
		BuyerRepository $buyer,
		BankRepository $bank,
		ExpDocSubmissionRepository $expdocsubmission
	)
    {
		$this->expsalescontract    = $expsalescontract;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->bank = $bank;
        $this->expdocsubmission = $expdocsubmission;

		$this->middleware('auth');
		$this->middleware('permission:view.liabilitycoveragereports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
		$bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.Commercial.NegotiationReport',['company'=>$company,'buyer'=>$buyer,'bank'=>$bank]);
    }

    
    public function htmlgrid()
    {
    	$rows=$this->getData()->map(function ($rows){
    	$today=Carbon::parse(date('Y-m-d'));
    	$possible_realization_date = Carbon::parse($rows->possible_realization_date);
    	$realization_date = Carbon::parse($rows->realization_date);
    	$overDueDays=0;
    	if(!$rows->realization_date && ($today > $possible_realization_date)){
		$overDueDays = $possible_realization_date->diffInDays($today);
    	}

    	$rows->over_due_days=$overDueDays;
    	$rows->submission_date=$rows->submission_date?date('d-M-Y',strtotime($rows->submission_date)):'--';
    	$rows->bank_ref_date=$rows->bank_ref_date?date('d-M-Y',strtotime($rows->bank_ref_date)):'--';
    	$rows->bnk_to_bnk_cour_date=$rows->bnk_to_bnk_cour_date?date('d-M-Y',strtotime($rows->bnk_to_bnk_cour_date)):'--';
    	$rows->possible_realization_date=$rows->possible_realization_date?date('d-M-Y',strtotime($rows->possible_realization_date)):'--';
    	$rows->realization_date=$rows->realization_date?date('d-M-Y',strtotime($rows->realization_date)):'--';
        $rows->bnk_to_bnk_cour_date=$rows->bnk_to_bnk_cour_date?date('d-M-Y',strtotime($rows->bnk_to_bnk_cour_date)):'--';
    	$rows->file_value=$rows->rp_lc_sc_value+$rows->di_lc_sc_value;
    	$rows->foreign_commission_file=$rows->rp_lc_sc_foreign_commission+$rows->di_lc_sc_foreign_commission;
    	$rows->local_commission_file=$rows->rp_lc_sc_local_commission+$rows->di_lc_sc_local_commission;
    	$rows->freight_file=0;
    	$rows->net_value_file=$rows->file_value-($rows->foreign_commission_file+$rows->local_commission_file+$rows->freight_file);
    	$rows->btb_openable=$rows->net_value_file*(70/100);
    	$rows->btb_open_amount_per=0;

    	$rows->btb_open_amount_per=0;
    	$rows->pc_taken_amount_per=0;
    	if($rows->net_value_file){
    	$rows->btb_open_amount_per=($rows->btb_open_amount/$rows->net_value_file)*100;
    	$rows->pc_taken_amount_per=($rows->pc_taken_mount/$rows->net_value_file)*100;
    	}

    	$rows->yet_to_btb_opened=$rows->btb_openable-$rows->btb_open_amount;

    	$rows->deduction=$rows->discount_amount+$rows->bonus_amount+$rows->claim_amount;

    	$rows->net_invoice_value=$rows->invoice_value-($rows->bonus_amount+$rows->claim_amount+$rows->local_commission_invoice+$rows->foreign_commission_invoice+$rows->freight_by_supplier+$rows->freight_by_buyer+$rows->deduction);

    	$rows->freight_invoice=$rows->freight_by_supplier+$rows->freight_by_buyer;

		if($rows->btb_open_amount_per>=70){
			$rows->fc_held_btb_lc=$rows->net_invoice_value*($rows->btb_open_amount_per/100);
		}
		else
		{
			$rows->fc_held_btb_lc=$rows->net_invoice_value*(70/100);    
		}

    	$rows->erq_account=$rows->net_invoice_value*(1/100);
    	$rows->packaing_credit=$rows->net_invoice_value*(16/100);
    	$rows->cost_of_packaing_credit=$rows->pc_taken_rate;
    	//$rows->salary_account=$rows->net_invoice_value*(10/100);

    	$rows->mda_normal=$rows->net_invoice_value*(1/100);
    	$rows->inc_int_pur=$rows->net_invoice_value*(1/100);
    	$rows->source_tax=$rows->net_invoice_value*(1/100);
    	$rows->frg_bank_charge=$rows->net_invoice_value*(3/100);
    	//$rows->centarl_fund=$rows->net_invoice_value*(0.03/100);
    	//$rows->nego_comision=500/$rows->exch_rate;
    	//$rows->others_account=$rows->mda_normal+$rows->inc_int_pur+$rows->source_tax+$rows->centarl_fund+$rows->nego_comision;
    	
    	$rows->current_account=$rows->net_invoice_value-($rows->fc_held_btb_lc+$rows->erq_account+$rows->packaing_credit+$rows->mda_normal+$rows->inc_int_pur+$rows->source_tax+$rows->frg_bank_charge);

    	
    	$rows->ad_pu_amount=$rows->sub_docngl_doc_value+$rows->rlz_amt_docngl_doc_value+$rows->rlz_ded_docngl_doc_value;

    	/*$rows->btbm_built=$rows->sub_btbm_doc_value+$rows->rlz_amt_btbm_doc_value+$rows->rlz_ded_btbm_doc_value;
    	$rows->erq_cr=$rows->sub_erq_doc_value+$rows->rlz_amt_erq_doc_value+$rows->rlz_ded_erq_doc_value;
    	$rows->pc_ad=$rows->sub_pc_doc_value+$rows->rlz_amt_pc_doc_value+$rows->rlz_ded_pc_doc_value;
    	$rows->mdan_cr=$rows->sub_mdan_doc_value+$rows->rlz_amt_mdan_doc_value+$rows->rlz_ded_mdan_doc_value;
    	$rows->mdas_cr=$rows->sub_mdas_doc_value+$rows->rlz_amt_mdas_doc_value+$rows->rlz_ded_mdas_doc_value;
    	$rows->mdau_cr=$rows->sub_mdau_doc_value+$rows->rlz_amt_mdau_doc_value+$rows->rlz_ded_mdau_doc_value;

    	$rows->sct_deduct=$rows->sub_sct_doc_value+$rows->rlz_amt_sct_doc_value+$rows->rlz_ded_sct_doc_value;
    	$rows->fbc_ad=$rows->sub_fbc_doc_value+$rows->rlz_amt_fbc_doc_value+$rows->rlz_ded_fbc_doc_value;
    	$rows->cda_cr=$rows->sub_cda_doc_value+$rows->rlz_amt_cda_doc_value+$rows->rlz_ded_cda_doc_value;
    	$rows->disc_cr=$rows->sub_disc_doc_value+$rows->rlz_amt_disc_doc_value+$rows->rlz_ded_disc_doc_value;
    	$rows->discrip_cr=$rows->sub_discrip_doc_value+$rows->rlz_amt_discrip_doc_value+$rows->rlz_ded_discrip_doc_value;

    	$rows->commi_cr=$rows->sub_slcommi_doc_value+$rows->rlz_amt_slcommi_doc_value+$rows->rlz_ded_slcommi_doc_value;
    	$rows->sht_rlz=$rows->sub_shtrlz_doc_value+$rows->rlz_amt_shtrlz_doc_value+$rows->rlz_ded_shtrlz_doc_value;

    	$rows->exp_docp=$rows->sub_expdocp_doc_value+$rows->rlz_amt_expdocp_doc_value+$rows->rlz_ded_expdocp_doc_value;
    	$rows->cntrl_fund=$rows->sub_cntrlf_doc_value+$rows->rlz_amt_cntrlf_doc_value+$rows->rlz_ded_cntrlf_doc_value;
    	$rows->oth_crg=$rows->sub_othcrg_doc_value+$rows->rlz_amt_othcrg_doc_value+$rows->rlz_ded_othcrg_doc_value;
    	$rows->exch_vari=$rows->sub_exchvari_doc_value+$rows->rlz_amt_exchvari_doc_value+$rows->rlz_ded_exchvari_doc_value;

    	$rows->total_cr=$rows->btbm_built+
    	$rows->erq_cr+
    	$rows->pc_ad+
    	$rows->mdan_cr+
    	$rows->mdas_cr+
    	$rows->mdau_cr+
    	$rows->sct_deduct+
    	$rows->fbc_ad+
    	$rows->cda_cr+
    	$rows->disc_cr+
    	$rows->discrip_cr+
    	$rows->commi_cr+
    	$rows->sht_rlz+
    	$rows->exp_docp+
    	$rows->cntrl_fund+
    	$rows->oth_crg+
    	$rows->exch_vari;*/

        $rows->btbm_built=$rows->rlz_amt_btbm_doc_value+$rows->rlz_ded_btbm_doc_value;
        $rows->erq_cr=$rows->rlz_amt_erq_doc_value+$rows->rlz_ded_erq_doc_value;
        $rows->pc_ad=$rows->rlz_amt_pc_doc_value+$rows->rlz_ded_pc_doc_value;
        $rows->mdan_cr=$rows->rlz_amt_mdan_doc_value+$rows->rlz_ded_mdan_doc_value;
        $rows->mdas_cr=$rows->rlz_amt_mdas_doc_value+$rows->rlz_ded_mdas_doc_value;
        $rows->mdau_cr=$rows->rlz_amt_mdau_doc_value+$rows->rlz_ded_mdau_doc_value;

        $rows->sct_deduct=$rows->rlz_amt_sct_doc_value+$rows->rlz_ded_sct_doc_value;
        $rows->fbc_ad=$rows->rlz_amt_fbc_doc_value+$rows->rlz_ded_fbc_doc_value;
        $rows->cda_cr=$rows->rlz_amt_cda_doc_value+$rows->rlz_ded_cda_doc_value;
        $rows->disc_cr=$rows->rlz_amt_disc_doc_value+$rows->rlz_ded_disc_doc_value;
        $rows->discrip_cr=$rows->rlz_amt_discrip_doc_value+$rows->rlz_ded_discrip_doc_value;

        $rows->commi_cr=$rows->rlz_amt_slcommi_doc_value+$rows->rlz_ded_slcommi_doc_value;
        $rows->sht_rlz=$rows->rlz_amt_shtrlz_doc_value+$rows->rlz_ded_shtrlz_doc_value;

        $rows->exp_docp=$rows->rlz_amt_expdocp_doc_value+$rows->rlz_ded_expdocp_doc_value;
        $rows->cntrl_fund=$rows->rlz_amt_cntrlf_doc_value+$rows->rlz_ded_cntrlf_doc_value;
        $rows->oth_crg=$rows->rlz_amt_othcrg_doc_value+$rows->rlz_ded_othcrg_doc_value;
        $rows->exch_vari=$rows->rlz_amt_exchvari_doc_value+$rows->rlz_ded_exchvari_doc_value;

        $rows->total_cr=$rows->ad_pu_amount+
        $rows->btbm_built+
        $rows->erq_cr+
        $rows->pc_ad+
        $rows->mdan_cr+
        $rows->mdas_cr+
        $rows->mdau_cr+
        $rows->sct_deduct+
        $rows->fbc_ad+
        $rows->cda_cr+
        $rows->disc_cr+
        $rows->discrip_cr+
        $rows->commi_cr+
        $rows->sht_rlz+
        $rows->exp_docp+
        $rows->cntrl_fund+
        $rows->oth_crg+
        $rows->exch_vari;

    	$rows->ad_pu_amount=number_format($rows->ad_pu_amount,2);
    	$rows->btbm_built=number_format($rows->btbm_built,2);
    	$rows->erq_cr=number_format($rows->erq_cr,2);
    	$rows->pc_ad=number_format($rows->pc_ad,2);
    	$rows->mdan_cr=number_format($rows->mdan_cr,2);
    	$rows->mdas_cr=number_format($rows->mdas_cr,2);
    	$rows->mdau_cr=number_format($rows->mdau_cr,2);

    	$rows->sct_deduct=number_format($rows->sct_deduct,2);
    	$rows->fbc_ad=number_format($rows->fbc_ad,2);
    	$rows->cda_cr=number_format($rows->cda_cr,2);
    	$rows->disc_cr=number_format($rows->disc_cr,2);
    	$rows->discrip_cr=number_format($rows->discrip_cr,2);
    	$rows->commi_cr=number_format($rows->commi_cr,2);
    	$rows->sht_rlz=number_format($rows->sht_rlz,2);
    	$rows->exp_docp=number_format($rows->exp_docp,2);
    	$rows->cntrl_fund=number_format($rows->cntrl_fund,2);
    	$rows->oth_crg=number_format($rows->oth_crg,2);
    	$rows->exch_vari=number_format($rows->exch_vari,2);

    	$rows->total_cr=number_format($rows->total_cr,2);

    	$rows->invoice_value=number_format($rows->invoice_value,2);
    	$rows->deduction=number_format($rows->deduction,2);
    	$rows->local_commission_invoice=number_format($rows->local_commission_invoice,2);
    	$rows->foreign_commission_invoice=number_format($rows->foreign_commission_invoice,2);
    	$rows->freight_invoice=number_format($rows->freight_invoice,2);
    	$rows->net_invoice_value=number_format($rows->net_invoice_value,2);
    	$rows->fc_held_btb_lc=number_format($rows->fc_held_btb_lc,2);
    	$rows->erq_account=number_format($rows->erq_account,2);
    	$rows->packaing_credit=number_format($rows->packaing_credit,2);
    	$rows->cost_of_packaing_credit=number_format($rows->cost_of_packaing_credit,2);
    	$rows->mda_normal=number_format($rows->mda_normal,2);
    	$rows->inc_int_pur=number_format($rows->inc_int_pur,2);
    	$rows->source_tax=number_format($rows->source_tax,2);
    	$rows->frg_bank_charge=number_format($rows->frg_bank_charge,2);
    	$rows->current_account=number_format($rows->current_account,2);
    	$rows->rp_lc_sc_value=number_format($rows->rp_lc_sc_value,2);
    	$rows->di_lc_sc_value=number_format($rows->di_lc_sc_value,2);
    	$rows->file_value=number_format($rows->file_value,2);
    	$rows->foreign_commission_file=number_format($rows->foreign_commission_file,2);
    	$rows->local_commission_file=number_format($rows->local_commission_file,2);
    	$rows->freight_file=number_format($rows->freight_file,2);
    	$rows->net_value_file=number_format($rows->net_value_file,2);
    	$rows->btb_openable=number_format($rows->btb_openable,2);
    	$rows->btb_open_amount=number_format($rows->btb_open_amount,2);
    	$rows->btb_open_amount_per=number_format($rows->btb_open_amount_per,2);
    	$rows->yet_to_btb_opened=number_format($rows->yet_to_btb_opened,2);
    	$rows->pc_taken_mount=number_format($rows->pc_taken_mount,2);
    	$rows->pc_taken_amount_per=number_format($rows->pc_taken_amount_per,2);
    	$rows->pc_taken_rate=number_format($rows->pc_taken_rate,2);
 
		return $rows;
		});
    	echo json_encode($rows);
    }
    
    private function getData()
    {
     	$date_from=request('date_from',0);
     	$date_to=request('date_to',0);
     	$bank_id=request('bank_id',0);
     	$beneficiary_id=request('beneficiary_id',0);
     	$buyer_id=request('buyer_id',0);
     	$file_no=request('file_no',0);
     	$submission_id=request('submission_id',0);
		$possible_date_from=request('possible_date_from',0);
     	$possible_date_to=request('possible_date_to',0);


		$rows=$this->expdocsubmission
        ->selectRaw('
		exp_doc_submissions.*,
		exp_lc_scs.lc_sc_no,
		exp_lc_scs.file_no,
		exp_lc_scs.beneficiary_id,
		exp_lc_scs.buyer_id, 
		exp_lc_scs.buyers_bank, 
		exp_lc_scs.currency_id, 
		exp_lc_scs.exch_rate, 
		buyers.name as buyer_name,
		banks.name as bank_name,
		bank_branches.branch_name,
		bank_branches.address as bank_address,
		bank_branches.contact,
		companies.code as company_name,
		currencies.name as currency_name,
		replacablesc.lc_sc_value as rp_lc_sc_value,
		replacablesc.local_commission as rp_lc_sc_local_commission,
		replacablesc.foreign_commission as rp_lc_sc_foreign_commission,
		directlcsc.lc_sc_value as di_lc_sc_value,
		directlcsc.local_commission as di_lc_sc_local_commission,
		directlcsc.foreign_commission as di_lc_sc_foreign_commission,
		btb.btb_open_amount,
		exppc.pc_taken_mount,
		exppc.pc_taken_rate,
		submissioninvoice.invoice_value as invoice_value,
		submissioninvoice.discount_amount as discount_amount,
		submissioninvoice.bonus_amount as bonus_amount,
		submissioninvoice.claim_amount as claim_amount,
		submissioninvoice.commission as commission,
		submissioninvoice.net_inv_value as net_inv_value,
		submissioninvoice.freight_by_supplier as freight_by_supplier,
		submissioninvoice.freight_by_buyer as freight_by_buyer,
		submissioninvoice.local_commission_invoice as local_commission_invoice,
		submissioninvoice.foreign_commission_invoice as foreign_commission_invoice,

		realization.realization_date,

		sub_docngl.doc_value as sub_docngl_doc_value,
		rlz_amt_docngl.doc_value as rlz_amt_docngl_doc_value,
		rlz_ded_docngl.doc_value as rlz_ded_docngl_doc_value,

		sub_btbm.doc_value as sub_btbm_doc_value,
		rlz_amt_btbm.doc_value as rlz_amt_btbm_doc_value,
		rlz_ded_btbm.doc_value as rlz_ded_btbm_doc_value,

		sub_erq.doc_value as sub_erq_doc_value,
		rlz_amt_erq.doc_value as rlz_amt_erq_doc_value,
		rlz_ded_erq.doc_value as rlz_ded_erq_doc_value,

		sub_pc.doc_value as sub_pc_doc_value,
		rlz_amt_pc.doc_value as rlz_amt_pc_doc_value,
		rlz_ded_pc.doc_value as rlz_ded_pc_doc_value,

		sub_mdan.doc_value as sub_mdan_doc_value,
		rlz_amt_mdan.doc_value as rlz_amt_mdan_doc_value,
		rlz_ded_mdan.doc_value as rlz_ded_mdan_doc_value,

		sub_mdas.doc_value as sub_mdas_doc_value,
		rlz_amt_mdas.doc_value as rlz_amt_mdas_doc_value,
		rlz_ded_mdas.doc_value as rlz_ded_mdas_doc_value,

		sub_mdau.doc_value as sub_mdau_doc_value,
		rlz_amt_mdau.doc_value as rlz_amt_mdau_doc_value,
		rlz_ded_mdau.doc_value as rlz_ded_mdau_doc_value,
		
		sub_sct.doc_value as sub_sct_doc_value,
		rlz_amt_sct.doc_value as rlz_amt_sct_doc_value,
		rlz_ded_sct.doc_value as rlz_ded_sct_doc_value,

		sub_fbc.doc_value as sub_fbc_doc_value,
		rlz_amt_fbc.doc_value as rlz_amt_fbc_doc_value,
		rlz_ded_fbc.doc_value as rlz_ded_fbc_doc_value,

		sub_cda.doc_value as sub_cda_doc_value,
		rlz_amt_cda.doc_value as rlz_amt_cda_doc_value,
		rlz_ded_cda.doc_value as rlz_ded_cda_doc_value,

		sub_disc.doc_value as sub_disc_doc_value,
		rlz_amt_disc.doc_value as rlz_amt_disc_doc_value,
		rlz_ded_disc.doc_value as rlz_ded_disc_doc_value,

		sub_discrip.doc_value as sub_discrip_doc_value,
		rlz_amt_discrip.doc_value as rlz_amt_discrip_doc_value,
		rlz_ded_discrip.doc_value as rlz_ded_discrip_doc_value,

		sub_shtrlz.doc_value as sub_shtrlz_doc_value,
		rlz_amt_shtrlz.doc_value as rlz_amt_shtrlz_doc_value,
		rlz_ded_shtrlz.doc_value as rlz_ded_shtrlz_doc_value,

		sub_slcommi.doc_value as sub_slcommi_doc_value,
		rlz_amt_slcommi.doc_value as rlz_amt_slcommi_doc_value,
		rlz_ded_slcommi.doc_value as rlz_ded_slcommi_doc_value,

		sub_expdocp.doc_value as sub_expdocp_doc_value,
		rlz_amt_expdocp.doc_value as rlz_amt_expdocp_doc_value,
		rlz_ded_expdocp.doc_value as rlz_ded_expdocp_doc_value,

		sub_cntrlf.doc_value as sub_cntrlf_doc_value,
		rlz_amt_cntrlf.doc_value as rlz_amt_cntrlf_doc_value,
		rlz_ded_cntrlf.doc_value as rlz_ded_cntrlf_doc_value,

		sub_othcrg.doc_value as sub_othcrg_doc_value,
		rlz_amt_othcrg.doc_value as rlz_amt_othcrg_doc_value,
		rlz_ded_othcrg.doc_value as rlz_ded_othcrg_doc_value,

		sub_exchvari.doc_value as sub_exchvari_doc_value,
		rlz_amt_exchvari.doc_value as rlz_amt_exchvari_doc_value,
		rlz_ded_exchvari.doc_value as rlz_ded_exchvari_doc_value
		
		')
		->leftJoin('exp_lc_scs',function($join){
			$join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
		})
		->leftJoin('bank_branches', function($join) {
			$join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
		})
		->leftJoin('banks', function($join) {
			$join->on('banks.id', '=', 'bank_branches.bank_id');
		})
        ->join('buyers',function($join){
			$join->on('buyers.id','=','exp_lc_scs.buyer_id');
		})
		->join('companies',function($join){
			$join->on('companies.id','=','exp_lc_scs.beneficiary_id');
		})
		->join('currencies',function($join){
			$join->on('currencies.id','=','exp_lc_scs.currency_id');
		})
		
		->leftJoin(\DB::raw("(
		select 
		m.file_no,
		sum(m.lc_sc_value) as lc_sc_value,
		sum(m.local_commission) as local_commission,
		sum(m.foreign_commission) as foreign_commission
		from 
		(
		select 
		exp_lc_scs.file_no,
		exp_lc_scs.lc_sc_value,
		exp_lc_scs.lc_sc_value*(exp_lc_scs.local_commission_per/100) as local_commission,
		exp_lc_scs.lc_sc_value*(exp_lc_scs.foreign_commission_per/100) as foreign_commission
		from exp_lc_scs
		where exp_lc_scs.sc_or_lc=1
		and exp_lc_scs.lc_sc_nature_id=2 
		and exp_lc_scs.deleted_at is null
		) m group by m.file_no ) replacablesc"), "replacablesc.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(select 
		m.file_no,
		sum(m.lc_sc_value) as lc_sc_value,
		sum(m.local_commission) as local_commission,
		sum(m.foreign_commission) as foreign_commission
		from 
		(
		select 
		exp_lc_scs.file_no,
		exp_lc_scs.lc_sc_value,
		exp_lc_scs.lc_sc_value*(exp_lc_scs.local_commission_per/100) as local_commission,
		exp_lc_scs.lc_sc_value*(exp_lc_scs.foreign_commission_per/100) as foreign_commission
		from exp_lc_scs
		where exp_lc_scs.lc_sc_nature_id=1 
		and exp_lc_scs.deleted_at is null
		) m group by m.file_no ) directlcsc"), "directlcsc.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(
		SELECT 
		exp_lc_scs.file_no,
		sum(imp_backed_exp_lc_scs.amount) as btb_open_amount 
		FROM 
		exp_lc_scs
		join imp_backed_exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
		join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id    
		group by exp_lc_scs.file_no) btb"), "btb.file_no", "=", "exp_lc_scs.file_no")

		->leftJoin(\DB::raw("(
		SELECT 
		exp_lc_scs.file_no,
		sum(exp_pre_credit_lc_scs.equivalent_fc) as pc_taken_mount, 
		min(exp_pre_credits.rate) as pc_taken_rate
		FROM 
		exp_lc_scs
		join exp_pre_credit_lc_scs on exp_lc_scs.id = exp_pre_credit_lc_scs.exp_lc_sc_id 
		join exp_pre_credits on exp_pre_credits.id = exp_pre_credit_lc_scs.exp_pre_credit_id
		where exp_pre_credit_lc_scs.deleted_at is null  
		group by exp_lc_scs.file_no) exppc"), "exppc.file_no", "=", "exp_lc_scs.file_no")

		->join(\DB::raw("(select 
		inv.exp_doc_submission_id,
		sum(inv.invoice_value) as invoice_value,
		sum(inv.discount_amount) as discount_amount,
		sum(inv.bonus_amount) as bonus_amount,
		sum(inv.claim_amount) as claim_amount,
		sum(inv.commission)as commission,
		sum(inv.net_inv_value) as net_inv_value,
		sum(inv.freight_by_supplier) as freight_by_supplier,
		sum(inv.freight_by_buyer) as freight_by_buyer,
		sum(inv.local_commission_invoice) as local_commission_invoice,
		sum(inv.foreign_commission_invoice) as foreign_commission_invoice
		from 
		(select
		exp_invoices.invoice_value,
		exp_invoices.discount_amount,
		exp_invoices.bonus_amount,
		exp_invoices.claim_amount,
		exp_invoices.commission,
		exp_invoices.net_inv_value,
		exp_invoices.freight_by_supplier,
		exp_invoices.freight_by_buyer,
		exp_invoices.invoice_value*(exp_lc_scs.local_commission_per/100) as local_commission_invoice,
		exp_invoices.invoice_value*(exp_lc_scs.foreign_commission_per/100) as foreign_commission_invoice,
		exp_lc_scs.lc_sc_no,
		exp_lc_scs.local_commission_per,
		exp_lc_scs.foreign_commission_per,
		exp_lc_scs.sc_or_lc,
		exp_doc_sub_invoices.id as exp_doc_sub_invoice_id,
		exp_doc_submissions.id as exp_doc_submission_id,
		exp_invoices.id as exp_invoice_id,
		exp_invoices.invoice_no,
		exp_invoices.invoice_date 
		from 
		exp_doc_submissions
		join exp_invoices on exp_invoices.exp_lc_sc_id=exp_doc_submissions.exp_lc_sc_id
		join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id
		join exp_doc_sub_invoices on exp_doc_sub_invoices.exp_doc_submission_id=exp_doc_submissions.id
		and exp_doc_sub_invoices.exp_invoice_id=exp_invoices.id
		and  exp_doc_sub_invoices.deleted_at is null) inv group by inv.exp_doc_submission_id) submissioninvoice"), "submissioninvoice.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		max(exp_pro_rlzs.realization_date) as realization_date
		from
		exp_pro_rlzs
		group by
		exp_pro_rlzs.exp_doc_submission_id) realization"), "realization.exp_doc_submission_id", "=", "exp_doc_submissions.id")


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=4
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_docngl"), "sub_docngl.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=4
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_docngl"), "rlz_amt_docngl.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=4
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_docngl"), "rlz_ded_docngl.exp_doc_submission_id", "=", "exp_doc_submissions.id")


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=8
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_btbm"), "sub_btbm.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=8
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_btbm"), "rlz_amt_btbm.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=8
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_btbm"), "rlz_ded_btbm.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=12
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_erq"), "sub_erq.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=12
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_erq"), "rlz_amt_erq.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=12
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_erq"), "rlz_ded_erq.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=5
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_pc"), "sub_pc.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=5
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_pc"), "rlz_amt_pc.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=5
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_pc"), "rlz_ded_pc.exp_doc_submission_id", "=", "exp_doc_submissions.id")


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=13
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_mdan"), "sub_mdan.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=13
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_mdan"), "rlz_amt_mdan.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=13
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_mdan"), "rlz_ded_mdan.exp_doc_submission_id", "=", "exp_doc_submissions.id")


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=14
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_mdas"), "sub_mdas.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=14
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_mdas"), "rlz_amt_mdas.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=14
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_mdas"), "rlz_ded_mdas.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=15
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_mdau"), "sub_mdau.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=15
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_mdau"), "rlz_amt_mdau.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=15
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_mdau"), "rlz_ded_mdau.exp_doc_submission_id", "=", "exp_doc_submissions.id")


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=16
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_sct"), "sub_sct.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=16
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_sct"), "rlz_amt_sct.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=16
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_sct"), "rlz_ded_sct.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=17
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_fbc"), "sub_fbc.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=17
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_fbc"), "rlz_amt_fbc.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=17
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_fbc"), "rlz_ded_fbc.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=18
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_cda"), "sub_cda.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=18
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_cda"), "rlz_amt_cda.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=18
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_cda"), "rlz_ded_cda.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=11
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_disc"), "sub_disc.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=11
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_disc"), "rlz_amt_disc.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=11
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_disc"), "rlz_ded_disc.exp_doc_submission_id", "=", "exp_doc_submissions.id")


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=19
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_shtrlz"), "sub_shtrlz.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=19
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_shtrlz"), "rlz_amt_shtrlz.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=19
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_shtrlz"), "rlz_ded_shtrlz.exp_doc_submission_id", "=", "exp_doc_submissions.id")



		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=20
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_discrip"), "sub_discrip.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=20
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_discrip"), "rlz_amt_discrip.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=20
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_discrip"), "rlz_ded_discrip.exp_doc_submission_id", "=", "exp_doc_submissions.id")


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=21
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_slcommi"), "sub_slcommi.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=21
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_slcommi"), "rlz_amt_slcommi.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=21
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_slcommi"), "rlz_ded_slcommi.exp_doc_submission_id", "=", "exp_doc_submissions.id")


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=22
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_expdocp"), "sub_expdocp.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=22
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_expdocp"), "rlz_amt_expdocp.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=22
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_expdocp"), "rlz_ded_expdocp.exp_doc_submission_id", "=", "exp_doc_submissions.id")


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=23
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_cntrlf"), "sub_cntrlf.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=23
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_cntrlf"), "rlz_amt_cntrlf.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=23
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_cntrlf"), "rlz_ded_cntrlf.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=24
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_othcrg"), "sub_othcrg.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=24
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_othcrg"), "rlz_amt_othcrg.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=24
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_othcrg"), "rlz_ded_othcrg.exp_doc_submission_id", "=", "exp_doc_submissions.id")


		->leftJoin(\DB::raw("(
		select
		exp_doc_sub_transections.exp_doc_submission_id,
		sum(exp_doc_sub_transections.doc_value) as doc_value
		from
		exp_doc_sub_transections
		join commercial_heads on commercial_heads.id=exp_doc_sub_transections.commercialhead_id
		where commercial_heads.commercialhead_type_id=25
		group by
		exp_doc_sub_transections.exp_doc_submission_id) sub_exchvari"), "sub_exchvari.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_amounts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_amounts on exp_pro_rlz_amounts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_amounts.commercial_head_id
		where commercial_heads.commercialhead_type_id=25
		and exp_pro_rlz_amounts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_amt_exchvari"), "rlz_amt_exchvari.exp_doc_submission_id", "=", "exp_doc_submissions.id")

		->leftJoin(\DB::raw("(
		select
		exp_pro_rlzs.exp_doc_submission_id,
		sum(exp_pro_rlz_deducts.doc_value) as doc_value
		from
		exp_pro_rlzs
		join exp_pro_rlz_deducts on exp_pro_rlz_deducts.exp_pro_rlz_id=exp_pro_rlzs.id
		join commercial_heads on commercial_heads.id=exp_pro_rlz_deducts.commercial_head_id
		where commercial_heads.commercialhead_type_id=25
		and exp_pro_rlz_deducts.deleted_at is null
		group by
		exp_pro_rlzs.exp_doc_submission_id) rlz_ded_exchvari"), "rlz_ded_exchvari.exp_doc_submission_id", "=", "exp_doc_submissions.id")
		->when(request('date_from'), function ($q) {
			return $q->where('exp_doc_submissions.submission_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
			return $q->where('exp_doc_submissions.submission_date', '<=',request('date_to', 0));
		})
		->when(request('possible_date_from'), function ($q) {
			return $q->where('exp_doc_submissions.possible_realization_date', '>=',request('possible_date_from', 0));
		})
		->when(request('possible_date_to'), function ($q) {
			return $q->where('exp_doc_submissions.possible_realization_date', '<=',request('possible_date_to', 0));
		})
		->when(request('bank_id'), function ($q) {
			return $q->where('banks.id', '=', request('bank_id', 0));
		})
		
		->when(request('beneficiary_id'), function ($q) {
			return $q->where('exp_lc_scs.beneficiary_id', '=', request('beneficiary_id', 0));
		})
		->when(request('buyer_id'), function ($q) {
			return $q->where('exp_lc_scs.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('file_no'), function ($q) {
			return $q->where('exp_lc_scs.file_no', '=', request('file_no', 0));
		})
		->when(request('submission_id'), function ($q) {
			return $q->where('exp_doc_submissions.id', '=', request('submission_id', 0));
		})
		->orderBy('exp_lc_scs.file_no')
		->get()
		->map(function ($rows){
		return $rows;
		});
        return $rows;
     }


    public function buyersummery()
    {
        $buyerdata=[];
        $datas=$this->getData();
        foreach ($datas as $rows){
            $rows->file_value=$rows->rp_lc_sc_value+$rows->di_lc_sc_value;
            $rows->foreign_commission_file=$rows->rp_lc_sc_foreign_commission+$rows->di_lc_sc_foreign_commission;
            $rows->local_commission_file=$rows->rp_lc_sc_local_commission+$rows->di_lc_sc_local_commission;
            $rows->freight_file=0;
            $rows->net_value_file=$rows->file_value-($rows->foreign_commission_file+$rows->local_commission_file+$rows->freight_file);
            $rows->btb_openable=$rows->net_value_file*(70/100);
            $rows->btb_open_amount_per=0;

            $rows->btb_open_amount_per=0;
            $rows->pc_taken_amount_per=0;
            if($rows->net_value_file){
            $rows->btb_open_amount_per=($rows->btb_open_amount/$rows->net_value_file)*100;
            $rows->pc_taken_amount_per=($rows->pc_taken_mount/$rows->net_value_file)*100;
            }

            $rows->yet_to_btb_opened=$rows->btb_openable-$rows->btb_open_amount;


            

            


            $rows->net_invoice_value=$rows->invoice_value-($rows->bonus_amount+$rows->claim_amount+$rows->local_commission_invoice+$rows->local_commission_invoice+$rows->freight_by_supplier+$rows->freight_by_buyer);

            $rows->deduction=$rows->discount_amount+$rows->bonus_amount+$rows->claim_amount;

            $rows->net_invoice_value=$rows->invoice_value-($rows->bonus_amount+$rows->claim_amount+$rows->local_commission_invoice+$rows->foreign_commission_invoice+$rows->freight_by_supplier+$rows->freight_by_buyer+$rows->deduction);

            $rows->freight_invoice=$rows->freight_by_supplier+$rows->freight_by_buyer;

            if($rows->btb_open_amount_per>=70){
                $rows->fc_held_btb_lc=$rows->net_invoice_value*($rows->btb_open_amount_per/100);
            }
            else
            {
                $rows->fc_held_btb_lc=$rows->net_invoice_value*(70/100);    
            }

            $rows->erq_account=$rows->net_invoice_value*(1/100);
            $rows->packaing_credit=$rows->net_invoice_value*(16/100);
            $rows->cost_of_packaing_credit=$rows->pc_taken_rate;
            //$rows->salary_account=$rows->net_invoice_value*(10/100);

            $rows->mda_normal=$rows->net_invoice_value*(1/100);
            $rows->inc_int_pur=$rows->net_invoice_value*(1/100);
            $rows->source_tax=$rows->net_invoice_value*(1/100);
            $rows->frg_bank_charge=$rows->net_invoice_value*(3/100);
            //$rows->centarl_fund=$rows->net_invoice_value*(0.03/100);
            //$rows->nego_comision=500/$rows->exch_rate;
            //$rows->others_account=$rows->mda_normal+$rows->inc_int_pur+$rows->source_tax+$rows->centarl_fund+$rows->nego_comision;
            
            $rows->current_account=$rows->net_invoice_value-($rows->fc_held_btb_lc+$rows->erq_account+$rows->packaing_credit+$rows->mda_normal+$rows->inc_int_pur+$rows->source_tax+$rows->frg_bank_charge);

            
            $rows->ad_pu_amount=$rows->sub_docngl_doc_value+$rows->rlz_amt_docngl_doc_value+$rows->rlz_ded_docngl_doc_value;

            /*$rows->btbm_built=$rows->sub_btbm_doc_value+$rows->rlz_amt_btbm_doc_value+$rows->rlz_ded_btbm_doc_value;
            $rows->erq_cr=$rows->sub_erq_doc_value+$rows->rlz_amt_erq_doc_value+$rows->rlz_ded_erq_doc_value;
            $rows->pc_ad=$rows->sub_pc_doc_value+$rows->rlz_amt_pc_doc_value+$rows->rlz_ded_pc_doc_value;
            $rows->mdan_cr=$rows->sub_mdan_doc_value+$rows->rlz_amt_mdan_doc_value+$rows->rlz_ded_mdan_doc_value;
            $rows->mdas_cr=$rows->sub_mdas_doc_value+$rows->rlz_amt_mdas_doc_value+$rows->rlz_ded_mdas_doc_value;
            $rows->mdau_cr=$rows->sub_mdau_doc_value+$rows->rlz_amt_mdau_doc_value+$rows->rlz_ded_mdau_doc_value;

            $rows->sct_deduct=$rows->sub_sct_doc_value+$rows->rlz_amt_sct_doc_value+$rows->rlz_ded_sct_doc_value;
            $rows->fbc_ad=$rows->sub_fbc_doc_value+$rows->rlz_amt_fbc_doc_value+$rows->rlz_ded_fbc_doc_value;
            $rows->cda_cr=$rows->sub_cda_doc_value+$rows->rlz_amt_cda_doc_value+$rows->rlz_ded_cda_doc_value;
            $rows->disc_cr=$rows->sub_disc_doc_value+$rows->rlz_amt_disc_doc_value+$rows->rlz_ded_disc_doc_value;
            $rows->discrip_cr=$rows->sub_discrip_doc_value+$rows->rlz_amt_discrip_doc_value+$rows->rlz_ded_discrip_doc_value;

            $rows->commi_cr=$rows->sub_slcommi_doc_value+$rows->rlz_amt_slcommi_doc_value+$rows->rlz_ded_slcommi_doc_value;
            $rows->sht_rlz=$rows->sub_shtrlz_doc_value+$rows->rlz_amt_shtrlz_doc_value+$rows->rlz_ded_shtrlz_doc_value;

            $rows->exp_docp=$rows->sub_expdocp_doc_value+$rows->rlz_amt_expdocp_doc_value+$rows->rlz_ded_expdocp_doc_value;
            $rows->cntrl_fund=$rows->sub_cntrlf_doc_value+$rows->rlz_amt_cntrlf_doc_value+$rows->rlz_ded_cntrlf_doc_value;
            $rows->oth_crg=$rows->sub_othcrg_doc_value+$rows->rlz_amt_othcrg_doc_value+$rows->rlz_ded_othcrg_doc_value;
            $rows->exch_vari=$rows->sub_exchvari_doc_value+$rows->rlz_amt_exchvari_doc_value+$rows->rlz_ded_exchvari_doc_value;

            $rows->total_cr=$rows->btbm_built+
            $rows->erq_cr+
            $rows->pc_ad+
            $rows->mdan_cr+
            $rows->mdas_cr+
            $rows->mdau_cr+
            $rows->sct_deduct+
            $rows->fbc_ad+
            $rows->cda_cr+
            $rows->disc_cr+
            $rows->discrip_cr+
            $rows->commi_cr+
            $rows->sht_rlz+
            $rows->exp_docp+
            $rows->cntrl_fund+
            $rows->oth_crg+
            $rows->exch_vari;*/

            $rows->btbm_built=$rows->rlz_amt_btbm_doc_value+$rows->rlz_ded_btbm_doc_value;
            $rows->erq_cr=$rows->rlz_amt_erq_doc_value+$rows->rlz_ded_erq_doc_value;
            $rows->pc_ad=$rows->rlz_amt_pc_doc_value+$rows->rlz_ded_pc_doc_value;
            $rows->mdan_cr=$rows->rlz_amt_mdan_doc_value+$rows->rlz_ded_mdan_doc_value;
            $rows->mdas_cr=$rows->rlz_amt_mdas_doc_value+$rows->rlz_ded_mdas_doc_value;
            $rows->mdau_cr=$rows->rlz_amt_mdau_doc_value+$rows->rlz_ded_mdau_doc_value;

            $rows->sct_deduct=$rows->rlz_amt_sct_doc_value+$rows->rlz_ded_sct_doc_value;
            $rows->fbc_ad=$rows->rlz_amt_fbc_doc_value+$rows->rlz_ded_fbc_doc_value;
            $rows->cda_cr=$rows->rlz_amt_cda_doc_value+$rows->rlz_ded_cda_doc_value;
            $rows->disc_cr=$rows->rlz_amt_disc_doc_value+$rows->rlz_ded_disc_doc_value;
            $rows->discrip_cr=$rows->rlz_amt_discrip_doc_value+$rows->rlz_ded_discrip_doc_value;

            $rows->commi_cr=$rows->rlz_amt_slcommi_doc_value+$rows->rlz_ded_slcommi_doc_value;
            $rows->sht_rlz=$rows->rlz_amt_shtrlz_doc_value+$rows->rlz_ded_shtrlz_doc_value;

            $rows->exp_docp=$rows->rlz_amt_expdocp_doc_value+$rows->rlz_ded_expdocp_doc_value;
            $rows->cntrl_fund=$rows->rlz_amt_cntrlf_doc_value+$rows->rlz_ded_cntrlf_doc_value;
            $rows->oth_crg=$rows->rlz_amt_othcrg_doc_value+$rows->rlz_ded_othcrg_doc_value;
            $rows->exch_vari=$rows->rlz_amt_exchvari_doc_value+$rows->rlz_ded_exchvari_doc_value;
            $rows->total_cr=$rows->ad_pu_amount+
            $rows->btbm_built+
            $rows->erq_cr+
            $rows->pc_ad+
            $rows->mdan_cr+
            $rows->mdas_cr+
            $rows->mdau_cr+
            $rows->sct_deduct+
            $rows->fbc_ad+
            $rows->cda_cr+
            $rows->disc_cr+
            $rows->discrip_cr+
            $rows->commi_cr+
            $rows->sht_rlz+
            $rows->exp_docp+
            $rows->cntrl_fund+
            $rows->oth_crg+
            $rows->exch_vari;

            $buyerdata[$rows->buyer_id]['buyer_name']=$rows->buyer_name;

            if(isset($buyerdata[$rows->buyer_id]['net_invoice_value'])){
            $buyerdata[$rows->buyer_id]['net_invoice_value']+=$rows->net_invoice_value;
            }
            else{
            $buyerdata[$rows->buyer_id]['net_invoice_value']=$rows->net_invoice_value;
            }
            if(isset($buyerdata[$rows->buyer_id]['total_cr'])){
            $buyerdata[$rows->buyer_id]['total_cr']+=$rows->total_cr;
            }
            else{
            $buyerdata[$rows->buyer_id]['total_cr']=$rows->total_cr;
            }
        }

        $buyerdatas=[];


        foreach($buyerdata as $key=>$value){
            $row=[
                'balance_amount'=>number_format($value['net_invoice_value']-$value['total_cr'],2),
                'buyer_name'=>$value['buyer_name'],
                'net_invoice_value'=>number_format($value['net_invoice_value'],2),
                'total_cr'=>number_format($value['total_cr'],2),
                
            ];
         array_push($buyerdatas,$row);
        }
        echo json_encode($buyerdatas);
    }

    public function buyerFollowUp(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $possible_date_from=request('possible_date_from',0);
        $possible_date_to=request('possible_date_to',0);
        $bank_id=request('bank_id',0);
        $beneficiary_id=request('beneficiary_id',0);
        $buyer_id=request('buyer_id',0);
        $file_no=request('file_no',0);
        $submission_id=request('submission_id',0);

        $rows=$this->expdocsubmission
        ->selectRaw('
        exp_doc_submissions.*,
        exp_lc_scs.lc_sc_no,
        exp_lc_scs.file_no,
        exp_lc_scs.beneficiary_id,
        exp_lc_scs.buyer_id,
        exp_lc_scs.buyers_bank, 
        exp_lc_scs.currency_id, 
        exp_lc_scs.exch_rate, 
        buyers.name as buyer_name,
        banks.name as bank_name,
        bank_branches.branch_name,
        bank_branches.address as bank_address,
        bank_branches.contact,
        companies.code as company_name,
        currencies.name as currency_name,
        
        btb.btb_open_amount,
        exppc.pc_taken_mount,
        exppc.pc_taken_rate,
        submissioninvoice.invoice_value as invoice_value,
        submissioninvoice.discount_amount as discount_amount,
        submissioninvoice.bonus_amount as bonus_amount,
        submissioninvoice.claim_amount as claim_amount,
        submissioninvoice.commission as commission,
        submissioninvoice.net_inv_value as net_inv_value,
        submissioninvoice.freight_by_supplier as freight_by_supplier,
        submissioninvoice.freight_by_buyer as freight_by_buyer,
        submissioninvoice.local_commission_invoice as local_commission_invoice,
        submissioninvoice.foreign_commission_invoice as foreign_commission_invoice
        ')
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
        })
        ->leftJoin('bank_branches', function($join) {
            $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join) {
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->leftJoin(\DB::raw("(
        select 
        m.file_no,
        sum(m.lc_sc_value) as lc_sc_value,
        sum(m.local_commission) as local_commission,
        sum(m.foreign_commission) as foreign_commission
        from 
        (
        select 
        exp_lc_scs.file_no,
        exp_lc_scs.lc_sc_value,
        exp_lc_scs.lc_sc_value*(exp_lc_scs.local_commission_per/100) as local_commission,
        exp_lc_scs.lc_sc_value*(exp_lc_scs.foreign_commission_per/100) as foreign_commission
        from exp_lc_scs
        where exp_lc_scs.sc_or_lc=1
        and exp_lc_scs.lc_sc_nature_id=2 
        and exp_lc_scs.deleted_at is null
        ) m group by m.file_no ) replacablesc"), "replacablesc.file_no", "=", "exp_lc_scs.file_no")

        ->leftJoin(\DB::raw("(select 
        m.file_no,
        sum(m.lc_sc_value) as lc_sc_value,
        sum(m.local_commission) as local_commission,
        sum(m.foreign_commission) as foreign_commission
        from 
        (
        select 
        exp_lc_scs.file_no,
        exp_lc_scs.lc_sc_value,
        exp_lc_scs.lc_sc_value*(exp_lc_scs.local_commission_per/100) as local_commission,
        exp_lc_scs.lc_sc_value*(exp_lc_scs.foreign_commission_per/100) as foreign_commission
        from exp_lc_scs
        where exp_lc_scs.lc_sc_nature_id=1 
        and exp_lc_scs.deleted_at is null
        ) m group by m.file_no ) directlcsc"), "directlcsc.file_no", "=", "exp_lc_scs.file_no")

        ->leftJoin(\DB::raw("(
        SELECT 
        exp_lc_scs.file_no,
        sum(imp_backed_exp_lc_scs.amount) as btb_open_amount 
        FROM 
        exp_lc_scs
        join imp_backed_exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id    
        group by exp_lc_scs.file_no) btb"), "btb.file_no", "=", "exp_lc_scs.file_no")

        ->leftJoin(\DB::raw("(
        SELECT 
        exp_lc_scs.file_no,
        sum(exp_pre_credit_lc_scs.equivalent_fc) as pc_taken_mount, 
        min(exp_pre_credits.rate) as pc_taken_rate
        FROM 
        exp_lc_scs
        join exp_pre_credit_lc_scs on exp_lc_scs.id = exp_pre_credit_lc_scs.exp_lc_sc_id 
        join exp_pre_credits on exp_pre_credits.id = exp_pre_credit_lc_scs.exp_pre_credit_id
        where exp_pre_credit_lc_scs.deleted_at is null  
        group by exp_lc_scs.file_no) exppc"), "exppc.file_no", "=", "exp_lc_scs.file_no")

        ->join(\DB::raw("(select 
        inv.exp_doc_submission_id,
        sum(inv.invoice_value) as invoice_value,
        sum(inv.discount_amount) as discount_amount,
        sum(inv.bonus_amount) as bonus_amount,
        sum(inv.claim_amount) as claim_amount,
        sum(inv.commission)as commission,
        sum(inv.net_inv_value) as net_inv_value,
        sum(inv.freight_by_supplier) as freight_by_supplier,
        sum(inv.freight_by_buyer) as freight_by_buyer,
        sum(inv.local_commission_invoice) as local_commission_invoice,
        sum(inv.foreign_commission_invoice) as foreign_commission_invoice
        from 
        (select
        exp_invoices.invoice_value,
        exp_invoices.discount_amount,
        exp_invoices.bonus_amount,
        exp_invoices.claim_amount,
        exp_invoices.commission,
        exp_invoices.net_inv_value,
        exp_invoices.freight_by_supplier,
        exp_invoices.freight_by_buyer,
        exp_invoices.invoice_value*(exp_lc_scs.local_commission_per/100) as local_commission_invoice,
        exp_invoices.invoice_value*(exp_lc_scs.foreign_commission_per/100) as foreign_commission_invoice,
        exp_lc_scs.lc_sc_no,
        exp_lc_scs.local_commission_per,
        exp_lc_scs.foreign_commission_per,
        exp_lc_scs.sc_or_lc,
        exp_doc_sub_invoices.id as exp_doc_sub_invoice_id,
        exp_doc_submissions.id as exp_doc_submission_id,
        exp_invoices.id as exp_invoice_id,
        exp_invoices.invoice_no,
        exp_invoices.invoice_date 
        from 
        exp_doc_submissions
        join exp_invoices on exp_invoices.exp_lc_sc_id=exp_doc_submissions.exp_lc_sc_id
        join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id
        join exp_doc_sub_invoices on exp_doc_sub_invoices.exp_doc_submission_id=exp_doc_submissions.id
        and exp_doc_sub_invoices.exp_invoice_id=exp_invoices.id
        and  exp_doc_sub_invoices.deleted_at is null) inv group by inv.exp_doc_submission_id) submissioninvoice"), "submissioninvoice.exp_doc_submission_id", "=", "exp_doc_submissions.id")
        ->leftJoin('exp_pro_rlzs',function($join){
            $join->on('exp_pro_rlzs.exp_doc_submission_id','=','exp_doc_submissions.id');
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('exp_doc_submissions.submission_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('exp_doc_submissions.submission_date', '<=',request('date_to', 0));
        })
        ->when(request('possible_date_from'), function ($q) {
            return $q->where('exp_doc_submissions.possible_realization_date', '>=',request('possible_date_from', 0));
        })
        ->when(request('possible_date_to'), function ($q) {
            return $q->where('exp_doc_submissions.possible_realization_date', '<=',request('possible_date_to', 0));
        })
        ->when(request('bank_id'), function ($q) {
            return $q->where('banks.id', '=', request('bank_id', 0));
        })
        ->when(request('beneficiary_id'), function ($q) {
            return $q->where('exp_lc_scs.beneficiary_id', '=', request('beneficiary_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
            return $q->where('exp_lc_scs.buyer_id', '=', request('buyer_id', 0));
        })
        ->when(request('file_no'), function ($q) {
            return $q->where('exp_lc_scs.file_no', '=', request('file_no', 0));
        })
        ->when(request('submission_id'), function ($q) {
            return $q->where('exp_doc_submissions.id', '=', request('submission_id', 0));
        })
        ->whereNull('exp_pro_rlzs.exp_doc_submission_id')
        ->orderBy('exp_lc_scs.file_no')
        ->get()
        ->map(function ($rows){
            $today=Carbon::parse(date('Y-m-d'));
            $possible_realization_date = Carbon::parse($rows->possible_realization_date);
            $realization_date = Carbon::parse($rows->realization_date);
            $overDueDays=0;
            if(!$rows->realization_date && ($today > $possible_realization_date)){
            $overDueDays = $possible_realization_date->diffInDays($today);
            }

            $rows->over_due_days=$overDueDays;
            $rows->deduction=$rows->discount_amount+$rows->bonus_amount+$rows->claim_amount;
            $rows->net_invoice_value=$rows->invoice_value-($rows->bonus_amount+$rows->claim_amount+$rows->local_commission_invoice+$rows->foreign_commission_invoice+$rows->freight_by_supplier+$rows->freight_by_buyer+$rows->deduction);

            $rows->bnk_to_bnk_cour_date=$rows->bnk_to_bnk_cour_date?date('d-M-Y',strtotime($rows->bnk_to_bnk_cour_date)):'--';
            $rows->bank_ref_date=$rows->bank_ref_date?date('d-M-Y',strtotime($rows->bank_ref_date)):'--';
            $rows->possible_realization_date=$rows->possible_realization_date?date('d-M-Y',strtotime($rows->possible_realization_date)):'--';
            $rows->invoice_value=number_format($rows->invoice_value,2);
            $rows->net_invoice_value=number_format($rows->net_invoice_value,2);
            return $rows;
        });
        echo json_encode($rows) ;
    }
}