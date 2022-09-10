<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRqRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRqItemRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvGeneralIsuRqItemRequest;

class InvGeneralIsuRqItemController extends Controller {

   
    private $invgeneralisurq;
    private $invgeneralisurqitem;
    private $itemaccount;
    private $job;
    private $assetquantitycost;

    public function __construct(
        InvGeneralIsuRqRepository $invgeneralisurq,
        InvGeneralIsuRqItemRepository $invgeneralisurqitem,
        ItemAccountRepository $itemaccount,
        JobRepository $job,
        AssetQuantityCostRepository $assetquantitycost
    ) {
        
        $this->invgeneralisurq = $invgeneralisurq;
        $this->invgeneralisurqitem = $invgeneralisurqitem;
        $this->itemaccount = $itemaccount;
        $this->job = $job;
        $this->assetquantitycost = $assetquantitycost;
        $this->middleware('auth');
        //$this->middleware('permission:view.invgeneralrcv',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invgeneralrcv', ['only' => ['store']]);
        //$this->middleware('permission:edit.invgeneralrcv',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invgeneralrcv', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
          $inv_general_isu_rq_id=request('inv_general_isu_rq_id',0);
          $rows = $this->invgeneralisurq
          ->join('inv_general_isu_rq_items',function($join){
          $join->on('inv_general_isu_rq_items.inv_general_isu_rq_id','=','inv_general_isu_rqs.id');
          })
          ->join('item_accounts',function($join){
          $join->on('inv_general_isu_rq_items.item_account_id','=','item_accounts.id');
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
          ->leftJoin('sales_orders', function($join){
          $join->on('sales_orders.id', '=', 'inv_general_isu_rq_items.sale_order_id');
          })
          ->leftJoin('jobs', function($join){
          $join->on('jobs.id', '=', 'sales_orders.job_id');
          })
          ->leftJoin('styles', function($join){
          $join->on('styles.id', '=', 'jobs.style_id');
          })
          ->leftJoin('departments', function($join){
          $join->on('departments.id', '=', 'inv_general_isu_rq_items.department_id');
          })
          ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','inv_general_isu_rq_items.asset_quantity_cost_id');
          })
          ->where([['inv_general_isu_rqs.id','=',$inv_general_isu_rq_id]])
          ->orderBy('inv_general_isu_rqs.id','desc')
          ->get([
          'inv_general_isu_rq_items.*',
          'itemcategories.name as category_name',
          'itemclasses.name as class_name',
          'item_accounts.sub_class_name',
          'item_accounts.item_description',
          'item_accounts.specification',
          'uoms.code as uom_name',
          'styles.style_ref',
          'sales_orders.sale_order_no',
          'departments.name as department_name',
          'asset_quantity_costs.custom_no',
          ])
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
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvGeneralIsuRqItemRequest $request) {
      
        $invgeneralisurqitem = $this->invgeneralisurqitem->create(
        [
        'inv_general_isu_rq_id'=> $request->inv_general_isu_rq_id,         
        'item_account_id'=> $request->item_account_id,        
        'sale_order_id'=> $request->sale_order_id,        
        'department_id'=> $request->department_id,        
        'purpose_id'=> $request->purpose_id,        
        'asset_quantity_cost_id'=> $request->asset_quantity_cost_id,        
        'qty' => $request->qty,
        'remarks' => $request->remarks     
        ]);
      if($invgeneralisurqitem){
        return response()->json(array('success' =>true ,'id'=>$invgeneralisurqitem->id, 'inv_general_isu_rq_id'=>$request->inv_general_isu_rq_id,'message'=>'Saved Successfully'),200);
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
          $rows =$this->invgeneralisurqitem
          ->join('inv_general_isu_rqs',function($join){
          $join->on('inv_general_isu_rqs.id','=','inv_general_isu_rq_items.inv_general_isu_rq_id');
          })
          
          
          ->join('item_accounts',function($join){
          $join->on('inv_general_isu_rq_items.item_account_id','=','item_accounts.id');
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
          ->leftJoin('sales_orders', function($join){
          $join->on('sales_orders.id', '=', 'inv_general_isu_rq_items.sale_order_id');
          })
          ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','inv_general_isu_rq_items.asset_quantity_cost_id');
          })
          ->where([['inv_general_isu_rq_items.id','=',$id]])
          ->orderBy('inv_general_isu_rq_items.id','desc')
          ->get([
          'inv_general_isu_rq_items.*',
          'inv_general_isu_rqs.id as inv_general_isu_rq_id',
          'itemcategories.name as category_name',
          'itemclasses.name as class_name',
          'item_accounts.sub_class_name',
          'item_accounts.id as item_id',
          'item_accounts.item_description as item_desc',
          'item_accounts.specification',
          'uoms.code as uom_code',
          'sales_orders.sale_order_no',
          'asset_quantity_costs.custom_no',
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
    public function update(InvGeneralIsuRqItemRequest $request, $id) {
      
      $invgeneralisurqitem = $this->invgeneralisurqitem->update($id,
        [
        //'inv_general_isu_rq_id'=> $request->inv_general_isu_rq_id,         
        'item_account_id'=> $request->item_account_id,        
        'sale_order_id'=> $request->sale_order_id,        
        'department_id'=> $request->department_id,        
        'purpose_id'=> $request->purpose_id, 
        'asset_quantity_cost_id'=> $request->asset_quantity_cost_id,        
        'qty' => $request->qty,
        'remarks' => $request->remarks     
        ]);
      
      if($invgeneralisurqitem){
        return response()->json(array('success' =>true ,'id'=>$id, 'inv_general_isu_rq_id'=>$request->inv_general_isu_rq_id,'message'=>'Saved Successfully'),200);
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
      return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
        if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


    public function getItem()
    {
        
          $rows=$this->itemaccount
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
          inv_general_transactions.item_account_id,
          sum(inv_general_transactions.store_qty) as qty 
          FROM inv_general_transactions 
          where  inv_general_transactions.deleted_at is null
          group by inv_general_transactions.item_account_id
          ) stock"), "stock.item_account_id", "=", "item_accounts.id")
          ->whereIn('itemcategories.identity',[9])
          ->selectRaw(
          '
          itemcategories.name as category_name,
          itemclasses.name as class_name,
          item_accounts.id,
          item_accounts.id as item_account_id,
          item_accounts.sub_class_name,
          item_accounts.item_description,
          item_accounts.specification,
          uoms.code as uom_name,
          stock.qty as stock_qty
          ')
          ->get()
          ->map(function($rows){
            $rows->item_desc=$rows->item_description.", ".$rows->specification;
            return $rows;
          });
          echo json_encode($rows);
        
    }

    public function getOrder()
    {

          $invgeneralisurq=$this->invgeneralisurq->find(request('inv_general_isu_rq_id',0));
        
          $rows=$this->job
          ->join('sales_orders', function($join){
            $join->on('sales_orders.job_id', '=', 'jobs.id');
          })
          ->join('styles', function($join){
            $join->on('styles.id', '=', 'jobs.style_id');
          })
          ->leftJoin('buyers', function($join){
            $join->on('buyers.id', '=', 'styles.buyer_id');
          })
          ->leftJoin('companies', function($join){
            $join->on('companies.id', '=', 'jobs.company_id');
          })
          ->leftJoin('companies as produced_company', function($join)  {
          $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
          })
          ->leftJoin('teammembers', function($join)  {
          $join->on('styles.factory_merchant_id', '=', 'teammembers.id');
          })
          ->leftJoin('users', function($join)  {
          $join->on('users.id', '=', 'teammembers.user_id');
          })
          ->where([['jobs.company_id','=',$invgeneralisurq->company_id]])
          ->selectRaw(
          '
          styles.style_ref,
          buyers.name as buyer_name,
          sales_orders.id,
          sales_orders.id as sale_order_id,
          sales_orders.sale_order_no,
          sales_orders.qty,
          sales_orders.rate,
          sales_orders.amount,
          sales_orders.ship_date,
          companies.code as company_name,
          produced_company.code as pcompany_name,
          users.name as team_member_name
          ')
          ->get()
          ->map(function($rows){
            $rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
            return $rows;
          });
          echo json_encode($rows);
        
    }

    public function getMachine()
    {
        $machine=$this->assetquantitycost
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_acquisitions.id','=','asset_technical_features.asset_acquisition_id');
        })
        ->when(request('dia_width'), function ($q) {
        return $q->where('asset_technical_features.dia_width', '=>',request('dia_width', 0));
        })
        ->when(request('no_of_feeder'), function ($q) {
        return $q->where('asset_technical_features.no_of_feeder', '<=',request('no_of_feeder', 0));
        })
        //->where([['asset_acquisitions.production_area_id','=',10]])
        ->where([['asset_acquisitions.type_id','=',65]])
        ->orderBy('asset_acquisitions.id','asc')
        ->orderBy('asset_quantity_costs.id','asc')
        ->get([
            'asset_quantity_costs.*',
            'asset_acquisitions.prod_capacity',
            'asset_acquisitions.name as asset_name',
            'asset_acquisitions.origin',
            'asset_acquisitions.brand',
            'asset_technical_features.dia_width',
            'asset_technical_features.gauge',
            'asset_technical_features.extra_cylinder',
            'asset_technical_features.no_of_feeder'
        ]);
        echo json_encode($machine);
    }
}