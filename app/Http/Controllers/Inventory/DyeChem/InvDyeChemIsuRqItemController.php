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
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuRqItemRequest;

class InvDyeChemIsuRqItemController extends Controller {

    private $invdyechemisurq;
    private $invdyechemisurqitem;
    private $itemaccount;

    public function __construct(
        InvDyeChemIsuRqRepository $invdyechemisurq, 
        InvDyeChemIsuRqItemRepository $invdyechemisurqitem,
        ItemAccountRepository $itemaccount
    ) {
        $this->invdyechemisurq = $invdyechemisurq;
        $this->invdyechemisurqitem = $invdyechemisurqitem;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        
        $this->middleware('permission:view.invdyechemisurqitems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisurqitems', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisurqitems',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisurqitems', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
            $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'), '-Select-','');

        
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
          'uoms.code as store_uom',
          ])
          ->map(function($rows) use($dyeingsubprocess){
            $rows->sub_process_name=$dyeingsubprocess[$rows->sub_process_id];
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
    public function store(InvDyeChemIsuRqItemRequest $request) {

      
        $invdyechemisurqitem = $this->invdyechemisurqitem->create(
        [
        'inv_dye_chem_isu_rq_id'=> $request->inv_dye_chem_isu_rq_id,         
        'item_account_id'=> $request->item_account_id,        
        'sub_process_id'=> $request->sub_process_id,        
        'per_on_batch_wgt'=> $request->per_on_batch_wgt,
        'gram_per_ltr_liqure'=> $request->gram_per_ltr_liqure,        
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
          ->where([['inv_dye_chem_isu_rq_items.id','=',$id]])
          ->get([
          'inv_dye_chem_isu_rq_items.*',
          'itemcategories.name as item_category',
          'itemclasses.name as item_class',
          'item_accounts.sub_class_name',
          'item_accounts.item_description as item_desc',
          'item_accounts.specification',
          'uoms.code as uom_code',
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
    public function update(InvDyeChemIsuRqItemRequest $request, $id) {
      $invdyechemisurqitem = $this->invdyechemisurqitem->update($id,
        [
        'item_account_id'=> $request->item_account_id,        
        'sub_process_id'=> $request->sub_process_id,        
        'per_on_batch_wgt'=> $request->per_on_batch_wgt,
        'gram_per_ltr_liqure'=> $request->gram_per_ltr_liqure,        
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
        //$invrcv=$this->invrcv->find(request('inv_rcv_id',0));

        
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

    public function getMasterRq(){
       $rows = $this->invdyechemisurq
       ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
       })
       /*->join('buyers',function($join){
        $join->on('buyers.id','=','inv_dye_chem_isu_rqs.buyer_id');
       })*/
       ->join('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->where([['inv_dye_chem_isu_rqs.menu_id','=',208]])
       ->where([['inv_dye_chem_isu_rqs.id','!=',request('inv_dye_chem_isu_rq_id',0)]])
       ->when(request('company_id'), function ($q) {
        return $q->where('prod_batches.company_id', '=', request('company_id', 0));
        })
       ->when(request('colorrange_id'), function ($q) {
        return $q->where('prod_batches.colorrange_id', '=', request('colorrange_id', 0));
        })
       ->when(request('fabric_color'), function ($q) {
        return $q->where('colors.name', 'LIKE', "%".request('fabric_color', 0)."%");
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
        'companies.code as company_id',
        'locations.name as location_id',
       ])
       ->map(function($rows){
        return $rows;
       });
      echo json_encode($rows);

    }

    public function copyItem(){
      $inv_dye_chem_isu_rq_id=request('inv_dye_chem_isu_rq_id',0);
      $master_rq_id=request('master_rq_id',0);
      $invdyechemisurq=$this->invdyechemisurq
      ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
      })
      
      ->where([['inv_dye_chem_isu_rqs.id','=',$inv_dye_chem_isu_rq_id]])
      ->get([
      'inv_dye_chem_isu_rqs.*',
      'prod_batches.batch_wgt',
      ])
      ->first();
      //echo json_encode($invdyechemisurq);
      //die;

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
      ->where([['inv_dye_chem_isu_rqs.id','=',$master_rq_id]])
      ->orderBy('inv_dye_chem_isu_rq_items.id')
      ->get([
      'inv_dye_chem_isu_rq_items.*',
      ]);

      foreach($rows as $row){
        $qty=0;
        if($row->per_on_batch_wgt){
         $qty=$invdyechemisurq->batch_wgt*($row->per_on_batch_wgt/100);
        }
        if($row->gram_per_ltr_liqure){
         $qty=($invdyechemisurq->liqure_wgt*$row->gram_per_ltr_liqure)/1000;
        }

        $invdyechemisurqitem = $this->invdyechemisurqitem->create(
        [
        'inv_dye_chem_isu_rq_id'=> $inv_dye_chem_isu_rq_id,         
        'item_account_id'=> $row->item_account_id,        
        'sub_process_id'=> $row->sub_process_id,        
        'per_on_batch_wgt'=> $row->per_on_batch_wgt,
        'gram_per_ltr_liqure'=> $row->gram_per_ltr_liqure,        
        'qty' => $qty,
        'sort_id' => $row->sort_id,
        'remarks'=> $row->remarks,
        ]);

        /*$request->request->add(['sub_process_id' => $row->sub_process_id]);
        $request->request->add(['item_account_id' => $row->item_account_id]);
        $request->request->add(['per_on_batch_wgt' => $row->per_on_batch_wgt]);
        $request->request->add(['gram_per_ltr_liqure' => $row->gram_per_ltr_liqure]);
        $request->request->add(['qty' => $qty]);
        $request->request->add(['sort_id' => $row->sort_id]);
        $request->request->add(['remarks' => $row->remarks]);*/

      }
      return response()->json(array('success' =>true ,'id'=>$invdyechemisurqitem->id, 'inv_dye_chem_isu_rq_id'=>$inv_dye_chem_isu_rq_id,'message'=>'Saved Successfully'),200);


      /*'inv_dye_chem_isu_rq_id'=> $request->inv_dye_chem_isu_rq_id,         
        'item_account_id'=> $request->item_account_id,        
        'sub_process_id'=> $request->sub_process_id,        
        'per_on_batch_wgt'=> $request->per_on_batch_wgt,
        'gram_per_ltr_liqure'=> $request->gram_per_ltr_liqure,        
        'qty' => $request->qty,
        'sort_id' => $request->sort_id,
        'remarks'=> $request->remarks*/
          


    }
}