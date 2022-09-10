<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;

use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Account\AccBepRepository;
use Illuminate\Support\Carbon;

class BudgetAndCostingComparisonController extends Controller
{
	private $style;
	private $company;
	private $buyer;
	private $buyernature;
	private $itemaccount;
	private $budget;
	private $embelishmenttype;
	private $salesordergmtcolorsize;
	private $user;
	private $accbep;
	private $exch_rate;
	private $no_of_days;
	public function __construct(StyleRepository $style,CompanyRepository $company,BuyerRepository $buyer,BuyerNatureRepository $buyernature ,ItemAccountRepository $itemaccount,BudgetRepository $budget,EmbelishmentTypeRepository $embelishmenttype,SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,UserRepository $user, AccBepRepository $accbep)
    {
		$this->style                     = $style;
		$this->company                   = $company;
		$this->buyer                     = $buyer;
		$this->buyernature 				 = $buyernature;
		$this->itemaccount               = $itemaccount;
		$this->budget                    = $budget;
		$this->embelishmenttype          = $embelishmenttype;
		$this->salesordergmtcolorsize    = $salesordergmtcolorsize;
		$this->user						 = $user;
		$this->accbep                    = $accbep;
		$this->exch_rate                 = 82;
		$this->no_of_days                = 26;
		$this->middleware('auth');
		$this->middleware('permission:view.budgetandcostingcomparison',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'',0);
		$status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
		$from_date=date('Y-m')."-01";
        //$to=Carbon::parse($from_date);
        //$to->addDays(364);
        $to_date=$to=date('Y-m-t',strtotime($from_date));
        return Template::loadView('Report.BudgetAndCostingComparison',['company'=>$company,'buyer'=>$buyer,'status'=>$status,'from'=>$from_date,'to'=>$to_date]);
    }
    

    public function formatOne(){
    	/*$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
    	echo $rows=$this->reportData()
    	->map(function($rows) use($itemcomplexity){
    		$costing_unit=12;
    		$courier_amount=($rows->courier_rate/$costing_unit)*$rows->qty;
	        $lab_amount=($rows->lab_rate/$costing_unit)*$rows->qty;
	        $insp_amount=($rows->insp_rate/$costing_unit)*$rows->qty;
	        $opa_amount=($rows->opa_rate/$costing_unit)*$rows->qty;
	        $dep_amount=($rows->dep_rate/$costing_unit)*$rows->qty;
	        $coc_amount=($rows->coc_rate/$costing_unit)*$rows->qty;
	        $ict_amount=($rows->ict_rate/$costing_unit)*$rows->qty;

	        $other_amount=$courier_amount+$lab_amount+$insp_amount+$opa_amount+$dep_amount+ $coc_amount+ $ict_amount;

            $rows->courier_amount=$other_amount;

            $freight_amount=($rows->freight_rate/$costing_unit)*$rows->qty;
            $rows->freight_amount=$freight_amount;

            $cm_amount=($rows->cm_rate/$costing_unit)*$rows->qty;
            $rows->cm_amount=$cm_amount;

            $commi_amount=($rows->commi_rate/100)*$rows->amount;
            $rows->commi_amount=$commi_amount;

            $commmercial=$rows->yarn_amount+$rows->trim_amount+$rows->kniting_amount+$rows->yarn_dying_amount+$rows->weaving_amount+$rows->dying_amount+$rows->aop_amount+$rows->burn_out_amount+$rows->finishing_amount+$rows->washing_amount+$rows->printing_amount+$rows->emb_amount+$rows->spemb_amount+$rows->gmt_dyeing_amount+$rows->gmt_washing_amount;

	        $commer_amount=($rows->commer_rate/100)*$commmercial;
	        $rows->commer_amount=$commer_amount;

	        $total_amount=$commmercial+$other_amount+$freight_amount+$cm_amount+$commi_amount+$commer_amount;
	        $rows->total_amount=$total_amount;
	        $total_profit=$rows->amount-$total_amount;
	        $rows->total_profit=$total_profit;

	        $rows->total_profit_per=0;
	        if($rows->amount)
	        {
		        $rows->total_profit_per=(($total_profit/$rows->amount)*100);
	        }

	        $rows->qty_dzn=$rows->qty/$costing_unit;

	        $rows->mkt_yarn_amount=$rows->mkt_yarn_amount*($rows->qty_dzn);
	        $rows->mkt_trim_amount=$rows->mkt_trim_amount*($rows->qty_dzn);
	        $rows->mkt_yd_amount=$rows->mkt_yd_amount*($rows->qty_dzn);
	        $rows->mkt_knit_amount=$rows->mkt_knit_amount*($rows->qty_dzn);
	        $rows->mkt_weav_amount=$rows->mkt_weav_amount*($rows->qty_dzn);
	        $rows->mkt_dye_amount=$rows->mkt_dye_amount*($rows->qty_dzn);
	        $rows->mkt_aop_amount=$rows->mkt_aop_amount*($rows->qty_dzn);
	        $rows->mkt_burn_amount=$rows->mkt_burn_amount*($rows->qty_dzn);
	        $rows->mkt_fab_finsh_amount=$rows->mkt_fab_finsh_amount*($rows->qty_dzn);
	        $rows->mkt_fab_wash_amount=$rows->mkt_fab_wash_amount*($rows->qty_dzn);
	        $rows->mkt_print_amount=$rows->mkt_print_amount*($rows->qty_dzn);
	        $rows->mkt_embel_amount=$rows->mkt_embel_amount*($rows->qty_dzn);
	        $rows->mkt_spembel_amount=$rows->mkt_spembel_amount*($rows->qty_dzn);
	        $rows->mkt_gmt_dye_amount=$rows->mkt_gmt_dye_amount*($rows->qty_dzn);
	        $rows->mkt_gmt_wash_amount=$rows->mkt_gmt_wash_amount*($rows->qty_dzn);

	        $rows->mkt_courier_amount=$rows->mkt_courier_amount*($rows->qty_dzn);
	        $rows->mkt_lab_amount=$rows->mkt_courier_amount*($rows->qty_dzn);
	        $rows->mkt_insp_amount=$rows->mkt_insp_amount*($rows->qty_dzn);
	        $rows->mkt_opa_amount=$rows->mkt_opa_amount*($rows->qty_dzn);
	        $rows->mkt_dep_amount=$rows->mkt_dep_amount*($rows->qty_dzn);
	        $rows->mkt_coc_amount=$rows->mkt_coc_amount*($rows->qty_dzn);
	        $rows->mkt_ict_amount=$rows->mkt_ict_amount*($rows->qty_dzn);
	        $other_amount=$rows->mkt_courier_amount+$rows->mkt_lab_amount+$rows->mkt_insp_amount+$rows->mkt_opa_amount+$rows->mkt_dep_amount+ $rows->mkt_coc_amount+ $rows->mkt_ict_amount;

	        $rows->mkt_other_amount=$other_amount;
	        $rows->mkt_frei_amount=$rows->mkt_frei_amount*($rows->qty_dzn);
	        $rows->mkt_cm_amount=$rows->mkt_cm_amount*($rows->qty_dzn);
	        $rows->mkt_commer_amount=$rows->mkt_commer_amount*($rows->qty_dzn);
	        $rows->mkt_commi_amount=$rows->mkt_commi_amount*($rows->qty_dzn);

	        $mktTotalAmount=$rows->mkt_yarn_amount+$rows->mkt_trim_amount+$rows->mkt_yd_amount+$rows->mkt_knit_amount+$rows->mkt_weav_amount+$rows->mkt_dye_amount+$rows->mkt_aop_amount+$rows->mkt_burn_amount+$rows->mkt_fab_finsh_amount+$rows->mkt_fab_wash_amount+$rows->mkt_print_amount+$rows->mkt_embel_amount+$rows->mkt_spembel_amount+$rows->mkt_gmt_dye_amount+$rows->mkt_gmt_wash_amount+$rows->gmt_washing_amount+$rows->mkt_other_amount+$rows->mkt_frei_amount+$rows->mkt_cm_amount+$rows->mkt_commer_amount+$rows->mkt_commi_amount;
	        $rows->mkt_total_amount=$mktTotalAmount;

	        $rows->mkt_total_profit=$rows->amount-$rows->mkt_total_amount;

	        $rows->mkt_total_profit_per=0;
	        if($rows->amount){
		        $rows->mkt_total_profit_per=(($rows->mkt_total_profit/$rows->amount)*100);
	        }

	        $rows->yarn_vari=$rows->mkt_yarn_amount-$rows->yarn_amount;
	        $rows->trim_vari=$rows->mkt_trim_amount-$rows->trim_amount;
	        $rows->yd_vari=$rows->mkt_yd_amount-$rows->yarn_dying_amount;
	        $rows->kniting_vari=$rows->mkt_knit_amount-$rows->kniting_amount;
	        $rows->weav_vari=$rows->mkt_weav_amount-$rows->weaving_amount;
	        $rows->dye_vari=$rows->mkt_dye_amount-$rows->dying_amount;
	        $rows->aop_vari=$rows->mkt_aop_amount-$rows->aop_amount;
	        $rows->burn_out_vari=$rows->mkt_burn_amount-$rows->burn_out_amount;
	        $rows->finish_vari=$rows->mkt_fab_finsh_amount-$rows->finishing_amount;
	        $rows->wash_vari=$rows->mkt_fab_wash_amount-$rows->washing_amount;
	        $rows->print_vari=$rows->mkt_print_amount-$rows->printing_amount;
	        $rows->emb_vari=$rows->mkt_embel_amount-$rows->emb_amount;
	        $rows->spemb_vari=$rows->mkt_spembel_amount-$rows->spemb_amount;
	        $rows->gmt_dye_vari=$rows->mkt_gmt_dye_amount-$rows->gmt_dyeing_amount;
	        $rows->gmt_wash_vari=$rows->mkt_gmt_wash_amount-$rows->gmt_washing_amount;
	        $rows->gmt_other_vari=$rows->mkt_other_amount-$rows->courier_amount;
	        $rows->gmt_frei_vari=$rows->mkt_frei_amount-$rows->freight_amount;
	        $rows->gmt_cm_vari=$rows->mkt_cm_amount-$rows->cm_amount;
	        $rows->gmt_commi_vari=$rows->mkt_commi_amount-$rows->commi_amount;
	        $rows->gmt_commer_vari=$rows->mkt_commer_amount-$rows->commer_amount;

	        $rows->total_amount_vari=$rows->mkt_total_amount-$rows->total_amount;
	        $rows->total_profit_vari=$rows->mkt_total_profit-$rows->total_profit;
	        $rows->total_profit_per_vari=$rows->mkt_total_profit_per-$rows->total_profit_per;

	        $rows->sale_order_receive_date=date('d-M-Y',strtotime($rows->sale_order_receive_date));
    		$rows->delivery_date=date('d-M-Y',strtotime($rows->ship_date));
    		$rows->delivery_month=date('M',strtotime($rows->ship_date));
    		$rows->item_complexity=$itemcomplexity[$rows->item_complexity];

            $rows->qty=number_format($rows->qty,0);
            $rows->rate=number_format($rows->rate,2);
            $rows->amount=number_format($rows->amount,2);

            $rows->mkt_yarn_amount=number_format($rows->mkt_yarn_amount,2);
            $rows->yarn_amount=number_format($rows->yarn_amount,2);
            $rows->yarn_vari=number_format($rows->yarn_vari,2);

            $rows->mkt_trim_amount=number_format($rows->mkt_trim_amount,2);
            $rows->trim_amount=number_format($rows->trim_amount,2);
            $rows->trim_vari=number_format($rows->trim_vari,2);

            $rows->mkt_yd_amount=number_format($rows->mkt_yd_amount,2);
            $rows->yarn_dying_amount=number_format($rows->yarn_dying_amount,2);
            $rows->yd_vari=number_format($rows->yd_vari,2);

            $rows->mkt_knit_amount=number_format($rows->mkt_knit_amount,2);
            $rows->kniting_amount=number_format($rows->kniting_amount,2);
            $rows->kniting_vari=number_format($rows->kniting_vari,2);

            $rows->mkt_dye_amount=number_format($rows->mkt_dye_amount,2);
            $rows->dying_amount=number_format($rows->dying_amount,2);
            $rows->dye_vari=number_format($rows->dye_vari,2);

            $rows->mkt_aop_amount=number_format($rows->mkt_aop_amount,2);
            $rows->aop_amount=number_format($rows->aop_amount,2);
            $rows->aop_vari=number_format($rows->aop_vari,2);

            $rows->mkt_burn_amount=number_format($rows->mkt_burn_amount,2);
            $rows->burn_out_amount=number_format($rows->burn_out_amount,2);
            $rows->burn_out_vari=number_format($rows->burn_out_vari,2);

            $rows->mkt_fab_finsh_amount=number_format($rows->mkt_fab_finsh_amount,2);
            $rows->finishing_amount=number_format($rows->finishing_amount,2);
            $rows->finish_vari=number_format($rows->finish_vari,2);

            $rows->mkt_fab_wash_amount=number_format($rows->mkt_fab_wash_amount,2);
            $rows->washing_amount=number_format($rows->washing_amount,2);
            $rows->wash_vari=number_format($rows->wash_vari,2);

            $rows->mkt_print_amount=number_format($rows->mkt_print_amount,2);
            $rows->printing_amount=number_format($rows->printing_amount,2);
            $rows->print_vari=number_format($rows->print_vari,2);

            $rows->mkt_embel_amount=number_format($rows->mkt_embel_amount,2);
            $rows->emb_amount=number_format($rows->emb_amount,2);
            $rows->emb_vari=number_format($rows->emb_vari,2);

            $rows->mkt_spembel_amount=number_format($rows->mkt_spembel_amount,2);
            $rows->spemb_amount=number_format($rows->spemb_amount,2);
            $rows->spemb_vari=number_format($rows->spemb_vari,2);

            $rows->mkt_gmt_dye_amount=number_format($rows->mkt_gmt_dye_amount,2);
            $rows->gmt_dyeing_amount=number_format($rows->gmt_dyeing_amount,2);
            $rows->gmt_dye_vari=number_format($rows->gmt_dye_vari,2);

            $rows->mkt_gmt_wash_amount=number_format($rows->mkt_gmt_wash_amount,2);
            $rows->gmt_washing_amount=number_format($rows->gmt_washing_amount,2);
            $rows->gmt_wash_vari=number_format($rows->gmt_wash_vari,2);

            $rows->mkt_other_amount=number_format($rows->mkt_other_amount,2);
            $rows->courier_amount=number_format($rows->courier_amount,2);
            $rows->gmt_other_vari=number_format($rows->gmt_other_vari,2);

            $rows->mkt_frei_amount=number_format($rows->mkt_frei_amount,2);
            $rows->freight_amount=number_format($rows->freight_amount,2);
            $rows->gmt_frei_vari=number_format($rows->gmt_frei_vari,2);

            $rows->mkt_cm_amount=number_format($rows->mkt_cm_amount,2);
            $rows->cm_amount=number_format($rows->cm_amount,2);
            $rows->gmt_cm_vari=number_format($rows->gmt_cm_vari,2);

            $rows->mkt_commi_amount=number_format($rows->mkt_commi_amount,2);
            $rows->commi_amount=number_format($rows->commi_amount,2);
            $rows->gmt_commi_vari=number_format($rows->gmt_commi_vari,2);

            $rows->mkt_commer_amount=number_format($rows->mkt_commer_amount,2);
            $rows->commer_amount=number_format($rows->commer_amount,2);
            $rows->gmt_commer_vari=number_format($rows->gmt_commer_vari,2);

            $rows->mkt_total_amount=number_format($rows->mkt_total_amount,2);
            $rows->total_amount=number_format($rows->total_amount,2);
            $rows->total_amount_vari=number_format($rows->total_amount_vari,2);

            $rows->mkt_total_profit=number_format($rows->mkt_total_profit,2);
            $rows->total_profit=number_format($rows->total_profit,2);
            $rows->total_profit_vari=number_format($rows->total_profit_vari,2);

            $rows->mkt_total_profit_per=number_format($rows->mkt_total_profit_per,2);
            $rows->total_profit_per=number_format($rows->total_profit_per,2);
            $rows->total_profit_per_vari=number_format($rows->total_profit_per_vari,2);
    		return $rows;
    	})->toJson();*/
    }

