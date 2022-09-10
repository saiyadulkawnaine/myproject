<?php

namespace App\Http\Controllers\Inventory\DyeChem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuItemRepository;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemTransactionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuItemRequest;

class InvDyeChemIsuItemController extends Controller {

   
    private $invisu;
    private $invdyechemisurq;
    private $invdyechemisuitem;
    private $invdyechemtransaction;
    private $itemaccount;
    private $job;
    private $store;

    public function __construct(
        InvIsuRepository $invisu,
        InvDyeChemIsuRqRepository $invdyechemisurq,
        InvDyeChemIsuItemRepository $invdyechemisuitem,
        InvDyeChemTransactionRepository $invdyechemtransaction, 
        ItemAccountRepository $itemaccount,
        JobRepository $job,
        StoreRepository $store

    ) {
        
        $this->invisu = $invisu;
        $this->invdyechemisurq = $invdyechemisurq;
        $this->invdyechemisuitem = $invdyechemisuitem;
        $this->invdyechemtransaction = $invdyechemtransaction;
        $this->itemaccount = $itemaccount;
        $this->job = $job;
        $this->store = $store;
        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemisuitem',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisuitem', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisuitem',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisuitem', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
          $inv_isu_id=request('inv_isu_id',0);
          $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'), '-Select-','');
          $invisu=$this->invisu->find(request($inv_isu_id));


          $rows = $this->invisu
          ->join('inv_dye_chem_isu_items',function($join){
            $join->on('inv_dye_chem_isu_items.inv_isu_id','=','inv_isus.id');
          })
          ->join('inv_dye_chem_isu_rq_items',function($join){
            $join->on('inv_dye_chem_isu_rq_items.id','=','inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id');
          })
          ->join('inv_dye_chem_isu_rqs',function($join){
            $join->on('inv_dye_chem_isu_rqs.id','=','inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id');
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
          ->join('stores',function($join){
            $join->on('stores.id','=','inv_dye_chem_isu_items.store_id');
          })
          
          ->where([['inv_isus.id','=',$inv_isu_id]])
          ->orderBy('inv_dye_chem_isu_items.id','desc')
          ->get([
            'inv_dye_chem_isu_items.*',
            'inv_dye_chem_isu_rq_items.sub_process_id',
            'inv_dye_chem_isu_rq_items.per_on_batch_wgt',
            'inv_dye_chem_isu_rq_items.gram_per_ltr_liqure',
            'inv_dye_chem_isu_rq_items.rto_on_paste_wgt',
            'inv_dye_chem_isu_rq_items.sort_id',
            'inv_dye_chem_isu_rqs.rq_no',
            'itemcategories.name as category_name',
            'itemclasses.name as class_name',
            'item_accounts.sub_class_name',
            'item_accounts.item_description',
            'item_accounts.specification',
            'uoms.code as uom_name',
            'stores.name as store_name',
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
            else if ($rows->rto_on_paste_wgt){
            $rows->ratio=$rows->rto_on_paste_wgt.' Ratio on Paste Wgt';
            }
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
    public function store(InvDyeChemIsuItemRequest $request) {
        $invisu=$this->invisu->find($request->inv_isu_id);
        $itemaccount=$this->itemaccount->find($request->item_account_id);

        $invdyechemtransaction=$this->invdyechemtransaction
        ->selectRaw(
        'inv_dye_chem_transactions.store_id,
        inv_dye_chem_transactions.company_id,
        sum(inv_dye_chem_transactions.store_qty) as store_qty'
        )
        ->where([['inv_dye_chem_transactions.store_id','=',$request->store_id]])
        ->where([['inv_dye_chem_transactions.company_id','=',$invisu->company_id]])
        ->where([['inv_dye_chem_transactions.item_account_id','=',$request->item_account_id]])
        ->groupBy([
        'inv_dye_chem_transactions.store_id',
        'inv_dye_chem_transactions.company_id',
        'inv_dye_chem_transactions.item_account_id'
        ])
        ->get()
        ->first();
        if(!$invdyechemtransaction){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock1'),200);
        }
        if($invdyechemtransaction->store_qty < $request->qty ){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock'),200);
        }

        $trans_type_id=2;
        \DB::beginTransaction();
        $invdyechemisuitem = $this->invdyechemisuitem->create(
        [
        'inv_isu_id'=> $request->inv_isu_id,         
        'item_account_id'=> $request->item_account_id,        
        'inv_dye_chem_isu_rq_item_id'=> $request->inv_dye_chem_isu_rq_item_id,        
        'batch'=> $request->batch,        
        'store_id'=> $request->store_id,
        'qty' => $request->qty,
        'room'=> $request->room,     
        'rack'=> $request->rack,     
        'shelf'=> $request->shelf,
        'remarks' => $request->remarks     
        ]);

        $x=$request->qty;
        $total_store_amount=0;
        try
        {
            while($x > 0) {
                $invdyechemrcvitem=$this->invdyechemtransaction
                ->selectRaw(
                'inv_dye_chem_transactions.store_id,
                inv_dye_chem_transactions.item_account_id,
                inv_dye_chem_transactions.inv_dye_chem_rcv_item_id,
                inv_dye_chem_transactions.supplier_id,
                inv_dye_chem_rcv_items.store_rate,
                sum(inv_dye_chem_transactions.store_qty) as store_qty'
                )
                ->join('inv_dye_chem_rcv_items',function($join){
                $join->on('inv_dye_chem_rcv_items.id','=','inv_dye_chem_transactions.inv_dye_chem_rcv_item_id');
                })
                ->where([['inv_dye_chem_transactions.store_id','=',$request->store_id]])
                ->where([['inv_dye_chem_transactions.company_id','=',$invisu->company_id]])
                ->where([['inv_dye_chem_transactions.item_account_id','=',$request->item_account_id]])
                ->groupBy([
                'inv_dye_chem_transactions.store_id',
                'inv_dye_chem_transactions.company_id',
                'inv_dye_chem_transactions.item_account_id',
                'inv_dye_chem_transactions.inv_dye_chem_rcv_item_id',
                'inv_dye_chem_transactions.supplier_id',
                'inv_dye_chem_rcv_items.store_rate',
                ])
                ->havingRaw('sum(inv_dye_chem_transactions.store_qty) > 0')
                ->orderBy('inv_dye_chem_transactions.inv_dye_chem_rcv_item_id')
                ->get()
                ->map(function($invdyechemrcvitem){
                return $invdyechemrcvitem;
                })
                ->first();

                if(!$invdyechemrcvitem){
                return response()->json(array('success' =>false , 'message'=>'Stock not found'),200);
                }

                if($x >= $invdyechemrcvitem->store_qty)
                {
                $iss_qty = $invdyechemrcvitem->store_qty;
                }
                else
                {
                $iss_qty = $x;
                }

                $store_amount=$iss_qty*$invdyechemrcvitem->store_rate;
                $total_store_amount+=$store_amount;
                $invdyechemtransaction=$this->invdyechemtransaction->create([
                'trans_type_id'=>$trans_type_id,
                'trans_date'=>$invisu->issue_date,
                'inv_dye_chem_rcv_item_id'=>$invdyechemrcvitem->inv_dye_chem_rcv_item_id,
                'inv_dye_chem_isu_item_id'=>$invdyechemisuitem->id,
                'item_account_id'=>$request->item_account_id,
                'company_id'=>$invisu->company_id,
                'supplier_id'=>$invdyechemrcvitem->supplier_id,
                'store_id'=>$request->store_id,
                'store_qty' => $iss_qty*-1,
                'store_rate' => $invdyechemrcvitem->store_rate,
                'store_amount'=> $store_amount
                ]);
                $x= $x - $invdyechemrcvitem->store_qty;
            }
            $this->invdyechemisuitem->update($invdyechemisuitem->id,['amount'=>$total_store_amount,'rate'=>$total_store_amount/$request->qty]);

        }
        catch(EXCEPTION $e)
        {
          \DB::rollback();
          throw $e;
        }
        \DB::commit();

      if($invdyechemisuitem){
        return response()->json(array('success' =>true ,'id'=>$invdyechemisuitem->id, 'inv_isu_id'=>$request->inv_isu_id,'seq'=>$request->seq,'message'=>'Saved Successfully'),200);
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
        $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'), '-Select-','');
        $rows = $this->invdyechemisuitem
        ->join('inv_isus',function($join){
        $join->on('inv_dye_chem_isu_items.inv_isu_id','=','inv_isus.id');
        })
        ->join('inv_dye_chem_isu_rq_items',function($join){
        $join->on('inv_dye_chem_isu_rq_items.id','=','inv_dye_chem_isu_items.inv_dye_chem_isu_rq_item_id');
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
        ->join('stores',function($join){
        $join->on('stores.id','=','inv_dye_chem_isu_items.store_id');
        })
        ->where([['inv_dye_chem_isu_items.id','=',$id]])
        ->orderBy('inv_dye_chem_isu_items.id','desc')
        ->get([
        'inv_dye_chem_isu_items.*',
        'inv_dye_chem_isu_rq_items.sub_process_id',
        'inv_dye_chem_isu_rq_items.per_on_batch_wgt',
        'inv_dye_chem_isu_rq_items.gram_per_ltr_liqure',
        'inv_dye_chem_isu_rq_items.sort_id',
        'itemcategories.name as item_category',
        'itemclasses.name as item_class',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code as uom_code',
        'stores.name as store_name',
        ])
        ->map(function($rows) use ($dyeingsubprocess){
        $rows->sub_process_name=$dyeingsubprocess[$rows->sub_process_id];
        $rows->item_desc=$rows->item_description.', '.$rows->specification;
        $rows->ratio='';
        if($rows->per_on_batch_wgt){
        $rows->ratio=$rows->per_on_batch_wgt.' % on Batch Wgt';
        }
        else if ($rows->gram_per_ltr_liqure){
        $rows->ratio=$rows->gram_per_ltr_liqure.' Gram/L. Liqure';
        }
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
    public function update(InvDyeChemIsuItemRequest $request, $id) {

    	/*$is_received=$this->invgeneralisuitem
        ->join('inv_general_rcv_items',function($join){
        $join->on('inv_general_rcv_items.inv_general_isu_item_id','=','inv_general_isu_items.id');
        })
        ->where([['inv_general_isu_items.id','=',$id]])
        ->get()
        ->first();
        if($is_received){
        return response()->json(array('success' =>false , 'message'=>'Received Found, So update not allowed'),200);
        }*/
      
        $invisu=$this->invisu->find($request->inv_isu_id);
        $itemaccount=$this->itemaccount->find($request->item_account_id);
        \DB::beginTransaction();
        $this->invdyechemtransaction->where([['inv_dye_chem_isu_item_id','=',$id]])->delete();

        $invdyechemtransaction=$this->invdyechemtransaction
        ->selectRaw(
        'inv_dye_chem_transactions.store_id,
        inv_dye_chem_transactions.company_id,
        sum(inv_dye_chem_transactions.store_qty) as store_qty'
        )
        ->where([['inv_dye_chem_transactions.store_id','=',$request->store_id]])
        ->where([['inv_dye_chem_transactions.company_id','=',$invisu->company_id]])
        ->where([['inv_dye_chem_transactions.item_account_id','=',$request->item_account_id]])
        ->groupBy([
        'inv_dye_chem_transactions.store_id',
        'inv_dye_chem_transactions.company_id',
        'inv_dye_chem_transactions.item_account_id'
        ])
        ->get()
        ->first();
        if(!$invdyechemtransaction){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock1'),200);
        }
        if($invdyechemtransaction->store_qty < $request->qty ){
            return response()->json(array('success' =>false , 'message'=>'Insufficient Stock'),200);
        }

        $trans_type_id=2;
        

        $invdyechemisuitem = $this->invdyechemisuitem->update($id,
        [
        //'inv_isu_id'=> $request->inv_isu_id,         
        'item_account_id'=> $request->item_account_id,        
        //'inv_dye_chem_isu_rq_item_id'=> $request->inv_dye_chem_isu_rq_item_id,        
        'batch'=> $request->batch,        
        'store_id'=> $request->store_id,
        'qty' => $request->qty,
        'room'=> $request->room,     
        'rack'=> $request->rack,     
        'shelf'=> $request->shelf,
        'remarks' => $request->remarks     
        ]);

        $x=$request->qty;
        $total_store_amount=0;
        try
        {
          while($x > 0) {
            $invdyechemrcvitem=$this->invdyechemtransaction
            ->selectRaw(
            'inv_dye_chem_transactions.store_id,
            inv_dye_chem_transactions.item_account_id,
            inv_dye_chem_transactions.inv_dye_chem_rcv_item_id,
            inv_dye_chem_transactions.supplier_id,
            inv_dye_chem_rcv_items.store_rate,
            sum(inv_dye_chem_transactions.store_qty) as store_qty'
            )
            ->join('inv_dye_chem_rcv_items',function($join){
            $join->on('inv_dye_chem_rcv_items.id','=','inv_dye_chem_transactions.inv_dye_chem_rcv_item_id');
            })
            ->where([['inv_dye_chem_transactions.store_id','=',$request->store_id]])
            ->where([['inv_dye_chem_transactions.company_id','=',$invisu->company_id]])
            ->where([['inv_dye_chem_transactions.item_account_id','=',$request->item_account_id]])
            ->groupBy([
            'inv_dye_chem_transactions.store_id',
            'inv_dye_chem_transactions.company_id',
            'inv_dye_chem_transactions.item_account_id',
            'inv_dye_chem_transactions.inv_dye_chem_rcv_item_id',
            'inv_dye_chem_transactions.supplier_id',
            'inv_dye_chem_rcv_items.store_rate',
            ])
            ->havingRaw('sum(inv_dye_chem_transactions.store_qty) > 0')
            ->orderBy('inv_dye_chem_transactions.inv_dye_chem_rcv_item_id')
            ->get()
            ->map(function($invdyechemrcvitem){
            return $invdyechemrcvitem;
            })
            ->first();

            if(!$invdyechemrcvitem){
            return response()->json(array('success' =>false , 'message'=>'Stock not found'),200);
            }

            if($x >= $invdyechemrcvitem->store_qty)
            {
              $iss_qty = $invdyechemrcvitem->store_qty;
            }
            else
            {
              $iss_qty = $x;
            }

            $store_amount=$iss_qty*$invdyechemrcvitem->store_rate;
            $total_store_amount+=$store_amount;
            $invdyechemtransaction=$this->invdyechemtransaction->create([
            'trans_type_id'=>$trans_type_id,
            'trans_date'=>$invisu->issue_date,
            'inv_dye_chem_rcv_item_id'=>$invdyechemrcvitem->inv_dye_chem_rcv_item_id,
            'inv_dye_chem_isu_item_id'=>$id,
            'item_account_id'=>$request->item_account_id,
            'company_id'=>$invisu->company_id,
            'supplier_id'=>$invdyechemrcvitem->supplier_id,
            'store_id'=>$request->store_id,
            'store_qty' => $iss_qty*-1,
            'store_rate' => $invdyechemrcvitem->store_rate,
            'store_amount'=> $store_amount
            ]);
            $x=$x - $invdyechemrcvitem->store_qty;
          }
         $this->invdyechemisuitem->update($id,['amount'=>$total_store_amount,'rate'=>$total_store_amount/$request->qty]);

        }
        catch(EXCEPTION $e)
        {
          \DB::rollback();
          throw $e;
        }
        \DB::commit();

      if($invdyechemisuitem){
        return response()->json(array('success' =>true ,'id'=>$id,'seq'=>$request->seq, 'inv_isu_id'=>$request->inv_isu_id,'message'=>'Saved Successfully'),200);
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
       /* if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }*/
    }


    public function getItem()
    {
        $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
        $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'), '-Select-','');
        $invisu=$this->invisu->find(request('inv_isu_id',0));
        
        if ($invisu->isu_against_id==208) {
            $rows=$this->invdyechemisurq
            ->join('prod_batches', function($join){
            $join->on('prod_batches.id', '=', 'inv_dye_chem_isu_rqs.prod_batch_id');
            })
            ->join('inv_dye_chem_isu_rq_items', function($join){
            $join->on('inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id', '=', 'inv_dye_chem_isu_rqs.id');
            })
            ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'inv_dye_chem_isu_rq_items.item_account_id');
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
            ->join('companies', function($join){
            $join->on('companies.id', '=', 'prod_batches.company_id');
            })
            ->where([['prod_batches.company_id','=',$invisu->company_id]])
            //->where([['inv_dye_chem_isu_rqs.menu_id','=',$invisu->isu_against_id]])
            ->where([['prod_batches.location_id','=',$invisu->location_id]])
            ->where([['inv_dye_chem_isu_rqs.rq_no','=',request('rq_no',0)]])
            ->selectRaw('
	            inv_dye_chem_isu_rq_items.*,
	            inv_dye_chem_isu_rq_items.id as inv_dye_chem_isu_rq_item_id,
	            itemcategories.name as category_name,
	            itemclasses.name as class_name,
	            item_accounts.id as item_account_id,
	            item_accounts.sub_class_name,
	            item_accounts.item_description,
	            item_accounts.specification,
	            uoms.code as uom_name,
	            companies.code as company_name,
	            inv_dye_chem_isu_rqs.rq_no
            ')
            ->get()
            ->map(function($rows) use($dyeingsubprocess){
            $rows->sub_process=$dyeingsubprocess[$rows->sub_process_id];
            $rows->item_desc=$rows->item_description.", ".$rows->specification;
            $rows->ratio='';
            if($rows->per_on_batch_wgt){
            $rows->ratio=$rows->per_on_batch_wgt.' % on Batch Wgt';
            }
            else if ($rows->gram_per_ltr_liqure){
            $rows->ratio=$rows->gram_per_ltr_liqure.' Gram/L. Liqure';
            }
            else if ($rows->rto_on_paste_wgt){
            $rows->ratio=$rows->rto_on_paste_wgt.' Ratio on Paste Wgt';
            }
            return $rows;
            });
            return Template::loadView('Inventory.DyeChem.InvDyeChemIsuItemMatrix',['rows'=>$rows,'store'=>$store]);
        }
        if ($invisu->isu_against_id==210) {
            $rows=$this->invdyechemisurq
            ->join('prod_aop_batches', function($join){
            $join->on('prod_aop_batches.id', '=', 'inv_dye_chem_isu_rqs.prod_aop_batch_id');
            })
            ->join('so_aops', function($join){
            $join->on('so_aops.id', '=', 'prod_aop_batches.so_aop_id');
            })
            ->join('inv_dye_chem_isu_rq_items', function($join){
            $join->on('inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id', '=', 'inv_dye_chem_isu_rqs.id');
            })
            ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'inv_dye_chem_isu_rq_items.item_account_id');
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
            ->join('companies', function($join){
            $join->on('companies.id', '=', 'so_aops.company_id');
            })
            ->where([['so_aops.company_id','=',$invisu->company_id]])
            //->where([['inv_dye_chem_isu_rqs.menu_id','=',$invisu->isu_against_id]])
            ->where([['inv_dye_chem_isu_rqs.location_id','=',$invisu->location_id]])
            ->where([['inv_dye_chem_isu_rqs.rq_no','=',request('rq_no',0)]])
            ->selectRaw('
                inv_dye_chem_isu_rq_items.*,
                inv_dye_chem_isu_rq_items.id as inv_dye_chem_isu_rq_item_id,
                itemcategories.name as category_name,
                itemclasses.name as class_name,
                item_accounts.id as item_account_id,
                item_accounts.sub_class_name,
                item_accounts.item_description,
                item_accounts.specification,
                uoms.code as uom_name,
                companies.code as company_name,
                inv_dye_chem_isu_rqs.rq_no
            ')
            ->get()
            ->map(function($rows) use($dyeingsubprocess){
            $rows->sub_process=$dyeingsubprocess[$rows->sub_process_id];
            $rows->item_desc=$rows->item_description.", ".$rows->specification;
            $rows->ratio='';
            if($rows->per_on_batch_wgt){
            $rows->ratio=$rows->per_on_batch_wgt.' % on Batch Wgt';
            }
            else if ($rows->gram_per_ltr_liqure){
            $rows->ratio=$rows->gram_per_ltr_liqure.' Gram/L. Liqure';
            }
            else if ($rows->rto_on_paste_wgt){
            $rows->ratio=$rows->rto_on_paste_wgt.' Ratio on Paste Wgt';
            }
            return $rows;
            });
            return Template::loadView('Inventory.DyeChem.InvDyeChemIsuItemMatrix',['rows'=>$rows,'store'=>$store]);
        }
        else {
            $rows=$this->invdyechemisurq
            // ->join('prod_batches', function($join){
            // $join->on('prod_batches.id', '=', 'inv_dye_chem_isu_rqs.prod_batch_id');
            // })
            ->join('inv_dye_chem_isu_rq_items', function($join){
            $join->on('inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id', '=', 'inv_dye_chem_isu_rqs.id');
            })
            ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'inv_dye_chem_isu_rq_items.item_account_id');
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
            ->join('companies', function($join){
            $join->on('companies.id', '=', 'inv_dye_chem_isu_rqs.company_id');
            })
            ->where([['inv_dye_chem_isu_rqs.company_id','=',$invisu->company_id]])
            ->where([['inv_dye_chem_isu_rqs.menu_id','=',$invisu->isu_against_id]])
            ->where([['inv_dye_chem_isu_rqs.location_id','=',$invisu->location_id]])
            ->where([['inv_dye_chem_isu_rqs.rq_no','=',request('rq_no',0)]])
            ->selectRaw('
            inv_dye_chem_isu_rq_items.*,
            inv_dye_chem_isu_rq_items.id as inv_dye_chem_isu_rq_item_id,
            itemcategories.name as category_name,
            itemclasses.name as class_name,
            item_accounts.id as item_account_id,
            item_accounts.sub_class_name,
            item_accounts.item_description,
            item_accounts.specification,
            uoms.code as uom_name,
            companies.code as company_name,
            inv_dye_chem_isu_rqs.rq_no
            ')
            ->get()
            ->map(function($rows) use($dyeingsubprocess){
            $rows->sub_process=$dyeingsubprocess[$rows->sub_process_id];
            $rows->item_desc=$rows->item_description.", ".$rows->specification;
            $rows->ratio='';
            if($rows->per_on_batch_wgt){
            $rows->ratio=$rows->per_on_batch_wgt.' % on Batch Wgt';
            }
            else if ($rows->gram_per_ltr_liqure){
            $rows->ratio=$rows->gram_per_ltr_liqure.' Gram/L. Liqure';
            }
            else if ($rows->rto_on_paste_wgt){
            $rows->ratio=$rows->rto_on_paste_wgt.' Ratio on Paste Wgt';
            }
            return $rows;
            });
            return Template::loadView('Inventory.DyeChem.InvDyeChemIsuItemMatrix',['rows'=>$rows,'store'=>$store]);
        }
    }
}