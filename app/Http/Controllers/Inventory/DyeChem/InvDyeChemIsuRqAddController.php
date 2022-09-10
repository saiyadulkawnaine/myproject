<?php

namespace App\Http\Controllers\Inventory\DyeChem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuRqAddRequest;

class InvDyeChemIsuRqAddController extends Controller {

    private $invdyechemisurq;
    private $company;
    private $buyer;
    private $location;
    private $itemaccount;
    private $autoyarn;
    private $colorrange;
    private $prodbatch;
    private $color;

    public function __construct(
        InvDyeChemIsuRqRepository $invdyechemisurq, 
        CompanyRepository $company, 
        BuyerRepository $buyer, 
        LocationRepository $location,
        ItemAccountRepository $itemaccount,
        AutoyarnRepository $autoyarn,
        ColorrangeRepository $colorrange,
        ProdBatchRepository $prodbatch,
        ColorRepository $color
    ) {
        $this->invdyechemisurq = $invdyechemisurq;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->itemaccount = $itemaccount;
        $this->autoyarn = $autoyarn;
        $this->colorrange = $colorrange;
        $this->prodbatch = $prodbatch;
        $this->color = $color;
        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemisurqadd',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisurqadd', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisurqadd',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisurqadd', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $rows = $this->invdyechemisurq
       ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->join('inv_dye_chem_isu_rqs as rootrq',function($join){
        $join->on('rootrq.id','=','inv_dye_chem_isu_rqs.root_id');
       })
       ->where([['inv_dye_chem_isu_rqs.menu_id','=',209]])
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'prod_batches.colorrange_id',
        'prod_batches.batch_no',
        'prod_batches.lap_dip_no',
        'prod_batches.batch_wgt',
        'companies.code as company_id',
        'locations.name as location_id',
        'rootrq.rq_no as root_rq_no',
       ])
       ->take(100)
       ->map(function($rows){
        return $rows;
       });
      echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $location = array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
      $buyer = array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'), '-Select-','');

      return Template::loadView('Inventory.DyeChem.InvDyeChemIsuRqAdd',['company'=>$company,'location'=>$location, 'buyer'=>$buyer, 'colorrange'=>$colorrange,'dyeingsubprocess'=>$dyeingsubprocess]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvDyeChemIsuRqAddRequest $request) {
      $rootrq=$this->invdyechemisurq->find(request('root_id',0));
      $max=$this->invdyechemisurq
      ->where([['company_id','=',$rootrq->company_id]])
      ->max('rq_no');
      $rq_no=$max+1;
      
      
      $invdyechemisurq=$this->invdyechemisurq->create([
        'rq_no'=>$rq_no,
        'menu_id'=>209,
        'root_id'=>$rootrq->id,
        'company_id'=>$rootrq->company_id,
        'rq_basis_id'=>2,
        'location_id'=>$rootrq->location_id,
        'prod_batch_id'=>$rootrq->prod_batch_id,
        'fabrication_id'=>$rootrq->fabrication_id,
        'fabric_desc'=>$rootrq->fabric_desc,
        'buyer_id'=>$rootrq->buyer_id,
        'liqure_ratio'=>$rootrq->liqure_ratio,
        'liqure_wgt'=>$rootrq->liqure_wgt,
        'rq_date'=>$request->rq_date,
        'remarks'=>$request->remarks,
      ]);
      

     
      if($invdyechemisurq){
        return response()->json(array('success' =>true ,'id'=>$invdyechemisurq->id, 'rq_no'=>$rq_no,'message'=>'Saved Successfully'),200);
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
        $rows = $this->invdyechemisurq
       ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
       })
       ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','inv_dye_chem_isu_rqs.buyer_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->join('inv_dye_chem_isu_rqs as rootrq',function($join){
        $join->on('rootrq.id','=','inv_dye_chem_isu_rqs.root_id');
       })
       ->where([['inv_dye_chem_isu_rqs.id','=',$id]])
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'prod_batches.colorrange_id',
        'prod_batches.batch_no',
        'prod_batches.lap_dip_no',
        'prod_batches.batch_wgt',
        'prod_batches.company_id',
        'prod_batches.location_id',
        'rootrq.rq_no as root_rq_no',
       ])
       ->map(function($rows){
        return $rows;
       })->first();
        $row ['fromData'] = $rows;
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
    public function update(InvDyeChemIsuRqAddRequest $request, $id) {
      $rootrq=$this->invdyechemisurq->find(request('root_id',0));
      $invdyechemisurq=$this->invdyechemisurq->update($id,[
        //'root_id'=>$rootrq->id,
        //'location_id'=>$rootrq->location_id,
        //'fabrication_id'=>$rootrq->fabrication_id,
        //'fabric_desc'=>$rootrq->fabric_desc,
        //'buyer_id'=>$rootrq->buyer_id,
        'rq_date'=>$request->rq_date,
        //'liqure_ratio'=>$rootrq->liqure_ratio,
        //'liqure_wgt'=>$rootrq->liqure_wgt,
        'remarks'=>$request->remarks,
      ]);
      

     
      if($invdyechemisurq){
        return response()->json(array('success' =>true ,'id'=>$id, 'message'=>'Saved Successfully'),200);
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
        return response()->json(array('success'=>false,'message'=>'Deleted not Successfully'),200);
        if($this->invdyechemisurq->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }

    public function getRequisition()
    {

      $rows = $this->invdyechemisurq
       ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->when(request('rq_no'), function ($q) {
      return $q->where('inv_dye_chem_isu_rqs.rq_no', '=', request('rq_no', 0));
      })
      ->when(request('company_id'), function ($q) {
      return $q->where('inv_dye_chem_isu_rqs.company_id', '=', request('company_id', 0));
      })
      ->when(request('batch_no'), function ($q) {
      return $q->where('prod_batches.batch_no', '=', request('batch_no', 0));
      })
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'prod_batches.colorrange_id',
        'prod_batches.batch_no',
        'prod_batches.lap_dip_no',
        'prod_batches.batch_wgt',
        'prod_batches.company_id',
        'prod_batches.location_id',
        
        'companies.code as company_name',
        'locations.name as location_name',
       ])
       ->map(function($rows){
        return $rows;
       });
      echo json_encode($rows);
    }

    public function getRq() {
       $rows = $this->invdyechemisurq
       ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->join('inv_dye_chem_isu_rqs as rootrq',function($join){
        $join->on('rootrq.id','=','inv_dye_chem_isu_rqs.root_id');
       })
       ->where([['inv_dye_chem_isu_rqs.menu_id','=',209]])
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->when(request('from_rq_date'), function ($q) {
        return $q->where('inv_dye_chem_isu_rqs.rq_date', '>=', request('from_rq_date', 0));
        })
        ->when(request('to_rq_date'), function ($q) {
        return $q->where('inv_dye_chem_isu_rqs.rq_date', '<=', request('to_rq_date', 0));
        })
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'prod_batches.colorrange_id',
        'prod_batches.batch_no',
        'prod_batches.lap_dip_no',
        'prod_batches.batch_wgt',
        'companies.code as company_id',
        'locations.name as location_id',
        'rootrq.rq_no as root_rq_no',
       ])
       ->map(function($rows){
        return $rows;
       });
      echo json_encode($rows);
    }

    public function getPdf()
    {
      $id=request('id',0);
      $invdyechemisurq=$this->invdyechemisurq->find($id);
      $prodbatch=$this->prodbatch->find($invdyechemisurq->prod_batch_id);
      $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'),'-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');

      $rows=$this->invdyechemisurq 
      ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
       })
       ->leftJoin('asset_quantity_costs',function($join){
        $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
       ->join('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->join('colors batch_colors',function($join){
        $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
       ->join('users',function($join){
      $join->on('users.id','=','inv_dye_chem_isu_rqs.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->where([['inv_dye_chem_isu_rqs.id','=',$id]])
      ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'prod_batches.colorrange_id',
        'prod_batches.batch_no',
        'prod_batches.lap_dip_no',
        'prod_batches.batch_wgt',
        'prod_batches.machine_id',
        'asset_quantity_costs.custom_no as machine_no',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'locations.name as location_id',
        'users.name as user_name',
        'batch_colors.name as batch_color',
      'employee_h_rs.contact'
       ])
       ->first();
        $rows->rq_date=date('d-M-Y',strtotime($rows->rq_date));

        $invdyechemisurqitem=$this->invdyechemisurq
        ->join('inv_dye_chem_isu_rq_items',function($join){
          $join->on('inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id','=','inv_dye_chem_isu_rqs.id');
        })
        ->join('item_accounts',function($join){
          $join->on('inv_dye_chem_isu_rq_items.item_account_id','=','item_accounts.id');
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
        ->leftJoin(\DB::raw("(SELECT 
          inv_dye_chem_transactions.item_account_id,
          sum(inv_dye_chem_transactions.store_qty) as qty 
          FROM inv_dye_chem_transactions 
          where  inv_dye_chem_transactions.deleted_at is null
          group by inv_dye_chem_transactions.item_account_id
          ) stock"), "stock.item_account_id", "=", "item_accounts.id")
        ->leftJoin(\DB::raw("(SELECT 
          inv_dye_chem_transactions.item_account_id,
          sum(inv_dye_chem_transactions.store_amount) as receive_amount 
          FROM inv_dye_chem_transactions 
          where trans_type_id=1 and 
          inv_dye_chem_transactions.deleted_at is null
          group by inv_dye_chem_transactions.item_account_id
          ) receives"), "receives.item_account_id", "=", "item_accounts.id")

        ->leftJoin(\DB::raw("(SELECT 
          inv_dye_chem_transactions.item_account_id,
          sum(inv_dye_chem_transactions.store_amount) as issue_amount 
          FROM inv_dye_chem_transactions 
          where trans_type_id=2 and 
          inv_dye_chem_transactions.deleted_at is null
          group by inv_dye_chem_transactions.item_account_id
          ) issues"), "issues.item_account_id", "=", "item_accounts.id")
        ->where([['inv_dye_chem_isu_rqs.id','=',$id]])
        ->orderBy('inv_dye_chem_isu_rq_items.id')
        ->orderBy('inv_dye_chem_isu_rq_items.sort_id')
        ->get([
        'inv_dye_chem_isu_rq_items.*',
        'itemcategories.name as category_name',
        'itemclasses.name as class_name',
        'item_accounts.sub_class_name',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code as uom_name',
        'stock.qty as stock_qty',
        'receives.receive_amount',
        'issues.issue_amount',
        ]) 
        ->map(function($invdyechemisurqitem) use ($dyeingsubprocess){
          $invdyechemisurqitem->sub_process_name=$dyeingsubprocess[$invdyechemisurqitem->sub_process_id];
          $invdyechemisurqitem->ratio='';
          if($invdyechemisurqitem->per_on_batch_wgt){
          $invdyechemisurqitem->ratio=$invdyechemisurqitem->per_on_batch_wgt.' % on Batch Wgt';
          }
          else if ($invdyechemisurqitem->gram_per_ltr_liqure){
          $invdyechemisurqitem->ratio=$invdyechemisurqitem->gram_per_ltr_liqure.' Gram/L. Liqure';
          }
          $invdyechemisurqitem->stock_amount=$invdyechemisurqitem->receive_amount-$invdyechemisurqitem->issue_amount;
          $invdyechemisurqitem->stock_rate=0;
          if($invdyechemisurqitem->stock_qty){
          $invdyechemisurqitem->stock_rate=number_format($invdyechemisurqitem->stock_amount/$invdyechemisurqitem->stock_qty,4);
          }
          return $invdyechemisurqitem;
        })
        ->groupBy('sub_process_name');
        if($prodbatch->batch_for==1){
            $prodknitqc=$this->prodbatch
              ->selectRaw('
                  prod_batches.id as prod_batch_id,
                  CASE 
                  WHEN  inhouseprods.sale_order_no IS NULL THEN outhouseprods.sale_order_no 
                  ELSE inhouseprods.sale_order_no
                  END as sale_order_no,
                  CASE 
                  WHEN  inhouseprods.style_ref IS NULL THEN outhouseprods.style_ref 
                  ELSE inhouseprods.style_ref
                  END as style_ref,
  
                  CASE 
                  WHEN  inhouseprods.buyer_name IS NULL THEN outhouseprods.buyer_name 
                  ELSE inhouseprods.buyer_name
                  END as buyer_name,
  
                  CASE 
                  WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
                  ELSE inhouseprods.customer_name
                  END as customer_name
              ')
              ->join('prod_batch_rolls',function($join){
                  $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
              })
              ->join('so_dyeing_fabric_rcv_rols',function($join){
                  $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
              })
              ->join('so_dyeing_fabric_rcv_items',function($join){
                  $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
              })
              ->join('so_dyeing_refs',function($join){
                  $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
              })
              ->join('so_dyeings',function($join){
                  $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
              })
              ->join('buyers as customers',function($join){
                  $join->on('customers.id','=','so_dyeings.buyer_id');
              })
              ->leftJoin('so_dyeing_pos',function($join){
                  $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
              })
              ->join('so_dyeing_po_items',function($join){
                  $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
              })
              ->join('po_dyeing_service_item_qties',function($join){
                  $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
              })
              ->join('po_dyeing_service_items',function($join){
                  $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
              ->whereNull('po_dyeing_service_items.deleted_at');
              })
              ->join('sales_orders',function($join){
                  $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
              })
              ->join('jobs',function($join){
                  $join->on('jobs.id','=','sales_orders.job_id');
              })
              ->join('styles',function($join){
                  $join->on('styles.id','=','jobs.style_id');
              })
              ->join('buyers',function($join){
                  $join->on('buyers.id','=','styles.buyer_id');
              })
              ->join('inv_grey_fab_isu_items',function($join){
                  $join->on('inv_grey_fab_isu_items.id', '=', 'so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id');
              })
              ->join('inv_isus',function($join){
                  $join->on('inv_isus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
              })
              ->join('inv_grey_fab_items',function($join){
                  $join->on('inv_grey_fab_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_item_id');
              })
              ->join('inv_grey_fab_rcv_items',function($join){
                  $join->on('inv_grey_fab_rcv_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id');
              })
              ->join('inv_grey_fab_rcvs',function($join){
                  $join->on('inv_grey_fab_rcvs.id', '=', 'inv_grey_fab_rcv_items.inv_grey_fab_rcv_id');
              })
              ->join('inv_rcvs',function($join){
                  $join->on('inv_rcvs.id', '=', 'inv_grey_fab_rcvs.inv_rcv_id');
              })
              ->join('prod_knit_dlvs',function($join){
                  $join->on('prod_knit_dlvs.id', '=', 'inv_grey_fab_rcvs.prod_knit_dlv_id');
              })
              ->join('prod_knit_dlv_rolls',function($join){
                  $join->on('prod_knit_dlvs.id', '=', 'prod_knit_dlv_rolls.prod_knit_dlv_id');
                  $join->on('inv_grey_fab_rcv_items.prod_knit_dlv_roll_id', '=', 'prod_knit_dlv_rolls.id');
              })
              ->join('prod_knit_qcs',function($join){
                  $join->on('prod_knit_qcs.id', '=', 'prod_knit_dlv_rolls.prod_knit_qc_id');
              })
              ->join('prod_knit_rcv_by_qcs',function($join){
                  $join->on('prod_knit_rcv_by_qcs.id', '=', 'prod_knit_qcs.prod_knit_rcv_by_qc_id');
              })
              ->join('prod_knit_item_rolls',function($join){
                  $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
              })
              ->join('prod_knit_items',function($join){
                  $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
              })
              ->join ('prod_knits',function($join){
                  $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
              })
              ->join ('suppliers',function($join){
                  $join->on('suppliers.id', '=', 'inv_grey_fab_items.supplier_id');
              })
              ->leftJoin('asset_quantity_costs',function($join){
                  $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
              })
              ->leftJoin('asset_technical_features',function($join){
                  $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
              })
              ->leftJoin(\DB::raw("(
                  select 
                  pl_knit_items.id,
                  customer.name as customer_name,
                  companies.id as company_id,
                  
                  CASE 
                  WHEN sales_orders.sale_order_no IS NULL THEN so_knit_items.gmt_sale_order_no 
                  ELSE sales_orders.sale_order_no
                  END as sale_order_no,
                  CASE 
                  WHEN sales_orders.id IS NULL THEN 0
                  ELSE sales_orders.id
                  END as sale_order_id,
                  CASE 
                  WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
                  ELSE styles.style_ref
                  END as style_ref,
                  CASE 
                  WHEN styles.id IS NULL THEN 0 
                  ELSE styles.id
                  END as style_id,
                  CASE 
                  WHEN buyers.name IS NULL THEN outbuyers.name 
                  ELSE buyers.name
                  END as buyer_name,
  
                  CASE 
                  WHEN buyers.id IS NULL THEN outbuyers.id 
                  ELSE buyers.id
                  END as buyer_id
  
                  from pl_knit_items
                  join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
                  join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
                  left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
                  left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
                  left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
                  and po_knit_service_items.deleted_at is null
                  left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
                  left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                  left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                  left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
                  left join so_knits on so_knits.id=so_knit_refs.so_knit_id
                  left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                  left join jobs on jobs.id=sales_orders.job_id
                  left join styles on styles.id=jobs.style_id
                  left join buyers on buyers.id=styles.buyer_id
                  left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
                  left join buyers customer on customer.id=so_knits.buyer_id
                  left join companies  on companies.id=customer.company_id
              ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
              ->leftJoin(\DB::raw("(
                  select 
                  po_knit_service_item_qties.id,
                  sales_orders.sale_order_no,
                  sales_orders.id as sale_order_id,
                  styles.style_ref,
                  styles.id as style_id,
                  buyers.name as buyer_name,
                  buyers.id as buyer_id,
                  companies.name as customer_name,
                  companies.id as company_id   
                  from 
                  po_knit_service_item_qties
                  join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
                  join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
                  left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
                  join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
                  join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                  join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                  
                  left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                  left join jobs on jobs.id=sales_orders.job_id
                  left join styles on styles.id=jobs.style_id
                  left join buyers on buyers.id=styles.buyer_id
                  left join companies on companies.id=po_knit_services.company_id
                  order by po_knit_service_item_qties.id
              ) outhouseprods"),"outhouseprods.id","=","prod_knit_items.po_knit_service_item_qty_id")
              ->leftJoin('companies',function($join){
                  $join->on('companies.id','=','outhouseprods.company_id');
                  $join->Oron('companies.id','=','inhouseprods.company_id');
              })
              ->where([['prod_batches.id','=',$invdyechemisurq->prod_batch_id]])
              ->orderBy('sales_orders.ship_date','desc')
              ->get()
              ->map(function($prodknitqc) {
                  return $prodknitqc;
              });
        }
        if($prodbatch->batch_for==2){
              $prodknitqc=$this->prodbatch
              ->selectRaw('
                prod_batches.id as prod_batch_id,
                so_dyeing_fabric_rcv_rols.id as so_dyeing_fabric_rcv_rol_id,
                so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id,
                so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
                so_dyeing_items.gmt_sale_order_no as sale_order_no,
                so_dyeing_items.gmt_style_ref as style_ref,
                buyers.name as buyer_name,
                customers.name as customer_name
              ')
              ->join('prod_batch_rolls',function($join){
                $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
              })
              ->join('so_dyeing_fabric_rcv_rols',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
              })
              ->join('so_dyeing_fabric_rcv_items',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
              })
              ->join('so_dyeing_refs',function($join){
                $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
              })
              ->join('so_dyeing_items',function($join){
                $join->on('so_dyeing_refs.id', '=', 'so_dyeing_items.so_dyeing_ref_id');
              })
              ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
              })
              ->join('buyers as customers',function($join){
                $join->on('customers.id','=','so_dyeings.buyer_id');
              })
              ->leftJoin ('buyers',function($join){
                $join->on('buyers.id', '=', 'so_dyeing_items.gmt_buyer');
              })
              ->where([['prod_batches.id','=',$invdyechemisurq->prod_batch_id]])
              ->orderBy('prod_batches.id','desc')
              ->get()
              ->map(function($prodknitqc){
                return $prodknitqc;
              });
          }
          $ordDtl=[];
          foreach($prodknitqc as $data){
              $ordDtl['sale_order_no'][$data->sale_order_no]=$data->sale_order_no;
              $ordDtl['style_ref'][$data->style_ref]=$data->style_ref;
              $ordDtl['buyer_name'][$data->buyer_name]=$data->buyer_name;
              //$ordDtl['ship_date'][$data->ship_date]=$data->ship_date?date("d-M-Y",strtotime($data->ship_date)):'';
              $ordDtl['customer_name'][$data->customer_name]=$data->customer_name;
        }
        $batch['ordDtl']=$ordDtl;
        $data['master']    =$rows;
        $data['details']   =$invdyechemisurqitem;

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
        $pdf->SetY(3);
        $pdf->SetX(190);
        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

      $pdf->SetY(35);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, 'Additional Dyes & Chemicals Issue Requisition ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Additional Dyes & Chemicals Issue Requisition');
      $view= \View::make('Defult.Inventory.DyeChem.DyeChemIsuRqAddPdf',['data'=>$data,'batch'=>$batch]);
      $html_content=$view->render();
      $pdf->SetY(45);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/DyeChemIsuRqAddPdf.pdf';
      $pdf->output($filename);

    }
}