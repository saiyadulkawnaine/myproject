<?php

namespace App\Http\Controllers\JhuteSale;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderQtyRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderItemRepository;

use App\Library\Template;
use App\Http\Requests\JhuteSale\JhuteSaleDlvOrderQtyRequest;

class GmtLeftoverSaleOrderQtyController extends Controller
{

	private $jhutesaledlvorderqty;
	private $salesordergmtcolorsize;
	private $jhutesaledlvorderitem;

	public function __construct(
		JhuteSaleDlvOrderQtyRepository $jhutesaledlvorderqty,
		JhuteSaleDlvOrderItemRepository $jhutesaledlvorderitem,
		SalesOrderGmtColorSizeRepository $salesordergmtcolorsize
	) {

		$this->salesordergmtcolorsize = $salesordergmtcolorsize;
		$this->jhutesaledlvorderitem = $jhutesaledlvorderitem;
		$this->jhutesaledlvorderqty = $jhutesaledlvorderqty;

		$this->middleware('auth');

		/*$this->middleware('permission:view.gmtleftoversaledlvorderqties',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.gmtleftoversaledlvorderqties', ['only' => ['store']]);
        $this->middleware('permission:edit.gmtleftoversaledlvorderqties',   ['only' => ['update']]);
        $this->middleware('permission:delete.gmtleftoversaledlvorderqties', ['only' => ['destroy']]);*/
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index()
	{
		//
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

	public function store(JhuteSaleDlvOrderQtyRequest $request)
	{
		$total_qty = 0;
		$total_amount = 0;
		// $jhutesaledlvorderitemId=0;
		$avg_rate = 0;
		foreach ($request->sales_order_gmt_color_size_id as $index => $sales_order_gmt_color_size_id) {
			//  $jhutesaledlvorderitemId=$request->jhute_sale_dlv_order_item_id[$index];

			if ($sales_order_gmt_color_size_id && $request->qty[$index]) {
				$total_qty += $request->qty[$index];
				$total_amount += $request->amount[$index];
			}
		}
		$avg_rate = $total_amount / $total_qty;


		foreach ($request->sales_order_gmt_color_size_id as $index => $sales_order_gmt_color_size_id) {
			//   $jhutesaledlvorderitemId=$request->jhute_sale_dlv_order_item_id[$index];
			if ($sales_order_gmt_color_size_id && $request->qty[$index]) {
				$jhutesaledlvorderqty = $this->jhutesaledlvorderqty->updateOrCreate(
					[
						'sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,
						'jhute_sale_dlv_order_item_id' => $request->jhute_sale_dlv_order_item_id
					],
					[
						'qty' => $request->qty[$index],
						'rate' => $request->rate[$index],
						'amount' => $request->amount[$index],
					]
				);
			}
		}

		$this->jhutesaledlvorderitem->update(
			$request->jhute_sale_dlv_order_item_id,
			[
				'qty' => $total_qty,
				'amount' => $total_amount,
				'rate' => $avg_rate
			]
		);

		if ($jhutesaledlvorderqty) {
			return response()->json(array(
				'success' => true,
				'id' =>  $jhutesaledlvorderqty->id,
				//  'jhute_sale_dlv_order_item_id' =>  $jhutesaledlvorderitemId,
				'message' => 'Save Successfully'
			), 200);
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
		$jhutesaledlvorderqty = $this->jhutesaledlvorderqty->find($id);
		$row['fromData'] = $jhutesaledlvorderqty;
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
	public function update(JhuteSaleDlvOrderQtyRequest $request, $id)
	{
		$jhutesaledlvorderqty = $this->jhutesaledlvorderqty->update($id, $request->except(['id']));
		if ($jhutesaledlvorderqty) {
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
		if ($this->jhutesaledlvorderqty->delete($id)) {
			return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
		}
	}
}
