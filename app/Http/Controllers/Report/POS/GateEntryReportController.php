<?php
namespace App\Http\Controllers\Report\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Purchase\PoDyeChemRepository;
use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Repositories\Contracts\Purchase\PoGeneralRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Purchase\PoAopServiceRepository;
use App\Repositories\Contracts\Purchase\PoGeneralServiceRepository;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;


use App\Repositories\Contracts\GateEntry\GateEntryRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\GateEntry\GateEntryItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;

class GateEntryReportController extends Controller
{
    private $user;
    private $supplier;
    private $company;
    private $gateentry;
    private $salesordergmtcolorsize;
    private $gateentryitem;
    private $purchaseorder;
    private $poyarn;
    private $potrim;
    private $podyechem;
    private $podyeingservice;
    private $pogeneral;
    private $poknitservice;
    private $itemaccount;
    private $invpurreq;
    private $budgetfabric;
    private $poaopservice;
    private $pogeneralservice;
    private $poyarndyeing;
    private $invyarnitem;


	public function __construct(
        SupplierRepository $supplier,
        UserRepository $user,
        GateEntryRepository $gateentry,
        SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
        CompanyRepository $company,
        GateEntryItemRepository $gateentryitem, 
        PoFabricRepository $pofabric,
        PoTrimRepository $potrim,
        PoDyeChemRepository $podyechem,
        PoDyeingServiceRepository $podyeingservice,
        PoGeneralRepository $pogeneral,
        PoKnitServiceRepository $poknitservice,
        PoEmbServiceRepository $poembservice,
        PoGeneralServiceRepository $pogeneralservice,
        PoYarnRepository $poyarn,
        ImpLcRepository $implc,
        ItemAccountRepository $itemaccount,
        InvPurReqRepository $invpurreq,
        BudgetFabricRepository $budgetfabric,
        PoAopServiceRepository $poaopservice,
        PoYarnDyeingRepository $poyarndyeing,
        InvYarnItemRepository $invyarnitem
  )
    {
        $this->supplier = $supplier;
        $this->user = $user;
        $this->gateentry  = $gateentry;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->company = $company;
        $this->gateentryitem = $gateentryitem;
        $this->poyarn = $poyarn;
        $this->potrim = $potrim;
        $this->podyechem = $podyechem;
        $this->podyeingservice = $podyeingservice;
        $this->pogeneral = $pogeneral;
        $this->poknitservice = $poknitservice;
        $this->pogeneralservice = $pogeneralservice;
        $this->pofabric = $pofabric;
        $this->implc = $implc;
        $this->itemaccount = $itemaccount;
        $this->invyarnitem = $invyarnitem;
        $this->invpurreq = $invpurreq;
        $this->budgetfabric = $budgetfabric;
        $this->poaopservice = $poaopservice;
        $this->poyarndyeing = $poyarndyeing;
        $this->poembservice = $poembservice;


    $this->middleware('auth');
		//$this->middleware('permission:view.gateentryreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $menu=array_prepend(array_only(config('bprs.menu'),[1,2,3,4,5,6,7,8,9,10,11,103]),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      return Template::loadView('Report.POS.GateEntryReport',['company'=>$company,'menu'=>$menu,'supplier'=>$supplier]);
    }
	public function reportData() {
        $menu_id=request('menu_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $supplier_id=request('supplier_id',0);
        $po_pr_no=request('po_pr_no',0);
        //Fabric Purchase Order
        if($menu_id==1){
            $purchaseorder = $this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                gate_entries.created_by,
                gate_entries.created_at,
                po_fabrics.po_no as fabric_po_no,
                po_fabrics.company_id,
                po_fabrics.supplier_id,
                companies.code as fabric_company,
                suppliers.name as fabric_supplier_name,
                suppliers.address as fabric_supplier_adress,
                item_accounts.id as item_account_id,          
                item_accounts.item_description as fabric_itemdesc,
                item_accounts.specification,
                item_accounts.sub_class_name,
                item_accounts.uom_id,
                budget_fabrics.style_fabrication_id,
                uoms.code as fabric_uom_code,
                users.name as create_user_name,
                po_fabric_items.rate,
                gate_entry_items.qty
                ')
                ->join('po_fabrics',function($join){
                    $join->on('po_fabrics.id','=','gate_entries.barcode_no_id');
                })
                ->join('companies',function($join){
                    $join->on('companies.id','=','po_fabrics.company_id');
                })
                ->join('suppliers',function($join){
                    $join->on('suppliers.id','=','po_fabrics.supplier_id');
                })
                ->join('po_fabric_items',function($join){
                    $join->on('po_fabric_items.po_fabric_id','=','po_fabrics.id')
                ->whereNull('po_fabric_items.deleted_at');
                })
                ->join('budget_fabrics',function($join){
                    $join->on('budget_fabrics.id','=','po_fabric_items.budget_fabric_id');
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
                ->join('users',function($join){
                    $join->on('users.id','=','gate_entries.created_by');
                })
                ->join('gate_entry_items',function($join){
                    $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                    $join->on('po_fabric_items.id','=','gate_entry_items.item_id');
                })
                ->where([['gate_entries.menu_id','=',$menu_id]])
                ->when(request('date_from'), function ($q) {
                    return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
                })
                ->when(request('date_to'), function ($q) {
                    return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
                })
                ->when(request('company_id'), function ($q) use($company_id) {
                    return $q->where('po_fabrics.company_id', '=', $company_id);
                })
                ->when(request('supplier_id'), function ($q) use($supplier_id) {
                    return $q->where('po_fabrics.supplier_id', '=', $supplier_id);
                })
                ->when(request('po_pr_no'), function ($q) use($po_pr_no) {
                    return $q->where('po_fabrics.po_no', '=', $po_pr_no);
                })
                ->orderBy('gate_entries.created_at','desc')
                ->get()
                ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->fabric_po_no;
                $purchaseorder->company_code=$purchaseorder->fabric_company;
                $purchaseorder->item_description=$purchaseorder->fabric_itemdesc;
                $purchaseorder->uom_code=$purchaseorder->fabric_uom_code;
                $purchaseorder->supplier_name=$purchaseorder->fabric_supplier_name.",".$purchaseorder->fabric_supplier_adress;
                $purchaseorder->receive_no='';
                if ($purchaseorder->qty) {
                    $purchaseorder->amount=$purchaseorder->qty*$purchaseorder->rate;
                }
                $purchaseorder->entry_date=date('d-M-Y',strtotime($purchaseorder->entry_date));
                $purchaseorder->amount=number_format($purchaseorder->amount,2);
                $purchaseorder->qty=number_format($purchaseorder->qty,2);
                $purchaseorder->rcv_qty=number_format(0,2);
                $purchaseorder->purchaseThrough="Fabric Purchase Order";
                return $purchaseorder;
            });

            //$purchaseThrough="Fabric Purchase Order";
            //$category=$purchaseorder;
            //return Template::loadView('Report.POS.GateEntryOrderMatrix',['category'=>$category,'purchaseThrough'=>$purchaseThrough]);
            echo json_encode($purchaseorder);
        }
        //Trims Purchase Order
        if($menu_id==2){
            $purchaseorder =$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                gate_entries.created_by,
                gate_entries.created_at,
                po_trims.po_no as trim_po_no,
                po_trims.supplier_id,
                po_trims.company_id,
                po_trims.remarks as po_trim_remarks,
                suppliers.name as trim_supplier_name,
                companies.code as trim_company,
                itemclasses.itemcategory_id,
                itemcategories.name as itemcategory_name,
                item_accounts.specification,
                item_accounts.sub_class_name,
                budget_trims.uom_id,
                itemclasses.name as itemclass_name,
                uoms.code as trim_uom_code,
                users.name as create_user_name,
                po_trim_items.rate,
                gate_entry_items.qty as trim_qty
            ')
            ->join('po_trims',function($join){
                $join->on('po_trims.id','=','gate_entries.barcode_no_id');
            })
            ->leftJoin('users',function($join){
                $join->on('users.id','=','gate_entries.created_by');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','po_trims.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_trims.supplier_id');
            })
            ->join('po_trim_items',function($join){
                $join->on('po_trims.id','=','po_trim_items.po_trim_id');
            })
            ->join('budget_trims',function($join){
                $join->on('po_trim_items.budget_trim_id','=','budget_trims.id')
                ->whereNull('po_trim_items.deleted_at');
            })
            ->leftJoin('itemclasses', function($join){
                $join->on('itemclasses.id', '=','budget_trims.itemclass_id');
            })
            ->join('uoms',function($join){
                $join->on('uoms.id','=','budget_trims.uom_id');
            })
            ->leftJoin('itemcategories', function($join){
                $join->on('itemcategories.id', '=','itemclasses.itemcategory_id');
            })
            ->leftJoin('item_accounts',function($join){
                $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->join('gate_entry_items',function($join){
                $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                $join->on('po_trim_items.id','=','gate_entry_items.item_id');
            })
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->when(request('date_from'), function ($q) {
                return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
              return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('po_trims.company_id', '=', $company_id);
            })
            ->when(request('supplier_id'), function ($q) use($supplier_id) {
                return $q->where('po_trims.supplier_id', '=', $supplier_id);
            })
            ->when(request('po_pr_no'), function ($q) use($po_pr_no) {
                return $q->where('po_trims.po_no', '=', $po_pr_no );
            })
            ->orderBy('gate_entries.created_at','desc')
            ->get()
            ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->trim_po_no;
                $purchaseorder->supplier_name=$purchaseorder->trim_supplier_name;
                $purchaseorder->company_code=$purchaseorder->trim_company;
                $purchaseorder->qty=$purchaseorder->trim_qty;
                $purchaseorder->uom_code=$purchaseorder->trim_uom_code;
                $purchaseorder->item_description=$purchaseorder->itemclass_name;
                $purchaseorder->receive_no='';
                if ($purchaseorder->qty) {
                    $purchaseorder->amount=$purchaseorder->qty*$purchaseorder->rate;
                }
                $purchaseorder->amount=number_format($purchaseorder->amount,2);
                $purchaseorder->qty=number_format($purchaseorder->qty,2);
                $purchaseorder->entry_date=date('d-M-Y',strtotime($purchaseorder->created_at));
                $purchaseorder->rcv_qty=number_format(0,2);
                //$purchaseorder->purchaseThrough="Trims Purchase";
                return $purchaseorder;
            });
            echo json_encode($purchaseorder);
            // $itemCatArr=array();
            // foreach($purchaseorder as $row){
            //     $itemCatArr[$row->itemcategory_id]['itemcategory_name']=$row->itemcategory_name;
            //     $itemCatArr[$row->itemcategory_id]['created_at']=$row->created_at;
            //     $itemCatArr[$row->itemcategory_id]['create_user_name']=$row->create_user_name;
            //     $itemCatArr[$row->itemcategory_id]['company_code']=$row->company_code;
            //     $itemCatArr[$row->itemcategory_id]['po_pr_no']=$row->po_pr_no;
            //     $itemCatArr[$row->itemcategory_id]['barcode_no_id']=$row->barcode_no_id;
            //     $itemCatArr[$row->itemcategory_id]['challan_no']=$row->challan_no;
            //     $itemCatArr[$row->itemcategory_id]['item_description']=$row->item_description/* .", ".$row->purchase_item_desc.", ".$row->specification */;
            //     $itemCatArr[$row->itemcategory_id]['uom_code']=$row->trim_uom_code;
            //     $itemCatArr[$row->itemcategory_id]['qty']=$row->qty;
            //     $itemCatArr[$row->itemcategory_id]['amount']=$row->amount;
            //     $itemCatArr[$row->itemcategory_id]['supplier_name']=$row->supplier_name;
            //     //$itemCatArr[$row->itemcategory_id]['remarks']=$row->remarks;
            // }
            // $category=$purchaseorder->groupBy('itemcategory_id');
           // $purchaseThrough="Trims Purchase";
            
            //return Template::loadView('Report.POS.GateEntryServiceMatrix',['category'=>$category,'itemCatArr'=>$itemCatArr,'purchaseThrough'=>$purchaseThrough]);
        }
        //Yarn Purchase Order 
        if($menu_id==3){

            $yarnDescription=$this->itemaccount
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
                //->where([['itemcategories.identity','=',1]])
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
                //$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
                $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
            }
            $yarnDropdown=array();
            foreach($itemaccountArr as $key=>$value){
                $yarnDropdown[$key]=/* $value['itemclass_name']." ". */$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
            }

            $receive=$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entry_items.id as gate_entry_items_id,
                inv_rcvs.receive_no
            ')
            ->join('gate_entry_items',function($join){
                $join->on('gate_entry_items.gate_entry_id','=','gate_entries.id');
            })
            ->join('po_yarns',function($join){
                $join->on('po_yarns.id','=','gate_entries.barcode_no_id');
            })
            ->join('po_yarn_items', function($join){
                $join->on('po_yarn_items.po_yarn_id', '=', 'po_yarns.id');
                $join->on('po_yarn_items.id','=','gate_entry_items.item_id');
            })
            ->join('inv_yarn_rcv_items', function($join){
                $join->on('inv_yarn_rcv_items.po_yarn_item_id', '=','po_yarn_items.id' );
            })
            ->join('inv_yarn_rcvs',function($join){
                $join->on('inv_yarn_rcvs.id','=','inv_yarn_rcv_items.inv_yarn_rcv_id');
            })
            ->join('inv_rcvs',function($join){
                $join->on('inv_rcvs.id','=','inv_yarn_rcvs.inv_rcv_id');
            })
            ->whereColumn([['gate_entries.challan_no','=','inv_rcvs.challan_no']])
            ->groupBy([
                'gate_entries.id',
                'gate_entry_items.id',
                'inv_rcvs.receive_no',
            ])
            ->get();

            
            $poArr=array();
            foreach($receive as $row){
                $poArr[$row->gate_entry_items_id][]=$row->receive_no;
            }

            $rcvarr=array();
            foreach ($poArr as $key => $value) {
                $rcvarr[$key]=$value;
            }
            //dd($rcvarr);die;

            $purchaseorder =$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                gate_entries.created_by,
                gate_entries.created_at,
                po_yarns.po_no as yarn_po_no,
                po_yarns.company_id,
                po_yarns.supplier_id,
                po_yarn_items.item_account_id,
                po_yarn_items.remarks as yarn_item_remarks,
                companies.code as yarn_company,
                suppliers.name as yarn_supplier_name,
                suppliers.address as yarn_supplier_address,
                itemcategories.name as itemcategory_name,
                item_accounts.id as item_account_id,
                item_accounts.uom_id,
                itemclasses.name as itemclass_name,
                uoms.code as yarn_uom_code,
                users.name as create_user_name,
                yarn_rcv.receive_date,
                yarn_rcv.rcv_qty,
                gate_entry_items.id as gate_entry_items_id,
                po_yarn_items.rate,
                gate_entry_items.qty as yarn_qty
            ')
            ->join('po_yarns',function($join){
                $join->on('po_yarns.id','=','gate_entries.barcode_no_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','po_yarns.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_yarns.supplier_id');
            })
            ->join('po_yarn_items',function($join){
                $join->on('po_yarn_items.po_yarn_id','=','po_yarns.id')
            ->whereNull('po_yarn_items.deleted_at');
            })
            ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'po_yarn_items.item_account_id');
            })
            ->join('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->join('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->join('users',function($join){
                $join->on('users.id','=','gate_entries.created_by');
            })
            ->join('gate_entry_items',function($join){
                $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                $join->on('po_yarn_items.id','=','gate_entry_items.item_id');
            })
            ->leftJoin(\DB::raw("(
                select 
                gate_entries.id as gate_entry_id,
                gate_entry_items.id as gate_entry_item_id,
                inv_rcvs.receive_date,
                sum(inv_yarn_rcv_items.qty)  as rcv_qty
                from gate_entries
                join gate_entry_items on gate_entry_items.gate_entry_id=gate_entries.id
                join po_yarn_items on po_yarn_items.id=gate_entry_items.item_id
                join inv_yarn_rcv_items on inv_yarn_rcv_items.po_yarn_item_id=po_yarn_items.id 
                join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
                join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
                where gate_entries.challan_no=inv_rcvs.challan_no
                group by 
                gate_entries.id,
                gate_entry_items.id,
                inv_rcvs.receive_date
              ) yarn_rcv"), "yarn_rcv.gate_entry_item_id", "=", "gate_entry_items.id")
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->when(request('date_from'), function ($q) {
                return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
              return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('po_yarns.company_id', '=', $company_id);
            })
            ->when(request('supplier_id'), function ($q) use($supplier_id) {
                return $q->where('po_yarns.supplier_id', '=', $supplier_id);
            })
            ->when(request('po_pr_no'), function ($q) use($po_pr_no) {
                return $q->where('po_yarns.po_no', '=', $po_pr_no );
            })
            ->orderBy('gate_entries.created_at','desc')
            ->groupBy([
                'gate_entries.id',
                'gate_entries.barcode_no_id',
                'gate_entries.challan_no',
                'gate_entries.created_by',
                'gate_entries.created_at',
                'po_yarns.po_no',
                'po_yarns.company_id',
                'po_yarns.supplier_id',
                'po_yarn_items.item_account_id',
                'po_yarn_items.remarks',
                'companies.code',
                'suppliers.name',
                'suppliers.address',
                'itemcategories.name',
                'item_accounts.id',
                'item_accounts.uom_id',
                'itemclasses.name',
                'uoms.code',
                'users.name',
                'yarn_rcv.receive_date',
                'yarn_rcv.rcv_qty',
                'gate_entry_items.id',
                'po_yarn_items.rate',
                'gate_entry_items.qty'
            ])
            ->get()
            ->map(function($purchaseorder) use($yarnDropdown,$rcvarr) {
                $purchaseorder->po_pr_no=$purchaseorder->yarn_po_no;
                $purchaseorder->itemcategory_name=$purchaseorder->itemcategory_name;
                $purchaseorder->create_user_name=$purchaseorder->create_user_name;
                $purchaseorder->company_code=$purchaseorder->yarn_company;
                $purchaseorder->item_description = $yarnDropdown[$purchaseorder->item_account_id];
                $purchaseorder->uom_code=$purchaseorder->yarn_uom_code;
                $purchaseorder->qty=$purchaseorder->yarn_qty;
                $purchaseorder->supplier_name=$purchaseorder->yarn_supplier_name.",".$purchaseorder->yarn_supplier_address;
                $purchaseorder->remarks=$purchaseorder->yarn_item_remarks;
                $purchaseorder->receive_no=isset($rcvarr[$purchaseorder->gate_entry_items_id])?implode(',',$rcvarr[$purchaseorder->gate_entry_items_id]):'';
                $purchaseorder->receive_date=($purchaseorder->receive_date!==null)?date('d-M-Y',strtotime($purchaseorder->receive_date)):null;
                if ($purchaseorder->qty) {
                    $purchaseorder->amount=$purchaseorder->qty*$purchaseorder->rate;
                }
                $purchaseorder->amount=number_format($purchaseorder->amount,2);
                $purchaseorder->qty=number_format($purchaseorder->qty,2);
                $purchaseorder->rcv_qty=number_format($purchaseorder->rcv_qty,2);

                $purchaseorder->entry_date=date('d-M-Y',strtotime($purchaseorder->created_at));
            return $purchaseorder;
            });

            //$purchaseThrough="Yarn Purchase";
            //$category=$purchaseorder;
            //return Template::loadView('Report.POS.GateEntryOrderMatrix',['category'=>$category,'purchaseThrough'=>$purchaseThrough]);
            echo json_encode($purchaseorder);
        }
        //Knit Purchase Order 
        if($menu_id==4){
            $purchaseorder=$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                gate_entries.created_by,
                gate_entries.created_at,
                po_knit_services.po_no as knit_service_po_no,
                po_knit_services.company_id,
                po_knit_services.supplier_id,

                companies.code as knit_company_code,
                suppliers.name as knit_supplier_name,
                suppliers.address as knit_supplier_address,
                item_accounts.item_description as knit_service_itemdesc,
                uoms.code as knit_uom_code,
                users.name as create_user_name,
                po_knit_service_items.rate,
                gate_entry_items.qty as knit_qty
            ')
            ->join('po_knit_services',function($join){
                $join->on('po_knit_services.id','=','gate_entries.barcode_no_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','po_knit_services.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_knit_services.supplier_id');
            })
            ->join('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.po_knit_service_id','=','po_knit_services.id');
            })
            ->join('budget_fabric_prods',function($join){
                $join->on('po_knit_service_items.budget_fabric_prod_id','=','budget_fabric_prods.id')
            ->whereNull('po_knit_service_items.deleted_at');
            })
            ->join('budget_fabrics',function($join){
            $join->on('budget_fabric_prods.budget_fabric_id','=','budget_fabrics.id');
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
            ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->join('users',function($join){
                $join->on('users.id','=','gate_entries.created_by');
            })
            ->join('gate_entry_items',function($join){
                $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                $join->on('po_knit_service_items.id','=','gate_entry_items.item_id');
            })
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->when(request('date_from'), function ($q) {
                return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
              return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('po_knit_services.company_id', '=', $company_id);
            })
            ->when(request('supplier_id'), function ($q) use($supplier_id) {
                return $q->where('po_knit_services.supplier_id', '=', $supplier_id);
            })
            ->when(request('po_pr_no'), function ($q) use($po_pr_no) {
                return $q->where('po_knit_services.po_no', '=', $po_pr_no );
            })
            ->orderBy('gate_entries.created_at','desc')
            ->get()
            ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->knit_service_po_no;
                $purchaseorder->company_code=$purchaseorder->knit_company_code;
                $purchaseorder->item_description=$purchaseorder->knit_service_itemdesc;
                $purchaseorder->uom_code=$purchaseorder->knit_uom_code;
                $purchaseorder->qty=$purchaseorder->knit_qty;
                $purchaseorder->supplier_name=$purchaseorder->knit_supplier_name.",".$purchaseorder->knit_supplier_address;
                $purchaseorder->receive_no='';
                $purchaseorder->purchaseThrough='';
                if ($purchaseorder->qty) {
                    $purchaseorder->amount=$purchaseorder->qty*$purchaseorder->rate;
                }
                $purchaseorder->amount=number_format($purchaseorder->amount,2);
                $purchaseorder->qty=number_format($purchaseorder->qty,2);
                $purchaseorder->purchaseThrough="Knitting Work Order";
                $purchaseorder->entry_date=date('d-M-Y',strtotime($purchaseorder->created_at));
                $purchaseorder->rcv_qty=number_format(0,2);
                return $purchaseorder;
            });
           //$purchaseThrough="Knitting Work Order";
           //$category=$purchaseorder;
           //return Template::loadView('Report.POS.GateEntryOrderMatrix',['category'=>$category,'purchaseThrough'=>$purchaseThrough]);
           echo json_encode($purchaseorder);
        }
        //AOP Service Order
        if($menu_id==5){
            $purchaseorder=$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                gate_entries.created_by,
                gate_entries.created_at,
                po_aop_services.po_no as aop_service_po_no,
                po_aop_services.company_id,
                po_aop_services.supplier_id,
                po_aop_services.remarks as po_aop_remarks,
                companies.code as aop_company,
                suppliers.name as aop_service_supplier_name,
                po_aop_service_items.rate,
                gate_entry_items.qty
            ')
            ->join('po_aop_services',function($join){
                $join->on('po_aop_services.id','=','gate_entries.barcode_no_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','po_aop_services.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_aop_services.supplier_id');
            })
            ->join('users',function($join){
                $join->on('users.id','=','gate_entries.created_by');
            })
            ->join('po_aop_service_items',function($join){
                $join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id')
                ->whereNull('po_aop_service_items.deleted_at');
            })
            ->join('gate_entry_items',function($join){
                $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                $join->on('po_aop_service_items.id','=','gate_entry_items.item_id');
            })
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->when(request('date_from'), function ($q) {
                return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
              return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('po_aop_services.company_id', '=', $company_id);
            })
            ->when(request('supplier_id'), function ($q) use($supplier_id) {
                return $q->where('po_aop_services.supplier_id', '=', $supplier_id);
            })
            ->when(request('po_pr_no'), function ($q) use($po_pr_no) {
                return $q->where('po_aop_services.po_no', '=', $po_pr_no);
            })
            ->orderBy('gate_entries.created_at','desc')
            ->get()
            ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->aop_service_po_no;
                $purchaseorder->company_name=$purchaseorder->aop_company;
                $purchaseorder->supplier_name=$purchaseorder->aop_service_supplier_name;
                $purchaseorder->master_remarks=$purchaseorder->po_aop_remarks;
                $purchaseorder->receive_no='';
                $purchaseorder->receive_date='';
                if ($purchaseorder->qty) {
                        $purchaseorder->amount=$purchaseorder->qty*$purchaseorder->rate;
                    }
                $purchaseorder->amount=number_format($purchaseorder->amount,2);
                $purchaseorder->qty=number_format($purchaseorder->qty,2);
                $purchaseorder->entry_date=date('d-M-Y',strtotime($purchaseorder->created_at));
                $purchaseorder->purchaseThrough="AOP Work Order";
                $purchaseorder->rcv_qty=number_format(0,2);
                return $purchaseorder;
            });
           // $purchaseThrough="AOP Work Order";
           // $category=$purchaseorder;
           //return Template::loadView('Report.POS.GateEntryOrderMatrix',['category'=>$category,'purchaseThrough'=>$purchaseThrough]);
           echo json_encode($purchaseorder);
        }
        //Dyeing Service Work Order
        if($menu_id==6){    
            $purchaseorder = $this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                gate_entries.created_by,
                gate_entries.created_at,
                po_dyeing_services.po_no as dyeing_service_po_no,
                po_dyeing_services.company_id,
                po_dyeing_services.supplier_id,
                companies.code as dyeing_company,
                suppliers.name as dyeService_supplier_name,
                suppliers.address as dyeService_supplier_adress,
                item_accounts.id as item_account_id,          
                item_accounts.item_description as dyeService_itemdesc,
                item_accounts.specification,
                item_accounts.sub_class_name,
                item_accounts.uom_id,
                itemclasses.name as itemclass_name,
                budget_fabrics.style_fabrication_id,
                uoms.code as dyeing_uom_code,
                users.name as create_user_name,
                po_dyeing_service_items.rate,
                gate_entry_items.qty

            ')
            ->join('po_dyeing_services',function($join){
                $join->on('po_dyeing_services.id','=','gate_entries.barcode_no_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','po_dyeing_services.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_dyeing_services.supplier_id');
            })
            ->join('po_dyeing_service_items',function($join){
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
            ->leftJoin('item_accounts', function($join) {
                $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
            })
            ->leftJoin('itemcategories',function($join){
                $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->leftJoin('itemclasses',function($join){
                $join->on('itemclasses.id','=','item_accounts.itemclass_id');
            })
            ->join('uoms', function($join) {
                $join->on('uoms.id', '=', 'item_accounts.uom_id');
            })
            ->join('users',function($join){
                $join->on('users.id','=','gate_entries.created_by');
            })
            ->join('gate_entry_items',function($join){
                $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                $join->on('po_dyeing_service_items.id','=','gate_entry_items.item_id');
            })
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->when(request('date_from'), function ($q) {
                return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
                return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('po_dyeing_services.company_id', '=', $company_id);
            })
            ->when(request('supplier_id'), function ($q) use($supplier_id) {
                return $q->where('po_dyeing_services.supplier_id', '=', $supplier_id);
            })
            ->when(request('po_pr_no'), function ($q) use($po_pr_no) {
                return $q->where('po_dyeing_services.po_no', '=', $po_pr_no);
            })
            ->orderBy('gate_entries.created_at','desc')
            ->get()
            ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->dyeing_service_po_no;
                $purchaseorder->company_code=$purchaseorder->dyeing_company;
                $purchaseorder->item_description=$purchaseorder->dyeService_itemdesc;
                $purchaseorder->uom_code=$purchaseorder->dyeService_uom_code;
               // $purchaseorder->qty=$purchaseorder->qty;
                $purchaseorder->supplier_name=$purchaseorder->dyeService_supplier_name.",".$purchaseorder->dyeService_supplier_adress;
                $purchaseorder->receive_no='';
                if ($purchaseorder->qty) {
                    $purchaseorder->amount=$purchaseorder->qty*$purchaseorder->rate;
                }
                $purchaseorder->amount=number_format($purchaseorder->amount,2);
                $purchaseorder->qty=number_format($purchaseorder->qty,2);
                $purchaseorder->entry_date=date('d-M-Y',strtotime($purchaseorder->created_at));
                $purchaseorder->purchaseThrough="Dyeing Work Order";
                $purchaseorder->rcv_qty=number_format(0,2);
                return $purchaseorder;
            });

           // $purchaseThrough="Dyeing Work Order";
          //  $category=$purchaseorder;
          // return Template::loadView('Report.POS.GateEntryOrderMatrix',['category'=>$category,'purchaseThrough'=>$purchaseThrough]);
          echo json_encode($purchaseorder);
        }
        //Dye & Chem Purchase Order 
        if($menu_id==7){
            $receive=$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entry_items.id as gate_entry_item_id,
                inv_rcvs.receive_no
            ')
            ->join('gate_entry_items',function($join){
                $join->on('gate_entry_items.gate_entry_id','=','gate_entries.id');
            })
            ->join('po_dye_chems',function($join){
                $join->on('po_dye_chems.id','=','gate_entries.barcode_no_id');
            })
            ->join('po_dye_chem_items', function($join){
                $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
                $join->on('po_dye_chem_items.id','=','gate_entry_items.item_id');
            })
            ->join('inv_dye_chem_rcv_items', function($join){
                $join->on('inv_dye_chem_rcv_items.po_dye_chem_item_id', '=','po_dye_chem_items.id' );
            })
            ->join('inv_dye_chem_rcvs',function($join){
                $join->on('inv_dye_chem_rcvs.id','=','inv_dye_chem_rcv_items.inv_dye_chem_rcv_id');
            })
            ->join('inv_rcvs',function($join){
                $join->on('inv_rcvs.id','=','inv_dye_chem_rcvs.inv_rcv_id');
            })
            ->whereColumn([['gate_entries.challan_no','=','inv_rcvs.challan_no']])
           // ->where([['inv_rcvs.challan_no','=',272]])
           ->groupBy([
            'gate_entries.id',
            'gate_entry_items.id',
            'inv_rcvs.receive_no',
           ])
            ->get();

           //dd($receive);die;
            $poArr=array();
            foreach($receive as $row){
                $poArr[$row->gate_entry_item_id][]=$row->receive_no;
            }

            $rcvarr=array();
            foreach ($poArr as $key => $value) {
                $rcvarr[$key]=$value;
            }

            $purchaseorder=$this->gateentry
                ->selectRaw('
                    gate_entries.id,
                    gate_entries.barcode_no_id,
                    gate_entries.challan_no,
                    gate_entries.created_by,
                    gate_entries.created_at,
                    po_dye_chems.po_no as dye_chem_po_no,
                    po_dye_chems.company_id,
                    po_dye_chems.supplier_id,
                    po_dye_chem_items.remarks as po_dye_chem_remarks,
                    inv_pur_req_items.item_account_id,
                    companies.code as dye_chem_company,
                    suppliers.name as dyechem_supplier_name,
                    suppliers.address as dyechem_supplier_address,
                    itemcategories.name as itemcategory_name,
                    itemclasses.itemcategory_id,
                    itemclasses.name as itemclass_name,
                    item_accounts.id as item_account_id,
                    item_accounts.sub_class_name,
                    item_accounts.item_description as dye_chem_itemdesc,
                    item_accounts.specification,
                    item_accounts.uom_id,
                    users.name as create_user_name,
                    uoms.code as dyechem_uom_code,
                    dye_chem_rcv.receive_date,
                    dye_chem_rcv.rcv_qty,
                    gate_entry_items.id as gate_entry_item_id,
                    po_dye_chem_items.rate,
                    gate_entry_items.qty
                ')
                ->join('po_dye_chems',function($join){
                    $join->on('po_dye_chems.id','=','gate_entries.barcode_no_id');
                })
                ->join('companies',function($join){
                    $join->on('companies.id','=','po_dye_chems.company_id');
                })
                ->join('suppliers',function($join){
                    $join->on('suppliers.id','=','po_dye_chems.supplier_id');
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
                ->leftJoin('uoms', function($join){
                    $join->on('uoms.id', '=', 'item_accounts.uom_id');
                })
                ->join('itemclasses', function($join){
                    $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
                })
                ->join('itemcategories', function($join){
                    $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
                })
                ->join('users',function($join){
                    $join->on('users.id','=','gate_entries.created_by');
                })
                ->join('gate_entry_items',function($join){
                    $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                    $join->on('po_dye_chem_items.id','=','gate_entry_items.item_id');
                })
                ->leftJoin(\DB::raw("(
                    select 
                    gate_entries.id as gate_entry_id,
                    gate_entry_items.id as gate_entry_item_id,
                    inv_rcvs.receive_date,
                    sum(inv_dye_chem_rcv_items.qty)  as rcv_qty
                    from gate_entries
                    join gate_entry_items on gate_entry_items.gate_entry_id=gate_entries.id
                    join po_dye_chem_items on po_dye_chem_items.id=gate_entry_items.item_id
                    join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.po_dye_chem_item_id=po_dye_chem_items.id 
                    join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
                    join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
                    where gate_entries.challan_no=inv_rcvs.challan_no
                    group by 
                    gate_entries.id,
                    gate_entry_items.id,
                    inv_rcvs.receive_date
                ) dye_chem_rcv"), "dye_chem_rcv.gate_entry_item_id", "=", "gate_entry_items.id")
                ->where([['gate_entries.menu_id','=',$menu_id]])
                ->when(request('date_from'), function ($q) {
                	return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
	            })
	            ->when(request('date_to'), function ($q) {
	              return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
	            })
                ->when(request('company_id'), function ($q) use($company_id) {
                    return $q->where('po_dye_chems.company_id', '=', $company_id);
                })
                ->when(request('supplier_id'), function ($q) use($supplier_id) {
                    return $q->where('po_dye_chems.supplier_id', '=', $supplier_id);
                })
                ->when(request('po_no'), function ($q) use($po_pr_no) {
                    return $q->where('po_dye_chems.po_pr_no', '=',$po_pr_no);
                })
                ->orderBy('gate_entries.created_at','desc')
                ->groupBy([
                    'gate_entries.id',
                    'gate_entries.barcode_no_id',
                    'gate_entries.challan_no',
                    'gate_entries.created_by',
                    'gate_entries.created_at',
                    'po_dye_chems.po_no',
                    'po_dye_chems.company_id',
                    'po_dye_chems.supplier_id',
                    'po_dye_chem_items.remarks',
                    'inv_pur_req_items.item_account_id',
                    'companies.code',
                    'suppliers.name',
                    'suppliers.address',
                    'itemcategories.name',
                    'itemclasses.name',
                    'itemclasses.itemcategory_id',
                    'item_accounts.id',
                    'item_accounts.sub_class_name',
                    'item_accounts.item_description',
                    'item_accounts.specification',
                    'item_accounts.uom_id',
                    'users.name',
                    'uoms.code',
                    'dye_chem_rcv.receive_date',
                    'dye_chem_rcv.rcv_qty',
                    'gate_entry_items.id',
                    'po_dye_chem_items.rate',
                    'gate_entry_items.qty',
                ])
                ->get()
                ->map(function($purchaseorder) use($rcvarr) {
                    $purchaseorder->po_pr_no=$purchaseorder->dye_chem_po_no;
                    $purchaseorder->supplier_name=$purchaseorder->dyechem_supplier_name.",".$purchaseorder->dyechem_supplier_address;
                    $purchaseorder->company_code=$purchaseorder->dye_chem_company;
                    $purchaseorder->item_description=$purchaseorder->sub_class_name.",".$purchaseorder->dye_chem_itemdesc.",".$purchaseorder->specification;                
                    $purchaseorder->uom_code=$purchaseorder->dyechem_uom_code;      $purchaseorder->remarks=$purchaseorder->po_dye_chem_remarks;
                    $purchaseorder->receive_no=isset($rcvarr[$purchaseorder->gate_entry_item_id])?implode(',',$rcvarr[$purchaseorder->gate_entry_item_id]):'';
                    $purchaseorder->receive_date=($purchaseorder->receive_date!==null)?date('d-M-Y',strtotime($purchaseorder->receive_date)):null;
                    if ($purchaseorder->qty) {
                        $purchaseorder->amount=$purchaseorder->qty*$purchaseorder->rate;
                    }
                    $purchaseorder->amount=number_format($purchaseorder->amount,2);
                    $purchaseorder->qty=number_format($purchaseorder->qty,2);
                    $purchaseorder->rcv_qty=number_format($purchaseorder->rcv_qty,2);
                    $purchaseorder->entry_date=date('d-M-Y',strtotime($purchaseorder->created_at));
                    $purchaseorder->purchaseThrough="Dyes & Chemical Purchase";
                    return $purchaseorder;
                });

                echo json_encode($purchaseorder);
            //$purchaseThrough="Dyes & Chemical Purchase";
            //$itemCatArr=array();
           // foreach($purchaseorder as $row){
            //    $itemCatArr[$row->itemcategory_id]['itemcategory_name']=$row->itemcategory_name;
           // }
             //   $category=$purchaseorder->groupBy('itemcategory_id');
            //    return Template::loadView('Report.POS.GateEntryServiceMatrix',['category'=>$category,'itemCatArr'=>$itemCatArr,'purchaseThrough'=>$purchaseThrough]);

        }
        //General Item Purchase Worder
        if($menu_id==8){
            $receive=$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                inv_rcvs.receive_no,
                gate_entry_items.id as gate_entry_item_id
            ')
            ->join('gate_entry_items',function($join){
                $join->on('gate_entry_items.gate_entry_id','=','gate_entries.id');
            })
            ->join('po_generals',function($join){
                $join->on('po_generals.id','=','gate_entries.barcode_no_id');
            })
            ->join('po_general_items', function($join){
                $join->on('po_general_items.po_general_id', '=', 'po_generals.id');
                $join->on('po_general_items.id','=','gate_entry_items.item_id');
            })
            ->join('inv_general_rcv_items', function($join){
                $join->on('inv_general_rcv_items.po_general_item_id', '=','po_general_items.id' );
            })
            ->join('inv_general_rcvs',function($join){
                $join->on('inv_general_rcvs.id','=','inv_general_rcv_items.inv_general_rcv_id');
            })
            ->join('inv_rcvs',function($join){
                $join->on('inv_rcvs.id','=','inv_general_rcvs.inv_rcv_id');
            })
            ->whereColumn([['gate_entries.challan_no','=','inv_rcvs.challan_no']])
            ->groupBy([
                'gate_entries.id',
                'gate_entries.barcode_no_id',
                'gate_entries.challan_no',
                'inv_rcvs.receive_no',
                'gate_entry_items.id'
            ])
            ->get();

            
            $poArr=array();
            foreach($receive as $row){
                $poArr[$row->gate_entry_item_id][]=$row->receive_no;
            }

            $rcvarr=array();
            foreach ($poArr as $key => $value) {
                $rcvarr[$key]=$value;
            }
           //dd(implode(',',$rcvarr[14490]));
            //die;
            $purchaseorder =$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                gate_entries.created_by,
                gate_entries.created_at,
                po_generals.po_no as general_po_no,
                po_generals.company_id,
                po_generals.supplier_id,
                po_general_items.remarks as general_item_remarks,
                inv_pur_req_items.item_account_id,
                companies.code as general_company,
                suppliers.name as general_supplier_name,
                suppliers.address as general_supplier_address,
                itemcategories.name as itemcategory_name,
                itemclasses.itemcategory_id,
                itemclasses.name as itemclass_name,
                item_accounts.id as item_account_id,
                item_accounts.sub_class_name,
                item_accounts.item_description as general_itemdesc,
                item_accounts.specification,
                item_accounts.uom_id,
                users.name as create_user_name,
                uoms.code as general_uom_code,
                general_rcv.receive_date,
                general_rcv.rcv_qty,
                gate_entry_items.id as gate_entry_item_id,
                po_general_items.rate,
                gate_entry_items.qty
            ')
            ->join('po_generals',function($join){
                $join->on('po_generals.id','=','gate_entries.barcode_no_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','po_generals.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_generals.supplier_id');
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
            ->leftJoin('uoms', function($join){
                $join->on('uoms.id', '=', 'item_accounts.uom_id');
            })
            ->leftJoin('itemclasses', function($join){
                $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
            })
            ->leftJoin('itemcategories', function($join){
                $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
            })
            ->join('users',function($join){
                $join->on('users.id','=','gate_entries.created_by');
            })
            ->join('gate_entry_items',function($join){
                $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                $join->on('po_general_items.id','=','gate_entry_items.item_id');
            })
            ->leftJoin(\DB::raw("(
                select 
                gate_entries.id as gate_entry_id,
                gate_entry_items.id as gate_entry_item_id,
                inv_rcvs.receive_date,
                sum(inv_general_rcv_items.qty)  as rcv_qty
                from gate_entries
                join gate_entry_items on gate_entry_items.gate_entry_id=gate_entries.id
                join po_general_items on po_general_items.id=gate_entry_items.item_id
                join inv_general_rcv_items on inv_general_rcv_items.po_general_item_id=po_general_items.
                id 
                join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
                join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
                where gate_entries.challan_no=inv_rcvs.challan_no
                group by 
                gate_entries.id,
                gate_entry_items.id,
                inv_rcvs.receive_date
                ) general_rcv"), "general_rcv.gate_entry_item_id", "=", "gate_entry_items.id")
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->when(request('date_from'), function ($q) {
                return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
                return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('po_generals.company_id', '=', $company_id);
            })
            ->when(request('supplier_id'), function ($q) use($supplier_id) {
                return $q->where('po_generals.supplier_id', '=', $supplier_id);
            })
            ->when(request('po_no'), function ($q) use($po_pr_no) {
                return $q->where('po_generals.po_pr_no', '=', $po_pr_no );
            })
            ->orderBy('gate_entries.created_at','desc')
            ->groupBy([
                'gate_entries.id',
                'gate_entries.barcode_no_id',
                'gate_entries.challan_no',
                'gate_entries.created_by',
                'gate_entries.created_at',
                'po_generals.po_no',
                'po_generals.company_id',
                'po_generals.supplier_id',
                'po_general_items.remarks',
                'inv_pur_req_items.item_account_id',
                'companies.code',
                'suppliers.name',
                'suppliers.address',
                'itemcategories.name',
                'itemclasses.name',
                'itemclasses.itemcategory_id',
                'item_accounts.id',
                'item_accounts.sub_class_name',
                'item_accounts.item_description',
                'item_accounts.specification',
                'item_accounts.uom_id',
                'users.name',
                'uoms.code',
                'general_rcv.receive_date',
                'general_rcv.rcv_qty',
                'gate_entry_items.id',
                'po_general_items.rate',
                'gate_entry_items.qty',
            ])
            ->get()
            ->map(function($purchaseorder) use($rcvarr){
                $purchaseorder->po_pr_no=$purchaseorder->general_po_no;
                $purchaseorder->supplier_name=$purchaseorder->general_supplier_name.",".$purchaseorder->general_supplier_address;
                $purchaseorder->company_code=$purchaseorder->general_company;
                $purchaseorder->item_description=$purchaseorder->sub_class_name.",".$purchaseorder->general_itemdesc.",".$purchaseorder->specification;                
                $purchaseorder->uom_code=$purchaseorder->general_uom_code;      $purchaseorder->remarks=$purchaseorder->general_item_remarks;
                $purchaseorder->receive_no=isset($rcvarr[$purchaseorder->gate_entry_item_id])?implode(',',$rcvarr[$purchaseorder->gate_entry_item_id]):'';
                $purchaseorder->receive_date=($purchaseorder->receive_date!==null)?date('d-M-Y',strtotime($purchaseorder->receive_date)):null;
                if ($purchaseorder->qty) {
                    $purchaseorder->amount=$purchaseorder->qty*$purchaseorder->rate;
                }
                $purchaseorder->amount=number_format($purchaseorder->amount,2);
                $purchaseorder->qty=number_format($purchaseorder->qty,2);
                $purchaseorder->rcv_qty=number_format($purchaseorder->rcv_qty,2);
                $purchaseorder->entry_date=date('d-M-Y',strtotime($purchaseorder->created_at));
                $purchaseorder->purchaseThrough="General Item Purchase";
                return $purchaseorder;
            });
            echo json_encode($purchaseorder);
           // $purchaseThrough="General Item Purchase";
           // $itemCatArr=array();
           // $poArr=array();
          //  foreach($purchaseorder as $row){
          //      $itemCatArr[$row->itemcategory_id]['itemcategory_name']=$row->itemcategory_name;
          //     // $poArr[$row->id][]=$row->receive_no;
         //   }

           // $category=$purchaseorder->groupBy('itemcategory_id');
            //dd($category);
          //  return Template::loadView('Report.POS.GateEntryServiceMatrix',['category'=>$category,'itemCatArr'=>$itemCatArr,'purchaseThrough'=>$purchaseThrough,'rcvarr'=>$rcvarr]);
        }
        //Yarn Dyeing Work Order
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

            $data=$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                gate_entries.created_by,
                gate_entries.created_at,
                po_yarn_dyeings.po_no as yarn_dyeing_po_no,
                po_yarn_dyeings.company_id,
                po_yarn_dyeings.supplier_id,
                po_yarn_dyeing_items.id as po_yarn_dyeing_item_id,
                po_yarn_dyeing_items.inv_yarn_item_id,
                po_yarn_dyeing_items.remarks as yarn_dyeing_item_remarks,
                inv_yarn_items.id as inv_yarn_item_id,
                companies.code as yarn_dyeing_company,
                suppliers.name as yarn_dyeing_supplier_name,
                suppliers.address as yarn_dyeing_supplier_address,
                uoms.code as yndyeing_uom_code,
                users.name as create_user_name,
                gate_entry_items.qty
            ')
            ->join('po_yarn_dyeings',function($join){
                $join->on('po_yarn_dyeings.id','=','gate_entries.barcode_no_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','po_yarn_dyeings.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_yarn_dyeings.supplier_id');
            })
            ->join('po_yarn_dyeing_items', function($join){
                $join->on('po_yarn_dyeing_items.po_yarn_dyeing_id', '=', 'po_yarn_dyeings.id');
              })
            ->join('inv_yarn_items',function($join){
                $join->on('inv_yarn_items.id','=','po_yarn_dyeing_items.inv_yarn_item_id'); 
            })
            ->leftJoin('item_accounts',function($join){
                $join->on('item_accounts.id','=','inv_yarn_items.item_account_id'); 
            })
            ->leftJoin('uoms', function($join){
                $join->on('uoms.id', '=', 'item_accounts.uom_id');
            })
            ->join('users',function($join){
                $join->on('users.id','=','gate_entries.created_by');
            })
            ->join('gate_entry_items',function($join){
                $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                $join->on('po_yarn_dyeing_items.id', '=' , 'gate_entry_items.item_id');
            })
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->when(request('date_from'), function ($q) {
            	return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
              return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('po_yarn_dyeings.company_id', '=', $company_id);
            })
            ->when(request('supplier_id'), function ($q) use($supplier_id) {
                return $q->where('po_yarn_dyeings.supplier_id', '=', $supplier_id);
            })
            ->when(request('po_pr_no'), function ($q) use($po_pr_no) {
                return $q->where('po_yarn_dyeings.po_no', '=', $po_pr_no);
            })
            ->orderBy('gate_entries.created_at','desc')
            ->groupBy([
                'gate_entries.id',
                'gate_entries.barcode_no_id',
                'gate_entries.challan_no',
                'gate_entries.created_by',
                'gate_entries.created_at',
                'po_yarn_dyeings.po_no',
                'po_yarn_dyeings.company_id',
                'po_yarn_dyeings.supplier_id',
                'po_yarn_dyeing_items.id',
                'po_yarn_dyeing_items.inv_yarn_item_id',
                'po_yarn_dyeing_items.remarks',
                'inv_yarn_items.id',
                'companies.code',
                'suppliers.name',
                'suppliers.address',
                'uoms.code',
                'users.name',
                'gate_entry_items.qty'
            ])
            ->get()
            ->map(function ($data) use($yarnDropdown) {
                $data->po_pr_no=$data->yarn_dyeing_po_no;
                $data->company_code=$data->yarn_dyeing_company;
                $data->item_description = $yarnDropdown[$data->inv_yarn_item_id];
                $data->supplier_name=$data->yarn_dyeing_supplier_name;
                $data->uom_code=$data->yndyeing_uom_code;
                $data->remarks=$data->yarn_dyeing_item_remarks;
                $data->receive_no='';
                $data->receive_date='';
                $data->purchaseThrough="Yarn Dyeing Work Order";
                $purchaseorder->qty=number_format($purchaseorder->qty,2);
                $purchaseorder->amount=number_format(0,2);
                //$purchaseorder->rcv_qty=number_format($purchaseorder->rcv_qty,2);
                $purchaseorder->rcv_qty=number_format($purchaseorder->rcv_qty,2);
                $data->created_at=date('d-M-Y',strtotime($data->created_at));
                return $data;
            });
            echo json_encode($data);
           // $purchaseThrough="Yarn Dyeing Work Order";
           // $itemCatArr=array();
           // foreach($data as $row){
          //      $itemCatArr[$row->itemcategory_id]['itemcategory_name']=$row->itemcategory_name;
          //  }
         //   $category=$data->groupBy('itemcategory_id');
         //   return Template::loadView('Report.POS.GateEntryServiceMatrix',['category'=>$category,'itemCatArr'=>$itemCatArr,'purchaseThrough'=>$purchaseThrough]);
        }
        //Embelishment Purchase Order
        if($menu_id==10){
            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
            $purchaseorder = $this->gateentry
                ->selectRaw('
                    gate_entries.id,
                    gate_entries.barcode_no_id,
                    gate_entries.challan_no,
                    gate_entries.created_by,
                    gate_entries.created_at,
                    po_emb_services.po_no as emb_service_po_no,
                    po_emb_services.company_id,
                    po_emb_services.supplier_id,
                    companies.code as emb_company,
                    suppliers.name as emb_supplier_name,
                    suppliers.address as emb_supplier_adress,
                    item_accounts.id as item_account_id,          
                    item_accounts.item_description as emb_itemdesc,
                    item_accounts.specification,
                    item_accounts.sub_class_name,
                    item_accounts.uom_id,
                    itemclasses.name as itemclass_name,
                    uoms.code as emb_uom_code,
                    users.name as create_user_name,
                    style_embelishments.embelishment_size_id,
                    embelishments.name as embelishment_name,
                    embelishment_types.name as embelishment_type,
                    gmtsparts.name as gmtspart_name,
                    po_emb_service_items.rate,
                    gate_entry_items.qty
                ')
                ->join('po_emb_services',function($join){
                    $join->on('po_emb_services.id','=','gate_entries.barcode_no_id');
                })
                ->join('companies',function($join){
                    $join->on('companies.id','=','po_emb_services.company_id');
                })
                ->join('suppliers',function($join){
                    $join->on('suppliers.id','=','po_emb_services.supplier_id');
                })
                ->join('po_emb_service_items',function($join){
                    $join->on('po_emb_service_items.po_emb_service_id','=','po_emb_services.id')
                    ->whereNull('po_emb_service_items.deleted_at');
                })
                ->join('budget_embs',function($join){
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
                ->join('uoms', function($join) {
                    $join->on('uoms.id', '=', 'item_accounts.uom_id');
                })
                ->join('users',function($join){
                    $join->on('users.id','=','gate_entries.created_by');
                })
                ->leftJoin('budgets',function($join){
                    $join->on('budgets.id','=','budget_embs.budget_id');
                })
                ->leftJoin('jobs',function($join){
                    $join->on('jobs.id','=','budgets.job_id');
                })
                ->leftJoin('sales_orders',function($join){
                    $join->on('sales_orders.job_id','=','jobs.id');
                })
                ->leftJoin('styles', function($join) {
                    $join->on('styles.id', '=', 'jobs.style_id');
                })
                ->leftJoin('buyers', function($join) {
                    $join->on('buyers.id', '=', 'styles.buyer_id');
                })
                ->leftJoin('gmtsparts',function($join){
                    $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
                })
                ->leftJoin('embelishments',function($join){
                    $join->on('embelishments.id','=','style_embelishments.embelishment_id');
                })
                ->leftJoin('embelishment_types',function($join){
                    $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
                })
                ->join('gate_entry_items',function($join){
                    $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                    $join->on('po_emb_service_items.id','=','gate_entry_items.item_id');
                })
                ->where([['gate_entries.menu_id','=',$menu_id]])
                ->when(request('date_from'), function ($q) {
                	return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
	            })
	            ->when(request('date_to'), function ($q) {
	              return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
	            })
                ->when(request('company_id'), function ($q) use($company_id) {
                    return $q->where('po_emb_services.company_id', '=', $company_id);
                })
                ->when(request('supplier_id'), function ($q) use($supplier_id) {
                    return $q->where('po_emb_services.supplier_id', '=', $supplier_id);
                })
                ->when(request('po_pr_no'), function ($q) use($po_pr_no) {
                    return $q->where('po_emb_services.po_no', '=', $po_pr_no );
                })
                ->orderBy('gate_entries.created_at','desc')
                ->get()
                ->map(function($purchaseorder) use($embelishmentsize) {
                    $purchaseorder->po_pr_no=$purchaseorder->emb_service_po_no;
                    $purchaseorder->company_code=$purchaseorder->emb_company;               
                    $purchaseorder->embelishment_size = $embelishmentsize[$purchaseorder->embelishment_size_id];
                    $purchaseorder->item_description=$purchaseorder->emb_itemdesc.','.$purchaseorder->gmtspart_name.','.$purchaseorder->embelishment_name.','.$purchaseorder->embelishment_size.','.$purchaseorder->embelishment_type;
                    $purchaseorder->uom_code=$purchaseorder->emb_uom_code;
                // $purchaseorder->qty=$purchaseorder->qty;
                    $purchaseorder->supplier_name=$purchaseorder->emb_supplier_name.",".$purchaseorder->emb_supplier_adress;
                    $purchaseorder->receive_no='';
                    $purchaseorder->receive_date='';
                    if ($purchaseorder->qty) {
                        $purchaseorder->amount=$purchaseorder->qty*$purchaseorder->rate;
                    }
                    $purchaseorder->amount=number_format($purchaseorder->amount,2);
                    $purchaseorder->qty=number_format($purchaseorder->qty,2);
                    $purchaseorder->rcv_qty=number_format(0,2);
                    $purchaseorder->entry_date=date('d-M-Y',strtotime($purchaseorder->created_at));
                    $purchaseorder->purchaseThrough="Embelishment Work Order";
                return $purchaseorder;
            });
            echo json_encode($purchaseorder);
           // $purchaseThrough="Embelishment Work Order";
           // $category=$purchaseorder;
          // return Template::loadView('Report.POS.GateEntryOrderMatrix',['category'=>$category,'purchaseThrough'=>$purchaseThrough]);
        }
        //General Service Work Order
        if($menu_id==11){
            $purchaseorder = $this->gateentry
                ->selectRaw('
                    gate_entries.id,
                    gate_entries.barcode_no_id,
                    gate_entries.challan_no,
                    gate_entries.created_by,
                    gate_entries.created_at,
                    po_general_services.po_no as service_po_no,
                    po_general_services.company_id,
                    po_general_services.supplier_id,
                    companies.code as service_company,
                    suppliers.name as service_supplier_name,
                    suppliers.address as service_supplier_adress,          
                    po_general_service_items.service_description,
                    po_general_service_items.remarks as item_remarks,
                    uoms.code as service_uom_code,
                    users.name as create_user_name,
                    po_general_service_items.rate,
                    gate_entry_items.qty
                ')
                ->join('po_general_services',function($join){
                    $join->on('po_general_services.id','=','gate_entries.barcode_no_id');
                })
                ->join('companies',function($join){
                    $join->on('companies.id','=','po_general_services.company_id');
                })
                ->join('suppliers',function($join){
                    $join->on('suppliers.id','=','po_general_services.supplier_id');
                })
                ->join('po_general_service_items',function($join){
                    $join->on('po_general_service_items.po_general_service_id','=','po_general_services.id')
                    ->whereNull('po_general_service_items.deleted_at');
                })
                ->join('departments', function($join){
                    $join->on('departments.id', '=', 'po_general_service_items.department_id');
                })
                ->join('users as demand_by', function($join){
                    $join->on('demand_by.id', '=', 'po_general_service_items.demand_by_id');
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
                ->join('users',function($join){
                    $join->on('users.id','=','gate_entries.created_by');
                })
                ->join('gate_entry_items',function($join){
                    $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                    $join->on('po_general_service_items.id','=','gate_entry_items.item_id');
                })
                ->where([['gate_entries.menu_id','=',$menu_id]])
                ->when(request('date_from'), function ($q) {
                	return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
	            })
	            ->when(request('date_to'), function ($q) {
	              return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
	            })
                ->when(request('company_id'), function ($q) use($company_id) {
                    return $q->where('po_general_services.company_id', '=', $company_id);
                })
                ->when(request('supplier_id'), function ($q) use($supplier_id) {
                    return $q->where('po_general_services.supplier_id', '=', $supplier_id);
                })
                ->when(request('po_pr_no'), function ($q) use($po_pr_no) {
                    return $q->where('po_general_services.po_no', '=', $po_pr_no);
                })
                ->orderBy('gate_entries.created_at','desc')
                ->get()
                ->map(function($purchaseorder) {
                    $purchaseorder->po_pr_no=$purchaseorder->service_po_no;
                    $purchaseorder->company_code=$purchaseorder->service_company;                
                    $purchaseorder->item_description=$purchaseorder->service_description;
                    $purchaseorder->uom_code=$purchaseorder->service_uom_code;
                    $purchaseorder->remarks=$purchaseorder->item_remarks;
                    $purchaseorder->supplier_name=$purchaseorder->service_supplier_name.",".$purchaseorder->service_supplier_adress;
                    $purchaseorder->receive_no='';
                    $purchaseorder->receive_date='';
                    if ($purchaseorder->qty) {
                        $purchaseorder->amount=$purchaseorder->qty*$purchaseorder->rate;
                    }
                    $purchaseorder->amount=number_format($purchaseorder->amount,2);
                    $purchaseorder->qty=number_format($purchaseorder->qty,2);
                    $purchaseorder->rcv_qty=number_format(0,2);
                    $purchaseorder->entry_date=date('d-M-Y',strtotime($purchaseorder->created_at));
                    $purchaseorder->purchaseThrough="General Service Work Order";
                return $purchaseorder;
            });
            echo json_encode($purchaseorder);
         //   $purchaseThrough="General Service Work Order";
          //  $category=$purchaseorder;
         //  return Template::loadView('Report.POS.GateEntryOrderMatrix',['category'=>$category,'purchaseThrough'=>$purchaseThrough]);
        }
        //Inventory Purchase Requisition
        if($menu_id==103){
            $rcvGn=$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                gate_entry_items.id as gate_entry_item_id,
                --inv_rcvs.receive_date as receive_date_gn,
                inv_rcvs.receive_no as receive_no_gn
            ')
            ->join('gate_entry_items',function($join){
                $join->on('gate_entry_items.gate_entry_id','=','gate_entries.id');
            })
            ->join('inv_pur_reqs',function($join){
                $join->on('inv_pur_reqs.id','=','gate_entries.barcode_no_id');
            })
            ->join('inv_pur_req_items',function($join){
                $join->on('inv_pur_req_items.inv_pur_req_id','=','inv_pur_reqs.id');
                $join->on('inv_pur_req_items.id','=','gate_entry_items.item_id');
            })
            ->join('inv_general_rcv_items', function($join){
                $join->on('inv_general_rcv_items.inv_pur_req_item_id', '=','inv_pur_req_items.id' );
            })
            ->join('inv_general_rcvs',function($join){
                $join->on('inv_general_rcvs.id','=','inv_general_rcv_items.inv_general_rcv_id');
            })
            ->join('inv_rcvs',function($join){
                $join->on('inv_rcvs.id','=','inv_general_rcvs.inv_rcv_id');
            })
            //->whereIn('inv_rcvs.receive_no',[784,777])
            ->whereColumn([['gate_entries.challan_no','=','inv_rcvs.challan_no']])
            ->groupBy([
                'gate_entries.id',
                'gate_entries.barcode_no_id',
                'gate_entries.challan_no',
                'gate_entry_items.id',
                'inv_rcvs.receive_no',
                //'inv_rcvs.receive_date',
            ])
            ->get();

            $invGnArr=array();
            foreach($rcvGn as $row){
                //$invGnArr[$row->id]=
                $invGnArr[$row->gate_entry_item_id][]=$row->receive_no_gn;
            }
          // dd($invGnArr);
            //die;

            $rcvgnarr=array();
            foreach ($invGnArr as $key => $value) {
                $rcvgnarr[$key]=$value;
            }

            //dd($rcvgnarr);
           // die;

            $receiveDc=$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entry_items.id as gate_entry_item_id,
                inv_rcvs.receive_no as receive_no_dc
            ')
            ->join('gate_entry_items',function($join){
                $join->on('gate_entry_items.gate_entry_id','=','gate_entries.id');
            })
            ->join('inv_pur_reqs',function($join){
                $join->on('inv_pur_reqs.id','=','gate_entries.barcode_no_id');
            })
            ->join('inv_pur_req_items',function($join){
                $join->on('inv_pur_req_items.inv_pur_req_id','=','inv_pur_reqs.id');
                $join->on('inv_pur_req_items.id','=','gate_entry_items.item_id');
            })
            ->join('inv_dye_chem_rcv_items', function($join){
                $join->on('inv_dye_chem_rcv_items.inv_pur_req_item_id', '=','inv_pur_req_items.id' );
            })
            ->join('inv_dye_chem_rcvs',function($join){
                $join->on('inv_dye_chem_rcvs.id','=','inv_dye_chem_rcv_items.inv_dye_chem_rcv_id');
            })
            ->join('inv_rcvs',function($join){
                $join->on('inv_rcvs.id','=','inv_dye_chem_rcvs.inv_rcv_id');
            })
           ->whereColumn([['gate_entries.challan_no','=','inv_rcvs.challan_no']])
           // ->where([['inv_rcvs.challan_no','=',272]])
            ->groupBy([
                'gate_entries.id',
                'inv_rcvs.receive_no',
                'gate_entry_items.id'
            ])
            ->get();

            $invDcArr=array();
            foreach($receiveDc as $row){
                $invDcArr[$row->gate_entry_item_id][]=$row->receive_no_dc;
            }

            $rcvdcarr=array();
            foreach ($invDcArr as $key => $value) {
                $rcvdcarr[$key]=$value;
            }

            //dd($rcvdcarr);
            //die;

            $invpurreqitem=$this->gateentry
            ->selectRaw('
                gate_entries.id,
                gate_entries.barcode_no_id,
                gate_entries.challan_no,
                gate_entries.created_by,
                gate_entries.created_at,
                inv_pur_reqs.requisition_no as purchase_req_no,
                inv_pur_reqs.company_id,
                companies.code as purchase_company,
                inv_pur_req_items.item_account_id,
                inv_pur_req_items.remarks as purchase_item_remarks,
                item_accounts.itemcategory_id,
                item_accounts.item_description as purchase_item_desc,
                item_accounts.sub_class_name,
                item_accounts.specification,
                item_accounts.uom_id,
                itemcategories.name as itemcategory_name,         
                itemclasses.name as itemclass_name,         
                uoms.code as purchase_uom_code,
                users.name as create_user_name,
                dye_chem_rcv.receive_date_dc,
                dye_chem_rcv.rcv_qty_dc,
                general_item_rcv.receive_date_gn,
                general_item_rcv.rcv_qty_gn,
                gate_entry_items.id as gate_entry_item_id,
                inv_pur_req_items.rate,
                gate_entry_items.qty as item_qty
            ')/* inv_pur_reqs.remarks as req_item_remarks,    */
            ->join('inv_pur_reqs',function($join){
                $join->on('inv_pur_reqs.id','=','gate_entries.barcode_no_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','inv_pur_reqs.company_id');
            })
            ->join('inv_pur_req_items',function($join){
                $join->on('inv_pur_reqs.id','=','inv_pur_req_items.inv_pur_req_id');
            })
            ->join('item_accounts',function($join){
                $join->on('item_accounts.id','=','inv_pur_req_items.item_account_id');
            })
            ->join('itemclasses', function($join){
                $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
            })
            ->join('itemcategories', function($join){
                $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->join('users',function($join){
                $join->on('users.id','=','gate_entries.created_by');
            })
            ->join('gate_entry_items',function($join){
                $join->on('gate_entries.id','=','gate_entry_items.gate_entry_id');
                $join->on('inv_pur_req_items.id','=','gate_entry_items.item_id');
            })
            ->leftJoin(\DB::raw("(
                select 
                gate_entry_items.id as gate_entry_item_id,
                inv_rcvs.receive_date as receive_date_dc,
                sum(inv_dye_chem_rcv_items.qty)  as rcv_qty_dc
                from gate_entries
                join gate_entry_items on gate_entry_items.gate_entry_id=gate_entries.id
                join inv_pur_req_items on inv_pur_req_items.id=gate_entry_items.item_id
                join inv_dye_chem_rcv_items on inv_dye_chem_rcv_items.inv_pur_req_item_id=inv_pur_req_items.id
                join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
                join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
                join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
                where gate_entries.challan_no=inv_rcvs.challan_no
                and inv_dye_chem_transactions.trans_type_id=1
                and inv_dye_chem_transactions.deleted_at is null
                and inv_dye_chem_rcv_items.deleted_at is null
                group by 
                gate_entry_items.id,
                inv_rcvs.receive_date
            ) dye_chem_rcv"), "dye_chem_rcv.gate_entry_item_id", "=", "gate_entry_items.id")
            ->leftJoin(\DB::raw("(
                select 
                gate_entry_items.id as gate_entry_item_id,
                inv_rcvs.receive_date as receive_date_gn,
                sum(inv_general_rcv_items.qty)  as rcv_qty_gn
                from gate_entries
                join gate_entry_items on gate_entry_items.gate_entry_id=gate_entries.id
                join inv_pur_req_items on inv_pur_req_items.id=gate_entry_items.item_id
                join inv_general_rcv_items on inv_general_rcv_items.inv_pur_req_item_id=inv_pur_req_items.id
                join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
                join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
                join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
                where gate_entries.challan_no=inv_rcvs.challan_no
                and inv_general_transactions.trans_type_id=1
                and inv_general_transactions.deleted_at is null
                and inv_general_rcv_items.deleted_at is null
                group by 
                gate_entry_items.id,
                inv_rcvs.receive_date
            ) general_item_rcv"), "general_item_rcv.gate_entry_item_id", "=", "gate_entry_items.id")
            ->when(request('date_from'), function ($q) {
            	return $q->where('gate_entries.entry_date', '>=',request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
              return $q->where('gate_entries.entry_date', '<=',request('date_to', 0));
            })
            ->when(request('company_id'), function ($q) use($company_id) {
                return $q->where('inv_pur_reqs.company_id', '=', $company_id);
            })
            ->when(request('po_pr_no'), function ($q) use($po_pr_no) {
                return $q->where('inv_pur_reqs.requisition_no', '=', $po_pr_no);
            })
            ->where([['gate_entries.menu_id','=',$menu_id ]])
            ->orderBy('gate_entries.created_at','desc')
           // ->orderBy('gate_entries.barcode_no_id','desc')
            ->groupBy([
                'gate_entries.id',
                'gate_entries.barcode_no_id',
                'gate_entries.challan_no',
                'gate_entries.created_by',
                'gate_entries.created_at',
               // 'inv_pur_reqs.remarks',
                'inv_pur_reqs.requisition_no',
                'inv_pur_reqs.currency_id',
                'inv_pur_reqs.company_id',
                'companies.code',
                'inv_pur_req_items.remarks',
                'inv_pur_req_items.item_account_id',
                'item_accounts.itemcategory_id',
                'item_accounts.item_description',
                'item_accounts.sub_class_name',
                'item_accounts.specification',
                'item_accounts.uom_id',
                'itemcategories.name',
                'itemclasses.name',
                'uoms.code',
                'users.name',
                'dye_chem_rcv.receive_date_dc',
                'dye_chem_rcv.rcv_qty_dc',
                'general_item_rcv.receive_date_gn',
                'general_item_rcv.rcv_qty_gn',
                'gate_entry_items.id',
                'inv_pur_req_items.rate',
                'gate_entry_items.qty'
            ])
            ->get()
            ->map(function($invpurreqitem) use($rcvgnarr,$rcvdcarr){
                $invpurreqitem->qty=$invpurreqitem->item_qty;
                $invpurreqitem->uom_code=$invpurreqitem->purchase_uom_code;
                $invpurreqitem->po_pr_no=$invpurreqitem->purchase_req_no;
                $invpurreqitem->company_code=$invpurreqitem->purchase_company;
                $invpurreqitem->remarks=$invpurreqitem->purchase_item_remarks;
                $invpurreqitem->item_description =$invpurreqitem->sub_class_name.", ".$invpurreqitem->purchase_item_desc.", ".$invpurreqitem->specification;

                if($invpurreqitem->receive_date_gn){
                    $invpurreqitem->receive_date=($invpurreqitem->receive_date_gn!==null)?date('d-M-Y',strtotime($invpurreqitem->receive_date_gn)):null;
                    
                }
                if($invpurreqitem->receive_date_dc){
                    $invpurreqitem->receive_date=($invpurreqitem->receive_date_dc!==null)?date('d-M-Y',strtotime($invpurreqitem->receive_date_dc)):null;
                    
                }
                $invpurreqitem->rec_no_gn=isset($rcvgnarr[$invpurreqitem->gate_entry_item_id])?implode(',',$rcvgnarr[$invpurreqitem->gate_entry_item_id]):'';
                $invpurreqitem->rec_no_dc=isset($rcvdcarr[$invpurreqitem->gate_entry_item_id])?implode(',',$rcvdcarr[$invpurreqitem->gate_entry_item_id]):'';
    
                $invpurreqitem->rcv_qty=$invpurreqitem->rcv_qty_gn?number_format($invpurreqitem->rcv_qty_gn,2):number_format($invpurreqitem->rcv_qty_dc,2);
                $invpurreqitem->receive_no=$invpurreqitem->rec_no_gn?$invpurreqitem->rec_no_gn:$invpurreqitem->rec_no_dc;
                if ($invpurreqitem->qty) {
                        $invpurreqitem->amount=$invpurreqitem->qty*$invpurreqitem->rate;
                    }
                $invpurreqitem->amount=number_format($invpurreqitem->amount,2);
                $invpurreqitem->qty=number_format($invpurreqitem->qty,2);
                $invpurreqitem->entry_date=date('d-M-Y',strtotime($invpurreqitem->created_at));
                $invpurreqitem->purchaseThrough="Purchase Requisition";
                return $invpurreqitem;
            });
            echo json_encode($invpurreqitem);
         //   $itemCatArr=array();
         ////   foreach($invpurreqitem as $row){
         //       $itemCatArr[$row->itemcategory_id]['itemcategory_name']=$row->itemcategory_name;
         //   }
         //   $category=$invpurreqitem->groupBy('itemcategory_id');
         //   return Template::loadView('Report.POS.GateEntryRequisitionMatrix',['menu_id'=>$menu_id,'invpurreqitem'=>$invpurreqitem,'category'=>$category,'itemCatArr'=>$itemCatArr]);
        }

    }

    public function getPrPo(){
        $menu_id=request('menu_id', 0);
        //Fabric Purchase Order
        if($menu_id==1){
            $purchaseorder = $this->pofabric
                ->selectRaw('
                    po_fabrics.id as purchase_order_id,
                    po_fabrics.po_no as fabric_po_no,
                    po_fabrics.po_date,
                    po_fabrics.company_id,
                    po_fabrics.supplier_id,
                    po_fabrics.remarks as po_fabric_remarks,
                    companies.code as fabric_company,
                    suppliers.name as fabric_supplier_name  
                ')
                ->join('companies',function($join){
                $join->on('companies.id','=','po_fabrics.company_id');
                })
                ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_fabrics.supplier_id');
                })
                ->when(request('po_pr_date'), function ($q) {
                    return $q->where('po_fabrics.po_date', '=',request('po_pr_date', 0));
                })
                ->when(request('po_pr_no'), function ($q)  {
                    return $q->where('po_fabrics.po_no', '=', request('po_pr_no', 0));
                })
                ->orderBy('po_fabrics.id','desc')
                ->get()
                ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->fabric_po_no;
                $purchaseorder->po_pr_date=$purchaseorder->po_date;
                $purchaseorder->company_name=$purchaseorder->fabric_company;
                $purchaseorder->supplier_name=$purchaseorder->fabric_supplier_name;
                $purchaseorder->master_remarks=$purchaseorder->po_fabric_remarks;
                return $purchaseorder;
            });

            echo json_encode($purchaseorder);
        }
        //Trims Purchase Order
        if($menu_id==2){
            $purchaseorder =$this->potrim
            ->selectRaw('
                    po_trims.id as purchase_order_id,
                    po_trims.po_no as trim_po_no,
                    po_trims.po_date,
                    po_trims.supplier_id,
                    po_trims.company_id,
                    po_trims.remarks as po_trim_remarks,
                    suppliers.name as trim_supplier_name,
                    companies.code as trim_company
                ')
                ->join('companies',function($join){
                $join->on('companies.id','=','po_trims.company_id');
                })
                ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_trims.supplier_id');
                })
                ->leftJoin('currencies',function($join){
                $join->on('currencies.id','=','po_trims.currency_id');
                })
                ->when(request('po_pr_date'), function ($q) {
                    return $q->where('po_trims.po_date', '=',request('po_pr_date', 0));
                })
                ->when(request('po_pr_no'), function ($q)  {
                    return $q->where('po_trims.po_no', '=', request('po_pr_no',0));
                })
                ->orderBy('po_trims.id','desc')
                ->get()
                ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->trim_po_no;
                $purchaseorder->po_pr_date=$purchaseorder->po_date;
                $purchaseorder->supplier_name=$purchaseorder->trim_supplier_name;
                $purchaseorder->company_name=$purchaseorder->trim_company;
                $purchaseorder->master_remarks=$purchaseorder->po_trim_remarks;
                return $purchaseorder;
                }); 
            echo json_encode($purchaseorder);
        }
        //Yarn Purchase Order 
        if($menu_id==3){
            $purchaseorder =$this->poyarn
            ->selectRaw('
                    po_yarns.id as purchase_order_id,
                    po_yarns.po_no as yarn_po_no,
                    po_yarns.po_date,
                    po_yarns.company_id,
                    po_yarns.supplier_id,
                    po_yarns.remarks as po_yarn_remarks,
                    companies.code as yarn_company,
                    suppliers.name as yarn_supplier_name
                ')
                ->join('companies',function($join){
                $join->on('companies.id','=','po_yarns.company_id');
                })
                ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_yarns.supplier_id');
                })
                ->when(request('po_pr_date'), function ($q) {
                    return $q->where('po_yarns.po_date', '=',request('po_pr_date', 0));
                })
                ->when(request('po_pr_no'), function ($q)  {
                    return $q->where('po_yarns.po_no', '=', request('po_pr_no',0));
                })
                ->orderBy('po_yarns.id','desc')
                ->get()
                ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->yarn_po_no;
                $purchaseorder->po_pr_date=$purchaseorder->po_date;
                $purchaseorder->company_name=$purchaseorder->yarn_company;
                $purchaseorder->supplier_name=$purchaseorder->yarn_supplier_name;
                $purchaseorder->master_remarks=$purchaseorder->po_yarn_remarks;
                return $purchaseorder;
                });
            echo json_encode($purchaseorder);
        }
        //Knit Purchase Order 
        if($menu_id==4){
            $purchaseorder=$this->poknitservice
            ->selectRaw('
                po_yarns.id as purchase_order_id,
                po_knit_services.po_no as knit_service_po_no,
                po_knit_services.po_date,
                po_knit_services.company_id,
                po_knit_services.supplier_id,
                po_knit_services.remarks as po_knit_remarks,
                companies.code as knit_company_name,
                suppliers.name as knit_service_supplier_name
            ')
            ->join('po_knit_services',function($join){
                $join->on('po_knit_services.id','=','gate_entries.barcode_no_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','po_knit_services.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_knit_services.supplier_id');
            })
            ->when(request('po_pr_date'), function ($q) {
                return $q->where('po_knit_services.po_date', '=',request('po_pr_date', 0));
            })
            ->when(request('po_pr_no'), function ($q)  {
                return $q->where('po_knit_services.po_no', '=', request('po_pr_no',0));
            })
            ->orderBy('po_knit_services.id','desc')
            ->get()
            ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->knit_service_po_no;
                $purchaseorder->po_pr_date=$purchaseorder->po_date;
                $purchaseorder->supplier_name=$purchaseorder->knit_service_supplier_name;
                $purchaseorder->company_name=$purchaseorder->knit_company_name;
                $purchaseorder->master_remarks=$purchaseorder->po_knit_remarks;
                return $purchaseorder;
            });   
            echo json_encode($purchaseorder);
        }
        //AOP Service Order
        if($menu_id==5){
            $purchaseorder=$this->poaopservice
            ->selectRaw('
                po_aop_services.id as purchase_order_id,
                po_aop_services.po_no as aop_service_po_no,
                po_aop_services.po_date,
                po_aop_services.company_id,
                po_aop_services.supplier_id,
                po_aop_services.remarks as po_aop_remarks,
                companies.code as aop_company,
                suppliers.name as aop_service_supplier_name
            ')
            ->join('companies',function($join){
                $join->on('companies.id','=','po_aop_services.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_aop_services.supplier_id');
            })
            ->when(request('po_pr_date'), function ($q) {
                return $q->where('po_aop_services.po_date', '=',request('po_pr_date', 0));
            })
            ->when(request('po_pr_no'), function ($q)  {
                return $q->where('po_aop_services.po_no', '=', request('po_pr_no',0));
            })
            ->orderBy('po_aop_services.id','desc')
            ->get()
            ->map(function($purchaseorder){
            $purchaseorder->po_pr_no=$purchaseorder->aop_service_po_no;
            $purchaseorder->po_pr_date=$purchaseorder->po_date;
            $purchaseorder->company_name=$purchaseorder->aop_company;
            $purchaseorder->supplier_name=$purchaseorder->aop_service_supplier_name;
            $purchaseorder->master_remarks=$purchaseorder->po_aop_remarks;
            return $purchaseorder;
            });
            echo json_encode($purchaseorder);
        }
        //Dyeing Service Work Order
        if($menu_id==6){    
            $purchaseorder = $this->podyeingservice
                ->selectRaw('
                    po_dyeing_services.id as purchase_order_id,
                    po_dyeing_services.po_no as dyeing_service_po_no,
                    po_dyeing_services.po_date,
                    po_dyeing_services.company_id,
                    po_dyeing_services.supplier_id,
                    po_dyeing_services.remarks as po_dyeing_service_remarks,
                    companies.code as dyeing_company,
                    suppliers.name as dyeing_service_supplier_name  
                ')
                ->join('companies',function($join){
                $join->on('companies.id','=','po_dyeing_services.company_id');
                })
                ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_dyeing_services.supplier_id');
                })
                ->when(request('po_pr_date'), function ($q) {
                    return $q->where('po_dyeing_services.po_date', '=',request('po_pr_date', 0));
                })
                ->when(request('po_pr_no'), function ($q)  {
                    return $q->where('po_dyeing_services.po_no', '=', request('po_pr_no',0));
                })
                ->orderBy('po_dyeing_services.id','desc')
                ->get()
                ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->dyeing_service_po_no;
                $purchaseorder->po_pr_date=$purchaseorder->po_date;
                $purchaseorder->company_name=$purchaseorder->dyeing_company;
                $purchaseorder->supplier_name=$purchaseorder->dyeing_service_supplier_name;
                $purchaseorder->master_remarks=$purchaseorder->po_dyeing_service_remarks;
                return $purchaseorder;
            });

            echo json_encode($purchaseorder);
        }
        //Dye & Chem Purchase Order 
        if($menu_id==7){
            $purchaseorder=$this->podyechem
                ->join('companies',function($join){
                $join->on('companies.id','=','po_dye_chems.company_id');
                })
                ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_dye_chems.supplier_id');
                })
                ->when(request('po_pr_date'), function ($q) {
                    return $q->where('po_dye_chems.po_date', '=',request('po_pr_date', 0));
                })
                ->when(request('po_pr_no'), function ($q)  {
                    return $q->where('po_dye_chems.po_no', '=', request('po_pr_no',0));
                })
                ->orderBy('po_dye_chems.id','desc')
                ->get([
                    'po_dye_chems.id as purchase_order_id',
                    'po_dye_chems.po_no as dye_chem_po_no',
                    'po_dye_chems.po_date',
                    'po_dye_chems.company_id',
                    'po_dye_chems.supplier_id',
                    'po_dye_chems.remarks as po_dye_chem_remarks',
                    'companies.code as dye_chem_company',
                    'suppliers.name as dyechem_supplier_name'
                ])
                ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->dye_chem_po_no;
                $purchaseorder->po_pr_date=$purchaseorder->po_date;
                $purchaseorder->supplier_name=$purchaseorder->dyechem_supplier_name;
                $purchaseorder->company_name=$purchaseorder->dye_chem_company;                
                $purchaseorder->master_remarks=$purchaseorder->po_dye_chem_remarks;
                return $purchaseorder;
                });
            echo json_encode($purchaseorder);
        }
        //General Item Purchase Worder
        if($menu_id==8){
            $purchaseorder =$this->pogeneral
            ->selectRaw('
                po_generals.id as purchase_order_id,
                po_generals.po_no as general_po_no,
                po_generals.po_date,
                po_generals.company_id,
                po_generals.supplier_id,
                po_generals.remarks as po_general_remarks,
                companies.code as po_general_company,
                suppliers.name as general_supplier_name
            ')
            ->join('companies',function($join){
                $join->on('companies.id','=','po_generals.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_generals.supplier_id');
            })
            ->when(request('po_pr_date'), function ($q) {
                return $q->where('po_generals.po_date', '=',request('po_pr_date', 0));
            })
            ->when(request('po_pr_no'), function ($q)  {
                return $q->where('po_generals.po_no', '=', request('po_pr_no',0));
            })
            ->orderBy('po_generals.id','desc')
            ->get()
            ->map(function($purchaseorder){
                $purchaseorder->po_pr_no=$purchaseorder->general_po_no;
                $purchaseorder->po_pr_date=$purchaseorder->po_date;
                $purchaseorder->company_name=$purchaseorder->po_general_company;
                $purchaseorder->supplier_name=$purchaseorder->general_supplier_name;
                $purchaseorder->master_remarks=$purchaseorder->po_general_remarks;
                return $purchaseorder;
            });
            echo json_encode($purchaseorder);
        }
        //Yarn Dyeing Work Order
        if($menu_id==9){
            $data=$this->poyarndyeing
            ->selectRaw('
                po_yarn_dyeings.id as purchase_order_id,
                po_yarn_dyeings.po_no as yarn_dyeing_po_no,
                po_yarn_dyeings.po_date,
                po_yarn_dyeings.company_id,
                po_yarn_dyeings.supplier_id,
                po_yarn_dyeings.remarks as po_yarn_dyeing_remarks,
                companies.code as yarn_dyeing_company,
                suppliers.name as yarn_dyeing_supplier_name
            ')
            ->join('companies',function($join){
                $join->on('companies.id','=','po_yarn_dyeings.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_yarn_dyeings.supplier_id');
            })
            ->when(request('po_pr_date'), function ($q) {
                return $q->where('po_yarn_dyeings.po_date', '=',request('po_pr_date', 0));
            })
            ->when(request('po_pr_no'), function ($q)  {
                return $q->where('po_yarn_dyeings.po_no', '=', request('po_pr_no',0));
            })
            ->orderBy('po_yarn_dyeings.id','desc')
            ->groupBy([
                'po_yarn_dyeings.id',
                'po_yarn_dyeings.po_no',
                'po_yarn_dyeings.po_date',
                'po_yarn_dyeings.company_id',
                'po_yarn_dyeings.supplier_id',
                'po_yarn_dyeings.remarks',
                'companies.code',
                'suppliers.name'
            ])
            ->get()
            ->map(function ($data) {
                $data->po_pr_no=$data->yarn_dyeing_po_no;
                $data->po_pr_date=$data->po_date;
                $data->company_name=$data->yarn_dyeing_company;
                $data->supplier_name=$data->yarn_dyeing_supplier_name;
                $data->master_remarks=$data->po_yarn_dyeing_remarks;
                return $data;
            });  
            echo json_encode($data);
        }
        //Embelishment Purchase Order
        if($menu_id==10){
            $purchaseorder=$this->poembservice
            ->selectRaw('
                po_emb_services.id as purchase_order_id,
                po_emb_services.po_no as emb_service_po_no,
                po_emb_services.company_id,
                po_emb_services.supplier_id,
                po_emb_services.remarks as po_emb_remarks,
                companies.code as emb_company,
                suppliers.name as emb_service_supplier_name
            ')
            ->join('companies',function($join){
                $join->on('companies.id','=','po_emb_services.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','po_emb_services.supplier_id');
            })
            ->when(request('po_pr_date'), function ($q) {
                return $q->where('po_emb_services.po_date', '=',request('po_pr_date', 0));
            })
            ->when(request('po_pr_no'), function ($q)  {
                return $q->where('po_emb_services.po_no', '=', request('po_pr_no',0));
            })
            ->orderBy('po_emb_services.id','desc')
            ->get()
            ->map(function($purchaseorder){
            $purchaseorder->po_pr_no=$purchaseorder->emb_service_po_no;
            $purchaseorder->po_pr_date=$purchaseorder->po_date;
            $purchaseorder->company_name=$purchaseorder->emb_company;
            $purchaseorder->supplier_name=$purchaseorder->emb_service_supplier_name;
            $purchaseorder->master_remarks=$purchaseorder->po_emb_remarks;
            return $purchaseorder;
            });
            echo json_encode($purchaseorder);
        }
        //General Service Work Order
        if($menu_id==11){
            $purchaseorder=$this->pogeneralservice
            ->selectRaw('
                po_general_services.id as purchase_order_id,
                po_general_services.po_no as general_service_po_no,
                po_general_services.po_date,
                po_general_services.company_id,
                po_general_services.supplier_id,
                companies.code as general_company,
                suppliers.name as general_service_supplier,
                po_general_services.remarks as po_general_remarks
            ')
            ->join('companies',function($join){
            $join->on('companies.id','=','po_general_services.company_id');
            })
            ->join('suppliers',function($join){
            $join->on('suppliers.id','=','po_general_services.supplier_id');
            })
            ->when(request('po_pr_date'), function ($q) {
                return $q->where('po_general_services.po_date', '=',request('po_pr_date', 0));
            })
            ->when(request('po_pr_no'), function ($q)  {
                return $q->where('po_general_services.po_no', '=', request('po_pr_no',0));
            })
            ->orderBy('po_general_services.id','desc')
            ->get()
            ->map(function($purchaseorder){
            $purchaseorder->po_pr_no=$purchaseorder->general_service_po_no;
            $purchaseorder->po_pr_date=$purchaseorder->po_date;
            $purchaseorder->company_name=$purchaseorder->general_company;
            $purchaseorder->supplier_name=$purchaseorder->general_service_supplier;
            $purchaseorder->master_remarks=$purchaseorder->po_general_remarks;
            return $purchaseorder;
            });
            echo json_encode($purchaseorder);
        }
        //Inventory Purchase Requisition
        if($menu_id==103){
            $invpurreqitem=$this->invpurreq
            ->selectRaw('
                inv_pur_reqs.id as purchase_order_id,
                inv_pur_reqs.requisition_no as purchase_req_no,
                inv_pur_reqs.req_date,
                inv_pur_reqs.company_id,
                inv_pur_reqs.remarks as req_item_remarks,        
                companies.code as purchase_company
            ')
            ->join('companies',function($join){
            $join->on('companies.id','=','inv_pur_reqs.company_id');
            })
            ->when(request('po_pr_date'), function ($q) {
                return $q->where('inv_pur_reqs.req_date', '=',request('po_pr_date', 0));
            })
            ->when(request('po_pr_no'), function ($q)  {
                return $q->where('inv_pur_reqs.requisition_no', '=', request('po_pr_no',0));
            })
            ->orderBy('inv_pur_reqs.id','desc')
            ->groupBy([
            'inv_pur_reqs.id',
            'inv_pur_reqs.remarks',
            'inv_pur_reqs.requisition_no',
            'inv_pur_reqs.req_date',
            'inv_pur_reqs.currency_id',
            'inv_pur_reqs.company_id',
            'companies.code',
            ])
            ->get()
            ->map(function($invpurreqitem){
            $invpurreqitem->po_pr_no=$invpurreqitem->purchase_req_no;
            $invpurreqitem->po_pr_date=$invpurreqitem->req_date;
            $invpurreqitem->company_name=$invpurreqitem->purchase_company;
            $invpurreqitem->master_remarks=$invpurreqitem->req_item_remarks;
            return $invpurreqitem;
            });
            echo json_encode($invpurreqitem);
        }
    }

}
