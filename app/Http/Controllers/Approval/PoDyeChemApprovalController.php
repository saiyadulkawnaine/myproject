<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Purchase\PoDyeChemRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Sms;
use Illuminate\Support\Carbon;
use App\Library\Numbertowords;

class PoDyeChemApprovalController extends Controller
{
    private $podyechem;
    private $company;
    private $supplier;
    private $currency;
    private $itemcategory;
    private $termscondition;
    private $purchasetermscondition;
    private $itemclass;
    private $implc;
    private $implcpo;

 public function __construct(
	PoDyeChemRepository $podyechem,
    CompanyRepository $company,
    SupplierRepository $supplier,
    BuyerRepository $buyer,
    CurrencyRepository $currency,
    ItemcategoryRepository $itemcategory,
    TermsConditionRepository $termscondition,
    PurchaseTermsConditionRepository $purchasetermscondition,
    ItemclassRepository $itemclass,
    ImpLcRepository $implc,
    ImpLcPoRepository $implcpo

 ) {
    $this->podyechem = $podyechem;
    $this->company = $company;
    $this->supplier = $supplier;
    $this->buyer = $buyer;
    $this->currency = $currency;
    $this->itemcategory = $itemcategory;
    $this->termscondition = $termscondition;
    $this->purchasetermscondition = $purchasetermscondition;
    $this->itemclass     = $itemclass;
    $this->implc = $implc;
    $this->implcpo = $implcpo;

    $this->middleware('auth');
      
    $this->middleware('permission:approve.podyechems',   ['only' => ['approved', 'index','reportData','reportDataApp']]);
    $this->middleware('permission:unapprove.podyechems',   ['only' => [ 'unapproved']]);
  }
    
