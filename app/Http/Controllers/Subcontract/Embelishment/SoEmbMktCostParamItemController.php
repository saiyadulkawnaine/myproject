<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostParamRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostParamItemRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostQpriceRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbMktCostParamItemRequest;

class SoEmbMktCostParamItemController extends Controller {

    private $soaopmktcost;
    private $soaopmktcostparam;
    private $soaopmktcostparamitem;
    private $soaop;
    private $itemaccount;
    private $uom;
    private $colorrange;
    private $color;
    private $embelishmenttype;
    private $user;
    private $soaopmktcostqprice;

    public function __construct(
        SoEmbMktCostRepository $soaopmktcost,
        SoEmbMktCostParamRepository $soaopmktcostparam, 
        SoEmbMktCostParamItemRepository $soaopmktcostparamitem,
        SoEmbMktCostQpriceRepository $soaopmktcostqprice,
        SoEmbRepository $soaop,
        ItemAccountRepository $itemaccount,
        UomRepository $uom,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        EmbelishmentTypeRepository $embelishmenttype,
        UserRepository $user
    ) {
        $this->soaopmktcost = $soaopmktcost;
        $this->soaopmktcostparam = $soaopmktcostparam;
        $this->soaopmktcostparamitem = $soaopmktcostparamitem;
        $this->soaopmktcostqprice = $soaopmktcostqprice;
        $this->soaop = $soaop;
        $this->itemaccount = $itemaccount;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->embelishmenttype = $embelishmenttype;
        $this->user = $user;

        $this->middleware('auth');

        //$this->middleware('permission:view.soaopmktcostparamitems',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.soaopmktcostparamitems', ['only' => ['store']]);
        //$this->middleware('permission:edit.soaopmktcostparamitems',   ['only' => ['update']]);
        //$this->middleware('permission:delete.soaopmktcostparamitems', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

      $rows = $this->soaopmktcostparam
      ->join('so_aop_mkt_cost_param_items',function($join){
        $join->on('so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id','=','so_aop_mkt_cost_params.id');
      })
      ->join('item_accounts',function($join){
        $join->on('so_aop_mkt_cost_param_items.item_account_id','=','item_accounts.id');
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
      ->where([['so_aop_mkt_cost_params.id','=',request('so_aop_mkt_cost_param_id',0)]])
      ->orderBy('so_aop_mkt_cost_param_items.id','desc')
      ->get([
        'so_aop_mkt_cost_param_items.*',
        'itemcategories.name as category_name',
        'itemclasses.name as class_name',
        'item_accounts.sub_class_name',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code as uom_name',
        'uoms.code as store_uom',
      ]);

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
    public function store(SoEmbMktCostParamItemRequest $request) {
        $soaopmktcostparam=$this->soaopmktcostparam->find($request->so_aop_mkt_cost_param_id);
        $approved=$this->soaopmktcostqprice
        ->where([['so_aop_mkt_cost_id','=', $soaopmktcostparam->so_aop_mkt_cost_id]])
        ->get(['first_approved_at'])
        ->first();

        if ($approved) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }
        

        $soaopmktcostparamitem = $this->soaopmktcostparamitem->create(
        [
            'so_aop_mkt_cost_param_id'=> $request->so_aop_mkt_cost_param_id,         
            'item_account_id'=> $request->item_account_id,
            'rto_on_paste_wgt'=> $request->rto_on_paste_wgt,        
            'qty' => $request->qty,
            'rate' => $request->rate,
            'last_rcv_rate' => $request->last_rcv_rate,
            'last_receive_no' => $request->last_receive_no,
            'amount' => $request->amount,
            'remarks'=> $request->remarks
        ]);
        if($soaopmktcostparamitem){
        return response()->json(array('success' =>true ,'id'=>$soaopmktcostparamitem->id, 'so_aop_mkt_cost_param_id'=>$request->so_aop_mkt_cost_param_id,'message'=>'Saved Successfully'),200);
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

        $rows = $this->soaopmktcostparamitem
        ->join('so_aop_mkt_cost_params',function($join){
            $join->on('so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id','=','so_aop_mkt_cost_params.id');
        })
        ->join('item_accounts',function($join){
            $join->on('so_aop_mkt_cost_param_items.item_account_id','=','item_accounts.id');
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
        ->where([['so_aop_mkt_cost_param_items.id','=',$id]])
        ->get([
            'so_aop_mkt_cost_param_items.*',
            'itemcategories.name as item_category',
            'itemclasses.name as item_class',
            'item_accounts.sub_class_name',
            'item_accounts.item_description as item_desc',
            'item_accounts.specification',
            'uoms.code as uom_code',
        ])
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
    public function update(SoEmbMktCostParamItemRequest $request, $id) {
        $soaopmktcostparam=$this->soaopmktcostparam->find($request->so_aop_mkt_cost_param_id);
        $approved=$this->soaopmktcostqprice
        ->where([['so_aop_mkt_cost_id','=', $soaopmktcostparam->so_aop_mkt_cost_id]])
        ->get(['first_approved_at'])
        ->first();

        if ($approved) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }

        $user = \Auth::user();
        $authenticatedUser=$this->user
        ->join('role_user',function($join){
            $join->on('role_user.user_id','=','users.id');
        })
        ->join('roles',function($join){
            $join->on('role_user.role_id','=','roles.id');
        })
        ->where([['users.id','=',$user->id]])
        ->get(['roles.*'])
        ->first();
        
        if ($authenticatedUser->level ==4 || $authenticatedUser->level ==5) {
            $soaopmktcostparamitem = $this->soaopmktcostparamitem->update($id,
            [     
                'item_account_id'=> $request->item_account_id,             
                'rto_on_paste_wgt'=> $request->rto_on_paste_wgt,    
                'qty' => $request->qty,
                'rate' => $request->rate,
                'last_rcv_rate' => $request->last_rcv_rate,
                'last_receive_no' => $request->last_receive_no,
                'amount' => $request->amount,
                'remarks'=> $request->remarks
            ]);
        }

        if($soaopmktcostparamitem){
            return response()->json(array('success' =>true ,'id'=>$id, 'so_aop_mkt_cost_param_id'=>$request->so_aop_mkt_cost_param_id,'message'=>'Update Successfully'),200);
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
        if($this->soaopmktcostparamitem->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


    public function getItem()
    {
       // $soaopmktcost=$this->soaopmktcost->find(request('so_aop_mkt_cost_id',0));

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
        ->leftJoin(\DB::raw("(
            select 
            max(inv_rcvs.receive_no) as last_receive_no,
            inv_dye_chem_transactions.item_account_id,
            inv_dye_chem_transactions.store_rate
            from inv_rcvs
            join inv_dye_chem_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
            join inv_dye_chem_rcv_items on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
            join inv_dye_chem_transactions on inv_dye_chem_rcv_items.id=inv_dye_chem_transactions.inv_dye_chem_rcv_item_id
            where inv_dye_chem_transactions.trans_type_id=1
            and inv_dye_chem_transactions.id in (
            select
            m.id 
            from(
            SELECT 
            max(inv_dye_chem_transactions.id) as id,
            inv_dye_chem_transactions.item_account_id
            FROM inv_dye_chem_transactions 
            where  inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=1
            group by 
            inv_dye_chem_transactions.item_account_id)m
            )
            group by 
            inv_dye_chem_transactions.item_account_id,
            inv_dye_chem_transactions.store_rate
        ) lastStoreRate"), "lastStoreRate.item_account_id", "=", "item_accounts.id")

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
        ->selectRaw('
            itemcategories.name as category_name,
            itemclasses.name as class_name,
            item_accounts.id,
            item_accounts.id as item_account_id,
            item_accounts.sub_class_name,
            item_accounts.item_description,
            item_accounts.specification,
            uoms.code as uom_name,
            lastStoreRate.last_receive_no,
            lastStoreRate.store_rate as last_rcv_rate,
            stock.qty as stock_qty
        ')
        ->get()
        ->map(function($rows){
            $rows->rate=number_format($rows->rate,4);
            return $rows;
        });
        
        echo json_encode($rows);
    }


    public function getMasterCopyParameter() {
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
        
        $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbTypes(),'name','id'),'-Select-','');

        $rows=$this->soaopmktcost
        ->join('so_aop_mkt_cost_params',function($join){
            $join->on('so_aop_mkt_cost_params.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->where([['so_aop_mkt_costs.id','=',request('so_aop_mkt_cost_id',0)]])
        ->where([['so_aop_mkt_cost_params.id','!=',request('so_aop_mkt_cost_param_id',0)]])
        ->selectRaw('
            so_aop_mkt_cost_params.id,
            so_aop_mkt_cost_params.so_aop_mkt_cost_id,
            so_aop_mkt_cost_params.print_type_id,
            so_aop_mkt_cost_params.gsm_weight,
            so_aop_mkt_cost_params.fabric_wgt,
            so_aop_mkt_cost_params.paste_wgt,
            so_aop_mkt_cost_params.dia,
            so_aop_mkt_cost_params.offer_qty,
            so_aop_mkt_cost_params.color_ratio_from,
            so_aop_mkt_cost_params.color_ratio_to,
            so_aop_mkt_cost_params.no_of_color_from,
            so_aop_mkt_cost_params.no_of_color_to,
            so_aop_mkt_cost_params.colorrange_id
        ')
        ->get()
        ->map(function($rows) use($embelishmenttype,$colorrange){
            $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:'';
            $rows->print_type=$rows->print_type_id?$embelishmenttype[$rows->print_type_id]:'';
            return $rows;
        });

        echo json_encode($rows);

    }


    public function copyItem(){
        $so_aop_mkt_cost_param_id=request('so_aop_mkt_cost_param_id',0);
        $master_fab_id=request('master_fab_id',0);


        $soaopmktcostparam=$this->soaopmktcostparam
        ->join('so_aop_mkt_costs',function($join){
            $join->on('so_aop_mkt_cost_params.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->where([['so_aop_mkt_cost_params.id','=',$so_aop_mkt_cost_param_id]])
        ->selectRaw('
            so_aop_mkt_cost_params.id,
            so_aop_mkt_cost_params.paste_wgt,
            so_aop_mkt_cost_params.fabric_wgt
        ')
        ->orderBy('so_aop_mkt_cost_params.id','desc')
        ->get()
        ->first();
      

        $rows = $this->soaopmktcostparam
        ->join('so_aop_mkt_costs',function($join){
            $join->on('so_aop_mkt_cost_params.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->join('so_aop_mkt_cost_param_items',function($join){
            $join->on('so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id','=','so_aop_mkt_cost_params.id');
        })
        ->join('item_accounts',function($join){
            $join->on('so_aop_mkt_cost_param_items.item_account_id','=','item_accounts.id');
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
        ->leftJoin(\DB::raw("(
            select 
            max(inv_rcvs.receive_no) as last_receive_no,
            inv_dye_chem_transactions.item_account_id,
            inv_dye_chem_transactions.store_rate
            from inv_rcvs
            join inv_dye_chem_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
            join inv_dye_chem_rcv_items on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
            join inv_dye_chem_transactions on inv_dye_chem_rcv_items.id=inv_dye_chem_transactions.inv_dye_chem_rcv_item_id
            where inv_dye_chem_transactions.trans_type_id=1
            and inv_dye_chem_transactions.id in (
            select
            m.id 
            from(
            SELECT 
            max(inv_dye_chem_transactions.id) as id,
            inv_dye_chem_transactions.item_account_id
            FROM inv_dye_chem_transactions 
            where  inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=1
            group by 
            inv_dye_chem_transactions.item_account_id)m
            )
            group by 
            inv_dye_chem_transactions.item_account_id,
            inv_dye_chem_transactions.store_rate
        ) lastStoreRate"), "lastStoreRate.item_account_id", "=", "item_accounts.id")
        ->where([['so_aop_mkt_cost_params.id','=',$master_fab_id]])
        ->orderBy('so_aop_mkt_cost_param_items.id','desc')
        ->get([
            'so_aop_mkt_costs.exch_rate',
            'so_aop_mkt_cost_param_items.*',
            'itemcategories.name as category_name',
            'itemclasses.name as class_name',
            'item_accounts.sub_class_name',
            'item_accounts.item_description',
            'item_accounts.specification',
            'uoms.code as uom_name',
            'uoms.code as store_uom',
            'lastStoreRate.last_receive_no',
            'lastStoreRate.store_rate as last_rcv_rate'
        ]);
        $soaopmktcostparam=$this->soaopmktcostparam->find($so_aop_mkt_cost_param_id);
        $approved=$this->soaopmktcostqprice
        ->where([['so_aop_mkt_cost_id','=', $soaopmktcostparam->so_aop_mkt_cost_id]])
        ->get(['first_approved_at'])
        ->first();

        if ($approved) {
            return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }
        // dd($rows);die;

        foreach($rows as $row){
            $qty=0;
            if($row->rto_on_paste_wgt){
                $qty=($soaopmktcostparam->paste_wgt*$row->rto_on_paste_wgt)/100;
            }
            $soaopmktcostparamitem = $this->soaopmktcostparamitem->create(
                [
                    'so_aop_mkt_cost_param_id'=> $so_aop_mkt_cost_param_id,         
                    'item_account_id'=> $row->item_account_id,        
                    'rto_on_paste_wgt'=> $row->rto_on_paste_wgt,        
                    'qty' => $qty,
                    'rate' => $row->rate,
                    'last_rcv_rate' => $row->last_rcv_rate,
                    'last_receive_no' => $row->last_receive_no,
                    'amount' =>$qty*$row->rate,
                    'remarks'=> $row->remarks
                ]);
        }
        return response()->json(array('success' =>true ,'id'=>$soaopmktcostparamitem->id, 'so_aop_mkt_cost_param_id'=>$so_aop_mkt_cost_param_id,'message'=>'Saved Successfully'),200);
    }
}

