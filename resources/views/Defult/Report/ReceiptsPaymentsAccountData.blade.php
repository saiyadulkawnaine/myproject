<table  border="1" style="margin: 0 auto;">
    <thead>
        <tr style="background-color: #EAE9E9">
        <th width="300" class="text-left">Particulars</th>
        <th width="112" class="text-center">A21</th>
        <th width="112" class="text-center">FFL</th>
        <th width="114" class="text-center">LAL</th>
        <th width="114" class="text-center">FDL</th>
        <th width="114" class="text-center">FPL</th>
        <th width="114" class="text-center">LFL</th>
        <th width="114" class="text-center">MIL</th>
        <th width="114" class="text-center">HO</th>
        <th width="114" class="text-center">TOTAL</th>
        </tr>
    </thead>
    <tr><td colspan="10"></td></tr>
    <tr><td colspan="10" style="padding-left: 15px"><strong>Receipts</strong></td></tr>
    <?php
    $monthInflowTotal=0;
    ?>
    @foreach($month_inflow_arr as $monthinflowhead=>$monthinflowvalue)
    <?php
        $head=explode('::',$monthinflowhead);
        $is_multiple=0;
        if($head[0]==0){
        $is_multiple=1;
        }
        else{
        $is_multiple=0;
        }
    ?>
    <tr>
    <td width="300" style="padding-left: 30px"> {{$head[1]}} </td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts({{$head[0]}},2,{{$is_multiple}})">{{isset($monthinflowvalue[2])?number_format($monthinflowvalue[2],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts({{$head[0]}},4,{{$is_multiple}})">{{isset($monthinflowvalue[4])?number_format($monthinflowvalue[4],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts({{$head[0]}},1,{{$is_multiple}})">{{isset($monthinflowvalue[1])?number_format($monthinflowvalue[1],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts({{$head[0]}},5,{{$is_multiple}})">
    {{isset($monthinflowvalue[5])?number_format($monthinflowvalue[5],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts({{$head[0]}},6,{{$is_multiple}})">{{isset($monthinflowvalue[6])?number_format($monthinflowvalue[6],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts({{$head[0]}},41,{{$is_multiple}})">
    {{isset($monthinflowvalue[41])?number_format($monthinflowvalue[41],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts({{$head[0]}},3,{{$is_multiple}})">{{isset($monthinflowvalue[3])?number_format($monthinflowvalue[3],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts({{$head[0]}},21,{{$is_multiple}})">{{isset($monthinflowvalue[21])?number_format($monthinflowvalue[21],0):''}}</a></td>
    <td width="114" align="right">
    <?php
    $monthInflowRowTotal=isset($month_inflow_row_total[$monthinflowhead])?array_sum($month_inflow_row_total[$monthinflowhead]):0;
    $monthInflowTotal+= $monthInflowRowTotal;
    ?>
    <a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts({{$head[0]}},0,{{$is_multiple}})">
    {{number_format($monthInflowRowTotal,0)}}
    </a>
    </td>
    </tr>
    @endforeach

    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Receipts</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts(0,2,0)">{{isset($month_inflow_com_total[2])?number_format(array_sum($month_inflow_com_total[2]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts(0,4,0)">{{isset($month_inflow_com_total[4])?number_format(array_sum($month_inflow_com_total[4]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts(0,1,0)">{{isset($month_inflow_com_total[1])?number_format(array_sum($month_inflow_com_total[1]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts(0,5,0)">{{isset($month_inflow_com_total[5])?number_format(array_sum($month_inflow_com_total[5]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts(0,6,0)">{{isset($month_inflow_com_total[6])?number_format(array_sum($month_inflow_com_total[6]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts(0,41,0)">{{isset($month_inflow_com_total[41])?number_format(array_sum($month_inflow_com_total[41]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts(0,3,0)">{{isset($month_inflow_com_total[3])?number_format(array_sum($month_inflow_com_total[3]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts(0,21,0)">{{isset($month_inflow_com_total[21])?number_format(array_sum($month_inflow_com_total[21]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.receipts(0,0,0)">{{number_format($monthInflowTotal,0)}}</a></strong></td>
    </tr>

    <tr><td colspan="10" style="padding-left: 15px"><strong>Payments</strong></td></tr>
    <?php
    $monthOutflowTotal=0;
    ?>
    @foreach($month_outflow_arr as $monthoutflowhead=>$monthoutflowvalue)
    <?php
    $head=explode('::',$monthoutflowhead);
    $is_multiple=0;
    if($head[0]==0){
        $is_multiple=1;
    }
    else{
        $is_multiple=0;
    }
    ?>
    <tr>
    <td width="300" style="padding-left: 30px"> {{$head[1]}} </td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments({{$head[0]}},2,{{$is_multiple}})">{{isset($monthoutflowvalue[2])?number_format($monthoutflowvalue[2],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments({{$head[0]}},4,{{$is_multiple}})">
    {{isset($monthoutflowvalue[4])?number_format($monthoutflowvalue[4],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments({{$head[0]}},1,{{$is_multiple}})">{{isset($monthoutflowvalue[1])?number_format($monthoutflowvalue[1],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments({{$head[0]}},5,{{$is_multiple}})">{{isset($monthoutflowvalue[5])?number_format($monthoutflowvalue[5],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments({{$head[0]}},6,{{$is_multiple}})">{{isset($monthoutflowvalue[6])?number_format($monthoutflowvalue[6],0):''}}</a></td>
    <td width="114" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments({{$head[0]}},41,{{$is_multiple}})">
    {{isset($monthoutflowvalue[41])?number_format($monthoutflowvalue[41],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments({{$head[0]}},3,{{$is_multiple}})">{{isset($monthoutflowvalue[3])?number_format($monthoutflowvalue[3],0):''}}</a></td>
    <td width="112" align="right"><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments({{$head[0]}},21,{{$is_multiple}})">{{isset($monthoutflowvalue[21])?number_format($monthoutflowvalue[21],0):''}}</a></td>
    <td width="114" align="right">
    <?php
    $monthOutflowRowTotal=isset($month_outflow_row_total[$monthoutflowhead])?array_sum($month_outflow_row_total[$monthoutflowhead]):0;
    $monthOutflowTotal+=$monthOutflowRowTotal;
    ?>
    <a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments({{$head[0]}},0,{{$is_multiple}})">
    {{number_format($monthOutflowRowTotal,0)}}
    </a>
    </td>
    </tr>
    @endforeach

    <tr style="background-color: #EAE9E9">
    <td width="300" style="padding-left: 15px"><strong>Total Payments</strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments(0,2,0)">{{isset($month_outflow_com_total[2])?number_format(array_sum($month_outflow_com_total[2]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments(0,4,0)">{{isset($month_outflow_com_total[4])?number_format(array_sum($month_outflow_com_total[4]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments(0,1,0)">{{isset($month_outflow_com_total[1])?number_format(array_sum($month_outflow_com_total[1]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments(0,5,0)">{{isset($month_outflow_com_total[5])?number_format(array_sum($month_outflow_com_total[5]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments(0,6,0)">{{isset($month_outflow_com_total[6])?number_format(array_sum($month_outflow_com_total[6]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments(0,41,0)">{{isset($month_outflow_com_total[41])?number_format(array_sum($month_outflow_com_total[41]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments(0,3,0)">{{isset($month_outflow_com_total[3])?number_format(array_sum($month_outflow_com_total[3]),0):''}}</a></strong></td>
    <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments(0,21,0)">{{isset($month_outflow_com_total[21])?number_format(array_sum($month_outflow_com_total[21]),0):''}}</a></strong></td>
    <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong><a href="javascript:void(0)" onClick="MsReceiptsPaymentsAccount.payments(0,0,0)">{{number_format($monthOutflowTotal,0)}}</a></strong></td>
    </tr>
</table>
