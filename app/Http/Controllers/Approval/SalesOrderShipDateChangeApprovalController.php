<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Sales\SalesOrderShipDateChangeRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Sales\ProjectionRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Library\Sms;
use Illuminate\Support\Carbon;

class SalesOrderShipDateChangeApprovalController extends Controller
{
    private $salesordershipdatechange;
    private $salesorder;
    private $projection;
    private $job;
    private $salesordercountry;
    private $buyernature;
    private $style;

    public function __construct(
        SalesOrderShipDateChangeRepository $salesordershipdatechange,
        SalesOrderRepository $salesorder, 
        JobRepository $job,
        StyleRepository $style,
        ProjectionRepository $projection,
        CompanyRepository $company,
        BuyerNatureRepository $buyernature,
        SalesOrderCountryRepository $salesordercountry

    ) {
        $this->salesordershipdatechange = $salesordershipdatechange;
        $this->salesorder = $salesorder;
        $this->projection = $projection;
        $this->buyernature = $buyernature;
        $this->company = $company;
        $this->style=$style;
        $this->job = $job;
        $this->salesordercountry = $salesordercountry;
        $this->middleware('auth');
       // $this->middleware('permission:approve.salesordershipdatechangeapproval',   ['only' => ['approved', 'index','reportData']]);

    }
    public function index() {
        $job=array_prepend(array_pluck($this->job->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.SalesOrderShipDateChangeApproval',['job'=>$job,'company'=>$company]);
    }

    public function reportData() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-','');
        $rows = $this->salesordershipdatechange
        ->selectRaw('
            sales_order_ship_date_changes.id,
            sales_order_ship_date_changes.ship_date,
            sales_order_ship_date_changes.remarks,
            sales_order_ship_date_changes.old_ship_date,
            sales_orders.id  as sale_order_id,
            sales_orders.sale_order_no,
            jobs.job_no,
            styles.style_ref,
            buyers.code as buyer_code,
            sales_orders.place_date,
            sales_orders.receive_date,
            sales_orders.org_ship_date,
            sales_orders.file_no,
            sales_orders.tna_to,
            sales_orders.tna_from,
            sales_orders.order_status,
            sales_orders.produced_company_id,
            sum(sales_order_gmt_color_sizes.qty) as qty,
            sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
            avg(sales_order_gmt_color_sizes.rate) as rate,
            sum(sales_order_gmt_color_sizes.amount) as amount
        ')
        ->join('sales_orders', function($join) {
            $join->on('sales_orders.id', '=', 'sales_order_ship_date_changes.sale_order_id');
        })
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
        })
        ->join('companies', function($join) {
            $join->on('companies.id', '=', 'sales_orders.produced_company_id');
        })
        ->join('jobs', function($join) {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('styles', function($join) {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('buyers', function($join) {
            $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('employee_h_r_statuses.status_date','>=',request('date_from', 0));
        })   
        ->when(request('date_to'), function ($q) {
            return $q->where('employee_h_r_statuses.status_date','=',request('date_to', 0));
        })
        ->orderBy('sales_order_ship_date_changes.id','desc')
        ->whereNull('sales_order_ship_date_changes.approved_at')
        ->whereNull('sales_order_ship_date_changes.approved_by')
        ->groupBy([
            'sales_order_ship_date_changes.id',
            'sales_order_ship_date_changes.ship_date',
            'sales_order_ship_date_changes.remarks',
            'sales_order_ship_date_changes.old_ship_date',
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'jobs.job_no',
            'styles.style_ref',
            'buyers.code',
            'sales_orders.place_date',
            'sales_orders.receive_date',
            'sales_orders.org_ship_date',
            'sales_orders.file_no',
            'sales_orders.tna_to',
            'sales_orders.tna_from',
            'sales_orders.order_status',
            'sales_orders.produced_company_id',
        ])
        ->get()
        ->map(function($rows) use($status,$company){
            $receive_date = Carbon::parse($rows->receive_date);
            $ship_date = Carbon::parse($rows->ship_date);
            $diff = $receive_date->diffInDays($ship_date);
            if($diff >1){
            $diff.=" Days";
            }else{
            $diff.=" Day";
            }
            $rows->lead_time=$diff;
            $rows->produced_company=isset($rows->produced_company_id)?$company[$rows->produced_company_id]:'';
            $rows->order_status=isset($status[$rows->order_status])?$status[$rows->order_status]:'';
            $rows->ship_date=date("d-M-Y",strtotime($rows->ship_date));
            $rows->old_ship_date=date("d-M-Y",strtotime($rows->old_ship_date));
            $rows->org_ship_date=date("d-M-Y",strtotime($rows->org_ship_date));
            $rows->place_date=date("d-M-Y",strtotime($rows->place_date));
            $rows->receive_date=date("d-M-Y",strtotime($rows->receive_date));
            return $rows;
        });
        echo json_encode($rows);
    }

    public function approved (Request $request)
    {
        $id=request('id',0);
        $master=$this->salesordershipdatechange->find($id);
        $country=$this->salesordercountry->where([['sale_order_id','=',$master->sale_order_id]])->get();
         $arr=[];
        foreach($country as $c){
            $arr[$c->id]=$c->id;
        }
       // dd($country->id);die;
        $user = \Auth::user();
        $approved_at=date('Y-m-d h:i:s');
        $master->approved_by=$user->id;
        $master->approved_at=$approved_at;
        $master->timestamps=false;
        \DB::beginTransaction();
        try
        {
            $salesordershipdatechange=$master->save();
            $order=$this->salesorder->find($master->sale_order_id);
            //$remarks=$order->remarks." ".$master->remarks;
            $salesorder=$this->salesorder->update($master->sale_order_id,[
                'ship_date'=>$master->ship_date,
              //  'remarks'=>$remarks,
            ]);
            
            $salesordercountry=$this->salesordercountry
            ->where([['sale_order_id','=',$master->sale_order_id]])
            ->update([
                'country_ship_date'=>$master->ship_date,
            ]);

        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        
        

        if($salesordercountry){
        return response()->json(array('success' => true,  'message' => 'Approved Successfully',), 200);
        }
    }

    private function getData()
    {
        $sale_order_id=request('sale_order_id', 0);
        $saleorderid=null;
        if($sale_order_id){
            $saleorderid=" and sales_orders.id = $sale_order_id ";
        }

        $buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);

        


        $rows=$this->salesorder
        ->selectRaw(
        'styles.id as style_id,
        styles.style_ref,
        styles.flie_src,
        styles.buying_agent_id,
        styles.contact,
        companies.name as company_code,
        produced_company.name as produced_company_code,
        buyers.name as buyer_name,
        sales_orders.id as sale_order_id,
        sales_orders.sale_order_no,
        sales_orders.ship_date,
        sales_orders.org_ship_date,
        sales_orders.receive_date as sale_order_receive_date,
        sales_orders.internal_ref,
        sales_orders.order_status,
        sum(sales_order_gmt_color_sizes.qty) as qty,
        sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty,
        avg(sales_order_gmt_color_sizes.rate) as rate,
        sum(sales_order_gmt_color_sizes.amount) as amount,
        bookedsmv.smv,
        bookedsmv.booked_minute,
        bookedsmv.sewing_effi_per,
        teamleadernames.name as team_name,
        users.name as team_member_name,
        exfactory.qty as ship_qty,
        exfactory.max_exfactory_date,
        yarnrq.yarn_req,
        yarnrcv.yarn_rcv,
        poyarnlc.qty as poyarnlc_qty,
        inhyarnisu.inh_yarn_isu_qty,
        outyarnisu.out_yarn_isu_qty,
        inhyarnisurtn.qty as inh_yarn_isu_rtn_qty,
        outyarnisurtn.qty as out_yarn_isu_rtn_qty,
        prodknit.knit_qty,
        prodknit.prod_knit_qty,
        prodbatch.batch_qty,
        proddyeing.dyeing_qty,
        finfabrq.fin_fab_req,
        prodfinish.finish_qty,
        prodcut.cut_qty,
        prodscrreq.req_scr_qty,
        prodscrrcv.rcv_scr_qty,
        prodembreq.req_emb_qty,
        prodembrcv.rcv_emb_qty,
        prodsewline.sew_line_qty,
        prodsew.sew_qty,
        prodiron.iron_qty,
        prodpoly.poly_qty,
        carton.car_qty,
        inspec.insp_pass_qty
        ')
        ->join('sales_order_ship_date_changes', function($join) {
            $join->on('sales_orders.id', '=', 'sales_order_ship_date_changes.sale_order_id');
        })
        ->join('jobs', function($join)  {
            $join->on('sales_orders.job_id', '=', 'jobs.id');
        })
        ->join('styles', function($join)  {
            $join->on('jobs.style_id', '=', 'styles.id');
        })
        ->join('buyers', function($join)  {
            $join->on('styles.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('uoms', function($join)  {
            $join->on('styles.uom_id', '=', 'uoms.id');
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
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->leftJoin('companies as produced_company', function($join)  {
            $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
        })
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
        })
        ->leftJoin(\DB::raw("(
            select 
            m.sales_order_id,
            avg(m.smv) as smv,
            avg(m.sewing_effi_per) as sewing_effi_per,
            sum(m.booked_minute) as booked_minute
            from 
            (
            SELECT 
            sales_orders.id as sales_order_id,
            style_gmts.smv,
            style_gmts.sewing_effi_per,
            sales_order_gmt_color_sizes.qty as qty,
            sales_order_gmt_color_sizes.qty * style_gmts.smv as booked_minute
            FROM sales_orders 
            join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
            join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id
            where sales_orders.order_status !=2 $saleorderid
            ) m group by m.sales_order_id) 
        bookedsmv"), "bookedsmv.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(SELECT 
        sales_orders.id as sales_order_id,
        sum(budget_fabric_cons.grey_fab) as yarn_req,
        sum(budget_fabric_cons.fin_fab) as fin_fab_req
        FROM sales_orders 
        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
        join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
        join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
        join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id

        join jobs on jobs.id = sales_orders.job_id 
        join styles on styles.id = jobs.style_id 
        where 
        style_fabrications.material_source_id !=1 
        and sales_orders.order_status !=2 $saleorderid
        group by sales_orders.id) yarnrq"), "yarnrq.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(SELECT 
        sales_orders.id as sales_order_id,
        sum(budget_fabric_cons.grey_fab) as yarn_req,
        sum(budget_fabric_cons.fin_fab) as fin_fab_req
        FROM sales_orders 
        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
        join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id

        join jobs on jobs.id = sales_orders.job_id 
        join styles on styles.id = jobs.style_id 
        where sales_orders.order_status !=2  $saleorderid
        group by sales_orders.id) finfabrq"), "finfabrq.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(select 
        po_yarn_item_bom_qties.sale_order_id as sales_order_id,
        sum(inv_yarn_rcv_item_sos.qty) yarn_rcv
        from
        po_yarn_item_bom_qties
        join inv_yarn_rcv_item_sos on inv_yarn_rcv_item_sos.po_yarn_item_bom_qty_id=po_yarn_item_bom_qties.id
        where po_yarn_item_bom_qties.deleted_at is null 
        and  inv_yarn_rcv_item_sos.deleted_at is null
        group by po_yarn_item_bom_qties.sale_order_id
        ) yarnrcv"), "yarnrcv.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(SELECT 
        sales_orders.id as sales_order_id,
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
        where   inv_isus.isu_against_id=102 
        and   inv_isus.isu_basis_id=1 
        and inv_yarn_isu_items.deleted_at is null $saleorderid 
        group by sales_orders.id) inhyarnisu"), "inhyarnisu.sales_order_id", "=", "sales_orders.id")
        ->leftJoin(\DB::raw("(
        select 
        inv_yarn_rcv_items.sales_order_id,
        sum(inv_yarn_transactions.store_qty) as qty,
        sum(inv_yarn_transactions.store_amount) as amount
        from 
        sales_orders
        join inv_yarn_rcv_items on inv_yarn_rcv_items.sales_order_id=sales_orders.id
        join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
        join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
        join suppliers on suppliers.id = inv_rcvs.return_from_id
        join companies on companies.id = suppliers.company_id
        join jobs on jobs.id = sales_orders.job_id 
        join styles on styles.id = jobs.style_id 
        where inv_rcvs.receive_basis_id=4
        and inv_yarn_transactions.deleted_at is null
        and inv_yarn_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_yarn_transactions.trans_type_id=1  $saleorderid 
        group by 
        inv_yarn_rcv_items.sales_order_id) inhyarnisurtn"), "inhyarnisurtn.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(SELECT 
        sales_orders.id as sales_order_id,
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
        where   inv_isus.isu_against_id=102 
        and  inv_isus.isu_basis_id=1 
        and inv_yarn_isu_items.deleted_at is null $saleorderid
        group by sales_orders.id) outyarnisu"), "outyarnisu.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
        select 
        inv_yarn_rcv_items.sales_order_id,
        sum(inv_yarn_transactions.store_qty) as qty,
        sum(inv_yarn_transactions.store_amount) as amount
        from 
        sales_orders
        join inv_yarn_rcv_items on inv_yarn_rcv_items.sales_order_id=sales_orders.id
        join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
        join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
        join suppliers on suppliers.id = inv_rcvs.return_from_id
        and (suppliers.company_id is null or  suppliers.company_id=0)
        --join companies on companies.id = suppliers.company_id
        join companies on companies.id = inv_rcvs.company_id
        join jobs on jobs.id = sales_orders.job_id 
        join styles on styles.id = jobs.style_id 
        where inv_rcvs.receive_basis_id=4
        and inv_yarn_transactions.deleted_at is null
        and inv_yarn_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_yarn_transactions.trans_type_id=1  $saleorderid 
        group by 
        inv_yarn_rcv_items.sales_order_id) outyarnisurtn"), "outyarnisurtn.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
        select
        m.sales_order_id,
        sum(m.qc_pass_qty) as knit_qty,
        sum(m.roll_weight) as prod_knit_qty
        from 
        (
        select
        prod_knit_items.pl_knit_item_id,
        prod_knit_items.po_knit_service_item_qty_id,
        prod_knit_item_rolls.roll_weight,
        prod_knit_qcs.reject_qty,   
        prod_knit_qcs.qc_pass_qty,
        CASE 
        WHEN  inhprods.sales_order_id IS NULL THEN outprods.sales_order_id 
        ELSE inhprods.sales_order_id
        END as sales_order_id
        from
        prod_knits
        join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
        join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
        left join prod_knit_rcv_by_qcs on prod_knit_item_rolls.id=prod_knit_rcv_by_qcs.prod_knit_item_roll_id
        left join prod_knit_qcs on prod_knit_qcs.prod_knit_rcv_by_qc_id=prod_knit_rcv_by_qcs.id

        left join (
        select 
        pl_knit_items.id as pl_knit_item_id,
        sales_orders.id as sales_order_id
        from 
        sales_orders
        join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
        join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
        and po_knit_service_items.deleted_at is null
        join so_knit_po_items on so_knit_po_items.po_knit_service_item_qty_id=po_knit_service_item_qties.id
        join so_knit_refs on so_knit_refs.id=so_knit_po_items.so_knit_ref_id
        join pl_knit_items on pl_knit_items.so_knit_ref_id=so_knit_refs.id
        join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id

        ) inhprods on inhprods.pl_knit_item_id=prod_knit_items.pl_knit_item_id

        left join (
        select 
        po_knit_service_item_qties.id as po_knit_service_item_qty_id,
        sales_orders.id as sales_order_id
        from 
        sales_orders
        join po_knit_service_item_qties on sales_orders.id=po_knit_service_item_qties.sales_order_id
        join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
        join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
        ) outprods on outprods.po_knit_service_item_qty_id=prod_knit_items.po_knit_service_item_qty_id
        ) m group by  m.sales_order_id) prodknit"
        ), "prodknit.sales_order_id", "=", "sales_orders.id")


        ->leftJoin(\DB::raw("(
        select 
        sales_orders.id as sales_order_id,
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
        where 
        prod_batches.batch_for=1 and
        prod_batches.is_redyeing=0 and 
        prod_batches.deleted_at is null and 
        prod_batch_rolls.deleted_at is null 
        $saleorderid
        group by
        sales_orders.id
        ) prodbatch"), "prodbatch.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
        select 
        sales_orders.id as sales_order_id,
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
        where 
        prod_batches.batch_for=1 and
        prod_batches.is_redyeing=0 and 
        prod_batches.deleted_at is null and 
        prod_batch_rolls.deleted_at is null  and
        prod_batches.unloaded_at is not null 
        $saleorderid
        group by
        sales_orders.id
        ) proddyeing"), "proddyeing.sales_order_id", "=", "sales_orders.id")


        ->leftJoin(\DB::raw("(
        select 
        sales_orders.id as sales_order_id,
        sum(prod_batch_finish_qc_rolls.qty) as finish_qty
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
        where 
        prod_batches.batch_for=1 and
        prod_batches.is_redyeing=0 and 
        prod_batches.deleted_at is null and 
        prod_batch_rolls.deleted_at is null  and
        prod_batches.unloaded_at is not null 
        $saleorderid
        group by
        sales_orders.id
        ) prodfinish"), "prodfinish.sales_order_id", "=", "sales_orders.id")


        ->leftJoin(\DB::raw("(
        SELECT 
        sales_orders.id as sales_order_id,
        sum(prod_gmt_cutting_qties.qty) as cut_qty,
        min(prod_gmt_cuttings.cut_qc_date) as min_cut_qc_date
        FROM prod_gmt_cuttings
        join prod_gmt_cutting_orders on prod_gmt_cutting_orders.prod_gmt_cutting_id = prod_gmt_cuttings.id
        join sales_order_countries on sales_order_countries.id = prod_gmt_cutting_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join prod_gmt_cutting_qties on prod_gmt_cutting_qties.prod_gmt_cutting_order_id = prod_gmt_cutting_orders.id
        group by 
        sales_orders.id) prodcut"), "prodcut.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
        select 
        sales_order_gmt_color_sizes.sale_order_id as sales_order_id,
        sum(budget_emb_cons.req_cons) as req_scr_qty
        from budget_emb_cons 
        join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
        join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
        join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
        left join embelishments on embelishments.id=style_embelishments.embelishment_id
        join production_processes on production_processes.id=embelishments.production_process_id
        where production_processes.production_area_id =45
        group by sales_order_gmt_color_sizes.sale_order_id) prodscrreq"), "prodscrreq.sales_order_id", "=", "sales_orders.id")


        ->leftJoin(\DB::raw("(
        SELECT 
        sales_orders.id as sales_order_id,
        sum(prod_gmt_print_rcv_qties.qty) as rcv_scr_qty
        FROM prod_gmt_print_rcvs
        join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id = prod_gmt_print_rcvs.id
        join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id
        group by 
        sales_orders.id) prodscrrcv"), "prodscrrcv.sales_order_id", "=", "sales_orders.id")


        ->leftJoin(\DB::raw("(
            select 
            sales_order_gmt_color_sizes.sale_order_id as sales_order_id,
            sum(budget_emb_cons.req_cons) as req_emb_qty
            from budget_emb_cons 
            left join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_emb_cons.sales_order_gmt_color_size_id
            join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
            left join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
            left join embelishments on embelishments.id=style_embelishments.embelishment_id
            left join production_processes on production_processes.id=embelishments.production_process_id
            where production_processes.production_area_id = 50
            group by sales_order_gmt_color_sizes.sale_order_id) prodembreq"), "prodembreq.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
            SELECT 
            sales_orders.id as sales_order_id,
            sum(prod_gmt_emb_rcv_qties.qty) as rcv_emb_qty
            FROM prod_gmt_emb_rcvs
            join prod_gmt_emb_rcv_orders on prod_gmt_emb_rcv_orders.prod_gmt_emb_rcv_id = prod_gmt_emb_rcvs.id
            join sales_order_countries on sales_order_countries.id = prod_gmt_emb_rcv_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join prod_gmt_emb_rcv_qties on prod_gmt_emb_rcv_qties.prod_gmt_emb_rcv_order_id = prod_gmt_emb_rcv_orders.id
            group by 
            sales_orders.id) prodembrcv"), "prodembrcv.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
        SELECT 
        sales_orders.id as sales_order_id,
        sum(prod_gmt_sewing_line_qties.qty) as sew_line_qty
        FROM prod_gmt_sewing_lines
        join prod_gmt_sewing_line_orders on prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id = prod_gmt_sewing_lines.id
        join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_line_orders.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join prod_gmt_sewing_line_qties on prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id = prod_gmt_sewing_line_orders.id
        group by 
        sales_orders.id) prodsewline"), "prodsewline.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
            SELECT 
            sales_orders.id as sales_order_id,
            sum(prod_gmt_iron_qties.qty) as iron_qty
            FROM prod_gmt_irons
            join prod_gmt_iron_orders on prod_gmt_iron_orders.prod_gmt_iron_id = prod_gmt_irons.id
            join sales_order_countries on sales_order_countries.id = prod_gmt_iron_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join prod_gmt_iron_qties on prod_gmt_iron_qties.prod_gmt_iron_order_id = prod_gmt_iron_orders.id
            group by 
            sales_orders.id) prodiron"), "prodiron.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
            SELECT 
            sales_orders.id as sales_order_id,
            sum(prod_gmt_poly_qties.qty) as poly_qty
            FROM prod_gmt_polies
            join prod_gmt_poly_orders on prod_gmt_poly_orders.prod_gmt_poly_id = prod_gmt_polies.id
            join sales_order_countries on sales_order_countries.id = prod_gmt_poly_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join prod_gmt_poly_qties on prod_gmt_poly_qties.prod_gmt_poly_order_id = prod_gmt_poly_orders.id
            group by 
            sales_orders.id) prodpoly"), "prodpoly.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
            SELECT 
            sales_orders.id as sales_order_id,
            sum(prod_gmt_sewing_qties.qty) as sew_qty
            FROM prod_gmt_sewings
            join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
            join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
            join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
            join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id
            where prod_gmt_sewing_qties.deleted_at is null
            group by 
            sales_orders.id) prodsew"), "prodsew.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
        SELECT 
        sales_orders.id as sales_order_id,
        sum(style_pkg_ratios.qty) as car_qty 
        FROM prod_gmt_carton_entries
        join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
        join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
        join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
        join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        group by sales_orders.id) carton"), "carton.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
        select 
        sales_orders.id as sales_order_id,
        sum(prod_gmt_inspection_orders.qty) as insp_pass_qty,
        sum(prod_gmt_inspection_orders.re_check_qty) as insp_re_check_qty,
        sum(prod_gmt_inspection_orders.failed_qty) as insp_faild_qty
        from
        prod_gmt_inspections
        join prod_gmt_inspection_orders on prod_gmt_inspection_orders.prod_gmt_inspection_id=prod_gmt_inspections.id
        join sales_order_countries on sales_order_countries.id=prod_gmt_inspections.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        group by
        sales_orders.id) inspec"), "inspec.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
            SELECT sales_orders.id as sale_order_id,
            sum(style_pkg_ratios.qty) as qty,
            max(prod_gmt_ex_factories.exfactory_date) as max_exfactory_date

            FROM sales_orders  
            join jobs on jobs.id = sales_orders.job_id 
            join styles on styles.id = jobs.style_id 
            join style_pkgs on style_pkgs.style_id = styles.id 
            join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
            join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
            join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
            and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id 
            join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id
            join prod_gmt_ex_factories on prod_gmt_ex_factories.id = prod_gmt_ex_factory_qties.prod_gmt_ex_factory_id 
            where prod_gmt_ex_factory_qties.deleted_at is null 
            and prod_gmt_carton_details.deleted_at is null
            $saleorderid
            group by sales_orders.id) exfactory"), "exfactory.sale_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(
        select 
        sales_orders.id as sales_order_id,
        sum(exp_invoice_orders.qty) as ci_qty, 
        sum(exp_invoice_orders.amount) as ci_amount 
        from
        sales_orders 
        join exp_pi_orders on exp_pi_orders.sales_order_id=sales_orders.id
        join exp_invoice_orders on exp_invoice_orders.exp_pi_order_id = exp_pi_orders.id 
        join exp_invoices on exp_invoices.id=exp_invoice_orders.exp_invoice_id
        where exp_invoices.invoice_status_id=2
        and exp_invoice_orders.deleted_at is null
        group by sales_orders.id) ci"), "ci.sales_order_id", "=", "sales_orders.id")

