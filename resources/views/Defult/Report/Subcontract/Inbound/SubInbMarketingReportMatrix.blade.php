<h3 align="center">LitheGroup</h3>
<h4 align="center">Order Forcasting</h4>
<?php 
        $width=(count($monthArr)*330)+540;
?>
<table width={{$width}} border="1" cellpadding="2" cellspacing="0">
    <tr>
        <th class="text-center" rowspan="2"><strong>SL</strong></th>
        <th class="text-center" rowspan="2"><strong>Company</strong></th>
        <th class="text-center" rowspan="2"><strong>Prod.Area</strong></th>
        <th class="text-center" rowspan="2"><strong>Team</strong></th>
        <th class="text-center" rowspan="2"><strong>Marketing Member</strong></th>
        <th class="text-center" rowspan="2"><strong>Customer</strong></th>
        <th class="text-center" rowspan="2"><strong>Referred By</strong></th>
        <th class="text-center" rowspan="2"><strong>Status</strong></th>
        @foreach ($monthArr as $month=>$month_name)
        <th class="text-center"  align="center" colspan="3" ><strong>{{ $month_name }}</strong></th>
        @endforeach
        <th class="text-center" colspan="3"><strong>Total</strong></th>
    </tr>
    <tr>
        @foreach ($monthArr as $month=>$month_name)
        <th class="text-center"><strong>Qty</strong></th>
        <th class="text-center"><strong>Avg Price</strong></th>
        <th class="text-center"><strong>Value</strong></th>
        @endforeach
        <th class="text-center"><strong>Qty</strong></th>
        <th class="text-center"><strong>Avg Price</strong></th>
        <th class="text-center"><strong>Value</strong></th>
    </tr>
    <?php 
        $i=1;
        $monthwisetotalQtyArr=[];
        $monthwiseAvgRate=[];
        $monthwiseTotalAmountArr=[];
    ?>
    @foreach ($rows as $row)
    <?php
        $totalQty=0;
        
        $totalAmount=0;
    ?>
        <tbody>
            <tr>
                <td class="text-center" rowspan="3">{{ $i++ }}</td>
                <td class="text-left" rowspan="3">{{ $row->company_code }}</td>
                <td class="text-left" rowspan="3">{{ $row->production_area }}</td>
                <td class="text-left" rowspan="3">{{ $row->team_name }}</td>
                <td class="text-left" rowspan="3">{{ $row->teammember_name }}</td>
                <td class="text-left" rowspan="3">{{ $row->buyer_name }}</td>
                <td class="text-left" rowspan="3">{{ $row->refered_by }}</td>
                <td class="text-left">Forecasted</td>
                @foreach ($monthArr as $month=>$month_name)
                <?php
                    $qty=isset($monthwiseArr[$row->company_id][$row->production_area_id][$row->team_id][$row->teammember_id][$row->buyer_id][$row->refered_by][$month]['qty'])?$monthwiseArr[$row->company_id][$row->production_area_id][$row->team_id][$row->teammember_id][$row->buyer_id][$row->refered_by][$month]['qty']:0;
                    $rate=isset($monthwiseArr[$row->company_id][$row->production_area_id][$row->team_id][$row->teammember_id][$row->buyer_id][$row->refered_by][$month]['rate'])?$monthwiseArr[$row->company_id][$row->production_area_id][$row->team_id][$row->teammember_id][$row->buyer_id][$row->refered_by][$month]['rate']:0;
                    $amount=isset($monthwiseArr[$row->company_id][$row->production_area_id][$row->team_id][$row->teammember_id][$row->buyer_id][$row->refered_by][$month]['amount'])?$monthwiseArr[$row->company_id][$row->production_area_id][$row->team_id][$row->teammember_id][$row->buyer_id][$row->refered_by][$month]['amount']:0;
                ?>
                <td class="text-right">{{ number_format($qty,0) }}</td>
                <td class="text-right">{{ number_format($rate,2) }}</td>
                <td class="text-right">{{ number_format($amount,0) }}</td>
                <?php
                    $totalQty+=$qty;
                    $monthwisetotalQtyArr[$month]=isset($monthwisetotalQtyArr[$month])?$monthwisetotalQtyArr[$month]+=$qty:$qty;
                    $totalAmount+=$amount;
                    $monthwiseTotalAmountArr[$month]=isset($monthwiseTotalAmountArr[$month])?$monthwiseTotalAmountArr[$month]+=$amount:$amount;
                    $avgRate=0;
                    if ($totalQty) {
                        $avgRate=$totalAmount/$totalQty;
                    }
                   
                ?>
                @endforeach
                <td class="text-right">{{ number_format($totalQty,0) }}</td>
                <td class="text-right">{{ number_format($avgRate,2) }}</td>
                <td class="text-right">{{ number_format($totalAmount,0) }}</td>
            </tr>
            <tr>
                <td class="text-left">Received</td>
                @foreach ($monthArr as $month=>$month_name)
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                @endforeach
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
            </tr>
            <tr>
                <td class="text-left">Yet To Receive</td>
                @foreach ($monthArr as $month=>$month_name)
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                @endforeach
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
            </tr>
        </tbody>  
    @endforeach
    <tfoot>
        <tr>
            <td colspan="7" rowspan="3" class="text-center"><strong>Total</strong></td>
            <td class="text-left"><strong>Forecasted</strong></td>
            @foreach ($monthArr as $month=>$month_name)
            <td class="text-right"><strong>{{ number_format($monthwisetotalQtyArr[$month],0) }}</strong></td>
            <td class="text-right"><strong>{{ number_format($monthwiseTotalAmountArr[$month]/$monthwisetotalQtyArr[$month],2) }}</strong></td>
            <td class="text-right"><strong>{{ number_format($monthwiseTotalAmountArr[$month],0) }}</strong></td>
            @endforeach
            <td class="text-right"><strong>{{ number_format(array_sum($monthwisetotalQtyArr),0) }}</td>
            <td class="text-right">{{ number_format(array_sum($monthwiseTotalAmountArr)/array_sum($monthwisetotalQtyArr),2) }}</td>
            <td class="text-right"><strong>{{ number_format(array_sum($monthwiseTotalAmountArr),0) }}</td>
        </tr>
        <tr>
            <td class="text-left"><strong>Received</strong></td>
            @foreach ($monthArr as $month=>$month_name)
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            @endforeach
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
        </tr>
        <tr>
            <td class="text-left"><strong>Yet To Receive</strong></td>
            @foreach ($monthArr as $month=>$month_name)
            <td class="text-right"><strong></strong></td>
            <td class="text-right"><strong></strong></td>
            <td class="text-right"><strong></strong></td>
            @endforeach
            <td class="text-right"><strong></td>
            <td class="text-right"></td>
            <td class="text-right"><strong></td>
        </tr>
    </tfoot>
</table>