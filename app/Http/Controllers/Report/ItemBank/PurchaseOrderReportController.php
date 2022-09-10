<?php

namespace App\Http\Controllers\Report\ItemBank;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Purchase\PoFabricItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Purchase\PoTrimItemRepository;
use App\Repositories\Contracts\Purchase\PoDyeChemRepository;
use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Repositories\Contracts\Purchase\PoAopServiceRepository;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Contracts\Purchase\PoGeneralRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnItemRepository;
use App\Repositories\Contracts\Purchase\PoDyeChemItemRepository;
use App\Repositories\Contracts\Purchase\PoGeneralItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingItemRepository;
use App\Repositories\Contracts\Purchase\PoGeneralServiceRepository;


use App\Library\Template;

class PurchaseOrderReportController extends Controller
{
    private $company;
    private $buyer;
    private $supplier;
    private $currency;
    private $itemcategory;
    private $pofabric;
    private $pofabricitem;
    private $poyarn;
    private $potrim;
    private $potrimitem;
    private $podyechem;
    private $podyeingservice;
    private $poaopservice;
    private $pogeneral;
    private $poknitservice;
    private $itemaccount;
    private $budgetfabric;
    private $poyarndyeing;
    private $invyarnitem;
    private $poembservice;
    private $pogeneralservice;


  public function __construct(
      CompanyRepository $company,
      SupplierRepository $supplier,
      CurrencyRepository $currency,
      BuyerRepository $buyer,
      PoFabricRepository $pofabric,
      PoFabricItemRepository $pofabricitem,
      EmbelishmentTypeRepository $embelishmenttype,
      ItemcategoryRepository $itemcategory,
      PoTrimRepository $potrim,
      PoTrimItemRepository $potrimitem,
      PoDyeChemRepository $podyechem,
      PoDyeingServiceRepository $podyeingservice,
      PoGeneralRepository $pogeneral,
      PoKnitServiceRepository $poknitservice,
      PoEmbServiceRepository $poembservice,
      PoYarnRepository $poyarn,
      ItemAccountRepository $itemaccount,
      PoAopServiceRepository $poaopservice,
      PoYarnDyeingRepository $poyarndyeing,
      BudgetFabricRepository $budgetfabric,
      PoDyeChemItemRepository $podyechemitem,
    PoGeneralItemRepository $pogeneralitem,
      PoYarnItemRepository $poyarnitem,
      PoYarnDyeingItemRepository $poyarndyeingitem,
      PoGeneralServiceRepository $pogeneralservice,
      InvYarnItemRepository $invyarnitem

      )
  {
      $this->company = $company;
      $this->supplier = $supplier;
      $this->buyer = $buyer;
      $this->currency = $currency;
      $this->embelishmenttype = $embelishmenttype;
      $this->itemcategory = $itemcategory;
      $this->pofabric = $pofabric;
      $this->pofabricitem = $pofabricitem;
      $this->potrim = $potrim;
      $this->potrimitem = $potrimitem;
      $this->poyarn = $poyarn;
      $this->poyarnitem = $poyarnitem;
      $this->invyarnitem = $invyarnitem;
      $this->podyechem = $podyechem;
      $this->podyeingservice = $podyeingservice;
      $this->poaopservice = $poaopservice;
      $this->poknitservice = $poknitservice;
      $this->poembservice = $poembservice;
      $this->pogeneral = $pogeneral;
      $this->itemaccount = $itemaccount;
      $this->poyarndyeing = $poyarndyeing;
      $this->budgetfabric = $budgetfabric;
      $this->podyechemitem = $podyechemitem;
      $this->pogeneralitem = $pogeneralitem;
      $this->poyarndyeingitem = $poyarndyeingitem;
      $this->pogeneralservice = $pogeneralservice;

        $this->middleware('auth');
    // $this->middleware('permission:view.poaopserviceitems',   ['only' => ['create', 'index','show']]);

  }

