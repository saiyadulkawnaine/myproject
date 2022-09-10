<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomFabricRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingBomRequest;

class SoDyeingBomController extends Controller {

    private $sodyeing;
    private $sodyeingbom;
    private $company;
    private $buyer;
    private $uom;
    private $currency;
    private $accchartctrlhead;
    private $sodyeingbomfabric;
    private $autoyarn;
    private $gmtspart;
    private $colorrange;
    private $color;

    public function __construct(
        SoDyeingRepository $sodyeing,
        SoDyeingBomRepository $sodyeingbom, 
        CompanyRepository $company, 
        BuyerRepository $buyer,
        UomRepository $uom, 
        CurrencyRepository $currency,
        AccChartCtrlHeadRepository $accchartctrlhead,

        SoDyeingBomFabricRepository $sodyeingbomfabric,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        ColorrangeRepository $colorrange,
        ColorRepository $color
        ) {
        $this->sodyeing = $sodyeing;
        $this->sodyeingbom = $sodyeingbom;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->currency = $currency;
        $this->accchartctrlhead = $accchartctrlhead;
        $this->sodyeingbomfabric = $sodyeingbomfabric;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->middleware('auth');
        //$this->middleware('permission:view.sodyeingboms',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingboms', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingboms',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingboms', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       return response()->json(
          $this->sodyeingbom
          ->leftJoin('so_dyeings', function($join)  {
            $join->on('so_dyeings.id', '=', 'so_dyeing_boms.so_dyeing_id');
          })
          ->leftJoin('buyers', function($join)  {
            $join->on('so_dyeings.buyer_id', '=', 'buyers.id');
          })
          ->leftJoin('companies', function($join)  {
            $join->on('so_dyeings.company_id', '=', 'companies.id');
          })
          ->orderBy('so_dyeing_boms.id','desc')
          ->get([
            'so_dyeing_boms.*',
            'so_dyeings.sales_order_no',
            'buyers.name as buyer_id',
            'companies.name as company_id'
          ])
          ->map(function($rows){
            $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));
            return $rows;
          })
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $ctrlHead=array_prepend(array_pluck( $this->accchartctrlhead
        ->selectRaw(
        'acc_chart_ctrl_heads.root_id,
        reportHead.id,
        reportHead.name
        '
        )
        ->leftJoin(\DB::raw("(SELECT acc_chart_ctrl_heads.id,acc_chart_ctrl_heads.name FROM acc_chart_ctrl_heads  group by acc_chart_ctrl_heads.id,acc_chart_ctrl_heads.name) reportHead"), "reportHead.id", "=", "acc_chart_ctrl_heads.root_id")
        ->where([['acc_chart_ctrl_heads.statement_type_id','=',2]])
        ->whereNull('acc_chart_ctrl_heads.deleted_at')->orderBy('reportHead.name')->get(),'name','id'),'-Select-','');
        return Template::LoadView('Subcontract.Dyeing.SoDyeingBom',['company'=>$company,'buyer'=>$buyer,'uom'=>$uom,'currency'=>$currency,'ctrlHead'=>$ctrlHead]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoDyeingBomRequest $request) {
        /*$year=date('Y');
        $max=$this->sodyeingbom
        ->where([['year','=',$year]])
        ->max('receive_no');
        $receive_no=$max+1;
        $request->request->add(['year' => $year]);
        $request->request->add(['receive_no' => $receive_no]);*/
        $sodyeingbom=$this->sodyeingbom->create($request->except(['id','sales_order_no']));
        
        if($sodyeingbom){
          return response()->json(array('success' => true,'id' =>  $sodyeingbom->id,'message' => 'Save Successfully'),200);
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
        $sodyeingbom = $this->sodyeingbom
        ->leftJoin('so_dyeings', function($join)  {
            $join->on('so_dyeings.id', '=', 'so_dyeing_boms.so_dyeing_id');
          })
        ->leftJoin(\DB::raw("(select 
          so_dyeing_refs.so_dyeing_id,
          sum(so_dyeing_items.amount) as  amount,
          sum(po_dyeing_service_item_qties.amount)as amountpo
          from
          so_dyeing_refs
          left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
          left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
          left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id 
          where 
          so_dyeing_refs.deleted_at is null and
          so_dyeing_items.deleted_at is null and 
          po_dyeing_service_item_qties.deleted_at is null
          group by so_dyeing_refs.so_dyeing_id
          ) orderamount"), "orderamount.so_dyeing_id", "=", "so_dyeings.id")
        ->where([['so_dyeing_boms.id','=',$id]])

        ->get([
          'so_dyeing_boms.*',
          'so_dyeings.company_id',
          'so_dyeings.buyer_id',
          'so_dyeings.currency_id',
          'so_dyeings.sales_order_no',
          'orderamount.amount',
          'orderamount.amountpo'
        ])
        ->map(function($sodyeingbom){
            $sodyeingbom->order_val= $sodyeingbom->amount?$sodyeingbom->amount:$sodyeingbom->amountpo;
            if(!$sodyeingbom->order_val){
             $sodyeingbom->order_val=0; 
            }
            $sodyeingbom->costing_date=date('Y-m-d',strtotime($sodyeingbom->costing_date));
            return $sodyeingbom;

        })
        ->first();
        $row ['fromData'] = $sodyeingbom;
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
    public function update(SoDyeingBomRequest $request, $id) {
        $sodyeingbom=$this->sodyeingbom->update($id,$request->except(['id','sales_order_no','so_dyeing_id']));
        if($sodyeingbom){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->sodyeingbom->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    

    public function getSo()
    {
        return response()->json(
          $sodyeing=$this->sodyeing
          ->join('companies', function($join)  {
          $join->on('companies.id', '=', 'so_dyeings.company_id');
          })
          ->leftJoin('buyers', function($join)  {
          $join->on('so_dyeings.buyer_id', '=', 'buyers.id');
          })
          ->leftJoin('currencies', function($join)  {
          $join->on('so_dyeings.currency_id', '=', 'currencies.id');
          })
          ->leftJoin(\DB::raw("(select 
          so_dyeing_refs.so_dyeing_id,
          sum(so_dyeing_items.amount) as  amount,
          sum(po_dyeing_service_item_qties.amount)as amountpo
          from
          so_dyeing_refs
          left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
          left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
          left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id 
          where 
          so_dyeing_refs.deleted_at is null and
          so_dyeing_items.deleted_at is null and 
          po_dyeing_service_item_qties.deleted_at is null
          group by so_dyeing_refs.so_dyeing_id
          ) orderamount"), "orderamount.so_dyeing_id", "=", "so_dyeings.id")
          ->when(request('so_no'), function ($q) {
            return $q->where('sales_order_no', 'LIKE', "%".request('so_no', 0)."%");
          })
          //->where([['sales_order_no','=',request('so_no',0)]])
          ->get([
          'so_dyeings.*',
          'buyers.name as buyer_name',
          'companies.name as company_name',
          'orderamount.amount',
          'orderamount.amountpo',
          'currencies.code as currency_code'
          ])
          ->map(function($sodyeing){
            $sodyeing->order_val= $sodyeing->amount?$sodyeing->amount:$sodyeing->amountpo;
            if(!$sodyeing->order_val){
             $sodyeing->order_val=0; 
            }
            return $sodyeing;

          })
        );

    }

    public function getPdf()
    {
        $id=request('id',0);
        $master=$this->sodyeingbom
         ->join('so_dyeings',function($join){
          $join->on('so_dyeings.id','=','so_dyeing_boms.so_dyeing_id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','so_dyeings.company_id');
        })
        ->join('buyers',function($join){
          $join->on('buyers.id','=','so_dyeings.buyer_id');
        })
        ->join('users',function($join){
          $join->on('users.id','=','so_dyeing_boms.created_by');
        })
        ->join('currencies',function($join){
          $join->on('currencies.id','=','so_dyeings.currency_id');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->leftJoin('teams',function($join){
          $join->on('teams.id','=','buyers.team_id');
        })
        ->leftJoin(\DB::raw("(select 
          so_dyeing_refs.so_dyeing_id,
          sum(so_dyeing_items.amount) as  amount,
          sum(po_dyeing_service_item_qties.amount)as amountpo
          from
          so_dyeing_refs
          left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id=so_dyeing_refs.id
          left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id=so_dyeing_refs.id
          left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id=so_dyeing_po_items.po_dyeing_service_item_qty_id 
          where 
          so_dyeing_refs.deleted_at is null and
          so_dyeing_items.deleted_at is null and 
          po_dyeing_service_item_qties.deleted_at is null
          group by so_dyeing_refs.so_dyeing_id
          ) orderamount"), "orderamount.so_dyeing_id", "=", "so_dyeings.id")
        ->leftJoin(\DB::raw("( select
          users.name,
          teammembers.team_id
          from 
          teammembers
          left join users on users.id=teammembers.user_id
          where teammembers.type_id=2
          group by 
          users.name,
          teammembers.team_id
          ) teamleaders"), "teamleaders.team_id", "=", "teams.id")
        ->where([['so_dyeing_boms.id','=',$id]])
        ->get([
          'so_dyeing_boms.*',
          'so_dyeing_boms.remarks as master_remarks',
          'so_dyeings.sales_order_no',
          'companies.name as company_name',
          'companies.logo as logo',
          'companies.address as company_address',
          'buyers.name as buyer_name',
          'users.name as user_name',
          'employee_h_rs.contact',
          'currencies.code as currency_name',
          'currencies.hundreds_name',
          'orderamount.amount',
          'orderamount.amountpo',
          'teamleaders.name as team_leader_name'
        ])
        ->map(function($master){
            $master->order_val= $master->amount?$master->amount:$master->amountpo;
            if(!$master->order_val){
             $master->order_val=0; 
            }
            $master->costing_date=date('d-M-Y',strtotime($master->costing_date));
            return $master;
        })
        ->first();

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

        $rows=$this->sodyeingbom
        ->join('so_dyeing_bom_fabrics',function($join){
        $join->on('so_dyeing_bom_fabrics.so_dyeing_bom_id','=','so_dyeing_boms.id');
        })
        ->join('so_dyeing_refs',function($join){
        $join->on('so_dyeing_refs.id','=','so_dyeing_bom_fabrics.so_dyeing_ref_id');
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
        ->leftJoin('budget_fabric_prod_cons',function($join){
        $join->on('po_dyeing_service_item_qties.budget_fabric_prod_con_id','=','budget_fabric_prod_cons.id');
        })
        ->leftJoin('po_dyeing_service_items',function($join){
        $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
        ->whereNull('po_dyeing_service_items.deleted_at');
        })
        ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','budget_fabric_prod_cons.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        $join->on('budget_fabric_prod_cons.budget_fabric_prod_id','=','budget_fabric_prods.id');
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
        $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->leftJoin('uoms as so_uoms',function($join){
        $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
        })
        ->leftJoin('colors as so_color',function($join){
        $join->on('so_color.id','=','so_dyeing_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
        $join->on('po_color.id','=','budget_fabric_prod_cons.fabric_color_id');
        })
        ->where([['so_dyeing_boms.id','=',$id]])
        ->selectRaw('
          so_dyeing_bom_fabrics.id,
          so_dyeing_bom_fabrics.so_dyeing_bom_id,
          so_dyeing_bom_fabrics.liqure_ratio,
          so_dyeing_bom_fabrics.liqure_wgt,
          so_dyeing_refs.id as so_dyeing_ref_id,
          so_dyeing_refs.so_dyeing_id,
          constructions.name as construction_name,
          budget_fabric_prod_cons.fabric_color_id,
          style_fabrications.autoyarn_id,
          style_fabrications.fabric_look_id,
          style_fabrications.fabric_shape_id,
          style_fabrications.gmtspart_id,
          style_fabrications.dyeing_type_id,
          budget_fabrics.gsm_weight,
          po_dyeing_service_item_qties.budget_fabric_prod_con_id,
          po_dyeing_service_item_qties.colorrange_id,
          po_dyeing_service_item_qties.qty as fabric_wgt,
          po_dyeing_service_item_qties.pcs_qty,
          po_dyeing_service_item_qties.rate,
          po_dyeing_service_item_qties.amount,
          so_dyeing_items.autoyarn_id as c_autoyarn_id,
          so_dyeing_items.fabric_look_id as c_fabric_look_id,
          so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
          so_dyeing_items.gmtspart_id as c_gmtspart_id,
          so_dyeing_items.gsm_weight as c_gsm_weight,
          so_dyeing_items.fabric_color_id as c_fabric_color_id,
          so_dyeing_items.colorrange_id as c_colorrange_id,
          so_dyeing_items.qty as c_qty,
          so_dyeing_items.rate as c_rate,
          so_dyeing_items.amount as c_amount,
          so_dyeing_items.dyeing_type_id as c_dyeing_type_id,
          styles.style_ref,
          sales_orders.sale_order_no,
          so_dyeing_items.gmt_style_ref,
          so_dyeing_items.gmt_sale_order_no,
          buyers.name as buyer_name,
          gmt_buyer.name as gmt_buyer_name,
          uoms.code as uom_name,
          so_uoms.code as so_uom_name
          '
        )
        ->orderBy('so_dyeing_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$uom,$colorrange,$color,$dyetype,$fabricDescriptionArr){
            $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
            $rows->construction_name=$rows->autoyarn_id?$fabricDescriptionArr[$rows->autoyarn_id]:$fabricDescriptionArr[$rows->c_autoyarn_id];
            $rows->gmtspart=$rows->gmtspart_id?$gmtspart[$rows->gmtspart_id]:$gmtspart[$rows->c_gmtspart_id];
            $rows->fabriclooks=$rows->fabric_look_id?$fabriclooks[$rows->fabric_look_id]:$fabriclooks[$rows->c_fabric_look_id];
            $rows->fabricshape=$rows->fabric_shape_id?$fabricshape[$rows->fabric_shape_id]:$fabricshape[$rows->c_fabric_shape_id];
            $rows->uom_id=$rows->uom_id?$uom[$rows->uom_id]:'';
            $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
            $rows->fabric_color=$rows->fabric_color_id?$color[$rows->fabric_color_id]:$color[$rows->c_fabric_color_id];
            $rows->colorrange_id=$rows->colorrange_id?$colorrange[$rows->colorrange_id]:$colorrange[$rows->c_colorrange_id];
            $rows->qty=$rows->qty?$rows->qty:$rows->c_qty;
            $rows->pcs_qty=$rows->pcs_qty;
            $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
            $rows->order_val=$rows->amount?$rows->amount:$rows->c_amount;
            $rows->style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
            $rows->buyer_name=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
            $rows->sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
            $rows->uom_name=$rows->uom_name?$rows->uom_name:$rows->so_uom_name;
            $rows->dyeingtype=$rows->dyeing_type_id?$dyetype[$rows->dyeing_type_id]:$dyetype[$rows->c_dyeing_type_id];
            //$rows->qty=number_format($rows->qty,2,'.',',');
            //$rows->pcs_qty=number_format($rows->pcs_qty,0,'.',',');
            //$rows->order_val=number_format($rows->order_val,2,'.',','); 
            return $rows;
        });

        $identity=array_prepend(config('bprs.identity'), '-Select-','');
        $dyeChem = $this->sodyeingbom
        ->join('so_dyeing_bom_fabrics',function($join){
        $join->on('so_dyeing_bom_fabrics.so_dyeing_bom_id','=','so_dyeing_boms.id');
        })
        ->join('so_dyeing_bom_fabric_items',function($join){
        $join->on('so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id','=','so_dyeing_bom_fabrics.id');
        })
        ->join('item_accounts',function($join){
        $join->on('so_dyeing_bom_fabric_items.item_account_id','=','item_accounts.id');
        })
        ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->selectRaw('
        itemcategories.identity,
        sum(so_dyeing_bom_fabric_items.qty) as qty,
        sum(so_dyeing_bom_fabric_items.amount) as amount
        '
        )
        ->groupBy([
        'itemcategories.identity'
        ])

        ->where([['so_dyeing_boms.id','=',$id]])
       ->get()
        ->map(function($dyeChem) use($identity){
        $dyeChem->item_cat=$identity[$dyeChem->identity];
        return $dyeChem;
        });

        $heads=$this->sodyeingbom
        ->join('so_dyeing_bom_overheads', function($join){
        $join->on('so_dyeing_boms.id', '=', 'so_dyeing_bom_overheads.so_dyeing_bom_id');
        })
        ->join('acc_chart_ctrl_heads', function($join){
        $join->on('acc_chart_ctrl_heads.id', '=', 'so_dyeing_bom_overheads.acc_chart_ctrl_head_id');
        })
        ->where([['so_dyeing_boms.id','=',$id]])
        ->get([
        'so_dyeing_bom_overheads.*',
        'acc_chart_ctrl_heads.name as acc_head'
        ]);

        $dyes = $this->sodyeingbom
        ->join('so_dyeing_bom_fabrics',function($join){
        $join->on('so_dyeing_bom_fabrics.so_dyeing_bom_id','=','so_dyeing_boms.id');
        })
        ->join('so_dyeing_bom_fabric_items',function($join){
        $join->on('so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id','=','so_dyeing_bom_fabrics.id');
        })
        ->join('item_accounts',function($join){
        $join->on('so_dyeing_bom_fabric_items.item_account_id','=','item_accounts.id');
        })
        ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->where([['so_dyeing_boms.id','=',$id]])
        ->where([['itemcategories.identity','=',7]])
        ->orderBy('so_dyeing_bom_fabric_items.id')
        ->get([
        'so_dyeing_bom_fabric_items.*',
        'itemcategories.name as category_name',
        'itemclasses.name as class_name',
        'item_accounts.sub_class_name',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code as uom_name',
        'uoms.code as store_uom',
        ])
        ->map(function($dyes){
        return $dyes;
        });

        $chems = $this->sodyeingbom
        ->join('so_dyeing_bom_fabrics',function($join){
        $join->on('so_dyeing_bom_fabrics.so_dyeing_bom_id','=','so_dyeing_boms.id');
        })
        ->join('so_dyeing_bom_fabric_items',function($join){
        $join->on('so_dyeing_bom_fabric_items.so_dyeing_bom_fabric_id','=','so_dyeing_bom_fabrics.id');
        })
        ->join('item_accounts',function($join){
        $join->on('so_dyeing_bom_fabric_items.item_account_id','=','item_accounts.id');
        })
        ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->where([['so_dyeing_boms.id','=',$id]])
        ->where([['itemcategories.identity','=',8]])
        ->orderBy('so_dyeing_bom_fabric_items.id')
        ->get([
        'so_dyeing_bom_fabric_items.*',
        'itemcategories.name as category_name',
        'itemclasses.name as class_name',
        'item_accounts.sub_class_name',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code as uom_name',
        'uoms.code as store_uom',
        ])
        ->map(function($chems){
        return $chems;
        });

        $data['master']    =$master;
        $data['fabric']    =$rows;
        $data['dyechem']    =$dyeChem;
        $data['head']    =$heads;
        $data['dyes']    =$dyes;
        $data['chems']    =$chems;
        //$data['details']    =$rows;

        $barcodestyle = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => false,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0,0,0),
        'bgcolor' => false, //array(255,255,255),
        'text' => true,
        'font' => 'helvetica',
        'fontsize' => 8,
        'stretchtext' => 4
        );

        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
        $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
        $pdf->SetPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, '43', PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $header['logo']=$master->logo;
        $header['address']=$master->company_address;
        $header['title']='Dyeing Cost Sheet';
        $header['barcodestyle']= $barcodestyle;
        $header['barcodeno']= $challan;
        $pdf->setCustomHeader($header);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle('Dyeing Cost Sheet');
        $view= \View::make('Defult.Subcontract.Dyeing.SoDyeingBomPdf',['data'=>$data]);

        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/SoDyeingBomPdf.pdf';
        $pdf->output($filename);
    }
}