<?php

namespace App\Http\Controllers\Inventory\FinishFabric;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvFabricRepository;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;

use App\Library\Template;
use App\Http\Requests\Inventory\FinishFabric\InvFinishFabRcvPurFabricRequest;

class InvFinishFabRcvPurFabricController extends Controller {

    private $invrcv;
    private $invfinishfabrcv;
    private $pofabric;
    private $budgetfabric;
    private $gmtspart;
    private $autoyarn;
    private $itemaccount;
    

    public function __construct(
        InvRcvRepository $invrcv,
        InvFinishFabRcvFabricRepository $invfinishfabrcvfabric,
        PoFabricRepository $pofabric,
        BudgetFabricRepository $budgetfabric,
        GmtspartRepository $gmtspart, 
        AutoyarnRepository $autoyarn,
        ItemAccountRepository $itemaccount
        
    ) {
        $this->invrcv = $invrcv;
        $this->invfinishfabrcvfabric = $invfinishfabrcvfabric;
        $this->pofabric = $pofabric;
        $this->budgetfabric = $budgetfabric;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->itemaccount = $itemaccount;
        
        $this->middleware('auth');
        //$this->middleware('permission:view.invfinishfabrcvfabrics',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invfinishfabrcvfabrics', ['only' => ['store']]);
        //$this->middleware('permission:edit.invfinishfabrcvfabrics',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invfinishfabrcvfabrics', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

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
        $fabrics=$this->invrcv
        ->join('inv_finish_fab_rcvs',function($join){
        $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
        })
        ->join('inv_finish_fab_rcv_fabrics',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.inv_finish_fab_rcv_id', '=', 'inv_finish_fab_rcvs.id');
        })
        ->join('po_fabric_items',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.po_fabric_item_id', '=', 'po_fabric_items.id');
        })
        ->join('budget_fabrics',function($join){
          $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_gmts',function($join){
          $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
          $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('sales_orders',function($join){
          $join->on('inv_finish_fab_rcv_fabrics.sales_order_id','=','sales_orders.id');
        })
        ->join('colors',function($join){
          $join->on('inv_finish_fab_rcv_fabrics.fabric_color_id','=','colors.id');
        })
        ->join('gmtsparts',function($join){
          $join->on('style_fabrications.gmtspart_id','=','gmtsparts.id');
        })
        ->join('jobs',function($join){
          $join->on('sales_orders.job_id','=','jobs.id');
        })
        ->join('styles',function($join){
          $join->on('jobs.style_id','=','styles.id');
        })
        ->join('buyers',function($join){
          $join->on('styles.buyer_id','=','buyers.id');
        })
        ->leftJoin('colorranges',function($join){
          $join->on('inv_finish_fab_rcv_fabrics.colorrange_id','=','colorranges.id');
        })
        ->where([['inv_finish_fab_rcvs.id','=',request('inv_finish_fab_rcv_id',0)]])
        ->get([
            'inv_finish_fab_rcv_fabrics.*',
            'style_fabrications.autoyarn_id',
            'budget_fabrics.gsm_weight as req_gsm_weight',
            'gmtsparts.name as gmts_part_name',
            'colors.name as fabric_color',
            'sales_orders.sale_order_no',
            'buyers.name as buyer_name',
            'styles.style_ref',
            'colorranges.name as colorrange_name',
        ])
        ->map(function($fabrics) use($desDropdown){
            $fabrics->fabrication=$desDropdown[$fabrics->autoyarn_id];
            return $fabrics;
        });
        return response()->json($fabrics);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvFinishFabRcvPurFabricRequest $request) {
        $invfinishfabrcvfabric=$this->invfinishfabrcvfabric->create([
        'inv_finish_fab_rcv_id'=>$request->inv_finish_fab_rcv_id,
        'po_fabric_item_id'=>$request->po_fabric_item_id,
        'req_dia'=>$request->req_dia,
        'fabric_color_id'=>$request->fabric_color_id,
        'sales_order_id'=>$request->sales_order_id,
        'colorrange_id'=>$request->colorrange_id,
        'gsm_weight'=>$request->gsm_weight,
        'dia'=>$request->dia,
        'stitch_length'=>$request->stitch_length,
        'shrink_per'=>$request->shrink_per,
        'rate'=>$request->rate,
        'remarks'=>$request->remarks,
        ]);

        if($invfinishfabrcvfabric){
            return response()->json(array('success' =>true ,'id'=>$invfinishfabrcvfabric->id, 'inv_finish_fab_rcv_id'=>$request->inv_finish_fab_rcv_id,'message'=>'Saved Successfully'),200);
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
        $fabrics=$this->invfinishfabrcvfabric
        ->join('inv_finish_fab_rcvs',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.inv_finish_fab_rcv_id', '=', 'inv_finish_fab_rcvs.id');
        })
        ->join('inv_rcvs',function($join){
        $join->on('inv_finish_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
        })
        ->join('po_fabric_items',function($join){
        $join->on('inv_finish_fab_rcv_fabrics.po_fabric_item_id', '=', 'po_fabric_items.id');
        })
        ->join('budget_fabrics',function($join){
          $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_gmts',function($join){
          $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
          $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('sales_orders',function($join){
          $join->on('inv_finish_fab_rcv_fabrics.sales_order_id','=','sales_orders.id');
        })
        ->join('colors',function($join){
          $join->on('inv_finish_fab_rcv_fabrics.fabric_color_id','=','colors.id');
        })
        ->join('gmtsparts',function($join){
          $join->on('style_fabrications.gmtspart_id','=','gmtsparts.id');
        })
        ->join('jobs',function($join){
          $join->on('sales_orders.job_id','=','jobs.id');
        })
        ->join('styles',function($join){
          $join->on('jobs.style_id','=','styles.id');
        })
        ->join('buyers',function($join){
          $join->on('styles.buyer_id','=','buyers.id');
        })
        ->where([['inv_finish_fab_rcv_fabrics.id','=',$id]])
        ->get([
            'inv_finish_fab_rcv_fabrics.*',
            'style_fabrications.autoyarn_id',
            'budget_fabrics.gsm_weight as req_gsm_weight',
            'gmtsparts.name as gmts_part_name',
            'colors.name as fabric_color',
            'sales_orders.sale_order_no as sales_order_no',
            'buyers.name as buyer_name',
            'styles.style_ref',
        ])
        ->map(function($fabrics) use($desDropdown){
            $fabrics->fabrication=$fabrics->gmts_part_name." ".$desDropdown[$fabrics->autoyarn_id]." ".$fabrics->req_gsm_weight;
            return $fabrics;
        })
        ->first();
        $row ['fromData'] = $fabrics;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        return response()->json($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvFinishFabRcvPurFabricRequest $request, $id) {

        $rolls=$this->invfinishfabrcvfabric
        ->join('inv_finish_fab_rcv_items',function($join){
        $join->on('inv_finish_fab_rcv_items.inv_finish_fab_rcv_fabric_id', '=', 'inv_finish_fab_rcv_fabrics.id');
        })
        ->where([['inv_finish_fab_rcv_fabrics.id','=',$id]])
        ->get();
        if($rolls->first()){
          return response()->json(array('success' =>false , 'inv_finish_fab_rcv_id'=>$request->inv_finish_fab_rcv_id,'message'=>'Roll found, Update not possible'),200);
        }

        $invfinishfabrcvfabric=$this->invfinishfabrcvfabric->update($id,[
        //'inv_finish_fab_rcv_id'=>$request->inv_finish_fab_rcv_id,
        'po_fabric_item_id'=>$request->po_fabric_item_id,
        'req_dia'=>$request->req_dia,
        'fabric_color_id'=>$request->fabric_color_id,
        'sales_order_id'=>$request->sales_order_id,
        'colorrange_id'=>$request->colorrange_id,
        'gsm_weight'=>$request->gsm_weight,
        'dia'=>$request->dia,
        'stitch_length'=>$request->stitch_length,
        'shrink_per'=>$request->shrink_per,
        'rate'=>$request->rate,
        'remarks'=>$request->remarks,
        ]);

        if($invfinishfabrcvfabric){
            return response()->json(array('success' =>true ,'id'=>$id, 'inv_finish_fab_rcv_id'=>$request->inv_finish_fab_rcv_id,'message'=>'Saved Successfully'),200);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
       return response()->json(array('success'=>false,'message'=>'Deleted Not Successfully'),200);

        if($this->invfinishfabrcvfabric->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        } 
    }

    public function getFabric(){
      $id=request('po_fabric_id',0);

      $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
      $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
      $fabricDescription=$this->budgetfabric
      ->join('style_fabrications',function($join){
      $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
      })
      ->join('style_gmts',function($join){
      $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
      })
      ->join('item_accounts', function($join) {
      $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('budgets',function($join){
      $join->on('budgets.id','=','budget_fabrics.budget_id');
      })
      ->join('jobs',function($join){
      $join->on('jobs.id','=','budgets.job_id');
      })
      ->join('styles', function($join) {
      $join->on('styles.id', '=', 'jobs.style_id');
      })
      ->join('gmtsparts',function($join){
      $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
      })
      ->join('autoyarns',function($join){
      $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
      })
      ->join('autoyarnratios',function($join){
        $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
      })
      ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
      })
      ->join('constructions',function($join){
        $join->on('constructions.id','=','autoyarns.construction_id');
      })
      ->join('po_fabric_items',function($join){
      $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id')
      ->whereNull('po_fabric_items.deleted_at');
      })
      ->join('po_fabrics',function($join){
      $join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
      })
      ->where([['po_fabrics.id','=',$id]])
      ->get([
      'style_fabrications.id',
      'constructions.name as construction',
      'autoyarnratios.composition_id',
      'compositions.name',
      'autoyarnratios.ratio',
      ]);
      $fabricDescriptionArr=array();
      $fabricCompositionArr=array();
      foreach($fabricDescription as $row){
        $fabricDescriptionArr[$row->id]=$row->construction;
        $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
      }
      
      $desDropdown=array();
      foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
      }
    

      $fabrics=$this->pofabric
        ->selectRaw(
          'jobs.job_no,
          styles.id as style_id,
          styles.style_ref,
          styles.buyer_id,
          gmt_colors.name as color_name,
          gmt_colors.code as color_code,
          fabric_colors.name as fabric_color_name,
          fabric_colors.code as fabric_color_code,
          po_fabric_items.id as po_fabric_item_id,
          budget_fabrics.id as budget_fabric_id,
          budget_fabrics.budget_id,
          budget_fabrics.style_fabrication_id,
          budget_fabrics.gsm_weight,

          budget_fabric_cons.dia,
          budget_fabric_cons.cons,
          budget_fabric_cons.fabric_color,
          budget_fabric_cons.measurment,
          sales_orders.id as sales_order_id,
          sales_orders.sale_order_no, 

          style_fabrications.fabric_nature_id,
          style_fabrications.gmtspart_id,
          style_fabrications.autoyarn_id,
          style_fabrications.fabric_look_id,
          style_fabrications.material_source_id,
          style_fabrications.is_stripe,
          style_fabrications.fabric_shape_id,
          style_fabrications.uom_id,
          style_fabrications.is_narrow,
          gmtsparts.name as gmtspart_name,
          item_accounts.item_description,
          uoms.code as uom_code,
          buyers.code as buyer_code,
          
          sum(po_fabric_item_qties.qty) as qty,
          avg(po_fabric_item_qties.rate) as rate,
          sum(po_fabric_item_qties.amount) as amount'
        )
        ->join('po_fabric_items',function($join){
          $join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
        })
        ->join('po_fabric_item_qties',function($join){
          $join->on('po_fabric_item_qties.po_fabric_item_id','=','po_fabric_items.id');
        })
        ->join('budget_fabric_cons',function($join){
          $join->on('po_fabric_item_qties.budget_fabric_con_id','=','budget_fabric_cons.id'); 
        })
        ->join('budget_fabrics',function($join){
          $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');  
          $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('sales_order_gmt_color_sizes',function($join){
          $join->on('budget_fabric_cons.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        ->join('sales_order_countries',function($join){
          $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
        })
        ->join('sales_orders',function($join){
          $join->on('sales_order_countries.sale_order_id','=','sales_orders.id');
        })
        ->join('jobs',function($join){
          $join->on('sales_orders.job_id','=','jobs.id');
        })
        ->leftJoin('style_sizes',function($join){
          $join->on('style_sizes.id','=','sales_order_gmt_color_sizes.style_size_id');
        })
        ->leftJoin('sizes',function($join){
          $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('style_colors',function($join){
          $join->on('style_colors.id','=','sales_order_gmt_color_sizes.style_color_id');
        })
        ->join('colors as gmt_colors',function($join){
          $join->on('gmt_colors.id','=','style_colors.color_id');
        })
        ->join('colors as fabric_colors',function($join){
          $join->on('fabric_colors.id','=','budget_fabric_cons.fabric_color');
        })
        ->join('countries',function($join){
          $join->on('countries.id','=','sales_order_countries.country_id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_gmts',function($join){
          $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
          $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('budgets',function($join){
          $join->on('budgets.id','=','budget_fabrics.budget_id');
          $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('styles', function($join) {
          $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('buyers',function($join){
          $join->on('buyers.id','=','styles.buyer_id');
        })
        ->join('gmtsparts',function($join){
          $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->join('autoyarns',function($join){
          $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->join('uoms',function($join){
          $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->where([['po_fabrics.id','=',$id]])
        ->groupBy([
          'jobs.job_no',
          'styles.id',
          'styles.style_ref',
          'styles.buyer_id',
          'gmt_colors.name',
          'gmt_colors.code',
          'fabric_colors.name',
          'fabric_colors.code',
          'po_fabric_items.id',
          'budget_fabrics.id',
          'budget_fabrics.budget_id',
          'budget_fabrics.style_fabrication_id',
          'budget_fabrics.gsm_weight',

          'budget_fabric_cons.dia',
          'budget_fabric_cons.cons',
          'budget_fabric_cons.fabric_color',
          'budget_fabric_cons.measurment',
          'sales_orders.id',
          'sales_orders.sale_order_no', 

          'style_fabrications.fabric_nature_id',
          'style_fabrications.gmtspart_id',
          'style_fabrications.autoyarn_id',
          'style_fabrications.fabric_look_id',
          'style_fabrications.material_source_id',
          'style_fabrications.is_stripe',
          'style_fabrications.fabric_shape_id',
          'style_fabrications.uom_id',
          'style_fabrications.is_narrow',
          'gmtsparts.name',
          'item_accounts.item_description',
          'uoms.code',
          'buyers.code',
    
        ])
        ->get(['po_fabric_item_qties.*'])
        ->map(function($fabrics) use($desDropdown,$materialsourcing,$fabricnature,$fabriclooks,$fabricshape){
          $fabrics->style_fabrication_id =  $fabrics->style_fabrication_id;
          $fabrics->style_gmt = $fabrics->item_description;
          $fabrics->gmtspart =  $fabrics->gmtspart_name;
          $fabrics->fabric_description =  $desDropdown[$fabrics->style_fabrication_id];
          $fabrics->uom_name =  $fabrics->uom_name;
          $fabrics->materialsourcing =  $materialsourcing[$fabrics->material_source_id];
          $fabrics->fabricnature =  $fabricnature[$fabrics->fabric_nature_id];
          $fabrics->fabriclooks = $fabriclooks[$fabrics->fabric_look_id];
          $fabrics->fabricshape = $fabricshape[$fabrics->fabric_shape_id];
          return $fabrics;
        });
        return response()->json($fabrics);
    }
}