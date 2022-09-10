<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvItemRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvItemRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRefRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingDlvItemRequest;

class SoDyeingDlvItemController extends Controller {

   
    private $sodyeingdlvitem;
    private $sodyeingdlv;
    private $sodyeingfabricrcv;
    private $sodyeingfabricrcvitem;
    private $sodyeing;
    private $podyeingref;
    private $sodyeingitem;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;


    public function __construct(
      SoDyeingDlvItemRepository $sodyeingdlvitem,
      SoDyeingDlvRepository $sodyeingdlv,
      SoDyeingFabricRcvRepository $sodyeingfabricrcv,
      SoDyeingFabricRcvItemRepository $sodyeingfabricrcvitem,
      SoDyeingRepository $sodyeing, 
      SoDyeingRefRepository $podyeingref, 
      SoDyeingItemRepository $sodyeingitem, 
      AutoyarnRepository $autoyarn,
      GmtspartRepository $gmtspart,
      UomRepository $uom,
      ColorrangeRepository $colorrange,
      ColorRepository $color
    ) {
        $this->sodyeingdlvitem = $sodyeingdlvitem;
        $this->sodyeingdlv = $sodyeingdlv;
        $this->sodyeingfabricrcv = $sodyeingfabricrcv;
        $this->sodyeingfabricrcvitem = $sodyeingfabricrcvitem;
        $this->sodyeing = $sodyeing;
        $this->podyeingref = $podyeingref;
        $this->sodyeingitem = $sodyeingitem;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->middleware('auth');
          
             $this->middleware('permission:view.sodyeingdlvitems',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.sodyeingdlvitems', ['only' => ['store']]);
            $this->middleware('permission:edit.sodyeingdlvitems',   ['only' => ['update']]);
            $this->middleware('permission:delete.sodyeingdlvitems', ['only' => ['destroy']]);

            
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
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
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
        

        $rows=$this->sodyeingdlv
        ->join('so_dyeing_dlv_items',function($join){
          $join->on('so_dyeing_dlv_items.so_dyeing_dlv_id','=','so_dyeing_dlvs.id');
        })
        
        ->join('so_dyeing_refs',function($join){
          $join->on('so_dyeing_refs.id','=','so_dyeing_dlv_items.so_dyeing_ref_id');
        })
        ->leftJoin('so_dyeing_items',function($join){
            $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','so_dyeing_items.uom_id');
        })
        ->leftJoin('colors',function($join){
          $join->on('colors.id','=','so_dyeing_items.fabric_color_id');
        })

        ->where([['so_dyeing_dlvs.id','=',request('so_dyeing_dlv_id')]])
        ->selectRaw('
            so_dyeing_dlv_items.id,
            so_dyeing_dlv_items.qty,
            so_dyeing_dlv_items.rate,
            so_dyeing_dlv_items.amount,
            so_dyeing_dlv_items.batch_no,
            so_dyeing_dlv_items.process_name,
            so_dyeing_dlv_items.fin_dia,
            so_dyeing_dlv_items.fin_gsm,
            so_dyeing_dlv_items.grey_used,
            so_dyeing_dlv_items.no_of_roll,
            so_dyeing_dlv_items.remarks,
            so_dyeing_refs.id as so_dyeing_ref_id,
            so_dyeing_refs.so_dyeing_id,
            so_dyeing_items.autoyarn_id,
            so_dyeing_items.fabric_look_id,
            so_dyeing_items.fabric_shape_id,
            so_dyeing_items.gmtspart_id,
            so_dyeing_items.gsm_weight,
            so_dyeing_items.fabric_color_id,
            so_dyeing_items.dyeing_type_id,
            colors.name as dyeing_color,
            so_dyeing_items.colorrange_id,
            uoms.code as uom_code
            '
          )
        ->orderBy('so_dyeing_dlv_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
          $rows->fabrication=$gmtspart[$rows->gmtspart_id].", ".$desDropdown[$rows->autoyarn_id];
          $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
          $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
          $rows->dyetype=$dyetype[$rows->dyeing_type_id];
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
      $so_dyeing_dlv_id=request('so_dyeing_dlv_id',0);
      $sodyeingdlv=$this->sodyeingdlv->find($so_dyeing_dlv_id);

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



      $rows=$this->sodyeing
      ->join('so_dyeing_refs',function($join){
      $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
      })
      ->leftJoin('so_dyeing_items',function($join){
      $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
      })
      ->leftJoin('uoms',function($join){
      $join->on('uoms.id','=','so_dyeing_items.uom_id');
      })
      ->leftJoin('colors',function($join){
      $join->on('colors.id','=','so_dyeing_items.fabric_color_id');
      })
      ->join('so_dyeing_fabric_rcvs',function($join){
      $join->on('so_dyeing_fabric_rcvs.so_dyeing_id','=','so_dyeings.id');
      })
      ->join('so_dyeing_fabric_rcv_items',function($join){
      $join->on('so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id','=','so_dyeing_fabric_rcvs.id');
      $join->on('so_dyeing_fabric_rcv_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
      })
      ->where([['so_dyeings.company_id','=',$sodyeingdlv->company_id]])
      ->where([['so_dyeings.buyer_id','=',$sodyeingdlv->buyer_id]])
      ->where([['so_dyeings.currency_id','=',$sodyeingdlv->currency_id]])
      ->where([['so_dyeings.sales_order_no','=',request('sales_order_no',0)]])
      ->selectRaw('
      so_dyeing_refs.id as so_dyeing_ref_id,
      so_dyeing_refs.so_dyeing_id,
      so_dyeing_items.autoyarn_id,
      so_dyeing_items.fabric_look_id,
      so_dyeing_items.fabric_shape_id,
      so_dyeing_items.gmtspart_id,
      so_dyeing_items.gsm_weight,
      so_dyeing_items.fabric_color_id,
      colors.name as dyeing_color,
      so_dyeing_items.colorrange_id,
      so_dyeing_items.qty,
      so_dyeing_items.rate,
      so_dyeing_items.amount,
      so_dyeing_items.dyeing_type_id,
      so_dyeing_items.gmt_style_ref,
      so_dyeing_items.gmt_sale_order_no,
      uoms.code as uom_code
      '
      )
      ->groupBy([
      'so_dyeing_refs.id',
      'so_dyeing_refs.so_dyeing_id',
      'so_dyeing_items.autoyarn_id',
      'so_dyeing_items.fabric_look_id',
      'so_dyeing_items.fabric_shape_id',
      'so_dyeing_items.gmtspart_id',
      'so_dyeing_items.gsm_weight',
      'so_dyeing_items.fabric_color_id',
      'colors.name',
      'so_dyeing_items.id',
      'so_dyeing_items.colorrange_id',
      'so_dyeing_items.qty',
      'so_dyeing_items.rate',
      'so_dyeing_items.amount',
      'so_dyeing_items.dyeing_type_id',
      'so_dyeing_items.gmt_style_ref',
      'so_dyeing_items.gmt_sale_order_no',
      'uoms.code'
      ])
      ->orderBy('so_dyeing_items.id','desc')
      ->get()
      ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
      $rows->fabrication=$gmtspart[$rows->gmtspart_id].", ".$desDropdown[$rows->autoyarn_id];
      $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
      $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
      $rows->dyetype=$dyetype[$rows->dyeing_type_id];
      $rows->gsm_weight=$rows->gsm_weight;
      $rows->colorrange_id=$colorrange[$rows->colorrange_id];
      return $rows;
      });
      return Template::LoadView('Subcontract.Dyeing.SoDyeingDlvItemMatrix',['items'=>$rows]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoDyeingDlvItemRequest $request) {
      $master=$this->sodyeingdlv->find($request->so_dyeing_dlv_id);
      if($master->approved_by && $master->approved_at && \Auth::user()->level() < 5){
        return response()->json(array('success' => false,'id' => $id,'message' => 'It is Approved,So New Item Add Not Possible'),200);
      }

      foreach($request->so_dyeing_ref_id as $index=>$so_dyeing_ref_id)
      {
        if($request->qty[$index])
        {
          $sodyeingdlvitem=$this->sodyeingdlvitem->create([
          'so_dyeing_dlv_id'=>$request->so_dyeing_dlv_id,
          'so_dyeing_ref_id'=>$so_dyeing_ref_id,
          'batch_no'=>$request->batch_no[$index],
          'process_name'=>$request->process_name[$index],
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
      
      if($sodyeingdlvitem){
        return response()->json(array('success' => true,'id' =>  $sodyeingdlvitem->id,'so_dyeing_dlv_id' =>  $request->so_dyeing_dlv_id,'message' => 'Save Successfully'),200);
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
        $rows=$this->sodyeingdlvitem->find($id);
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
    public function update(SoDyeingDlvItemRequest $request, $id) {
      $master=$this->sodyeingdlv->find($request->so_dyeing_dlv_id);
      if($master->approved_by && $master->approved_at && \Auth::user()->level() < 5){
        return response()->json(array('success' => false,'id' => $id,'message' => 'It is Approved,So Update Not Possible'),200);
      }
      $sodyeingfabricrcvitem=$this->sodyeingdlvitem->update($id,$request->except(['id','so_dyeing_dlv_id','so_dyeing_ref_id']));
        
        if($sodyeingfabricrcvitem){
          return response()->json(array('success' => true,'id' => $id,'so_dyeing_dlv_id' => $request->so_dyeing_dlv_id,'message' => 'Update Successfully'),200);
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
}