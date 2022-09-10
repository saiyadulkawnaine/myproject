<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqItemRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;

use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvPurReqItemRequest;

class InvPurReqItemController extends Controller {

    private $invpurreq;
    private $invpurreqitem;
    private $itemaccount;
    private $itemclass;
    private $itemcategory;


    public function __construct(InvPurReqRepository $invpurreq,InvPurReqItemRepository $invpurreqitem,ItemAccountRepository $itemaccount,ItemclassRepository $itemclass,ItemcategoryRepository $itemcategory) {
        $this->invpurreq = $invpurreq;
        $this->invpurreqitem = $invpurreqitem;
        $this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;


        $this->middleware('auth');

        // $this->middleware('permission:view.invpurreqitems',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.invpurreqitems', ['only' => ['store']]);
        // $this->middleware('permission:edit.invpurreqitems',   ['only' => ['update']]);
        // $this->middleware('permission:delete.invpurreqitems', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $invpurreqitems=array();
        $rows=$this->invpurreqitem
            ->join('inv_pur_reqs',function($join){
                $join->on('inv_pur_reqs.id','=','inv_pur_req_items.inv_pur_req_id');
            })
            ->join('item_accounts',function($join){
                $join->on('item_accounts.id','=','inv_pur_req_items.item_account_id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','item_accounts.uom_id');
            })
            ->where([['inv_pur_req_id','=',request('inv_pur_req_id',0)]])
            ->orderBy('inv_pur_req_items.id','desc')
            ->get([
            'inv_pur_req_items.*',
            'item_accounts.id as item_account_id',
            'item_accounts.item_description',
            'item_accounts.specification',
            'item_accounts.sub_class_name',
            'item_accounts.uom_id',
            'uoms.code as uom_code'
        ]);

        foreach($rows as $row){
            $invpurreqitem['id']=$row->id;
            $invpurreqitem['item_description']=$row->sub_class_name.", ".$row->item_description.", ".$row->specification;
            $invpurreqitem['uom_code']=$row->uom_code;
            $invpurreqitem['qty']=number_format($row->qty,2);
            $invpurreqitem['rate']=$row->rate;
            $invpurreqitem['amount']=number_format($row->amount,2);   
            $invpurreqitem['remarks']=$row->remarks;
            array_push($invpurreqitems,$invpurreqitem);
        }
        echo json_encode($invpurreqitems);
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
    public function store(InvPurReqItemRequest $request) {
        $req=$this->invpurreq->find($request->inv_pur_req_id);
        if($req->first_approved_by){
            return response()->json(array('success' => false,'message' => 'This requisition is approved so insert new item is not allowed'),200);
        }

        $invpurreqitem = $this->invpurreqitem->create([
            'inv_pur_req_id'=>$request->inv_pur_req_id,'item_account_id'=>$request->item_account_id,
            'department_id'=>$request->department_id,
            'qty'=>$request->qty,
            'rate'=>$request->rate,
            'amount'=>$request->amount,
            'remarks'=>$request->remarks
            ]);
		if($invpurreqitem){
			return response()->json(array('success' => true,'id' =>  $invpurreqitem->id,'message' => 'Save Successfully'),200);
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
        
    $invpurreqitem = $this->invpurreqitem
        /* ->selectRaw('inv_pur_req_items.id,
            inv_pur_req_items.item_account_id,
            item_accounts.item_description,
            item_accounts.itemcategory_id,
            item_accounts.itemclass_id,
            item_accounts.reorder_level,
            item_accounts.sub_class_name,
            item_accounts.specification,
            item_accounts.uom_id,
            inv_pur_req_items.qty,   
            inv_pur_req_items.rate,
            inv_pur_req_items.amount,
            inv_pur_req_items.remarks') */
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_pur_req_items.item_account_id');
        })
        ->join('inv_pur_reqs',function($join){
           $join->on('inv_pur_reqs.id','=','inv_pur_req_items.inv_pur_req_id');
        })
        ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->where([['inv_pur_req_items.id','=',$id]])
        ->get([
           'inv_pur_req_items.*',
           //'inv_pur_reqs.id',
           'item_accounts.item_description',
           'item_accounts.sub_class_name',
           'item_accounts.specification',
           'item_accounts.uom_id',
           'uoms.code as uom_code',
       ])
       ->first();
       $invpurreqitem->item_description=$invpurreqitem->sub_class_name.", ".$invpurreqitem->item_description.", ".$invpurreqitem->specification;

        $row ['fromData'] = $invpurreqitem;
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
    public function update(InvPurReqItemRequest $request, $id) {
        $req=$this->invpurreq->find($request->inv_pur_req_id);
        // if($req->first_approved_by){
        //     return response()->json(array('success' => false,'message' => 'This Requisition is approved so update not allowed'),200);
        // }
        if($req->first_approved_by && !$req->final_approved_by){
            $this->invpurreqitem->update($id,[ 'rate'=>$request->rate,'amount'=>$request->amount ]);
            return response()->json(array('success' => false,'message' => 'This Requisition is approved.Only Rate Can be Updated before final approval'),200);
        }
        if($req->final_approved_by)
        {
            return response()->json(array('success' => false,'message' => 'This Requisition is approved so update not allowed'),200);
        }

        $invpurreqitem = $this->invpurreqitem->update($id,[
            'inv_pur_req_id'=>$request->inv_pur_req_id,'item_account_id'=>$request->item_account_id,
            'department_id'=>$request->department_id,
            'qty'=>$request->qty,
            'rate'=>$request->rate,
            'amount'=>$request->amount,
            'remarks'=>$request->remarks
            ]);
		if($invpurreqitem){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $req=$this->invpurreq->find($request->inv_pur_req_id);
        if($req->first_approved_by){
            return response()->json(array('success' => false,'message' => 'This Requisition is approved so delete not allowed'),200);

        }
        if($this->invpurreqitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        } 
    }
    public function getItemAccount(){
        $rows=$this->itemaccount
        ->join('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->join('itemclasses',function($join){
            $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->leftJoin('yarncounts',function($join){
            $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->leftJoin('yarntypes',function($join){
            $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->leftJoin('compositions',function($join){
            $join->on('compositions.id','=','item_accounts.composition_id');
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','item_accounts.color_id');
        })
        ->leftJoin('sizes',function($join){
            $join->on('sizes.id','=','item_accounts.size_id');
        })
        ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->when(request('id'), function ($q) {
            return $q->where('item_accounts.id', '=', request('id', 0));
        })
        ->where([['item_accounts.status_id','=',1]])
        ->whereIn('itemcategories.identity',[6,7,8,9])
        ->orderBy('item_accounts.id','desc')
        ->get([
            'item_accounts.*',
            'itemcategories.name',
            'itemclasses.name as class_name',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'compositions.name as composition',
            'colors.name as color',
            'sizes.name as size',
            'uoms.code as uom_code'
        ]);
        echo json_encode($rows);
    }

}
