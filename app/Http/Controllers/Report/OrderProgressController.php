<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use Illuminate\Support\Carbon;

class OrderProgressController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $user;
	private $buyernature;
	private $itemaccount;
	private $autoyarn;
	private $teammember;
    private $team;
	public function __construct(
		StyleRepository $style,
		CompanyRepository $company,
		BuyerRepository $buyer,
		BuyerNatureRepository $buyernature,
		UserRepository $user,
		ItemAccountRepository $itemaccount,
		AutoyarnRepository $autoyarn,
		TeamRepository $team,
		TeammemberRepository $teammember
	)
    {
		$this->style=$style;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->user = $user;
		$this->buyernature = $buyernature;
		$this->itemaccount = $itemaccount;
		$this->autoyarn = $autoyarn;
		$this->team = $team;
		$this->teammember = $teammember;

		$this->middleware('auth');
		$this->middleware('permission:view.orderprogressreports',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
		$sortby=array_prepend(config('bprs.sortby'), '-Select-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.OrderProgress',['company'=>$company,'buyer'=>$buyer,'status'=>$status,'sortby'=>$sortby,'team'=>$team]);
    }

    private function getData()
    {
    	$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		//$company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto

		//echo $style; die;


    	$str2=date('Y-m-d');
		$yesterday = date('Y-m-d', strtotime('-1 days', strtotime($str2)));
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);

		$rows=$this->style
		->selectRaw(
	    'styles.id as style_id,
		styles.style_ref,
		styles.flie_src,
		styles.buying_agent_id,
		styles.contact,
		buyers.id as buyer_id,
		buyers.name as buyer_name,
		uoms.code as uom_name,
		seasons.name as season_name,
		teams.name as team_name,
		teamleadernames.id as teamleader_id,
		teamleadernames.name as team_name,
		users.id as user_id,
		users.name as team_member_name,
		productdepartments.department_name,
		jobs.job_no,
		companies.code as company_code,
		produced_company.code as produced_company_code,
		sales_orders.id as sale_order_id,
		sales_orders.sale_order_no,
		sales_orders.receive_date as sale_order_receive_date,
		sales_orders.internal_ref,
		sales_orders.ship_date,
		sales_orders.order_status,
		sum(sales_order_gmt_color_sizes.qty) as qty,
		sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
		avg(sales_order_gmt_color_sizes.rate) as rate,
		sum(sales_order_gmt_color_sizes.amount) as amount,
		bookedsmv.smv,
		bookedsmv.booked_minute,
		bookedsmv.sewing_effi_per,
		exfactory.qty as ship_qty,
		exfactory.max_exfactory_date,
		pending.qty as pending_ship_qty,
		pending.amount as pending_ship_value,
		explcsc.lc_sc_no,
		target.target_qty,
		dyedyarnrq.dyed_yarn_rq,
		yarnrq.yarn_req,
		yarnrcv.yarn_rcv,
		finfabrq.fin_fab_req,
		greyyarnfordye.grey_yarn_issue_qty_for_dye,
		dyedyarnrcv.dyed_yarn_rcv_qty,
		inhyarnisu.inh_yarn_isu_qty,
		outyarnisu.out_yarn_isu_qty,
		inhyarnisurtn.qty as inh_yarn_isu_rtn_qty,
		outyarnisurtn.qty as out_yarn_isu_rtn_qty,
		prodknit.knit_qty,
		prodknit.prod_knit_qty,
		prodbatch.batch_qty,
		proddyeing.dyeing_qty,
		prodfinish.finish_qty,
		prodcut.cut_qty,
		prodcut.min_cut_qc_date,
		prodscrreq.req_scr_qty,
		prodscrdlv.snd_scr_qty,
		prodscrrcv.rcv_scr_qty,
		prodsewline.sew_line_qty,
		prodsew.sew_qty,
		prodiron.iron_qty,
		prodpoly.poly_qty,
		carton.car_qty,
		inspec.insp_pass_qty,
		inspec.insp_re_check_qty,
		inspec.insp_faild_qty,
		ci.ci_qty,
		ci.ci_amount,
		poyarnlc.qty as poyarnlc_qty
		')
		->leftJoin('buyers', function($join)  {
			$join->on('styles.buyer_id', '=', 'buyers.id');
		})
		->leftJoin('uoms', function($join)  {
			$join->on('styles.uom_id', '=', 'uoms.id');
		})
		->leftJoin('seasons', function($join)  {
			$join->on('styles.season_id', '=', 'seasons.id');
		})
		->leftJoin('teams', function($join)  {
			$join->on('styles.team_id', '=', 'teams.id');
		})
		->leftJoin('teammembers', function($join)  {
			$join->on('styles.factory_merchant_id', '=', 'teammembers.id');
		})
		->leftJoin('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
		})
		->leftJoin('productdepartments', function($join)  {
			$join->on('productdepartments.id', '=', 'styles.productdepartment_id');
		})
		->leftJoin('teammembers as teamleaders', function($join)  {
			$join->on('styles.teammember_id', '=', 'teamleaders.id');
		})
		->leftJoin('users as teamleadernames', function($join)  {
			$join->on('teamleadernames.id', '=', 'teamleaders.user_id');
		})
		->leftJoin('jobs', function($join)  {
			$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('companies', function($join)  {
			$join->on('companies.id', '=', 'jobs.company_id');
		})
		->leftJoin('sales_orders', function($join)  {
			$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('companies as produced_company', function($join)  {
			$join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
			$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		})
		
		

		->leftJoin(\DB::raw('(
			select count(exp_lc_scs.lc_sc_no) as lc_sc_no,
			sales_orders.sale_order_no,
			exp_pi_orders.sales_order_id 
			from exp_pi_orders
			join sales_orders on sales_orders.id=exp_pi_orders.sales_order_id
			join exp_pis on exp_pis.id=exp_pi_orders.exp_pi_id
			join exp_lc_sc_pis on exp_lc_sc_pis.exp_pi_id=exp_pis.id
			join exp_lc_scs on exp_lc_scs.id=exp_lc_sc_pis.exp_lc_sc_id 
			where exp_pi_orders.deleted_at is null
			group by 
			exp_pi_orders.sales_order_id,
			sales_orders.sale_order_no 
		) explcsc'), "explcsc.sales_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw('(select 
		m.sales_order_id,
		avg(m.smv) as smv,
		avg(m.sewing_effi_per) as sewing_effi_per,
		sum(m.booked_minute) as booked_minute
		from 
		(
		SELECT 
		sales_orders.id as sales_order_id,
		style_gmts.smv,
		style_gmts.sewing_effi_per,
		sales_order_gmt_color_sizes.qty as qty,
		sales_order_gmt_color_sizes.qty * style_gmts.smv as booked_minute
		FROM sales_orders 
		join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		where  1=1) m group by m.sales_order_id) bookedsmv'), "bookedsmv.sales_order_id", "=", "sales_orders.id")
		

		->leftJoin(\DB::raw('(select
		target_transfers.sales_order_id,
		sum(target_transfers.qty)  as target_qty
		from
		target_transfers
		where
		target_transfers.process_id=8
		and target_transfers.deleted_at is null
		group by
		target_transfers.sales_order_id ) target'), "target.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
		sales_orders.id as sales_order_id,
		sum(budget_fabric_cons.grey_fab) as yarn_req,
		sum(budget_fabric_cons.fin_fab) as fin_fab_req
		FROM sales_orders 
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
		join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
		join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
		join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id

		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where style_fabrications.material_source_id !=1 and sales_orders.order_status !=2 and   1=1 $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by sales_orders.id) yarnrq"), "yarnrq.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
		sales_orders.id as sales_order_id,
		sum(budget_fabric_cons.grey_fab) as yarn_req,
		sum(budget_fabric_cons.fin_fab) as fin_fab_req
		FROM sales_orders 
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
		join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id

		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where sales_orders.order_status !=2 and   1=1 $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by sales_orders.id) finfabrq"), "finfabrq.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select 
		po_yarn_item_bom_qties.sale_order_id as sales_order_id,
		sum(inv_yarn_rcv_item_sos.qty) yarn_rcv
		from
		po_yarn_item_bom_qties
		join inv_yarn_rcv_item_sos on inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id=po_yarn_item_bom_qties.id
		where po_yarn_item_bom_qties.deleted_at is null and  inv_yarn_rcv_item_sos.deleted_at is null
		group by po_yarn_item_bom_qties.sale_order_id) yarnrcv"), "yarnrcv.sales_order_id", "=", "sales_orders.id")


		->leftJoin(\DB::raw("(SELECT 
		sales_orders.id as sales_order_id,
		sum(budget_yarn_dyeing_cons.bom_qty) as dyed_yarn_rq
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id
		where  1=1 $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by sales_orders.id) dyedyarnrq"), "dyedyarnrq.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
		sales_orders.id as sales_order_id,
		sales_orders.ship_date,
		sales_orders.sale_order_no,
		sum(budget_yarn_dyeing_cons.bom_qty) as dyed_yarn_rq,
		sum(inv_yarn_isu_items.qty) as grey_yarn_issue_qty_for_dye
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id = budget_yarn_dyeing_cons.id
		join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
		join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id

		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where   inv_isus.isu_against_id=9 and inv_yarn_isu_items.deleted_at is null $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by sales_orders.id,sales_orders.ship_date,sales_orders.sale_order_no) greyyarnfordye"), "greyyarnfordye.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
		sales_orders.id as sales_order_id,
		sum(inv_yarn_rcv_items.qty) as dyed_yarn_rcv_qty
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id = budget_yarn_dyeing_cons.id
		join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
		join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
		join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_isu_item_id = inv_yarn_isu_items.id
		join inv_yarn_rcvs on inv_yarn_rcvs.id = inv_yarn_rcv_items.inv_yarn_rcv_id
		join inv_rcvs on inv_rcvs.id = inv_yarn_rcvs.inv_rcv_id

		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where   inv_isus.isu_against_id=9 and inv_yarn_isu_items.deleted_at is null and inv_rcvs.receive_against_id=9 and inv_yarn_rcv_items.deleted_at is null $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by sales_orders.id) dyedyarnrcv"), "dyedyarnrcv.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
		sales_orders.id as sales_order_id,
		sum(inv_yarn_isu_items.qty) as inh_yarn_isu_qty
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
		where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto  
		group by sales_orders.id) inhyarnisu"), "inhyarnisu.sales_order_id", "=", "sales_orders.id")
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
		and inv_yarn_transactions.trans_type_id=1  $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto  
		group by 
		inv_yarn_rcv_items.sales_order_id) inhyarnisurtn"), "inhyarnisurtn.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
		sales_orders.id as sales_order_id,
		sum(inv_yarn_isu_items.qty) as out_yarn_isu_qty
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
		--join companies on companies.id = suppliers.company_id
		join companies on companies.id = inv_isus.company_id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto  
		group by sales_orders.id) outyarnisu"), "outyarnisu.sales_order_id", "=", "sales_orders.id")

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
		--join companies on companies.id = suppliers.company_id
		join companies on companies.id = inv_rcvs.company_id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where inv_rcvs.receive_basis_id=4
		and inv_yarn_transactions.deleted_at is null
		and inv_yarn_rcv_items.deleted_at is null
		and inv_rcvs.deleted_at is null
		and inv_yarn_transactions.trans_type_id=1  $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto  
		group by 
		inv_yarn_rcv_items.sales_order_id) outyarnisurtn"), "outyarnisurtn.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
		select
		m.sales_order_id,
		sum(m.qc_pass_qty) as knit_qty,
		sum(m.roll_weight) as prod_knit_qty
		from 
		(
		select
		prod_knit_items.pl_knit_item_id,
		prod_knit_items.po_knit_service_item_qty_id,
		prod_knit_item_rolls.roll_weight,
		prod_knit_qcs.reject_qty,   
		prod_knit_qcs.qc_pass_qty,
		CASE 
		WHEN  inhprods.sales_order_id IS NULL THEN outprods.sales_order_id 
		ELSE inhprods.sales_order_id
		END as sales_order_id
		from
		prod_knits
		join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
		join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
		left join prod_knit_rcv_by_qcs on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
		left join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id
		/*prod_knit_qcs
		join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id=prod_knit_qcs.prod_knit_rcv_by_qc_id
		join prod_knit_item_rolls on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
		join prod_knit_items on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
		join prod_knits on prod_knits.id=prod_knit_items.prod_knit_id*/
		left join (
		select 
		pl_knit_items.id as pl_knit_item_id,
		sales_orders.id as sales_order_id
		from 
		sales_orders
		join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
		join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
		and po_knit_service_items.deleted_at is null
		join so_knit_po_items on so_knit_po_items.po_knit_service_item_qty_id=po_knit_service_item_qties.id
		join so_knit_refs on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
		join pl_knit_items on pl_knit_items.so_knit_ref_id=so_knit_refs.id
		join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id

		) inhprods on inhprods.pl_knit_item_id=prod_knit_items.pl_knit_item_id

		left join (
		select 
		po_knit_service_item_qties.id as po_knit_service_item_qty_id,
		sales_orders.id as sales_order_id
		from 
		sales_orders
		join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
		join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
		join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
		) outprods on outprods.po_knit_service_item_qty_id=prod_knit_items.po_knit_service_item_qty_id
		) m group by  m.sales_order_id) prodknit"), "prodknit.sales_order_id", "=", "sales_orders.id")


		->leftJoin(\DB::raw("(
		select 
		sales_orders.id as sales_order_id,
		sum(prod_batch_rolls.qty) as batch_qty
		from 
		prod_batches
		join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
		join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
		join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
		join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
		join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
		join so_dyeing_pos on so_dyeings.id=so_dyeing_pos.so_dyeing_id
		join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
		join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
		join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
		and po_dyeing_service_items.deleted_at is null
		join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where 
		prod_batches.batch_for=1 and
		prod_batches.is_redyeing=0 and 
		prod_batches.deleted_at is null and 
		prod_batch_rolls.deleted_at is null 
		$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by
		sales_orders.id
		) prodbatch"), "prodbatch.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
		select 
		sales_orders.id as sales_order_id,
		sum(prod_batch_rolls.qty) as dyeing_qty
		from 
		prod_batches
		join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
		join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
		join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
		join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
		join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
		join so_dyeing_pos on so_dyeings.id=so_dyeing_pos.so_dyeing_id
		join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
		join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
		join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
		and po_dyeing_service_items.deleted_at is null
		join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where 
		prod_batches.batch_for=1 and
		prod_batches.is_redyeing=0 and 
		prod_batches.deleted_at is null and 
		prod_batch_rolls.deleted_at is null  and
		prod_batches.unloaded_at is not null 
		$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by
		sales_orders.id
		) proddyeing"), "proddyeing.sales_order_id", "=", "sales_orders.id")


		->leftJoin(\DB::raw("(
		select 
		sales_orders.id as sales_order_id,
		sum(prod_batch_finish_qc_rolls.qty) as finish_qty
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
		join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where 
		prod_batches.batch_for=1 and
		prod_batches.is_redyeing=0 and 
		prod_batches.deleted_at is null and 
		prod_batch_rolls.deleted_at is null  and
		prod_batches.unloaded_at is not null 
		$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by
		sales_orders.id
		) prodfinish"), "prodfinish.sales_order_id", "=", "sales_orders.id")


		->leftJoin(\DB::raw("(
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_cutting_qties.qty) as cut_qty,
		min(prod_gmt_cuttings.cut_qc_date) as min_cut_qc_date
		FROM prod_gmt_cuttings
		join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
		group by 
		sales_orders.id) prodcut"), "prodcut.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
		select sales_order_gmt_color_sizes.sale_order_id as sales_order_id,sum(budget_emb_cons.req_cons) as req_scr_qty
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =45
		group by sales_order_gmt_color_sizes.sale_order_id) prodscrreq"), "prodscrreq.sales_order_id", "=", "sales_orders.id")


		->leftJoin(\DB::raw("(
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_dlv_print_qties.qty) as snd_scr_qty
		FROM prod_gmt_dlv_prints
		join prod_gmt_dlv_print_orders on prod_gmt_dlv_print_orders.prod_gmt_dlv_print_id = prod_gmt_dlv_prints.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_dlv_print_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_dlv_print_qties on prod_gmt_dlv_print_qties.prod_gmt_dlv_print_order_id = prod_gmt_dlv_print_orders.id
		group by 
		sales_orders.id) prodscrdlv"), "prodscrdlv.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_print_rcv_qties.qty) as rcv_scr_qty
		FROM prod_gmt_print_rcvs
		join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id
		group by 
		sales_orders.id) prodscrrcv"), "prodscrrcv.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_sewing_line_qties.qty) as sew_line_qty
		FROM prod_gmt_sewing_lines
		join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id = prod_gmt_sewing_lines.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_line_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_sewing_line_qties on prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id = prod_gmt_sewing_line_orders.id
		group by 
		sales_orders.id) prodsewline"), "prodsewline.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
			SELECT 
			sales_orders.id as sales_order_id,
			sum(prod_gmt_iron_qties.qty) as iron_qty
			FROM prod_gmt_irons
			join prod_gmt_iron_orders on prod_gmt_iron_orders.prod_gmt_iron_id = prod_gmt_irons.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_iron_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_iron_qties on prod_gmt_iron_qties.prod_gmt_iron_order_id = prod_gmt_iron_orders.id
			group by 
			sales_orders.id) prodiron"), "prodiron.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
			SELECT 
			sales_orders.id as sales_order_id,
			sum(prod_gmt_poly_qties.qty) as poly_qty
			FROM prod_gmt_polies
			join prod_gmt_poly_orders on prod_gmt_poly_orders.prod_gmt_poly_id = prod_gmt_polies.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_poly_qties on prod_gmt_poly_qties.prod_gmt_poly_order_id = prod_gmt_poly_orders.id
			group by 
			sales_orders.id) prodpoly"), "prodpoly.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
			SELECT 
			sales_orders.id as sales_order_id,
			sum(prod_gmt_sewing_qties.qty) as sew_qty
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id
			--where prod_gmt_sewing_qties.deleted_at is null
			group by 
			sales_orders.id) prodsew"), "prodsew.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
		SELECT 
		sales_orders.id as sales_order_id,
		sum(style_pkg_ratios.qty) as car_qty 
		FROM prod_gmt_carton_entries
		join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
		join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
		join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id) carton"), "carton.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
		select 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_inspection_orders.qty) as insp_pass_qty,
		sum(prod_gmt_inspection_orders.re_check_qty) as insp_re_check_qty,
		sum(prod_gmt_inspection_orders.failed_qty) as insp_faild_qty
		from
		prod_gmt_inspections
		join prod_gmt_inspection_orders on prod_gmt_inspection_orders.prod_gmt_inspection_id=prod_gmt_inspections.id
		join sales_order_countries on sales_order_countries.id=prod_gmt_inspections.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by
		sales_orders.id) inspec"), "inspec.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
			SELECT sales_orders.id as sale_order_id,
			sum(style_pkg_ratios.qty) as qty,
			max(prod_gmt_ex_factories.exfactory_date) as max_exfactory_date

			FROM sales_orders  
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			join style_pkgs on style_pkgs.style_id = styles.id 
			join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
			and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id 
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id = prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id 
			where prod_gmt_ex_factory_qties.deleted_at is null 
			and prod_gmt_carton_details.deleted_at is null
			$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
			group by sales_orders.id) exfactory"), "exfactory.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT sales_orders.id as sale_order_id,sum(sales_order_gmt_color_sizes.qty) as qty,sum(sales_order_gmt_color_sizes.amount) as amount FROM sales_orders 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			where sales_orders.ship_date<='".$yesterday."' $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto  and sales_orders.order_status=1
			group by sales_orders.id) pending"), "pending.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
		select 
		sales_orders.id as sales_order_id,
		sum(exp_invoice_orders.qty) as ci_qty, 
		sum(exp_invoice_orders.amount) as ci_amount 
		from
		sales_orders 
		join exp_pi_orders on exp_pi_orders.sales_order_id=sales_orders.id
		join exp_invoice_orders on exp_invoice_orders.exp_pi_order_id = exp_pi_orders.id 
		join exp_invoices on exp_invoices.id=exp_invoice_orders.exp_invoice_id
		where exp_invoices.invoice_status_id=2
		and exp_invoice_orders.deleted_at is null
		group by sales_orders.id) ci"), "ci.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select
            po_yarn_item_bom_qties.sale_order_id as sales_order_id,
            sum(po_yarn_item_bom_qties.qty) as qty
            from
            sales_orders
            join po_yarn_item_bom_qties on sales_orders.id=po_yarn_item_bom_qties.sale_order_id
            join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
            join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
            join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
            join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id

            join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id
            where imp_lcs.menu_id=3 $company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
            and po_yarn_item_bom_qties.deleted_at is null
            and po_yarn_items.deleted_at is null
            and po_yarns.deleted_at is null
            and imp_lc_pos.deleted_at is null
            and imp_lcs.deleted_at is null
            group by
            po_yarn_item_bom_qties.sale_order_id) poyarnlc"), "poyarnlc.sales_order_id", "=", "sales_orders.id")

		



		->when(request('buyer_id'), function ($q) {
			return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
			return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('style_id'), function ($q) {
			return $q->where('styles.id', '=', request('style_id', 0));
		})
		->when(request('factory_merchant_id'), function ($q) {
			return $q->where('styles.factory_merchant_id', '=', request('factory_merchant_id', 0));
		})
		->when(request('company_id'), function ($q) {
			return $q->where('jobs.company_id', '=', request('company_id', 0));
		})
		->when(request('produced_company_id'), function ($q) {
			return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id', 0));
		})
		->when(request('job_no'), function ($q) {
			return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when(request('date_from'), function ($q) {
			return $q->where('sales_orders.ship_date', '>=',request('date_from', 0));
		})
		->when(request('date_to'), function ($q) {
			return $q->where('sales_orders.ship_date', '<=',request('date_to', 0));
		})
		->when(request('receive_date_from'), function ($q) {
			return $q->where('sales_orders.receive_date', '>=',request('receive_date_from', 0));
			})
		->when(request('receive_date_to'), function ($q) {
			return $q->where('sales_orders.receive_date', '<=',request('receive_date_to', 0));
		})
		->when(request('order_status'), function ($q) {
			return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['sales_orders.order_status','!=',2]])
		->groupBy([
			'styles.id',
			'styles.style_ref',
			'styles.flie_src',
			'styles.buying_agent_id',
			'styles.contact',
			'buyers.id',
			'buyers.name',
			'uoms.code',
			'seasons.name',
			'teams.name',
			'teamleadernames.id',
			'teamleadernames.name',
			'users.id',
			'users.name',
			'productdepartments.department_name',
			'jobs.job_no',
			'companies.code',
			'produced_company.code',
			'sales_orders.id',
			'sales_orders.sale_order_no',
			'sales_orders.receive_date',
			'sales_orders.internal_ref',
			'sales_orders.ship_date',
			'sales_orders.order_status',
			'bookedsmv.smv',
			'bookedsmv.sewing_effi_per',
		    'bookedsmv.booked_minute',
			'exfactory.qty',
			'exfactory.max_exfactory_date',
			'pending.qty',
			'pending.amount',
			'explcsc.lc_sc_no',
			'target.target_qty',
			'dyedyarnrq.dyed_yarn_rq',
			'yarnrq.yarn_req',
			'finfabrq.fin_fab_req',
			'yarnrcv.yarn_rcv',
			'greyyarnfordye.grey_yarn_issue_qty_for_dye',
			'dyedyarnrcv.dyed_yarn_rcv_qty',
			'inhyarnisu.inh_yarn_isu_qty',
			'outyarnisu.out_yarn_isu_qty',
			'inhyarnisurtn.qty',
			'outyarnisurtn.qty',
			'prodknit.knit_qty',
			'prodknit.prod_knit_qty',
			'prodbatch.batch_qty',
			'proddyeing.dyeing_qty',
			'prodfinish.finish_qty',
			'prodcut.cut_qty',
			'prodcut.min_cut_qc_date',
			'prodscrreq.req_scr_qty',
			'prodscrdlv.snd_scr_qty',
			'prodscrrcv.rcv_scr_qty',
			'prodsewline.sew_line_qty',
			'prodsew.sew_qty',
			'prodiron.iron_qty',
			'prodpoly.poly_qty',
			'carton.car_qty',
			'inspec.insp_pass_qty',
			'inspec.insp_re_check_qty',
			'inspec.insp_faild_qty',
			'ci.ci_qty',
			'ci.ci_amount',
			'poyarnlc.qty'
		])
		->orderby('sales_orders.ship_date')
		->get();

		

		$data=$rows->map(function($rows) use($itemcomplexity,$buyinghouses){
			$receive_date = Carbon::parse($rows->sale_order_receive_date);
			$ship_date = Carbon::parse($rows->ship_date);
			$diff = $receive_date->diffInDays($ship_date);
			if($diff >1){
			$diff.=" Days";
			}else{
			$diff.=" Day";
			}
			$rows->lead_time=$diff;

			$min_cut_qc_date = Carbon::parse($rows->min_cut_qc_date);
			$max_exfactory_date = Carbon::parse($rows->max_exfactory_date);
			$cuttoshipdays = $min_cut_qc_date->diffInDays($max_exfactory_date);
			if($cuttoshipdays>1){
				$cuttoshipdays.=" Days";
			}else{
				$cuttoshipdays.=" Day";
			}
			if($rows->order_status==4){
			$rows->cut_to_ship_days=$cuttoshipdays;
			}else{
				$rows->cut_to_ship_days='--';
			}

			$rows->agent_name=	isset($buyinghouses[$rows->buying_agent_id])? $buyinghouses[$rows->buying_agent_id]:'';
			$rows->buying_agent_name=$rows->agent_name.", ". $rows->contact;
			$rows->ship_value=$rows->ship_qty*$rows->rate;
			$rows->yet_to_ship_qty=$rows->ship_qty-$rows->qty;
			$rows->yet_to_ship_value=$rows->ship_value-$rows->amount;

			$rows->sale_order_receive_date=date('d-M-Y',strtotime($rows->sale_order_receive_date));
			$rows->delivery_date=date('d-M-Y',strtotime($rows->ship_date));
			$rows->delivery_month=date('M',strtotime($rows->ship_date));
			$rows->booked_minute=number_format($rows->booked_minute,2);
			$rows->smv=number_format($rows->smv,2);
			$rows->sewing_effi_per=number_format($rows->sewing_effi_per,2);
			//$rows->item_complexity=$itemcomplexity[$rows->item_complexity];
			$rows->poyarnlc_qty_bal=$rows->yarn_req-$rows->poyarnlc_qty;
			$rows->inh_yarn_isu_qty=$rows->inh_yarn_isu_qty-$rows->inh_yarn_isu_rtn_qty;
			$rows->out_yarn_isu_qty=$rows->out_yarn_isu_qty-$rows->out_yarn_isu_rtn_qty;

			$rows->knit_bal=$rows->knit_qty-$rows->yarn_req;

			$rows->batch_bal=$rows->batch_qty-$rows->yarn_req;
			$rows->dyeing_bal=$rows->dyeing_qty-$rows->yarn_req;
			$rows->finish_bal=$rows->finish_qty-$rows->fin_fab_req;

			$rows->cut_bal=$rows->plan_cut_qty-$rows->cut_qty;
			$rows->snd_scr_qty_bal=$rows->req_scr_qty-$rows->snd_scr_qty;
			$rows->bal_scr_qty=$rows->rcv_scr_qty-$rows->snd_scr_qty;
			$rows->sew_line_bal=$rows->sew_line_qty-$rows->cut_qty;
			$rows->sew_bal=$rows->sew_qty-$rows->qty;

			$rows->iron_bal_qty=$rows->qty-$rows->iron_qty;
			$rows->iron_bal=$rows->sew_qty-$rows->iron_qty;

			$rows->poly_bal_qty=$rows->qty-$rows->poly_qty;
			$rows->poly_bal=$rows->iron_qty-$rows->poly_qty;

			$rows->car_bal=$rows->car_qty-$rows->sew_qty;
			$rows->ci_qty_bal=$rows->ci_qty-$rows->ship_qty;
			$rows->ci_amount_bal=$rows->ci_amount-$rows->ship_value;
			$rows->qty=number_format($rows->qty,'0','.',',');
			$rows->rate=number_format($rows->rate,'2','.',',');
			$rows->amount=number_format($rows->amount,'2','.',',');
			$rows->ship_qty=number_format($rows->ship_qty,0,'.',',');
			$rows->ship_value=number_format($rows->ship_value,2,'.',',');
			$rows->yet_to_ship_qty=number_format($rows->yet_to_ship_qty,'0','.',',');
			$rows->yet_to_ship_value=number_format($rows->yet_to_ship_value,'2','.',',');
			$rows->pending_ship_qty=number_format($rows->pending_ship_qty,'0','.',',');
			$rows->pending_ship_value=number_format($rows->pending_ship_value,'2','.',',');

			$rows->dyed_yarn_bal_qty=number_format($rows->dyed_yarn_rq-$rows->dyed_yarn_rcv_qty,'2','.',',');
			$rows->grey_yarn_issue_for_dye_bal=number_format($rows->grey_yarn_issue_qty_for_dye- $rows->dyed_yarn_rq,'2','.',',');

			$rows->dyed_yarn_rq=number_format($rows->dyed_yarn_rq,'2','.',',');
			$rows->grey_yarn_issue_qty_for_dye=number_format($rows->grey_yarn_issue_qty_for_dye,'2','.',',');
			$rows->dyed_yarn_rcv_qty=number_format($rows->dyed_yarn_rcv_qty,'2','.',',');

			$rows->poyarnlc_qty_bal=number_format($rows->poyarnlc_qty_bal,'2','.',',');
			$rows->yarn_rcv_bal=number_format($rows->yarn_rcv-$rows->yarn_req,'2','.',',');
			$rows->yarn_req_bal=number_format($rows->yarn_req-($rows->inh_yarn_isu_qty+$rows->out_yarn_isu_qty),'2','.',',');

			$rows->yarn_req=number_format($rows->yarn_req,'2','.',',');
			$rows->poyarnlc_qty=number_format($rows->poyarnlc_qty,'2','.',',');
			$rows->yarn_rcv=number_format($rows->yarn_rcv,'2','.',',');
			$rows->inh_yarn_isu_qty=number_format($rows->inh_yarn_isu_qty,'2','.',',');
			$rows->out_yarn_isu_qty=number_format($rows->out_yarn_isu_qty,'2','.',',');
			$rows->lc_sc_no=$rows->lc_sc_no? "Yes:".$rows->lc_sc_no:'No';
			$rows->target_qty=number_format($rows->target_qty,'0','.',',');
			$rows->knit_qty=number_format($rows->knit_qty,'0','.',',');
			$rows->knit_bal=number_format($rows->knit_bal,'0','.',',');
			$rows->batch_qty=number_format($rows->batch_qty,'0','.',',');
			$rows->batch_bal=number_format($rows->batch_bal,'0','.',',');
			$rows->dyeing_qty=number_format($rows->dyeing_qty,'0','.',',');
			$rows->dyeing_bal=number_format($rows->dyeing_bal,'0','.',',');

			$rows->fin_fab_req=number_format($rows->fin_fab_req,'0','.',',');
			$rows->finish_qty=number_format($rows->finish_qty,'0','.',',');
			$rows->finish_bal=number_format($rows->finish_bal,'0','.',',');

			$rows->plan_cut_qty=number_format($rows->plan_cut_qty,'0','.',',');
			$rows->cut_qty=number_format($rows->cut_qty,'0','.',',');
			$rows->cut_bal=number_format($rows->cut_bal,'0','.',',');
			$rows->req_scr_qty=number_format($rows->req_scr_qty,'0','.',',');
			$rows->snd_scr_qty=number_format($rows->snd_scr_qty,'0','.',',');
			$rows->snd_scr_qty_bal=number_format($rows->snd_scr_qty_bal,'0','.',',');
			$rows->rcv_scr_qty=number_format($rows->rcv_scr_qty,'0','.',',');
			$rows->bal_scr_qty=number_format($rows->bal_scr_qty,'0','.',',');
			$rows->sew_line_qty=number_format($rows->sew_line_qty,'0','.',',');
			$rows->sew_line_bal=number_format($rows->sew_line_bal,'0','.',',');

			$rows->sew_qty=number_format($rows->sew_qty,'0','.',',');
			$rows->sew_bal=number_format($rows->sew_bal,'0','.',',');

			$rows->iron_qty=number_format($rows->iron_qty,'0','.',',');
			$rows->iron_bal_qty=number_format($rows->iron_bal_qty,'0','.',',');
			$rows->iron_bal=number_format($rows->iron_bal,'0','.',',');

			$rows->poly_qty=number_format($rows->poly_qty,'0','.',',');
			$rows->poly_bal_qty=number_format($rows->poly_bal_qty,'0','.',',');
			$rows->poly_bal=number_format($rows->poly_bal,'0','.',',');

			$rows->car_qty=number_format($rows->car_qty,'0','.',',');
			$rows->car_bal=number_format($rows->car_bal,'0','.',',');
			$rows->insp_pass_qty=number_format($rows->insp_pass_qty,'0','.',',');
			$rows->insp_re_check_qty=number_format($rows->insp_re_check_qty,'0','.',',');
			$rows->insp_faild_qty=number_format($rows->insp_faild_qty,'0','.',',');
			$rows->ci_qty=number_format($rows->ci_qty,'0','.',',');
			$rows->ci_qty_bal=number_format($rows->ci_qty_bal,'0','.',',');
			$rows->ci_amount=number_format($rows->ci_amount,'2','.',',');
			$rows->ci_amount_bal=number_format($rows->ci_amount_bal,'2','.',',');
			return $rows;
		});

		return $data;
    }


	public function reportData() {
		return response()->json($this->getData());
	}

	
	public function getDealMerchant(){
			$dlmerchant = $this->user
			->leftJoin('employee_h_rs', function($join)  {
				$join->on('users.id', '=', 'employee_h_rs.user_id');
			})
			->where([['user_id','=',request('user_id',0)]])
			->get([
				'users.id as user_id',
				/* 'users.name as team_member', */
				'employee_h_rs.name',
				'employee_h_rs.date_of_join',
				'employee_h_rs.last_education',
				'employee_h_rs.address',
				'employee_h_rs.email',
				'employee_h_rs.experience',
				'employee_h_rs.contact'
			])
			->map(function($dlmerchant){
				$dlmerchant->date_of_join=date('d-M-Y',strtotime($dlmerchant->date_of_join));
				return $dlmerchant;
			});
			echo json_encode($dlmerchant);
	}

	public function getBuyingHouse(){
			$rows= $this->buyer
			->selectRaw(
				'buyers.id as buyer_id,
				buyers.name as buyer_name,
				buyer_branches.name as branch_name,
				buyer_branches.contact_person,
				buyer_branches.email,
				buyer_branches.designation,
				buyer_branches.address'
			)
			->leftJoin('buyer_branches',function($join){
				$join->on('buyer_branches.buyer_id','=','buyers.id');
			})
			->where([['buyers.id','=',request('buyer_id',0)]])
			->get([
			    'buyers.id as buyer_id',
				'buyers.name',
				'buyer_branches.name',
				'buyer_branches.contact_person',
				'buyer_branches.email',
				'buyer_branches.designation',
				'buyer_branches.address'
		 ]);
			echo json_encode($rows) ;
	}

	public function getOpFileSrc(){
		return response()->json($this->style
		->leftJoin('style_file_uploads',function($join){
		$join->on('style_file_uploads.style_id','=','styles.id');
		})
		->where([['style_id','=',request('style_id',0)]])
		->get([
		'styles.id as style_id',
		'styles.style_ref',
		'style_file_uploads.*'
		]));
	}

	public function getLCSc()
	{
		$sale_order_id=request('sale_order_id',0);
		$payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        $contractNature = array_prepend(array_only(config('bprs.contractNature'), [1,3,2]),'-Select-','');


		$results = collect(
          \DB::select("
				select 
				exp_lc_scs.lc_sc_no,
				exp_lc_scs.lc_sc_date,
				exp_lc_scs.lc_sc_value,
				exp_lc_scs.file_no ,
				buyers.name as buyer_name,
				currencies.code as currency_code,
				exp_lc_scs.pay_term_id,
				exp_lc_scs.incoterm_id,
				exp_lc_scs.lc_sc_nature_id,
				exp_lc_scs.remarks
				from 
				exp_pi_orders
				join exp_pis on exp_pis.id=exp_pi_orders.exp_pi_id
				join exp_lc_sc_pis on exp_lc_sc_pis.exp_pi_id=exp_pis.id
				join exp_lc_scs on exp_lc_scs.id=exp_lc_sc_pis.exp_lc_sc_id
				join buyers on buyers.id=exp_lc_scs.buyer_id
				join currencies on currencies.id=exp_lc_scs.currency_id
				where exp_pi_orders.sales_order_id=?
				and exp_pi_orders.deleted_at is null
          ", [$sale_order_id])
        )
        ->map(function($results) use($payterm,$incoterm,$contractNature){
          $results->pay_term=$results->pay_term_id?$payterm[$results->pay_term_id]:'';
          $results->inco_term=$results->incoterm_id?$incoterm[$results->incoterm_id]:'';
          $results->lc_nature=$results->lc_sc_nature_id?$contractNature[$results->lc_sc_nature_id]:'';
          $results->lc_sc_date=date('d-M-Y',strtotime($results->lc_sc_date));
          $results->lc_sc_value=number_format($results->lc_sc_value,2);
          return $results;
        });
        echo json_encode($results);
	}

	public function getOrderQty(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//$company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');


		$results = \DB::select("
		select
		styles.id as style_id,
		styles.style_ref,
		styles.flie_src,
		styles.buying_agent_id,
		styles.contact,
		buyers.id as buyer_id,
		buyers.name as buyer_name,
		uoms.code as uom_name,
		seasons.name as season_name,
		teams.name as team_name,
		teamleadernames.id as teamleader_id,
		teamleadernames.name as team_name,
		users.id as user_id,
		users.name as team_member_name,
		productdepartments.department_name,
		jobs.job_no,
		companies.code as company_code,
		produced_company.code as produced_company_code,
		sales_orders.id as sale_order_id,
		sales_orders.sale_order_no,
		sales_orders.receive_date as sale_order_receive_date,
		sales_orders.internal_ref,
		sales_orders.ship_date,
		countries.name as country_name,
		item_accounts.item_description,
		colors.name as color_name,
		sizes.name as size_name,
		sales_order_gmt_color_sizes.qty as qty,
		sales_order_gmt_color_sizes.plan_cut_qty as plan_cut_qty,
		sales_order_gmt_color_sizes.rate as rate,
		sales_order_gmt_color_sizes.amount as amount,
		style_gmts.smv,
		style_gmts.item_complexity,
		(style_gmts.smv*sales_order_gmt_color_sizes.qty)  as booked_minute

		from
		styles
		join buyers on buyers.id=styles.buyer_id
		join uoms on uoms.id=styles.uom_id
		join seasons on seasons.id=styles.season_id
		join teams on teams.id=styles.team_id
		left join teammembers on teammembers.id=styles.factory_merchant_id
		left join users on users.id=teammembers.user_id
		left join productdepartments on productdepartments.id=styles.productdepartment_id
		left join teammembers  teamleaders on teamleaders.id=styles.teammember_id
		left join users teamleadernames on teamleadernames.id=teamleaders.user_id
		left join jobs on jobs.style_id=styles.id
		left join companies on companies.id=jobs.company_id
		left join sales_orders on sales_orders.job_id=jobs.id
		left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
		join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
		join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id
		join item_accounts on item_accounts.id = style_gmts.item_account_id
		join colors on colors.id = style_colors.color_id
		join sizes on sizes.id = style_sizes.size_id
		join countries on countries.id = sales_order_countries.country_id
		where sales_orders.order_status !=2  $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		order by 
        style_colors.sort_id,
        style_sizes.sort_id");
		$data=collect($results)
		->map(function($data) use($itemcomplexity){
			$data->qty=number_format($data->qty,0);
			$data->item_complexity=$itemcomplexity[$data->item_complexity];
			$data->rate=number_format($data->rate,2);
			$data->amount=number_format($data->amount,2);
			$data->smv=number_format($data->smv,2);
			$data->booked_minute=number_format($data->booked_minute,2);
			return $data;

		});
		echo json_encode($data);

	}

	public function getDyedYarnRq(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		$autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
        return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
        return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
        'autoyarns.*',
        'constructions.name',
        'compositions.name as composition_name',
        'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
        $fabricDescriptionArr[$row->id]=$row->name;
        $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$fabricDescriptionArr[$key]." ".implode(",",$fabricCompositionArr[$key]);
        }

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');

		$results = \DB::select("
		select
		buyers.name as buyer_name, 
		styles.style_ref,
		sales_orders.id as sales_order_id,
		sales_orders.sale_order_no,
		style_fabrications.autoyarn_id,
		style_fabrications.fabric_look_id,
		item_accounts.item_description,
		gmt_colors.name as gmt_color_name,
		yarn_colors.name as yarn_color_name,
		style_fabrication_stripes.measurment,
		style_fabrication_stripes.feeder,
		sum(budget_yarn_dyeing_cons.bom_qty) as dyed_yarn_rq
		from sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id
		join style_fabrication_stripes on style_fabrication_stripes.id = budget_yarn_dyeing_cons.style_fabrication_stripe_id
		join style_colors on style_colors.id = style_fabrication_stripes.style_color_id
		join colors  gmt_colors on gmt_colors.id = style_colors.color_id
		join colors  yarn_colors on yarn_colors.id = style_fabrication_stripes.color_id
		join style_fabrications on style_fabrications.id = style_fabrication_stripes.style_fabrication_id
		join style_gmts on style_gmts.id = style_fabrications.style_gmt_id
		join item_accounts on item_accounts.id = style_gmts.item_account_id
		join buyers on buyers.id = styles.buyer_id
		where sales_orders.order_status !=2  $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		group by 
		buyers.name,
		styles.style_ref,
		sales_orders.id,
		sales_orders.sale_order_no,
		style_fabrications.autoyarn_id,
		style_fabrications.fabric_look_id,
		item_accounts.item_description,
		budget_yarn_dyeing_cons.style_fabrication_stripe_id,
		budget_yarn_dyeing_cons.yarn_color_id,
		gmt_colors.name,
		yarn_colors.name,
		style_fabrication_stripes.measurment,
		style_fabrication_stripes.feeder");
		$data=collect($results)
		->map(function($data) use($desDropdown,$fabriclooks){
			$data->fabric_des=$desDropdown[$data->autoyarn_id];
			$data->fabriclooks=$fabriclooks[$data->fabric_look_id];
			return $data;
		});
		echo json_encode($data);
	}

	public function getGreyYarnToDye()
	{
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid

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


		$results = \DB::select("
			select 
			inv_isus.issue_no,
			inv_isus.issue_date,
			yarncounts.count,
			yarncounts.symbol,
			inv_yarn_items.item_account_id,
			yarntypes.name as yarn_type,
			colors.name as yarn_color_name,
			issue_to.name as issue_to_name,
			sales_orders.id as sales_order_id,
			sales_orders.sale_order_no,
			suppliers.name as supplier_name,
			inv_yarn_items.lot,
			inv_yarn_items.brand,
			sum(budget_yarn_dyeing_cons.bom_qty) as dyed_yarn_rq,
			sum(inv_yarn_isu_items.qty) as grey_yarn_issue_qty_for_dye,
			avg(inv_yarn_isu_items.rate) as grey_yarn_issue_rate_for_dye,
			sum(inv_yarn_isu_items.amount) as grey_yarn_issue_amount_for_dye
			FROM sales_orders 
			join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
			join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id = budget_yarn_dyeing_cons.id
			join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
			join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
			join inv_yarn_items on inv_yarn_items.id = inv_yarn_isu_items.inv_yarn_item_id
			join item_accounts on item_accounts.id = inv_yarn_items.item_account_id
			join suppliers on suppliers.id = inv_yarn_items.supplier_id
			join suppliers issue_to on issue_to.id = inv_isus.supplier_id
			join colors on colors.id = inv_yarn_items.color_id
			join yarncounts on yarncounts.id = item_accounts.yarncount_id
			join yarntypes on yarntypes.id = item_accounts.yarntype_id
			join itemclasses on itemclasses.id = item_accounts.itemclass_id
			join itemcategories on itemcategories.id = item_accounts.itemcategory_id
			join jobs on jobs.id=sales_orders.job_id
			join styles on styles.id=jobs.style_id
			where 
			sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid and
			inv_isus.isu_against_id=9 and 
			inv_yarn_isu_items.deleted_at is null 
			group by 
			sales_orders.id,
			sales_orders.sale_order_no,
			inv_isus.id,
			inv_yarn_items.id,
			suppliers.name,
			inv_isus.issue_no,
			inv_isus.issue_date,
			issue_to.name,
			inv_yarn_items.item_account_id,
			inv_yarn_items.lot,
			inv_yarn_items.brand,
			colors.name,
			yarncounts.count,
			yarncounts.symbol,
			yarntypes.name");
		$data=collect($results)
		->map(function($data) use($yarnDropdown){
			$data->issue_date=date('d-M-Y',strtotime($data->issue_date));
			$data->count_name=$data->count."/".$data->symbol;
            $data->composition=isset($yarnDropdown[$data->item_account_id])?$yarnDropdown[$data->item_account_id]:'';
            return $data;

		});

		$results = \DB::select("
			select 
			inv_yarn_rcv_items.sales_order_id,
			inv_rcvs.receive_no,
			inv_rcvs.receive_date,
			inv_yarn_items.item_account_id,
			inv_yarn_items.lot,
			inv_yarn_items.brand,
			colors.name as yarn_color_name,
			yarncounts.count,
			yarncounts.symbol,
			yarntypes.name as yarn_type,
			sum(inv_yarn_transactions.store_qty) as qty,
			avg(inv_yarn_transactions.store_rate) as rate,
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
			join inv_yarn_items on inv_yarn_items.id = inv_yarn_rcv_items.inv_yarn_item_id
			join item_accounts on item_accounts.id = inv_yarn_items.item_account_id
			--join suppliers on suppliers.id = inv_yarn_items.supplier_id

			join colors on colors.id = inv_yarn_items.color_id
			join yarncounts on yarncounts.id = item_accounts.yarncount_id
			join yarntypes on yarntypes.id = item_accounts.yarntype_id
			join itemclasses on itemclasses.id = item_accounts.itemclass_id
			join itemcategories on itemcategories.id = item_accounts.itemcategory_id
			where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
			and inv_rcvs.receive_basis_id=4
			and inv_rcvs.receive_against_id=9
			and inv_yarn_transactions.deleted_at is null
			and inv_yarn_rcv_items.deleted_at is null
			and inv_rcvs.deleted_at is null
			and inv_yarn_transactions.trans_type_id=1   
			group by 
			inv_yarn_rcv_items.sales_order_id,
			inv_rcvs.receive_no,
			inv_rcvs.receive_date,
			inv_yarn_items.item_account_id,
			inv_yarn_items.lot,
			inv_yarn_items.brand,
			colors.name,
			yarncounts.count,
			yarncounts.symbol,
			yarntypes.name");
		$return=collect($results)
		->map(function($return) use($yarnDropdown){
		$return->receive_date=date('d-M-Y',strtotime($return->receive_date));
		$return->count_name=$return->count."/".$return->symbol;
		$return->composition=isset($yarnDropdown[$return->item_account_id])?$yarnDropdown[$return->item_account_id]:'';
		return $return;

		});


		$results = \DB::select("
		select 
		sales_orders.id as sales_order_id,
		sales_orders.sale_order_no,
		suppliers.name as supplier_name,
		inv_yarn_items.item_account_id,
		inv_yarn_items.lot,
		inv_yarn_items.brand,
		colors.name as yarn_color_name,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name as yarn_type,
		issue_to.name as issue_to_name,
		inv_rcvs.receive_no,
		inv_rcvs.receive_date,
		sum(inv_yarn_rcv_items.qty) as dyed_yarn_rcv_qty,
		avg(inv_yarn_rcv_items.rate) as dyed_yarn_rcv_rate,
		sum(inv_yarn_rcv_items.amount) as dyed_yarn_rcv_amount
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id = budget_yarn_dyeing_cons.id
		join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
		join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
		join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_isu_item_id = inv_yarn_isu_items.id
		join inv_yarn_rcvs on inv_yarn_rcvs.id = inv_yarn_rcv_items.inv_yarn_rcv_id
		join inv_rcvs on inv_rcvs.id = inv_yarn_rcvs.inv_rcv_id
		join inv_yarn_items on inv_yarn_items.id = inv_yarn_isu_items.inv_yarn_item_id
		join item_accounts on item_accounts.id = inv_yarn_items.item_account_id
		join suppliers on suppliers.id = inv_yarn_items.supplier_id
		join suppliers issue_to on issue_to.id = inv_isus.supplier_id
		join colors on colors.id = inv_yarn_items.color_id
		join yarncounts on yarncounts.id = item_accounts.yarncount_id
		join yarntypes on yarntypes.id = item_accounts.yarntype_id
		join itemclasses on itemclasses.id = item_accounts.itemclass_id
		join itemcategories on itemcategories.id = item_accounts.itemcategory_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where  sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid and  inv_isus.isu_against_id=9 and inv_yarn_isu_items.deleted_at is null and inv_rcvs.receive_against_id=9 and inv_yarn_rcv_items.deleted_at is null 
		group by 
		sales_orders.id,
		sales_orders.sale_order_no,
		inv_yarn_items.id,
		suppliers.name,
		inv_yarn_items.item_account_id,
		inv_yarn_items.lot,
		inv_yarn_items.brand,
		colors.name,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name,
		issue_to.name,
		inv_rcvs.receive_no,
		inv_rcvs.receive_date");
		$receive=collect($results)
		->map(function($receive) use($yarnDropdown){
		$receive->receive_date=date('d-M-Y',strtotime($receive->receive_date));
		$receive->count_name=$receive->count."/".$receive->symbol;
		$receive->composition=isset($yarnDropdown[$receive->item_account_id])?$yarnDropdown[$receive->item_account_id]:'';
		return $receive;

		});
	   return Template::loadView('Report.OrderProgressDyedYarnDtl',['issue'=>$data,'return'=>$return,'receive'=>$receive]);

		//echo json_encode(['issue'=>$data,'return'=>'','receive'=>'']);
	}

	public function getDyedYarnRcv()
	{

		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid

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


		$results = \DB::select("
		select 
		sales_orders.id as sales_order_id,
		sales_orders.sale_order_no,
		suppliers.name as supplier_name,
		inv_yarn_items.item_account_id,
		inv_yarn_items.lot,
		inv_yarn_items.brand,
		colors.name as yarn_color_name,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name as yarn_type,
		issue_to.name as issue_to_name,
		inv_rcvs.receive_no,
		inv_rcvs.receive_date,
		sum(inv_yarn_rcv_items.qty) as dyed_yarn_rcv_qty,
		avg(inv_yarn_rcv_items.rate) as dyed_yarn_rcv_rate,
		sum(inv_yarn_rcv_items.amount) as dyed_yarn_rcv_amount
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id = budget_yarn_dyeing_cons.id
		join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
		join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
		join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_isu_item_id = inv_yarn_isu_items.id
		join inv_yarn_rcvs on inv_yarn_rcvs.id = inv_yarn_rcv_items.inv_yarn_rcv_id
		join inv_rcvs on inv_rcvs.id = inv_yarn_rcvs.inv_rcv_id
		join inv_yarn_items on inv_yarn_items.id = inv_yarn_isu_items.inv_yarn_item_id
		join item_accounts on item_accounts.id = inv_yarn_items.item_account_id
		join suppliers on suppliers.id = inv_yarn_items.supplier_id
		join suppliers issue_to on issue_to.id = inv_isus.supplier_id
		join colors on colors.id = inv_yarn_items.color_id
		join yarncounts on yarncounts.id = item_accounts.yarncount_id
		join yarntypes on yarntypes.id = item_accounts.yarntype_id
		join itemclasses on itemclasses.id = item_accounts.itemclass_id
		join itemcategories on itemcategories.id = item_accounts.itemcategory_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where  sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid and  inv_isus.isu_against_id=9 and inv_yarn_isu_items.deleted_at is null and inv_rcvs.receive_against_id=9 and inv_yarn_rcv_items.deleted_at is null 
		group by 
		sales_orders.id,
		sales_orders.sale_order_no,
		inv_yarn_items.id,
		suppliers.name,
		inv_yarn_items.item_account_id,
		inv_yarn_items.lot,
		inv_yarn_items.brand,
		colors.name,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name,
		issue_to.name,
		inv_rcvs.receive_no,
		inv_rcvs.receive_date");
		$data=collect($results)
		->map(function($data) use($yarnDropdown){
		$data->receive_date=date('d-M-Y',strtotime($data->receive_date));
		$data->count_name=$data->count."/".$data->symbol;
		$data->composition=isset($yarnDropdown[$data->item_account_id])?$yarnDropdown[$data->item_account_id]:'';
		return $data;

		});
		echo json_encode($data);
	}

	public function getYarnRq()
	{
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
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


		$results = \DB::select("
		select
		n.sales_order_id, 
		n.item_account_id,
		n.count,
		n.symbol,
		n.yarn_type,
		
		sum(n.yarn_req) as yarn_req,
		avg(n.rate) as rate,
		sum(n.req_amount) as req_amount,

		sum(n.rcv_qty) as rcv_qty,
		avg(n.rcv_rate) as rcv_rate,
		sum(n.rcv_amount) as rcv_amount 
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
		m.req_amount,

		yarnrcv.rcv_qty,
		yarnrcv.rcv_rate,
		yarnrcv.rcv_amount
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
		where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
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
		left join (
		select 
		po_yarn_item_bom_qties.sale_order_id,
		po_yarn_item_bom_qties.budget_yarn_id,
		sum(inv_yarn_rcv_item_sos.qty) rcv_qty,
		avg(inv_yarn_rcv_items.rate) as rcv_rate,
		sum(inv_yarn_rcv_item_sos.qty*inv_yarn_rcv_items.rate) rcv_amount
		from
		po_yarn_item_bom_qties
		join inv_yarn_rcv_item_sos on inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id=po_yarn_item_bom_qties.id
		join inv_yarn_rcv_items on inv_yarn_rcv_items.id=inv_yarn_rcv_item_sos.inv_yarn_rcv_item_id
		where po_yarn_item_bom_qties.deleted_at is null and  inv_yarn_rcv_item_sos.deleted_at is null
		group by 
		po_yarn_item_bom_qties.budget_yarn_id,
		po_yarn_item_bom_qties.sale_order_id
		) yarnrcv on yarnrcv.budget_yarn_id=m.id and yarnrcv.sale_order_id=m.sales_order_id) n 
		group by 
		n.sales_order_id, 
		n.item_account_id,
		n.count,
		n.symbol,
		n.yarn_type");
		$data=collect($results)
		->map(function($data) use($yarnDropdown){
		$data->count_name=$data->count."/".$data->symbol;
		$data->composition=isset($yarnDropdown[$data->item_account_id])?$yarnDropdown[$data->item_account_id]:'';
		$data->pending_qty=number_format($data->rcv_qty-$data->yarn_req,2);
		$data->pending_amount=number_format($data->rcv_amount-$data->req_amount,2);
		$data->yarn_req=number_format($data->yarn_req,2);
		$data->rate=number_format($data->rate,2);
		$data->req_amount=number_format($data->req_amount,2);
		$data->rcv_qty=number_format($data->rcv_qty,2);
		$data->rcv_rate=number_format($data->rcv_rate,2);
		$data->rcv_amount=number_format($data->rcv_amount,2);
		return $data;

		});
		echo json_encode($data);
	}

	public function getYarnIsuInh()
	{

		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid

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


		$results = \DB::select("
				select 
		sales_orders.id as sales_order_id,
		sales_orders.sale_order_no,
		inv_isus.issue_no,
		inv_isus.issue_date,
		suppliers.name as supplier_name,
		inv_yarn_items.item_account_id,
		inv_yarn_items.lot,
		inv_yarn_items.brand,
		colors.name as yarn_color_name,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name as yarn_type,
		issue_to.name as issue_to_name,
		sum(inv_yarn_isu_items.qty) as qty,
		avg(inv_yarn_isu_items.rate) as rate,
		sum(inv_yarn_isu_items.amount) as amount
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
		join inv_yarn_items on inv_yarn_items.id = inv_yarn_isu_items.inv_yarn_item_id
		join item_accounts on item_accounts.id = inv_yarn_items.item_account_id
		join suppliers on suppliers.id = inv_yarn_items.supplier_id
		join suppliers issue_to on issue_to.id = inv_isus.supplier_id
		join companies on companies.id = issue_to.company_id
		join colors on colors.id = inv_yarn_items.color_id
		join yarncounts on yarncounts.id = item_accounts.yarncount_id
		join yarntypes on yarntypes.id = item_accounts.yarntype_id
		join itemclasses on itemclasses.id = item_accounts.itemclass_id
		join itemcategories on itemcategories.id = item_accounts.itemcategory_id
		join jobs on jobs.id = sales_orders.job_id 
        join styles on styles.id = jobs.style_id 
		where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid and   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null  
		group by 
		sales_orders.id,
		sales_orders.sale_order_no,
		inv_isus.issue_no,
		inv_isus.issue_date,
		suppliers.name,
		inv_yarn_items.item_account_id,
		inv_yarn_items.lot,
		inv_yarn_items.brand,
		colors.name,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name,
		issue_to.name");
		$inisu=collect($results)
		->map(function($inisu) use($yarnDropdown){
			$inisu->issue_date=date('d-M-Y',strtotime($inisu->issue_date));
			$inisu->count_name=$inisu->count."/".$inisu->symbol;
			$inisu->composition=isset($yarnDropdown[$inisu->item_account_id])?$yarnDropdown[$inisu->item_account_id]:'';
			
			return $inisu;
		});

		$results = \DB::select("
			select 
			inv_yarn_rcv_items.sales_order_id,
			inv_rcvs.receive_no,
			inv_rcvs.receive_date,
			inv_yarn_items.item_account_id,
			inv_yarn_items.lot,
			inv_yarn_items.brand,
			colors.name as yarn_color_name,
			yarncounts.count,
			yarncounts.symbol,
			yarntypes.name as yarn_type,
			suppliers.name as kniting_company,
			sum(inv_yarn_transactions.store_qty) as qty,
			avg(inv_yarn_transactions.store_rate) as rate,
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
			join inv_yarn_items on inv_yarn_items.id = inv_yarn_rcv_items.inv_yarn_item_id 
			join item_accounts on item_accounts.id = inv_yarn_items.item_account_id
			join colors on colors.id = inv_yarn_items.color_id
			join yarncounts on yarncounts.id = item_accounts.yarncount_id
			join yarntypes on yarntypes.id = item_accounts.yarntype_id
			join itemclasses on itemclasses.id = item_accounts.itemclass_id
			join itemcategories on itemcategories.id = item_accounts.itemcategory_id
			where 
			sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
			and inv_rcvs.receive_basis_id=4
			and inv_yarn_transactions.deleted_at is null
			and inv_yarn_rcv_items.deleted_at is null
			and inv_rcvs.deleted_at is null
			and inv_yarn_transactions.trans_type_id=1 
			
			group by
			inv_rcvs.receive_no,
			inv_rcvs.receive_date,
			inv_yarn_items.item_account_id,
			inv_yarn_items.lot,
			inv_yarn_items.brand,
			colors.name,
			yarncounts.count,
			yarncounts.symbol,
			yarntypes.name,
			suppliers.name, 
			inv_yarn_rcv_items.sales_order_id");
		$inisurtn=collect($results)
		->map(function($inisurtn) use($yarnDropdown){
			$inisurtn->receive_date=date('d-M-Y',strtotime($inisurtn->receive_date));
			$inisurtn->count_name=$inisurtn->count."/".$inisurtn->symbol;
			$inisurtn->composition=isset($yarnDropdown[$inisurtn->item_account_id])?$yarnDropdown[$inisurtn->item_account_id]:'';
			
			return $inisurtn;
		});

		$results = \DB::select("
		select 
		sales_orders.id as sales_order_id,
		sales_orders.sale_order_no,
		inv_isus.issue_no,
		inv_isus.issue_date,
		suppliers.name as supplier_name,
		inv_yarn_items.item_account_id,
		inv_yarn_items.lot,
		inv_yarn_items.brand,
		colors.name as yarn_color_name,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name as yarn_type,
		issue_to.name as issue_to_name,
		sum(inv_yarn_isu_items.qty) as qty,
		avg(inv_yarn_isu_items.rate) as rate,
		sum(inv_yarn_isu_items.amount) as amount
		from sales_orders 
		join po_knit_service_item_qties on po_knit_service_item_qties.sales_order_id = sales_orders.id
		join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
		join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id

		join rq_yarn_fabrications on rq_yarn_fabrications.po_knit_service_item_qty_id = po_knit_service_item_qties.id
		join rq_yarn_items on rq_yarn_items.rq_yarn_fabrication_id = rq_yarn_fabrications.id
		join rq_yarns on rq_yarns.id = rq_yarn_fabrications.rq_yarn_id	
		join inv_yarn_isu_items on inv_yarn_isu_items.rq_yarn_item_id = rq_yarn_items.id
		join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
		join inv_yarn_items on inv_yarn_items.id = inv_yarn_isu_items.inv_yarn_item_id
		join item_accounts on item_accounts.id = inv_yarn_items.item_account_id
		join suppliers on suppliers.id = inv_isus.supplier_id
		join suppliers issue_to on issue_to.id = inv_isus.supplier_id
		and (suppliers.company_id is null or  suppliers.company_id=0)
		join companies on companies.id = inv_isus.company_id
		join colors on colors.id = inv_yarn_items.color_id
		join yarncounts on yarncounts.id = item_accounts.yarncount_id
		join yarntypes on yarntypes.id = item_accounts.yarntype_id
		join itemclasses on itemclasses.id = item_accounts.itemclass_id
		join itemcategories on itemcategories.id = item_accounts.itemcategory_id
		join jobs on jobs.id = sales_orders.job_id 
        join styles on styles.id = jobs.style_id 
		where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid and   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null   
		group by 
		sales_orders.id,
		sales_orders.sale_order_no,
		inv_isus.issue_no,
		inv_isus.issue_date,
		suppliers.name,
		inv_yarn_items.item_account_id,
		inv_yarn_items.lot,
		inv_yarn_items.brand,
		colors.name,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name,
		issue_to.name");
		$inisuout=collect($results)
		->map(function($inisuout) use($yarnDropdown){
		$inisuout->issue_date=date('d-M-Y',strtotime($inisuout->issue_date));

		$inisuout->count_name=$inisuout->count."/".$inisuout->symbol;
		$inisuout->composition=isset($yarnDropdown[$inisuout->item_account_id])?$yarnDropdown[$inisuout->item_account_id]:'';
		return $inisuout;

		});


		$results = \DB::select("
			select 
			inv_yarn_rcv_items.sales_order_id,
			inv_rcvs.receive_no,
			inv_rcvs.receive_date,
			inv_yarn_items.item_account_id,
			inv_yarn_items.lot,
			inv_yarn_items.brand,
			colors.name as yarn_color_name,
			yarncounts.count,
			yarncounts.symbol,
			yarntypes.name as yarn_type,
			suppliers.name as kniting_company,
			sum(inv_yarn_transactions.store_qty) as qty,
			sum(inv_yarn_transactions.store_rate) as rate,
			sum(inv_yarn_transactions.store_amount) as amount
			from 
			sales_orders
			join inv_yarn_rcv_items on inv_yarn_rcv_items.sales_order_id=sales_orders.id
			join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
			join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
			join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
			join suppliers on suppliers.id = inv_rcvs.return_from_id
			and (suppliers.company_id is null or  suppliers.company_id=0)
			--join companies on companies.id = suppliers.company_id
			join companies on companies.id = inv_rcvs.company_id
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			join inv_yarn_items on inv_yarn_items.id = inv_yarn_rcv_items.inv_yarn_item_id 
			join item_accounts on item_accounts.id = inv_yarn_items.item_account_id
			join colors on colors.id = inv_yarn_items.color_id
			join yarncounts on yarncounts.id = item_accounts.yarncount_id
			join yarntypes on yarntypes.id = item_accounts.yarntype_id
			join itemclasses on itemclasses.id = item_accounts.itemclass_id
			join itemcategories on itemcategories.id = item_accounts.itemcategory_id
			where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid and inv_rcvs.receive_basis_id=4
			and inv_yarn_transactions.deleted_at is null
			and inv_yarn_rcv_items.deleted_at is null
			and inv_rcvs.deleted_at is null
			and inv_yarn_transactions.trans_type_id=1
			group by 
			inv_rcvs.receive_no,
			inv_rcvs.receive_date,
			inv_yarn_items.item_account_id,
			inv_yarn_items.lot,
			inv_yarn_items.brand,
			colors.name,
			yarncounts.count,
			yarncounts.symbol,
			yarntypes.name ,
			suppliers.name,
			inv_yarn_rcv_items.sales_order_id");
		$inisuoutrtn=collect($results)
		->map(function($inisuoutrtn) use($yarnDropdown){
			$inisuoutrtn->receive_date=date('d-M-Y',strtotime($inisuoutrtn->receive_date));
			$inisuoutrtn->count_name=$inisuoutrtn->count."/".$inisuoutrtn->symbol;
			$inisuoutrtn->composition=isset($yarnDropdown[$inisuoutrtn->item_account_id])?$yarnDropdown[$inisuoutrtn->item_account_id]:'';
			
			return $inisuoutrtn;
		});

	    return Template::loadView('Report.OrderProgressYarnIssueDtl',['inisu'=>$inisu,'inisurtn'=>$inisurtn,'inisuout'=>$inisuout,'inisuoutrtn'=>$inisuoutrtn]);

		
	}

	public function getYarnIsuOut()
	{
		$data=$this->getYarnIsuInh();
		return $data;

		/*$yarnDescription=$this->itemaccount
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


		$results = \DB::select('
		select 
		sales_orders.id as sales_order_id,
		sales_orders.sale_order_no,
		inv_isus.issue_no,
		inv_isus.issue_date,
		suppliers.name as supplier_name,
		inv_yarn_items.item_account_id,
		inv_yarn_items.lot,
		inv_yarn_items.brand,
		colors.name as yarn_color_name,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name as yarn_type,
		issue_to.name as issue_to_name,
		sum(inv_yarn_isu_items.qty) as out_yarn_isu_qty
		from sales_orders 
		join po_knit_service_item_qties on po_knit_service_item_qties.sales_order_id = sales_orders.id
		join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
		join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id

		join rq_yarn_fabrications on rq_yarn_fabrications.po_knit_service_item_qty_id = po_knit_service_item_qties.id
		join rq_yarn_items on rq_yarn_items.rq_yarn_fabrication_id = rq_yarn_fabrications.id
		join rq_yarns on rq_yarns.id = rq_yarn_fabrications.rq_yarn_id	
		join inv_yarn_isu_items on inv_yarn_isu_items.rq_yarn_item_id = rq_yarn_items.id
		join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
		join inv_yarn_items on inv_yarn_items.id = inv_yarn_isu_items.inv_yarn_item_id
		join item_accounts on item_accounts.id = inv_yarn_items.item_account_id
		join suppliers on suppliers.id = inv_isus.supplier_id
		join suppliers issue_to on issue_to.id = inv_isus.supplier_id
		and (suppliers.company_id is null or  suppliers.company_id=0)
		join companies on companies.id = inv_isus.company_id
		join colors on colors.id = inv_yarn_items.color_id
		join yarncounts on yarncounts.id = item_accounts.yarncount_id
		join yarntypes on yarntypes.id = item_accounts.yarntype_id
		join itemclasses on itemclasses.id = item_accounts.itemclass_id
		join itemcategories on itemcategories.id = item_accounts.itemcategory_id
		where sales_orders.id=? and   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null   
		group by 
		sales_orders.id,
		sales_orders.sale_order_no,
		inv_isus.issue_no,
		inv_isus.issue_date,
		suppliers.name,
		inv_yarn_items.item_account_id,
		inv_yarn_items.lot,
		inv_yarn_items.brand,
		colors.name,
		yarncounts.count,
		yarncounts.symbol,
		yarntypes.name,
		issue_to.name', [request('sale_order_id',0)]);
		$data=collect($results)
		->map(function($data) use($yarnDropdown){
		$data->count_name=$data->count."/".$data->symbol;
		$data->composition=isset($yarnDropdown[$data->item_account_id])?$yarnDropdown[$data->item_account_id]:'';
		return $data;

		});
		echo json_encode($data);*/
	}

	public function getKnit(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid

		$autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
        return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
        return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
        'autoyarns.*',
        'constructions.name',
        'compositions.name as composition_name',
        'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
        $fabricDescriptionArr[$row->id]=$row->name;
        $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
        }

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape=array_prepend(config('bprs.fabricshape'),'-Select-','');



		$results = \DB::select("
		select
		sales_orders.id,
		style_fabrications.gmtspart_id,
		gmtsparts.name as gmts_part_name,
		style_fabrications.autoyarn_id,
		budget_fabrics.gsm_weight,
		budget_fabric_cons.dia,
		budget_fabric_cons.fabric_color,
		colors.name as fabric_color_name,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		sum(budget_fabric_cons.grey_fab) as req_qty,
		prods.reject_qty,   
		prods.qc_pass_qty,
		prods.qc_pass_qty_pcs,
		prods.reject_qty_pcs
		from 
		sales_orders
		join sales_order_countries on  sales_order_countries.sale_order_id=sales_orders.id
		join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
		join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
		join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
		left join colors on colors.id=budget_fabric_cons.fabric_color
		left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		left join(
		select
		m.sales_order_id,
		m.gmtspart_id,
		m.autoyarn_id,
		m.gsm_weight,
		m.dia,
		m.fabric_color_id,
		m.fabric_look_id,
		m.fabric_shape_id,
		sum(m.reject_qty) as reject_qty,   
		sum(m.qc_pass_qty) as qc_pass_qty,
		sum(m.qc_pass_qty_pcs) as qc_pass_qty_pcs,
		sum(m.reject_qty_pcs)  as reject_qty_pcs
		from
		(
		select
		prod_knit_qcs.id,
		prod_knit_items.pl_knit_item_id,
		prod_knit_qcs.reject_qty,   
		prod_knit_qcs.qc_pass_qty,
		prod_knit_qcs.qc_pass_qty_pcs,
		prod_knit_qcs.reject_qty_pcs,
		CASE 
		WHEN  inhprods.po_knit_service_item_qty_id IS NULL THEN outprods.po_knit_service_item_qty_id 
		ELSE inhprods.po_knit_service_item_qty_id
		END as po_knit_service_item_qty_id,
		colorranges.name as colorrange_name,
		style_fabrications.autoyarn_id,
		style_fabrications.gmtspart_id,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		po_knit_service_item_qties.dia,
		po_knit_service_item_qties.fabric_color_id,
		budget_fabrics.gsm_weight,
		sales_orders.id as  sales_order_id
		from
		prod_knits
		join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
		join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
		left join prod_knit_rcv_by_qcs on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
		left join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id

		/*prod_knit_qcs
		join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id=prod_knit_qcs.prod_knit_rcv_by_qc_id
		join prod_knit_item_rolls on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
		join prod_knit_items on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
		join prod_knits on prod_knits.id=prod_knit_items.prod_knit_id*/
		left join (
		select 
		pl_knit_items.id as pl_knit_item_id,
		po_knit_service_item_qties.id as po_knit_service_item_qty_id
		from 
		sales_orders
		join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
		join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
		and po_knit_service_items.deleted_at is null
		join so_knit_po_items on so_knit_po_items.po_knit_service_item_qty_id=po_knit_service_item_qties.id
		join so_knit_refs on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
		join pl_knit_items on pl_knit_items.so_knit_ref_id=so_knit_refs.id
		join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
		) inhprods on inhprods.pl_knit_item_id=prod_knit_items.pl_knit_item_id
		left join (
		select 
		po_knit_service_item_qties.id as po_knit_service_item_qty_id
		from 
		sales_orders
		join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
		join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
		join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
		) outprods on outprods.po_knit_service_item_qty_id=prod_knit_items.po_knit_service_item_qty_id

		left join po_knit_service_item_qties on po_knit_service_item_qties.id=inhprods.po_knit_service_item_qty_id
		or po_knit_service_item_qties.id=outprods.po_knit_service_item_qty_id
		left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
		and po_knit_service_items.deleted_at is null
		left join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
		left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
		left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
		left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
		left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
		left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
		) m  
		group by
		m.sales_order_id,
		m.gmtspart_id,
		m.autoyarn_id,
		m.gsm_weight,
		m.dia,
		m.fabric_color_id,
		m.fabric_look_id,
		m.fabric_shape_id
		) prods on sales_orders.id=prods.sales_order_id
		and prods.gmtspart_id=style_fabrications.gmtspart_id
		and prods.autoyarn_id=style_fabrications.autoyarn_id
		and prods.gsm_weight=budget_fabrics.gsm_weight
		and prods.dia=budget_fabric_cons.dia
		and prods.fabric_color_id=budget_fabric_cons.fabric_color
		and prods.fabric_look_id=style_fabrications.fabric_look_id
		and prods.fabric_shape_id=style_fabrications.fabric_shape_id
		where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		group by
		sales_orders.id,
		style_fabrications.gmtspart_id,
		gmtsparts.name,
		style_fabrications.autoyarn_id,
		budget_fabrics.gsm_weight,
		budget_fabric_cons.dia,
		budget_fabric_cons.fabric_color,
		colors.name,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		prods.reject_qty,   
		prods.qc_pass_qty,
		prods.qc_pass_qty_pcs,
		prods.reject_qty_pcs");
		$data=collect($results)
		->map(function($data) use($fabricDescriptionArr,$desDropdown,$fabriclooks,$fabricshape){
		$data->construction_name=$fabricDescriptionArr[$data->autoyarn_id];
		$data->composition_name=$desDropdown[$data->autoyarn_id];
		$data->fabriclooks=$fabriclooks[$data->fabric_look_id];
		$data->fabricshape=$fabricshape[$data->fabric_shape_id];
		$data->pending_knit=number_format($data->qc_pass_qty-$data->req_qty,2);
		$data->req_qty=number_format($data->req_qty,2);
		$data->qc_pass_qty=number_format($data->qc_pass_qty,2);
		$data->qc_pass_qty_pcs=number_format($data->qc_pass_qty_pcs,2);
		return $data;
		});
		echo json_encode($data);
	}

	public function getCutQty(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');


		$results = \DB::select("
		select
		styles.id as style_id,
		styles.style_ref,
		styles.flie_src,
		styles.buying_agent_id,
		styles.contact,
		buyers.id as buyer_id,
		buyers.name as buyer_name,
		uoms.code as uom_name,
		seasons.name as season_name,
		teams.name as team_name,
		teamleadernames.id as teamleader_id,
		teamleadernames.name as team_name,
		users.id as user_id,
		users.name as team_member_name,
		productdepartments.department_name,
		jobs.job_no,
		companies.code as company_code,
		produced_company.code as produced_company_code,
		sales_orders.id as sale_order_id,
		sales_orders.sale_order_no,
		sales_orders.receive_date as sale_order_receive_date,
		sales_orders.internal_ref,
		sales_orders.ship_date,
		countries.name as country_name,
		item_accounts.item_description,
		colors.name as color_name,
		sizes.name as size_name,
		sales_order_gmt_color_sizes.qty as qty,
		sales_order_gmt_color_sizes.plan_cut_qty as plan_cut_qty,
		sales_order_gmt_color_sizes.rate as rate,
		sales_order_gmt_color_sizes.amount as amount,
		style_gmts.smv,
		style_gmts.item_complexity,
		(style_gmts.smv*sales_order_gmt_color_sizes.qty)  as minute_booked,
		prodcut.cut_qty,
		(sales_order_gmt_color_sizes.plan_cut_qty-prodcut.cut_qty) as cut_pending

		from
		styles
		join buyers on buyers.id=styles.buyer_id
		join uoms on uoms.id=styles.uom_id
		join seasons on seasons.id=styles.season_id
		join teams on teams.id=styles.team_id
		left join teammembers on teammembers.id=styles.factory_merchant_id
		left join users on users.id=teammembers.user_id
		left join productdepartments on productdepartments.id=styles.productdepartment_id
		left join teammembers  teamleaders on teamleaders.id=styles.teammember_id
		left join users teamleadernames on teamleadernames.id=teamleaders.user_id
		left join jobs on jobs.style_id=styles.id
		left join companies on companies.id=jobs.company_id
		left join sales_orders on sales_orders.job_id=jobs.id
		left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
		join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
		join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id
		join item_accounts on item_accounts.id = style_gmts.item_account_id
		join colors on colors.id = style_colors.color_id
		join sizes on sizes.id = style_sizes.size_id
		join countries on countries.id = sales_order_countries.country_id
		left  join(
		SELECT 
		prod_gmt_cutting_qties.sales_order_gmt_color_size_id,
		sum(prod_gmt_cutting_qties.qty) as cut_qty
		FROM prod_gmt_cuttings
		join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
		group by 
		prod_gmt_cutting_qties.sales_order_gmt_color_size_id
		) prodcut on prodcut.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
		where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		order by 
		style_colors.sort_id,
		style_sizes.sort_id");
		$data=collect($results)
		->map(function($data) use($itemcomplexity){
			$data->qty=number_format($data->qty,0);
			$data->item_complexity=$itemcomplexity[$data->item_complexity];
			$data->plan_cut_qty=number_format($data->plan_cut_qty,0);
			$data->cut_qty=number_format($data->cut_qty,0);
			$data->cut_pending=number_format($data->cut_pending,0);
			return $data;
		});
		echo json_encode($data);
	}

	public function getScrQty(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');


		$results = \DB::select("
			select
			styles.id as style_id,
			styles.style_ref,
			styles.flie_src,
			styles.buying_agent_id,
			styles.contact,
			buyers.id as buyer_id,
			buyers.name as buyer_name,
			uoms.code as uom_name,
			seasons.name as season_name,
			teams.name as team_name,
			teamleadernames.id as teamleader_id,
			teamleadernames.name as team_name,
			users.id as user_id,
			users.name as team_member_name,
			productdepartments.department_name,
			jobs.job_no,
			companies.code as company_code,
			produced_company.code as produced_company_code,
			sales_orders.id as sale_order_id,
			sales_orders.sale_order_no,
			sales_orders.receive_date as sale_order_receive_date,
			sales_orders.internal_ref,
			sales_orders.ship_date,
			countries.name as country_name,
			item_accounts.item_description,
			colors.name as color_name,
			sizes.name as size_name,
			sales_order_gmt_color_sizes.qty as qty,
			sales_order_gmt_color_sizes.plan_cut_qty as plan_cut_qty,
			sales_order_gmt_color_sizes.rate as rate,
			sales_order_gmt_color_sizes.amount as amount,
			style_gmts.smv,
			style_gmts.item_complexity,
			(style_gmts.smv*sales_order_gmt_color_sizes.qty)  as minute_booked,
			prodcut.cut_qty,
			(sales_order_gmt_color_sizes.plan_cut_qty-prodcut.cut_qty) as cut_pending,
			prodscrreq.req_scr_qty,
			prodscrdlv.snd_scr_qty,
			prodscrrcv.rcv_scr_qty,
			(prodscrrcv.rcv_scr_qty-prodscrdlv.snd_scr_qty) as scr_pending

			from
			styles
			join buyers on buyers.id=styles.buyer_id
			join uoms on uoms.id=styles.uom_id
			join seasons on seasons.id=styles.season_id
			join teams on teams.id=styles.team_id
			left join teammembers on teammembers.id=styles.factory_merchant_id
			left join users on users.id=teammembers.user_id
			left join productdepartments on productdepartments.id=styles.productdepartment_id
			left join teammembers  teamleaders on teamleaders.id=styles.teammember_id
			left join users teamleadernames on teamleadernames.id=teamleaders.user_id
			left join jobs on jobs.style_id=styles.id
			left join companies on companies.id=jobs.company_id
			left join sales_orders on sales_orders.job_id=jobs.id
			left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
			join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id
			join item_accounts on item_accounts.id = style_gmts.item_account_id
			join colors on colors.id = style_colors.color_id
			join sizes on sizes.id = style_sizes.size_id
			join countries on countries.id = sales_order_countries.country_id
			left  join(
			SELECT 
			prod_gmt_cutting_qties.sales_order_gmt_color_size_id,
			sum(prod_gmt_cutting_qties.qty) as cut_qty
			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
			where prod_gmt_cutting_qties.deleted_at is null
			group by 
			prod_gmt_cutting_qties.sales_order_gmt_color_size_id
			) prodcut on prodcut.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			left  join(
			select 
			sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
			sum(budget_emb_cons.req_cons) as req_scr_qty
			from budget_emb_cons 
			left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
			left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
			left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
			left join embelishments on embelishments.id=style_embelishments.embelishment_id
			left join production_processes on production_processes.id=embelishments.production_process_id
			where production_processes.production_area_id =45
			group by sales_order_gmt_color_sizes.id
			) prodscrreq on prodscrreq.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			left  join(
			SELECT 
			prod_gmt_dlv_print_qties.sales_order_gmt_color_size_id,
			sum(prod_gmt_dlv_print_qties.qty) as snd_scr_qty
			FROM prod_gmt_dlv_prints
			join prod_gmt_dlv_print_orders on prod_gmt_dlv_print_orders.prod_gmt_dlv_print_id = prod_gmt_dlv_prints.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_dlv_print_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_dlv_print_qties on prod_gmt_dlv_print_qties.prod_gmt_dlv_print_order_id = prod_gmt_dlv_print_orders.id
			where prod_gmt_dlv_print_qties.deleted_at is null
			group by 
			prod_gmt_dlv_print_qties.sales_order_gmt_color_size_id
			) prodscrdlv on prodscrdlv.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			left  join(
			SELECT 
			prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id,
			sum(prod_gmt_print_rcv_qties.qty) as rcv_scr_qty
			FROM prod_gmt_print_rcvs
			join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id
			where prod_gmt_print_rcv_qties.deleted_at is null
			group by 
			prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id
			) prodscrrcv on prodscrrcv.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
			order by 
			style_colors.sort_id,
			style_sizes.sort_id");
		$data=collect($results)
		->map(function($data) use($itemcomplexity){
			$data->qty=number_format($data->qty,0);
			$data->item_complexity=$itemcomplexity[$data->item_complexity];
			$data->plan_cut_qty=number_format($data->plan_cut_qty,0);
			$data->cut_qty=number_format($data->cut_qty,0);
			$data->cut_pending=number_format($data->cut_pending,0);
			$data->req_scr_qty=number_format($data->req_scr_qty,0);
			$data->snd_scr_qty=number_format($data->snd_scr_qty,0);
			$data->rcv_scr_qty=number_format($data->rcv_scr_qty,0);
			$data->scr_pending=number_format($data->scr_pending,0);
			return $data;
		});
		echo json_encode($data);
	}

	public function getSewQty(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');


		$results = \DB::select("
			select
			styles.id as style_id,
			styles.style_ref,
			styles.flie_src,
			styles.buying_agent_id,
			styles.contact,
			buyers.id as buyer_id,
			buyers.name as buyer_name,
			uoms.code as uom_name,
			seasons.name as season_name,
			teams.name as team_name,
			teamleadernames.id as teamleader_id,
			teamleadernames.name as team_name,
			users.id as user_id,
			users.name as team_member_name,
			productdepartments.department_name,
			jobs.job_no,
			companies.code as company_code,
			produced_company.code as produced_company_code,
			sales_orders.id as sale_order_id,
			sales_orders.sale_order_no,
			sales_orders.receive_date as sale_order_receive_date,
			sales_orders.internal_ref,
			sales_orders.ship_date,
			countries.name as country_name,
			item_accounts.item_description,
			colors.name as color_name,
			sizes.name as size_name,
			sales_order_gmt_color_sizes.qty as qty,
			sales_order_gmt_color_sizes.plan_cut_qty as plan_cut_qty,
			sales_order_gmt_color_sizes.rate as rate,
			sales_order_gmt_color_sizes.amount as amount,
			style_gmts.smv,
			style_gmts.item_complexity,
			(style_gmts.smv*sales_order_gmt_color_sizes.qty)  as minute_booked,
			prodsewline.sew_line_qty,
			prodsew.sew_qty,
			(prodsew.sew_qty-prodsewline.sew_line_qty) as sewwip,
			(prodsew.sew_qty-sales_order_gmt_color_sizes.qty) as sew_pending


			from
			styles
			join buyers on buyers.id=styles.buyer_id
			join uoms on uoms.id=styles.uom_id
			join seasons on seasons.id=styles.season_id
			join teams on teams.id=styles.team_id
			left join teammembers on teammembers.id=styles.factory_merchant_id
			left join users on users.id=teammembers.user_id
			left join productdepartments on productdepartments.id=styles.productdepartment_id
			left join teammembers  teamleaders on teamleaders.id=styles.teammember_id
			left join users teamleadernames on teamleadernames.id=teamleaders.user_id
			left join jobs on jobs.style_id=styles.id
			left join companies on companies.id=jobs.company_id
			left join sales_orders on sales_orders.job_id=jobs.id
			left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
			join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id
			join item_accounts on item_accounts.id = style_gmts.item_account_id
			join colors on colors.id = style_colors.color_id
			join sizes on sizes.id = style_sizes.size_id
			join countries on countries.id = sales_order_countries.country_id
			left  join(
			SELECT 
			prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id,
			sum(prod_gmt_sewing_line_qties.qty) as sew_line_qty
			FROM prod_gmt_sewing_lines
			join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id = prod_gmt_sewing_lines.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_line_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_sewing_line_qties on prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id = prod_gmt_sewing_line_orders.id
			group by 
			prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id
			) prodsewline on prodsewline.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			left  join(
			SELECT 
			prod_gmt_sewing_qties.sales_order_gmt_color_size_id,
			sum(prod_gmt_sewing_qties.qty) as sew_qty
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id
			group by 
			prod_gmt_sewing_qties.sales_order_gmt_color_size_id
			) prodsew on prodsew.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
			order by 
			style_colors.sort_id,
			style_sizes.sort_id");
		$data=collect($results)
		->map(function($data) use($itemcomplexity){
			
			$data->item_complexity=$itemcomplexity[$data->item_complexity];
			//$data->plan_cut_qty=number_format($data->plan_cut_qty,0);
			//$data->cut_qty=number_format($data->cut_qty,0);
			//$data->cut_pending=number_format($data->cut_pending,0);
			$data->sewwip=number_format($data->sew_qty-$data->sew_line_qty,0);
			$data->sew_pending=number_format($data->sew_qty-$data->qty,0);
			$data->qty=number_format($data->qty,0);
			$data->sew_line_qty=number_format($data->sew_line_qty,0);
			$data->sew_qty=number_format($data->sew_qty,0);
			
			return $data;
		});
		echo json_encode($data);
	}

	public function getCarQty(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');


		$results = \DB::select("
		select
		styles.id as style_id,
		styles.style_ref,
		styles.flie_src,
		styles.buying_agent_id,
		styles.contact,
		buyers.id as buyer_id,
		buyers.name as buyer_name,
		uoms.code as uom_name,
		seasons.name as season_name,
		teams.name as team_name,
		teamleadernames.id as teamleader_id,
		teamleadernames.name as team_name,
		users.id as user_id,
		users.name as team_member_name,
		productdepartments.department_name,
		jobs.job_no,
		companies.code as company_code,
		produced_company.code as produced_company_code,
		sales_orders.id as sale_order_id,
		sales_orders.sale_order_no,
		sales_orders.receive_date as sale_order_receive_date,
		sales_orders.internal_ref,
		sales_orders.ship_date,
		countries.name as country_name,
		item_accounts.item_description,
		colors.name as color_name,
		sizes.name as size_name,
		sales_order_gmt_color_sizes.qty as qty,
		sales_order_gmt_color_sizes.plan_cut_qty as plan_cut_qty,
		sales_order_gmt_color_sizes.rate as rate,
		sales_order_gmt_color_sizes.amount as amount,
		style_gmts.smv,
		style_gmts.item_complexity,
		(style_gmts.smv*sales_order_gmt_color_sizes.qty)  as minute_booked,
		prodsew.sew_qty,
		carton.car_qty


		from
		styles
		join buyers on buyers.id=styles.buyer_id
		join uoms on uoms.id=styles.uom_id
		join seasons on seasons.id=styles.season_id
		join teams on teams.id=styles.team_id
		left join teammembers on teammembers.id=styles.factory_merchant_id
		left join users on users.id=teammembers.user_id
		left join productdepartments on productdepartments.id=styles.productdepartment_id
		left join teammembers  teamleaders on teamleaders.id=styles.teammember_id
		left join users teamleadernames on teamleadernames.id=teamleaders.user_id
		left join jobs on jobs.style_id=styles.id
		left join companies on companies.id=jobs.company_id
		left join sales_orders on sales_orders.job_id=jobs.id
		left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
		join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
		join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id
		join item_accounts on item_accounts.id = style_gmts.item_account_id
		join colors on colors.id = style_colors.color_id
		join sizes on sizes.id = style_sizes.size_id
		join countries on countries.id = sales_order_countries.country_id

		left  join(
		SELECT 
		prod_gmt_sewing_qties.sales_order_gmt_color_size_id,
		sum(prod_gmt_sewing_qties.qty) as sew_qty
		FROM prod_gmt_sewings
		join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id
		group by 
		prod_gmt_sewing_qties.sales_order_gmt_color_size_id
		) prodsew on prodsew.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
		left join (
		SELECT 
		sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
		sum(style_pkg_ratios.qty) as car_qty 
		FROM prod_gmt_carton_entries
		join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
		join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
		join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		and sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
		group by sales_order_gmt_color_sizes.id
		) carton on carton.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

		where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		order by 
		style_colors.sort_id,
		style_sizes.sort_id");
		$data=collect($results)
		->map(function($data) use($itemcomplexity){
			$data->item_complexity=$data->item_complexity?$itemcomplexity[$data->item_complexity]:'';
			//$data->plan_cut_qty=number_format($data->plan_cut_qty,0);
			//$data->cut_qty=number_format($data->cut_qty,0);
			//$data->cut_pending=number_format($data->cut_pending,0);
			$data->carwip=number_format($data->car_qty-$data->sew_qty,0);
			$data->sew_qty=number_format($data->sew_qty,0);
			$data->car_pending=number_format($data->car_qty-$data->qty,0);
			$data->qty=number_format($data->qty,0);
			$data->car_qty=number_format($data->car_qty,0);
			return $data;
		});
		echo json_encode($data);
	}

	public function getInspQty(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');


		$results = \DB::select("
		select
		styles.id as style_id,
		styles.style_ref,
		styles.flie_src,
		styles.buying_agent_id,
		styles.contact,
		buyers.id as buyer_id,
		buyers.name as buyer_name,
		uoms.code as uom_name,
		seasons.name as season_name,
		teams.name as team_name,
		teamleadernames.id as teamleader_id,
		teamleadernames.name as team_name,
		users.id as user_id,
		users.name as team_member_name,
		productdepartments.department_name,
		jobs.job_no,
		companies.code as company_code,
		produced_company.code as produced_company_code,
		sales_orders.id as sale_order_id,
		sales_orders.sale_order_no,
		sales_orders.receive_date as sale_order_receive_date,
		sales_orders.internal_ref,
		sales_orders.ship_date,
		countries.name as country_name,
		item_accounts.item_description,
		colors.name as color_name,
		sizes.name as size_name,
		sales_order_gmt_color_sizes.qty as qty,
		sales_order_gmt_color_sizes.plan_cut_qty as plan_cut_qty,
		sales_order_gmt_color_sizes.rate as rate,
		sales_order_gmt_color_sizes.amount as amount,
		style_gmts.smv,
		style_gmts.item_complexity,
		(style_gmts.smv*sales_order_gmt_color_sizes.qty)  as minute_booked,
		carton.car_qty,
		inspec.insp_pass_qty,
		inspec.insp_re_check_qty,
		inspec.insp_faild_qty,
		inspec.re_check_remarks,
		inspec.failed_remarks


		from
		styles
		join buyers on buyers.id=styles.buyer_id
		join uoms on uoms.id=styles.uom_id
		join seasons on seasons.id=styles.season_id
		join teams on teams.id=styles.team_id
		left join teammembers on teammembers.id=styles.factory_merchant_id
		left join users on users.id=teammembers.user_id
		left join productdepartments on productdepartments.id=styles.productdepartment_id
		left join teammembers  teamleaders on teamleaders.id=styles.teammember_id
		left join users teamleadernames on teamleadernames.id=teamleaders.user_id
		left join jobs on jobs.style_id=styles.id
		left join companies on companies.id=jobs.company_id
		left join sales_orders on sales_orders.job_id=jobs.id
		left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
		join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
		join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id
		join item_accounts on item_accounts.id = style_gmts.item_account_id
		join colors on colors.id = style_colors.color_id
		join sizes on sizes.id = style_sizes.size_id
		join countries on countries.id = sales_order_countries.country_id

		left  join(
		select 
		prod_gmt_inspection_orders.sales_order_gmt_color_size_id,
		sum(prod_gmt_inspection_orders.qty) as insp_pass_qty,
		sum(prod_gmt_inspection_orders.re_check_qty) as insp_re_check_qty,
		sum(prod_gmt_inspection_orders.failed_qty) as insp_faild_qty,
		min(prod_gmt_inspection_orders.re_check_remarks) as re_check_remarks,
		min(prod_gmt_inspection_orders.failed_remarks) as failed_remarks
		from
		prod_gmt_inspections
		join prod_gmt_inspection_orders on prod_gmt_inspection_orders.prod_gmt_inspection_id=prod_gmt_inspections.id
		join sales_order_countries on sales_order_countries.id=prod_gmt_inspections.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by
		prod_gmt_inspection_orders.sales_order_gmt_color_size_id
		) inspec on inspec.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

		left join (
		SELECT 
		sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
		sum(style_pkg_ratios.qty) as car_qty 
		FROM prod_gmt_carton_entries
		join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
		join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
		join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		and sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
		group by sales_order_gmt_color_sizes.id
		) carton on carton.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
		where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		order by 
		style_colors.sort_id,
		style_sizes.sort_id");
		$data=collect($results)
		->map(function($data) use($itemcomplexity){
			$data->item_complexity=$itemcomplexity[$data->item_complexity];
			$data->insp_pending=number_format($data->insp_pass_qty-$data->car_qty,0);
			$data->qty=number_format($data->qty,0);
			$data->car_qty=number_format($data->car_qty,0);
			$data->insp_pass_qty=number_format($data->insp_pass_qty,0);
			$data->insp_re_check_qty=number_format($data->insp_re_check_qty,0);
			$data->insp_faild_qty=number_format($data->insp_faild_qty,0);
			return $data;
		});
		echo json_encode($data);
	}

	public function getExfQty(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);
        $sale_order_id=request('sale_order_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		$saleorderid=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		if($sale_order_id){
			$saleorderid=" and sales_orders.id = $sale_order_id ";
		}

		//sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');


		$results = \DB::select("
			select
			styles.id as style_id,
			styles.style_ref,
			styles.flie_src,
			styles.buying_agent_id,
			styles.contact,
			buyers.id as buyer_id,
			buyers.name as buyer_name,
			uoms.code as uom_name,
			seasons.name as season_name,
			teams.name as team_name,
			teamleadernames.id as teamleader_id,
			teamleadernames.name as team_name,
			users.id as user_id,
			users.name as team_member_name,
			productdepartments.department_name,
			jobs.job_no,
			companies.code as company_code,
			produced_company.code as produced_company_code,
			sales_orders.id as sale_order_id,
			sales_orders.sale_order_no,
			sales_orders.receive_date as sale_order_receive_date,
			sales_orders.internal_ref,
			sales_orders.ship_date,
			countries.name as country_name,
			item_accounts.item_description,
			colors.name as color_name,
			sizes.name as size_name,
			sales_order_gmt_color_sizes.qty as qty,
			sales_order_gmt_color_sizes.plan_cut_qty as plan_cut_qty,
			sales_order_gmt_color_sizes.rate as rate,
			sales_order_gmt_color_sizes.amount as amount,
			style_gmts.smv,
			style_gmts.item_complexity,
			(style_gmts.smv*sales_order_gmt_color_sizes.qty)  as minute_booked,
			carton.car_qty,
			exfactory.exf_qty



			from
			styles
			join buyers on buyers.id=styles.buyer_id
			join uoms on uoms.id=styles.uom_id
			join seasons on seasons.id=styles.season_id
			join teams on teams.id=styles.team_id
			left join teammembers on teammembers.id=styles.factory_merchant_id
			left join users on users.id=teammembers.user_id
			left join productdepartments on productdepartments.id=styles.productdepartment_id
			left join teammembers  teamleaders on teamleaders.id=styles.teammember_id
			left join users teamleadernames on teamleadernames.id=teamleaders.user_id
			left join jobs on jobs.style_id=styles.id
			left join companies on companies.id=jobs.company_id
			left join sales_orders on sales_orders.job_id=jobs.id
			left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
			join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id
			join item_accounts on item_accounts.id = style_gmts.item_account_id
			join colors on colors.id = style_colors.color_id
			join sizes on sizes.id = style_sizes.size_id
			join countries on countries.id = sales_order_countries.country_id



			left join (
			SELECT 
			sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
			sum(style_pkg_ratios.qty) as car_qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			and sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
			where prod_gmt_carton_details.deleted_at is null
			and sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
			group by sales_order_gmt_color_sizes.id
			) carton on carton.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			left join (
			SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
			sum(style_pkg_ratios.qty) as exf_qty 
			FROM sales_orders  
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			join style_pkgs on style_pkgs.style_id = styles.id 
			join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
			join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id

			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			and sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
			join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
			and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id 
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
			where prod_gmt_ex_factory_qties.deleted_at is null 
			and prod_gmt_carton_details.deleted_at is null
			and sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
			group by sales_order_gmt_color_sizes.id
			) exfactory on exfactory.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

		    where sales_orders.order_status !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto $saleorderid
			order by 
			style_colors.sort_id,
			style_sizes.sort_id");
		$data=collect($results)
		->map(function($data) use($itemcomplexity){
			$data->item_complexity=$data->item_complexity?$itemcomplexity[$data->item_complexity]:'';
			$data->exf_pending=number_format($data->exf_qty-$data->qty,0);
			$data->qty=number_format($data->qty,0);
			$data->car_qty=number_format($data->car_qty,0);
			$data->exf_qty=number_format($data->exf_qty,0);
			return $data;
		});
		echo json_encode($data);
	}


	public function reportDataCom()
    {
    	$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		//$company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto


    	$str2=date('Y-m-d');
		$yesterday = date('Y-m-d', strtotime('-1 days', strtotime($str2)));
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);

		$results = \DB::select("
		select
		m.produced_company_id as company_id,
		m.produced_company_code as company_code,
		sum(m.qty) as qty,
		sum(m.plan_cut_qty) as plan_cut_qty,
		avg(m.rate) as rate,
		sum(m.amount) as amount,
		sum(m.smv) as smv,
        sum(m.booked_minute) as booked_minute,
		sum(m.ship_qty) as ship_qty,
		sum(m.target_qty) as target_qty,
		sum(m.dyed_yarn_rq) as dyed_yarn_rq,
		sum(m.yarn_req) as yarn_req,
		sum(m.fin_fab_req) as fin_fab_req,
		sum(m.yarn_rcv) as yarn_rcv,
		sum(m.grey_yarn_issue_qty_for_dye) as grey_yarn_issue_qty_for_dye,
		sum(m.dyed_yarn_rcv_qty) as dyed_yarn_rcv_qty,
		sum(m.inh_yarn_isu_qty) as inh_yarn_isu_qty,
		sum(m.out_yarn_isu_qty) as out_yarn_isu_qty,
		sum(m.inh_yarn_isu_rtn_qty) as inh_yarn_isu_rtn_qty,
		sum(m.out_yarn_isu_rtn_qty) as out_yarn_isu_rtn_qty,
		sum(m.prod_knit_qty) as prod_knit_qty,
		sum(m.knit_qty) as knit_qty,
		sum(m.batch_qty) as batch_qty,
		sum(m.dyeing_qty) as dyeing_qty,
		sum(m.finish_qty) as finish_qty,
		sum(m.cut_qty) as cut_qty,
		sum(m.req_scr_qty) as req_scr_qty,
		sum(m.snd_scr_qty) as snd_scr_qty,
		sum(m.rcv_scr_qty) as rcv_scr_qty,
		sum(m.sew_line_qty) as sew_line_qty,
		sum(m.sew_qty) as sew_qty,
		sum(m.alter_qty) as alter_qty,
		sum(m.reject_qty) as reject_qty,
		sum(m.iron_qty) as iron_qty,
		sum(m.iron_alter_qty) as iron_alter_qty,
		sum(m.iron_reject_qty) as iron_reject_qty,
		sum(m.poly_qty) as poly_qty,
		sum(m.poly_alter_qty) as poly_alter_qty,
		sum(m.poly_reject_qty) as poly_reject_qty,
		sum(m.car_qty) as car_qty,
		sum(m.ci_qty) as ci_qty,
		sum(m.ci_amount) as ci_amount,
		sum(m.poyarnlc_qty) as poyarnlc_qty
		from
		(
		select
		sales_orders.id,
		buyers.id as buyer_id,
		buyers.name as buyer_name,
		companies.id as company_id,
		companies.code as company_code,
		produced_company.id as produced_company_id,
		produced_company.code as produced_company_code,
		sum(sales_order_gmt_color_sizes.qty) as qty,
		sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
		avg(sales_order_gmt_color_sizes.rate) as rate,
		sum(sales_order_gmt_color_sizes.amount) as amount,
		bookedsmv.smv,
		bookedsmv.booked_minute,
		exfactory.qty as ship_qty,
		target.target_qty,
		dyedyarnrq.dyed_yarn_rq,
		yarnrq.yarn_req,
		yarnrcv.yarn_rcv,
		finfabrq.fin_fab_req,
		greyyarnfordye.grey_yarn_issue_qty_for_dye,
		dyedyarnrcv.dyed_yarn_rcv_qty,
		inhyarnisu.inh_yarn_isu_qty,
		outyarnisu.out_yarn_isu_qty,
		inhyarnisurtn.qty as inh_yarn_isu_rtn_qty,
		outyarnisurtn.qty as out_yarn_isu_rtn_qty,
		prodknit.prod_knit_qty,
		prodknit.knit_qty,
		prodbatch.batch_qty,
		proddyeing.dyeing_qty,
		prodfinish.finish_qty,
		prodcut.cut_qty,
		prodscrreq.req_scr_qty,
		prodscrdlv.snd_scr_qty,
		prodscrrcv.rcv_scr_qty,
		prodsewline.sew_line_qty,
		prodsew.sew_qty,
		prodsew.alter_qty,
		prodsew.reject_qty,
		prodiron.iron_qty,
		prodiron.iron_alter_qty,
		prodiron.iron_reject_qty,
		prodpoly.poly_qty,
		prodpoly.poly_alter_qty,
		prodpoly.poly_reject_qty,
		carton.car_qty,
		ci.ci_qty,
		ci.ci_amount,
		poyarnlc.qty as poyarnlc_qty
		from
		styles
		join buyers on buyers.id=styles.buyer_id
		join uoms on uoms.id=styles.uom_id
		join seasons on seasons.id=styles.season_id
		join teams on teams.id=styles.team_id
		left join teammembers on teammembers.id=styles.factory_merchant_id
		left join users on users.id=teammembers.user_id
		left join productdepartments on productdepartments.id=styles.productdepartment_id
		left join teammembers  teamleaders on teamleaders.id=styles.teammember_id
		left join users teamleadernames on teamleadernames.id=teamleaders.user_id
		left join jobs on jobs.style_id=styles.id
		left join companies on companies.id=jobs.company_id
		left join sales_orders on sales_orders.job_id=jobs.id
		left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id

		left join(
		select
		m.sales_order_id,
		avg(m.smv) as smv,
		sum(m.booked_minute) as booked_minute
		from 
		(
		SELECT 
		sales_orders.id as sales_order_id,
		style_gmts.smv,
		sales_order_gmt_color_sizes.qty as qty,
		sales_order_gmt_color_sizes.qty * style_gmts.smv as booked_minute
		FROM sales_orders 
		join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		where  1=1) m group by m.sales_order_id
		) bookedsmv on bookedsmv.sales_order_id=sales_orders.id
		left join (
		select
		target_transfers.sales_order_id,
		sum(target_transfers.qty)  as target_qty
		from
		target_transfers
		where
		target_transfers.process_id=8
		and target_transfers.deleted_at is null
		group by
		target_transfers.sales_order_id
		) target on target.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(budget_fabric_cons.grey_fab) as yarn_req,
		sum(budget_fabric_cons.fin_fab) as fin_fab_req
		FROM sales_orders 
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
		join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
		join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
		join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where style_fabrications.material_source_id!=1 and 1=1 
		group by sales_orders.id
		) yarnrq on yarnrq.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(budget_fabric_cons.grey_fab) as yarn_req,
		sum(budget_fabric_cons.fin_fab) as fin_fab_req
		FROM sales_orders 
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
		join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where  1=1 
		group by sales_orders.id
		) finfabrq on finfabrq.sales_order_id=sales_orders.id

		

		left join (
		select 
		po_yarn_item_bom_qties.sale_order_id as sales_order_id,
		sum(inv_yarn_rcv_item_sos.qty) yarn_rcv
		from
		po_yarn_item_bom_qties
		join inv_yarn_rcv_item_sos on inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id=po_yarn_item_bom_qties.id
		where po_yarn_item_bom_qties.deleted_at is null and  inv_yarn_rcv_item_sos.deleted_at is null
		group by po_yarn_item_bom_qties.sale_order_id
		) yarnrcv on yarnrcv.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(budget_yarn_dyeing_cons.bom_qty) as dyed_yarn_rq
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id
		where  1=1
		group by sales_orders.id
		) dyedyarnrq on dyedyarnrq.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sales_orders.ship_date,
		sales_orders.sale_order_no,
		sum(budget_yarn_dyeing_cons.bom_qty) as dyed_yarn_rq,
		sum(inv_yarn_isu_items.qty) as grey_yarn_issue_qty_for_dye
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id = budget_yarn_dyeing_cons.id
		join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
		join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where   inv_isus.isu_against_id=9 and inv_yarn_isu_items.deleted_at is null
		group by sales_orders.id,sales_orders.ship_date,sales_orders.sale_order_no
		) greyyarnfordye on greyyarnfordye.sales_order_id=sales_orders.id


		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(inv_yarn_rcv_items.qty) as dyed_yarn_rcv_qty
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id = budget_yarn_dyeing_cons.id
		join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
		join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
		join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_isu_item_id = inv_yarn_isu_items.id
		join inv_yarn_rcvs on inv_yarn_rcvs.id = inv_yarn_rcv_items.inv_yarn_rcv_id
		join inv_rcvs on inv_rcvs.id = inv_yarn_rcvs.inv_rcv_id

		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where   inv_isus.isu_against_id=9 and inv_yarn_isu_items.deleted_at is null and inv_rcvs.receive_against_id=9 and inv_yarn_rcv_items.deleted_at is null
		group by sales_orders.id
		) dyedyarnrcv on dyedyarnrcv.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(inv_yarn_isu_items.qty) as inh_yarn_isu_qty
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
		where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null  
		group by sales_orders.id
		) inhyarnisu on inhyarnisu.sales_order_id=sales_orders.id

		left join (
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
		) inhyarnisurtn on inhyarnisurtn.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(inv_yarn_isu_items.qty) as out_yarn_isu_qty
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
		--join companies on companies.id = suppliers.company_id
		join companies on companies.id = inv_isus.company_id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null 
		group by sales_orders.id
		) outyarnisu on outyarnisu.sales_order_id=sales_orders.id

		left join (
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
		--join companies on companies.id = suppliers.company_id
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
		) outyarnisurtn on outyarnisurtn.sales_order_id=sales_orders.id

		left join (
		select
		m.sales_order_id,
		sum(m.roll_weight) as prod_knit_qty,
		sum(m.qc_pass_qty) as knit_qty
		from 
		(
		select
		prod_knit_items.pl_knit_item_id,
		prod_knit_items.po_knit_service_item_qty_id,
		prod_knit_item_rolls.roll_weight,
		prod_knit_qcs.reject_qty,   
		prod_knit_qcs.qc_pass_qty,
		CASE 
		WHEN  inhprods.sales_order_id IS NULL THEN outprods.sales_order_id 
		ELSE inhprods.sales_order_id
		END as sales_order_id
		from
		prod_knits
		join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
		join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
		left join prod_knit_rcv_by_qcs on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
		left join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id
		/*prod_knit_qcs
		join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id=prod_knit_qcs.prod_knit_rcv_by_qc_id
		join prod_knit_item_rolls on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
		join prod_knit_items on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
		join prod_knits on prod_knits.id=prod_knit_items.prod_knit_id*/
		left join (
		select 
		pl_knit_items.id as pl_knit_item_id,
		sales_orders.id as sales_order_id
		from 
		sales_orders
		join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
		join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
		and po_knit_service_items.deleted_at is null
		join so_knit_po_items on so_knit_po_items.po_knit_service_item_qty_id=po_knit_service_item_qties.id
		join so_knit_refs on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
		join pl_knit_items on pl_knit_items.so_knit_ref_id=so_knit_refs.id
		join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id

		) inhprods on inhprods.pl_knit_item_id=prod_knit_items.pl_knit_item_id

		left join (
		select 
		po_knit_service_item_qties.id as po_knit_service_item_qty_id,
		sales_orders.id as sales_order_id
		from 
		sales_orders
		join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
		join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
		join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
		) outprods on outprods.po_knit_service_item_qty_id=prod_knit_items.po_knit_service_item_qty_id
		) m group by  m.sales_order_id
		) prodknit on prodknit.sales_order_id=sales_orders.id

		left join (
		select 
		sales_orders.id as sales_order_id,
		sum(prod_batch_rolls.qty) as batch_qty
		from 
		prod_batches
		join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
		join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
		join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
		join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
		join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
		join so_dyeing_pos on so_dyeings.id=so_dyeing_pos.so_dyeing_id
		join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
		join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
		join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
		and po_dyeing_service_items.deleted_at is null
		join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where 
		prod_batches.batch_for=1 and
		prod_batches.is_redyeing=0 and 
		prod_batches.deleted_at is null and 
		prod_batch_rolls.deleted_at is null 
		$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by
		sales_orders.id

		) prodbatch on prodbatch.sales_order_id=sales_orders.id

		left join (
		select 
		sales_orders.id as sales_order_id,
		sum(prod_batch_rolls.qty) as dyeing_qty
		from 
		prod_batches
		join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
		join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
		join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
		join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
		join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
		join so_dyeing_pos on so_dyeings.id=so_dyeing_pos.so_dyeing_id
		join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
		join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
		join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
		and po_dyeing_service_items.deleted_at is null
		join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where 
		prod_batches.batch_for=1 and
		prod_batches.is_redyeing=0 and 
		prod_batches.deleted_at is null and 
		prod_batch_rolls.deleted_at is null  and
		prod_batches.unloaded_at is not null 
		$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by
		sales_orders.id
		) proddyeing on proddyeing.sales_order_id=sales_orders.id

		left join (
		select 
		sales_orders.id as sales_order_id,
		sum(prod_batch_finish_qc_rolls.qty) as finish_qty
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
		join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where 
		prod_batches.batch_for=1 and
		prod_batches.is_redyeing=0 and 
		prod_batches.deleted_at is null and 
		prod_batch_rolls.deleted_at is null  and
		prod_batches.unloaded_at is not null 
		$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by
		sales_orders.id
		) prodfinish on prodfinish.sales_order_id=sales_orders.id



		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_cutting_qties.qty) as cut_qty
		FROM prod_gmt_cuttings
		join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
		group by 
		sales_orders.id
		) prodcut on prodcut.sales_order_id=sales_orders.id

		left join (
		select sales_order_gmt_color_sizes.sale_order_id as sales_order_id,sum(budget_emb_cons.req_cons) as req_scr_qty
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =45
		group by sales_order_gmt_color_sizes.sale_order_id
		) prodscrreq on prodscrreq.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_dlv_print_qties.qty) as snd_scr_qty
		FROM prod_gmt_dlv_prints
		join prod_gmt_dlv_print_orders on prod_gmt_dlv_print_orders.prod_gmt_dlv_print_id = prod_gmt_dlv_prints.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_dlv_print_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_dlv_print_qties on prod_gmt_dlv_print_qties.prod_gmt_dlv_print_order_id = prod_gmt_dlv_print_orders.id
		group by 
		sales_orders.id
		) prodscrdlv on prodscrdlv.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_print_rcv_qties.qty) as rcv_scr_qty
		FROM prod_gmt_print_rcvs
		join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id
		group by 
		sales_orders.id
		) prodscrrcv on prodscrrcv.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_sewing_line_qties.qty) as sew_line_qty
		FROM prod_gmt_sewing_lines
		join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id = prod_gmt_sewing_lines.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_line_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_sewing_line_qties on prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id = prod_gmt_sewing_line_orders.id
		group by 
		sales_orders.id
		) prodsewline on prodsewline.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_sewing_qties.qty) as sew_qty,
		sum(prod_gmt_sewing_qties.alter_qty) as alter_qty,
		sum(prod_gmt_sewing_qties.reject_qty) as reject_qty
		FROM prod_gmt_sewings
		join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id
		group by 
		sales_orders.id
		) prodsew on prodsew.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_iron_qties.qty) as iron_qty,
		sum(prod_gmt_iron_qties.alter_qty) as iron_alter_qty,
		sum(prod_gmt_iron_qties.reject_qty) as iron_reject_qty
		FROM prod_gmt_irons
		join prod_gmt_iron_orders on prod_gmt_iron_orders.prod_gmt_iron_id = prod_gmt_irons.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_iron_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_iron_qties on prod_gmt_iron_qties.prod_gmt_iron_order_id = prod_gmt_iron_orders.id
		group by 
		sales_orders.id
		) prodiron on prodiron.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_poly_qties.qty) as poly_qty,
		sum(prod_gmt_poly_qties.alter_qty) as poly_alter_qty,
		sum(prod_gmt_poly_qties.reject_qty) as poly_reject_qty
		FROM prod_gmt_polies
		join prod_gmt_poly_orders on prod_gmt_poly_orders.prod_gmt_poly_id = prod_gmt_polies.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_poly_qties on prod_gmt_poly_qties.prod_gmt_poly_order_id = prod_gmt_poly_orders.id
		group by 
		sales_orders.id
		) prodpoly on prodpoly.sales_order_id=sales_orders.id


		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(style_pkg_ratios.qty) as car_qty 
		FROM prod_gmt_carton_entries
		join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
		join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
		join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) carton on carton.sales_order_id=sales_orders.id

		left join (
		SELECT sales_orders.id as sales_order_id,sum(style_pkg_ratios.qty) as qty FROM sales_orders  
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		join style_pkgs on style_pkgs.style_id = styles.id 
		join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
		join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
		join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
		and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id 
		join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
		where prod_gmt_ex_factory_qties.deleted_at is null 
			and prod_gmt_carton_details.deleted_at is null
		group by sales_orders.id
		) exfactory on exfactory.sales_order_id=sales_orders.id

		left join (
		select 
		sales_orders.id as sales_order_id,
		sum(exp_invoice_orders.qty) as ci_qty, 
		sum(exp_invoice_orders.amount) as ci_amount 
		from
		sales_orders 
		join exp_pi_orders on exp_pi_orders.sales_order_id=sales_orders.id
		join exp_invoice_orders on exp_invoice_orders.exp_pi_order_id = exp_pi_orders.id 
		join exp_invoices on exp_invoices.id=exp_invoice_orders.exp_invoice_id
		where exp_invoices.invoice_status_id=2
		and exp_invoice_orders.deleted_at is null
		group by sales_orders.id
		) ci on ci.sales_order_id=sales_orders.id
		
		left join (
		select
            po_yarn_item_bom_qties.sale_order_id as sales_order_id,
            sum(po_yarn_item_bom_qties.qty) as qty
            from
            sales_orders
            join po_yarn_item_bom_qties on sales_orders.id=po_yarn_item_bom_qties.sale_order_id
            join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
            join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
            join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
            join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id

            join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id
            where imp_lcs.menu_id=3 
            and po_yarn_item_bom_qties.deleted_at is null
            and po_yarn_items.deleted_at is null
            and po_yarns.deleted_at is null
            and imp_lc_pos.deleted_at is null
            and imp_lcs.deleted_at is null
            group by
            po_yarn_item_bom_qties.sale_order_id
		) poyarnlc on poyarnlc.sales_order_id=sales_orders.id

		where sales_orders.order_status  !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto

		
		group by
		buyers.id,
		buyers.name,
		companies.id,
		companies.code,
		produced_company.id,
		produced_company.code,
		sales_orders.id,
		bookedsmv.smv,
		bookedsmv.booked_minute,
		exfactory.qty,
		target.target_qty,
		dyedyarnrq.dyed_yarn_rq,
		yarnrq.yarn_req,
		finfabrq.fin_fab_req,
		yarnrcv.yarn_rcv,
		greyyarnfordye.grey_yarn_issue_qty_for_dye,
		dyedyarnrcv.dyed_yarn_rcv_qty,
		inhyarnisu.inh_yarn_isu_qty,
		outyarnisu.out_yarn_isu_qty,
		inhyarnisurtn.qty,
		outyarnisurtn.qty,
		prodknit.knit_qty,
		prodknit.prod_knit_qty,
		prodbatch.batch_qty,
		proddyeing.dyeing_qty,
		prodfinish.finish_qty,
		prodcut.cut_qty,
		prodscrreq.req_scr_qty,
		prodscrdlv.snd_scr_qty,
		prodscrrcv.rcv_scr_qty,
		prodsewline.sew_line_qty,
		prodsew.sew_qty,
		prodsew.alter_qty,
		prodsew.reject_qty,
		prodiron.iron_qty,
		prodiron.iron_alter_qty,
		prodiron.iron_reject_qty,
		prodpoly.poly_qty,
		prodpoly.poly_alter_qty,
		prodpoly.poly_reject_qty,
		carton.car_qty,
		ci.ci_qty,
		ci.ci_amount,
		poyarnlc.qty
		) m
		group by
		m.produced_company_id,
		m.produced_company_code");
		$rows=collect($results)
		->map(function($rows) use($itemcomplexity,$buyinghouses){
			
			$rows->yarn_rcv_bal=$rows->yarn_rcv-$rows->yarn_req;
			$rows->poyarnlc_qty_bal=$rows->yarn_req-$rows->poyarnlc_qty;
			$rows->yarn_rcv_per=$rows->yarn_req?($rows->yarn_rcv/$rows->yarn_req)*100:0;
			$rows->yarn_cons=$rows->qty?($rows->yarn_req/$rows->qty)*12:0;
			$rows->inh_yarn_isu_qty=$rows->inh_yarn_isu_qty-$rows->inh_yarn_isu_rtn_qty;
			$rows->out_yarn_isu_qty=$rows->out_yarn_isu_qty-$rows->out_yarn_isu_rtn_qty;
			$rows->yarn_issue_to_knit=($rows->inh_yarn_isu_qty+$rows->out_yarn_isu_qty);
			$rows->yarn_req_bal=$rows->yarn_req-$rows->yarn_issue_to_knit;

			$rows->knit_prod_bal=$rows->prod_knit_qty-$rows->yarn_req;
			$rows->knit_prod_per=$rows->yarn_req?($rows->prod_knit_qty/$rows->yarn_req)*100:0;
			$rows->knit_bal=$rows->knit_qty-$rows->yarn_req;

			$rows->batch_bal=$rows->batch_qty-$rows->yarn_req;
			$rows->batch_per=$rows->knit_qty?($rows->batch_qty/$rows->yarn_req)*100:0;
			$rows->dyeing_bal=$rows->dyeing_qty-$rows->yarn_req;
			$rows->dyeing_per=$rows->batch_qty?($rows->dyeing_qty/$rows->yarn_req)*100:0;
			$rows->finish_bal=$rows->finish_qty-$rows->fin_fab_req;
			$rows->finish_per=$rows->dyeing_qty?($rows->finish_qty/$rows->fin_fab_req)*100:0;

			$rows->cut_bal=$rows->plan_cut_qty-$rows->cut_qty;
			$rows->cut_per=$rows->plan_cut_qty?($rows->cut_qty/$rows->plan_cut_qty)*100:0;
			$rows->sew_line_bal=$rows->sew_line_qty-$rows->cut_qty;
			$rows->cut_wip=0;

			$rows->sew_bal=$rows->sew_qty-$rows->qty;
			$rows->sew_per=$rows->qty?($rows->sew_qty/$rows->qty)*100:0;
			$rows->sew_wip=$rows->sew_qty-$rows->sew_line_qty;

			$rows->iron_bal=$rows->iron_qty-$rows->qty;
			$rows->iron_per=$rows->qty?($rows->iron_qty/$rows->qty)*100:0;
			$rows->iron_wip=$rows->sew_qty-$rows->iron_qty;

			$rows->poly_bal=$rows->poly_qty-$rows->qty;
			$rows->poly_per=$rows->qty?($rows->poly_qty/$rows->qty)*100:0;
			$rows->poly_wip=$rows->iron_qty-$rows->poly_qty;

			$rows->car_bal=$rows->car_qty-$rows->qty;
			$rows->car_per=$rows->sew_qty?($rows->car_qty/$rows->sew_qty)*100:0;
			$rows->car_wip=$rows->car_qty-$rows->sew_qty;

			$rows->yet_to_ship_qty=$rows->ship_qty-$rows->qty;
			$rows->ship_per=$rows->qty?($rows->ship_qty/$rows->qty)*100:0;
			$rows->ship_wip=$rows->ship_qty-$rows->car_qty;
			$rows->ship_value=$rows->ship_qty*$rows->rate;
			$rows->yet_to_ship_value=$rows->ship_value-$rows->amount;

			$rows->ci_qty_bal=$rows->ci_qty-$rows->qty;
			$rows->ci_qty_per=$rows->ship_qty?($rows->ci_qty/$rows->ship_qty)*100:0;
			$rows->ci_amount_bal=$rows->ci_amount-$rows->amount;
			$rows->ci_qty_wip=$rows->ci_qty-$rows->ship_qty;

			$rows->sortamount=$rows->amount;
			$rows->qty=number_format($rows->qty,'0','.',',');
			$rows->rate=number_format($rows->rate,'2','.',',');
			$rows->amount=number_format($rows->amount,'2','.',',');
			$rows->booked_minute=number_format($rows->booked_minute,2);
			$rows->smv=number_format($rows->smv,2);
			
            $rows->poyarnlc_qty_bal=number_format($rows->poyarnlc_qty_bal,'2','.',',');
            $rows->yarn_req=number_format($rows->yarn_req,'2','.',',');
            $rows->poyarnlc_qty=number_format($rows->poyarnlc_qty,'2','.',',');
            
			$rows->yarn_rcv=number_format($rows->yarn_rcv,'2','.',',');
			$rows->yarn_rcv_bal=number_format($rows->yarn_rcv_bal,'2','.',',');
			$rows->yarn_rcv_per=number_format($rows->yarn_rcv_per,2);
			$rows->yarn_cons=number_format($rows->yarn_cons,2);
			$rows->yarn_issue_to_knit=number_format($rows->yarn_issue_to_knit,2);
			$rows->yarn_req_bal=number_format($rows->yarn_req_bal,2);

			$rows->prod_knit_req=$rows->yarn_req;
			$rows->prod_knit_qty=number_format($rows->prod_knit_qty,'0','.',',');
			$rows->knit_prod_bal=number_format($rows->knit_prod_bal,'0','.',',');
			$rows->knit_prod_per=number_format($rows->knit_prod_per,'0','.',',');
			$rows->knit_qty=number_format($rows->knit_qty,'0','.',',');
			$rows->knit_bal=number_format($rows->knit_bal,'0','.',',');

			$rows->prod_dyeing_req=$rows->yarn_req;
			$rows->batch_qty=number_format($rows->batch_qty,'0','.',',');
			$rows->batch_bal=number_format($rows->batch_bal,'0','.',',');
			$rows->batch_per=number_format($rows->batch_per,'0','.',',');

			$rows->dyeing_qty=number_format($rows->dyeing_qty,'0','.',',');
			$rows->dyeing_bal=number_format($rows->dyeing_bal,'0','.',',');
			$rows->dyeing_per=number_format($rows->dyeing_per,'0','.',',');

			$rows->fin_fab_req=number_format($rows->fin_fab_req,'2','.',',');
			$rows->finish_qty=number_format($rows->finish_qty,'0','.',',');
			$rows->finish_bal=number_format($rows->finish_bal,'0','.',',');
			$rows->finish_per=number_format($rows->finish_per,'0','.',',');

            $rows->plan_cut_qty=number_format($rows->plan_cut_qty,'0','.',',');
		    $rows->cut_qty=number_format($rows->cut_qty,'0','.',',');
			$rows->cut_bal=number_format($rows->cut_bal,'0','.',',');
			$rows->cut_per=number_format($rows->cut_per,'2','.',',');
			$rows->cut_wip=number_format($rows->cut_wip,'2','.',',');
			$rows->sew_line_qty=number_format($rows->sew_line_qty,'0','.',',');
			$rows->sew_line_bal=number_format($rows->sew_line_bal,'0','.',',');

			$rows->sew_qty=number_format($rows->sew_qty,'0','.',',');
			$rows->sew_bal=number_format($rows->sew_bal,'0','.',',');
			$rows->sew_per=number_format($rows->sew_per,'0','.',',');
			$rows->alter_qty=number_format($rows->alter_qty,'0','.',',');
			$rows->reject_qty=number_format($rows->reject_qty,'0','.',',');
			$rows->sew_wip=number_format($rows->sew_wip,'0','.',',');

			$rows->iron_qty=number_format($rows->iron_qty,'0','.',',');
			$rows->iron_bal=number_format($rows->iron_bal,'0','.',',');
			$rows->iron_per=number_format($rows->iron_per,'0','.',',');
			$rows->iron_alter_qty=number_format($rows->iron_alter_qty,'0','.',',');
			$rows->iron_reject_qty=number_format($rows->iron_reject_qty,'0','.',',');
			$rows->iron_wip=number_format($rows->iron_wip,'0','.',',');

			$rows->poly_qty=number_format($rows->poly_qty,'0','.',',');
			$rows->poly_bal=number_format($rows->poly_bal,'0','.',',');
			$rows->poly_per=number_format($rows->poly_per,'0','.',',');
			$rows->poly_alter_qty=number_format($rows->poly_alter_qty,'0','.',',');
			$rows->poly_reject_qty=number_format($rows->poly_reject_qty,'0','.',',');
			$rows->poly_wip=number_format($rows->poly_wip,'0','.',',');


			$rows->car_qty=number_format($rows->car_qty,'0','.',',');
			$rows->car_bal=number_format($rows->car_bal,'0','.',',');
			$rows->car_per=number_format($rows->car_per,'0','.',',');
			$rows->car_wip=number_format($rows->car_wip,'0','.',',');

			$rows->ship_qty=number_format($rows->ship_qty,0,'.',',');
			$rows->yet_to_ship_qty=number_format($rows->yet_to_ship_qty,'0','.',',');
			$rows->ship_per=number_format($rows->ship_per,'2','.',',');
			$rows->ship_wip=number_format($rows->ship_wip,'0','.',',');
			$rows->ship_value=number_format($rows->ship_value,2,'.',',');
			$rows->yet_to_ship_value=number_format($rows->yet_to_ship_value,'2','.',',');

			$rows->ci_qty=number_format($rows->ci_qty,'0','.',',');
			$rows->ci_qty_bal=number_format($rows->ci_qty_bal,'0','.',',');
			$rows->ci_qty_per=number_format($rows->ci_qty_per,'0','.',',');
			$rows->ci_qty_wip=number_format($rows->ci_qty_wip,'0','.',',');
			$rows->ci_amount=number_format($rows->ci_amount,'2','.',',');
			$rows->ci_amount_bal=number_format($rows->ci_amount_bal,'2','.',',');
			return $rows;
		})
		->sortByDesc('sortamount')
		->values()
		->all();
        echo json_encode($rows);
	}

	public function reportDataBuy()
    {
    	$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

		//$company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto


    	$str2=date('Y-m-d');
		$yesterday = date('Y-m-d', strtotime('-1 days', strtotime($str2)));
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);

		$results = \DB::select("
		select
		m.buyer_id,
		m.buyer_name,
		sum(m.qty) as qty,
		sum(m.plan_cut_qty) as plan_cut_qty,
		avg(m.rate) as rate,
		sum(m.amount) as amount,
		sum(m.smv) as smv,
        sum(m.booked_minute) as booked_minute,
		sum(m.ship_qty) as ship_qty,
		sum(m.target_qty) as target_qty,
		sum(m.dyed_yarn_rq) as dyed_yarn_rq,
		sum(m.yarn_req) as yarn_req,
		sum(m.fin_fab_req) as fin_fab_req,
		sum(m.yarn_rcv) as yarn_rcv,
		sum(m.grey_yarn_issue_qty_for_dye) as grey_yarn_issue_qty_for_dye,
		sum(m.dyed_yarn_rcv_qty) as dyed_yarn_rcv_qty,
		sum(m.inh_yarn_isu_qty) as inh_yarn_isu_qty,
		sum(m.out_yarn_isu_qty) as out_yarn_isu_qty,
		sum(m.inh_yarn_isu_rtn_qty) as inh_yarn_isu_rtn_qty,
		sum(m.out_yarn_isu_rtn_qty) as out_yarn_isu_rtn_qty,
		sum(m.prod_knit_qty) as prod_knit_qty,
		sum(m.knit_qty) as knit_qty,
		sum(m.batch_qty) as batch_qty,
		sum(m.dyeing_qty) as dyeing_qty,
		sum(m.finish_qty) as finish_qty,
		sum(m.cut_qty) as cut_qty,
		sum(m.req_scr_qty) as req_scr_qty,
		sum(m.snd_scr_qty) as snd_scr_qty,
		sum(m.rcv_scr_qty) as rcv_scr_qty,
		sum(m.sew_line_qty) as sew_line_qty,
		sum(m.sew_qty) as sew_qty,
		sum(m.alter_qty) as alter_qty,
		sum(m.reject_qty) as reject_qty,
		sum(m.iron_qty) as iron_qty,
		sum(m.iron_alter_qty) as iron_alter_qty,
		sum(m.iron_reject_qty) as iron_reject_qty,
		sum(m.poly_qty) as poly_qty,
		sum(m.poly_alter_qty) as poly_alter_qty,
		sum(m.poly_reject_qty) as poly_reject_qty,
		sum(m.car_qty) as car_qty,
		sum(m.ci_qty) as ci_qty,
		sum(m.ci_amount) as ci_amount,
		sum(m.poyarnlc_qty) as poyarnlc_qty
		from
		(
		select
		sales_orders.id,
		buyers.id as buyer_id,
		buyers.name as buyer_name,
		companies.id as company_id,
		companies.code as company_code,
		produced_company.id as produced_company_id,
		produced_company.code as produced_company_code,
		sum(sales_order_gmt_color_sizes.qty) as qty,
		sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
		avg(sales_order_gmt_color_sizes.rate) as rate,
		sum(sales_order_gmt_color_sizes.amount) as amount,
		bookedsmv.smv,
		bookedsmv.booked_minute,
		exfactory.qty as ship_qty,
		target.target_qty,
		dyedyarnrq.dyed_yarn_rq,
		yarnrq.yarn_req,
		finfabrq.fin_fab_req,
		yarnrcv.yarn_rcv,
		greyyarnfordye.grey_yarn_issue_qty_for_dye,
		dyedyarnrcv.dyed_yarn_rcv_qty,
		inhyarnisu.inh_yarn_isu_qty,
		outyarnisu.out_yarn_isu_qty,
		inhyarnisurtn.qty as inh_yarn_isu_rtn_qty,
		outyarnisurtn.qty as out_yarn_isu_rtn_qty,
		prodknit.prod_knit_qty,
		prodknit.knit_qty,
		prodbatch.batch_qty,
		proddyeing.dyeing_qty,
		prodfinish.finish_qty,
		prodcut.cut_qty,
		prodscrreq.req_scr_qty,
		prodscrdlv.snd_scr_qty,
		prodscrrcv.rcv_scr_qty,
		prodsewline.sew_line_qty,
		prodsew.sew_qty,
		prodsew.alter_qty,
		prodsew.reject_qty,
		prodiron.iron_qty,
		prodiron.iron_alter_qty,
		prodiron.iron_reject_qty,
		prodpoly.poly_qty,
		prodpoly.poly_alter_qty,
		prodpoly.poly_reject_qty,
		carton.car_qty,
		ci.ci_qty,
		ci.ci_amount,
		poyarnlc.qty as poyarnlc_qty
		from
		styles
		join buyers on buyers.id=styles.buyer_id
		join uoms on uoms.id=styles.uom_id
		join seasons on seasons.id=styles.season_id
		join teams on teams.id=styles.team_id
		left join teammembers on teammembers.id=styles.factory_merchant_id
		left join users on users.id=teammembers.user_id
		left join productdepartments on productdepartments.id=styles.productdepartment_id
		left join teammembers  teamleaders on teamleaders.id=styles.teammember_id
		left join users teamleadernames on teamleadernames.id=teamleaders.user_id
		left join jobs on jobs.style_id=styles.id
		left join companies on companies.id=jobs.company_id
		left join sales_orders on sales_orders.job_id=jobs.id
		left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id

		left join(
		select
		m.sales_order_id,
		avg(m.smv) as smv,
		sum(m.booked_minute) as booked_minute
		from 
		(
		SELECT 
		sales_orders.id as sales_order_id,
		style_gmts.smv,
		sales_order_gmt_color_sizes.qty as qty,
		sales_order_gmt_color_sizes.qty * style_gmts.smv as booked_minute
		FROM sales_orders 
		join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		where  1=1) m group by m.sales_order_id
		) bookedsmv on bookedsmv.sales_order_id=sales_orders.id
		left join (
		select
		target_transfers.sales_order_id,
		sum(target_transfers.qty)  as target_qty
		from
		target_transfers
		where
		target_transfers.process_id=8
		and target_transfers.deleted_at is null
		group by
		target_transfers.sales_order_id
		) target on target.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(budget_fabric_cons.grey_fab) as yarn_req,
		sum(budget_fabric_cons.fin_fab) as fin_fab_req
		FROM sales_orders 
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
		join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where  1=1 
		group by sales_orders.id
		) yarnrq on yarnrq.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(budget_fabric_cons.fin_fab) as fin_fab_req
		FROM sales_orders 
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
		join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where  1=1 
		group by sales_orders.id
		) finfabrq on finfabrq.sales_order_id=sales_orders.id

		left join (
		select 
		po_yarn_item_bom_qties.sale_order_id as sales_order_id,
		sum(inv_yarn_rcv_item_sos.qty) yarn_rcv
		from
		po_yarn_item_bom_qties
		join inv_yarn_rcv_item_sos on inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id=po_yarn_item_bom_qties.id
		where po_yarn_item_bom_qties.deleted_at is null and  inv_yarn_rcv_item_sos.deleted_at is null
		group by po_yarn_item_bom_qties.sale_order_id
		) yarnrcv on yarnrcv.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(budget_yarn_dyeing_cons.bom_qty) as dyed_yarn_rq
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id
		where  1=1
		group by sales_orders.id
		) dyedyarnrq on dyedyarnrq.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sales_orders.ship_date,
		sales_orders.sale_order_no,
		sum(budget_yarn_dyeing_cons.bom_qty) as dyed_yarn_rq,
		sum(inv_yarn_isu_items.qty) as grey_yarn_issue_qty_for_dye
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id = budget_yarn_dyeing_cons.id
		join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
		join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where   inv_isus.isu_against_id=9 and inv_yarn_isu_items.deleted_at is null
		group by sales_orders.id,sales_orders.ship_date,sales_orders.sale_order_no
		) greyyarnfordye on greyyarnfordye.sales_order_id=sales_orders.id


		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(inv_yarn_rcv_items.qty) as dyed_yarn_rcv_qty
		FROM sales_orders 
		join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
		join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id = budget_yarn_dyeing_cons.id
		join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
		join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
		join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_isu_item_id = inv_yarn_isu_items.id
		join inv_yarn_rcvs on inv_yarn_rcvs.id = inv_yarn_rcv_items.inv_yarn_rcv_id
		join inv_rcvs on inv_rcvs.id = inv_yarn_rcvs.inv_rcv_id

		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where   inv_isus.isu_against_id=9 and inv_yarn_isu_items.deleted_at is null and inv_rcvs.receive_against_id=9 and inv_yarn_rcv_items.deleted_at is null
		group by sales_orders.id
		) dyedyarnrcv on dyedyarnrcv.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(inv_yarn_isu_items.qty) as inh_yarn_isu_qty
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
		where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null  
		group by sales_orders.id
		) inhyarnisu on inhyarnisu.sales_order_id=sales_orders.id

		left join (
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
		) inhyarnisurtn on inhyarnisurtn.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(inv_yarn_isu_items.qty) as out_yarn_isu_qty
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
		--join companies on companies.id = suppliers.company_id
		join companies on companies.id = inv_isus.company_id
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null 
		group by sales_orders.id
		) outyarnisu on outyarnisu.sales_order_id=sales_orders.id

		left join (
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
		--join companies on companies.id = suppliers.company_id
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
		) outyarnisurtn on outyarnisurtn.sales_order_id=sales_orders.id

		left join (
		select
		m.sales_order_id,
		sum(m.roll_weight) as prod_knit_qty,
		sum(m.qc_pass_qty) as knit_qty
		from 
		(
		select
		prod_knit_items.pl_knit_item_id,
		prod_knit_items.po_knit_service_item_qty_id,
		prod_knit_item_rolls.roll_weight,
		prod_knit_qcs.reject_qty,   
		prod_knit_qcs.qc_pass_qty,
		CASE 
		WHEN  inhprods.sales_order_id IS NULL THEN outprods.sales_order_id 
		ELSE inhprods.sales_order_id
		END as sales_order_id
		from
		prod_knits
		join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
		join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
		left join prod_knit_rcv_by_qcs on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
		left join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id
		/*prod_knit_qcs
		join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id=prod_knit_qcs.prod_knit_rcv_by_qc_id
		join prod_knit_item_rolls on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
		join prod_knit_items on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
		join prod_knits on prod_knits.id=prod_knit_items.prod_knit_id*/
		left join (
		select 
		pl_knit_items.id as pl_knit_item_id,
		sales_orders.id as sales_order_id
		from 
		sales_orders
		join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
		join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
		and po_knit_service_items.deleted_at is null
		join so_knit_po_items on so_knit_po_items.po_knit_service_item_qty_id=po_knit_service_item_qties.id
		join so_knit_refs on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
		join pl_knit_items on pl_knit_items.so_knit_ref_id=so_knit_refs.id
		join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id

		) inhprods on inhprods.pl_knit_item_id=prod_knit_items.pl_knit_item_id

		left join (
		select 
		po_knit_service_item_qties.id as po_knit_service_item_qty_id,
		sales_orders.id as sales_order_id
		from 
		sales_orders
		join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
		join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
		join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
		) outprods on outprods.po_knit_service_item_qty_id=prod_knit_items.po_knit_service_item_qty_id
		) m group by  m.sales_order_id
		) prodknit on prodknit.sales_order_id=sales_orders.id

		left join (
		select 
		sales_orders.id as sales_order_id,
		sum(prod_batch_rolls.qty) as batch_qty
		from 
		prod_batches
		join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
		join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
		join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
		join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
		join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
		join so_dyeing_pos on so_dyeings.id=so_dyeing_pos.so_dyeing_id
		join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
		join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
		join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
		and po_dyeing_service_items.deleted_at is null
		join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where 
		prod_batches.batch_for=1 and
		prod_batches.is_redyeing=0 and 
		prod_batches.deleted_at is null and 
		prod_batch_rolls.deleted_at is null 
		$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by
		sales_orders.id

		) prodbatch on prodbatch.sales_order_id=sales_orders.id

		left join (
		select 
		sales_orders.id as sales_order_id,
		sum(prod_batch_rolls.qty) as dyeing_qty
		from 
		prod_batches
		join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
		join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
		join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
		join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
		join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
		join so_dyeing_pos on so_dyeings.id=so_dyeing_pos.so_dyeing_id
		join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
		join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
		join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
		and po_dyeing_service_items.deleted_at is null
		join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where 
		prod_batches.batch_for=1 and
		prod_batches.is_redyeing=0 and 
		prod_batches.deleted_at is null and 
		prod_batch_rolls.deleted_at is null  and
		prod_batches.unloaded_at is not null 
		$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by
		sales_orders.id
		) proddyeing on proddyeing.sales_order_id=sales_orders.id

		left join (
		select 
		sales_orders.id as sales_order_id,
		sum(prod_batch_finish_qc_rolls.qty) as finish_qty
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
		join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
		join jobs on jobs.id=sales_orders.job_id
		join styles on styles.id=jobs.style_id
		where 
		prod_batches.batch_for=1 and
		prod_batches.is_redyeing=0 and 
		prod_batches.deleted_at is null and 
		prod_batch_rolls.deleted_at is null  and
		prod_batches.unloaded_at is not null 
		$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		group by
		sales_orders.id
		) prodfinish on prodfinish.sales_order_id=sales_orders.id


		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_cutting_qties.qty) as cut_qty
		FROM prod_gmt_cuttings
		join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
		group by 
		sales_orders.id
		) prodcut on prodcut.sales_order_id=sales_orders.id

		left join (
		select sales_order_gmt_color_sizes.sale_order_id as sales_order_id,sum(budget_emb_cons.req_cons) as req_scr_qty
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =45
		group by sales_order_gmt_color_sizes.sale_order_id
		) prodscrreq on prodscrreq.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_dlv_print_qties.qty) as snd_scr_qty
		FROM prod_gmt_dlv_prints
		join prod_gmt_dlv_print_orders on prod_gmt_dlv_print_orders.prod_gmt_dlv_print_id = prod_gmt_dlv_prints.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_dlv_print_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_dlv_print_qties on prod_gmt_dlv_print_qties.prod_gmt_dlv_print_order_id = prod_gmt_dlv_print_orders.id
		group by 
		sales_orders.id
		) prodscrdlv on prodscrdlv.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_print_rcv_qties.qty) as rcv_scr_qty
		FROM prod_gmt_print_rcvs
		join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id
		group by 
		sales_orders.id
		) prodscrrcv on prodscrrcv.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_sewing_line_qties.qty) as sew_line_qty
		FROM prod_gmt_sewing_lines
		join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id = prod_gmt_sewing_lines.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_line_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_sewing_line_qties on prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id = prod_gmt_sewing_line_orders.id
		group by 
		sales_orders.id
		) prodsewline on prodsewline.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_sewing_qties.qty) as sew_qty,
		sum(prod_gmt_sewing_qties.alter_qty) as alter_qty,
		sum(prod_gmt_sewing_qties.reject_qty) as reject_qty
		FROM prod_gmt_sewings
		join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id
		group by 
		sales_orders.id
		) prodsew on prodsew.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_iron_qties.qty) as iron_qty,
		sum(prod_gmt_iron_qties.alter_qty) as iron_alter_qty,
		sum(prod_gmt_iron_qties.reject_qty) as iron_reject_qty
		FROM prod_gmt_irons
		join prod_gmt_iron_orders on prod_gmt_iron_orders.prod_gmt_iron_id = prod_gmt_irons.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_iron_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_iron_qties on prod_gmt_iron_qties.prod_gmt_iron_order_id = prod_gmt_iron_orders.id
		group by 
		sales_orders.id
		) prodiron on prodiron.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(prod_gmt_poly_qties.qty) as poly_qty,
		sum(prod_gmt_poly_qties.alter_qty) as poly_alter_qty,
		sum(prod_gmt_poly_qties.reject_qty) as poly_reject_qty
		FROM prod_gmt_polies
		join prod_gmt_poly_orders on prod_gmt_poly_orders.prod_gmt_poly_id = prod_gmt_polies.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join prod_gmt_poly_qties on prod_gmt_poly_qties.prod_gmt_poly_order_id = prod_gmt_poly_orders.id
		group by 
		sales_orders.id
		) prodpoly on prodpoly.sales_order_id=sales_orders.id

		left join (
		SELECT 
		sales_orders.id as sales_order_id,
		sum(style_pkg_ratios.qty) as car_qty 
		FROM prod_gmt_carton_entries
		join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
		join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
		join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) carton on carton.sales_order_id=sales_orders.id

		left join (
		SELECT sales_orders.id as sales_order_id,sum(style_pkg_ratios.qty) as qty FROM sales_orders  
		join jobs on jobs.id = sales_orders.job_id 
		join styles on styles.id = jobs.style_id 
		join style_pkgs on style_pkgs.style_id = styles.id 
		join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
		join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
		join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
		and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id 
		join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
		where prod_gmt_ex_factory_qties.deleted_at is null 
			and prod_gmt_carton_details.deleted_at is null
		group by sales_orders.id
		) exfactory on exfactory.sales_order_id=sales_orders.id

		left join (
		select 
		sales_orders.id as sales_order_id,
		sum(exp_invoice_orders.qty) as ci_qty, 
		sum(exp_invoice_orders.amount) as ci_amount 
		from
		sales_orders 
		join exp_pi_orders on exp_pi_orders.sales_order_id=sales_orders.id
		join exp_invoice_orders on exp_invoice_orders.exp_pi_order_id = exp_pi_orders.id
		join exp_invoices on exp_invoices.id=exp_invoice_orders.exp_invoice_id
		where exp_invoices.invoice_status_id=2 
		and exp_invoice_orders.deleted_at is null
		group by sales_orders.id
		) ci on ci.sales_order_id=sales_orders.id

		left join (
		select
            po_yarn_item_bom_qties.sale_order_id as sales_order_id,
            sum(po_yarn_item_bom_qties.qty) as qty
            from
            sales_orders
            join po_yarn_item_bom_qties on sales_orders.id=po_yarn_item_bom_qties.sale_order_id
            join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
            join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
            join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
            join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id

            join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id
            where imp_lcs.menu_id=3 
            and po_yarn_item_bom_qties.deleted_at is null
            and po_yarn_items.deleted_at is null
            and po_yarns.deleted_at is null
            and imp_lc_pos.deleted_at is null
            and imp_lcs.deleted_at is null
            group by
            po_yarn_item_bom_qties.sale_order_id
		) poyarnlc on poyarnlc.sales_order_id=sales_orders.id

		where sales_orders.order_status  !=2 $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
		
		group by
		buyers.id,
		buyers.name,
		companies.id,
		companies.code,
		produced_company.id,
		produced_company.code,
		sales_orders.id,
		bookedsmv.smv,
		bookedsmv.booked_minute,
		exfactory.qty,
		target.target_qty,
		dyedyarnrq.dyed_yarn_rq,
		yarnrq.yarn_req,
		finfabrq.fin_fab_req,
		yarnrcv.yarn_rcv,
		greyyarnfordye.grey_yarn_issue_qty_for_dye,
		dyedyarnrcv.dyed_yarn_rcv_qty,
		inhyarnisu.inh_yarn_isu_qty,
		outyarnisu.out_yarn_isu_qty,
		inhyarnisurtn.qty,
		outyarnisurtn.qty,
		prodknit.knit_qty,
		prodknit.prod_knit_qty,
		prodbatch.batch_qty,
		proddyeing.dyeing_qty,
		prodfinish.finish_qty,
		prodcut.cut_qty,
		prodscrreq.req_scr_qty,
		prodscrdlv.snd_scr_qty,
		prodscrrcv.rcv_scr_qty,
		prodsewline.sew_line_qty,
		prodsew.sew_qty,
		prodsew.alter_qty,
		prodsew.reject_qty,
		prodiron.iron_qty,
		prodiron.iron_alter_qty,
		prodiron.iron_reject_qty,
		prodpoly.poly_qty,
		prodpoly.poly_alter_qty,
		prodpoly.poly_reject_qty,
		carton.car_qty,
		ci.ci_qty,
		ci.ci_amount,
		poyarnlc.qty
		) m
		group by
		m.buyer_id,
		m.buyer_name");
		$rows=collect($results)
		->map(function($rows) use($itemcomplexity,$buyinghouses){
			
			$rows->poyarnlc_qty_bal=$rows->yarn_req-$rows->poyarnlc_qty;
			$rows->yarn_rcv_bal=$rows->yarn_rcv-$rows->yarn_req;
			$rows->yarn_rcv_per=$rows->yarn_req?($rows->yarn_rcv/$rows->yarn_req)*100:0;
			$rows->yarn_cons=$rows->qty?($rows->yarn_req/$rows->qty)*12:0;
			$rows->inh_yarn_isu_qty=$rows->inh_yarn_isu_qty-$rows->inh_yarn_isu_rtn_qty;
			$rows->out_yarn_isu_qty=$rows->out_yarn_isu_qty-$rows->out_yarn_isu_rtn_qty;
			$rows->yarn_issue_to_knit=($rows->inh_yarn_isu_qty+$rows->out_yarn_isu_qty);
			$rows->yarn_req_bal=$rows->yarn_req-$rows->yarn_issue_to_knit;

			$rows->knit_prod_bal=$rows->prod_knit_qty-$rows->yarn_req;
			$rows->knit_prod_per=$rows->yarn_req?($rows->prod_knit_qty/$rows->yarn_req)*100:0;
			$rows->knit_bal=$rows->knit_qty-$rows->yarn_req;

			$rows->batch_bal=$rows->batch_qty-$rows->yarn_req;
			$rows->batch_per=$rows->knit_qty?($rows->batch_qty/$rows->yarn_req)*100:0;
			$rows->dyeing_bal=$rows->dyeing_qty-$rows->yarn_req;
			$rows->dyeing_per=$rows->batch_qty?($rows->dyeing_qty/$rows->yarn_req)*100:0;
			$rows->finish_bal=$rows->finish_qty-$rows->fin_fab_req;
			$rows->finish_per=$rows->dyeing_qty?($rows->finish_qty/$rows->fin_fab_req)*100:0;

			$rows->cut_bal=$rows->plan_cut_qty-$rows->cut_qty;
			$rows->cut_per=$rows->plan_cut_qty?($rows->cut_qty/$rows->plan_cut_qty)*100:0;
			$rows->sew_line_bal=$rows->sew_line_qty-$rows->cut_qty;
			$rows->cut_wip=0;

			$rows->sew_bal=$rows->sew_qty-$rows->qty;
			$rows->sew_per=$rows->qty?($rows->sew_qty/$rows->qty)*100:0;
			$rows->sew_wip=$rows->sew_qty-$rows->sew_line_qty;

			$rows->iron_bal=$rows->iron_qty-$rows->qty;
			$rows->iron_per=$rows->qty?($rows->iron_qty/$rows->qty)*100:0;
			$rows->iron_wip=$rows->sew_qty-$rows->iron_qty;

			$rows->poly_bal=$rows->poly_qty-$rows->qty;
			$rows->poly_per=$rows->qty?($rows->poly_qty/$rows->qty)*100:0;
			$rows->poly_wip=$rows->iron_qty-$rows->poly_qty;

			$rows->car_bal=$rows->car_qty-$rows->qty;
			$rows->car_per=$rows->sew_qty?($rows->car_qty/$rows->sew_qty)*100:0;
			$rows->car_wip=$rows->car_qty-$rows->sew_qty;


			$rows->yet_to_ship_qty=$rows->ship_qty-$rows->qty;
			$rows->ship_per=$rows->qty?($rows->ship_qty/$rows->qty)*100:0;
			$rows->ship_wip=$rows->ship_qty-$rows->car_qty;
			$rows->ship_value=$rows->ship_qty*$rows->rate;
			$rows->yet_to_ship_value=$rows->ship_value-$rows->amount;

			$rows->ci_qty_bal=$rows->ci_qty-$rows->qty;
			$rows->ci_qty_per=$rows->ship_qty?($rows->ci_qty/$rows->ship_qty)*100:0;
			$rows->ci_amount_bal=$rows->ci_amount-$rows->amount;

			$rows->ci_qty_wip=$rows->ci_qty-$rows->ship_qty;

			$rows->sortamount=$rows->amount;
			$rows->qty=number_format($rows->qty,'0','.',',');
			$rows->rate=number_format($rows->rate,'2','.',',');
			$rows->amount=number_format($rows->amount,'2','.',',');
			$rows->booked_minute=number_format($rows->booked_minute,2);
			$rows->smv=number_format($rows->smv,2);
			
			$rows->poyarnlc_qty_bal=number_format($rows->poyarnlc_qty_bal,'2','.',',');
            $rows->yarn_req=number_format($rows->yarn_req,'2','.',',');
            $rows->poyarnlc_qty=number_format($rows->poyarnlc_qty,'2','.',',');
			$rows->yarn_rcv=number_format($rows->yarn_rcv,'2','.',',');
			$rows->yarn_rcv_bal=number_format($rows->yarn_rcv_bal,'2','.',',');
			$rows->yarn_rcv_per=number_format($rows->yarn_rcv_per,2);
			$rows->yarn_cons=number_format($rows->yarn_cons,2);
			$rows->yarn_issue_to_knit=number_format($rows->yarn_issue_to_knit,2);
			$rows->yarn_req_bal=number_format($rows->yarn_req_bal,2);

			$rows->prod_knit_req=$rows->yarn_req;
			$rows->prod_knit_qty=number_format($rows->prod_knit_qty,'0','.',',');
			$rows->knit_prod_bal=number_format($rows->knit_prod_bal,'0','.',',');
			$rows->knit_prod_per=number_format($rows->knit_prod_per,'0','.',',');
			$rows->knit_qty=number_format($rows->knit_qty,'0','.',',');
			$rows->knit_bal=number_format($rows->knit_bal,'0','.',',');

			$rows->prod_dyeing_req=$rows->yarn_req;
			$rows->batch_qty=number_format($rows->batch_qty,'0','.',',');
			$rows->batch_bal=number_format($rows->batch_bal,'0','.',',');
			$rows->batch_per=number_format($rows->batch_per,'0','.',',');

			$rows->dyeing_qty=number_format($rows->dyeing_qty,'0','.',',');
			$rows->dyeing_bal=number_format($rows->dyeing_bal,'0','.',',');
			$rows->dyeing_per=number_format($rows->dyeing_per,'0','.',',');

			$rows->fin_fab_req=number_format($rows->fin_fab_req,'0','.',',');
			$rows->finish_qty=number_format($rows->finish_qty,'0','.',',');
			$rows->finish_bal=number_format($rows->finish_bal,'0','.',',');
			$rows->finish_per=number_format($rows->finish_per,'0','.',',');

            $rows->plan_cut_qty=number_format($rows->plan_cut_qty,'0','.',',');
		    $rows->cut_qty=number_format($rows->cut_qty,'0','.',',');
			$rows->cut_bal=number_format($rows->cut_bal,'0','.',',');
			$rows->cut_per=number_format($rows->cut_per,'2','.',',');
			$rows->cut_wip=number_format($rows->cut_wip,'2','.',',');
			$rows->sew_line_qty=number_format($rows->sew_line_qty,'0','.',',');
			$rows->sew_line_bal=number_format($rows->sew_line_bal,'0','.',',');

			$rows->sew_qty=number_format($rows->sew_qty,'0','.',',');
			$rows->sew_bal=number_format($rows->sew_bal,'0','.',',');
			$rows->sew_per=number_format($rows->sew_per,'0','.',',');
			$rows->alter_qty=number_format($rows->alter_qty,'0','.',',');
			$rows->reject_qty=number_format($rows->reject_qty,'0','.',',');
			$rows->sew_wip=number_format($rows->sew_wip,'0','.',',');
			
			$rows->iron_qty=number_format($rows->iron_qty,'0','.',',');
			$rows->iron_bal=number_format($rows->iron_bal,'0','.',',');
			$rows->iron_per=number_format($rows->iron_per,'0','.',',');
			$rows->iron_alter_qty=number_format($rows->iron_alter_qty,'0','.',',');
			$rows->iron_reject_qty=number_format($rows->iron_reject_qty,'0','.',',');
			$rows->iron_wip=number_format($rows->iron_wip,'0','.',',');

			$rows->poly_qty=number_format($rows->poly_qty,'0','.',',');
			$rows->poly_bal=number_format($rows->poly_bal,'0','.',',');
			$rows->poly_per=number_format($rows->poly_per,'0','.',',');
			$rows->poly_alter_qty=number_format($rows->poly_alter_qty,'0','.',',');
			$rows->poly_reject_qty=number_format($rows->poly_reject_qty,'0','.',',');
			$rows->poly_wip=number_format($rows->poly_wip,'0','.',',');

			$rows->car_qty=number_format($rows->car_qty,'0','.',',');
			$rows->car_bal=number_format($rows->car_bal,'0','.',',');
			$rows->car_per=number_format($rows->car_per,'0','.',',');
			$rows->car_wip=number_format($rows->car_wip,'0','.',',');

			$rows->ship_qty=number_format($rows->ship_qty,0,'.',',');
			$rows->yet_to_ship_qty=number_format($rows->yet_to_ship_qty,'0','.',',');
			$rows->ship_per=number_format($rows->ship_per,'2','.',',');
			$rows->ship_wip=number_format($rows->ship_wip,'0','.',',');
			$rows->ship_value=number_format($rows->ship_value,2,'.',',');
			$rows->yet_to_ship_value=number_format($rows->yet_to_ship_value,'2','.',',');

			$rows->ci_qty=number_format($rows->ci_qty,'0','.',',');
			$rows->ci_qty_bal=number_format($rows->ci_qty_bal,'0','.',',');
			$rows->ci_qty_per=number_format($rows->ci_qty_per,'0','.',',');
			$rows->ci_qty_wip=number_format($rows->ci_qty_wip,'0','.',',');
			$rows->ci_amount=number_format($rows->ci_amount,'2','.',',');
			$rows->ci_amount_bal=number_format($rows->ci_amount_bal,'2','.',',');

			/*$rows->dyed_yarn_bal_qty=number_format($rows->dyed_yarn_rq-$rows->dyed_yarn_rcv_qty,'2','.',',');
			$rows->grey_yarn_issue_for_dye_bal=number_format($rows->grey_yarn_issue_qty_for_dye- $rows->dyed_yarn_rq,'2','.',',');
			$rows->dyed_yarn_rq=number_format($rows->dyed_yarn_rq,'2','.',',');
			$rows->grey_yarn_issue_qty_for_dye=number_format($rows->grey_yarn_issue_qty_for_dye,'2','.',',');
			$rows->dyed_yarn_rcv_qty=number_format($rows->dyed_yarn_rcv_qty,'2','.',',');*/
			//$rows->inh_yarn_isu_qty=number_format($rows->inh_yarn_isu_qty,'2','.',',');
			//$rows->out_yarn_isu_qty=number_format($rows->out_yarn_isu_qty,'2','.',',');
			//$rows->target_qty=number_format($rows->target_qty,'0','.',',');
			/*$rows->req_scr_qty=number_format($rows->req_scr_qty,'0','.',',');
			$rows->snd_scr_qty=number_format($rows->snd_scr_qty,'0','.',',');
			$rows->snd_scr_qty_bal=number_format($rows->snd_scr_qty_bal,'0','.',',');
			$rows->rcv_scr_qty=number_format($rows->rcv_scr_qty,'0','.',',');
			$rows->bal_scr_qty=number_format($rows->bal_scr_qty,'0','.',',');
			$rows->insp_pass_qty=number_format(0,'0','.',',');
			$rows->insp_re_check_qty=number_format(0,'0','.',',');
			$rows->insp_faild_qty=number_format(0,'0','.',',');*/
			
			return $rows;
		})
		->sortByDesc('sortamount')
		->values()
		->all();
        echo json_encode($rows);
	}

	public function getOrderStyle(){
		return response()->json($this->style->getAll()->map(function($rows){
			$rows->receivedate=date("d-M-Y",strtotime($rows->receive_date));
			$rows->buyer=$rows->buyer_name;
			$rows->deptcategory=$rows->dept_category_name;
			$rows->season=$rows->season_name;
			$rows->uom=$rows->uom_name;
			$rows->team=$rows->team_name;
			$rows->teammember=$rows->team_member_name;
			$rows->productdepartment=$rows->department_name;
			return $rows;
		}));
	}

	public function getTeamMemberDlm(){
		$membertype=array_prepend(config('bprs.membertype'),'-Select-',0);
		$teammember = $this->teammember
		->join('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
		})
		->join('teams', function($join)  {
			$join->on('teammembers.team_id', '=', 'teams.id');
		})
		
		->when(request('team_id'), function ($q) {
			return $q->where('teammembers.team_id', '=', request('team_id', 0));
		})
		->get([
			'users.id as user_id',
			'teammembers.id as factory_merchant_id',
			'teammembers.type_id',
			'teams.name as team_name',
			'users.name as dlm_name',
			
		])
		->map(function($teammember)use($membertype){
			$teammember->type_id=$membertype[$teammember->type_id];	
			return $teammember;
		});
		echo json_encode($teammember);
	}

	public function orderProgressSummery(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;
		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
			$producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}
    	$str2=date('Y-m-d');
		$yesterday = date('Y-m-d', strtotime('-1 days', strtotime($str2)));
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);

		$ship_from=date('d-M-Y',strtotime($date_from));
		$ship_to=date('d-M-Y',strtotime($date_to));

		$order=collect(\DB::select("
		select
			m.produced_company_id,
			m.produced_company_code,
			sum(m.qty) as qty,
			sum(m.plan_cut_qty) as plan_cut_qty,
			avg(m.rate) as rate,
			sum(m.amount) as amount,
			sum(m.ship_qty) as ship_qty,
			sum(m.ship_value) as ship_value,
			sum(m.yarn_req) as yarn_req,
			sum(m.fin_fab_req) as fin_fab_req,
			sum(m.inh_yarn_isu_qty) as inh_yarn_isu_qty,
			sum(m.out_yarn_isu_qty) as out_yarn_isu_qty,
			sum(m.inh_yarn_isu_rtn_qty) as inh_yarn_isu_rtn_qty,
			sum(m.out_yarn_isu_rtn_qty) as out_yarn_isu_rtn_qty,
			sum(m.prod_knit_qty) as prod_knit_qty,
			sum(m.knit_qty) as knit_qty,
			sum(m.dyeing_qty) as dyeing_qty,
			sum(m.finish_qty) as finish_qty,
			sum(m.cut_qty) as cut_qty,
			sum(m.req_scr_qty) as req_scr_qty,
			sum(m.snd_scr_qty) as snd_scr_qty,
			sum(m.rcv_scr_qty) as rcv_scr_qty,
			sum(m.req_emb_qty) as req_emb_qty,
			sum(m.snd_emb_qty) as snd_emb_qty,
			sum(m.rcv_emb_qty) as rcv_emb_qty,
			sum(m.sew_line_qty) as sew_line_qty,
			sum(m.sew_qty) as sew_qty,
			sum(m.pending_ship_qty) as pending_ship_qty,
			sum(m.pending_ship_value) as pending_ship_value,
			sum(m.insp_pass_qty) as insp_pass_qty,
			sum(m.insp_re_check_qty) as insp_re_check_qty,
			sum(m.insp_faild_qty) as insp_faild_qty,
			sum(m.iron_qty) as iron_qty,
			sum(m.poly_qty) as poly_qty,
			sum(m.car_qty) as car_qty,
			sum(m.inv_fin_fab_isu_qty) as inv_fin_fab_isu_qty,
			sum(m.fin_fab_body_qty) as fin_fab_body_qty
			from
			(    
				select 
				produced_company.id as produced_company_id,
				produced_company.code as produced_company_code,
				sales_orders.id as sale_order_id,
				sum(sales_order_gmt_color_sizes.qty) as qty,
				sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
				avg(sales_order_gmt_color_sizes.rate) as rate,
				sum(sales_order_gmt_color_sizes.amount) as amount,
				exfactory.qty as ship_qty,
				exfactory.exfactory_amount as ship_value,
				pending.qty as pending_ship_qty,
				pending.amount as pending_ship_value,
				yarnrq.yarn_req,
				finfabrq.fin_fab_req,
				
				inhyarnisu.inh_yarn_isu_qty,
				outyarnisu.out_yarn_isu_qty,
				inhyarnisurtn.qty as inh_yarn_isu_rtn_qty,
				outyarnisurtn.qty as out_yarn_isu_rtn_qty,
				prodknit.knit_qty,
				prodknit.prod_knit_qty,
				proddyeing.dyeing_qty,
				prodfinish.finish_qty,
				prodcut.cut_qty,
				--prodcut.min_cut_qc_date,
				prodscrreq.req_scr_qty,
				prodscrdlv.snd_scr_qty,
				prodscrrcv.rcv_scr_qty,
				prodembreq.req_emb_qty,
				prodembdlv.snd_emb_qty,
				prodembrcv.rcv_emb_qty,
				prodsewline.sew_line_qty,
				prodsew.sew_qty,
				prodiron.iron_qty,
				prodpoly.poly_qty,
				carton.car_qty,
				inspec.insp_pass_qty,
				inspec.insp_re_check_qty,
				inspec.insp_faild_qty,
				finfabisutocut.rcv_qty as inv_fin_fab_isu_qty,
				cadcons.fin_fab_body_qty
				from 
				styles 
				join jobs on jobs.style_id = styles.id 
				join sales_orders on sales_orders.job_id = jobs.id 
				join companies produced_company on produced_company.id = sales_orders.produced_company_id 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id 
				
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(budget_fabric_cons.grey_fab) as yarn_req,
					sum(budget_fabric_cons.fin_fab) as fin_fab_req
					from sales_orders 
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
					join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
					join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
					join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
			
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where style_fabrications.material_source_id !=1  
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by sales_orders.id
				) yarnrq on yarnrq.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(budget_fabric_cons.grey_fab) as yarn_req,
					sum(budget_fabric_cons.fin_fab) as fin_fab_req
					from sales_orders 
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
					join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto      
					group by sales_orders.id
				) finfabrq on finfabrq.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(inv_yarn_isu_items.qty) as inh_yarn_isu_qty
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
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto     
					group by 
					sales_orders.id
				) inhyarnisu on inhyarnisu.sales_order_id = sales_orders.id 
				left join (
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
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto           
					group by 
					inv_yarn_rcv_items.sales_order_id
				) inhyarnisurtn on inhyarnisurtn.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(inv_yarn_isu_items.qty) as out_yarn_isu_qty
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
				) outyarnisu on outyarnisu.sales_order_id = sales_orders.id 
				left join (
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
				) outyarnisurtn on outyarnisurtn.sales_order_id = sales_orders.id 
				left join (
					select
					m.sales_order_id,
					sum(m.qc_pass_qty) as knit_qty,
					sum(m.roll_weight) as prod_knit_qty
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
						end as sales_order_id
						from
						prod_knits
						join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
						join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
						left join prod_knit_rcv_by_qcs on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
						left join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id
						left join (
							select 
							pl_knit_items.id as pl_knit_item_id,
							sales_orders.id as sales_order_id
							from 
							sales_orders
							join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
							join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
							and po_knit_service_items.deleted_at is null
							join so_knit_po_items on so_knit_po_items.po_knit_service_item_qty_id=po_knit_service_item_qties.id
							join so_knit_refs on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
							join pl_knit_items on pl_knit_items.so_knit_ref_id=so_knit_refs.id
							join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
						) inhprods on inhprods.pl_knit_item_id=prod_knit_items.pl_knit_item_id
						left join (
							select 
							po_knit_service_item_qties.id as po_knit_service_item_qty_id,
							sales_orders.id as sales_order_id
							from 
							sales_orders
							join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
							join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
							join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
						) outprods on outprods.po_knit_service_item_qty_id=prod_knit_items.po_knit_service_item_qty_id
					) m 
					group by  
					m.sales_order_id
				) prodknit on prodknit.sales_order_id = sales_orders.id 
				
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_batch_rolls.qty) as dyeing_qty
					from 
					prod_batches
					join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
					join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id=prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
					join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
					join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_fabric_rcv_items.so_dyeing_ref_id
					join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
					join so_dyeing_pos on so_dyeings.id=so_dyeing_pos.so_dyeing_id
					join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
					join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id
					join po_dyeing_service_items on po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
					and po_dyeing_service_items.deleted_at is null
					join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
					join jobs on jobs.id=sales_orders.job_id
					join styles on styles.id=jobs.style_id
					where 
					prod_batches.batch_for=1 and
					prod_batches.is_redyeing=0 and 
					prod_batches.deleted_at is null and 
					prod_batch_rolls.deleted_at is null  and
					prod_batches.unloaded_at is not null 
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by
					sales_orders.id
				) proddyeing on proddyeing.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_batch_finish_qc_rolls.qty) as finish_qty
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
					join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
					join jobs on jobs.id=sales_orders.job_id
					join styles on styles.id=jobs.style_id
					where 
					prod_batches.batch_for=1 and
					prod_batches.is_redyeing=0 and 
					prod_batches.deleted_at is null and 
					prod_batch_rolls.deleted_at is null  and
					prod_batches.unloaded_at is not null 
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by
					sales_orders.id
				) prodfinish on prodfinish.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_gmt_cutting_qties.qty) as cut_qty,
					min(prod_gmt_cuttings.cut_qc_date) as min_cut_qc_date
					from prod_gmt_cuttings
					join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
					join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by 
					sales_orders.id
				) prodcut on prodcut.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_order_gmt_color_sizes.sale_order_id as sales_order_id,
					sum(budget_emb_cons.req_cons) as req_scr_qty
					from budget_emb_cons 
					left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
					left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
					left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
					left join embelishments on embelishments.id=style_embelishments.embelishment_id
					left join production_processes on production_processes.id=embelishments.production_process_id
					where production_processes.production_area_id =45
					
					group by 
					sales_order_gmt_color_sizes.sale_order_id
				) prodscrreq on prodscrreq.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_gmt_dlv_print_qties.qty) as snd_scr_qty
					from prod_gmt_dlv_prints
					join prod_gmt_dlv_print_orders on prod_gmt_dlv_print_orders.prod_gmt_dlv_print_id = prod_gmt_dlv_prints.id
					join sales_order_countries on sales_order_countries.id = prod_gmt_dlv_print_orders.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join prod_gmt_dlv_print_qties on prod_gmt_dlv_print_qties.prod_gmt_dlv_print_order_id = prod_gmt_dlv_print_orders.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by 
					sales_orders.id
				) prodscrdlv on prodscrdlv.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_gmt_print_rcv_qties.qty) as rcv_scr_qty
					from prod_gmt_print_rcvs
					join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
					join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by 
					sales_orders.id
				) prodscrrcv on prodscrrcv.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_order_gmt_color_sizes.sale_order_id as sales_order_id,
					sum(budget_emb_cons.req_cons) as req_emb_qty
					from budget_emb_cons 
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
					join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
					join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
					join embelishments on embelishments.id=style_embelishments.embelishment_id
					join production_processes on production_processes.id=embelishments.production_process_id
					where production_processes.production_area_id =50
					group by 
					sales_order_gmt_color_sizes.sale_order_id
				) prodembreq on prodembreq.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_gmt_dlv_to_emb_qties.qty) as snd_emb_qty
					from prod_gmt_dlv_to_embs
					join prod_gmt_dlv_to_emb_orders on prod_gmt_dlv_to_emb_orders.prod_gmt_dlv_to_emb_id = prod_gmt_dlv_to_embs.id
					join sales_order_countries on sales_order_countries.id = prod_gmt_dlv_to_emb_orders.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join prod_gmt_dlv_to_emb_qties on prod_gmt_dlv_to_emb_qties.prod_gmt_dlv_to_emb_order_id = prod_gmt_dlv_to_emb_orders.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by 
					sales_orders.id
				) prodembdlv on prodembdlv.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_gmt_emb_rcv_qties.qty) as rcv_emb_qty
					from prod_gmt_emb_rcvs
					join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id = prod_gmt_emb_rcvs.id
					join sales_order_countries on sales_order_countries.id = prod_gmt_emb_rcv_orders.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join prod_gmt_emb_rcv_qties on prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id = prod_gmt_emb_rcv_orders.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by 
					sales_orders.id
				) prodembrcv on prodembrcv.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_gmt_sewing_line_qties.qty) as sew_line_qty
					from prod_gmt_sewing_lines
					join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id = prod_gmt_sewing_lines.id
					join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_line_orders.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join prod_gmt_sewing_line_qties on prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id = prod_gmt_sewing_line_orders.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by 
					sales_orders.id
				) prodsewline on prodsewline.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_gmt_iron_qties.qty) as iron_qty
					from prod_gmt_irons
					join prod_gmt_iron_orders on prod_gmt_iron_orders.prod_gmt_iron_id = prod_gmt_irons.id
					join sales_order_countries on sales_order_countries.id = prod_gmt_iron_orders.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join prod_gmt_iron_qties on prod_gmt_iron_qties.prod_gmt_iron_order_id = prod_gmt_iron_orders.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by 
					sales_orders.id
				) prodiron on prodiron.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_gmt_poly_qties.qty) as poly_qty
					from prod_gmt_polies
					join prod_gmt_poly_orders on prod_gmt_poly_orders.prod_gmt_poly_id = prod_gmt_polies.id
					join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join prod_gmt_poly_qties on prod_gmt_poly_qties.prod_gmt_poly_order_id = prod_gmt_poly_orders.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by 
					sales_orders.id
				) prodpoly on prodpoly.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_gmt_sewing_qties.qty) as sew_qty
					from prod_gmt_sewings
					join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
					join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id
					--where prod_gmt_sewing_qties.deleted_at is null
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by 
					sales_orders.id
				) prodsew on prodsew.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(style_pkg_ratios.qty) as car_qty 
					from prod_gmt_carton_entries
					join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
					join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
					join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
					join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by sales_orders.id
				) carton on carton.sales_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(prod_gmt_inspection_orders.qty) as insp_pass_qty,
					sum(prod_gmt_inspection_orders.re_check_qty) as insp_re_check_qty,
					sum(prod_gmt_inspection_orders.failed_qty) as insp_faild_qty
					from
					prod_gmt_inspections
					join prod_gmt_inspection_orders on prod_gmt_inspection_orders.prod_gmt_inspection_id=prod_gmt_inspections.id
					join sales_order_countries on sales_order_countries.id=prod_gmt_inspections.sales_order_country_id
					join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where 1=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by
					sales_orders.id
				) inspec on inspec.sales_order_id = sales_orders.id 
				left join (
					SELECT 
					sales_orders.id as sale_order_id,
					sum(style_pkg_ratios.qty) as qty,
					sum(style_pkg_ratios.qty)*avg(saleorders.rate) as exfactory_amount

					FROM sales_orders  
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					join style_pkgs on style_pkgs.style_id = styles.id 
					join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
					join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
					join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
					and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id 
					join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id
					join prod_gmt_ex_factories on prod_gmt_ex_factories.id = prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id
					left join (
						SELECT 
							sales_orders.id as sale_order_id,
							avg(sales_order_gmt_color_sizes.rate) as rate 
						FROM sales_orders
							join sales_order_countries on sales_orders.id = sales_order_countries.sale_order_id
							join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
							where sales_order_gmt_color_sizes.deleted_at is null
							and sales_order_gmt_color_sizes.qty >0 
							group by sales_orders.id
					) saleorders on saleorders.sale_order_id = sales_orders.id
					where prod_gmt_ex_factory_qties.deleted_at is null 
					and prod_gmt_carton_details.deleted_at is null
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by sales_orders.id
				) exfactory on exfactory.sale_order_id = sales_orders.id 
				left join (
					select 
					sales_orders.id as sale_order_id,
					sum(sales_order_gmt_color_sizes.qty) as qty,
					sum(sales_order_gmt_color_sizes.amount) as amount 
					from sales_orders 
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id 
					where sales_orders.ship_date<='$yesterday'
					and sales_orders.order_status=1
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
					group by sales_orders.id
				) pending on pending.sale_order_id = sales_orders.id 
				left join (
					select
					m.sales_order_id,
					m.produced_company_id,
					sum(m.rcv_qty) as rcv_qty
					from(
					select 
						case 
						when  dyeingbatch.sales_order_id is null then aopbatch.sales_order_id 
						else dyeingbatch.sales_order_id
						end as sales_order_id,
						case 
						when  dyeingbatch.produced_company_id is null then aopbatch.produced_company_id 
						else dyeingbatch.produced_company_id
						end as produced_company_id,
						
						inv_finish_fab_isu_items.qty as rcv_qty
						from 
						inv_isus
						inner join inv_finish_fab_isu_items on  inv_finish_fab_isu_items.inv_isu_id=inv_isus.id
						inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.id=inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id
						inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.id=inv_finish_fab_rcv_items.inv_finish_fab_rcv_id
						inner join inv_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
						inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
						inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
						inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
						and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
						inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
						inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 

						left join (
						select 
						prod_batch_rolls.id as prod_batch_roll_id,
						po_dyeing_service_item_qties.sales_order_id,
						sales_orders.produced_company_id
						from 
						prod_batches
						inner join prod_batch_rolls on prod_batch_rolls.prod_batch_id = prod_batches.id
						inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
						inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
						inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
						--inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
						--inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
						inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
						inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
						inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
						inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
						inner join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
						inner join jobs on jobs.id = sales_orders.job_id
						inner join styles on styles.id = jobs.style_id
						where prod_batches.deleted_at is null 
						) dyeingbatch on dyeingbatch.prod_batch_roll_id=prod_batch_finish_qc_rolls.prod_batch_roll_id

						left join (
						select 
						prod_aop_batch_rolls.id as prod_aop_batch_roll_id,
						sales_orders.id as sales_order_id,
						sales_orders.produced_company_id

						from 
						prod_aop_batches
						inner join prod_aop_batch_rolls on prod_aop_batch_rolls.prod_aop_batch_id = prod_aop_batches.id
						inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
						inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
						inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
						inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
						inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id
						inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
						inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
						inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
						inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
						inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
						--inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
						--inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
						inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
						inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
						inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id 
						and po_aop_service_items.deleted_at is null
						inner join budget_fabric_prod_cons on budget_fabric_prod_cons.id = po_aop_service_item_qties.budget_fabric_prod_con_id
						inner join sales_orders on sales_orders.id=budget_fabric_prod_cons.sales_order_id
						inner join jobs on jobs.id = sales_orders.job_id
						inner join styles on styles.id = jobs.style_id
						where prod_aop_batches.deleted_at is null 
						) aopbatch on aopbatch.prod_aop_batch_roll_id=prod_batch_finish_qc_rolls.prod_aop_batch_roll_id
						--where
						--inv_isus.id = 66073 
						--and inv_isus.deleted_at is null
						) m
						group by m.sales_order_id,
						m.produced_company_id
				)finfabisutocut on finfabisutocut.sales_order_id=sales_orders.id 
				left join (
					select 
					sales_orders.id as sales_order_id,
					sum(budget_fabric_cons.fin_fab) as fin_fab_body_qty
					from sales_orders 
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
					join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
					join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
        			join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
					join jobs on jobs.id = sales_orders.job_id 
					join styles on styles.id = jobs.style_id
					where style_fabrications.is_narrow=0
					$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto      
					group by sales_orders.id
				) cadcons on cadcons.sales_order_id=sales_orders.id 
				where 1=1
				and (sales_orders.order_status != 2) 
				and styles.deleted_at is null
				$company $producedcompany $buyer $style $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto 
				group by 
					produced_company.code, 
					produced_company.id, 
					sales_orders.id, 
					exfactory.qty, 
					exfactory.exfactory_amount,
					pending.qty, 
					pending.amount, 
					yarnrq.yarn_req, 
					finfabrq.fin_fab_req, 
					inhyarnisu.inh_yarn_isu_qty, 
					outyarnisu.out_yarn_isu_qty, 
					inhyarnisurtn.qty, 
					outyarnisurtn.qty, 
					prodknit.knit_qty, 
					prodknit.prod_knit_qty, 
					proddyeing.dyeing_qty, 
					prodfinish.finish_qty, 
					prodcut.cut_qty, 
					prodscrreq.req_scr_qty, 
					prodscrdlv.snd_scr_qty, 
					prodscrrcv.rcv_scr_qty,
					prodembreq.req_emb_qty,
					prodembdlv.snd_emb_qty,
					prodembrcv.rcv_emb_qty,
					prodsewline.sew_line_qty, 
					prodsew.sew_qty, 
					prodiron.iron_qty, 
					prodpoly.poly_qty, 
					carton.car_qty, 
					inspec.insp_pass_qty, 
					inspec.insp_re_check_qty, 
					inspec.insp_faild_qty,
					finfabisutocut.rcv_qty,
					cadcons.fin_fab_body_qty
			) m
			group by
			m.produced_company_id,
			m.produced_company_code
		"))
		->map(function($order){
			//$order->ship_value=$order->ship_qty*$order->rate;
			$order->ship_pending_qty=$order->qty-$order->ship_qty;
			$order->ship_pending_value=$order->amount-$order->ship_value;
			$order->inh_yarn_isu_qty=$order->inh_yarn_isu_qty-$order->inh_yarn_isu_rtn_qty;
			$order->out_yarn_isu_qty=$order->out_yarn_isu_qty-$order->out_yarn_isu_rtn_qty;
			$order->knit_bal=$order->knit_qty-$order->yarn_req;
			$order->dyeing_bal=$order->dyeing_qty-$order->yarn_req;
			$order->finish_bal=$order->finish_qty-$order->fin_fab_req;

			$order->snd_scr_qty_bal=$order->req_scr_qty-$order->snd_scr_qty;
			$order->bal_scr_qty=$order->rcv_scr_qty-$order->snd_scr_qty;

			$order->sew_line_bal=$order->sew_line_qty-$order->cut_qty;
			$order->sew_bal=$order->sew_qty-$order->qty;

			$order->iron_bal_qty=$order->qty-$order->iron_qty;
			$order->iron_bal=$order->sew_qty-$order->iron_qty;

			$order->poly_bal_qty=$order->qty-$order->poly_qty;
			$order->poly_bal=$order->iron_qty-$order->poly_qty;

			$order->car_bal=$order->car_qty-$order->sew_qty;
			
			$order->yarn_isu_qty=$order->inh_yarn_isu_qty+$order->out_yarn_isu_qty;
			
			$order->yarn_isu_pending=$order->yarn_req-$order->yarn_isu_qty;

			$order->prod_knit_pending=$order->yarn_req-$order->prod_knit_qty;
			$order->prod_knit_wip=$order->yarn_isu_qty-$order->prod_knit_qty;

			$order->dyeing_pending=$order->yarn_req-$order->dyeing_qty;
			$order->dyeing_wip=$order->prod_knit_qty-$order->dyeing_qty;

			$order->fin_fab_pending=$order->fin_fab_req-$order->finish_qty;
			$order->fin_fab_wip=$order->dyeing_qty-$order->finish_qty;

			$order->cut_pending=$order->plan_cut_qty-$order->cut_qty;
			if ($order->plan_cut_qty) {
				$cons_per_kg=$order->fin_fab_body_qty/$order->plan_cut_qty;
			}
			$order->cut_wip_qty_kg=$order->inv_fin_fab_isu_qty-($order->cut_qty*$cons_per_kg);
			if ($cons_per_kg) {
				$order->cut_wip_qty_pcs=($order->inv_fin_fab_isu_qty-($order->cut_qty*$cons_per_kg))/$cons_per_kg;
			}
			
			$order->scr_pending_qty=$order->req_scr_qty-$order->rcv_scr_qty;
			$order->scr_wip_qty=$order->snd_scr_qty-$order->rcv_scr_qty;
			$order->emb_pending_qty=$order->req_emb_qty-$order->rcv_emb_qty;
			$order->emb_wip_qty=$order->snd_emb_qty-$order->rcv_emb_qty;
			$order->sew_line_pending_qty=$order->qty-$order->sew_line_qty;
			$order->sew_line_wip_qty=($order->cut_qty-(($order->snd_scr_qty*0.01)-($order->snd_emb_qty*0.01)))-$order->sew_line_qty;
			$order->sew_pending_qty=$order->qty-$order->sew_qty;
			$order->sew_wip_qty=$order->sew_line_qty-$order->sew_qty;
			$order->iron_pending_qty=$order->qty-$order->iron_qty;
			$order->iron_wip_qty=$order->sew_qty-$order->iron_qty;
			$order->poly_pending_qty=$order->qty-$order->poly_qty;
			$order->poly_wip_qty=$order->iron_qty-$order->poly_qty;
			$order->car_pending_qty=$order->qty-$order->car_qty;
			$order->car_wip_qty=$order->poly_qty-$order->car_qty;
			$order->insp_pass_pending_qty=$order->qty-$order->insp_pass_qty;
			$order->insp_pass_wip_qty=$order->car_qty-$order->insp_pass_qty;
			$order->yarn_req_bal=$order->yarn_req-($order->inh_yarn_isu_qty+$order->out_yarn_isu_qty);
			return $order;
		});
		$rows=$order->groupBy(['produced_company_id']);

		$prodCompanyArr=[];
		 foreach ($order as $data) {
		 	$prodCompanyArr[$data->produced_company_id]=$data->produced_company_code;
		 }

		//dd($prodCompanyArr);die;

		return Template::loadView('Report.OrderProgressSummeryMatrix',[
            'rows'=>$rows,
            'ship_from'=>$ship_from,
            'ship_to'=>$ship_to,
            'prodCompanyArr'=>$prodCompanyArr,
        ]);
	}

}
