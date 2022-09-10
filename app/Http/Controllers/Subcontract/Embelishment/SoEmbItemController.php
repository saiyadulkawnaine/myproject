<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRefRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbItemRequest;

class SoEmbItemController extends Controller
{
	private $soemb;
	private $poembref;
	private $soembitem;
	private $autoyarn;
	private $gmtspart;
	private $uom;
	private $color;
	private $size;
	private $embelishment;
	private $embelishmenttype;


	public function __construct(
		SoEmbRepository $soemb,
		SoEmbRefRepository $poembref,
		SoEmbItemRepository $soembitem,
		AutoyarnRepository $autoyarn,
		GmtspartRepository $gmtspart,
		UomRepository $uom,
		ColorRepository $color,
		SizeRepository $size,
		EmbelishmentRepository $embelishment,
		EmbelishmentTypeRepository $embelishmenttype
	) {
		$this->soemb = $soemb;
		$this->poembref = $poembref;
		$this->soembitem = $soembitem;
		$this->autoyarn = $autoyarn;
		$this->gmtspart = $gmtspart;
		$this->uom = $uom;
		$this->color = $color;
		$this->size = $size;
		$this->embelishment = $embelishment;
		$this->embelishmenttype = $embelishmenttype;
		$this->middleware('auth');
		$this->middleware('permission:view.soembitems',   ['only' => ['create', 'index', 'show']]);
		$this->middleware('permission:create.soembitems', ['only' => ['store']]);
		$this->middleware('permission:edit.soembitems',   ['only' => ['update']]);
		$this->middleware('permission:delete.soembitems', ['only' => ['destroy']]);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		$embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');

		$row_item = $this->soemb
			->join('so_emb_refs', function ($join) {
				$join->on('so_emb_refs.so_emb_id', '=', 'so_embs.id');
			})
			->join('so_emb_items', function ($join) {
				$join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'so_emb_items.gmtspart_id');
			})
			->join('embelishments', function ($join) {
				$join->on('embelishments.id', '=', 'so_emb_items.embelishment_id');
			})
			->join('embelishment_types', function ($join) {
				$join->on('embelishment_types.id', '=', 'so_emb_items.embelishment_type_id');
			})
			->leftJoin('buyers', function ($join) {
				$join->on('buyers.id', '=', 'so_emb_items.gmt_buyer');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'so_emb_items.uom_id');
			})
			->leftJoin('colors', function ($join) {
				$join->on('colors.id', '=', 'so_emb_items.color_id');
			})
			->leftJoin('sizes', function ($join) {
				$join->on('sizes.id', '=', 'so_emb_items.size_id');
			})
			->leftJoin('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'so_emb_items.item_account_id');
			})
			->where([['so_embs.id', '=', request('so_emb_id', 0)]])
			->selectRaw(
				'
        so_emb_refs.id,
        so_emb_refs.so_emb_id,
        embelishments.name as emb_name,
        embelishment_types.name as emb_type,
        so_emb_items.gmtspart_id,
        gmtsparts.name as gmtspart,
        so_emb_items.embelishment_size_id,
        so_emb_items.qty,
        so_emb_items.rate,
        so_emb_items.amount,
        so_emb_items.gmt_style_ref as style_ref,
        so_emb_items.gmt_sale_order_no as sale_order_no,
        so_emb_items.delivery_date,
        buyers.name as buyer_name,
        uoms.code as uom_name,
        colors.name as gmt_color,
        sizes.name as gmt_size,
        item_accounts.item_description as item_desc
        '
			)
			->orderBy('so_emb_items.id', 'desc')
			->get()
			->map(function ($row_item) use ($embelishmentsize) {
				$row_item->emb_size = $embelishmentsize[$row_item->embelishment_size_id];
				$row_item->qty = number_format($row_item->qty, 2, '.', ',');
				$row_item->rate = number_format($row_item->rate, 4, '.', ',') . ' / ' . $row_item->uom_name;
				$row_item->amount = number_format($row_item->amount, 2, '.', ',');
				return $row_item;
			});

		$rows = $this->soemb
			->join('so_emb_refs', function ($join) {
				$join->on('so_emb_refs.so_emb_id', '=', 'so_embs.id');
			})
			->leftJoin('so_emb_pos', function ($join) {
				$join->on('so_emb_pos.so_emb_id', '=', 'so_embs.id');
			})
			->leftJoin('so_emb_po_items', function ($join) {
				$join->on('so_emb_po_items.so_emb_ref_id', '=', 'so_emb_refs.id');
			})
			->leftJoin('po_emb_service_item_qties', function ($join) {
				$join->on('po_emb_service_item_qties.id', '=', 'so_emb_po_items.po_emb_service_item_qty_id');
			})
			->leftJoin('po_emb_service_items', function ($join) {
				$join->on('po_emb_service_items.id', '=', 'po_emb_service_item_qties.po_emb_service_item_id')
					->whereNull('po_emb_service_items.deleted_at');
			})
			->leftJoin('po_emb_services', function ($join) {
				$join->on('po_emb_services.id', '=', 'po_emb_service_items.po_emb_service_id');
			})

			->join('budget_embs', function ($join) {
				$join->on('budget_embs.id', '=', 'po_emb_service_items.budget_emb_id');
			})
			->join('style_embelishments', function ($join) {
				$join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
			})
			->join('style_gmts', function ($join) {
				$join->on('style_gmts.id', '=', 'style_embelishments.style_gmt_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'style_embelishments.gmtspart_id');
			})
			->join('embelishments', function ($join) {
				$join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
			})
			->join('embelishment_types', function ($join) {
				$join->on('embelishment_types.id', '=', 'style_embelishments.embelishment_type_id');
			})
			->join('budget_emb_cons', function ($join) {
				$join->on('budget_emb_cons.id', '=', 'po_emb_service_item_qties.budget_emb_con_id')
					->whereNull('budget_emb_cons.deleted_at');
			})
			->join('sales_order_gmt_color_sizes', function ($join) {
				$join->on('sales_order_gmt_color_sizes.id', '=', 'budget_emb_cons.sales_order_gmt_color_size_id');
			})
			->join('sales_order_countries', function ($join) {
				$join->on('sales_order_countries.id', '=', 'sales_order_gmt_color_sizes.sale_order_country_id');
			})
			->join('sales_orders', function ($join) {
				$join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
			})
			->join('jobs', function ($join) {
				$join->on('jobs.id', '=', 'sales_orders.job_id');
			})
			->join('styles', function ($join) {
				$join->on('jobs.style_id', '=', 'styles.id');
			})
			->join('style_gmt_color_sizes', function ($join) {
				$join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
			})
			->join('style_sizes', function ($join) {
				$join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
			})
			->join('sizes', function ($join) {
				$join->on('sizes.id', '=', 'style_sizes.size_id');
			})
			->join('style_colors', function ($join) {
				$join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'style_colors.color_id');
			})

			->join('countries', function ($join) {
				$join->on('countries.id', '=', 'sales_order_countries.country_id');
			})

			->leftJoin('buyers', function ($join) {
				$join->on('buyers.id', '=', 'styles.buyer_id');
			})
			->where([['so_embs.id', '=', request('so_emb_id', 0)]])
			->selectRaw(
				'
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
        '
			)
			->orderBy('so_emb_po_items.id', 'desc')
			->get()
			->map(function ($rows) use ($embelishmentsize) {
				$rows->emb_size = $embelishmentsize[$rows->embelishment_size_id];
				$rows->uom_name = 'Pcs';
				$rows->qty = number_format($rows->qty, 0, '.', ',');
				$rows->rate = number_format($rows->rate, 4, '.', ',') . ' / Dzn';
				$rows->amount = number_format($rows->amount, 2, '.', ',');
				return $rows;
			});

		if ($rows->isNotEmpty()) {
			echo json_encode($rows);
		} else {
			echo json_encode($row_item);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(SoEmbItemRequest $request)
	{
		\DB::beginTransaction();
		try {
			$poembref = $this->poembref->create(['so_emb_id' => $request->so_emb_id]);
			$request->request->add(['so_emb_ref_id' => $poembref->id]);

			$color = $this->color->firstOrCreate(['name' => $request->gmt_color], ['code' => '']);
			$size = $this->size->firstOrCreate(['name' => $request->gmt_size], ['code' => '']);
			$request->request->add(['color_id' => $color->id]);
			$request->request->add(['size_id' => $size->id]);

			$soembitem = $this->soembitem->create($request->except(['id', 'po_emb_service_item_id', 'gmt_color', 'gmt_size']));
		} catch (EXCEPTION $e) {
			\DB::rollback();
			throw $e;
		}
		\DB::commit();
		if ($soembitem) {
			return response()->json(array('success' => true, 'id' =>  $soembitem->id, 'message' => 'Save Successfully'), 200);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{


		/*$rows=$this->soemb
        ->join('so_emb_refs',function($join){
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
        ->leftJoin('sales_orders',function($join){
              $join->on('sales_orders.id','=','po_emb_service_item_qties.sales_order_id');
        })
        ->leftJoin('jobs',function($join){
              $join->on('jobs.id','=','sales_orders.job_id');
        })
        ->leftJoin('styles',function($join){
              $join->on('styles.id','=','jobs.style_id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
                 $join->on('budget_fabric_prods.id','=','po_emb_service_items.budget_fabric_prod_id');
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
        ->leftJoin('so_emb_items',function($join){
            $join->on('so_emb_items.so_emb_ref_id','=','so_emb_refs.id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
        })
        ->leftJoin('buyers as gmt_buyer',function($join){
            $join->on('gmt_buyer.id','=','so_emb_items.gmt_buyer');
        })
        ->leftJoin('colors as so_color',function($join){
            $join->on('so_color.id','=','so_emb_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
            $join->on('po_color.id','=','po_emb_service_item_qties.fabric_color_id');
        })
        ->where([['so_emb_refs.id','=',$id]])
        ->selectRaw('
              so_emb_items.id,
              so_emb_refs.id as so_emb_ref_id,
              so_emb_refs.so_emb_id,
              style_fabrications.autoyarn_id,
              style_fabrications.fabric_look_id,
              style_fabrications.fabric_shape_id,
              style_fabrications.gmtspart_id,
              budget_fabrics.gsm_weight,
              style_fabrications.uom_id,
              po_emb_service_items.id as po_emb_service_item_id,
              po_emb_service_item_qties.dia,
              po_emb_service_item_qties.measurment,
              po_emb_service_item_qties.qty,
              po_emb_service_item_qties.pcs_qty,
              po_emb_service_item_qties.rate,
              po_emb_service_item_qties.amount,
              so_emb_items.autoyarn_id as c_autoyarn_id,
              so_emb_items.fabric_look_id as c_fabric_look_id,
              so_emb_items.fabric_shape_id as c_fabric_shape_id,
              so_emb_items.gmtspart_id as c_gmtspart_id,
              so_emb_items.gsm_weight as c_gsm_weight,
              so_emb_items.dia as c_dia,
              so_emb_items.measurment as c_measurment,
              so_emb_items.qty as c_qty,
              so_emb_items.rate as c_rate,
              so_emb_items.amount as c_amount,
              so_emb_items.delivery_date,
              so_emb_items.delivery_point,
              so_emb_items.uom_id as so_uom_id,
              so_emb_items.currency_id,
              styles.style_ref,
              sales_orders.sale_order_no,
              so_emb_items.gmt_style_ref,
              so_emb_items.gmt_sale_order_no,
              buyers.id as buyer_name,
              gmt_buyer.id as gmt_buyer_name,
              so_color.name as c_fabric_color_name,
              po_color.name as fabric_color_name
              '
              )
        ->orderBy('so_emb_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown){
          $rows->autoyarn_id=$rows->autoyarn_id?$rows->autoyarn_id:$rows->c_autoyarn_id;

          $rows->fabrication=$rows->autoyarn_id?$desDropdown[$rows->autoyarn_id]:$desDropdown[$rows->c_autoyarn_id];
          $rows->gmtspart_id=$rows->gmtspart_id?$rows->gmtspart_id:$rows->c_gmtspart_id;
          $rows->fabric_look_id=$rows->fabric_look_id?$rows->fabric_look_id:$rows->c_fabric_look_id;
          $rows->fabric_shape_id=$rows->fabric_shape_id?$rows->fabric_shape_id:$rows->c_fabric_shape_id;
          $rows->gsm_weight=$rows->gsm_weight?$rows->gsm_weight:$rows->c_gsm_weight;
          $rows->dia=$rows->dia?$rows->dia:$rows->c_dia;
          $rows->measurment=$rows->measurment?$rows->measurment:$rows->c_measurment;
          $rows->qty=$rows->qty?$rows->qty:$rows->c_qty;
          $rows->rate=$rows->rate?$rows->rate:$rows->c_rate;
          $rows->amount=$rows->amount?$rows->amount:$rows->c_amount;
          $rows->gmt_style_ref=$rows->style_ref?$rows->style_ref:$rows->gmt_style_ref;
          $rows->gmt_buyer=$rows->buyer_name?$rows->buyer_name:$rows->gmt_buyer_name;
          $rows->gmt_sale_order_no=$rows->sale_order_no?$rows->sale_order_no:$rows->gmt_sale_order_no;
          $rows->uom_id=$rows->uom_id?$rows->uom_id:$rows->so_uom_id;
          $rows->fabric_color=$rows->fabric_color_name?$rows->fabric_color_name:$rows->c_fabric_color_name;
          return $rows;
        })->first();
        if(!$rows->id){
          $rows->id=$rows->so_emb_ref_id;
        }*/
		$rows = $this->soemb
			->join('so_emb_refs', function ($join) {
				$join->on('so_emb_refs.so_emb_id', '=', 'so_embs.id');
			})
			->join('so_emb_items', function ($join) {
				$join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'so_emb_items.gmtspart_id');
			})
			->join('embelishments', function ($join) {
				$join->on('embelishments.id', '=', 'so_emb_items.embelishment_id');
			})
			->join('embelishment_types', function ($join) {
				$join->on('embelishment_types.id', '=', 'so_emb_items.embelishment_type_id');
			})
			->leftJoin('buyers', function ($join) {
				$join->on('buyers.id', '=', 'so_emb_items.gmt_buyer');
			})
			->leftJoin('uoms', function ($join) {
				$join->on('uoms.id', '=', 'so_emb_items.uom_id');
			})
			->leftJoin('colors', function ($join) {
				$join->on('colors.id', '=', 'so_emb_items.color_id');
			})
			->leftJoin('sizes', function ($join) {
				$join->on('sizes.id', '=', 'so_emb_items.size_id');
			})
			->leftJoin('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'so_emb_items.item_account_id');
			})
			->where([['so_emb_refs.id', '=', $id]])
			->selectRaw(
				'
        so_emb_refs.id as so_emb_ref_id,
        so_emb_refs.so_emb_id,
        so_emb_items.id,
        so_emb_items.gmtspart_id,
        so_emb_items.embelishment_id,
        so_emb_items.embelishment_type_id,
        so_emb_items.embelishment_size_id,
        so_emb_items.item_account_id,
        so_emb_items.gmt_buyer,
        so_emb_items.qty,
        so_emb_items.rate,
        so_emb_items.amount,
        so_emb_items.gmt_style_ref,
        so_emb_items.gmt_sale_order_no,
        so_emb_items.uom_id,
        so_emb_items.delivery_date,
        so_emb_items.delivery_point,
        colors.name as gmt_color,
        sizes.name as gmt_size
        '
			)
			->orderBy('so_emb_items.id', 'desc')
			->get()
			->map(function ($rows) {
				return $rows;
			})->first();
		if (!$rows->id) {
			$rows->id = $rows->so_emb_ref_id;
		}

		$row['embelishmenttype'] = $this->embelishmenttype->where([['embelishment_id', '=', $rows->embelishment_id]])->get();
		$row['fromData'] = $rows;
		$dropdown['att'] = '';
		$row['dropDown'] = $dropdown;
		echo json_encode($row);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(SoEmbItemRequest $request, $id)
	{
		if ($request->po_emb_service_item_id) {
			return response()->json(array('success' => false, 'message' => 'Update no possible, Emb Service Order Found'), 200);
		} else {
			\DB::beginTransaction();
			try {
				$color = $this->color->firstOrCreate(['name' => $request->gmt_color], ['code' => '']);
				$size = $this->size->firstOrCreate(['name' => $request->gmt_size], ['code' => '']);
				$request->request->add(['color_id' => $color->id]);
				$request->request->add(['size_id' => $size->id]);
				$soembitem = $this->soembitem->update($id, $request->except(['id', 'po_emb_service_item_id', 'gmt_color', 'gmt_size']));
			} catch (EXCEPTION $e) {
				\DB::rollback();
				throw $e;
			}
			\DB::commit();

			if ($soembitem) {
				return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
			}
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
		if ($this->soembitem->delete($id)) {
			return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
		}
	}

	public function getEmbtype()
	{
		$embelishment = $this->embelishment->find(request('embelishment_id', 0));
		$row['embelishmenttype'] = $this->embelishmenttype->where([['embelishment_id', '=', request('embelishment_id', 0)]])->get();
		echo json_encode($row);
	}
}
