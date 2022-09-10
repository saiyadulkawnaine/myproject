<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use App\Repositories\Contracts\Account\AccBepRepository;
use App\Repositories\Contracts\HRM\EmployeeAttendenceRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;

class ProdGmtCapacityAchievementController extends Controller
{
	private $no_of_days;
	private $exch_rate;
	private $subsection;
	private $wstudylinesetup;
	private $prodgmtsewing;
	private $accbep;
	private $attendence;
	private $salesorder;
	public function __construct(
		SubsectionRepository $subsection,
        WstudyLineSetupRepository $wstudylinesetup,
		ProdGmtSewingRepository $prodgmtsewing,
		AccBepRepository $accbep,
		EmployeeAttendenceRepository $attendence,
		SalesOrderRepository $salesorder
	)
    {
		$this->no_of_days                = 26;
		$this->exch_rate                 = 82;
		$this->subsection                = $subsection;
		$this->wstudylinesetup           = $wstudylinesetup;
		$this->prodgmtsewing             = $prodgmtsewing;
		$this->accbep                    = $accbep;
		$this->attendence                = $attendence;
		$this->salesorder                = $salesorder;
		$this->middleware('auth');
		$this->middleware('permission:view.prodgmtcapacityachievereports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
    	return Template::loadView('Report.CapacityAchivment', []);
    }
    

    public function formatOne(){
    	//$exch_rate=82;
    	$str2=request('date_to',0);
    	$date_to = date('d-M-y', strtotime($str2));
    	$rows=$this->reportData()
    	->map(function($rows) use($date_to){
    		$rows->date_to=$date_to;
    		// ========Row-1=============

    		$attendence=$rows->emp_att_operator+$rows->emp_att_helper+$rows->emp_att_prod_staff+$rows->emp_att_supporting_staff+$rows->emp_att_cutting_staff+$rows->emp_att_finishing_staff;
    		$rows->attendence=$attendence; 

    		// ========Row-2=============
            $rows->man_machine=0;
            if($rows->operator){
            	 $rows->man_machine=$attendence/$rows->operator;
            }
            // ========Row-3=============
            $rows->pdc_iact_qty=$rows->ttl_qty-($rows->pjd_qty+$rows->pdc_qty);

            $contribu=$rows->eamount-$rows->vamount;
    	    $contribuPer=0;
    	    if($rows->eamount){
    	    	$contribuPer=$contribu/$rows->eamount;
    	    }
    	    $rows->contribuPer=$contribuPer;
            $bepinsale=0;
    	    if($contribuPer){
    	    	$bepinsale=$rows->famount/$contribuPer;
    	    }

    	    $bepinqty=0;
			if($rows->bepunit_amount){
                $bepinqty=$bepinsale/$rows->bepunit_amount;
			}
			$rows->bepinqty=$bepinqty/$this->no_of_days;
			$rows->bepinsale=($bepinsale/$this->no_of_days)/$this->exch_rate;

			$rows->avg_sew_smv=0;
			if($rows->sew_qty)
			{
                $rows->avg_sew_smv=$rows->sew_smv/$rows->sew_qty;
			}
			$rows->cm_pcs=$rows->avg_sew_smv*$rows->cpm_amount;

			// ========Row-4=============
    		$rows->sew_amount=$rows->sew_amount;
    		$rows->cmo=$rows->cmo;
    		$rows->exfactory_amount=$rows->exfactory_amount;
    		$expense_except_sal_prod_bill=(($rows->vamount+$rows->famount))/$this->no_of_days;
            $budgeted_produced_cm= $expense_except_sal_prod_bill+$rows->operator_ot+$rows->helper_ot;
            $rows->budgeted_produced_cm=$budgeted_produced_cm/$this->exch_rate;

            $commer_amount=$rows->commer_amount;

			$profit=$rows->gmt_amount-($rows->yarn_amount+$rows->trim_amount+$rows->fabprod_amount+$rows->fabprod_overhead_amount+$rows->emb_amount+$rows->emb_overhead_amount+$commer_amount+$rows->comm_amount+$rows->other_amount);
    	    $bepincmproduced=0;
    	    if($rows->gmt_qty){
    	    	$bepincmproduced=(($profit)/$rows->gmt_qty)*($rows->cty);
    	    }
            $rows->profit=$profit;
    	    $rows->bepincmproduced=($bepincmproduced);

    	    

            $mkt_cost=($rows->mkt_cost_yarn_amount+
            $rows->mkt_cost_trim_amount+
            $rows->mkt_cost_fabric_prod_amount+
            $rows->mkt_cost_emb_amount+
            $rows->mkt_costs_other_amount+
            $rows->mkt_cost_commercial_amount+
            $rows->mkt_cost_commission_amount);
            $mkt_profit=$rows->gmt_amount-$mkt_cost;
            $mktcm=0;
    	    if($rows->gmt_qty){
    	    	$mktcm=(($mkt_profit)/$rows->gmt_qty)*($rows->cty);
    	    }
    	    $rows->mktcm=($mktcm);
            


            $rows->avg_used_smv=0;
			if($rows->sew_qty)
			{
                $rows->avg_used_smv=$rows->used_smv/$rows->sew_qty;
			}
			$rows->cm_used_pcs=$rows->avg_used_smv*$rows->cpm_amount;

    	    // ========Row-5=============
    	    $rows->dev_cut_qty=$rows->cut_qty-$rows->cutting_capacity_qty;
    		$rows->dev_screen_print_qty=$rows->scprinting_qty-$rows->screen_print_capacity_qty;
    		$rows->dev_embroidery_qty=$rows->embr_qty-$rows->embroidery_capacity_qty;
            $rows->dev_sew_qty=$rows->sew_qty-$rows->qty;
            $rows->dev_fin_qty=$rows->cty-$rows->cartoning_capacity_qty;
			$rows->dev_exfactory_qty=$rows->exfactory_qty-$rows->cartoning_capacity_qty;
			$rows->dev_sew_amount=$rows->sew_amount-$rows->amount;
			$rows->dev_fin_amount=$rows->cmo-$rows->cartoning_capacity_amount;
			$rows->dev_exfactory_amount=$rows->exfactory_amount-$rows->cartoning_capacity_amount;

			$rows->dev_cm_amount=$rows->bepincmproduced-$rows->budgeted_produced_cm;
			$rows->dev_bep_qty=$rows->cty-$rows->bepinqty;
			$rows->dev_bep_amount=$rows->bepincmproduced-$rows->bepinsale;
			$rows->dev_sew_smv=$rows->sew_smv-$rows->used_smv;
			$rows->dev_avg_sew_smv=$rows->avg_sew_smv-$rows->avg_used_smv;
			$rows->dev_cm_pcs=$rows->cm_pcs-$rows->cm_used_pcs;

			// ========Row-6=============

			$rows->cut_ach_qty_per=0;
    		if($rows->cutting_capacity_qty){
    			$rows->cut_ach_qty_per=($rows->cut_qty/$rows->cutting_capacity_qty)*100;
    		}
    		$rows->screen_print_ach_qty_per=0;
    		if($rows->screen_print_capacity_qty){
    			$rows->screen_print_ach_qty_per=(0/$rows->screen_print_capacity_qty)*100;
    		}
    		$rows->embroidery_ach_qty_per=0;
    		if($rows->embroidery_capacity_qty){
    			$rows->embroidery_ach_qty_per=(0/$rows->embroidery_capacity_qty)*100;
    		}
    		$rows->ach_qty_per=0;
    		if($rows->qty){
    			$rows->ach_qty_per=($rows->sew_qty/$rows->qty)*100;
    		}
    		$rows->fin_ach_qty_per=0;
    		if($rows->cartoning_capacity_qty){
    			$rows->fin_ach_qty_per=($rows->cty/$rows->cartoning_capacity_qty)*100;
    		}
    		$rows->exfactory_ach_qty_per=0;
    		if($rows->cartoning_capacity_qty){
    			$rows->exfactory_ach_qty_per=($rows->exfactory_qty/$rows->cartoning_capacity_qty)*100;
    		}
    		$rows->ach_amount_per=0;
    		if($rows->amount){
	    		$rows->ach_amount_per=($rows->sew_amount/$rows->amount)*100;
    	    }
    		$rows->fin_ach_amount_per=0;
    		if($rows->cartoning_capacity_amount){
	    		$rows->fin_ach_amount_per=($rows->cmo/$rows->cartoning_capacity_amount)*100;
    	    }

    	    $rows->exfactory_ach_amount_per=0;
    		if($rows->cartoning_capacity_amount){
	    		$rows->exfactory_ach_amount_per=($rows->exfactory_amount/$rows->cartoning_capacity_amount)*100;
    	    }
    	    $rows->bep_ach_qty_per=0;
    		if($rows->dev_bep_qty){
    			$rows->bep_ach_qty_per=($rows->cty/$rows->dev_bep_qty)*100;
    		}
    		$rows->bep_ach_amount_per=0;
    		if($rows->dev_bep_amount){
	    		$rows->bep_ach_amount_per=($rows->bepincmproduced/$rows->dev_bep_amount)*100;
    	    }
            // ========Row-7=============
    	    $rows->sew_eff_per=0;
			if($rows->used_smv){
			    $rows->sew_eff_per=($rows->sew_smv/$rows->used_smv)*100;
			}

            // ========Row-8=============
            
            $rows->cuttgt_plan_cut_qty=$rows->cuttgt_plan_cut_qty-$rows->exfactoryaslastmonth_qty;
    		$rows->cuttgt_amount=$rows->cuttgt_amount;
    		$rows->cuttgt_qty=$rows->cuttgt_qty-$rows->exfactoryaslastmonth_qty;
    		$rows->cuttgt_amount=$rows->cuttgt_amount-$rows->exfactoryaslastmonth_amount;
    		

    		
    		// ========Row-9=============
    		$rows->sewasof_amount=$rows->sewasof_amount;
    		$rows->cartoonasof_amount=$rows->cartoonasof_amount;
    		$rows->exfactoryasof_amount=$rows->exfactoryasof_amount;
    		// ========Row-10=============
    		$rows->dev_cut_qty_month=$rows->cutasof_qty-$rows->cuttgt_qty;
    		$rows->dev_screen_print_qty_month=$rows->scprintingasof_qty-$rows->screenprinttgt_qty;
    		$rows->dev_embroidery_qty_month=$rows->embrasof_qty-$rows->embrotgt_qty;
    		$rows->dev_sew_qty_month=$rows->sewasof_qty-$rows->cuttgt_qty;
			$rows->dev_fin_qty_month=$rows->cartoonasof_qty-$rows->cuttgt_qty;
			$rows->dev_exfactory_qty_month=$rows->exfactoryasof_qty-$rows->cuttgt_qty;
            $rows->dev_sew_amount_month=$rows->sewasof_amount-$rows->cuttgt_amount;
            $rows->dev_fin_amount_month=$rows->cartoonasof_amount-$rows->cuttgt_amount;
            $rows->dev_exfactory_amount_month=$rows->exfactoryasof_amount-$rows->cuttgt_amount;

            // ========Row-11=============
            // ========Row-12=============
            $rows->yet_to_expinvoice_qty= $rows->exfactoryasof_qty-$rows->expinvoice_qty;



    		$cm_amount=0;
    		if($rows->gmt_qty){
    			$cm_amount=(($rows->cm_amount/$rows->gmt_qty)*$rows->cty);
    		}
    		$rows->cm_amount=$cm_amount;
    		$rows->cmo_usd=$rows->cty*$rows->rate;
    		
			$manp=$rows->operator+$rows->helper;
			$rows->manp=$manp;
			$hour=$rows->working_hour+$rows->overtime_hour;
			$rows->hour=$hour;
			$teff=($manp*($hour*60));
			$rows->teff=$teff;
    		return $rows;
    	});
    	return Template::loadView('Report.CapacityAchivmentColorSizeMatrix', ['rows'=>$rows]);
    }

    public function reportData() {
    	$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	//$end_date=date('Y-m', strtotime($str2))."-31";
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$rows=$this->subsection
		->selectRaw(
		'company_subsections.company_id,
		companies.ceo,
		ttl.ttl_qty,
		pjd.pjd_qty,
		act.act_qty,
		iact.iact_qty,
		carton.qty as cty,
		carton.amount as cmo,
		cartonasof.qty as cartoonasof_qty,
		cartonasof.amount as cartoonasof_amount,
		
		gmt.rate,
		gmt.qty as gmt_qty,
		gmt.amount as gmt_amount,

		pdc.pdc_qty,
		pdc.operator,
		pdc.helper,
		pdc.working_hour,
		pdc.overtime_hour,
		cutting.qty as cut_qty,
		cuttingasof.qty as cutasof_qty,
		scprinting.qty as scprinting_qty,
		scprintingasof.qty as scprintingasof_qty,
		embr.qty as embr_qty,
		embrasof.qty as embrasof_qty,
		
		sew.qty as sew_qty,
		sew.amount as sew_amount,
		sew.smv as sew_smv,
		sewSmv.used_smv,
		exfactory.qty as exfactory_qty,
		exfactory.amount as exfactory_amount,
		exfactoryasof.qty as exfactoryasof_qty,
		exfactoryasof.amount as exfactoryasof_amount,
		exfactoryaslastmonth.qty as exfactoryaslastmonth_qty,
		exfactoryaslastmonth.amount as exfactoryaslastmonth_amount,
		earnings.amount eamount,
		vexpense.amount vamount,
		fexpense.amount famount,
		sal_prod_bill_expense.amount as sal_prod_bill_amount,
		bepunit.amount as bepunit_amount,
		cpm.amount as cpm_amount,
		yarn.amount as yarn_amount,
		trim.amount as trim_amount,
		fabprod.amount as fabprod_amount,
		fabprod.overhead_amount as fabprod_overhead_amount,
		emb.amount as emb_amount,
		emb.overhead_amount as emb_overhead_amount,
		commer.amount as commer_amount,
		comm.amount as comm_amount,
		other.amount as other_amount,
		cm.amount as cm_amount, 
		companies.code as company_code,

		companies.cutting_capacity_qty,
		companies.cutting_capacity_amount,

        companies.screen_print_capacity_qty,
		companies.screen_print_capacity_amount,

		companies.embroidery_capacity_qty,
		companies.embroidery_capacity_amount,



		companies.cartoning_capacity_qty,
		companies.cartoning_capacity_amount,

		emp_att.operator as emp_att_operator,
		emp_att.helper as emp_att_helper,
		emp_att.prod_staff as emp_att_prod_staff,
		emp_att.supporting_staff as emp_att_supporting_staff,
		emp_att.cutting_staff as emp_att_cutting_staff,
		emp_att.embroidery_staff as emp_att_embroidery_staff,
		emp_att.finishing_staff as emp_att_finishing_staff,
		emp_att.printing_staff as emp_att_printing_staff,
		


		emp_att.operator_salary,
		emp_att.helper_salary,
		emp_att.prod_stuff_salary,
		emp_att.operator_ot,
		emp_att.helper_ot,
		emp_att.daily_prod_bill,
		sum(subsections.qty) as qty ,
		sum(subsections.amount) as amount,
		sewasof.qty as sewasof_qty,
		sewasof.amount as sewasof_amount,
		monthtgt.qty as cuttgt_qty,
		monthtgt.plan_cut_qty as cuttgt_plan_cut_qty,
		monthtgt.amount as cuttgt_amount,
		screenprinttgt.qty as screenprinttgt_qty,
		embrotgt.qty as embrotgt_qty,
		aoptgt.qty as aoptgt_qty,
		expinvoice.qty as expinvoice_qty,
		mkt_cost_yarns.amount as mkt_cost_yarn_amount,
		mkt_cost_trims.amount as mkt_cost_trim_amount,
		mkt_cost_fabric_prods.amount as mkt_cost_fabric_prod_amount,
		mkt_cost_embs.amount as mkt_cost_emb_amount,
		mkt_costs_other.amount as mkt_costs_other_amount,
		mkt_cost_cms.amount as mkt_cost_cm_amount,
		mkt_cost_commercials.amount as mkt_cost_commercial_amount,
		mkt_cost_commissions.amount as mkt_cost_commission_amount
		'
		)
		->join('company_subsections', function($join)  {
			$join->on('company_subsections.subsection_id', '=', 'subsections.id');
			$join->on('subsections.is_treat_sewing_line', '=', 1);
			$join->on('subsections.projected_line_id', '=', 0);
			$join->on('subsections.status_id', '=', 1);
			$join->WhereNull('subsections.deleted_at');
			$join->WhereNull('company_subsections.deleted_at');
		})
		->leftJoin('companies', function($join)  {
			$join->on('companies.id', '=', 'company_subsections.company_id');
		})
		->leftJoin(\DB::raw("(SELECT 
			company_subsections.company_id,
			count(subsections.id) as ttl_qty
			FROM subsections  
			join company_subsections on company_subsections.subsection_id = subsections.id 
			where 
			subsections.is_treat_sewing_line=1 and 
		    subsections.deleted_at is null     and 
		    company_subsections.deleted_at is null  
			group by company_subsections.company_id) ttl"), "ttl.company_id", "=", "companies.id")
		->leftJoin(\DB::raw("(SELECT 
			company_subsections.company_id,
			count(subsections.id) as pjd_qty
			FROM subsections  
			join company_subsections on company_subsections.subsection_id = subsections.id 
			where 
			subsections.is_treat_sewing_line=1 and 
			subsections.projected_line_id=1 and
		    subsections.deleted_at is null     and 
		    company_subsections.deleted_at is null  
			group by company_subsections.company_id) pjd"), "pjd.company_id", "=", "companies.id")
		->leftJoin(\DB::raw("(SELECT 
			company_subsections.company_id,
			count(subsections.id) as act_qty
			FROM subsections  
			join company_subsections on company_subsections.subsection_id = subsections.id 
			where 
			subsections.is_treat_sewing_line=1 and 
			subsections.projected_line_id=0 and
			subsections.status_id=1            and
		    subsections.deleted_at is null     and 
		    company_subsections.deleted_at is null  
			group by company_subsections.company_id) act"), "act.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(SELECT 
			company_subsections.company_id,
			count(subsections.id) as iact_qty
			FROM subsections  
			join company_subsections on company_subsections.subsection_id = subsections.id 
			where 
			subsections.is_treat_sewing_line=1 and 
			subsections.projected_line_id=0 and
			subsections.status_id=0            and
		    subsections.deleted_at is null     and 
		    company_subsections.deleted_at is null  
			group by company_subsections.company_id) iact"), "iact.company_id", "=", "companies.id")
			->leftJoin(\DB::raw("(select m.produced_company_id as company_id,sum(m.qty) as qty, sum(m.amount) as amount from (SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			carton.qty as no_of_carton,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			left join (SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id
			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,sales_orders.produced_company_id,carton.qty,saleorders.rate) m group by m.produced_company_id) carton"), "carton.company_id", "=", "companies.id")

			->leftJoin(\DB::raw("(select m.produced_company_id as company_id,sum(m.qty) as qty, sum(m.amount) as amount from (SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			carton.qty as no_of_carton,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			left join (SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id
			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,sales_orders.produced_company_id,carton.qty,saleorders.rate) m group by m.produced_company_id) cartonasof"), "cartonasof.company_id", "=", "companies.id")

			->leftJoin(\DB::raw("(select m.produced_company_id as company_id,sum(m.qty) as qty, sum(m.amount) as amount from (SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id 
            join styles on styles.id = jobs.style_id
            and styles.id = style_pkgs.style_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where prod_gmt_ex_factories.exfactory_date>='".$date_to."' and 
			prod_gmt_ex_factories.exfactory_date<='".$date_to."'
			and sales_order_gmt_color_sizes.qty>0 and sales_order_gmt_color_sizes.deleted_at is null
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_ex_factories.exfactory_date>='".$date_to."' and 
			prod_gmt_ex_factories.exfactory_date<='".$date_to."'
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id) exfactory"), "exfactory.company_id", "=", "companies.id")

			->leftJoin(\DB::raw("(select m.produced_company_id as company_id,sum(m.qty) as qty, sum(m.amount) as amount from (
				SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id 
            join styles on styles.id = jobs.style_id
            and styles.id = style_pkgs.style_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where prod_gmt_ex_factories.exfactory_date>='".$start_date."' and 
			prod_gmt_ex_factories.exfactory_date<='".$date_to."'
			and sales_order_gmt_color_sizes.qty>0 and sales_order_gmt_color_sizes.deleted_at is null
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_ex_factories.exfactory_date>='".$start_date."' and 
			prod_gmt_ex_factories.exfactory_date<='".$date_to."'
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id) exfactoryasof"), "exfactoryasof.company_id", "=", "companies.id")

			->leftJoin(\DB::raw("(select m.produced_company_id as company_id,sum(m.qty) as qty, sum(m.amount) as amount from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id 
            join styles on styles.id = jobs.style_id
            and styles.id = style_pkgs.style_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id 
            join styles on styles.id = jobs.style_id
            
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where  
			prod_gmt_ex_factories.exfactory_date<='".$last_month."'
			and sales_orders.ship_date>='".$start_date."' and 
			sales_orders.ship_date<='".$end_date."'
			group by sales_orders.id
			) saleorders on saleorders.sale_order_id=sales_orders.id
			where 
			prod_gmt_ex_factories.exfactory_date<='".$last_month."'
			 and sales_orders.ship_date>='".$start_date."' and 
			sales_orders.ship_date<='".$end_date."'
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id) exfactoryaslastmonth"), "exfactoryaslastmonth.company_id", "=", "companies.id")

			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.qty) as qty,sum(m.amount) as amount,avg(m.rate) as rate from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				sales_order_gmt_color_sizes.qty,
				sales_order_gmt_color_sizes.rate,
				sales_order_gmt_color_sizes.amount

				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join styles on styles.id = jobs.style_id
				 
				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				group by 
				sales_orders.produced_company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				sales_order_gmt_color_sizes.id,
				sales_order_gmt_color_sizes.qty,
				sales_order_gmt_color_sizes.rate,
				sales_order_gmt_color_sizes.amount
				) m group by m.company_id) gmt"), "gmt.company_id", "=", "companies.id")

			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.qty) as qty,sum(m.plan_cut_qty) as plan_cut_qty,sum(m.amount) as amount,avg(m.rate) as rate from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				sales_order_gmt_color_sizes.qty,
				sales_order_gmt_color_sizes.plan_cut_qty,
				sales_order_gmt_color_sizes.rate,
				sales_order_gmt_color_sizes.amount

				FROM sales_orders
				join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				 
				where sales_orders.ship_date>='".$start_date."' and 
				sales_orders.ship_date<='".$end_date."' and 
				sales_orders.order_status !=2
				
				group by 
				sales_orders.produced_company_id,
				sales_order_gmt_color_sizes.id,
				sales_order_gmt_color_sizes.qty,
				sales_order_gmt_color_sizes.plan_cut_qty,
				sales_order_gmt_color_sizes.rate,
				sales_order_gmt_color_sizes.amount
				) m group by m.company_id) monthtgt"), "monthtgt.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(SELECT 
			company_subsections.company_id,
			count(wstudy_line_setups.id) as pdc_qty,
			sum(wstudy_line_setup_dtls.operator) as operator,
			sum(wstudy_line_setup_dtls.helper) as helper,
			sum(wstudy_line_setup_dtls.working_hour) as working_hour,
			sum(wstudy_line_setup_dtls.overtime_hour) as overtime_hour
			
			FROM subsections  
			join company_subsections on company_subsections.subsection_id = subsections.id 
            join wstudy_line_setup_lines on wstudy_line_setup_lines.subsection_id = subsections.id
            join wstudy_line_setups on wstudy_line_setups.id = wstudy_line_setup_lines.wstudy_line_setup_id and
            wstudy_line_setups.company_id = company_subsections.company_id
            join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and  '".$date_to."' between wstudy_line_setup_dtls.from_date and wstudy_line_setup_dtls.to_date
			where 
			subsections.is_treat_sewing_line=1 and 
			subsections.projected_line_id=0 and
			subsections.status_id=1            and
		    subsections.deleted_at is null     and 
		    company_subsections.deleted_at is null  
			group by company_subsections.company_id) pdc"), "pdc.company_id", "=", "companies.id")

		

		->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_cuttings.cut_qc_date>='".$date_to."' and 
				prod_gmt_cuttings.cut_qc_date<='".$date_to."'
			group by 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id) cutting"), "cutting.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_cuttings.cut_qc_date>='".$start_date."' and 
				prod_gmt_cuttings.cut_qc_date<='".$date_to."'
			group by 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id) cuttingasof"), "cuttingasof.company_id", "=", "companies.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.qty) as qty,sum(m.amount) as amount from (select 
		sales_orders.produced_company_id as company_id,
		prod_gmt_print_rcv_qties.qty,
		prod_gmt_print_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
		from prod_gmt_print_rcvs
		join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
		join suppliers on suppliers.id=prod_gmt_print_rcv_orders.supplier_id
		join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id  
		and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
		where prod_gmt_print_rcvs.receive_date>='".$date_to."' and 
		prod_gmt_print_rcvs.receive_date<='".$date_to."'
		group by 
		sales_orders.produced_company_id,
		prod_gmt_print_rcv_qties.id,
		prod_gmt_print_rcv_qties.qty,
		sales_order_gmt_color_sizes.id,
		sales_order_gmt_color_sizes.rate) m group by m.company_id) scprinting"), "scprinting.company_id", "=", "companies.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.qty) as qty,sum(m.amount) as amount from (select 
		sales_orders.produced_company_id as company_id,
		prod_gmt_print_rcv_qties.qty,
		prod_gmt_print_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
		from prod_gmt_print_rcvs
		join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
		join suppliers on suppliers.id=prod_gmt_print_rcv_orders.supplier_id
		join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id  
		and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
		where prod_gmt_print_rcvs.receive_date>='".$start_date."' and 
		prod_gmt_print_rcvs.receive_date<='".$date_to."'
		group by 
		sales_orders.produced_company_id,
		prod_gmt_print_rcv_qties.id,
		prod_gmt_print_rcv_qties.qty,
		sales_order_gmt_color_sizes.id,
		sales_order_gmt_color_sizes.rate) m group by m.company_id) scprintingasof"), "scprintingasof.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.qty) as qty,sum(m.amount) as amount from (select 
		sales_orders.produced_company_id as company_id,
		prod_gmt_emb_rcv_qties.qty,
		prod_gmt_emb_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
		from prod_gmt_emb_rcvs
		join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id = prod_gmt_emb_rcvs.id
		join suppliers on suppliers.id=prod_gmt_emb_rcv_orders.supplier_id
		join sales_order_countries on sales_order_countries.id = prod_gmt_emb_rcv_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		join prod_gmt_emb_rcv_qties on prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id = prod_gmt_emb_rcv_orders.id  
		and prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
		where prod_gmt_emb_rcvs.receive_date>='".$date_to."' and 
		prod_gmt_emb_rcvs.receive_date<='".$date_to."'
		group by 
		sales_orders.produced_company_id,
		prod_gmt_emb_rcv_qties.id,
		prod_gmt_emb_rcv_qties.qty,
		sales_order_gmt_color_sizes.id,
		sales_order_gmt_color_sizes.rate) m group by m.company_id) embr"), "embr.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.qty) as qty,sum(m.amount) as amount from (select 
		sales_orders.produced_company_id as company_id,
		prod_gmt_emb_rcv_qties.qty,
		prod_gmt_emb_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
		from prod_gmt_emb_rcvs
		join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id = prod_gmt_emb_rcvs.id
		join suppliers on suppliers.id=prod_gmt_emb_rcv_orders.supplier_id
		join sales_order_countries on sales_order_countries.id = prod_gmt_emb_rcv_orders.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
		join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
		join prod_gmt_emb_rcv_qties on prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id = prod_gmt_emb_rcv_orders.id  
		and prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
		where prod_gmt_emb_rcvs.receive_date>='".$start_date."' and 
		prod_gmt_emb_rcvs.receive_date<='".$date_to."'
		group by 
		sales_orders.produced_company_id,
		prod_gmt_emb_rcv_qties.id,
		prod_gmt_emb_rcv_qties.qty,
		sales_order_gmt_color_sizes.id,
		sales_order_gmt_color_sizes.rate) m group by m.company_id) embrasof"), "embrasof.company_id", "=", "companies.id")



		->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			sales_orders.produced_company_id as company_id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			wstudy_line_setup_dtls.from_date>='".$date_to."' and 
				wstudy_line_setup_dtls.to_date<='".$date_to."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
				prod_gmt_sewings.sew_qc_date<='".$date_to."'
			group by sales_orders.produced_company_id,prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id) sew"), "sew.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			sales_orders.produced_company_id as company_id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			wstudy_line_setup_dtls.from_date>='".$start_date."' and 
				wstudy_line_setup_dtls.to_date<='".$date_to."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where prod_gmt_sewings.sew_qc_date>='".$start_date."' and 
				prod_gmt_sewings.sew_qc_date<='".$date_to."'
			group by sales_orders.produced_company_id,prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id) sewasof"), "sewasof.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.used_smv) as used_smv from (SELECT 
			wstudy_line_setups.company_id,
			wstudy_line_setup_dtls.operator ,
			wstudy_line_setup_dtls.helper,
			wstudy_line_setup_dtls.working_hour,
			wstudy_line_setup_dtls.overtime_hour,
			((wstudy_line_setup_dtls.operator+wstudy_line_setup_dtls.helper)*(wstudy_line_setup_dtls.working_hour+wstudy_line_setup_dtls.overtime_hour))*60 as used_smv
			
			FROM wstudy_line_setups
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id 
			
		
			
			where wstudy_line_setup_dtls.from_date>='".$date_to."' and 
				wstudy_line_setup_dtls.to_date<='".$date_to."'
			group by 
			wstudy_line_setups.id,
			wstudy_line_setups.company_id,
			wstudy_line_setup_dtls.operator ,
			wstudy_line_setup_dtls.helper,
			wstudy_line_setup_dtls.working_hour,
			wstudy_line_setup_dtls.overtime_hour) m group by m.company_id) sewSmv"), "sewSmv.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(select acc_beps.company_id,sum(acc_bep_entries.amount)  as amount from acc_beps 
		join acc_bep_entries on acc_beps.id=acc_bep_entries.acc_bep_id 
		join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
		join acc_chart_sub_groups on acc_chart_sub_groups.id=acc_chart_ctrl_heads.acc_chart_sub_group_id
		where acc_chart_sub_groups.acc_chart_group_id=16 
		and  '".$date_to."' between acc_beps.start_date and acc_beps.end_date
		group by acc_beps.company_id) earnings"), "earnings.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(select acc_beps.company_id,sum(acc_bep_entries.amount)  as amount from acc_beps 
		join acc_bep_entries on acc_beps.id=acc_bep_entries.acc_bep_id 
		join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
		
		where acc_bep_entries.expense_type_id=1 
		and  '".$date_to."' between acc_beps.start_date and acc_beps.end_date
		group by acc_beps.company_id) vexpense"), "vexpense.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(select acc_beps.company_id,sum(acc_bep_entries.amount)  as amount from acc_beps 
		join acc_bep_entries on acc_beps.id=acc_bep_entries.acc_bep_id 
		join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
		
		where acc_bep_entries.expense_type_id=2 
		and  '".$date_to."' between acc_beps.start_date and acc_beps.end_date
		group by acc_beps.company_id) fexpense"), "fexpense.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(select acc_beps.company_id,sum(acc_bep_entries.amount)  as amount from acc_beps 
		join acc_bep_entries on acc_beps.id=acc_bep_entries.acc_bep_id 
		join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_bep_entries.acc_chart_ctrl_head_id
		
		where acc_bep_entries.salary_prod_bill_id in(1,2) 
		and  '".$date_to."' between acc_beps.start_date and acc_beps.end_date
		group by acc_beps.company_id) sal_prod_bill_expense"), "sal_prod_bill_expense.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(select keycontrols.company_id,sum(keycontrol_parameters.value) as amount from keycontrols 
		join keycontrol_parameters on keycontrols.id=keycontrol_parameters.keycontrol_id 
		where keycontrol_parameters.parameter_id=7 
		and  '".$date_to."' between keycontrol_parameters.from_date and keycontrol_parameters.to_date
		group by keycontrols.company_id) bepunit"), "bepunit.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(select keycontrols.company_id,sum(keycontrol_parameters.value) as amount from keycontrols 
		join keycontrol_parameters on keycontrols.id=keycontrol_parameters.keycontrol_id 
		where keycontrol_parameters.parameter_id=4 
		and  '".$date_to."' between keycontrol_parameters.from_date and keycontrol_parameters.to_date
		group by keycontrols.company_id) cpm"), "cpm.company_id", "=", "companies.id")


		


			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.yarn_amount) as amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_yarns.id as budget_yarn_id ,
				budget_yarns.ratio,
				budget_yarns.cons,
				budget_yarns.rate,
				budget_yarns.amount,
				budget_fabric_cons.grey_fab as grey_fab,
				budget_fabric_cons.grey_fab*budget_yarns.ratio/100 as yarn,
				budget_fabric_cons.grey_fab*budget_yarns.ratio/100*budget_yarns.rate as yarn_amount

				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id=styles.id
				join budget_yarns on budget_yarns.budget_id=budgets.id
				join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id and 
				sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				group by 
				sales_orders.produced_company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_yarns.id,
				budget_yarns.ratio,
				budget_yarns.cons,
				budget_yarns.rate,
				budget_yarns.amount,
				budget_fabric_cons.id,
				budget_fabric_cons.grey_fab
				) m group by m.company_id) yarn"), "yarn.company_id", "=", "companies.id")
			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.amount) as amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_trims.id as budget_trim_id ,
				budget_trim_cons.amount
				

				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id=styles.id

				join budget_trims on budget_trims.budget_id=budgets.id
				join budget_trim_cons on budget_trim_cons.budget_trim_id=budget_trims.id
				and budget_trim_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id 
				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				group by 
				sales_orders.produced_company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_trims.id,
				budget_trim_cons.id,
				sales_order_gmt_color_sizes.id,
				budget_trim_cons.amount
				) m group by m.company_id) trim"), "trim.company_id", "=", "companies.id")
			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.amount) as amount,sum(m.overhead_amount) as overhead_amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_fabric_prods.id as budget_fabric_prod_id ,
				budget_fabric_prod_cons.amount,
				budget_fabric_prod_cons.overhead_amount
				

				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id=styles.id

				join budget_fabric_prods on budget_fabric_prods.budget_id=budgets.id
				join budget_fabric_prod_cons on budget_fabric_prod_cons.budget_fabric_prod_id=budget_fabric_prods.id
				and budget_fabric_prod_cons.sales_order_id=sales_orders.id 
				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				group by 
				sales_orders.produced_company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_fabric_prods.id ,
				budget_fabric_prod_cons.id,
				budget_fabric_prod_cons.amount,
				budget_fabric_prod_cons.overhead_amount
				) m group by m.company_id) fabprod"), "fabprod.company_id", "=", "companies.id")

			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.bom_qty) as qty,sum(m.amount) as amount,sum(m.overhead_amount) as overhead_amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				budget_fabric_prods.id as budget_fabric_prod_id ,
				budget_fabric_prod_cons.bom_qty,
				budget_fabric_prod_cons.amount,
				budget_fabric_prod_cons.overhead_amount
				FROM sales_orders
				
				join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
				
				join jobs on jobs.id = sales_orders.job_id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id=styles.id

				join budget_fabric_prods on budget_fabric_prods.budget_id=budgets.id
				join budget_fabric_prod_cons on budget_fabric_prod_cons.budget_fabric_prod_id=budget_fabric_prods.id
				and budget_fabric_prod_cons.sales_order_id=sales_orders.id 
				left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
				where sales_orders.ship_date>='".$start_date."' and 
				sales_orders.ship_date<='".$end_date."' and
				production_processes.production_area_id =25 
				group by 
				sales_orders.produced_company_id,
				budget_fabric_prods.id ,
				budget_fabric_prod_cons.id,
				budget_fabric_prod_cons.bom_qty,
				budget_fabric_prod_cons.amount,
				budget_fabric_prod_cons.overhead_amount
				) m group by m.company_id) aoptgt"), "aoptgt.company_id", "=", "companies.id")
			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.amount) as amount,sum(m.overhead_amount) as overhead_amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_embs.id as budget_emb_id ,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id=styles.id

				join budget_embs on budget_embs.budget_id=budgets.id
				join budget_emb_cons on budget_emb_cons.budget_emb_id=budget_embs.id
				and budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id 
				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				group by 
				sales_orders.produced_company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_embs.id,
				budget_emb_cons.id,
				sales_order_gmt_color_sizes.id,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				) m group by m.company_id) emb"), "emb.company_id", "=", "companies.id")
			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.req_cons) as qty,sum(m.amount) as amount,sum(m.overhead_amount) as overhead_amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				budget_embs.id as budget_emb_id ,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				FROM sales_orders
				join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join budgets on budgets.job_id=jobs.id
				join budget_embs on budget_embs.budget_id=budgets.id
				join budget_emb_cons on budget_emb_cons.budget_emb_id=budget_embs.id
				and budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id 
				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where sales_orders.ship_date>='".$start_date."' and 
				sales_orders.ship_date<='".$end_date."'
				and production_processes.production_area_id =45
				group by 
				sales_orders.produced_company_id,
				budget_embs.id,
				budget_emb_cons.id,
				sales_order_gmt_color_sizes.id,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				) m group by m.company_id) screenprinttgt"), "screenprinttgt.company_id", "=", "companies.id")
			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.req_cons) as qty,sum(m.amount) as amount,sum(m.overhead_amount) as overhead_amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				budget_embs.id as budget_emb_id ,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount

				FROM sales_orders
				 
				join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join budgets on budgets.job_id=jobs.id
				

				join budget_embs on budget_embs.budget_id=budgets.id
				join budget_emb_cons on budget_emb_cons.budget_emb_id=budget_embs.id
				and budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id 

				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id

				where sales_orders.ship_date>='".$start_date."' and 
				sales_orders.ship_date<='".$end_date."'
				and production_processes.production_area_id =50
				group by 
				sales_orders.produced_company_id,
				budget_embs.id,
				budget_emb_cons.id,
				sales_order_gmt_color_sizes.id,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				) m group by m.company_id) embrotgt"), "embrotgt.company_id", "=", "companies.id")

			->leftJoin(\DB::raw("(SELECT commer_m.company_id,sum(commer_m.commer_amount) as amount from (SELECT
                sales_orders.id,
                sales_orders.sale_order_no, 
				sales_orders.produced_company_id as company_id,
				budget_commercials.amount,
				budget_commercials.rate,
				yarn.amount as yarn_amount,
				trims.amount as trim_amount,
				prods.amount as prod_amount,
				embs.amount as emb_amount,
				others.amount as other_amount,
				((case when yarn.amount is null then 0 else yarn.amount end)+
				(case when trims.amount is null then 0 else trims.amount end)+
				(case when prods.amount is null then 0 else prods.amount end)+
				(case when embs.amount is null then 0 else embs.amount end))*((case when budget_commercials.rate is null then 0/100 else budget_commercials.rate/100 end)) as commer_amount
				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id = styles.id
				left join budget_commercials on budget_commercials.budget_id = budgets.id
				left join (
				SELECT m.sale_order_id,m.company_id,sum(m.yarn_amount) as amount from (
				SELECT sales_orders.id as sale_order_id,sales_orders.produced_company_id as company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_yarns.id as budget_yarn_id ,
				budget_yarns.ratio,
				budget_yarns.cons,
				budget_yarns.rate,
				budget_yarns.amount,
				budget_fabric_cons.grey_fab as grey_fab,
				budget_fabric_cons.grey_fab*budget_yarns.ratio/100 as yarn,
				budget_fabric_cons.grey_fab*budget_yarns.ratio/100*budget_yarns.rate as yarn_amount
				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id=styles.id
				join budget_yarns on budget_yarns.budget_id=budgets.id
				join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id and 
				sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				group by 
				sales_orders.id,
				sales_orders.produced_company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_yarns.id,
				budget_yarns.ratio,
				budget_yarns.cons,
				budget_yarns.rate,
				budget_yarns.amount,
				budget_fabric_cons.id,
				budget_fabric_cons.grey_fab
				) m group by m.company_id,m.sale_order_id
				) yarn on sales_orders.id=yarn.sale_order_id
				
				left join (
				SELECT m.sale_order_id,m.company_id,sum(m.amount) as amount from (
				SELECT
				sales_orders.id as sale_order_id,      
				sales_orders.produced_company_id as company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_trims.id as budget_trim_id ,
				budget_trim_cons.amount
				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id=styles.id
				join budget_trims on budget_trims.budget_id=budgets.id
				join budget_trim_cons on budget_trim_cons.budget_trim_id=budget_trims.id 
				and budget_trim_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				group by 
				sales_orders.id,
				sales_orders.produced_company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_trims.id  ,
				budget_trim_cons.id,
				sales_order_gmt_color_sizes.id,
				budget_trim_cons.amount
				) m group by m.company_id,m.sale_order_id
				) trims on sales_orders.id=trims.sale_order_id
				
				left join (SELECT m.sale_order_id,m.company_id,sum(m.amount) as amount from (
								SELECT 
								sales_orders.id as sale_order_id,      
								sales_orders.produced_company_id as company_id,
								prod_gmt_carton_entries.id,
								prod_gmt_carton_details.sales_order_country_id,
								budget_fabric_prods.id as budget_fabric_prod_id ,
								budget_fabric_prod_cons.amount
								

								FROM prod_gmt_carton_entries
								join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
								join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
								join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
								join jobs on jobs.id = sales_orders.job_id
								join styles on styles.id = jobs.style_id
								join budgets on budgets.style_id=styles.id

								join budget_fabric_prods on budget_fabric_prods.budget_id=budgets.id
								join budget_fabric_prod_cons on budget_fabric_prod_cons.budget_fabric_prod_id=budget_fabric_prods.id
								and budget_fabric_prod_cons.sales_order_id=sales_orders.id 

								

								where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
								prod_gmt_carton_entries.carton_date<='".$date_to."'
								group by 
								sales_orders.id,
								sales_orders.produced_company_id,
								prod_gmt_carton_entries.id,
								prod_gmt_carton_details.sales_order_country_id,
								budget_fabric_prods.id ,
								budget_fabric_prod_cons.id,
								budget_fabric_prod_cons.amount
								) m group by m.company_id,m.sale_order_id) prods on sales_orders.id=prods.sale_order_id
								
								
								left join (
								SELECT m.sale_order_id,m.company_id,sum(m.amount) as amount from (
								SELECT
								sales_orders.id as sale_order_id,       
								sales_orders.produced_company_id as company_id,
								prod_gmt_carton_entries.id,
								prod_gmt_carton_details.sales_order_country_id,
								budget_embs.id as budget_emb_id ,
								budget_emb_cons.amount
								

								FROM prod_gmt_carton_entries
								join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
								join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
								join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
								join jobs on jobs.id = sales_orders.job_id
								join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
								join styles on styles.id = jobs.style_id
								join budgets on budgets.style_id=styles.id

								join budget_embs on budget_embs.budget_id=budgets.id
								join budget_emb_cons on budget_emb_cons.budget_emb_id=budget_embs.id
								and budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id 
								where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
								prod_gmt_carton_entries.carton_date<='".$date_to."'
								group by
								sales_orders.id, 
								sales_orders.produced_company_id,
								prod_gmt_carton_entries.id,
								prod_gmt_carton_details.sales_order_country_id,
								budget_embs.id,
								budget_emb_cons.id,
								sales_order_gmt_color_sizes.id,
								budget_emb_cons.amount
								) m group by m.company_id,m.sale_order_id
								) embs on sales_orders.id=embs.sale_order_id
								
								
								left join (SELECT m.sale_order_id,m.company_id,sum(m.other_amount) as amount from (
								SELECT
								sales_orders.id as sale_order_id,      
								sales_orders.produced_company_id as company_id,
								prod_gmt_carton_entries.id,
								prod_gmt_carton_details.sales_order_country_id,
								sales_order_gmt_color_sizes.id,
								sales_order_gmt_color_sizes.qty,
								budget_others.id,
								budget_others.amount,
								(sales_order_gmt_color_sizes.qty*(budget_others.amount/12)) as other_amount
								

								FROM prod_gmt_carton_entries
								join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
								join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
								join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
								join jobs on jobs.id = sales_orders.job_id
								join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
								join styles on styles.id = jobs.style_id
								join budgets on budgets.style_id=styles.id
								join budget_others on budget_others.budget_id=budgets.id
								 
								where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
								prod_gmt_carton_entries.carton_date<='".$date_to."'
								
								group by 
								sales_orders.id,
								sales_orders.produced_company_id,
								prod_gmt_carton_entries.id,
								prod_gmt_carton_details.sales_order_country_id,
								sales_order_gmt_color_sizes.id,
								sales_order_gmt_color_sizes.qty,
								budget_others.id,
								budget_others.amount
								) m group by m.company_id,m.sale_order_id) others on sales_orders.id=others.sale_order_id
				
								
				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				group by 
				sales_orders.id,
				sales_orders.sale_order_no,
				sales_orders.produced_company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				budget_commercials.id,
				budget_commercials.amount,
				budget_commercials.rate,
				yarn.amount,
				trims.amount,
				prods.amount,
				embs.amount,
				others.amount
				
			) commer_m group by commer_m.company_id) commer"), "commer.company_id", "=", "companies.id")
			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.commi_amount) as amount from (SELECT      
				sales_orders.produced_company_id as company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				sales_order_gmt_color_sizes.id,
				sales_order_gmt_color_sizes.amount,
				budget_commissions.id,
				budget_commissions.rate,
				(sales_order_gmt_color_sizes.amount*(budget_commissions.rate/100)) as commi_amount
				

				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id=styles.id
				join budget_commissions on budget_commissions.budget_id=budgets.id
				 
				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				
				group by 
				sales_orders.produced_company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				sales_order_gmt_color_sizes.id,
				sales_order_gmt_color_sizes.amount,
				budget_commissions.id,
				budget_commissions.rate) m group by m.company_id) comm"), "comm.company_id", "=", "companies.id")


		->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.other_amount) as amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				sales_order_gmt_color_sizes.id,
				sales_order_gmt_color_sizes.qty,
				budget_others.id,
				budget_others.amount,
				(sales_order_gmt_color_sizes.qty*(budget_others.amount/12)) as other_amount
				

				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id=styles.id
				join budget_others on budget_others.budget_id=budgets.id
				 
				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				
				group by 
				sales_orders.produced_company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				sales_order_gmt_color_sizes.id,
				sales_order_gmt_color_sizes.qty,
				budget_others.id,
				budget_others.amount
				) m group by m.company_id) other"), "other.company_id", "=", "companies.id")

			->leftJoin(\DB::raw("(SELECT m.company_id,sum(m.cm_amount) as amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				prod_gmt_carton_entries.id,
				prod_gmt_carton_details.sales_order_country_id,
				sales_order_gmt_color_sizes.id,
				sales_order_gmt_color_sizes.qty,
				budget_cms.id,
				budget_cms.amount,
				(sales_order_gmt_color_sizes.qty*budget_cms.cm_per_pcs) as cm_amount
				

				FROM prod_gmt_carton_entries
				join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
				join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
				join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
				join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
				join styles on styles.id = jobs.style_id
				join budgets on budgets.style_id=styles.id
				join budget_cms on budget_cms.budget_id=budgets.id and style_gmts.id= budget_cms.style_gmt_id
				 
				where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				
				
				) m group by m.company_id) cm"), "cm.company_id", "=", "companies.id")

		->leftJoin(\DB::raw("(SELECT 
			employee_attendences.company_id,
			sum(employee_attendences.operator) as operator,
			sum(employee_attendences.helper) as helper,
			sum(employee_attendences.prod_staff) as prod_staff,
			sum(employee_attendences.supporting_staff) as supporting_staff,
			sum(employee_attendences.cutting_staff) as cutting_staff,
			sum(employee_attendences.embroidery_staff) as embroidery_staff,
            sum(employee_attendences.finishing_staff) as finishing_staff,
            sum(employee_attendences.printing_staff) as printing_staff,
            
			sum(employee_attendences.operator_salary) as operator_salary,
			sum(employee_attendences.helper_salary) as helper_salary,
			sum(employee_attendences.prod_stuff_salary) as prod_stuff_salary,
			sum(employee_attendences.operator_ot) as operator_ot,
			sum(employee_attendences.helper_ot) as helper_ot,
			sum(employee_attendences.daily_prod_bill) as daily_prod_bill
			FROM employee_attendences
			where employee_attendences.attendence_date>='".$date_to."' and 
				employee_attendences.attendence_date<='".$date_to."'
			group by employee_attendences.company_id) emp_att"), "emp_att.company_id", "=", "companies.id")
		->leftJoin(\DB::raw("(SELECT      
				sales_orders.produced_company_id as company_id,
				sum(exp_invoice_orders.qty) as qty
				FROM exp_invoices
				join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id
				join exp_pi_orders on exp_pi_orders.id = exp_invoice_orders.exp_pi_order_id
				join sales_orders on sales_orders.id = exp_pi_orders.sales_order_id
				where exp_invoices.invoice_date>='".$start_date."' and 
				exp_invoices.invoice_date<='".$end_date."'
				group by 
				sales_orders.produced_company_id) expinvoice"), "expinvoice.company_id", "=", "companies.id")
		

		->leftJoin(\DB::raw("(
			select m.company_id,sum(m.offer_qty) as offer_qty from (select sales_orders.produced_company_id as company_id,mkt_costs.style_id,mktcosts.offer_qty 
			from prod_gmt_carton_entries
			join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join mkt_costs on mkt_costs.style_id = styles.id
			join(
			select mkt_costs.style_id, 
			sum(mkt_costs.offer_qty)  as offer_qty 
			from mkt_costs
			group by mkt_costs.style_id
			) mktcosts on mktcosts.style_id=mkt_costs.style_id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.produced_company_id,mkt_costs.style_id,mktcosts.offer_qty) m group by m.company_id
			) mkt_costs"), "mkt_costs.company_id", "=", "companies.id")

		
			->leftJoin(\DB::raw("(
			select m.company_id,sum(m.amount) as amount from (
			select
			sales_orders.id as sale_order_id, 
			sales_orders.produced_company_id as company_id,
			mkt_costs.style_id,
			(mktyarns.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
			from prod_gmt_carton_entries
			join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join mkt_costs on mkt_costs.style_id = styles.id
			join(
			select mkt_costs.style_id, 
			sum(mkt_cost_yarns.amount)  as amount 
			from mkt_cost_yarns
			join mkt_costs on mkt_costs.id = mkt_cost_yarns.mkt_cost_id
			group by mkt_costs.style_id
			) mktyarns on mktyarns.style_id=mkt_costs.style_id
			join(
			select sales_orders.id as sale_order_id, 
			sum(sales_order_gmt_color_sizes.qty)  as qty 
			from sales_order_gmt_color_sizes
			join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) slaeprders on slaeprders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,
			sales_orders.produced_company_id,
			mkt_costs.style_id,
			mkt_costs.costing_unit_id,
			mktyarns.amount,
			slaeprders.qty
			) m group by m.company_id
			) mkt_cost_yarns"), "mkt_cost_yarns.company_id", "=", "companies.id")


		
		->leftJoin(\DB::raw("(
		select m.company_id,sum(m.amount) as amount from (
		select
		sales_orders.id as sale_order_id, 
		sales_orders.produced_company_id as company_id,
		mkt_costs.style_id,
		(mkttrims.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
		from prod_gmt_carton_entries
		join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join styles on styles.id = jobs.style_id
		join mkt_costs on mkt_costs.style_id = styles.id
		join(
		select mkt_costs.style_id, 
		sum(mkt_cost_trims.amount)  as amount 
		from mkt_cost_trims
		join mkt_costs on mkt_costs.id = mkt_cost_trims.mkt_cost_id
		group by mkt_costs.style_id
		) mkttrims on mkttrims.style_id=mkt_costs.style_id
		join(
		select sales_orders.id as sale_order_id, 
		sum(sales_order_gmt_color_sizes.qty)  as qty 
		from sales_order_gmt_color_sizes
		join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) slaeprders on slaeprders.sale_order_id=sales_orders.id
		where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
		prod_gmt_carton_entries.carton_date<='".$date_to."'
		group by sales_orders.id,
		sales_orders.produced_company_id,
		mkt_costs.style_id,
		mkt_costs.costing_unit_id,
		mkttrims.amount,
		slaeprders.qty
		) m group by m.company_id
		) mkt_cost_trims"), "mkt_cost_trims.company_id", "=", "companies.id")
		
		->leftJoin(\DB::raw("(
		select m.company_id,sum(m.amount) as amount from (
		select
		sales_orders.id as sale_order_id, 
		sales_orders.produced_company_id as company_id,
		mkt_costs.style_id,
		(mktprods.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
		from prod_gmt_carton_entries
		join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join styles on styles.id = jobs.style_id
		join mkt_costs on mkt_costs.style_id = styles.id
		join(
		select mkt_costs.style_id, 
		sum(mkt_cost_fabric_prods.amount)  as amount 
		from mkt_cost_fabric_prods
		join mkt_costs on mkt_costs.id = mkt_cost_fabric_prods.mkt_cost_id
		group by mkt_costs.style_id
		) mktprods on mktprods.style_id=mkt_costs.style_id
		join(
		select sales_orders.id as sale_order_id, 
		sum(sales_order_gmt_color_sizes.qty)  as qty 
		from sales_order_gmt_color_sizes
		join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) slaeprders on slaeprders.sale_order_id=sales_orders.id
		where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
		prod_gmt_carton_entries.carton_date<='".$date_to."'
		group by sales_orders.id,
		sales_orders.produced_company_id,
		mkt_costs.style_id,
		mkt_costs.costing_unit_id,
		mktprods.amount,
		slaeprders.qty
		) m group by m.company_id
		) mkt_cost_fabric_prods"), "mkt_cost_fabric_prods.company_id", "=", "companies.id")

		
			->leftJoin(\DB::raw("(
			select m.company_id,sum(m.amount) as amount from (
			select
			sales_orders.id as sale_order_id, 
			sales_orders.produced_company_id as company_id,
			mkt_costs.style_id,
			(mktembs.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
			from prod_gmt_carton_entries
			join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join mkt_costs on mkt_costs.style_id = styles.id
			join(
			select mkt_costs.style_id, 
			sum(mkt_cost_embs.amount)  as amount 
			from mkt_cost_embs
			join mkt_costs on mkt_costs.id = mkt_cost_embs.mkt_cost_id
			group by mkt_costs.style_id
			) mktembs on mktembs.style_id=mkt_costs.style_id
			join(
			select sales_orders.id as sale_order_id, 
			sum(sales_order_gmt_color_sizes.qty)  as qty 
			from sales_order_gmt_color_sizes
			join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) slaeprders on slaeprders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,
			sales_orders.produced_company_id,
			mkt_costs.style_id,
			mkt_costs.costing_unit_id,
			mktembs.amount,
			slaeprders.qty
			) m group by m.company_id) mkt_cost_embs"), "mkt_cost_embs.company_id", "=", "companies.id")

		
			->leftJoin(\DB::raw("(
			select m.company_id,sum(m.amount) as amount from (
			select
			sales_orders.id as sale_order_id, 
			sales_orders.produced_company_id as company_id,
			mkt_costs.style_id,
			(mktothers.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
			from prod_gmt_carton_entries
			join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join mkt_costs on mkt_costs.style_id = styles.id
			join(
			select mkt_costs.style_id, 
			sum(mkt_cost_others.amount)  as amount 
			from mkt_cost_others
			join mkt_costs on mkt_costs.id = mkt_cost_others.mkt_cost_id
			group by mkt_costs.style_id
			) mktothers on mktothers.style_id=mkt_costs.style_id
			join(
			select sales_orders.id as sale_order_id, 
			sum(sales_order_gmt_color_sizes.qty)  as qty 
			from sales_order_gmt_color_sizes
			join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) slaeprders on slaeprders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,
			sales_orders.produced_company_id,
			mkt_costs.style_id,
			mkt_costs.costing_unit_id,
			mktothers.amount,
			slaeprders.qty
			) m group by m.company_id) mkt_costs_other"), "mkt_costs_other.company_id", "=", "companies.id")

		
		->leftJoin(\DB::raw("(
		select m.company_id,sum(m.amount) as amount from (
		select
		sales_orders.id as sale_order_id, 
		sales_orders.produced_company_id as company_id,
		mkt_costs.style_id,
		(mktcms.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
		from prod_gmt_carton_entries
		join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join styles on styles.id = jobs.style_id
		join mkt_costs on mkt_costs.style_id = styles.id
		join(
		select mkt_costs.style_id, 
		sum(mkt_cost_cms.amount)  as amount 
		from mkt_cost_cms
		join mkt_costs on mkt_costs.id = mkt_cost_cms.mkt_cost_id
		group by mkt_costs.style_id
		) mktcms on mktcms.style_id=mkt_costs.style_id
		join(
		select sales_orders.id as sale_order_id, 
		sum(sales_order_gmt_color_sizes.qty)  as qty 
		from sales_order_gmt_color_sizes
		join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) slaeprders on slaeprders.sale_order_id=sales_orders.id
		where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
		prod_gmt_carton_entries.carton_date<='".$date_to."'
		group by sales_orders.id,
		sales_orders.produced_company_id,
		mkt_costs.style_id,
		mkt_costs.costing_unit_id,
		mktcms.amount,
		slaeprders.qty
		) m group by m.company_id
		) mkt_cost_cms"), "mkt_cost_cms.company_id", "=", "companies.id")

		
		->leftJoin(\DB::raw("(
		select m.company_id,sum(m.amount) as amount from (
		select
		sales_orders.id as sale_order_id, 
		sales_orders.produced_company_id as company_id,
		mkt_costs.style_id,
		(mktcommers.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
		from prod_gmt_carton_entries
		join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join styles on styles.id = jobs.style_id
		join mkt_costs on mkt_costs.style_id = styles.id
		join(
		select mkt_costs.style_id, 
		sum(mkt_cost_commercials.amount)  as amount 
		from mkt_cost_commercials
		join mkt_costs on mkt_costs.id = mkt_cost_commercials.mkt_cost_id
		group by mkt_costs.style_id
		) mktcommers on mktcommers.style_id=mkt_costs.style_id
		join(
		select sales_orders.id as sale_order_id, 
		sum(sales_order_gmt_color_sizes.qty)  as qty 
		from sales_order_gmt_color_sizes
		join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) slaeprders on slaeprders.sale_order_id=sales_orders.id
		where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
		prod_gmt_carton_entries.carton_date<='".$date_to."'
		group by sales_orders.id,
		sales_orders.produced_company_id,
		mkt_costs.style_id,
		mkt_costs.costing_unit_id,
		mktcommers.amount,
		slaeprders.qty
		) m group by m.company_id
		) mkt_cost_commercials"), "mkt_cost_commercials.company_id", "=", "companies.id")

		
			->leftJoin(\DB::raw("(
			select m.company_id,sum(m.amount) as amount from (
			select
			sales_orders.id as sale_order_id, 
			sales_orders.produced_company_id as company_id,
			mkt_costs.style_id,
			(mktcommis.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
			from prod_gmt_carton_entries
			join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join mkt_costs on mkt_costs.style_id = styles.id
			join(
			select mkt_costs.style_id, 
			sum(mkt_cost_commissions.amount)  as amount 
			from mkt_cost_commissions
			join mkt_costs on mkt_costs.id = mkt_cost_commissions.mkt_cost_id
			group by mkt_costs.style_id
			) mktcommis on mktcommis.style_id=mkt_costs.style_id
			join(
			select sales_orders.id as sale_order_id, 
			sum(sales_order_gmt_color_sizes.qty)  as qty 
			from sales_order_gmt_color_sizes
			join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) slaeprders on slaeprders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,
			sales_orders.produced_company_id,
			mkt_costs.style_id,
			mkt_costs.costing_unit_id,
			mktcommis.amount,
			slaeprders.qty
			) m group by m.company_id) mkt_cost_commissions"), "mkt_cost_commissions.company_id", "=", "companies.id")

		->groupBy([
		'company_subsections.company_id',
		'companies.ceo',
		'ttl.ttl_qty',
		'pjd.pjd_qty',
		'act.act_qty',
		'iact.iact_qty',
		'carton.qty',
		'carton.amount',
		'cartonasof.qty',
		'cartonasof.amount',
		'gmt.rate',
		'gmt.qty',
		'gmt.amount',
		'pdc.pdc_qty',
		'pdc.operator',
		'pdc.helper',
		'pdc.working_hour',
		'pdc.overtime_hour',
		'companies.code',
		'companies.cutting_capacity_qty',
		'companies.cutting_capacity_amount',
        'companies.screen_print_capacity_qty',
		'companies.screen_print_capacity_amount',
		'companies.embroidery_capacity_qty',
		'companies.embroidery_capacity_amount',
		'companies.cartoning_capacity_qty',
		'companies.cartoning_capacity_amount',
		'cutting.qty',
		'cuttingasof.qty',
		'scprinting.qty',
		'scprintingasof.qty',
		'embr.qty',
		'embrasof.qty',
		'sew.qty',
		'sew.amount',
		'sew.smv',
		'sewSmv.used_smv',
		'exfactory.qty',
		'exfactoryasof.qty',
		'exfactory.amount',
		'exfactoryaslastmonth.qty',
		'exfactoryaslastmonth.amount',
		'exfactoryasof.amount',
		'earnings.amount',
		'vexpense.amount',
		'fexpense.amount',
		'sal_prod_bill_expense.amount',
		'bepunit.amount',
		'cpm.amount',
		'yarn.amount',
		'trim.amount',
		'fabprod.amount',
		'fabprod.overhead_amount',
		'emb.amount',
		'emb.overhead_amount',
		'commer.amount',
		'comm.amount',
		'other.amount',
		'cm.amount',
		'emp_att.operator',
		'emp_att.helper',
		'emp_att.prod_staff',
		'emp_att.supporting_staff',
		'emp_att.cutting_staff',
		'emp_att.embroidery_staff',
		'emp_att.finishing_staff', 
        'emp_att.printing_staff',
		'emp_att.operator_salary',
		'emp_att.helper_salary',
		'emp_att.prod_stuff_salary',
		'emp_att.operator_ot',
		'emp_att.helper_ot',
		'emp_att.daily_prod_bill',
		'sewasof.qty',
		'sewasof.amount',
		'monthtgt.qty',
		'monthtgt.plan_cut_qty',
		'monthtgt.amount',
		'screenprinttgt.qty',
		'embrotgt.qty',
		'aoptgt.qty',
		'expinvoice.qty',
		'mkt_cost_yarns.amount',
		'mkt_cost_trims.amount',
		'mkt_cost_fabric_prods.amount',
		'mkt_cost_embs.amount',
		'mkt_costs_other.amount',
		'mkt_cost_cms.amount',
		'mkt_cost_commercials.amount',
		'mkt_cost_commissions.amount',
		])
		->orderBy('company_subsections.company_id')
		->get();
		return $rows;
    }

   public  function getSewing(){
		$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=request('company_id',0);
		$subsections=$this->wstudylinesetup
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
        })
        ->join('wstudy_line_setup_lines', function($join)  {
            $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('wstudy_line_setup_dtls', function($join) {
			$join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
		})
        ->leftJoin('subsections', function($join)  {
            $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
        })
        ->leftJoin('floors', function($join)  {
            $join->on('floors.id', '=', 'subsections.floor_id');
        })
         ->leftJoin('employees', function($join)  {
            $join->on('employees.id', '=', 'subsections.employee_id');
        })
        ->when($date_to, function ($q) use($date_to){
		return $q->where('wstudy_line_setup_dtls.from_date', '>=',$date_to);
		})
		->when($date_to, function ($q) use($date_to){
		return $q->where('wstudy_line_setup_dtls.to_date', '<=',$date_to);
		})
		->where([['wstudy_line_setups.company_id','=',$company_id]])

        ->get([
            'wstudy_line_setups.id',
            'subsections.name',
            'subsections.code',
            'floors.name as floor_name',
            'employees.name as employee_name',
            'subsections.qty',
		    'subsections.amount'
        ]);
        $lineNames=Array();
        $lineCode=Array();
        $lineFloor=Array();
        $lineCheif=Array();
        $capacityQty=Array();
        $capacityAmount=Array();
        foreach($subsections as $subsection)
        {
           $lineNames[$subsection->id][]=$subsection->name;
           $lineCode[$subsection->id][]=$subsection->code;
           $lineFloor[$subsection->id][]=$subsection->floor_name;
           $lineCheif[$subsection->id][]=$subsection->employee_name;
           $capacityQty[$subsection->id][]=$subsection->qty;
           $capacityAmount[$subsection->id][]=$subsection->amount;
        }


        $results = \DB::select("
			select 
			wstudy_line_setups.id,
			sales_orders.id as sale_order_id ,
			sales_orders.sale_order_no,
			styles.buyer_id,
			buyers.name,
			style_gmts.id as style_gmt_id,
			style_gmts.smv,
			item_accounts.item_description as item_name,
			sales_order_gmt_color_sizes.qty,
			sales_order_gmt_color_sizes.rate
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join styles on styles.id = style_gmts.style_id
			join buyers on buyers.id=styles.buyer_id
			join item_accounts on item_accounts.id=style_gmts.item_account_id
			right join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
			prod_gmt_sewings.sew_qc_date<='".$date_to."'
			and wstudy_line_setups.company_id=?
			group by 
			wstudy_line_setups.id,
			sales_orders.id,
			styles.buyer_id,
			buyers.name,
			sales_orders.sale_order_no,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.qty,
			sales_order_gmt_color_sizes.rate,
			style_gmts.id,
			item_accounts.item_description,
			style_gmts.smv
			", [$company_id]);

             $buyerName=array();
             $orderNo=array();
             $orderQty=array();
             $orderAmount=array();
             $itemAccounts=array();
             $itemSmv=array();
			
			foreach($results as $result)
			{
				 $amount=$result->qty*$result->rate;
				 $buyerName[$result->id][$result->buyer_id]=$result->name;
				 $orderNo[$result->id][$result->sale_order_id]=$result->sale_order_no;
				 $itemAccounts[$result->id][$result->style_gmt_id]=$result->item_name;
				 $itemSmv[$result->id][$result->style_gmt_id]=$result->smv;

				 if(isset($orderQty[$result->id][$result->sale_order_id]))
				 {
				     $orderQty[$result->id][$result->sale_order_id]+=$result->qty;
				 }
				 else
				 {
				 	$orderQty[$result->id][$result->sale_order_id]=$result->qty;
				 }
				 if(isset($orderAmount[$result->id][$result->sale_order_id]))
				 {
				     $orderAmount[$result->id][$result->sale_order_id]+=$amount;
				 }
				 else
				 {
				 	$orderAmount[$result->id][$result->sale_order_id]=$amount;
				 }

			}
		$prodgmtsewing=$this->wstudylinesetup
		->join('wstudy_line_setup_dtls', function($join) use($date_to) {
			$join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
			$join->where('wstudy_line_setup_dtls.from_date', '>=',$date_to);
			$join->where('wstudy_line_setup_dtls.to_date', '<=',$date_to);
		})
		->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			wstudy_line_setups.id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			wstudy_line_setup_dtls.from_date>='".$date_to."' and 
				wstudy_line_setup_dtls.to_date<='".$date_to."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
				prod_gmt_sewings.sew_qc_date<='".$date_to."'
			group by 
			wstudy_line_setups.id,
			prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
			sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
			style_gmts.smv
			) m group by m.id) sew"), "sew.id", "=", "wstudy_line_setups.id")

		->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			wstudy_line_setups.id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*budget_cms.cm_per_pcs as amount
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			wstudy_line_setup_dtls.from_date>='".$date_to."' and 
				wstudy_line_setup_dtls.to_date<='".$date_to."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			join budgets on budgets.style_id=style_gmts.style_id
			join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
				prod_gmt_sewings.sew_qc_date<='".$date_to."'
			
			) m group by m.id) sewCM"), "sewCM.id", "=", "wstudy_line_setups.id")

		->leftJoin(\DB::raw("(SELECT m.id,sum(m.yarn_amount) as amount from (select 
			wstudy_line_setups.id,
			budget_yarns.id as budget_yarn_id ,
			budget_yarns.ratio,
			budget_yarns.cons,
			budget_yarns.rate,
			budget_yarns.amount,
			sum(budget_fabric_cons.grey_fab) as grey_fab,
			sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,
			(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join budgets on budgets.style_id=styles.id
			join budget_yarns on budget_yarns.budget_id=budgets.id
			join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id and 
			sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 

			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
			prod_gmt_sewings.sew_qc_date<='".$date_to."'
			group by 
			wstudy_line_setups.id,
			budget_yarns.id,
			budget_yarns.ratio,
			budget_yarns.cons,
			budget_yarns.rate,
			budget_yarns.amount
			) m group by m.id) yarns"), "yarns.id", "=", "wstudy_line_setups.id")

		->leftJoin(\DB::raw("(select 
			wstudy_line_setups.id,
			sum(budget_trim_cons.amount) as amount
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join styles on styles.id = jobs.style_id
			join budgets on budgets.style_id=styles.id
			join budget_trims on budget_trims.budget_id=budgets.id
			join budget_trim_cons on budget_trim_cons.budget_trim_id=budget_trims.id and 
			budget_trim_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
			prod_gmt_sewings.sew_qc_date<='".$date_to."'
			group by 
			wstudy_line_setups.id
			)  trims"), "trims.id", "=", "wstudy_line_setups.id")

		->leftJoin(\DB::raw("(select 
			wstudy_line_setups.id,
			sum(budget_fabric_prod_cons.amount) as amount
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id

			join styles on styles.id = jobs.style_id
			join budgets on budgets.style_id=styles.id
			join budget_fabric_prods on budget_fabric_prods.budget_id=budgets.id
			join budget_fabric_prod_cons on budget_fabric_prod_cons.budget_fabric_prod_id=budget_fabric_prods.id and 
			budget_fabric_prod_cons.sales_order_id=sales_orders.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
			prod_gmt_sewings.sew_qc_date<='".$date_to."'
			group by 
			wstudy_line_setups.id
			)  fabricProd"), "fabricProd.id", "=", "wstudy_line_setups.id")

		->leftJoin(\DB::raw("(select 
			wstudy_line_setups.id,
			sum(budget_emb_cons.amount) as amount
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join styles on styles.id = jobs.style_id
			join budgets on budgets.style_id=styles.id
			join budget_embs on budget_embs.budget_id=budgets.id
			join budget_emb_cons on budget_emb_cons.budget_emb_id=budget_embs.id and 
			budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
			prod_gmt_sewings.sew_qc_date<='".$date_to."'
			group by 
			wstudy_line_setups.id
			)  embs"), "embs.id", "=", "wstudy_line_setups.id")

		

		->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			wstudy_line_setups.id,
			sales_order_gmt_color_sizes.qty,
			sales_order_gmt_color_sizes.qty*(budget_others.amount/12) as amount
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			
			join budgets on budgets.style_id=style_gmts.style_id
			join budget_others on budget_others.budget_id=budgets.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
				prod_gmt_sewings.sew_qc_date<='".$date_to."'
			group by 
			wstudy_line_setups.id,
			budget_others.id,
			sales_order_gmt_color_sizes.qty,
			sales_order_gmt_color_sizes.id,
			budget_others.amount
			) m group by m.id) others"), "others.id", "=", "wstudy_line_setups.id")

			->leftJoin(\DB::raw("(SELECT m.id,sum(m.amount) as commer_rate from (SELECT 
			wstudy_line_setups.id,
			sum(budget_commercials.rate) as amount
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id

			join budgets on budgets.style_id=jobs.style_id
			join budget_commercials on budget_commercials.budget_id=budgets.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
				prod_gmt_sewings.sew_qc_date<='".$date_to."'
			group by 
			wstudy_line_setups.id,
			budget_commercials.id
			) m group by m.id) commer"), "commer.id", "=", "wstudy_line_setups.id")

			->leftJoin(\DB::raw("(SELECT m.id,sum(m.amount) as commi_rate from (SELECT 
			wstudy_line_setups.id,
			sum(budget_commissions.rate) as amount
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id

			join budgets on budgets.style_id=jobs.style_id
			join budget_commissions on budget_commissions.budget_id=budgets.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
				prod_gmt_sewings.sew_qc_date<='".$date_to."'
			group by 
			wstudy_line_setups.id,
			budget_commissions.id
			) m group by m.id) commi"), "commi.id", "=", "wstudy_line_setups.id")

			->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			wstudy_line_setups.id,
			sales_order_gmt_color_sizes.qty,
			sales_order_gmt_color_sizes.qty * budget_cms.cm_per_pcs as amount
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			wstudy_line_setup_dtls.from_date>='".$date_to."' and 
				wstudy_line_setup_dtls.to_date<='".$date_to."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join budgets on budgets.style_id=style_gmts.style_id
			join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
				prod_gmt_sewings.sew_qc_date<='".$date_to."'
			
			) m group by m.id) CM"), "CM.id", "=", "wstudy_line_setups.id")
			->leftJoin(\DB::raw("(select keycontrols.company_id,sum(keycontrol_parameters.value) as amount from keycontrols 
		join keycontrol_parameters on keycontrols.id=keycontrol_parameters.keycontrol_id 
		where keycontrol_parameters.parameter_id=4 
		and  '".$date_to."' between keycontrol_parameters.from_date and keycontrol_parameters.to_date
		group by keycontrols.company_id) cpm"), "cpm.company_id", "=", "wstudy_line_setups.company_id")
		
		->when($date_to, function ($q) use($date_to){
		return $q->where('wstudy_line_setup_dtls.from_date', '>=',$date_to);
		})
		->when($date_to, function ($q) use($date_to){
		return $q->where('wstudy_line_setup_dtls.to_date', '<=',$date_to);
		})
		->selectRaw(
	   'wstudy_line_setups.id,
	    wstudy_line_setups.company_id,
	    wstudy_line_setup_dtls.line_chief,
	    wstudy_line_setup_dtls.operator,
	    wstudy_line_setup_dtls.helper,
	    wstudy_line_setup_dtls.working_hour,
	    wstudy_line_setup_dtls.overtime_hour,
	    sew.qty as sew_qty,
	    sew.amount as prodused_fob,
	    sew.smv as produced_mint,
	    sewCM.amount as cm_earned_usd,
	    trims.amount as trim_amount,
	    yarns.amount as yarn_amount,
	    fabricProd.amount as fabric_prod_amount,
	    embs.amount as emb_amount,
	    others.amount as other_amount,
	    commer.commer_rate as commer_rate,
	    commi.commi_rate as commer_rate,
	    CM.amount,
	    cpm.amount as cpm_amount
	    '
		)
		->where([['wstudy_line_setups.company_id','=',$company_id]])
		->groupBy([
			'wstudy_line_setups.id',
			'wstudy_line_setups.company_id',
			'wstudy_line_setup_dtls.line_chief',
			'wstudy_line_setup_dtls.operator' ,
			'wstudy_line_setup_dtls.helper',
			'wstudy_line_setup_dtls.working_hour',
			'wstudy_line_setup_dtls.overtime_hour',
			'sew.qty',
			'sew.amount',
			'sew.smv',
			'sewCM.amount',
			'trims.amount',
			'yarns.amount',
			'fabricProd.amount',
			'embs.amount',
			'others.amount',
			'commer.commer_rate',
			'commi.commi_rate',
			'CM.amount',
			'cpm.amount'
		])
		->orderBy('wstudy_line_setups.id')
		->get()
		->map(function($prodgmtsewing) use($lineCode,$lineFloor,$lineCheif,$capacityQty,$capacityAmount, $buyerName,$orderNo,$orderQty,$orderAmount,$itemAccounts,$itemSmv){
			$order_qty=0;
			if(isset($orderQty[$prodgmtsewing->id])){
			   $order_qty=array_sum($orderQty[$prodgmtsewing->id]);	
			}
			$order_amount=0;
			if (isset($orderAmount[$prodgmtsewing->id])) {
				$order_amount=array_sum($orderAmount[$prodgmtsewing->id]);

			}
			$prodgmtsewing->buyer_code=0;
			if(isset($buyerName[$prodgmtsewing->id]))
			{
			$prodgmtsewing->buyer_code=implode(',',$buyerName[$prodgmtsewing->id]);
			}
			$prodgmtsewing->sale_order_no='';
			if(isset($orderNo[$prodgmtsewing->id]))
			{
				$prodgmtsewing->sale_order_no=implode(',',$orderNo[$prodgmtsewing->id]);

			}
			$prodgmtsewing->line='';
			if(isset($lineCode[$prodgmtsewing->id])){
			$prodgmtsewing->line=implode(',',$lineCode[$prodgmtsewing->id]);
			}
			$prodgmtsewing->floor='';
			if(isset($lineFloor[$prodgmtsewing->id])){
			$prodgmtsewing->floor=implode(',',$lineFloor[$prodgmtsewing->id]);	
			}
			
			$prodgmtsewing->item_description='';

			if(isset($itemAccounts[$prodgmtsewing->id]))
			{
			$prodgmtsewing->item_description=implode(',',$itemAccounts[$prodgmtsewing->id]);
			}

			$prodgmtsewing->smv=0;
			if (isset($itemSmv[$prodgmtsewing->id])) {
				$prodgmtsewing->smv=implode(' / ',$itemSmv[$prodgmtsewing->id]);
			}
			$prodgmtsewing->apm=$prodgmtsewing->line_chief;
			$prodgmtsewing->manpower=$prodgmtsewing->operator+$prodgmtsewing->helper;
			$capacity_qty=0;
			if(isset($capacityQty[$prodgmtsewing->id])){
				$capacity_qty=array_sum($capacityQty[$prodgmtsewing->id]);
			}
			
			$capacity_dev=$prodgmtsewing->sew_qty-$capacity_qty;

			$prodgmtsewing->wh=$prodgmtsewing->working_hour;
			$prodgmtsewing->ot=$prodgmtsewing->overtime_hour;
			$wh=$prodgmtsewing->working_hour+$prodgmtsewing->overtime_hour;

			$used_mint=$prodgmtsewing->manpower*$wh*60;
			$prodgmtsewing->smv_used=0;
			$prodgmtsewing->avg_smv_pcs=0;
			if($prodgmtsewing->sew_qty){
				$prodgmtsewing->smv_used=$used_mint/$prodgmtsewing->sew_qty;
				$prodgmtsewing->avg_smv_pcs=$prodgmtsewing->produced_mint/$prodgmtsewing->sew_qty;
			}
			$prodgmtsewing->dev_avg_smv_pcs=$prodgmtsewing->avg_smv_pcs-$prodgmtsewing->smv_used;

			$prodgmtsewing->cm_pcs=$prodgmtsewing->avg_smv_pcs*$prodgmtsewing->cpm_amount;
			$prodgmtsewing->cm_used_pcs=$prodgmtsewing->smv_used*$prodgmtsewing->cpm_amount;
			$prodgmtsewing->dev_cm_pcs=$prodgmtsewing->cm_pcs-$prodgmtsewing->cm_used_pcs;

			$prodgmtsewing->smv_used=number_format($prodgmtsewing->smv_used,2);
			$prodgmtsewing->avg_smv_pcs=number_format($prodgmtsewing->avg_smv_pcs,2);
			$prodgmtsewing->dev_avg_smv_pcs=number_format($prodgmtsewing->dev_avg_smv_pcs,2);

			$prodgmtsewing->cm_pcs=number_format($prodgmtsewing->cm_pcs,2);
			$prodgmtsewing->cm_used_pcs=number_format($prodgmtsewing->cm_used_pcs,2);
			$prodgmtsewing->dev_cm_pcs=number_format($prodgmtsewing->dev_cm_pcs,2);

			$prodgmtsewing->used_mint=number_format($used_mint,2);
			$prodgmtsewing->effi_per_value=0;
			$prodgmtsewing->effi_per=0;
			if($used_mint)
			{
				$prodgmtsewing->effi_per_value=$prodgmtsewing->produced_mint/$used_mint*100;
				$prodgmtsewing->effi_per=number_format($prodgmtsewing->produced_mint/$used_mint*100,2)." %";
			}



			$commmercial=$prodgmtsewing->yarn_amount+$prodgmtsewing->trim_amount+$prodgmtsewing->fabric_prod_amount+$prodgmtsewing->emb_amount;
			$commer_amount=number_format(($prodgmtsewing->commer_rate/100)*$commmercial,'2','.','');
			$commi_amount=number_format(($prodgmtsewing->commi_rate/100)*$prodgmtsewing->amount,'2','.','');
			$total_cost=$commmercial+$commer_amount+$commi_amount+$prodgmtsewing->other_amount;
			$cm=$order_amount-$total_cost;
			$prodgmtsewing->cm_earned_usd=number_format($cm,2);
			$prodgmtsewing->cm_earned_tk=number_format($cm,2);
			$cmdzn=0;
			if($order_qty){
				$cmdzn=$cm/$order_qty*12;
			}
			$cm_earned_usd=$cmdzn/12*$prodgmtsewing->sew_qty;
			$prodgmtsewing->cm_earned_usd=number_format($cm_earned_usd,2);
			$prodgmtsewing->cm_earned_tk=number_format($cm_earned_usd,2);
			$prodgmtsewing->cm=number_format($cmdzn,2);
			$prodgmtsewing->qty=number_format($order_qty,0);
			$prodgmtsewing->amount=number_format($order_amount,0);
			$prodgmtsewing->capacity_qty=number_format($capacity_qty,0);
			$prodgmtsewing->capacity_dev=number_format($capacity_dev,0);
			$prodgmtsewing->capacity_ach=0;
			if($capacity_qty)
			{
				$prodgmtsewing->capacity_ach=number_format(($prodgmtsewing->sew_qty/$capacity_qty)*100,2)." %";
			}
			$prodgmtsewing->sew_qty=number_format($prodgmtsewing->sew_qty,0);
			$prodgmtsewing->prodused_fob_tk=number_format($prodgmtsewing->prodused_fob,2);
			$prodgmtsewing->prodused_fob=number_format($prodgmtsewing->prodused_fob,2);
			$prodgmtsewing->produced_mint=number_format($prodgmtsewing->produced_mint,2);
			return $prodgmtsewing;
		})->sortByDesc('effi_per_value')->values()
		;
		echo json_encode($prodgmtsewing);
    }

    public function getbep()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=request('company_id',0);
		$earnings=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) use($date_to) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->join('acc_chart_sub_groups', function($join) use($date_to) {
			$join->on('acc_chart_sub_groups.id', '=', 'acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->where([['acc_chart_sub_groups.acc_chart_group_id','=',16]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($earnings){
			$earnings->per_day=$earnings->amount/$this->no_of_days;
			return $earnings;
		});

		$fexpense=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',2]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($fexpense){
			$fexpense->per_day=$fexpense->amount/$this->no_of_days;
			return $fexpense;
		});

		$vexpense=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',1]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($vexpense){
			$vexpense->per_day=$vexpense->amount/$this->no_of_days;
			$vexpense->amount_usd=$vexpense->amount/$this->exch_rate;
			$vexpense->per_day_usd=$vexpense->amount_usd/$this->no_of_days;
			return $vexpense;

		});
		return Template::loadView('Report.CapacityAchivmentBepDetails', ['fexpense'=>$fexpense,'vexpense'=>$vexpense,'earnings'=>$earnings,'exch_rate'=>$this->exch_rate]);
    }

    public function getcm()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=request('company_id',0);
		$earnings=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) use($date_to) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->join('acc_chart_sub_groups', function($join) use($date_to) {
			$join->on('acc_chart_sub_groups.id', '=', 'acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})
		->where([['acc_chart_sub_groups.acc_chart_group_id','=',16]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($earnings){
			$earnings->per_day=$earnings->amount/$this->no_of_days;
			return $earnings;
		});

		$fexpense=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',2]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($fexpense){
			$fexpense->per_day=$fexpense->amount/$this->no_of_days;
			return $fexpense;
		});

		$vexpense=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})

		->where([['acc_bep_entries.expense_type_id','=',1]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($vexpense){
			$vexpense->per_day=$vexpense->amount/$this->no_of_days;
			return $vexpense;
		});

		$results = \DB::select("
			select 
			employee_attendences.company_id,
			sum(employee_attendences.operator) as operator,
			sum(employee_attendences.helper) as helper,
			sum(employee_attendences.prod_staff) as prod_staff,
			sum(employee_attendences.supporting_staff) as supporting_staff,
			sum(employee_attendences.operator_salary) as operator_salary,
			sum(employee_attendences.helper_salary) as helper_salary,
			sum(employee_attendences.prod_stuff_salary) as prod_stuff_salary,
			sum(employee_attendences.operator_ot) as operator_ot,
			sum(employee_attendences.helper_ot) as helper_ot,
			sum(employee_attendences.daily_prod_bill) as daily_prod_bill
			FROM employee_attendences
			where employee_attendences.attendence_date>='".$date_to."' and 
				employee_attendences.attendence_date<='".$date_to."' and employee_attendences.company_id=?
			group by employee_attendences.company_id
			", [request('company_id',0)]);
		$attendences=collect($results)
		->map(function ($attendences){
			$attendences->amount=($attendences->operator_ot+$attendences->helper_ot)*$this->no_of_days;
			$attendences->per_day=$attendences->operator_ot+$attendences->helper_ot;
			return $attendences;

		})
		->first();

		return Template::loadView('Report.CapacityAchivmentCmDetails', ['fexpense'=>$fexpense,'vexpense'=>$vexpense,'earnings'=>$earnings,'attendences'=>$attendences,'exch_rate'=>$this->exch_rate]);
		//echo json_encode($fexpense);

    }

    public function getCarton(){
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$yesterDay=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $str2) ) ));

    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.amount as order_amount,
			requiredCarton.required_carton,
			carton.qty as no_of_carton,
			cartonyesterday.qty no_of_carton_yester_day,

			sum(style_pkg_ratios.qty) as finishing_qty,
			finishingYesterDay.qty as finishingyesterday_qty,
			budgetTrim.trim_amount,
		budgetKniting.kniting_amount,
		budgetYarnDyeing.yarn_dying_amount,
		budgetWeaving.weaving_amount,
		budgetDyeing.dying_amount,
		budgetDyeing.overhead_amount as dyeing_overhead_amount,
		budgetAop.aop_amount,
		budgetAop.overhead_amount as aop_overhead_amount,
		burnOut.burn_out_amount,
		budgetFabFinishing.finishing_amount,
		budgetFabWashing.washing_amount,
		budgetYarn.yarn_amount,
		budgetPrinting.printing_amount,
		budgetPrinting.overhead_amount as print_overhead_amount,
		budgetEmb.emb_amount,
		budgetSpEmb.spemb_amount,
		budgetGmtDyeing.gmt_dyeing_amount,
		budgetGmtWashing.gmt_washing_amount,
		budgetCourier.courier_rate,
		budgetlab.lab_rate,
		budgetInsp.insp_rate,
		budgetOpa.opa_rate,
		budgetDep.dep_rate,
		budgetCoc.coc_rate,
		budgetIct.ict_rate,
		budgetFreight.freight_rate,
		budgetCm.cm_rate,
		budgetCommer.commer_rate,
		budgetCommi.commi_rate,
		mkt_cost_yarns.amount as mkt_cost_yarn_amount,
		mkt_cost_trims.amount as mkt_cost_trim_amount,
		mkt_cost_fabric_prods.amount as mkt_cost_fabric_prod_amount,
		mkt_cost_embs.amount as mkt_cost_emb_amount,
		mkt_costs_other.amount as mkt_costs_other_amount,
		mkt_cost_cms.amount as mkt_cost_cm_amount,
		mkt_cost_commercials.amount as mkt_cost_commercial_amount,
		mkt_cost_commissions.amount as mkt_cost_commission_amount
    		')
    	->join('sales_order_countries', function($join) use($date_to) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_carton_details', function($join) use($date_to) {
			$join->on('prod_gmt_carton_details.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_carton_entries', function($join) use($date_to) {
			$join->on('prod_gmt_carton_entries.id', '=', 'prod_gmt_carton_details.prod_gmt_carton_entry_id');
			$join->where('prod_gmt_carton_entries.carton_date', '>=',$date_to);
			$join->where('prod_gmt_carton_entries.carton_date', '<=',$date_to);
		})
		->join('style_pkgs', function($join) use($date_to) {
			$join->on('style_pkgs.id', '=', 'prod_gmt_carton_details.style_pkg_id');
		})
		->join('style_pkg_ratios', function($join) use($date_to) {
			$join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
		})
		->leftJoin('companies', function($join) use($date_to) {
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
        ->leftJoin('companies as bcompanies', function($join) use($date_to) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
		})
		->leftJoin(\DB::raw("(SELECT 
		sales_orders.id,orders.qty,orders.rate,orders.amount,orders.plan_cut_qty
		FROM prod_gmt_carton_entries
		join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join(SELECT 
		sales_orders.id,
		sum(sales_order_gmt_color_sizes.qty) as qty, 
		avg(sales_order_gmt_color_sizes.rate) as rate,
		sum(sales_order_gmt_color_sizes.amount) as amount, 
		sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
		FROM sales_order_countries
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) orders on orders.id=sales_orders.id
		where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
		prod_gmt_carton_entries.carton_date<='".$date_to."'
		group by sales_orders.id,orders.qty,orders.rate,orders.amount,orders.plan_cut_qty
		) saleorders"), "saleorders.id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,orders.no_of_carton as required_carton
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join(SELECT 
			sales_orders.id,

			sum(sales_order_countries.no_of_carton) as no_of_carton
			FROM sales_order_countries

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) orders on orders.id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,orders.no_of_carton
			) requiredCarton"), "requiredCarton.id", "=", "sales_orders.id")
       
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id) carton"), "carton.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where  
				prod_gmt_carton_entries.carton_date<='".$yesterDay."'
			group by sales_orders.id) cartonyesterday"), "cartonyesterday.sale_order_id", "=", "sales_orders.id")

		/*->leftJoin(\DB::raw("(select m.produced_company_id as company_id,m.sale_order_id,sum(m.qty) as qty, sum(m.amount) as amount from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			carton.qty as no_of_carton,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id,sales_orders.produced_company_id,carton.qty,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) finishing"), "finishing.sale_order_id", "=", "sales_orders.id")*/

		->leftJoin(\DB::raw("(select m.produced_company_id as company_id,m.sale_order_id,sum(m.qty) as qty, sum(m.amount) as amount from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			carton.qty as no_of_carton,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where 
			prod_gmt_carton_entries.carton_date<='".$yesterDay."'
			group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where  
			prod_gmt_carton_entries.carton_date<='".$yesterDay."'
			
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where  
			prod_gmt_carton_entries.carton_date<='".$yesterDay."'
			
			group by sales_orders.id,sales_orders.produced_company_id,carton.qty,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) finishingYesterDay"), "finishingYesterDay.sale_order_id", "=", "sales_orders.id")

			
			


		->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.sale_order_id,sum(budget_trim_cons.amount) as trim_amount FROM budget_trim_cons  join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id   group by sales_order_gmt_color_sizes.sale_order_id) budgetTrim"), "budgetTrim.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as kniting_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =10
		group by sales_orders.id,production_processes.production_area_id) budgetKniting"), "budgetKniting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as yarn_dying_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =5
		group by sales_orders.id,production_processes.production_area_id) budgetYarnDyeing"), "budgetYarnDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as weaving_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =15
		group by sales_orders.id,production_processes.production_area_id) budgetWeaving"), "budgetWeaving.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as dying_amount,sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =20
		group by sales_orders.id,production_processes.production_area_id) budgetDyeing"), "budgetDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as aop_amount,sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =25
		group by sales_orders.id,production_processes.production_area_id) budgetAop"), "budgetAop.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as burn_out_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =28
		group by sales_orders.id,production_processes.production_area_id) burnOut"), "burnOut.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as finishing_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =30
		group by sales_orders.id,production_processes.production_area_id) budgetFabFinishing"), "budgetFabFinishing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as washing_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =35
		group by sales_orders.id,production_processes.production_area_id) budgetFabWashing"), "budgetFabWashing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select m.id as sale_order_id,sum(m.yarn_amount) as yarn_amount  from (select budget_yarns.id as budget_yarn_id ,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,sum(budget_fabric_cons.grey_fab) as grey_fab,sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount,sales_orders.id as id  from budget_yarns join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id group by budget_yarns.id,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,sales_orders.id,sales_orders.sale_order_no) m group by m.id) budgetYarn'), "budgetYarn.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as printing_amount ,sum(budget_emb_cons.overhead_amount) as overhead_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =45
		group by sales_order_gmt_color_sizes.sale_order_id) budgetPrinting'), "budgetPrinting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as emb_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =50
		group by sales_order_gmt_color_sizes.sale_order_id) budgetEmb'), "budgetEmb.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as spemb_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =51
		group by sales_order_gmt_color_sizes.sale_order_id) budgetSpEmb'), "budgetSpEmb.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as gmt_dyeing_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =58
		group by sales_order_gmt_color_sizes.sale_order_id) budgetGmtDyeing'), "budgetGmtDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as gmt_washing_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =60
		group by sales_order_gmt_color_sizes.sale_order_id) budgetGmtWashing'), "budgetGmtWashing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as courier_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =1 group by budgets.style_id) budgetCourier'), "budgetCourier.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as lab_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =5 group by budgets.style_id) budgetLab'), "budgetLab.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as insp_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =10 group by budgets.style_id) budgetInsp'), "budgetInsp.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as freight_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =15 group by budgets.style_id) budgetFreight'), "budgetFreight.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as opa_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =20 group by budgets.style_id) budgetOpa'), "budgetOpa.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as dep_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =25 group by budgets.style_id) budgetDep'), "budgetDep.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as coc_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =30 group by budgets.style_id) budgetCoc'), "budgetCoc.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as ict_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =35 group by budgets.style_id) budgetIct'), "budgetIct.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, avg(budget_cms.cm_per_pcs)  as cm_rate from budget_cms left join budgets on budgets.id = budget_cms.budget_id   group by budgets.style_id) budgetCm'), "budgetCm.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_commercials.rate)  as commer_rate from budget_commercials left join budgets on budgets.id = budget_commercials.budget_id   group by budgets.style_id) budgetCommer'), "budgetCommer.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_commissions.rate)  as commi_rate from budget_commissions left join budgets on budgets.id = budget_commissions.budget_id   group by budgets.style_id) budgetCommi'), "budgetCommi.style_id", "=", "styles.id")



		
			->leftJoin(\DB::raw("(
			select m.sale_order_id,sum(m.amount) as amount from (
			select
			sales_orders.id as sale_order_id, 
			sales_orders.produced_company_id as company_id,
			mkt_costs.style_id,
			(mktyarns.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
			from prod_gmt_carton_entries
			join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join mkt_costs on mkt_costs.style_id = styles.id
			join(
			select mkt_costs.style_id, 
			sum(mkt_cost_yarns.amount)  as amount 
			from mkt_cost_yarns
			join mkt_costs on mkt_costs.id = mkt_cost_yarns.mkt_cost_id
			group by mkt_costs.style_id
			) mktyarns on mktyarns.style_id=mkt_costs.style_id
			join(
			select sales_orders.id as sale_order_id, 
			sum(sales_order_gmt_color_sizes.qty)  as qty 
			from sales_order_gmt_color_sizes
			join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) slaeprders on slaeprders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,
			sales_orders.produced_company_id,
			mkt_costs.style_id,
			mkt_costs.costing_unit_id,
			mktyarns.amount,
			slaeprders.qty
			) m group by m.sale_order_id
			) mkt_cost_yarns"), "mkt_cost_yarns.sale_order_id", "=", "sales_orders.id")


		
		->leftJoin(\DB::raw("(
		select m.sale_order_id,sum(m.amount) as amount from (
		select
		sales_orders.id as sale_order_id, 
		sales_orders.produced_company_id as company_id,
		mkt_costs.style_id,
		(mkttrims.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
		from prod_gmt_carton_entries
		join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join styles on styles.id = jobs.style_id
		join mkt_costs on mkt_costs.style_id = styles.id
		join(
		select mkt_costs.style_id, 
		sum(mkt_cost_trims.amount)  as amount 
		from mkt_cost_trims
		join mkt_costs on mkt_costs.id = mkt_cost_trims.mkt_cost_id
		group by mkt_costs.style_id
		) mkttrims on mkttrims.style_id=mkt_costs.style_id
		join(
		select sales_orders.id as sale_order_id, 
		sum(sales_order_gmt_color_sizes.qty)  as qty 
		from sales_order_gmt_color_sizes
		join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) slaeprders on slaeprders.sale_order_id=sales_orders.id
		where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
		prod_gmt_carton_entries.carton_date<='".$date_to."'
		group by sales_orders.id,
		sales_orders.produced_company_id,
		mkt_costs.style_id,
		mkt_costs.costing_unit_id,
		mkttrims.amount,
		slaeprders.qty
		) m group by m.sale_order_id
		) mkt_cost_trims"), "mkt_cost_trims.sale_order_id", "=", "sales_orders.id")
		
		->leftJoin(\DB::raw("(
		select m.sale_order_id,sum(m.amount) as amount from (
		select
		sales_orders.id as sale_order_id, 
		sales_orders.produced_company_id as company_id,
		mkt_costs.style_id,
		(mktprods.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
		from prod_gmt_carton_entries
		join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join styles on styles.id = jobs.style_id
		join mkt_costs on mkt_costs.style_id = styles.id
		join(
		select mkt_costs.style_id, 
		sum(mkt_cost_fabric_prods.amount)  as amount 
		from mkt_cost_fabric_prods
		join mkt_costs on mkt_costs.id = mkt_cost_fabric_prods.mkt_cost_id
		group by mkt_costs.style_id
		) mktprods on mktprods.style_id=mkt_costs.style_id
		join(
		select sales_orders.id as sale_order_id, 
		sum(sales_order_gmt_color_sizes.qty)  as qty 
		from sales_order_gmt_color_sizes
		join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) slaeprders on slaeprders.sale_order_id=sales_orders.id
		where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
		prod_gmt_carton_entries.carton_date<='".$date_to."'
		group by sales_orders.id,
		sales_orders.produced_company_id,
		mkt_costs.style_id,
		mkt_costs.costing_unit_id,
		mktprods.amount,
		slaeprders.qty
		) m group by m.sale_order_id
		) mkt_cost_fabric_prods"), "mkt_cost_fabric_prods.sale_order_id", "=", "sales_orders.id")

		
			->leftJoin(\DB::raw("(
			select m.sale_order_id,sum(m.amount) as amount from (
			select
			sales_orders.id as sale_order_id, 
			sales_orders.produced_company_id as company_id,
			mkt_costs.style_id,
			(mktembs.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
			from prod_gmt_carton_entries
			join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join mkt_costs on mkt_costs.style_id = styles.id
			join(
			select mkt_costs.style_id, 
			sum(mkt_cost_embs.amount)  as amount 
			from mkt_cost_embs
			join mkt_costs on mkt_costs.id = mkt_cost_embs.mkt_cost_id
			group by mkt_costs.style_id
			) mktembs on mktembs.style_id=mkt_costs.style_id
			join(
			select sales_orders.id as sale_order_id, 
			sum(sales_order_gmt_color_sizes.qty)  as qty 
			from sales_order_gmt_color_sizes
			join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) slaeprders on slaeprders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,
			sales_orders.produced_company_id,
			mkt_costs.style_id,
			mkt_costs.costing_unit_id,
			mktembs.amount,
			slaeprders.qty
			) m group by m.sale_order_id) mkt_cost_embs"), "mkt_cost_embs.sale_order_id", "=", "sales_orders.id")

		
			->leftJoin(\DB::raw("(
			select m.sale_order_id,sum(m.amount) as amount from (
			select
			sales_orders.id as sale_order_id, 
			sales_orders.produced_company_id as company_id,
			mkt_costs.style_id,
			(mktothers.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
			from prod_gmt_carton_entries
			join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join mkt_costs on mkt_costs.style_id = styles.id
			join(
			select mkt_costs.style_id, 
			sum(mkt_cost_others.amount)  as amount 
			from mkt_cost_others
			join mkt_costs on mkt_costs.id = mkt_cost_others.mkt_cost_id
			group by mkt_costs.style_id
			) mktothers on mktothers.style_id=mkt_costs.style_id
			join(
			select sales_orders.id as sale_order_id, 
			sum(sales_order_gmt_color_sizes.qty)  as qty 
			from sales_order_gmt_color_sizes
			join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) slaeprders on slaeprders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,
			sales_orders.produced_company_id,
			mkt_costs.style_id,
			mkt_costs.costing_unit_id,
			mktothers.amount,
			slaeprders.qty
			) m group by m.sale_order_id) mkt_costs_other"), "mkt_costs_other.sale_order_id", "=", "sales_orders.id")

		
		->leftJoin(\DB::raw("(
		select m.sale_order_id,sum(m.amount) as amount from (
		select
		sales_orders.id as sale_order_id, 
		sales_orders.produced_company_id as company_id,
		mkt_costs.style_id,
		(mktcms.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
		from prod_gmt_carton_entries
		join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join styles on styles.id = jobs.style_id
		join mkt_costs on mkt_costs.style_id = styles.id
		join(
		select mkt_costs.style_id, 
		sum(mkt_cost_cms.amount)  as amount 
		from mkt_cost_cms
		join mkt_costs on mkt_costs.id = mkt_cost_cms.mkt_cost_id
		group by mkt_costs.style_id
		) mktcms on mktcms.style_id=mkt_costs.style_id
		join(
		select sales_orders.id as sale_order_id, 
		sum(sales_order_gmt_color_sizes.qty)  as qty 
		from sales_order_gmt_color_sizes
		join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) slaeprders on slaeprders.sale_order_id=sales_orders.id
		where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
		prod_gmt_carton_entries.carton_date<='".$date_to."'
		group by sales_orders.id,
		sales_orders.produced_company_id,
		mkt_costs.style_id,
		mkt_costs.costing_unit_id,
		mktcms.amount,
		slaeprders.qty
		) m group by m.sale_order_id
		) mkt_cost_cms"), "mkt_cost_cms.sale_order_id", "=", "sales_orders.id")

		
		->leftJoin(\DB::raw("(
		select m.sale_order_id,sum(m.amount) as amount from (
		select
		sales_orders.id as sale_order_id, 
		sales_orders.produced_company_id as company_id,
		mkt_costs.style_id,
		(mktcommers.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
		from prod_gmt_carton_entries
		join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join jobs on jobs.id = sales_orders.job_id
		join styles on styles.id = jobs.style_id
		join mkt_costs on mkt_costs.style_id = styles.id
		join(
		select mkt_costs.style_id, 
		sum(mkt_cost_commercials.amount)  as amount 
		from mkt_cost_commercials
		join mkt_costs on mkt_costs.id = mkt_cost_commercials.mkt_cost_id
		group by mkt_costs.style_id
		) mktcommers on mktcommers.style_id=mkt_costs.style_id
		join(
		select sales_orders.id as sale_order_id, 
		sum(sales_order_gmt_color_sizes.qty)  as qty 
		from sales_order_gmt_color_sizes
		join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) slaeprders on slaeprders.sale_order_id=sales_orders.id
		where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
		prod_gmt_carton_entries.carton_date<='".$date_to."'
		group by sales_orders.id,
		sales_orders.produced_company_id,
		mkt_costs.style_id,
		mkt_costs.costing_unit_id,
		mktcommers.amount,
		slaeprders.qty
		) m group by m.sale_order_id
		) mkt_cost_commercials"), "mkt_cost_commercials.sale_order_id", "=", "sales_orders.id")

		
			->leftJoin(\DB::raw("(
			select m.sale_order_id,sum(m.amount) as amount from (
			select
			sales_orders.id as sale_order_id, 
			sales_orders.produced_company_id as company_id,
			mkt_costs.style_id,
			(mktcommis.amount/mkt_costs.costing_unit_id)*slaeprders.qty as amount
			from prod_gmt_carton_entries
			join prod_gmt_carton_details   on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join mkt_costs on mkt_costs.style_id = styles.id
			join(
			select mkt_costs.style_id, 
			sum(mkt_cost_commissions.amount)  as amount 
			from mkt_cost_commissions
			join mkt_costs on mkt_costs.id = mkt_cost_commissions.mkt_cost_id
			group by mkt_costs.style_id
			) mktcommis on mktcommis.style_id=mkt_costs.style_id
			join(
			select sales_orders.id as sale_order_id, 
			sum(sales_order_gmt_color_sizes.qty)  as qty 
			from sales_order_gmt_color_sizes
			join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) slaeprders on slaeprders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			group by sales_orders.id,
			sales_orders.produced_company_id,
			mkt_costs.style_id,
			mkt_costs.costing_unit_id,
			mktcommis.amount,
			slaeprders.qty
			) m group by m.sale_order_id) mkt_cost_commissions"), "mkt_cost_commissions.sale_order_id", "=", "sales_orders.id")


		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_carton_entries.carton_date', '>=',$date_to);
		})
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_carton_entries.carton_date', '<=',$date_to);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.ship_date',
    		'sales_orders.id',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.amount',
    		'requiredCarton.required_carton',
    		'carton.qty',
    		'cartonyesterday.qty',
    		'finishingYesterDay.qty',
    		'budgetTrim.trim_amount',
			'budgetKniting.kniting_amount',
			'budgetYarnDyeing.yarn_dying_amount',
			'budgetWeaving.weaving_amount',
			'budgetDyeing.dying_amount',
			'budgetDyeing.overhead_amount',
			'budgetAop.aop_amount',
			'budgetAop.overhead_amount',
			'burnOut.burn_out_amount',
			'budgetFabFinishing.finishing_amount',
			'budgetFabWashing.washing_amount',
			'budgetYarn.yarn_amount',
			'budgetPrinting.printing_amount',
			'budgetPrinting.overhead_amount',
			'budgetEmb.emb_amount',
			'budgetSpEmb.spemb_amount',
			'budgetGmtDyeing.gmt_dyeing_amount',
			'budgetGmtWashing.gmt_washing_amount',
			'budgetCourier.courier_rate',
			'budgetlab.lab_rate',
			'budgetInsp.insp_rate',
			'budgetFreight.freight_rate',
			'budgetOpa.opa_rate',
			'budgetDep.dep_rate',
			'budgetCoc.coc_rate',
			'budgetIct.ict_rate',
			'budgetCm.cm_rate',
			'budgetCommer.commer_rate',
			'budgetCommi.commi_rate',
			'mkt_cost_yarns.amount',
			'mkt_cost_trims.amount',
			'mkt_cost_fabric_prods.amount',
			'mkt_cost_embs.amount',
			'mkt_costs_other.amount',
			'mkt_cost_cms.amount',
			'mkt_cost_commercials.amount',
			'mkt_cost_commissions.amount'
		])
		->get()
		->map(function($data){
			$data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			$data->total_carton=$data->no_of_carton+$data->no_of_carton_yester_day;
			$data->yet_to_no_of_carton=$data->required_carton-$data->total_carton;



			$finishing_amount=$data->finishing_qty*$data->order_rate;
			$data->total_finishing=$data->finishing_qty+$data->finishingyesterday_qty;
			$data->yet_to_finishing=$data->order_qty-$data->total_finishing;
			

			

            
			$commmercial=$data->trim_amount+
			$data->kniting_amount+
			$data->yarn_dying_amount+
			$data->weaving_amount+
			$data->dying_amount+$data->dyeing_overhead_amount+$data->finishing_amount+
			$data->aop_amount+$data->aop_overhead_amount+
			$data->burn_out_amount+

			$data->washing_amount+
			$data->yarn_amount+
			$data->printing_amount+$data->print_overhead_amount+
			$data->emb_amount+
			$data->spemb_amount+
			$data->gmt_dyeing_amount+
			$data->gmt_washing_amount;

			$commer_amount=number_format(($data->commer_rate/100)*$commmercial,'2','.','');
			$commi_amount=number_format(($data->commi_rate/100)*$data->order_amount,'2','.','');

			$cm_amount=number_format(($data->cm_rate*$data->order_qty),'2','.','');
			$freight_amount=number_format(($data->freight_rate/12)*$data->order_qty,'2','.','');

			$courier_amount=number_format(($data->courier_rate/12)*$data->order_qty,'2','.','');
			$lab_amount=number_format(($data->lab_rate/12)*$data->order_qty,'2','.','');
			$insp_amount=number_format(($data->insp_rate/12)*$data->order_qty,'2','.','');
			$opa_amount=number_format(($data->opa_rate/12)*$data->order_qty,'2','.','');
			$dep_amount=number_format(($data->dep_rate/12)*$data->order_qty,'2','.','');
			$coc_amount=number_format(($data->coc_rate/12)*$data->order_qty,'2','.','');
			$ict_amount=number_format(($data->ict_rate/12)*$data->order_qty,'2','.','');
			$other_amount=$courier_amount+$lab_amount+$insp_amount+$opa_amount+$dep_amount+ $coc_amount+ $ict_amount;

			$cmMnuf=(($data->order_amount - ($commmercial+$commer_amount+$commi_amount+$freight_amount+$other_amount))/$data->order_qty)*$data->finishing_qty;

			$mkt_cost=($data->mkt_cost_yarn_amount+
            $data->mkt_cost_trim_amount+
            $data->mkt_cost_fabric_prod_amount+
            $data->mkt_cost_emb_amount+
            $data->mkt_costs_other_amount+
            $data->mkt_cost_commercial_amount+
            $data->mkt_cost_commission_amount);
            $mkt_profit=$data->order_amount-$mkt_cost;
            $mktcm=0;
    	    if($data->order_qty){
    	    	$mktcm=(($mkt_profit)/$data->order_qty)*($data->finishing_qty);
    	    }
    	    $data->cm_mkt=($mktcm);



			$data->order_qty=number_format($data->order_qty,0,'.',',');
            $data->order_rate=number_format($data->order_rate,4,'.',',');
            $data->order_amount=number_format($data->order_amount,2,'.',',');

            $data->required_carton=number_format($data->required_carton,0,'.',',');


            $data->no_of_carton=number_format($data->no_of_carton,0,'.',',');
            $data->no_of_carton_yester_day=number_format($data->no_of_carton_yester_day,0,'.',',');
            $data->total_carton=number_format($data->total_carton,0,'.',',');
			$data->yet_to_no_of_carton=number_format($data->yet_to_no_of_carton,0,'.',',');


            $data->finishing_qty=number_format($data->finishing_qty,0,'.',',');
            $data->finishingyesterday_qty=number_format($data->finishingyesterday_qty,0,'.',',');
            $data->total_finishing=number_format($data->total_finishing,0,'.',',');

			$data->yet_to_finishing=number_format($data->yet_to_finishing,0,'.',',');
			$data->finishing_amount=number_format($finishing_amount,2,'.',',');

			$data->cm_mnuf=number_format($cmMnuf,2,'.',',');
			$data->cm_mkt=number_format($data->cm_mkt,2,'.',',');
            return $data;
        });
		echo json_encode($data);
    }

    public function getCartonMonth(){
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
        $start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.amount as order_amount,
			requiredCarton.required_carton,
			carton.qty as no_of_carton,
			cartonyesterday.qty no_of_carton_yester_day,

			sum(style_pkg_ratios.qty) as finishing_qty,
			finishingYesterDay.qty as finishingyesterday_qty,
			budgetTrim.trim_amount,
		budgetKniting.kniting_amount,
		budgetYarnDyeing.yarn_dying_amount,
		budgetWeaving.weaving_amount,
		budgetDyeing.dying_amount,
		budgetDyeing.overhead_amount as dyeing_overhead_amount,
		budgetAop.aop_amount,
		budgetAop.overhead_amount as aop_overhead_amount,
		burnOut.burn_out_amount,
		budgetFabFinishing.finishing_amount,
		budgetFabWashing.washing_amount,
		budgetYarn.yarn_amount,
		budgetPrinting.printing_amount,
		budgetPrinting.overhead_amount as print_overhead_amount,
		budgetEmb.emb_amount,
		budgetSpEmb.spemb_amount,
		budgetGmtDyeing.gmt_dyeing_amount,
		budgetGmtWashing.gmt_washing_amount,
		budgetCourier.courier_rate,
		budgetlab.lab_rate,
		budgetInsp.insp_rate,
		budgetOpa.opa_rate,
		budgetDep.dep_rate,
		budgetCoc.coc_rate,
		budgetIct.ict_rate,
		budgetFreight.freight_rate,
		budgetCm.cm_rate,
		budgetCommer.commer_rate,
		budgetCommi.commi_rate
    		')
    	->join('sales_order_countries', function($join) use($date_to) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_carton_details', function($join) use($date_to) {
			$join->on('prod_gmt_carton_details.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_carton_entries', function($join) use($start_date,$end_date) {
			$join->on('prod_gmt_carton_entries.id', '=', 'prod_gmt_carton_details.prod_gmt_carton_entry_id');
			$join->where('prod_gmt_carton_entries.carton_date', '>=',$start_date);
			$join->where('prod_gmt_carton_entries.carton_date', '<=',$end_date);
		})
		->join('style_pkgs', function($join) use($date_to) {
			$join->on('style_pkgs.id', '=', 'prod_gmt_carton_details.style_pkg_id');
		})
		->join('style_pkg_ratios', function($join) use($date_to) {
			$join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
		})
		->leftJoin('companies', function($join) use($date_to) {
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
        ->leftJoin('companies as bcompanies', function($join) use($date_to) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
		})
		->leftJoin(\DB::raw("(SELECT 
		sales_orders.id,orders.qty,orders.rate,orders.amount,orders.plan_cut_qty
		FROM prod_gmt_carton_entries
		join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
		join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		join(SELECT 
		sales_orders.id,
		sum(sales_order_gmt_color_sizes.qty) as qty, 
		avg(sales_order_gmt_color_sizes.rate) as rate,
		sum(sales_order_gmt_color_sizes.amount) as amount, 
		sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
		FROM sales_order_countries
		join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
		join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
		group by sales_orders.id
		) orders on orders.id=sales_orders.id
		where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
		prod_gmt_carton_entries.carton_date<='".$end_date."'
		group by sales_orders.id,orders.qty,orders.rate,orders.amount,orders.plan_cut_qty
		) saleorders"), "saleorders.id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,orders.no_of_carton as required_carton
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join(SELECT 
			sales_orders.id,

			sum(sales_order_countries.no_of_carton) as no_of_carton
			FROM sales_order_countries

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) orders on orders.id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$end_date."'
			group by sales_orders.id,orders.no_of_carton
			) requiredCarton"), "requiredCarton.id", "=", "sales_orders.id")
       
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
				prod_gmt_carton_entries.carton_date<='".$end_date."'
			group by sales_orders.id) carton"), "carton.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where  
				prod_gmt_carton_entries.carton_date<='".$last_month."'
			group by sales_orders.id) cartonyesterday"), "cartonyesterday.sale_order_id", "=", "sales_orders.id")

		/*->leftJoin(\DB::raw("(select m.produced_company_id as company_id,m.sale_order_id,sum(m.qty) as qty, sum(m.amount) as amount from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			carton.qty as no_of_carton,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$end_date."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$end_date."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$end_date."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id,sales_orders.produced_company_id,carton.qty,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) finishing"), "finishing.sale_order_id", "=", "sales_orders.id")*/

		->leftJoin(\DB::raw("(select m.produced_company_id as company_id,m.sale_order_id,sum(m.qty) as qty, sum(m.amount) as amount from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			carton.qty as no_of_carton,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where 
			prod_gmt_carton_entries.carton_date<='".$last_month."'
			group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where  
			prod_gmt_carton_entries.carton_date<='".$last_month."'
			
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where  
			prod_gmt_carton_entries.carton_date<='".$last_month."'
			
			group by sales_orders.id,sales_orders.produced_company_id,carton.qty,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) finishingYesterDay"), "finishingYesterDay.sale_order_id", "=", "sales_orders.id")

			
			


		->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.sale_order_id,sum(budget_trim_cons.amount) as trim_amount FROM budget_trim_cons  join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id   group by sales_order_gmt_color_sizes.sale_order_id) budgetTrim"), "budgetTrim.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as kniting_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =10
		group by sales_orders.id,production_processes.production_area_id) budgetKniting"), "budgetKniting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as yarn_dying_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =5
		group by sales_orders.id,production_processes.production_area_id) budgetYarnDyeing"), "budgetYarnDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as weaving_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =15
		group by sales_orders.id,production_processes.production_area_id) budgetWeaving"), "budgetWeaving.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as dying_amount,sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =20
		group by sales_orders.id,production_processes.production_area_id) budgetDyeing"), "budgetDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as aop_amount,sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =25
		group by sales_orders.id,production_processes.production_area_id) budgetAop"), "budgetAop.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as burn_out_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =28
		group by sales_orders.id,production_processes.production_area_id) burnOut"), "burnOut.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as finishing_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =30
		group by sales_orders.id,production_processes.production_area_id) budgetFabFinishing"), "budgetFabFinishing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as washing_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =35
		group by sales_orders.id,production_processes.production_area_id) budgetFabWashing"), "budgetFabWashing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select m.id as sale_order_id,sum(m.yarn_amount) as yarn_amount  from (select budget_yarns.id as budget_yarn_id ,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,sum(budget_fabric_cons.grey_fab) as grey_fab,sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount,sales_orders.id as id  from budget_yarns join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id group by budget_yarns.id,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,sales_orders.id,sales_orders.sale_order_no) m group by m.id) budgetYarn'), "budgetYarn.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as printing_amount ,sum(budget_emb_cons.overhead_amount) as overhead_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =45
		group by sales_order_gmt_color_sizes.sale_order_id) budgetPrinting'), "budgetPrinting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as emb_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =50
		group by sales_order_gmt_color_sizes.sale_order_id) budgetEmb'), "budgetEmb.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as spemb_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =51
		group by sales_order_gmt_color_sizes.sale_order_id) budgetSpEmb'), "budgetSpEmb.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as gmt_dyeing_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =58
		group by sales_order_gmt_color_sizes.sale_order_id) budgetGmtDyeing'), "budgetGmtDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as gmt_washing_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =60
		group by sales_order_gmt_color_sizes.sale_order_id) budgetGmtWashing'), "budgetGmtWashing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as courier_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =1 group by budgets.style_id) budgetCourier'), "budgetCourier.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as lab_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =5 group by budgets.style_id) budgetLab'), "budgetLab.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as insp_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =10 group by budgets.style_id) budgetInsp'), "budgetInsp.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as freight_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =15 group by budgets.style_id) budgetFreight'), "budgetFreight.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as opa_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =20 group by budgets.style_id) budgetOpa'), "budgetOpa.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as dep_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =25 group by budgets.style_id) budgetDep'), "budgetDep.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as coc_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =30 group by budgets.style_id) budgetCoc'), "budgetCoc.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as ict_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =35 group by budgets.style_id) budgetIct'), "budgetIct.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, avg(budget_cms.cm_per_pcs)  as cm_rate from budget_cms left join budgets on budgets.id = budget_cms.budget_id   group by budgets.style_id) budgetCm'), "budgetCm.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_commercials.rate)  as commer_rate from budget_commercials left join budgets on budgets.id = budget_commercials.budget_id   group by budgets.style_id) budgetCommer'), "budgetCommer.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_commissions.rate)  as commi_rate from budget_commissions left join budgets on budgets.id = budget_commissions.budget_id   group by budgets.style_id) budgetCommi'), "budgetCommi.style_id", "=", "styles.id")


		->when($start_date, function ($q) use($start_date){
		return $q->where('prod_gmt_carton_entries.carton_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('prod_gmt_carton_entries.carton_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.amount',
    		'requiredCarton.required_carton',
    		'carton.qty',
    		'cartonyesterday.qty',
    		'finishingYesterDay.qty',
    		'budgetTrim.trim_amount',
			'budgetKniting.kniting_amount',
			'budgetYarnDyeing.yarn_dying_amount',
			'budgetWeaving.weaving_amount',
			'budgetDyeing.dying_amount',
			'budgetDyeing.overhead_amount',
			'budgetAop.aop_amount',
			'budgetAop.overhead_amount',
			'burnOut.burn_out_amount',
			'budgetFabFinishing.finishing_amount',
			'budgetFabWashing.washing_amount',
			'budgetYarn.yarn_amount',
			'budgetPrinting.printing_amount',
			'budgetPrinting.overhead_amount',
			'budgetEmb.emb_amount',
			'budgetSpEmb.spemb_amount',
			'budgetGmtDyeing.gmt_dyeing_amount',
			'budgetGmtWashing.gmt_washing_amount',
			'budgetCourier.courier_rate',
			'budgetlab.lab_rate',
			'budgetInsp.insp_rate',
			'budgetFreight.freight_rate',
			'budgetOpa.opa_rate',
			'budgetDep.dep_rate',
			'budgetCoc.coc_rate',
			'budgetIct.ict_rate',
			'budgetCm.cm_rate',
			'budgetCommer.commer_rate',
			'budgetCommi.commi_rate'
		])
		->get()
		->map(function($data){
			$data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			$data->total_carton=$data->no_of_carton+$data->no_of_carton_yester_day;
			$data->yet_to_no_of_carton=$data->required_carton-$data->total_carton;



			$finishing_amount=$data->finishing_qty*$data->order_rate;
			$data->total_finishing=$data->finishing_qty+$data->finishingyesterday_qty;
			$data->yet_to_finishing=$data->order_qty-$data->total_finishing;
			

			

            
			$commmercial=$data->trim_amount+
			$data->kniting_amount+
			$data->yarn_dying_amount+
			$data->weaving_amount+
			$data->dying_amount+$data->dyeing_overhead_amount+$data->finishing_amount+
			$data->aop_amount+$data->aop_overhead_amount+
			$data->burn_out_amount+

			$data->washing_amount+
			$data->yarn_amount+
			$data->printing_amount+$data->print_overhead_amount+
			$data->emb_amount+
			$data->spemb_amount+
			$data->gmt_dyeing_amount+
			$data->gmt_washing_amount;

			$commer_amount=number_format(($data->commer_rate/100)*$commmercial,'2','.','');
			$commi_amount=number_format(($data->commi_rate/100)*$data->order_amount,'2','.','');

			$cm_amount=number_format(($data->cm_rate*$data->order_qty),'2','.','');
			$freight_amount=number_format(($data->freight_rate/12)*$data->order_qty,'2','.','');

			$courier_amount=number_format(($data->courier_rate/12)*$data->order_qty,'2','.','');
			$lab_amount=number_format(($data->lab_rate/12)*$data->order_qty,'2','.','');
			$insp_amount=number_format(($data->insp_rate/12)*$data->order_qty,'2','.','');
			$opa_amount=number_format(($data->opa_rate/12)*$data->order_qty,'2','.','');
			$dep_amount=number_format(($data->dep_rate/12)*$data->order_qty,'2','.','');
			$coc_amount=number_format(($data->coc_rate/12)*$data->order_qty,'2','.','');
			$ict_amount=number_format(($data->ict_rate/12)*$data->order_qty,'2','.','');
			$other_amount=$courier_amount+$lab_amount+$insp_amount+$opa_amount+$dep_amount+ $coc_amount+ $ict_amount;

			$cmMnuf=0;
			if($data->order_qty){
				$cmMnuf=(($data->order_amount - ($commmercial+$commer_amount+$commi_amount+$freight_amount+$other_amount))/$data->order_qty)*$data->finishing_qty;
			}

			$data->order_qty=number_format($data->order_qty,0,'.',',');
            $data->order_rate=number_format($data->order_rate,4,'.',',');
            $data->order_amount=number_format($data->order_amount,2,'.',',');

            $data->required_carton=number_format($data->required_carton,0,'.',',');


            $data->no_of_carton=number_format($data->no_of_carton,0,'.',',');
            $data->no_of_carton_yester_day=number_format($data->no_of_carton_yester_day,0,'.',',');
            $data->total_carton=number_format($data->total_carton,0,'.',',');
			$data->yet_to_no_of_carton=number_format($data->yet_to_no_of_carton,0,'.',',');


            $data->finishing_qty=number_format($data->finishing_qty,0,'.',',');
            $data->finishingyesterday_qty=number_format($data->finishingyesterday_qty,0,'.',',');
            $data->total_finishing=number_format($data->total_finishing,0,'.',',');

			$data->yet_to_finishing=number_format($data->yet_to_finishing,0,'.',',');
			$data->finishing_amount=number_format($finishing_amount,2,'.',',');

			$data->cm_mnuf=number_format($cmMnuf,2,'.',',');
			$data->cm_mkt=number_format(0,2,'.',',');
            return $data;
        });
		echo json_encode($data);
    }

    public function getDataAll()
    {
        $str2=request('date_to',0);
        $yesterDay=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $str2) ) ));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			screenprinttgt.qty as screenprinttgt_qty,
			embrotgt.qty as embrotgt_qty,
			cutting.qty as cutting_qty,
			cuttingYesterDay.qty as cuttingyesterday_qty,
			sew.qty as sew_qty, 
			sewYesterDay.qty as sewyesterday_qty,
			sew.amount as sew_amount, 
			sewYesterDay.amount as sewyesterday_amount,
			finishing.qty as finishing_qty,
			finishingYesterDay.qty as finishingyesterday_qty,
			finishing.amount as gmtfinishing_amount,
			finishingYesterDay.amount as finishingyesterday_amount,
			exfactory.qty as exfactory_qty, 
			exfactoryYesterDay.qty as exfactoryyesterday_qty,
			exfactory.amount as exfactory_amount, 
			exfactoryYesterDay.amount as exfactoryyesterday_amount,
			carton.qty as no_of_carton,
			cartonYesterDay.qty as cartonyesterday_no_of_carton,

			requiredCarton.required_carton,
			budgetTrim.trim_amount,
			budgetKniting.kniting_amount,
			budgetYarnDyeing.yarn_dying_amount,
			budgetWeaving.weaving_amount,
			budgetDyeing.dying_amount,
			budgetDyeing.overhead_amount as dyeing_overhead_amount,
			budgetAop.aop_amount,
			budgetAop.overhead_amount as aop_overhead_amount,
			burnOut.burn_out_amount,
			budgetFabFinishing.finishing_amount,
			budgetFabWashing.washing_amount,
			budgetYarn.yarn_amount,
			budgetPrinting.printing_amount,
			budgetPrinting.overhead_amount as print_overhead_amount,
			budgetEmb.emb_amount,
			budgetSpEmb.spemb_amount,
			budgetGmtDyeing.gmt_dyeing_amount,
			budgetGmtWashing.gmt_washing_amount,
			budgetCourier.courier_rate,
			budgetlab.lab_rate,
			budgetInsp.insp_rate,
			budgetOpa.opa_rate,
			budgetDep.dep_rate,
			budgetCoc.coc_rate,
			budgetIct.ict_rate,
			budgetFreight.freight_rate,
			budgetCm.cm_rate,
			budgetCommer.commer_rate,
			budgetCommi.commi_rate
    		')
    	->join('sales_order_countries', function($join) use($date_to) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		
		->leftJoin('companies', function($join) use($date_to) {
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
        ->leftJoin('companies as bcompanies', function($join) use($date_to) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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

		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.req_cons) as qty,sum(m.amount) as amount,sum(m.overhead_amount) as overhead_amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				sales_orders.id as sale_order_id,
				budget_embs.id as budget_emb_id ,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				FROM sales_orders
				join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join budgets on budgets.job_id=jobs.id
				join budget_embs on budget_embs.budget_id=budgets.id
				join budget_emb_cons on budget_emb_cons.budget_emb_id=budget_embs.id
				and budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id 
				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where  production_processes.production_area_id =45
				and sales_orders.produced_company_id='".$company_id."'
				group by 
				sales_orders.produced_company_id,
				sales_orders.id,
				budget_embs.id,
				budget_emb_cons.id,
				sales_order_gmt_color_sizes.id,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				) m group by m.company_id,m.sale_order_id) screenprinttgt"), "screenprinttgt.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.req_cons) as qty,sum(m.amount) as amount,sum(m.overhead_amount) as overhead_amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				sales_orders.id as sale_order_id,
				budget_embs.id as budget_emb_id ,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				FROM sales_orders
				join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join budgets on budgets.job_id=jobs.id
				join budget_embs on budget_embs.budget_id=budgets.id
				join budget_emb_cons on budget_emb_cons.budget_emb_id=budget_embs.id
				and budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id 
				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where  production_processes.production_area_id =50
				and sales_orders.produced_company_id='".$company_id."'
				group by 
				sales_orders.produced_company_id,
				sales_orders.id,
				budget_embs.id,
				budget_emb_cons.id,
				sales_order_gmt_color_sizes.id,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				) m group by m.company_id,m.sale_order_id) embrotgt"), "embrotgt.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_cuttings.cut_qc_date>='".$date_to."' and 
				prod_gmt_cuttings.cut_qc_date<='".$date_to."'
				and suppliers.company_id='".$company_id."'
			group by
			sales_orders.id, 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) cutting"), "cutting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_cuttings.cut_qc_date<='".$yesterDay."'
				and suppliers.company_id='".$company_id."'
			group by
			sales_orders.id, 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) cuttingYesterDay"), "cuttingYesterDay.sale_order_id", "=", "sales_orders.id")

		

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (
			SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			wstudy_line_setup_dtls.from_date>='".$date_to."' and 
				wstudy_line_setup_dtls.to_date<='".$date_to."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where prod_gmt_sewings.sew_qc_date>='".$date_to."' and 
				prod_gmt_sewings.sew_qc_date<='".$date_to."'
				and sales_orders.produced_company_id='".$company_id."'
			group by 
			sales_orders.produced_company_id,
			sales_orders.id,
			prod_gmt_sewing_qties.id,
			prod_gmt_sewing_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) sew"), "sew.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (
			SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			
				wstudy_line_setup_dtls.to_date<='".$yesterDay."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where  
				prod_gmt_sewings.sew_qc_date<='".$yesterDay."'
				and sales_orders.produced_company_id='".$company_id."'
			group by 
			sales_orders.produced_company_id,
			sales_orders.id,
			prod_gmt_sewing_qties.id,
			prod_gmt_sewing_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) sewYesterDay"), "sewYesterDay.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select m.produced_company_id as company_id,m.sale_order_id,sum(m.qty) as qty, sum(m.amount) as amount from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			carton.qty as no_of_carton,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id,sales_orders.produced_company_id,carton.qty,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) finishing"), "finishing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select m.produced_company_id as company_id,m.sale_order_id,sum(m.qty) as qty, sum(m.amount) as amount from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			carton.qty as no_of_carton,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where 
			prod_gmt_carton_entries.carton_date<='".$yesterDay."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where  
			prod_gmt_carton_entries.carton_date<='".$yesterDay."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where  
			prod_gmt_carton_entries.carton_date<='".$yesterDay."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id,sales_orders.produced_company_id,carton.qty,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) finishingYesterDay"), "finishingYesterDay.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(
			select 
			m.produced_company_id as company_id,
			m.sale_order_id,
			sum(m.qty) as qty, 
			sum(m.amount) as amount 
			FROM (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where prod_gmt_ex_factories.exfactory_date>='".$date_to."' and 
			prod_gmt_ex_factories.exfactory_date<='".$date_to."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_ex_factories.exfactory_date>='".$date_to."' and 
			prod_gmt_ex_factories.exfactory_date<='".$date_to."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) exfactory"), "exfactory.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
			select 
			m.produced_company_id as company_id,
			m.sale_order_id,
			sum(m.qty) as qty, 
			sum(m.amount) as amount 
			FROM (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where
			prod_gmt_ex_factories.exfactory_date<='".$yesterDay."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where 
			prod_gmt_ex_factories.exfactory_date<='".$yesterDay."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) exfactoryYesterDay"), "exfactoryYesterDay.sale_order_id", "=", "sales_orders.id")


       
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
				prod_gmt_carton_entries.carton_date<='".$date_to."'
				and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) carton"), "carton.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where 
				prod_gmt_carton_entries.carton_date<='".$yesterDay."'
				and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) cartonYesterDay"), "cartonYesterDay.sale_order_id", "=", "sales_orders.id")

			

			->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,orders.no_of_carton as required_carton
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join(SELECT 
			sales_orders.id,

			sum(sales_order_countries.no_of_carton) as no_of_carton
			FROM sales_order_countries

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) orders on orders.id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$date_to."' and 
			prod_gmt_carton_entries.carton_date<='".$date_to."'

			group by sales_orders.id,orders.no_of_carton
			) requiredCarton"), "requiredCarton.id", "=", "sales_orders.id")


		->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.sale_order_id,sum(budget_trim_cons.amount) as trim_amount FROM budget_trim_cons  join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id   group by sales_order_gmt_color_sizes.sale_order_id) budgetTrim"), "budgetTrim.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as kniting_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =10
		group by sales_orders.id,production_processes.production_area_id) budgetKniting"), "budgetKniting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as yarn_dying_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =5
		group by sales_orders.id,production_processes.production_area_id) budgetYarnDyeing"), "budgetYarnDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as weaving_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =15
		group by sales_orders.id,production_processes.production_area_id) budgetWeaving"), "budgetWeaving.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as dying_amount,sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =20
		group by sales_orders.id,production_processes.production_area_id) budgetDyeing"), "budgetDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as aop_amount,sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =25
		group by sales_orders.id,production_processes.production_area_id) budgetAop"), "budgetAop.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as burn_out_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =28
		group by sales_orders.id,production_processes.production_area_id) burnOut"), "burnOut.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as finishing_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =30
		group by sales_orders.id,production_processes.production_area_id) budgetFabFinishing"), "budgetFabFinishing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as washing_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =35
		group by sales_orders.id,production_processes.production_area_id) budgetFabWashing"), "budgetFabWashing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select m.id as sale_order_id,sum(m.yarn_amount) as yarn_amount  from (select budget_yarns.id as budget_yarn_id ,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,sum(budget_fabric_cons.grey_fab) as grey_fab,sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount,sales_orders.id as id  from budget_yarns join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id group by budget_yarns.id,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,sales_orders.id,sales_orders.sale_order_no) m group by m.id) budgetYarn'), "budgetYarn.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as printing_amount ,sum(budget_emb_cons.overhead_amount) as overhead_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =45
		group by sales_order_gmt_color_sizes.sale_order_id) budgetPrinting'), "budgetPrinting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as emb_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =50
		group by sales_order_gmt_color_sizes.sale_order_id) budgetEmb'), "budgetEmb.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as spemb_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =51
		group by sales_order_gmt_color_sizes.sale_order_id) budgetSpEmb'), "budgetSpEmb.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as gmt_dyeing_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =58
		group by sales_order_gmt_color_sizes.sale_order_id) budgetGmtDyeing'), "budgetGmtDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as gmt_washing_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =60
		group by sales_order_gmt_color_sizes.sale_order_id) budgetGmtWashing'), "budgetGmtWashing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as courier_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =1 group by budgets.style_id) budgetCourier'), "budgetCourier.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as lab_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =5 group by budgets.style_id) budgetLab'), "budgetLab.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as insp_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =10 group by budgets.style_id) budgetInsp'), "budgetInsp.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as freight_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =15 group by budgets.style_id) budgetFreight'), "budgetFreight.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as opa_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =20 group by budgets.style_id) budgetOpa'), "budgetOpa.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as dep_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =25 group by budgets.style_id) budgetDep'), "budgetDep.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as coc_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =30 group by budgets.style_id) budgetCoc'), "budgetCoc.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as ict_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =35 group by budgets.style_id) budgetIct'), "budgetIct.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, avg(budget_cms.cm_per_pcs)  as cm_rate from budget_cms left join budgets on budgets.id = budget_cms.budget_id   group by budgets.style_id) budgetCm'), "budgetCm.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_commercials.rate)  as commer_rate from budget_commercials left join budgets on budgets.id = budget_commercials.budget_id   group by budgets.style_id) budgetCommer'), "budgetCommer.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_commissions.rate)  as commi_rate from budget_commissions left join budgets on budgets.id = budget_commissions.budget_id   group by budgets.style_id) budgetCommi'), "budgetCommi.style_id", "=", "styles.id")
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('sale_order_no'), function ($q) {
		return $q->where('sales_orders.sale_order_no', 'like', '%'.request('sale_order_no', 0).'%');
		})
		//->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		

    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'screenprinttgt.qty',
    		'embrotgt.qty',
    		'cutting.qty',
    		'cuttingYesterDay.qty',
    		'sew.qty',
    		'sew.amount',
    		'sewYesterDay.qty',
    		'sewYesterDay.amount',
    		'finishing.qty',
    		'finishingYesterDay.qty',
    		'finishing.amount',
    		'finishingYesterDay.amount',
    		'exfactory.qty',
    		'exfactoryYesterDay.qty',
    		'exfactory.amount',
    		'exfactoryYesterDay.amount',
    		'carton.qty',
    		'cartonYesterDay.qty',
    		'requiredCarton.required_carton',
    		'budgetTrim.trim_amount',
			'budgetKniting.kniting_amount',
			'budgetYarnDyeing.yarn_dying_amount',
			'budgetWeaving.weaving_amount',
			'budgetDyeing.dying_amount',
			'budgetDyeing.overhead_amount',
			'budgetAop.aop_amount',
			'budgetAop.overhead_amount',
			'burnOut.burn_out_amount',
			'budgetFabFinishing.finishing_amount',
			'budgetFabWashing.washing_amount',
			'budgetYarn.yarn_amount',
			'budgetPrinting.printing_amount',
			'budgetPrinting.overhead_amount',
			'budgetEmb.emb_amount',
			'budgetSpEmb.spemb_amount',
			'budgetGmtDyeing.gmt_dyeing_amount',
			'budgetGmtWashing.gmt_washing_amount',
			'budgetCourier.courier_rate',
			'budgetlab.lab_rate',
			'budgetInsp.insp_rate',
			'budgetFreight.freight_rate',
			'budgetOpa.opa_rate',
			'budgetDep.dep_rate',
			'budgetCoc.coc_rate',
			'budgetIct.ict_rate',
			'budgetCm.cm_rate',
			'budgetCommer.commer_rate',
			'budgetCommi.commi_rate'
		])
		->orderBy('sales_orders.id')
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_cut=$data->cutting_qty+$data->cuttingyesterday_qty;
			 $data->yet_cut=$data->order_plan_cut_qty-$data->total_cut;


			 $data->total_sew=$data->sew_qty+$data->sewyesterday_qty;
			 $data->yet_sew=$data->order_qty-$data->total_sew;

			 $data->sew_amount=$data->sew_amount;
			 $data->sewyesterday_amount=$data->sewyesterday_amount;

			 $data->total_sew_amount=$data->sew_amount+$data->sewyesterday_amount;
			 $data->yet_sew_amount=$data->order_amount-$data->total_sew_amount;


			 $data->total_finish=$data->finishing_qty+$data->finishingyesterday_qty;
			 $data->yet_finish=$data->order_qty-$data->total_finish;

			 $data->gmtfinishing_amount=$data->gmtfinishing_amount;
			 $data->finishingyesterday_amount=$data->finishingyesterday_amount;

			 $data->total_finish_amount=$data->gmtfinishing_amount+$data->finishingyesterday_amount;
			 $data->yet_finish_amount=$data->order_amount-$data->total_finish_amount;


			 $data->total_exfactory=$data->exfactory_qty+$data->exfactoryyesterday_qty;
			 $data->yet_exfactory=$data->order_qty-$data->total_exfactory;

			 $data->exfactory_amount=$data->exfactory_amount;
			 $data->exfactoryyesterday_amount=$data->exfactoryyesterday_amount;

			 $data->total_exfactory_amount=$data->exfactory_amount+$data->exfactoryyesterday_amount;
			 $data->yet_exfactory_amount=$data->order_amount-$data->total_exfactory_amount;
			 $data->total_no_of_carton=$data->no_of_carton+$data->cartonyesterday_no_of_carton;
			 $data->yet_no_of_carton=$data->required_carton-$data->total_no_of_carton;

			$commmercial=$data->trim_amount+
			$data->kniting_amount+
			$data->yarn_dying_amount+
			$data->weaving_amount+
			$data->dying_amount+$data->dyeing_overhead_amount+$data->finishing_amount+
			$data->aop_amount+$data->aop_overhead_amount+
			$data->burn_out_amount+

			$data->washing_amount+
			$data->yarn_amount+
			$data->printing_amount+$data->print_overhead_amount+
			$data->emb_amount+
			$data->spemb_amount+
			$data->gmt_dyeing_amount+
			$data->gmt_washing_amount;

			$commer_amount=number_format(($data->commer_rate/100)*$commmercial,'2','.','');
			$commi_amount=number_format(($data->commi_rate/100)*$data->order_amount,'2','.','');

			$cm_amount=number_format(($data->cm_rate*$data->order_qty),'2','.','');
			$freight_amount=number_format(($data->freight_rate/12)*$data->order_qty,'2','.','');

			$courier_amount=number_format(($data->courier_rate/12)*$data->order_qty,'2','.','');
			$lab_amount=number_format(($data->lab_rate/12)*$data->order_qty,'2','.','');
			$insp_amount=number_format(($data->insp_rate/12)*$data->order_qty,'2','.','');
			$opa_amount=number_format(($data->opa_rate/12)*$data->order_qty,'2','.','');
			$dep_amount=number_format(($data->dep_rate/12)*$data->order_qty,'2','.','');
			$coc_amount=number_format(($data->coc_rate/12)*$data->order_qty,'2','.','');
			$ict_amount=number_format(($data->ict_rate/12)*$data->order_qty,'2','.','');
			$other_amount=$courier_amount+$lab_amount+$insp_amount+$opa_amount+$dep_amount+ $coc_amount+ $ict_amount;

			$data->required_cm=(($data->order_amount)-($commmercial+$commer_amount+$commi_amount+$freight_amount+$other_amount));
			$data->cmmnuf=0;
			if($data->finishing_qty){
				$data->cmmnuf=($data->required_cm/$data->order_qty)*$data->finishing_qty;
			}
			$data->cmmnuf_yesterday=0;
			if($data->finishing_qty){
				$data->cmmnuf_yesterday=($data->required_cm/$data->order_qty)*$data->finishingyesterday_qty;
			}

           $data->total_cm=$data->cmmnuf+$data->cmmnuf_yesterday;
           $data->yet_cm=$data->required_cm-$data->total_cm;
            return $data;
        })
        ->filter(function($value){
        	if($value->cutting_qty || $value->sew_qty || $value->finishing_qty || $value->exfactory_qty || $value->no_of_carton){
        		return $value;
        	}
        });
        return Template::loadView('Report.CapacityAchivmentCommonPopUp', ['data'=>$data]);
    }

    public function getDataAllForMonth()
    {
        $str2=request('date_to',0);
        $date_to = date('Y-m-d', strtotime($str2));

        $start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
        

    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			screenprinttgt.qty as screenprinttgt_qty,
			embrotgt.qty as embrotgt_qty,
			cutting.qty as cutting_qty,
			cuttingYesterDay.qty as cuttingyesterday_qty,
			sew.qty as sew_qty, 
			sewYesterDay.qty as sewyesterday_qty,
			sew.amount as sew_amount, 
			sewYesterDay.amount as sewyesterday_amount,
			finishing.qty as finishing_qty,
			finishingYesterDay.qty as finishingyesterday_qty,
			finishing.amount as gmtfinishing_amount,
			finishingYesterDay.amount as finishingyesterday_amount,
			exfactory.qty as exfactory_qty, 
			exfactoryYesterDay.qty as exfactoryyesterday_qty,
			exfactory.amount as exfactory_amount, 
			exfactoryYesterDay.amount as exfactoryyesterday_amount,
			carton.qty as no_of_carton,
			cartonYesterDay.qty as cartonyesterday_no_of_carton,

			requiredCarton.required_carton,
			budgetTrim.trim_amount,
			budgetKniting.kniting_amount,
			budgetYarnDyeing.yarn_dying_amount,
			budgetWeaving.weaving_amount,
			budgetDyeing.dying_amount,
			budgetDyeing.overhead_amount as dyeing_overhead_amount,
			budgetAop.aop_amount,
			budgetAop.overhead_amount as aop_overhead_amount,
			burnOut.burn_out_amount,
			budgetFabFinishing.finishing_amount,
			budgetFabWashing.washing_amount,
			budgetYarn.yarn_amount,
			budgetPrinting.printing_amount,
			budgetPrinting.overhead_amount as print_overhead_amount,
			budgetEmb.emb_amount,
			budgetSpEmb.spemb_amount,
			budgetGmtDyeing.gmt_dyeing_amount,
			budgetGmtWashing.gmt_washing_amount,
			budgetCourier.courier_rate,
			budgetlab.lab_rate,
			budgetInsp.insp_rate,
			budgetOpa.opa_rate,
			budgetDep.dep_rate,
			budgetCoc.coc_rate,
			budgetIct.ict_rate,
			budgetFreight.freight_rate,
			budgetCm.cm_rate,
			budgetCommer.commer_rate,
			budgetCommi.commi_rate
    		')
    	->join('sales_order_countries', function($join) use($date_to) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		
		->leftJoin('companies', function($join) use($date_to) {
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
        ->leftJoin('companies as bcompanies', function($join) use($date_to) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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

		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.req_cons) as qty,sum(m.amount) as amount,sum(m.overhead_amount) as overhead_amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				sales_orders.id as sale_order_id,
				budget_embs.id as budget_emb_id ,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				FROM sales_orders
				join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join budgets on budgets.job_id=jobs.id
				join budget_embs on budget_embs.budget_id=budgets.id
				join budget_emb_cons on budget_emb_cons.budget_emb_id=budget_embs.id
				and budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id 
				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where  production_processes.production_area_id =45
				and sales_orders.produced_company_id='".$company_id."'
				group by 
				sales_orders.produced_company_id,
				sales_orders.id,
				budget_embs.id,
				budget_emb_cons.id,
				sales_order_gmt_color_sizes.id,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				) m group by m.company_id,m.sale_order_id) screenprinttgt"), "screenprinttgt.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.req_cons) as qty,sum(m.amount) as amount,sum(m.overhead_amount) as overhead_amount from (
				SELECT      
				sales_orders.produced_company_id as company_id,
				sales_orders.id as sale_order_id,
				budget_embs.id as budget_emb_id ,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				FROM sales_orders
				join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
				join jobs on jobs.id = sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
				join budgets on budgets.job_id=jobs.id
				join budget_embs on budget_embs.budget_id=budgets.id
				join budget_emb_cons on budget_emb_cons.budget_emb_id=budget_embs.id
				and budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id 
				left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
				left join embelishments on embelishments.id=style_embelishments.embelishment_id
				left join production_processes on production_processes.id=embelishments.production_process_id
				where  production_processes.production_area_id =50
				and sales_orders.produced_company_id='".$company_id."'
				group by 
				sales_orders.produced_company_id,
				sales_orders.id,
				budget_embs.id,
				budget_emb_cons.id,
				sales_order_gmt_color_sizes.id,
				budget_emb_cons.req_cons,
				budget_emb_cons.amount,
				budget_emb_cons.overhead_amount
				) m group by m.company_id,m.sale_order_id) embrotgt"), "embrotgt.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_cuttings.cut_qc_date>='".$start_date."' and 
				prod_gmt_cuttings.cut_qc_date<='".$end_date."'
				and suppliers.company_id='".$company_id."'
			group by
			sales_orders.id, 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) cutting"), "cutting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_cuttings.cut_qc_date<='".$last_month."'
				and suppliers.company_id='".$company_id."'
			group by
			sales_orders.id, 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) cuttingYesterDay"), "cuttingYesterDay.sale_order_id", "=", "sales_orders.id")

		

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (
			SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			wstudy_line_setup_dtls.from_date>='".$start_date."' and 
				wstudy_line_setup_dtls.to_date<='".$end_date."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where prod_gmt_sewings.sew_qc_date>='".$start_date."' and 
				prod_gmt_sewings.sew_qc_date<='".$end_date."'
				and sales_orders.produced_company_id='".$company_id."'
			group by 
			sales_orders.produced_company_id,
			sales_orders.id,
			prod_gmt_sewing_qties.id,
			prod_gmt_sewing_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) sew"), "sew.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (
			SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			
				wstudy_line_setup_dtls.to_date<='".$last_month."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where  
				prod_gmt_sewings.sew_qc_date<='".$last_month."'
				and sales_orders.produced_company_id='".$company_id."'
			group by 
			sales_orders.produced_company_id,
			sales_orders.id,
			prod_gmt_sewing_qties.id,
			prod_gmt_sewing_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) sewYesterDay"), "sewYesterDay.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select m.produced_company_id as company_id,m.sale_order_id,sum(m.qty) as qty, sum(m.amount) as amount from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			carton.qty as no_of_carton,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$end_date."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$end_date."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$end_date."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id,sales_orders.produced_company_id,carton.qty,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) finishing"), "finishing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select m.produced_company_id as company_id,m.sale_order_id,sum(m.qty) as qty, sum(m.amount) as amount from (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			carton.qty as no_of_carton,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where 
			prod_gmt_carton_entries.carton_date<='".$last_month."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) carton on carton.sale_order_id=sales_orders.id

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where  
			prod_gmt_carton_entries.carton_date<='".$last_month."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where  
			prod_gmt_carton_entries.carton_date<='".$last_month."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id,sales_orders.produced_company_id,carton.qty,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) finishingYesterDay"), "finishingYesterDay.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(
			select 
			m.produced_company_id as company_id,
			m.sale_order_id,
			sum(m.qty) as qty, 
			sum(m.amount) as amount 
			FROM (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where prod_gmt_ex_factories.exfactory_date>='".$start_date."' and 
			prod_gmt_ex_factories.exfactory_date<='".$end_date."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_ex_factories.exfactory_date>='".$start_date."' and 
			prod_gmt_ex_factories.exfactory_date<='".$end_date."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) exfactory"), "exfactory.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
			select 
			m.produced_company_id as company_id,
			m.sale_order_id,
			sum(m.qty) as qty, 
			sum(m.amount) as amount 
			FROM (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where
			prod_gmt_ex_factories.exfactory_date<='".$last_month."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where 
			prod_gmt_ex_factories.exfactory_date<='".$last_month."'
			and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) exfactoryYesterDay"), "exfactoryYesterDay.sale_order_id", "=", "sales_orders.id")


       
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
				prod_gmt_carton_entries.carton_date<='".$end_date."'
				and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) carton"), "carton.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id as sale_order_id,
			count(prod_gmt_carton_details.qty) as qty 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			where 
				prod_gmt_carton_entries.carton_date<='".$last_month."'
				and sales_orders.produced_company_id='".$company_id."'
			group by sales_orders.id) cartonYesterDay"), "cartonYesterDay.sale_order_id", "=", "sales_orders.id")

			

			->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,orders.no_of_carton as required_carton
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join(SELECT 
			sales_orders.id,

			sum(sales_order_countries.no_of_carton) as no_of_carton
			FROM sales_order_countries

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			group by sales_orders.id
			) orders on orders.id=sales_orders.id
			where prod_gmt_carton_entries.carton_date>='".$start_date."' and 
			prod_gmt_carton_entries.carton_date<='".$end_date."'

			group by sales_orders.id,orders.no_of_carton
			) requiredCarton"), "requiredCarton.id", "=", "sales_orders.id")


		->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.sale_order_id,sum(budget_trim_cons.amount) as trim_amount FROM budget_trim_cons  join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id   group by sales_order_gmt_color_sizes.sale_order_id) budgetTrim"), "budgetTrim.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as kniting_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =10
		group by sales_orders.id,production_processes.production_area_id) budgetKniting"), "budgetKniting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as yarn_dying_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =5
		group by sales_orders.id,production_processes.production_area_id) budgetYarnDyeing"), "budgetYarnDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as weaving_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =15
		group by sales_orders.id,production_processes.production_area_id) budgetWeaving"), "budgetWeaving.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as dying_amount,sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =20
		group by sales_orders.id,production_processes.production_area_id) budgetDyeing"), "budgetDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as aop_amount,sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =25
		group by sales_orders.id,production_processes.production_area_id) budgetAop"), "budgetAop.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as burn_out_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =28
		group by sales_orders.id,production_processes.production_area_id) burnOut"), "burnOut.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as finishing_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =30
		group by sales_orders.id,production_processes.production_area_id) budgetFabFinishing"), "budgetFabFinishing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as washing_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =35
		group by sales_orders.id,production_processes.production_area_id) budgetFabWashing"), "budgetFabWashing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select m.id as sale_order_id,sum(m.yarn_amount) as yarn_amount  from (select budget_yarns.id as budget_yarn_id ,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,sum(budget_fabric_cons.grey_fab) as grey_fab,sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount,sales_orders.id as id  from budget_yarns join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id group by budget_yarns.id,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,sales_orders.id,sales_orders.sale_order_no) m group by m.id) budgetYarn'), "budgetYarn.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as printing_amount ,sum(budget_emb_cons.overhead_amount) as overhead_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =45
		group by sales_order_gmt_color_sizes.sale_order_id) budgetPrinting'), "budgetPrinting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as emb_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =50
		group by sales_order_gmt_color_sizes.sale_order_id) budgetEmb'), "budgetEmb.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as spemb_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =51
		group by sales_order_gmt_color_sizes.sale_order_id) budgetSpEmb'), "budgetSpEmb.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as gmt_dyeing_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =58
		group by sales_order_gmt_color_sizes.sale_order_id) budgetGmtDyeing'), "budgetGmtDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as gmt_washing_amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		where production_processes.production_area_id =60
		group by sales_order_gmt_color_sizes.sale_order_id) budgetGmtWashing'), "budgetGmtWashing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as courier_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =1 group by budgets.style_id) budgetCourier'), "budgetCourier.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as lab_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =5 group by budgets.style_id) budgetLab'), "budgetLab.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as insp_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =10 group by budgets.style_id) budgetInsp'), "budgetInsp.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as freight_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =15 group by budgets.style_id) budgetFreight'), "budgetFreight.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as opa_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =20 group by budgets.style_id) budgetOpa'), "budgetOpa.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as dep_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =25 group by budgets.style_id) budgetDep'), "budgetDep.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as coc_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =30 group by budgets.style_id) budgetCoc'), "budgetCoc.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_others.amount)  as ict_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =35 group by budgets.style_id) budgetIct'), "budgetIct.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, avg(budget_cms.cm_per_pcs)  as cm_rate from budget_cms left join budgets on budgets.id = budget_cms.budget_id   group by budgets.style_id) budgetCm'), "budgetCm.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_commercials.rate)  as commer_rate from budget_commercials left join budgets on budgets.id = budget_commercials.budget_id   group by budgets.style_id) budgetCommer'), "budgetCommer.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select budgets.style_id, sum(budget_commissions.rate)  as commi_rate from budget_commissions left join budgets on budgets.id = budget_commissions.budget_id   group by budgets.style_id) budgetCommi'), "budgetCommi.style_id", "=", "styles.id")
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('sale_order_no'), function ($q) {
		return $q->where('sales_orders.sale_order_no', 'like', '%'.request('sale_order_no', 0).'%');
		})
		//->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'screenprinttgt.qty',
    		'embrotgt.qty',
    		'cutting.qty',
    		'cuttingYesterDay.qty',
    		'sew.qty',
    		'sew.amount',
    		'sewYesterDay.qty',
    		'sewYesterDay.amount',
    		'finishing.qty',
    		'finishingYesterDay.qty',
    		'finishing.amount',
    		'finishingYesterDay.amount',
    		'exfactory.qty',
    		'exfactoryYesterDay.qty',
    		'exfactory.amount',
    		'exfactoryYesterDay.amount',
    		'carton.qty',
    		'cartonYesterDay.qty',
    		'requiredCarton.required_carton',
    		'budgetTrim.trim_amount',
			'budgetKniting.kniting_amount',
			'budgetYarnDyeing.yarn_dying_amount',
			'budgetWeaving.weaving_amount',
			'budgetDyeing.dying_amount',
			'budgetDyeing.overhead_amount',
			'budgetAop.aop_amount',
			'budgetAop.overhead_amount',
			'burnOut.burn_out_amount',
			'budgetFabFinishing.finishing_amount',
			'budgetFabWashing.washing_amount',
			'budgetYarn.yarn_amount',
			'budgetPrinting.printing_amount',
			'budgetPrinting.overhead_amount',
			'budgetEmb.emb_amount',
			'budgetSpEmb.spemb_amount',
			'budgetGmtDyeing.gmt_dyeing_amount',
			'budgetGmtWashing.gmt_washing_amount',
			'budgetCourier.courier_rate',
			'budgetlab.lab_rate',
			'budgetInsp.insp_rate',
			'budgetFreight.freight_rate',
			'budgetOpa.opa_rate',
			'budgetDep.dep_rate',
			'budgetCoc.coc_rate',
			'budgetIct.ict_rate',
			'budgetCm.cm_rate',
			'budgetCommer.commer_rate',
			'budgetCommi.commi_rate'
		])
		->orderBy('sales_orders.id')
		->get()
		->map(function($data){
			$data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_cut=$data->cutting_qty+$data->cuttingyesterday_qty;
			 $data->yet_cut=$data->order_plan_cut_qty-$data->total_cut;
			 $data->total_sew=$data->sew_qty+$data->sewyesterday_qty;
			 $data->yet_sew=$data->order_qty-$data->total_sew;
			 $data->sew_amount=$data->sew_amount;
			 $data->sewyesterday_amount=$data->sewyesterday_amount;
			 $data->total_sew_amount=$data->sew_amount+$data->sewyesterday_amount;
			 $data->yet_sew_amount=$data->order_amount-$data->total_sew_amount;


			 $data->total_finish=$data->finishing_qty+$data->finishingyesterday_qty;
			 $data->yet_finish=$data->order_qty-$data->total_finish;

			 $data->gmtfinishing_amount=$data->gmtfinishing_amount;
			 $data->finishingyesterday_amount=$data->finishingyesterday_amount;

			 $data->total_finish_amount=$data->gmtfinishing_amount+$data->finishingyesterday_amount;
			 $data->yet_finish_amount=$data->order_amount-$data->total_finish_amount;


			 $data->total_exfactory=$data->exfactory_qty+$data->exfactoryyesterday_qty;
			 $data->yet_exfactory=$data->order_qty-$data->total_exfactory;

			 $data->exfactory_amount=$data->exfactory_amount;
			 $data->exfactoryyesterday_amount=$data->exfactoryyesterday_amount;

			 $data->total_exfactory_amount=$data->exfactory_amount+$data->exfactoryyesterday_amount;
			 $data->yet_exfactory_amount=$data->order_amount-$data->total_exfactory_amount;
			 $data->total_no_of_carton=$data->no_of_carton+$data->cartonyesterday_no_of_carton;
			 $data->yet_no_of_carton=$data->required_carton-$data->total_no_of_carton;

			$commmercial=$data->trim_amount+
			$data->kniting_amount+
			$data->yarn_dying_amount+
			$data->weaving_amount+
			$data->dying_amount+$data->dyeing_overhead_amount+$data->finishing_amount+
			$data->aop_amount+$data->aop_overhead_amount+
			$data->burn_out_amount+

			$data->washing_amount+
			$data->yarn_amount+
			$data->printing_amount+$data->print_overhead_amount+
			$data->emb_amount+
			$data->spemb_amount+
			$data->gmt_dyeing_amount+
			$data->gmt_washing_amount;

			$commer_amount=number_format(($data->commer_rate/100)*$commmercial,'2','.','');
			$commi_amount=number_format(($data->commi_rate/100)*$data->order_amount,'2','.','');

			$cm_amount=number_format(($data->cm_rate*$data->order_qty),'2','.','');
			$freight_amount=number_format(($data->freight_rate/12)*$data->order_qty,'2','.','');

			$courier_amount=number_format(($data->courier_rate/12)*$data->order_qty,'2','.','');
			$lab_amount=number_format(($data->lab_rate/12)*$data->order_qty,'2','.','');
			$insp_amount=number_format(($data->insp_rate/12)*$data->order_qty,'2','.','');
			$opa_amount=number_format(($data->opa_rate/12)*$data->order_qty,'2','.','');
			$dep_amount=number_format(($data->dep_rate/12)*$data->order_qty,'2','.','');
			$coc_amount=number_format(($data->coc_rate/12)*$data->order_qty,'2','.','');
			$ict_amount=number_format(($data->ict_rate/12)*$data->order_qty,'2','.','');
			$other_amount=$courier_amount+$lab_amount+$insp_amount+$opa_amount+$dep_amount+ $coc_amount+ $ict_amount;

			$data->required_cm=(($data->order_amount)-($commmercial+$commer_amount+$commi_amount+$freight_amount+$other_amount));
			$data->cmmnuf=0;
			if($data->finishing_qty){
				$data->cmmnuf=($data->required_cm/$data->order_qty)*$data->finishing_qty;
			}
			$data->cmmnuf_yesterday=0;
			if($data->finishing_qty){
				$data->cmmnuf_yesterday=($data->required_cm/$data->order_qty)*$data->finishingyesterday_qty;
			}

           $data->total_cm=$data->cmmnuf+$data->cmmnuf_yesterday;
           $data->yet_cm=$data->required_cm-$data->total_cm;
            return $data;
        })
        ->filter(function($value){
        	if($value->cutting_qty || $value->sew_qty || $value->finishing_qty || $value->exfactory_qty || $value->no_of_carton){
        		return $value;
        	}
        });
        return Template::loadView('Report.CapacityAchivmentCommonPopUpMonth', ['data'=>$data]);
    }

   /*  public function getCutting()
    {
    	$today=date('d-m-Y');
        $str2=request('date_to',0);
        $yesterDay=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $str2) ) ));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.code as company_id,
    		companies.code as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			cutting.qty as cutting_qty,
			cuttingYesterDay.qty as cuttingyesterday_qty
    		')
    	->join('sales_order_countries', function($join) use($date_to) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_cutting_orders', function($join) use($date_to) {
			$join->on('prod_gmt_cutting_orders.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_cuttings', function($join) use($date_to) {
			$join->on('prod_gmt_cuttings.id', '=', 'prod_gmt_cutting_orders.prod_gmt_cutting_id');
		})
		->join('suppliers', function($join) use($date_to) {
			$join->on('suppliers.id', '=', 'prod_gmt_cutting_orders.supplier_id');
		})
		->leftJoin('companies', function($join) use($date_to) {
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
        ->leftJoin('companies as bcompanies', function($join) use($date_to) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_cuttings.cut_qc_date>='".$date_to."' and 
				prod_gmt_cuttings.cut_qc_date<='".$date_to."'
				and suppliers.company_id='".$company_id."'
			group by
			sales_orders.id, 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) cutting"), "cutting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_cuttings.cut_qc_date<='".$yesterDay."'
				and suppliers.company_id='".$company_id."'
			group by
			sales_orders.id, 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) cuttingYesterDay"), "cuttingYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_cuttings.cut_qc_date', '>=',$date_to);
		})
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_cuttings.cut_qc_date', '<=',$date_to);
		})
		->where([['suppliers.company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.code',
    		'companies.code',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'cutting.qty',
    		'cuttingYesterDay.qty',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_cut=$data->cutting_qty+$data->cuttingyesterday_qty;
			 $data->yet_cut=$data->order_plan_cut_qty-$data->total_cut;
			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->cutting_qty=number_format($data->cutting_qty,0);
			 $data->cuttingyesterday_qty=number_format($data->cuttingyesterday_qty,0);
			 $data->total_cut=number_format($data->total_cut,0);
			 $data->yet_cut=number_format($data->yet_cut,0);
            return $data;
        });
        echo json_encode($data);
	} */
	public function getCutting()
    {
    	$today=date('d-m-Y');
        $str2=request('date_to',0);
        $yesterDay=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $str2) ) ));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
		->selectRaw('
			styles.id as style_id,
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.code as company_id,
    		companies.code as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			cutting.qty as cutting_qty,
			cutting.used_fabric,
			cutting.wastage_fabric,
			cuttingYesterDay.qty as cuttingyesterday_qty,
			cad_cons.style_cad_cons
    		')/*  */
    	->join('sales_order_countries', function($join) use($date_to) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_cutting_orders', function($join) use($date_to) {
			$join->on('prod_gmt_cutting_orders.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_cuttings', function($join) use($date_to) {
			$join->on('prod_gmt_cuttings.id', '=', 'prod_gmt_cutting_orders.prod_gmt_cutting_id');
		})
		->join('suppliers', function($join) use($date_to) {
			$join->on('suppliers.id', '=', 'prod_gmt_cutting_orders.supplier_id');
		})
		->leftJoin('companies', function($join) use($date_to) {
			$join->on('companies.id', '=', 'sales_orders.produced_company_id');
		})
		->leftJoin('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
		})
		->leftJoin(\DB::raw("(SELECT styles.id as style_id,
			avg(cad_cons.cons) as style_cad_cons 
			from styles
			  left join cads 
				on styles.id = cads.style_id   
			left join style_fabrications  
				on style_fabrications.style_id = styles.id 
			left join gmtsparts 
				on gmtsparts.id = style_fabrications.gmtspart_id 
			left Join cad_cons 
				on cad_cons.cad_id = cads.id 
			where 
			gmtsparts.part_type_id =1
			group by styles.id) cad_cons"), "cad_cons.style_id","=","styles.id" )
        ->leftJoin('buyers', function($join)  {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->leftJoin('companies as bcompanies', function($join) use($date_to) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,
		sum(m.qty) as qty,
		sum(m.amount) as amount,
		sum(m.smv) as smv,
		sum(m.used_fabric) as used_fabric,
		sum(m.wastage_fabric) as wastage_fabric
		from (SELECT 
			suppliers.company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_orders.used_fabric,
			prod_gmt_cutting_orders.wastage_fabric,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id
			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_cuttings.cut_qc_date>='".$date_to."' and 
				prod_gmt_cuttings.cut_qc_date<='".$date_to."'
				and suppliers.company_id='".$company_id."'

			group by
			sales_orders.id, 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_orders.used_fabric,
			prod_gmt_cutting_orders.wastage_fabric,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) cutting"), "cutting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_cuttings.cut_qc_date<='".$yesterDay."'
				and suppliers.company_id='".$company_id."'
			group by
			sales_orders.id, 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) cuttingYesterDay"), "cuttingYesterDay.sale_order_id", "=", "sales_orders.id")
		
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_cuttings.cut_qc_date', '>=',$date_to);
		})
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_cuttings.cut_qc_date', '<=',$date_to);
		})
		->where([['suppliers.company_id','=',$company_id]])
		->groupBy([
			'styles.id',
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.code',
    		'companies.code',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
			'cutting.qty',
			'cutting.used_fabric',
			'cutting.wastage_fabric',
			'cuttingYesterDay.qty',
			'cad_cons.style_cad_cons'
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_cut=$data->cutting_qty+$data->cuttingyesterday_qty;
			 $data->yet_cut=$data->order_plan_cut_qty-$data->total_cut;
			 $data->req_fabric=0;
			 if($data->total_cut){
				$data->req_fabric=($data->used_fabric/$data->total_cut)*12;
			 }
			 $data->used_variance=$data->style_cad_cons-$data->req_fabric;
			 $data->cut_pcs_should_be=0;
			 if($data->style_cad_cons){
				$data->cut_pcs_should_be=($data->used_fabric/$data->style_cad_cons)/12;
			 }
			 $data->wastage_variance=$data->total_cut-$data->cut_pcs_should_be;
			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->cutting_qty=number_format($data->cutting_qty,0);
			 $data->cuttingyesterday_qty=number_format($data->cuttingyesterday_qty,0);
			 $data->total_cut=number_format($data->total_cut,0);
			 $data->yet_cut=number_format($data->yet_cut,0);
			 $data->style_cad_cons=number_format($data->style_cad_cons,2);
			 $data->used_fabric=number_format($data->used_fabric,2);
			 $data->req_fabric=number_format($data->req_fabric,2);
			 $data->used_variance=number_format($data->used_variance,2);
			 $data->wastage_fabric=number_format($data->wastage_fabric,2);
			 $data->cut_pcs_should_be=number_format($data->cut_pcs_should_be,2);
			 $data->wastage_variance=number_format($data->wastage_variance,2);
            return $data;
        });
        echo json_encode($data);
    }

    public function getCuttingMonth()
    {
        $str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));

    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			cutting.qty as cutting_qty,
			cuttingYesterDay.qty as cuttingyesterday_qty
    		')
    	->join('sales_order_countries', function($join) use($date_to) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_cutting_orders', function($join) use($date_to) {
			$join->on('prod_gmt_cutting_orders.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_cuttings', function($join) use($date_to) {
			$join->on('prod_gmt_cuttings.id', '=', 'prod_gmt_cutting_orders.prod_gmt_cutting_id');
		})
		->join('suppliers', function($join) use($date_to) {
			$join->on('suppliers.id', '=', 'prod_gmt_cutting_orders.supplier_id');
		})
		->leftJoin('companies', function($join) use($date_to) {
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
        ->leftJoin('companies as bcompanies', function($join) use($date_to) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_cuttings.cut_qc_date>='".$start_date."' and 
				prod_gmt_cuttings.cut_qc_date<='".$end_date."'
				and suppliers.company_id='".$company_id."'
			group by
			sales_orders.id, 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) cutting"), "cutting.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
			suppliers.company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_cutting_qties.qty,
			prod_gmt_cutting_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_cutting_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_cuttings
			join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
			join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id  and prod_gmt_cutting_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_cuttings.cut_qc_date<='".$last_month."'
				and suppliers.company_id='".$company_id."'
			group by
			sales_orders.id, 
			suppliers.company_id,
			prod_gmt_cutting_qties.id,
			prod_gmt_cutting_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) cuttingYesterDay"), "cuttingYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('prod_gmt_cuttings.cut_qc_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('prod_gmt_cuttings.cut_qc_date', '<=',$end_date);
		})
		->where([['suppliers.company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'cutting.qty',
    		'cuttingYesterDay.qty',
		])
		->get()
		->map(function($data){
			  $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_cut=$data->cutting_qty+$data->cuttingyesterday_qty;
			 $data->yet_cut=$data->order_plan_cut_qty-$data->total_cut;

			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->cutting_qty=number_format($data->cutting_qty,0);
			 $data->cuttingyesterday_qty=number_format($data->cuttingyesterday_qty,0);
			 $data->total_cut=number_format($data->total_cut,0);
			 $data->yet_cut=number_format($data->yet_cut,0);
            return $data;
        });
        echo json_encode($data);
    }

    public function getScprint()
    {
    	$today=date('d-m-Y');
        $str2=request('date_to',0);
        $yesterDay=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $str2) ) ));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			scprint.qty as scprint_qty,
			scprintYesterDay.qty as scprintyesterday_qty
    		')
    	->join('sales_order_countries', function($join) use($date_to) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_print_rcv_orders', function($join) use($date_to) {
			$join->on('prod_gmt_print_rcv_orders.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_print_rcvs', function($join) use($date_to) {
			$join->on('prod_gmt_print_rcvs.id', '=', 'prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id');
		})
		->join('suppliers', function($join) use($date_to) {
			$join->on('suppliers.id', '=', 'prod_gmt_print_rcv_orders.supplier_id');
		})
		->leftJoin('companies', function($join) use($date_to) {
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
        ->leftJoin('companies as bcompanies', function($join) use($date_to) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_print_rcv_qties.qty,
			prod_gmt_print_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_print_rcvs
			join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
			join suppliers on suppliers.id=prod_gmt_print_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id  
			and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_print_rcvs.receive_date>='".$date_to."' and 
				prod_gmt_print_rcvs.receive_date<='".$date_to."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_print_rcv_qties.id,
			prod_gmt_print_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) scprint"), "scprint.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_print_rcv_qties.qty,
			prod_gmt_print_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			FROM prod_gmt_print_rcvs
			join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
			join suppliers on suppliers.id=prod_gmt_print_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id  
			and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_print_rcvs.receive_date<='".$yesterDay."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_print_rcv_qties.id,
			prod_gmt_print_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) scprintYesterDay"), "scprintYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_print_rcvs.receive_date', '>=',$date_to);
		})
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_print_rcvs.receive_date', '<=',$date_to);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'scprint.qty',
    		'scprintYesterDay.qty',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_scprint=$data->scprint_qty+$data->scprintyesterday_qty;
			 $data->yet_scprint=$data->order_qty-$data->total_scprint;
			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->scprint_qty=number_format($data->scprint_qty,0);
			 $data->scprintyesterday_qty=number_format($data->scprintyesterday_qty,0);
			 $data->total_scprint=number_format($data->total_scprint,0);
			 $data->yet_scprint=number_format($data->yet_scprint,0);
            return $data;
        });
        echo json_encode($data);
    }

    public function getScprintMonth()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			scprint.qty as scprint_qty,
			scprintYesterDay.qty as scprintyesterday_qty
    		')
    	->join('sales_order_countries', function($join) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_print_rcv_orders', function($join) {
			$join->on('prod_gmt_print_rcv_orders.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_print_rcvs', function($join)  {
			$join->on('prod_gmt_print_rcvs.id', '=', 'prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id');
		})
		->join('suppliers', function($join)  {
			$join->on('suppliers.id', '=', 'prod_gmt_print_rcv_orders.supplier_id');
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
        ->leftJoin('companies as bcompanies', function($join)  {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_print_rcv_qties.qty,
			prod_gmt_print_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_print_rcvs
			join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
			join suppliers on suppliers.id=prod_gmt_print_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id  
			and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_print_rcvs.receive_date>='".$start_date."' and 
				prod_gmt_print_rcvs.receive_date<='".$end_date."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_print_rcv_qties.id,
			prod_gmt_print_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) scprint"), "scprint.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_print_rcv_qties.qty,
			prod_gmt_print_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_print_rcvs
			join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
			join suppliers on suppliers.id=prod_gmt_print_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id  
			and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_print_rcvs.receive_date<='".$last_month."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_print_rcv_qties.id,
			prod_gmt_print_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) scprintYesterDay"), "scprintYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('prod_gmt_print_rcvs.receive_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('prod_gmt_print_rcvs.receive_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'scprint.qty',
    		'scprintYesterDay.qty',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_scprint=$data->scprint_qty+$data->scprintyesterday_qty;
			 $data->yet_scprint=$data->order_qty-$data->total_scprint;
			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->scprint_qty=number_format($data->scprint_qty,0);
			 $data->scprintyesterday_qty=number_format($data->scprintyesterday_qty,0);
			 $data->total_scprint=number_format($data->total_scprint,0);
			 $data->yet_scprint=number_format($data->yet_scprint,0);
            return $data;
        });
        echo json_encode($data);
    }

    public function getScprintMonthTgt()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			sum(budget_emb_cons.req_cons) as req_qty,
			scprint.qty as scprint_qty,
			scprintYesterDay.qty as scprintyesterday_qty
    		')
    	->join('sales_order_countries', function($join) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
        })
        ->join('budgets', function($join)  {
            $join->on('budgets.job_id', '=', 'jobs.id');
        })
        ->join('budget_embs', function($join)  {
            $join->on('budget_embs.budget_id', '=', 'budgets.id');
        })
        ->join('budget_emb_cons', function($join)  {
            $join->on('budget_emb_cons.budget_emb_id', '=', 'budget_embs.id');
            $join->on('budget_emb_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
        })
        ->join('style_embelishments', function($join)  {
            $join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
        })
        ->join('embelishments', function($join)  {
            $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
        })
        ->join('production_processes', function($join)  {
            $join->on('production_processes.id', '=', 'embelishments.production_process_id');
        })
		
		->leftJoin('companies', function($join)  {
			$join->on('companies.id', '=', 'sales_orders.produced_company_id');
		})
		
        ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('buyers', function($join)  {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->leftJoin('companies as bcompanies', function($join)  {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_print_rcv_qties.qty,
			prod_gmt_print_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_print_rcvs
			join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
			join suppliers on suppliers.id=prod_gmt_print_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id  
			and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_print_rcvs.receive_date>='".$start_date."' and 
				prod_gmt_print_rcvs.receive_date<='".$end_date."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_print_rcv_qties.id,
			prod_gmt_print_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) scprint"), "scprint.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_print_rcv_qties.qty,
			prod_gmt_print_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_print_rcvs
			join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
			join suppliers on suppliers.id=prod_gmt_print_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id  
			and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_print_rcvs.receive_date<='".$last_month."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_print_rcv_qties.id,
			prod_gmt_print_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) scprintYesterDay"), "scprintYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('sales_orders.ship_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('sales_orders.ship_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->where([['production_processes.production_area_id','=',45]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'scprint.qty',
    		'scprintYesterDay.qty',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_scprint=$data->scprint_qty+$data->scprintyesterday_qty;
			 $data->yet_scprint=$data->req_qty-$data->total_scprint;
			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->req_qty=number_format($data->req_qty,2);
			 $data->scprint_qty=number_format($data->scprint_qty,0);
			 $data->scprintyesterday_qty=number_format($data->scprintyesterday_qty,0);
			 $data->total_scprint=number_format($data->total_scprint,0);
			 $data->yet_scprint=number_format($data->yet_scprint,0);
            return $data;
        });
        echo json_encode($data);
    }

    

    public function getEmb()
    {
    	$today=date('d-m-Y');
        $str2=request('date_to',0);
        $yesterDay=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $str2) ) ));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			embr.qty as emb_qty,
			embrYesterDay.qty as embyesterday_qty
    		')
    	->join('sales_order_countries', function($join) use($date_to) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_emb_rcv_orders', function($join) use($date_to) {
			$join->on('prod_gmt_emb_rcv_orders.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_emb_rcvs', function($join) use($date_to) {
			$join->on('prod_gmt_emb_rcvs.id', '=', 'prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id');
		})
		->join('suppliers', function($join) use($date_to) {
			$join->on('suppliers.id', '=', 'prod_gmt_emb_rcv_orders.supplier_id');
		})
		->leftJoin('companies', function($join) use($date_to) {
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
        ->leftJoin('companies as bcompanies', function($join) use($date_to) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_emb_rcv_qties.qty,
			prod_gmt_emb_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_emb_rcvs
			join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id = prod_gmt_emb_rcvs.id
			join suppliers on suppliers.id=prod_gmt_emb_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_emb_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_emb_rcv_qties on prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id = prod_gmt_emb_rcv_orders.id  
			and prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_emb_rcvs.receive_date>='".$date_to."' and 
				prod_gmt_emb_rcvs.receive_date<='".$date_to."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_emb_rcv_qties.id,
			prod_gmt_emb_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) embr"), "embr.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_emb_rcv_qties.qty,
			prod_gmt_emb_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_emb_rcvs
			join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id = prod_gmt_emb_rcvs.id
			join suppliers on suppliers.id=prod_gmt_emb_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_emb_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_emb_rcv_qties on prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id = prod_gmt_emb_rcv_orders.id  
			and prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_emb_rcvs.receive_date<='".$yesterDay."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_emb_rcv_qties.id,
			prod_gmt_emb_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) embrYesterDay"), "embrYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_emb_rcvs.receive_date', '>=',$date_to);
		})
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_emb_rcvs.receive_date', '<=',$date_to);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'embr.qty',
    		'embrYesterDay.qty',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_emb=$data->emb_qty+$data->embyesterday_qty;
			 $data->yet_emb=$data->order_qty-$data->total_emb;
			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->emb_qty=number_format($data->emb_qty,0);
			 $data->embyesterday_qty=number_format($data->embyesterday_qty,0);
			 $data->total_emb=number_format($data->total_emb,0);
			 $data->yet_emb=number_format($data->yet_emb,0);
            return $data;
        });
        echo json_encode($data);
    }

    public function getEmbMonth()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			embr.qty as emb_qty,
			embrYesterDay.qty as embyesterday_qty
    		')
    	->join('sales_order_countries', function($join) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_emb_rcv_orders', function($join) {
			$join->on('prod_gmt_emb_rcv_orders.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_emb_rcvs', function($join)  {
			$join->on('prod_gmt_emb_rcvs.id', '=', 'prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id');
		})
		->join('suppliers', function($join)  {
			$join->on('suppliers.id', '=', 'prod_gmt_emb_rcv_orders.supplier_id');
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
        ->leftJoin('companies as bcompanies', function($join)  {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_emb_rcv_qties.qty,
			prod_gmt_emb_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_emb_rcvs
			join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id = prod_gmt_emb_rcvs.id
			join suppliers on suppliers.id=prod_gmt_emb_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_emb_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_emb_rcv_qties on prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id = prod_gmt_emb_rcv_orders.id  
			and prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_emb_rcvs.receive_date>='".$start_date."' and 
				prod_gmt_emb_rcvs.receive_date<='".$end_date."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_emb_rcv_qties.id,
			prod_gmt_emb_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) embr"), "embr.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_emb_rcv_qties.qty,
			prod_gmt_emb_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_emb_rcvs
			join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id = prod_gmt_emb_rcvs.id
			join suppliers on suppliers.id=prod_gmt_emb_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_emb_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_emb_rcv_qties on prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id = prod_gmt_emb_rcv_orders.id  
			and prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_emb_rcvs.receive_date<='".$last_month."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_emb_rcv_qties.id,
			prod_gmt_emb_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) embrYesterDay"), "embrYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('prod_gmt_emb_rcvs.receive_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('prod_gmt_emb_rcvs.receive_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'embr.qty',
    		'embrYesterDay.qty',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_emb=$data->emb_qty+$data->embyesterday_qty;
			 $data->yet_emb=$data->order_qty-$data->total_emb;
			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->emb_qty=number_format($data->emb_qty,0);
			 $data->embyesterday_qty=number_format($data->embyesterday_qty,0);
			 $data->total_emb=number_format($data->total_emb,0);
			 $data->yet_emb=number_format($data->yet_emb,0);
            return $data;
        });
        echo json_encode($data);
    }

    public function getEmbMonthTgt()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			sum(budget_emb_cons.req_cons) as req_qty,
			scprint.qty as scprint_qty,
			scprintYesterDay.qty as scprintyesterday_qty
    		')
    	->join('sales_order_countries', function($join) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
        })
        ->join('budgets', function($join)  {
            $join->on('budgets.job_id', '=', 'jobs.id');
        })
        ->join('budget_embs', function($join)  {
            $join->on('budget_embs.budget_id', '=', 'budgets.id');
        })
        ->join('budget_emb_cons', function($join)  {
            $join->on('budget_emb_cons.budget_emb_id', '=', 'budget_embs.id');
            $join->on('budget_emb_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
        })
        ->join('style_embelishments', function($join)  {
            $join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
        })
        ->join('embelishments', function($join)  {
            $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
        })
        ->join('production_processes', function($join)  {
            $join->on('production_processes.id', '=', 'embelishments.production_process_id');
        })
		
		->leftJoin('companies', function($join)  {
			$join->on('companies.id', '=', 'sales_orders.produced_company_id');
		})
		
        ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('buyers', function($join)  {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->leftJoin('companies as bcompanies', function($join)  {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_print_rcv_qties.qty,
			prod_gmt_print_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_print_rcvs
			join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
			join suppliers on suppliers.id=prod_gmt_print_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id  
			and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where prod_gmt_print_rcvs.receive_date>='".$start_date."' and 
				prod_gmt_print_rcvs.receive_date<='".$end_date."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_print_rcv_qties.id,
			prod_gmt_print_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) scprint"), "scprint.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_print_rcv_qties.qty,
			prod_gmt_print_rcv_qties.qty*sales_order_gmt_color_sizes.rate as amount
			

			FROM prod_gmt_print_rcvs
			join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
			join suppliers on suppliers.id=prod_gmt_print_rcv_orders.supplier_id

			join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id

			join jobs on jobs.id = sales_orders.job_id

			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id

			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id

			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

			join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id  
			and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

			where  
				prod_gmt_print_rcvs.receive_date<='".$last_month."'
				and sales_orders.produced_company_id='".$company_id."'
			group by
			sales_orders.id, 
			sales_orders.produced_company_id,
			prod_gmt_print_rcv_qties.id,
			prod_gmt_print_rcv_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) scprintYesterDay"), "scprintYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('sales_orders.ship_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('sales_orders.ship_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->where([['production_processes.production_area_id','=',50]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'scprint.qty',
    		'scprintYesterDay.qty',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_emb=$data->emb_qty+$data->embyesterday_qty;
			 $data->yet_emb=$data->req_qty-$data->total_emb;
			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->req_qty=number_format($data->req_qty,0);
			 $data->emb_qty=number_format($data->emb_qty,0);
			 $data->embyesterday_qty=number_format($data->embyesterday_qty,0);
			 $data->total_emb=number_format($data->total_emb,0);
			 $data->yet_emb=number_format($data->yet_emb,0);
            return $data;
        });
        echo json_encode($data);
    }

    public function getAopMonthTgt()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			sum(budget_fabric_prod_cons.bom_qty) as req_qty
    		')
    	->join('sales_order_countries', function($join) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        /*->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
        })*/
        ->join('budgets', function($join)  {
            $join->on('budgets.job_id', '=', 'jobs.id');
        })
        ->join('budget_fabric_prods', function($join)  {
            $join->on('budget_fabric_prods.budget_id', '=', 'budgets.id');
        })
        ->join('budget_fabric_prod_cons', function($join)  {
            $join->on('budget_fabric_prod_cons.budget_fabric_prod_id', '=', 'budget_fabric_prods.id');
            $join->on('budget_fabric_prod_cons.sales_order_id', '=', 'sales_orders.id');
        })
        /*->join('style_embelishments', function($join)  {
            $join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
        })
        ->join('embelishments', function($join)  {
            $join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
        })*/
        ->join('production_processes', function($join)  {
            $join->on('production_processes.id', '=', 'budget_fabric_prods.production_process_id');
        })
		
		->leftJoin('companies', function($join)  {
			$join->on('companies.id', '=', 'sales_orders.produced_company_id');
		})
		
        ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('buyers', function($join)  {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->leftJoin('companies as bcompanies', function($join)  {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('sales_orders.ship_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('sales_orders.ship_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->where([['production_processes.production_area_id','=',25]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->req_qty=number_format($data->req_qty,0);
            return $data;
        });
        echo json_encode($data);
    }

    public function getExfactory()
    {
    	$today=date('d-m-Y');
        $str2=request('date_to',0);
        $yesterDay=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $str2) ) ));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			exfactory.qty as exfactory_qty,
			exfactory.amount as exfactory_amount,
			exfactoryYesterDay.qty as exfactoryyesterday_qty,
			exfactoryYesterDay.amount as exfactoryyesterday_amount
    		')
    	->join('sales_order_countries', function($join) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_carton_details', function($join) {
			$join->on('prod_gmt_carton_details.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_carton_entries', function($join){
			$join->on('prod_gmt_carton_entries.id', '=', 'prod_gmt_carton_details.prod_gmt_carton_entry_id');
		})
		->join('style_pkgs', function($join){
			$join->on('style_pkgs.id', '=', 'prod_gmt_carton_details.style_pkg_id');
		})
		->join('style_pkg_ratios', function($join){
			$join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
		})
		->join('prod_gmt_ex_factory_qties', function($join) {
			$join->on('prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id', '=', 'prod_gmt_carton_details.id');
		    $join->WhereNull('prod_gmt_ex_factory_qties.deleted_at');

		})
		->join('prod_gmt_ex_factories', function($join) use($date_to) {
			$join->on('prod_gmt_ex_factories.id', '=', 'prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id');
			$join->where('prod_gmt_ex_factories.exfactory_date', '>=',$date_to);
			$join->where('prod_gmt_ex_factories.exfactory_date', '<=',$date_to);
		})

		
		->leftJoin('companies', function($join) use($date_to) {
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
        ->leftJoin('companies as bcompanies', function($join) use($date_to) {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
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
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where sales_order_gmt_color_sizes.qty>0 and sales_order_gmt_color_sizes.deleted_at is null
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(
			select 
			m.produced_company_id as company_id,
			m.sale_order_id,
			sum(m.qty) as qty, 
			sum(m.amount) as amount 
			FROM (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where prod_gmt_ex_factories.exfactory_date>='".$date_to."' and 
			prod_gmt_ex_factories.exfactory_date<='".$date_to."'
			
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_ex_factories.exfactory_date>='".$date_to."' and 
			prod_gmt_ex_factories.exfactory_date<='".$date_to."'
			
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) exfactory"), "exfactory.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
			select 
			m.produced_company_id as company_id,
			m.sale_order_id,
			sum(m.qty) as qty, 
			sum(m.amount) as amount 
			FROM (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where
			prod_gmt_ex_factories.exfactory_date<='".$yesterDay."'
			
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where 
			prod_gmt_ex_factories.exfactory_date<='".$yesterDay."'
			
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) exfactoryYesterDay"), "exfactoryYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_ex_factories.exfactory_date', '>=',$date_to);
		})
		->when($date_to, function ($q) use($date_to){
		return $q->where('prod_gmt_ex_factories.exfactory_date', '<=',$date_to);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'exfactory.qty',
    		'exfactory.amount',
    		'exfactoryYesterDay.qty',
    		'exfactoryYesterDay.amount'
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_exfactory=$data->exfactory_qty+$data->exfactoryyesterday_qty;
			 $data->yet_exfactory=$data->order_qty-$data->total_exfactory;

			 $data->total_exfactory_amount=$data->exfactory_amount+$data->exfactoryyesterday_amount;
			 $data->yet_exfactory_amount=$data->order_amount-$data->total_exfactory_amount;

			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->exfactory_qty=number_format($data->exfactory_qty,0);
			 $data->exfactoryyesterday_qty=number_format($data->exfactoryyesterday_qty,0);
			 $data->total_exfactory=number_format($data->total_exfactory,0);
			 $data->yet_exfactory=number_format($data->yet_exfactory,0);

			 $data->exfactory_amount=number_format($data->exfactory_amount,0);
			 $data->exfactoryyesterday_amount=number_format($data->exfactoryyesterday_amount,0);
			 $data->total_exfactory_amount=number_format($data->total_exfactory_amount,0);
			 $data->yet_exfactory_amount=number_format($data->yet_exfactory_amount,0);
            return $data;
        });
        echo json_encode($data);
    }

    public function getExfactoryMonth()
    {
    	$today=date('d-m-Y');
        $str2=request('date_to',0);
        
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			exfactory.qty as exfactory_qty,
			exfactory.amount as exfactory_amount,
			exfactoryYesterDay.qty as exfactoryyesterday_qty,
			exfactoryYesterDay.amount as exfactoryyesterday_amount
    		')
    	->join('sales_order_countries', function($join){
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_carton_details', function($join){
			$join->on('prod_gmt_carton_details.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('prod_gmt_carton_entries', function($join)  {
			$join->on('prod_gmt_carton_entries.id', '=', 'prod_gmt_carton_details.prod_gmt_carton_entry_id');
			
		})
		->join('style_pkgs', function($join){
			$join->on('style_pkgs.id', '=', 'prod_gmt_carton_details.style_pkg_id');
		})
		->join('style_pkg_ratios', function($join) {
			$join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
		})
		->join('prod_gmt_ex_factory_qties', function($join) {
			$join->on('prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id', '=', 'prod_gmt_carton_details.id');
		    $join->WhereNull('prod_gmt_ex_factory_qties.deleted_at');

		})
		->join('prod_gmt_ex_factories', function($join) use($start_date,$end_date) {
			$join->on('prod_gmt_ex_factories.id', '=', 'prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id');
			$join->where('prod_gmt_ex_factories.exfactory_date', '>=',$start_date);
			$join->where('prod_gmt_ex_factories.exfactory_date', '<=',$end_date);
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
		->leftJoin('teams', function($join)  {
			$join->on('styles.team_id', '=', 'teams.id');
		})
		->leftJoin('teammembers', function($join)  {
			$join->on('styles.factory_merchant_id', '=', 'teammembers.id');
		})
		->leftJoin('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
		})
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			where sales_order_gmt_color_sizes.qty>0 and sales_order_gmt_color_sizes.deleted_at is null
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->leftJoin(\DB::raw("(
			select 
			m.produced_company_id as company_id,
			m.sale_order_id,
			sum(m.qty) as qty, 
			sum(m.amount) as amount 
			FROM (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id
			and styles.id = style_pkgs.style_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where prod_gmt_ex_factories.exfactory_date>='".$start_date."' and 
			prod_gmt_ex_factories.exfactory_date<='".$end_date."'
			and sales_order_gmt_color_sizes.deleted_at is null
			and sales_order_gmt_color_sizes.qty >0
			
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where prod_gmt_ex_factories.exfactory_date>='".$start_date."' and 
			prod_gmt_ex_factories.exfactory_date<='".$end_date."'
			
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) exfactory"), "exfactory.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(
			select 
			m.produced_company_id as company_id,
			m.sale_order_id,
			sum(m.qty) as qty, 
			sum(m.amount) as amount 
			FROM (
			SELECT 
			sales_orders.id as sale_order_id,
			sales_orders.produced_company_id,
			sum(style_pkg_ratios.qty) as qty ,
			saleorders.rate,
			sum(style_pkg_ratios.qty)*saleorders.rate as amount
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
			join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id
			and styles.id = style_pkgs.style_id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			left join (SELECT 
			sales_orders.id as sale_order_id,
			avg(sales_order_gmt_color_sizes.rate) as rate 
			FROM prod_gmt_carton_entries
			join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
			join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id=prod_gmt_carton_details.id
			join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id and prod_gmt_ex_factory_qties.deleted_at is null

			where
			prod_gmt_ex_factories.exfactory_date<='".$last_month."'
			
			group by sales_orders.id) saleorders on saleorders.sale_order_id=sales_orders.id
			where 
			prod_gmt_ex_factories.exfactory_date<='".$last_month."'
			
			group by sales_orders.id,sales_orders.produced_company_id,saleorders.rate) m group by m.produced_company_id,m.sale_order_id) exfactoryYesterDay"), "exfactoryYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('prod_gmt_ex_factories.exfactory_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('prod_gmt_ex_factories.exfactory_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'exfactory.qty',
    		'exfactory.amount',
    		'exfactoryYesterDay.qty',
    		'exfactoryYesterDay.amount',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_exfactory=$data->exfactory_qty+$data->exfactoryyesterday_qty;
			 $data->yet_exfactory=$data->order_qty-$data->total_exfactory;

			 $data->total_exfactory_amount=$data->exfactory_amount+$data->exfactoryyesterday_amount;
			 $data->yet_exfactory_amount=$data->order_amount-$data->total_exfactory_amount;

			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->exfactory_qty=number_format($data->exfactory_qty,0);
			 $data->exfactoryyesterday_qty=number_format($data->exfactoryyesterday_qty,0);
			 $data->total_exfactory=number_format($data->total_exfactory,0);
			 $data->yet_exfactory=number_format($data->yet_exfactory,0);

			 $data->exfactory_amount=number_format($data->exfactory_amount,0);
			 $data->exfactoryyesterday_amount=number_format($data->exfactoryyesterday_amount,0);
			 $data->total_exfactory_amount=number_format($data->total_exfactory_amount,0);
			 $data->yet_exfactory_amount=number_format($data->yet_exfactory_amount,0);
            return $data;
        });
        echo json_encode($data);
    }

    public function getSewingMonth()
    {
    	$today=date('d-m-Y');
        $str2=request('date_to',0);
        
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			sew.qty as sewing_qty,
			sew.amount as sewing_amount,
			sewYesterDay.qty as sewyesterday_qty,
			sewYesterDay.amount as sewyesterday_amount
    		')
    	->join('sales_order_countries', function($join){
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_sewing_orders', function($join){
			$join->on('prod_gmt_sewing_orders.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('wstudy_line_setups', function($join){
			$join->on('wstudy_line_setups.id', '=', 'prod_gmt_sewing_orders.wstudy_line_setup_id');
		})

		->join('wstudy_line_setup_dtls', function($join) use($start_date,$end_date){
			$join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
			$join->where('wstudy_line_setup_dtls.from_date', '>=',$start_date);
			$join->where('wstudy_line_setup_dtls.to_date', '<=',$end_date);
		})
		

		->join('prod_gmt_sewings', function($join) use($start_date,$end_date) {
			$join->on('prod_gmt_sewings.id', '=','prod_gmt_sewing_orders.prod_gmt_sewing_id');
			$join->where('prod_gmt_sewings.sew_qc_date', '>=',$start_date);
			$join->where('prod_gmt_sewings.sew_qc_date', '<=',$end_date);
			
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
		->leftJoin('teams', function($join)  {
			$join->on('styles.team_id', '=', 'teams.id');
		})
		->leftJoin('teammembers', function($join)  {
			$join->on('styles.factory_merchant_id', '=', 'teammembers.id');
		})
		->leftJoin('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
		})
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (
			SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id

			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			wstudy_line_setup_dtls.from_date>='".$start_date."' and 
				wstudy_line_setup_dtls.to_date<='".$end_date."'

			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where prod_gmt_sewings.sew_qc_date>='".$start_date."' and 
				prod_gmt_sewings.sew_qc_date<='".$end_date."'
				and sales_orders.produced_company_id='".$company_id."'
			group by 
			sales_orders.produced_company_id,
			sales_orders.id,
			prod_gmt_sewing_qties.id,
			prod_gmt_sewing_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) sew"), "sew.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (
			SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			
				wstudy_line_setup_dtls.to_date<='".$last_month."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where  
				prod_gmt_sewings.sew_qc_date<='".$last_month."'
				and sales_orders.produced_company_id='".$company_id."'
			group by 
			sales_orders.produced_company_id,
			sales_orders.id,
			prod_gmt_sewing_qties.id,
			prod_gmt_sewing_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) sewYesterDay"), "sewYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('prod_gmt_sewings.sew_qc_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('prod_gmt_sewings.sew_qc_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'sew.qty',
    		'sew.amount',
    		'sewYesterDay.qty',
    		'sewYesterDay.amount',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_sewing=$data->sewing_qty+$data->sewyesterday_qty;
			 $data->yet_sewing=$data->order_qty-$data->total_sewing;

			 $data->total_sewing_amount=$data->sewing_amount+$data->sewyesterday_amount;
			 $data->yet_sewing_amount=$data->order_amount-$data->total_sewing_amount;

			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->sewing_qty=number_format($data->sewing_qty,0);
			 $data->sewingyesterday_qty=number_format($data->sewyesterday_qty,0);
			 $data->total_sewing=number_format($data->total_sewing,0);
			 $data->yet_sewing=number_format($data->yet_sewing,0);

			 $data->sewing_amount=number_format($data->sewing_amount,0);
			 $data->sewingyesterday_amount=number_format($data->sewyesterday_amount,0);
			 $data->total_sewing_amount=number_format($data->total_sewing_amount,0);
			 $data->yet_sewing_amount=number_format($data->yet_sewing_amount,0);

            return $data;
        });
        echo json_encode($data);
    }

    public function getInvoiceMonth()
    {
    	$today=date('d-m-Y');
        $str2=request('date_to',0);
        
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			expinvoice.qty as invoice_qty,
			expinvoice.amount as invoice_amount,
			expinvoiceYesterDay.qty as invoiceyesterday_qty,
			expinvoiceYesterDay.amount as invoiceyesterday_amount
    		')
    	->join('exp_pi_orders', function($join){
			$join->on('exp_pi_orders.sales_order_id', '=', 'sales_orders.id');
		})
		->join('exp_invoice_orders', function($join){
			$join->on('exp_invoice_orders.exp_pi_order_id', '=', 'exp_pi_orders.id');
		})
		->join('exp_invoices', function($join) use($start_date,$end_date){
			$join->on('exp_invoices.id', '=', 'exp_invoice_orders.exp_invoice_id');
			$join->where('exp_invoices.invoice_date', '>=',$start_date);
			$join->where('exp_invoices.invoice_date', '<=',$end_date);
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
		->leftJoin('teams', function($join)  {
			$join->on('styles.team_id', '=', 'teams.id');
		})
		->leftJoin('teammembers', function($join)  {
			$join->on('styles.factory_merchant_id', '=', 'teammembers.id');
		})
		->leftJoin('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
		})
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT      
				sales_orders.id as sale_order_id,
				sum(exp_invoice_orders.qty) as qty,
				sum(exp_invoice_orders.amount) as amount
				FROM exp_invoices
				join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id
				join exp_pi_orders on exp_pi_orders.id = exp_invoice_orders.exp_pi_order_id

				join sales_orders on sales_orders.id = exp_pi_orders.sales_order_id
				where exp_invoices.invoice_date>='".$start_date."' and 
				exp_invoices.invoice_date<='".$end_date."'
				group by 
				sales_orders.id) expinvoice"), "expinvoice.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT      
				sales_orders.id as sale_order_id,
				sum(exp_invoice_orders.qty) as qty,
				sum(exp_invoice_orders.amount) as amount
				FROM exp_invoices
				join exp_invoice_orders on exp_invoice_orders.exp_invoice_id = exp_invoices.id
				join exp_pi_orders on exp_pi_orders.id = exp_invoice_orders.exp_pi_order_id
				join sales_orders on sales_orders.id = exp_pi_orders.sales_order_id
				where
				exp_invoices.invoice_date<='".$last_month."'
				group by 
				sales_orders.id) expinvoiceYesterDay"), "expinvoiceYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('exp_invoices.invoice_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('exp_invoices.invoice_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'expinvoice.qty',
    		'expinvoice.amount',
    		'expinvoiceYesterDay.qty',
    		'expinvoiceYesterDay.amount'
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_invoice=$data->invoice_qty+$data->invoiceyesterday_qty;
			 $data->yet_invoice=$data->order_qty-$data->total_invoice;

			 $data->total_invoice_amount=$data->invoice_amount+$data->invoiceyesterday_amount;
			 $data->yet_invoice_amount=$data->order_amount-$data->total_invoice_amount;

			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->invoice_qty=number_format($data->invoice_qty,0);
			 $data->invoiceyesterday_qty=number_format($data->invoiceyesterday_qty,0);
			 $data->total_invoice=number_format($data->total_invoice,0);
			 $data->yet_invoice=number_format($data->yet_invoice,0);

			 $data->invoice_amount=number_format($data->invoice_amount,0);
			 $data->invoiceyesterday_amount=number_format($data->invoiceyesterday_amount,0);
			 $data->total_invoice_amount=number_format($data->total_invoice_amount,0);
			 $data->yet_invoice_amount=number_format($data->yet_invoice_amount,0);
            return $data;
        });
        echo json_encode($data);
	}
	/////////////////////
	public function getSewingQtyMonth()
    {
    	$today=date('d-m-Y');
        $str2=request('date_to',0);
        
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			sew.qty as sewing_qty,
			sew.amount as sewing_amount,
			sewYesterDay.qty as sewyesterday_qty,
			sewYesterDay.amount as sewyesterday_amount
    		')
    	->join('sales_order_countries', function($join){
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('prod_gmt_sewing_orders', function($join){
			$join->on('prod_gmt_sewing_orders.sales_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('wstudy_line_setups', function($join){
			$join->on('wstudy_line_setups.id', '=', 'prod_gmt_sewing_orders.wstudy_line_setup_id');
		})
		->join('wstudy_line_setup_dtls', function($join) use($start_date,$end_date){
			$join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
			$join->where('wstudy_line_setup_dtls.from_date', '>=',$start_date);
			$join->where('wstudy_line_setup_dtls.to_date', '<=',$end_date);
		})
		->join('prod_gmt_sewings', function($join) use($start_date,$end_date) {
			$join->on('prod_gmt_sewings.id', '=','prod_gmt_sewing_orders.prod_gmt_sewing_id');
			$join->where('prod_gmt_sewings.sew_qc_date', '>=',$start_date);
			$join->where('prod_gmt_sewings.sew_qc_date', '<=',$end_date);			
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
		->leftJoin('teams', function($join)  {
			$join->on('styles.team_id', '=', 'teams.id');
		})
		->leftJoin('teammembers', function($join)  {
			$join->on('styles.factory_merchant_id', '=', 'teammembers.id');
		})
		->leftJoin('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
		})
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (
			SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv

			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id

			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			wstudy_line_setup_dtls.from_date>='".$start_date."' and 
				wstudy_line_setup_dtls.to_date<='".$end_date."'

			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id

			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where prod_gmt_sewings.sew_qc_date>='".$start_date."' and 
				prod_gmt_sewings.sew_qc_date<='".$end_date."'
				and sales_orders.produced_company_id='".$company_id."'
			group by 
			sales_orders.produced_company_id,
			sales_orders.id,
			prod_gmt_sewing_qties.id,
			prod_gmt_sewing_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) sew"), "sew.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT m.company_id,m.sale_order_id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (
			SELECT 
			sales_orders.produced_company_id as company_id,
			sales_orders.id as sale_order_id,
			prod_gmt_sewing_qties.qty,
			prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
			prod_gmt_sewing_qties.qty*style_gmts.smv as smv
			FROM prod_gmt_sewings
			join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
			join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
			join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
			
				wstudy_line_setup_dtls.to_date<='".$last_month."'
			join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
			join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
			join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
			where  
				prod_gmt_sewings.sew_qc_date<='".$last_month."'
				and sales_orders.produced_company_id='".$company_id."'
			group by 
			sales_orders.produced_company_id,
			sales_orders.id,
			prod_gmt_sewing_qties.id,
			prod_gmt_sewing_qties.qty,
			sales_order_gmt_color_sizes.id,
			sales_order_gmt_color_sizes.rate,
			style_gmts.smv) m group by m.company_id,m.sale_order_id) sewYesterDay"), "sewYesterDay.sale_order_id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('prod_gmt_sewings.sew_qc_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('prod_gmt_sewings.sew_qc_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
    		'sew.qty',
    		'sew.amount',
    		'sewYesterDay.qty',
    		'sewYesterDay.amount',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->total_sewing=$data->sewing_qty+$data->sewyesterday_qty;
			 $data->yet_sewing=$data->order_qty-$data->total_sewing;

			 $data->total_sewing_amount=$data->sewing_amount+$data->sewyesterday_amount;
			 $data->yet_sewing_amount=$data->order_amount-$data->total_sewing_amount;

			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->sewing_qty=number_format($data->sewing_qty,0);
			 $data->sewingyesterday_qty=number_format($data->sewyesterday_qty,0);
			 $data->total_sewing=number_format($data->total_sewing,0);
			 $data->yet_sewing=number_format($data->yet_sewing,0);

			 $data->sewing_amount=number_format($data->sewing_amount,0);
			 $data->sewingyesterday_amount=number_format($data->sewyesterday_amount,0);
			 $data->total_sewing_amount=number_format($data->total_sewing_amount,0);
			 $data->yet_sewing_amount=number_format($data->yet_sewing_amount,0);

            return $data;
        });
        echo json_encode($data);
	}
}