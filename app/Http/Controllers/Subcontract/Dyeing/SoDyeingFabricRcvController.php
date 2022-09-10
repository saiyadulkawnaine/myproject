<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingFabricRcvRequest;

class SoDyeingFabricRcvController extends Controller {

    private $sodyeing;
    private $sodyeingfabricrcv;
    private $company;
    private $buyer;
    private $uom;
    private $currency;

    public function __construct(
        SoDyeingRepository $sodyeing,
        SoDyeingFabricRcvRepository $sodyeingfabricrcv, 
        CompanyRepository $company, 
        BuyerRepository $buyer,
        UomRepository $uom, 
        CurrencyRepository $currency
        ) {
        $this->sodyeing = $sodyeing;
        $this->sodyeingfabricrcv = $sodyeingfabricrcv;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->currency = $currency;
         
        $this->middleware('auth');
        $this->middleware('permission:view.sodyeingfabricrcvs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.sodyeingfabricrcvs', ['only' => ['store']]);
        $this->middleware('permission:edit.sodyeingfabricrcvs',   ['only' => ['update']]);
        $this->middleware('permission:delete.sodyeingfabricrcvs', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       return response()->json(
          $this->sodyeingfabricrcv
          ->join('so_dyeings', function($join)  {
            $join->on('so_dyeings.id', '=', 'so_dyeing_fabric_rcvs.so_dyeing_id');
          })
          ->join('buyers', function($join)  {
            $join->on('so_dyeings.buyer_id', '=', 'buyers.id');
          })
          ->join('companies', function($join)  {
            $join->on('so_dyeings.company_id', '=', 'companies.id');
          })
          ->where([['so_dyeing_fabric_rcvs.is_self','=',0]])
          ->orderBy('so_dyeing_fabric_rcvs.id','desc')
          ->get([
            'so_dyeing_fabric_rcvs.*',
            'so_dyeings.sales_order_no',
            'buyers.name as buyer_id',
            'companies.name as company_id'
          ])
          ->take(500)
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
        return Template::LoadView('Subcontract.Dyeing.SoDyeingFabricRcv',['company'=>$company,'buyer'=>$buyer,'uom'=>$uom,'currency'=>$currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoDyeingFabricRcvRequest $request) {
        $year=date('Y');
        $max=$this->sodyeingfabricrcv
        ->where([['year','=',$year]])
        ->max('receive_no');
        $receive_no=$max+1;
        $request->request->add(['year' => $year]);
        $request->request->add(['receive_no' => $receive_no]);
        $request->request->add(['is_self' => 0]);
        $sodyeingfabricrcv=$this->sodyeingfabricrcv->create($request->except(['id','sales_order_no']));
        
        if($sodyeingfabricrcv){
          return response()->json(array('success' => true,'id' =>  $sodyeingfabricrcv->id,'message' => 'Save Successfully'),200);
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
        $sodyeingfabricrcv = $this->sodyeingfabricrcv
        ->leftJoin('so_dyeings', function($join)  {
            $join->on('so_dyeings.id', '=', 'so_dyeing_fabric_rcvs.so_dyeing_id');
          })
        ->where([['so_dyeing_fabric_rcvs.id','=',$id]])
        ->get([
          'so_dyeing_fabric_rcvs.*',
          'so_dyeings.company_id',
          'so_dyeings.buyer_id',
          'so_dyeings.sales_order_no'
        ])
        ->first();
        $sodyeingfabricrcv->receive_date=date('Y-m-d',strtotime($sodyeingfabricrcv->receive_date));
        $row ['fromData'] = $sodyeingfabricrcv;
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
    public function update(SoDyeingFabricRcvRequest $request, $id) {
        $sodyeingfabricrcv=$this->sodyeingfabricrcv->update($id,$request->except(['id','sales_order_no','so_dyeing_id','receive_no','year']));
        if($sodyeingfabricrcv){
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
        if($this->sodyeingfabricrcv->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    

    public function getSo()
    {
        return response()->json(
          $sodyeing=$this->sodyeing
          ->join('companies', function($join)  {
          $join->on('companies.id', '=', 'so_dyeings.company_id');
          })
          ->join('buyers', function($join)  {
          $join->on('so_dyeings.buyer_id', '=', 'buyers.id');
          })
          ->when(request('so_no'), function ($q) {
            return $q->where('sales_order_no', 'LIKE', "%".request('so_no', 0)."%");
          })
          ->whereNull('buyers.company_id')
          ->get([
          'so_dyeings.*',
          'buyers.name as buyer_name',
          'companies.name as company_name'
          ])
        );

    }

    public function getDyeingFabricReceive(){
      return response()->json(
        $this->sodyeingfabricrcv
        ->join('so_dyeings', function($join)  {
          $join->on('so_dyeings.id', '=', 'so_dyeing_fabric_rcvs.so_dyeing_id');
        })
        ->join('buyers', function($join)  {
          $join->on('so_dyeings.buyer_id', '=', 'buyers.id');
        })
        ->join('companies', function($join)  {
          $join->on('so_dyeings.company_id', '=', 'companies.id');
        })
        
        ->when(request('date_from'), function ($q) {
          return $q->where('so_dyeing_fabric_rcvs.receive_date', '>=', request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('so_dyeing_fabric_rcvs.receive_date', '<=', request('date_to', 0));
        })
        ->where([['so_dyeing_fabric_rcvs.is_self','=',0]])
        ->orderBy('so_dyeing_fabric_rcvs.id','desc')
        ->get([
          'so_dyeing_fabric_rcvs.*',
          'so_dyeings.sales_order_no',
          'buyers.name as buyer_id',
          'companies.name as company_id'
        ])
        ->map(function($rows){
          $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
          return $rows;
        })
      );
    }
}