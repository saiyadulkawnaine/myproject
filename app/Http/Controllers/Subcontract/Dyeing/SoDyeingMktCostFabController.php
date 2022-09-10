<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpriceRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingMktCostFabRequest;

class SoDyeingMktCostFabController extends Controller {

   
    private $sodyeingmktcostfab;
    private $sodyeingmktcost;
    private $sodyeingmktcostqprice;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $keycontrol;


    public function __construct(
        SoDyeingMktCostFabRepository $sodyeingmktcostfab,
        SoDyeingMktCostRepository $sodyeingmktcost,
        SoDyeingMktCostQpriceRepository $sodyeingmktcostqprice,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        UomRepository $uom,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        KeycontrolRepository $keycontrol
    ) {
        $this->sodyeingmktcostfab = $sodyeingmktcostfab;
        $this->sodyeingmktcost = $sodyeingmktcost;
        $this->sodyeingmktcostqprice = $sodyeingmktcostqprice;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->keycontrol = $keycontrol;

        $this->middleware('auth');
      
        //$this->middleware('permission:view.sodyeingmktcostfabs',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingmktcostfabs', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingmktcostfabs',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingmktcostfabs', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
            $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
            return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
            return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
            'autoyarns.*',
            'constructions.name',
            'compositions.name as composition_name',
            'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
            $fabricDescriptionArr[$row->id]=$row->name;
            $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }
      
        $sodyeingmktcostfabs=array();
        $rows=$this->sodyeingmktcostfab
        ->where([['so_dyeing_mkt_cost_id','=',request('so_dyeing_mkt_cost_id',0)]])
        ->orderBy('so_dyeing_mkt_cost_fabs.id','desc')
        ->get();

        foreach($rows as $row){
            $sodyeingmktcostfab['id']=$row->id;
            $sodyeingmktcostfab['fabrication']=$desDropdown[$row->autoyarn_id];
            $sodyeingmktcostfab['colorrange']=$colorrange[$row->colorrange_id];
            $sodyeingmktcostfab['dyeingtype']=$row->dyeing_type_id?$dyetype[$row->dyeing_type_id]:'--';
            $sodyeingmktcostfab['gsm_weight']=$row->gsm_weight;
            $sodyeingmktcostfab['dia']=$row->dia;
            $sodyeingmktcostfab['color_ratio_from']=$row->color_ratio_from;
            $sodyeingmktcostfab['color_ratio_to']=$row->color_ratio_to;
            $sodyeingmktcostfab['fabric_wgt']=$row->fabric_wgt;
            $sodyeingmktcostfab['offer_qty']=$row->offer_qty;
            $sodyeingmktcostfab['liqure_ratio']=$row->liqure_ratio;
            $sodyeingmktcostfab['liqure_wgt']=$row->liqure_wgt;
            $sodyeingmktcostfab['remarks']=$row->remarks;
            array_push($sodyeingmktcostfabs,$sodyeingmktcostfab);
        }

        echo json_encode($sodyeingmktcostfabs);
      
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
    public function store(SoDyeingMktCostFabRequest $request) {
        $approved=$this->sodyeingmktcost
        ->join('so_dyeing_mkt_cost_qprices',function($join){
            $join->on('so_dyeing_mkt_cost_qprices.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->where([['so_dyeing_mkt_costs.id','=', $request->so_dyeing_mkt_cost_id]])
        ->get(['so_dyeing_mkt_cost_qprices.final_approved_at'])->first();

        if ($approved->final_approved_at) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }
        $company=$this->sodyeingmktcost
        ->join('sub_inb_services', function($join)  {
            $join->on('sub_inb_services.id', '=', 'so_dyeing_mkt_costs.sub_inb_service_id');
        })
        ->join('sub_inb_marketings', function($join)  {
            $join->on('sub_inb_marketings.id', '=', 'sub_inb_services.sub_inb_marketing_id');
        })
        ->where([['so_dyeing_mkt_costs.id','=',$request->so_dyeing_mkt_cost_id]])
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
        
        $sodyeingmktcostfab=$this->sodyeingmktcostfab->create($request->except(['id','fabrication']));

        if($sodyeingmktcostfab){
        return response()->json(array('success' => true,'id' =>  $sodyeingmktcostfab->id,'so_dyeing_mkt_cost_id' =>  $request->so_dyeing_mkt_cost_id,'message' => 'Save Successfully'),200);
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
        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
            $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
            return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
            return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
            'autoyarns.*',
            'constructions.name',
            'compositions.name as composition_name',
            'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
            $fabricDescriptionArr[$row->id]=$row->name;
            $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $rows=$this->sodyeingmktcostfab->find($id);

        $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:'';
            
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

    public function update(SoDyeingMktCostFabRequest $request, $id) {
        $approved=$this->sodyeingmktcost
        ->join('so_dyeing_mkt_cost_qprices',function($join){
            $join->on('so_dyeing_mkt_cost_qprices.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->where([['so_dyeing_mkt_costs.id','=', $request->so_dyeing_mkt_cost_id]])
        ->get(['so_dyeing_mkt_cost_qprices.final_approved_at'])->first();

        if ($approved->final_approved_at) {
          return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Update not possible '), 200);
        }

        if($request->liqure_ratio <= 0){
            return response()->json(array('success' => false,'id' => $id,'so_dyeing_mkt_cost_id' => $request->so_dyeing_mkt_cost_id,'message' => '0 Qty Not Allowed'),200);
        }
        if($request->liqure_wgt <= 0){
            return response()->json(array('success' => false,'id' => $id,'so_dyeing_mkt_cost_id' => $request->so_dyeing_mkt_cost_id,'message' => '0 Rate Not Allowed'),200);
        }

        $company=$this->sodyeingmktcost
        ->join('sub_inb_services', function($join)  {
            $join->on('sub_inb_services.id', '=', 'so_dyeing_mkt_costs.sub_inb_service_id');
        })
        ->join('sub_inb_marketings', function($join)  {
            $join->on('sub_inb_marketings.id', '=', 'sub_inb_services.sub_inb_marketing_id');
        })
        ->where([['so_dyeing_mkt_costs.id','=',$request->so_dyeing_mkt_cost_id]])
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
        $sodyeingmktcostfab=$this->sodyeingmktcostfab->update($id,$request->except(['id','fabrication']));

      if($sodyeingmktcostfab){
        return response()->json(array('success' => true,'id' => $id,'so_dyeing_mkt_cost_id' => $request->so_dyeing_mkt_cost_id,'message' => 'Update Successfully'),200);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
      if($this->sodyeingmktcostfab->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }

    public function getAutoYarn()
    {
        $autoyarn=$this->autoyarn->join('autoyarnratios', function($join)  {
            $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
            return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
            return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
            'autoyarns.*',
            'constructions.name',
            'compositions.name as composition_name',
            'autoyarnratios.ratio'
        ]);
    
        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
            foreach($autoyarn as $row){
            $fabricDescriptionArr[$row->id]=$row->name;
        $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
            }
            $desDropdown=array();
            foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
        
        $fab=array();
        $fabs=array();

        foreach($autoyarn as $row){
            $fab[$row->id]['id']=$row->id;
            $fab[$row->id]['name']=$row->name;
            $fab[$row->id]['composition_name']=$desDropdown[$row->id];
        }

        foreach($fab as $row){   
            array_push($fabs,$row);
        }
        
        echo json_encode($fabs);
    }
}