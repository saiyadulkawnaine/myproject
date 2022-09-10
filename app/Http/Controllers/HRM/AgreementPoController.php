<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\AgreementRepository;
use App\Repositories\Contracts\HRM\AgreementPoRepository;
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
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Library\Template;
use App\Http\Requests\HRM\AgreementPoRequest;

class AgreementPoController extends Controller {

    private $agreementpo;
    private $agreement;
    private $pofabric;
    private $poyarn;
    private $potrim;
    private $podyechem;
    private $podyeingservice;
    private $pogeneral;
    private $poknitservice;
    private $itemaccount;
    private $budgetfabric;
    private $poaopservice;
    private $poyarndyeing;
    private $poembservice;

        public function __construct(AgreementRepository $agreement,AgreementPoRepository $agreementpo,  PoFabricRepository $pofabric,
            PoTrimRepository $potrim,
            PoYarnRepository $poyarn,
            PoKnitServiceRepository $poknitservice,
            PoAopServiceRepository $poaopservice,
            PoDyeingServiceRepository $podyeingservice,
            PoDyeChemRepository $podyechem,
            PoGeneralRepository $pogeneral,
            ItemAccountRepository $itemaccount,
            BudgetFabricRepository $budgetfabric,
            PoEmbServiceRepository $poembservice,
            PoYarnDyeingRepository $poyarndyeing
        ) {
        
        $this->agreementpo = $agreementpo;
        $this->agreement = $agreement;
        $this->pofabric = $pofabric;
        $this->poyarn = $poyarn;
        $this->potrim = $potrim;
        $this->podyechem = $podyechem;
        $this->podyeingservice = $podyeingservice;
        $this->pogeneral = $pogeneral;
        $this->poknitservice = $poknitservice;
        $this->budgetfabric = $budgetfabric;
        $this->poaopservice = $poaopservice;
        $this->poembservice = $poembservice;
        $this->poyarndyeing = $poyarndyeing;

        $this->middleware('auth');
        // $this->middleware('permission:view.agreementpos',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.agreementpos', ['only' => ['store']]);
        // $this->middleware('permission:edit.agreementpos',   ['only' => ['update']]);
        // $this->middleware('permission:delete.agreementpos', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $menu=array_prepend(array_only(config('bprs.menu'), [1,2,3,4,5,6,7,8,9,10]),'-Select-',''); 
        $agreementpos=array();
        // $rows=$this->agreementpo
        // ->where([['agreement_id','=',request('agreement_id',0)]])
        // ->orderBy('agreement_pos.id','asc')
        // ->get();
        $agreementId=request('agreement_id',0);
        $rows = collect(\DB::select("
          select
          agreement_pos.id,
          agreement_pos.purchase_order_id,
          agreement_pos.menu_id,
          case when
          agreement_pos.menu_id=1
          then po_fabrics.po_no
          when 
          agreement_pos.menu_id=2
          then po_trims.po_no
          when
          agreement_pos.menu_id=3
          then po_yarns.po_no
          when 
          agreement_pos.menu_id=4
          then po_knit_services.po_no
          when 
          agreement_pos.menu_id=5
          then po_aop_services.po_no
          when
          agreement_pos.menu_id=6
          then po_dyeing_services.po_no
          when
          agreement_pos.menu_id=7
          then po_dye_chems.po_no
          when
          agreement_pos.menu_id=8
          then po_generals.po_no
          when
          agreement_pos.menu_id=9
          then po_yarn_dyeings.po_no
          when
          agreement_pos.menu_id=10
          then po_emb_services.po_no
          else null
          end as po_no,
          case when 
          agreement_pos.menu_id=1
          then po_fabrics.amount
          when 
          agreement_pos.menu_id=2
          then po_trims.amount
          when 
          agreement_pos.menu_id=3
          then po_yarns.amount
          when 
          agreement_pos.menu_id=4
          then po_knit_services.amount
          when 
          agreement_pos.menu_id=5
          then po_aop_services.amount
          when 
          agreement_pos.menu_id=6
          then po_dyeing_services.amount
          when 
          agreement_pos.menu_id=7
          then po_dye_chems.amount
          when 
          agreement_pos.menu_id=8
          then po_generals.amount
          when 
          agreement_pos.menu_id=9
          then po_yarn_dyeings.amount
          when 
          agreement_pos.menu_id=10
          then po_emb_services.amount
          else 0
          end as po_amount
          from
          agreement_pos
          join agreements on agreements.id=agreement_pos.agreement_id
          left join po_fabrics on po_fabrics.id=agreement_pos.purchase_order_id and agreement_pos.menu_id=1
          left join po_trims on po_trims.id=agreement_pos.purchase_order_id and agreement_pos.menu_id=2
          left join po_yarns on po_yarns.id=agreement_pos.purchase_order_id and agreement_pos.menu_id=3
          left join po_knit_services on po_knit_services.id=agreement_pos.purchase_order_id and agreement_pos.menu_id=4
          left join po_aop_services on po_aop_services.id=agreement_pos.purchase_order_id and agreement_pos.menu_id=5
          left join po_dyeing_services on po_dyeing_services.id=agreement_pos.purchase_order_id and agreement_pos.menu_id=6
          left join po_dye_chems on po_dye_chems.id=agreement_pos.purchase_order_id and agreement_pos.menu_id=7
          left join po_generals on po_generals.id=agreement_pos.purchase_order_id and agreement_pos.menu_id=8
          left join po_yarn_dyeings on po_yarn_dyeings.id=agreement_pos.purchase_order_id and agreement_pos.menu_id=9
          left join po_emb_services on po_emb_services.id=agreement_pos.purchase_order_id and agreement_pos.menu_id=10
          where agreement_pos.agreement_id=".$agreementId."  
          order by agreement_pos.id desc
        "));
        foreach($rows as $row){
           $agreementpo['id']=$row->id; 
           $agreementpo['menu_id']=$row->menu_id; 
           $agreementpo['item']=$menu[$row->menu_id]; 
           $agreementpo['purchase_order_id']=$row->purchase_order_id; 
           $agreementpo['po_no']=$row->po_no; 
           $agreementpo['po_amount']=$row->po_amount;
           array_push($agreementpos,$agreementpo);
        }
        echo json_encode($agreementpos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AgreementPoRequest $request) {
        //$agreementpo=$this->agreementpo->create($request->except(['id']));
        foreach($request->purchase_order_id as $index=>$purchase_order_id){
			if($request->agreement_id){
          //if ($request->menu_id) {
              $agreementpo = $this->agreementpo->updateOrCreate(
                  ['agreement_id' => $request->agreement_id,'purchase_order_id' => $purchase_order_id],
                  ['menu_id' => $request->menu_id]
                  );
          // }
				
			}
		}
		if($agreementpo){
			return response()->json(array('success' => true,'id' =>  $agreementpo->id,'message' => 'Save Successfully'),200);
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
      $agreementpo = $this->agreementpo->find($id);
	    $row ['fromData'] = $agreementpo;
	    $dropdown['att'] = '';
	    $row ['dropDown'] = $dropdown;
       echo json_encode($row); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AgreementPoRequest $request, $id) {
       $agreementpo=$this->agreementpo->update($id,$request->except(['id']));
		if($agreementpo){
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
        if($this->agreementpo->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getPurchaseOrder(){
        $menu_id=request('menu_id',0);
        $agreement=$this->agreement->find(request('agreement_id',0));
        //Fabric Purchase Order
        if($menu_id==1){
          $purchaseorder =$this->pofabric
            ->selectRaw('
              po_fabrics.id as po_fabric_id,
              po_fabrics.po_no as fabric_po_no,
              po_fabrics.po_date,
              po_fabrics.company_id,
              po_fabrics.supplier_id,
              po_fabrics.exch_rate,
              po_fabrics.delv_start_date,
              po_fabrics.delv_end_date,
              po_fabrics.pi_no,
              companies.name as fabric_company,
              currencies.code as currency_name,
              suppliers.name as fabric_supplier_name,
              suppliers.address as fabric_supplier_address,
              po_fabrics.amount as fabric_amount,
              agreement_pos.id as agreement_po_id
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
            ->leftJoin('agreement_pos',function($join){
              $join->on('agreement_pos.purchase_order_id','=','po_fabrics.id');
              $join->where([['agreement_pos.agreement_id','=',request('agreement_id',0)]]);
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('po_fabrics.company_id','=',request('company_id', 0));
            })   
            ->when(request('po_no'), function ($q) {
                return $q->where('po_fabrics.po_no','=',request('po_no', 0));
            })
            ->where([['po_fabrics.supplier_id','=',$agreement->supplier_id]])
            ->orderBy('po_fabrics.id')
            ->get()
            ->map(function($purchaseorder){
              $purchaseorder->purchase_order_id=$purchaseorder->po_fabric_id;
              $purchaseorder->po_no=$purchaseorder->fabric_po_no;
              $purchaseorder->company_name=$purchaseorder->fabric_company;
              $purchaseorder->supplier_name=$purchaseorder->fabric_supplier_name;
              $purchaseorder->supplier_contact=$purchaseorder->fabric_supplier_address;
              
              return $purchaseorder;
            });

            $notsaved = $purchaseorder->filter(function ($value) {
              if(!$value->agreement_po_id){
                  return $value;
              }
            });
            $po=array();
            foreach($notsaved as $row){
              array_push($po,$row);
            }
            echo json_encode($po);
        }
        //Trims Purchase Order
        if($menu_id==2){
            $purchaseorder =$this->potrim
            ->selectRaw('
                po_trims.id as po_trim_id,
                po_trims.po_no as trim_po_no,
                po_trims.supplier_id,
                po_trims.company_id,
                po_trims.currency_id,
                po_trims.exch_rate,
                po_trims.delv_start_date,
                po_trims.delv_end_date,
                po_trims.pi_no,
                suppliers.code as trim_supplier_name,
                suppliers.address as trim_supplier_address,
                companies.name as trim_company,
                po_trims.amount as trim_amount,
                agreement_pos.id as agreement_po_id
                
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
            ->leftJoin('agreement_pos',function($join){
              $join->on('agreement_pos.purchase_order_id','=','po_trims.id');
              $join->where([['agreement_pos.agreement_id','=',request('agreement_id',0)]]);
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('po_trims.company_id','=',request('company_id', 0));
            })   
            ->when(request('po_no'), function ($q) {
                return $q->where('po_trims.po_no','=',request('po_no', 0));
            })
            ->where([['po_trims.supplier_id','=',$agreement->supplier_id]])
            ->orderBy('po_trims.id')
            ->get()
            ->map(function($purchaseorder){
              $purchaseorder->purchase_order_id=$purchaseorder->po_trim_id;
              $purchaseorder->po_no=$purchaseorder->trim_po_no;
              $purchaseorder->company_name=$purchaseorder->trim_company;
              $purchaseorder->supplier_name=$purchaseorder->trim_supplier_name;
              $purchaseorder->supplier_contact=$purchaseorder->trim_supplier_address;
              $purchaseorder->amount=$purchaseorder->trim_amount;
            return $purchaseorder;
            });
            
            echo json_encode($purchaseorder);
        }
        //Yarn Purchase Order 
        if($menu_id==3){
          $purchaseorder =$this->poyarn
            ->selectRaw('
                po_yarns.id as po_yarn_id,
                po_yarns.po_no as yarn_po_no,
                po_yarns.po_date,
                po_yarns.company_id,
                po_yarns.supplier_id,
                po_yarns.exch_rate,
                po_yarns.delv_start_date,
                po_yarns.delv_end_date,
                po_yarns.pi_no,
                companies.name as yarn_company,
                currencies.code as currency_name,
                suppliers.name as yarn_supplier_name,
                suppliers.address as yarn_supplier_address,
                po_yarns.amount as yarn_amount,
                agreement_pos.id as agreement_po_id
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
            ->leftJoin('agreement_pos',function($join){
              $join->on('agreement_pos.purchase_order_id','=','po_yarns.id');
              $join->where([['agreement_pos.agreement_id','=',request('agreement_id',0)]]);
            })
            ->when(request('company_id'), function ($q) {
                return $q->where('po_yarns.company_id','=',request('company_id', 0));
            })   
            ->when(request('po_no'), function ($q) {
                return $q->where('po_yarns.po_no','=',request('po_no', 0));
            })
            ->where([['po_yarns.supplier_id','=',$agreement->supplier_id]])
            ->orderBy('po_yarns.id')
            ->get()
            ->map(function($purchaseorder) /* use($yarnDropdown) */{
                $purchaseorder->purchase_order_id=$purchaseorder->po_yarn_id;
                $purchaseorder->po_no=$purchaseorder->yarn_po_no;
                $purchaseorder->company_name=$purchaseorder->yarn_company;
                $purchaseorder->supplier_name=$purchaseorder->yarn_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->yarn_supplier_address;
                $purchaseorder->remarks=$purchaseorder->yarn_remarks;
                $purchaseorder->amount=$purchaseorder->yarn_amount;   
            return $purchaseorder;
            });

            $notsaved = $purchaseorder->filter(function ($value) {
                if(!$value->agreement_po_id){
                    return $value;
                }
            });
            $po=array();
            foreach($notsaved as $row){
                array_push($po,$row);
            }
            echo json_encode($po);
        }
        //Knit Purchase Order 
        if($menu_id==4){
            $purchaseorder =$this->poknitservice
            ->selectRaw('
                po_knit_services.id as po_knit_service_id,
                po_knit_services.po_no as knit_service_po_no,
                po_knit_services.po_date,
                po_knit_services.company_id,
                po_knit_services.currency_id,
                po_knit_services.supplier_id,
                po_knit_services.exch_rate,
                po_knit_services.delv_start_date,
                po_knit_services.delv_end_date,
                po_knit_services.pi_no,
                companies.name as knit_company_name,
                currencies.code as currency_name,
                suppliers.name as knit_service_supplier_name,
                suppliers.address as knit_service_supplier_address,
                po_knit_services.amount as knit_amount,
                agreement_pos.id as agreement_po_id
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
            ->leftJoin('agreement_pos',function($join){
              $join->on('agreement_pos.purchase_order_id','=','po_knit_services.id');
              $join->where([['agreement_pos.agreement_id','=',request('agreement_id',0)]]);
            })
            ->when(request('company_id'), function ($q) {
              return $q->where('po_knit_services.company_id','=',request('company_id', 0));
            })   
            ->when(request('po_no'), function ($q) {
              return $q->where('po_knit_services.po_no','=',request('po_no', 0));
            })
            ->where([['po_knit_services.supplier_id','=',$agreement->supplier_id]])
            ->orderBy('po_knit_services.id')
            ->get()
            ->map(function($purchaseorder){
                $purchaseorder->purchase_order_id=$purchaseorder->po_knit_service_id;
                $purchaseorder->po_no=$purchaseorder->knit_service_po_no;
                $purchaseorder->supplier_name=$purchaseorder->knit_service_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->knit_service_supplier_address;
                $purchaseorder->company_name=$purchaseorder->knit_company_name;
                $purchaseorder->amount=$purchaseorder->knit_amount;
                return $purchaseorder;
            });   
            $notsaved = $purchaseorder->filter(function ($value) {
              if(!$value->agreement_po_id){
                  return $value;
              }
            });
            $po=array();
            foreach($notsaved as $row){
                array_push($po,$row);
            }
            echo json_encode($po);
        }
        // //AOP Service Order
        if($menu_id==5){
            $purchaseorder =$this->poaopservice
            ->selectRaw('
                po_aop_services.id as po_aop_service_id,
                po_aop_services.po_no as aop_service_po_no,
                po_aop_services.po_date,
                po_aop_services.company_id,
                po_aop_services.currency_id,
                po_aop_services.supplier_id,
                po_aop_services.exch_rate,
                po_aop_services.delv_start_date,
                po_aop_services.delv_end_date,
                po_aop_services.pi_no,
                companies.name as aop_company,
                currencies.code as currency_name,
                suppliers.name as aop_service_supplier_name,
                suppliers.address as aop_service_supplier_address,
                po_aop_services.amount as aop_service_amount,
                agreement_pos.id as agreement_po_id
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
              ->leftJoin('agreement_pos',function($join){
                $join->on('agreement_pos.purchase_order_id','=','po_aop_services.id');
                $join->where([['agreement_pos.agreement_id','=',request('agreement_id',0)]]);
              })
              ->when(request('company_id'), function ($q) {
                return $q->where('po_aop_services.company_id','=',request('company_id', 0));
              })   
              ->when(request('po_no'), function ($q) {
                return $q->where('po_aop_services.po_no','=',request('po_no', 0));
              })
              ->where([['po_aop_services.supplier_id','=',$agreement->supplier_id]])
              ->orderBy('po_aop_services.id')
            
            ->get()
            ->map(function($purchaseorder) {
                $purchaseorder->purchase_order_id=$purchaseorder->po_aop_service_id;
                $purchaseorder->po_no=$purchaseorder->aop_service_po_no;
                $purchaseorder->company_name=$purchaseorder->aop_company;
                $purchaseorder->supplier_name=$purchaseorder->aop_service_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->aop_service_supplier_address;
                $purchaseorder->amount=$purchaseorder->aop_service_amount;
               
                return $purchaseorder;
            });

            $notsaved = $purchaseorder->filter(function ($value) {
              if(!$value->agreement_po_id){
                  return $value;
              }
            });
            $po=array();
            foreach($notsaved as $row){
                array_push($po,$row);
            }
            echo json_encode($po);
        }
        // //Dyeing Service Work Order
        if($menu_id==6){
            $purchaseorder =$this->podyeingservice
            ->selectRaw('
                po_dyeing_services.id as po_dyeing_service_id,
                po_dyeing_services.po_no as dyeing_service_po_no,
                po_dyeing_services.po_date,
                po_dyeing_services.company_id,
                po_dyeing_services.currency_id,
                po_dyeing_services.supplier_id,
                po_dyeing_services.exch_rate,
                po_dyeing_services.delv_start_date,
                po_dyeing_services.delv_end_date,
                po_dyeing_services.pi_no,
                companies.name as dyeing_company,
                currencies.code as currency_name,
                suppliers.name as dyservice_supplier_name,
                suppliers.address as dyservice_supplier_address,
                po_dyeing_services.amount as dyeing_service_amount,
                agreement_pos.id as agreement_po_id
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
              ->leftJoin('agreement_pos',function($join){
                $join->on('agreement_pos.purchase_order_id','=','po_dyeing_services.id');
                $join->where([['agreement_pos.agreement_id','=',request('agreement_id',0)]]);
              })
              ->when(request('company_id'), function ($q) {
                return $q->where('po_dyeing_services.company_id','=',request('company_id', 0));
              })   
              ->when(request('po_no'), function ($q) {
                return $q->where('po_dyeing_services.po_no','=',request('po_no', 0));
              })
              ->where([['po_dyeing_services.supplier_id','=',$agreement->supplier_id]])
              ->orderBy('po_dyeing_services.id')
              ->get()
              ->map(function($purchaseorder){
                $purchaseorder->purchase_order_id=$purchaseorder->po_dyeing_service_id;
                $purchaseorder->po_no=$purchaseorder->dyeing_service_po_no;
                $purchaseorder->company_name=$purchaseorder->dyeing_company;
                $purchaseorder->supplier_name=$purchaseorder->dyservice_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->dyservice_supplier_address;
                $purchaseorder->amount=$purchaseorder->dyeing_service_amount;
                
                return $purchaseorder;
              });
              $notsaved = $purchaseorder->filter(function ($value) {
                if(!$value->agreement_po_id){
                    return $value;
                }
              });
              $po=array();
              foreach($notsaved as $row){
                  array_push($po,$row);
              }
              echo json_encode($po);
        }
        // //Dye & Chem Purchase Order 
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
              ->leftJoin('agreement_pos',function($join){
                $join->on('agreement_pos.purchase_order_id','=','po_dye_chems.id');
                $join->where([['agreement_pos.agreement_id','=',request('agreement_id',0)]]);
              })
              ->when(request('company_id'), function ($q) {
                return $q->where('po_dye_chems.company_id','=',request('company_id', 0));
              })   
              ->when(request('po_no'), function ($q) {
                return $q->where('po_dye_chems.po_no','=',request('po_no', 0));
              })
              ->where([['po_dye_chems.supplier_id','=',$agreement->supplier_id]])
              ->orderBy('po_dye_chems.id')
              ->get([
                'po_dye_chems.id as dye_chem_id',
                'po_dye_chems.po_no as dye_chem_po_no',
                'po_dye_chems.po_date',
                'po_dye_chems.itemcategory_id',
                'po_dye_chems.currency_id',
                'po_dye_chems.company_id',
                'po_dye_chems.supplier_id',
                'po_dye_chems.delv_start_date',
                'po_dye_chems.delv_end_date',
                'po_dye_chems.pi_no',
                'companies.code as dye_chem_company',
                'currencies.code as currency_name',
                'suppliers.name as dyechem_supplier_name',
                'suppliers.address as dyechem_supplier_address',
                'agreement_pos.id as agreement_po_id'
              ])
              ->map(function($purchaseorder){
                $purchaseorder->purchase_order_id=$purchaseorder->dye_chem_id;
                $purchaseorder->po_no=$purchaseorder->dye_chem_po_no;
                $purchaseorder->company_name=$purchaseorder->dye_chem_company;
                $purchaseorder->supplier_name=$purchaseorder->dyechem_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->dyechem_supplier_address;
                $purchaseorder->remarks=$purchaseorder->dye_chem_item_remarks;
                return $purchaseorder;
              });

              $notsaved = $purchaseorder->filter(function ($value) {
                if(!$value->agreement_po_id){
                    return $value;
                }
              });
              $po=array();
              foreach($notsaved as $row){
                  array_push($po,$row);
              }
              echo json_encode($po);
        }
        // //General Item Purchase Worder
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
              po_generals.delv_start_date,
              po_generals.delv_end_date,
              po_generals.pi_no,
              companies.name as general_company_name,
              currencies.code as currency_name,
              suppliers.name as general_supplier_name,
              suppliers.address as general_supplier_address,
              po_generals.amount as general_amount,
              agreement_pos.id as agreement_po_id
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
            ->leftJoin('agreement_pos',function($join){
              $join->on('agreement_pos.purchase_order_id','=','po_generals.id');
              $join->where([['agreement_pos.agreement_id','=',request('agreement_id',0)]]);
            })
            ->when(request('company_id'), function ($q) {
              return $q->where('po_generals.company_id','=',request('company_id', 0));
            })   
            ->when(request('po_no'), function ($q) {
              return $q->where('po_generals.po_no','=',request('po_no', 0));
            })
            ->where([['po_generals.supplier_id','=',$agreement->supplier_id]])
            ->orderBy('po_generals.id')
            ->get()
            ->map(function($purchaseorder){
              $purchaseorder->purchase_order_id=$purchaseorder->po_general_id;
              $purchaseorder->po_no=$purchaseorder->general_po_no;
              $purchaseorder->supplier_name=$purchaseorder->general_supplier_name;
              $purchaseorder->company_name=$purchaseorder->general_company_name;
              $purchaseorder->supplier_contact=$purchaseorder->general_supplier_address;
              $purchaseorder->remarks=$purchaseorder->general_item_remarks;
              $purchaseorder->amount=$purchaseorder->general_amount;
              return $purchaseorder;
            });
           $notsaved = $purchaseorder->filter(function ($value) {
                if(!$value->agreement_po_id){
                    return $value;
                }
            });
            $po=array();
            foreach($notsaved as $row){
                array_push($po,$row);
            }
            echo json_encode($po);
        }
        // //Yarn Dyeing Work Order
        if($menu_id==9){
            $data=$this->poyarndyeing
            ->selectRaw('
              po_yarn_dyeings.id as po_yarn_dyeing_id,
              po_yarn_dyeings.po_no as yarn_dyeing_po_no,
              po_yarn_dyeings.supplier_id,
              po_yarn_dyeings.company_id,
              po_yarn_dyeings.currency_id,
              po_yarn_dyeings.delv_start_date,
              po_yarn_dyeings.delv_end_date,
              po_yarn_dyeings.pi_no,
              suppliers.name as yarn_dyeing_supplier_name,
              suppliers.address as yarn_dyeing_supplier_address,
              companies.name as yarn_dyeing_company,
              po_yarn_dyeings.amount as yarn_dyeing_amount,
              agreement_pos.id as agreement_po_id
            ')
            ->join('companies',function($join){
              $join->on('companies.id','=','po_yarn_dyeings.company_id');
            })
            ->join('suppliers',function($join){
              $join->on('suppliers.id','=','po_yarn_dyeings.supplier_id');
            })
            ->leftJoin('currencies',function($join){
              $join->on('currencies.id','=','po_yarn_dyeings.currency_id');
            })
            ->leftJoin('agreement_pos',function($join){
              $join->on('agreement_pos.purchase_order_id','=','po_yarn_dyeings.id');
              $join->where([['agreement_pos.agreement_id','=',request('agreement_id',0)]]);
            })
            ->when(request('company_id'), function ($q) {
              return $q->where('po_yarn_dyeings.company_id','=',request('company_id', 0));
            })   
            ->when(request('po_no'), function ($q) {
              return $q->where('po_yarn_dyeings.po_no','=',request('po_no', 0));
            })
            ->where([['po_yarn_dyeings.supplier_id','=',$agreement->supplier_id]])
            ->orderBy('po_yarn_dyeings.id')
            ->groupBy([
              'po_yarn_dyeings.id',
              'po_yarn_dyeings.po_no',
              'po_yarn_dyeings.supplier_id',
              'po_yarn_dyeings.company_id',
              'po_yarn_dyeings.currency_id',
              'po_yarn_dyeings.delv_start_date',
              'po_yarn_dyeings.delv_end_date',
              'po_yarn_dyeings.pi_no',
              'suppliers.name',
              'suppliers.address',
              'companies.name',
              'po_yarn_dyeings.amount',
              'agreement_pos.id'
            ])
            ->get()
            ->map(function ($data) {
                $data->purchase_order_id=$data->po_yarn_dyeing_id;
                $data->po_no=$data->yarn_dyeing_po_no;
                $data->company_name=$data->yarn_dyeing_company;
                $data->supplier_name=$data->yarn_dyeing_supplier_name;
                $data->supplier_contact=$data->yarn_dyeing_supplier_address;
                $data->amount=$data->yarn_dyeing_amount;
                return $data;
            });
            $notsaved = $data->filter(function ($value) {
              if(!$value->agreement_po_id){
                  return $value;
              }
            });
            $po=array();
            foreach($notsaved as $row){
                array_push($po,$row);
            }
            echo json_encode($po);
        }
        // //Embelishment Purchase Order
        if($menu_id==10){
            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
            $purchaseorder =$this->poembservice
            ->selectRaw('
              po_emb_services.id as po_emb_service_id,
              po_emb_services.po_no as emb_service_po_no,
              po_emb_services.po_date,
              po_emb_services.company_id,
              po_emb_services.supplier_id,
              po_emb_services.currency_id,
              po_emb_services.exch_rate,
              po_emb_services.delv_start_date,
              po_emb_services.delv_end_date,
              po_emb_services.pi_no,
              po_emb_services.remarks,
              companies.name as emb_company,
              currencies.code as currency_name,
              suppliers.name as emb_supplier_name,
              suppliers.address as emb_supplier_address,
              po_emb_services.amount as emb_service_amount,
              agreement_pos.id as agreement_po_id
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
            ->leftJoin('agreement_pos',function($join){
              $join->on('agreement_pos.purchase_order_id','=','po_emb_services.id');
              $join->where([['agreement_pos.agreement_id','=',request('agreement_id',0)]]);
            })
            ->when(request('company_id'), function ($q) {
              return $q->where('po_emb_services.company_id','=',request('company_id', 0));
            })   
            ->when(request('po_no'), function ($q) {
              return $q->where('po_emb_services.po_no','=',request('po_no', 0));
            })
            ->where([['po_emb_services.supplier_id','=',$agreement->supplier_id]])
            ->orderBy('po_emb_services.id')
            ->get()
            ->map(function($purchaseorder){
                $purchaseorder->purchase_order_id=$purchaseorder->po_emb_service_id;
                $purchaseorder->po_no=$purchaseorder->emb_service_po_no;
                $purchaseorder->company_name=$purchaseorder->emb_company;
                $purchaseorder->supplier_name=$purchaseorder->emb_supplier_name;
                $purchaseorder->supplier_contact=$purchaseorder->emb_supplier_address;                
                $purchaseorder->amount=$purchaseorder->emb_service_amount;                
              return $purchaseorder;
            });

            $notsaved = $purchaseorder->filter(function ($value) {
              if(!$value->agreement_po_id){
                  return $value;
              }
            });
            $po=array();
            foreach($notsaved as $row){
                array_push($po,$row);
            }
            echo json_encode($po);
        }
    }

}
