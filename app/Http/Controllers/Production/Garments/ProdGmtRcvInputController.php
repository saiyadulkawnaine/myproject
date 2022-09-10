<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtRcvInputRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtRcvInputRequest;

class ProdGmtRcvInputController extends Controller {

    private $prodgmtrcvinput;
    private $prodgmtdlvinput;
    private $location;
    private $company;
    private $supplier;

    public function __construct(ProdGmtRcvInputRepository $prodgmtrcvinput, ProdGmtDlvInputRepository $prodgmtdlvinput, LocationRepository $location, CompanyRepository $company, SupplierRepository $supplier) {
        $this->prodgmtrcvinput = $prodgmtrcvinput;
        $this->prodgmtdlvinput = $prodgmtdlvinput;
        $this->location = $location;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtrcvinputs',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtrcvinputs', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtrcvinputs',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtrcvinputs', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $prodgmtrcvinputs=array();
        $rows=$this->prodgmtrcvinput
        ->leftJoin('prod_gmt_dlv_inputs',function($join){
            $join->on('prod_gmt_dlv_inputs.id','=','prod_gmt_rcv_inputs.prod_gmt_dlv_input_id');
        })
        ->leftJoin('suppliers',function($join){
            $join->on('prod_gmt_dlv_inputs.supplier_id','=','suppliers.id');
        })
        ->leftJoin('locations',function($join){
            $join->on('prod_gmt_dlv_inputs.location_id','=','locations.id');
        })
        ->orderBy('prod_gmt_rcv_inputs.id','desc')
        ->get([
            'prod_gmt_rcv_inputs.*',
            'prod_gmt_dlv_inputs.location_id',
            'prod_gmt_dlv_inputs.challan_no',
            'suppliers.name as supplier_id',
            'locations.name as location_id'
        ]);
        foreach($rows as $row){
            $prodgmtrcvinput['id']=$row->id;
            $prodgmtrcvinput['receive_no']=$row->receive_no;
            $prodgmtrcvinput['prod_gmt_dlv_input_id']=$row->prod_gmt_dlv_input_id;
            $prodgmtrcvinput['challan_no']=$row->challan_no;
            $prodgmtrcvinput['location_id']=$row->location_id;
            $prodgmtrcvinput['supplier_id']=$row->supplier_id;
            $prodgmtrcvinput['receive_date']=date('d-m-Y',strtotime($row->receive_date));
            array_push($prodgmtrcvinputs,$prodgmtrcvinput);
        }
        echo json_encode($prodgmtrcvinputs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');

        return Template::loadView('Production.Garments.ProdGmtRcvInput', ['location'=> $location, 'company'=> $company,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtRcvInputRequest $request) {
		$prodgmtrcvinput = $this->prodgmtrcvinput->create([
            'receive_no'=>$request->challan_no,
            'prod_gmt_dlv_input_id'=>$request->prod_gmt_dlv_input_id,
            'receive_date'=>$request->receive_date
        ]);
        if($prodgmtrcvinput){
            return response()->json(array('success' => true,'id' =>  $prodgmtrcvinput->id,'receive_no' => $request->challan_no ,'message' => 'Save Successfully'),200);
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
        $prodgmtrcvinput = $this->prodgmtrcvinput
        
        ->leftJoin('prod_gmt_dlv_inputs',function($join){
            $join->on('prod_gmt_dlv_inputs.id','=','prod_gmt_rcv_inputs.prod_gmt_dlv_input_id');
        })
        ->leftJoin('suppliers',function($join){
            $join->on('prod_gmt_dlv_inputs.supplier_id','=','suppliers.id');
        })
        ->leftJoin('locations',function($join){
            $join->on('prod_gmt_dlv_inputs.location_id','=','locations.id');
        })
        ->where([['prod_gmt_rcv_inputs.id','=',$id]])
        ->get([
            'prod_gmt_rcv_inputs.*',
            'prod_gmt_dlv_inputs.supplier_id',
            'prod_gmt_dlv_inputs.location_id',
            'prod_gmt_dlv_inputs.challan_no',
            'suppliers.name as supplier_name',
            'locations.name as location_id'
        ])
        ->first();
        $prodgmtrcvinput['receive_date']=date('Y-m-d',strtotime($prodgmtrcvinput->receive_date));
        $row ['fromData'] = $prodgmtrcvinput;
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
    public function update(ProdGmtRcvInputRequest $request, $id) {
        $prodgmtrcvinput=$this->prodgmtrcvinput->update($id,$request->except(['id','receive_no','challan_no']));
        if($prodgmtrcvinput){
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
        if($this->prodgmtrcvinput->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
    public function getDeliveryChallan(){

		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');

        $rows=$this->prodgmtdlvinput
        ->leftJoin('suppliers',function($join){
            $join->on('prod_gmt_dlv_inputs.supplier_id','=','suppliers.id');
        })
        ->leftJoin('locations',function($join){
            $join->on('prod_gmt_dlv_inputs.location_id','=','locations.id');
        })
        ->when(request('supplier_id'), function ($q) {
            return $q->where('prod_gmt_dlv_inputs.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('delivery_date'), function ($q) {
            return $q->where('prod_gmt_dlv_inputs.delivery_date', '=',request('delivery_date', 0));
        })
        ->orderBy('prod_gmt_dlv_inputs.id','desc')
        ->get(['prod_gmt_dlv_inputs.*',
                'suppliers.name as supplier_name',
                'suppliers.id as supplier_id',
                'locations.name as location_id'
        ])
        ->map(function($rows) use($shiftname){
            $rows->shiftname_id = $shiftname[$rows->shiftname_id];
            return $rows;
        });
        echo json_encode($rows);
    }

}