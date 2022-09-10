<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricIsuRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopFabricIsuRequest;

class SoAopFabricIsuController extends Controller {

    private $soaop;
    private $soaopfabricisu;
    private $company;
    private $buyer;
    private $uom;
    private $currency;

    public function __construct(
        SoAopRepository $soaop,
        SoAopFabricIsuRepository $soaopfabricisu, 
        CompanyRepository $company, 
        BuyerRepository $buyer,
        UomRepository $uom, 
        CurrencyRepository $currency
        ) {
        $this->soaop = $soaop;
        $this->soaopfabricisu = $soaopfabricisu;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->currency = $currency;
         
        $this->middleware('auth');
        $this->middleware('permission:view.soaopfabricisus',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soaopfabricisus', ['only' => ['store']]);
        $this->middleware('permission:edit.soaopfabricisus',   ['only' => ['update']]);
        $this->middleware('permission:delete.soaopfabricisus', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       return response()->json(
          $this->soaopfabricisu
          ->leftJoin('so_aops', function($join)  {
            $join->on('so_aops.id', '=', 'so_aop_fabric_isus.so_aop_id');
          })
          ->leftJoin('buyers', function($join)  {
            $join->on('so_aops.buyer_id', '=', 'buyers.id');
          })
          ->leftJoin('companies', function($join)  {
            $join->on('so_aops.company_id', '=', 'companies.id');
          })
          ->orderBy('so_aop_fabric_isus.id','desc')
          ->get([
            'so_aop_fabric_isus.*',
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
        return Template::LoadView('Subcontract.AOP.SoAopFabricIsu',['company'=>$company,'buyer'=>$buyer,'uom'=>$uom,'currency'=>$currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopFabricIsuRequest $request) {
        $year=date('Y');
        $max=$this->soaopfabricisu
        ->where([['year','=',$year]])
        ->max('issue_no');
        $issue_no=$max+1;
        $request->request->add(['year' => $year]);
        $request->request->add(['issue_no' => $issue_no]);
        $soaopfabricisu=$this->soaopfabricisu->create($request->except(['id','sales_order_no']));
        
        if($soaopfabricisu){
          return response()->json(array('success' => true,'id' =>  $soaopfabricisu->id,'message' => 'Save Successfully'),200);
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
        $soaopfabricisu = $this->soaopfabricisu
        ->leftJoin('so_aops', function($join)  {
            $join->on('so_aops.id', '=', 'so_aop_fabric_isus.so_aop_id');
          })
        ->where([['so_aop_fabric_isus.id','=',$id]])
        ->get([
          'so_aop_fabric_isus.*',
          'so_aops.company_id',
          'so_aops.buyer_id',
          'so_aops.sales_order_no'
        ])
        ->first();
        $soaopfabricisu->issue_date=date('Y-m-d',strtotime($soaopfabricisu->issue_date));
        $row ['fromData'] = $soaopfabricisu;
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
    public function update(SoAopFabricIsuRequest $request, $id) {
        $soaopfabricisu=$this->soaopfabricisu->update($id,$request->except(['id','sales_order_no','so_aop_id','issue_no','year']));
        if($soaopfabricisu){
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
        if($this->soaopfabricisu->delete($id)){
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