<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostParamRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbMktCostParamRequest;

class SoEmbMktCostParamController extends Controller {

   
    private $soaopmktcostparam;
    private $soaopmktcost;
    private $uom;
    private $colorrange;
    private $color;
    private $keycontrol;
    private $embelishmenttype;


    public function __construct(
        SoEmbMktCostParamRepository $soaopmktcostparam,
        SoEmbMktCostRepository $soaopmktcost,
        UomRepository $uom,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        KeycontrolRepository $keycontrol,
        EmbelishmentTypeRepository $embelishmenttype
    ) {
        $this->soaopmktcostparam = $soaopmktcostparam;
        $this->soaopmktcost = $soaopmktcost;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->keycontrol = $keycontrol;
        $this->embelishmenttype = $embelishmenttype;


        $this->middleware('auth');
      
        //$this->middleware('permission:view.soaopmktcostparams',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.soaopmktcostparams', ['only' => ['store']]);
        //$this->middleware('permission:edit.soaopmktcostparams',   ['only' => ['update']]);
        //$this->middleware('permission:delete.soaopmktcostparams', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbTypes(),'name','id'),'-Select-','');
      
        $soaopmktcostparams=array();
        $rows=$this->soaopmktcostparam
        ->where([['so_aop_mkt_cost_id','=',request('so_aop_mkt_cost_id',0)]])
        ->orderBy('so_aop_mkt_cost_params.id','desc')
        ->get();

        foreach($rows as $row){
            $soaopmktcostparam['id']=$row->id;
            $soaopmktcostparam['colorrange']=$colorrange[$row->colorrange_id];
            $soaopmktcostparam['print_type']=$row->print_type_id?$embelishmenttype[$row->print_type_id]:'--';
            $soaopmktcostparam['gsm_weight']=$row->gsm_weight;
            $soaopmktcostparam['dia']=$row->dia;
            $soaopmktcostparam['color_ratio_from']=$row->color_ratio_from;
            $soaopmktcostparam['color_ratio_to']=$row->color_ratio_to;
            $soaopmktcostparam['no_of_color_from']=$row->no_of_color_from;
            $soaopmktcostparam['no_of_color_to']=$row->no_of_color_to;
            $soaopmktcostparam['fabric_wgt']=$row->fabric_wgt;
            $soaopmktcostparam['offer_qty']=$row->offer_qty;
            $soaopmktcostparam['paste_wgt']=$row->paste_wgt;
            $soaopmktcostparam['remarks']=$row->remarks;
            array_push($soaopmktcostparams,$soaopmktcostparam);
        }

        echo json_encode($soaopmktcostparams);
      
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
    public function store(SoEmbMktCostParamRequest $request) {
        $quotationApproved=$this->soaopmktcost
        ->join('so_aop_mkt_cost_qprices', function($join)  {
            $join->on('so_aop_mkt_cost_qprices.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->where([['so_aop_mkt_costs.id','=',$request->so_aop_mkt_cost_id]])
        ->get(['so_aop_mkt_cost_qprices.final_approved_by'])
        ->first();

        if ($quotationApproved) {
            return response()->json(array('success' => false,'message' => 'Save not possible. Quotation Approved'),200);
        }

        $company=$this->soaopmktcost
        ->join('sub_inb_services', function($join)  {
            $join->on('sub_inb_services.id', '=', 'so_aop_mkt_costs.sub_inb_service_id');
        })
        ->join('sub_inb_marketings', function($join)  {
            $join->on('sub_inb_marketings.id', '=', 'sub_inb_services.sub_inb_marketing_id');
        })
        ->where([['so_aop_mkt_costs.id','=',$request->so_aop_mkt_cost_id]])
        ->get(['sub_inb_marketings.company_id'])
        ->first();
        //dd($request->all());die;
        $today=date('Y-m-d');
        $overhead=$this->keycontrol
        ->join('keycontrol_parameters', function($join){
            $join->on('keycontrol_parameters.keycontrol_id','=','keycontrols.id');
        })
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$today])
        ->where([['keycontrols.company_id','=',$company->company_id]])
        ->where([['keycontrol_parameters.parameter_id','=',11]])
        ->get()
        ->first();
        //dd($overhead->value);
        if ($overhead) {
            $request->request->add(['overhead_per_kg'=>$overhead->value]);
            $request->request->add(['overhead_amount'=>$overhead->value*$request->fabric_wgt]);
        }else {
            $request->request->add(['overhead_per_kg'=>'']);
            $request->request->add(['overhead_amount'=>'']);
        }
        
        $soaopmktcostparam=$this->soaopmktcostparam->create($request->except(['id']));

        if($soaopmktcostparam){
        return response()->json(array('success' => true,'id' =>  $soaopmktcostparam->id,'so_aop_mkt_cost_id' =>  $request->so_aop_mkt_cost_id,'message' => 'Save Successfully'),200);
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
        $rows=$this->soaopmktcostparam->find($id);    
        $row ['fromData'] = $rows;
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

    public function update(SoEmbMktCostParamRequest $request, $id) {
        $quotationApproved=$this->soaopmktcost
        ->join('so_aop_mkt_cost_qprices', function($join)  {
            $join->on('so_aop_mkt_cost_qprices.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->where([['so_aop_mkt_costs.id','=',$request->so_aop_mkt_cost_id]])
        ->get(['so_aop_mkt_cost_qprices.final_approved_by'])
        ->first();

        if ($quotationApproved) {
            return response()->json(array('success' => false,'message' => 'Update not possible. Quotation Approved'),200);
        }

        if($request->paste_wgt <= 0){
            return response()->json(array('success' => false,'id' => $id,'so_aop_mkt_cost_id' => $request->so_aop_mkt_cost_id,'message' => '0 Rate Not Allowed'),200);
        }

        $company=$this->soaopmktcost
        ->join('sub_inb_services', function($join)  {
            $join->on('sub_inb_services.id', '=', 'so_aop_mkt_costs.sub_inb_service_id');
        })
        ->join('sub_inb_marketings', function($join)  {
            $join->on('sub_inb_marketings.id', '=', 'sub_inb_services.sub_inb_marketing_id');
        })
        ->where([['so_aop_mkt_costs.id','=',$request->so_aop_mkt_cost_id]])
        ->get(['sub_inb_marketings.company_id'])
        ->first();

        $today=date('Y-m-d');
        $overhead=$this->keycontrol
        ->join('keycontrol_parameters', function($join){
            $join->on('keycontrol_parameters.keycontrol_id','=','keycontrols.id');
        })
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$today])
        ->where([['keycontrol_parameters.parameter_id','=',11]])
        ->where([['keycontrols.company_id','=',$company->company_id]])
        ->get()
        ->first();
        //dd($overhead->value);
        if ($overhead) {
            $request->request->add(['overhead_per_kg'=>$overhead->value]);
            $request->request->add(['overhead_amount'=>$overhead->value*$request->fabric_wgt]);
        }else {
            $request->request->add(['overhead_per_kg'=>'']);
            $request->request->add(['overhead_amount'=>'']);
        }

        $soaopmktcostparam=$this->soaopmktcostparam->update($id,$request->except(['id']));

        if($soaopmktcostparam){
            return response()->json(array('success' => true,'id' => $id,'so_aop_mkt_cost_id' => $request->so_aop_mkt_cost_id,'message' => 'Update Successfully'),200);
        }
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id) {
      if($this->soaopmktcostparam->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }

}