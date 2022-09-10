<?php
namespace App\Http\Controllers\Report\Commercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Purchase\PoDyeChemRepository;
use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Repositories\Contracts\Purchase\PoGeneralRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Purchase\PoAopServiceRepository;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Contracts\Purchase\PoGeneralServiceRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
//use App\Library\Numbertowords;
use Illuminate\Support\Carbon;

class PendingImpLcPoReportController extends Controller
{
    private $supplier;
    private $salesorder;
    private $company;
    private $itemaccount;
    private $purchaseorder;
    private $currency;

    
    public function __construct(SupplierRepository $supplier,
    ItemAccountRepository $itemaccount,
    SalesOrderRepository $salesorder, 
    CompanyRepository $company, 
    UomRepository $uom,
    CurrencyRepository $currency,
    PoFabricRepository $pofabric,
    PoTrimRepository $potrim,
    PoYarnRepository $poyarn,
    PoAopServiceRepository $poaopservice,
    PoKnitServiceRepository $poknitservice,
    PoDyeingServiceRepository $podyeingservice,
    PoDyeChemRepository $podyechem,
    PoGeneralRepository $pogeneral,
    PoEmbServiceRepository $poembservice,
    PoYarnDyeingRepository $poyarndyeing,
    PoGeneralServiceRepository $pogeneralservice,
    ImpLcPoRepository $implcpo,
    ImpLcRepository $implc
    
  ) {
        $this->supplier = $supplier;
        $this->itemaccount = $itemaccount;
        $this->salesorder = $salesorder;
        $this->company = $company;
        $this->uom = $uom;
        $this->currency= $currency;
        $this->pofabric = $pofabric;
        $this->potrim= $potrim;
        $this->poyarn= $poyarn;
        $this->poknitservice= $poknitservice;
        $this->podyeingservice= $podyeingservice;
        $this->poaopservice= $poaopservice;
        $this->poembservice= $poembservice;        
        $this->podyechem= $podyechem;
        $this->pogeneral= $pogeneral;
        $this->poyarndyeing= $poyarndyeing;
        $this->pogeneralservice = $pogeneralservice;
        $this->implcpo= $implcpo;
        $this->implc= $implc;


        $this->middleware('auth');
        //$this->middleware('permission:view.pendingimplcporeport',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
        //$productionarea=array_prepend(array_only(config('bprs.productionarea'),[5,10,20,25,45,50]),'-Select-','');
        $menu=array_prepend(array_only(config('bprs.menu'),[1,2,3,4,5,6,7,8,9,10,11]),'-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-','');
        //$indentor=array_prepend(array_pluck($this->supplier->indentor(),'name','id'),'-Select-','');     
        return Template::loadView('Report.Commercial.PendingImpLcPoReport',['company'=>$company,'supplier'=>$supplier,'menu'=>$menu,'payterm'=>$payterm,'incoterm'=>$incoterm,'yesno'=>$yesno/* ,'indentor'=>$indentor */]);
    }

    
    public function reportData() {

      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $indentor=array_prepend(array_pluck($this->supplier->indentor(),'name','id'),'--','');

        $menu_id=request('menu_id',0);
        $company_id=request('company_id',0);
        $supplier_id=request('supplier_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $pi_no=request('pi_no',0);
      //Fabric Purchase Order
      if ($menu_id==1) {
        $purchaseorder =$this->pofabric
        ->selectRaw('
          po_fabrics.id,
          po_fabrics.company_id,
          po_fabrics.supplier_id,
          po_fabrics.pi_no,
          po_fabrics.pi_date as fabric_pi_date,
          po_fabrics.po_no,
          po_fabrics.po_date as fabric_po_date,
          po_fabrics.amount as fabric_amount,
          po_fabrics.pay_mode as fabric_pay_mode,
          po_fabrics.exch_rate,
          po_fabrics.delv_start_date as fabric_delv_start_date,
          po_fabrics.delv_end_date as fabric_delv_end_date,
          po_fabrics.currency_id,
          po_fabrics.remarks,
          companies.code as company_code,
          currencies.code as currency_code,
          suppliers.code as supplier_code
        ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('po_fabrics.id','=','imp_lc_pos.purchase_order_id');
        })
        ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id')->where([['imp_lcs.menu_id', '=', 1]]);
        })
        ->leftJoin('companies',function($join){
          $join->on('companies.id','=','po_fabrics.company_id');
        })
        ->leftJoin('suppliers',function($join){
          $join->on('suppliers.id','=','po_fabrics.supplier_id');
        })
        ->leftJoin('currencies',function($join){
          $join->on('currencies.id','=','po_fabrics.currency_id');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('po_fabrics.pi_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('po_fabrics.pi_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) use($company_id) {
          return $q->where('po_fabrics.company_id','=',$company_id);
        })
        ->when(request('supplier_id'), function ($q) use($supplier_id) {
          return $q->where('po_fabrics.supplier_id','=',$supplier_id);
        })
        ->when(request('pi_no'), function ($q) use($pi_no) {
          return $q->where('po_fabrics.pi_no', 'LIKE', "%".$pi_no."%");
        })
        // ->whereNull('imp_lc_pos.purchase_order_id')

        
        ->whereNull('imp_lcs.lc_date')
        ->whereNotNull('po_fabrics.pi_no')
        ->whereNotNull('po_fabrics.pi_date')
        ->get()
        ->map(function($purchaseorder)  use($paymode) {
          $purchaseorder->amount=number_format($purchaseorder->fabric_amount,2);
          $purchaseorder->menu_id='Fabric Purchase';
          $purchaseorder->pi_date=date('d-M-Y',strtotime($purchaseorder->fabric_pi_date));
          $purchaseorder->po_date=$purchaseorder->fabric_po_date?date('d-M-Y',strtotime($purchaseorder->fabric_po_date)):'--';
          $purchaseorder->pay_mode=isset($paymode[$purchaseorder->fabric_pay_mode])?$paymode[$purchaseorder->fabric_pay_mode]:'--';
          $purchaseorder->delv_start_date=date('d-M-Y',strtotime($purchaseorder->fabric_delv_start_date));
          $purchaseorder->delv_start_end=date('d-M-Y',strtotime($purchaseorder->fabric_delv_end_date));
          $pi_date = Carbon::parse($purchaseorder->fabric_pi_date);
          $today = Carbon::now();
          $waitingDays = $pi_date->diffInDays($today)+1;
          $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';

          return $purchaseorder;
          
        });
        echo json_encode($purchaseorder);
      }
      //Trims Purchase Order
      if($menu_id==2){
        $purchaseorder= $this->potrim
          ->selectRaw('
            po_trims.id,
            po_trims.company_id,
            po_trims.supplier_id,
            po_trims.pi_no,
            po_trims.pi_date as trim_pi_date,
            po_trims.po_no,
            po_trims.po_date as trim_po_date,
            po_trims.amount as trim_amount,
            po_trims.pay_mode as trim_pay_mode,
            po_trims.exch_rate,
            po_trims.delv_start_date as trim_delv_start_date,
            po_trims.delv_end_date as trim_delv_end_date,
            po_trims.indentor_id as trim_indentor_id,
            po_trims.currency_id,
            po_trims.remarks,
            companies.code as company_code,
            currencies.code as  currency_code,
            suppliers.name as  supplier_code
          ')
          ->leftJoin('imp_lc_pos',function($join){
            $join->on('po_trims.id','=','imp_lc_pos.purchase_order_id');
          })
          ->leftJoin('imp_lcs',function($join){
            $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id')->where([['imp_lcs.menu_id', '=', 2]]);
          })
          ->leftJoin('companies',function($join){
            $join->on('companies.id','=','po_trims.company_id');
          })
          ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_trims.supplier_id');
          })
          ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_trims.currency_id');
          })
          ->when(request('date_from'), function ($q) {
            return $q->where('po_trims.pi_date', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
            return $q->where('po_trims.pi_date', '<=',request('date_to', 0));
          })
          ->when(request('company_id'), function ($q) use($company_id) {
            return $q->where('po_trims.company_id','=',$company_id);
          })
          ->when(request('supplier_id'), function ($q) use($supplier_id) {
            return $q->where('po_trims.supplier_id','=',$supplier_id);
          })
          ->when(request('pi_no'), function ($q) use($pi_no) {
            return $q->where('po_trims.pi_no', 'LIKE', "%".$pi_no."%");
          })
          // ->whereNull('imp_lc_pos.purchase_order_id')

          
          ->whereNotNull('po_trims.pi_no')
          ->whereNotNull('po_trims.pi_date')
          ->whereNull('imp_lcs.lc_date')
          ->get()
          ->map(function($purchaseorder) use($indentor,$paymode){
            $purchaseorder->menu_id='Trims';
            $purchaseorder->pi_date=date('d-M-Y',strtotime($purchaseorder->trim_pi_date));
            $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->trim_po_date));
            $purchaseorder->paymode=isset($paymode[$purchaseorder->trim_pay_mode])?$paymode[$purchaseorder->trim_pay_mode]:'--';
            $purchaseorder->delv_start_date=$purchaseorder->trim_delv_start_date?date('d-M-Y',strtotime($purchaseorder->trim_delv_start_date)):'--';
            $purchaseorder->delv_start_end=$purchaseorder->trim_delv_end_date?date('d-M-Y',strtotime($purchaseorder->trim_delv_end_date)):'--';
            $purchaseorder->indentor_id=isset($indentor[$purchaseorder->trim_indentor_id])?$indentor[$purchaseorder->trim_indentor_id]:'--';
            $purchaseorder->amount=number_format($purchaseorder->trim_amount,2);
            $pi_date = Carbon::parse($purchaseorder->trim_pi_date);
		        $today = Carbon::now();
            $waitingDays = $pi_date->diffInDays($today)+1;
            $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';
            return $purchaseorder;
          });
          echo json_encode($purchaseorder);
      }
      //Yarn Purchase Order
      elseif ($menu_id==3) {
        $purchaseorder =$this->poyarn
        ->selectRaw('
            po_yarns.id,
            po_yarns.company_id,
            po_yarns.supplier_id,
            po_yarns.pi_no,
            po_yarns.pi_date as yarn_pi_date,
            po_yarns.po_no,
            po_yarns.po_date as yarn_po_date,
            po_yarns.amount as yarn_amount,
            po_yarns.pay_mode as yarn_pay_mode,
            po_yarns.exch_rate,
            po_yarns.delv_start_date as yarn_delv_start_date,
            po_yarns.delv_end_date as yarn_delv_end_date,
            po_yarns.indentor_id as yarn_indentor_id,
            po_yarns.currency_id,
            po_yarns.remarks,

            companies.code as company_code,
            currencies.code as currency_code,
            suppliers.code as supplier_code
          ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('po_yarns.id','=','imp_lc_pos.purchase_order_id');
        })
        ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id')->where([['imp_lcs.menu_id', '=', 3]]);
        })
        ->leftJoin('companies',function($join){
          $join->on('companies.id','=','po_yarns.company_id');
        })
        ->leftJoin('suppliers',function($join){
          $join->on('suppliers.id','=','po_yarns.supplier_id');
        })
        ->leftJoin('currencies',function($join){
          $join->on('currencies.id','=','po_yarns.currency_id');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('po_yarns.pi_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('po_yarns.pi_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) use($company_id) {
          return $q->where('po_yarns.company_id','=',$company_id);
        })
        ->when(request('supplier_id'), function ($q) use($supplier_id) {
          return $q->where('po_yarns.supplier_id','=',$supplier_id);
        })
        ->when(request('pi_no'), function ($q) use($pi_no) {
          return $q->where('po_yarns.pi_no', 'LIKE', "%".$pi_no."%");
        })
        // ->whereNull('imp_lc_pos.purchase_order_id')
        
        ->whereNull('imp_lcs.lc_date')
        ->whereNotNull('po_yarns.pi_no')
        ->get()
        ->map(function($purchaseorder) use($indentor,$paymode){
          $purchaseorder->amount=number_format($purchaseorder->yarn_amount,2);
          $purchaseorder->menu_id='Yarn';
          $purchaseorder->pi_date=date('d-M-Y',strtotime($purchaseorder->yarn_pi_date));
          $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->yarn_po_date));
          $purchaseorder->pay_mode=$paymode[$purchaseorder->yarn_pay_mode];
          $purchaseorder->delv_start_date=$purchaseorder->yarn_delv_start_date?date('d-M-Y',strtotime($purchaseorder->yarn_delv_start_date)):'';
          $purchaseorder->delv_start_end=$purchaseorder->yarn_delv_end_date?date('d-M-Y',strtotime($purchaseorder->yarn_delv_end_date)):'';
          $purchaseorder->indentor_id=isset($indentor[$purchaseorder->yarn_indentor])?$indentor[$purchaseorder->yarn_indentor]:'--';
          $pi_date = Carbon::parse($purchaseorder->yarn_pi_date);
          $today = Carbon::now();
          $waitingDays = $pi_date->diffInDays($today)+1;
          $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
      //knit Service
      elseif ($menu_id==4) {
        $purchaseorder =$this->poknitservice
        ->selectRaw('
          po_knit_services.id,
          po_knit_services.company_id,
          po_knit_services.supplier_id,
          po_knit_services.pi_no,
          po_knit_services.pi_date as knitservice_pi_date,
          po_knit_services.po_no,
          po_knit_services.po_date as knitservice_po_date,
          po_knit_services.amount as knitservice_amount,
          po_knit_services.pay_mode as knitservice_pay_mode,
          po_knit_services.exch_rate,
          po_knit_services.delv_start_date as knitservice_delv_start_date,
          po_knit_services.delv_end_date as knitservice_delv_end_date,
          po_knit_services.currency_id,
          po_knit_services.remarks,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name
        ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('po_knit_services.id','=','imp_lc_pos.purchase_order_id');
        })
        ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id');
        })
        ->leftJoin('companies',function($join){
          $join->on('companies.id','=','po_knit_services.company_id');
        })
        ->leftJoin('suppliers',function($join){
          $join->on('suppliers.id','=','po_knit_services.supplier_id');
        })
        ->leftJoin('currencies',function($join){
          $join->on('currencies.id','=','po_knit_services.currency_id');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('po_knit_services.pi_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('po_knit_services.pi_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) use($company_id) {
          return $q->where('po_knit_services.company_id','=',$company_id);
        })
        ->when(request('supplier_id'), function ($q) use($supplier_id) {
          return $q->where('po_knit_services.supplier_id','=',$supplier_id);
        })
        ->when(request('pi_no'), function ($q) use($pi_no) {
          return $q->where('po_knit_services.pi_no', 'LIKE', "%".$pi_no."%");
        })
        // ->whereNull('imp_lc_pos.purchase_order_id')
        ->where([['imp_lcs.menu_id', '=', 4]])
        ->whereNotNull('po_knit_services.pi_no')
        ->whereNull('imp_lcs.lc_date')
        ->get()
        ->map(function($purchaseorder,$paymode){
          $purchaseorder->amount=number_format($purchaseorder->knitservice_amount,2);
          $purchaseorder->menu_id='Knitting';
          $purchaseorder->pi_date=date('d-M-Y',strtotime($purchaseorder->knitservice_pi_date));
          $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->knitservice_po_date));
          $purchaseorder->pay_mode=$paymode[$purchaseorder->knitservice_pay_mode];
          $purchaseorder->delv_start_date=$purchaseorder->knitservice_delv_start_date;
          $purchaseorder->delv_start_end=$purchaseorder->knitservice_delv_end_date;
          $pi_date = Carbon::parse($purchaseorder->knitservice_pi_date);
          $today = Carbon::now();
          $waitingDays = $pi_date->diffInDays($today)+1;
          $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
      //AOP Service
      elseif ($menu_id==5) {
        $purchaseorder =$this->poaopservice
        ->selectRaw('
          po_aop_services.id,
          po_aop_services.company_id,
          po_aop_services.supplier_id,
          po_aop_services.pi_no,
          po_aop_services.pi_date as  aopservice_pi_date,
          po_aop_services.po_no,
          po_aop_services.po_date as  aopservice_po_date,
          po_aop_services.amount as  aopservice_amount,
          po_aop_services.pay_mode as  aopservice_pay_mode,
          po_aop_services.exch_rate,
          po_aop_services.delv_start_date as  aopservice_delv_start_date,
          po_aop_services.delv_end_date as  aopservice_delv_end_date,
          po_aop_services.currency_id,
          po_aop_services.remarks,
          companies.code as  company_name,
          currencies.code as  currency_name,
          suppliers.code as  supplier_name
          ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('po_aop_services.id','=','imp_lc_pos.purchase_order_id');
        })
        ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id')->where([['imp_lcs.menu_id', '=', 5]]);
        })
        ->leftJoin('companies',function($join){
          $join->on('companies.id','=','po_aop_services.company_id');
        })
        ->leftJoin('suppliers',function($join){
          $join->on('suppliers.id','=','po_aop_services.supplier_id');
        })
        ->leftJoin('currencies',function($join){
          $join->on('currencies.id','=','po_aop_services.currency_id');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('po_aop_services.pi_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('po_aop_services.pi_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) use($company_id) {
          return $q->where('po_aop_services.company_id','=',$company_id);
        })
        ->when(request('supplier_id'), function ($q) use($supplier_id) {
          return $q->where('po_aop_services.supplier_id','=',$supplier_id);
        })
        ->when(request('pi_no'), function ($q) use($pi_no) {
          return $q->where('po_aop_services.pi_no', 'LIKE', "%".$pi_no."%");
        })
        // ->whereNull('imp_lc_pos.purchase_order_id')
        
        ->whereNull('imp_lcs.lc_date')
        ->whereNotNull('po_aop_services.pi_no')
        ->get()
        ->map(function($purchaseorder)  use($paymode) {
          $purchaseorder->amount=number_format($purchaseorder->aopservice_amount,2);
          $purchaseorder->menu_id='AOP';
          $purchaseorder->pi_date=date('d-M-Y',strtotime($purchaseorder->aopservice_pi_date));
          $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->aopservice_po_date));
          $purchaseorder->pay_mode=isset($paymode[$purchaseorder->aopservice_pay_mode])?$paymode[$purchaseorder->aopservice_pay_mode]:'--';
          $purchaseorder->delv_start_date=date('d-M-Y',strtotime($purchaseorder->aopservice_delv_start_date));
          $purchaseorder->delv_start_end=date('d-M-Y',strtotime($purchaseorder->aopservice_delv_end_date));
          $pi_date = Carbon::parse($purchaseorder->aopservice_pi_date);
          $today = Carbon::now();
          $waitingDays = $pi_date->diffInDays($today)+1;
          $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
      //Dyeing Service Work Order
      elseif ($menu_id==6) {
        $purchaseorder =$this->podyeingservice
        ->selectRaw('
          po_dyeing_services.id,
          po_dyeing_services.company_id,
          po_dyeing_services.supplier_id,
          po_dyeing_services.pi_no,
          po_dyeing_services.pi_date as dyeingservice_pi_date,
          po_dyeing_services.po_no,
          po_dyeing_services.po_date as dyeingservice_po_date,
          po_dyeing_services.amount as dyeingservice_amount,
          po_dyeing_services.pay_mode as dyeingservice_pay_mode,
          po_dyeing_services.exch_rate,
          po_dyeing_services.delv_start_date as dyeingservice_delv_start_date,
          po_dyeing_services.delv_end_date as dyeingservice_delv_end_date,
          po_dyeing_services.currency_id,
          po_dyeing_services.remarks,
          companies.code as company_code,
          currencies.code as currency_code,
          suppliers.code as supplier_code
        ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('po_dyeing_services.id','=','imp_lc_pos.purchase_order_id');
        })
        ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id')->where([['imp_lcs.menu_id', '=', 6]]);
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','po_dyeing_services.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_dyeing_services.supplier_id');
        })
        ->join('currencies',function($join){
          $join->on('currencies.id','=','po_dyeing_services.currency_id');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('po_dyeing_services.pi_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('po_dyeing_services.pi_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) use($company_id) {
          return $q->where('po_dyeing_services.company_id','=',$company_id);
        })
        ->when(request('supplier_id'), function ($q) use($supplier_id) {
          return $q->where('po_dyeing_services.supplier_id','=',$supplier_id);
        })
        ->when(request('pi_no'), function ($q) use($pi_no) {
          return $q->where('po_dyeing_services.pi_no', 'LIKE', "%".$pi_no."%");
        })
        // ->whereNull('imp_lc_pos.purchase_order_id')
        
        ->whereNull('imp_lcs.lc_date')
        ->whereNotNull('po_dyeing_services.pi_no')
        ->get()
        ->map(function($purchaseorder)  use($paymode) {
          $purchaseorder->amount=number_format($purchaseorder->dyeingservice_amount,2);
          $purchaseorder->menu_id='Dyeing Service';
          $purchaseorder->pi_date=date('d-M-Y',strtotime($purchaseorder->dyeingservice_pi_date));
          $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->dyeingservice_po_date));
          $purchaseorder->pay_mode=isset($paymode[$purchaseorder->dyeingservice_pay_mode])?$paymode[$purchaseorder->dyeingservice_pay_mode]:'--';
          $purchaseorder->delv_start_date=date('d-M-Y',strtotime($purchaseorder->dyeingservice_delv_start_date));
          $purchaseorder->delv_start_end=date('d-M-Y',strtotime($purchaseorder->dyeingservice_delv_end_date));
          $pi_date = Carbon::parse($purchaseorder->dyeingservice_pi_date);
          $today = Carbon::now();
          $waitingDays = $pi_date->diffInDays($today)+1;
          $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
      //Dyes and Chemical Purchase Order
      elseif ($menu_id==7) {
        $purchaseorder =$this->podyechem
        ->selectRaw('
          po_dye_chems.id,
          po_dye_chems.company_id,
          po_dye_chems.supplier_id,
          po_dye_chems.pi_no,
          po_dye_chems.pi_date as dyechem_pi_date,
          po_dye_chems.po_no,
          po_dye_chems.po_date as dyechem_po_date,
          po_dye_chems.amount as dyechem_amount,
          po_dye_chems.pay_mode as dyechem_pay_mode,
          po_dye_chems.exch_rate,
          po_dye_chems.delv_start_date as dyechem_delv_start_date,
          po_dye_chems.delv_end_date as dyechem_delv_end_date,
          po_dye_chems.indentor_id as dyechem_indentor_id,
          po_dye_chems.currency_id,
          po_dye_chems.remarks,
          companies.code as company_code,
          currencies.code as  currency_code,
          suppliers.name as  supplier_code
        ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('po_dye_chems.id','=','imp_lc_pos.purchase_order_id');
        })
        ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id')->where([['imp_lcs.menu_id', '=', 7]]);
        })
        ->leftJoin('companies',function($join){
          $join->on('companies.id','=','po_dye_chems.company_id');
        })
        ->leftJoin('suppliers',function($join){
          $join->on('suppliers.id','=','po_dye_chems.supplier_id');
        })
        ->leftJoin('currencies',function($join){
          $join->on('currencies.id','=','po_dye_chems.currency_id');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('po_dye_chems.pi_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('po_dye_chems.pi_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) use($company_id) {
          return $q->where('po_dye_chems.company_id','=',$company_id);
        })
        ->when(request('supplier_id'), function ($q) use($supplier_id) {
          return $q->where('po_dye_chems.supplier_id','=',$supplier_id);
        })
        ->when(request('pi_no'), function ($q) use($pi_no) {
          return $q->where('po_dye_chems.pi_no', 'LIKE', "%".$pi_no."%");
        })
        // ->whereNull('imp_lc_pos.purchase_order_id')
        
        ->whereNull('imp_lcs.lc_date')
        ->whereNotNull('po_dye_chems.pi_no')
        ->get()
        ->map(function($purchaseorder) use($paymode,$indentor) {
          $purchaseorder->amount=number_format($purchaseorder->dyechem_amount,2);
          $purchaseorder->menu_id='Dyes & Chemical';
          $purchaseorder->pi_date=date('d-M-Y',strototime($purchaseorder->dyechem_pi_date));
          $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->dyechem_po_date));
          $purchaseorder->pay_mode=isset($paymode[$purchaseorder->dyechem_pay_mode])?$paymode[$purchaseorder->dyechem_pay_mode]:'--';
          $purchaseorder->delv_start_date=date('d-M-Y',strtotime($purchaseorder->dyechem_delv_start_date));
          $purchaseorder->delv_start_end=date('d-M-Y',strtotime($purchaseorder->dyechem_delv_end_date));
          $purchaseorder->indentor_id=isset($indentor[$purchaseorder->dyechem_indentor])?$indentor[$purchaseorder->dyechem_indentor]:'--';
          $pi_date = Carbon::parse($purchaseorder->dyechem_pi_date);
          $today = Carbon::now();
          $waitingDays = $pi_date->diffInDays($today)+1;
          $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
      //General Item Purchase Order
      elseif ($menu_id==8) {
        $purchaseorder =$this->pogeneral
        ->selectRaw('
          po_generals.id,
          po_generals.company_id,
          po_generals.supplier_id,
          po_generals.pi_no,
          po_generals.pi_date as general_pi_date,
          po_generals.po_no,
          po_generals.po_date as general_po_date,
          po_generals.amount as general_amount,
          po_generals.pay_mode as general_pay_mode,
          po_generals.exch_rate,
          po_generals.delv_start_date as general_delv_start_date,
          po_generals.delv_end_date as general_delv_end_date,
          po_generals.indentor_id as general_indentor_id,
          po_generals.currency_id,
          po_generals.remarks,
          companies.code as company_code,
          currencies.code as  currency_code,
          suppliers.name as  supplier_code
        ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('po_generals.id','=','imp_lc_pos.purchase_order_id');
        })
        ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id')->where([['imp_lcs.menu_id', '=', 8]]);
        })
        ->leftJoin('companies',function($join){
          $join->on('companies.id','=','po_generals.company_id');
        })
        ->leftJoin('suppliers',function($join){
          $join->on('suppliers.id','=','po_generals.supplier_id');
        })
        ->leftJoin('currencies',function($join){
          $join->on('currencies.id','=','po_generals.currency_id');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('po_generals.pi_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('po_generals.pi_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) use($company_id) {
          return $q->where('po_generals.company_id','=',$company_id);
        })
        ->when(request('supplier_id'), function ($q) use($supplier_id) {
          return $q->where('po_generals.supplier_id','=',$supplier_id);
        })
        ->when(request('pi_no'), function ($q) use($pi_no) {
          return $q->where('po_generals.pi_no', 'LIKE', "%".$pi_no."%");
        })
        // ->whereNull('imp_lc_pos.purchase_order_id')
        
        ->whereNull('imp_lcs.lc_date')
        ->whereNotNull('po_generals.pi_no')
        ->get()
        ->map(function($purchaseorder) use($paymode) {
          $purchaseorder->amount=number_format($purchaseorder->general_amount,2);
          $purchaseorder->menu_id='General Item';
          $purchaseorder->pi_date=date('d-M-Y',strtotime($purchaseorder->general_pi_date));
          $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->general_po_date));
          $purchaseorder->pay_mode=isset($paymode[$purchaseorder->general_pay_mode])?$paymode[$purchaseorder->general_pay_mode]:'--';
          $purchaseorder->delv_start_date=date('d-M-Y',strtotime($purchaseorder->general_delv_start_date));
          $purchaseorder->delv_start_end=date('d-M-Y',strtotime($purchaseorder->general_delv_end_date));
          $pi_date = Carbon::parse($purchaseorder->general_pi_date);
          $today = Carbon::now();
          $waitingDays = $pi_date->diffInDays($today)+1;
          $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
      //Yarn Dyeing Purchase Order
      elseif ($menu_id==9) {
        $purchaseorder =$this->poyarndyeing
        ->selectRaw('
          po_yarn_dyeings.id,
          po_yarn_dyeings.company_id,
          po_yarn_dyeings.supplier_id,
          po_yarn_dyeings.pi_no,
          po_yarn_dyeings.pi_date as yarndye_pi_date,
          po_yarn_dyeings.po_no,
          po_yarn_dyeings.po_date as yarndye_po_date,
          po_yarn_dyeings.amount as yarndye_amount,
          po_yarn_dyeings.pay_mode as yarndye_pay_mode,
          po_yarn_dyeings.exch_rate,
          po_yarn_dyeings.delv_start_date as yarndye_delv_start_date,
          po_yarn_dyeings.delv_end_date as yarndye_delv_end_date,
          po_yarn_dyeings.currency_id,
          po_yarn_dyeings.remarks,
          companies.code as company_code,
          currencies.code as  currency_code,
          suppliers.name as  supplier_code
        ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('po_yarn_dyeings.id','=','imp_lc_pos.purchase_order_id');
        })
        ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id')->where([['imp_lcs.menu_id', '=', 9]]);
        })
        ->leftJoin('companies',function($join){
          $join->on('companies.id','=','po_yarn_dyeings.company_id');
        })
        ->leftJoin('suppliers',function($join){
          $join->on('suppliers.id','=','po_yarn_dyeings.supplier_id');
        })
        ->leftJoin('currencies',function($join){
          $join->on('currencies.id','=','po_yarn_dyeings.currency_id');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('po_yarn_dyeings.pi_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('po_yarn_dyeings.pi_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) use($company_id) {
          return $q->where('po_yarn_dyeings.company_id','=',$company_id);
        })
        ->when(request('supplier_id'), function ($q) use($supplier_id) {
          return $q->where('po_yarn_dyeings.supplier_id','=',$supplier_id);
        })
        ->when(request('pi_no'), function ($q) use($pi_no) {
          return $q->where('po_yarn_dyeings.pi_no', 'LIKE', "%".$pi_no."%");
        })
        // ->whereNull('imp_lc_pos.purchase_order_id')
        
        ->whereNull('imp_lcs.lc_date')
        ->whereNotNull('po_yarn_dyeings.pi_no')
        ->get()
        ->map(function($purchaseorder) use($paymode){
          $purchaseorder->amount=number_format($purchaseorder->yarndye_amount,2);
          $purchaseorder->menu_id='YarnDyeing';
          $purchaseorder->pi_date=date('d-M-Y',strtotime($purchaseorder->yarndye_pi_date));
          $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->yarndye_po_date));
          $purchaseorder->pay_mode=isset($paymode[$purchaseorder->yarndye_pay_mode])?$paymode[$purchaseorder->yarndye_pay_mode]:'--';
          $purchaseorder->delv_start_date=$purchaseorder->yarndye_delv_start_date?date('d-M-Y',strtotime($purchaseorder->yarndye_delv_start_date)):'--';
          $purchaseorder->delv_start_end=$purchaseorder->yarndye_delv_end_date?date('d-M-Y',strtotime($purchaseorder->yarndye_delv_end_date)):'--';
          $pi_date = Carbon::parse($purchaseorder->yarndye_pi_date);
          $today = Carbon::now();
          $waitingDays = $pi_date->diffInDays($today)+1;
          $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';
          return $purchaseorder; 
        });
        echo json_encode($purchaseorder);
      }
      //Embelishment Work Order
      elseif ($menu_id==10) {
	        $purchaseorder =$this->poembservice
	        ->selectRaw('
	          po_emb_services.id,
	          po_emb_services.company_id,
	          po_emb_services.supplier_id,
	          po_emb_services.pi_no,
	          po_emb_services.pi_date as poemb_pi_date,
	          po_emb_services.po_no,
	          po_emb_services.po_date as poemb_po_date,
	          po_emb_services.amount as poemb_amount,
	          po_emb_services.pay_mode as poemb_pay_mode,
	          po_emb_services.exch_rate as exch_rate,
	          po_emb_services.delv_start_date as poemb_delv_start_date,
	          po_emb_services.delv_end_date as poemb_delv_end_date,
	          po_emb_services.currency_id,
	          po_emb_services.remarks,
	          companies.code as company_code,
	          currencies.code as  currency_code,
	          suppliers.name as  supplier_code
	        ')
	        ->leftJoin('imp_lc_pos',function($join){
	          $join->on('po_emb_services.id','=','imp_lc_pos.purchase_order_id');
	        })
	        ->leftJoin('imp_lcs',function($join){
	          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id')->where([['imp_lcs.menu_id', '=', 10]]);
	        })
	        ->leftJoin('companies',function($join){
	          $join->on('companies.id','=','po_emb_services.company_id');
	        })
	        ->leftJoin('suppliers',function($join){
	          $join->on('suppliers.id','=','po_emb_services.supplier_id');
	        })
	        ->leftJoin('currencies',function($join){
	          $join->on('currencies.id','=','po_emb_services.currency_id');
	        })
	        ->when(request('date_from'), function ($q) {
	          return $q->where('po_emb_services.pi_date', '>=',request('date_from', 0));
	        })
	        ->when(request('date_to'), function ($q) {
	          return $q->where('po_emb_services.pi_date', '<=',request('date_to', 0));
	        })
	        ->when(request('company_id'), function ($q) use($company_id) {
	          return $q->where('po_emb_services.company_id','=',$company_id);
	        })
	        ->when(request('supplier_id'), function ($q) use($supplier_id) {
	          return $q->where('po_emb_services.supplier_id','=',$supplier_id);
	        })
	        ->when(request('pi_no'), function ($q) use($pi_no) {
	          return $q->where('po_emb_services.pi_no', 'LIKE', "%".$pi_no."%");
	        })
	        // ->whereNull('imp_lc_pos.purchase_order_id')
          
          ->whereNull('imp_lcs.lc_date')
	        ->whereNotNull('po_emb_services.pi_no')
	        ->get()
	        ->map(function($purchaseorder) use($paymode){
	          $purchaseorder->amount=number_format($purchaseorder->poemb_amount,2);
	          $purchaseorder->menu_id='Embellishment';
	          $purchaseorder->pi_date=$purchaseorder->poemb_pi_date?date('d-M-Y',strtotime($purchaseorder->poemb_pi_date)):'--';
	          $purchaseorder->po_date=$purchaseorder->poemb_po_date?date('d-M-Y',strtotime($purchaseorder->poemb_po_date)):'--';
	          $purchaseorder->pay_mode=isset($paymode[$purchaseorder->poemb_pay_mode])?$paymode[$purchaseorder->poemb_pay_mode]:'';
	          $purchaseorder->delv_start_date=$purchaseorder->poemb_delv_start_date?date('d-M-Y',strtotime($purchaseorder->poemb_delv_start_date)):'--';
	          $purchaseorder->delv_start_end=$purchaseorder->poemb_delv_end_date?date('d-M-Y',strtotime($purchaseorder->poemb_delv_end_date)):'--';
	          $pi_date = Carbon::parse($purchaseorder->poemb_pi_date);
	          $today = Carbon::now();
	          $waitingDays = $pi_date->diffInDays($today)+1;
	          $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';
	          return $purchaseorder;
	        });
	        echo json_encode($purchaseorder);
	  	}
	  	//General Service Work Order
      elseif ($menu_id==11) {
        $purchaseorder =$this->pogeneralservice
        ->selectRaw('
          po_general_services.id,
          po_general_services.company_id,
          po_general_services.supplier_id,
          po_general_services.pi_no,
          po_general_services.pi_date as genservice_pi_date,
          po_general_services.po_no,
          po_general_services.po_date as genservice_po_date,
          po_general_services.amount as genservice_amount,
          po_general_services.pay_mode as genservice_pay_mode,
          po_general_services.exch_rate,
          po_general_services.delv_start_date as genservice_delv_start_date,
          po_general_services.delv_end_date as genservice_delv_end_date,
          po_general_services.currency_id,
          po_general_services.remarks,
          companies.code as company_code,
          currencies.code as currency_code,
          suppliers.name as  supplier_code
        ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('po_general_services.id','=','imp_lc_pos.purchase_order_id');
        })
        ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id')->where([['imp_lcs.menu_id', '=', 11]]);
        })
        ->leftJoin('companies',function($join){
          $join->on('companies.id','=','po_general_services.company_id');
        })
        ->leftJoin('suppliers',function($join){
          $join->on('suppliers.id','=','po_general_services.supplier_id');
        })
        ->leftJoin('currencies',function($join){
          $join->on('currencies.id','=','po_general_services.currency_id');
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('po_general_services.pi_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('po_general_services.pi_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) use($company_id) {
          return $q->where('po_general_services.company_id','=',$company_id);
        })
        ->when(request('supplier_id'), function ($q) use($supplier_id) {
          return $q->where('po_general_services.supplier_id','=',$supplier_id);
        })
        ->when(request('pi_no'), function ($q) use($pi_no) {
          return $q->where('po_general_services.pi_no', 'LIKE', "%".$pi_no."%");
        })
        // ->whereNull('imp_lc_pos.purchase_order_id')
        
        ->whereNull('imp_lcs.lc_date')
        ->whereNotNull('po_general_services.pi_no')
        ->get()
        ->map(function($purchaseorder) use($paymode){
          $purchaseorder->amount=number_format($purchaseorder->genservice_amount,2);
          $purchaseorder->menu_id='General Service';
          $purchaseorder->pi_date=date('d-M-Y',strtotime($purchaseorder->genservice_pi_date));
          $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->genservice_po_date));
          $purchaseorder->pay_mode=isset($paymode[$purchaseorder->genservice_pay_mode])?$paymode[$purchaseorder->genservice_pay_mode]:'';
          $purchaseorder->delv_start_date=$purchaseorder->genservice_delv_start_date?date('d-M-Y',strtotime($purchaseorder->genservice_delv_start_date)):'--';
          $purchaseorder->delv_start_end=$purchaseorder->genservice_delv_end_date?date('d-M-Y',strtotime($purchaseorder->genservice_delv_end_date)):'--';
          $pi_date = Carbon::parse($purchaseorder->genservice_pi_date);
          $today = Carbon::now();
          $waitingDays = $pi_date->diffInDays($today)+1;
          $purchaseorder->days_taken=($purchaseorder->pi_date&&$today)?$waitingDays:'--';
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
 
   	}
}