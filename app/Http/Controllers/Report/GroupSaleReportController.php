<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use Illuminate\Support\Carbon;

class GroupSaleReportController extends Controller
{

  private $subsection;
  private $wstudylinesetup;
  private $prodgmtsewing;
	private $buyer;
	private $company;
	private $supplier;
	private $location;

	public function __construct(
    SubsectionRepository $subsection,
    WstudyLineSetupRepository $wstudylinesetup,
    ProdGmtSewingRepository $prodgmtsewing,
    CompanyRepository $company, 
    LocationRepository $location, 
    SupplierRepository $supplier, 
    BuyerRepository $buyer
  )
  {
    $this->subsection                = $subsection;
    $this->wstudylinesetup           = $wstudylinesetup;
    $this->prodgmtsewing             = $prodgmtsewing;
    $this->company = $company;
    $this->buyer = $buyer;
    $this->location = $location;
    $this->supplier = $supplier;
    $this->middleware('auth');
  }

  public function index() {
    return Template::loadView('Report.GroupSale');
  }

	public function reportData() {
    $date_from=request('date_from',0);
    $date_to =request('date_to',0);

    $date = Carbon::parse($date_from);
    $now = Carbon::parse($date_to);
    $diff = $date->diffInMonths($now);
    $monthArr=[];
    for($i=0;$i<=$diff;$i++){
    $month=date('M-y',strtotime($date));
    $monthArr[$month]=$month;
    $date->addMonth();
    }

    $companies=$this->company->orderBy('name')->get(['id','code','name']);
    $comGmt=[];
    $comKniting=[];
    $comDyeing=[];
    $comAop=[];
    $comSrp=[];
    $comEmb=[];
    $comTot=[];

    $comMonth=[];
    $comMonthGmt=[];
    $comMonthKniting=[];
    $comMonthDyeing=[];
    $comMonthAop=[];
    $comMonthSrp=[];
    $comMonthEmb=[];

    foreach($companies as $company){
      $comGmt[$company->id]=0;
      $comKniting[$company->id]=0;
      $comDyeing[$company->id]=0;
      $comAop[$company->id]=0;
      $comSrp[$company->id]=0;
      $comEmb[$company->id]=0;
      $comTot[$company->id]=0;
      foreach($monthArr as $key=>$value){
        $comMonth[$company->id][$key]=0;
        $comMonthGmt[$company->id][$key]=0;
        $comMonthKniting[$company->id][$key]=0;
        $comMonthDyeing[$company->id][$key]=0;
        $comMonthAop[$company->id][$key]=0;
        $comMonthSrp[$company->id][$key]=0;
        $comMonthEmb[$company->id][$key]=0;
        $comMonthTot[$company->id][$key]=0;
      }
    }
   
    $gmt = \DB::select("
    select
    exp_invoices.id,
    exp_lc_scs.beneficiary_id as company_id,
    currencies.code as currency_code,
    exp_lc_scs.exch_rate,
    exp_invoices.invoice_date,
    exp_invoices.invoice_value as amount
    from
    exp_invoices
    inner join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id
    left join currencies on currencies.id=exp_lc_scs.currency_id
    where exp_invoices.invoice_date >= ? 
    and exp_invoices.invoice_date <= ?
    and exp_invoices.deleted_at is null
    and exp_lc_scs.deleted_at is null
    ",[$date_from,$date_to]);
    $gmtdats=collect($gmt);
    foreach($gmtdats as $gmtdat ){
      $month=date('M-y',strtotime($gmtdat->invoice_date));
      if($gmtdat->currency_code !=='BDT'){
        $gmtdat->amount_bdt=$gmtdat->amount*$gmtdat->exch_rate;
      }
      else{
        $gmtdat->amount_bdt=$gmtdat->amount;
      }
      $comGmt[$gmtdat->company_id]+=$gmtdat->amount_bdt;
      $comTot[$gmtdat->company_id]+=$gmtdat->amount_bdt;
      $comMonthGmt[$gmtdat->company_id][$month]+=$gmtdat->amount_bdt;
      $comMonthTot[$gmtdat->company_id][$month]+=$gmtdat->amount_bdt;
    }
    
    $subconknit = \DB::select("
    select 
    so_knit_dlvs.id as barcode_no,
    so_knit_dlvs.issue_no as bill_no,
    so_knit_dlvs.issue_date as bill_date,
    so_knit_dlvs.company_id,
    currencies.code as currency_code,
    so_knits.exch_rate,
    sum(so_knit_dlv_items.qty) as qty,
    avg(so_knit_dlv_items.rate) as rate,
    sum(so_knit_dlv_items.amount) as amount
    from so_knit_dlvs
    join companies on companies.id=so_knit_dlvs.company_id
    join buyers on buyers.id = so_knit_dlvs.buyer_id
    left join currencies on currencies.id = so_knit_dlvs.currency_id
    join so_knit_dlv_items on so_knit_dlv_items.so_knit_dlv_id=so_knit_dlvs.id
    join so_knit_refs on so_knit_refs.id=so_knit_dlv_items.so_knit_ref_id
    join so_knits on so_knits.id=so_knit_refs.so_knit_id
    join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
    --left join buyers gmt_buyers on gmt_buyers.id=so_knit_items.gmt_buyer
    left join uoms on uoms.id=so_knit_items.uom_id
    left join colors on colors.id = so_knit_items.fabric_color_id
    where so_knit_dlvs.issue_date >= ?
    and so_knit_dlvs.issue_date <= ?
    and so_knit_dlv_items.deleted_at is null
    and  so_knit_items.deleted_at is null 
    group by
    so_knit_dlvs.id,
    so_knit_dlvs.issue_no,
    so_knit_dlvs.issue_date,
    so_knit_dlvs.company_id,
    currencies.code,
    so_knits.exch_rate
    ",[$date_from,$date_to]);
    $subconknitdats=collect($subconknit);
    foreach($subconknitdats as $subconknitdat ){
      $month=date('M-y',strtotime($subconknitdat->bill_date));
      if($subconknitdat->currency_code=='USD'){
      $subconknitdat->amount_bdt=$subconknitdat->amount*84;
      }
      else{
      $subconknitdat->amount_bdt=$subconknitdat->amount;
      }
      $comKniting[$subconknitdat->company_id]+=$subconknitdat->amount_bdt;
      $comTot[$subconknitdat->company_id]+=$subconknitdat->amount_bdt;
      $comMonthKniting[$subconknitdat->company_id][$month]+=$subconknitdat->amount_bdt;
      $comMonthTot[$subconknitdat->company_id][$month]+=$subconknitdat->amount_bdt;
    }

    $inhouseknit = \DB::select("
    select 
    prod_knit_dlvs.id,
    so_knits.company_id,
    prod_knit_dlvs.dlv_date,
    prod_knit_qcs.qc_pass_qty,   
    po_knit_service_item_qties.rate,
    currencies.code as currency_code
    from prod_knit_dlvs 
    inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id
    inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id
    inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id
    inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id
    inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id
    inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id
    inner join pl_knit_items on pl_knit_items.id=prod_knit_items.pl_knit_item_id
    inner join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
    inner join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
    inner join so_knits on so_knits.id=so_knit_refs.so_knit_id
    inner join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
    inner join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
    inner join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
    and po_knit_service_items.deleted_at is null
    inner join po_knit_services on po_knit_service_items.po_knit_service_id=po_knit_services.id
    inner join currencies  on currencies.id=po_knit_services.currency_id
    where 
    prod_knits.basis_id = 1 
    and prod_knit_dlvs.dlv_date>=?
    and prod_knit_dlvs.dlv_date <=?
    and prod_knit_dlvs.deleted_at is null

    ",[$date_from,$date_to]);
    $inhouseknitdats=collect($inhouseknit);
    foreach($inhouseknitdats as $inhouseknitdat ){
      $month=date('M-y',strtotime($inhouseknitdat->dlv_date));
      $inhouseknitdat->amount=$inhouseknitdat->qc_pass_qty*$inhouseknitdat->rate;
      if($inhouseknitdat->currency_code=='USD'){
      $inhouseknitdat->amount_bdt=$inhouseknitdat->amount*84;
      }
      else{
      $inhouseknitdat->amount_bdt=$inhouseknitdat->amount;
      }
      $comKniting[$inhouseknitdat->company_id]+=$inhouseknitdat->amount_bdt;
      $comTot[$inhouseknitdat->company_id]+=$inhouseknitdat->amount_bdt;
      $comMonthKniting[$inhouseknitdat->company_id][$month]+=$inhouseknitdat->amount_bdt;
      $comMonthTot[$inhouseknitdat->company_id][$month]+=$inhouseknitdat->amount_bdt;
    }
    //=========================

    $subcondye = \DB::select("
    select
    so_dyeing_dlvs.id as so_dyeing_dlv_id,
    so_dyeing_dlvs.issue_no,
    so_dyeing_dlvs.company_id,
    so_dyeing_dlvs.issue_date,
    so_dyeings.exch_rate,
    so_dyeing_dlv_items.id,
    currencies.code as currency_code,
    sum(so_dyeing_dlv_items.amount) as amount
    from
    so_dyeing_dlvs
    join so_dyeing_dlv_items on so_dyeing_dlv_items.so_dyeing_dlv_id=so_dyeing_dlvs.id
    join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_dlv_items.so_dyeing_ref_id
    join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
    join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
    join uoms on uoms.id=so_dyeing_items.uom_id
    join colors on colors.id=so_dyeing_items.fabric_color_id
    join buyers on buyers.id=so_dyeing_dlvs.buyer_id
    join companies on companies.id=so_dyeing_dlvs.company_id
    join currencies on currencies.id=so_dyeing_dlvs.currency_id
    where so_dyeing_dlvs.issue_date >=?
    and so_dyeing_dlvs.issue_date <=?
    group by
    so_dyeing_dlvs.id,
    so_dyeing_dlvs.issue_no,
    so_dyeing_dlvs.company_id,
    so_dyeing_dlvs.buyer_id,
    so_dyeing_dlvs.issue_date,
    so_dyeings.exch_rate,
    so_dyeing_dlvs.remarks,
    so_dyeing_dlv_items.id,
    currencies.code
    ",[$date_from,$date_to]);

    $subcondyedats=collect($subcondye);
    foreach($subcondyedats as $subcondyedat ){
      $month=date('M-y',strtotime($subcondyedat->issue_date));
      if($subcondyedat->currency_code=='USD'){
      $subcondyedat->amount_bdt=$subcondyedat->amount*84;
      }
      else{
      $subcondyedat->amount_bdt=$subcondyedat->amount;
      }
      $comDyeing[$subcondyedat->company_id]+=$subcondyedat->amount_bdt;
      $comTot[$subcondyedat->company_id]+=$subcondyedat->amount_bdt;
      $comMonthDyeing[$subcondyedat->company_id][$month]+=$subcondyedat->amount_bdt;
      $comMonthTot[$subcondyedat->company_id][$month]+=$subcondyedat->amount_bdt;
    }

    $inhousedye = \DB::select("
    select 
    prod_finish_dlvs.id,
    prod_finish_dlvs.dlv_date,
    prod_finish_dlvs.company_id,
    prod_batch_finish_qc_rolls.reject_qty,   
    prod_batch_finish_qc_rolls.qty as qc_pass_qty, 
    prod_batch_rolls.qty as batch_qty,  
    po_dyeing_service_item_qties.rate,
    currencies.code as currency_code
    from 
    prod_finish_dlvs
    inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
    inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
    inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
    inner join prod_batches on prod_batches.id = prod_batch_finish_qcs.prod_batch_id
    inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
    inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
    inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
    inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
    inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
    inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
    inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
    inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
    inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
    inner join po_dyeing_services on po_dyeing_services.id = po_dyeing_service_items.po_dyeing_service_id
    inner join currencies on currencies.id = po_dyeing_services.currency_id
    inner join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
    inner join jobs on jobs.id = sales_orders.job_id
    inner join styles on styles.id = jobs.style_id
    inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
    inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
    inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
    inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
    inner join constructions on constructions.id = autoyarns.construction_id
    inner join buyers on buyers.id = styles.buyer_id
    left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id

    left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id = so_dyeing_refs.id

    left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
    left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 


    inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
    inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
    inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
    inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
    inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
    inner join inv_rcvs on inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
    inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
    inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
    and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
    inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
    inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
    inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
    inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
    inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
    left join colors on  colors.id=prod_knit_item_rolls.fabric_color
    left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
    where prod_finish_dlvs.menu_id in(285,286) 
    and prod_finish_dlvs.dlv_date >=?
    and prod_finish_dlvs.dlv_date <=?
    and prod_finish_dlvs.deleted_at is null
    ",[$date_from,$date_to]);

    $inhousedyedats=collect($inhousedye);
    foreach($inhousedyedats as $inhousedyedat ){
      $month=date('M-y',strtotime($inhousedyedat->dlv_date));
      $inhousedyedat->amount=$inhousedyedat->batch_qty*$inhousedyedat->rate;
      if($inhousedyedat->currency_code=='USD'){
      $inhousedyedat->amount_bdt=$inhousedyedat->amount*84;
      }
      else{
      $inhousedyedat->amount_bdt=$inhousedyedat->amount;
      }
      $comDyeing[$inhousedyedat->company_id]+=$inhousedyedat->amount_bdt;
      $comTot[$inhousedyedat->company_id]+=$inhousedyedat->amount_bdt;
      $comMonthDyeing[$inhousedyedat->company_id][$month]+=$inhousedyedat->amount_bdt;
      $comMonthTot[$inhousedyedat->company_id][$month]+=$inhousedyedat->amount_bdt;
    }

    $subconaopdats=collect(
    \DB::select("
    select 
    so_aop_dlvs.id as barcode_no,
    so_aop_dlvs.issue_no as bill_no,
    so_aop_dlvs.issue_date as bill_date,
    so_aop_dlvs.buyer_id,
    so_aops.sales_order_no as dyeing_sales_order_no,
    so_aop_items.gmtspart_id,
    so_aop_items.autoyarn_id,
    so_aop_items.gsm_weight,
    so_aop_dlv_items.no_of_roll,
    so_aop_dlv_items.design_no,
    so_aop_dlv_items.fin_dia as dia_width,
    so_aop_dlv_items.fin_gsm as gsm_wgt,
    so_aop_dlv_items.grey_used as grey_wgt,
    so_aop_items.gmt_style_ref,
    so_aop_items.gmt_sale_order_no,
    companies.id as company_id,
    buyers.name as customer_name,
    uoms.code as uom_code,
    colors.name as batch_color,
    currencies.code as currency_code,
    so_aops.exch_rate,
    sum(so_aop_dlv_items.qty) as qty,
    avg(so_aop_dlv_items.rate) as rate,
    sum(so_aop_dlv_items.amount) as amount
    from so_aop_dlvs
    join companies on companies.id=so_aop_dlvs.company_id
    join buyers on buyers.id = so_aop_dlvs.buyer_id
    left join currencies on currencies.id = so_aop_dlvs.currency_id
    join so_aop_dlv_items on so_aop_dlv_items.so_aop_dlv_id=so_aop_dlvs.id
    join so_aop_refs on so_aop_refs.id=so_aop_dlv_items.so_aop_ref_id
    join so_aops on so_aops.id=so_aop_refs.so_aop_id
    join so_aop_items on so_aop_items.so_aop_ref_id=so_aop_refs.id
    left join uoms on uoms.id=so_aop_items.uom_id
    left join colors on colors.id = so_aop_items.fabric_color_id
    where so_aop_dlvs.issue_date >= ?
    and so_aop_dlvs.issue_date <= ?
    and so_aop_dlv_items.deleted_at is null

    group by
    so_aop_dlvs.id,
    so_aop_dlvs.issue_no,
    so_aop_dlvs.issue_date,
    so_aop_dlvs.buyer_id,
    so_aops.sales_order_no,
    so_aop_items.gmtspart_id,
    so_aop_items.autoyarn_id,
    so_aop_items.gsm_weight,
    so_aop_dlv_items.no_of_roll, 
    so_aop_dlv_items.design_no,
    so_aop_dlv_items.fin_dia,
    so_aop_dlv_items.fin_gsm,
    so_aop_dlv_items.grey_used,
    so_aop_items.gmt_style_ref,
    so_aop_items.gmt_sale_order_no,

    companies.id,
    buyers.name,
    uoms.code,
    colors.name,
    currencies.code,
    so_aops.exch_rate
    ",[$date_from,$date_to]));
    foreach($subconaopdats as $subconaopdat){
      $month=date('M-y',strtotime($subconaopdat->bill_date));
      if($subconaopdat->currency_code=='USD'){
        $subconaopdat->amount_bdt=$subconaopdat->amount*84;
      }
      else{
        $subconaopdat->amount_bdt=$subconaopdat->amount;
      }
      $comAop[$subconaopdat->company_id]+=$subconaopdat->amount_bdt;
      $comTot[$subconaopdat->company_id]+=$subconaopdat->amount_bdt;
      $comMonthAop[$subconaopdat->company_id][$month]+=$subconaopdat->amount_bdt;
      $comMonthTot[$subconaopdat->company_id][$month]+=$subconaopdat->amount_bdt;
    }

    $inhouseaopdats=collect(
    \DB::select("
    select 
    prod_finish_dlvs.id,
    prod_finish_dlvs.dlv_date,
    prod_finish_dlvs.company_id,
    prod_finish_dlv_rolls.id, 
    prod_aop_batch_finish_qc_rolls.reject_qty,   
    prod_aop_batch_finish_qc_rolls.qty as qc_pass_qty,   
    prod_aop_batch_rolls.qty as batch_qty,
    po_aop_service_item_qties.rate,
    currencies.code as currency_code
    from 
    prod_finish_dlvs
    inner join prod_finish_dlv_rolls prod_aop_finish_dlv_rolls on prod_finish_dlvs.id = prod_aop_finish_dlv_rolls.prod_finish_dlv_id 
    inner join prod_batch_finish_qc_rolls prod_aop_batch_finish_qc_rolls on prod_aop_batch_finish_qc_rolls.id = prod_aop_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
    inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_aop_batch_finish_qc_rolls.prod_batch_finish_qc_id 
    inner join prod_aop_batches on prod_aop_batches.id = prod_batch_finish_qcs.prod_aop_batch_id
    inner join prod_aop_batch_rolls on prod_aop_batch_rolls.id = prod_aop_batch_finish_qc_rolls.prod_aop_batch_roll_id
    inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
    inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
    inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
    inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
    inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id

    inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
    inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
    inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
    inner join colors fabriccolors on fabriccolors.id = prod_batches.batch_color_id

    inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
    inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
    inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
    inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
    inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
    inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
    inner join po_aop_services on po_aop_services.id=po_aop_service_items.po_aop_service_id
    inner join currencies on currencies.id = po_aop_services.currency_id
    inner join budget_fabric_prod_cons on budget_fabric_prod_cons.id = po_aop_service_item_qties.budget_fabric_prod_con_id
    inner join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id
    inner join jobs on jobs.id = sales_orders.job_id
    inner join styles on styles.id = jobs.style_id
    inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
    inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
    inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
    inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
    inner join constructions on constructions.id = autoyarns.construction_id
    inner join buyers on buyers.id = styles.buyer_id
    inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id

    inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
    inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
    inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
    inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
    inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
    inner join inv_rcvs on inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
    inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
    inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
    and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
    inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
    inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
    inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
    inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
    inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 

    where prod_finish_dlvs.menu_id in(287) 
    and prod_finish_dlvs.dlv_date >=?
    and prod_finish_dlvs.dlv_date <=?
    and prod_finish_dlvs.deleted_at is null
    ",[$date_from,$date_to]));
    foreach($inhouseaopdats as $inhouseaopdat){
      $month=date('M-y',strtotime($inhouseaopdat->dlv_date));
      $inhouseaopdat->amount=$inhouseaopdat->batch_qty*$inhouseaopdat->rate;
      if($inhouseaopdat->currency_code=='USD'){
      $inhouseaopdat->amount_bdt=$inhouseaopdat->amount*84;
      }
      else{
      $inhouseaopdat->amount_bdt=$inhouseaopdat->amount;
      }
      $comAop[$inhouseaopdat->company_id]+=$inhouseaopdat->amount_bdt;
      $comTot[$inhouseaopdat->company_id]+=$inhouseaopdat->amount_bdt;
      $comMonthAop[$inhouseaopdat->company_id][$month]+=$inhouseaopdat->amount_bdt;
      $comMonthTot[$inhouseaopdat->company_id][$month]+=$inhouseaopdat->amount_bdt;
    }



    return Template::loadView('Report.GroupSaleMatrix',[
    'date_from'=>$date_from,
    'date_to'=>$date_to,
    'companies'=>$companies,
    'comGmt'=> $comGmt,
    'comKniting'=>$comKniting,
    'comDyeing'=>$comDyeing,
    'comAop'=>$comAop,
    'comSrp'=>$comSrp,
    'comEmb'=>$comEmb,
    'comTot'=>$comTot,
    'comMonth'=>$comMonth,
    'comMonthGmt'=>$comMonthGmt,
    'comMonthKniting'=>$comMonthKniting,
    'comMonthDyeing'=>$comMonthDyeing,
    'comMonthAop'=>$comMonthAop,
    'comMonthSrp'=>$comMonthSrp,
    'comMonthEmb'=>$comMonthEmb,
    'comMonthTot'=>$comMonthTot,
    ]);
  }

  public function getDyeingDetails(){
    $date_from=request('date_from',0);
    $date_to =request('date_to',0);
    $company_id =request('company_id',0);
    if($company_id){
      $company_cond=" and so_dyeing_dlvs.company_id =$company_id ";
      $company_cond2=" and prod_finish_dlvs.company_id =$company_id ";
    }
    else{
      $company_cond="";
      $company_cond2="";
    }
    $subcondye = \DB::select("
    select
    so_dyeing_dlvs.id,
    so_dyeing_dlvs.issue_no,
    companies.code as company_code,
    buyers.name as buyer_name,
    so_dyeing_dlvs.company_id,
    so_dyeing_dlvs.issue_date,
    so_dyeings.exch_rate,
    currencies.code as currency_code,
    sum(so_dyeing_dlv_items.qty) as qty,
    sum(so_dyeing_dlv_items.amount) as amount
    from
    so_dyeing_dlvs
    join so_dyeing_dlv_items on so_dyeing_dlv_items.so_dyeing_dlv_id=so_dyeing_dlvs.id
    join so_dyeing_refs on so_dyeing_refs.id=so_dyeing_dlv_items.so_dyeing_ref_id
    join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
    join so_dyeings on so_dyeings.id=so_dyeing_refs.so_dyeing_id
    join uoms on uoms.id=so_dyeing_items.uom_id
    join colors on colors.id=so_dyeing_items.fabric_color_id
    join buyers on buyers.id=so_dyeing_dlvs.buyer_id
    join companies on companies.id=so_dyeing_dlvs.company_id
    join currencies on currencies.id=so_dyeing_dlvs.currency_id
    where so_dyeing_dlvs.issue_date >=?
    and so_dyeing_dlvs.issue_date <=?
    $company_cond

    group by
    so_dyeing_dlvs.id,
    so_dyeing_dlvs.issue_no,
    companies.code,
    buyers.name,
    so_dyeing_dlvs.company_id,
    so_dyeing_dlvs.buyer_id,
    so_dyeing_dlvs.issue_date,
    so_dyeings.exch_rate,
    so_dyeing_dlvs.remarks,
    currencies.code
    order by so_dyeing_dlvs.id desc
    ",[$date_from,$date_to]);

    $subcondyedats=collect($subcondye)
    ->map(function($subcondyedats){
      $month=date('M-y',strtotime($subcondyedats->issue_date));
      $subcondyedats->bill_month=$month;
      $subcondyedats->issue_date=date('d-M-Y',strtotime($subcondyedats->issue_date));
      if($subcondyedats->currency_code=='USD'){
        $subcondyedats->amount_bdt=$subcondyedats->amount*84;
      }
      else{
        $subcondyedats->amount_bdt=$subcondyedats->amount;
      }
      $subcondyedats->qty=number_format($subcondyedats->qty,0);
      $subcondyedats->amount_bdt=number_format($subcondyedats->amount_bdt,0);
      return $subcondyedats;
    });

    $inhousedye = \DB::select("
    select
    m.id,
    m.dlv_date as issue_date,
    m.dlv_no as issue_no,
    m.company_code,
    m.buyer_name,
    m.currency_code,
    sum(m.batch_qty) as qty,
    sum(m.amount) as amount

    from
    (
    select 
    prod_finish_dlvs.id,
    prod_finish_dlvs.dlv_no,
    prod_finish_dlvs.dlv_date,
    customers.name as buyer_name,
    companies.code as company_code,
    prod_finish_dlvs.company_id,
    prod_batch_finish_qc_rolls.reject_qty,   
    prod_batch_finish_qc_rolls.qty as qc_pass_qty, 
    prod_batch_rolls.qty as batch_qty,  
    po_dyeing_service_item_qties.rate,
    (prod_batch_rolls.qty*po_dyeing_service_item_qties.rate) as amount,
    currencies.code as currency_code
    from 
    prod_finish_dlvs
    inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
    inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
    inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
    inner join prod_batches on prod_batches.id = prod_batch_finish_qcs.prod_batch_id
    inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
    inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
    inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
    inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
    inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
    inner join buyers  customers on customers.id=so_dyeings.buyer_id
    inner join companies  on companies.id=prod_finish_dlvs.company_id
    inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
    inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
    inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
    inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
    inner join po_dyeing_services on po_dyeing_services.id = po_dyeing_service_items.po_dyeing_service_id
    inner join currencies on currencies.id = po_dyeing_services.currency_id
    inner join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
    inner join jobs on jobs.id = sales_orders.job_id
    inner join styles on styles.id = jobs.style_id
    inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
    inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
    inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
    inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
    inner join constructions on constructions.id = autoyarns.construction_id
    inner join buyers on buyers.id = styles.buyer_id
    left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id

    left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id = so_dyeing_refs.id

    left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
    left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 


    inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
    inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
    inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
    inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
    inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
    inner join inv_rcvs on inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
    inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
    inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
    and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
    inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
    inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
    inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
    inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
    inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
    left join colors on  colors.id=prod_knit_item_rolls.fabric_color
    left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
    where prod_finish_dlvs.menu_id in(285,286) 
    and prod_finish_dlvs.dlv_date >=?
    and prod_finish_dlvs.dlv_date <=?
    $company_cond2
    and prod_finish_dlvs.deleted_at is null
    ) m 
    group by m.id,
    m.dlv_date,
    m.dlv_no,
    m.company_code,
    m.buyer_name,
    m.currency_code
    order by m.id desc
    ",[$date_from,$date_to]);

    $inhousedyedats=collect($inhousedye)
    ->map(function($inhousedyedats){
      $month=date('M-y',strtotime($inhousedyedats->issue_date));
      $inhousedyedats->bill_month=$month;
      $inhousedyedats->issue_date=date('d-M-Y',strtotime($inhousedyedats->issue_date));
      if($inhousedyedats->currency_code=='USD'){
        $inhousedyedats->amount_bdt=$inhousedyedats->amount*84;
      }
      else{
        $inhousedyedats->amount_bdt=$inhousedyedats->amount;
      }
      $inhousedyedats->qty=number_format($inhousedyedats->qty,0);
      $inhousedyedats->amount_bdt=number_format($inhousedyedats->amount_bdt,0);
      return $inhousedyedats;
    });
    echo json_encode(['sub'=>$subcondyedats,'inh'=>$inhousedyedats]);
  }

  public function getAopDetails(){
    $date_from=request('date_from',0);
    $date_to =request('date_to',0);
    $company_id =request('company_id',0);
    if($company_id){
    $company_cond=" and so_aop_dlvs.company_id =$company_id ";
    $company_cond2=" and prod_finish_dlvs.company_id =$company_id ";
    }
    else{
    $company_cond="";
    $company_cond2="";
    }
    $subconaop = \DB::select("
    select 
    so_aop_dlvs.id,
    so_aop_dlvs.issue_no,
    so_aop_dlvs.issue_date,
    companies.code as company_code,
    so_aop_dlvs.company_id,
    buyers.name as buyer_name,
    currencies.code as currency_code,
    so_aops.exch_rate,
    sum(so_aop_dlv_items.qty) as qty,
    avg(so_aop_dlv_items.rate) as rate,
    sum(so_aop_dlv_items.amount) as amount
    from so_aop_dlvs
    join companies on companies.id=so_aop_dlvs.company_id
    join buyers on buyers.id = so_aop_dlvs.buyer_id
    left join currencies on currencies.id = so_aop_dlvs.currency_id
    join so_aop_dlv_items on so_aop_dlv_items.so_aop_dlv_id=so_aop_dlvs.id
    join so_aop_refs on so_aop_refs.id=so_aop_dlv_items.so_aop_ref_id
    join so_aops on so_aops.id=so_aop_refs.so_aop_id
    join so_aop_items on so_aop_items.so_aop_ref_id=so_aop_refs.id
    left join uoms on uoms.id=so_aop_items.uom_id
    left join colors on colors.id = so_aop_items.fabric_color_id
    where so_aop_dlvs.issue_date >= ?
    and so_aop_dlvs.issue_date <= ?
    and so_aop_dlv_items.deleted_at is null
    $company_cond

    group by
    so_aop_dlvs.id,
    so_aop_dlvs.issue_no,
    so_aop_dlvs.issue_date,
    companies.code,
    so_aop_dlvs.company_id,
    buyers.name,
    currencies.code,
    so_aops.exch_rate
    order by so_aop_dlvs.id desc
    ",[$date_from,$date_to]);

    $subconaopdats=collect($subconaop)
    ->map(function($subconaopdats){
      $month=date('M-y',strtotime($subconaopdats->issue_date));
      $subconaopdats->bill_month=$month;
      $subconaopdats->issue_date=date('d-M-Y',strtotime($subconaopdats->issue_date));
      if($subconaopdats->currency_code=='USD'){
        $subconaopdats->amount_bdt=$subconaopdats->amount*84;
      }
      else{
        $subconaopdats->amount_bdt=$subconaopdats->amount;
      }
      $subconaopdats->qty=number_format($subconaopdats->qty,0);
      $subconaopdats->amount_bdt=number_format($subconaopdats->amount_bdt,0);
      return $subconaopdats;
    });

    $inhouseaop = \DB::select("
      select
      m.id,
      m.dlv_date as issue_date,
      m.dlv_no as issue_no,
      m.company_code,
      m.buyer_name,
      m.currency_code,
      sum(m.batch_qty) as qty,
      sum(m.amount) as amount

      from
      (
      select 
      prod_finish_dlvs.id,
      prod_finish_dlvs.dlv_no,
      prod_finish_dlvs.dlv_date,
      prod_finish_dlvs.company_id,
      prod_finish_dlv_rolls.id as prod_finish_dlv_roll_id, 
      customers.name as buyer_name,
      companies.code as company_code,
      prod_aop_batch_finish_qc_rolls.reject_qty,   
      prod_aop_batch_finish_qc_rolls.qty as qc_pass_qty,   
      prod_aop_batch_rolls.qty as batch_qty,
      po_aop_service_item_qties.rate,
      currencies.code as currency_code,
      (prod_aop_batch_rolls.qty*po_aop_service_item_qties.rate) as amount
      from 
      prod_finish_dlvs
      inner join prod_finish_dlv_rolls prod_aop_finish_dlv_rolls on prod_finish_dlvs.id = prod_aop_finish_dlv_rolls.prod_finish_dlv_id 
      inner join prod_batch_finish_qc_rolls prod_aop_batch_finish_qc_rolls on prod_aop_batch_finish_qc_rolls.id = prod_aop_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
      inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_aop_batch_finish_qc_rolls.prod_batch_finish_qc_id 
      inner join prod_aop_batches on prod_aop_batches.id = prod_batch_finish_qcs.prod_aop_batch_id
      inner join prod_aop_batch_rolls on prod_aop_batch_rolls.id = prod_aop_batch_finish_qc_rolls.prod_aop_batch_roll_id
      inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
      inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
      inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
      inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
      inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id

      inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
      inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
      inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
      inner join colors fabriccolors on fabriccolors.id = prod_batches.batch_color_id

      inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
      inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
      inner join buyers  customers on customers.id=so_aops.buyer_id
      inner join companies  on companies.id=prod_finish_dlvs.company_id
      inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
      inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
      inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
      inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
      inner join po_aop_services on po_aop_services.id=po_aop_service_items.po_aop_service_id
      inner join currencies on currencies.id = po_aop_services.currency_id
      inner join budget_fabric_prod_cons on budget_fabric_prod_cons.id = po_aop_service_item_qties.budget_fabric_prod_con_id
      inner join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id
      inner join jobs on jobs.id = sales_orders.job_id
      inner join styles on styles.id = jobs.style_id
      inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
      inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
      inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
      inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
      inner join constructions on constructions.id = autoyarns.construction_id
      inner join buyers on buyers.id = styles.buyer_id
      inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id

      inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
      inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
      inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
      inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
      inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
      inner join inv_rcvs on inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
      inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
      inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
      and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
      inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
      inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
      inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
      inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
      inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
      where prod_finish_dlvs.menu_id in(287) 
      and prod_finish_dlvs.dlv_date >=?
      and prod_finish_dlvs.dlv_date <=?
      and prod_finish_dlvs.deleted_at is null
      $company_cond2
      ) m 
      group by m.id,
      m.dlv_date,
      m.dlv_no,
      m.company_code,
      m.buyer_name,
      m.currency_code
      order by m.id desc
    ",[$date_from,$date_to]);

    $inhouseaopdats=collect($inhouseaop)
    ->map(function($inhouseaopdats){
      $month=date('M-y',strtotime($inhouseaopdats->issue_date));
      $inhouseaopdats->bill_month=$month;
      $inhouseaopdats->issue_date=date('d-M-Y',strtotime($inhouseaopdats->issue_date));
      if($inhouseaopdats->currency_code=='USD'){
        $inhouseaopdats->amount_bdt=$inhouseaopdats->amount*84;
      }
      else{
        $inhouseaopdats->amount_bdt=$inhouseaopdats->amount;
      }
      $inhouseaopdats->qty=number_format($inhouseaopdats->qty,0);
      $inhouseaopdats->amount_bdt=number_format($inhouseaopdats->amount_bdt,0);
      return $inhouseaopdats;
      });
      echo json_encode(['sub'=>$subconaopdats,'inh'=>$inhouseaopdats]);
  }

  public function getKnitingDetails(){
    $date_from=request('date_from',0);
    $date_to =request('date_to',0);
    $company_id =request('company_id',0);
    if($company_id){
    $company_cond=" and so_knit_dlvs.company_id =$company_id ";
    $company_cond2=" and so_knits.company_id =$company_id ";
    }
    else{
    $company_cond="";
    $company_cond2="";
    }
    $subconknit = \DB::select("
      select 
      so_knit_dlvs.id,
      so_knit_dlvs.issue_no,
      so_knit_dlvs.issue_date,
      companies.code as company_code,
      so_knit_dlvs.company_id,
      buyers.name as buyer_name,
      currencies.code as currency_code,
      so_knits.exch_rate,
      sum(so_knit_dlv_items.qty) as qty,
      avg(so_knit_dlv_items.rate) as rate,
      sum(so_knit_dlv_items.amount) as amount
      from so_knit_dlvs
      join companies on companies.id=so_knit_dlvs.company_id
      join buyers on buyers.id = so_knit_dlvs.buyer_id
      left join currencies on currencies.id = so_knit_dlvs.currency_id
      join so_knit_dlv_items on so_knit_dlv_items.so_knit_dlv_id=so_knit_dlvs.id
      join so_knit_refs on so_knit_refs.id=so_knit_dlv_items.so_knit_ref_id
      join so_knits on so_knits.id=so_knit_refs.so_knit_id
      join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
      --left join buyers gmt_buyers on gmt_buyers.id=so_knit_items.gmt_buyer
      left join uoms on uoms.id=so_knit_items.uom_id
      left join colors on colors.id = so_knit_items.fabric_color_id
      where so_knit_dlvs.issue_date >= ?
      and so_knit_dlvs.issue_date <= ?
      and so_knit_dlv_items.deleted_at is null
      and  so_knit_items.deleted_at is null 
      $company_cond
      group by
      so_knit_dlvs.id,
      so_knit_dlvs.issue_no,
      so_knit_dlvs.issue_date,
      companies.code,
      so_knit_dlvs.company_id,
      buyers.name,
      currencies.code,
      so_knits.exch_rate
      order by so_knit_dlvs.id desc
    ",[$date_from,$date_to]);

    $subconknitdats=collect($subconknit)
    ->map(function($subconknitdats){
      $month=date('M-y',strtotime($subconknitdats->issue_date));
      $subconknitdats->bill_month=$month;
      $subconknitdats->issue_date=date('d-M-Y',strtotime($subconknitdats->issue_date));
      if($subconknitdats->currency_code=='USD'){
        $subconknitdats->amount_bdt=$subconknitdats->amount*84;
      }
      else{
        $subconknitdats->amount_bdt=$subconknitdats->amount;
      }
      $subconknitdats->qty=number_format($subconknitdats->qty,0);
      $subconknitdats->amount_bdt=number_format($subconknitdats->amount_bdt,0);
      return $subconknitdats;
    });

    $inhouseknit = \DB::select("
    select
    m.id,
    m.dlv_date as issue_date,
    m.dlv_no as issue_no,
    m.company_code,
    m.buyer_name,
    m.currency_code,
    sum(m.qc_pass_qty) as qty,
    sum(m.amount) as amount

    from
    (
    select 
    prod_knit_dlvs.id,
    prod_knit_dlvs.dlv_no,
    so_knits.company_id,
    companies.code as company_code,
    buyers.name as buyer_name,
    prod_knit_dlvs.dlv_date,
    prod_knit_qcs.qc_pass_qty,   
    po_knit_service_item_qties.rate,
    currencies.code as currency_code,
    (prod_knit_qcs.qc_pass_qty*po_knit_service_item_qties.rate) as amount
    from prod_knit_dlvs 
    inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id
    inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id
    inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id
    inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id
    inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id
    inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id
    inner join pl_knit_items on pl_knit_items.id=prod_knit_items.pl_knit_item_id
    inner join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
    inner join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
    inner join so_knits on so_knits.id=so_knit_refs.so_knit_id
    inner join companies on so_knits.company_id=companies.id
    inner join buyers on so_knits.buyer_id=buyers.id
    inner join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
    inner join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
    inner join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
    and po_knit_service_items.deleted_at is null
    inner join po_knit_services on po_knit_service_items.po_knit_service_id=po_knit_services.id
    inner join currencies  on currencies.id=po_knit_services.currency_id
    where 
    prod_knits.basis_id = 1 
    and prod_knit_dlvs.dlv_date>=?
    and prod_knit_dlvs.dlv_date <=?
    and prod_knit_dlvs.deleted_at is null
    $company_cond2
    ) m
    group by
    m.id,
    m.dlv_date,
    m.dlv_no,
    m.company_code,
    m.buyer_name,
    m.currency_code
    order by m.id desc
    ",[$date_from,$date_to]);

    $inhouseknitdats=collect($inhouseknit)
    ->map(function($inhouseknitdats){
      $month=date('M-y',strtotime($inhouseknitdats->issue_date));
      $inhouseknitdats->bill_month=$month;
      $inhouseknitdats->issue_date=date('d-M-Y',strtotime($inhouseknitdats->issue_date));
      if($inhouseknitdats->currency_code=='USD'){
      $inhouseknitdats->amount_bdt=$inhouseknitdats->amount*84;
      }
      else{
      $inhouseknitdats->amount_bdt=$inhouseknitdats->amount;
      }
      $inhouseknitdats->qty=number_format($inhouseknitdats->qty,0);
      $inhouseknitdats->amount_bdt=number_format($inhouseknitdats->amount_bdt,0);
      return $inhouseknitdats;
    });
    echo json_encode(['sub'=>$subconknitdats,'inh'=>$inhouseknitdats]);
  }

  public function getGmtDetails(){
    $date_from=request('date_from',0);
    $date_to =request('date_to',0);
    $company_id =request('company_id',0);
    if($company_id){
    $company_cond=" and exp_lc_scs.beneficiary_id =$company_id ";
    }
    else{
    $company_cond="";
    }

    $gmt = \DB::select("
    select
    exp_invoices.id,
    exp_invoices.invoice_no,
    exp_lc_scs.beneficiary_id as company_id,
    companies.code as company_code,
    buyers.name as buyer_name,
    currencies.code as currency_code,
    exp_lc_scs.exch_rate,
    exp_invoices.invoice_date,
    exp_invoices.invoice_qty as qty,
    exp_invoices.invoice_value as amount
    from
    exp_invoices
    inner join exp_lc_scs on exp_lc_scs.id=exp_invoices.exp_lc_sc_id
    left join currencies on currencies.id=exp_lc_scs.currency_id
    left join companies on companies.id=exp_lc_scs.beneficiary_id
    left join buyers on buyers.id=exp_lc_scs.buyer_id
    where exp_invoices.invoice_date >= ? 
    and exp_invoices.invoice_date <= ?
    and exp_invoices.deleted_at is null
    and exp_lc_scs.deleted_at is null
    $company_cond
    ",[$date_from,$date_to]);
    $gmtdats=collect($gmt)
    ->map(function($gmtdats){
      $month=date('M-y',strtotime($gmtdats->invoice_date));
      $gmtdats->invoice_month=$month;
      $gmtdats->invoice_date=date('d-M-Y',strtotime($gmtdats->invoice_date));
      if($gmtdats->currency_code !=='BDT'){
        $gmtdats->amount_bdt=$gmtdats->amount*$gmtdats->exch_rate;
      }
      else{
        $gmtdats->amount_bdt=$gmtdats->amount;
      }
      $gmtdats->qty=number_format($gmtdats->qty,0);
      $gmtdats->amount_bdt=number_format($gmtdats->amount_bdt,0);
      return $gmtdats;
    });
    echo json_encode($gmtdats);
  }

}
