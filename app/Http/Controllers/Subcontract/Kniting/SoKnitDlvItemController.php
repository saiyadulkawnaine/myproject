<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\SoKnitDlvItemRequest;

class SoKnitDlvItemController extends Controller {

    private $soknitdlv;
    private $soknitdlvitem;
    private $soknit;
    private $soknitpoitem;
    private $soknityarnrcv;
    private $soknityarnrcvitem;
    private $uom;
    private $itemaccount;
    private $color;
    private $autoyarn;
    private $gmtspart;
    private $colorrange;

    public function __construct(
        SoKnitDlvRepository $soknitdlv,
        SoKnitDlvItemRepository $soknitdlvitem,
        SoKnitRepository $soknit,
        SoKnitPoItemRepository $soknitpoitem,
        SoKnitYarnRcvRepository $soknityarnrcv,
        SoKnitYarnRcvItemRepository $soknityarnrcvitem,
        UomRepository $uom,
        ItemAccountRepository $itemaccount,
        ColorRepository $color,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        ColorrangeRepository $colorrange

 
        ) {
        $this->soknitdlv = $soknitdlv;
        $this->soknitdlvitem = $soknitdlvitem;
        $this->soknit = $soknit;
        $this->soknitpoitem = $soknitpoitem;
        $this->soknityarnrcv = $soknityarnrcv;
        $this->soknityarnrcvitem = $soknityarnrcvitem;
        $this->uom = $uom;
        $this->itemaccount = $itemaccount;
        $this->color = $color;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->colorrange = $colorrange;
         
        $this->middleware('auth');
        $this->middleware('permission:view.soknitdlvitems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soknitdlvitems', ['only' => ['store']]);
        $this->middleware('permission:edit.soknitdlvitems',   ['only' => ['update']]);
        $this->middleware('permission:delete.soknitdlvitems', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

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
        $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
        }
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');


        $rows=$this->soknitdlv
        ->join('so_knit_dlv_items',function($join){
        $join->on('so_knit_dlv_items.so_knit_dlv_id','=','so_knit_dlvs.id');
        })
        ->join('so_knit_refs',function($join){
        $join->on('so_knit_refs.id','=','so_knit_dlv_items.so_knit_ref_id');
        })
         ->join('so_knits',function($join){
        $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
        })
        ->leftJoin('so_knit_items',function($join){
        $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','so_knit_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_knit_items.uom_id');
        })
        ->leftJoin('colors',function($join){
        $join->on('colors.id','=','so_knit_items.fabric_color_id');
        })
        ->where([['so_knit_dlvs.id','=',request('so_knit_dlv_id',0)]])
        ->selectRaw('
        so_knit_refs.id as so_knit_ref_id,
        so_knit_refs.so_knit_id,
        so_knit_items.autoyarn_id,
        so_knit_items.fabric_look_id,
        so_knit_items.fabric_shape_id,
        so_knit_items.gmtspart_id,
        so_knit_items.gsm_weight,
        so_knit_items.dia,
        so_knit_items.measurment,
        so_knit_dlv_items.id,
        so_knit_dlv_items.qty,
        so_knit_dlv_items.rate,
        so_knit_dlv_items.amount,
        so_knit_dlv_items.no_of_roll,
        so_knit_dlv_items.remarks,
        so_knit_items.gmt_style_ref,
        so_knit_items.gmt_sale_order_no,
        buyers.name as buyer_name,
        uoms.code as uom_name,
        colors.name as fabric_color
        '
        )
        ->orderBy('so_knit_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$fabricDescriptionArr,$gmtspart,$fabriclooks,$fabricshape,$uom){
        $rows->fabrication=isset($desDropdown[$rows->autoyarn_id])?$desDropdown[$rows->autoyarn_id]:'';
        $rows->fabricshape=isset($fabricshape[$rows->fabric_shape_id])?$fabricshape[$rows->fabric_shape_id]:'';
        $rows->fabriclooks=isset($fabricshape[$rows->fabric_look_id])?$fabricshape[$rows->fabric_look_id]:'';
        $rows->gmtspart=isset($gmtspart[$rows->gmtspart_id])?$gmtspart[$rows->gmtspart_id]:'';
        $rows->constructions_name=isset($fabricDescriptionArr[$rows->autoyarn_id])?$fabricDescriptionArr[$rows->autoyarn_id]:'';
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
    public function create(Request $request) {
        $so_knit_dlv_id=request('so_knit_dlv_id',0);
        $sales_order_no=request('sales_order_no',0);
        $style_ref=request('style_ref',0);
        $soknitdlv=$this->soknitdlv->find($so_knit_dlv_id);
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'--','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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

        $rows=$this->soknit
        ->join('so_knit_refs',function($join){
        $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
        })
        ->leftJoin('so_knit_items',function($join){
        $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_knit_items.uom_id');
        })
        ->leftJoin('colors',function($join){
        $join->on('colors.id','=','so_knit_items.fabric_color_id');
        })
        ->where([['so_knits.company_id','=',$soknitdlv->company_id]])
        ->where([['so_knits.buyer_id','=',$soknitdlv->buyer_id]])
        ->where([['so_knits.currency_id','=',$soknitdlv->currency_id]])
        ->where([['so_knits.sales_order_no','=',$sales_order_no]])
        ->when(request('style_ref'), function($q) {
        return $q->where('so_knit_items.gmt_style_ref', '=' , request('style_ref',0));
        })
        ->selectRaw('
        so_knit_refs.id as so_knit_ref_id,
        so_knit_refs.so_knit_id,
        so_knit_items.autoyarn_id,
        so_knit_items.fabric_look_id,
        so_knit_items.fabric_shape_id,
        so_knit_items.gmtspart_id,
        so_knit_items.gsm_weight,
        so_knit_items.fabric_color_id,
        so_knit_items.dia,
        so_knit_items.rate,
        colors.name as fabric_color,
        so_knit_items.qty,
        so_knit_items.rate,
        so_knit_items.amount,
        so_knit_items.gmt_style_ref,
        so_knit_items.gmt_sale_order_no,
        uoms.code as uom_code
        '
        )
        ->orderBy('so_knit_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$fabricDescriptionArr){
        $rows->fabrication=$desDropdown[$rows->autoyarn_id];
        $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
        $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
        $rows->gsm_weight=$rows->gsm_weight;
        return $rows;
        });
        return Template::LoadView('Subcontract.Kniting.SoKnitDlvItemMatrix',['items'=>$rows]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoKnitDlvItemRequest $request) {

        $master=$this->soknitdlv->find($request->so_knit_dlv_id);
        if($master->approved_by && $master->approved_at && \Auth::user()->level() < 5){
        return response()->json(array('success' => false,'id' => $id,'message' => 'It is Approved,So Update Not Possible'),200);
        }

        foreach($request->so_knit_ref_id as $index=>$so_knit_ref_id)
        {
            if($request->qty[$index])
            {
                $soknitdlvitem=$this->soknitdlvitem->create([
                'so_knit_dlv_id'=>$request->so_knit_dlv_id,
                'so_knit_ref_id'=>$so_knit_ref_id,
                'qty'=>$request->qty[$index],
                'rate'=>$request->rate[$index],
                'amount'=>$request->amount[$index],
                'no_of_roll'=>$request->no_of_roll[$index],
                'remarks'=>$request->remarks[$index],
                ]);
            }
        }
        
        if($soknitdlvitem){
          return response()->json(array('success' => true,'id' =>  $soknitdlvitem->id,'so_knit_dlv_id' =>  $request->so_knit_dlv_id,'message' => 'Save Successfully'),200);
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
        $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
        }
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');


        $rows=$this->soknitdlvitem
        ->join('so_knit_dlvs',function($join){
        $join->on('so_knit_dlv_items.so_knit_dlv_id','=','so_knit_dlvs.id');
        })
        ->join('so_knit_refs',function($join){
        $join->on('so_knit_refs.id','=','so_knit_dlv_items.so_knit_ref_id');
        })
         ->join('so_knits',function($join){
        $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
        })
        ->leftJoin('so_knit_items',function($join){
        $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','so_knit_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_knit_items.uom_id');
        })
        ->leftJoin('colors',function($join){
        $join->on('colors.id','=','so_knit_items.fabric_color_id');
        })
        ->where([['so_knit_dlv_items.id','=',$id]])
        ->selectRaw('
        so_knit_refs.id as so_knit_ref_id,
        so_knit_refs.so_knit_id,
        so_knit_items.autoyarn_id,
        so_knit_items.fabric_look_id,
        so_knit_items.fabric_shape_id,
        so_knit_items.gmtspart_id,
        so_knit_items.gsm_weight,
        so_knit_items.dia,
        so_knit_items.measurment,
        so_knit_dlv_items.id,
        so_knit_dlv_items.qty,
        so_knit_dlv_items.rate,
        so_knit_dlv_items.amount,
        so_knit_dlv_items.no_of_roll,
        so_knit_dlv_items.remarks,
        so_knit_items.gmt_style_ref,
        so_knit_items.gmt_sale_order_no,
        buyers.name as buyer_name,
        uoms.code as uom_name,
        colors.name as fabric_color
        '
        )
        ->orderBy('so_knit_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$fabricDescriptionArr,$gmtspart,$fabriclooks,$fabricshape,$uom){
        $rows->fabrication=$desDropdown[$rows->autoyarn_id];
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
    public function update(SoKnitDlvItemRequest $request, $id) {
        
        $master=$this->soknitdlv->find($request->so_knit_dlv_id);
        if($master->approved_by && $master->approved_at && \Auth::user()->level() < 5){
        return response()->json(array('success' => false,'id' => $id,'message' => 'It is Approved,So Update Not Possible'),200);
        }

        $soknitdlvitem=$this->soknitdlvitem->update($id,
            [
                'qty'=>$request->qty,
                'rate'=>$request->rate,
                'amount'=>$request->amount,
                'no_of_roll'=>$request->no_of_roll,
                'remarks'=>$request->remarks,
            ]
        );
        if($soknitdlvitem){
            return response()->json(array('success' => true,'id' => $id,'so_knit_dlv_id' =>  $request->so_knit_dlv_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->soknitdlvitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}