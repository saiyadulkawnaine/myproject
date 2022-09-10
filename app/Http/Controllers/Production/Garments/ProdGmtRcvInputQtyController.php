<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtRcvInputRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtRcvInputQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtRcvInputQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class ProdGmtRcvInputQtyController extends Controller {

    private $company;
    private $rcvinputqty;
    private $location;
    private $buyer;

    public function __construct(ProdGmtRcvInputQtyRepository $rcvinputqty, ProdGmtRcvInputRepository $prodgmtrcvinput, CompanyRepository $company, LocationRepository $location, SupplierRepository $supplier,CountryRepository $country, BuyerRepository $buyer, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->rcvinputqty = $rcvinputqty;
        $this->prodgmtrcvinput = $prodgmtrcvinput;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->supplier = $supplier;
        $this->country = $country;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtrcvinputqtys',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtrcvinputqtys', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtrcvinputqtys',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtrcvinputqtys', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $gmtrcvinputqty = $this->prodgmtrcvinput
        ->join('prod_gmt_dlv_inputs',function($join){
            $join->on('prod_gmt_dlv_inputs.id','=','prod_gmt_rcv_inputs.prod_gmt_dlv_input_id');
        })
        ->join('prod_gmt_dlv_input_orders', function($join)  {
            $join->on('prod_gmt_dlv_input_orders.prod_gmt_dlv_input_id', '=', 'prod_gmt_dlv_inputs.id');
        })
        ->join('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_input_orders.sales_order_country_id');
        })
        ->join('countries', function($join) {
            $join->on('countries.id', '=', 'sales_order_countries.country_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
        })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->join('prod_gmt_dlv_input_qties',function($join){
            $join->on('prod_gmt_dlv_input_qties.prod_gmt_dlv_input_order_id','=','prod_gmt_dlv_input_orders.id');
        })
        ->leftJoin('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
            $join->on('prod_gmt_dlv_input_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        ->leftJoin('style_gmt_color_sizes', function($join)  {
            $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->leftJoin('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_gmt_color_sizes.style_gmt_id');
        })
        ->leftJoin('item_accounts',function($join){
          $join->on('item_accounts.id','=','style_gmts.item_account_id');
        })
        ->leftJoin('style_colors',function($join){
            $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','style_colors.color_id');
        })
        ->leftJoin('style_sizes',function($join){
            $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->leftJoin('sizes',function($join){
            $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->leftJoin('prod_gmt_rcv_input_qties', function($join)  {
            $join->on('prod_gmt_rcv_inputs.id', '=', 'prod_gmt_rcv_input_qties.prod_gmt_rcv_input_id');
            $join->on('prod_gmt_rcv_input_qties.prod_gmt_dlv_input_qty_id','=','prod_gmt_dlv_input_qties.id');
                 
        })
        ->leftJoin(\DB::raw("(SELECT prod_gmt_dlv_input_qties.id as prod_gmt_dlv_input_qty_id,
            sum(prod_gmt_rcv_input_qties.qty) as qty 
            FROM prod_gmt_rcv_inputs 
            join prod_gmt_rcv_input_qties 
            on prod_gmt_rcv_inputs.id =prod_gmt_rcv_input_qties.prod_gmt_rcv_input_id 
            join prod_gmt_dlv_input_qties 
            on  prod_gmt_dlv_input_qties.id=prod_gmt_rcv_input_qties.prod_gmt_dlv_input_qty_id 
            where prod_gmt_rcv_input_qties.deleted_at is null  
            group by prod_gmt_dlv_input_qties.id) cumulatives"), "cumulatives.prod_gmt_dlv_input_qty_id", "=", "prod_gmt_dlv_input_qties.id")

  	    ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
        ->where([['prod_gmt_rcv_inputs.id','=',$id]])
        ->get([
            'sales_orders.sale_order_no',
            'sales_order_countries.id as sales_order_country_id',
            'countries.name as country_id',
            'item_accounts.item_description',
            'sizes.name as size_name',
            'colors.name as color_name',
            'style_sizes.sort_id as size_sort_id',
            'style_colors.sort_id as color_sort_id',
            'sales_order_gmt_color_sizes.plan_cut_qty',
            'styles.style_ref',
            'styles.id as style_id',     
            'buyers.code as buyer_name',
            'companies.name as company_id',
            'sales_orders.ship_date',
            'prod_gmt_dlv_input_qties.id as prod_gmt_dlv_input_qty_id',          
            'prod_gmt_dlv_input_qties.sales_order_gmt_color_size_id',
            'prod_gmt_dlv_input_qties.qty as input_qty',
            'prod_gmt_rcv_input_qties.id as prod_gmt_rcv_input_qty_id',
            'prod_gmt_rcv_input_qties.qty as receive_qty',
            'cumulatives.qty as cumulative_qty'
        ])
        ->map(function ($gmtrcvinputqty){
            $gmtrcvinputqty->total_receive_qty=$gmtrcvinputqty->cumulative_qty-$gmtrcvinputqty->receive_qty;

            $gmtrcvinputqty->ship_date=($gmtrcvinputqty->ship_date !== null)?date("d-M-Y",strtotime($gmtrcvinputqty->ship_date)):null;
            return $gmtrcvinputqty;
        });


        $saved = $gmtrcvinputqty->filter(function ($value) {
            if($value->prod_gmt_rcv_input_qty_id){
                return $value;
            }
        });
        $new = $gmtrcvinputqty->filter(function ($value) {
            if(!$value->prod_gmt_rcv_input_qty_id){
                return $value;
            }
        });

        return Template::LoadView('Production.Garments.ProdGmtRcvInputQty',[
            'colorsizes'=>$new,'saved'=>$saved
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtRcvInputQtyRequest $request) {
        foreach($request->prod_gmt_dlv_input_qty_id as $index=>$prod_gmt_dlv_input_qty_id){
            if($prod_gmt_dlv_input_qty_id && $request->qty[$index])
            {
                $rcvinputqty = $this->rcvinputqty->updateOrCreate(
                ['prod_gmt_dlv_input_qty_id' => $prod_gmt_dlv_input_qty_id,
                'prod_gmt_rcv_input_id' => $request->prod_gmt_rcv_input_id],
                ['qty' => $request->qty[$index]]);
            }
        }

        if($rcvinputqty){
            return response()->json(array('success' => true,'id' =>  $rcvinputqty->id,'prod_gmt_rcv_input_id' =>  $request->prod_gmt_rcv_input_id ,'message' => 'Save Successfully'),200);
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
        $rcvinputqty = $this->rcvinputqty->find($id);
        $row ['fromData'] = $rcvinputqty;
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
    public function update(ProdGmtRcvInputQtyRequest $request, $id) {
        $rcvinputqty=$this->rcvinputqty->update($id,$request->except(['id']));
        if($rcvinputqty){
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
        if($this->rcvinputqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}