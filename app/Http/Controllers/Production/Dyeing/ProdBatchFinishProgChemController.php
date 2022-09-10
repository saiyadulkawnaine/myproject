<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishProgRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishProgChemRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqItemRepository;


use App\Library\Template;
use App\Http\Requests\Production\Dyeing\ProdBatchFinishProgChemRequest;

class ProdBatchFinishProgChemController extends Controller {

    private $prodbatchfinishprog;
    private $prodbatchfinishprogchem;
    private $itemaccount;
    private $invdyechemisurq;
    private $prodbatch;
    private $invdyechemisurqitem;

    public function __construct(
        ProdBatchFinishProgRepository $prodbatchfinishprog,  
        ProdBatchFinishProgChemRepository $prodbatchfinishprogchem,  
        ItemAccountRepository $itemaccount,
        InvDyeChemIsuRqRepository $invdyechemisurq,
        ProdBatchRepository $prodbatch,
        InvDyeChemIsuRqItemRepository $invdyechemisurqitem
    ) {
        $this->prodbatchfinishprog = $prodbatchfinishprog;
        $this->prodbatchfinishprogchem = $prodbatchfinishprogchem;
        $this->itemaccount = $itemaccount;
        $this->invdyechemisurq = $invdyechemisurq;
        $this->prodbatch = $prodbatch;
        $this->invdyechemisurqitem = $invdyechemisurqitem;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodbatchfinishprogchems',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodbatchfinishprogchems', ['only' => ['store']]);
            $this->middleware('permission:edit.prodbatchfinishprogchems',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodbatchfinishprogchems', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         $rows=$this->prodbatchfinishprog
        ->join('prod_batch_finish_prog_chems',function($join){
            $join->on('prod_batch_finish_prog_chems.prod_batch_finish_prog_id','=','prod_batch_finish_progs.id');
        })
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','prod_batch_finish_prog_chems.item_account_id');
        })
        ->join('itemclasses', function($join){
            $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
          })
          ->join('itemcategories', function($join){
            $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
          })
        ->where([['prod_batch_finish_progs.id','=',request('prod_batch_finish_prog_id')]])
        ->get([
            'prod_batch_finish_prog_chems.*',
            'itemcategories.name as item_category',
            'itemclasses.name as item_class',
            'item_accounts.id as item_account_id',
            'item_accounts.sub_class_name',
            'item_accounts.item_description as item_desc',
            'item_accounts.specification',
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
    public function store(ProdBatchFinishProgChemRequest $request) {
       
        $prodbatchfinishprogchem = $this->prodbatchfinishprogchem->create($request->except(['id','item_desc']));
        if($prodbatchfinishprogchem){
            return response()->json(array('success' => true,'id' =>  $prodbatchfinishprogchem->id,'message' => 'Save Successfully'),200);
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
         $rows=$this->prodbatchfinishprogchem
        ->join('prod_batch_finish_progs',function($join){
            $join->on('prod_batch_finish_prog_chems.prod_batch_finish_prog_id','=','prod_batch_finish_progs.id');
        })
        
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','prod_batch_finish_prog_chems.item_account_id');
        })
        ->join('itemclasses', function($join){
            $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
          })
          ->join('itemcategories', function($join){
            $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
          })
        
        ->where([['prod_batch_finish_prog_chems.id','=',$id]])
        ->get([
            'prod_batch_finish_prog_chems.*',
            'itemcategories.name as item_category',
            'itemclasses.name as item_class',
            'item_accounts.id as item_account_id',
            'item_accounts.sub_class_name',
            'item_accounts.item_description as item_desc',
            'item_accounts.specification',
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
    public function update(ProdBatchFinishProgChemRequest $request, $id) {
        $prodbatchfinishprogchem = $this->prodbatchfinishprogchem->update($id,$request->except(['id','item_desc']));

        if($prodbatchfinishprogchem){
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
        if($this->prodbatchfinishprogchem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
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

    public function genReq(){
      $prod_batch_finish_prog_id=request('prod_batch_finish_prog_id',0);
      $prodbatchfinishprog=$this->prodbatchfinishprog->find($prod_batch_finish_prog_id);
      $batch=$this->prodbatch->find($prodbatchfinishprog->prod_batch_id);

      $isreq=$this->invdyechemisurq
      ->where([['inv_dye_chem_isu_rqs.prod_batch_id','=',$batch->id]])
      ->where([['inv_dye_chem_isu_rqs.menu_id','=',284]])
      ->where([['inv_dye_chem_isu_rqs.rq_basis_id','=',8]])
      ->where([['inv_dye_chem_isu_rqs.prod_batch_finish_prog_id','=',$prod_batch_finish_prog_id]])
      ->get()
      ->first();
      if($isreq){
          return response()->json(array('success' => false,'id' => $isreq->id,'message' => 'Requisition Found'),200);
      }
      if(!$isreq){
          $max=$this->invdyechemisurq
          ->join('prod_batches',function($join){
          $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
          })
          ->where([['prod_batches.company_id','=',$batch->company_id]])
          ->max('inv_dye_chem_isu_rqs.rq_no');

          $rq_no=$max+1;
          $invdyechemisurq=$this->invdyechemisurq->create([
          'rq_no'=>$rq_no,
          'menu_id'=>284,
          'rq_basis_id'=>8,
          'prod_batch_id'=>$batch->id,
          'rq_date'=>date('Y-m-d'),
          'liqure_ratio'=>0,
          'liqure_wgt'=>0,
          'remarks'=>$prodbatchfinishprog->remarks,
          'operator_id'=>$prodbatchfinishprog->operator_id,
          'incharge_id'=>$prodbatchfinishprog->incharge_id,
          'prod_batch_finish_prog_id'=>$prod_batch_finish_prog_id,
          ]);

          $chems=$this->prodbatchfinishprog
          ->join('prod_batch_finish_prog_chems',function($join){
          $join->on('prod_batch_finish_prog_chems.prod_batch_finish_prog_id','=','prod_batch_finish_progs.id');
          })
          ->where([['prod_batch_finish_progs.prod_batch_id','=',$batch->id]])
          ->where([['prod_batch_finish_progs.id','=',$prod_batch_finish_prog_id]])
          ->whereNull('prod_batch_finish_prog_chems.deleted_at')
          ->whereNull('prod_batch_finish_progs.deleted_at')
          ->orderBy('prod_batch_finish_prog_chems.id')
          ->get();
          foreach($chems as $chem){
            $invdyechemisurqitem = $this->invdyechemisurqitem->create(
            [
            'inv_dye_chem_isu_rq_id'=> $invdyechemisurq->id,         
            'item_account_id'=> $chem->item_account_id,        
            'sub_process_id'=>60,        
            'per_on_batch_wgt'=> 0,
            'gram_per_ltr_liqure'=> 0,        
            'qty' => $chem->qty,
            'sort_id' => $chem->id,
            'remarks'=> $chem->remarks
            ]);
          }
          return response()->json(array('success' => true,'id' => $invdyechemisurq->id,'message' => 'Save Successfully'),200);
      }
    }
}