<?php

namespace App\Http\Controllers\Report\FabricProduction;
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
use App\Repositories\Contracts\Util\GmtspartRepository;
use Illuminate\Support\Carbon;

class FabricProdProgressController extends Controller
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
    private $gmtspart;
	public function __construct(
		StyleRepository $style,
		CompanyRepository $company,
		BuyerRepository $buyer,
		BuyerNatureRepository $buyernature,
		UserRepository $user,
		ItemAccountRepository $itemaccount,
		AutoyarnRepository $autoyarn,
		TeamRepository $team,
		TeammemberRepository $teammember,
		GmtspartRepository $gmtspart
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
		$this->gmtspart = $gmtspart;

		//$this->middleware('auth');
		//$this->middleware('permission:view.orderprogressreports',['only' => ['create', 'index','show']]);
    }

    public function index() 
    {
		$from=request('date_from', 0);
        $to=request('date_to', 0);
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-',0);
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.FabricProduction.FabricProdProgress',['company'=>$company,'buyer'=>$buyer,'status'=>$status,'team'=>$team,'from'=>$from,'to'=>$to]);
    }

    public function reportData()
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

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;

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


		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);

		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        //$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        //$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
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
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

		$rows=collect(
			\DB::select("
			select 
				styles.id as style_id,
				styles.style_ref,
				styles.factory_merchant_id,
				buyers.id as buyer_id,
				buyers.name as buyer_name,
				jobs.job_no,
				companies.code as company_code,
				produced_company.code as produced_company_code,
				sales_orders.id as sale_order_id,
				sales_orders.sale_order_no,
				sales_orders.order_status,
				sales_orders.receive_date as sale_order_receive_date,
				sales_orders.ship_date,
				style_fabrications.id as style_fabrication_id,
				budget_fabrics.id as budget_fabric_id,
				gmtsparts.name as gmt_part_name,
				style_fabrications.fabric_look_id,
				style_fabrications.fabric_shape_id,
				style_fabrications.autoyarn_id,
				budget_fabrics.gsm_weight,
				budgetfabriccons.dia,
				budgetfabriccons.fabric_color,
				colors.name as fabric_color_name,
				budgetfabriccons.grey_fab,
				budgetfabriccons.fin_fab,
				kwo.kwo_qty,
				dwo.dwo_qty,
				inhyarnisu.inh_yarn_isu_qty,
				outyarnisu.out_yarn_isu_qty,
				prodknit.knit_qty,
				prodknit.prod_knit_qty,
				prodknit.knit_dlv_to_st_qty,
				prodknit.knit_rcv_by_st_qty,
				rcvbybatch.rcv_by_batch_qty,
				prodbatch.batch_qty,
				prodbatchload.load_qty,
				proddyeing.dyeing_qty,
				aopreq.aop_gery_fab,
				aopreq.aop_fin_fab,
				aopqc.aop_qc_qty,
				proddyeingqc.dyeing_qc_qty,
				proddyeingdlvtostore.dyeing_dlv_to_store_qty,
				proddyeingrcvtostore.dyeing_rcv_to_store_qty,
				proddyeingisucut.dyeing_isu_cut_qty
				
				from
				styles
				left join buyers on buyers.id=styles.buyer_id
				left join uoms on uoms.id=styles.uom_id
				left join seasons on seasons.id=styles.season_id
				left join teams on teams.id=styles.team_id
				left join teammembers on teammembers.id=styles.factory_merchant_id
				left join users on users.id=teammembers.user_id
				left join productdepartments on productdepartments.id=styles.productdepartment_id
				left join teammembers teamleaders on teamleaders.id=styles.teammember_id
				left join users teamleadernames on teamleadernames.id=teamleaders.user_id
				left join jobs  on jobs.style_id=styles.id
				left join companies  on companies.id=jobs.company_id
				left join sales_orders  on sales_orders.job_id=jobs.id
				left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
				join style_fabrications  on style_fabrications.style_id=styles.id
				join budget_fabrics on budget_fabrics.style_fabrication_id=style_fabrications.id
				join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id
				join autoyarns on autoyarns.id=style_fabrications.autoyarn_id
				left join (
				select
				sales_orders.id as sales_order_id, 
				budget_fabric_cons.budget_fabric_id,
				budget_fabric_cons.dia,
				budget_fabric_cons.fabric_color,
				sum(budget_fabric_cons.fin_fab) as fin_fab,
				sum(budget_fabric_cons.grey_fab) as grey_fab
				from
				budget_fabric_cons
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id=budget_fabric_cons.sales_order_gmt_color_size_id 
				join sales_order_countries on sales_order_countries.id=sales_order_gmt_color_sizes.sale_order_country_id 
				join sales_orders on sales_orders.id=sales_order_countries.sale_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				where 1=1 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				sales_orders.id, 
				budget_fabric_cons.budget_fabric_id,
				budget_fabric_cons.dia,
				budget_fabric_cons.fabric_color
				) budgetfabriccons on budgetfabriccons.budget_fabric_id=budget_fabrics.id and budgetfabriccons.sales_order_id=sales_orders.id

				left join (
				SELECT
				budget_fabric_prods.budget_fabric_id, 
				po_knit_service_item_qties.sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				sum(po_knit_service_item_qties.qty) as kwo_qty 
				FROM po_knit_service_item_qties 
				join po_knit_service_items on  po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
				and po_knit_service_items.deleted_at is null
				join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				where po_knit_service_item_qties.deleted_at is null $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				budget_fabric_prods.budget_fabric_id, 
				po_knit_service_item_qties.sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id
				) kwo on kwo.budget_fabric_id=budget_fabrics.id 
				and kwo.sales_order_id=sales_orders.id 
				and kwo.dia=budgetfabriccons.dia 
				and kwo.fabric_color_id=budgetfabriccons.fabric_color

				left join (
				SELECT
				budget_fabric_prods.budget_fabric_id, 
				po_dyeing_service_item_qties.sales_order_id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				sum(po_dyeing_service_item_qties.qty) as dwo_qty 
				FROM po_dyeing_service_item_qties 
				join po_dyeing_service_items on  po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
				and po_dyeing_service_items.deleted_at is null
				join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
				join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				where po_dyeing_service_item_qties.deleted_at is null  $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				budget_fabric_prods.budget_fabric_id, 
				po_dyeing_service_item_qties.sales_order_id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id
				) dwo on dwo.budget_fabric_id=budget_fabrics.id 
				and dwo.sales_order_id=sales_orders.id 
				and dwo.dia=budgetfabriccons.dia 
				and dwo.fabric_color_id=budgetfabriccons.fabric_color

				left join(
				select 
				sales_orders.id as sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id,
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
				join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by 
				sales_orders.id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				) inhyarnisu on  inhyarnisu.budget_fabric_id=budget_fabrics.id 
				and inhyarnisu.sales_order_id=sales_orders.id 
				and inhyarnisu.dia=budgetfabriccons.dia 
				and inhyarnisu.fabric_color_id=budgetfabriccons.fabric_color

				left join (
				select 
				sales_orders.id as sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id,
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
				join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by 
				sales_orders.id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				) outyarnisu on outyarnisu.budget_fabric_id=budget_fabrics.id 
				and outyarnisu.sales_order_id=sales_orders.id 
				and outyarnisu.dia=budgetfabriccons.dia 
				and outyarnisu.fabric_color_id=budgetfabriccons.fabric_color
				left join(
				select
				m.sales_order_id,
				m.dia,
				m.fabric_color_id,
				m.budget_fabric_id,
				sum(qc_pass_qty) as knit_qty,
				sum(m.roll_weight) as prod_knit_qty,
				sum(m.knit_dlv_to_st_qty) as knit_dlv_to_st_qty,
				sum(m.knit_rcv_by_st_qty) as knit_rcv_by_st_qty
				from 
				(
				select
				prod_knit_items.pl_knit_item_id,
				prod_knit_items.po_knit_service_item_qty_id,
				prod_knit_item_rolls.roll_weight,
				prod_knit_qcs.reject_qty,   
				prod_knit_qcs.qc_pass_qty,
				knitdlvstore.knit_dlv_to_st_qty,
				knitrcvstore.knit_rcv_by_st_qty,
				CASE 
				WHEN  inhprods.sales_order_id IS NULL THEN outprods.sales_order_id 
				ELSE inhprods.sales_order_id
				END as sales_order_id,
				CASE 
				WHEN  inhprods.dia IS NULL THEN outprods.dia 
				ELSE inhprods.dia
				END as dia,
				CASE 
				WHEN  inhprods.fabric_color_id IS NULL THEN outprods.fabric_color_id 
				ELSE inhprods.fabric_color_id
				END as fabric_color_id,
				CASE 
				WHEN  inhprods.budget_fabric_id IS NULL THEN outprods.budget_fabric_id 
				ELSE inhprods.budget_fabric_id
				END as budget_fabric_id
				from

				prod_knits
				join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
				join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
				left join prod_knit_rcv_by_qcs on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
				left join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id

				left join (
				select
				prod_knit_dlv_rolls.id, 
				prod_knit_qcs.id as prod_knit_qc_id,
				prod_knit_qcs.qc_pass_qty as knit_dlv_to_st_qty
				from
				prod_knit_qcs
				join prod_knit_dlv_rolls on prod_knit_dlv_rolls.prod_knit_qc_id=prod_knit_qcs.id
				) knitdlvstore on knitdlvstore.prod_knit_qc_id=prod_knit_qcs.id

				left join (
				select 
				prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id,
				inv_grey_fab_rcv_items.qty as knit_rcv_by_st_qty
				from
				prod_knit_dlv_rolls
				join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
				) knitrcvstore on knitrcvstore.prod_knit_dlv_roll_id=knitdlvstore.id


				/*prod_knit_qcs
				join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id=prod_knit_qcs.prod_knit_rcv_by_qc_id
				join prod_knit_item_rolls on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
				join prod_knit_items on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
				join prod_knits on prod_knits.id=prod_knit_items.prod_knit_id*/

				left join (
				select 
				pl_knit_items.id as pl_knit_item_id,
				sales_orders.id as sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				from 
				sales_orders
				join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
				join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
				and po_knit_service_items.deleted_at is null
				join so_knit_po_items on so_knit_po_items.po_knit_service_item_qty_id=po_knit_service_item_qties.id
				join so_knit_refs on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
				join pl_knit_items on pl_knit_items.so_knit_ref_id=so_knit_refs.id
				join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
				join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				where 1=1 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant

				) inhprods on inhprods.pl_knit_item_id=prod_knit_items.pl_knit_item_id

				left join (
				select 
				po_knit_service_item_qties.id as po_knit_service_item_qty_id,
				sales_orders.id as sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				from 
				sales_orders
				join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
				join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
				join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
				join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				where 1=1 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				) outprods on outprods.po_knit_service_item_qty_id=prod_knit_items.po_knit_service_item_qty_id
				) m 
				group by  
				m.sales_order_id,
				m.dia,
				m.fabric_color_id,
				m.budget_fabric_id
				) prodknit on  prodknit.budget_fabric_id=budget_fabrics.id 
				and prodknit.sales_order_id=sales_orders.id 
				and prodknit.dia=budgetfabriccons.dia 
				and prodknit.fabric_color_id=budgetfabriccons.fabric_color

				left join (
					select 
					sales_orders.id as sales_order_id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(inv_grey_fab_isu_items.qty) as rcv_by_batch_qty
					from 
					so_dyeing_fabric_rcv_items
					join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id=so_dyeing_fabric_rcv_items.id
					join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id=so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id

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
					join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
					where 
					inv_grey_fab_isu_items.deleted_at is null and 
					inv_grey_fab_isu_items.deleted_at is null and 
					so_dyeing_fabric_rcv_items.deleted_at is null $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
					group by
					sales_orders.id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) rcvbybatch on rcvbybatch.budget_fabric_id=budget_fabrics.id 
				and rcvbybatch.sales_order_id=sales_orders.id 
				and rcvbybatch.dia=budgetfabriccons.dia 
				and rcvbybatch.fabric_color_id=budgetfabriccons.fabric_color

				left join(
				select 
				sales_orders.id as sales_order_id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id,
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
				join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
				where 
				prod_batches.batch_for=1 and
				prod_batches.is_redyeing=0 and 
				prod_batches.deleted_at is null and 
				prod_batch_rolls.deleted_at is null  $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				sales_orders.id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				) prodbatch on prodbatch.budget_fabric_id=budget_fabrics.id 
				and prodbatch.sales_order_id=sales_orders.id 
				and prodbatch.dia=budgetfabriccons.dia 
				and prodbatch.fabric_color_id=budgetfabriccons.fabric_color

				left join(
				select 
				sales_orders.id as sales_order_id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id,
				sum(prod_batch_rolls.qty) as load_qty
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
				join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
				where 
				prod_batches.batch_for=1 and
				prod_batches.is_redyeing=0 and 
				prod_batches.deleted_at is null and 
				prod_batch_rolls.deleted_at is null and 
				prod_batches.loaded_at is not null $datefrom $dateto $company $producedcompany $style  $buyer $orderstatus $factorymerchant
				group by
				sales_orders.id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				) prodbatchload on prodbatchload.budget_fabric_id=budget_fabrics.id 
				and prodbatchload.sales_order_id=sales_orders.id 
				and prodbatchload.dia=budgetfabriccons.dia 
				and prodbatchload.fabric_color_id=budgetfabriccons.fabric_color

				left join(
				select 
				sales_orders.id as sales_order_id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id,
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
				join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
				where 
				prod_batches.batch_for=1 and
				prod_batches.is_redyeing=0 and 
				prod_batches.deleted_at is null and 
				prod_batch_rolls.deleted_at is null and 
				prod_batches.unloaded_at is not null $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				sales_orders.id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				) proddyeing on proddyeing.budget_fabric_id=budget_fabrics.id 
				and proddyeing.sales_order_id=sales_orders.id 
				and proddyeing.dia=budgetfabriccons.dia 
				and proddyeing.fabric_color_id=budgetfabriccons.fabric_color

				left join(
					select
					sales_orders.id as sales_order_id, 
					budget_fabric_cons.budget_fabric_id,
					budget_fabric_cons.dia,
					budget_fabric_cons.fabric_color,
					sum(budget_fabric_cons.fin_fab) as aop_fin_fab,
					sum(budget_fabric_cons.grey_fab) as aop_gery_fab
					from
					budget_fabric_cons
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id=budget_fabric_cons.sales_order_gmt_color_size_id 
					join sales_order_countries on sales_order_countries.id=sales_order_gmt_color_sizes.sale_order_country_id 
					join sales_orders on sales_orders.id=sales_order_countries.sale_order_id
					join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
					join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
					join jobs on jobs.id=sales_orders.job_id
					join styles on styles.id=jobs.style_id
					where style_fabrications.fabric_look_id=25 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
					group by
					sales_orders.id, 
					budget_fabric_cons.budget_fabric_id,
					budget_fabric_cons.dia,
					budget_fabric_cons.fabric_color
				) aopreq on aopreq.budget_fabric_id=budget_fabrics.id 
				and aopreq.sales_order_id=sales_orders.id 
				and aopreq.dia=budgetfabriccons.dia 
				and aopreq.fabric_color=budgetfabriccons.fabric_color

				left join(
					select 
					sales_orders.id as sales_order_id,
					--prod_aop_batch_finish_qc_rolls.dia_width,
					budget_fabric_prod_cons.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(prod_aop_batch_finish_qc_rolls.qty) as aop_qc_qty
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
					join colors  fabriccolors on fabriccolors.id=prod_batches.batch_color_id
					join so_aop_refs on so_aop_refs.id=so_aop_fabric_rcv_items.so_aop_ref_id
					join so_aops on so_aops.id=so_aop_refs.so_aop_id
					join so_aop_pos on so_aops.id=so_aop_pos.so_aop_id
					join so_aop_po_items on so_aop_po_items.so_aop_ref_id=so_aop_refs.id
					join po_aop_service_item_qties on po_aop_service_item_qties.id=so_aop_po_items.po_aop_service_item_qty_id
					join po_aop_service_items on po_aop_service_items.id=po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
					join budget_fabric_prod_cons on budget_fabric_prod_cons.id=po_aop_service_item_qties.budget_fabric_prod_con_id
					join sales_orders on sales_orders.id=budget_fabric_prod_cons.sales_order_id
					join jobs on jobs.id=sales_orders.job_id
					join styles on styles.id=jobs.style_id
					join budget_fabric_prods on budget_fabric_prods.id=po_aop_service_items.budget_fabric_prod_id
					join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
					join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
					where style_fabrications.fabric_look_id=25 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant

					group by
					sales_orders.id,
					--prod_aop_batch_finish_qc_rolls.dia_width,
					budget_fabric_prod_cons.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) aopqc on aopqc.budget_fabric_id=budget_fabrics.id 
				and aopqc.sales_order_id=sales_orders.id 
				--and aopqc.dia_width=budgetfabriccons.dia 
				and aopqc.fabric_color_id=budgetfabriccons.fabric_color

				left join(
					select 
					sales_orders.id as sales_order_id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(prod_batch_finish_qc_rolls.qty) as dyeing_qc_qty
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
					join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
					where 
					prod_batches.batch_for=1 and
					prod_batches.is_redyeing=0 and 
					prod_batches.deleted_at is null and 
					prod_batch_rolls.deleted_at is null  and
					prod_batches.unloaded_at is not null 
					$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
					group by
					sales_orders.id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) proddyeingqc on proddyeingqc.budget_fabric_id=budget_fabrics.id 
				and proddyeingqc.sales_order_id=sales_orders.id 
				and proddyeingqc.dia=budgetfabriccons.dia 
				and proddyeingqc.fabric_color_id=budgetfabriccons.fabric_color

				left join(
					select 
					sales_orders.id as sales_order_id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(prod_batch_finish_qc_rolls.qty) as dyeing_dlv_to_store_qty
					from 
					prod_batches
					join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
					join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_roll_id=prod_batch_rolls.id
					join prod_finish_dlv_rolls on prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id=prod_batch_finish_qc_rolls.id
					join prod_finish_dlvs on prod_finish_dlvs.id=prod_finish_dlv_rolls.prod_finish_dlv_id
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
					join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
					where 
					prod_batches.batch_for=1 and
					prod_batches.is_redyeing=0 and 
					prod_batches.deleted_at is null and 
					prod_batch_rolls.deleted_at is null  and
					prod_batches.unloaded_at is not null and
					prod_finish_dlvs.menu_id=285 and
					prod_finish_dlvs.dlv_to_finish_store=1  
					$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
					group by
					sales_orders.id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) proddyeingdlvtostore on proddyeingdlvtostore.budget_fabric_id=budget_fabrics.id 
				and proddyeingdlvtostore.sales_order_id=sales_orders.id 
				and proddyeingdlvtostore.dia=budgetfabriccons.dia 
				and proddyeingdlvtostore.fabric_color_id=budgetfabriccons.fabric_color

				left join(
					select 
					sales_orders.id as sales_order_id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(prod_batch_finish_qc_rolls.qty) as dyeing_rcv_to_store_qty
					from 
					prod_batches
					join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
					join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_roll_id=prod_batch_rolls.id
					join prod_finish_dlv_rolls on prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id=prod_batch_finish_qc_rolls.id
					join prod_finish_dlvs on prod_finish_dlvs.id=prod_finish_dlv_rolls.prod_finish_dlv_id
					join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
					join inv_finish_fab_rcvs on inv_finish_fab_rcvs.id=inv_finish_fab_rcv_items.inv_finish_fab_rcv_id
					and inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
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
					join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
					where 
					prod_batches.batch_for=1 and
					prod_batches.is_redyeing=0 and 
					prod_batches.deleted_at is null and 
					prod_batch_rolls.deleted_at is null  and
					prod_batches.unloaded_at is not null and
					prod_finish_dlvs.menu_id=285 and
					prod_finish_dlvs.dlv_to_finish_store=1  
					$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
					group by
					sales_orders.id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) proddyeingrcvtostore on proddyeingrcvtostore.budget_fabric_id=budget_fabrics.id 
				and proddyeingrcvtostore.sales_order_id=sales_orders.id 
				and proddyeingrcvtostore.dia=budgetfabriccons.dia 
				and proddyeingrcvtostore.fabric_color_id=budgetfabriccons.fabric_color

				left join(
					select 
					sales_orders.id as sales_order_id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(inv_finish_fab_isu_items.qty) as dyeing_isu_cut_qty
					from 
					prod_batches
					join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
					join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_roll_id=prod_batch_rolls.id
					join prod_finish_dlv_rolls on prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id=prod_batch_finish_qc_rolls.id
					join prod_finish_dlvs on prod_finish_dlvs.id=prod_finish_dlv_rolls.prod_finish_dlv_id
					join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
					join inv_finish_fab_rcvs on inv_finish_fab_rcvs.id=inv_finish_fab_rcv_items.inv_finish_fab_rcv_id
					and inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
					join inv_finish_fab_isu_items on inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id=inv_finish_fab_rcv_items.id
					join inv_isus on inv_isus.id=inv_finish_fab_isu_items.inv_isu_id

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
					join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
					where 
					prod_batches.batch_for=1 and
					prod_batches.is_redyeing=0 and 
					prod_batches.deleted_at is null and 
					prod_batch_rolls.deleted_at is null  and
					prod_batches.unloaded_at is not null and 
					prod_finish_dlvs.menu_id=285 and
					prod_finish_dlvs.dlv_to_finish_store=1  and
					inv_isus.isu_basis_id=1

					group by
					sales_orders.id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) proddyeingisucut on proddyeingisucut.budget_fabric_id=budget_fabrics.id 
				and proddyeingisucut.sales_order_id=sales_orders.id 
				and proddyeingisucut.dia=budgetfabriccons.dia 
				and proddyeingisucut.fabric_color_id=budgetfabriccons.fabric_color

				left join colors on colors.id=budgetfabriccons.fabric_color

				where 1=1 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				order by styles.id
			"))
			->map(function($rows) use($desDropdown,$fabriclooks,$fabricshape){
				$rows->fabrication=isset($desDropdown[$rows->autoyarn_id])?$desDropdown[$rows->autoyarn_id]:'';
				$rows->fabriclook=isset($fabriclooks[$rows->fabric_look_id])?$fabriclooks[$rows->fabric_look_id]:'';
				$rows->fabricshape=isset($fabricshape[$rows->fabric_shape_id])?$fabricshape[$rows->fabric_shape_id]:'';
				$rows->yisu_qty=$rows->out_yarn_isu_qty+$rows->inh_yarn_isu_qty;
				$yisu_bal=$rows->grey_fab-$rows->yisu_qty;

				$knit_wip=$rows->yisu_qty-$rows->prod_knit_qty;
				$knit_bal=$rows->prod_knit_qty-$rows->grey_fab;

				$rcv_by_batch_bal=$rows->rcv_by_batch_qty-$rows->grey_fab;

				$batch_wip=$rows->batch_qty-$rows->knit_qty;
				$batch_bal=$rows->batch_qty-$rows->grey_fab;
				
				$load_wip=$rows->load_qty-$rows->batch_qty;
				$load_bal=$rows->load_qty-$rows->grey_fab;

				$dyeing_wip=$rows->dyeing_qty-$rows->load_qty;
				$dyeing_bal=$rows->dyeing_qty-$rows->grey_fab;
				
				$rows->aop_fab=$rows->aop_gery_fab;
				$rows->aop_bal=$rows->aop_fab-$rows->aop_qc_qty;

				$rows->finish_qty=$rows->dyeing_qc_qty;
				$finish_wip=$rows->dyeing_qty-$rows->finish_qty;
				$finish_bal=$rows->finish_qty-$rows->fin_fab;

				$rows->finish_dlv_to_store_qty=$rows->dyeing_dlv_to_store_qty;
				$rows->finish_rcv_to_store_qty=$rows->dyeing_rcv_to_store_qty;
				
				$rows->finish_isu_to_cut_qty=$rows->dyeing_isu_cut_qty;
				$rows->finish_isu_to_cut_wip=$rows->finish_rcv_to_store_qty-$rows->finish_isu_to_cut_qty;
				$rows->finish_isu_to_cut_bal=$rows->finish_isu_to_cut_qty-$rows->fin_fab;

				


				$rows->yisu_bal=number_format($yisu_bal,2,'.',',');
				$rows->grey_fab=number_format($rows->grey_fab,2,'.',',');
				$rows->yisu_qty=number_format($rows->yisu_qty,2,'.',',');
				$rows->fin_fab=number_format($rows->fin_fab,2,'.',',');
				$rows->kwo_qty=number_format($rows->kwo_qty,2,'.',',');
				$rows->dwo_qty=number_format($rows->dwo_qty,2,'.',',');
				$rows->prod_knit_qty=number_format($rows->prod_knit_qty,2,'.',',');
				$rows->knit_wip=number_format($knit_wip,2,'.',',');
				$rows->knit_bal=number_format($knit_bal,2,'.',',');
				$rows->knit_qty=number_format($rows->knit_qty,2,'.',',');
				$rows->knit_dlv_to_st_qty=number_format($rows->knit_dlv_to_st_qty,2,'.',',');
				$rows->knit_rcv_by_st_qty=number_format($rows->knit_rcv_by_st_qty,2,'.',',');
				$rows->rcv_by_batch_qty=number_format($rows->rcv_by_batch_qty,2,'.',',');
				$rows->rcv_by_batch_bal=number_format($rcv_by_batch_bal,2,'.',',');
				$rows->batch_qty=number_format($rows->batch_qty,2,'.',',');
				$rows->batch_wip=number_format($batch_wip,2,'.',',');
				$rows->batch_bal=number_format($batch_bal,2,'.',',');
				$rows->load_qty=number_format($rows->load_qty,2,'.',',');
				$rows->load_wip=number_format($load_wip,2,'.',',');
				$rows->load_bal=number_format($load_bal,2,'.',',');

				$rows->dyeing_qty=number_format($rows->dyeing_qty,2,'.',',');
				$rows->dyeing_wip=number_format($dyeing_wip,2,'.',',');
				$rows->dyeing_bal=number_format($dyeing_bal,2,'.',',');
				$rows->aop_fab=number_format($rows->aop_fab,2,'.',',');
				$rows->aop_qc_qty=number_format($rows->aop_qc_qty,2,'.',',');
				$rows->aop_bal=number_format($rows->aop_bal,2,'.',',');
				$rows->finish_qty=number_format($rows->finish_qty,2,'.',',');
				$rows->finish_wip=number_format($finish_wip,2,'.',',');
				$rows->finish_bal=number_format($finish_bal,2,'.',',');
				// $rows->dlv_cut=number_format($dlv_cut,2,'.',',');
				// $rows->dlv_wip=number_format($dlv_wip,2,'.',',');
				// $rows->dlv_bal=number_format($dlv_bal,2,'.',',');
				$rows->finish_dlv_to_store_qty=number_format($rows->finish_dlv_to_store_qty,2,'.',',');
				$rows->finish_rcv_to_store_qty=number_format($rows->finish_rcv_to_store_qty,2,'.',',');
				$rows->finish_isu_to_cut_qty=number_format($rows->finish_isu_to_cut_qty,2,'.',',');
				$rows->finish_isu_to_cut_wip=number_format($rows->finish_isu_to_cut_wip,2,'.',',');
				$rows->finish_isu_to_cut_bal=number_format($rows->finish_isu_to_cut_bal,2,'.',',');
				return $rows;
			});
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

	public function getCompanyBuyerSummery(){
		$company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;

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

		$rows=collect(
			\DB::select("
			select 
				m.buyer_id,
				m.buyer_name,
				m.produced_company_id,
				m.produced_company_name,
				sum(m.qty) as qty,
				sum(m.grey_fab) as grey_fab,
				sum(m.fin_fab) as fin_fab,
				sum(m.kwo_qty) as kwo_qty,
				sum(m.dwo_qty) as dwo_qty,
				sum(m.inh_yarn_isu_qty) as inh_yarn_isu_qty,
				sum(m.out_yarn_isu_qty) as out_yarn_isu_qty,
				sum(m.knit_qty) as knit_qty,
				sum(m.prod_knit_qty) as prod_knit_qty,
				sum(m.knit_dlv_to_st_qty) as knit_dlv_to_st_qty,
				sum(m.knit_rcv_by_st_qty) as knit_rcv_by_st_qty,
				sum(m.rcv_by_batch_qty) as rcv_by_batch_qty,
				sum(m.batch_qty) as batch_qty,
				sum(m.load_qty) as load_qty,
				sum(m.dyeing_qty) as dyeing_qty,
				sum(m.dyeing_qc_qty) as dyeing_qc_qty,
				sum(m.dyeing_dlv_to_store_qty) as dyeing_dlv_to_store_qty,
				sum(m.dyeing_rcv_to_store_qty) as dyeing_rcv_to_store_qty,
				sum(m.dyeing_isu_cut_qty) as dyeing_isu_cut_qty
			from(
			select 
				buyers.id as buyer_id,
				buyers.name as buyer_name,
				produced_company.id as produced_company_id,
				produced_company.name as produced_company_name,
				sales_orders.id as sale_order_id,
				sales_orders.sale_order_no,
				sales_orders.ship_date,
				orders.qty,
				sum(budgetfabriccons.grey_fab) as grey_fab,
				sum(budgetfabriccons.fin_fab) as fin_fab,
				sum(kwo.kwo_qty) as kwo_qty,
				sum(dwo.dwo_qty) as dwo_qty,
				sum(inhyarnisu.inh_yarn_isu_qty) as inh_yarn_isu_qty,
				sum(outyarnisu.out_yarn_isu_qty) as out_yarn_isu_qty,
				sum(prodknit.knit_qty) as knit_qty,
				sum(prodknit.prod_knit_qty) as prod_knit_qty,
				sum(prodknit.knit_dlv_to_st_qty) as knit_dlv_to_st_qty,
				sum(prodknit.knit_rcv_by_st_qty) as knit_rcv_by_st_qty,
				sum(rcvbybatch.rcv_by_batch_qty) as rcv_by_batch_qty,
				sum(prodbatch.batch_qty) as batch_qty,
				sum(prodbatchload.load_qty) as load_qty,
				sum(proddyeing.dyeing_qty) as dyeing_qty,
				sum(proddyeingqc.dyeing_qc_qty) as dyeing_qc_qty,
				sum(proddyeingdlvtostore.dyeing_dlv_to_store_qty) as dyeing_dlv_to_store_qty,
				sum(proddyeingrcvtostore.dyeing_rcv_to_store_qty) as dyeing_rcv_to_store_qty,
				sum(proddyeingisucut.dyeing_isu_cut_qty) as dyeing_isu_cut_qty
				
				from
				styles
				join buyers on buyers.id=styles.buyer_id
				join jobs  on jobs.style_id=styles.id
				join companies  on companies.id=jobs.company_id
				join sales_orders  on sales_orders.job_id=jobs.id
				join companies  produced_company on produced_company.id=sales_orders.produced_company_id
				join style_fabrications  on style_fabrications.style_id=styles.id
				join budget_fabrics on budget_fabrics.style_fabrication_id=style_fabrications.id
				join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id
				join autoyarns on autoyarns.id=style_fabrications.autoyarn_id
				left join(
					select
					sales_orders.id as sales_order_id,
					sum(sales_order_gmt_color_sizes.qty) as qty
					from
					sales_orders
					join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
					--join sales_order_countries on sales_order_countries.id=sales_order_gmt_color_sizes.sale_order_country_id 
					join jobs on jobs.id=sales_orders.job_id
					join styles on styles.id=jobs.style_id
					where  1=1 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				sales_orders.id
				)orders on orders.sales_order_id=sales_orders.id
				left join (
				select
				sales_orders.id as sales_order_id, 
				budget_fabric_cons.budget_fabric_id,
				budget_fabric_cons.dia,
				budget_fabric_cons.fabric_color,
				sum(budget_fabric_cons.fin_fab) as fin_fab,
				sum(budget_fabric_cons.grey_fab) as grey_fab
				from
				budget_fabric_cons
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id=budget_fabric_cons.sales_order_gmt_color_size_id 
				join sales_order_countries on sales_order_countries.id=sales_order_gmt_color_sizes.sale_order_country_id 
				join sales_orders on sales_orders.id=sales_order_countries.sale_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				where  1=1 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				sales_orders.id, 
				budget_fabric_cons.budget_fabric_id,
				budget_fabric_cons.dia,
				budget_fabric_cons.fabric_color
				) budgetfabriccons on budgetfabriccons.budget_fabric_id=budget_fabrics.id and budgetfabriccons.sales_order_id=sales_orders.id


				left join (
				SELECT
				budget_fabric_prods.budget_fabric_id, 
				po_knit_service_item_qties.sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				sum(po_knit_service_item_qties.qty) as kwo_qty 
				FROM po_knit_service_item_qties 
				join po_knit_service_items on  po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
				and po_knit_service_items.deleted_at is null
				join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				where po_knit_service_item_qties.deleted_at is null 
				and  1=1 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				budget_fabric_prods.budget_fabric_id, 
				po_knit_service_item_qties.sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id
				) kwo on kwo.budget_fabric_id=budget_fabrics.id 
				and kwo.sales_order_id=sales_orders.id 
				and kwo.dia=budgetfabriccons.dia 
				and kwo.fabric_color_id=budgetfabriccons.fabric_color

				left join (
				SELECT
				budget_fabric_prods.budget_fabric_id, 
				po_dyeing_service_item_qties.sales_order_id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				sum(po_dyeing_service_item_qties.qty) as dwo_qty 
				FROM po_dyeing_service_item_qties 
				join po_dyeing_service_items on  po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
				and po_dyeing_service_items.deleted_at is null
				join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
				join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				where po_dyeing_service_item_qties.deleted_at is null  
				$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				budget_fabric_prods.budget_fabric_id, 
				po_dyeing_service_item_qties.sales_order_id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id
				) dwo on dwo.budget_fabric_id=budget_fabrics.id 
				and dwo.sales_order_id=sales_orders.id 
				and dwo.dia=budgetfabriccons.dia 
				and dwo.fabric_color_id=budgetfabriccons.fabric_color

				left join(
				select 
				sales_orders.id as sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id,
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
				join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null 
				$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by 
				sales_orders.id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				) inhyarnisu on  inhyarnisu.budget_fabric_id=budget_fabrics.id 
				and inhyarnisu.sales_order_id=sales_orders.id 
				and inhyarnisu.dia=budgetfabriccons.dia 
				and inhyarnisu.fabric_color_id=budgetfabriccons.fabric_color

				left join (
				select 
				sales_orders.id as sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id,
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
				join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null 
				$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by 
				sales_orders.id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				) outyarnisu on outyarnisu.budget_fabric_id=budget_fabrics.id 
				and outyarnisu.sales_order_id=sales_orders.id 
				and outyarnisu.dia=budgetfabriccons.dia 
				and outyarnisu.fabric_color_id=budgetfabriccons.fabric_color
				left join(
				select
				m.sales_order_id,
				m.dia,
				m.fabric_color_id,
				m.budget_fabric_id,
				sum(qc_pass_qty) as knit_qty,
				sum(m.roll_weight) as prod_knit_qty,
				sum(m.knit_dlv_to_st_qty) as knit_dlv_to_st_qty,
				sum(m.knit_rcv_by_st_qty) as knit_rcv_by_st_qty
				from 
				(
				select
				prod_knit_items.pl_knit_item_id,
				prod_knit_items.po_knit_service_item_qty_id,
				prod_knit_item_rolls.roll_weight,
				prod_knit_qcs.reject_qty,   
				prod_knit_qcs.qc_pass_qty,
				knitdlvstore.knit_dlv_to_st_qty,
				knitrcvstore.knit_rcv_by_st_qty,
				CASE 
				WHEN  inhprods.sales_order_id IS NULL THEN outprods.sales_order_id 
				ELSE inhprods.sales_order_id
				END as sales_order_id,
				CASE 
				WHEN  inhprods.dia IS NULL THEN outprods.dia 
				ELSE inhprods.dia
				END as dia,
				CASE 
				WHEN  inhprods.fabric_color_id IS NULL THEN outprods.fabric_color_id 
				ELSE inhprods.fabric_color_id
				END as fabric_color_id,
				CASE 
				WHEN  inhprods.budget_fabric_id IS NULL THEN outprods.budget_fabric_id 
				ELSE inhprods.budget_fabric_id
				END as budget_fabric_id
				from

				prod_knits
				join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
				join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
				left join prod_knit_rcv_by_qcs on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
				left join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id

				left join (
				select
				prod_knit_dlv_rolls.id, 
				prod_knit_qcs.id as prod_knit_qc_id,
				prod_knit_qcs.qc_pass_qty as knit_dlv_to_st_qty
				from
				prod_knit_qcs
				join prod_knit_dlv_rolls on prod_knit_dlv_rolls.prod_knit_qc_id=prod_knit_qcs.id
				) knitdlvstore on knitdlvstore.prod_knit_qc_id=prod_knit_qcs.id

				left join (
				select 
				prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id,
				inv_grey_fab_rcv_items.qty as knit_rcv_by_st_qty
				from
				prod_knit_dlv_rolls
				join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
				) knitrcvstore on knitrcvstore.prod_knit_dlv_roll_id=knitdlvstore.id

				left join (
				select 
				pl_knit_items.id as pl_knit_item_id,
				sales_orders.id as sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				from 
				sales_orders
				join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
				join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
				and po_knit_service_items.deleted_at is null
				join so_knit_po_items on so_knit_po_items.po_knit_service_item_qty_id=po_knit_service_item_qties.id
				join so_knit_refs on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
				join pl_knit_items on pl_knit_items.so_knit_ref_id=so_knit_refs.id
				join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
				join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				where  1=1 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant

				) inhprods on inhprods.pl_knit_item_id=prod_knit_items.pl_knit_item_id

				left join (
				select 
				po_knit_service_item_qties.id as po_knit_service_item_qty_id,
				sales_orders.id as sales_order_id,
				po_knit_service_item_qties.dia,
				po_knit_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				from 
				sales_orders
				join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
				join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
				join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
				join budget_fabric_prods on  budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				where  1=1 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				) outprods on outprods.po_knit_service_item_qty_id=prod_knit_items.po_knit_service_item_qty_id
				) m 
				group by  
				m.sales_order_id,
				m.dia,
				m.fabric_color_id,
				m.budget_fabric_id
				) prodknit on  prodknit.budget_fabric_id=budget_fabrics.id 
				and prodknit.sales_order_id=sales_orders.id 
				and prodknit.dia=budgetfabriccons.dia 
				and prodknit.fabric_color_id=budgetfabriccons.fabric_color

				left join (
					select 
					sales_orders.id as sales_order_id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(inv_grey_fab_isu_items.qty) as rcv_by_batch_qty
					from 
					so_dyeing_fabric_rcv_items
					join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id=so_dyeing_fabric_rcv_items.id
					join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id=so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id

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
					join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
					where 
					inv_grey_fab_isu_items.deleted_at is null and 
					inv_grey_fab_isu_items.deleted_at is null and 
					so_dyeing_fabric_rcv_items.deleted_at is null 
					
					$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
					group by
					sales_orders.id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) rcvbybatch on rcvbybatch.budget_fabric_id=budget_fabrics.id 
				and rcvbybatch.sales_order_id=sales_orders.id 
				and rcvbybatch.dia=budgetfabriccons.dia 
				and rcvbybatch.fabric_color_id=budgetfabriccons.fabric_color

				left join(
				select 
				sales_orders.id as sales_order_id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id,
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
				join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
				where 
				prod_batches.batch_for=1 and
				prod_batches.is_redyeing=0 and 
				prod_batches.deleted_at is null and 
				prod_batch_rolls.deleted_at is null  
				$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				sales_orders.id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				) prodbatch on prodbatch.budget_fabric_id=budget_fabrics.id 
				and prodbatch.sales_order_id=sales_orders.id 
				and prodbatch.dia=budgetfabriccons.dia 
				and prodbatch.fabric_color_id=budgetfabriccons.fabric_color

				left join(
				select 
				sales_orders.id as sales_order_id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id,
				sum(prod_batch_rolls.qty) as load_qty
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
				join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
				where 
				prod_batches.batch_for=1 and
				prod_batches.is_redyeing=0 and 
				prod_batches.deleted_at is null and 
				prod_batch_rolls.deleted_at is null and 
				prod_batches.loaded_at is not null 
				$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				sales_orders.id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				) prodbatchload on prodbatchload.budget_fabric_id=budget_fabrics.id 
				and prodbatchload.sales_order_id=sales_orders.id 
				and prodbatchload.dia=budgetfabriccons.dia 
				and prodbatchload.fabric_color_id=budgetfabriccons.fabric_color

				left join(
				select 
				sales_orders.id as sales_order_id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id,
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
				join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
				where 
				prod_batches.batch_for=1 and
				prod_batches.is_redyeing=0 and 
				prod_batches.deleted_at is null and 
				prod_batch_rolls.deleted_at is null and 
				prod_batches.unloaded_at is not null 
				$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				sales_orders.id,
				po_dyeing_service_item_qties.dia,
				po_dyeing_service_item_qties.fabric_color_id,
				budget_fabric_prods.budget_fabric_id
				) proddyeing on proddyeing.budget_fabric_id=budget_fabrics.id 
				and proddyeing.sales_order_id=sales_orders.id 
				and proddyeing.dia=budgetfabriccons.dia 
				and proddyeing.fabric_color_id=budgetfabriccons.fabric_color

				left join(
					select 
					sales_orders.id as sales_order_id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(prod_batch_finish_qc_rolls.qty) as dyeing_qc_qty
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
					join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
					where 
					prod_batches.batch_for=1 and
					prod_batches.is_redyeing=0 and 
					prod_batches.deleted_at is null and 
					prod_batch_rolls.deleted_at is null  and
					prod_batches.unloaded_at is not null 
					$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
					group by
					sales_orders.id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) proddyeingqc on proddyeingqc.budget_fabric_id=budget_fabrics.id 
				and proddyeingqc.sales_order_id=sales_orders.id 
				and proddyeingqc.dia=budgetfabriccons.dia 
				and proddyeingqc.fabric_color_id=budgetfabriccons.fabric_color

				left join(
					select 
					sales_orders.id as sales_order_id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(prod_batch_finish_qc_rolls.qty) as dyeing_dlv_to_store_qty
					from 
					prod_batches
					join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
					join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_roll_id=prod_batch_rolls.id
					join prod_finish_dlv_rolls on prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id=prod_batch_finish_qc_rolls.id
					join prod_finish_dlvs on prod_finish_dlvs.id=prod_finish_dlv_rolls.prod_finish_dlv_id
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
					join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
					where 
					prod_batches.batch_for=1 and
					prod_batches.is_redyeing=0 and 
					prod_batches.deleted_at is null and 
					prod_batch_rolls.deleted_at is null  and
					prod_batches.unloaded_at is not null and
					prod_finish_dlvs.menu_id=285 and
					prod_finish_dlvs.dlv_to_finish_store=1  
					$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
					group by
					sales_orders.id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) proddyeingdlvtostore on proddyeingdlvtostore.budget_fabric_id=budget_fabrics.id 
				and proddyeingdlvtostore.sales_order_id=sales_orders.id 
				and proddyeingdlvtostore.dia=budgetfabriccons.dia 
				and proddyeingdlvtostore.fabric_color_id=budgetfabriccons.fabric_color

				left join(
					select 
					sales_orders.id as sales_order_id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(prod_batch_finish_qc_rolls.qty) as dyeing_rcv_to_store_qty
					from 
					prod_batches
					join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
					join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_roll_id=prod_batch_rolls.id
					join prod_finish_dlv_rolls on prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id=prod_batch_finish_qc_rolls.id
					join prod_finish_dlvs on prod_finish_dlvs.id=prod_finish_dlv_rolls.prod_finish_dlv_id
					join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
					join inv_finish_fab_rcvs on inv_finish_fab_rcvs.id=inv_finish_fab_rcv_items.inv_finish_fab_rcv_id
					and inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
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
					join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
					where 
					prod_batches.batch_for=1 and
					prod_batches.is_redyeing=0 and 
					prod_batches.deleted_at is null and 
					prod_batch_rolls.deleted_at is null  and
					prod_batches.unloaded_at is not null and
					prod_finish_dlvs.menu_id=285 and
					prod_finish_dlvs.dlv_to_finish_store=1  
					$datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
					group by
					sales_orders.id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) proddyeingrcvtostore on proddyeingrcvtostore.budget_fabric_id=budget_fabrics.id 
				and proddyeingrcvtostore.sales_order_id=sales_orders.id 
				and proddyeingrcvtostore.dia=budgetfabriccons.dia 
				and proddyeingrcvtostore.fabric_color_id=budgetfabriccons.fabric_color

				left join(
					select 
					sales_orders.id as sales_order_id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id,
					sum(inv_finish_fab_isu_items.qty) as dyeing_isu_cut_qty
					from 
					prod_batches
					join prod_batch_rolls on prod_batch_rolls.prod_batch_id=prod_batches.id
					join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.prod_batch_roll_id=prod_batch_rolls.id
					join prod_finish_dlv_rolls on prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id=prod_batch_finish_qc_rolls.id
					join prod_finish_dlvs on prod_finish_dlvs.id=prod_finish_dlv_rolls.prod_finish_dlv_id
					join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
					join inv_finish_fab_rcvs on inv_finish_fab_rcvs.id=inv_finish_fab_rcv_items.inv_finish_fab_rcv_id
					and inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
					join inv_finish_fab_isu_items on inv_finish_fab_isu_items.inv_finish_fab_rcv_item_id=inv_finish_fab_rcv_items.id
					join inv_isus on inv_isus.id=inv_finish_fab_isu_items.inv_isu_id

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
					join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
					where 
					prod_batches.batch_for=1 and
					prod_batches.is_redyeing=0 and 
					prod_batches.deleted_at is null and 
					prod_batch_rolls.deleted_at is null  and
					prod_batches.unloaded_at is not null and 
					prod_finish_dlvs.menu_id=285 and
					prod_finish_dlvs.dlv_to_finish_store=1  and
					inv_isus.isu_basis_id=1

					group by
					sales_orders.id,
					po_dyeing_service_item_qties.dia,
					po_dyeing_service_item_qties.fabric_color_id,
					budget_fabric_prods.budget_fabric_id
				) proddyeingisucut on proddyeingisucut.budget_fabric_id=budget_fabrics.id 
				and proddyeingisucut.sales_order_id=sales_orders.id 
				and proddyeingisucut.dia=budgetfabriccons.dia 
				and proddyeingisucut.fabric_color_id=budgetfabriccons.fabric_color
				left join colors on colors.id=budgetfabriccons.fabric_color

				where 
				1=1 $datefrom $dateto $company $producedcompany $style $styleid $buyer $orderstatus $factorymerchant
				group by
				buyers.id,
				buyers.name,
				produced_company.id,
				produced_company.name,
				sales_orders.id,
				sales_orders.sale_order_no,
				sales_orders.ship_date,
				orders.qty
				)m
				group by
				m.buyer_id,
				m.buyer_name,
				m.produced_company_id,
				m.produced_company_name
			"))
			->map(function($rows) {
				$rows->yisu_qty=$rows->out_yarn_isu_qty+$rows->inh_yarn_isu_qty;
				$rows->yisu_bal=$rows->grey_fab-$rows->yisu_qty;
				$rows->knit_wip=$rows->yisu_qty-$rows->prod_knit_qty;
				$rows->knit_bal=$rows->prod_knit_qty-$rows->grey_fab;
				$rows->rcv_by_batch_bal=$rows->rcv_by_batch_qty-$rows->grey_fab;
				$rows->batch_wip=$rows->batch_qty-$rows->knit_qty;
				$rows->batch_bal=$rows->batch_qty-$rows->grey_fab;
				$rows->load_wip=$rows->load_qty-$rows->batch_qty;
				$rows->load_bal=$rows->load_qty-$rows->grey_fab;
	
				$rows->dyeing_wip=$rows->dyeing_qty-$rows->load_qty;
				$rows->dyeing_bal=$rows->dyeing_qty-$rows->grey_fab;
	
				$rows->finish_qty=$rows->dyeing_qc_qty;
				$rows->finish_wip=$rows->dyeing_qty-$rows->finish_qty;
				$rows->finish_bal=$rows->finish_qty-$rows->fin_fab;
	
				$rows->finish_dlv_to_store_qty=$rows->dyeing_dlv_to_store_qty;
				$rows->finish_rcv_to_store_qty=$rows->dyeing_rcv_to_store_qty;
				
				$rows->finish_isu_to_cut_qty=$rows->dyeing_isu_cut_qty;
				$rows->finish_isu_to_cut_wip=$rows->finish_rcv_to_store_qty-$rows->finish_isu_to_cut_qty;
				$rows->finish_isu_to_cut_bal=$rows->finish_isu_to_cut_qty-$rows->fin_fab;
				return $rows;
			});

		
		$prodCompanyArr=[];

		foreach($rows as $row){
			$prodCompanyArr[$row->produced_company_id]['produced_company_name']=$row->produced_company_name;
			$prodCompanyArr[$row->produced_company_id]['qty']=isset($prodCompanyArr[$row->produced_company_id]['qty'])?$prodCompanyArr[$row->produced_company_id]['qty']+=$row->qty:$row->qty;
			$prodCompanyArr[$row->produced_company_id]['grey_fab']=isset($prodCompanyArr[$row->produced_company_id]['grey_fab'])?$prodCompanyArr[$row->produced_company_id]['grey_fab']+=$row->grey_fab:$row->grey_fab;
			$prodCompanyArr[$row->produced_company_id]['yisu_qty']=isset($prodCompanyArr[$row->produced_company_id]['yisu_qty'])?$prodCompanyArr[$row->produced_company_id]['yisu_qty']+=$row->yisu_qty:$row->yisu_qty;
			$prodCompanyArr[$row->produced_company_id]['yisu_bal']=isset($prodCompanyArr[$row->produced_company_id]['yisu_bal'])?$prodCompanyArr[$row->produced_company_id]['yisu_bal']+=$row->yisu_bal:$row->yisu_bal;
			$prodCompanyArr[$row->produced_company_id]['prod_knit_qty']=isset($prodCompanyArr[$row->produced_company_id]['prod_knit_qty'])?$prodCompanyArr[$row->produced_company_id]['prod_knit_qty']+=$row->prod_knit_qty:$row->prod_knit_qty;
			$prodCompanyArr[$row->produced_company_id]['knit_bal']=isset($prodCompanyArr[$row->produced_company_id]['knit_bal'])?$prodCompanyArr[$row->produced_company_id]['knit_bal']+=$row->knit_bal:$row->knit_bal;
			$prodCompanyArr[$row->produced_company_id]['knit_qty']=isset($prodCompanyArr[$row->produced_company_id]['knit_qty'])?$prodCompanyArr[$row->produced_company_id]['knit_qty']+=$row->knit_qty:$row->knit_qty;
			$prodCompanyArr[$row->produced_company_id]['rcv_by_batch_qty']=isset($prodCompanyArr[$row->produced_company_id]['rcv_by_batch_qty'])?$prodCompanyArr[$row->produced_company_id]['rcv_by_batch_qty']+=$row->rcv_by_batch_qty:$row->rcv_by_batch_qty;
			$prodCompanyArr[$row->produced_company_id]['batch_qty']=isset($prodCompanyArr[$row->produced_company_id]['batch_qty'])?$prodCompanyArr[$row->produced_company_id]['batch_qty']+=$row->batch_qty:$row->batch_qty;
			$prodCompanyArr[$row->produced_company_id]['batch_bal']=isset($prodCompanyArr[$row->produced_company_id]['batch_bal'])?$prodCompanyArr[$row->produced_company_id]['batch_bal']+=$row->batch_bal:$row->batch_bal;
			$prodCompanyArr[$row->produced_company_id]['dyeing_qty']=isset($prodCompanyArr[$row->produced_company_id]['dyeing_qty'])?$prodCompanyArr[$row->produced_company_id]['dyeing_qty']+=$row->dyeing_qty:$row->dyeing_qty;
			$prodCompanyArr[$row->produced_company_id]['dyeing_bal']=isset($prodCompanyArr[$row->produced_company_id]['dyeing_bal'])?$prodCompanyArr[$row->produced_company_id]['dyeing_bal']+=$row->dyeing_bal:$row->dyeing_bal;
			$prodCompanyArr[$row->produced_company_id]['fin_fab']=isset($prodCompanyArr[$row->produced_company_id]['fin_fab'])?$prodCompanyArr[$row->produced_company_id]['fin_fab']+=$row->fin_fab:$row->fin_fab;
			$prodCompanyArr[$row->produced_company_id]['finish_qty']=isset($prodCompanyArr[$row->produced_company_id]['finish_qty'])?$prodCompanyArr[$row->produced_company_id]['finish_qty']+=$row->finish_qty:$row->finish_qty;
			$prodCompanyArr[$row->produced_company_id]['finish_bal']=isset($prodCompanyArr[$row->produced_company_id]['finish_bal'])?$prodCompanyArr[$row->produced_company_id]['finish_bal']+=$row->finish_bal:$row->finish_bal;
			$prodCompanyArr[$row->produced_company_id]['finish_isu_to_cut_qty']=isset($prodCompanyArr[$row->produced_company_id]['finish_isu_to_cut_qty'])?$prodCompanyArr[$row->produced_company_id]['finish_isu_to_cut_qty']+=$row->finish_isu_to_cut_qty:$row->finish_isu_to_cut_qty;
			$prodCompanyArr[$row->produced_company_id]['finish_isu_to_cut_bal']=isset($prodCompanyArr[$row->produced_company_id]['finish_isu_to_cut_bal'])?$prodCompanyArr[$row->produced_company_id]['finish_isu_to_cut_bal']+=$row->finish_isu_to_cut_bal:$row->finish_isu_to_cut_bal;

		}
       
		$buyerdata=$rows->groupBy('produced_company_id');
		return Template::loadView('Report.FabricProduction.FabricProdProgressSummeryMatrix',[
            'prodCompanyArr'=>$prodCompanyArr,
            'buyerdata'=>$buyerdata,
        ]);
	}

}
