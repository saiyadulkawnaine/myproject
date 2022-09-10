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
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopFabricRcvRequest;

class SoAopFabricRcvController extends Controller {

    private $soaop;
    private $soaopfabricrcv;
    private $company;
    private $buyer;
    private $uom;
    private $currency;

    public function __construct(
        SoAopRepository $soaop,
        SoAopFabricRcvRepository $soaopfabricrcv, 
        CompanyRepository $company, 
        BuyerRepository $buyer,
        UomRepository $uom, 
        CurrencyRepository $currency
        ) {
        $this->soaop = $soaop;
        $this->soaopfabricrcv = $soaopfabricrcv;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->currency = $currency;
         
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
          ->where([['so_aop_fabric_rcvs.is_self','=',0]])
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
        return Template::LoadView('Subcontract.AOP.SoAopFabricRcv',['company'=>$company,'buyer'=>$buyer,'uom'=>$uom,'currency'=>$currency]);
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
        $request->request->add(['is_self' => 0]);
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
        return response()->json(
          $soaop=$this->soaop
          ->join('companies', function($join)  {
          $join->on('companies.id', '=', 'so_aops.company_id');
          })
          ->leftJoin('buyers', function($join)  {
          $join->on('so_aops.buyer_id', '=', 'buyers.id');
          })
          ->when(request('so_no'), function ($q) {
            return $q->where('sales_order_no', 'LIKE', "%".request('so_no', 0)."%");
          })
          //->where([['sales_order_no','=',request('so_no',0)]])
          ->get([
          'so_aops.*',
          'buyers.name as buyer_name',
          'companies.name as company_name'
          ])
        );

    }
}