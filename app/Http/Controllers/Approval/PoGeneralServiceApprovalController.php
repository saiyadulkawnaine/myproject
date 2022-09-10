<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Purchase\PoGeneralServiceRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Sms;
class PoGeneralServiceApprovalController extends Controller
{
    private $pogeneralservice;
    private $user;
    private $supplier;
    private $company;
    private $implc;
    private $implcpo;

    public function __construct(
		PoGeneralServiceRepository $pogeneralservice,
		UserRepository $user,
		SupplierRepository $supplier,
		CompanyRepository $company,
        ImpLcRepository $implc,
        ImpLcPoRepository $implcpo

    ) {
        $this->pogeneralservice = $pogeneralservice;
        $this->user = $user;
        $this->supplier = $supplier;
        $this->company = $company;
        $this->implc = $implc;
        $this->implcpo = $implcpo;

        $this->middleware('auth');
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.PoGeneralServiceApproval',['company'=>$company,'supplier'=>$supplier]);
    }
	public function reportData() {
        $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $rows=$this->pogeneralservice
        ->join('companies',function($join){
            $join->on('companies.id','=','po_general_services.company_id');
        })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_general_services.supplier_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','po_general_services.currency_id');
        })
        ->when(request('company_id'), function ($q) {
            return $q->where('po_general_services.company_id', '=',request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
            return $q->where('po_general_services.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('po_general_services.po_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('po_general_services.po_date', '<=',request('date_to', 0));
        })
        ->whereNull('po_general_services.approved_at')
        ->orderBy('po_general_services.id','desc')
        ->get([
            'po_general_services.*',
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
    	$master=$this->pogeneralservice->find($id);
		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');

        $master->approved_by=$user->id;
        $master->approved_at=$approved_at;
        $master->unapproved_by=NULL;
        $master->unapproved_at=NULL;
        $master->timestamps=false;
        $pogeneralservice=$master->save();
		

		if($pogeneralservice){
		return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
		}
    }

    public function reportDataApp() {
        $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $rows=$this->pogeneralservice
        ->join('companies',function($join){
            $join->on('companies.id','=','po_general_services.company_id');
        })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_general_services.supplier_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','po_general_services.currency_id');
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('po_general_services.company_id', '=',request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
        return $q->where('po_general_services.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('date_from'), function ($q) {
        return $q->where('po_general_services.po_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
        return $q->where('po_general_services.po_date', '<=',request('date_to', 0));
        })
        ->whereNotNull('po_general_services.approved_at')
        ->orderBy('po_general_services.id','desc')
        ->get([
            'po_general_services.*',
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

    public function unapproved (Request $request)
    {
        $id=request('id',0);
        $master=$this->pogeneralservice->find($id);
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
        ->where([['imp_lcs.menu_id','=',11]])
        ->where([['imp_lc_pos.purchase_order_id','=',$id]])
        ->get(['imp_lc_pos.purchase_order_id'])
        ->first();
        if($implcpo){
            return response()->json(array('success' => false,  'message' => 'LC Found. Untag PO from LC first'), 200);
        }

        $pogeneralservice=$master->save();


        if($pogeneralservice){
        return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
        }
    }
}
