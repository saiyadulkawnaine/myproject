<?php

namespace App\Http\Controllers\Commercial\LocalExport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpInvoiceOrderRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpInvoiceRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpInvoiceOrderRequest;

class LocalExpInvoiceOrderController extends Controller {

    private $localexpinvoice;
    private $localexpinvoiceorder;
    private $location;
    private $itemaccount;
    private $soknit;
    private $sodyeing;
    private $soaop;
    private $soemb;
    private $embelishment;
    private $size;

    public function __construct(
        LocalExpInvoiceOrderRepository $localexpinvoiceorder, 
        LocalExpInvoiceRepository $localexpinvoice,
        LocationRepository $location,
        LocalExpLcRepository $localexplc,
        SoAopRepository $soaop,
        ItemAccountRepository $itemaccount, 
        SoKnitRepository $soknit,
        SoDyeingRepository $sodyeing,
        GmtspartRepository $gmtspart,
        AutoyarnRepository $autoyarn,
        ColorrangeRepository $colorrange,
        EmbelishmentTypeRepository $embelishmenttype,
        SoEmbRepository $soemb,
        EmbelishmentRepository $embelishment,
        ColorRepository $color,
        SizeRepository $size
    ){
        
        $this->localexpinvoice = $localexpinvoice;
        $this->localexpinvoiceorder = $localexpinvoiceorder;
        $this->location = $location;
        $this->localexplc = $localexplc;
        $this->soaop = $soaop;
        $this->itemaccount = $itemaccount;
        $this->soknit = $soknit;
        $this->sodyeing = $sodyeing;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->size = $size;
        $this->embelishmenttype = $embelishmenttype;
        $this->soemb = $soemb;
        $this->embelishment = $embelishment;

        $this->middleware('auth');

        // $this->middleware('permission:view.localexpinvoiceorders',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.localexpinvoiceorders', ['only' => ['store']]);
        // $this->middleware('permission:edit.localexpinvoiceorders',   ['only' => ['update']]);
        // $this->middleware('permission:delete.localexpinvoiceorders', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $localexpinvoiceorders=array();
        $rows=$this->localexpinvoiceorder
        ->where([['local_exp_invoice_id','=',request('local_exp_invoice_id',0)]])
        ->get();
        foreach($rows as $row){
            $localexpinvoiceorder['id']=$row->id;
            $localexpinvoiceorder['acceptance_value']=$row->acceptance_value;
            $localexpinvoiceorder['local_exp_invoice_id']=$row->local_exp_invoice_id;
            array_push($localexpinvoiceorders,$localexpinvoiceorder);
        }
        echo json_encode($localexpinvoiceorders);
    }

