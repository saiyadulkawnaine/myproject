<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Purchase\PoGeneralRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Sms;

class PoGeneralApprovalController extends Controller
{
    private $pogeneral;
    private $user;
    private $supplier;
    private $company;
    private $implc;
    private $implcpo;

    public function __construct(
		PoGeneralRepository $pogeneral,
		UserRepository $user,
		SupplierRepository $supplier,
		CompanyRepository $company,
        ImpLcRepository $implc,
        ImpLcPoRepository $implcpo

    ) {
        $this->pogeneral = $pogeneral;
        $this->user = $user;
        $this->supplier = $supplier;
        $this->company = $company;
        $this->implc = $implc;
        $this->implcpo = $implcpo;

        $this->middleware('auth');
       $this->middleware('permission:approve.pogenerals',   ['only' => ['approved', 'index','reportData','reportDataApp']]);
        $this->middleware('permission:unapprove.pogenerals',   ['only' => [ 'unapproved']]);

    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.PoGeneralApproval',['company'=>$company,'supplier'=>$supplier]);
    }

	public function reportData() {
        $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $rows=$this->pogeneral
        ->join('companies',function($join){
            $join->on('companies.id','=','po_generals.company_id');
        })
        ->join('suppliers',function($join){
            $join->on('suppliers.id','=','po_generals.supplier_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','po_generals.currency_id');
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('po_generals.company_id', '=',request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
        return $q->where('po_generals.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('date_from'), function ($q) {
        return $q->where('po_generals.po_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
        return $q->where('po_generals.po_date', '<=',request('date_to', 0));
        })
        ->whereNull('po_generals.approved_at')
        ->orderBy('po_generals.id','desc')
        ->get([
            'po_generals.*',
            'companies.name as company_code',
            'suppliers.name as supplier_code',
            'currencies.code as currency_code',
        ])
        ->map(function($rows)use($source,$paymode){
            $rows->source=$source[$rows->source_id];
            $rows->paymode=$paymode[$rows->pay_mode];
            $rows->amount=number_format($rows->amount);
            $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
            $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
            $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
            return $rows;
        });
        echo json_encode($rows);
    }

    public function approved (Request $request)
    {
    	$id=request('id',0);
    	$master=$this->pogeneral->find($id);
		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');

        $master->approved_by=$user->id;
        $master->approved_at=$approved_at;
        $master->unapproved_by=NULL;
        $master->unapproved_at=NULL;
        $master->timestamps=false;
        $pogeneral=$master->save();
		

		if($pogeneral){
		return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
		}
    }

    public function reportDataApp() {
        $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $rows=$this->pogeneral
        ->selectRaw('
            po_generals.id,
            po_generals.po_no,
            po_generals.po_date,
            po_generals.pi_no,
            po_generals.pi_date,
            po_generals.source_id,
            po_generals.pay_mode,
            po_generals.amount,
            companies.code as company_code,
            suppliers.name as supplier_code,
            currencies.code as currency_code,
            implc.lc_date,
            implc.lc_no_i,
            implc.lc_no_ii,
            implc.lc_no_iii,
            implc.lc_no_iv, 
            general_rcv.rcv_qty,
            sum(po_general_items.qty) as po_qty
        ')
        ->leftJoin('po_general_items',function($join){
            $join->on('po_general_items.po_general_id','=','po_generals.id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','po_generals.company_id');
        })
        ->join('suppliers',function($join){
            $join->on('suppliers.id','=','po_generals.supplier_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','po_generals.currency_id');
        })
        ->leftJoin(\DB::raw("(
            select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_date,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            from  imp_lcs 
            join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id 
            join po_generals on po_generals.id=imp_lc_pos.purchase_order_id
            where imp_lcs.menu_id=8
        ) implc"), "po_generals.id", "=", "implc.purchase_order_id")
        ->leftJoin(\DB::raw("(
            select 
            po_general_items.po_general_id,
            sum(inv_general_rcv_items.qty) as rcv_qty--,
            --sum(inv_general_rcv_items.amount) as rcv_amount,
            --sum(inv_general_transactions.store_qty) as store_qty,
            --sum(inv_general_transactions.store_amount) as rcv_amount_tk
            from po_general_items
            join inv_general_rcv_items on inv_general_rcv_items.po_general_item_id=po_general_items.id
            join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
            where inv_general_transactions.trans_type_id=1
            group by 
            po_general_items.po_general_id
        ) general_rcv"), "general_rcv.po_general_id", "=", "po_generals.id")
        ->when(request('company_id'), function ($q) {
        return $q->where('po_generals.company_id', '=',request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
        return $q->where('po_generals.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('date_from'), function ($q) {
        return $q->where('po_generals.po_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
        return $q->where('po_generals.po_date', '<=',request('date_to', 0));
        })
        ->whereNotNull('po_generals.approved_at')
        ->orderBy('po_generals.id','desc')
        ->groupBy([
            'po_generals.id',
            'po_generals.po_no',
            'po_generals.po_date',
            'po_generals.pi_no',
            'po_generals.pi_date',
            'po_generals.source_id',
            'po_generals.pay_mode',
            'po_generals.amount',
            'companies.code',
            'suppliers.name',
            'currencies.code',
            'implc.lc_date',
            'implc.lc_no_i',
            'implc.lc_no_ii',
            'implc.lc_no_iii',
            'implc.lc_no_iv',
            'general_rcv.rcv_qty'
        ])
        ->get()
        ->map(function($rows)use($source,$paymode){
            $rows->source=$source[$rows->source_id];
            $rows->paymode=$paymode[$rows->pay_mode];
            $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
            $rows->amount=number_format($rows->amount);
            $rows->po_qty=number_format($rows->po_qty);
            $rows->rcv_qty=number_format($rows->rcv_qty);
            $rows->delv_start_date=$rows->delv_start_date?date('d-M-Y',strtotime($rows->delv_start_date)):'--';
            $rows->delv_end_date=$rows->delv_end_date?date('d-M-Y',strtotime($rows->delv_end_date)):'--';
            $rows->lc_date=$rows->lc_date?date('d-M-Y',strtotime($rows->lc_date)):'';
            $rows->lc_no=$rows->lc_no_i?$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv."; ".$rows->lc_date."":'--';
            return $rows;
        });
        echo json_encode($rows);
    }

    public function unapproved (Request $request)
    {
        $id=request('id',0);
        $master=$this->pogeneral->find($id);
        $user = \Auth::user();
        $unapproved_at=date('Y-m-d h:i:s');
        $unapproved_count=$master->unapproved_count+1;
        $master->approved_by=NUll;
        $master->approved_at=NUll;
        $master->unapproved_by=$user->id;
        $master->unapproved_at=$unapproved_at;
        $master->unapproved_count=$unapproved_count;
        $master->timestamps=false;

        $implcpo=$this->implcpo
        ->join('imp_lcs',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
        })
        ->where([['imp_lcs.menu_id','=',8]])
        ->where([['imp_lc_pos.purchase_order_id','=',$id]])
        ->get(['imp_lc_pos.purchase_order_id'])
        ->first();
        if($implcpo){
            return response()->json(array('success' => false,  'message' => 'LC Found. Untag PO from LC first'), 200);
        }

        $pogeneral=$master->save();

        if($pogeneral){
        return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
        }
    }

    public function getRcvNo(){
        $po_general_id=request('id',0);
        $generalrcv=$this->pogeneral
        ->leftJoin('po_general_items',function($join){
            $join->on('po_general_items.po_general_id','=','po_generals.id');
        })
        ->join('inv_general_rcv_items',function($join){
            $join->on('po_general_items.id','=','inv_general_rcv_items.po_general_item_id')
            ->whereNull('inv_general_rcv_items.deleted_at');
        })
        ->join('inv_general_rcvs',function($join){
            $join->on('inv_general_rcv_items.inv_general_rcv_id','=','inv_general_rcvs.id');
        })
        ->join('inv_rcvs',function($join){
            $join->on('inv_general_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->where([['po_generals.id','=',$po_general_id]])
        ->get([
            'po_general_items.id as po_item_id',
            'inv_rcvs.receive_no',
            'inv_rcvs.receive_date',
            'inv_rcvs.challan_no',
            'inv_rcvs.remarks',
            'inv_general_rcv_items.id as inv_rcv_item_id',
            'inv_general_rcv_items.qty',
            'inv_general_rcv_items.rate',
            'inv_general_rcv_items.amount',
            'inv_general_rcv_items.store_qty',
            'inv_general_rcv_items.store_amount',
        ])
        ->map(function($generalrcv){
            $generalrcv->receive_date=date('d-M-Y',strtotime($generalrcv->receive_date));
            $generalrcv->qty=number_format($generalrcv->qty,2);
            $generalrcv->rate=number_format($generalrcv->rate,4);
            $generalrcv->amount=number_format($generalrcv->amount,2);
            $generalrcv->store_qty=number_format($generalrcv->store_qty,2);
            $generalrcv->store_amount=number_format($generalrcv->store_amount,2);
            return $generalrcv;
        });
        echo json_encode($generalrcv);
        
    }
    
}
