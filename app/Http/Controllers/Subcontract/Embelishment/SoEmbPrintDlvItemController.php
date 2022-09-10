<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintDlvRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRefRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintDlvItemRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvQtyRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbPrintDlvItemRequest;

class SoEmbPrintDlvItemController extends Controller
{
	private $soembprintdlv;
	private $poembref;
	private $soembprintdlvitem;
	private $autoyarn;
	private $gmtspart;
	private $uom;
	private $color;
	private $size;
	private $embelishment;
	private $embelishmenttype;
	private $soembcutpanelrcvqty;


	public function __construct(
		SoEmbPrintDlvRepository $soembprintdlv,
		SoEmbRefRepository $poembref,
		SoEmbCutpanelRcvQtyRepository $soembcutpanelrcvqty,
		SoEmbPrintDlvItemRepository $soembprintdlvitem,
		AutoyarnRepository $autoyarn,
		GmtspartRepository $gmtspart,
		UomRepository $uom,
		ColorRepository $color,
		SizeRepository $size,
		EmbelishmentRepository $embelishment,
		EmbelishmentTypeRepository $embelishmenttype
	) {
		$this->soembprintdlv = $soembprintdlv;
		$this->poembref = $poembref;
		$this->soembprintdlvitem = $soembprintdlvitem;
		$this->autoyarn = $autoyarn;
		$this->gmtspart = $gmtspart;
		$this->uom = $uom;
		$this->color = $color;
		$this->size = $size;
		$this->embelishment = $embelishment;
		$this->embelishmenttype = $embelishmenttype;
		$this->soembcutpanelrcvqty = $soembcutpanelrcvqty;
		$this->middleware('auth');
		// $this->middleware('permission:view.soembprintdlvitems',   ['only' => ['create', 'index', 'show']]);
		// $this->middleware('permission:create.soembprintdlvitems', ['only' => ['store']]);
		// $this->middleware('permission:edit.soembprintdlvitems',   ['only' => ['update']]);
		// $this->middleware('permission:delete.soembprintdlvitems', ['only' => ['destroy']]);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		$embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');
		$rows = $this->soembprintdlvitem
			->join('so_emb_print_dlvs', function ($join) {
				$join->on('so_emb_print_dlvs.id', '=', 'so_emb_print_dlv_items.so_emb_print_dlv_id');
			})
			->join('so_emb_cutpanel_rcv_qties', function ($join) {
				$join->on('so_emb_cutpanel_rcv_qties.id', '=', 'so_emb_print_dlv_items.so_emb_cutpanel_rcv_qty_id');
			})
			->join('so_emb_refs', function ($join) {
				$join->on('so_emb_refs.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_ref_id');
			})
			->join('so_embs', function ($join) {
				$join->on('so_embs.id', '=', 'so_emb_refs.so_emb_id');
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
			->where([['so_emb_print_dlv_items.so_emb_print_dlv_id', '=', request('so_emb_print_dlv_id', 0)]])
			->orderBy('so_emb_print_dlv_items.id', 'DESC')
			->get([
				'so_emb_print_dlv_items.*',
				'so_emb_cutpanel_rcv_qties.design_no',
				'embelishments.name as emb_name',
				'embelishment_types.name as emb_type',
				'gmtsparts.name as gmtspart',
				'so_emb_items.embelishment_size_id',
				'so_emb_items.rate',
				'so_emb_items.gmt_style_ref as style_ref',
				'so_emb_items.gmt_sale_order_no as sale_order_no',
				'buyers.name as buyer_name',
				'item_accounts.item_description as item_desc',
				'colors.name as gmt_color',
				'sizes.name as gmt_size'
			])
			->map(function ($rows) use ($embelishmentsize) {
				$rows->emb_size = $embelishmentsize[$rows->embelishment_size_id];
				$rows->uom_name = 'Pcs';
				$rows->qty = number_format($rows->qty, 0, '.', '');
				$rows->rate = number_format($rows->rate, 4, '.', '');
				$rows->amount = number_format($rows->amount, 2, '.', '');
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
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(SoEmbPrintDlvItemRequest $request)
	{
		$soembprintdlvitem = $this->soembprintdlvitem->create([
			'so_emb_print_dlv_id' => $request->so_emb_print_dlv_id,
			'so_emb_cutpanel_rcv_qty_id' => $request->so_emb_cutpanel_rcv_qty_id,
			'dlv_qty' => $request->dlv_qty,
			'additional_charge' => $request->additional_charge,
			'amount' => $request->amount,
			'delivery_point' => $request->delivery_point,
		]);

		if ($soembprintdlvitem) {
			return response()->json(array('success' => true, 'id' =>  $soembprintdlvitem->id, 'message' => 'Save Successfully'), 200);
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
		$embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');
		$rows = $this->soembprintdlvitem
			->join('so_emb_print_dlvs', function ($join) {
				$join->on('so_emb_print_dlvs.id', '=', 'so_emb_print_dlv_items.so_emb_print_dlv_id');
			})
			->join('so_emb_cutpanel_rcv_qties', function ($join) {
				$join->on('so_emb_cutpanel_rcv_qties.id', '=', 'so_emb_print_dlv_items.so_emb_cutpanel_rcv_qty_id');
			})
			->join('so_emb_refs', function ($join) {
				$join->on('so_emb_refs.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_ref_id');
			})
			->join('so_embs', function ($join) {
				$join->on('so_embs.id', '=', 'so_emb_refs.so_emb_id');
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
			->where([['so_emb_print_dlv_items.id', '=', $id]])
			->orderBy('so_emb_print_dlv_items.id', 'DESC')
			->get([
				'so_emb_print_dlv_items.*',
				'so_emb_cutpanel_rcv_qties.design_no',
				'embelishments.name as emb_name',
				'embelishment_types.name as emb_type',
				'gmtsparts.name as gmtspart',
				'so_emb_items.embelishment_size_id',
				'so_emb_items.rate',
				'so_emb_items.gmt_style_ref as style_ref',
				'so_emb_items.gmt_sale_order_no as sale_order_no',
				'buyers.name as buyer_name',
				'item_accounts.item_description as item_desc',
				'colors.name as gmt_color',
				'sizes.name as gmt_size'
			])
			->map(function ($rows) use ($embelishmentsize) {
				$rows->emb_size = $embelishmentsize[$rows->embelishment_size_id];
				$rows->uom_name = 'Pcs';
				$rows->qty = number_format($rows->qty, 0, '.', '');
				$rows->rate = number_format($rows->rate, 4, '.', '');
				$rows->amount = number_format($rows->amount, 2, '.', '');
				return $rows;
			})
			->first();

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
	public function update(SoEmbPrintDlvItemRequest $request, $id)
	{
		$soembprintdlvitem = $this->soembprintdlvitem->update($id, [
			'so_emb_cutpanel_rcv_qty_id' => $request->so_emb_cutpanel_rcv_qty_id,
			'dlv_qty' => $request->dlv_qty,
			'additional_charge' => $request->additional_charge,
			'amount' => $request->amount,
			'delivery_point' => $request->delivery_point,
		]);

		if ($soembprintdlvitem) {
			return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
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
		if ($this->soembprintdlvitem->delete($id)) {
			return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
		}
	}

	public function getEmbSalesOrder()
	{

		$embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');

		$rows = $this->soembcutpanelrcvqty
			->join('so_emb_cutpanel_rcv_orders', function ($join) {
				$join->on('so_emb_cutpanel_rcv_orders.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_cutpanel_rcv_order_id');
			})
			->join('so_emb_refs', function ($join) {
				$join->on('so_emb_refs.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_ref_id');
			})
			->join('so_embs', function ($join) {
				$join->on('so_embs.id', '=', 'so_emb_refs.so_emb_id');
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
			->where([['so_embs.currency_id', '=', request('currency_id', 0)]])
			->orderBy('so_emb_cutpanel_rcv_qties.id', 'DESC')
			->get([
				'so_emb_cutpanel_rcv_qties.*',
				'embelishments.name as emb_name',
				'embelishment_types.name as emb_type',
				'gmtsparts.name as gmtspart',
				'so_emb_items.embelishment_size_id',
				'so_emb_items.delivery_date',
				'so_emb_items.qty',
				'so_emb_items.rate',
				'so_emb_items.amount',
				'so_emb_items.gmt_style_ref as style_ref',
				'so_emb_items.gmt_sale_order_no as sale_order_no',
				'buyers.name as buyer_name',
				'item_accounts.item_description as item_desc',
				'colors.name as gmt_color',
				'sizes.name as gmt_size'
			])
			->map(function ($rows) use ($embelishmentsize) {
				$rows->emb_size = $embelishmentsize[$rows->embelishment_size_id];
				$rows->uom_name = 'Pcs';
				$rows->qty = number_format($rows->qty, 0, '.', '');
				$rows->rate = number_format($rows->rate, 4, '.', '');
				$rows->amount = number_format($rows->amount, 2, '.', '');
				return $rows;
			});
		echo json_encode($rows);
	}
}
