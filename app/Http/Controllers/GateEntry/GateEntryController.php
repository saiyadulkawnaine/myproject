<?php

namespace App\Http\Controllers\GateEntry;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
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
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Contracts\Purchase\PoGeneralServiceRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;

use App\Repositories\Contracts\GateEntry\GateEntryRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\GateEntry\GateEntryItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;

use App\Library\Sms;
use App\Library\Template;
use App\Http\Requests\GateEntry\GateEntryRequest;

class GateEntryController extends Controller {
    private $gateentry;
    private $salesordergmtcolorsize;
    private $company;
    private $gateentryitem;
    private $purchaseorder;
    private $pofabric;
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
    private $poyarndyeing;
    private $invyarnitem;
    private $poembservice;
    private $pogeneralservice;


    public function __construct(
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
        PoYarnRepository $poyarn,
        ImpLcRepository $implc,
        ItemAccountRepository $itemaccount,
        InvPurReqRepository $invpurreq,
        BudgetFabricRepository $budgetfabric,
        PoAopServiceRepository $poaopservice,
        PoEmbServiceRepository $poembservice,
        PoYarnDyeingRepository $poyarndyeing,
        PoGeneralServiceRepository $pogeneralservice,
        InvYarnItemRepository $invyarnitem, 
      UserRepository $user

    ) {
        $this->gateentry  = $gateentry;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->company = $company;
        $this->gateentryitem = $gateentryitem;
        $this->pofabric = $pofabric;
        $this->poyarn = $poyarn;
        $this->potrim = $potrim;
        $this->podyechem = $podyechem;
        $this->podyeingservice = $podyeingservice;
        $this->pogeneral = $pogeneral;
        $this->poknitservice = $poknitservice;
        $this->pogeneralservice = $pogeneralservice;
        $this->implc = $implc;
        $this->itemaccount = $itemaccount;
        $this->invyarnitem = $invyarnitem;
        $this->invpurreq = $invpurreq;
        $this->budgetfabric = $budgetfabric;
        $this->poaopservice = $poaopservice;
        $this->poembservice = $poembservice;
        $this->poyarndyeing = $poyarndyeing;
        $this->user = $user;

      $this->middleware('auth');
      //$this->middleware('permission:view.gateentries',   ['only' => ['create', 'index','show']]);
     // $this->middleware('permission:create.gateentries', ['only' => ['store']]);
      //$this->middleware('permission:edit.gateentries',   ['only' => ['update']]);
      //$this->middleware('permission:delete.gateentries', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $menu_id=request('menu_id', 0);
      //Fabric Purchase Order
      if($menu_id==1){
        $purchaseorder = $this->gateentry
            ->selectRaw('
              gate_entries.id,
              gate_entries.barcode_no_id,
              gate_entries.challan_no,
              po_fabrics.po_no as fabric_po_no,
              po_fabrics.company_id,
              po_fabrics.supplier_id,
              po_fabrics.remarks as po_fabric_remarks,
              companies.code as fabric_company,
              suppliers.name as fabric_supplier_name  
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
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->orderBy('gate_entries.id','desc')
            //->limit(10)
            ->get()
            ->map(function($purchaseorder){
              $purchaseorder->po_pr_no=$purchaseorder->fabric_po_no;
              $purchaseorder->company_name=$purchaseorder->fabric_company;
              $purchaseorder->supplier_name=$purchaseorder->fabric_supplier_name;
              $purchaseorder->master_remarks=$purchaseorder->po_fabric_remarks;
              return $purchaseorder;
          });

          echo json_encode($purchaseorder);
      }
      //Trims Purchase Order
      if($menu_id==2){
          $purchaseorder =$this->gateentry
          ->selectRaw('
              gate_entries.id,
              gate_entries.barcode_no_id,
              gate_entries.challan_no,
              po_trims.po_no as trim_po_no,
              po_trims.supplier_id,
              po_trims.company_id,
              po_trims.remarks as po_trim_remarks,
              suppliers.name as trim_supplier_name,
              companies.code as trim_company
              ')
            ->join('po_trims',function($join){
              $join->on('po_trims.id','=','gate_entries.barcode_no_id');
            })
            ->join('companies',function($join){
              $join->on('companies.id','=','po_trims.company_id');
            })
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','po_trims.supplier_id');
            })
            ->leftJoin('currencies',function($join){
              $join->on('currencies.id','=','po_trims.currency_id');
            })
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->orderBy('gate_entries.id','desc')
            //->limit(10)
            ->get()
            ->map(function($purchaseorder){
              $purchaseorder->po_pr_no=$purchaseorder->trim_po_no;
              $purchaseorder->supplier_name=$purchaseorder->trim_supplier_name;
              $purchaseorder->company_name=$purchaseorder->trim_company;
              $purchaseorder->master_remarks=$purchaseorder->po_trim_remarks;
            return $purchaseorder;
            }); 
          echo json_encode($purchaseorder);
      }
      //Yarn Purchase Order 
      if($menu_id==3){
          $purchaseorder =$this->gateentry
          ->selectRaw('
              gate_entries.id,
              gate_entries.barcode_no_id,
              gate_entries.challan_no,
              po_yarns.po_no as yarn_po_no,
              po_yarns.company_id,
              po_yarns.supplier_id,
              po_yarns.remarks as po_yarn_remarks,
              companies.code as yarn_company,
              suppliers.name as yarn_supplier_name
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
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->orderBy('gate_entries.id','desc')
            //->limit(10)
            ->get()
            ->map(function($purchaseorder){
              $purchaseorder->po_pr_no=$purchaseorder->yarn_po_no;
              $purchaseorder->company_name=$purchaseorder->yarn_company;
              $purchaseorder->supplier_name=$purchaseorder->yarn_supplier_name;
              $purchaseorder->master_remarks=$purchaseorder->po_yarn_remarks;
            return $purchaseorder;
            });
          echo json_encode($purchaseorder);
      }
      //Knit Purchase Order 
      if($menu_id==4){
        $purchaseorder=$this->gateentry
          ->selectRaw('
            gate_entries.id,
            gate_entries.barcode_no_id,
            gate_entries.challan_no,
            po_knit_services.po_no as knit_service_po_no,
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
          ->where([['gate_entries.menu_id','=',$menu_id]])
          ->orderBy('gate_entries.id','desc')
          ->get()
          ->map(function($purchaseorder){
            $purchaseorder->po_pr_no=$purchaseorder->knit_service_po_no;
            $purchaseorder->supplier_name=$purchaseorder->knit_service_supplier_name;
            $purchaseorder->company_name=$purchaseorder->knit_company_name;
            $purchaseorder->master_remarks=$purchaseorder->po_knit_remarks;
            return $purchaseorder;
          });   
        echo json_encode($purchaseorder);
      }
      //AOP Service Order
      if($menu_id==5){
          $purchaseorder=$this->gateentry
          ->selectRaw('
              gate_entries.id,
              gate_entries.barcode_no_id,
              gate_entries.challan_no,
              po_aop_services.po_no as aop_service_po_no,
              po_aop_services.company_id,
              po_aop_services.supplier_id,
              po_aop_services.remarks as po_aop_remarks,
              companies.code as aop_company,
              suppliers.name as aop_service_supplier_name
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
          ->where([['gate_entries.menu_id','=',$menu_id]])
          ->orderBy('gate_entries.id','desc')
          //->limit(10)
          ->get()
          ->map(function($purchaseorder){
            $purchaseorder->po_pr_no=$purchaseorder->aop_service_po_no;
            $purchaseorder->company_name=$purchaseorder->aop_company;
            $purchaseorder->supplier_name=$purchaseorder->aop_service_supplier_name;
            $purchaseorder->master_remarks=$purchaseorder->po_aop_remarks;
            return $purchaseorder;
          });
          echo json_encode($purchaseorder);
      }
      //Dyeing Service Work Order
      if($menu_id==6){    
          $purchaseorder = $this->gateentry
            ->selectRaw('
              gate_entries.id,
              gate_entries.barcode_no_id,
              gate_entries.challan_no,
              po_dyeing_services.po_no as dyeing_service_po_no,
              po_dyeing_services.company_id,
              po_dyeing_services.supplier_id,
              po_dyeing_services.remarks as po_dyeing_service_remarks,
              companies.code as dyeing_company,
              suppliers.name as dyeing_service_supplier_name  
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
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->orderBy('gate_entries.id','desc')
            //->limit(10)
            ->get()
            ->map(function($purchaseorder){
              $purchaseorder->po_pr_no=$purchaseorder->dyeing_service_po_no;
              $purchaseorder->company_name=$purchaseorder->dyeing_company;
              $purchaseorder->supplier_name=$purchaseorder->dyeing_service_supplier_name;
              $purchaseorder->master_remarks=$purchaseorder->po_dyeing_service_remarks;
              return $purchaseorder;
          });

          echo json_encode($purchaseorder);
      }
      //Dye & Chem Purchase Order 
      if($menu_id==7){
          $purchaseorder=$this->gateentry
            ->join('po_dye_chems',function($join){
              $join->on('po_dye_chems.id','=','gate_entries.barcode_no_id');
            })
            ->join('companies',function($join){
              $join->on('companies.id','=','po_dye_chems.company_id');
            })
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','po_dye_chems.supplier_id');
            })
            ->where([['gate_entries.menu_id','=',$menu_id]])
            ->orderBy('gate_entries.id','desc')
            //->limit(10)
            ->get([
              'gate_entries.id',
              'gate_entries.barcode_no_id',
              'gate_entries.challan_no',
              'po_dye_chems.po_no as dye_chem_po_no',
              'po_dye_chems.company_id',
              'po_dye_chems.supplier_id',
              'po_dye_chems.remarks as po_dye_chem_remarks',
              'companies.code as dye_chem_company',
              'suppliers.name as dyechem_supplier_name'
            ])
            ->map(function($purchaseorder){
              $purchaseorder->po_pr_no=$purchaseorder->dye_chem_po_no;
              $purchaseorder->supplier_name=$purchaseorder->dyechem_supplier_name;
              $purchaseorder->company_name=$purchaseorder->dye_chem_company;                
              $purchaseorder->master_remarks=$purchaseorder->po_dye_chem_remarks;
              return $purchaseorder;
            });
          echo json_encode($purchaseorder);
      }
      //General Item Purchase Worder
      if($menu_id==8){
        $purchaseorder =$this->gateentry
          ->selectRaw('
            gate_entries.id,
            gate_entries.barcode_no_id,
            gate_entries.challan_no,
            po_generals.po_no as general_po_no,
            po_generals.company_id,
            po_generals.supplier_id,
            po_generals.remarks as po_general_remarks,
            companies.code as po_general_company,
            suppliers.name as general_supplier_name
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
          ->where([['gate_entries.menu_id','=',$menu_id]])
          ->orderBy('gate_entries.id','desc')
          //->limit(10)
          ->get()
          ->map(function($purchaseorder){
            $purchaseorder->po_pr_no=$purchaseorder->general_po_no;
            $purchaseorder->company_name=$purchaseorder->po_general_company;
            $purchaseorder->supplier_name=$purchaseorder->general_supplier_name;
            $purchaseorder->master_remarks=$purchaseorder->po_general_remarks;
            return $purchaseorder;
          });
        echo json_encode($purchaseorder);
      }
      //Yarn Dyeing Work Order
      if($menu_id==9){
        $data=$this->gateentry
          ->selectRaw('
            gate_entries.id,
            gate_entries.barcode_no_id,
            gate_entries.challan_no,
            po_yarn_dyeings.po_no as yarn_dyeing_po_no,
            po_yarn_dyeings.company_id,
            po_yarn_dyeings.supplier_id,
            po_yarn_dyeings.remarks as po_yarn_dyeing_remarks,
            companies.code as yarn_dyeing_company,
            suppliers.name as yarn_dyeing_supplier_name
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
          ->where([['gate_entries.menu_id','=',$menu_id]])
          ->orderBy('gate_entries.id','desc')
          //->limit(10)
          ->groupBy([
            'gate_entries.id',
            'gate_entries.barcode_no_id',
            'gate_entries.challan_no',
            'po_yarn_dyeings.po_no',
            'po_yarn_dyeings.company_id',
            'po_yarn_dyeings.supplier_id',
            'po_yarn_dyeings.remarks',
            'companies.code',
            'suppliers.name'
          ])
          ->get()
          ->map(function ($data) {
              $data->po_pr_no=$data->yarn_dyeing_po_no;
              $data->company_name=$data->yarn_dyeing_company;
              $data->supplier_name=$data->yarn_dyeing_supplier_name;
              $data->master_remarks=$data->po_yarn_dyeing_remarks;
              return $data;
          });  
        echo json_encode($data);
      }
      //Embelishment Service Order
      if($menu_id==10){
          $purchaseorder=$this->gateentry
          ->selectRaw('
              gate_entries.id,
              gate_entries.barcode_no_id,
              gate_entries.challan_no,
              po_emb_services.po_no as emb_service_po_no,
              po_emb_services.company_id,
              po_emb_services.supplier_id,
              po_emb_services.remarks as po_emb_remarks,
              companies.code as emb_company,
              suppliers.name as emb_service_supplier_name
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
          ->where([['gate_entries.menu_id','=',$menu_id]])
          ->orderBy('gate_entries.id','desc')
          //->limit(10)
          ->get()
          ->map(function($purchaseorder){
            $purchaseorder->po_pr_no=$purchaseorder->emb_service_po_no;
            $purchaseorder->company_name=$purchaseorder->emb_company;
            $purchaseorder->supplier_name=$purchaseorder->emb_service_supplier_name;
            $purchaseorder->master_remarks=$purchaseorder->po_emb_remarks;
            return $purchaseorder;
          });
          echo json_encode($purchaseorder);
      }
      //General Service Work Order
      if($menu_id==11){
        $purchaseorder=$this->gateentry
        ->selectRaw('
            gate_entries.id,
            gate_entries.barcode_no_id,
            gate_entries.challan_no,
            po_general_services.po_no as general_service_po_no,
            po_general_services.company_id,
            po_general_services.supplier_id,
            companies.code as general_company,
            suppliers.name as general_service_supplier,
            po_general_services.remarks as po_general_remarks
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
        ->where([['gate_entries.menu_id','=',$menu_id]])
        ->orderBy('gate_entries.id','desc')
        //->limit(10)
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->po_pr_no=$purchaseorder->general_service_po_no;
          $purchaseorder->company_name=$purchaseorder->general_company;
          $purchaseorder->supplier_name=$purchaseorder->general_service_supplier;
          $purchaseorder->master_remarks=$purchaseorder->po_general_remarks;
          return $purchaseorder;
        });
        echo json_encode($purchaseorder);
      }
      //Inventory Purchase Requisition
      if($menu_id==103){
        $invpurreqitem=$this->gateentry
        ->selectRaw('
          gate_entries.id,
          gate_entries.barcode_no_id,
          gate_entries.challan_no,
          inv_pur_reqs.requisition_no as purchase_req_no,
          inv_pur_reqs.company_id,
          inv_pur_reqs.remarks as req_item_remarks,        
          companies.code as purchase_company
        ')
        ->join('inv_pur_reqs',function($join){
          $join->on('inv_pur_reqs.id','=','gate_entries.barcode_no_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','inv_pur_reqs.company_id');
        })
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->orderBy('gate_entries.id','desc')
        //->limit(10)
        ->groupBy([
          'gate_entries.id',
          'gate_entries.barcode_no_id',
          'gate_entries.challan_no',
          'inv_pur_reqs.remarks',
          'inv_pur_reqs.requisition_no',
          'inv_pur_reqs.currency_id',
          'inv_pur_reqs.company_id',
          'companies.code',
        ])
        ->get()
        ->map(function($invpurreqitem){
          $invpurreqitem->po_pr_no=$invpurreqitem->purchase_req_no;
          $invpurreqitem->company_name=$invpurreqitem->purchase_company;
          $invpurreqitem->master_remarks=$invpurreqitem->req_item_remarks;
        return $invpurreqitem;
        });
        echo json_encode($invpurreqitem);
      }
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $menu=array_prepend(array_only(config('bprs.menu'),[1,2,3,4,5,6,7,8,9,10,11,103]),'-Select-','');
      return Template::loadView('GateEntry.GateEntry', ['menu'=>$menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GateEntryRequest $request) {
      $entry_date=date('Y-m-d');
      $user=array_prepend(array_pluck($this->user->get(),'name','id'),'','');
      $qty=0;
      //$itemArr=[];
      
        $gateentry=$this->gateentry->create([
            'menu_id'=>$request->menu_id,
            'barcode_no_id'=>$request->barcode_no_id,
            'challan_no'=>$request->challan_no,
            'entry_date'=>$entry_date,
            'comments'=>$request->comments
        ]);
        $itemArr=[];
        $itemDescArr=[];
        foreach($request->item_id as $index=>$item_id){
          if($item_id && $request->qty[$index])
          {
            $itemArr[]=$item_id;
            $itemDescArr[]=$request->item_description[$index].' '.$request->qty[$index].' '.$request->uom_code[$index];
              $gateentryitem = $this->gateentryitem->create([
                'gate_entry_id'=>$gateentry->id,
                'item_id'=>$item_id,
                'qty'=>$request->qty[$index],
                'remarks'=>$request->remarks[$index],
              ]);
              $qty += $request->qty[$index];
          }
        }
        $tqty=$qty;
        // if($gateentry){
        //     return response()->json(array('success' => true,'id' => $gateentry->id,'message' => 'Save Successfully'),200);
        // }
        if($gateentry){
          $menu=config('bprs.menu');
          //Trims Purchase Order
          if ($request->menu_id==2) {
            $gatemsg=$this->potrim
            ->selectRaw('
              po_trims.id as po_trim_id,
              po_trims.po_no,
              po_trims.remarks,
              companies.name as company_name,
              buyers.name as buyer_name,
              suppliers.name as supplier_name
            ')
            ->join('companies',function($join){
              $join->on('companies.id','=','po_trims.company_id');
            })
            ->leftJoin('buyers',function($join){
              $join->on('buyers.id','=','po_trims.buyer_id');
            })
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','po_trims.supplier_id');
            })
            ->where([['po_trims.id','=',$request->barcode_no_id]])
            ->get()
            ->first();

            $title ='Accessories Receive Gate Entry';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($entry_date))."\n".
            'Description:'.implode(',',$itemDescArr)."\n".
            'Total Qty:'.$tqty."\n".
            'Supplier:'.$gatemsg->supplier_name."\n".
            'Company:'.$gatemsg->company_name."\n".
            'Buyer:'.$gatemsg->buyer_name."\n".
            'Challan No:'.$request->challan_no."\n".
            'PO No:'.$gatemsg->po_no."\n".
            'Gate Entry By:'.$user[$gateentry->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateentry->created_at));

           // $sms=Sms::send_sms($text, '8801766489228');
           $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801620913828,8801714174033,8801714173598');
          }
          //Yarn Purchase Order
          if ($request->menu_id==3) {
            $gatemsg=$this->poyarn
            ->selectRaw('
              po_yarns.id as po_yarn_id,
              po_yarns.po_no,
              po_yarns.remarks,
              companies.code as company_name,
              suppliers.name as supplier_name,
              importLc.lc_no_i,
              importLc.lc_no_ii,
              importLc.lc_no_iii,
              importLc.lc_no_iv
            ')
            ->join('companies',function($join){
              $join->on('companies.id','=','po_yarns.company_id');
            })
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','po_yarns.supplier_id');
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
              where imp_lcs.menu_id=3
              ) importLc"), "importLc.purchase_order_id", "=", "po_yarns.id")
            ->where([['po_yarns.id','=',$request->barcode_no_id]])
            ->get()
            ->map(function($gatemsg){
              $gatemsg->lc_no=$gatemsg->lc_no_i.''.$gatemsg->lc_no_ii.''.$gatemsg->lc_no_iii.''.$gatemsg->lc_no_iv;
              return $gatemsg;
            })
            ->first();

            $title ='Yarn Receive Gate Entry';
            $text = 
            $title."\n".
             'Date:'.date('d-M-Y',strtotime($entry_date))."\n".
            'Description:'.implode(',',$itemDescArr)."\n".
            'Total Qty:'.$tqty."\n".
            'Supplier:'.$gatemsg->supplier_name."\n".
            'Challan No:'.$request->challan_no."\n".
            'PO No:'.$gatemsg->po_no."\n".
            'LC No:'.$gatemsg->lc_no."\n".
            'Gate Entry By:'.$user[$gateentry->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateentry->created_at));

            $sms=Sms::send_sms($text,'8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801714174033,8801714173598,8801916188046,8801772217255,8801712675485');
          }
          //Knit Service Order
          if ($request->menu_id==4) {
            $gatemsg=$this->poknitservice
            ->selectRaw('
              po_knit_services.id as po_knit_service_id,
              po_knit_services.po_no,
              po_knit_services.remarks,
              companies.code as company_name,
              suppliers.name as supplier_name
            ')
            ->join('companies',function($join){
              $join->on('companies.id','=','po_knit_services.company_id');
            }) 
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','po_knit_services.supplier_id');
            })
            ->where([['po_knit_services.id','=',$request->barcode_no_id]])
            ->get()
            ->first();

            $title ='Grey Receive From Outside Knitting House';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($entry_date))."\n".
            'Description:'.implode(',',$itemDescArr)."\n".
            'Total Qty:'.$tqty."\n".
            'Supplier:'.$gatemsg->supplier_name."\n".
            'Challan No:'.$request->challan_no."\n".
            'PO No:'.$gatemsg->po_no."\n".
            'Gate Entry By:'.$user[$gateentry->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateentry->created_at));

            $sms=Sms::send_sms($text, '8801766489228,8801730595836,8801711563231,8801740928970,8801713241051,8801772681965,8801713043117,8801778579933');
          }
          //Dyes & Chemical Purchase Order
          else if ($request->menu_id==7) {
              $gatemsg =$this->podyechem
              ->selectRaw('
                po_dye_chems.id as po_dye_chem_id,
                po_dye_chems.po_no,
                po_dye_chems.company_id,
                po_dye_chems.supplier_id,
                po_dye_chems.remarks,
                companies.name as company_name,
                suppliers.name as supplier_name
              ')
              ->join('companies',function($join){
                  $join->on('companies.id','=','po_dye_chems.company_id');
              })
              ->join('suppliers',function($join){
                  $join->on('suppliers.id','=','po_dye_chems.supplier_id');
              })
              ->where([['po_dye_chems.id','=',$request->barcode_no_id]])
              ->get()
              ->first();

            $title ='Dyes & Chemical Receive Gate Entry';
            $text = 
            $title."\n".
            'Entry Date:'.date('d-M-Y',strtotime($entry_date))."\n".
            'Description:'.implode(',',$itemDescArr)."\n".
            'Total Qty:'.$tqty."\n".
            'Company:'.$gatemsg->company_name."\n".
            'Supplier:'.$gatemsg->supplier_name."\n".
            'Challan No:'.$request->challan_no."\n".
            'PO No:'.$gatemsg->po_no."\n".
            'Gate Entry By:'.$user[$gateentry->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateentry->created_at));

            $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801620913828,8801713041126,8801714173589,8801824313399,8801704022333');
              
          }
          //General Item Purchase Order
          else if ($request->menu_id==8) {
              $gatemsg =$this->pogeneral
              ->selectRaw('
                po_generals.id as po_general_id,
                po_generals.po_no,
                po_generals.company_id,
                po_generals.supplier_id,
                po_generals.remarks,
                companies.name as company_name,
                suppliers.name as supplier_name
              ')
              ->join('companies',function($join){
                  $join->on('companies.id','=','po_generals.company_id');
              })
              ->join('suppliers',function($join){
                  $join->on('suppliers.id','=','po_generals.supplier_id');
              })
              ->where([['po_generals.id','=',$request->barcode_no_id]])
              ->get()
              ->first();

            $title ='General Item Receive Gate Entry';
            $text = 
            $title."\n".
            'Date:'.date('d-M-Y',strtotime($entry_date))."\n".
            'Description:'.implode(',',$itemDescArr)."\n".
            'Total Qty:'.$tqty."\n".
            'Company:'.$gatemsg->company_name."\n".
            'Supplier:'.$gatemsg->supplier_name."\n".
            'Challan No:'.$request->challan_no."\n".
            'PO No:'.$gatemsg->po_no."\n".
            'Gate Entry By:'.$user[$gateentry->created_by]."\n".
            'Time:'.date('h:i A',strtotime($gateentry->created_at));

            $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801620913828,8801748990411');
              
          }
          //Yarn Dyeing Work Order
          if($request->menu_id==9){
             $gatemsg =$this->poyarndyeing
              ->selectRaw('
                po_yarn_dyeings.id as po_yarn_dyeing_id,
                po_yarn_dyeings.po_no,
                po_yarn_dyeings.company_id,
                po_yarn_dyeings.supplier_id,
                po_yarn_dyeings.remarks,
                companies.name as company_name,
                suppliers.name as supplier_name
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
              ->where([['po_yarn_dyeings.id','=',$request->barcode_no_id]])
              ->get()
              ->first();
              $title ='Dyed Yarn Receive Gate Entry';
              $text = 
              $title."\n".
              'Date:'.date('d-M-Y',strtotime($entry_date))."\n".
              'Description:'.implode(',',$itemDescArr)."\n".
              'Total Qty:'.$tqty."\n".
              'Company:'.$gatemsg->company_name."\n".
              'Supplier:'.$gatemsg->supplier_name."\n".
              'Challan No:'.$request->challan_no."\n".
              'PO No:'.$gatemsg->po_no."\n".
              'Gate Entry By:'.$user[$gateentry->created_by]."\n".
              'Time:'.date('h:i A',strtotime($gateentry->created_at));

              $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801620913828');
          }
          //General Service Work Order
          if($request->menu_id==11){
             $gatemsg =$this->pogeneralservice
              ->selectRaw('
                po_general_services.id as po_general_service_id,
                po_general_services.po_no,
                po_general_services.company_id,
                po_general_services.supplier_id,
                po_general_services.remarks,
                companies.name as company_name,
                suppliers.name as supplier_name
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
              ->where([['po_general_services.id','=',$request->barcode_no_id]])
              ->get()
              ->first();
              $title ='General Service Receive Gate Entry';
              $text = 
              $title."\n".
              'Date:'.date('d-M-Y',strtotime($entry_date))."\n".
              'Description:'.implode(',',$itemDescArr)."\n".
              'Total Qty:'.$tqty."\n".
              'Company:'.$gatemsg->company_name."\n".
              'Supplier:'.$gatemsg->supplier_name."\n".
              'Challan No:'.$request->challan_no."\n".
              'PO No:'.$gatemsg->po_no."\n".
              'Gate Entry By:'.$user[$gateentry->created_by]."\n".
              'Time:'.date('h:i A',strtotime($gateentry->created_at));

              $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801620913828');
          }
          //Purchase Requisition
          elseif($request->menu_id==103){
            $gatemsg=$this->invpurreq
            ->selectRaw('
              inv_pur_reqs.id as inv_pur_req_id,
              inv_pur_reqs.requisition_no,
              inv_pur_reqs.company_id,
              inv_pur_reqs.remarks,
              companies.name as company_name,
              users.name as demand_by_name
            ')
            ->leftJoin('users',function($join){
              $join->on('users.id','=','inv_pur_reqs.demand_by_id');
            })
            ->join('companies',function($join){
               $join->on('companies.id','=','inv_pur_reqs.company_id');
             })
            ->where([['inv_pur_reqs.id','=',$request->barcode_no_id]])
            ->get()
            ->first();

            $title ='Item Receive Gate Entry';
              $text = 
              $title."\n".
              'Date:'.date('d-M-Y',strtotime($entry_date))."\n".
              'Description:'.implode(',',$itemDescArr)."\n".
              'Total Qty:'.$tqty."\n".
              'Company:'.$gatemsg->company_name."\n".
              'Challan No:'.$request->challan_no."\n".
              'PR No:'.$gatemsg->requisition_no."\n".
              'Demand By:'.$gatemsg->demand_by_name."\n".
              'Gate Entry By:'.$user[$gateentry->created_by]."\n".
              'Time:'.date('h:i A',strtotime($gateentry->created_at));
        //common=8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715
            $sms=Sms::send_sms($text, '8801730595836,8801711563231,8801713043117,8801714173989,8801713241051,8801730071795,8801778579933,8801788699715,8801620913828,8801713041126,8801714173589,8801824313399,8801704022333,8801748990411');
          }
          
          return response()->json(array('success' => true,'id' =>  $gateentry->id,'sms' => $sms,'message' => 'Save Successfully'),200);
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
      $gate=$this->gateentry->find($id);
      $menu_id=$gate->menu_id;
      //Fabric Purchse Order
      if($menu_id==1){
        $gateentry=$this->gateentry
        ->join('po_fabrics',function($join){
          $join->on('po_fabrics.id','=','gate_entries.barcode_no_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','po_fabrics.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_fabrics.supplier_id');
        })
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->where([['gate_entries.id','=',$id]])
        ->get([
            'gate_entries.*',
            'po_fabrics.po_no as fabric_po_no',
            'po_fabrics.company_id',
            'po_fabrics.supplier_id',
            'companies.code as fabric_company',
            'suppliers.name as fabric_supplier_name',
            'suppliers.address as fabric_supplier_address' 
        ])
        ->map(function($gateentry){
          $gateentry->po_no=$gateentry->fabric_po_no;
          $gateentry->company_name=$gateentry->fabric_company;
          $gateentry->supplier_name=$gateentry->fabric_supplier_name;
          $gateentry->supplier_contact=$gateentry->fabric_supplier_address;
        return $gateentry;
        })
        ->first();

        $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

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


        $gateentryitem=$this->gateentryitem
        ->leftJoin('po_fabric_items',function($join){
          $join->on('gate_entry_items.item_id','=','po_fabric_items.id')
          ->whereNull('po_fabric_items.deleted_at');
        })
        ->join('budget_fabrics',function($join){
          $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
        })
        /* 
          ->join('po_fabrics',function($join){
            $join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
          })
        */
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
        ->join('buyers', function($join) {
          $join->on('buyers.id', '=', 'styles.buyer_id');
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
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
            'gate_entry_items.*',
            'item_accounts.uom_id',
            'uoms.code as fabric_uom_code',
            'budget_fabrics.style_fabrication_id',
            'styles.style_ref',
            'buyers.name as buyer_name',
            'po_fabric_items.qty as po_qty',
           
        ])
        ->map(function($gateentryitem) use($desDropdown) {
          $gateentryitem->item_description=isset($desDropdown[$gateentryitem->style_fabrication_id])?$desDropdown[$gateentryitem->style_fabrication_id]:'';
          $gateentryitem->uom_code=$gateentryitem->fabric_uom_code;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
      }
      //Trims Purchase Order
      if($menu_id==2){
        $gateentry=$this->gateentry
        ->join('po_trims',function($join){
          $join->on('po_trims.id','=','gate_entries.barcode_no_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','po_trims.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_trims.supplier_id');
        })
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->where([['gate_entries.id','=',$id]])
        ->get([
          'gate_entries.*',
          'po_trims.po_no as trim_po_no',
          'po_trims.supplier_id',
          'po_trims.company_id',
          'suppliers.name as trim_supplier_name',
          'suppliers.address as trim_supplier_address',
          'companies.name as trim_company'
        ])
        ->map(function($gateentry){
          $gateentry->po_no=$gateentry->purchase_req_no;
          //$gateentry->requisition_no=$gateentry->requisition_no;
          $gateentry->company_name=$gateentry->trim_company;
          $gateentry->supplier_name=$gateentry->trim_supplier_name;
          $gateentry->supplier_contact=$gateentry->trim_supplier_address;
        return $gateentry;
        })
        ->first();

        $gateentryitem=$this->gateentryitem
        ->join('po_trim_items',function($join){
          $join->on('po_trim_items.id','=','gate_entry_items.item_id');
        })
        ->join('budget_trims',function($join){
          $join->on('po_trim_items.budget_trim_id','=','budget_trims.id')
        ->whereNull('po_trim_items.deleted_at');
        })
        ->join('budgets',function($join){
          $join->on('budgets.id','=','budget_trims.budget_id');
        })
        ->join('jobs',function($join){
          $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('styles', function($join) {
          $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('buyers', function($join) {
          $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->leftJoin('itemclasses', function($join){
          $join->on('itemclasses.id', '=','budget_trims.itemclass_id');
        })
        ->leftJoin('uoms',function($join){
          $join->on('uoms.id','=','budget_trims.uom_id');
        })
        ->leftJoin('itemcategories', function($join){
          $join->on('itemcategories.id', '=','itemclasses.itemcategory_id');
        })
        /*->leftJoin('item_accounts',function($join){
          $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })*/
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
            'gate_entry_items.*',
            'itemcategories.name as itemcategory',
            //'item_accounts.item_description as trim_itemdesc',
            //'item_accounts.specification',
            //'item_accounts.sub_class_name',
            'budget_trims.uom_id',
            'itemclasses.name as itemclass_name',
            'uoms.name as trim_uom_code',
            'styles.style_ref',
            'buyers.name as buyer_name',
            'po_trim_items.qty as po_qty'
        ])
        ->map(function($gateentryitem){
          $gateentryitem->item_description=$gateentryitem->itemclass_name;
          $gateentryitem->uom_code=$gateentryitem->trim_uom_code;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
      }
      //Yarn Purchase Order
      if($menu_id==3){
        $gateentry=$this->gateentry
        ->join('po_yarns',function($join){
          $join->on('po_yarns.id','=','gate_entries.barcode_no_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','po_yarns.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_yarns.supplier_id');
        })
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->where([['gate_entries.id','=',$id]])
        ->get([
          'gate_entries.*',
          'po_yarns.po_no as yarn_po_no',
          'po_yarns.company_id',
          'companies.name as yarn_company',
          'suppliers.code as yarn_supplier_name',
          'suppliers.address as yarn_supplier_address',
        ])
        ->map(function($gateentry){
          $gateentry->po_no=$gateentry->yarn_po_no;
          //$gateentry->requisition_no=$gateentry->requisition_no;
          $gateentry->company_name=$gateentry->yarn_company;
          $gateentry->supplier_name=$gateentry->yarn_supplier_name;
          $gateentry->supplier_contact=$gateentry->yarn_supplier_address;
        return $gateentry;
        })
        ->first();

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

        $gateentryitem=$this->gateentryitem
        ->join('po_yarn_items',function($join){
          $join->on('po_yarn_items.id','=','gate_entry_items.item_id');
        //->whereNull('po_yarn_items.deleted_at');
        })
        ->leftJoin('item_accounts', function($join){
          $join->on('item_accounts.id', '=', 'po_yarn_items.item_account_id');
        })
        ->leftJoin('uoms',function($join){
          $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
            'gate_entry_items.*',
            //'item_accounts.item_description as yarn_itemdesc',
            'item_accounts.id as item_account_id',
            'item_accounts.uom_id',
            'uoms.code as yarn_uom_code',
            'po_yarn_items.remarks as yarn_item_remarks',
            'po_yarn_items.qty as po_qty',
        ])
        ->map(function($gateentryitem) use($yarnDropdown){
          $gateentryitem->item_description = $yarnDropdown[$gateentryitem->item_account_id];
          $gateentryitem->uom_code=$gateentryitem->yarn_uom_code;
          $gateentryitem->remarks=$gateentryitem->yarn_item_remarks;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
      }
      //Knit Purchase Order 
      if($menu_id==4){
        $gateentry=$this->gateentry
          ->join('po_knit_services',function($join){
            $join->on('po_knit_services.id','=','gate_entries.barcode_no_id');
          })
          ->join('companies',function($join){
            $join->on('companies.id','=','po_knit_services.company_id');
          })
          ->join('suppliers',function($join){
            $join->on('suppliers.id','=','po_knit_services.supplier_id');
          })
          ->where([['gate_entries.menu_id','=',$menu_id ]])
          ->where([['gate_entries.id','=',$id]])
          ->get([
            'gate_entries.*',
            'po_knit_services.po_no as knit_service_po_no',
            'po_knit_services.company_id',
            'po_knit_services.supplier_id',
            'companies.code as knit_company_name',
            'suppliers.name as knit_service_supplier_name',
            'suppliers.address as knit_service_supplier_address'
          ])
          ->map(function($gateentry){
            $gateentry->po_no=$gateentry->knit_service_po_no;
            //$gateentry->requisition_no=$gateentry->requisition_no;
            $gateentry->company_name=$gateentry->knit_company_name;
            $gateentry->supplier_name=$gateentry->knit_service_supplier_name;
            $gateentry->supplier_contact=$gateentry->yarn_dyeing_supplier_address;
            $gateentry->supplier_address=$gateentry->knit_service_supplier_address;
          return $gateentry;
          })
          ->first();

        $gateentryitem=$this->gateentryitem
        ->join('po_knit_service_items',function($join){
          $join->on('po_knit_service_items.id','=','gate_entry_items.item_id');
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
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
            'gate_entry_items.*',
            'item_accounts.item_description as knit_service_itemdesc',
            'item_accounts.uom_id',
            'uoms.code as knit_uom_code',
        ])
        ->map(function($gateentryitem){
          $gateentryitem->item_description=$gateentryitem->knit_service_itemdesc;
          $gateentryitem->uom_code=$gateentryitem->knit_uom_code;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
      }
      //AOP Service Order
      if($menu_id==5){
        $gateentry=$this->gateentry
        ->join('po_aop_services',function($join){
          $join->on('po_aop_services.id','=','gate_entries.barcode_no_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','po_aop_services.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_aop_services.supplier_id');
        })
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->where([['gate_entries.id','=',$id]])
        ->get([
            'gate_entries.*',
            'po_aop_services.po_no as aop_service_po_no',
            'po_aop_services.company_id',
            'po_aop_services.supplier_id',
            'po_aop_services.remarks as po_aop_remarks',
            'companies.name as aop_company',
            'suppliers.name as aop_service_supplier_name',
            'suppliers.address as aop_service_supplier_address',
        ])
        ->map(function($gateentry){
          $gateentry->po_no=$gateentry->aop_service_po_no;
          $gateentry->company_name=$gateentry->aop_company;
          $gateentry->supplier_name=$gateentry->aop_service_supplier_name;
          $gateentry->supplier_contact=$gateentry->aop_service_supplier_address;
        return $gateentry;
        })
        ->first();

        $fabricDescription=$this->gateentry
            ->join('po_aop_services',function($join){
              $join->on('po_aop_services.id','=','gate_entries.barcode_no_id');
            })
            ->join('po_aop_service_items',function($join){
              $join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id')
            ->whereNull('po_aop_service_items.deleted_at');
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
            ->where([['gate_entries.id','=',$id]])
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

        $gateentryitem=$this->gateentryitem
        ->leftJoin('po_aop_service_items',function($join){
          $join->on('po_aop_service_items.id','=','gate_entry_items.item_id');
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
        ->leftJoin('uoms',function($join){
          $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
            'gate_entry_items.*',
            'item_accounts.id as item_account_id',       
              'item_accounts.specification',
              'item_accounts.sub_class_name',
              'item_accounts.uom_id',    
            //'item_accounts.item_description as aop_service_itemdesc',
              'budget_fabrics.style_fabrication_id',
              'uoms.code as aop_uom_code',
        ])
        ->map(function($gateentryitem) use($desDropdown) {
          $gateentryitem->item_description=$desDropdown[$gateentryitem->style_fabrication_id];
          //$gateentryitem->item_description=$gateentryitem->aop_service_itemdesc;
          $gateentryitem->uom_code=$gateentryitem->aop_uom_code;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
      }
      //Dyeing Service Work Order
      if($menu_id==6){
        $gateentry=$this->gateentry
        ->join('po_dyeing_services',function($join){
          $join->on('po_dyeing_services.id','=','gate_entries.barcode_no_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','po_dyeing_services.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_dyeing_services.supplier_id');
        })
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->where([['gate_entries.id','=',$id]])
        ->get([
            'gate_entries.*',
            'po_dyeing_services.po_no as dyeing_service_po_no',
            'po_dyeing_services.company_id',
            'po_dyeing_services.supplier_id',
            'companies.code as dyeing_company',
            'suppliers.name as dyservice_supplier_name',
            'suppliers.address as dyservice_supplier_address' 
        ])
        ->map(function($gateentry){
          $gateentry->po_no=$gateentry->dyeing_service_po_no;
          $gateentry->company_name=$gateentry->dyeing_company;
          $gateentry->supplier_name=$gateentry->dyservice_supplier_name;
          $gateentry->supplier_contact=$gateentry->dyservice_supplier_address;
        return $gateentry;
        })
        ->first();

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


        $gateentryitem=$this->gateentryitem
        ->leftJoin('po_dyeing_service_items',function($join){
          $join->on('gate_entry_items.item_id','=','po_dyeing_service_items.id')
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
        ->join('uoms', function($join) {
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
            'gate_entry_items.*',
            'item_accounts.uom_id',
            'uoms.code as dyservice_uom_code',
            'budget_fabrics.style_fabrication_id'
           
        ])
        ->map(function($gateentryitem) use($desDropdown) {
          //$gateentryitem->item_description=$gateentryitem->item_description;
          $gateentryitem->item_description=isset($desDropdown[$gateentryitem->style_fabrication_id])?$desDropdown[$gateentryitem->style_fabrication_id]:'';
          $gateentryitem->uom_code=$gateentryitem->dyservice_uom_code;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
      }
      //Dye & Chem Purchase Order 
      if($menu_id==7){
        $gateentry=$this->gateentry
        ->join('po_dye_chems',function($join){
          $join->on('po_dye_chems.id','=','gate_entries.barcode_no_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','po_dye_chems.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_dye_chems.supplier_id');
        })
        /* 
        ->join('po_dye_chem_items', function($join){
          $join->on('gate_entry_items.item_id', '=', 'po_dye_chem_items.id');
        })
        ->join('inv_pur_req_items', function($join){
          $join->on('inv_pur_req_items.id', '=', 'po_dye_chem_items.inv_pur_req_item_id');
        })
        ->join('inv_pur_reqs', function($join){
          $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
        })
        */
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->where([['gate_entries.id','=',$id]])
        ->get([
            'gate_entries.*',
            'po_dye_chems.po_no as dye_chem_po_no',
            'po_dye_chems.company_id',
            'po_dye_chems.supplier_id',
            'po_dye_chems.remarks as po_dye_chem_remarks',
           // 'inv_pur_reqs.requisition_no as dyechem_req_no',
            'companies.name as dye_chem_company',
            'suppliers.name as dyechem_supplier_name',
            'suppliers.address as dyechem_supplier_address'
        ])
        ->map(function($gateentry){
          $gateentry->po_no=$gateentry->dye_chem_po_no;
          $gateentry->requisition_no=$gateentry->dyechem_req_no;
          $gateentry->company_name=$gateentry->dye_chem_company;
          $gateentry->supplier_name=$gateentry->dyechem_supplier_name;
          $gateentry->supplier_contact=$gateentry->dyechem_supplier_address;
        return $gateentry;
        })
        ->first();

        $gateentryitem=$this->gateentryitem
        ->join('po_dye_chem_items', function($join){
          $join->on('gate_entry_items.item_id', '=', 'po_dye_chem_items.id');
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
        ->leftJoin('uoms', function($join){
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->join('inv_pur_reqs', function($join){
          $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
        })
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
          'gate_entry_items.*',
          'inv_pur_reqs.requisition_no as dyechem_req_no',
          'itemcategories.name as itemcategory',
          'itemclasses.name as itemclass_name',
          'item_accounts.sub_class_name',
          'item_accounts.item_description',
          'item_accounts.specification',
          'item_accounts.uom_id',
          'uoms.code as dye_chem_uom_code',
          'po_dye_chem_items.remarks as dye_chem_item_remarks',
          'po_dye_chem_items.qty as po_qty'
        ])
        ->map(function($gateentryitem){
          $gateentryitem->item_description=$gateentryitem->sub_class_name.", ".$gateentryitem->item_description.", ".$gateentryitem->specification;
          $gateentryitem->remarks=$gateentryitem->dye_chem_item_remarks;
          $gateentryitem->uom_code=$gateentryitem->dye_chem_uom_code;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
      }
      //General Item Purchase Worder
      if($menu_id==8){
        $gateentry=$this->gateentry
        ->selectRaw('
        gate_entries.id,
        gate_entries.barcode_no_id,
        gate_entries.challan_no,
        po_generals.po_no as general_po_no,
        po_generals.company_id,
        po_generals.supplier_id,
        po_generals.remarks as po_general_remarks,
        companies.code as po_general_company,
        suppliers.name as general_supplier_name
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
        ->where([['gate_entries.menu_id','=',$menu_id]])
        ->where([['gate_entries.id','=',$id]])
        ->get([
          'gate_entries.*',
          'po_generals.po_no as general_po_no',
          'inv_pur_reqs.requisition_no as general_req_no',
          'po_generals.company_id',
          'po_generals.supplier_id',
          'companies.code as po_general_company',
          'suppliers.name as general_supplier_name',
          'suppliers.address as general_supplier_address',
        ])
        ->map(function($gateentry){
          $gateentry->po_no=$gateentry->purchase_req_no;
          $gateentry->requisition_no=$gateentry->general_req_no;
          $gateentry->company_name=$gateentry->po_general_company;
          $gateentry->supplier_name=$gateentry->general_supplier_name;
          $gateentry->supplier_contact=$gateentry->general_supplier_address;
        return $gateentry;
        })
        ->first();

        $gateentryitem=$this->gateentryitem
        ->join('po_general_items', function($join){
          $join->on('gate_entry_items.item_id', '=', 'po_general_items.id');
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
        ->leftJoin('uoms', function($join){
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->join('inv_pur_reqs', function($join){
          $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
        })
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
            'gate_entry_items.*',
            'itemcategories.name as itemcategory',
            'itemclasses.name as itemclass_name',
            'item_accounts.sub_class_name',
            'item_accounts.item_description',
            'item_accounts.specification',
            'item_accounts.uom_id',
            'uoms.code as general_uom_code',
            'po_general_items.remarks as general_item_remarks',
            'po_general_items.qty as po_qty',
        ])
        ->map(function($gateentryitem){
          $gateentryitem->item_description=$gateentryitem->sub_class_name.", ".$gateentryitem->item_description.", ".$gateentryitem->specification;
          $gateentryitem->uom_code=$gateentryitem->general_uom_code;
          $gateentryitem->remarks=$gateentryitem->general_item_remarks;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
      }
      //Yarn Dyeing Work Order
      if($menu_id==9){
        $gateentry=$this->gateentry
          ->join('po_yarn_dyeings',function($join){
            $join->on('po_yarn_dyeings.id','=','gate_entries.barcode_no_id');
          })
          ->join('companies',function($join){
            $join->on('companies.id','=','po_yarn_dyeings.company_id');
          })
          ->join('suppliers',function($join){
            $join->on('suppliers.id','=','po_yarn_dyeings.supplier_id');
          })
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->where([['gate_entries.id','=',$id]])
        ->get([
            'gate_entries.*',
            'po_yarn_dyeings.po_no as yarn_dyeing_po_no',
            'po_yarn_dyeings.company_id',
            'po_yarn_dyeings.supplier_id',
            'companies.code as yarn_dyeing_company',
            'suppliers.name as yarn_dyeing_supplier_name'
        ])
        ->map(function($gateentry){
          $gateentry->po_no=$gateentry->yarn_dyeing_po_no;
          //$gateentry->requisition_no=$gateentry->requisition_no;
          $gateentry->company_name=$gateentry->yarn_dyeing_company;
          $gateentry->supplier_name=$gateentry->yarn_dyeing_supplier_name;
          $gateentry->supplier_contact=$gateentry->yarn_dyeing_supplier_address;
        return $gateentry;
        })
        ->first();

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
    

        $gateentryitem=$this->gateentryitem
        ->join('po_yarn_dyeing_items', function($join){
          $join->on('po_yarn_dyeing_items.id', '=' , 'gate_entry_items.item_id');
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
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
          'gate_entry_items.*',
          'item_accounts.uom_id',
          'uoms.code as uom_code',
          'po_yarn_dyeing_items.inv_yarn_item_id',
          'po_yarn_dyeing_items.remarks as yarndye_item_remarks',
        ])
        ->map(function($gateentryitem) use($yarnDropdown) {
          $gateentryitem->item_description = $yarnDropdown[$gateentryitem->inv_yarn_item_id];
          $gateentryitem->remarks=$gateentryitem->yarndye_item_remarks;
          $gateentryitem->uom_code=$gateentryitem->uom_code;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row); 
      }
      //Embelishment Work Order
      if($menu_id==10){
        $gateentry=$this->gateentry
        ->join('po_emb_services',function($join){
          $join->on('po_emb_services.id','=','gate_entries.barcode_no_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','po_emb_services.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_emb_services.supplier_id');
        })
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->where([['gate_entries.id','=',$id]])
        ->get([
            'gate_entries.*',
            'po_emb_services.po_no as emb_service_po_no',
            'po_emb_services.company_id',
            'po_emb_services.supplier_id',
            'companies.code as emb_company',
            'suppliers.name as emb_supplier_name',
            'suppliers.address as emb_supplier_address' 
        ])
        ->map(function($gateentry){
          $gateentry->po_no=$gateentry->emb_service_po_no;
          $gateentry->company_name=$gateentry->emb_company;
          $gateentry->supplier_name=$gateentry->emb_supplier_name;
          $gateentry->supplier_contact=$gateentry->emb_supplier_address;
        return $gateentry;
        })
        ->first();

        $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
        $gateentryitem=$this->gateentryitem
        ->join('po_emb_service_items',function($join){
          $join->on('gate_entry_items.item_id','=','po_emb_service_items.id')
          ->whereNull('po_emb_service_items.deleted_at');
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
        ->leftJoin('uoms', function($join) {
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
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
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
            'gate_entry_items.*',
            'item_accounts.uom_id',
            'uoms.code as emb_uom_code',
            'style_embelishments.embelishment_size_id',
            'embelishments.name as embelishment_name',
            'embelishment_types.name as embelishment_type',
            'gmtsparts.name as gmtspart_name',
           
        ])
        ->map(function($gateentryitem) use($embelishmentsize) {
          $gateentryitem->embelishment_size = $embelishmentsize[$gateentryitem->embelishment_size_id];
          $gateentryitem->item_description=$gateentryitem->item_description.','.$gateentryitem->gmtspart_name.','.$gateentryitem->embelishment_name.','.$gateentryitem->embelishment_size.','.$gateentryitem->embelishment_type;
          $gateentryitem->uom_code=$gateentryitem->emb_uom_code;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
      }
      //General Service Work Order
      if($menu_id==11){
        $gateentry=$this->gateentry
        ->join('po_general_services',function($join){
          $join->on('po_general_services.id','=','gate_entries.barcode_no_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','po_general_services.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_general_services.supplier_id');
        })
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->where([['gate_entries.id','=',$id]])
        ->get([
            'gate_entries.*',
            'po_general_services.po_no as service_po_no',
            'po_general_services.company_id',
            'po_general_services.supplier_id',
            'companies.code as service_company',
            'suppliers.name as service_supplier_name',
            'suppliers.address as service_supplier_address' 
        ])
        ->map(function($gateentry){
          $gateentry->po_no=$gateentry->service_po_no;
          $gateentry->company_name=$gateentry->service_company;
          $gateentry->supplier_name=$gateentry->service_supplier_name;
          $gateentry->supplier_contact=$gateentry->service_supplier_address;
          return $gateentry;
        })
        ->first();

        $gateentryitem=$this->gateentryitem
        ->join('po_general_service_items',function($join){
          $join->on('gate_entry_items.item_id','=','po_general_service_items.id')
          ->whereNull('po_general_service_items.deleted_at');
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
        ->where([['gate_entry_items.gate_entry_id','=',$id]])
        ->get([
            'gate_entry_items.*',
            'po_general_service_items.uom_id',
            'po_general_service_items.service_description',
            'uoms.code as service_uom_code',
        ])
        ->map(function($gateentryitem) {
          $gateentryitem->item_description=$gateentryitem->service_description;
          $gateentryitem->uom_code=$gateentryitem->service_uom_code;
          return $gateentryitem;
        });
        
        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
      }
      //Inventory Purchase Requisition
      if($menu_id==103){
        $gateentry=$this->gateentry
        ->join('inv_pur_reqs',function($join){
          $join->on('inv_pur_reqs.id','=','gate_entries.barcode_no_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','inv_pur_reqs.company_id');
        })
        ->where([['gate_entries.menu_id','=',$menu_id ]])
        ->where([['gate_entries.id','=',$gate->id]])
        ->get([
          'gate_entries.*',
          'inv_pur_reqs.requisition_no as purchase_req_no',
          'inv_pur_reqs.company_id',       
          'companies.code as purchase_company'
        ])
        ->map(function($gateentry){
          //$gateentry->po_no=$gateentry->purchase_req_no;
          $gateentry->requisition_no=$gateentry->purchase_req_no;
          $gateentry->company_name=$gateentry->purchase_company;
         // $gateentry->supplier_name=$gateentry->yarn_dyeing_supplier_name;
        //  $gateentry->supplier_contact=$gateentry->yarn_dyeing_supplier_address;
        return $gateentry;
        })
        ->first();

        $gateentryitem=$this->gateentryitem
        ->join('inv_pur_req_items',function($join){
          $join->on('inv_pur_req_items.id','=','gate_entry_items.item_id');
        })
        ->join('item_accounts',function($join){
          $join->on('item_accounts.id','=','inv_pur_req_items.item_account_id');
        })
        ->leftJoin('itemclasses', function($join){
          $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories', function($join){
          $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms',function($join){
          $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->where([['gate_entry_items.gate_entry_id','=',$gate->id]])
        ->get([
          'gate_entry_items.*',
          'item_accounts.item_description as purchase_item_desc',
          'item_accounts.sub_class_name',
          'item_accounts.specification',
          'uoms.code as purchase_uom_code',
          'inv_pur_req_items.qty as po_qty'
        ])
        ->map(function($gateentryitem){
          $gateentryitem->item_description=$gateentryitem->sub_class_name.", ".$gateentryitem->purchase_item_desc.", ".$gateentryitem->specification;
          $gateentryitem->uom_code=$gateentryitem->purchase_uom_code;
          return $gateentryitem;
        });

        $row ['fromData'] = $gateentry;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['chlddata'] = $gateentryitem;
        echo json_encode($row);
        
      }
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GateEntryRequest $request, $id) {
      $gateentry=$this->gateentry->update($id,[
        'menu_id'=>$request->menu_id,
        'barcode_no_id'=>$request->barcode_no_id,
        'challan_no'=>$request->challan_no,
        'comments'=>$request->comments
      ]);

      $this->gateentryitem->where([['gate_entry_id','=',$id]])->forceDelete();
     
      foreach($request->item_id as $index=>$item_id){
        if($item_id && $request->qty[$index])
        {
            $gateentryitem = $this->gateentryitem->create([
              'gate_entry_id'=>$id,
              'item_id'=>$item_id,
              'qty'=>$request->qty[$index],
              'remarks'=>$request->remarks[$index],
            ]);
        }
      }
        

      if($gateentry){
        return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
      }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

    public function getPurchaseItem()
    {
        $menu_id=request('menu_id', 0);
        $bar_code_no=request('barcode_no_id',0);
        $bar_code_no= (int) $bar_code_no;
        //Fabric Purchase Order
        if($menu_id==1){
          $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
            $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
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
            // ->join('po_fabric_items',function($join){
            //   $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id')
            //   ->whereNull('po_fabric_items.deleted_at');
            // })
            // ->join('po_fabrics',function($join){
            //   $join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
            // })
            //->where([['po_fabrics.id','=',request('po_fabric_id',0)]])
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
                po_fabrics.id as po_fabric_id,
                po_fabrics.po_no as fabric_po_no,
                po_fabrics.po_date,
                po_fabrics.company_id,
                po_fabrics.supplier_id,
                po_fabrics.exch_rate,
                companies.name as fabric_company,
                currencies.code as currency_name,
                suppliers.name as fabric_supplier_name,
                suppliers.address as fabric_supplier_address,
                
                item_accounts.id as item_account_id,          
                item_accounts.item_description,
                item_accounts.specification,
                item_accounts.sub_class_name,
                item_accounts.uom_id,
                styles.style_ref,
                buyers.name as buyer_name,
                budget_fabrics.style_fabrication_id,
                uoms.code as fabric_uom_code,
                po_fabric_items.id as po_fabric_item_id,
                po_fabric_items.qty as fabric_qty,
                po_fabric_items.rate as fabric_rate,
                po_fabric_items.amount as fabric_amount
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
              ->join('buyers', function($join) {
                $join->on('buyers.id', '=', 'styles.buyer_id');
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
              ->where([['po_fabrics.id','=',$bar_code_no]])
              ->get()
              ->map(function($purchaseorder) use($desDropdown){
                $purchaseorder->item_description=isset($desDropdown[$purchaseorder->style_fabrication_id])?$desDropdown[$purchaseorder->style_fabrication_id]:'';
                $purchaseorder->barcode_no_id=$purchaseorder->po_fabric_id;
                $purchaseorder->po_no=$purchaseorder->fabric_po_no;
                $purchaseorder->company_name=$purchaseorder->fabric_company;
                $purchaseorder->supplier_name=$purchaseorder->fabric_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->fabric_supplier_address;
                $purchaseorder->item_id=$purchaseorder->po_fabric_item_id;
                $purchaseorder->uom_code=$purchaseorder->fabric_uom_code;
                $purchaseorder->po_qty=$purchaseorder->fabric_qty;
                return $purchaseorder;
              });

            echo json_encode($purchaseorder);
        }
        //Trims Purchase Order
        if($menu_id==2){
            $purchaseorder =$this->potrim
            ->selectRaw('
                po_trims.id as po_trim_id,
                po_trims.po_no as trim_po_no,
                po_trims.supplier_id,
                po_trims.company_id,
                po_trims.itemcategory_id,
                suppliers.code as trim_supplier_name,
                suppliers.address as trim_supplier_address,
                companies.name as trim_company,
                itemcategories.name as itemcategory,
                item_accounts.item_description as trim_itemdesc,
                item_accounts.specification,
                item_accounts.sub_class_name,
                budget_trims.uom_id,
                itemclasses.name as itemclass_name,
                uoms.name as trim_uom_code,
                po_trim_items.id as trim_item_id,
                styles.style_ref,
                buyers.name as buyer_name,
                po_trim_items.qty as trim_qty
              ')
            ->leftJoin('companies',function($join){
              $join->on('companies.id','=','po_trims.company_id');
            })
            ->leftJoin('suppliers',function($join){
              $join->on('suppliers.id','=','po_trims.supplier_id');
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
            ->join('budgets',function($join){
              $join->on('budgets.id','=','budget_trims.budget_id');
            })
            ->join('jobs',function($join){
              $join->on('jobs.id','=','budgets.job_id');
            })
            ->join('styles', function($join) {
              $join->on('styles.id', '=', 'jobs.style_id');
            })
            ->join('buyers', function($join) {
              $join->on('buyers.id', '=', 'styles.buyer_id');
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
            ->where([['po_trims.id','=',$bar_code_no]])
            ->get()
            ->map(function($purchaseorder){
              $purchaseorder->barcode_no_id=$purchaseorder->po_trim_id;
              $purchaseorder->po_no=$purchaseorder->trim_po_no;
              $purchaseorder->company_name=$purchaseorder->trim_company;
              $purchaseorder->supplier_name=$purchaseorder->trim_supplier_name;
              $purchaseorder->supplier_contact=$purchaseorder->trim_supplier_address;
              $purchaseorder->item_id=$purchaseorder->trim_item_id;
              $purchaseorder->item_description=$purchaseorder->itemclass_name." , ".$purchaseorder->trim_itemdesc;
              $purchaseorder->uom_code=$purchaseorder->trim_uom_code;
              $purchaseorder->po_qty=$purchaseorder->trim_qty;
              //$purchaseorder->display_qty=number_format($purchaseorder->qty,0);
            return $purchaseorder;
            });
            
            echo json_encode($purchaseorder);
        }
        //Yarn Purchase Order 
        if($menu_id==3){
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
            $purchaseorder =$this->poyarn
            ->selectRaw('
                po_yarns.id as po_yarn_id,
                po_yarns.po_no as yarn_po_no,
                po_yarns.po_date,
                po_yarns.company_id,
                po_yarns.supplier_id,
                po_yarns.exch_rate,
                po_yarn_items.id as po_yarn_item_id,
                po_yarn_items.item_account_id,
                po_yarn_items.remarks as yarn_item_remarks,
                companies.name as yarn_company,
                currencies.code as currency_name,
                suppliers.code as yarn_supplier_name,
                suppliers.address as yarn_supplier_address,
                itemcategories.name as itemcategory,
                item_accounts.id as item_account_id,          
                item_accounts.item_description as yarn_itemdesc,
                item_accounts.specification,
                item_accounts.sub_class_name,
                item_accounts.uom_id,
                itemclasses.name as itemclass_name,
                uoms.code as yarn_uom_code,
                po_yarn_items.remarks as yarn_item_remarks,
                po_yarn_items.qty as yarn_qty,
                po_yarn_items.rate as yarn_rate,
                po_yarn_items.amount as yarn_amount
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
              ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','item_accounts.uom_id');
              })
              ->where([['po_yarns.id','=',$bar_code_no]])
              ->get()
              ->map(function($purchaseorder) use($yarnDropdown){
                $purchaseorder->barcode_no_id=$purchaseorder->po_yarn_id;
                $purchaseorder->po_no=$purchaseorder->yarn_po_no;
                $purchaseorder->company_name=$purchaseorder->yarn_company;
                $purchaseorder->supplier_name=$purchaseorder->yarn_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->yarn_supplier_address;
                $purchaseorder->yarn_itemdesc = $yarnDropdown[$purchaseorder->item_account_id];
                //$purchaseorder->qty=0;
                $purchaseorder->po_qty=$purchaseorder->yarn_qty;
                $purchaseorder->item_description=$purchaseorder->yarn_itemdesc;
                $purchaseorder->item_id=$purchaseorder->po_yarn_item_id;
                $purchaseorder->remarks=$purchaseorder->yarn_item_remarks;
                $purchaseorder->uom_code=$purchaseorder->yarn_uom_code;
                $purchaseorder->po_qty=$purchaseorder->yarn_qty;
              return $purchaseorder;
              });

            echo json_encode($purchaseorder);
        }
        //Knit Purchase Order 
        if($menu_id==4){
          $purchaseorder =$this->poknitservice
            ->selectRaw('
                po_knit_services.id as po_knit_service_id,
                po_knit_services.po_no as knit_service_po_no,
                po_knit_services.po_date,
                po_knit_services.company_id,
                po_knit_services.supplier_id,
                po_knit_services.exch_rate,
                companies.name as knit_company_name,
                currencies.code as currency_name,
                suppliers.name as knit_service_supplier_name,
                suppliers.address as knit_service_supplier_address,
                item_accounts.item_description as knit_service_itemdesc,
                item_accounts.uom_id,
                uoms.code as knit_uom_code,
                po_knit_service_items.id as po_knit_service_item_id,
                po_knit_service_items.qty as knit_qty,
                po_knit_service_items.rate as knit_rate,
                po_knit_service_items.amount as knit_amount
              ')
              /* itemcategories.name as itemcategory,
                item_accounts.id as item_account_id,          
                item_accounts.item_description as knit_service_itemdesc,
                item_accounts.specification,
                item_accounts.sub_class_name,
                itemclasses.name as itemclass_name, */
              ->leftJoin('companies',function($join){
                $join->on('companies.id','=','po_knit_services.company_id');
              })
              ->leftJoin('suppliers',function($join){
                $join->on('suppliers.id','=','po_knit_services.supplier_id');
              })
              ->leftJoin('currencies',function($join){
                $join->on('currencies.id','=','po_knit_services.currency_id');
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
              ->where([['po_knit_services.id','=',$bar_code_no]])
              ->get()
              ->map(function($purchaseorder){
                  $purchaseorder->barcode_no_id=$purchaseorder->po_knit_service_id;
                  $purchaseorder->po_no=$purchaseorder->knit_service_po_no;
                  $purchaseorder->supplier_name=$purchaseorder->knit_service_supplier_name;
                  $purchaseorder->supplier_contact=$purchaseorder->knit_service_supplier_address;
                  //$purchaseorder->qty=0;
                  //$purchaseorder->display_qty=number_format($purchaseorder->qty,0);
                  $purchaseorder->item_description=$purchaseorder->knit_service_itemdesc;
                  $purchaseorder->item_id=$purchaseorder->po_knit_service_item_id;
                  $purchaseorder->company_name=$purchaseorder->knit_company_name;
                  $purchaseorder->uom_code=$purchaseorder->knit_uom_code;
                  $purchaseorder->po_qty=$purchaseorder->knit_qty;
                return $purchaseorder;
            });   
            echo json_encode($purchaseorder);
        }
        //AOP Service Order
        if($menu_id==5){
            $fabricDescription=$this->poaopservice
            ->join('po_aop_service_items',function($join){
              $join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id')
            ->whereNull('po_aop_service_items.deleted_at');
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
            ->where([['po_aop_services.id','=',$bar_code_no]])
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
              po_aop_services.id as po_aop_service_id,
              po_aop_services.po_no as aop_service_po_no,
              po_aop_services.po_date,
              po_aop_services.company_id,
              po_aop_services.supplier_id,
              po_aop_services.exch_rate,
              companies.name as aop_company,
              currencies.code as currency_name,
              suppliers.name as aop_service_supplier_name,
              suppliers.address as aop_service_supplier_address,
              item_accounts.id as item_account_id,          
              item_accounts.item_description as aop_service_itemdesc,
              item_accounts.specification,
              item_accounts.sub_class_name,
              item_accounts.uom_id,
              budget_fabrics.style_fabrication_id,
              uoms.code as aop_uom_code,
              po_aop_service_items.id as po_aop_service_item_id,
              po_aop_service_items.qty as aop_service_qty,
              po_aop_service_items.rate as aop_service_rate,
              po_aop_service_items.amount as aop_service_amount
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
          /* ->leftJoin('itemcategories',function($join){
              $join->on('itemcategories.id','=','po_aop_services.itemcategory_id');
            }) */
            ->join('po_aop_service_items',function($join){
              $join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id')
              ->whereNull('po_aop_service_items.deleted_at');
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
            ->join('gmtsparts',function($join){
            $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
            })
            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            
            ->join('uoms',function($join){
            $join->on('uoms.id','=','style_fabrications.uom_id');
            })
          ->where([['po_aop_services.id','=',$bar_code_no]])
          ->get()
          ->map(function($purchaseorder) use($desDropdown){
            $purchaseorder->barcode_no_id=$purchaseorder->po_aop_service_id;
            $purchaseorder->po_no=$purchaseorder->aop_service_po_no;
            $purchaseorder->company_name=$purchaseorder->aop_company;
            $purchaseorder->supplier_name=$purchaseorder->aop_service_supplier_name;
            $purchaseorder->supplier_contact=$purchaseorder->aop_service_supplier_address;
            //$purchaseorder->qty=$purchaseorder->aop_service_qty;
            //$purchaseorder->display_qty=number_format($purchaseorder->qty,0);
            $purchaseorder->style_fabrication_id=$purchaseorder->style_fabrication_id;
            $purchaseorder->item_description=$desDropdown[$purchaseorder->style_fabrication_id];
            //$purchaseorder->item_description=$purchaseorder->aop_service_itemdesc;
            $purchaseorder->item_id=$purchaseorder->po_aop_service_item_id;
            $purchaseorder->uom_code=$purchaseorder->aop_uom_code;
            $purchaseorder->po_qty=$purchaseorder->aop_service_qty;
            return $purchaseorder;
          });

          echo json_encode($purchaseorder);
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
                po_dyeing_services.id as po_dyeing_service_id,
                po_dyeing_services.po_no as dyeing_service_po_no,
                po_dyeing_services.po_date,
                po_dyeing_services.company_id,
                po_dyeing_services.supplier_id,
                po_dyeing_services.exch_rate,
                companies.name as dyeing_company,
                currencies.code as currency_name,
                suppliers.name as dyservice_supplier_name,
                suppliers.address as dyservice_supplier_address,
                itemcategories.name as itemcategory,
                item_accounts.id as item_account_id,          
                item_accounts.item_description,
                item_accounts.specification,
                item_accounts.sub_class_name,
                item_accounts.uom_id,
                itemclasses.name as itemclass_name,
                budget_fabrics.style_fabrication_id,
                uoms.code as dyeing_uom_code,
                po_dyeing_service_items.id as po_dyeing_service_item_id,
                po_dyeing_service_items.qty as dyeing_service_qty,
                po_dyeing_service_items.rate as dyeing_service_rate,
                po_dyeing_service_items.amount as dyeing_service_amount
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
              ->join('uoms', function($join) {
                $join->on('uoms.id', '=', 'item_accounts.uom_id');
              })
              ->where([['po_dyeing_services.id','=',$bar_code_no]])
              ->get()
              ->map(function($purchaseorder) use($desDropdown){
                $purchaseorder->item_description=isset($desDropdown[$purchaseorder->style_fabrication_id])?$desDropdown[$purchaseorder->style_fabrication_id]:'';
                $purchaseorder->barcode_no_id=$purchaseorder->po_dyeing_service_id;
                $purchaseorder->po_no=$purchaseorder->dyeing_service_po_no;
                $purchaseorder->company_name=$purchaseorder->dyeing_company;
                $purchaseorder->supplier_name=$purchaseorder->dyservice_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->dyservice_supplier_address;
                $purchaseorder->item_id=$purchaseorder->po_dyeing_service_item_id;
                $purchaseorder->uom_code=$purchaseorder->dyeing_uom_code;
                
                return $purchaseorder;
            });

            echo json_encode($purchaseorder);
        }
        //Dye & Chem Purchase Order 
        if($menu_id==7){
            $purchaseorder =$this->podyechem
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
                ->leftJoin('uoms', function($join){
                  $join->on('uoms.id', '=', 'item_accounts.uom_id');
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
              ->where([['po_dye_chems.id','=',$bar_code_no]])
              ->get([
                'po_dye_chems.id as dye_chem_id',
                'po_dye_chems.po_no as dye_chem_po_no',
                'po_dye_chems.po_date',
                'po_dye_chems.itemcategory_id',
                'po_dye_chems.currency_id',
                'po_dye_chems.company_id',
                'po_dye_chems.supplier_id',
                'companies.code as dye_chem_company',
                'currencies.code as currency_name',
                'suppliers.name as dyechem_supplier_name',
                'suppliers.address as dyechem_supplier_address',
                'inv_pur_reqs.requisition_no as dyechem_req_no',
                'itemcategories.name as itemcategory',
                'itemclasses.name as itemclass_name',
                'item_accounts.sub_class_name',
                'item_accounts.item_description as dye_chem_itemdesc',
                'item_accounts.specification',
                'item_accounts.uom_id',
                'uoms.code as dye_chem_uom_code',
                'po_dye_chem_items.id as dye_chem_item_id',
                'po_dye_chem_items.remarks as dye_chem_item_remarks',
                'po_dye_chem_items.qty as dye_chem_qty'
              ])
              ->map(function($purchaseorder){
                $purchaseorder->barcode_no_id=$purchaseorder->dye_chem_id;
                $purchaseorder->po_no=$purchaseorder->dye_chem_po_no;
                $purchaseorder->item_description=$purchaseorder->sub_class_name.", ".$purchaseorder->dye_chem_itemdesc.", ".$purchaseorder->specification;
                $purchaseorder->company_name=$purchaseorder->dye_chem_company;
                $purchaseorder->supplier_name=$purchaseorder->dyechem_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->dyechem_supplier_address;
                $purchaseorder->requisition_no=$purchaseorder->dyechem_req_no;
                $purchaseorder->item_id=$purchaseorder->dye_chem_item_id;
                $purchaseorder->remarks=$purchaseorder->dye_chem_item_remarks;
                $purchaseorder->uom_code=$purchaseorder->dye_chem_uom_code;
                $purchaseorder->po_qty=$purchaseorder->dye_chem_qty;
                return $purchaseorder;
              });

            echo json_encode($purchaseorder);
        }
        //General Item Purchase Worder
        if($menu_id==8){
          $purchaseorder =$this->pogeneral
            ->selectRaw('
              po_generals.id as po_general_id,
              po_generals.po_no as general_po_no,
              po_generals.po_date,
              po_generals.itemcategory_id,
              po_generals.currency_id,
              po_generals.company_id,
              po_generals.exch_rate,
              companies.name as general_company_name,
              currencies.code as currency_name,
              suppliers.name as general_supplier_name,
              suppliers.address as general_supplier_address,
              inv_pur_reqs.requisition_no as general_req_no,
              itemcategories.name as itemcategory,
              itemclasses.name as itemclass_name,
              item_accounts.sub_class_name,
              item_accounts.item_description as general_itemdesc,
              item_accounts.specification,
              item_accounts.uom_id,
              uoms.code as general_uom_code,
              po_general_items.id as po_general_item_id,
              po_general_items.remarks as general_item_remarks,
              po_general_items.qty as general_qty,
              po_general_items.rate as general_rate,
              po_general_items.amount as general_amount
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
            ->join('uoms', function($join){
              $join->on('uoms.id', '=', 'item_accounts.uom_id');
            })
            ->join('inv_pur_reqs', function($join){
              $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
            })
            ->where([['po_generals.id','=',$bar_code_no]])
            ->get()
            ->map(function($purchaseorder){
              $purchaseorder->barcode_no_id=$purchaseorder->po_general_id;
              $purchaseorder->po_no=$purchaseorder->general_po_no;
              $purchaseorder->item_description=$purchaseorder->sub_class_name.", ".$purchaseorder->general_itemdesc.", ".$purchaseorder->specification;
              $purchaseorder->supplier_name=$purchaseorder->general_supplier_name;
              $purchaseorder->company_name=$purchaseorder->general_company_name;
              $purchaseorder->supplier_contact=$purchaseorder->general_supplier_address;
              $purchaseorder->requisition_no=$purchaseorder->general_req_no;
              $purchaseorder->item_id=$purchaseorder->po_general_item_id;
              $purchaseorder->uom_code=$purchaseorder->general_uom_code;
              $purchaseorder->remarks=$purchaseorder->general_item_remarks;
              $purchaseorder->po_qty=$purchaseorder->general_qty;
              return $purchaseorder;
            });
          echo json_encode($purchaseorder);
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
    
          $data=$this->poyarndyeing
            ->selectRaw(
            '
              po_yarn_dyeings.id as po_yarn_dyeing_id,
              po_yarn_dyeings.po_no as yarn_dyeing_po_no,
              po_yarn_dyeings.supplier_id,
              po_yarn_dyeings.company_id,
              item_accounts.uom_id,
              uoms.code as uom_code,
              suppliers.name as yarn_dyeing_supplier_name,
              suppliers.address as yarn_dyeing_supplier_address,
              companies.name as yarn_dyeing_company,
              po_yarn_dyeing_items.inv_yarn_item_id,
              po_yarn_dyeing_items.id as po_yarn_dyeing_item_id,
              po_yarn_dyeing_items.remarks as yarn_dyeing_item_remarks,
              po_yarn_dyeing_items.qty as yarn_dyeing_qty
            '
            )
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
            ->where([['po_yarn_dyeings.id','=',$bar_code_no]])
            ->groupBy([
              'po_yarn_dyeings.id',
              'po_yarn_dyeings.po_no',
              'po_yarn_dyeings.company_id',
              'po_yarn_dyeings.supplier_id',
              'item_accounts.uom_id',
              'uoms.code',
              'suppliers.name',
              'suppliers.address',
              'companies.name',
              'po_yarn_dyeing_items.inv_yarn_item_id',
              'po_yarn_dyeing_items.id',
              'po_yarn_dyeing_items.remarks',
              'po_yarn_dyeing_items.qty',
            ])
            ->get()
            ->map(function ($data) use($yarnDropdown) {
                $data->barcode_no_id=$data->po_yarn_dyeing_id;
                $data->item_description = $yarnDropdown[$data->inv_yarn_item_id];
                $data->po_no=$data->yarn_dyeing_po_no;
                $data->company_name=$data->yarn_dyeing_company;
                $data->supplier_name=$data->yarn_dyeing_supplier_name;
                $data->supplier_contact=$data->yarn_dyeing_supplier_address;
                //$data->qty=$data->yarn_dyeing_qty;
                //$data->display_qty=number_format($data->qty,0);
                $data->item_id=$data->po_yarn_dyeing_item_id;
                $data->remarks=$data->yarn_dyeing_item_remarks;
                return $data;
            });  
          echo json_encode($data);
        }
        //Embelishment Purchase Order
        if($menu_id==10){
          $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
            $purchaseorder =$this->poembservice
              ->selectRaw('
                po_emb_services.id as po_emb_service_id,
                po_emb_services.po_no as emb_service_po_no,
                po_emb_services.po_date,
                po_emb_services.company_id,
                po_emb_services.supplier_id,
                po_emb_services.exch_rate,
                companies.name as emb_company,
                currencies.code as currency_name,
                suppliers.name as emb_supplier_name,
                suppliers.address as emb_supplier_address,
                
                item_accounts.id as item_account_id,          
                item_accounts.item_description,
                item_accounts.specification,
                item_accounts.sub_class_name,
                item_accounts.uom_id,
                
                style_embelishments.embelishment_size_id,
                embelishments.name as embelishment_name,
                embelishment_types.name as embelishment_type,
                gmtsparts.name as gmtspart_name,
                
                uoms.code as emb_uom_code,
                po_emb_service_items.id as po_emb_service_item_id,
                po_emb_service_items.qty as emb_service_qty,
                po_emb_service_items.rate as emb_service_rate,
                po_emb_service_items.amount as emb_service_amount
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
              // ->leftJoin('itemcategories',function($join){
              //   $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
              // })
              // ->leftJoin('itemclasses',function($join){
              //   $join->on('itemclasses.id','=','item_accounts.itemclass_id');
              // })
              ->leftJoin('uoms', function($join) {
                $join->on('uoms.id', '=', 'item_accounts.uom_id');
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
              ->where([['po_emb_services.id','=',$bar_code_no]])
              ->get()
              ->map(function($purchaseorder) use($embelishmentsize){
                $purchaseorder->embelishment_size = $embelishmentsize[$purchaseorder->embelishment_size_id];
                $purchaseorder->barcode_no_id=$purchaseorder->po_emb_service_id;
                $purchaseorder->po_no=$purchaseorder->emb_service_po_no;
                $purchaseorder->company_name=$purchaseorder->emb_company;
                $purchaseorder->supplier_name=$purchaseorder->emb_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->emb_supplier_address;
                $purchaseorder->item_description=$purchaseorder->item_description.','.$purchaseorder->gmtspart_name.','.$purchaseorder->embelishment_name.','.$purchaseorder->embelishment_size.','.$purchaseorder->embelishment_type;
                $purchaseorder->item_id=$purchaseorder->po_emb_service_item_id;
                $purchaseorder->uom_code=$purchaseorder->emb_uom_code;
                $purchaseorder->po_qty=$purchaseorder->emb_service_qty;
                return $purchaseorder;
            });

            echo json_encode($purchaseorder);
        }
        //General Service Order
        if($menu_id==11){
            $purchaseorder =$this->pogeneralservice
              ->selectRaw('
                po_general_services.id as po_general_service_id,
                po_general_services.po_no as general_service_po_no,
                po_general_services.po_date,
                po_general_services.company_id,
                po_general_services.supplier_id,
                po_general_services.exch_rate,
                companies.name as service_company,
                currencies.code as currency_name,
                suppliers.name as service_supplier_name,
                suppliers.address as service_supplier_address,
                po_general_service_items.service_description,
                po_general_service_items.uom_id,
                po_general_service_items.remarks as service_item_remarks,
                uoms.code as service_uom_code,
                po_general_service_items.id as po_general_service_item_id,
                po_general_service_items.qty as general_service_qty,
                po_general_service_items.rate as general_service_rate,
                po_general_service_items.amount as general_service_amount
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
              ->join('po_general_service_items',function($join){
                $join->on('po_general_service_items.po_general_service_id','=','po_general_services.id')
              ->whereNull('po_general_service_items.deleted_at');
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
              ->where([['po_general_services.id','=',$bar_code_no]])
              ->get()
              ->map(function($purchaseorder) {
                $purchaseorder->barcode_no_id=$purchaseorder->po_general_service_id;
                $purchaseorder->po_no=$purchaseorder->general_service_po_no;
                $purchaseorder->company_name=$purchaseorder->service_company;
                $purchaseorder->supplier_name=$purchaseorder->service_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->service_supplier_address;
                $purchaseorder->item_description=$purchaseorder->service_description;
                $purchaseorder->item_id=$purchaseorder->po_general_service_item_id;
                $purchaseorder->uom_code=$purchaseorder->service_uom_code;
                $purchaseorder->remarks=$purchaseorder->service_item_remarks;
                $purchaseorder->po_qty=$purchaseorder->general_service_qty;
                return $purchaseorder;
            });

            echo json_encode($purchaseorder);
        }
        //Inventory Purchase Requisition
        if($menu_id==103){
          $invpurreqitem=$this->invpurreq
          ->selectRaw('
            inv_pur_reqs.id as inv_pur_req_id,
            inv_pur_reqs.requisition_no as purchase_req_no,
            inv_pur_reqs.currency_id,
            inv_pur_reqs.company_id,
            inv_pur_req_items.id as inv_pur_req_item_id,
            inv_pur_req_items.item_account_id, 
            inv_pur_req_items.remarks as req_item_remarks,        
            item_accounts.item_description as purchase_item_desc,
            item_accounts.sub_class_name,
            item_accounts.specification,
            item_accounts.uom_id,
            companies.name as purchase_company,
            itemcategories.name as itemcategory_name,         
            uoms.code as purchase_uom_code,
            inv_pur_req_items.qty as item_qty
          ')
          ->join('companies',function($join){
            $join->on('companies.id','=','inv_pur_reqs.company_id');
          })
          ->leftJoin('inv_pur_req_items',function($join){
              $join->on('inv_pur_reqs.id','=','inv_pur_req_items.inv_pur_req_id');
          })
          ->leftJoin('item_accounts',function($join){
              $join->on('item_accounts.id','=','inv_pur_req_items.item_account_id');
          })
          ->leftJoin('itemclasses', function($join){
              $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
          })
          ->leftJoin('itemcategories', function($join){
          $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
          })
          ->leftJoin('uoms',function($join){
              $join->on('uoms.id','=','item_accounts.uom_id');
          })
          ->where([['inv_pur_reqs.id','=',$bar_code_no]])
          ->groupBy([
            'inv_pur_reqs.id',
            'inv_pur_req_items.id', 
            'inv_pur_req_items.item_account_id', 
            'inv_pur_reqs.requisition_no',
            'inv_pur_reqs.currency_id',
            'inv_pur_reqs.company_id',
            'inv_pur_req_items.remarks',        
            'item_accounts.item_description',
            'item_accounts.sub_class_name',
            'item_accounts.specification',
            'item_accounts.uom_id',
            'companies.name',
            'itemcategories.name',         
            'uoms.code',
            'inv_pur_req_items.qty'
          ])
          ->get()
          ->map(function($invpurreqitem){
            $invpurreqitem->barcode_no_id=$invpurreqitem->inv_pur_req_id;
            $invpurreqitem->requisition_no=$invpurreqitem->purchase_req_no;
            $invpurreqitem->item_description=$invpurreqitem->sub_class_name.", ".$invpurreqitem->purchase_item_desc.", ".$invpurreqitem->specification;
           // $invpurreqitem->qty=$invpurreqitem->item_qty;
            $invpurreqitem->display_qty=number_format($invpurreqitem->qty,0);
            $invpurreqitem->item_id=$invpurreqitem->inv_pur_req_item_id;
            $invpurreqitem->remarks=$invpurreqitem->req_item_remarks;
            $invpurreqitem->company_name=$invpurreqitem->purchase_company;
            $invpurreqitem->uom_code=$invpurreqitem->purchase_uom_code;
            $invpurreqitem->po_qty=$invpurreqitem->item_qty;
            return $invpurreqitem;
          });
          echo json_encode($invpurreqitem);
        }
    }

}