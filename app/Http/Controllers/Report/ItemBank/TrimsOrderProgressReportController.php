<?php

namespace App\Http\Controllers\Report\ItemBank;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Library\Template;


class TrimsOrderProgressReportController extends Controller
{
    private $style;
    private $company;
    private $team;
    private $buyer;
    private $teammember;

    public function __construct(
        StyleRepository $style,
        CompanyRepository $company,
        BuyerRepository $buyer,
        TeamRepository $team,
		TeammemberRepository $teammember
    ) {
        $this->style=$style;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->team = $team;
        $this->teammember = $teammember;

        $this->middleware('auth');
		// $this->middleware('permission:view.poaopserviceitems',   ['only' => ['create', 'index','show']]);

	}

    public function index()
    {
        $company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-','');
        $sortby=array_prepend(config('bprs.sortby'), '-Select-','');
        $status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-All-','');
        return Template::loadView('Report.ItemBank.TrimsOrderProgressReport',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'sortby'=>$sortby,'status'=>$status]);

    }

    public function reportData() {
        $company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;

		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
		    $producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

        $rows=collect(
            \DB::select("
            select
            styles.style_ref,
            styles.id as style_id,
            styles.buyer_id,
            jobs.company_id,
            companies.code as company_code,
            produced_company.code as produced_company_code,
            sales_orders.id as sales_order_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            buyers.code as buyer_code,
            itemclasses.id as itemclass_id,
            itemclasses.name as itemclass_name,
            uoms.code as uom_code,
            users.name as team_member_name,
            sum(bomtrimcons.bom_qty) as bom_qty,
            avg(bomtrimcons.bom_rate) as bom_rate,
            sum(bomtrimcons.bom_amount) as bom_amount,
            sum(potrims.po_qty) as po_qty,
            avg(potrims.po_rate) as po_rate,
            sum(potrims.po_amount) as po_amount,
            sum(trimsrcv.rcv_qty) as rcv_qty,
            avg(trimsrcv.rcv_rate) as rcv_rate,
            sum(trimsrcv.rcv_amount) as rcv_amount
            from
            styles
            join buyers on buyers.id=styles.buyer_id
            join teammembers on teammembers.id=styles.factory_merchant_id
		    left join users on users.id=teammembers.user_id
            join jobs on jobs.style_id=styles.id
            join companies on companies.id=jobs.company_id
            join sales_orders on sales_orders.job_id=jobs.id
            left join companies produced_company on produced_company.id=sales_orders.produced_company_id
            join budgets on budgets.style_id=styles.id
            join  budget_trims on budget_trims.budget_id=budgets.id
            and budgets.style_id=styles.id
            join uoms on uoms.id=budget_trims.uom_id
            join itemclasses on budget_trims.itemclass_id=itemclasses.id
            join itemcategories on itemcategories.id=itemclasses.itemcategory_id
            left join (
                select
                sales_orders.id as sales_order_id,
                budget_trim_cons.budget_trim_id,
                sum(budget_trim_cons.bom_trim) as bom_qty,
                avg(budget_trim_cons.rate) as bom_rate,
                sum(budget_trim_cons.amount) as bom_amount
                from
                sales_orders
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id=sales_orders.id
                join budget_trim_cons on budget_trim_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
                join jobs on sales_orders.job_id=jobs.id
                join styles on jobs.style_id=styles.id
                where sales_order_gmt_color_sizes.deleted_at is null
                and budget_trim_cons.deleted_at is null
                group by
                sales_orders.id,
                budget_trim_cons.budget_trim_id
            )bomtrimcons on bomtrimcons.sales_order_id=sales_orders.id
            and bomtrimcons.budget_trim_id=budget_trims.id
            
            left join (
                select
                po_trim_item_reports.sales_order_id,
                po_trim_items.budget_trim_id,
                sum(po_trim_item_reports.qty) as po_qty,
                avg(po_trim_item_reports.rate) as po_rate,
                sum(po_trim_item_reports.amount) as po_amount
                from
                po_trim_item_reports
                join po_trim_items on po_trim_item_reports.po_trim_item_id=po_trim_items.id
                where po_trim_items.deleted_at is null
                group by
                po_trim_item_reports.sales_order_id,
                po_trim_items.budget_trim_id
            )potrims on potrims.sales_order_id=sales_orders.id
            and potrims.budget_trim_id=budget_trims.id
            
            left join (
                select
                po_trim_item_reports.sales_order_id,
                po_trim_items.budget_trim_id,
                sum(inv_trim_rcv_items.qty) as rcv_qty,
                avg(inv_trim_rcv_items.rate) as rcv_rate,
                sum(inv_trim_rcv_items.amount) as rcv_amount
                from inv_rcvs
                join inv_trim_rcvs on inv_trim_rcvs.INV_RCV_ID=inv_rcvs.id
                join inv_trim_rcv_items on inv_trim_rcv_items.inv_trim_rcv_id=inv_trim_rcvs.id
                join po_trim_item_reports on po_trim_item_reports.id=inv_trim_rcv_items.po_trim_item_report_id
                join po_trim_items on po_trim_item_reports.po_trim_item_id=po_trim_items.id
                where
                inv_trim_rcv_items.deleted_at is null
                group by
                po_trim_item_reports.sales_order_id,
                po_trim_items.budget_trim_id
            )trimsrcv on trimsrcv.sales_order_id=sales_orders.id
            and trimsrcv.budget_trim_id=budget_trims.id

            where 1=1
            and sales_orders.order_status !=2 
            $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
            group by
                styles.style_ref,
                styles.id,
                styles.buyer_id,
                jobs.company_id,
                companies.code,
                produced_company.code,
                sales_orders.id,
                sales_orders.sale_order_no,
                sales_orders.ship_date,
                buyers.code,
                itemclasses.id,
                itemclasses.name,
                uoms.code,
                users.name
            order by
            styles.id,
            sales_orders.id
            ")
        )
        ->map(function($rows){
            $rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
            $bal_po_qty=$rows->bom_qty-$rows->po_qty;
            $bal_po_amount=$rows->bom_amount-$rows->po_amount;
            $bal_rcv_qty=$rows->bom_qty-$rows->rcv_qty;
            $bal_rcv_amount=$rows->bom_amount-$rows->rcv_amount;
            $rows->rcv_qty=number_format($rows->rcv_qty,2);
            $rows->rcv_amount=number_format($rows->rcv_amount,2);
            $rows->bal_po_qty=number_format($bal_po_qty,2);
            $rows->bal_po_amount=number_format($bal_po_amount,2);
            $rows->bal_rcv_qty=number_format($bal_rcv_qty,2);
            $rows->bal_rcv_amount=number_format($bal_rcv_amount,2);
            $rows->bom_qty=number_format($rows->bom_qty,2);
            $rows->bom_rate=number_format($rows->bom_rate,4);
            $rows->bom_amount=number_format($rows->bom_amount,2);
            $rows->po_qty=number_format($rows->po_qty,2);
            $rows->po_rate=number_format($rows->po_rate,4);
            $rows->po_amount=number_format($rows->po_amount,2);
          
            return $rows;
        });

        echo json_encode($rows);
    }

    public function getTrimsStyle(){
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

	public function getTrimsDlMerchant(){
		$membertype=array_prepend(config('bprs.membertype'),'-Select-',0);
		$teammember = $this->teammember
		->join('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
		})
		->join('teams', function($join)  {
			$join->on('teammembers.team_id', '=', 'teams.id');
		})
		->when(request('team_id'), function ($q) {
			return $q->where('teammembers.team_id', '=', request('team_id', 0));
		})
		->get([
			'users.id as user_id',
			'teammembers.id as factory_merchant_id',
			'teammembers.type_id',
			'teams.name as team_name',
			'users.name as dlm_name',
		])
		->map(function($teammember)use($membertype){
			$teammember->type_id=$membertype[$teammember->type_id];	
			return $teammember;
		});
		echo json_encode($teammember);
	}

    public function getPoTrimQty(){
        $company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);

        $sales_order_id=request('sales_order_id', 0);
        $itemclass_id=request('itemclass_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;

		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
		    $producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

        $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');

        $rows=collect(
            \DB::select(
            "select
            po_trims.id,
            po_trims.po_no,
            po_trims.po_date,
            po_trims.source_id,
            po_trims.pay_mode,
            po_trims.pi_date,
            po_trims.pi_no,
            po_trims.exch_rate,
            po_trims.delv_start_date,
            po_trims.delv_end_date,
            po_trims.approved_by,
            po_trims.remarks,
            companies.code as company_code,
            suppliers.name as supplier_name,
            sum(po_trim_item_reports.qty) as po_qty,
            avg(po_trim_item_reports.rate) as po_rate,
            sum(po_trim_item_reports.amount) as po_amount
            from
            po_trim_item_reports
            join po_trim_items on po_trim_item_reports.po_trim_item_id=po_trim_items.id
            join po_trims on po_trim_items.po_trim_id=po_trims.id
            join companies on companies.id=po_trims.company_id
            join suppliers on suppliers.id=po_trims.supplier_id
            join budget_trims on po_trim_items.budget_trim_id=budget_trims.id
            join sales_orders on sales_orders.id=po_trim_item_reports.sales_order_id
            join jobs on sales_orders.job_id=jobs.id
            join styles on styles.id=jobs.style_id
            join itemclasses on budget_trims.itemclass_id=itemclasses.id
            where po_trim_items.deleted_at is null
            and po_trim_item_reports.sales_order_id = ?
            and itemclasses.id=?
            $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
            group by
            po_trims.id,
            po_trims.po_no,
            po_trims.po_date,
            po_trims.source_id,
            po_trims.pay_mode,
            po_trims.pi_date,
            po_trims.pi_no,
            po_trims.exch_rate,
            po_trims.delv_start_date,
            po_trims.delv_end_date,
            po_trims.approved_by,
            companies.code,
            suppliers.name,
            po_trims.remarks"
            ,[$sales_order_id,$itemclass_id]
        ))
        ->map(function($rows) use($source,$paymode){
            $rows->source=$source[$rows->source_id];
            $rows->paymode=$paymode[$rows->pay_mode];
            $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
            $rows->pi_date=date('d-M-Y',strtotime($rows->pi_date));
            $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
            $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
            $rows->po_qty=number_format($rows->po_qty,2);
            $rows->po_rate=number_format($rows->po_rate,4);
            $rows->po_amount=number_format($rows->po_amount,2);
            if($rows->approved_by){
                $rows->approved="Approved";
              }
            return $rows;
        });

        echo json_encode($rows);
    }

    public function getRcvTrimQty(){
        $company_id=request('company_id', 0);
    	$produced_company_id=request('produced_company_id', 0);
		$buyer_id=request('buyer_id', 0);
		$style_ref=request('style_ref', 0);
		$style_id=request('style_id', 0);
		$factory_merchant_id=request('factory_merchant_id', 0);
		$order_status=request('order_status',0);

		$date_from=request('date_from', 0);
        $date_to=request('date_to', 0);
		$receive_date_from=request('receive_date_from', 0);
        $receive_date_to=request('receive_date_to', 0);

        $sales_order_id=request('sales_order_id', 0);
        $itemclass_id=request('itemclass_id', 0);

		$company=null;
		$producedcompany=null;
		$buyer=null;
		$style=null;
		$styleid=null;
		$factorymerchant=null;
		$orderstatus=null;
		$datefrom=null;
		$dateto=null;
		$receivedatefrom=null;
		$receivedateto=null;

		if($company_id){
			$company=" and jobs.company_id = $company_id ";
		}
		if($produced_company_id){
		    $producedcompany=" and sales_orders.produced_company_id = $produced_company_id ";
		}
		if($buyer_id){
			$buyer=" and styles.buyer_id=$buyer_id ";
		}

		if($style_ref){
			$style=" and styles.style_ref like '%".$style_ref."%' ";
		}
		if($style_id){
			$styleid=" and styles.id = $style_id ";
		}
		if($factory_merchant_id){
			$factorymerchant=" and styles.factory_merchant_id = $factory_merchant_id ";
		}
		if($order_status){
			$orderstatus=" and sales_orders.order_status = $order_status ";
		}
		if($date_from){
			$datefrom=" and sales_orders.ship_date>='".$date_from."' ";
		}
		if($date_to){
			$dateto=" and sales_orders.ship_date<='".$date_to."' ";
		}
		if($receive_date_from){
			$receivedatefrom=" and sales_orders.receive_date>='".$receive_date_from."' ";
		}
		if($receive_date_to){
			$receivedateto=" and sales_orders.receive_date<='".$receive_date_to."' ";
		}

        $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');

        $rows=collect(\DB::select("
            select
                inv_rcvs.receive_no,
                inv_rcvs.receive_date,
                inv_rcvs.receive_basis_id,
                inv_rcvs.challan_no,
                inv_trim_rcvs.id as inv_trim_rcv_id,
                companies.code as company_code,
                suppliers.name as supplier_name,
                itemclasses.name as class_name,
                sales_orders.sale_order_no,
                po_trim_item_reports.description,
                sum(inv_trim_rcv_items.qty) as rcv_qty,
                avg(inv_trim_rcv_items.rate) as rcv_rate,
                sum(inv_trim_rcv_items.amount) as rcv_amount
            from inv_rcvs
            join companies on companies.id=inv_rcvs.company_id
            join suppliers on suppliers.id=inv_rcvs.supplier_id
            join inv_trim_rcvs on inv_trim_rcvs.inv_rcv_id=inv_rcvs.id
            join inv_trim_rcv_items on inv_trim_rcv_items.inv_trim_rcv_id=inv_trim_rcvs.id
            join po_trim_item_reports on po_trim_item_reports.id=inv_trim_rcv_items.po_trim_item_report_id
            join po_trim_items on po_trim_items.id=po_trim_item_reports.po_trim_item_id
            join budget_trims on budget_trims.id=po_trim_items.budget_trim_id
            join itemclasses on itemclasses.id=budget_trims.itemclass_id
            join sales_orders on sales_orders.id=po_trim_item_reports.sales_order_id
            join jobs on sales_orders.job_id=jobs.id
            join styles on styles.id=jobs.style_id
            where
            inv_trim_rcv_items.deleted_at is null
            and po_trim_items.deleted_at is null
            and po_trim_item_reports.sales_order_id = ?
            and itemclasses.id = ?
            $company $producedcompany $buyer $style  $styleid $factorymerchant $orderstatus $datefrom $dateto $receivedatefrom $receivedateto
            group by
            inv_rcvs.receive_no,
            inv_rcvs.receive_date,
            inv_rcvs.receive_basis_id,
            inv_rcvs.challan_no,
            inv_trim_rcvs.id,
            companies.code,
            suppliers.name,
            itemclasses.name,
            po_trim_item_reports.description,
            sales_orders.sale_order_no
            ",[$sales_order_id,$itemclass_id]
        ))
        ->map(function($rows) use($invreceivebasis){
            $rows->receive_basis_id=$invreceivebasis[$rows->receive_basis_id];
            $rows->rcv_qty=number_format($rows->rcv_qty,2);
            $rows->rcv_rate=number_format($rows->rcv_rate,4);
            $rows->rcv_amount=number_format($rows->rcv_amount,2);
            return $rows;
        });

        echo json_encode($rows);
    }

}