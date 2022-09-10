<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRefRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\SoKnitYarnRcvRequest;

class SoKnitYarnRcvController extends Controller {

    private $soknit;
    private $soknitpoitem;
    private $soknityarnrcv;
    private $company;
    private $buyer;
    private $uom;
    private $gmtspart;
    private $poknitservice;
    private $poknitpo;
    private $poknitref;
    private $currency;

    public function __construct(
        SoKnitRepository $soknit,
        SoKnitPoItemRepository $soknitpoitem,
        BuyerRepository $buyer,
        CompanyRepository $company, 
        UomRepository $uom, 
        SoKnitYarnRcvRepository $soknityarnrcv, 
        GmtspartRepository $gmtspart,
        PoKnitServiceRepository $poknitservice,
        SoKnitPoRepository $poknitpo,
        SoKnitRefRepository $poknitref,
        CurrencyRepository $currency
        ) {
        $this->soknit = $soknit;
        $this->soknitpoitem = $soknitpoitem;
        $this->soknityarnrcv = $soknityarnrcv;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->gmtspart = $gmtspart;
        $this->poknitservice = $poknitservice;
        $this->poknitpo = $poknitpo;
        $this->poknitref = $poknitref;
        $this->currency = $currency;
         
        $this->middleware('auth');
        $this->middleware('permission:view.soknityarnrcvs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soknityarnrcvs', ['only' => ['store']]);
        $this->middleware('permission:edit.soknityarnrcvs',   ['only' => ['update']]);
        $this->middleware('permission:delete.soknityarnrcvs', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       return response()->json(
          $this->soknityarnrcv
          ->leftJoin('so_knits', function($join)  {
            $join->on('so_knits.id', '=', 'so_knit_yarn_rcvs.so_knit_id');
          })
          ->leftJoin('buyers', function($join)  {
            $join->on('so_knits.buyer_id', '=', 'buyers.id');
          })
          ->leftJoin('companies', function($join)  {
            $join->on('so_knits.company_id', '=', 'companies.id');
          })
          ->orderBy('so_knit_yarn_rcvs.id','desc')
          ->get([
            'so_knit_yarn_rcvs.*',
            'so_knits.sales_order_no',
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
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        return Template::LoadView('Subcontract.Kniting.SoKnitYarnRcv',['company'=>$company,'buyer'=>$buyer,'uom'=>$uom,'fabriclooks'=>$fabriclooks,'fabricshape'=>$fabricshape,'gmtspart'=>$gmtspart,'currency'=>$currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoKnitYarnRcvRequest $request) {
        $soknityarnrcv=$this->soknityarnrcv->create($request->except(['id','sales_order_no']));
        
        if($soknityarnrcv){
          return response()->json(array('success' => true,'id' =>  $soknityarnrcv->id,'message' => 'Save Successfully'),200);
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
        $soknityarnrcv = $this->soknityarnrcv
        ->leftJoin('so_knits', function($join)  {
            $join->on('so_knits.id', '=', 'so_knit_yarn_rcvs.so_knit_id');
          })
        ->where([['so_knit_yarn_rcvs.id','=',$id]])
        ->get([
          'so_knit_yarn_rcvs.*',
          'so_knits.company_id',
          'so_knits.buyer_id',
          'so_knits.sales_order_no'
        ])
        ->first();
        $soknityarnrcv->receive_date=date('Y-m-d',strtotime($soknityarnrcv->receive_date));
        $row ['fromData'] = $soknityarnrcv;
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
    public function update(SoKnitYarnRcvRequest $request, $id) {
        $soknityarnrcv=$this->soknityarnrcv->update($id,$request->except(['id','sales_order_no','so_knit_id']));
        if($soknityarnrcv){
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
        if($this->soknityarnrcv->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    

    public function getSo()
    {
        return response()->json(
          $soknit=$this->soknit
          ->join('companies', function($join)  {
          $join->on('companies.id', '=', 'so_knits.company_id');
          })
          ->leftJoin('buyers', function($join)  {
          $join->on('so_knits.buyer_id', '=', 'buyers.id');
          })
          ->when(request('so_no'), function ($q) {
            return $q->where('sales_order_no', 'LIKE', "%".request('so_no', 0)."%");
          })
          //->where([['sales_order_no','=',request('so_no',0)]])
          ->get([
          'so_knits.*',
          'buyers.name as buyer_name',
          'companies.name as company_name'
          ])
        );

    }
}