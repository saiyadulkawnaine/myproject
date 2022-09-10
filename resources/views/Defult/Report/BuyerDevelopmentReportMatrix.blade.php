<style>
	td , th {
		padding: 5px; font-size: 18px
	}
</style>

<h3 align="center">LitheGroup</h3>
<h4 align="center">Order Forcasting</h4>
<?php 
        $est_ship_from=$datefrom?date('d-M-Y',strtotime($datefrom)):'';
        $est_ship_to=$dateto?date('d-M-Y',strtotime($dateto)):'';
?>
<p align="center" style="font-weight: bold;font-size: 15px;">Date :{{ $est_ship_from }} To {{ $est_ship_to }} </p>
<table border="1" cellpadding="2" cellspacing="0" width="4500px">
    <?php 
        $j=7;
    ?>
    <tr>
        <td align="center"></td>
        <td align="center"><strong>1</strong></td>
        <td align="center"><strong>2</strong></td>
        <td align="center"><strong>3</strong></td>
        <td align="center"><strong>4</strong></td>
        <td align="center"><strong>5</strong></td>
        <td align="center"><strong>6</strong></td>
        @foreach ($monthArr as $month=>$month_name)
        <td align="center" colspan="3" width="80"><strong>{{$j++}}</strong></td>
        @endforeach
        <td align="center" colspan="3"><strong>{{$j}}</strong></td>
        <th class="text-center"  align="center"><strong>{{$j+1}}</strong></th>
    </tr>
    <tr>
        <td align="center"  colspan="7" ></td>
        @foreach ($monthArr as $month=>$month_name)
        <th class="text-center"  align="center" colspan="3" >{{ $month_name }}</th>
        @endforeach
        <th class="text-center" colspan="3"  align="center">Total</th>
        <th class="text-center"  align="center"></th>
    </tr>
    <tr>
        <td align="center"><strong>SL</strong></td>
        <td align="center"><strong>Team Leader</strong></td>
        <td align="center"><strong>Team Member</strong></td>
        <td align="center"><strong>Buyer</strong></td>
        <td align="center"><strong>Brand</strong></td>
        <td align="center"><strong>Program Name/Dept</strong></td>
        <td align="center"><strong>Status</strong></td>
        @foreach ($monthArr as $month=>$month_name)
        <td align="center" width="120"><strong> Qty</strong></td>
        <td align="center" width="120"><strong> Value</strong></td>
        <td align="center" width="120"><strong> SAH</strong></td>
        @endforeach
        <td align="center"><strong> Qty</strong></td>
        <td align="center"><strong> Value</strong></td>
        <td align="center"><strong> SAH</strong></td>
        <th class="text-center"  align="center"><strong>Remarks</strong></th>
    </tr>
    <?php 
        $i=1;
        $monthwiseTotalQtyArr=[];
        $monthwiseTotalAmountArr=[];
        $monthwiseTotalStdAllwdHrArr=[];
        $monthwiseTotalRcvQtyArr=[];
        $monthwiseTotalRcvAmountArr=[];
        $monthwiseTotalRcvStdAllwdHrArr=[];
        $monthwiseTotalYetToRcvQtyArr=[];
        $monthwiseTotalYetToRcvAmountArr=[];
        $monthwiseTotalYetToRcvStdAllwdHrArr=[];
    ?>
    @foreach ($styleDescArr as $key=>$data)
    <?php
        $stylewiseTotalQty=0;
        $stylewiseTotalAmount=0;
        $stylewiseTotalStdAllwdHr=0;
        $stylewiseTotalRcvQty=0;
        $stylewiseTotalRcvAmount=0;
        $stylewiseTotalRcvStdAllwdHr=0;
        $stylewiseTotalYetToRcvQty=0;
        $stylewiseTotalYetToRcvAmount=0;
        $stylewiseTotalYetToRcvStdAllwdHr=0;
    ?>
    <tbody>
        <tr>
            <td align="center" rowspan="4">{{ $i++ }}</td>
            <td align="center" rowspan="4">{{ $data['team_name'] }}</td>
            <td align="center" rowspan="4">{{ $data['teammember_name'] }}</td>
            <td align="center" rowspan="4"><a href="javascript:void(0)" onClick="MsBuyerDevelopmentReport.buyWindow({{$data['id']}})">{{ $data['buyer_name'] }}</a></td>
            <td align="center" rowspan="4"><a href="javascript:void(0)" onClick="MsBuyerDevelopmentReport.intmWindow({{$data['id']}})">{{ $data['brand_name'] }}</a></td>
            <td align="center" rowspan="4">{{ $data['style_description'] }}</td>
        </tr>
        <tr>
            <td align="left"><strong>Forecasted</strong></td>
            @foreach ($monthArr as $month=>$month_name)
            <?php
                $qty=isset($monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['qty'])?$monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['qty']:0;

                $amount=isset($monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['amount'])?$monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['amount']:0;

                $std_allowed_hr=isset($monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['std_allowed_hr'])?$monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['std_allowed_hr']:0;
                $start_date=$monthStartDateArr[$month]['start_date'];
                $end_date=$monthStartDateArr[$month]['end_date'];
            ?>
            <td align="right"><a href="javascript:void(0)" onClick="MsBuyerDevelopmentReport.buyerdevelopmentMktCostWindow({{$key}},'{{$start_date}}','{{$end_date}}')">{{ number_format($qty,0) }} Pcs</td>
            <td align="right">${{ number_format($amount,0) }}</a></td>
            <td align="right">{{ number_format($std_allowed_hr,0) }}</td>
            <?php
                $stylewiseTotalQty+=$qty;
                $monthwiseTotalQtyArr[$month]=isset($monthwiseTotalQtyArr[$month])?$monthwiseTotalQtyArr[$month]+=$qty:$qty;

                $stylewiseTotalStdAllwdHr+=$std_allowed_hr;
                $monthwiseTotalStdAllwdHrArr[$month]=isset($monthwiseTotalStdAllwdHrArr[$month])?$monthwiseTotalStdAllwdHrArr[$month]+=$std_allowed_hr:$std_allowed_hr;

                $stylewiseTotalAmount+=$amount;
                $monthwiseTotalAmountArr[$month]=isset($monthwiseTotalAmountArr[$month])?$monthwiseTotalAmountArr[$month]+=$amount:$amount;
            ?>
            @endforeach
            <td align="center">{{ number_format($stylewiseTotalQty,0) }} Pcs</td>
            <td align="center">${{ number_format($stylewiseTotalAmount,0) }}</td>
            <td align="center">{{ number_format($stylewiseTotalStdAllwdHr,2) }}</td>
            <td align="center" rowspan="3">{{ $data['remarks'] }}</td>
        </tr>
        <tr>
            <td align="left"><strong>Received</strong></td>
            @foreach ($monthArr as $month=>$month_name)
            <?php
                $rcvqty=isset($monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['rcv_qty'])?$monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['rcv_qty']:0;
                $rcvamount=isset($monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['rcv_amount'])?$monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['rcv_amount']:0;
                $rcv_std_allowed_hr=isset($monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['rcv_std_allowed_hr'])?$monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['rcv_std_allowed_hr']:0;
            ?>
            <td align="right">{{ number_format($rcvqty,0) }} Pcs</td>
            <td align="right">$ {{ number_format($rcvamount,0) }}</td>
            <td align="right">{{ number_format($rcv_std_allowed_hr,0) }}</td>
            <?php
                $stylewiseTotalRcvQty+=$rcvqty;
                $monthwiseTotalRcvQtyArr[$month]=isset($monthwiseTotalRcvQtyArr[$month])?$monthwiseTotalRcvQtyArr[$month]+=$rcvqty:$rcvqty;
                $stylewiseTotalRcvAmount+=$rcvamount;
                $monthwiseTotalRcvAmountArr[$month]=isset($monthwiseTotalRcvAmountArr[$month])?$monthwiseTotalRcvAmountArr[$month]+=$rcvamount:$rcvamount;
                $stylewiseTotalRcvStdAllwdHr+=$rcv_std_allowed_hr;
                $monthwiseTotalRcvStdAllwdHrArr[$month]=isset($monthwiseTotalRcvStdAllwdHrArr[$month])?$monthwiseTotalRcvStdAllwdHrArr[$month]+=$rcv_std_allowed_hr:$rcv_std_allowed_hr;
            ?>
            @endforeach
            <td align="center">{{ number_format($stylewiseTotalRcvQty,0) }} Pcs</td>
            <td align="center">$ {{ number_format($stylewiseTotalRcvAmount,0) }}</td>
            <td align="center">{{ number_format($stylewiseTotalRcvStdAllwdHr,0) }}</td>
            {{-- <td align="center"></td> --}}
        </tr>
        <tr>
            <td align="left"><strong>Yet To Receive</strong></td>
            @foreach ($monthArr as $month=>$month_name)
            <?php
                $yettorcvqty=isset($monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['yet_to_rcv_qty'])?$monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['yet_to_rcv_qty']:0;
                $yettorcvamount=isset($monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['yet_to_rcv_amount'])?$monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['yet_to_rcv_amount']:0;
                $yettorcvstdhr=isset($monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['yet_rcv_std_allowed_hr'])?$monthwiseArr[$data['id']][$data['brand_id']][$key][$month]['yet_rcv_std_allowed_hr']:0;
            ?>
            <td align="right">{{ number_format($yettorcvqty,0) }} Pcs</td>
            <td align="right">$ {{ number_format($yettorcvamount,0) }}</td>
            <td align="right">{{ number_format($yettorcvstdhr,0) }}</td>
            <?php
                $stylewiseTotalQty+=$yettorcvqty;
                $monthwiseTotalYetToRcvQtyArr[$month]=isset($monthwiseTotalYetToRcvQtyArr[$month])?$monthwiseTotalYetToRcvQtyArr[$month]+=$yettorcvqty:$yettorcvqty;
                $stylewiseTotalAmount+=$yettorcvamount;
                $monthwiseTotalYetToRcvAmountArr[$month]=isset($monthwiseTotalYetToRcvAmountArr[$month])?$monthwiseTotalYetToRcvAmountArr[$month]+=$yettorcvamount:$yettorcvamount;
                $stylewiseTotalYetToRcvStdAllwdHr+=$yettorcvstdhr;
                $monthwiseTotalYetToRcvStdAllwdHrArr[$month]=isset($monthwiseTotalYetToRcvStdAllwdHrArr[$month])?$monthwiseTotalYetToRcvStdAllwdHrArr[$month]+=$yettorcvstdhr:$yettorcvstdhr;
            ?>
            @endforeach
            <td align="center">{{ number_format($stylewiseTotalYetToRcvQty,0) }} Pcs</td>
            <td align="center">${{ number_format($stylewiseTotalYetToRcvAmount,0) }}</td>
            <td align="center">{{ number_format($stylewiseTotalYetToRcvStdAllwdHr,0) }}</td>
            
        </tr>
    </tbody>
    @endforeach
    <tfoot>
        <tr>
            <td align="center" colspan="6" rowspan="4"><strong>Total</strong></td>
        </tr>
        <tr>
            <td align="left" ><strong>Forecasted</strong></td>
            @foreach ($monthArr as $month=>$month_name)
            <td align="right"><strong>{{ number_format($monthwiseTotalQtyArr[$month],0) }} Pcs</strong></td>
            <td align="right"><strong>${{ number_format($monthwiseTotalAmountArr[$month],0) }}</strong></td>
            <td align="right"><strong>{{ number_format($monthwiseTotalStdAllwdHrArr[$month],0) }}</strong></td>
            @endforeach
            <td align="right"><strong>{{ number_format(array_sum( $monthwiseTotalQtyArr),0) }} Pcs</strong></td>
            <td align="right"><strong>${{ number_format(array_sum( $monthwiseTotalAmountArr),0) }}</strong></td>
            <td align="right"><strong>{{ number_format(array_sum($monthwiseTotalStdAllwdHrArr),0) }}</strong></td>
            <td align="center" rowspan="3"></td>
        </tr>
        <tr>
            <td align="left" ><strong>Received</strong></td>
            @foreach ($monthArr as $month=>$month_name)
            <td align="right"><strong>{{ number_format($monthwiseTotalRcvQtyArr[$month],0) }} Pcs</strong></td>
            <td align="right"><strong>${{ number_format($monthwiseTotalRcvAmountArr[$month],0) }}</strong></td>
            <td align="right"><strong>{{ number_format($monthwiseTotalRcvStdAllwdHrArr[$month],0) }}</strong></td>
            @endforeach
            <td align="right"><strong>{{ number_format(array_sum( $monthwiseTotalRcvQtyArr),0) }} Pcs</strong></td>
            <td align="right"><strong>${{ number_format(array_sum( $monthwiseTotalRcvAmountArr),0) }}</strong></td>
            <td align="right"><strong>{{ number_format(array_sum( $monthwiseTotalRcvStdAllwdHrArr),0) }}</strong></td>
        </tr>
        <tr>
            <td align="left" ><strong>Yet to Received</strong></td>
            @foreach ($monthArr as $month=>$month_name)
            <td align="right"><strong>{{ number_format($monthwiseTotalYetToRcvQtyArr[$month],0) }} Pcs</strong></td>
            <td align="right"><strong>$ {{ number_format($monthwiseTotalYetToRcvAmountArr[$month],0) }}</strong></td>
            <td align="right"><strong>{{ number_format($monthwiseTotalYetToRcvStdAllwdHrArr[$month],0) }}</strong></td>
            @endforeach
            <td align="right"><strong>{{ number_format(array_sum( $monthwiseTotalYetToRcvQtyArr),0) }} Pcs</strong></td>
            <td align="right"><strong>${{ number_format(array_sum( $monthwiseTotalYetToRcvAmountArr),0) }}</strong></td>
            <td align="right"><strong>{{ number_format(array_sum( $monthwiseTotalYetToRcvStdAllwdHrArr),0) }}</strong></td>
        </tr>
    </tfoot>
</table>