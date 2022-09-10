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
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuRqItemAddRequest;

class InvDyeChemIsuRqItemAddController extends Controller {

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
        $this->middleware('permission:view.invdyechemisurqitemadd',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisurqitemadd', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisurqitemadd',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisurqitemadd', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
          $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'), '-Select-','');
          $rootrq=$this->invdyechemisurq->find(request('inv_dye_chem_isu_rq_id',0));
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
          ->where([['inv_dye_chem_isu_rqs.id','=',$rootrq->root_id]])
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
          ])
          ->map(function($rows) use ($dyeingsubprocess){
            $rows->sub_process_name=$dyeingsubprocess[$rows->sub_process_id];
            $rows->item_description=$rows->item_description.', '.$rows->specification;
            $rows->ratio='';
            if($rows->per_on_batch_wgt){
              $rows->ratio=$rows->per_on_batch_wgt.' % on Batch Wgt';
            }
            else if ($rows->gram_per_ltr_liqure){
              $rows->ratio=$rows->gram_per_ltr_liqure.' Gram/L. Liqure';
            }
            return $rows;
          });

          $saved = $this->invdyechemisurq
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
          ])
          ->map(function($saved) use ($dyeingsubprocess){
            $saved->sub_process_name=$dyeingsubprocess[$saved->sub_process_id];
            $saved->item_description=$saved->item_description.', '.$saved->specification;
            $saved->ratio='';
            if($saved->per_on_batch_wgt){
              $saved->ratio=$saved->per_on_batch_wgt.' % on Batch Wgt';
            }
            else if ($saved->gram_per_ltr_liqure){
              $saved->ratio=$saved->gram_per_ltr_liqure.' Gram/L. Liqure';
            }
            return $saved;
          });
          return Template::loadView('Inventory.DyeChem.InvDyeChemIsuRqAddItemMatrix',['rows'=>$rows,'saved'=>$saved]);

          //echo json_encode($rows);
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
    public function store(InvDyeChemIsuRqItemAddRequest $request) {

      foreach($request->root_item_id as $index=>$root_item_id)
      {
        if($request->add_qty[$index])
        {
          $root_item=$this->invdyechemisurqitem->find($request->root_item_id[$index]);
          if($request->id[$index])
          {
            $invdyechemisurqitem = $this->invdyechemisurqitem->update($request->id[$index],
            [
            'inv_dye_chem_isu_rq_id'=> $request->inv_dye_chem_isu_rq_id,         
            'root_item_id'=> $root_item_id,         
            'item_account_id'=> $request->item_account_id[$index],        
            'sub_process_id'=> $request->sub_process_id[$index], 
            'per_on_batch_wgt'=> $root_item->per_on_batch_wgt,       
            'gram_per_ltr_liqure'=> $root_item->gram_per_ltr_liqure,       
            'qty' => $request->add_qty[$index],
            'first_qty' => $request->qty[$index],
            'add_per' => $request->add_per[$index],
            'sort_id' => $request->sort_id[$index],
            'remarks' => $request->remarks[$index],
            ]); 
          }
          else
          {
            $invdyechemisurqitem = $this->invdyechemisurqitem->create(
            [
            'inv_dye_chem_isu_rq_id'=> $request->inv_dye_chem_isu_rq_id,         
            'root_item_id'=> $root_item_id,         
            'item_account_id'=> $request->item_account_id[$index],        
            'sub_process_id'=> $request->sub_process_id[$index], 
            'per_on_batch_wgt'=> $root_item->per_on_batch_wgt,       
            'gram_per_ltr_liqure'=> $root_item->gram_per_ltr_liqure,       
            'qty' => $request->add_qty[$index],
            'first_qty' => $request->qty[$index],
            'add_per' => $request->add_per[$index],
            'sort_id' => $request->sort_id[$index],
            'remarks' => $request->remarks[$index],
            ]); 
          }
        }
      }

      
        

        if($invdyechemisurqitem){
        return response()->json(array('success' =>true ,'id'=>'', 'inv_dye_chem_isu_rq_id'=>$request->inv_dye_chem_isu_rq_id,'message'=>'Saved Successfully'),200);
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
    public function update(InvDyeChemIsuRqItemAddRequest $request, $id) {
      
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
          uoms.code as uom_name
          ')
          ->get()
          ->map(function($rows){
            return $rows;
          });
          echo json_encode($rows);
    }
}