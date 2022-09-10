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
use App\Repositories\Contracts\System\Auth\UserRepository;

class ProdGmtSewingProductionController extends Controller
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
        $this->style = $style;

      $this->middleware('auth');
		//$this->middleware('permission:view.prodgmtsewingproductions',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'','');
      $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
      $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'','');
      $productionarea=array_prepend(array_only(config('bprs.productionarea'),[40,55,65,67]),'-Select-','');
      return Template::loadView('Report.GmtProduction.ProdGmtSewingProduction',['company'=>$company,'buyer'=>$buyer,'supplier'=>$supplier,'productionarea'=>$productionarea]);
    }
	public function reportData() {

        $date_from = request('date_from',0);
        $date_to=request('date_to',0);
        $production_area_id=request('production_area_id',0);
        $supplier_id=request(('supplier_id'), 0);
        $buyer_id=request(('buyer_id'), 0);
        $company_id=request('company_id',0);
        $produced_company_id=request(('produced_company_id'), 0);
        $style_ref=request(('style_ref'), 0);
        $sale_order_no=request(('sale_order_no'), 0);
		if($production_area_id == 40){
            $prodgmtcutting=$this->prodgmtcutting
            ->selectRaw('
                prod_gmt_cuttings.cut_qc_date,
                prod_gmt_cutting_orders.supplier_id,
                sales_orders.id as sales_order_id,
                sales_orders.produced_company_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                jobs.job_no,
                styles.id as style_id,
                styles.style_ref,
                styles.flie_src,
                styles.file_name,
                users.id as user_id,
                users.name as dl_marchent,
                buyers.id as buyer_id,
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                sizes.name as size_name,
                colors.name as color_name,
                receive_by.name as recv_by,
                suppliers.name as supplier_name,
                sum(prod_gmt_cutting_qties.qty) as cut_qty
            ')
            ->join('prod_gmt_cutting_orders', function($join) {
                $join->on('prod_gmt_cutting_orders.prod_gmt_cutting_id', '=', 'prod_gmt_cuttings.id');
            })
            ->join('prod_gmt_cutting_qties',function($join){
                $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id','=','prod_gmt_cutting_orders.id');
            })
            ->join('sales_order_countries',function($join){
                $join->on('sales_order_countries.id', '=' , 'prod_gmt_cutting_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
            ->join('sales_order_gmt_color_sizes',function($join){
                $join->on('prod_gmt_cutting_qties.sales_order_gmt_color_size_id', '=' , 'sales_order_gmt_color_sizes.id');
                $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=' , 'sales_order_countries.id');
            })
            ->join('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id', '=' , 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->join('style_gmts',function($join){
                $join->on('style_gmts.id', '=' , 'style_gmt_color_sizes.style_gmt_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('companies as bcompany', function($join)  {
                $join->on('bcompany.id', '=', 'jobs.company_id');
            })
            ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->leftJoin('teammembers', function($join)  {
                $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
            })           
            ->leftJoin('users', function($join)  {
                $join->on('users.id', '=', 'teammembers.user_id');
            })
            ->join('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })
            ->leftJoin('suppliers', function($join)  {
                $join->on('suppliers.id', '=', 'prod_gmt_cutting_orders.supplier_id');
            })
            ->join('users as receive_by', function($join)  {
                $join->on('receive_by.id', '=', 'prod_gmt_cuttings.created_by');
            })
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_cuttings.cut_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_cuttings.cut_qc_date', '<=', $date_to);
            })
            ->when(request('supplier_id',0), function ($q){
                return $q->where('prod_gmt_cutting_orders.supplier_id', '=', request('supplier_id',0));
            })
            ->when(request('produced_company_id',0), function ($q){
                return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id',0));
            })
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('jobs.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('style_ref'), function ($q) {
                return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
            })
            ->where([['prod_gmt_cutting_orders.prod_source_id','=',5]])
            ->orderBy('prod_gmt_cuttings.cut_qc_date')
            ->groupBy([
                'prod_gmt_cuttings.cut_qc_date',
                'prod_gmt_cutting_orders.supplier_id',
                'sales_orders.id',
                'sales_orders.produced_company_id',
                'sales_orders.sale_order_no',
                'sales_orders.ship_date',
                'jobs.job_no',
                'styles.id',
                'styles.style_ref',
                'styles.flie_src',
                'styles.file_name',
                'users.id',
                'users.name',
                'buyers.id',
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'sizes.name',
                'colors.name',
                'suppliers.name',
                'receive_by.name',
            ])
            ->get()
            ->map(function($prodgmtcutting) use($date_from,$date_to){
                $prodgmtcutting->ship_date=date('d-M-Y',strtotime($prodgmtcutting->ship_date));
                $prodgmtcutting->qc_date = date('d-M-Y',strtotime($prodgmtcutting->cut_qc_date));
                $prodgmtcutting->item_ratio=number_format(0,4);
                $prodgmtcutting->qty=number_format($prodgmtcutting->cut_qty,0);
                $prodgmtcutting->cm_amount=number_format(0,2);
                $prodgmtcutting->cm_rate=number_format(0,4);
                return $prodgmtcutting;
            });
            echo json_encode($prodgmtcutting);
        }
        if($production_area_id == 55){
            $sewingproduction=$this->prodgmtsewing
            ->selectRaw('
                prod_gmt_sewings.sew_qc_date,
                prod_gmt_sewing_orders.supplier_id,
                sales_orders.id as sales_order_id,
                sales_orders.produced_company_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                jobs.job_no,
                styles.id as style_id,
                styles.style_ref,
                styles.flie_src,
                styles.file_name,
                users.id as user_id,
                users.name as dl_marchent,
                buyers.id as buyer_id,
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                sizes.name as size_name,
                colors.name as color_name,
                receive_by.name as recv_by,
                suppliers.name as supplier_name,
                budgetCm.budget_cm_amount,
                gmt_item_ratio.item_ratio,
                sum(prod_gmt_sewing_qties.qty) as sew_qty
            ')
            ->join('prod_gmt_sewing_orders', function($join) {
                $join->on('prod_gmt_sewing_orders.prod_gmt_sewing_id', '=', 'prod_gmt_sewings.id');
            })
             ->join('prod_gmt_sewing_qties',function($join){
                $join->on('prod_gmt_sewing_qties.prod_gmt_sewing_order_id','=','prod_gmt_sewing_orders.id');
            })
             ->join('wstudy_line_setups', function($join) {
                $join->on('wstudy_line_setups.id', '=', 'prod_gmt_sewing_orders.wstudy_line_setup_id');
            })

            ->join('sales_order_countries',function($join){
            $join->on('sales_order_countries.id', '=' , 'prod_gmt_sewing_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
           
            ->join('sales_order_gmt_color_sizes',function($join){
                $join->on('prod_gmt_sewing_qties.sales_order_gmt_color_size_id', '=' , 'sales_order_gmt_color_sizes.id');
                $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=' , 'sales_order_countries.id');
            })

            ->join('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id', '=' , 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->join('style_gmts',function($join){
                $join->on('style_gmts.id', '=' , 'style_gmt_color_sizes.style_gmt_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('companies as bcompany', function($join)  {
                $join->on('bcompany.id', '=', 'jobs.company_id');
            })
            /*->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })*/
            ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'wstudy_line_setups.company_id');
            })
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->leftJoin('teammembers', function($join)  {
                $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
            })           
            ->leftJoin('users', function($join)  {
                $join->on('users.id', '=', 'teammembers.user_id');
            })

            ->join('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })


            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })

            ->leftJoin('suppliers', function($join)  {
                $join->on('suppliers.id', '=', 'prod_gmt_sewing_orders.supplier_id');
            })
            ->join('users as receive_by', function($join)  {
                $join->on('receive_by.id', '=', 'prod_gmt_sewings.created_by');
            })
            ->leftJoin(\DB::raw("(
                select
                m.sales_order_id,
                m.color_id,
                m.size_id,
                m.sew_qc_date,
                m.supplier_id,
                sum(m.amount) as budget_cm_amount
                from

                (
                SELECT 
                sales_orders.id as sales_order_id,
                style_colors.color_id,
                style_sizes.size_id,
                prod_gmt_sewings.sew_qc_date,
                prod_gmt_sewing_orders.supplier_id,
                prod_gmt_sewing_qties.qty*budget_cms.cm_per_pcs as amount
                FROM prod_gmt_sewings
                join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
                
                join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join jobs on jobs.id = sales_orders.job_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
                join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
                join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
                join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id

                join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
                join budgets on budgets.style_id=style_gmts.style_id
                join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id
                where prod_gmt_sewings.sew_qc_date>='".$date_from."' and 
                prod_gmt_sewings.sew_qc_date<='".$date_to."'
                and prod_gmt_sewing_orders.prod_source_id=5
                ) m group by m.sales_order_id, m.color_id , m.size_id,m.sew_qc_date,m.supplier_id
            ) budgetCm"), [["budgetCm.sales_order_id", "=", "sales_orders.id"],["budgetCm.color_id", "=", "colors.id"],["budgetCm.size_id", "=", "sizes.id"],["budgetCm.sew_qc_date", "=", "prod_gmt_sewings.sew_qc_date"],["budgetCm.supplier_id", "=", "prod_gmt_sewing_orders.supplier_id"]])

            ->join(\DB::raw('(
                select style_gmts.style_id, 
                sum(style_gmts.gmt_qty)  as item_ratio 
                from style_gmts   
                group by style_gmts.style_id
            ) gmt_item_ratio'), "gmt_item_ratio.style_id", "=", "styles.id")
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_sewings.sew_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_sewings.sew_qc_date', '<=', $date_to);
            })
            ->when(request('supplier_id',0), function ($q){
                return $q->where('prod_gmt_sewing_orders.supplier_id', '=', request('supplier_id',0));
            })
            /*->when(request('produced_company_id',0), function ($q){
                return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id',0));
            })*/
            ->when(request('produced_company_id',0), function ($q){
                return $q->where('wstudy_line_setups.company_id', '=', request('produced_company_id',0));
            })
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('jobs.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('style_ref'), function ($q) {
                return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
            })
            ->where([['prod_gmt_sewing_orders.prod_source_id','=',5]])
            ->orderBy('prod_gmt_sewings.sew_qc_date')
            ->groupBy([
                'prod_gmt_sewings.sew_qc_date',
                'prod_gmt_sewing_orders.supplier_id',
                'sales_orders.id',
                'sales_orders.produced_company_id',
                'sales_orders.sale_order_no',
                'sales_orders.ship_date',
                'jobs.job_no',
                'styles.id',
                'styles.style_ref',
                'styles.flie_src',
                'styles.file_name',
                'users.id',
                'users.name',
                'buyers.id',
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'sizes.name',
                'colors.name',
                'suppliers.name',
                'receive_by.name',
                'budgetCm.budget_cm_amount',
                'gmt_item_ratio.item_ratio'
            ])
            //->toSql();
            //dd($sewingproduction);
            ->get()
            ->map(function($sewingproduction) use($date_from,$date_to){
                $cm_amount=$sewingproduction->budget_cm_amount;
                $sewingproduction->cm_rate=0;
                if($sewingproduction->sew_qty){
                    $sewingproduction->cm_rate=($cm_amount/$sewingproduction->sew_qty)*12;
                    //$cm_amount=($sewingproduction->cm_rate/12)*($sewingproduction->sew_qty/$sewingproduction->item_ratio);
                }
                $sewingproduction->ship_date=date('d-M-Y',strtotime($sewingproduction->ship_date));
                $sewingproduction->qc_date = date('d-M-Y',strtotime($sewingproduction->sew_qc_date));
                $sewingproduction->qty=number_format($sewingproduction->sew_qty,0);
                $sewingproduction->cm_amount=number_format($cm_amount,2);
                $sewingproduction->cm_rate=number_format($sewingproduction->cm_rate,4);
                return $sewingproduction;
            });
            echo json_encode($sewingproduction);
    	}
    	if($production_area_id == 65){
            $prodgmtiron=$this->prodgmtiron
            ->selectRaw('
                prod_gmt_irons.iron_qc_date,
                prod_gmt_iron_orders.supplier_id,
                sales_orders.id as sales_order_id,
                sales_orders.produced_company_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                jobs.job_no,
                styles.id as style_id,
                styles.style_ref,
                styles.flie_src,
                styles.file_name,
                users.id as user_id,
                users.name as dl_marchent,
                buyers.id as buyer_id,
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                sizes.name as size_name,
                colors.name as color_name,
                receive_by.name as recv_by,
                suppliers.name as supplier_name,
                sum(prod_gmt_iron_qties.qty) as iron_qty
            ')
            ->join('prod_gmt_iron_orders', function($join) {
                $join->on('prod_gmt_iron_orders.prod_gmt_iron_id', '=', 'prod_gmt_irons.id');
            })
             ->join('prod_gmt_iron_qties',function($join){
                $join->on('prod_gmt_iron_qties.prod_gmt_iron_order_id','=','prod_gmt_iron_orders.id');
            })
             ->leftJoin('wstudy_line_setups', function($join) {
                $join->on('wstudy_line_setups.id', '=', 'prod_gmt_iron_orders.wstudy_line_setup_id');
            })
            ->join('sales_order_countries',function($join){
                $join->on('sales_order_countries.id', '=' , 'prod_gmt_iron_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
            ->join('sales_order_gmt_color_sizes',function($join){
                $join->on('prod_gmt_iron_qties.sales_order_gmt_color_size_id', '=' , 'sales_order_gmt_color_sizes.id');
                $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=' , 'sales_order_countries.id');
            })
            ->join('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id', '=' , 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->join('style_gmts',function($join){
                $join->on('style_gmts.id', '=' , 'style_gmt_color_sizes.style_gmt_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('companies as bcompany', function($join)  {
                $join->on('bcompany.id', '=', 'jobs.company_id');
            })
            ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })
            /*->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'wstudy_line_setups.company_id');
            })*/
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->leftJoin('teammembers', function($join)  {
                $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
            })           
            ->leftJoin('users', function($join)  {
                $join->on('users.id', '=', 'teammembers.user_id');
            })
            ->join('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })
            ->leftJoin('suppliers', function($join)  {
                $join->on('suppliers.id', '=', 'prod_gmt_iron_orders.supplier_id');
            })
            ->join('users as receive_by', function($join)  {
                $join->on('receive_by.id', '=', 'prod_gmt_irons.created_by');
            })
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_irons.iron_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_irons.iron_qc_date', '<=', $date_to);
            })
            ->when(request('supplier_id',0), function ($q){
                return $q->where('prod_gmt_irons.supplier_id', '=', request('supplier_id',0));
            })
            ->when(request('produced_company_id',0), function ($q){
                return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id',0));
            })
            /*->when(request('produced_company_id',0), function ($q){
                return $q->where('wstudy_line_setups.company_id', '=', request('produced_company_id',0));
            })*/
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('jobs.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('style_ref'), function ($q) {
                return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
            })
            ->where([['prod_gmt_iron_orders.prod_source_id','=',5]])
            ->orderBy('prod_gmt_irons.iron_qc_date')
            ->groupBy([
                'prod_gmt_irons.iron_qc_date',
                'prod_gmt_iron_orders.supplier_id',
                'sales_orders.id',
                'sales_orders.produced_company_id',
                'sales_orders.sale_order_no',
                'sales_orders.ship_date',
                'jobs.job_no',
                'styles.id',
                'styles.style_ref',
                'styles.flie_src',
                'styles.file_name',
                'users.id',
                'users.name',
                'buyers.id',
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'sizes.name',
                'colors.name',
                'suppliers.name',
                'receive_by.name',
            ])
            ->get()
            ->map(function($prodgmtiron) use($date_from,$date_to){
                $prodgmtiron->ship_date=date('d-M-Y',strtotime($prodgmtiron->ship_date));
                $prodgmtiron->qc_date = date('d-M-Y',strtotime($prodgmtiron->iron_qc_date));
                $prodgmtiron->item_ratio=number_format(0,4);
                $prodgmtiron->qty=number_format($prodgmtiron->iron_qty,0);
                $prodgmtiron->cm_amount=number_format(0,2);
                $prodgmtiron->cm_rate=number_format(0,4);
                return $prodgmtiron;
            });
            echo json_encode($prodgmtiron);
        }
        if($production_area_id == 67){
            $prodgmtpoly=$this->prodgmtpoly
            ->selectRaw('
                prod_gmt_polies.poly_qc_date,
                prod_gmt_poly_orders.supplier_id,
                sales_orders.id as sales_order_id,
                sales_orders.produced_company_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                jobs.job_no,
                styles.id as style_id,
                styles.style_ref,
                styles.flie_src,
                styles.file_name,
                users.id as user_id,
                users.name as dl_marchent,
                buyers.id as buyer_id,
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                sizes.name as size_name,
                colors.name as color_name,
                receive_by.name as recv_by,
                suppliers.name as supplier_name,
                budgetCm.budget_cm_amount,
                gmt_item_ratio.item_ratio,
                sum(prod_gmt_poly_qties.qty) as poly_qty
            ')
            ->join('prod_gmt_poly_orders', function($join) {
                $join->on('prod_gmt_poly_orders.prod_gmt_poly_id', '=', 'prod_gmt_polies.id');
            })
            ->join('prod_gmt_poly_qties',function($join){
                $join->on('prod_gmt_poly_qties.prod_gmt_poly_order_id','=','prod_gmt_poly_orders.id');
            })
            ->leftJoin('wstudy_line_setups', function($join) {
                $join->on('wstudy_line_setups.id', '=', 'prod_gmt_poly_orders.wstudy_line_setup_id');
            })
            ->join('sales_order_countries',function($join){
                $join->on('sales_order_countries.id', '=' , 'prod_gmt_poly_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
            ->join('sales_order_gmt_color_sizes',function($join){
                $join->on('prod_gmt_poly_qties.sales_order_gmt_color_size_id', '=' , 'sales_order_gmt_color_sizes.id');
                $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=' , 'sales_order_countries.id');
            })
            ->join('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id', '=' , 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->join('style_gmts',function($join){
                $join->on('style_gmts.id', '=' , 'style_gmt_color_sizes.style_gmt_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('companies as bcompany', function($join)  {
                $join->on('bcompany.id', '=', 'jobs.company_id');
            })
            ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })
            /*->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'wstudy_line_setups.company_id');
            })*/
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->leftJoin('teammembers', function($join)  {
                $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
            })           
            ->leftJoin('users', function($join)  {
                $join->on('users.id', '=', 'teammembers.user_id');
            })
            ->join('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })
            ->leftJoin('suppliers', function($join)  {
                $join->on('suppliers.id', '=', 'prod_gmt_poly_orders.supplier_id');
            })
            ->join('users as receive_by', function($join)  {
                $join->on('receive_by.id', '=', 'prod_gmt_polies.created_by');
            })
            ->leftJoin(\DB::raw("(
                select
                m.sales_order_id,
                m.color_id,
                m.size_id,
                m.poly_qc_date,
                m.supplier_id,
                sum(m.amount) as budget_cm_amount
                from
                (
                SELECT 
                sales_orders.id as sales_order_id,
                style_colors.color_id,
                style_sizes.size_id,
                prod_gmt_polies.poly_qc_date,
                prod_gmt_poly_orders.supplier_id,
                prod_gmt_poly_qties.qty*budget_cms.cm_per_pcs as amount
                FROM prod_gmt_polies
                join prod_gmt_poly_orders on prod_gmt_poly_orders.prod_gmt_poly_id = prod_gmt_polies.id
                join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join jobs on jobs.id = sales_orders.job_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
                join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
                join style_colors on style_colors.id = style_gmt_color_sizes.style_color_id
                join style_sizes on style_sizes.id = style_gmt_color_sizes.style_size_id
                join prod_gmt_poly_qties on prod_gmt_poly_qties.prod_gmt_poly_order_id = prod_gmt_poly_orders.id  and prod_gmt_poly_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
                join budgets on budgets.style_id=style_gmts.style_id
                join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id
                where prod_gmt_polies.poly_qc_date>='".$date_from."' and 
                prod_gmt_polies.poly_qc_date<='".$date_to."'
                ) m group by m.sales_order_id , m.color_id , m.size_id,m.poly_qc_date,m.supplier_id
            ) budgetCm"), [["budgetCm.sales_order_id", "=", "sales_orders.id"],["budgetCm.color_id", "=", "colors.id"],["budgetCm.size_id", "=", "sizes.id"],["budgetCm.poly_qc_date", "=", "prod_gmt_polies.poly_qc_date"],["budgetCm.supplier_id", "=", "prod_gmt_poly_orders.supplier_id"]])
            ->join(\DB::raw('(
                select style_gmts.style_id, 
                sum(style_gmts.gmt_qty)  as item_ratio 
                from style_gmts 
                group by style_gmts.style_id
            ) gmt_item_ratio'), "gmt_item_ratio.style_id", "=", "styles.id")
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_polies.poly_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_polies.poly_qc_date', '<=', $date_to);
            })
            ->when(request('supplier_id',0), function ($q){
                return $q->where('prod_gmt_poly_orders.supplier_id', '=', request('supplier_id',0));
            })
            ->when(request('produced_company_id',0), function ($q){
                return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id',0));
            })
            /*->when(request('produced_company_id',0), function ($q){
                return $q->where('wstudy_line_setups.company_id', '=', request('produced_company_id',0));
            })*/
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('jobs.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('style_ref'), function ($q) {
                return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
            })
            ->where([['prod_gmt_poly_orders.prod_source_id','=',5]])
            ->orderBy('prod_gmt_polies.poly_qc_date')
            ->groupBy([
                'prod_gmt_polies.poly_qc_date',
                'prod_gmt_poly_orders.supplier_id',
                'sales_orders.id',
                'sales_orders.produced_company_id',
                'sales_orders.sale_order_no',
                'sales_orders.ship_date',
                'jobs.job_no',
                'styles.id',
                'styles.style_ref',
                'styles.flie_src',
                'styles.file_name',
                'users.id',
                'users.name',
                'buyers.id',
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'sizes.name',
                'colors.name',
                'suppliers.name',
                'receive_by.name',
                'budgetCm.budget_cm_amount',
                'gmt_item_ratio.item_ratio'
            ])
            ->get()
            ->map(function($prodgmtpoly) use($date_from,$date_to){
                $cm_amount=$prodgmtpoly->budget_cm_amount;
                $prodgmtpoly->cm_rate=0;
                if($prodgmtpoly->poly_qty){
                    $prodgmtpoly->cm_rate=($cm_amount/$prodgmtpoly->poly_qty)*12;
                    //$cm_amount=($prodgmtpoly->cm_rate/12)*($prodgmtpoly->poly_qty/$prodgmtpoly->item_ratio);
                }
                
                $prodgmtpoly->ship_date=date('d-M-Y',strtotime($prodgmtpoly->ship_date));
                $prodgmtpoly->qc_date = date('d-M-Y',strtotime($prodgmtpoly->poly_qc_date));
                $prodgmtpoly->item_ratio=number_format(0,4);
                $prodgmtpoly->qty=number_format($prodgmtpoly->poly_qty,0);
                $prodgmtpoly->cm_amount=number_format($cm_amount,2);
                $prodgmtpoly->cm_rate=number_format($prodgmtpoly->cm_rate,4);
                return $prodgmtpoly;
            });
            echo json_encode($prodgmtpoly);
        }

    }

    
    public function getProdGmtDlmerchant(){
        $dlmerchant = $this->user
			->leftJoin('employee_h_rs', function($join)  {
				$join->on('users.id', '=', 'employee_h_rs.user_id');
			})
			->when(request('buyer_id'), function ($q) {
				return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
			})
			->when(request('style_ref'), function ($q) {
				return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
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
				'employee_h_rs.contact'
			])
			->map(function($dlmerchant){
				$dlmerchant->date_of_join=date('d-M-Y',strtotime($dlmerchant->date_of_join));
				return $dlmerchant;
			});
			echo json_encode($dlmerchant);
    }

    public function getprodgmtfile(){
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

    
    public function getOrder(){
        $order=$this->salesorder
        ->selectRaw('
         sales_orders.id as sales_order_id,
         sales_orders.sale_order_no,
         sales_orders.ship_date,
         sales_orders.produced_company_id,
         styles.style_ref,
         styles.id as style_id,
         jobs.job_no,
         buyers.code as buyer_name,
         companies.name as company_id,
         produced_company.name as produced_company_name,
         sales_orders.qty as order_qty
         ')
        ->join('jobs', function($join)  {
             $join->on('jobs.id', '=', 'sales_orders.job_id');
         })
        ->join('companies', function($join)  {
             $join->on('companies.id', '=', 'jobs.company_id');
         })
        ->leftJoin('companies as produced_company', function($join)  {
            $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
        })
        ->join('styles', function($join)  {
             $join->on('styles.id', '=', 'jobs.style_id');
         })
         ->join('buyers', function($join)  {
         $join->on('buyers.id', '=', 'styles.buyer_id');
         })
         ->when(request('style_ref'), function ($q) {
             return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
         })
         ->when(request('job_no'), function ($q) {
             return $q->where('jobs.job_no', 'LIKE', "%".request('job_no', 0)."%");
         })
         ->when(request('sale_order_no'), function ($q) {
             return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
         })
         ->groupBy([
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'sales_orders.produced_company_id',
            'styles.style_ref',
            'styles.id',
            'jobs.job_no',
            'buyers.code',
            'companies.name',
            'produced_company.name',
            'sales_orders.qty'
        ])
        ->get()
        ->map(function ($order){
          return $order;
         });
        echo json_encode($order); 
    }

    public function getStyle(){
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

    public function getBuyer(){
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
        echo json_encode($rows);
    }

    public function getServiceProvider(){
        $supplier=$this->supplier
        ->where([['suppliers.id','=',request('supplier_id',0)]])
        ->get([
            'suppliers.*',
        ]);

        echo json_encode($supplier);
    }

    public function getPdf(){
        $date_from = request('date_from',0);
        $date_to=request('date_to',0);
        $supplier_id=request(('supplier_id'), 0);
        $buyer_id=request(('buyer_id'), 0);
        $company_id=request('company_id',0);
        $produced_company_id=request(('produced_company_id'), 0);
        $style_ref=request(('style_ref'), 0);
        $sale_order_no=request(('sale_order_no'), 0);
        $prod_source_id=request(('prod_source_id'), 0);
        $production_area_id=request('production_area_id',0);
        if($production_area_id == 40){
            $gmtproduction=$this->prodgmtcutting
            ->selectRaw('
                prod_gmt_cuttings.cut_qc_date,
                sales_orders.produced_company_id,
                sales_orders.id as sale_order_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                jobs.job_no,
                styles.style_ref,
                styles.flie_src,
                styles.file_name,
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                suppliers.name as supplier_name,
                sum(prod_gmt_cutting_qties.qty) as cut_qty,
                supplier.qty as sup_qty
            ')
            ->join('prod_gmt_cutting_orders', function($join) {
                $join->on('prod_gmt_cutting_orders.prod_gmt_cutting_id', '=', 'prod_gmt_cuttings.id');
            })
            ->join('prod_gmt_cutting_qties',function($join){
                $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id','=','prod_gmt_cutting_orders.id');
            })
            ->join('sales_order_countries',function($join){
                $join->on('sales_order_countries.id', '=' , 'prod_gmt_cutting_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            }) 
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('companies as bcompany', function($join)  {
                $join->on('bcompany.id', '=', 'jobs.company_id');
            })
            ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->join('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->leftJoin('suppliers', function($join)  {
                $join->on('suppliers.id', '=', 'prod_gmt_cutting_orders.supplier_id');
            })
            ->leftJoin(\DB::raw('(
                select
                    prod_gmt_cuttings.cut_qc_date,
                    suppliers.name as supplier_name,
                    sales_orders.id as sale_order_id,
                    sales_orders.sale_order_no,
                    sum(prod_gmt_cutting_qties.qty) as qty
                from
                prod_gmt_cuttings
                join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id=prod_gmt_cuttings.id
                join suppliers on suppliers.id=prod_gmt_cutting_orders.supplier_id
                join sales_order_countries on sales_order_countries.ID=prod_gmt_cutting_orders.SALES_ORDER_COUNTRY_ID
                join sales_orders on sales_orders.id=sales_order_countries.sale_order_id
                join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id=prod_gmt_cutting_orders.id
                where prod_gmt_cuttings.cut_qc_date < "'.$date_from.'"
                and prod_gmt_cutting_orders.PROD_SOURCE_ID=5
                
                group by
                prod_gmt_cuttings.cut_qc_date,
                suppliers.name,
                sales_orders.id,
                sales_orders.sale_order_no
            ) supplier'), "supplier.sale_order_id", "=", "sales_orders.id")
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_cuttings.cut_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_cuttings.cut_qc_date', '<=', $date_to);
            })
            ->when(request('supplier_id',0), function ($q){
                return $q->where('prod_gmt_cutting_orders.supplier_id', '=', request('supplier_id',0));
            })
            ->when(request('produced_company_id',0), function ($q){
                return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id',0));
            })
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('jobs.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('style_ref'), function ($q) {
                return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
            })
            ->where([['prod_gmt_cutting_orders.prod_source_id','=',5]])
            ->orderBy('prod_gmt_cuttings.cut_qc_date')
            ->groupBy([
                'prod_gmt_cuttings.cut_qc_date',
                'sales_orders.produced_company_id',
                'sales_orders.id',
                'sales_orders.sale_order_no',
                'sales_orders.ship_date',
                'jobs.job_no',
                'styles.style_ref',
                'styles.flie_src',
                'styles.file_name',
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'suppliers.name',
                'supplier.qty'
            ])
            ->get()
            ->map(function($gmtproduction) use($date_from,$date_to){
                $gmtproduction->ship_date=date('d-M-Y',strtotime($gmtproduction->ship_date));
                $gmtproduction->qc_date = date('d-M-Y',strtotime($gmtproduction->cut_qc_date));
                $gmtproduction->qty=$gmtproduction->cut_qty;
                return $gmtproduction;
            });
            //$totalQty+=$prodgmtcutting->qty;
           // dd($prodgmtcutting);
           // die;
            $txt="Cutting(".date('d-M-Y',strtotime($date_from))." - ".date('d-M-Y',strtotime($date_to)).")";
            $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            //$header=['logo'=>$company->logo,'address'=>$company->address,'title'=>'Challan'];
            //$pdf->setCustomHeader($header);
            //$pdf->SetPrintHeader(true);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(true);
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(7, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            //$pdf->SetFont('helvetica', 'B', 12);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', 'N', 8);
            $view= \View::make('Defult.Report.GmtProduction.SubcontractProductionReportPdf',['gmtproduction'=>$gmtproduction,'txt'=>$txt]);
            $html_content=$view->render();
            $pdf->SetY(10);
            $pdf->WriteHtml($html_content, true, false,true,false,'');
            $filename = storage_path() . '/SubcontractProductionReportPdf.pdf';
            $pdf->output($filename);
            exit();
        }
        if($production_area_id == 55){
            $gmtproduction=$this->prodgmtsewing
            ->selectRaw('
                prod_gmt_sewings.sew_qc_date,
                prod_gmt_sewing_orders.supplier_id,
                sales_orders.id as sales_order_id,
                sales_orders.produced_company_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                jobs.job_no,
                styles.id as style_id,
                styles.style_ref,
                buyers.id as buyer_id,
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                suppliers.name as supplier_name,
                sum(prod_gmt_sewing_qties.qty) as sew_qty
            ')
            ->join('prod_gmt_sewing_orders', function($join) {
                $join->on('prod_gmt_sewing_orders.prod_gmt_sewing_id', '=', 'prod_gmt_sewings.id');
            })
             ->join('prod_gmt_sewing_qties',function($join){
                $join->on('prod_gmt_sewing_qties.prod_gmt_sewing_order_id','=','prod_gmt_sewing_orders.id');
            })
             ->join('wstudy_line_setups', function($join) {
                $join->on('wstudy_line_setups.id', '=', 'prod_gmt_sewing_orders.wstudy_line_setup_id');
            })
            ->join('sales_order_countries',function($join){
                $join->on('sales_order_countries.id', '=' , 'prod_gmt_sewing_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('companies as bcompany', function($join)  {
                $join->on('bcompany.id', '=', 'jobs.company_id');
            })
            /*->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })*/
            ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'wstudy_line_setups.company_id');
            })
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->join('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->leftJoin('suppliers', function($join)  {
                $join->on('suppliers.id', '=', 'prod_gmt_sewing_orders.supplier_id');
            })
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_sewings.sew_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_sewings.sew_qc_date', '<=', $date_to);
            })
            ->when(request('supplier_id',0), function ($q){
                return $q->where('prod_gmt_sewing_orders.supplier_id', '=', request('supplier_id',0));
            })
            /*->when(request('produced_company_id',0), function ($q){
                return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id',0));
            })*/
            ->when(request('produced_company_id',0), function ($q){
                return $q->where('wstudy_line_setups.company_id', '=', request('produced_company_id',0));
            })
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('jobs.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('style_ref'), function ($q) {
                return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
            })
            ->where([['prod_gmt_sewing_orders.prod_source_id','=',5]])
            ->orderBy('prod_gmt_sewings.sew_qc_date')
            ->orderBy('sales_orders.sale_order_no')
            ->groupBy([
                'prod_gmt_sewings.sew_qc_date',
                'prod_gmt_sewing_orders.supplier_id',
                'sales_orders.id',
                'sales_orders.produced_company_id',
                'sales_orders.sale_order_no',
                'sales_orders.ship_date',
                'jobs.job_no',
                'styles.id',
                'styles.style_ref',
                'buyers.id',
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'suppliers.name',
            ])
            //->toSql();
            //dd($sewingproduction);
            ->get()
            ->map(function($gmtproduction) use($date_from,$date_to){
                $gmtproduction->ship_date=date('d-M-Y',strtotime($gmtproduction->ship_date));
                $gmtproduction->qc_date = date('d-M-Y',strtotime($gmtproduction->sew_qc_date));
                $gmtproduction->qty= $gmtproduction->sew_qty;
                return $gmtproduction;
            });
            $txt="Sewing(".date('d-M-Y',strtotime($date_from))." - ".date('d-M-Y',strtotime($date_to)).")";
            $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            //$header=['logo'=>$company->logo,'address'=>$company->address,'title'=>'Challan'];
            //$pdf->setCustomHeader($header);
            //$pdf->SetPrintHeader(true);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(true);
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(7, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            //$pdf->SetFont('helvetica', 'B', 12);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', 'N', 8);
            $view= \View::make('Defult.Report.GmtProduction.SubcontractProductionReportPdf',['gmtproduction'=>$gmtproduction,'txt'=>$txt]);
            $html_content=$view->render();
            $pdf->SetY(10);
            $pdf->WriteHtml($html_content, true, false,true,false,'');
            $filename = storage_path() . '/SubcontractProductionReportPdf.pdf';
            $pdf->output($filename);
            exit();
        }
        if($production_area_id == 65){
            $gmtproduction=$this->prodgmtiron
            ->selectRaw('
                prod_gmt_irons.iron_qc_date,
                prod_gmt_iron_orders.supplier_id,
                sales_orders.id as sales_order_id,
                sales_orders.produced_company_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                jobs.job_no,
                styles.id as style_id,
                styles.style_ref,
                buyers.id as buyer_id,
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                suppliers.name as supplier_name,
                sum(prod_gmt_iron_qties.qty) as iron_qty
            ')
            ->join('prod_gmt_iron_orders', function($join) {
                $join->on('prod_gmt_iron_orders.prod_gmt_iron_id', '=', 'prod_gmt_irons.id');
            })
             ->join('prod_gmt_iron_qties',function($join){
                $join->on('prod_gmt_iron_qties.prod_gmt_iron_order_id','=','prod_gmt_iron_orders.id');
            })
             ->join('wstudy_line_setups', function($join) {
                $join->on('wstudy_line_setups.id', '=', 'prod_gmt_iron_orders.wstudy_line_setup_id');
            })
            ->join('sales_order_countries',function($join){
                $join->on('sales_order_countries.id', '=' , 'prod_gmt_iron_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('companies as bcompany', function($join)  {
                $join->on('bcompany.id', '=', 'jobs.company_id');
            })
            ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })
            /*->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'wstudy_line_setups.company_id');
            })*/
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->join('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->leftJoin('suppliers', function($join)  {
                $join->on('suppliers.id', '=', 'prod_gmt_iron_orders.supplier_id');
            })
            ->join('users as receive_by', function($join)  {
                $join->on('receive_by.id', '=', 'prod_gmt_irons.created_by');
            })
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_irons.iron_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_irons.iron_qc_date', '<=', $date_to);
            })
            ->when(request('supplier_id',0), function ($q){
                return $q->where('prod_gmt_irons.supplier_id', '=', request('supplier_id',0));
            })
            ->when(request('produced_company_id',0), function ($q){
                return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id',0));
            })
            /*->when(request('produced_company_id',0), function ($q){
                return $q->where('wstudy_line_setups.company_id', '=', request('produced_company_id',0));
            })*/
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('jobs.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('style_ref'), function ($q) {
                return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
            })
            ->where([['prod_gmt_iron_orders.prod_source_id','=',5]])
            ->orderBy('prod_gmt_irons.iron_qc_date')
            ->groupBy([
                'prod_gmt_irons.iron_qc_date',
                'prod_gmt_iron_orders.supplier_id',
                'sales_orders.id',
                'sales_orders.produced_company_id',
                'sales_orders.sale_order_no',
                'sales_orders.ship_date',
                'jobs.job_no',
                'styles.id',
                'styles.style_ref',
                'buyers.id',
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'suppliers.name',
            ])
            ->get()
            ->map(function($gmtproduction) use($date_from,$date_to){
                $gmtproduction->ship_date=date('d-M-Y',strtotime($gmtproduction->ship_date));
                $gmtproduction->qc_date = date('d-M-Y',strtotime($gmtproduction->iron_qc_date));
                $gmtproduction->qty=$gmtproduction->iron_qty;
                return $gmtproduction;
            });

            $txt="Iron(".date('d-M-Y',strtotime($date_from))." - ".date('d-M-Y',strtotime($date_to)).")";
            $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            //$header=['logo'=>$company->logo,'address'=>$company->address,'title'=>'Challan'];
            //$pdf->setCustomHeader($header);
            //$pdf->SetPrintHeader(true);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(true);
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(7, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            //$pdf->SetFont('helvetica', 'B', 12);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', 'N', 8);
            $view= \View::make('Defult.Report.GmtProduction.SubcontractProductionReportPdf',['gmtproduction'=>$gmtproduction,'txt'=>$txt]);
            $html_content=$view->render();
            $pdf->SetY(10);
            $pdf->WriteHtml($html_content, true, false,true,false,'');
            $filename = storage_path() . '/SubcontractProductionReportPdf.pdf';
            $pdf->output($filename);
            exit();

        }
        if($production_area_id == 67){
            $gmtproduction=$this->prodgmtpoly
            ->selectRaw('
                prod_gmt_polies.poly_qc_date,
                prod_gmt_poly_orders.supplier_id,
                sales_orders.id as sales_order_id,
                sales_orders.produced_company_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                jobs.job_no,
                styles.id as style_id,
                styles.style_ref,
                buyers.id as buyer_id,
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                suppliers.name as supplier_name,
                sum(prod_gmt_poly_qties.qty) as poly_qty
            ')
            ->join('prod_gmt_poly_orders', function($join) {
                $join->on('prod_gmt_poly_orders.prod_gmt_poly_id', '=', 'prod_gmt_polies.id');
            })
             ->join('prod_gmt_poly_qties',function($join){
                $join->on('prod_gmt_poly_qties.prod_gmt_poly_order_id','=','prod_gmt_poly_orders.id');
            })
             ->join('wstudy_line_setups', function($join) {
                $join->on('wstudy_line_setups.id', '=', 'prod_gmt_poly_orders.wstudy_line_setup_id');
            })
            ->join('sales_order_countries',function($join){
                $join->on('sales_order_countries.id', '=' , 'prod_gmt_poly_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('companies as bcompany', function($join)  {
                $join->on('bcompany.id', '=', 'jobs.company_id');
            })
            ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })
            /*->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'wstudy_line_setups.company_id');
            })*/
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->join('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->leftJoin('suppliers', function($join)  {
                $join->on('suppliers.id', '=', 'prod_gmt_poly_orders.supplier_id');
            })
            ->join('users as receive_by', function($join)  {
                $join->on('receive_by.id', '=', 'prod_gmt_polies.created_by');
            })
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_polies.poly_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_polies.poly_qc_date', '<=', $date_to);
            })
            ->when(request('supplier_id',0), function ($q){
                return $q->where('prod_gmt_polies.supplier_id', '=', request('supplier_id',0));
            })
            ->when(request('produced_company_id',0), function ($q){
                return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id',0));
            })
            /*->when(request('produced_company_id',0), function ($q){
                return $q->where('wstudy_line_setups.company_id', '=', request('produced_company_id',0));
            })*/
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('jobs.company_id', '=', request('company_id', 0));
            })
            ->when(request('buyer_id'), function ($q) {
                return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
            })
            ->when(request('style_ref'), function ($q) {
                return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
            })
            ->where([['prod_gmt_poly_orders.prod_source_id','=',5]])
            ->orderBy('prod_gmt_polies.poly_qc_date')
            ->groupBy([
                'prod_gmt_polies.poly_qc_date',
                'prod_gmt_poly_orders.supplier_id',
                'sales_orders.id',
                'sales_orders.produced_company_id',
                'sales_orders.sale_order_no',
                'sales_orders.ship_date',
                'jobs.job_no',
                'styles.id',
                'styles.style_ref',
                'buyers.id',
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'suppliers.name',
            ])
            ->get()
            ->map(function($gmtproduction) use($date_from,$date_to){
                $gmtproduction->ship_date=date('d-M-Y',strtotime($gmtproduction->ship_date));
                $gmtproduction->qc_date = date('d-M-Y',strtotime($gmtproduction->poly_qc_date));
                $gmtproduction->qty=$gmtproduction->poly_qty;
                return $gmtproduction;
            });

            $txt="Poly(".date('d-M-Y',strtotime($date_from))." - ".date('d-M-Y',strtotime($date_to)).")";
            $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            //$header=['logo'=>$company->logo,'address'=>$company->address,'title'=>'Challan'];
            //$pdf->setCustomHeader($header);
            //$pdf->SetPrintHeader(true);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(true);
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(7, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            //$pdf->SetFont('helvetica', 'B', 12);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', 'N', 8);
            $view= \View::make('Defult.Report.GmtProduction.SubcontractProductionReportPdf',['gmtproduction'=>$gmtproduction,'txt'=>$txt]);
            $html_content=$view->render();
            $pdf->SetY(10);
            $pdf->WriteHtml($html_content, true, false,true,false,'');
            $filename = storage_path() . '/SubcontractProductionReportPdf.pdf';
            $pdf->output($filename);
            exit();

        }

    }
}
