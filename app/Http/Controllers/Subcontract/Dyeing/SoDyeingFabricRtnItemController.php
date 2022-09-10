<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRtnRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRtnItemRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingFabricRtnItemRequest;

class SoDyeingFabricRtnItemController extends Controller {

   
    private $sodyeingfabricrtn;
    private $sodyeingfabricrtnitem;
    private $sodyeing;
    private $autoyarn;
    private $gmtspart;
    private $colorrange;
    private $color;


    public function __construct(
      SoDyeingFabricRtnRepository $sodyeingfabricrtn,
      SoDyeingFabricRtnItemRepository $sodyeingfabricrtnitem,
      SoDyeingRepository $sodyeing, 
      AutoyarnRepository $autoyarn,
      GmtspartRepository $gmtspart,
      ColorrangeRepository $colorrange,
      ColorRepository $color
    ) {
        $this->sodyeingfabricrtn = $sodyeingfabricrtn;
        $this->sodyeingfabricrtnitem = $sodyeingfabricrtnitem;
        $this->sodyeing = $sodyeing;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->middleware('auth');
      
        // $this->middleware('permission:view.sodyeingfabricrtnitems',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.sodyeingfabricrtnitems', ['only' => ['store']]);
        // $this->middleware('permission:edit.sodyeingfabricrtnitems',   ['only' => ['update']]);
        // $this->middleware('permission:delete.sodyeingfabricrtnitems', ['only' => ['destroy']]);

        
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
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->sodyeingfabricrtn
        ->join('so_dyeing_fabric_rtn_items',function($join){
          $join->on('so_dyeing_fabric_rtn_items.so_dyeing_fabric_rtn_id','=','so_dyeing_fabric_rtns.id');
        })

        ->join('so_dyeing_refs',function($join){
          $join->on('so_dyeing_refs.id','=','so_dyeing_fabric_rtn_items.so_dyeing_ref_id');
        })
        ->join('so_dyeings',function($join){
          $join->on('so_dyeings.id','=','so_dyeing_refs.so_dyeing_id');
        })
        ->join('so_dyeing_items',function($join){
            $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->join(\DB::raw("(
          SELECT 
          so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
          sum(so_dyeing_fabric_rcv_items.qty) as qty,
          avg(so_dyeing_fabric_rcv_items.rate) as rate,
          sum(so_dyeing_fabric_rcv_items.amount) as amount 
          FROM so_dyeing_fabric_rcv_items 
          group by so_dyeing_fabric_rcv_items.so_dyeing_ref_id) sodyeingfabricrcv"), "sodyeingfabricrcv.so_dyeing_ref_id", "=", "so_dyeing_refs.id")
        
        ->where([['so_dyeing_fabric_rtns.id','=',request('so_dyeing_fabric_rtn_id',0)]])

        ->selectRaw('
            so_dyeings.sales_order_no,
            so_dyeing_refs.so_dyeing_id,
            so_dyeing_items.autoyarn_id,
            so_dyeing_items.fabric_look_id,
            so_dyeing_items.fabric_shape_id,
            so_dyeing_items.gmtspart_id,
            so_dyeing_items.gsm_weight,
            so_dyeing_items.fabric_color_id,
            so_dyeing_items.colorrange_id,
            so_dyeing_items.dyeing_type_id,
            so_dyeing_items.gmt_style_ref,
            so_dyeing_items.gmt_sale_order_no,
            so_dyeing_fabric_rtn_items.*,
            sodyeingfabricrcv.rate,
            so_dyeing_fabric_rtn_items.qty*sodyeingfabricrcv.rate as amount
            '
          )
        ->orderBy('so_dyeing_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$dyetype){
          $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:'';
           $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
          $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
          $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';

          $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:'';
          $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:'';

          $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:'';
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
    public function store(SoDyeingFabricRtnItemRequest $request) {
        $sodyeingfabricrtnitem=$this->sodyeingfabricrtnitem->create($request->except(['id','fabrication']));
        
        if($sodyeingfabricrtnitem){
          return response()->json(array('success' => true,'id' =>  $sodyeingfabricrtnitem->id,'so_dyeing_fabric_rtn_id' =>  $request->so_dyeing_fabric_rtn_id,'message' => 'Save Successfully'),200);
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
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->sodyeingfabricrtnitem
        ->join('so_dyeing_fabric_rtns',function($join){
          $join->on('so_dyeing_fabric_rtn_items.so_dyeing_fabric_rtn_id','=','so_dyeing_fabric_rtns.id');
        })

        ->join('so_dyeing_refs',function($join){
          $join->on('so_dyeing_refs.id','=','so_dyeing_fabric_rtn_items.so_dyeing_ref_id');
        })
        ->join('so_dyeings',function($join){
          $join->on('so_dyeings.id','=','so_dyeing_refs.so_dyeing_id');
        })
        ->join('so_dyeing_items',function($join){
            $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->join(\DB::raw("(
          SELECT 
          so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
          sum(so_dyeing_fabric_rcv_items.qty) as qty,
          avg(so_dyeing_fabric_rcv_items.rate) as rate,
          sum(so_dyeing_fabric_rcv_items.amount) as amount 
          FROM so_dyeing_fabric_rcv_items 
          group by so_dyeing_fabric_rcv_items.so_dyeing_ref_id) sodyeingfabricrcv"), "sodyeingfabricrcv.so_dyeing_ref_id", "=", "so_dyeing_refs.id")
        
        ->where([['so_dyeing_fabric_rtn_items.id','=',$id]])

        ->selectRaw('
            so_dyeings.sales_order_no,
            so_dyeing_refs.so_dyeing_id,
            so_dyeing_items.autoyarn_id,
            so_dyeing_items.fabric_look_id,
            so_dyeing_items.fabric_shape_id,
            so_dyeing_items.gmtspart_id,
            so_dyeing_items.gsm_weight,
            so_dyeing_items.fabric_color_id,
            so_dyeing_items.colorrange_id,
            so_dyeing_items.dyeing_type_id,
            so_dyeing_items.gmt_style_ref,
            so_dyeing_items.gmt_sale_order_no,
            so_dyeing_fabric_rtn_items.*,
            sodyeingfabricrcv.rate,
            so_dyeing_fabric_rtn_items.qty*sodyeingfabricrcv.rate as amount
            '
          )
        ->orderBy('so_dyeing_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$dyetype){
          $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:'';
           $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
          $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
          $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';

          $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:'';
          $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:'';

          $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:'';
          $rows->qty=number_format($rows->qty,2,'.',',');
          $rows->amount=number_format($rows->amount,2,'.',','); 
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
    public function update(SoDyeingFabricRtnItemRequest $request, $id) {

      $sodyeingfabricrtnitem=$this->sodyeingfabricrtnitem->update($id,$request->except(['id','fabrication']));
        
        if($sodyeingfabricrtnitem){
          return response()->json(array('success' => true,'id' => $id,'so_dyeing_fabric_rtn_id' => $request->so_dyeing_fabric_rtn_id,'message' => 'Update Successfully'),200);
        }
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
      if($this->sodyeingfabricrcvitem->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }

    


    public function getItem()
    {
      $sodyeingfabricrtn=$this->sodyeingfabricrtn->find(request('so_dyeing_fabric_rtn_id',0));

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
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

        $rows=$this->sodyeing
        ->join('so_dyeing_refs',function($join){
          $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
        ->join('so_dyeing_items',function($join){
            $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->join(\DB::raw("(
          SELECT 
          so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
          sum(so_dyeing_fabric_rcv_items.qty) as qty,
          avg(so_dyeing_fabric_rcv_items.rate) as rate,
          sum(so_dyeing_fabric_rcv_items.amount) as amount 
          FROM so_dyeing_fabric_rcv_items 
          group by so_dyeing_fabric_rcv_items.so_dyeing_ref_id) sodyeingfabricrcv"), "sodyeingfabricrcv.so_dyeing_ref_id", "=", "so_dyeing_refs.id")
        
        ->where([['so_dyeings.company_id','=',$sodyeingfabricrtn->company_id]])
        ->where([['so_dyeings.buyer_id','=',$sodyeingfabricrtn->buyer_id]])
        ->where([['so_dyeings.sales_order_no','=',request('sales_order_no',0)]])
        ->selectRaw('
            so_dyeings.sales_order_no,
            so_dyeing_refs.id,
            so_dyeing_refs.so_dyeing_id,
            so_dyeing_items.autoyarn_id,
            so_dyeing_items.fabric_look_id,
            so_dyeing_items.fabric_shape_id,
            so_dyeing_items.gmtspart_id,
            so_dyeing_items.gsm_weight,
            so_dyeing_items.fabric_color_id,
            so_dyeing_items.colorrange_id,
            so_dyeing_items.dyeing_type_id,
            so_dyeing_items.gmt_style_ref,
            so_dyeing_items.gmt_sale_order_no,
            sodyeingfabricrcv.qty,
            sodyeingfabricrcv.rate,
            sodyeingfabricrcv.amount
            '
          )

        ->orderBy('so_dyeing_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$dyetype){
          $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:'';
           $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:'';
          $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:'';
          $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:'';

          $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:'';
          $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:'';

          $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:'';
          $rows->qty=number_format($rows->qty,2,'.',',');
          $rows->amount=number_format($rows->amount,2,'.',','); 
          return $rows;
        });
        echo json_encode($rows);
    }
}