<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\WashChargeRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;

use App\Library\Template;
use App\Http\Requests\WashChargeRequest;

class WashChargeController extends Controller {

    private $washcharge;
    private $company;
    private $embelishmenttype;
	private $embelishment;
    private $composition;
    private $colorrange;
    private $uom;
    private $supplier;
	private $productionprocess;

    public function __construct(WashChargeRepository $washcharge,CompanyRepository $company,EmbelishmentRepository $embelishment,EmbelishmentTypeRepository $embelishmenttype,CompositionRepository $composition,ColorrangeRepository $colorrange,UomRepository $uom,SupplierRepository $supplier,ProductionProcessRepository $productionprocess) {
        $this->washcharge = $washcharge;
        $this->company = $company;
        $this->embelishmenttype = $embelishmenttype;
        $this->composition = $composition;
        $this->colorrange = $colorrange;
        $this->uom = $uom;
        $this->supplier = $supplier;
		$this->embelishment = $embelishment;
		$this->productionprocess = $productionprocess;
        $this->middleware('auth');
        $this->middleware('permission:view.washcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.washcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.washcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.washcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
	   $embelishment=array_prepend(array_pluck($this->embelishment->getEmbelishments(),'name','id'),'-Select-','');
      $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->get(),'name','id'),'-Select-','');
      $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
	   $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
      $washcharges=array();
      $rows=$this->washcharge->orderBy('id','desc')->get();
      foreach ($rows as $row) {
        $washcharge['id']=$row->id;
        $washcharge['company']=$company[$row->company_id];
		$washcharge['embelishmenttype']=$embelishmenttype[$row->embelishment_type_id];
		$washcharge['embelishment_type_id']=$row->embelishment_type_id;
        $washcharge['embelishment_id']=$row->embelishment_id;
		$washcharge['embelishment_name']=$embelishment[$row->embelishment_id];
        $washcharge['composition']=$composition[$row->composition_id];
        $washcharge['colorrange']=$colorrange[$row->color_range_id];
		$washcharge['embelishmentsize']=$embelishmentsize[$row->embelishment_size_id];
		$washcharge['embelishment_size_id']=$row->embelishment_size_id;
        $washcharge['rate']=$row->rate;
        $washcharge['uom']=$uom[$row->uom_id];
        array_push($washcharges,$washcharge);
      }
        echo json_encode($washcharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'-Select-','');
	  $embelishment=array_prepend(array_pluck($this->embelishment->getEmbelishments(),'name','id'),'-Select-','');
      $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $fabricshape=array_prepend(config('bprs.fabricshape'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
	  $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
      return Template::loadView("Util.WashCharge",['company'=>$company,'embelishment'=>$embelishment,'embelishmenttype'=>$embelishmenttype,'composition'=>$composition,'colorrange'=>$colorrange,'fabricshape'=>$fabricshape,'uom'=>$uom,'supplier'=>$supplier,'embelishmentsize'=>$embelishmentsize]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WashChargeRequest $request) {
        $washcharge = $this->washcharge->create($request->except(['id','production_area_id']));
        if ($washcharge) {
            return response()->json(array('success' => true, 'id' => $washcharge->id, 'message' => 'Save Successfully'), 200);
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
        $washcharge = $this->washcharge->find($id);
        $row ['fromData'] = $washcharge;
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
    public function update(WashChargeRequest $request, $id) {
        $washcharge = $this->washcharge->update($id, $request->except(['id','production_area_id']));
        if ($washcharge) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->washcharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	public function getEmbtype(){
		$embelishment=$this->embelishment->find(request('embelishment_id',0));
		$productionprocess=$this->productionprocess->find($embelishment->production_process_id);
        $row['embelishmenttype']=$this->embelishmenttype->where([['embelishment_id','=',request('embelishment_id',0)]])->get();
		$row['embelishment']=['production_area_id'=>$productionprocess->production_area_id];
		//$row=$this->embelishmenttype->where([['embelishment_id','=',request('embelishment_id',0)]])->get();
		 echo json_encode($row);
	}

}
