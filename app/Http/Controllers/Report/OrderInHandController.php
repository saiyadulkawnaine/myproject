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
use Illuminate\Support\Carbon;


class OrderInHandController extends Controller
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
		$this->middleware('permission:view.orderwisebudgetreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'',0);
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
		$months=array_prepend(config('bprs.months'),'-Select-','');
		$years=array_prepend(config('bprs.years'),'-Select-','');
		$first_selected_month=date('n')+1;
		$selected_year=date('Y');
        return Template::loadView('Report.OrderInHand',['company'=>$company,'buyer'=>$buyer,'status'=>$status,'months'=>$months,'years'=>$years,'first_selected_month'=>$first_selected_month,'selected_year'=>$selected_year]);
    }
    public function formatTwo(){
    	$year=request('year', 0);
    	$month_from=request('month_from', 0);
    	$month_to=request('month_to', 0);
    	$months=($month_to-$month_from)+1;
    	$rest_of_months=$months%3;
    	$round_months=$months-$rest_of_months;
		$slots=$round_months/3;
		$header=array();
		$subMonth=array();
		$rangeArr=array();
		for($i=1;$i<=$slots;$i++)
		{
			$last_month=($month_from+3)-1;
			$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
			$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($last_month,2,"0",STR_PAD_LEFT).'-01'));
			$last_date = date("Y-m-t", strtotime($first_date_last_month));
			$month_from+=3;
			$collection=$this->reportData($first_date,$last_date);
			$rangeArr[$i]=$collection;
			$subMonth[$i]['month_from']=date('n',strtotime($first_date));
			$subMonth[$i]['month_to']=date('n',strtotime($last_date));
		}
		if($rest_of_months){
			$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));

			$last_month=($month_from+$rest_of_months)-1;
			$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($last_month,2,"0",STR_PAD_LEFT).'-01'));

			$last_date = date("Y-m-t", strtotime($first_date_last_month));
			$collection=$this->reportData($first_date,$last_date);
			$rangeArr[$i]=$collection;
			$subMonth[$i]['month_from']=date('n',strtotime($first_date));
			$subMonth[$i]['month_to']=date('n',strtotime($last_date));
		}

        $styles=array();
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
		$company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');


		for($j=1; $j<=count($rangeArr);$j++)
		{
		 	$subtotQty=0;
			$subtotAmt=0;
			$subtotShipQty=0;
			$subtotShipBalance=0;
			$subtotyarnamount=0;
			$subtottrimamount=0;
			$subtotfabpuramount=0;
			$subknitingamount=0;
			$subyarndyingamount=0;
			$subdyingamount=0;
			$subaopamount=0;
			$subburnoutamount=0;
			$subwashingamount=0;
			$subprintingamount=0;
			$subembamount=0;
			$subspembamount=0;
			$subgmtdyeingamount=0;
			$subgmtwashingamount=0;
			$subcourieramount=0;
			$subfreightamount=0;
			$subcmamount=0;
			$subcommiamount=0;
			$subcommeramount=0;
			$subtotalamount=0;
			$subtotalprofit=0;
			$subtotalprofitper=0;
			$subfin_fab_cons=0;
			$rows=$rangeArr[$j];
			$rangeCompany=array();
			foreach($rows as $row)
			{
				$receive_date = Carbon::parse($row->sale_order_receive_date);
				$ship_date = Carbon::parse($row->ship_date);
				$diff = $receive_date->diffInDays($ship_date);
				if($diff >1){
					$diff.=" Days";
				}else{
					$diff.=" Day";
				}
				$rangeCompany[$row->company_id]=$row->company_id;
				$style['id']=	$row->id;
				$style['receivedate']=	$row->receive_date;
				$style['style_ref']=	$row->style_ref;
				$style['style_id']=	$row->style_id;
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
				$style['company_id']=$row->company_id;
				$style['produced_company_id']=$company[$row->company_id];
				$style['sale_order_no']=$row->sale_order_no;

				$style['lc_sc_no']=$row->lc_sc_no? "Yes:".$row->lc_sc_no:'No';
				$style['sale_order_receive_date']=date('d-M-Y',strtotime($row->sale_order_receive_date));
				$style['internal_ref']=$row->internal_ref;
				$style['delivery_date']=date('d-M-Y',strtotime($row->ship_date));
				$style['lead_time']=$diff;
				$style['delivery_month']=date('M',strtotime($row->ship_date));
				$style['month_from']=date('n',strtotime($row->ship_date));
				$style['month_to']=date('n',strtotime($row->ship_date));
				$style['item_description']=$row->item_description;
				$style['item_complexity']=$itemcomplexity[$row->item_complexity];
				$style['qty']=number_format($row->qty,'0','.',',');
				$style['rate']=number_format($row->rate,'4','.',',');
				$style['amount']=number_format($row->amount,'2','.',',');
				$style['ship_qty']=number_format($row->ship_qty,'0','.',',');
				$style['ship_balance']=number_format($row->qty-$row->ship_qty,'0','.',',');

				$style['yarn_amount']=number_format($row->yarn_amount,'2','.',',');
				$style['trim_amount']=number_format($row->trim_amount,'2','.',',');
				$style['fab_pur_amount']=number_format($row->fab_pur_amount,'2','.',',');
				$style['kniting_amount']=number_format($row->kniting_amount,'2','.',',');
				$style['yarn_dying_amount']=number_format($row->yarn_dying_amount,'2','.',',');
				//$style['weaving_amount']=number_format($row->weaving_amount,'2','.',',');
				$style['dying_amount']=number_format($row->dying_amount+$row->dyeing_overhead_amount+$row->finishing_amount,'2','.',',');

				$style['aop_amount']=number_format($row->aop_amount+$row->aop_overhead_amount,'2','.',',');
				$style['burn_out_amount']=number_format($row->burn_out_amount,'2','.',',');
				//$style['finishing_amount']=number_format($row->finishing_amount,'2','.',',');
				$style['washing_amount']=number_format($row->washing_amount,'2','.',',');
				
				$style['printing_amount']=number_format($row->printing_amount+$row->print_overhead_amount,'2','.',',');
				$style['emb_amount']=number_format($row->emb_amount,'2','.',',');
				$style['spemb_amount']=number_format($row->spemb_amount,'2','.',',');
				$style['gmt_dyeing_amount']=number_format($row->gmt_dyeing_amount,'2','.',',');
				$style['gmt_washing_amount']=number_format($row->gmt_washing_amount,'2','.',',');

				$courier_amount=number_format(($row->courier_rate/12)*($row->qty/$row->item_ratio),'2','.','');
				$lab_amount=number_format(($row->lab_rate/12)*($row->qty/$row->item_ratio),'2','.','');
				$insp_amount=number_format(($row->insp_rate/12)*($row->qty/$row->item_ratio),'2','.','');
				$opa_amount=number_format(($row->opa_rate/12)*($row->qty/$row->item_ratio),'2','.','');
				$dep_amount=number_format(($row->dep_rate/12)*($row->qty/$row->item_ratio),'2','.','');
				$coc_amount=number_format(($row->coc_rate/12)*($row->qty/$row->item_ratio),'2','.','');
				$ict_amount=number_format(($row->ict_rate/12)*($row->qty/$row->item_ratio),'2','.','');
				$other_amount=$courier_amount+$lab_amount+$insp_amount+$opa_amount+$dep_amount+ $coc_amount+ $ict_amount;

				$style['courier_amount']=number_format($other_amount,'2','.',',');
				$freight_amount=number_format(($row->freight_rate/12)*($row->qty/$row->item_ratio),'2','.','');
				$style['freight_amount']=number_format($freight_amount,'2','.',',');
				//$cm_amount=number_format(($row->cm_rate/12)*($row->qty/$row->item_ratio),'2','.','');
				$cm_amount=number_format(($row->cm_rate * $row->qty),'2','.','');
				$style['cm_amount']=number_format($cm_amount,'2','.',',');
				$commi_amount=number_format(($row->commi_rate/100)*$row->amount,'2','.','');
				$style['commi_amount']=number_format($commi_amount,'2','.',',');
				
				$commmercial=
				$row->trim_amount+
				$row->fab_pur_amount+
				$row->kniting_amount+
				$row->yarn_dying_amount+
				$row->weaving_amount+
				$row->dying_amount+$row->dyeing_overhead_amount+$row->finishing_amount+
				$row->aop_amount+$row->aop_overhead_amount+
				$row->burn_out_amount+
				
				$row->washing_amount+
				$row->yarn_amount+
				$row->printing_amount+$row->print_overhead_amount+
				$row->emb_amount+
				$row->spemb_amount+
				$row->gmt_dyeing_amount+
				$row->gmt_washing_amount;
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
				$total_profit_per=($total_profit/$ordAmount)*100;
				$style['total_profit_per']=number_format($total_profit_per,'2','.',',');

                $fin_fab_cons=0;
                if($row->plan_cut_qty){
                	$fin_fab_cons=($row->fin_fab/($row->plan_cut_qty/$row->item_ratio))*12;
                }
				
				$style['fin_fab_cons']=number_format($fin_fab_cons,'2','.',',');

                
				$subtotQty+=$row->qty;
				$subtotAmt+= $row->amount;

				$subtotShipQty+=$row->ship_qty;
			    $subtotShipBalance+=$row->qty-$row->ship_qty;

				$subtotyarnamount+=$row->yarn_amount;
				$subtottrimamount+=$row->trim_amount;
				$subtotfabpuramount+=$row->fab_pur_amount;
				$subknitingamount+=$row->kniting_amount;;
				$subyarndyingamount+=$row->yarn_dying_amount;
				$subdyingamount+=$row->dying_amount+$row->dyeing_overhead_amount+$row->finishing_amount;
				$subaopamount+=$row->aop_amount+$row->aop_overhead_amount;
				$subburnoutamount+=$row->burn_out_amount;
				$subwashingamount+=$row->washing_amount;
				$subprintingamount+=$row->printing_amount+$row->print_overhead_amount;
				$subembamount+=$row->emb_amount;
				$subspembamount+=$row->spemb_amount;
				$subgmtdyeingamount+=$row->gmt_dyeing_amount;
				$subgmtwashingamount+=$row->gmt_washing_amount;
				$subcourieramount+=$other_amount;
				$subfreightamount+=$freight_amount;
				$subcmamount+=$cm_amount;
				$subcommiamount+=$commi_amount;
				$subcommeramount+=$commer_amount;
				$subtotalamount+=$total_amount;
				$subtotalprofit+=$total_profit;
				$subfin_fab_cons+=$fin_fab_cons;
				array_push($styles,$style);
			}
			$month_from=$subMonth[$j]['month_from'];
			$month_to=$subMonth[$j]['month_to'];
			$rate=0;
			$subtotalprofitper=0;
			if($subtotQty)
			{
				$rate=$subtotAmt/$subtotQty;
				$subtotalprofitper=($subtotalprofit/$subtotAmt)*100;

			}
			
			if($rows->sum('qty')){
				$subTot = collect([
					'company_code'=>'Sub Total',
					'qty'=>number_format($subtotQty,0,'.',','),
					'rate'=>number_format($rate,4,'.',','),
					'amount'=>number_format($subtotAmt,4,'.',','),
					'ship_qty'=>number_format($subtotShipQty,0,'.',','),
					'ship_balance'=>number_format($subtotShipBalance,0,'.',','),
					'yarn_amount'=>number_format($subtotyarnamount,4,'.',','),
					'trim_amount'=>number_format($subtottrimamount,4,'.',','),
					'fab_pur_amount'=>number_format($subtotfabpuramount,4,'.',','),
					'kniting_amount'=>number_format($subknitingamount,4,'.',','),
					'yarn_dying_amount'=>number_format($subyarndyingamount,4,'.',','),
					'dying_amount'=>number_format($subdyingamount,4,'.',','),
					'aop_amount'=>number_format($subaopamount,4,'.',','),
					'burn_out_amount'=>number_format($subburnoutamount,4,'.',','),
					'washing_amount'=>number_format($subwashingamount,4,'.',','),
					'printing_amount'=>number_format($subprintingamount,4,'.',','),
					'emb_amount'=>number_format($subembamount,4,'.',','),
					'spemb_amount'=>number_format($subspembamount,4,'.',','),
					'gmt_dyeing_amount'=>number_format($subgmtdyeingamount,4,'.',','),
					'gmt_washing_amount'=>number_format($subgmtwashingamount,4,'.',','),
					'courier_amount'=>number_format($subcourieramount,4,'.',','),
					'freight_amount'=>number_format($subfreightamount,4,'.',','),
					'cm_amount'=>number_format($subcmamount,4,'.',','),
					'commi_amount'=>number_format($subcommiamount,4,'.',','),
					'commer_amount'=>number_format($subcommeramount,4,'.',','),
					'total_amount'=>number_format($subtotalamount,4,'.',','),
					'total_profit'=>number_format($subtotalprofit,4,'.',','),
					'total_profit_per'=>number_format($subtotalprofitper,4,'.',','),
					'month_from'=>$month_from,
					'month_to'=>$month_to,
					'id'=>0,
					'company_id'=>implode(",",$rangeCompany),
					'fin_fab_cons'=>number_format($subfin_fab_cons,4,'.',',')
				]);
				array_push($styles,$subTot);
			}
		}
		echo json_encode($styles);
    }

    /*public function formatOne()
    {
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
	        $cm_amount=number_format(($row->cm_rate/12)*$row->qty,'2','.','');
	        $style['cm_amount']=number_format($cm_amount,'2','.',',');
	        $commi_amount=number_format(($row->commi_rate/100)*$row->amount,'2','.','');
	        $style['commi_amount']=number_format($commi_amount,'2','.',',');
	        $commmercial=$row->trim_amount+$row->kniting_amount+$row->yarn_dying_amount+$row->weaving_amount+$row->dying_amount+$row->aop_amount+$row->burn_out_amount+$row->finishing_amount+$row->washing_amount+$row->yarn_amount+$row->printing_amount+$row->emb_amount+$row->spemb_amount+$row->gmt_dyeing_amount+$row->gmt_washing_amount;
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

    }*/

    public function reportData($first_date,$last_date) {
    	$user = \Auth::user();
    	$user_id=$user->id;
    	//$level=$user->level();
		
		$rows=$this->style
		->selectRaw(
		'
		styles.id as style_id,
		styles.style_ref,
		styles.flie_src,
		buyers.name as buyer_name,
		uoms.code as uom_name,
		seasons.name as season_name,
		teams.name as team_name,
		users.name as team_member_name,
		productdepartments.department_name,
		jobs.id as job_id,
		jobs.job_no,
		sales_orders.produced_company_id as company_id,
		companies.code as company_code,
		sales_orders.id,
		sales_orders.sale_order_no,
		sales_orders.receive_date as sale_order_receive_date,
		sales_orders.internal_ref,
		sales_orders.ship_date,
		sum(sales_order_gmt_color_sizes.qty) as qty,
		avg(sales_order_gmt_color_sizes.rate) as rate,
		sum(sales_order_gmt_color_sizes.amount) as amount, 
        sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
		budgetTrim.trim_amount,
		budgetFabPur.fab_pur_amount,
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
		exfactory.qty as ship_qty,
		budgetFab.grey_fab,
		budgetFab.fin_fab,
		gmt_item_ratio.item_ratio,
		explcsc.lc_sc_no
		'
		)
		->join('buyers', function($join)  {
			$join->on('styles.buyer_id', '=', 'buyers.id');
		})
		->join('buyer_users', function($join) use($user_id) {
			$join->on('buyer_users.buyer_id', '=', 'buyers.id');
			$join->where('buyer_users.user_id', '=', $user_id);
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
		$join->on('budgets.job_id', '=', 'jobs.id');
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
		->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.sale_order_id,sum(budget_trim_cons.amount) as trim_amount FROM budget_trim_cons  join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id   group by sales_order_gmt_color_sizes.sale_order_id) budgetTrim"), "budgetTrim.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(SELECT 
		sales_orders.id as sales_order_id,
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
		group by sales_orders.id) budgetFabPur"), "budgetFabPur.sales_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as kniting_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =10
		group by sales_orders.id,production_processes.production_area_id) budgetKniting"), "budgetKniting.sale_order_id", "=", "sales_orders.id")

		/*->leftJoin(\DB::raw("(
			select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as yarn_dying_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =5
		group by sales_orders.id,production_processes.production_area_id
	    ) budgetYarnDyeing"), "budgetYarnDyeing.sale_order_id", "=", "sales_orders.id")*/

	    ->leftJoin(\DB::raw("(
			select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_yarn_dyeing_cons.amount) as yarn_dying_amount
			from budget_yarn_dyeing_cons 
			left join sales_orders on sales_orders.id = budget_yarn_dyeing_cons.sales_order_id 
			left join budget_yarn_dyeings on budget_yarn_dyeings.id=budget_yarn_dyeing_cons.budget_yarn_dyeing_id 
			left join production_processes on production_processes.id=budget_yarn_dyeings.production_process_id 
			where production_processes.production_area_id =5
			group by sales_orders.id,production_processes.production_area_id
	    ) budgetYarnDyeing"), "budgetYarnDyeing.sale_order_id", "=", "sales_orders.id")

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

		->leftJoin(\DB::raw('(select budgets.job_id, sum(budget_others.amount)  as courier_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =1 group by budgets.job_id) budgetCourier'), "budgetCourier.job_id", "=", "jobs.id")
		->leftJoin(\DB::raw('(select budgets.job_id, sum(budget_others.amount)  as lab_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =5 group by budgets.job_id) budgetLab'), "budgetLab.job_id", "=", "jobs.id")
		->leftJoin(\DB::raw('(select budgets.job_id, sum(budget_others.amount)  as insp_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =10 group by budgets.job_id) budgetInsp'), "budgetInsp.job_id", "=", "jobs.id")

		->leftJoin(\DB::raw('(select budgets.job_id, sum(budget_others.amount)  as freight_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =15 group by budgets.job_id) budgetFreight'), "budgetFreight.job_id", "=", "jobs.id")
		->leftJoin(\DB::raw('(select budgets.job_id, sum(budget_others.amount)  as opa_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =20 group by budgets.job_id) budgetOpa'), "budgetOpa.job_id", "=", "jobs.id")
		->leftJoin(\DB::raw('(select budgets.job_id, sum(budget_others.amount)  as dep_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =25 group by budgets.job_id) budgetDep'), "budgetDep.job_id", "=", "jobs.id")
		->leftJoin(\DB::raw('(select budgets.job_id, sum(budget_others.amount)  as coc_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =30 group by budgets.job_id) budgetCoc'), "budgetCoc.job_id", "=", "jobs.id")
		->leftJoin(\DB::raw('(select budgets.job_id, sum(budget_others.amount)  as ict_rate from budget_others left join budgets on budgets.id = budget_others.budget_id  where budget_others.cost_head_id =35 group by budgets.job_id) budgetIct'), "budgetIct.job_id", "=", "jobs.id")
		->leftJoin(\DB::raw('(select budgets.job_id, avg(budget_cms.cm_per_pcs)  as cm_rate from budget_cms left join budgets on budgets.id = budget_cms.budget_id   group by budgets.job_id) budgetCm'), "budgetCm.job_id", "=", "jobs.id")
		->leftJoin(\DB::raw('(select budgets.job_id, sum(budget_commercials.rate)  as commer_rate from budget_commercials left join budgets on budgets.id = budget_commercials.budget_id   group by budgets.job_id) budgetCommer'), "budgetCommer.job_id", "=", "jobs.id")
		->leftJoin(\DB::raw('(select budgets.job_id, sum(budget_commissions.rate)  as commi_rate from budget_commissions left join budgets on budgets.id = budget_commissions.budget_id   group by budgets.job_id) budgetCommi'), "budgetCommi.job_id", "=", "jobs.id")
		->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,sum(style_pkg_ratios.qty) as qty FROM sales_orders  
			join jobs on jobs.id = sales_orders.job_id 
			join styles on styles.id = jobs.style_id 
			join style_pkgs on style_pkgs.style_id = styles.id 
			join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
			and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id 
			join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
			group by sales_orders.id) exfactory"), "exfactory.sale_order_id", "=", "sales_orders.id")
		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_fabric_cons.grey_fab) as grey_fab,sum(budget_fabric_cons.fin_fab) as fin_fab  
		from budget_fabric_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id
		left join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
		where budget_fabric_cons.deleted_at is null
		group by sales_order_gmt_color_sizes.sale_order_id) budgetFab'), "budgetFab.sale_order_id", "=", "sales_orders.id")
		->join(\DB::raw('(select style_gmts.style_id, sum(style_gmts.gmt_qty)  as item_ratio from style_gmts   group by style_gmts.style_id) gmt_item_ratio'), "gmt_item_ratio.style_id", "=", "styles.id")
		/*->leftJoin(\DB::raw('(select exp_lc_scs.lc_sc_no,sales_orders.sale_order_no,exp_pi_orders.sales_order_id from exp_pi_orders
		join sales_orders on sales_orders.id=exp_pi_orders.sales_order_id
		join exp_pis on exp_pis.id=exp_pi_orders.exp_pi_id
		join exp_lc_sc_pis on exp_lc_sc_pis.exp_pi_id=exp_pis.id
		join exp_lc_scs on exp_lc_scs.id=exp_lc_sc_pis.exp_lc_sc_id) explcsc'), "explcsc.sales_order_id", "=", "sales_orders.id")*/
		->leftJoin(\DB::raw('(select count(exp_lc_scs.lc_sc_no) as lc_sc_no,sales_orders.sale_order_no,exp_pi_orders.sales_order_id from exp_pi_orders
		join sales_orders on sales_orders.id=exp_pi_orders.sales_order_id
		join exp_pis on exp_pis.id=exp_pi_orders.exp_pi_id
		join exp_lc_sc_pis on exp_lc_sc_pis.exp_pi_id=exp_pis.id
		join exp_lc_scs on exp_lc_scs.id=exp_lc_sc_pis.exp_lc_sc_id group by exp_pi_orders.sales_order_id,sales_orders.sale_order_no ) explcsc'), "explcsc.sales_order_id", "=", "sales_orders.id")
		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date) {
		return $q->where('sales_orders.ship_date', '>=',$first_date);
		})
		->when($last_date, function ($q) use($last_date) {
		return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['sales_orders.order_status','!=',2]])
		->groupBy([
			'styles.id',
			'styles.style_ref',
			'styles.flie_src',
			'buyers.name',
			'uoms.code',
			'seasons.name',
			'teams.name',
			'users.name',
			'productdepartments.department_name',
			'jobs.id',
			'jobs.job_no',
			'sales_orders.produced_company_id',
			'companies.code',
			'sales_orders.id',
			'sales_orders.sale_order_no',
			'sales_orders.receive_date',
			'sales_orders.internal_ref',
			'sales_orders.ship_date',
			'budgetTrim.trim_amount',
			'budgetFabPur.fab_pur_amount',
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
			'exfactory.qty',
			'budgetFab.grey_fab',
		    'budgetFab.fin_fab',
		    'gmt_item_ratio.item_ratio',
		    'explcsc.lc_sc_no'
		])
		->orderBy('sales_orders.ship_date')
		->get();
		return $rows;
    }

    public function getyarn()
    {
    	$company_id=explode(',',request('company_id', 0));
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$job_no=request('job_no', 0);
		$order_status=request('order_status',0);
		$company=null;
		$buyer=null;
		$style=null;
		$job=null;
		$orderstatus=null;
		if($company_id){
			$company=" and sales_orders.produced_company_id in(".implode(',',$company_id).") ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($job_no){
			$job=" and jobs.job_no like %$job_no% ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}

    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));
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
				left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id  
				join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id   
				join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id 
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				

				where sales_orders.id=? 
				group by 
				budget_yarns.id,
				budget_yarns.budget_fabric_id,
				budget_yarns.item_account_id,
				style_gmts.item_account_id,
				budget_yarns.ratio,
				budget_yarns.cons,
				budget_yarns.rate,
				budget_yarns.amount,
				style_fabrications.id,
				gmtsparts.name,
				item_accounts.item_description,
				style_gmts.id,
				gmtsparts.id 
				order by style_gmts.id,
				gmtsparts.id,
				budget_yarns.id', [request('sale_order_id',0)]);
			
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
		'budget_yarns.item_account_id,
		budgetYarn.yarn_qty,
		budgetYarn.yarn_amount,
		yarncounts.count,
		poYarn.po_qty,
		poYarn.po_rate,
		poYarn.po_amount
		'
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
				join budget_fabrics on budget_fabrics.id=budget_yarns.budget_fabric_id 

				join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
				join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
				join item_accounts on item_accounts.id=style_gmts.item_account_id
                left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id 
				join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id

				join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id 
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				
				where sales_orders.ship_date>='".$first_date."' and 
				sales_orders.ship_date<='".$last_date."' $company $buyer $style $job $orderstatus
				and sales_orders.order_status !=2
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

		->leftJoin(\DB::raw("(select 
			budget_yarns.item_account_id,
			sum(po_yarn_item_bom_qties.qty) as po_qty,
			avg(po_yarn_item_bom_qties.rate) as po_rate,
			sum(po_yarn_item_bom_qties.amount) as po_amount
			from budget_yarns
			join budgets on budgets.id=budget_yarns.budget_id
			join jobs on jobs.id=budgets.job_id
			join sales_orders on sales_orders.job_id=jobs.id 
			join styles on styles.id=jobs.style_id
			left join po_yarn_item_bom_qties on budget_yarns.id=po_yarn_item_bom_qties.budget_yarn_id 
			and sales_orders.id=po_yarn_item_bom_qties.sale_order_id
			where  sales_orders.ship_date>='".$first_date."' and 
				sales_orders.ship_date<='".$last_date."' $company $buyer $style $job $orderstatus
				and sales_orders.order_status !=2
			group by 
			budget_yarns.item_account_id) poYarn"), "poYarn.item_account_id", "=", "budget_yarns.item_account_id")
		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) use($company_id){
			return $q->whereIn('sales_orders.produced_company_id', $company_id);
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
		return $q->where('sales_orders.ship_date', '>=',$first_date);
		})
		->when($last_date, function ($q) use($last_date){
		return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['sales_orders.order_status','!=',2]])
		->groupBy([
		'budget_yarns.item_account_id', 
		'budgetYarn.yarn_qty',
		'budgetYarn.yarn_amount',
		'yarncounts.count',
		'poYarn.po_qty',
		'poYarn.po_rate',
		'poYarn.po_amount'
		])
		->orderBy('yarncounts.count')
		/*->toSql();
		dd($rows);*/
		->get()
		->groupBy('count');
		foreach($rows as $count){
			$subQty=0;
			$subAmount=0;
			$subPoQty=0;
			$subPoAmount=0;
			$subBalPoQty=0;
			$subBalPoAmount=0;
			foreach($count as $result){
				if($result->yarn_qty)
				{
					$subQty+=$result->yarn_qty;
			        $subAmount+=$result->yarn_amount;
			        $subPoQty+=$result->po_qty;
			        $subPoAmount+=$result->po_amount;
					$result->yarn_des=$yarnDropdown[$result->item_account_id];
					$bal_po_qty=$result->yarn_qty-$result->po_qty;
					$bal_po_amount=$result->yarn_amount-$result->po_amount;
					$subBalPoQty+=$bal_po_qty;
			        $subBalPoAmount+=$bal_po_amount;

					$result->bal_po_qty=number_format($bal_po_qty,'4','.',',');
					$result->bal_po_amount=number_format($bal_po_amount,'4','.',',');

					$result->rate=number_format($result->yarn_amount/$result->yarn_qty,'4','.',',');

					$result->yarn_amount=number_format($result->yarn_amount,'4','.',',');
					$result->yarn_qty=number_format($result->yarn_qty,'4','.',',');

					
					$result->po_qty=number_format($result->po_qty,'4','.',',');
					$result->po_rate=number_format($result->po_rate,'4','.',',');
					$result->po_amount=number_format($result->po_amount,'4','.',',');
					
					array_push($datas,$result);
				}
			}

			$rate=0;
			if($subQty){
	            $rate=number_format($subAmount/$subQty,'4','.',',');
			}
            $porate=0;
            if($subPoQty){
            	$porate=number_format($subPoAmount/$subPoQty,'4','.',',');
            }
            
			$subTot = collect(['yarn_des'=>'Sub Total','yarn_amount'=>number_format($subAmount,'4','.',','),'yarn_qty'=>number_format($subQty,'4','.',','),'rate'=>$rate,'po_qty'=>number_format($subPoQty,'4','.',','),'porate'=>$porate,'po_amount'=>number_format($subPoAmount,'4','.',','),'bal_po_qty'=>number_format($subBalPoQty,'4','.',','),'bal_po_amount'=>number_format($subBalPoAmount,'4','.',',')]);
			array_push($datas,$subTot);
		}
    	}

    	$dd=array('total'=>1,'rows'=>$datas,'footer'=>array(0=>array('yarn_des'=>'','yarn_qty'=>'','yarn_amount'=>'','rate'=>'')));
    	echo json_encode($dd);
    }

    public function gettrim()
    {

    	$company_id=explode(',',request('company_id', 0));
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$job_no=request('job_no', 0);
		$order_status=request('order_status',0);
		$company=null;
		$buyer=null;
		$style=null;
		$job=null;
		$orderstatus=null;
		if($company_id){
			$company=" and sales_orders.produced_company_id in(".implode(',',$company_id).") ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($job_no){
			$job=" and jobs.job_no like %$job_no% ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}

    	/*$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));*/

    	//======
		$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

    	$datas=array();
		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select 
			itemclasses.name,
			budget_trims.description,
			budget_trim_cons.budget_trim_id,
			sales_order_gmt_color_sizes.sale_order_id,
			uoms.code,
			sum(budget_trim_cons.bom_trim) as bom_trim, 
			avg(budget_trim_cons.rate) as rate, 
			sum(budget_trim_cons.amount) as trim_amount,
			potrim.qty as po_qty,
			potrim.rate as po_rate,
			potrim.amount as po_amount,
			potrimlc.amount as lc_amount

			from budget_trim_cons 
			join budget_trims on budget_trims.id=budget_trim_cons.budget_trim_id 
			left join itemclasses on itemclasses.id=budget_trims.itemclass_id  
			left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id 
			left join uoms on uoms.id=budget_trims.uom_id

			left join(
			select 
			po_trim_items.budget_trim_id,
			sum(po_trim_item_qties.qty) as qty,
			avg(po_trim_item_qties.rate) as rate,
			sum(po_trim_item_qties.amount) as amount
			from 
			po_trims
			join po_trim_items on po_trim_items.po_trim_id=po_trims.id
			join po_trim_item_qties on po_trim_item_qties.po_trim_item_id=po_trim_items.id
			join budget_trim_cons on budget_trim_cons.id=po_trim_item_qties.budget_trim_con_id
			left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id 
			where
			sales_order_gmt_color_sizes.sale_order_id=? 
			and po_trims.deleted_at is null
			and po_trim_items.deleted_at is null
			and po_trim_item_qties.deleted_at is null
			group by
			po_trim_items.budget_trim_id
			) potrim on potrim.budget_trim_id=budget_trims.id 

			left join(
			select 
			po_trim_items.budget_trim_id,
			sum(po_trim_item_qties.qty) as qty,
			avg(po_trim_item_qties.rate) as rate,
			sum(po_trim_item_qties.amount) as amount
			from 
			po_trims
			join po_trim_items on po_trim_items.po_trim_id=po_trims.id
			join po_trim_item_qties on po_trim_item_qties.po_trim_item_id=po_trim_items.id
			join budget_trim_cons on budget_trim_cons.id=po_trim_item_qties.budget_trim_con_id
			left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id 
			join imp_lc_pos on imp_lc_pos.purchase_order_id=po_trims.id
			where
			sales_order_gmt_color_sizes.sale_order_id=? 
			and po_trims.deleted_at is null
			and po_trim_items.deleted_at is null
			and po_trim_item_qties.deleted_at is null
			group by
			po_trim_items.budget_trim_id
			) potrimlc on potrimlc.budget_trim_id=budget_trims.id 

			where sales_order_gmt_color_sizes.sale_order_id=?    
			group by 
			sales_order_gmt_color_sizes.sale_order_id,
			uoms.code,
			budget_trim_cons.budget_trim_id,
			itemclasses.name,
			budget_trims.description,
			potrim.qty,
			potrim.rate,
			potrim.amount,
			potrimlc.amount
			', [request('sale_order_id',0),request('sale_order_id',0),request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->rate_var=$result->rate-$result->po_rate;
				$result->amount_var=$result->trim_amount-$result->po_amount;
				$result->bom_trim=number_format($result->bom_trim,'2','.',',');
				$result->rate=number_format($result->rate,'4','.',',');
				$result->trim_amount=number_format($result->trim_amount,'2','.',',');
				$result->po_qty=number_format($result->po_qty,'2','.',',');
				$result->po_rate=number_format($result->po_rate,'4','.',',');
				$result->po_amount=number_format($result->po_amount,'2','.',',');
				$result->rate_var=number_format($result->rate_var,'4','.',',');
				$result->amount_var=number_format($result->amount_var,'2','.',',');
				$result->lc_amount=number_format($result->lc_amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		else
		{
			$results=$this->style
			->selectRaw(
			    'itemclasses.id,
				itemclasses.name,
				--budget_trims.description,
				uoms.id,
				uoms.code,
				sum(budget_trim_cons.bom_trim) as bom_trim, 
				avg(budget_trim_cons.rate) as rate, 
				sum(budget_trim_cons.amount) as trim_amount,
				potrim.qty as po_qty,
				potrim.rate as po_rate,
				potrim.amount as po_amount,
				potrimlc.amount as lc_amount
				'
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
			->leftJoin('uoms',function($join){
			$join->on('uoms.id','=','budget_trims.uom_id');
			})
			->leftJoin('itemclasses',function($join){
			$join->on('itemclasses.id','=','budget_trims.itemclass_id');
			})
			->leftJoin(\DB::raw("( 
				select 
				itemclasses.id as itemclass_id,
				itemclasses.name,
				--budget_trims.description,
				uoms.id as uom_id,
				uoms.code,
				sum(po_trim_item_qties.qty) as qty,
				avg(po_trim_item_qties.rate) as rate,
				sum(po_trim_item_qties.amount) as amount
				from budget_trim_cons 
				join budget_trims on budget_trims.id=budget_trim_cons.budget_trim_id 
				left join itemclasses on itemclasses.id=budget_trims.itemclass_id  
				left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id 
				left join uoms on uoms.id=budget_trims.uom_id
				join po_trim_item_qties on po_trim_item_qties.budget_trim_con_id=budget_trim_cons.id
				join po_trim_items on po_trim_items.id=po_trim_item_qties.po_trim_item_id
				join po_trims on po_trims.id=po_trim_items.po_trim_id
				join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on  styles.id=jobs.style_id
				
				where sales_orders.ship_date>='".$first_date."' and 
				sales_orders.ship_date<='".$last_date."' $company $buyer $style $job $orderstatus
				and sales_orders.order_status !=2    
				group by 
				itemclasses.id,
				itemclasses.name,
				--budget_trims.description,
				uoms.id,
				uoms.code
			) potrim"), [
				["potrim.itemclass_id", "=", "itemclasses.id"],
				["potrim.uom_id", "=", "uoms.id"]
			])

			->leftJoin(\DB::raw("( 
				select 
				itemclasses.id as itemclass_id,
				itemclasses.name,
				--budget_trims.description,
				uoms.id as uom_id,
				uoms.code,
				sum(po_trim_item_qties.qty) as qty,
				avg(po_trim_item_qties.rate) as rate,
				sum(po_trim_item_qties.amount) as amount
				from budget_trim_cons 
				join budget_trims on budget_trims.id=budget_trim_cons.budget_trim_id 
				left join itemclasses on itemclasses.id=budget_trims.itemclass_id  
				left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id 
				left join uoms on uoms.id=budget_trims.uom_id
				join po_trim_item_qties on po_trim_item_qties.budget_trim_con_id=budget_trim_cons.id
				join po_trim_items on po_trim_items.id=po_trim_item_qties.po_trim_item_id
				join po_trims on po_trims.id=po_trim_items.po_trim_id
				join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id
				join jobs on jobs.id=sales_orders.job_id
				join styles on  styles.id=jobs.style_id
				join imp_lc_pos on imp_lc_pos.purchase_order_id=po_trims.id
				
				where sales_orders.ship_date>='".$first_date."' and 
				sales_orders.ship_date<='".$last_date."' $company $buyer $style $job $orderstatus
				and sales_orders.order_status !=2    
				group by 
				itemclasses.id,
				itemclasses.name,
				--budget_trims.description,
				uoms.id,
				uoms.code
			) potrimlc"), [
				["potrimlc.itemclass_id", "=", "itemclasses.id"],
				["potrimlc.uom_id", "=", "uoms.id"]
			])
			->when(request('buyer_id'), function ($q) {
			return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
			})
			->when(request('style_ref'), function ($q) {
			return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
			})
			/*->when(request('company_id'), function ($q) {
			return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
			})*/
			->when(request('company_id'), function ($q) use($company_id){
			return $q->whereIn('sales_orders.produced_company_id', $company_id);
			})
			->when(request('job_no'), function ($q) {
			return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
			})
			->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
			})
			->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
			})
			->when(request('order_status'), function ($q) {
			return $q->where('sales_orders.order_status', '=',request('order_status', 0));
			})
			->where([['sales_orders.order_status','!=',2]])
			->groupBy([
				'itemclasses.id',
				'itemclasses.name',
				//'budget_trims.description',
				'uoms.id',
				'uoms.code',
				'potrim.qty',
				'potrim.rate',
				'potrim.amount',
				'potrimlc.amount',

			])
			->orderBy('itemclasses.name')
			->get();
			foreach($results as $result)
			{
				$result->rate_var=$result->rate-$result->po_rate;
				$result->amount_var=$result->trim_amount-$result->po_amount;
				$result->bom_trim=number_format($result->bom_trim,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->trim_amount=number_format($result->trim_amount,'2','.',',');
				$result->po_qty=number_format($result->po_qty,'2','.',',');
				$result->po_rate=number_format($result->po_rate,'4','.',',');
				$result->po_amount=number_format($result->po_amount,'2','.',',');
				$result->rate_var=number_format($result->rate_var,'4','.',',');
				$result->amount_var=number_format($result->amount_var,'2','.',',');
				$result->lc_amount=number_format($result->lc_amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		echo json_encode($datas);
    }

    public function getfabpur()
    {
    	$company_id=explode(',',request('company_id', 0));
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$job_no=request('job_no', 0);
		$order_status=request('order_status',0);
		$company=null;
		$buyer=null;
		$style=null;
		$job=null;
		$orderstatus=null;
		if($company_id){
			$company=" and sales_orders.produced_company_id in(".implode(',',$company_id).") ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($job_no){
			$job=" and jobs.job_no like %$job_no% ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
    	//============
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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
				select 
				sales_orders.id as sales_order_id,
				budget_fabrics.id as budget_fabric_id,
				style_fabrications.id as style_fabrication_id,
				gmtsparts.name as gmt_part_name,
				item_accounts.item_description as gmt_item_description,
				sum(budget_fabric_cons.amount) as fab_pur_amount,
				avg(budget_fabric_cons.rate) as rate,
				sum(budget_fabric_cons.fin_fab) as fin_fab_pur_req,
				sum(budget_fabric_cons.grey_fab) as grey_fab_pur_req
				FROM sales_orders 
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
				join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
				join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
				left join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
				left join item_accounts on item_accounts.id=style_gmts.item_account_id
				left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id 

				join jobs on jobs.id = sales_orders.job_id 
				join styles on styles.id = jobs.style_id 
				where  style_fabrications.material_source_id=? 
				and sales_orders.id=?
				group by 
				sales_orders.id,
				budget_fabrics.id,
				style_fabrications.id,
				gmtsparts.name,
				item_accounts.item_description', [1,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->grey_qty=number_format($result->grey_fab_pur_req,'2','.',',');
				$result->fin_qty=number_format($result->fin_fab_pur_req,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->fab_pur_amount=number_format($result->fab_pur_amount,'2','.',',');
				array_push($datas,$result);
			}
		}
		else{
		$results=$this->style
		->selectRaw(
		'
		budget_fabrics.id as budget_fabric_id,
		style_fabrications.id as style_fabrication_id,
		gmtsparts.name as gmt_part_name,
		item_accounts.item_description as gmt_item_description,
		sum(budget_fabric_cons.amount) as fab_pur_amount,
		avg(budget_fabric_cons.rate) as rate,
		sum(budget_fabric_cons.fin_fab) as fin_fab_pur_req,
		sum(budget_fabric_cons.grey_fab) as grey_fab_pur_req'
		)
		->join('jobs', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->leftJoin('budgets', function($join)  {
		$join->on('budgets.job_id', '=', 'jobs.id');
		})
		->join('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		
		
		->join('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		})
		->join('budget_fabric_cons', function($join)  {
		$join->on('budget_fabric_cons.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
		})
		->join('budget_fabrics',function($join){
		$join->on('budget_fabrics.id','=','budget_fabric_cons.budget_fabric_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->join('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		})
		->join('item_accounts',function($join){
		$join->on('item_accounts.id','=','style_gmts.item_account_id');
		})
		->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
		})

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) use($company_id){
			return $q->whereIn('sales_orders.produced_company_id', $company_id);
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
			})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['style_fabrications.material_source_id','=',1]])
		->where([['sales_orders.order_status','!=',2]])
		
		->groupBy([
		'budget_fabrics.id',
		'style_fabrications.id',
		'gmtsparts.name',
		'item_accounts.item_description'
		])
		->get();
		foreach($results as $result)
			{
				if($result->fin_qty)
				{
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->grey_qty=number_format($result->grey_fab_pur_req,'2','.',',');
				$result->fin_qty=number_format($result->fin_fab_pur_req,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->fab_pur_amount=number_format($result->fab_pur_amount,'2','.',',');
				array_push($datas,$result);
				}
				
			}
		}
    	echo json_encode($datas);
    }


    public function getknit()
    {
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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
			avg(budget_fabric_prod_cons.rate) as rate,
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
		avg(budget_fabric_prod_cons.rate) as rate,
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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
			})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',10]])
		->where([['sales_orders.order_status','!=',2]])
		
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
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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
			/*$results = \DB::select('
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
			order by style_gmts.id,gmtsparts.id,budget_fabric_prods.id', [5,request('sale_order_id',0)]);*/

			$results = \DB::select('
			select budget_yarn_dyeings.id as budget_fabric_prod_id,
			budget_yarn_dyeings.budget_fabric_id,
			style_fabrications.id as style_fabrication_id,

			gmtsparts.name as gmt_part_name,
			item_accounts.item_description as gmt_item_description,
			budget_yarn_dyeing_cons.yarn_color_id as fabric_color_id,
			colors.name as fabric_color,
			--gmt_colors.name as gmt_color,
			--style_fabrication_stripes.id,
			--style_fabrication_stripes.measurment,
			--style_fabrication_stripes.feeder,
			--style_fabrication_stripes.is_dye_wash,
			sum(budget_yarn_dyeing_cons.bom_qty) as yarn_dyeing_qty,
			sum(budget_yarn_dyeing_cons.rate) as rate,
			sum(budget_yarn_dyeing_cons.amount) as yarn_dyeing_amount
			from 
			budget_yarn_dyeing_cons
			join style_fabrication_stripes on style_fabrication_stripes.id= budget_yarn_dyeing_cons.style_fabrication_stripe_id
			join style_colors on style_colors.id=style_fabrication_stripes.style_color_id
			left join sales_orders on sales_orders.id = budget_yarn_dyeing_cons.sales_order_id 
			left join budget_yarn_dyeings on budget_yarn_dyeings.id = budget_yarn_dyeing_cons.budget_yarn_dyeing_id 
			left join production_processes on production_processes.id = budget_yarn_dyeings.production_process_id
			left join  budget_fabrics on budget_fabrics.id=budget_yarn_dyeings.budget_fabric_id
			left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
			left join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
			left join item_accounts on item_accounts.id=style_gmts.item_account_id
			left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id 

			left join colors on colors.id=style_fabrication_stripes.color_id 
			left join colors gmt_colors  on gmt_colors.id=style_colors.color_id 
			where production_processes.production_area_id =? and sales_orders.id=?
			group by 
			budget_yarn_dyeings.id,
			budget_yarn_dyeings.budget_fabric_id,
			style_fabrications.id ,
			gmtsparts.id,
			style_gmts.id,
			gmtsparts.name,
			item_accounts.item_description,
			budget_yarn_dyeing_cons.yarn_color_id,
			colors.name
			--gmt_colors.name,
			--style_fabrication_stripes.id,
			--style_fabrication_stripes.measurment,
			--style_fabrication_stripes.feeder,
			--style_fabrication_stripes.is_dye_wash
			order by style_gmts.id,gmtsparts.id,budget_yarn_dyeings.id', [5,request('sale_order_id',0)]);

			
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
		/*$results=$this->style
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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
			})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
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
		->get();*/
		$results=$this->style
		->selectRaw(
		' 
		style_fabrications.id as style_fabrication_id,
		budget_yarn_dyeing_cons.yarn_color_id as fabric_color_id,
		colors.name as fabric_color,
		--gmt_colors.name as gmt_color,
		--style_fabrication_stripes.id,
		--style_fabrication_stripes.measurment,
		--style_fabrication_stripes.feeder,
		--style_fabrication_stripes.is_dye_wash,
		sum(budget_yarn_dyeing_cons.bom_qty) as yarn_dyeing_qty,
		sum(budget_yarn_dyeing_cons.rate) as rate,
		sum(budget_yarn_dyeing_cons.amount) as yarn_dyeing_amount'
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
		
		->leftJoin('budget_yarn_dyeing_cons', function($join)  {
		$join->on('budget_yarn_dyeing_cons.sales_order_id', '=', 'sales_orders.id');
		})
		->join('style_fabrication_stripes',function($join){
          $join->on('style_fabrication_stripes.id','=','budget_yarn_dyeing_cons.style_fabrication_stripe_id');
        })
        ->join('style_colors',function($join){
          $join->on('style_colors.id','=','style_fabrication_stripes.style_color_id');
        })

		->leftJoin('budget_yarn_dyeings', function($join)  {
		$join->on('budget_yarn_dyeings.id', '=', 'budget_yarn_dyeing_cons.budget_yarn_dyeing_id');
		})
		->leftJoin('production_processes', function($join)  {
		$join->on('production_processes.id', '=', 'budget_yarn_dyeings.production_process_id');
		})

		
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.id','=','budget_yarn_dyeings.budget_fabric_id');
		})
		->leftJoin('style_fabrications',function($join){
		$join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->leftJoin('colors',function($join){
		$join->on('colors.id','=','style_fabrication_stripes.color_id');
		})
		->join('colors as gmt_colors',function($join){
          $join->on('gmt_colors.id','=','style_colors.color_id');
        })

		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
			})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',5]])
		->where([['sales_orders.order_status','!=',2]])
		
		->groupBy([
		'style_fabrications.id',
		'budget_yarn_dyeing_cons.yarn_color_id',
		'colors.name',
		//'gmt_colors.name',
		//'style_fabrication_stripes.id',
		//'style_fabrication_stripes.measurment',
		//'style_fabrication_stripes.feeder',
		//'style_fabrication_stripes.is_dye_wash',
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
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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
			avg(budget_fabric_prod_cons.rate) as rate,
			sum(budget_fabric_prod_cons.amount) as amount,
			avg(budget_fabric_prod_cons.overhead_rate) as overhead_rate,
			sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
			
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
				$total_amount=$result->amount+$result->overhead_amount;
				$result->dye_charge_per_kg=0;
				if($result->qty){
					$result->dye_charge_per_kg=number_format($total_amount/$result->qty,'2','.',',');
				}			
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->total_amount=number_format($total_amount,'2','.',',');
				$result->overhead_amount=number_format($result->overhead_amount,'2','.',',');
				$result->overhead_rate=number_format($result->overhead_rate,'2','.',',');
				
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
		avg(budget_fabric_prod_cons.rate) as rate,
		sum(budget_fabric_prod_cons.amount) as amount,
		avg(budget_fabric_prod_cons.overhead_rate) as overhead_rate,
		sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
		'
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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
			})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->whereIn('production_processes.production_area_id',[20,30])
		->where([['sales_orders.order_status','!=',2]])
		
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
				$total_amount=$result->amount+$result->overhead_amount;
				$result->dye_charge_per_kg=number_format($total_amount/$result->qty,'2','.',',');
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->total_amount=number_format($total_amount,'2','.',',');

				$result->amount=number_format($result->amount,'2','.',',');
				$result->overhead_amount=number_format($result->overhead_amount,'2','.',',');
				$result->overhead_rate=number_format($result->overhead_rate,'2','.',',');
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
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

    	$datas=array();
    	$fabricDescription=$this->budget
    	->join('jobs', function($join)  {
		$join->on('jobs.id', '=', 'budgets.job_id');
		})
		->leftJoin('styles',function($join){
		$join->on('styles.id','=','jobs.style_id');
		})
		->leftJoin('sales_orders', function($join)  {
		$join->on('sales_orders.job_id', '=', 'jobs.id');
		})
		->leftJoin('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
		})
		->leftJoin('autoyarns',function($join){
		$join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
		})
		->leftJoin('autoyarnratios',function($join){
		$join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
		})
		->leftJoin('compositions',function($join){
		$join->on('compositions.id','=','autoyarnratios.composition_id');
		})
		->leftJoin('constructions',function($join){
		$join->on('constructions.id','=','autoyarns.construction_id');
		})
		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('company_id'), function ($q) {
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
			})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->when(request('sale_order_id'), function ($q) {
		return $q->where('sales_orders.id', '=',request('sale_order_id', 0));
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


		 $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->get(),'name','id'),'-Select-','');
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
			sum(budget_fabric_prod_cons.amount) as amount,
			avg(budget_fabric_prod_cons.overhead_rate) as overhead_rate,
			sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
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
				$total_amount=$result->amount+$result->overhead_amount;
				$result->aop_charge_per_kg=0;
				if ($result->qty) {
					$result->aop_charge_per_kg=number_format($total_amount/$result->qty,'2','.',',');
				}
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->total_amount=number_format($total_amount,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->overhead_amount=number_format($result->overhead_amount,'2','.',',');
				$result->overhead_rate=number_format($result->overhead_rate,'2','.',',');
				$result->shape=$fabricshape[$result->fabric_shape_id];
				$result->look=$fabriclooks[$result->fabric_look_id];
				$result->aoptype=$embelishmenttype[$result->embelishment_type_id];
				array_push($datas,$result);
			}
		}
		else{
		$results=$this->style
		->selectRaw(
		'style_fabrications.id as style_fabrication_id,
		style_fabrications.coverage, 
		style_fabrications.impression,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		style_fabrications.embelishment_type_id,
		sum(budget_fabric_prod_cons.bom_qty) as qty,
		sum(budget_fabric_prod_cons.rate) as rate,
		sum(budget_fabric_prod_cons.amount) as amount,
		avg(budget_fabric_prod_cons.overhead_rate) as overhead_rate,
		sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
		'
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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
			})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',25]])
		->where([['sales_orders.order_status','!=',2]])
		
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
				$total_amount=$result->amount+$result->overhead_amount;
				$result->aop_charge_per_kg=number_format($total_amount/$result->qty,'2','.',',');
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->total_amount=number_format($total_amount,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->overhead_amount=number_format($result->overhead_amount,'2','.',',');
				$result->overhead_rate=number_format($result->overhead_rate,'2','.',',');
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
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
		})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',28]])
		->where([['sales_orders.order_status','!=',2]])
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
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
		})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',35]])
		->where([['sales_orders.order_status','!=',2]])
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
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

    	$datas=array();
		 
		 $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');

		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select item_accounts.item_description as gmt_item,embelishment_types.name as emb_type,gmtsparts.name as gmt_part_name,style_embelishments.embelishment_size_id,sum(budget_emb_cons.req_cons) as qty,avg(budget_emb_cons.rate) as rate, sum(budget_emb_cons.amount) as amount ,
			    avg(budget_emb_cons.overhead_rate) as overhead_rate,
			    sum(budget_emb_cons.overhead_amount) as overhead_amount
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
				$total_amount=$result->amount+$result->overhead_amount;
				$screen_print_charge=($total_amount/$result->qty)*12;
				$result->screen_print_charge=number_format($screen_print_charge,'2','.',',');
				$result->total_amount=number_format($total_amount,'2','.',',');
				$result->overhead_rate=number_format($result->overhead_rate,'2','.',',');
				$result->overhead_amount=number_format($result->overhead_amount,'2','.',',');
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
		sum(budget_emb_cons.amount) as amount,
		avg(budget_emb_cons.overhead_rate) as overhead_rate,
	    sum(budget_emb_cons.overhead_amount) as overhead_amount
		'

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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
		})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',45]])
		->where([['sales_orders.order_status','!=',2]])
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
				$total_amount=$result->amount+$result->overhead_amount;
				$screen_print_charge=($total_amount/$result->qty)*12;
				$result->screen_print_charge=number_format($screen_print_charge,'2','.',',');
				$result->total_amount=number_format($total_amount,'2','.',',');
				$result->overhead_rate=number_format($result->overhead_rate,'2','.',',');
				$result->overhead_amount=number_format($result->overhead_amount,'2','.',',');
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
    	
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
		})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',50]])
		->where([['sales_orders.order_status','!=',2]])
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
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
		})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',51]])
		->where([['sales_orders.order_status','!=',2]])
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
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
		})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',58]])
		->where([['sales_orders.order_status','!=',2]])
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
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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
		return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
		})
		->when(request('job_no'), function ($q) {
		return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
		})
		->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
		})
		->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
		})
		->when(request('order_status'), function ($q) {
		return $q->where('sales_orders.order_status', '=',request('order_status', 0));
		})
		->where([['production_processes.production_area_id','=',60]])
		->where([['sales_orders.order_status','!=',2]])
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

    	$company_id=explode(',',request('company_id', 0));
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$job_no=request('job_no', 0);
		$order_status=request('order_status',0);
		$company=null;
		$buyer=null;
		$style=null;
		$job=null;
		$orderstatus=null;
		if($company_id){
			$company=" and sales_orders.produced_company_id in(".implode(',',$company_id).") ";
		}
		//echo $company; die;
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($job_no){
			$job=" and jobs.job_no like %$job_no% ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}

    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

    	$datas=array();
		$othercosthead=array_prepend(config('bprs.othercosthead'),'-Select-','');
		if(request('sale_order_id',0))
		{
			$results = \DB::select('
			select budget_others.id, 
			budget_others.cost_head_id,
			budget_others.amount,
			gmt_item_ratio.item_ratio,
			sum(sales_order_gmt_color_sizes.qty) as qty
			from budget_others 
			left join budgets on budgets.id = budget_others.budget_id  
			left join jobs on jobs.id = budgets.job_id
			left join sales_orders on sales_orders.job_id = jobs.id 
			left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id

			left join (
			select 
			budget_others.id, 
			sum(style_gmts.gmt_qty)  as item_ratio 
			from 
			budget_others
			left join budgets on budgets.id = budget_others.budget_id  
			left join jobs on jobs.id = budgets.job_id
			join styles on styles.id=jobs.style_id
			join style_gmts on style_gmts.style_id=styles.id
			group by budget_others.id
			) gmt_item_ratio on gmt_item_ratio.id=budget_others.id

			where budget_others.cost_head_id !=15  and sales_orders.id=?
			group by 
			budget_others.id, 
			budget_others.cost_head_id,
			budget_others.amount,
			gmt_item_ratio.item_ratio', [request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				
				$result->name=$othercosthead[$result->cost_head_id];
				$result->amount=number_format(($result->amount/12)*($result->qty/$result->item_ratio),'2','.',',');
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
			->join('budgets', function($join)  {
			$join->on('budgets.style_id', '=', 'styles.id');
			})
			->join('sales_orders', function($join)  {
			$join->on('sales_orders.job_id', '=', 'jobs.id');
			})
			->join('sales_order_gmt_color_sizes', function($join)  {
			$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
			})
			->join('budget_others', function($join)  {
			$join->on('budget_others.budget_id', '=', 'budgets.id');
			})
			->leftJoin(\DB::raw("(select m.cost_head_id,sum(m.amount) as amount  from 
			(
			select budget_others.id, 
			budget_others.cost_head_id,
			sum ((sales_order_gmt_color_sizes.qty/gmt_item_ratio.item_ratio) *(budget_others.amount/12)) as amount
			from budget_others 
			left join budgets on budgets.id = budget_others.budget_id  
			left join jobs on jobs.id = budgets.job_id
			left join styles on styles.id = jobs.style_id
			left join sales_orders on sales_orders.job_id = jobs.id 
			left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id

			left join (
			select 
			budget_others.id, 
			sum(style_gmts.gmt_qty)  as item_ratio 
			from 
			budget_others
			left join budgets on budgets.id = budget_others.budget_id  
			left join jobs on jobs.id = budgets.job_id
			join styles on styles.id=jobs.style_id
			join style_gmts on style_gmts.style_id=styles.id
			group by budget_others.id
			) gmt_item_ratio on gmt_item_ratio.id=budget_others.id

			where 
			budget_others.cost_head_id !=15
			and sales_orders.ship_date>='".$first_date."' 
			and sales_orders.ship_date<='".$last_date."'
			$company $buyer $style $job $orderstatus 
			group by budget_others.id, budget_others.cost_head_id,budget_others.amount,gmt_item_ratio.item_ratio
			) m group by m.cost_head_id) budgetOther"), "budgetOther.cost_head_id", "=", "budget_others.cost_head_id")
			->when(request('buyer_id'), function ($q) {
			return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
			})
			->when(request('style_ref'), function ($q) {
			return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
			})
			->when(request('company_id'), function ($q) {
			return $q->where('sales_orders.produced_company_id', '=', request('company_id', 0));
			})
			->when(request('job_no'), function ($q) {
			return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
			})
			->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
			})
			->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
			})
			->when(request('order_status'), function ($q) {
			return $q->where('sales_orders.order_status', '=',request('order_status', 0));
			})
			->where([['sales_orders.order_status','!=',2]])
			->where([['budget_others.cost_head_id','!=',15]])
			->groupBy([
			'budget_others.cost_head_id',
			'budgetOther.amount'
			])
			/*->toSql();
			dd($results);*/
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

    	$company_name=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
    	$company_id=explode(',',request('company_id', 0));
    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));

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


    	$color_size_matrix=0;
		if(request('sale_order_id',0))
		{
			$color_size_matrix=1;
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
			return Template::loadView('Report.ColorSizeMatrix', ['colors'=>$colors,'sizes'=>$sizes,'datas'=>$datas,'color_total'=>$color_total,'size_total'=>$size_total,'country'=>$country,  'country_colors'=>$country_colors,'country_sizes'=>$country_sizes,'country_datas'=>$country_datas,'country_color_total'=>$country_color_total,'country_size_total'=>$country_size_total,'color_size_matrix'=>$color_size_matrix]);
		}
		else
		{
			    $capacity=$this->getcapacity($company_id);
			    $lineinfo=$this->getcapacityline($company_id);
			    $color_size_matrix=0;
				$months=($month_to-$month_from)+1;
				$rest_of_months=$months%3;
				$round_months=$months-$rest_of_months;
				$slots=$round_months/3;
				$header=array();
				$rangeArr=array();
				for($i=1;$i<=$slots;$i++)
				{
					$last_month=($month_from+3)-1;
					$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
					$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($last_month,2,"0",STR_PAD_LEFT).'-01'));
					$last_date = date("Y-m-t", strtotime($first_date_last_month));
					$month_from+=3;
					$collection=$this->getsalesorderstatus($company_id,$first_date,$last_date);
					$mkt_data=$this->getmarketingcost($company_id,$first_date,$last_date);

					$data=$collection->map(function ($collection) use($mkt_data,$capacity) {
						$collection->cap_qty=$capacity->qty;
						$collection->cap_amount=$capacity->amount;

						$collection->ned_qty=$collection->qty-$capacity->qty;
						$collection->ned_amount=$collection->amount-$capacity->amount;

						$collection->mkt_qty=$mkt_data->qty;
						$collection->mkt_amount=$mkt_data->amount;
						return $collection;
					})->first();

					$rangeArr[$i]=$data;
					$header[$i]['month_from']=date('M',strtotime($first_date));
					$header[$i]['month_to']=date('M',strtotime($last_date));
				}
				if($rest_of_months){
					$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
					$last_month=($month_from+$rest_of_months)-1;
					$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($last_month,2,"0",STR_PAD_LEFT).'-01'));
					$last_date = date("Y-m-t", strtotime($first_date_last_month));
					$collection=$this->getsalesorderstatus($company_id,$first_date,$last_date);
					$mkt_data=$this->getmarketingcost($company_id,$first_date,$last_date);
                    $data=$collection->map(function ($collection) use($mkt_data,$capacity) {
					    $collection->cap_qty=$capacity->qty;
						$collection->cap_amount=$capacity->amount;

						$collection->ned_qty=$collection->qty-$capacity->qty;
						$collection->ned_amount=$collection->amount-$capacity->amount;
						
						$collection->mkt_qty=$mkt_data->qty;
						$collection->mkt_amount=$mkt_data->amount;
					return $collection;
					})->first();

					$rangeArr[$i]=$data;
					$header[$i]['month_from']=date('M',strtotime($first_date));
					$header[$i]['month_to']=date('M',strtotime($last_date));
				}
				//=============================
				$comRangeArr=array();
				$comHeader=array();
				$comlineinfo=array();
				for($j=0;$j<count($company_id);$j++){
					$month_from=request('month_from', 0);
					$month_to=request('month_to', 0);
					$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
					$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
					$last_date = date("Y-m-t", strtotime($first_date_last_month));

					$months=($month_to-$month_from)+1;
					$rest_of_months=$months%3;
					$round_months=$months-$rest_of_months;
					$slots=$round_months/3;

					$company=[$company_id[$j]];
					$capacity=$this->getcapacity($company);
					$comlineinfo[$company_id[$j]]=$this->getcapacityline($company);
					for($i=1;$i<=$slots;$i++)
					{
						$last_month=($month_from+3)-1;
						$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
						$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($last_month,2,"0",STR_PAD_LEFT).'-01'));
						$last_date = date("Y-m-t", strtotime($first_date_last_month));
						$month_from+=3;
						$collection=$this->getsalesorderstatus($company,$first_date,$last_date);
						$mkt_data=$this->getmarketingcost($company,$first_date,$last_date);

						$data=$collection->map(function ($collection) use($mkt_data,$capacity) {
							$collection->cap_qty=$capacity->qty;
							$collection->cap_amount=$capacity->amount;

							$collection->ned_qty=$collection->qty-$capacity->qty;
							$collection->ned_amount=$collection->amount-$capacity->amount;

							$collection->mkt_qty=$mkt_data->qty;
							$collection->mkt_amount=$mkt_data->amount;
							return $collection;
						})->first();

						$comRangeArr[$company_id[$j]][$i]=$data;
						$comHeader[$company_id[$j]][$i]['month_from']=date('M',strtotime($first_date));
						$comHeader[$company_id[$j]][$i]['month_to']=date('M',strtotime($last_date));
					}
					if($rest_of_months){
						$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
						$last_month=($month_from+$rest_of_months)-1;
						$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($last_month,2,"0",STR_PAD_LEFT).'-01'));
						$last_date = date("Y-m-t", strtotime($first_date_last_month));
						$collection=$this->getsalesorderstatus($company,$first_date,$last_date);
						$mkt_data=$this->getmarketingcost($company,$first_date,$last_date);
	                    $data=$collection->map(function ($collection) use($mkt_data,$capacity) {
						    $collection->cap_qty=$capacity->qty;
							$collection->cap_amount=$capacity->amount;

							$collection->ned_qty=$collection->qty-$capacity->qty;
							$collection->ned_amount=$collection->amount-$capacity->amount;
							
							$collection->mkt_qty=$mkt_data->qty;
							$collection->mkt_amount=$mkt_data->amount;
						return $collection;
						})->first();

						$comRangeArr[$company_id[$j]][$i]=$data;
						$comHeader[$company_id[$j]][$i]['month_from']=date('M',strtotime($first_date));
						$comHeader[$company_id[$j]][$i]['month_to']=date('M',strtotime($last_date));
					}
			    }

			return Template::loadView('Report.ColorSizeMatrix', ['colors'=>$colors,'sizes'=>$sizes,'datas'=>$datas,'color_total'=>$color_total,'size_total'=>$size_total,'country'=>$country,  'country_colors'=>$country_colors,'country_sizes'=>$country_sizes,'country_datas'=>$country_datas,'country_color_total'=>$country_color_total,'country_size_total'=>$country_size_total,'color_size_matrix'=>$color_size_matrix,'rangeArr'=>$rangeArr,'header'=>$header,'comRangeArr'=>$comRangeArr,'comHeader'=>$comHeader,'company_name'=>$company_name,'lineinfo'=>$lineinfo,'comlineinfo'=>$comlineinfo]);
		}
    }

    private function getsalesorderstatus($company_id,$first_date,$last_date)
    {
    	$results=$this->salesordergmtcolorsize
			->selectRaw(
			'sum(sales_order_gmt_color_sizes.qty) as qty,
			sum(sales_order_gmt_color_sizes.amount) as amount'
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
				$join->whereNull('styles.deleted_at');
			})
			->when(request('buyer_id'), function ($q) {
				return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
			})
			->when(request('style_ref'), function ($q) {
			return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
			})
			->when(request('company_id'), function ($q) use($company_id){
			return $q->whereIn('sales_orders.produced_company_id', $company_id);
			})
			->when(request('job_no'), function ($q) {
			return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
			})
			->when($first_date, function ($q) use($first_date){
			return $q->where('sales_orders.ship_date', '>=',$first_date);
			})
			->when($last_date, function ($q) use($last_date){
			return $q->where('sales_orders.ship_date', '<=',$last_date);
			})
			->when(request('order_status'), function ($q) {
			return $q->where('sales_orders.order_status', '=',request('order_status', 0));
			})
			->where([['sales_orders.order_status','!=',2]])
			->get();
			return $results;
    }

    private function getmarketingcost($company_id,$first_date,$last_date)
    {
    	$results=$this->style
    	->selectRaw(
			'sum(mkt_costs.offer_qty) as qty,
			avg(mkt_cost_quote_prices.quote_price) as quote_price'
			)
			->leftJoin('mkt_costs', function($join)  {
				$join->on('mkt_costs.style_id', '=', 'styles.id');
			})
			->leftJoin('mkt_cost_quote_prices', function($join)  {
				$join->on('mkt_cost_quote_prices.mkt_cost_id', '=', 'mkt_costs.id');
			})
			
			->leftJoin('jobs', function($join)  {
				$join->on('jobs.style_id', '=', 'styles.id');
			})

			->when(request('buyer_id'), function ($q) {
				return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
			})
			->when(request('style_ref'), function ($q) {
			return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
			})
			->when(request('company_id'), function ($q) use($company_id){
			return $q->whereIn('mkt_costs.company_id', $company_id);
			})
			->when(request('job_no'), function ($q) {
			return $q->where('jobs.job_no', 'like', '%'.request('job_no', 0).'%');
			})
			->when($first_date, function ($q) use($first_date){
			return $q->where('mkt_costs.est_ship_date', '>=',$first_date);
			})
			->when($last_date, function ($q) use($last_date){
			return $q->where('mkt_costs.est_ship_date', '<=',$last_date);
			})
			->whereNull('mkt_cost_quote_prices.refused_date')
			->whereNull('mkt_cost_quote_prices.cancel_date')
			->get()
			->map(function ($results) {
               $results->amount=$results->qty*$results->quote_price;
               return $results;
            })->first();
            return $results;
    }

    private function getcapacity($company_id)
    {
		$capacity = \DB::select("
		select 
		(sum(subsections.qty)*3*25) as qty,
		(sum(subsections.amount)*3*25) as amount
		from 
		subsections 
		left join company_subsections on company_subsections.subsection_id = subsections.id
		where 
		company_subsections.company_id in(".implode(',',$company_id).")  and subsections.is_treat_sewing_line=1 and subsections.projected_line_id=0 and 
		subsections.deleted_at is null and company_subsections.deleted_at is null and subsections.status_id=1
		");
		$capacity=collect($capacity)->first();
		return $capacity;
    }
    private function getcapacityline($company_id)
    {
		$capacity = \DB::select("
		select subsections.projected_line_id, subsections.status_id
		from 
		subsections 
		left join company_subsections on company_subsections.subsection_id = subsections.id
		where 
		company_subsections.company_id in(".implode(',',$company_id).")  and 
		subsections.is_treat_sewing_line=1  and 
		subsections.deleted_at is null and 
		company_subsections.deleted_at is null
		");
		$capacity=collect($capacity);
		$ttl=$capacity->count();
		$projected=$capacity->filter(function ($value) {
			if($value->projected_line_id){
              return $value ;
			}
        })->count();
        $active=$capacity->filter(function ($value) {
			if($value->status_id){
              return $value ;
			}
        })->count();
        $inactive=$capacity->filter(function ($value) {
			if(!$value->status_id){
              return $value ;
			}
        })->count();
       $linfo= ['ttl'=>$ttl,'projected'=>$projected,'active'=>$active,'inactive'=>$inactive];
       return $linfo;
		
	}
	
	public function getFileSrc(){
		$filesrc=$this->style
		->leftJoin('style_file_uploads',function($join){
		   $join->on('style_file_uploads.style_id','=','styles.id');
		 })
		
		->where([['style_id','=',request('style_id',0)]])
		->get([
			'styles.id as style_id',
			'styles.style_ref',
			'style_file_uploads.*'
		]);
		echo json_encode($filesrc);
	}

	public function getconsdzn()
    {
    	$company_id=explode(',',request('company_id', 0));
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$job_no=request('job_no', 0);
		$order_status=request('order_status',0);
		$company=null;
		$buyer=null;
		$style=null;
		$job=null;
		if($company_id){
			$company=" and sales_orders.produced_company_id in(".implode(',',$company_id).") ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($job_no){
			$job=" and jobs.job_no like %$job_no% ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}

    	$year=request('year', 0);
		$month_from=request('month_from', 0);
		$month_to=request('month_to', 0);
		$first_date=date('Y-m-d',strtotime($year.'-'.str_pad($month_from,2,"0",STR_PAD_LEFT).'-01'));
		$first_date_last_month=date('Y-m-d',strtotime($year.'-'.str_pad($month_to,2,"0",STR_PAD_LEFT).'-01'));
		$last_date = date("Y-m-t", strtotime($first_date_last_month));
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
				select

				style_gmts.item_account_id as gmt_item_id,
				style_fabrications.id as style_fabrication_id,
				gmtsparts.name as gmt_part_name,
				item_accounts.item_description as gmt_item_description,
				sum(budget_fabric_cons.grey_fab) as grey_fab,
				sum(budget_fabric_cons.fin_fab) as fin_fab,
				avg(budget_fabric_cons.cons) as cons,
				avg(budget_fabric_cons.req_cons) as req_cons,
				avg(budget_fabric_cons.process_loss) as process_loss,
				avg(cad_cons.cons) as cad_cons,
				avg(budget_fabric_cons.unlayable_per) as unlayable_per,
				salesOrd.qty as qty,
				salesOrd.plan_cut_qty as plan_cut_qty,
				salesOrd.extra_percent as extra_percent
				


				from sales_orders 
				join jobs on jobs.id=sales_orders.job_id
				join styles on styles.id=jobs.style_id
				join budgets on budgets.job_id=jobs.id
				join budget_fabrics on budget_fabrics.budget_id=budgets.id 
				join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
				join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
				join item_accounts on item_accounts.id=style_gmts.item_account_id
				left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id  
				join budget_fabric_cons on budget_fabrics.id=budget_fabric_cons.budget_fabric_id 

				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id
				and sales_orders.id=sales_order_gmt_color_sizes.sale_order_id

				left join cads on cads.style_id=jobs.style_id
				left join cad_cons on cad_cons.cad_id=cads.id 
				and  cad_cons.style_fabrication_id=budget_fabrics.style_fabrication_id
				and  cad_cons.style_gmt_color_size_id=sales_order_gmt_color_sizes.style_gmt_color_size_id

				left join(
				select
				sales_orders.id,
				sum(sales_order_gmt_color_sizes.qty) as qty,
				sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
				avg(sales_order_gmt_color_sizes.extra_percent) as extra_percent
				from sales_orders 
				join jobs on jobs.id=sales_orders.job_id
				join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
				where sales_orders.id='.request('sale_order_id',0).'
				group by sales_orders.id

				) salesOrd on salesOrd.id=sales_orders.id
				where sales_orders.id=? 
				group by 
				style_gmts.item_account_id,
				style_fabrications.id,
				gmtsparts.name,
				item_accounts.item_description,
				style_gmts.id,
				gmtsparts.id,
				salesOrd.qty,
				salesOrd.plan_cut_qty,
				salesOrd.extra_percent
				order by 
				style_gmts.id,
				gmtsparts.id', [request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$cad_req=$result->cad_cons*($result->plan_cut_qty/12);
				$req_cons=($result->grey_fab/$result->plan_cut_qty)*12;
				$cons=($result->fin_fab/$result->plan_cut_qty)*12;
	            $result->fab_des=$fabDropdown[$result->style_fabrication_id];
	            $result->req_cons=number_format($req_cons,'4','.',',');
	            $result->cons=number_format($cons,'4','.',',');
	            $result->fin_fab=number_format($result->fin_fab,'4','.',',');
	            $result->grey_fab=number_format($result->grey_fab,'4','.',',');
	            $result->extra_percent=number_format($result->extra_percent,'2','.',',')." %";
	            $result->process_loss=number_format($result->process_loss,'2','.',',')." %";
	            $result->cad_req=number_format($cad_req,'2','.',',');
	            $result->unlayable_per=number_format($result->unlayable_per,'2','.',',')." %";
	            array_push($datas,$result);
			}

    	}
    	else
    	{
		
			
		
    	}

    	$dd=array('total'=>1,'rows'=>$datas,'footer'=>array(0=>array('yarn_des'=>'','yarn_qty'=>'','yarn_amount'=>'','rate'=>'')));

    	echo json_encode($dd);
    }

    public function getbep()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=request('company_id',0);

    	/*->leftJoin(\DB::raw("(select acc_beps.company_id,sum(acc_bep_entries.amount)  as amount from acc_beps 
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
		group by keycontrols.company_id) bepunit"), "bepunit.company_id", "=", "companies.id")*/

		$earnings=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
			//$join->where('acc_beps.start_date', '>=',$date_to);
			//$join->where('acc_beps.end_date', '<=',$date_to);
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
			$earnings->per_day=$earnings->amount/26;
			return $earnings;

		});

		$fexpense=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
			//$join->where('acc_beps.start_date', '>=',$date_to);
			//$join->where('acc_beps.end_date', '<=',$date_to);
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		/*->join('acc_chart_sub_groups', function($join) use($date_to) {
			$join->on('acc_chart_sub_groups.id', '=', 'acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})*/
		->where([['acc_bep_entries.expense_type_id','=',2]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($fexpense){
			$fexpense->per_day=$fexpense->amount/26;
			return $fexpense;

		});

		$vexpense=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
			//$join->where('acc_beps.start_date', '>=',$date_to);
			//$join->where('acc_beps.end_date', '<=',$date_to);
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		/*->join('acc_chart_sub_groups', function($join) use($date_to) {
			$join->on('acc_chart_sub_groups.id', '=', 'acc_chart_ctrl_heads.acc_chart_sub_group_id');
		})*/
		->where([['acc_bep_entries.expense_type_id','=',1]])
		->where([['acc_beps.company_id','=',request('company_id',0)]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($vexpense){
			$vexpense->per_day=$vexpense->amount/26;
			return $vexpense;

		});
		return Template::loadView('Report.OrderInHandBepDetails', ['fexpense'=>$fexpense,'vexpense'=>$vexpense,'earnings'=>$earnings]);
		//echo json_encode($fexpense);

    }

    public function getLCSc()
	{
		$sale_order_id=request('sale_order_id',0);
		  $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
          $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
          $contractNature = array_prepend(config('bprs.contractNature'),'-Select-','');


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
}