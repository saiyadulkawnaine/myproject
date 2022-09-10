<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvOrderRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvQtyRepository;
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
use App\Http\Requests\Subcontract\Embelishment\SoEmbCutpanelRcvQtyRequest;

class SoEmbCutpanelRcvQtyController extends Controller
{
	private $soembcutpanelrcv;
	private $soembcutpanelrcvorder;
	private $soembcutpanelrcvqty;
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
		SoEmbCutpanelRcvRepository $soembcutpanelrcv,
		SoEmbCutPanelRcvOrderRepository $soembcutpanelrcvorder,
		SoEmbCutpanelRcvQtyRepository $soembcutpanelrcvqty,
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
		$this->soembcutpanelrcv = $soembcutpanelrcv;
		$this->soembcutpanelrcvorder = $soembcutpanelrcvorder;
		$this->soembcutpanelrcvqty = $soembcutpanelrcvqty;
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

		// $this->middleware('permission:view.soembcutpanelrcvqties',   ['only' => ['create', 'index','show']]);
		// $this->middleware('permission:create.soembcutpanelrcvqties', ['only' => ['store']]);
		// $this->middleware('permission:edit.soembcutpanelrcvqties',   ['only' => ['update']]);
		// $this->middleware('permission:delete.soembcutpanelrcvqties', ['only' => ['destroy']]);

	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$rows = $this->soembcutpanelrcvqty
			->join('so_emb_cutpanel_rcv_orders', function ($join) {
				$join->on('so_emb_cutpanel_rcv_orders.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_cutpanel_rcv_order_id');
			})
			->join('so_emb_refs', function ($join) {
				$join->on('so_emb_refs.id', '=', 'so_emb_cutpanel_rcv_qties.so_emb_ref_id');
			})
			->join('so_emb_items', function ($join) {
				$join->on('so_emb_items.so_emb_ref_id', '=', 'so_emb_refs.id');
			})
			->join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'so_emb_items.gmtspart_id');
			})
			->join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'so_emb_items.item_account_id');
			})
			->join('colors', function ($join) {
				$join->on('colors.id', '=', 'so_emb_items.color_id');
			})
			->where([['so_emb_cutpanel_rcv_qties.so_emb_cutpanel_rcv_order_id', '=', request('so_emb_cutpanel_rcv_order_id', 0)]])
			->orderBy('so_emb_cutpanel_rcv_qties.id', 'DESC')
			->get([
				'so_emb_cutpanel_rcv_qties.*',
				'so_emb_items.gmt_sale_order_no as sale_order_no',
				'item_accounts.item_description as item_desc',
				'colors.name as gmt_color',
				'gmtsparts.name as gmtspart'
			]);
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
	public function store(SoEmbCutpanelRcvQtyRequest $request)
	{
		$soembcutpanelrcvqty = $this->soembcutpanelrcvqty->create([
			'so_emb_cutpanel_rcv_order_id' => $request->so_emb_cutpanel_rcv_order_id,
			'so_emb_ref_id' => $request->so_emb_ref_id,
			'qty' => $request->qty,
			'design_no' => $request->design_no
		]);

		if ($soembcutpanelrcvqty) {
			return response()->json(array("success" => true, 'id' => $soembcutpanelrcvqty->id, "message" => "Save Successfully"), 200);
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
		$soembcutpanelrcvqty = $this->soembcutpanelrcvqty
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
				$join->on('so_emb_items.so_emb_id', '=', 'so_embs.id');
			})
			->Join('gmtsparts', function ($join) {
				$join->on('gmtsparts.id', '=', 'so_emb_items.gmtspart_id');
			})
			->Join('item_accounts', function ($join) {
				$join->on('item_accounts.id', '=', 'so_emb_items.item_account_id');
			})
			->Join('colors', function ($join) {
				$join->on('colors.id', '=', 'so_emb_items.color_id');
			})
			->where([['so_emb_cutpanel_rcv_qties.id', '=', $id]])
			->get([
				'so_emb_cutpanel_rcv_qties.*',
				'so_emb_items.gmt_sale_order_no as sale_order_no',
				'item_accounts.item_description as item_desc',
				'colors.name as gmt_color',
				'gmtsparts.name as gmtspart'
			])
			->first();
		$row['fromData'] = $soembcutpanelrcvqty;
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
	public function update(SoEmbCutpanelRcvQtyRequest $request, $id)
	{
		$soembcutpanelrcvqty = $this->soembcutpanelrcvqty->update($id, [
			'so_emb_ref_id' => $request->so_emb_ref_id,
			'qty' => $request->qty,
			'design_no' => $request->design_no
		]);


		if ($soembcutpanelrcvqty) {
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
		return response()->json(array('success' => false, 'message' => 'Delete Not Successfully'), 200);
		if ($this->soembcutpanelrcvqty->delete($id)) {
			return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
		}
	}

	public function getSoEmbItem()
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

		echo json_encode($row_item);
	}
}
