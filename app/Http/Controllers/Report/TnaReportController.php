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
use App\Repositories\Contracts\Util\TnataskRepository;
use Illuminate\Support\Carbon;
class TnaReportController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $user;
	private $buyernature;
	private $itemaccount;
	private $tnatask;
	public function __construct(
		StyleRepository $style,
		CompanyRepository $company,
		BuyerRepository $buyer,
		BuyerNatureRepository $buyernature,
		UserRepository $user,
		ItemAccountRepository $itemaccount,
		TnataskRepository $tnatask
	)
    {
		$this->style=$style;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->user = $user;
		$this->buyernature = $buyernature;
		$this->itemaccount = $itemaccount;
		$this->tnatask = $tnatask;
		$this->middleware('auth');
		//$this->middleware('permission:view.orderprogressreports',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-',0);
        return Template::loadView('Report.TnaReport',['company'=>$company,'buyer'=>$buyer]);
    }

    private function getData()
    {
    	$company_id=request('company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);

		$company=null;
		$buyer=null;
		$datefrom=null;
		$dateto=null;

		if($company_id){
			$company=" and target_transfers.produced_company_id = $company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date >='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date <='".$date_to."' ";
		}
		$tnatask=array_prepend(array_pluck($this->tnatask->get(),'completion_treated_percent','tna_task_id'),'-Select-',0);

		


		/*$data = collect(
        \DB::select("
			select 
			target_transfers.entry_id,
			companies.code as company_code,
			pcompanies.code as produced_company_code,
			teamleadernames.name as team_ld_name,
			users.name as team_member_name,
			buyers.name as buyer_name,
			buying_houses.name as buying_house_name,
			styles.style_ref,
			sales_orders.sale_order_no,
			sales_orders.ship_date,
			productdepartments.department_name,
			styles.flie_src,
			target_transfers.date_from,
			target_transfers.date_to,
			target_transfers.qty
			from
			target_transfers
			join sales_orders on sales_orders.id=target_transfers.sales_order_id
			join jobs on jobs.id=sales_orders.job_id
			join styles on styles.id=jobs.style_id
			join companies on companies.id=jobs.company_id
			join companies  pcompanies on pcompanies.id=target_transfers.produced_company_id
			left join teammembers teamleaders on styles.teammember_id=teamleaders.id
			left join users  teamleadernames on teamleadernames.id=teamleaders.user_id
			left join teammembers  on styles.factory_merchant_id=teammembers.id
			left join users on users.id=teammembers.user_id
			join buyers on buyers.id=styles.buyer_id
			left join buyers   buying_houses on buying_houses.id=styles.buying_agent_id
			left join productdepartments on productdepartments.id=styles.productdepartment_id
			where target_transfers.process_id=8 $company $buyer  $datefrom  $dateto
			order by target_transfers.entry_id desc
			")
        )
        ->map(function($data) use($gmtArr) {

        	$ship_date = Carbon::parse($data->ship_date);
			$date_from = Carbon::parse($data->date_from);
			$date_to = Carbon::parse($data->date_to);
			$sewingDays = $date_to->diffInDays($date_from)+1;

        	$data->ship_date=date('d-M-Y',strtotime($data->ship_date));
        	$data->date_from=date('d-M-Y',strtotime($data->date_from));
        	$data->date_to=date('d-M-Y',strtotime($data->date_to));
        	$data->sewing_days=$sewingDays;
        	$data->item_description=implode(',',$gmtArr[$data->entry_id]);
        	$data->remarks='';
			if($date_to->greaterThan($ship_date)){
				$data->remarks='Ask buyer to extend shipment date';
			}
			$data->no_of_line=ceil($data->qty/$sewingDays/2200);
			$data->qty=number_format($data->qty,0);
        	return $data;
        });
        return $data;*/

        $data = collect(
        \DB::select("
			select 
			sales_orders.id,
			styles.style_ref,
			buyers.name as buyer_name,
			companies.code as company_code,
			pcompanies.code as produced_company_code,
			teamleadernames.name as team_ld_name,
			users.name as team_member_name,
			sales_orders.sale_order_no,
			sales_orders.ship_date,
			sales_orders.receive_date,

			poqties.po_qty,
			poqties.plan_cut_qty,

			tnadyedyarns.tna_start_date as dyed_yarn_start_date,
			tnadyedyarns.tna_end_date as dyed_yarn_end_date,

			tnayarns.tna_start_date as yarn_start_date,
			tnayarns.tna_end_date as yarn_end_date,

			tnayarnisus.tna_start_date as yarn_isu_start_date,
			tnayarnisus.tna_end_date as yarn_isu_end_date,

			tnaknits.tna_start_date as knit_start_date,
			tnaknits.tna_end_date as knit_end_date,

			tnadyeings.tna_start_date as dyeing_start_date,
			tnadyeings.tna_end_date as dyeing_end_date,

			tnaaops.tna_start_date as aop_start_date,
			tnaaops.tna_end_date as aop_end_date,
			tnaaops.min_aop_date,
			tnaaops.max_aop_date,

			tnadyeingfinish.tna_start_date as dyeingfinish_start_date,
			tnadyeingfinish.tna_end_date as dyeingfinish_end_date,

			tnatrims.tna_start_date as trim_start_date,
			tnatrims.tna_end_date as trim_end_date,
			tnatrims.min_trim_date,
			tnatrims.max_trim_date,

			tnapps.tna_start_date as pp_start_date,
			tnapps.tna_end_date as pp_end_date,
			tnapps.min_pp_date,
			tnapps.max_pp_date,

			tnacuts.tna_start_date as cut_start_date,
			tnacuts.tna_end_date as cut_end_date,

			tnaembsps.tna_start_date as embsp_start_date,
			tnaembsps.tna_end_date as embsp_end_date,

			tnasews.tna_start_date as sew_start_date,
			tnasews.tna_end_date as sew_end_date,

			tnafins.tna_start_date as fin_start_date,
			tnafins.tna_end_date as fin_end_date,

			tnainsps.tna_start_date as insp_start_date,
			tnainsps.tna_end_date as insp_end_date,

			dyedyarnrq.yarn_req as dyed_yarn_req,
			dyedyarnrcv.yarn_rcv as dyed_yarn_rcv,
			dyedyarnrcv.min_receive_date as min_dyed_yarn_receive_date,
			dyedyarnrcv.max_receive_date as max_dyed_yarn_receive_date,

			yarnrq.yarn_req,

			yarnrcv.yarn_rcv,
			yarnrcv.min_receive_date as min_yarn_receive_date,
			yarnrcv.max_receive_date as max_yarn_receive_date,

			yarnrq.yarn_req as yarn_isu_req,
			yarnisu.yarn_isu,
			yarnisu.min_issue_date as min_yarn_isu_date,
			yarnisu.max_issue_date as max_yarn_isu_date,

			fabricrq.grey_fab_req,
			fabricrq.fin_fab_req,

			prodknit.knit_qty,
			prodknit.min_knit_date,
			prodknit.max_knit_date,

			proddyeing.dyeing_qty,
			proddyeing.min_dyeing_date,
			proddyeing.max_dyeing_date,

			prodfinish.dyeingfinish_qty,
			prodfinish.min_dyeingfinish_date,
			prodfinish.max_dyeingfinish_date,

			prodcut.cut_qty,
			prodcut.min_cut_date,
			prodcut.max_cut_date,

			prodscrreq.req_scr_qty,
			prodscrrcv.rcv_scr_qty,
			prodscrrcv.min_rcv_scr_date,
			prodscrrcv.max_rcv_scr_date,

			prodsew.sew_qty,
			prodsew.min_sew_date,
			prodsew.max_sew_date,

			carton.car_qty,
			carton.min_car_date,
			carton.max_car_date,

			inspec.insp_pass_qty,
			inspec.min_insp_date,
			inspec.max_insp_date
			from
			sales_orders
			left join jobs on jobs.id=sales_orders.job_id
			left join styles on styles.id=jobs.style_id
			left join buyers on buyers.id=styles.buyer_id
			left join companies on companies.id=jobs.company_id
			left join companies  pcompanies on pcompanies.id=sales_orders.produced_company_id
			left join teammembers teamleaders on styles.teammember_id=teamleaders.id
			left join users  teamleadernames on teamleadernames.id=teamleaders.user_id
			left join teammembers  on styles.factory_merchant_id=teammembers.id
			left join users on users.id=teammembers.user_id
			left join (
			SELECT 
			sales_orders.id as sales_order_id,
			sum(sales_order_gmt_color_sizes.qty) as po_qty,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders 
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			where sales_orders.order_status !=2 
			group by sales_orders.id
			) poqties on poqties.sales_order_id= sales_orders.id 

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=29
			) tnadyedyarns on tnadyedyarns.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=24
			) tnayarns on tnayarns.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=52
			) tnayarnisus on tnayarnisus.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=30
			) tnaknits on tnaknits.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=32
			) tnadyeings on tnadyeings.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date,
			tna_ords.acl_start_date as min_aop_date,
			tna_ords.acl_end_date as max_aop_date
			from
			tna_ords
			where tna_ords.tna_task_id=34
			) tnaaops on tnaaops.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=35
			) tnadyeingfinish on tnadyeingfinish.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date,
			tna_ords.acl_start_date as min_trim_date,
			tna_ords.acl_end_date as max_trim_date
			from
			tna_ords
			where tna_ords.tna_task_id=37
			) tnatrims on tnatrims.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date,
			tna_ords.acl_start_date as min_pp_date,
			tna_ords.acl_end_date as max_pp_date
			from
			tna_ords
			where tna_ords.tna_task_id=7
			) tnapps on tnapps.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=40
			) tnacuts on tnacuts.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=42
			) tnaembsps on tnaembsps.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=43
			) tnasews on tnasews.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=45
			) tnafins on tnafins.sales_order_id=sales_orders.id

			left join(
			select 
			tna_ords.sales_order_id,
			tna_ords.tna_start_date,
			tna_ords.tna_end_date
			from
			tna_ords
			where tna_ords.tna_task_id=47
			) tnainsps on tnainsps.sales_order_id=sales_orders.id

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
			where sales_orders.order_status !=2 
			and style_fabrications.fabric_look_id in(5,8,10,15,20)
			group by sales_orders.id
			) dyedyarnrq on dyedyarnrq.sales_order_id= sales_orders.id 

			left join (
			SELECT 
			sales_orders.id as sales_order_id,
			sum(inv_yarn_rcv_items.qty) as yarn_rcv,
			min(inv_rcvs.receive_date) min_receive_date,
			max(inv_rcvs.receive_date) max_receive_date
			FROM sales_orders 
			join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
			join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.id = budget_yarn_dyeing_cons.id
			join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id
			join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
			join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_isu_item_id = inv_yarn_isu_items.id
			join inv_yarn_rcvs on inv_yarn_rcvs.id = inv_yarn_rcv_items.inv_yarn_rcv_id
			join inv_rcvs on inv_rcvs.id = inv_yarn_rcvs.inv_rcv_id

			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			where   inv_isus.isu_against_id=9 and inv_yarn_isu_items.deleted_at is null and inv_rcvs.receive_against_id=9 and inv_yarn_rcv_items.deleted_at is null 
			group by sales_orders.id
			) dyedyarnrcv on  dyedyarnrcv.sales_order_id=sales_orders.id


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
			where sales_orders.order_status !=2 
			group by sales_orders.id
			) yarnrq on yarnrq.sales_order_id= sales_orders.id 



			left join (
			select 
			po_yarn_item_bom_qties.sale_order_id as sales_order_id,
			sum(inv_yarn_rcv_item_sos.qty) yarn_rcv,
			min(inv_rcvs.receive_date) min_receive_date,
			max(inv_rcvs.receive_date) max_receive_date
			from
			po_yarn_item_bom_qties
			join inv_yarn_rcv_item_sos on inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id=po_yarn_item_bom_qties.id
			join inv_yarn_rcv_items on inv_yarn_rcv_item_sos.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
			join inv_yarn_rcvs on inv_yarn_rcv_items.inv_yarn_rcv_id=inv_yarn_rcvs.id
			join inv_rcvs on inv_yarn_rcvs.inv_rcv_id=inv_rcvs.id
			where po_yarn_item_bom_qties.deleted_at is null and  inv_yarn_rcv_item_sos.deleted_at is null
			group by po_yarn_item_bom_qties.sale_order_id
			) yarnrcv on  yarnrcv.sales_order_id=sales_orders.id

			left join (
				SELECT 
				sales_orders.id as sales_order_id,
				sum(inv_yarn_isu_items.qty) as yarn_isu,
				min(inv_isus.issue_date) min_issue_date,
				max(inv_isus.issue_date) max_issue_date
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
			) yarnisu on  yarnisu.sales_order_id=sales_orders.id

			left join (
			SELECT 
			sales_orders.id as sales_order_id,
			sum(budget_fabric_cons.grey_fab) as grey_fab_req,
			sum(budget_fabric_cons.fin_fab) as fin_fab_req
			FROM sales_orders 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
			join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id

			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			where sales_orders.order_status !=2 
			group by sales_orders.id
			) fabricrq on fabricrq.sales_order_id= sales_orders.id 


			left join(
			select
			m.sales_order_id,
			sum(m.qc_pass_qty) as knit_qty,
			sum(m.roll_weight) as prod_knit_qty,
			max(m.prod_date) as max_knit_date,
			min(m.prod_date) as min_knit_date 
			from 
			(
			select
			prod_knits.prod_date,
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
			sum(prod_batch_rolls.qty) as dyeing_qty,
			min(prod_batches.unloaded_at) min_dyeing_date,
			max(prod_batches.unloaded_at) max_dyeing_date
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
			group by
			sales_orders.id
			) proddyeing on proddyeing.sales_order_id=sales_orders.id


			left join (
			select 
			sales_orders.id as sales_order_id,
			sum(prod_batch_finish_qc_rolls.qty) as dyeingfinish_qty,
			min(prod_batch_finish_qcs.posting_date) as min_dyeingfinish_date,
			max(prod_batch_finish_qcs.posting_date) as max_dyeingfinish_date
			from 
			prod_batches
			join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
			join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_roll_id=prod_batch_rolls.id
			join prod_batch_finish_qcs on prod_batch_finish_qc_rolls.prod_batch_finish_qc_id=prod_batch_finish_qcs.id
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
			group by
			sales_orders.id
			)prodfinish on prodfinish.sales_order_id=sales_orders.id




			left join (
			SELECT 
			sales_orders.id as sales_order_id,
			sum(prod_gmt_cutting_qties.qty) as cut_qty,
			min(prod_gmt_cuttings.cut_qc_date) as min_cut_date,
			max(prod_gmt_cuttings.cut_qc_date) as max_cut_date
			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
			group by 
			sales_orders.id
			) prodcut on  prodcut.sales_order_id=sales_orders.id


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
			group by sales_order_gmt_color_sizes.sale_order_id
			) prodscrreq on  prodscrreq.sales_order_id=sales_orders.id




			left join (
			SELECT 
			sales_orders.id as sales_order_id,
			sum(prod_gmt_print_rcv_qties.qty) as rcv_scr_qty,
			min(prod_gmt_print_rcvs.receive_date) as min_rcv_scr_date,
			max(prod_gmt_print_rcvs.receive_date) as max_rcv_scr_date
			FROM prod_gmt_print_rcvs
			join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id
			group by 
			sales_orders.id
			) prodscrrcv on  prodscrrcv.sales_order_id=sales_orders.id

			left join (
			SELECT 
			sales_orders.id as sales_order_id,
			sum(prod_gmt_sewing_qties.qty) as sew_qty,
			min(prod_gmt_sewings.sew_qc_date) as min_sew_date,
			max(prod_gmt_sewings.sew_qc_date) as max_sew_date
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id
			group by 
			sales_orders.id
			) prodsew on  prodsew.sales_order_id=sales_orders.id



			left join (
			SELECT 
			sales_orders.id as sales_order_id,
			sum(style_pkg_ratios.qty) as car_qty, 
			min(prod_gmt_carton_entries.carton_date) as min_car_date,
			max(prod_gmt_carton_entries.carton_date) as max_car_date
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) carton on  carton.sales_order_id=sales_orders.id



			left join (
			select 
			sales_orders.id as sales_order_id,
			sum(prod_gmt_inspection_orders.qty) as insp_pass_qty,
			sum(prod_gmt_inspection_orders.re_check_qty) as insp_re_check_qty,
			sum(prod_gmt_inspection_orders.failed_qty) as insp_faild_qty,
			min(prod_gmt_inspections.inspection_date) as min_insp_date,
			max(prod_gmt_inspections.inspection_date) as max_insp_date
			from
			prod_gmt_inspections
			join prod_gmt_inspection_orders on prod_gmt_inspection_orders.prod_gmt_inspection_id=prod_gmt_inspections.id
			join sales_order_countries on sales_order_countries.id=prod_gmt_inspections.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by
			sales_orders.id
			) inspec on  inspec.sales_order_id=sales_orders.id

			where 
			sales_orders.order_status !=2
			and sales_orders.deleted_at is null
			--and sales_orders.ship_date>=TO_DATE('04/01/2021 00:00:00', 'MM/DD/YYYY HH24:MI:SS')
			--and sales_orders.ship_date<=TO_DATE('04/30/2021 00:00:00', 'MM/DD/YYYY HH24:MI:SS')
			and sales_orders.deleted_at is null
			$company $buyer  $datefrom  $dateto
			order by 
			sales_orders.ship_date
			
			")
        )
        ->map(function($data) use($tnatask) {
        	$today=date('Y-m-d');
			$receive_date = Carbon::parse($data->receive_date);
			$ship_date = Carbon::parse($data->ship_date);
			$diff = $receive_date->diffInDays($ship_date);
			if($diff >1){
			$diff.=" Days";
			}else{
			$diff.=" Day";
			}
			$data->lead_time=$diff;
        	$data->ship_date=date('d-M-Y',strtotime($data->ship_date));

        	// -------------Dyed Yarn---------------
        	$dyed_yarn_per_req=$tnatask[29];
        	$dyed_yarn_per=$data->dyed_yarn_req?($data->dyed_yarn_rcv/$data->dyed_yarn_req)*100:0;
        	if($dyed_yarn_per>=$dyed_yarn_per_req){
        	   $data->max_dyed_yarn_receive_date=$data->max_dyed_yarn_receive_date?date('d-M-Y',strtotime($data->max_dyed_yarn_receive_date)):'';
        	}
        	else{
        		$data->max_dyed_yarn_receive_date='';
        	}

            $data->dyed_yarn_start_delay='';
            $dyed_yarn_start_diff=0;
        	if($data->min_dyed_yarn_receive_date){
	            $dyed_yarn_start_diff=$this->dateDiff($data->min_dyed_yarn_receive_date,$data->dyed_yarn_start_date);
	            $data->dyed_yarn_start_delay=$dyed_yarn_start_diff;
        	}
        	else{
	        	$dyed_yarn_start_diff=$this->dateDiff($today,$data->dyed_yarn_start_date);
	        	if($dyed_yarn_start_diff<0){
	        		$data->dyed_yarn_start_delay=$dyed_yarn_start_diff;
	        	}
        	}

        	$data->dyed_yarn_start_diff=$dyed_yarn_start_diff;

        	$data->dyed_yarn_end_delay='';
            $dyed_yarn_end_diff=0;
        	if($data->max_dyed_yarn_receive_date){
	            $dyed_yarn_end_diff=$this->dateDiff($data->max_dyed_yarn_receive_date,$data->dyed_yarn_end_date);
	            $data->dyed_yarn_end_delay=$dyed_yarn_end_diff;
        	}
        	else{
	        	$dyed_yarn_end_diff=$this->dateDiff($today,$data->dyed_yarn_end_date);
	        	if($dyed_yarn_end_diff<0){
	        		$data->dyed_yarn_end_delay=$dyed_yarn_end_diff;
	        	}
        	}

        	$data->dyed_yarn_end_diff=$dyed_yarn_end_diff;

        	$data->dyed_yarn_start_date=$data->dyed_yarn_start_date?date('d-M-Y',strtotime($data->dyed_yarn_start_date)):'--';
        	$data->dyed_yarn_end_date=$data->dyed_yarn_end_date?date('d-M-Y',strtotime($data->dyed_yarn_end_date)):'--';
        	$data->min_dyed_yarn_receive_date=$data->min_dyed_yarn_receive_date?date('d-M-Y',strtotime($data->min_dyed_yarn_receive_date)):'--';
        	$data->max_dyed_yarn_receive_date=$data->max_dyed_yarn_receive_date?date('d-M-Y',strtotime($data->max_dyed_yarn_receive_date)):number_format($dyed_yarn_per,2)." %";

        	// -------------Yarn Inhouse---------------
        	$yarn_per_req=isset($tnatask[24])?$tnatask[24]:0;
        	$yarn_per=$data->yarn_req?($data->yarn_rcv/$data->yarn_req)*100:0;
        	if($yarn_per>=$yarn_per_req){
        	   $data->max_yarn_receive_date=$data->max_yarn_receive_date?date('d-M-Y',strtotime($data->max_yarn_receive_date)):'';
        	}
        	else{
        		$data->max_yarn_receive_date='';
        	}

            $data->yarn_start_delay='';
            $yarn_start_diff=0;
        	if($data->min_yarn_receive_date){
	            $yarn_start_diff=$this->dateDiff($data->min_yarn_receive_date,$data->yarn_start_date);
	            $data->yarn_start_delay=$yarn_start_diff;
        	}
        	else{
	        	$yarn_start_diff=$this->dateDiff($today,$data->yarn_start_date);
	        	if($yarn_start_diff<0){
	        		$data->yarn_start_delay=$yarn_start_diff;
	        	}
        	}

        	$data->yarn_start_diff=$yarn_start_diff;

        	$data->yarn_end_delay='';
            $yarn_end_diff=0;
        	if($data->max_yarn_receive_date){
	            $yarn_end_diff=$this->dateDiff($data->max_yarn_receive_date,$data->yarn_end_date);
	            $data->yarn_end_delay=$yarn_end_diff;
        	}
        	else{
	        	$yarn_end_diff=$this->dateDiff($today,$data->yarn_end_date);
	        	if($yarn_end_diff<0){
	        		$data->yarn_end_delay=$yarn_end_diff;
	        	}
        	}

        	$data->yarn_end_diff=$yarn_end_diff;

        	$data->yarn_start_date=$data->yarn_start_date?date('d-M-Y',strtotime($data->yarn_start_date)):'--';
        	$data->yarn_end_date=$data->yarn_end_date?date('d-M-Y',strtotime($data->yarn_end_date)):'--';
        	$data->min_yarn_receive_date=$data->min_yarn_receive_date?date('d-M-Y',strtotime($data->min_yarn_receive_date)):'--';
        	$data->max_yarn_receive_date=$data->max_yarn_receive_date?date('d-M-Y',strtotime($data->max_yarn_receive_date)):number_format($yarn_per,2)." %";


        	// -------------Yarn Issue---------------
			$yarn_isu_per_req=$tnatask[52];
			$yarn_isu_per=$data->yarn_isu_req?($data->yarn_isu/$data->yarn_isu_req)*100:0;
			if($yarn_isu_per>=$yarn_isu_per_req){
			$data->max_yarn_isu_date=$data->max_yarn_isu_date?date('d-M-Y',strtotime($data->max_yarn_isu_date)):'';
			}
			else{
			$data->max_yarn_isu_date='';
			}

			$data->yarn_isu_start_delay='';
			$yarn_isu_start_diff=0;
			if($data->min_yarn_isu_date){
			$yarn_isu_start_diff=$this->dateDiff($data->min_yarn_isu_date,$data->yarn_isu_start_date);
			$data->yarn_isu_start_delay=$yarn_isu_start_diff;
			}
			else{
			$yarn_isu_start_diff=$this->dateDiff($today,$data->yarn_isu_start_date);
			if($yarn_isu_start_diff<0){
			$data->yarn_isu_start_delay=$yarn_isu_start_diff;
			}
			}

			$data->yarn_isu_start_diff=$yarn_isu_start_diff;

			$data->yarn_isu_end_delay='';
			$yarn_isu_end_diff=0;
			if($data->max_yarn_isu_date){
			$yarn_isu_end_diff=$this->dateDiff($data->max_yarn_isu_date,$data->yarn_isu_end_date);
			$data->yarn_isu_end_delay=$yarn_isu_end_diff;
			}
			else{
			$yarn_isu_end_diff=$this->dateDiff($today,$data->yarn_isu_end_date);
			if($yarn_isu_end_diff<0){
			$data->yarn_isu_end_delay=$yarn_isu_end_diff;
			}
			}

			$data->yarn_isu_end_diff=$yarn_isu_end_diff;

			$data->yarn_isu_start_date=$data->yarn_isu_start_date?date('d-M-Y',strtotime($data->yarn_isu_start_date)):'--';
			$data->yarn_isu_end_date=$data->yarn_isu_end_date?date('d-M-Y',strtotime($data->yarn_isu_end_date)):'--';
			$data->min_yarn_isu_date=$data->min_yarn_isu_date?date('d-M-Y',strtotime($data->min_yarn_isu_date)):'--';
			$data->max_yarn_isu_date=$data->max_yarn_isu_date?date('d-M-Y',strtotime($data->max_yarn_isu_date)):number_format($yarn_isu_per,2)." %";


        	// -------------Knit---------------
        	$knit_per_req=$tnatask[30];
        	$knit_per=$data->grey_fab_req?($data->knit_qty/$data->grey_fab_req)*100:0;
        	if($knit_per>=$knit_per_req){
        	   $data->max_knit_date=$data->max_knit_date?date('d-M-Y',strtotime($data->max_knit_date)):'';
        	}
        	else{
        		$data->max_knit_date='';
        	}

        	$data->knit_start_delay='';
            $knit_start_diff=0;
        	if($data->min_knit_date){
	            $knit_start_diff=$this->dateDiff($data->min_knit_date,$data->knit_start_date);
	            $data->knit_start_delay=$knit_start_diff;
        	}
        	else{
	        	$knit_start_diff=$this->dateDiff($today,$data->knit_start_date);
	        	if($knit_start_diff < 0){
	        		$data->knit_start_delay=$knit_start_diff;
	        	}
        	}
        	$data->knit_start_diff=$knit_start_diff;

        	$data->knit_end_delay='';
            $knit_end_diff=0;
        	if($data->max_knit_date){
	            $knit_end_diff=$this->dateDiff($data->max_knit_date,$data->knit_end_date);
	            $data->knit_end_delay=$knit_end_diff;
        	}
        	else{
	        	$knit_end_diff=$this->dateDiff($today,$data->knit_end_date);
	        	if($knit_end_diff<0){
	        		$data->knit_end_delay=$knit_end_diff;
	        	}
        	}

        	$data->knit_end_diff=$knit_end_diff;





        	$data->knit_start_date=$data->knit_start_date?date('d-M-Y',strtotime($data->knit_start_date)):'--';
        	$data->knit_end_date=$data->knit_end_date?date('d-M-Y',strtotime($data->knit_end_date)):'--';
        	$data->min_knit_date=$data->min_knit_date?date('d-M-Y',strtotime($data->min_knit_date)):'--';
        	$data->max_knit_date=$data->max_knit_date?date('d-M-Y',strtotime($data->max_knit_date)):number_format($knit_per,2)." %";

        	// -------------Dyeing---------------

        	$dyeing_per_req=$tnatask[32];
        	$dyeing_per=$data->grey_fab_req?($data->dyeing_qty/$data->grey_fab_req)*100:0;
        	if($dyeing_per>=$dyeing_per_req){
        	   $data->max_dyeing_date=$data->max_dyeing_date?date('d-M-Y',strtotime($data->max_dyeing_date)):'';
        	}
        	else{
        		$data->max_dyeing_date='';
        	}

        	$data->dyeing_start_delay='';
            $dyeing_start_diff=0;
        	if($data->dyeing_start_date){
	            $dyeing_start_diff=$this->dateDiff($data->min_dyeing_date,$data->dyeing_start_date);
	            $data->dyeing_start_delay=$dyeing_start_diff;
        	}
        	else{
	        	$dyeing_start_diff=$this->dateDiff($today,$data->dyeing_start_date);
	        	if($dyeing_start_diff < 0){
	        		$data->dyeing_start_delay=$dyeing_start_diff;
	        	}
        	}
        	$data->dyeing_start_diff=$dyeing_start_diff;

        	$data->dyeing_end_delay='';
            $dyeing_end_diff=0;
        	if($data->max_dyeing_date){
	            $dyeing_end_diff=$this->dateDiff($data->max_dyeing_date,$data->dyeing_end_date);
	            $data->dyeing_end_delay=$dyeing_end_diff;
        	}
        	else{
	        	$dyeing_end_diff=$this->dateDiff($today,$data->dyeing_end_date);
	        	if($dyeing_end_diff<0){
	        		$data->dyeing_end_delay=$dyeing_end_diff;
	        	}
        	}

        	$data->dyeing_end_diff=$dyeing_end_diff;

        	$data->dyeing_start_date=$data->dyeing_start_date?date('d-M-Y',strtotime($data->dyeing_start_date)):'--';
        	$data->dyeing_end_date=$data->dyeing_end_date?date('d-M-Y',strtotime($data->dyeing_end_date)):'--';
        	$data->min_dyeing_date=$data->min_dyeing_date?date('d-M-Y',strtotime($data->min_dyeing_date)):'--';
        	$data->max_dyeing_date=$data->max_dyeing_date?date('d-M-Y',strtotime($data->max_dyeing_date)):number_format($dyeing_per,2)." %";

        	// -------------AOP---------------

        	$data->aop_start_delay='';
            $aop_start_diff=0;
        	if($data->aop_start_date){
	            $aop_start_diff=$this->dateDiff($data->min_aop_date,$data->aop_start_date);
	            $data->aop_start_delay=$aop_start_diff;
        	}
        	else{
	        	$aop_start_diff=$this->dateDiff($today,$data->aop_start_date);
	        	if($aop_start_diff < 0){
	        		$data->aop_start_delay=$aop_start_diff;
	        	}
        	}
        	$data->aop_start_diff=$aop_start_diff;

        	$data->aop_end_delay='';
            $aop_end_diff=0;
        	if($data->max_aop_date){
	            $aop_end_diff=$this->dateDiff($data->max_aop_date,$data->aop_end_date);
	            $data->aop_end_delay=$aop_end_diff;
        	}
        	else{
	        	$aop_end_diff=$this->dateDiff($today,$data->aop_end_date);
	        	if($aop_end_diff<0){
	        		$data->aop_end_delay=$aop_end_diff;
	        	}
        	}

        	$data->aop_end_diff=$aop_end_diff;


        	$data->aop_start_date=$data->aop_start_date?date('d-M-Y',strtotime($data->aop_start_date)):'--';
        	$data->aop_end_date=$data->aop_end_date?date('d-M-Y',strtotime($data->aop_end_date)):'--';
        	$data->min_aop_date=$data->min_aop_date?date('d-M-Y',strtotime($data->min_aop_date)):'--';
        	$data->max_aop_date=$data->max_aop_date?date('d-M-Y',strtotime($data->max_aop_date)):'0.00 %';

        	// -------------Dyeing Finishing---------------

        	$dyeingfinish_per_req=$tnatask[35];
        	$dyeingfinish_per=$data->fin_fab_req?($data->dyeingfinish_qty/$data->fin_fab_req)*100:0;
        	if($dyeingfinish_per>=$dyeingfinish_per_req){
        	   $data->max_dyeingfinish_date=$data->max_dyeingfinish_date?date('d-M-Y',strtotime($data->max_dyeingfinish_date)):'';
        	}
        	else{
        		$data->max_dyeingfinish_date='';
        	}

        	$data->dyeingfinish_start_delay='';
            $dyeingfinish_start_diff=0;
        	if($data->dyeingfinish_start_date){
	            $dyeingfinish_start_diff=$this->dateDiff($data->min_dyeingfinish_date,$data->dyeingfinish_start_date);
	            $data->dyeingfinish_start_delay=$dyeingfinish_start_diff;
        	}
        	else{
	        	$dyeingfinish_start_diff=$this->dateDiff($today,$data->dyeingfinish_start_date);
	        	if($dyeingfinish_start_diff < 0){
	        		$data->dyeingfinish_start_delay=$dyeingfinish_start_diff;
	        	}
        	}
        	$data->dyeingfinish_start_diff=$dyeingfinish_start_diff;

        	$data->dyeingfinish_end_delay='';
            $dyeingfinish_end_diff=0;
        	if($data->max_dyeingfinish_date){
	            $dyeingfinish_end_diff=$this->dateDiff($data->max_dyeingfinish_date,$data->dyeingfinish_end_date);
	            $data->dyeingfinish_end_delay=$dyeingfinish_end_diff;
        	}
        	else{
	        	$dyeingfinish_end_diff=$this->dateDiff($today,$data->dyeingfinish_end_date);
	        	if($dyeingfinish_end_diff<0){
	        		$data->dyeingfinish_end_delay=$dyeingfinish_end_diff;
	        	}
        	}

        	$data->dyeingfinish_end_diff=$dyeingfinish_end_diff;

        	$data->dyeingfinish_start_date=$data->dyeingfinish_start_date?date('d-M-Y',strtotime($data->dyeingfinish_start_date)):'--';
        	$data->dyeingfinish_end_date=$data->dyeingfinish_end_date?date('d-M-Y',strtotime($data->dyeingfinish_end_date)):'--';

        	$data->min_dyeingfinish_date=$data->min_dyeingfinish_date?date('d-M-Y',strtotime($data->min_dyeingfinish_date)):'--';
        	$data->max_dyeingfinish_date=$data->max_dyeingfinish_date?date('d-M-Y',strtotime($data->max_dyeingfinish_date)):number_format($dyeingfinish_per,2)." %";

        	

        	// -------------Trim---------------
        	$data->trim_start_delay='';
            $trim_start_diff=0;
        	if($data->trim_start_date){
	            $trim_start_diff=$this->dateDiff($data->min_trim_date,$data->trim_start_date);
	            $data->trim_start_delay=$trim_start_diff;
        	}
        	else{
	        	$trim_start_diff=$this->dateDiff($today,$data->trim_start_date);
	        	if($trim_start_diff < 0){
	        		$data->trim_start_delay=$trim_start_diff;
	        	}
        	}
        	$data->trim_start_diff=$trim_start_diff;

        	$data->trim_end_delay='';
            $trim_end_diff=0;
        	if($data->max_trim_date){
	            $trim_end_diff=$this->dateDiff($data->max_trim_date,$data->trim_end_date);
	            $data->trim_end_delay=$trim_end_diff;
        	}
        	else{
	        	$trim_end_diff=$this->dateDiff($today,$data->trim_end_date);
	        	if($trim_end_diff<0){
	        		$data->trim_end_delay=$trim_end_diff;
	        	}
        	}

        	$data->trim_end_diff=$trim_end_diff;

        	$data->trim_start_date=$data->trim_start_date?date('d-M-Y',strtotime($data->trim_start_date)):'--';
        	$data->trim_end_date=$data->trim_end_date?date('d-M-Y',strtotime($data->trim_end_date)):'--';
        	$data->min_trim_date=$data->min_trim_date?date('d-M-Y',strtotime($data->min_trim_date)):'--';
        	$data->max_trim_date=$data->max_trim_date?date('d-M-Y',strtotime($data->max_trim_date)):'0.00 %';

        	// -------------PP---------------

        	$data->pp_start_delay='';
            $pp_start_diff=0;
        	if($data->pp_start_date){
	            $pp_start_diff=$this->dateDiff($data->min_pp_date,$data->pp_start_date);
	            $data->pp_start_delay=$pp_start_diff;
        	}
        	else{
	        	$pp_start_diff=$this->dateDiff($today,$data->pp_start_date);
	        	if($pp_start_diff < 0){
	        		$data->pp_start_delay=$pp_start_diff;
	        	}
        	}
        	$data->pp_start_diff=$pp_start_diff;

        	$data->pp_end_delay='';
            $pp_end_diff=0;
        	if($data->max_pp_date){
	            $pp_end_diff=$this->dateDiff($data->max_pp_date,$data->pp_end_date);
	            $data->pp_end_delay=$pp_end_diff;
        	}
        	else{
	        	$pp_end_diff=$this->dateDiff($today,$data->pp_end_date);
	        	if($pp_end_diff<0){
	        		$data->pp_end_delay=$pp_end_diff;
	        	}
        	}

        	$data->pp_end_diff=$pp_end_diff;

        	$data->pp_start_date=$data->pp_start_date?date('d-M-Y',strtotime($data->pp_start_date)):'--';
        	$data->pp_end_date=$data->pp_end_date?date('d-M-Y',strtotime($data->pp_end_date)):'--';
        	$data->min_pp_date=$data->min_pp_date?date('d-M-Y',strtotime($data->min_pp_date)):'--';
        	$data->max_pp_date=$data->max_pp_date?date('d-M-Y',strtotime($data->max_pp_date)):'0.00 %';

        	// -------------Cuting---------------

        	$cut_per_req=$tnatask[40];
        	$cut_per=$data->plan_cut_qty?($data->cut_qty/$data->plan_cut_qty)*100:0;
        	if($cut_per>=$cut_per_req){
        	   $data->max_cut_date=$data->max_cut_date?date('d-M-Y',strtotime($data->max_cut_date)):'--';
        	}
        	else{
        		$data->max_cut_date='';//number_format($yarn_per,2)."%";
        	}

        	$data->cut_start_delay='';
            $cut_start_diff=0;
        	if($data->cut_start_date){
	            $cut_start_diff=$this->dateDiff($data->min_cut_date,$data->cut_start_date);
	            $data->cut_start_delay=$cut_start_diff;
        	}
        	else{
	        	$cut_start_diff=$this->dateDiff($today,$data->cut_start_date);
	        	if($cut_start_diff < 0){
	        		$data->cut_start_delay=$cut_start_diff;
	        	}
        	}
        	$data->cut_start_diff=$cut_start_diff;

        	$data->cut_end_delay='';
            $cut_end_diff=0;
        	if($data->max_cut_date){
	            $cut_end_diff=$this->dateDiff($data->max_cut_date,$data->cut_end_date);
	            $data->cut_end_delay=$cut_end_diff;
        	}
        	else{
	        	$cut_end_diff=$this->dateDiff($today,$data->cut_end_date);
	        	if($cut_end_diff<0){
	        		$data->cut_end_delay=$cut_end_diff;
	        	}
        	}

        	$data->cut_end_diff=$cut_end_diff;




        	$data->cut_start_date=$data->cut_start_date?date('d-M-Y',strtotime($data->cut_start_date)):'--';
        	$data->cut_end_date=$data->cut_end_date?date('d-M-Y',strtotime($data->cut_end_date)):'--';
        	$data->min_cut_date=$data->min_cut_date?date('d-M-Y',strtotime($data->min_cut_date)):'--';
        	$data->max_cut_date=$data->max_cut_date?date('d-M-Y',strtotime($data->max_cut_date)):number_format($cut_per,2)." %";

        	// -------------Screen Print---------------

        	$embsp_per_req=$tnatask[42];
        	$embsp_per=$data->req_scr_qty?($data->rcv_scr_qty/$data->req_scr_qty)*100:0;
        	if($embsp_per>=$embsp_per_req){
        	   $data->max_rcv_scr_date=$data->max_rcv_scr_date?date('d-M-Y',strtotime($data->max_rcv_scr_date)):'';
        	}
        	else{
        		$data->max_rcv_scr_date='';
        	}


            $data->embsp_start_delay='';
            $embsp_start_diff=0;
        	if($data->embsp_start_date){
	            $embsp_start_diff=$this->dateDiff($data->min_rcv_scr_date,$data->embsp_start_date);
	            $data->embsp_start_delay=$embsp_start_diff;
        	}
        	else{
	        	$embsp_start_diff=$this->dateDiff($today,$data->embsp_start_date);
	        	if($embsp_start_diff < 0){
	        		$data->embsp_start_delay=$embsp_start_diff;
	        	}
        	}
        	$data->embsp_start_diff=$embsp_start_diff;

        	$data->embsp_end_delay='';
            $embsp_end_diff=0;
        	if($data->max_rcv_scr_date){
	            $embsp_end_diff=$this->dateDiff($data->max_rcv_scr_date,$data->embsp_end_date);
	            $data->embsp_end_delay=$embsp_end_diff;
        	}
        	else{
	        	$embsp_end_diff=$this->dateDiff($today,$data->embsp_end_date);
	        	if($embsp_end_diff<0){
	        		$data->embsp_end_delay=$embsp_end_diff;
	        	}
        	}

        	$data->embsp_end_diff=$embsp_end_diff;

        	$data->embsp_start_date=$data->embsp_start_date?date('d-M-Y',strtotime($data->embsp_start_date)):'--';
        	$data->embsp_end_date=$data->embsp_end_date?date('d-M-Y',strtotime($data->embsp_end_date)):'--';
        	$data->min_rcv_scr_date=$data->min_rcv_scr_date?date('d-M-Y',strtotime($data->min_rcv_scr_date)):'--';
        	$data->max_rcv_scr_date=$data->max_rcv_scr_date?date('d-M-Y',strtotime($data->max_rcv_scr_date)):number_format($embsp_per,2)." %";
        	


        	// -------------Sewing---------------

        	$sew_per_req=$tnatask[43];
        	$sew_per=$data->po_qty?($data->sew_qty/$data->po_qty)*100:0;
        	if($sew_per>=$sew_per_req){
        	   $data->max_sew_date=$data->max_sew_date?date('d-M-Y',strtotime($data->max_sew_date)):'';
        	}
        	else{
        		$data->max_sew_date='';
        	}

        	$data->sew_start_delay='';
            $sew_start_diff=0;
        	if($data->sew_start_date){
	            $sew_start_diff=$this->dateDiff($data->min_sew_date,$data->sew_start_date);
	            $data->sew_start_delay=$sew_start_diff;
        	}
        	else{
	        	$sew_start_diff=$this->dateDiff($today,$data->sew_start_date);
	        	if($sew_start_diff < 0){
	        		$data->sew_start_delay=$sew_start_diff;
	        	}
        	}
        	$data->sew_start_diff=$sew_start_diff;

        	$data->sew_end_delay='';
            $sew_end_diff=0;
        	if($data->max_sew_date){
	            $sew_end_diff=$this->dateDiff($data->max_sew_date,$data->sew_end_date);
	            $data->sew_end_delay=$sew_end_diff;
        	}
        	else{
	        	$sew_end_diff=$this->dateDiff($today,$data->sew_end_date);
	        	if($sew_end_diff<0){
	        		$data->sew_end_delay=$sew_end_diff;
	        	}
        	}

        	$data->sew_end_diff=$sew_end_diff;




        	$data->sew_start_date=$data->sew_start_date?date('d-M-Y',strtotime($data->sew_start_date)):'--';
        	$data->sew_end_date=$data->sew_end_date?date('d-M-Y',strtotime($data->sew_end_date)):'--';
        	$data->min_sew_date=$data->min_sew_date?date('d-M-Y',strtotime($data->min_sew_date)):'--';
        	$data->max_sew_date=$data->max_sew_date?date('d-M-Y',strtotime($data->max_sew_date)):number_format($sew_per,2)." %";

        	// ------------- GMT Finishing---------------

        	$car_per_req=$tnatask[45];
        	$car_per=$data->po_qty?($data->car_qty/$data->po_qty)*100:0;
        	if($car_per>=$car_per_req){
        	   $data->max_car_date=$data->max_car_date?date('d-M-Y',strtotime($data->max_car_date)):'';
        	}
        	else{
        		$data->max_car_date='';
        	}

        	$data->car_start_delay='';
            $car_start_diff=0;
        	if($data->fin_start_date){
	            $car_start_diff=$this->dateDiff($data->min_car_date,$data->fin_start_date);
	            $data->car_start_delay=$car_start_diff;
        	}
        	else{
	        	$car_start_diff=$this->dateDiff($today,$data->fin_start_date);
	        	if($car_start_diff < 0){
	        		$data->car_start_delay=$car_start_diff;
	        	}
        	}
        	$data->car_start_diff=$car_start_diff;

        	$data->car_end_delay='';
            $car_end_diff=0;
        	if($data->max_car_date){
	            $car_end_diff=$this->dateDiff($data->max_car_date,$data->fin_end_date);
	            $data->car_end_delay=$car_end_diff;
        	}
        	else{
	        	$car_end_diff=$this->dateDiff($today,$data->fin_end_date);
	        	if($car_end_diff<0){
	        		$data->car_end_delay=$car_end_diff;
	        	}
        	}

        	$data->car_end_diff=$car_end_diff;



        	$data->fin_start_date=$data->fin_start_date?date('d-M-Y',strtotime($data->fin_start_date)):'--';
        	$data->fin_end_date=$data->fin_end_date?date('d-M-Y',strtotime($data->fin_end_date)):'--';
        	$data->min_car_date=$data->min_car_date?date('d-M-Y',strtotime($data->min_car_date)):'--';
        	$data->max_car_date=$data->max_car_date?date('d-M-Y',strtotime($data->max_car_date)):number_format($car_per,2)." %";

        	// -------------GMT Inspection---------------

        	$insp_per_req=$tnatask[47];
        	$insp_per=$data->po_qty?($data->insp_pass_qty/$data->po_qty)*100:0;
        	if($insp_per>=$insp_per_req){
        	   $data->max_insp_date=$data->max_insp_date?date('d-M-Y',strtotime($data->max_insp_date)):'';
        	}
        	else{
        		$data->max_insp_date='';//number_format($yarn_per,2)."%";
        	}

        	$data->insp_start_delay='';
            $insp_start_diff=0;
        	if($data->insp_start_date){
	            $insp_start_diff=$this->dateDiff($data->min_insp_date,$data->insp_start_date);
	            $data->insp_start_delay=$insp_start_diff;
        	}
        	else{
	        	$insp_start_diff=$this->dateDiff($today,$data->insp_start_date);
	        	if($insp_start_diff < 0){
	        		$data->insp_start_delay=$insp_start_diff;
	        	}
        	}
        	$data->insp_start_diff=$insp_start_diff;

        	$data->insp_end_delay='';
            $insp_end_diff=0;
        	if($data->max_insp_date){
	            $insp_end_diff=$this->dateDiff($data->max_insp_date,$data->insp_end_date);
	            $data->insp_end_delay=$insp_end_diff;
        	}
        	else{
	        	$insp_end_diff=$this->dateDiff($today,$data->insp_end_date);
	        	if($insp_end_diff<0){
	        		$data->insp_end_delay=$insp_end_diff;
	        	}
        	}

        	$data->insp_end_diff=$insp_end_diff;

        

        	$data->insp_start_date=$data->insp_start_date?date('d-M-Y',strtotime($data->insp_start_date)):'--';
        	$data->insp_end_date=$data->insp_end_date?date('d-M-Y',strtotime($data->insp_end_date)):'--';
        	$data->min_insp_date=$data->min_insp_date?date('d-M-Y',strtotime($data->min_insp_date)):'--';
        	$data->max_insp_date=$data->max_insp_date?date('d-M-Y',strtotime($data->max_insp_date)):number_format($insp_per,2)." %";

        	
        	$data->po_qty=number_format($data->po_qty,2);
        	return $data;
        });
        return $data;
    }

    public function reportData() {
		return response()->json($this->getData());
	}

	private function dateDiff($date1,$date2){
		$first = Carbon::parse($date1);
		$second = Carbon::parse($date2);
		$diff = $first->diffInDays($second,false);
		return $diff; 

	}
}
