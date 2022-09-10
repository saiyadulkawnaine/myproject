<?php

namespace App\Http\Controllers\Subcontract\Kniting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderProductRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemStripeRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemQtyRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\PlKnitItemRequest;

class PlKnitItemQtyController extends Controller {

    private $plknititem;
    private $subinbmarketing;
    private $company;
    private $buyer;
    private $uom;
    private $itemaccount;
    private $autoyarn;
    private $soknit;
    private $gmtspart;
    private $assetquantitycost;
    private $plknititemstripe;
    private $plknititemqty;

    public function __construct(
    	PlKnitItemRepository $plknititem,
    	BuyerRepository $buyer,
    	CompanyRepository $company, 
    	UomRepository $uom, 
    	SubInbMarketingRepository $subinbmarketing, 
    	ItemAccountRepository $itemaccount, 
    	ItemclassRepository $itemclass, 
    	ItemcategoryRepository $itemcategory, 
    	SubInbOrderProductRepository $subinborderproduct,
    	AutoyarnRepository $autoyarn,
    	SoKnitRepository $soknit, 
    	GmtspartRepository $gmtspart,
    	AssetQuantityCostRepository $assetquantitycost,
        PlKnitItemStripeRepository $plknititemstripe,
        PlKnitItemQtyRepository $plknititemqty
    ) {
        $this->plknititem = $plknititem;
        $this->subinbmarketing = $subinbmarketing;
        $this->subinborderproduct = $subinborderproduct;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->itemaccount = $itemaccount;
        $this->autoyarn = $autoyarn;
        $this->soknit = $soknit;
        $this->gmtspart = $gmtspart;
        $this->assetquantitycost = $assetquantitycost;
        $this->plknititemstripe = $plknititemstripe;
        $this->plknititemqty = $plknititemqty;
/*  
        $this->middleware('auth');
        $this->middleware('permission:view.plknititemqties',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.plknititemqties', ['only' => ['store']]);
        $this->middleware('permission:edit.plknititemqties',   ['only' => ['update']]);
        $this->middleware('permission:delete.plknititemqties', ['only' => ['destroy']]);

        */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $rows=$this->plknititem
        ->join('pl_knits', function($join)  {
            $join->on('pl_knits.id', '=', 'pl_knit_items.pl_knit_id');
        })
        ->join('pl_knit_item_qties', function($join)  {
            $join->on('pl_knit_item_qties.pl_knit_item_id', '=', 'pl_knit_items.id');
        })
        ->leftJoin(\DB::raw('(
            select
            prod_knits.prod_date,
            prod_knit_items.pl_knit_item_id,
            sum(prod_knit_item_rolls.roll_weight) as qty
            from
            prod_knits
            join prod_knit_items on prod_knit_items.prod_knit_id=prod_knits.id
            join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id=prod_knit_items.id
            group by 
            prod_knits.prod_date,
            prod_knit_items.pl_knit_item_id ) prod'), [
            ['prod.pl_knit_item_id', '=', 'pl_knit_items.id'],
            ['prod.prod_date','=','pl_knit_item_qties.pl_date']
        ])
        ->orderBy('pl_knit_item_qties.pl_date','asc')
        ->where([['pl_knit_items.id','=',request('pl_knit_item_id',0)]])
        ->get([
            'pl_knit_item_qties.*',
            'prod.qty as prod_qty',
		])
        ->map(function($rows){
            $rows->qty=number_format($rows->qty,2);
            $rows->prod_qty=number_format($rows->prod_qty,2);
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
    public function store(PlKnitItemRequest $request) {


       $plknititemqty= $this->plknititemqty->create([
            'pl_knit_item_id'=>$request->pl_knit_item_id,
            'pl_date'=>$request->pl_date,
            'qty'=>$request->qty,
            'adjusted_minute'=>$request->adjusted_minute,
            'remarks'=>$request->remarks,
            'filled'=>0,
            'free'=>0
        ]);
        $tot=$this->plknititemqty->where([['pl_knit_item_id','=',$request->pl_knit_item_id]])->sum('qty');

       $plknititem= $this->plknititem->update($request->pl_knit_item_id,[
            'qty'=>$tot,
        ]);


        if($plknititemqty){
            return response()->json(array('success' => true,'id' =>  $plknititemqty->id,'message' => 'Save Successfully'),200);
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
        

        $plknititemqty=$this->plknititemqty 
        ->where([['pl_knit_item_qties.id','=',$id]])
        ->get([
            'pl_knit_item_qties.*',
        ])
        ->map(function($plknititemqty){
            $plknititemqty->pl_date=date('Y-m-d',strtotime($plknititemqty->pl_date));
            return $plknititemqty;
        })->first();
        $row ['fromData'] = $plknititemqty;
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
    public function update(PlKnitItemRequest $request, $id) {
        $plknititemqty= $this->plknititemqty->update($id,[
            //'pl_knit_item_id'=>$plknititem->id,
            'pl_date'=>$request->pl_date,
            'qty'=>$request->qty,
            'adjusted_minute'=>$request->adjusted_minute,
            'remarks'=>$request->remarks,
            'filled'=>0,
            'free'=>0
        ]);
        $tot=$this->plknititemqty->where([['pl_knit_item_id','=',$request->pl_knit_item_id]])->sum('qty');

        $plknititem= $this->plknititem->update($request->pl_knit_item_id,[
            'qty'=>$tot,
        ]);
        if($plknititemqty){
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

        /*if($this->plknititemqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }*/
    }
}