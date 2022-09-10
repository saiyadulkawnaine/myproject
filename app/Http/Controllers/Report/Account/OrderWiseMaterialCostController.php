<?php

namespace App\Http\Controllers\Report\Account;

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
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Repositories\Contracts\Purchase\PoAopServiceRepository;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;

class OrderWiseMaterialCostController extends Controller
{
	private $lcsc;
	private $expinvoice;
	private $company;
	private $buyer;
	private $bank;
    private $bankbranch;
	private $itemcategory;
    private $pofabric;
    private $poyarn;
    private $potrim;
	private $podyeingservice;
	private $poaopservice;
	private $poknitservice;
	private $itemaccount;
	private $budgetfabric;
	private $poembservice;
    
	public function __construct(
        ExpLcScRepository $lcsc,
        ExpInvoiceRepository $expinvoice,
		PoFabricRepository $pofabric,
		EmbelishmentTypeRepository $embelishmenttype,
		ItemcategoryRepository $itemcategory,
		PoTrimRepository $potrim,
		PoDyeingServiceRepository $podyeingservice,
		PoKnitServiceRepository $poknitservice,
		PoEmbServiceRepository $poembservice,
		PoYarnRepository $poyarn,
		ItemAccountRepository $itemaccount,
		PoAopServiceRepository $poaopservice,
		BudgetFabricRepository $budgetfabric,
        CompanyRepository $company, 
        BuyerRepository $buyer,BankRepository $bank,
		BankBranchRepository $bankbranch
        
        )
    {
		$this->lcsc = $lcsc;
		$this->expinvoice = $expinvoice;
		$this->company = $company;
		$this->buyer = $buyer;
		$this->bank = $bank;
		$this->bankbranch = $bankbranch;
		$this->embelishmenttype = $embelishmenttype;
        $this->itemcategory = $itemcategory;
        $this->pofabric = $pofabric;
        $this->poyarn = $poyarn;
        $this->potrim = $potrim;
		$this->podyeingservice = $podyeingservice;
        $this->poaopservice = $poaopservice;
        $this->poknitservice = $poknitservice;
        $this->poembservice = $poembservice;
        $this->itemaccount = $itemaccount;
        $this->budgetfabric = $budgetfabric;

		$this->middleware('auth');
		//$this->middleware('permission:view.orderwisematerialcostreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $invoicestatus=array_prepend(config('bprs.invoicestatus'), '-Select-','');
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
      return Template::loadView('Report.Account.OrderWiseMaterialCost',['company'=>$company,'buyer'=>$buyer, 'bankbranch'=>$bankbranch, 'invoicestatus'=>$invoicestatus]);
    }
   

