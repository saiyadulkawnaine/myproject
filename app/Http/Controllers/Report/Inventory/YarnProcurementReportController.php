<?php
namespace App\Http\Controllers\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;

class YarnProcurementReportController extends Controller
{
  private $invrcv;
  private $style;
  private $buyer;
  private $company;
  private $salesorder;
  private $user;

  public function __construct(
    StyleRepository $style,
    SalesOrderRepository $salesorder,
    SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
    InvRcvRepository $invrcv,
    ItemAccountRepository $itemaccount,
    BuyerRepository $buyer,
    UserRepository $user,
    CompanyRepository $company
  )
  
  {
    $this->invrcv=$invrcv;
    $this->itemaccount=$itemaccount;
    $this->buyer = $buyer;
    $this->company = $company;
    $this->style = $style;
    $this->user = $user;
    $this->salesorder = $salesorder;
    $this->salesordergmtcolorsize = $salesordergmtcolorsize;

    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
    public function index() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
        return Template::loadView('Report.Inventory.YarnProcurementReport',['buyer'=>$buyer,'company'=>$company,'status'=>$status]);
    }


    public function reportData() {
        $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
            $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('compositions',function($join){
            $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->where([['itemcategories.identity','=',1]])
        ->orderBy('item_account_ratios.ratio','desc')
        ->get([
            'item_accounts.id',
            'compositions.name as composition_name',
            'item_account_ratios.ratio',
            'yarncounts.count',
            'yarncounts.symbol',
		    'yarntypes.name as yarn_type',
        ]);
        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
		    $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
          $yarnDropdown[$key]=$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
        }

        $rows=$this->getData()->map(function($rows) use($yarnDropdown){
        $rows->tna_start_date=$rows->tna_start_date?date('d-M-Y',strtotime($rows->tna_start_date)):'--';
        $rows->tna_end_date=$rows->tna_end_date?date('d-M-Y',strtotime($rows->tna_end_date)):'--';
        $rows->issue_start_date=$rows->issue_start_date?date('d-M-Y',strtotime($rows->issue_start_date)):'--';
        $rows->issue_end_date=$rows->issue_end_date?date('d-M-Y',strtotime($rows->issue_end_date)):'--';
        $rows->issue_qty=$rows->inh_yarn_isu_qty?$rows->inh_yarn_isu_qty:$rows->out_yarn_isu_qty;
        $rows->po_bal=$rows->req_qty-$rows->po_qty;
        $issue_bal=$rows->req_qty-$rows->issue_qty;
        $rows->yarn_des=isset($rows->item_account_id)?$yarnDropdown[$rows->item_account_id]:'';
        $rows->po_bal=number_format($rows->po_bal,2);
        $rows->issue_bal=number_format($issue_bal,2);
        $rows->req_qty=number_format($rows->req_qty,2);
        $rows->po_qty=number_format($rows->po_qty,2);
        $rows->issue_qty=number_format($rows->issue_qty,2);
        $rows->uom='Kg';
          return $rows;
        });

        echo json_encode($rows);
    }
    private function getData()
    {
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_id=request('style_id', 0);
		$sales_order_id=request('sales_order_id', 0);
        $order_status=request('order_status',0);
		$produced_company=null;
		$buyer=null;
		$style=null;
		$salesorder=null;
		$datefrom=null;
		$dateto=null;
        $orderstatus=null;
		if($produced_company_id){
			$produced_company=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}
		if($style_id){
			$style=" and styles.id = $style_id ";
		}
		if($sales_order_id){
			$salesorder=" and sales_orders.id = $sales_order_id ";
		}
        if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
        if($order_status){
            $orderstatus=" and sales_orders.order_status = $order_status ";
        }

        $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
            $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('compositions',function($join){
            $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->where([['itemcategories.identity','=',1]])
        ->orderBy('item_account_ratios.ratio','desc')
        ->get([
            'item_accounts.id',
            'compositions.name as composition_name',
            'item_account_ratios.ratio',
            'yarncounts.count',
            'yarncounts.symbol',
		    'yarntypes.name as yarn_type',
        ]);
        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
        $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
		$itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
          $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
          $yarnDropdown[$key]=$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
        }
        

        $results=collect(
			\DB::select("
			select 
                styles.id as style_id,
                styles.style_ref,
                styles.factory_merchant_id,
                buyers.id as buyer_id,
                buyers.code as buyer_name,
                companies.code as company_code,
                produced_company.code as produced_company_code,
                users.id as user_id,
                users.name as team_member_name,
                sales_orders.id as sale_order_id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                tna.tna_start_date,
                tna.tna_end_date,
                tna.acl_start_date,
                tna.acl_end_date,
                budgetYarn.item_account_id,
                inhyarnisu.max_inh_issue_date,
                inhyarnisu.min_inh_issue_date,
                outyarnisu.max_out_issue_date,
                outyarnisu.min_out_issue_date,
                budgetYarn.yarn_qty as req_qty,
                budgetYarn.yarn_amount,
                PoYarnItem.po_item_qty,
                poYarn.po_qty,
                inhyarnisu.inh_yarn_isu_qty,
                outyarnisu.out_yarn_isu_qty
            from
            styles
            left join buyers on buyers.id=styles.buyer_id
            left join uoms on uoms.id=styles.uom_id
            left join teams on teams.id=styles.team_id
            left join teammembers on teammembers.id=styles.factory_merchant_id
            left join users on users.id=teammembers.user_id
            join jobs  on jobs.style_id=styles.id

            join budgets on budgets.style_id = styles.id 
            join sales_orders on sales_orders.job_id = jobs.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id 
            join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id 
            left join(
                select 
                tna_ords.sales_order_id,
                tna_ords.tna_start_date,
                tna_ords.tna_end_date,
                tna_ords.acl_start_date,
                tna_ords.acl_end_date
                from
                tna_ords 
                join sales_orders on sales_orders.id=tna_ords.sales_order_id
                where tna_ords.tna_task_id=52
            ) tna on tna.sales_order_id=sales_orders.id

            left join(
                select 
                m.sales_order_id,
                m.item_account_id,
                sum(m.yarn) as yarn_qty,
                sum(m.yarn_amount) as yarn_amount  
                from 
                (
                    select budget_yarns.id as budget_yarn_id,
                    budget_yarns.item_account_id,
                    budget_yarns.ratio,
                    budget_yarns.cons,
                    budget_yarns.rate,
                    budget_yarns.amount,
                    sales_orders.id as sales_order_id,
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
                    where 1=1 $datefrom $dateto $style $salesorder $produced_company $buyer $orderstatus
                    group by 
                    budget_yarns.id,
                    budget_yarns.item_account_id,
                    budget_yarns.ratio,
                    budget_yarns.cons,
                    budget_yarns.rate,
                    budget_yarns.amount,
                    sales_orders.id,
                    sales_orders.sale_order_no
                ) m group by 
                m.sales_order_id,
                m.item_account_id
            ) budgetYarn on budgetYarn.sales_order_id = sales_orders.id

            left join(
                select 
                budget_yarns.item_account_id,
                sales_orders.id as sales_order_id,
                sum(po_yarn_item_bom_qties.qty) as po_qty
                from budget_yarns
                join budgets on budgets.id=budget_yarns.budget_id
                join jobs on jobs.id=budgets.job_id
                join sales_orders on sales_orders.job_id=jobs.id 
                join styles on styles.id=jobs.style_id
                left join po_yarn_item_bom_qties on budget_yarns.id=po_yarn_item_bom_qties.budget_yarn_id 
                and sales_orders.id=po_yarn_item_bom_qties.sale_order_id
                where 1=1 $datefrom $dateto $style $salesorder $produced_company  $buyer $orderstatus
                group by 
                budget_yarns.item_account_id,
                sales_orders.id
            ) poYarn on poYarn.sales_order_id=sales_orders.id 
            and poYarn.item_account_id = budgetYarn.item_account_id

            left join (
                SELECT 
                sales_orders.id as sales_order_id,
                inv_yarn_items.item_account_id,
                max(inv_isus.issue_date) as max_inh_issue_date,
                min(inv_isus.issue_date) as min_inh_issue_date,
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
                left join inv_yarn_items on inv_yarn_items.id=inv_yarn_isu_items.inv_yarn_item_id
                join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
                join suppliers on suppliers.id = inv_isus.supplier_id
                join companies on companies.id = suppliers.company_id
                join jobs on jobs.id = sales_orders.job_id 
                join styles on styles.id = jobs.style_id 
                where inv_isus.isu_against_id=102 and inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null $produced_company $buyer $style $datefrom $dateto $salesorder $orderstatus
                group by 
                sales_orders.id,
                inv_yarn_items.item_account_id
            ) inhyarnisu on inhyarnisu.sales_order_id=sales_orders.id
            and inhyarnisu.item_account_id=budgetYarn.item_account_id

            left join(
                SELECT 
                sales_orders.id as sales_order_id,
                inv_yarn_items.item_account_id,
                max(inv_isus.issue_date) as max_out_issue_date,
                min(inv_isus.issue_date) as min_out_issue_date,
                sum(inv_yarn_isu_items.qty) as out_yarn_isu_qty
                from sales_orders 
                join po_knit_service_item_qties on po_knit_service_item_qties.sales_order_id = sales_orders.id
                join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
                join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id
                join rq_yarn_fabrications on rq_yarn_fabrications.po_knit_service_item_qty_id = po_knit_service_item_qties.id
                join rq_yarn_items on rq_yarn_items.rq_yarn_fabrication_id = rq_yarn_fabrications.id
                join rq_yarns on rq_yarns.id = rq_yarn_fabrications.rq_yarn_id  
                join inv_yarn_isu_items on inv_yarn_isu_items.rq_yarn_item_id = rq_yarn_items.id
                left join inv_yarn_items on inv_yarn_items.id=inv_yarn_isu_items.inv_yarn_item_id
                join inv_isus on inv_isus.id = inv_yarn_isu_items.inv_isu_id
                join suppliers on suppliers.id = inv_isus.supplier_id 
                and (suppliers.company_id is null or  suppliers.company_id=0)
                join companies on companies.id = inv_isus.company_id
                join jobs on jobs.id = sales_orders.job_id 
                join styles on styles.id = jobs.style_id 
                where   inv_isus.isu_against_id=102 and   inv_isus.isu_basis_id=1 and inv_yarn_isu_items.deleted_at is null $produced_company $buyer $style $datefrom $dateto $salesorder $orderstatus
                group by 
                sales_orders.id,
                inv_yarn_items.item_account_id
            ) outyarnisu on outyarnisu.sales_order_id = sales_orders.id 
            and outyarnisu.item_account_id=budgetYarn.item_account_id
            left join(
            select 
            po_yarn_items.item_account_id,
            po.sales_order_id,
            sum(po_yarn_items.qty) as po_item_qty
            from
            po_yarn_items
            join
            (
                select
                    po_yarn_item_bom_qties.po_yarn_item_id,
                    budget_yarns.item_account_id,
                    sales_orders.id as sales_order_id,
                    sum(po_yarn_item_bom_qties.qty) as po_bom_qty
                    from 
                    budget_yarns
                    join budgets on budgets.id=budget_yarns.budget_id
                    join jobs on jobs.id=budgets.job_id
                    join sales_orders on sales_orders.job_id=jobs.id 
                    join styles on styles.id=jobs.style_id
                    join po_yarn_item_bom_qties on budget_yarns.id=po_yarn_item_bom_qties.budget_yarn_id 
                    and sales_orders.id=po_yarn_item_bom_qties.sale_order_id
                group by
                po_yarn_item_bom_qties.po_yarn_item_id,
                budget_yarns.item_account_id,
                sales_orders.id
            )po on po.po_yarn_item_id=po_yarn_items.id
            group by
            po_yarn_items.item_account_id,
            po.sales_order_id
            )PoYarnItem on   PoYarnItem.item_account_id=budgetYarn.item_account_id
            and PoYarnItem.sales_order_id=sales_orders.id
            left join companies  on companies.id=jobs.company_id
            left join companies  produced_company on produced_company.id=sales_orders.produced_company_id
            where sales_orders.order_status not in (2) 
            $datefrom $dateto $style $salesorder $produced_company $buyer $orderstatus
            group by
                styles.id,
                styles.style_ref,
                styles.factory_merchant_id,
                buyers.id,
                buyers.code,
                companies.code,
                produced_company.code,
                users.id,
                users.name,
                sales_orders.id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                budgetYarn.item_account_id,
                tna.tna_start_date,
                tna.tna_end_date,
                tna.acl_start_date,
                tna.acl_end_date,
                budgetYarn.yarn_qty,
                budgetYarn.yarn_amount,
                inhyarnisu.max_inh_issue_date,
                inhyarnisu.min_inh_issue_date,
                outyarnisu.max_out_issue_date,
                outyarnisu.min_out_issue_date,
                PoYarnItem.po_item_qty,
                poYarn.po_qty,
                inhyarnisu.inh_yarn_isu_qty,
                outyarnisu.out_yarn_isu_qty
            order by sales_orders.id
        "))
        ->map(function($results) use($yarnDropdown){
            $results->yarn_des=isset($results->item_account_id)?$yarnDropdown[$results->item_account_id]:'';
            $results->issue_qty=$results->inh_yarn_isu_qty?$results->inh_yarn_isu_qty:$results->out_yarn_isu_qty;
            $results->issue_start_date=$results->min_inh_issue_date?$results->min_inh_issue_date:$results->min_out_issue_date;
            $results->issue_end_date=$results->max_inh_issue_date?$results->max_inh_issue_date:$results->max_out_issue_date;
            return $results;
        });
        return $results;
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
        ->get();
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

    public function getDealMerchant(){
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
            'employee_h_rs.email',
            'employee_h_rs.experience',
            'employee_h_rs.contact'
        ])
        ->map(function($dlmerchant){
            $dlmerchant->date_of_join=date('d-M-Y',strtotime($dlmerchant->date_of_join));
            return $dlmerchant;
        });
        echo json_encode($dlmerchant);
    }

    public function getPoQty(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_id=request('style_id', 0);
		$sales_order_id=request('sales_order_id', 0);
        $order_status=request('order_status',0);
		$produced_company=null;
		$buyer=null;
		$style=null;
		$salesorder=null;
		$datefrom=null;
		$dateto=null;
        $orderstatus=null;
		if($produced_company_id){
			$produced_company=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}
		if($style_id){
			$style=" and styles.id = $style_id ";
		}
		if($sales_order_id){
			$salesorder=" and sales_orders.id = $sales_order_id ";
		}
        if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
        if($order_status){
            $orderstatus=" and sales_orders.order_status = $order_status ";
        }
        $rows=collect(
			\DB::select("
            select 
            po_yarns.id as po_yarn_id,
            po_yarns.po_no,
            po_yarns.po_date,
            po_yarns.pi_no,
            po_yarns.pi_date,
            po_yarns.remarks,
            suppliers.name as supplier_name,
            ImpLc.lc_no_i,
            ImpLc.lc_no_ii,
            ImpLc.lc_no_iii,
            ImpLc.lc_no_iv,
            sum(po_yarn_items.qty) as po_item_qty,
            sum(po_yarn_item_bom_qties.qty) as po_qty,
            sum(po_yarn_item_bom_qties.amount) as po_amount
            from budget_yarns
            join budgets on budgets.id=budget_yarns.budget_id
            join jobs on jobs.id=budgets.job_id
            join sales_orders on sales_orders.job_id=jobs.id 
            join styles on styles.id=jobs.style_id
            left join po_yarn_item_bom_qties on budget_yarns.id=po_yarn_item_bom_qties.budget_yarn_id 
            and sales_orders.id=po_yarn_item_bom_qties.sale_order_id
            left join po_yarn_items on po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id
            left join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id

            left join buyers on buyers.id=styles.buyer_id
            left join suppliers on suppliers.id=po_yarns.supplier_id
            left join(
                select 
                    imp_lc_pos.purchase_order_id,
                    imp_lcs.lc_no_i,
                    imp_lcs.lc_no_ii,
                    imp_lcs.lc_no_iii,
                    imp_lcs.lc_no_iv
                    from imp_lc_pos
                    join po_yarns on imp_lc_pos.purchase_order_id=po_yarns.id
                    join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
                    where imp_lcs.menu_id=3
                    group by 
                    imp_lc_pos.purchase_order_id,
                    imp_lcs.lc_no_i,
                    imp_lcs.lc_no_ii,
                    imp_lcs.lc_no_iii,
                    imp_lcs.lc_no_iv
            ) ImpLc on ImpLc.purchase_order_id=po_yarns.id 
            where po_yarn_item_bom_qties.sale_order_id='".request('sale_order_id', 0)."'
            and budget_yarns.item_account_id='".request('item_account_id', 0)."'
            and sales_orders.order_status not in (2) 
            $datefrom $dateto $style $salesorder $produced_company $buyer $orderstatus
            group by 
            po_yarns.id,
            po_yarns.po_no,
            po_yarns.po_date,
            po_yarns.pi_no,
            po_yarns.pi_date,
            po_yarns.remarks,
            suppliers.name,
            ImpLc.lc_no_i,
            ImpLc.lc_no_ii,
            ImpLc.lc_no_iii,
            ImpLc.lc_no_iv
            "))
            ->map(function($rows){
                $rows->import_lc_no=$rows->lc_no_i.$rows->lc_no_ii.$rows->lc_no_iii.$rows->lc_no_iv;
                $rows->lc_no=($rows->lc_no_i!==null)?$rows->import_lc_no:'--';
                $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
                $rows->pi_date=$rows->pi_date?date('d-M-Y',strtotime($rows->pi_date)):'';
                $rows->po_qty=number_format($rows->po_qty,2);
                $rows->po_item_qty=number_format($rows->po_item_qty,2);
                $rows->po_amount=number_format($rows->po_amount,2);
                return $rows;
            });

        echo json_encode($rows);
    }

    public function yarnSummery(){
        $itemarr=[];
        $datas=$this->getData();
        foreach ($datas as $rows){
            
            $itemarr[$rows->item_account_id]['yarn_des']=$rows->yarn_des;

            if(isset($itemarr[$rows->item_account_id]['req_qty'])){
                $itemarr[$rows->item_account_id]['req_qty']+=$rows->req_qty;
            }
            else{
                $itemarr[$rows->item_account_id]['req_qty']=$rows->req_qty;
            }

            if(isset($itemarr[$rows->item_account_id]['po_qty'])){
                $itemarr[$rows->item_account_id]['po_qty']+=$rows->po_qty;
            }
            else{
            $itemarr[$rows->item_account_id]['po_qty']=$rows->po_qty;
            }

            if(isset($itemarr[$rows->item_account_id]['po_item_qty'])){
                $itemarr[$rows->item_account_id]['po_item_qty']+=$rows->po_item_qty;
            }
            else{
            $itemarr[$rows->item_account_id]['po_item_qty']=$rows->po_item_qty;
            }

            if(isset($itemarr[$rows->item_account_id]['issue_qty'])){
                $itemarr[$rows->item_account_id]['issue_qty']+=$rows->issue_qty;
            }
            else{
                $itemarr[$rows->item_account_id]['issue_qty']=$rows->issue_qty;
            }
        }

        $itemarrs=[];


        foreach($itemarr as $key=>$value){
            if ($value['req_qty']) {
                $percent=($value['issue_qty']/$value['req_qty'])*100;
            }
            
            $row=[
                'po_bal'=>number_format($value['req_qty']-$value['po_qty'],2),
                'issue_bal'=>number_format($value['req_qty']-$value['issue_qty'],2),

                'issue_per'=>number_format($percent,0),
                'yarn_des'=>$value['yarn_des'],
                'req_qty'=>number_format($value['req_qty'],2),
               'po_item_qty'=>number_format($value['po_item_qty'],2),
                'po_qty'=>number_format($value['po_qty'],2),
                'issue_qty'=>number_format($value['issue_qty'],2),
                
                
            ];
         array_push($itemarrs,$row);
        }
        echo json_encode($itemarrs);
    }

}