  public function index() {
   $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
   $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
   $supplier=array_prepend(array_pluck($this->supplier->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
   $menu=array_prepend(config('bprs.menu'),'-Select-','');
   return Template::loadView('Approval.PoDyeChemApproval',['company'=>$company,'buyer'=>$buyer,'supplier'=>$supplier,'menu'=>$menu]);
  }

  public function reportData() {
   $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->podyechem
      ->join('companies',function($join){
        $join->on('companies.id','=','po_dye_chems.company_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_dye_chems.supplier_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_dye_chems.currency_id');
      })
      ->leftJoin('po_dye_chem_items', function($join){
        $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
      })
      ->when(request('company_id'), function ($q) {
        return $q->where('po_dye_chems.company_id', '=',request('company_id', 0));
      })
      ->when(request('supplier_id'), function ($q) {
        return $q->where('po_dye_chems.supplier_id', '=',request('supplier_id', 0));
      })
      ->when(request('date_from'), function ($q) {
        return $q->where('po_dye_chems.po_date', '>=',request('date_from', 0));
      })
      ->when(request('date_to'), function ($q) {
        return $q->where('po_dye_chems.po_date', '<=',request('date_to', 0));
      })
      ->whereNull('po_dye_chems.approved_by')
      ->where([['po_type_id', 1]])
      ->orderBy('po_dye_chems.id','desc')
      ->selectRaw('
        po_dye_chems.id,
        po_dye_chems.po_no,
        po_dye_chems.po_date,
        po_dye_chems.pi_no,
        po_dye_chems.company_id,
        po_dye_chems.supplier_id,
        po_dye_chems.source_id,
        po_dye_chems.pay_mode,
        po_dye_chems.delv_start_date,
        po_dye_chems.delv_end_date,
        po_dye_chems.exch_rate,
        companies.code as company_code,
        suppliers.name as supplier_code,
        currencies.code as currency_code,
        sum(po_dye_chem_items.qty) as item_qty,
        po_dye_chems.amount
      ')
      ->groupBy([
        'po_dye_chems.id',
        'po_dye_chems.po_no',
        'po_dye_chems.po_date',
        'po_dye_chems.pi_no',
        'po_dye_chems.company_id',
        'po_dye_chems.supplier_id',
        'po_dye_chems.source_id',
        'po_dye_chems.pay_mode',
        'po_dye_chems.delv_start_date',
        'po_dye_chems.delv_end_date',
        'po_dye_chems.exch_rate',
        'companies.code',
        'suppliers.name',
        'currencies.code',
        'po_dye_chems.amount'
      ])
      ->get()
      ->map(function($rows) use($source,$paymode){
        $rows->source=isset($source[$rows->source_id])?$source[$rows->source_id]:'';
        $rows->paymode=isset($paymode[$rows->pay_mode])?$paymode[$rows->pay_mode]:'';
        $rows->item_qty = number_format($rows->item_qty,2);
        $rows->amount = number_format($rows->amount,2);
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
      $master=$this->podyechem->find($id);
      $user = \Auth::user();
      $approved_at=date('Y-m-d h:i:s');
      $master->approved_by=$user->id;
      $master->approved_at=$approved_at;
      $master->unapproved_by=NULL;
      $master->unapproved_at=NULL;
      $master->timestamps=false;

      $implcpo=$this->implcpo
        ->join('imp_lcs',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
        })
        ->where([['imp_lcs.menu_id','=',7]])
        ->where([['imp_lc_pos.purchase_order_id','=',$id]])
        ->get(['imp_lc_pos.purchase_order_id'])
        ->first();
        if($implcpo){
            return response()->json(array('success' => false,  'message' => 'LC Found. Untag PO from LC first'), 200);
        }

      $podyechem=$master->save();
      if($podyechem){
        return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
      }
   }

   public function reportDataApp() {
      $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->podyechem
      ->join('companies',function($join){
        $join->on('companies.id','=','po_dye_chems.company_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_dye_chems.supplier_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_dye_chems.currency_id');
      })
      ->leftJoin('po_dye_chem_items', function($join){
        $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
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
        join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id
        where imp_lcs.menu_id=7
    ) implc"), "po_dye_chems.id", "=", "implc.purchase_order_id")
    ->leftJoin(\DB::raw("(
        select 
        po_dye_chem_items.po_dye_chem_id,
        sum(inv_dye_chem_rcv_items.qty) as rcv_qty--,
        --sum(inv_dye_chem_rcv_items.amount) as rcv_amount,
        --sum(inv_dye_chem_transactions.store_qty) as store_qty,
        --sum(inv_dye_chem_transactions.store_amount) as rcv_amount_tk
        from po_dye_chem_items
        join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.po_dye_chem_item_id=po_dye_chem_items.id
        join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
        where inv_dye_chem_transactions.trans_type_id=1
        group by 
        po_dye_chem_items.po_dye_chem_id
    ) dye_chem_rcv"), "dye_chem_rcv.po_dye_chem_id", "=", "po_dye_chems.id")
      ->when(request('company_id'), function ($q) {
        return $q->where('po_dye_chems.company_id', '=',request('company_id', 0));
      })
      ->when(request('supplier_id'), function ($q) {
        return $q->where('po_dye_chems.supplier_id', '=',request('supplier_id', 0));
      })
      ->when(request('date_from'), function ($q) {
        return $q->where('po_dye_chems.po_date', '>=',request('date_from', 0));
      })
      ->when(request('date_to'), function ($q) {
        return $q->where('po_dye_chems.po_date', '<=',request('date_to', 0));
      })
      ->whereNotNull('po_dye_chems.approved_by')
      ->where([['po_type_id', 1]])
      ->orderBy('po_dye_chems.id','desc')
      ->selectRaw('
        po_dye_chems.id,
        po_dye_chems.po_no,
        po_dye_chems.po_date,
        po_dye_chems.pi_no,
        po_dye_chems.company_id,
        po_dye_chems.supplier_id,
        po_dye_chems.source_id,
        po_dye_chems.pay_mode,
        po_dye_chems.delv_start_date,
        po_dye_chems.delv_end_date,
        po_dye_chems.exch_rate,
        companies.code as company_code,
        suppliers.name as supplier_code,
        currencies.code as currency_code,
        implc.lc_date,
        implc.lc_no_i,
        implc.lc_no_ii,
        implc.lc_no_iii,
        implc.lc_no_iv, 
        dye_chem_rcv.rcv_qty,
        po_dye_chems.amount,
        sum(po_dye_chem_items.qty) as item_qty
      ')
      ->groupBy([
        'po_dye_chems.id',
        'po_dye_chems.po_no',
        'po_dye_chems.po_date',
        'po_dye_chems.pi_no',
        'po_dye_chems.company_id',
        'po_dye_chems.supplier_id',
        'po_dye_chems.source_id',
        'po_dye_chems.pay_mode',
        'po_dye_chems.delv_start_date',
        'po_dye_chems.delv_end_date',
        'po_dye_chems.exch_rate',
        'companies.code',
        'suppliers.name',
        'currencies.code',
        'implc.lc_date',
        'implc.lc_no_i',
        'implc.lc_no_ii',
        'implc.lc_no_iii',
        'implc.lc_no_iv',
        'dye_chem_rcv.rcv_qty',
        'po_dye_chems.amount'
      ])
      ->get()
      ->map(function($rows) use($source,$paymode){
        $rows->source=isset($source[$rows->source_id])?$source[$rows->source_id]:'';
        $rows->paymode=isset($paymode[$rows->pay_mode])?$paymode[$rows->pay_mode]:'';
        $rows->item_qty = number_format($rows->item_qty,2);
        $rows->amount = number_format($rows->amount,2);
        $rows->rcv_qty=number_format($rows->rcv_qty);
        $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
        $rows->delv_start_date=$rows->delv_start_date?date('d-M-Y',strtotime($rows->delv_start_date)):'--';
        $rows->delv_end_date=$rows->delv_end_date?date('d-M-Y',strtotime($rows->delv_end_date)):'--';
        $rows->lc_date=$rows->lc_date?date('d-M-Y',strtotime($rows->lc_date)):'';
        $rows->lc_no=$rows->lc_no_i?$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv."; ".$rows->lc_date."":'--';
        return $rows;
      });
      echo json_encode($rows);
    }

   public function unapproved (Request $request){
     $id=request('id',0);
     $master=$this->podyechem->find($id);
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
     ->where([['imp_lcs.menu_id','=',7]])
     ->where([['imp_lc_pos.purchase_order_id','=',$id]])
     ->get(['imp_lc_pos.purchase_order_id'])
     ->first();
     if($implcpo){
        return response()->json(array('success' => false,  'message' => 'LC Found. Untag PO from LC first'), 200);
     }

     $podyechem=$master->save();

     if($podyechem){
         return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
     }
   }

   public function getRcvNo(){
    $po_dye_chem_id=request('id',0);
    $dye_chemrcv=$this->podyechem
    ->leftJoin('po_dye_chem_items',function($join){
        $join->on('po_dye_chem_items.po_dye_chem_id','=','po_dye_chems.id');
    })
    ->join('inv_dye_chem_rcv_items',function($join){
        $join->on('po_dye_chem_items.id','=','inv_dye_chem_rcv_items.po_dye_chem_item_id')
        ->whereNull('inv_dye_chem_rcv_items.deleted_at');
    })
    ->join('inv_dye_chem_rcvs',function($join){
        $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id');
    })
    ->join('inv_rcvs',function($join){
        $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
    })
    ->where([['po_dye_chems.id','=',$po_dye_chem_id]])
    ->get([
        'po_dye_chem_items.id as po_item_id',
        'inv_rcvs.receive_no',
        'inv_rcvs.receive_date',
        'inv_rcvs.challan_no',
        'inv_rcvs.remarks',
        'inv_dye_chem_rcv_items.id as inv_rcv_item_id',
        'inv_dye_chem_rcv_items.qty',
        'inv_dye_chem_rcv_items.rate',
        'inv_dye_chem_rcv_items.amount',
        'inv_dye_chem_rcv_items.store_qty',
        'inv_dye_chem_rcv_items.store_amount',
    ])
    ->map(function($dye_chemrcv){
        $dye_chemrcv->receive_date=date('d-M-Y',strtotime($dye_chemrcv->receive_date));
        $dye_chemrcv->qty=number_format($dye_chemrcv->qty,2);
        $dye_chemrcv->rate=number_format($dye_chemrcv->rate,4);
        $dye_chemrcv->amount=number_format($dye_chemrcv->amount,2);
        $dye_chemrcv->store_qty=number_format($dye_chemrcv->store_qty,2);
        $dye_chemrcv->store_amount=number_format($dye_chemrcv->store_amount,2);
        return $dye_chemrcv;
    });
    echo json_encode($dye_chemrcv);
    
}


}
