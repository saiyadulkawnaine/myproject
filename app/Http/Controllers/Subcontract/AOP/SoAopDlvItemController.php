<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopDlvItemRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopDlvRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvItemRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRefRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopDlvItemRequest;

class SoAopDlvItemController extends Controller {

   
    private $soaopdlvitem;
    private $soaopdlv;
    private $soaopfabricrcv;
    private $soaopfabricrcvitem;
    private $soaop;
    private $poaopref;
    private $soaopitem;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $embelishmenttype;


    public function __construct(
      SoAopDlvItemRepository $soaopdlvitem,
      SoAopDlvRepository $soaopdlv,
      SoAopFabricRcvRepository $soaopfabricrcv,
      SoAopFabricRcvItemRepository $soaopfabricrcvitem,
      SoAopRepository $soaop, 
      SoAopRefRepository $poaopref, 
      SoAopItemRepository $soaopitem, 
      AutoyarnRepository $autoyarn,
      GmtspartRepository $gmtspart,
      UomRepository $uom,
      ColorrangeRepository $colorrange,
      ColorRepository $color,
      EmbelishmentTypeRepository $embelishmenttype
    ) {
        $this->soaopdlvitem = $soaopdlvitem;
        $this->soaopdlv = $soaopdlv;
        $this->soaopfabricrcv = $soaopfabricrcv;
        $this->soaopfabricrcvitem = $soaopfabricrcvitem;
        $this->soaop = $soaop;
        $this->poaopref = $poaopref;
        $this->soaopitem = $soaopitem;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->embelishmenttype = $embelishmenttype;
        $this->middleware('auth');
          
             $this->middleware('permission:view.soaopdlvitems',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.soaopdlvitems', ['only' => ['store']]);
            $this->middleware('permission:edit.soaopdlvitems',   ['only' => ['update']]);
            $this->middleware('permission:delete.soaopdlvitems', ['only' => ['destroy']]);

            
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
        $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

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
        

        $rows=$this->soaopdlv
        ->join('so_aop_dlv_items',function($join){
          $join->on('so_aop_dlv_items.so_aop_dlv_id','=','so_aop_dlvs.id');
        })
        
        ->join('so_aop_refs',function($join){
          $join->on('so_aop_refs.id','=','so_aop_dlv_items.so_aop_ref_id');
        })
        ->leftJoin('so_aop_items',function($join){
            $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','so_aop_items.uom_id');
        })
        ->leftJoin('colors',function($join){
          $join->on('colors.id','=','so_aop_items.fabric_color_id');
        })

        ->where([['so_aop_dlvs.id','=',request('so_aop_dlv_id')]])
        ->selectRaw('
            so_aop_dlv_items.id,
            so_aop_dlv_items.qty,
            so_aop_dlv_items.rate,
            so_aop_dlv_items.amount,
            so_aop_dlv_items.design_no,
            so_aop_dlv_items.design_name,
            so_aop_dlv_items.fin_dia,
            so_aop_dlv_items.fin_gsm,
            so_aop_dlv_items.grey_used,
            so_aop_dlv_items.no_of_roll,
            so_aop_dlv_items.remarks,
            so_aop_refs.id as so_aop_ref_id,
            so_aop_refs.so_aop_id,
            so_aop_items.autoyarn_id,
            so_aop_items.fabric_look_id,
            so_aop_items.fabric_shape_id,
            so_aop_items.gmtspart_id,
            so_aop_items.gsm_weight,
            so_aop_items.fabric_color_id,
            so_aop_items.embelishment_type_id,
            colors.name as aop_color,
            so_aop_items.colorrange_id,
            uoms.code as uom_code
            '
          )
        ->orderBy('so_aop_dlv_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$aoptype,$fabricDescriptionArr){
          $rows->fabrication=$desDropdown[$rows->autoyarn_id];
          $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
          $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
          $rows->aoptype=$aoptype[$rows->embelishment_type_id];
          $rows->gsm_weight=$rows->gsm_weight;
          $rows->colorrange_id=$colorrange[$rows->colorrange_id];
          return $rows;
        });
        
        
        echo json_encode($rows);
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $so_aop_dlv_id=request('so_aop_dlv_id',0);
      $soaopdlv=$this->soaopdlv->find($so_aop_dlv_id);

      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'--','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
      $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
      $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'--','');

      
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



