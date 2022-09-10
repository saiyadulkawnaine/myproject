<?php

namespace App\Http\Controllers\Report\FAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\FAMS\AssetBreakdownRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use Illuminate\Support\Carbon;

class AssetBreakdownReportController extends Controller
{
    private $assetbreakdown;
	private $assetacquisition;
	private $assetquantitycost;
    private $invpurreq;
    private $company;

	public function __construct(
        AssetBreakdownRepository $assetbreakdown,
        AssetAcquisitionRepository $assetacquisition, 
        CompanyRepository $company,
        InvPurReqRepository $invpurreq,
        AssetQuantityCostRepository $assetquantitycost
        )
    {
		$this->assetacquisition = $assetacquisition;
		$this->assetbreakdown = $assetbreakdown;
		$this->assetquantitycost = $assetquantitycost;
        $this->invpurreq = $invpurreq;
        $this->company = $company;

		$this->middleware('auth');
		//$this->middleware('permission:view.assetbreakdownreport',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
        $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $reason=array_prepend(config('bprs.reason'),'','');
      return Template::loadView('Report.FAM.AssetBreakdownReport',['productionarea'=>$productionarea,'reason'=>$reason,'company'=>$company]);
	 }
	 
	public function reportData() {
	  $date_from=request('date_from', 0);
	  $date_to=request('date_to', 0);
      $company_id=request('company_id', 0);
      $reason_id=request('reason_id', 0);
      
      $reason=array_prepend(config('bprs.reason'),'--','');
      $decision = array_prepend(config('bprs.decision'),'--','');
      $assetType = config('bprs.assetType');

      $rows=$this->assetbreakdown
        ->join('asset_quantity_costs',function($join){
          $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
        })
        ->join('asset_acquisitions',function($join){
          $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('employee_h_rs as maintenance_by',function($join){
            $join->on('maintenance_by.id','=','asset_breakdowns.employee_h_r_id');
        })
        ->leftJoin('inv_pur_req_asset_breakdowns',function($join){
            $join->on('asset_breakdowns.id','=','inv_pur_req_asset_breakdowns.asset_breakdown_id');
        })
        ->leftJoin(\DB::raw("(
          select 
          asset_quantity_costs.id as asset_quantity_cost_id,
          asset_manpowers.employee_h_r_id,
          employee_h_rs.name as employee_name
          from asset_manpowers
          join asset_quantity_costs on asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id
          join employee_h_rs on employee_h_rs.id=asset_manpowers.employee_h_r_id
          where asset_manpowers.id=(select max(asset_manpowers.id) from asset_manpowers where asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id)
          group by 
          asset_quantity_costs.id,
          asset_manpowers.employee_h_r_id,
          employee_h_rs.name
          ) cumulatives"), "cumulatives.asset_quantity_cost_id", "=", "asset_quantity_costs.id")
        ->when(request('company_id'), function ($q) {
            return $q->where('asset_acquisitions.company_id', '=', request('company_id', 0));
        })
        ->when(request('reason_id'), function ($q) {
            return $q->where('asset_breakdowns.reason_id', '=', request('reason_id', 0));
        })
        ->when(request('date_from'), function ($q) use($date_from) {
            return $q->whereDate('asset_breakdowns.breakdown_at', '>=', $date_from);
        })
        ->when(request('date_to'), function ($q) use($date_to)  {
           return $q->whereDate('asset_breakdowns.breakdown_at', '<=', $date_to);
        })
		->orderBy('asset_acquisitions.id','asc')
		->get([
            'asset_breakdowns.*',
            'asset_quantity_costs.custom_no',
            'asset_quantity_costs.serial_no',
            'asset_acquisitions.name as asset_name',
            'maintenance_by.name as maintenance_name',
            'cumulatives.employee_name',
            'asset_acquisitions.type_id',
            'asset_acquisitions.production_area_id',
            'asset_acquisitions.asset_group',
            'asset_acquisitions.brand',
            'asset_acquisitions.origin',
            'asset_acquisitions.purchase_date',
            'asset_acquisitions.prod_capacity',
            'inv_pur_req_asset_breakdowns.id as inv_pur_req_asset_id',
        ])
        ->map(function ($rows) use($assetType,$reason,$decision)  {
            if ($rows->function_at) {
                $breakdown=Carbon::parse($rows->breakdown_at);
                $function=Carbon::parse($rows->function_at);
                $rows->total_breakdown_hour=$breakdown->diffInHours($function);
            }else {
                $today=Carbon::now();
                $breakdown=Carbon::parse($rows->breakdown_at);
                $rows->pending_breakdown_hour=$breakdown->diffInHours($today);
            }
            

            $rows->asset_type_name = isset($assetType[$rows->type_id])?$assetType[$rows->type_id]:'';
            $rows->reason_id=isset($reason[$rows->reason_id])?$reason[$rows->reason_id]:'';
            $rows->decision_id=isset($decision[$rows->decision_id])?$decision[$rows->decision_id]:'';
            $rows->breakdown_date=date('d-M-Y',strtotime($rows->breakdown_at));
            $rows->breakdown_time=date('h:i A',strtotime($rows->breakdown_at));
            $rows->purchase_date=($rows->purchase_date)?date('d-M-Y',strtotime($rows->purchase_date)):'--';
            $rows->function_date=($rows->function_at)?date('d-M-Y',strtotime($rows->function_at)):'--';
            $rows->function_time=($rows->function_at)?date('h:i A',strtotime($rows->function_at)):'--';
            $rows->estimated_recovery_date=$rows->estimated_recovery_at?date('d-M-Y',strtotime($rows->estimated_recovery_at)):'--';
            $rows->estimated_recovery_time=$rows->estimated_recovery_at?date('h:i A',strtotime($rows->estimated_recovery_at)):'--';
            $rows->total_breakdown_hour=number_format($rows->total_breakdown_hour,0);
            $rows->pending_breakdown_hour=number_format($rows->pending_breakdown_hour,0);
            return $rows;
        });

		echo json_encode($rows);
	}

    public function getPurchaseRequisition(){
        $asset_breakdown_id=request('id', 0);

        $reason=array_prepend(config('bprs.reason'),'','');
        $rows=$this->assetbreakdown
        ->join('asset_quantity_costs',function($join){
          $join->on('asset_quantity_costs.id','=','asset_breakdowns.asset_quantity_cost_id');
        })
        ->join('asset_acquisitions',function($join){
          $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->join('inv_pur_req_asset_breakdowns',function($join){
            $join->on('asset_breakdowns.id','=','inv_pur_req_asset_breakdowns.asset_breakdown_id');
        })
        ->join('inv_pur_reqs',function($join){
            $join->on('inv_pur_reqs.id','=','inv_pur_req_asset_breakdowns.inv_pur_req_id');
        })
        ->leftJoin(\DB::raw("(select
            inv_pur_reqs.id as inv_pur_req_id,
            sum(inv_pur_req_items.amount) as amount
            from inv_pur_reqs
            join inv_pur_req_items on inv_pur_req_items.inv_pur_req_id=inv_pur_reqs.id
            group by inv_pur_reqs.id) puritem"), "puritem.inv_pur_req_id", "=", "inv_pur_reqs.id")
        ->leftJoin(\DB::raw("(select
            inv_pur_reqs.id as inv_pur_req_id,
            sum(inv_pur_req_paids.amount) as paid_amount
            from inv_pur_reqs
            join inv_pur_req_paids on inv_pur_req_paids.inv_pur_req_id=inv_pur_reqs.id
            group by inv_pur_reqs.id) puritempaid"), "puritempaid.inv_pur_req_id", "=", "inv_pur_reqs.id")
        ->where([['asset_breakdowns.id','=',$asset_breakdown_id]])
        ->get([
            'asset_breakdowns.id',
            'asset_breakdowns.reason_id',
            'asset_breakdowns.remarks',
            'asset_quantity_costs.custom_no',
            'asset_acquisitions.name as asset_name',
            'puritem.amount',
            'puritempaid.paid_amount',
        ])
        ->map(function ($rows) use($reason)  {
            $rows->reason=$reason[$rows->reason_id];
            $rows->balance_amount=$rows->amount-$rows->paid_amount;
            return $rows;
        })
        ->first();

        $paymode=config('bprs.paymode');

        $invpurreq=$this->assetbreakdown
        ->selectRaw('
            inv_pur_reqs.requisition_no,
            inv_pur_reqs.pay_mode,
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name,
            uoms.code as uom_code,
            inv_pur_req_items.item_account_id,
            podyechem.po_qty_dc,
            podyechem.po_rate_dc,
            podyechem.po_amount_dc,
            rcvdyechem.rcv_qty_dc,
            pogeneral.po_qty_gn,
            pogeneral.po_rate_gn,
            pogeneral.po_amount_gn,
            rcvgeneral.rcv_qty_gn,
            sum(inv_pur_req_items.qty) as req_qty,
            avg(inv_pur_req_items.rate) as req_rate,
            sum(inv_pur_req_items.amount) as req_amount
        ')
        ->join('inv_pur_req_asset_breakdowns',function($join){
            $join->on('asset_breakdowns.id','=','inv_pur_req_asset_breakdowns.asset_breakdown_id');
        })
        ->join('inv_pur_reqs',function($join){
            $join->on('inv_pur_reqs.id','=','inv_pur_req_asset_breakdowns.inv_pur_req_id');
        })
        ->join('inv_pur_req_items',function($join){
            $join->on('inv_pur_reqs.id','=','inv_pur_req_items.inv_pur_req_id');
        })
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_pur_req_items.item_account_id');
        })
        ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->leftJoin(\DB::raw("(
            select 
            inv_pur_req_items.item_account_id,
            sum(po_dye_chem_items.qty) as po_qty_dc,
            avg(po_dye_chem_items.rate) as po_rate_dc,
            sum(po_dye_chem_items.amount) as po_amount_dc
            from inv_pur_req_items
            join po_dye_chem_items on po_dye_chem_items.inv_pur_req_item_id=inv_pur_req_items.id
            where po_dye_chem_items.deleted_at is null
            group by 
            inv_pur_req_items.item_account_id
        ) podyechem"), "podyechem.item_account_id", "=", "inv_pur_req_items.item_account_id")

        ->leftJoin(\DB::raw("(
            select 
            inv_dye_chem_rcv_items.item_account_id,
            sum(inv_dye_chem_transactions.store_qty) as rcv_qty_dc
            from inv_dye_chem_rcv_items
            join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
            join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
            where  inv_rcvs.receive_basis_id in (1,2,3)
            and inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=1
            group by 
            inv_dye_chem_rcv_items.item_account_id
        ) rcvdyechem"), "podyechem.item_account_id", "=", "inv_pur_req_items.item_account_id")
        ->leftJoin(\DB::raw("(
            select 
            inv_pur_req_items.item_account_id,
            sum(po_general_items.qty) as po_qty_gn,
            avg(po_general_items.rate) as po_rate_gn,
            sum(po_general_items.amount) as po_amount_gn
            from inv_pur_req_items
            join po_general_items on po_general_items.inv_pur_req_item_id=inv_pur_req_items.id
            where po_general_items.deleted_at is null
            group by 
            inv_pur_req_items.item_account_id
        ) pogeneral"), "pogeneral.item_account_id", "=", "inv_pur_req_items.item_account_id")
        ->leftJoin(\DB::raw("(
            select 
            inv_general_rcv_items.item_account_id,
            sum(inv_general_transactions.store_qty) as rcv_qty_gn
            from inv_general_rcv_items
            join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
            join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
            where  inv_rcvs.receive_basis_id in (1,2,3)
            and inv_general_transactions.deleted_at is null
            and inv_general_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_general_transactions.trans_type_id=1
            group by 
            inv_general_rcv_items.item_account_id
        ) rcvgeneral"), "rcvgeneral.item_account_id", "=", "inv_pur_req_items.item_account_id")

        ->where([['inv_pur_req_asset_breakdowns.asset_breakdown_id','=',$asset_breakdown_id]])
        ->orderBy('item_accounts.item_description','desc')
        ->groupBy([
            'inv_pur_reqs.requisition_no',
            'inv_pur_reqs.pay_mode',
            'item_accounts.item_description',
            'item_accounts.specification',
            'item_accounts.sub_class_name',
            'uoms.code',
            'inv_pur_req_items.item_account_id',
            'podyechem.po_qty_dc',
            'podyechem.po_rate_dc',
            'podyechem.po_amount_dc',
            'rcvdyechem.rcv_qty_dc',
            'pogeneral.po_qty_gn',
            'pogeneral.po_rate_gn',
            'pogeneral.po_amount_gn',
            'rcvgeneral.rcv_qty_gn'
        ])
        ->get()
        ->map(function($invpurreq) use($paymode){
            $invpurreq->pay_mode=isset($paymode[$invpurreq->pay_mode])?$paymode[$invpurreq->pay_mode]:'';
            $invpurreq->item_desc=$invpurreq->sub_class_name.", ".$invpurreq->item_description.", ".$invpurreq->specification;
            $invpurreq->po_qty=$invpurreq->po_qty_dc?$invpurreq->po_qty_dc:$invpurreq->po_qty_gn;
            $invpurreq->po_rate=$invpurreq->po_rate_dc?$invpurreq->po_rate_dc:$invpurreq->po_rate_gn;
            $invpurreq->po_amount=$invpurreq->po_amount_dc?$invpurreq->po_amount_dc:$invpurreq->po_amount_gn;
            $invpurreq->rcv_qty=$invpurreq->rcv_qty_dc?$invpurreq->rcv_qty_dc:$invpurreq->rcv_qty_gn;
            return $invpurreq;
        });

        //dd($rows);
       // return Template::loadView('Report.FAM.AssetBreakdownPRMatrix',['rows'=>$rows]);

        $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT , PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->AddPage();
        $pdf->SetY(10);
        $pdf->SetFont('helvetica', 'N', 9);
        
        $view= \View::make('Defult.Report.FAM.AssetBreakdownPRMatrix',['rows'=>$rows,'invpurreq'=>$invpurreq]);
        $html_content=$view->render();
        //$pdf->SetY(55);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/AssetBreakdownPRMatrix.pdf';
        //$pdf->output($filename);
        $pdf->output($filename,'I');
        exit();
    }
	
	
}