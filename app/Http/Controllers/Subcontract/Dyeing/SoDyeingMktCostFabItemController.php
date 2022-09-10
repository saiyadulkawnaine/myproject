<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabItemRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpriceRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingMktCostFabItemRequest;

class SoDyeingMktCostFabItemController extends Controller {

    private $sodyeingmktcost;
    private $sodyeingmktcostfab;
    private $sodyeingmktcostfabitem;
    private $sodyeingmktcostqprice;

    private $sodyeing;
    private $itemaccount;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;

    public function __construct(
        SoDyeingMktCostRepository $sodyeingmktcost,
        SoDyeingMktCostFabRepository $sodyeingmktcostfab, 
        SoDyeingMktCostFabItemRepository $sodyeingmktcostfabitem,
        SoDyeingMktCostQpriceRepository $sodyeingmktcostqprice,
        SoDyeingRepository $sodyeing,
        ItemAccountRepository $itemaccount,

        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        UomRepository $uom,
        ColorrangeRepository $colorrange,
        ColorRepository $color
    ) {
        $this->sodyeingmktcost = $sodyeingmktcost;
        $this->sodyeingmktcostfab = $sodyeingmktcostfab;
        $this->sodyeingmktcostfabitem = $sodyeingmktcostfabitem;
        $this->sodyeingmktcostqprice = $sodyeingmktcostqprice;
        $this->sodyeing = $sodyeing;
        $this->itemaccount = $itemaccount;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;

        $this->middleware('auth');

        //$this->middleware('permission:view.sodyeingmktcostfabitems',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingmktcostfabitems', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingmktcostfabitems',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingmktcostfabitems', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

      $rows = $this->sodyeingmktcostfab
      ->join('so_dyeing_mkt_cost_fab_items',function($join){
        $join->on('so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id','=','so_dyeing_mkt_cost_fabs.id');
      })
      ->join('item_accounts',function($join){
        $join->on('so_dyeing_mkt_cost_fab_items.item_account_id','=','item_accounts.id');
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
      ->where([['so_dyeing_mkt_cost_fabs.id','=',request('so_dyeing_mkt_cost_fab_id',0)]])
      ->orderBy('so_dyeing_mkt_cost_fab_items.id','desc')
      ->get([
        'so_dyeing_mkt_cost_fab_items.*',
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
    public function store(SoDyeingMktCostFabItemRequest $request) {
        $approved=$this->sodyeingmktcostfab
        ->join('so_dyeing_mkt_costs',function($join){
            $join->on('so_dyeing_mkt_cost_fabs.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->join('so_dyeing_mkt_cost_qprices',function($join){
            $join->on('so_dyeing_mkt_cost_qprices.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->where([['so_dyeing_mkt_cost_fabs.id','=', $request->so_dyeing_mkt_cost_fab_id]])
        ->get(['so_dyeing_mkt_cost_qprices.final_approved_at'])->first();

        if ($approved->final_approved_at) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }

        $sodyeingmktcostfabitem = $this->sodyeingmktcostfabitem->create(
        [
            'so_dyeing_mkt_cost_fab_id'=> $request->so_dyeing_mkt_cost_fab_id,         
            'item_account_id'=> $request->item_account_id,            
            'per_on_fabric_wgt'=> $request->per_on_fabric_wgt,
            'gram_per_ltr_liqure'=> $request->gram_per_ltr_liqure,        
            'qty' => $request->qty,
            'rate' => $request->rate,
            'last_rcv_rate' => $request->last_rcv_rate,
            'last_receive_no' => $request->last_receive_no,
            'amount' => $request->amount,
            'remarks'=> $request->remarks
        ]);
        if($sodyeingmktcostfabitem){
        return response()->json(array('success' =>true ,'id'=>$sodyeingmktcostfabitem->id, 'so_dyeing_mkt_cost_fab_id'=>$request->so_dyeing_mkt_cost_fab_id,'message'=>'Saved Successfully'),200);
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

        $rows = $this->sodyeingmktcostfabitem
        ->join('so_dyeing_mkt_cost_fabs',function($join){
            $join->on('so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id','=','so_dyeing_mkt_cost_fabs.id');
        })
        ->join('item_accounts',function($join){
            $join->on('so_dyeing_mkt_cost_fab_items.item_account_id','=','item_accounts.id');
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
        ->where([['so_dyeing_mkt_cost_fab_items.id','=',$id]])
        ->get([
            'so_dyeing_mkt_cost_fab_items.*',
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
    public function update(SoDyeingMktCostFabItemRequest $request, $id) {
        $approved=$this->sodyeingmktcostfab
        ->join('so_dyeing_mkt_costs',function($join){
            $join->on('so_dyeing_mkt_cost_fabs.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->join('so_dyeing_mkt_cost_qprices',function($join){
            $join->on('so_dyeing_mkt_cost_qprices.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->where([['so_dyeing_mkt_cost_fabs.id','=', $request->so_dyeing_mkt_cost_fab_id]])
        ->get(['so_dyeing_mkt_cost_qprices.final_approved_at'])->first();

        if ($approved->final_approved_at) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }

        $sodyeingmktcostfabitem = $this->sodyeingmktcostfabitem->update($id,
        [     
            'item_account_id'=> $request->item_account_id,             
            'per_on_fabric_wgt'=> $request->per_on_fabric_wgt,
            'gram_per_ltr_liqure'=> $request->gram_per_ltr_liqure,        
            'qty' => $request->qty,
            'rate' => $request->rate,
            'last_rcv_rate' => $request->last_rcv_rate,
            'last_receive_no' => $request->last_receive_no,
            'amount' => $request->amount,
            'remarks'=> $request->remarks
        ]);

        if($sodyeingmktcostfabitem){
        return response()->json(array('success' =>true ,'id'=>$id, 'so_dyeing_mkt_cost_fab_id'=>$request->so_dyeing_mkt_cost_fab_id,'message'=>'Update Successfully'),200);
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
        if($this->sodyeingmktcostfabitem->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }


    public function getItem()
    {
       // $sodyeingmktcost=$this->sodyeingmktcost->find(request('so_dyeing_mkt_cost_id',0));

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


    public function getMasterCopyFabric() {
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
        return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
        return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
        'autoyarns.*',
        'constructions.name',
        'compositions.name as composition_name',
        'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
            $fabricDescriptionArr[$row->id]=$row->name;
            $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->sodyeingmktcost
        ->join('so_dyeing_mkt_cost_fabs',function($join){
            $join->on('so_dyeing_mkt_cost_fabs.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->where([['so_dyeing_mkt_costs.id','=',request('so_dyeing_mkt_cost_id',0)]])
        ->where([['so_dyeing_mkt_cost_fabs.id','!=',request('so_dyeing_mkt_cost_fab_id',0)]])
        ->selectRaw('
            so_dyeing_mkt_cost_fabs.id,
            so_dyeing_mkt_cost_fabs.so_dyeing_mkt_cost_id,
            so_dyeing_mkt_cost_fabs.liqure_ratio,
            so_dyeing_mkt_cost_fabs.liqure_wgt,
            so_dyeing_mkt_cost_fabs.autoyarn_id,
            so_dyeing_mkt_cost_fabs.dyeing_type_id,
            so_dyeing_mkt_cost_fabs.gsm_weight,
            so_dyeing_mkt_cost_fabs.fabric_wgt,
            so_dyeing_mkt_cost_fabs.dia,
            so_dyeing_mkt_cost_fabs.offer_qty,
            so_dyeing_mkt_cost_fabs.color_ratio_from,
            so_dyeing_mkt_cost_fabs.color_ratio_to,
            so_dyeing_mkt_cost_fabs.colorrange_id
        ')
        ->get()
        ->map(function($rows) use($desDropdown,$colorrange,$dyetype,$fabricDescriptionArr){
            $rows->fabrication=$desDropdown[$rows->autoyarn_id];
            $rows->construction_name=$fabricDescriptionArr[$rows->autoyarn_id];
            $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:'';
            $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:'';
            return $rows;
        });

        echo json_encode($rows);

    }


    public function copyItem(){
      $so_dyeing_mkt_cost_fab_id=request('so_dyeing_mkt_cost_fab_id',0);
      $master_fab_id=request('master_fab_id',0);


      $sodyeingmktcostfab=$this->sodyeingmktcostfab
        ->join('so_dyeing_mkt_costs',function($join){
        $join->on('so_dyeing_mkt_cost_fabs.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->where([['so_dyeing_mkt_cost_fabs.id','=',$so_dyeing_mkt_cost_fab_id]])
        ->selectRaw('
            so_dyeing_mkt_cost_fabs.id,
            so_dyeing_mkt_cost_fabs.liqure_ratio,
            so_dyeing_mkt_cost_fabs.liqure_wgt,
            so_dyeing_mkt_cost_fabs.fabric_wgt
        ')
        ->orderBy('so_dyeing_mkt_cost_fabs.id','desc')
        ->get()
        ->first();
      

    $rows = $this->sodyeingmktcostfab
    ->join('so_dyeing_mkt_costs',function($join){
        $join->on('so_dyeing_mkt_cost_fabs.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
    })
    ->join('so_dyeing_mkt_cost_fab_items',function($join){
        $join->on('so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id','=','so_dyeing_mkt_cost_fabs.id');
    })
    ->join('item_accounts',function($join){
        $join->on('so_dyeing_mkt_cost_fab_items.item_account_id','=','item_accounts.id');
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
    // ->leftJoin(\DB::raw("(SELECT 
    //     inv_dye_chem_transactions.item_account_id,
    //     sum(inv_dye_chem_transactions.store_qty) as qty 
    //     FROM inv_dye_chem_transactions 
    //     where  inv_dye_chem_transactions.deleted_at is null
    //     group by inv_dye_chem_transactions.item_account_id
    // ) stock"), "stock.item_account_id", "=", "item_accounts.id")

    // ->leftJoin(\DB::raw("(SELECT 
    //     inv_dye_chem_transactions.item_account_id,
    //     sum(inv_dye_chem_transactions.store_amount) as amount 
    //     FROM inv_dye_chem_transactions 
    //     where  inv_dye_chem_transactions.deleted_at is null
    //     and inv_dye_chem_transactions.trans_type_id=1
    //     group by inv_dye_chem_transactions.item_account_id
    // ) rcvamount"), "rcvamount.item_account_id", "=", "item_accounts.id")

    // ->leftJoin(\DB::raw("(SELECT 
    //     inv_dye_chem_transactions.item_account_id,
    //     sum(inv_dye_chem_transactions.store_amount) as amount 
    //     FROM inv_dye_chem_transactions 
    //     where  inv_dye_chem_transactions.deleted_at is null
    //     and inv_dye_chem_transactions.trans_type_id=2
    //     group by inv_dye_chem_transactions.item_account_id
    // ) isuamount"), "isuamount.item_account_id", "=", "item_accounts.id")
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
    ->where([['so_dyeing_mkt_cost_fabs.id','=',$master_fab_id]])
    ->orderBy('so_dyeing_mkt_cost_fab_items.id','desc')
    ->get([
        'so_dyeing_mkt_costs.exch_rate',
        'so_dyeing_mkt_cost_fab_items.*',
        'itemcategories.name as category_name',
        'itemclasses.name as class_name',
        'item_accounts.sub_class_name',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code as uom_name',
        'uoms.code as store_uom',
        'lastStoreRate.last_receive_no',
        'lastStoreRate.store_rate as last_rcv_rate'
    ])
    ->map(function($rows) {
        // $amount=$rows->amount_rcv-$rows->amount_isu;
        // $rows->rate=0;
        // if($rows->stock_qty){
        // $rows->rate=$amount/$rows->stock_qty; 
        // }
        // if ($rows->exch_rate) {
        //     $rows->rate=$rows->rate/$rows->exch_rate;
        // }
        return $rows;
    });

   // dd($rows);die;

    foreach($rows as $row){
        $qty=0;
        if($row->per_on_fabric_wgt){
            $qty=$sodyeingmktcostfab->fabric_wgt*($row->per_on_fabric_wgt/100);
        }
        if($row->gram_per_ltr_liqure){
            $qty=($sodyeingmktcostfab->liqure_wgt*$row->gram_per_ltr_liqure)/1000;
        }
        $sodyeingmktcostfabitem = $this->sodyeingmktcostfabitem->create(
            [
                'so_dyeing_mkt_cost_fab_id'=> $so_dyeing_mkt_cost_fab_id,         
                'item_account_id'=> $row->item_account_id,        
                'per_on_fabric_wgt'=> $row->per_on_fabric_wgt,
                'gram_per_ltr_liqure'=> $row->gram_per_ltr_liqure,        
                'qty' => $qty,
                'rate' => $row->rate,
                'last_rcv_rate' => $row->last_rcv_rate,
                'last_receive_no' => $row->last_receive_no,
                'amount' =>$qty*$row->rate,
                'remarks'=> $row->remarks
            ]);
        }
        return response()->json(array('success' =>true ,'id'=>$sodyeingmktcostfabitem->id, 'so_dyeing_mkt_cost_fab_id'=>$so_dyeing_mkt_cost_fab_id,'message'=>'Saved Successfully'),200);
    }
}

