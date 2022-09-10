<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th width="30px"align="center" colspan="4">Variable Expenses</th>
    </tr>
    <tr align="center">
        <th width="30px"align="center">SL</th>
        <th width="350px"align="center">Particulars</th>
        <th width="350px"align="center">Data Source and Proportion Basis</th>
        <th width="30px"align="center">Amount/Month</th>
        <th width="30px"align="center">Amount/Day</th>
    </tr>
    <?php
    $i=1;
    $vtamountmonth=0;
    $vtamountday=0;
    ?>
    @foreach($vexpense as $row)
    <tr align="left">
        <td width="30px">{{$i}}</td>
        <td width="350px">{{$row->name}}</td>
        <td width="350px">{{$row->remarks}}</td>
        <td width="30px"align="right">{{number_format($row->amount,2)}}</td>
        <td width="30px"align="right">{{number_format($row->per_day,2)}}</td>
    </tr>
    <?php
    $vtamountmonth+=$row->amount;
    $vtamountday+=$row->per_day;
    $i++;
    ?>
   @endforeach
   <tr align="center" style="font-weight: bold;">
        <td width="30px"align="right" colspan="3">Total</td>
        <td width="30px"align="right">{{number_format($vtamountmonth,2)}}</td>
        <td width="30px"align="right">{{number_format($vtamountday,2)}}</td>
   </tr>
</table>
<br/>
<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th width="30px"align="center" colspan="4">Fixed Expenses</th>
    </tr>
    <tr align="center">
        <th width="30px"align="center">SL</th>
        <th width="350px"align="center">Particulars</th>
        <th width="350px"align="center">Data Source and Proportion Basis</th>
        <th width="30px"align="center">Amount/Month</th>
        <th width="30px"align="center">Amount/Day</th>
    </tr>
     <tr align="left">
        <td width="30px">1</td>
        <td width="350px">OT (Operator + Helper)</td>
        <td width="350px"></td>
        <td width="30px"align="right">{{number_format($attendences->amount,2)}}</td>
        <td width="30px"align="right">{{number_format($attendences->per_day,2)}}</td>
    </tr>
    <?php
    $i=2;
    $ftamountmonth=$attendences->amount;
    $ftamountday=$attendences->per_day;
    ?>
    @foreach($fexpense as $row)
    <tr align="left">
        <td width="30px">{{$i}}</td>
        <td width="350px">{{$row->name}}</td>
        <td width="350px">{{$row->remarks}}</td>
        <td width="30px"align="right">{{number_format($row->amount,2)}}</td>
        <td width="30px"align="right">{{ number_format($row->per_day,2)}}</td>
    </tr>
    <?php
    $ftamountmonth+=$row->amount;
    $ftamountday+=$row->per_day;
    $i++;
    ?>
   @endforeach
<tr align="center">
<td width="30px"align="right" colspan="3" style="font-weight: bold;">Total</td>

<td width="30px"align="right">{{number_format($ftamountmonth,2)}}</td>
<td width="30px"align="right">{{number_format($ftamountday,2)}}</td>
</tr>
<tr align="center">
<td width="30px"align="right" colspan="3" style="font-weight: bold;">Grand Total Expenses In Taka</td>
<td width="30px"align="right">{{number_format($ftamountmonth+$vtamountmonth,2)}}</td>
<td width="30px"align="right">{{number_format($ftamountday+$vtamountday,2)}}</td>
</tr>
<tr align="center">
<td width="30px"align="right" colspan="3" style="font-weight: bold;">Grand Total Expenses In USD ( Exch. Rate: {{$exch_rate}} )</td>
<td width="30px"align="right">{{number_format(($ftamountmonth+$vtamountmonth)/$exch_rate,2)}}</td>
<td width="30px"align="right">{{number_format(($ftamountday+$vtamountday)/$exch_rate,2)}}</td>
</tr>
</table>