    public function getDataYarn()
    {
     	$company_id=request('company_id',0);
     	$buyer_id=request('buyer_id',0);
     	$lc_sc_date_from=request('lc_sc_date_from',0);
     	$lc_sc_date_to=request('lc_sc_date_to',0);
     	$invoice_no=request('invoice_no',0);
     	$invoice_date_from=request('invoice_date_from',0);
     	$invoice_date_to=request('invoice_date_to',0);
     	$invoice_status_id=request('invoice_status_id',0);
     	$ex_factory_date_from=request('ex_factory_date_from',0);
     	$ex_factory_date_to=request('ex_factory_date_to',0);
     	
		$data=$this->expinvoice
		->selectRaw('
			exp_invoices.id as exp_invoice_id,
			sales_orders.id as sales_order_id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			jobs.exch_rate,
			buyers.name as buyer_name,
			3 as menu_id,
			sum(exp_invoice_orders.qty) as invoice_qty,
			sum(exp_invoice_orders.amount) as invoice_amount,
			order_qty.qty as order_qty,
			budgetyarn.yarn_req,
			inhyarnisu.inh_yarn_isu_qty,
			inhyarnisu.inh_yarn_isu_amount,
			inhyarnisurtn.qty as inh_yarn_isu_rtn_qty,
			inhyarnisurtn.amount as inh_yarn_isu_rtn_amount,
			outyarnisu.out_yarn_isu_qty,
			outyarnisu.out_yarn_isu_amount,
			outyarnisurtn.qty as out_yarn_isu_rtn_qty,
			outyarnisurtn.amount as out_yarn_isu_rtn_amount
		')
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
		->join('jobs', function($join){
			$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->join('styles', function($join){
			$join->on('jobs.style_id', '=', 'styles.id');
		})
		->join('exp_lc_scs', function($join){
			$join->on('exp_lc_scs.id', '=', 'exp_invoices.exp_lc_sc_id');
		})
		->join('buyers', function($join){
			$join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
		})
		->leftJoin(\DB::raw("(
			select
			sales_order_gmt_color_sizes.sale_order_id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			sum(sales_order_gmt_color_sizes.amount) as amount
			from
			sales_order_gmt_color_sizes
			group by
			sales_order_gmt_color_sizes.sale_order_id
		) order_qty"), "order_qty.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(
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
		) budgetyarn"), "budgetyarn.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(
			select 
			sales_orders.id as sales_order_id,
			sum(inv_yarn_isu_items.qty) as inh_yarn_isu_qty,
			avg(inv_yarn_isu_items.rate) as inh_yarn_isu_rate,
			sum(inv_yarn_isu_items.amount) as inh_yarn_isu_amount
			from sales_orders 
			join po_knit_service_item_qties on po_knit_service_item_qties.sales_order_id = sales_orders.id
			join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
			join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id
			join so_knit_po_items on so_knit_po_items.po_knit_service_item_qty_id = po_knit_service_item_qties.id
			join so_knit_refs on so_knit_refs.id = so_knit_po_items.so_knit_ref_id
			join so_knit_pos on  so_knit_pos.po_knit_service_id=po_knit_services.id
			join so_knits on so_knits.id = so_knit_pos.so_knit_id and so_knits.id = so_knit_refs.so_knit_id
			join pl_knit_items on pl_knit_items.so_knit_ref_id = so_knit_refs.id
			join pl_knits on pl_knits.id = pl_knit_items.pl_knit_id
			join rq_yarn_fabrications on rq_yarn_fabrications.pl_knit_item_id = pl_knit_items.id
			join rq_yarn_items on rq_yarn_items.rq_yarn_fabrication_id = rq_yarn_fabrications.id
			join rq_yarns on rq_yarns.id = rq_yarn_fabrications.rq_yarn_id
			join inv_yarn_isu_items on inv_yarn_isu_items.rq_yarn_item_id = rq_yarn_items.id
			join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
			join suppliers on suppliers.id = inv_isus.supplier_id
			join companies on companies.id = suppliers.company_id
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			where   inv_isus.isu_against_id=102 
			and   inv_isus.isu_basis_id=1 
			and inv_yarn_isu_items.deleted_at is null  
			group by 
			sales_orders.id
		) inhyarnisu"), "inhyarnisu.sales_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(
			select 
			inv_yarn_rcv_items.sales_order_id,
			sum(inv_yarn_transactions.store_qty) as qty,
			sum(inv_yarn_transactions.store_amount) as amount
			from 
			sales_orders
			join inv_yarn_rcv_items on inv_yarn_rcv_items.sales_order_id=sales_orders.id
			join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
			join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
			join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
			join suppliers on suppliers.id = inv_rcvs.return_from_id
			join companies on companies.id = suppliers.company_id
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			where inv_rcvs.receive_basis_id=4
			and inv_yarn_transactions.deleted_at is null
			and inv_yarn_rcv_items.deleted_at is null
			and inv_rcvs.deleted_at is null
			and inv_yarn_transactions.trans_type_id=1           
			group by 
			inv_yarn_rcv_items.sales_order_id
		) inhyarnisurtn"), "inhyarnisurtn.sales_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(
			select 
			sales_orders.id as sales_order_id,
			sum(inv_yarn_isu_items.qty) as out_yarn_isu_qty,
			avg(inv_yarn_isu_items.rate) as out_yarn_isu_rate,
			sum(inv_yarn_isu_items.amount) as out_yarn_isu_amount
			from sales_orders 
			join po_knit_service_item_qties on po_knit_service_item_qties.sales_order_id = sales_orders.id
			join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
			join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id

			join rq_yarn_fabrications on rq_yarn_fabrications.po_knit_service_item_qty_id = po_knit_service_item_qties.id
			join rq_yarn_items on rq_yarn_items.rq_yarn_fabrication_id = rq_yarn_fabrications.id
			join rq_yarns on rq_yarns.id = rq_yarn_fabrications.rq_yarn_id
			join inv_yarn_isu_items on inv_yarn_isu_items.rq_yarn_item_id = rq_yarn_items.id
			join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
			join suppliers on suppliers.id = inv_isus.supplier_id 
			and (suppliers.company_id is null or  suppliers.company_id=0)
			join companies on companies.id = inv_isus.company_id
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null             
			group by sales_orders.id
		) outyarnisu"), "outyarnisu.sales_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(
			select 
			inv_yarn_rcv_items.sales_order_id,
			sum(inv_yarn_transactions.store_qty) as qty,
			sum(inv_yarn_transactions.store_amount) as amount
			from 
			sales_orders
			join inv_yarn_rcv_items on inv_yarn_rcv_items.sales_order_id=sales_orders.id
			join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
			join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
			join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
			join suppliers on suppliers.id = inv_rcvs.return_from_id
			and (suppliers.company_id is null or  suppliers.company_id=0)
			join companies on companies.id = inv_rcvs.company_id
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			where inv_rcvs.receive_basis_id=4
			and inv_yarn_transactions.deleted_at is null
			and inv_yarn_rcv_items.deleted_at is null
			and inv_rcvs.deleted_at is null
			and inv_yarn_transactions.trans_type_id=1            
			group by 
			inv_yarn_rcv_items.sales_order_id
		) outyarnisurtn"), "outyarnisurtn.sales_order_id", "=", "sales_orders.id")
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
		->when(request('buyer_id',0), function ($q){
			return $q->where('exp_lc_scs.buyer_id', '=',request('buyer_id',0));
		})
		->when(request('company_id',0), function ($q){
			return $q->where('jobs.company_id', '=',request('company_id',0));
		})
		->where([['exp_invoices.invoice_status_id','=',2]])
		//->orderBy('exp_invoices.invoice_date')
		->groupBy([
			'exp_invoices.id',
			'sales_orders.id',
			'sales_orders.sale_order_no',
			'exp_invoices.ex_factory_date',
			'exp_invoices.invoice_no',
			'styles.style_ref',
			'jobs.exch_rate',
			'buyers.name',
			'order_qty.qty',
			'budgetyarn.yarn_req',
			'inhyarnisu.inh_yarn_isu_qty',
			'inhyarnisu.inh_yarn_isu_amount',
			'inhyarnisurtn.qty',
			'inhyarnisurtn.amount',
			'outyarnisu.out_yarn_isu_qty',
			'outyarnisu.out_yarn_isu_amount',
			'outyarnisurtn.qty',
			'outyarnisurtn.amount'
		])
		->get()
		->map(function($data){
			$data->ex_factory_date=$data->ex_factory_date?date('d-M-Y',strtotime($data->ex_factory_date)):'--';
			$data->issue_qty=($data->inh_yarn_isu_qty-$data->inh_yarn_isu_rtn_qty)+($data->out_yarn_isu_qty-$data->out_yarn_isu_rtn_qty);
			$data->net_consumption = ($data->inh_yarn_isu_amount-$data->inh_yarn_isu_rtn_amount)+($data->out_yarn_isu_amount-$data->out_yarn_isu_rtn_amount) ;

			// $data->issued_per=0;
			 $order_qty_issued=0;
			// if (!$data->issue_qty && !$data->yarn_req ) {
			// 	$data->issued_per=0;
			// 	$order_qty_issued=0;
			// }

			if ($data->yarn_req) {
				$data->issued_per=($data->issue_qty/$data->yarn_req)*100;
				$order_qty_issued=$data->order_qty*($data->issued_per/100);
			}

			$data->net_cons_per_pcs=0;
			// if ($data->order_qty_issued==0 && !$data->yarn_req) {
			// 	$data->net_cons_per_pcs = 0;
			// }

			if ($data->issued_per > 100  && $order_qty_issued) {
				$data->net_cons_per_pcs = $data->net_consumption/$data->order_qty;
			}

			else if($data->issued_per <= 100 && $order_qty_issued){
				$data->net_cons_per_pcs = $data->net_consumption/$order_qty_issued;
			}

			if ($data->invoice_qty > $data->order_qty) {
				$data->yarn_cost=$data->net_consumption;
			}

			else{
				$data->yarn_cost=$data->net_cons_per_pcs*$data->invoice_qty;
			}

			//$data->order_qty=number_format($data->order_qty,0,'.',',');
			$data->yarn_req=number_format($data->yarn_req,2,'.',',');
			$data->issue_qty=number_format($data->issue_qty,2,'.',',');
			$data->issued_per=number_format($data->issued_per,4,'.',',')." %";
			$data->net_consumption=number_format($data->net_consumption,2,'.',',');
			$data->net_cons_per_pcs=number_format($data->net_cons_per_pcs,4,'.',',');
			//$data->invoice_qty=number_format($data->invoice_qty,0,'.',',');
			$data->yarn_cost=number_format($data->yarn_cost,2,'.',',');
			$data->invoice_amount=number_format($data->invoice_amount*$data->exch_rate,2,'.',',');
			return $data;
		});

		echo json_encode($data);
    }

	public function getDataFabric() {
		$company_id=request('company_id',0);
		$buyer_id=request('buyer_id',0);
		$lc_sc_date_from=request('lc_sc_date_from',0);
		$lc_sc_date_to=request('lc_sc_date_to',0);
		$invoice_no=request('invoice_no',0);
		$invoice_date_from=request('invoice_date_from',0);
		$invoice_date_to=request('invoice_date_to',0);
		$invoice_status_id=request('invoice_status_id',0);
		$ex_factory_date_from=request('ex_factory_date_from',0);
		$ex_factory_date_to=request('ex_factory_date_to',0);

		$company=null;
		$buyer=null;
		$lcscdatefrom=null;
		$lcscdateto=null;
		$invoiceno=null;
		$invoicedatefrom=null;
		$invoicedateto=null;
		$invoicestatus=null;
		$exfactorydatefrom=null;
		$exfactorydateto=null;

		if ($company_id) {
			$company=" and jobs.company_id=$company_id";
		}
		if ($buyer_id) {
			$buyer=" and exp_lc_scs.buyer_id=$buyer_id";
		}
		if ($lc_sc_date_from) {
			$lcscdatefrom=" and exp_lc_scs.lc_sc_date >= '".$lc_sc_date_from."'";
		}
		if ($lc_sc_date_to) {
			$lcscdateto=" and exp_lc_scs.lc_sc_date <= '".$lc_sc_date_to."'";
		}
		if ($invoice_date_from) {
			$invoicedatefrom=" and exp_invoices.invoice_date >= '".$invoice_date_from."'";
		}
		if ($invoice_date_to) {
			$invoicedateto=" and exp_invoices.invoice_date <= '".$invoice_date_to."'";
		}
		if ($invoice_status_id) {
			$invoicestatus=" and exp_invoices.invoice_status_id=$invoice_status_id" ;
		}
		if ($ex_factory_date_from) {
			$exfactorydatefrom=" and exp_invoices.ex_factory_date >= '".$ex_factory_date_from."'";
		}
		if ($ex_factory_date_to) {
			$exfactorydateto=" and exp_invoices.ex_factory_date <= '".$ex_factory_date_to."'";
		}

		$rows=collect(
			\DB::select("
			select
			exp_invoices.id as exp_invoice_id,
			sales_orders.id as sales_order_id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			buyers.name as buyer_name,
			jobs.exch_rate,
			1 as menu_id,
			sum(exp_invoice_orders.qty) as invoice_qty,
			sum(exp_invoice_orders.amount) as invoice_amount,
			order_qty.qty as order_qty,
			FinfabReq.fin_fab_req,
			rcvfinfab.rcv_qty,
			rcvfinfab.rcv_amount
			from exp_invoices
			join exp_invoice_orders on exp_invoice_orders.exp_invoice_id=exp_invoices.id
			join exp_pi_orders on exp_pi_orders.id=exp_invoice_orders.exp_pi_order_id
			join sales_orders on sales_orders.id=exp_pi_orders.sales_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id=jobs.style_id
			join buyers on buyers.id=styles.buyer_id
			join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id
			left join (
				select
				sales_order_gmt_color_sizes.sale_order_id,
				sum(sales_order_gmt_color_sizes.qty) as qty,
				sum(sales_order_gmt_color_sizes.amount) as amount
				from
				sales_order_gmt_color_sizes
				group by
				sales_order_gmt_color_sizes.sale_order_id
			)order_qty on order_qty.sale_order_id=sales_orders.id
			left join (
				select
					sales_orders.id as sales_order_id,
					sum(budget_fabric_cons.grey_fab) as fin_fab_req,
					sum(budget_fabric_cons.amount) as fin_fab_req_amount
					--budget_fabric_cons.fin_fab,
					--(budget_fabric_cons.fin_fab*budget_fabric_cons.rate) as fin_fab_req_amount
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
			)FinfabReq on FinfabReq.sales_order_id=sales_orders.id
			left join (
				select
				sales_orders.id as sales_order_id,
				sum(inv_finish_fab_transactions.store_qty) as rcv_qty,
				sum(inv_finish_fab_transactions.store_amount) as rcv_amount
				from
				sales_orders
				join inv_finish_fab_rcv_fabrics on inv_finish_fab_rcv_fabrics.sales_order_id=sales_orders.id
				join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_fabric_id=inv_finish_fab_rcv_fabrics.id
				join inv_finish_fab_transactions on inv_finish_fab_transactions.inv_finish_fab_rcv_item_id=inv_finish_fab_rcv_items.id
				join inv_finish_fab_rcvs on inv_finish_fab_rcv_fabrics.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
				join inv_rcvs on inv_rcvs.id=inv_finish_fab_rcvs.inv_rcv_id
				join po_fabrics on po_fabrics.id=inv_finish_fab_rcvs.po_fabric_id
				where inv_finish_fab_transactions.trans_type_id=1
				and inv_finish_fab_transactions.deleted_at is null
				and inv_finish_fab_rcv_items.deleted_at is null
				and inv_rcvs.deleted_at is null
				and inv_rcvs.menu_id=224
				group by sales_orders.id
			)rcvfinfab on rcvfinfab.sales_order_id=sales_orders.id
			where exp_invoice_orders.deleted_at is null
			$company $buyer $lcscdatefrom $lcscdateto $invoiceno $invoicedatefrom $invoicedateto $invoicestatus $exfactorydatefrom $exfactorydateto
			and exp_invoices.invoice_status_id=2
			group by
			exp_invoices.id,
			sales_orders.id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			jobs.exch_rate,
			buyers.name,
			order_qty.qty,
			FinfabReq.fin_fab_req,
			rcvfinfab.rcv_qty,
			rcvfinfab.rcv_amount
		"))
		->map(function($rows){
			$rows->ex_factory_date=$rows->ex_factory_date?date('d-M-Y',strtotime($rows->ex_factory_date)):'--';
			$rows->net_consumption = $rows->rcv_amount ;

			$rows->rcv_per=0;
			$order_qty_rcved=0;
			if ($rows->fin_fab_req) {
				$rows->rcv_per=($rows->rcv_qty/$rows->fin_fab_req)*100;
				$order_qty_rcved=$rows->order_qty*($rows->rcv_per/100);
			}

			$rows->net_cons_per_pcs=0;
			if ($rows->rcv_per > 100) {
				$rows->net_cons_per_pcs = $rows->net_consumption/$rows->order_qty;
			}
			else if($rows->rcv_per <= 100 && $order_qty_rcved){
				$rows->net_cons_per_pcs = $rows->net_consumption/$order_qty_rcved;
			}

			if ($rows->invoice_qty > $rows->order_qty) {
				$rows->fabric_cost=$rows->net_consumption;
			}
			else{
				$rows->fabric_cost=$rows->net_cons_per_pcs*$rows->invoice_qty;
			}

			//$rows->order_qty=number_format($rows->order_qty,0,'.',',');
			$rows->fin_fab_req=number_format($rows->fin_fab_req,2,'.',',');
			$rows->rcv_qty=number_format($rows->rcv_qty,0,'.',',');
			$rows->rcv_per=number_format($rows->rcv_per,4,'.',',')." %";
			$rows->net_consumption=number_format($rows->net_consumption,2,'.',',');
			$rows->net_cons_per_pcs=number_format($rows->net_cons_per_pcs,4,'.',',');
			//$rows->invoice_qty=number_format($rows->invoice_qty,0,'.',',');
			$rows->fabric_cost=number_format($rows->fabric_cost,2,'.',',');
			$rows->invoice_amount=number_format($rows->invoice_amount*$rows->exch_rate,2,'.',',');
			return $rows;
		});

		echo json_encode($rows);

    }
    
	public function getDataKnit(){
		$company_id=request('company_id',0);
		$buyer_id=request('buyer_id',0);
		$lc_sc_date_from=request('lc_sc_date_from',0);
		$lc_sc_date_to=request('lc_sc_date_to',0);
		$invoice_no=request('invoice_no',0);
		$invoice_date_from=request('invoice_date_from',0);
		$invoice_date_to=request('invoice_date_to',0);
		$invoice_status_id=request('invoice_status_id',0);
		$ex_factory_date_from=request('ex_factory_date_from',0);
		$ex_factory_date_to=request('ex_factory_date_to',0);

		$company=null;
		$buyer=null;
		$lcscdatefrom=null;
		$lcscdateto=null;
		$invoiceno=null;
		$invoicedatefrom=null;
		$invoicedateto=null;
		$invoicestatus=null;
		$exfactorydatefrom=null;
		$exfactorydateto=null;

		if ($company_id) {
			$company=" and jobs.company_id=$company_id";
		}
		if ($buyer_id) {
			$buyer=" and exp_lc_scs.buyer_id=$buyer_id";
		}
		if ($lc_sc_date_from) {
			$lcscdatefrom=" and exp_lc_scs.lc_sc_date >= '".$lc_sc_date_from."'";
		}
		if ($lc_sc_date_to) {
			$lcscdateto=" and exp_lc_scs.lc_sc_date <= '".$lc_sc_date_to."'";
		}
		if ($invoice_date_from) {
			$invoicedatefrom=" and exp_invoices.invoice_date >= '".$invoice_date_from."'";
		}
		if ($invoice_date_to) {
			$invoicedateto=" and exp_invoices.invoice_date <= '".$invoice_date_to."'";
		}
		if ($invoice_status_id) {
			$invoicestatus=" and exp_invoices.invoice_status_id=$invoice_status_id" ;
		}
		if ($ex_factory_date_from) {
			$exfactorydatefrom=" and exp_invoices.ex_factory_date >= '".$ex_factory_date_from."'";
		}
		if ($ex_factory_date_to) {
			$exfactorydateto=" and exp_invoices.ex_factory_date <= '".$ex_factory_date_to."'";
		}

		$rows=collect(
			\DB::select("
			select
			exp_invoices.id as exp_invoice_id,
			sales_orders.id as sales_order_id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			jobs.exch_rate as sales_exch_rate,
			4 as menu_id,
			buyers.name as buyer_name,
			sum(exp_invoice_orders.qty) as invoice_qty,
			sum(exp_invoice_orders.amount) as invoice_amount,
			order_qty.qty as order_qty,
			knitReq.knit_req,
			prodknit.knit_qty,
			prodknit.rate,
			prodknit.exch_rate
			
			from exp_invoices
			join exp_invoice_orders on exp_invoice_orders.exp_invoice_id=exp_invoices.id
			join exp_pi_orders on exp_pi_orders.id=exp_invoice_orders.exp_pi_order_id
			join sales_orders on sales_orders.id=exp_pi_orders.sales_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id=jobs.style_id
			join buyers on buyers.id=styles.buyer_id
			join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id
			left join (
				select
				sales_order_gmt_color_sizes.sale_order_id,
				sum(sales_order_gmt_color_sizes.qty) as qty,
				sum(sales_order_gmt_color_sizes.amount) as amount
				from
				sales_order_gmt_color_sizes
				group by
				sales_order_gmt_color_sizes.sale_order_id
			)order_qty on order_qty.sale_order_id=sales_orders.id
			left join (
				select 
                sales_orders.id as sales_order_id,
                sum(budget_fabric_cons.grey_fab) as knit_req
                from sales_orders 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
                join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
                join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
                join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
                join jobs on jobs.id = sales_orders.job_id 
                join styles on styles.id = jobs.style_id 
                where style_fabrications.material_source_id!=1 and 1=1 
                group by sales_orders.id
			)knitReq on knitReq.sales_order_id=sales_orders.id
			left join (
				select
				m.sales_order_id,
				sum(m.roll_weight) as prod_knit_qty,
				sum(m.qc_pass_qty) as knit_qty,
				avg(m.rate) as rate,
				avg(m.exch_rate) as exch_rate
				from 
				(
					select
					prod_knit_items.pl_knit_item_id,
					prod_knit_items.po_knit_service_item_qty_id,
					prod_knit_item_rolls.roll_weight,
					prod_knit_qcs.reject_qty,   
					prod_knit_qcs.qc_pass_qty,
					case 
					when  inhprods.sales_order_id is null then outprods.sales_order_id 
					else inhprods.sales_order_id
					end as sales_order_id,
					case 
					when  inhprods.inh_rate is null then outprods.out_rate 
					else inhprods.inh_rate
					end as rate,
					case 
					when  inhprods.exch_rate is null then outprods.exch_rate 
					else inhprods.exch_rate
					end as exch_rate
					from
					prod_knits
					join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
					join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
					left join prod_knit_rcv_by_qcs on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
					left join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id
					left join (
						select 
						pl_knit_items.id as pl_knit_item_id,
						sales_orders.id as sales_order_id,
						avg(po_knit_service_item_qties.rate) as inh_rate,
						po_knit_services.exch_rate
						from 
						sales_orders
						join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
						join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
						and po_knit_service_items.deleted_at is null
						join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
						join so_knit_po_items on so_knit_po_items.po_knit_service_item_qty_id=po_knit_service_item_qties.id
						join so_knit_refs on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
						join pl_knit_items on pl_knit_items.so_knit_ref_id=so_knit_refs.id
						join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
						group by
						pl_knit_items.id,
						sales_orders.id,
						po_knit_services.exch_rate
					) inhprods on inhprods.pl_knit_item_id=prod_knit_items.pl_knit_item_id
		
					left join (
						select 
						po_knit_service_item_qties.id as po_knit_service_item_qty_id,
						sales_orders.id as sales_order_id,
						avg(po_knit_service_item_qties.rate) as out_rate,
						po_knit_services.exch_rate
						from 
						sales_orders
						join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
						join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
						join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
						group by
						po_knit_service_item_qties.id,
						sales_orders.id,
						po_knit_services.exch_rate
					) outprods on outprods.po_knit_service_item_qty_id=prod_knit_items.po_knit_service_item_qty_id
				) m 
				group by  
				m.sales_order_id
			) prodknit on prodknit.sales_order_id=sales_orders.id
			where exp_invoice_orders.deleted_at is null
			$company $buyer $lcscdatefrom $lcscdateto $invoiceno $invoicedatefrom $invoicedateto $invoicestatus $exfactorydatefrom $exfactorydateto
			and exp_invoices.invoice_status_id=2
			group by
			exp_invoices.id,
			sales_orders.id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			jobs.exch_rate,
			buyers.name,
			order_qty.qty,
			knitReq.knit_req,
			prodknit.knit_qty,
			prodknit.rate,
			prodknit.exch_rate
		"))
		->map(function($rows){
			$rows->ex_factory_date=$rows->ex_factory_date?date('d-M-Y',strtotime($rows->ex_factory_date)):'--';

			$rows->net_consumption = $rows->knit_qty*$rows->rate*$rows->exch_rate ;

			$rows->knitting_per=0;
			$order_qty_rcved=0;
			if ($rows->knit_req) {
				$rows->knitting_per=($rows->knit_qty/$rows->knit_req)*100;
				$order_qty_rcved=$rows->order_qty*($rows->knitting_per/100);
			}

			$rows->net_cons_per_pcs=0;
			if ($rows->knitting_per > 100) {
				$rows->net_cons_per_pcs = $rows->net_consumption/$rows->order_qty;
			}
			else if($rows->knitting_per <= 100 && $order_qty_rcved){
				$rows->net_cons_per_pcs = $rows->net_consumption/$order_qty_rcved;
			}

			if ($rows->invoice_qty > $rows->order_qty) {
				$rows->knitting_cost=$rows->net_consumption;
			}
			else{
				$rows->knitting_cost=$rows->net_cons_per_pcs*$rows->invoice_qty;
			}

			//$rows->order_qty=number_format($rows->order_qty,0,'.',',');
			$rows->knit_req=number_format($rows->knit_req,2,'.',',');
			$rows->knit_qty=number_format($rows->knit_qty,2,'.',',');
			$rows->knitting_per=number_format($rows->knitting_per,4,'.',',')." %";
			$rows->net_consumption=number_format($rows->net_consumption,2,'.',',');
			$rows->net_cons_per_pcs=number_format($rows->net_cons_per_pcs,4,'.',',');
			//$rows->invoice_qty=number_format($rows->invoice_qty,0,'.',',');
			$rows->knitting_cost=number_format($rows->knitting_cost,2,'.',',');
			$rows->invoice_amount=number_format($rows->invoice_amount*$rows->sales_exch_rate,2,'.',',');
			return $rows;
		});

		echo json_encode($rows);
	}
    
	public function getDataDyeing(){

		$company_id=request('company_id',0);
		$buyer_id=request('buyer_id',0);
		$lc_sc_date_from=request('lc_sc_date_from',0);
		$lc_sc_date_to=request('lc_sc_date_to',0);
		$invoice_no=request('invoice_no',0);
		$invoice_date_from=request('invoice_date_from',0);
		$invoice_date_to=request('invoice_date_to',0);
		$invoice_status_id=request('invoice_status_id',0);
		$ex_factory_date_from=request('ex_factory_date_from',0);
		$ex_factory_date_to=request('ex_factory_date_to',0);

		$company=null;
		$buyer=null;
		$lcscdatefrom=null;
		$lcscdateto=null;
		$invoiceno=null;
		$invoicedatefrom=null;
		$invoicedateto=null;
		$invoicestatus=null;
		$exfactorydatefrom=null;
		$exfactorydateto=null;

		if ($company_id) {
			$company=" and jobs.company_id=$company_id";
		}
		if ($buyer_id) {
			$buyer=" and exp_lc_scs.buyer_id=$buyer_id";
		}
		if ($lc_sc_date_from) {
			$lcscdatefrom=" and exp_lc_scs.lc_sc_date >= '".$lc_sc_date_from."'";
		}
		if ($lc_sc_date_to) {
			$lcscdateto=" and exp_lc_scs.lc_sc_date <= '".$lc_sc_date_to."'";
		}
		if ($invoice_date_from) {
			$invoicedatefrom=" and exp_invoices.invoice_date >= '".$invoice_date_from."'";
		}
		if ($invoice_date_to) {
			$invoicedateto=" and exp_invoices.invoice_date <= '".$invoice_date_to."'";
		}
		if ($invoice_status_id) {
			$invoicestatus=" and exp_invoices.invoice_status_id=$invoice_status_id" ;
		}
		if ($ex_factory_date_from) {
			$exfactorydatefrom=" and exp_invoices.ex_factory_date >= '".$ex_factory_date_from."'";
		}
		if ($ex_factory_date_to) {
			$exfactorydateto=" and exp_invoices.ex_factory_date <= '".$ex_factory_date_to."'";
		}

		$rows=collect(
			\DB::select("
			select
			exp_invoices.id as exp_invoice_id,
			sales_orders.id as sales_order_id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			jobs.exch_rate as sales_exch_rate,
			buyers.name as buyer_name,
			sum(exp_invoice_orders.qty) as invoice_qty,
			sum(exp_invoice_orders.amount) as invoice_amount,
			order_qty.qty as order_qty,
			dyeingReq.dyeing_req,
			prodDyeing.dyeing_qc_qty,
			prodDyeing.rate,
			prodDyeing.exch_rate,
			6 as menu_id
			from exp_invoices
			join exp_invoice_orders on exp_invoice_orders.exp_invoice_id=exp_invoices.id
			join exp_pi_orders on exp_pi_orders.id=exp_invoice_orders.exp_pi_order_id
			join sales_orders on sales_orders.id=exp_pi_orders.sales_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id=jobs.style_id
			join buyers on buyers.id=styles.buyer_id
			join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id
			left join (
				select
				sales_order_gmt_color_sizes.sale_order_id,
				sum(sales_order_gmt_color_sizes.qty) as qty,
				sum(sales_order_gmt_color_sizes.amount) as amount
				from
				sales_order_gmt_color_sizes
				group by
				sales_order_gmt_color_sizes.sale_order_id
			)order_qty on order_qty.sale_order_id=sales_orders.id
			left join (
				select 
				sales_orders.id as sales_order_id,
				sum(budget_fabric_cons.grey_fab) as dyeing_req
				from sales_orders 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
				join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
				join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
				join jobs on jobs.id = sales_orders.job_id 
				join styles on styles.id = jobs.style_id 
				where style_fabrications.material_source_id!=1 and 1=1 
				group by sales_orders.id
			)dyeingReq on dyeingReq.sales_order_id=sales_orders.id
			left join (
				select 
				sales_orders.id as sales_order_id,
				sum(prod_batch_finish_qc_rolls.qty) as dyeing_qc_qty,
				avg(po_dyeing_service_item_qties.rate) as rate,
				avg(po_dyeing_services.exch_rate) as exch_rate
				from 
				prod_batches
				join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
				join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_roll_id=prod_batch_rolls.id
				join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
				join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
				join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
				join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
				join so_dyeing_pos on so_dyeings.id=so_dyeing_pos.so_dyeing_id
				join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
				join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
				join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
				and po_dyeing_service_items.deleted_at is null
				join po_dyeing_services on po_dyeing_service_items.po_dyeing_service_id=po_dyeing_services.id 
				join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				--join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
				where 
				prod_batches.batch_for=1 and
				prod_batches.is_redyeing=0 and 
				prod_batches.deleted_at is null and 
				prod_batch_rolls.deleted_at is null  and
				prod_batches.unloaded_at is not null 
				group by
				sales_orders.id
			) prodDyeing on prodDyeing.sales_order_id=sales_orders.id
			where exp_invoice_orders.deleted_at is null
			$company $buyer $lcscdatefrom $lcscdateto $invoiceno $invoicedatefrom $invoicedateto $invoicestatus $exfactorydatefrom $exfactorydateto
			and exp_invoices.invoice_status_id=2
			--and sales_orders.id = 17016
			group by
			exp_invoices.id,
			sales_orders.id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			jobs.exch_rate,
			buyers.name,
			order_qty.qty,
			dyeingReq.dyeing_req,
			prodDyeing.dyeing_qc_qty,
			prodDyeing.rate,
			prodDyeing.exch_rate
		"))
		->map(function($rows){
			$rows->ex_factory_date=$rows->ex_factory_date?date('d-M-Y',strtotime($rows->ex_factory_date)):'--';

			$rows->net_consumption = $rows->dyeing_qc_qty*$rows->rate*$rows->exch_rate ;

			$rows->dyeing_per=0;
			$order_qty_rcved=0;
			if ($rows->dyeing_req) {
				$rows->dyeing_per=($rows->dyeing_qc_qty/$rows->dyeing_req)*100;
				$order_qty_rcved=$rows->order_qty*($rows->dyeing_per/100);
			}

			$rows->net_cons_per_pcs=0;
			if ($rows->dyeing_per > 100) {
				$rows->net_cons_per_pcs = $rows->net_consumption/$rows->order_qty;
			}
			else if($rows->dyeing_per <= 100 && $order_qty_rcved){
				$rows->net_cons_per_pcs = $rows->net_consumption/$order_qty_rcved;
			}

			if ($rows->invoice_qty > $rows->order_qty) {
				$rows->dyeing_cost=$rows->net_consumption;
			}
			else{
				$rows->dyeing_cost=$rows->net_cons_per_pcs*$rows->invoice_qty;
			}

			//$rows->order_qty=number_format($rows->order_qty,0,'.',',');
			$rows->dyeing_req=number_format($rows->dyeing_req,0,'.',',');
			$rows->dyeing_qc_qty=number_format($rows->dyeing_qc_qty,0,'.',',');
			$rows->dyeing_per=number_format($rows->dyeing_per,4,'.',',')." %";
			$rows->net_consumption=number_format($rows->net_consumption,2,'.',',');
			$rows->net_cons_per_pcs=number_format($rows->net_cons_per_pcs,4,'.',',');
			//$rows->invoice_qty=number_format($rows->invoice_qty,0,'.',',');
			$rows->dyeing_cost=number_format($rows->dyeing_cost,2,'.',',');
			$rows->invoice_amount=number_format($rows->invoice_amount*$rows->sales_exch_rate,2,'.',',');
			return $rows;
		});

		echo json_encode($rows);
	}
    
	public function getDataAop(){

		$company_id=request('company_id',0);
		$buyer_id=request('buyer_id',0);
		$lc_sc_date_from=request('lc_sc_date_from',0);
		$lc_sc_date_to=request('lc_sc_date_to',0);
		$invoice_no=request('invoice_no',0);
		$invoice_date_from=request('invoice_date_from',0);
		$invoice_date_to=request('invoice_date_to',0);
		$invoice_status_id=request('invoice_status_id',0);
		$ex_factory_date_from=request('ex_factory_date_from',0);
		$ex_factory_date_to=request('ex_factory_date_to',0);

		$company=null;
		$buyer=null;
		$lcscdatefrom=null;
		$lcscdateto=null;
		$invoiceno=null;
		$invoicedatefrom=null;
		$invoicedateto=null;
		$invoicestatus=null;
		$exfactorydatefrom=null;
		$exfactorydateto=null;

		if ($company_id) {
			$company=" and jobs.company_id=$company_id";
		}
		if ($buyer_id) {
			$buyer=" and exp_lc_scs.buyer_id=$buyer_id";
		}
		if ($lc_sc_date_from) {
			$lcscdatefrom=" and exp_lc_scs.lc_sc_date >= '".$lc_sc_date_from."'";
		}
		if ($lc_sc_date_to) {
			$lcscdateto=" and exp_lc_scs.lc_sc_date <= '".$lc_sc_date_to."'";
		}
		if ($invoice_date_from) {
			$invoicedatefrom=" and exp_invoices.invoice_date >= '".$invoice_date_from."'";
		}
		if ($invoice_date_to) {
			$invoicedateto=" and exp_invoices.invoice_date <= '".$invoice_date_to."'";
		}
		if ($invoice_status_id) {
			$invoicestatus=" and exp_invoices.invoice_status_id=$invoice_status_id" ;
		}
		if ($ex_factory_date_from) {
			$exfactorydatefrom=" and exp_invoices.ex_factory_date >= '".$ex_factory_date_from."'";
		}
		if ($ex_factory_date_to) {
			$exfactorydateto=" and exp_invoices.ex_factory_date <= '".$ex_factory_date_to."'";
		}

		$rows=collect(
			\DB::select("
			select
			exp_invoices.id as exp_invoice_id,
			sales_orders.id as sales_order_id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			jobs.exch_rate as sales_exch_rate,
			buyers.name as buyer_name,
			sum(exp_invoice_orders.qty) as invoice_qty,
			sum(exp_invoice_orders.amount) as invoice_amount,
			order_qty.qty as order_qty,
			aopreq.aop_req,
			prodaop.aop_qc_qty,
			prodaop.rate,
			prodaop.exch_rate,
			5 as menu_id
			
			from exp_invoices
			join exp_invoice_orders on exp_invoice_orders.exp_invoice_id=exp_invoices.id
			join exp_pi_orders on exp_pi_orders.id=exp_invoice_orders.exp_pi_order_id
			join sales_orders on sales_orders.id=exp_pi_orders.sales_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id=jobs.style_id
			join buyers on buyers.id=styles.buyer_id
			join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id
			left join (
				select
				sales_order_gmt_color_sizes.sale_order_id,
				sum(sales_order_gmt_color_sizes.qty) as qty,
				sum(sales_order_gmt_color_sizes.amount) as amount
				from
				sales_order_gmt_color_sizes
				group by
				sales_order_gmt_color_sizes.sale_order_id
			)order_qty on order_qty.sale_order_id=sales_orders.id
			left join (
				select 
				sales_orders.id as sales_order_id,
				sum(budget_fabric_prod_cons.bom_qty) as aop_req,
				sum(budget_fabric_prod_cons.amount) as aop_amount,
				sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
				from budget_fabric_prod_cons 
				join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
				join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
				join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
				where production_processes.production_area_id =25
				group by 
				sales_orders.id
			)aopreq on aopreq.sales_order_id=sales_orders.id
			left join (
				select 
				sales_orders.id as sales_order_id,
				sum(prod_aop_batch_finish_qc_rolls.qty) as aop_qc_qty,
				avg(po_aop_service_item_qties.rate) as rate,
				avg(po_aop_services.exch_rate) as exch_rate
				from
				prod_batch_finish_qcs
				join prod_batch_finish_qc_rolls prod_aop_batch_finish_qc_rolls on prod_aop_batch_finish_qc_rolls.prod_batch_finish_qc_id=prod_batch_finish_qcs.id
				join prod_aop_batch_rolls on prod_aop_batch_rolls.id=prod_aop_batch_finish_qc_rolls.prod_aop_batch_roll_id
				join prod_aop_batches on prod_aop_batches.id=prod_aop_batch_rolls.prod_aop_batch_id

				join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id=prod_aop_batch_rolls.so_aop_fabric_isu_item_id
				join so_aop_fabric_isus on so_aop_fabric_isus.id=so_aop_fabric_isu_items.so_aop_fabric_isu_id
				join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id=so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
				join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id=so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
				join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id=so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id
				join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id=prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
				join prod_batch_rolls on prod_batch_rolls.id=prod_batch_finish_qc_rolls.prod_batch_roll_id
				join prod_batches on prod_batches.id=prod_batch_rolls.prod_batch_id
				join so_aop_refs on so_aop_refs.id=so_aop_fabric_rcv_items.so_aop_ref_id
				join so_aop_po_items on so_aop_po_items.so_aop_ref_id=so_aop_refs.id
				join po_aop_service_item_qties on po_aop_service_item_qties.id=so_aop_po_items.po_aop_service_item_qty_id
				join po_aop_service_items on po_aop_service_items.id=po_aop_service_item_qties.po_aop_service_item_id 
				and po_aop_service_items.deleted_at is null
				join po_aop_services on po_aop_services.id=po_aop_service_items.po_aop_service_id
				join sales_orders on sales_orders.id=po_aop_service_item_qties.sales_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				join budget_fabric_prods on budget_fabric_prods.id=po_aop_service_items.budget_fabric_prod_id
				join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
				join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
				where style_fabrications.fabric_look_id=25 
				group by
				sales_orders.id
			) prodaop on prodaop.sales_order_id=sales_orders.id
			where exp_invoice_orders.deleted_at is null
			and exp_invoices.invoice_status_id=2
			$company $buyer $lcscdatefrom $lcscdateto $invoiceno $invoicedatefrom $invoicedateto $invoicestatus $exfactorydatefrom $exfactorydateto
			group by
			exp_invoices.id,
			sales_orders.id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			jobs.exch_rate,
			buyers.name,
			order_qty.qty,
			aopreq.aop_req,
			prodaop.aop_qc_qty,
			prodaop.rate,
			prodaop.exch_rate
		"))
		->map(function($rows){
			$rows->ex_factory_date=$rows->ex_factory_date?date('d-M-Y',strtotime($rows->ex_factory_date)):'--';

			$rows->net_consumption = $rows->aop_qc_qty*$rows->rate*$rows->exch_rate ;

			$rows->aop_per=0;
			$order_qty_rcved=0;
			if ($rows->aop_req) {
				$rows->aop_per=($rows->aop_qc_qty/$rows->aop_req)*100;
				$order_qty_rcved=$rows->order_qty*($rows->aop_per/100);
			}

			$rows->net_cons_per_pcs=0;
			if ($rows->aop_per > 100) {
				$rows->net_cons_per_pcs = $rows->net_consumption/$rows->order_qty;
			}
			else if($rows->aop_per <= 100 && $order_qty_rcved){
				$rows->net_cons_per_pcs = $rows->net_consumption/$order_qty_rcved;
			}

			if ($rows->invoice_qty > $rows->order_qty) {
				$rows->aop_cost=$rows->net_consumption;
			}
			else{
				$rows->aop_cost=$rows->net_cons_per_pcs*$rows->invoice_qty;
			}

			//$rows->order_qty=number_format($rows->order_qty,0,'.',',');
			$rows->aop_req=number_format($rows->aop_req,0,'.',',');
			$rows->aop_qc_qty=number_format($rows->aop_qc_qty,0,'.',',');
			$rows->aop_per=number_format($rows->aop_per,4,'.',',')." %";
			$rows->net_consumption=number_format($rows->net_consumption,2,'.',',');
			$rows->net_cons_per_pcs=number_format($rows->net_cons_per_pcs,4,'.',',');
			//$rows->invoice_qty=number_format($rows->invoice_qty,0,'.',',');
			$rows->aop_cost=number_format($rows->aop_cost,2,'.',',');
			$rows->invoice_amount=number_format($rows->invoice_amount*$rows->sales_exch_rate,2,'.',',');
			return $rows;
		});

		echo json_encode($rows);
	}
    
	public function getDataTrim(){

		$company_id=request('company_id',0);
		$buyer_id=request('buyer_id',0);
		$lc_sc_date_from=request('lc_sc_date_from',0);
		$lc_sc_date_to=request('lc_sc_date_to',0);
		$invoice_no=request('invoice_no',0);
		$invoice_date_from=request('invoice_date_from',0);
		$invoice_date_to=request('invoice_date_to',0);
		$invoice_status_id=request('invoice_status_id',0);
		$ex_factory_date_from=request('ex_factory_date_from',0);
		$ex_factory_date_to=request('ex_factory_date_to',0);

		$company=null;
		$buyer=null;
		$lcscdatefrom=null;
		$lcscdateto=null;
		$invoiceno=null;
		$invoicedatefrom=null;
		$invoicedateto=null;
		$invoicestatus=null;
		$exfactorydatefrom=null;
		$exfactorydateto=null;

		if ($company_id) {
			$company=" and jobs.company_id=$company_id";
		}
		if ($buyer_id) {
			$buyer=" and exp_lc_scs.buyer_id=$buyer_id";
		}
		if ($lc_sc_date_from) {
			$lcscdatefrom=" and exp_lc_scs.lc_sc_date >= '".$lc_sc_date_from."'";
		}
		if ($lc_sc_date_to) {
			$lcscdateto=" and exp_lc_scs.lc_sc_date <= '".$lc_sc_date_to."'";
		}
		if ($invoice_date_from) {
			$invoicedatefrom=" and exp_invoices.invoice_date >= '".$invoice_date_from."'";
		}
		if ($invoice_date_to) {
			$invoicedateto=" and exp_invoices.invoice_date <= '".$invoice_date_to."'";
		}
		if ($invoice_status_id) {
			$invoicestatus=" and exp_invoices.invoice_status_id=$invoice_status_id" ;
		}
		if ($ex_factory_date_from) {
			$exfactorydatefrom=" and exp_invoices.ex_factory_date >= '".$ex_factory_date_from."'";
		}
		if ($ex_factory_date_to) {
			$exfactorydateto=" and exp_invoices.ex_factory_date <= '".$ex_factory_date_to."'";
		}

		$rows=collect(
			\DB::select("
			select
			exp_invoices.id as exp_invoice_id,
			sales_orders.id as sales_order_id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			buyers.name as buyer_name,
			sum(exp_invoice_orders.qty) as invoice_qty,
			sum(exp_invoice_orders.amount) as invoice_amount,
			order_qty.qty as order_qty,
			trimsreq.req_trims_amount,
			jobs.exch_rate,
			rcvtrims.rcv_trims_amount,
			2 as menu_id
			from exp_invoices
			join exp_invoice_orders on exp_invoice_orders.exp_invoice_id=exp_invoices.id
			join exp_pi_orders on exp_pi_orders.id=exp_invoice_orders.exp_pi_order_id
			join sales_orders on sales_orders.id=exp_pi_orders.sales_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id=jobs.style_id
			join buyers on buyers.id=styles.buyer_id
			join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id
			left join (
				select
				sales_order_gmt_color_sizes.sale_order_id,
				sum(sales_order_gmt_color_sizes.qty) as qty,
				sum(sales_order_gmt_color_sizes.amount) as amount
				from
				sales_order_gmt_color_sizes
				group by
				sales_order_gmt_color_sizes.sale_order_id
			)order_qty on order_qty.sale_order_id=sales_orders.id
			left join (
				select
				sales_orders.id as sales_order_id,
				sum(budget_trim_cons.amount) as req_trims_amount
				from
				sales_orders
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
				join budget_trim_cons on budget_trim_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
				join budget_trims on budget_trims.id=budget_trim_cons.budget_trim_id
				group by sales_orders.id
			)trimsreq on trimsreq.sales_order_id=sales_orders.id
			left join (
				select
				po_trim_item_reports.sales_order_id,
				sum(inv_trim_transactions.store_amount) as rcv_trims_amount
				from
				po_trim_item_reports
				join inv_trim_rcv_items on inv_trim_rcv_items.po_trim_item_report_id=po_trim_item_reports.id
				join inv_trim_transactions on inv_trim_transactions.inv_trim_rcv_item_id=inv_trim_rcv_items.id
				join inv_trim_rcvs on inv_trim_rcvs.id=inv_trim_rcv_items.inv_trim_rcv_id
				join inv_rcvs on inv_rcvs.id=inv_trim_rcvs.inv_rcv_id
				where po_trim_item_reports.deleted_at is null
				and inv_trim_rcv_items.deleted_at is null
				and inv_trim_transactions.trans_type_id=1
				group by
				po_trim_item_reports.sales_order_id
			) rcvtrims on rcvtrims.sales_order_id=sales_orders.id
			where exp_invoice_orders.deleted_at is null
			$company $buyer $lcscdatefrom $lcscdateto $invoiceno $invoicedatefrom $invoicedateto $invoicestatus $exfactorydatefrom $exfactorydateto
			and exp_invoices.invoice_status_id=2
			group by
			exp_invoices.id,
			sales_orders.id,
			sales_orders.sale_order_no,
			exp_invoices.ex_factory_date,
			exp_invoices.invoice_no,
			styles.style_ref,
			buyers.name,
			jobs.exch_rate,
			order_qty.qty,
			trimsreq.req_trims_amount,
			rcvtrims.rcv_trims_amount
		"))
		->map(function($rows){
			$rows->ex_factory_date=$rows->ex_factory_date?date('d-M-Y',strtotime($rows->ex_factory_date)):'--';
			$rows->req_trims_amount=$rows->req_trims_amount*$rows->exch_rate;
			$rows->rcv_trims_per=0;
			$order_qty_rcved=0;
			if ($rows->req_trims_amount) {
				$rows->rcv_trims_per=($rows->rcv_trims_amount/$rows->req_trims_amount)*100;
				$order_qty_rcved=$rows->order_qty*($rows->rcv_trims_per/100);
			}

			$rows->net_cons_per_pcs=0;
			if ($rows->rcv_trims_per > 100) {
				$rows->net_cons_per_pcs = $rows->rcv_trims_amount/$rows->order_qty;
			}
			else if($rows->rcv_trims_per <= 100 && $order_qty_rcved){
				$rows->net_cons_per_pcs = $rows->rcv_trims_amount/$order_qty_rcved;
			}

			if ($rows->invoice_qty > $rows->order_qty) {
				$rows->trims_cost=$rows->rcv_trims_amount;
			}
			else{
				$rows->trims_cost=$rows->net_cons_per_pcs*$rows->invoice_qty;
			}

			//$rows->order_qty=number_format($rows->order_qty,0,'.',',');
			$rows->req_trims_amount=number_format($rows->req_trims_amount,2,'.',',');
			$rows->rcv_trims_per=number_format($rows->rcv_trims_per,4,'.',',')." %";
			$rows->rcv_trims_amount=number_format($rows->rcv_trims_amount,2,'.',',');
			$rows->net_cons_per_pcs=number_format($rows->net_cons_per_pcs,4,'.',',');
			//$rows->invoice_qty=number_format($rows->invoice_qty,0,'.',',');
			$rows->trims_cost=number_format($rows->trims_cost,2,'.',',');
			$rows->invoice_amount=number_format($rows->invoice_amount*$rows->exch_rate,2,'.',',');
			return $rows;
		});

		echo json_encode($rows);
	}

	public function getPurchaseOrderDtl(){
		$sales_order_id=request('sales_order_id', 0);
		$menu_id=request('menu_id', 0);

		//Fabric Purchase Order
		if ($menu_id==1) {
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

			$rows=$this->pofabric
			->selectRaw('
				po_fabrics.id as purchase_order_id,
				po_fabrics.po_no,
				po_fabrics.exch_rate,
				gmt_colors.name as color_name,
				fabric_colors.name as fabric_color_name,
				budget_fabrics.gsm_weight,
				budget_fabric_cons.dia,
				style_fabrications.fabric_nature_id,
				style_fabrications.fabric_look_id,
				style_fabrications.material_source_id,
				style_fabrications.is_stripe,
				style_fabrications.fabric_shape_id,
				style_fabrications.is_narrow,
				gmtsparts.name as gmtspart_name,
				item_accounts.item_description,
				sum(po_fabric_item_qties.qty) as po_qty,
				avg(po_fabric_item_qties.rate) as po_rate,
				sum(po_fabric_item_qties.amount) as po_amount
			')
			->join('po_fabric_items',function($join){
				$join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
			})
			->join('po_fabric_item_qties',function($join){
				$join->on('po_fabric_item_qties.po_fabric_item_id','=','po_fabric_items.id');
			})
			->join('budget_fabric_cons',function($join){
				$join->on('po_fabric_item_qties.budget_fabric_con_id','=','budget_fabric_cons.id'); 
			})
			->join('budget_fabrics',function($join){
				$join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');  
				$join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
			})
			->join('sales_order_gmt_color_sizes',function($join){
				$join->on('budget_fabric_cons.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
			})
			->join('sales_order_countries',function($join){
				$join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
			})
			->join('sales_orders',function($join){
				$join->on('sales_order_countries.sale_order_id','=','sales_orders.id');
			})
			->join('jobs',function($join){
				$join->on('sales_orders.job_id','=','jobs.id');
			})
			->leftJoin('style_sizes',function($join){
				$join->on('style_sizes.id','=','sales_order_gmt_color_sizes.style_size_id');
			})
			->leftJoin('sizes',function($join){
				$join->on('sizes.id','=','style_sizes.size_id');
			})
			->join('style_colors',function($join){
				$join->on('style_colors.id','=','sales_order_gmt_color_sizes.style_color_id');
			})
			->join('colors as gmt_colors',function($join){
				$join->on('gmt_colors.id','=','style_colors.color_id');
			})
			->join('colors as fabric_colors',function($join){
				$join->on('fabric_colors.id','=','budget_fabric_cons.fabric_color');
			})
			->join('style_fabrications',function($join){
				$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
			})
			->join('style_gmts',function($join){
				$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
			})
			->join('item_accounts', function($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('gmtsparts',function($join){
				$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
			})
			->where([['sales_orders.id','=',$sales_order_id]])
			->groupBy([
				'po_fabrics.id',
				'po_fabrics.po_no',
				'po_fabrics.exch_rate',
				'gmt_colors.name',
				'fabric_colors.name',
				'budget_fabrics.gsm_weight',
          		'budget_fabric_cons.dia',
				'style_fabrications.fabric_nature_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.material_source_id',
				'style_fabrications.is_stripe',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.is_narrow',
				'gmtsparts.name',
				'item_accounts.item_description',
			])
			->get()
			->map(function ($rows) use($desDropdown,$materialsourcing,$fabricnature,$fabriclooks,$fabricshape) {
				$rows->fabrication =  $desDropdown[$rows->style_fabrication_id];
				$rows->materialsourcing =  $materialsourcing[$rows->material_source_id];
				$rows->fabricnature =  $fabricnature[$rows->fabric_nature_id];
				$rows->fabriclooks = $fabriclooks[$rows->fabric_look_id];
				$rows->fabricshape = $fabricshape[$rows->fabric_shape_id];
				$rows->fabric_description = $rows->gmtspart_name.', '.$rows->fabrication.', '.$rows->materialsourcing.', '.$rows->fabricnature.', '.$rows->fabriclooks.', '.$rows->fabricshape.', '.$rows->item_description;
				$rows->po_qty=number_format($rows->po_qty,2);
				$rows->po_amount_bdt=number_format($rows->po_amount*$rows->exch_rate,2);
				$rows->po_amount=number_format($rows->po_amount,2);
				return $rows;
			});

			echo json_encode($rows);

		}
		//Accessories Purchase Order
		if ($menu_id==2) {
			$rows=$this->potrim
			->selectRaw('
				po_trims.id as purchase_order_id,
				po_trims.po_no,
				po_trims.exch_rate,
				itemcategories.name as itemcategory,
				item_accounts.item_description,
				item_accounts.specification,
				item_accounts.sub_class_name,
				itemclasses.name as itemclass_name,
				sum(po_trim_item_reports.qty) as po_qty,
				avg(po_trim_item_reports.rate) as po_rate,
				sum(po_trim_item_reports.amount) as po_amount
			')
			->join('po_trim_items',function($join){
				$join->on('po_trims.id','=','po_trim_items.po_trim_id');
			})
			->join('budget_trims',function($join){
				$join->on('po_trim_items.budget_trim_id','=','budget_trims.id')
			->whereNull('po_trim_items.deleted_at');
			})
			->leftJoin('itemclasses', function($join){
				$join->on('itemclasses.id', '=','budget_trims.itemclass_id');
			})
			->leftJoin('itemcategories', function($join){
				$join->on('itemcategories.id', '=','itemclasses.itemcategory_id');
			})
			->leftJoin('item_accounts',function($join){
				$join->on('itemclasses.id','=','item_accounts.itemclass_id');
			})
			->join('po_trim_item_reports', function($join){
				$join->on('po_trim_item_reports.po_trim_item_id', '=', 'po_trim_items.id');
			})
			->join('sales_orders',function($join){
				$join->on('po_trim_item_reports.sales_order_id','=','sales_orders.id');
			})
			->where([['po_trim_item_reports.sales_order_id','=',$sales_order_id]])
			->groupBy([
				'po_trims.id',
				'po_trims.po_no',
				'po_trims.exch_rate',
				'itemcategories.name',
				'item_accounts.item_description',
				'item_accounts.specification',
				'item_accounts.sub_class_name',
				'itemclasses.name'
			])
			->get()
			->map(function ($rows) {
				$rows->item_description = $rows->itemclass_name.', '.$rows->itemcategory.', '.$rows->item_description.', '.$rows->specification.', '.$rows->sub_class_name;
				$rows->po_qty=number_format($rows->po_qty,2);
				$rows->po_amount_bdt=number_format($rows->po_amount*$rows->exch_rate,2);
				$rows->po_amount=number_format($rows->po_amount,2);
				return $rows;
			});

			echo json_encode($rows);
		}
		//Yarn Purchase Order
		if ($menu_id==3) {
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
			//->where([['itemcategories.identity','=',1]])
			->get([
				'item_accounts.id',
				'yarncounts.count',
				'yarncounts.symbol',
				'yarntypes.name as yarn_type',
				'itemclasses.name as itemclass_name',
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

			$rows=$this->poyarn
			->selectRaw('
				po_yarns.id as purchase_order_id,
				po_yarns.po_no,
				po_yarns.exch_rate,
				uoms.code as uom_code,
				po_yarn_items.item_account_id,
				sum(po_yarn_items.qty) as po_qty,
				avg(po_yarn_items.rate) as po_rate,
				sum(po_yarn_items.amount) as po_amount
			')
			->join('currencies',function($join){
				$join->on('currencies.id','=','po_yarns.currency_id');
			})
			->join('po_yarn_items', function($join){
				$join->on('po_yarn_items.po_yarn_id', '=', 'po_yarns.id');
				$join->whereNull('po_yarn_items.deleted_at');
			})
			->join('item_accounts', function($join){
				$join->on('item_accounts.id', '=', 'po_yarn_items.item_account_id');
			})
			->leftJoin('uoms', function($join){
				$join->on('uoms.id', '=', 'item_accounts.uom_id');
			})
			->join('po_yarn_item_bom_qties',function($join){
				$join->on('po_yarn_item_bom_qties.po_yarn_item_id','=','po_yarn_items.id');
				//$join->on('po_yarn_item_bom_qties.budget_yarn_id','=','budget_yarns.id');
				//$join->on('po_yarn_item_bom_qties.sale_order_id','=','sales_orders.id');
			})
			->join('sales_orders',function($join){
				$join->on('po_yarn_item_bom_qties.sale_order_id','=','sales_orders.id');
			})
			->where([['po_yarn_item_bom_qties.sale_order_id','=',$sales_order_id]])
			->groupBy([
				'po_yarns.id',
				'po_yarns.po_no',
				'po_yarns.exch_rate',
				'uoms.code',
				'po_yarn_items.item_account_id'
			])
			->get()
			->map(function ($rows) use($yarnDropdown) {
				$rows->item_description = $yarnDropdown[$rows->item_account_id];
				$rows->po_qty=number_format($rows->po_qty,2);
				$rows->po_amount_bdt=number_format($rows->po_amount*$rows->exch_rate,2);
				$rows->po_amount=number_format($rows->po_amount,2);
				return $rows;
			});

			echo json_encode($rows);

		}
		//Knitting Work Order
		if ($menu_id==4) {
			$fabricDescription=$this->budgetfabric
			->join('budget_fabric_prods',function($join){
				$join->on('budget_fabric_prods.budget_fabric_id','=','budget_fabrics.id');
			})
			->join('production_processes',function($join){
				$join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
			})
			->join('style_fabrications',function($join){
				$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
			})
			->join('gmtsparts',function($join){
				$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
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
			->where([['production_processes.production_area_id','=',10]])
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

			$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        	$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

			$rows=$this->poknitservice
			->selectRaw('
				po_knit_services.id as purchase_order_id,
				po_knit_services.po_no,
				po_knit_services.exch_rate,
				currencies.code as currency_code,
				budget_fabrics.style_fabrication_id,
				budget_fabrics.gsm_weight,
				po_knit_service_item_qties.dia,
				gmtsparts.name as gmtspart_name,
				colorranges.name as colorrange_name,
				style_fabrications.fabric_look_id,
				style_fabrications.fabric_shape_id,
				po_knit_service_item_qties.fabric_color_id,
				colors.name as fabric_color,
				sum(po_knit_service_item_qties.qty) as po_qty,
				avg(po_knit_service_item_qties.rate) as po_rate,
				sum(po_knit_service_item_qties.amount) as po_amount
			')
			->join('po_knit_service_items',function($join){
				$join->on('po_knit_service_items.po_knit_service_id','=','po_knit_services.id');
			})
			->join('budget_fabric_prods',function($join){
				$join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
			})
			->join('budget_fabrics',function($join){
				$join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
			})
			->join('style_fabrications',function($join){
				$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
			})
			->join('gmtsparts',function($join){
				$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
			})
			->leftJoin('po_knit_service_item_qties',function($join){
				$join->on('po_knit_service_item_qties.po_knit_service_item_id','=','po_knit_service_items.id');
				$join->whereNull('po_knit_service_item_qties.deleted_at');
			})
			->join('sales_orders',function($join){
				$join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
			})
			->leftJoin('colorranges',function($join){
				$join->on('colorranges.id','=','po_knit_service_item_qties.colorrange_id');
			})
			->join('currencies',function($join){
				$join->on('currencies.id','=','po_knit_services.currency_id');
			})
			->leftJoin('colors',function($join){
				$join->on('colors.id','=','po_knit_service_item_qties.fabric_color_id');
			})
			->where([['po_knit_service_item_qties.sales_order_id','=', $sales_order_id]])
			->groupBy([
				'po_knit_services.id',
				'po_knit_services.po_no',
				'po_knit_services.exch_rate',
				'currencies.code',
				'budget_fabrics.style_fabrication_id',
				'budget_fabrics.gsm_weight',
				'po_knit_service_item_qties.dia',
				'gmtsparts.name',
				'colorranges.name',
				'po_knit_service_item_qties.fabric_color_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'colors.name',
			])
			->get()
			->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape) {
				$rows->fabrication=isset($desDropdown[$rows->style_fabrication_id])?$desDropdown[$rows->style_fabrication_id]:'';
				$rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
				$rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';
				$rows->fabric_description=$rows->gmtspart_name.', '.$rows->fabric_color.', '.$rows->colorrange_name.', '.$rows->fabrication.', '.$rows->dia.', '.$rows->gsm_weight.','.$rows->fabriclooks.','.$rows->fabricshape;
				$rows->po_qty=number_format($rows->po_qty,2);
				$rows->po_amount_bdt=number_format($rows->po_amount*$rows->exch_rate,2);
				$rows->po_amount=number_format($rows->po_amount,2);
				return $rows;
			});

			
			echo json_encode($rows);
		}
		//AOP Work Order
		if ($menu_id==5) {
			$fabricDescription=$this->budgetfabric
			->join('budget_fabric_prods',function($join){
				$join->on('budget_fabric_prods.budget_fabric_id','=','budget_fabrics.id');
			})
			->join('production_processes',function($join){
				$join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
			})
			->join('style_fabrications',function($join){
				$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
			})
			->join('gmtsparts',function($join){
				$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
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
			->where([['production_processes.production_area_id','=',25]])
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

			$dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
			$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        	$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

			$rows=$this->poaopservice
			->selectRaw('
				po_aop_services.id as purchase_order_id,
				po_aop_services.po_no,
				po_aop_services.exch_rate,
				currencies.code as currency_code,
				budget_fabrics.style_fabrication_id,
				budget_fabrics.gsm_weight,
				po_aop_service_item_qties.dia,
				gmtsparts.name as gmtspart_name,
				colorranges.name as colorrange_name,
				style_fabrications.fabric_look_id,
				style_fabrications.fabric_shape_id,
				style_fabrications.dyeing_type_id,
				po_aop_service_item_qties.fabric_color_id,
				colors.name as fabric_color,
				sum(po_aop_service_item_qties.qty) as po_qty,
				avg(po_aop_service_item_qties.rate) as po_rate,
				sum(po_aop_service_item_qties.amount) as po_amount
			')
			->join('po_aop_service_items',function($join){
				$join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id')
				->whereNull('po_aop_service_items.deleted_at');
			})
			->join('budget_fabric_prods',function($join){
				$join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
			})
			->join('budget_fabrics',function($join){
				$join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
			})
			->join('style_fabrications',function($join){
				$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
			})
			->join('gmtsparts',function($join){
				$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
			})
			->join('po_aop_service_item_qties',function($join){
				$join->on('po_aop_service_item_qties.po_aop_service_item_id','=','po_aop_service_items.id');
				$join->whereNull('po_aop_service_item_qties.deleted_at');
			})
			->join('sales_orders',function($join){
				$join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
			})
			->leftJoin('colorranges',function($join){
				$join->on('colorranges.id','=','po_aop_service_item_qties.colorrange_id');
			})
			->join('currencies',function($join){
				$join->on('currencies.id','=','po_aop_services.currency_id');
			})
			->leftJoin('colors',function($join){
				$join->on('colors.id','=','po_aop_service_item_qties.fabric_color_id');
			})
			->where([['po_aop_service_item_qties.sales_order_id','=', $sales_order_id]])
			->groupBy([
				'po_aop_services.id',
				'po_aop_services.po_no',
				'po_aop_services.exch_rate',
				'currencies.code',
				'budget_fabrics.style_fabrication_id',
				'budget_fabrics.gsm_weight',
				'po_aop_service_item_qties.dia',
				'gmtsparts.name',
				'colorranges.name',
				'po_aop_service_item_qties.fabric_color_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.dyeing_type_id',
				'colors.name',
			])
			->get()
			->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$dyetype) {
				$rows->fabrication=isset($desDropdown[$rows->style_fabrication_id])?$desDropdown[$rows->style_fabrication_id]:'';
				$rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
				$rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';
				$rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:'';
				$rows->fabric_description=$rows->gmtspart_name.', '.$rows->fabric_color.', '.$rows->colorrange_name.', '.$rows->fabrication.', '.$rows->dia.', '.$rows->gsm_weight.', '.$rows->dyeingtype.', '.$rows->fabriclooks.', '.$rows->fabricshape;
				$rows->po_qty=number_format($rows->po_qty,2);
				$rows->po_amount_bdt=number_format($rows->po_amount*$rows->exch_rate,2);
				$rows->po_amount=number_format($rows->po_amount,2);
				return $rows;
			});

			echo json_encode($rows);
		}
		//Dyeing Work Order
		if ($menu_id==6) {
			$fabricDescription=$this->budgetfabric
			->join('budget_fabric_prods',function($join){
				$join->on('budget_fabric_prods.budget_fabric_id','=','budget_fabrics.id');
			})
			->join('production_processes',function($join){
				$join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
			})
			->join('style_fabrications',function($join){
				$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
			})
			->join('gmtsparts',function($join){
				$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
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
			->where([['production_processes.production_area_id','=',20]])
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

			$dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
			$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        	$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

			$rows=$this->podyeingservice
			->selectRaw('
				po_dyeing_services.id as purchase_order_id,
				po_dyeing_services.po_no,
				po_dyeing_services.exch_rate,
				currencies.code as currency_code,
				budget_fabrics.style_fabrication_id,
				budget_fabrics.gsm_weight,
				po_dyeing_service_item_qties.dia,
				gmtsparts.name as gmtspart_name,
				colorranges.name as colorrange_name,
				style_fabrications.fabric_look_id,
				style_fabrications.fabric_shape_id,
				style_fabrications.dyeing_type_id,
				po_dyeing_service_item_qties.fabric_color_id,
				colors.name as fabric_color,
				sum(po_dyeing_service_item_qties.qty) as po_qty,
				sum(po_dyeing_service_item_qties.pcs_qty) as pcs_qty,
				avg(po_dyeing_service_item_qties.rate) as po_rate,
				sum(po_dyeing_service_item_qties.amount) as po_amount
			')
			->join('po_dyeing_service_items',function($join){
				$join->on('po_dyeing_service_items.po_dyeing_service_id','=','po_dyeing_services.id')
				->whereNull('po_dyeing_service_items.deleted_at');
			})
			->join('budget_fabric_prods',function($join){
				$join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
			})
			->join('budget_fabrics',function($join){
				$join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
			})
			->join('style_fabrications',function($join){
				$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
			})
			->join('gmtsparts',function($join){
				$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
			})
			->join('po_dyeing_service_item_qties',function($join){
				$join->on('po_dyeing_service_item_qties.po_dyeing_service_item_id','=','po_dyeing_service_items.id');
				$join->whereNull('po_dyeing_service_item_qties.deleted_at');
			})
			->join('sales_orders',function($join){
				$join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
			})
			->leftJoin('colorranges',function($join){
				$join->on('colorranges.id','=','po_dyeing_service_item_qties.colorrange_id');
			})
			->join('currencies',function($join){
				$join->on('currencies.id','=','po_dyeing_services.currency_id');
			})
			->leftJoin('colors',function($join){
				$join->on('colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
			})
			->where([['po_dyeing_service_item_qties.sales_order_id','=', $sales_order_id]])
			->groupBy([
				'po_dyeing_services.id',
				'po_dyeing_services.po_no',
				'po_dyeing_services.exch_rate',
				'currencies.code',
				'budget_fabrics.style_fabrication_id',
				'budget_fabrics.gsm_weight',
				'po_dyeing_service_item_qties.dia',
				'gmtsparts.name',
				'colorranges.name',
				'po_dyeing_service_item_qties.fabric_color_id',
				'style_fabrications.fabric_look_id',
				'style_fabrications.fabric_shape_id',
				'style_fabrications.dyeing_type_id',
				'colors.name',
			])
			->get()
			->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape,$dyetype) {
				$rows->fabrication=isset($desDropdown[$rows->style_fabrication_id])?$desDropdown[$rows->style_fabrication_id]:'';
				$rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
				$rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';
				$rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:'';
				$rows->fabric_description=$rows->gmtspart_name.', '.$rows->fabric_color.', '.$rows->colorrange_name.', '.$rows->fabrication.', '.$rows->dia.', '.$rows->gsm_weight.', '.$rows->dyeingtype.', '.$rows->fabriclooks.', '.$rows->fabricshape;
				$rows->po_qty=number_format($rows->po_qty,2);
				$rows->po_amount_bdt=number_format($rows->po_amount*$rows->exch_rate,2);
				$rows->po_amount=number_format($rows->po_amount,2);
				return $rows;
			});

			echo json_encode($rows);
		}
		

	}

}
