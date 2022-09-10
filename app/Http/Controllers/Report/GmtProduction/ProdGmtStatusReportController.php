<?php
namespace App\Http\Controllers\Report\GmtProduction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtCartonEntryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtIronRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPolyRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;

class ProdGmtStatusReportController extends Controller
{
    private $prodgmtcutting;
    private $prodgmtprintrcv;
    private $prodgmtembrcv;
    private $prodgmtcarton;
    private $prodgmtsewing;
    private $prodgmtpoly;
    private $prodgmtiron;
    private $salesorder;
    private $buyer;
	private $company;
    private $supplier;
    private $style;
    private $user;
    private $subsection;
    private $wstudylinesetup;

	public function __construct(
    SalesOrderRepository $salesorder,
    ProdGmtCuttingRepository $prodgmtcutting,
    ProdGmtPrintRcvRepository $prodgmtprintrcv,
    ProdGmtEmbRcvRepository $prodgmtembrcv,
    ProdGmtCartonEntryRepository $prodgmtcarton,
    ProdGmtSewingRepository $prodgmtsewing,
    ProdGmtIronRepository $prodgmtiron,
    ProdGmtPolyRepository $prodgmtpoly,
    CompanyRepository $company, 
    BuyerRepository $buyer,
    SupplierRepository $supplier,
    UserRepository $user,
    SubsectionRepository $subsection,
    WstudyLineSetupRepository $wstudylinesetup,
    StyleRepository $style
  )
    {

        $this->prodgmtcutting = $prodgmtcutting;
        $this->prodgmtprintrcv = $prodgmtprintrcv;
        $this->prodgmtembrcv = $prodgmtembrcv;
        $this->prodgmtcarton = $prodgmtcarton;
        $this->prodgmtsewing = $prodgmtsewing;
        $this->prodgmtiron = $prodgmtiron;
        $this->prodgmtpoly = $prodgmtpoly;
        $this->salesorder = $salesorder;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
        $this->user = $user;
        $this->subsection = $subsection;
      $this->wstudylinesetup = $wstudylinesetup;
        $this->style = $style;

      $this->middleware('auth');
		//$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-','');
      $productionarea=array_prepend(array_only(config('bprs.productionarea'),[40,45,50,55,65,67,70]),'-Select-','');
      return Template::loadView('Report.GmtProduction.ProdGmtStatusReport',['company'=>$company,'buyer'=>$buyer,'productionarea'=>$productionarea]);
    }

