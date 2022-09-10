<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;

use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Account\AccBepRepository;

class BudgetSummaryController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $itemaccount;
	private $budget;
	private $embelishmenttype;
	private $salesordergmtcolorsize;
	private $accbep;

	public function __construct(
		StyleRepository $style,
		CompanyRepository $company,
		BuyerRepository $buyer,
		ItemAccountRepository $itemaccount,
		BudgetRepository $budget,
		EmbelishmentTypeRepository $embelishmenttype,
		SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
		AccBepRepository $accbep
	)
    {
		$this->style                     = $style;
		$this->company                   = $company;
		$this->buyer                     = $buyer;
		$this->itemaccount               = $itemaccount;
		$this->budget                    = $budget;
		$this->embelishmenttype          = $embelishmenttype;
		$this->salesordergmtcolorsize    = $salesordergmtcolorsize;
		$this->accbep                    = $accbep;
		$this->middleware('auth');
		//$this->middleware('permission:view.orderwisebudgetreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-',0);
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
        return Template::loadView('Report.BudgetSummary',['company'=>$company,'buyer'=>$buyer,'status'=>$status]);
    }
    
    

    public function reportData() {
    	$date_from=request('date_from',0);
    	$date_to=request('date_to',0);
    	$rows = collect(
        \DB::select("
				select 
				m.rcv_month,
				m.rcv_month_no,
				m.rcv_year,

				sum(m.qty) as qty,
				avg(m.rate) as rate,
				(sum(m.amount)/sum(m.qty)) as w_rate,
				sum(m.amount) as amount,
				sum(m.plan_cut_qty) as plan_cut_qty,

				sum(m.commi_amount) as commi_amount,
				sum(m.yarn_qty) as yarn_qty,
				sum(m.yarn_amount) as yarn_amount,
				sum(m.grey_fab_pur_req) as grey_fab_pur_req,
				sum(m.fab_pur_amount) as fab_pur_amount,

				sum(m.trim_amount) as trim_amount,
				sum(m.yarn_dying_qty) as yarn_dying_qty,
				sum(m.yarn_dying_amount) as yarn_dying_amount,
				sum(m.kniting_qty) as kniting_qty,
				sum(m.kniting_amount) as kniting_amount,
				sum(m.dying_qty) as dying_qty,
				sum(m.dying_amount) as dying_amount,
				sum(m.dyeing_overhead_amount) as dyeing_overhead_amount,
				sum(m.aop_qty) as aop_qty,
				sum(m.aop_amount) as aop_amount,
				sum(m.aop_overhead_amount) as aop_overhead_amount,
				sum(m.burnout_qty) as burnout_qty,
				sum(m.burnout_amount) as burnout_amount,
				sum(m.finishing_qty) as finishing_qty,
				sum(m.finishing_amount) as finishing_amount,
				sum(m.washing_qty) as washing_qty,
				sum(m.washing_amount) as washing_amount,
				sum(m.printing_qty) as printing_qty,
				sum(m.printing_amount) as printing_amount,
				sum(m.printing_overhead_amount) as printing_overhead_amount,
				sum(m.emb_qty) as emb_qty,
				sum(m.emb_amount) as emb_amount,
				sum(m.spemb_qty) as spemb_qty,
				sum(m.spemb_amount) as spemb_amount,
				sum(m.gmtdyeing_qty) as gmtdyeing_qty,
				sum(m.gmtdyeing_amount) as gmtdyeing_amount,
				sum(m.gmtwashing_qty) as gmtwashing_qty,
				sum(m.gmtwashing_amount) as gmtwashing_amount,

				sum(m.courier_amount) as courier_amount,
				sum(m.lab_amount) as lab_amount,
				sum(m.insp_amount) as insp_amount,
				sum(m.frei_amount) as frei_amount,
				sum(m.opa_amount) as opa_amount,
				sum(m.dep_amount) as dep_amount,
				sum(m.coc_amount) as coc_amount,
				sum(m.ict_amount) as ict_amount,
				sum(m.cm_amount) as cm_amount,
				sum(m.commer_amount) as commer_amount,
				sum(m.lc_fabric_qty) as lc_fabric_qty,
				sum(m.lc_fabric_amount) as lc_fabric_amount,
				sum(m.lc_yarn_qty) as lc_yarn_qty,
				sum(m.lc_yarn_amount) as lc_yarn_amount,
				sum(m.lc_trim_qty) as lc_trim_qty,
				sum(m.lc_trim_amount) as lc_trim_amount,
				sum(m.fin_fab) as fin_fab,
				sum(m.grey_fab) as grey_fab

				from 

				(select
				sales_orders.id, 
				sales_orders.sale_order_no,
				sales_orders.ship_date,
				to_char(sales_orders.ship_date, 'Mon') as rcv_month,
				to_char(sales_orders.ship_date, 'MM') as rcv_month_no,
				to_char(sales_orders.ship_date, 'yy') as rcv_year,
				saleorders.qty,
				saleorders.rate,
				saleorders.amount,
				saleorders.plan_cut_qty,

				commissions.commi_rate,
				saleorders.amount*(commissions.commi_rate/100) as commi_amount,
				yarns.yarn_qty,
				yarns.yarn_amount,
				fabpurs.grey_fab_pur_req,
				fabpurs.fab_pur_amount,

				trims.trim_amount,
				yarndyeings.yarn_dying_qty,
				yarndyeings.yarn_dying_amount,
				knitings.kniting_qty,
				knitings.kniting_amount,
				dyeings.dying_qty,
				dyeings.dying_amount,
				dyeings.dyeing_overhead_amount,
				aops.aop_qty,
				aops.aop_amount,
				aops.aop_overhead_amount,
				burnouts.burnout_qty,
				burnouts.burnout_amount,
				finishings.finishing_qty,
				finishings.finishing_amount,
				washings.washing_qty,
				washings.washing_amount,
				scprintings.printing_qty,
				scprintings.printing_amount,
				scprintings.printing_overhead_amount,
				embs.emb_qty,
				embs.emb_amount,
				spembs.spemb_qty,
				spembs.spemb_amount,
				gmtdyeings.gmtdyeing_qty,
				gmtdyeings.gmtdyeing_amount,
				gmtwashings.gmtwashing_qty,
				gmtwashings.gmtwashing_amount,

				couriers.courier_rate,
				saleorders.qty*(couriers.courier_rate/12) as courier_amount,
				labs.lab_rate,
				saleorders.qty*(labs.lab_rate/12) as lab_amount,
				insps.insp_rate,
				saleorders.qty*(insps.insp_rate/12) as insp_amount,
				freis.frei_rate,
				saleorders.qty*(freis.frei_rate/12) as frei_amount,
				opas.opa_rate,
				saleorders.qty*(opas.opa_rate/12) as opa_amount,
				deps.dep_rate,
				saleorders.qty*(deps.dep_rate/12) as dep_amount,
				cocs.coc_rate,
				saleorders.qty*(cocs.coc_rate/12) as coc_amount,
				icts.ict_rate,
				saleorders.qty*(icts.ict_rate/12) as ict_amount,
				gmtsitems.item_ratio,
				cms.cm_rate,
				saleorders.qty * cms.cm_rate as cm_amount,
				commers.commer_rate,
				(nvl(fabpurs.fab_pur_amount,0)+nvl(yarns.yarn_amount,0)+nvl(trims.trim_amount,0)+nvl(yarndyeings.yarn_dying_amount,0)+
				nvl(knitings.kniting_amount,0)+nvl(dyeings.dying_amount,0)+nvl(dyeings.dyeing_overhead_amount,0)+
				nvl(aops.aop_amount,0)+nvl(aops.aop_overhead_amount,0)+nvl(burnouts.burnout_amount,0)+
				nvl(finishings.finishing_amount,0)+nvl(washings.washing_amount,0)+nvl(scprintings.printing_amount,0)+
				nvl(scprintings.printing_overhead_amount,0)+nvl(embs.emb_amount,0)+nvl(spembs.spemb_amount,0)+
				nvl(gmtdyeings.gmtdyeing_amount,0)+nvl(gmtwashings.gmtwashing_amount,0))*(commers.commer_rate/100) as commer_amount,

				lcfabrics.lc_fabric_qty,
				lcfabrics.lc_fabric_amount,
				lcyarns.lc_yarn_qty,
				lcyarns.lc_yarn_amount,
				lctrims.lc_trim_qty,
				lctrims.lc_trim_amount,
				fabrics.fin_fab,
				fabrics.grey_fab
				from
				sales_orders
				join jobs on jobs.id=sales_orders.job_id
				join sales_order_countries on sales_orders.id=sales_order_countries.sale_order_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
				left join budgets on budgets.job_id=jobs.id

				left join(
				select sales_orders.id as sale_order_id, 
				sum(sales_order_gmt_color_sizes.qty) as qty,
				avg(sales_order_gmt_color_sizes.rate) as rate,
				sum(sales_order_gmt_color_sizes.amount) as amount, 
				sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
				from sales_orders 
				join sales_order_countries on sales_orders.id=sales_order_countries.sale_order_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id   
				group by sales_orders.id
				) saleorders on saleorders.sale_order_id=sales_orders.id

				left join(
				select budgets.job_id, 
				sum(budget_commissions.rate)  as commi_rate 
				from budget_commissions 
				left join budgets on budgets.id = budget_commissions.budget_id   
				group by budgets.job_id
				) commissions on commissions.job_id=jobs.id

				left join(
				select m.id as sale_order_id,sum(m.yarn) as yarn_qty,sum(m.yarn_amount) as yarn_amount  from (
				select 
				budget_yarns.id as budget_yarn_id ,
				budget_yarns.ratio,
				budget_yarns.cons,
				budget_yarns.rate,
				budget_yarns.amount,
				sum(budget_fabric_cons.grey_fab) as grey_fab,
				sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,
				(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount,
				sales_orders.id as id  
				from budget_yarns 
				join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id 
				join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id 
				group by budget_yarns.id,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,sales_orders.id,sales_orders.sale_order_no
				) m group by m.id
				) yarns on yarns.sale_order_id=sales_orders.id

				left join (
				SELECT 
				sales_orders.id as sale_order_id,
				sum(budget_fabric_cons.amount) as fab_pur_amount,
				sum(budget_fabric_cons.fin_fab) as fin_fab_pur_req,
				sum(budget_fabric_cons.grey_fab) as grey_fab_pur_req
				FROM sales_orders 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
				join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
				join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id

				join jobs on jobs.id = sales_orders.job_id 
				join styles on styles.id = jobs.style_id 
				where  style_fabrications.material_source_id=1 
				group by sales_orders.id
				) fabpurs on fabpurs.sale_order_id=sales_orders.id
				left join(
				SELECT 
				sales_order_gmt_color_sizes.sale_order_id,
				sum(budget_trim_cons.amount) as trim_amount 
				FROM budget_trim_cons  
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id   
				group by sales_order_gmt_color_sizes.sale_order_id
				) trims on trims.sale_order_id=sales_orders.id
				left join (
				select 
				sales_orders.id as sale_order_id,
				production_processes.production_area_id,
				sum(budget_yarn_dyeing_cons.bom_qty) as yarn_dying_qty,
				sum(budget_yarn_dyeing_cons.amount) as yarn_dying_amount
				from budget_yarn_dyeing_cons 
				left join sales_orders on sales_orders.id = budget_yarn_dyeing_cons.sales_order_id 
				left join budget_yarn_dyeings on budget_yarn_dyeings.id=budget_yarn_dyeing_cons.budget_yarn_dyeing_id 
				left join production_processes on production_processes.id=budget_yarn_dyeings.production_process_id 
				where production_processes.production_area_id =5
				group by sales_orders.id,production_processes.production_area_id
				)  yarndyeings on yarndyeings.sale_order_id=sales_orders.id
				left join (
				select 
				sales_orders.id as sale_order_id,
				production_processes.production_area_id,
				sum(budget_fabric_prod_cons.bom_qty) as kniting_qty,
				sum(budget_fabric_prod_cons.amount) as kniting_amount
				from budget_fabric_prod_cons 
				left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
				left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
				left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
				where production_processes.production_area_id =10
				group by sales_orders.id,production_processes.production_area_id
				) knitings on knitings.sale_order_id=sales_orders.id

				left join (
				select 
				sales_orders.id as sale_order_id,
				production_processes.production_area_id,
				sum(budget_fabric_prod_cons.bom_qty) as dying_qty,
				sum(budget_fabric_prod_cons.amount) as dying_amount,
				sum(budget_fabric_prod_cons.overhead_amount) as dyeing_overhead_amount
				from budget_fabric_prod_cons 
				left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
				left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
				left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
				where production_processes.production_area_id =20
				group by sales_orders.id,production_processes.production_area_id
				) dyeings on dyeings.sale_order_id=sales_orders.id

				left join (
				select 
				sales_orders.id as sale_order_id,
				production_processes.production_area_id,
				sum(budget_fabric_prod_cons.bom_qty) as aop_qty,
				sum(budget_fabric_prod_cons.amount) as aop_amount,
				sum(budget_fabric_prod_cons.overhead_amount) as aop_overhead_amount
				from budget_fabric_prod_cons 
				left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
				left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
				left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
				where production_processes.production_area_id =25
				group by sales_orders.id,production_processes.production_area_id
				) aops on aops.sale_order_id=sales_orders.id

				left join (
				select 
				sales_orders.id as sale_order_id,
				production_processes.production_area_id,
				sum(budget_fabric_prod_cons.bom_qty) as burnout_qty,
				sum(budget_fabric_prod_cons.amount) as burnout_amount
				from budget_fabric_prod_cons 
				left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
				left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
				left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
				where production_processes.production_area_id =28
				group by sales_orders.id,production_processes.production_area_id
				) burnouts on burnouts.sale_order_id=sales_orders.id
				left join (
				select 
				sales_orders.id as sale_order_id,
				production_processes.production_area_id,
				sum(budget_fabric_prod_cons.bom_qty) as finishing_qty,
				sum(budget_fabric_prod_cons.amount) as finishing_amount
				from budget_fabric_prod_cons 
				left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
				left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
				left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
				where production_processes.production_area_id =30
				group by sales_orders.id,production_processes.production_area_id
				) finishings on finishings.sale_order_id=sales_orders.id

				left join (
				select 
				sales_orders.id as sale_order_id,
				production_processes.production_area_id,
				sum(budget_fabric_prod_cons.bom_qty) as washing_qty,
				sum(budget_fabric_prod_cons.amount) as washing_amount
				from budget_fabric_prod_cons 
				left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
				left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
				left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
				where production_processes.production_area_id =35
				group by sales_orders.id,production_processes.production_area_id
				) washings on washings.sale_order_id=sales_orders.id
				left join (
				select sales_order_gmt_color_sizes.sale_order_id,
				sum(budget_emb_cons.req_cons) as printing_qty ,
				sum(budget_emb_cons.amount) as printing_amount ,
				sum(budget_emb_cons.overhead_amount) as printing_overhead_amount 
				from budget_emb_cons 
				left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
				left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where production_processes.production_area_id =45
				group by sales_order_gmt_color_sizes.sale_order_id
				) scprintings on scprintings.sale_order_id=sales_orders.id

				left join (
				select sales_order_gmt_color_sizes.sale_order_id,
				sum(budget_emb_cons.req_cons) as emb_qty ,
				sum(budget_emb_cons.amount) as emb_amount 

				from budget_emb_cons 
				left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
				left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where production_processes.production_area_id =50
				group by sales_order_gmt_color_sizes.sale_order_id
				) embs on embs.sale_order_id=sales_orders.id

				left join (
				select sales_order_gmt_color_sizes.sale_order_id,
				sum(budget_emb_cons.req_cons) as spemb_qty ,
				sum(budget_emb_cons.amount) as spemb_amount 

				from budget_emb_cons 
				left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
				left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where production_processes.production_area_id =51
				group by sales_order_gmt_color_sizes.sale_order_id
				) spembs on spembs.sale_order_id=sales_orders.id

				left join (
				select sales_order_gmt_color_sizes.sale_order_id,
				sum(budget_emb_cons.req_cons) as gmtdyeing_qty ,
				sum(budget_emb_cons.amount) as gmtdyeing_amount 

				from budget_emb_cons 
				left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
				left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where production_processes.production_area_id =58
				group by sales_order_gmt_color_sizes.sale_order_id
				) gmtdyeings on gmtdyeings.sale_order_id=sales_orders.id

				left join (
				select sales_order_gmt_color_sizes.sale_order_id,
				sum(budget_emb_cons.req_cons) as gmtwashing_qty ,
				sum(budget_emb_cons.amount) as gmtwashing_amount 

				from budget_emb_cons 
				left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
				left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where production_processes.production_area_id =60
				group by sales_order_gmt_color_sizes.sale_order_id
				) gmtwashings on gmtwashings.sale_order_id=sales_orders.id

				left join (
				select 
				budgets.job_id, 
				sum(budget_others.amount)  as courier_rate 
				from budget_others 
				left join budgets on budgets.id = budget_others.budget_id  
				where budget_others.cost_head_id =1 
				group by budgets.job_id
				) couriers on couriers.job_id=jobs.id
				left join (
				select 
				budgets.job_id, 
				sum(budget_others.amount)  as lab_rate 
				from budget_others 
				left join budgets on budgets.id = budget_others.budget_id  
				where budget_others.cost_head_id =5 
				group by budgets.job_id
				) labs on labs.job_id=jobs.id
				left join (
				select 
				budgets.job_id, 
				sum(budget_others.amount)  as insp_rate 
				from budget_others 
				left join budgets on budgets.id = budget_others.budget_id  
				where budget_others.cost_head_id =10 
				group by budgets.job_id
				) insps on insps.job_id=jobs.id
				left join (
				select 
				budgets.job_id, 
				sum(budget_others.amount)  as frei_rate 
				from budget_others 
				left join budgets on budgets.id = budget_others.budget_id  
				where budget_others.cost_head_id =15
				group by budgets.job_id
				) freis on freis.job_id=jobs.id
				left join (
				select 
				budgets.job_id, 
				sum(budget_others.amount)  as opa_rate 
				from budget_others 
				left join budgets on budgets.id = budget_others.budget_id  
				where budget_others.cost_head_id =20
				group by budgets.job_id
				) opas on opas.job_id=jobs.id
				left join (
				select 
				budgets.job_id, 
				sum(budget_others.amount)  as dep_rate 
				from budget_others 
				left join budgets on budgets.id = budget_others.budget_id  
				where budget_others.cost_head_id =25
				group by budgets.job_id
				) deps on deps.job_id=jobs.id
				left join (
				select 
				budgets.job_id, 
				sum(budget_others.amount)  as coc_rate 
				from budget_others 
				left join budgets on budgets.id = budget_others.budget_id  
				where budget_others.cost_head_id =30
				group by budgets.job_id
				) cocs on cocs.job_id=jobs.id
				left join (
				select 
				budgets.job_id, 
				sum(budget_others.amount)  as ict_rate 
				from budget_others 
				left join budgets on budgets.id = budget_others.budget_id  
				where budget_others.cost_head_id =35
				group by budgets.job_id
				) icts on icts.job_id=jobs.id

				left join (
				select 
				budgets.job_id, 
				avg(budget_cms.cm_per_pcs)  as cm_rate 
				from budget_cms 
				left join budgets on budgets.id = budget_cms.budget_id   
				group by budgets.job_id
				) cms on cms.job_id=jobs.id

				left join (
				select 
				budgets.job_id, 
				sum(budget_commercials.rate)  as commer_rate 
				from 
				budget_commercials 
				left join budgets on budgets.id = budget_commercials.budget_id   
				group by budgets.job_id
				) commers on commers.job_id=jobs.id

				left join (
				select
				sales_order_gmt_color_sizes.sale_order_id,
				sum(po_fabric_item_qties.qty) as lc_fabric_qty,
				sum(po_fabric_item_qties.amount) as lc_fabric_amount
				from
				po_fabrics
				join po_fabric_items on po_fabric_items.po_fabric_id=po_fabrics.id
				join po_fabric_item_qties on po_fabric_item_qties.po_fabric_item_id=po_fabric_items.id
				join budget_fabric_cons on budget_fabric_cons.id=po_fabric_item_qties.budget_fabric_con_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id
				join imp_lc_pos on imp_lc_pos.purchase_order_id=po_fabrics.id
				join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
				where 
				imp_lcs.menu_id=1 and
				imp_lcs.deleted_at is null and
				po_fabric_items.deleted_at is null and
				po_fabrics.deleted_at is null and
				imp_lc_pos.deleted_at is null and
				po_fabric_item_qties.deleted_at is null
				group by
				sales_order_gmt_color_sizes.sale_order_id
				) lcfabrics on lcfabrics.sale_order_id=sales_orders.id

				left join(
				select
				po_yarn_item_bom_qties.sale_order_id,
				sum(po_yarn_item_bom_qties.qty) as lc_yarn_qty,
				sum(po_yarn_item_bom_qties.amount) as lc_yarn_amount
				from
				po_yarn_item_bom_qties
				join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
				join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
				join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
				join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
				where 
				imp_lcs.menu_id=3 and
				imp_lcs.deleted_at is null and
				po_yarn_items.deleted_at is null and
				po_yarns.deleted_at is null and
				imp_lc_pos.deleted_at is null and
				po_yarn_item_bom_qties.deleted_at is null
				group by
				po_yarn_item_bom_qties.sale_order_id
				) lcyarns on lcyarns.sale_order_id=sales_orders.id

				left join(
				select
				sales_order_gmt_color_sizes.sale_order_id,
				sum(po_trim_item_qties.qty) as lc_trim_qty,
				sum(po_trim_item_qties.amount) as lc_trim_amount
				from
				po_trim_item_qties
				join budget_trim_cons on budget_trim_cons.id=po_trim_item_qties.budget_trim_con_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id=budget_trim_cons.sales_order_gmt_color_size_id
				join po_trim_items on po_trim_items.id=po_trim_item_qties.po_trim_item_id
				join po_trims on po_trims.id=po_trim_items.po_trim_id
				join imp_lc_pos on imp_lc_pos.purchase_order_id=po_trims.id
				join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
				where 
				imp_lcs.menu_id=2 and
				imp_lcs.deleted_at is null and
				po_trim_items.deleted_at is null and
				po_trims.deleted_at is null and
				imp_lc_pos.deleted_at is null and
				po_trim_item_qties.deleted_at is null and 
				budget_trim_cons.deleted_at is null and 
				sales_order_gmt_color_sizes.deleted_at is null 
				group by
				sales_order_gmt_color_sizes.sale_order_id
				) lctrims on lctrims.sale_order_id=sales_orders.id


				left join(
				select
				sales_order_countries.sale_order_id,
				sum(budget_fabric_cons.fin_fab) as fin_fab,
				sum(budget_fabric_cons.grey_fab) as grey_fab
				from 
				budget_fabric_cons
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id=budget_fabric_cons.sales_order_gmt_color_size_id
				join sales_order_countries on sales_order_countries.id=sales_order_gmt_color_sizes.sale_order_country_id
				where 
				budget_fabric_cons.deleted_at is null and 
				sales_order_gmt_color_sizes.deleted_at is null and 
				sales_order_countries.deleted_at is null 
				group by
				sales_order_countries.sale_order_id
				) fabrics on fabrics.sale_order_id=sales_orders.id
				left join(
				select 
				style_gmts.style_id, 
				sum(style_gmts.gmt_qty)  as item_ratio 
				from style_gmts   group by style_gmts.style_id
				) gmtsitems on gmtsitems.style_id=jobs.style_id

				where 
				sales_orders.ship_date >= ? and
				sales_orders.ship_date <= ? and
				sales_orders.order_status !=2
				--sales_orders.id=6020
				group by
				sales_orders.id,
				sales_orders.sale_order_no,
				sales_orders.ship_date,
				saleorders.qty,
				saleorders.rate,
				saleorders.amount,
				saleorders.plan_cut_qty,
				commissions.commi_rate,
				yarns.yarn_qty,
				yarns.yarn_amount,
				fabpurs.grey_fab_pur_req,
				fabpurs.fab_pur_amount,
				trims.trim_amount,
				yarndyeings.yarn_dying_qty,
				yarndyeings.yarn_dying_amount,
				knitings.kniting_qty,
				knitings.kniting_amount,
				dyeings.dying_qty,
				dyeings.dying_amount,
				dyeings.dyeing_overhead_amount,
				aops.aop_qty,
				aops.aop_amount,
				aops.aop_overhead_amount,
				burnouts.burnout_qty,
				burnouts.burnout_amount,
				finishings.finishing_qty,
				finishings.finishing_amount,
				washings.washing_qty,
				washings.washing_amount,
				scprintings.printing_qty,
				scprintings.printing_amount,
				scprintings.printing_overhead_amount,
				embs.emb_qty,
				embs.emb_amount,
				spembs.spemb_qty,
				spembs.spemb_amount,
				gmtdyeings.gmtdyeing_qty,
				gmtdyeings.gmtdyeing_amount,
				gmtwashings.gmtwashing_qty,
				gmtwashings.gmtwashing_amount,
				couriers.courier_rate,
				labs.lab_rate,
				insps.insp_rate,
				freis.frei_rate,
				opas.opa_rate,
				deps.dep_rate,
				cocs.coc_rate,
				icts.ict_rate,
				cms.cm_rate,
				commers.commer_rate,
				lcfabrics.lc_fabric_qty,
				lcfabrics.lc_fabric_amount,
				lcyarns.lc_yarn_qty,
				lcyarns.lc_yarn_amount,
				lctrims.lc_trim_qty,
				lctrims.lc_trim_amount,
				fabrics.fin_fab,
				fabrics.grey_fab,
				gmtsitems.item_ratio
				order by sales_orders.ship_date
				) m 
				group by
				m.rcv_month,
				m.rcv_month_no,
				m.rcv_year
				order by 
				m.rcv_year,m.rcv_month_no
			",[$date_from,$date_to])
        )
		->map(function($rows){
			$rows->dying_amount=$rows->dying_amount+$rows->dyeing_overhead_amount;
			$rows->aop_amount=$rows->aop_amount+$rows->aop_overhead_amount;
			$rows->printing_amount=$rows->printing_amount+$rows->printing_overhead_amount;
			return $rows;

		});
        $from=date('M-Y',strtotime($date_from));
        $to=date('M-Y',strtotime($date_to));
        $stndardprocessloss=$this->company->whereNotNull('dye_process_loss_per')->get()->first();
        $stndardprocesslossper=$stndardprocessloss->dye_process_loss_per;
    	
		return Template::loadView('Report.BudgetSummaryMatrix',['rows'=>$rows,'from'=>$from,'to'=>$to,'stndardprocesslossper'=>$stndardprocesslossper]);
		
    }
}