<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\KnitChargeRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ConstructionRepository;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Library\Template;
use App\Http\Requests\KnitChargeRequest;

class KnitChargeController extends Controller {

    private $knitcharge;
    private $company;
    private $gmtspart;
    private $construction;
    private $composition;
    private $yarncount;
    private $uom;
    private $buyer;
    private $supplier;
	private $autoyarn;

    public function __construct(KnitChargeRepository $knitcharge,CompanyRepository $company,GmtspartRepository $gmtspart, ConstructionRepository $construction,CompositionRepository $composition,YarncountRepository $yarncount,UomRepository $uom,BuyerRepository $buyer,SupplierRepository $supplier,AutoyarnRepository $autoyarn) {
        $this->knitcharge = $knitcharge;
        $this->company = $company;
        $this->gmtspart = $gmtspart;
        $this->construction = $construction;
        $this->composition = $composition;
        $this->yarncount = $yarncount;
        $this->uom = $uom;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
		$this->autoyarn = $autoyarn;
        $this->middleware('auth');
        $this->middleware('permission:view.knitcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.knitcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.knitcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.knitcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
      $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
      $construction=array_prepend(array_pluck($this->construction->get(),'name','id'),'-Select-','');
      $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
      $yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
	  $autoyarn=array_prepend($this->autoyarn->getConstruction(),'-Select-','');
	  $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      $knitcharges=array();
      $rows=$this->knitcharge->orderBy('id','desc')->get();
      foreach ($rows as $row) {
        $knitcharge['id']=$row->id;
       // $knitcharge['fromgsm']=$row->from_gsm;
        //$knitcharge['togsm']=$row->to_gsm;
        $knitcharge['company']=$company[$row->company_id];
        //$knitcharge['gmtspart']=$gmtspart[$row->gmtspart_id];
        //$knitcharge['construction']=$construction[$row->construction_id];
        //$knitcharge['composition']=$composition[$row->composition_id];
		$knitcharge['autoyarn_id']=$row->autoyarn_id;
		$knitcharge['fabrication']=$construction[$row->construction_id];
		//$knitcharge['fabric_shape_id']=$row->fabric_shape_id;
		$knitcharge['fabriclooks']=$fabriclooks[$row->fabric_look_id];
        //$knitcharge['yarncount']=$yarncount[$row->yarncount_id];
        $knitcharge['inhouserate']=$row->in_house_rate;
        $knitcharge['uom']=$uom[$row->uom_id];
        array_push($knitcharges,$knitcharge);
      }
        echo json_encode($knitcharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $construction=array_prepend(array_pluck($this->construction->get(),'name','id'),'-Select-','');
        $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
        //$fabricshape=array_prepend(config('bprs.fabricshape'),'-Select-','');
		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
		
		$autoyarn=array_prepend($this->autoyarn->getConstruction(),'-Select-','');
        return Template::loadView("Util.KnitCharge",['company'=>$company,'gmtspart'=>$gmtspart,'construction'=>$construction,'composition'=>$composition,'fabriclooks'=>$fabriclooks,'yarncount'=>$yarncount,'uom'=>$uom,'buyer'=>$buyer,'supplier'=>$supplier,'autoyarn'=>$autoyarn]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KnitChargeRequest $request) {
        $knitcharge = $this->knitcharge->create($request->except(['id']));
        if ($knitcharge) {
            return response()->json(array('success' => true, 'id' => $knitcharge->id, 'message' => 'Save Successfully'), 200);
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
        $knitcharge = $this->knitcharge->find($id);
        $row ['fromData'] = $knitcharge;
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
    public function update(KnitChargeRequest $request, $id) {
        $knitcharge = $this->knitcharge->update($id, $request->except(['id']));
        if ($knitcharge) {
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
        if ($this->knitcharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
