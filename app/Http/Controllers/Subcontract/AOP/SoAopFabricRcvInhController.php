<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopFabricRcvRequest;

class SoAopFabricRcvInhController extends Controller {

    private $soaop;
    private $soaopfabricrcv;
    private $company;
    private $buyer;
    private $uom;
    private $currency;
    private $prodfinishdlv;

    public function __construct(
        SoAopRepository $soaop,
        SoAopFabricRcvRepository $soaopfabricrcv, 
        CompanyRepository $company, 
        BuyerRepository $buyer,
        UomRepository $uom, 
        CurrencyRepository $currency,
        ProdFinishDlvRepository $prodfinishdlv
        ) {
        $this->soaop = $soaop;
        $this->soaopfabricrcv = $soaopfabricrcv;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->currency = $currency;
        $this->prodfinishdlv = $prodfinishdlv;
         
        $this->middleware('auth');
        $this->middleware('permission:view.soaopfabricrcvs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soaopfabricrcvs', ['only' => ['store']]);
        $this->middleware('permission:edit.soaopfabricrcvs',   ['only' => ['update']]);
        $this->middleware('permission:delete.soaopfabricrcvs', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       return response()->json(
          $this->soaopfabricrcv
          ->leftJoin('so_aops', function($join)  {
            $join->on('so_aops.id', '=', 'so_aop_fabric_rcvs.so_aop_id');
          })
          ->leftJoin('buyers', function($join)  {
            $join->on('so_aops.buyer_id', '=', 'buyers.id');
          })
          ->leftJoin('companies', function($join)  {
            $join->on('so_aops.company_id', '=', 'companies.id');
          })
          ->where([['so_aop_fabric_rcvs.is_self','=',1]])
          ->orderBy('so_aop_fabric_rcvs.id','desc')
          ->get([
            'so_aop_fabric_rcvs.*',
            'so_aops.sales_order_no',
            'buyers.name as buyer_id',
            'companies.name as company_id'
          ])
          ->map(function($rows){
            $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
            return $rows;
          })
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        return Template::LoadView('Subcontract.AOP.SoAopFabricRcvInh',['company'=>$company,'buyer'=>$buyer,'uom'=>$uom,'currency'=>$currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopFabricRcvRequest $request) {
        $year=date('Y');
        $max=$this->soaopfabricrcv
        ->where([['year','=',$year]])
        ->max('receive_no');
        $receive_no=$max+1;
        $request->request->add(['year' => $year]);
        $request->request->add(['receive_no' => $receive_no]);
        $request->request->add(['is_self' => 1]);
        $soaopfabricrcv=$this->soaopfabricrcv->create($request->except(['id','sales_order_no']));
        
        if($soaopfabricrcv){
          return response()->json(array('success' => true,'id' =>  $soaopfabricrcv->id,'message' => 'Save Successfully'),200);
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
        $soaopfabricrcv = $this->soaopfabricrcv
        ->leftJoin('so_aops', function($join)  {
            $join->on('so_aops.id', '=', 'so_aop_fabric_rcvs.so_aop_id');
          })
        ->where([['so_aop_fabric_rcvs.id','=',$id]])
        ->get([
          'so_aop_fabric_rcvs.*',
          'so_aops.company_id',
          'so_aops.buyer_id',
          'so_aops.sales_order_no'
        ])
        ->first();
        $soaopfabricrcv->receive_date=date('Y-m-d',strtotime($soaopfabricrcv->receive_date));
        $row ['fromData'] = $soaopfabricrcv;
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
    public function update(SoAopFabricRcvRequest $request, $id) {
        $soaopfabricrcv=$this->soaopfabricrcv->update($id,$request->except(['id','sales_order_no','so_aop_id','receive_no','year']));
        if($soaopfabricrcv){
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
        if($this->soaopfabricrcv->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    

    public function getSo()
    {
        $prodfinishdlv=$this->prodfinishdlv->find(request('prod_finish_dlv_id',0));
        return response()->json(
          $soaop=$this->soaop
          ->join('companies', function($join)  {
          $join->on('companies.id', '=', 'so_aops.company_id');
          })
          ->join('so_aop_refs',function($join){
          $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
          })
          ->join('so_aop_pos', function($join)  {
          $join->on('so_aop_pos.so_aop_id', '=', 'so_aops.id');
          })
          ->leftJoin('so_aop_po_items',function($join){
          $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
          })
          ->leftJoin('po_aop_service_item_qties',function($join){
          $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
          })
          // ->leftJoin('budget_fabric_prod_cons',function($join){
          // $join->on('po_aop_service_item_qties.budget_fabric_prod_con_id','=','budget_fabric_prod_cons.id');
          // })
          ->leftJoin('po_aop_service_items',function($join){
          $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
          ->whereNull('po_aop_service_items.deleted_at');
          })
          ->leftJoin('sales_orders',function($join){
          $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
          })
          ->leftJoin('jobs',function($join){
          $join->on('jobs.id','=','sales_orders.job_id');
          })
          ->leftJoin('styles',function($join){
          $join->on('styles.id','=','jobs.style_id');
          })
          ->leftJoin('buyers as customers', function($join)  {
          $join->on('so_aops.buyer_id', '=', 'customers.id');
          })
          ->when(request('so_no'), function ($q) {
            return $q->where('so_aops.sales_order_no', 'LIKE', "%".request('so_no', 0)."%");
          })
          ->where([['customers.company_id','>',0]])
          ->where([['customers.id','=',$prodfinishdlv->buyer_id]])
          ->groupBy([
            'so_aops.id',
            'so_aops.sales_order_no',
            'customers.name',
            'companies.name',
            'styles.style_ref',
            'sales_orders.sale_order_no',
          ])
          ->orderBy('so_aops.id','decs')
          ->get([
          'so_aops.id',
          'so_aops.sales_order_no',
          'customers.name as customer_name',
          'companies.name as company_name',
          'styles.style_ref',
          'sales_orders.sale_order_no',
          ])
        );

    }

    public function getChallan(){
      $rows=$this->prodfinishdlv
        ->leftJoin('companies', function($join)  {
            $join->on('prod_finish_dlvs.company_id', '=', 'companies.id');
        })
         ->leftJoin('locations', function($join)  {
           $join->on('prod_finish_dlvs.location_id', '=', 'locations.id');
        })
        ->leftJoin('buyers', function($join)  {
            $join->on('prod_finish_dlvs.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('stores', function($join)  {
            $join->on('prod_finish_dlvs.store_id', '=', 'stores.id');
        })
        ->leftJoin('so_aop_fabric_rcvs', function($join)  {
            $join->on('so_aop_fabric_rcvs.prod_finish_dlv_id', '=', 'prod_finish_dlvs.id');
        })
        ->where([['prod_finish_dlvs.dlv_to_finish_store','=',0]])
        ->when(request('dlv_no'), function ($q) {
            return $q->where('prod_finish_dlvs.dlv_no', '=', request('dlv_no', 0));
        })
        ->when(request('from_dlv_date'), function ($q) {
            return $q->where('prod_finish_dlvs.dlv_date', '>=', request('from_dlv_date', 0));
        })
        ->when(request('to_dlv_date'), function ($q) {
            return $q->where('prod_finish_dlvs.dlv_date', '<=', request('to_dlv_date', 0));
        })
        ->orderBy('prod_finish_dlvs.id','desc')
        ->get([
            'prod_finish_dlvs.*',
            'companies.name as company_name',
            'locations.name as location_name',
            'buyers.name as buyer_name',
            'stores.name as store_name',
            'so_aop_fabric_rcvs.id as so_aop_fabric_rcv_id'
        ])
        ->map(function($rows){
            $rows->dlv_date=date('d-M-Y',strtotime($rows->dlv_date));
            return $rows;
        })/*
        ->filter(function($rows){
          if( !$rows->so_aop_fabric_rcv_id){
              return $rows;
          }
        })
        ->values()*/;
        return response()->json($rows);

    }
}