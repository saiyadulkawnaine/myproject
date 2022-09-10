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

class OrderWiseBudgetController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $itemaccount;
	private $budget;
	private $embelishmenttype;
	private $salesordergmtcolorsize;
	public function __construct(StyleRepository $style,CompanyRepository $company,BuyerRepository $buyer,ItemAccountRepository $itemaccount,BudgetRepository $budget,EmbelishmentTypeRepository $embelishmenttype,SalesOrderGmtColorSizeRepository $salesordergmtcolorsize)
    {
		$this->style                     =$style;
		$this->company                   = $company;
		$this->buyer                     = $buyer;
		$this->itemaccount               = $itemaccount;
		$this->budget                    = $budget;
		$this->embelishmenttype          = $embelishmenttype;
		$this->salesordergmtcolorsize    = $salesordergmtcolorsize;

		$this->middleware('auth');
		$this->middleware('permission:view.orderwisebudgetreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'',0);
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
        return Template::loadView('Report.OrderWiseBudget',['company'=>$company,'buyer'=>$buyer,'status'=>$status]);
    }
    public function formatTwo(){
    	$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
    	$datas=$this->reportData();
		$rows=$datas->map(function ($datas) {
		$datas->delivery_month=date('M',strtotime($datas->ship_date));
		$datas->delivery_month_year=date('Y-m',strtotime($datas->ship_date));
		return $datas;
		})->groupBy('delivery_month_year');
        $styles=array();
        $totQty=0;
		$totAmt= 0;
        
		$i=3;
		$j=0;
		foreach($rows as $months){
			foreach($months as $row){
				$style['id']=	$row->id;
				$style['receivedate']=	$row->receive_date;
				$style['style_ref']=	$row->style_ref;
				$style['flie_src']=	$row->flie_src;
				$style['buyer']=	$row->buyer_name;
				$style['season']=	$row->season_name;
				$style['season_id']=	$row->season_id;
				$style['uom_name']=	$row->uom_name;
				$style['uom_id']=	$row->uom_id;
				$style['team']=	$row->team_name;
				$style['teammember']=	$row->team_member_name;
				$style['productdepartment']=$row->department_name;
				$style['company_code']=$row->company_code;
				$style['sale_order_no']=$row->sale_order_no;
				$style['sale_order_receive_date']=date('d-M-Y',strtotime($row->sale_order_receive_date));
				$style['internal_ref']=$row->internal_ref;
				$style['delivery_date']=date('d-M-Y',strtotime($row->ship_date));
				$style['delivery_month']=date('M',strtotime($row->ship_date));
				$style['item_description']=$row->item_description;
				$style['item_complexity']=$itemcomplexity[$row->item_complexity];
				$style['qty']=number_format($row->qty,'0','.',',');
				$style['rate']=number_format($row->rate,'4','.',',');
				$style['amount']=number_format($row->amount,'2','.',',');
				$style['trim_amount']=number_format($row->trim_amount,'2','.',',');
				$style['kniting_amount']=number_format($row->kniting_amount,'2','.',',');
				$style['yarn_dying_amount']=number_format($row->yarn_dying_amount,'2','.',',');
				//$style['weaving_amount']=number_format($row->weaving_amount,'2','.',',');
				$style['dying_amount']=number_format($row->dying_amount+$row->finishing_amount,'2','.',',');
				$style['aop_amount']=number_format($row->aop_amount,'2','.',',');
				$style['burn_out_amount']=number_format($row->burn_out_amount,'2','.',',');
				//$style['finishing_amount']=number_format($row->finishing_amount,'2','.',',');
		        $style['washing_amount']=number_format($row->washing_amount,'2','.',',');
		        $style['yarn_amount']=number_format($row->yarn_amount,'2','.',',');
		        $style['printing_amount']=number_format($row->printing_amount,'2','.',',');
		        $style['emb_amount']=number_format($row->emb_amount,'2','.',',');
		        $style['spemb_amount']=number_format($row->spemb_amount,'2','.',',');
		        $style['gmt_dyeing_amount']=number_format($row->gmt_dyeing_amount,'2','.',',');
		        $style['gmt_washing_amount']=number_format($row->gmt_washing_amount,'2','.',',');

		        $courier_amount=number_format(($row->courier_rate/12)*$row->qty,'2','.','');
		        $lab_amount=number_format(($row->lab_rate/12)*$row->qty,'2','.','');
		        $insp_amount=number_format(($row->insp_rate/12)*$row->qty,'2','.','');
		        $opa_amount=number_format(($row->opa_rate/12)*$row->qty,'2','.','');
		        $dep_amount=number_format(($row->dep_rate/12)*$row->qty,'2','.','');
		        $coc_amount=number_format(($row->coc_rate/12)*$row->qty,'2','.','');
		        $ict_amount=number_format(($row->ict_rate/12)*$row->qty,'2','.','');
		        $other_amount=$courier_amount+$lab_amount+$insp_amount+$opa_amount+$dep_amount+ $coc_amount+ $ict_amount;

		        $style['courier_amount']=number_format($other_amount,'2','.',',');
		        $freight_amount=number_format(($row->freight_rate/12)*$row->qty,'2','.','');
		        $style['freight_amount']=number_format($freight_amount,'2','.',',');
		        $cm_amount=number_format(($row->cm_rate*$row->qty),'2','.','');
		        $style['cm_amount']=number_format($cm_amount,'2','.',',');
		        $commi_amount=number_format(($row->commi_rate/100)*$row->amount,'2','.','');
		        $style['commi_amount']=number_format($commi_amount,'2','.',',');
		        $commmercial=$row->trim_amount+$row->kniting_amount+$row->yarn_dying_amount+$row->yarn_dying_amount+$row->weaving_amount+$row->dying_amount+$row->aop_amount+$row->burn_out_amount+$row->finishing_amount+$row->washing_amount+$row->yarn_amount+$row->printing_amount+$row->emb_amount+$row->spemb_amount+$row->gmt_dyeing_amount+$row->gmt_washing_amount;
		        $commer_amount=number_format(($row->commer_rate/100)*$commmercial,'2','.','');
		        $style['commer_amount']=number_format($commer_amount,'2','.',',');
		        $total_amount=$commmercial+$other_amount+$freight_amount+$cm_amount+$commi_amount+$commer_amount;
		        $style['total_amount']=number_format($total_amount,'2','.',',');
		        $total_profit=number_format($row->amount-$total_amount,'2','.','');
		        $style['total_profit']=number_format($total_profit,'2','.',',');
		        $ordAmount=1;
		        if($row->amount){
		          $ordAmount=$row->amount;
		        }
		        $style['total_profit_per']=number_format((($total_profit/$ordAmount)*100),'2','.',',');
				$totQty+=$row->qty;
				$totAmt+= $row->amount;
				array_push($styles,$style);
			}
			if($j==0){
				$min_date=$months->min('ship_date');
			}
			$j++;
			if($j==$i){
				$max_date=$months->max('ship_date');
				$subTot = collect(['company_code'=>'Sub Total','qty'=>number_format(20000,4,'.',','),'rate'=>number_format(2,4,'.',','),'amount'=>number_format(20000,4,'.',','),'yarn_amount'=>number_format(20000,4,'.',','),'trim_amount'=>number_format(20000,4,'.',','),'min_date'=>$min_date,'max_date'=>$max_date]);
				array_push($styles,$subTot);
				$j=0;
			}
		}
		echo json_encode($styles);
    }

    public function formatOne(){
    	$rows=$this->reportData();
        $itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
        $styles=array();
    	$totQty=0;
		$totAmt= 0;
		foreach($rows as $row){
			$style['id']=	$row->id;
			$style['receivedate']=	$row->receive_date;
			$style['style_ref']=	$row->style_ref;
			$style['flie_src']=	$row->flie_src;
			$style['buyer']=	$row->buyer_name;
			$style['season']=	$row->season_name;
			$style['season_id']=	$row->season_id;
			$style['uom_name']=	$row->uom_name;
			
			$style['uom_id']=	$row->uom_id;
			$style['team']=	$row->team_name;
			$style['teammember']=	$row->team_member_name;
			$style['productdepartment']=$row->department_name;
			$style['company_code']=$row->company_code;
			$style['sale_order_no']=$row->sale_order_no;
			$style['sale_order_receive_date']=date('d-M-Y',strtotime($row->sale_order_receive_date));
			$style['internal_ref']=$row->internal_ref;
			$style['delivery_date']=date('d-M-Y',strtotime($row->ship_date));
			$style['delivery_month']=date('M',strtotime($row->ship_date));
			$style['item_description']=$row->item_description;
			$style['item_complexity']=$itemcomplexity[$row->item_complexity];
			$style['qty']=number_format($row->qty,'0','.',',');
			$style['rate']=number_format($row->rate,'4','.',',');
			$style['amount']=number_format($row->amount,'2','.',',');
			$style['trim_amount']=number_format($row->trim_amount,'2','.',',');
			$style['kniting_amount']=number_format($row->kniting_amount,'2','.',',');
			$style['yarn_dying_amount']=number_format($row->yarn_dying_amount,'2','.',',');
			//$style['weaving_amount']=number_format($row->weaving_amount,'2','.',',');
			$style['dying_amount']=number_format($row->dying_amount+$row->finishing_amount,'2','.',',');
			$style['aop_amount']=number_format($row->aop_amount,'2','.',',');
			$style['burn_out_amount']=number_format($row->burn_out_amount,'2','.',',');
			//$style['finishing_amount']=number_format($row->finishing_amount,'2','.',',');
	        $style['washing_amount']=number_format($row->washing_amount,'2','.',',');
	        $style['yarn_amount']=number_format($row->yarn_amount,'2','.',',');
	        $style['printing_amount']=number_format($row->printing_amount,'2','.',',');
	        $style['emb_amount']=number_format($row->emb_amount,'2','.',',');
	        $style['spemb_amount']=number_format($row->spemb_amount,'2','.',',');
	        $style['gmt_dyeing_amount']=number_format($row->gmt_dyeing_amount,'2','.',',');
	        $style['gmt_washing_amount']=number_format($row->gmt_washing_amount,'2','.',',');

	        $courier_amount=number_format(($row->courier_rate/12)*$row->qty,'2','.','');
	        $lab_amount=number_format(($row->lab_rate/12)*$row->qty,'2','.','');
	        $insp_amount=number_format(($row->insp_rate/12)*$row->qty,'2','.','');
	        $opa_amount=number_format(($row->opa_rate/12)*$row->qty,'2','.','');
	        $dep_amount=number_format(($row->dep_rate/12)*$row->qty,'2','.','');
	        $coc_amount=number_format(($row->coc_rate/12)*$row->qty,'2','.','');
	        $ict_amount=number_format(($row->ict_rate/12)*$row->qty,'2','.','');
	        $other_amount=$courier_amount+$lab_amount+$insp_amount+$opa_amount+$dep_amount+ $coc_amount+ $ict_amount;

	        $style['courier_amount']=number_format($other_amount,'2','.',',');
	        $freight_amount=number_format(($row->freight_rate/12)*$row->qty,'2','.','');
	        $style['freight_amount']=number_format($freight_amount,'2','.',',');
	        $cm_amount=number_format(($row->cm_rate*$row->qty),'2','.','');
	        $style['cm_amount']=number_format($cm_amount,'2','.',',');
	        $commi_amount=number_format(($row->commi_rate/100)*$row->amount,'2','.','');
	        $style['commi_amount']=number_format($commi_amount,'2','.',',');
	        $commmercial=$row->trim_amount+$row->kniting_amount+$row->yarn_dying_amount+$row->yarn_dying_amount+$row->weaving_amount+$row->dying_amount+$row->aop_amount+$row->burn_out_amount+$row->finishing_amount+$row->washing_amount+$row->yarn_amount+$row->printing_amount+$row->emb_amount+$row->spemb_amount+$row->gmt_dyeing_amount+$row->gmt_washing_amount;
	        $commer_amount=number_format(($row->commer_rate/100)*$commmercial,'2','.','');
	        $style['commer_amount']=number_format($commer_amount,'2','.',',');
	        $total_amount=$commmercial+$other_amount+$freight_amount+$cm_amount+$commi_amount+$commer_amount;
	        $style['total_amount']=number_format($total_amount,'2','.',',');
	        $total_profit=number_format($row->amount-$total_amount,'2','.','');
	        $style['total_profit']=number_format($total_profit,'2','.',',');
	        $ordAmount=1;
	        if($row->amount){
	          $ordAmount=$row->amount;
	        }
	        $style['total_profit_per']=number_format((($total_profit/$ordAmount)*100),'2','.',',');
			$totQty+=$row->qty;
			$totAmt+= $row->amount;
			array_push($styles,$style);
		}

		$dd=array('total'=>1,'rows'=>$styles,'footer'=>array(0=>array('company_code'=>'','internal_ref'=>'','teammember'=>'','delivery_date'=>'','delivery_month'=>'','buyer'=>'','style_ref'=>'','sale_order_no'=>'','sale_order_receive_date'=>'','productdepartment'=>'','item_description'=>'','item_complexity'=>'','flie_src'=>'','qty'=>number_format($totQty,0,'.',','),'rate'=>'','amount'=>number_format($totAmt,4,'.',','))));
		echo json_encode($styles);

    }

    public function reportData() {
		
		$rows=$this->style
		->selectRaw(
		'styles.style_ref,
		styles.flie_src,
		buyers.name as buyer_name,
		uoms.code as uom_name,
		seasons.name as season_name,
		teams.name as team_name,
		users.name as team_member_name,
		productdepartments.department_name,
		jobs.job_no,
		companies.id as company_id,
		companies.code as company_code,
		sales_orders.id,
		sales_orders.sale_order_no,
		sales_orders.receive_date as sale_order_receive_date,
		sales_orders.internal_ref,
		sales_orders.ship_date,
		sum(sales_order_gmt_color_sizes.qty) as qty,
		avg(sales_order_gmt_color_sizes.rate) as rate,
		sum(sales_order_gmt_color_sizes.amount) as amount,
		budgetTrim.trim_amount,
		budgetKniting.kniting_amount,
		budgetYarnDyeing.yarn_dying_amount,
		budgetWeaving.weaving_amount,
		budgetDyeing.dying_amount,
		budgetAop.aop_amount,
		burnOut.burn_out_amount,
		budgetFabFinishing.finishing_amount,
		budgetFabWashing.washing_amount,
		budgetYarn.yarn_amount,
		budgetPrinting.printing_amount,
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
		'
		)
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
		->leftJoin('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('companies', function($join)  {
		$join->on('companies.id', '=', 'jobs.company_id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		$join->whereNull('sales_order_gmt_color_sizes.deleted_at');
		})
		->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.sale_order_id,sum(budget_trim_cons.amount) as trim_amount FROM budget_trim_cons right join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id   group by sales_order_gmt_color_sizes.sale_order_id) budgetTrim"), "budgetTrim.sale_order_id", "=", "sales_orders.id")
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

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as dying_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =20
		group by sales_orders.id,production_processes.production_area_id) budgetDyeing"), "budgetDyeing.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as aop_amount
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

		->leftJoin(\DB::raw('(select m.id as sale_order_id,sum(m.yarn_amount) as yarn_amount  from (select budget_yarns.id as budget_yarn_id ,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,sum(budget_fabric_cons.grey_fab) as grey_fab,sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount,SALES_ORDERS.ID as id  
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
			SALES_ORDERS.SALE_ORDER_NO) m group by m.id) budgetYarn'), "budgetYarn.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as printing_amount 
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

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['sales_orders.order_status','!=',2]])
		->groupBy([
		'styles.style_ref',
		'styles.flie_src',
		'buyers.name',
		'uoms.code',
		'seasons.name',
		'teams.name',
		'users.name',
		'productdepartments.department_name',
		'jobs.job_no',
		'companies.id',
		'companies.code',
		'sales_orders.id',
		'sales_orders.sale_order_no',
		'sales_orders.receive_date',
		'sales_orders.internal_ref',
		'sales_orders.ship_date',
		'budgetTrim.trim_amount',
		'budgetKniting.kniting_amount',
		'budgetYarnDyeing.yarn_dying_amount',
		'budgetWeaving.weaving_amount',
		'budgetDyeing.dying_amount',
		'budgetAop.aop_amount',
		'burnOut.burn_out_amount',
		'budgetFabFinishing.finishing_amount',
		'budgetFabWashing.washing_amount',
		'budgetYarn.yarn_amount',
		'budgetPrinting.printing_amount',
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
		->orderBy('sales_orders.ship_date')
		->get();
		return $rows;
		
    }

    public function getyarn()
    {
    	$company_id=request('company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$job_no=request('job_no', 0);

		$date_from=request('date_from', 0);
		$date_to=request('date_to', 0);

		$company=null;
		$buyer=null;
		$style=null;
		$job=null;
		$from=null;
		$to=null;
		if($company_id){
			$company=" and jobs.company_id=$company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like %$style_ref% ";
		}
		if($job_no){
			$job=" sales_orders.ship_date>='".$date_from."' and ";
		}
		if($date_from){
			$from=" and jobs.job_no like %$job_no% ";
		}
		if($date_to){
			$to=" sales_orders.ship_date<='".$date_to."' ";
		}

		 

    	$datas=array();

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
		->orderBY('item_accounts.id')
		->orderBY('item_account_ratios.id')
	    ->where([['itemcategories.identity','=',1]])
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
		$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
		$yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
		}
		$yarnDropdown=array();
		foreach($itemaccountArr as $key=>$value){
		$yarnDropdown[$key]=$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
		}

		$fabricDescription=$this->budget
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		$fabDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
			$fabDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}
		
    	if(request('sale_order_id',0))
    	{
			$results = \DB::select('select budget_yarns.id,budget_yarns.budget_fabric_id,budget_yarns.item_account_id,style_gmts.item_account_id as gmt_item_id,budget_yarns.cons,budget_yarns.rate,budget_yarns.ratio,budget_yarns.amount,style_fabrications.id as style_fabrication_id,gmtsparts.name as gmt_part_name,item_accounts.item_description as gmt_item_description,sum(budget_fabric_cons.grey_fab) as grey_fab,sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,sum(((budget_fabric_cons.req_cons*budget_yarns.ratio)/100)) as req_cons,(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount   from budget_yarns 
				join budget_fabrics on budget_fabrics.id=budget_yarns.budget_fabric_id 
				join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
				join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
				join item_accounts on item_accounts.id=style_gmts.item_account_id
				join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id  
				join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id   
				join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id 
				where sales_orders.id=? 
				group by budget_yarns.id,budget_yarns.budget_fabric_id,budget_yarns.item_account_id,style_gmts.item_account_id,budget_yarns.ratio,budget_yarns.cons,budget_yarns.rate,budget_yarns.amount,style_fabrications.id,gmtsparts.name,item_accounts.item_description,style_gmts.id,gmtsparts.id order by style_gmts.id,gmtsparts.id,budget_yarns.id', [request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
	            $result->yarn_des=$yarnDropdown[$result->item_account_id];
	            $result->fab_des=$fabDropdown[$result->style_fabrication_id];
	            $result->yarn_amount=number_format($result->yarn_amount,'4','.',',');
	            $result->cons=number_format($result->yarn,'4','.',',');
	            $result->req_cons=number_format($result->req_cons,'4','.',',');
	            array_push($datas,$result);
			}

    	}
    	else
    	{
		$rows=$this->style
		->selectRaw(
		'budget_yarns.item_account_id,budgetYarn.yarn_qty,budgetYarn.yarn_amount,yarncounts.count'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_fabric_cons', function($join)  {
		$join->on('budget_fabric_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
		})
		->leftJoin('budget_yarns', function($join)  {
		$join->on('budget_yarns.budget_fabric_id', '=', 'budget_fabric_cons.budget_fabric_id');
		})
		->join('item_accounts',function($join){
		$join->on('item_accounts.id','=','budget_yarns.item_account_id');
		})
		->join('yarncounts',function($join){
		$join->on('yarncounts.id','=','item_accounts.yarncount_id');
		})

		->leftJoin(\DB::raw("(select m.item_account_id,sum(m.yarn) as yarn_qty,sum(m.yarn_amount) as yarn_amount  from 
			(
				select budget_yarns.id as budget_yarn_id, 
				budget_yarns.item_account_id,
				budget_yarns.ratio,
				budget_yarns.cons,
				budget_yarns.rate,
				budget_yarns.amount,
				sales_orders.id as id, 
				sum(budget_fabric_cons.grey_fab) as grey_fab,
				sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,
				(sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount
				 
				from budget_yarns 
				join budget_fabric_cons on budget_yarns.budget_fabric_id = budget_fabric_cons.budget_fabric_id 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id 
				join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id 
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				join budgets on budgets.style_id=styles.id
				where 1=1  $from $to $company $buyer $style $job
				group by 
				budget_yarns.id,
				budget_yarns.item_account_id,
				budget_yarns.ratio,
				budget_yarns.cons,
				budget_yarns.rate,
				budget_yarns.amount,
				sales_orders.id,
				sales_orders.sale_order_no
		) m group by m.item_account_id) budgetYarn"), "budgetYarn.item_account_id", "=", "budget_yarns.item_account_id")
		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->groupBy([
		'budget_yarns.item_account_id', 
		'budgetYarn.yarn_qty',
		'budgetYarn.yarn_amount',
		'yarncounts.count'
		])
		->orderBy('yarncounts.count')
		->get()
		->groupBy('count');
		foreach($rows as $count){
			$subQty=0;
			$subAmount=0;
			foreach($count as $result){
				if($result->yarn_qty)
				{
					$subQty+=$result->yarn_qty;
			        $subAmount+=$result->yarn_amount;
					$result->yarn_des=$yarnDropdown[$result->item_account_id];
					$result->yarn_amount=number_format($result->yarn_amount,'4','.',',');
					$result->yarn_qty=number_format($result->yarn_qty,'4','.',',');
					$result->rate=number_format($result->yarn_amount/$result->yarn_qty,'4','.',',');
					
					array_push($datas,$result);
				}
			}
            $rate=number_format($subAmount/$subQty,'4','.',',');
			$subTot = collect(['yarn_des'=>'Sub Total','yarn_amount'=>number_format($subAmount,'4','.',','),'yarn_qty'=>number_format($subQty,'4','.',','),'rate'=>$rate]);
			array_push($datas,$subTot);
			
		}
    	}

    	$dd=array('total'=>1,'rows'=>$datas,'footer'=>array(0=>array('yarn_des'=>'','yarn_qty'=>'','yarn_amount'=>'','rate'=>'')));

    	echo json_encode($dd);
    }

    public function gettrim()
    {
    	$datas=array();
		if(request('sale_order_id',0))
		{
			$results = \DB::select('select itemclasses.name,budget_trims.description,budget_trim_cons.budget_trim_id,sales_order_gmt_color_sizes.sale_order_id,uoms.code,sum(budget_trim_cons.bom_trim) as bom_trim, avg(budget_trim_cons.rate) as rate, sum(budget_trim_cons.amount) as trim_amount from budget_trim_cons 
			join budget_trims on budget_trims.id=budget_trim_cons.budget_trim_id 
			left join itemclasses on itemclasses.id=budget_trims.itemclass_id  
			left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id 
			left join uoms on uoms.id=budget_trims.uom_id
			where sales_order_gmt_color_sizes.sale_order_id=?    
			group by sales_order_gmt_color_sizes.sale_order_id,uoms.code,budget_trim_cons.budget_trim_id,itemclasses.name,budget_trims.description', [request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->bom_trim=number_format($result->bom_trim,'2','.',',');
				$result->trim_amount=number_format($result->trim_amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		'
		itemclasses.id,
		itemclasses.name,
		budget_trims.description,
		uoms.id,
		uoms.code,
		sum(budget_trim_cons.bom_trim) as bom_trim, 
		avg(budget_trim_cons.rate) as rate, 
		sum(budget_trim_cons.amount) as trim_amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_trim_cons', function($join)  {
		$join->on('budget_trim_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
		})
		->leftJoin('budget_trims', function($join)  {
		$join->on('budget_trims.id', '=', 'budget_trim_cons.budget_trim_id');
		})
		->join('uoms',function($join){
		$join->on('uoms.id','=','budget_trims.uom_id');
		})
		->join('itemclasses',function($join){
		$join->on('itemclasses.id','=','budget_trims.itemclass_id');
		})
		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->groupBy([
		'itemclasses.id',
		'itemclasses.name',
		'budget_trims.description',
		'uoms.id',
		'uoms.code'
		])
		->orderBy('itemclasses.name')
		->get();
		foreach($results as $result)
			{
				$result->bom_trim=number_format($result->bom_trim,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->trim_amount=number_format($result->trim_amount,'2','.',',');
				array_push($datas,$result);
			}
		}
    	echo json_encode($datas);
    }


    public function getknit()
    {
    	$datas=array();
    	$fabricDescription=$this->budget
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		$fabDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
			$fabDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select budget_fabric_prods.id as budget_fabric_prod_id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id as style_fabrication_id,

			gmtsparts.name as gmt_part_name,
			item_accounts.item_description as gmt_item_description,
			sum(budget_fabric_prod_cons.bom_qty) as kniting_qty,
			sum(budget_fabric_prod_cons.rate) as rate,
			sum(budget_fabric_prod_cons.amount) as kniting_amount
			from budget_fabric_prod_cons 
			left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
			left join budget_fabric_prods on budget_fabric_prods.id = budget_fabric_prod_cons.budget_fabric_prod_id 
			left join production_processes on production_processes.id = budget_fabric_prods.production_process_id
			left join  budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
			left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
			left join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
			left join item_accounts on item_accounts.id=style_gmts.item_account_id
			left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id 
			where production_processes.production_area_id =? and sales_orders.id=?
			group by budget_fabric_prods.id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id ,
			gmtsparts.id,
			style_gmts.id,
			gmtsparts.name,
			item_accounts.item_description
			order by style_gmts.id,gmtsparts.id,budget_fabric_prods.id', [10,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->kniting_qty=number_format($result->kniting_qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->kniting_amount=number_format($result->kniting_amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		style_fabrications.id as style_fabrication_id,
		sum(budget_fabric_prod_cons.bom_qty) as kniting_qty,
		sum(budget_fabric_prod_cons.rate) as rate,
		sum(budget_fabric_prod_cons.amount) as kniting_amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		
		->leftJoin('budget_fabric_prod_cons', function($join)  {
		$join->on('budget_fabric_prod_cons.sales_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_fabric_prods', function($join)  {
		$join->on('budget_fabric_prods.id', '=', 'budget_fabric_prod_cons.budget_fabric_prod_id');
		})
		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'budget_fabric_prods.production_process_id');
		})

		
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
		})
		->leftJoin('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',10]])
		
		->groupBy([
		'style_fabrications.id'
		])
		->get();
		foreach($results as $result)
			{
				if($result->kniting_qty)
				{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->kniting_qty=number_format($result->kniting_qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->kniting_amount=number_format($result->kniting_amount,'2','.',',');
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }

    public function getyarndyeing()
    {
    	$datas=array();
    	$fabricDescription=$this->budget
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		$fabDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
			$fabDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select budget_fabric_prods.id as budget_fabric_prod_id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id as style_fabrication_id,

			gmtsparts.name as gmt_part_name,
			item_accounts.item_description as gmt_item_description,
			budget_fabric_prod_cons.fabric_color_id,
			colors.name as fabric_color,
			sum(budget_fabric_prod_cons.bom_qty) as yarn_dyeing_qty,
			sum(budget_fabric_prod_cons.rate) as rate,
			sum(budget_fabric_prod_cons.amount) as yarn_dyeing_amount
			from budget_fabric_prod_cons 
			left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
			left join budget_fabric_prods on budget_fabric_prods.id = budget_fabric_prod_cons.budget_fabric_prod_id 
			left join production_processes on production_processes.id = budget_fabric_prods.production_process_id
			left join  budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
			left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
			left join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
			left join item_accounts on item_accounts.id=style_gmts.item_account_id
			left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id 
			left join colors on colors.id=budget_fabric_prod_cons.fabric_color_id 
			where production_processes.production_area_id =? and sales_orders.id=?
			group by budget_fabric_prods.id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id ,
			gmtsparts.id,
			style_gmts.id,
			gmtsparts.name,
			item_accounts.item_description,
			budget_fabric_prod_cons.fabric_color_id,
			colors.name
			order by style_gmts.id,gmtsparts.id,budget_fabric_prods.id', [5,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->yarn_dyeing_qty=number_format($result->yarn_dyeing_qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->yarn_dyeing_amount=number_format($result->yarn_dyeing_amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		style_fabrications.id as style_fabrication_id,
		budget_fabric_prod_cons.fabric_color_id,
		colors.name as fabric_color,
		sum(budget_fabric_prod_cons.bom_qty) as yarn_dyeing_qty,
		sum(budget_fabric_prod_cons.rate) as rate,
		sum(budget_fabric_prod_cons.amount) as yarn_dyeing_amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		
		->leftJoin('budget_fabric_prod_cons', function($join)  {
		$join->on('budget_fabric_prod_cons.sales_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_fabric_prods', function($join)  {
		$join->on('budget_fabric_prods.id', '=', 'budget_fabric_prod_cons.budget_fabric_prod_id');
		})
		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'budget_fabric_prods.production_process_id');
		})

		
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
		})
		->leftJoin('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->leftJoin('colors',function($join){
		$join->on('colors.id','=','budget_fabric_prod_cons.fabric_color_id');
		})

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',5]])
		
		->groupBy([
		'style_fabrications.id',
		'budget_fabric_prod_cons.fabric_color_id',
		'colors.name'
		])
		->get();
		foreach($results as $result)
			{
				if($result->yarn_dyeing_qty)
				{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->yarn_dyeing_qty=number_format($result->yarn_dyeing_qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->yarn_dyeing_amount=number_format($result->yarn_dyeing_amount,'2','.',',');
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }


    public function getdyeing()
    {
    	$datas=array();
    	$fabricDescription=$this->budget
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		$fabDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
			$fabDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}
		 $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
		 $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		 $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select budget_fabric_prods.id as budget_fabric_prod_id,
			budget_fabric_prods.budget_fabric_id,
			budget_fabric_prods.production_process_id,
			production_processes.process_name,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.dyeing_type_id,

			gmtsparts.name as gmt_part_name,
			item_accounts.item_description as gmt_item_description,
			budget_fabric_prod_cons.fabric_color_id,
			colors.name as fabric_color,
			sum(budget_fabric_prod_cons.bom_qty) as qty,
			sum(budget_fabric_prod_cons.rate) as rate,
			sum(budget_fabric_prod_cons.amount) as amount
			from budget_fabric_prod_cons 
			left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
			left join budget_fabric_prods on budget_fabric_prods.id = budget_fabric_prod_cons.budget_fabric_prod_id 
			left join production_processes on production_processes.id = budget_fabric_prods.production_process_id
			left join  budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
			left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
			left join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
			left join item_accounts on item_accounts.id=style_gmts.item_account_id
			left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id 
			left join colors on colors.id=budget_fabric_prod_cons.fabric_color_id 
			where production_processes.production_area_id in(20,30) and sales_orders.id=?
			group by budget_fabric_prods.id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id ,
			gmtsparts.id,
			style_gmts.id,
			gmtsparts.name,
			item_accounts.item_description,
			budget_fabric_prod_cons.fabric_color_id,
			colors.name,
			budget_fabric_prods.production_process_id,
			production_processes.process_name,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.dyeing_type_id
			order by style_gmts.id,gmtsparts.id,budget_fabric_prods.id', [request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->shape=$fabricshape[$result->fabric_shape_id];
				$result->look=$fabriclooks[$result->fabric_look_id];
				$result->dyetype=$dyetype[$result->dyeing_type_id];
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		style_fabrications.id as style_fabrication_id,
		budget_fabric_prod_cons.fabric_color_id,
		colors.name as fabric_color,
		budget_fabric_prods.production_process_id,
		production_processes.process_name,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		style_fabrications.dyeing_type_id,
		sum(budget_fabric_prod_cons.bom_qty) as qty,
		sum(budget_fabric_prod_cons.rate) as rate,
		sum(budget_fabric_prod_cons.amount) as amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		
		->leftJoin('budget_fabric_prod_cons', function($join)  {
		$join->on('budget_fabric_prod_cons.sales_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_fabric_prods', function($join)  {
		$join->on('budget_fabric_prods.id', '=', 'budget_fabric_prod_cons.budget_fabric_prod_id');
		})
		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'budget_fabric_prods.production_process_id');
		})

		
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
		})
		->leftJoin('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->leftJoin('colors',function($join){
		$join->on('colors.id','=','budget_fabric_prod_cons.fabric_color_id');
		})

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->whereIn('production_processes.production_area_id',[20,30])
		
		->groupBy([
		'style_fabrications.id',
		'budget_fabric_prod_cons.fabric_color_id',
		'colors.name',
		'budget_fabric_prods.production_process_id',
		'production_processes.process_name',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'style_fabrications.dyeing_type_id'
		])
		->get();
		foreach($results as $result)
			{
				if($result->qty)
				{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->shape=$fabricshape[$result->fabric_shape_id];
				$result->look=$fabriclooks[$result->fabric_look_id];
				$result->dyetype=$dyetype[$result->dyeing_type_id];
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }


    public function getaop()
    {
    	$datas=array();
    	$fabricDescription=$this->budget
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		$fabDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
			$fabDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}
		 $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'-Select-','');
		 $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		 $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select budget_fabric_prods.id as budget_fabric_prod_id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.embelishment_type_id,
			style_fabrications.coverage, 
			style_fabrications.impression,

			gmtsparts.name as gmt_part_name,
			item_accounts.item_description as gmt_item_description,
			
			sum(budget_fabric_prod_cons.bom_qty) as qty,
			sum(budget_fabric_prod_cons.rate) as rate,
			sum(budget_fabric_prod_cons.amount) as amount
			from budget_fabric_prod_cons 
			left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
			left join budget_fabric_prods on budget_fabric_prods.id = budget_fabric_prod_cons.budget_fabric_prod_id 
			left join production_processes on production_processes.id = budget_fabric_prods.production_process_id
			left join  budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
			left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
			left join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
			left join item_accounts on item_accounts.id=style_gmts.item_account_id
			left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id 
			
			where production_processes.production_area_id =? and sales_orders.id=?
			group by budget_fabric_prods.id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id ,
			gmtsparts.id,
			style_gmts.id,
			gmtsparts.name,
			item_accounts.item_description,
			style_fabrications.coverage, 
			style_fabrications.impression,
			
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.embelishment_type_id
			order by style_gmts.id,gmtsparts.id,budget_fabric_prods.id', [25,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->shape=$fabricshape[$result->fabric_shape_id];
				$result->look=$fabriclooks[$result->fabric_look_id];
				$result->aoptype=$embelishmenttype[$result->embelishment_type_id];
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		style_fabrications.id as style_fabrication_id,
		style_fabrications.coverage, 
		style_fabrications.impression,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		style_fabrications.embelishment_type_id,
		sum(budget_fabric_prod_cons.bom_qty) as qty,
		sum(budget_fabric_prod_cons.rate) as rate,
		sum(budget_fabric_prod_cons.amount) as amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		
		->leftJoin('budget_fabric_prod_cons', function($join)  {
		$join->on('budget_fabric_prod_cons.sales_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_fabric_prods', function($join)  {
		$join->on('budget_fabric_prods.id', '=', 'budget_fabric_prod_cons.budget_fabric_prod_id');
		})
		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'budget_fabric_prods.production_process_id');
		})

		
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
		})
		->leftJoin('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->leftJoin('colors',function($join){
		$join->on('colors.id','=','budget_fabric_prod_cons.fabric_color_id');
		})

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',25]])
		
		->groupBy([
		'style_fabrications.id',
		'style_fabrications.coverage', 
		'style_fabrications.impression',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'style_fabrications.embelishment_type_id'
		])
		->get();
		foreach($results as $result)
			{
				if($result->qty)
				{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->shape=$fabricshape[$result->fabric_shape_id];
				$result->look=$fabriclooks[$result->fabric_look_id];
				$result->aoptype=$embelishmenttype[$result->embelishment_type_id];
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }


    public function getboc()
    {
    	$datas=array();
    	$fabricDescription=$this->budget
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		$fabDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
			$fabDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}
		 //$embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'-Select-','');
		 $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		 $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select budget_fabric_prods.id as budget_fabric_prod_id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			

			gmtsparts.name as gmt_part_name,
			item_accounts.item_description as gmt_item_description,
			
			sum(budget_fabric_prod_cons.bom_qty) as qty,
			sum(budget_fabric_prod_cons.rate) as rate,
			sum(budget_fabric_prod_cons.amount) as amount
			from budget_fabric_prod_cons 
			left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
			left join budget_fabric_prods on budget_fabric_prods.id = budget_fabric_prod_cons.budget_fabric_prod_id 
			left join production_processes on production_processes.id = budget_fabric_prods.production_process_id
			left join  budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
			left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
			left join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
			left join item_accounts on item_accounts.id=style_gmts.item_account_id
			left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id 
			
			where production_processes.production_area_id =? and sales_orders.id=?
			group by budget_fabric_prods.id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id ,
			gmtsparts.id,
			style_gmts.id,
			gmtsparts.name,
			item_accounts.item_description,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id
			order by style_gmts.id,gmtsparts.id,budget_fabric_prods.id', [28,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->shape=$fabricshape[$result->fabric_shape_id];
				$result->look=$fabriclooks[$result->fabric_look_id];
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		style_fabrications.id as style_fabrication_id,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		sum(budget_fabric_prod_cons.bom_qty) as qty,
		sum(budget_fabric_prod_cons.rate) as rate,
		sum(budget_fabric_prod_cons.amount) as amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		
		->leftJoin('budget_fabric_prod_cons', function($join)  {
		$join->on('budget_fabric_prod_cons.sales_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_fabric_prods', function($join)  {
		$join->on('budget_fabric_prods.id', '=', 'budget_fabric_prod_cons.budget_fabric_prod_id');
		})
		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'budget_fabric_prods.production_process_id');
		})

		
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
		})
		->leftJoin('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->leftJoin('colors',function($join){
		$join->on('colors.id','=','budget_fabric_prod_cons.fabric_color_id');
		})

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',28]])
		->groupBy([
		'style_fabrications.id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		
		])
		->get();
		foreach($results as $result)
			{
				if($result->qty)
				{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->shape=$fabricshape[$result->fabric_shape_id];
				$result->look=$fabriclooks[$result->fabric_look_id];
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }


    public function getfwc()
    {
    	$datas=array();
    	$fabricDescription=$this->budget
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		$fabDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
			$fabDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}
		 //$embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'-Select-','');
		 $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		 $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select budget_fabric_prods.id as budget_fabric_prod_id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			

			gmtsparts.name as gmt_part_name,
			item_accounts.item_description as gmt_item_description,
			budget_fabric_prod_cons.fabric_color_id,
			colors.name as fabric_color,
			
			sum(budget_fabric_prod_cons.bom_qty) as qty,
			sum(budget_fabric_prod_cons.rate) as rate,
			sum(budget_fabric_prod_cons.amount) as amount
			from budget_fabric_prod_cons 
			left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
			left join budget_fabric_prods on budget_fabric_prods.id = budget_fabric_prod_cons.budget_fabric_prod_id 
			left join production_processes on production_processes.id = budget_fabric_prods.production_process_id
			left join  budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
			left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
			left join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
			left join item_accounts on item_accounts.id=style_gmts.item_account_id
			left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id 
			left join colors on colors.id=budget_fabric_prod_cons.fabric_color_id 
			where production_processes.production_area_id =? and sales_orders.id=?
			group by budget_fabric_prods.id,
			budget_fabric_prods.budget_fabric_id,
			style_fabrications.id ,
			gmtsparts.id,
			style_gmts.id,
			gmtsparts.name,
			item_accounts.item_description,
			budget_fabric_prod_cons.fabric_color_id,
			colors.name,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id
			order by style_gmts.id,gmtsparts.id,budget_fabric_prods.id', [35,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->shape=$fabricshape[$result->fabric_shape_id];
				$result->look=$fabriclooks[$result->fabric_look_id];
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		style_fabrications.id as style_fabrication_id,
		budget_fabric_prod_cons.fabric_color_id,
		colors.name as fabric_color,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		sum(budget_fabric_prod_cons.bom_qty) as qty,
		sum(budget_fabric_prod_cons.rate) as rate,
		sum(budget_fabric_prod_cons.amount) as amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		
		->leftJoin('budget_fabric_prod_cons', function($join)  {
		$join->on('budget_fabric_prod_cons.sales_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_fabric_prods', function($join)  {
		$join->on('budget_fabric_prods.id', '=', 'budget_fabric_prod_cons.budget_fabric_prod_id');
		})
		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'budget_fabric_prods.production_process_id');
		})

		
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
		})
		->leftJoin('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->leftJoin('colors',function($join){
		$join->on('colors.id','=','budget_fabric_prod_cons.fabric_color_id');
		})
		

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',35]])
		->groupBy([
		'style_fabrications.id',
		'budget_fabric_prod_cons.fabric_color_id',
		'colors.name',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id'
		
		])
		->get();
		foreach($results as $result)
			{
				if($result->qty)
				{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->shape=$fabricshape[$result->fabric_shape_id];
				$result->look=$fabriclooks[$result->fabric_look_id];
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }


    public function getgpc()
    {
    	$datas=array();
		 
		 $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select item_accounts.item_description as gmt_item,embelishment_types.name as emb_type,gmtsparts.name as gmt_part_name,style_embelishments.embelishment_size_id,sum(budget_emb_cons.req_cons) as qty,avg(budget_emb_cons.rate) as rate, sum(budget_emb_cons.amount) as amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		left join embelishment_types on embelishment_types.id=style_embelishments.embelishment_type_id
		left join style_gmts on style_gmts.id=style_embelishments.style_gmt_id
		left join item_accounts on item_accounts.id=style_gmts.item_account_id
		left join gmtsparts on gmtsparts.id=style_embelishments.gmtspart_id
		where production_processes.production_area_id = ? and sales_order_gmt_color_sizes.sale_order_id=?
		group by item_accounts.item_description,style_embelishments.embelishment_type_id,embelishment_types.name,gmtsparts.name,style_embelishments.embelishment_size_id,style_embelishments.gmtspart_id', [45,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->gmt_item=$result->gmt_item;
				$result->emb_type=$result->emb_type;
				$result->gmt_part_name=$result->gmt_part_name;
				$result->embelishment_size=$embelishmentsize[$result->embelishment_size_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		item_accounts.item_description as gmt_item,
		embelishment_types.name as emb_type,
		gmtsparts.name as gmt_part_name,
		style_embelishments.embelishment_size_id,
		sum(budget_emb_cons.req_cons) as qty,
		avg(budget_emb_cons.rate) as rate, 
		sum(budget_emb_cons.amount) as amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_emb_cons', function($join)  {
		$join->on('budget_emb_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
		})
		->leftJoin('budget_embs', function($join)  {
		$join->on('budget_embs.id', '=', 'budget_emb_cons.budget_emb_id');
		})
        ->leftJoin('style_embelishments', function($join)  {
		$join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
		})
		->leftJoin('embelishments', function($join)  {
		$join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
		})

		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'embelishments.production_process_id');
		})
		->leftJoin('embelishment_types', function($join)  {
		$join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
		})

		
		->leftJoin('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
		})
		->leftJoin('item_accounts',function($join){
		$join->on('item_accounts.id','=','style_gmts.item_account_id');
		})
		->leftJoin('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
		})
		

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',45]])
		->groupBy([
		'item_accounts.item_description',
		'embelishment_types.name',
		'gmtsparts.name',
		'style_embelishments.embelishment_size_id'
		])
		->get();
		foreach($results as $result)
			{
				if($result->qty)
				{
				$result->gmt_item=$result->gmt_item;
				$result->emb_type=$result->emb_type;
				$result->gmt_part_name=$result->gmt_part_name;
				$result->embelishment_size=$embelishmentsize[$result->embelishment_size_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }


    public function getgec()
    {
    	$datas=array();
		 
		 $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select item_accounts.item_description as gmt_item,embelishment_types.name as emb_type,gmtsparts.name as gmt_part_name,style_embelishments.embelishment_size_id,sum(budget_emb_cons.req_cons) as qty,avg(budget_emb_cons.rate) as rate, sum(budget_emb_cons.amount) as amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		left join embelishment_types on embelishment_types.id=style_embelishments.embelishment_type_id
		left join style_gmts on style_gmts.id=style_embelishments.style_gmt_id
		left join item_accounts on item_accounts.id=style_gmts.item_account_id
		left join gmtsparts on gmtsparts.id=style_embelishments.gmtspart_id
		where production_processes.production_area_id = ? and sales_order_gmt_color_sizes.sale_order_id=?
		group by item_accounts.item_description,style_embelishments.embelishment_type_id,embelishment_types.name,gmtsparts.name,style_embelishments.embelishment_size_id,style_embelishments.gmtspart_id', [50,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->gmt_item=$result->gmt_item;
				$result->emb_type=$result->emb_type;
				$result->gmt_part_name=$result->gmt_part_name;
				$result->embelishment_size=$embelishmentsize[$result->embelishment_size_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		item_accounts.item_description as gmt_item,
		embelishment_types.name as emb_type,
		gmtsparts.name as gmt_part_name,
		style_embelishments.embelishment_size_id,
		sum(budget_emb_cons.req_cons) as qty,
		avg(budget_emb_cons.rate) as rate, 
		sum(budget_emb_cons.amount) as amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_emb_cons', function($join)  {
		$join->on('budget_emb_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
		})
		->leftJoin('budget_embs', function($join)  {
		$join->on('budget_embs.id', '=', 'budget_emb_cons.budget_emb_id');
		})
        ->leftJoin('style_embelishments', function($join)  {
		$join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
		})
		->leftJoin('embelishments', function($join)  {
		$join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
		})

		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'embelishments.production_process_id');
		})
		->leftJoin('embelishment_types', function($join)  {
		$join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
		})

		
		->leftJoin('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
		})
		->leftJoin('item_accounts',function($join){
		$join->on('item_accounts.id','=','style_gmts.item_account_id');
		})
		->leftJoin('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
		})
		

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',50]])
		->groupBy([
		'item_accounts.item_description',
		'embelishment_types.name',
		'gmtsparts.name',
		'style_embelishments.embelishment_size_id'
		])
		->get();
		foreach($results as $result)
			{
				if($result->qty)
				{
				$result->gmt_item=$result->gmt_item;
				$result->emb_type=$result->emb_type;
				$result->gmt_part_name=$result->gmt_part_name;
				$result->embelishment_size=$embelishmentsize[$result->embelishment_size_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }


    public function getgsec()
    {
    	$datas=array();
		 
		 $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select item_accounts.item_description as gmt_item,embelishment_types.name as emb_type,gmtsparts.name as gmt_part_name,style_embelishments.embelishment_size_id,sum(budget_emb_cons.req_cons) as qty,avg(budget_emb_cons.rate) as rate, sum(budget_emb_cons.amount) as amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		left join embelishment_types on embelishment_types.id=style_embelishments.embelishment_type_id
		left join style_gmts on style_gmts.id=style_embelishments.style_gmt_id
		left join item_accounts on item_accounts.id=style_gmts.item_account_id
		left join gmtsparts on gmtsparts.id=style_embelishments.gmtspart_id
		where production_processes.production_area_id = ? and sales_order_gmt_color_sizes.sale_order_id=?
		group by item_accounts.item_description,style_embelishments.embelishment_type_id,embelishment_types.name,gmtsparts.name,style_embelishments.embelishment_size_id,style_embelishments.gmtspart_id', [51,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->gmt_item=$result->gmt_item;
				$result->emb_type=$result->emb_type;
				$result->gmt_part_name=$result->gmt_part_name;
				$result->embelishment_size=$embelishmentsize[$result->embelishment_size_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		item_accounts.item_description as gmt_item,
		embelishment_types.name as emb_type,
		gmtsparts.name as gmt_part_name,
		style_embelishments.embelishment_size_id,
		sum(budget_emb_cons.req_cons) as qty,
		avg(budget_emb_cons.rate) as rate, 
		sum(budget_emb_cons.amount) as amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_emb_cons', function($join)  {
		$join->on('budget_emb_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
		})
		->leftJoin('budget_embs', function($join)  {
		$join->on('budget_embs.id', '=', 'budget_emb_cons.budget_emb_id');
		})
        ->leftJoin('style_embelishments', function($join)  {
		$join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
		})
		->leftJoin('embelishments', function($join)  {
		$join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
		})

		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'embelishments.production_process_id');
		})
		->leftJoin('embelishment_types', function($join)  {
		$join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
		})

		
		->leftJoin('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
		})
		->leftJoin('item_accounts',function($join){
		$join->on('item_accounts.id','=','style_gmts.item_account_id');
		})
		->leftJoin('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
		})
		

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',51]])
		->groupBy([
		'item_accounts.item_description',
		'embelishment_types.name',
		'gmtsparts.name',
		'style_embelishments.embelishment_size_id'
		])
		->get();
		foreach($results as $result)
			{
				if($result->qty)
				{
				$result->gmt_item=$result->gmt_item;
				$result->emb_type=$result->emb_type;
				$result->gmt_part_name=$result->gmt_part_name;
				$result->embelishment_size=$embelishmentsize[$result->embelishment_size_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }


    public function getgdc()
    {
    	$datas=array();
		 
		 $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select item_accounts.item_description as gmt_item,embelishment_types.name as emb_type,gmtsparts.name as gmt_part_name,style_embelishments.embelishment_size_id,sum(budget_emb_cons.req_cons) as qty,avg(budget_emb_cons.rate) as rate, sum(budget_emb_cons.amount) as amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		left join embelishment_types on embelishment_types.id=style_embelishments.embelishment_type_id
		left join style_gmts on style_gmts.id=style_embelishments.style_gmt_id
		left join item_accounts on item_accounts.id=style_gmts.item_account_id
		left join gmtsparts on gmtsparts.id=style_embelishments.gmtspart_id
		where production_processes.production_area_id = ? and sales_order_gmt_color_sizes.sale_order_id=?
		group by item_accounts.item_description,style_embelishments.embelishment_type_id,embelishment_types.name,gmtsparts.name,style_embelishments.embelishment_size_id,style_embelishments.gmtspart_id', [58,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->gmt_item=$result->gmt_item;
				$result->emb_type=$result->emb_type;
				$result->gmt_part_name=$result->gmt_part_name;
				$result->embelishment_size=$embelishmentsize[$result->embelishment_size_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		item_accounts.item_description as gmt_item,
		embelishment_types.name as emb_type,
		gmtsparts.name as gmt_part_name,
		style_embelishments.embelishment_size_id,
		sum(budget_emb_cons.req_cons) as qty,
		avg(budget_emb_cons.rate) as rate, 
		sum(budget_emb_cons.amount) as amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_emb_cons', function($join)  {
		$join->on('budget_emb_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
		})
		->leftJoin('budget_embs', function($join)  {
		$join->on('budget_embs.id', '=', 'budget_emb_cons.budget_emb_id');
		})
        ->leftJoin('style_embelishments', function($join)  {
		$join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
		})
		->leftJoin('embelishments', function($join)  {
		$join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
		})

		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'embelishments.production_process_id');
		})
		->leftJoin('embelishment_types', function($join)  {
		$join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
		})

		
		->leftJoin('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
		})
		->leftJoin('item_accounts',function($join){
		$join->on('item_accounts.id','=','style_gmts.item_account_id');
		})
		->leftJoin('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
		})
		

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',58]])
		->groupBy([
		'item_accounts.item_description',
		'embelishment_types.name',
		'gmtsparts.name',
		'style_embelishments.embelishment_size_id'
		])
		->get();
		foreach($results as $result)
			{
				if($result->qty)
				{
				$result->gmt_item=$result->gmt_item;
				$result->emb_type=$result->emb_type;
				$result->gmt_part_name=$result->gmt_part_name;
				$result->embelishment_size=$embelishmentsize[$result->embelishment_size_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }

    public function getgwc()
    {
    	$datas=array();
		 
		 $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select item_accounts.item_description as gmt_item,embelishment_types.name as emb_type,gmtsparts.name as gmt_part_name,style_embelishments.embelishment_size_id,sum(budget_emb_cons.req_cons) as qty,avg(budget_emb_cons.rate) as rate, sum(budget_emb_cons.amount) as amount 
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		left join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments on embelishments.id=style_embelishments.embelishment_id
		left join production_processes on production_processes.id=embelishments.production_process_id
		left join embelishment_types on embelishment_types.id=style_embelishments.embelishment_type_id
		left join style_gmts on style_gmts.id=style_embelishments.style_gmt_id
		left join item_accounts on item_accounts.id=style_gmts.item_account_id
		left join gmtsparts on gmtsparts.id=style_embelishments.gmtspart_id
		where production_processes.production_area_id = ? and sales_order_gmt_color_sizes.sale_order_id=?
		group by item_accounts.item_description,style_embelishments.embelishment_type_id,embelishment_types.name,gmtsparts.name,style_embelishments.embelishment_size_id,style_embelishments.gmtspart_id', [60,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->gmt_item=$result->gmt_item;
				$result->emb_type=$result->emb_type;
				$result->gmt_part_name=$result->gmt_part_name;
				$result->embelishment_size=$embelishmentsize[$result->embelishment_size_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		item_accounts.item_description as gmt_item,
		embelishment_types.name as emb_type,
		gmtsparts.name as gmt_part_name,
		style_embelishments.embelishment_size_id,
		sum(budget_emb_cons.req_cons) as qty,
		avg(budget_emb_cons.rate) as rate, 
		sum(budget_emb_cons.amount) as amount'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_emb_cons', function($join)  {
		$join->on('budget_emb_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
		})
		->leftJoin('budget_embs', function($join)  {
		$join->on('budget_embs.id', '=', 'budget_emb_cons.budget_emb_id');
		})
        ->leftJoin('style_embelishments', function($join)  {
		$join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
		})
		->leftJoin('embelishments', function($join)  {
		$join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
		})

		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'embelishments.production_process_id');
		})
		->leftJoin('embelishment_types', function($join)  {
		$join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
		})

		
		->leftJoin('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
		})
		->leftJoin('item_accounts',function($join){
		$join->on('item_accounts.id','=','style_gmts.item_account_id');
		})
		->leftJoin('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
		})
		

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',60]])
		->groupBy([
		'item_accounts.item_description',
		'embelishment_types.name',
		'gmtsparts.name',
		'style_embelishments.embelishment_size_id'
		])
		->get();
		foreach($results as $result)
			{
				if($result->qty)
				{
				$result->gmt_item=$result->gmt_item;
				$result->emb_type=$result->emb_type;
				$result->gmt_part_name=$result->gmt_part_name;
				$result->embelishment_size=$embelishmentsize[$result->embelishment_size_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }


    public function getoth()
    {
    	$datas=array();
		$othercosthead=array_prepend(config('bprs.othercosthead'),'-Select-','');
		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select budget_others.id, 
			budget_others.cost_head_id,
			budget_others.amount,
			sum(sales_order_gmt_color_sizes.qty) as qty
			from budget_others 
			left join budgets on budgets.id = budget_others.budget_id  
			left join jobs on jobs.id = budgets.job_id
			left join sales_orders on sales_orders.job_id = jobs.id 
			left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
			where budget_others.cost_head_id !=15  and sales_orders.id=?
			group by budget_others.id, budget_others.cost_head_id,budget_others.amount', [request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				
				$result->name=$othercosthead[$result->cost_head_id];
				$result->amount=number_format(($result->amount/12)*$result->qty,'2','.',',');
				array_push($datas,$result);
			}
		}
		else{
			$results=$this->style
		->selectRaw(
		' 
		budget_others.cost_head_id,
		budgetOther.amount'
		
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.style_id', '=', 'styles.id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		})
		->leftJoin('budget_others', function($join)  {
		$join->on('budget_others.budget_id', '=', 'budgets.id');
		})
		->leftJoin(\DB::raw('(select m.cost_head_id,sum(m.amount) as amount  from 
			(
				select budget_others.id, 
			budget_others.cost_head_id,
			
			sum ((sales_order_gmt_color_sizes.qty) *(budget_others.amount/12)) as amount
			from budget_others 
			left join budgets on budgets.id = budget_others.budget_id  
			left join jobs on jobs.id = budgets.job_id
			left join sales_orders on sales_orders.job_id = jobs.id 
			left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
			where budget_others.cost_head_id !=15
			group by budget_others.id, budget_others.cost_head_id,budget_others.amount
		) m group by m.cost_head_id) budgetOther'), "budgetOther.cost_head_id", "=", "budget_others.cost_head_id")
		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('jobs.company_id', '=', request('company_id', 0));
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
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['budget_others.cost_head_id','!=',15]])
		->groupBy([
		'budget_others.cost_head_id',
		'budgetOther.amount'
		])
		->get();
		foreach($results as $result)
			{
				if($result->amount)
				{
				$result->name=$othercosthead[$result->cost_head_id];
				$result->amount=number_format($result->amount,'2','.',',');
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }

    public function getsalesorder()
    {
    	$colors=array();
    	$sizes=array();
    	$datas=array();
    	$color_total=array();
    	$size_total=array();


        $country=array();
    	$country_colors=array();
    	$country_sizes=array();
    	$country_datas=array();
    	$country_color_total=array();
    	$country_size_total=array();


    	
		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select sales_orders.id,sales_order_countries.country_id,countries.name as country_name,style_colors.color_id, colors.name as color_name, style_sizes.size_id,sizes.name as size_name,
			sales_order_gmt_color_sizes.qty
			from sales_order_gmt_color_sizes 
			left join sales_order_countries on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
			left join sales_orders on sales_orders.id = sales_order_countries.sale_order_id 
			left join jobs on jobs.id = sales_orders.job_id
			left join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
			left join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
			left join colors on colors.id = style_colors.color_id
			left join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id
			left join sizes on sizes.id = style_sizes.size_id
			left join countries on countries.id = sales_order_countries.country_id
			where sales_orders.id=? and sales_order_gmt_color_sizes.deleted_at is null
			', [request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$colors[$result->color_id]=$result->color_name;
				$sizes[$result->size_id]=$result->size_name;
				$datas[$result->color_id][$result->size_id][]=$result->qty;
				$color_total[$result->color_id][]=$result->qty;
				$size_total[$result->size_id][]=$result->qty;
				//country
				$country[$result->country_id]=$result->country_name;
				$country_colors[$result->country_id][$result->color_id]=$result->color_name;
				$country_sizes[$result->country_id][$result->size_id]=$result->size_name;
				$country_datas[$result->country_id][$result->color_id][$result->size_id][]=$result->qty;
				$country_color_total[$result->country_id][$result->color_id][]=$result->qty;
				$country_size_total[$result->country_id][$result->size_id][]=$result->qty;

			}


		}
		else{
			$results=$this->salesordergmtcolorsize
			->selectRaw(
			' 
			sales_orders.id,sales_order_countries.country_id,countries.name as country_name,style_colors.color_id, colors.name as color_name, style_sizes.size_id,sizes.name as size_name,
			sales_order_gmt_color_sizes.qty'
			)
			->leftJoin('sales_order_countries', function($join)  {
			$join->on('sales_order_countries.id', '=', 'sales_order_gmt_color_sizes.sale_order_country_id');
			})
			->leftJoin('sales_orders', function($join)  {
			$join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
			})
			->leftJoin('jobs', function($join)  {
			$join->on('jobs.id', '=', 'sales_orders.job_id');
			})
			->leftJoin('style_gmt_color_sizes', function($join)  {
			$join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
			})
			->leftJoin('style_colors', function($join)  {
			$join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
			})
			->leftJoin('colors', function($join)  {
			$join->on('colors.id', '=', 'style_colors.color_id');
			})
			->leftJoin('style_sizes', function($join)  {
			$join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
			})
			->leftJoin('sizes', function($join)  {
			$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->leftJoin('countries', function($join)  {
			$join->on('countries.id', '=', 'sales_order_countries.country_id');
			})
			->leftJoin('styles', function($join)  {
			$join->on('styles.id', '=', 'jobs.style_id');
			})


			->when(request('buyer_id'), function ($q) {
			return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
			})
			->when(request('style_ref'), function ($q) {
			return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
			})
			->when(request('company_id'), function ($q) {
			return $q->where('jobs.company_id', '=', request('company_id', 0));
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
			->when(request('order_status'), function ($q) {
			return $q->where('sales_orders.order_status', '=',request('order_status', 0));
			})
			->get();
			foreach($results as $result)
			{
				$colors[$result->color_id]=$result->color_name;
				$sizes[$result->size_id]=$result->size_name;
				$datas[$result->color_id][$result->size_id][]=$result->qty;
				$color_total[$result->color_id][]=$result->qty;
				$size_total[$result->size_id][]=$result->qty;
				//country
				$country[$result->country_id]=$result->country_name;
				$country_colors[$result->country_id][$result->color_id]=$result->color_name;
				$country_sizes[$result->country_id][$result->size_id]=$result->size_name;
				$country_datas[$result->country_id][$result->color_id][$result->size_id][]=$result->qty;
				$country_color_total[$result->country_id][$result->color_id][]=$result->qty;
				$country_size_total[$result->country_id][$result->size_id][]=$result->qty;
			}
		}
		return Template::loadView('Report.ColorSizeMatrix', ['colors'=>$colors,'sizes'=>$sizes,'datas'=>$datas,'color_total'=>$color_total,'size_total'=>$size_total,'country'=>$country,  'country_colors'=>$country_colors,'country_sizes'=>$country_sizes,'country_datas'=>$country_datas,'country_color_total'=>$country_color_total,'country_size_total'=>$country_size_total]);

    }
}