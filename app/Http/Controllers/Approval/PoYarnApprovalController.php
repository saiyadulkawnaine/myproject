<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Sms;
class PoYarnApprovalController extends Controller
{
    private $poyarn;
    private $user;
    private $supplier;
    private $company;
    private $implc;
    private $implcpo;

    public function __construct(
		PoYarnRepository $poyarn,
		UserRepository $user,
		SupplierRepository $supplier,
		CompanyRepository $company,
        ImpLcRepository $implc,
        ImpLcPoRepository $implcpo

    ) {
        $this->poyarn = $poyarn;
        $this->user = $user;
        $this->supplier = $supplier;
        $this->company = $company;
        $this->implc = $implc;
        $this->implcpo = $implcpo;

        $this->middleware('auth');
        $this->middleware('permission:approve.poyarns',   ['only' => ['approved', 'index','reportData','reportDataApp']]);
        $this->middleware('permission:unapprove.poyarns',   ['only' => ['unapproved']]);

    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.PoYarnApproval',['company'=>$company,'supplier'=>$supplier]);
    }
	public function reportData() {
        $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $rows=$this->poyarn
        ->join('companies',function($join){
            $join->on('companies.id','=','po_yarns.company_id');
        })
        ->join('suppliers',function($join){
            $join->on('suppliers.id','=','po_yarns.supplier_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','po_yarns.currency_id');
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('po_yarns.company_id', '=',request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
        return $q->where('po_yarns.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('date_from'), function ($q) {
        return $q->where('po_yarns.po_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
        return $q->where('po_yarns.po_date', '<=',request('date_to', 0));
        })
        ->whereNull('po_yarns.approved_at')
        ->orderBy('po_yarns.id','desc')
        ->get([
            'po_yarns.*',
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
    	$master=$this->poyarn->find($id);
		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');

        $master->approved_by=$user->id;
        $master->approved_at=$approved_at;
        $master->unapproved_by=NULL;
        $master->unapproved_at=NULL;
        $master->timestamps=false;
        $poyarn=$master->save();
		

		if($poyarn){
		return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
		}
    }

    public function reportDataApp() {
        $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $rows=$this->poyarn
        ->selectRaw('
            po_yarns.id,
            po_yarns.po_no,
            po_yarns.po_date,
            po_yarns.pi_no,
            po_yarns.pi_date,
            po_yarns.source_id,
            po_yarns.pay_mode,
            po_yarns.amount,
            companies.code as company_code,
            suppliers.name as supplier_code,
            currencies.code as currency_code,
            implc.lc_date,
            implc.lc_no_i,
            implc.lc_no_ii,
            implc.lc_no_iii,
            implc.lc_no_iv, 
            yarn_rcv.rcv_qty,
            sum(po_yarn_items.qty) as po_qty
        ')
        ->leftJoin('po_yarn_items',function($join){
            $join->on('po_yarn_items.po_yarn_id','=','po_yarns.id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','po_yarns.company_id');
        })
        ->join('suppliers',function($join){
            $join->on('suppliers.id','=','po_yarns.supplier_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','po_yarns.currency_id');
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
            join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id
            where imp_lcs.menu_id=3 
        ) implc"), "po_yarns.id", "=", "implc.purchase_order_id")
        ->leftJoin(\DB::raw("(
            select 
            po_yarn_items.po_yarn_id,
            sum(inv_yarn_rcv_items.qty) as rcv_qty--,
            --sum(inv_yarn_rcv_items.amount) as rcv_amount,
            --sum(inv_yarn_transactions.store_qty) as store_qty,
            --sum(inv_yarn_transactions.store_amount) as rcv_amount_tk
            from po_yarn_items
            join inv_yarn_rcv_items on inv_yarn_rcv_items.po_yarn_item_id=po_yarn_items.id
            join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
            where inv_yarn_transactions.trans_type_id=1
            group by 
            po_yarn_items.po_yarn_id
        ) yarn_rcv"), "yarn_rcv.po_yarn_id", "=", "po_yarns.id")
        ->when(request('company_id'), function ($q) {
        return $q->where('po_yarns.company_id', '=',request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
        return $q->where('po_yarns.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('date_from'), function ($q) {
        return $q->where('po_yarns.po_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
        return $q->where('po_yarns.po_date', '<=',request('date_to', 0));
        })
        ->whereNotNull('po_yarns.approved_at')
        ->orderBy('po_yarns.id','desc')
        ->groupBy([
            'po_yarns.id',
            'po_yarns.po_no',
            'po_yarns.po_date',
            'po_yarns.pi_no',
            'po_yarns.pi_date',
            'po_yarns.source_id',
            'po_yarns.pay_mode',
            'po_yarns.amount',
            'companies.code',
            'suppliers.name',
            'currencies.code',
            'implc.lc_date',
            'implc.lc_no_i',
            'implc.lc_no_ii',
            'implc.lc_no_iii',
            'implc.lc_no_iv',
            'yarn_rcv.rcv_qty'

        ])
        ->get()
        ->map(function($rows)use($source,$paymode){
            $rows->source=$source[$rows->source_id];
            $rows->paymode=$paymode[$rows->pay_mode];
            $rows->amount=number_format($rows->amount);
            $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
            $rows->po_qty=number_format($rows->po_qty);
            $rows->rcv_qty=number_format($rows->rcv_qty);
            $rows->delv_start_date=$rows->delv_start_date?date('d-M-Y',strtotime($rows->delv_start_date)):'--';
            $rows->delv_end_date=$rows->delv_end_date?date('d-M-Y',strtotime($rows->delv_end_date)):'--';
            $rows->lc_date=$rows->lc_date?date('d-M-Y',strtotime($rows->lc_date)):'';
            $rows->lc_no=$rows->lc_no_i?$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv." ".$rows->lc_date."":'--';
            return $rows;
        });
        echo json_encode($rows);
    }

    public function unapproved (Request $request)
    {
        $id=request('id',0);
        $master=$this->poyarn->find($id);
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
        ->where([['imp_lcs.menu_id','=',3]])
        ->where([['imp_lc_pos.purchase_order_id','=',$id]])
        ->get(['imp_lc_pos.purchase_order_id'])
        ->first();
        if($implcpo){
            return response()->json(array('success' => false,  'message' => 'LC Found. Untag PO from LC first'), 200);
        }

        $poyarn=$master->save();


        if($poyarn){
        return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
        }
    }

    public function getRcvNo(){
        $po_yarn_id=request('id',0);
        $yarnrcv=$this->poyarn
        ->leftJoin('po_yarn_items',function($join){
            $join->on('po_yarn_items.po_yarn_id','=','po_yarns.id');
        })
        ->join('inv_yarn_rcv_items',function($join){
            $join->on('po_yarn_items.id','=','inv_yarn_rcv_items.po_yarn_item_id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
        })
        ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id');
        })
        ->join('inv_rcvs',function($join){
            $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->where([['po_yarns.id','=',$po_yarn_id]])
        ->get([
            'po_yarn_items.id as po_item_id',
            'inv_rcvs.receive_no',
            'inv_rcvs.receive_date',
            'inv_rcvs.challan_no',
            'inv_rcvs.remarks',
            'inv_yarn_rcv_items.id as inv_rcv_item_id',
            'inv_yarn_rcv_items.qty',
            'inv_yarn_rcv_items.rate',
            'inv_yarn_rcv_items.amount',
            'inv_yarn_rcv_items.store_qty',
            'inv_yarn_rcv_items.store_amount',
        ])
        ->map(function($yarnrcv){
            $yarnrcv->receive_date=date('d-M-Y',strtotime($yarnrcv->receive_date));
            $yarnrcv->qty=number_format($yarnrcv->qty,2);
            $yarnrcv->rate=number_format($yarnrcv->rate,4);
            $yarnrcv->amount=number_format($yarnrcv->amount,2);
            $yarnrcv->store_qty=number_format($yarnrcv->store_qty,2);
            $yarnrcv->store_amount=number_format($yarnrcv->store_amount,2);
            return $yarnrcv;
        });
        echo json_encode($yarnrcv);
        
    }
}