    /**
     * Show the form for creating a new resource.
     *d
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $localexpinvoice=$this->localexpinvoice->find(request('local_exp_invoice_id',0));
        $localexplc=$this->localexplc->find($localexpinvoice->local_exp_lc_id);
        $production_area_id=$localexplc->production_area_id;
         //Yarn Dyeing Sales Order
        if($production_area_id==5){
            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
            }) 
            ->leftJoin(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',request('local_exp_invoice_id',0)]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept){
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->pi_rate=0;
                if($impdocaccept->pi_qty){
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                }
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });

            $saved = $impdocaccept->filter(function ($value) {
                if($value->local_exp_invoice_order_id){
                    return $value;
                }
            });
            $new = $impdocaccept->filter(function ($value) {
                if(!$value->local_exp_invoice_order_id){
                    return $value;
                }
            });
            return Template::LoadView('Commercial.LocalExport.LocalExpInvoiceOrder',['impdocaccepts'=>$new,'saved'=>$saved]);
        }
        //Knitting Sales Order
        elseif ($production_area_id==10) {
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

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,

                so_knit_refs.id as sales_order_ref_id,
                so_knit_refs.so_knit_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_knit_service_item_qties.dia,
                po_knit_service_item_qties.measurment,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                so_knit_items.fabric_look_id as c_fabric_look_id,
                so_knit_items.fabric_shape_id as c_fabric_shape_id,
                so_knit_items.gmtspart_id as c_gmtspart_id,
                so_knit_items.gsm_weight as c_gsm_weight,
                so_knit_items.dia as c_dia,
                so_knit_items.measurment as c_measurment,
                so_color.name as c_fabric_color_name,
                po_color.name as fabric_color_name,
                sales_orders.sale_order_no,

                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
            })
            ->join('so_knit_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_knit_refs.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_knits',function($join){
                $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_pos',function($join){
                $join->on('so_knit_pos.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_po_items',function($join){
                $join->on('so_knit_po_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('po_knit_service_item_qties',function($join){
                $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                ->whereNull('po_knit_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
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
            ->leftJoin('so_knit_items',function($join){
                $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            // ->leftJoin('buyers',function($join){
            //     $join->on('buyers.id','=','styles.buyer_id');
            // })
            // ->leftJoin('buyers as gmt_buyer',function($join){
            //     $join->on('gmt_buyer.id','=','so_knit_items.gmt_buyer');
            // })
            // ->leftJoin('uoms',function($join){
            //     $join->on('uoms.id','=','style_fabrications.uom_id');
            // })
            // ->leftJoin('uoms as so_uoms',function($join){
            //     $join->on('so_uoms.id','=','so_knit_items.uom_id');
            // })
            ->leftJoin('colors as so_color',function($join){
                $join->on('so_color.id','=','so_knit_items.fabric_color_id');
            })
            ->leftJoin('colors as po_color',function($join){
                $join->on('po_color.id','=','po_knit_service_item_qties.fabric_color_id');
            })

            ->leftJoin(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',request('local_exp_invoice_id',0)]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',

                'so_knit_refs.id',
                'so_knit_refs.so_knit_id',
                'constructions.name',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_knit_service_item_qties.dia',
                'po_knit_service_item_qties.measurment',
                'so_knit_items.gmt_sale_order_no',
                'so_knit_items.autoyarn_id',
                'so_knit_items.fabric_look_id',
                'so_knit_items.fabric_shape_id',
                'so_knit_items.gmtspart_id',
                'so_knit_items.gsm_weight',
                'so_knit_items.dia',
                'so_knit_items.measurment',
                'so_color.name',
                'po_color.name',
                'sales_orders.sale_order_no',


                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$fabriclooks,$fabricshape,$desDropdown){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->dia=$impdocaccept->dia?$impdocaccept->dia:$impdocaccept->c_dia;
                $impdocaccept->measurment=$impdocaccept->measurment?$impdocaccept->measurment:$impdocaccept->c_measurment;
                $impdocaccept->fabric_color=$impdocaccept->fabric_color_name?$impdocaccept->fabric_color_name:$impdocaccept->c_fabric_color_name;

                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color.','.$impdocaccept->dia.','.$impdocaccept->measurment;

                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                //$impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                $impdocaccept->pi_rate=0;
                if($impdocaccept->pi_qty){
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                }
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });

            $saved = $impdocaccept->filter(function ($value) {
                if($value->local_exp_invoice_order_id){
                    return $value;
                }
            });
            $new = $impdocaccept->filter(function ($value) {
                if(!$value->local_exp_invoice_order_id){
                    return $value;
                }
            });
            return Template::LoadView('Commercial.LocalExport.LocalExpInvoiceOrder',['impdocaccepts'=>$new,'saved'=>$saved,'']);
        }
        //Dyeing Sales Order
        elseif ($production_area_id==20) {
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
            $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
            $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,

                so_dyeing_refs.id as so_dyeing_ref_id,
                so_dyeing_refs.so_dyeing_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_dyeing_service_item_qties.fabric_color_id,
                po_dyeing_service_item_qties.colorrange_id,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.gmtspart_id as c_gmtspart_id,
                so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.colorrange_id as c_colorrange_id,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_sale_order_no,

                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
            })

            ->join('so_dyeing_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_dyeing_refs.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
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
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
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
            ->leftJoin(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',request('local_exp_invoice_id',0)]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',

                'so_dyeing_refs.id',
                'so_dyeing_refs.so_dyeing_id',
                'constructions.name',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_dyeing_service_item_qties.fabric_color_id',
                'po_dyeing_service_item_qties.colorrange_id',
                'so_dyeing_items.autoyarn_id',
                'so_dyeing_items.fabric_look_id',
                'so_dyeing_items.fabric_shape_id',
                'so_dyeing_items.gmtspart_id',
                'so_dyeing_items.gsm_weight',
                'so_dyeing_items.fabric_color_id',
                'so_dyeing_items.colorrange_id',
                'sales_orders.sale_order_no',
                'so_dyeing_items.gmt_sale_order_no',

                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$dyetype){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->fabric_color_name=$impdocaccept->fabric_color_id?$color[$impdocaccept->fabric_color_id]:$color[$impdocaccept->c_fabric_color_id];
                $impdocaccept->colorrange_id=$impdocaccept->colorrange_id?$colorrange[$impdocaccept->colorrange_id]:$colorrange[$impdocaccept->c_colorrange_id];
                $impdocaccept->dyeing_type=$impdocaccept->dyeing_type_id?$dyetype[$impdocaccept->dyeing_type_id]:$dyetype[$impdocaccept->c_dyeing_type_id];
                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color_name.','.$impdocaccept->colorrange_id.','.$impdocaccept->dyeing_type;

                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));

                //$impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                $impdocaccept->pi_rate=0;
                if($impdocaccept->pi_qty){
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                }
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });

            $saved = $impdocaccept->filter(function ($value) {
                if($value->local_exp_invoice_order_id){
                    return $value;
                }
            });
            $new = $impdocaccept->filter(function ($value) {
                if(!$value->local_exp_invoice_order_id){
                    return $value;
                }
            });
            return Template::LoadView('Commercial.LocalExport.LocalExpInvoiceOrder',['impdocaccepts'=>$new,'saved'=>$saved]);
        }
        //AOP Sales Order
        elseif ($production_area_id==25) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
            $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
            $autoyarn=$this->autoyarn
            ->leftJoin('autoyarnratios', function($join)  {
                $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('compositions',function($join){
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

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,

                so_aop_refs.id as so_aop_ref_id,
                so_aop_refs.so_aop_id,

                constructions.name as constructions_name,
                po_aop_service_item_qties.fabric_color_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_aop_service_item_qties.colorrange_id,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                so_aop_items.fabric_look_id as c_fabric_look_id,
                so_aop_items.fabric_shape_id as c_fabric_shape_id,
                so_aop_items.gmtspart_id as c_gmtspart_id,
                so_aop_items.gsm_weight as c_gsm_weight,
                so_aop_items.fabric_color_id as c_fabric_color_id,
                so_aop_items.colorrange_id as c_colorrange_id,
                po_aop_service_item_qties.embelishment_type_id,
                po_aop_service_item_qties.coverage,
                po_aop_service_item_qties.impression,
                so_aop_items.embelishment_type_id as c_embelishment_type_id,
                so_aop_items.coverage as c_coverage,
                so_aop_items.impression as c_impression,
                sales_orders.sale_order_no,
                so_aop_items.gmt_sale_order_no,
                local_exp_pi_orders.sales_order_ref_id,

                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
            })

            ->join('so_aop_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_aop_refs.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_aops',function($join){
                $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
                $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_po_items',function($join){
                $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('po_aop_service_item_qties',function($join){
                $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->leftJoin('po_aop_service_items',function($join){
                $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_aop_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
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
            ->leftJoin('so_aop_items',function($join){
                $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            // ->leftJoin('buyers',function($join){
            //     $join->on('buyers.id','=','styles.buyer_id');
            // })
            // ->leftJoin('buyers as gmt_buyer',function($join){
            //     $join->on('gmt_buyer.id','=','so_aop_items.gmt_buyer');
            // })
            // ->leftJoin('uoms',function($join){
            //     $join->on('uoms.id','=','style_fabrications.uom_id');
            // })
            // ->leftJoin('uoms as so_uoms',function($join){
            //     $join->on('so_uoms.id','=','so_aop_items.uom_id');
            // })

            ->leftJoin(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',request('local_exp_invoice_id',0)]])
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',

                'so_aop_refs.id',
                'so_aop_refs.so_aop_id',

                'constructions.name',
                'po_aop_service_item_qties.fabric_color_id',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_aop_service_item_qties.colorrange_id',
                'so_aop_items.autoyarn_id',
                'so_aop_items.fabric_look_id',
                'so_aop_items.fabric_shape_id',
                'so_aop_items.gmtspart_id',
                'so_aop_items.gsm_weight',
                'so_aop_items.fabric_color_id',
                'so_aop_items.colorrange_id',
                'po_aop_service_item_qties.embelishment_type_id',
                'po_aop_service_item_qties.coverage',
                'po_aop_service_item_qties.impression',
                'so_aop_items.embelishment_type_id',
                'so_aop_items.coverage',
                'so_aop_items.impression',
                'sales_orders.sale_order_no',
                'so_aop_items.gmt_sale_order_no',
                'local_exp_pi_orders.sales_order_ref_id',

                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept) use($color,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$aoptype,$desDropdown){

                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->fabric_color_name=$impdocaccept->fabric_color_id?$color[$impdocaccept->fabric_color_id]:$color[$impdocaccept->c_fabric_color_id];
                $impdocaccept->colorrange_id=$impdocaccept->colorrange_id?$colorrange[$impdocaccept->colorrange_id]:$colorrange[$impdocaccept->c_colorrange_id];
                $impdocaccept->embelishment_type_id=$impdocaccept->embelishment_type_id?$aoptype[$impdocaccept->embelishment_type_id]:$aoptype[$impdocaccept->c_embelishment_type_id];
                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color_name.','.$impdocaccept->colorrange_id.','.$impdocaccept->embelishment_type_id;

                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                //$impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                $impdocaccept->pi_rate=0;
                if($impdocaccept->pi_qty){
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                }
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });

            $saved = $impdocaccept->filter(function ($value) {
                if($value->local_exp_invoice_order_id){
                    return $value;
                }
            });
            $new = $impdocaccept->filter(function ($value) {
                if(!$value->local_exp_invoice_order_id){
                    return $value;
                }
            });


           
            return Template::LoadView('Commercial.LocalExport.LocalExpInvoiceOrder',['impdocaccepts'=>$new,'saved'=>$saved,'color'=>$color /* ,'autoyarn'=>$autoyarn,'gmtspart'=>$gmtspart,'fabriclooks'=>$fabriclooks,'fabricshape'=>$fabricshape,'colorrange'=>$colorrange,'color'=>$color,'aoptype'=>$aoptype ,'desDropdown'=>$desDropdown */]);
        }
        //Embelishment Work Order
        elseif ($production_area_id==45 ||$production_area_id==50 || $production_area_id==51) {
            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
            $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'','');
            $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $size=array_prepend(array_pluck($this->size->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,

                so_emb_refs.id as so_emb_ref_id,
                so_emb_refs.so_emb_id,
                so_embs.sales_order_no,
                gmtsparts.id as gmtspart_id,
                so_emb_items.gmtspart_id as c_gmtspart_id,
                style_embelishments.embelishment_size_id,
                style_embelishments.embelishment_type_id,
                style_embelishments.embelishment_id,
                so_emb_items.embelishment_id as c_embelishment_id,
                so_emb_items.embelishment_type_id as c_embelishment_type_id,
                so_emb_items.embelishment_size_id as c_embelishment_size_id,
                so_emb_items.color_id as c_color_id,
                so_emb_items.size_id as c_size_id,
                sales_orders.sale_order_no,
                so_emb_items.gmt_sale_order_no,
                item_accounts.item_description,
                colors.name as gmt_color,
                sizes.name as gmt_size,

                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
            })
            ->leftJoin('so_emb_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_emb_refs.id');
                //$join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->leftJoin('so_embs',function($join){
                $join->on('so_emb_refs.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_pos',function($join){
                $join->on('so_emb_pos.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_po_items',function($join){
                $join->on('so_emb_po_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('po_emb_service_item_qties',function($join){
                $join->on('po_emb_service_item_qties.id','=','so_emb_po_items.po_emb_service_item_qty_id');
            })
            ->leftJoin('po_emb_service_items',function($join){
                $join->on('po_emb_service_items.id','=','po_emb_service_item_qties.po_emb_service_item_id')
                ->whereNull('po_emb_service_items.deleted_at');
            })
            ->leftJoin('budget_embs',function($join){
                $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
            })
            ->leftJoin('style_embelishments',function($join){
                $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            })
            ->leftJoin('style_gmts',function($join){
                $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
            })
            ->leftJoin('item_accounts', function($join) {
                $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
            })
            ->leftJoin('gmtsparts',function($join){
                $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
            })
            ->leftJoin('embelishments',function($join){
                $join->on('embelishments.id','=','style_embelishments.embelishment_id');
            })
            ->leftJoin('embelishment_types',function($join){
                $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
            })
            ->leftJoin('budget_emb_cons',function($join){
                $join->on('budget_emb_cons.id','=','po_emb_service_item_qties.budget_emb_con_id')
                ->whereNull('budget_emb_cons.deleted_at');
            })
            ->leftJoin('sales_order_gmt_color_sizes',function($join){
                $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
            })
            ->leftJoin('sales_order_countries',function($join){
                $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
            })
            ->leftJoin('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })
            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('so_emb_items',function($join){
                $join->on('so_emb_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',request('local_exp_invoice_id',0)]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',

                'so_emb_refs.id',
                'so_emb_refs.so_emb_id',
                'so_embs.sales_order_no',
                'gmtsparts.id',
                'so_emb_items.gmtspart_id',
                'style_embelishments.embelishment_size_id',
                'style_embelishments.embelishment_type_id',
                'style_embelishments.embelishment_id',
                'so_emb_items.embelishment_id',
                'so_emb_items.embelishment_type_id',
                'so_emb_items.embelishment_size_id',
                'so_emb_items.color_id',
                'so_emb_items.size_id',
                'sales_orders.sale_order_no',
                'so_emb_items.gmt_sale_order_no',
                'item_accounts.item_description',
                'colors.name',
                'sizes.name',

                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$embelishmentsize,$embelishmenttype,$embelishment,$color,$size){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->emb_size=$impdocaccept->embelishment_size_id?$embelishmentsize[$impdocaccept->embelishment_size_id]:$embelishmentsize[$impdocaccept->c_embelishment_size_id];
                $impdocaccept->emb_name=$impdocaccept->embelishment_id?$embelishment[$impdocaccept->embelishment_id]:$embelishment[$impdocaccept->c_embelishment_id];
                $impdocaccept->gmt_color=$impdocaccept->gmt_color?$impdocaccept->gmt_color:$color[$impdocaccept->c_color_id];
                $impdocaccept->gmt_size=$impdocaccept->gmt_size?$impdocaccept->gmt_size:$size[$impdocaccept->c_size_id];
                $impdocaccept->item_description=$impdocaccept->item_description.','.$impdocaccept->emb_name.','.$impdocaccept->emb_size.','.$impdocaccept->gmtspart.','.$impdocaccept->gmt_color.','.$impdocaccept->gmt_size;
                $impdocaccept->dye_aop_type=$impdocaccept->embelishment_type_id?$embelishmenttype[$impdocaccept->embelishment_type_id]:$embelishmenttype[$impdocaccept->c_embelishment_type_id];

                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                //$impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                $impdocaccept->pi_rate=0;
                if($impdocaccept->pi_qty){
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                }
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });

            $saved = $impdocaccept->filter(function ($value) {
                if($value->local_exp_invoice_order_id){
                    return $value;
                }
            });
            $new = $impdocaccept->filter(function ($value) {
                if(!$value->local_exp_invoice_order_id){
                    return $value;
                }
            });
            return Template::LoadView('Commercial.LocalExport.LocalExpInvoiceOrder',['impdocaccepts'=>$new,'saved'=>$saved]);
        }
        
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocalExpInvoiceOrderRequest $request) {
       
        $impDocAcceptId=0;
        $amount=0;
        foreach($request->local_exp_pi_order_id as $index=>$local_exp_pi_order_id){
            $expInvoiceId=$request->local_exp_invoice_id[$index];
            if($local_exp_pi_order_id && $request->qty[$index])
            {
                $localexpinvoiceorder = $this->localexpinvoiceorder->updateOrCreate(
                [
                    'local_exp_pi_order_id' => $local_exp_pi_order_id,
                    'local_exp_invoice_id' => $request->local_exp_invoice_id[$index]
                ],[
                    'qty' => $request->qty[$index],
                    'rate' => $request->rate[$index],
                    'amount' => $request->amount[$index]
                ]);
                $amount += $request->amount[$index];
            }
        }
        $tamount=$amount;
        $this->localexpinvoice->where([['id','=',$localexpinvoiceorder->local_exp_invoice_id]])->update(['local_invoice_value'=>$tamount]);
        if($localexpinvoiceorder){
            return response()->json(array('success' => true,'id' =>  $localexpinvoiceorder->id,'local_exp_invoice_id' =>  $expInvoiceId,'message' => 'Save Successfully'),200);
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LocalExpInvoiceOrderRequest $request, $id) {
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $localexpinvoiceorder=$this->localexpinvoiceorder->find($id);
        $invoice=$this->localexpinvoice->find($localexpinvoiceorder->local_exp_invoice_id);
        if($this->localexpinvoiceorder->delete($id)){
            $this->localexpinvoice->where([['id','=',$localexpinvoiceorder->local_exp_invoice_id]])->update(['local_invoice_value'=>$invoice->local_invoice_value-$localexpinvoiceorder->amount]);
            return response()->json(array('success' => true, 'local_exp_invoice_id' =>  $localexpinvoiceorder->local_exp_invoice_id,'message' => 'Delete Successfully'),200);
        }
        else{
            return response()->json(array('success' => false, 'local_exp_invoice_id' =>   $localexpinvoiceorder->local_exp_invoice_id,  'message' => 'Delete Not Successfull Because Subsequent Entry Found'),  200);
        }
    }
}