        ->leftJoin(\DB::raw("(select
            po_yarn_item_bom_qties.sale_order_id as sales_order_id,
            sum(po_yarn_item_bom_qties.qty) as qty
            from
            sales_orders
            join po_yarn_item_bom_qties on sales_orders.id=po_yarn_item_bom_qties.sale_order_id
            join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
            join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
            join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
            join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id

            join jobs on jobs.id = sales_orders.job_id 
            join styles on styles.id = jobs.style_id
            where imp_lcs.menu_id=3 $saleorderid
            and po_yarn_item_bom_qties.deleted_at is null
            and po_yarn_items.deleted_at is null
            and po_yarns.deleted_at is null
            and imp_lc_pos.deleted_at is null
            and imp_lcs.deleted_at is null
            group by
            po_yarn_item_bom_qties.sale_order_id) poyarnlc"), "poyarnlc.sales_order_id", "=", "sales_orders.id")
        ->where([['sales_orders.id','=', $sale_order_id]])
        ->where([['sales_orders.order_status','!=',2]])
        //->toSql();
        ->groupBy([
            'styles.id',
            'styles.style_ref',
            'styles.flie_src',
            'styles.buying_agent_id',
            'styles.contact',
            'companies.name',
            'produced_company.name',
            'buyers.name',
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'sales_orders.org_ship_date',
            'sales_orders.receive_date',
            'sales_orders.internal_ref',
            'sales_orders.order_status',
            'bookedsmv.smv',
            'bookedsmv.booked_minute',
            'bookedsmv.sewing_effi_per',
            'teamleadernames.name',
            'users.name',
            'exfactory.qty',
            'exfactory.max_exfactory_date',
            'yarnrq.yarn_req',
            'yarnrcv.yarn_rcv',
            'poyarnlc.qty',
            'inhyarnisu.inh_yarn_isu_qty',
            'outyarnisu.out_yarn_isu_qty',
            'inhyarnisurtn.qty',
            'outyarnisurtn.qty',
            'prodknit.knit_qty',
            'prodknit.prod_knit_qty',
            'prodbatch.batch_qty',
            'proddyeing.dyeing_qty',
            'finfabrq.fin_fab_req',
            'prodfinish.finish_qty',
            'prodcut.cut_qty',
            'prodscrreq.req_scr_qty',
            'prodscrrcv.rcv_scr_qty',
            'prodembreq.req_emb_qty',
            'prodembrcv.rcv_emb_qty',
            'prodsewline.sew_line_qty',
            'prodsew.sew_qty',
            'prodiron.iron_qty',
            'prodpoly.poly_qty',
            'carton.car_qty',
            'inspec.insp_pass_qty'
        ])
        ->orderby('sales_orders.ship_date')
        ->get()
        ->map(function($rows) use($buyinghouses){
            $receive_date = Carbon::parse($rows->sale_order_receive_date);
            $ship_date = Carbon::parse($rows->ship_date);
            $diff = $receive_date->diffInDays($ship_date);
            if($diff >1){
            $diff.=" Days";
            }else{
            $diff.=" Day";
            }
            $rows->lead_time=$diff;
            //$rows->tna_end_date=$rows->tna_end_date?date('d-M-Y',strtotime($rows->tna_end_date)):'--';
            $rows->agent_name=  isset($buyinghouses[$rows->buying_agent_id])? $buyinghouses[$rows->buying_agent_id]:'';
            $rows->buying_agent_name=$rows->agent_name.", ". $rows->contact;
            $rows->ship_value=$rows->ship_qty*$rows->rate;
            $rows->balance_qty=$rows->qty-$rows->ship_qty;
            $rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
            $rows->org_ship_date=date('d-M-Y',strtotime($rows->org_ship_date));
            $rows->booked_minute=number_format($rows->booked_minute,2);
            $rows->smv=number_format($rows->smv,2);
            $rows->sewing_effi_per=number_format($rows->sewing_effi_per,2);
            $rows->balance_qty=number_format($rows->balance_qty,2);

            $rows->inh_yarn_isu_qty=$rows->inh_yarn_isu_qty-$rows->inh_yarn_isu_rtn_qty;
            $rows->out_yarn_isu_qty=$rows->out_yarn_isu_qty-$rows->out_yarn_isu_rtn_qty;

            $rows->qty=number_format($rows->qty,'0','.',',');
            $rows->rate=number_format($rows->rate,'2','.',',');
            $rows->amount=number_format($rows->amount,'2','.',',');
            $rows->ship_qty=number_format($rows->ship_qty,0,'.',',');
            $rows->ship_value=number_format($rows->ship_value,2,'.',',');

            $rows->yarn_req=number_format($rows->yarn_req,'2','.',',');
            $rows->poyarnlc_qty=number_format($rows->poyarnlc_qty,'2','.',',');
            $rows->yarn_rcv=number_format($rows->yarn_rcv,'2','.',',');
            $rows->yarn_isu_qty=number_format($rows->inh_yarn_isu_qty+$rows->out_yarn_isu_qty,'2','.',',');
            $rows->knit_qty=number_format($rows->knit_qty,'0','.',',');
            $rows->batch_qty=number_format($rows->batch_qty,'0','.',',');
            $rows->dyeing_qty=number_format($rows->dyeing_qty,'0','.',',');
            $rows->fin_fab_req=number_format($rows->fin_fab_req,'0','.',',');
            $rows->finish_qty=number_format($rows->finish_qty,'0','.',',');
            $rows->cut_qty=number_format($rows->cut_qty,'0','.',',');
            $rows->req_scr_qty=number_format($rows->req_scr_qty,'0','.',',');
            $rows->req_emb_qty=number_format($rows->req_emb_qty,'0','.',',');
            $rows->rcv_emb_qty=number_format($rows->rcv_emb_qty,'0','.',',');
            $rows->rcv_scr_qty=number_format($rows->rcv_scr_qty,'0','.',',');
            $rows->sew_line_qty=number_format($rows->sew_line_qty,'0','.',',');

            $rows->sew_qty=number_format($rows->sew_qty,'0','.',',');
            $rows->iron_qty=number_format($rows->iron_qty,'0','.',',');
            $rows->poly_qty=number_format($rows->poly_qty,'0','.',',');
            $rows->car_qty=number_format($rows->car_qty,'0','.',',');

            $rows->insp_pass_qty=number_format($rows->insp_pass_qty,'0','.',',');
            return $rows;
        });

        return $rows;
    }

