<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
//use App\Repositories\Contracts\Purchase\PurchaseOrderRepository;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Purchase\PoDyeChemRepository;
use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Repositories\Contracts\Purchase\PoGeneralRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Purchase\PoAopServiceRepository;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Purchase\PoGeneralServiceRepository;
use App\Repositories\Contracts\Account\AccTermLoanRepository;
use App\Repositories\Contracts\Account\AccTermLoanInstallmentRepository;

use App\Library\Template;
use App\Http\Requests\Commercial\Import\ImpLcPoRequest;

class ImpLcPoController extends Controller {

    private $implcpo;
    private $implc;
    private $purchaseorder;
    private $poyarn;
    private $potrim;
    private $podyechem;
    private $podyeingservice;
    private $pogeneral;
    private $poknitservice;
    private $poaopservice;
    private $poyarndyeing;
    private $poembservice;
    private $pofabric;
    private $pogeneralservice;
    private $acctermloan;
    private $acctermloaninstallment;


    public function __construct(
      ImpLcPoRepository $implcpo,
      ImpLcRepository $implc, 
      //PurchaseOrderRepository $purchaseorder,
      PoFabricRepository $pofabric,
      PoTrimRepository $potrim,
      PoDyeChemRepository $podyechem,
      PoDyeingServiceRepository $podyeingservice,
      PoGeneralRepository $pogeneral,
      PoKnitServiceRepository $poknitservice,
      PoYarnRepository $poyarn,
      PoAopServiceRepository $poaopservice,
      PoYarnDyeingRepository $poyarndyeing,
      PoEmbServiceRepository $poembservice,
      PoGeneralServiceRepository $pogeneralservice,
      AccTermLoanRepository $acctermloan,
      AccTermLoanInstallmentRepository $acctermloaninstallment

      ) {
        $this->implcpo = $implcpo;
        $this->implc = $implc;
        $this->pofabric = $pofabric;
        $this->potrim = $potrim;
        $this->poyarn = $poyarn;
        $this->podyeingservice = $podyeingservice;
        $this->pogeneral = $pogeneral;
        $this->poknitservice = $poknitservice;
        $this->poaopservice = $poaopservice;
        $this->poyarndyeing = $poyarndyeing;
        //$this->purchaseorder = $purchaseorder;
        $this->poembservice = $poembservice;
        $this->podyechem = $podyechem;
        $this->pogeneralservice = $pogeneralservice;
        $this->acctermloan = $acctermloan;
        $this->acctermloaninstallment = $acctermloaninstallment;

        $this->middleware('auth');
        $this->middleware('permission:view.implcpos',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.implcpos', ['only' => ['store']]);
        $this->middleware('permission:edit.implcpos',   ['only' => ['update']]);
        $this->middleware('permission:delete.implcpos', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $implc=$this->implc->find(request('imp_lc_id',0));
      $menu_id=$implc->menu_id; 
      //Fabric Purchase Order
      if ($menu_id==1) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lcs.menu_id,
          imp_lc_pos.id,
          imp_lc_pos.purchase_order_id,
          po_fabrics.po_no as fabric_po_no,
          po_fabrics.amount as fabric_amount,
          po_fabrics.currency_id,
          po_fabrics.company_id,
          po_fabrics.supplier_id,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          pofabric.po_qty
          ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->leftJoin('po_fabrics',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_fabrics.id');
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
        ->leftJoin(\DB::raw("(
          select
          po_fabrics.id as purchase_order_id,
          sum(po_fabric_items.qty) as po_qty
          from
          po_fabrics
          join po_fabric_items on po_fabric_items.po_fabric_id=po_fabrics.id
          where po_fabric_items.deleted_at is null
          group by
          po_fabrics.id
        )pofabric"),"pofabric.purchase_order_id","=","imp_lc_pos.purchase_order_id")
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->po_no=$purchaseorder->fabric_po_no;
          $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
          $purchaseorder->amount=number_format($purchaseorder->fabric_amount,2);
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
      //Trims Purchase Order
      elseif($menu_id==2){
        $purchaseorder =$this->implc
          ->selectRaw('
            imp_lcs.menu_id,
            imp_lc_pos.id,
            imp_lc_pos.purchase_order_id,
            po_trims.po_no as trim_po_no,
            po_trims.amount as trim_amount,
            po_trims.itemcategory_id,
            po_trims.currency_id,
            po_trims.company_id,
            po_trims.supplier_id,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            itemcategories.name as itemcategory,
            potrim.po_qty
            ')
          ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
          ->leftJoin('po_trims',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_trims.id');
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
          ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','po_trims.itemcategory_id');
          })
          ->leftJoin(\DB::raw("(
            select
            po_trims.id as purchase_order_id,
            sum(po_trim_items.qty) as po_qty
            from
            po_trims
            join po_trim_items on po_trim_items.po_trim_id=po_trims.id
            where po_trim_items.deleted_at is null
            group by
            po_trims.id
          )potrim"),"potrim.purchase_order_id","=","imp_lc_pos.purchase_order_id")
          ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
          //->where([['imp_lc_id','=',request('imp_lc_id',0)]])
          ->get()
          ->map(function($purchaseorder){
            $purchaseorder->po_no=$purchaseorder->trim_po_no;
            $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
            $purchaseorder->amount=number_format($purchaseorder->trim_amount,2);
            return $purchaseorder;
          });
          echo json_encode($purchaseorder);
      }
      elseif ($menu_id==3) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lcs.menu_id,
          imp_lc_pos.id,
          imp_lc_pos.purchase_order_id,
          po_yarns.po_no as yarn_po_no,
          po_yarns.amount as yarn_amount,
          po_yarns.itemcategory_id,
          po_yarns.currency_id,
          po_yarns.company_id,
          po_yarns.supplier_id,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          itemcategories.name as itemcategory,
          poyarn.po_qty
        ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->leftJoin('po_yarns',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_yarns.id');
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
        ->leftJoin('itemcategories',function($join){
          $join->on('itemcategories.id','=','po_yarns.itemcategory_id');
        })
        ->leftJoin(\DB::raw("(
          select
          po_yarns.id as purchase_order_id,
          sum(po_yarn_items.qty) as po_qty
          from
          po_yarns
          join po_yarn_items on po_yarn_items.po_yarn_id=po_yarns.id
          where po_yarn_items.deleted_at is null
          group by
          po_yarns.id
        )poyarn"),"poyarn.purchase_order_id","=","imp_lc_pos.purchase_order_id")
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
      //->where([['imp_lc_id','=',request('imp_lc_id',0)]])
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->po_no=$purchaseorder->yarn_po_no;
          $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
          $purchaseorder->amount=number_format($purchaseorder->yarn_amount,2);
          return $purchaseorder;
          
        });
        echo json_encode($purchaseorder);
      }//knit Service
      elseif ($menu_id==4) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lcs.menu_id,
          imp_lc_pos.id,
          imp_lc_pos.purchase_order_id,
          po_knit_services.po_no as knitservice_po_no,
          po_knit_services.amount as knitservice_amount,
          po_knit_services.itemcategory_id,
          po_knit_services.currency_id,
          po_knit_services.company_id,
          po_knit_services.supplier_id,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          itemcategories.name as itemcategory,
          poknitservice.po_qty
          ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->leftJoin('po_knit_services',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_knit_services.id');
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
        ->leftJoin('itemcategories',function($join){
          $join->on('itemcategories.id','=','po_knit_services.itemcategory_id');
        })
        ->leftJoin(\DB::raw("(
          select
          po_knit_services.id as purchase_order_id,
          sum(po_knit_service_items.qty) as po_qty
          from
          po_knit_services
          join po_knit_service_items on po_knit_service_items.po_knit_service_id=po_knit_services.id
          where po_knit_service_items.deleted_at is null
          group by
          po_knit_services.id
        )poknitservice"),"poknitservice.purchase_order_id","=","imp_lc_pos.purchase_order_id")
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        //->where([['imp_lc_id','=',request('imp_lc_id',0)]])
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->po_no=$purchaseorder->knitservice_po_no;
          $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
          $purchaseorder->amount=number_format($purchaseorder->knitservice_amount,2);
          return $purchaseorder;
          
        });
        echo json_encode($purchaseorder);
      }
      //AOP Service
      elseif ($menu_id==5) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lcs.menu_id,
          imp_lc_pos.id,
          imp_lc_pos.purchase_order_id,
          po_aop_services.po_no as aopservice_po_no,
          po_aop_services.amount as aopservice_amount,
          po_aop_services.itemcategory_id,
          po_aop_services.currency_id,
          po_aop_services.company_id,
          po_aop_services.supplier_id,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          itemcategories.name as itemcategory,
          poaopservice.po_qty
        ')
        ->join('imp_lc_pos',function($join){
          $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
        })
        ->leftJoin('po_aop_services',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_aop_services.id');
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
        ->leftJoin('itemcategories',function($join){
          $join->on('itemcategories.id','=','po_aop_services.itemcategory_id');
        })
        ->leftJoin(\DB::raw("(
          select
          po_aop_services.id as purchase_order_id,
          sum(po_aop_service_items.qty) as po_qty
          from
          po_aop_services
          join po_aop_service_items on po_aop_service_items.po_aop_service_id=po_aop_services.id
          where po_aop_service_items.deleted_at is null
          group by
          po_aop_services.id
        )poaopservice"),"poaopservice.purchase_order_id","=","imp_lc_pos.purchase_order_id")
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        //->where([['imp_lc_id','=',request('imp_lc_id',0)]])
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->po_no=$purchaseorder->aopservice_po_no;
          $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
          $purchaseorder->amount=number_format($purchaseorder->aopservice_amount,2);
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
      //Dyeing Service 
      elseif ($menu_id==6) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lcs.menu_id,
          imp_lc_pos.id,
          imp_lc_pos.purchase_order_id,
          po_dyeing_services.po_no as dyeingservice_po_no,
          po_dyeing_services.amount as dyeingservice_amount,
          po_dyeing_services.itemcategory_id,
          po_dyeing_services.currency_id,
          po_dyeing_services.company_id,
          po_dyeing_services.supplier_id,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          itemcategories.name as itemcategory,
          podyeingservice.po_qty
          ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->leftJoin('po_dyeing_services',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_dyeing_services.id');
          })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','po_dyeing_services.company_id');
          })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_dyeing_services.supplier_id');
          })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_dyeing_services.currency_id');
          })
        ->leftJoin('itemcategories',function($join){
          $join->on('itemcategories.id','=','po_dyeing_services.itemcategory_id');
        })
        ->leftJoin(\DB::raw("(
          select
          po_dyeing_services.id as purchase_order_id,
          sum(po_dyeing_service_items.qty) as po_qty
          from
          po_dyeing_services
          join po_dyeing_service_items on po_dyeing_service_items.po_dyeing_service_id=po_dyeing_services.id
          where po_dyeing_service_items.deleted_at is null
          group by
          po_dyeing_services.id
        ) podyeingservice"),"podyeingservice.purchase_order_id","=","imp_lc_pos.purchase_order_id")
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        //->where([['imp_lc_id','=',request('imp_lc_id',0)]])
        ->get()
        ->map(function($purchaseorder){
          
          $purchaseorder->po_no=$purchaseorder->dyeingservice_po_no;
          $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
          $purchaseorder->amount=number_format($purchaseorder->dyeingservice_amount,2);
          return $purchaseorder;
          
        });
        echo json_encode($purchaseorder);
      }
      //Dyes & Chemical Purchase
      elseif ($menu_id==7) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lcs.menu_id,
          imp_lc_pos.id,
          imp_lc_pos.purchase_order_id,
          po_dye_chems.po_no as dyechem_po_no,
          po_dye_chems.amount as dyechem_amount,
          po_dye_chems.itemcategory_id,
          po_dye_chems.currency_id,
          po_dye_chems.company_id,
          po_dye_chems.supplier_id,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          itemcategories.name as itemcategory,
          podyechem.po_qty
        ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->leftJoin('po_dye_chems',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_dye_chems.id');
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
        ->leftJoin('itemcategories',function($join){
          $join->on('itemcategories.id','=','po_dye_chems.itemcategory_id');
        })
        ->leftJoin(\DB::raw("(
          select
          po_dye_chems.id as purchase_order_id,
          sum(po_dye_chem_items.qty) as po_qty
          from
          po_dye_chems
          join po_dye_chem_items on po_dye_chem_items.po_dye_chem_id=po_dye_chems.id
          where po_dye_chem_items.deleted_at is null
          group by
          po_dye_chems.id
        ) podyechem"),"podyechem.purchase_order_id","=","imp_lc_pos.purchase_order_id")
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        //->where([['imp_lc_id','=',request('imp_lc_id',0)]])
        ->get()
        ->map(function($purchaseorder){
          
          $purchaseorder->po_no=$purchaseorder->dyechem_po_no;
          $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
          $purchaseorder->amount=number_format($purchaseorder->dyechem_amount,2);
          return $purchaseorder;
          
        });
        echo json_encode($purchaseorder);
      }
      //General Item Purchase Order
      elseif ($menu_id==8) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lcs.menu_id,
          imp_lc_pos.id,
          imp_lc_pos.purchase_order_id,
          po_generals.po_no as general_po_no,
          po_generals.amount as general_amount,
          po_generals.itemcategory_id,
          po_generals.currency_id,
          po_generals.company_id,
          po_generals.supplier_id,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          itemcategories.name as itemcategory,
          pogeneral.po_qty
          ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->leftJoin('po_generals',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_generals.id');
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
        ->leftJoin('itemcategories',function($join){
          $join->on('itemcategories.id','=','po_generals.itemcategory_id');
        })
        ->leftJoin(\DB::raw("(
          select
          po_generals.id as purchase_order_id,
          sum(po_general_items.qty) as po_qty
          from
          po_generals
          join po_general_items on po_general_items.po_general_id=po_generals.id
          where po_general_items.deleted_at is null
          group by
          po_generals.id
        ) pogeneral"),"pogeneral.purchase_order_id","=","imp_lc_pos.purchase_order_id")
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        //->where([['imp_lc_id','=',request('imp_lc_id',0)]])
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->po_no=$purchaseorder->general_po_no;
          $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
          $purchaseorder->amount=number_format($purchaseorder->general_amount,2);
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
      //Yarn Dyeing
      elseif ($menu_id==9) {
        $purchaseorder =$this->implc
        ->selectRaw('
            imp_lcs.menu_id,
            imp_lc_pos.id,
            imp_lc_pos.purchase_order_id,
            po_yarn_dyeings.po_no as yarndye_po_no,
            po_yarn_dyeings.amount as yarndye_amount,
            po_yarn_dyeings.itemcategory_id,
            po_yarn_dyeings.currency_id,
            po_yarn_dyeings.company_id,
            po_yarn_dyeings.supplier_id,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            itemcategories.name as itemcategory,
            poyarndyeing.po_qty
          ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->leftJoin('po_yarn_dyeings',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_yarn_dyeings.id');
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
        ->leftJoin('itemcategories',function($join){
          $join->on('itemcategories.id','=','po_yarn_dyeings.itemcategory_id');
        })
        ->leftJoin(\DB::raw("(
          select
          po_yarn_dyeings.id as purchase_order_id,
          sum(po_yarn_dyeing_items.qty) as po_qty
          from
          po_yarn_dyeings
          join po_yarn_dyeing_items on po_yarn_dyeing_items.po_yarn_dyeing_id=po_yarn_dyeings.id
          where po_yarn_dyeing_items.deleted_at is null
          group by
          po_yarn_dyeings.id
        ) poyarndyeing"),"poyarndyeing.purchase_order_id","=","imp_lc_pos.purchase_order_id")
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        //->where([['imp_lc_id','=',request('imp_lc_id',0)]])
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->po_no=$purchaseorder->yarndye_po_no;
          $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
          $purchaseorder->amount=number_format($purchaseorder->yarndye_amount,2);
          return $purchaseorder;
          
        });
        echo json_encode($purchaseorder);
      }
      //Embelishment Work Order
      elseif ($menu_id==10) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lcs.menu_id,
          imp_lc_pos.id,
          imp_lc_pos.purchase_order_id,
          po_emb_services.po_no as poemb_po_no,
          po_emb_services.amount as poemb_amount,
          po_emb_services.itemcategory_id,
          po_emb_services.currency_id,
          po_emb_services.company_id,
          po_emb_services.supplier_id,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          itemcategories.name as itemcategory,
          poembservice.po_qty
        ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->leftJoin('po_emb_services',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_emb_services.id');
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
        ->leftJoin('itemcategories',function($join){
          $join->on('itemcategories.id','=','po_emb_services.itemcategory_id');
        })
        ->leftJoin(\DB::raw("(
          select
          po_emb_services.id as purchase_order_id,
          sum(po_emb_service_items.qty) as po_qty
          from
          po_emb_services
          join po_emb_service_items on po_emb_service_items.po_emb_service_id=po_emb_services.id
          where po_emb_service_items.deleted_at is null
          group by
          po_emb_services.id
        ) poembservice"),"poembservice.purchase_order_id","=","imp_lc_pos.purchase_order_id")
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        //->where([['imp_lc_id','=',request('imp_lc_id',0)]])
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->po_no=$purchaseorder->poemb_po_no;
          $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
          $purchaseorder->amount=number_format($purchaseorder->poemb_amount,2);
          return $purchaseorder;
          
        });
        echo json_encode($purchaseorder);
      }
      //General Service Work Order
      elseif ($menu_id==11) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lcs.menu_id,
          imp_lc_pos.id,
          imp_lc_pos.purchase_order_id,
          po_general_services.po_no as poemb_po_no,
          po_general_services.amount as poemb_amount,
          po_general_services.currency_id,
          po_general_services.company_id,
          po_general_services.supplier_id,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          pogeneralservice.po_qty
        ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->leftJoin('po_general_services',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_general_services.id');
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
        ->leftJoin(\DB::raw("(
          select
          po_general_services.id as purchase_order_id,
          sum(po_general_service_items.qty) as po_qty
          from
          po_general_services
          join po_general_service_items on po_general_service_items.po_general_service_id=po_general_services.id
          where po_general_service_items.deleted_at is null
          group by
          po_general_services.id
        ) pogeneralservice"),"pogeneralservice.purchase_order_id","=","imp_lc_pos.purchase_order_id")
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        //->where([['imp_lc_id','=',request('imp_lc_id',0)]])
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->po_no=$purchaseorder->poemb_po_no;
          $purchaseorder->po_qty=number_format($purchaseorder->po_qty,2);
          $purchaseorder->amount=number_format($purchaseorder->poemb_amount,2);
          return $purchaseorder;
        });

        echo json_encode($purchaseorder);
      }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImpLcPoRequest $request) {

        \DB::beginTransaction();
        try
        {
          foreach($request->purchase_order_id as $index=>$purchase_order_id){
              if($purchase_order_id)
              {
                  $implcpo = $this->implcpo->create([
                    'purchase_order_id' => $purchase_order_id,
                    'imp_lc_id' => $request->imp_lc_id,
                  ]);
              }
          }
          $this->updateLoan($request->imp_lc_id);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        if($implcpo){
            return response()->json(array('success' => true,'id' =>  $implcpo->id,'message' => 'Save Successfully'),200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImpLcPoRequest $request, $id) {
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
      $implcpo=$this->implcpo->find($id);
        $last_delete_date=date('Y-m-d H:i:s');
        $importbackbylc=$this->implc
        ->join('imp_backed_exp_lc_scs',function($join){
          $join->on('imp_lcs.id','=','imp_backed_exp_lc_scs.imp_lc_id');
        })
        ->where([['imp_lcs.id','=',$implcpo->imp_lc_id]])
        ->get()
        ->first();
        if ($importbackbylc) {
          return response()->json(array('success' => false,'message' => 'Untag LC/SC from Backed by Export LC/SC first.Delete unsuccessfully'),200);
        }
        \DB::beginTransaction();
        try
        {
        $this->implc->where([['id','=',$implcpo->imp_lc_id]])->update(['last_untagged_po_at'=>$last_delete_date]);
        $this->updateLoan($implcpo->imp_lc_id);
        $this->implcpo->delete($id);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
    }

    private function updateLoan($implcid){
      $implc=$this->implc->find($implcid);
      if($implc->lc_type_id==1 || $implc->lc_type_id==2)
      {
        $implcamount= collect(\DB::select("
        select 
        imp_lcs.id,
        case when 
        imp_lcs.menu_id=1
        then sum(po_fabrics.amount)
        when 
        imp_lcs.menu_id=2
        then sum(po_trims.amount)
        when 
        imp_lcs.menu_id=3
        then sum(po_yarns.amount)
        when 
        imp_lcs.menu_id=4
        then sum(po_knit_services.amount)
        when 
        imp_lcs.menu_id=5
        then sum(po_aop_services.amount)
        when 
        imp_lcs.menu_id=6
        then sum(po_dyeing_services.amount)
        when 
        imp_lcs.menu_id=7
        then sum(po_dye_chems.amount)
        when 
        imp_lcs.menu_id=8
        then sum(po_generals.amount)
        when 
        imp_lcs.menu_id=9
        then sum(po_yarn_dyeings.amount)
        when 
        imp_lcs.menu_id=10
        then sum(po_emb_services.amount)
        when 
        imp_lcs.menu_id=11
        then sum(po_general_services.amount)
        else 0
        end as lc_amount
        from imp_lcs 
        join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
        left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
        left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
        left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
        left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
        left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
        left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
        left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
        left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
        left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
        left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
        left join po_general_services on po_general_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=11
        left join bank_branches on bank_branches.id=imp_lcs.issuing_bank_branch_id
        left join bank_accounts on bank_accounts.bank_branch_id=bank_branches.id and bank_accounts.company_id=imp_lcs.company_id
        left join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
        where 
        imp_lcs.id =".$implcid."
        group by 
        imp_lcs.id,
        imp_lcs.menu_id
        "))
        ->first();
        $loan_date=$implc->lc_date?$implc->lc_date:$implc->lc_application_date;
        $due_date=date("Y-m-d",strtotime($loan_date."+ 180 days"));

        $acctermloan=$this->acctermloan->update($implc->acc_term_loan_id,[
        'loan_ref_no'=>$implc->id,
        'loan_date'=>$loan_date,
        'amount'=>$implcamount->lc_amount,
        'grace_period'=>0,
        'rate'=>0,
        'installment_amount'=>$implcamount->lc_amount,
        'no_of_installment'=>1,
        'term_loan_for'=>2,
        'bank_account_id'=>$implc->bank_account_id,
        'remarks'=>NULL,
        ]);
        $this->acctermloaninstallment->where([['acc_term_loan_id','=',$implc->acc_term_loan_id]])->update([
        'acc_term_loan_id'=>$implc->acc_term_loan_id,
        'amount'=>$implcamount->lc_amount,
        'sort_id'=>1,
        'due_date'=>$due_date,
        ]); 
      }
    }

    public function importpo ()
    {
       $implc=$this->implc->find(request('implcid',0));
       $menu_id=$implc->menu_id; 
       
       //Fabric Purchase Order
       if($menu_id==1){
          $purchaseorder =$this->pofabric
          ->selectRaw('
            po_fabrics.id,
            po_fabrics.po_no,
            po_fabrics.currency_id,
            po_fabrics.company_id,
            po_fabrics.amount,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            imp_lc_po.purchase_order_id
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
          ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_fabrics.id", "=", "imp_lc_po.purchase_order_id")
          // ->when(request('po_no'), function ($q) {
          //       return $q->where('po_fabrics.po_no', '=', request('po_no', 0));
          // })
          // ->where([['po_fabrics.company_id','=',$implc->company_id]])
          ->whereNotNull('po_fabrics.approved_at')
          ->where([['po_fabrics.currency_id','=',$implc->currency_id]])
          ->where([['po_fabrics.supplier_id','=',$implc->supplier_id]])
          ->get();
          $notsaved = $purchaseorder->filter(function ($value) {
              if(!$value->purchase_order_id){
                  return $value;
              }
          })->values();
        echo json_encode($notsaved);
       }
       //Trims Purchase Order
       if($menu_id==2){
          $purchaseorder =$this->potrim
            ->selectRaw('
              po_trims.id ,
              po_trims.po_no,
              po_trims.itemcategory_id,
              po_trims.currency_id,
              po_trims.company_id,
              po_trims.amount,
              companies.code as company_name,
              currencies.code as currency_name,
              suppliers.code as supplier_name,
              itemcategories.name as itemcategory,
              imp_lc_po.purchase_order_id
            ')
            /*->leftJoin('imp_lc_pos',function($join){
                $join->on('imp_lc_pos.purchase_order_id','=','po_trims.id');
              })*/
            ->leftJoin('companies',function($join){
                $join->on('companies.id','=','po_trims.company_id');
              })
            ->leftJoin('suppliers',function($join){
                $join->on('suppliers.id','=','po_trims.supplier_id');
              })
            ->leftJoin('currencies',function($join){
                $join->on('currencies.id','=','po_trims.currency_id');
              })
            ->leftJoin('itemcategories',function($join){
                $join->on('itemcategories.id','=','po_trims.itemcategory_id');
              })
            ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_trims.id", "=", "imp_lc_po.purchase_order_id")
            // ->when(request('po_no'), function ($q) {
            //       return $q->where('po_trims.po_no', '=', request('po_no', 0));
            // })
            //->where([['po_trims.company_id','=',$implc->company_id]])
            ->whereNotNull('po_trims.approved_at')
            ->where([['po_trims.currency_id','=',$implc->currency_id]])
            ->where([['po_trims.supplier_id','=',$implc->supplier_id]])
            ->get();
          $notsaved = $purchaseorder->filter(function ($value) {
                if(!$value->purchase_order_id){
                    return $value;
                }
            })->values();
          echo json_encode($notsaved);
       }
      //Yarn Purchase Order 
       if($menu_id==3){
          $purchaseorder =$this->poyarn
            ->selectRaw('
              po_yarns.id,
              po_yarns.po_no,
              po_yarns.itemcategory_id,
              po_yarns.currency_id,
              po_yarns.company_id,
              po_yarns.amount,
              companies.code as company_name,
              currencies.code as currency_name,
              suppliers.code as supplier_name,
              itemcategories.name as itemcategory,
              imp_lc_po.purchase_order_id
              ')
            /*->leftJoin('imp_lc_pos',function($join){
                $join->on('imp_lc_pos.purchase_order_id','=','po_yarns.id');
              })*/
            ->leftJoin('companies',function($join){
                $join->on('companies.id','=','po_yarns.company_id');
              })
            ->leftJoin('suppliers',function($join){
                $join->on('suppliers.id','=','po_yarns.supplier_id');
              })
            ->leftJoin('currencies',function($join){
                $join->on('currencies.id','=','po_yarns.currency_id');
              })
            ->leftJoin('itemcategories',function($join){
                $join->on('itemcategories.id','=','po_yarns.itemcategory_id');
              })
            ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_yarns.id", "=", "imp_lc_po.purchase_order_id")
            // ->when(request('po_no'), function ($q) {
            //       return $q->where('po_yarns.po_no', '=', request('po_no', 0));
            // })
            //->where([['po_yarns.company_id','=',$implc->company_id]])
            ->whereNotNull('po_yarns.approved_at')
            ->where([['po_yarns.currency_id','=',$implc->currency_id]])
            ->where([['po_yarns.supplier_id','=',$implc->supplier_id]])
            ->get();
          $notsaved = $purchaseorder->filter(function ($value) {
                if(!$value->purchase_order_id){
                    return $value;
                }
            })->values();
          echo json_encode($notsaved);
       }
      //Knit Purchase Order 
       if($menu_id==4){
          $purchaseorder =$this->poknitservice
            ->selectRaw('
              po_knit_services.id,
              po_knit_services.po_no,
              po_knit_services.itemcategory_id,
              po_knit_services.currency_id,
              po_knit_services.company_id,
              po_knit_services.amount,
              companies.code as company_name,
              currencies.code as currency_name,
              suppliers.code as supplier_name,
              itemcategories.name as itemcategory,
              imp_lc_po.purchase_order_id
              ')
            /*->leftJoin('imp_lc_pos',function($join){
                $join->on('imp_lc_pos.purchase_order_id','=','po_knit_services.id');
              })*/
            ->leftJoin('companies',function($join){
                $join->on('companies.id','=','po_knit_services.company_id');
              })
            ->leftJoin('suppliers',function($join){
                $join->on('suppliers.id','=','po_knit_services.supplier_id');
              })
            ->leftJoin('currencies',function($join){
                $join->on('currencies.id','=','po_knit_services.currency_id');
              })
            ->leftJoin('itemcategories',function($join){
                $join->on('itemcategories.id','=','po_knit_services.itemcategory_id');
              })
            ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_knit_services.id", "=", "imp_lc_po.purchase_order_id")
            // ->when(request('po_no'), function ($q) {
            //       return $q->where('po_knit_services.po_no', '=', request('po_no', 0));
            //   })
            //->where([['po_knit_services.company_id','=',$implc->company_id]])
            ->whereNotNull('po_knit_services.approved_at')
            ->where([['po_knit_services.currency_id','=',$implc->currency_id]])
            ->where([['po_knit_services.supplier_id','=',$implc->supplier_id]])
            ->get();
            $notsaved = $purchaseorder->filter(function ($value) {
                if(!$value->purchase_order_id){
                    return $value;
                }
            })->values();
          echo json_encode($notsaved);
       }
       //AOP Service Order
       if($menu_id==5){
          $purchaseorder =$this->poaopservice
          ->selectRaw('
            po_aop_services.id,
            po_aop_services.po_no,
            po_aop_services.itemcategory_id,
            po_aop_services.currency_id,
            po_aop_services.company_id,
            po_aop_services.amount,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            itemcategories.name as itemcategory,
            imp_lc_po.purchase_order_id
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
          ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','po_aop_services.itemcategory_id');
          })
          ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_aop_services.id", "=", "imp_lc_po.purchase_order_id")
          // ->when(request('po_no'), function ($q) {
          //     return $q->where('po_aop_services.po_no', '=', request('po_no', 0));
          // })
          // ->where([['po_aop_services.company_id','=',$implc->company_id]])
          ->whereNotNull('po_aop_services.approved_at')
          ->where([['po_aop_services.currency_id','=',$implc->currency_id]])
          ->where([['po_aop_services.supplier_id','=',$implc->supplier_id]])
          ->get();
          $notsaved = $purchaseorder->filter(function ($value) {
            if(!$value->purchase_order_id){
                return $value;
            }
          })->values();
          echo json_encode($notsaved);
       }
       //Dyeing Service Work Order
       if($menu_id==6){
          $purchaseorder =$this->podyeingservice
            ->selectRaw('
              po_dyeing_services.id,
              po_dyeing_services.po_no,
              po_dyeing_services.itemcategory_id,
              po_dyeing_services.currency_id,
              po_dyeing_services.company_id,
              po_dyeing_services.amount,
              companies.code as company_name,
              currencies.code as currency_name,
              suppliers.code as supplier_name,
              itemcategories.name as itemcategory,
              imp_lc_po.purchase_order_id
              ')
            /*->leftJoin('imp_lc_pos',function($join){
                $join->on('imp_lc_pos.purchase_order_id','=','po_dyeing_services.id');
              })*/
            ->leftJoin('companies',function($join){
                $join->on('companies.id','=','po_dyeing_services.company_id');
              })
            ->leftJoin('suppliers',function($join){
                $join->on('suppliers.id','=','po_dyeing_services.supplier_id');
              })
            ->leftJoin('currencies',function($join){
                $join->on('currencies.id','=','po_dyeing_services.currency_id');
              })
            ->leftJoin('itemcategories',function($join){
                $join->on('itemcategories.id','=','po_dyeing_services.itemcategory_id');
              })
            ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_dyeing_services.id", "=", "imp_lc_po.purchase_order_id")
            // ->when(request('po_no'), function ($q) {
            //       return $q->where('po_dyeing_services.po_no', '=', request('po_no', 0));
            // })
           // ->where([['po_dyeing_services.company_id','=',$implc->company_id]])
            ->whereNotNull('po_dyeing_services.approved_at')
            ->where([['po_dyeing_services.currency_id','=',$implc->currency_id]])
            ->where([['po_dyeing_services.supplier_id','=',$implc->supplier_id]])
            ->get();
          $notsaved = $purchaseorder->filter(function ($value) {
                if(!$value->purchase_order_id){
                    return $value;
                }
            })->values();
          echo json_encode($notsaved);
       }
      //Dye & Chem Purchase Order 
       if($menu_id==7){
          $purchaseorder =$this->podyechem
            ->selectRaw('
              po_dye_chems.id,
              po_dye_chems.po_no,
              po_dye_chems.itemcategory_id,
              po_dye_chems.currency_id,
              po_dye_chems.company_id,
              po_dye_chems.amount,
              companies.code as company_name,
              currencies.code as currency_name,
              suppliers.code as supplier_name,
              itemcategories.name as itemcategory,
              imp_lc_po.purchase_order_id
              ')
            /*->leftJoin('imp_lc_pos',function($join){
                $join->on('imp_lc_pos.purchase_order_id','=','po_dye_chems.id');
              })*/
            ->leftJoin('companies',function($join){
                $join->on('companies.id','=','po_dye_chems.company_id');
              })
            ->leftJoin('suppliers',function($join){
                $join->on('suppliers.id','=','po_dye_chems.supplier_id');
              })
            ->leftJoin('currencies',function($join){
                $join->on('currencies.id','=','po_dye_chems.currency_id');
              })
            ->leftJoin('itemcategories',function($join){
                $join->on('itemcategories.id','=','po_dye_chems.itemcategory_id');
              })
            ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_dye_chems.id", "=", "imp_lc_po.purchase_order_id")
            // ->when(request('po_no'), function ($q) {
            //       return $q->where('po_dye_chems.po_no', '=', request('po_no', 0));
            //   })
           // ->where([['po_dye_chems.company_id','=',$implc->company_id]])
            ->whereNotNull('po_dye_chems.approved_at')
            ->where([['po_dye_chems.currency_id','=',$implc->currency_id]])
            ->where([['po_dye_chems.supplier_id','=',$implc->supplier_id]])
            ->get();
          $notsaved = $purchaseorder->filter(function ($value) {
                if(!$value->purchase_order_id){
                    return $value;
                }
            })->values();
          echo json_encode($notsaved);
       }
       //General Item Purchase Worder
       if($menu_id==8){
          $purchaseorder =$this->pogeneral
            ->selectRaw('
              po_generals.id,
              po_generals.po_no,
              po_generals.itemcategory_id,
              po_generals.currency_id,
              po_generals.company_id,
              po_generals.amount,
              companies.code as company_name,
              currencies.code as currency_name,
              suppliers.code as supplier_name,
              itemcategories.name as itemcategory,
              imp_lc_po.purchase_order_id
              ')
            /*->leftJoin('imp_lc_pos',function($join){
                $join->on('imp_lc_pos.purchase_order_id','=','po_generals.id');
              })*/
            ->leftJoin('companies',function($join){
              $join->on('companies.id','=','po_generals.company_id');
            })
            ->leftJoin('suppliers',function($join){
              $join->on('suppliers.id','=','po_generals.supplier_id');
            })
            ->leftJoin('currencies',function($join){
              $join->on('currencies.id','=','po_generals.currency_id');
            })
            ->leftJoin('itemcategories',function($join){
              $join->on('itemcategories.id','=','po_generals.itemcategory_id');
            })
            ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_generals.id", "=", "imp_lc_po.purchase_order_id")
            // ->when(request('po_no'), function ($q) {
            //     return $q->where('po_generals.po_no', '=', request('po_no', 0));
            // })
           // ->where([['po_generals.company_id','=',$implc->company_id]])
            ->whereNotNull('po_generals.approved_at')
            ->where([['po_generals.currency_id','=',$implc->currency_id]])
            ->where([['po_generals.supplier_id','=',$implc->supplier_id]])
            ->get();
            $notsaved = $purchaseorder->filter(function ($value) {
              if(!$value->purchase_order_id){
                    return $value;
              }
            })->values();
          echo json_encode($notsaved);
       }
       //Yarn Dyeing Purchase Order 
       if($menu_id==9){
        $purchaseorder =$this->poyarndyeing
          ->selectRaw('
            po_yarn_dyeings.id,
            po_yarn_dyeings.po_no,
            po_yarn_dyeings.itemcategory_id,
            po_yarn_dyeings.currency_id,
            po_yarn_dyeings.company_id,
            po_yarn_dyeings.amount,
            companies.code as company_name,
            currencies.code as currency_name,
            suppliers.code as supplier_name,
            itemcategories.name as itemcategory,
            imp_lc_po.purchase_order_id
            ')
          /*->leftJoin('imp_lc_pos',function($join){
              $join->on('imp_lc_pos.purchase_order_id','=','po_yarn_dyeings.id');
            })*/
          ->leftJoin('companies',function($join){
            $join->on('companies.id','=','po_yarn_dyeings.company_id');
          })
          ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','po_yarn_dyeings.supplier_id');
          })
          ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_yarn_dyeings.currency_id');
          })
          ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','po_yarn_dyeings.itemcategory_id');
          })
          ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs
           join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_yarn_dyeings.id", "=", "imp_lc_po.purchase_order_id")
          // ->when(request('po_no'), function ($q) {
          //       return $q->where('po_yarn_dyeings.po_no', '=', request('po_no', 0));
          //   })
          //->where([['po_yarn_dyeings.company_id','=',$implc->company_id]])
          ->whereNotNull('po_yarn_dyeings.approved_by')
          ->where([['po_yarn_dyeings.currency_id','=',$implc->currency_id]])
          ->where([['po_yarn_dyeings.supplier_id','=',$implc->supplier_id]])
          ->get();
        $notsaved = $purchaseorder->filter(function ($value) {
              if(!$value->purchase_order_id){
                  return $value;
              }
          })->values();
        echo json_encode($notsaved);
      }
      //Embelishment Work Order
      if($menu_id==10){
        $purchaseorder =$this->poembservice
        ->selectRaw('
          po_emb_services.id,
          po_emb_services.po_no,
          po_emb_services.itemcategory_id,
          po_emb_services.currency_id,
          po_emb_services.company_id,
          po_emb_services.amount,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          itemcategories.name as itemcategory,
          imp_lc_po.purchase_order_id
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
          ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','po_emb_services.itemcategory_id');
          })
          ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_emb_services.id", "=", "imp_lc_po.purchase_order_id")
          ->whereNotNull('po_emb_services.approved_at')
          ->where([['po_emb_services.currency_id','=',$implc->currency_id]])
          ->where([['po_emb_services.supplier_id','=',$implc->supplier_id]])
          ->get();
          $notsaved = $purchaseorder->filter(function ($value) {
            if(!$value->purchase_order_id){
                return $value;
            }
          })->values();
        echo json_encode($notsaved);
      }
      //General Service Work Order
      if ($menu_id==11) {
        $purchaseorder =$this->pogeneralservice
        ->selectRaw('
          po_general_services.id,
          po_general_services.po_no,
          po_general_services.currency_id,
          po_general_services.company_id,
          po_general_services.amount,
          companies.code as company_name,
          currencies.code as currency_name,
          suppliers.code as supplier_name,
          imp_lc_po.purchase_order_id
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
          ->leftJoin(\DB::raw("(select imp_lc_pos.purchase_order_id from  imp_lcs join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_lcs.menu_id=".$menu_id.") imp_lc_po"), "po_general_services.id", "=", "imp_lc_po.purchase_order_id")
          ->whereNotNull('po_general_services.approved_at')
          ->where([['po_general_services.currency_id','=',$implc->currency_id]])
          ->where([['po_general_services.supplier_id','=',$implc->supplier_id]])
          ->get();
          $notsaved = $purchaseorder->filter(function ($value) {
            if(!$value->purchase_order_id){
                return $value;
            }
          })->values();
        echo json_encode($notsaved);
      }
  }

}
