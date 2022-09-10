<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemStripeRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderProductRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ColorRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\PlKnitItemStripeRequest;

class PlKnitItemStripeController extends Controller {

    private $plknititemstripe;
    private $subinbmarketing;
    private $company;
    private $buyer;
    private $uom;
    private $color;

    public function __construct(
        PlKnitItemStripeRepository $plknititemstripe,
        BuyerRepository $buyer,
        CompanyRepository $company,
        UomRepository $uom, 
        SubInbMarketingRepository $subinbmarketing,
        ItemAccountRepository $itemaccount, 
        ItemclassRepository $itemclass, 
        ItemcategoryRepository $itemcategory, 
        SubInbOrderProductRepository $subinborderproduct, 
        ColorRepository $color
    ) {
        $this->plknititemstripe = $plknititemstripe;
        $this->subinbmarketing = $subinbmarketing;
        $this->subinborderproduct = $subinborderproduct;
        $this->itemaccount = $itemaccount;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;
        $this->color = $color;
 
        $this->middleware('auth');
       /*   
        $this->middleware('permission:view.plknititemstripes',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.plknititemstripes', ['only' => ['store']]);
        $this->middleware('permission:edit.plknititemstripes',   ['only' => ['update']]);
        $this->middleware('permission:delete.plknititemstripes', ['only' => ['destroy']]); 
        
        */

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $plknititemstripe=$this->plknititemstripe
        ->leftJoin('colors', function($join)  {
            $join->on('colors.id', '=', 'pl_knit_item_stripes.gmt_color_id');
        })
        ->leftJoin('colors as stripe_color', function($join)  {
            $join->on('stripe_color.id', '=', 'pl_knit_item_stripes.stripe_color_id');
        })
        ->get([
            'pl_knit_item_stripes.id',
            'pl_knit_item_stripes.pl_knit_item_id',
            'pl_knit_item_stripes.style_fabrication_stripe_id',
            'pl_knit_item_stripes.measurment',
            'pl_knit_item_stripes.no_of_feeder',
            'colors.name as gmt_color_id',
            'stripe_color.name as stripe_color_id'
        ]);

       echo json_encode($plknititemstripe);
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
    public function store(PlKnitItemStripeRequest $request) {
        $gmt_color = $this->color->firstOrCreate(['name' => $request->gmt_color_id],['code' => $request->color_code]);
        $color = $this->color->firstOrCreate(['name' => $request->stripe_color_id],['code' => $request->color_code]);
		$plknititemstripe=$this->plknititemstripe->create(['pl_knit_item_id'=>$request->pl_knit_item_id,'gmt_color_id'=>$gmt_color->id,'stripe_color_id'=>$color->id,'no_of_feeder'=>$request->no_of_feeder,'measurment'=>$request->measurment]);
        if($plknititemstripe){
            return response()->json(array('success' => true,'id' =>  $plknititemstripe->id,'message' => 'Save Successfully'),200);
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
        
        $plknititemstripe=$this->plknititemstripe
        ->leftJoin('colors', function($join)  {
            $join->on('colors.id', '=', 'pl_knit_item_stripes.gmt_color_id');
        })
        ->leftJoin('colors as stripe_color', function($join)  {
            $join->on('stripe_color.id', '=', 'pl_knit_item_stripes.stripe_color_id');
        })
        ->where([['pl_knit_item_stripes.id','=',$id]])
        ->get([
            'pl_knit_item_stripes.id',
            'pl_knit_item_stripes.pl_knit_item_id',
            'pl_knit_item_stripes.style_fabrication_stripe_id',
            'pl_knit_item_stripes.measurment',
            'pl_knit_item_stripes.no_of_feeder',
            'colors.name as gmt_color_id',
            'stripe_color.name as stripe_color_id'
        ])->first();
        $row ['fromData'] = $plknititemstripe;
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
    public function update(PlKnitItemStripeRequest $request, $id) {
         $gmt_color = $this->color->firstOrCreate(['name' => $request->gmt_color_id],['code' => $request->color_code]);
        $color = $this->color->firstOrCreate(['name' => $request->stripe_color_id],['code' => $request->color_code]);

        if($request->style_fabrication_stripe_id)
        {
            $plknititemstripe=$this->plknititemstripe->update($id,['no_of_feeder'=>$request->no_of_feeder,'measurment'=>$request->measurment]);
        }
        else{
            $plknititemstripe=$this->plknititemstripe->update($id,['gmt_color_id'=>$gmt_color->id,'stripe_color_id'=>$color->id,'no_of_feeder'=>$request->no_of_feeder,'measurment'=>$request->measurment]);
        }


        if($plknititemstripe){
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
        if($this->plknititemstripe->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }    
}