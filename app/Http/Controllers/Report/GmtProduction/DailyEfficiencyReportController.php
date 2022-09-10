<?php

namespace App\Http\Controllers\Report\GmtProduction;

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

class DailyEfficiencyReportController extends Controller
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
  ) {
    $this->subsection                = $subsection;
    $this->wstudylinesetup           = $wstudylinesetup;
    $this->prodgmtsewing             = $prodgmtsewing;
    $this->company = $company;
    $this->buyer = $buyer;
    $this->location = $location;
    $this->supplier = $supplier;
    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtcartonqtyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index()
  {
    $company = array_prepend(array_pluck($this->company->where([['nature_id', '=', 1]])->orderBy('name')->get(), 'name', 'id'), '-Select-', '');
    $buyer = array_prepend(array_pluck($this->buyer->buyers(), 'name', 'id'), '', '');
    $supplier = array_prepend(array_pluck($this->supplier->garmentSubcontractors(), 'name', 'id'), '-Select-', '');
    $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
    $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');
    $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');
    $ordersource = array_prepend(config('bprs.ordersource'), '-Select-', '');
    return Template::loadView('Report.GmtProduction.DailyEfficiencyReport', ['location' => $location, 'productionsource' => $productionsource, 'shiftname' => $shiftname, 'ordersource' => $ordersource, 'company' => $company, 'supplier' => $supplier, 'buyer' => $buyer]);
  }
  public function reportData()
  {
    //$date_from=request('date_from',0);
    $date_to = request('date_to', 0);
    $company_id = request('company_id', 0);
    $company = array_prepend(array_pluck($this->company->where([['nature_id', '=', 1]])->orderBy('name')->get(), 'code', 'id'), '-Select-', '');

    $subsections = \DB::select("
        select
        count(subsections.id) as id,
        company_subsections.company_id
        from
        subsections
        join company_subsections on company_subsections.subsection_id=subsections.id
        where 
        subsections.is_treat_sewing_line=1 
        and subsections.projected_line_id=0 
        and subsections.status_id=1
        and subsections.deleted_at is null
        group by 
        company_subsections.company_id
        order by 
        company_subsections.company_id
      ");
    $subsectionarr = [];
    foreach ($subsections as $subsection) {
      $subsectionarr[$subsection->company_id] = $subsection->id;
    }


    $subsections = $this->wstudylinesetup
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
      })
      ->join('wstudy_line_setup_lines', function ($join) {
        $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
      })
      ->join('wstudy_line_setup_dtls', function ($join) {
        $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
      })
      ->leftJoin('subsections', function ($join) {
        $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
      })
      ->leftJoin('floors', function ($join) {
        $join->on('floors.id', '=', 'subsections.floor_id');
      })
      ->leftJoin('employees', function ($join) {
        $join->on('employees.id', '=', 'subsections.employee_id');
      })
      ->when($date_to, function ($q) use ($date_to) {
        return $q->where('wstudy_line_setup_dtls.from_date', '>=', $date_to);
      })
      ->when($date_to, function ($q) use ($date_to) {
        return $q->where('wstudy_line_setup_dtls.to_date', '<=', $date_to);
      })
      //->where([['wstudy_line_setups.company_id','=',$company_id]])
      ->get([
        'wstudy_line_setups.id',
        'subsections.name',
        'subsections.code',
        'floors.name as floor_name',
        'employees.name as employee_name',
        'subsections.qty',
        'subsections.amount'
      ]);

    $lineNames = array();
    $lineCode = array();
    $lineFloor = array();
    $lineCheif = array();
    $capacityQty = array();
    $capacityAmount = array();
    foreach ($subsections as $subsection) {
      $lineNames[$subsection->id][] = $subsection->name;
      $lineCode[$subsection->id][] = $subsection->code;
      $lineFloor[$subsection->id][] = $subsection->floor_name;
      $lineCheif[$subsection->id][] = $subsection->employee_name;
      $capacityQty[$subsection->id][] = $subsection->qty;
      $capacityAmount[$subsection->id][] = $subsection->amount;
    }


    $results = \DB::select("
      select 
      wstudy_line_setups.id,
      wstudy_line_setups.company_id,
      sales_orders.id as sale_order_id ,
      sales_orders.sale_order_no,
      styles.id as style_id,
      styles.buyer_id,
      styles.flie_src,
      buyers.name,
      style_gmts.id as style_gmt_id,
      style_gmts.smv,
      style_gmts.sewing_effi_per,
      item_accounts.item_description as item_name,
      sales_order_gmt_color_sizes.qty,
      sales_order_gmt_color_sizes.rate,
      totday.day
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
      left join(
        select m.id,m.sale_order_id, count (m.sew_qc_date) as day from (
        select 
        wstudy_line_setups.id,
        sales_orders.id as sale_order_id,
        prod_gmt_sewings.sew_qc_date
        FROM prod_gmt_sewings
        join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
        join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join jobs on jobs.id = sales_orders.job_id
        right join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
        where  wstudy_line_setups.company_id=2 --and sales_orders.id=14253 and wstudy_line_setups.id=20
        group by
        wstudy_line_setups.id,
        sales_orders.id,
        prod_gmt_sewings.sew_qc_date
        order by prod_gmt_sewings.sew_qc_date) m group by m.id,m.sale_order_id
      ) totday on totday.id=wstudy_line_setups.id and totday.sale_order_id=sales_orders.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
      prod_gmt_sewings.sew_qc_date<='" . $date_to . "'
      --and wstudy_line_setups.company_id=?
      group by 
      wstudy_line_setups.id,
      wstudy_line_setups.company_id,
      sales_orders.id,
      styles.id,
      styles.flie_src,
      styles.buyer_id,
      buyers.name,
      sales_orders.sale_order_no,
      sales_order_gmt_color_sizes.id,
      sales_order_gmt_color_sizes.qty,
      sales_order_gmt_color_sizes.rate,
      style_gmts.id,
      item_accounts.item_description,
      style_gmts.smv,
      style_gmts.sewing_effi_per,
      totday.day,
      prod_gmt_sewing_orders.id
      order by prod_gmt_sewing_orders.id
      ");

    $buyerName = array();
    $orderNo = array();
    $orderQty = array();
    $orderAmount = array();
    $itemAccounts = array();
    $itemSmv = array();
    $imagepath = array();
    $totday = array();
    //$currentItem=array();

    foreach ($results as $result) {
      $amount = $result->qty * $result->rate;
      $buyerName[$result->id][$result->buyer_id] = $result->name;
      $orderNo[$result->id][$result->sale_order_id] = $result->sale_order_no;
      //$itemAccounts[$result->id][$result->style_gmt_id]=$result->item_name;
      //$itemSmv[$result->id][$result->style_gmt_id]=$result->smv;
      $totday[$result->id][$result->sale_order_id] = $result->day;
      $imagepath[$result->id][$result->style_id] = $result->flie_src;

      //$currentItem[$result->id]['smv']=$result->smv;
      //$currentItem[$result->id]['sewing_effi_per']=$result->sewing_effi_per;

      if (isset($orderQty[$result->id][$result->sale_order_id])) {
        $orderQty[$result->id][$result->sale_order_id] += $result->qty;
      } else {
        $orderQty[$result->id][$result->sale_order_id] = $result->qty;
      }
      if (isset($orderAmount[$result->id][$result->sale_order_id])) {
        $orderAmount[$result->id][$result->sale_order_id] += $amount;
      } else {
        $orderAmount[$result->id][$result->sale_order_id] = $amount;
      }
    }

    $current_items = \DB::select("
      select 
      wstudy_line_setups.id,
      wstudy_line_setups.company_id,
      sales_orders.id as sale_order_id ,
      sales_orders.sale_order_no,
      styles.id as style_id,
      styles.buyer_id,
      styles.flie_src,
      buyers.name,
      style_gmts.id as style_gmt_id,
      style_gmts.smv,
      style_gmts.sewing_effi_per,
      item_accounts.item_description as item_name
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id and sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
      join styles on styles.id = style_gmts.style_id
      join buyers on buyers.id=styles.buyer_id
      join item_accounts on item_accounts.id=style_gmts.item_account_id
      right join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
    
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
      prod_gmt_sewings.sew_qc_date<='" . $date_to . "'
      --and wstudy_line_setups.company_id=?
      group by 
      wstudy_line_setups.id,
      wstudy_line_setups.company_id,
      sales_orders.id,
      styles.id,
      styles.flie_src,
      styles.buyer_id,
      buyers.name,
      sales_orders.sale_order_no,
      sales_order_gmt_color_sizes.id,
      sales_order_gmt_color_sizes.qty,
      sales_order_gmt_color_sizes.rate,
      style_gmts.id,
      item_accounts.item_description,
      style_gmts.smv,
      style_gmts.sewing_effi_per,
      prod_gmt_sewing_orders.id
      order by prod_gmt_sewing_orders.id
      ");


    $currentItem = array();

    foreach ($current_items as $current_item) {
      $currentItem[$current_item->id]['smv'] = $current_item->smv;
      $currentItem[$current_item->id]['sewing_effi_per'] = $current_item->sewing_effi_per;
      $itemAccounts[$current_item->id][$current_item->style_gmt_id] = $current_item->item_name;
      $itemSmv[$current_item->id][$current_item->style_gmt_id] = $current_item->smv;
    }

    $prods = collect(
      \DB::select("
            select 
            m.id,
            m.company_id,
            m.prod_hour,
            sum(m.prod_cm) as prod_cm,
            sum(m.qty) as  qc_pass_qty,
            sum(m.alter_qty) as  alter_qty,
            sum(m.spot_qty) as  spot_qty,
            sum(m.reject_qty) as  reject_qty,
            sum(m.replace_qty) as  replace_qty,
            sum(m.smv) as  produced_mint

            from (
            select
            wstudy_line_setups.id,
            companies.id as company_id,
            companies.code as company_code,
            prods.prod_hour,
            prods.prod_cm,
            prods.qty,
            prods.alter_qty,
            prods.spot_qty,
            prods.reject_qty,
            prods.replace_qty,
            prods.smv
            from
            wstudy_line_setups
            join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id=wstudy_line_setups.id
            join companies on companies.id=wstudy_line_setups.company_id
            left join(
            SELECT 
            prod_gmt_sewing_orders.wstudy_line_setup_id,
            prod_gmt_sewing_orders.prod_hour,
            prod_gmt_sewing_qties.qty,
            budget_cms.cm_per_pcs,
            ((budget_cms.cm_per_pcs)*prod_gmt_sewing_qties.qty) as prod_cm,
            prod_gmt_sewing_qties.alter_qty,
            prod_gmt_sewing_qties.spot_qty,
            prod_gmt_sewing_qties.reject_qty,
            prod_gmt_sewing_qties.replace_qty,
            prod_gmt_sewing_qties.qty*style_gmts.smv as smv
            FROM prod_gmt_sewings
            join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
            join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id 
            join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join jobs on jobs.id = sales_orders.job_id
            join budgets on budgets.job_id=jobs.id
            
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id

            join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
            join budget_cms on budget_cms.budget_id=budgets.id and style_gmts.id=budget_cms.style_gmt_id

            where 
            prod_gmt_sewings.sew_qc_date>=? and 
            prod_gmt_sewings.sew_qc_date<=? 
            ) prods on prods.wstudy_line_setup_id = wstudy_line_setups.id
            where  wstudy_line_setup_dtls.from_date=?
            ) m 
            group by
            m.id,
            m.company_id,
            m.prod_hour
            --having sum(m.prod_cm)>0
            order by m.id
            ", [$date_to, $date_to, $date_to])
    );

    $prodHourArr = [];
    /*$totProdCm=0;
        $qc_pass_qty=0;
        $alter_qty=0;
        $spot_qty=0;
        $reject_qty=0;
        $replace_qty=0;
        $produced_mint=0;*/


    foreach ($prods as $prod) {
      $index = $prod->id;
      $prodHourArr[$index][$prod->prod_hour] = $prod->prod_hour;
      //$line[$index][$prod->prod_hour]['Prod']+=$prod->prod_cm;
      //$totProdCm+=$prod->prod_cm;
      //$qc_pass_qty+=$prod->qc_pass_qty;
      //$alter_qty+=$prod->alter_qty;
      //$spot_qty+=$prod->spot_qty;
      //$reject_qty+=$prod->reject_qty;
      //$replace_qty+=$prod->replace_qty;
      //$produced_mint+=$prod->produced_mint;
    }


    $prodgmtsewing = $this->wstudylinesetup
      ->join('wstudy_line_setup_dtls', function ($join) use ($date_to) {
        $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        $join->where('wstudy_line_setup_dtls.from_date', '>=', $date_to);
        $join->where('wstudy_line_setup_dtls.to_date', '<=', $date_to);
      })
      ->leftJoin(\DB::raw("(SELECT 
    wstudy_line_setups.id,
    wstudy_line_setup_dtls.from_date,
    sum(wstudy_line_setup_min_adjs.no_of_minute) as no_of_minute_minus
    from
    wstudy_line_setups
    join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id 
    join wstudy_line_setup_min_adjs on wstudy_line_setup_min_adjs.wstudy_line_setup_dtl_id = wstudy_line_setup_dtls.id 
    where wstudy_line_setup_min_adjs.minute_adj_reason_id in(1,2,3,4,5,7)
    and wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
    group by 
    wstudy_line_setups.id,
    wstudy_line_setup_dtls.from_date) minusaddj"), "minusaddj.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT 
    wstudy_line_setups.id,
    wstudy_line_setup_dtls.from_date,
    sum(wstudy_line_setup_min_adjs.no_of_minute) as no_of_minute_plus
    from
    wstudy_line_setups
    join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id 
    join wstudy_line_setup_min_adjs on wstudy_line_setup_min_adjs.wstudy_line_setup_dtl_id = wstudy_line_setup_dtls.id 
    where wstudy_line_setup_min_adjs.minute_adj_reason_id in(6)
    and wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
    group by 
    wstudy_line_setups.id,
    wstudy_line_setup_dtls.from_date) plusaddj"), "plusaddj.id", "=", "wstudy_line_setups.id")

      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew"), "sew.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='7:00am'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew7am"), "sew7am.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='8:00am'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew8am"), "sew8am.id", "=", "wstudy_line_setups.id")

      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='9:00am'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew9am"), "sew9am.id", "=", "wstudy_line_setups.id")

      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='10:00am'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew10am"), "sew10am.id", "=", "wstudy_line_setups.id")

      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='11:00am'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew11am"), "sew11am.id", "=", "wstudy_line_setups.id")

      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='12:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew12pm"), "sew12pm.id", "=", "wstudy_line_setups.id")

      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='1:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew1pm"), "sew1pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='2:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew2pm"), "sew2pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='3:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew3pm"), "sew3pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='4:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew4pm"), "sew4pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='5:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew5pm"), "sew5pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='6:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew6pm"), "sew6pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='7:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew7pm"), "sew7pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='8:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew8pm"), "sew8pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='9:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew9pm"), "sew9pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='10:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew10pm"), "sew10pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='11:00pm'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew11pm"), "sew11pm.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "' and prod_gmt_sewing_orders.prod_hour='12:00am'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id) sew12am"), "sew12am.id", "=", "wstudy_line_setups.id")

      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount from (SELECT 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*(budget_cms.cm_per_pcs) as amount
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
      wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      join budgets on budgets.style_id=style_gmts.style_id
      join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_to . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "'
      group by 
      wstudy_line_setups.id,
      prod_gmt_sewing_qties.id,
      prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,
      budget_cms.cm_per_pcs
      ) m group by m.id) sewCM"), "sewCM.id", "=", "wstudy_line_setups.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.amount) as amount from (
      SELECT 
      wstudy_line_setups.id,
      mkt_cost_cms.prod_per_hour*wstudy_line_setup_dtl_ords.prod_hour*mkt_cost_cms.cm_per_pcs as amount
      FROM wstudy_line_setups
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id 
      join wstudy_line_setup_dtl_ords on wstudy_line_setup_dtl_ords.wstudy_line_setup_dtl_id = wstudy_line_setup_dtls.id 
      join mkt_cost_cms on mkt_cost_cms.style_gmt_id=wstudy_line_setup_dtl_ords.style_gmt_id
      where wstudy_line_setup_dtls.from_date>='" . $date_to . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      
      ) m group by m.id) mktCM"), "mktCM.id", "=", "wstudy_line_setups.id")
      ->leftJoin('companies', function ($join) {
        $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
      })
      ->when($date_to, function ($q) use ($date_to) {
        return $q->where('wstudy_line_setup_dtls.from_date', '>=', $date_to);
      })
      ->when($date_to, function ($q) use ($date_to) {
        return $q->where('wstudy_line_setup_dtls.to_date', '<=', $date_to);
      })
      /*->when(request('line_id',0), function ($q) use($date_to){
    return $q->where('wstudy_line_setups.id', '=',request('line_id',0));
    })*/
      //->where([['wstudy_line_setups.company_id','=',$company_id]])
      ->selectRaw(
        'wstudy_line_setups.id,
      wstudy_line_setups.company_id,
      wstudy_line_setup_dtls.line_chief,
      wstudy_line_setup_dtls.operator,
      wstudy_line_setup_dtls.helper,
      wstudy_line_setup_dtls.working_hour,
      wstudy_line_setup_dtls.overtime_hour,
      wstudy_line_setup_dtls.target_per_hour,
      wstudy_line_setup_dtls.remarks,
      wstudy_line_setup_dtls.sewing_start_at,      
      wstudy_line_setup_dtls.sewing_end_at,        
      wstudy_line_setup_dtls.lunch_start_at,        
      wstudy_line_setup_dtls.lunch_end_at,
      companies.ceo,
      companies.contact,
      sew.qty as sew_qty,
      sew.amount as prodused_fob,
      sew.smv as produced_mint,
      sewCM.amount as cm_earned_usd,
      mktCM.amount as mkt_cm,
      minusaddj.no_of_minute_minus,
      plusaddj.no_of_minute_plus,

      sew7am.qty as sew7am_qty,
      sew8am.qty as sew8am_qty,
      sew9am.qty as sew9am_qty,
      sew10am.qty as sew10am_qty,
      sew11am.qty as sew11am_qty,
      sew12pm.qty as sew12pm_qty,
      sew1pm.qty as sew1pm_qty,
      sew2pm.qty as sew2pm_qty,
      sew3pm.qty as sew3pm_qty,
      sew4pm.qty as sew4pm_qty,
      sew5pm.qty as sew5pm_qty,
      sew6pm.qty as sew6pm_qty,
      sew7pm.qty as sew7pm_qty,
      sew8pm.qty as sew8pm_qty,
      sew9pm.qty as sew9pm_qty,
      sew10pm.qty as sew10pm_qty,
      sew11pm.qty as sew11pm_qty,
      sew12am.qty as sew12am_qty
      '
      )

      ->groupBy([
        'wstudy_line_setups.id',
        'wstudy_line_setups.company_id',
        'wstudy_line_setup_dtls.line_chief',
        'wstudy_line_setup_dtls.operator',
        'wstudy_line_setup_dtls.helper',
        'wstudy_line_setup_dtls.working_hour',
        'wstudy_line_setup_dtls.overtime_hour',
        'wstudy_line_setup_dtls.target_per_hour',
        'wstudy_line_setup_dtls.remarks',
        'wstudy_line_setup_dtls.sewing_start_at',
        'wstudy_line_setup_dtls.sewing_end_at',
        'wstudy_line_setup_dtls.lunch_start_at',
        'wstudy_line_setup_dtls.lunch_end_at',
        'companies.ceo',
        'companies.contact',
        'minusaddj.no_of_minute_minus',
        'plusaddj.no_of_minute_plus',
        'sew.qty',
        'sew.amount',
        'sew.smv',
        'sewCM.amount',
        'mktCM.amount',
        'sew7am.qty',
        'sew8am.qty',
        'sew9am.qty',
        'sew10am.qty',
        'sew11am.qty',
        'sew12pm.qty',
        'sew1pm.qty',
        'sew2pm.qty',
        'sew3pm.qty',
        'sew4pm.qty',
        'sew5pm.qty',
        'sew6pm.qty',
        'sew7pm.qty',
        'sew8pm.qty',
        'sew9pm.qty',
        'sew10pm.qty',
        'sew11pm.qty',
        'sew12am.qty',
      ])
      ->orderBy('wstudy_line_setups.id')
      ->get()
      ->map(function ($prodgmtsewing) use ($lineCode, $lineFloor, $lineCheif, $capacityQty, $capacityAmount, $buyerName, $orderNo, $orderQty, $orderAmount, $itemAccounts, $itemSmv, $imagepath, $date_to, $totday, $currentItem, $prodHourArr) {

        $sewing_start_at = Carbon::parse($prodgmtsewing->sewing_start_at);
        $sewing_end_at = Carbon::parse($prodgmtsewing->sewing_end_at);
        $lunch_start_at = Carbon::parse($prodgmtsewing->lunch_start_at);
        $lunch_end_at = Carbon::parse($prodgmtsewing->lunch_end_at);
        $lunch = $lunch_start_at->diffInHours($lunch_end_at);
        $hourUpto = $sewing_start_at->diffInHours($sewing_end_at);
        $now = Carbon::now();


        if ($now <= $sewing_end_at) {
          $hourUpto = $sewing_start_at->diffInHours($now);
        }
        if ($now >= $lunch_end_at) {
          $hourUpto = $hourUpto - $lunch;
        }
        if ($date_to != date('Y-m-d')) {
          $hourUpto = $sewing_start_at->diffInHours($sewing_end_at);
          $hourUpto = $hourUpto - $lunch;
        }

        $hourUpto = $hourUpto ? $hourUpto : count($prodHourArr[$prodgmtsewing->id]);

        $prodgmtsewing->hourUpto = $hourUpto;



        $prodgmtsewing->minute_addjust = $prodgmtsewing->no_of_minute_plus - $prodgmtsewing->no_of_minute_minus;
        $prodgmtsewing->target_per_hour = $prodgmtsewing->target_per_hour;

        $prodgmtsewing->sew_qc_date = $date_to;
        $order_qty = 0;
        if (isset($orderQty[$prodgmtsewing->id])) {
          $order_qty = array_sum($orderQty[$prodgmtsewing->id]);
        }
        $order_amount = 0;
        if (isset($orderAmount[$prodgmtsewing->id])) {
          $order_amount = array_sum($orderAmount[$prodgmtsewing->id]);
        }
        $prodgmtsewing->buyer_code = 0;
        if (isset($buyerName[$prodgmtsewing->id])) {
          $prodgmtsewing->buyer_code = implode(',', $buyerName[$prodgmtsewing->id]);
        }
        $prodgmtsewing->sale_order_no = '';
        if (isset($orderNo[$prodgmtsewing->id])) {
          $prodgmtsewing->sale_order_no = implode(',', $orderNo[$prodgmtsewing->id]);
        }
        $prodgmtsewing->line = '';
        if (isset($lineCode[$prodgmtsewing->id])) {
          $prodgmtsewing->line = implode(',', $lineCode[$prodgmtsewing->id]);
        }
        $prodgmtsewing->floor = '';
        if (isset($lineFloor[$prodgmtsewing->id])) {
          $prodgmtsewing->floor = implode(',', $lineFloor[$prodgmtsewing->id]);
        }

        $prodgmtsewing->item_description = '';

        if (isset($itemAccounts[$prodgmtsewing->id])) {
          $prodgmtsewing->item_description = implode(',', $itemAccounts[$prodgmtsewing->id]);
        }

        $prodgmtsewing->smv = 0;
        if (isset($itemSmv[$prodgmtsewing->id])) {
          $prodgmtsewing->smv = implode(' / ', $itemSmv[$prodgmtsewing->id]);
        }


        //$imagepath[$result->id][$result->style_id]=$result->flie_src;

        $prodgmtsewing->flie_src = '';
        if (isset($imagepath[$prodgmtsewing->id])) {
          $prodgmtsewing->flie_src = implode(',', $imagepath[$prodgmtsewing->id]);
        }

        $prodgmtsewing->totday = '';
        if (isset($totday[$prodgmtsewing->id])) {
          $prodgmtsewing->totday = implode(',', $totday[$prodgmtsewing->id]);
        }


        $prodgmtsewing->apm = $prodgmtsewing->line_chief;
        $prodgmtsewing->manpower = $prodgmtsewing->operator + $prodgmtsewing->helper;

        $prodgmtsewing->actual_terget = 0;
        if (isset($currentItem[$prodgmtsewing->id])) {
          $prodgmtsewing->actual_terget = ($prodgmtsewing->manpower * 60) * ($currentItem[$prodgmtsewing->id]['sewing_effi_per'] / 100) / ($currentItem[$prodgmtsewing->id]['smv']);
        }

        $capacity_qty = 0;
        if (isset($capacityQty[$prodgmtsewing->id])) {
          $capacity_qty = array_sum($capacityQty[$prodgmtsewing->id]);
        }

        $capacity_dev = $prodgmtsewing->sew_qty - $capacity_qty;

        $prodgmtsewing->wh = $prodgmtsewing->working_hour + $prodgmtsewing->overtime_hour;
        //$used_mint=$prodgmtsewing->manpower*$prodgmtsewing->wh*60;
        $used_mint = ($prodgmtsewing->manpower * $hourUpto * 60) + ($prodgmtsewing->minute_addjust);
        //target
        //$day_target=$prodgmtsewing->target_per_hour*$prodgmtsewing->wh;
        $day_target = $prodgmtsewing->target_per_hour * $hourUpto;
        $target_per_hour_ach = 0;
        if ($day_target) {
          $target_per_hour_ach = ($prodgmtsewing->sew_qty / $day_target) * 100;
        }
        $target_per_hour_var = $prodgmtsewing->sew_qty - $day_target;
        //target

        $prodgmtsewing->smv_used = 0;
        $prodgmtsewing->avg_smv_pcs = 0;
        if ($prodgmtsewing->sew_qty) {
          $prodgmtsewing->smv_used = $used_mint / $prodgmtsewing->sew_qty;
          $prodgmtsewing->avg_smv_pcs = $prodgmtsewing->produced_mint / $prodgmtsewing->sew_qty;
        }
        $prodgmtsewing->dev_avg_smv_pcs = $prodgmtsewing->avg_smv_pcs - $prodgmtsewing->smv_used;

        $prodgmtsewing->cm_pcs = $prodgmtsewing->avg_smv_pcs * $prodgmtsewing->cpm_amount * 82;
        $prodgmtsewing->cm_used_pcs = $prodgmtsewing->smv_used * $prodgmtsewing->cpm_amount * 82;
        $prodgmtsewing->dev_cm_pcs = $prodgmtsewing->cm_pcs - $prodgmtsewing->cm_used_pcs;

        //$prodgmtsewing->smv_used=number_format($prodgmtsewing->smv_used,2);
        //$prodgmtsewing->avg_smv_pcs=number_format($prodgmtsewing->avg_smv_pcs,2);
        //$prodgmtsewing->dev_avg_smv_pcs=number_format($prodgmtsewing->dev_avg_smv_pcs,2);

        //$prodgmtsewing->cm_pcs=number_format($prodgmtsewing->cm_pcs,2);
        //$prodgmtsewing->cm_used_pcs=number_format($prodgmtsewing->cm_used_pcs,2);
        //$prodgmtsewing->dev_cm_pcs=number_format($prodgmtsewing->dev_cm_pcs,2);

        $prodgmtsewing->used_mint = $used_mint;
        $prodgmtsewing->effi_per_value = 0;
        $prodgmtsewing->effi_per = 0;
        if ($used_mint) {
          $prodgmtsewing->effi_per_value = $prodgmtsewing->produced_mint / $used_mint * 100;
          $prodgmtsewing->effi_per = ($prodgmtsewing->produced_mint / $used_mint) * 100;
        }



        $commmercial = $prodgmtsewing->yarn_amount + $prodgmtsewing->trim_amount + $prodgmtsewing->fabric_prod_amount + $prodgmtsewing->emb_amount;
        $commer_amount = ($prodgmtsewing->commer_rate / 100) * $commmercial;
        $commi_amount = ($prodgmtsewing->commi_rate / 100) * $prodgmtsewing->amount;
        $total_cost = $commmercial + $commer_amount + $commi_amount + $prodgmtsewing->other_amount;
        /*$cm=$order_amount-$total_cost;
      $prodgmtsewing->cm_earned_usd=$cm;
      $prodgmtsewing->cm_earned_tk=$cm*82;*/
        $mkt_cm = $prodgmtsewing->mkt_cm;
        $cm = $prodgmtsewing->cm_earned_usd;
        $cmdzn = 0;
        $cmdzn = $cm * 12;
        $cm_earned_usd = $cm;
        $prodgmtsewing->cm_earned_usd = $cm_earned_usd;
        $prodgmtsewing->cm_earned_tk = $cm_earned_usd * 82;
        $prodgmtsewing->cm = $cmdzn;
        $prodgmtsewing->mkt_cm = $mkt_cm;
        $prodgmtsewing->mkt_cm_tk = $mkt_cm * 82;
        $prodgmtsewing->qty = $order_qty;
        $prodgmtsewing->amount = $order_amount;
        $prodgmtsewing->capacity_qty = $capacity_qty;
        $prodgmtsewing->capacity_dev = $capacity_dev;
        $prodgmtsewing->capacity_ach = 0;
        if ($capacity_qty) {
          $prodgmtsewing->capacity_ach = ($prodgmtsewing->sew_qty / $capacity_qty) * 100;
        }
        $prodgmtsewing->sew_qty = $prodgmtsewing->sew_qty;
        $prodgmtsewing->prodused_fob_tk = $prodgmtsewing->prodused_fob * 82;
        $prodgmtsewing->prodused_fob = $prodgmtsewing->prodused_fob;
        $prodgmtsewing->produced_mint = $prodgmtsewing->produced_mint;
        $prodgmtsewing->day_target = $day_target;
        $prodgmtsewing->target_per_hour_ach = $target_per_hour_ach;
        $prodgmtsewing->target_per_hour_var = $target_per_hour_var;
        $prodgmtsewing->actual_terget = $prodgmtsewing->actual_terget;
        return $prodgmtsewing;
      });
    $data = array();
    foreach ($prodgmtsewing as $prodgmtsewingrow) {
      $data[$prodgmtsewingrow->company_id]['company_name'] = $prodgmtsewingrow->company_id;
      $data[$prodgmtsewingrow->company_id]['company_ceo'] = $prodgmtsewingrow->ceo;
      $data[$prodgmtsewingrow->company_id]['company_contact'] = $prodgmtsewingrow->contact;
      $data[$prodgmtsewingrow->company_id]['total_line'] = $subsectionarr[$prodgmtsewingrow->company_id];
      isset($data[$prodgmtsewingrow->company_id]['no_of_line']) ? $data[$prodgmtsewingrow->company_id]['no_of_line'] += 1 : $data[$prodgmtsewingrow->company_id]['no_of_line'] = 1;
      isset($data[$prodgmtsewingrow->company_id]['mp_engaged']) ? $data[$prodgmtsewingrow->company_id]['mp_engaged'] += $prodgmtsewingrow->manpower : $data[$prodgmtsewingrow->company_id]['mp_engaged'] = $prodgmtsewingrow->manpower;
      isset($data[$prodgmtsewingrow->company_id]['used_mint']) ? $data[$prodgmtsewingrow->company_id]['used_mint'] += $prodgmtsewingrow->used_mint : $data[$prodgmtsewingrow->company_id]['used_mint'] = $prodgmtsewingrow->used_mint;
      isset($data[$prodgmtsewingrow->company_id]['produced_mint']) ? $data[$prodgmtsewingrow->company_id]['produced_mint'] += $prodgmtsewingrow->produced_mint : $data[$prodgmtsewingrow->company_id]['produced_mint'] = $prodgmtsewingrow->produced_mint;
      isset($data[$prodgmtsewingrow->company_id]['day_target']) ? $data[$prodgmtsewingrow->company_id]['day_target'] += $prodgmtsewingrow->day_target : $data[$prodgmtsewingrow->company_id]['day_target'] = $prodgmtsewingrow->day_target;

      isset($data[$prodgmtsewingrow->company_id]['sew_qty']) ? $data[$prodgmtsewingrow->company_id]['sew_qty'] += $prodgmtsewingrow->sew_qty : $data[$prodgmtsewingrow->company_id]['sew_qty'] = $prodgmtsewingrow->sew_qty;

      isset($data[$prodgmtsewingrow->company_id]['prodused_fob']) ? $data[$prodgmtsewingrow->company_id]['prodused_fob'] += $prodgmtsewingrow->prodused_fob : $data[$prodgmtsewingrow->company_id]['prodused_fob'] = $prodgmtsewingrow->prodused_fob;

      isset($data[$prodgmtsewingrow->company_id]['effi_per']) ? $data[$prodgmtsewingrow->company_id]['effi_per'] += $prodgmtsewingrow->effi_per : $data[$prodgmtsewingrow->company_id]['effi_per'] = $prodgmtsewingrow->effi_per;
      isset($data[$prodgmtsewingrow->company_id]['target_per_hour_ach']) ? $data[$prodgmtsewingrow->company_id]['target_per_hour_ach'] += $prodgmtsewingrow->target_per_hour_ach : $data[$prodgmtsewingrow->company_id]['target_per_hour_ach'] = $prodgmtsewingrow->target_per_hour_ach;
      isset($data[$prodgmtsewingrow->company_id]['cm_earned_usd']) ? $data[$prodgmtsewingrow->company_id]['cm_earned_usd'] += $prodgmtsewingrow->cm_earned_usd : $data[$prodgmtsewingrow->company_id]['cm_earned_usd'] = $prodgmtsewingrow->cm_earned_usd;
      isset($data[$prodgmtsewingrow->company_id]['mkt_cm']) ? $data[$prodgmtsewingrow->company_id]['mkt_cm'] += $prodgmtsewingrow->mkt_cm : $data[$prodgmtsewingrow->company_id]['mkt_cm'] = $prodgmtsewingrow->mkt_cm;
    }
    $dtldata = $prodgmtsewing->groupBy('company_id');
    $user = \Auth::user();
    return Template::loadView('Report.GmtProduction.DailyEfficiencyReportMatrix', [
      'company' => $company,
      'summary' => $data,
      'dtldata' => $dtldata,
      'user' => $user,
    ]);

    //->sortByDesc('effi_per_value')
    //->values();
    //echo json_encode($prodgmtsewing);
  }

  public function reportDataMonthly()
  {
    $date_from = request('date_from', 0);
    $date_to = request('date_to', 0);
    $company_id = request('company_id', 0);
    $company = array_prepend(array_pluck($this->company->where([['nature_id', '=', 1]])->orderBy('name')->get(), 'code', 'id'), '-Select-', '');

    /*$subsections = \DB::select("
      select
      count(subsections.id) as id,
      company_subsections.company_id
      from
      subsections
      join company_subsections on company_subsections.subsection_id=subsections.id
      where 
      subsections.is_treat_sewing_line=1 
      and subsections.projected_line_id=0 
      and subsections.status_id=1
      and subsections.deleted_at is null
      group by 
      company_subsections.company_id
      order by 
      company_subsections.company_id
      ");
      $subsectionarr=[];
      foreach($subsections as $subsection)
      {
      $subsectionarr[$subsection->company_id]=$subsection->id;
      }*/


    $subsections = $this->wstudylinesetup
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
      })
      ->join('wstudy_line_setup_lines', function ($join) {
        $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
      })
      ->join('wstudy_line_setup_dtls', function ($join) {
        $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
      })
      ->leftJoin('subsections', function ($join) {
        $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
      })
      ->leftJoin('floors', function ($join) {
        $join->on('floors.id', '=', 'subsections.floor_id');
      })
      ->leftJoin('employees', function ($join) {
        $join->on('employees.id', '=', 'subsections.employee_id');
      })
      ->when($date_from, function ($q) use ($date_from) {
        return $q->where('wstudy_line_setup_dtls.from_date', '>=', $date_from);
      })
      ->when($date_to, function ($q) use ($date_to) {
        return $q->where('wstudy_line_setup_dtls.to_date', '<=', $date_to);
      })
      //->where([['wstudy_line_setups.company_id','=',$company_id]])
      ->get([
        'wstudy_line_setups.id',
        'subsections.name',
        'subsections.code',
        'floors.name as floor_name',
        'employees.name as employee_name',
        'subsections.qty',
        'subsections.amount'
      ]);
    $lineNames = array();
    $lineCode = array();
    $lineFloor = array();
    $lineCheif = array();
    $capacityQty = array();
    $capacityAmount = array();
    foreach ($subsections as $subsection) {
      $lineNames[$subsection->id][] = $subsection->name;
      $lineCode[$subsection->id][] = $subsection->code;
      $lineFloor[$subsection->id][] = $subsection->floor_name;
      $lineCheif[$subsection->id][] = $subsection->employee_name;
      $capacityQty[$subsection->id][] = $subsection->qty;
      $capacityAmount[$subsection->id][] = $subsection->amount;
    }


    $results = \DB::select("
      select 
      wstudy_line_setups.id,
      wstudy_line_setups.company_id,
      sales_orders.id as sale_order_id ,
      sales_orders.sale_order_no,
      styles.id as style_id,
      styles.buyer_id,
      styles.flie_src,
      buyers.name,
      style_gmts.id as style_gmt_id,
      style_gmts.smv,
      style_gmts.sewing_effi_per,
      item_accounts.item_description as item_name,
      sales_order_gmt_color_sizes.qty,
      sales_order_gmt_color_sizes.rate,
      totday.day
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
      left join(
      select m.id,m.sale_order_id, count (m.sew_qc_date) as day from (
      select 
      wstudy_line_setups.id,
      sales_orders.id as sale_order_id,
      prod_gmt_sewings.sew_qc_date
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      right join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      where  wstudy_line_setups.company_id=2 --and sales_orders.id=14253 and wstudy_line_setups.id=20
      group by
      wstudy_line_setups.id,
      sales_orders.id,
      prod_gmt_sewings.sew_qc_date
      order by prod_gmt_sewings.sew_qc_date) m group by m.id,m.sale_order_id
      ) totday on totday.id=wstudy_line_setups.id and totday.sale_order_id=sales_orders.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_from . "' and 
      prod_gmt_sewings.sew_qc_date<='" . $date_to . "'
      --and wstudy_line_setups.company_id=?
      group by 
      wstudy_line_setups.id,
      wstudy_line_setups.company_id,
      sales_orders.id,
      styles.id,
      styles.flie_src,
      styles.buyer_id,
      buyers.name,
      sales_orders.sale_order_no,
      sales_order_gmt_color_sizes.id,
      sales_order_gmt_color_sizes.qty,
      sales_order_gmt_color_sizes.rate,
      style_gmts.id,
      item_accounts.item_description,
      style_gmts.smv,
      style_gmts.sewing_effi_per,
      totday.day,
      prod_gmt_sewing_orders.id
      order by prod_gmt_sewing_orders.id
      ");

    $buyerName = array();
    $orderNo = array();
    $orderQty = array();
    $orderAmount = array();
    $itemAccounts = array();
    $itemSmv = array();
    $imagepath = array();
    $totday = array();

    foreach ($results as $result) {
      $amount = $result->qty * $result->rate;
      $buyerName[$result->id][$result->buyer_id] = $result->name;
      $orderNo[$result->id][$result->sale_order_id] = $result->sale_order_no;
      $totday[$result->id][$result->sale_order_id] = $result->day;
      $imagepath[$result->id][$result->style_id] = $result->flie_src;
      if (isset($orderQty[$result->id][$result->sale_order_id])) {
        $orderQty[$result->id][$result->sale_order_id] += $result->qty;
      } else {
        $orderQty[$result->id][$result->sale_order_id] = $result->qty;
      }
      if (isset($orderAmount[$result->id][$result->sale_order_id])) {
        $orderAmount[$result->id][$result->sale_order_id] += $amount;
      } else {
        $orderAmount[$result->id][$result->sale_order_id] = $amount;
      }
    }

    $current_items = \DB::select("
      select 
      wstudy_line_setups.id,
      wstudy_line_setups.company_id,
      sales_orders.id as sale_order_id ,
      sales_orders.sale_order_no,
      styles.id as style_id,
      styles.buyer_id,
      styles.flie_src,
      buyers.name,
      style_gmts.id as style_gmt_id,
      style_gmts.smv,
      style_gmts.sewing_effi_per,
      item_accounts.item_description as item_name
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id and sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
      join styles on styles.id = style_gmts.style_id
      join buyers on buyers.id=styles.buyer_id
      join item_accounts on item_accounts.id=style_gmts.item_account_id
      right join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 

      where prod_gmt_sewings.sew_qc_date>='" . $date_from . "' and 
      prod_gmt_sewings.sew_qc_date<='" . $date_to . "'
      --and wstudy_line_setups.company_id=?
      group by 
      wstudy_line_setups.id,
      wstudy_line_setups.company_id,
      sales_orders.id,
      styles.id,
      styles.flie_src,
      styles.buyer_id,
      buyers.name,
      sales_orders.sale_order_no,
      sales_order_gmt_color_sizes.id,
      sales_order_gmt_color_sizes.qty,
      sales_order_gmt_color_sizes.rate,
      style_gmts.id,
      item_accounts.item_description,
      style_gmts.smv,
      style_gmts.sewing_effi_per,
      prod_gmt_sewing_orders.id
      order by prod_gmt_sewing_orders.id
      ");


    $currentItem = array();

    foreach ($current_items as $current_item) {
      $currentItem[$current_item->id]['smv'] = $current_item->smv;
      $currentItem[$current_item->id]['sewing_effi_per'] = $current_item->sewing_effi_per;
      $itemAccounts[$current_item->id][$current_item->style_gmt_id] = $current_item->item_name;
      $itemSmv[$current_item->id][$current_item->style_gmt_id] = $current_item->smv;
    }




    $prodgmtsewing = $this->wstudylinesetup
      ->join('wstudy_line_setup_dtls', function ($join) use ($date_to, $date_from) {
        $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        $join->where('wstudy_line_setup_dtls.from_date', '>=', $date_from);
        $join->where('wstudy_line_setup_dtls.to_date', '<=', $date_to);
      })
      ->leftJoin(\DB::raw("(SELECT 
      wstudy_line_setups.id,
      wstudy_line_setup_dtls.id as wstudy_line_setup_dtl_id,
      wstudy_line_setup_dtls.from_date,
      sum(wstudy_line_setup_min_adjs.no_of_minute) as no_of_minute_minus
      from
      wstudy_line_setups
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id 
      join wstudy_line_setup_min_adjs on wstudy_line_setup_min_adjs.wstudy_line_setup_dtl_id = wstudy_line_setup_dtls.id 
      where wstudy_line_setup_min_adjs.minute_adj_reason_id in(1,2,3,4,5,7)
      and wstudy_line_setup_dtls.from_date>='" . $date_from . "' and 
      wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      group by 
      wstudy_line_setups.id,
      wstudy_line_setup_dtls.id,
      wstudy_line_setup_dtls.from_date) minusaddj"), "minusaddj.wstudy_line_setup_dtl_id", "=", "wstudy_line_setup_dtls.id")
      ->leftJoin(\DB::raw("(SELECT 
      wstudy_line_setups.id,
      wstudy_line_setup_dtls.id as wstudy_line_setup_dtl_id,
      wstudy_line_setup_dtls.from_date,
      sum(wstudy_line_setup_min_adjs.no_of_minute) as no_of_minute_plus
      from
      wstudy_line_setups
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id 
      join wstudy_line_setup_min_adjs on wstudy_line_setup_min_adjs.wstudy_line_setup_dtl_id = wstudy_line_setup_dtls.id 
      where wstudy_line_setup_min_adjs.minute_adj_reason_id in(6)
      and wstudy_line_setup_dtls.from_date>='" . $date_from . "' and 
      wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      group by 
      wstudy_line_setups.id,
      wstudy_line_setup_dtls.id,
      wstudy_line_setup_dtls.from_date) plusaddj"), "plusaddj.wstudy_line_setup_dtl_id", "=", "wstudy_line_setup_dtls.id")

      ->leftJoin(\DB::raw("(
      SELECT 
      m.id,
      m.wstudy_line_setup_dtl_id,
      sum(m.qty) as qty,
      sum(m.amount) as amount,
      sum(m.smv) as smv from (
      SELECT 
      wstudy_line_setups.id,
      wstudy_line_setup_dtls.id as wstudy_line_setup_dtl_id,
      prod_gmt_sewing_qties.qty,
      prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
      prod_gmt_sewing_qties.qty*style_gmts.smv as smv
      FROM prod_gmt_sewings
      join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
      join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and prod_gmt_sewings.sew_qc_date=wstudy_line_setup_dtls.from_date and
      wstudy_line_setup_dtls.from_date>='" . $date_from . "' and 
      wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
      join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
      join jobs on jobs.id = sales_orders.job_id
      join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
      join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
      join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

      join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
      where prod_gmt_sewings.sew_qc_date>='" . $date_from . "' and 
      prod_gmt_sewings.sew_qc_date<='" . $date_to . "'
      group by 
      wstudy_line_setups.id,
      wstudy_line_setup_dtls.id,
      prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
      sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
      style_gmts.smv
      ) m group by m.id,m.wstudy_line_setup_dtl_id) sew"), "sew.wstudy_line_setup_dtl_id", "=", "wstudy_line_setup_dtls.id")
      ->leftJoin(\DB::raw("(
        SELECT 
        m.id,
        m.wstudy_line_setup_dtl_id,
        sum(m.qty) as qty,
        sum(m.amount) as amount 
        from (
        SELECT 
        wstudy_line_setups.id,
        wstudy_line_setup_dtls.id as wstudy_line_setup_dtl_id,
        prod_gmt_sewing_qties.qty,
        prod_gmt_sewing_qties.qty*(budget_cms.cm_per_pcs) as amount
        FROM prod_gmt_sewings
        join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
        join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
        join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and prod_gmt_sewings.sew_qc_date=wstudy_line_setup_dtls.from_date and
        wstudy_line_setup_dtls.from_date>='" . $date_from . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
        join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join jobs on jobs.id = sales_orders.job_id
        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
        join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
        join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

        join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
        join budgets on budgets.style_id=style_gmts.style_id
        join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id
        where prod_gmt_sewings.sew_qc_date>='" . $date_from . "' and 
        prod_gmt_sewings.sew_qc_date<='" . $date_to . "'
        group by 
        wstudy_line_setups.id,
        wstudy_line_setup_dtls.id,
        prod_gmt_sewing_qties.id,
        prod_gmt_sewing_qties.qty,
        sales_order_gmt_color_sizes.id,
        budget_cms.cm_per_pcs
      ) m group by m.id,m.wstudy_line_setup_dtl_id) sewCM"), "sewCM.wstudy_line_setup_dtl_id", "=", "wstudy_line_setup_dtls.id")
      ->leftJoin(\DB::raw("(SELECT m.id,sum(m.amount) as amount from (
      SELECT 
      wstudy_line_setups.id,
      mkt_cost_cms.prod_per_hour*wstudy_line_setup_dtl_ords.prod_hour*mkt_cost_cms.cm_per_pcs as amount
      FROM wstudy_line_setups
      join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id 
      join wstudy_line_setup_dtl_ords on wstudy_line_setup_dtl_ords.wstudy_line_setup_dtl_id = wstudy_line_setup_dtls.id 
      
      join mkt_cost_cms on mkt_cost_cms.style_gmt_id=wstudy_line_setup_dtl_ords.style_gmt_id
      where wstudy_line_setup_dtls.from_date>='" . $date_from . "' and 
        wstudy_line_setup_dtls.to_date<='" . $date_to . "'
      
      ) m group by m.id) mktCM"), "mktCM.id", "=", "wstudy_line_setups.id")
      ->leftJoin('companies', function ($join) {
        $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
      })
      ->when($date_from, function ($q) use ($date_from) {
        return $q->where('wstudy_line_setup_dtls.from_date', '>=', $date_from);
      })
      ->when($date_to, function ($q) use ($date_to) {
        return $q->where('wstudy_line_setup_dtls.to_date', '<=', $date_to);
      })
      ->selectRaw(
        'wstudy_line_setups.id,
      wstudy_line_setups.company_id,
      wstudy_line_setup_dtls.id as wstudy_line_setup_dtl_id,
      wstudy_line_setup_dtls.from_date,
      wstudy_line_setup_dtls.line_chief,
      wstudy_line_setup_dtls.operator,
      wstudy_line_setup_dtls.helper,
      wstudy_line_setup_dtls.working_hour,
      wstudy_line_setup_dtls.overtime_hour,
      wstudy_line_setup_dtls.target_per_hour,
      wstudy_line_setup_dtls.remarks,
      wstudy_line_setup_dtls.sewing_start_at,      
      wstudy_line_setup_dtls.sewing_end_at,        
      wstudy_line_setup_dtls.lunch_start_at,        
      wstudy_line_setup_dtls.lunch_end_at,
      companies.ceo,
      companies.contact,
      sew.qty as sew_qty,
      sew.amount as prodused_fob,
      sew.smv as produced_mint,
      sewCM.amount as cm_earned_usd,
      mktCM.amount as mkt_cm,
      minusaddj.no_of_minute_minus,
      plusaddj.no_of_minute_plus
      '
      )
      ->orderBy('wstudy_line_setup_dtls.from_date')
      ->get()
      ->map(function ($prodgmtsewing) use ($lineCode, $lineFloor, $lineCheif, $capacityQty, $capacityAmount, $buyerName, $orderNo, $orderQty, $orderAmount, $itemAccounts, $itemSmv, $imagepath, $date_to, $totday, $currentItem) {
        $sewing_start_at = Carbon::parse($prodgmtsewing->sewing_start_at);
        $sewing_end_at = Carbon::parse($prodgmtsewing->sewing_end_at);
        $lunch_start_at = Carbon::parse($prodgmtsewing->lunch_start_at);
        $lunch_end_at = Carbon::parse($prodgmtsewing->lunch_end_at);
        $lunch = $lunch_start_at->diffInHours($lunch_end_at);
        $hourUpto = $sewing_start_at->diffInHours($sewing_end_at);
        $now = Carbon::now();


        if ($now <= $sewing_end_at) {
          $hourUpto = $sewing_start_at->diffInHours($now);
        }
        if ($now >= $lunch_end_at) {
          $hourUpto = $hourUpto - $lunch;
        }
        if ($date_to != date('Y-m-d')) {
          $hourUpto = $sewing_start_at->diffInHours($sewing_end_at);
          $hourUpto = $hourUpto - $lunch;
        }

        //$hourUpto=$hourUpto?$hourUpto:count($prodHourArr[$prodgmtsewing->id]);

        $prodgmtsewing->hourUpto = $hourUpto;



        $prodgmtsewing->minute_addjust = $prodgmtsewing->no_of_minute_plus - $prodgmtsewing->no_of_minute_minus;
        $prodgmtsewing->target_per_hour = $prodgmtsewing->target_per_hour;

        $prodgmtsewing->sew_qc_date = $date_to;

        $order_qty = 0;
        if (isset($orderQty[$prodgmtsewing->id])) {
          $order_qty = array_sum($orderQty[$prodgmtsewing->id]);
        }
        $order_amount = 0;
        if (isset($orderAmount[$prodgmtsewing->id])) {
          $order_amount = array_sum($orderAmount[$prodgmtsewing->id]);
        }
        $prodgmtsewing->buyer_code = 0;
        if (isset($buyerName[$prodgmtsewing->id])) {
          $prodgmtsewing->buyer_code = implode(',', $buyerName[$prodgmtsewing->id]);
        }
        $prodgmtsewing->sale_order_no = '';
        if (isset($orderNo[$prodgmtsewing->id])) {
          $prodgmtsewing->sale_order_no = implode(',', $orderNo[$prodgmtsewing->id]);
        }
        $prodgmtsewing->line = '';
        if (isset($lineCode[$prodgmtsewing->id])) {
          $prodgmtsewing->line = implode(',', $lineCode[$prodgmtsewing->id]);
        }
        $prodgmtsewing->floor = '';
        if (isset($lineFloor[$prodgmtsewing->id])) {
          $prodgmtsewing->floor = implode(',', $lineFloor[$prodgmtsewing->id]);
        }

        $prodgmtsewing->item_description = '';

        if (isset($itemAccounts[$prodgmtsewing->id])) {
          $prodgmtsewing->item_description = implode(',', $itemAccounts[$prodgmtsewing->id]);
        }

        $prodgmtsewing->smv = 0;
        if (isset($itemSmv[$prodgmtsewing->id])) {
          $prodgmtsewing->smv = implode(' / ', $itemSmv[$prodgmtsewing->id]);
        }



        $prodgmtsewing->flie_src = '';
        if (isset($imagepath[$prodgmtsewing->id])) {
          $prodgmtsewing->flie_src = implode(',', $imagepath[$prodgmtsewing->id]);
        }

        $prodgmtsewing->totday = '';
        if (isset($totday[$prodgmtsewing->id])) {
          $prodgmtsewing->totday = implode(',', $totday[$prodgmtsewing->id]);
        }


        $prodgmtsewing->apm = $prodgmtsewing->line_chief;
        $prodgmtsewing->manpower = $prodgmtsewing->operator + $prodgmtsewing->helper;

        $prodgmtsewing->actual_terget = 0;
        if (isset($currentItem[$prodgmtsewing->id])) {
          $prodgmtsewing->actual_terget = ($prodgmtsewing->manpower * 60) * ($currentItem[$prodgmtsewing->id]['sewing_effi_per'] / 100) / ($currentItem[$prodgmtsewing->id]['smv']);
        }

        $capacity_qty = 0;
        if (isset($capacityQty[$prodgmtsewing->id])) {
          $capacity_qty = array_sum($capacityQty[$prodgmtsewing->id]);
        }

        $capacity_dev = $prodgmtsewing->sew_qty - $capacity_qty;

        $prodgmtsewing->wh = $prodgmtsewing->working_hour + $prodgmtsewing->overtime_hour;
        //$used_mint=$prodgmtsewing->manpower*$prodgmtsewing->wh*60;
        $used_mint = ($prodgmtsewing->manpower * $hourUpto * 60) + ($prodgmtsewing->minute_addjust);
        //target
        //$day_target=$prodgmtsewing->target_per_hour*$prodgmtsewing->wh;
        $day_target = $prodgmtsewing->target_per_hour * $hourUpto;
        $target_per_hour_ach = 0;
        if ($day_target) {
          $target_per_hour_ach = ($prodgmtsewing->sew_qty / $day_target) * 100;
        }
        $target_per_hour_var = $prodgmtsewing->sew_qty - $day_target;
        //target

        $prodgmtsewing->smv_used = 0;
        $prodgmtsewing->avg_smv_pcs = 0;
        if ($prodgmtsewing->sew_qty) {
          $prodgmtsewing->smv_used = $used_mint / $prodgmtsewing->sew_qty;
          $prodgmtsewing->avg_smv_pcs = $prodgmtsewing->produced_mint / $prodgmtsewing->sew_qty;
        }
        $prodgmtsewing->dev_avg_smv_pcs = $prodgmtsewing->avg_smv_pcs - $prodgmtsewing->smv_used;

        $prodgmtsewing->cm_pcs = $prodgmtsewing->avg_smv_pcs * $prodgmtsewing->cpm_amount * 82;
        $prodgmtsewing->cm_used_pcs = $prodgmtsewing->smv_used * $prodgmtsewing->cpm_amount * 82;
        $prodgmtsewing->dev_cm_pcs = $prodgmtsewing->cm_pcs - $prodgmtsewing->cm_used_pcs;

        $prodgmtsewing->used_mint = $used_mint;
        $prodgmtsewing->effi_per_value = 0;
        $prodgmtsewing->effi_per = 0;
        if ($used_mint) {
          $prodgmtsewing->effi_per_value = $prodgmtsewing->produced_mint / $used_mint * 100;
          $prodgmtsewing->effi_per = ($prodgmtsewing->produced_mint / $used_mint) * 100;
        }



        $commmercial = $prodgmtsewing->yarn_amount + $prodgmtsewing->trim_amount + $prodgmtsewing->fabric_prod_amount + $prodgmtsewing->emb_amount;
        $commer_amount = ($prodgmtsewing->commer_rate / 100) * $commmercial;
        $commi_amount = ($prodgmtsewing->commi_rate / 100) * $prodgmtsewing->amount;
        $total_cost = $commmercial + $commer_amount + $commi_amount + $prodgmtsewing->other_amount;
        /*$cm=$order_amount-$total_cost;
        $prodgmtsewing->cm_earned_usd=$cm;
        $prodgmtsewing->cm_earned_tk=$cm*82;*/
        $mkt_cm = $prodgmtsewing->mkt_cm;
        $cm = $prodgmtsewing->cm_earned_usd;
        $cmdzn = 0;
        $cmdzn = $cm * 12;
        $cm_earned_usd = $cm;
        $prodgmtsewing->cm_earned_usd = $cm_earned_usd;
        $prodgmtsewing->cm_earned_tk = $cm_earned_usd * 82;
        $prodgmtsewing->cm = $cmdzn;
        $prodgmtsewing->mkt_cm = $mkt_cm;
        $prodgmtsewing->mkt_cm_tk = $mkt_cm * 82;
        $prodgmtsewing->qty = $order_qty;
        $prodgmtsewing->amount = $order_amount;
        $prodgmtsewing->capacity_qty = $capacity_qty;
        $prodgmtsewing->capacity_dev = $capacity_dev;
        $prodgmtsewing->capacity_ach = 0;
        if ($capacity_qty) {
          $prodgmtsewing->capacity_ach = ($prodgmtsewing->sew_qty / $capacity_qty) * 100;
        }
        $prodgmtsewing->sew_qty = $prodgmtsewing->sew_qty;
        $prodgmtsewing->prodused_fob_tk = $prodgmtsewing->prodused_fob * 82;
        $prodgmtsewing->prodused_fob = $prodgmtsewing->prodused_fob;
        $prodgmtsewing->produced_mint = $prodgmtsewing->produced_mint;
        $prodgmtsewing->day_target = $day_target;
        $prodgmtsewing->target_per_hour_ach = $target_per_hour_ach;
        $prodgmtsewing->target_per_hour_var = $target_per_hour_var;
        $prodgmtsewing->actual_terget = $prodgmtsewing->actual_terget;
        $prodgmtsewing->from_date = date('d-M-y', strtotime($prodgmtsewing->from_date));
        return $prodgmtsewing;
      });


    $dataDate = array();
    $data = [];
    $dataSum = [];
    foreach ($prodgmtsewing as $prodgmtsewingrow) {
      $dataDate[$prodgmtsewingrow->from_date] = $prodgmtsewingrow->from_date;

      if (isset($data['produced_mint'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date])) {
        $data['produced_mint'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->produced_mint;
      } else {
        $data['produced_mint'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->produced_mint;
      }

      if (isset($dataSum['produced_mint'][$prodgmtsewingrow->from_date])) {
        $dataSum['produced_mint'][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->produced_mint;
      } else {
        $dataSum['produced_mint'][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->produced_mint;
      }


      if (isset($data['used_mint'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date])) {
        $data['used_mint'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->used_mint;
      } else {
        $data['used_mint'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->used_mint;
      }

      if (isset($dataSum['used_mint'][$prodgmtsewingrow->from_date])) {
        $dataSum['used_mint'][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->used_mint;
      } else {
        $dataSum['used_mint'][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->used_mint;
      }

      if (isset($data['day_target'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date])) {
        $data['day_target'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->day_target;
      } else {
        $data['day_target'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->day_target;
      }

      if (isset($dataSum['day_target'][$prodgmtsewingrow->from_date])) {
        $dataSum['day_target'][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->day_target;
      } else {
        $dataSum['day_target'][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->day_target;
      }

      /*if(isset($data['target_per_hour_ach'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date])){
          $data['target_per_hour_ach'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date]+=$prodgmtsewingrow->target_per_hour_ach;
        }
        else{
         $data['target_per_hour_ach'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date]=$prodgmtsewingrow->target_per_hour_ach;
        }*/

      if (isset($data['sew_qty'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date])) {
        $data['sew_qty'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->sew_qty;
      } else {
        $data['sew_qty'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->sew_qty;
      }

      if (isset($dataSum['sew_qty'][$prodgmtsewingrow->from_date])) {
        $dataSum['sew_qty'][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->sew_qty;
      } else {
        $dataSum['sew_qty'][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->sew_qty;
      }

      if (isset($data['prodused_fob'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date])) {
        $data['prodused_fob'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->prodused_fob;
      } else {
        $data['prodused_fob'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->prodused_fob;
      }

      if (isset($dataSum['prodused_fob'][$prodgmtsewingrow->from_date])) {
        $dataSum['prodused_fob'][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->prodused_fob;
      } else {
        $dataSum['prodused_fob'][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->prodused_fob;
      }

      if (isset($data['cm_earned_usd'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date])) {
        $data['cm_earned_usd'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->cm_earned_usd;
      } else {
        $data['cm_earned_usd'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->cm_earned_usd;
      }

      if (isset($dataSum['cm_earned_usd'][$prodgmtsewingrow->from_date])) {
        $dataSum['cm_earned_usd'][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->cm_earned_usd;
      } else {
        $dataSum['cm_earned_usd'][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->cm_earned_usd;
      }

      if (isset($data['mkt_cm'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date])) {
        $data['mkt_cm'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->mkt_cm;
      } else {
        $data['mkt_cm'][$prodgmtsewingrow->company_id][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->mkt_cm;
      }

      if (isset($dataSum['mkt_cm'][$prodgmtsewingrow->from_date])) {
        $dataSum['mkt_cm'][$prodgmtsewingrow->from_date] += $prodgmtsewingrow->mkt_cm;
      } else {
        $dataSum['mkt_cm'][$prodgmtsewingrow->from_date] = $prodgmtsewingrow->mkt_cm;
      }
    }
    //echo "string";die;
    $user = \Auth::user();
    return Template::loadView('Report.GmtProduction.MonthlyEfficiencyReportMatrix', [
      'company' => $company,
      'dataDates' => $dataDate,
      'summary' => $data,
      'dataSum' => $dataSum,
      'user' => $user,
    ]);
  }

  public function reportDataDetails()
  {

    $prodgmtsewing = $this->wstudylinesetup
      ->selectRaw(
        'wstudy_line_setups.id,
        sales_orders.sale_order_no,
        styles.style_ref,
        item_accounts.item_description,
        colors.name as color_name,
        sizes.name as size_name,
        style_gmts.smv,
        style_gmts.sewing_effi_per,

        sum(prod_gmt_sewing_qties.qty) as sew_qty
        '
      )
      ->join('prod_gmt_sewing_orders', function ($join) {
        $join->on('prod_gmt_sewing_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
      })
      ->join('prod_gmt_sewings', function ($join) {
        $join->on('prod_gmt_sewings.id', '=', 'prod_gmt_sewing_orders.prod_gmt_sewing_id');
      })
      ->join('sales_order_countries', function ($join) {
        $join->on('sales_order_countries.id', '=', 'prod_gmt_sewing_orders.sales_order_country_id');
      })
      ->join('sales_orders', function ($join) {
        $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
      })
      ->join('jobs', function ($join) {
        $join->on('jobs.id', '=', 'sales_orders.job_id');
      })
      ->join('styles', function ($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
      })
      ->join('sales_order_gmt_color_sizes', function ($join) {
        $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
      })

      ->join('style_gmt_color_sizes', function ($join) {
        $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
      })
      ->join('style_gmts', function ($join) {
        $join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
      })
      ->join('item_accounts', function ($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('style_sizes', function ($join) {
        $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
      })
      ->join('sizes', function ($join) {
        $join->on('sizes.id', '=', 'style_sizes.size_id');
      })
      ->join('style_colors', function ($join) {
        $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
      })
      ->join('colors', function ($join) {
        $join->on('colors.id', '=', 'style_colors.color_id');
      })
      ->join('prod_gmt_sewing_qties', function ($join) {
        $join->on('prod_gmt_sewing_qties.prod_gmt_sewing_order_id', '=', 'prod_gmt_sewing_orders.id');
        $join->on('prod_gmt_sewing_qties.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
      })
      ->when(request('sew_date', 0), function ($q) {
        return $q->where('prod_gmt_sewings.sew_qc_date', '=', request('sew_date', 0));
      })
      ->when(request('sew_hour', 0), function ($q) {
        return $q->where('prod_gmt_sewing_orders.prod_hour', '=', request('sew_hour', 0));
      })
      ->where([['wstudy_line_setups.id', '=', request('wstudy_line_setup_id', 0)]])
      //->where([['wstudy_line_setups.company_id','=',request('company_id',0)]])
      ->groupBy([
        'wstudy_line_setups.id',
        'sales_orders.sale_order_no',
        'styles.style_ref',
        'item_accounts.item_description',
        'style_gmts.id',
        'colors.name',
        'sizes.name',
        'style_gmts.smv',
        'style_gmts.sewing_effi_per',
      ])
      ->get();
    echo json_encode($prodgmtsewing);



    /*$prodgmtsewing=$this->prodgmtsewing
        ->join('prod_gmt_sewing_orders', function($join)  {
        $join->on('prod_gmt_sewing_orders.prod_gmt_sewing_id', '=', 'prod_gmt_sewings.id');
        })
        ->join('sales_order_countries', function($join)  {
        $join->on('sales_order_countries.id', '=', 'prod_gmt_sewing_orders.sales_order_country_id');
        })
        ->join('sales_orders', function($join)  {
        $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
        })
        ->join('jobs', function($join)  {
        $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('sales_order_gmt_color_sizes', function($join)  {
        $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
        })

        ->join('style_gmt_color_sizes', function($join)  {
        $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->join('style_gmts', function($join)  {
        $join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
        })
        ->join('style_sizes', function($join)  {
        $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
        })
        ->join('style_colors', function($join)  {
        $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
        })
        ->join('prod_gmt_sewing_qties', function($join)  {
        $join->on('prod_gmt_sewing_qties.prod_gmt_sewing_order_id', '=', 'prod_gmt_sewing_orders.id');
        $join->on('prod_gmt_sewing_qties.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
        })
        ->when(request('sew_date',0), function ($q){
        return $q->where('prod_gmt_sewings.sew_qc_date', '=',request('sew_date',0));
        })
        ->when(request('sew_hour',0), function ($q){
        return $q->where('prod_gmt_sewings.prod_hour', '=',request('sew_hour',0));
        })
        ->where([['prod_gmt_sewing_orders.wstudy_line_setup_id','=',request('wstudy_line_setup_id',0)]])
        ->get();
        echo json_encode($prodgmtsewing);*/


    /*$prodgmtsewing=$this->wstudylinesetup
        ->join('wstudy_line_setup_dtls', function($join) use($date_to) {
        $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        $join->where('wstudy_line_setup_dtls.from_date', '>=',$date_to);
        $join->where('wstudy_line_setup_dtls.to_date', '<=',$date_to);
        })

        ->leftJoin(\DB::raw("(SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from (
        SELECT 
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
        prod_gmt_sewings.sew_qc_date<='".$date_to."' and prod_gmt_sewing_orders.prod_hour='9:00am'
        group by 
        wstudy_line_setups.id,
        prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
        sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
        style_gmts.smv
        ) m group by m.id) sew9am"), "sew9am.id", "=", "wstudy_line_setups.id")




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
        sew9am.qty as sew9am_qty,
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
        'sew9am.qty',

        ])
        ->orderBy('wstudy_line_setups.id')
        ->get()*/
  }
}
