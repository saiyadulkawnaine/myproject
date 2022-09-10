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

class ProdGmtDailyReportController extends Controller
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
		//$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $prodcompany=array_prepend(array_pluck($this->supplier
      ->join('companies', function($join)  {
        $join->on('companies.id', '=', 'suppliers.company_id');
      })
      ->get([
        'suppliers.name',
        'suppliers.id'
      ]),'name','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'-Select-','');
      $productionarea=array_prepend(array_only(config('bprs.productionarea'),[40,45,50,55,65,67,70]),'-Select-','');
      $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
      return Template::loadView('Report.GmtProduction.ProdGmtDailyReport',['company'=>$company,'buyer'=>$buyer,'supplier'=>$supplier,'productionarea'=>$productionarea,'prodcompany'=>$prodcompany,'productionsource'=>$productionsource]);
    }
	public function reportData() {

        $date_from = request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $produced_company_id=request(('produced_company_id'), 0);
        $production_area_id=request(('production_area_id'), 0);
        $prod_source_id=request(('prod_source_id'), 0);
        if($production_area_id == 40){
            $dailyproduction=$this->prodgmtcutting
            ->selectRaw('
                prod_gmt_cuttings.cut_qc_date as production_date,
                styles.id as style_id,
                styles.style_ref,
                styles.flie_src,
                styles.file_name,
			    users.id as user_id,
                buyers.name as buyer_name,
                users.name as dl_marchent,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                companies.code as supplier_id,
                sales_orders.id as sales_order_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                sum(prod_gmt_cutting_qties.qty) as cut_qty
            ')
            ->join('prod_gmt_cutting_orders', function($join) use($date_to) {
                $join->on('prod_gmt_cutting_orders.prod_gmt_cutting_id', '=', 'prod_gmt_cuttings.id');
            })
            ->join('suppliers', function($join) {
                $join->on( 'suppliers.id','=','prod_gmt_cutting_orders.supplier_id');
            })
            ->join('sales_order_countries', function($join) {
                $join->on('sales_order_countries.id', '=', 'prod_gmt_cutting_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
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
            ->join('prod_gmt_cutting_qties',function($join) use($date_to){
                $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id','=','prod_gmt_cutting_orders.id');
                $join->on('prod_gmt_cutting_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('companies', function($join)  {
                $join->on('companies.id', '=', 'suppliers.company_id');
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
            ->leftJoin('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_cuttings.cut_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to)  {
                return $q->where('prod_gmt_cuttings.cut_qc_date', '<=', $date_to);
            })
            ->when(request('produced_company_id'), function ($q) use($produced_company_id) {
                return $q->where('sales_orders.produced_company_id', '=', $produced_company_id);
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
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->when(request('prod_source_id',0), function ($q) use($prod_source_id){
                return $q->where('prod_gmt_cutting_orders.prod_source_id', '=', $prod_source_id);
            }) 
            ->orderBY('sales_orders.id')
            ->groupBy([
                'prod_gmt_cuttings.cut_qc_date',
                'styles.id',
                'styles.style_ref',
                'styles.flie_src',
                'styles.file_name',
                'buyers.name',
                'users.name',
			    'users.id',
                'bcompany.code',
                'produced_company.code',
                'companies.code',
                'sales_orders.id',
                'sales_orders.sale_order_no',
                'sales_orders.ship_date'
            ])
            ->get()
            ->map(function($dailyproduction) use($date_to,$date_from){
                $dailyproduction->ship_date=date('d-M-Y',strtotime($dailyproduction->ship_date));
                $dailyproduction->production_date = date('d-M-Y',strtotime($dailyproduction->production_date));
                $dailyproduction->cut_qty=number_format($dailyproduction->cut_qty,0);
                $dailyproduction->print_qty=number_format(0,0);
                $dailyproduction->emb_qty=number_format(0,0);
                $dailyproduction->finishing_qty=number_format(0,0);
                $dailyproduction->finishing_amount=number_format(0,0);
                $dailyproduction->sew_qty=number_format(0,0);
                $dailyproduction->iron_qty=number_format(0,0);
                $dailyproduction->poly_qty=number_format(0,0);
                $dailyproduction->cm_amount=number_format(0,0);
                $dailyproduction->cm_rate=number_format(0,0);
                $dailyproduction->mkt_cm_amount=number_format(0,2);
                $dailyproduction->mkt_cm_rate=number_format(0,4);
                $dailyproduction->prod_hour=number_format(0,0);
                return $dailyproduction;
            });
            echo json_encode($dailyproduction);
        }
        if ($production_area_id==45) {
            $dailyproduction=$this->prodgmtprintrcv
            ->selectRaw('
                prod_gmt_print_rcvs.receive_date as production_date,
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
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                companies.code as supplier_id,
                sum(prod_gmt_print_rcv_qties.qty) as print_qty
            ')
            ->join('prod_gmt_print_rcv_orders', function($join) use($date_to)  {
                $join->on('prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id', '=', 'prod_gmt_print_rcvs.id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','prod_gmt_print_rcv_orders.supplier_id');
            })
            ->join('sales_order_countries', function($join)  {
                $join->on('sales_order_countries.id', '=', 'prod_gmt_print_rcv_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            }) 
            ->join('companies', function($join)  {
                $join->on('companies.id', '=', 'suppliers.company_id');
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
            ->join('sales_order_gmt_color_sizes', function($join)  {
                $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
            //->whereNull('sales_order_gmt_color_sizes.deleted_at'); 
            })
            ->leftJoin('prod_gmt_print_rcv_qties',function($join) use($date_to){
                $join->on('prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id','=','prod_gmt_print_rcv_orders.id');
                $join->on('prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
            })
            ->leftJoin('teammembers', function($join)  {
                $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
            })
            ->leftJoin('users', function($join)  {
                $join->on('users.id', '=', 'teammembers.user_id');
            })
            ->leftJoin('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_print_rcvs.receive_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_print_rcvs.receive_date', '<=', $date_to);
            })
            ->when(request('produced_company_id'), function ($q) use($produced_company_id) {
                return $q->where('sales_orders.produced_company_id', '=', $produced_company_id );
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
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->orderBy('sales_orders.id')
            ->groupBy([
                'prod_gmt_print_rcvs.receive_date',
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
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'companies.code'
            ])
            ->get()
            ->map(function($dailyproduction) use($date_from,$date_to){
                $dailyproduction->ship_date=date('d-M-Y',strtotime($dailyproduction->ship_date));
                $dailyproduction->production_date = date('d-M-Y',strtotime($dailyproduction->production_date));
                $dailyproduction->cut_qty=number_format(0,0);
                $dailyproduction->print_qty=number_format($dailyproduction->print_qty,0);
                $dailyproduction->emb_qty=number_format(0,0);
                $dailyproduction->finishing_qty=number_format(0,0);
                $dailyproduction->finishing_amount=number_format(0,0);
                $dailyproduction->iron_qty=number_format(0,0);
                $dailyproduction->poly_qty=number_format(0,0);
                $dailyproduction->sew_qty=number_format(0,0);
                $dailyproduction->cm_amount=number_format(0,0);
                $dailyproduction->cm_rate=number_format(0,0);
                $dailyproduction->mkt_cm_amount=number_format(0,2);
                $dailyproduction->mkt_cm_rate=number_format(0,4);
                $dailyproduction->prod_hour=number_format(0,0);
                return $dailyproduction;
            });
            echo json_encode($dailyproduction);
        }
        if ($production_area_id==50) {
            $dailyproduction=$this->prodgmtembrcv
            ->selectRaw('
                prod_gmt_emb_rcvs.receive_date as production_date,
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
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                companies.code as supplier_id,
                sum(prod_gmt_emb_rcv_qties.qty) as emb_qty
            ')
            ->join('prod_gmt_emb_rcv_orders', function($join)  {
                $join->on('prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id', '=', 'prod_gmt_emb_rcvs.id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','prod_gmt_emb_rcv_orders.supplier_id');
            })
            ->join('sales_order_countries', function($join)  {
                $join->on('sales_order_countries.id', '=', 'prod_gmt_emb_rcv_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('sales_order_gmt_color_sizes', function($join)  {
                $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
            //->whereNull('sales_order_gmt_color_sizes.deleted_at'); 
            })
            ->leftJoin('prod_gmt_emb_rcv_qties',function($join){
                $join->on('prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id','=','prod_gmt_emb_rcv_orders.id');
                $join->on('prod_gmt_emb_rcv_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
             })
            ->join('companies', function($join)  {
                $join->on('companies.id', '=', 'suppliers.company_id');
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
            ->leftJoin('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->when(request('date_from'), function ($q) {
                return $q->where('prod_gmt_emb_rcvs.receive_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
                return $q->where('prod_gmt_emb_rcvs.receive_date', '<=',request('date_to', 0));
            })
            ->when(request('produced_company_id'), function ($q) use($produced_company_id) {
                return $q->where('sales_orders.produced_company_id', '=', $produced_company_id);
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
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->orderBY('sales_orders.id')
            ->groupBy([
                'prod_gmt_emb_rcvs.receive_date',
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
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'companies.code'
            ])
            ->get()
            ->map(function($dailyproduction) use($date_to,$date_from){
                $dailyproduction->ship_date=date('d-M-Y',strtotime($dailyproduction->ship_date));
                $dailyproduction->production_date = date('d-M-Y',strtotime($dailyproduction->production_date));
                $dailyproduction->cut_qty=number_format(0,0);
                $dailyproduction->print_qty=number_format(0,0);
                $dailyproduction->emb_qty=number_format($dailyproduction->emb_qty,0);
                $dailyproduction->finishing_qty=number_format(0,0);
                $dailyproduction->finishing_amount=number_format(0,0);
                $dailyproduction->iron_qty=number_format(0,0);
                $dailyproduction->poly_qty=number_format(0,0);
                $dailyproduction->sew_qty=number_format(0,0);
                $dailyproduction->cm_amount=number_format(0,0);
                $dailyproduction->cm_rate=number_format(0,0);
                $dailyproduction->mkt_cm_amount=number_format(0,2);
                $dailyproduction->mkt_cm_rate=number_format(0,4);
                $dailyproduction->prod_hour=number_format(0,0);
                return $dailyproduction;
            });
            echo json_encode($dailyproduction);
        }
        if ($production_area_id==55) {
            $dailyproduction=$this->prodgmtsewing
            ->selectRaw('
                prod_gmt_sewings.sew_qc_date as production_date,
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
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                budgetCm.budget_cm_amount,
                mktCm.mkt_cm_amount,
                gmt_item_ratio.item_ratio,
                saleorders.rate,
                bookedsmv.smv,
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
            /* ->join('wstudy_line_setup_dtls', function($join) use($date_from,$date_to)  {
                $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
                $join->where('wstudy_line_setup_dtls.from_date', '>=',$date_from);
                $join->where('wstudy_line_setup_dtls.to_date', '<=',$date_to);
            }) */
            ->join('sales_order_countries',function($join){
                $join->on('sales_order_countries.id', '=' , 'prod_gmt_sewing_orders.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            })
            ->join('sales_order_gmt_color_sizes',function($join){
                $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=' , 'sales_order_countries.id');
                $join->on('prod_gmt_sewing_qties.sales_order_gmt_color_size_id', '=' , 'sales_order_gmt_color_sizes.id');
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
           /* ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })*/
            ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'wstudy_line_setups.company_id');
            })
            /* ->leftJoin('suppliers', function($join)  {
                $join->on('suppliers.company_id', '=', 'produced_company.id');
            }) */
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->leftJoin('teammembers', function($join)  {
                $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
            })           
            ->leftJoin('users', function($join)  {
                $join->on('users.id', '=', 'teammembers.user_id');
            })
            ->leftJoin(\DB::raw("(
                select
                m.sales_order_id,
                m.sew_qc_date,
                sum(m.amount) as budget_cm_amount
                from

                (
                SELECT 
                sales_orders.id as sales_order_id,
                prod_gmt_sewings.sew_qc_date,
                wstudy_line_setups.id,
                prod_gmt_sewing_qties.qty*budget_cms.cm_per_pcs as amount
                FROM prod_gmt_sewings
                join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
                join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
                join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
                wstudy_line_setup_dtls.from_date>='".$date_from."' and 
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
                where prod_gmt_sewings.sew_qc_date>='".$date_from."' and 
                prod_gmt_sewings.sew_qc_date<='".$date_to."'
                ) m group by m.sales_order_id,m.sew_qc_date
            ) budgetCm"), [["budgetCm.sales_order_id", "=", "sales_orders.id"],["budgetCm.sew_qc_date", "=", "prod_gmt_sewings.sew_qc_date"]])

            ->leftJoin(\DB::raw("(
				select
                m.sales_order_id,
                m.sew_qc_date,
                sum(m.amount) as mkt_cm_amount
                from

                (
                SELECT 
                sales_orders.id as sales_order_id,
                prod_gmt_sewings.sew_qc_date,
                wstudy_line_setups.id,
                prod_gmt_sewing_qties.qty*mkt_cost_cms.cm_per_pcs as amount
                FROM prod_gmt_sewings
                join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
                join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
                join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
                wstudy_line_setup_dtls.from_date>='".$date_from."' and 
                wstudy_line_setup_dtls.to_date<='".$date_to."'
                join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join jobs on jobs.id = sales_orders.job_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
                join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

                join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
                join mkt_costs on mkt_costs.style_id=style_gmts.style_id
                join mkt_cost_cms on mkt_cost_cms.mkt_cost_id=mkt_costs.id and mkt_cost_cms.style_gmt_id=style_gmts.id
                where prod_gmt_sewings.sew_qc_date>='".$date_from."' and 
                prod_gmt_sewings.sew_qc_date<='".$date_to."'
                ) m group by m.sales_order_id,m.sew_qc_date
            ) mktCm"), [["mktCm.sales_order_id", "=", "sales_orders.id"],["mktCm.sew_qc_date", "=", "prod_gmt_sewings.sew_qc_date"]])

            ->join(\DB::raw('(
                select style_gmts.style_id, 
                sum(style_gmts.gmt_qty)  as item_ratio 
                from style_gmts   
                group by style_gmts.style_id
            ) gmt_item_ratio'), "gmt_item_ratio.style_id", "=", "styles.id")

            ->leftJoin(\DB::raw("(SELECT 
                sales_orders.id,
                avg(sales_order_gmt_color_sizes.rate) as rate
                FROM sales_order_countries
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                group by sales_orders.id
            ) saleorders"), "saleorders.id", "=", "sales_orders.id")

            ->leftJoin(\DB::raw('(select 
                m.sales_order_id,
                avg(m.smv) as smv
                from 
                (
                SELECT 
                sales_orders.id as sales_order_id,
                style_gmts.smv,
                style_gmts.sewing_effi_per
                FROM sales_orders 
                join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
                join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
                where  1=1
                ) m 
                group by 
                m.sales_order_id
            ) bookedsmv'), "bookedsmv.sales_order_id", "=", "sales_orders.id")

            ->join('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_sewings.sew_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_sewings.sew_qc_date', '<=', $date_to);
            })
            ->when(request('prod_source_id',0), function ($q) use($prod_source_id){
                return $q->where('prod_gmt_sewing_orders.prod_source_id', '=', $prod_source_id);
            })
            /*->when(request('produced_company_id',0), function ($q){
                return $q->where('sales_orders.produced_company_id', '=', request('produced_company_id',0));
            }) */
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
            //->where([['prod_gmt_sewing_orders.prod_source_id','=',5]])
            ->orderBy('sales_orders.id')
            ->groupBy([
                'prod_gmt_sewings.sew_qc_date',
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
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'budgetCm.budget_cm_amount',
                'mktCm.mkt_cm_amount',
                'gmt_item_ratio.item_ratio',
                'saleorders.rate',
                'bookedsmv.smv',
            ])
            //->toSql();
            //dd($dailyproduction);
            ->get()
            ->map(function($dailyproduction) use($date_from,$date_to){
                $cm_amount=0;
                $mkt_cm_amount=0;
                $cm_amount=$dailyproduction->budget_cm_amount;
                $mkt_cm_amount=$dailyproduction->mkt_cm_amount;
                $dailyproduction->cm_rate=0;
                $dailyproduction->mkt_cm_rate=0;
                if($dailyproduction->sew_qty){
                    $dailyproduction->cm_rate= ($cm_amount/$dailyproduction->sew_qty)*12;
                    $dailyproduction->mkt_cm_rate= ($mkt_cm_amount/$dailyproduction->sew_qty)*12;
                    //$cm_amount=($dailyproduction->cm_rate/12)*($dailyproduction->sew_qty/$dailyproduction->item_ratio);
                    //$mkt_cm_amount=($dailyproduction->mkt_cm_rate/12)*($dailyproduction->sew_qty/$dailyproduction->item_ratio);
                }
                $dailyproduction->prod_hour=($dailyproduction->sew_qty*$dailyproduction->smv)/60;
                $dailyproduction->ship_date=date('d-M-Y',strtotime($dailyproduction->ship_date));
                $dailyproduction->production_date = date('d-M-Y',strtotime($dailyproduction->production_date));
                $dailyproduction->cut_qty=number_format(0,0);
                $dailyproduction->print_qty=number_format(0,0);
                $dailyproduction->emb_qty=number_format(0,0);
                $dailyproduction->finishing_qty=number_format(0,0);
                $dailyproduction->finishing_amount=number_format($dailyproduction->sew_qty*$dailyproduction->rate,0);
                $dailyproduction->sew_qty=number_format($dailyproduction->sew_qty,0);
                $dailyproduction->iron_qty=number_format(0,0);
                $dailyproduction->poly_qty=number_format(0,0);
                $dailyproduction->cm_amount=number_format($cm_amount,2);
                $dailyproduction->cm_rate=number_format($dailyproduction->cm_rate,4);
                $dailyproduction->mkt_cm_amount=number_format($mkt_cm_amount,2);
                $dailyproduction->mkt_cm_rate=number_format($dailyproduction->mkt_cm_rate,4);
                $dailyproduction->prod_hour=number_format($dailyproduction->prod_hour,0);
                return $dailyproduction;
            });
            echo json_encode($dailyproduction);
        }
        if ($production_area_id==65) {
            $dailyproduction=$this->prodgmtiron
            ->selectRaw('
                prod_gmt_irons.iron_qc_date as production_date,
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
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
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
                $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=' , 'sales_order_countries.id');
                $join->on('prod_gmt_iron_qties.sales_order_gmt_color_size_id', '=' , 'sales_order_gmt_color_sizes.id');
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
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_irons.iron_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_irons.iron_qc_date', '<=', $date_to);
            })
            ->when(request('prod_source_id',0), function ($q) use($prod_source_id){
                return $q->where('prod_gmt_iron_orders.prod_source_id', '=', $prod_source_id);
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
            ->orderBy('sales_orders.id')
            ->groupBy([
                'prod_gmt_irons.iron_qc_date',
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
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
            ])
            //->toSql();
            //dd($dailyproduction);
            ->get()
            ->map(function($dailyproduction) use($date_from,$date_to){
                $dailyproduction->ship_date=date('d-M-Y',strtotime($dailyproduction->ship_date));
                $dailyproduction->production_date = date('d-M-Y',strtotime($dailyproduction->production_date));
                $dailyproduction->cut_qty=number_format(0,0);
                $dailyproduction->print_qty=number_format(0,0);
                $dailyproduction->emb_qty=number_format(0,0);
                $dailyproduction->finishing_qty=number_format(0,0);
                $dailyproduction->finishing_amount=number_format(0,0);
                $dailyproduction->iron_qty=number_format($dailyproduction->iron_qty,0);
                $dailyproduction->poly_qty=number_format(0,0);
                $dailyproduction->sew_qty=number_format(0,0);
                $dailyproduction->cm_amount=number_format(0,2);
                $dailyproduction->cm_rate=number_format(0,4);
                $dailyproduction->mkt_cm_amount=number_format(0,2);
                $dailyproduction->mkt_cm_rate=number_format(0,4);
                $dailyproduction->prod_hour=number_format(0,0);
                return $dailyproduction;
            });
            echo json_encode($dailyproduction);
        }
        if ($production_area_id==67) {
            $dailyproduction=$this->prodgmtpoly
            ->selectRaw('
                prod_gmt_polies.poly_qc_date as production_date,
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
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                budgetCm.budget_cm_amount,
                mktCm.mkt_cm_amount,
                gmt_item_ratio.item_ratio,
                bookedsmv.smv,
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
                $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=' , 'sales_order_countries.id');
                $join->on('prod_gmt_poly_qties.sales_order_gmt_color_size_id', '=' , 'sales_order_gmt_color_sizes.id');
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
                $join->on('produced_company.id', '=', 'wstudy_line_setups.company_id');
            })*/
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
            ->leftJoin(\DB::raw("(
                select
                m.sales_order_id,
                m.poly_qc_date,
                sum(m.amount) as budget_cm_amount
                from
                (
                SELECT 
                sales_orders.id as sales_order_id,
                prod_gmt_polies.poly_qc_date,
                prod_gmt_poly_qties.qty*budget_cms.cm_per_pcs as amount
                FROM prod_gmt_polies
                join prod_gmt_poly_orders on prod_gmt_poly_orders.prod_gmt_poly_id = prod_gmt_polies.id
                join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join jobs on jobs.id = sales_orders.job_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
                join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
                join prod_gmt_poly_qties on prod_gmt_poly_qties.prod_gmt_poly_order_id = prod_gmt_poly_orders.id  and prod_gmt_poly_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
                join budgets on budgets.style_id=style_gmts.style_id
                join budget_cms on budget_cms.budget_id=budgets.id and budget_cms.style_gmt_id=style_gmts.id
                where prod_gmt_polies.poly_qc_date>='".$date_from."' and 
                prod_gmt_polies.poly_qc_date<='".$date_to."'
                ) m group by m.sales_order_id,m.poly_qc_date
            ) budgetCm"), [["budgetCm.sales_order_id", "=", "sales_orders.id"],["budgetCm.poly_qc_date", "=", "prod_gmt_polies.poly_qc_date"]])
            ->leftJoin(\DB::raw("(
                select
                m.sales_order_id,
                m.poly_qc_date,
                sum(m.amount) as mkt_cm_amount
                from
                (
				SELECT 
                sales_orders.id as sales_order_id,
                prod_gmt_polies.poly_qc_date,
                prod_gmt_poly_qties.qty*mkt_cost_cms.cm_per_pcs as amount
                FROM prod_gmt_polies
                join prod_gmt_poly_orders on prod_gmt_poly_orders.prod_gmt_poly_id = prod_gmt_polies.id
                join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join jobs on jobs.id = sales_orders.job_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
                join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
                join prod_gmt_poly_qties on prod_gmt_poly_qties.prod_gmt_poly_order_id = prod_gmt_poly_orders.id  and prod_gmt_poly_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
                join mkt_costs on mkt_costs.style_id=style_gmts.style_id
                join mkt_cost_cms on mkt_cost_cms.mkt_cost_id=mkt_costs.id and mkt_cost_cms.style_gmt_id=style_gmts.id
                where prod_gmt_polies.poly_qc_date>='".$date_from."' and 
                prod_gmt_polies.poly_qc_date<='".$date_to."'
                ) m group by m.sales_order_id,m.poly_qc_date
            ) mktCm"), [["mktCm.sales_order_id", "=", "sales_orders.id"],["mktCm.poly_qc_date", "=", "prod_gmt_polies.poly_qc_date"]])
            ->join(\DB::raw('(
                select style_gmts.style_id, 
                sum(style_gmts.gmt_qty)  as item_ratio 
                from style_gmts   
                group by style_gmts.style_id
            ) gmt_item_ratio'), "gmt_item_ratio.style_id", "=", "styles.id")
            ->leftJoin(\DB::raw("(SELECT 
                sales_orders.id,
                avg(sales_order_gmt_color_sizes.rate) as rate
                FROM sales_order_countries
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                group by sales_orders.id
            ) saleorders"), "saleorders.id", "=", "sales_orders.id")
            ->leftJoin(\DB::raw('(select 
                m.sales_order_id,
                avg(m.smv) as smv
                from 
                (
                SELECT 
                sales_orders.id as sales_order_id,
                style_gmts.smv
                FROM sales_orders 
                join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
                join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
                where  1=1) 
                m group by m.sales_order_id
            ) bookedsmv'), "bookedsmv.sales_order_id", "=", "sales_orders.id")
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_polies.poly_qc_date', '>=', $date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_polies.poly_qc_date', '<=', $date_to);
            })
            ->when(request('prod_source_id',0), function ($q) use($prod_source_id){
                return $q->where('prod_gmt_poly_orders.prod_source_id', '=', $prod_source_id);
            })
            /*->when(request('produced_company_id',0), function ($q){
                return $q->where('wstudy_line_setups.company_id', '=', request('produced_company_id',0));
            })*/
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
            ->orderBy('sales_orders.id')
            ->groupBy([
                'prod_gmt_polies.poly_qc_date',
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
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'budgetCm.budget_cm_amount',
                'mktCm.mkt_cm_amount',
                'gmt_item_ratio.item_ratio',
                'saleorders.rate',
                'bookedsmv.smv',
            ])
            //->toSql();
            //dd($dailyproduction);
            ->get()
            ->map(function($dailyproduction) use($date_from,$date_to){
                $cm_amount=$dailyproduction->budget_cm_amount;
                $mkt_cm_amount=$dailyproduction->mkt_cm_amount;
                $dailyproduction->cm_rate=0;
                $dailyproduction->mkt_cm_rate=0;
                if($dailyproduction->poly_qty){
                    $dailyproduction->cm_rate=($dailyproduction->budget_cm_amount/$dailyproduction->poly_qty)*12;
                    $dailyproduction->mkt_cm_rate=($dailyproduction->mkt_cm_amount/$dailyproduction->poly_qty)*12;
                   // $cm_amount=($dailyproduction->cm_rate/12)*($dailyproduction->poly_qty/$dailyproduction->item_ratio);
                    //$mkt_cm_amount=($dailyproduction->mkt_cm_rate/12)*($dailyproduction->poly_qty/$dailyproduction->item_ratio);
                }
                $dailyproduction->prod_hour=($dailyproduction->poly_qty*$dailyproduction->smv)/60;
                $dailyproduction->ship_date=date('d-M-Y',strtotime($dailyproduction->ship_date));
                $dailyproduction->production_date = date('d-M-Y',strtotime($dailyproduction->production_date));
                $dailyproduction->cut_qty=number_format(0,0);
                $dailyproduction->print_qty=number_format(0,0);
                $dailyproduction->emb_qty=number_format(0,0);
                $dailyproduction->finishing_qty=number_format(0,0);
                $dailyproduction->finishing_amount=number_format($dailyproduction->poly_qty*$dailyproduction->rate,0);
                $dailyproduction->poly_qty=number_format($dailyproduction->poly_qty,0);
                $dailyproduction->iron_qty=number_format(0,0);
                $dailyproduction->sew_qty=number_format(0,0);
                $dailyproduction->cm_amount=number_format($cm_amount,2);
                $dailyproduction->cm_rate=number_format($dailyproduction->cm_rate,4);
                $dailyproduction->mkt_cm_amount=number_format($mkt_cm_amount,2);
                $dailyproduction->mkt_cm_rate=number_format($dailyproduction->mkt_cm_rate,4);
                $dailyproduction->prod_hour=number_format($dailyproduction->prod_hour,0);
                return $dailyproduction;
            });
            echo json_encode($dailyproduction);
        }
        if ($production_area_id==70) {
            $dailyproduction=$this->prodgmtcarton
            ->selectRaw('
                prod_gmt_carton_entries.carton_date as production_date,
                sales_orders.id as sales_order_id,
                sales_orders.produced_company_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                jobs.job_no,
                styles.id as style_id,
                styles.style_ref,
                styles.flie_src,
                users.id as user_id,
                users.name as dl_marchent,
                buyers.name as buyer_name,
                bcompany.code as company_code,
                produced_company.code as pcompany_code,
                bookedsmv.smv,
                budgetCm.budget_cm_amount,
                gmt_item_ratio.item_ratio,
                mktCm.mkt_cm_amount,
                saleorders.rate as order_rate,
                saleorders.qty as order_qty,
                saleorders.amount as order_amount,
                sum(style_pkg_ratios.qty) as finishing_qty
                
            ')/* ,
            avg(budget_cms.amount) as cm_amount */
            ->join('prod_gmt_carton_details', function($join) use($date_to) {
                $join->on('prod_gmt_carton_details.prod_gmt_carton_entry_id', '=', 'prod_gmt_carton_entries.id');
            })
            ->join('sales_order_countries', function($join) use($date_to) {
                $join->on('sales_order_countries.id', '=', 'prod_gmt_carton_details.sales_order_country_id');
            })
            ->join('sales_orders',function($join){
                $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
            }) 
            ->join('style_pkgs', function($join) use($date_to) {
                $join->on('style_pkgs.id', '=', 'prod_gmt_carton_details.style_pkg_id');
            })
            ->join('style_pkg_ratios', function($join) {
                $join->on('style_pkg_ratios.style_pkg_id', '=', 'style_pkgs.id');
            })
            ->leftJoin(\DB::raw('(select 
                m.sales_order_id,
                avg(m.smv) as smv
                from 
                (
                SELECT 
                sales_orders.id as sales_order_id,
                style_gmts.smv
                FROM sales_orders 
                join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
                join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
                where  1=1
                ) m 
                group by 
                m.sales_order_id
            ) bookedsmv'), "bookedsmv.sales_order_id", "=", "sales_orders.id")
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
                where prod_gmt_carton_entries.carton_date>='".$date_from."' and 
                prod_gmt_carton_entries.carton_date<='".$date_to."'
                group by sales_orders.id,orders.qty,orders.rate,orders.amount,orders.plan_cut_qty
                ) saleorders"), "saleorders.id", "=", "sales_orders.id")
            ->leftJoin(\DB::raw("(SELECT 
                sales_orders.id as sale_order_id,
                count(prod_gmt_carton_details.qty) as qty 
                FROM prod_gmt_carton_entries
                join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
                join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                where prod_gmt_carton_entries.carton_date>='".$date_from."' and 
                    prod_gmt_carton_entries.carton_date<='".$date_to."'
                group by sales_orders.id) carton"), "carton.sale_order_id", "=", "sales_orders.id")
            ->leftJoin('companies as produced_company', function($join)  {
                $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
            })     
            ->join('jobs', function($join)  {
                $join->on('jobs.id', '=', 'sales_orders.job_id');
            })
            ->join('companies as bcompany', function($join)  {
                $join->on('bcompany.id', '=', 'jobs.company_id');
            })
            ->join('styles', function($join)  {
                $join->on('styles.id', '=', 'jobs.style_id');
            })
            /* ->leftJoin('budgets', function($join)  {
                $join->on('jobs.id', '=', 'budgets.job_id');
            })
            ->leftJoin('budget_cms', function($join)  {
                $join->on('budgets.id', '=', 'budget_cms.budget_id');
            }) */
            ->leftJoin(\DB::raw("(select
                m.sales_order_id,
                m.carton_date,
                sum(m.amount) as budget_cm_amount
                from
                (
                select
                sales_orders.id as sales_order_id,
                prod_gmt_carton_entries.carton_date,
                style_pkg_ratios.qty*budget_cms.cm_per_pcs as amount
                from
                prod_gmt_carton_entries
                join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id=prod_gmt_carton_entries.id
                join sales_order_countries on prod_gmt_carton_details.sales_order_country_id=sales_order_countries.id
                join sales_orders on sales_order_countries.sale_order_id=sales_orders.id
                join style_pkgs on prod_gmt_carton_details.style_pkg_id=style_pkgs.id
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id=style_pkgs.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id=style_pkg_ratios.style_gmt_color_size_id
                join style_gmts on style_gmts.id=style_gmt_color_sizes.style_gmt_id
                join budget_cms on budget_cms.style_gmt_id=style_gmts.id
                join budgets on budgets.id=budget_cms.budget_id
                where prod_gmt_carton_entries.carton_date>='".$date_from."' and 
                 prod_gmt_carton_entries.carton_date<='".$date_to."'
                 ) m group by m.sales_order_id,m.carton_date) budgetCm"), [["budgetCm.sales_order_id", "=", "sales_orders.id"],["budgetCm.carton_date", "=", "prod_gmt_carton_entries.carton_date"]])
             ->leftJoin(\DB::raw("(
				select
                m.sales_order_id,
                m.carton_date,
                sum(m.amount) as mkt_cm_amount
                from
                (
                select
                sales_orders.id as sales_order_id,
                prod_gmt_carton_entries.carton_date,
                style_pkg_ratios.qty*mkt_cost_cms.cm_per_pcs as amount
                from
                prod_gmt_carton_entries
                join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id=prod_gmt_carton_entries.id
                join sales_order_countries on prod_gmt_carton_details.sales_order_country_id=sales_order_countries.id
                join sales_orders on sales_order_countries.sale_order_id=sales_orders.id
                join style_pkgs on prod_gmt_carton_details.style_pkg_id=style_pkgs.id
                join style_pkg_ratios on style_pkg_ratios.style_pkg_id=style_pkgs.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id=style_pkg_ratios.style_gmt_color_size_id
                join style_gmts on style_gmts.id=style_gmt_color_sizes.style_gmt_id
                join mkt_cost_cms on mkt_cost_cms.style_gmt_id=style_gmts.id
                join mkt_costs on mkt_costs.id=mkt_cost_cms.mkt_cost_id
                where prod_gmt_carton_entries.carton_date>='".$date_from."' and 
                 prod_gmt_carton_entries.carton_date<='".$date_to."'
                 ) m group by m.sales_order_id,m.carton_date
            ) mktCm"), [["mktCm.sales_order_id", "=", "sales_orders.id"],["mktCm.carton_date", "=", "prod_gmt_carton_entries.carton_date"]])
            ->join(\DB::raw('(
                select style_gmts.style_id, 
                sum(style_gmts.gmt_qty)  as item_ratio 
                from style_gmts   
                group by style_gmts.style_id
            ) gmt_item_ratio'), "gmt_item_ratio.style_id", "=", "styles.id")
            ->leftJoin('teammembers', function($join)  {
                $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
            }) 
            ->leftJoin('users', function($join)  {
                $join->on('users.id', '=', 'teammembers.user_id');
            })
            ->leftJoin('buyers', function($join)  {
                $join->on('buyers.id', '=', 'styles.buyer_id');
            })
            ->when(request('date_from'), function ($q) use($date_from) {
                return $q->where('prod_gmt_carton_entries.carton_date', '>=',$date_from);
            })
            ->when(request('date_to'), function ($q) use($date_to) {
                return $q->where('prod_gmt_carton_entries.carton_date', '<=',$date_to );
            })
            ->when(request('produced_company_id'), function ($q) use($produced_company_id) {
                return $q->where('sales_orders.produced_company_id', '=', $produced_company_id);
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
            ->when(request('sale_order_no'), function ($q) {
                return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
            })
            ->orderBY('sales_orders.id')
            ->groupBy([
                'prod_gmt_carton_entries.carton_date',
                'sales_orders.id',
                'sales_orders.produced_company_id',
                'sales_orders.sale_order_no',
                'sales_orders.ship_date',
                'jobs.job_no',
                'styles.id',
                'styles.style_ref',
                'styles.flie_src',
                'users.id',
                'users.name',
                'buyers.name',
                'bcompany.code',
                'produced_company.code',
                'bookedsmv.smv',
                'budgetCm.budget_cm_amount',
                'mktCm.mkt_cm_amount',
                'gmt_item_ratio.item_ratio',
                'saleorders.rate',
                'saleorders.qty',
                'saleorders.amount'
            ])
            ->get()
            ->map(function($dailyproduction) use($date_from,$date_to){
                $cm_amount=$dailyproduction->budget_cm_amount;
                $mkt_cm_amount=$dailyproduction->mkt_cm_amount;
                $dailyproduction->cm_rate=0;
                $dailyproduction->mkt_cm_rate=0;
                if($dailyproduction->finishing_qty){
                    $dailyproduction->cm_rate=($cm_amount/$dailyproduction->finishing_qty)*12;
                    $dailyproduction->mkt_cm_rate=($mkt_cm_amount/$dailyproduction->finishing_qty)*12;
                    /*$cm_amount=($dailyproduction->cm_rate/12)*($dailyproduction->finishing_qty/$dailyproduction->item_ratio);
                    $mkt_cm_amount=($dailyproduction->mkt_cm_rate/12)*($dailyproduction->finishing_qty/$dailyproduction->item_ratio);*/
                }
                $dailyproduction->prod_hour=($dailyproduction->finishing_qty*$dailyproduction->smv)/60;
                $dailyproduction->ship_date=date('d-M-Y',strtotime($dailyproduction->ship_date));
                $dailyproduction->production_date = date('d-M-Y',strtotime($dailyproduction->production_date));
                $finishing_amount=$dailyproduction->finishing_qty*$dailyproduction->order_rate;
                $dailyproduction->cut_qty=number_format(0,0);
                $dailyproduction->print_qty=number_format(0,0);
                $dailyproduction->emb_qty=number_format(0,0);
                $dailyproduction->finishing_amount=number_format($finishing_amount,2);
                $dailyproduction->cm_amount=number_format($cm_amount,2);
                $dailyproduction->cm_rate=number_format($dailyproduction->cm_rate,4);
                $dailyproduction->mkt_cm_amount=number_format($mkt_cm_amount,2);
                $dailyproduction->mkt_cm_rate=number_format($dailyproduction->mkt_cm_rate,4);
                $dailyproduction->finishing_qty=number_format($dailyproduction->finishing_qty,0);
                $dailyproduction->sew_qty=number_format(0,0);
                $dailyproduction->iron_qty=number_format(0,0);
                $dailyproduction->poly_qty=number_format(0,0);
                $dailyproduction->prod_hour=number_format($dailyproduction->prod_hour,0);
                return $dailyproduction;
            });
            echo json_encode($dailyproduction);
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
          
            /*  ->when(request('orderstage_id'), function ($q) {
            return $q->where('style_samples.approval_priority', '=',request('orderstage_id', 0));
             }) */
        ->where([['style_id','=',request('style_id',0)]])
        ->get([
            'styles.id as style_id',
            'styles.style_ref',
            'style_file_uploads.*'
        ]);
        echo json_encode($filesrc); 
    }
}
