<?php

namespace App\Http\Controllers\Subcontract\Dyeing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingItemRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderProductRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
//use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingItemStripeRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingItemQtyRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\PlDyeingItemRequest;

class PlDyeingItemQtyController extends Controller {

    private $pldyeingitem;
    private $subinbmarketing;
    private $company;
    private $buyer;
    private $uom;
    private $itemaccount;
    private $autoyarn;
    private $sodyeing;
    private $gmtspart;
    private $assetquantitycost;
    //private $pldyeingitemstripe;
    private $pldyeingitemqty;

    public function __construct(
    	PlDyeingItemRepository $pldyeingitem,
    	BuyerRepository $buyer,
    	CompanyRepository $company, 
    	UomRepository $uom, 
    	SubInbMarketingRepository $subinbmarketing, 
    	ItemAccountRepository $itemaccount, 
    	ItemclassRepository $itemclass, 
    	ItemcategoryRepository $itemcategory, 
    	SubInbOrderProductRepository $subinborderproduct,
    	AutoyarnRepository $autoyarn,
    	SoDyeingRepository $sodyeing, 
    	GmtspartRepository $gmtspart,
    	AssetQuantityCostRepository $assetquantitycost,
        //PlDyeingItemStripeRepository $pldyeingitemstripe,
        PlDyeingItemQtyRepository $pldyeingitemqty
    ) {
        $this->pldyeingitem = $pldyeingitem;
        $this->subinbmarketing = $subinbmarketing;
        $this->subinborderproduct = $subinborderproduct;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->itemaccount = $itemaccount;
        $this->autoyarn = $autoyarn;
        $this->sodyeing = $sodyeing;
        $this->gmtspart = $gmtspart;
        $this->assetquantitycost = $assetquantitycost;
        //$this->pldyeingitemstripe = $pldyeingitemstripe;
        $this->pldyeingitemqty = $pldyeingitemqty;
/*  
        $this->middleware('auth');
        $this->middleware('permission:view.pldyeingitemqties',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.pldyeingitemqties', ['only' => ['store']]);
        $this->middleware('permission:edit.pldyeingitemqties',   ['only' => ['update']]);
        $this->middleware('permission:delete.pldyeingitemqties', ['only' => ['destroy']]);

        */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $rows=$this->pldyeingitem
        ->join('pl_dyeings', function($join)  {
            $join->on('pl_dyeings.id', '=', 'pl_dyeing_items.pl_dyeing_id');
        })
        ->join('pl_dyeing_item_qties', function($join)  {
            $join->on('pl_dyeing_item_qties.pl_dyeing_item_id', '=', 'pl_dyeing_items.id');
        })
        /*->leftJoin(\DB::raw('(
            select
            prod_dyeings.prod_date,
            prod_dyeing_items.pl_dyeing_item_id,
            sum(prod_dyeing_item_rolls.roll_weight) as qty
            from
            prod_dyeings
            join prod_dyeing_items on prod_dyeing_items.prod_dyeing_id=prod_dyeings.id
            join prod_dyeing_item_rolls on prod_dyeing_item_rolls.prod_dyeing_item_id=prod_dyeing_items.id
            group by 
            prod_dyeings.prod_date,
            prod_dyeing_items.pl_dyeing_item_id ) prod'), [
            ['prod.pl_dyeing_item_id', '=', 'pl_dyeing_items.id'],
            ['prod.prod_date','=','pl_dyeing_item_qties.pl_date']
        ])*/
        ->orderBy('pl_dyeing_item_qties.id','desc')
        ->where([['pl_dyeing_items.id','=',request('pl_dyeing_item_id',0)]])
        ->get([
            'pl_dyeing_item_qties.*',
            //'prod.qty as prod_qty',
		])
        ->map(function($rows){
            $rows->qty=number_format($rows->qty,2);
            $rows->prod_qty=number_format(0,2);
            $rows->pl_date=date('d-M-Y',strtotime($rows->pl_date));
            return $rows;
        });
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlDyeingItemRequest $request) {


       $pldyeingitemqty= $this->pldyeingitemqty->create([
            'pl_dyeing_item_id'=>$request->pl_dyeing_item_id,
            'pl_date'=>$request->pl_date,
            'qty'=>$request->qty,
            'filled'=>0,
            'free'=>0
        ]);
        $tot=$this->pldyeingitemqty->where([['pl_dyeing_item_id','=',$request->pl_dyeing_item_id]])->sum('qty');

       $pldyeingitem= $this->pldyeingitem->update($request->pl_dyeing_item_id,[
            'qty'=>$tot,
        ]);


        if($pldyeingitemqty){
            return response()->json(array('success' => true,'id' =>  $pldyeingitemqty->id,'message' => 'Save Successfully'),200);
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
        

        $pldyeingitemqty=$this->pldyeingitemqty 
        ->where([['pl_dyeing_item_qties.id','=',$id]])
        ->get([
            'pl_dyeing_item_qties.*',
        ])
        ->map(function($pldyeingitemqty){
            $pldyeingitemqty->pl_date=date('Y-m-d',strtotime($pldyeingitemqty->pl_date));
            return $pldyeingitemqty;
        })->first();
        $row ['fromData'] = $pldyeingitemqty;
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
    public function update(PlDyeingItemRequest $request, $id) {
        $pldyeingitemqty= $this->pldyeingitemqty->update($id,[
            //'pl_dyeing_item_id'=>$pldyeingitem->id,
            'pl_date'=>$request->pl_date,
            'qty'=>$request->qty,
            'filled'=>0,
            'free'=>0
        ]);
        $tot=$this->pldyeingitemqty->where([['pl_dyeing_item_id','=',$request->pl_dyeing_item_id]])->sum('qty');

        $pldyeingitem= $this->pldyeingitem->update($request->pl_dyeing_item_id,[
            'qty'=>$tot,
        ]);
        if($pldyeingitemqty){
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
        return response()->json(array('success' => false,'message' => 'Delete not possible'),200);

        /*if($this->pldyeingitemqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }*/
    }
}