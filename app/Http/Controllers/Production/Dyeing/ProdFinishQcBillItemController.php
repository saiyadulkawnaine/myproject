<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishQcBillItemRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use Illuminate\Support\Carbon;
use App\Http\Requests\Production\Dyeing\ProdFinishQcBillItemRequest;

class ProdFinishQcBillItemController extends Controller {

    private $prodfinishqcbillitem;
    private $prodbatch;
    private $prodbatchfinishqc;
    private $sodyeingfabricrcv;
    private $company;
    private $sodyeingitem;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $prodfinishdlv;

    public function __construct(
      ProdFinishDlvRepository $prodfinishdlv,
        ProdFinishQcBillItemRepository $prodfinishqcbillitem,
        ProdBatchRepository $prodbatch,  
        ProdBatchFinishQcRepository $prodbatchfinishqc,
        SoDyeingFabricRcvRepository $sodyeingfabricrcv,
        CompanyRepository $company,
        SoDyeingItemRepository $sodyeingitem, 
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        UomRepository $uom,
        ColorrangeRepository $colorrange,
        ColorRepository $color
      )
    {

        $this->prodfinishqcbillitem = $prodfinishqcbillitem;
        $this->prodfinishdlv = $prodfinishdlv;
        $this->prodbatch = $prodbatch;
        $this->prodbatchfinishqc = $prodbatchfinishqc;
        $this->sodyeingfabricrcv = $sodyeingfabricrcv;
        $this->company = $company;
        $this->sodyeingitem = $sodyeingitem;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;

        $this->middleware('auth');
        // $this->middleware('permission:view.acctermloans',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.acctermloans', ['only' => ['store']]);
        // $this->middleware('permission:edit.acctermloans',   ['only' => ['update']]);
        // $this->middleware('permission:delete.acctermloans', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
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
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');

      $rows=$this->prodfinishqcbillitem
        ->join('so_dyeing_fabric_rcv_items',function($join){
          $join->on('so_dyeing_fabric_rcv_items.id','=','prod_finish_qc_bill_Items.so_dyeing_fabric_rcv_item_id');
        })
        ->join('so_dyeing_refs',function($join){
          $join->on('so_dyeing_refs.id','=','so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
        })
        ->join('so_dyeings',function($join){
          $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_pos',function($join){
          $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_po_items',function($join){
          $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('po_dyeing_service_item_qties',function($join){
          $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
        })
        ->leftJoin('po_dyeing_service_items',function($join){
          $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
          ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
          $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
          $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
          $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
          $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        })
        ->leftJoin('budget_fabrics',function($join){
          $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('autoyarns',function($join){
          $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->leftJoin('constructions', function($join)  {
          $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->leftJoin('so_dyeing_items',function($join){
          $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->join('prod_batch_finish_qcs',function($join){
          $join->on('prod_batch_finish_qcs.id','=','prod_finish_qc_bill_Items.prod_batch_finish_qc_id');
        })
        ->join('prod_batches',function($join){
          $join->on('prod_batches.id','=','prod_batch_finish_qcs.prod_batch_id');
        })
        ->join('colors',function($join){
		      $join->on('colors.id','=','prod_batches.fabric_color_id');
		    })
       ->where([['prod_finish_qc_bill_Items.prod_finish_dlv_id','=',request('prod_finish_dlv_id',0)]])
       ->orderBy('prod_finish_qc_bill_Items.id','desc')
       ->get([
        'prod_finish_qc_bill_Items.*',
        'style_fabrications.autoyarn_id',
        'style_fabrications.fabric_look_id',
        'style_fabrications.fabric_shape_id',
        'style_fabrications.gmtspart_id',
        'budget_fabrics.gsm_weight',
        'po_dyeing_service_item_qties.dia',

        'so_dyeing_items.autoyarn_id as c_autoyarn_id',
        'so_dyeing_items.fabric_look_id as c_fabric_look_id',
        'so_dyeing_items.fabric_shape_id as c_fabric_shape_id',
        'so_dyeing_items.gmtspart_id as c_gmtspart_id',
        'so_dyeing_items.gsm_weight as c_gsm_weight',
        'so_dyeing_items.dia as c_dia',
        'prod_batches.batch_no',
        'colors.name as fabric_color_name',
       ])
       ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape) {
          $gmtspart_name=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
          $autoyarn=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
          $fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
          $fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
          $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
          $rows->dia=$rows->dia?$rows->dia:$rows->c_dia;
          $rows->fabrication=$gmtspart_name.", ".$autoyarn.", ".$fabriclooks.", ".$fabricshape;
          return $rows;
       });

      //  foreach($rows as $row){
      //     $prodfinishqcbillitem['id']=$row->id;
      //     $prodfinishqcbillitem['process_name']=$row->process_name;
      //     $prodfinishqcbillitem['amount']=number_format($row->amount,2);
      //     $prodfinishqcbillitem['qty']=number_format($row->qty,2);
      //     $prodfinishqcbillitem['rate']=number_format($row->rate,2);
      //     $prodfinishqcbillitem['fabric_color_name']=$row->fabric_color_name;
      //     $prodfinishqcbillitem['fabrication']=$row->fabrication;
      //     $prodfinishqcbillitem['no_of_roll']=$row->no_of_roll;
      //     $prodfinishqcbillitem['gsm_weight']=$row->gsm_weight;
      //     $prodfinishqcbillitem['dia']=$row->dia;
      //     $prodfinishqcbillitem['batch_no']=$row->batch_no;
      //     $prodfinishqcbillitem['remarks']=$row->remarks;

      //     array_push($prodfinishqcbillitems,$prodfinishqcbillitem);
      //  }
       echo json_encode($rows);
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
    public function store(ProdFinishQcBillItemRequest $request) {

        $prodfinishqcbillitem = $this->prodfinishqcbillitem->create([
          'prod_finish_dlv_id'=>$request->prod_finish_dlv_id,
          'process_name'=>$request->process_name,
          'prod_batch_finish_qc_id'=>$request->prod_batch_finish_qc_id,
          'so_dyeing_fabric_rcv_item_id'=>$request->so_dyeing_fabric_rcv_item_id,
          'no_of_roll'=>$request->no_of_roll,
          'rate'=>$request->rate,
          'amount'=>$request->amount,
          'qty'=>$request->qty,
          'remarks'=>$request->remarks,
        ]);
        if($prodfinishqcbillitem){
            return response()->json(array('success' => true,'id' =>  $prodfinishqcbillitem->id,'message' => 'Save Successfully'),200);
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
        $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
      }

      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
      $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');

      $rows=$this->prodfinishqcbillitem
        ->join('so_dyeing_fabric_rcv_items',function($join){
          $join->on('so_dyeing_fabric_rcv_items.id','=','prod_finish_qc_bill_Items.so_dyeing_fabric_rcv_item_id');
        })
        ->join('so_dyeing_refs',function($join){
          $join->on('so_dyeing_refs.id','=','so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
        })
        ->join('so_dyeings',function($join){
          $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_pos',function($join){
          $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('so_dyeing_po_items',function($join){
          $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->leftJoin('po_dyeing_service_item_qties',function($join){
          $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
        })
        ->leftJoin('po_dyeing_service_items',function($join){
          $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
          ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
          $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
          $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
          $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
          $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        })
        ->leftJoin('budget_fabrics',function($join){
          $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('autoyarns',function($join){
          $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->leftJoin('constructions', function($join)  {
          $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->leftJoin('so_dyeing_items',function($join){
          $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
        })
        ->join('prod_batch_finish_qcs',function($join){
          $join->on('prod_batch_finish_qcs.id','=','prod_finish_qc_bill_Items.prod_batch_finish_qc_id');
        })
        ->join('prod_batches',function($join){
          $join->on('prod_batches.id','=','prod_batch_finish_qcs.prod_batch_id');
        })
        ->join('colors',function($join){
		      $join->on('colors.id','=','prod_batches.fabric_color_id');
		    })
        ->where([['prod_finish_qc_bill_Items.id','=',$id]])
        ->orderBy('prod_finish_qc_bill_Items.id','desc')
        ->get([
          'prod_finish_qc_bill_Items.*',
          'style_fabrications.autoyarn_id',
          'style_fabrications.fabric_look_id',
          'style_fabrications.fabric_shape_id',
          'style_fabrications.gmtspart_id',
          'budget_fabrics.gsm_weight',
          'po_dyeing_service_item_qties.dia',

          'so_dyeing_items.autoyarn_id as c_autoyarn_id',
          'so_dyeing_items.fabric_look_id as c_fabric_look_id',
          'so_dyeing_items.fabric_shape_id as c_fabric_shape_id',
          'so_dyeing_items.gmtspart_id as c_gmtspart_id',
          'so_dyeing_items.gsm_weight as c_gsm_weight',
          'so_dyeing_items.dia as c_dia',
          'prod_batches.batch_no',
          'colors.name as fabric_color_name',
          'so_dyeing_refs.id as so_dyeing_ref_id'
        ])
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape) {
            $gmtspart_name=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
            $autoyarn=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
            $fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
            $fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
            $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
            $rows->dia=$rows->dia?$rows->dia:$rows->c_dia;
            $rows->fabrication=$gmtspart_name.", ".$autoyarn.", ".$fabriclooks.", ".$fabricshape;
            return $rows;
        })
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
    public function update(ProdFinishQcBillItemRequest $request, $id) {
        $prodfinishqcbillitem=$this->prodfinishqcbillitem->update($id,$request->except([
            'id','fabrication','fabric_color_name','gsm_weight','dia','batch_no'
        ]));
                 if($prodfinishqcbillitem){
            return response()->json(array('success' => true,'id' =>  $id, 'message' => 'Update Successfully'),200);
        }
    }

    /**
     *
     *
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodfinishqcbillitem->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }



  public function getItem()
  {
    //$prodfinishqcbillid=$this->prodfinishdlv->find(request('prodfinishqcbillid',0));
    
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
    $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'--','');
		$color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
		$uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
		$dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');

		$rows=$this->sodyeingfabricrcv
		->join('so_dyeing_fabric_rcv_items',function($join){
			$join->on('so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id','=','so_dyeing_fabric_rcvs.id');
		})
		->join('so_dyeing_refs',function($join){
			$join->on('so_dyeing_refs.id','=','so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
		})
		->join('so_dyeings',function($join){
			$join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
		})
		->leftJoin('so_dyeing_pos',function($join){
			$join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
		})
		->leftJoin('so_dyeing_po_items',function($join){
			$join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
		})
		->leftJoin('po_dyeing_service_item_qties',function($join){
		  $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
		})
		->leftJoin('po_dyeing_service_items',function($join){
		  $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
		  ->whereNull('po_dyeing_service_items.deleted_at');
		})
		->leftJoin('sales_orders',function($join){
		  $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
		})
		->leftJoin('jobs',function($join){
		  $join->on('jobs.id','=','sales_orders.job_id');
		})
		->leftJoin('styles',function($join){
		  $join->on('styles.id','=','jobs.style_id');
		})
		->leftJoin('budget_fabric_prods',function($join){
		  $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
		})
		->leftJoin('budget_fabrics',function($join){
		  $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
		})
		->leftJoin('style_fabrications',function($join){
		  $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
		})
		->leftJoin('autoyarns',function($join){
		  $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
		})
		->leftJoin('constructions', function($join)  {
		  $join->on('autoyarns.construction_id', '=', 'constructions.id');
		})
		->leftJoin('so_dyeing_items',function($join){
			$join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
		})
		->leftJoin('buyers',function($join){
		  $join->on('buyers.id','=','styles.buyer_id');
		})
		->leftJoin('buyers as gmt_buyer',function($join){
		  $join->on('gmt_buyer.id','=','so_dyeing_items.gmt_buyer');
		})
		->leftJoin('uoms',function($join){
		  $join->on('uoms.id','=','so_dyeing_items.uom_id');
		})
		->leftJoin('uoms as so_uoms',function($join){
		  $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
		})
		->leftJoin('colors as so_color',function($join){
		  $join->on('so_color.id','=','so_dyeing_items.fabric_color_id');
		})
		->leftJoin('colors as po_color',function($join){
		  $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
		})
    //->where([['so_dyeings.buyer_id','=',$prodfinishqcbillid->buyer_id]])
		->when(request('from_date',0), function ($q){
      return $q->where('so_dyeing_fabric_rcvs.receive_date', '>=',request('from_date',0));
    })
		->when(request('to_date',0), function ($q){
      return $q->where('so_dyeing_fabric_rcvs.receive_date', '<=',request('to_date',0));
    })
    ->when(request('sale_order_no',0), function ($q){
      return $q->where('so_dyeings.sales_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
    })
    ->when(request('buyer_id',0), function ($q){
      return $q->where('so_dyeings.buyer_id', '=', request('buyer_id', 0));
    })
		->selectRaw('
			so_dyeing_fabric_rcv_items.id,
			so_dyeing_fabric_rcv_items.qty,
			so_dyeing_fabric_rcv_items.rate,
			so_dyeing_fabric_rcv_items.amount,
			so_dyeing_fabric_rcv_items.process_loss_per,
			so_dyeing_fabric_rcv_items.real_rate,
			so_dyeing_fabric_rcv_items.yarn_des,
			so_dyeing_fabric_rcv_items.remarks,

			so_dyeing_refs.id as so_dyeing_ref_id,
			so_dyeing_refs.so_dyeing_id,
      styles.style_ref,
			style_fabrications.autoyarn_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.gmtspart_id,
			style_fabrications.dyeing_type_id,
			budget_fabrics.gsm_weight,

			po_dyeing_service_item_qties.fabric_color_id,
			po_color.name as dyeing_color,
			po_dyeing_service_item_qties.colorrange_id,
			po_dyeing_service_item_qties.dia,
			po_dyeing_service_item_qties.measurment,

			so_dyeing_items.autoyarn_id as c_autoyarn_id,
			so_dyeing_items.fabric_look_id as c_fabric_look_id,
			so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
			so_dyeing_items.gmtspart_id as c_gmtspart_id,
			so_dyeing_items.dyeing_type_id as c_dyeing_type_id,
			so_dyeing_items.gsm_weight as c_gsm_weight,

			so_dyeing_items.fabric_color_id as c_fabric_color_id,
			so_color.name as c_dyeing_color,
			so_dyeing_items.colorrange_id as c_colorrange_id,
			so_dyeing_items.dia as c_dia,
			so_dyeing_items.measurment as c_measurment,

			so_dyeing_items.gmt_style_ref,
			so_dyeing_items.gmt_sale_order_no,
      sales_orders.sale_order_no,
			gmt_buyer.name as gmt_buyer_name,
      buyers.name as buyer_name,
			uoms.code as uom_code,
			so_uoms.code as so_uom_name,
      so_dyeings.sales_order_no
		'
		)
		->orderBy('so_dyeing_fabric_rcv_items.id','desc')
		->get()
		->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype){
			$gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
			$autoyarn=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];

			$rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
			$rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
			$rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
			$rows->dia=$rows->dia?$rows->dia:$rows->c_dia;
			$rows->dyeing_color=$rows->dyeing_color?$rows->dyeing_color:$rows->c_dyeing_color;
			$rows->fabric_color_id=$rows->fabric_color_id?$rows->fabric_color_id:$rows->c_fabric_color_id;
			$rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];
			$rows->fabrication=$gmtspart.", ".$autoyarn;
			$rows->dyetype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:$dyetype[$rows->c_dyeing_type_id];
      $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
      $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
      $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
      $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
			return $rows;
		});
		echo json_encode($rows);

      
  }

  public function getProdBatchFinishQc(){
      $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');

        $rows=$this->prodbatchfinishqc
        ->join('prod_batches',function($join){
          $join->on('prod_batches.id','=','prod_batch_finish_qcs.prod_batch_id');
        })
        ->join('companies',function($join){
		      $join->on('companies.id','=','prod_batches.company_id');
		    })
        ->join('colors',function($join){
		      $join->on('colors.id','=','prod_batches.fabric_color_id');
		    })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
          $join->on('asset_quantity_costs.id','=','prod_batch_finish_qcs.machine_id');
        })
        ->leftJoin('colorranges',function($join){
          $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('employee_h_rs.id','=','prod_batch_finish_qcs.qc_by_id');
        })
        ->when(request('company_id',0), function ($q){
          return $q->where('prod_batches.company_id', '=',request('company_id',0));
        })
        ->when(request('batch_for',0), function ($q){
          return $q->where('prod_batches.batch_for', '=',request('batch_for',0));
        })
        ->when(request('batch_no',0), function ($q){
          return $q->where('prod_batches.batch_no', 'LIKE', "%".request('batch_no', 0)."%");
        })
        ->orderBy('prod_batch_finish_qcs.id','desc')
        ->get([
            'prod_batch_finish_qcs.*',
            'prod_batches.batch_no',
            'prod_batches.batch_date',
            'prod_batches.batch_for',
            'prod_batches.batch_wgt',
            'prod_batches.fabric_wgt',
            'companies.code as company_code',
            'colors.name as fabric_color_name',
            'batch_colors.name as batch_color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'colorranges.name as color_range_name',
            'employee_h_rs.name as qc_by_name',
        ])
        ->map(function($rows) use($batchfor,$shiftname){
            $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->shiftname=$rows->shift_id?$shiftname[$rows->shift_id]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            $rows->posting_date=date('Y-m-d',strtotime($rows->posting_date));
            return $rows;
        });
        echo json_encode($rows);
  }

}
