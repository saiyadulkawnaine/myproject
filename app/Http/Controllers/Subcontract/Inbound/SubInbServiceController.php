<?php

namespace App\Http\Controllers\Subcontract\Inbound;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbServiceRepository;

use App\Repositories\Contracts\Util\AopChargeRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\ConstructionRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Repositories\Contracts\Util\UomRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Inbound\SubInbServiceRequest;

class SubInbServiceController extends Controller {

    private $subinbmarketing;
    private $subinbservice;
    private $colorrange;
    private $embelishmenttype;
    private $gmtspart;
    private $construction;
    private $yarncount;
    private $uom;


    public function __construct(SubInbServiceRepository $subinbservice,SubInbMarketingRepository $subinbmarketing, ColorrangeRepository $colorrange, AopChargeRepository $aopcharge, EmbelishmentTypeRepository $embelishmenttype, GmtspartRepository $gmtspart, ConstructionRepository $construction, YarncountRepository $yarncount, UomRepository $uom) {
        $this->subinbservice = $subinbservice;
        $this->subinbmarketing = $subinbmarketing;
        $this->colorrange = $colorrange;
        $this->aopcharge = $aopcharge;
        $this->embelishmenttype = $embelishmenttype;
        $this->construction = $construction;
        $this->gmtspart = $gmtspart;
        $this->yarncount = $yarncount;
        $this->uom = $uom;

        $this->middleware('auth');
            $this->middleware('permission:view.subinbservices',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.subinbservices', ['only' => ['store']]);
            $this->middleware('permission:edit.subinbservices',   ['only' => ['update']]);
            $this->middleware('permission:delete.subinbservices', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
         
        $subinbservices=array();
        $rows=$this->subinbservice
        ->where([['sub_inb_marketing_id','=',request('sub_inb_marketing_id',0)]])
        ->orderBy('sub_inb_services.id','desc')
        ->get();
        foreach($rows as $row){
            $subinbservice['id']=$row->id;
            $subinbservice['colorrange']=$colorrange[$row->colorrange_id];
            $subinbservice['qty']=$row->qty;
            $subinbservice['rate']=$row->rate;
            $subinbservice['amount']=$row->amount;
            $subinbservice['remarks']=$row->remarks;
            $subinbservice['est_delv_date']=date('Y-m-d',strtotime($row->est_delv_date));
            $subinbservice['uom_id']=$uom[$row->uom_id];
            array_push($subinbservices,$subinbservice);
        }
        echo json_encode($subinbservices);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubInbServiceRequest $request) {
		$subinbservice=$this->subinbservice->create($request->except(['id']));
        if($subinbservice){
            return response()->json(array('success' => true,'id' =>  $subinbservice->id,'message' => 'Save Successfully'),200);
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
        $subinbservice = $this->subinbservice->find($id);
        $row ['fromData'] = $subinbservice;
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
    public function update(SubInbServiceRequest $request, $id) {
        $subinbservice=$this->subinbservice->update($id,$request->except(['id']));
        if($subinbservice){
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
        if($this->subinbservice->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
