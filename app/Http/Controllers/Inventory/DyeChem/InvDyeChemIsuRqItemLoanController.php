<?php

namespace App\Http\Controllers\Inventory\DyeChem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqItemRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemTransactionRepository;
use App\Repositories\Contracts\Purchase\PoDyeChemRepository;
use App\Repositories\Contracts\Purchase\PoDyeChemItemRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqItemRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuRqItemLoanRequest;

class InvDyeChemIsuRqItemLoanController extends Controller {

    private $invdyechemisurq;
    private $invdyechemisurqitem;
    private $itemaccount;
    private $job;
    private $assetquantitycost;

    public function __construct(
        InvDyeChemIsuRqRepository $invdyechemisurq, 
        InvDyeChemIsuRqItemRepository $invdyechemisurqitem,
        ItemAccountRepository $itemaccount,
        JobRepository $job,
        AssetQuantityCostRepository $assetquantitycost
    ) {
        $this->invdyechemisurq = $invdyechemisurq;
        $this->invdyechemisurqitem = $invdyechemisurqitem;
        $this->itemaccount = $itemaccount;
        $this->job = $job;
        $this->assetquantitycost = $assetquantitycost;
        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemisurqitemloan',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisurqitemloan', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisurqitemloan',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisurqitemloan', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
          $rows = $this->invdyechemisurq
          
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
          ->leftJoin('asset_quantity_costs',function($join){
          $join->on('asset_quantity_costs.id','=','inv_dye_chem_isu_rq_items.asset_quantity_cost_id');
          })
          
          ->where([['inv_dye_chem_isu_rqs.id','=',request('inv_dye_chem_isu_rq_id',0)]])
          ->orderBy('inv_dye_chem_isu_rq_items.id','desc')
          ->get([
          'inv_dye_chem_isu_rq_items.*',
          'itemcategories.name as category_name',
          'itemclasses.name as class_name',
          'item_accounts.sub_class_name',
          'item_accounts.item_description',
          'item_accounts.specification',
          'uoms.code as uom_name',
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
    public function store(InvDyeChemIsuRqItemLoanRequest $request) {
        $invdyechemisurqitem = $this->invdyechemisurqitem->create(
        [
          'inv_dye_chem_isu_rq_id'=> $request->inv_dye_chem_isu_rq_id,         
          'item_account_id'=> $request->item_account_id,        
          'asset_quantity_cost_id'=> $request->asset_quantity_cost_id,        
          'qty' => $request->qty,
          'sort_id' => $request->sort_id,
          'remarks'=> $request->remarks
        ]);

        if($invdyechemisurqitem){
          return response()->json(array('success' =>true ,'id'=>$invdyechemisurqitem->id, 'inv_dye_chem_isu_rq_id'=>$request->inv_dye_chem_isu_rq_id,'message'=>'Saved Successfully'),200);
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
      $rows = $this->invdyechemisurqitem
      ->join('inv_dye_chem_isu_rqs',function($join){
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
      ->leftJoin('asset_quantity_costs',function($join){
      $join->on('asset_quantity_costs.id','=','inv_dye_chem_isu_rq_items.asset_quantity_cost_id');
      })
      ->where([['inv_dye_chem_isu_rq_items.id','=',$id]])
      ->get([
      'inv_dye_chem_isu_rq_items.*',
      'itemcategories.name as item_category',
      'itemclasses.name as item_class',
      'item_accounts.sub_class_name',
      'item_accounts.item_description as item_desc',
      'item_accounts.specification',
      'uoms.code as uom_code',
      'asset_quantity_costs.custom_no',
      ])
      ->map(function($rows){
      return $rows;
      })
      ->first();
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
    public function update(InvDyeChemIsuRqItemLoanRequest $request, $id) {
        $invdyechemisurqitem = $this->invdyechemisurqitem->update($id,
        [
        'item_account_id'=> $request->item_account_id,        
        'asset_quantity_cost_id'=> $request->asset_quantity_cost_id,        
        'qty' => $request->qty,
        'sort_id' => $request->sort_id,
        'remarks'=> $request->remarks
        ]);

        if($invdyechemisurqitem){
        return response()->json(array('success' =>true ,'id'=>$id, 'inv_dye_chem_isu_rq_id'=>$request->inv_dye_chem_isu_rq_id,'message'=>'Update Successfully'),200);
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
          inv_dye_chem_transactions.item_account_id,
          sum(inv_dye_chem_transactions.store_qty) as qty 
          FROM inv_dye_chem_transactions 
          where  inv_dye_chem_transactions.deleted_at is null
          group by inv_dye_chem_transactions.item_account_id
          ) stock"), "stock.item_account_id", "=", "item_accounts.id")
          ->when(request('item_category'), function ($q) {
          return $q->where('itemcategories.name', 'LIKE', "%".request('item_category', 0)."%");
          })
          ->when(request('item_class'), function ($q) {
          return $q->where('itemclasses.name', 'LIKE', "%".request('item_class', 0)."%");
          })
          ->whereIn('itemcategories.identity',[7,8])
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
        //->where([['asset_acquisitions.type_id','=',65]])
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