      $rows=$this->soaop
      ->join('so_aop_refs',function($join){
      $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
      })
      ->leftJoin('so_aop_items',function($join){
      $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
      })
      ->leftJoin('uoms',function($join){
      $join->on('uoms.id','=','so_aop_items.uom_id');
      })
      ->leftJoin('colors',function($join){
      $join->on('colors.id','=','so_aop_items.fabric_color_id');
      })
      ->join('so_aop_fabric_rcvs',function($join){
      $join->on('so_aop_fabric_rcvs.so_aop_id','=','so_aops.id');
      })
      ->join('so_aop_fabric_rcv_items',function($join){
      $join->on('so_aop_fabric_rcv_items.so_aop_fabric_rcv_id','=','so_aop_fabric_rcvs.id');
      $join->on('so_aop_fabric_rcv_items.so_aop_ref_id','=','so_aop_refs.id');
      })
      ->where([['so_aops.company_id','=',$soaopdlv->company_id]])
      ->where([['so_aops.buyer_id','=',$soaopdlv->buyer_id]])
      ->where([['so_aops.currency_id','=',$soaopdlv->currency_id]])
      ->where([['so_aops.sales_order_no','=',request('sales_order_no',0)]])
      ->selectRaw('
      so_aop_refs.id as so_aop_ref_id,
      so_aop_refs.so_aop_id,
      so_aop_items.autoyarn_id,
      so_aop_items.fabric_look_id,
      so_aop_items.fabric_shape_id,
      so_aop_items.gmtspart_id,
      so_aop_items.gsm_weight,
      so_aop_items.fabric_color_id,
      colors.name as aop_color,
      so_aop_items.colorrange_id,
      so_aop_items.qty,
      so_aop_items.rate,
      so_aop_items.amount,
      so_aop_items.embelishment_type_id,
      so_aop_items.gmt_style_ref,
      so_aop_items.gmt_sale_order_no,
      so_aop_items.bill_for,
      uoms.code as uom_code
      '
      )
      ->groupBy([
      'so_aop_refs.id',
      'so_aop_refs.so_aop_id',
      'so_aop_items.autoyarn_id',
      'so_aop_items.fabric_look_id',
      'so_aop_items.fabric_shape_id',
      'so_aop_items.gmtspart_id',
      'so_aop_items.gsm_weight',
      'so_aop_items.fabric_color_id',
      'colors.name',
      'so_aop_items.id',
      'so_aop_items.colorrange_id',
      'so_aop_items.qty',
      'so_aop_items.rate',
      'so_aop_items.amount',
      'so_aop_items.embelishment_type_id',
      'so_aop_items.gmt_style_ref',
      'so_aop_items.gmt_sale_order_no',
      'so_aop_items.bill_for',
      'uoms.code'
      ])
      ->orderBy('so_aop_items.id','desc')
      ->get()
      ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$aoptype,$fabricDescriptionArr){
      $rows->fabrication=$desDropdown[$rows->autoyarn_id];
      $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
      $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
      $rows->aoptype=$aoptype[$rows->embelishment_type_id];
      $rows->gsm_weight=$rows->gsm_weight;
      $rows->colorrange_id=$colorrange[$rows->colorrange_id];
      return $rows;
      });
      return Template::LoadView('Subcontract.AOP.SoAopDlvItemMatrix',['items'=>$rows]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopDlvItemRequest $request) {
      $master=$this->soaopdlv->find($request->so_aop_dlv_id);
      if($master->approved_by && $master->approved_at && \Auth::user()->level() < 5){
        return response()->json(array('success' => false,'id' => $id,'message' => 'It is Approved,So New Item Add Not Possible'),200);
      }
      foreach($request->so_aop_ref_id as $index=>$so_aop_ref_id)
      {
        if($request->qty[$index])
        {
          $soaopdlvitem=$this->soaopdlvitem->create([
          'so_aop_dlv_id'=>$request->so_aop_dlv_id,
          'so_aop_ref_id'=>$so_aop_ref_id,
          'design_no'=>$request->design_no[$index],
          'design_name'=>$request->design_name[$index],
          'fin_dia'=>$request->fin_dia[$index],
          'fin_gsm'=>$request->fin_gsm[$index],
          'qty'=>$request->qty[$index],
          'rate'=>$request->rate[$index],
          'amount'=>$request->amount[$index],
          'no_of_roll'=>$request->no_of_roll[$index],
          'grey_used'=>$request->grey_used[$index],
          'remarks'=>$request->remarks[$index],
          ]);
        }
      }
      
      if($soaopdlvitem){
        return response()->json(array('success' => true,'id' =>  $soaopdlvitem->id,'so_aop_dlv_id' =>  $request->so_aop_dlv_id,'message' => 'Save Successfully'),200);
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
        $rows=$this->soaopdlvitem
        ->join('so_aop_dlvs',function($join){
          $join->on('so_aop_dlv_items.so_aop_dlv_id','=','so_aop_dlvs.id');
        })
        ->join('so_aop_refs',function($join){
          $join->on('so_aop_refs.id','=','so_aop_dlv_items.so_aop_ref_id');
        })
        ->join('so_aop_items',function($join){
            $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->where([['so_aop_dlv_items.id','=',$id]])
        ->get([
          'so_aop_dlv_items.*',
          'so_aop_items.bill_for'
        ])
        ->first();
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
    public function update(SoAopDlvItemRequest $request, $id) {
      $master=$this->soaopdlv->find($request->so_aop_dlv_id);
      if($master->approved_by && $master->approved_at && \Auth::user()->level() < 5){
        return response()->json(array('success' => false,'id' => $id,'message' => 'It is Approved,So Update Not Possible'),200);
      }
      $soaopfabricrcvitem=$this->soaopdlvitem->update($id,$request->except(['id','so_aop_dlv_id','so_aop_ref_id','bill_for']));
        
        if($soaopfabricrcvitem){
          return response()->json(array('success' => true,'id' => $id,'so_aop_dlv_id' => $request->so_aop_dlv_id,'message' => 'Update Successfully'),200);
        }
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
      if($this->soaopfabricrcvitem->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }
}