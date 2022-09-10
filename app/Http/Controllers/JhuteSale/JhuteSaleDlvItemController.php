<?php

namespace App\Http\Controllers\JhuteSale;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderItemRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvItemRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Library\Template;
use App\Http\Requests\JhuteSale\JhuteSaleDlvItemRequest;

class JhuteSaleDlvItemController extends Controller
{
    private $jhutesaledlvorderitem;
    private $jhutesaledlvorder;
    private $uom;
    private $ctrlHead;
    private $jhutesaledlvitem;


    public function __construct(
        JhuteSaleDlvOrderItemRepository $jhutesaledlvorderitem,
        JhuteSaleDlvOrderRepository $jhutesaledlvorder,
        UomRepository $uom,
        AccChartCtrlHeadRepository $ctrlHead,
        JhuteSaleDlvItemRepository $jhutesaledlvitem
    ) {

        $this->jhutesaledlvorderitem = $jhutesaledlvorderitem;
        $this->jhutesaledlvorder = $jhutesaledlvorder;
        $this->uom = $uom;
        $this->ctrlHead = $ctrlHead;
        $this->jhutesaledlvitem = $jhutesaledlvitem;
        $this->middleware('auth');

        // $this->middleware('permission:view.jhutesaledlvitems',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.jhutesaledlvitems', ['only' => ['store']]);
        // $this->middleware('permission:edit.jhutesaledlvitems',   ['only' => ['update']]);
        // $this->middleware('permission:delete.jhutesaledlvitems', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = $this->jhutesaledlvitem
            ->join('jhute_sale_dlv_order_items', function ($join) {
                $join->on('jhute_sale_dlv_order_items.id', '=', 'jhute_sale_dlv_items.jhute_sale_dlv_order_item_id');
            })
            ->leftJoin('uoms', function ($join) {
                $join->on('uoms.id', '=', 'jhute_sale_dlv_order_items.uom_id');
            })
            ->join('acc_chart_ctrl_heads', function ($join) {
                $join->on('acc_chart_ctrl_heads.id', '=', 'jhute_sale_dlv_order_items.acc_chart_ctrl_head_id');
            })
            ->where([['jhute_sale_dlv_items.jhute_sale_dlv_id', '=', request('jhute_sale_dlv_id', 0)]])
            ->orderBy('jhute_sale_dlv_items.id', 'desc')
            ->get([
                'jhute_sale_dlv_items.*',
                'acc_chart_ctrl_heads.name as acc_chart_ctrl_head_name',
                'uoms.code as uom_code',
                'jhute_sale_dlv_order_items.rate'

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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JhuteSaleDlvItemRequest $request)
    {
        $rows = $this->jhutesaledlvorderitem
            ->leftJoin(\DB::raw(
                '(
            select 
            jhute_sale_dlv_order_items.id as jhute_sale_dlv_order_item_id,
            sum(jhute_sale_dlv_items.qty) as cumulative_qty
            from jhute_sale_dlv_items
            join jhute_sale_dlv_order_items on jhute_sale_dlv_order_items.id=jhute_sale_dlv_items.jhute_sale_dlv_order_item_id
            where 
            jhute_sale_dlv_items.deleted_at is null
            group by 
            jhute_sale_dlv_order_items.id
            ) cumulative'
            ), 'cumulative.jhute_sale_dlv_order_item_id', '=', 'jhute_sale_dlv_order_items.id')
            ->where([['jhute_sale_dlv_order_items.id', '=', $request->jhute_sale_dlv_order_item_id]])
            ->get([
                'jhute_sale_dlv_order_items.qty',
                'cumulative.cumulative_qty'
            ])
            ->first();
        $rows->balance_qty = $rows->qty - ($rows->cumulative_qty + $request->qty);

        if ($rows->balance_qty < 0) {
            return response()->json(array('success' => false,/*  'id'=>$jhutesaledlvitem->id, */ 'message' => 'Delivery Quantity Exceeded Order Quantity'), 200);
        }

        $jhutesaledlvitem = $this->jhutesaledlvitem->create([
            'jhute_sale_dlv_id' => $request->jhute_sale_dlv_id,
            'jhute_sale_dlv_order_item_id' => $request->jhute_sale_dlv_order_item_id,
            'qty' => $request->qty,
            'amount' => $request->amount,
            'remarks' => $request->remarks
        ]);
        if ($jhutesaledlvitem) {
            return response()->json(array('success' => true, 'id' => $jhutesaledlvitem->id, 'message' => 'Save Successfully'), 200);
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
        $jhutesaledlvitem = $this->jhutesaledlvitem
            ->join('jhute_sale_dlv_order_items', function ($join) {
                $join->on('jhute_sale_dlv_order_items.id', '=', 'jhute_sale_dlv_items.jhute_sale_dlv_order_item_id');
            })
            ->join('jhute_sale_dlvs', function ($join) {
                $join->on('jhute_sale_dlvs.id', '=', 'jhute_sale_dlv_items.jhute_sale_dlv_id');
            })
            ->leftJoin('uoms', function ($join) {
                $join->on('uoms.id', '=', 'jhute_sale_dlv_order_items.uom_id');
            })
            ->join('acc_chart_ctrl_heads', function ($join) {
                $join->on('acc_chart_ctrl_heads.id', '=', 'jhute_sale_dlv_order_items.acc_chart_ctrl_head_id');
            })
            ->leftJoin(\DB::raw(
                '(
            select 
            jhute_sale_dlv_order_items.id as jhute_sale_dlv_order_item_id,
            sum(jhute_sale_dlv_items.qty) as cumulative_qty
            from jhute_sale_dlv_items
            join jhute_sale_dlv_order_items on jhute_sale_dlv_order_items.id=jhute_sale_dlv_items.jhute_sale_dlv_order_item_id
            where 
            jhute_sale_dlv_items.deleted_at is null
            group by 
            jhute_sale_dlv_order_items.id
            ) cumulative'
            ), 'cumulative.jhute_sale_dlv_order_item_id', '=', 'jhute_sale_dlv_order_items.id')
            ->where([['jhute_sale_dlv_items.id', '=', $id]])
            ->get([
                'jhute_sale_dlv_items.*',
                'acc_chart_ctrl_heads.name as acc_chart_ctrl_head_name',
                'uoms.name as uom_name',
                'jhute_sale_dlv_order_items.rate',
                'jhute_sale_dlv_order_items.qty as order_qty',
                'cumulative.cumulative_qty',

            ])
            ->map(function ($jhutesaledlvitem) {
                $jhutesaledlvitem->balance_qty = $jhutesaledlvitem->order_qty - $jhutesaledlvitem->cumulative_qty;
                return $jhutesaledlvitem;
            })
            ->first();
        $row['fromData'] = $jhutesaledlvitem;
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

    public function update(JhuteSaleDlvItemRequest $request, $id)
    {
        $rows = $this->jhutesaledlvorderitem
            ->leftJoin(\DB::raw(
                '(
            select 
            jhute_sale_dlv_order_items.id as jhute_sale_dlv_order_item_id,
            sum(jhute_sale_dlv_items.qty) as cumulative_qty
            from jhute_sale_dlv_items
            join jhute_sale_dlv_order_items on jhute_sale_dlv_order_items.id=jhute_sale_dlv_items.jhute_sale_dlv_order_item_id
            where 
            jhute_sale_dlv_items.deleted_at is null
            group by 
            jhute_sale_dlv_order_items.id
            ) cumulative'
            ), 'cumulative.jhute_sale_dlv_order_item_id', '=', 'jhute_sale_dlv_order_items.id')
            ->leftJoin('jhute_sale_dlv_items', function ($join) {
                $join->on('jhute_sale_dlv_order_items.id', '=', 'jhute_sale_dlv_items.jhute_sale_dlv_order_item_id');
            })
            ->where([['jhute_sale_dlv_order_items.id', '=', $request->jhute_sale_dlv_order_item_id]])
            ->get([
                'jhute_sale_dlv_order_items.qty as order_qty',
                //'jhute_sale_dlv_items.qty',
                'cumulative.cumulative_qty',
            ])
            ->first();

        $rows->prev_balance_qty = $rows->order_qty - $rows->cumulative_qty;
        $balance_qty = $rows->order_qty - $request->qty;

        if ($balance_qty < 0  &&  $balance_qty > $request->balance_qty) {
            return response()->json(array('success' => false, 'message' => 'Delivery Quantity Exceeded Order Quantity'), 200);
        }


        $jhutesaledlvitem = $this->jhutesaledlvitem->update($id, [
            'jhute_sale_dlv_id' => $request->jhute_sale_dlv_id,
            'jhute_sale_dlv_order_item_id' => $request->jhute_sale_dlv_order_item_id,
            'qty' => $request->qty,
            'amount' => $request->amount,
            'remarks' => $request->remarks
        ]);
        if ($jhutesaledlvitem) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Updated Successfully'), 200);
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
        if ($this->jhutesaledlvitem->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Deleted Successfully'), 200);
        }
    }

    public function getJhuteSaleDlvOrderItem()
    {

        $jhutesaleorder = $this->jhutesaledlvorder->find(request('jhutesaledlvorderid', 0));
        $rows = $this->jhutesaledlvorderitem
            ->leftJoin('uoms', function ($join) {
                $join->on('uoms.id', '=', 'jhute_sale_dlv_order_items.uom_id');
            })
            ->join('acc_chart_ctrl_heads', function ($join) {
                $join->on('acc_chart_ctrl_heads.id', '=', 'jhute_sale_dlv_order_items.acc_chart_ctrl_head_id');
            })
            ->leftJoin(\DB::raw(
                '(
            select 
            jhute_sale_dlv_items.jhute_sale_dlv_order_item_id,
            sum(jhute_sale_dlv_items.qty) as prev_dlv_qty
            from jhute_sale_dlv_items
            where 
            jhute_sale_dlv_items.deleted_at is null
            group by 
            jhute_sale_dlv_items.jhute_sale_dlv_order_item_id
            ) prevDlvOrder'
            ), 'prevDlvOrder.jhute_sale_dlv_order_item_id', '=', 'jhute_sale_dlv_order_items.id')
            ->where([['jhute_sale_dlv_order_items.jhute_sale_dlv_order_id', '=', $jhutesaleorder->id]])
            ->orderBy('jhute_sale_dlv_order_items.id', 'desc')
            ->get(
                [
                    'jhute_sale_dlv_order_items.*',
                    'uoms.name as uom_name',
                    'acc_chart_ctrl_heads.name as acc_chart_ctrl_head_name',
                    'prevDlvOrder.prev_dlv_qty'
                ]
            )
            ->map(function ($rows) {
                $rows->balance_qty = $rows->qty - $rows->prev_dlv_qty;
                if ($rows->balance_qty) {
                    $rows->balance_amount = $rows->balance_qty * $rows->rate;
                }

                return $rows;
            });

        echo json_encode($rows);
    }
}