    public function formatTow(){
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);
    	$rows=$this->reportData()
    	->map(function($rows) use($itemcomplexity,$buyinghouses){
    		$costing_unit=12;
    		$rows->dying_amount=$rows->dying_amount+$rows->dying_overhead_amount;
    		$rows->aop_amount=$rows->aop_amount+$rows->aop_overhead_amount;
    		$rows->printing_amount=$rows->printing_amount+$rows->printing_overhead_amount;
    		$courier_amount=($rows->courier_rate/$costing_unit)*$rows->qty;
	        $lab_amount=($rows->lab_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
	        $insp_amount=($rows->insp_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
	        $opa_amount=($rows->opa_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
	        $dep_amount=($rows->dep_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
	        $coc_amount=($rows->coc_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
	        $ict_amount=($rows->ict_rate/$costing_unit)*($rows->qty/$rows->item_ratio);

	        $other_amount=$courier_amount+$lab_amount+$insp_amount+$opa_amount+$dep_amount+ $coc_amount+ $ict_amount;

            $rows->courier_amount=$other_amount;

            $freight_amount=($rows->freight_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
            $rows->freight_amount=$freight_amount;

            //$cm_amount=($rows->cm_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
            $cm_amount=$rows->cm_rate*$rows->qty;
            $rows->cm_amount=$cm_amount;

            $commi_amount=($rows->commi_rate/100)*$rows->amount;
            $rows->commi_amount=$commi_amount;


            $commmercial=
            $rows->yarn_amount+
            $rows->trim_amount+
            $rows->fab_pur_amount+
            $rows->kniting_amount+
            $rows->yarn_dying_amount+
            $rows->weaving_amount+
            $rows->dying_amount+
            $rows->aop_amount+
            $rows->burn_out_amount+
            $rows->finishing_amount+
            $rows->washing_amount+
            $rows->printing_amount+
            $rows->emb_amount+
            $rows->spemb_amount+
            $rows->gmt_dyeing_amount+
            $rows->gmt_washing_amount;

	        $commer_amount=($rows->commer_rate/100)*$commmercial;
	        $rows->commer_amount=$commer_amount;

	        $total_amount=$commmercial+$other_amount+$freight_amount+$cm_amount+$commi_amount+$commer_amount;
	        $rows->total_amount=$total_amount;
	        $total_profit=$rows->amount-$total_amount;
	        $rows->total_profit=$total_profit;

	        $rows->total_profit_per=0;
	        if($rows->amount){
		        $rows->total_profit_per=(($total_profit/$rows->amount)*100);
	        }

	        $rows->qty_dzn=($rows->qty/$rows->item_ratio)/$costing_unit;
	        $rows->mkt_yarn_amount=$rows->mkt_yarn_amount*($rows->qty_dzn);
	        $rows->mkt_fab_pur_amount=$rows->mkt_fab_pur_amount*($rows->qty_dzn);
	        $rows->mkt_trim_amount=$rows->mkt_trim_amount*($rows->qty_dzn);
	        $rows->mkt_yd_amount=$rows->mkt_yd_amount*($rows->qty_dzn);
	        $rows->mkt_knit_amount=$rows->mkt_knit_amount*($rows->qty_dzn);
	        $rows->mkt_weav_amount=$rows->mkt_weav_amount*($rows->qty_dzn);
	        $rows->mkt_dye_amount=$rows->mkt_dye_amount*($rows->qty_dzn);
	        $rows->mkt_aop_amount=$rows->mkt_aop_amount*($rows->qty_dzn);
	        $rows->mkt_burn_amount=$rows->mkt_burn_amount*($rows->qty_dzn);
	        $rows->mkt_fab_finsh_amount=$rows->mkt_fab_finsh_amount*($rows->qty_dzn);
	        $rows->mkt_fab_wash_amount=$rows->mkt_fab_wash_amount*($rows->qty_dzn);
	        $rows->mkt_print_amount=$rows->mkt_print_amount*($rows->qty_dzn);
	        $rows->mkt_embel_amount=$rows->mkt_embel_amount*($rows->qty_dzn);
	        $rows->mkt_spembel_amount=$rows->mkt_spembel_amount*($rows->qty_dzn);
	        $rows->mkt_gmt_dye_amount=$rows->mkt_gmt_dye_amount*($rows->qty_dzn);
	        $rows->mkt_gmt_wash_amount=$rows->mkt_gmt_wash_amount*($rows->qty_dzn);

	        $rows->mkt_courier_amount=$rows->mkt_courier_amount*($rows->qty_dzn);
	        $rows->mkt_lab_amount=$rows->mkt_courier_amount*($rows->qty_dzn);
	        $rows->mkt_insp_amount=$rows->mkt_insp_amount*($rows->qty_dzn);
	        $rows->mkt_opa_amount=$rows->mkt_opa_amount*($rows->qty_dzn);
	        $rows->mkt_dep_amount=$rows->mkt_dep_amount*($rows->qty_dzn);
	        $rows->mkt_coc_amount=$rows->mkt_coc_amount*($rows->qty_dzn);
	        $rows->mkt_ict_amount=$rows->mkt_ict_amount*($rows->qty_dzn);
	        $other_amount=$rows->mkt_courier_amount+$rows->mkt_lab_amount+$rows->mkt_insp_amount+$rows->mkt_opa_amount+$rows->mkt_dep_amount+ $rows->mkt_coc_amount+ $rows->mkt_ict_amount;

	        $rows->mkt_other_amount=$other_amount;
	        $rows->mkt_frei_amount=$rows->mkt_frei_amount*($rows->qty_dzn);
	        $rows->mkt_cm_amount=$rows->mkt_cm_amount*($rows->qty);
	        $rows->mkt_commer_amount=$rows->mkt_commer_amount*($rows->qty_dzn);
	        $rows->mkt_commi_amount=$rows->mkt_commi_amount*($rows->qty_dzn);

	        $mktTotalAmount=$rows->mkt_yarn_amount+$rows->mkt_fab_pur_amount+$rows->mkt_trim_amount+$rows->mkt_yd_amount+$rows->mkt_knit_amount+$rows->mkt_weav_amount+$rows->mkt_dye_amount+$rows->mkt_aop_amount+$rows->mkt_burn_amount+$rows->mkt_fab_finsh_amount+$rows->mkt_fab_wash_amount+$rows->mkt_print_amount+$rows->mkt_embel_amount+$rows->mkt_spembel_amount+$rows->mkt_gmt_dye_amount+$rows->mkt_gmt_wash_amount+$rows->gmt_washing_amount+$rows->mkt_other_amount+$rows->mkt_frei_amount+$rows->mkt_cm_amount+$rows->mkt_commer_amount+$rows->mkt_commi_amount;
	        $rows->mkt_total_amount=$mktTotalAmount;

	        $rows->mkt_total_profit=$rows->amount-$rows->mkt_total_amount;

	        $rows->mkt_total_profit_per=0;
	        if($rows->amount){
		        $rows->mkt_total_profit_per=(($rows->mkt_total_profit/$rows->amount)*100);
	        }

	        $fin_fab_cons=0;
            if($rows->plan_cut_qty){
                $fin_fab_cons=($rows->fin_fab/($rows->plan_cut_qty/$rows->item_ratio))*12;
            }
            $rows->fin_fab_cons=$fin_fab_cons;

			//$style['fin_fab_cons']=number_format($fin_fab_cons,'2','.',',');

	        $rows->yarn_vari=$rows->mkt_yarn_amount-$rows->yarn_amount;
	        $rows->fabpur_vari=$rows->mkt_fab_pur_amount-$rows->fab_pur_amount;
	        $rows->trim_vari=$rows->mkt_trim_amount-$rows->trim_amount;
	        $rows->yd_vari=$rows->mkt_yd_amount-$rows->yarn_dying_amount;
	        $rows->kniting_vari=$rows->mkt_knit_amount-$rows->kniting_amount;
	        $rows->weav_vari=$rows->mkt_weav_amount-$rows->weaving_amount;
	        $rows->dye_vari=$rows->mkt_dye_amount-$rows->dying_amount;
	        $rows->aop_vari=$rows->mkt_aop_amount-$rows->aop_amount;
	        $rows->burn_out_vari=$rows->mkt_burn_amount-$rows->burn_out_amount;
	        $rows->finish_vari=$rows->mkt_fab_finsh_amount-$rows->finishing_amount;
	        $rows->wash_vari=$rows->mkt_fab_wash_amount-$rows->washing_amount;
	        $rows->print_vari=$rows->mkt_print_amount-$rows->printing_amount;
	        $rows->emb_vari=$rows->mkt_embel_amount-$rows->emb_amount;
	        $rows->spemb_vari=$rows->mkt_spembel_amount-$rows->spemb_amount;
	        $rows->gmt_dye_vari=$rows->mkt_gmt_dye_amount-$rows->gmt_dyeing_amount;
	        $rows->gmt_wash_vari=$rows->mkt_gmt_wash_amount-$rows->gmt_washing_amount;
	        $rows->gmt_other_vari=$rows->mkt_other_amount-$rows->courier_amount;
	        $rows->gmt_frei_vari=$rows->mkt_frei_amount-$rows->freight_amount;
	        $rows->gmt_cm_vari=$rows->mkt_cm_amount-$rows->cm_amount;
	        $rows->gmt_commi_vari=$rows->mkt_commi_amount-$rows->commi_amount;
	        $rows->gmt_commer_vari=$rows->mkt_commer_amount-$rows->commer_amount;
	        $rows->total_amount_vari=$rows->mkt_total_amount-$rows->total_amount;

	        /*$rows->total_profit_vari=$rows->mkt_total_profit-$rows->total_profit;
	        $rows->total_profit_per_vari=$rows->mkt_total_profit_per-$rows->total_profit_per;*/
	        $rows->total_profit_vari= $rows->total_profit - ($rows->mkt_total_profit);
	        $rows->total_profit_per_vari=$rows->total_profit_per - ($rows->mkt_total_profit_per);

	        $rows->fin_fab_cons_vari=$rows->mkt_fab_fin_cons-$fin_fab_cons;

	        
            


	        $rows->sale_order_receive_date=date('d-M-Y',strtotime($rows->sale_order_receive_date));
    		$rows->delivery_date=date('d-M-Y',strtotime($rows->ship_date));
    		$rows->delivery_month=date('M',strtotime($rows->ship_date));
			$rows->item_complexity=$itemcomplexity[$rows->item_complexity];
			
			$rows->agent_name=	isset($buyinghouses[$rows->buying_agent_id])? $buyinghouses[$rows->buying_agent_id]:'';

			$rows->buying_agent_name=$rows->agent_name." ". $rows->contact;

            /*$rows->qty=number_format($rows->qty,0);
            $rows->rate=number_format($rows->rate,2);
            $rows->amount=number_format($rows->amount,2);

            $rows->mkt_yarn_amount=number_format($rows->mkt_yarn_amount,2);
            $rows->yarn_amount=number_format($rows->yarn_amount,2);
            $rows->yarn_vari=number_format($rows->yarn_vari,2);

            $rows->mkt_trim_amount=number_format($rows->mkt_trim_amount,2);
            $rows->trim_amount=number_format($rows->trim_amount,2);
            $rows->trim_vari=number_format($rows->trim_vari,2);

            $rows->mkt_yd_amount=number_format($rows->mkt_yd_amount,2);
            $rows->yarn_dying_amount=number_format($rows->yarn_dying_amount,2);
            $rows->yd_vari=number_format($rows->yd_vari,2);

            $rows->mkt_knit_amount=number_format($rows->mkt_knit_amount,2);
            $rows->kniting_amount=number_format($rows->kniting_amount,2);
            $rows->kniting_vari=number_format($rows->kniting_vari,2);

            $rows->mkt_dye_amount=number_format($rows->mkt_dye_amount,2);
            $rows->dying_amount=number_format($rows->dying_amount,2);
            $rows->dye_vari=number_format($rows->dye_vari,2);

            $rows->mkt_aop_amount=number_format($rows->mkt_aop_amount,2);
            $rows->aop_amount=number_format($rows->aop_amount,2);
            $rows->aop_vari=number_format($rows->aop_vari,2);

            $rows->mkt_burn_amount=number_format($rows->mkt_burn_amount,2);
            $rows->burn_out_amount=number_format($rows->burn_out_amount,2);
            $rows->burn_out_vari=number_format($rows->burn_out_vari,2);

            $rows->mkt_fab_finsh_amount=number_format($rows->mkt_fab_finsh_amount,2);
            $rows->finishing_amount=number_format($rows->finishing_amount,2);
            $rows->finish_vari=number_format($rows->finish_vari,2);

            $rows->mkt_fab_wash_amount=number_format($rows->mkt_fab_wash_amount,2);
            $rows->washing_amount=number_format($rows->washing_amount,2);
            $rows->wash_vari=number_format($rows->wash_vari,2);

            $rows->mkt_print_amount=number_format($rows->mkt_print_amount,2);
            $rows->printing_amount=number_format($rows->printing_amount,2);
            $rows->print_vari=number_format($rows->print_vari,2);

            $rows->mkt_embel_amount=number_format($rows->mkt_embel_amount,2);
            $rows->emb_amount=number_format($rows->emb_amount,2);
            $rows->emb_vari=number_format($rows->emb_vari,2);

            $rows->mkt_spembel_amount=number_format($rows->mkt_spembel_amount,2);
            $rows->spemb_amount=number_format($rows->spemb_amount,2);
            $rows->spemb_vari=number_format($rows->spemb_vari,2);

            $rows->mkt_gmt_dye_amount=number_format($rows->mkt_gmt_dye_amount,2);
            $rows->gmt_dyeing_amount=number_format($rows->gmt_dyeing_amount,2);
            $rows->gmt_dye_vari=number_format($rows->gmt_dye_vari,2);

            $rows->mkt_gmt_wash_amount=number_format($rows->mkt_gmt_wash_amount,2);
            $rows->gmt_washing_amount=number_format($rows->gmt_washing_amount,2);
            $rows->gmt_wash_vari=number_format($rows->gmt_wash_vari,2);

            $rows->mkt_other_amount=number_format($rows->mkt_other_amount,2);
            $rows->courier_amount=number_format($rows->courier_amount,2);
            $rows->gmt_other_vari=number_format($rows->gmt_other_vari,2);

            $rows->mkt_frei_amount=number_format($rows->mkt_frei_amount,2);
            $rows->freight_amount=number_format($rows->freight_amount,2);
            $rows->gmt_frei_vari=number_format($rows->gmt_frei_vari,2);

            $rows->mkt_cm_amount=number_format($rows->mkt_cm_amount,2);
            $rows->cm_amount=number_format($rows->cm_amount,2);
            $rows->gmt_cm_vari=number_format($rows->gmt_cm_vari,2);

            $rows->mkt_commi_amount=number_format($rows->mkt_commi_amount,2);
            $rows->commi_amount=number_format($rows->commi_amount,2);
            $rows->gmt_commi_vari=number_format($rows->gmt_commi_vari,2);

            $rows->mkt_commer_amount=number_format($rows->mkt_commer_amount,2);
            $rows->commer_amount=number_format($rows->commer_amount,2);
            $rows->gmt_commer_vari=number_format($rows->gmt_commer_vari,2);

            $rows->mkt_total_amount=number_format($rows->mkt_total_amount,2);
            $rows->total_amount=number_format($rows->total_amount,2);
            $rows->total_amount_vari=number_format($rows->total_amount_vari,2);

            $rows->mkt_total_profit=number_format($rows->mkt_total_profit,2);
            $rows->total_profit=number_format($rows->total_profit,2);
            $rows->total_profit_vari=number_format($rows->total_profit_vari,2);

            $rows->mkt_total_profit_per=number_format($rows->mkt_total_profit_per,2);
            $rows->total_profit_per=number_format($rows->total_profit_per,2);
            $rows->total_profit_per_vari=number_format($rows->total_profit_per_vari,2);*/
    		return $rows;
    	});

        return Template::loadView('Report.BudgetAndCostingComparisonMatrix',['rows'=>$rows]);
    }

    public function reportData() {
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
		jobs.id as job_id,
		jobs.job_no,
		companies.id as company_id,
		companies.code as company_code,
		produced_company.code as produced_company_code,
		sales_orders.id,
		sales_orders.sale_order_no,
		sales_orders.produced_company_id,
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
		budgetDyeing.overhead_amount as dying_overhead_amount,
		budgetAop.aop_amount,
		budgetAop.overhead_amount as aop_overhead_amount,
		burnOut.burn_out_amount,
		budgetFabFinishing.finishing_amount,
		budgetFabWashing.washing_amount,
		budgetYarn.yarn_amount,
		budgetPrinting.printing_amount,
		budgetPrinting.overhead_amount as printing_overhead_amount,
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
		mkt_costs.offer_qty,
		mkt_cost_yarns.mkt_yarn_amount,
		mkt_cost_fab_purs.mkt_fab_pur_amount,
		mkt_cost_trims.mkt_trim_amount,
		mkt_cost_fabric_prods_yd.amount as mkt_yd_amount,
		mkt_cost_fabric_prods_knitting.amount as mkt_knit_amount,
		mkt_cost_fabric_prods_weaving.amount as mkt_weav_amount,
		mkt_cost_fabric_prods_dyeing.amount as mkt_dye_amount,
		mkt_cost_fabric_prods_aop.amount as mkt_aop_amount,
		mkt_cost_fabric_prods_burn_out.amount as mkt_burn_amount,
		mkt_cost_fabric_prods_fab_fini.amount as mkt_fab_finsh_amount,
		mkt_cost_fabric_prods_fab_wash.amount as mkt_fab_wash_amount,
		mkt_cost_embs_print.amount as mkt_print_amount,
		mkt_cost_embs_emb.amount as mkt_embel_amount,
		mkt_cost_embs_spemb.amount as mkt_spembel_amount,
		mkt_cost_embs_gmt_dyeing.amount as mkt_gmt_dye_amount,
		mkt_cost_embs_gmt_washing.amount as mkt_gmt_wash_amount,
		mkt_costs_courier.amount as mkt_courier_amount,
		mkt_costs_lab.amount as mkt_lab_amount,
		mkt_costs_insp.amount as mkt_insp_amount,
		mkt_costs_frei.amount as mkt_frei_amount,
		mkt_costs_opa.amount as mkt_opa_amount,
		mkt_costs_dep.amount as mkt_dep_amount,
		mkt_costs_coc.amount as mkt_coc_amount,
		mkt_costs_ict.amount as mkt_ict_amount,
		mkt_cost_cms.amount as mkt_cm_amount,
		mkt_cost_commercials.amount as mkt_commer_amount,
		mkt_cost_commissions.amount as mkt_commi_amount,
		budgetFab.grey_fab,
		budgetFab.fin_fab,
		mkt_cost_fabrics.cons as mkt_fab_fin_cons,
		mkt_cost_fabrics.req_cons as mkt_fab_grey_cons,
		gmt_item_ratio.item_ratio,
		explcsc.lc_sc_no
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
		->leftJoin('teammembers as teamleaders', function($join)  {
			$join->on('styles.teammember_id', '=', 'teamleaders.id');
		})
		->leftJoin('users as teamleadernames', function($join)  {
			$join->on('teamleadernames.id', '=', 'teamleaders.user_id');
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
		->join('companies as produced_company', function($join)  {
			$join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
		})
		->leftJoin('sales_order_gmt_color_sizes', function($join)  {
		$join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
		$join->whereNull('sales_order_gmt_color_sizes.deleted_at');
		})
		->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.sale_order_id,sum(budget_trim_cons.amount) as trim_amount FROM budget_trim_cons right join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_trim_cons.sales_order_gmt_color_size_id   group by sales_order_gmt_color_sizes.sale_order_id) budgetTrim"), "budgetTrim.sale_order_id", "=", "sales_orders.id")
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

		/*->leftJoin(\DB::raw("(select sales_orders.id as sale_order_id,production_processes.production_area_id,sum(budget_fabric_prod_cons.amount) as yarn_dying_amount
		from budget_fabric_prod_cons 
		left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
		left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
		left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
		where production_processes.production_area_id =5
		group by sales_orders.id,production_processes.production_area_id) budgetYarnDyeing"), "budgetYarnDyeing.sale_order_id", "=", "sales_orders.id")*/
		
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

		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_emb_cons.amount) as printing_amount,sum(budget_emb_cons.overhead_amount) as overhead_amount 
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

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_costs.offer_qty)  as offer_qty from mkt_costs   group by mkt_costs.style_id) mkt_costs'), "mkt_costs.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_yarns.amount)  as mkt_yarn_amount from mkt_cost_yarns left join mkt_costs on mkt_costs.id = mkt_cost_yarns.mkt_cost_id   group by mkt_costs.style_id) mkt_cost_yarns'), "mkt_cost_yarns.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_trims.amount)  as mkt_trim_amount from mkt_cost_trims left join mkt_costs on mkt_costs.id = mkt_cost_trims.mkt_cost_id   group by mkt_costs.style_id) mkt_cost_trims'), "mkt_cost_trims.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select 
			mkt_costs.style_id, 
			sum(mkt_cost_fabrics.amount)  as mkt_fab_pur_amount 
			from mkt_cost_fabrics 
			join mkt_costs on mkt_costs.id = mkt_cost_fabrics.mkt_cost_id   
			join style_fabrications on style_fabrications.id = mkt_cost_fabrics.style_fabrication_id
			where  style_fabrications.material_source_id=1
			group by mkt_costs.style_id) mkt_cost_fab_purs'), "mkt_cost_fab_purs.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_fabric_prods.amount)  as amount from mkt_cost_fabric_prods 
			left join mkt_costs on mkt_costs.id = mkt_cost_fabric_prods.mkt_cost_id
			left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id 
		where production_processes.production_area_id =5   
			group by mkt_costs.style_id) mkt_cost_fabric_prods_yd'), "mkt_cost_fabric_prods_yd.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_fabric_prods.amount)  as amount from mkt_cost_fabric_prods 
			left join mkt_costs on mkt_costs.id = mkt_cost_fabric_prods.mkt_cost_id
			left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id 
		where production_processes.production_area_id =10   
			group by mkt_costs.style_id) mkt_cost_fabric_prods_knitting'), "mkt_cost_fabric_prods_knitting.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_fabric_prods.amount)  as amount from mkt_cost_fabric_prods 
			left join mkt_costs on mkt_costs.id = mkt_cost_fabric_prods.mkt_cost_id
			left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id 
		where production_processes.production_area_id =15   
			group by mkt_costs.style_id) mkt_cost_fabric_prods_weaving'), "mkt_cost_fabric_prods_weaving.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_fabric_prods.amount)  as amount from mkt_cost_fabric_prods 
			left join mkt_costs on mkt_costs.id = mkt_cost_fabric_prods.mkt_cost_id
			left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id 
		where production_processes.production_area_id =20   
			group by mkt_costs.style_id) mkt_cost_fabric_prods_dyeing'), "mkt_cost_fabric_prods_dyeing.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_fabric_prods.amount)  as amount from mkt_cost_fabric_prods 
			left join mkt_costs on mkt_costs.id = mkt_cost_fabric_prods.mkt_cost_id
			left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id 
		where production_processes.production_area_id =25   
			group by mkt_costs.style_id) mkt_cost_fabric_prods_aop'), "mkt_cost_fabric_prods_aop.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_fabric_prods.amount)  as amount from mkt_cost_fabric_prods 
			left join mkt_costs on mkt_costs.id = mkt_cost_fabric_prods.mkt_cost_id
			left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id 
		where production_processes.production_area_id =28   
			group by mkt_costs.style_id) mkt_cost_fabric_prods_burn_out'), "mkt_cost_fabric_prods_burn_out.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_fabric_prods.amount)  as amount from mkt_cost_fabric_prods 
			left join mkt_costs on mkt_costs.id = mkt_cost_fabric_prods.mkt_cost_id
			left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id 
		where production_processes.production_area_id =30   
			group by mkt_costs.style_id) mkt_cost_fabric_prods_fab_fini'), "mkt_cost_fabric_prods_fab_fini.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_fabric_prods.amount)  as amount from mkt_cost_fabric_prods 
			left join mkt_costs on mkt_costs.id = mkt_cost_fabric_prods.mkt_cost_id
			left join production_processes on production_processes.id=mkt_cost_fabric_prods.production_process_id 
		where production_processes.production_area_id =35   
			group by mkt_costs.style_id) mkt_cost_fabric_prods_fab_wash'), "mkt_cost_fabric_prods_fab_wash.style_id", "=", "styles.id")


		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_embs.amount)  as amount from mkt_cost_embs 
			left join mkt_costs on mkt_costs.id = mkt_cost_embs.mkt_cost_id

			left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
		    left join embelishments on embelishments.id=style_embelishments.embelishment_id

			left join production_processes on production_processes.id=embelishments.production_process_id 
		where production_processes.production_area_id =45   
			group by mkt_costs.style_id) mkt_cost_embs_print'), "mkt_cost_embs_print.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_embs.amount)  as amount from mkt_cost_embs 
			left join mkt_costs on mkt_costs.id = mkt_cost_embs.mkt_cost_id

			left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
		    left join embelishments on embelishments.id=style_embelishments.embelishment_id

			left join production_processes on production_processes.id=embelishments.production_process_id 
		where production_processes.production_area_id =50   
			group by mkt_costs.style_id) mkt_cost_embs_emb'), "mkt_cost_embs_emb.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_embs.amount)  as amount from mkt_cost_embs 
			left join mkt_costs on mkt_costs.id = mkt_cost_embs.mkt_cost_id

			left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
		    left join embelishments on embelishments.id=style_embelishments.embelishment_id

			left join production_processes on production_processes.id=embelishments.production_process_id 
		where production_processes.production_area_id =51   
			group by mkt_costs.style_id) mkt_cost_embs_spemb'), "mkt_cost_embs_spemb.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_embs.amount)  as amount from mkt_cost_embs 
			left join mkt_costs on mkt_costs.id = mkt_cost_embs.mkt_cost_id

			left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
		    left join embelishments on embelishments.id=style_embelishments.embelishment_id

			left join production_processes on production_processes.id=embelishments.production_process_id 
		where production_processes.production_area_id =58  
			group by mkt_costs.style_id) mkt_cost_embs_gmt_dyeing'), "mkt_cost_embs_gmt_dyeing.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_embs.amount)  as amount from mkt_cost_embs 
			left join mkt_costs on mkt_costs.id = mkt_cost_embs.mkt_cost_id

			left join style_embelishments on style_embelishments.id=mkt_cost_embs.style_embelishment_id
		    left join embelishments on embelishments.id=style_embelishments.embelishment_id

			left join production_processes on production_processes.id=embelishments.production_process_id 
		where production_processes.production_area_id =60  
			group by mkt_costs.style_id) mkt_cost_embs_gmt_washing'), "mkt_cost_embs_gmt_washing.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_others.amount)  as amount from mkt_cost_others left join mkt_costs on mkt_costs.id = mkt_cost_others.mkt_cost_id  where mkt_cost_others.cost_head_id =1 group by mkt_costs.style_id) mkt_costs_courier'), "mkt_costs_courier.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_others.amount)  as amount from mkt_cost_others left join mkt_costs on mkt_costs.id = mkt_cost_others.mkt_cost_id  where mkt_cost_others.cost_head_id =5 group by mkt_costs.style_id) mkt_costs_lab'), "mkt_costs_lab.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_others.amount)  as amount from mkt_cost_others left join mkt_costs on mkt_costs.id = mkt_cost_others.mkt_cost_id  where mkt_cost_others.cost_head_id =10 group by mkt_costs.style_id) mkt_costs_insp'), "mkt_costs_insp.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_others.amount)  as amount from mkt_cost_others left join mkt_costs on mkt_costs.id = mkt_cost_others.mkt_cost_id  where mkt_cost_others.cost_head_id =15 group by mkt_costs.style_id) mkt_costs_frei'), "mkt_costs_frei.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_others.amount)  as amount from mkt_cost_others left join mkt_costs on mkt_costs.id = mkt_cost_others.mkt_cost_id  where mkt_cost_others.cost_head_id =20 group by mkt_costs.style_id) mkt_costs_opa'), "mkt_costs_opa.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_others.amount)  as amount from mkt_cost_others left join mkt_costs on mkt_costs.id = mkt_cost_others.mkt_cost_id  where mkt_cost_others.cost_head_id =25 group by mkt_costs.style_id) mkt_costs_dep'), "mkt_costs_dep.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_others.amount)  as amount from mkt_cost_others left join mkt_costs on mkt_costs.id = mkt_cost_others.mkt_cost_id  where mkt_cost_others.cost_head_id =30 group by mkt_costs.style_id) mkt_costs_coc'), "mkt_costs_coc.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_others.amount)  as amount from mkt_cost_others left join mkt_costs on mkt_costs.id = mkt_cost_others.mkt_cost_id  where mkt_cost_others.cost_head_id =35 group by mkt_costs.style_id) mkt_costs_ict'), "mkt_costs_ict.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, avg(mkt_cost_cms.cm_per_pcs)  as amount from mkt_cost_cms left join mkt_costs on mkt_costs.id = mkt_cost_cms.mkt_cost_id   group by mkt_costs.style_id) mkt_cost_cms'), "mkt_cost_cms.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_commercials.amount)  as amount from mkt_cost_commercials left join mkt_costs on mkt_costs.id = mkt_cost_commercials.mkt_cost_id   group by mkt_costs.style_id) mkt_cost_commercials'), "mkt_cost_commercials.style_id", "=", "styles.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_commissions.amount)  as amount from mkt_cost_commissions left join mkt_costs on mkt_costs.id = mkt_cost_commissions.mkt_cost_id   group by mkt_costs.style_id) mkt_cost_commissions'), "mkt_cost_commissions.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select sales_order_gmt_color_sizes.sale_order_id,sum(budget_fabric_cons.grey_fab) as grey_fab,sum(budget_fabric_cons.fin_fab) as fin_fab  
		from budget_fabric_cons 
		left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id
		left join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
		where budget_fabric_cons.deleted_at is null
		group by sales_order_gmt_color_sizes.sale_order_id) budgetFab'), "budgetFab.sale_order_id", "=", "sales_orders.id")

		->leftJoin(\DB::raw('(select mkt_costs.style_id, sum(mkt_cost_fabric_cons.cons)  as cons,sum(mkt_cost_fabric_cons.req_cons) as req_cons from mkt_cost_fabric_cons 
			left join mkt_cost_fabrics on mkt_cost_fabrics.id = mkt_cost_fabric_cons.mkt_cost_fabric_id 
			left join mkt_costs on mkt_costs.id = mkt_cost_fabrics.mkt_cost_id   group by mkt_costs.style_id) mkt_cost_fabrics'), "mkt_cost_fabrics.style_id", "=", "styles.id")
		->join(\DB::raw('(select style_gmts.style_id, sum(style_gmts.gmt_qty)  as item_ratio from style_gmts   group by style_gmts.style_id) gmt_item_ratio'), "gmt_item_ratio.style_id", "=", "styles.id")
		->leftJoin(\DB::raw('(select exp_lc_scs.lc_sc_no,sales_orders.sale_order_no,exp_pi_orders.sales_order_id from exp_pi_orders
		join sales_orders on sales_orders.id=exp_pi_orders.sales_order_id
		join exp_pis on exp_pis.id=exp_pi_orders.exp_pi_id
		join exp_lc_sc_pis on exp_lc_sc_pis.exp_pi_id=exp_pis.id
		join exp_lc_scs on exp_lc_scs.id=exp_lc_sc_pis.exp_lc_sc_id) explcsc'), "explcsc.sales_order_id", "=", "sales_orders.id")

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
		->when(request('style_id'), function ($q) {
		return $q->where('styles.id', '=',request('style_id', 0));
		})
		->when(request('job_id'), function ($q) {
		return $q->where('jobs.id', '=',request('job_id', 0));
		})
		->when(request('budget_id'), function ($q) {
		return $q->where('budgets.id', '=',request('budget_id', 0));
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
		'jobs.id',
		'companies.id',
		'companies.code',
		'produced_company.code',
		'sales_orders.id',
		'sales_orders.sale_order_no',
		'sales_orders.produced_company_id',
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
		'mkt_costs.offer_qty',
		'mkt_cost_yarns.mkt_yarn_amount',
		'mkt_cost_fab_purs.mkt_fab_pur_amount',
		'mkt_cost_trims.mkt_trim_amount',
		'mkt_cost_fabric_prods_yd.amount',
		'mkt_cost_fabric_prods_knitting.amount',
		'mkt_cost_fabric_prods_weaving.amount',
		'mkt_cost_fabric_prods_dyeing.amount',
		'mkt_cost_fabric_prods_aop.amount',
		'mkt_cost_fabric_prods_burn_out.amount',
		'mkt_cost_fabric_prods_fab_fini.amount',
		'mkt_cost_fabric_prods_fab_wash.amount',
		'mkt_cost_embs_print.amount',
		'mkt_cost_embs_emb.amount',
		'mkt_cost_embs_spemb.amount',
		'mkt_cost_embs_gmt_dyeing.amount',
		'mkt_cost_embs_gmt_washing.amount',
		'mkt_costs_courier.amount',
		'mkt_costs_lab.amount',
		'mkt_costs_insp.amount',
		'mkt_costs_frei.amount',
		'mkt_costs_opa.amount',
		'mkt_costs_dep.amount',
		'mkt_costs_coc.amount',
		'mkt_costs_ict.amount',
		'mkt_cost_cms.amount',
		'mkt_cost_commercials.amount',
		'mkt_cost_commissions.amount',
		'budgetFab.grey_fab',
		'budgetFab.fin_fab',
		'mkt_cost_fabrics.cons',
		'mkt_cost_fabrics.req_cons',
		'gmt_item_ratio.item_ratio',
		'explcsc.lc_sc_no'
		])
		->orderBy('sales_orders.ship_date')
		->get();
		return $rows;
		
    }

    public function formatThree(){
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);
    	$rows=$this->reportData()
    	->map(function($rows) use($itemcomplexity,$buyinghouses){
    		$costing_unit=12;
    		$rows->dying_amount=$rows->dying_amount+$rows->dying_overhead_amount;
    		$rows->aop_amount=$rows->aop_amount+$rows->aop_overhead_amount;
    		$rows->printing_amount=$rows->printing_amount+$rows->printing_overhead_amount;
    		$courier_amount=($rows->courier_rate/$costing_unit)*$rows->qty;
	        $lab_amount=($rows->lab_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
	        $insp_amount=($rows->insp_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
	        $opa_amount=($rows->opa_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
	        $dep_amount=($rows->dep_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
	        $coc_amount=($rows->coc_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
	        $ict_amount=($rows->ict_rate/$costing_unit)*($rows->qty/$rows->item_ratio);

	        $other_amount=$courier_amount+$lab_amount+$insp_amount+$opa_amount+$dep_amount+ $coc_amount+ $ict_amount;

            $rows->courier_amount=$other_amount;

            $freight_amount=($rows->freight_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
            $rows->freight_amount=$freight_amount;

            //$cm_amount=($rows->cm_rate/$costing_unit)*($rows->qty/$rows->item_ratio);
            $cm_amount=$rows->cm_rate*$rows->qty;
            $rows->cm_amount=$cm_amount;

            $commi_amount=($rows->commi_rate/100)*$rows->amount;
            $rows->commi_amount=$commi_amount;


            $commmercial=
            $rows->yarn_amount+
            $rows->trim_amount+
            $rows->fab_pur_amount+
            $rows->kniting_amount+
            $rows->yarn_dying_amount+
            $rows->weaving_amount+
            $rows->dying_amount+
            $rows->aop_amount+
            $rows->burn_out_amount+
            $rows->finishing_amount+
            $rows->washing_amount+
            $rows->printing_amount+
            $rows->emb_amount+
            $rows->spemb_amount+
            $rows->gmt_dyeing_amount+
            $rows->gmt_washing_amount;

	        $commer_amount=($rows->commer_rate/100)*$commmercial;
	        $rows->commer_amount=$commer_amount;

	        $total_amount=$commmercial+$other_amount+$freight_amount+$cm_amount+$commi_amount+$commer_amount;
	        $rows->total_amount=$total_amount;
	        $total_profit=$rows->amount-$total_amount;
	        $rows->total_profit=$total_profit;

	        $rows->total_profit_per=0;
	        if($rows->amount){
		        $rows->total_profit_per=(($total_profit/$rows->amount)*100);
	        }

	        $rows->qty_dzn=($rows->qty/$rows->item_ratio)/$costing_unit;
	        $rows->mkt_yarn_amount=$rows->mkt_yarn_amount*($rows->qty_dzn);
	        $rows->mkt_fab_pur_amount=$rows->mkt_fab_pur_amount*($rows->qty_dzn);
	        $rows->mkt_trim_amount=$rows->mkt_trim_amount*($rows->qty_dzn);
	        $rows->mkt_yd_amount=$rows->mkt_yd_amount*($rows->qty_dzn);
	        $rows->mkt_knit_amount=$rows->mkt_knit_amount*($rows->qty_dzn);
	        $rows->mkt_weav_amount=$rows->mkt_weav_amount*($rows->qty_dzn);
	        $rows->mkt_dye_amount=$rows->mkt_dye_amount*($rows->qty_dzn);
	        $rows->mkt_aop_amount=$rows->mkt_aop_amount*($rows->qty_dzn);
	        $rows->mkt_burn_amount=$rows->mkt_burn_amount*($rows->qty_dzn);
	        $rows->mkt_fab_finsh_amount=$rows->mkt_fab_finsh_amount*($rows->qty_dzn);
	        $rows->mkt_fab_wash_amount=$rows->mkt_fab_wash_amount*($rows->qty_dzn);
	        $rows->mkt_print_amount=$rows->mkt_print_amount*($rows->qty_dzn);
	        $rows->mkt_embel_amount=$rows->mkt_embel_amount*($rows->qty_dzn);
	        $rows->mkt_spembel_amount=$rows->mkt_spembel_amount*($rows->qty_dzn);
	        $rows->mkt_gmt_dye_amount=$rows->mkt_gmt_dye_amount*($rows->qty_dzn);
	        $rows->mkt_gmt_wash_amount=$rows->mkt_gmt_wash_amount*($rows->qty_dzn);

	        $rows->mkt_courier_amount=$rows->mkt_courier_amount*($rows->qty_dzn);
	        $rows->mkt_lab_amount=$rows->mkt_courier_amount*($rows->qty_dzn);
	        $rows->mkt_insp_amount=$rows->mkt_insp_amount*($rows->qty_dzn);
	        $rows->mkt_opa_amount=$rows->mkt_opa_amount*($rows->qty_dzn);
	        $rows->mkt_dep_amount=$rows->mkt_dep_amount*($rows->qty_dzn);
	        $rows->mkt_coc_amount=$rows->mkt_coc_amount*($rows->qty_dzn);
	        $rows->mkt_ict_amount=$rows->mkt_ict_amount*($rows->qty_dzn);
	        $other_amount=$rows->mkt_courier_amount+$rows->mkt_lab_amount+$rows->mkt_insp_amount+$rows->mkt_opa_amount+$rows->mkt_dep_amount+ $rows->mkt_coc_amount+ $rows->mkt_ict_amount;

	        $rows->mkt_other_amount=$other_amount;
	        $rows->mkt_frei_amount=$rows->mkt_frei_amount*($rows->qty_dzn);
	        $rows->mkt_cm_amount=$rows->mkt_cm_amount*($rows->qty);
	        $rows->mkt_commer_amount=$rows->mkt_commer_amount*($rows->qty_dzn);
	        $rows->mkt_commi_amount=$rows->mkt_commi_amount*($rows->qty_dzn);

	        $mktTotalAmount=$rows->mkt_yarn_amount+$rows->mkt_fab_pur_amount+$rows->mkt_trim_amount+$rows->mkt_yd_amount+$rows->mkt_knit_amount+$rows->mkt_weav_amount+$rows->mkt_dye_amount+$rows->mkt_aop_amount+$rows->mkt_burn_amount+$rows->mkt_fab_finsh_amount+$rows->mkt_fab_wash_amount+$rows->mkt_print_amount+$rows->mkt_embel_amount+$rows->mkt_spembel_amount+$rows->mkt_gmt_dye_amount+$rows->mkt_gmt_wash_amount+$rows->gmt_washing_amount+$rows->mkt_other_amount+$rows->mkt_frei_amount+$rows->mkt_cm_amount+$rows->mkt_commer_amount+$rows->mkt_commi_amount;
	        $rows->mkt_total_amount=$mktTotalAmount;

	        $rows->mkt_total_profit=$rows->amount-$rows->mkt_total_amount;

	        $rows->mkt_total_profit_per=0;
	        if($rows->amount){
		        $rows->mkt_total_profit_per=(($rows->mkt_total_profit/$rows->amount)*100);
	        }

	        $fin_fab_cons=0;
            if($rows->plan_cut_qty){
                $fin_fab_cons=($rows->fin_fab/($rows->plan_cut_qty/$rows->item_ratio))*12;
            }
            $rows->fin_fab_cons=$fin_fab_cons;

			//$style['fin_fab_cons']=number_format($fin_fab_cons,'2','.',',');

	        $rows->yarn_vari=$rows->mkt_yarn_amount-$rows->yarn_amount;
	        $rows->fabpur_vari=$rows->mkt_fab_pur_amount-$rows->fab_pur_amount;
	        $rows->trim_vari=$rows->mkt_trim_amount-$rows->trim_amount;
	        $rows->yd_vari=$rows->mkt_yd_amount-$rows->yarn_dying_amount;
	        $rows->kniting_vari=$rows->mkt_knit_amount-$rows->kniting_amount;
	        $rows->weav_vari=$rows->mkt_weav_amount-$rows->weaving_amount;
	        $rows->dye_vari=$rows->mkt_dye_amount-$rows->dying_amount;
	        $rows->aop_vari=$rows->mkt_aop_amount-$rows->aop_amount;
	        $rows->burn_out_vari=$rows->mkt_burn_amount-$rows->burn_out_amount;
	        $rows->finish_vari=$rows->mkt_fab_finsh_amount-$rows->finishing_amount;
	        $rows->wash_vari=$rows->mkt_fab_wash_amount-$rows->washing_amount;
	        $rows->print_vari=$rows->mkt_print_amount-$rows->printing_amount;
	        $rows->emb_vari=$rows->mkt_embel_amount-$rows->emb_amount;
	        $rows->spemb_vari=$rows->mkt_spembel_amount-$rows->spemb_amount;
	        $rows->gmt_dye_vari=$rows->mkt_gmt_dye_amount-$rows->gmt_dyeing_amount;
	        $rows->gmt_wash_vari=$rows->mkt_gmt_wash_amount-$rows->gmt_washing_amount;
	        $rows->gmt_other_vari=$rows->mkt_other_amount-$rows->courier_amount;
	        $rows->gmt_frei_vari=$rows->mkt_frei_amount-$rows->freight_amount;
	        $rows->gmt_cm_vari=$rows->mkt_cm_amount-$rows->cm_amount;
	        $rows->gmt_commi_vari=$rows->mkt_commi_amount-$rows->commi_amount;
	        $rows->gmt_commer_vari=$rows->mkt_commer_amount-$rows->commer_amount;
	        $rows->total_amount_vari=$rows->mkt_total_amount-$rows->total_amount;

	        /*$rows->total_profit_vari=$rows->mkt_total_profit-$rows->total_profit;
	        $rows->total_profit_per_vari=$rows->mkt_total_profit_per-$rows->total_profit_per;*/
	        $rows->total_profit_vari= $rows->total_profit - ($rows->mkt_total_profit);
	        $rows->total_profit_per_vari=$rows->total_profit_per - ($rows->mkt_total_profit_per);

	        $rows->fin_fab_cons_vari=$rows->mkt_fab_fin_cons-$fin_fab_cons;

	        
            


	        $rows->sale_order_receive_date=date('d-M-Y',strtotime($rows->sale_order_receive_date));
    		$rows->delivery_date=date('d-M-Y',strtotime($rows->ship_date));
    		$rows->delivery_month=date('M',strtotime($rows->ship_date));
			$rows->item_complexity=$itemcomplexity[$rows->item_complexity];
			
			$rows->agent_name=	isset($buyinghouses[$rows->buying_agent_id])? $buyinghouses[$rows->buying_agent_id]:'';

			$rows->buying_agent_name=$rows->agent_name." ". $rows->contact;

            /*$rows->qty=number_format($rows->qty,0);
            $rows->rate=number_format($rows->rate,2);
            $rows->amount=number_format($rows->amount,2);

            $rows->mkt_yarn_amount=number_format($rows->mkt_yarn_amount,2);
            $rows->yarn_amount=number_format($rows->yarn_amount,2);
            $rows->yarn_vari=number_format($rows->yarn_vari,2);

            $rows->mkt_trim_amount=number_format($rows->mkt_trim_amount,2);
            $rows->trim_amount=number_format($rows->trim_amount,2);
            $rows->trim_vari=number_format($rows->trim_vari,2);

            $rows->mkt_yd_amount=number_format($rows->mkt_yd_amount,2);
            $rows->yarn_dying_amount=number_format($rows->yarn_dying_amount,2);
            $rows->yd_vari=number_format($rows->yd_vari,2);

            $rows->mkt_knit_amount=number_format($rows->mkt_knit_amount,2);
            $rows->kniting_amount=number_format($rows->kniting_amount,2);
            $rows->kniting_vari=number_format($rows->kniting_vari,2);

            $rows->mkt_dye_amount=number_format($rows->mkt_dye_amount,2);
            $rows->dying_amount=number_format($rows->dying_amount,2);
            $rows->dye_vari=number_format($rows->dye_vari,2);

            $rows->mkt_aop_amount=number_format($rows->mkt_aop_amount,2);
            $rows->aop_amount=number_format($rows->aop_amount,2);
            $rows->aop_vari=number_format($rows->aop_vari,2);

            $rows->mkt_burn_amount=number_format($rows->mkt_burn_amount,2);
            $rows->burn_out_amount=number_format($rows->burn_out_amount,2);
            $rows->burn_out_vari=number_format($rows->burn_out_vari,2);

            $rows->mkt_fab_finsh_amount=number_format($rows->mkt_fab_finsh_amount,2);
            $rows->finishing_amount=number_format($rows->finishing_amount,2);
            $rows->finish_vari=number_format($rows->finish_vari,2);

            $rows->mkt_fab_wash_amount=number_format($rows->mkt_fab_wash_amount,2);
            $rows->washing_amount=number_format($rows->washing_amount,2);
            $rows->wash_vari=number_format($rows->wash_vari,2);

            $rows->mkt_print_amount=number_format($rows->mkt_print_amount,2);
            $rows->printing_amount=number_format($rows->printing_amount,2);
            $rows->print_vari=number_format($rows->print_vari,2);

            $rows->mkt_embel_amount=number_format($rows->mkt_embel_amount,2);
            $rows->emb_amount=number_format($rows->emb_amount,2);
            $rows->emb_vari=number_format($rows->emb_vari,2);

            $rows->mkt_spembel_amount=number_format($rows->mkt_spembel_amount,2);
            $rows->spemb_amount=number_format($rows->spemb_amount,2);
            $rows->spemb_vari=number_format($rows->spemb_vari,2);

            $rows->mkt_gmt_dye_amount=number_format($rows->mkt_gmt_dye_amount,2);
            $rows->gmt_dyeing_amount=number_format($rows->gmt_dyeing_amount,2);
            $rows->gmt_dye_vari=number_format($rows->gmt_dye_vari,2);

            $rows->mkt_gmt_wash_amount=number_format($rows->mkt_gmt_wash_amount,2);
            $rows->gmt_washing_amount=number_format($rows->gmt_washing_amount,2);
            $rows->gmt_wash_vari=number_format($rows->gmt_wash_vari,2);

            $rows->mkt_other_amount=number_format($rows->mkt_other_amount,2);
            $rows->courier_amount=number_format($rows->courier_amount,2);
            $rows->gmt_other_vari=number_format($rows->gmt_other_vari,2);

            $rows->mkt_frei_amount=number_format($rows->mkt_frei_amount,2);
            $rows->freight_amount=number_format($rows->freight_amount,2);
            $rows->gmt_frei_vari=number_format($rows->gmt_frei_vari,2);

            $rows->mkt_cm_amount=number_format($rows->mkt_cm_amount,2);
            $rows->cm_amount=number_format($rows->cm_amount,2);
            $rows->gmt_cm_vari=number_format($rows->gmt_cm_vari,2);

            $rows->mkt_commi_amount=number_format($rows->mkt_commi_amount,2);
            $rows->commi_amount=number_format($rows->commi_amount,2);
            $rows->gmt_commi_vari=number_format($rows->gmt_commi_vari,2);

            $rows->mkt_commer_amount=number_format($rows->mkt_commer_amount,2);
            $rows->commer_amount=number_format($rows->commer_amount,2);
            $rows->gmt_commer_vari=number_format($rows->gmt_commer_vari,2);

            $rows->mkt_total_amount=number_format($rows->mkt_total_amount,2);
            $rows->total_amount=number_format($rows->total_amount,2);
            $rows->total_amount_vari=number_format($rows->total_amount_vari,2);

            $rows->mkt_total_profit=number_format($rows->mkt_total_profit,2);
            $rows->total_profit=number_format($rows->total_profit,2);
            $rows->total_profit_vari=number_format($rows->total_profit_vari,2);

            $rows->mkt_total_profit_per=number_format($rows->mkt_total_profit_per,2);
            $rows->total_profit_per=number_format($rows->total_profit_per,2);
            $rows->total_profit_per_vari=number_format($rows->total_profit_per_vari,2);*/
    		return $rows;
    	});

        return Template::loadView('Report.BudgetVsMktCost',['rows'=>$rows]);
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
				$result->grey_fab_qty=number_format($result->grey_fab_pur_req,'2','.',',');
				$result->fin_fab_qty=number_format($result->fin_fab_pur_req,'2','.',',');
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
				$result->grey_fab_qty=number_format($result->grey_fab_pur_req,'2','.',',');
				$result->fin_fab_qty=number_format($result->fin_fab_pur_req,'2','.',',');
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
			sales_orders.ship_date,

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
			style_fabrications.dyeing_type_id,
			sales_orders.ship_date
			order by style_gmts.id,gmtsparts.id,budget_fabric_prods.id', [request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$total_amount=$result->amount+$result->overhead_amount;
				$result->dye_charge_per_kg=0;
				if ($result->qty) {
					$result->dye_charge_per_kg=number_format($total_amount/$result->qty,'2','.',',');
				}	
				$result->ship_date=$result->ship_date;	
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
				$total_amount=$result->amount+$result->overhead_amount;
				$result->dye_charge_per_kg=number_format($total_amount/$result->qty,'2','.',',');
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
			sales_orders.ship_date,
			gmtsparts.name as gmt_part_name,
			item_accounts.item_description as gmt_item_description,
			
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
			style_fabrications.embelishment_type_id,
			sales_orders.ship_date
			order by style_gmts.id,gmtsparts.id,budget_fabric_prods.id', [25,request('sale_order_id',0)]);
			
			foreach($results as $result)
			{
				$total_amount=$result->amount+$result->overhead_amount;
				$result->aop_charge_per_kg=0;
				if($result->qty)
				{
					$result->aop_charge_per_kg=number_format($total_amount/$result->qty,'2','.',',');

				}
				$result->ship_date=$result->ship_date;
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->total_amount=number_format($total_amount,'2','.',',');
				$result->overhead_amount=number_format($result->overhead_amount,'2','.',',');
				$result->overhead_rate=number_format($result->overhead_rate,'2','.',',');
				$result->shape=isset($fabricshape[$result->fabric_shape_id])?$fabricshape[$result->fabric_shape_id]:'';
				$result->look=isset($fabriclooks[$result->fabric_look_id])?$fabriclooks[$result->fabric_look_id]:'';
				$result->aoptype=isset($embelishmenttype[$result->embelishment_type_id])?$embelishmenttype[$result->embelishment_type_id]:'';
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
		avg(budget_fabric_prod_cons.rate) as rate,
		sum(budget_fabric_prod_cons.amount) as amount,
		avg(budget_fabric_prod_cons.overhead_rate) as overhead_rate,
		sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount'
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
				$total_amount=$result->amount+$result->overhead_amount;
				$result->aop_charge_per_kg=number_format($total_amount/$result->qty,'2','.',',');
				$result->fab_des=$fabDropdown[$result->style_fabrication_id];
				$result->qty=number_format($result->qty,'2','.',',');
				$result->rate=number_format($result->rate,'2','.',',');
				$result->amount=number_format($result->amount,'2','.',',');
				$result->total_amount=number_format($total_amount,'2','.',',');
				$result->overhead_amount=number_format($result->overhead_amount,'2','.',',');
				$result->overhead_rate=number_format($result->overhead_rate,'2','.',',');
				$result->shape=isset($fabricshape[$result->fabric_shape_id])?$fabricshape[$result->fabric_shape_id]:'';
				$result->look=isset($fabriclooks[$result->fabric_look_id])?$fabriclooks[$result->fabric_look_id]:'';
				$result->aoptype=isset($embelishmenttype[$result->embelishment_type_id])?$embelishmenttype[$result->embelishment_type_id]:'';
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
			select 
			item_accounts.item_description as gmt_item,
			embelishment_types.name as emb_type,
			gmtsparts.name as gmt_part_name,
			style_embelishments.embelishment_size_id,
			sum(budget_emb_cons.req_cons) as qty,
			avg(budget_emb_cons.rate) as rate, 
			sum(budget_emb_cons.amount) as amount ,
			avg(budget_emb_cons.overhead_rate) as overhead_rate,
			sum(budget_emb_cons.overhead_amount) as overhead_amount,
			sales_orders.ship_date
		from budget_emb_cons 
		left join sales_order_gmt_color_sizes
		 on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
		 join sales_order_countries
		 on sales_order_countries.id = sales_order_gmt_color_sizes.sale_order_country_id
		join sales_orders
		 on sales_orders.id = sales_order_countries.sale_order_id
		left join budget_embs
		 on budget_embs.id=budget_emb_cons.budget_emb_id
		left join style_embelishments
		 on style_embelishments.id=budget_embs.style_embelishment_id
		left join embelishments
		 on embelishments.id=style_embelishments.embelishment_id
		left join production_processes
		 on production_processes.id=embelishments.production_process_id
		left join embelishment_types
		 on embelishment_types.id=style_embelishments.embelishment_type_id
		left join style_gmts
		 on style_gmts.id=style_embelishments.style_gmt_id
		left join item_accounts
		 on item_accounts.id=style_gmts.item_account_id
		left join gmtsparts
		 on gmtsparts.id=style_embelishments.gmtspart_id
		where production_processes.production_area_id = ? and sales_order_gmt_color_sizes.sale_order_id=?
		group by
		 item_accounts.item_description,
		 style_embelishments.embelishment_type_id,
		 embelishment_types.name,gmtsparts.name,style_embelishments.embelishment_size_id,
		 style_embelishments.gmtspart_id,
		 sales_orders.ship_date'
		 , [45,request('sale_order_id',0)]);
			
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
	    sum(budget_emb_cons.overhead_amount) as overhead_amount'
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
			
			sum ((sales_order_gmt_color_sizes.qty/gmt_item_ratio.item_ratio) *(budget_others.amount/12)) as amount
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
			where budget_others.cost_head_id !=15
			group by budget_others.id, budget_others.cost_head_id,budget_others.amount,gmt_item_ratio.item_ratio
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
	
	public function getBacFileSrc(){
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

	public function getDealMerchant()
	{
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
			'employee_h_rs.experience',
			'employee_h_rs.email',
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
			$style=" and styles.style_ref like %$style_ref% ";
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
				$req_cons=($result->grey_fab/$result->plan_cut_qty)*12;
				$cons=($result->fin_fab/$result->plan_cut_qty)*12;
	            $result->fab_des=$fabDropdown[$result->style_fabrication_id];
	            $result->req_cons=number_format($req_cons,'4','.',',');
	            $result->cons=number_format($cons,'4','.',',');
	            $result->fin_fab=number_format($result->fin_fab,'4','.',',');
	            $result->grey_fab=number_format($result->grey_fab,'4','.',',');
	            $result->extra_percent=number_format($result->extra_percent,'2','.',',')." %";
	            $result->process_loss=number_format($result->process_loss,'2','.',',')." %";
	            
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
    	$company_id=5;
		

		$fexpense=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',2]])
		->where([['acc_beps.company_id','=',$company_id/* request('company_id',0) */]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->orderBy('acc_bep_entries.id','asc')
		->get()
		->map(function ($fexpense){
			$fexpense->per_day=$fexpense->amount/$this->no_of_days;
			return $fexpense;
		});

		$vexpense=$this->accbep
		->leftJoin('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
			$join->whereNull('acc_bep_entries.salary_prod_bill_id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',1]])
		->where([['acc_beps.company_id','=',$company_id]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->filter(function ($vexpense){
			if($vexpense->salary_prod_bill_id!==3){
				return $vexpense;
			}	
		})
		->values()
		->map(function ($vexpense){
			$vexpense->per_day=$vexpense->amount/$this->no_of_days;
			$vexpense->amount_usd=$vexpense->amount/$this->exch_rate;
			$vexpense->per_day_usd=$vexpense->amount_usd/$this->no_of_days;
			return $vexpense;
		});

		$prodcompany=$this->accbep
		->join('companies', function($join) {
			$join->on('acc_beps.company_id', '=', 'companies.id');
		})
		->where([['acc_beps.company_id','=',$company_id]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($prodcompany) use($date_to) {
			//$prodcompany->dyeing_capacity_qty=number_format($prodcompany->dyeing_capacity_qty,0);
			return $prodcompany;
		});
		return Template::loadView('Report.BudgetAndCostingBepDetailsMatrix', ['fexpense'=>$fexpense,'vexpense'=>$vexpense,/* 'earnings'=>$earnings, */'exch_rate'=>$this->exch_rate, 'prodcompany'=>$prodcompany]);
    }

    public function getbepAop()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=6;
		
		$fexpense=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',2]])
		->where([['acc_beps.company_id','=',$company_id]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->orderBy('acc_bep_entries.id','asc')
		->get()
		->map(function ($fexpense){
			$fexpense->per_day=$fexpense->amount/$this->no_of_days;
			return $fexpense;
		});

		$vexpense=$this->accbep
		->leftJoin('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
			$join->whereNull('acc_bep_entries.salary_prod_bill_id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',1]])
		->where([['acc_beps.company_id','=',$company_id]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->filter(function ($vexpense){
			if($vexpense->salary_prod_bill_id!==3){
				return $vexpense;
			}	
		})
		->values()
		->map(function ($vexpense){
			$vexpense->per_day=$vexpense->amount/$this->no_of_days;
			$vexpense->amount_usd=$vexpense->amount/$this->exch_rate;
			$vexpense->per_day_usd=$vexpense->amount_usd/$this->no_of_days;
			return $vexpense;
		});

		$prodcompany=$this->accbep
		->join('companies', function($join) {
			$join->on('acc_beps.company_id', '=', 'companies.id');
		})
		->where([['acc_beps.company_id','=',$company_id]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($prodcompany) use($date_to) {
			return $prodcompany;
		});
		return Template::loadView('Report.BudgetAndCostingAopBepDtailMatrix', ['fexpense'=>$fexpense,'vexpense'=>$vexpense,'exch_rate'=>$this->exch_rate, 'prodcompany'=>$prodcompany]);
    }

    public function getbepGpc()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$company_id=41;
		
		$fexpense=$this->accbep
		->join('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',2]])
		->where([['acc_beps.company_id','=',$company_id]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->orderBy('acc_bep_entries.id','asc')
		->get()
		->map(function ($fexpense){
			$fexpense->per_day=$fexpense->amount/$this->no_of_days;
			return $fexpense;
		});

		$vexpense=$this->accbep
		->leftJoin('acc_bep_entries', function($join) use($date_to) {
			$join->on('acc_bep_entries.acc_bep_id', '=', 'acc_beps.id');
			$join->whereNull('acc_bep_entries.salary_prod_bill_id');
		})
		->join('acc_chart_ctrl_heads', function($join) {
			$join->on('acc_chart_ctrl_heads.id', '=', 'acc_bep_entries.acc_chart_ctrl_head_id');
		})
		->where([['acc_bep_entries.expense_type_id','=',1]])
		->where([['acc_beps.company_id','=',$company_id]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->filter(function ($vexpense){
			if($vexpense->salary_prod_bill_id!==3){
				return $vexpense;
			}	
		})
		->values()
		->map(function ($vexpense){
			$vexpense->per_day=$vexpense->amount/$this->no_of_days;
			$vexpense->amount_usd=$vexpense->amount/$this->exch_rate;
			$vexpense->per_day_usd=$vexpense->amount_usd/$this->no_of_days;
			return $vexpense;
		});

		$prodcompany=$this->accbep
		->join('companies', function($join) {
			$join->on('acc_beps.company_id', '=', 'companies.id');
		})
		->where([['acc_beps.company_id','=',$company_id]])
		->whereRaw("'".$date_to."' between acc_beps.start_date and acc_beps.end_date")
		->get()
		->map(function ($prodcompany) use($date_to) {
			//$prodcompany->dyeing_capacity_qty=number_format($prodcompany->dyeing_capacity_qty,0);
			return $prodcompany;
		});
		return Template::loadView('Report.BudgetAndCostingBepPrintDtailMatrix', ['fexpense'=>$fexpense,'vexpense'=>$vexpense,'exch_rate'=>$this->exch_rate, 'prodcompany'=>$prodcompany]);
    }
}