<?php

namespace App\Http\Controllers\Inventory\Yarn;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnRcvRequest;

class InvYarnRcvController extends Controller {

    private $invrcv;
    private $invyarnrcv;
    private $company;
    private $location;
    private $currency;
    private $supplier;
    private $store;
    private $itemaccount;

    public function __construct(
        InvRcvRepository $invrcv,
        InvYarnRcvRepository $invyarnrcv, 
        CompanyRepository $company, 
        LocationRepository $location,
        CurrencyRepository $currency,
        SupplierRepository $supplier,
        StoreRepository $store,
        ItemAccountRepository $itemaccount
    ) {
        $this->invrcv = $invrcv;
        $this->invyarnrcv = $invyarnrcv;
        $this->company = $company;
        $this->location = $location;
        $this->currency = $currency;
        $this->supplier = $supplier;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        //$this->middleware('permission:view.invyarnrcv',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invyarnrcv', ['only' => ['store']]);
        //$this->middleware('permission:edit.invyarnrcv',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invyarnrcv', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $company = array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
       $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
       $supplier = array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
       $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
       $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');
       $invyarnrcvs = array();
       $rows = $this->invrcv
       ->join('inv_yarn_rcvs',function($join){
        $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
       })
       ->where([['inv_rcvs.menu_id','=',100]])
       ->orderBy('inv_rcvs.id','desc')
       ->get(['inv_rcvs.*','inv_yarn_rcvs.id as inv_yarn_rcv_id']);
       foreach ($rows as $row) {
            $invyarnrcv['id']=$row->id;
            $invyarnrcv['inv_yarn_rcv_id']=$row->inv_yarn_rcv_id;
            $invyarnrcv['receive_basis_id']=$invreceivebasis[$row->receive_basis_id];
            $invyarnrcv['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:'';
            $invyarnrcv['supplier_id']=isset($supplier[$row->supplier_id])?$supplier[$row->supplier_id]:'';
            $invyarnrcv['receive_no']=$row->receive_no;
            $invyarnrcv['challan_no']=$row->challan_no;
            $invyarnrcv['receive_date']=date('d-M-Y',strtotime($row->receive_date));
            array_push($invyarnrcvs, $invyarnrcv);
        }
        echo json_encode($invyarnrcvs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $invreceivebasis=array_prepend(array_only(config('bprs.invreceivebasis'),[1,2,3]),'-Select-','');
      $menu=array_prepend(array_only(config('bprs.menu'),[0,3,9]),'-Select-','');

      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->yarnSupplier(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.Yarn.InvYarnRcv',['company'=>$company,'currency'=>$currency, 'invreceivebasis'=>$invreceivebasis,'supplier'=>$supplier,'store'=>$store,'menu'=>$menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvYarnRcvRequest $request) {
      $max=$this->invrcv
      ->where([['company_id','=',$request->company_id]])
      //->where([['menu_id','=',100]])
      ->whereIn('menu_id',[100,105,106,108])
      ->max('receive_no');
      $receive_no=$max+1;

      if($request->receive_basis_id==2 || $request->receive_basis_id==3){
        $request->receive_against_id=3;
      }

      if($request->receive_against_id==9){
        $request->receive_basis_id=1;
      }

      $invrcv=$this->invrcv->create([
        'menu_id'=>100,
        'receive_no'=>$receive_no,
        'company_id'=>$request->company_id,
        'receive_basis_id'=>$request->receive_basis_id,
        'receive_against_id'=>$request->receive_against_id,
        'supplier_id'=>$request->supplier_id,
        'challan_no'=>$request->challan_no,
        'receive_date'=>$request->receive_date,
        'currency_id'=>$request->currency_id,
        'exch_rate'=>$request->exch_rate
      ]);

      $invyarnrcv=$this->invyarnrcv->create([
        'menu_id'=>100,
        'inv_rcv_id'=>$invrcv->id,
      ]);
      if($invyarnrcv){
        return response()->json(array('success' =>true ,'id'=>$invyarnrcv->id, 'receive_no'=>$receive_no,'message'=>'Saved Successfully'),200);
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
        $invyarnrcv = $this->invrcv
        ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->where([['inv_rcvs.id','=',$id]])
        ->get([
            'inv_rcvs.*',
            'inv_yarn_rcvs.id  as inv_yarn_rcv_id'
        ])
        ->first();
        $row ['fromData'] = $invyarnrcv;
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
    public function update(InvYarnRcvRequest $request, $id) {
        $invyarnrcv=$this->invrcv->update($id,$request->except(['id','inv_yarn_rcv_id','company_id','receive_basis_id','receive_against_id','supplier_id']));
        if($invyarnrcv){
            return response()->json(array('success'=> true, 'id' =>$id, 'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        return response()->json(array('success'=>false,'message'=>'Deleted Not Successfully'),200);

        if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }

    public function getPdf()
    {

      $id=request('id',0);
      $invreceivebasis=array_prepend(config('bprs.invreceivebasis'),'-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');      
      $rows=$this->invrcv
      ->join('inv_yarn_rcvs',function($join){
      $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
      })
      ->join('companies',function($join){
      $join->on('companies.id','=','inv_rcvs.company_id');
      })
      
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','inv_rcvs.supplier_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','inv_rcvs.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->where([['inv_rcvs.id','=',$id]])
      ->get([
      'inv_rcvs.*',
      'companies.name as company_name',
      'companies.logo as logo',
      'companies.address as company_address',
      'suppliers.name as supplier_name',
      'suppliers.address as supplier_address',
      'suppliers.contact_person',
      'suppliers.designation',
      'suppliers.email',
      'users.name as user_name',
      'employee_h_rs.contact'
      ])
      ->first();
        $receive_against_id=$rows->receive_against_id;
        $rows->receive_basis_id=$invreceivebasis[$rows->receive_basis_id];
        $rows->receive_against_id=$menu[$rows->receive_against_id];
        $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
        $rows->contact_detail=$rows->contact_person.','.$rows->designation.','.$rows->email;



        

      $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })

        ->where([['itemcategories.identity','=',1]])
        ->orderBy('item_account_ratios.ratio','desc')
        ->get([
        'item_accounts.id',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);

        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
        }
        //echo $rows->receive_against_id."nnnn";die;

        if($receive_against_id==9){
           $lc = \DB::select("
          select
          inv_rcvs.id,
          inv_yarn_rcvs.id as inv_yarn_rcv_id,
          imp_lcs.id as imp_lc_id,
          imp_lcs.lc_no_i,
          imp_lcs.lc_no_ii,
          imp_lcs.lc_no_iii,
          imp_lcs.lc_no_iv
          from
          inv_rcvs
          join inv_yarn_rcvs on inv_yarn_rcvs.inv_rcv_id=inv_rcvs.id
          join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_rcv_id=inv_yarn_rcvs.id
          join po_yarn_items on po_yarn_items.id=inv_yarn_rcv_items.po_yarn_item_id
          join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
          join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
          join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
          where inv_rcvs.id=".$id." and imp_lcs.menu_id=9 
          group by 
          inv_rcvs.id,
          inv_yarn_rcvs.id,
          imp_lcs.id,
          imp_lcs.lc_no_i,
          imp_lcs.lc_no_ii,
          imp_lcs.lc_no_iii,
          imp_lcs.lc_no_iv
          ");
          $lc=collect($lc)->first();

            $invyarnrcvitem=$this->invrcv
            ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
            })
            ->join('inv_yarn_rcv_items',function($join){
            $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
            })
            ->join('inv_yarn_isu_items',function($join){
            $join->on('inv_yarn_isu_items.id','=','inv_yarn_rcv_items.inv_yarn_isu_item_id');
            })
            ->join('po_yarn_dyeing_item_bom_qties',function($join){
            $join->on('po_yarn_dyeing_item_bom_qties.id','=','inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id');
            })
            ->join('po_yarn_dyeing_items',function($join){
            $join->on('po_yarn_dyeing_items.id','=','po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id');
            })
            ->join('po_yarn_dyeings',function($join){
            $join->on('po_yarn_dyeings.id','=','po_yarn_dyeing_items.po_yarn_dyeing_id');
            })

            ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
            })

            ->join('item_accounts',function($join){
            $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
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
            ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_yarn_dyeings.currency_id');
            })
            ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id');
            })
           /* ->leftJoin('imp_lc_pos',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_yarn_dyeings.id');
            })
            ->leftJoin('imp_lcs',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
            //$join->on('imp_lcs.menu_id','=',9);
            })*/
            ->where([['inv_rcvs.id','=',$id]])
            //->where([['imp_lcs.menu_id','=',9]])
            ->where([['inv_rcvs.receive_against_id','=',9]])
            ->orderBy('inv_yarn_rcv_items.id','desc')
            
            ->get([
            'po_yarn_dyeings.po_no',
            'po_yarn_dyeings.pi_no',
            'po_yarn_dyeings.exch_rate',
            //'imp_lcs.lc_no_i',
            //'imp_lcs.lc_no_ii',
            //'imp_lcs.lc_no_iii',
            //'imp_lcs.lc_no_iv',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',
            'po_yarn_dyeing_items.id as po_yarn_item_id',
            'currencies.code as currency_code',
            'inv_yarn_rcv_items.id',
            'inv_yarn_items.lot',
            'inv_yarn_items.brand',
            'colors.name as color_name',
            'inv_yarn_rcv_items.qty',
            'inv_yarn_rcv_items.rate',
            'inv_yarn_rcv_items.amount',
            'inv_yarn_rcv_items.cone_per_bag',
            'inv_yarn_rcv_items.wgt_per_cone',
            'inv_yarn_rcv_items.no_of_bag',
            'inv_yarn_rcv_items.remarks',
            'inv_yarn_rcv_items.store_qty',
            'uoms.code as uom',
            'inv_yarn_rcv_items.store_rate',
            'inv_yarn_rcv_items.store_amount',
            'inv_rcvs.receive_basis_id',
            ])
            ->map(function($invyarnrcvitem) use($yarnDropdown,$lc) {

            if($lc){
            $invyarnrcvitem->lc_no= $lc->lc_no_i." ".$lc->lc_no_ii." ".$lc->lc_no_iii." ".$lc->lc_no_iv;
            }
            else{
            $invyarnrcvitem->lc_no='';
            }
            $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
            $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
            $invyarnrcvitem->wgt_per_bag=$invyarnrcvitem->cone_per_bag*$invyarnrcvitem->wgt_per_cone;
            $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
            if($invyarnrcvitem->receive_basis_id==1){
            $invyarnrcvitem->po_no=$invyarnrcvitem->po_no; 
            }else{
            $invyarnrcvitem->po_no=''; 
            }
            return $invyarnrcvitem;
            });

        }
        else{
          $lc = \DB::select("
          select
          inv_rcvs.id,
          inv_yarn_rcvs.id as inv_yarn_rcv_id,
          imp_lcs.id as imp_lc_id,
          imp_lcs.lc_no_i,
          imp_lcs.lc_no_ii,
          imp_lcs.lc_no_iii,
          imp_lcs.lc_no_iv
          from
          inv_rcvs
          join inv_yarn_rcvs on inv_yarn_rcvs.inv_rcv_id=inv_rcvs.id
          join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_rcv_id=inv_yarn_rcvs.id
          join po_yarn_items on po_yarn_items.id=inv_yarn_rcv_items.po_yarn_item_id
          join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
          join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
          join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
          where inv_rcvs.id=".$id." and imp_lcs.menu_id=3 
          group by 
          inv_rcvs.id,
          inv_yarn_rcvs.id,
          imp_lcs.id,
          imp_lcs.lc_no_i,
          imp_lcs.lc_no_ii,
          imp_lcs.lc_no_iii,
          imp_lcs.lc_no_iv
          ");
          $lc=collect($lc)->first();


          $invyarnrcvitem=$this->invrcv
          ->join('inv_yarn_rcvs',function($join){
          $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
          })
          ->join('inv_yarn_rcv_items',function($join){
          $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id')
          ->whereNull('inv_yarn_rcv_items.deleted_at');
          })
          ->join('po_yarn_items',function($join){
          $join->on('po_yarn_items.id','=','inv_yarn_rcv_items.po_yarn_item_id');
          })
          ->join('po_yarns',function($join){
          $join->on('po_yarns.id','=','po_yarn_items.po_yarn_id');
          })

          ->join('inv_yarn_items',function($join){
          $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
          ->whereNull('inv_yarn_rcv_items.deleted_at');
          })

          ->join('item_accounts',function($join){
          $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
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
          ->join('itemcategories',function($join){
          $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
          })
          ->join('uoms',function($join){
          $join->on('uoms.id','=','item_accounts.uom_id');
          })
          ->join('currencies',function($join){
          $join->on('currencies.id','=','po_yarns.currency_id');
          })
          ->join('colors',function($join){
          $join->on('colors.id','=','inv_yarn_items.color_id');
          })
          /*->leftJoin('imp_lc_pos',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_yarns.id');
          })
          ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          //$join->on('imp_lcs.menu_id','=',3);
          })*/
          ->where([['inv_rcvs.id','=',$id]])
          //->where([['imp_lcs.menu_id','=',3]])
          ->orderBy('inv_yarn_rcv_items.id','desc')
          ->get([
          'po_yarns.po_no',
          'po_yarns.pi_no',
          'po_yarns.exch_rate',
          //'imp_lcs.lc_no_i',
          //'imp_lcs.lc_no_ii',
          //'imp_lcs.lc_no_iii',
          //'imp_lcs.lc_no_iv',
          'itemcategories.name as itemcategory_name',
          'itemclasses.name as itemclass_name',
          'item_accounts.id as item_account_id',
          'yarncounts.count',
          'yarncounts.symbol',
          'yarntypes.name as yarn_type',
          'uoms.code as uom_code',
          'po_yarn_items.id as po_yarn_item_id',
          'currencies.code as currency_code',
          'inv_yarn_rcv_items.id',
          'inv_yarn_items.lot',
          'inv_yarn_items.brand',
          'colors.name as color_name',
          'inv_yarn_rcv_items.qty',
          'inv_yarn_rcv_items.rate',
          'inv_yarn_rcv_items.amount',
          'inv_yarn_rcv_items.cone_per_bag',
          'inv_yarn_rcv_items.wgt_per_cone',
          'inv_yarn_rcv_items.no_of_bag',
          'inv_yarn_rcv_items.remarks',
          'inv_yarn_rcv_items.store_qty',
          'uoms.code as uom',
          'inv_yarn_rcv_items.store_rate',
          'inv_yarn_rcv_items.store_amount',
          'inv_rcvs.receive_basis_id',
          ])
          ->map(function($invyarnrcvitem) use($yarnDropdown,$lc) {
          if($lc){
          $invyarnrcvitem->lc_no= $lc->lc_no_i." ".$lc->lc_no_ii." ".$lc->lc_no_iii." ".$lc->lc_no_iv;
          }
          else{
          $invyarnrcvitem->lc_no='';
          }
          $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
          $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
          $invyarnrcvitem->wgt_per_bag=$invyarnrcvitem->cone_per_bag*$invyarnrcvitem->wgt_per_cone;
          $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
          if($invyarnrcvitem->receive_basis_id==1){
          $invyarnrcvitem->po_no=$invyarnrcvitem->po_no; 
          }else{
          $invyarnrcvitem->po_no=''; 
          }
          return $invyarnrcvitem;
          });

        } 

      



      
      


      
      //$amount=$data->sum('amount');
      //$inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      //$rows->inword          =$inword;
      $data['master']    =$rows;
      $data['details']   =$invyarnrcvitem;

      
     

      $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      $pdf->SetY(10);
      //$txt = "Trim Purchase Order";
      //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(13);
      $pdf->SetFont('helvetica', 'N', 8);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      //$pdf->Text(115, 12, $rows->company_address);
      //$pdf->Write(0, $rows->company_address, '', 0, 'C', true, 0, false, false, 0);

      $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetY(5);
        $pdf->SetX(200);
        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

      $pdf->SetY(36);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, 'Yarn Receiving Report ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Yarn Receiving Report');
      $view= \View::make('Defult.Inventory.Yarn.YarnRcvPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(46);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/PoKnitServicePdf.pdf';
      $pdf->output($filename);

    }

    
    public function getStorePdf(){
      $id=request('id',0);
      $invreceivebasis=array_prepend(config('bprs.invreceivebasis'),'-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');      
      $rows=$this->invrcv
      ->join('inv_yarn_rcvs',function($join){
      $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
      })
      ->join('companies',function($join){
      $join->on('companies.id','=','inv_rcvs.company_id');
      })
      
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','inv_rcvs.supplier_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','inv_rcvs.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->where([['inv_rcvs.id','=',$id]])
      ->get([
      'inv_rcvs.*',
      'companies.name as company_name',
      'companies.logo as logo',
      'companies.address as company_address',
      'suppliers.name as supplier_name',
      'suppliers.address as supplier_address',
      'suppliers.contact_person',
      'suppliers.designation',
      'suppliers.email',
      'users.name as user_name',
      'employee_h_rs.contact'
      ])
      ->first();
        $receive_against_id=$rows->receive_against_id;
        $rows->receive_basis_id=$invreceivebasis[$rows->receive_basis_id];
        $rows->receive_against_id=$menu[$rows->receive_against_id];
        $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
        $rows->contact_detail=$rows->contact_person.','.$rows->designation.','.$rows->email;



        

      $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->leftJoin('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })

        ->where([['itemcategories.identity','=',1]])
        ->orderBy('item_account_ratios.ratio','desc')
        ->get([
        'item_accounts.id',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);

        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }

        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
        }
        //echo $rows->receive_against_id."nnnn";die;

        if($receive_against_id==9){
          $lc = \DB::select("
          select
          inv_rcvs.id,
          inv_yarn_rcvs.id as inv_yarn_rcv_id,
          imp_lcs.id as imp_lc_id,
          imp_lcs.lc_no_i,
          imp_lcs.lc_no_ii,
          imp_lcs.lc_no_iii,
          imp_lcs.lc_no_iv
          from
          inv_rcvs
          join inv_yarn_rcvs on inv_yarn_rcvs.inv_rcv_id=inv_rcvs.id
          join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_rcv_id=inv_yarn_rcvs.id
          join po_yarn_items on po_yarn_items.id=inv_yarn_rcv_items.po_yarn_item_id
          join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
          join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
          join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
          where inv_rcvs.id=".$id." and imp_lcs.menu_id=9 
          group by 
          inv_rcvs.id,
          inv_yarn_rcvs.id,
          imp_lcs.id,
          imp_lcs.lc_no_i,
          imp_lcs.lc_no_ii,
          imp_lcs.lc_no_iii,
          imp_lcs.lc_no_iv
          ");
          $lc=collect($lc)->first();

            $invyarnrcvitem=$this->invrcv
            ->join('inv_yarn_rcvs',function($join){
            $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
            })
            ->join('inv_yarn_rcv_items',function($join){
            $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
            })
            ->join('inv_yarn_isu_items',function($join){
            $join->on('inv_yarn_isu_items.id','=','inv_yarn_rcv_items.inv_yarn_isu_item_id');
            })
            ->join('po_yarn_dyeing_item_bom_qties',function($join){
            $join->on('po_yarn_dyeing_item_bom_qties.id','=','inv_yarn_isu_items.po_yarn_dyeing_item_bom_qty_id');
            })
            ->join('po_yarn_dyeing_items',function($join){
            $join->on('po_yarn_dyeing_items.id','=','po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id');
            })
            ->join('po_yarn_dyeings',function($join){
            $join->on('po_yarn_dyeings.id','=','po_yarn_dyeing_items.po_yarn_dyeing_id');
            })

            ->join('inv_yarn_items',function($join){
            $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
            ->whereNull('inv_yarn_rcv_items.deleted_at');
            })

            ->join('item_accounts',function($join){
            $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
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
            ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
            })
            ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','po_yarn_dyeings.currency_id');
            })
            ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id');
            })
            /*->leftJoin('imp_lc_pos',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_yarn_dyeings.id');
            })
            ->leftJoin('imp_lcs',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
            //$join->on('imp_lcs.menu_id','=',9);
            })*/
            ->where([['inv_rcvs.id','=',$id]])
            //->where([['imp_lcs.menu_id','=',9]])
            ->where([['inv_rcvs.receive_against_id','=',9]])
            ->orderBy('inv_yarn_rcv_items.id','desc')
            
            ->get([
            'po_yarn_dyeings.po_no',
            'po_yarn_dyeings.pi_no',
            'po_yarn_dyeings.exch_rate',
            //'imp_lcs.lc_no_i',
            //'imp_lcs.lc_no_ii',
            //'imp_lcs.lc_no_iii',
            //'imp_lcs.lc_no_iv',
            'itemcategories.name as itemcategory_name',
            'itemclasses.name as itemclass_name',
            'item_accounts.id as item_account_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'uoms.code as uom_code',
            'po_yarn_dyeing_items.id as po_yarn_item_id',
            'currencies.code as currency_code',
            'inv_yarn_rcv_items.id',
            'inv_yarn_items.lot',
            'inv_yarn_items.brand',
            'colors.name as color_name',
            'inv_yarn_rcv_items.qty',
            'inv_yarn_rcv_items.rate',
            'inv_yarn_rcv_items.amount',
            'inv_yarn_rcv_items.cone_per_bag',
            'inv_yarn_rcv_items.wgt_per_cone',
            'inv_yarn_rcv_items.no_of_bag',
            'inv_yarn_rcv_items.remarks',
            'inv_yarn_rcv_items.store_qty',
            'uoms.code as uom',
            'inv_yarn_rcv_items.store_rate',
            'inv_yarn_rcv_items.store_amount',
            'inv_rcvs.receive_basis_id',
            ])
            ->map(function($invyarnrcvitem) use($yarnDropdown,$lc) {
            if($lc){
            $invyarnrcvitem->lc_no= $lc->lc_no_i." ".$lc->lc_no_ii." ".$lc->lc_no_iii." ".$lc->lc_no_iv;
            }
            else{
            $invyarnrcvitem->lc_no='';
            }
            $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
            $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
            $invyarnrcvitem->wgt_per_bag=$invyarnrcvitem->cone_per_bag*$invyarnrcvitem->wgt_per_cone;
            $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
            if($invyarnrcvitem->receive_basis_id==1){
            $invyarnrcvitem->po_no=$invyarnrcvitem->po_no; 
            }else{
            $invyarnrcvitem->po_no=''; 
            }
            return $invyarnrcvitem;
            });

        }
        else{
          $lc = \DB::select("
          select
          inv_rcvs.id,
          inv_yarn_rcvs.id as inv_yarn_rcv_id,
          imp_lcs.id as imp_lc_id,
          imp_lcs.lc_no_i,
          imp_lcs.lc_no_ii,
          imp_lcs.lc_no_iii,
          imp_lcs.lc_no_iv
          from
          inv_rcvs
          join inv_yarn_rcvs on inv_yarn_rcvs.inv_rcv_id=inv_rcvs.id
          join inv_yarn_rcv_items on inv_yarn_rcv_items.inv_yarn_rcv_id=inv_yarn_rcvs.id
          join po_yarn_items on po_yarn_items.id=inv_yarn_rcv_items.po_yarn_item_id
          join po_yarns on po_yarns.id=po_yarn_items.po_yarn_id
          join imp_lc_pos on imp_lc_pos.purchase_order_id=po_yarns.id
          join imp_lcs on imp_lcs.id=imp_lc_pos.imp_lc_id
          where inv_rcvs.id=".$id." and imp_lcs.menu_id=3 
          group by 
          inv_rcvs.id,
          inv_yarn_rcvs.id,
          imp_lcs.id,
          imp_lcs.lc_no_i,
          imp_lcs.lc_no_ii,
          imp_lcs.lc_no_iii,
          imp_lcs.lc_no_iv
          ");
          $lc=collect($lc)->first();

          $invyarnrcvitem=$this->invrcv
          ->join('inv_yarn_rcvs',function($join){
          $join->on('inv_yarn_rcvs.inv_rcv_id','=','inv_rcvs.id');
          })
          ->join('inv_yarn_rcv_items',function($join){
          $join->on('inv_yarn_rcv_items.inv_yarn_rcv_id','=','inv_yarn_rcvs.id')
          ->whereNull('inv_yarn_rcv_items.deleted_at');
          })
          ->join('po_yarn_items',function($join){
          $join->on('po_yarn_items.id','=','inv_yarn_rcv_items.po_yarn_item_id');
          })
          ->join('po_yarns',function($join){
          $join->on('po_yarns.id','=','po_yarn_items.po_yarn_id');
          })

          ->join('inv_yarn_items',function($join){
          $join->on('inv_yarn_items.id','=','inv_yarn_rcv_items.inv_yarn_item_id')
          ->whereNull('inv_yarn_rcv_items.deleted_at');
          })

          ->join('item_accounts',function($join){
          $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
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
          ->join('itemcategories',function($join){
          $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
          })
          ->join('uoms',function($join){
          $join->on('uoms.id','=','item_accounts.uom_id');
          })
          ->join('currencies',function($join){
          $join->on('currencies.id','=','po_yarns.currency_id');
          })
          ->join('colors',function($join){
          $join->on('colors.id','=','inv_yarn_items.color_id');
          })
          /*->leftJoin('imp_lc_pos',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_yarns.id');
          })
          ->leftJoin('imp_lcs',function($join){
          $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          //$join->on('imp_lcs.menu_id','=',3);
          })*/
          ->where([['inv_rcvs.id','=',$id]])
          //->where([['imp_lcs.menu_id','=',3]])
          ->orderBy('inv_yarn_rcv_items.id','desc')
          ->get([
          'po_yarns.po_no',
          'po_yarns.pi_no',
          'po_yarns.exch_rate',
          //'imp_lcs.lc_no_i',
          //'imp_lcs.lc_no_ii',
         // 'imp_lcs.lc_no_iii',
         // 'imp_lcs.lc_no_iv',
          'itemcategories.name as itemcategory_name',
          'itemclasses.name as itemclass_name',
          'item_accounts.id as item_account_id',
          'yarncounts.count',
          'yarncounts.symbol',
          'yarntypes.name as yarn_type',
          'uoms.code as uom_code',
          'po_yarn_items.id as po_yarn_item_id',
          'currencies.code as currency_code',
          'inv_yarn_rcv_items.id',
          'inv_yarn_items.lot',
          'inv_yarn_items.brand',
          'colors.name as color_name',
          'inv_yarn_rcv_items.qty',
          'inv_yarn_rcv_items.rate',
          'inv_yarn_rcv_items.amount',
          'inv_yarn_rcv_items.cone_per_bag',
          'inv_yarn_rcv_items.wgt_per_cone',
          'inv_yarn_rcv_items.no_of_bag',
          'inv_yarn_rcv_items.remarks',
          'inv_yarn_rcv_items.store_qty',
          'uoms.code as uom',
          'inv_yarn_rcv_items.store_rate',
          'inv_yarn_rcv_items.store_amount',
          'inv_rcvs.receive_basis_id',
          ])
          ->map(function($invyarnrcvitem) use($yarnDropdown,$lc) {
          if($lc){
          $invyarnrcvitem->lc_no= $lc->lc_no_i." ".$lc->lc_no_ii." ".$lc->lc_no_iii." ".$lc->lc_no_iv;
          }
          else{
          $invyarnrcvitem->lc_no='';
          }
          $invyarnrcvitem->yarn_count=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
          $invyarnrcvitem->yarn_type=$invyarnrcvitem->yarn_type;
          $invyarnrcvitem->wgt_per_bag=$invyarnrcvitem->cone_per_bag*$invyarnrcvitem->wgt_per_cone;
          $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
          if($invyarnrcvitem->receive_basis_id==1){
          $invyarnrcvitem->po_no=$invyarnrcvitem->po_no; 
          }else{
          $invyarnrcvitem->po_no=''; 
          }
          return $invyarnrcvitem;
          });

        } 

    

      
      //$amount=$data->sum('amount');
      //$inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      //$rows->inword          =$inword;
      $data['master']    =$rows;
      $data['details']   =$invyarnrcvitem;

      
     

      $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      $pdf->SetY(10);
      //$txt = "Trim Purchase Order";
      //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(13);
      $pdf->SetFont('helvetica', 'N', 8);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      // $pdf->Text(115, 12, $rows->company_address);
      //$pdf->Write(0, $rows->company_address, '', 0, 'C', true, 0, false, false, 0);

      $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetY(5);
        $pdf->SetX(210);
        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

      $pdf->SetY(36);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, 'Yarn Receiving Report ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Yarn Receiving Report');
      $view= \View::make('Defult.Inventory.Yarn.YarnRcvStorePdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(46);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/YarnRcvStorePdf.pdf';
      $pdf->output($filename);
    }
}