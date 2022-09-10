<?php

namespace App\Http\Controllers\Report\Commercial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use Illuminate\Support\Carbon;

class ImportConsignmentReportController extends Controller
{
	private $accchartctrlhead;
	private $accchartsubgroup;
    private $currency;
    private $bank;
    private $expdocsubmission;
    private $bankbranch;
    private $commercialhead;
	public function __construct(
		ExpLcScRepository $expsalescontract,
		CurrencyRepository $currency, 
		CompanyRepository $company, 
		BuyerRepository $buyer,
		SupplierRepository $supplier,
		BankRepository $bank,
		ExpDocSubmissionRepository $expdocsubmission,
		BankBranchRepository $bankbranch,
		CommercialHeadRepository $commercialhead,
		ImpLcRepository $implc

	)
    {
		$this->expsalescontract    = $expsalescontract;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
        $this->company = $company;
        $this->bank = $bank;
        $this->expdocsubmission = $expdocsubmission;
        $this->bankbranch = $bankbranch;
        $this->commercialhead = $commercialhead;
        $this->implc = $implc;

		$this->middleware('auth');
		//$this->middleware('permission:view.liabilitycoveragereports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
		$menu=array_prepend(config('bprs.menu'),'-Select-','');
		$lctype = array_prepend(config('bprs.lctype'), '-Select-','');
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
        return Template::loadView('Report.Commercial.ImportConsignmentReport',['company'=>$company,'supplier'=>$supplier,'menu'=>$menu,'lctype'=>$lctype,'bankbranch'=>$bankbranch]);
    }

    
     public function htmlgrid()
    {
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
		,'name','id'),'--','');
		$lctype = array_prepend(config('bprs.lctype'), '--','');
        $payterm = array_prepend(config('bprs.payterm'), '--','');
        $docStatus = array_prepend(config('bprs.docStatus'), '--','');
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'--','');
        $deliveryMode = array_prepend(config('bprs.deliveryMode'), '--','');
        $incoterm = array_prepend(config('bprs.incoterm'), '--','');

       	$item= [
	        0=>"Independent",
	        1=>"Fabric",
	        2=>"Trim",
	        3=>"Yarn",
	        4=>"Knit",
	        5=>"AOP",
	        6=>"Dyeing",
	        7=>"Dyes & Chemical",
	        8=>"General Item" ,
	        9=>"Yarn Dyeing",
	        10=>"Embellishment",
	        11=>"General Service"
    	];

    
    	$explcfile = $this->implc
			->join('imp_backed_exp_lc_scs',function($join){
				$join->on('imp_lcs.id','=','imp_backed_exp_lc_scs.imp_lc_id');
			})
			->join('exp_lc_scs',function($join){
				$join->on('exp_lc_scs.id','=','imp_backed_exp_lc_scs.exp_lc_sc_id');
			})
			->get([
				'imp_lcs.id',
				'exp_lc_scs.file_no',
			]);

		$explcfileArr=[];
		foreach($explcfile as $data){
			$explcfileArr[$data->id]=$data->file_no;
		}


    	$rows=$this->getData()->map(function ($rows) use($bankbranch,$payterm,$lctype,$docStatus,$commercialhead, $deliveryMode,$incoterm,$item,$explcfileArr){
		$rows->file_no=isset($explcfileArr[$rows->id])?$explcfileArr[$rows->id]:'--';
    	$original_doc_rcv_date = Carbon::parse($rows->original_doc_rcv_date);
		$bank_accep_date = Carbon::parse($rows->bank_accep_date);
		$takenDaysToAccept = $original_doc_rcv_date->diffInDays($bank_accep_date)+1;

		$rows->days_taken_to_accept=($rows->original_doc_rcv_date&&$rows->bank_accep_date)?$takenDaysToAccept:'--';

		$lc_application_date = Carbon::parse($rows->lc_application_date);
		$lc_date = Carbon::parse($rows->lc_date);
		$takenDaysToApply = $lc_application_date->diffInDays($lc_date)+1;
		$rows->days_taken_to_apply=($rows->lc_application_date&&$rows->lc_date)?$takenDaysToApply:'--';


    	$rows->menu_id=$item[$rows->menu_id];
    	$rows->lc_no=$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv;
    	$rows->lc_application_date=$rows->lc_application_date?date('d-M-Y',strtotime($rows->lc_application_date)):'--';
    	$rows->lc_date=$rows->lc_date?date('d-M-Y',strtotime($rows->lc_date)):'--';

    	$rows->bankbranch=$bankbranch[$rows->issuing_bank_branch_id];
    	$rows->invoice_date=$rows->invoice_date?date('d-M-Y',strtotime($rows->invoice_date)):'--';
    	$rows->payterm=$payterm[$rows->pay_term_id];
    	$rows->paln_shipment_date=$rows->last_delivery_date?date('d-M-Y',strtotime($rows->last_delivery_date)):'--';
    	$rows->actual_shipment_date=$rows->shipment_date?date('d-M-Y',strtotime($rows->shipment_date)):'--';
    	$rows->docstatus=$docStatus[$rows->doc_status];
    	$rows->copy_doc_rcv_date=$rows->copy_doc_rcv_date?date('d-M-Y',strtotime($rows->copy_doc_rcv_date)):'--';
    	$rows->original_doc_rcv_date=$rows->original_doc_rcv_date?date('d-M-Y',strtotime($rows->original_doc_rcv_date)):'--';
    	$rows->company_accep_date=$rows->company_accep_date?date('d-M-Y',strtotime($rows->company_accep_date)):'--';
    	$rows->bank_accep_date=$rows->bank_accep_date?date('d-M-Y',strtotime($rows->bank_accep_date)):'--';

    	$rows->maturity_date=$rows->maturity_date?date('d-M-Y',strtotime($rows->maturity_date)):'--';
    	$rows->payment_date=$rows->payment_date?date('d-M-Y',strtotime($rows->payment_date)):'--';

    	$rows->commercial_head_id=$commercialhead[$rows->commercial_head_id];
    	$rows->shipment_mode=$deliveryMode[$rows->shipment_mode];
    	$rows->bl_cargo_date=$rows->bl_cargo_date?date('d-M-Y',strtotime($rows->bl_cargo_date)):'--';
    	$rows->doc_to_cf_date=$rows->doc_to_cf_date?date('d-M-Y',strtotime($rows->doc_to_cf_date)):'--';
    	$rows->eta_date=$rows->eta_date?date('d-M-Y',strtotime($rows->eta_date)):'--';
    	$rows->discharge_date=$rows->discharge_date?date('d-M-Y',strtotime($rows->discharge_date)):'--';
    	$rows->ic_received_date=$rows->ic_received_date?date('d-M-Y',strtotime($rows->ic_received_date)):'--';
    	$rows->port_clearing_date=$rows->port_clearing_date?date('d-M-Y',strtotime($rows->port_clearing_date)):'--';
    	$rows->incoterm=$incoterm[$rows->incoterm_id];
    	$rows->lctype=$lctype[$rows->lc_type_id];
    	$rows->overdue=$rows->doc_value-$rows->paid_amount;
    	$rows->doc_value=number_format($rows->doc_value,2);
    	$rows->lc_amount=number_format($rows->lc_amount,2);
    	$rows->qty=number_format($rows->qty,0);
    	$rows->paid_amount=number_format($rows->paid_amount,0);
    	$rows->overdue=number_format($rows->overdue,0);

    	$rows->id=$rows->id;


		return $rows;
		});
    	echo json_encode($rows);
    }
    
     private function getData()
     {
     	$date_from=request('date_from',0);
     	$date_to=request('date_to',0);
     	$beneficiary_id=request('beneficiary_id',0);
     	$supplier_id=request('supplier_id',0);
     	$menu_id=request('menu_id',0);
     	$lc_type_id=request('lc_type_id',0);
     	$lc_no=request('lc_no',0);
     	$issuing_bank_branch_id=request('issuing_bank_branch_id',0);

     	$lcdate='';
     	if($date_from && $date_to){
	     	$lcdate=" and imp_lcs.lc_date between '". $date_from ."' and '". $date_to ."'";
     	}

		$beneficiary='';
		if($beneficiary_id){
			$beneficiary=" and imp_lcs.company_id= $beneficiary_id ";
		}

		$supplier='';
		if($supplier_id){
			$supplier=" and imp_lcs.supplier_id= $supplier_id ";
		}

		$menu='';
		if($menu_id){
			$menu=" and imp_lcs.menu_id= $menu_id ";
		}

		$lctype='';
		if($lc_type_id){
			$lctype=" and imp_lcs.lc_type_id= $lc_type_id ";
		}

		$lcno='';
		if($lc_no){
			$lcno=" and imp_lcs.lc_no_iv'". $lc_no ."'";
		}

		$issuing_bank_branch='';
		if($issuing_bank_branch_id){
			$issuing_bank_branch=" and imp_lcs.issuing_bank_branch_id=$issuing_bank_branch_id";
		}




		$rows = collect(\DB::select("
			select 
			imp_lcs.id,
			imp_lcs.menu_id,
			imp_lcs.lc_no_i,
			imp_lcs.lc_no_ii,
			imp_lcs.lc_no_iii,
			imp_lcs.lc_no_iv,
			imp_lcs.company_id,
			companies.code as company_name,
			imp_lcs.supplier_id,
			suppliers.name as supplier_name,
			currencies.code as currency_code,
			imp_lcs.issuing_bank_branch_id,
			imp_lcs.pay_term_id,
			imp_lcs.tenor,
			imp_lcs.lc_date,
			imp_lcs.lc_application_date,
			imp_lcs.lc_type_id,
			imp_lcs.last_delivery_date,
			imp_lcs.expiry_date,

			imp_lcs.exch_rate,
			case when 
			imp_lcs.menu_id=1
			then sum(po_fabrics.amount)
			when 
			imp_lcs.menu_id=2
			then sum(po_trims.amount)
			when 
			imp_lcs.menu_id=3
			then sum(po_yarns.amount)
			when 
			imp_lcs.menu_id=4
			then sum(po_knit_services.amount)
			when 
			imp_lcs.menu_id=5
			then sum(po_aop_services.amount)
			when 
			imp_lcs.menu_id=6
			then sum(po_dyeing_services.amount)
			when 
			imp_lcs.menu_id=7
			then sum(po_dye_chems.amount)
			when 
			imp_lcs.menu_id=8
			then sum(po_generals.amount)
			when 
			imp_lcs.menu_id=9
			then sum(po_yarn_dyeings.amount)
			when 
			imp_lcs.menu_id=10
			then sum(po_emb_services.amount)
			when 
			imp_lcs.menu_id=11
			then sum(po_general_services.amount)
			else 0
			end as lc_amount,
			imp_doc_accepts.id as imp_doc_accept_id,                    
			imp_doc_accepts.imp_lc_id,              
			imp_doc_accepts.invoice_no,           
			imp_doc_accepts.invoice_date,           
			imp_doc_accepts.shipment_date,         
			imp_doc_accepts.company_accep_date,     
			imp_doc_accepts.bank_accep_date, 
			imp_doc_accepts.discharge_date,
			imp_doc_accepts.port_clearing_date,       
			imp_doc_accepts.bank_ref,               
			imp_doc_accepts.commercial_head_id,
			imp_doc_accepts.loan_ref,             
			imp_doc_accepts.doc_value,            
			imp_doc_accepts.rate,                
			imp_doc_accepts.bl_cargo_no ,           
			imp_doc_accepts.bl_cargo_date,          
			imp_doc_accepts.shipment_mode,         
			imp_doc_accepts.doc_status,           
			imp_doc_accepts.copy_doc_rcv_date,     
			imp_doc_accepts.original_doc_rcv_date,
			imp_doc_accepts.doc_to_cf_date,
			imp_doc_accepts.feeder_vessel,
			imp_doc_accepts.mother_vessel,
			imp_doc_accepts.eta_date,
			imp_doc_accepts.ic_received_date,
			imp_doc_accepts.shipping_bill_no,
			imp_doc_accepts.incoterm_id,
			imp_doc_accepts.incoterm_place,
			imp_doc_accepts.port_of_loading,
			imp_doc_accepts.port_of_discharge,
			imp_doc_accepts.internal_file_no,
			imp_doc_accepts.bill_of_entry_no,
			imp_doc_accepts.psi_ref_no,
			imp_doc_accepts.maturity_date,
			imp_doc_accepts.container_no,
			imp_doc_accepts.qty,
			imp_doc_accepts.remarks,
			impliabilityadjusts.payment_date,
			impliabilityadjusts.amount as paid_amount

			from imp_lcs  
			left join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
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
			left join imp_doc_accepts on imp_doc_accepts.imp_lc_id=imp_lcs.id
			left join companies on companies.id=imp_lcs.company_id
			left join suppliers on suppliers.id=imp_lcs.supplier_id
			left join currencies on currencies.id=imp_lcs.currency_id
			left join (
			select 
			imp_liability_adjusts.imp_doc_accept_id,
			max(imp_liability_adjusts.payment_date) as payment_date,
			sum(imp_liability_adjust_chlds.amount) as amount
			from 
			imp_liability_adjusts
			join imp_liability_adjust_chlds on imp_liability_adjust_chlds.imp_liability_adjust_id=imp_liability_adjusts.id
			where imp_liability_adjusts.deleted_at is null
			and imp_liability_adjust_chlds.deleted_at is null
			group by imp_liability_adjusts.imp_doc_accept_id
			) impliabilityadjusts on impliabilityadjusts.imp_doc_accept_id=imp_doc_accepts.id
			where  imp_lcs.deleted_at is null  
			$lcdate $beneficiary $supplier $menu $lctype $lcno $issuing_bank_branch

			group by 
			imp_lcs.id,
			imp_lcs.menu_id,
			imp_lcs.lc_date,
			imp_lcs.company_id,
			companies.code,
			imp_lcs.supplier_id,
			suppliers.name,
			currencies.code,
			imp_lcs.lc_type_id,
			imp_lcs.issuing_bank_branch_id,
			imp_lcs.last_delivery_date,
			imp_lcs.expiry_date,
			imp_lcs.lc_no_i,
			imp_lcs.lc_no_ii,
			imp_lcs.lc_no_iii,
			imp_lcs.lc_no_iv,
			imp_lcs.pay_term_id,
			imp_lcs.tenor,
			imp_lcs.exch_rate,
			imp_lcs.lc_application_date,

			imp_doc_accepts.id,                    
			imp_doc_accepts.imp_lc_id,              
			imp_doc_accepts.invoice_no,           
			imp_doc_accepts.invoice_date,           
			imp_doc_accepts.shipment_date,         
			imp_doc_accepts.company_accep_date,     
			imp_doc_accepts.bank_accep_date, 
			imp_doc_accepts.discharge_date,
			imp_doc_accepts.port_clearing_date,       
			imp_doc_accepts.bank_ref,               
			imp_doc_accepts.commercial_head_id,
			imp_doc_accepts.loan_ref,             
			imp_doc_accepts.doc_value,            
			imp_doc_accepts.rate,                
			imp_doc_accepts.bl_cargo_no ,           
			imp_doc_accepts.bl_cargo_date,          
			imp_doc_accepts.shipment_mode,         
			imp_doc_accepts.doc_status,           
			imp_doc_accepts.copy_doc_rcv_date,     
			imp_doc_accepts.original_doc_rcv_date,
			imp_doc_accepts.doc_to_cf_date,
			imp_doc_accepts.feeder_vessel,
			imp_doc_accepts.mother_vessel,
			imp_doc_accepts.eta_date,
			imp_doc_accepts.ic_received_date,
			imp_doc_accepts.shipping_bill_no,
			imp_doc_accepts.incoterm_id,
			imp_doc_accepts.incoterm_place,
			imp_doc_accepts.port_of_loading,
			imp_doc_accepts.port_of_discharge,
			imp_doc_accepts.internal_file_no,
			imp_doc_accepts.bill_of_entry_no,
			imp_doc_accepts.psi_ref_no,
			imp_doc_accepts.maturity_date,
			imp_doc_accepts.container_no,
			imp_doc_accepts.qty,
			imp_doc_accepts.remarks,
			impliabilityadjusts.payment_date,
			impliabilityadjusts.amount
			order by imp_lcs.id
        "
        ));

        return $rows;
	 }
	 
	 public function getImpLcFile() {
        $id=request('id',0);

		$implcfiles=$this->implc
		->leftJoin('imp_lc_files',function($join){
			$join->on('imp_lcs.id','=','imp_lc_files.imp_lc_id');
		})
		->where([['imp_lc_files.imp_lc_id','=',$id]])
		->get([
			'imp_lcs.id',
			'imp_lcs.menu_id',
			'imp_lc_files.*',
		]);

        echo json_encode($implcfiles);

    }

    public function bankPending(){
		$date_from=request('date_from',0);
     	$date_to=request('date_to',0);
     	$beneficiary_id=request('beneficiary_id',0);
     	$supplier_id=request('supplier_id',0);
     	$menu_id=request('menu_id',0);
     	$lc_type_id=request('lc_type_id',0);
     	$lc_no=request('lc_no',0);
     	$issuing_bank_branch_id=request('issuing_bank_branch_id',0);
     	$today=date('Y-m-d');

		$item= [
			0=>"Independent",1=>"Fabric",2=>"Trim",3=>"Yarn",4=>"Knit",5=>"AOP",6=>"Dyeing",7=>"Dyes & Chemical",8=>"General Item" ,9=>"Yarn Dyeing",10=>"Embellishment",11=>"General Service"];

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

     	$lcdate='';
     	if($date_from && $date_to){
	     	$lcdate=" and imp_lcs.lc_date between '". $date_from ."' and '". $date_to ."'";
     	}

		$beneficiary='';
		if($beneficiary_id){
			$beneficiary=" and imp_lcs.company_id= $beneficiary_id ";
		}

		$supplier='';
		if($supplier_id){
			$supplier=" and imp_lcs.supplier_id= $supplier_id ";
		}

		$menu='';
		if($menu_id){
			$menu=" and imp_lcs.menu_id= $menu_id ";
		}

		$lctype='';
		if($lc_type_id){
			$lctype=" and imp_lcs.lc_type_id= $lc_type_id ";
		}

		$lcno='';
		if($lc_no){
			$lcno=" and imp_lcs.lc_no_iv'". $lc_no ."'";
		}

		$issuing_bank_branch='';
		if($issuing_bank_branch_id){
			$issuing_bank_branch=" and imp_lcs.issuing_bank_branch_id= $issuing_bank_branch_id ";
		}

		$acceptancePending = collect(\DB::select("
			select 
			imp_lcs.menu_id,        
			sum(imp_doc_accepts.doc_value) as invoice_value
			from imp_lcs  
			join imp_doc_accepts on imp_doc_accepts.imp_lc_id=imp_lcs.id
			where  imp_doc_accepts.bank_accep_date is null
			and imp_doc_accepts.deleted_at is null  $lcdate $beneficiary $supplier $menu $lctype $lcno $issuing_bank_branch
			group by
			imp_lcs.menu_id
		"));

		$otherBankPending=$acceptancePending->whereNotIn('menu_id', [2,3,7])->sum('invoice_value');
		$totalBankPending=$acceptancePending->sum('invoice_value');

		$menuitemar=[];
		foreach($acceptancePending as $rows){
			$menuitemar[$rows->menu_id]=$rows->invoice_value;
		}
		
		// $maturityPending = collect(\DB::select("
		// 	select
		// 	imp_lcs.menu_id, 
		// 	sum(imp_doc_accepts.doc_value) as invoice_value
		// 	from imp_lcs 
		// 	join imp_doc_accepts on imp_doc_accepts.imp_lc_id=imp_lcs.id
		// 	left join imp_liability_adjusts on imp_liability_adjusts.imp_doc_accept_id=imp_doc_accepts.id
		// 	where  imp_liability_adjusts.payment_date is null
		// 	and imp_doc_accepts.maturity_date is not null
		// 	and imp_doc_accepts.deleted_at is null 
		// 	$lcdate $beneficiary $supplier $menu $lctype $lcno
		// 	group by
		// 	imp_lcs.menu_id
		// "));

		// $otherMaturityPending=$maturityPending->whereNotIn('menu_id', [2,3,7])->sum('invoice_value');
		// $totalMaturityPending=$maturityPending->sum('invoice_value');

		// $menuitemarpp=[];
		// foreach($maturityPending as $rows){
		// 	$menuitemarpp[$rows->menu_id]=$rows->invoice_value;
		// }

		$maturityPending = collect(\DB::select("
			select
			imp_lcs.menu_id, 
			sum(imp_doc_accepts.doc_value) as invoice_value,
			sum(impliabilityadjusts.amount) as paid_amount
			from imp_lcs 
			join imp_doc_accepts on imp_doc_accepts.imp_lc_id=imp_lcs.id
			left join (
				select 
				imp_liability_adjusts.imp_doc_accept_id,
				sum(imp_liability_adjust_chlds.amount) as amount
				from 
				imp_liability_adjusts
				join imp_liability_adjust_chlds on imp_liability_adjust_chlds.imp_liability_adjust_id=imp_liability_adjusts.id
				where imp_liability_adjusts.deleted_at is null
				and imp_liability_adjust_chlds.deleted_at is null
				group by imp_liability_adjusts.imp_doc_accept_id
			) impliabilityadjusts on impliabilityadjusts.imp_doc_accept_id=imp_doc_accepts.id
			where imp_doc_accepts.maturity_date <'".$today."'
			and imp_doc_accepts.deleted_at is null 
			$lcdate $beneficiary $supplier $menu $lctype $lcno $issuing_bank_branch
			group by
			imp_lcs.menu_id
		"))
		->map(function($maturityPending){
			$maturityPending->pending_amount=$maturityPending->invoice_value-$maturityPending->paid_amount;
			return $maturityPending;
		});

		

		$otherMaturityPending=$maturityPending->whereNotIn('menu_id', [2,3,7])->sum('pending_amount');
		$totalMaturityPending=$maturityPending->sum('pending_amount');

		$menuitemarpp=[];
		foreach($maturityPending as $rows){
			$menuitemarpp[$rows->menu_id]=$rows->pending_amount;
		}

		$lcPending = collect(\DB::select("
			select 
			imp_lcs.menu_id,        
			case when 
			imp_lcs.menu_id=1
			then sum(po_fabrics.amount)
			when 
			imp_lcs.menu_id=2
			then sum(po_trims.amount)
			when 
			imp_lcs.menu_id=3
			then sum(po_yarns.amount)
			when 
			imp_lcs.menu_id=4
			then sum(po_knit_services.amount)
			when 
			imp_lcs.menu_id=5
			then sum(po_aop_services.amount)
			when 
			imp_lcs.menu_id=6
			then sum(po_dyeing_services.amount)
			when 
			imp_lcs.menu_id=7
			then sum(po_dye_chems.amount)
			when 
			imp_lcs.menu_id=8
			then sum(po_generals.amount)
			when 
			imp_lcs.menu_id=9
			then sum(po_yarn_dyeings.amount)
			when 
			imp_lcs.menu_id=10
			then sum(po_emb_services.amount)
			when 
			imp_lcs.menu_id=11
			then sum(po_general_services.amount)
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
			where  imp_lcs.lc_no_iv like '0000'
			and imp_lcs.deleted_at is null 
			$lcdate $beneficiary $supplier $menu $lctype $lcno $issuing_bank_branch
			group by
			imp_lcs.menu_id
			order by imp_lcs.menu_id asc
		"));

		$otherLcPending=$lcPending->whereNotIn('menu_id', [2,3,7])->sum('lc_amount');
		$totalLcPending=$lcPending->sum('lc_amount');

		$menuitemarlcp=[];
		foreach($lcPending as $rows){
			$menuitemarlcp[$rows->menu_id]=$rows->lc_amount;
		}

		$data=[
			'issuing_bank_branch_id' => isset($bankbranch[$issuing_bank_branch_id])?$bankbranch[$issuing_bank_branch_id]:'',
			'yarn'=>isset($menuitemar[3])?$menuitemar[3]:0,
			'accessories'=>isset($menuitemar[2])?$menuitemar[2]:0,
			'dyeschemical'=>isset($menuitemar[7])?$menuitemar[7]:0,
			'others'=>$otherBankPending,
			'totalpending'=>$totalBankPending,
			
			'yarn_pp'=>isset($menuitemarpp[3])?$menuitemarpp[3]:0,
			'accessories_pp'=>isset($menuitemarpp[2])?$menuitemarpp[2]:0,
			'dyeschemical_pp'=>isset($menuitemarpp[7])?$menuitemarpp[7]:0,
			'others_pp'=>$otherMaturityPending,
			'totalpending_pp'=>$totalMaturityPending,

			'yarn_lcp'=>isset($menuitemarlcp[3])?$menuitemarlcp[3]:0,
			'accessories_lcp'=>isset($menuitemarlcp[2])?$menuitemarlcp[2]:0,
			'dyeschemical_lcp'=>isset($menuitemarlcp[7])?$menuitemarlcp[7]:0,
			'others_lcp'=>$otherLcPending,
			'totalpending_lcp'=>$totalLcPending,
		];

		return Template::loadView('Report.Commercial.ImportConsignmentMatrix',['data'=>$data]);
	}
}