  public function index()
  {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
      $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
      $basis = array_only(config('bprs.pur_order_basis'), [1]);
      $paymode = array_prepend(array_only(config('bprs.paymode'), [3,4,6]),'-Select-','');
      $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
      $itemcategory=array_prepend(array_pluck($this->itemcategory->orderBy('name','asc')->get(),'name','id'),'','');
      $menu=array_prepend(array_only(config('bprs.menu'),[1,2,3,4,5,6,7,8,9,10,11]),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
      return Template::loadView('Report.ItemBank.PurchaseOrderReport',['itemcategory'=>$itemcategory,'company'=>$company,'basis'=>$basis,'supplier'=>$supplier,'currency'=>$currency,'paymode'=>$paymode,'aoptype'=>$aoptype,'menu'=>$menu,'buyer'=>$buyer]);
  }

  public function reportData() {
      $menu_id=request('menu_id',0);
      $company_id=request('company_id',0);
      $itemcategory_id=request('itemcategory_id',0);
      $supplier_id=request('supplier_id',0);
      $buyer_id=request('buyer_id',0);
    //Fabric Purchase Order
      if($menu_id==1){
        $fabricDescription=$this->budgetfabric
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_gmts',function($join){
          $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
          $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('budgets',function($join){
          $join->on('budgets.id','=','budget_fabrics.budget_id');
        })
        ->join('jobs',function($join){
          $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('styles', function($join) {
          $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('gmtsparts',function($join){
          $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->join('autoyarns',function($join){
          $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->join('autoyarnratios',function($join){
          $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
        })
        ->join('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->join('constructions',function($join){
          $join->on('constructions.id','=','autoyarns.construction_id');
        })
        ->get([
          'style_fabrications.id',
          'constructions.name as construction',
          'autoyarnratios.composition_id',
          'compositions.name',
          'autoyarnratios.ratio',
        ]);
        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
          $fabricDescriptionArr[$row->id]=$row->construction;
          $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
        }
        
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
          $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }
        $purchaseorder =$this->pofabric
          ->selectRaw('
              po_fabrics.id,
              po_fabrics.po_no,
              po_fabrics.po_date,
              po_fabrics.company_id,
              po_fabrics.supplier_id,
              po_fabrics.exch_rate,
              po_fabrics.pi_no,
              po_fabrics.pi_date,
              po_fabrics.remarks,
              companies.code as company_name,
              currencies.code as currency_name,
              suppliers.code as supplier_name,
              suppliers.id as supplier_id,
              item_accounts.id as item_account_id,          
              item_accounts.item_description,
              item_accounts.specification,
              item_accounts.sub_class_name,
              budget_fabrics.style_fabrication_id,
              importLc.lc_no_i,
              importLc.lc_no_ii,
              importLc.lc_no_iii,
              importLc.lc_no_iv,
              po_fabric_items.id as po_item_id,
              po_fabric_items.qty,
              po_fabric_items.rate,
              po_fabric_items.amount,
              fabric_rcv.rcv_qty,
              fabric_rcv.rcv_amount
          ')
          ->leftJoin('companies',function($join){
            $join->on('companies.id','=','po_fabrics.company_id');
          })
          ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_fabrics.supplier_id');
          })
          ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_fabrics.currency_id');
          })
          ->leftJoin('po_fabric_items',function($join){
            $join->on('po_fabric_items.po_fabric_id','=','po_fabrics.id')
          ->whereNull('po_fabric_items.deleted_at');
          })
          ->join('budget_fabrics',function($join){
            $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
          })
          ->join('style_fabrications',function($join){
            $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
          })
          ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
          })
          ->join('item_accounts', function($join) {
            $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
          })
          ->join('budgets',function($join){
            $join->on('budgets.id','=','budget_fabrics.budget_id');
          })
          ->join('jobs',function($join){
            $join->on('jobs.id','=','budgets.job_id');
          })
          ->join('styles', function($join) {
            $join->on('styles.id', '=', 'jobs.style_id');
          })
          ->join('gmtsparts',function($join){
            $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
          })
          ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
          })
          ->join('uoms',function($join){
            $join->on('uoms.id','=','style_fabrications.uom_id');
          })
          ->leftJoin(\DB::raw("(
            select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            from imp_lc_pos
            join po_fabrics on imp_lc_pos.purchase_order_id=po_fabrics.id
            join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
            where imp_lcs.menu_id='".$menu_id."'
            group by 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            ) importLc"), "importLc.purchase_order_id", "=", "po_fabrics.id")
          ->leftJoin(\DB::raw("(
            select 
            inv_finish_fab_rcv_fabrics.po_fabric_item_id,
            sum(inv_finish_fab_rcv_items.qty) as rcv_qty,
            sum(inv_finish_fab_rcv_items.amount) as rcv_amount,
            sum(inv_finish_fab_transactions.store_qty) as store_qty,
            sum(inv_finish_fab_transactions.store_amount) as rcv_amount_tk
            from inv_finish_fab_rcv_fabrics
            join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_fabric_id=inv_finish_fab_rcv_fabrics.id
            join inv_finish_fab_transactions on inv_finish_fab_transactions.inv_finish_fab_rcv_item_id=inv_finish_fab_rcv_items.id
            where inv_finish_fab_transactions.trans_type_id=1
            group by 
            inv_finish_fab_rcv_fabrics.po_fabric_item_id
          ) fabric_rcv"), "fabric_rcv.po_fabric_item_id", "=", "po_fabric_items.id")
          ->when(request('date_from'), function ($q) {
            return $q->where('po_fabrics.po_date', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
            return $q->where('po_fabrics.po_date', '<=',request('date_to', 0));
          })
          ->when(request('company_id'), function ($q) use($company_id) {
            return $q->where('po_fabrics.company_id', '=',$company_id);
          })
          ->when(request('supplier_id'), function ($q) use($supplier_id) {
            return $q->where('po_fabrics.supplier_id', '=',$supplier_id);
          })
          ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
            return $q->where('po_fabrics.itemcategory_id', '=', $itemcategory_id);
          })
          ->orderBy('po_fabrics.company_id')
          ->orderBy('po_fabrics.id','desc')
        ->get();
      }
    //Trims Purchase Order
      if($menu_id==2){
        $purchaseorder =$this->potrim
          ->selectRaw('
            po_trims.id,
            po_trims.company_id,
            po_trims.po_no,
            po_trims.po_date,
            po_trims.supplier_id,
            po_trims.itemcategory_id,
            po_trims.exch_rate,
            po_trims.pi_no,
            po_trims.pi_date,
            po_trims.remarks,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            suppliers.id as supplier_id,
            itemcategories.name as itemcategory,
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name,
            itemclasses.name as itemclass_name,
            buyers.name as buyer_name,
            importLc.lc_no_i,
            importLc.lc_no_ii,
            importLc.lc_no_iii,
            importLc.lc_no_iv,
            po_trim_items.id as po_item_id,
            po_trim_items.qty,
            po_trim_items.rate,
            po_trim_items.amount,
            trims_rcv.rcv_qty,
            trims_rcv.rcv_amount
          ')
          ->leftJoin('companies',function($join){
            $join->on('companies.id','=','po_trims.company_id');
          })
          ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_trims.supplier_id');
          })
          ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','po_trims.buyer_id');
          })
          ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_trims.currency_id');
          })
          ->leftJoin('po_trim_items',function($join){
            $join->on('po_trims.id','=','po_trim_items.po_trim_id');
          })
          ->leftJoin('budget_trims',function($join){
            $join->on('po_trim_items.budget_trim_id','=','budget_trims.id')
            ->whereNull('po_trim_items.deleted_at');
          })
          ->leftJoin('itemclasses', function($join){
            $join->on('itemclasses.id', '=','budget_trims.itemclass_id');
          })
          ->leftJoin('itemcategories', function($join){
            $join->on('itemcategories.id', '=','itemclasses.itemcategory_id');
          })
          ->leftJoin('item_accounts',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
          })
          ->leftJoin(\DB::raw("(
            select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            from imp_lc_pos
            join po_trims on imp_lc_pos.purchase_order_id=po_trims.id
            join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
            where imp_lcs.menu_id='".$menu_id."'
            group by 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            ) importLc"), "importLc.purchase_order_id", "=", "po_trims.id")
          ->leftJoin(\DB::raw("(
            select 
              po_trim_item_reports.po_trim_item_id,
              sum(inv_trim_rcv_items.qty) as rcv_qty,
              sum(inv_trim_rcv_items.amount) as rcv_amount,
              sum(inv_trim_transactions.store_qty) as store_qty,
              sum(inv_trim_transactions.store_amount) as rcv_amount_tk
            from po_trim_item_reports
            join inv_trim_rcv_items on inv_trim_rcv_items.po_trim_item_report_id=po_trim_item_reports.id
            join inv_trim_transactions on inv_trim_transactions.inv_trim_rcv_item_id=inv_trim_rcv_items.id
            where inv_trim_transactions.trans_type_id=1
            group by 
            po_trim_item_reports.po_trim_item_id
          ) trims_rcv"), "trims_rcv.po_trim_item_id", "=", "po_trim_items.id")
          ->when(request('date_from'), function ($q) {
            return $q->where('po_trims.po_date', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
            return $q->where('po_trims.po_date', '<=',request('date_to', 0));
          })
          ->when(request('company_id'), function ($q) use($company_id) {
            return $q->where('po_trims.company_id','=',$company_id);
          })
          ->when(request('supplier_id'), function ($q) use($supplier_id) {
            return $q->where('po_trims.supplier_id','=',$supplier_id);
          })
          ->when(request('buyer_id'), function ($q) use($buyer_id) {
            return $q->where('po_trims.buyer_id','=',$buyer_id);
          })
          ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
            return $q->where('po_trims.itemcategory_id', '=', $itemcategory_id);
          })
          ->orderBy('po_trims.company_id')
          ->orderBy('po_trims.id','desc')
          ->get();
      }
    //Yarn Purchase Order 
    if($menu_id==3){
        $purchaseorder =$this->poyarn
        ->selectRaw('
            po_yarns.id,
            po_yarns.po_no,
            po_yarns.po_date,
            po_yarns.company_id,
            po_yarns.supplier_id,
            po_yarns.exch_rate,
            po_yarns.pi_no,
            po_yarns.pi_date,
            po_yarns.remarks,
            po_yarn_items.item_account_id,
            po_yarn_items.remarks as item_remarks,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            suppliers.id as supplier_id,
            itemcategories.name as itemcategory,
            item_accounts.id as item_account_id,          
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name,
            itemclasses.name as itemclass_name,
            importLc.lc_no_i,
            importLc.lc_no_ii,
            importLc.lc_no_iii,
            importLc.lc_no_iv,
            po_yarn_items.id as po_item_id,
            po_yarn_items.qty,
            po_yarn_items.rate,
            po_yarn_items.amount,
            yarn_rcv.rcv_qty,
            yarn_rcv.rcv_amount
          ')
          ->join('companies',function($join){
            $join->on('companies.id','=','po_yarns.company_id');
          })
          ->join('suppliers',function($join){
            $join->on('suppliers.id','=','po_yarns.supplier_id');
          })
          ->join('currencies',function($join){
            $join->on('currencies.id','=','po_yarns.currency_id');
          })
          ->leftJoin('po_yarn_items',function($join){
            $join->on('po_yarn_items.po_yarn_id','=','po_yarns.id')
          ->whereNull('po_yarn_items.deleted_at');
          })
          ->leftJoin('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'po_yarn_items.item_account_id');
          })
          ->join('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
          })
          ->join('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
          })
          ->leftJoin(\DB::raw("(
            select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            from imp_lc_pos
            join po_yarns on imp_lc_pos.purchase_order_id=po_yarns.id
            join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
            where imp_lcs.menu_id='".$menu_id."'
            group by 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            ) importLc"), "importLc.purchase_order_id", "=", "po_yarns.id")
          ->leftJoin(\DB::raw("(
            select 
            po_yarn_items.id as po_yarn_item_id,
            sum(inv_yarn_rcv_items.qty) as rcv_qty,
            sum(inv_yarn_rcv_items.amount) as rcv_amount
            from po_yarn_items
            join inv_yarn_rcv_items on inv_yarn_rcv_items.po_yarn_item_id=po_yarn_items.id
            group by 
            po_yarn_items.id
          ) yarn_rcv"), "yarn_rcv.po_yarn_item_id", "=", "po_yarn_items.id")
          ->when(request('date_from'), function ($q) {
            return $q->where('po_yarns.po_date', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
            return $q->where('po_yarns.po_date', '<=',request('date_to', 0));
          })
          ->when(request('company_id'), function ($q) use($company_id) {
            return $q->where('po_yarns.company_id', '=',$company_id);
          })
          ->when(request('supplier_id'), function ($q) use($supplier_id) {
            return $q->where('po_yarns.supplier_id', '=',$supplier_id);
          })
          ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
            return $q->where('po_yarns.itemcategory_id', '=', $itemcategory_id);
          })
          ->orderBy('po_yarns.company_id')
          ->orderBy('po_yarns.id','desc')
          ->get();
    }
    //Knit Purchase Order 
    if($menu_id==4){
        $purchaseorder =$this->poknitservice
          ->selectRaw('
            po_knit_services.id,
            po_knit_services.po_no,
            po_knit_services.po_date,
            po_knit_services.company_id,
            po_knit_services.supplier_id,
            po_knit_services.exch_rate,
            po_knit_services.pi_no,
            po_knit_services.pi_date,
            po_knit_services.remarks,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            suppliers.id as supplier_id,
            itemcategories.name as itemcategory,
            item_accounts.id as item_account_id,          
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name,
            itemclasses.name as itemclass_name,
            buyers.name as buyer_name,
            importLc.lc_no_i,
            importLc.lc_no_ii,
            importLc.lc_no_iii,
            importLc.lc_no_iv,
            po_knit_service_items.qty,
            po_knit_service_items.rate,
            po_knit_service_items.amount
            ')
            ->leftJoin('companies',function($join){
              $join->on('companies.id','=','po_knit_services.company_id');
            })
            ->leftJoin('suppliers',function($join){
              $join->on('suppliers.id','=','po_knit_services.supplier_id');
            })
            ->leftJoin('currencies',function($join){
              $join->on('currencies.id','=','po_knit_services.currency_id');
            })
            // ->leftJoin('itemcategories',function($join){
            //   $join->on('itemcategories.id','=','po_knit_services.itemcategory_id');
            // })
            // ->leftJoin('item_accounts',function($join){
            //   $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            // })
            // ->leftJoin('itemclasses',function($join){
            //   $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            // })
            ->leftJoin('po_knit_service_items',function($join){
              $join->on('po_knit_service_items.po_knit_service_id','=','po_knit_services.id');
            })
            ->join('budget_fabric_prods',function($join){
            $join->on('po_knit_service_items.budget_fabric_prod_id','=','budget_fabric_prods.id')->whereNull('po_knit_service_items.deleted_at');
            })
            ->join('budget_fabrics',function($join){
              $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->join('style_fabrications',function($join){
              $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->join('style_gmts',function($join){
              $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
            })
            ->join('item_accounts', function($join) {
              $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
            })
            ->leftJoin('itemcategories',function($join){
              $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->leftJoin('itemclasses',function($join){
              $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->join('budgets',function($join){
              $join->on('budgets.id','=','budget_fabrics.budget_id');
            })
            ->join('jobs',function($join){
              $join->on('jobs.id','=','budgets.job_id');
            })
            ->join('styles', function($join) {
              $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->join('buyers',function($join){
              $join->on('buyers.id','=','styles.buyer_id');
            })
            ->leftJoin(\DB::raw("(
              select 
              imp_lc_pos.purchase_order_id,
              imp_lcs.lc_no_i,
              imp_lcs.lc_no_ii,
              imp_lcs.lc_no_iii,
              imp_lcs.lc_no_iv
              from imp_lc_pos
              join po_knit_services on imp_lc_pos.purchase_order_id=po_knit_services.id
              join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
              where imp_lcs.menu_id='".$menu_id."'
              group by 
              imp_lc_pos.purchase_order_id,
              imp_lcs.lc_no_i,
              imp_lcs.lc_no_ii,
              imp_lcs.lc_no_iii,
              imp_lcs.lc_no_iv
              ) importLc"), "importLc.purchase_order_id", "=", "po_knit_services.id")
            ->when(request('date_from'), function ($q) {
                return $q->where('po_knit_services.po_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
                return $q->where('po_knit_services.po_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('po_knit_services.company_id', '=',$company_id);
            })
            ->when(request('supplier_id'), function ($q) use($supplier_id) {
                return $q->where('po_knit_services.supplier_id', '=',$supplier_id);
            })
            ->when(request('buyer_id'), function ($q) use($buyer_id) {
              return $q->where('buyers.id', '=',$buyer_id);
            })
            ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
                return $q->where('itemcategories.id', '=', $itemcategory_id);
            })
            ->orderBy('po_knit_services.company_id')
            ->orderBy('po_knit_services.id','desc')
            ->get();
          /*->map(function($purchaseorder){
            $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->po_date));
            $purchaseorder->qty_d=number_format($purchaseorder->qty,2);
            $purchaseorder->rate_d=number_format($purchaseorder->rate,2);
            if($purchaseorder->currency_name=='BDT'){
            $purchaseorder->exch_rate=1;
            $purchaseorder->amount_tk=$purchaseorder->amount;
            $purchaseorder->amount_taka=number_format($purchaseorder->amount,2);

            }
            else{
            $purchaseorder->exch_rate=$purchaseorder->exch_rate;
            $purchaseorder->amount_tk=$purchaseorder->amount*$purchaseorder->exch_rate;
            $purchaseorder->amount_taka=number_format($purchaseorder->amount*$purchaseorder->exch_rate,2);
            }
            $purchaseorder->amount_d=number_format($purchaseorder->amount,2);
            return $purchaseorder;
          });*/
          //echo json_encode(['maindata'=>$purchaseorder,'categorydata'=>'','supplierdata'=>'']);
    }
    //AOP Service Order
    if($menu_id==5){
        $fabricDescription=$this->budgetfabric
        ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('production_processes',function($join){
        $join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
        })
        ->join('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_gmts',function($join){
        $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
          $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('budgets',function($join){
          $join->on('budgets.id','=','budget_fabrics.budget_id');
        })
        ->join('jobs',function($join){
          $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('styles', function($join) {
          $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('gmtsparts',function($join){
          $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->join('autoyarns',function($join){
          $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->join('autoyarnratios',function($join){
            $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
        })
        ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->join('constructions',function($join){
            $join->on('constructions.id','=','autoyarns.construction_id');
        })
        ->where([['production_processes.production_area_id','=',25]])

        ->get([
        'style_fabrications.id',
        'constructions.name as construction',
        'autoyarnratios.composition_id',
        'compositions.name',
        'autoyarnratios.ratio',
        ]);
        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
            $fabricDescriptionArr[$row->id]=$row->construction;
            $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
        }
        
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $purchaseorder =$this->poaopservice
        ->selectRaw('
            po_aop_services.id,
            po_aop_services.po_no,
            po_aop_services.po_date,
            po_aop_services.company_id,
            po_aop_services.supplier_id,
            po_aop_services.exch_rate,
            po_aop_services.pi_no,
            po_aop_services.pi_date,
            po_aop_services.remarks,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            suppliers.id as supplier_id,
            itemcategories.name as itemcategory,
            item_accounts.id as item_account_id,          
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name,
            itemclasses.name as itemclass_name,
            budget_fabrics.style_fabrication_id,
            buyers.name as buyer_name,
            importLc.lc_no_i,
            importLc.lc_no_ii,
            importLc.lc_no_iii,
            importLc.lc_no_iv,
            po_aop_service_items.qty,
            po_aop_service_items.rate,
            po_aop_service_items.amount
          ')
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','po_aop_services.company_id');
          })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_aop_services.supplier_id');
          })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_aop_services.currency_id');
          })
        ->leftJoin('po_aop_service_items',function($join){
            $join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id');
          })
          ->join('budget_fabric_prods',function($join){
            $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
          })
          ->join('budget_fabrics',function($join){
              $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
          })
          ->join('style_fabrications',function($join){
              $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
          })
          ->join('style_gmts',function($join){
              $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
          })
          ->join('item_accounts', function($join) {
              $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
          })
          ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
          })
          ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
          })
          ->join('budgets',function($join){
            $join->on('budgets.id','=','budget_fabrics.budget_id');
          })
          ->join('jobs',function($join){
              $join->on('jobs.id','=','budgets.job_id');
          })
          ->join('styles', function($join) {
              $join->on('styles.id', '=', 'jobs.style_id');
          })
          ->join('buyers',function($join){
              $join->on('buyers.id','=','styles.buyer_id');
          })
          ->leftJoin(\DB::raw("(
            select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            from imp_lc_pos
            join po_aop_services on imp_lc_pos.purchase_order_id=po_aop_services.id
            join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
            where imp_lcs.menu_id='".$menu_id."'
            group by 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            ) importLc"), "importLc.purchase_order_id", "=", "po_aop_services.id")
          ->when(request('date_from'), function ($q) {
              return $q->where('po_aop_services.po_date', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
              return $q->where('po_aop_services.po_date', '<=',request('date_to', 0));
          })
          ->when(request('company_id'), function ($q) use($company_id) {
              return $q->where('po_aop_services.company_id', '=',$company_id);
          })
          ->when(request('supplier_id'), function ($q) use($supplier_id) {
              return $q->where('po_aop_services.supplier_id', '=',$supplier_id);
          })
          ->when(request('buyer_id'), function ($q) use($buyer_id) {
            return $q->where('buyers.id', '=',$buyer_id);
          })
          ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
            return $q->where('itemcategories.id', '=', $itemcategory_id);
          })
          ->orderBy('po_aop_services.company_id')
          ->orderBy('po_aop_services.id','desc')
        ->get();
        /*->map(function($purchaseorder) use($desDropdown){
          $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->po_date));
          $purchaseorder->composition=isset($desDropdown[$purchaseorder->style_fabrication_id])?$desDropdown[$purchaseorder->style_fabrication_id]:'';
          $purchaseorder->qty_d=number_format($purchaseorder->qty,2);
          $purchaseorder->rate_d=number_format($purchaseorder->rate,2);
          if($purchaseorder->currency_name=='BDT'){
          $purchaseorder->exch_rate=1;
          $purchaseorder->amount_tk=$purchaseorder->amount;
          $purchaseorder->amount_taka=number_format($purchaseorder->amount,2);
          }
          else{
          $purchaseorder->exch_rate=$purchaseorder->exch_rate;
          $purchaseorder->amount_tk=$purchaseorder->amount*$purchaseorder->exch_rate;
          $purchaseorder->amount_taka=number_format($purchaseorder->amount*$purchaseorder->exch_rate,2);
          }
          $purchaseorder->amount_d=number_format($purchaseorder->amount,2);
          return $purchaseorder;
        });*/
        //echo json_encode(['maindata'=>$purchaseorder,'categorydata'=>'','supplierdata'=>'']);
    }
    //Dyeing Service Work Order
    if($menu_id==6){
      $fabricDescription=$this->budgetfabric
      ->join('budget_fabric_prods',function($join){
      $join->on('budget_fabric_prods.budget_fabric_id','=','budget_fabrics.id');
      })
      ->join('production_processes',function($join){
      $join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
      })
      ->join('style_fabrications',function($join){
      $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
      })
      ->join('style_gmts',function($join){
      $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
      })
      ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('budgets',function($join){
        $join->on('budgets.id','=','budget_fabrics.budget_id');
      })
      ->join('jobs',function($join){
        $join->on('jobs.id','=','budgets.job_id');
      })
      ->join('styles', function($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
      })
      ->join('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
      })
      ->join('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
      })
      ->join('autoyarnratios',function($join){
          $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
      })
      ->join('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
      })
      ->join('constructions',function($join){
          $join->on('constructions.id','=','autoyarns.construction_id');
      })
      ->where([['production_processes.production_area_id','=',20]])
      ->get([
      'style_fabrications.id',
      'constructions.name as construction',
      'autoyarnratios.composition_id',
      'compositions.name',
      'autoyarnratios.ratio',
      ]);
      $fabricDescriptionArr=array();
      $fabricCompositionArr=array();
      foreach($fabricDescription as $row){
          $fabricDescriptionArr[$row->id]=$row->construction;
          $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
      }
      
      $desDropdown=array();
      foreach($fabricDescriptionArr as $key=>$val){
          $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
      }
        $purchaseorder =$this->podyeingservice
          ->selectRaw('
            po_dyeing_services.id,
            po_dyeing_services.po_no,
            po_dyeing_services.po_date,
            po_dyeing_services.company_id,
            po_dyeing_services.supplier_id,
            po_dyeing_services.exch_rate,
            po_dyeing_services.pi_no,
            po_dyeing_services.pi_date,
            po_dyeing_services.remarks,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            suppliers.id as supplier_id,
            itemcategories.name as itemcategory,
            item_accounts.id as item_account_id,          
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name,
            itemclasses.name as itemclass_name,
            budget_fabrics.style_fabrication_id,
            buyers.name as buyer_name,
            importLc.lc_no_i,
            importLc.lc_no_ii,
            importLc.lc_no_iii,
            importLc.lc_no_iv,
            po_dyeing_service_items.qty,
            po_dyeing_service_items.rate,
            po_dyeing_service_items.amount
            ')
          ->leftJoin('companies',function($join){
            $join->on('companies.id','=','po_dyeing_services.company_id');
          })
          ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_dyeing_services.supplier_id');
          })
          ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_dyeing_services.currency_id');
          })
          ->leftJoin('po_dyeing_service_items',function($join){
            $join->on('po_dyeing_service_items.po_dyeing_service_id','=','po_dyeing_services.id')
          ->whereNull('po_dyeing_service_items.deleted_at');
          })
          ->leftJoin('budget_fabric_prods',function($join){
            $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
          })
          ->join('budget_fabrics',function($join){
            $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
          })
          ->join('style_fabrications',function($join){
            $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
          })
          ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
          })
          ->join('item_accounts', function($join) {
            $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
          })
          ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
          })
          ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
          })
          ->join('budgets',function($join){
            $join->on('budgets.id','=','budget_fabrics.budget_id');
          })
          ->join('jobs',function($join){
              $join->on('jobs.id','=','budgets.job_id');
          })
          ->join('styles', function($join) {
              $join->on('styles.id', '=', 'jobs.style_id');
          })
          ->join('buyers',function($join){
              $join->on('buyers.id','=','styles.buyer_id');
          })
          ->leftJoin(\DB::raw("(
            select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            from imp_lc_pos
            join po_dyeing_services on imp_lc_pos.purchase_order_id=po_dyeing_services.id
            join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
            where imp_lcs.menu_id='".$menu_id."'
            group by 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            ) importLc"), "importLc.purchase_order_id", "=", "po_dyeing_services.id")
          ->when(request('date_from'), function ($q) {
            return $q->where('po_dyeing_services.po_date', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
            return $q->where('po_dyeing_services.po_date', '<=',request('date_to', 0));
          })
          ->when(request('company_id'), function ($q) use($company_id) {
            return $q->where('po_dyeing_services.company_id', '=',$company_id);
          })
          ->when(request('supplier_id'), function ($q) use($supplier_id) {
            return $q->where('po_dyeing_services.supplier_id', '=',$supplier_id);
          })
          ->when(request('buyer_id'), function ($q) use($buyer_id) {
            return $q->where('buyers.id', '=',$buyer_id);
          })
          ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
            return $q->where('itemcategories.id', '=', $itemcategory_id);
          })
          ->orderBy('po_dyeing_services.company_id')
          ->orderBy('po_dyeing_services.id','desc')
        ->get();
        /*->map(function($purchaseorder) use($desDropdown){
          $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->po_date));
          $purchaseorder->composition=isset($desDropdown[$purchaseorder->style_fabrication_id])?$desDropdown[$purchaseorder->style_fabrication_id]:'';
          $purchaseorder->qty_d=number_format($purchaseorder->qty,2);
          $purchaseorder->rate_d=number_format($purchaseorder->rate,2);
          if($purchaseorder->currency_name=='BDT'){
            $purchaseorder->exch_rate=1;
            $purchaseorder->amount_tk=$purchaseorder->amount;
            $purchaseorder->amount_taka=number_format($purchaseorder->amount,2);
          }
          else{
            $purchaseorder->exch_rate=$purchaseorder->exch_rate;
            $purchaseorder->amount_tk=$purchaseorder->amount*$purchaseorder->exch_rate;
            $purchaseorder->amount_taka=number_format($purchaseorder->amount*$purchaseorder->exch_rate,2);
          }
          $purchaseorder->amount_d=number_format($purchaseorder->amount,2);
          return $purchaseorder;
        });*/
        //echo json_encode(['maindata'=>$purchaseorder,'categorydata'=>'','supplierdata'=>'']);

    }
    //Dye & Chem Purchase Order 
    if($menu_id==7){
        $purchaseorder =$this->podyechem
          ->selectRaw('
            po_dye_chems.id,
            po_dye_chems.company_id,
            po_dye_chems.po_no,
            po_dye_chems.po_date,
            po_dye_chems.itemcategory_id,
            po_dye_chems.currency_id,
            po_dye_chems.company_id,
            po_dye_chems.exch_rate,
            po_dye_chems.pi_no,
            po_dye_chems.pi_date,
            po_dye_chems.remarks,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.name as supplier_name,
            suppliers.id as supplier_id,
            inv_pur_reqs.requisition_no,
            itemcategories.name as itemcategory,
            itemclasses.name as itemclass_name,
            item_accounts.sub_class_name,
            item_accounts.item_description,
            item_accounts.specification,
            po_dye_chem_items.remarks as item_remarks,
            importLc.lc_no_i,
            importLc.lc_no_ii,
            importLc.lc_no_iii,
            importLc.lc_no_iv,
            po_dye_chem_items.id as po_item_id,
            po_dye_chem_items.qty,
            po_dye_chem_items.rate,
            po_dye_chem_items.amount,
            dye_rcv.rcv_qty,
            dye_rcv.rcv_amount
            ')
          ->leftJoin('companies',function($join){
              $join->on('companies.id','=','po_dye_chems.company_id');
            })
          ->leftJoin('suppliers',function($join){
              $join->on('suppliers.id','=','po_dye_chems.supplier_id');
            })
          ->leftJoin('currencies',function($join){
              $join->on('currencies.id','=','po_dye_chems.currency_id');
            })
            ->join('po_dye_chem_items', function($join){
              $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
            })
            ->join('inv_pur_req_items', function($join){
              $join->on('inv_pur_req_items.id', '=', 'po_dye_chem_items.inv_pur_req_item_id');
            })
            ->join('item_accounts', function($join){
              $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
            })
            ->join('itemclasses', function($join){
              $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
            })
            ->join('itemcategories', function($join){
              $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
            })
            ->join('inv_pur_reqs', function($join){
              $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
            })
            // ->leftJoin('imp_lc_pos',function($join){
            //   $join->on('imp_lc_pos.purchase_order_id','=','po_dye_chems.id');
            // })
            // ->leftJoin('imp_lcs',function($join){
            //   $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id')
            //   ->where([['imp_lcs.menu_id','=',$menu_id]]);
            // })
            ->leftJoin(\DB::raw("(
              select 
              imp_lc_pos.purchase_order_id,
              imp_lcs.lc_no_i,
              imp_lcs.lc_no_ii,
              imp_lcs.lc_no_iii,
              imp_lcs.lc_no_iv
              from imp_lc_pos
              join po_dye_chems on imp_lc_pos.purchase_order_id=po_dye_chems.id
              join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
              where imp_lcs.menu_id='".$menu_id."'
              group by 
              imp_lc_pos.purchase_order_id,
              imp_lcs.lc_no_i,
              imp_lcs.lc_no_ii,
              imp_lcs.lc_no_iii,
              imp_lcs.lc_no_iv
              ) importLc"), "importLc.purchase_order_id", "=", "po_dye_chems.id")
            ->leftJoin(\DB::raw("(
              select 
              po_dye_chem_items.id as po_dye_chem_item_id,
              sum(inv_dye_chem_rcv_items.qty) as rcv_qty,
              sum(inv_dye_chem_rcv_items.amount) as rcv_amount
            from po_dye_chem_items
                join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.po_dye_chem_item_id=po_dye_chem_items.id
            group by 
              po_dye_chem_items.id
            ) dye_rcv"), "dye_rcv.po_dye_chem_item_id", "=", "po_dye_chem_items.id")
            ->when(request('date_from'), function ($q) {
            return $q->where('po_dye_chems.po_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
                return $q->where('po_dye_chems.po_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('po_dye_chems.company_id', '=',$company_id);
            })
            ->when(request('supplier_id'), function ($q) use($supplier_id) {
                return $q->where('po_dye_chems.supplier_id', '=',$supplier_id);
            })
            ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
                return $q->where('po_dye_chems.itemcategory_id', '=', $itemcategory_id);
            })
            ->orderBy('po_dye_chems.company_id')
            ->orderBy('po_dye_chems.id','desc')
          ->get();
          /*->map(function($purchaseorder){
            $purchaseorder->po_date=date('d-M-Y',strtotime($purchaseorder->po_date));
            $purchaseorder->qty_d=number_format($purchaseorder->qty,2);
            $purchaseorder->rate_d=number_format($purchaseorder->rate,2);

            

            
            if($purchaseorder->currency_name=='BDT'){
              $purchaseorder->exch_rate=1;
              $purchaseorder->amount_tk=$purchaseorder->amount;
              $purchaseorder->amount_taka=number_format($purchaseorder->amount,2);
            }
            else{
              $purchaseorder->exch_rate=$purchaseorder->exch_rate;
              $purchaseorder->amount_tk=$purchaseorder->amount*$purchaseorder->exch_rate;
              $purchaseorder->amount_taka=number_format($purchaseorder->amount*$purchaseorder->exch_rate,2);
            }
            $purchaseorder->amount_d=number_format($purchaseorder->amount,2);
            return $purchaseorder;
          });*/
          //echo json_encode($this->_data($purchaseorder));
    }
    //General Item Purchase Worder
      if($menu_id==8){
          $purchaseorder =$this->pogeneral
            ->selectRaw('
              po_generals.id,
              po_generals.po_no,
              po_generals.po_date,
              po_generals.itemcategory_id,
              po_generals.currency_id,
              po_generals.company_id,
              po_generals.exch_rate,
              po_generals.pi_no,
              po_generals.pi_date,
              po_generals.remarks,
              companies.code as company_name,
              currencies.code as currency_name,
              suppliers.name as supplier_name,
              suppliers.id as supplier_id,
              inv_pur_reqs.requisition_no,
              itemcategories.name as itemcategory,
              itemclasses.name as itemclass_name,
              item_accounts.sub_class_name,
              item_accounts.item_description,
              item_accounts.specification,
              po_general_items.remarks as item_remarks,
              importLc.lc_no_i,
              importLc.lc_no_ii,
              importLc.lc_no_iii,
              importLc.lc_no_iv,
              po_general_items.id as po_item_id,
              po_general_items.qty,
              po_general_items.rate,
              po_general_items.amount,
              general_rcv.rcv_qty,
              general_rcv.rcv_amount
              ')
            ->leftJoin('companies',function($join){
                $join->on('companies.id','=','po_generals.company_id');
            })
            ->leftJoin('suppliers',function($join){
                $join->on('suppliers.id','=','po_generals.supplier_id');
            })
            ->leftJoin('currencies',function($join){
                $join->on('currencies.id','=','po_generals.currency_id');
            })
            ->join('po_general_items', function($join){
              $join->on('po_general_items.po_general_id', '=', 'po_generals.id');
            })
            ->join('inv_pur_req_items', function($join){
              $join->on('inv_pur_req_items.id', '=', 'po_general_items.inv_pur_req_item_id');
            })
            ->join('item_accounts', function($join){
              $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
            })
            ->join('itemclasses', function($join){
              $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
            })
            ->join('itemcategories', function($join){
              $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
            })
            ->join('inv_pur_reqs', function($join){
              $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
            })
            // ->leftJoin('imp_lc_pos',function($join){
            //   $join->on('imp_lc_pos.purchase_order_id','=','po_generals.id');
            // })
            // ->leftJoin('imp_lcs',function($join){
            //   $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id')
            //   ->where([['imp_lcs.menu_id','=',$menu_id]]);
            // })
            ->leftJoin(\DB::raw("(
              select 
              imp_lc_pos.purchase_order_id,
              imp_lcs.lc_no_i,
              imp_lcs.lc_no_ii,
              imp_lcs.lc_no_iii,
              imp_lcs.lc_no_iv
              from imp_lc_pos
              join po_generals on imp_lc_pos.purchase_order_id=po_generals.id
              join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
              where imp_lcs.menu_id='".$menu_id."'
              group by 
              imp_lc_pos.purchase_order_id,
              imp_lcs.lc_no_i,
              imp_lcs.lc_no_ii,
              imp_lcs.lc_no_iii,
              imp_lcs.lc_no_iv
              ) importLc"), "importLc.purchase_order_id", "=", "po_generals.id")
            ->leftJoin(\DB::raw("(
                select 
                po_general_items.id as po_general_item_id,
                sum(inv_general_rcv_items.qty) as rcv_qty,
                sum(inv_general_rcv_items.amount) as rcv_amount
                from po_general_items
                join inv_general_rcv_items on inv_general_rcv_items.po_general_item_id=po_general_items.id
                group by 
                po_general_items.id
              ) general_rcv"), "general_rcv.po_general_item_id", "=", "po_general_items.id")
            ->when(request('date_from'), function ($q) {
              return $q->where('po_generals.po_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
                return $q->where('po_generals.po_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('po_generals.company_id', '=',$company_id);
            })
            ->when(request('supplier_id'), function ($q) use($supplier_id) {
                return $q->where('po_generals.supplier_id', '=',$supplier_id);
            })
            ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
                return $q->where('po_generals.itemcategory_id', '=', $itemcategory_id);
            })
            ->orderBy('po_generals.company_id')
            ->orderBy('po_generals.id','desc')
            ->get();
      }
    //Yarn Dyeing Purchase Order
    if($menu_id==9){
        $yarnDescription=$this->invyarnitem
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_yarn_items.item_account_id'); 
        })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','inv_yarn_items.supplier_id'); 
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id'); 
        })
        ->leftJoin('item_account_ratios',function($join){
            $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('compositions',function($join){
            $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->where([['itemcategories.identity','=',1]])
        ->get([
            'inv_yarn_items.id as inv_yarn_item_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'compositions.name as composition_name',
            'item_account_ratios.ratio',
        ]);
        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->inv_yarn_item_id]['count']=$row->count."/".$row->symbol;
            $itemaccountArr[$row->inv_yarn_item_id]['yarn_type']=$row->yarn_type;
            $itemaccountArr[$row->inv_yarn_item_id]['itemclass_name']=$row->itemclass_name;
            $yarnCompositionArr[$row->inv_yarn_item_id][]=$row->composition_name." ".$row->ratio."%";
        }
        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
        }

        $purchaseorder =$this->poyarndyeing
        ->selectRaw('
          po_yarn_dyeings.id,
          po_yarn_dyeings.po_no,
          po_yarn_dyeings.po_date,
          po_yarn_dyeings.company_id,
          po_yarn_dyeings.supplier_id,
          po_yarn_dyeings.exch_rate,
          po_yarn_dyeings.pi_no,
          po_yarn_dyeings.pi_date,
          po_yarn_dyeings.remarks,
          po_yarn_dyeing_items.inv_yarn_item_id,
          po_yarn_dyeing_items.remarks as item_remarks,
          inv_yarn_items.item_account_id,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          suppliers.id as supplier_id,
          itemcategories.name as itemcategory,
          item_accounts.id as item_account_id,          
          item_accounts.item_description,
          item_accounts.specification,
          item_accounts.sub_class_name,
          itemclasses.name as itemclass_name,
          importLc.lc_no_i,
          importLc.lc_no_ii,
          importLc.lc_no_iii,
          importLc.lc_no_iv,
          po_yarn_dyeing_items.qty,
          po_yarn_dyeing_items.amount,
          po_yarn_dyeing_items.id as po_item_id,
          yarndyeing_rcv.rcv_qty,
          yarndyeing_rcv.rcv_amount
        ')
        ->join('companies',function($join){
          $join->on('companies.id','=','po_yarn_dyeings.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_yarn_dyeings.supplier_id');
        })
        ->join('currencies',function($join){
          $join->on('currencies.id','=','po_yarn_dyeings.currency_id');
        })
        ->leftJoin('po_yarn_dyeing_items',function($join){
          $join->on('po_yarn_dyeing_items.po_yarn_dyeing_id','=','po_yarn_dyeings.id')
          ->whereNull('po_yarn_dyeing_items.deleted_at');
        })
        ->leftJoin('inv_yarn_items', function($join){
          $join->on('inv_yarn_items.id', '=', 'po_yarn_dyeing_items.inv_yarn_item_id');
        })
        ->leftJoin('item_accounts', function($join){
          $join->on('item_accounts.id', '=', 'inv_yarn_items.item_account_id');
        })
        ->join('itemclasses',function($join){
          $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('itemcategories',function($join){
          $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->leftJoin(\DB::raw("(
          select 
          imp_lc_pos.purchase_order_id,
          imp_lcs.lc_no_i,
          imp_lcs.lc_no_ii,
          imp_lcs.lc_no_iii,
          imp_lcs.lc_no_iv
          from imp_lc_pos
          join po_yarn_dyeings on imp_lc_pos.purchase_order_id=po_yarn_dyeings.id
          join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
          where imp_lcs.menu_id='".$menu_id."'
          group by 
          imp_lc_pos.purchase_order_id,
          imp_lcs.lc_no_i,
          imp_lcs.lc_no_ii,
          imp_lcs.lc_no_iii,
          imp_lcs.lc_no_iv
          ) importLc"), "importLc.purchase_order_id", "=", "po_yarn_dyeings.id")
        ->leftJoin(\DB::raw("(
            select 
            po_yarn_dyeing_items.id as po_yarn_dyeing_item_id,
            sum(inv_yarn_rcv_items.qty) as rcv_qty,
            sum(inv_yarn_rcv_items.amount) as rcv_amount
            from po_yarn_dyeing_items
            left Join po_yarn_dyeing_item_bom_qties on po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id = po_yarn_dyeing_items.id 
            left Join inv_yarn_isu_items on inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id = po_yarn_dyeing_item_bom_qties.id 
            join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_isu_item_id = inv_yarn_isu_items.id 
            group by 
            po_yarn_dyeing_items.id
          ) yarndyeing_rcv"), "yarndyeing_rcv.po_yarn_dyeing_item_id", "=", "po_yarn_dyeing_items.id")
        ->when(request('date_from'), function ($q) {
          return $q->where('po_yarn_dyeings.po_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('po_yarn_dyeings.po_date', '<=',request('date_to', 0));
        })
        ->when(request('company_id'), function ($q) use($company_id) {
          return $q->where('po_yarn_dyeings.company_id', '=',$company_id);
        })
        ->when(request('supplier_id'), function ($q) use($supplier_id) {
          return $q->where('po_yarn_dyeings.supplier_id', '=',$supplier_id);
        })
        ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
          return $q->where('po_yarn_dyeings.itemcategory_id', '=', $itemcategory_id);
        })
        ->orderBy('po_yarn_dyeings.company_id')
        ->orderBy('po_yarn_dyeings.id','desc')
        ->get();
      }
    //Embelishment Service Order
    if ($menu_id==10) {
      $purchaseorder =$this->poembservice
        ->selectRaw('
            po_emb_services.id,
            po_emb_services.po_no,
            po_emb_services.po_date,
            po_emb_services.company_id,
            po_emb_services.supplier_id,
            po_emb_services.exch_rate,
            po_emb_services.pi_no,
            po_emb_services.pi_date,
            po_emb_services.remarks,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            suppliers.id as supplier_id,
            itemcategories.name as itemcategory,
            item_accounts.id as item_account_id,          
            item_accounts.item_description,
            item_accounts.specification,
            item_accounts.sub_class_name,
            itemclasses.name as itemclass_name,
            importLc.lc_no_i,
            importLc.lc_no_ii,
            importLc.lc_no_iii,
            importLc.lc_no_iv,
            po_emb_service_items.qty,
            po_emb_service_items.rate,
            po_emb_service_items.amount
          ')
          ->leftJoin('companies',function($join){
            $join->on('companies.id','=','po_emb_services.company_id');
          })
          ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_emb_services.supplier_id');
          })
          ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_emb_services.currency_id');
          })
          ->leftJoin('po_emb_service_items',function($join){
            $join->on('po_emb_service_items.po_emb_service_id','=','po_emb_services.id');
          })
          ->leftJoin('budget_embs',function($join){
            $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
          })
          ->leftJoin('style_embelishments',function($join){
            $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
          })
          ->leftJoin('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
          })
          ->leftJoin('item_accounts', function($join) {
            $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
          })
          ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
          })
          ->leftJoin('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
          })
          // ->leftJoin('imp_lc_pos',function($join){
          //   $join->on('imp_lc_pos.purchase_order_id','=','po_emb_services.id');
          // })
          // ->leftJoin('imp_lcs',function($join) use($menu_id) {
          //   $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id')
          //   ->where([['imp_lcs.menu_id','=',$menu_id]]);
          // })
          ->leftJoin(\DB::raw("(
            select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            from imp_lc_pos
            join po_emb_services on imp_lc_pos.purchase_order_id=po_emb_services.id
            join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
            where imp_lcs.menu_id='".$menu_id."'
            group by 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            ) importLc"), "importLc.purchase_order_id", "=", "po_emb_services.id")
          ->when(request('date_from'), function ($q) {
              return $q->where('po_emb_services.po_date', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
              return $q->where('po_emb_services.po_date', '<=',request('date_to', 0));
          })
          ->when(request('company_id'), function ($q) use($company_id) {
              return $q->where('po_emb_services.company_id', '=',$company_id);
          })
          ->when(request('supplier_id'), function ($q) use($supplier_id) {
              return $q->where('po_emb_services.supplier_id', '=',$supplier_id);
          })
          ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
              return $q->where('po_emb_services.itemcategory_id', '=', $itemcategory_id);
          })
          ->orderBy('po_emb_services.company_id')
          ->orderBy('po_emb_services.id','desc')
        ->get();
    }
    //General Service Work Order
    if ($menu_id==11) {
      $purchaseorder =$this->pogeneralservice
        ->selectRaw('
            po_general_services.id,
            po_general_services.po_no,
            po_general_services.po_date,
            po_general_services.company_id,
            po_general_services.supplier_id,
            po_general_services.exch_rate,
            po_general_services.pi_no,
            po_general_services.pi_date,
            po_general_services.remarks,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            suppliers.id as supplier_id,
            importLc.lc_no_i,
            importLc.lc_no_ii,
            importLc.lc_no_iii,
            importLc.lc_no_iv,
            po_general_service_items.service_description as item_description,
            po_general_service_items.remarks as item_remarks,
            po_general_service_items.qty,
            po_general_service_items.rate,
            po_general_service_items.amount
          ')
          ->leftJoin('companies',function($join){
            $join->on('companies.id','=','po_general_services.company_id');
          })
          ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_general_services.supplier_id');
          })
          ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_general_services.currency_id');
          })
          ->leftJoin('po_general_service_items',function($join){
            $join->on('po_general_service_items.po_general_service_id','=','po_general_services.id');
          })
          ->join('departments', function($join){
            $join->on('departments.id', '=', 'po_general_service_items.department_id');
          })
          ->join('users', function($join){
            $join->on('users.id', '=', 'po_general_service_items.demand_by_id');
          })
          ->leftJoin('asset_quantity_costs', function($join){
            $join->on('asset_quantity_costs.id', '=', 'po_general_service_items.asset_quantity_cost_id');
          })
          ->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
          })
          ->join('uoms', function($join){
            $join->on('uoms.id', '=', 'po_general_service_items.uom_id');
          })
          ->leftJoin(\DB::raw("(
            select 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            from imp_lc_pos
            join po_general_services on imp_lc_pos.purchase_order_id=po_general_services.id
            join imp_lcs on imp_lc_pos.imp_lc_id=imp_lcs.id
            where imp_lcs.menu_id='".$menu_id."'
            group by 
            imp_lc_pos.purchase_order_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv
            ) importLc"), "importLc.purchase_order_id", "=", "po_general_services.id")
          ->when(request('date_from'), function ($q) {
              return $q->where('po_general_services.po_date', '>=',request('date_from', 0));
          })
          ->when(request('date_to'), function ($q) {
              return $q->where('po_general_services.po_date', '<=',request('date_to', 0));
          })
          ->when(request('company_id'), function ($q) use($company_id) {
              return $q->where('po_general_services.company_id', '=',$company_id);
          })
          ->when(request('supplier_id'), function ($q) use($supplier_id) {
              return $q->where('po_general_services.supplier_id', '=',$supplier_id);
          })
          ->when(request('itemcategory_id'), function ($q) use($itemcategory_id) {
              return $q->where('po_general_services.itemcategory_id', '=', $itemcategory_id);
          })
          ->orderBy('po_general_services.company_id')
          ->orderBy('po_general_services.id','desc')
        ->get();
    }
    echo json_encode($this->_data($purchaseorder));
  }

  private function _data($purchaseorder){
    $yarnDescription=$this->itemaccount
    ->join('item_account_ratios',function($join){
      $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
    })
    ->join('yarncounts',function($join){
      $join->on('yarncounts.id','=','item_accounts.yarncount_id');
    })
    ->join('yarntypes',function($join){
      $join->on('yarntypes.id','=','item_accounts.yarntype_id');
    })
    ->join('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->join('compositions',function($join){
      $join->on('compositions.id','=','item_account_ratios.composition_id');
    })
    ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    ->get([
      'item_accounts.id',
      'yarncounts.count',
      'yarncounts.symbol',
      'yarntypes.name as yarn_type',
      'itemclasses.name as itemclass_name',
      'compositions.name as composition_name',
      'item_account_ratios.ratio'
    ]);
    $itemaccountArr=array();
    $yarnCompositionArr=array();
    foreach($yarnDescription as $row){
      $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
      $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
      $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
    }
    $yarnDropdown=array();
    $compDropdown=array();
    $typeDropdown=array();
    foreach($itemaccountArr as $key=>$value){
      $yarnDropdown[$key]=$value['count'];
      $compDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
      $typeDropdown[$key]=$value['yarn_type'];
    }

    $purchaseorder= $purchaseorder->map(function($purchaseorder) use($yarnDropdown,$compDropdown,$typeDropdown) {

      $purchaseorder->composition=isset($compDropdown[$purchaseorder->item_account_id])?$compDropdown[$purchaseorder->item_account_id]:'';
      $purchaseorder->count_name=isset($yarnDropdown[$purchaseorder->item_account_id])?$yarnDropdown[$purchaseorder->item_account_id]:'';
      $purchaseorder->yarn_type=isset($typeDropdown[$purchaseorder->item_account_id])?$typeDropdown[$purchaseorder->item_account_id]:'';

      $purchaseorder->po_date=$purchaseorder->po_date?date('d-M-Y',strtotime($purchaseorder->po_date)):'--';
      $purchaseorder->pi_date=$purchaseorder->pi_date?date('d-M-Y',strtotime($purchaseorder->pi_date)):'--';
      //$purchaseorder->lc_no=$purchaseorder->lc_no_i." ".$purchaseorder->lc_no_ii." ".$purchaseorder->lc_no_iii." ".$purchaseorder->lc_no_iv;
      $purchaseorder->import_lc_no=$purchaseorder->lc_no_i.$purchaseorder->lc_no_ii.$purchaseorder->lc_no_iii.$purchaseorder->lc_no_iv;
      $purchaseorder->lc_no=($purchaseorder->lc_no_i!==null)?$purchaseorder->import_lc_no:'--';
      $purchaseorder->pi_no=($purchaseorder->pi_no)?$purchaseorder->pi_no:'--';
      $balance_qty=$purchaseorder->qty-$purchaseorder->rcv_qty;
      $purchaseorder->balance_qty=number_format($balance_qty,2);
      $purchaseorder->rcv_qty_d=number_format($purchaseorder->rcv_qty,2);
      $purchaseorder->qty_d=number_format($purchaseorder->qty,2);
      $purchaseorder->rate_d=number_format($purchaseorder->rate,2);

      if($purchaseorder->currency_name=='BDT'){
        $purchaseorder->exch_rate=1;
        $purchaseorder->amount_tk=$purchaseorder->amount;
        $purchaseorder->rcv_amount_tk=$purchaseorder->rcv_amount;
        $purchaseorder->total_amount_tk=$purchaseorder->amount_tk-$purchaseorder->rcv_amount_tk;
        $purchaseorder->balance_amount_taka=number_format($purchaseorder->total_amount_tk,2);
        $purchaseorder->amount_taka=number_format($purchaseorder->amount,2);
        $purchaseorder->rcv_amount_taka=number_format($purchaseorder->rcv_amount_tk,2);
      }
      else{
        $purchaseorder->exch_rate=$purchaseorder->exch_rate;
        $purchaseorder->amount_tk=$purchaseorder->amount*$purchaseorder->exch_rate;
        $purchaseorder->rcv_amount_tk=$purchaseorder->rcv_amount*$purchaseorder->exch_rate;
        $balance_amount_tk=$purchaseorder->amount_tk-$purchaseorder->rcv_amount_tk;
        $purchaseorder->balance_amount_taka=number_format($balance_amount_tk,2);
        $purchaseorder->amount_taka=number_format($purchaseorder->amount*$purchaseorder->exch_rate,2);
        $purchaseorder->rcv_amount_taka=number_format($purchaseorder->rcv_amount_tk,2);
      }
      $balance_amount_d=$purchaseorder->amount-$purchaseorder->rcv_amount;
        $purchaseorder->balance_amount_d=number_format($balance_amount_d,2);
        $purchaseorder->amount_d=number_format($purchaseorder->amount,2);
        $purchaseorder->rcv_amount_d=number_format($purchaseorder->rcv_amount,2);
      return $purchaseorder;
      });

      $category=[];
      $supplier=[];
      $po=[];
      foreach($purchaseorder as $row){
      $category[$row->itemcategory]['no_of_po'][$row->id]=$row->po_no;
      $category[$row->itemcategory]['no_of_supplier'][$row->supplier_id]=$row->supplier_name;
      $category[$row->itemcategory]['qty']=isset($category[$row->itemcategory]['qty'])?$category[$row->itemcategory]['qty']+=$row->qty:$row->qty;
      $category[$row->itemcategory]['amount_taka']=isset($category[$row->itemcategory]['amount_taka'])?$category[$row->itemcategory]['amount_taka']+=$row->amount_tk:$row->amount_tk;

      if($row->currency_name=='USD'){
      $category[$row->itemcategory]['po_usd']=isset($category[$row->itemcategory]['po_usd'])?$category[$row->itemcategory]['po_usd']+=$row->amount:$row->amount;

      }
      else if ($row->currency_name=='BDT'){
      $category[$row->itemcategory]['po_taka']=isset($category[$row->itemcategory]['po_taka'])?$category[$row->itemcategory]['po_taka']+=$row->amount:$row->amount;
      }
      else{
      $category[$row->itemcategory]['po_oth']=isset($category[$row->itemcategory]['po_oth'])?$category[$row->itemcategory]['po_oth']+=$row->amount:$row->amount;
      }

      $supplier[$row->supplier_id][$row->itemcategory]['supplier_name']=$row->supplier_name;
      $supplier[$row->supplier_id][$row->itemcategory]['no_of_po'][$row->id]=$row->po_no;

      $supplier[$row->supplier_id][$row->itemcategory]['qty']=isset($supplier[$row->supplier_id][$row->itemcategory]['qty'])?$supplier[$row->supplier_id][$row->itemcategory]['qty']+=$row->qty:$row->qty;

      $supplier[$row->supplier_id][$row->itemcategory]['amount_taka']=isset($supplier[$row->supplier_id][$row->itemcategory]['amount_taka'])?$supplier[$row->supplier_id][$row->itemcategory]['amount_taka']+=$row->amount_tk:$row->amount_tk;

      if($row->currency_name=='USD'){
      $supplier[$row->supplier_id][$row->itemcategory]['po_usd']=isset($supplier[$row->supplier_id][$row->itemcategory]['po_usd'])?$supplier[$row->supplier_id][$row->itemcategory]['po_usd']+=$row->amount:$row->amount;
      }
      else if ($row->currency_name=='BDT'){
      $supplier[$row->supplier_id][$row->itemcategory]['po_taka']=isset($supplier[$row->supplier_id][$row->itemcategory]['po_taka'])?$supplier[$row->supplier_id][$row->itemcategory]['po_taka']+=$row->amount:$row->amount;
      }
      else{
      $supplier[$row->supplier_id][$row->itemcategory]['po_oth']=isset($supplier[$row->supplier_id][$row->itemcategory]['po_oth'])?$supplier[$row->supplier_id][$row->itemcategory]['po_oth']+=$row->amount:$row->amount;
      }

      $po[$row->id]['po_no']=$row->po_no;
      $po[$row->id]['po_date']=$row->po_date;
      $po[$row->id]['company_name']=$row->company_name;
      $po[$row->id]['supplier_name']=$row->supplier_name;
      $po[$row->id]['remarks']=$row->remarks;
      $po[$row->id]['qty']=isset($po[$row->id]['qty'])?$po[$row->id]['qty']+=$row->qty:$row->qty;
      $po[$row->id]['amount']=isset($po[$row->id]['amount'])?$po[$row->id]['amount']+=$row->amount:$row->amount;
      $po[$row->id]['currency_name']=$row->currency_name;
      if($row->currency_name=='BDT'){
      $po[$row->id]['exch_rate']=1;
      }
      else{
      $po[$row->id]['exch_rate']=$row->exch_rate;
      }
      $po[$row->id]['amount_taka']=isset($po[$row->id]['amount_taka'])?$po[$row->id]['amount_taka']+=$row->amount_tk:$row->amount_tk;
      }

      $categoryDatas=[];
      foreach($category as $key=>$value){
      $categoryData['category_name']=$key;
      $categoryData['no_of_po']=number_format(count($value['no_of_po']),0);
      $categoryData['no_of_supplier']=number_format(count($value['no_of_supplier']),0);
      $categoryData['qty']=number_format($value['qty'],0);
      $categoryData['amount_taka']=number_format($value['amount_taka'],2);
      $categoryData['po_usd']=isset($value['po_usd'])?number_format($value['po_usd'],2):number_format(0,2);
      $categoryData['po_taka']=isset($value['po_taka'])?number_format($value['po_taka'],2):number_format(0,2);
      $categoryData['po_oth']=isset($value['po_oth'])?number_format($value['po_oth'],2):number_format(0,2);
      array_push($categoryDatas, $categoryData);
      }
      $supplierDatas=[];

      foreach($supplier as $supp=>$suppliervalue){
      $no_of_po=0;
      $qty=0;
      $amount_taka=0;
      $po_usd=0;
      $po_taka=0;
      foreach($suppliervalue as $key=>$value){
      $supplierData['supplier_name']=$value['supplier_name'];
      $supplierData['category_name']=$key;
      $supplierData['no_of_po']=number_format(count($value['no_of_po']),0);

      $supplierData['qty']=number_format($value['qty'],0);
      $supplierData['amount_taka']=number_format($value['amount_taka'],2);
      $supplierData['po_usd']=isset($value['po_usd'])?number_format($value['po_usd'],2):number_format(0,2);
      $supplierData['po_taka']=isset($value['po_taka'])?number_format($value['po_taka'],2):number_format(0,2);
      $supplierData['po_oth']=isset($value['po_oth'])?number_format($value['po_oth'],2):number_format(0,2);

      $no_of_po+=count($value['no_of_po']);
      $qty+=$value['qty'];
      $amount_taka+=$value['amount_taka'];
      $po_usd+=isset($value['po_usd'])?$value['po_usd']:0;
      $po_taka+=isset($value['po_taka'])?$value['po_taka']:0;
      array_push($supplierDatas, $supplierData);
      }
      $subTot = collect([
      'supplier_name'=>'Sub Total',
      'no_of_po'=>number_format($no_of_po,'0','.',','),
      'qty'=>number_format($qty,'0','.',','),
      'amount_taka'=>number_format($amount_taka,'0','.',','),
      'po_usd'=>number_format($po_usd,'0','.',','),
      'po_taka'=>number_format($po_taka,'0','.',',')
      ]);
      array_push($supplierDatas,$subTot);
      }

      $poDatas=[];
      foreach($po as $key=>$value)
      {
      $poData['po_no']=$value['po_no'];
      $poData['po_date']=$value['po_date'];
      $poData['company_name']=$value['company_name'];
      $poData['supplier_name']=$value['supplier_name'];
      $poData['remarks']=$value['remarks'];
      $poData['qty']=number_format($value['qty'],0);
      $poData['amount']=number_format($value['amount'],2);
      $poData['currency_name']=$value['currency_name'];
      $poData['exch_rate']=$value['exch_rate'];
      $poData['amount_taka']=number_format($value['amount_taka'],2);
      array_push($poDatas, $poData);
      }
      return ['maindata'=>$purchaseorder,'categorydata'=>$categoryDatas,'supplierdata'=>$supplierDatas,'podata'=>$poDatas];

  }

  public function getRcvNo(){
    $menu_id=request('menu_id',0);
    $po_item_id=request('po_item_id',0);
    if ($menu_id==1) {
      $fabricrcv=$this->pofabricitem
      ->join('inv_finish_fab_rcv_fabrics', function($join){
        $join->on('inv_finish_fab_rcv_fabrics.po_fabric_item_id', '=', 'po_fabric_items.id');
      })
      ->join('inv_finish_fab_rcv_items', function($join){
        $join->on('inv_finish_fab_rcv_fabrics.id', '=', 'inv_finish_fab_rcv_items.inv_finish_fab_rcv_fabric_id');
      })
      ->join('inv_finish_fab_rcvs',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.inv_finish_fab_rcv_id','=','inv_finish_fab_rcvs.id');
      })
      ->join('inv_rcvs',function($join){
        $join->on('inv_finish_fab_rcvs.inv_rcv_id','=','inv_rcvs.id');
      })
      ->where([['po_fabric_items.id','=',$po_item_id]])
      ->get([
        'po_fabric_items.id as po_item_id',
        'inv_rcvs.receive_no',
        'inv_rcvs.receive_date',
        'inv_rcvs.challan_no',
        'inv_rcvs.remarks',
        'inv_finish_fab_rcv_items.id as inv_rcv_item_id',
        'inv_finish_fab_rcv_items.qty',
        'inv_finish_fab_rcv_items.rate',
        'inv_finish_fab_rcv_items.amount',
        'inv_finish_fab_rcv_items.store_qty',
        'inv_finish_fab_rcv_items.store_amount',
      ])
      ->map(function($fabricrcv){
        $fabricrcv->qty=number_format($fabricrcv->qty,2);
        $fabricrcv->rate=number_format($fabricrcv->rate,4);
        $fabricrcv->amount=number_format($fabricrcv->amount,2);
        $fabricrcv->store_qty=number_format($fabricrcv->store_qty,2);
        $fabricrcv->store_amount=number_format($fabricrcv->store_amount,2);
        return $fabricrcv;
      });
      echo json_encode($fabricrcv);
    }
    if ($menu_id==2) {
      $trimrcv=$this->potrimitem
      ->join('po_trim_item_reports', function($join){
        $join->on('po_trim_items.id', '=', 'po_trim_item_reports.po_trim_item_id');
      })
      ->join('inv_trim_rcv_items', function($join){
        $join->on('po_trim_item_reports.id', '=', 'inv_trim_rcv_items.po_trim_item_report_id');
      })
      ->join('inv_trim_rcvs',function($join){
        $join->on('inv_trim_rcv_items.inv_trim_rcv_id','=','inv_trim_rcvs.id');
      })
      ->join('inv_rcvs',function($join){
        $join->on('inv_trim_rcvs.inv_rcv_id','=','inv_rcvs.id');
      })
      ->where([['po_trim_items.id','=',$po_item_id]])
      ->get([
        'po_trim_items.id as po_item_id',
        'inv_rcvs.receive_no',
        'inv_rcvs.receive_date',
        'inv_rcvs.challan_no',
        'inv_rcvs.remarks',
        'inv_trim_rcv_items.id as inv_rcv_item_id',
        'inv_trim_rcv_items.qty',
        'inv_trim_rcv_items.rate',
        'inv_trim_rcv_items.amount',
        'inv_trim_rcv_items.store_qty',
        'inv_trim_rcv_items.store_amount',
      ])
      ->map(function($trimrcv){
        $trimrcv->qty=number_format($trimrcv->qty,2);
        $trimrcv->rate=number_format($trimrcv->rate,4);
        $trimrcv->amount=number_format($trimrcv->amount,2);
        $trimrcv->store_qty=number_format($trimrcv->store_qty,2);
        $trimrcv->store_amount=number_format($trimrcv->store_amount,2);
        return $trimrcv;
      });
      echo json_encode($trimrcv);
    }
    if ($menu_id==3) {
      $yarnrcv=$this->poyarnitem
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
      ->where([['po_yarn_items.id','=',$po_item_id]])
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
        $yarnrcv->qty=number_format($yarnrcv->qty,2);
        $yarnrcv->rate=number_format($yarnrcv->rate,4);
        $yarnrcv->amount=number_format($yarnrcv->amount,2);
        $yarnrcv->store_qty=number_format($yarnrcv->store_qty,2);
        $yarnrcv->store_amount=number_format($yarnrcv->store_amount,2);
        return $yarnrcv;
      });
      echo json_encode($yarnrcv);

    }
    if ($menu_id==7) {
      $dyechemrcv=$this->podyechemitem
      ->leftJoin('inv_dye_chem_rcv_items', function($join){
        $join->on('po_dye_chem_items.id', '=', 'inv_dye_chem_rcv_items.po_dye_chem_item_id');
      })
      ->leftJoin('inv_dye_chem_rcvs',function($join){
        $join->on('inv_dye_chem_rcv_items.inv_dye_chem_rcv_id','=','inv_dye_chem_rcvs.id');
      //->whereNull('inv_dye_chem_rcv_items.deleted_at');
      })
      ->leftJoin('inv_rcvs',function($join){
        $join->on('inv_dye_chem_rcvs.inv_rcv_id','=','inv_rcvs.id');
      })
      ->where([['po_dye_chem_items.id','=',$po_item_id]])
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
      ->map(function($dyechemrcv){
        $dyechemrcv->qty=number_format($dyechemrcv->qty,2);
        $dyechemrcv->rate=number_format($dyechemrcv->rate,4);
        $dyechemrcv->amount=number_format($dyechemrcv->amount,2);
        $dyechemrcv->store_qty=number_format($dyechemrcv->store_qty,2);
        $dyechemrcv->store_amount=number_format($dyechemrcv->store_amount,2);
        return $dyechemrcv;
      });

      echo json_encode($dyechemrcv);
    }
    if ($menu_id==8) {
      $generalrcv=$this->pogeneralitem
      ->leftJoin('inv_general_rcv_items',function($join){
        $join->on('po_general_items.id','=','inv_general_rcv_items.po_general_item_id')
        ->whereNull('inv_general_rcv_items.deleted_at');
      })
      ->join('inv_general_rcvs',function($join){
        $join->on('inv_general_rcv_items.inv_general_rcv_id','=','inv_general_rcvs.id');
      })
      ->join('inv_rcvs',function($join){
        $join->on('inv_general_rcvs.inv_rcv_id','=','inv_rcvs.id');
      })
      ->where([['po_general_items.id','=',$po_item_id]])
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
        $generalrcv->qty=number_format($generalrcv->qty,2);
        $generalrcv->rate=number_format($generalrcv->rate,4);
        $generalrcv->amount=number_format($generalrcv->amount,2);
        $generalrcv->store_qty=number_format($generalrcv->store_qty,2);
        $generalrcv->store_amount=number_format($generalrcv->store_amount,2);
        return $generalrcv;
      });

      echo json_encode($generalrcv);
    }
    if($menu_id==9){
      $yarndyeingrcv=$this->poyarndyeingitem
      ->leftJoin('po_yarn_dyeing_item_bom_qties',function($join){
        $join->on('po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id','=','po_yarn_dyeing_items.id');
      })
      ->leftJoin('inv_yarn_isu_items',function($join){
        $join->on('inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id','=','po_yarn_dyeing_item_bom_qties.id');
      })
      ->join('inv_yarn_rcv_items',function($join){
        $join->on('inv_yarn_rcv_items.inv_yarn_isu_item_id','=','inv_yarn_isu_items.id');
      })
      ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id');
      })
      ->join('inv_rcvs',function($join){
        $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
      })
      ->where([['po_yarn_dyeing_items.id','=',$po_item_id]])
      ->get([
        'po_yarn_dyeing_items.id as po_item_id',
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
      ->map(function($yarndyeingrcv){
        $yarndyeingrcv->qty=number_format($yarndyeingrcv->qty,2);
        $yarndyeingrcv->rate=number_format($yarndyeingrcv->rate,4);
        $yarndyeingrcv->amount=number_format($yarndyeingrcv->amount,2);
        $yarndyeingrcv->store_qty=number_format($yarndyeingrcv->store_qty,2);
        $yarndyeingrcv->store_amount=number_format($yarndyeingrcv->store_amount,2);
        return $yarndyeingrcv;
      });

      echo json_encode($yarndyeingrcv);
    }
  }

}