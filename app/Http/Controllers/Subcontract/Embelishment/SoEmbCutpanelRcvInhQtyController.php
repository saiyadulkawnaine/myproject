<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvOrderRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbCutpanelRcvInhQtyRequest;
use App\Repositories\Contracts\Util\BuyerRepository;

class SoEmbCutpanelRcvInhQtyController extends Controller {

    private $soembcutpanelrcvinhqty;
    private $prodgmtdlvinputorder;
    private $salesordergmtcolorsize;
    private $soemb;

    public function __construct(
        SoEmbCutpanelRcvQtyRepository $soembcutpanelrcvinhqty, 
        SoEmbCutpanelRcvOrderRepository $prodgmtdlvinputorder,
        SoEmbCutpanelRcvRepository $prodgmtdlvinput,
        SoEmbRepository $soemb,
        SalesOrderGmtColorSizeRepository $salesordergmtcolorsize
    ) {
        $this->soembcutpanelrcvinhqty = $soembcutpanelrcvinhqty;
        $this->prodgmtdlvinputorder = $prodgmtdlvinputorder;
        $this->prodgmtdlvinput = $prodgmtdlvinput;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->soemb = $soemb;

        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtsoembcutpanelrcvinhqtys',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtsoembcutpanelrcvinhqtys', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtsoembcutpanelrcvinhqtys',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtsoembcutpanelrcvinhqtys', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');

        $rows=$this->soembcutpanelrcvinhqty
        ->selectRaw('
            so_emb_cutpanel_rcv_qties.id,
            so_emb_cutpanel_rcv_qties.design_no,
            so_emb_cutpanel_rcv_qties.qty,
            embelishments.name as emb_name,
            embelishment_types.name as emb_type,
            gmtsparts.name as gmtspart,
            style_embelishments.embelishment_size_id,
            po_emb_services.delv_start_date as delivery_date,
            styles.style_ref,
            sales_orders.sale_order_no,
            buyers.name as buyer_name,
            item_accounts.item_description as item_desc,
            colors.name as gmt_color,
            sizes.name as gmt_size 
        ')
        ->join('so_emb_refs',function($join){
            $join->on('so_emb_cutpanel_rcv_qties.so_emb_ref_id','=','so_emb_refs.id');
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
        ->leftJoin('po_emb_services',function($join){
            $join->on('po_emb_services.id','=','po_emb_service_items.po_emb_service_id');
        })
        ->join('budget_embs',function($join){
            $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
        })
        ->join('style_embelishments',function($join){
            $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
        })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
        })
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','style_gmts.item_account_id');
        })
        ->join('gmtsparts',function($join){
            $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
        })
        ->join('embelishments',function($join){
            $join->on('embelishments.id','=','style_embelishments.embelishment_id');
        })
        ->join('embelishment_types',function($join){
            $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
        })
        ->join('budget_emb_cons',function($join){
            $join->on('budget_emb_cons.id','=','po_emb_service_item_qties.budget_emb_con_id')
            ->whereNull('budget_emb_cons.deleted_at');
        })
        ->join('sales_order_gmt_color_sizes',function($join){
            $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
        })
        ->join('sales_order_countries',function($join){
            $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
        })
        ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->join('styles',function($join){
            $join->on('jobs.style_id','=','styles.id');
        })
        ->join('style_gmt_color_sizes',function($join){
            $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->join('style_sizes',function($join){
            $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
            $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('style_colors',function($join){
            $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','style_colors.color_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
        ->where([['so_emb_cutpanel_rcv_qties.so_emb_cutpanel_rcv_order_id','=',request('so_emb_cutpanel_rcv_order_id',0)]])
        ->orderBy('so_emb_cutpanel_rcv_qties.id','desc')
        ->get()
        ->map(function($rows) use($embelishmentsize) {
            $rows->emb_size=$embelishmentsize[$rows->embelishment_size_id];
            $rows->uom_name='Pcs';
            $rows->qty=number_format($rows->qty,0,'.',',');
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoEmbCutpanelRcvInhQtyRequest $request) {
        $soembcutpanelrcvinhqty = $this->soembcutpanelrcvinhqty->create([
            'so_emb_ref_id' => $request->so_emb_ref_id,
            'so_emb_cutpanel_rcv_order_id' => $request->so_emb_cutpanel_rcv_order_id,
            'qty' => $request->qty,
            'design_no' => $request->design_no,
        ]);

        if($soembcutpanelrcvinhqty){
            return response()->json(array('success' => true,'id' =>  $soembcutpanelrcvinhqty->id,'message' => 'Save Successfully'),200);
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
        $soembcutpanelrcvinhqty = 
        $this->soembcutpanelrcvinhqty
        ->join('so_emb_refs',function($join){
            $join->on('so_emb_cutpanel_rcv_qties.so_emb_ref_id','=','so_emb_refs.id');
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
        ->leftJoin('po_emb_services',function($join){
            $join->on('po_emb_services.id','=','po_emb_service_items.po_emb_service_id');
        })
        ->join('budget_embs',function($join){
            $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
        })
        ->join('style_embelishments',function($join){
            $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
        })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
        })
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','style_gmts.item_account_id');
        })
        ->join('gmtsparts',function($join){
            $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
        })
        ->join('embelishments',function($join){
            $join->on('embelishments.id','=','style_embelishments.embelishment_id');
        })
        ->join('embelishment_types',function($join){
            $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
        })
        ->join('budget_emb_cons',function($join){
            $join->on('budget_emb_cons.id','=','po_emb_service_item_qties.budget_emb_con_id')
            ->whereNull('budget_emb_cons.deleted_at');
        })
        ->join('sales_order_gmt_color_sizes',function($join){
            $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
        })
        ->join('sales_order_countries',function($join){
            $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
        })
        ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->join('styles',function($join){
            $join->on('jobs.style_id','=','styles.id');
        })
        ->join('style_gmt_color_sizes',function($join){
            $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->join('style_sizes',function($join){
            $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
            $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('style_colors',function($join){
            $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','style_colors.color_id');
        })
        ->where([['so_emb_cutpanel_rcv_qties.id','=',$id]])
        ->selectRaw('
            so_emb_cutpanel_rcv_qties.id,
            so_emb_cutpanel_rcv_qties.so_emb_ref_id,
            so_emb_cutpanel_rcv_qties.so_emb_cutpanel_rcv_order_id,
            so_emb_cutpanel_rcv_qties.design_no,
            so_emb_cutpanel_rcv_qties.qty,
            embelishments.name as emb_name,
            embelishment_types.name as emb_type,
            gmtsparts.name as gmtspart,
            style_embelishments.embelishment_size_id,
            po_emb_services.delv_start_date as delivery_date,
            styles.style_ref,
            sales_orders.sale_order_no,
            item_accounts.item_description as item_desc,
            colors.name as gmt_color,
            sizes.name as gmt_size 
        ')
        ->get()
        ->first();

        $row ['fromData'] = $soembcutpanelrcvinhqty;
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
    public function update(SoEmbCutpanelRcvInhQtyRequest $request, $id) {
        $soembcutpanelrcvinhqty=$this->soembcutpanelrcvinhqty->update($id,
            [
                'so_emb_ref_id' => $request->so_emb_ref_id,
                'so_emb_cutpanel_rcv_order_id' => $request->so_emb_cutpanel_rcv_order_id,
                'qty' => $request->qty,
                'design_no' => $request->design_no,
            ]
        );
        if($soembcutpanelrcvinhqty){
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
        if($this->soembcutpanelrcvinhqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getSoEmbItemRef(){
        //dd(request('soembid',0));die;
        $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
        $rows=$this->soemb
        ->join('so_emb_refs',function($join){
            $join->on('so_emb_refs.so_emb_id','=','so_embs.id');
        })
        ->join('so_emb_pos',function($join){
            $join->on('so_emb_pos.so_emb_id','=','so_embs.id');
        })
        ->join('so_emb_po_items',function($join){
            $join->on('so_emb_po_items.so_emb_ref_id','=','so_emb_refs.id');
        })
        ->join('po_emb_service_item_qties',function($join){
            $join->on('po_emb_service_item_qties.id','=','so_emb_po_items.po_emb_service_item_qty_id');
        })
        ->join('po_emb_service_items',function($join){
            $join->on('po_emb_service_items.id','=','po_emb_service_item_qties.po_emb_service_item_id')
            ->whereNull('po_emb_service_items.deleted_at');
        })
        ->join('po_emb_services',function($join){
            $join->on('po_emb_services.id','=','po_emb_service_items.po_emb_service_id');
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
        ->leftJoin('item_accounts',function($join){
            $join->on('item_accounts.id','=','style_gmts.item_account_id');
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
        ->leftJoin('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
            $join->on('jobs.style_id','=','styles.id');
        })
        ->leftJoin('style_gmt_color_sizes',function($join){
            $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
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
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
        ->where([['so_embs.id','=',request('so_emb_id',0)]])
        ->selectRaw('
            so_emb_refs.id,
            so_emb_refs.so_emb_id,
            embelishments.name as emb_name,
            embelishment_types.name as emb_type,
            gmtsparts.name as gmtspart,
            style_embelishments.embelishment_size_id,
            po_emb_services.delv_start_date as delivery_date,
            po_emb_service_item_qties.qty,
            po_emb_service_item_qties.rate,
            po_emb_service_item_qties.amount,
            styles.style_ref,
            sales_orders.sale_order_no,
            buyers.name as buyer_name,
            item_accounts.item_description as item_desc,
            colors.name as gmt_color,
            sizes.name as gmt_size 
        ')
        ->orderBy('so_emb_po_items.id','desc')
        ->get()
        ->map(function($rows) use($embelishmentsize) {
            $rows->emb_size=$embelishmentsize[$rows->embelishment_size_id];
            $rows->uom_name='Pcs';
            $rows->qty=number_format($rows->qty,0,'.',',');
            $rows->rate=number_format($rows->rate,4,'.',',').' / Dzn';
            $rows->amount=number_format($rows->amount,2,'.',',');
            return $rows;
        });

        echo json_encode($rows);
    }

}