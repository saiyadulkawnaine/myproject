<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRtnRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRtnItemRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopFabricRtnItemRequest;

class SoAopFabricRtnItemController extends Controller {

   
    private $soaopfabricrtn;
    private $soaopfabricrtnitem;
    private $soaop;
    private $autoyarn;
    private $gmtspart;
    private $colorrange;
    private $color;


    public function __construct(
      SoAopFabricRtnRepository $soaopfabricrtn,
      SoAopFabricRtnItemRepository $soaopfabricrtnitem,
      SoAopRepository $soaop, 
      AutoyarnRepository $autoyarn,
      GmtspartRepository $gmtspart,
      ColorrangeRepository $colorrange,
      ColorRepository $color
    ) {
        $this->soaopfabricrtn = $soaopfabricrtn;
        $this->soaopfabricrtnitem = $soaopfabricrtnitem;
        $this->soaop = $soaop;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->middleware('auth');
      
        // $this->middleware('permission:view.soaopfabricrtnitems',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.soaopfabricrtnitems', ['only' => ['store']]);
        // $this->middleware('permission:edit.soaopfabricrtnitems',   ['only' => ['update']]);
        // $this->middleware('permission:delete.soaopfabricrtnitems', ['only' => ['destroy']]);

        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');

      
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
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->soaopfabricrtn
        ->join('so_aop_fabric_rtn_items',function($join){
          $join->on('so_aop_fabric_rtn_items.so_aop_fabric_rtn_id','=','so_aop_fabric_rtns.id');
        })

        ->join('so_aop_refs',function($join){
          $join->on('so_aop_refs.id','=','so_aop_fabric_rtn_items.so_aop_ref_id');
        })
        ->join('so_aops',function($join){
          $join->on('so_aops.id','=','so_aop_refs.so_aop_id');
        })
        ->join('so_aop_items',function($join){
            $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->join(\DB::raw("(
          SELECT 
          so_aop_fabric_rcv_items.so_aop_ref_id,
          sum(so_aop_fabric_rcv_items.qty) as qty,
          avg(so_aop_fabric_rcv_items.rate) as rate,
          sum(so_aop_fabric_rcv_items.amount) as amount 
          FROM so_aop_fabric_rcv_items 
          group by so_aop_fabric_rcv_items.so_aop_ref_id) soaopfabricrcv"), "soaopfabricrcv.so_aop_ref_id", "=", "so_aop_refs.id")
        
        ->where([['so_aop_fabric_rtns.id','=',request('so_aop_fabric_rtn_id',0)]])

        ->selectRaw('
            so_aops.sales_order_no,
            so_aop_refs.so_aop_id,
            so_aop_items.autoyarn_id,
            so_aop_items.fabric_look_id,
            so_aop_items.fabric_shape_id,
            so_aop_items.gmtspart_id,
            so_aop_items.gsm_weight,
            so_aop_items.fabric_color_id,
            so_aop_items.colorrange_id,          
            so_aop_items.gmt_style_ref,
            so_aop_items.gmt_sale_order_no,
            so_aop_fabric_rtn_items.*,
            soaopfabricrcv.rate,
            so_aop_fabric_rtn_items.qty*soaopfabricrcv.rate as amount
            '
          )
        ->orderBy('so_aop_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color){
          $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:'';
           $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
          $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
          $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';

          $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:'';
          $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:'';

          $rows->qty=number_format($rows->qty,2,'.',',');
          $rows->amount=number_format($rows->amount,2,'.',','); 
          return $rows;
        });
        echo json_encode($rows);
    
    }     

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
     {
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopFabricRtnItemRequest $request) {
        $soaopfabricrtnitem=$this->soaopfabricrtnitem->create($request->except(['id','fabrication']));
        
        if($soaopfabricrtnitem){
          return response()->json(array('success' => true,'id' =>  $soaopfabricrtnitem->id,'so_aop_fabric_rtn_id' =>  $request->so_aop_fabric_rtn_id,'message' => 'Save Successfully'),200);
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

      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');

      
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
        foreach($autoyarn as $data){
          $fabricDescriptionArr[$data->id]=$data->name;
          $fabricCompositionArr[$data->id][]=$data->composition_name." ".$data->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
          $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->soaopfabricrtnitem
        ->join('so_aop_fabric_rtns',function($join){
          $join->on('so_aop_fabric_rtn_items.so_aop_fabric_rtn_id','=','so_aop_fabric_rtns.id');
        })

        ->join('so_aop_refs',function($join){
          $join->on('so_aop_refs.id','=','so_aop_fabric_rtn_items.so_aop_ref_id');
        })
        ->join('so_aops',function($join){
          $join->on('so_aops.id','=','so_aop_refs.so_aop_id');
        })
        ->join('so_aop_items',function($join){
            $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->join(\DB::raw("(
          SELECT 
          so_aop_fabric_rcv_items.so_aop_ref_id,
          sum(so_aop_fabric_rcv_items.qty) as qty,
          avg(so_aop_fabric_rcv_items.rate) as rate,
          sum(so_aop_fabric_rcv_items.amount) as amount 
          FROM so_aop_fabric_rcv_items 
          group by so_aop_fabric_rcv_items.so_aop_ref_id) soaopfabricrcv"), "soaopfabricrcv.so_aop_ref_id", "=", "so_aop_refs.id")
        
        ->where([['so_aop_fabric_rtn_items.id','=',$id]])

        ->selectRaw('
            so_aops.sales_order_no,
            so_aop_refs.so_aop_id,
            so_aop_items.autoyarn_id,
            so_aop_items.fabric_look_id,
            so_aop_items.fabric_shape_id,
            so_aop_items.gmtspart_id,
            so_aop_items.gsm_weight,
            so_aop_items.fabric_color_id,
            so_aop_items.colorrange_id,           
            so_aop_items.gmt_style_ref,
            so_aop_items.gmt_sale_order_no,
            so_aop_fabric_rtn_items.*,
            soaopfabricrcv.rate,
            so_aop_fabric_rtn_items.qty*soaopfabricrcv.rate as amount
            '
          )
        ->orderBy('so_aop_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color){
          $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:'';
          $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
          $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
          $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';

          $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:'';
          $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:'';

          //$rows->qty=number_format($rows->qty,2,'.',',');
          //$rows->amount=number_format($rows->amount,2,'.',','); 
          return $rows;
        })->first();

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
    public function update(SoAopFabricRtnItemRequest $request, $id) {

      $soaopfabricrtnitem=$this->soaopfabricrtnitem->update($id,$request->except(['id','fabrication']));
        
        if($soaopfabricrtnitem){
          return response()->json(array('success' => true,'id' => $id,'so_aop_fabric_rtn_id' => $request->so_aop_fabric_rtn_id,'message' => 'Update Successfully'),200);
        }
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
      if($this->soaopfabricrtnitem->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }

    


    public function getItem()
    {
      $soaopfabricrtn=$this->soaopfabricrtn->find(request('so_aop_fabric_rtn_id',0));

      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');

      
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

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->soaop
        ->join('so_aop_refs',function($join){
          $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
        })
        ->join('so_aop_items',function($join){
            $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->join(\DB::raw("(
          SELECT 
          so_aop_fabric_rcv_items.so_aop_ref_id,
          sum(so_aop_fabric_rcv_items.qty) as qty,
          avg(so_aop_fabric_rcv_items.rate) as rate,
          sum(so_aop_fabric_rcv_items.amount) as amount 
          FROM so_aop_fabric_rcv_items 
          group by so_aop_fabric_rcv_items.so_aop_ref_id) soaopfabricrcv"), "soaopfabricrcv.so_aop_ref_id", "=", "so_aop_refs.id")
        
        ->where([['so_aops.company_id','=',$soaopfabricrtn->company_id]])
        ->where([['so_aops.buyer_id','=',$soaopfabricrtn->buyer_id]])
        ->where([['so_aops.sales_order_no','=',request('sales_order_no',0)]])
        ->selectRaw('
            so_aops.sales_order_no,
            so_aop_refs.id,
            so_aop_refs.so_aop_id,
            so_aop_items.autoyarn_id,
            so_aop_items.fabric_look_id,
            so_aop_items.fabric_shape_id,
            so_aop_items.gmtspart_id,
            so_aop_items.gsm_weight,
            so_aop_items.fabric_color_id,
            so_aop_items.colorrange_id,
            so_aop_items.gmt_style_ref,
            so_aop_items.gmt_sale_order_no,
            soaopfabricrcv.qty,
            soaopfabricrcv.rate,
            soaopfabricrcv.amount
            '
          )

        ->orderBy('so_aop_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color){
          $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:'';
          $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
          $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
          $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';

          $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:'';
          $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:'';

          $rows->qty=number_format($rows->qty,2,'.',',');
          $rows->amount=number_format($rows->amount,2,'.',','); 
          return $rows;
        });
        echo json_encode($rows);
    }
}