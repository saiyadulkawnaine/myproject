<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvItemRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRolRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRefRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopFabricRcvItemRequest;

class SoAopFabricRcvItemController extends Controller {

   
    private $soaopfabricrcv;
    private $soaopfabricrcvitem;
    private $soaopfabricrcvrol;
    private $soaop;
    private $poaopref;
    private $soaopitem;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;


    public function __construct(
      SoAopFabricRcvRepository $soaopfabricrcv,
      SoAopFabricRcvItemRepository $soaopfabricrcvitem,
      SoAopFabricRcvRolRepository $soaopfabricrcvrol,
      SoAopRepository $soaop, 
      SoAopRefRepository $poaopref, 
      SoAopItemRepository $soaopitem, 
      AutoyarnRepository $autoyarn,
      GmtspartRepository $gmtspart,
      UomRepository $uom,
      ColorrangeRepository $colorrange,
      ColorRepository $color
    ) {
        $this->soaopfabricrcv = $soaopfabricrcv;
        $this->soaopfabricrcvitem = $soaopfabricrcvitem;
        $this->soaopfabricrcvrol = $soaopfabricrcvrol;
        $this->soaop = $soaop;
        $this->poaopref = $poaopref;
        $this->soaopitem = $soaopitem;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->middleware('auth');
          
        $this->middleware('permission:view.soaopfabricrcvitems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soaopfabricrcvitems', ['only' => ['store']]);
        $this->middleware('permission:edit.soaopfabricrcvitems',   ['only' => ['update']]);
        $this->middleware('permission:delete.soaopfabricrcvitems', ['only' => ['destroy']]);

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
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->soaopfabricrcv
        ->join('so_aop_fabric_rcv_items',function($join){
          $join->on('so_aop_fabric_rcv_items.so_aop_fabric_rcv_id','=','so_aop_fabric_rcvs.id');
        })
        ->join('so_aop_refs',function($join){
          $join->on('so_aop_refs.id','=','so_aop_fabric_rcv_items.so_aop_ref_id');
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

        ->where([['so_aop_fabric_rcvs.id','=',request('so_aop_fabric_rcv_id')]])
        ->selectRaw('
            so_aop_fabric_rcv_items.id,
            so_aop_fabric_rcv_items.qty,
            so_aop_fabric_rcv_items.rate,
            so_aop_fabric_rcv_items.amount,
            so_aop_fabric_rcv_items.process_loss_per,
            so_aop_fabric_rcv_items.real_rate,
            so_aop_fabric_rcv_items.yarn_des,
            so_aop_fabric_rcv_items.remarks,
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

            uoms.code as uom_code
            '
          )
        ->orderBy('so_aop_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
          $rows->fabrication=$desDropdown[$rows->autoyarn_id];
          $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
          $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
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
      $so_aop_fabric_rcv_id=request('so_aop_fabric_rcv_id',0);
      $soaopfabricrcv=$this->soaopfabricrcv->find($so_aop_fabric_rcv_id);

      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'--','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
      $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
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

        ->where([['so_aops.id','=',$soaopfabricrcv->so_aop_id]])
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
            so_aop_items.gmt_style_ref,
            so_aop_items.gmt_sale_order_no,
            uoms.code as uom_code
            '
          )
        ->orderBy('so_aop_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
          $rows->fabrication=$desDropdown[$rows->autoyarn_id];
          $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
          $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
          $rows->gsm_weight=$rows->gsm_weight;
          $rows->colorrange_id=$colorrange[$rows->colorrange_id];
          return $rows;
        });
        return Template::LoadView('Subcontract.AOP.SoAopFabricRcvItemMatrix',['items'=>$rows]);

        //echo json_encode($rows);
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopFabricRcvItemRequest $request) {
      foreach($request->so_aop_ref_id as $index=>$so_aop_ref_id)
      {
        if($request->rate[$index])
        {
          $soaopfabricrcvitem=$this->soaopfabricrcvitem->create([
          'so_aop_fabric_rcv_id'=>$request->so_aop_fabric_rcv_id,
          'so_aop_ref_id'=>$so_aop_ref_id,
          //'qty'=>$request->qty[$index],
          'rate'=>$request->rate[$index],
          //'amount'=>$request->amount[$index],
          'process_loss_per'=>$request->process_loss_per[$index],
          'real_rate'=>$request->real_rate[$index],
          'yarn_des'=>$request->yarn_des[$index],
          'remarks'=>$request->remarks[$index],
          ]);

         /* $this->soaopfabricrcvrol->create([
          'so_aop_fabric_rcv_item_id'=>$soaopfabricrcvitem->id,
          'qty'=>$request->qty[$index],
          'rate'=>0,
          'amount'=>0,
        ]);*/
        }
      }
      
      if($soaopfabricrcvitem){
        return response()->json(array('success' => true,'id' =>  $soaopfabricrcvitem->id,'so_aop_fabric_rcv_id' =>  $request->so_aop_fabric_rcv_id,'message' => 'Save Successfully'),200);
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
        $rows=$this->soaopfabricrcvitem->find($id);
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
    public function update(SoAopFabricRcvItemRequest $request, $id) {
        /*if($request->qty <= 0){
          return response()->json(array('success' => false,'id' => $id,'so_aop_fabric_rcv_id' => $request->so_aop_fabric_rcv_id,'message' => '0 Qty Not Allowed'),200);
        }*/
        if($request->rate <= 0){
          return response()->json(array('success' => false,'id' => $id,'so_aop_fabric_rcv_id' => $request->so_aop_fabric_rcv_id,'message' => '0 Rate Not Allowed'),200);
        }
        $soaopfabricrcvitem=$this->soaopfabricrcvitem->update($id,$request->except(['id','so_aop_fabric_rcv_id','so_aop_ref_id']));

        /*$this->soaopfabricrcvrol
        ->where([['so_aop_fabric_rcv_item_id','=',$id]])
        ->update([
        'qty'=>$request->qty,
        'rate'=>0,
        'amount'=>0,
        ]);*/

        if($soaopfabricrcvitem){
          return response()->json(array('success' => true,'id' => $id,'so_aop_fabric_rcv_id' => $request->so_aop_fabric_rcv_id,'message' => 'Update Successfully'),200);
        }
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
      return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
      if($this->soaopfabricrcvitem->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }
}