    public function orderProgress(){
        
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();
        $pdf->SetY(10);
        $pdf->SetFont('helvetica', '', 9);
        $data=$this->getData();

        $sale_order_id=request('sale_order_id', 0);
        $explcsc=collect(
            \DB::select("
                select
                sales_orders.id as sale_order_id,
                exp_lc_scs.lc_sc_no,
                exp_lc_scs.lc_sc_value,
                exp_lc_scs.lc_sc_date,
                exp_lc_scs.file_no,
                currencies.code as currency_code,
                currencies.symbol
                from
                sales_orders
                join exp_pi_orders on sales_orders.id=exp_pi_orders.sales_order_id
                join exp_pis on exp_pis.id=exp_pi_orders.exp_pi_id
                join exp_lc_sc_pis on exp_lc_sc_pis.exp_pi_id=exp_pis.id
                join exp_lc_scs on exp_lc_scs.id=exp_lc_sc_pis.exp_lc_sc_id 
                left join currencies on currencies.id=exp_lc_scs.currency_id
                where sales_orders.id='".$sale_order_id."'
                and exp_pi_orders.deleted_at is null
                group by 
                sales_orders.id,
                exp_lc_scs.lc_sc_no,
                exp_lc_scs.lc_sc_value,
                exp_lc_scs.lc_sc_date,
                exp_lc_scs.file_no,
                currencies.code,
                currencies.symbol
                order by sales_orders.id
            "));
        $expLcArr=array();
        $expLcValueArr=array();
        $expLcDateArr=array();
        $expFileArr=array();
        foreach($explcsc as $row){
            $expLcArr[$row->sale_order_id]=$row->lc_sc_no;
            $expLcValueArr[$row->sale_order_id]=$row->currency_code.' '.$row->symbol.''.$row->lc_sc_value;
            $expLcDateArr[$row->sale_order_id]=$row->lc_sc_date;
            $expFileArr[$row->sale_order_id]=$row->file_no;
        }
        

        
        $exp_lc_sc_no=implode(',',$expLcArr);
        $lc_sc_value=implode(',',$expLcValueArr);
        $lc_sc_date=implode(',',$expLcDateArr);
        $file_no=implode(',',$expFileArr);
        dd($lc_sc_value);die;
        $country=$this->salesorder
        ->join('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
        })
        ->join('countries', function($join)  {
            $join->on('sales_order_countries.country_id', '=', 'countries.id');
        })
        ->where([['sales_orders.id', '=', $sale_order_id]])
        ->get([
            'sales_orders.id as sale_order_id',
            'countries.name as country_name'
        ]);

        $countryArr=array();
        foreach ($country as  $rows) {
            $countryArr[$rows->sale_order_id]=$rows->country_name;
        }

        $country_name=implode(',',$countryArr);

        //dd($data->country_name);


        $view= \View::make('Defult.Approval.SalesOrderProgressPdf',[
            'data'=>$data,
            'country_name'=>$country_name,
            'lc_sc_value'=>$lc_sc_value,
            'exp_lc_sc_no'=>$exp_lc_sc_no,
            'file_no'=>$file_no,
        ]);
        $html_content=$view->render();
        $pdf->WriteHtml($html_content, true, false,true,false,'');

        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/SalesOrderProgressPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }
    
}
