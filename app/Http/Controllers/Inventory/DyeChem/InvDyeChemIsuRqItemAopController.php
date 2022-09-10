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
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Production\AOP\ProdAopBatchRepository;

use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuRqItemAopRequest;

class InvDyeChemIsuRqItemAopController extends Controller {

    private $invdyechemisurq;
    private $invdyechemisurqitem;
    private $itemaccount;
    private $job;
    private $soaop;
    private $soemb;
    private $embelishmenttype;
    private $prodaopbatch;


    public function __construct(
        InvDyeChemIsuRqRepository $invdyechemisurq, 
        InvDyeChemIsuRqItemRepository $invdyechemisurqitem,
        ProdAopBatchRepository $prodaopbatch,
        ItemAccountRepository $itemaccount,
        JobRepository $job,
        SoAopRepository $soaop,
        SoEmbRepository $soemb,
        EmbelishmentTypeRepository $embelishmenttype
    ) {
        $this->invdyechemisurq = $invdyechemisurq;
        $this->invdyechemisurqitem = $invdyechemisurqitem;
        $this->itemaccount = $itemaccount;
        $this->job = $job;
        $this->soaop = $soaop;
        $this->soemb = $soemb;
        $this->embelishmenttype = $embelishmenttype;
        $this->prodaopbatch = $prodaopbatch;

        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemisurqitemaop',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisurqitemaop', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisurqitemaop',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisurqitemaop', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $invdyechemisurq=$this->invdyechemisurq->find(request('inv_dye_chem_isu_rq_id',0));
        
		$aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');

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
		->leftJoin('so_aops',function($join){
		$join->on('so_aops.id','=','inv_dye_chem_isu_rq_items.so_aop_id');
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
		'so_aops.sales_order_no as sale_order_no',
		])
		->map(function($rows) use($aoptype){
		$rows->print_type=$aoptype[$rows->print_type_id];
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
    public function store(InvDyeChemIsuRqItemAopRequest $request) {
      $invdyechemisurq=$this->invdyechemisurq->find($request->inv_dye_chem_isu_rq_id);
       $prodaopbatch=$this->prodaopbatch->find($invdyechemisurq->prod_aop_batch_id);
       if($invdyechemisurq->rq_basis_id==3 && ! $prodaopbatch->so_aop_id){
        return response()->json(array('success' =>false , 'inv_dye_chem_isu_rq_id'=>$request->inv_dye_chem_isu_rq_id,'message'=>'Add Sales Order in AOP Batch'),200);
       }
       

        $invdyechemisurqitem = $this->invdyechemisurqitem->create(
        [
        'inv_dye_chem_isu_rq_id'=> $request->inv_dye_chem_isu_rq_id,         
        'so_aop_id'=> $prodaopbatch->so_aop_id,        
        'item_account_id'=> $request->item_account_id,        
        'print_type_id'=> $request->print_type_id,        
        'rto_on_paste_wgt'=> $request->rto_on_paste_wgt,
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
        ->leftJoin('so_aops',function($join){
        $join->on('so_aops.id','=','inv_dye_chem_isu_rq_items.so_aop_id');
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
        'so_aops.sales_order_no as sale_order_no',
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
    public function update(InvDyeChemIsuRqItemAopRequest $request, $id) {
        $invdyechemisurq=$this->invdyechemisurq->find($request->inv_dye_chem_isu_rq_id);
        $prodaopbatch=$this->prodaopbatch->find($invdyechemisurq->prod_aop_batch_id);
        
        if($invdyechemisurq->rq_basis_id==3 && !$prodaopbatch->so_aop_id){
          return response()->json(array('success' =>false , 'inv_dye_chem_isu_rq_id'=>$request->inv_dye_chem_isu_rq_id,'message'=>'Add Sales Order in Aop Batch'),200);
        }
        
        $invdyechemisurqitem = $this->invdyechemisurqitem->update($id,
        [
          'so_aop_id'=> $prodaopbatch->so_aop_id,        
          'item_account_id'=> $request->item_account_id,        
          'print_type_id'=> $request->print_type_id,        
          'rto_on_paste_wgt'=> $request->rto_on_paste_wgt,
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

    /*public function getOrder()
    {
      $invdyechemisurq=$this->invdyechemisurq->find(request('inv_dye_chem_isu_rq_id',0));
      
        return response()->json(
        $this->soaop
        ->leftJoin('buyers', function($join)  {
        $join->on('so_aops.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('companies', function($join)  {
        $join->on('so_aops.company_id', '=', 'companies.id');
        })
        ->leftJoin('currencies', function($join)  {
        $join->on('currencies.id', '=', 'so_aops.currency_id');
        })
        ->leftJoin('sub_inb_marketings', function($join)  {
        $join->on('so_aops.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
        })
        ->when(request('order_no'), function ($q) {
        return $q->where('itemcategories.sales_order_no', '=', request('order_no', 0));
        })
        ->where([['so_aops.company_id','=',$invdyechemisurq->company_id]])
        ->orderBy('so_aops.id','desc')
        ->get([
        'so_aops.*',
        'so_aops.id as so_aop_id',
        'sub_inb_marketings.id as sub_inb_marketing_id',
        'buyers.name as buyer_id',
        'companies.name as company_id',
        'currencies.name as currency_id',
        ])
        ->map(function($rows){
        $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
        //$rows->rq_basis_id=$invdyechemisurq->rq_basis_id;
        return $rows;
        })
        );
    }*/

    /*public function getPrintType(){
      $invdyechemisurq=$this->invdyechemisurq->find(request('inv_dye_chem_isu_rq_id',0));
      if($invdyechemisurq->rq_basis_id==3)
      {
        $aoptype=$this->embelishmenttype->getAopTypes();
        echo json_encode($aoptype);
      }
       if($invdyechemisurq->rq_basis_id==4)
       {
         $embtype=$this->embelishmenttype->getEmbelishmentTypes();
          echo json_encode($embtype);
       }
    }*/

    public function getMasterRq(){
       $rows = $this->invdyechemisurq
       
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_dye_chem_isu_rqs.company_id');
       })
       ->join('buyers',function($join){
        $join->on('buyers.id','=','inv_dye_chem_isu_rqs.buyer_id');
       })
       ->join('locations',function($join){
        $join->on('locations.id','=','inv_dye_chem_isu_rqs.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','inv_dye_chem_isu_rqs.color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','inv_dye_chem_isu_rqs.colorrange_id');
       })
       ->where([['inv_dye_chem_isu_rqs.menu_id','=',210]])
       ->where([['inv_dye_chem_isu_rqs.rq_basis_id','=',3]])
       ->where([['inv_dye_chem_isu_rqs.id','!=',request('inv_dye_chem_isu_rq_id',0)]])
       ->when(request('company_id'), function ($q) {
        return $q->where('inv_dye_chem_isu_rqs.company_id', '=', request('company_id', 0));
        })
       ->when(request('colorrange_id'), function ($q) {
        return $q->where('inv_dye_chem_isu_rqs.colorrange_id', '=', request('colorrange_id', 0));
        })
       ->when(request('fabric_color'), function ($q) {
        return $q->where('colors.name', 'LIKE', "%".request('fabric_color', 0)."%");
        })
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'inv_dye_chem_isu_rqs.colorrange_id',
        'inv_dye_chem_isu_rqs.design_no',
        'inv_dye_chem_isu_rqs.paste_wgt',
        'companies.code as company_id',
        'buyers.name as buyer_id',
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
      ->where([['inv_dye_chem_isu_rqs.id','=',$inv_dye_chem_isu_rq_id]])
      ->get([
      'inv_dye_chem_isu_rqs.*',
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
        $qty=($invdyechemisurq->paste_wgt*$row->rto_on_paste_wgt)/100;

        $invdyechemisurqitem = $this->invdyechemisurqitem->create(
        [
        'inv_dye_chem_isu_rq_id'=> $inv_dye_chem_isu_rq_id,
        'so_aop_id'=> $row->so_aop_id,        
        'so_emb_id'=> $row->so_emb_id,         
        'item_account_id'=> $row->item_account_id,        
        'print_type_id'=> $row->print_type_id,        
        'rto_on_paste_wgt'=> $row->rto_on_paste_wgt,
        'qty' => $qty,
        'sort_id' => $row->sort_id,
        'remarks'=> $row->remarks,
        ]);

       /* 'inv_dye_chem_isu_rq_id'=> $request->inv_dye_chem_isu_rq_id,         
        'so_aop_id'=> $request->so_aop_id,        
        'so_emb_id'=> $request->so_emb_id,        
        'item_account_id'=> $request->item_account_id,        
        'print_type_id'=> $request->print_type_id,        
        'rto_on_paste_wgt'=> $request->rto_on_paste_wgt,
        'qty' => $request->qty,
        'sort_id' => $request->sort_id,
        'remarks'=> $request->remarks*/

        

      }
      return response()->json(array('success' =>true ,'id'=>$invdyechemisurqitem->id, 'inv_dye_chem_isu_rq_id'=>$inv_dye_chem_isu_rq_id,'message'=>'Saved Successfully'),200);
    }
}