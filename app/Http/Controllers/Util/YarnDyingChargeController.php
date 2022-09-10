<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\YarnDyingChargeRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Repositories\Contracts\Util\YarntypeRepository;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;

use App\Library\Template;
use App\Http\Requests\YarnDyingChargeRequest;

class YarnDyingChargeController extends Controller {

    private $yarndyingcharge;
    private $company;
    private $yarncount;
	private $yarntype;
    private $composition;
    private $colorrange;
    private $productionprocess;
    private $uom;
    private $buyer;
    private $supplier;
	private $autoyarn;

    public function __construct(YarnDyingChargeRepository $yarndyingcharge,CompanyRepository $company,YarncountRepository $yarncount,YarntypeRepository $yarntype, CompositionRepository $composition,ColorrangeRepository $colorrange,ProductionProcessRepository $productionprocess,UomRepository $uom,BuyerRepository $buyer,SupplierRepository $supplier,AutoyarnRepository $autoyarn) {
        $this->yarndyingcharge = $yarndyingcharge;
        $this->company = $company;
        $this->yarncount = $yarncount;
		$this->yarntype = $yarntype;
        $this->composition = $composition;
        $this->colorrange = $colorrange;
        $this->productionprocess = $productionprocess;
        $this->uom = $uom;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
		$this->autoyarn = $autoyarn;
        $this->middleware('auth');
        $this->middleware('permission:view.yarndyingcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.yarndyingcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.yarndyingcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.yarndyingcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
     $yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
	 $yarntype=array_prepend(array_pluck($this->yarntype->get(),'name','id'),'-Select-','');
      $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
	  $autoyarn=array_prepend($this->autoyarn->getConstructinComposition(),'-Select-','');
      $yarndyingcharges=array();
      $rows=$this->yarndyingcharge->get();
      foreach ($rows as $row) {
        $yarndyingcharge['id']=$row->id;
        $yarndyingcharge['company']=$company[$row->company_id];
        $yarndyingcharge['yarncount']=$yarncount[$row->yarncount_id];
        $yarndyingcharge['composition']=$composition[$row->composition_id];
		$yarndyingcharge['autoyarn_id']=$row->autoyarn_id;
		$yarndyingcharge['fabrication']=$autoyarn[$row->autoyarn_id];
		$yarndyingcharge['yarntype_id']=$row->yarntype_id;
		$yarndyingcharge['yarntype']=$yarntype[$row->yarntype_id];
        $yarndyingcharge['colorrange']=$colorrange[$row->colorrange_id];
        $yarndyingcharge['productionprocess']=$productionprocess[$row->production_process_id];
        $yarndyingcharge['rate']=$row->rate;
        $yarndyingcharge['uom']=$uom[$row->uom_id];
        array_push($yarndyingcharges,$yarndyingcharge);
      }
        echo json_encode($yarndyingcharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
		$yarntype=array_prepend(array_pluck($this->yarntype->get(),'name','id'),'-Select-','');
        $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
		$autoyarn=array_prepend($this->autoyarn->getConstructinComposition(),'-Select-','');
        return Template::loadView("Util.YarnDyingCharge",['company'=>$company,'yarncount'=>$yarncount,'yarntype'=>$yarntype,'composition'=>$composition,'colorrange'=>$colorrange,'productionprocess'=>$productionprocess,'uom'=>$uom,'buyer'=>$buyer,'supplier'=>$supplier,'autoyarn'=>$autoyarn]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(YarnDyingChargeRequest $request) {
        $yarndyingcharge = $this->yarndyingcharge->create($request->except(['id']));
        if ($yarndyingcharge) {
            return response()->json(array('success' => true, 'id' => $yarndyingcharge->id, 'message' => 'Save Successfully'), 200);
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
        $yarndyingcharge = $this->yarndyingcharge->find($id);
        $row ['fromData'] = $yarndyingcharge;
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
    public function update(YarnDyingChargeRequest $request, $id) {
        $yarndyingcharge = $this->yarndyingcharge->update($id, $request->except(['id']));
        if ($yarndyingcharge) {
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
        if ($this->yarndyingcharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