    public function reportData(){
        $style_id = request('style_id',0);

        $rows=collect(
            \DB::Select("
            select 
                styles.id as style_id,
                styles.style_ref,
                styles.factory_merchant_id,
                buyers.name as buyer_name,
                users.name as team_member_name,
                style_gmts.item_account_id,
                item_accounts.item_description,
                companies.name as company_name,
                sum(sales_order_gmt_color_sizes.qty) as qty,
                sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
                sum(sales_order_gmt_color_sizes.extra_qty) as extra_qty,
                avg(sales_order_gmt_color_sizes.extra_percent) as extra_percent
            from
            styles
            join buyers on buyers.id=styles.buyer_id
            left join teams on teams.id=styles.team_id
            left join teammembers on teammembers.id=styles.factory_merchant_id
            left join users on users.id=teammembers.user_id
            left join teammembers teamleaders on teamleaders.id=styles.teammember_id
            left join users teamleadernames on teamleadernames.id=teamleaders.user_id
            join jobs  on jobs.style_id=styles.id
            join companies on companies.id=jobs.company_id
            join sales_orders  on sales_orders.job_id=jobs.id
            join sales_order_gmt_color_sizes  on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
            join style_gmts on style_gmts.id=sales_order_gmt_color_sizes.style_gmt_id
            join item_accounts on item_accounts.id=style_gmts.item_account_id
            where styles.id='".$style_id."'
            group by
            styles.id,
            styles.style_ref,
            styles.factory_merchant_id,
            buyers.name,
            users.name,
            style_gmts.item_account_id,
            item_accounts.item_description,
            companies.name
        "));

        
        $stylecolorsize=collect(
            \DB::select("
            select 
                styles.id as style_id,
                style_gmts.item_account_id,
                item_accounts.item_description,
                sizes.name as size_name,
                colors.name as color_name,
                style_sizes.size_id,
                style_colors.color_id,
                style_colors.sort_id,
                style_sizes.sort_id,
                sum(sales_order_gmt_color_sizes.qty) as qty,
                sum(prod_cut.qty) as cut_qty,
                sum(prodscrreq.qty) as req_scr_qty,
                sum(prodscrrcv.qty) as rcv_scr_qty,
                sum(prodembreq.qty) as req_emb_qty,
                sum(prodembrcv.qty) as emb_rcv_qty,
                sum(prodsewline.qty) as sew_line_qty,
                sum(prodsewing.qty) as sew_qty,
                sum(prodiron.qty) as iron_qty,
                sum(prodpoly.qty) as poly_qty,
                sum(carton.carton_qty) as carton_qty,
                sum(exfactory.exf_qty) as ship_out_qty
            from
            styles
            join jobs  on jobs.style_id=styles.id
            join sales_orders  on sales_orders.job_id=jobs.id
            join sales_order_gmt_color_sizes  on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
            join style_gmt_color_sizes on style_gmt_color_sizes.id=sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id=style_gmt_color_sizes.style_gmt_id
            join item_accounts on item_accounts.id=style_gmts.item_account_id
            join style_colors on style_colors.id=style_gmt_color_sizes.style_color_id
            join colors on colors.id=style_colors.color_id
            join style_sizes on style_sizes.id=style_gmt_color_sizes.style_size_id
            join sizes on sizes.id=style_sizes.size_id
            left join(
                select 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_cutting_qties.qty) as qty 
                FROM prod_gmt_cutting_qties 
                join prod_gmt_cutting_orders on prod_gmt_cutting_orders.id =prod_gmt_cutting_qties.prod_gmt_cutting_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id 
                where prod_gmt_cutting_qties.deleted_at is null
                and sales_order_gmt_color_sizes.qty>0
                group by
                sales_order_gmt_color_sizes.id
            ) prod_cut on prod_cut.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(budget_emb_cons.req_cons) as qty
                from budget_emb_cons 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
                join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
                join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
                join embelishments on embelishments.id=style_embelishments.embelishment_id
                join production_processes on production_processes.id=embelishments.production_process_id
                where production_processes.production_area_id =45
                and sales_order_gmt_color_sizes.qty>0
                group by sales_order_gmt_color_sizes.id
            ) prodscrreq on prodscrreq.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_print_rcv_qties.qty) as qty 
                FROM prod_gmt_print_rcv_qties 
                join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.id =prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id 
                where prod_gmt_print_rcv_qties.deleted_at is null
                and sales_order_gmt_color_sizes.qty>0
                group by
                sales_order_gmt_color_sizes.id
            ) prodscrrcv on prodscrrcv.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(budget_emb_cons.req_cons) as qty
                from budget_emb_cons 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
                join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
                join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
                join embelishments on embelishments.id=style_embelishments.embelishment_id
                join production_processes on production_processes.id=embelishments.production_process_id
                where production_processes.production_area_id =50
                and sales_order_gmt_color_sizes.qty>0
                group by sales_order_gmt_color_sizes.id
            ) prodembreq on prodembreq.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
            SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(prod_gmt_emb_rcv_qties.qty) as qty 
                FROM prod_gmt_emb_rcv_qties 
                join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.id =prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id
                where prod_gmt_emb_rcv_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            ) prodembrcv on prodembrcv.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_sewing_line_qties.qty) as qty 
                FROM prod_gmt_sewing_line_qties 
                join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.id =prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id
                where prod_gmt_sewing_line_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodsewline on prodsewline.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_sewing_qties.qty) as qty 
                FROM prod_gmt_sewing_qties 
                join prod_gmt_sewing_orders on prod_gmt_sewing_orders.id =prod_gmt_sewing_qties.prod_gmt_sewing_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id
                where prod_gmt_sewing_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodsewing on prodsewing.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_iron_qties.qty) as qty 
                FROM prod_gmt_iron_qties 
                join prod_gmt_iron_orders on prod_gmt_iron_orders.id =prod_gmt_iron_qties.prod_gmt_iron_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_iron_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_iron_qties.sales_order_gmt_color_size_id
                where prod_gmt_iron_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodiron on prodiron.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_poly_qties.qty) as qty 
                FROM prod_gmt_poly_qties 
                join prod_gmt_poly_orders on prod_gmt_poly_orders.id =prod_gmt_poly_qties.prod_gmt_poly_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_poly_qties.sales_order_gmt_color_size_id
                where prod_gmt_poly_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodpoly on prodpoly.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(style_pkg_ratios.qty) as carton_qty 
                FROM prod_gmt_carton_entries
                join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
                join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
                --join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                and sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
                where style_pkgs.style_id='".$style_id."'
                group by sales_order_gmt_color_sizes.id
            )carton on carton.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(style_pkg_ratios.qty) as exf_qty 
                FROM prod_gmt_ex_factories
                join prod_gmt_ex_factory_qties on prod_gmt_ex_factories.id = prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id 
                join prod_gmt_carton_details on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
                join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                and  sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
                where prod_gmt_ex_factory_qties.deleted_at is null 
                and style_pkgs.style_id='".$style_id."'
                and prod_gmt_carton_details.deleted_at is null
                group by sales_order_gmt_color_sizes.id
            )exfactory on exfactory.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            where styles.id='".$style_id."'
            
            group by
            styles.id,
            style_gmts.item_account_id,
            item_accounts.item_description,
            sizes.name,
            colors.name,
            style_sizes.size_id,
            style_colors.color_id,
            style_colors.sort_id,
            style_sizes.sort_id
            order by style_colors.sort_id,
            style_sizes.sort_id
        "))
        ->map(function($stylecolorsize){
            if ($stylecolorsize->req_scr_qty==NULL) {
                $stylecolorsize->req_scr_qty='N/A';
                $stylecolorsize->rcv_scr_qty='N/A';
            }
            if ($stylecolorsize->req_emb_qty==NULL) {
                $stylecolorsize->req_emb_qty='N/A';
                $stylecolorsize->emb_rcv_qty='N/A';
            }
            
            return $stylecolorsize;
        });

        $itemArr=[];
        $colorArr=[];
       // $sizeArr=[];
        foreach ($stylecolorsize as $colorsize) {
            $itemArr[$colorsize->item_account_id]['item_description']=$colorsize->item_description;
            $colorArr[$colorsize->color_id]=$colorsize->color_name;
            //$colorArr[$colorsize->color_id]['item_description']=$colorsize->item_description;
        }

        $datas=$stylecolorsize->groupBy([
            'item_account_id',
            'color_id',
            //'size_id'
        ]);

        $salesorder=collect(
            \DB::select("
            select 
                sales_orders.id as sales_order_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                sales_orders.org_ship_date,
                styles.factory_merchant_id,
                buyers.code as buyer_code,
                users.name as team_member_name,
                style_gmts.item_account_id,
                item_accounts.item_description,
                sizes.name as size_name,
                colors.name as color_name,
                style_sizes.size_id,
                style_colors.color_id,
                style_colors.sort_id,
                style_sizes.sort_id,
                companies.name as produced_company_name,
                sum(sales_order_gmt_color_sizes.qty) as qty,
                sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
                sum(sales_order_gmt_color_sizes.extra_qty) as extra_qty,
                avg(sales_order_gmt_color_sizes.extra_percent) as extra_percent,
                sum(prod_cut.qty) as cut_qty,
                sum(prodscrreq.qty) as req_scr_qty,
                sum(prodscrrcv.qty) as rcv_scr_qty,
                sum(prodembreq.qty) as req_emb_qty,
                sum(prodembrcv.qty) as emb_rcv_qty,
                sum(prodsewline.qty) as sew_line_qty,
                sum(prodsewing.qty) as sew_qty,
                sum(prodiron.qty) as iron_qty,
                sum(prodpoly.qty) as poly_qty,
                sum(carton.carton_qty) as carton_qty,
                sum(exfactory.exf_qty) as ship_out_qty
            from
            styles
            join buyers on buyers.id=styles.buyer_id
            left join teams on teams.id=styles.team_id
            left join teammembers on teammembers.id=styles.factory_merchant_id
            left join users on users.id=teammembers.user_id
            left join teammembers teamleaders on teamleaders.id=styles.teammember_id
            left join users teamleadernames on teamleadernames.id=teamleaders.user_id
            join jobs  on jobs.style_id=styles.id
            join sales_orders  on sales_orders.job_id=jobs.id
            join companies on companies.id=sales_orders.produced_company_id
            join sales_order_gmt_color_sizes  on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
            join style_gmts on style_gmts.id=sales_order_gmt_color_sizes.style_gmt_id
            join item_accounts on item_accounts.id=style_gmts.item_account_id
            join style_gmt_color_sizes on style_gmt_color_sizes.id=sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_colors on style_colors.id=style_gmt_color_sizes.style_color_id
            join colors on colors.id=style_colors.color_id
            join style_sizes on style_sizes.id=style_gmt_color_sizes.style_size_id
            join sizes on sizes.id=style_sizes.size_id
            left join(
            select 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(prod_gmt_cutting_qties.qty) as qty 
                FROM prod_gmt_cutting_qties 
                join prod_gmt_cutting_orders on prod_gmt_cutting_orders.id =prod_gmt_cutting_qties.prod_gmt_cutting_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id 
                where prod_gmt_cutting_qties.deleted_at is null
                and sales_order_gmt_color_sizes.qty>0
                group by
                sales_order_gmt_color_sizes.id
            )prod_cut on prod_cut.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(budget_emb_cons.req_cons) as qty
                from budget_emb_cons 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
                join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
                join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
                join embelishments on embelishments.id=style_embelishments.embelishment_id
                join production_processes on production_processes.id=embelishments.production_process_id
                where production_processes.production_area_id =45
                and sales_order_gmt_color_sizes.qty>0
                group by sales_order_gmt_color_sizes.id
            ) prodscrreq on prodscrreq.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_print_rcv_qties.qty) as qty 
                FROM prod_gmt_print_rcv_qties 
                join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.id =prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id 
                where prod_gmt_print_rcv_qties.deleted_at is null
                and sales_order_gmt_color_sizes.qty>0
                group by
                sales_order_gmt_color_sizes.id
            )prodscrrcv on prodscrrcv.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(budget_emb_cons.req_cons) as qty
                from budget_emb_cons 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
                join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
                join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
                join embelishments on embelishments.id=style_embelishments.embelishment_id
                join production_processes on production_processes.id=embelishments.production_process_id
                where production_processes.production_area_id =50
                and sales_order_gmt_color_sizes.qty>0
                group by sales_order_gmt_color_sizes.id
            ) prodembreq on prodembreq.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
            SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(prod_gmt_emb_rcv_qties.qty) as qty 
                FROM prod_gmt_emb_rcv_qties 
                join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.id =prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id
                where prod_gmt_emb_rcv_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodembrcv on prodembrcv.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_sewing_line_qties.qty) as qty 
                FROM prod_gmt_sewing_line_qties 
                join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.id =prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id
                where prod_gmt_sewing_line_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodsewline on prodsewline.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_sewing_qties.qty) as qty 
                FROM prod_gmt_sewing_qties 
                join prod_gmt_sewing_orders on prod_gmt_sewing_orders.id =prod_gmt_sewing_qties.prod_gmt_sewing_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id
                where prod_gmt_sewing_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodsewing on prodsewing.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_iron_qties.qty) as qty 
                FROM prod_gmt_iron_qties 
                join prod_gmt_iron_orders on prod_gmt_iron_orders.id =prod_gmt_iron_qties.prod_gmt_iron_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_iron_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_iron_qties.sales_order_gmt_color_size_id
                where prod_gmt_iron_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodiron on prodiron.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_poly_qties.qty) as qty 
                FROM prod_gmt_poly_qties 
                join prod_gmt_poly_orders on prod_gmt_poly_orders.id =prod_gmt_poly_qties.prod_gmt_poly_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_poly_qties.sales_order_gmt_color_size_id
                where prod_gmt_poly_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodpoly on prodpoly.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(style_pkg_ratios.qty) as carton_qty 
                FROM prod_gmt_carton_entries
                join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
                join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
                --join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                and sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
                where style_pkgs.style_id='".$style_id."'
                and prod_gmt_carton_details.deleted_at is null
                group by sales_order_gmt_color_sizes.id
            )carton on carton.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(style_pkg_ratios.qty) as exf_qty 
                FROM prod_gmt_ex_factories
                join prod_gmt_ex_factory_qties on prod_gmt_ex_factories.id = prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id 
                join prod_gmt_carton_details on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
                join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                and  sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
                where prod_gmt_ex_factory_qties.deleted_at is null 
                and style_pkgs.style_id='".$style_id."'
                and prod_gmt_carton_details.deleted_at is null
                group by sales_order_gmt_color_sizes.id
            )exfactory on exfactory.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            where styles.id='".$style_id."'
           -- and sales_order_gmt_color_sizes.qty>0
            group by
            sales_orders.id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.org_ship_date,
            companies.name,
            styles.factory_merchant_id,
            buyers.code,
            users.name,
            style_gmts.item_account_id,
            item_accounts.item_description,
            sizes.name,
            colors.name,
            style_sizes.size_id,
            style_colors.color_id,
            style_colors.sort_id,
            style_sizes.sort_id
            order by style_colors.sort_id,
            style_sizes.sort_id
        "))
        ->map(function($salesorder){
            $salesorder->ship_date=date('d-M-Y',strtotime($salesorder->ship_date));
            $salesorder->org_ship_date=date('d-M-Y',strtotime($salesorder->org_ship_date));
            if ($salesorder->req_scr_qty==NULL) {
                $salesorder->req_scr_qty='N/A';
                $salesorder->rcv_scr_qty='N/A';
            }
            if ($salesorder->req_emb_qty==NULL) {
                $salesorder->req_emb_qty='N/A';
                $salesorder->emb_rcv_qty='N/A';
            }
            return $salesorder;
        });

        $salesorderArr=[];
        $salesordergmtItemArr=[];
        $salesordercolorArr=[];
        foreach ($salesorder as $order) {
            $salesorderArr[$order->sales_order_id]['buyer_code']=$order->buyer_code;
            $salesorderArr[$order->sales_order_id]['produced_company_name']=$order->produced_company_name;
            $salesorderArr[$order->sales_order_id]['sale_order_no']=$order->sale_order_no;
            $salesorderArr[$order->sales_order_id]['team_member_name']=$order->team_member_name;
            $salesorderArr[$order->sales_order_id]['item_description']=$order->item_description;
            $salesorderArr[$order->sales_order_id]['qty']=isset($salesorderArr[$order->sales_order_id]['qty'])?$salesorderArr[$order->sales_order_id]['qty']+=$order->qty:$order->qty;
            $salesorderArr[$order->sales_order_id]['plan_cut_qty']=isset($salesorderArr[$order->sales_order_id]['plan_cut_qty'])?$salesorderArr[$order->sales_order_id]['plan_cut_qty']+=$order->plan_cut_qty:$order->plan_cut_qty;
            $salesorderArr[$order->sales_order_id]['extra_qty']=isset($salesorderArr[$order->sales_order_id]['extra_qty'])?$salesorderArr[$order->sales_order_id]['extra_qty']+=$order->extra_qty:$order->extra_qty;
            $salesorderArr[$order->sales_order_id]['extra_percent']=$order->extra_percent;
            $salesorderArr[$order->sales_order_id]['ship_date']=$order->ship_date;
            $salesorderArr[$order->sales_order_id]['org_ship_date']=$order->org_ship_date;
            $salesordergmtItemArr[$order->item_account_id]['item_description']=$order->item_description;
            $salesordercolorArr[$order->color_id]=$order->color_name;
        }

        $orderdtl=$salesorder->groupBy([
            'sales_order_id','item_account_id','color_id'
        ]);

        $packingratio=collect(
            \DB::select("
                select
                style_pkgs.id as style_pkg_id,
                style_pkgs.assortment_name,
                style_gmts.item_account_id,
                item_accounts.item_description,
                style_pkg_ratios.style_color_id,
                style_pkg_ratios.style_size_id,
                style_pkg_ratios.style_gmt_id,
                style_sizes.size_id,
                style_colors.color_id,
                style_sizes.sort_id,
                style_colors.sort_id,
                sizes.name as size_name,
                colors.name as color_name,
                sum(style_pkg_ratios.qty) as pkg_qty
                from
                styles
                join style_pkgs on style_pkgs.style_id=styles.id
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id=style_pkgs.id
                join style_gmts on style_gmts.id=style_pkg_ratios.style_gmt_id
                join item_accounts on item_accounts.id =style_gmts.item_account_id
                join style_sizes on style_sizes.id=style_pkg_ratios.style_size_id
                join  sizes on sizes.id=style_sizes.size_id
                join style_colors on style_colors.id=style_pkg_ratios.style_color_id
                join colors on colors.id=style_colors.color_id
                where styles.id='".$style_id."'
                group by
                style_pkgs.id,
                style_pkgs.assortment_name,
                item_accounts.item_description,
                style_gmts.item_account_id,
                style_pkg_ratios.style_color_id,
                style_pkg_ratios.style_size_id,
                style_pkg_ratios.style_gmt_id,
                style_sizes.sort_id,
                style_colors.sort_id,
                style_sizes.size_id,
                style_colors.color_id,
                sizes.name,
                colors.name
                order by
                style_colors.sort_id,
                style_sizes.sort_id
            ")
        );

        $pkgratioAssortnameArr=[];
        $pkgratioGmtItemArr=[];
        $pkgratiocolorArr=[];
        $pkgratiosizeArr=[];
        $pkgcolorsizeArr=[];
        foreach ($packingratio as $pkgratio) {
            $pkgratioAssortnameArr[$pkgratio->style_pkg_id]['assortment_name']=$pkgratio->assortment_name;
            $pkgratioGmtItemArr[$pkgratio->item_account_id]['item_description']=$pkgratio->item_description;
            $pkgratiocolorArr[$pkgratio->color_id]=$pkgratio->color_name;
            $pkgratiosizeArr[$pkgratio->style_pkg_id][$pkgratio->size_id]=$pkgratio->size_name;
            $pkgcolorsizeArr[$pkgratio->style_pkg_id][$pkgratio->item_account_id][$pkgratio->color_id][$pkgratio->size_id]=$pkgratio->pkg_qty;
        }

       //  dd($pkgcolorsizeArr);die;

        $pkgratiodtl=$packingratio->groupBy(['style_pkg_id',/* 'item_account_id','color_id', */'size_id']);
      //  dd($pkgratioGmtItemArr);die;
       $bnfcompany=$rows->max('company_name');
      
       return Template::loadView('Report.GmtProduction.ProdGmtStatusReportMatrix',[
            'rows'=>$rows,
            'itemArr'=>$itemArr,
            'colorArr'=>$colorArr,
            'datas'=>$datas,
            'orderdtl'=>$orderdtl, 
            'salesorderArr'=>$salesorderArr,
            'salesordergmtItemArr'=>$salesordergmtItemArr,
            'salesordercolorArr'=>$salesordercolorArr,
            'pkgratiodtl'=>$pkgratiodtl,
            'pkgratioAssortnameArr'=>$pkgratioAssortnameArr,
            'pkgratioGmtItemArr'=>$pkgratioGmtItemArr,
            'pkgratiocolorArr'=>$pkgratiocolorArr,
            'pkgratiosizeArr'=>$pkgratiosizeArr,
            'pkgcolorsizeArr'=>$pkgcolorsizeArr,
            'bnfcompany'=>$bnfcompany,
        ]);
    }

    public function reportPdf(){
        $style_id = request('style_id',0);

        $rows=collect(
            \DB::Select("
            select 
                styles.id as style_id,
                styles.style_ref,
                styles.factory_merchant_id,
                buyers.name as buyer_name,
                users.name as team_member_name,
                style_gmts.item_account_id,
                item_accounts.item_description,
                companies.name as company_name,
                sum(sales_order_gmt_color_sizes.qty) as qty,
                sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
                sum(sales_order_gmt_color_sizes.extra_qty) as extra_qty,
                avg(sales_order_gmt_color_sizes.extra_percent) as extra_percent
            from
            styles
            join buyers on buyers.id=styles.buyer_id
            left join teams on teams.id=styles.team_id
            left join teammembers on teammembers.id=styles.factory_merchant_id
            left join users on users.id=teammembers.user_id
            left join teammembers teamleaders on teamleaders.id=styles.teammember_id
            left join users teamleadernames on teamleadernames.id=teamleaders.user_id
            join jobs  on jobs.style_id=styles.id
            join companies on companies.id=jobs.company_id
            join sales_orders  on sales_orders.job_id=jobs.id
            join sales_order_gmt_color_sizes  on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
            join style_gmts on style_gmts.id=sales_order_gmt_color_sizes.style_gmt_id
            join item_accounts on item_accounts.id=style_gmts.item_account_id
            where styles.id='".$style_id."'
            group by
            styles.id,
            styles.style_ref,
            styles.factory_merchant_id,
            buyers.name,
            users.name,
            style_gmts.item_account_id,
            item_accounts.item_description,
            companies.name
        "));

        
        $stylecolorsize=collect(
            \DB::select("
            select 
                style_gmts.item_account_id,
                item_accounts.item_description,
                sizes.name as size_name,
                colors.name as color_name,
                style_sizes.size_id,
                style_colors.color_id,
                style_colors.sort_id,
                style_sizes.sort_id,
                sum(sales_order_gmt_color_sizes.qty) as qty,
                sum(prod_cut.qty) as cut_qty,
                sum(prodscrreq.qty) as req_scr_qty,
                sum(prodscrrcv.qty) as rcv_scr_qty,
                sum(prodembreq.qty) as req_emb_qty,
                sum(prodembrcv.qty) as emb_rcv_qty,
                sum(prodsewline.qty) as sew_line_qty,
                sum(prodsewing.qty) as sew_qty,
                sum(prodiron.qty) as iron_qty,
                sum(prodpoly.qty) as poly_qty,
                sum(carton.carton_qty) as carton_qty,
                sum(exfactory.exf_qty) as ship_out_qty
            from
            styles
            join jobs  on jobs.style_id=styles.id
            join sales_orders  on sales_orders.job_id=jobs.id
            join sales_order_gmt_color_sizes  on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
            join style_gmt_color_sizes on style_gmt_color_sizes.id=sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id=style_gmt_color_sizes.style_gmt_id
            join item_accounts on item_accounts.id=style_gmts.item_account_id
            join style_colors on style_colors.id=style_gmt_color_sizes.style_color_id
            join colors on colors.id=style_colors.color_id
            join style_sizes on style_sizes.id=style_gmt_color_sizes.style_size_id
            join sizes on sizes.id=style_sizes.size_id
            left join(
                select 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_cutting_qties.qty) as qty 
                FROM prod_gmt_cutting_qties 
                join prod_gmt_cutting_orders on prod_gmt_cutting_orders.id =prod_gmt_cutting_qties.prod_gmt_cutting_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id 
                where prod_gmt_cutting_qties.deleted_at is null
                and sales_order_gmt_color_sizes.qty>0
                group by
                sales_order_gmt_color_sizes.id
            ) prod_cut on prod_cut.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(budget_emb_cons.req_cons) as qty
                from budget_emb_cons 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
                join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
                join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
                join embelishments on embelishments.id=style_embelishments.embelishment_id
                join production_processes on production_processes.id=embelishments.production_process_id
                where production_processes.production_area_id =45
                and sales_order_gmt_color_sizes.qty>0
                group by sales_order_gmt_color_sizes.id
            ) prodscrreq on prodscrreq.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_print_rcv_qties.qty) as qty 
                FROM prod_gmt_print_rcv_qties 
                join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.id =prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id 
                where prod_gmt_print_rcv_qties.deleted_at is null
                and sales_order_gmt_color_sizes.qty>0
                group by
                sales_order_gmt_color_sizes.id
            ) prodscrrcv on prodscrrcv.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(budget_emb_cons.req_cons) as qty
                from budget_emb_cons 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
                join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
                join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
                join embelishments on embelishments.id=style_embelishments.embelishment_id
                join production_processes on production_processes.id=embelishments.production_process_id
                where production_processes.production_area_id =50
                and sales_order_gmt_color_sizes.qty>0
                group by sales_order_gmt_color_sizes.id
            ) prodembreq on prodembreq.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
            SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(prod_gmt_emb_rcv_qties.qty) as qty 
                FROM prod_gmt_emb_rcv_qties 
                join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.id =prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id
                where prod_gmt_emb_rcv_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            ) prodembrcv on prodembrcv.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_sewing_line_qties.qty) as qty 
                FROM prod_gmt_sewing_line_qties 
                join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.id =prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id
                where prod_gmt_sewing_line_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodsewline on prodsewline.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_sewing_qties.qty) as qty 
                FROM prod_gmt_sewing_qties 
                join prod_gmt_sewing_orders on prod_gmt_sewing_orders.id =prod_gmt_sewing_qties.prod_gmt_sewing_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id
                where prod_gmt_sewing_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodsewing on prodsewing.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_iron_qties.qty) as qty 
                FROM prod_gmt_iron_qties 
                join prod_gmt_iron_orders on prod_gmt_iron_orders.id =prod_gmt_iron_qties.prod_gmt_iron_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_iron_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_iron_qties.sales_order_gmt_color_size_id
                where prod_gmt_iron_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodiron on prodiron.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_poly_qties.qty) as qty 
                FROM prod_gmt_poly_qties 
                join prod_gmt_poly_orders on prod_gmt_poly_orders.id =prod_gmt_poly_qties.prod_gmt_poly_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_poly_qties.sales_order_gmt_color_size_id
                where prod_gmt_poly_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodpoly on prodpoly.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(style_pkg_ratios.qty) as carton_qty 
                FROM prod_gmt_carton_entries
                join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
                join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
                --join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                and sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
                where style_pkgs.style_id='".$style_id."'
                group by sales_order_gmt_color_sizes.id
            )carton on carton.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(style_pkg_ratios.qty) as exf_qty 
                FROM prod_gmt_ex_factories
                join prod_gmt_ex_factory_qties on prod_gmt_ex_factories.id = prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id 
                join prod_gmt_carton_details on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
                join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                and  sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
                where prod_gmt_ex_factory_qties.deleted_at is null 
                and style_pkgs.style_id='".$style_id."'
                and prod_gmt_carton_details.deleted_at is null
                group by sales_order_gmt_color_sizes.id
            )exfactory on exfactory.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            where styles.id='".$style_id."'
            
            group by
            style_gmts.item_account_id,
            item_accounts.item_description,
            sizes.name,
            colors.name,
            style_sizes.size_id,
            style_colors.color_id,
            style_colors.sort_id,
            style_sizes.sort_id
            order by style_colors.sort_id,
            style_sizes.sort_id
        "))
        ->map(function($stylecolorsize){
            $stylecolorsize->req_scr_qty=$stylecolorsize->req_scr_qty?$stylecolorsize->req_scr_qty:'N/A';
            $stylecolorsize->rcv_scr_qty=$stylecolorsize->rcv_scr_qty?$stylecolorsize->rcv_scr_qty:'N/A';
            $stylecolorsize->req_emb_qty=$stylecolorsize->req_emb_qty?$stylecolorsize->req_emb_qty:'N/A';
            $stylecolorsize->emb_rcv_qty=$stylecolorsize->emb_rcv_qty?$stylecolorsize->emb_rcv_qty:'N/A';
            return $stylecolorsize;
        });

        $itemArr=[];
        $colorArr=[];
       // $sizeArr=[];
        foreach ($stylecolorsize as $colorsize) {
            $itemArr[$colorsize->item_account_id]['item_description']=$colorsize->item_description;
            $colorArr[$colorsize->color_id]=$colorsize->color_name;
            //$colorArr[$colorsize->color_id]['item_description']=$colorsize->item_description;
        }

        $datas=$stylecolorsize->groupBy([
            'item_account_id',
            'color_id',
            //'size_id'
        ]);

        $salesorder=collect(
            \DB::select("
            select 
                sales_orders.id as sales_order_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                sales_orders.org_ship_date,
                styles.factory_merchant_id,
                buyers.code as buyer_code,
                users.name as team_member_name,
                style_gmts.item_account_id,
                item_accounts.item_description,
                sizes.name as size_name,
                colors.name as color_name,
                style_sizes.size_id,
                style_colors.color_id,
                style_colors.sort_id,
                style_sizes.sort_id,
                companies.name as produced_company_name,
                sum(sales_order_gmt_color_sizes.qty) as qty,
                sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
                sum(sales_order_gmt_color_sizes.extra_qty) as extra_qty,
                avg(sales_order_gmt_color_sizes.extra_percent) as extra_percent,
                sum(prod_cut.qty) as cut_qty,
                sum(prodscrreq.qty) as req_scr_qty,
                sum(prodscrrcv.qty) as rcv_scr_qty,
                sum(prodembreq.qty) as req_emb_qty,
                sum(prodembrcv.qty) as emb_rcv_qty,
                sum(prodsewline.qty) as sew_line_qty,
                sum(prodsewing.qty) as sew_qty,
                sum(prodiron.qty) as iron_qty,
                sum(prodpoly.qty) as poly_qty,
                sum(carton.carton_qty) as carton_qty,
                sum(exfactory.exf_qty) as ship_out_qty
            from
            styles
            join buyers on buyers.id=styles.buyer_id
            left join teams on teams.id=styles.team_id
            left join teammembers on teammembers.id=styles.factory_merchant_id
            left join users on users.id=teammembers.user_id
            left join teammembers teamleaders on teamleaders.id=styles.teammember_id
            left join users teamleadernames on teamleadernames.id=teamleaders.user_id
            join jobs  on jobs.style_id=styles.id
            join sales_orders  on sales_orders.job_id=jobs.id
            join companies on companies.id=sales_orders.produced_company_id
            join sales_order_gmt_color_sizes  on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
            join style_gmts on style_gmts.id=sales_order_gmt_color_sizes.style_gmt_id
            join item_accounts on item_accounts.id=style_gmts.item_account_id
            join style_gmt_color_sizes on style_gmt_color_sizes.id=sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_colors on style_colors.id=style_gmt_color_sizes.style_color_id
            join colors on colors.id=style_colors.color_id
            join style_sizes on style_sizes.id=style_gmt_color_sizes.style_size_id
            join sizes on sizes.id=style_sizes.size_id
            left join(
            select 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(prod_gmt_cutting_qties.qty) as qty 
                FROM prod_gmt_cutting_qties 
                join prod_gmt_cutting_orders on prod_gmt_cutting_orders.id =prod_gmt_cutting_qties.prod_gmt_cutting_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id 
                where prod_gmt_cutting_qties.deleted_at is null
                and sales_order_gmt_color_sizes.qty>0
                group by
                sales_order_gmt_color_sizes.id
            )prod_cut on prod_cut.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(budget_emb_cons.req_cons) as qty
                from budget_emb_cons 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
                join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
                join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
                join embelishments on embelishments.id=style_embelishments.embelishment_id
                join production_processes on production_processes.id=embelishments.production_process_id
                where production_processes.production_area_id =45
                and sales_order_gmt_color_sizes.qty>0
                group by sales_order_gmt_color_sizes.id
            ) prodscrreq on prodscrreq.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_print_rcv_qties.qty) as qty 
                FROM prod_gmt_print_rcv_qties 
                join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.id =prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id 
                where prod_gmt_print_rcv_qties.deleted_at is null
                and sales_order_gmt_color_sizes.qty>0
                group by
                sales_order_gmt_color_sizes.id
            )prodscrrcv on prodscrrcv.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(budget_emb_cons.req_cons) as qty
                from budget_emb_cons 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
                join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
                join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
                join embelishments on embelishments.id=style_embelishments.embelishment_id
                join production_processes on production_processes.id=embelishments.production_process_id
                where production_processes.production_area_id =50
                and sales_order_gmt_color_sizes.qty>0
                group by sales_order_gmt_color_sizes.id
            ) prodembreq on prodembreq.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
            SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(prod_gmt_emb_rcv_qties.qty) as qty 
                FROM prod_gmt_emb_rcv_qties 
                join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.id =prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id
                where prod_gmt_emb_rcv_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodembrcv on prodembrcv.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_sewing_line_qties.qty) as qty 
                FROM prod_gmt_sewing_line_qties 
                join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.id =prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id 
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id
                where prod_gmt_sewing_line_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodsewline on prodsewline.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_sewing_qties.qty) as qty 
                FROM prod_gmt_sewing_qties 
                join prod_gmt_sewing_orders on prod_gmt_sewing_orders.id =prod_gmt_sewing_qties.prod_gmt_sewing_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id
                where prod_gmt_sewing_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodsewing on prodsewing.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_iron_qties.qty) as qty 
                FROM prod_gmt_iron_qties 
                join prod_gmt_iron_orders on prod_gmt_iron_orders.id =prod_gmt_iron_qties.prod_gmt_iron_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_iron_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_iron_qties.sales_order_gmt_color_size_id
                where prod_gmt_iron_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodiron on prodiron.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                    sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                    sum(prod_gmt_poly_qties.qty) as qty 
                FROM prod_gmt_poly_qties 
                join prod_gmt_poly_orders on prod_gmt_poly_orders.id =prod_gmt_poly_qties.prod_gmt_poly_order_id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
                join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_poly_qties.sales_order_gmt_color_size_id
                where prod_gmt_poly_qties.deleted_at is null 
                and sales_order_gmt_color_sizes.qty>0 
                group by 
                sales_order_gmt_color_sizes.id
            )prodpoly on prodpoly.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(style_pkg_ratios.qty) as carton_qty 
                FROM prod_gmt_carton_entries
                join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
                join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
                --join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                and sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
                where style_pkgs.style_id='".$style_id."'
                and prod_gmt_carton_details.deleted_at is null
                group by sales_order_gmt_color_sizes.id
            )carton on carton.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                SELECT 
                sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
                sum(style_pkg_ratios.qty) as exf_qty 
                FROM prod_gmt_ex_factories
                join prod_gmt_ex_factory_qties on prod_gmt_ex_factories.id = prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id 
                join prod_gmt_carton_details on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
                join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = style_pkg_ratios.style_gmt_color_size_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                and  sales_order_gmt_color_sizes.style_gmt_color_size_id=style_gmt_color_sizes.id
                where prod_gmt_ex_factory_qties.deleted_at is null 
                and style_pkgs.style_id='".$style_id."'
                and prod_gmt_carton_details.deleted_at is null
                group by sales_order_gmt_color_sizes.id
            )exfactory on exfactory.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            where styles.id='".$style_id."'
           -- and sales_order_gmt_color_sizes.qty>0
            group by
            sales_orders.id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.org_ship_date,
            companies.name,
            styles.factory_merchant_id,
            buyers.code,
            users.name,
            style_gmts.item_account_id,
            item_accounts.item_description,
            sizes.name,
            colors.name,
            style_sizes.size_id,
            style_colors.color_id,
            style_colors.sort_id,
            style_sizes.sort_id
            order by style_colors.sort_id,
            style_sizes.sort_id
        "))
        ->map(function($salesorder){
            $salesorder->ship_date=date('d-M-Y',strtotime($salesorder->ship_date));
            $salesorder->org_ship_date=date('d-M-Y',strtotime($salesorder->org_ship_date));
            $salesorder->req_scr_qty=$salesorder->req_scr_qty?$salesorder->req_scr_qty:'N/A';
            $salesorder->rcv_scr_qty=$salesorder->rcv_scr_qty?$salesorder->rcv_scr_qty:'N/A';
            $salesorder->req_emb_qty=$salesorder->req_emb_qty?$salesorder->req_emb_qty:'N/A';
            $salesorder->emb_rcv_qty=$salesorder->emb_rcv_qty?$salesorder->emb_rcv_qty:'N/A';
            return $salesorder;
        });

        $salesorderArr=[];
        $salesordergmtItemArr=[];
        $salesordercolorArr=[];
        foreach ($salesorder as $order) {
            $salesorderArr[$order->sales_order_id]['buyer_code']=$order->buyer_code;
            $salesorderArr[$order->sales_order_id]['produced_company_name']=$order->produced_company_name;
            $salesorderArr[$order->sales_order_id]['sale_order_no']=$order->sale_order_no;
            $salesorderArr[$order->sales_order_id]['team_member_name']=$order->team_member_name;
            $salesorderArr[$order->sales_order_id]['item_description']=$order->item_description;
            $salesorderArr[$order->sales_order_id]['qty']=isset($salesorderArr[$order->sales_order_id]['qty'])?$salesorderArr[$order->sales_order_id]['qty']+=$order->qty:$order->qty;
            $salesorderArr[$order->sales_order_id]['plan_cut_qty']=isset($salesorderArr[$order->sales_order_id]['plan_cut_qty'])?$salesorderArr[$order->sales_order_id]['plan_cut_qty']+=$order->plan_cut_qty:$order->plan_cut_qty;
            $salesorderArr[$order->sales_order_id]['extra_qty']=isset($salesorderArr[$order->sales_order_id]['extra_qty'])?$salesorderArr[$order->sales_order_id]['extra_qty']+=$order->extra_qty:$order->extra_qty;
            $salesorderArr[$order->sales_order_id]['extra_percent']=$order->extra_percent;
            $salesorderArr[$order->sales_order_id]['ship_date']=$order->ship_date;
            $salesorderArr[$order->sales_order_id]['org_ship_date']=$order->org_ship_date;
            $salesordergmtItemArr[$order->item_account_id]['item_description']=$order->item_description;
            $salesordercolorArr[$order->color_id]=$order->color_name;
        }

        $orderdtl=$salesorder->groupBy([
            'sales_order_id','item_account_id','color_id'
        ]);

        $packingratio=collect(
            \DB::select("
                select
                style_pkgs.id as style_pkg_id,
                style_pkgs.assortment_name,
                style_gmts.item_account_id,
                item_accounts.item_description,
                style_pkg_ratios.style_color_id,
                style_pkg_ratios.style_size_id,
                style_pkg_ratios.style_gmt_id,
                style_sizes.size_id,
                style_colors.color_id,
                style_sizes.sort_id,
                style_colors.sort_id,
                sizes.name as size_name,
                colors.name as color_name,
                sum(style_pkg_ratios.qty) as pkg_qty
                from
                styles
                join style_pkgs on style_pkgs.style_id=styles.id
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id=style_pkgs.id
                join style_gmts on style_gmts.id=style_pkg_ratios.style_gmt_id
                join item_accounts on item_accounts.id =style_gmts.item_account_id
                join style_sizes on style_sizes.id=style_pkg_ratios.style_size_id
                join  sizes on sizes.id=style_sizes.size_id
                join style_colors on style_colors.id=style_pkg_ratios.style_color_id
                join colors on colors.id=style_colors.color_id
                where styles.id='".$style_id."'
                group by
                style_pkgs.id,
                style_pkgs.assortment_name,
                item_accounts.item_description,
                style_gmts.item_account_id,
                style_pkg_ratios.style_color_id,
                style_pkg_ratios.style_size_id,
                style_pkg_ratios.style_gmt_id,
                style_sizes.sort_id,
                style_colors.sort_id,
                style_sizes.size_id,
                style_colors.color_id,
                sizes.name,
                colors.name
                order by
                style_colors.sort_id,
                style_sizes.sort_id
            ")
        );

        $pkgratioAssortnameArr=[];
        $pkgratioGmtItemArr=[];
        $pkgratiocolorArr=[];
        $pkgratiosizeArr=[];
        $pkgcolorsizeArr=[];
        foreach ($packingratio as $pkgratio) {
            $pkgratioAssortnameArr[$pkgratio->style_pkg_id]['assortment_name']=$pkgratio->assortment_name;
            $pkgratioGmtItemArr[$pkgratio->item_account_id]['item_description']=$pkgratio->item_description;
            $pkgratiocolorArr[$pkgratio->color_id]=$pkgratio->color_name;
            $pkgratiosizeArr[$pkgratio->style_pkg_id][$pkgratio->size_id]=$pkgratio->size_name;
            $pkgcolorsizeArr[$pkgratio->style_pkg_id][$pkgratio->item_account_id][$pkgratio->color_id][$pkgratio->size_id]=$pkgratio->pkg_qty;
        }

       //  dd($pkgcolorsizeArr);die;

        $pkgratiodtl=$packingratio->groupBy(['style_pkg_id',/* 'item_account_id','color_id', */'size_id']);
      //  dd($pkgratioGmtItemArr);die;
       $bnfcompany=$rows->max('company_name');

       
        
        $pdf = new \TCPDF('PDF_PAGE_ORIENTATION', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(10, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        $pdf->SetY(10);

        //$data=$this->getData();
        

        $pdf->SetFont('helvetica', 'N', 8);
        $view= \View::make('Defult.Report.GmtProduction.ProdGmtStatusReportPdf',[
            'rows'=>$rows,
            'itemArr'=>$itemArr,
            'colorArr'=>$colorArr,
            'datas'=>$datas,
            'orderdtl'=>$orderdtl, 
            'salesorderArr'=>$salesorderArr,
            'salesordergmtItemArr'=>$salesordergmtItemArr,
            'salesordercolorArr'=>$salesordercolorArr,
            'pkgratiodtl'=>$pkgratiodtl,
            'pkgratioAssortnameArr'=>$pkgratioAssortnameArr,
            'pkgratioGmtItemArr'=>$pkgratioGmtItemArr,
            'pkgratiocolorArr'=>$pkgratiocolorArr,
            'pkgratiosizeArr'=>$pkgratiosizeArr,
            'pkgcolorsizeArr'=>$pkgcolorsizeArr,
            'bnfcompany'=>$bnfcompany,
        ]);
        $html_content=$view->render();
        $pdf->SetY(18);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/ProdGmtStatusReportPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function getGmtStyle(){
        $rows=$this->style
		->leftJoin('buyers', function($join)  {
		    $join->on('styles.buyer_id', '=', 'buyers.id');
		})
		->leftJoin('buyers as buyingagents', function($join)  {
		    $join->on('styles.buying_agent_id', '=', 'buyingagents.id');
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
		->when(request('buyer_id'), function ($q) {
		    return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		    return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('style_description'), function ($q) {
		    return $q->where('styles.style_description', 'like', '%'.request('style_description', 0).'%');
		})
		->orderBy('styles.id','desc')
		->get([
            'styles.*',
            'buyers.code as buyer_name',
            'uoms.name as uom_name',
            'seasons.name as season_name',
            'teams.name as team_name',
            'users.name as team_member_name',
            'productdepartments.department_name',
            'buyingagents.name as buying_agent_id'
		])->map(function($rows){
			$rows->receivedate=date("d-M-Y",strtotime($rows->receive_date));
			$rows->buyer=$rows->buyer_name;
			$rows->deptcategory=$rows->dept_category_name;
			$rows->season=$rows->season_name;
			$rows->uom=$rows->uom_name;
			$rows->team=$rows->team_name;
			$rows->teammember=$rows->team_member_name;
			$rows->productdepartment=$rows->department_name;
			return $rows;
		});
		echo json_encode($rows);
    }

    public function getSewingData(){
        $style_id=request('style_id', 0);
        $item_account_id=request('item_account_id', 0);
        $color_id=request('color_id', 0);

        $subsections=$this->wstudylinesetup
        ->join('companies', function($join)  {
        $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
        })
        ->join('wstudy_line_setup_lines', function($join)  {
        $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        // ->join('wstudy_line_setup_dtls', function($join) {
        // $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        // })
        ->join('subsections', function($join)  {
        $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
        })
        //->where([['wstudy_line_setups.company_id','=',$company_id]])
        //->where([['wstudy_line_setups.id','=',3]])
        ->get([
        'wstudy_line_setups.id',
        'subsections.name',
        'subsections.code',
        ]);

        $lineNames=Array();
        foreach($subsections as $subsection)
        {
            $lineNames[$subsection->id][]=$subsection->code;
        }

        $rows=collect(
            \DB::Select("
            select 
                styles.id as style_id,
                style_gmts.item_account_id,
                companies.id as produced_company_id,
                colors.id as color_id,
                style_sizes.size_id,
                sizes.name as size_name,
                sizes.sort_id,
                companies.name as company_name
            from
            styles
            join buyers on buyers.id=styles.buyer_id
            join jobs  on jobs.style_id=styles.id
            join sales_orders  on sales_orders.job_id=jobs.id
            join companies on companies.id=sales_orders.produced_company_id
            join sales_order_gmt_color_sizes  on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
            join style_gmt_color_sizes on style_gmt_color_sizes.id=sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id=sales_order_gmt_color_sizes.style_gmt_id
            join item_accounts on item_accounts.id=style_gmts.item_account_id
            join style_colors on style_colors.id=style_gmt_color_sizes.style_color_id
            join colors on colors.id=style_colors.color_id
            join style_sizes on style_sizes.id=style_gmt_color_sizes.style_size_id
            join sizes on sizes.id=style_sizes.size_id
            where styles.id='".$style_id."'
            and style_gmts.item_account_id='".$item_account_id."'
            and colors.id='".$color_id."'
            group by
            styles.id,
            style_gmts.item_account_id,
            companies.id,
            colors.id,
            style_sizes.size_id,
            sizes.name,
            sizes.sort_id,
            companies.name
        "));

        $sizeArr=[];
        foreach($rows as $row){
            $sizeArr[$row->size_id]=$row->size_name;
        }
        //$company_id=$rows->max('produced_company_id');

        

        

        $linewisesize=collect(\DB::select("
            select
                companies.name as produced_company_name,
                style_sizes.size_id,
                prodsewing.wstudy_line_setup_id,
                sum(prodsewline.qty) as sew_line_qty,
                sum(prodsewing.qty) as sew_qty
            from
            styles
            join jobs  on jobs.style_id=styles.id
            join sales_orders on jobs.id = sales_orders.job_id
            join companies on companies.id=sales_orders.produced_company_id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id 
            join style_gmts on style_gmts.id=sales_order_gmt_color_sizes.style_gmt_id
            join style_gmt_color_sizes on style_gmt_color_sizes.id=sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_colors on style_colors.id=style_gmt_color_sizes.style_color_id
            join style_sizes on style_sizes.id=style_gmt_color_sizes.style_size_id
            left join(
                select 
                prod_gmt_sewing_qties.sales_order_gmt_color_size_id,
                prod_gmt_sewing_orders.wstudy_line_setup_id,
                sum(prod_gmt_sewing_qties.qty) as qty 
                from prod_gmt_sewing_qties 
                join prod_gmt_sewing_orders on prod_gmt_sewing_orders.id =prod_gmt_sewing_qties.prod_gmt_sewing_order_id 
                join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
                where prod_gmt_sewing_qties.deleted_at is null 
                group by 
                prod_gmt_sewing_qties.sales_order_gmt_color_size_id,
                prod_gmt_sewing_orders.wstudy_line_setup_id
            )prodsewing on prodsewing.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            left join(
                select 
                prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id,
                prod_gmt_sewing_line_orders.wstudy_line_setup_id,
                sum(prod_gmt_sewing_line_qties.qty) as qty 
                from prod_gmt_sewing_line_qties 
                join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.id =prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id 
                join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_line_orders.wstudy_line_setup_id 
                where prod_gmt_sewing_line_qties.deleted_at is null 
                group by 
                prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id,
                prod_gmt_sewing_line_orders.wstudy_line_setup_id
            )prodsewline on prodsewline.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            and prodsewline.wstudy_line_setup_id=prodsewing.wstudy_line_setup_id
            where 
                styles.id='".$style_id."'
                and style_gmts.item_account_id='".$item_account_id."'
                and style_colors.color_id='".$color_id."' 
                and sales_order_gmt_color_sizes.qty>0
            group by
            style_sizes.size_id,
            companies.name,
            prodsewing.wstudy_line_setup_id
            order by 
            prodsewing.wstudy_line_setup_id
        "));
       // dd($sizeArr);die;
       $lineArr=[];
       $lineSizeArr=[];
       foreach($linewisesize as $data){
        $lineArr[$data->wstudy_line_setup_id]['company_name']=$data->produced_company_name;
        $lineArr[$data->wstudy_line_setup_id]['line_no']=isset($lineNames[$data->wstudy_line_setup_id])?implode(',',$lineNames[$data->wstudy_line_setup_id]):'';
        $lineSizeArr[$data->wstudy_line_setup_id][$data->size_id]['sew_line_qty']=$data->sew_line_qty;
        $lineSizeArr[$data->wstudy_line_setup_id][$data->size_id]['sew_qty']=$data->sew_qty;
       }


   // dd($lineSizeArr);die;

        return Template::loadView('Report.GmtProduction.ProdGmtStatusLineWiseSewingPdf',[
            'rows'=>$rows,
            'sizeArr'=>$sizeArr,
            'lineArr'=>$lineArr,
            'lineSizeArr'=>$lineSizeArr
        ]);
    }

}

